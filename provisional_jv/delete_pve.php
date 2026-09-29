<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$modulePath = "provisional_jv/";

$record_id 				= $_GET['record_id'];
$provisional_jv_hdr_id  = $_GET['provisional_jv_hdr_id'];

$sql = " DELETE from sma_provisional_jv_details where id = " . $_GET['record_id'];
mysqli_query($con, $sql);
			
//echo $sql; exit();

	//echo "<script type='text/javascript'> document.location = '" . $_GET['url'].'&IN=in' . "'; </script>";
	$value = "<script>window.location.href='edit.php?sub=edit&id=$provisional_jv_hdr_id&IN=in';</script>";
	echo $value;
	
/* 		$file = fopen("ravitest.txt","w");
		fwrite($file,$sql);
		fclose($file);
echo $sql; exit();
		
 */

?>