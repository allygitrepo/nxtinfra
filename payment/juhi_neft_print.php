<?php
session_start();
if($_GET['sub'] == 'pdf' || $_GET['sub'] == 'list' || $_POST['company_id'] || !empty($company_id)){
	
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();
//	$prn		= $_GET['sub'];
$output='';
$sqlv ='';
		$rtgs_text 			= $_SESSION['rtgs_text'];
		$bank_account_text  = $_SESSION['bank_account_text'];

	if( $_GET['company_id']){
		$message ='';

		$bank_id		= $_GET['bank_id'];
		$company_id		= $_GET['company_id'];
		$instrument_no 	= $_GET['instrument_no'];
	}
	else if( $_POST['company_id']){
		$message ='';

		$bank_id		= $_POST['bank_id'];
		$company_id		= $_POST['company_id'];
		$instrument_no 	= $_POST['instrument_no'];
	}
	else {
		$output='File';
		$message ='';
		$sqlv = " AND company_id = '$company_id' and cash_bank_name_id = '$bank_id'  ";	
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
	
	//$message ='';

	/* $message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';
	$message .='<p>&nbsp;</p>';
 */
 
	$footer  = "";
	$head    = "";
	//$message .='<BR>';
	//$message .='<p>&nbsp;</p>';
	$header_img = 'img/AJUHL_header_img.jpg';
	//$head    = "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-right:20px;'><tr><td width='90%' rowspan='4' style='text-align: center;'><img src='".$header_img."' width='80%' ></td><td width='10%'>&nbsp;</td></tr></table>";
	//$head = "<img src='".$header_img."' width='80%' >";
	
	$footer_img = 'img/AJUHL_footer_img.jpg';
	//$footer  = "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-right:20px;'><tr><td width='90%' rowspan='4' style='text-align: center;'><img src='".$footer_img."' width='80%' ></td><td width='10%'>&nbsp;</td></tr></table>";
	//$footer  = "<img src='".$footer_img."' width='80%' >";
	$footer  = "";
	$head    = "";
	$message .= '<page backtop="25mm" backbottom="20mm" backleft="10mm" backright="2mm" pagegroup="new123">
    <page_header>
        <table class="page_header" style="width: 90%; text-align: center;font-size: 18pt;margin-left:50px;">
            <tr>
                <td style="width: 90%; text-align: center123;text-align: center;margin-left:50px;">
                    '. $head .'
                </td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 12pt;text-align: left;margin-left:50px;margin-top:5px; padding-left: 48px;"><p>&nbsp;</p>
					</td>
            </tr>
			<br><br>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 90%; text-align: right;margin-left:60px;" >
            
			 <tr>
                <td style="width: 90%; text-align: center123;text-align: center;margin-left:60px;">
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
	
	//$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:50px;'><tr><td style='width: 100%;'> SP JAMMU UDHAMPUR HIGHWAY LIMITED</td></tr></table><br><br>";
	
	$party_bank_seq = 0;
	$counter = 0;
	$party_bank_seq_prev ='';
	
	$instrument_v = "Ref No.: P2P-". $instrument_no ;	
	
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; text-align: left; font-family: Arial, Helvetica, sans-serif;font-size: 14px;margin-left:50px;' ><tr><td style='width: 80%;'><b>$instrument_v </b></td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; text-align: left; font-family: Arial, Helvetica, sans-serif;font-size: 14px;margin-left:50px;' ><tr><td style='width: 80%;'><b>Date : ". date('d-m-Y')." </b></td></tr></table><br>";
	
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; text-align: left; font-family: Arial, Helvetica, sans-serif;font-size: 14px;margin-left:50px;'>";
	$message .='<tr><td> To,</td></tr>';
	$message .='<tr><td> Kotak Mahindra Bank Limited ,</td></tr>';
	$message .='<tr><td> 1st Floor - Vinay Bhavya Complex, </td></tr>';
	$message .='<tr><td> 159-A, C.S.T. Road, Kalina, Santacruz (E), </td></tr>';
	$message .='<tr><td> Mumbai – 400098</td></tr>';
	
	$message .= "</table><br>";
	
	//$message .='<p>&nbsp;</p>';
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; text-align: left; font-family: Arial, Helvetica, sans-serif;font-size: 14px;margin-left:50px;' ><tr><td style='width: 95%;'>Dear Sir, </td></tr></table>";
	
	$message .= " <br>";
	
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:50px;' ><tr><td style='width: 95%;'>Kind Attention: Ms Sulochana Nalawade– Rcad (Escrow operations) team </td></tr></table><BR>";
	
	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:50px;' ><tr><th style='width: 95%;'>Subject: 	Payments from O & M Expenses Account No. 6411488413 </th></tr></table><BR>";

	$sql 	= " SELECT sum(total_amount_paid) as total_amount_paid FROM rtgs_temp where 1 and selected = 'Y'  $sqlv ";
	$resw2 = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rw2 	= mysqli_fetch_array($resw2);	
	$total_amount_paid = $rw2['total_amount_paid'];

	 $message .= "<table cellspacing='0' style='width: 95%; line-height: 1.4;border: solid 0px black; text-align: left; font-size: 14px;margin-left:50px;' ><tr><td style='width: 95%;'>In relation to the above-mentioned subject, please transfer funds of Rs," . moneyFormatIndia($total_amount_paid). " (Rupees " . numbertoword($total_amount_paid). " Only) from O & M Expenses Account No. 6411488413 to the below-mentioned Account. The same is in accordance with clause no. 6.2 (c) of the Supplementary Escrow Agreement executed on 1st October 2015</td></tr></table><BR>";
 
	
	
	$message .= "<table cellspacing='0' border='.5' style='width: 85%; border: solid 0px black; text-align: center; font-family: Arial, Helvetica, sans-serif;font-size: 14px;margin-left:50px;vertical-align: top;'>";
		
	$message .= "<tr><th style='width: 5%;padding-top: 5px;padding-bottom: 5px;'> Sr. No.</th>";
	$message .= "<th style='width: 20%;padding-top: 5px;padding-bottom: 5px;'> Account Name</th>";	
	$message .= "<th style='width: 20%;text-align: center;padding-top: 5px;padding-bottom: 5px;'> Account No.</th>";
	$message .= "<th style='width: 20%;text-align: center;padding-top: 5px;padding-bottom: 5px;'> IFSC Code</th>";
	$message .= "<th style='width: 20%;padding-top: 5px;padding-bottom: 5px;'> Bank Name</th>";
	$message .= "<th style='width: 10%;text-align:right;padding-top: 5px;padding-bottom: 5px;'> Amount (in Rs.)&nbsp;</th> </tr>";
			
	$sql 	= " SELECT * FROM rtgs_temp where 1 and selected = 'Y'  $sqlv order by party_bank_seq, party_bank asc ";
	$resw2 = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($rw2 = mysqli_fetch_array($resw2)){
		
		$py_id  	= $rw2['py_id'];
		$total_amount_paid		= round($rw2['total_amount_paid'],0);
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
		//$total_amount_paid		= round($row['total_amount_paid'],0);
		$tds_amount				= $row['tds_amount'];
		$utr_no					= $row['utr_no'];
		
		$cheque_no				= $row['cheque_no'];
		$remarks				= $row['remarks'];
		$rtgs_narration			= $ros['rtgs_narration'];
		$maker_date				= date('d-m-Y h:m i', strtotime($row['draft_dated']));
		$st_flag				= $row['st_flag'];
		
		$approval_status		= $row['approval_status'];
		$status					= $row['status'];
		
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$py_id' ";		
//echo $sql;
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$actual_amount      = $res2['amount'];
			$account_name1 		= $res2['account_name'];
			//$total_amount_paid =  round($actual_amount,0);
		}
		
		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C' || $st_flag == 'R'){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
	
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			//$party_name  	 		 = ucwords(strtolower($s1['party_name']));
			$party_name				 = ucwords(strtolower($s1['party_beneficiary_name']));
			$party_bank_name 		 = ucwords(strtolower($s1['party_bank_name']));
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
			$party_name  	 		 = ucwords(strtolower($s1['username']));
			$party_bank_name 		 = $s1['bank_name'];
			$party_bank_account_type = $s1['bank_type'];
			$party_bank_address  	 = $s1['bank_branch'];
			$party_bank_account_no 	 = $s1['bank_ac_no'];
			$party_bank_ifsc_code  	 = $s1['bank_ifsc'];
		
		}
		
		$fntsiz = "";
		$fntsizz = "";
		if(strlen($party_bank_account_no)>=16){
			$fntsiz = " font-size:12px;";	
			$fntsizz = " font-size:12px;";	
		}
		if(strlen($party_bank_account_no)>=17){
			$fntsiz = " font-size:11px;";	
			$fntsizz = " font-size:11px;";
		}
		//
		$message .= "<tr>
				<td style='width: 5%;text-align: left;padding-top: 5px;padding-bottom: 5px;'> " . ++$ii."</td>
				<td style='width: 20%;text-align: center;padding-top: 5px;padding-bottom: 5px;padding:3px;'> " . $party_name."</td>
				<td style='width: 20%;text-align: center;padding-top: 5px;padding-bottom: 5px;padding:3px;$fntsiz'> " . $party_bank_account_no . " </td>
				<td style='width: 20%;text-align: center;padding-top: 5px;padding-bottom: 5px;padding:3px;$fntsizz'> " . $party_bank_ifsc_code . " </td>
				<td style='width: 20%;text-align: left;padding-top: 5px;padding-bottom: 5px;padding:3px;'> " . $party_bank_name . " </td>
				<td style='width: 10%;text-align: right;padding-top: 5px;padding-bottom: 5px;padding:3px;'> " . moneyFormatIndia($total_amount_paid) . " &nbsp;</td>
				
			</tr>";
		
		$total_amount_paid_grand = $total_amount_paid_grand + $total_amount_paid ;
		
	}
	
	$amt_word = numbertoword($total_amount_paid_grand);
	
	$message .=  "<tr><th></th>
				<th style='width: 20%;text-align: left;padding-top: 5px;padding-bottom: 5px;'> Total  </th>
				<th style='width: 40%;text-align: left;padding-top: 5px;padding-bottom: 5px;padding:3px;' colspan='3'> Rupees " . $amt_word . " Only </th>	
				<th style='width: 10%;text-align: right;padding-top: 5px;padding-bottom: 5px;padding:3px;' > " . moneyFormatIndia($total_amount_paid_grand) . " &nbsp;</th>
			</tr> ";
			
	$message .= "</table>";
	
	$message .= "<table border='0' cellspacing='10' style='width: 90%; text-align: left; font-family: Arial, Helvetica, sans-serif;margin-left:40px;font-size: 14px;'> ";
	$message .=  "<tr><td > Kindly do the needful and confirm once transferred.</td></tr>";
	$message .=  "<tr><td> Thanking you</td></tr>";
	
	$message .=  "<tr><th > For $comp_name,</th></tr>";
	$message .=  "<tr><th>&nbsp;</th></tr>";
	$message .=  "<tr><th>&nbsp;</th></tr>";
	$message .=  "<tr><th > Authorized Signatory</th></tr>";
	$message .=  "</table>";
		
	$message .= "<table border='0' cellspacing='10' style='width: 90%; text-align: center; margin-left:50px;font-family: Arial, Helvetica, sans-serif;font-size: 14px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";			

//	$message .='<BR>';
	//$message .='<p>&nbsp;</p>';
	
//echo $_POST['company_id'] ."<<>>";	
//print $message;
//exit();

	if( $output!='File' ){
		$prn='pdf';	
		
	}
	
    // get the HTML
    ob_start();
	
    // convert to PDF
    if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'juhi_neft_'.$bank_id. '.pdf';
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
	}
	
}



function moneyFormatIndiaA3($num){
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
   function numbertowordA3($num){
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
			//$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$plural = (($counter = count($str)) && $number > 9) ? '' : null;
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
