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
			$email_id		= $_POST['email_id']; 
			
			$sql="select * from sma_user where userid='$userid' or email='$email_id' ";
	//echo $sql;
	//exit();
			$result = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($result);
			//$password	= $r->password;
			$password	= $r->ps;
			$userid	 	= $r->userid;
			$user_name 	= $r->username;
			$user_email	= $r->email;
			
			$rowcount = mysqli_num_rows($result);
			if($rowcount ==0){
				$value = "User id not available...";
		?>

				</br></br></br>
				<div class="box-body">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							User id / Email not available...
							<a href="../index.php" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Go to login page </a>	
					</div>
				</div>	
				</div>
		<?php
		
			}
			else {
			
				$mail_to = 'ravindra.gandhile@gmail.com';

				//require '../PHPMailerAutoload.php';
				require '../PHPMailer-master/PHPMailerAutoload.php';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer;
				// optional
				// used only when SMTP requires authentication  
				
				$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
				$mail->SMTPAutoTLS = false;
				
				$mail->IsSMTP();
				$mail->Host = "outlook.office365.com";
				$mail->SMTPAuth = true;
				//$mail->SMTPSecure = "ssl";
				$mail->Username = 'workflow@highwayconcessions.com';
				$mail->Password = 'highway@1234';
				$mail->Port       = "587";                    // set the SMTP port
				
				// Set PHPMailer to use the sendmail transport
				//$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

				//Set an alternative reply-to address
				//$mail->addReplyTo('replyto@example.com', 'First Last');
				
			//$mail->addReplyTo($user_email, $user_name);
				
				$mail->addAddress($user_email, $user_name);
				
				//$mail->addAddress($mail_to, $user_name);
				
			//Set the subject line
				$mail->Subject = 'HC Workflow - Request for forgot password '. $user_name;
				
				
				$body .= 'Hi '. $user_name. "<br><br>";
				$body .= "Please find here your password.<br><br>";
				$body .= $password . "<br><br>";
				$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

				$mail->MsgHTML($body);

				
				//send the message, check for errors
				if (!$mail->send()){
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					//echo "Message sent!";
					$i='';
				}
						
			//echo ' <script>window.location.href="../index.php";</script> ';
			
	?>

	</br></br></br>
		<div class="box-body">
		<div class="col-md-3"></div>
		<div class="col-md-7">
		
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Email send to your email id successfully...
				<a href="../index.php" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Go to login page </a>	
			</div>	
		</div>
	
		
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
?>
	
	<div class="col-md-3"></div>
        <div class="col-md-6">
          <!-- Horizontal Form -->
          <div class="box box-info" style="background-color: grey;color:white;">
            <div class="box-header with-border">
              <h3 class="box-title" style="color:white;">Forgot Password</h3><br>
			  <h3 class="box-title" style="color:white;">Highway Concessions P2P</h3>
            </div>
            
				<form class="form-horizontal" action="forgot_pass.php" method="post">
				
					<div class="box-body">
						
						<div class="form-group">
							
							<label class="col-lg-3 control-label">User Id</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="userid" name="userid" autocomplete="off" value="" >
							</div>
							
							<span id="getuname"></span>
							
						</div>
						
						
						<div class="form-group">
							
							<label class="col-lg-3 control-label">OR</label>
							
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-3 control-label">User Email Id</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="email_id" name="email_id" autocomplete="off" value="" >
							</div>
							
							<span id="getuname"></span>
							
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


