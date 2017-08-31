<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use File;
use DB;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;
class DownloadCSVfromsftp extends Command
{
    protected $signature = 'downloadcsvfromsftp:download';
    protected $description = 'Download raw CSV File From SFTP';
    public function __construct() {
        parent::__construct();
        set_time_limit(0);
    }

    public function handle() {
        /*$details = array(
            'host'     => '67.23.226.139',
            'port'     => 22,
            'username' => 'midclass',
            'password' => 'RX(dmjKvSB(UJedVXKBJ', // uou can set password or key path 
            'root' => '/public_html/supplier-file/',
            'timeout' => 10,
            'directoryPerm' => 0755,
            'type'=>'ftp'
        );*/

        $newkey = 'ppk/anupam_aws.ppk';
        $newkeyPath = storage_path($newkey);
        $details = array(
            'host'     => '52.77.182.246',
            'port'     => 22,
            'username' => 'ec2-user',
            'password' => '',
            'privateKey' => "$newkeyPath",
            'root' => '/var/www/html/membraindev/supplier-file/',
            'timeout' => 10,
            'directoryPerm' => 0755,
            'type'=>'sftp'
        );
        if($details['type']=='ftp'){
            $Response ='';
            $conn_id = ftp_connect($details['host'], 21);
            if(!$conn_id){
                $Response .="\nFTP connection failed.";
            }
            $login = ftp_login($conn_id,$details['username'],$details['password']);
            if ((!$conn_id) || (!$login)) {
                $Response .="\n FTP connection has failed!";
            } else {
                $fff = ftp_pasv($conn_id, true);    
                $suppliers = DB::table('suppliers')->select('id', 'public_id', 'error_allowance','return_csv')->get();
                foreach($suppliers as $_supplier){
                    $ftppath = $details['root'].$_supplier->public_id.'/upload/';
                    $storepath = 'supplier-file/'.$_supplier->public_id.'/upload/';
                    $Localpath = storage_path($storepath);
                    $contents = ftp_nlist($conn_id, $ftppath);
                    if(!empty($contents)){
                        foreach($contents as $file){
                            if($file =='.' || $file=='..'){
                                $Response .="\nThere was a problem";
                            } else {
                                File::makeDirectory($Localpath, $mode = 0777, true, true);
                                $newfile = fopen($Localpath.$file, 'w');fclose($newfile);
                                if (ftp_get($conn_id,$Localpath.$file,$ftppath.$file,FTP_ASCII)) {
                                    if (ftp_delete($conn_id, $ftppath.$file)) {
                                        $Response .="\n$file deleted successful\n";
                                    } else {
                                        $Response .="\ncould not delete $file\n";
                                    }
                                } else {
                                    $Response .="\nThere was a problem\n";
                                }                                
                            }
                        }
                    }
                }
            }
            ftp_close($conn_id);
            echo $Response;
        } else {
            $Response ='';
            $sftp = new SFTP($details['host']);
            if($details['privateKey']!=''){
                $password = new RSA();
                // If the private key has a passphrase we set that first
                $password->setPassword('passphrase');
                // Next load the private key using file_gets_contents to retrieve the key
                $password->loadKey(file_get_contents($details['privateKey']));
            } else {
                $password = $details['password'];
            }
            if (!$sftp->login($details['username'],$password)) {
                throw new Exception('Login failed');
            } else {
                $suppliers = DB::table('suppliers')->select('id', 'public_id', 'error_allowance','return_csv')->get();
                foreach($suppliers as $_supplier){
                    $ftppath = $details['root'].$_supplier->public_id.'/upload/';
                    $storepath = 'supplier-file/'.$_supplier->public_id.'/upload/';
                    $Localpath = storage_path($storepath);
                    $contents = $sftp->nlist($ftppath);
                    if(!empty($contents)){
                        foreach($contents as $file){
                            if($file =='.' || $file=='..'){
                                $Response .="\nThere was a problem";
                            } else {
                                File::makeDirectory($Localpath, $mode = 0777, true, true);
                                $newfile = fopen($Localpath.$file, 'w');fclose($newfile);
                                if($sftp->get($ftppath.$file,$Localpath.$file)){
                                    if ($sftp->delete($ftppath.$file)) {
                                        $Response .="\n$file deleted successful\n";
                                    } else {
                                        $Response .="\ncould not delete $file\n";
                                    }
                                } else {
                                    $Response .="\nThere was a problem\n";
                                }                              
                            }
                        }
                    }
                }
            }
            echo $Response;
        }
    }
}