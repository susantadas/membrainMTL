<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GhcRequest;
use Illuminate\Support\Facades\Response;
use DB;
use Carbon\Carbon;
class LeadProcessTable extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'fraud_detection';
    public static function validator($input){
        $rules = array(
            'email_address'=>'required|string|email|max:255|unique:clients',
            'phone'=>'required|string|min:10',
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'postcode'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }

    //public static $salaciousWord = DB::table('salacious_word')->get(['pattern']);


    protected static $salaciousWord;
    protected static function salaciousWord() {
        return static::$salaciousWord = DB::table('salacious_word')->get(['pattern']);;
    }

    public function apiData($data){
        $campaignsDetails = DB::table('campaigns')->where('id','=',$data['campaign_id'])->get(['dncr_required','method','server_parameters','parameter_mapping','parameter_required']);
        $parameterRequired = json_decode($campaignsDetails[0]->parameter_required);
        $errors = array();
        $PayloadValidation = $this->payloadValidation($data);
        $_emailValidation = 1;
        $_pnpps = 1; 
        if($PayloadValidation['cCode']!=''){
            $data['countryCode'] = $PayloadValidation['cCode'];
        }
        if($PayloadValidation['status']!='0'){
            if(isset($parameterRequired->csv->email) && $parameterRequired->csv->email=='1'){
                $emailValidation = $this->emailAddressPreProcessing($data['email']);
                if($emailValidation=='0'){ 
                    $_emailValidation = 0;
                    $errors[] = 'Invalid Email Address';
                } else if($emailValidation=='2'){
                    $_emailValidation = 0; 
                    $errors[] = 'Required email id';
                } else {
                    $emailValidationThirdParty = $this->emailValidationThirdParty($data['email']);
                    if($emailValidationThirdParty['status']=='0'){
                        $errors[] = $emailValidationThirdParty['error'];
                    }
                }
            } elseif (isset($parameterRequired->api->email) && $parameterRequired->api->email=='1') {
                $emailValidation = $this->emailAddressPreProcessing($data['email']);
                if($emailValidation=='0'){ 
                    $_emailValidation = 0;
                    $errors[] = 'Invalid Email Address';
                } else if($emailValidation=='2'){
                    $_emailValidation = 0; 
                    $errors[] = 'Required email id';
                } else {
                    $emailValidationThirdParty = $this->emailValidationThirdParty($data['email']);
                    if($emailValidationThirdParty['status']=='0'){
                        $errors[] = $emailValidationThirdParty['error'];
                    }
                }
            } else{
            }

            if(isset($parameterRequired->csv->phone) && $parameterRequired->csv->phone=='1'){
                $pnpps = $this->phoneNumberPreProcessing($data['phone'],$data['countryCode']);                 
                if($pnpps['status']=='0'){
                    $_pnpps = 0;
                    $errors[] = 'Invalid Phone Number';
                } else if($pnpps['status']=='2') {
                    $_pnpps  = 0;
                    $errors[] = 'Invalid Phone Number and country code';
                } else if($pnpps['status']=='3') {
                    $_pnpps = 0;
                    $errors[] = 'Required phone number';
                } else {
                    $rapportAPICall = $this->rapportAPICall($pnpps['phone'],$data['countryCode'],$data['phone']);
                    $phoneValidation = $this->phoneValidation($pnpps['phone'],$data['countryCode'],$data['phone']);
                    if($phoneValidation['status']=='0'){
                        //$_pnpps = 0;
                        $errors[] = $phoneValidation['error'];
                    } else {
                        $data['phone'] = $pnpps['phone'];
                    }
                }
            } elseif (isset($parameterRequired->api->phone) && $parameterRequired->api->phone=='1') {
                $pnpps = $this->phoneNumberPreProcessing($data['phone'],$data['countryCode']);                 
                if($pnpps['status']=='0'){
                    $_pnpps = 0;
                    $errors[] = 'Invalid Phone Number';
                } else if($pnpps['status']=='2') {
                    $_pnpps  = 0;
                    $errors[] = 'Invalid Phone Number and country code';
                } else if($pnpps['status']=='3') {
                    $_pnpps = 0;
                    $errors[] = 'Required phone number';
                } else {
                    $rapportAPICall = $this->rapportAPICall($pnpps['phone'],$data['countryCode'],$data['phone']);
                    $phoneValidation = $this->phoneValidation($pnpps['phone'],$data['countryCode'],$data['phone']);
                    if($phoneValidation['status']=='0'){
                        //$_pnpps = 0;
                        $errors[] = $phoneValidation['error'];
                    } else {
                        $data['phone'] = $pnpps['phone'];
                    }
                }
            } else {
            }

            $duplicationChecking = $this->duplicationChecking($data['email'],$data['phone'],$data['countryCode']);
            $_duplicationChecking= 1;
            if($duplicationChecking=='0'){
                $_duplicationChecking= 0;
                $errors[] = 'Duplicate Consumer';
            }

            if($_emailValidation!='0' && $_pnpps!='0' && $_duplicationChecking!='0'){
                $nameTitle = $this->nameTitle($data['firstName'],$data['lastName'],$data['title']);                
                if($nameTitle['status']=='0'){
                   $errors[] = $nameTitle['error'];
                } else {                    
                    if(!isset($data['gender']) || $data['gender']==''){
                        $data['gender']='';
                    }
                    $genderValidation = $this->genderValidation($data['title'],$data['firstName'],$data['lastName'],$data['countryCode'],$data['gender']);
                    if(!empty($genderValidation)){
                        $data['gender'] = $genderValidation['gender'];
                        $data['firstName'] = $genderValidation['firstName'];
                        $data['lastName'] = $genderValidation['lastName'];
                        $data['countryCode'] = $genderValidation['countryCode'];
                    }
                }

                $addressFormatting = $this->addressFormatting($data);
                if($addressFormatting=='1'){
                    $errors[] = 'Invalid Invalid ZIP/Post code';
                } else if ($addressFormatting=='2') {
                    $errors[] = 'Consumer does not qualify';
                } else {
                    $data['postcode'] = $addressFormatting['postcode'];
                    $data['city'] = $addressFormatting['city'];
                    $data['address1'] = $addressFormatting['address1'];
                    $data['address2'] = $addressFormatting['address2'];
                }
                if(isset($parameterRequired->csv->birthdate) && $parameterRequired->csv->birthdate=='1' && isset($parameterRequired->csv->age) && $parameterRequired->csv->age=='1' && isset($parameterRequired->csv->ageRange) && $parameterRequired->csv->ageRange=='1'){
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $_errors = $errors;
                        unset($errors);
                        $errors = array_merge($_errors,$birthdateAgeAgeRange['error']);
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } elseif(isset($parameterRequired->api->birthdate) && $parameterRequired->api->birthdate=='1' && isset($parameterRequired->api->age) && $parameterRequired->api->age=='1' && isset($parameterRequired->api->ageRange) && $parameterRequired->api->ageRange=='1') {
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $_errors = $errors;
                        unset($errors);
                        $errors = array_merge($_errors,$birthdateAgeAgeRange['error']);
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } else {
                }

                $phoneAddress = $this->phoneAddress($data);
                if($campaignsDetails[0]->dncr_required=='0'){
                    if(isset($parameterRequired->csv->phone) && $parameterRequired->csv->phone=='1' && $data['countryCode']=='AU'){
                        $phoneValidationDncr = $this->phoneValidationDncr($data['phone']);
                        if($phoneValidationDncr['status']=='0'){
                            $errors[] = $phoneValidationDncr['error'];
                        }
                    } elseif (isset($parameterRequired->api->phone) && $parameterRequired->api->phone=='1' && $data['countryCode']=='AU') {
                        $phoneValidationDncr = $this->phoneValidationDncr($data['phone']);
                        if($phoneValidationDncr['status']=='0'){
                            $errors[] = $phoneValidationDncr['error'];
                        }
                    } else {
                    } 
                }
            }

            if($campaignsDetails[0]->method=='API' && empty($errors)){
                $serverParameters = json_decode($campaignsDetails[0]->server_parameters);
                $parameterMapping = json_decode($campaignsDetails[0]->parameter_mapping);
                $clientDelivery = $this->clientDelivery($serverParameters,$parameterMapping,$data);
                if($clientDelivery['success']=='true'){
                    if($clientDelivery['errors']!=''){
                        $errors[] = $clientDelivery['errors'];
                    }
                } else {
                    $errors[] = $clientDelivery['errors'];
                }
            }

            if(!empty($errors)){
                $LAdata = array('supplier_id' => $data['supplier_id'],'source' => $data['supplier_id'],'client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' => json_encode($data),'received' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')),'errors' => 'True');
                if(in_array('Duplicate Consumer', $errors)) {
                    $data['lead_disposition']['error'] = 'Duplicate Consumer';
                    $data['lead_disposition']['success'] = 'false'; 
                    $LAdata['disposition'] = 'Duplicate';
                    DB::table('lead_audit')->insert($LAdata);
                } else {
                    $data['lead_disposition']['error'] = $errors;
                    $data['lead_disposition']['success'] = 'false';
                    $LAdata['disposition'] = 'Rejected';
                    DB::table('lead_audit')->insert($LAdata);
                }
                $data['audi_flag'] = 'True';
            } else {
                $LAdata = array('supplier_id' => $data['supplier_id'],'source' => $data['supplier_id'],'client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' => json_encode($data),'received' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')),'errors' => 'False','disposition' => 'Accepted');
                DB::table('lead_audit')->insert($LAdata);
                //$_JLHData= str_replace('&quot;','',json_encode(array('email'=>$data['email'],'phone'=>$data['phone'])));
                $LHdataEmail = array('client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' =>$data['email'],'client_duplicate' => 0,'delivered' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')));
                $LHdataPhone = array('client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' =>$data['phone'],'client_duplicate' => 0,'delivered' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')));

                $exitCpId = DB::table('lead_history')->where('data','=',$data['email'])->orWhere('data','=',$data['phone'])->get(['id']);
                if(empty($exitCpId[0])) {
                    DB::table('lead_history')->insert($LHdataEmail);
                    DB::table('lead_history')->insert($LHdataPhone);
                }
                $data['lead_disposition']['error'] = array();
                $data['lead_disposition']['success'] = 'true';  
                $data['audi_flag'] = 'False';
            }
        } else {
            $data['lead_disposition']['error'] = $PayloadValidation['error'];
            $data['lead_disposition']['success'] = 'false';
            $data['audi_flag'] = 'False';
        }
        return $data;
    }
    /* Start Phone & Email Basic Validation & De-Duping 1.) Email Address Pre-Processing */
    public function payloadValidation($data){
        $error = array();
        $status = 1;
        $_campaignsDetails = DB::table('campaigns')->where('id','=',$data['campaign_id'])->get(['parameter_required']);        
        if(!empty($_campaignsDetails[0])){
           $_cmpRequired = json_decode($_campaignsDetails[0]->parameter_required);
        } else {
            $_cmpRequired = array();
        }
        
        if(!empty($_cmpRequired)){
            if(isset($_cmpRequired->api)){
                if((!isset($data['email']) || $data['email']=='') && $_cmpRequired->api->email=='1'){
                    $status = 0;
                    $error[] = 'Required email id';
                }

                if((!isset($data['phone']) || $data['phone']=='') && $_cmpRequired->api->phone=='1'){
                    $status = 0;
                    $error[] = 'Required phone number';
                }

                if((!isset($data['title']) || $data['title']=='') && $_cmpRequired->api->title=='1'){
                    $status = 0;
                    $error[] = 'Required title like Mr.,Mrs.,Ms.';
                }

                if((!isset($data['firstName']) || $data['firstName']=='') && $_cmpRequired->api->firstName=='1'){
                    $status = 0;
                    $error[] = 'Required first name';
                }

                if((!isset($data['lastName']) || $data['lastName']=='') && $_cmpRequired->api->lastName=='1'){
                    $status = 0;
                    $error[] = 'Required last name';
                }

                if((!isset($data['birthdate']) || $data['birthdate']=='') && $_cmpRequired->api->birthdate=='1'){
                    $status = 0;
                    $error[] = 'Required birth date';
                }

                if((!isset($data['age']) || $data['age']=='') && $_cmpRequired->api->age=='1'){
                    $status = 0;
                    $error[] = 'Required age';
                }

                if((!isset($data['ageRange']) || $data['ageRange']=='') && $_cmpRequired->api->ageRange=='1'){
                    $status = 0;
                    $error[] = 'Required age range';
                }

                if((!isset($data['gender']) || $data['gender']=='')  && $_cmpRequired->api->gender=='1'){
                    $status = 0;
                    $error[] = 'Required gender';
                }

                if((!isset($data['address1']) || $data['address1']=='') && $_cmpRequired->api->address1=='1'){
                    $status = 0;
                    $error[] = 'Required address1';
                }

                if((!isset($data['city']) || $data['city']=='') && $_cmpRequired->api->city=='1'){
                    $status = 0;
                    $error[] = 'Required city';
                }

                if((!isset($data['state']) || $data['state']=='') && $_cmpRequired->api->state=='1'){
                    $status = 0;
                    $error[] = 'Required state';
                }

                if((!isset($data['postcode']) || $data['postcode']=='') && $_cmpRequired->api->postcode=='1'){
                    $status = 0;
                    $error[] = 'Required postcode.';
                }

                if((!isset($data['countryCode']) || $data['countryCode']=='') && $_cmpRequired->api->countryCode=='1'){
                    $status = 0;
                    $error[] = 'Required country code..';
                    $cCode = '';
                } else {
                    if(strlen($data['countryCode'])==2){                        
                        $country = DB::table('countries')->where('code',$data['countryCode'])->get(['id']);
                        if(empty($country[0])){
                            $status = 0;
                            $error[] = 'Country code invalid';
                        }
                        $cCode = '';
                    } else {
                        $country = DB::table('countries')->where('name',$data['countryCode'])->get(['id','code']);
                        if(empty($country[0])){
                            $status = 0;
                            $error[] = 'Country code invalid';
                            $cCode = '';
                        } else {
                            $cCode = $country[0]->code;
                        }
                    }
                }
            } else {
                if((!isset($data['email']) || $data['email']=='') && $_cmpRequired->csv->email=='1'){
                    $status = 0;
                    $error[] = 'Required email id';
                }

                if((!isset($data['phone']) || $data['phone']=='') && $_cmpRequired->csv->phone=='1'){
                    $status = 0;
                    $error[] = 'Required phone number';
                }

                if((!isset($data['title']) || $data['title']=='') && $_cmpRequired->csv->title=='1'){
                    $status = 0;
                    $error[] = 'Required title like Mr.,Mrs.,Ms.';
                }

                if((!isset($data['firstName']) || $data['firstName']=='') && $_cmpRequired->csv->firstName=='1'){
                    $status = 0;
                    $error[] = 'Required first name';
                }

                if((!isset($data['lastName']) || $data['lastName']=='') && $_cmpRequired->csv->lastName=='1'){
                    $status = 0;
                    $error[] = 'Required last name';
                }

                if((!isset($data['birthdate']) || $data['birthdate']=='') && $_cmpRequired->csv->birthdate=='1'){
                    $status = 0;
                    $error[] = 'Required birth date';
                }

                if((!isset($data['age']) || $data['age']=='') && $_cmpRequired->csv->age=='1'){
                    $status = 0;
                    $error[] = 'Required age';
                }

                if((!isset($data['ageRange']) || $data['ageRange']=='') && $_cmpRequired->csv->ageRange=='1'){
                    $status = 0;
                    $error[] = 'Required age range';
                }

                if((!isset($data['gender']) || $data['gender']=='') && $_cmpRequired->csv->gender=='1'){
                    $status = 0;
                    $error[] = 'Required gender';
                }

                if((!isset($data['address1']) || $data['address1']=='') && $_cmpRequired->csv->address1=='1'){
                    $status = 0;
                    $error[] = 'Required address1';
                }

                if((!isset($data['city']) || $data['city']=='') && $_cmpRequired->csv->city=='1'){
                    $status = 0;
                    $error[] = 'Required city';
                }

                if((!isset($data['state']) || $data['state']=='') && $_cmpRequired->csv->state=='1'){
                    $status = 0;
                    $error[] = 'Required state';
                }

                if((!isset($data['postcode']) || $data['postcode']=='') && $_cmpRequired->csv->postcode=='1'){
                    $status = 0;
                    $error[] = 'Required postcode.';
                }

                if((!isset($data['countryCode']) || $data['countryCode']=='') && $_cmpRequired->csv->countryCode=='1'){
                    $status = 0;
                    $error[] = 'Required country code..';
                    $cCode = '';
                } else {
                    if(strlen($data['countryCode'])==2){                        
                        $country = DB::table('countries')->where('code',$data['countryCode'])->get(['id']);
                        if(empty($country[0])){
                            $status = 0;
                            $error[] = 'Country code invalid';
                        }
                        $cCode = '';
                    } else {
                        $country = DB::table('countries')->where('name',$data['countryCode'])->get(['id','code']);
                        if(empty($country[0])){
                            $status = 0;
                            $error[] = 'Country code invalid';
                            $cCode = '';
                        } else {
                            $cCode = $country[0]->code;
                        }
                    }
                }
            }
        } else {
            if(!isset($data['email']) || $data['email']==''){
                $status = 0;
                $error[] = 'Required email id';
            }

            if(!isset($data['phone']) || $data['phone']==''){
                $status = 0;
                $error[] = 'Required phone number';
            }

            if(!isset($data['title']) || $data['title']==''){
                $status = 0;
                $error[] = 'Required title like Mr.,Mrs.,Ms.';
            }

            if(!isset($data['firstName']) || $data['firstName']==''){
                $status = 0;
                $error[] = 'Required first name';
            }

            if(!isset($data['lastName']) || $data['lastName']==''){
                $status = 0;
                $error[] = 'Required last name';
            }

            if(!isset($data['birthdate']) || $data['birthdate']==''){
                $status = 0;
                $error[] = 'Required birth date';
            }

            if(!isset($data['age']) || $data['age']==''){
                $status = 0;
                $error[] = 'Required age';
            }

            if(!isset($data['ageRange']) || $data['ageRange']==''){
                $status = 0;
                $error[] = 'Required age range';
            }

            /*if(!isset($data['gender']) || $data['gender']==''){
                $status = 0;
                $error[] = 'Required gender';
            }*/

            if(!isset($data['address1']) || $data['address1']==''){
                $status = 0;
                $error[] = 'Required address1';
            }

            if(!isset($data['city']) || $data['city']==''){
                $status = 0;
                $error[] = 'Required city';
            }

            if(!isset($data['state']) || $data['state']==''){
                $status = 0;
                $error[] = 'Required state';
            }

            if(!isset($data['postcode']) || $data['postcode']==''){
                $status = 0;
                $error[] = 'Required postcode.';
            }

            if(!isset($data['countryCode']) || $data['countryCode']==''){
                $status = 0;
                $error[] = 'Required country code..';
                $cCode = '';
            } else {
                if(strlen($data['countryCode'])==2){
                    $country = DB::table('countries')->where('code',$data['countryCode'])->get(['id']);
                    if(empty($country[0])){
                        $status = 0;
                        $error[] = 'Country code invalid';
                    }
                    $cCode = '';
                } else {
                    $country = DB::table('countries')->where('name',$data['countryCode'])->get(['id','code']);
                    if(empty($country[0])){
                        $status = 0;
                        $error[] = 'Country code invalid';
                        $cCode = '';
                    } else {
                        $cCode = $country[0]->code;
                    }
                }
            }
        }

        return array('status'=>$status,'error'=>$error,'cCode'=>$cCode);
    }
    /* End Payload Validation */
    /* Start Phone & Email Basic Validation & De-Duping 1.) Email Address Pre-Processing */
    public function emailAddressPreProcessing($email){
        $regex = '/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/';
        if($email !=''){
            $_email = strtolower($email); //Standardised
            $_domainName1 = explode('@',$_email);
            $_domainName = end($_domainName1);
            $domainBlacklist = DB::table('domain_blacklist')->where('domain',$_domainName)->get(['id']); //Not on Domain Blacklist       
            $emailBlacklist = DB::table('email_blacklist')->where('email_address',$_email)->get(['id']); //Not on Email Blacklist
            $emailValid1 = (preg_match($regex, $_email)) ? 1 : 0; //Syntax Checked
            $emailValid2 = ($domainBlacklist->count()<='0') ? 1 : 0;
            $emailValid3 = ($emailBlacklist->count()<='0') ? 1 : 0;
            $emailValid = ($emailValid1 != 0 ? ($emailValid2 != 0 ? ($emailValid3 != 0 ? 1 : 0) : 0) : 0);
        } else {
            $emailValid = 2;
        }
        return $emailValid;
    }
    /* End Phone & Email Basic Validation & De-Duping 1.) Email Address Pre-Processing */
    /* Start Phone & Email Basic Validation & De-Duping 2.) Phone Number Pre-Processing */
    public function phoneNumberPreProcessing($phone,$country){
        $_phone = preg_replace('/[^0-9+]/', '', $phone);
        $carray = array('AU','NZ','US','CA','UK','GB');
        $valid = 0;
        if($_phone!=''){
            $bforelength = strlen($_phone);
            if(in_array($country, $carray)){
                if(strrpos($_phone,'+64',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,3);
                } else if(strrpos($_phone,'+61',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,3);
                } else if(strrpos($_phone,'+1',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'1',0,2);
                } else if(strrpos($_phone,'+44',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,3);
                } else if(strrpos($_phone,'0064',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,4);
                } else if(strrpos($_phone,'0061',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,4);
                } else if(strrpos($_phone,'0111',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'1',0,4);
                } else if(strrpos($_phone,'0044',-$bforelength)!== false){
                    $phoneN = substr_replace($_phone,'0',0,4);
                } else if(strrpos($_phone,'0',-$bforelength) === false && ($country=='NZ' || $country=='AU' || $country=='UK' || $country=='GB')){
                    $phoneN = substr_replace($_phone,'0',0,0);
                } else if(strrpos($_phone,'1',-$bforelength) === false && ($country=='CA' || $country=='US')){
                    $phoneN = substr_replace($_phone,'1',0,0);
                } else {
                    $phoneN=$_phone;
                }

                $afterlength = strlen($phoneN);
                if($country=='AU' && $afterlength=='10'){
                    $valid = 1;
                } else if($country=='NZ' && ($afterlength >= '9' && $afterlength <= '11')) {
                    if((strrpos($phoneN,'03',-$afterlength)!== false || strrpos($phoneN,'04',-$afterlength)!== false || strrpos($phoneN,'06',-$afterlength)!== false || strrpos($phoneN,'07',-$afterlength)!== false || strrpos($phoneN,'09',-$afterlength)!== false) && $afterlength == '9'){
                        $valid = 1;
                    } else if(strrpos($phoneN,'02',-$afterlength)!== false) {
                        $valid = 1;
                    } else if($afterlength >= '9' && $afterlength <= '11'){
                        $valid = 1;
                    } else {
                        $valid = 0;
                    }
                } else if($country=='UK' && ($afterlength >= '10' && $afterlength <= '11')) {
                    if(strrpos($phoneN,'07',-$afterlength)!== false && $afterlength == '10'){
                        $valid = 1;
                    } else if(strrpos($phoneN,'01',-$afterlength)!== false || strrpos($phoneN,'02',-$afterlength)!== false || strrpos($phoneN,'03',-$afterlength)!== false || strrpos($phoneN,'05',-$afterlength)!== false || strrpos($phoneN,'06',-$afterlength)!== false || strrpos($phoneN,'08',-$afterlength)!== false) {
                        $valid = 1;
                    } else if($afterlength >= '10' && $afterlength <= '11'){
                        $valid = 1;
                    } else {
                        $valid = 0;
                    }
                } else if($country=='GB' && ($afterlength >= '10' && $afterlength <= '11')) {
                    if(strrpos($phoneN,'07',-$afterlength)!== false && $afterlength == '10'){
                        $valid = 1;
                    } else if(strrpos($phoneN,'01',-$afterlength)!== false || strrpos($phoneN,'02',-$afterlength)!== false || strrpos($phoneN,'03',-$afterlength)!== false || strrpos($phoneN,'05',-$afterlength)!== false || strrpos($phoneN,'06',-$afterlength)!== false || strrpos($phoneN,'08',-$afterlength)!== false) {
                        $valid = 1;
                    } else if($afterlength >= '10' && $afterlength <= '11'){
                        $valid = 1;
                    } else {
                        $valid = 0;
                    }
                } else if(($country=='US' || $country=='CA') && $afterlength == '11') {
                    $valid = 1;
                } else {
                    $valid = 0;
                }
            } else {
                $phoneN = $_phone;
                $valid = 2;
            }
        } else {
            $phoneN = $_phone;
            $valid = 3;
        }
        return array('status'=>$valid,'phone'=>$phoneN);
    }
    /* End Phone & Email Basic Validation & De-Duping 2.) Phone Number Pre-Processing */
    /* Start Phone & Email Basic Validation & De-Duping 3.) Duplication Checking */
    public function duplicationChecking($email,$phone,$country){
        //$_JLHData= str_replace('&quot;','',json_encode(array('email'=>$email,'phone'=>$phone)));        
        //$_lhe = DB::table('lead_history')->where('data',$_JLHData)->get(['id']); //Membrain Lead Delivery Lists email
        $_lhe = DB::table('lead_history')->where('data','=',$email)->orWhere('data','=',$phone)->get(['id']); //Membrain Lead Delivery Lists email
        if(empty($_lhe[0])){
            $lhe = 1;
            $_donotcall = DB::table('membrain_global_do_not_call')->where('country_code','=',$country)->where('phone_number','=',$phone)->get(['id']); //Membrain Global Do Not Call List
            if(empty($_donotcall[0])){
                $_cps = DB::table('client_phone_suppression')->where('data',$phone)->get(['id']); //Client-Supplied Lists email suppression
                if(empty($_cps[0])){
                    $_ces = DB::table('client_email_suppression')->where('data',$email)->get(['id']); //Client-Supplied Lists email suppression
                    if(empty($_ces[0])){
                        $validdata = 1;
                    } else {
                        $validdata = 0; 
                    }
                } else {
                   $validdata = 0; 
                }
            } else{
                $validdata = 0;
            }
        } else {
            $validdata = 0;
        }
        return $validdata;
    }
    /* End Phone & Email Basic Validation & De-Duping 3.) Duplication Checking */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 1.) Name / Title / Gender Processing */
    public function genderValidation($title,$firstName,$lastName,$countryCode,$gender){
        if($gender=='' && $title!=''){
            if(strtolower($title)=='mr'){
               $_gender = 'Male'; 
            } else {
                $_gender = 'Female';
            }
            $_fname = $firstName;
            $_lname = $lastName;
            $_countryCode = $countryCode;
        } else if($gender == '') {
            $_firstName = preg_replace('/[^a-zA-Z ]/', '', $firstName);
            $_lastName = preg_replace('/[^a-zA-Z ]/', '', $lastName);
            if($countryCode=='UK'){
                $_countryCodes = 'GB';
            } else {
                $_countryCodes = $countryCode;
            }

            $url = "https://gender-api.com/get?key=BTvunEepSkbLZULazE&split=".$_firstName.' '.$_lastName."&country=".$_countryCodes;
            $client = new Client();
            $res = $client->get($url);
            $result = $res->getBody();
            $_result = json_decode($result);
            if($_result->gender=='unknown'){
                $_gender = $gender;            
            } else if($_result->gender=='') {
                $_gender = $gender;
            } else {
                $_gender = $_result->gender;
            }
            if(isset($_result->first_name) && $_result->first_name !=''){
                $_fname = $_result->first_name;
            } else {
                $_fname = $firstName;
            }


            if(isset($_result->last_name) && $_result->last_name !='' && $_result->last_name != $firstName){
                $_lname = $_result->last_name;
            } else {
                $_lname = $lastName;
            }
            $_countryCode = $countryCode;
        } else {
            $_gender = $gender;
            $_fname = $firstName;
            $_lname = $lastName;
            $_countryCode = $countryCode;
        }
        return array('firstName'=>ucwords($_fname),'lastName'=>ucwords($_lname),'gender'=>ucwords($_gender),'countryCode'=>ucwords($_countryCode));
    }

    public function nameTitle($firstName,$lastName,$title){
        $fname = $firstName;
        $lname = $lastName;
        $regx = "/^[a-zA-Z’ ]*$/";
        if(!preg_match($regx, $fname) || !preg_match($regx, $lname) || !preg_match($regx, $title)){            
            $name = array('status'=>'0','error'=>'Invalid characters in First Name / Last Name / title');
        } else {
            $name = preg_replace('/[a-zA-Z ]/', '', $fname).' '.preg_replace('/[a-zA-Z ]/', '', $lname);
            $_name = DB::table('name_blacklist')->where('name',$name)->get(['id']);
            if($_name->count()<='0'){
                $salaciousWords = static::salaciousWord();
                foreach($salaciousWords as $swk => $_salaciousWord){
                    $newSrtingSw = str_replace('/', '', $_salaciousWord->pattern);
                    $regxNew = "/$newSrtingSw/";
                    if(preg_match($regxNew,$fname)){
                        $name = array('status'=>'0','error'=>'Salacious word in First Name');
                        break;
                    } else {
                        $name = array('status'=>'1','error'=>'');  
                    }
                }                
            } else {
                $name = array('status'=>'0','error'=>'Invalid consumer name');
            }
        }
        return $name;
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 1.) Name / Title / Gender Processing */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 2.) Address */
    public function addressFormatting($data){
        $ZIPREG=array(
            "US"=>"^\d{5}([\-]?\d{4})?$",
            "UK"=>"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
            "GB"=>"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
            "CA"=>"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
            "AU"=>"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
            "NZ"=>"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
        );

        if($data['countryCode']=='UK' || $data['countryCode']=='CA' || $data['countryCode']=='GB'){
            $data['postcode'] = strtoupper(wordwrap($data['postcode'] , 3 , ' ' , true ));
        } else {
            $data['postcode'] = strtoupper($data['postcode']);
        }
        if(isset($ZIPREG[$data['countryCode']])){
            if (!preg_match("/".$ZIPREG[$data['countryCode']]."/i",$data['postcode'])){
                $postcode = 1;
            }
        }

        $_postcode = DB::table('postcode')->where('country_code','=',$data['countryCode'])->where('postcode','=',$data['postcode'])->get(['id']);        
        $postcode1 = (empty($_postcode)) ? 1 : 0;

        $_campaigns = DB::table('campaigns')->where('criteria_state','=',$data['countryCode'])->orWhere('criteria_postcode','=',$data['postcode'])->get(['id']);        
        $campaigns = (empty($_campaigns)) ? 1 : 0;

        $postcode = ($postcode1==1 && $postcode==1) ? 1 : 0;

        $data['city'] = strtoupper($data['city']);
        $data['address1'] = ucwords(str_replace(array('Avenue','Street'), array('Ave','St'), $data['address1']));
        $data['address2'] = ucwords(str_replace(array('Avenue','Street'), array('Ave','St'), $data['address2']));
        if($postcode=='1'){
            return 1; //'Invalid postcode';
        } else if($campaigns=='1'){
            return 2; //'Consumer does not qualify - Address'
        } else {
            return $data;
        }
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 2.) Address */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 3.) Birthdate / Age / Age Range */
    public function birthdateAgeAgeRange($data){
        $valid = 1;
        $regx = "/^[0-9]+$/";
        $error = array();
        $format = array('Y-m-d','m/d/Y','m/d/y','d M Y','d M y','M d y','M d Y','d-m-Y','d-m-y','y-m-d','d/m/Y','d/m/y','d-M-Y');
        $validBirthDate = $this->validateDateTime($data['birthdate'],$format);
        if($validBirthDate['status']=='1'){
            $Y1 = date('Y',strtotime($validBirthDate['date']));
            $Y2 = date('Y', strtotime('-100 year'));
            $Y3 = date('Y');
            $error = '';
            
            if($Y1 <= $Y3 && $Y1 >= $Y2){
                $birthdate = date('Y-m-d',strtotime($validBirthDate['date']));
            } else {
                $birthdate = $data['birthdate'];
                $valid = 0;
                $error[] = 'Invalid birthdate';
            }

            if(!preg_match($regx, $data['age'])){
                $valid = 0;
                $error[] = 'Invalid age';
            } else {
                if($data['age'] < 1 || $data['age'] > 100){
                    $valid = 0;
                    $error[] = 'Invalid age';
                }
            }

            $_ageRange = explode('-', $data['ageRange']);
            if(!preg_match($regx, $_ageRange[0]) && !preg_match($regx, $_ageRange[1])){
                $valid = 0;
                $error[] = 'Invalid age range';
            } else {
                if(($_ageRange[0] < 2 || $_ageRange[0] > 100) || ($_ageRange[1] < 2 || $_ageRange[1] > 100)){
                    $valid = 0;
                    $error[] = 'Invalid age range';
                }
            }

            if($valid=='1'){
                $_campaigns = DB::table('campaigns')->where('id',$data['campaign_id'])->get(['id','criteria_age']);
                if(!empty($_campaigns[0])){
                    if($_campaigns[0]->criteria_age!=''){
                        $criteriaAge = explode('-', $_campaigns[0]->criteria_age);
                        $dob=$birthdate;
                        $actualAge = (date('Y') - date('Y',strtotime($dob)));
                        if($actualAge >= $criteriaAge[0] && $actualAge <= $criteriaAge[1]){
                            if($data['age'] >= $criteriaAge[0] && $data['age'] <= $criteriaAge[1]){
                                if($criteriaAge[0] != $_ageRange[0] && $criteriaAge[1] != $_ageRange[1]){
                                    $valid = 0;
                                    $error[] = 'Consumer does not qualify agerange';
                                }
                            } else {
                                $valid = 0;
                                $error[] = 'Consumer does not qualify age';
                            }
                        } else {
                            $valid = 0;
                            $error[] = 'Consumer does not qualify birthdate';
                        }
                    } else {
                        $error[] = 'Consumer does not qualify(criteria age have blank)';
                    }
                }
            }
        } else {
            $birthdate = $data['birthdate'];
            $valid = 0;
            $error[] = 'Invalid birthdate';
        }        
        return array('status'=>$valid,'birthdate'=>$birthdate,'error'=>$error);
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 3.) Birthdate / Age / Age Range */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 4.) Phone / Address */
    public function phoneAddress($data){
       return 1;
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 4.) Phone / Address */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 5.) Email Address */
    public function rapportAPICall($phone,$countryCode,$orgphone){
       return 1;
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 5.) Email Address */
    /* Start Data Validation 1.) Email Validation – Third Party */
    public function emailValidationThirdParty($email){
        $emailHistory = DB::table('email_history')->where('email_address',$email)->where('is_valid','0')->get(['id']);
        if(empty($emailHistory[0])){
            $url = "https://bpi.briteverify.com/emails.json?address=".$email."&apikey=cf7d1153-f2af-4b86-9bdd-d5c6b6ad8078";
            $client = new Client();
            $res = $client->get($url);
            $result = $res->getBody();
            $_result = json_decode($result);
            if(isset($_result->error)){
                $exitCpId = DB::table('email_history')->where('email_address','=',$email)->get(['id']);
                if(empty($exitCpId[0])) {
                    $_emailHis = array('email_address'=>$email,'is_valid'=>'0','validation_date'=>date('Y-m-d'));
                    DB::table('email_history')->insert($_emailHis);
                } else {
                    DB::table('email_history')->where('id','=',$exitCpId[0]->id)->update(['is_valid' =>'1']);
                }
                $valid = 0;
                $error = $_result->error;
            } else {
                $exitCpId = DB::table('email_history')->where('email_address','=',$email)->get(['id']);
                if(empty($exitCpId[0])) {
                    $_emailHis = array('email_address'=>$email,'is_valid'=>'1','validation_date'=>date('Y-m-d'));
                    DB::table('email_history')->insert($_emailHis);
                } else {
                    DB::table('email_history')->where('id','=',$exitCpId[0]->id)->update(['is_valid' =>'0']);
                }
                $valid = 1;
                $error ='';
            }
        } else{
            $valid = 0;
            $error ='Invalid Email Address';
        }        
        return array('status'=>$valid,'error'=>$error);
    }
    /* End Data Validation 1.) Email Validation – Third Party */
    /* Start Data Validation 2.) Phone Validation */
    public function phoneValidation($phone,$countryCode,$orgPhone){
        $phoneRPH = DB::table('rapport_phone_history')->where('phone_number',$phone)->where('country_code',$countryCode)->where('status_code','invalid')->get(['id']);
        if(empty($phoneRPH[0])) {
            $phonePH = DB::table('phone_history')->where('phone_number',$phone)->where('country_code',$countryCode)->where('is_valid','0')->get(['id']);
            if(empty($phonePH[0])){                
                $url = "https://api.mobileverification.co.uk/?msisdn=".$orgPhone."&account=scott@membraindata.com&password=Bra1nWa5h3d!!!&type=HLR&output=json";
                $client = new Client();
                $res = $client->get($url);
                $result = $res->getBody();
                $_result = json_decode($result);
                if(isset($_result->HLRResult->Error) && isset($_result->HLRResult)){
                    $valid = 0;
                    $error = $_result->HLRResult->Error;
                    /* add/update phone history after getting responce */
                    $exitCpId = DB::table('phone_history')->where('phone_number',$phone)->where('country_code',$countryCode)->get(['id']);
                    if(empty($exitCpId[0])) {
                        $_phoneHis = array('country_code'=>$countryCode,'phone_number'=>$phone,'is_valid'=>'0','validation_date'=>date('Y-m-d'));
                        DB::table('phone_history')->insert($_phoneHis);
                    } else {
                        DB::table('phone_history')->where('id','=',$exitCpId[0]->id)->update(['is_valid' =>'0']);
                    }
                } else {
                    $valid = 1;
                    $error ='';
                    /* add/update phone history after getting responce */
                    $exitCpId = DB::table('phone_history')->where('phone_number',$phone)->where('country_code',$countryCode)->get(['id']);
                    if(empty($exitCpId[0])) {
                        $_phoneHis = array('country_code'=>$countryCode,'phone_number'=>$phone,'is_valid'=>'1','validation_date'=>date('Y-m-d'));
                        DB::table('phone_history')->insert($_phoneHis);
                    } else {
                        DB::table('phone_history')->where('id','=',$exitCpId[0]->id)->update(['is_valid' =>'1']);
                    }
                }
            } else {
                $valid = 0;
                $error = 'Invalid Phone Number';
            }
        } else { 
            $valid = 0;
            $error = 'Invalid Phone Number';
        }
        return array('status'=>$valid,'error'=>$error);
    }
    /* End Data Validation 2.) Phone Validation */
    /* Start Data Validation 3.) Phone DNCR */
    public function phoneValidationDncr($phone){
        if($phone!=''){
            $xml = '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rtw="http://rtw.dncrtelem" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/"><soapenv:Header/><soapenv:Body><rtw:WashNumbers soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><TelemarketerId xsi:type="xsd:string">36441</TelemarketerId><TelemarketerPassword xsi:type="xsd:string">Bra1nWa5h3d!!!</TelemarketerPassword><ClientReferenceId xsi:type="xsd:string">SL0001</ClientReferenceId><NumbersToWash xsi:type="rtw:ArrayOf_xsd_anyType" soapenc:arrayType="xsd:anyType[]"><Number xsi:type="xsd:string">'.$phone.'</Number></NumbersToWash></rtw:WashNumbers></soapenv:Body></soapenv:Envelope>';
            $uri = 'https://www.donotcall.gov.au/dncrtelem/rtw/washing.cfc';
            $client = new Client();
            $request = new GhcRequest ('POST',$uri,['Content-Type' => 'text/xml; charset=UTF8'],$xml);
            $response = $client->send($request);
            $_result = $this->XMLToArray($response->getBody());
            if(isset($_result['Envelope']['Body']['WashNumbersResponse']['WashNumbersReturn']['NumbersSubmitted']['Number']) && !empty($_result['Envelope']['Body']['WashNumbersResponse']['WashNumbersReturn']['NumbersSubmitted']['Number'])){
                $result = $_result['Envelope']['Body']['WashNumbersResponse']['WashNumbersReturn']['NumbersSubmitted']['Number'];
                if($result['attributes']['Result']=='Y' && $result['attributes']['ErrorCode']=='0'){
                    $returnData =  array('status'=>'1','error'=>'','phone'=>$result['value']);
                } else {
                    $returnData =  array('status'=>'0','error'=>'Phone Number fail DNCR','phone'=>$phone);
                }
            } else {
               $returnData =  array('status'=>'0','error'=>'Phone Number fail DNCR','phone'=>$phone); 
            }
        } else {
            $returnData =  array('status'=>'0','error'=>'Phone Number fail DNCR','phone'=>$phone); 
        }
        return $returnData;
    }
    /* End Data Validation 3.) Phone DNCR */
    /**/
    public function clientDelivery($serverParameters,$parameterMapping,$data){
        $newdata[$parameterMapping->email] = $data['email'];
        $newdata[$parameterMapping->phone] = $data['phone'];
        $newdata[$parameterMapping->title] = $data['title'];
        $newdata[$parameterMapping->firstName] = $data['firstName'];
        $newdata[$parameterMapping->lastName] = $data['lastName'];
        $newdata[$parameterMapping->birthdate] = $data['birthdate'];
        $newdata[$parameterMapping->age] = $data['age'];
        $newdata[$parameterMapping->ageRange] = $data['ageRange'];
        $newdata[$parameterMapping->gender] = $data['gender'];
        $newdata[$parameterMapping->address1] = $data['address1'];
        $newdata[$parameterMapping->address2] = $data['address2'];
        $newdata[$parameterMapping->city] = $data['city'];
        $newdata[$parameterMapping->state] = $data['state'];
        $newdata[$parameterMapping->postcode] = $data['postcode'];
        $newdata[$parameterMapping->countryCode] = $data['countryCode'];
        $newdata['type'] = $serverParameters->type;
        $newdata['endpoint'] = $serverParameters->server;;
        $newdata['user'] = $serverParameters->user;
        $newdata['password'] = $serverParameters->password;
        $newdata['port'] = '443';
        $newdata['segment'] = '1A';
        $newdata['created'] = date('d/m/Y');
        $url = $serverParameters->server;
        $client = new Client();
        $res = $client->post($url, ['form_params'=>$newdata,'timeout' => 10]);
        $result = $res->getBody();
        $_result = json_decode($result);
        return $_result;
    }
    /**/
    public function validateDateTime($dateStr, $format) {
        foreach ($format as $key => $value) {
        $newdate = ucwords($dateStr);
        $date = \DateTime::createFromFormat($value, $newdate);
            if($date && ($date->format($value) === $newdate)){
                $mydate = (array)$date;
                return array('status'=>1,'date'=>$mydate['date'],'format'=>$value);
            } else {
                $valid = 0;
            }
        }
        return array('status'=>$valid,'date'=>$newdate,'format'=>'');
    }

    public function XMLToArray($xml) {
        $parser = xml_parser_create('ISO-8859-1'); // For Latin-1 charset
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); // Dont mess with my cAsE sEtTings
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); // Dont bother with empty info
        xml_parse_into_struct($parser, $xml, $values);
        xml_parser_free($parser);
        $return = array(); // The returned array
        $stack = array(); // tmp array used for stacking
        
        foreach($values as $val) {
            $_tagNew = (strpos($val['tag'], ':') !== false) ? str_replace(':','',strstr($val['tag'], ':')) : $val['tag'];
            if($val['type'] == "open") { 
                if($_tagNew == 'NumbersSubmitted'){
                    $realdata = $val['attributes'];
                }               
                array_push($stack, $_tagNew);
            } elseif($val['type'] == "close") {
                array_pop($stack);
            } elseif($val['type'] == "complete") {
                if(!empty($realdata)){
                    $addAttribute = $realdata;
                    if(isset($val['attributes']['Result'])){
                        $addAttribute['Result'] = $val['attributes']['Result'];
                    } else {
                        $addAttribute['Result'] = '';
                    }
                } else {
                    if(isset($val['attributes']['Result'])){
                        $addAttribute['Result'] = $val['attributes']['Result'];
                    } else {
                        $addAttribute['Result'] = '';
                    }                    
                }
                array_push($stack, $val['tag']);
                $data = array('attributes'=>$addAttribute,'value'=>$val['value']);
                $this->setArrayValue($return, $stack, $data);
                array_pop($stack);
            }//if-elseif
        }//foreach
        return $return;
    }//function XMLToArray

    public function setArrayValue(&$array, $stack, $value) {
        if ($stack) {
            $key = array_shift($stack);
            $this->setArrayValue($array[$key], $stack, $value);
            return $array;
        } else {
            $array = $value;
        }//if-else
    }//function setArrayValue

    public $timestamps = false;
    protected $fillable = array(
        'id',
        'email_address',
        'phone',
        'first_name',
        'last_name',
        'postcode',
        'received',
    );
}