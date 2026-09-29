<?php
	include('../dbcon.php');

		$rid     		= $_POST['rid'];

		$payment_hdr_id 	= $_POST['payment_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_name 		= $_POST['account_name'];
		$against_invoice    = $_POST['against_invoice'];
        $invoice_number     = $_POST['invoice_number'];
		$debit_credit    	= $_POST['debit_credit'];
		$amount    			= $_POST['amount'];
		$remarks 			= $_POST['remarks'];
		
		
		$sql = "update `payment_details` set account_type		= '$account_type',
				account_name 		= '$account_name',
				against_invoice    	= '$against_invoice',
				invoice_number     	= '$invoice_number',
				debit_credit    	= '$debit_credit',
				amount    			= '$amount',
				remarks 			= '$remarks'
				where payment_hdr_id = '$payment_hdr_id' and id = '$rid' ";

		$r2 = mysqli_query($con, $sql);
	
//echo $sql;
//exit();

//	echo "<script>window.location.reload();</script>";
	echo "<meta http-equiv='refresh' content='0'>";    
	echo "<script>window.location.href='edit.php?id=$payment_hdr_id&active=active&987';</script>";

?>
