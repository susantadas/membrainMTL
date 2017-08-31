<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Portal;
use DB;
class Uservalidation {
    public static $rules = array(
        'username' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',
    );
}

class UserTestLogin extends TestCase
{
    public function test_for_blank_usename() {
        $data =  array('username'=>'','password'=>bcrypt('123456'));
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "\n*********************************************************************\nDescription: Blank username present in this array \nInput: username='".$data['username']."' \nOutput: The username field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
	}  

    public function test_for_existing_usename(){
        $data =  array('username'=>'isusantakumardas@gmail.com','password'=>bcrypt('123456'));
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        if($validator->passes()==1){
            $massage = "\n*********************************************************************\nDescription: Exist username present in this array \nInput: username='".$data['username']."' \nOutput: The username valid email address.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->passes());
    }

    public function test_for_format_usename(){
        $data =  array('username'=>'isusantakumardas2','password'=>bcrypt('123456'));
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "\n*********************************************************************\nDescription: Invalid username present in this array \nInput: username='".$data['username']."' \nOutput: The username must be a valid email address.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
    }

    public function test_for_blank_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','password'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "\n*********************************************************************\nDescription: Blank password present in this array \nInput: password='".$data['password']."' \nOutput: The password field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
    }

    public function test_for_minsixlength_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','password'=>'12345');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "\n*********************************************************************\nDescription: invalid password present in this array \nInput: password='".$data['password']."'  \nOutput: The password must be at least 6 characters.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertTrue($validator->fails());
    }    

    public function test_login_user_invalid(){
        $data =  array('username'=>'isusantakumardas4@gmail.com');
        $count = DB::Table('protal_user')
        ->where('username','=',$data['username'])
        ->count();
        if($count <= 0){
            $massage = "\n*********************************************************************\nDescription: Not found email id present in this array \nInput: Invalid username  \nOutput: Found records in database table protal_user that matched attributes.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertEquals(0,$count);
    }

    public function test_login_user_valid(){
        $data =  array('username'=>'isusantakumardas@gmail.com');
        $count = DB::Table('protal_user')
        ->where('username','=',$data['username'])
        ->count();
        if($count > 0){
            $massage = "\n*********************************************************************\nDescription: Found email id present in this array \nInput: Valid username  \nOutput: Not found any records in database table protal_user that matched attributes.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        } 
        $this->assertEquals(1,$count); 
    }

    public function test_login_user(){
        $data =  array('username'=>'isusantakumardas@gmail.com','password'=>'123456');        
        $user = Auth::attempt(['username' => $data['username'], 'password' => $data['password']]); 
        if($user==1){
            $massage = "\n*********************************************************************\nDescription: Valid login details present in this array \nInput: Valid login details  \nOutput: successfully login.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }     
        $this->assertEquals(1,$user);
    }
}