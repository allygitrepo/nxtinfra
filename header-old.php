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
		
<?php if(empty($mobtab) and $menuonly[$i] !='Y' ){	?>
        <li class="">
          <a href="<?php echo $baseurl. 'dashboard.php?sub=dash' ?>">
            <i class="fa fa-123tachometer-alt"></i> <span>Dashboard</span>
          </a>
        </li>
		
		<?php
		
////New Menu		
		$prev_main_menu_no ='';
		$sql = " select * from sma_main_menu where order_no > 1 order by order_no ";
		$ress3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r3 = mysqli_fetch_array($ress3)){
		
			$menuid 		= $r3['id'];
			$menu_name 		= $r3['menu_name'];
			$main_menu_no 	= $r3['order_no'];
					
				echo '<li class="treeview">
					<a href="#">
					<i class="fa fa-123flag-checkered"></i>
					<span>'.$menu_name.' 123</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
					</a> 
					<ul class="treeview-menu">';

			$sql = " SELECT a.sub_menu_name, a.target, a.source as 'source_link', a.order_no as sub_menu_no FROM `sma_menu` a, sma_main_menu b where a.menu_id = b.id and a.menu_id = '$menuid' order by b.order_no, a.order_no ";
			$ress4 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r4 = mysqli_fetch_array($ress4)){
			
				$sub_menu_name 	= $r4['sub_menu_name'];
				$target 		= $r4['target'];
				$source_link 	= $r4['source_link'];
				
				if($target=='Self'){
					$target='';
				}	
				echo '<li><a href="'. $baseurl . $source_link .'" target="'.$target.'"><i class="fa fa-123wrench"></i>'. $sub_menu_name .' 123 </a></li>';
			
			}	
		
			echo '</ul>
					</li>';
			
		}	
////New Menu
	?>
		
		
		<li class="treeview">
          <a href="#">
            <i class="fa fa-123flag-checkered"></i>
            <span>P2P</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<?php $i = $menu_id[1]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "purchase_requisition/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> Purchase Requisitions </a></li>
			<?php } ?>	
		<?php $i = $menu_id[2]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "approval/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> Approval Memo </a></li>
		<?php } ?>	 
			<?php $i = $menu_id[3]; if ( $menuonly[$i] =='Y' ){ ?>	
				<li><a href="<?php echo $baseurl . "purchase_order/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> Purchase Order </a></li>
		<?php } ?>
		
		<?php $i = $menu_id[5]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "supp_invoice/index.php?reset=1"?>"><i class="fa fa-123wrench"></i> Supplier Invoice </a></li>
		<?php } ?>		
		<?php $i = $menu_id[4]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "travel_approval/company_expense.php?sub=list"?>"><i class="fa fa-123wrench"></i> Operating Expenses </a></li>
		<?php } ?>	
		<?php $i = $menu_id[4]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "ipc/ipc.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i> IPC </a></li>
		<?php } ?>		
				
		  </ul>
        </li>

<!--		
		<?php /* $i = $menu_id[1]; if ( $menuonly[$i] =='Y' ){ ?>
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
		  
        </li>
		
		
	<?php } 
	
	?>

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
		
		<?php } */ ?> 
-->

		
	<!--	<li class="treeview">
            <a href="<?php echo $baseurl . "payment/index.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i>
            <span>Payment</span>
            </a>
        </li>-->
		
		<li class="treeview">
          <a href="#">
            <i class="fa fa-123flag-checkered"></i>
            <span>Account</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
			<ul class="treeview-menu">
			<?php $i = $menu_id[6]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "payment/index.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i> Payment </a></li>
			<?php } ?>	
				<li><a href="<?php echo $baseurl . "petty/pettycash_expense.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i> Petty Cash </a></li>
				<li><a href="<?php echo $baseurl . "payment/vendor_ledger_prn_repo.php?sub=list"?>" target="_blank" ><i class="fa  fa-123users"></i> Ledger</a></li>
				
			</ul>
        </li>
	<?php 
	}
	
	?>
	
	<?php //$i = $menu_id[7]; if ( $menuonly[$i] =='Y' ){ 
		if ( $user !='Eyauditor' ){
//DMS start		

		
		?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123flag-checkered"></i>
            <span>DMS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
		
				<li><a href="<?php echo $baseurl . "dashbmob.php?sub=list"?>"><i class="fa fa-123wrench"></i> Dashboard </a></li>
		<?php if(!empty($mobtab)){ ?>
				<li><a href="<?php echo $baseurl . "dms/outwardm.php?sub=list"?>"><i class="fa fa-123wrench"></i> Outward </a></li>
				<li><a href="<?php echo $baseurl . "dms/inbox_scrm.php?sub=list"?>"><i class="fa fa-123wrench"></i> Inward</a></li>
		<?php } 
			else { ?>		
				<li><a href="<?php echo $baseurl . "dms/outward.php?sub=list"?>"><i class="fa fa-123wrench"></i> Outward </a></li>
				<li><a href="<?php echo $baseurl . "dms/inbox_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> Inward</a></li>
		<?php } ?>
				<li><a href="<?php echo $baseurl . "dms/document_search.php?sub=search"?>"><i class="fa fa-123wrench"></i> Search </a></li>
				<li><a href="<?php echo $baseurl . "dms/forwarded_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> Forward/Handover </a></li>
				<li><a href="<?php echo $baseurl . "dms/my_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> My Document </a></li>
				<li><a href="<?php echo $baseurl . "dms/shared_document.php?sub=list"?>"><i class="fa fa-123wrench"></i> Shared </a></li>
			<!--	<li><a href="<?php echo $baseurl . "dms/outbox_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> History Test</a></li>-->
				<li><a href="<?php echo $baseurl . "dms/history_list.php?sub=list&reset=1"?>"><i class="fa fa-123wrench"></i> History </a></li>
			<!--<li><a href="<?php echo $baseurl . "dms/reminder_mail.php?sub=list"?>"><i class="fa fa-123wrench"></i> Reminder Process </a></li>-->
				<li><a href="<?php echo $baseurl . "setting/document_type_dms.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Digital Document Type</a></li>
				
				<!--<li><a href="<?php echo $baseurl . "dms/inbox.php?sub=list"?>"><i class="fa fa-123wrench"></i> InBox </a></li>-->
				<!--<li><a href="<?php echo $baseurl . "dms/forward_scr.php?sub=list"?>"><i class="fa fa-123wrench"></i> Forward </a></li>-->
				
		  </ul>
        </li>
		
	<?php } ?>
		

<?php
	$menu_show = '';
	for($i=0;$i<40;$i++){
//		echo $menu_id[$i]."<BR>";	
		if($menu_id[$i] =='9' ){
			$menu_show = 'Y';
		}			
	}
?>					
<?php 	$i = $menu_id[33];

//echo $menuonly[$i] . "<< Hello >>". $menu_show. " <<<>>> ";

		if(empty($mobtab) && $menuonly[$i] =='Y' ){
		
		//$i = $menu_id[7]; if ( $menuonly[$i] =='Y' ){ ?>
        <?php if( ( strpos( $role, 'Audit') === false) && ($menu_show == 'Y') ){ ?>
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
		
		if ( $menuonly[10] =='Y' || $menuonly[11] =='Y' || $menuonly[12] =='Y' || $menuonly[35] =='Y' || $menuonly[36] =='Y' || $menu_show=='Y' ){ ?>
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
				<li><a href="<?php echo $baseurl . "budget/budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Entry </a></li>
			<?php } ?>
			
			<?php $i = $menu_id[39]; if ( $menuonly[$i] =='Y' || $user =='Admin' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_adjust.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Adjust </a></li>
				<li><a href="<?php echo $baseurl . "budget/upload_adjust_budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Upload Budget Adjust </a></li>
			<?php } ?>
<?php 

//	echo $menu_id[$i]."<BR>";	

	$menu_show = '';
	for($i=0;$i<40;$i++){
		if($menu_id[$i] =='35'){
			$menu_show = 'Y';
		}			
	}	
//	echo $menu_show ."< ##1 >";
?>			
			<?php if ( $menu_show =='Y' || $user =='Admin' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_trans_list.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Transactions</a></li>				
		
				<li><a href="<?php echo $baseurl . "budget/budget_used_SI.php?sub=list"?>"><i class="fa fa-123wrench"></i> Budget Used Transactions</a></li>
			<?php } ?>

<?php 
	$menu_show = '';
	for($i=0;$i<40;$i++){
		if($menu_id[$i] =='36'){
			$menu_show = 'Y';
		}			
	}	
?>	
			
			<?php $i = $menu_id[35]; if (  $user =='Admin' ){ ?>	
				<li><a href="<?php echo $baseurl . "budget/upload_budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Upload Budget </a></li>
			<?php } ?>

			<?php $i = $menu_id[11]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_name.php?sub=list"?>"><i class="fa fa-123wrench"></i> Category</a></li>
            <?php } ?>

			<?php $i = $menu_id[12]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/budget_category.php?sub=list"?>"><i class="fa fa-123wrench"></i> Sub Category</a></li>
			
			<?php } ?>
			
			<?php if ( $user =='Admin' ){ ?>
				<li><a href="<?php echo $baseurl . "budget/copy_budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Copy Budget</a></li>
				<li><a href="<?php echo $baseurl . "budget/adjust_budget.php?sub=list"?>"><i class="fa fa-123wrench"></i> Recalculate Used Budget</a></li>
				<li><a href="<?php echo $baseurl . "budget/budget_used_export_zoho.php?sub=pdf"?>" target="_blank" ><i class="fa fa-123wrench"></i> Export Used Budget-ZOHO</a></li>
			
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
		
		
		<?php //if( strpos( $role, 'Audit') === false ){ ?>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-123cog"></i>
            <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

			<?php $i = $menu_id[15]; echo $menuonly[$i]. ' <<>> '; if ( $menuonly[$i] =='Y' ){ ?>			
				<li><a href="<?php echo $baseurl . "vendor/vendor.php?sub=list"?>"><i class="fa fa-123child"></i> Vendor</a></li>
			<?php } ?>
			<?php $i = $menu_id[16]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/category.php?sub=list"?>"><i class="fa fa-123cubes"></i> Vendor Category</a></li>
			<?php } ?>
			<?php $i = $menu_id[17]; if ( $menuonly[$i] =='Y' ){ ?>
				<li><a href="<?php echo $baseurl . "vendor/type.php?sub=list"?>"><i class="fa fa-123crosshairs"></i> Vendor Type</a></li>
			<?php } ?>

<!--			<li><a href="<?php echo $baseurl . "setting/account_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Account </a></li>-->

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
		<?php // } ?>
	
		
	<?php if ($user=='Admin'){?>
		
		<li class=""> 
          <!--<a href="<?php echo $baseurl . "pending_list.php?sub=pdf"?>">
            <i class="fa fa-123file-invoice"></i> <span>Pending Status</span>
          </a>-->
		  
		  <a href="#">
            <i class="fa fa-123cog"></i>
            <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
			<ul class="treeview-menu">
				<li><a href="<?php echo $baseurl . "pending_list.php?sub=pdf"?>"><i class="fa  fa-123users"></i> Pending Status</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_sum.php?sub=list"?>"><i class="fa  fa-123users"></i> Approval Note Workflow</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_po.php?sub=list"?>"><i class="fa  fa-123users"></i> Purchase Order Workflow</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_si.php?sub=list"?>"><i class="fa  fa-123users"></i> Supplier Invoice Workflow</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_ipc.php?sub=list"?>"><i class="fa  fa-123users"></i> IPC Workflow</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_py.php?sub=list"?>"><i class="fa  fa-123users"></i> Payment Workflow</a></li>
				<li><a href="<?php echo $baseurl . "approval/hc_workflow_oe.php?sub=list"?>"><i class="fa  fa-123users"></i> Operating Expense Workflow</a></li>
				</a></li>
				
				
				
			</ul>
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
		   ('10', 'Role', 'setting/role_mst.php?sub=list', 'Self', '1'),
		   ('10', 'Main Menu', 'setting/main_menu.php?sub=list', 'Self', '2'),
		   ('10', 'Sub Menu', 'setting/sub_menu.php?sub=list', 'Self', '3'),
		   ('10', 'Users', 'setting/user.php?sub=list', 'Self', '4'),
		   ('10', 'Users Group', 'setting/user_group.php?sub=list', 'Self', '5'),
		   ('10', 'User Access Level', 'setting/useraccs.php?sub=list', 'Self', '6'),
		   ('10', 'Department', 'setting/department.php?sub=list', 'Self', '7'),
		   ('10', 'Designation', 'setting/designation.php?sub=list', 'Self', '8'),
		   ('10', 'Level', 'setting/level.php?sub=list', 'Self', '9'),
		   ('10', 'Document Type', 'setting/document_type.php?sub=list', 'Self', '10'),
		   ('10', 'Company Profile', 'setting/company.php?sub=list', 'Self', '11'),
		   ('10', 'Location', 'setting/project.php?sub=list', 'Self', '12'),
		   ('10', 'Workflow Config', 'setting/workflow_config.php?sub=list', 'Self', '13'),
		   ('10', 'Account', 'setting/account_mst.php?sub=list', 'Self', '14'),
		   ('10', 'Term & Conditions', 'setting/term_mst.php?sub=list', 'Self', '15'),
		   ('10', 'SMTP Login Detail', 'setting/smtp_detail.php?sub=list', 'Self', '16'),
				<li><a href="<?php echo $baseurl . "setting/role_mst.php?sub=list"?>"><i class="fa  fa-123users"></i> Role</a></li>
				<li><a href="<?php echo $baseurl . "setting/main_menu.php?sub=list"?>"><i class="fa  fa-123users"></i> Main Menu</a></li>
				<li><a href="<?php echo $baseurl . "setting/sub_menu.php?sub=list"?>"><i class="fa  fa-123users"></i> Sub Menu</a></li>
			    <li><a href="<?php echo $baseurl . "setting/user.php?sub=list"?>"><i class="fa  fa-123users"></i> Users</a></li>
				<li><a href="<?php echo $baseurl . "setting/user_group.php?sub=list"?>"><i class="fa  fa-123users"></i> Users Group</a></li>
				<li><a href="<?php echo $baseurl . "setting/useraccs.php?sub=list"?>"><i class="fa fa-123university"></i> User Access Level</a></li>
  				<li><a href="<?php echo $baseurl . "setting/department.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Department</a></li>
				<li><a href="<?php echo $baseurl . "setting/designation.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Designation</a></li>
				<li><a href="<?php echo $baseurl . "setting/level.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Level </a></li>
				<li><a href="<?php echo $baseurl . "setting/document_type.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Document Type</a></li>
				<li><a href="<?php echo $baseurl . "setting/company.php?sub=list"?>"><i class="fa fa-123arrows"></i> Company Profile</a></li>
			 
				<li><a href="<?php echo $baseurl . "setting/project.php?sub=list"?>"><i class="fa fa-123code-branch"></i> Location</a></li>
				<li><a href="<?php echo $baseurl . "setting/workflow_config.php?sub=list"?>"><i class="fa fa-123code-branch"></i> Workflow Config</a></li>
				<li><a href="<?php echo $baseurl . "setting/account_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Account </a></li>
				<li><a href="<?php echo $baseurl . "setting/term_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Term & Conditions </a></li>
				
				<li><a href="<?php echo $baseurl . "setting/smtp_detail.php?sub=list"?>"><i class="fa fa-123asterisk"></i> SMTP Login Detail </a></li>
				
				<li><a href="<?php echo $baseurl . "setting/bkstorage.php?sub=bkp"?>"><i class="fa fa-123asterisk"></i> Backup Data </a></li>
					
				<li><a href="<?php echo $baseurl . "setting/mis_mail.php?sub=list"?>"><i class="fa fa-123sitemap"></i> MIS Mail</a></li>
				<li><a href="<?php echo $baseurl . "setting/alert_msg.php?sub=list"?>"><i class="fa fa-123sitemap"></i> Alert Message</a></li>
				<li><a href="<?php echo $baseurl . "dms/courier_mst.php?sub=list"?>"><i class="fa fa-123asterisk"></i> Courier Master</a></li>
			
          </ul>
        </li>
		
		<?php } ?>
			
			
		<?php if ($user=='Admin'){?>
		<li class="treeview">
			<a href="#">
				<i class="fa fa-123cog"></i>
				<span>Tally Adhoc</span>
				<span class="pull-right-container">
				     <i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
				<li><a href="<?php echo $baseurl . "transfer_tally.php?sub=list"?>" target="_blank" ><i class="fa  fa-123users"></i> Transfer to Tally DB
				<li><a href="<?php echo $baseurl . "setting/tally_flag_update.php?sub=list"?>"><i class="fa  fa-123users"></i> Tally Revert Flag</a></li>
				<li><a href="<?php echo $baseurl . "transfer_tally_manually.php?sub=list"?>"><i class="fa  fa-123users"></i> Tally Update Process</a></li>
				
				<li><a href="<?php echo $baseurl . "supp_invoice/budget_change.php?sub=edit"?>"><i class="fa  fa-123users"></i> Budget Change from SI</a></li>
				
			</ul>
		</li>
			
		<?php } ?>
		
<?php } ?>
			


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