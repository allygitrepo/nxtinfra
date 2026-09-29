<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	
?>



<?php

	if(isset($_POST['sub1'])){
	
		$value ='';
		
		$userid 	= $_POST['id'];
		
		$s="select * from sma_user where userid='$userid' ";
	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		if($rowcount ==0){
			$value = "User id not available...";
		}
//		$value .= $sql;
			
//$value = $value1;

		echo $value;
				
	}

	
?>	