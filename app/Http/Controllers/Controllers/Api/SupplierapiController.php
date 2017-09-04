<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\LeadProcessTable;
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
            if($headerApiKey == $actualKey){
                $data = $request->all();
                if(isset($data['Supplier']) && isset($data['Supplier']['ID'])){
                    $supplier = $data['Supplier']; 
                    $SuppPubId = DB::table('suppliers')->where('public_id','=',$supplier['ID'])->where('active','=','1')->get(['id']);              
                    if(!empty($SuppPubId[0])){
                        $supId = $SuppPubId[0]->id;
                        if(isset($data['campaign']) && isset($data['campaign']['ID'])){
                            $campaign = $data['campaign'];
                            $CampPubId = DB::table('campaigns')->where('public_id','=',$campaign['ID'])->where('active','=','1')->get(['id','client_id']);
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
                                        $_Model = new LeadProcessTable();
                                        $result = $_Model->apiData($consumer);                        
                                        if($result['lead_disposition']['success']=='false'){
                                            if(is_array($result['lead_disposition']['error'])){
                                                $data['success'] = 'false';
                                                $data['errors'] = $result['lead_disposition']['error'];
                                                unset($result['error']);
                                                unset($data['supplier_id']);
                                                unset($data['client_id']);
                                                unset($data['campaign_id']);
                                                unset($result['audi_flag']);
                                                unset($result['lead_disposition']);
                                                return response()->json($data,400);
                                            } else {
                                                $data['success'] = 'false';
                                                $data['errors'] = array('Duplicate Consumer');
                                                unset($result['error']);
                                                unset($data['supplier_id']);
                                                unset($data['client_id']);
                                                unset($data['campaign_id']);
                                                unset($result['audi_flag']);
                                                unset($result['lead_disposition']);
                                                return response()->json($data,409);
                                            }
                                        } else {
                                            unset($data['consumer']);
                                            unset($result['supplier_id']);
                                            unset($result['client_id']);
                                            unset($result['campaign_id']);
                                            unset($result['audi_flag']);
                                            unset($result['lead_disposition']);
                                            $data['consumer'] = $result;
                                            $data['success'] = 'true';
                                            $data['errors'] = array();
                                            return response()->json($data);
                                        }
                                    } else {
                                        $data['success'] = 'false';
                                        $data['errors'] = array('Missing Consumer Data');
                                        return response()->json($data,400);
                                    }
                                } else {
                                    $data['success'] = 'false';
                                    $data['errors'] = array('Missing Consumer Data');
                                    return response()->json($data,400);
                                }
                            } else {
                                $data['campaign']['value']='False';
                                $data['success'] = 'false';
                                $data['errors'] = array('invalid Campaign');
                                return response()->json($data,400);
                            }
                        } else {
                            $data['campaign']['value']='False';
                            $data['success'] = 'false';
                            $data['errors'] = array('Campaign Id Missing');
                            return response()->json($data,400);
                        }
                    } else {
                        $data['success'] = 'false';
                        $data['errors'] = array('invalid Supplier');
                        return response()->json($data,400);
                    }
                } else {
                    $data['success'] = 'false';
                    $data['errors'] = array('Supplier Id Missing');
                    return response()->json($data,400);
                }
            } else {
                $data = array('success'=>'false','errors'=>'API Authorisation key is not present');
                return response()->json($data,401);
            }
        } else {
            $data = array('success'=>'false','errors'=>'Method not allowed');
            return response()->json($data,400);
        }
    }

    public function create() {
        //
    }
     
    public function store(Request $request) {
        $data = $request->all();
        if(!empty($data)){
            $data['accepted'] = '^OK$';
            $data['success'] = 'true';            
            /*if($_data['duplicate']=='Duplicate'){
                $_data['duplicate'] = 'Duplicate';
                $data['errors'] = 'Duplicate Consumer';
            } elseif ($_data['rejected']=='Rejection') {
                $data['errors'] = 'Client rejected this Consumer Data';
            } else {
                $data['errors'] = '';
            }*/
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
            /*if($_data['duplicate']=='Duplicate'){
                $_data['duplicate'] = 'Duplicate';
                $data['errors'] = 'Duplicate Consumer';
            } elseif ($_data['rejected']=='Rejection') {
                $data['errors'] = 'Client rejected this Consumer Data';
            } else {
                $data['errors'] = '';
            }*/
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