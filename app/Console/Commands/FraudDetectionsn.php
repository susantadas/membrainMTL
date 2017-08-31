<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Excel; //csv file reader
use App\Models\fraudDetection;
use App\Models\fraudAlert;
use App\Supplier;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class FraudDetectionsn extends Command
{
    protected $signature = 'frauddetectionsn:clear';
    protected $description = 'Fraud Detection';
    protected $fraudDetection;

    public function __construct(fraudDetection $fraudDetection) {
        parent::__construct();
        $this->fraudDetection = $fraudDetection;
        set_time_limit(0);
    }
    
    public function handle() {
        $data = ['email'=>'ss@ss.com','phone'=>'9002557974','title'=>'MR','firstName'=>'susanta','lastName'=>'das','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'Sydny','postcode'=>'W113DS','countryCode'=>'UK'];
        $client = new Client();
        $res = $client->post('http://ec2-13-126-3-130.ap-south-1.compute.amazonaws.com/membraindev/public/api/v1/leadprocessing/getdata', ['form_params'=>$data]);
        if($res->getStatusCode()=='200'){
           $result = $res->getBody();
        }
        echo $result;
    }
}