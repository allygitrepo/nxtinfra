<?php
	session_start();
	require "dbcon.php";
	
	$rowcount='0';	
// 10 mins in seconds

	$_SESSION['timeout']=time();
	
	$pass_expired 	= '';
	$role 			= 0;
	
if(isset($_POST['submit'])){
	
	$username = $_POST['username'];
	$password = $_POST['password'];

		
/* 	$link = ldap_connect('10.0.0.11',389 ); // Your domain or domain server
	//$link = ldap_connect('CN=Athaangadmin,CN=Users,DC=Athaang,DC=local'); // Your domain or domain server
	if(! $link) {
		// Could not connect to server - handle error appropriately
		echo "Could not connect to server";
	}

	ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD
 */
	// Now try to authenticate with credentials provided by user
/* 	if (! ldap_bind($link, 'athaangadmin@athaang.local', 'P@ssw00rd@121!!' )) {
		// Invalid credentials! Handle error appropriately
		echo "Invalid Credential...###3..";
		
		exit();
	}
	else {
		
		echo "Bind Successfully..... ";

	}
	 */

//Commented for testing
/*  	if(empty($password)){
		$msg = "Invalid Credential...###4..";
		$rowcount=0;	
		echo '<script>window.location.href="index.php?rowcount=0";</script>';
		exit();
	}	 */
//Commented for testing	

//$sql = " select * from sma_user where userid = '$username' and ps = '$password' ";
//	if (! ldap_bind($link, $username, $password )) {
		// Invalid credentials! Handle error appropriately
		
		$sql = " select * from sma_user where userid = '$username' and ps = '$password' ";	
		$qry = mysqli_query($con, $sql);
		$rowcount = mysqli_num_rows($qry);
		if($rowcount==0){	
		//echo $sql; exit();
			echo "Invalid Credential...###4..";
			$rowcount=0;
			echo '<script>window.location.href="index.php?rowcount=0";</script>';
			exit();
		}
		$bind 		= 'Y';
		
/* 	}
	else {
		
		//echo "Bind Successfully.....OK ";
		$rowcount 	= 1;
		$bind 		= 'Y';
		
	}
 */
	
	if($rowcount >0 && $bind == 'Y'){
		
		$bind ='Y';
	
			$uname 		= $_POST['username'];
			
			$role		= '';
			
			if($uname == 'athaangp2p@athaanginfra.in'){
				$uname = 'admin';
			}	
			$sql = " select * from sma_user where userid = '$uname'  ";
		//	echo $sql; exit();

			$qry = mysqli_query($con, $sql);
			$rowcount = mysqli_num_rows($qry);
			if($rowcount==0){	
				echo "Invalid Credential...###4..";
				$rowcount=0;
				echo '<script>window.location.href="index.php?rowcount=0";</script>';
				exit();
			}
			while($r = mysqli_fetch_object($qry)){
				$username 		= $r->userid;
				$user_name_by 	= $r->username;
				$id		 		= $r->id;
				$role	 		= $r->role;
				$primary_role	= $r->primary_role;
				$department		= $r->department;
				$otp_send	 	= $r->otp_send;
				$accountant_role = $r->accountant_role;
				$user_email	= $r->email;
				$company_id = $r->company_id;
				$user_category = $r->user_category;
				
				$todays_date = date("Y-m-d");
				
			}
			if(empty($role)){
				$role=0;
				$user_role =0;
			}	
			$s="select * from sma_role where id in ( $role )";
			$sql = mysqli_query($con, $s);
		
			$role_array = array();
			while(	$r = mysqli_fetch_object($sql)){
				$role	 	= $r->role;
				$role_array[] = $role;
				$role_id	= $r->id;
				$role_id_a  .= $r->id.',';
			}
			$role_id_a  .= '0';
			
			$_SESSION['user']  = ucfirst(strtolower($username));
			$_SESSION['usrid'] = $id;
			$_SESSION['comid'] = $company_id;
			$_SESSION['role']  = $role;
			$_SESSION['role_id_a']  = $role_id_a;
			$_SESSION['role_array']  = $role_array;
			$_SESSION['role_id']  = $role_id;
			$_SESSION['primary_role']  = $primary_role;
			
			$_SESSION['department']  = $department;
			$_SESSION['user_category'] = $user_category;
			$_SESSION['user_name_by'] = $user_name_by;
			$_SESSION['accountant_role'] = $accountant_role;
			unset($_SESSION['mob']);

			$menuonly = array();
			$dashboard = array();
			
			$sql ="SELECT * FROM `useraccess` where user_role in ( $role_id_a ) and menuonly = 'Y' order by menu_id , id";
			$rs1 = mysqli_query($con, $sql);
			$r = mysqli_fetch_array($rs1);
			$user_role = $r['user_role'];
			
			if(empty($user_role)){
				$user_role =0;
			}
			$s="select * from sma_role where id in ( $user_role )";
			$sql = mysqli_query($con, $s);
			$r = mysqli_fetch_object($sql);
			$role_a	 			= $r->role;
			$_SESSION['role_a'] = $role_a;
			
			$sql ="SELECT * FROM `useraccess` where user_role in ( $primary_role ) and menuonly = 'Y' order by menu_id , id";
		//echo $sql. "<BR>";// exit();

			$rs1 = mysqli_query($con, $sql);
			$i = 0;
			while($r = mysqli_fetch_object($rs1)){
				//$i = $i + 1;
				$i = $r->menu_id;
				$readonly[$i] 	= $r->readonly;
				$writeonly[$i] 	= $r->writeonly;
				$menuonly[$i] 	= $r->menuonly;
				$dashboard[$i] 	= $r->dashboard;
				$menu_id[$i] 	= $r->menu_id;
			}

//print_r($readonly)."<BR>";
//print_r($writeonly);exit();
			
			$_SESSION['menu_id']  = $menu_id;
			$_SESSION['menuonly']  = $menuonly;
			$_SESSION['readonly']  = $readonly;
			$_SESSION['writeonly']  = $writeonly;
			$_SESSION['dashboard']  = $dashboard;
			
			$uname = $_SESSION['user'];
			$otp   = $_POST['otp'];
			$tdate = date("Y-m-d");
			
			if($_GET['rowcount']){
				$rowcount=$_GET['rowcount'];
			}	
			if($rowcount!=0 ){
					
				$_SESSION['alert_sts'] = 'Y';
				$_SESSION['fstlogin']='Y';
				
				echo '<script>window.location.href="dashboard_athang.php?sub=dash";</script>';
				
		//		echo '<script>window.location.href="select_finyr.php?sub=list";</script>';        
			}
	
	}

}

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

  .btn-primary {
    background-color: #ff8c00;
    border-color: #ff8c00;
    color: #FFF; }
  .btn-primary:hover,  .btn-primary:focus {
      border-color: #e55317;
      background-color: #e55317;
      color: #FFF; }
  
	body {
		background-image:url("img/background_img1.jpg");
		background-repeat: no-repeat;
		background-size: 100% 100%;
	}
		
</style>

</head>
<body style="background-color123:skyblue;" background123="Athaang_screen.jpg">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	 
	<!--<img src="img/background_img.jpg" > -->
	 
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        
        <!--/.col (left) -->
        <!-- right column -->
		
	
<?php
//	$sql = "SELECT * FROM `company` WHERE comp_name is not null limit 1";
//	$result = mysqli_query($con, $sql);
//	while($r = mysqli_fetch_object($result)){
//	$compname = $r->comp_name;}
//	$_SESSION['compname'] = $compname;
	echo "</br></br></br></br></br></br>";
?>
	
	<div class="col-md-3"></div>
        <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-info123" style="background-color: #222426;color:white;">
            <div class="box-header with-border" style="background-color:green;" >
              <h3 class="box-title123" style="color:white;text-align:center;background-color:#green;">NXT Infra P2P Application</h3>
		    </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form form="form1" class="form-horizontal" action="index.php" method="post" onSubmit="disableButton()" >
				<p>&nbsp;</p>
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-3 control-label" style="font-size:16px;color:white;">User Id</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="username" style="font-size:16px;" name="username" placeholder="User Id">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label" style="font-size:16px;color:white;">Password</label>

                  <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" style="font-size:16px;" name="password" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
					  
					
						  <label style="font-size:16px;color:white;">
							<!--<input type="checkbox"> Remember me-->
						  </label>
								
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer" style="background-color: green;color:white;">
<!--                <button type="submit" class="btn btn-default">Cancel</button>-->
                <input type="submit" class="btn btn-primary pull-right" name="submit" style="font-size:16px;" value="Sign in">
              </div>
			  
              <!-- /.box-footer -->
            </form>
		  </div>
          
        </div>
		
		<?php 
	if ($rowcount==0 and isset($_GET['rowcount'])){
	?>
		</br>
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
		echo "</br>";
	}
	?>
	
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
