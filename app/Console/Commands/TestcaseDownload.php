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
            //$sup_id_added_path = 'supplier_files\\'.$current_sup_pub_id.'\upload';
            $path = base_path($sup_id_added_path);
            //$files = Storage::allFiles($path);
            //echo "No. of files: ".count($files);
            //echo $path;
            //echo $this->is_dir_empty($path);
            //$dir_counter = 0;
            $is_dir_exists =  $this->check_if_is_dir_exists($path);
            $is_dir_empty = $this->check_if_dir_is_empty($path);
            //if(is_dir($path) && ! $this->is_dir_empty($path)){

            if($is_dir_exists == '1'){

                if($is_dir_empty == '1'){
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
                    $filename_only_campaign_id = $arr[0];
                    //echo "\nPlain File name: ".$filename_only_campaign_id;
                    $fname_matches_counter = 0;
                    $campaigns = DB::table('campaigns')->select('public_id')->get();
                    foreach($campaigns as $each_cam){
                        $filename_matches = check_if_filename_matches($filename_only_campaign_id, $each_cam->public_id);
                        //if($filename_only_campaign_id == $each_cam->public_id){
                        if($filename_matches == '1'){
                            //The name of the directory that we want to move csv file.
                            $newpath_var = base_path('process_dir/'.$current_sup_pub_id);
                            //$newpath_var ='process_dir\\'.$current_sup_pub_id.'';
                            //Check if the directory already exists.
                            if (!file_exists($newpath_var) || !is_dir($newpath_var)) {
                                //Directory does not exist, so lets create it.
                                mkdir($newpath_var, 0777, true);
                            }
                            //$newpath_f = base_path($newpath_var);
                            //$newpath = public_path('\new');
                            echo "\nFile found is: ".$latest_filename;
                            $move    =  File::move($path.'/'.$latest_filename, $newpath_var.'/'.$latest_filename);
                            /*copy($path.'/'.$latest_filename, $newpath_f.'/'.$latest_filename);
                            unlink($path.'/'.$latest_filename);*/
                            //echo "\nFile moved return is: ".$move;
                            if($move == 1){
                                echo "\nFile is downloaded.";    
                            }
                            $fname_matches_counter++;
                            $dir_counter++;
                        }else{
                            //echo "\tFile name doesn't match with campaign public id.";
                            /*copy($path.'/'.$latest_filename, $newpath_f.'/'.$latest_filename);
                            unlink($path.'/'.$latest_filename);
                            echo "\tFile is downloaded to .";*/
                        }
                    }//foreach($campaigns)
                    //$dir_counter++;
                    if($fname_matches_counter == 0){
                        echo "\nFile name doesn't match with campaign public id.";
                        $dest_path =  public_path('frauddetections/'.$current_sup_id);
                        //echo $dest_path;
                        //$dest_path =  public_path('frauddetections\\'.$current_sup_id);
                        //if(!file_exists($dest_path_var)) {
                        //if(!File::exists($dest_path)){
                            //Directory does not exist, so lets create it.
                            $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                            echo "frauddetections directory created with supplier id.";
                        //}
                        //$dest_path =  public_path($dest_path_var);
                        //$sup_id_added_path = 'supplier_files\\'.$current_sup_pub_id.'\upload';
                        $sup_id_added_path = 'supplier_files/'.$current_sup_pub_id.'/upload';
                        $path = base_path($sup_id_added_path);
                        $source_path =  $path;
                        //echo $source_path;
                        $move     =  File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);
                        //$move     =  File::move($source_path.'\\'.$latest_filename, $dest_path.'\\'.$latest_filename);
                        /*copy($path.'/'.$latest_filename, $dest_path.'\\'.$latest_filename);
                        unlink($path.'/'.$latest_filename);*/
                        //$delete   =  File::delete($old_path);
                        $alert = new Alert;
                        $alert->supplier_id = $current_sup_id;
                        $alert->subject = "Invalid SFTP Campaign ID";
                        $alert->body = "Invalid SFTP Supplier ID” or “Invalid SFTP Campaign ID";
                        $alert->filename = $latest_filename;
                        $alert->login_usernme = '';
                        $alert->acknowledged = '1';
                        $alert->created = date('Y-m-d H:i:s');
                        //$alert->acknowledged_date = '';
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
    public function check_if_is_dir_exists($path){
        if(is_dir($path)){
            return '1';
        }else{
            return '0';
        }
    }
    public function check_if_dir_is_empty($path){
        if(!$this->is_dir_empty($path)){
            return '1';
        }else{
            return '0';
        }
    }
    public function check_if_filename_matches($filename_only_campaign_id, $current_camp_public_id){
        if($filename_only_campaign_id == $current_camp_public_id){
          return '1';
        }else{
            return '0';
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
