<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use File;
use App\Alert;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProcessleadcsvController;

class DownloadCSV extends Command
{
    protected $signature = 'downloadcsv2:download';
    protected $description = 'Download CSV File';
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
    }
    public function handle()
    {
        $suppliers = DB::table('suppliers')->select('id', 'public_id', 'error_allowance','return_csv')->get();
        $dir_counter = 0;
        foreach($suppliers as $each_sup){
            $current_sup_id = $each_sup->id;
            $current_sup_pub_id = $each_sup->public_id;
            $sup_id_added_path = 'supplier_files/'.$current_sup_pub_id.'/upload';
            //$sup_id_added_path = 'supplier_files\\'.$current_sup_pub_id.'\upload';
            $path = storage_path($sup_id_added_path);
            
            $check = check_if_is_dir_exists_and_empty();
            if(is_dir($path) && ! $this->is_dir_empty($path)){
                
                $latest_ctime = 0;
                $latest_filename = '';    

                $d = dir($path);
                while (false !== ($entry = $d->read())) {
                  $filepath = "{$path}/{$entry}";
                  // could do also other checks than just checking whether the entry is a file
                  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
                    $latest_ctime = filectime($filepath);
                    $latest_filename = $entry;
                  }
                }
                //echo $latest_filename;
                $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
                $arr = explode("_", $file_ext_removed, 2);
                $filename_campaign_id = $arr[0];
                //echo "\nPlain File name: ".$filename_campaign_id;
                $fname_matches_counter = 0;
                $campaigns = DB::table('campaigns')->select('public_id')->get();
                foreach($campaigns as $each_cam){
                    if($filename_campaign_id == $each_cam->public_id){
                        //The name of the directory that we want to move csv file.
                        $newpath_var = storage_path('process_dir/'.$current_sup_pub_id);

                        if (!file_exists($newpath_var) || !is_dir($newpath_var)) {
                            //Directory does not exist, so lets create it.
                            mkdir($newpath_var, 0777, true);
                        }

                        echo "\nFile found is: ".$latest_filename;
                        $move    =  File::move($path.'/'.$latest_filename, $newpath_var.'/'.$latest_filename);

                        if($move == 1){
                            echo "\nFile is downloaded.";    
                        }
                        $fname_matches_counter++;
                        $dir_counter++;
                    }else{
                    }
                }

                if($fname_matches_counter == 0){
                    echo "\nFile name doesn't match with campaign public id.";
                    $dest_path =  storage_path('frauddetections/'.$current_sup_id);
                    $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                    echo "frauddetections directory created with supplier id.";
                    $sup_id_added_path = 'supplier_files/'.$current_sup_pub_id.'/upload';
                    $path = storage_path($sup_id_added_path);
                    $source_path =  $path;
                    //echo $source_path;
                    $move =  File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);

                    $alert = new Alert;
                    $alert->supplier_id = $current_sup_id;
                    $alert->subject = "Invalid SFTP Campaign ID";
                    $alert->body = "Invalid SFTP Supplier ID” or “Invalid SFTP Campaign ID";
                    $alert->filename = $latest_filename;
                    $alert->login_usernme = '';
                    $alert->acknowledged = '1';
                    $alert->created = date('Y-m-d H:i:s');
                    if($alert->save()){
                        echo "\nAlert record generated.";
                    }
                }
            }
        }//foreach($suppliers)
        if($dir_counter == 0){
            echo "\nDirectory is empty.";
        }else{
            echo "\ncalling csvprocessing controller method after download";
            $plcController = new ProcessleadcsvController;
            $plcController->csvprocessing();
        }
        
    }
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
    }
}
