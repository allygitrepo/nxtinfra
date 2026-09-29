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
		
		$sql = " select * from sma_purchase_order where id = '$our_po_ref_no' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$po_type	 		= $r2['po_type'];
		$approval_hdr_id	= $r2['approval_memo_ref'];
		
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
			
			$gstamt = round((($si_qty * $si_rate) * $si_gst / 100),2);
			if($budget_control_gst!='Y'){
				$gstamt = 0;
			}
			
			$si_amount = $si_qty * $si_rate + $gstamt;
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   = $r4->blocked_budget;
			$used_budget    = $r4->used_budget;
			
			//if($used_budget>=$si_amount){
				$sql = "update sma_budget set used_budget = used_budget - $si_amount where id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			//}
				$sql = "update sma_budget set used_budget =  0 where 1 and used_budget <0 and id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			
				$sql = "update sma_budget set blocked_budget = blocked_budget + $si_amount where id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				$sql = "update sma_budget set blocked_budget =  0 where 1 and blocked_budget <0 and id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
			if(!empty($our_po_ref_no)){
				$sql="update sma_po_items set bal_si_qty  = bal_si_qty - $si_qty, bal_si_amount  = bal_si_amount - $si_amount where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";
				$query=mysqli_query($con, $sql);		
				echo mysqli_error($con);
				
				$sql="update sma_po_items set bal_si_qty  = 0, bal_si_amount = 0 where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and bal_si_qty < 0 ";
				$query=mysqli_query($con, $sql);		
				echo mysqli_error($con);
				
			}
			
					
		}
		
		$sql = "update sma_supplier_invoice_details set qty = 0 where si_hdr_id = '$si_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql . "<BR>";		
//		$sql = "delete from sma_supplier_invoice where id='$si_id' ";
		$sql = "update sma_supplier_invoice set del = 'Y' where id='$si_id' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql . "<BR>";

		$sql = "delete FROM `tally_journal_entry` where doc_no = '$si_id' and doc_type = 'SI' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'SI', '$si_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//echo $sql . "<BR>";		
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		
