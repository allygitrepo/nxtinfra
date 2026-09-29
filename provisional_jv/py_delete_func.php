<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "provisional_jv/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$pv_id  		= $_POST['pv_id'];
		
		$sql = "UPDATE sma_provisional_jv_hdr set del = 'Y' WHERE id = " .$pv_id ;
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'PV', '$pv_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



