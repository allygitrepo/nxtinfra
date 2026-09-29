	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
	//$mail_to = 'ravindra.gandhile@gmail.com';

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
	$host_subject   =   $row['host_subject'];
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
	//$mail->addReplyTo($mail_to, 'First Gmail');
	//$mail->addBCC($mail_to, 'First Gmail');
	
	$mail->addAddress($user_email, $user_name);
	
//Notification Mail	Start common routine 
if($status=='Completed'){
	$sql = "SELECT * FROM `sma_workflow` where company_id = '$company_id' and doc_type = 'PR' ";
	$q2 = mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
	    $email_one			= $r2['email_one'];
		$email_two			= $r2['email_two'];
		$email_three		= $r2['email_three'];
		$email_four			= $r2['email_four'];
		$email_final_approval = $r2['email_final_approval'];
	    if($email_one>0){
    	    $sql = " select * from sma_user where id = '$email_one' ";
    		$qry =mysqli_query($con, $sql);
    		$r22 = mysqli_fetch_array($qry);
    		$name_notify 		= $r22['username'];
    		$email_notify 		= $r22['email'];
    		$mail->addAddress($email_notify, $name_notify);	
	    }
	    if($email_two>0){
    	    $sql = " select * from sma_user where id = '$email_two' ";
    		$qry =mysqli_query($con, $sql);
    		$r22 = mysqli_fetch_array($qry);
    		$name_notify 		= $r22['username'];
    		$email_notify 		= $r22['email'];
    		$mail->addAddress($email_notify, $name_notify);	
	    }
	    if($email_three>0){
    	    $sql = " select * from sma_user where id = '$email_three' ";
    		$qry =mysqli_query($con, $sql);
    		$r22 = mysqli_fetch_array($qry);
    		$name_notify 		= $r22['username'];
    		$email_notify 		= $r22['email'];
    		$mail->addAddress($email_notify, $name_notify);	
	    }
	    if($email_four>0){
    	    $sql = " select * from sma_user where id = '$email_four' ";
    		$qry =mysqli_query($con, $sql);
    		$r22 = mysqli_fetch_array($qry);
    		$name_notify 		= $r22['username'];
    		$email_notify 		= $r22['email'];
    		$mail->addAddress($email_notify, $name_notify);	
	    }
	    
	}
}	
	
//Notification Mail end
	
	if($status=='Completed'){
		$sql = " select * from sma_user where email = '$email_final_approval' ";
    	$q2 =mysqli_query($con, $sql);
    	$r2 = mysqli_fetch_array($q2);
    	$user_email 		= $r2['email'];
    	$user_name 			= $r2['username'];
		$mail->addAddress($email_final_approval, $user_name);
	}
	
// 	$mail->addAddress('daksh.s@nxt-infra.com', 'Daksh Sharma');
// 	$mail->addAddress('ajay.s@nxt-infra.com', 'Ajay Singh');
	
	//$mail->addAddress('nitkot@gmail.com', 'Nitin Kothari');
	//Set the subject line
	
	$mail->Subject = $host_subject. ' - Purchase Requisition Note No. ' . $pr_id . ' is '.  $status.' by '. $user_name_by;
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $user_name. "<br><br>";
	//$body .= "Please find here Purchase Requisition Note for your review, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
	
	$body .= "This notification indicates that PR No. $pr_id Dated ".date('d-m-Y')." is $status .". "<br><br>";

    $body .= "New Status of this PR is " . $status . ".". "<br><br>";

	$body .= $baseurl1 . "<br><br>";
	
    $body .= "Following is the History of Workflow.". "<br>";

	$doctype = 'PR';
	$ap_id   = $pr_id;
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
