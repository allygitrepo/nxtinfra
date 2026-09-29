	<?php
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
	session_start();
	
	include("../dbcon.php");

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

	//Set an alternative reply-to address
	//$mail->addReplyTo('replyto@example.com', 'First Last');
//	$mail->addReplyTo($user_email, $user_name);
	//Set who the message is to be sent to
	//$mail->addAddress('whoto@example.com', 'John Doe');
	$mail->addAddress($mail_to, 'First Gmail');
//	$mail->addAddress('ravindra_gandhile@yahoo.com', 'Ravindra Yahoo');
//	$mail->addAddress($user_email, $user_name);
	
	//$mail->addAddress('ranganathan.n@highwayconcessions.com', 'Rajesh Ranganathan');
	//$mail->addAddress('nitkot@gmail.com', 'Nitin Kothari');
	//Set the subject line
	$mail->Subject = 'P2P Daily Status Report';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	$sql = "SELECT * from sma_user ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$mis_alert_email = $row['mis_alert_email'];
		
		if($mis_alert_email=='Y'){
		
			$user_name  = $row['username'];
			$user_email = $row['email'];
			//$mail->addAddress($user_email, $user_name);
		
		}
		
	}
	
	$message = "";
	$body .= 'Hi '. "<br><br>";
	$body .= "Please find here MIS- Pending Workflow Report for the Date: ". date('d-m-Y') . "<br><br>";

//Approval Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Approval Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Approval Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_approval_memo`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Verified'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Approval Module	

//GRN SRN Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> GRN SRN Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'GRN SRN Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_grn_srn`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Verified'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//GRN SRN Module	
	
//Supplier Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Supplier Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Supplier Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_supplier_invoice`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Verified'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Supplier Module	
	
//Purchase Order Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Purchase Order Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Purchase Order Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_purchase_order`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Verified'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Purchase Order Module	
	
//IPC Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> IPC Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'IPC Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_ipc`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Prepared'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//IPC Module	
	
	
//Payment Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Payment Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Payment Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `payment_header`  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Verified'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Payment Module				
	
//Travel Request Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Travel Request Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Travel Request' as module, status, `approval_status` as approval_status, count(*) as cnt FROM `sma_traval_approval` where status !='Withdraw' group by  status, `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Prepared'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Oerating Expense Module	
	
	
//Travel Expense Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Travel Expense Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Travel Expense Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_travel_expenses` where exp_Type = 'T'  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Prepared'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Travel Expense Module	
	
//Regular Expense Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Regular Expense Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Regular Expense Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_travel_expenses` where exp_Type = 'R'  group by   `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Prepared'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Regular Expense Module	
	
//Oerating Expense Module	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'><tr><td style='width: 60%;background-color: yellow;'> Oerating Expense Module</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'><tr><td style='width: 20%;'> Pending</td><td style='width: 20%;'> Approved</td><td style='width: 20%;'> Rejected</td></tr></table>";
	
	$pcnt ='';
	$acnt ='';
	$rcnt ='';
	$sql="SELECT 'Oerating Expense Module' as module,  `approval_status` as approval_status, count(*) as cnt FROM `sma_travel_expenses` where exp_Type = 'C'  group by  `approval_status` ";
	
	$q2 	= mysqli_query($con, $sql);
	while($r2 = mysqli_fetch_array($q2)){
		
		$module = $r2['module'];
		$approval_status = $r2['approval_status'];
		if($approval_status =='Pending'){
			$pcnt += $r2['cnt'];
		}
		else if($approval_status =='Prepared'){
			$pcnt += $r2['cnt'];
		}
		if($approval_status =='Approved'){
			$acnt = $r2['cnt'];
		}
		if($approval_status =='Rejected'){
			$rcnt = $r2['cnt'];
		}
		
	}
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%;text-align: center; font-size: 14px;margin-left:10px;'>
				<tr><td style='width: 20%;'>$pcnt </td>
					<td style='width: 20%;'>$acnt</td>
					<td style='width: 20%;'>$rcnt</td>
				</tr></table>";
//Oerating Expense Module	
		
	
	$body .= $message;
	
	$body .= "<br><br><br>"."Thank & Regards"."<br>"."Highway Concessions";

	
echo $body;
exit();
	
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
		echo "Message sent!";
		$i='';
	}
//exit();
