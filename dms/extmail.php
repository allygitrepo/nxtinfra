<?php
	
	session_start();
	require "../dbcon.php";
	
$K = $K + 1;
echo "<small>" . $K . "</sma>";
	
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

	$user   = $_SESSION['user'];
	 
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

	//$mail->AddBCC($mail_to, "First Gmail");
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	//$mail->addAddress($user_email, $user_name);
	
	$scnt = 0;
					$six_digit_random_number = mt_rand(100000, 999999);
					
					$sql 		= " update external_user_mail set otp = '$six_digit_random_number', tdate = now() where inward_no = '$inward_no' and id = '$unq_no' ";
					mysqli_query($con, $sql);
//echo $sql."<BR>";	
					
					$sql        = " select * from external_user_mail where id in ( select id from external_user_mail where inward_no = '$inward_no' and id = '$unq_no' )";
					$result     = mysqli_query($con, $sql); //and userid='$userid' and tdate = '$tdate'
					echo mysqli_error( $con );
					$rw 		= mysqli_fetch_array( $result );
					$ext_mail 	= $rw['email_id'];
				
//echo $sql."<BR>";	
//echo $ext_mail."<BR>";
//exit();

					$mail->addAddress($ext_mail, 'External Mail User');
					
					//$baseurl1 = $baseurl.$modulePath.'extuser_otp.php?sub=edit&inward_no='.$inward_no;

	$mail->Subject = 'HC1 DMS - OTP for Login';
	
	$body .= 'Hi <br><br>'; 
	
	$body .= "Please find here OTP to open document <br><br>";
	
	$body .= 'OTP : '. $six_digit_random_number;
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}
//exit();
