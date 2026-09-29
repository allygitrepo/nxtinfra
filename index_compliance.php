<?php
//header("Location:upgradation.php");
//ini_set("session.cookie_httponly", True);
//ini_set("session.cookie_secure", True);
session_start();
error_reporting(0);
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
$kcccc=rand();
function getlBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Trident/i',$u_agent))
        { // this condition is for IE11
            $bname = 'Internet Explorer';
            $ub = "rv";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
        // finally get the correct version number
        // Added "|:"
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
         ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }


function getBrowser() {
    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Mozilla Firefox',
                            '/safari/i'     =>  'safari',
                            '/chrome/i'     =>  'chrome',
                            '/edge/i'       =>  'edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}

function findproxy()	{
	//if ($_SERVER['HTTP_X_FORWARDED_FOR']
	//|| $_SERVER['HTTP_X_FORWARDED']
	//|| $_SERVER['HTTP_FORWARDED_FOR']
	//|| $_SERVER['HTTP_VIA']
	//|| in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
	//|| @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 8))
	//{
	//	$ips = 'detected';
	//}else {
	//$ips = 'notdetected';
	//}
	return 'notdetected';
}
if(findproxy() == 'notdetected')
{
$url=$_SERVER['HTTP_HOST'];
if($url=="gig.lexcomply.com")
{
		header('Location: gig.php');
}
elseif($url=="promila.lexcomply.com")
{
		header('Location: promila.php');
}
else
{
//include_once 'include/configpdo.php';
include_once 'dbcon.php';

function select_activate_user_by_email( $email) {
	$dbh = connect_db();
  try {
    $query = $dbh->prepare( "select * from user where email=? and status='1'" );	
    $query->bindValue( 1, $email );	
    $query->execute();
	$result = $query->fetchAll();
    return $result;
  } catch (Exception $e) {
    return $e->getMessage();
  }

}

function select_user_auth_by_user_id_and_pwd( $user_id,$pwd) {
	$dbh = connect_db();
  try {
    $query = $dbh->prepare( "select * from user_auth where user_id=? and password_key=? and status='1'" );	
    $query->bindValue( 1, $user_id );	
    $query->bindValue( 2, $pwd );	
    $query->execute();
	$result = $query->fetchAll();
    return $result;
  } catch (Exception $e) {
    return $e->getMessage();
  }

}

function select_user_role( $user_id) {
	$dbh = connect_db();
  try {
    $query = $dbh->prepare( "SELECT DISTINCT `Company_id`, `user_role` FROM `user_role` WHERE `user_id`=? and status='1'" );	
    $query->bindValue( 1, $user_id );		
    $query->execute();
	$result = $query->fetchAll();
    return $result;
  } catch (Exception $e) {
    return $e->getMessage();
  }

}

function insert_login_log( $user_id,$ip,$mac,$ids) {
	$dbh = connect_db();
  try {
    $query = $dbh->prepare( "insert into login_log (user_id,ip_add,macadd,s,session) values('$user_id','$ip','$mac','login','$ids')" );	
   /*  $query->bindValue( 1, $user_id );		
    $query->bindValue( 2, $ip );		
    $query->bindValue( 3, $mac );		
    $query->bindValue( 4, 'logout' );		
    $query->bindValue( 5, $ids );	 */	
    $query->execute();
  } catch (Exception $e) {
    return $e->getMessage();
  }

}
if(isset($_REQUEST['msg']))
{

	$msg=$_REQUEST['msg'];
}
if(isset($_SESSION['user_id']) and $_SESSION['user_id']!="" and $_SESSION['user_id']!=null){
	header('Location: main.php');	
	if($_GET['s']=='logout'){
	$_SESSION['user']=null;
	$_SESSION['user_id']=null;
	$_SESSION['company_id']=null;
	session_destroy();	
}
}
else{

if(isset($_POST['login']))
{
	//if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){
	//	
	//	echo "<script>window.location='admin_login.php?msg=reCAPTCHA'</script>";// Captcha verification is incorrect.	
	//	
	//}else{
	//if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
	//	 //your site secret key
    //    $secret = '6LdE3BkTAAAAAKOmMrYWiHi2ofXawO8TljR4sV_E';
	//	//get verify response data
    //    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    //    $responseData = json_decode($verifyResponse);
	//	if($responseData->success)
	//	{
	$email=$_POST['email'];
	$pwd=md5(base64_decode ( $_POST['password']));
	$qrw=select_activate_user_by_email( $email);
	$qrow=$qrw;
	
	if(count($qrow)!=0)
	{
		
		$user_id=$qrow[0]['user_id'];
		$user_name=$qrow[0]['name'];
		$auth=select_user_auth_by_user_id_and_pwd( $user_id,$pwd);
		$authcount=count($auth);
		//echo $authcount." for ".$user_id;
			if($authcount==1)
			{
				/*
			$ss = new SecureSession();
      $ss->check_browser = true;
      $ss->check_ip_blocks = 2;
      $ss->secure_word = 'SALT_';
      $ss->regenerate_id = true;
      $ss->Open();
      $_SESSION['logged_in'] = true;
	  */
			 $_SESSION['user_id']=$user_id;
			 $_SESSION['user_name']=$user_name;
			 $_SESSION['email']=$email;
			 $comsql=select_user_role( $user_id);
			 $comcount=count($comsql);
			 if($comcount!="1")			// if($comcount > "1")	
			 {
				 $ip=$_SERVER['REMOTE_ADDR'];
				 $mac = '';
				 $ids = session_id();
				 insert_login_log( $user_id,$ip,$mac,$ids);
				 header('Location: company.php');
			 }
			 else
			 {
				 $comsql=select_user_role( $user_id);
				 $comrow=$comsql;
				
				 $company_id=$comrow[0]['Company_id'];
	// edit start 				
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
   $browser = 'Internet explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
    $browser = 'Internet explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
   $browser = 'Mozilla Firefox';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
   $browser = 'Google Chrome';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
   $browser = "Opera Mini";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
   $browser = "Opera";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
   $browser = "Safari";
 else
   $browser = 'Something else';
date_default_timezone_set('Asia/Kolkata');
//browser end
$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['userAgent'] = $browser;	
//edit stop 
					
				 $_SESSION['company_id']=$comrow[0]['Company_id'];
				 $ncrow=get_company_by_id( $company_id );
				 $_SESSION['company_name']=$ncrow['name'];
				 $_SESSION['user_role']=$comrow[0]['user_role'];
				 
				 $ip=$_SERVER['REMOTE_ADDR'];
				 $mac = '';
				  $ids = session_id();
				 insert_login_log( $user_id,$ip,$mac,$ids);
				 header('Location: main.php');
			 }
			}
			else{
				if($authcount>1){header('Location: admin_login.php?msg=multi_account_Internal_error');}
				else{header('Location: index_compliance.php?msg=Wrong Credential');}
				
				
			}
	}
	
	
	
	else{
		header('Location: index_compliance.php?msg=Wrong Credential');
	}
	
	//}
	
	//}
	//	else
	//	{
	//		header('Location: index.php?msg=Robot');
	//		
	//	}
	//	}
	//	 else
	//	 {
	//		 header('Location: index.php?msg=reCAPTCHA');
    // 
	//	 }
}
else{
	if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
          <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Compliance Management System </title>
        <link rel="stylesheet" href="LexCompli/css/bootstrap.css">
  <script src="LexCompli/js/bootstrap_js.js"></script>
  <script src="LexCompli/js/jquery.js"></script>
  <link rel="stylesheet" href="LexCompli/css/style.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  
 	<script src="assets/js/jquery.base64.js"></script>
	<script src="assets/js/jquery.base.js"></script>  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="LexCompli/css/font-awesome-4.7.0/css/font-awesome.css">
  <link rel="stylesheet" href="LexCompli/css/font-awesome-4.7.0/css/font-awesome.min.css">
  <!--script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
    <script>
      var recaptcha1;
      var recaptcha2;
      var myCallBack = function() {
        //Render the recaptcha1 on the element with ID "recaptcha1"
        recaptcha1 = grecaptcha.render('recaptcha1', {
          'sitekey' : '6LdE3BkTAAAAALLtRIxLRaUI4aza9IMALLehbd61', //Replace this with your Site key
          'theme' : 'light'
        });
        
        //Render the recaptcha2 on the element with ID "recaptcha2"
        recaptcha2 = grecaptcha.render('recaptcha2', {
          'sitekey' : '6LdE3BkTAAAAALLtRIxLRaUI4aza9IMALLehbd61', //Replace this with your Site key
          'theme' : 'light'
        });
      };
    </script-->
	
	<script type='text/javascript'>
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	
}
function refreshCaptchaforget(){
	
	var imgg = document.images['captchaimgg'];
	imgg.src = imgg.src.substring(0,imgg.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>

</head>
<body>

<div class="jumbotron" style="background-color: white;">
  <div class="container">
    <div class="col-sm-9">
     <img src="logo_a.png" class="img-responsive" />
    </div>
    <div class="col-sm-3">

    </div>
  </div>
</div>

<hr class="hhr" />

  
<div class="container-fluid" id="bnr">    
  <div class="row content">
    
    <div class="col-sm-12"> 
      
      <!-------- ------->
       
       <div class="row vertical-offset-100">
                    <div class="col-md-4 col-md-offset-7">
                        <div class="panel panel-default" id="lform" >
                            <div class="panel-heading">                                

                            </div>
                            <div class="panel-body">
                                <form accept-charset="UTF-8" role="form" class="form-signin"  name="myform"  method="post" autocomplete="off"  onsubmit="return doThis()">
                                    <fieldset>
                                        <label class="panel-login">
                                            <div class="login_result"></div>
                                        </label>
										<div>
						<?php
						if(isset($msg))
						{
							if (preg_match('/[\'^£$%&*()_}{@#~?><>,|=+¬-]/', $msg))
								{
									$msg='Not A Valid login';
								}
								else
								{
							
							if($msg=='logout'){
								$msg='Logout Successful';
							}
							elseif($msg=='sout'){
								$msg='Session Timeout';
							}
							elseif($msg=='Robot'){
								$msg = 'Robot verification failed, please try again.';
							}
							elseif($msg=='reCAPTCHA'){
								   $msg = 'Please fill in the Captcha.';
							}
							elseif($msg=='noc'){
								   $msg = 'Unauthorized Login';
							}elseif($msg=='unauth'){
								   $msg = 'Not A Valid login';
							}
							elseif($msg=='role'){
								   $msg = 'Always fill proper information. Lets try again.';
							}
							else
							{
								$msg=$msg;
							}
							  ?><div style="color:black"><h4><strong></strong><em><?=$msg?></em></h4></div>  <?php	
							
								}
						}
						?>
						
						</div>
                                         <div class="form-group">
                                        	<label class="lbl" style="color:black">E-mail</label>
                                            <div class="input-group">
                                            <span  class = "input-group-addon" id="#"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                            <input type="email" name="email" class="form-control" id="#" placeholder="Enter Email" required>
                                            </div>
                                          </div>
                                          
                                          <div class="form-group">
                                        	<label class="lbl" style="color:black">Password</label>
                                            <div class="input-group">
                                            <span  class = "input-group-addon" id="#"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                            <input type="password" name="password" class="form-control" id="#" placeholder="password" required>
                                            </div>
                                          </div>
                                          
                                          <div class="checkbox hidden" hidden>
                                                <label>
                                                    <input name="remember" type="checkbox" value="Remember Me" class="btn-lg"> Remember Me
                                                </label>
                                           </div>
										   <!--div class="form-group" id="recaptcha1"></div-->

                                          
                                        	<input type="hidden" id="#" value="Sign in" name="login">
												<button class="btn btn-success btn-block" type="submit" >Sign in</button>
											<br>
											<a href="https://compliance.athaang.in/login_sso.php" class="btn btn-warning btn-block">Login with Email</a>
                                        <p class="text-center" style="color:black"> <a href="javascript:void(0);" class="pull-right mt-10" data-toggle="modal" data-target="#myModal" data-options="splash-2 splash-ef-14" style="color:black">Forgot Password</a> </p>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
       
      <!--------- ------->

      
    </div>
    
  </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-md">

                <div class="modal-content">

                    <div class="modal-header">

                        <h3 class="modal-title custom-font">Forgot Password..!</h3>
						

                    </div>
					<div id="msg"></div>

                    <div class="modal-body">

                        <form name="form1" id="form1"  method="post" action="" class="form-validation mt-20" autocomplete="off">



                        <p class="help-block text-left">

                            <strong>Enter your e-mail address below to reset your password.</strong>

                        </p>



                        <div class="form-group">

                            <input class="form-control underline-input" placeholder="Email" type="email" name="mail">

                        </div>
						 <div class="form-group" hidden>

                            <input class="form-control underline-input" placeholder="Mobile Number" type="text" name="mobile">

                        </div>
						<!--div class="form-group" >

                            <div id="recaptcha2"></div>
                        </div-->

					<div class="form-group">
 <!--       
                                            <div class="input-group">
                                            <span  class = "input-group-addon" id="#" style="padding:0px"><img src="captcha.php?rand=<?php echo $kcccc;?>" id='captchaimgg'></span>
                                            <input id="captcha_code" name="captcha_code" type="text" class="form-control" style="height:42px;"											required>
                                            </div>
											Can't read the image? click <a href='javascript: refreshCaptchaforget();'>here</a> to refresh.
											-->
                                          </div>	



                    

                    </div>

                    <div class="modal-footer">			
	<input type="hidden" name="submitz" id="submitz" value="submit">
                       <!-- <input type="submit" name="submit" class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" value="Submit" >-->		
                       <input type="button"  class="btn btn-success btn-ef btn-ef-3 btn-ef-3c" value="Submit"  onClick="document.forms.form1.submit();this.disabled=true;" >

                        <button class="btn btn-lightred btn-ef btn-ef-4 btn-ef-4c" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</button>

                    </div>

                </div>
			</form>
            </div>

        </div>
<footer class="container-fluid text-center">
<div class="container">

</div>
</footer>

</body>
</html>
<?php

}}
?>
<?php 


function resend_activation_mail_send( $email, $otp, $token, $company3, $role3){
	$absolute_url 	=	full_url( $_SERVER );
	$host 			=	'lexcomply.com';
	$sender 	 	= 	"no-reply@".$host;
	$namesa		 	= 	"LexComply";
	$actlink		=	$absolute_url."/newuser/activate.php?token=".$token;
	$subject		=	"Athaang | Activation Mail";
	$headers 		= 	"From: $namesa "."<".$sender.">\r\n";
	$headers	.= 	'Reply-To: support@'.$host."\r\n" ;
	$headers  	.= 	'MIME-Version: 1.0' . "\r\n";
	$headers 	.= 	'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	$msg = '<div style="margin:70;padding:0"><center><font size="6px">Thank You & Congratulations!</font></center><br><p>'.$email.'</p><p>We are pleased to welcome you as <b>Saarthi </b>for steering the Compliance Management System of your esteemed Organization.</p><p>Your registered details are under:</p><p style="margin:30;"><table border="1" width="600px"><tr><td width="300px"><b>Company Name</b></td><td>'.$company3.'</td></tr><tr><td width="300px"><b>One Time Password (OTP)</b></td><td>'.$otp.'</td></tr><tr><td width="300px"><b>Role</b></td><td>'.$role3.'</td></tr></table></p><p>To activate your account please <a href="'.$actlink.'">ClickHere</a> or visit <u>'.$actlink.'</u></p><p>On activation of your account, you will be automatically admitted to <b> Training and Appreciation</b>programme.</p><p><strong><u>Key Suggestions</u></strong></p><p style="margin:30;"><ol><li>Please change your password on first login and after every 30 days.</li><li>Personal and Login details should not be shared with any person. </li><li>Please ensure that documents uploaded are not obscene/un-desirable/prohibited.</li><li>Adhere to the policies of the Company and LexComply while using the tool.</li></ol></p><p>/p><p>We again thank you for making us part of your Journey . We hereby pledge to serve you as per the Best Industry Practices and seek to learn from you . </p><br><br><p>Best Regards<br>Athaang</p><br>';	
	//mail($email,$subject,$msg, $headers, "-f $sender");
	mailp_send($email,$subject,$msg);
}


$otp = rand(194567,987654);
//include('include/configpdo.php');
if(isset($_REQUEST['submitz']))
{
	//if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){
	//	
	//	echo "<script>window.location='index.php?msg=reCAPTCHA'</script>";// Captcha verification is incorrect.	
	//	
	//}else{
	//if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
	//	 //your site secret key
    //    $secret = '6LdE3BkTAAAAAKOmMrYWiHi2ofXawO8TljR4sV_E';  
	//	//get verify response data
    //    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    //    $responseData = json_decode($verifyResponse);
	//	if($responseData->success)
	//	{
	$mail=$_REQUEST['mail'];
	$val=check_email_type ($mail);
if($val==1)
{
	//header('Location: index.php?msg=Enter Email.');
	echo "<script>window.location='index_compliance.php?msg=Enter Email.'</script>";
}
elseif($val==2)
{
	//header('Location: index.php?msg=Enter valid email.');
	echo "<script>window.location='index_compliance.php?msg=Enter valid email.'</script>";
}
else
	{
		$dbh= connect_db ( );
	$query = $dbh->query("SELECT * FROM user WHERE email='$mail'");
	//$query->bindValue( 1, $mail );	
    $query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
	
	 $email=$row['email'];
	  $sta=$row['status'];
	 
	 
	if(strtolower($email)==strtolower($mail) && $sta==1)
	{
		
		forget_pass($mail,$otp,$sta);
		//header('Location: index.php?msg=Go to your email for reset password link.');
		echo "<script>window.location='index_compliance.php?msg=Please Go to your email for reset password link.'</script>";
	}
	elseif(strtolower($email)==strtolower($mail) && $sta==0)
	{
		
		$otp3=$row['otp'];
		$token3=$row['token'];
		$user_id3=$row['user_id'];
		$queryw = $dbh->query("SELECT company.name as c_name, user_role.user_role as roles  FROM company inner join user_role on user_role.Company_id = company.company_id WHERE user_role.user_id=$user_id3 order by user_role.id DESC ");
		$queryw->execute();
		$row3 = $queryw->fetch(PDO::FETCH_ASSOC);
		$company3 =$row3['c_name'];
		$role3=$row3['roles'];
		
		//forget_pass($mail,$otp,$sta);
		resend_activation_mail_send( $email, $otp3, $token3, $company3, $role3);
		echo "<script>window.location='index_compliance.php?msg=Your Email is not yet activated. Please Go to your email for activate email'</script>";
	}
	else
	{
		//header('Location: index.php?msg=Your Email Not valid');
		echo "<script>window.location='index_compliance.php?msg=You are not a valid user'</script>";
		
	}
	}
	
	//}
	//}
	//	else
	//	{
	//		//header('Location: index.php?msg=You are robot.');
	//		echo "<script>window.location='index.php?msg=You are a robot.'</script>";
	//		
	//	}
	//	}
	//	 else
	//	 {
	//		//header('Location: index.php?msg=Please fill the captcha.');
	//		echo "<script>window.location='index.php?msg=Please fill the captcha.'</script>";
    // 
	//	 }
}
}
}
else {
	?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->



    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Lexcomply-Proxy Find</title>
        <link rel="icon" type="image/png" href="https://lexcomply.com/siteadmin/admin_dashboard/img/logo/favicon-min.png" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
        <!-- vendor css files -->
        <link rel="stylesheet" href="https://lexcomply.com/assets/css/vendor/bootstrap.min.css">
        <link rel="stylesheet" href="https://lexcomply.com/assets/css/vendor/animate.css">
        <link rel="stylesheet" href="https://lexcomply.com/assets/css/vendor/font-awesome.min.css">
        <link rel="stylesheet" href="https://lexcomply.com/assets/js/vendor/animsition/css/animsition.min.css">
        <!-- project main css files -->
        <link rel="stylesheet" href="https://lexcomply.com/assets/css/main.css">
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="https://lexcomply.com/assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <!--/ modernizr -->
<!-- Start FreeUsersOnline Code - Do Not Modify! -->
<style>
.moved {
    position: absolute;
    right: 5%;
	top:8%;
    z-index: 0;
}
.powered {
    position: absolute;
	top:8%;
    z-index: 0;
}
</style>
    </head>

    <body id="minovate" class="appWrapper">
     <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">
	<div class="page page-core page-login bg-white" >
                <div class="text-center"><h3 class="text-light text-info"></br><span class="text-lightred">Compliance</span> Management System</h3></div> 
                <div class="container  bg-white  text-justify text-md">
				<p>&nbsp;</p>    
                    <p>Dear User,</p>
<p>You are using proxy Server. Kindly login with simply site.  
</p>
<p>Thank you,</p>
<p>Technical Team LexComply.</p>
                    <hr class="b-3x">
					<img class="powered" src="upgradation.png" width="40px" title="RSJ Lexsys Private Limited" hidden>
                </div>
            </div>
        </div>
        <!--/ Application Content -->
        <!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script>
        <script src="https://lexcomply.com/assets/js/vendor/bootstrap/bootstrap.min.js"></script>
        <script src="https://lexcomply.com/assets/js/vendor/jRespond/jRespond.min.js"></script>
        <script src="https://lexcomply.com/assets/js/vendor/sparkline/jquery.sparkline.min.js"></script>
        <script src="https://lexcomply.com/assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="https://lexcomply.com/assets/js/vendor/animsition/js/jquery.animsition.min.js"></script>
        <script src="https://lexcomply.com/assets/js/vendor/screenfull/screenfull.min.js"></script>	
        <!--/ vendor javascripts -->
        <!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= -->
        <script src="https://lexcomply.com/assets/js/main.js"></script>
        <!--/ custom javascripts -->	
    </body>
</html>
	<?php 
}
?>
