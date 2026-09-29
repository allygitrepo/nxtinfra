<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "travel_approval/";
	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$ta_id  		= $_POST['ta_id'];
		$doc_type		= $_POST['doc_type'];
		
			$sql = "SELECT * FROM sma_traval_approval where id = '$ta_id' ";
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

			}
		$sql 	 = "select * from sma_user where userid = ( select draft_by from sma_traval_approval where id = '$ta_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid 	= $r2['id'];
		$userid 	= $r2['userid'];
		
        $sql 	 = "update sma_traval_approval set status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', current_approver= '', changed_by = '$userid', del ='' where id = '$ta_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) values( '$doc_type', '$ta_id', '$userid', now(), 'Draft', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}

		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=list';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file); */
?>		

