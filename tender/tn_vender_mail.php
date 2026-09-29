<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	
	//error_reporting(0);
	
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	
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
	//$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	
	/* if($status=='Completed'){
			$mail->addAddress($party_email, $party_name);
	} */
	
	if($approval_status	== 'Rejected'){
		$mail->addAddress($draft_email, $draft_username);
	}	
	//Set the subject line
	
	//$mail->Subject = 'Athaang P2P - '. $subject.'- requested by '. $draft_username;
	$mail->Subject = "Tender $tender_id from $comp_name ";
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$message = "Status : " . $status;
	$body .= 'Hi '. $party_name. "<br><br>";
	
	$body .= "We require your quote for the Material/Services mentioned in the following tender";
	$body .= "<br><br>";
	$body .= "Please find here Tender for your review, Tender Number : ". $tender_id ." Dated : " .date('d-m-Y')
;
	$body .= "<br><br>";
	$body .= 'Title : '.$tender_title;
	$body .= "<br><br>";
	$body .= "Closing Date : " . date('d-m-Y', strtotime($deadline_date)) .' Time : '. $deadline_time;
	$body .= "<br><br>";
	
	if(!empty($remarks)){
		$body  .= "Remarks : ". $remarks . "<BR><BR>";
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
		
		$sql = "SELECT * from company where comp_id = '$from_company_work' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$comp_name = $r2['comp_name'];	
			$comp_pan_no = $r2['comp_pan_no'];	
			$comp_gst_no = $r2['comp_gst_no'];	
			$comp_state = $r2['comp_state'];	
			
	$body .= "<br><br>";
	$body .= $btn_var . "<br>";
		
	$body .= "<br>"."Company Details : ";
	$body .= "<br>"."Company Name : ". $comp_name;
	$body .= "<br>"."Location: ". $comp_state;
	$body .= "<br>"."GST Number : ". $comp_gst_no;
	$body .= "<br>"."PAN Number : ". $comp_pan_no;
	
	$body .= "<br>";
	//$body .= $baseurl1 ."<br>";
	
	if($status=='Extended'){
		$body .= "<br>". "Please ignore this email if you have already submitted a tender". "<br>";;
	}
	
	$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
	
	$body .= "<br><br>"."Thank & Regards"."<br>". $from_name."<br>".
				$comp_name . "<BR>".
				'Email - ' .$from_email . "<BR>".
				'Mobile- ' .$from_mobile . "<BR>";

	$body .= "<br><br>".$disclaimer;
	//Tender submitting User Manual_Vendor.pdf
	//xampp-7.4\htdocs\p2p2023
	
	$dir = "c:/xampp-7.4\htdocs\p2p2023/tender/";
	$file = 'Tender submitting User Manual_Vendor.pdf';
	$file_attach = $dir.$file;
	$mail->addAttachment($file_attach, $file);

	$sql = " SELECT * FROM `sma_tender_file_upload` where tender_hdr_id = '$tender_id' and supplier_id = 0 and doc_type = 'V' ";
	$q2	 = mysqli_query($con, $sql);
	while($r2 =	mysqli_fetch_array($q2)){
		$file_name 		= $r2['file_name'];
		$file_path 		= $r2['file_path'];
		$file 		 = $file_path.'/'.$file_name ;
		$file_attach = $dir.$file;
		
		$mail->addAttachment($file_attach, $file);
	}
		
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

