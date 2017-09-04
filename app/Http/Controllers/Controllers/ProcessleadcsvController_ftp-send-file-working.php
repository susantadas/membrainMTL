<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Leadaudit;
use App\Client as MClient;
use App\Campaign;
use DB;
use Excel;
use File;
use App\Alert;
use App\Quarantine; //Model name and Where model present
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class ProcessleadcsvController extends Controller
{
    /* Start: authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* End: authentication check */
    /* Start: Shows the process lead csv form */
    public function index()
    {
        //$leads = DB::table('lead_audit')->get();
        if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        {
            $clients = MClient::all();
            return view('processleadcsv.list', ['clients'=>$clients]);
        }
        else if(Auth::user()->role_id==5)
        {
            $user_id = Auth::user()->id;

            $clients = DB::table('portal_sub_client AS ps')
                        ->join('clients AS cl', 'ps.client_id', '=', 'cl.id')
                        ->select('cl.*')
                        ->where('ps.portal_user_id', '=', $user_id)
                        ->get();
            return view('processleadcsv.list', ['clients'=>$clients]);
        }
        else if(Auth::user()->role_id==4)
        {
            $client_id = Auth::user()->client_id;

            $campaigns = DB::table('campaigns')
                         ->where('client_id', '=', $client_id)
                         ->where('active', '=', '1')
                         ->get();   

            $clients = DB::table('clients')
                        ->where('id', '=', $client_id)
                        ->get();

            return view('processleadcsv.list', ['campaigns' => $campaigns, 'clients'=>$clients]);
           /* $data = DB::table('campaigns AS ca')
                    ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                    ->select('ca.*', 'cl.name AS client_name') 
                    ->where('cl.id', '=', $client_id)
                    ->where('ca.client_id', '=', $client_id)
                    ->get();*/
        }
        else
        {
            return abort(404); //redirect user to resources/views/errors/404.blade.php
        }
    }
    /* End: Shows the process lead csv form */
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        dd($request->all());  
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    /* Start: process the uploaded CSV file after user uploads the file */
    public function processcsv(Request $request)
    {
        $error = array();
        $succe = array();
        $dup = array();
        $empty = 1;
        $delimiter = 1;
        $total_records = 0;
        $status=1;
      
        if($request->hasFile('myfile'))
        {
            $file_path = $request->file('myfile')->getRealPath();
            $filename = $request->file('myfile')->getClientOriginalName();
            $file_temp_name = $_FILES['myfile']['tmp_name'];
            $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            /*echo $filename;
            echo $file_ext_removed;echo $file_path;
            echo $_FILES['myfile']['tmp_name'];*/ 
            
            $arr = explode("_", $file_ext_removed, 2);
            //$arr = explode(".", $filename, 2);
            $newfile_name = $arr[0];
            //return array('filename' => $filename);
            $public_id = Campaign::where('id','=',$request->input('id'))->get(['public_id']);            
            /*print_r($public_id);
            echo $public_id[0]->public_id;  */
            //dd(DB::getQueryLog());exit;              
            //$name_matches = Campaign::where('public_id','LIKE',"%{$newfile_name}%")->get();
            if($public_id[0]->public_id != $newfile_name){
                //echo "filename doesn't matches";
                $name_accept = 0;
                $status=0;
            }else{
                $name_accept = 1;
                //$file_path = $request->file('myfile')->getRealPath();
                /*$data = Excel::load($file_path, function($reader) { 
                    $reader->setDelimiter('\t'); 
                })->get();*/
                //$delimiter = $this->getFileDelimiter($file_path, $filename, 5);
                /*echo "<pre>";
                print_r($data);
                echo $data->count();
                echo "</pre>";*/
                //if( !empty($data) && $data->count() > 0 ){
                //if($file_as_str = file_get_contents($_FILES['myfile']['tmp_name']) !== false )
                if(filesize($file_path)) {
                    // your file is not empty
                    //$fh = fopen($file_path, 'r');
                    $a = file_get_contents($_FILES['myfile']['tmp_name']);
                    $lines = explode( "\r\n" , $a );
                    //dd($lines);
                    $each_record = array();
                    foreach ($lines as $key => $value) {
                        $each_record[] = explode("\t", $value);
                        if(count($value) > 0 && substr_count($value, "\t") > 5){
                            //echo "File is Tab delimited";
                            $delimiter++;
                            $total_records++;
                        }
                    }
                    /* checking if Tab delimiter is present in every Line then  */
                    if(count($lines) == $delimiter){
                        $delimiter = 1;
                        $status=1;
                        $temp_path = base_path("supplier_files/temp/");
                        if(!File::exists($temp_path)) {
                            // path does not exist
                            File::makeDirectory($temp_path, $mode = 0777, true, true);
                            File::cleanDirectory($temp_path);
                        }
                        //$move = File::move($file_path.'/'.$file_temp_name, $temp_path.'/'.$filename);
                        if(move_uploaded_file($_FILES['myfile']['tmp_name'], $temp_path. $_FILES["myfile"]['name'])) {
                            //echo "Uploaded";

                        } else {
                           //echo "File was not uploaded";
                        }
                        /*if($move == 1){
                            //echo "File hasbeen moved to temporary path";
                        }*/
                    }
                    else
                    {
                        //echo "else";exit;
                        $delimiter = 0;
                        $status=0;
                    }
                } 
                else{
                    $empty=0;
                    $status=0;
                }
            }
        }
        return array('filename' => $name_accept,'delimiter' => $delimiter, 'status'=>$status,'total'=>$total_records,'empty'=>$empty,'cid'=>$request->input('id'));
    }
    /* End: process the uploaded CSV file after user uploads the file */
    public function csvProcessingFromPortal(Request $request){
        set_time_limit(0);
        $Response = "";
        $campaign_id = $request->input('camp_id');
        $query = Campaign::where('id','=',$campaign_id)->get(['public_id']);
        //dd($query);
        $public_id = $query[0]->public_id;
        //echo $public_id;die;
        $temp_path = base_path("supplier_files/temp");
        $path = base_path("process_dir/portal_csv_upload");
        if(!File::exists($path)) {
            // path does not exist
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* START: checking for uploaded files in temp directory */
        $latest_ctime = 0;
        $latest_filename = '';    
        $client_csv = array();
        $return_csv = array();
        $ret_csv_original_rec = array();
        $ret_csv_original_rec2 = array();
        $d = dir($temp_path);
        while (false !== ($entry = $d->read())) {
          $filepath = "{$temp_path}/{$entry}";
          // could do also other checks than just checking whether the entry is a file
          if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
          }
        } // while
        $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
        $arr = explode("_", $file_ext_removed, 2);
        $filename_campaign_id = $arr[0];
        /* END: checking for uploaded files */
        /* Moving csv file to process_dir/portal_csv_upload/) */
        if (strpos($filename_campaign_id, $public_id) !== false) {
            //echo 'true';
            $move = File::move($temp_path.'/'.$latest_filename, $path.'/'.$latest_filename);
            /*if($move == 1){
                return 1;
            }else{
                return 0;
            }*/
            $campaign = DB::table('campaigns AS ca')
                                ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                                ->select('ca.*', 'cl.name AS client_name') 
                                ->where('ca.id', '=', $campaign_id)
                                ->get();
                //dd($campaign);
                //foreach($campaigns as $each_cam){
                    $current_sup_name = "Supplier name:empty(because no supplier_id)";                                
                    $current_camp_id = $campaign[0]->id;
                    $current_client_id = $campaign[0]->client_id;
                    $current_camp_name = $campaign[0]->name;
                    $current_client_name = $campaign[0]->client_name;
                    $current_camp_server_param = json_decode($campaign[0]->server_parameters);
                    //echo $current_camp_server_param;
                    $curr_camp_param_map = $campaign[0]->parameter_mapping;
                    $curr_camp_public_id = $campaign[0]->public_id;
                    if($filename_campaign_id == $campaign[0]->public_id){
                        //echo "\tFile found is: ".$latest_filename;
                        $Response .= "\tFile found is: ".$latest_filename;
                        $delimiter = $this->getFileDelimiter($path, $latest_filename, 5);
                        //echo "\nDelimiter is: ".$delimiter;
                        $Response .= "\nDelimiter is: ".$delimiter;
                        if($delimiter == '\t' && $delimiter!='0'){
                             //echo "\tFile is Tab Delimited.\n";
                            $Response .= "\tFile is Tab Delimited.\n";
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
                                    array_push($value, '0', $current_client_id, $current_camp_id);
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
                                $current_sup_id = 0; //because there is no supplier_id 
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
                            //$camp_id_ret_csv_path = 'process_dir/temp/'.$return_csv_filename;
                            $camp_id_ret_csv_path = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename;
                            $camp_id_cli_csv_path = 'process_dir/portal_csv_upload/temp/'.$client_csv_filename;
                            $ret_csv_file_path = base_path($camp_id_ret_csv_path);
                            $cli_csv_file_path = base_path($camp_id_cli_csv_path);
                            //$path = base_path('process_dir/temp/exports/Return-CSV.csv');
                            if(!empty($return_csv)){
                                if(!File::exists($ret_csv_file_path)){
                                    Excel::create($return_csv_filename, function($excel) use ($ret_csv_original_rec){
                                        $excel->sheet('Return-CSV', function($sheet) use ($ret_csv_original_rec) {
                                            $sheet->fromArray($ret_csv_original_rec);
                                        });
                                    })->store('csv', base_path('process_dir/portal_csv_upload/temp'));
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
                                })->store('csv', base_path('process_dir/portal_csv_upload/temp'));
                            }//if
                            $total_error = ($invalid_lead+$duplicate_lead);
                            //echo $total_error;  
                            $percentage = 10;
                            $percent_result = ($percentage / 100) * $total_error;
                            $current_sup_error_count = 10; //Declared statically for now because there is no supplier_id
                            if($percent_result > $current_sup_error_count){
                                $Result .= "\nThe ratio of invalid leads exceeds the “error_allowance” threshold (defined in the “supplier” table)";
                                /* START: generating alert */
                                $alert = new Alert;
                                //$alert->supplier_id = $current_sup_id;
                                $alert->subject = "File Quarantined – Excessive Errors";
                                $alert->body = "Supplier name - ".$current_sup_name."Campaign name - ".$current_camp_name."Client name - ".$current_client_name;
                                $alert->filename = $latest_filename;
                                $alert->login_usernme = '';
                                $alert->acknowledged = '1';
                                $alert->created = date('Y-m-d H:i:s');
                                if($alert->save()){
                                    //echo "\nAlert record generated.";
                                    $Response .= "\nAlert record generated.";
                                }
                                /* END: generating alert */
                                /* START: adding a quarantine record */
                                $quarantine = new Quarantine;
                                //$quarantine->supplier_id = $current_sup_id;
                                $quarantine->client_id = $current_client_id;
                                $quarantine->campaign_id = $current_camp_id;
                                $quarantine->reason = 'Excessive Errors';
                                $quarantine->filename = $latest_filename;
                                $quarantine->created = date('Y-m-d H:i:s');
                                if($quarantine->save()){
                                    //echo "\nQuarantine record generated, File Quarantined.";
                                    $Response .= "\nQuarantine record generated, File Quarantined.";
                                }
                                /* END: adding a quarantine record */
                                /* START: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                $ret_csv_quarantine_path = public_path().'/qurantine-file/' .$quarantine->id;
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename_qua;
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
                                    //echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                    $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                                   /*echo "There was one or more failures. They were: <br />";
                                   foreach(Mail::failures as $email_address) {
                                       echo " - $email_address <br />";
                                    }*/
                                } else {
                                    //echo "\nEmail has been sent to Supplier.";
                                    $Response .= "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                $current_sup_return_csv = 1; //Declared statically for now because there is no supplier_id
                                if($current_sup_return_csv == 1){
                                    //$dest_path = base_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    $dest_path = base_path().'/supplier_files/portal_csv_upload/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_quarantine_path.'/'.$return_csv_filename_qua, $dest_path.'/'.$new_ret_csv_filename);
                                }
                                return json_encode(array('status'=>1, 'responseData'=>$Response));                 
                            }else{
                                //echo "\tcoming to else";
                                $Response .= "\tThe error rate does not exceed the threshold";
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                //$ret_csv_quarantine_path = public_path().'/qurantine-file/' .$quarantine->id;
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename_qua;
                                $ret_csv_file_path_qua = base_path($camp_id_ret_csv_path_qua);
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                $current_sup_return_csv = 1; //Declared statically for now because there is no supplier_id
                                if($current_sup_return_csv == 1){
                                    //$dest_path = base_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    $dest_path = base_path().'/supplier_files/portal_csv_upload/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_file_path_qua, $dest_path.'/'.$new_ret_csv_filename);
                                } 
                                /* START: Sending Email to supplier */
                                //$emailid = $current_sup_email;
                                $emailid = 'chinthala.prasad@mindtechlabs.com';
                                \Mail::send('mailtemplate.sendSuccessProcessCSVInfo',['name'=>$current_sup_name,'successful'=>$successful_lead,'duplicate'=>$duplicate_lead,'invalid'=>$invalid_lead], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV file has been processed');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    //echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                    $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                                   /*echo "There was one or more failures. They were: <br />";
                                   foreach(Mail::failures as $email_address) {
                                       echo " - $email_address <br />";
                                    }*/
                                } else {
                                    //echo "\nEmail has been sent to Supplier.";
                                    $Response .= "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */  
                                 return json_encode(array('status'=>1, 'responseData'=>$Response));                
                            }
                        }else{
                            $Response .= "\nFile is not Tab delimited.";
                            /* generating alert */
                            $alert = new Alert;
                            //$alert->supplier_id = $current_sup_id;
                            $alert->subject = "File Quarantined – Invalid Delimiters";
                            $alert->body = "File Quarantined – Invalid Delimiters";
                            $alert->filename = $latest_filename;
                            $alert->login_usernme = '';
                            $alert->acknowledged = '1';
                            $alert->created = date('Y-m-d H:i:s');
                            if($alert->save()){
                                //echo "\nAlert record generated.";
                                $Response .= "\nAlert record generated.";
                            }

                            /* adding a quarantine record */
                            $quarantine = new Quarantine;
                            //$quarantine->supplier_id = $current_sup_id;
                            $quarantine->client_id = $current_client_id;
                            $quarantine->campaign_id = $current_camp_id;
                            $quarantine->reason = 'Invalid Delimiters';
                            $quarantine->filename = $latest_filename;
                            $quarantine->created = date('Y-m-d H:i:s');
                            if($quarantine->save()){
                                //echo "\nQuarantine record generated, File Quarantined.";
                                $Response .= "\nQuarantine record generated, File Quarantined.";
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
                                //echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                               /*echo "There was one or more failures. They were: <br />";
                               foreach(Mail::failures as $email_address) {
                                   echo " - $email_address <br />";
                                }*/
                            } else {
                                //echo "\nEmail has been sent to Supplier.";
                                $Response .= "\nEmail has been sent to Supplier.";
                            }
                            return json_encode(array('status'=>0, 'responseData'=>$Response)); 
                        }
                    }
                //}//foreach($campaigns)
        }
        
    }
    /*START: It processes the CSV file which is called from CRON job after downloading csv file */
    public function csvprocessing(){
        set_time_limit(0);
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
                    //echo $curr_camp_param_map;die;
                    $curr_camp_public_id = $each_cam->public_id;
                    if($filename_campaign_id == $each_cam->public_id){
                        echo "\tFile found is: ".$latest_filename;
                        //echo "Path is: ".$path;die;
                       $delimiter = $this->getFileDelimiter($path, $latest_filename, 5);
                        //die;
                        echo "\nDelimiter is: ".$delimiter;
                        //var_dump($delimiter != "\t" || $delimiter == 0);
                        //var_dump($delimiter == '\t' && $delimiter != 0);
                        //if( ! ($delimiter != "\t" || $delimiter == 0)){
                        if($delimiter == '\t' && $delimiter!='0'){
                            echo "\tFile is Tab Delimited.\n";
                            $camp_csv_data = json_decode($curr_camp_param_map);
                            $csv_keys = array_keys((array)$camp_csv_data);
                            array_push($csv_keys,"supplier_id","client_id","campaign_id");
                            //$file_path = $path.'\\'.$latest_filename;
                            //dd($csv_keys);
                            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
                            $path = base_path($sup_id_added_path);
                            $file_path = $path.'/'.$latest_filename;
                            //echo $file_path;
                            //$fp = fopen($file_path, "r"); //open the file 
                           
                            //$data = fgetcsv($fp, filesize($file_path), "\t");  
                            $a = file_get_contents( $file_path ); 
                            //echo $a;die;
                            //fclose($fp); //close file  
                            $lines = explode( "\r\n" , $a );
                            //dd($lines);
                            $each_record = array();
                            foreach ($lines as $key => $value) {
                                $each_record[] = explode("\t", $value);
                            } 
                            //dd($each_record);
                            $mappings = array(); 
                            $total_records = 0;
                            foreach ($each_record as $key => $value) {
                                if(count($value) > 1){
                                    array_push($value, $current_sup_id, $current_client_id, $current_camp_id);
                                    $f_csvdata_array = array_values($value);
                                    //dd($f_csvdata_array);
                                    $mappings[] = array_combine((array)$csv_keys, $f_csvdata_array);
                                    //dd($mappings);
                                    $total_records++;
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
                                $datanew = array();
                                //echo $f_result->returnData->lead_disposition->success;
                                if($f_result->returnData->lead_disposition->success == 'true'){
                                     $datanew = [$f_result->returnData->email,$f_result->returnData->phone,$f_result->returnData->title,$f_result->returnData->firstName,$f_result->returnData->lastName,$f_result->returnData->birthdate,$f_result->returnData->age,$f_result->returnData->ageRange,$f_result->returnData->gender,$f_result->returnData->address1,$f_result->returnData->address2,$f_result->returnData->city,$f_result->returnData->state,$f_result->returnData->postcode,$f_result->returnData->countryCode];
                                     $datanew['lead_status']='Accepted';
                                     /*echo "\tcoming inside success";
                                     dd($datanew);*/
                                      $client_csv[] = $datanew;
                                      //$each_mapping['lead_status']='Accepted';
                                      //$cli_csv_original_rec[] = $each_mapping;
                                       $each_mapping['lead_status']='Accepted';
                                       $cli_csv_original_rec[] = $each_mapping;
                                        //$cli_csv_original_rec[] = $client_csv;
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
                            $percentage = 10;
                            $percent_result = ($total_error/$total_records)*100;
                            echo "\ntotal_records: ".$total_records;
                            echo "\npercent result: ".$percent_result;
                            if($percent_result > $current_sup_error_count){
                                echo "\nThe ratio of invalid leads exceeds the “error_allowance” threshold (defined in the “supplier” table)";
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
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                if($current_sup_return_csv == 1){
                                    $dest_path = base_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_quarantine_path.'/'.$return_csv_filename_qua, $dest_path.'/'.$new_ret_csv_filename);
                                }                
                            }else{
                                echo "\tThe error rate does not exceed the threshold";
                                /* START: Client-CSV is delivered to the Client, using the server information stored in the “server_parameters” field in the “campaign” table */
                                /* START: Send file via ftp to server */
                                $client_csv_filename = $curr_camp_public_id.'_Client-CSV.csv';
                                $camp_id_cli_csv_path = 'process_dir/temp/'.$client_csv_filename;
                                $cli_csv_file_path = base_path($camp_id_cli_csv_path);
                                $dest_file_path = base_path()."/".$current_camp_server_param->directory;
                                $file = $cli_csv_file_path;
                                

                                $ftp_server = $current_camp_server_param->server;
                                $ftp_username = $current_camp_server_param->user;
                                $ftp_userpass = $current_camp_server_param->password;
                                $host = $ftp_server;
                                $usr = $ftp_username;
                                $pwd = $ftp_userpass;
                                $local_file = $cli_csv_file_path;
                                $ftp_path = $current_camp_server_param->directory;
                                $conn_id = ftp_connect($host, 21) or die ("Cannot connect to host");
                                ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
                                ftp_pasv($conn_id, true) or die("Cannot switch to passive mode");
                                $upload = ftp_put($conn_id, $ftp_path.$client_csv_filename, $local_file, FTP_ASCII); 
                                if($upload == '1'){
                                    echo "\n Client-CSV hasbeen uploaded successfully";
                                }
                                //print($upload);
                                ftp_close($conn_id);
                                /* END: Send file via ftp to server */

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
                                \Mail::send('mailtemplate.sendSuccessProcessCSVInfo',['name'=>$current_sup_name,'successful'=>$successful_lead,'invalid'=>$invalid_lead,'duplicate'=>$duplicate_lead], function ($message) use ($emailid, $current_sup_name){
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
                        }else{
                            echo "\nFile is not Tab delimited.";
                            /* generating alert */
                            $dest_path =  public_path('frauddetections/'.$current_sup_id);
                            $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                            if($result == 1){
                                echo "\nfrauddetections directory created with supplier id.";
                            }
                            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
                            $path = base_path($sup_id_added_path);
                            $source_path =  $path;
                            //echo $source_path;
                            $move = File::copy($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);
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
                    }
                    $delete_path = $path.'/'.$latest_filename;
                    $delete = File::delete($delete_path);
                    if($delete == 1){
                        echo "\nFile deleted.";
                    }
                    }
                }//foreach($campaigns)
            }//if(is_dir())
            //echo "\thello";exit;
        }//foreach($suppliers)
    }
    /*END: It processes the CSV file which is called from CRON job after downloading csv file */
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
        if(!empty($results)){
            $results = array_keys($results, max($results));
            //print_r($results);
            return $results[0];
        }else{
            return 0;
        }
        
    }// getFileDelimiter();
}
