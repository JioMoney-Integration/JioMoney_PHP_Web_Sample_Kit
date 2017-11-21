# JioMoney_PHP_Web_Sample_Kit

This is JioMoney’s PHP based sample library for integration of redirection based Payment Gateway/ Wallet</br>
# Installation :

<pre><b>• STEP1:</b> Copy JioMoney Lib inside your project.
  
<b>• STEP2:</b> Now add provided credentials (like MID,CID & SEED) into lib/config_jiomoney.php file or set during Runtime.
  
<b>• STEP3:</b> To use JioMoney API include JioMoney.php and add use JioMoney\lib\JioMoney;
  
<b>• STEP4:</b> Create object by new JioMoney() or JioMoney::setCredentials(ClientId,MerchnatId,seed,env);
  
<b>• STEP5:</b> Now you can call specified functions with required parameters</br></pre>

# Initiating payment request :</br>

<pre>require 'lib\JioMoney.php';</br>
$call = new JioMoney(); OR $call = JioMoney::setCredentials($clientId,$merchnatId,$seed,$env);
</pre>

<pre>$call->purchase(array('transaction.extref' => 'Purchase01',,'channel' => 'WEB','version' => '2.0','transaction.amount' => '1.00', 'subscriber.customerid'=>'CUST_01','subscriber.customername'=>'demo_name','subscriber.mobilenumber'=>'9812345678','returl'=>'http://return_URL'));</br></pre>     

# Initiating refund request :

<pre>$response = $call->api(array('api_name'=> 'REFUND','timestamp' => date('YmdHis'),'tran_ref_no' => 'Refund01','txn_amount' => '1.00','org_jm_tran_ref_no'=>'Jiomoney_ref_no','org_txn_timestamp'=>'Jiomoney_timestamp','mode'=>'2','additional_info' => 'optional'));</pre>

# Check transaction status :

<pre>$call->api(array('api_name'=> 'CHECKPAYMENTSTATUS','tranid'=>'Purchase1k2sai','mode'=>'2','timestamp' => date('YmdHis'), 'requestid'=>$requestId,'version'=>'3.0'));</pre>

# Other APIs:
<pre>
// To generate uuid format request id
$requestId = $call->gen_uuid();
</pre>

<pre>
$response = $call->api(array('api_name'=> 'GETTRANSACTIONDETAILS','tranid'=>'901033422981','mode'=>'2', 'requestid'=>$requestId));
</pre>
