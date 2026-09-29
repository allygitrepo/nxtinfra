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
	$mail->addReplyTo($user_email, $user_name_by);
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');	
	
	//email_final_approval SELECT * FROM `sma_workflow` WHERE 1 and doc_type = 'PY' and company_id = 6;
	
	
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	if(!empty($user_email1)){
		$mail->addAddress($user_email1, $user_name_by1);
	}
	$mail->addAddress($user_email, $user_name_by);
	
	if(!empty($user_email_b)){
		$mail->addAddress($user_email_b, $user_name_by_b);
	}
	
	$mail->addAddress($checker_email, $checker);
	$mail->addAddress($approval_email, $approval);
	
	if(!empty($ipc_user_email)){
		$mail->addAddress($ipc_user_email, $ipc_user_name_by);
	}

    if($status == 'Completed'){
        $sql = " SELECT * FROM `sma_workflow` WHERE 1 and doc_type = 'PY' and company_id = '$company_id' ";
    	$q2  = mysqli_query($con, $sql);
    	$r2  = mysqli_fetch_array($q2);
    	$email_final_approval 	= $r2['email_final_approval'];
    }
    
	//Set the subject line
	
	if($status!='Draft' && empty($utr_no)){
		$mail->Subject = 'NXT P2P - Payment Voucher for review requested by ,  '.  $user_name_by;
	}
	else if($status!='Draft' && !empty($utr_no) ){
		if(!empty($utr_no)){
			$mail->addAddress($party_email, $party_name);
			//$mail->AddBCC($mail_to, 'First Gmail');
		}
		
		$mail->Subject = 'Payment Confirmation by '.  $comp_name;
	}
	else if($status=='Draft'){
		$mail->Subject = 'Payment send back to Maker,  '.  $comp_name;
	}
	 
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	if(!empty($party_name) && !empty($utr_no)){
		$user_name = $party_name;
	}
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $user_name.  "<br><br>";
	$body .= $msg ."<br><br>" ;
	
	if ( empty($utr_no) ){
		$body .= $baseurl1 . "<br><br>";
	}
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

	if ( empty($utr_no) ){
	$message .=  "<BR><b> Approval Process</b>";	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; background: #E7E7E7; font-size: 13px;' border='1' >";		
	$message .= "<tr><th style='width: 35%;text-align: left;'>Decision by </th>
					<th style='width: 25%;text-align: left;'>Status </th>
					<th style='width: 40%;text-align: center;'> Date Time </th>				
					</tr></table>";
		$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black;  font-size: 10pt;' > ";				
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'PY' and a.doc_id = '$py_id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			$message .= "<tr>
					<td style='width: 35%;text-align: left;'>". $approval . " </td>
					<td style='width: 25%;text-align: left;'>". $status . " </td>
					<td style='width: 40%;text-align: center;'>" . $approval_date . " </td>				
					</tr> ";				
		}
		$message .= "</table>";
		
	}

	
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
