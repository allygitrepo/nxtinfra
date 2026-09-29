	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	$user   = $_SESSION['user'];
	$body   ='';
//	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	//require '../../PHPMailer-master/PHPMailerAutoload.php';
	
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
	$mail->SMTPAuth 	= true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	//$mail->addReplyTo($mail_to, 'First Last');
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');
	//$mail->AddBCC($mail_to, 'First Gmail');
	
	
	$tomail_1 	 = 'billdesk@athaanginfra.in';
	$tomail_name = 'IT Support';
	$mail->addAddress($party_email, $party_name);
	$mail->addAddress($tomail_1, $tomail_name);
	$mail->addReplyTo($tomail_1, $tomail_name);

	//$mail->addAddress($mail_to, 'Ravindra');
	
	//Set the subject line
	$mail->Subject = 'TDS certificate '. $party_pan_number ;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$body .= 'To, <br>'. $party_name. "<br><br>";
	$body .= "We hereby confirm that TDS paid against the amount paid to you. <BR><BR>

				Please review the attached TDS certificate and acknowledge the receipt for the same. <BR><BR>

				If you have any query or require further clarification, please, do not hesitate to send me an email to $tomail_1 <br>";
	
	$body .= "<br><br>"."Thank "."<br>". "<br>"."Athaang ";

	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	// $mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].$file, $name = 'test',  $encoding = 'base64', $type = 'application/pdf');
	 

	$file_attach = $dir.'/' .$file;
	$mail->addAttachment($file_attach, $file);

//echo $file. '#####1 ' ;
//exit();	
	
	//$mail->addStringAttachment($file, $file);
	

	//send the message, check for errors
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$is='';
	}


//exit();

