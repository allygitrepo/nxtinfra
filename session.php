<?php session_start();

if(!isset($_SESSION['user'])){ 
	include "baseurl.php";
	
	$baseurl1= $baseurl.'index.php';
	echo "<script>window.location.href='$baseurl1';</script>";
   
}
//RAVI 18-09-2019
else {
		include("dbcon.php");
		$one_time = '';
		$username = $_SESSION['user'];
		$tdate = date("Y-m-d");
		$sql      = " select * from user_login where userid='$username' and tdate = '$tdate' order by one_time desc";
		$result   = mysqli_query($con, $sql);
		$r		  = mysqli_fetch_object($result);
		$one_time = $r->one_time;
		$num_rows = mysqli_num_rows($result);

		if($num_rows==1 && $one_time!='1'){
				include "baseurl.php";
				
				$baseurl1= $baseurl.'otp_login.php';
				echo "<script>window.location.href='$baseurl1';</script>";
				
		}
		
}
//RAVI 18-09-2019

	$readonly  	= $_SESSION['readonly'];
	$writeonly 	= $_SESSION['writeonly'];
	$editonly	= $_SESSION['editonly'];
	$printonly	= $_SESSION['printonly'];
	
?>
