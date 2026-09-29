<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "provisional_jv/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$pv_id  		= $_POST['pv_id'];
		
		$sql = " select * from sma_budget_proposal where id = '$pv_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$overhead_exp		= $r2['overhead_exp'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$company_id 		= $r2['company'];
/* 
		$sql = " select * from company where comp_id = '$company_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 =	mysqli_fetch_array($q2);
		$budget_control_gst		= $r2['budget_control_gst'];
 */	
		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_budget_proposal where id = '$pv_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];

        $sql 	= "update sma_budget_proposal set status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', 
					approver_5	= '', approver_6 = '', approver_7 = '', approver_8 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', 
					approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', 
					current_approver= '', del ='' where id = '$pv_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) values( 'BP', '$pv_id', '$userid', now(), 'Draft', '$makerid', 'Draft', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


