	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	 
	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require 'PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
/*
	SMTP Server : outlook.office365.com
	SMTP Port : 587
	SSL = YES
	User Name : workflow@highwayconcessions.com
	Password : highway@1234
$mail->IsSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "myemail@gmail.com";
$mail->Password = "**********";
$mail->Port = "465";
*/

	// optional
	// used only when SMTP requires authentication  

	$mail->IsSMTP();
	$mail->Host = "outlook.office365.com";
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Username = 'workflow@highwayconcessions.com';
	$mail->Password = 'highway@1234';
	$mail->Port       = 587;                    // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	//$mail->isSendmail();
	//Set who the message is to be sent from
	$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	$mail->addReplyTo($mail_to, 'First Last');
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');
	$mail->addAddress($mail_to, 'First Gmail');
	$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
//	$mail->addAddress('prakash.patel@bon-in.co.in', 'Prakash Patel');
	//Set the subject line
	$mail->Subject = 'PHPMailer sendmail test';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$body .= "Please find here attached document transmittal for your review, Date: ". date('d-m-Y') . "<br><br>". $message;
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."BON-IN ";

	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
//	$mail->addAttachment('pdf/document_transmittal.pdf');

// the message

//echo "####1..";
//echo $message;

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
exit();
