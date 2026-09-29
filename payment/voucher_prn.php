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

	//$prn		= $_POST['prn'];
	$id			= $_GET['id'];
	$vendor_id	= $_GET['vendor_id'];
	$company_id	= $_GET['company_id'];
	
	$message  = '';
	
	$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
		$comp_name				= $row['comp_name'];
		$comp_addr1				= $row['comp_addr1'];
		$comp_addr2				= $row['comp_addr2'];
		$comp_addr3				= $row['comp_addr3'];
		$comp_city				= $row['comp_city'];
		$comp_pincode			= $row['comp_pincode'];
	}

	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 24px;margin-left:1px;'><tr><td style='width: 100%;'> $comp_name </td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;'> $comp_addr1 $comp_addr2 $comp_addr2 $comp_city $comp_pincode </td></tr></table>";
	
	$message .= "<br><br>";
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;'> PAYMENT VOUCHER </td></tr></table>";

	$message .= "<br><br>";
	
	
	//$sql 	= "SELECT * from payment_header where id = '$id' ";
	$sql    = "SELECT a.*, b.supplier_invoice_no FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id and a.id = '$id' ";
	//echo $sql;
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	$row    = mysqli_fetch_array($reshdr);
			
			$doc_no					= $row['id'];
			$paid_date 				= date('d-m-Y', strtotime($row['paid_date']));
			$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
			$company_id				= $row['company_id'];
			$paid_to				= $row['paid_to'];
			$cheque_no				= $row['cheque_no'];
			$utr_no					= $row['utr_no'];
			$bank_name				= $row['cash_bank_name'];
			$st_flag				= $row['st_flag'];
			$amount					= round($row['total_amount_paid'],0);
			$narration				= $row['remarks'];
			$invoice_no				= $row['supplier_invoice_no'];
	
		$maker					= $row['draft_by'];
		$status					= $row['status'];
		$maker_date				= date('d-m-Y h:i:sa', strtotime($row['draft_dated']));

		$sql 	= " SELECT *  FROM account_mst where id = '$bank_name' ";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$rs 	= mysqli_fetch_array($res);
		$bank_name	= $rs['account_name'];
		
		if($st_flag=='T'){
			$sql 	= " SELECT *  FROM sma_user where id = '$paid_to' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$party_name	= $rs['username'];
		}
		else {
			$sql 	= " SELECT *  FROM sma_party_mst where id = '$paid_to' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$party_name			= $rs['party_name'];
			$party_gst_number	= $rs['party_gst_number'];
		}
		
		
		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr><td style='width: 15%;'>Payment Sr.No.</td>
			<td style='width: 15%;'>$id</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'>Date : </td>
			<td style='width: 20%;'>".$paid_date."</td>
		</tr></table>";

		$message .= "<br><br>";
		
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 18px;margin-left:1px;'>
		<tr><td style='width: 75%;'><b>Particulars</b></td>
			<td style='width: 25%;text-align:center'><b>Amount in Rs.</b></td>
		</tr></table>";

		
		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr>
			<td style='width: 75%;'>&nbsp;</td>
			<td style='width: 25%;'>&nbsp;</td>
		</tr>
		
		<tr>
			<td style='width: 75%;'><b>Account </b></td>
			<td style='width: 25%;'></td>
		</tr>
		<tr>
			<td style='width: 75%;'>$party_name $party_gst_number</td>
			<td style='width: 25%;text-align:right'>$amount</td>
		</tr>";
		
		$message .= "<tr>
			<td style='width: 75%;'><b>Invoice No. $invoice_no</b></td>
			<td style='width: 25%;'></td>
		</tr>";
		
		$message .= "<tr>
			<td style='width: 75%;'>&nbsp;</td>
			<td style='width: 25%;'>&nbsp;</td>
		</tr>
		
		<tr>
			<td style='width: 75%;'><b>Thorugh </b></td>
			<td style='width: 25%;'></td>
		</tr>
		<tr>
			<td style='width: 75%;'>$bank_name </td>
			<td style='width: 25%;'></td>
		</tr> ";
		
		
		if(!empty($cheque_no)){
			$message .= "<tr>
				<td style='width: 75%;'>Cheque No. $cheque_no </td>
				<td style='width: 25%;'></td>
			</tr>";
		}
		
		$message .= "<tr>
			<td style='width: 75%;'>&nbsp;</td>
			<td style='width: 25%;'>&nbsp;</td>
		</tr>	
		
		<tr>
			<td style='width: 75%;'><b>On Account of: </b></td>
			<td style='width: 25%;'></td>
		</tr>
		<tr>
			<td style='width: 75%;'>$narration &nbsp;</td>
			<td style='width: 25%;'></td>
		</tr>
		</table>
		
		<tr>
			<td style='width: 75%;'>&nbsp;</td>
			<td style='width: 25%;'>&nbsp;</td>
		</tr>
		
		<table cellspacing='0' border='.3' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr>
			<td style='width: 75%;'><b>Amount (in words): </b></td>
			<td style='width: 25%;'></td>
		</tr>
		<tr>
			<td style='width: 75%;'  border='.5'>Rs. ". numbertoword($amount) ." Only</td>
			<td style='width: 25%;'  border='.5'></td>
		</tr>
		<tr>
			<td style='width: 75%;' ></td>
			<td style='width: 25%;text-align:right' >$amount</td>
		</tr>	

	</table>";
	
	$message .= "<br><br><br>";
	
	$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr>
			<td style='width: 50%;text-align:center'>Reciever's Signature</td>
			<td style='width: 50%;text-align:center'>Authorised Signatory</td>
		</tr>
		</table>";
		
	$message .= "<br><br><br>";
	 
	$approval 		='';
	$id		= $_GET['id'];
	
	$message .= "<table cellspacing='0' style='width: 70%; border: solid 0px black; text-align: center; font-size: 10pt;' border='1' > ";
	$message .= "<tr>
					<th style='width: 20%;'>Decision by </th>
					<th style='width: 20%;'>Status </th>
					<th style='width: 30%;'> Date Time </th>				
					</tr> ";			

//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'PY' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			$message .= "<tr>
					<td style='width: 20%;text-align: left;'>". $approval . " </td>
					<td style='width: 20%;text-align: left;'>". $status . " </td>
					<td style='width: 30%;text-align: right;'> " . $approval_date . " </td>				
					</tr> ";
		}
		$message .= "</table>";
//	}
		
	
echo $message;
exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='Excel'){
		$fl_name = 'vendor_ledger_'.$id. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}
	
    // convert to PDF
//	if($prn=='Pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'vendor_ledger_'.$id. '.pdf';
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
	//}
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