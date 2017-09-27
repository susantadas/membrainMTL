<?php // Controller name Suppliers
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Supplier;  //Model name and Where model have
use DB;
class SuppliersController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of suppliers */
    public function index() {
        $suppliers=DB::select( DB::raw("SELECT suppliers.*,(SELECT COUNT(*) FROM protal_user WHERE protal_user.supplier_id = suppliers.id) AS PortalUser FROM  suppliers ORDER BY suppliers.name ASC"));
        return view('suppliers.list')->with(compact('suppliers'));
    }
    /* end show all the list of suppliers */
    /* Start show created page */
    public function create() {
        return view('suppliers.create');
    }
    /* end show created page */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        try{
            $validation = Validator::make($request->input(), array(
                'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
                'contact_email' => 'required|string|email|max:255|unique:suppliers',
                'contact_name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
                'contact_phone' => 'required|numeric|max:255|unique:suppliers',
                'error_allowance' => 'required|numeric|max:3',
            ));
            if($validation->fails()) {
                return 2;
            } else {
                $result = DB::table('suppliers')->where('contact_email',$request->input('contact_email'))->where('contact_phone','=',$request->input('contact_phone'),'OR')->get(['id']);
                if($result->count() <='0'){
                    $suppliers = new Supplier;
                    $suppliers->public_id = hash('md5', 'MB#'.str_random(42));
                    $suppliers->name = $request->input('name');
                    $suppliers->contact_email = $request->input('contact_email');
                    $suppliers->contact_name = $request->input('contact_name');
                    $suppliers->contact_phone = $request->input('contact_phone');
                    $suppliers->error_allowance = $request->input('error_allowance');
                    $suppliers->return_csv = (bool)$request->input('return_csv');
                    $suppliers->active = (bool)$request->input('active');        
                    if(!$suppliers->save()){
                        return 0;
                    } else {
                        \Session::flash('success','Supplier has been created.');
                        return 1;
                    }
                } else {
                    return 2;
                }
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return  0;
            }
        }
    }
    /* end new data store in database */
    /* Start show supplier details not need this project */
    public function show($supplier) {
        //
    } 
    /* end show supplier details not need this project */
    /* Start show edit page with all data particuler supplier */
    public function edit($supplier) {
        $suppliers = Supplier::find($supplier);
        return view('suppliers.edit',compact('suppliers'));
    }
    /* end show edit page with all data particuler supplier */
    /* Start data update in database for particular supplier with parameters(all request data & and supplier id)*/
    public function update(Request $request, $supplier) {
        try{            
            $result = DB::table('suppliers')->where('contact_email',$request->input('contact_email'))->where('contact_phone','=',$request->input('contact_phone'),'OR')->get(['id']);
            if($result->count()==1 && $result[0]->id==$supplier){
                $suppliers = Supplier::find($supplier);
                $suppliers->name = $request->input('name');
                $suppliers->contact_email = $request->input('contact_email');
                $suppliers->contact_name = $request->input('contact_name');
                $suppliers->contact_phone = $request->input('contact_phone');
                $suppliers->error_allowance = $request->input('error_allowance');
                $suppliers->return_csv = (bool)$request->input('return_csv');
                $suppliers->active = (bool)$request->input('active'); 
                if(!$suppliers->save()){
                    return 0;
                } else {
                    \Session::flash('success','Supplier has been updated.');
                    return 1;
                }
            } else {
                return 2;
            }            
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return  0;
            }
        }
    }
    /* end data update in database for particular supplier with parameters(all request data & and supplier id) */
    /* Start particular supplier delete from database */
    public function destroy($supplier) {
        try{
            $_supplier = Supplier::find($supplier);
            if(!$_supplier->delete()){
                return 0;
            } else {
                return 1;
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return  0;
            }
        }
    }
    /* end particular supplier delete from database */
}