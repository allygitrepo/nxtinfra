<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
?>

<?php
	
	$ap_id			= $_GET['ap_id'];
	$id				= $_GET['id'];
	
	$sql    = " delete from sma_approval_expenses where id ='$id' ";
	$result = mysqli_query($con, $sql);

	//$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
	
	echo "<script>window.location.href='edit.php?sub=edit&id=$ap_id';</script>";
	
?>