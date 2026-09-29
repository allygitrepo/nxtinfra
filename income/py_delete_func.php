<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "income/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$in_id  		= $_POST['in_id'];
		
		$sql = "UPDATE sma_income_hdr set del = 'Y' WHERE id = " .$in_id ;
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'IN', '$in_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



