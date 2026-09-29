	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	session_start();
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	 
	//$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../../PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();

	include("../../dbcon.php");
	$sql="SELECT * from smtp_dtl";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$host 					= $row['host'];
	$host_name				= $row['host_name'];
	$host_username 			= $row['username'];
	$host_password  		= $row['password'];
	$host_port 				= $row['port'];
	$disclaimer 			= $row['disclaimer'];
	$vendor_mail_send_to	= $row['vendor_mail_send_to'];

	$mail->Host 		= $host;
	$mail->SMTPAuth 	= true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	$mail->setFrom($host_username, $host_name);

	$sql = " SELECT * from sma_user where 1 and email = '$vendor_mail_send_to' " ;
	$res = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r2  = mysqli_fetch_array($res);
	$mail_send_to 					= $r2['username'];
	
	//Set an alternative reply-to address
	$mail->addReplyTo($vendor_mail_send_to, $mail_send_to);
	//Set who the message is to be sent to	
	$mail->addAddress($vendor_mail_send_to, $mail_send_to);
	
	//$mail->addAddress($mail_to, 'Hello World');
	
	$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');

	//Set the subject line
	$mail->Subject = 'Athaang P2P - New Supplier Registred !';
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$body .= 'Hi '. $mail_send_to. "<br><br>";
	$body .= "Please find here new registration of supplier, click on below link. <br><br>";
	
	$body .= "Supplier Name : ". $party_name. "<br><br>";
	
	$body .= $baseurl1 . '<br><br>';
	
	$body .= "<br><br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards";
	$body .= "<br>"."Athaang Group of Company";
	
	$body .= "<br><br>".$disclaimer;
	
// echo $body;	
//exit();
	$mail->MsgHTML($body);

	//send the message, check for errors
	
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}

//exit('Mail Send...');

