<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Facades\Storage;

class CSVProcess extends Model
{
    protected $connection = 'mysql';

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
    public function is_dir_empty($dir){
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
