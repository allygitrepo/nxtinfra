<?php
//Development
	$con = mysqli_connect("localhost","root","","p2p2023");

// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>