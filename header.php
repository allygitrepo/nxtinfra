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
$user_email      = $_SESSION['user_email'];

$tally_access = $_SESSION['tally_access'];

//if($session_life > $inactive){
//	$baseurl1 = $baseurl."logout.php";
//	session_destroy();
//	header("Location: $baseurl1");
//}
 
    date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
 
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
$approval_edit = $_SESSION['approval_edit'];
$menu_id  	= $_SESSION['menu_id'];
$menuonly 	= $_SESSION['menuonly'];
$dashboard 	= $_SESSION['dashboard'];
$department = $_SESSION['department'];
$readonly	= $_SESSION['readonly'];
$writeonly	= $_SESSION['writeonly'];
$finance_from_date 	= $_SESSION['finance_from_date'];
$finance_to_date 	= $_SESSION['finance_to_date'];
$short_fy_code 		= $_SESSION['short_fy_code'];
$zoho_dashboard_view = $_SESSION['zoho_dashboard_view'];

include("dbcon.php");

$sql = " select * from sma_role where id = '$primary_role' ";
$q2 	= mysqli_query($con, $sql);
$r2 	= mysqli_fetch_array($q2);
$primaryrole = $r2['role'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>NXT Infra - Procurements</title>
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
  
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo $baseurl . "plugins/timepicker/bootstrap-timepicker.min.css" ?>">
  
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
   
  .btn-success {
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

	$help_code = 'header.php';
	include "help_code.php";

?>

<body class="hold-transition skin-blue sidebar-mini " >

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $baseurl. 'dashboard_p2p.php?sub=dash'; ?>" class="logo"  style="background-color:#FFFFFF;">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <img src="<?php echo $baseurl . "dist/img/logo_small.jpg"?>" class="logo-mini" style="background-color:#FFFFFF;" />
			<img src="<?php echo $baseurl . "dist/img/logo.jpg"?>" class="logo" style="background-color:#FFFFFF;" />
			
            <!--<span class="logo-mini"><b>A</b>TH</span>-->
            <!-- logo for regular state and mobile devices -->
            <!--<span class="logo-lg"><b>Admin</b>LTE</span>
            <span class="logo-lg" style="color:black;" ><b> NXT Infra </b></span>-->

        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top"  style="background-color:#ff8c00123;">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle sidebar_btn" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				P2P  
				
            </a>
			 
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                              <!-- User Account: style can be found in dropdown.less -->
					
					<li class="dropdown user user-menu " >
						
						<a href="dashboard_p2p.php?sub=dash" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" onclick="openprofile()">
							<span class="hidden-xs"><?php 
													
									$user=$_SESSION['user'];
									echo 'Welcome '.$user_name_by.'&nbsp;&nbsp;';
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
						<!--	<div class="pull-right">
								<a href="<?php echo $baseurl . "setting/user_reset.php?sub=1"?>" class="btn btn-default btn-flat" >Reset Password</a>
							</div>
						-->	
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
          <a href="<?php echo $baseurl. 'dashboard_p2p.php?sub=dash' ?>">
            <i class="fa fa-balance-scale"></i><span>Dashboard</span>
          </a>
        </li>
		
	<?php
		$prev_main_menu_no ='';
		$sql = " select * from sma_main_menu where order_no > 1 and status = 'Y'  order by order_no ";
//echo $sql. '<BR>';		
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r3 = mysqli_fetch_array($res3)){
			
			$menuid 		= $r3['id'];
			$menu_name 		= $r3['menu_name'];
			$main_menu_no 	= $r3['order_no'];
			$menu_icon  	= $r3['menu_icon'];
					
			$sql = " select * from useraccess where user_role = ( select id from sma_role where id = '$primary_role' ) and main_menu_id = '$menuid' and  menu_id = '0' ";
//echo $sql. '<BR>';
			$res6 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r6 = mysqli_fetch_array($res6);
			$menu_id   = $r6['menu_id'];
			$menuonly  = $r6['menuonly'];
				
			if($menuonly=='Y'){
				echo '<li class="treeview">
						<a href="#">
						<i class="fa '.$menu_icon.'"></i>
						<span>'.$menu_name.'</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
						</a> 
						<ul class="treeview-menu">';
				
				$sql = " SELECT a.id as 'sub_menu_id', a.sub_menu_name, a.target as target, a.source as 'source_link', a.order_no as sub_menu_no, a.menu_icon as menu_icon
					FROM `sma_menu` a, sma_main_menu b 
						WHERE a.menu_id = b.id and a.menu_id = '$menuid' and a.status = 'Y' order by b.order_no, a.order_no ";
//echo $sql. '<BR>';	//exit();				
				$res4 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r4 = mysqli_fetch_array($res4)){
						
						$main_menu_id	= $menuid;
						$sub_menu_id	= $r4['sub_menu_id'];
						$sub_menu_name 	= $r4['sub_menu_name'];
						$target 		= $r4['target'];
						$source_link 	= $r4['source_link'];
 						$menu_icon  	= $r4['menu_icon'];

						if($target=='Self'){
							$target='';
						}	
//echo $menu_icon. "<<>>".$sub_menu_id. '<>' .$target." <<<>>> <BR>";						
						$sql = " select * from useraccess where user_role = ( select id from sma_role where id = '$primary_role' ) and main_menu_id = '$main_menu_id' and  menu_id = '$sub_menu_id' ";
//echo $sql. '<BR>';	//exit();						
		//
//if($sub_menu_id==15){ echo $sql. '<BR>';	//exit(); 
//}
						$res5 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r5 = mysqli_fetch_array($res5);
						$menu_id   = $r5['menu_id'];
						//$readonly  = $r5['readonly'];
						//$writeonly  = $r5['writeonly'];
						$menuonly  = $r5['menuonly'];
						$dashboard = $r5['dashboard'];
						
						if($menuonly=='Y'){
							echo '<li><a href="'. $baseurl . $source_link .'" target="'.$target.'"><i class="fa '.$menu_icon.'"></i>'. $sub_menu_name .' </a></li>';
						}
	//fa-wrench fa-circle
				}	
					
			echo '</ul>
					</li>';
			
			}
?>			
						
<?php					
		}	
	?>

<!--		<li class="treeview">
						<a href="#">
						<i class="fa fa-123flag-checkered"></i>
						<span>Other Master</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
						</a> 
				<ul class="treeview-menu">
								
						<li><a href="<?php echo $baseurl . "setting/gst_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> GST Master </a></li>
						<li><a href="<?php echo $baseurl . "setting/account_group_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Account Group </a></li>
				</ul>
		</li>-->


<?php 
//DMS


function moneyFormatIndiaa($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '0';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
			//$thecash = $thecash;
		}
        
		return $thecash;
    }
}
		
	function numbertoword($num){
	   $number = $num;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
	  if(!empty($points)){
			$points = $points . " Paise";
		}
		else{$points='';}
	  //echo $result . "Rupees  " . $points . " Paise";
	  $words=ucwords($result) . " " . $points;
	  return $words;
	}
		
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
//};
	  
</script>

