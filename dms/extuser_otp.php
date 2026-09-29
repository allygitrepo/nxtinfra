<?php
	session_start();
	
	include "../baseurl.php";
	require "../dbcon.php";
	
	$modulePath = "dms/";
	$rowcount='0';
    $_SESSION['timeout']=time();
	
	// 10 mins in seconds
    //echo $_POST['submit'];

if(isset($_POST['submit'])){

   	$userid 	= $_SESSION['user'];
	$otp    	= $_POST['otp'];
	$inward_no 	= $_POST['inward_no'];
	$unq_no 	= $_POST['unq_no'];
	
	$tdate = date("Y-m-d");
	if(!empty($otp)){
		$sql      = " select count(*) cnt, email_id from external_user_mail where inward_no = '$inward_no' and otp='$otp' and tdate = '$tdate' ";
	//echo $sql."<br>";
	//exit();
	
		$result   = mysqli_query($con, $sql);
		$rowcount = mysqli_num_rows($result);
		$r2 		= mysqli_fetch_array($result);
		$cnt		= $r2['cnt'];
		$email_id	= $r2['email_id'];
		
		if( $cnt>0 ){
			$sql      = " update external_user_mail set one_time = '1' where inward_no = '$inward_no' and otp='$otp' and tdate = '$tdate' ";
			mysqli_query( $con, $sql );
		}

		//echo $sql."<br>";
		//exit();
    //$baseurl1 = $baseurl.$modulePath.'shared_document_ext.php?sub=lnk&inward_no='.$inward_no;
	//echo $baseurl1;		
	//echo $cnt;
	//exit();

		if( $cnt!=0 ){
			
			$sql = " INSERT into dms_log_extmail ( doc_type, inward_no, link_open_date, email_id, remarks ) 
					values ( 'IN', '$inward_no', now(), '$email_id', '' ) ";
			mysqli_query($con, $sql);
			 
			$baseurl1 = $baseurl.$modulePath.'shared_document_ext.php?sub=lnk&inward_no='.$inward_no;
	//echo $baseurl1;
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
	}
}
else if( $_GET['sub']=='edit' && $_GET['inward_no'] >0 ){

	$inward_no 	= $_GET['inward_no'];
	$unq_no 	= $_GET['unq_no'];
//	$inward_no = 100005;
	require "extmail.php";

//	echo "<H1>" . ++$I . "</H1>";

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
	if ($rowcount==0 and isset($_POST['submit'])){
	?>
		</br></br></br>
		<div class="box-body">
		<div class="col-md-3"></div>
		<div class="col-md-6">
		
			<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Invalid OTP....
			</div>
		</div>	
		</div>
	<?php  
	} 
	else {
		echo "</br></br></br></br></br></br></br></br>";
	}
	?>
	
	<div class="col-md-4"></div>
        <div class="col-md-4">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
			  <h3 class="box-title">Highway Concession - DMS</h3>
              <h3 class="box-title">Enter OTP, You have received OTP in your Email box. </h3><br>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="extuser_otp.php" method="post">
              <div class="box-body">
					
					<input type="hidden" name="inward_no" value="<?php echo $inward_no;?>" >
					<input type="hidden" name="unq_no" value="<?php echo $unq_no;?>" >
					
					<div class="form-group">
						<label for="inputOTP" class="col-sm-3 control-label">Enter OTP</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" autocomplete="off" id="otp" name="otp" value="" >
						</div>
					</div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <input type="submit" class="btn btn-info pull-right" name="submit" value="Sign in">
              </div>
              <!-- /.box-footer -->
            </form>
		  </div>
          
        </div>
		
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
<!-- ./wrapper -->

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


