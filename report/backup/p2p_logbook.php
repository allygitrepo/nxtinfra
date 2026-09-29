<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Workflow P2P Log report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow P2P Log report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					<?php
						$sql = "select * from company where comp_id in ($comid) order by comp_name ";
						$q2 	= mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$start_date		= date('d-m-Y', strtotime($r2['comp_start_date']));
						$end_date		= date('d-m-Y', strtotime($r2['comp_end_date']));
											
					?>
						<form class="form-horizontal" action="p2p_logbook.php?sub=pdf" method="post">
                      
							<div class="form-group">
								
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
				<?php //echo $sql = "select * from company where comp_id in ($comid) order by comp_name "; ?>
				
								<div class="col-sm-4">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company_id" id="companY"  >
                             		<option value=""> Select </option>
									<option value="" selected > All </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<div class="col-sm-4">
									<label for="Company" class="control-label">Supplier</label>
									<select class="form-control" name="supplier_id" id="supplier_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_party_mst order by party_name ";
											$q2 	  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($supplier_id == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
											<?php } ?>
									</select>
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
                                	<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
									<a href="dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
 
if($_GET['sub'] == 'pdf'){

	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

	$prn		= "excel";
	$from_date_p	= '01-09-2022';
	$to_date_p		= '30-09-2022';
	
	$company_id		 = $_POST['company_id'];
	$supplier_id_chk = $_POST['supplier_id'];
	$from_date_p  	= $_POST['start_date'];
	$to_date_p  	= $_POST['end_date'];
	$from_date		= date('Y-m-d', strtotime($from_date_p));
	$to_date		= date('Y-m-d', strtotime($to_date_p));
	
	$sql = "TRUNCATE p2p_logbook";
	mysqli_query($con,$sql);
	
	$tableName	= "sma_approval_memo";
	
	$sql 		= " SELECT * FROM $tableName where status = 'Completed' and del !='Y' and dated >= '$from_date' and dated <= '$to_date' ";
	if(!empty($company_id)){
		$sql .= "  and company = '$company_id' ";
	}
	
	
	
//echo $sql. "<BR>";	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$app_id					= $row['id'];
		//$dated  				= date('d-m-Y', strtotime($row['dated']));
		$dated  				= $row['dated'];
		$company_id				= $row['company'];
		$subject				= $row['subject'];
		$overhead_exp			= $row['overhead_exp'];
		$status					= $row['status'];

		$sql = "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 		= mysqli_query($con, $sql);
		$com 			= mysqli_fetch_array($comresult);
		$comp_name 		= $com['comp_name'];
		$comp_code 		= $com['comp_code'];
		
		$sql="SELECT sum(round((quantity * unit_rate) + ((quantity * unit_rate) * gst /100),2) ) as total_value  from sma_approval_items where approval_hdr_id = '$app_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$total_value 		= $r2['total_value'];
		
		$sql 	= " SELECT * FROM sma_approval_details where 1 and vendor_selected = 'Y' and approval_hdr_id = '$app_id' ";
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$supplier_id		= $rw['supplier_name'];
			if( $supplier_id != $supplier_id_chk && !empty($supplier_id_chk) ){
				continue;
			}
			$quote_ref_no		= $rw['quote_ref_no'];
			$vendor_selected	= $rw['vendor_selected'];
			$values				= $rw['values'];
			
		}
		
			$company_code 		= $comp_code;
			$supplier_id		= $supplier_id;
			$ap_id				= $app_id;
			$ap_date			= $dated;
			$subject			= $subject;
			$quote_value		= $values;
			
			$sql = "INSERT INTO p2p_logbook ( doc_type, company_code, supplier_id, ap_id, ap_date, subject, quote_value , status ) 
					VALUE ('Approval Memo', '$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$status' ) ";
			 mysqli_query($con,$sql);
			 echo mysqli_error($con);

//Approval Memo End
			$poaffected_rows = 0;
			
//Operating Expense Start
			if($overhead_exp=='Y'){
				$sql = "SELECT a.id as po_no, a.dated as po_date, round(sum(amount+gst_amount),2) as po_value, a.status FROM `sma_travel_expenses` a, sma_expenses b where a.id = b.approval_ref_no and a.del !='Y' and a.exp_type = 'C' and a.approval_number = '$app_id' group by a.id ";
				$poqry 			= mysqli_query($con,$sql);
				$poaffected_rows= mysqli_affected_rows($con);
				if($poaffected_rows>0){
					
					while($porows 		= mysqli_fetch_array($poqry)){
						
						$po_no  		= $porows['po_no'];
						$po_date  		= $porows['po_date'];
						$po_value  		= $porows['po_value'];
						$status			= $porows['status'];
						
						$po_id			= $po_no;
						$bal_ap_value	= round($quote_value - $po_value,2);
						$sql = "INSERT INTO p2p_logbook ( doc_type, company_code, supplier_id, ap_id, ap_date, subject, quote_value, po_id , po_date, po_value, bal_ap_value , status) 
						VALUE ('Operating Expense','$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$po_id', '$po_date', '$po_value', '$bal_ap_value' , '$status' ) ";
						mysqli_query($con,$sql);
						mysqli_error($con);
						
						
//Payment Start	for Operating Exp
							$sql = "SELECT a.id as py_no, a.paid_date , b.actual_payment, b.payment_adjusted as paid_amount , a.status FROM `payment_header` a, payment_details b WHERE 1 and a.st_flag = 'C' and a.del !='Y' and a.id = b.payment_hdr_id and b.supp_id = '$po_id' ";
							$pyqry 			= mysqli_query($con,$sql);
							$pyaffected_rows= mysqli_affected_rows($con);
							if($pyaffected_rows>0){
							
								$pyln = 0;
								while($pyrows 		= mysqli_fetch_array($pyqry)){
									$py_no  			= $pyrows['py_no'];
									$paid_date  		= $pyrows['paid_date'];
									$paid_amount  		= $pyrows['paid_amount'];
									$bal_amount			= $po_value - $paid_amount;
									$status				= $pyrows['status'];
									
									$py_id				= $py_no;
									$paid_value			= $paid_amount;
									$sql = "INSERT INTO p2p_logbook ( doc_type, company_code, supplier_id, ap_id, ap_date, subject, quote_value, po_id , po_date, po_value, bal_ap_value, si_id, si_date, si_value, py_id, paid_date, paid_value, balance , status ) 
									VALUE ('Payment Against OE', '$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$po_id', '$po_date', '$po_value', '$bal_ap_value', '$si_id', '$si_date', '$si_value', '$py_id', '$paid_date', '$paid_value', '$bal_amount', '$status' ) ";
									mysqli_query($con,$sql);
									mysqli_error($con);
									
								}
								
							}
								
//Payment End	for Operating Exp		

					}
			 
				}
			}
//Operating Expense End			
			
//Purchase Order Start		
		if($overhead_exp=='N'){
			$sql = "SELECT a.id as po_no, a.dated as po_date, b.purchase_id, round(sum((quantity*unit_rate) + ((quantity*unit_rate) * gst / 100) ),2) as po_value, status FROM `sma_purchase_order` a, sma_po_items b where a.id = b.purchase_id and a.del !='Y' and approval_memo_ref = '$app_id' group by a.id  ";
			$poqry 			= mysqli_query($con,$sql);
			$poaffected_rows= mysqli_affected_rows($con);
			if($poaffected_rows>0){
				
				$iln = 1;
				while($porows 		= mysqli_fetch_array($poqry)){
					$po_no  		= $porows['po_no'];
					$po_date  		= $porows['po_date'];
					$po_value  		= $porows['po_value'];
					$status			= $porows['status'];
					
					$po_id			= $po_no;
					$bal_ap_value	= round($quote_value - $po_value,2);
					$sql = "INSERT INTO p2p_logbook (doc_type, company_code, supplier_id, ap_id, ap_date, subject, quote_value, po_id , po_date, po_value, bal_ap_value, status ) 
					VALUE ('Purchase Order','$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$po_id', '$po_date', '$po_value', '$bal_ap_value', '$status' ) ";
					mysqli_query($con,$sql);
					mysqli_error($con);
			 
//Purchase Order End

//Supplier Invoice Start							
					$sql = "SELECT round(sum((b.qty*b.rate) + ((b.qty*b.rate) * b.gst / 100 )),2) as si_value, a.payable_amount, a.id as si_no, created_date as si_date, a.status FROM `sma_supplier_invoice` a , `sma_supplier_invoice_details` b where a.id = b.si_hdr_id and a.del !='Y' and a.our_po_ref_no = '$po_no' group by a.id ";
					$siqry 			= mysqli_query($con,$sql);
					$siaffected_rows= mysqli_affected_rows($con);
					if($siaffected_rows>0){
					
						$siln = 0;
						while($sirows 		= mysqli_fetch_array($siqry)){
							$si_no  		= $sirows['si_no'];
							$si_date  		= $sirows['si_date'];
							$si_value  		= $sirows['si_value'];
							$status  		= $sirows['status'];
							
							$si_id			= $si_no;
							$si_date		= $si_date;
							$si_value		= $si_value;
							$sql = "INSERT INTO p2p_logbook ( doc_type,company_code, supplier_id, ap_id, ap_date, subject, quote_value, po_id , po_date, po_value, bal_ap_value, si_id, si_date, si_value, status ) 
							VALUE ('Supllier Invoice','$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$po_id', '$po_date', '$po_value', '$bal_ap_value', '$si_id', '$si_date', '$si_value', '$status' ) ";
							mysqli_query($con,$sql);
							mysqli_error($con);
									
						}
//Supplier Invoice End


//Payment Start		
							$sql = "SELECT a.id as py_no, a.paid_date , b.actual_payment, b.payment_adjusted as paid_amount , a.status FROM `payment_header` a, payment_details b WHERE 1 and a.st_flag = 'S' and a.del !='Y' and a.id = b.payment_hdr_id and b.supp_id = '$si_no' ";
							$pyqry 			= mysqli_query($con,$sql);
							$pyaffected_rows= mysqli_affected_rows($con);
							if($pyaffected_rows>0){
							
								$pyln = 0;
								while($pyrows 		= mysqli_fetch_array($pyqry)){
									$py_no  			= $pyrows['py_no'];
									$paid_date  		= $pyrows['paid_date'];
									$paid_amount  		= $pyrows['paid_amount'];
									$status  			= $pyrows['status'];
									$bal_amount			= $si_value - $paid_amount;
									
									$py_id				= $py_no;
									$paid_value			= $paid_amount;
									$sql = "INSERT INTO p2p_logbook ( doc_type, company_code, supplier_id, ap_id, ap_date, subject, quote_value, po_id , po_date, po_value, bal_ap_value, si_id, si_date, si_value, py_id, paid_date, paid_value, balance, status ) 
									VALUE ('Payment Against SI', '$company_code', '$supplier_id', '$ap_id', '$ap_date', '$subject', '$quote_value', '$po_id', '$po_date', '$po_value', '$bal_ap_value', '$si_id', '$si_date', '$si_value', '$py_id', '$paid_date', '$paid_value', '$bal_amount' , '$status' ) ";
									mysqli_query($con,$sql);
									mysqli_error($con);
									
								}
								
							}
								
//Payment End					
							
						}	
					
					}
					
//Supplier Invoice	End				
						
				}
				
			}
			
	}
	
//exit();
	
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> Report Date period from ". $from_date_p. " TO ". $to_date_p . "</th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td colspan='2'> </td>
					<th colspan='4'>Approval Memo </th>
					<th colspan='5'>Order</th>
					<th colspan='3'>Supplier Invoice </th>
					<th colspan='6'>Payment</th>
					
				</tr></table>";
				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> Company </td>
					<td style='width: 08%;text-align: left;'> Supplier Name </td>
					<td style='width: 10%;'> Doc Type </td>
					
					<td style='width: 06%;text-align: left;'> AP.MEMO No.</td>
					<td style='width: 10%;text-align: left;'> AP Dated </td>
					<td style='width: 25%;'> Subject</td>
					<td style='width: 08%;text-align: right;'> Quoted Amount </td>
					
					<td style='width: 10%;'> PO No. </td>
					<td style='width: 10%;'> PO Date </td>
					<td style='width: 10%;'> Total PO Amount. </td>
					<td style='width: 10%;'> Balance AP Amount. </td>
					
					<td style='width: 10%;'> SI No. </td>
					<td style='width: 10%;'> SI Date </td>
					<td style='width: 10%;'> Received Amount. </td>
					
					<td style='width: 10%;'> Payment No. </td>
					<td style='width: 10%;'> Paid Date </td>
					<td style='width: 10%;'> Paid Amount </td>
					<td style='width: 10%;'> Deduction </td>
					<td style='width: 10%;'> Balance </td>
					<td style='width: 10%;'> Status </td>
					
				</tr></table>";
				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;vertical-align: top;'>";
		
	$sql = "SELECt * FROM p2p_logbook WHERE 1 ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_name					= $row['company_code'];
		$supplier_id				= $row['supplier_id'];
		$ap_id						= $row['ap_id'];
		$ap_date					= date('d-m-Y', strtotime($row['ap_date']));
		$subject					= $row['subject'];
		$quote_value				= $row['quote_value'];
		$doc_type					= $row['doc_type'];
		$po_id						= $row['po_id'];
		$po_date					= date('d-m-Y', strtotime($row['po_date']));
		$po_value					= $row['po_value'];
		$bal_ap_value				= $row['bal_ap_value'];
		$si_id						= $row['si_id'];
		$si_date					= date('d-m-Y', strtotime($row['si_date']));
		$si_value					= $row['si_value'];
		$py_id						= $row['py_id'];
		$paid_date					= date('d-m-Y', strtotime($row['paid_date']));
		$paid_value					= $row['paid_value'];
		$deduction					= $row['deduction'];
		$balance					= $row['balance'];
		$status						= $row['status'];
		
		if($ap_date=='01-01-1970' || $ap_date=='30-11--0001'){
			$ap_date ='';	
		}
		if($po_date=='01-01-1970' || $po_date=='30-11--0001'){
			$po_date ='';	
		}
		if($si_date=='01-01-1970' || $si_date=='30-11--0001'){
			$si_date ='';	
		}
		if($paid_date=='01-01-1970' || $paid_date=='30-11--0001'){
			$paid_date ='';	
		}
		if($si_id==0){
			$si_id ='';
		}
		if($po_id==0){
			$po_id ='';
		}
		if($py_id==0){
			$py_id ='';
		}
		
		if($si_value==0){
			$si_value ='';
		}
		if($po_value==0){
			$po_value ='';
		}
		if($paid_value==0){
			$paid_value ='';
		}
		if($deduction==0){
			$deduction ='';
		}
		if($balance==0){
			$balance ='';
		}
		if($bal_ap_value==0){
			$bal_ap_value ='';
		}
		
		$sql = " SELECT * FROM `sma_party_mst` where id = '$supplier_id' ";
		$dep 			= mysqli_query($con,$sql);
		$deps 			= mysqli_fetch_array($dep);
		$supplier_name  = $deps['party_name'];
		
		$ap_id_v		= $ap_id;
		if($ap_id_prev == $ap_id){
			//$comp_name 			= '';
			//$supplier_name 		= '';
			$subject			= '';
			//$ap_id_v			= '';
			//$ap_date 			= '';
			$quote_value 		= '';
		}
		
		$po_id_v 		= $po_id;
		if($po_id_prev == $po_id){
			$po_id_v			= '';
			$po_date 			= '';
			$po_value	 		= '';
			//$bal_ap_value		= '';
		}
		
		$si_id_v 		= $si_id;
		if($si_id_prev == $si_id || $doc_type =='Operating Expense' || $doc_type == 'Payment Against OE'){
			$si_id_v			= '';
			$si_date 			= '';
			$si_value	 		= '';
			$bal_ap_value		= '';
		}
		
		$message .= "<tr>
					<td>".$comp_name."</td>
					<td style='width: 25%;'> " . $supplier_name . " </td>
					<td>".$doc_type ."</td>
					<td>".$ap_id_v ."</td>
					<td>".$ap_date."</td>
					<td>".$subject."</td>
					<td style='width: 10%;text-align: right;'> ".$quote_value." </td>
					<td>".$po_id_v ."</td>
					<td>".$po_date ."</td>
					<td style='width: 10%;text-align: right;'>".$po_value ."</td>
					<td style='width: 10%;text-align: right;'>".$bal_ap_value ."</td>
					<td>".$si_id_v ."</td>
					<td>".$si_date ."</td>
					<td style='width: 10%;text-align: right;'>".$si_value ."</td>
					<td>".$py_id ."</td>
					<td>".$paid_date ."</td>
					<td style='width: 10%;text-align: right;' >".$paid_value ."</td>
					<td style='width: 10%;text-align: right;'>".$deduction ."</td>
					<td style='width: 10%;text-align: right;'>".$balance ."</td>
					<td style='width: 10%;text-align: right;'>".$status ."</td>
				</tr>";
				
		$ap_id_prev = $ap_id;		
		$po_id_prev = $po_id;
		$si_id_prev = $si_id;
		
	}
	
	$message .= "</table>";
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	$fl_name = 'workflow_p2p_logbook'.'_'.date('d-m-Y h:i:sa').'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	
}
?>
