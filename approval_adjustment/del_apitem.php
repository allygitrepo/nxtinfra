<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$ap_id = $_POST['ap_id'];
$id_no = $_POST['id_no'];
//purchase_order/edit.php?id=419&active=active&999
//$modulePath = "purchase_order/edit.php?sub=edit&id=$po_no#tab_2";
$modulePath = "edit.php?id=".$ap_id."&active2=active&999";

$sql = "SELECT * FROM sma_approval_memo WHERE id  = '$ap_id' "; //
$qry = mysqli_query($con, $sql);
$r2 = mysqli_fetch_array($qry);
$company_id 		= $r2['company'];

$sql = "SELECT * FROM sma_approval_items WHERE approval_hdr_id = '$ap_id' and id = '$id_no' "; //
$qry = mysqli_query($con, $sql);
$r2 = mysqli_fetch_array($qry);
$quantity 		= $r2['quantity'];
$rate 			= $r2['unit_rate'];
$gst 			= $r2['gst'];
$budget_id		= $r2['budget_id'];

	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q3  = mysqli_query($con, $sql);
	$r3  = mysqli_fetch_array($q3);
	$budget_control_gst = $r3['budget_control_gst'];
		
	if($budget_control_gst =='Y'){		
		$amount = round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
	}
	else if($budget_control_gst =='N'){		
		$amount = round(($quantity	* $rate ),0) ;
	}
$sql = "DELETE FROM sma_approval_items WHERE approval_hdr_id = '$ap_id' and id = '$id_no' "; //

if(mysqli_query($con, $sql)){
	
	$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
	mysqli_query($con, $sql);
		
	echo "<script>window.location.href='$modulePath';</script>";
	echo "<script type='text/javascript'> document.location = '" . $modulePath . "'; </script>";
	die();
	
}
else {
	echo mysqli_error($con);
}

//exit();

/* $file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file);
 */
//echo "<script>location.reload();</script>";

?>