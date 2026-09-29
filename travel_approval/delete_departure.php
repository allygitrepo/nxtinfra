<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
?>

<?php
	
	$approval_ref_no= $_GET['approval_ref_no'];
	$id				= $_GET['id'];
	$sql="delete from sma_departure where id ='$id' ";
	$result = mysqli_query($con, $sql);

	//$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
	echo "<script>window.location.href='travel_expence.php?sub=edit&id=$approval_ref_no';</script>";

?>