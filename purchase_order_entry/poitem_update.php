<?php
	include('../dbcon.php');

		$rid     		= $_POST['rid'];
		$purchase_id 	= $_POST['purchase_id'];
		$product_id		= $_POST['product_id'];
		$categoryid		= $_POST['categoryid'];
		$description 	= $_POST['itemdescription'];
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		$quantity 		= $_POST['itemquantity'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		$amount 		= $_POST['itemamount'];
		$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));
//echo $product_id. ' <- product ID ';		
		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name = $r1['name'];
		
		$sql = "update `sma_po_items` set product_id='$product_id', product_name='$product_name', product_category = '$categoryid',
					product_desc='$description', 
					account_year   = '$account_year',
					company_id     = '$company_id',
					budget_name    = '$budget_name',
					budget_head    = '$budget_head',
					quantity='$quantity', uom='$units',
					unit_rate='$rate', gst='$gst', delivery_date='$deliverydate' 
				where purchase_id = '$purchase_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
	
//echo $sql;
//exit();
	
	echo "<meta http-equiv='refresh' content='0'>";    

	echo "<script>window.location.href='edit.php?id=$purchase_id&active=active&888';</script>";
	//echo "<script>window.location.href='purchase_order_entry.php?sub=edit&id=$purchase_id&active=active&888';</script>";

		
?>