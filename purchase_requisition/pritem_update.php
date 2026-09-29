<?php
	include('../dbcon.php');

		$rid     		= $_POST['rid'];
		$purchase_req_id 	= $_POST['purchase_req_id'];
		$product_id		= $_POST['product_id'];
		$description 	= str_replace("'","",$_POST['itemdescription']);
		$quantity 		= $_POST['itemquantity'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$amount 		= $_POST['itemamount'];
/*		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name = $r1['name'];
*/		
		$sql = "update `sma_purchase_req_items` set product_id = '$product_id', 
					description 	= '$description', 
					quantity		= '$quantity', 
					unit			= '$units',
					rate			= '$rate'
				where purchase_req_id = '$purchase_req_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
	
//echo $sql;
//exit();
	
	echo "<meta http-equiv='refresh' content='0'>";
	echo "<script>window.location.href='purchase_order.php?sub=edit&id=$purchase_req_id&active=active&888';</script>";
		
?>