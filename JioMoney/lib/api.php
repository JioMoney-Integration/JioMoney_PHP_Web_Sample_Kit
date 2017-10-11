<?php

namespace Api\lib;
use common_jiomoney\lib\Common_jiomoney as Base;

require_once 'config_jiomoney.php';
require_once 'common_jiomoney.php';

class api extends Base{

	/**
	* Function Name: allApis
	* Description: Provide 
	* parameter : array
	* Return : API response as string
	*/
	public function allApis($data){

		//Validate API name provided by user
		$validate_errors = Base::validateApiRequest($data);		
		
		if($validate_errors['flag']){

			//function to calculate checksum 
			$data['checksum'] = Base::genrateChecksum($data['api_name'], $data);

			switch (strtolower($data['api_name'])) {
				case 'statusquery': 
						// Create an request having all required parameters for Refund.
						$request_data = $this->statusquery($data);

						$url = STATUSQUERY_URL;

						break;
				case 'refund' : 
						// Create an request having all required parameters for Refund.
						$request_data = $this->refund($data);

						$url = REFUND_URL;

						break;
				default: 
						$data['startdate'] = isset($data['startdate']) ? $data['startdate'] : "NA";
						$data['enddate'] = isset($data['enddate']) ? $data['enddate'] : "NA";
						
						if($data['api_name'] == 'FETCHTRANSACTIONPERIOD' || $data['api_name'] == 'GETTODAYSDATA'){					
							$data['tranid'] = isset($data['tranid']) ? $data['tranid'] : "NA";
						}					

						//APINAME~MODE~REQUESTID~STARTDATETIME~ENDDATETIME~MID~TRANID~CHECKSUM
						$request_data = $data['api_name']."~".$data['mode']."~".$data['requestid']."~".$data['startdate']."~".$data['enddate']."~".MERCHANT_ID."~".$data['tranid']."~".$data['checksum'];
												
						$url = NON_PAYMENT_API_URL;

						break;
			}			
			return Base::curlRequest($request_data,$url,$data['mode']);
		
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
				$request_header = array("version" => API_VERSION,
										"api_name" => "STATUSQUERY"
										);
				$payload_data = array("client_id" => CLIENT_ID,
										"merchant_id" =>  MERCHANT_ID,
										"tran_ref_no" => $statusquery_data['tran_ref_no']
									);
				$request_data = array("request_header" => $request_header,
										"payload_data" => $payload_data,
										"checksum"	=>	$statusquery_data['checksum']
									);
				$request_data = json_encode($request_data, JSON_UNESCAPED_SLASHES);
							
		}else{
				/* Create XML request data to POST*/
				$request_data = '<REQUEST> <REQUEST_HEADER> <VERSION>'.API_VERSION.'</VERSION> <API_NAME>STATUSQUERY</API_NAME> </REQUEST_HEADER> <PAYLOAD_DATA> <CLIENT_ID>'.CLIENT_ID.'</CLIENT_ID> <MERCHANT_ID>'.MERCHANT_ID.'</MERCHANT_ID> <TRAN_REF_NO>'.$statusquery_data["tran_ref_no"].'</TRAN_REF_NO> </PAYLOAD_DATA> <CHECKSUM>'.$statusquery_data['checksum'].'</CHECKSUM> </REQUEST>';				

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
								"merchant_id"			=> MERCHANT_ID,
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
										<merchant_id>".MERCHANT_ID."</merchant_id>
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


}




?>