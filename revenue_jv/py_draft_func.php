<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	//$modulePath = "purchase_order/";	
	$modulePath = "revenue_jv/revenue_jv.php?sub=list";
?>

<?php

		$remarks 		= $_POST['remarks'];
		$revenue_hdr_id  		= $_POST['revenue_hdr_id'];

		$sql = " select * from p2p_revenue_hdr where id = '$revenue_hdr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		//$po_type	 		= $r2['po_type'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];

//exit();

        $sql 	= "update p2p_revenue_hdr set status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', 
					approver_5	= '', approver_6 = '', approver_7 = '', approver_8 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', 
					approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', 
					current_approver= '', del ='' , tally_status = ''
					where id = '$revenue_hdr_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql. "<BR>";

		$sql 	= "select * from sma_user where userid = ( select draft_by from p2p_revenue_hdr where id = '$revenue_hdr_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'RV', '$revenue_hdr_id', '$userid', now(), 'Draft', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}

//echo $sql. "<BR>";
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


