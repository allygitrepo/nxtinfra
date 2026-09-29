<?php
//Development
	$con = mysqli_connect("localhost","root","","athaangmobile");
	
// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
  
//P2P Development
	$conp2p = mysqli_connect("localhost","root","","p2p2023");
// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>

