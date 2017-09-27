<?php // Controller name salaciouswords
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Salaciousword; //Model name and Where model have
use Excel; //csv file reader
use DB;
class SalaciouswordsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of salaciouswords */
    public function index() {
        $salaciouswords = Salaciousword::all()->count();
        return view('salaciouswords.list')
            ->with(compact('salaciouswords'));
    }
    /* Start show all the list of salaciouswords */
    /* Start show created page not needed */
    public function create() {
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
            $notformeted = 0;
            $empty = 1;
            if($request->hasFile('myfile')){
                $path = $request->file('myfile')->getRealPath();
                $CountData =  Excel::selectSheetsByIndex(0)->load($path, function($reader){
                    $results = $reader->noHeading();
                })->get();
                if(!empty($CountData) && $CountData->count() > 0){
                    $SalaciouswordTruncate = DB::table('salacious_word')->truncate();
                    foreach ($CountData as $key => $lineSW) {
                        $lineSWTabdilimited = explode("\t",$lineSW[0]);
                        $pattern = (isset($lineSWTabdilimited[0]) ? $lineSWTabdilimited[0] : (isset($lineSW[0]) ? $lineSW[0] : '0'));
                        $email_address = (isset($lineSWTabdilimited[1]) ? $lineSWTabdilimited[1] : (isset($lineSW[1]) ? $lineSW[1] : '0'));
                        $first_name = (isset($lineSWTabdilimited[2]) ? $lineSWTabdilimited[2] : (isset($lineSW[2]) ? $lineSW[2] : '0'));
                        $last_name = (isset($lineSWTabdilimited[3]) ? $lineSWTabdilimited[3] : (isset($lineSW[3]) ? $lineSW[3] : '0'));
                        $address = (isset($lineSWTabdilimited[4]) ? $lineSWTabdilimited[4] : (isset($lineSW[4]) ? $lineSW[4] : '0'));
                        $value = array('pattern'=>$pattern,'email_address'=>$email_address,'first_name'=>$first_name,'last_name'=>$last_name,'address'=>$address);
                        if(isset($value['pattern'])){
                            if(!empty($value) && $value['pattern']!=''){
                                $validation = Validator::make($value,array(
                                    'pattern' => 'required|string|max:255|unique:salacious_word',
                                ));

                                if($validation->fails()) {
                                    $dup[]=0;
                                } else {
                                    $salaciousword = new Salaciousword;
                                    $salaciousword->pattern = $value['pattern'];
                                    $salaciousword->email_address = (bool)$value['email_address'];
                                    $salaciousword->first_name = (bool)$value['first_name'];
                                    $salaciousword->last_name = (bool)$value['last_name'];
                                    $salaciousword->address = (bool)$value['address'];
                                    if(!$salaciousword->save()){
                                        $error[]=0;
                                    } else {
                                        $succe[] = 1;
                                    }
                                }
                            }
                        } else {
                            $notformeted = 1;
                        }
                    }
                } else {
                    $empty = 0;
                }
            }
            if(count($succe)>0){
                $status=1;
            } else {
                $status=0;
            }

            return array('status'=>$status,'error'=> (count($error) + count($dup)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'notformeted'=>$notformeted);
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return array('status'=>'2','error'=>$e->getMessage());
            }
        }
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function update(Request $request, $salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start particular salaciouswords csv file */
    public function downloadExcelFile($type){
        try{
            $salaciouswords = Salaciousword::get(['pattern','email_address','first_name','last_name','address'])->toArray();
            return Excel::create('salaciouswords', function($excel) use ($salaciouswords) {
                $excel->sheet('salaciouswords', function($sheet) use ($salaciouswords) {
                    $sheet->fromArray($salaciouswords);
                });
            })->download($type);
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return  $e->getMessage();
            }
        }
    }
    /* Start particular salaciouswords csv file */
}