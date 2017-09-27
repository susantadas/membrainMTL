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
                    $ftppath = $details['root'].$_supplier->public_id.'/upload/';
                    $storepath = 'supplier_files/'.$_supplier->public_id.'/upload/';
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
                $contentsFolder = $sftp->nlist($details['root']);
                if(!empty($contentsFolder)){
                    foreach($contentsFolder as $folder){
                        if($folder !='.' && $folder!='..' && $folder!='.ssh'){
                            $suppliers = DB::table('suppliers')->where('public_id','=',$folder)->get()->toArray();
                            if(!empty($suppliers)){
                                $folderNew = $sftp->nlist($details['root'].$folder.'/');
                                if(!empty($folderNew)){
                                    foreach ($folderNew as $fk => $_folderNew) {
                                        if($_folderNew !='upload'){
                                            unset($folderNew[$fk]);
                                        }
                                    }
                                    foreach ($folderNew as $_folderNew) {
                                        if($_folderNew !='.' && $_folderNew!='..'){
                                            $ftppath = $details['root'].$folder.'/upload/';
                                            $storepath = 'supplier_files/'.$folder.'/upload/';
                                            $Localpath = storage_path($storepath);
                                            $storepathDownload = 'supplier_files/'.$folder.'/download/';
                                            $_storepathDownload = storage_path($storepathDownload);
                                            $contents = $sftp->nlist($ftppath);
                                            if(!empty($contents)){
                                                foreach($contents as $file){
                                                    if($file !='.' && $file!='..'){
                                                        $newFileforCan = explode('_',$file);
                                                        $campaigns = DB::table('campaigns')->where('public_id', '=', $newFileforCan[0])->get()->toArray();
                                                        if(!empty($campaigns)){
                                                            File::makeDirectory($Localpath, $mode = 0777, true, true);
                                                            File::makeDirectory($_storepathDownload, $mode = 0777, true, true);
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
                                                        } else {
                                                            $storepathAlert = 'supplier_files/portal_csv_upload/download/';
                                                            $filename = $newFileforCan[0].'_invalid.csv';
                                                            $LocalpathAlert = storage_path($storepathAlert);
                                                            $newfile = fopen($LocalpathAlert.$filename, 'w');fclose($newfile);
                                                            $alertError = array('supplier_id'=>$suppliers[0]->id,'subject'=>'Invalid SFTP Campaign ID','body'=>'Invalid SFTP Campaign ID','filename'=>$filename,'acknowledged'=>'1','created'=>date('Y-m-d H:i:s'));
                                                            DB::table('alerts')->insert($alertError);
                                                            if($sftp->get($ftppath.$file,$LocalpathAlert.$filename)){
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
                                    }
                                }
                            } else {                                
                                $folderNew = $sftp->nlist($details['root'].$folder.'/');
                                if(!empty($folderNew)){
                                    foreach ($folderNew as $fk => $_folderNew) {
                                        if($_folderNew !='upload'){
                                            unset($folderNew[$fk]);
                                        }
                                    }
                                    foreach ($folderNew as $_folderNew) {
                                        if($_folderNew !='.' && $_folderNew!='..'){
                                            $ftppath = $details['root'].$folder.'/upload/';
                                            $contents = $sftp->nlist($ftppath);
                                            if(!empty($contents)){
                                                foreach($contents as $file){
                                                    if($file !='.' && $file!='..' && $file!='known_hosts'){
                                                        $newFileforCan = explode('_',$file);
                                                        $campaigns = DB::table('campaigns')->where('public_id', '=', $newFileforCan[0])->get()->toArray();
                                                        if(!empty($campaigns)){
                                                            $storepathAlert = 'supplier_files/portal_csv_upload/download/';
                                                            $filename = $newFileforCan[0].'_invalid.csv';
                                                            $LocalpathAlert = storage_path($storepathAlert);
                                                            $newfile = fopen($LocalpathAlert.$filename, 'w');fclose($newfile);
                                                            $alertError = array('supplier_id'=>'0','subject'=>'Invalid SFTP Supplier ID','body'=>'Invalid SFTP Supplier ID','filename'=>$filename,'acknowledged'=>'1','created'=>date('Y-m-d H:i:s'));
                                                            DB::table('alerts')->insert($alertError);
                                                            if($sftp->get($ftppath.$file,$LocalpathAlert.$filename)){
                                                                if ($sftp->delete($ftppath.$file)) {
                                                                    $Response .="\n$file deleted successful\n";
                                                                } else {
                                                                    $Response .="\ncould not delete $file\n";
                                                                }
                                                            } else {
                                                                $Response .="\nThere was a problem\n";
                                                            }
                                                        } else {
                                                            $storepathAlert = 'supplier_files/portal_csv_upload/download/';
                                                            $filename = $newFileforCan[0].'_invalid.csv';
                                                            $LocalpathAlert = storage_path($storepathAlert);
                                                            $newfile = fopen($LocalpathAlert.$filename, 'w');fclose($newfile);
                                                            $alertError = array('supplier_id'=>'0','subject'=>'Invalid SFTP Supplier Id & Campaign ID','body'=>'Invalid SFTP Supplier ID & Campaign ID','filename'=>$filename,'acknowledged'=>'1','created'=>date('Y-m-d H:i:s'));
                                                            DB::table('alerts')->insert($alertError);
                                                            if($sftp->get($ftppath.$file,$LocalpathAlert.$filename)){
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
                                    }
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