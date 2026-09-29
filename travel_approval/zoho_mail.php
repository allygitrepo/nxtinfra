	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	session_start();
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	 
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

	//Set an alternative reply-to address
	$user_email = 'ranganathan.n@athaanginfra.in';
	$user_name  = 'ranganathan.n';
	$mail->addReplyTo($user_email, $user_name);
	//Set who the message is to be sent to	
	$mail->addAddress($user_email, $user_name);

	$mail->addBCC($mail_to, 'First Gmail');
	
	//$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
		
	//Set the subject line
	$mail->Subject = 'Athaang P2P - Expenses Zoho scheduler processed';
	
	$message = "Status : " . $status;
	$body .= 'Hi '.  "<br><br>";
	$body .= "Please find here Expenses Zoho scheduler processed";
		
	$body .= "<br><br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards";
	
	$body .= "<br><br>".$disclaimer;
	
	$mail->MsgHTML($body);
	
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
		$close = 'Y';
	}
 
//exit('Mail Send...');
