<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$modulePath = "income/";

$record_id 				= $_GET['record_id'];
$income_hdr_id  = $_GET['income_hdr_id'];

$sql = " DELETE from sma_income_dtl where id = " . $_GET['record_id'];
mysqli_query($con, $sql);
			
//echo $sql; exit();

	//echo "<script type='text/javascript'> document.location = '" . $_GET['url'].'&IN=in' . "'; </script>";
	$value = "<script>window.location.href='edit.php?sub=edit&id=$income_hdr_id&IN=in';</script>";
	echo $value;
	
/* 		$file = fopen("ravitest.txt","w");
		fwrite($file,$sql);
		fclose($file);
echo $sql; exit();
		
 */

?>