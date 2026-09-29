<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "approval/";	
?>

<?php
//check for AP = 87

		$remarks 		= $_POST['remarks'];
		$ap_id  		= $_POST['ap_id'];
		
		$sql = " select * from sma_approval_memo where id = '$ap_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$overhead_exp		= $r2['overhead_exp'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$company_id 		= $r2['company'];
		
		$sql = " select * from company where comp_id = '$company_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 =	mysqli_fetch_array($q2);
		$budget_control_gst		= $r2['budget_control_gst'];
//echo $sql. "<BR>";			
		
		$sql = "UPDATE sma_approval_memo set status = 'Suspend', approval_status = 'Suspend' WHERE id = '$ap_id'" ;
		$res=mysqli_query($con, $sql);
		echo mysqli_error($con);
		$row_affected = mysqli_affected_rows($con);
//echo $sql. "<BR>";	
		
		//Budget Revert
			//if($overhead_exp!='Y'){
				$sql  = "SELECT * from sma_approval_items where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$quantity 		= $r2['quantity'];
					$rate 			= $r2['unit_rate'];
					$gst 			= $r2['gst'];
					$product_id		= $r2['product_id'];
					$budget_id		= $r2['budget_id'];
					
					if($overhead_exp=='Y'){
						$sql  = "SELECT sum(b.amount) as amount_tot, sum(gst_amount) as gst_amount_tot, reference FROM `sma_travel_expenses` a, sma_expenses b  WHERE 1 and a.id = b.approval_ref_no  and approval_number = '$ap_id' and reference = '$product_id' ";
						$res  = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r2 = mysqli_fetch_array($res);
						$amount_tot			=  $r2['amount_tot'];
						$gst_amount 		=  $r2['gst_amount_tot'];
						$reference_id 		=  $r2['reference'];
					}
					
					if($budget_control_gst=='Y'){
						$amount	= round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) - ($amount_tot + $gst_amount)  ;
					}
					else {
						$amount	= round(($quantity	* $rate ),0) - ($amount_tot) ;
					}	
					
					$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
//echo $sql. "<BR>";
				}
			//}
			
				$sql = " UPDATE sma_budget SET blocked_budget = 0 WHERE id = '$budget_id' AND blocked_budget < 0 ";
				mysqli_query($con, $sql);
				
//Budget Revert	

		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'AP', '$ap_id', '$userid', now(), 'Suspend', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Suspend ...");}
//echo $sql. "<BR>";		
//echo('Exit HERE...');
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



