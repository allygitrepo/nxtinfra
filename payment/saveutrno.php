<?php

require_once("../dbcon.php");

	//$utrno_val 	= $_POST["utr_no"];
	$pay_id 	= $_POST["pay_id"];
	$utr_val  	= $_POST["editval"];
	$column		= $_POST["column"];
	
	$sql = " SELECT * FROM `payment_header` where id = $pay_id  ";
	$result = mysqli_query($con,$sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$rowcount=mysqli_num_rows( $result);
	
//$file = fopen("ravitest.txt","a");
//fwrite($file,$rowcount);
//fclose($file);
	
	/* if($rowcount>0){
		$sql = "UPDATE `payment_header` set " . $column . " = '" . $utr_val . "' WHERE id = " . $_POST['pay_id'];
		$result = mysqli_query($con, $sql);
	}
 */
 
	$sqlutr = '';
	if($column=='utr_no'){
		if(!empty($utr_val)){
			$sqlutr = " , utr_upd_flag ='Y', utr_upd_date = now() ";
		}
		else {
			$sqlutr = ", utr_upd_flag ='', utr_upd_date = '' ";
		}	
	}
	if($column=='paid_date'){
		$utr_val = date('Y-m-d', strtotime($utr_val));
	}
	
	if($rowcount>0){
		$sql = "UPDATE `payment_header` set " . $column . " = '" . $utr_val ."'" . $sqlutr . " WHERE id = " . $_POST['pay_id'];
		$result = mysqli_query($con, $sql);
	}
	
	/* 
$file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file);
	 */
//	echo $sql;
	
	//$val=$_POST["editval"];
	//if($val>00){echo "<h1>Value should not be more than 100!!!!!</h1><br>Please try again.";}

?>