	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	session_start();
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	 
	$body = ''; 
	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
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
	$disclaimer 		=	$row['disclaimer'];

	$mail->Host 		= $host;
	$mail->SMTPAuth 	= true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	$mail->addReplyTo($mail_to, 'First Gmail');
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');	
	//$mail->AddBCC($mail_to, 'First Gmail');
	$mail->AddBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N.');
	
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	$mail->addAddress($mail_to, 'First Gmail');

	//Set the subject line
	
	$mail->Subject = 'NEFT / RTGS FILE ';
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));

	$message = "Status : " . $status;
	$body .= 'Hi '. "<br><br>";
	$body .= 'Kindly findhere attached NEFT / RTGS PDF file for Review and Print.'."<br><br>" ;
	
	$mail->addStringAttachment($pdf, $fl_name);
	
	$sql = " select * from sma_user where id = '$userid' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
			$from_by 			= $r2['userid'];
			$from_name 			= $r2['username'];
			$from_by_id 		= $r2['id'];
			$from_email 		= $r2['email'];
			$from_mobile 		= $r2['mobile_no'];
			$from_company_work	= $r2['company_work'];
		
	$sql = "SELECT * from company where comp_id = '$company_id' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$comp_name = $r2['comp_name'];

	$body .= "<br><br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards"."<br>". $from_name."<br>".
		$comp_name . "<BR>".
		'Email - ' . $from_email . "<BR>".
		'Mobile- ' . $from_mobile . "<BR>";

	
	$body .= $message;
	
	$body .= "<br><br><br><br>".$disclaimer;
	
	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
//	$mail->addAttachment('pdf/document_transmittal.pdf');


//	echo $user_email. ' ' . $mail_to. ' ' . $user_email1 . ' ' . $party_email . ' ' . $checker_email . ' <<>> ' . $approval_email ;	
//exit();

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}
	
//echo $user_email;	
//exit();
