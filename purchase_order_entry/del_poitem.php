<?php

include("../dbcon.php");

$po_no = $_GET['po_no'];
$id_no = $_GET['id_no'];

$modulePath = "edit.php?id=".$po_no."&999";

	$sql="SELECT * from sma_po_items where purchase_id = '$po_no' and id = '$id_no' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);	
	$row = mysqli_fetch_array($result);
	
	$purchase_id	= $row['purchase_id'];	
	$product_id 	= $row['product_id'];
	$qty 			= $row['quantity'];
	$budget_head	= $row['budget_head'];
	$budget_id		= $row['budget_id'];
												
	$rate 			= $row['unit_rate'];
	$gst			= $row['gst'];
	
	$sql="SELECT * FROM sma_product where id = '$product_id' ";
	$res2 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$mat = mysqli_fetch_array($res2);
	$category 			= $mat['category'];
	if($category =='S'){
		$sql = "UPDATE sma_purchase_req_items SET po_quantity = $qty , 
					po_value = po_value - (( $qty * rate ) + (( ($qty * rate ) * $igst ) / 100) )
					WHERE id = '$pr_item_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
	}
	else if($category =='M'){
		$sql = "UPDATE sma_purchase_req_items SET po_quantity = po_quantity - $qty , 
					po_value = po_value - (( $qty * rate ) + (( ($qty * rate ) * $igst ) / 100) )
					WHERE id = '$pr_item_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);	
	}
				
	$sql = "DELETE FROM sma_po_items WHERE purchase_id = '$po_no' and id = '$id_no' ";
	mysqli_query($con, $sql);
	
	echo "<script type='text/javascript'> document.location = '" . $modulePath . "'; </script>";
	
	exit();
	
?>