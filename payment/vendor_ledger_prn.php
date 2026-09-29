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
								
								<?php $start_date = '01-04-2020' ?>
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
										<option value="Pdf" selected > Pdf </option>
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

	$prev_pay_id    = '';
	$message        = '';
	$grand_total_cr = 0;
	$grand_total_dr = 0;
	$amount_cumulative = 0;
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 12px;margin-left:1px;'><tr><td style='width: 100%;'> Ledger for the period From $start_date to $end_date </td></tr></table>";

	$sql 	= "SELECT a.*, b.id as dtl_id FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and paid_date >= '$start_date' and paid_date <= '$end_date' and paid_to  = '$vendor_id' and del !='Y' and company_id = '$company_id' ";
//echo $sql;	
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	$phdr   = mysqli_fetch_array($reshdr);
	$id					= $phdr['id'];
	$dtl_id				= $phdr['dtl_id'];
	$st_flag 		    = $phdr['st_flag'];
	
	$sql 	= "SELECT b.* FROM `payment_header` a, company b where a.company_id = b.comp_id and a.id = '$id'";
//echo $sql. "<br>";	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
		$comp_name				= $row['comp_name'];
	}
	
	if($st_flag =='S' || $st_flag =='A'){
		$sql 	= " SELECT a.total_amount, a.tally_narration, a.suplier_name as supplier_id, a.supplier_invoice_no, a.invoice_date, b.* FROM `sma_supplier_invoice` a, sma_purchase_order b 
						where a.our_po_ref_no = b.id
							and a.id in (SELECT supp_id FROM `payment_details` where id = '$dtl_id') ";
		$reshdr = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		while($rs = mysqli_fetch_array($reshdr)){
			$supplier_id				= $rs['supplier_id'];
			$location_id				= $rs['location'];
		}		
			$sql = " SELECT * FROM `sma_location` where id = '$location_id' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$location					= $rs['loc_name'];
	}
	else if( $st_flag=='C' || $st_flag=='R' || $st_flag=='T' ){
		$sql ="SELECT a.exp_type, a.total_amount, a.tally_narration, a.emp_id, b.invoice_no, b.dated, b.amount 
				FROM `sma_travel_expenses` a, sma_expenses b where a.id = b.approval_ref_no and a.id in (SELECT supp_id FROM `payment_details` where payment_hdr_id = '$id') ";
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
	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
	<tr><td style='width: 10%;'>Company</td>
		<td style='width: 35%;'>$comp_name</td>
		<td style='width: 10%;'></td>
		<td style='width: 25%;'></td>
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
	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
		<tr>
		<td style='width: 10%;'> Date</td>
		<td style='width: 15%;'> Doc Type</td>
		<td style='width: 15%;'> Doc No</td>
		<td style='width: 20%;'> Narration</td>
		<td style='width: 10%;'> Mode of Transaction</td>
		<td style='width: 10%;text-align:right;'> CR AMT</td>
		<td style='width: 10%;text-align:right;'> DR AMT</td>
		<td style='width: 10%;text-align:right;'> Net Amount</td>		
		</tr></table>";
		
//echo $message; exit();

$sql 	= "SELECT a.*, b.id as dtl_id, b.supp_id, b.invoice_date, b.supplier_invoice_no FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and paid_date >= '$start_date' and paid_date <= '$end_date' and paid_to  = '$vendor_id' and del !='Y' and company_id = '$company_id' ";
//echo $sql. "<BR>";
//$sql 	= "SELECT * FROM `payment_header` a where paid_date >= '$start_date' and paid_date <= '$end_date' and paid_to  = '$vendor_id' and del !='Y' and company_id = '$company_id' ";
//echo $sql;	
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	while($phdr   = mysqli_fetch_array($reshdr)){
		$id					= $phdr['id'];
		$dtl_id				= $phdr['dtl_id'];
		$supp_id			= $phdr['supp_id'];
		$st_flag 		    = $phdr['st_flag'];
		$total_amount_paid  = $phdr['total_amount_paid'];
		$paid_date			= date('d-m-Y', strtotime($phdr['paid_date']));
		$invoice_date		= date('d-m-Y', strtotime($phdr['invoice_date']));
		$supplier_invoice_no  = $phdr['supplier_invoice_no'];
		$utr_no				= $phdr['utr_no'];
		$tally_narration    = $phdr['tally_narration'];

		//Payment
		if($st_flag =='S'){
			$doc_type = 'SI';
			$test_v					= 'Supplier Invoice';
		}
		else if($st_flag =='C' || $st_flag =='T'){
			$doc_type = 'PY';
			$test_v					= 'Operating Exp.';
		}
		
		if($st_flag =='D'){
			//SELECT * FROM `tally_journal_entry` WHERE doc_no = 5471 and doc_Type = 'PY'
			$doc_type 		= 'PY';
			$test_v			= 'Purchase Order';
			$supp_id 		= $id;
			$account_type	= 'A';
		}
		else {
			$account_type	= 'B';
		}	
		
		$sql 	= "SELECT * FROM `tally_journal_entry` 
					where doc_type = '$doc_type' and doc_no = '$supp_id' and account_type = '$account_type' ";
		//$sql 	= "SELECT * FROM `sma_supplier_invoice` where id = '$supp_id' ";
		
		$result = mysqli_query($con,$sql);
		if(mysqli_affected_rows($con)== 0){ continue;};  // only check tally records.

//echo $sql."<br>";
			
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($row = mysqli_fetch_array($result)){
			
			$account_type_py			= $row['account_type'];	
			$account_id_py				= $row['account_id'];
		
			if( $account_type== 'A' ){
				$sql 	= "SELECT * FROM account_mst where id = '$account_id_py'";
				$res 	= mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				$s1 	= mysqli_fetch_array($res);
				$account_name_py  	 = $s1['account_name'];	
				$account_ty_py      = $s1['account_type'];
			}
			//SELECT supplier_invoice_no, invoice_date, company_id, suplier_name, total_amount, bal_amount, our_po_ref_no FROM `sma_supplier_invoice`

			//if($account_ty_py !='B'){continue;}
		
			$doc_no_py					= $row['doc_no'];
			$doc_type_py				= $row['doc_type'];
			$doc_date_py 				= date('d-m-Y', strtotime($row['doc_date']));
			$supp_invoice_date_py		= date('d-m-Y', strtotime($row['supp_invoice_date']));
			$company_id_py				= $row['company_id'];
			$supplier_id_py				= $row['supplier_id'];
			$supp_invoice_no_py			= $row['supp_invoice_no'];
			$account_type_py			= $row['account_type'];
			$account_id_py				= $row['account_id'];
			$account_name_py			= $row['account_name'];
			$bank_name_py				= $row['bank_name'];
			$effect_py					= $row['effect'];
			$amount_py					= $row['amount'];
			$narration_py				= $row['narration'];
		
			$amount_dr_py 	='0';
			$amount_cr_py	='0';
			$dedction_py	='';
			
			if($st_flag =='D' ) {
				$amount_dr_py 	=	$total_amount_paid;
				$amount_cr_py 	= 0;
				$effect_py		= 'Dr';
			}	
			if($effect_py=='Dr'){
				$amount_dr_py 		= $amount_py;
				$amount_cumulative 	= $amount_cumulative + $amount_dr_py;
				$grand_total_dr 	= $grand_total_dr + $amount_py;
			}
			if($effect_py=='Cr'){
				$amount_cr_py 		= $amount_py;
				$amount_cumulative 	= $amount_cumulative + $amount_cr_py;
				$grand_total_cr 	= $grand_total_cr + $amount_py;
			}
			
			if($amount_dr_py ==0 ){
				$amount_dr_py =	$total_amount_paid;
			}
			
			
			$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
				<tr>
				<td style='width: 10%;'>$invoice_date</td>
				<td style='width: 15%;'>$test_v</td>
				<td style='width: 15%;'>$supplier_invoice_no</td>
				<td style='width: 20%;'>$tally_narration</td>
				<td style='width: 10%;'> </td>
				<td style='width: 10%;text-align:right;'>".number_format($amount_dr_py,2)."</td>
				<td style='width: 10%;text-align:right;'>".number_format($amount_cr_py,2)."</td>
				<td style='width: 10%;text-align:right;'>".number_format($amount_py,2)."</td>		
				</tr></table>";
		
		$sql 	= "SELECT * FROM `tally_journal_entry` where doc_type = '$doc_type' and doc_no = '$supp_id' and account_type = 'A' ";
//echo $sql ."<BR>";

		$result2 = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
			while($rows = mysqli_fetch_array($result2)){

				$account_type			= $rows['account_type'];	
				$account_id				= $rows['account_id'];
				if( $account_type== 'A' ){
					$sql 	= "SELECT * FROM account_mst where id = '$account_id'";
					$res 	= mysqli_query($con,$sql);
					$error  = mysqli_error($con);
					$s2 	= mysqli_fetch_array($res);
					$account_name  	 = $s2['account_name'];	
					$account_ty      = $s2['account_type'];
				}
				if($account_ty!='D'){continue;}
			
				$doc_no					= $rows['doc_no'];
				$doc_type				= $rows['doc_type'];
				$doc_date  				= date('d-m-Y', strtotime($rows['doc_date']));
				$supp_invoice_date		= date('d-m-Y', strtotime($rows['supp_invoice_date']));
				$company_id				= $rows['company_id'];
				$supp_invoice_no		= $rows['supp_invoice_no'];
				$account_type			= $rows['account_type'];
				$account_id				= $rows['account_id'];
				$account_name			= $rows['account_name'];
				$bank_name				= $rows['bank_name'];
				$effect					= $rows['effect'];
				$amount					= $rows['amount'];
				$narration				= $rows['narration'];
				
				$amount_dr 	='0';
				$amount_cr	='0';
				$dedction	='';
				
				if($effect=='Dr'){
					$amount_dr 		= $amount;
					$amount_dr_tot 	= $amount_dr_tot + $amount_cr;
					$grand_total_cr = $grand_total_cr + $amount_cr;
					$amount_cumulative = $amount_cumulative - $amount;
				}
				if($effect=='Cr'){
					$amount_cr 		= $amount;
					$amount_cr_tot 	= $amount_cr_tot + $amount_dr;
					$grand_total_cr = $grand_total_cr + $amount_cr;
					$amount_cumulative = $amount_cumulative - $amount;
				}
				
				
			$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
					<tr>
					<td style='width: 10%;'>$invoice_date</td>
					<td style='width: 15%;'>$account_name </td>
					<td style='width: 15%;'></td>
					<td style='width: 20%;'>$account_name</td>
					<td style='width: 10%;'>Deduction </td>
					<td style='width: 10%;text-align:right;'>".number_format($amount_dr,2)."</td>
					<td style='width: 10%;text-align:right;'>".number_format($amount_cr,2)."</td>
					<td style='width: 10%;text-align:right;'>".number_format($amount_cumulative,2)."</td>	
					</tr></table>";
				}				
				
			}
				
				if($prev_pay_id != $id && !empty($prev_pay_id)){
				
					$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
						<tr>
						<td style='width: 10%;'>$paid_date</td>
						<td style='width: 15%;'>Payment </td>
						<td style='width: 15%;'>$prev_pay_id</td>
						<td style='width: 20%;'>$account_name</td>
						<td style='width: 10%;'>Payment </td>
						<td style='width: 10%;text-align:right;'>".number_format($amount_dr,2)."</td>
						<td style='width: 10%;text-align:right;'>".number_format($amount_cr,2)."</td>
						<td style='width: 10%;text-align:right;'>&nbsp;</td>		
						</tr></table>";
						
					$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
					<tr>
					<td style='width: 10%;'>&nbsp;</td>
					<td style='width: 15%;'>&nbsp;</td>
					<td style='width: 15%;'>&nbsp;</td>
					<td style='width: 20%;'>Grand Total</td>
					<td style='width: 10%;'>&nbsp;</td>
					<td style='width: 10%;text-align:right;'>&nbsp;</td>
					<td style='width: 10%;text-align:right;'>".number_format($grand_total_dr,2)."</td>
					<td style='width: 10%;text-align:right;'>".number_format($grand_total_cr,2)."</td>		
					</tr></table>";
					
					$grand_total_dr = 0;
					$grand_total_cr	= 0;
					
				}
			
			$prev_pay_id = $id;
						
		}
		
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
					<tr>
					<td style='width: 10%;'>$paid_date</td>
					<td style='width: 15%;'>Payment</td>
					<td style='width: 15%;'>$prev_pay_id</td>
					<td style='width: 20%;'>$account_name</td>
					<td style='width: 10%;'>$utr_no </td>
					<td style='width: 10%;text-align:right;'>&nbsp;</td>
					<td style='width: 10%;text-align:right;'>".number_format($total_amount_paid,2)."</td>
					<td style='width: 10%;text-align:right;'>&nbsp;</td>		
					</tr></table>";
		
		$grand_total_cr = $grand_total_cr + $total_amount_paid;
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
					<tr>
					<td style='width: 10%;'>&nbsp;</td>
					<td style='width: 15%;'>&nbsp;</td>
					<td style='width: 15%;'>&nbsp;</td>
					<td style='width: 20%;'>Grand Total</td>
					<td style='width: 10%;'>&nbsp;</td>
					<td style='width: 10%;text-align:right;'>".number_format($grand_total_dr,2)."</td>
					<td style='width: 10%;text-align:right;'>".number_format($grand_total_cr,2)."</td>
					<td style='width: 10%;text-align:right;'>&nbsp;</td>					
					</tr></table>";
					

//echo $message; exit();
//================



	
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