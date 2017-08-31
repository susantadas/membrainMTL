<?php
namespace App\Models\Api;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
use DB;
class LeadProcessValidation extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'lead_audit'; 
    public function validateSupplier($data){
        if(isset($data['Supplier']) && isset($data['Supplier']['ID'])){
            $valid=1;
        } else {
            $valid=0;
        }
        return $valid;
    }

    public function validateCampaignr($data){
        if(isset($data['campaign']) && isset($data['campaign']['ID'])){
            $valid=1;
        } else {
            $valid=0;
        }
        return $valid;
    }

    public function validateConsumer($data){        
        if(isset($data['consumer'])){
            if(!empty($data['consumer'])){
                $valid=1;
            } else {
              $valid=0; 
            }
        } else {
            $valid=0;
        }
        return $valid;
    }

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
                echo $birthdate = date('Y-m-d',strtotime($validBirthDate['date']));
            } else {
                $birthdate = $data['birthdate'];
                $valid = 0;
                $error .= 'Invalid birthdate,';
            }

            if(!preg_match($regx, $data['age'])){
                $valid = 0;
                $error .= 'Invalid age,';
            } else {
                if($data['age'] < 1 || $data['age'] > 100){
                    $valid = 0;
                    $error .= 'Invalid age,';
                }
            }

            $_ageRange = explode('-', $data['ageRange']);
            if(!preg_match($regx, $_ageRange[0]) && !preg_match($regx, $_ageRange[1])){
                $valid = 0;
                $error .= 'Invalid age range,';
            } else {
                if(($_ageRange[0] < 2 || $_ageRange[0] > 100) || ($_ageRange[1] < 2 || $_ageRange[1] > 100)){
                    $valid = 0;
                    $error .= 'Invalid age range,';
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
                                    $error .= 'Consumer does not qualify agerange,';
                                }
                            } else {
                                $valid = 0;
                                $error .= 'Consumer does not qualify age,';
                            }
                        } else {
                            $valid = 0;
                            $error .= 'Consumer does not qualify birthdate,';
                        }
                    } else {
                        $error .= 'Consumer does not qualify(criteria age have blank),';
                    }
                }
            }
        } else {
            $birthdate = $data['birthdate'];
            $valid = 0;
            $error .= 'Invalid birthdate.';
        }        
        return array('status'=>$valid,'birthdate'=>$birthdate,'error'=>$error);
    }

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