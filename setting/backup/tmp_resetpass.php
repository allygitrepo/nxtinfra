<?php

	require "../dbcon.php";
	$sql = " Select * from sma_user ";
	$query = mysqli_query($con, $sql);
    while($row = mysqli_fetch_array($query)){
		
		$userid 		= $row['userid'];
		$password		= md5($row['password']);
		
		$sql="update sma_user set password = '$password' where userid='$userid'";
		mysqli_query($con, $sql);
		
		exit();
		
	}
				
?>				