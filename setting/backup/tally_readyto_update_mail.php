	<?php

		include "../dbcon.php";
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */
	
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
	$mail->Host = "outlook.office365.com";
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = "ssl";
	$mail->Username = 'workflow@highwayconcessions.com';
	$mail->Password = 'highway@1234';
	$mail->Port       = "587";                    // set the SMTP port
	
	// Set PHPMailer to use the sendmail transport
	$mail->setFrom('workflow@highwayconcessions.com', 'Highway Concession-Workflow');

		
	$msg =  '';
	$today_date = date('Y-m-d');
	
	$msg .='<br> Payment ';
	$msg .= "<table cellspacing='0' border='1'> ";
	$msg .= "<tr><td>Srno.</td>
					<td>Company</td>
					<td>Vendor</td>
					<td>Amount</td>
					<td>Exp.Type</td>
					<td>Status</td>
					<td>Tally Updated on</td>
					<td>Ticked by</td>
					<td>Module</td></tr>";
					
 	$sql   = " SELECT * FROM `payment_header` where tally_updated_on >= '$today_date' and tally_status = 'R' "; 
//echo $sql;	
	$result  = mysqli_query($con, $sql);
	while($row = mysqli_fetch_object($result)){
		
		$company_id 			= $row->company_id;
		$id		 				= $row->id;
		$tally_updated_on		= $row->tally_updated_on;
		$tally_ticked_by		= $row->tally_ticked_by;
		$paid_to				= $row->paid_to;
		$st_flag				= $row->st_flag;
		$status					= $row->status;
		$total_amount_paid		= $row->total_amount_paid;
		$rem					= 'Payment';
		
		$sql = "select * from company where comp_id ='$company_id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$comp_name 		= $r->comp_name;
		$comp_code 		= $r->comp_code;
		
		$sql = "select * from sma_party_mst where id ='$paid_to' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$party_name 		= $r->party_name;
		
		$stflag = $st_flag;
		if($st_flag=='C'){ $stflag='Company Expense'; }
		if($st_flag=='R'){ $stflag='Regular Expense'; }
		if($st_flag=='T'){ $stflag='Traval Expense'; }
		if($st_flag=='S'){ $stflag='Supplier Invoice'; }
		if($st_flag=='D'){ $stflag='Advance'; }
		if($st_flag=='R'){ $stflag='Retention'; }
		
		$msg .= "<tr><td>$id</td>
					<td>$comp_code</td>
					<td>$party_name</td>
					<td style='text-align:right'>$total_amount_paid</td>
					<td>$stflag</td>
					<td>$status</td>
					<td>$tally_updated_on</td>
					<td>$tally_ticked_by</td>
					<td>$rem</td></tr>";
	}
	
	$msg .="</table>";
	
	$msg .="<br> Supplier Invoice";
	
	$msg .= "<table cellspacing='0' border='1'> ";
	$msg .= "<tr><td>Srno.</td>
					<td>Company</td>
					<td>Vendor</td>
					<td>Amount</td>
					<td>Invoice No.</td>
					<td>Status</td>
					<td>Tally Updated on</td>
					<td>Ticked by</td>
					<td>Module</td></tr>";
					
 	$sql   = " SELECT * FROM `sma_supplier_invoice` where tally_updated_on >= '$today_date' and tally_status = 'R' "; 
//echo $sql;	
	$result  = mysqli_query($con, $sql);
	while($row = mysqli_fetch_object($result)){
		
		$company_id 			= $row->company_id;
		$id		 				= $row->id;
		$tally_updated_on		= $row->tally_updated_on;
		$tally_ticked_by		= $row->tally_ticked_by;
		$paid_to				= $row->suplier_name;
		$supplier_invoice_no	= $row->supplier_invoice_no;
		$status					= $row->status;
		$total_amount			= $row->total_amount;
		$rem					= 'Supplier Invoice';
		
		$sql = "select * from company where comp_id ='$company_id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$comp_name 		= $r->comp_name;
		$comp_code 		= $r->comp_code;
		
		$sql = "select * from sma_party_mst where id ='$paid_to' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$party_name 		= $r->party_name;
		
		
		
		$msg .= "<tr><td>$id</td>
					<td>$comp_code</td>
					<td>$party_name</td>
					<td style='text-align:right'>$total_amount</td>
					<td>$supplier_invoice_no</td>
					<td>$status</td>
					<td>$tally_updated_on</td>
					<td>$tally_ticked_by</td>
					<td>$rem</td></tr>";
	}
	
	$msg.="</table>";
	$msg .="<br> Company / Travel / Regular Expense";
	
	$msg .= "<table cellspacing='0' border='1'> ";
	$msg .= "<tr><td>Srno.</td>
					<td>Company</td>
					<td>Vendor/User</td>
					<td>Amount</td>
					<td>Status</td>
					<td>Tally Updated on</td>
					<td>Ticked by</td>
					<td>Module</td></tr>";
					
 	$sql   = " SELECT * FROM `sma_travel_expenses` where tally_updated_on >= '$today_date' and tally_status = 'R' "; 
//echo $sql;	
	$result  = mysqli_query($con, $sql);
	while($row = mysqli_fetch_object($result)){
		
		$company_id 			= $row->company_id;
		$id		 				= $row->id;
		$tally_updated_on		= $row->tally_updated_on;
		$tally_ticked_by		= $row->tally_ticked_by;
		$paid_to				= $row->emp_id;
		$exp_type				= $row->exp_type;
		$status					= $row->status;
		$total_amount			= $row->total_amount;
		//$rem					= 'Supplier Invoice';
		
		$sql = "select * from company where comp_id ='$company_id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$comp_name 		= $r->comp_name;
		$comp_code 		= $r->comp_code;
		
		if($exp_type=='C'){
			
			$sql = "select * from sma_party_mst where id ='$paid_to' ";
			$res1 = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($res1);
			$party_name 		= $r->party_name;
			$rem = 'Company Expense';
			
		}
		else if($exp_type=='T' || $exp_type=='R'){
			
			$sql = "select * from sma_user where id ='$paid_to' ";
			$res1 = mysqli_query($con, $sql);
			$r    = mysqli_fetch_object($res1);
			$party_name 		= $r->username;
			if( $exp_type=='T' ){
				$rem = 'Travel Expense';
			}
			else if( $exp_type=='R'){
				$rem = 'Regular Expense';
			}
			
		}
		
		$msg .= "<tr><td>$id</td>
					<td>$comp_code</td>
					<td>$party_name</td>
					<td style='text-align:right'>$total_amount</td>
					<td>$status</td>
					<td>$tally_updated_on</td>
					<td>$tally_ticked_by</td>
					<td>$rem</td></tr>";
	}
	
	$msg.="</table>";
	
	$msg.="<br> Petty Cash	";
	$msg .= "<table cellspacing='0' border='1'> ";
	$msg .= "<tr><td>Srno.</td>
					<td>Company</td>
					<td>Location</td>
					<td>Vendor/User</td>
					<td>Type</td>
					<td>Amount</td>
					<td>Status</td>
					<td>Tally Updated on</td>
					<td>Ticked by</td>
					<td>Module</td></tr>";
					
 	$sql   = " SELECT * FROM `sma_pettycash` where tally_updated_on >= '$today_date' and tally_status = 'R' "; 
//echo $sql;	
	$result  = mysqli_query($con, $sql);
	while($row = mysqli_fetch_object($result)){
		
		$company_id 			= $row->company_id;
		$location_id 			= $row->location_id;
		$id		 				= $row->id;
		$tally_updated_on		= $row->tally_updated_on;
		$tally_ticked_by		= $row->tally_ticked_by;
		
		$trans_type				= $row->trans_type;
		$status					= $row->status;
		$total_amount			= $row->total_amount;
		$rem					= 'Petty Cash';
		
		$sql = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no ='$id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$spend_by				= $row->spend_by;
		$paid_to				= $row->paid_to;
		
		$sql = "select * from sma_location where id ='$location_id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$loc_name 		= $r->loc_name;
		
		
		$sql = "select * from company where comp_id ='$company_id' ";
		$res1 = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($res1);
		$comp_name 		= $r->comp_name;
		$comp_code 		= $r->comp_code;
		
		$party_name  	= $spend_by;
		if($paid_to=='V'){
			
			$sql = "select * from sma_party_mst where id ='$spend_by' ";
			$res1 = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($res1);
			$party_name 		= $r->party_name;
			$rem = 'Company Expense';
			
		}
		else if($exp_type=='U' ){
			
			$sql = "select * from sma_user where id ='$spend_by' ";
			$res1 = mysqli_query($con, $sql);
			$r    = mysqli_fetch_object($res1);
			$party_name 		= $r->username;
			
		}
		
		$msg .= "<tr><td>$id</td>
					<td>$comp_code</td>
					<td>$loc_name</td>
					<td>$party_name</td>
					<td>$trans_type</td>
					<td style='text-align:right'>$total_amount</td>
					<td>$status</td>
					<td>$tally_updated_on</td>
					<td>$tally_ticked_by</td>
					<td>$rem</td></tr>";
	}
	
	$msg.="</table>";
	
	//Set who the message is to be sent to BCC
	$mail->addAddress($mail_to, 'First Gmail');
	$mail->addBCC($mail_to, 'First Gmail');
	
	$user_email1 	= 'ranganathan.n@highwayconcessions.com';
	$user_name_by1	= 'IT Support';
	$mail->addReplyTo($user_email1, $user_name_by1);
	$mail->addAddress($user_email1, $user_name_by1);
	
	$user_email2 = 'ithcone@highwayconcessions.com';
	$mail->addReplyTo($user_email2, $user_name_by1);
	
	$mail->Subject = 'Records ready for update to Tally DB '.  date("d-m-Y");
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
	
	
	$body .= 'Hi ';
	$body .= '<br>'.'Records ready for update to Tally DB '.  date("d-m-Y");
	$body .= $msg ."<br><br>" ;
	//$body .= $baseurl1 . "<br><br>";


	$body .= "<br><br><br>"."Thank & Regards"."<br><br>"."Highway Concessions";

 /*  echo $body;
 exit(); */
  
/*
exit('STOPED HERE FOR MAIL....');
*/
 
	$mail->MsgHTML($body);

	//send the message, check for errors
 	if (!$mail->send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
		$i='';
	} 
//exit();
