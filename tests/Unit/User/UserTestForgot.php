<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Portal;
use DB;
class Uservalidation {
    public static $rules = array(
        'username' => 'required|string|email|max:255|unique:protal_user',
    );
}

class UserTestForgot extends TestCase
{
    public function test_for_blank_email() {
        $data =  array('username'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank username present in this array \nInput: username='".$data['username']."' \nOutput: The username field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
	}  

    public function test_for_notfind_email(){
        $data =  array('username'=>'isusantakumardasnedas@gmail.com');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Not Existing username present in this array \nInput: username='".$data['username']."' \nOutput: The username not find.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->passes());
    }

    public function test_for_format_email(){
        $data =  array('username'=>'isusantakumardas');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid username present in this array \nInput: username='".$data['username']."' \nOutput: he username must be a valid email address.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_email(){
        $data =  array('username'=>'isusantakumardas@gmail.com');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        if($validator->fails()==1){
            $massage = "\n*********************************************************************\nDescription: Valid username present in this array \nInput: username='".$data['username']."' \nOutput: The username have in database.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
    }
}