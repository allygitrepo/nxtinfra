<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "travel_approval/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$re_id  		= $_POST['re_id'];
		
			$sql = "SELECT a.reference, a.amount, a.gst_amount, b.company_id, b.approval_number, a.budget_id, a.exp_type
					FROM `sma_expenses` a, sma_travel_expenses b
					where a.approval_ref_no = b.id  and b.id = '$re_id' ";//and b.exp_type = 'C'
//echo $sql ."<BR>";					
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->reference;
				$gst_amount		= $r->gst_amount ;
				
				$company_id		= $r->company_id;
				$budget_id		= $r->budget_id;
				$approval_number= $r->approval_number;
				$exp_type		= $r->exp_type;
				
				$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
				$q3  = mysqli_query($con, $sql);
				$r3  = mysqli_fetch_array($q3);
				$budget_control_gst = $r3['budget_control_gst'];
				if($budget_control_gst=='N'){
					$gst_amount = 0;
				}
				
				$amount			= $r->amount + $gst_amount;
				
				if(!empty($approval_number)){
					
					$sql = " UPDATE `sma_budget` SET 
									used_budget = used_budget - $amount, blocked_budget = blocked_budget + $amount 
								WHERE id = '$budget_id' ";
					mysqli_query($con, $sql);
					
					$sql = " UPDATE  sma_approval_items SET bal_amount = bal_amount - '$amount' where approval_hdr_id = '$approval_number' ";
					mysqli_query($con, $sql);
					
					$sql = " UPDATE  sma_approval_items SET bal_amount = 0 where approval_hdr_id = '$approval_number' and bal_amount < 0 ";
					mysqli_query($con, $sql);
					
				}
				else if(empty($approval_number)){
					$sql = " UPDATE `sma_budget` SET 
									used_budget = used_budget - $amount 
								WHERE id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
				
			}
//echo $sql. "<BR>";			
		$sql = "update sma_travel_expenses SET no_budget = '', del = 'Y' where 1 and id='$re_id' "; //exp_type = 'C'
		 mysqli_query($con, $sql);
		 
		// Update email inbox table if the record is linked
        $sqlEMail = "UPDATE email_inbox SET grn_no='', grn_type='' WHERE grn_no='$re_id' ";         
        $queryEmail = mysqli_query($con, $sqlEMail);
        $error= mysqli_error($con);
		
		$sql = "delete FROM `tally_journal_entry` where doc_no = '$re_id' and doc_type = 'CE' ";
		mysqli_query($con, $sql);
		
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'CE', '$re_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}

//echo $exp_type . "<<>>";		
//exit('Exit Here...');
		$baseurl1 = $baseurl.$modulePath.'company_expense.php?sub=list';
		if($exp_type=='D'){
			$baseurl1 = $baseurl.$modulePath.'direct_expense_payment.php?sub=list';
		}	
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



