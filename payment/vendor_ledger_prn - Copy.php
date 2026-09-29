<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";

	$id					= $_GET['id'];
	$vendor_id			= $_GET['vendor_id'];
	$company_id			= $_GET['company_id'];

?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vendor Ledger Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Vendor Ledger Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
					<form class="form-horizontal" action="vendor_ledger_prn.php?sub=pdf" method="post">
                      
						<div class="form-group">
								
							<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id;?>" > 
							<input type="hidden" class="form-control" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id;?>" > 
							<input type="hidden" class="form-control" id="company_id" name="company_id" value="<?php echo $company_id;?>" > 
								
								<div class="col-md-2">
									<label class="control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							
								<div class="col-sm-2">
									<label for="project" class="control-label">Output</label>
									<select class="form-control " name="prn" id="prn" >
										<option value=""> Select </option>
										<option value="Pdf" > Pdf </option>
										<option value="Excel" > Excel </option>
											
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

}



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

	$prn		= $_POST['prn'];
	$id			= $_POST['id'];
	$vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	$message ='';
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:1px;'><tr><td style='width: 100%;'> Ledger</td></tr></table>";

	$sql 	= "SELECT a.*, b.id as dtl_id FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and company_id = '$company_id' and paid_date >= '$start_date' and paid_date <= '$end_date' and paid_to  = '$vendor_id' and del !='Y' ";
//echo $sql."<BR>";	exit();
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($phdr = mysqli_fetch_array($reshdr)){
		$id 	 = $phdr['id'];
		$dtl_id 	 = $phdr['dtl_id'];
		$st_flag = $phdr['st_flag'];
		
//		echo $vendor_id. ' ' . $id . ' ' . $start_date . ' ' . $end_date. ' ' . $id."<BR>";
		
	$amount_dr_tot =0;
	$amount_cr_tot =0;
	
	$sql 	= "SELECT b.* FROM `payment_header` a, company b where a.company_id = b.comp_id and a.id = '$id'";
//echo $sql. "<br>";	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
		$comp_name				= $row['comp_name'];
	}
	
		$location	='';
		$test_v		='';
	$total_amount ='';	
	if($st_flag=='S'){
		$sql 	= " SELECT a.total_amount, a.tally_narration, a.suplier_name as supplier_id, a.supplier_invoice_no, a.invoice_date, b.* FROM `sma_supplier_invoice` a, sma_purchase_order b 
					where a.our_po_ref_no = b.id
						and a.id in (SELECT supp_id FROM `payment_details` where id = '$dtl_id') ";
//echo $sql; exit();						
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$rs 	= mysqli_fetch_array($res);
			$total_amount				= $rs['total_amount'];
			$tally_narration			= $rs['tally_narration'];
			$supplier_id				= $rs['supplier_id'];
			$supplier_invoice_no		= $rs['supplier_invoice_no'].' test';
			$invoice_date				= date('d-m-Y', strtotime($rs['invoice_date']));
			$location_id				= $rs['location'];
			$test_v						= 'Purchase';
			
			$sql = " SELECT * FROM `sma_location` where id = '$location_id' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$location					= $rs['loc_name'];
		

	}
	else if( $st_flag=='C' || $st_flag=='R' || $st_flag=='T' ){
		$sql ="SELECT a.exp_type, a.total_amount, a.tally_narration, a.emp_id, b.invoice_no, b.dated, b.amount 
				FROM `sma_travel_expenses` a, sma_expenses b where a.id = b.approval_ref_no and a.id and a.id in (SELECT supp_id FROM `payment_details` where payment_hdr_id = '$id') ";
//echo $sql."<BR>";				
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$rs 	= mysqli_fetch_array($res);
		$total_amount				= $rs['total_amount'];
		$tally_narration			= $rs['tally_narration'];
		$supplier_id				= $rs['emp_id'];
		$supplier_invoice_no		= $rs['invoice_no'];
		$invoice_date				= date('d-m-Y', strtotime($rs['dated']));	
		$exp_type					= $rs['exp_type'];
		if($exp_type=='C'){
			$test_v					= 'Operating Exp.';
		}
		else if ($exp_type=='R'){
			$test_v					= 'Regular Exp.';
		}
		else if ($exp_type=='T'){
			$test_v					= 'Travelling Exp.';
		}
	}	
	
	if($total_amount==0){
		continue;
	}
//echo $sql; exit();

	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
	<tr><td style='width: 10%;'>Company</td>
		<td style='width: 35%;'>$comp_name</td>
		<td style='width: 10%;'>Location</td>
		<td style='width: 25%;'>$location</td>
		<td style='width: 10%;'>Date</td>
		<td style='width: 10%;'>".date("d-m-Y")."</td>
	</tr></table>";

	$sql 	= " SELECT *  FROM sma_party_mst where id = '$supplier_id' ";
	$res 	= mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rs 	= mysqli_fetch_array($res);
	$party_name				 	= $rs['party_name'];

	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:1px;'>
	<tr><td style='width: 10%;'>Vendor Name</td>
		<td style='width: 35%;'>$party_name</td>
		<td style='width: 10%;'>&nbsp;</td>
		<td style='width: 25%;'>&nbsp;</td>
		<td style='width: 10%;'>&nbsp;</td>
		<td style='width: 10%;'>&nbsp;</td>
	</tr></table>";

	$sql = "SELECT * FROM `payment_details` where id = '$dtl_id' ";
//	$sql = "SELECT * FROM `payment_details` where payment_hdr_id = '$id' ";
	$res 	= mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rs 	= mysqli_fetch_array($res);
	$paid_amount				= $rs['payment_adjusted'];
	$supp_id					= $rs['supp_id'];
	
	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
		<tr>
		<td style='width: 10%;'> Date</td>
		<td style='width: 15%;'> Doc Type</td>
		<td style='width: 15%;'> Doc No</td>
		<td style='width: 20%;'> Narration</td>
		<td style='width: 10%;'> Mode of Transaction</td>
		<td style='width: 10%;text-align:right;'> DR AMT</td>
		<td style='width: 10%;text-align:right;'> CR AMT</td>
		<td style='width: 10%;text-align:right;'> Net Amount</td>		
		</tr></table>";
		
	$amount_cr_tot = $total_amount;
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
		<tr>
		<td style='width: 10%;'> $invoice_date</td>
		<td style='width: 15%;'> $test_v ($supp_id)</td>
		<td style='width: 15%;word-break:break-all;'>$supplier_invoice_no</td>
		<td style='width: 20%;'> $tally_narration</td>
		<td style='width: 10%;'> $test_v</td>
		<td style='width: 10%;'> &nbsp;</td>
		<td style='width: 10%;text-align:right;'> $total_amount</td>
		<td style='width: 10%;text-align:right;'> $total_amount</td>		
		</tr></table>";
	
	$sql = "SELECT * FROM `payment_details` where id = '$dtl_id' ";
	$res 	= mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rs 	= mysqli_fetch_array($res);
	$paid_amount				= $rs['payment_adjusted'];
	$supp_id					= $rs['supp_id'];
	
	$sql 	= "SELECT * FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$supp_id' and account_type = 'A' ";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$account_type			= $row['account_type'];	
		$account_id				= $row['account_id'];
		if( $account_type== 'A' ){
			$sql 	= "SELECT * FROM account_mst where id = '$account_id'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			$account_name  	 = $s1['account_name'];	
			$account_ty      = $s1['account_type'];
		}
		if($account_ty!='D'){continue;}
	
		$doc_no					= $row['doc_no'];
		$doc_type				= $row['doc_type'];
		$doc_date  				= date('d-m-Y', strtotime($row['doc_date']));
		$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
		$company_id				= $row['company_id'];
		$supp_invoice_no		= $row['supp_invoice_no'];
		$account_type			= $row['account_type'];
		$account_id				= $row['account_id'];
		$account_name			= $row['account_name'];
		$bank_name				= $row['bank_name'];
		$effect					= $row['effect'];
		$amount					= $row['amount'];
		$narration				= $row['narration'];
		
		$amount_dr 	='';
		$amount_cr	='';
		$dedction	='';
		
		if($effect=='Dr'){
			$amount_cr 		= $amount;
			$amount_cr_tot 	= $amount_cr_tot + $amount_cr;
		}
		if($effect=='Cr'){
			$amount_dr 		= $amount;
			$amount_dr_tot 	= $amount_dr_tot + $amount_dr;
		}
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
			<tr>
			<td style='width: 10%;'>&nbsp;</td>
			<td style='width: 20%;'>$account_name . ' SI123'</td>
			<td style='width: 10%;'></td>
			<td style='width: 20%;'>$account_name</td>
			<td style='width: 10%;'>Deduction </td>
			<td style='width: 10%;text-align:right;'>$amount_dr</td>
			<td style='width: 10%;text-align:right;'>$amount_cr</td>
			<td style='width: 10%;text-align:right;'>$amount</td>		
			</tr></table>";
		
	}

//================
//Payment
	$sql 	= "SELECT * FROM `tally_journal_entry` where doc_type = 'PY' and doc_no = '$id' and account_type = 'A' ";
//echo $sql."<br>";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		
		$account_type			= $row['account_type'];	
		$account_id				= $row['account_id'];
		if( $account_type== 'A' ){
			$sql 	= "SELECT * FROM account_mst where id = '$account_id'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			$account_name  	 = $s1['account_name'];	
			$account_ty      = $s1['account_type'];
		}
		if($account_ty!='B'){continue;}
	
		$doc_no					= $row['doc_no'];
		$doc_type				= $row['doc_type'];
		$doc_date  				= date('d-m-Y', strtotime($row['doc_date']));
		$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
		$company_id				= $row['company_id'];
		$supp_invoice_no		= $row['supp_invoice_no'];
		$account_type			= $row['account_type'];
		$account_id				= $row['account_id'];
		$account_name			= $row['account_name'];
		$bank_name				= $row['bank_name'];
		$effect					= $row['effect'];
		$amount					= $row['amount'];
		$narration				= $row['narration'];
	
		$amount_dr 	='';
		$amount_cr	='';
		$dedction	='';
		
		if($effect=='Dr'){
			$amount_cr 		= $amount;
			$amount_cr_tot 	= $amount_cr_tot + $amount_cr;
		}
		if($effect=='Cr'){
			$amount_dr 		= $amount;
			$amount_dr_tot 	= $amount_dr_tot + $amount_dr;
		}
		
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
			<tr>
			<td style='width: 10%;'>&nbsp;</td>
			<td style='width: 20%;'>$account_name. ' TY123' </td>
			<td style='width: 10%;'></td>
			<td style='width: 20%;'>$account_name</td>
			<td style='width: 10%;'>Payment </td>
			<td style='width: 10%;text-align:right;'>$amount_dr</td>
			<td style='width: 10%;text-align:right;'>$amount_cr</td>
			<td style='width: 10%;text-align:right;'>$amount</td>		
			</tr></table>";
	
	}

	$grand_tot = $amount_cr_tot - $amount_dr_tot;
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
			<tr>
			<td style='width: 10%;'>&nbsp;</td>
			<td style='width: 20%;'>&nbsp;</td>
			<td style='width: 10%;'>&nbsp;</td>
			<td style='width: 20%;'>Grand Total</td>
			<td style='width: 10%;'>&nbsp;</td>
			<td style='width: 10%;text-align:right;'>$amount_dr_tot</td>
			<td style='width: 10%;text-align:right;'>$amount_cr_tot</td>
			<td style='width: 10%;text-align:right;'>$grand_tot</td>	
			</tr></table>";

}

	
//echo $message;
//exit();
	

	
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
	if($prn=='Pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
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