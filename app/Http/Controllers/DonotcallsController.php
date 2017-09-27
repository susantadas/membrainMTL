<?php // Controller name Domainblacklists
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Donotcall; //Model name and Where model have
use Excel; //csv file reader
use DB;
use App\Models\Api\LeadProcessTableCsv; //Model name and Where model have
class DonotcallsController extends Controller
{   
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of Donotcalls */
    public function index() {
        $donotcalls = Donotcall::all()->count();
        return view('donotcalls.list')
            ->with(compact('donotcalls'));
    }
    /* End show all the list of Donotcalls */
    /* Start show created page not needed */
    public function create() {
        echo date('Y-m-d H:i:s');
        //
    }
    /* End show created page not needed */
   /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        set_time_limit(0);
        try{
            $error = array();
            $succe = array();
            $dup = array();
            $notformeted = array();
            $empty = 1;
            $regexP = '/^[0-9]*$/';
            if($request->hasFile('myfile')){
                $path = $request->file('myfile')->getRealPath();
                $CountData =  Excel::selectSheetsByIndex(0)->load($path, function($reader){
                    $results = $reader->noHeading();
                })->get();
                if(!empty($CountData) && $CountData->count() > 0){
                    $Donotcall = DB::table('membrain_global_do_not_call')->truncate();
                    foreach ($CountData as $key => $lineP) {
                        $linePTabdilimited = explode("\t",$lineP[0]);
                        $country_code = (isset($linePTabdilimited[0]) ? $linePTabdilimited[0] : (isset($lineP[0]) ? $lineP[0] : ''));
                        $phone_number = (isset($linePTabdilimited[1]) ? $linePTabdilimited[1] : (isset($lineP[1]) ? $lineP[1] : ''));
                        $reason_code = (isset($linePTabdilimited[2]) ? $linePTabdilimited[2] : (isset($lineP[2]) ? $lineP[2] : ''));
                        $value = array('country_code'=>($country_code!='' ? $country_code : ''),'phone_number'=>($phone_number!='' ? $phone_number : ''),'reason_code'=>($reason_code!='' ? $reason_code : ''));
                        if(!empty($value['phone_number'])){
                            if(!empty($value) && $value['phone_number']!=''){
                                $validation = Validator::make($value,array(
                                    'phone_number' => 'required|numeric|unique:membrain_global_do_not_call',
                                ));

                                if($validation->fails()) {
                                    $dup[]=0;
                                } else {
                                    /* Start Check country wise phone number */
                                    $cunCode = $value['country_code'];
                                    if($cunCode!=''){
                                        $LeadProcessValidation = new LeadProcessTableCsv();
                                        $pnpps = $LeadProcessValidation->phoneNumberPreProcessing($value['phone_number'],$cunCode);
                                        if($pnpps['status']=='0'){
                                            $valid = 0;
                                            $errorsNew= 'Invalid Phone Number';
                                            $st = 2;
                                        } elseif ($pnpps['status']=='2') {
                                            $valid = 0;
                                            $errorsNew = 'Invalid Phone Number and country code';
                                            $st = 2;
                                        } elseif ($pnpps['status']=='3'){
                                            $valid = 0;
                                            $errorsNew = 'Required phone number';
                                            $st = 2;
                                        } else {
                                            $valid = 1;
                                            $newphoneNumber = $pnpps['phone'];
                                        }
                                    } else {
                                        $valid = 0;
                                        $errorsNew = 'Missing country. Please select country';
                                        $st = 2;
                                    } 
                                    /* End Check country wise phone number */
                                    if($valid == 1){
                                        $donotcall = new Donotcall;
                                        $donotcall->country_code = $value['country_code'];
                                        $donotcall->phone_number = $value['phone_number'];
                                        $donotcall->reason_code = $value['reason_code'];
                                        if(!$donotcall->save()){
                                            $error[]=0;
                                        } else {
                                            $succe[] = 1;
                                        }
                                    } else {
                                        $error[]=0;
                                    }
                                }
                            }
                        } else {
                            $notformeted[] = 1;
                        }
                    }
                } else {
                    $empty=0;
                }
            }
            if(count($succe)>1){
                $status=1;
            } else {
                $status=0;
            }
            return array('status'=>$status,'error'=>(count($error) + count($dup)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'notformeted'=>count($notformeted));
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return array('status'=>'2','error'=>$e->getMessage());
            }
        }
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($donotcalls) {
        //
    }
    /* End show created page not needed */
    //* Start show created page not needed */
    public function update(Request $request, $donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start particular quarantines csv file */
    public function downloadExcelFile($type){
        try{
            $donotcalls = Donotcall::get(['country_code','phone_number','reason_code'])->toArray();
            return Excel::create('donotcall', function($excel) use ($donotcalls) {
                $excel->sheet('donotcall', function($sheet) use ($donotcalls) {
                    $sheet->fromArray($donotcalls);
                });
            })->download($type);
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return $e->getMessage();
            }
        }
    }
    /* End particular quarantines csv file */
}