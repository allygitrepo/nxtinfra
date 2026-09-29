	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   		= $_SESSION['user'];
	$user_name_by 	= $_SESSION['user_name_by'];
	$userid   		= $_SESSION['usrid'];

	//$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();
	include("../dbcon.php");
	$sql="SELECT * from smtp_dtl";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$host 			=	$row['host'];
	$host_name		=	$row['host_name'];
	$host_username 	=	$row['username'];
	$host_password  =	$row['password'];
	$host_port 		=	$row['port'];
	$disclaimer 		=	$row['disclaimer'];

	$mail->Host 		= $host;
	$mail->SMTPAuth = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	$mail->setFrom($host_username, $host_name);

	$sql = " select * from sma_user where id = '$userid' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$from_by 			= $r2['userid'];
		$from_name 			= $r2['username'];
		$from_by_id 		= $r2['id'];
		$from_email 		= $r2['email'];
		$from_mobile 		= $r2['mobile_no'];
		$from_company_work	= $r2['company_work'];
		
		
// Today Ravi	$mail->addAddress($user_email, $user_name);
	$mail->addReplyTo($user_email, $user_name);

	//$mail->addAddress($mail_to, 'Test Name');
	
	$mail->addAddress($user_email, $user_name);
	if(!empty($ac_email)){
		$mail->addAddress($ac_email, $ac_email_name);
	}
	
	$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	
	$mail->Subject = 'Athaang P2P - Petty Cash Expenses for review requested by '. $from_name;
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $user_name. "<br><br>";
	$body .= "Please find here ". $msg1. " for your review, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
	if(!empty($remarks)){
		$body  .= "Remarks : ". $remarks . "<BR>";
	}
	
	$body .= $baseurl1 . "<br><br>";
	
		
	$doctype  = 'PC';
	$ap_id	  = $re_id;
	include "../workflow_process_to_mail.php";
	
		$sql = "SELECT * from company where comp_id = '$from_company_work' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
								
		$comp_name = $r2['comp_name'];	

	$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards"."<br>". $from_name."<br>".
				$comp_name . "<BR>".
				'Email - ' .$from_email . "<BR>".
				'Mobile- ' .$from_mobile . "<BR>";

	$body .= "<br><br>".$disclaimer;
	
	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
//	$mail->addAttachment('pdf/document_transmittal.pdf');

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}
//exit();
