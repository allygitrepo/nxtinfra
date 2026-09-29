	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	$user   = $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	
	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	//require '../PHPMailer-master/PHPMailerAutoload.php';
	
	$body = '';
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	
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
	$mail->SMTPAuth     = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	$user_email = 'athaangp2p@athaanginfra.in';
	$user_name	= 'Athaangp2p';
	//Set who the message is to be sent to
	//$mail->addReplyTo($mail_to, 'First Gmail');
	//$mail->addBCC($mail_to, 'First Gmail');
	
	$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($user_email, $user_name);
	$mail->addAddress($user_email, $user_name);
	$mail->addAddress($maker_email, $maker_name);
	$mail->addAddress($booked_by_email, $booked_by_name);
	
	//Set the subject line
	$mail->Subject = 'Tax Invoice need to book against Advance Paid';
	
	$body .= 'Hi '. $maker_name. "<br><br>";
	$body .= "This is an automated request for the booking of a tax invoice against the advance payment made on [date]. The details of the advance payment are as follows:". "<br><br>";

	
	$body .= $message. "<br><br>";
	
	
	$body .= "Please send the tax invoice to billdesk@athaanginfra.in at your earliest convenience and update the records accordingly.". "<br><br>";

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
