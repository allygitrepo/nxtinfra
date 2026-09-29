<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "payment/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$py_id  		= $_POST['py_id'];
		$st_flag  		= $_POST['st_flag'];
		
		$sql = "SELECT * FROM `payment_details` where payment_hdr_id ='$py_id' ";
		$query11 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql."<BR>";

		while($r3  = mysqli_fetch_array($query11)){
			$supplier_invoice_no = $r3['supplier_invoice_no'];
			$supp_id			 = $r3['supp_id'];
			$total_amount 		 = $r3['payment_adjusted'] ;//+  $r3['deduction_amt']  +  $r3['deduction_amt1'] + $r3['deduction_amt3'] + $r3['retention_amount'];
			
			if($st_flag=='S' ){
				$sql = "update `sma_supplier_invoice` set bal_amount = bal_amount + '$total_amount', paid_status='' where supplier_invoice_no = '$supplier_invoice_no' or id = '$supp_id' ";
//		echo $sql."<BR>";
//exit();		
			}
			else if( $st_flag=='R'){
				
				$sql = "UPDATE `sma_supplier_invoice` set bal_retention_amount = bal_retention_amount - '$total_amount'  where id = '$supp_id' ";
//echo $sql."<BR>";			
			}
			else if($st_flag=='A'){
						
				$sql = "update `sma_traval_approval` set paid_amount = paid_amount - '$total_amount', paid_status='' where id = '$supp_id' ";
						
			}
			else if($st_flag=='T'){
						
				$sql = " update sma_travel_expenses set paid_status = '', bal_amount = bal_amount - '$total_amount', paid_status=''  where id = '$supp_id' ";
						
			}
			else if($st_flag=='C'){
						
				$sql = " update sma_travel_expenses set bal_amount = bal_amount - '$total_amount', paid_status=''  where id = '$supp_id' ";
						
			}
			else if($st_flag=='D'){
						
				$sql = "update sma_purchase_order set paid_amount = paid_amount - '$total_amount', paid_status = '' where id = '$supp_id' ";
						
			}
					
			$query1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			//echo $sql."<BR>";
		}
		
        $sql 	= "update payment_header set del = 'Y' where id = '$py_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql."<BR>";

		$sql = "DELETE FROM `tally_journal_entry` where doc_no = '$py_id' and doc_type = 'PY' ";
		mysqli_query($con, $sql);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'PY', '$py_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



