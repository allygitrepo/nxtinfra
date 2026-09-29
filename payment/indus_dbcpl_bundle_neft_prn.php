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

//	$prn		= $_GET['sub'];
//	$id			= $_GET['id'];
//	$bank_id	= $_GET['bank_id'];
//echo $bank_id.' ###!<BR>'; exit();	
	$prn = 'pdf';
	$message ='';

//	$message .='<p>&nbsp;</p>';
//	$message .='<p>&nbsp;</p>';
//	$message .='<p>&nbsp;</p>';
//	$message .='<p>&nbsp;</p>';

	//$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:40px;'><tr><td style='width: 100%;'> APPLICATION FORM FOR RTGS / NEFT PAYMENT</td></tr></table>";
				
$head = $message;

//	$message .='<p>&nbsp;</p>';
//	$message .='<p>&nbsp;</p>';

	$id				= $_GET['id'];
	$tableName		= "payment_header";
//	$sql 	= "SELECT * FROM $tableName where id = '$id'";
	$sql 	= " SELECT * FROM $tableName where company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and cash_bank_name = '$bank_id' ";
	
	if(!empty($vendor_id)){
			$sql .= " and paid_to = '$vendor_id' ";
	}
//echo $sql; exit();

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
		$utr_no					= $row['utr_no'];
		
		$cheque_no				= $row['cheque_no'];
		$remarks				= $row['remarks'];
		$rtgs_narration			= $row['rtgs_narration'];
		$maker_date				= date('d-m-Y h:m i', strtotime($row['draft_dated']));
		$st_flag				= $row['st_flag'];
		
		$approval_status		= $row['approval_status'];
		$status					= $row['status'];
		
		$draft_mode = '';
		
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$id' ";		
	//	echo $sql;
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$actual_amount      = $res2['amount'];
			$account_name1 		= $res2['account_name'];
			$total_amount_paid =  round($actual_amount,0);
		}
		
//echo $draft_mode. ' '.$draft_mode_I. ' ' . $approval_status .' ' .$utr_no. ' ' . $comp_code. ' ' . "<###1>><br>";

		/* if($approval_status!='Approved'){
			
			$draft_mode = 'Draft';			
		
			if( ($comp_code=='GEPL' || $comp_code=='BETPL' || $comp_code=='DBCPL' ) && $status == 'Verified' ){
				//Verified
				$draft_mode_I = $draft_mode;
				$draft_mode = '';
			}
			
		}
		else if(!empty($utr_no)){
			$font_size = 36;	
			//$draft_mode = $utr_no;
			
		} */
		

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


		if( $st_flag== 'S' || $st_flag == 'D' || $st_flag == 'C' || $st_flag == 'R' ){
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
//echo $sql; exit();		
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$account_name  	 = $s1['account_name'];
		$account_type  	 = $s1['account_type'];
		$branch		  	 = $s1['branch'];
		$address	  	 = $s1['address'];
		$account_number	 = $s1['account_number'];
		$isfc_code		 = $s1['isfc_code'];

	
	$message .=  "<br>";
	$message .=  "<br>";
	$message .=  "<br>";
	$message .=  "<br>";
	$message .=  "<br>";
	$message .=  "<br>";

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


//	$message .= "<table><td> <img src='img/".$img_flname_hdr."' width='100%' height='50%' ></td> </table>";

//echo $message; exit();
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> </td></tr></table>";
	
	//if(!empty($draft_mode)){	
	//	$message .= "<p style='width: 95%;text-align: Center;color:#c9d6d6;font-size:72;position: absolute;	left: 0px;	top:0px;	z-index: -20;'>$draft_mode </p>";
	//}
	
//echo $draft_mode. ' '.$draft_mode_I. ' ' . $utr_no. "<<>>";

	if( !empty($draft_mode) || !empty($draft_mode_I) ){
		if(!empty($utr_no)){
			$zindex = '0';
			$message .= "<p style='text-align: Center;color:#FF3383;font-size:30;'>$draft_mode </p>";
			
		}
		else {$zindex = '-50';
	
			$message .= "<p style='width: 95%;text-align: Center;color:#c9d6d6;font-size:".$font_size.";position: absolute;	left: 0px;top:0px;z-index1:".$zindex.";'>$draft_mode </p>";
			
		}
	}

//exit();
	
	$y4 = date("Y");
	$y2 = date("y")+1;
	
	$mthchk = date("m");
	if($mthchk <= 3 ){
		$y4 = $y4 - 1; 
		$y2 = date("y");
	}


//echo $message; exit();
	$message .=  "<br>";
//	$message .=  "<br>";
//	$message .=  "<br>";
//	$message .=  "<br>";
//	$message .=  "<br>";
//	$message .=  "<br>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> Date: $paid_date</td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:40px;'><tr><td style='width: 95%;'> Ref :  DBCPL/P2P/".$y4.'-'.$y2."/0". $pur_req_no. " </td></tr></table>";
	
	$message .=  "<br>";

	$message .=  "<span  style='margin-left:40px;'>To,</span><br>";
	$list = explode(PHP_EOL, $address);
//	print_r($list);
	$message .=  "<span style='margin-left:40px;'>".$list[0].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[1].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[2].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[3].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[4].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[5]. "</span><br>";
//	$message .=  "<span style='margin-left:40px;'>Branch : $branch</span>";
	$message .=  "<span style='margin-left:40px;'> $email  $mobile </span>";
	
	

	$amt_word=numbertoword($total_amount_paid);
		
	//$message .=  "<p style='margin-left:40px;'> Ref.:Account Number: $comp_name / $account_number / ISFC - $isfc_code </p>";
	
	$message .=  "<p style='margin-left:40px;'> Transfer of funds under budget head  ". $bugdet_name." </p>";
	
	$message .=  "<p style='margin-left:40px;'> Subject : Request to Process RTGS/NEFT of Rs.".moneyFormatIndia($total_amount_paid)."/-</p>";
	
	$message .=  "<p style='margin-left:40px;'> Dear Sir, </p>";
	
	$message .=  "<p style='margin-left:40px;'> We request you to disburse Rs.".moneyFormatIndia($total_amount_paid)."/- (Rupees ". $amt_word." Only) towards the following stated details.  </p>";
	
	$message .=  "<p style='margin-left:40px;'> You may authorize to debit our account no ". $account_number ." for said purpose.  </p>";
	
	
	//$message .=  "<br>";
	
	$message .= "<table border='1' cellspacing='-1' style='width: 95%; text-align: center; margin-left:40px;font-size: 14px;' >
			<tr>
				<td style='width: 15%;text-align: left;'> Beneficiary Name </td>
				<td style='width: 20%;text-align: left;'> Beneficiary A/c No. </td>
				<td style='width: 20%;text-align: left;'> Beneficiary Bank Name& Address : </td>
				<td style='width: 15%;text-align: left;'> IFSC Code </td>
				<td style='width: 15%;text-align: left;'> Amount </td>
				<td style='width: 15%;text-align: left;'> Purpose </td>
				
			</tr>
			<tr>
				<td style='width: 15%;text-align: left;font-size: 14px;'> " . $party_name . " </td>
				<td style='width: 20%;text-align: left;font-size: 14px;'> " . $party_bank_account_no . " </td>
				<td style='width: 20%;text-align: left;font-size: 14px;'> " .$party_bank_name. ' ' . $party_bank_address . " </td>
				<td style='width: 15%;text-align: left;font-size: 14px;'> " . $party_bank_ifsc_code . " </td>
				<td style='width: 15%;text-align: left;font-size: 14px;'> Rs." . moneyFormatIndia($total_amount_paid)."/-</td>
				<td style='width: 15%;text-align: left;font-size: 10px;'> " . $rtgs_narration . " </td>
			</tr>
			</table>";
	
	$message .=  "<p style='margin-left:40px;'> We confirm that the instructions issued are in line with the escrow agreement.</p>";
	
	$message .=  "<p style='margin-left:40px;'> Thanking you</p>";
	$message .=  "<p style='margin-left:40px;'> For $comp_name,</p>";
	$message .=  "<p>&nbsp;</p>";
	$message .=  "<p style='margin-left:40px;'> Authorised Signatories.</p>";
	
	$message .= "<table border='0' cellspacing='10'>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		</table>";
	
		$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";			

	}
		
//	$message .= "<table><td style='text-align:center;' > <img src='img/".$img_flname_ftr."' width='90%' height='90%' ></td> </table>";

//print $message; //DBCPL Letter Head_Bottom
//exit();
	
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
