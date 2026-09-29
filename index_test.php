<?php
	session_start();
	require "dbcon.php";
	
	$rowcount='0';
	
// 10 mins in seconds

	$_SESSION['timeout']=time();

if(isset($_POST['submit'])){
   	$uname = $_POST['username'];
	//$pass  = md5($_POST['password']);
	$pass  = $_POST['password'];
	$pass_expired='';
	
    $s="select * from sma_user where userid='$uname' and password='$pass'";
	
	$sql = mysqli_query($con, $s);
	$rowcount = mysqli_num_rows($sql);
	
	while($r = mysqli_fetch_object($sql)){
		$username = $r->userid;
		$user_name_by = $r->username;
		$id		 = $r->id;
		$role	 = $r->role;
		$department	 = $r->department;
		$user_email	 = $r->email;
		$company_id = $r->company_id;
		$user_category = $r->user_category;
		$password_expired_date	= $r->password_expired_date;
		$todays_date = date("Y-m-d");
		
		//echo strtotime($todays_date). ' ' . strtotime($password_expired_date); exit();
//	if ($username =='test'){
//		echo $username . ' ' .strtotime($todays_date). ' >>>> ' . strtotime($password_expired_date);
		if( strtotime($todays_date) > strtotime($password_expired_date) ){
			$pass_expired = 'Y';
		}
//	}
		
	}

    $s="select * from sma_role where id='$role' ";
	$sql = mysqli_query($con, $s);
	$rowcount = mysqli_num_rows($sql);
	
	while($r = mysqli_fetch_object($sql)){
		$role	 	= $r->role;
		$role_id	= $r->id;
	}
	
	$_SESSION['user']  = ucfirst(strtolower($username));
	$_SESSION['usrid'] = $id;
	$_SESSION['comid'] = $company_id;
	$_SESSION['role']  = $role;
	$_SESSION['role_id']  = $role_id;
	$_SESSION['department']  = $department;
	$_SESSION['user_category'] = $user_category;
	$_SESSION['user_name_by'] = $user_name_by;

	$menuonly = array();
	$dashboard = array();
	
	$sql ="SELECT * FROM `useraccess` where user_role = '$role_id' order by menu_id ";

	$rs1 = mysqli_query($con, $sql);
	$i = 0;
	while($r = mysqli_fetch_object($rs1)){
		$i = $i + 1;
		$readonly[$i] 	= $r->readonly;
		$menuonly[$i] 	= $r->menuonly;
		$dashboard[$i] 	= $r->dashboard;
		$menu_id[$i] 	= $r->menu_id;
	}
	
	$_SESSION['menu_id']  = $menu_id;
	$_SESSION['menuonly']  = $menuonly;
	$_SESSION['readonly']  = $readonly;
	$_SESSION['dashboard']  = $dashboard;
	
	$uname = $_SESSION['user'];
	$otp   = $_POST['otp'];
	$tdate = date("Y-m-d");
	
	if($rowcount!=0 && $pass_expired=='' ){

		$one_time = '';
		$sql      = " select * from user_login where userid='$username' and tdate = '$tdate' order by one_time desc";
		$result   = mysqli_query($con, $sql);
		$r		  = mysqli_fetch_object($result);
		$one_time = $r->one_time;
		$num_rows = mysqli_num_rows($result);

echo $sql. "<BR>";
echo $one_time. ' <<>> ' . $num_rows;
		if($num_rows==1 && $one_time!='1'){
				echo '<script>window.location.href="otp_login.php";</script>'; 
		}
		else if((empty($num_rows) || $num_rows==0 ) && $one_time!='1' ){
			$six_digit_random_number = mt_rand(100000, 999999);
			$tdate = date("Y-m-d");
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$one_time = '';
			$sql      = " select count(*) as cnt, one_time from user_login where userid='$username' and tdate = '$tdate' ";
			$result   = mysqli_query($con, $sql);
			$r 		  = mysqli_fetch_object($result);
			$cnt  	  = $r->cnt;
			$one_time = $r->one_time;
echo $sql."<BR>";			
			if(empty($cnt) || $cnt==0){
				$sql  = "insert into user_login(`userid`, `otp`, `tdate`, `ipaddress`) values( '$username', '$six_digit_random_number', '$tdate', '$ipaddress' )";
				mysqli_query($con, $sql);
				include("otp_mail.php");
				echo '<script>window.location.href="otp_login.php";</script>'; 
			}
			else if($one_time!='1'){
				echo '<script>window.location.href="otp_login.php";</script>'; 
			}
			else {
				echo '<script>window.location.href="otp_login.php";</script>'; 
			}
			
		}
exit();	
		$_SESSION['alert_sts'] = 'Y';
		$_SESSION['fstlogin']='Y';
		
		if(!empty($_SESSION['current_url'])){
			echo '<script>window.location.href="'.$_SESSION['current_url'].'";</script>';
		}
		else{	

			echo '<script>window.location.href="dashboard.php?sub=dash";</script>';
		}
//		echo '<script>window.location.href="select_finyr.php?sub=list";</script>';        
	}
	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Highway Concession</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

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
  
	body {
		background-image:url("img/background_img.jpg");
		background-repeat: no-repeat;
		background-size: 100% 100%;
	}
		
</style>

</head>
<body style="background-color:skyblue;" background123="img/background_img.jpg">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	  
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
		
	<?php 
	if ($pass_expired=='Y'){
	?>
		</br></br></br>
		<div class="box-body">
		<div class="col-md-3"></div>
		<div class="col-md-6">
		
			<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Users Password Expired...Please click on reset password link to change password...
			</div>
		</div>	
		</div>
	<?php  
	}
	else if ($rowcount==0 and isset($_POST['submit'])){
	?>
		</br></br></br>
		<div class="box-body">
		<div class="col-md-3"></div>
		<div class="col-md-6">
		
			<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Invalid User Id or Password....
			</div>
		</div>	
		</div>
	<?php  
	} 
	else {
		echo "</br></br></br></br></br></br>";
	}
	?>
<?php
//	$sql = "SELECT * FROM `company` WHERE comp_name is not null limit 1";
//	$result = mysqli_query($con, $sql);
//	while($r = mysqli_fetch_object($result)){
//	$compname = $r->comp_name;}
//	$_SESSION['compname'] = $compname;
?>
	
	<div class="col-md-4"></div>
        <div class="col-md-4">
          <!-- Horizontal Form -->
          <div class="box box-info123" style="background-color: grey;color:white;">
            <div class="box-header with-border" style="background-color:#ff6700;" >
              <h3 class="box-title123" style="color:white;text-align:center;background-color:#ff6700;">Highway Concessions P2P</h3>
		    </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form form="form1" class="form-horizontal" action="index_test.php" method="post" onSubmit="disableButton()" >
				<p>&nbsp;</p>
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-3 control-label" style="font-size:16px;">User Id</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="username" style="font-size:16px;" name="username" placeholder="User Id">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label" style="font-size:16px;">Password</label>

                  <div class="col-sm-5">
                    <input type="password" class="form-control" id="password" style="font-size:16px;" name="password" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
					  
					<?php
					if ($pass_expired=='Y'){
					?>
						<p><a href="<?php echo $baseurl . "setting/user_reset.php?sub=1&userid=$uname"?>" ><span style="color:white;font-size:16px;"><u><b>Reset Password </b></u></span></a>
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</p>
					<?php } 
					else { ?>
						  <label style="font-size:16px;">
							<input type="checkbox"> Remember me
						  </label>
					<p>&nbsp;</p>	
					<p><a href="<?php echo $baseurl . "setting/forgot_pass.php?sub=1"?>" ><span style="color:white;font-size:16px;"><u>Forgot Password </u></span></a>
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</p>
					<?php } ?>	
										
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer" style="background-color: grey;color:white;">
<!--                <button type="submit" class="btn btn-default">Cancel</button>-->
                <input type="submit" class="btn btn-primary pull-right" name="submit" style="font-size:16px;" value="Sign in">
              </div>
              <!-- /.box-footer -->
            </form>
		  </div>
          
        </div>
		
<!--		<div class="col-md-4" >
		
			<img src="img/Procurement Workflow Icon.png" width="70%" height="70%"></img>
        
		</div>
-->		
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  

<!-- ./wrapper -->

<script>

function disableButton(button) {
//alert("Hello...");
     button.disabled = true;
     button.value = "submitting...."
     button.form1.submit();

	 
}
</script>

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrbootstrap.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="diapp.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="didemo.js"></script>
</body>
</html>
