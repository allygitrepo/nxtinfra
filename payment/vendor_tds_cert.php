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
        TDS Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">TDS Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
					<form class="form-horizontal" action="vendor_tds_cert.php?sub=pdf" target="_blank" method="post">
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Company Name</label>
							<div class="col-md-5">
								
								<select class="form-control select2-123" name="company_id" id="company_id" >
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
								<label class="col-lg-2 control-label">Supplier Name</label>
								<div class="col-md-5">
									<select class="form-control select2" name="vendor_id" id="vendor_id" >
										<option value=""> Select </option>
										<option value="All"> All </option>
											<?php $sql = "select * from sma_party_mst order by party_name  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
											<?php } ?>
									</select>
								</div>
						</div>
						
						<div class="form-group">		
								<?php $start_date = '01-04-2024' ?>
								<label class=" col-sm-2 control-label">Start.Date</label>
									
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
								<label class="col-sm-1 control-label">End.Date</label>
								<div class="col-md-2">	
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							</div>
						
							<div class="form-group">
								<label for="project" class="col-sm-2 control-label">Output</label>
								<div class="col-sm-2">
									<select class="form-control " name="prn" id="prn" >
										<option value=""> Select </option>
										<!--<option value="Pdf" selected > Pdf </option>-->
										<option value="Excel" > Excel </option>
											
									</select>
								</div>
						</div>
							
						<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="../dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
						</div>
							
				</form>
				
			</div>	
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
	//$id			= $_POST['id'];
	$vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	$prev_pay_id    = '';
	$message        = '';
	$grand_total_cr = 0;
	$grand_total_dr = 0;
	$amount_cumulative = 0;
	
			$sql 	= " SELECT *  FROM sma_party_mst where id = '$vendor_id' ";
			$res 	= mysqli_query($con,$sql);
			$rs 	= mysqli_fetch_array($res);
			$party_name	= $rs['party_name'];
			
			$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
			$result = mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$r1 = mysqli_fetch_array($result);
			$comp_code				= $r1['comp_code'];
			$comp_name				= $r1['comp_name'];
			
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;' colspan='10'> ". $comp_name . " </td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;' colspan='10'> ". $party_name . " </td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;' colspan='10'> TDS Working for the period from ".  date('d-m-Y', strtotime($start_date)) . " To " .  date('d-m-Y', strtotime($end_date)) . " </td></tr></table>";
	
	$message .="<br>";
	
	$message .= "<table cellspacing='0' border='1' style='width: 100%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:1px;'>
		<tr>
			<td style='width: 10%;'> Sr.No.</td>
			<td style='width: 10%;'> Month</td>
			<td style='width: 10%;'> Tally Entry Date</td>
			<td style='width: 10%;'> P2P Serial Number</td>
			
			<td style='width: 10%;'> Invoice Date</td>
			<td style='width: 10%;'> Supplier Invoice No.</td>
			<td style='width: 10%;'> Co/Non</td>
			
			<td style='width: 10%;'> TDS Section</td>
			<td style='width: 10%;'> Particulars</td>
			<td style='width: 10%;'> Narration</td>
			<td style='width: 10%;'> PAN No.</td>
			<td style='width: 10%;'> Advance / Actual Invoice</td>
			<td style='width: 10%;'> GST Number</td>
			
			<td style='width: 10%;'> RCM Applicable under GST or Not.</td>
			<td style='width: 10%;'> Product Value</td>
			<td style='width: 10%;'> Invoice Amount</td>
			
			
			<td style='width: 10%;'> Amount</td>
			<td style='width: 10%;text-align:right;'> Rate of TDS</td>	
			<td style='width: 10%;text-align:right;'> TDS Section</td>	
			
			<td style='width: 10%;text-align:right;'> TDS Amount</td>	
			
			
			<td style='width: 10%;'>GST Rate</td>
			<td style='width: 10%;'>CGST Amount</td>
			<td style='width: 10%;'>SGST Amount</td>
			<td style='width: 10%;'>IGST Amount</td>
				
			<td style='width: 10%;text-align:right;'> TDS Deducted as per Books</td>	
			<td style='width: 10%;text-align:right;'> TDS Round off Amount</td>	
			<td style='width: 10%;text-align:right;'> Interest (for Late Payment)</td>	
			
			<td style='width: 10%;text-align:right;'> LDC Certificate No</td>	
			<td style='width: 10%;text-align:right;'> Rate as per LDC</td>	
			<td style='width: 10%;text-align:right;'> Limit as per LDC</td>	
			
			<td style='width: 10%;text-align:right;'> Challan No</td>	
			<td style='width: 10%;text-align:right;'> Challan Date</td>	
			<td style='width: 10%;text-align:right;'> Remarks</td>	
		</tr></table>";
		
//echo $message; exit();
//	$sql = "SELECT * from tally_journal_entry where doc_date >= '$start_date' and doc_date <= '$end_date' and account_id = '$vendor_id' and account_type = 'V' and company_id = '$company_id' ";

	$tds_id = '';
	$sql    = " SELECT * FROM `account_mst` where account_name like '%tds%' ";
	$reshdr = mysqli_query($con,$sql);
	while($row   = mysqli_fetch_array($reshdr)){
		
		$tds_id .= $row['id'].',';
		
	}		
	$tds_id .= '99999';
	
if($vendor_id=='All'){
	$sqla ='';
	$sqlb ='';
	$sqlc ='';
	$sqld ='';
	$sqle = "";
}
else {
	$sqla = " and b.suplier_name = '$vendor_id' ";
	$sqlb = " and b.paid_to = '$vendor_id' ";
	$sqlc = " and b.emp_id = '$vendor_id' ";
	$sqld = " and b.emp_id = '$vendor_id' ";
	$sqle = " and c.spend_by = '$vendor_id' ";
}
	$sql = "SELECT a.*, b.tally_created_date , b.total_amount FROM `tally_journal_entry` a, sma_supplier_invoice b WHERE a.doc_no = b.id and a.doc_type = 'SI'  and a.company_id = '$company_id' and invoice_date >= '$start_date' and invoice_date <= '$end_date' and (a.account_id in ($tds_id) || a.account_name like '%tds%') $sqla
		UNION
	SELECT a.*, b.tally_created_date,' ' as total_amount FROM `tally_journal_entry` a, payment_header b WHERE a.doc_no = b.id and a.doc_type = 'PY' and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and (a.account_id in ($tds_id) || a.account_name like '%tds%')  $sqlb
	    UNION
	SELECT a.*, b.tally_created_date , b.total_amount FROM `tally_journal_entry` a, sma_travel_expenses b WHERE a.doc_no = b.id and a.doc_type = 'CE' and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and (a.account_id in ($tds_id) || a.account_name like '%tds%') $sqlc
		UNION
	SELECT a.*, b.tally_created_date , b.total_amount FROM `tally_journal_entry` a, sma_travel_expenses b WHERE a.doc_no = b.id and a.doc_type in ( 'RE' , 'TE') and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and (a.account_id in ($tds_id) || a.account_name like '%tds%') $sqld
		UNION
	SELECT a.*, ' ' as tally_created_date , ' ' as total_amount FROM `tally_journal_entry` a, sma_pettycash b, sma_pettycash_exp c WHERE a.doc_no = b.id and b.id = c.approval_ref_no and a.doc_type = 'PC' and a.company_id = '$company_id' and b.dated >= '$start_date' and b.dated <= '$end_date' and (a.account_id in ($tds_id) || a.account_name like '%tds%') $sqle order by doc_no, record_id ";

//echo $sql."<BR>"; 
//exit();

	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	while($row   = mysqli_fetch_array($reshdr)){
		
			$doc_no					= $row['doc_no'];
			$doc_type				= $row['doc_type'];
			$doc_date 				= date('d-m-Y', strtotime($row['doc_date']));
			$doc_month 				= date('F', strtotime($row['doc_date']));
			$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
			$tally_created_date		= date('d-m-Y', strtotime($row['tally_created_date']));
			$tally_uploaded_on		= date('d-m-Y', strtotime($row['tally_uploaded_on']));
			$company_id				= $row['company_id'];
			//$supplier_id			= $row['supplier_id'];
			$invoice_no				= $row['supp_invoice_no'];
			$account_type			= $row['account_type'];
			$account_id_v			= $row['account_id'];
			$account_name			= $row['account_name'];
			$bank_name				= $row['bank_name'];
			$effect					= $row['effect'];
			$amount					= $row['amount'];
			$total_amount			= $row['total_amount'];
			$narration				= $row['narration'];

			$sql = " SELECT * FROM `tally_journal_entry` where doc_no = '$doc_no' and doc_type = '$doc_type' and effect in ('Dr', 'Cr') and account_type = 'V'  order by amount desc"; 
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$supplier_id	= $rs['account_id'];
			
			$sql = " SELECT * FROM `tally_journal_entry` where doc_no = '$doc_no' and doc_type = '$doc_type' and effect = 'Cr' order by amount desc "; //account_type = 'V'
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$amount_tot			= $rs['amount'];
			$doc_date			= date('d-m-Y', strtotime($rs['doc_date']));
			
			$sgst_amount =0;
			$cgst_amount =0;
			$igst_amount =0;
			$gst_percentage =0;
			$gst_id = 0;
			$ij = 0;
			$sql = " SELECT a.*, b.percentage FROM `tally_journal_entry` a, account_mst b where 1 and a.account_id = b.id 
						and doc_no = '$doc_no' and doc_type = '$doc_type' 
						and a.account_name like '%gst%' and effect in ('Cr', 'Dr' )  ";
			$res 	= mysqli_query($con,$sql);
			$accnt = mysqli_affected_rows($con);
			/* if($accnt>0){
				echo $sql ."<BR>";
			} */	
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			while($rs 	= mysqli_fetch_array($res)){
				$ij = $ij + 1;
				$gst_percentage			= $rs['percentage'];
				//$account_id				= $rs['account_id'];
				
				if($gst_percentage==0){
					$sql = " SELECT * FROM `sma_supplier_invoice_details` where si_hdr_id = '$doc_no' ";
					$ress = mysqli_query($con,$sql);
					$error  = mysqli_error($con);
					if(!empty($error)){ echo "ERROR : " . $error; exit();}
					$r11 = mysqli_fetch_array($ress);
					$gst_id					= $r11['gst_id'];
					$gst_percentage			= $r11['gst'];
				}
				
				if($ij==1){
					$sgst_amount		= $rs['amount'];
					$igst_amount		= $igst_amount + $sgst_amount;
					
				}
				else if($ij==2){
					$cgst_amount		= $rs['amount'];
					$igst_amount		= 0;
					
				}
				
				$gst_account_name		= $rs['account_name'];
				
			}
			
			if($ij==1){
				$sgst_amount		= 0;
				$cgst_amount		= 0;
			}
			
			$reverse_gst_amount 	= 0;
			$reverse_gst_percentage = 0;
			$gst_id = 0;
			$ij 	= 0;
			$sql = " SELECT a.*, b.percentage FROM `tally_journal_entry` a, account_mst b where 1 and a.account_id = b.id 
						and doc_no = '$doc_no' and doc_type = '$doc_type' 
						and a.account_name like 'Reverse%gst%' and effect in ('Cr', 'Dr' )  ";
			$res 	= mysqli_query($con,$sql);
			$accnt = mysqli_affected_rows($con);
			/* if($accnt>0){
				echo $sql ."<BR>";
			} */	
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			while($rs 	= mysqli_fetch_array($res)){
				$ij = $ij + 1;
				$reverse_gst_percentage		= $rs['percentage'];
				//$account_id					= $rs['account_id'];
				
				if($reverse_gst_percentage==0){
					$sql = " SELECT * FROM `sma_supplier_invoice_details` where si_hdr_id = '$doc_no' ";
					$ress = mysqli_query($con,$sql);
					$error  = mysqli_error($con);
					if(!empty($error)){ echo "ERROR : " . $error; exit();}
					$r11 = mysqli_fetch_array($ress);
					$gst_id					= $r11['gst_id'];
					$reverse_gst_percentage			= $r11['gst'];
				}
				
				if($ij==1){
					$reverse_sgst_amount		= $rs['amount'];
					$reverse_gst_amount		= $reverse_gst_amount + $reverse_sgst_amount;
					
				}
				else if($ij==2){
					$reverse_cgst_amount		= $rs['amount'];
					$reverse_gst_amount		= $reverse_gst_amount + $reverse_cgst_amount;
					
				}
				
				$gst_account_name		= $rs['account_name'];
				
			}
			
			$sql = " SELECT * FROM `tally_journal_entry` where doc_no = '$doc_no' and doc_type = '$doc_type' and effect = 'Dr' order by amount desc "; 
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$test_budget_head		= $rs['account_name'];
			
			$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
			$result = mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$r1 = mysqli_fetch_array($result);
			$comp_code				= $r1['comp_code'];
			$comp_name				= $r1['comp_name'];
			
			$sql 	= " SELECT * FROM account_mst where id = '$account_id_v' ";
			$result = mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$r1 = mysqli_fetch_array($result);
			$tds_percentage			= $r1['percentage'];
			$account_name			= $r1['account_name'];
			$budget_head			= $r1['budget_head'];
			
			if($doc_type=='SI'){
				$test_v = 'Suupplier Invoice';
			}
			else if($doc_type=='CE'){
				$test_v = 'Operating Expences';
			}
			else if($doc_type=='RE'){
				$test_v = 'Regular Expences';
			}
			else if($doc_type=='TE'){
				$test_v = 'Travel Expences';
			}
			else if($doc_type=='PY'){
				$test_v = 'Payment';
				$sql 	= " SELECT * FROM payment_details where payment_hdr_id = '$doc_no' ";
				$result = mysqli_query($con,$sql);
				$error  = mysqli_error($con);
				if(!empty($error)){ echo "ERROR : " . $error; exit();}
				$r1 = mysqli_fetch_array($result);
				$invoice_no			= $r1['supplier_invoice_no'];				
			}
			else if($doc_type=='PC'){
				$test_v = 'Petty Cash';
			}
			
			$test_v = $test_budget_head; 
			
			$sql 	= " SELECT *  FROM sma_party_mst where id = '$supplier_id' ";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$rs 	= mysqli_fetch_array($res);
			$party_name			= $rs['party_name'];
			$panno				= $rs['party_pan_number'];
			$gstno				= $rs['party_gst_number'];
			$ldc_cert_no 					= $rs['ldc_cert_no'];
			$ldc_rate 						= $rs['ldc_rate'];
			$limit_as_per_ldc 				= $rs['limit_as_per_ldc'];

			if(substr($panno,3,1) != 'C' ){
				$non_co = 'Non Co';
			}
			else if(substr($panno,3,1) == 'C' ){
				$non_co = 'Co';
			}

			$tds_section = '';
			if (stripos($account_name, "194") !== false) {
				$tds_section = '194';
			}
			if (stripos($account_name, "194J") !== false) {
				$tds_section = '194J';
			}
			else if (stripos($account_name, "194I") !== false) {
				$tds_section = '194I';
			}
			else if (stripos($account_name, "194C") !== false) {
				$tds_section = '194C';
			}
			if (stripos($account_name, "194Ib") !== false) {
				$tds_section = '194Ib';
			}
			if (stripos($account_name, "194Ia") !== false) {
				$tds_section = '194Ia';
			}
			if (stripos($account_name, "194Q") !== false) {
				$tds_section = '194Q';
			}
			if (stripos($account_name, "194J") !== false) {
				$tds_section = '194J';
			}
			
			

			$message .= "<table cellspacing='0' border='1' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
			<tr><td style='width: 10%;'>".++$ii."</td>
				
				<td style='width: 10%;'>$doc_month</td>
				<td style='width: 10%;'>$tally_uploaded_on</td>
				<td style='width: 10%;'>$doc_type-$doc_no</td>
				
				<td style='width: 10%;'>$supp_invoice_date</td>
				<td style='width: 10%;'>$non_co</td>
				<td style='width: 10%;'>$invoice_no</td>
				<td style='width: 10%;'>$account_name</td>
				<td style='width: 10%;'>$party_name</td>
				<td style='width: 10%;'>$narration</td>
				
				<td style='width: 10%;'>$panno</td>
				<td style='width: 10%;'>$total_amount</td>
				<td style='width: 10%;'>$gstno</td>
				<td style='width: 10%;'>$reverse_gst_percentage</td>
				<td style='width: 10%;'>$amount_tot</td>
				<td style='width: 10%;'>$total_amount</td>
				
				<td style='width: 10%;text-align:right'>&nbsp;</td>
				<td style='width: 10%;text-align:right'>$tds_percentage</td>
				<td style='width: 10%;'>$tds_section</td>
				<td style='width: 10%;text-align:right'>$amount</td>
				
				<td style='width: 10%;'>$gst_percentage</td>
				<td style='width: 10%;'>$sgst_amount</td>
				<td style='width: 10%;'>$cgst_amount</td>
				<td style='width: 10%;'>$igst_amount</td>
				
				<td style='width: 10%;'></td>
				<td style='width: 10%;'></td>
				<td style='width: 10%;'></td>
				<td style='width: 10%;'>$ldc_cert_no</td>
				<td style='width: 10%;'>$ldc_rate</td>
				<td style='width: 10%;'>$limit_as_per_ldc</td>
				<td style='width: 10%;'></td>
				<td style='width: 10%;'></td>
				<td style='width: 10%;'></td>
				
			</tr></table>";

	}
	
    // get the HTML
    ob_start();
//echo $message;
//exit();
	$fl_name = 'tds_report_'.$party_name.'.xls';
//   if($prn=='Excel'){
		
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
//	}
	
}

 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertowordz($num){
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