	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	$body = '';
	//$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	//require '../PHPMailer-master/PHPMailerAutoload.php';
	
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
	//$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

	//Set who the message is to be sent to BCC
	//$mail->addAddress($mail_to, 'First Gmail');
	//$mail->addBCC($mail_to, 'First Gmail');
	
	$user_email1 	= 'ranganathan.n@highwayconcessions.com';
	$user_name_by1	= 'IT Support';
	$mail->addReplyTo($user_email1, $user_name_by1);
	
	$user_email2 = 'ithcone@highwayconcessions.com';
	$mail->addReplyTo($user_email2, $user_name_by1);
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');	
	$mail->AddBCC($mail_to, 'First Gmail');
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	
 	if(!empty($user_email)){
		$mail->addAddress($user_email, $user_name_by);
	}
	$mail->addAddress($party_email, $party_name);
	$mail->addAddress($checker_email, $checker);
	$mail->addAddress($approval_email, $approval);
	$mail->addAddress($drafyby_email , $drafyby);
	$mail->addAddress($verified_email , $verified);
	
	if(!empty($ipc_user_email)){
		$mail->addAddress($ipc_user_email, $ipc_user_name_by);
	}
 
	$mail->Subject = 'Payment Confirmation by '.  $comp_name;
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $user_name_by. ' / '. $party_name . "<br><br>";
	$body .= $msg ."<br><br>" ;
	//$body .= $baseurl1 . "<br><br>";

	
	$sql = "select * from sma_user where userid='$checker_id' ";
//echo $sql."<BR>";	
	$res1 = mysqli_query($con, $sql);
	while($r = mysqli_fetch_object($res1)){
		$username 		= $r->userid;
		$id		 		= $r->id;
		$user_name		= $r->username;
	}

	$body .= "<br><br><br>"."Thank & Regards"."<br>". $user_name."<br>"."Highway Concessions";

// echo $body;
/*
exit('STOPED HERE FOR MAIL....');
*/
 
	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	//	echo "Message sent!";
		$i='';
	}
	
//exit();
