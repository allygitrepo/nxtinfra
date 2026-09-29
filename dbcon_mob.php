<?php
//Development
	$conmob = mysqli_connect("localhost","root","","athaang_mob");
	
// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
  
?>
