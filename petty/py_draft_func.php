<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "petty/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$re_id  		= $_POST['re_id'];
		
		$sql = "SELECT a.expense_id, a.amount, b.status, c.budget_name, c.budget_head , b.company_id , b.location_id, b.trans_type
					FROM `sma_pettycash_exp` a, sma_pettycash b, account_mst c 
					where a.approval_ref_no = b.id and b.id = '$re_id' and a.expense_id = c.id ";
//echo $sql."<BR>";				
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->expense_id;
				$status			= $r->status;
				$amount			= $r->amount;
				$budget_name	= $r->budget_name;
				$budget_head	= $r->budget_head;
				$company_id		= $r->company_id;
				$location_id	= $r->location_id;
				$trans_type		= $r->trans_type;
				
					if($trans_type=='P'){
						$sql 	= "update sma_location set paid_total = paid_total - $amount where id = '$location_id' ";
					}
					else if($trans_type=='R'){
						$sql 	= "update sma_location set received_total = received_total - $amount where id = '$location_id' ";
					}
//echo $sql."<BR>";					
					$q2 	= mysqli_query($con, $sql);	
					
			}

        $sql 	= "update sma_pettycash set status = 'Draft', del ='' where id = '$re_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'PC', '$re_id', '$userid', now(), 'Draft', '$makerid', 'Draft', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


