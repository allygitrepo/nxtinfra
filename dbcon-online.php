<?php
//Development
	$con = mysqli_connect("localhost","syminypj_sekura","sekura@123","syminypj_sekura");
	
//production
//	$con = mysqli_connect("localhost","vim_vimaljob","Vimaljob@123","vim_vimaljob");
	

// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
  
?>
 