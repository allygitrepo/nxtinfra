	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
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
	$mail->SMTPSecure 	= "tls";
	$mail->SMTPAuth     = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	//$mail->Port       	= 587;               // set the SMTP port
	 
	
	$mail->setFrom($host_username, $host_name);

    $user_email 	= 'ravindra.gandhile@gmail.com';
	$user_name_by	= 'IT Support';
	$mail->addAddress($user_email, $user_name_by);
	
	//Set who the message is to be sent to BCC
	//$mail->addAddress($mail_to, 'First Gmail');
	//$mail->addAddress($mail_to, 'First Gmail');
	$mail->addBCC($mail_to, 'First Gmail');
	
 	
	
	//Set an alternative reply-to address
//	$mail->addReplyTo($user_email, $user_name_by);
	
	//Set the subject line
	$mail->Subject = 'Athaang Workflow - TESTING ';

	$body .= 'Hi Athaang'.",<br><br>";
	$body .= "Kindly informing Tally data has been transfered  <br><br>";
	
	$body .= $upload_error;
	
	//$body .= '<br><br>'. $message;
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Athaang Group of Company";
	
	$body .= "<br><br><br><br>".$disclaimer;

echo $body. "<BR>";
//exit();

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$i='';
	}
	
//exit();
