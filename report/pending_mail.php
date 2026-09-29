	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   		= $_SESSION['user'];
	$user_name_by 	= $_SESSION['user_name_by'];
	$userid   		= $_SESSION['usrid'];
	$body			= '';
	
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

//	$mail->addBCC($mail_to, 'First Gmail');
//	$mail->addAddress($mail_to, $user_name);
	
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	
	$mail->addReplyTo($user_email, $user_name);
	//Set who the message is to be sent to
	$mail->addAddress($user_email, $user_name);
	
	if($pending_with_prev==996  ){
		$mail->addAddress('g.krishnamurthy@athaanginfra.in', 'G Krishnamurthy');
		$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	}
	
		
	//Set the subject line
	$mail->Subject = 'Athaang P2P - Pending documents for your Review and approval ';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$body .= 'Hi '. $user_name. "<br><br>";
	$body .= "Following Documents are pending for your decision, You are requested to review and provide your decision as soon as possible. <br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>";
	
	$body .= $message;
	
	$body .= "<br><br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.<br>';
	
	$body .= "<br><br>Athaang P2P";

	//$body .= "<br><br>".$disclaimer ."<br>";

//echo $body;

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$message ='';
		$i='';
	}
//exit();
