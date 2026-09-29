<?php 
include("../dbcon.php");
include("../baseurl.php");

	$company_id	= $_GET['company_id'];
	$sql 	= "select * from company where comp_id = '$company_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$comp_code = $r2['comp_code'];
	$comp_name = $r2['comp_name'];

	
if($_GET['sub'] == 'list'){ 
	
	$modulePath = "payment/";
	$message = '';
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
	
		//Sr. No.	Account Name	Account No.	IFSC Code	Bank Name	 Amount (in Rs.) 	P2P No.

	$ii = 0;
	if( $comp_code=='QB' || $comp_code=='KTPL' || $comp_code=='AIPL' || $comp_code=='AIIMPL' || $comp_code=='JUHI' ){
		$message .= "<tr>
			<th>Sr. No.</th>
			<th>Account Name</th>
			<th>Account No.</th>
			<th>IFSC Code</th>
			<th>Bank Name</th>
			<th>Amount (in Rs.)</td>
			<th>P2P No.</td>
		</tr>";	
	}
	else if( $comp_code=='ADTPL' || $comp_code=='ADHTPL' ){
		
		$message .= "<tr>
			<th>Name of the Vendor</th>
			<th>Amount</th>
			<th>Account No.</th>
			<th>IFSC Code</th>
			<th>Description of the work</th>
			<th>Ref No.</td>
		</tr>";	
	}	
	
	$sql   = "SELECT * from rtgs_temp where 1 and selected = 'Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$py_id					= $row['py_id'];
		$paid_date 				= $row['paid_date'];
		$cash_bank_name 		= $row['cash_bank_name'];
		$party_name 			= $row['party_name'];
		$dated 					= $row['dated'];
		$paid_to				= $row['paid_to'];
		$supplier_invoice_no 	= $row['supplier_invoice_no'];
		$supp_id				= $row['supp_id'];
		$st_flag 				= $row['st_flag'];
		$total_amount_paid 		= $row['total_amount_paid'];
		$note 	='';
// echo $st_flag		. "<BR>";
		if( $st_flag== 'SI' || $st_flag == 'SA'  || $st_flag == 'OE' || $st_flag == 'R' ){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
	
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			//$party_name  	 		 = ucwords(strtolower($s1['party_name']));
			$party_name				 = ucwords(strtolower($s1['party_beneficiary_name']));
			$party_bank_name 		 = $s1['party_bank_name'];
			$party_bank_account_type = $s1['party_bank_account_type'];
			$party_bank_address  	 = $s1['party_bank_address'];
			$party_bank_account_no 	 = $s1['party_bank_account_no'];
			$party_bank_ifsc_code  	 = $s1['party_bank_ifsc_code'];
			if($st_flag == 'OE' ){
				$sql 	= "SELECT a.remarks, b.* FROM `sma_travel_expenses` a, sma_expenses b where a.id = b.approval_ref_no and a.id = '$supp_id'";
				$res 	= mysqli_query($con,$sql);
				$s1 	= mysqli_fetch_array($res);
				$note 	= trim($s1['note']);
				if(empty($note)){
					$note 	= $s1['remarks'];
				}
			}
			else if($st_flag == 'SI' ){
				$sql 	= "SELECT b.* FROM `sma_supplier_invoice` a, sma_supplier_invoice_details b where a.id = b.si_hdr_id and a.id = '$supp_id'";
				$res 	= mysqli_query($con,$sql);
				$s1 	= mysqli_fetch_array($res);
				$note 	= trim($s1['description']);
				if(empty($note)){
					$note 	= $s1['material_name'];
				}
			}
			else if($st_flag == 'SA' ){
				$sql 	= "SELECT b.* FROM `sma_purchase_order` a where a.id = '$supp_id'";
				$res 	= mysqli_query($con,$sql);
				$s1 	= mysqli_fetch_array($res);
				$note 	= $s1['subject'];
			}
			
		}
		else if( $st_flag== 'TA' || $st_flag == 'TE' ){
			$sql 	= "SELECT * FROM sma_user where id = '$paid_to'";
			$res 	= mysqli_query($con,$sql);
			$s1 	= mysqli_fetch_array($res);
			$party_name  	 		 = ucwords(strtolower($s1['username']));
			$party_bank_name 		 = $s1['bank_name'];
			$party_bank_account_type = $s1['bank_type'];
			$party_bank_address  	 = $s1['bank_branch'];
			$party_bank_account_no 	 = $s1['bank_ac_no'];
			$party_bank_ifsc_code  	 = $s1['bank_ifsc'];
		
			$sql 	= "SELECT a.remarks, b.* FROM `sma_travel_expenses` a, sma_expenses b where a.id = b.approval_ref_no and a.id = '$supp_id'";
			$res 	= mysqli_query($con,$sql);
			while($s1 	= mysqli_fetch_array($res)){
				$note 	= trim($s1['note']).' '. $s1['remarks'];
			}
			if(empty($note)){
				$note 	= $s1['remarks'];
			}
			
		}
		
		$ii = $ii + 1;
		if( $comp_code=='QB' || $comp_code=='KTPL' || $comp_code=='AIPL' || $comp_code=='AIIMPL' || $comp_code=='JUHI' ){
			$message .= "<tr>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>$ii</td>
						<td style='width: 5%;text-align: left;padding-top: 5px;padding-bottom: 5px;'>$party_name</td>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>&nbsp;$party_bank_account_no</td>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>$party_bank_ifsc_code</td>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>$party_bank_name</td>
						<td style='text-align:right;'>".moneyFormatIndia($total_amount_paid)."</td>
						<td style='text-align:center;'>$st_flag-$supp_id</td>
					</tr>";
		}
		else if( $comp_code=='ADTPL' || $comp_code=='ADHTPL' ){
			//Name of the Vendor	Amount	Account No.	ISFC Code	Description of the work	Ref No 
			$message .= "<tr>
						<td style='width: 5%;text-align: left;padding-top: 5px;padding-bottom: 5px;'>$party_name</td>
						<td style='text-align:right;'>".moneyFormatIndia($total_amount_paid)."</td>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>&nbsp;$party_bank_account_no</td>
						<td style='width: 5%;text-align: center;padding-top: 5px;padding-bottom: 5px;'>$party_bank_ifsc_code</td>
						<td style='width: 5%;text-align: left;padding-top: 5px;padding-bottom: 5px;'>$note</td>
						<td style='text-align:center;'>$st_flag-$supp_id</td>
					</tr>";
		}
		
		$total_amount_paid_total = $total_amount_paid_total + $total_amount_paid;
		
	}
	
	if( $comp_code=='ADTPL' || $comp_code=='ADHTPL' ){
		$message .= "<tr>
					<th style='text-align: center;padding-top: 5px;padding-bottom: 5px;' >Total </th>
					<th style='text-align:right;'>".moneyFormatIndia($total_amount_paid_total)."</th>
					<th style='text-align:left;' colspan='4'>".numbertowordA5($total_amount_paid_total)." Rupees Only</th>		
				</tr>";
	}
	else {	
		$message .= "<tr>
					<th style='text-align: center;padding-top: 5px;padding-bottom: 5px;' colspan='5'>Total Amount in Rs.</th>
					<th style='text-align:right;'>".moneyFormatIndia($total_amount_paid_total)."</th>
					<td style='text-align:left;'></td>		
				</tr>";
	}				
	
	
}
	$message .="</table>";
	//echo $message;
	//exit();
	
		$fl_name = 'rtgs_export_'.date("d-m-Y").'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;

function numbertowordA5($num){
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

		
?>		
		