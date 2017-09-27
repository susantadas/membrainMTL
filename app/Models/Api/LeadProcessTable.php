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

    protected static $salaciousWord;
    protected static $salaciousWordemail;
    protected static $salaciousWordaddress;
    protected static $salaciousWordLastname;
    protected static function salaciousWord() {
        return static::$salaciousWord = DB::table('salacious_word')->where('first_name','=','1')->get(['pattern']);
    }
    protected static function salaciousWordemail() {
        return static::$salaciousWordemail = DB::table('salacious_word')->where('email_address','=','1')->get(['pattern']);
    }
    protected static function salaciousWordaddress() {
        return static::$salaciousWordaddress = DB::table('salacious_word')->where('address','=','1')->get(['pattern']);
    }
    protected static function salaciousWordLastname() {
        return static::$salaciousWordLastname = DB::table('salacious_word')->where('last_name','=','1')->get(['pattern']);
    }

    public function apiData($data){
        $campaignsDetails = DB::table('campaigns')->where('id','=',$data['campaign_id'])->get(['dncr_required','method','server_parameters','parameter_mapping','parameter_required']);
        $clientAccepted = '';
        $_parameterRequired = json_decode($campaignsDetails[0]->parameter_required);
        if(!empty($_parameterRequired)){
            if(isset($_parameterRequired->api)){
                $parameterRequired = $_parameterRequired->api;
            } else {
                $parameterRequired = $_parameterRequired->csv;
            }
        }
        $errors = array();
        $PayloadValidation = $this->payloadValidation($data);
        $_emailValidation = 1;

        $_pnpps = 1;
        $basicvalid = 1;
        if($PayloadValidation['cCode']!=''){
            $data['countryCode'] = $PayloadValidation['cCode'];
        }
        if($PayloadValidation['status']!='0'){
            $basicValidationforFiels = $this->basicValidationforFiels($data);
            if($basicValidationforFiels['status']=='0'){
                $basicvalid = 0;
                foreach ($basicValidationforFiels['error'] as $bsvkey => $_basicValidationforFiels) {
                    array_push($errors, $_basicValidationforFiels);
                }
            } 

            if(isset($parameterRequired->email) && $parameterRequired->email=='1'){
                $emailValidation = $this->emailAddressPreProcessing($data['email']);
                if($emailValidation['status']=='0'){ 
                    $_emailValidation = 0;
                    foreach ($emailValidation['error'] as $bsvkey => $_emailValidations) {
                        array_push($errors, $_emailValidations);
                    }                   
                }
            }

            if(isset($parameterRequired->phone) && $parameterRequired->phone=='1'){
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
                }
                if($pnpps['phone']!=''){
                    $data['phone']=$pnpps['phone'];
                }
            } else {
                if(isset($data['phone']) && isset($data['countryCode']) && $data['phone']!='' && $data['countryCode']!=''){
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
                    }
                    if($pnpps['phone']!=''){
                        $data['phone']=$pnpps['phone'];
                    }
                }
            }

            $duplicationChecking = $this->duplicationChecking($data['email'],$data['phone'],$data['countryCode']);
            $_duplicationChecking= 1;
            if($duplicationChecking=='0'){
                $_duplicationChecking= 0;  
                $errors[] = 'Duplicate Consumer';              
            } else {
                if($duplicationChecking=='2'){
                    $_duplicationChecking= 0;
                    $errors[] = 'Duplicate Consumer';
                    $errors[] = 'Prohibited Phone Number';
                }
            }

            if($_emailValidation!='0' && $_pnpps!='0' && $_duplicationChecking!='0' && $basicvalid !='0'){
                $nameTitle = $this->nameTitle($data['firstName'],$data['lastName'],$data['title']);
                if($nameTitle['status']=='0'){
                    $nameTitleError = $nameTitle['error'];
                    foreach ($nameTitleError as $nameKey => $nameError) {
                        array_push($errors, $nameError);
                    }
                }

                $addressFormatting = $this->addressFormatting($data);
                if($addressFormatting['status']=='0'){
                    $addressFormattingnewError = $addressFormatting['error'];
                    foreach ($addressFormattingnewError as $kadkey => $aderror) {
                        array_push($errors, $aderror);
                    }
                } else {
                    $addressFormattingnew = $addressFormatting['data'];
                    $data['postcode'] = $addressFormattingnew['postcode'];
                    $data['city'] = $addressFormattingnew['city'];
                    $data['address1'] = $addressFormattingnew['address1'];
                    $data['address2'] = $addressFormattingnew['address2'];
                }
                if(isset($parameterRequired->birthdate) && $parameterRequired->birthdate=='1' && isset($parameterRequired->age) && $parameterRequired->age=='1' && isset($parameterRequired->ageRange) && $parameterRequired->ageRange=='1'){
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $birthdateAgeAgeRangeError = $birthdateAgeAgeRange['error'];
                        foreach ($birthdateAgeAgeRangeError as $dobkey => $_birthdateAgeAgeRangeError) {
                            array_push($errors, $_birthdateAgeAgeRangeError);
                        }
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } elseif(isset($data['birthdate']) && isset($data['age']) && $data['birthdate']!='' && $data['age']!=''){
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $birthdateAgeAgeRangeError = $birthdateAgeAgeRange['error'];
                        foreach ($birthdateAgeAgeRangeError as $dobkey => $_birthdateAgeAgeRangeError) {
                            array_push($errors, $_birthdateAgeAgeRangeError);
                        }
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } else if(isset($data['birthdate']) && isset($data['ageRange']) && $data['birthdate']!='' && $data['ageRange']!=''){
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $birthdateAgeAgeRangeError = $birthdateAgeAgeRange['error'];
                        foreach ($birthdateAgeAgeRangeError as $dobkey => $_birthdateAgeAgeRangeError) {
                            array_push($errors, $_birthdateAgeAgeRangeError);
                        }
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } elseif(isset($data['ageRange']) && isset($data['age']) && $data['age']!='' && $data['ageRange']!=''){
                    $birthdateAgeAgeRange = $this->birthdateAgeAgeRange($data);
                    if($birthdateAgeAgeRange['status']=='0'){
                        $birthdateAgeAgeRangeError = $birthdateAgeAgeRange['error'];
                        foreach ($birthdateAgeAgeRangeError as $dobkey => $_birthdateAgeAgeRangeError) {
                            array_push($errors, $_birthdateAgeAgeRangeError);
                        }
                    } else {
                        $data['birthdate'] = $birthdateAgeAgeRange['birthdate'];
                    }
                } else {                    
                }
                $phoneAddress = $this->phoneAddress($data);                
            }

            if(empty($errors)){
                if(isset($parameterRequired->email) && $parameterRequired->email=='1'){
                    $emailValidationThirdParty = $this->emailValidationThirdParty($data['email']);
                    if($emailValidationThirdParty['status']=='0'){
                        $errors[] = $emailValidationThirdParty['error'];
                    }
                }

                if(isset($parameterRequired->phone) && $parameterRequired->phone=='1'){
                    $phoneValidation = $this->phoneValidation($pnpps['phone'],$data['countryCode'],$data['phone']);
                    if($phoneValidation['status']=='0'){
                        $errors[] = $phoneValidation['error'];
                    } else {
                        $data['phone'] = $pnpps['phone'];
                    }
                }

                if($campaignsDetails[0]->dncr_required=='0'){
                    if(isset($parameterRequired->phone) && $parameterRequired->phone=='1' && $data['countryCode']=='AU'){
                        $phoneValidationDncr = $this->phoneValidationDncr($data['phone']);
                        if($phoneValidationDncr['status']=='0'){
                            $errors[] = $phoneValidationDncr['error'];
                        }
                    }
                }

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

            if($campaignsDetails[0]->method=='API' && empty($errors)){
                $serverParameters = json_decode($campaignsDetails[0]->server_parameters);
                $parameterMapping = json_decode($campaignsDetails[0]->parameter_mapping);
                $clientDelivery = $this->clientDelivery($serverParameters,$parameterMapping,$data);
                if($clientDelivery=='0'){
                    $errors[] = 'Reject by Client';
                    $clientAccepted = 'Reject_by_Client';
                } else if($clientDelivery=='2'){
                    $errors[] = 'duplicate consumer';
                    $clientAccepted = 'client_duplicate';
                } else if($clientDelivery=='3'){
                    $errors[] = 'Client Time Out';
                } else {
                }
            }

            if(!empty($errors)){                
                $LAdata = array('supplier_id' => $data['supplier_id'],'source' => $data['supplier_id'],'client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' => json_encode($data),'received' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')),'errors' => 'True');
                if(in_array('Duplicate Consumer', $errors)) {
                    $data['lead_disposition']['error'] = $errors;
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
                if($clientAccepted!='' && $clientAccepted=='client_duplicate'){
                    $LHdataEmail = array('client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' =>$data['email'],'client_duplicate' => 1,'delivered' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')));
                    $LHdataPhone = array('client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' =>$data['phone'],'client_duplicate' => 1,'delivered' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')));
                    DB::table('lead_history')->insert($LHdataEmail);
                    DB::table('lead_history')->insert($LHdataPhone);
                }
            } else {
                $LAdata = array('supplier_id' => $data['supplier_id'],'source' => $data['supplier_id'],'client_id' => $data['client_id'],'campaign_id' => $data['campaign_id'],'data' => json_encode($data),'received' => Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a')),'errors' => 'False','disposition' => 'Accepted');
                DB::table('lead_audit')->insert($LAdata);
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
            if(!empty($_cmpRequired)){
                if(isset($_cmpRequired->api)){
                    $cmpRequired = $_cmpRequired->api;
                } else {
                    $cmpRequired = $_cmpRequired->csv;
                }

                if((!isset($data['email']) || $data['email']=='') && $cmpRequired->email=='1'){
                    $status = 0;
                    $error[] = 'Required email id';
                }

                if((!isset($data['phone']) || $data['phone']=='') && $cmpRequired->phone=='1'){
                    $status = 0;
                    $error[] = 'Required phone number';
                }

                if((!isset($data['title']) || $data['title']=='') && $cmpRequired->title=='1'){
                    $status = 0;
                    $error[] = 'Required title like Mr.,Mrs.,Ms.';
                }

                if((!isset($data['firstName']) || $data['firstName']=='') && $cmpRequired->firstName=='1'){
                    $status = 0;
                    $error[] = 'Required first name';
                }

                if((!isset($data['lastName']) || $data['lastName']=='') && $cmpRequired->lastName=='1'){
                    $status = 0;
                    $error[] = 'Required last name';
                }

                if((!isset($data['birthdate']) || $data['birthdate']=='') && $cmpRequired->birthdate=='1'){
                    $status = 0;
                    $error[] = 'Required birth date';
                }

                if((!isset($data['age']) || $data['age']=='') && $cmpRequired->age=='1'){
                    $status = 0;
                    $error[] = 'Required age';
                }

                if((!isset($data['ageRange']) || $data['ageRange']=='') && $cmpRequired->ageRange=='1'){
                    $status = 0;
                    $error[] = 'Required age range';
                }

                if((!isset($data['gender']) || $data['gender']=='') && $cmpRequired->gender=='1'){
                    $status = 0;
                    $error[] = 'Required gender';
                }

                if((!isset($data['address1']) || $data['address1']=='') && $cmpRequired->address1=='1'){
                    $status = 0;
                    $error[] = 'Required address1';
                }

                if((!isset($data['city']) || $data['city']=='') && $cmpRequired->city=='1'){
                    $status = 0;
                    $error[] = 'Required city';
                }

                if((!isset($data['state']) || $data['state']=='') && $cmpRequired->state=='1'){
                    $status = 0;
                    $error[] = 'Required state';
                }

                if((!isset($data['postcode']) || $data['postcode']=='') && $cmpRequired->postcode=='1'){
                    $status = 0;
                    $error[] = 'Required postcode.';
                }

                if((!isset($data['countryCode']) || $data['countryCode']=='') && $cmpRequired->countryCode=='1'){
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
        $error = array();
        if($email!=''){
            $_email = strtolower($email); //Standardised
            $_domainName1 = explode('@',$_email);
            $_domainName = end($_domainName1);
            if(preg_match($regex, $_email)){
                $domainBlacklist = DB::table('domain_blacklist')->where('domain','like','%'.$_domainName.'%')->get(['id']); //Not on Domain Blacklist
                if(!empty($domainBlacklist[0])){
                    $error[] = 'Prohibited Email Id for your domain';
                }
                $emailBlacklist = DB::table('email_blacklist')->where('email_address','=',$_email)->get(['id']);
                if(!empty($emailBlacklist[0])){
                    $error[] = 'Prohibited Email Id';
                }
            } else {
                $error[] = 'Invalid Email Id';
            }

            $salaciousWordemail = static::salaciousWordemail();
            if(!empty($salaciousWordemail[0])){
                foreach($salaciousWordemail as $swk => $_salaciousWord){
                    $_newSrtingSw = str_replace('/', '', $_salaciousWord->pattern);
                    $_newSrtingSws = str_replace('\p', 'p', $_newSrtingSw);
                    $newSrtingSw = str_replace('\b', '', $_newSrtingSws);
                    $newSrtingSw=strtolower($newSrtingSw);
                    $regxNew = "/\b(\w*$newSrtingSw\w*)\b/";
                    if(preg_match($regxNew,strtolower($_email))){
                        $error[]='salacious word in email id';
                        break;
                    }
                }
            }
        } else {
            $error[]='Required email id';
        }
        if(!empty($error)){
            return array('status'=>'0','error'=>$error);
        } else{
            return array('status'=>'1','error'=>$error);;
        }
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
                if(strrpos($_phone,'+64',-$bforelength)!== false || strrpos($_phone,'0064',-$bforelength)!== false){
                    if($country!='NZ'){
                        $valid = 0;
                    } else {
                        $valid = 1;
                    }
                } elseif(strrpos($_phone,'+61',-$bforelength)!== false || strrpos($_phone,'0061',-$bforelength)!== false){
                    if($country!='AU'){
                        $valid = 0;
                    } else {
                        $valid = 1;
                    }
                } elseif(strrpos($_phone,'+1',-$bforelength)!== false || strrpos($_phone,'0111',-$bforelength)!== false){
                    if($country=='US' || $country=='CA'){
                        $valid = 1;
                    } else {
                        $valid = 0;
                    }
                } elseif(strrpos($_phone,'+44',-$bforelength)!== false || strrpos($_phone,'0044',-$bforelength)!== false){
                    if($country=='UK' || $country=='GB'){
                        $valid = 1;
                    } else {
                        $valid = 0;
                    }
                } else {
                    $valid = 1;
                }

                if($valid=='1'){
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
                    $valid=0;
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
        $_lhe = DB::table('lead_history')->where('data','=',$email)->orWhere('data','=',$phone)->get(['id']); //Membrain Lead Delivery Lists email
        if(empty($_lhe[0])){
            $lhe = 1;
            $_donotcall = DB::table('membrain_global_do_not_call')->where('country_code','=',$country)->where('phone_number','=',$phone)->get(['id']); //Membrain Global Do No
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
                $validdata = 2;
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
        $error = array();
        $regx = "/^[a-zA-Z’ ]*$/";
        if(!preg_match($regx, $fname) || !preg_match($regx, $lname) || !preg_match($regx, $title)){           
            $error[]='Invalid characters in First Name / Last Name / title';
        } else {          
            $name = preg_replace('/[^A-Za-z ]/', '', $fname).' '.preg_replace('/[^A-Za-z ]/', '', $lname);
            $_name = DB::table('name_blacklist')->where('name','like','%'.$name.'%')->get(['id']);
            if(empty($_name[0])){
                $salaciousWords = static::salaciousWord();
                if(!empty($salaciousWords[0])){
                    foreach($salaciousWords as $swk => $_salaciousWord){
                        $_newSrtingSw = str_replace('/', '', $_salaciousWord->pattern);
                        $_newSrtingSwn = str_replace('\p', 'p', $_newSrtingSw);
                        $newSrtingSw = str_replace('\b', '', $_newSrtingSwn);
                        $newSrtingSw=strtolower($newSrtingSw);
                        $regxNew = "/\b(\w*$newSrtingSw\w*)\b/";
                        if(preg_match($regxNew,strtolower($fname))){
                            $error[]='Salacious word in First Name';
                            break;
                        }
                    }
                }

                $salaciousWordLastnames = static::salaciousWordLastname();
                if(!empty($salaciousWords[0])){
                    foreach($salaciousWordLastnames as $swkl => $_salaciousWordL){
                        $_newSrtingSwL = str_replace('/', '', $_salaciousWordL->pattern);
                        $_newSrtingSwLs = str_replace('\p', 'p', $_newSrtingSwL);
                        $newSrtingSwL = str_replace('\b', '', $_newSrtingSwLs);
                        $newSrtingSwL=strtolower($newSrtingSwL);
                        $regxNewL = "/\b(\w*$newSrtingSwL\w*)\b/";
                        if(preg_match($regxNewL,strtolower($lname))){
                            $error[]='Salacious word in Last Name';
                            break;
                        }
                    }
                }
            } else {
                $error[]='Prohibited consumer name';
            }
        }
        if(!empty($error)){
            return array('status'=>'0','error'=>$error);
        } else {
            return array('status'=>'1','error'=>'');
        }
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 1.) Name / Title / Gender Processing */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 2.) Address */
    public function addressFormatting($data){
        $couCode=$data['countryCode'];
        $ZIPREG=array(
            "US"=>"^\d{5}([\-]?\d{4})?$",
            "UK"=>"^[a-zA-Z]{1,2}$",
            "GB"=>"^[a-zA-Z]{1,2}$",
            "CA"=>"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
            "AU"=>"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$"
        );
        $error = array();
        if($couCode!='' && $data['postcode']!=''){ 
            if (isset($ZIPREG[$couCode])) {
                if (!preg_match("/".$ZIPREG[$couCode]."/i",$data['postcode'])){
                    $error[] = 'Invalid ZIP/Post code';
                }
            }
        }
        if($data['countryCode']=='UK' || $data['countryCode']=='CA' || $data['countryCode']=='GB'){
            $data['postcode'] = strtoupper(wordwrap($data['postcode'] , 3 , ' ' , true ));
        } else {
            $data['postcode'] = strtoupper($data['postcode']);
        }
        $campaigns=0;
        if(isset($data['countryCode']) && $data['countryCode']=='AU'){
            $_postcode = DB::table('postcode')->where('country_code','=',$data['countryCode'])->where('state','=',$data['state'])->where('city','=',$data['city'])->where('postcode','=',$data['postcode'])->get(['id']);
            if(empty($_postcode[0])){
                $error[] = 'Invalid zip/post code';
            }

            $_city = DB::table('postcode')->where('country_code','=',$data['countryCode'])->where('state','=',$data['state'])->where('city','=',$data['city'])->get(['id']);
            if(empty($_city[0])){
                $error[] = 'Invalid city';
            }

            $_state = DB::table('postcode')->where('country_code','=',$data['countryCode'])->where('state','=',$data['state'])->get(['id']);
            if(empty($_state[0])){
                $error[] = 'Invalid state';
            }
        }


        $_campaigns = DB::table('campaigns')->where('id','=',$data['campaign_id'])->get(['criteria_state','criteria_postcode']);
        if(!empty($_campaigns[0])){
            if($_campaigns[0]->criteria_state!=''){
                $stateCriterias = explode(',', $_campaigns[0]->criteria_state);
                if(!in_array($data['state'], $stateCriterias)){
                    $error[] = 'Invalid state because does not qualify campaign state criteria';
                } 
            }

            if($_campaigns[0]->criteria_postcode!=''){
                $postcodeCriterias = explode(',', $_campaigns[0]->criteria_postcode);
                if(!in_array($data['postcode'], $postcodeCriterias)){
                    $error[] = 'Invalid ZIP/Post code because does not qualify campaign ZIP/Post code criteria';
                }                
            }
        }

        $salaciousWordaddress = static::salaciousWordaddress();
        if(!empty($salaciousWordaddress[0])){
            foreach($salaciousWordaddress as $swk => $_salaciousWord){
                $_newSrtingSw = str_replace('/', '', $_salaciousWord->pattern);
                $_newSrtingSwn = str_replace('\p', 'p', $_newSrtingSw);
                $newSrtingSw = str_replace('\b', '', $_newSrtingSwn);
                $newSrtingSw=strtolower($newSrtingSw);
                $regxNew = "/\b(\w*$newSrtingSw\w*)\b/";
                $address = $data['address1'].', '.$data['address2'];
                if(preg_match($regxNew,strtolower($address))){
                    $error[] = 'salacious word in address';
                    break;
                }
            }
        } 

        $data['city'] = strtoupper($data['city']);
        $data['address1'] = ucwords(str_replace(array('Avenue','Street'), array('Ave','St'), $data['address1']));
        $data['address2'] = ucwords(str_replace(array('Avenue','Street'), array('Ave','St'), $data['address2']));
        if(!empty($error)){
            return array('status'=>'0','data'=>$data,'error'=>$error);
        } else {
            return array('status'=>'1','data'=>$data,'error'=>$error);
        }
    }
    /* End Concurrent Data Reformatting, Enhancement and Validation 2.) Address */
    /* Start Concurrent Data Reformatting, Enhancement and Validation 3.) Birthdate / Age / Age Range */
    public function birthdateAgeAgeRange($data){
        $valid = 1;
        $regx = "/^[0-9]+$/";
        $error = array();
        if(isset($data['birthdate']) && $data['birthdate']!=''){
            $format = array('Y-m-d','m/d/Y','m/d/y','d M Y','d M y','M d y','M d Y','d-m-Y','d-m-y','y-m-d','d/m/Y','d/m/y','d-M-Y','j-n-y','j-n-Y','j/n/y','j/n/Y','n/j/Y','n/j/y','y-n-j','Y-n-j','j n y','j n Y','n j y','n j Y','d/M/Y','j/M/Y');
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
            } else {
                $birthdate = $data['birthdate'];
                $valid = 0;
                $error[] = 'Invalid birthdate';
            }
        } else {
            $birthdate = '';
        }

        if(isset($data['age']) && $data['age']!=''){
            if(!preg_match($regx, $data['age'])){
                $valid = 0;
                $error[] = 'Invalid age';
            } else {
                if($data['age'] < 1 || $data['age'] > 100){
                    $valid = 0;
                    $error[] = 'Invalid age';
                }
            }
        }
        if($data['ageRange']!=''){
            $_ageRange = explode('-', $data['ageRange']);
            if(!preg_match($regx, $_ageRange[0]) && !preg_match($regx, $_ageRange[1])){
                $valid = 0;
                $error[] = 'Invalid age range';
            } else {
                if(($_ageRange[0] < 1 || $_ageRange[0] > 100) || ($_ageRange[1] < 2 || $_ageRange[1] > 100)){
                    $valid = 0;
                    $error[] = 'Invalid age range';
                }
            }
        }

        if($valid=='1'){
            $_campaigns = DB::table('campaigns')->where('id',$data['campaign_id'])->get(['id','criteria_age','parameter_required']);                
            if(!empty($_campaigns[0])){
                $_parameterRequired = json_decode($_campaigns[0]->parameter_required);
                if(!empty($_parameterRequired)){
                    if(isset($_parameterRequired->api)){
                        $parameterRequired = $_parameterRequired->api;
                    } else {
                        $parameterRequired = $_parameterRequired->csv;
                    }
                }
                if($_campaigns[0]->criteria_age!=''){
                    $criteriaAge = explode('-', $_campaigns[0]->criteria_age);
                    $dob=$birthdate;
                    $actualAge = (date('Y') - date('Y',strtotime($dob)));
                    if($data['ageRange']!=''){
                        if($criteriaAge[0] != $_ageRange[0] || $criteriaAge[1] != $_ageRange[1]){
                            $valid = 0;
                            $error[] = "Supplied Age Range doesn't match with Campaign Age Range";                        
                        } else {
                            if($data['age']!=''){
                                if($data['age'] >= $criteriaAge[0] && $data['age'] <= $criteriaAge[1]){
                                    if($dob!=''){
                                        if($actualAge < $criteriaAge[0] || $actualAge > $criteriaAge[1]){
                                            $valid = 0;
                                            $error[] = 'Consumer does not qualify birthdate';
                                        } else {
                                            if($data['age']!=$actualAge){
                                                $valid = 0;
                                                $error[] = 'date of birth does not match with age provided';
                                            }
                                        }
                                    }
                                } else {
                                    $valid = 0;
                                    $error[] = 'Consumer does not qualify age';
                                }
                            } else {
                                if($dob!=''){
                                    if($actualAge < $criteriaAge[0] || $actualAge > $criteriaAge[1]){
                                        $valid = 0;
                                        $error[] = 'Consumer does not qualify birthdate';
                                    }
                                }
                            }
                        }
                    } else {
                        if($data['age']!=''){
                            if($data['age'] >= $criteriaAge[0] && $data['age'] <= $criteriaAge[1]){
                                if($dob!=''){
                                    if($actualAge < $criteriaAge[0] || $actualAge > $criteriaAge[1]){
                                        $valid = 0;
                                        $error[] = 'Consumer does not qualify birthdate';
                                    } else {
                                        if($data['age']!=$actualAge){
                                            $valid = 0;
                                            $error[] = 'date of birth does not match with age provided';
                                        }
                                    }
                                }
                            } else {
                                $valid = 0;
                                $error[] = 'Consumer does not qualify age';
                            }
                        } else {
                            if($dob!=''){
                                if($actualAge < $criteriaAge[0] || $actualAge > $criteriaAge[1]){
                                    $valid = 0;
                                    $error[] = 'Consumer does not qualify birthdate';
                                }
                            }
                        }
                    }
                } else {                        
                    if($data['ageRange']!='' && isset($data['ageRange'])){
                        $dob=$birthdate;
                        $actualAge = (date('Y') - date('Y',strtotime($dob)));
                        $_ageRanges = explode('-', $data['ageRange']);
                        if($data['age']!='' && isset($data['age'])){
                            if($data['age'] >= $_ageRanges[0] && $data['age'] <= $_ageRanges[1]){
                                if($dob!=''){
                                    if($actualAge < $_ageRanges[0] || $actualAge > $_ageRanges[1]){
                                        $valid = 0;
                                        $error[] = 'Consumer does not qualify birthdate';
                                    } else {
                                        if($data['age']!=$actualAge){
                                            $valid = 0;
                                            $error[] = 'date of birth does not match with age provided';
                                        }
                                    }
                                }
                            }else {
                                $valid = 0;
                                $error[] = 'Consumer does not qualify age';
                            }
                        } else {
                            if($dob!=''){
                                if($actualAge < $_ageRanges[0] || $actualAge > $_ageRanges[1]){
                                    $valid = 0;
                                    $error[] = 'Consumer does not qualify birthdate';
                                }
                            }
                        } 
                    } else {
                        $dob=$birthdate;
                        $actualAge = (date('Y') - date('Y',strtotime($dob)));
                        if($data['age']!='' && isset($data['age'])){
                            if($data['age']!=$actualAge){
                                $valid = 0;
                                $error[] = 'date of birth does not match with age provided';
                            }
                        }
                    }
                }
            }
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
        $emailHistory = DB::table('email_history')->where('email_address','=',$email)->where('is_valid','0')->get(['id']);
        $valid = 1;
        $error ='';
        if(empty($emailHistory[0])){
            $url = "https://bpi.briteverify.com/emails.json?address=".$email."&apikey=cf7d1153-f2af-4b86-9bdd-d5c6b6ad8078";
            $client = new Client();
            $res = $client->get($url);
            $result = $res->getBody();
            $_result = json_decode($result);
            if(isset($_result->error)){                
                $_emailHis = array('email_address'=>$email,'is_valid'=>'0','validation_date'=>date('Y-m-d'));
                DB::table('email_history')->insert($_emailHis);                
                $valid = 0;
                $error = $_result->error;
            } else {
                $exitCpId = DB::table('email_history')->where('email_address','=',$email)->where('is_valid','=','1')->get(['id']);
                if(empty($exitCpId[0])) {
                    $_emailHis = array('email_address'=>$email,'is_valid'=>'1','validation_date'=>date('Y-m-d'));
                    DB::table('email_history')->insert($_emailHis);
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
        if($parameterMapping->email!=''){
            $newdata[$parameterMapping->email] = $data['email'];
        } else {
            $newdata['email'] = $data['email'];
        }

        if($parameterMapping->phone!=''){
            $newdata[$parameterMapping->phone] = $data['phone'];
        } else {
            $newdata['phone'] = $data['phone'];
        }

        if($parameterMapping->title!=''){
            $newdata[$parameterMapping->title] = $data['title'];
        } else {
            $newdata['title'] = $data['title'];
        }

        if($parameterMapping->firstName!=''){
            $newdata[$parameterMapping->firstName] = $data['firstName'];
        } else {
            $newdata['firstName'] = $data['firstName'];
        }

        if($parameterMapping->lastName!=''){
            $newdata[$parameterMapping->lastName] = $data['lastName'];
        } else {
            $newdata['lastName'] = $data['lastName'];
        }

        if($parameterMapping->birthdate!=''){
            $newdata[$parameterMapping->birthdate] = $data['birthdate'];
        } else {
            $newdata['birthdate'] = $data['birthdate'];
        }

        if($parameterMapping->age!=''){
            $newdata[$parameterMapping->age] = $data['age'];
        } else {
            $newdata['age'] = $data['age'];
        }

        if($parameterMapping->ageRange!=''){
            $newdata[$parameterMapping->ageRange] = $data['ageRange'];
        } else {
            $newdata['ageRange'] = $data['ageRange'];
        }

        if($parameterMapping->gender!=''){
            $newdata[$parameterMapping->gender] = $data['gender'];
        } else {
            $newdata['gender'] = $data['gender'];
        }

        if($parameterMapping->address1!=''){
            $newdata[$parameterMapping->address1] = $data['address1'];
        } else {
            $newdata['address1'] = $data['address1'];
        }

        if($parameterMapping->address2!=''){
            $newdata[$parameterMapping->address2] = $data['address2'];
        } else {
            $newdata['address2'] = $data['address2'];
        }

        if($parameterMapping->city!=''){
            $newdata[$parameterMapping->city] = $data['city'];
        } else {
            $newdata['city'] = $data['city'];
        }

        if($parameterMapping->state!=''){
            $newdata[$parameterMapping->state] = $data['state'];
        } else {
            $newdata['state'] = $data['state'];
        }

        if($parameterMapping->postcode!=''){
            $newdata[$parameterMapping->postcode] = $data['postcode'];
        } else {
            $newdata['postcode'] = $data['postcode'];
        }

        if($parameterMapping->countryCode!=''){
            $newdata[$parameterMapping->countryCode] = $data['countryCode'];
        } else {
            $newdata['countryCode'] = $data['countryCode'];
        }

        if($serverParameters->type!=''){
            $newdata['type'] = $serverParameters->type;
        } else {
            if(preg_match("@^https://@i",$serverParameters->endpoint)){
                $newdata['type'] = '443';
            }
        }

        if($serverParameters->endpoint!=''){
            $newdata['endpoint'] = $serverParameters->endpoint;
        }

        if($serverParameters->user!=''){
            $newdata['user'] = $serverParameters->user;
        }

        if($serverParameters->password!=''){
            $newdata['password'] = $serverParameters->password;
        }
        if($serverParameters->port!=''){
            $newdata['port'] = $serverParameters->port;
        }

        if($serverParameters->accepted!='' && isset($serverParameters->accepted)){
            $newdata['accepted'] = $serverParameters->accepted;
            $_regxaccepted = strtolower($serverParameters->accepted);
        } else {
            $_regxaccepted = '';
        }
        if($serverParameters->duplicate!='' && isset($serverParameters->duplicate)){
            $newdata['duplicate'] = $serverParameters->duplicate;
            $_regxduplicate = strtolower($serverParameters->duplicate);
        } else {
            $_regxduplicate = ''; 
        }
        $url = $serverParameters->endpoint;
        try{
            $client = new Client(['timeout'=>10]);
            if(isset($newdata['type']) && ($newdata['type']=='POST' || $newdata['type']=='')){
                $res = $client->post($url, ['form_params'=>$newdata,'timeout' => 10]);
            } elseif(isset($newdata['type']) && $newdata['type']=='GET') {
                $res = $client->get($url, ['form_params'=>$newdata,'timeout' => 10]);
            } else {
                $res = $client->post($url, ['form_params'=>$newdata,'timeout' => 10]);
            }
            $result = $res->getBody();

            $regxaccepted = "/\b(\w*$_regxaccepted\w*)\b/";
            $regxduplicate = "/\b(\w*$_regxduplicate\w*)\b/";
            if(preg_match($regxaccepted,strtolower($result))){
                $_result = 1;
            } else if(preg_match($regxduplicate,strtolower($result))){
                $_result = 2;
            } else {
                $_result = 0;
            }
        } catch (\Exception $e) {
            if($e->getMessage()!=''){
                $_result = 3;
            }
        }
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

    public function basicValidationforFiels($data) {
        $error = array();
        if(!isset($data['email']) || $data['email']==''){
            $error[] = 'basic validation not possble because email is empty';
        } else {
            $regexEmail = '/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/';
            if(!preg_match($regexEmail, $data['email'])){
                $error[] = 'Email id failed basic validation';
            } 
        }

        if(!isset($data['phone']) || $data['phone']==''){
            $error[] = 'basic validation not possble because phone number is empty';
        } else {
            $regexPhone = '/^[0-9+]*$/';
            if(!preg_match($regexPhone, $data['phone'])){
                $error[] = 'phone number failed basic validation';
            } 
        }

        if(isset($data['birthdate']) && $data['birthdate']!=''){
            $format = array('Y-m-d','m/d/Y','m/d/y','d M Y','d M y','M d y','M d Y','d-m-Y','d-m-y','y-m-d','d/m/Y','d/m/y','d-M-Y','j-n-y','j-n-Y','j/n/y','j/n/Y','n/j/Y','n/j/y','y-n-j','Y-n-j','j n y','j n Y','n j y','n j Y','d/M/Y','j/M/Y');
            $validBirthDate = $this->validateDateTime($data['birthdate'],$format);
            if($validBirthDate['status']=='0'){
                $error[] = 'Birthdate failed basic validation';
            } elseif(strtotime($validBirthDate['date']) >= strtotime(date('Y-m-d H:i:s'))){
                $error[] = 'Invalid birthdate because it is future date';
            } else {
                $Y1 = date('Y',strtotime($validBirthDate['date']));
                $Y2 = date('Y', strtotime('-100 year'));
                $Y3 = date('Y');
                if($Y1<=$Y2 || $Y1>=$Y3){
                    $error[] = 'Birthdate failed basic validation';
                }
            }
        }

        if(isset($data['age']) && $data['age']!=''){
            $regexAge = '/^[0-9]{1,3}$/';
            if(!preg_match($regexAge, $data['age'])){
                $error[] = 'Not A valid Age';
            } else {
                if($data['age'] > 100 || $data['age'] < 1){
                    $error[] = 'Not A valid Age';
                }
            }
        }

        if(isset($data['ageRange']) && $data['ageRange']!=''){
            $regexPhone = '/^[0-9-]*$/';
            if(!preg_match($regexPhone, $data['ageRange'])){
                $error[] = 'Age range failed basic validation';
            } else {
                $agerange = explode('-',$data['ageRange']);
                if(($agerange[0] < 1 || $agerange[0] > 100) || ($agerange[1] < 1 || $agerange[1] > 100)){
                    $error[] = 'Age range failed basic validation';
                }
            }
        }

        if(isset($data['gender']) && $data['gender']!=''){
            $genderNew = array('Male','Female','Unknown');
            if(!in_array(ucwords($data['gender']),$genderNew)){
                $error[] = 'Invalid gender';
            }
        }

        if(!empty($error)){
            $valid = 0;
        } else {
            $valid = 1;
        }
        return array('status'=>$valid,'error'=>$error);
    }

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