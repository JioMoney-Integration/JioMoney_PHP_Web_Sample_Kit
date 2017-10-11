<?php

namespace Purchase\lib;
use common_jiomoney\lib\Common_jiomoney as Base;

require_once 'config_jiomoney.php';
require_once 'common_jiomoney.php';

class Purchase extends Base{
	
	/**
	* Function Name: create
	* Description: Submit the form automatically and gives response to return URL
	* Parameter : Array for purchase request
	* Return : Response to be sent to return URL 
	*/
	public function create($purchase_array){
		
		//Validate purchase request
		$valid = Base::validatePurchaseRequest($purchase_array);
		
		if($valid['flag']){
			//Gather all the required required details
			$purchase_array = $this->gatherPostData($purchase_array);
	?>
			<!--Submit the form as POST from backend-->
			<html>
				<head>
					<title>Check Out Page</title>
				</head>
				<body>
					<center><h1>Please do not refresh this page...</h1></center>
						<form method="post" action="<?php echo PURCHASE_URL ?>" name="redirectfrm">
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
		$value['merchantid'] 			= MERCHANT_ID;
		$value['clientid'] 				= CLIENT_ID;
		$value['channel'] 				= "WEB";
		$value['version']				= VERSION;
		$value['token'] 				= "";
		$value['transaction.txntype'] 	= "PURCHASE";
		$value['transaction.currency'] 	= "INR";
		$timestamp = date('YmdHis');
		$value['transaction.timestamp']	= $timestamp;
		
		$value['returl'] 				= !empty($value['returl']) ? $value['returl'] : PURCHASE_RESPONSE_URL;

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
		$checksum = Base::genrateChecksum('PURCHASE', $value, $udf_array);

		$value['checksum'] = $checksum;

		return $value;
	}

}

?>