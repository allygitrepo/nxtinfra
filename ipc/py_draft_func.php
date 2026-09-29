<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "ipc/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$ipc_id  		= $_POST['ipc_id'];
		
		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_ipc where id = '$ipc_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		$user_id = $r2['userid'];

        $sql 	= "update sma_ipc set status = 'Draft', `approval_status` = '', del = '' , draft_by = '$user_id' where id = '$ipc_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'IP', '$ipc_id', '$userid', now(), 'Draft', '$makerid', 'Draft', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


