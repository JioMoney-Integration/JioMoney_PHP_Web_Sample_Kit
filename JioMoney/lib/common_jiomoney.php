<?php

use Validate\lib\Validate as Validate; 

/*
- Common functions required for payment gateway integration
*/

// following files need to be included
require_once 'config_jiomoney.php';
require_once 'validate.php';

class common_jiomoney extends Validate{

	/**
	* Function Name: genrateChecksum
	* Description: Generate checksum depends upon type for request
	* Parameters : $type = type of transaction (e.g. PURCHASE,REFUND etc )
	* Return : checksum String
	*/

	public function genrateChecksum($type, $data, $udf = array()){
		if(!empty($type)){
			$checksum_str = "";
			switch ($type) {
				case 'PURCHASE':
					//Check for UDF fields
					if(!empty($udf)){

						/* CHECKSUM GENRATION FORMAT WITH DESCRIPTION & UDFs:
						* Clientid|Amount|Extref|Channel|MerchantId|Token|ReturnUrl|TxnTimeStamp|TxnType|subscriber.mobilenumber|productdescription |UDF1|UDF2|UDF3|UDF4|UDF5
						*/
						$data['productdescription'] = isset($data['productdescription']) ? $data['productdescription'] : "";

						$checksum_str = Config::$clientId."|".$data['transaction.amount']."|".$data['transaction.extref']."|".$data['channel']."|".Config::$merchantId."||".$data['returl']."|".$data['transaction.timestamp']."|PURCHASE|".$data['subscriber.mobilenumber']."|".$data['productdescription']."|".$data['udf1']."|".$data['udf2']."|".$data['udf3']."|".$data['udf4']."|".$data['udf5'];

					}else{

						/* CHECKSUM GENRATION FORMAT:
						* Clientid|Amount|Extref|Channel|MerchantId|Token|ReturnUrl|TxnTimeStamp|TxnType|subscriber.mobilenumber
						*/

						$checksum_str = Config::$clientId."|".$data['transaction.amount']."|".$data['transaction.extref']."|".$data['channel']."|".Config::$merchantId."||".$data['returl']."|".$data['transaction.timestamp']."|PURCHASE|".$data['subscriber.mobilenumber'];
					}

					
					break;
				case 'REFUND' :
					/*  CHECKSUM GENRATION FORMAT:
					* MERCHANT_ID|api_name|timestamp|tran_ref_no|txn_amount|org_jm_tran_ref_no|org_txn_timestamp|additional_info
					*/
					$data['additional_info'] = isset($data['additional_info']) ? $data['additional_info'] : "NA";

					$checksum_str = Config::$merchantId."|REFUND|".$data['timestamp']."|".$data['tran_ref_no']."|".$data['txn_amount']."|".$data['org_jm_tran_ref_no']."|".$data['org_txn_timestamp']."|".$data['additional_info'];

					break;

				case 'STATUSQUERY':
					/* CHECKSUM GENRATION FORMAT:
					* CLIENT_ID|MERCHANT_ID|APINAME|TRAN_REF_NO
					* TRAN_REF_NO -> Transaction extref number provided by the merchant during the purchase transaction
					*/
					$checksum_str = Config::$clientId."|".Config::$merchantId."|STATUSQUERY|".$data['tran_ref_no'];

					break;

				case 'CHECKPAYMENTSTATUS':
					/* CHECKSUM GENRATION FORMAT:
					* CHECKPAYMENTSTATUS|timestamp|mid
					*/					
					$checksum_str = "CHECKPAYMENTSTATUS|".$data['timestamp']."|".Config::$merchantId;
					break;

				case 'GETREQUESTSTATUS':
					/* CHECKSUM GENRATION FORMAT:
					* APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID
					*/
					$checksum_str = "GETREQUESTSTATUS~".$data['mode']."~".$data['requestid']."~NA~NA~".Config::$merchantId."~".$data['tranid'];	
					break;
				
				case 'GETMDR':
					/* CHECKSUM GENRATION FORMAT:
					* APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID
					*/
					$checksum_str = "GETMDR~".$data['mode']."~".$data['requestid']."~NA~NA~".Config::$merchantId."~".$data['tranid'];	
					break;

				case 'GETTRANSACTIONDETAILS':
					/* CHECKSUM GENRATION FORMAT:
					* APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID
					*/				
					$checksum_str = "GETTRANSACTIONDETAILS~".$data['mode']."~".$data['requestid']."~NA~NA~".Config::$merchantId."~".$data['tranid'];	
					break;

				case 'FETCHTRANSACTIONPERIOD':
					/* CHECKSUM FETCHTRANSACTIONPERIOD FORMAT:
					* APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID
					*/
					$checksum_str = "FETCHTRANSACTIONPERIOD~".$data['mode']."~".$data['requestid']."~".$data['startdate']."~".$data['enddate']."~".Config::$merchantId."~NA";	
					break;	

				case 'GETTODAYSDATA':
					/* CHECKSUM FETCHTRANSACTIONPERIOD FORMAT:
					* APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID
					*/
					$checksum_str = "GETTODAYSDATA~".$data['mode']."~".$data['requestid']."~NA~NA~".Config::$merchantId."~NA";	
					break;
				default:
					# code...
					break;
			}		
			$checksum = hash_hmac('SHA256',$checksum_str, Config::$seed);
		}else{

			return "Invalid Type";
			exit;
		}	
		return $checksum;

	}

	/**
	* Function Name: curlRequest
	* Description: Make curl call
	* Parameters : $data = data to pass,$url = API URL, $mode = xml or json
	* Return : curl response String
	*/

	public function curlRequest($data,$url,$mode,$version = ""){
		
		// Mode is used to get in what format response is required. For XML 1 , for JSON 2
		if($mode == '1'){
			$mode = 'xml';
		}else{
			$mode = 'json';
		}
		//Write transaction request Log		
		$this->log_transactions($data);

		$headers = array();
		$headers[] = 'Accept: application/'.$mode;
		$headers[] = 'Content-Type: application/'.$mode;
		
		if(!empty($version)){
			$headers[] = 'APIVer:'.$version;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // output to variable		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		$result = curl_exec($ch);
		$info = curl_error($ch);
		curl_close($ch);
		
		if($result == false){		
			echo "CURL ERROR: ".$info;		
		}else{
			//Write transaction response Log
			$this->log_transactions($result);
			return $result;
		}	
	}

	/**
	* Function Name: log_transactions
	* Description: Log all transaction request and response into date wise text files. 
	* Parameters : String (Request and Response)
	* Return : 
	*/

	private function log_transactions($value){
		
		$year = date('Y');
		$month = date('M');
		$day = date('m-d-y')."logs.txt";
		
		$directoryName = './lib/logs/'.$year.'/'.$month.'/';
		$filePath = './lib/logs/'.$year.'/'.$month.'/'.$day;

		if(!file_exists($filePath)){
		 	
		 	if(!is_dir($directoryName)){
			    //Directory does not exist, so lets create it.
			    mkdir($directoryName, 0755, true);
			}	 	
		}
		//Write log into files
		file_put_contents($filePath, $value . PHP_EOL, FILE_APPEND);

	}


}








?>