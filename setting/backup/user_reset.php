<?php
	session_start();
	require "../dbcon.php";
	require "../baseurl.php";
	
	$rowcount='0';
	
// 10 mins in seconds

	$_SESSION['timeout']=time();


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Athaang </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  
  <link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<script type="text/javascript">

function cf(){
   
    var a = new Array();
    a[0] = document.getElementById('password').value;
    a[1] = document.getElementById('cpassword').value;


    var b = new Array();
    b[0] = "<span style='color:red'>Please type your password!</span>";
    b[1] = "<span style='color:red'>Please confirm your password!</span>";


    var divs = new Array("mpassword", "mcpassword");
   
       
        for(i in a){
       
            var error = b[i];
            var div = divs[i];
           
            if(a[i]==""){
                document.getElementById(div).innerHTML = error;
            }else{
                document.getElementById(div).innerHTML = "OK!";
            }
               
        }
       
    }
   
   
function pass(){

    var first = document.getElementById('password').value;
    var second = document.getElementById('cpassword').value;

    if(second==first){
        document.getElementById('mcpassword').innerHTML = "OK!";
    }else{
        document.getElementById('mcpassword').innerHTML = "<span style='color: red'>Your passwords don't match!</span>";

	}
   
}

</script>

</head>

<body style="background-color:skyblue;" background="../img/background_img.jpg">
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
	
	if (isset($_POST['submit'])){
	
			$userid			= $_POST['userid']; 
			
			$s="select * from sma_user where userid='$userid' ";
	
			$sql = mysqli_query($con, $s);
			$rowcount = mysqli_num_rows($sql);
			
			$rw = mysqli_fetch_array($sql);
			$password_expired_days	= $rw['password_expired_days'];

			if($rowcount ==0){
				$value = "User id not available...";
		?>

				</br></br></br>
				<div class="box-body">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							User id not available...
							<a href="../index.php" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Go to login page </a>	
					</div>
				</div>	
				</div>
		<?php
		
			}
			else {
			
				$ps				= $_POST['password'];
				$password		= md5($_POST['password']);
	//password_expired_days
	//date('Y-m-d', strtotime($Date. ' + 1 day'))
				$today_date		= date("Y-m-d");
				$password_expired_date	= date('Y-m-d', strtotime($today_date. ' + ' . $password_expired_days. ' day'));
				
				$sql="update sma_user set password = '$password', ps = '$ps', password_expired_date = '$password_expired_date' where userid='$userid'";

				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
			//echo ' <script>window.location.href="../index.php";</script> ';
			
	?>

	</br></br></br>
		<div class="box-body">
		<div class="col-md-3"></div>
		<div class="col-md-6">
		
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Password reset successfully...
				<a href="../index.php" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Go to login page </a>	
			</div>	
		</div>
		
<!--	
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
-->
		
	<?php  
	
			}
	} 
	else {
		echo "</br></br></br></br></br></br>";
	
	?>
<?php
	$sql = "SELECT * FROM `company` WHERE comp_name is not null limit 1";
	$result = mysqli_query($con, $sql);
	while($r = mysqli_fetch_object($result)){
	$compname = $r->comp_name;}
	$_SESSION['compname'] = $compname;
	
	$userid = $_GET['userid'];
?>
		
		<div class="box-body">
		<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Users Password Expired...Please click on reset password link to change password...
				</div>
			</div>	
		</div>
		
	<div class="col-md-3"></div>
        <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-info" style="background-color: grey;color:white;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:white;">Reset Password</h3><br>
			  <h3 class="box-title" style="color:white;">Athaang P2P</h3>
            </div>
            
				<form class="form-horizontal" action="user_reset.php" method="post">
				
					<div class="box-body">
						
						<div class="form-group">
							
							<label class="col-lg-3 control-label">User Id</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="userid" name="userid" readonly value="<?php echo $userid ?>" >
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-3 control-label">Password</label>
							<div class="col-md-4">
								<input type="password" class="form-control" id="password" name="password" placeholder="" autocomplete="off" onkeyup="cf()" value="<?php echo $password;?>">
								<span class="help-block" style="color:white">Enter new password</span>
								<div id="mpassword" style="color:white"></div>
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-3 control-label">Confirm Password</label>
							<div class="col-md-4">
								<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="" autocomplete="off" onkeyup="pass()" value="<?php echo $cpassword;?>">
								<span class="help-block" style="color:white">Repeat password</span>
								<div id="mcpassword" style="color:white"></div>
							</div>
						</div>
					
					</div>
					
					<div class="box-footer" style="background-color: grey;color:white;">
						<a href="../index.php" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Go to login page </a>	
						<input type="submit" class="btn btn-info pull-right" name="submit" value="Submit">
					</div>
				  
				</form>
				
		  </div>
          
        </div>
		
		<?php
			}
		?>
		
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

<!-- ./wrapper -->

<script>

	function getuname(id){
	
		var sub    = 'sub1';

//alert(company_id);		
		var strURL = "u_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getuname').html(result);
		});
		
	}

</script>

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrbootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../diapp.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../didemo.js"></script>
</body>
</html>


