	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   		= $_SESSION['user'];
	$user_name_by 	= $_SESSION['user_name_by'];
	$userid   		= $_SESSION['usrid'];

	$body  = '';
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

	//$mail->addBCC($mail_to, 'First Gmail');

	$sql = " select * from sma_user where id = '$approver_id' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$user_name 			= $r2['username'];
	$user_email 		= $r2['email'];
			
	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	$mail->addReplyTo($user_email, $user_name);
	//Set who the message is to be sent to
	$mail->addAddress($user_email, $user_name);
	
	//$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
		
	//Set the subject line
	$subject = $user_name_by. 'has provided comment/response on GRN SRN  Number '. $ap_id;
	$mail->Subject = $subject;
	
	$body .= 'Hello '. "<br><br>"; //$user_name. 
	$body .= "GRN SRN  Number $si_id , Dated :". date('d-m-Y') ."<br>";
	
	if(!empty($comment)){
		$body  .= "Comment / Response : <br> <b>". $comment . "</b><BR><br>";
	}

	$body .= $user_name_by.' has provided comment/response on following document, you are requested to go to "Comment" tab and click on "Submit" button against this comment to provide your feedback <br>';	
	$body .= $baseurl . "<br><br>";

	$doctype = 'SI';
	$ap_id   = $si_id;
	include "../comment_history_to_mail.php";

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

	$body .= "<br><br><br>".$disclaimer;
//echo $body;
//exit();

 
	$mail->MsgHTML($body);

	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
		$i='';
	}
//exit();
