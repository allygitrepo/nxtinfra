	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	$user   = $_SESSION['user'];
	
	$body   = '';	
	
	$mail_to = 'ravindra.gandhile@gmail.com';

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
	//$mail->isSendmail();
	//Set who the message is to be sent from
	$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

	$mail->AddBCC($mail_to, "First Gmail");
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	$mail->addAddress($user_email, $user_name);
	
	$mail->Subject = " HC1 DMS - Gentle Reminder ";
	
	$message = "Status : " . $status;
	$body   .= 'Hi '. $user_name. "<br><br>"; 
	//Here's the document that Narayanan S shared with you.
	$body .="Please find here Document No. ".$inward_no." is reminding you to take action against giving document number";
	$body .=" <br><br> ";
	
	//<a href="" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; ">Go Button</a>

	$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
	
	$body .= "Description : " .$remarks. "<br><br>";
	//RAVI $body .= $baseurl1 . "<br><br>";
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	$mail->MsgHTML($body);
	
	//echo $body;
	//echo $user_email . ' ' . $username . " Hello World <br> ";
	
	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}

	
//exit();
