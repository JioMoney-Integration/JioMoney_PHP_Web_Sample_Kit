<?php

namespace JioMoney\lib;

require 'purchase.php';
require 'Api.php';

use Purchase\lib\Purchase;
use Api\lib\Api;

class JioMoney{

	var $purchae_obj;
	var $api_obj;

    public function __construct() { 

    	$this->purchae_obj = new Purchase();    	
    	$this->api_obj = new Api();

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