	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	session_start();
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	 
	//$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../../PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();

	include("../../dbcon.php");
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
	$mail->addReplyTo($party_email, $party_name);
	//Set who the message is to be sent to	
	$mail->addAddress($party_email, $party_name);
	
	//$mail->addAddress($mail_to, 'Hello World');
	
	//$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');

	//Set the subject line
	$mail->Subject = 'Athaang P2P - Supplier OTP ';
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$six_digit_random_number 	= mt_rand(100000, 999999);
	$_SESSION['otp_six_digit']	= $six_digit_random_number;
	
	$body .= 'Hi '. $party_name. "<br><br>";
	$body .= "Please find here OTP <br><br>";
	$body .= "<b>".$six_digit_random_number."</b><br>";
	
	$body .= "<br><br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards";
	$body .= "<br>"."Athaang Group of Company";
	
	$body .= "<br><br>".$disclaimer;

	$mail->MsgHTML($body);

	//send the message, check for errors
	
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}

 
 
//exit('Mail Send...');
