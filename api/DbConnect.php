<?php
//Development
	
	// Production 
	//$con = mysqli_connect("localhost","u174398304_nxtinfra_p2p","Nxtinfra@123#","u174398304_nxtinfra_p2p");
	$con = mysqli_connect("localhost","root","","nxtinfra_p2p");

// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	
// $sql = 'SET GLOBAL time_zone = "Asia/Calcutta" ' ;
// mysqli_query($con, $sql);
// echo mysqli_error($con) ;	

$sql = 'SET time_zone = "+05:30" ';
mysqli_query($con, $sql);
echo mysqli_error($con) ;			
$sql = 'SET @@session.time_zone = "+05:30" ';
mysqli_query($con, $sql);
echo mysqli_error($con) ;			

?>