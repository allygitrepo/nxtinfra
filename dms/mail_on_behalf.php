<style>
	a.button {
		-webkit-appearance: button;
		-moz-appearance: button;
		appearance: button;

		text-decoration: none;
		color: initial;
	}
</style>

	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
		
    session_start();
	
	$user   			= $_SESSION['user'];
	$userid   			= $_SESSION['usrid'];
		 
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

//	$mail->clearAddresses();
	
			$sql="select * from sma_user where id = '$on_behalf' ";
	//echo $sql."<BR>";	
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_category 	= $r->user_category;
					$user_email		= $r->email;
					$user_name		= $r->username;
					$mail->addAddress($user_email, $user_name);
				}

//$mail->addAddress($user_email, $user_name);
	
//$mail->addAddress($mail_to, $user_name);
	
	$subject = 'HC - DMS My Document ';
	$mail->Subject = $subject;

	$body .= 'Dear Sir / Madam,'. "<br><br>"; 
	$body .= "Please find here Document ". $inward_no. " by " . $user . "<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
	$body .= " Description :" . $remarks."<br><br>";
				
	$baseurl1 =$baseurl.$modulePath.'my_document.php?sub=edit&inward_no='.$inward_no.'&unq_no='.$unq_no;
					
	$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
						
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

//echo $body;
//exit();

	$mail->MsgHTML($body);
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent! abc";
		$i='';
	}
	
//echo $snd. "  <<< Exit....###>>";
//exit();


//exit();
