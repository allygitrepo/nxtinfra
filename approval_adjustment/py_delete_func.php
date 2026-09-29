<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "approval/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$ap_id  		= $_POST['ap_id'];
		
		$sql = " select * from sma_approval_memo where id = '$ap_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$overhead_exp		= $r2['overhead_exp'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		
		$sql = "UPDATE sma_approval_memo set del = 'Y' WHERE id = " .$ap_id . " and id in ( SELECT approval_memo_ref FROM `sma_purchase_order` where approval_memo_ref = '$ap_id' and del = 'Y' ) ";
		$res=mysqli_query($con, $sql);
		echo mysqli_error($con);
		$row_affected = mysqli_affected_rows($con);
		
		if($row_affected ==0){
			$sql = "UPDATE sma_approval_memo set del = 'Y' WHERE id = " .$ap_id ;
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		
		//Budget Revert
			if($overhead_exp!='Y'){
				$sql  = "SELECT * from sma_approval_items where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$quantity 		= $r2['quantity'];
					$rate 			= $r2['unit_rate'];
					$gst 			= $r2['gst'];
					$budget_id		= $r2['budget_id'];
					$amount	= round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
				
					$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
			}
			else if($overhead_exp=='Y'){
				$sql  = "SELECT * from sma_approval_expenses where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$budget_id		= $r2['budget_id'];
					$amount			= $r2['amount'];
				
					$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}	
			}
				
//Budget Revert	

		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'AP', '$ap_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



