<?php
//$baseurl = "http://localhost:80/hc_template/";    //dev url
//$baseurl = "http://bidsdms.in/highwayc/";    //production url
$baseurl = "http://114.143.234.194/hc_template/";    //production url

	include "baseurl.php";
	session_start(); 	
		
		
	//Session time out
// set timeout period in seconds
$inactive = 6000; 
$session_life = time() - $_SESSION['timeout'];

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
<body class="hold-transition skin-blue sidebar-mini"  >
<div class="wrapper">

  <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $baseurl. 'dashboard.php?sub=dash'; ?>" class="logo"  style="background-color:#FFFFFF;">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <img src="<?php echo $baseurl . "dist/img/logo.png"?>" class="logo-mini" />
            <!--<span class="logo-mini"><b>A</b>LT</span>-->
            <!-- logo for regular state and mobile devices -->
            <!--<span class="logo-lg"><b>Admin</b>LTE</span>-->
            <img src="<?php echo $baseurl . "dist/img/logo.png"?>" class="logo-lg" />
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top"  style="background-color:#ff8c00;">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
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
		
	<!--	<li><a href="<?php echo $baseurl . "dashboard.php?sub=list"?>"><i class="fa fa-123wrench"></i> Dashboard Old</a></li>-->
		
		
		<!--
		<li class="">
          <a href="<?php echo $baseurl. 'dashboard.php?sub=dash' ?>" >
            <i class="fa fa-123tachometer-alt"></i> <span>Test Dashboard</span>
          </a>
        </li>-->
		<?php $i = $menu_id[1]; if ( $menuonly[$i] =='Y' ){ ?>
        <li class=""> 
          <a href="<?php echo $baseurl . "purchase_requisition/index.php?reset=1"?>">
            <i class="fa fa-123file-invoice"></i> <span>Purchase Requisitions</span>
          </a>
        </li>
		<?php } ?>

		<?php $i = $menu_id[2]; if ( $menuonly[$i] =='Y' ){ ?>	
        <li class="">
          <a href="<?php echo $baseurl . "approval/index.php?reset=1"?>">
            <i class="fa fa-123thumbs-up"></i> <span>Approval Memo </span>
          </a>
        </li>
		
		<?php } ?>

		<?php $i = $menu_id[3]; if ( $menuonly[$i] =='Y' ){ ?>
		<li class="#">
		    <a href="<?php echo $baseurl . "purchase_order/index.php?reset=1"?>">
				<i class="fa fa-123files-o"></i><span>Purchase Order</span>
		    </a>
		</li>
		
		<?php } ?>

		<?php $i = $menu_id[4]; if ( $menuonly[$i] =='Y' ){ ?>	
		<li class="#">
		    <a href="<?php echo $baseurl . "grnsrn"?>">
				<i class="fa fa-123files-o"></i><span>GRN / SRN </span>
		    </a>
		</li> 
		
		<?php } ?>

		<?php $i = $menu_id[5]; if ( $menuonly[$i] =='Y' ){ ?>
		
		 <li class="treeview">
			   <a href="<?php echo $baseurl . "supp_invoice/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> 
			   <span>Supplier Invoice</span>
			   </a>
		 </li>
        
<!--          <ul class="treeview-menu">
				<li><a href="<?php echo $baseurl . "supp_invoice/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> Supplier Invoice</a></li>
				<li><a href="<?php echo $baseurl . "travel_approval/company_expense.php?sub=list"?>"><i class="fa fa-123wrench"></i> Operating Expenses</a></li>
			
          </ul>-->
		  
        </li>
		
		
		<?php } ?>

		
		<?php $i = $menu_id[4]; if ( $menuonly[$i] =='Y' ){ ?>
		
        <li class="treeview">
          <a href="<?php echo $baseurl . "travel_approval/company_expense.php?sub=list"?>"><i class="fa fa-123wrench"></i> 
            <span>Operating Expenses</span>
          </a>
        </li>
		
		<?php } ?>
		
		
		<?php $i = $menu_id[4]; if ( $menuonly[$i] =='Y' ){ ?>
		
        <li class="treeview">
          <a href="<?php echo $baseurl . "ipc/ipc.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i>
            <span>IPC </span>
          </a>
        </li>
		
		<?php } ?>

		<?php $i = $menu_id[6]; if ( $menuonly[$i] =='Y' ){ ?>
		
		<li class="treeview">
          <a href="<?php echo $baseurl . "payment/index.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i>
            <span>Payment</span>
          </a>
        </li>
		
		<?php } ?>

		<?php //$i = $menu_id[7]; if ( $menuonly[$i] =='Y' ){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123flag-checkered"></i>
            <span>DMS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			
				<li><a href="<?php echo $baseurl . "dms/outward.php?sub=list"?>"><i class="fa fa-123wrench"></i> Outward </a></li>
				<li><a href="<?php echo $baseurl . "dms/inbox_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> Inward</a></li>
				<!--<li><a href="<?php echo $baseurl . "dms/inbox.php?sub=list"?>"><i class="fa fa-123wrench"></i> InBox </a></li>-->
				<li><a href="<?php echo $baseurl . "dms/my_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> My Document </a></li>
				<!--<li><a href="<?php echo $baseurl . "dms/forward_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> Forward </a></li>-->
				<li><a href="<?php echo $baseurl . "dms/forwarded_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> Forwarded </a></li>
				<li><a href="<?php echo $baseurl . "dms/shared_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> Shared </a></li>
				<li><a href="<?php echo $baseurl . "dms/outbox_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> History </a></li>
				
		  </ul>
        </li>
		<?php //} ?>
		

		
		<?php //$i = $menu_id[7]; if ( $menuonly[$i] =='Y' ){ ?>
        <?php if( strpos( $role, 'Audit') === false ){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123flag-checkered"></i>
            <span>Employee Portal</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			
				<li><a href="<?php echo $baseurl . "travel_approval/traval_app.php?sub=list"?>"><i class="fa fa-123wrench"></i> Travel Request</a></li>
				<li><a href="<?php echo $baseurl . "travel_approval/travel_expence.php?sub=list"?>"><i class="fa fa-123wrench"></i> Travel Expenses</a></li>
				<li><a href="<?php echo $baseurl . "travel_approval/regular_expense.php?sub=list"?>"><i class="fa fa-123wrench"></i> Regular Expenses</a></li>
			
		  </ul>
        </li>
		<?php } ?>

		<?php $i = $menu_id[10];
		if ( $menuonly[10] =='Y' || $menuonly[11] =='Y' || $menuonly[12] =='Y' ){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123wrench"></i>
            <span>Budget</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           
		<?php $i = $menu_id[10]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Entry</a></li>
				<li><a href="<?php echo $baseurl . "budget/budget_trans_list.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Transactions</a></li>				
				<li><a href="<?php echo $baseurl . "budget/budget_used_SI.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Used Transactions</a></li>	
			<?php } ?>

		<?php $i = $menu_id[11]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_name.php?sub=list"?>"><i class="fa fa-123wrench"></i> Name</a></li>
            <?php } ?>

		<?php $i = $menu_id[12]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_category.php?sub=list"?>"><i class="fa fa-123wrench"></i> Head</a></li>
			
			<?php } ?>
			
			<?php $i = $menu_id[12]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/copy_budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Copy Budget</a></li>
			
			<?php } ?>
				
<!--        <li><a href="<?php echo $baseurl . "budget/budget_group.php?sub=list"?>"><i class="fa fa-123wrench"></i> Groups</a></li>
            <li><a href="<?php echo $baseurl . "budget/budget_subgroup.php?sub=list"?>"><i class="fa fa-123wrench"></i>Sub Groups</a></li>  
-->			
          </ul>
        </li>
		<?php } 
		
		//echo $role; //$role!='Audit-PO'?>
		
		<?php if( strpos( $role, 'Audit') === false  ){  //if( strpos( $user, 'Audit' ) !== false) { ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123wrench"></i>
            <span>Materials</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<?php $i = $menu_id[13]; if ( $menuonly[$i] =='Y' ){ ?>            
			<li><a href="<?php echo $baseurl . "product/product.php?sub=list"?>"><i class="fa fa-123wrench"></i> Materials</a></li>
			<?php } ?>
			
			<?php $i = $menu_id[14]; if ( $menuonly[$i] =='Y' ){ ?>
            
			<li><a href="<?php echo $baseurl . "product/product_group.php?sub=list"?>"><i class="fa fa-123wrench"></i> Material Category</a></li>
  			<?php } ?>
			
			<li><a href="<?php echo $baseurl . "product/units.php?sub=list"?>"><i class="fa fa-123wrench"></i> Units</a></li> 
			
          </ul>
        </li>
		
		<?php } ?>
		
		<?php if( strpos( $role, 'Audit') === false ){ ?>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123cog"></i>
            <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

			<?php $i = $menu_id[15]; if ( $menuonly[$i] =='Y' ){ ?>			
				<li><a href="<?php echo $baseurl . "vendor/vendor.php?sub=list"?>"><i class="fa fa-123child"></i> Vendor</a></li>
			<?php } ?>
			<?php $i = $menu_id[16]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/category.php?sub=list"?>"><i class="fa fa-123cubes"></i> Vendor Category</a></li>
			<?php } ?>
			<?php $i = $menu_id[17]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/type.php?sub=list"?>"><i class="fa fa-123crosshairs"></i> Vendor Type</a></li>
			<?php } ?>

			<li><a href="<?php echo $baseurl . "setting/account_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Account </a></li>

			<?php $i = $menu_id[18]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/states.php?sub=list"?>"><i class="fa fa-123crosshairs"></i> States</a></li>  
			<?php } ?>
			<?php $i = $menu_id[19]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/cities.php?sub=list"?>"><i class="fa fa-123crosshairs"></i> City</a></li>  
			<?php } ?>
			<?php $i = $menu_id[20]; if ( $menuonly[$i] =='Y' ){ ?>			
				<li><a href="<?php echo $baseurl . "setting/stages.php?sub=list"?>"><i class="fa fa-123sitemap"></i> Stages</a></li>
			<?php } ?>
			
			
			
<!--		<li><a href="<?php echo $baseurl . "setting/location.php?sub=list"?>"><i class="fa  fa-123sliders"></i> Location</a></li>-->

          </ul>
        </li>
		<?php } ?>
		
		
		<?php if ($user=='Admin'){?>
		
		<li class=""> 
          <a href="<?php echo $baseurl . "pending_list.php?sub=pdf"?>">
            <i class="fa fa-123file-invoice"></i> <span>Pending Status</span>
          </a>
        </li>

		<li class="treeview">
          <a href="#">
            <i class="fa fa-123cog"></i>
            <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
		  
			
				<li><a href="<?php echo $baseurl . "setting/role_mst.php?sub=list"?>"><i class="fa  fa-123users"></i> Role</a></li>
			    <li><a href="<?php echo $baseurl . "setting/user.php?sub=list"?>"><i class="fa  fa-123users"></i> Users</a></li>
				<li><a href="<?php echo $baseurl . "setting/useraccs.php?sub=list"?>"><i class="fa fa-123university"></i> User Access Level</a></li>
  				<li><a href="<?php echo $baseurl . "setting/department.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Department</a></li>
				<li><a href="<?php echo $baseurl . "setting/designation.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Designation</a></li>
				<li><a href="<?php echo $baseurl . "setting/level.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Level </a></li>
				<li><a href="<?php echo $baseurl . "setting/document_type.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Document Type</a></li>
				<li><a href="<?php echo $baseurl . "setting/document_type_dms.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Digital Document Type</a></li>
				<li><a href="<?php echo $baseurl . "setting/company.php?sub=list"?>"><i class="fa fa-123arrows"></i> Company Profile</a></li>
			 
				<li><a href="<?php echo $baseurl . "setting/project.php?sub=list"?>"><i class="fa fa-123code-branch"></i> Location</a></li>
				<li><a href="<?php echo $baseurl . "setting/workflow_config.php?sub=list"?>"><i class="fa fa-123code-branch"></i> Workflow Config</a></li>
				<li><a href="<?php echo $baseurl . "setting/account_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Account </a></li>
				<li><a href="<?php echo $baseurl . "setting/term_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Term & Conditions </a></li>
				
				<li><a href="<?php echo $baseurl . "setting/smtp_detail.php?sub=list"?>"><i class="fa fa-123asterisk"></i> SMTP Login Detail </a></li>
				
				<li><a href="<?php echo $baseurl . "setting/bkstorage.php?sub=bkp"?>"><i class="fa fa-123asterisk"></i> Backup Data </a></li>
					
				<li><a href="<?php echo $baseurl . "setting/mis_mail.php?sub=list"?>"><i class="fa fa-123sitemap"></i> MIS Mail</a></li>
				<li><a href="<?php echo $baseurl . "setting/alert_msg.php?sub=list"?>"><i class="fa fa-123sitemap"></i> Alert Message</a></li>
			
			<?php } ?>
          </ul>
        </li>
		
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