<?php 

//	include("../header.php");
	include("../dbcon.php");
	$modulePath = "payment/";

		$party_bank_seq  ='';
		$sql = "TRUNCATE TABLE rtgs_temp";
		mysqli_query($con, $sql);
		
		$sql   = "SELECT * from payment_header where 1 and del !='Y' and utr_no = '' and cheque_no = ''  order by company_id, cash_bank_name, dated ";
//		$sql   .= " AND company_id = '$company_id' AND dated >= '$start_date' AND dated <= '$end_date' AND cash_bank_name = '$bank_id' ";

//echo $sql. "<BR>";
		
		$resultqry = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($rowr2 = mysqli_fetch_array($resultqry)){
		
		$cash_bank_name_id = $rowr2['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name 		= $r2['account_name'];
		$rtgs_format_available 	= $r2['rtgs_format_available'];
		
		if($rtgs_format_available!='Y'){
			continue;
		}
		
		$company_id = $rowr2['company_id'];
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_code = $r2['comp_code'];
		
		$paid_to = $rowr2['paid_to'];
		$st_flag = $rowr2['st_flag'];
		if($st_flag =='A' || $st_flag =='T'){
			$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$party_name  = ucwords(strtolower($r2['username']));
			$party_bank  = $r2['bank_name'];
		}
		else {
			$sql = "SELECT * FROM `sma_party_mst` where id = '$paid_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$party_name  = ucwords(strtolower($r2['party_name']));
			$party_bank  = $r2['party_bank_name'];
		}
		
		//$party_bank = strtoupper(substr($party_bank,0,4));
		if( strtoupper(substr($party_bank,0,4)) == 'AXIS' ){
			$party_bank_seq = '1';
		}
		else {
			$party_bank_seq = '2';
		}	
		
		if($st_flag=='S'){
			$st_flag ='SI';
		}
		else if($st_flag=='A'){
			$st_flag ='TA';
		}
		else if($st_flag=='T'){
			$st_flag ='TE';
		}
		else if($st_flag=='C'){
			$st_flag ='OE';
		}
		else if($st_flag=='D'){
			$st_flag ='SA';
		}
		
		$dated = date('d-m-Y', strtotime($rowr2['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$rid = $rowr2['id'];
		$sql = "SELECT a.supplier_invoice_no, a.supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.del !='Y' and payment_hdr_id = '$rid' "; //and b.st_flag = '$st_flag' 
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$supplier_invoice_no  = $r2['supplier_invoice_no'];
		$supp_id			  = $r2['supp_id'];
		
		$dated_v 			= $rowr2['dated'];
		$paid_date_v 		= $rowr2['paid_date'];
		$total_amount_paid 	= $rowr2['total_amount_paid'];
		
		$sql = " SELECT * from rtgs_temp where paid_to = '$paid_to' and st_flag = '$st_flag'";
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowaffect = mysqli_affected_rows($con);
//echo $sql.' ' .$rowaffect. "<BR>";
		if($rowaffect>0){
			$sql="UPDATE `rtgs_temp` set total_amount_paid = total_amount_paid + $total_amount_paid where paid_to = '$paid_to' ";
//echo $sql."<>";			
			mysqli_query($con, $sql);
	//		$rowaffect = mysqli_affected_rows($con);
	//		echo $sql.' ' .$rowaffect. "<BR>";
			echo mysqli_error($con);
			//exit();
		}
		else {
			$sql = "INSERT INTO `rtgs_temp` (py_id, company_id, company_code, paid_date, cash_bank_name_id, cash_bank_name, paid_to, party_name, party_bank, party_bank_seq, dated, supplier_invoice_no, supp_id, st_flag, total_amount_paid, selected) 
				VALUES ('$rid', '$company_id', '$company_code', '$paid_date_v', '$cash_bank_name_id', '$cash_bank_name', '$paid_to', '$party_name', '$party_bank', '$party_bank_seq', '$dated_v', '$supplier_invoice_no', '$supp_id', '$st_flag', '$total_amount_paid', 'Y' )";
			mysqli_query($con, $sql);
		}
		
	}

    // get the HTML
    ob_start();
	
//	if($prn=='view'){
				  
//		print $message;
//		exit();
			
//	}
	
	$message_m = '';
	$sql = "SELECT distinct(company_code) as company_code, company_id, cash_bank_name_id from rtgs_temp where 1  order by company_id, cash_bank_name, dated ";

//echo $sql. "<BR>";
		
		$resultqr2 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($rowrr2 = mysqli_fetch_array($resultqr2)){
		
		$bank_id 			= $rowrr2['cash_bank_name_id'];
		$comp_code 			= $rowrr2['company_code'];
		$company_id 		= $rowrr2['company_id'];
			
			//echo $comp_code."<BR>";
		$total_amount_paid_grand =0;
		
		if($comp_code=='JUHI'){
			include "juhi_neft_print.php";
			$message_m .= $message;
			//echo $message_m;
			//exit();
		}
		else if($comp_code=='ADHTPL'){
			
			include "adhtpl_neft_print.php";
			$message_m .= $message;
			//echo $message_m;
			//exit('ADHTPL...');
		}
		else if($comp_code=='ADTPL'){
			include "adtpl_neft_print.php";
			$message_m .= $message;
			//exit();
			//$continue = '';
		}
		else if($comp_code=='QB'){
			include "qb_neft_print.php";
			$message_m .= $message;
			//exit();
		}
		else if($comp_code=='KTPL'){
			include "ktpl_neft_print.php";
			$message_m .= $message;
			//echo $message_m;
			//exit('KTPL');
		}
		else if($comp_code=='AIPL'){
			$continue = '';
			continue;
		}
 
	}
 
 //echo $message_m;
 //exit('Hllo...');
 
	require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'neft_print'.'_'.date('d-m-Y'). '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->setDefaultFont('Arial');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message_m);
			//if(isset($_POST['company_id']) ){
				$pdf = $html2pdf->Output($fl_name, true);
			/* }
			else { 

				$html2pdf->Output($fl_name);
			} */	
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
	include "neft_mail.php";
	
	exit('Done Mail...');
	
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

?>

<script>
	function getbank(id){
		
        var sub    = 'sub1';
//	alert(sub + ' BANK ' + id);		
		 var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getbank').html(result);
		});
 
	}
	
	
	function getvendor(id){
		
        var sub    = 'sub2';
		
//	alert(sub + ' <<>> ' +  id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getvendor').html(result);
		});
	}
	
	
	function getchecked(id){
		
        var sub    = 'sub3';
//	alert(sub + ' BANK ' + id);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      //$('#getbank').html(result);
		});
 
	}
	
	function getapprover(){
		
		var company_id  = document.getElementById("company_id").value;
		//var tot_payment_adjusted    = document.getElementById("tot_payment_adjusted").value;
		//var py_id 		= document.getElementById("py_ID").value;
		
		//var trans_type    = document.getElementById("po_doc_type").value;
		//var trans_type    = 41;
		var sub = 'sub4';
//alert(sub + ' ' + company_id + ' ' + st_flag);
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,sub4:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	
	function getsubmit(){
		var sub = 'sub5';	
		var company_id  = document.getElementById("company_id").value;
		var bank_id  	= document.getElementById("bank_id").value;
		var rtgs_dd  	= document.getElementById("rtgs_dd").value;
		
		var approver_1 	= $(".approver_1").val();
//		alert(sub + ' ' + company_id + ' ' + bank_id + ' ' + approver_1);
		var strURL = "rtgs_mail.php";
		$.post(strURL,{company_id:company_id,bank_id:bank_id,approver_1:approver_1,rtgs_dd:rtgs_dd,sub5:sub},function(result){
		      $('#getapprover').html(result);
			  alert('Email Send....');
		});
	}	
	
	
</script>


