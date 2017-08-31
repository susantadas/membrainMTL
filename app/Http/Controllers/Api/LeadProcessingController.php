<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\LeadProcessTable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class LeadProcessingController extends Controller
{    
    /* Start authentication check */
    public function __construct()  {
        set_time_limit(0);        
    }
    /* end authentication check */

    public function index() {
        $datas = ['data' => ['supplier_id'=>'2','client_id'=>'2','campaign_id'=>'2','email'=>'test@mail.com','phone'=>'00611234567890','title'=>'MR','firstName'=>'Fred','lastName'=>'Farkus','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'Sydny','postcode'=>'58796','countryCode'=>'AU'],['supplier_id'=>'2','client_id'=>'2','campaign_id'=>'2','email'=>'test2@mail.com','phone'=>'00441234567890','title'=>'MR','firstName'=>'Prasad','lastName'=>'Kumar','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'Sydny','postcode'=>'W113DS','countryCode'=>'UK'],['supplier_id'=>'2','client_id'=>'2','campaign_id'=>'2','email'=>'test3@mail.com','phone'=>'01111234567890','title'=>'MR','firstName'=>'susanta','lastName'=>'das','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'Sydny','postcode'=>'W113DS','countryCode'=>'US']];
         
        foreach ($datas as $key => $data) {
            $_Model = new LeadProcessTable();
            //$result = $_Model->apiData($data);
            $result = $_Model->genderValidation($data);
            echo '<pre>';
            print_r($result);
        }
    }

    public function getData(Request $request) {
        set_time_limit(0);
        $data = ["Supplier"=>["ID"=>"cff9f87017698c43e9d9eb8c05ad3d14","source"=>"Wintastic"],"campaign"=>["ID"=>"182689fc3fc298e8e103413586bece67","value"=>"false"],"consumer"=>["email"=>"mr.susantakumardas@aol.com","phone"=>"0412080681","title"=>"MR","firstName"=>"susanta","lastName"=>"Das","birthdate"=>"14-Feb-1988","age"=>"29","ageRange"=>"20-50","gender"=>"","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"]];
        //$data = $request->all();
        print_r($data);
        $_Model = new LeadProcessTable();
        //$result = $_Model->apiData($data);
        $result = $_Model->genderValidation($data['consumer']['title'],$data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['countryCode'],$data['consumer']['gender']);     
        return response()->json(['returnData'=>$result]);
    } 

    public function create() {
        //
    } 
    public function store(Request $request) {
        print_r($request);
        //return 'sss';
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
}