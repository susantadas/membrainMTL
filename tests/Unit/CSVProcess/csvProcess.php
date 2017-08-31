<?php

namespace Tests\Unit\CSVProcess;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use File;
use App\ProcessCSV;

class csvProcess extends TestCase
{
    public function test_for_filepath_doesnt_exist()
    {
    	$current_sup_pub_id = '1e0cb7b9cf2df00fee1ad59d08a97855';
        $sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
        $dir_exists = $csv_process->check_if_is_dir_exists($path);
        if($dir_exists=='0'){
            $massage = "*********************************************************************\nDescription: checking if a directory doesn't exist \nInput: FolderPath = '".$path."' \nOutput: invalid Path.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$dir_exists);
    }
    public function test_for_filepath_exists()
    {
        $current_sup_pub_id = '32dfb6045d9b0e6b3e026822db8868af';
        $sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
        $dir_exists = $csv_process->check_if_is_dir_exists($path);
        if($dir_exists=='1'){
            $massage = "*********************************************************************\nDescription: checking if a directory exists \nInput: FolderPath = '".$path."' \nOutput: Valid Path.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$dir_exists);
    }
    public function test_for_directoy_is_empty(){
    	$current_sup_pub_id = '32dfb6045d9b0e6b3e026822db8868af';
        $sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
        $empty = $csv_process->check_if_dir_is_empty($path);
        if($empty=='1'){
            $massage = "*********************************************************************\nDescription: checking if a directory is empty \nInput: FolderPath = '$path' \nOutput: Directory is empty.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$empty);
    }
    public function test_for_directoy_is_not_empty(){
        $current_sup_pub_id = '38bbef9bdfe8c9d49eac63998ff9b9e0';
        $sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
        $empty = $csv_process->check_if_dir_is_empty($path);
        if($empty=='0'){
            $massage = "*********************************************************************\nDescription: checking if a directory is not empty \nInput: FolderPath = '$path' \nOutput: Directory is not empty.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$empty);
    }
    public function test_for_filename_doesnt_match_with_campaign_publicid(){
    	$current_sup_pub_id = '38bbef9bdfe8c9d49eac63998ff9b9e0';
    	$sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
    	$current_camp_pub_id = 'c3490353f7c65e5fbe9aac05a54ebc58';
    	$csv_process = new ProcessCSV;
        $latest_filename = $csv_process->get_latest_file($path);
    	$file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
        $arr = explode("_", $file_ext_removed, 2);
        $filename_only_campaign_id = $arr[0];
        $csv_process = new ProcessCSV;
        $filename_matches = $csv_process->check_if_filename_matches($filename_only_campaign_id, $current_camp_pub_id);
        if($filename_matches=='0'){
            $massage = "*********************************************************************\nDescription: checking if filename doesn't match with campaign public_id\nInput: FolderPath = '$path/$latest_filename' checked with '$current_camp_pub_id' \nOutput: The filename doesn't match with campaign publid_id.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$filename_matches);
    }
    public function test_for_filename_matches_with_campaign_publicid(){
    	$current_sup_pub_id = '44ab7d4d82bc5e923125bb19bd7d5b5b';
        $current_camp_pub_id = 'c3490353f7c65e5fbe9aac05a54ebc58';
    	$sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
    	$latest_filename = $csv_process->get_latest_file($path);
    	$file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
        $arr = explode("_", $file_ext_removed, 2);
        $filename_only_campaign_id = $arr[0];
        $filename_matches = $csv_process->check_if_filename_matches($filename_only_campaign_id, $current_camp_pub_id);
        if($filename_matches=='1'){
            $massage = "*********************************************************************\nDescription: checking if filename matches with campaign public_id\nInput: FolderPath = '$path/$latest_filename' checked with '$current_camp_pub_id' \nOutput: The filename matches with campaign publid_id.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$filename_matches);
    }
    public function test_for_file_is_tab_delimited(){
    	$current_sup_pub_id = 'cff9f87017698c43e9d9eb8c05ad3d14';
    	$sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
    	$filename_to_read = $csv_process->get_latest_file($path);
        //$filename_to_read = 'c3490353f7c65e5fbe9aac05a54ebc58_220617.txt';
    	//$full_path = $path.'/'.$filename_to_read;
    	//$full_path = $path.'\\'.$filename_to_read;
    	$tab_delimited = $csv_process->check_if_file_is_tab_delimited($path, $filename_to_read, $checkLines=5);
        if($tab_delimited=='1'){
            $massage = "*********************************************************************\nDescription: checking if file is tab delimited\nInput: FolderPath = '$path/$filename_to_read' \nOutput: The file is tab delimited.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$tab_delimited);
    }
    public function test_for_file_is_not_tab_delimited(){
    	$current_sup_pub_id = 'd3df314a6ce6f45d7d937e72a30bf55f';
    	$sup_id_added_path = 'testcase_csvprocess/'.$current_sup_pub_id.'/upload';
        //$sup_id_added_path = 'testcase_csvprocess\\'.$current_sup_pub_id.'\\upload';
        $path = base_path($sup_id_added_path);
        $csv_process = new ProcessCSV;
        $filename_to_read = $csv_process->get_latest_file($path);
    	//$filename_to_read = 'c3490353f7c65e5fbe9aac05a54ebc58_220617.txt';
    	$tab_delimited = $csv_process->check_if_file_is_tab_delimited($path, $filename_to_read, $checkLines=5);
        if($tab_delimited=='0'){
            $massage = "*********************************************************************\nDescription: checking if file is not tab delimited\nInput: FolderPath = '$path/$filename_to_read' \nOutput: The file is is not tab delimited.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$tab_delimited);
    }
    
}
