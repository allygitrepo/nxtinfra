<?php

if($_GET['sub'] == 'pdf' || $_GET['sub'] == 'list' || $_POST['company_id'] ){
	
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();
//	$prn		= $_GET['sub'];

	if( $_GET['company_id']){
		$bank_id	= $_GET['bank_id'];
		$company_id	= $_GET['company_id'];
	}
	else if( $_POST['company_id']){
		$bank_id	= $_POST['bank_id'];
		$company_id	= $_POST['company_id'];
	}

	
	$sql 	= "select * from company where comp_id = '$company_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$comp_code = $r2['comp_code'];
	$comp_name = $r2['comp_name'];
	
	
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
	
	
		$sql 	= "SELECT * FROM account_mst where id = '$bank_id'";
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
	
	$message ='';

	/* $message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';
 */
	//$message .='<BR>';
	//$message .='<p>&nbsp;</p>';
	$header_img = 'img/ADHTPL_header_img.jpg';
	//$head    = "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-right:20px;'><tr><td width='90%' rowspan='4' style='text-align: center;'><img src='".$header_img."' width='80%' ></td><td width='10%'>&nbsp;</td></tr></table>";
	$head = "<img src='".$header_img."' width='80%' >";
	
	$footer_img = 'img/ADHTPL_footer_img.jpg';
	//$footer  = "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-right:20px;'><tr><td width='90%' rowspan='4' style='text-align: center;'><img src='".$footer_img."' width='80%' ></td><td width='10%'>&nbsp;</td></tr></table>";
	$footer  = "<img src='".$footer_img."' width='80%' >";
	
	$footer  ="";
	$head  ="";
	$message .= '<page backtop="30mm" backbottom="20mm" backleft="10mm" backright="2mm" pagegroup="new123">
    <page_header>
        <table class="page_header" style="width: 90%; text-align: center;font-size: 18pt;margin-left:25px;">
            <tr>
                <td style="width: 90%; text-align: center123;text-align: center;margin-left:40px;">
                    '. $head .'
                </td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 12pt;text-align: left;margin-left:40px;margin-top:5px; padding-left: 48px;"><p>&nbsp;</p>
					</td>
            </tr>
			<br><br>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 90%; text-align: right;margin-left:25px;" >
            
			 <tr>
                <td style="width: 90%; text-align: center123;text-align: center;margin-left:40px;">
                    '. $footer .'
                </td>
            </tr>
        </table>
    </page_footer>
</page>';
/* <tr>
                <td style="width: 100%; text-align: right">
                    Page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
			 */
	//$message .='<p>&nbsp;</p>';
	//$message .='<BR>';
	
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:25px;'><tr><td style='width: 100%;'> APPLICATION FORM FOR RTGS / NEFT PAYMENT</td></tr></table>";
				
	$y4 = date("Y");
	$y2 = date("y")+1;
	
	$mthchk = date("m");
	if($mthchk <= 3 ){
		$y4 = $y4 - 1; 
		$y2 = date("y");
	}

	$party_bank_seq = 0;
	$counter = 0;
	$party_bank_seq_prev ='';
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; font-family: Arial, Helvetica, sans-serif;text-align: left; font-size: 14px;margin-left:25px;'>";
	$message .='<tr><td> To,</td></tr>';
	$message .='<tr><td> &nbsp;</td></tr>';
	$message .='<tr><td> Axis Bank Limited </td></tr>';
	$message .='<tr><td> A/12, 1st Flr, A Wing 210,</td></tr>';
	$message .='<tr><td> Mittal Tower, Barrister Rajni Patel Marg, </td></tr>';
	$message .='<tr><td> Nariman Point, </td></tr>';
	$message .='<tr><td> Mumbai, Maharashtra 400021</td></tr>';
	
	$message .= "</table><br>";
	
	//$message .='<p>&nbsp;</p>';
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; font-family: Arial, Helvetica, sans-serif;text-align: left; font-size: 14px;margin-left:25px;' ><tr><td style='width: 95%;'>Dear Sir, </td></tr></table>";
	
	$message .= " <br>";
	
	$sql 	= " SELECT sum(total_amount_paid) as total_amount_paid FROM rtgs_temp where 1 and selected = 'Y' ";
	$resw2 = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rw2 	= mysqli_fetch_array($resw2);	
	$total_amount_paid = $rw2['total_amount_paid'];

	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; font-family: Arial, Helvetica, sans-serif;text-align: left; text-align:justify;font-size: 14px;margin-left:25px;' ><tr><td style='width: 95%;'>We request you to kindly transfer an amount of INR " . moneyFormatIndia($total_amount_paid). " (Rupees " . numbertoword($total_amount_paid). " Only from our ADHTPL-Escrow A/C No.922020047006145 to ADHTPL-Operating Fund Account No. 922020047006187 then.  </td></tr>
	<tr><td>&nbsp;</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; font-family: Arial, Helvetica, sans-serif;text-align: left; text-align:justify;font-size: 14px;margin-left:25px;' ><tr><td style='width: 95%;'>ADHTPL-Operating Fund Account No. 922020047006187 to O&M Expenses Sub Account No. 922020047006297 and further pay O&M payments as per below details.  </td></tr>
	<tr><td>&nbsp;</td></tr></table>";

	$message .= "<table cellspacing='0' border='.5' style='width: 90%; border: solid 0px black; font-family: Arial, Helvetica, sans-serif;text-align: left; font-size: 14px;margin-left:25px;'>";
		
			$message .= "<tr><td style='width: 40%;padding:3px;'> In Favour of</td>";
			$message .= "<td style='width: 10%;text-align:right;padding:3px;'> Amount (in Rs.)</td> ";
			$message .= "<td style='width: 40%;padding:3px;'> Payable At</td>
			</tr>";
			
	$sql 	= " SELECT * FROM rtgs_temp where 1 and selected = 'Y'  order by party_bank_seq, party_bank asc ";
	$resw2 = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($rw2 = mysqli_fetch_array($resw2)){
		
		$py_id  	= $rw2['py_id'];
		$party_bank = strtoupper(substr($rw2['party_bank'],0,4));
		$party_bank_seq	 = $rw2['party_bank_seq'];
	
		$sql    = " SELECT * from payment_header where 1 and id = '$py_id' ";
		$result = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$row = mysqli_fetch_array($result);
		
		$paid_date  			= date('d-m-Y', strtotime($row['paid_date']));
		
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
		
		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C' ){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
	
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			$party_name  	 		 = $s1['party_name'];
			$party_city  	 		 = $s1['party_city'];
			//$party_name				 = $s1['party_beneficiary_name'];
			$party_bank_name 		 = $s1['party_bank_name'];
			$party_bank_account_type = $s1['party_bank_account_type'];
			$party_bank_address  	 = $s1['party_bank_address'];
			$party_bank_account_no 	 = $s1['party_bank_account_no'];
			$party_bank_ifsc_code  	 = $s1['party_bank_ifsc_code'];
			
			$sql 	= "SELECT * FROM cities where id = '$party_city'";
			$res 	= mysqli_query($con,$sql);
			$s1 	= mysqli_fetch_array($res);
			$party_city  	 	 = $s1['city_name'];
			
			if(empty($party_city)){
				$party_city  	 = $party_bank_ifsc_code;	
			}	
		
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
		
		$message .= "<tr>
				<td style='width: 40%;text-align: left;padding:3px;'> " . $party_name."</td>
				<td style='width: 10%;text-align: right;padding:3px;'> " . moneyFormatIndia($total_amount_paid) . " </td>
				<td style='width: 40%;text-align: left;padding:3px;'> " . $party_city . " </td>
				
			</tr>";
		
		$total_amount_paid_grand = $total_amount_paid_grand + $total_amount_paid ;
		
	}
	
	$amt_word = numbertoword($total_amount_paid_grand);
	
	$message .=  "<tr>
				<td style='width: 40%;text-align: left;padding:3px;'> Total Amount in Rs. </td>
				<td style='width: 10%;text-align: right;padding:3px;' > " . moneyFormatIndia($total_amount_paid_grand) . " </td>
				<td style='width: 40%;text-align: left;padding:3px;' > " . $amt_word . " </td>	
			</tr> ";
			
	$message .= "</table>";
	
	//$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: left; margin-left:25px;font-size: 14px;'> ";
	//$message .= "<tr><td style='margin-left:25px;'>&nbsp;</td></tr></table>";
	
	$message .= "<table border='0' cellspacing='10' style='width: 90%; font-family: Arial, Helvetica, sans-serif;text-align: left; margin-left:25px;font-size: 14px;'> ";
	$message .= "<tr><td style='margin-left:25px;'>Note : Demand Draft will be collected from below Axis Bank Yelahanka Branch SOL 094</td></tr>";
	$message .= "<tr><td style='margin-left:25px;'>IFSC Code : UTIB0000094</td></tr>";
	$message .= "<tr><td style='margin-left:25px;'>688, 3rd A Cross Rd, opposite Seshadri Puram College, Yelahanka Satellite Town, Yelahanka New Town, </td></tr>";
	$message .= "<tr><td style='margin-left:25px;'>Bengaluru, Karnataka 560064</td></tr>";
	$message .= "<tr><td style='margin-left:25px;'>We Authorised Mr Anjaneya Reddy to collect original Demand Draft and his PAN No: AQQPR2421Q</td></tr>";
	$message .= "<tr><td style='margin-left:25px;'>Kindly do the needful at the earliest. </td></tr>";
	$message .= "</table>";


	$message .= "<table border='0' cellspacing='10' style='width: 95%;font-family: Arial, Helvetica, sans-serif; text-align: left; margin-left:25px;font-size: 14px;'> ";
	$message .= "<tr><td style='margin-left:25px;'>Thanking you</td></tr>";
	
	$message .= "<tr><td style='margin-left:25px;'><b>For $comp_name,</b></td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	$message .= "<tr><td style='margin-left:25px;'><b>Authorized Signatory</b></td></tr>";
	$message .= "</table>";
		
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:25px;font-size: 14px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";			

//	$message .='<BR>';
	//$message .='<p>&nbsp;</p>';
	
//echo $_POST['company_id'] ."<<>>";	
//print $message;
//exit();

	$prn='pdf';	
	
    // get the HTML
    ob_start();
	
    // convert to PDF
//	if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'adhtpl_dd_'.$bank_id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->setDefaultFont('Arial');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			if(isset($_POST['company_id'])){
				$pdf = $html2pdf->Output($fl_name, true);
			}
			else { 
				$html2pdf->Output($fl_name);
			}	
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
//	}
	
}



function moneyFormatIndiaA($num){
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
   function numbertowordA($num){
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
