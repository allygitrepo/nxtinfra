<?php

	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	
	error_reporting(0);
	
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];

	$body = '';

	include("../baseurl.php");
	include("../dbcon.php");
	
	$mail_to = 'ravindra.gandhile@gmail.com';

	$tender_hdr_id 	= $_GET['tender_id'];
	$tender_id 		= $_GET['tender_id'];
	$supplier_id 	= $_GET['supplier_id'];
	
	$body = '';
		
		require '../PHPMailer-master/PHPMailerAutoload.php';
		
		$sql 	= "SELECT * FROM `sma_tender_header` where id = '$tender_hdr_id' ";
	//echo $sql. "<BR>";
		$q2  	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$draft_by 		 = $r2['draft_by'];
		$approval_status = $r2['approval_status'];
		$deadline_date 	 = date('d-m-Y', strtotime($r2['deadline_date']));	
		$deadline_date 	 = $deadline_date. ' ' .$r2['deadline_time'];
		$deadline_time 	 = $r2['deadline_time'];

	$sql="SELECT * from smtp_dtl";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$host 				=	$row['host'];
	$host_name			=	$row['host_name'];
	$host_username 		=	$row['username'];
	$host_password  	=	$row['password'];
	$host_port 			=	$row['port'];
	$disclaimer 		= 	$row['disclaimer'];
	
	$sql = " SELECT supplier_id FROM sma_tender_supplier 
					WHERE tender_hdr_id = '$tender_hdr_id' AND supplier_id = '$supplier_id' ";
	$qry2 = mysqli_query($con, $sql);
echo mysqli_affected_rows($con);	
//echo $sql. "<BR>";
//exit();
	
	echo mysqli_error($con);
	while($res2 	= mysqli_fetch_array($qry2)){
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		// optional
		// used only when SMTP requires authentication  
		
		$mail->SMTPSecure  = 'tls'; // secure transfer enabled REQUIRED for GMail
		$mail->SMTPAutoTLS = false;
		
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		
		$mail->Host 		= $host;
		$mail->SMTPAuth     = true;
		$mail->Username 	= $host_username;
		$mail->Password 	= $host_password;
		$mail->Port       	= $host_port;              
		
		$mail->setFrom($host_username, $host_name);

		$supplier_id = $res2['supplier_id'];
		$to_supplier = $res2['supplier_id'];
		$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
	//echo $sql. "<BR>";		
		$q2  	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$party_email = $r2['party_email'];
		$party_name  = $r2['party_name'];
			
//		Set who the message is to be sent to
		$mail->addAddress($mail_to, 'First Gmail');
		
		//Set an alternative reply-to address
		$mail->addReplyTo($party_email, $party_name);
		
		$mail->addAddress($party_email, $party_name);
		
		$mail->addBCC($mail_to, 'First Gmail');
		
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
		$body .= "Please find here Tender for your review, Tender Number : ". $tender_id ." Dated : " .date('d-m-Y');
		$body .= "<br><br>";
		$body .= 'Title : '.$tender_title;
		$body .= "<br><br>";
		//$body .= "Closing Date : " . $deadline_date .' '. $deadline_time;
		$body .= "Closing Date : " . date('d-m-Y', strtotime($deadline_date)) .' Time : '. $deadline_time;
		$body .= "<br><br>";
		
		if(!empty($remarks)){
			$body  .= "Remarks : ". $remarks . "<BR><BR>";
		}
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
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
				
		$body .= "<br><br>"."Company Details : ";
		$body .= "<br>"."Company Name : ". $comp_name;
		$body .= "<br>"."Location: ". $comp_state;
		$body .= "<br>"."GST Number : ". $comp_gst_no;
		$body .= "<br>"."PAN Number : ". $comp_pan_no;
		$body .= "<br><br><br>";

echo $body;		
		$encrypted = encryptIt( $to_supplier );
	
			$modulePath = "athaangSI/tender/"; 
			$baseurl1 = $baseurl.$modulePath.'editSI.php?id='.$tender_id. '&direct=D&supplier_id='.$encrypted;
			
			$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 8px 12px;text-align: center;font-weight: 400;" >Click here to view the tender</a>';

		//$body .= $baseurl1 ."<br>";
		$body .= $btn_var . "<br><br>";
		
		$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
		
//echo $body;
		
		$body .= "<br><br>"."Thank & Regards"."<br>". $from_name."<br>".
					$comp_name . "<BR>".
					'Email - ' .$from_email . "<BR>".
					'Mobile- ' .$from_mobile . "<BR>";

		$body .= "<br><br>".$disclaimer;
		//Tender submitting User Manual_Vendor.pdf
		
		$dir = "C:/xampp-7.4/htdocs/p2p2023/tender/";
		$file = 'Tender submitting User Manual_Vendor.pdf';
		$file_attach = $dir.$file;
		$mail->addAttachment($file_attach, $file);

/* echo $file_attach."<BR>";	
	echo $body;
	exit();	 */
		$mail->MsgHTML($body);

		//send the message, check for errors
 		if (!$mail->send()){
			echo "Mailer Error: " . $mail->ErrorInfo;
			exit();	
		} else {
			//echo "Message sent!";
			$i='';
		}
	 
	}	
	
	echo "<script>alert('Message sent !!!');</script>";
	echo "<script>window.close();</script>";	
	exit();
//exit();

//$input = "29";
//$encrypted = encryptIt( $input );
//$decrypted = decryptIt( $encrypted );

//echo $encrypted . '<br />' . $decrypted;

function encryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}

?>