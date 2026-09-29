<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "payment/";

	$comid  			= $_SESSION['comid'];
	
	$id					= $_GET['id'];
	$vendor_id			= $_GET['vendor_id'];
	$company_id			= $_GET['company_id'];
	$_POST['rtgs_text'] = '';

?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        RTGS Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">RTGS Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
					<form class="form-horizontal" action="neft_bundle_prn.php?sub=checklist" target="_blank" method="post">
						<div class="form-group">
							<label class="col-lg-2 control-label">Company Name<span style="color:red;"> **</span></label>
							<div class="col-md-5">
								<select class="form-control select2-123" name="company_id" id="companyid" onchange="getbank(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
							
							
								<label class="col-lg-2 control-label">Instrument No.<span style="color:red;"></span></label>
							
								<div class="col-md-2">
									<input type="text" class="form-control" name='instrument_no' id='instrument_no' value='' >
								
								</div>
						
						</div>
						
						<div class="form-group">		
								<?php $start_date = date('d-m-Y') ?>
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
								<label class="col-lg-2 control-label">Paid via<span style="color:red;"> **</span></label>
						<span id="getbank">	
								<div class="col-md-5">
								<input type="text" class="form-control"  >
								</div>
						</span>		
									
						</div>
							
					<!--	<div class="form-group">
								<label class="col-lg-2 control-label">Vendor<span style="color:red;"> **</span></label>
						<span id="getvendor">
								<div class="col-md-5">
									<select class="form-control select2" name="party_id" id="party_id" >
										<option value=""> Select </option>
										<option value="" selected > All </option>
										<?php $sql = "select * from sma_party_mst where 1 order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>
								</div>
						</span>		
									
						</div>-->
						<div class="form-group">
								<label for="project" class="col-sm-2 control-label">RTGS / DD</label>
								<div class="col-sm-2">
									<select class="form-control " name="rtgs_dd" id="rtgs_dd" >
										<option value=""> Select </option>
										<option value="RTGS" selected > RTGS </option>
										<option value="DD" > DD </option>
											
									</select>
								</div>
						</div>
						
					<!--	<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Text</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="rtgs_text"
                                                  placeholder="Enter text ..."  <?php echo $readonly; ?> ></textarea>
                                    </div>
									
									
								</div>
						</div>
						
						<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Bank Account Text</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason1" name="bank_account_text"
                                                  placeholder="Enter Bank Account text ..."  <?php echo $readonly; ?> ></textarea>
                                    </div>
								</div>
						</div>
					-->	
						<div class="form-group">
								<label for="project" class="col-sm-2 control-label">Output</label>
								<div class="col-sm-2">
									<select class="form-control " name="prn" id="prn" >
										<option value=""> Select </option>
										<option value="pdf" selected > Pdf </option>
										<option value="view" > Screen </option>
											
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

?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>


<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
         CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		CKEDITOR.replace('reason4');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
		 
    });

</script>
<?php 

}

?>

<?php 

if($_GET['sub'] == 'checklist'){
	
	include("../header.php");
	include("../baseurl.php");
	$modulePath = "payment/";

	$prn		= $_POST['prn'];
	$vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	$bank_id	= $_POST['cash_bank_name'];
	$rtgs_dd	= $_POST['rtgs_dd'];
	$instrument_no = $_POST['instrument_no'];
	$_SESSION['rtgs_text'] = $_POST['rtgs_text'];
	$_SESSION['bank_account_text'] = $_POST['bank_account_text'];
	$rtgs_text 			= $_POST['rtgs_text'];
	$bank_account_text 	= $_POST['bank_account_text'];
	
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	$sql 	= "select * from company where comp_id = '$company_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$comp_code = $r2['comp_code'];
	$comp_name = $r2['comp_name'];
	
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        RTGS Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">RTGS Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
			<div class="box-header">
				<div class=" pull-right col-xs-1 ">
					<?php 
						if($comp_code == 'AIPL' ){
					?>
						<span class="pull-right"><a href="aipl_neft_export.php?sub=list" name="btnAdd" class="btn btn-info" target="_blank" ><i class="splashy-document_letter_add"></i>Export</a></span>
					<?php }
						else {	 ?>
						<span class="pull-right"><a href="neft_export.php?sub=list" name="btnAdd" class="btn btn-info" target="_blank" ><i class="splashy-document_letter_add"></i>Export</a></span>
					<?php } ?>
				</div>	
				<div class=" pull-right col-xs-1">
					<span class="pull-rightt"><a href="neft_export_rtgs.php?sub=list&company_id=<?= $company_id;?>" name="btnAdd" class="btn btn-danger" target="_blank" ><i class="splashy-document_letter_add"></i>RTGS Export</a></span>
				</div>
				
				<div class=" pull-right col-xs-1">
					<span class="pull-right"><a href="neft_print.php?sub=list&company_id=<?= $company_id;?>&bank_id=<?= $bank_id; ?>&rtgs_dd=<?= $rtgs_dd;?>&instrument_no=<?= $instrument_no;?>" name="btnAdd" class="btn btn-primary" target="_blank" ><i class="splashy-document_letter_add"></i>Print </a></span>
				</div>
				
				<div class=" pull-right col-xs-1">
					<span class="pull-right"><a href="neft_bundle_prn.php?sub=list" name="btnAdd" class="btn btn-primary"  ><i class="splashy-document_letter_add"></i>Back </a></span>
				</div>
				
				<input type="hidden" name="company_id" id="company_id" value="<?= $company_id; ?>">
				
				<input type="hidden" name="bank_id" id="bank_id" value="<?= $bank_id; ?>">
				
				<input type="hidden" name="rtgs_dd" id="rtgs_dd" value="<?= $rtgs_dd; ?>">
				<input type="hidden" name="instrument_no" id="instrument_no" value="<?= $instrument_no; ?>">
				<input type="hidden" name="rtgs_text" id="rtgs_text" value="<?= $rtgs_text ; ?>">
				<input type="hidden" name="bank_account_text" id="bank_account_text" value="<?= $bank_account_text; ?>">
				
            </div>

<?php
		$party_bank_seq  ='';
		$sql = "TRUNCATE TABLE rtgs_temp";
		mysqli_query($con, $sql);
		
		$sql   = "SELECT * from payment_header where 1 and status = 'Completed' and del !='Y'  "; //and utr_no = '' and cheque_no = ''
		$sql   .= " AND company_id = '$company_id' AND dated >= '$start_date' AND dated <= '$end_date' AND cash_bank_name = '$bank_id' ";

	if(!empty($vendor_id)){
			$sql .= " AND paid_to = '$vendor_id' ";
	}

//echo $sql. "<BR>";
		
		$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
		$paid_to = $row['paid_to'];
		$st_flag = $row['st_flag'];
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
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$rid = $row['id'];
		
		$sql = "SELECT a.supplier_invoice_no, a.supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.del !='Y' and payment_hdr_id = '$rid'  ";
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$supplier_invoice_no  = $r2['supplier_invoice_no'];
		$supp_id			  = $r2['supp_id'];
		
		$dated_v 			= $row['dated'];
		$paid_date_v 		= $row['paid_date'];
		$total_amount_paid 	= $row['total_amount_paid'];
		
		$sql = " SELECT * from rtgs_temp where paid_to = '$paid_to' and st_flag = '$st_flag'";
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowaffect = mysqli_affected_rows($con);
//if($supp_id=='658'){		
//echo $sql.' ' .$rowaffect. "<BR>";//
//}
		if($rowaffect>0){
			$sql="UPDATE `rtgs_temp` set total_amount_paid = total_amount_paid + $total_amount_paid , supp_id = concat(supp_id, ',', '$supp_id') where paid_to = '$paid_to' and st_flag = '$st_flag' ";
//echo $sql."<>";			
			mysqli_query($con, $sql);
	//		$rowaffect = mysqli_affected_rows($con);
	//		echo $sql.' ' .$rowaffect. "<BR>";
			echo mysqli_error($con);
			//exit();
		}
		else {
			$sql = "INSERT INTO `rtgs_temp` (py_id, paid_date, cash_bank_name, paid_to, party_name, party_bank, party_bank_seq, dated, supplier_invoice_no, supp_id, st_flag, total_amount_paid, selected) 
				VALUES ('$rid', '$paid_date_v', '$cash_bank_name', '$paid_to', '$party_name', '$party_bank', '$party_bank_seq', '$dated_v', '$supplier_invoice_no', '$supp_id', '$st_flag', '$total_amount_paid', 'Y' )";
			mysqli_query($con, $sql);
		}
		
	}
?>

			
            <div class="box-header">
					
					<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>SrNo.</th>
			<th>Paid Date</th>
			<th>Paid via</th>
			<th>Paid To</th>
			<th>Dated.</th>
			<th>Supp.No.</td>
			<th>Inv Sr.No.</td>
			<th style="text-align:right;">Amount Paid</th>
		    <th>Select</td>
		</tr>
	</thead>
	<tbody>
<?php

	$sql   = "SELECT * from rtgs_temp where 1 ";
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$cash_bank_name = $row['cash_bank_name'];

		$paid_to 		= $row['paid_to'];
		$st_flag 		= $row['st_flag'];
		$party_bank  	= $row['bank_name'];
		$party_name		= ucwords(strtolower($row['party_name']));

		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$supplier_invoice_no  = $row['supplier_invoice_no'];
		$supp_id			  = $row['supp_id'];
		$total_amount_paid 	= $row['total_amount_paid'];
		
		$ij = 0;
		$baseurl_si = "#";
		if($st_flag=='SI'){
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
		}
		else if($st_flag=='OE'){
			$baseurl_si = $baseurl . "travel_approval/company_expense.php?sub=edit&id=$supp_id";
		}
		else if($st_flag=='TE'){
			$sql = "SELECT * FROM `sma_travel_expenses` where id in ($supp_id) ";
			$re2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($re2)){
				
				$exp_type 	= $r2['exp_type'];
				$exp_id 	= $r2['id'];
				$ij = $ij +1;
				$baseurl_si = "";
				if($exp_type=='T'){
					$supp_id = $exp_id;
					if($ij>1){
						$supp_id .="BR";
					}
					$baseurl_si = $baseurl . "travel_approval/travel_expence.php?sub=edit&id=$exp_id" ;
				}
				else if($exp_type=='R'){
					
					
					if($ij>1){
						$supp_id .="<BR>".$exp_id;
					}	
					else {
						$supp_id = $exp_id;
					}
					$baseurl_si = $baseurl . "travel_approval/regular_expense.php?sub=edit&id=$exp_id";
				}
			}	
			
		}
		else if($st_flag=='SA'){
			$baseurl_si = $baseurl . "purchase_order/edit.php?sub=edit&id=$supp_id";
		}
	
	?>
	
	<tr>
		<td width="2%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%" ><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="12%" ><?php echo $cash_bank_name;?></td>
		<td width="12%"><?php echo $party_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $supplier_invoice_no;?></td>
		<td width="8%"><a href="<?= $baseurl_si;?>" target="_blank" ><?php echo $supp_id . '-' . $st_flag;?></a></td>
		<td width="10%" style="text-align:right;"><?= round($total_amount_paid,0);?></td>
		<td width="2%" ><input type="checkbox" id = "py_id<?= $row['id'];?>" checked value="<?= $row['py_id'];?>" onchange="getchecked(this.value)" ></td>
    </tr>
	</a>
	
	<?php }
?>
</tbody> 
</table>


	<div class=" pull-right col-xs-5">
		<span id="getapprover">
			
			<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send" >
		</span>
	</div>
	
	
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
	$vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	$bank_id	= $_POST['cash_bank_name'];
	
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	$tableName		= "payment_header";
	$sql 	= "SELECT distinct(b.comp_code) as comp_code FROM `payment_header` a, company b where a.company_id = b.comp_id and a.company_id = '$company_id' ";
//echo $sql. "<BR>";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
	}
	
//	echo $bank_id . ' ' . $comp_code. ' ' . $bugdet_name; // 104 - BETPL
//	exit();

	$message ='';

	$tableName		= "payment_header";
	$sql 	= " SELECT * FROM $tableName where 1  and status = 'Completed' and company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' and cash_bank_name = '$bank_id' ";

	if(!empty($vendor_id)){
			$sql .= " and paid_to = '$vendor_id' ";
	}

//echo $sql ."<BR>";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$pur_req_no				= $row['id'];
		$id						= $row['id'];
		$paid_date  			= date('d-m-Y', strtotime($row['paid_date']));
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$paid_to				= $row['paid_to'];
		$cash_bank_name			= $row['cash_bank_name'];
		$total_amount_paid		= round($row['total_amount_paid'],0);
		$tds_amount				= $row['tds_amount'];
		$cheque_no				= $row['cheque_no'];
		$utr_no					= $row['utr_no'];
		$remarks				= $row['remarks'];
		$rtgs_narration			= $row['rtgs_narration'];
		$maker_date				= date('d-m-Y h:m i', strtotime($row['draft_dated']));
		$st_flag				= $row['st_flag'];
		
		$approval_status		= $row['approval_status'];
		$status					= $row['status'];

//		$message .= "<table  style='width: 95%; text-align: center; ' >
//				<tr><td><img src='img/".$img_flname_hdr."' style='margin-right:10%;height:15%;width: 100%;' ></td>
//				</tr></table>";
		//$message .= "<BR>";	
		
		$message .=  "<p>&nbsp;</p>";
		$message .=  "<p>&nbsp;</p>";
		$message .=  "<p>&nbsp;</p>";
		
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:40px;'><tr><td style='width: 100%;'> APPLICATION FORM FOR RTGS / NEFT PAYMENT</td></tr></table>";
		
		/* $sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'A' and b.account_type = 'D' and 
				( a.account_id = b.id || a.account_name = b.account_name ) and a.doc_type in ('PY') and a.effect = 'Cr' and a.doc_no = '$id' ";
		*/		
		$sql = "SELECT * FROM `tally_journal_entry` a, account_mst b where a.account_id = b.id and b.account_type = 'B' and a.effect = 'Cr' and a.doc_type in ('PY') and doc_no = '$id' ";		
	//	echo $sql;
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$actual_amount      = $res2['amount'];
			$deduction_head1    = $res2['account_name'];
			$total_amount_paid  = round($actual_amount,0);
		}
							
		$draft_mode = '';
		/* if($approval_status!='Approved'){
			$font_size = 72;
			$draft_mode = 'Draft';
			
			if( ($comp_code=='GEPL' || $comp_code=='BETPL' || $comp_code=='DBCPL' ) && $status == 'Verified' ){
				//Verified
				$draft_mode = '';
			}
			
		}
		else if(!empty($utr_no)){
			$font_size = 36;	
			$draft_mode = $utr_no;
			
		} */
		
//echo $approval_status. ' ' .$comp_code. ' '. $draft_mode;
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

		if( $st_flag== 'S' || $st_flag == 'D'  || $st_flag == 'C' || $st_flag == 'R'){
			$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
			$res 	= mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			$s1 	= mysqli_fetch_array($res);
			
			//$party_name  	 		 = $s1['party_name'];
			
			$party_name				 = $s1['party_beneficiary_name'];
			$party_bank_name 		 = $s1['party_bank_name'];
			$party_bank_account_type = $s1['party_bank_account_type'];
			$party_bank_address  	 = $s1['party_bank_address'];
			$party_bank_account_no 	 = $s1['party_bank_account_no'];
			$party_bank_ifsc_code  	 = $s1['party_bank_ifsc_code'];
		
		}
		else if( $st_flag== 'A' || $st_flag == 'T'){
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

	
	$y4 = date("Y");
	$y2 = date("y")+1;
	
	$mthchk = date("m");
	if($mthchk <= 3 ){
		$y4 = $y4 - 1; 
		$y2 = date("y");
	}
	
	/* if(!empty($draft_mode)){
		if(!empty($utr_no)){
			$zindex = '0';
			$message .= "<p style='text-align: Center;color:#FF3383;font-size:30;'>$draft_mode </p>";
			
		}
		else {$zindex = '-50';
	
			$message .= "<p style='width: 95%;text-align: Center;color:#c9d6d6;font-size:".$font_size.";position: absolute;	left: 0px;top:0px;z-index1:".$zindex.";'>$draft_mode </p>";
			
		}
	}
	 */
	
	//$message .=  "<br>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'><tr><td style='width: 95%;'> Ref: $comp_code/P2P/".$y4.'-'.$y2."/0". $id. "</td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:40px;'><tr><td style='width: 95%;'> Date: $paid_date</td></tr></table>";

	//$message .='<p>&nbsp;</p>';
	
	$message .=  "<span  style='margin-left:40px;'>To,</span><br>";
	//$message .=  "<span style='margin-left:40px;'>$account_name,</span><br>"; PHP_EOL
	
	$list = explode(PHP_EOL, $address);
//	print_r($list);
	$message .=  "<span style='margin-left:40px;'>".$list[0].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[1].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[2].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[3].','. "</span><br>";
	$message .=  "<span style='margin-left:40px;'>".$list[4].','. $list[5]. "</span><br>";
//	$message .=  "<span style='margin-left:40px;'>". "</span><br><br>";
//	$message .=  "<span style='margin-left:40px;'>Branch : $branch</span>";
	$message .=  "<span style='margin-left:40px;'> $email  $mobile </span>";
		
	$message .=  "<p style='margin-left:40px;'> Ref.:Account Number: $comp_name / $account_number </p>";
	
	$message .=  "<p style='margin-left:40px;'> Subject : Request to send  RTGS/NEFT of Rs.".moneyFormatIndia($total_amount_paid)."/-</p>";
	
	$message .=  "<p style='margin-left:40px;'> Dear Sir, </p>";
	
	$message .=  "<p style='margin-left:40px;'> With reference to the above, we request you to send RTGS/NEFT as per following details as under:- </p>";
	
	//$message .=  "<br>";
	
	$amt_word=numbertowordabc($total_amount_paid);
	
	if(!empty($cheque_no)){
		
		$cheque_no = $cheque_no. ' / ' . $paid_date;
	}
	
	$message .= "<table border='.5' cellspacing='-1' style='width: 95%; text-align: center; margin-left:40px;font-size: 12px;' >
			<tr>
				<td style='width: 40%;text-align: left;'> Amount </td>
				<td style='width: 40%;text-align: left;'> Rs." . moneyFormatIndia($total_amount_paid)."/-</td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Amount in words:- </td>
				<td style='width: 40%;text-align: left;'> Rupees " . $amt_word . " Only </td>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Cheque No & Date : </td>
				<th style='width: 40%;text-align: left;'> " .$cheque_no . " </th>
			</tr>
			<tr>
				<td style='width: 40%;text-align: left;'> Beneficiary Bank Name& Address : </td>
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
				<td style='width: 40%;text-align: left;'> Narration </td>
				<td style='width: 40%;text-align: left;'> " . $rtgs_narration . " </td>
			</tr>
			
			</table>";
	
	$message .=  "<p style='margin-left:30px;'> Kindly acknowledge the receipt of this letter and do the needful.</p>";
	
	$message .=  "<p style='margin-left:30px;'> Thanking you</p>";
	$message .=  "<p style='margin-left:30px;'> For $comp_name,</p>";
	$message .=  "<p>&nbsp;</p>";
	$message .=  "<p style='margin-left:30px;'> Authorised Signatories.</p>";
		
	//$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:40px;font-size: 10pt;'>
	//		<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";			
	
	//$message .= "<table><td style='text-align:center;'><img src='img/".$img_flname_ftr."' width='90%' height='90%' ></td> </table>";
	
//	$message .= "<table  style='width: 95%; text-align: center; ' >
//				<tr><td><img src='img/".$img_flname_ftr."' style='margin-right:10%;height:10%;width: 100%;' ></td>
//				</tr></table>";
	//$message .= "<BR>";		
	
//Page Break	
		$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
//Page Break	
	
}
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
	
	if($prn=='view'){
				  
		print $message;
		exit();
			
	}
	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'aipl_neft_'.$bank_id. '.pdf';
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
   function numbertowordabc($num){
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
		var instrument_no  	= document.getElementById("instrument_no").value;
		
		
		var approver_1 	= $(".approver_1").val();
//		alert(sub + ' ' + company_id + ' ' + bank_id + ' ' + approver_1);
		var strURL = "rtgs_mail.php";
		$.post(strURL,{company_id:company_id,bank_id:bank_id,approver_1:approver_1,rtgs_dd:rtgs_dd,instrument_no:instrument_no,sub5:sub},function(result){
		      $('#getapprover').html(result);
			  alert('Email Send....');
		});
	}	
	
	
</script>


