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
	
	/* $vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	$bank_id	= $_POST['cash_bank_name'];
	 */
	
	$message ='';
 
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
		
		$locdir 			= 'c:/xampp/htdocs/';
		$logo_dir_name		= $locdir.'setting/'.'upload/';
		$logo_fl			= $logo_dir_name . 'AthanglogoColor.JPG';
		
		$message .= "<table cellspacing='0' style='width: 93%; border: solid 0px black; margin-left:45px;'><tr><td style='width: 66%;font-size: 19px;text-align: left;'><b>" . $comp_name."</b></td><td rowspan='5' style='width: 30%;text-align: left;'><img src='".$logo_fl."'height='20%' width='20%'></td></tr>";
		
		$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>"."". $comp_addr1.' '.$comp_city.','.$comp_pincode.','.$comp_state."</td></tr>";
		$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>Phone: ".$comp_office.", E-mail : ".$comp_email."</td></tr>";
		$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>CIN No.: ".$comp_cin_no.", GST No.: ".$loc_gst_no."</td></tr>";
		$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>PAN No.: ".$loc_pan_no."</td></tr>";
		$message .= "</table>";
		
		$message .=  "<BR><BR><BR>";
		
		$sql 	= "SELECT * FROM account_mst where id = '$bank_id'";
//echo $sql."<BR>";		
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$account_name  	 = $s1['account_name'];
		$account_type  	 = $s1['account_type'];
		$branch		  	 = $s1['branch'];
		$address	  	 = $s1['address'];
		$email		  	 = $s1['email'];
		$mobile		  	 = $s1['mobile'];
		$account_number	 = $s1['account_number'];
		$isfc_code		 = $s1['isfc_code'];

		$list = explode(PHP_EOL, $address);
		
	$message .=  "<span style='margin-left:40px;'>To,</span><Br>";	
	$message .=  "<span style='margin-left:40px;'>$account_name</span>";	
//	print_r($list);
	$message .=  "<span style='margin-left:40px;'>".$list[0].','. "</span><br>";
	if(!empty($list[1])){
		$message .=  "<span style='margin-left:40px;'>".$list[1].','. "</span><br>";
	}
	if(!empty($list[2])){
		$message .=  "<span style='margin-left:40px;'>".$list[2].','. "</span><br>";
	}
	if(!empty($list[3])){
	$message .=  "<span style='margin-left:40px;'>".$list[3].','. "</span><br>";
	}
	if(!empty($list[4])){
	$message .=  "<span style='margin-left:40px;'>".$list[4].','. "</span><br>";
	}
	if(!empty($list[5])){
		$message .=  "<span style='margin-left:40px;'>".$list[5]. "</span><br>";
	}
	
	
	$message .=  "<span style='margin-left:40px;'>Branch : $branch</span>";
	$message .=  "<span style='margin-left:40px;'> $email  $mobile </span>";
//echo $message. "<BR>";
	
	$y4 = date("Y");
	$y2 = date("y")+1;
	
	$mthchk = date("m");
	if($mthchk <= 3 ){
		$y4 = $y4 - 1; 
		$y2 = date("y");
	}
		
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'><tr><td style='width: 95%;'> Instruction Letter No. : $comp_code/P2P/".$y4.'-'.$y2."/0". $id. "</td></tr></table>";
	
	$message .=  "<p style='margin-left:40px;'> Dear Sir, </p>";
	
	$tableName		= "payment_header";
	$sql 	= " SELECT * FROM $tableName where company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and cash_bank_name = '$bank_id' ";
	/* if(!empty($vendor_id)){
		$sql .= " and paid_to = '$vendor_id' ";
	} */
	
	$result = mysqli_query($con,$sql);
	while ($row 	= mysqli_fetch_array($result)){
		$py_id	= $row['id'];
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$py_id' ";		
	//	echo $sql;
		$qr2   = mysqli_query($con, $sql);
		$res2  = mysqli_fetch_array($qr2);
		$actual_amount      = $res2['amount'];
		$account_name1 		= $res2['account_name'];
		$net_total_amount_paid =  $net_total_amount_paid + $actual_amount;
	}
	
	
	$amt_word=numbertoword_rm(round($net_total_amount_paid,0));

	$message .=  "<p style='margin-left:40px;'> You are requested to kindly transfer an amount of Rs.". moneyFormatIndia($net_total_amount_paid)."/- (Rupees $amt_word Only) by debiting our   Account No. $account_number and credit our O &M Expenses Sub--Account No.31812896843 along with your bank charges. and pay   below payment.</p>";
	
	
	$message .=  "<p style='margin-left:40px;'> Beneficiary Details:- </p>";
	
	$message .= "<table cellspacing='0' style='margin-left:40px;width: 93%; border: solid 0px black; ' border='1' >
			<tr>
				<td>Vendor Name</td>
				<td>Account no</td>
				<td>IFSC Code</td>
				<td style='text-align:center;'>Amount</td>
				<td>Purpose</td>
			</tr>";//</table>
	
	$sql 	= " SELECT * FROM $tableName where company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and cash_bank_name = '$bank_id' ";
	
	if(!empty($vendor_id)){
			$sql .= " and paid_to = '$vendor_id' ";
	}

//echo $sql;
//	exit();
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$py_id					= $row['id'];
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
		$draft_mode = '';
		
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$py_id' ";		
		//echo $sql;
		$qr2   = mysqli_query($con, $sql);
		$res2  = mysqli_fetch_array($qr2);
		$actual_amount      = $res2['amount'];
		$account_name1 		= $res2['account_name'];
		$total_amount_paid =  round($actual_amount,0);
		

		if($total_amount_paid ==0){
			continue;
		}
		
		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C'){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			
			$party_name				 = $s1['party_beneficiary_name'];
			if(empty($party_name)){
				$party_name  	 		 = $s1['party_name'];
			}	
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
			
	//$amt_word=numbertoword($total_amount_paid);
	//$message .= "<table border='1' cellspacing='-1' style='width: 95%; text-align: center; margin-left:40px;font-size: 10pt;' >
	$message .= "<tr>
				<td style='width: 25%;text-align: left;'> " . $party_name . " </td>
				<td style='width: 19%;text-align: left;'> " . $party_bank_account_no . " </td>
				<td style='width: 15%;text-align: left;'> " . $party_bank_ifsc_code . " </td>
				<td style='width: 14%;text-align: right;'> " . moneyFormatIndia($total_amount_paid)."</td>
				<td style='width: 27%;text-align: left;'> " . $rtgs_narration . " </td>
			</tr>";
	
	}
	
	$message .= "<tr>
				<td style='width: 25%;text-align: left;'>  </td>
				<td style='width: 18%;text-align: left;'>  </td>
				<td style='width: 15%;text-align: left;'> Total</td>
				<td style='width: 14%;text-align: right;'> " . moneyFormatIndia($net_total_amount_paid)."</td>
				<td style='width: 27%;text-align: left;'> </td>
			</tr>";
			
	$message .= "</table>";
	$message .=  "<p style='margin-left:40px;'> Amount in Words.( $amt_word Only )</p>";

	$message .=  "<p style='margin-left:40px;'> Kindly do the needful at the earliest.</p>";
	
	$message .=  "<span style='margin-left:40px;'>Thanking you</span>";
	$message .=  "<p style='margin-left:40px;'> For $comp_name,</p>";
	$message .=  "<p>&nbsp;</p>";
	$message .=  "<p style='margin-left:40px;'> Authorised Signatory.</p>";
		
	/* $message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:40px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";			
	 */
	//Page Break	
	//	$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
	//Page Break
	
	
//print $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	$fl_name = 'neft_'.$comp_code.'_'.$bank_id.$id;
    if($prn=='excel'){
		$fl_name = $fl_name. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}	

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = $fl_name. '.pdf';
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
   function numbertoword_rm($num){
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
