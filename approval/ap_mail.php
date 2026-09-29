	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   		= $_SESSION['user'];
	$user_name_by 	= $_SESSION['user_name_by'];
	$userid   		= $_SESSION['usrid'];

//	$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
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
	$host_subject	=	$row['host_subject'];
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


	//$mail->addBCC($mail_to, 'First Gmail');
	//$mail->addAddress($mail_to, 'First Gmail');
	//$mail->addReplyTo($mail_to, 'First Gmail');
	
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	
	$mail->addReplyTo($user_email, $user_name);
	//Set who the message is to be sent to
	$mail->addAddress($user_email, $user_name);
	
	//$mail->addAddress('daksh.s@nxt-infra.com', 'Daksh Sharma');
	//$mail->addBCC('daksh.s@nxt-infra.com', 'Daksh Sharma');
	
	$sql = " SELECT * FROM sma_workflow where 1 and doc_type = 'AP' and company_id = '$company_id' "; 
	$q2 = mysqli_query($con, $sql);
	$r2     = mysqli_fetch_array($q2);
	$email_one = $r2['email_one'];
	$email_two = $r2['email_two'];
	$email_final_approval_err = explode(',',$r2['email_final_approval']);
	
	if($status=='Completed'){
	    foreach ($email_final_approval_err as $email_final_approval){
    		$sql = " select * from sma_user where email = '$email_final_approval' ";
        	$q2 =mysqli_query($con, $sql);
        	$r2 = mysqli_fetch_array($q2);
        	$user_email 		= $r2['email'];
        	$user_name 			= $r2['username'];
    		$mail->addAddress($email_final_approval, $user_name);
	    }
	}
	
	$sql = " select * from sma_user where id = '$email_one' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$user_email 		= $r2['email'];
	$user_name 			= $r2['username'];
	if(!empty($user_email)){
	    $mail->addAddress($user_email, $user_name);	
	}
	
	$sql = " select * from sma_user where id = '$email_two' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$user_email 		= $r2['email'];
	$user_name 			= $r2['username'];
	if(!empty($user_email)){
	    $mail->addAddress($user_email, $user_name);	
	}
	
	if($approval_status	== 'Rejected'){
		$mail->addAddress($draft_email, $draft_username);
		$mail->addAddress('ajay.s@nxt-infra.com', 'Ajay Singh');
	}	
	//Set the subject line
	$mail->Subject = $host_subject.' - ' . $subject . ' by ' . $user_name_by;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$message = "Status : " . $approval_status;
	$body .= 'Hi '. $user_name. "<br><br>";
	$body .= "Please find here Approval Memo for your review, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
	if(!empty($remarks)){
		$body  .= "Remarks : ". $remarks . "<BR><br>";
	}
		
		
	$body .= $btn_var . "<br><br>";
	
	if($status!='Completed'){
		$body .= $btn_varA . "<br><br>";
		
		$body .= $btn_varR . "<br><br>";
	}
	
	$body .= "Following is the History of Workflow.". "<br>";
	
	$doctype = 'AP';
	include "../workflow_process_to_mail.php";

 		$sql = " select * from sma_user where id = '$userid' ";
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

//echo $body;
//exit();

	$mail->MsgHTML($body);

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body <br>' . $message;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
//	$mail->addAttachment('pdf/document_transmittal.pdf');

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit(' Email Send Error...');
	} else {
		echo "Message sent!";
		$i='';
	}
//exit();

