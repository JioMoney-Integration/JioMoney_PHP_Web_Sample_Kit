<?php

/*

- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- 
Change the value of PAYTM_MERCHANT_KEY constant with details received from JioMoney.
- 
Change the value of PAYTM_MERCHANT_MID constant with details received from JioMoney.

- 
Above details will be different for testing and production environment.

*/


//Change this constant value with Merchant ID received from JioMoney
define('MERCHANT_ID', '');

//Change this constant value with Client ID received from JioMoney
define('CLIENT_ID', '');

//Change this constant value with Seed received from JioMoney
define('CHECKSUM_SEED', '');

define('ENVIRONMENT', 'TEST');



//JioMoney API Version 
define('VERSION', '2.0');

define('API_VERSION', '2.0');

define('CHANNEL', 'WEB');

//Change this constant value respective of environment (e.g. Pre-production or production) received from JioMoney
define('PURCHASE_URL','https://testpg.rpay.co.in/reliance-webpay/v1.0/jiopayments');

define('REFUND_URL', 'https://testpg.rpay.co.in/reliance-webpay/v1.0/payment/apis');

//Purchase return URL needs to be configured
define('PURCHASE_RESPONSE_URL','http://localhost/PHP_V2/response.php');


define('STATUSQUERY_URL','https://testpg.rpay.co.in/reliance-webpay/v1.0/payment/status');

//JioMoney Non-Payments APIs can be used for server to server interactions between Merchant’s systems and JioMoney backend.
define('NON_PAYMENT_API_URL','https://testbill.rpay.co.in:8443/Services/TransactionInquiry')


?>