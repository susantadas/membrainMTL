<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Excel; //csv file reader
use DB;
use File;
use App\Alert;
use App\Quarantine; //Model name and Where model present
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class ProcessCSV extends Command
{
    protected $signature = 'processcsv:process';
    protected $description = 'CSV Processing and download';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        //echo "hello this is process csv cron job";
        /*$files = File::allFiles(app_path('public/download'));
        foreach ($files as $file)
        {
            echo (string)$file, "\n";
        }*/
        //Storage::listContents(public_path().'/public/download/');

        //$path = public_path('\download'); 
        $suppliers = DB::table('suppliers')->select('id','public_id','name','contact_email','error_allowance','return_csv')->get();
        //dd($suppliers);
        $dir_counter = 0;
        foreach($suppliers as $each_sup){
            $current_sup_id = $each_sup->id;
            $current_sup_email = $each_sup->contact_email;
            $current_sup_name = $each_sup->name;
            $current_sup_error_count = $each_sup->error_allowance;
            $current_sup_return_csv = $each_sup->return_csv;
            $current_sup_pub_id = $each_sup->public_id;
            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
            //$sup_id_added_path = 'process_dir\\'.$current_sup_pub_id;
            $path = base_path($sup_id_added_path);
            if(is_dir($path) && ! $this->is_dir_empty($path))
            {
                $latest_ctime = 0;
                $latest_filename = '';    
                $client_csv = array();
                $return_csv = array();
                $ret_csv_original_rec = array();
                $ret_csv_original_rec2 = array();
                $d = dir($path);
                while (false !== ($entry = $d->read())) {
                  $filepath = "{$path}/{$entry}";
                  // could do also other checks than just checking whether the entry is a file
                  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
                    $latest_ctime = filectime($filepath);
                    $latest_filename = $entry;
                  }
                } // while
                $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
                $arr = explode("_", $file_ext_removed, 2);
                $filename_campaign_id = $arr[0];
                //$campaigns = DB::table('campaigns')->select('id','public_id','client_id','parameter_mapping')->get();
                $campaigns = DB::table('campaigns AS ca')
                                ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                                ->select('ca.*', 'cl.name AS client_name') 
                                ->orderBy('ca.name', 'asc')
                                ->get();
                //dd($campaigns);
                foreach($campaigns as $each_cam){
                    $current_camp_id = $each_cam->id;
                    $current_client_id = $each_cam->client_id;
                    $current_camp_name = $each_cam->name;
                    $current_client_name = $each_cam->client_name;
                    $current_camp_server_param = json_decode($each_cam->server_parameters);
                    //echo $current_camp_server_param;
                    $curr_camp_param_map = $each_cam->parameter_mapping;
                    $curr_camp_public_id = $each_cam->public_id;
                    if($filename_campaign_id == $each_cam->public_id){
                        echo "\tFile found is: ".$latest_filename;
                        $delimiter = $this->getFileDelimiter($path, $latest_filename, 5);
                        echo "\nDelimiter is: ".$delimiter;
                        if($delimiter != '\t'){
                            echo "\nFile is not Tab delimited.";
                            /* generating alert */
                            $alert = new Alert;
                            $alert->supplier_id = $current_sup_id;
                            $alert->subject = "File Quarantined – Invalid Delimiters";
                            $alert->body = "File Quarantined – Invalid Delimiters";
                            $alert->filename = $latest_filename;
                            $alert->login_usernme = '';
                            $alert->acknowledged = '1';
                            $alert->created = date('Y-m-d H:i:s');
                            if($alert->save()){
                                echo "\nAlert record generated.";
                            }

                            /* adding a quarantine record */
                            $quarantine = new Quarantine;
                            $quarantine->supplier_id = $current_sup_id;
                            $quarantine->client_id = $current_client_id;
                            $quarantine->campaign_id = $current_camp_id;
                            $quarantine->reason = 'Invalid Delimiters';
                            $quarantine->filename = $latest_filename;
                            $quarantine->created = date('Y-m-d H:i:s');
                            if($quarantine->save()){
                                echo "\nQuarantine record generated, File Quarantined.";
                            }

                            $source_path = $path;
                            $dest_path = public_path().'/qurantine-file/' .$quarantine->id;
                            File::makeDirectory($dest_path, $mode = 0777, true, true);
                            File::cleanDirectory($dest_path);
                            File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);
                            //File::move($source_path.'\\'.$latest_filename, $dest_path.'\\'.$latest_filename);
                            
                            /* Sending Mail to supplier */
                            //$emailid = $current_sup_email;
                            $emailid = 'chinthala.prasad@mindtechlabs.com';
                            
                            \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                $message->from('isusantakumardas@gmail.com', 'Susanta');
                                $message->to($emailid,$current_sup_name);
                                $message->subject('CSV File Quarantined');
                            });
                            if( count(\Mail::failures()) > 0 ) {
                                echo "\nSome problem occurred. Email has not been sent to Supplier.";
                               /*echo "There was one or more failures. They were: <br />";
                               foreach(Mail::failures as $email_address) {
                                   echo " - $email_address <br />";
                                }*/
                            } else {
                                echo "\nEmail has been sent to Supplier.";
                            }
                        }else{
                            echo "\tFile is Tab Delimited.\n";
                            $camp_csv_data = json_decode($curr_camp_param_map);
                            $csv_keys = array_keys((array)$camp_csv_data);
                            array_push($csv_keys,"supplier_id","client_id","campaign_id");
                            //$file_path = $path.'\\'.$latest_filename;
                            //dd($csv_keys);
                            $file_path = $path.'/'.$latest_filename;
                            //echo $file_path;
                            $fp = fopen($file_path, "r"); //open the file 
                           
                            //$data = fgetcsv($fp, filesize($file_path), "\t");  
                            $a = file_get_contents( $file_path ); 
                            //echo $a;
                            fclose($fp); //close file  
                            $lines = explode( "\r\n" , $a );
                            //dd($lines);
                            $each_record = array();
                            foreach ($lines as $key => $value) {
                                $each_record[] = explode("\t", $value);
                            } 
                            //dd($each_record);
                            $mappings = array(); 
                            foreach ($each_record as $key => $value) {
                                if(count($value) > 1){
                                    array_push($value, $current_sup_id, $current_client_id, $current_camp_id);
                                    $f_csvdata_array = array_values($value);
                                    //dd($f_csvdata_array);
                                    $mappings[] = array_combine((array)$csv_keys, $f_csvdata_array);
                                    //dd($mappings);
                                    
                                }
                            }
                            //dd($mappings);
                            $successful_lead=0;
                            $invalid_lead=0;
                            $duplicate_lead=0;
                            $arr_res=array();
                            foreach ($mappings as $key => $each_mapping) {
                                //dd($each_mapping);
                                $client = new Client();
                                $res = $client->post('http://ec2-13-126-3-130.ap-south-1.compute.amazonaws.com/membraindev/public/api/v1/leadprocessing/getdata', ['form_params'=>$each_mapping]);
                                if($res->getStatusCode()=='200'){
                                   $result = $res->getBody();
                                }

                                $f_result = json_decode($result);
                                //dd($f_result);
                                if($f_result->returnData->lead_disposition->success == 'true'){
                                     $datanew = [$f_result->returnData->email,$f_result->returnData->phone,$f_result->returnData->title,$f_result->returnData->firstName,$f_result->returnData->lastName,$f_result->returnData->birthdate,$f_result->returnData->age,$f_result->returnData->ageRange,$f_result->returnData->gender,$f_result->returnData->address1,$f_result->returnData->address2,$f_result->returnData->city,$f_result->returnData->state,$f_result->returnData->postcode,$f_result->returnData->countryCode];
                                      $client_csv[] = $datanew;
                                      //$each_mapping['lead_status']='Accepted';
                                      $cli_csv_original_rec[] = $each_mapping;
                                       $each_mapping['lead_status']='Accepted';
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $return_csv[] = array_values($datanew);
                                      $successful_lead++;
                            
                                }else{
                                    if(is_array($f_result->returnData->lead_disposition->error)){

                                        $error_arr=array();
                                        $error_arr=implode(',',$f_result->returnData->lead_disposition->error);
                                        $returndata = [$f_result->returnData->email,$f_result->returnData->phone,$f_result->returnData->title,$f_result->returnData->firstName,$f_result->returnData->lastName,$f_result->returnData->birthdate,$f_result->returnData->age,$f_result->returnData->ageRange,$f_result->returnData->gender,$f_result->returnData->address1,$f_result->returnData->address2,$f_result->returnData->city,$f_result->returnData->state,$f_result->returnData->postcode,$f_result->returnData->countryCode,$error_arr];   
                                        $return_csv[] = array_values($returndata);
                                        $each_mapping['lead_status']='Invalid';
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $invalid_lead++;

                                    }else{

                                        // $error_arr=array();
                                        // $error_arr=implode(',',$f_result->returnData->lead_disposition->error);
                                        $returndata = [$f_result->returnData->email,$f_result->returnData->phone,$f_result->returnData->title,$f_result->returnData->firstName,$f_result->returnData->lastName,$f_result->returnData->birthdate,$f_result->returnData->age,$f_result->returnData->ageRange,$f_result->returnData->gender,$f_result->returnData->address1,$f_result->returnData->address2,$f_result->returnData->city,$f_result->returnData->state,$f_result->returnData->postcode,$f_result->returnData->countryCode];   
                                        $each_mapping['lead_status']='Duplicate Consumer';
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $return_csv[] = array_values($returndata);
                                        $duplicate_lead++;
                                    }
                                }//else
                                $f_detection_data = array("supplier_id"=>$current_sup_id,"source"=>$latest_filename,"client_id"=>$current_client_id,"campaign_id"=>$current_camp_id,"email_address"=>$f_result->returnData->email,"phone"=>$f_result->returnData->phone,"first_name"=>$f_result->returnData->firstName,"last_name"=>$f_result->returnData->lastName,"postcode"=>$f_result->returnData->postcode,"received"=>date('Y-m-d H:i:s'));
                                    $result = DB::table('fraud_detection')->insert(array($f_detection_data));
                            }//foreach($mappings)
                            //dd($ret_csv_original_rec);
                            //dd($ret_csv_original_rec2);
                            //dd($client_csv);
                            //dd($return_csv);
                            /* Creating Return-CSV Excel sheet */
                            $return_csv_filename = $curr_camp_public_id.'_Return-CSV';
                            $client_csv_filename = $curr_camp_public_id.'_Client-CSV';
                            $camp_id_ret_csv_path = 'process_dir/temp/'.$return_csv_filename;
                            $camp_id_cli_csv_path = 'process_dir/temp/'.$client_csv_filename;
                            $ret_csv_file_path = base_path($camp_id_ret_csv_path);
                            $cli_csv_file_path = base_path($camp_id_cli_csv_path);
                            //$path = base_path('process_dir/temp/exports/Return-CSV.csv');
                            if(!empty($return_csv)){
                                if(!File::exists($ret_csv_file_path)){
                                    Excel::create($return_csv_filename, function($excel) use ($ret_csv_original_rec){
                                        $excel->sheet('Return-CSV', function($sheet) use ($ret_csv_original_rec) {
                                            $sheet->fromArray($ret_csv_original_rec);
                                        });
                                    })->store('csv', base_path('process_dir/temp'));
                                    //Directory does not exist, so lets create it.
                                    //$result = File::makeDirectory($ret_csv_file_path, $mode = 0777, true, true);
                                    //echo "\nfrauddetections directory created with supplier id.";
                                }
                            }//if
                            /* Creating Client-CSV Excel sheet */
                            if(!empty($client_csv)){
                                Excel::create($client_csv_filename, function($excel) use ($cli_csv_original_rec){
                                    $excel->sheet('Client-CSV',function($sheet) use ($cli_csv_original_rec)
                                    {
                                        $sheet->fromArray($cli_csv_original_rec);
                                    });
                                })->store('csv', base_path('process_dir/temp'));
                            }//if
                            $total_error = ($invalid_lead+$duplicate_lead);
                            //echo $total_error;  
                            if($total_error > $current_sup_error_count){
                                /* START: generating alert */
                                $alert = new Alert;
                                $alert->supplier_id = $current_sup_id;
                                $alert->subject = "File Quarantined – Excessive Errors";
                                $alert->body = "Supplier name - ".$current_sup_name."Campaign name - ".$current_camp_name."Client name - ".$current_client_name;
                                $alert->filename = $latest_filename;
                                $alert->login_usernme = '';
                                $alert->acknowledged = '1';
                                $alert->created = date('Y-m-d H:i:s');
                                if($alert->save()){
                                    echo "\nAlert record generated.";
                                }
                                /* END: generating alert */
                                /* START: adding a quarantine record */
                                $quarantine = new Quarantine;
                                $quarantine->supplier_id = $current_sup_id;
                                $quarantine->client_id = $current_client_id;
                                $quarantine->campaign_id = $current_camp_id;
                                $quarantine->reason = 'Excessive Errors';
                                $quarantine->filename = $latest_filename;
                                $quarantine->created = date('Y-m-d H:i:s');
                                if($quarantine->save()){
                                    echo "\nQuarantine record generated, File Quarantined.";
                                }
                                /* END: adding a quarantine record */
                                /* START: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                $ret_csv_quarantine_path = public_path().'/qurantine-file/' .$quarantine->id;
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/temp/'.$return_csv_filename_qua;
                                $ret_csv_file_path_qua = base_path($camp_id_ret_csv_path_qua);
                                File::makeDirectory($ret_csv_quarantine_path, $mode = 0777, true, true);
                                File::cleanDirectory($ret_csv_quarantine_path);
                                File::move($ret_csv_file_path_qua, $ret_csv_quarantine_path.'/'.$return_csv_filename_qua);
                                /* END: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                /* START: Sending Email to supplier */
                                //$emailid = $current_sup_email;
                                $emailid = 'chinthala.prasad@mindtechlabs.com';
                                \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV File Quarantined');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                   /*echo "There was one or more failures. They were: <br />";
                                   foreach(Mail::failures as $email_address) {
                                       echo " - $email_address <br />";
                                    }*/
                                } else {
                                    echo "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                if($current_sup_return_csv == 1){
                                    $dest_path = base_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_quarantine_path.'/'.$return_csv_filename_qua, $dest_path.'/'.$return_csv_filename_qua);
                                }                
                            }else{
                                echo "\tcoming to else";
                                /* START: Client-CSV is delivered to the Client, using the server information stored in the “server_parameters” field in the “campaign” table */
                                /* START: Send file via sftp to server */
                                /*$strServer = $current_camp_server_param->server;
                                $strServerPort = "22";
                                $strServerUsername = $current_camp_server_param->user;
                                $strServerPassword = $current_camp_server_param->password;
                                $client_csv_filename = $curr_camp_public_id.'_Client-CSV.csv';
                                $camp_id_cli_csv_path = 'process_dir/temp/'.$client_csv_filename;
                                $cli_csv_file_path = base_path($camp_id_cli_csv_path);
                                $dest_file_path = base_path()."/".$current_camp_server_param->directory;
                                echo "\nServer:".$strServer;
                                echo "\nUser:".$strServerUsername;
                                echo "\nPassword:".$strServerPassword;
                                echo "\nbefore if".$dest_file_path;
                                if(!File::exists($dest_file_path)) {
                                    echo "\tinside if() directory create";
                                    // path does not exist
                                    File::makeDirectory($dest_file_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_file_path);
                                }
                                echo "\tafter if()";
                                $ftp_server=$strServer;
                                 $ftp_user_name=$strServerUsername;
                                 $ftp_user_pass=$strServerPassword;
                                 $localFile = $dest_file_path."/".$client_csv_filename; //tobe uploaded
                                 //$remote_file = $dest_file_path."/".$client_csv_filename;
                                 $remote_file = "/home/midclass/public_html/".$client_csv_filename;

                                 // upload a file using ftp server
                                 // set up basic connection
                                 $conn_id = ftp_connect($ftp_server);

                                 // login with username and password
                                 $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

                                 // upload a file using ftp server
                                 if (ftp_put($conn_id, $remote_file, $localFile, FTP_ASCII)) {
                                    echo "successfully uploaded $localFile\n";
                                    //exit;
                                 } else {
                                    echo "There was a problem while uploading $localFile\n";
                                    //exit;
                                    }
                                 // close the connection
                                 ftp_close($conn_id);*/
                                 /* END: Send file via sftp to server */

                                 // START: connect to sftp server using laravel SSH package
                                /*\Config::set('remote.connections.runtime.host', $strServer);
                                \Config::set('remote.connections.runtime.port', '22');
                                \Config::set('remote.connections.runtime.username', $strServerUsername);
                                \Config::set('remote.connections.runtime.password', $strServerPassword);

                                \SSH::into('runtime')->run(array(
                                    'cd /var/www',
                                    'git pull origin master',
                                ));
                                $localFile = $dest_file_path."/".$client_csv_filename;
                                $remotePath = $dest_file_path."/".$client_csv_filename;
                                \SSH::into('staging')->put($localFile, $remotePath);*/
                                // END: connect to sftp server using laravel SSH package
                                /* END: Client-CSV is delivered to the Client, using the server information stored in the “server_parameters” field in the “campaign” table  */

                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                //$ret_csv_quarantine_path = public_path().'/qurantine-file/' .$quarantine->id;
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/temp/'.$return_csv_filename_qua;
                                $ret_csv_file_path_qua = base_path($camp_id_ret_csv_path_qua);
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                if($current_sup_return_csv == 1){
                                    $dest_path = base_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_file_path_qua, $dest_path.'/'.$new_ret_csv_filename);
                                } 
                                /* START: Sending Email to supplier */
                                //$emailid = $current_sup_email;
                                $emailid = 'chinthala.prasad@mindtechlabs.com';
                                \Mail::send('mailtemplate.sendSuccessProcessCSVInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV file has been processed');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                   /*echo "There was one or more failures. They were: <br />";
                                   foreach(Mail::failures as $email_address) {
                                       echo " - $email_address <br />";
                                    }*/
                                } else {
                                    echo "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */               
                            }
                        }
                    }
                }//foreach($campaigns)
            }//if(is_dir())
            //echo "\thello";exit;
        }
    } // handle()
    public function is_dir_empty($dir) 
    {
      if (!is_readable($dir)) return NULL; 
      $handle = opendir($dir);
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          return FALSE;
        }
      }
      return TRUE;
    } // is_dir_empty();
    public function getFileDelimiter($newpath, $file, $checkLines = 2){
        $filepath = $newpath.'/'.$file;
        //$file = new SplFileObject($file);
        //$file = Excel::load($filepath, function($reader) {})->get();
        //$file = fopen($filepath, 'r');
        $file = new \SplFileObject($filepath);
        $delimiters = array(
          ',',
          '\t',
          ';',
          '|',
          ':'
        );
        $results = array();
        $i = 0;
         while($file->valid() && $i <= $checkLines){
            $line = $file->fgets();
            foreach ($delimiters as $delimiter){
                $regExp = '/['.$delimiter.']/';
                $fields = preg_split($regExp, $line);
                if(count($fields) > 1){
                    if(!empty($results[$delimiter])){
                        $results[$delimiter]++;
                    } else {
                        $results[$delimiter] = 1;
                    }   
                }
            }
           $i++;
        }
        $results = array_keys($results, max($results));
        //print_r($results);
        return $results[0];
    }// getFileDelimiter();
}