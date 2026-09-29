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
	if($mailvar == 'M'){
		$a='';
		$mail = new PHPMailer;
	}
	else {
		require '../PHPMailer-master/PHPMailerAutoload.php';
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
	}
	
	
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	
	include("../dbcon.php");
	$sql="SELECT * from smtp_dtl";
	$res2 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row2 = mysqli_fetch_array($res2);
	$host 			=	$row2['host'];
	$host_name		=	$row2['host_name'];
	$host_username 	=	$row2['username'];
	$host_password  =	$row2['password'];
	$host_port 		=	$row2['port'];
	$disclaimer 		=	$row2['disclaimer'];

	$mail->Host 		= $host;
	$mail->SMTPAuth     = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	
	//$mail->addBCC($mail_to, 'Test Name');
	
	$mail_to 	= 'athaangp2p@athaanginfra.in';
	$mail_name  = 'Athaang-P2P';
	$mail->addReplyTo($mail_to, $mail_name);
	//Set who the message is to be sent to
	$mail->addAddress($mail_to, $mail_name);
//	$mail->addAddress('ranganathan.n@athaanginfra.in', $user_name);
//	$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
		
	$msme_v = '';	
	//Set the subject line
	if($unpaid_rep == 'M'){	
		$msme_v = ' for MSME Vendor ';
		$mail->Subject = 'Athaang P2P - MSME Vendor More than 15 Days payment pending report. ';
		
	}
	else if($unpaid_rep == 'Y'){	
		$mail->Subject = "Athaang P2P - $company_code More than 10 Days payment pending report. ";
		
	}
	else {
		$mail->Subject = "Athaang P2P - $company_code Daily Payment pending report. ";
		
	}
 	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$body .= 'Hi '. "<br><br>";
	
	//$body .= "Subject =" . "Athaang P2P - $company_code Daily Payment pending report. ";
	
	$body .= "Here is a list of invoices that are awaiting payment confirmation $msme_v . <br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>";
	
	$body .= $message;
	
	$body .= "<br><br>Athaang P2P";

	//$body .= "<br><br>".$disclaimer ."<br>";

//echo $body;
//exit();

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo $msme_v . " Unpaid Payment Message sent!";
		$message ='';
		$i='';
	}

//exit();
