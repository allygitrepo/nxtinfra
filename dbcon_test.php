<?php
//Development
	$con = mysqli_connect("localhost","athaangp2p","12345","athaangp2p");
//production
//	$con = mysqli_connect("localhost","vim_vimaljob","Vimaljob@123","vim_vimaljob");
// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>