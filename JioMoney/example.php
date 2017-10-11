<?php

	require 'lib\JioMoney.php';

	use JioMoney\lib\JioMoney;


	$call = new JioMoney();
	/**
	* Purchase
	* To perform purchase transactions.
	* Mandatory Fields: transaction.extref, transaction.amount,subscriber.customerid, subscriber.customername,subscriber.mobilenumber, returl
	* optional Fields : Please refer integration document for reaming fields
	* Response will get on returl
	*/

	$call->purchase(array('transaction.extref' => 'Purchase1k2sai', 'transaction.amount' => '1.00', 'subscriber.customername'=>'demo_name','subscriber.mobilenumber'=>'+9812345678','returl'=>'http://Domain_name/path/JioMoney/response.php','productdescription'=>'product1','udf1'=>'udf11','udf2'=>'udf22'));


	/**
	* REFUND API
	*
	* Momentary Fields 	: api_name, ext_ref_number, mode, timestamp, amount, jioextref, jiotimestamp 
	* optional Fields 	: additional_info
	* Used for full refund and partial refund
	*/

	//$response = $call->api(array('api_name'=> 'REFUND','timestamp' => date('YmdHis'),'tran_ref_no' => 'Refundk70pq7WTU','txn_amount' => '1.00','org_jm_tran_ref_no'=>'901033427824','org_txn_timestamp'=>'20170630154243','mode'=>'2','additional_info' => 'optional'));
	
	/**
	* STATUSQUERY API
	* All fields are mandatory 
	*/
	//$response = $call->api(array('api_name'=> 'STATUSQUERY','tran_ref_no'=>'Purchase1kNeee','mode'=>'2'));
	

	/**
	* To generate uuid format request id
	*/
	//$requestId = $call->gen_uuid();


	/**
	* All fields are mandatory
	* CHECKPAYMENTSTATUS
	* This API is to fetch status of a transaction using merchant reference number. This API needs to be called for all the transactions for which you don’t get 
	* Return response from JioMoney.
	*/
	//$response = $call->api(array('api_name'=> 'CHECKPAYMENTSTATUS','tranid'=>'Purchase1kNeee','mode'=>'2', 'requestid'=>$requestId));

	/**
	* All fields are mandatory
	* GETREQUESTSTATUS
	* To check the status of refund requests.
	*/
	//$response = $call->api(array('api_name'=> 'GETREQUESTSTATUS','tranid'=>'901033424012','mode'=>'2', 'requestid'=>$requestId));


	/**
	* All fields are mandatory
	* GETMDR
	* To get MDR details of a transaction
	*/
	//$response = $call->api(array('api_name'=> 'GETMDR','tranid'=>'901033424012','mode'=>'2', 'requestid'=>$requestId));
	

	/**
	* All fields are mandatory
	* GETTRANSACTIONDETAILS
	* To get MDR details of a transaction
	*/
	//$response = $call->api(array('api_name'=> 'GETTRANSACTIONDETAILS','tranid'=>'901033422981','mode'=>'2', 'requestid'=>$requestId));
	

	/**
	* All fields are mandatory
	* FETCHTRANSACTIONPERIOD
	* To fetch all the transactions for a given period
	* Format : yyyyMMddHHmmss
	*/
	//$response = $call->api(array('api_name'=> 'FETCHTRANSACTIONPERIOD','mode'=>'2','startdate'=>'20170615101010','enddate'=>'20170625101010' , 'requestid'=>$requestId));


	/**
	* All fields are mandatory
	* GETTODAYSDATA
	* To get analysis of today’s business
	* Format : yyyyMMddHHmmss
	*/
	//$response = $call->api(array('api_name'=> 'GETTODAYSDATA','mode'=>'2', 'requestid'=>$requestId));


?>