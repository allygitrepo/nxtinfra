<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vendor Payment  Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Vendor Payment Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="vendor_payment_report.php?sub=pdf" method="post">
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
                                <div class="col-sm-6">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company" id="companY" >
                             		<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							</div>
							
							<div class="form-group">
							
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-md-2">
								<label class=" control-label">Vendor Type </label>
									
									<select class="form-control"  name="party_type" id="party_type" required="true" onchange="getvendor(this.value);" >
										<option value="0"> Select </option>
											<?php $sql = "select * from sma_type order by type ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_type'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
											<?php } ?>
									</select>
								</div>
								
                                <div class="col-sm-6">
									<label for="Company" class="control-label">Vendor</label>
								<span id="getvendor">	
                                	<select class="form-control" name="party_name" id="party_name" >
                             		<option value=""> Select </option>
									
									</select>		
								</span>	
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
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
							
							</div>
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
                                <div class="col-xs-3">
									<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								</div>
								
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
									<a href="../dashboard.php?sub=list" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");
?>

<script>

function getvendor(id){
		var sub    = 'sub22';
//alert(id + ' ' + sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			//alert(result);
			  $('#getvendor').html(result);
		});
	}

</script>

<?php
	
 }
 
 ?>
 
<?php
if($_GET['sub'] == 'pdf'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

	$user   	= $_SESSION['user'];
	$role		= $_SESSION['role']; //Maker
	$prn		= "excel";
	$party_name	= $_POST['party_name'];
	$company	= $_POST['company'];
	$party_type	= $_POST['party_type'];
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='13'> Vendor Payment Transaction List </th></tr></table>";		
																					
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='13'> For the period dated From ". date('d-m-Y', strtotime($_POST['start_date'])) . " To " . date('d-m-Y', strtotime($_POST['end_date'])) ." </th></tr></table>";	
			
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Company</b> </td>
					<td style='width: 15%;'><b>Vendor Name</b></td>
					<td style='width: 10%;'><b>Vendor Type</b></td>
					<td style='width: 10%;'><b>Date Booking</b></td>
					<td style='width: 15%;'><b>Invoice Date</b></td>
					<td style='width: 10%;'><b>Invoice Number</b></td>
					<td style='width: 15%;'><b>PO Number</b></td>
					<td style='width: 15%;'><b>PO Approval Date</b></td>
					<td style='width: 15%;'><b>Invoice Amount</b></td>
					
					<td style='width: 10%;'><b>Payment Created Date</b></td>
					<td style='width: 10%;'><b>Paid Date</b></td>
					<td style='width: 15%;'><b>Paid Amount</b></td>
					<td style='width: 15%;'><b>Dedcution</b></td>
					<td style='width: 10%;'><b>UTR No.</b></td>	
					<th>Days Taken</th>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id				= $_GET['id'];
	$comid = $_SESSION['comid'];	
	
	$sql = "SELECT a.id as id, a.st_flag, a.paid_date, a.dated, a.company_id, a.paid_to, a.cash_bank_name, a.cheque_no as cheque_no, a.utr_no as utr_no, a.total_amount_paid, a.tds_amount, b.supplier_invoice_no, b.invoice_date, b.deduction_head, b.deduction_amt, b.deduction_head1, b.deduction_amt1, b.actual_payment 
	FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and del !='Y' ";
	
	
	if(!empty($party_name)){
		$sql .= " AND a.paid_to = '$party_name' ";
	}
	if(!empty($company)){
		$sql .= " AND a.company_id = '$company' ";
	}
	if(!empty($start_date)){
		$sql .= " AND a.dated >= '$start_date' AND a.dated <= '$end_date' ";
	}
	
	
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$paid_date 				= date('d-m-Y', strtotime($row['paid_date']));
		$invoice_date 			= date('d-m-Y', strtotime($row['invoice_date']));
		$created_date 			= date('d-m-Y', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$payment_no	 			= $row['id'];
		$paid_to 				= $row['paid_to'];
		
		$sql = "SELECT * from sma_party_mst where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to 			= $r2['party_name'];
			$party_type_v			= $r2['party_type'];
			
		if(!empty($party_type) && $party_type != $party_type_v){
			continue;
		}
	
		$cash_bank_name 		= $row['cash_bank_name'];
		$cheque_no 				= $row['cheque_no'];
		$utr_no 				= $row['utr_no'];
		$total_amount_paid 		= $row['total_amount_paid'];
		$tds_amount 			= $row['tds_amount'];
		$st_flag 				= $row['st_flag'];
		
		$approval_status 		= $row['approval_status'];
		if($approval_status 	== 'Rejected' ){
			$approval_status 	= $row['status'];
		}	
		$del   = $row['del'];
		if($del =='Y'){
			$approval_status = 'Deleted';
		}
			
		$changed_by = $row['changed_by'];
				if(empty($changed_by)){
					$changed_by = $draft_by;
				}	
				
				$sql = "select * from sma_user where userid = '$changed_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$changed_by  = $r2['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$company_name = $r2['comp_name'];
		$company_name = $r2['comp_code'];

		$msme = '';
		if($st_flag=='S' || $st_flag =='C' || $st_flag =='D'){
			$sql = "SELECT * from sma_party_mst where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to 			= $r2['party_name'];
			$party_type			= $r2['party_type'];
			$party_msme_number 	= $r2['party_msme_number'];
			
			$sql = "select * from sma_type where id = '$party_type' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$msme		= $r2['type'];
											
			
		}
		else if($st_flag=='T' || $st_flag=='A'){
			$sql = "SELECT * from sma_user where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to = $r2['username'];
        }

		$sql = "SELECT * from account_mst where id = '$cash_bank_name' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$account_name = $r2['account_name'];
		
		//$doc_type		= 'PY';
		//supplier_invoice_no, invoice_date, deduction_head, deduction_amt, deduction_head1, deduction_amt1, actual_payment
		$sql = "SELECT * FROM payment_details where payment_hdr_id = '$payment_no' ";
//echo $sql;		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
	
			$supplier_invoice_no 	= $rw['supplier_invoice_no'];
			$supp_id			 	= $rw['supp_id'];
			$deduction_head 		= $rw['deduction_head'];
			$deduction_amt 			= $rw['deduction_amt'];
			$deduction_head1 		= $rw['deduction_head1'];
			$deduction_amt1 		= $rw['deduction_amt1'];
			$actual_payment 		= $rw['actual_payment'];
			$payment_adjusted 		= $rw['payment_adjusted'];
			
			$payment_type = '';
			if($st_flag =='S'){
				$payment_type = 'Supplier Invoice';	
			}
			else if($st_flag =='D'){
				$payment_type = 'SI Advance';	
			}
			else if($st_flag =='T'){
				$payment_type = 'Travel / Reimbursement';	
			}
			else if($st_flag =='C'){
				$payment_type = 'OpEx';	
			}
			else if($st_flag =='A'){
				$payment_type = 'Travel Advance';	
			}
			
			
			$po_number = '';
			$si_total_amount = 0;
			if($st_flag =='S' || $st_flag=='R' ){
				$sql = "SELECT * FROM sma_supplier_invoice a, sma_purchase_order b where a.our_po_ref_no = b.id and a.id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number 		= $r2['po_number'];
						$our_po_ref_no 	= $r2['our_po_ref_no'];
						$si_total_amount = $r2['total_amount'];
						$invoice_date	 = date('d-m-Y', strtotime($r2['invoice_date']));
						
				$sql = "SELECT * FROM  sma_purchase_order b where id = '$our_po_ref_no' ";		
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_number = $r2['po_number'];	

				$sql = "SELECT * FROM `workflow_history` where 1 and doc_id = '$our_po_ref_no' and doc_type = 'PO' and status = 'Approved'  order by id desc;  ";		
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_approval_date = date('d-m-Y', strtotime($r2['create_date']));
								
			}
			else if($st_flag =='D'){
				$sql = "SELECT * FROM  sma_purchase_order b where id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
				$sql = "SELECT * FROM `workflow_history` where 1 and doc_id = '$supp_id' and doc_type = 'PO' and status = 'Approved'  order by id desc;  ";		
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$po_approval_date = date('d-m-Y', strtotime($r2['create_date']));		
			}
			
			$bal_payment = $si_total_amount - $actual_payment;
			
			if($invoice_date=='01-01-1970'){
				$invoice_date='';
			}	
			
			$diff = abs(strtotime($paid_date) - strtotime($invoice_date));
			$no_days = round($diff / (60 * 60 * 24));
								
			$message .= "<tr>
							<td>$company_name</td>
							<td>$paid_to</td>
							<td>$msme</td>
							<td>$created_date</td>
							<td>$invoice_date</td>
							<td>$supplier_invoice_no</td>
							<td>$po_number</td>
							<td>$po_approval_date</td>
							<td style='text-align:right;'>$si_total_amount</td>
							<td>$created_date</td>
							<td>$paid_date</td>
							<td style='text-align:right;'>$total_amount_paid</td>
							<td style='text-align:right;'>$tds_amount</td>
							<td>$utr_no</td>
							<td>$no_days</td>
							
							";
						$message .= "</tr>";

		}

	}
	
	$message .= "</table>";

//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'vendor_payment_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}

   
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
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}
