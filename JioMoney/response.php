<?php
	var_dump($_POST['response']);
	$result = explode('|', $_POST['response']);
	print_r($result);

	$year = substr($result[9],0,4);
	$month = substr($result[9],4,2);
	$day = substr($result[9],6,2);
	if($result[0]=='000') {
?>
		<p>Ref No. : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[5];?></p>
		<p>Amount  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[6];?></p>
		<p>Message : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[8];?></p>
		<p>Time    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $day.'-'.$month.'-'.$year;?></p>
		<p>Timestamp    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[9];?></p>
		<p>Card Number    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo 'xxxx xxxx xxxx '.$result[10];?></p>
		<p>Transcation Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[11];?></p>
		<p>Card Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[12];?></p>
<?php
	} else {
?>
		<p>Ref No. : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[5];?></p>
		<p>Amount  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[6];?></p>
		<p>Message : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[8];?></p>
		<p>Time    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $day.'-'.$month.'-'.$year;?></p>
		<p>Timestamp    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[9];?></p>
		<p>Card Number    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[10];?></p>
		<p>Transcation Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[11];?></p>
		<p>Card Type    : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result[12];?></p>
<?php
	}
	exit;
?>

