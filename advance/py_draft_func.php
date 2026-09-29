<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "advance/";	
	
		$remarks 		= $_POST['remarks'];
		$av_id  		= $_POST['av_id'];
		
		$sql = " select * from sma_advance where id = '$av_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
//echo $sql. "<BR>";
		
		$sql = " select * from company where comp_id = '$company_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 =	mysqli_fetch_array($q2);
		//$budget_control_gst		= $r2['budget_control_gst'];

		$sql 	= "select * from sma_user where userid in ( select draft_by from sma_advance where id = '$av_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
//echo $sql. "<BR>";	
        $sql 	= "update sma_advance set status = 'Draft', `approval_status` = '', approver_1	= '', approver_2 = '', approver_3 = '',
					approver_4 = '', approver_5	= '', approver_6 = '', approver_7 = '', approver_8 = '', approver_9 = '', 
					approver_10 = '', approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', 
					approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', 
					approver_9_status = '', approver_10_status = '', current_approver= '', del ='' where id = '$av_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql. "<BR>";		

		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) values( 'AV', '$av_id', '$userid', now(), 'Draft', '$makerid', 'Draft', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
//echo $sql. "<BR>";		
		if(!empty($error)){echo $error; exit(" Draft ...");}
//exit('Exit Here...');

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


