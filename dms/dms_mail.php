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
	$user   = $_SESSION['user'];
	 
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

	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	//$mail->addReplyTo($mail_to, 'First Last');
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');
	//$mail->addAddress($mail_to, 'First Gmail');
	$mail->AddBCC($mail_to, "First Gmail");
	//$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
	$mail->addAddress($user_email, $user_name);
	
	$scnt = 0;
//Shared TO Group users;
	$scnt = sizeof($send_comp_group_arr);
	if ($scnt>0){
		foreach ($send_comp_group_arr as $sendto){
			$status = 'Shared';
			if( !empty($sendto) ){
			$sql="select * from sma_user where id='$sendto' ";
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
			}
		}	
	}
		
//External Email ID with OTP

//Shared TO Company users;
	$scnt = sizeof($send_comp_user_arr);
	if ($scnt>0){
		foreach ( $send_comp_user_arr as $sendto ){
				$status = 'Shared';
				if( !empty($sendto) ){
					$sql = " select * from sma_user where id = '$sendto' ";
	//echo $sql."<BR>";			
					$result = mysqli_query($con, $sql);
					while( $r = mysqli_fetch_object($result) ){
						$username 		= $r->userid;
						$id		 		= $r->id;
						$role	 		= $r->role;
						$company_id 	= $r->company_id;
						$user_category 	= $r->user_category;
						$user_email		= $r->email;
						$user_name		= $r->username;
						$mail->addAddress($user_email, $user_name);
					echo $user_email."###1";	
					}
				}
		}
	}
	
//echo " Mail program....";
	
	$scnt = sizeof($send_to_arr);
	if ($scnt>0){
	foreach ($send_to_arr as $sendto){
			$status = 'Shared';
			if( !empty($sendto) ){
			$sql="select * from sma_user where id='$sendto' ";
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
					echo $user_email."###2";		
				}
			}
		}
	}
//exit();
	//$mail->addAddress('ranganathan.n@highwayconcessions.com', 'Rajesh Ranganathan');
	//$mail->addAddress('nitkot@gmail.com', 'Nitin Kothari');
	//Set the subject line
	$mail->Subject = $subject;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$message = "Status : " . $status;
						
						$sql = " SELECT * FROM `dms_inward` where inward_no = '$inward_no' ";
						$qry = mysqli_query($con, $sql);
						$r2	 = mysqli_fetch_array($qry);
						$description		= $r2['remarks'];		
						if(!empty($description)){
							$message  .= "<BR><br>"."Description : ". $description . "<BR><br>";
						}
						
	$body .= 'Dear Sir / Madam,'. "<br><br>"; 
	//Here's the document that Narayanan S shared with you.
	$body .= "Please find here Document that " . $user_name_by . ' ' . $status . " with you, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
	if(!empty($remarks)){
		$body  .= "Remarks : ". $remarks . "<BR><br>";
	}
	
	$snd  = '';
	$scnt = '';	
	
	if(empty($snd)){
		$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
				
		$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

		$mail->MsgHTML($body);

		//send the message, check for errors
		if (!$mail->send()){
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent! xyz";
			$i='';
			//exit();
			
		}
	}
//exit();

//echo $body;
//External Email ID with OTP
	
	$scnt ='';
	
	if(!empty($ext_email_arr)){
		$scnt = sizeof($ext_email_arr);
	}

//echo $scnt. " <<>> ". $ext_email_arr. "<<<>>>";	exit();
	if ($scnt>0){
		
		$inward_no 			= $_POST['inward_no'];
		foreach ( $ext_email_arr as $ext_mail ){
			$six_digit_random_number = mt_rand(100000, 999999);
			//$six_digit_random_number='';
			//echo $ext_mail. ' ' . $six_digit_random_number. "<BR>";
			if(!empty($ext_mail)){
				$sql = "select * from forward_share_doc where userid = '$userid' "; // and status = 'Forwarded' ";
					$rs = mysqli_query($con, $sql);
					$numrow = mysqli_affected_rows($con);
					echo mysqli_error($con);
					if($numrow>0){
						while($rw = mysqli_fetch_array($rs)){
							
							//$userid  		= $rw['userid']; 
							//$dated		= $rw['dated']; 
							$inward_no		= $rw['doc_id'];
							//$doc_type		= $rw['doc_type']; 
							//$status		= $rw['status']; 
							//$flag			= $rw['flag'];
							$sql = " INSERT INTO external_user_mail ( userid, inward_no, email_id , otp, tdate, otp_date, one_time, ipaddress ) 
									 values ( '$userid', '$inward_no', '$ext_mail', '$six_digit_random_number', now(), now(), '$one_time', '$ipaddress' )";
					
							mysqli_query( $con, $sql );
							echo mysqli_error( $con );
							$unq_no = mysqli_insert_id($con);
					//echo $sql."<BR>";
					//echo $ext_mail. "<BR>";					
					
							$status = 'Shared';
						
							$sql 	= "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, sent_to, remarks, approved_date ) 
											values( 'IN', '$inward_no', '$userid', now(), '$status', '$ext_mail', '$remarks', now() )";
							$r2  = mysqli_query($con, $sql);

							$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id,  shared_date, status, remarks ) 
										values ( 'IN', '$inward_no', '$ext_mail', '$userid',  now(), '$status', '$remarks' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);
							
								//require '../PHPMailer-master/PHPMailerAutoload.php';
								//Create a new PHPMailer instance
								$mail = new PHPMailer;	
								$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
								$mail->SMTPAutoTLS = false;
								
								$mail->IsSMTP();
								$mail->Host = "outlook.office365.com";
								$mail->SMTPAuth = true;
								$mail->Username = 'workflow@highwayconcessions.com';
								$mail->Password = 'highway@1234';
								$mail->Port       = "587";                    // set the SMTP port
								
								$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');
								$body = '';
								$mail->Subject = $subject;
								$message = "Status : " . $status;
										
								$sql = " SELECT * FROM `dms_inward` where inward_no = '$inward_no' ";
								$qry = mysqli_query($con, $sql);
								$r2	 = mysqli_fetch_array($qry);
								$description		= $r2['remarks'];		
								if(!empty($description)){
									$message  .= "<BR><br>"."Description : ". $description . "<BR><br>";
								}						
								$body .= 'Dear Sir / Madam,'. "<br><br>"; 
								//Here's the document that Narayanan S shared with you.
								$body .= "Please find here Document that " . $user_name_by . ' ' . $status . " with you, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
								if(!empty($remarks)){
									$body  .= "Remarks : ". $remarks . "<BR><br>";
								}

							$mail->clearAddresses();
							$mail->addAddress($ext_mail, ' External Mail User');
							//$mail->addAddress($ext_mail, ' External Mail User');					
						
							//echo $sql."<BR>";
							//echo $ext_mail. "<BR>";					
								
							$baseurl1 =$baseurl.$modulePath.'extuser_otp.php?sub=edit&inward_no='.$inward_no.'&unq_no='.$unq_no;
							
							$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
								
								$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

								$mail->MsgHTML($body);
								if (!$mail->send()){
									echo "Mailer Error: " . $mail->ErrorInfo;
								} else {
									echo "Message sent! 123";
									$i='';
								}
									
						}
					}
				else {
					
					$six_digit_random_number = mt_rand(100000, 999999);
					//$six_digit_random_number='';
					$sql = " INSERT INTO external_user_mail ( userid, inward_no, email_id , otp, tdate, otp_date, one_time, ipaddress ) 
									 values ( '$userid', '$inward_no', '$ext_mail', '$six_digit_random_number', now(), now(), '$one_time', '$ipaddress' )";
					//echo $sql;				 
					mysqli_query( $con, $sql );
					echo mysqli_error( $con );
					$unq_no = mysqli_insert_id($con);
					
					$status = 'Shared';
						
					$sql 	= "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, sent_to, remarks, approved_date ) 
								values( 'IN', '$inward_no', '$userid', now(), '$status', '$ext_mail', '$remarks', now() )";
					$r2  = mysqli_query($con, $sql);

					$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id,  shared_date, status, remarks ) 
								values ( 'IN', '$inward_no', '$ext_mail', '$userid',  now(), '$status', '$remarks' ) ";
						mysqli_query($con, $sql);
						echo mysqli_error($con);
					
						//require '../PHPMailer-master/PHPMailerAutoload.php';
						//Create a new PHPMailer instance
						$mail = new PHPMailer;	
						$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
						$mail->SMTPAutoTLS = false;
						
						$mail->IsSMTP();
						$mail->Host = "outlook.office365.com";
						$mail->SMTPAuth = true;
						$mail->Username = 'workflow@highwayconcessions.com';
						$mail->Password = 'highway@1234';
						$mail->Port       = "587";                    // set the SMTP port
						
						$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');
						$body = '';
						$mail->Subject = $subject;
						$message = "Status : " . $status;
						
						$sql = " SELECT * FROM `dms_inward` where inward_no = '$inward_no' ";
						$qry = mysqli_query($con, $sql);
						$r2	 = mysqli_fetch_array($qry);
						$description		= $r2['remarks'];		
						if(!empty($description)){
							$message  .= "<BR><br>"."Description : ". $description . "<BR><br>";
						}						
						
						$body .= 'Hi '. $user_name. "<br><br>"; 
						//Here's the document that Narayanan S shared with you.
						$body .= "Please find here Document that " . $user_name_by . ' ' . $status . " with you, ". $msg ."<br><br>" . "Today Date: ". date('d-m-Y') . "<br><br>". $message. "<br><br>";
						if(!empty($remarks)){
							$body  .= "Remarks : ". $remarks . "<BR><br>";
						}
					
						$mail->clearAddresses();
						$mail->addAddress($ext_mail, ' External Mail User');
				
					//echo $sql."<BR>";
					//echo $ext_mail. "<BR>";					
					
						$baseurl1 =$baseurl.$modulePath.'extuser_otp.php?sub=edit&inward_no='.$inward_no.'&unq_no='.$unq_no;
					
						$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
						
						$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

						$mail->MsgHTML($body);
						if (!$mail->send()){
							echo "Mailer Error: " . $mail->ErrorInfo;
						} else {
							echo "Message sent! abc";
							$i='';
						}
				}
					
		    }
			$snd = 'Y';
	    }
	}
	
/* echo $snd. "  <<< Exit....###>>";
exit();

 */
/* 
if(empty($snd)){
	$body .= '<a href="'.$baseurl1.'" style="border: 2px solid; text-decoration: none;color: yellow;background: blue;padding: 10px; " >Click Document </a> <br><br>';
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	$mail->MsgHTML($body);
	
	//send the message, check for errors
	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent! xyz";
		$i='';
		//exit();
		
	}
} */

//exit();
