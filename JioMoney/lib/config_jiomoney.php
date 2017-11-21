<?php

class Config{

	public static $clientId 			= "";
	public static $merchantId 			= "";
	public static $seed 				= "";
	public static $purchaseResponseUrl	= "";
	// 1 = Testing & 2 = Production
	public static $environment 			= "";


	public static $arrUrl = Array();




	public function __construct($clientId,$merchnatId,$seed,$environment){

		$this->setClientId($clientId);
		$this->setMerchnatId($merchnatId);
		$this->setSeed($seed);
		$this->setEnvironment($environment);
	}

	private function setClientId($cId){

		self::$clientId = $cId;
	}

	private function setMerchnatId($mId){

		self::$merchantId = $mId;
	}

	private function setSeed($seed){

		self::$seed = $seed;		
	}

	private function setEnvironment($env){

		self::$environment = $env;

		self::setUrl();
	}

	public static function setUrl(){
		
		if(self::$environment == 2){
			self::$arrUrl = Array(
					"purchase_url"	=> "https://pp2pay.jiomoney.com/reliance-webpay/v1.0/jiopayments",
					"refund_url"	=> "https://pp2pay.jiomoney.com/reliance-webpay/v1.0/payment/apis",
					"statusquery_url"	=> "https://pp2pay.jiomoney.com/reliance-webpay/v1.0/payment/status",
					"nonpaymentapi_url"	=> "https://pp2bill.jiomoney.com:8443/Services/TransactionInquiry"
				);

		}else{
			self::$arrUrl = Array(
					"purchase_url"	=> "https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments",
					"refund_url"	=> "https://testpg.rpay.co.in/reliance-webpay/v1.0/payment/apis",
					"statusquery_url"	=> "https://testpg.rpay.co.in/reliance-webpay/v1.0/payment/status",
					"nonpaymentapi_url"	=> "https://testbill.rpay.co.in:8443/Services/TransactionInquiry"
				);
				
		}

	}
	

}

?>