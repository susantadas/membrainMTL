<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\LeadProcessTable;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GhcRequest;
use Illuminate\Support\Facades\Response;
class PayloadValidationController extends Controller
{    
    /* Start authentication check */
    public function __construct()  {
    }
    /* end authentication check */
    public function index() {
        $data = ['email'=>'ss@ss.com','phone'=>'0412080681','title'=>'MR','firstName'=>'Fred','lastName'=>'Farkus','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'kolkata','postcode'=>'721144','countryCode'=>'IN'];

        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rtw="http://rtw.dncrtelem" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/"><soapenv:Header/><soapenv:Body><rtw:WashNumbers soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><TelemarketerId xsi:type="xsd:string">36441</TelemarketerId><TelemarketerPassword xsi:type="xsd:string">Bra1nWa5h3d!!!</TelemarketerPassword><ClientReferenceId xsi:type="xsd:string">SL0001</ClientReferenceId><NumbersToWash xsi:type="rtw:ArrayOf_xsd_anyType" soapenc:arrayType="xsd:anyType[]"><Number xsi:type="xsd:string">'.$data['phone'].'</Number></NumbersToWash></rtw:WashNumbers></soapenv:Body></soapenv:Envelope>';

        $uri = 'https://www.donotcall.gov.au/dncrtelem/rtw/washing.cfc';
        $client = new Client();
        $request = new GhcRequest ('POST',$uri,['Content-Type' => 'text/xml; charset=UTF8'],$xml);
        $response = $client->send($request);
        $_Model = new LeadProcessTable();
        $_result = $_Model->XMLToArray($response->getBody());
        $result = $_result['envelope']['body']['washnumbersresponse']['washnumbersreturn']['numberssubmitted']['number'];
        if(isset($_result['envelope']['body']['washnumbersresponse']['washnumbersreturn']['numberssubmitted']['number']) && !empty($_result['envelope']['body']['washnumbersresponse']['washnumbersreturn']['numberssubmitted']['number'])){
            $result = $_result['envelope']['body']['washnumbersresponse']['washnumbersreturn']['numberssubmitted']['number'];
            if($result['attributes']['result']=='Y' && $result['attributes']['errorcode']=='0'){
                $returnData =  array('status'=>'1','error'=>'','phone'=>$result['value']);
            } else {
                $returnData =  array('status'=>'0','error'=>'Phone Number fail DNCR','phone'=>$data['phone']);
            }
        } else {
           $returnData =  array('status'=>'0','error'=>'Phone Number fail DNCR','phone'=>$data['phone']); 
        }

        return $returnData;
    }

    public function suppliersApi(){
        $data = ['Supplier'=>['ID'=>'407b20e04c238b3feda82fd167e6068a','source'=>'Wintastic'],'campaign'=>['ID'=>'2f9065307e8b94e2cd7c1b602de0ccb2'],'consumer'=>['email'=>'ssdas@ss.com','phone'=>'8002557974','title'=>'MR','firstName'=>'Fred','lastName'=>'Farkus','birthdate'=>'14-02-1988','age'=>'15','ageRange'=>'25-35','gender'=>'Male','address1'=>'balaji guest house','address2'=>'100 Avenue madhapur','city'=>'100Ft Road','state'=>'kolkata','postcode'=>'721144','countryCode'=>'UK']];
        $client = new Client();      
        $headers = [
            'x-api-key'=>'ZlSTMNfu1r2GTK6xomkavO5OS9B2dXfznl27jJ3TJO238L8ldn00Yw8GbDW7',
            'content-type' => 'application/json'
        ];
        $request = $client->post(url('api/v1/supplierapi/newapi'),['headers' => $headers,'json' => $data]);
        return $request;
    }

    public function create() {
        //
    } 
    public function store(Request $request) {
        //print_r($request);
        return 'sss';
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