<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	$modulePath = "petty/";	
?>

<?php
	
	$id				= $_GET['id'];
		
	$sql = "SELECT a.expense_id, a.amount, b.status, b.company_id, b.location_id, b.trans_type,
				a.approval_ref_no
			FROM `sma_pettycash_exp` a, sma_pettycash b
				WHERE a.approval_ref_no = b.id AND a.id = '$id' ";
				
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->expense_id;
				$status			= $r->status;
				$amount			= $r->amount;
				$company_id		= $r->company_id;
				$location_id	= $r->location_id;
				$trans_type		= $r->trans_type;
				$approval_ref_no= $r->approval_ref_no;
				
				
				if($trans_type=='P'){
						$sql 	= "update sma_location set paid_total = paid_total - $amount where id = '$location_id' ";
				}
				else if($trans_type=='R'){
					$sql 	= "update sma_location set received_total = received_total - $amount where id = '$location_id' ";
				}				
				$q2 	= mysqli_query($con, $sql);	
				
				
			}
/* $file = fopen("ravitest.txt","a");
	fwrite($file,$sql);
	fclose($file); 	 */		
// exit();			
		
		$sql = "delete from sma_pettycash_exp where id = '$id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		/* $userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'PC', '$approval_ref_no', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");} */
//exit();
		$baseurl1 = $baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$approval_ref_no;
		echo "<script>window.location.href='$baseurl1';</script>";
		
		exit();
?>