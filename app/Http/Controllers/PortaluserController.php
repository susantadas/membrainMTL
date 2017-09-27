<?php  // Controller name Clients
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Portal; //Model name and Where model have
use App\Client; //Model name and Where model have
use App\Supplier; //Model name and Where model have
use App\Portalrole; //Model name and Where model have
use App\Portalsubclient; //Model name and Where model have
use DB;
class PortaluserController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of portalusers */
    public function index() {
        $portalusers = DB::table('protal_user as portalusers')
            ->select('portalusers.*','clients.name as cname','suppliers.name as sname','portal_role.name as rname')
            ->leftjoin('clients', 'portalusers.client_id', '=', 'clients.id')
            ->leftjoin('suppliers', 'portalusers.supplier_id', '=', 'suppliers.id')
            ->leftjoin('portal_role', 'portalusers.role_id', '=', 'portal_role.id')
            ->orderBy('portalusers.name', 'ASC')
            ->get();
        return view('portaluser.list')->with(compact('portalusers'));
    }
    /* end show all the list of portalusers */
    /* Start show created page */
    public function create() {
        $clients = Client::select('id','name')->get();
        $suppliers = Supplier::select('id','name')->get();
        $portalroles = Portalrole::all();
        return view('portaluser.create')
            ->with(compact('clients'))
            ->with(compact('portalroles'))
            ->with(compact('suppliers'));
    }
    /* end show created page */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        try{
            $validation = Validator::make($request->input(), array(
                'username' => 'required|string|email|max:255|unique:protal_user',
                'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
                'password' => 'required|string|min:6',
                'role_id' => 'required|numeric|max:1',
            ));
            if($validation->fails()) {
                return 2;
            } else {
                $result = DB::table('protal_user')->where('username',$request->input('username'))->get(['id']);
                if($result->count() <= '0'){
                    $portaluser = new Portal;
                    $portaluser->username = $request->input('username');
                    $portaluser->name = $request->input('name');
                    $portaluser->password = bcrypt($request->input('password'));
                    $portaluser->role_id = $request->input('role_id');
                    $portaluser->client_id = ($request->input('client_id')=='' ? 0 : $request->input('client_id'));
                    $portaluser->supplier_id = ($request->input('supplier_id')=='' ? 0 : $request->input('supplier_id'));
                    $portaluser->active = (bool)$request->input('active');
                    if(!$portaluser->save()){
                        return 0;
                    } else {
                        if(!empty($request->input('clients_id')) && $request->input('client_id')=='' && $request->input('role_id')=='5'){
                            foreach ($request->input('clients_id') as $key => $client_id) {
                                $portalsubclient = new Portalsubclient;
                                $portalsubclient->portal_user_id = $portaluser->id;
                                $portalsubclient->client_id = $client_id;
                                $portalsubclient->save();
                            }
                        }
                        \Session::flash('success','Portal User has been created.');
                        return 1;
                    }
                } else {
                    return 2;
                }
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return 2;
            }
        }
    }
    /* end new data store in database */
    /* Start show portaluser details not need this project */
    public function show($portaluser) {
        //
    }
    /* end show portaluser details not need this project */
    /* Start show edit page with all data particuler portaluser */
    public function edit($portaluser) {
        $id = $portaluser;
        $clients = Client::select('id','name')->get();;
        $suppliers = Supplier::select('id','name')->get();
        $portalroles = Portalrole::all();
        $portaluser = Portal::find($id);
        $supclientsobj = $portalsubclients = DB::table('portal_sub_client')->where('portal_user_id','=',$id)->get();
        $supclients_arr=array();
        foreach ($supclientsobj as $key => $value) {
            array_push($supclients_arr, $value->client_id);
        }

        return view('portaluser.edit')
            ->with(compact('portaluser'))
            ->with(compact('clients'))
            ->with(compact('portalroles'))
            ->with(compact('suppliers'))
            ->with(compact('supclients_arr'));
    }
    /* end show edit page with all data particuler portaluser */
    /* Start data update in database for particular portaluser with parameters(all request data & and portaluser id)*/
    public function update(Request $request, $portaluser) {
        try{
            $_portaluser = Portal::find($portaluser);
            $result = DB::table('protal_user')->where('username',$request->input('username'))->get(['id']);
            $_portaluser->username = $request->input('username');
            $_portaluser->name = $request->input('name');
            $_portaluser->role_id = $request->input('role_id');
            $_portaluser->client_id = $request->input('client_id');
            $_portaluser->supplier_id = $request->input('supplier_id');
            $_portaluser->active = (bool)$request->input('active');
            $newdata = array();
            if(!$_portaluser->save()){
                return 0;
            } else {
                if($request->input('role_id')==5){
                    if(!empty($request->input('clients_id')) && $request->input('client_id')=='0'){
                        /* Start associate client data particuler portaluser */
                        $portalsubclients = DB::table('portal_sub_client')->where('portal_user_id',$portaluser)->get(['client_id']);
                        if(!empty($portalsubclients)){
                            foreach ($portalsubclients as $key => $client) {
                                $newdata[] = $client->client_id;
                            }
                        }

                        foreach ($request->input('clients_id') as $key => $client_id) {
                            if(!empty($client_id)){
                                if(!in_array($client_id, $newdata)){
                                    $portalsubclient = new Portalsubclient;
                                    $portalsubclient->portal_user_id = $portaluser;
                                    $portalsubclient->client_id = $client_id;
                                    $portalsubclient->save();
                                } else {
                                    if(($key = array_search($client_id, $newdata)) !== false){
                                        unset($newdata[$key]);
                                    }
                                }
                            }
                        }
                        
                        foreach($newdata as $r => $cid) {
                            DB::table('portal_sub_client')->where('client_id',$cid)->where('portal_user_id','=',$portaluser,'AND')->delete();
                        }
                        /* End associate client data particuler portaluser */
                    } else {
                        $_ortalsubclient = Portalsubclient::find($portaluser);                
                        if(sizeof($_ortalsubclient)>0){
                            foreach ($_ortalsubclient as $okey => $ovalue) {
                                DB::table('portal_sub_client')->where('id', $ovalue['id'])->delete(); 
                            }
                        }   
                    }
                }
                \Session::flash('success','Portal User has been updated.');
                return 1;
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return 0;
            }
        }
    }
    /* end data update in database for particular portaluser with parameters(all request data & and portaluser id) */
    /* Start particular portaluser delete from database */
    public function destroy($portaluser) {
        try{
            $_portaluser = Portal::find($portaluser);
            if(!$_portaluser->delete()){
                return 0;
            } else {
                DB::table('portal_sub_client')->where('portal_user_id',$portaluser)->delete();
                \Session::flash('success','Portal User has been Deleted.');
                return 1;
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return 0;
            }
        }
    }
    /* end particular portaluser delete from database */
    /* Start particular portaluser delete from database */
    public function listdelete($portaluser) {
        try{
            $_portaluser = Portal::find($portaluser);
            if(!$_portaluser->delete()){
                return 0;
            } else {
                DB::table('portal_sub_client')->where('portal_user_id',$portaluser)->delete();
                return 1;
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                return 0;
            }
        }
    }
    /* end particular portaluser delete from database */
}