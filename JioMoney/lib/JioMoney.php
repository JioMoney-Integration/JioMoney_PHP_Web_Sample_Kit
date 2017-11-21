<?php

require_once 'config_jiomoney.php';
require_once 'purchase.php';
require_once 'Api.php';

class JioMoney{

	var $purchae_obj;
	var $api_obj;

    public function __construct() { 

    	$this->purchae_obj = new Purchase();    	
    	$this->api_obj = new Api();

    }

    //To set credentials 
    public static function setCredentials($clientId,$merchnatId,$seed,$env){
    	$instance = new self();
    	$obj = new config($clientId,$merchnatId,$seed,$env);
    	return $instance;
    }


	public function purchase($purchase_array){
		
		$this->purchae_obj->create($purchase_array);
	}

	public function api($request_array){
	
		return $this->api_obj->allApis($request_array);

	}

	public function gen_uuid(){

		return $this->api_obj->gen_uuid();
	}
}


?>