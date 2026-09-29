<?php
	session_start(); 	
	include "baseurl.php";			
	//Session time out
// set timeout period in seconds
$inactive = 6000; 
$session_life 	= time() - $_SESSION['timeout'];
$mobtab 		= $_SESSION['mob'];
$role_array 	= $_SESSION['role_array'];
$accountant_role = $_SESSION['accountant_role'];

//if($session_life > $inactive){
//	$baseurl1 = $baseurl."logout.php";
//	session_destroy();
//	header("Location: $baseurl1");
//}

$fstlogin = $_SESSION['fstlogin'];
unset($_SESSION['current_url']);
if($session_life > $inactive){
	$baseurl1 = $baseurl."logout.php";
	session_destroy();
	header("Location: $baseurl1");
}
else if($fstlogin !='Y' ){
	$_SESSION['timeout']=time();
	
	$current_url = 'https://' . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
	$_SESSION['current_url'] = $current_url;
	
	$baseurl1= $baseurl.'index.php';
	echo "<script>window.location.href='$baseurl1';</script>";
	
}
//RAVI 18-09-2019
else {
	
	include "session.php";
}
//RAVI 18-09-2019

$_SESSION['timeout']=time();

//Session time out

if(!isset($_SESSION['user'])){
	$baseurl1= $baseurl.'index.php';
   echo "<script>window.location.href='$baseurl1';</script>";
}
$user   = $_SESSION['user'];
$usrid  = $_SESSION['usrid'];
$comid  = $_SESSION['comid'];
$role	= $_SESSION['role'];
$role_a	= $_SESSION['role_a'];
$primary_role = $_SESSION['primary_role'];
$user_name_by = $_SESSION['user_name_by'];
$user_category	= $_SESSION['user_category'];  
$menu_id  	= $_SESSION['menu_id'];
$menuonly 	= $_SESSION['menuonly'];
$dashboard 	= $_SESSION['dashboard'];
$department = $_SESSION['department'];
$readonly	= $_SESSION['readonly'];
$writeonly	= $_SESSION['writeonly'];

include("dbcon.php");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Athaang Group of Companies - Procurements</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $baseurl . "bootstrap/css/bootstrap.min.css" ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <!-- Theme style -->
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $baseurl . "dist/css/skins/_all-skins.min.css"?>">
 
<div class="wrapper">


