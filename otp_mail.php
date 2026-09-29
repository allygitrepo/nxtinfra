	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	
	if( $_GET['res']=='R' ){
		require "dbcon.php";

		$user   = $_SESSION['user'];
		$s 		= " select * from sma_user where userid = '$user' ";
		$sql 	= mysqli_query($con, $s);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$user_name_by 	= $r->username;
			$user_email		= $r->email;
			echo $user_email;
		}

			$six_digit_random_number = mt_rand(100000, 999999);
			$tdate = date("Y-m-d");
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$one_time = '';
					
			$sql  = "insert into user_login(`userid`, `otp`, `tdate`, `ipaddress`) values( '$username', '$six_digit_random_number', '$tdate', '$ipaddress' )";
			mysqli_query($con, $sql);	
	}
	
	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require 'PHPMailer-master/PHPMailerAutoload.php';
	
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
	$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

	//Set who the message is to be sent to BCC
	//$mail->addAddress($mail_to, 'First Gmail');
	$mail->addBCC($mail_to, 'First Gmail');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($user_email, $user_name_by);
	
	//ranganathan.n@highwayconcessions.com
	$mail->addAddress($user_email, $user_name_by);
	//$mail->addAddress('it.support@highwayconcessions.com', 'IT Support');
	//$mail->addAddress('ranganathan.n@highwayconcessions.com', 'IT Support');
	$mail->addCC('ranganathan.n@highwayconcessions.com', 'IT Support');
	
	//Set the subject line
	$mail->Subject = 'HC Workflow - One Time Password for '. $user_name_by;

	$body .= 'Hi '. $user_name_by.",<br><br>";
	$body .= "Please find here One Time Password <br><br>";
	$body .= '<b>' . $six_digit_random_number . '</b><br><br>';
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	//	echo "Message sent!";
		$i='';
	}
	
	if( $_GET['res']=='R' ){
		echo '<script>window.location.href="otp_login.php";</script>';
	}	
//exit();
