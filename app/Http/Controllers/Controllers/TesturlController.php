<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Testmodel;
use DB;
use Excel; //CSV reader
use Carbon\Carbon;
class TesturlController extends Controller
{
    public function __construct()  {
        $this->middleware('auth');
    }

    public function index() {
        return view('testurl.list');
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        if($request->hasFile('myfile')){
            $_data = array();
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = (array)$CountData;
            print_r($data);
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