<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	
	//error_reporting(0);
	
	$body 		= '';
	
	//$mail_to 	= 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	//require '../PHPMailer-master/PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	// optional
	// used only when SMTP requires authentication  
	
	$body ='';
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
    $mail->SMTPAutoTLS = false;
	
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	
    include("../baseurl.php");
	
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

	//Set who the message is to be sent to
	//$mail->addAddress($mail_to, 'First Gmail');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($party_email, $party_name);
	
	$mail->addAddress($party_email, $party_name);
	
	//$mail->addBCC($mail_to, 'First Gmail');
	
	$six_digit_random_number = mt_rand(100000, 999999);
	$tdate = date("Y-m-d");
	$ipaddress = $_SERVER['REMOTE_ADDR'];
					
	$sql  = "insert into user_login(`userid`, `otp`, `tdate`, `ipaddress`) values( '$party_email', '$six_digit_random_number', '$tdate', '$ipaddress' )";
	mysqli_query($con, $sql);
			
	$mail->Subject = "Tender OTP for tender number $tender_id "; //from $comp_name 
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $party_name. "<br><br>";
	
	$body .= "Please find here OTP for Tender Open, Tender Number : ". $tender_id ." Dated : " .date('d-m-Y')
;
	$body .= "<br><br>";
	$body .= 'Title : '.$tender_title;
	$body .= "<br><br>";
	$body .= "Closing Date : " . date('d-m-Y', strtotime($deadline_date)) .' Time : '. $deadline_time;
	$body .= "<br><br>";
	
	$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	
	$body .= "<br><br>"."Thank & Regards";

	$body .= "<br><br>".$disclaimer;
	
//echo $file_attach."<BR>";	
//echo $body;
//exit('Exit HERE...');

	$mail->MsgHTML($body);

	//send the message, check for errors
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	} 
	$body = '';
//exit();


?>