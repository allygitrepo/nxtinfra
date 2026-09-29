<?php
	include('../dbcon.php');

		$rid     		= $_POST['rid'];
		$si_hdr_id 		= $_POST['si_hdr_id'];
		$material_id	= $_POST['material_id'];
		$grn_no			= $_POST['grn_no'];
		$description 	= $_POST['itemdescription'];
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		
		$quantity 		= $_POST['itemqty'];
		
		$quantity_p		= $_POST['itemqty_p'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		
		$amount = $quantity * $rate + (($quantity * $rate) * $gst / 100);
//echo $si_hdr_id. ' ' . $amount;
//exit();													
		//$amount 		= $_POST['itemamount'];
//		$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));

		$sql = "select * from sma_product where id = '$material_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$material_name = $r1['name'];
		
		$sql = "update `sma_supplier_invoice_details` set material_id = '$material_id', 
						grn_no			= '$grn_no',
						material_name = '$material_name', 
						description   = '$description',
						account_year   = '$account_year',
						company_id     = '$company_id',
						budget_name    = '$budget_name',
						budget_head    = '$budget_head',
						qty			  = '$quantity', 
						unit		  = '$units',
						rate		  = '$rate', 
						amount		  = '$amount', 
						gst			  = '$gst'
				where si_hdr_id = '$si_hdr_id' and si_srno = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
		
// HERE UPDATE PO ITEM QUANTITY AS WELL.
		$sql = "update sma_po_items set bal_si_qty =  bal_si_qty + '$quantity_p' where purchase_id = '$grn_no' and product_id = '$material_id' ";
		$r2  = mysqli_query($con, $sql);

	$amount = 0;
	
	$sql = "select * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id'";
//echo $sql;	
	$r2 = mysqli_query($con, $sql);
	while($r1 = mysqli_fetch_array($r2)){
		$amount = $amount + $r1['amount'];
	}
	
	if($amount > 0){
		$sql = " update `sma_supplier_invoice` set total_amount = '$amount', bal_amount = '$amount' where id = '$si_hdr_id' ";
		$r2 = mysqli_query($con, $sql);
//	echo $sql;
	}
	
//echo $sql;
//exit();

//	echo "<script>window.location.reload();</script>";
	echo "<meta http-equiv='refresh' content='0'>";    
	echo "<script>window.location.href='supplier_invoice.php?sub=edit&id=$si_hdr_id&active=active&987';</script>";

?>
