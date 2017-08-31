<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use File;
use App\Alert;
use App\ProcessCSV;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProcessleadcsvController;

class DownloadCSV extends Command
{
    protected $signature = 'downloadcsv:download';
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
            $path = storage_path($sup_id_added_path);
            $csv_process = new ProcessCSV;
            $is_dir_exists = $csv_process->check_if_is_dir_exists($path);
            $is_dir_empty = $csv_process->check_if_dir_is_empty($path);

            if($is_dir_exists == '1'){
                if($is_dir_empty == '0'){
                    
                    $latest_filename = $csv_process->get_latest_file($path);
                    $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
                    $arr = explode("_", $file_ext_removed, 2);
                    $filename_only_campaign_id = $arr[0];

                    $fname_matches_counter = 0;
                    $campaigns = DB::table('campaigns')->select('public_id')->get();
                    foreach($campaigns as $each_cam){
                        $filename_matches = $csv_process->check_if_filename_matches($filename_only_campaign_id, $each_cam->public_id);

                        if($filename_matches == '1'){
                            //The name of the directory that we want to move csv file.
                            $newpath_to_upload = storage_path('process_dir/'.$current_sup_pub_id);

                            $is_dir_exists = $csv_process->check_if_is_dir_exists($newpath_to_upload);

                            if ($is_dir_exists == '0') {
                                //Directory does not exist, so lets create it.
                                mkdir($newpath_to_upload, 0777, true);
                            }

                            $move    =  File::move($path.'/'.$latest_filename, $newpath_to_upload.'/'.$latest_filename);

                            if($move == 1){
                                echo "\nFile is downloaded.";    
                            }
                            $fname_matches_counter++;
                            $dir_counter++;
                        }else{
                        }
                    }
                    //$dir_counter++;
                    if($fname_matches_counter == 0){
                        echo "\nFile name doesn't match with campaign public id.";
                        $dest_path =  storage_path('frauddetections/'.$current_sup_id);
                        $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                        echo "frauddetections directory created with supplier id.";
                        $sup_id_added_path = 'supplier_files/'.$current_sup_pub_id.'/upload';
                        $path = storage_path($sup_id_added_path);
                        $source_path =  $path;
                        $move     =  File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);
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
                }//check_if_dir_is_empty($path)
            }//is_dir_exists($path)
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
