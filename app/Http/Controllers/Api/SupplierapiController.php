<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\LeadProcessTable;
use App\Models\Api\LeadProcessTableCsv;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
class SupplierapiController extends Controller
{    
    /* Start authentication check */
    public function __construct()  {
    }
    /* end authentication check */

    public function index() {        
    }

    public function newApi(Request $request) {
        if($request->isMethod('post')){
            $headerApiKey = $request->header('x-api-key');
            $actualKey = Config('app.x-api-key');
            $leaddata = array();
            if($headerApiKey!=''){
                if($headerApiKey == $actualKey){
                    $data = $request->all();            
                    if(!empty($data)){
                        if(isset($data['Supplier']) && isset($data['Supplier']['ID'])){
                            $supplier = $data['Supplier']; 
                            $SuppPubId = DB::table('suppliers')->where('public_id','=',$supplier['ID'])->where('active','=','1')->get(['id']);              
                            if(!empty($SuppPubId[0])){
                                $supId = $SuppPubId[0]->id;
                                if(isset($data['campaign']) && isset($data['campaign']['ID'])){
                                    $campaign = $data['campaign'];
                                    $CampPubId = DB::table('campaigns')->where('public_id','=',$campaign['ID'])->where('method','=','API')->where('active','=','1')->get(['id','client_id']);
                                    if(!empty($CampPubId[0])){
                                        $campId = $CampPubId[0]->id;
                                        $cliId = $CampPubId[0]->client_id;
                                        $data['campaign']['value']='True';
                                        if(isset($data['consumer'])){
                                            $consumer = $data['consumer'];
                                            if(!empty($consumer)){
                                                $consumer['supplier_id'] = $supId;
                                                $consumer['client_id'] = $cliId;
                                                $consumer['campaign_id'] = $campId;
                                                $consumer['delivery_flag'] = 'true';
                                                $consumer['audi_flag'] = 'true';
                                                if(!isset($consumer['email'])){
                                                    $consumer['email'] = '';
                                                }
                                                if(!isset($consumer['phone'])){
                                                    $consumer['phone'] = '';
                                                }
                                                if(!isset($consumer['title'])){
                                                    $consumer['title'] = '';
                                                }
                                                if(!isset($consumer['age'])){
                                                    $consumer['age'] = '';
                                                }
                                                if(!isset($consumer['ageRange'])){
                                                    $consumer['ageRange'] = '';
                                                }
                                                if(!isset($consumer['firstName'])){
                                                    $consumer['firstName'] = '';
                                                }
                                                if(!isset($consumer['lastName'])){
                                                    $consumer['lastName'] = '';
                                                }
                                                if(!isset($consumer['gender'])){
                                                    $consumer['gender'] = '';
                                                }
                                                if(!isset($consumer['birthdate'])){
                                                    $consumer['birthdate'] = '';
                                                }
                                                if(!isset($consumer['address1'])){
                                                    $consumer['address1'] = '';
                                                }
                                                if(!isset($consumer['address2'])){
                                                    $consumer['address2'] = '';
                                                }
                                                if(!isset($consumer['city'])){
                                                    $consumer['city'] = '';
                                                }
                                                if(!isset($consumer['state'])){
                                                    $consumer['state'] = '';
                                                }
                                                if(!isset($consumer['postcode'])){
                                                    $consumer['postcode'] = '';
                                                }
                                                if(!isset($consumer['countryCode'])){
                                                    $consumer['countryCode'] = '';
                                                }
                                                $_Model = new LeadProcessTableCsv();
                                                $result = $_Model->apiData($consumer);                        
                                                if($result['lead_disposition']['success']=='false'){
                                                    if(is_array($result['lead_disposition']['error']) && in_array('Duplicate Consumer', $result['lead_disposition']['error'])){
                                                        $leaddata['success'] = 'false';
                                                        $leaddata['errors'] = array('Duplicate Consumer');
                                                        return response()->json($leaddata,409);
                                                    } else {
                                                        $leaddata['success'] = 'false';
                                                        $leaddata['errors'] = $result['lead_disposition']['error'];
                                                        return response()->json($leaddata,400);
                                                    }
                                                } else {
                                                    $leaddata['success'] = 'true';
                                                    $leaddata['errors'] = array();                                            
                                                    return response()->json($leaddata);
                                                }
                                            } else {
                                                $leaddata['success'] = 'false';
                                                $leaddata['errors'] = array('Missing Consumer Data');
                                                return response()->json($leaddata,400);
                                            }
                                        } else {
                                            $leaddata['success'] = 'false';
                                            $leaddata['errors'] = array('Missing Consumer Data');
                                            return response()->json($leaddata,400);
                                        }
                                    } else {
                                        $leaddata['success'] = 'false';
                                        $leaddata['errors'] = array('Invalid Campaign');
                                        return response()->json($leaddata,400);
                                    }
                                } else {
                                    $leaddata['success'] = 'false';
                                    $leaddata['errors'] = array('Missing Campaign');
                                    return response()->json($leaddata,400);
                                }
                            } else {
                                $leaddata['success'] = 'false';
                                $leaddata['errors'] = array('Invalid Supplier');
                                return response()->json($leaddata,400);
                            }
                        } else {
                            $leaddata['success'] = 'false';
                            $leaddata['errors'] = array('Missing Supplier');
                            return response()->json($leaddata,400);
                        }
                    } else {
                        $leaddata['success'] = 'false';
                        $leaddata['errors'] = array('Invalid JSON object');
                        return response()->json($leaddata,400);
                    }
                } else {
                    $leaddata1 = array('success'=>'false','errors'=>'API Authorisation key is invalid');
                    return response()->json($leaddata1,401);
                }
            } else {
                $leaddata1 = array('success'=>'false','errors'=>'API Authorisation key is not present');
                return response()->json($leaddata1,401);
            }
        } else {
            $leaddata1 = array('success'=>'false','errors'=>'Method not allowed');
            return response()->json($leaddata1,400);
        }
    }

    public function create() {
        //
    }
     
    public function store(Request $request) {
        exit();
        $data = $request->all();
        if(!empty($data)){
            $data['accepted'] = '^OK$';
            $data['success'] = 'true';
            $data['errors'] = '';
            return response()->json($data);
        } else {
            $data['success'] = 'false';
            $data['errors'] = 'Invalid JSON object';
            return response()->json($data);
        }
    }

    public function clientapi(Request $request) {        
        $data = $request->all();
        if(!empty($data)){
            $data['accepted'] = '^OK$';
            $data['success'] = 'true';
            $data['errors'] = '';
            return response()->json($data);
        } else {
            $data['success'] = 'false';
            $data['errors'] = 'Invalid JSON object';
            return response()->json($data);
        }
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