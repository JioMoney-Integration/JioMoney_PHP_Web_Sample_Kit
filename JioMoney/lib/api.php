<?php

require_once 'config_jiomoney.php';
require_once 'common_jiomoney.php';

class api extends Common_jiomoney{

	/**
	* Function Name: allApis
	* Description: Provide 
	* parameter : array
	* Return : API response as string
	*/
	public function allApis($data){

		//Validate API name provided by user
		$validate_errors = Common_jiomoney::validateApiRequest($data);		
		
		if($validate_errors['flag']){

			//function to calculate checksum 
			$data['checksum'] = Common_jiomoney::genrateChecksum($data['api_name'], $data);
			
			switch (strtolower($data['api_name'])) {
				case 'statusquery': 
						// Create an request having all required parameters for Refund.
						$request_data = $this->statusquery($data);

						$url = Config::$arrUrl['statusquery_url'];

						break;
				case 'refund' : 
						// Create an request having all required parameters for Refund.
						$request_data = $this->refund($data);

						$url = Config::$arrUrl['refund_url'];

						break;
				case 'checkpaymentstatus' :									
						// Create an request having all required parameters for CHECKPAYMENTSTATUS.
						$request_data = $this->checkpaymentstatus($data);

						$url = Config::$arrUrl['nonpaymentapi_url'];

						break;
				default: 
						$data['startdate'] = !empty($data['startdate']) ? $data['startdate'] : "NA";
						$data['enddate'] = !empty($data['enddate']) ? $data['enddate'] : "NA";
						
						if($data['api_name'] == 'FETCHTRANSACTIONPERIOD' || $data['api_name'] == 'GETTODAYSDATA'){					
							$data['tranid'] = !empty($data['tranid']) ? $data['tranid'] : "NA";
						}					

						//APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID~CHECKSUM
						$request_data = $data['api_name']."~".$data['mode']."~".$data['requestid']."~".$data['startdate']."~".$data['enddate']."~".Config::$merchantId."~".$data['tranid']."~".$data['checksum'];
												
						$url = Config::$arrUrl['nonpaymentapi_url'];

						break;
			}
			$version = isset($data['version'])? $data['version'] : ""; 
			return Common_jiomoney::curlRequest($request_data,$url,$data['mode'],$version);
		
		}else{

			return $validate_errors;
		}
	}

	/**
	* Function Name: gen_uuid
	* Description: Generate unique uuids 
	* parameter : 
	* Return : uuid as string
	*/	
	public function gen_uuid() {
		
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				        // 32 bits for "time_low"
				        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

				        // 16 bits for "time_mid"
				        mt_rand( 0, 0xffff ),

				        // 16 bits for "time_hi_and_version",
				        // four most significant bits holds version number 4
				        mt_rand( 0, 0x0fff ) | 0x4000,

				        // 16 bits, 8 bits for "clk_seq_hi_res",
				        // 8 bits for "clk_seq_low",
				        // two most significant bits holds zero and one for variant DCE1.1
				        mt_rand( 0, 0x3fff ) | 0x8000,

				        // 48 bits for "node"
				        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
					);
	}

	/**
	* Function Name: statusquery
	* Description: To generate statusquery request 
	* parameter : array
	* Return : statusquery request as string
	*/
	private function statusquery($statusquery_data){
		// Create JSON request.
		if($statusquery_data['mode'] == '2'){
				$request_header = array("version" => "1.0",
										"api_name" => "STATUSQUERY"
										);
				$payload_data = array("client_id" => Config::$clientId,
										"merchant_id" =>  Config::$merchantId,
										"tran_ref_no" => $statusquery_data['tran_ref_no']
									);
				$request_data = array("request_header" => $request_header,
										"payload_data" => $payload_data,
										"checksum"	=>	$statusquery_data['checksum']
									);
				$request_data = json_encode($request_data, JSON_UNESCAPED_SLASHES);
							
		}else{
				/* Create XML request data to POST*/
				$request_data = '<REQUEST> <REQUEST_HEADER> <VERSION>1.0</VERSION> <API_NAME>STATUSQUERY</API_NAME> </REQUEST_HEADER> <PAYLOAD_DATA> <CLIENT_ID>'.Config::$clientId.'</CLIENT_ID> <MERCHANT_ID>'.Config::$merchnatId.'</MERCHANT_ID> <TRAN_REF_NO>'.$statusquery_data["tran_ref_no"].'</TRAN_REF_NO> </PAYLOAD_DATA> <CHECKSUM>'.$statusquery_data['checksum'].'</CHECKSUM> </REQUEST>';				

		}
		return $request_data;
	}

	/**
	* Function Name: refund
	* Description: To generate refund request 
	* parameter : array
	* Return : refund request as string
	*/
	private function refund($refund_data){
		
		//Optional parameter
		$refund_data['additional_info'] = isset($refund_data['additional_info']) ? $refund_data['additional_info'] : "NA";

		//For XML 1 , for JSON 2
		if($refund_data['mode'] == '2'){
			$request_header = array(
								"api_name" 	=> "REFUND",
								"version"	=> "2.0",
								"timestamp"	=> $refund_data['timestamp']
							);

			$payload_data = array(
								"merchant_id"			=> Config::$merchantId,
								"tran_ref_no" 			=> $refund_data['tran_ref_no'],
								"txn_amount"			=> $refund_data['txn_amount'],
								"org_jm_tran_ref_no"	=> $refund_data['org_jm_tran_ref_no'],
								"org_txn_timestamp"		=> $refund_data['org_txn_timestamp'],
								"additional_info"		=> $refund_data['additional_info']
			);

			$data = array(
						'request' => array(
						'request_header' 	=> $request_header,
						'payload_data'		=> $payload_data,
						'checksum'			=> $refund_data['checksum']
					)	
			);

			$request_data = json_encode($data, JSON_UNESCAPED_SLASHES);

		}else{

			$request_header = "<request>
									<request_header>
										<api_name>REFUND</api_name>
										<version>2.0</version>
										<timestamp>".$refund_data['timestamp']."</timestamp>
									</request_header>
									<payload_data>
										<merchant_id>".Config::$merchnatId."</merchant_id>
										<tran_ref_no>".$refund_data['tran_ref_no']."</tran_ref_no>
										<txn_amount>".$refund_data['txn_amount']."</txn_amount>
										<org_jm_tran_ref_no>".$refund_data['org_jm_tran_ref_no']."</org_jm_tran_ref_no>
										<org_txn_timestamp>".$refund_data['org_txn_timestamp']."</org_txn_timestamp>
										<additional_info>".$refund_data['additional_info']."</additional_info>
									</payload_data>
									<checksum>".$data['checksum']."</checksum>
							</request>";
		}
		return $request_data;
	}
	
	/**
	* Function Name: checkpaymentstatus
	* Description: To generate checkpaymentstatus request 
	* parameter : array
	* Return : checkpaymentstatus request as string
	*/
	private function checkpaymentstatus($cps_data){
		
		//For XML 1 , for JSON 2
		if($cps_data['mode'] == '2'){
			$request_header = array(
								"request_id" 	=> $cps_data['requestid'],
								"api_name"	=> "CHECKPAYMENTSTATUS",
								"timestamp"	=> $cps_data['timestamp']
							);

			$payload_data = array(
								"mid"			=> Config::$merchantId,
								"tran_details" 	=> array("tran_ref_no"	=> array($cps_data['tranid'])),
								"txntimestamp"			=> !empty($cps_data['txntimestamp'])? $cps_data['txntimestamp'] : ""
			);

			$data = array(
						'request' => array(
						'request_header' 	=> $request_header,
						'payload_data'		=> $payload_data,
						'checksum'			=> $cps_data['checksum']
					)	
			);
			
			$request_data = json_encode($data, JSON_UNESCAPED_SLASHES);
			
		}else{

			$request_header = "<request>
								<request_header>
									<request_id>".$cps_data['requestid']."</request_id>
									<api_name>CHECKPAYMENTSTATUS</api_name>
									<timestamp>".$cps_data['timestamp']."</timestamp>
								</request_header>
								<payload_data>
									<mid>".Config::$merchantId."</mid>
									<tran_details>
										<tran_ref_no>".$cps_data['tranid']."</tran_ref_no>
									</tran_details>
									<txntimestamp>".$cps_data['txntimestamp']."</txntimestamp>
								</payload_data>
								<checksum>".$cps_data['checksum']."</checksum>
							</request>";
		}
		return $request_data;
	}

}




?>