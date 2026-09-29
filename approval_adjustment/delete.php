<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../header.php");
$modulePath = "approval"; 
include("../dbcon.php");

//$modulePath = "approval/";
$did = $_GET['did'];
//$sql = "DELETE FROM sma_approval_memo WHERE id = " .$did;

$sql = "UPDATE sma_approval_memo set del = 'Y' WHERE id = " .$did . " and id not in ( SELECT approval_memo_ref FROM `sma_purchase_order` where approval_memo_ref = '$did' ) ";

//echo $sql;
//exit();

if(mysqli_query($con, $sql)){
	$sql = "DELETE FROM sma_approval_details WHERE approval_hdr_id = " .$did;
	mysqli_query($con, $sql);
	$sql = "DELETE FROM file_uploads WHERE id = " . $did;
	mysqli_query($con, $sql);
	echo "<script type='text/javascript'> document.location = '" . $baseurl.$modulePath . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
?>