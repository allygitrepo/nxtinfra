<?php
if($_GET['sub'] == 'pdf'){
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	
	$message ='';

	//$message .='<p>&nbsp;</p>';

   if( $comp_code=='BETPL' ){
		$img_flname_hdr = 'BETPL_Letter_Header.jpg';
		$img_flname_ftr = 'BETPL_Letter_Head_Bottom.jpg';
	}
	else if ($comp_code=='GEPL' ){
		$img_flname_hdr = 'GEPL_Letter_Head_Header.jpg';
		$img_flname_ftr = 'GEPL_Letter_Head_Bottom.jpg';
	}
	else if ($comp_code=='DBCPL' ){
		$img_flname_hdr = 'DBCPL_Letter_Head_header.jpg';
		$img_flname_ftr = 'DBCPL_Letter_Head_Bottom.jpg';
	}
	else if ($comp_code=='JPEPL' ){
		$img_flname_ftr = 'JPEPL_Letter_Head_Bottom.jpg';
		$img_flname_ftr = 'JPEPL_Letter_Head_Header.jpg';
	}
	else if ($comp_code=='NBL' ){
		$img_flname_ftr = 'NBL_Letter_Head_Bttom.jpg';
		$img_flname_hdr = 'NBL_Letter_Head_Header.jpg';
	}
	else if ($comp_code=='UEPL' ){
		$img_flname_ftr = 'UEPL_Letter_Head_Bottom.jpg';
		$img_flname_hdr = 'UEPL_Letter_Head_Header.jpg';
	}
	else if ($comp_code=='SEPL' ){
		$img_flname_hdr = 'SEPL_Letter_Head_Header.jpg';
		$img_flname_ftr = 'SEPL_Letter_Head_Bottom.jpg.jpg';
	}

	$message .= "<table><td> <img src='img/".$img_flname_hdr."' width='100%' height='50%' ></td> </table>";

	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: center; font-size: 15px;margin-left:10px;'>
				<tr ><td style='width: 20%;' > HDFC Bank <br> <span style='font-size: 12px;' >We understand your world</span></td><td style='width: 80%;' > Application Form for Funds Transfer Through <br> Real Time Gross Settlement (RTGS) /National Electronic Funds Transfer (NEFT) </td></tr></table>";
	
//echo $message; exit();					
						

	$id				= $_GET['id'];
	$tableName		= "payment_header";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$pur_req_no				= $row['id'];
		$paid_date  			= date('d-m-Y', strtotime($row['paid_date']));
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$paid_to				= $row['paid_to'];
		$cash_bank_name			= $row['cash_bank_name'];
		$total_amount_paid		= round($row['total_amount_paid'],0);
		$tds_amount				= $row['tds_amount'];
		$cheque_no				= $row['cheque_no'];
		$utr_no					= $row['utr_no'];
		$remarks				= $row['remarks'];
		$rtgs_narration			= $row['rtgs_narration'];
		$maker_date				= date('d-m-Y h:m i', strtotime($row['draft_dated']));
		$st_flag				= $row['st_flag'];
		
		$approval_status		= $row['approval_status'];
		$status					= $row['status'];
		
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$id' ";		
	//	echo $sql;
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$actual_amount      = $res2['amount'];
			$account_name1 		= $res2['account_name'];
			$total_amount_paid =  round($actual_amount,0);
		}
		
		$draft_mode = '';
		if($approval_status!='Approved'){
			
			$draft_mode = 'Draft';
			if( ($comp_code=='GEPL' || $comp_code=='BEPTL' || $comp_code=='DBCPL' ) && $status == 'Verified' ){
				//Verified
				$draft_mode = '';
			}
			
		}	
		

		$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$error  			= mysqli_error($con);
		$com 				= mysqli_fetch_array($comresult);
		
		$comp_name 			= $com['comp_name'];
		
		$comp_code 			= $com['comp_code'];
		$comp_addr1 		= $com['comp_addr1'];
		$comp_addr2 		= $com['comp_addr2'];
		$comp_addr3 		= $com['comp_addr3'];
		$comp_email 		= $com['comp_email'];
		$comp_office 		= $com['comp_office'];
		$comp_mobile 		= $com['comp_mobile'];
		$comp_city  		= $com['comp_city'];
		$comp_pincode 		= $com['comp_pincode'];
		$comp_country 		= $com['comp_country'];
		$comp_faxno 		= $com['comp_faxno'];
		$comp_cin_no 		= $com['comp_cin_no'];
		$comp_pan_no 		= $com['comp_pan_no'];
		$bank_ac_title		= $com['bank_ac_title'];
		$bank_ifsc_code		= $com['bank_ifsc_code'];
		$bank_ac_number		= $com['bank_ac_number'];
		$bank_ac_type		= $com['bank_ac_type'];
		$bank_ac_branch		= $com['bank_ac_branch'];


		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C'){
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
		
		
		$sql 	= "SELECT * FROM account_mst where id = '$cash_bank_name'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$account_name  	 = $s1['account_name'];
		$account_type  	 = $s1['account_type'];
		$branch		  	 = $s1['branch'];
		$address	  	 = $s1['address'];
		$account_number	 = $s1['account_number'];
		$isfc_code		 = $s1['isfc_code'];
		$email			 = $s1['email'];	
		$mobile   		 = $s1['mobile'];	

	}
	
	if(empty($cheque_no)){
		$cheque_no = $utr_no;
	}
	
	
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 25%;'> Branch Code / Name </td><td style='width: 25%;text-align: left;'>".$branch."</td><td style='width: 50%;'> Maximum Limit for NEFT Transaction </td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 25%;'> Date </td><td style='width: 25%;'>&nbsp;</td><td style='width: 25%;'> HDFC Bank Customer</td><td style='width: 25%;'>No Limit </td ></tr> </table>";
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 25%;'> Time </td><td style='width: 25%;'></td><td style='width: 25%;'> Non HDFC Bank Customer & Indo-Nepal NEFT Remittance</td><td style='width: 25%;'>Up to INR 50,000/-</td ></tr> </table>";	
	
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 100%;'> You are requested to remit the proceeds as per details below through RTGS  / NEFT (Tick /the appropriate Box Attaching 	</td></tr></table>";
				
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 25%;'> Cheque No.</td><th style='width: 25%;'>".$cheque_no ."</th><td style='width: 25%;text-align:right;'> for Rs. </td><td style='width: 25%;'>".moneyFormatIndia($total_amount_paid)."/-</td ></tr> </table>";	
	
	$message .= "<table cellspacing='0' border='.05'style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 100%;'> (For R TGS draw cheque favoring 'HDFC Bank Ltd — RTGS' and for NEFT draw cheque favoring 'HDFC Bank Ltd — NEFT')</td></tr></table>";			
				 									
	$message .= "<table cellspacing='0' border='.05' style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'>
				<tr><th style='width: 100%;'> Beneficiary Details</th></tr></table>";
	
	if(!empty($draft_mode)){	
		$message .= "<p style='width: 95%;text-align: Center;color:#c9d6d6;font-size:72;position: absolute;	left: 0px;	top:150px;	z-index: -20;'>$draft_mode </p>";
	}
				
	$amt_word=numbertoword($total_amount_paid);
	
	$message .= "<table border='1' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 13px;' >
			<tr>
				<td style='width: 50%;text-align: left;'> Beneficiary Name </td>
				<td style='width: 50%;text-align: left;'> " . $party_name."</td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Beneficiary Account Number </td>
				<td style='width: 50%;text-align: left;'> " . $party_bank_account_no. " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Beneficiary Address	 </td>
				<td style='width: 50%;text-align: left;'> " . ' ' . $party_bank_address . " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Beneficiary Bank Name & Branch		 </td>
				<td style='width: 50%;text-align: left;'> " .$party_bank_name. ' ' . $party_bank_address . " </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'> Beneficiary Bank IFSC Code</td>
				<td style='width: 50%;text-align: left;'> " . $party_bank_ifsc_code . " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Amount (in figures) to be credited </td>
				<td style='width: 50%;text-align: left;'> Rs." .moneyFormatIndia($total_amount_paid). "/- </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Amount (in words) to be credited </td>
				<td style='width: 50%;text-align: left;'> Rupees " . $amt_word  . " Only </td>
			</tr>
			
			
			</table>";
	
				
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:12px;'>
				<tr><th style='width: 100%;'> My/ Our Details (Remitter)</th></tr></table>";	
			
		$message .= "<table border='1' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 13px;' >
			<tr>
				<td style='width: 50%;text-align: left;'> Remitter (Applicant) Name </td>
				<td style='width: 50%;text-align: left;'>" . $comp_name."</td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Remitter Account Number </td>
				<td style='width: 50%;text-align: left;'> " . $account_number. " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Cash Deposited (Non HDFC Bank Customer) </td>
				<td style='width: 50%;text-align: left;'> " . ' ' .  " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Mobile / Phone Number of Remitter ( Mandatory) </td>
				<td style='width: 50%;text-align: left;'> " .$mobile . ' Email :' . $email . " </td>
			</tr>
			<tr>
				<td style='width: 50%;text-align: left;'> Address of the Remitter (Mandatory for Non — HDFC Bank Customer)</td>
				<td style='width: 50%;text-align: left;'> " . $address . ' ' .$branch . " </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'> Remarks </td>
				<td style='width: 50%;text-align: left;'> " . $rtgs_narration . " </td>
			</tr>
			</table>";

			$message .= '<table border=".5" cellspacing="-1" style="width: 95%; text-align: center; margin-left:10px;font-size: 10px;" >
				<tr>
				<td style="width: 100%;text-align: left;">			
					Terms & Conditions 	<br>								
					1/ We hereby authorize HDFC Bank Ltd. to carry out the RTGS / NEFT transaction as per details mentioned above. (Tick "the appropriate Box) <br>					
					1/ We hereby agree that the aforesaid details including the IFSC code and the beneficiary account are correct.	<br>								
					1/ We further acknowledge that HDFC Bank accepts no liability for any consequences arising out of erroneous details provided by me/us.	<br>
					I / We agree that the credit will be affected solely on the beneficiary account number information and beneficiary name particulars will not be used for the same.<br>
					I / We authorize the bank to debit my / our account with the charges plus taxes as applicable for this transaction.	<br>
					I / We agree that requests submitted after the cut off time will be sent in next batch or next working day as applicable.<br>
					I / We hereby agree & understand that the RTGS / NEFT request is subject to the RBI regulations and guidelines governing the same. <br>
					I / We also understand that the remitting Bank shall not be liable for any loss of damage arising or resulting from delay in transmission delivery or non-delivery of Electronic message or any mistake, omission, or error in transmission or delivery thereof or in deciphering the message from any cause whatsoever or from its misinterpretation received or the action of the destination Bank or any act or even beyond control. <br>		
					I/We agree that incase of NEFT Transaction if we do not have an account with the bank, we will produce Original identification proof while giving the request. In case I/We submit form 60, we will also submit the address proof. <br>
					In case the RTGS and NEFT option is not ticked by us, I / We authorize you to execute the transaction less than Rupees Two Lacs through NEFT and greater than or equal to Rupees Two Lacs through RTGS and debit  the charges as applicable. <br>
				</td>
				</tr>
				</table>';
				
			$message .= '<table border=".5" cellspacing="-1" style="width: 95%; text-align: center; margin-left:10px;font-size: 12px;" >
				<tr><td style="width: 25%;text-align: left;"><br> <br><br>Signature of Authorized Signatory <br><br></td>
					<td style="width: 25%;text-align: left;"><br><br>1st Signatory</td>
					<td style="width: 25%;text-align: left;"><br><br>2nd Signatory </td>
					<td style="width: 25%;text-align: left;"><br><br> 3rd Signatory </td></tr>
				</table>';
				
			$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16px;margin-left:12px;'>
				<tr><th style='width: 100%;'> Branch Use Only</th></tr></table>";	
				
			$message .= "<table border='.5' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 12px;' >
			<tr>
				<td style='width: 50%;text-align: left;'> Transaction Reference Number </td>
				<td style='width: 50%;text-align: left;'> </td>
			</tr>
			</table>";
		
			
			$message .= "<table border='.5' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 12px;' >
			<tr>
				<td style='width: 25%;text-align: left;'> Transaction Inputted by </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'> Transaction Authorized by </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'>Transaction Authorized by (2n1 level) (for amount > Rs. 5 lacs) </td>
				<td style='width: 25%;text-align: left;'> &nbsp;</td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'>KYC documentation done by (only for Non-HDFC Bank Customers) </td>
				<td style='width: 25%;text-align: left;'>&nbsp;</td>
				<td style='width: 25%;text-align: left;'> &nbsp;</td>
				<td style='width: 25%;text-align: left;'>&nbsp; </td>
			</tr>
			</table>";
			
			$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16px;margin-left:10px;'>
				<tr><th style='width: 100%;'> Customer Acknowledgement</th></tr></table>";	
					
			$message .= "<table border='.5' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 12px;' >
			<tr>
				<td style='width: 30%;text-align: left;'> Received application for RTGS / NEFT for an amount of Rs.</td>
				<td style='width: 20%;text-align: left;'> ". number_format($total_amount_paid,0) . "/-</td>
				<td style='width: 25%;text-align: right;'> vide cheque number </td>
				<td style='width: 25%;text-align: left;'> ".$cheque_no ."</td>
			</tr>
			<tr>
				<td style='width: 30%;text-align: left;'> to be credited to Account Number</td>
				<td style='width: 20%;text-align: left;'> 011012100002497</td>
				<td style='width: 25%;text-align: left;'> Apna Sahakari Bank Ltd,  Kurla Mumbai - 400070</td>
				<td style='width: 25%;text-align: left;'> with IFSC Code	ASBL0000011</td>
			</tr>
			</table>";

			$message .= "<table border='.5' cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 100%;'>Customers will be guided by the Terms and Conditions mentioned in the form HDFC  Bank will accept no liability for any consequences arising out of erroneous details provided by the Customer.	</td></tr></table>";	
				
			$message .= "<table border='.5' cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:10px;'>
				<tr><td style='width: 100%;'>Date Time Branch Stamp & Sign</td></tr></table>";	
									
//		$message .= "<table><td> <img src='img/HC1_Letter Head_Bottom.jpg' width='90%' height='90%' ></td> </table>";
		
			$message .= "<table><td style='text-align:center;'> <img src='img/".$img_flname_ftr."' width='90%' height='90%' ></td> </table>";


print $message;
exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'neft'.$id. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}	

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'neft_'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
}

 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertoword_1($num){
	   $number = $num;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
	  if(!empty($points)){
			$points = $points . " Paise";
		}
		else{$points='';}
	  //echo $result . "Rupees  " . $points . " Paise";
	  $words=ucwords($result) . " " . $points;
	  return $words;
	}
