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
	$bank_id	= $_GET['bank_id'];
	

	$message ='';

	$message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:40px;'><tr><td style='width: 100%;'> APPLICATION FORM FOR RTGS / NEFT PAYMENT</td></tr></table>";
				
	$y4 = date("Y");
	$y2 = date("y")+1;
	
	$mthchk = date("m");
	if($mthchk <= 3 ){
		$y4 = $y4 - 1; 
		$y2 = date("y");
	}

	$message .='<p>&nbsp;</p>';
	
	$id				= $_GET['id'];
	$tableName		= "payment_header";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$row = mysqli_fetch_array($result);
		
		$paid_date  			= date('d-m-Y', strtotime($row['paid_date']));
		
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'><tr><td style='width: 50%;'> Ref No: $comp_code/P2P/TRA Request - ". date('M', strtotime($paid_date)).'/'.$y4.'-'.$y2."/0". $id. "</td><td style='text-align: right;width: 45%;'> Date: $paid_date</td></tr></table>";
	
		//$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'><tr><td style='width: 95%;'> Date: $paid_date</td></tr></table>";
		
		
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
		$rtgs_narration			= $ros['rtgs_narration'];
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
		
		
	//$message .=  "<br><br><br>";
	//$message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';			
//$head = $message;
	$sql 	= "SELECT * FROM account_mst where id = '$cash_bank_name'";
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
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'>";
	$message .= "<tr><td style='width: 95%;'> $account_name</td></tr>";
	$message .= "<tr><td style='width: 95%;'> $address</td></tr>";
	$message .= "<tr><td style='width: 95%;'> $account_number</td></tr>";
	$message .= "<tr><td style='width: 95%;'> $isfc_code</td></tr>";
	$message .= "</table>";
	
	$amt_word=numbertoword($total_amount_paid);
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;' ><tr><td style='width: 95%;'>Dear Sir, </td></tr></table>";

	$message .= " <br>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 11pt;margin-left:40px;' ><tr><td style='width: 95%;'>We request you to kindly transfer an amount of  INR ". moneyFormatIndia($total_amount_paid) . "/- (Rupees ".$amt_word. " Only)  from our Escrow Account No. ". $account_number. " to Toll Expenditure Account No. 921020050855544 . and further pay O&M payments as per below details. </td></tr></table>";


	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 11pt;margin-left:40px;' ><tr><td style='width: 95%;'>Axis to Other Bank Accounts </td></tr></table>";
	
	$message .= " <br>";
	/* $message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; text-align: left; font-size: 12px;margin-left:40px;' ><tr>
		<td>Name of Account</td>
		<td>Amount</td>
		<td>Account No.</td>
		<td>IFSC Code</td>
		<td>Purpose</td>
		</tr></table>"; */

//echo $draft_mode;
//exit();		
		
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


		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C' ){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			$party_name  	 		 = $s1['party_name'];
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
		
		
		

//	}
	
	
	
	//$message .='<p>&nbsp;</p>';
	//$message .='<p>&nbsp;</p>';
	
	$message .=  "<span  style='margin-left:40px;'>To,</span><br>";
	//$message .=  "<span style='margin-left:40px;'>$account_name,</span><br>"; PHP_EOL
	
	$list = explode(PHP_EOL, $address);
//	print_r($list);
	$message .=  "<span style='margin-left:40px;'>".$list[0]. "</span><br>";
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
//	$message .=  "<span style='margin-left:40px;'>Branch : $branch</span>";
//	$message .=  "<span style='margin-left:40px;'> $email  $mobile </span>";
		
	$message .=  "<p style='margin-left:40px;'> Ref.: O & M Account No. $account_number </p>";
	
	$message .=  "<p style='margin-left:40px;'> Subject : Request to process  RTGS/NEFT of Rs.".moneyFormatIndia($total_amount_paid)."/-</p>";
	
	$message .=  "<p style='margin-left:40px;'> Dear Sir, </p>";
	
	$message .=  "<p style='margin-left:40px;'> With reference to the above, we request you to process RTGS/NEFT as per following details as under:- </p>";
	
	$message .=  "<br>";
	
	$amt_word=numbertoword($total_amount_paid);
	
	if(!empty($cheque_no)){
		
		$cheque_no = $cheque_no. ' / ' . $paid_date;
	}
	
	$message .= "<table border='1' cellspacing='-1' style='width: 95%; text-align: center; margin-left:40px;font-size: 10pt;' >
			<tr>
				<td style='width: 40%;text-align: left;'> Amount </td>
				<td style='width: 40%;text-align: left;'> Rs." . moneyFormatIndia($total_amount_paid)."/-</td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Amount in words:- </td>
				<td style='width: 40%;text-align: left;'> Rupees " . $amt_word . " Only </td>
			</tr>
			
			<tr>
				<td style='width: 40%;text-align: left;'> Beneficiary Bank Name & Address : </td>
				<td style='width: 40%;text-align: left;'> " .$party_bank_name. ' ' . $party_bank_address . " </td>
			</tr>
			<tr>
				<td style='width: 25%;text-align: left;'> IFSC Code </td>
				<td style='width: 40%;text-align: left;'> " . $party_bank_ifsc_code . " </td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Beneficiary A/c No. </td>
				<td style='width: 40%;text-align: left;'> " . $party_bank_account_no . " </td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Beneficiary A/c Name </td>
				<td style='width: 40%;text-align: left;'> " . $party_name . " </td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Contact Details </td>
				<td style='width: 40%;text-align: left;'> " . $email. ' , '. $mobile . " </td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Narration </td>
				<td style='width: 40%;text-align: left;'> " . $rtgs_narration . " </td>
			</tr>
			
			</table>";
	
	$message .=  "<p style='margin-left:40px;'> Kindly acknowledgement the receipt of this letter and do the needful.</p>";
	
	$message .=  "<p style='margin-left:40px;'> Thanking you</p>";
	$message .=  "<p style='margin-left:40px;'> For $comp_name,</p>";
	$message .=  "<p>&nbsp;</p>";
	$message .=  "<p style='margin-left:40px;'> Authorised Signatories.</p>";
		
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:40px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";			
	
//print $message;
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
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
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



function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			//$thecash = $thecash.".".$nums[1];
			$thecash = $thecash;
		}
        
		return $thecash;
    }
}	
 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertoword($num){
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
