<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	//$user   = $_SESSION['user'];
	//$userid   = $_SESSION['usrid'];
	
	$userid   = 'admin';
	
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
	$mail->SMTPAuth = true;
	
    include("../dbcon.php");
	$sql="SELECT * from smtp_dtl";
	$resulta = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$rowa = mysqli_fetch_array($resulta);
	$host 			=	$rowa['host'];
	$host_name		=	$rowa['host_name'];
	$host_username 	=	$rowa['username'];
	$host_password  =	$rowa['password'];
	$host_port 		=	$rowa['port'];
	$disclaimer 		=	$rowa['disclaimer'];

	$mail->Host 		= $host;
	$mail->SMTPAuth     = true;
	$mail->Username 	= $host_username;
	$mail->Password 	= $host_password;
	$mail->Port       	= $host_port;               // set the SMTP port
	
	$mail->setFrom($host_username, $host_name);

	//Set who the message is to be sent to
	//$mail->addReplyTo($mail_to, 'First Gmail');
	$mail->addBCC($mail_to, 'First Gmail');
	
	$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($draft_email, $draft_username);
	
	$mail->addAddress($draft_email, $draft_username);
		
	//Set the subject line
	$mail->Subject = 'Athaang P2P - '. 'PO Auto Close by System ';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $draft_username. "<br><br>";
	$body .= "This P2P as a notification that Purchase Order $purchase_id [$po_number] has been successfully closed in our system due to a zero balance. All items and associated transactions related to this purchase order have been fulfilled and accounted for.". "<br><br>";
	
	$sql = " select * from sma_user where userid  = '$userid' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$from_by 			= $r2['userid'];
			$from_name 			= $r2['username'];
			$from_by_id 		= $r2['id'];
			$from_email 		= $r2['email'];
			$from_mobile 		= $r2['mobile_no'];
			$from_company_work	= $r2['company_work'];
		
		$sql = "SELECT * from company where comp_id = '$from_company_work' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$comp_name = $r2['comp_name'];	

	$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	$body .= "<br><br>"."Thank & Regards"."<br>". $from_name."<br>".
				$comp_name . "<BR>".
				'Email - ' .$from_email . "<BR>".
				'Mobile- ' .$from_mobile . "<BR>";

	$body .= "<br><br>".$disclaimer;
	
	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
//	$mail->addAttachment('pdf/document_transmittal.pdf');

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}
//exit();
