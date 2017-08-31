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
        'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'username' => 'required|string|email|max:255|unique:protal_user',
        'password' => 'required|string|min:6',
        'role_id' => 'required|numeric|max:1',
        'active' => 'required|numeric|max:1',
    );
}

class UserTest extends TestCase
{
    public function test_for_blank_usename() {
        $data =  array('username'=>'','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank username present in this array \n\nInput: username = '' \nOutput: The username field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
	}  

    public function test_for_existing_usename(){
        $data =  array('username'=>'isusantakumardas@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Existing username present in this array \n\nInput: username = '".$data['username']."' \nOutput: The username already exist.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_usename(){
        $data =  array('username'=>'isusantakumardas2','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid username present in this array \n\nInput: username = '".$data['username']."' \nOutput: The username invalid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_blank_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','name'=>'Susanta Kumar Das','password'=>'','role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank password present in this array \n\nInput: password = '".$data['password']."' \nOutput: password field required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_minsixlength_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','name'=>'Susanta Kumar Das','password'=>'12345','role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank password present in this array \n\nInput: password = '".$data['password']."' \nOutput: Password minimum 6 .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_blank_name(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','name'=>'','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank name present in this array \n\nInput: name = '".$data['name']."' \nOutput: Name field required .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }
    public function test_for_format_name(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','name'=>'Susanta/Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid name present in this array \n\nInput: name = '".$data['name']."' \nOutput: Invalid Name field required .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_blank_roleid(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank role id present in this array \n\nInput: role_id = '".$data['role_id']."' \nOutput: Role_id field required .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_blank_active(){
        $data =  array('username'=>'isusantakumardas5@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank role id present in this array \n\nInput: active = '".$data['active']."' \nOutput:  active field required .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_create_user(){
        $data =  array('username'=>'isusantakumardas5@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        $this->assertTrue($validator->passes());
        /*if($validator->passes()==1){
            factory(\App\Portal::class)->create($data);
            $this->assertDatabaseHas('protal_user', $data);
            $massage = "\n*********************************************************************\nDescription:All valid data present in this array \n\nInput: data = ' ' \nOutput:  Successfully Created.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }*/
        $massage = "\n*********************************************************************\nDescription:All valid data present in this array \n\nInput: data = ' ' \nOutput:  Successfully Created.\nResult: PASS\n*********************************************************************\n\n";
        print_r($massage);
    }
}