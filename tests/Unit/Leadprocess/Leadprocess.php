<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\LeadProcessTable;
use App\Models\Api\LeadProcessValidation;
use DB;
class Leadprocess extends TestCase
{
    public function test_for_blank_supplier() {
        $data = array("campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU",'campaign_id'=>'12','client_id'=>'5'));
        $vali = new LeadProcessValidation();
        $result = $vali->validateSupplier($data);
        if($result==0){
            $massage = "*********************************************************************\nDescription: No supplier paramiter present in this array \nInput: Supplier ID = '' \nOutput: invalid Supplier.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);
    } 

    public function test_for_blank_supplier_id() {
        $data = array("Supplier"=>array("source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessValidation();
        $result = $vali->validateSupplier($data);
        if($result==0){
            $massage = "*********************************************************************\nDescription: No supplier id paramiter present in this array \nInput: Supplier ID = '' \nOutput: invalid Supplier.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);
    }

    public function test_for_blank_supplier_incr_id() {
        $data = array("Supplier"=>array("ID"=>"407b20e04c238xssadsab3feda82fd167e6068a","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        
        $SuppPubId = DB::table('suppliers')->where('public_id','=',$data['Supplier']['ID'])->where('active','=','1')->get(['id']);        
        if(empty($SuppPubId[0])){
            $valid = 0;
        }
        if($valid==0){            
            $massage = "*********************************************************************\nDescription: Incorrect supplier id present in this array \nInput: Supplier ID = '".$data['Supplier']['ID']."' \nOutput: invalid Supplier.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_blank_supplier_inactv_id() {
        $data = array("Supplier"=>array("ID"=>"0cdae0d8e49f6337450bb912db521e49","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        
        $SuppPubId = DB::table('suppliers')->where('public_id','=',$data['Supplier']['ID'])->where('active','=','1')->get(['id']);        
        if(empty($SuppPubId[0])){
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Inactive supplier id present in this array \nInput: Supplier ID = '".$data['Supplier']['ID']."' \nOutput: invalid Supplier.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_blank_supplier_valid_id() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        
        $SuppPubId = DB::table('suppliers')->where('public_id','=',$data['Supplier']['ID'])->where('active','=','1')->get(['id']);        
        if(!empty($SuppPubId[0])){
            $valid = 0;
        }
        if($valid==0){            
            $massage = "*********************************************************************\nDescription: valid & active supplier id present in this array \nInput: Supplier ID = '".$data['Supplier']['ID']."' \nOutput: Valid Supplier.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_blank_campaign() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessValidation();
        $result = $vali->validateCampaignr($data);
        if($result==0){
            $massage = "*********************************************************************\nDescription: No campaign paramiter present in this array \nInput: Supplier ID = '' \nOutput: invalid campaign.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);

        
    } 
    public function test_for_blank_campaign_id() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("value"=>"false"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessValidation();
        $result = $vali->validateCampaignr($data);
        if($result==0){
            $massage = "*********************************************************************\nDescription: No campaign id paramiter present in this array \nInput: Campaign ID = '' \nOutput: invalid campaign.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);        
    }


    public function test_for_blank_campaign_incr_id() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7csvdsc1b602de0ccb2","value"=>"false"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        
        $CampPubId = DB::table('campaigns')->where('public_id','=',$data['campaign']['ID'])->where('active','=','1')->get(['id','client_id']);
        if(empty($CampPubId[0])){
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Incorrect campaign id paramiter present in this array \nInput: Campaign ID = '".$data['campaign']['ID']."' \nOutput: invalid campaign.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_blank_campaign_inactv_id() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"f08db846e431b346a4020fd249e1717d"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        
        $CampPubId = DB::table('campaigns')->where('public_id','=',$data['campaign']['ID'])->where('active','=','1')->get(['id','client_id']);
        if(empty($CampPubId[0])){
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Inactive campaign id paramiter present in this array \nInput: Campaign ID = '".$data['campaign']['ID']."' \nOutput: invalid campaign.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_blank_campaign_valid_id() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@Outlook.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        
        $CampPubId = DB::table('campaigns')->where('public_id','=',$data['campaign']['ID'])->where('active','=','1')->get(['id','client_id']);
        if(!empty($CampPubId[0])){
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Valid & active campaign id paramiter present in this array \nInput: Campaign ID = '".$data['campaign']['ID']."' \nOutput: Valid campaign.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }


    public function test_for_blank_consumer() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"));
        $vali = new LeadProcessValidation();
        $result = $vali->validateConsumer($data);
        if($result==0){
            $massage = "*********************************************************************\nDescription: No consumer paramiter present in this array \nInput: Consumer = '' \nOutput: Missing consumer.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);
    }

    public function test_for_empty_consumer() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array());
        $vali = new LeadProcessValidation();
        $result = $vali->validateConsumer($data);
        if($result==0){            
            $massage = "*********************************************************************\nDescription: No consumer paramiter present in this array \nInput: Consumer = '' \nOutput: Missing consumer.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$result);
    }

    public function test_for_payloadvalidation() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"","phone"=>"","title"=>"","firstName"=>"","lastName"=>"","birthdate"=>"","age"=>"","ageRange"=>"","gender"=>"","address1"=>"","address2"=>"","city"=>"","state"=>"","postcode"=>"","countryCode"=>"","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->apiData($data['consumer']);
        if(!empty($result['lead_disposition']['error'])){
            $valid = 0;
        } else {
           $valid = 1; 
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription:Blank consumer paramiter present in this array \nInput: Consumer = '' \nOutput: error [".implode(', ',$result['lead_disposition']['error'])."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_payloadvalidation_particulardata() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@ss.com","phone"=>"","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5","supplier_id"=>"4"));
        $vali = new LeadProcessTable();
        $result = $vali->apiData($data['consumer']);
        if(!empty($result['lead_disposition']['error'])){
            $valid = 0;
            $errors = implode(', ',$result['lead_disposition']['error']);
        } else {
           $valid = 1; 
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription:Particular blank consumer paramiter present in this array \nInput: Consumer phone = '' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }


    public function test_for_consumer_emailpreprocessing() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->emailAddressPreProcessing($data['consumer']['email']);
        if($result=='0'){
            $errors = 'Invalid Email Address';
            $valid = 0;
        } else if($result=='2'){
            $errors = 'Required email id';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription:Blank consumer email id present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_emailpreprocessing2() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->emailAddressPreProcessing($data['consumer']['email']);
        if($result=='0'){
            $errors = 'Invalid Email Address';
            $valid = 0;
        } else if($result=='2'){
            $errors = 'Required email id';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription:Invalid consumer email id present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_emailpreprocessing3() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@gmail.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->emailAddressPreProcessing($data['consumer']['email']);
        if($result=='0'){
            $valid = 1;
        } else if($result=='2'){
            $valid = 1;
        } else {
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription:Valid consumer email id present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: Valid Email Addresss.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

   public function test_for_consumer_phonepreprocessing() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@gmail.com","phone"=>"","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneNumberPreProcessing($data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result['status']=='0'){
            $errors = 'Invalid Phone Number';
            $valid = 0;
        } else if($result['status']=='2') {
            $errors = 'Invalid Phone Number and country code';
            $valid = 0;
        } else if($result['status']=='3') {
            $errors = 'Required phone number';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Blank consumer phone present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_phonepreprocessing2() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@gmail.com","phone"=>"006189523640289","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneNumberPreProcessing($data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result['status']=='0'){
            $errors = 'Invalid Phone Number';
            $valid = 0;
        } else if($result['status']=='2') {
            $errors = 'Invalid Phone Number and country code';
            $valid = 0;
        } else if($result['status']=='3') {
            $errors = 'Required phone number';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Invalid consumer phone present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_phonepreprocessing3() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@gmail.com","phone"=>"006189523640289","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"IN"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneNumberPreProcessing($data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result['status']=='0'){
            $errors = 'Invalid Phone Number';
            $valid = 0;
        } else if($result['status']=='2') {
            $errors = 'Invalid Phone Number and country code';
            $valid = 0;
        } else if($result['status']=='3') {
            $errors = 'Required phone number';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Invalid consumer phone & country code countryCode  present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: error [".$errors."].\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_phonepreprocessing4() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ss@gmail.com","phone"=>"0061895236402","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneNumberPreProcessing($data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result['status']=='0'){
            $valid = 1;
        } else if($result['status']=='2') {
            $valid = 1;
        } else if($result['status']=='3') {
            $valid = 1;
        } else {
            $valid = 0;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Valid consumer phone present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: Valid Phone Number (".$result['phone'].").\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_duplicationchecking_lead_history() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ishita001@gmail.com","phone"=>"0364803315","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->duplicationChecking($data['consumer']['email'],$data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result=='0'){
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Duplicate checking phone & email present in this array \nInput: Consumer email & phone = '".$data['consumer']['email']." , ".$data['consumer']['phone']."' \nOutput: Duplicate Consume (present in lead history table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

   public function test_for_consumer_duplicationchecking_dncl() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ssdas@ss.com","phone"=>"0123456789","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->duplicationChecking($data['consumer']['email'],$data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result=='0'){
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Duplicate checking phone present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: Duplicate Consume (present in do not call table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_duplicationchecking_ces() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"abc@abc.com","phone"=>"0123456798","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->duplicationChecking($data['consumer']['email'],$data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result=='0'){
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Duplicate checking email present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: Duplicate Consume (present in client email suppression table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_duplicationchecking_cps() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ssdasp@ss.com","phone"=>"0123456798","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->duplicationChecking($data['consumer']['email'],$data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result=='0'){
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: Duplicate checking phone present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: Duplicate Consume (present in client phone suppression table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }



    public function test_for_consumer_duplicationchecking_dbl() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"ssdasp@ss.com","phone"=>"08002557976","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->emailAddressPreProcessing($data['consumer']['email']);
        if($result=='0'){
            $errors = 'Invalid Email Address';
            $valid = 0;
        } else if($result=='2'){
            $errors = 'Required email id';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: invalid consumer email present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: error = ".$errors." (present in domain blacklist table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_duplicationchecking_ebl() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"sss@ss.com","phone"=>"08002557976","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->emailAddressPreProcessing($data['consumer']['email']);
        if($result=='0'){
            $errors = 'Invalid Email Address';
            $valid = 0;
        } else if($result=='2'){
            $errors = 'Required email id';
            $valid = 0;
        } else {
            $valid = 1;
        }
        if($valid==0){
            $massage = "*********************************************************************\nDescription: invalid consumer email present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: error = ".$errors." (present in email blacklist table).\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_duplicationchecking_valid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->duplicationChecking($data['consumer']['email'],$data['consumer']['phone'],$data['consumer']['countryCode']);
        if($result=='0'){
            $valid = 0;
        } else {
            $valid = 1;
        } 
        if($valid==1){
            $massage = "\n*********************************************************************\nDescription: Valid consumer email present in this array \nInput: Consumer email = '".$data['consumer']['email']." , ".$data['consumer']['phone']."' \nOutput: No duplicate Consumer.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$valid);
    }

    public function test_for_consumer_NameTitleGenderPreprocess_valid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->nameTitle($data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['title']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $result = $vali->genderValidation($data['consumer']['title'],$data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['countryCode'],$data['consumer']['gender']);
            if(empty($result)){
                $valid = 0;
            } else {
                $valid = 1;
            }
        } 
        if($valid==1){
            $massage = "\n*********************************************************************\nDescription: Valid consumer name present in this array \nInput: Consumer email = '".$data['consumer']['firstName']." , ".$data['consumer']['lastName']."' \nOutput: valid name .\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$valid);
    }

    public function test_for_consumer_NameTitleGenderPreprocess_Invalidvalid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"sus#","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->nameTitle($data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['title']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $result = $vali->genderValidation($data['consumer']['title'],$data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['countryCode'],$data['consumer']['gender']);
            if(empty($result)){
                $valid = 0;
            } else {
                $valid = 1;
            }
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: Valid consumer email present in this array \nInput: Consumer email = '".$data['consumer']['email']." , ".$data['consumer']['phone']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_NameTitleGenderPreprocess_salaciousWord() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"fuck","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU"));
        $vali = new LeadProcessTable();
        $result = $vali->nameTitle($data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['title']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $result = $vali->genderValidation($data['consumer']['title'],$data['consumer']['firstName'],$data['consumer']['lastName'],$data['consumer']['countryCode'],$data['consumer']['gender']);
            if(empty($result)){
                $valid = 0;
            } else {
                $valid = 1;
            }
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: salaciousWord consumer firstName present in this array \nInput: Consumer firstName = '".$data['consumer']['firstName']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_bithDateAgeRange_invalidBirthday() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-1988","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessValidation();
        $result = $vali->birthdateAgeAgeRange($data['consumer']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: invalid consumer bithDate Age ageRange present in this array \nInput: Consumer birthdate = '".$data['consumer']['birthdate']." , ".$data['consumer']['age']." ageRange= ".$data['consumer']['ageRange']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_bithDateAgeRange_invalidage() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"15","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessValidation();
        $result = $vali->birthdateAgeAgeRange($data['consumer']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: invalid consumer bithDate Age ageRange present in this array \nInput: Consumer birthdate = '".$data['consumer']['birthdate']." , ".$data['consumer']['age']." ageRange= ".$data['consumer']['ageRange']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_bithDateAgeRange_invalidageRange() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"25-35","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessValidation();
        $result = $vali->birthdateAgeAgeRange($data['consumer']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: invalid consumer bithDate Age ageRange present in this array \nInput: Consumer birthdate = '".$data['consumer']['birthdate']." , ".$data['consumer']['age']." ageRange= ".$data['consumer']['ageRange']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_bithDateAgeRange_valid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessValidation();
        $result = $vali->birthdateAgeAgeRange($data['consumer']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==1){
            $massage = "\n*********************************************************************\nDescription: Valid consumer bithDate Age ageRange present in this array \nInput: Consumer birthdate = '".$data['consumer']['birthdate']." , ".$data['consumer']['age']." ageRange= ".$data['consumer']['ageRange']."' \nOutput: valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$valid);
    }

    public function test_for_consumer_thirdparty_invalid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@aa.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->emailValidationThirdParty($data['consumer']['email']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: InValid consumer email thirdparty present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_thirdparty_emailHstory() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"08002557977","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->emailValidationThirdParty($data['consumer']['email']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: InValid consumer email emailhistory present in this array \nInput: Consumer email = '".$data['consumer']['email']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_thirdparty_rapport_phone_history() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"0123456879","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneValidation($data['consumer']['phone'],$data['consumer']['countryCode'],$data['consumer']['phone']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: InValid consumer phore 'rapport phone history' present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_thirdparty_phone_history() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"0123456897","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneValidation($data['consumer']['phone'],$data['consumer']['countryCode'],$data['consumer']['phone']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: InValid consumer phore 'phone history' present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_thirdparty_phone_valid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"0764802315","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneValidation($data['consumer']['phone'],$data['consumer']['countryCode'],$data['consumer']['phone']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==1){
            $massage = "\n*********************************************************************\nDescription: Valid consumer phore present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: valid.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$valid);
    }

    public function test_for_consumer_phoneDncr_invalid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"07894561230","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneValidationDncr($data['consumer']['phone']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==0){
            $massage = "\n*********************************************************************\nDescription: InValid consumer phore Dncr present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: ".$error.".\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(0,$valid);
    }

    public function test_for_consumer_phoneDncr_valid() {
        $data = array("Supplier"=>array("ID"=>"a6b7ac9aeb165b1b4345b5ce88a04492","source"=>"Wintastic"),"campaign"=>array("ID"=>"2f9065307e8b94e2cd7c1b602de0ccb2"),"consumer"=>array("email"=>"susantakumardas@pp.com","phone"=>"0412080681","title"=>"MR","firstName"=>"Fred","lastName"=>"Farkus","birthdate"=>"14-02-2004","age"=>"10","ageRange"=>"10-14","gender"=>"Male","address1"=>"balaji guest house","address2"=>"100 Avenue madhapur","city"=>"100Ft Road","state"=>"kolkata","postcode"=>"721144","countryCode"=>"AU","campaign_id"=>"12","client_id"=>"5"));
        $vali = new LeadProcessTable();
        $result = $vali->phoneValidationDncr($data['consumer']['phone']);
        $error='';
        if($result['status']=='0'){
            $valid = 0;
            $error = $result['error'];
        } else {
            $valid = 1;
        } 
        if($valid==1){
            $massage = "\n*********************************************************************\nDescription: Valid consumer phore Dncr present in this array \nInput: Consumer phone = '".$data['consumer']['phone']."' \nOutput: Valid DNCR.\nResult: PASS\n*********************************************************************\n\n";
            print_r($massage);
        }
        $this->assertEquals(1,$valid);        
    }

    public function test_for_consumer_Done() {
        echo "\nDone\n\n";
    }
}