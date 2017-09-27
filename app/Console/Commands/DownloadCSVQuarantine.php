<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Http\Request;
use DB;
use File;
use App\Alert;
use App\ProcessCSV;
use App\Campaign;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProcessleadcsvController;
class DownloadCSVQuarantine extends Command
{
    protected $signature = 'downloadcsvquarantine:download';
    protected $description = 'Download CSV File Quarantine reprocess';
    public function __construct() {
        parent::__construct();
        set_time_limit(0);
    }
    public function handle() {
        $filepathDir = storage_path('supplier_files/portal_csv_upload/upload');
        $files = scandir($filepathDir);
        foreach ($files as $file) {            
            if($file!='.' && $file!='..'){
                $_capId = explode('_', $file);
                $idCamp = DB::table('campaigns')->where('public_id','=',$_capId[0])->get(['id'])->toArray();
                if(!empty($idCamp)){
                    $rqs = [$idCamp[0]->id];
                    $controller = app()->make('App\Http\Controllers\ProcessleadcsvController');
                    $VMFNDJN = app()->call([$controller, 'csvProcessingFromPortal'],$rqs);
                }
                
            }
        }
    }
}