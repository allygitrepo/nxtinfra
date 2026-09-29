<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "travel_approval/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$re_id  		= $_POST['re_id'];
		$doc_type		= $_POST['doc_type'];
		
		
			$sql = "SELECT a.reference, a.amount, a.budget_name, a.budget_head, a.budget_id, 	b.company_id, b.approval_number
				FROM `sma_expenses` a, sma_travel_expenses b, account_mst c 
					where a.approval_ref_no = b.id and b.id = '$re_id' and a.reference = c.id ";
			
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->reference;
				$amount			= $r->amount;
				$budget_name	= $r->budget_name;
				$budget_head	= $r->budget_head;
				$company_id		= $r->company_id;
				$budget_id		= $r->budget_id;
				$approval_number= $r->approval_number;
				
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
			
        $sql 	 = "update sma_travel_expenses set no_budget = '', status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '',approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', approver_5_status = '',approver_6_status = '',approver_7_status = '',approver_8_status = '', current_approver= '', del ='' where id = '$re_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql 	 = "select * from sma_user where userid = ( select draft_by from sma_travel_expenses where id = '$re_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) values( '$doc_type', '$re_id', '$userid', now(), 'Draft', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}

		if($doc_type=='TE'){
			$baseurl1 = $baseurl.$modulePath.'travel_expence.php?sub=list';
		}
		else if($doc_type=='RE'){
			$baseurl1 = $baseurl.$modulePath.'regular_expense.php?sub=list';
		}
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file); */
?>		

