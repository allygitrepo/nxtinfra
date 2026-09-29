	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
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
	$mail->Host = "thermocool.co.ug";
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = "ssl";
	$mail->Username = 'thermo_cool@thermocool.co.ug';
	$mail->Password = 'thermocool@123';
	$mail->Port       = "587";                    // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	$mail->setFrom('thermo_cool@thermocool.co.ug', 'THERMOCOOL');

	//Set who the message is to be sent to BCC
	//$mail->addAddress($mail_to, 'First Gmail');
	$mail->addBCC($mail_to, 'First Gmail');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($mail_to, 'Testing Mail');
	
	/* //ranganathan.n@highwayconcessions.com
	$mail->addAddress($user_email, $user_name_by);
	//$mail->addAddress('it.support@highwayconcessions.com', 'IT Support');
	//$mail->addAddress('ranganathan.n@highwayconcessions.com', 'IT Support');
	$mail->addCC('ranganathan.n@highwayconcessions.com', 'IT Support');
	 */
	//Set the subject line
	$mail->Subject = 'Thermo Cool - One Time Password for ';

	$body .= 'Hi ';
	$body .= "Please find here One Time Password <br><br>";
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$i='';
	}
	
	
exit();

?>
