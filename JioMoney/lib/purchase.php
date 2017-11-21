<?php

require_once 'config_jiomoney.php';
require_once 'common_jiomoney.php';

class Purchase extends Common_jiomoney{
	
	/**
	* Function Name: create
	* Description: Submit the form automatically and gives response to return URL
	* Parameter : Array for purchase request
	* Return : Response to be sent to return URL 
	*/
	public function create($purchase_array){
		
		/*Get and set MID, CID, SEED, ENV into array*/
		$purchase_array['merchantid'] 	= Config::$merchantId;
		$purchase_array['clientid'] 	= Config::$clientId;
		$purchase_array['checksumseed'] = Config::$seed;

		//Validate purchase request
		$valid = Common_jiomoney::validatePurchaseRequest($purchase_array);
		
		if($valid['flag']){
			//Gather all the required required details
			$purchase_array = $this->gatherPostData($purchase_array);
			unset($purchase_array['checksumseed']);
	?>
			<!--Submit the form as POST from backend-->
			<html>
				<head>
					<title>Check Out Page</title>
				</head>
				<body>
					<center><h1>Please do not refresh this page...</h1></center>
						<form method="post" action="<?php echo Config::$arrUrl['purchase_url'] ?>" name="redirectfrm">
						<?php							
							foreach($purchase_array as $name => $value) {
								echo '<input type="hidden" name="' . $name .'" value="' . $value . '"/>';
							}						
						?>
						<script type="text/javascript">
							document.redirectfrm.submit();
						</script>
					</form>
				</body>
			</html>

<?php }else{
			echo "Please resolve below errors: </br>";
			unset($valid ['flag']);
			foreach ($valid as $value) {
				echo "</br>".$value;
			}			
			exit;
		} 
	
	}


	/**
	* Function Name: gatherPostData
	* Description: Arrange data as per form POST for request 
	* Parameter : Array of purchase data
	* Return : Array of arranged data as per form POST
	*/
	private function gatherPostData($value)
	{	
		$value['merchantid'] 			= Config::$merchantId;
		$value['clientid'] 				= Config::$clientId;		
		$value['token'] 				= "";
		$value['transaction.txntype'] 	= "PURCHASE";
		$value['transaction.currency'] 	= "INR";
		$timestamp = date('YmdHis');
		$value['transaction.timestamp']	= $timestamp;
		
		$value['returl'] 				= !empty($value['returl']) ? $value['returl'] : Config::$purchaseResponseUrl;

		/* Optional Parameter
			To check UDF is present in purchase Request`*/
		$udf_array = Array();
		foreach($value as $k=>$v){
		  if("udf" == substr(strtolower($k),0,3)){
		    	$value[$k] = $v;
		    	$udf_array[$k] = $v;		    	
		  }
		}
		
		//If UDF is present
		$udf_array = array_filter($udf_array);
		if(!empty($udf_array)){
			for($i=1;$i<=5;$i++){
				$value["udf".$i] = isset($value["udf".$i]) ? $value["udf".$i] : "";
			}
		}

		//function to calculate checksum 
		$checksum = Common_jiomoney::genrateChecksum('PURCHASE', $value, $udf_array);

		$value['checksum'] = $checksum;

		return $value;
	}

}

?>