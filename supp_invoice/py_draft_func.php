<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "supp_invoice/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$si_id  		= $_POST['si_id'];
			
		$sql = " select * from sma_supplier_invoice where id = '$si_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$against_po_flag 	= $r2['against_po_flag'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$sql = "SELECT * FROM `sma_supplier_invoice_details` where si_hdr_id = '$si_id' ";
		$sires = mysqli_query($con, $sql);
		echo 	 mysqli_error($con);
		while($sr    = mysqli_fetch_array($sires)){
			$material_id 		= $sr['material_id'];
			$budget_head 		= $sr['budget_head'];
			$budget_id 			= $sr['budget_id'];
			$si_qty 			= $sr['qty'];
			$si_rate 			= $sr['rate'];
			$si_gst 			= $sr['gst'];
			$amount 			= $sr['amount'];
			
			if(empty($si_gst)){
				$si_gst = 0;
			}	
			$gstamt = round((($si_qty * $si_rate) * $si_gst / 100),0);
			if($budget_control_gst!='Y'){
				$gstamt = 0;
			}
			//$amount = $si_qty * $si_rate + $gstamt;
			$si_amount = $si_qty * $si_rate + $gstamt;
			if( $si_amount <= 0 ){
				$si_amount = 0;
			}
			
			/* $sql = "UPDATE `sma_po_items` set bal_si_qty = bal_si_qty - '$si_qty' where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
			mysqli_query($con, $sql);
								
			$sql = "UPDATE `sma_po_items` set bal_si_qty = 0, bal_si_amount = 0 where bal_si_qty < 0 AND purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
			mysqli_query($con, $sql); */
			
				/* 
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   = $r4->blocked_budget;
			$used_budget    = $r4->used_budget;
			 */
			$sql = "UPDATE sma_budget SET 
					used_budget = used_budget + $si_amount, blocked_budget = blocked_budget - $si_amount 
						WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			
		}
		
        $sql 	= "update sma_supplier_invoice set status = 'Draft',  tally_status = '', `grn_approval_status` = '', approval_status = '', 
					grn_approver	= '', approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', current_approver= '', del ='' where id = '$si_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);

		/* $sql = "update sma_supplier_invoice_details set qty = 0 , first_insert = 'F' where si_hdr_id = '$si_id' ";
		mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();} */
			
		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_supplier_invoice where id = '$si_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$sql = "delete FROM `tally_journal_entry` where doc_no = '$si_id' and doc_type = 'SI' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) values( 'SI', '$si_id', '$userid', now(), 'Draft', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


