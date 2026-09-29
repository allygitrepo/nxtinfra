<?php 

	include("../dbcon.php");
	$modulePath = "payment/";
	$message = '';
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12pt;'>";
	
	$message .= "<tr>
			<th>Transaction type (Within Bank (WIB)/NEFT (NFT)/RTGS (RTG)/IMPS (IFC))</th>
			<th>Amount (Rs.) (Should not be more than 15 digit including decimals and paise)</th>
			<th>Debit Account no Should be exactly 12 digit</th>
			<th>IFSC (Always 11 character alphanumeric and 5th character always 0 (zero)) (For ICICI bank accounts keep it blank)</th>
			<th>Beneficiary Account No (Max length for other bank 34 character alphanumeric and for ICICI Bank 12 digit number )</th>
			<th>Beneficiary Name (Max length 32 Character) (No Special Character is allowed but Space is allowed)</td>
			<th>Remarks for Client (should not be more than 21 characters)</td>
			<th>Remarks for Beneficiary (should not be more than 30 characters)</td>
		</tr>";	
		
	$sql   = "SELECT * from rtgs_temp where 1 and selected = 'Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$py_id					= $row['py_id'];
		$paid_date 				= $row['paid_date'];
		$cash_bank_name 		= $row['cash_bank_name'];
		$party_name 			= $row['party_name'];
		$dated 					= $row['dated'];
		$supplier_invoice_no 	= $row['supplier_invoice_no'];
		$supp_id				= $row['supp_id'];
		$st_flag 				= $row['st_flag'];
		$total_amount_paid 		= $row['total_amount_paid'];
		
		$sql    = " SELECT * from payment_header where 1 and id = '$py_id' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$r2 = mysqli_fetch_array($res);
		
		$paid_date  			= date('d-m-Y', strtotime($r2['paid_date']));
		
		$pur_req_no				= $r2['id'];
		$paid_date  			= date('d-m-Y', strtotime($r2['paid_date']));
		$dated  				= date('d-m-Y', strtotime($r2['dated']));
		$company_id				= $r2['company_id'];
		$paid_to				= $r2['paid_to'];
		$cash_bank_name			= $r2['cash_bank_name'];
		$total_amount_paid		= round($r2['total_amount_paid'],0);
		$tds_amount				= $r2['tds_amount'];
		$utr_no					= $r2['utr_no'];
		
		$cheque_no				= $r2['cheque_no'];
		$remarks				= $r2['remarks'];
		$rtgs_narration			= $r2['rtgs_narration'];
		$maker_date				= date('d-m-Y h:m i', strtotime($r2['draft_dated']));
		$st_flag				= $r2['st_flag'];
		
		$approval_status		= $r2['approval_status'];
		$status					= $r2['status'];
		
		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C' ){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
	
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			//$party_name  	 		 = $s1['party_name'];
			$party_name				 = $s1['party_beneficiary_name'];
			$party_bank_name 		 = $s1['party_bank_name'];
			$party_bank_account_type = $s1['party_bank_account_type'];
			$party_bank_address  	 = $s1['party_bank_address'];
			$party_bank_account_no 	 = $s1['party_bank_account_no'];
			$party_bank_ifsc_code  	 = $s1['party_bank_ifsc_code'];
		
		}
		else if( $st_flag== 'A' || $st_flag == 'T' ){
			$sql 	= "SELECT * FROM sma_user where id = '$paid_to'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			$party_name  	 		 = $s1['username'];
			$party_bank_name 		 = $s1['bank_name'];
			$party_bank_account_type = $s1['bank_type'];
			$party_bank_address  	 = $s1['bank_branch'];
			$party_bank_account_no 	 = $s1['bank_ac_no'];
			$party_bank_ifsc_code  	 = $s1['bank_ifsc'];
		
		}
		
		$var_account = "055505009542";
		$message .= "<tr>
						<td>NFT</td>
						<td style='text-align:right;'>$total_amount_paid</td>
						<td>&nbsp;".strval($var_account)."</td>
						<td>$party_bank_ifsc_code</td>
						<td>&nbsp;".$party_bank_account_no."</td>
						<td>$party_name</td>
						<td>$rtgs_narration</td>
						<td>$rtgs_narration</td>
					</tr>";
					
	}
	

	$message .="</table>";
	
//	echo $message;
//	exit();
	
		$fl_name = 'aipl_rtgs_export_'.date("d-m-Y").'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
		
?>		
		