	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	$user   = $_SESSION['user'];
	 
	$mail_to = 'ravindra@syminfotech.com';

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

	$mail->Host 		= $host;
	$mail->SMTPAuth = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	//$user_email = 'ranganathan.n@athaanginfra.in';
	//$user_name   = 'Ranganathan N';
	
	$mail->addReplyTo($user_email, $user_name);
	$mail->addAddress($user_email, $user_name);
	
	$mail->addAddress($mail_to, 'Hello !');
	
	$mail->Subject = 'Athaang Workflow - Budget  Reclculation schedule Done';
	
	$message = "Status : " . $status;
	$body .= 'Hi ' . "<br><br>";
	$body .= "Please find here Budget Reclculation schedule Done";
	$body .= "<br><br>"."Thank & Regards";

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$ix='';
	}
//exit();
