	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   = $_SESSION['user'];

	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '/PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
		
	$mail->IsSMTP();
	$mail->Host = "outlook.office365.com";
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = "ssl";
	$mail->Username = 'workflow@highwayconcessions.com';
	$mail->Password = 'highway@1234';
	$mail->Port       = "587";                    // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	//$mail->isSendmail();
	//Set who the message is to be sent from
	$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

	   
	// Set PHPMailer to use the sendmail transport
	//$mail->isSendmail();
	//Set who the message is to be sent from
//	$mail->addReplyTo($mail_to, $user_name);
	$mail->addAddress($mail_to, $user_name);
	
	$mail->addReplyTo($user_email, $user_name);
	$mail->addAddress($user_email, $user_name);
	
	
	$mail->Subject = 'HC Workflow -'.  $module_name. ' Pending Transaction';
	//$message = "Status : " . $status;
	$body .= 'Hi '. $user_name. "<br><br>";
	$body .= "Please find here " . $module_name ." for your review, <br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>";
	//$message. "<br><br>";
	//$body .= $baseurl1 . "<br><br>";
	
	$sql = "select * from sma_user where userid='$user' ";
	$result = mysqli_query($con, $sql);
	while($r = mysqli_fetch_object($result)){
		$username 		= $r->userid;
		$id		 		= $r->id;
		$user_name		= $r->username;
	}

	$body .= "<br><br><br>"."Thanks & Regards"."<br>". $user_name."<br>"."Highway Concessions";

	$mail->MsgHTML($body);

	$mail->addAttachment('pending_status.pdf');
	$attachment = $fl_name;
	$mail->addAttachment( $attachment );
	
	//send the message, check for errors
	if (!$mail->send()){
		"Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$i='';
	}
//exit();



