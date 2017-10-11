<?php

namespace Validate\lib;

class Validate{
	
	/**
	* Function Name: validateApiRequest
	* Description: To generate validateApi request 
	* parameter : array
	* Return : array with errors and flag
	*/
	public function validateApiRequest($api_data){

		$errors = Array();
		$errors['flag'] = TRUE;

		//Validate API Name
		$api_array = array('statusquery','checkpaymentstatus','getrequeststatus','getmdr','gettransactiondetails','fetchtransactionperiod','gettodaysdata','refund');

		//Validate key api_name 
		if(isset($api_data['api_name']) && !empty($api_data['api_name'])){
			$api_data['api_name'] = strtolower(trim($api_data['api_name']));
			//Validate API name value
			if(in_array($api_data['api_name'], $api_array)){
				//Validate key mode
				if(isset($api_data['mode']) && !empty($api_data['mode'])){
					//Validate mode value
					if($api_data['mode'] == "1" || $api_data['mode'] == "2"){

						//Validate API wise mandatory parameter
						$errors = $this->validateApiBasedRequest($api_data);

					}else{
						$errors['flag'] = FALSE;
						$errors['error_mode'] = "mode field should be 1(xml) or 2(json).";
					}
				}else{

					$errors['flag'] = FALSE;
					$errors['error_mode'] = "mode field is mandatory.";
				}
				
			}else{

				$errors['flag'] = FALSE;
				$errors['error_api_name'] = "Invalid API name.";
			}

		}else{

			$errors['flag'] = FALSE;
			$errors['error_api_name'] = "api_name field is mandatory.";
		}

		return $errors;
	}


	/**
	* Function Name: validateApiBasedRequest
	* Description: To generate validateApi request 
	* parameter : array
	* Return : array with errors and flag
	*/
	private function validateApiBasedRequest($api_data){

		$errors['flag'] = TRUE;		
		$api_name = strtolower($api_data['api_name']);
		
		switch ($api_name) {
			case 'statusquery':
					
					if(!isset($api_data['tran_ref_no']) || empty($api_data['tran_ref_no'])){

						$errors['flag'] = FALSE;
						$errors['error_tran_ref_no'] = "tran_ref_no field is mandatory.";
					}
				break;

			case in_array($api_name,array('checkpaymentstatus','getrequeststatus', 'getmdr' , 'gettransactiondeatils')):
					
					if(!isset($api_data['tranid']) || empty($api_data['tranid'])){

						$errors['flag'] = FALSE;
						$errors['error_tranid'] = "tranid field is mandatory.";
					}

					if(!isset($api_data['requestid']) || empty($api_data['requestid'])){

						$errors['flag'] = FALSE;
						$errors['error_requestid'] = "requestid field is mandatory.";
					}
				break;

			case 'fetchtransactionperiod':

					if(!isset($api_data['startdate']) || empty($api_data['startdate'])){

						$errors['flag'] = FALSE;
						$errors['error_startdate'] = "startdate field is mandatory.";
					}

					if(!isset($api_data['enddate']) || empty($api_data['enddate'])){

						$errors['flag'] = FALSE;
						$errors['error_enddate'] = "enddate field is mandatory.";
					}

					if(!isset($api_data['requestid']) || empty($api_data['requestid'])){

						$errors['flag'] = FALSE;
						$errors['error_requestid'] = "requestid field is mandatory.";
					}
				
				break;

			case 'gettodaysdata':

					if(!isset($api_data['requestid']) || empty($api_data['requestid'])){

						$errors['flag'] = FALSE;
						$errors['error_requestid'] = "requestid field is mandatory.";
					}

				break;
				
			case 'refund':
					
					if(!isset($api_data['timestamp']) || empty($api_data['timestamp'])){

						$errors['flag'] = FALSE;
						$errors['error_timestamp'] = "timestamp field is mandatory.";
					}

					if(!isset($api_data['tran_ref_no']) || empty($api_data['tran_ref_no'])){

						$errors['flag'] = FALSE;
						$errors['error_tran_ref_no'] = "tran_ref_no field is mandatory.";
					}

					if(!isset($api_data['txn_amount']) || empty($api_data['txn_amount'])){

						$errors['flag'] = FALSE;
						$errors['error_txn_amount'] = "txn_amount field is mandatory.";
					}

					if(!isset($api_data['org_jm_tran_ref_no']) || empty($api_data['org_jm_tran_ref_no'])){

						$errors['flag'] = FALSE;
						$errors['error_org_jm_tran_ref_no'] = "org_jm_tran_ref_no field is mandatory.";
					}

					if(!isset($api_data['org_txn_timestamp']) || empty($api_data['org_txn_timestamp'])){

						$errors['flag'] = FALSE;
						$errors['error_org_txn_timestamp'] = "org_txn_timestamp field is mandatory.";
					}
				break;
						
			default:
				# code...
				break;
		}
		return $errors;
	}


	/**
	* Function Name: validatePurchaseRequest
	* Description: To validate Purchase Request 
	* parameter : data array
	* Return : array with errors and flag
	*/
	public function validatePurchaseRequest($post_data){
		
		$errors = Array();		

		//Mandatory fields 
		$mendatory_fields = array('transaction.extref', 'transaction.amount', 'subscriber.customername','subscriber.mobilenumber','returl');
		//Data keys
		$post_fields = array_keys($post_data);

		//Check For Mandatory fields 
		$result = array_diff($mendatory_fields, $post_fields);

		if(!empty($result)){			
			$errors['error_mandatory_fields'] = "Missing mandatory fields: ".implode($result,',');
		}else{			
			//Validate transaction.extref field
			$extren_length = strlen($post_data['transaction.extref']);
			if($extren_length < 1 || $extren_length > 20){
				$errors['error_extren_length'] = "transaction.extref field length is between 1 to 20 character";
			}
			//Validate transaction.amount field
			$amount_lenght = strlen($post_data['transaction.amount']);
			if($amount_lenght < 4 || $amount_lenght > 12){
				$errors['error_amount_length'] = "transaction.amount value length is between 4 to 12 character";			
			}else if($post_data['transaction.amount'] < 0){
				$errors['error_amount_negative'] = "transaction.amount value should be positive decimals";
			}elseif(substr($post_data['transaction.amount'], -3, 1) != "."){
				$errors['error_amount_decimal'] = "transaction.amount value should be in two decimals";
			}

			//Validate subscriber.customername field
			$customername_lenght = strlen($post_data['subscriber.customername']);
			if($customername_lenght < 1 || $customername_lenght > 50){
				$errors['error_customername_lenght'] = "subscriber.customername value length is between 1 to 50 character";			
			}
			$customername_flag = $this->validateAlphaSpecialChar($post_data['subscriber.customername']);
			if(!$customername_flag){
				$errors['error_customername_flag'] = "subscriber.customername value contains only Alpha and Special characters(Hyphen,underscore,dot,space,comma or @) ";
			}

			//Validate subscriber.mobilenumber
			$mobilenumber_lenght = strlen($post_data['subscriber.mobilenumber']);
			if($mobilenumber_lenght < 10 || $mobilenumber_lenght > 13){
				$errors['error_mobilenumber_lenght'] = "subscriber.mobilenumber value length is between 10 to 13 character";			
			}
			$mobilenumber_flag = $this->validateMobileNumber($post_data['subscriber.mobilenumber']);
			if(!$mobilenumber_flag){
				$errors['error_mobilenumber_flag'] = "subscriber.mobilenumber value contains only numeric and Special character (+)";
			}

		}

		if(!empty($errors)){
			$errors['flag'] = FALSE;
		}else{
			$errors['flag'] = TRUE;
		}		
		return $errors;

	}

	/**
	* Function Name: validateAlphaSpecialChar
	* Description: value contains only Alpha and Special characters(Hyphen,underscore,dot,space,comma or @)
	* parameter : string
	* Return : boolean
	*/

	private function validateAlphaSpecialChar($string){

		if(preg_match('/^[A-Za-z0-9 _,-.@]*$/', $string)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	* Function Name: validateAlphaSpecialChar
	* Description: value contains only Alpha and Special characters(Hyphen,underscore,dot,space,comma or @)
	* parameter : string
	* Return : boolean
	*/
	private function validateMobileNumber($mobilenumber){
		if(preg_match('/^[0-9+]*$/', $mobilenumber)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

}

?>