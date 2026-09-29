<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "supp_invoice/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$si_id  		= $_POST['si_id'];
				
		$sql = "select * from  sma_supplier_invoice where id='$si_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$sr    = mysqli_fetch_array($query1);
		$company_id 		= $sr['company_id'];
		$our_po_ref_no 		= $sr['our_po_ref_no'];
		$against_po_flag 	= $sr['against_po_flag'];
		
		$sql = "SELECT * FROM `sma_supplier_invoice_details` where si_hdr_id = '$si_id' ";
		$sires = mysqli_query($con, $sql);
		echo 	 mysqli_error($con);
		while($sr    = mysqli_fetch_array($sires)){
			
			$po_item_id			= $sr['po_item_id'];
			$material_id 		= $sr['material_id'];
			$budget_head 		= $sr['budget_head'];
			$budget_id 			= $sr['budget_id'];
			$si_qty 			= $sr['qty'];
			$si_rate 			= $sr['rate'];
			$si_gst 			= $sr['gst'];
			
			$sql = "UPDATE sma_product_open_stock set receipts = receipts - $si_qty where product_name = '$material_id' and project = '$company_id'  ";
//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			$sql="update sma_po_items set bal_si_qty = bal_si_qty - $si_qty
						where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and id = '$po_item_id' 
						and bal_si_qty > 0 ";
			mysqli_query($con, $sql);
			
		}
		
		$sql = "update sma_supplier_invoice_details set qty = 0 where si_hdr_id = '$si_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql = "update sma_supplier_invoice set del = 'Y' where id='$si_id' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		// Update email inbox table if the record is linked
        $sqlEMail = "UPDATE email_inbox SET grn_no='', grn_type='' WHERE grn_no='$si_id' ";         
        $queryEmail = mysqli_query($con, $sqlEMail);
        echo mysqli_error($con);

		$userid   	= $_SESSION['usrid'];
		$sql = "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'GR', '$si_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}

		$baseurl1 = $baseurl.$modulePath.'indexgrn.php?reset=1';
		echo "<script>window.location.href='$baseurl1';</script>";

?>		
