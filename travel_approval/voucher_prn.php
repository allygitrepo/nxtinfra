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
	$vw			= $_GET['vw'];
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
		$comp_email			    = $row['comp_email'];
	}

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 24px;margin-left:1px;'><tr><td style='width: 95%;'> $comp_name </td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 14px;margin-left:1px;'><tr><td style='width: 95%;'> $comp_addr1 $comp_addr2 $comp_addr2 $comp_city $comp_pincode </td></tr></table>";
	
	if(!empty($comp_email)){
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 14px;margin-left:1px;'><tr><td style='width: 95%;'>E-Mail : $comp_email </td></tr></table>";
	}
	
	$message .= "<br><br>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 95%;'> Payment Memo </td></tr></table>";

	$message .= "<br><br>";
	
	
	//$sql 	= "SELECT * from purchase_order where id = '$id' ";
	$sql    = "SELECT * from sma_travel_expenses where id = '$id' ";
	
//echo $sql;
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	$row    = mysqli_fetch_array($reshdr);
		$tr_id					= $row['id'];
		$dated					= date('d-m-Y', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$emp_id				= $row['emp_id'];
		//$emp_id					= $row['onbehalf_emp_id'];
		$tally_narration		= $row['tally_narration'];
		$exp_type				= $row['exp_type'];
		$approver_1				= $row['approver_1'];
		
		$maker					= $row['draft_by'];
		$status					= $row['status'];
		$maker_date				= date('d-m-Y h:i:sa', strtotime($row['draft_dated']));
		
		$supplier_invoice_no	='';
		$sql 	= " SELECT * FROM `sma_expenses` where approval_ref_no = '$tr_id' ";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit(" sma_expenses ");}
		while($rs 	= mysqli_fetch_array($res)){
			
			$supplier_invoice_no		.= $rs['invoice_no'];
			$account_id					= $rs['reference'];
			
		}	
		
		if($exp_type =='T' || $exp_type =='R' ){
		
			$sql 	= " SELECT * FROM sma_user where id = '$emp_id' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			
			$party_name		= $rs['username'];
			
		}	
		
		if($exp_type =='C' || $exp_type =='D'){
		
			$sql 	= " SELECT party_name, party_address_1, party_address_2, party_address_3, party_pincode FROM sma_party_mst where id = '$emp_id' ";
//	echo $sql; //a, `cities` b, states c  //and a.party_city = b.id and a.party_state = c.id 

			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			
			
			$party_name		= $rs['party_name'];
			$party_address	= $rs['party_address_1'].','.$rs['party_address_2'];
			$party_address1	= $rs['party_address_3'];
			
			$city_name 		= $rs['city_name'];
			
			if(!empty($city_name)){
				$party_address1	.= ', '.$rs['city_name'];
			}
			if(!empty($state_name)){
				$party_address1	.= ','.$rs['state_name'];
			}
			if(!empty($party_pincode)){
				$party_address1	.= ','.$rs['party_pincode'];
			}
			
		}
		
		$message .= "<table cellspacing='0' border='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr><td style='width: 15%;'>No.</td>
			<td style='width: 15%;'>$id</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'>Date : </td>
			<td style='width: 20%;'>".$dated."</td>
		</tr></table>";

		$message .= "<table cellspacing='0' border='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr><td style='width: 15%;'>Ref.</td>
			<td style='width: 15%;'>$supplier_invoice_no</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr></table>";

		$message .= "<table cellspacing='0' border='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr><td style='width: 15%;'>Party's Name</td>
			<td style='width: 15%;'>$party_name</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr>	
		<tr><td style='width: 15%;'>&nbsp;</td>
			<td style='width: 15%;'>$party_address</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr>
		<tr><td style='width: 15%;'>&nbsp;</td>
			<td style='width: 15%;'>$party_address1</td>
			<td style='width: 20%;'></td>
			<td style='width: 30%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr>
		</table>";
		
		
		$message .= "<br>";
		
		
		$message .= "<table cellspacing='0' border='.5' style='width: 95%; border: solid 0px black; text-align: left; font-size: 18px;margin-left:1px;'>
		<tr><td style='width: 75%;'><b>Particulars</b></td>
			<td style='width: 12%;text-align:center'><b>Debit</b></td>
			<td style='width: 13%;text-align:center'><b>Credit</b></td>
		</tr>";
		
		if($exp_type =='T'){
			
			$doc_type = 'TE';
		}	
		else if($exp_type =='C'){
			
			$doc_type = 'CE';
		}
		else if($exp_type =='R'){
			
			$doc_type = 'RE';
		}
		else if($exp_type =='D'){
			
			$doc_type = 'DE';
		}
		
		$sql    = "SELECT doc_date, account_type, account_id, account_name, budget_head, amount, effect, narration FROM `tally_journal_entry` where doc_type = '$doc_type' and doc_no = '$id' and account_type in ( 'U', 'V' )";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$rw    = mysqli_fetch_array($res);
		$doc_date			= $rw['doc_date'];
			$account_type		= $rw['account_type'];
			$account_id			= $rw['account_id'];
			$account_name		= $rw['account_name'];
			$budget_head		= $rw['budget_head'];
			$bank_amount		= $rw['amount'];
			$effect				= $rw['effect'];
			$narration			= $rw['narration'];
			
		$sql    = "SELECT doc_date, account_type, account_id, account_name, budget_head, amount, effect, narration FROM `tally_journal_entry` where doc_type = '$doc_type' and doc_no = '$id' order by effect desc , record_id ";
//echo $sql;		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		while($rw    = mysqli_fetch_array($res)){
			
			$doc_date			= $rw['doc_date'];
			$account_type		= $rw['account_type'];
			$account_id			= $rw['account_id'];
			$account_name		= $rw['account_name'];
			$budget_head		= $rw['budget_head'];
			$amount				= $rw['amount'];
			$effect				= $rw['effect'];
			$narration			= $rw['narration'];
			
			$sql = "select * from sma_budget_subgroup where id = '$budget_head' ";
			$qr2 =	mysqli_query($con, $sql);
			$rowaffected =	mysqli_affected_rows($con);
			$r2  = mysqli_fetch_array($qr2);
			if($rowaffected>0){
				$budget_head 		= $r2['budget_head'];
			}
			if($effect=='Dr'){
				$message .= "<tr>
				<td style='width: 75%;'>$account_name ($budget_head)</td>
				<td style='width: 12%;text-align:right;'>$amount</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				
				</tr>";
			}	
			else { 
				$message .= "<tr>
				<td style='width: 75%;'>$account_name</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>$amount</td>
				</tr>";
			}

		}
		
		$message .= "<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>
				<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>
				<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>";
				
		$message .= "</table>";

		
		
		$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
		<tr>
			<td style='width: 75%;'  border='.5'><b>On Account of </b>: $narration Only</td>
			<td style='width: 12%;'  border='.5'></td>
			<td style='width: 13%;text-align:right;'>&nbsp;</td>
		</tr>
		<tr>
			<td style='width: 75%;'><b>Amount (in words): </b></td>
			<td style='width: 12%;'  border='.5'></td>
			<td style='width: 13%;text-align:right;'>&nbsp;</td>
		</tr>
		<tr>
			<td style='width: 75%;'  border='.5'>Rs. ". numbertoword($bank_amount) ." Only</td>
			<td style='width: 12%;'  border='.5'></td>
			<td style='width: 13%;text-align:right;'>&nbsp;</td>
		</tr>
		

	</table>";
		
	$message .= "<br>";
	
	$message .=  "<h4> Approval Process</h4>"; 	
	$id		= $_GET['id'];
$message .= "<table cellspacing='-1' style='width: 95%; border: solid 0px black; text-align: left; background: #E7E7E7; font-size: 13px;' >";		
$message .= "<tr>
					<th style='width: 20%;text-align: left;'>Decision by </th>
					<th style='width: 20%;text-align: left;'>Status </th>
					<th style='width: 40%;text-align: center;'> Date Time </th>				
					</tr></table>";			

$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black;  font-size: 10pt;' > ";				
//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username 
					FROM `workflow_history` a, `sma_user` b where a.doc_type = '$doc_type' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$create_by 		= $bs1['create_by'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			if($approver_1 == $create_by ){
				$status				= 'Verified';
			}
												
			$message .= "<tr>
					<td style='width: 20%;text-align: left;'>". $approval . " </td>
					<td style='width: 20%;text-align: left;'>". $status . " </td>
					<td style='width: 40%;text-align: center;'>" . $approval_date . " </td>				
					</tr> ";			
			
		}
			
		$message .= "</table>";
//	}	
	
if(empty($vw)){	
	echo $message;
	exit();
}
	
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
	if($prn=='Pdf' || $vw=='Y'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'vendor_voucher_'.$id. '.pdf';
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