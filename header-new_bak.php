<?php
//$baseurl = "http://localhost:80/hc_template/";    //dev url
//$baseurl = "http://bidsdms.in/highwayc/";    //production url
//$baseurl = "http://114.143.234.194/hc_template/";    //production url

	include "baseurl.php";
	session_start(); 	
			
	//Session time out
// set timeout period in seconds
$inactive = 6000; 
$session_life = time() - $_SESSION['timeout'];
$mobtab = $_SESSION['mob'];

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
$user_category	= $_SESSION['user_category'];  
$menu_id  = $_SESSION['menu_id'];
$menuonly = $_SESSION['menuonly'];
$dashboard = $_SESSION['dashboard'];
$department = $_SESSION['department'];

include("dbcon.php");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Highway Concessions - Procurements</title>
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
  <link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css" ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $baseurl . "dist/css/skins/_all-skins.min.css"?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/iCheck/flat/blue.css"?>">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/morris/morris.css"?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/select2/select2.css"?>">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/jvectormap/jquery-jvectormap-1.2.2.css"?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/datepicker/datepicker3.css"?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/daterangepicker/daterangepicker.css"?>">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"?>">
<!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/iCheck/all.css"?>">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
<style>  
//# .btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:visited , .btn-primary:focus{
// #   background-color: #ff8c00 !important;
//#	border-color: #8064A2;
//#   }
   
  .btn-primary {
    background-color: #ff8c00;
    border-color: #ff8c00;
    color: #FFF; }
  .btn-primary:hover,  .btn-primary:focus {
      border-color: #e55317;
      background-color: #e55317;
      color: #FFF; }
  
  .btn-info {
    background-color: #ff8c00;
    border-color: #ff8c00;
    color: #FFF; }
  .btn-info:hover,  .btn-info:focus {
      border-color: #e55317;
      background-color: #e55317;
      color: #FFF; }
		
		
	
	
</style>

</head>

<?php

//@media (max-width: 768px) { #sidebar_btn { display: none; } }

?>

<body class="hold-transition skin-blue sidebar-mini " >

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $baseurl. 'dashboard.php?sub=dash'; ?>" class="logo"  style="background-color:#FFFFFF123;">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <!--<img src="<?php //echo $baseurl . "dist/img/logo123.png"?>" class="logo-mini" />
            <!--<span class="logo-mini"><b>A</b>LT</span>-->
            <!-- logo for regular state and mobile devices -->
            <!--<span class="logo-lg"><b>Admin</b>LTE</span>-->
            <span class="logo-lg" style="color:black;" ><b> Symphony Infotech</b></span>

        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top"  style="background-color:#ff8c00123;">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle sidebar_btn" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                              <!-- User Account: style can be found in dropdown.less -->
							  
					  
					<li class="dropdown user user-menu " >
						<a href="dashboard.php?sub=dash" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" onclick="openprofile()">
							<span class="hidden-xs"><?php 
													
									$user=$_SESSION['user'];
									echo 'Welcome '.$user.'&nbsp;&nbsp;';
													?>
							</span>
						</a>
						
						<ul class="dropdown-menu">
						  <!-- User image -->
						  <!-- Menu Footer-->
						  <li class="user-footer">
							<div class="pull-left">
							  <a href="<?php echo $baseurl;?>setting/user_profile.php?sub=edit&user_id=<?php echo $user; ?>" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="<?php echo $baseurl . "setting/user_reset.php?sub=1"?>" class="btn btn-default btn-flat" >Reset Password</a>
							</div>
							<!--<div class="pull-right">
							  <a href="<?php echo $baseurl;?>logout.php" class="btn btn-default btn-flat">Sign out</a>
							</div>-->
						  </li>
						</ul>
						
					</li>
					  
					  
					  <li class="dropdown tasks-menu">
						<a href="<?php echo $baseurl;?>logout.php" >
						  <span class="hidden-xs">Logout</span>
						</a>
					  </li>	

                </ul>
            </div>
        </nav>
    </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <!--<li class="header">MAIN NAVIGATION</li>-->
		
        <li class="">
          <a href="<?php echo $baseurl. 'dashboard.php?sub=dash' ?>">
            <i class="fa fa-123tachometer-alt"></i> <span>Dashboard</span>
          </a>
        </li>
		
	<?php
		$prev_main_menu_no ='';
		$sql = " select * from sma_main_menu where order_no > 1 order by order_no ";
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r3 = mysqli_fetch_array($res3)){
			
			$menu_id 		= $r3['id'];
			$menu_name 		= $r3['menu_name'];
			$main_menu_no 	= $r3['order_no'];
					
				echo '<li class="treeview">
					<a href="#">
					<i class="fa fa-123flag-checkered"></i>
					<span>'.$menu_name.'</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					</a> 
					<ul class="treeview-menu">';
			
			$sql = " SELECT a.sub_menu_name, a.target, a.source as 'source_link', a.order_no as sub_menu_no FROM `sma_menu` a, sma_main_menu b where a.menu_id = b.id and a.menu_id = '$menu_id' order by b.order_no, a.order_no ";
			$res4 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r4 = mysqli_fetch_array($res4)){
			
				$sub_menu_name 	= $r4['sub_menu_name'];
				$target 		= $r4['target'];
				$source_link 	= $r4['source_link'];
				
				if($target=='Self'){
					$target='';
				}	
				echo '<li><a href="'. $baseurl . $source_link .'" target="'.$target.'"><i class="fa fa-123wrench"></i>'. $sub_menu_name .' </a></li>';
			
			}	
		
			echo '</ul>
					</li>';
			
		}	
	?>
		
		


<?php 
//DMS
	
?>		
      </ul>
    </section>
	<br><br><br>
    <!-- /.sidebar -->
  </aside>

<script>
function openprofile(){
	
//	alert('Hello');
	document.getElementById('openprofile').className = 'open';
	
}
</script>
<script>
      function disableClick(){
        document.onclick=function(event){
          if (event.button == 2) {
            alert('Right Click Message');
            return false;
          }
        }
      }
	  
/*function check(e)
{
alert(e.keyCode);
}*/
 //document.onkeydown = function(e) {
    //if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 ||     e.keyCode === 117 || e.keycode === 17 || e.keycode === 85)) 
//		if (e.ctrlKey && (    e.keycode === 85)) {//ctrl+u Alt+c, Alt+v will also be disabled sadly.
//        alert('not allowed');
//    }
//    return false;
};
	  
</script>