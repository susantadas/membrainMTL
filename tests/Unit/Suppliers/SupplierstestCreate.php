<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use DB;
class Uservalidation {
    public static $rules = array(
        'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_email' => 'required|string|email|max:255|unique:suppliers',
        'contact_name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_phone' => 'required|numeric|min:11|unique:suppliers',
        'error_allowance' => 'required|numeric|max:100',
        'return_csv' => 'required|numeric|max:1',
        'active' => 'required|numeric|max:1',
    );
}

class SupplierstestCreate extends TestCase
{
    public function test_for_blank_email() {
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank email present in this array \n\nInput: contact_email = '' \nOutput: The contact email field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
	}  

    public function test_for_existing_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas2@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Existing email present in this array \n\nInput: contact_email = '".$data['contact_email']."' \nOutput: The contact email already exist.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas2','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid email present in this array \n\nInput: contact_email = '".$data['contact_email']."' \nOutput: The contact email must be a valid email address.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid email present in this array \n\nInput: contact_email = '".$data['contact_email']."' \nOutput: The contact email is valid email address.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_phone() {
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank phone present in this array \n\nInput: contact_phone = '".$data['contact_phone']."' \nOutput: The contact phone field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }  

    public function test_for_existing_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'8888899999','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Existing phone present in this array \n\nInput: contact_phone = '".$data['contact_phone']."' \nOutput: The contact phone already exist.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'88888fgfdgfd','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid phone present in this array \n\nInput: contact_phone = '".$data['contact_phone']."' \nOutput: The contact phone must be a numeric and 11 digits.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid phone present in this array \n\nInput: contact_phone = '".$data['contact_phone']."' \nOutput: The contact phone is valid Phone.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_name(){
       $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Empty name present in this array \n\nInput: name = '".$data['name']."' \nOutput: The name field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_name(){
       $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta@123','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid name present in this array \n\nInput: name = '".$data['name']."' \nOutput: Name field Special character and Number not allowed.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid name present in this array \n\nInput: name = '".$data['name']."' \nOutput: The name is valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_contact_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);       
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank  contact name present in this array \n\nInput: contact_name = '".$data['contact_name']."' \nOutput: The name field is required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_contact_name(){
       $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta/Kumar/Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid contact name present in this array \n\nInput: contact_name = '".$data['contact_name']."' \nOutput: Contact name field Special character and Number not allowed.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_contact_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid contact name present in this array \n\nInput: contact_name = '".$data['contact_name']."' \nOutput: The contact name is valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank error allowance present in this array \n\nInput: error_allowance = '".$data['error_allowance']."' \nOutput: Error allowance required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'1ddd','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid error allowance present in this array \n\nInput: error_allowance = '".$data['error_allowance']."' \nOutput: Error allowance field Special character not allowed.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid error allowance present in this array \n\nInput: error_allowance = '".$data['error_allowance']."' \nOutput: valid error allowance.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);  
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank return csv present in this array \n\nInput: return_csv = '".$data['return_csv']."' \nOutput: return csv required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1xsd','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid return csv present in this array \n\nInput: return_csv = '".$data['return_csv']."' \nOutput: Return csv field Special character not allowed.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid return csv present in this array \n\nInput: return_csv = '".$data['return_csv']."' \nOutput: Return csv is valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_for_blank_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 

        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Blank active present in this array \n\nInput: active = '".$data['active']."' \nOutput: active required.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_format_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'10g');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        if($validator->fails()==1){
            $massage = "*********************************************************************\nDescription: Invalid active present in this array \n\nInput: active = '".$data['active']."' \nOutput: Active field Special character not allowed.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->fails());
    }

    public function test_for_pass_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        if($validator->passes()==1){
            $massage = "*********************************************************************\nDescription: Valid active present in this array \n\nInput: active = '".$data['active']."' \nOutput: Active field valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertTrue($validator->passes());
    }

    public function test_create_supplier(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardasnew@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345678901','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);  
        $check = $validator->passes();
        $this->assertTrue($check);
        /*if($check == 1){
            factory(\App\Supplier::class)->create($data);
            $this->assertDatabaseHas('suppliers', $data);
            $massage = "\n*********************************************************************\nDescription: Valid present array \n\nInput: data = '' \nOutput: Valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }*/

        if($check){
            $massage = "\n*********************************************************************\nDescription: Valid active present in this array \n\nInput: active = '".$data['active']."' \nOutput: Successfully created.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
    }
}