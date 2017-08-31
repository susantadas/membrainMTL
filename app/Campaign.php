<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Campaign extends Model
{
    public $timestamps = false;

    public function campaignCsvRequiredField($dataCsv) {
    	$data = array();
		if(isset($dataCsv['email']) && $dataCsv['email']!=''){
			$data['email'] = $dataCsv['email'];
		} else {
			$data['email'] = '0';
		}

		if(isset($dataCsv['phone']) && $dataCsv['phone']!=''){
			$data['phone'] = $dataCsv['phone'];
		} else {
			$data['phone'] = '0';
		}

		if(isset($dataCsv['title']) && $dataCsv['title']!=''){
			$data['title'] = $dataCsv['title'];
		} else {
			$data['title'] = '0';
		}

		if(isset($dataCsv['firstName']) && $dataCsv['firstName']!=''){
			$data['firstName'] = $dataCsv['firstName'];
		} else {
			$data['firstName'] = '0';
		}

		if(isset($dataCsv['lastName']) && $dataCsv['lastName']!=''){
			$data['lastName'] = $dataCsv['lastName'];
		} else {
			$data['lastName'] = '0';
		}

		if(isset($dataCsv['birthdate']) && $dataCsv['birthdate']!=''){
			$data['birthdate'] = $dataCsv['birthdate'];
		} else {
			$data['birthdate'] = '0';
		}

		if(isset($dataCsv['age']) && $dataCsv['age']!=''){
			$data['age'] = $dataCsv['age'];
		} else {
			$data['age'] = '0';
		}

		if(isset($dataCsv['ageRange']) && $dataCsv['ageRange']!=''){
			$data['ageRange'] = $dataCsv['ageRange'];
		} else {
			$data['ageRange'] = '0';
		}

		if(isset($dataCsv['gender']) && $dataCsv['gender']!=''){
			$data['gender'] = $dataCsv['gender'];
		} else {
			$data['gender'] = '0';
		}

		if(isset($dataCsv['address1']) && $dataCsv['address1']!=''){
			$data['address1'] = $dataCsv['address1'];
		} else {
			$data['address1'] = '0';
		}

		if(isset($dataCsv['address2']) && $dataCsv['address2']!=''){
			$data['address2'] = $dataCsv['address2'];
		} else {
			$data['address2'] = '0';
		}

		if(isset($dataCsv['city']) && $dataCsv['city']!=''){
			$data['city'] = $dataCsv['city'];
		} else {
			$data['city'] = '0';
		}

		if(isset($dataCsv['state']) && $dataCsv['state']!=''){
			$data['state'] = $dataCsv['state'];
		} else {
			$data['state'] = '0';
		}

		if(isset($dataCsv['postcode']) && $dataCsv['postcode']!=''){
			$data['postcode'] = $dataCsv['postcode'];
		} else {
			$data['postcode'] = '0';
		}

		if(isset($dataCsv['countryCode']) && $dataCsv['countryCode']!=''){
			$data['countryCode'] = $dataCsv['countryCode'];
		} else {
			$data['countryCode'] = '0';
		}

		return $data;
    }

    public function campaignApiRequiredField($dataApi) {
    	$data = array();
    	if(isset($dataApi['email']) && $dataApi['email']!=''){
			$data['email'] = $dataApi['email'];
		} else {
			$data['email'] = '0';
		}

		if(isset($dataApi['phone']) && $dataApi['phone']!=''){
			$data['phone'] = $dataApi['phone'];
		} else {
			$data['phone'] = '0';
		}

		if(isset($dataApi['title']) && $dataApi['title']!=''){
			$data['title'] = $dataApi['title'];
		} else {
			$data['title'] = '0';
		}

		if(isset($dataApi['firstName']) && $dataApi['firstName']!=''){
			$data['firstName'] = $dataApi['firstName'];
		} else {
			$data['firstName'] = '0';
		}

		if(isset($dataApi['lastName']) && $dataApi['lastName']!=''){
			$data['lastName'] = $dataApi['lastName'];
		} else {
			$data['lastName'] = '0';
		}

		if(isset($dataApi['birthdate']) && $dataApi['birthdate']!=''){
			$data['birthdate'] = $dataApi['birthdate'];
		} else {
			$data['birthdate'] = '0';
		}

		if(isset($dataApi['age']) && $dataApi['age']!=''){
			$data['age'] = $dataApi['age'];
		} else {
			$data['age'] = '0';
		}

		if(isset($dataApi['ageRange']) && $dataApi['ageRange']!=''){
			$data['ageRange'] = $dataApi['ageRange'];
		} else {
			$data['ageRange'] = '0';
		}

		if(isset($dataApi['gender']) && $dataApi['gender']!=''){
			$data['gender'] = $dataApi['gender'];
		} else {
			$data['gender'] = '0';
		}

		if(isset($dataApi['address1']) && $dataApi['address1']!=''){
			$data['address1'] = $dataApi['address1'];
		} else {
			$data['address1'] = '0';
		}

		if(isset($dataApi['address2']) && $dataApi['address2']!=''){
			$data['address2'] = $dataApi['address2'];
		} else {
			$data['address2'] = '0';
		}

		if(isset($dataApi['city']) && $dataApi['city']!=''){
			$data['city'] = $dataApi['city'];
		} else {
			$data['city'] = '0';
		}

		if(isset($dataApi['state']) && $dataApi['state']!=''){
			$data['state'] = $dataApi['state'];
		} else {
			$data['state'] = '0';
		}

		if(isset($dataApi['postcode']) && $dataApi['postcode']!=''){
			$data['postcode'] = $dataApi['postcode'];
		} else {
			$data['postcode'] = '0';
		}

		if(isset($dataApi['countryCode']) && $dataApi['countryCode']!=''){
			$data['countryCode'] = $dataApi['countryCode'];
		} else {
			$data['countryCode'] = '0';
		}
		return $data;
    }
}
