<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Facades\Storage;

class ProcessCSV extends Model
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
        if($this->is_dir_empty($path)){
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
    public function get_latest_file($path){
        $latest_ctime = 0;
        $latest_filename = '';    
        $d = dir($path);
        while (false !== ($entry = $d->read())) {
          $filepath = "{$path}/{$entry}";
          // could do also other checks than just checking whether the entry is a file
          if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
            return $latest_filename;
          }
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
    public function check_if_file_is_tab_delimited($path, $latest_filename, $checkLines = 5){
        $delimiter = $this->getFileDelimiter($path, $latest_filename, 5);
        echo "\nDelimiter is: ".$delimiter;
        if($delimiter == '\t' && $delimiter!='0'){
          return '1';
        }else{
          return '0';
        }
    }
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
