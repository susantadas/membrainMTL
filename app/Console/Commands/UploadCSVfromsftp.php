<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use File;
use DB;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;
class UploadCSVfromsftp extends Command
{
    protected $signature = 'uploadcsvfromsftp:upload';
    protected $description = 'Upload return CSV File to SFTP';
    public function __construct() {
        parent::__construct();
        set_time_limit(0);
    }

    public function handle() {
        $newkey = Config('app.sftp-privateKey');
        $newkeyPath = storage_path($newkey);
        $details = array(
            'host'     => Config('app.sftp-host'),
            'port'     => Config('app.sftp-port'),
            'username' => Config('app.sftp-username'),
            'password' => Config('app.sftp-password'),
            'privateKey' => "$newkeyPath",
            'root' => Config('app.sftp-root'),
            'timeout' => 10,
            'directoryPerm' => 0777,
            'type'=>Config('app.sftp-type')
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
                    $ftppath = $details['root'].$_supplier->public_id.'/download/';
                    $storepath = 'supplier_files/'.$_supplier->public_id.'/download/';
                    $Localpath = storage_path($storepath);
                    if (is_dir($Localpath)) {
                        $contents = scandir($Localpath);
                        if(!empty($contents)){
                            foreach($contents as $file){
                                if($file =='.' || $file=='..'){
                                    $Response .="\nThere was a problem";
                                } else {
                                    $fileExist = ftp_nlist($conn_id, $ftppath);
                                    if (empty($fileExist)) {
                                        ftp_mkdir($conn_id, $ftppath);
                                        $Response .="\nSuccessfully created $ftppath";
                                    } 
                                    if(ftp_put($conn_id, $ftppath.$file, $Localpath.$file, FTP_ASCII)){
                                        $Response .="\n$file upload successful";
                                    } else {
                                        $Response .="\nThere was a problem";
                                    }
                                }
                            }
                        }
                    } else {
                        $Response .="\nThere was a problem";
                    }
                }
            }
            ftp_close($conn_id);
            echo $Response;
        } else {
            $Response ='';
            $sftp = new SFTP($details['host']);
            if($details['privateKey']!='' && $details['password']==''){
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
                    $ftppath = $details['root'].$_supplier->public_id.'/download/';
                    $storepath = 'supplier_files/'.$_supplier->public_id.'/download/';
                    $Localpath = storage_path($storepath);
                    if (is_dir($Localpath)) {
                        $contents = scandir($Localpath);
                        if(!empty($contents)){
                            foreach($contents as $file){
                                if($file =='.' || $file=='..'){
                                    $Response .="\nThere was a problem";
                                } else {
                                    $sftppath = $sftp->nlist($ftppath);
                                    if(empty($sftppath)){                                        
                                        $sftp->mkdir($ftppath);
                                    }
                                    
                                    if($sftp->put($ftppath.$file,file_get_contents($Localpath.$file))){
                                        $Response .="\n$file upload successful\n";
                                    } else {
                                        $Response .="\ncould not upload  $file\n";
                                    }                           
                                }
                            }
                        }
                    } else {
                        $Response .="\nThere was a problem";
                    }
                }
            }
            echo $Response;
        }
    }
}