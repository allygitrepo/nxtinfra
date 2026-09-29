<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Budget (Supplier Name) Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget (Supplier Name) Report </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="budget_supplier_report.php?sub=pdf" method="post">
                      
							<div class="form-group">
								<div class="col-md-2">
									<label class="control-label">Fin.Year *</label>
									<select class="form-control" name="account_year" id="account_year" required >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_financial_year order by short_fy_code desc";
												$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['short_fy_code'];?>" ><?php echo $r2['short_fy_code'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>	
								
							<div class="form-group">
								<div class="col-sm-2">
									<label class="control-label">From Date *</label>
									<div class="input-group date" data-provide="datepicker"  data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" required id="from_date" name="from_date" value="" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
								<div class="col-sm-2">
									<label class="control-label">To Date *</label>
									<div class="input-group date" data-provide="datepicker"  data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" required id="to_date" name="to_date" value="" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-4">
									<label for="Company" class="control-label">Company *</label>
                                	<select class="form-control" name="company_id" required id="companY" >
                             		<option value=""> Select </option>
									<option value="" selected > All </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-md-5">
									<label class=" control-label">CC Group *</label>
								  <span id ="getccname">	
									<select class="form-control" required name="budget_name" id="budget_name" onchange="getccgroup(this.value);" >
										<option value=""> Select </option>
										<option value="" > All </option>
											<?php $sql = "select * from sma_budget_name where 1 and name !='' order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
								  </span>	
								</div>
							
								<div class="col-md-5">
									<label class="control-label">CC Sub Group *</label>
								<span id="getccgroup" >	
									<select class="form-control" required name="budget_head" id="budget_head" >
										<option value=""> Select </option>
	
										</select>
									</span>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
                                	<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
									<a href="../dashboard_athang.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
 ?>
 

<?php
if($_GET['sub'] == 'pdf'){

	session_start();
	
	include "../dbcon.php";
	include "../baseurl.php";

	
	$budget_head_prev ='';
	$project_prev ='';
	
	$comid  = $_SESSION['comid'];

	$prn			= "excel";

	$from_date		= date('Y-m-d', strtotime($_POST['from_date']));	
	$to_date		= date('Y-m-d', strtotime($_POST['to_date']));
	$company_id 	= $_POST['company_id'];
	$account_year	= $_POST['account_year'];
	$budget_head	= $_POST['budget_head'];
	$budget_name	= $_POST['budget_name'];
	
	$heada = ' Period From '.$_POST['from_date']. ' To '. $_POST['to_date'];
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> Budget (Supplier Name) Report ".$heada." </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt; background-color: skyblue;'>
				<tr><td style='width: 10%;'> Company </td>
					<td style='width: 10%;'> Cost Center Group </td>
					<td style='width: 10%;text-align: left;'> Budget Group Head</td>
					<td style='width: 10%;text-align: left;'> Vendor Name </td>
					<td style='width: 10%;text-align: left;'> Subject / Remarks </td>
					<td style='width: 10%;text-align: left;'> PO Number </td>
					<td style='width: 10%;text-align: left;'> PO Date </td>
					<td style='width: 10%;text-align: left;'> PO Amount </td>
					<td style='width: 10%;text-align: left;'> Board Approved </td>	
					<td style='width: 10%;'> Additional Board Approved </td>
					<td style='width: 10%;'> Inter Head Transfer </td>
					<td style='width: 10%;text-align: left;'> Inter Head Transfer %</td>
					<td style='width: 10%;'>Total Available Budget </td>
					<td style='width: 10%;text-align: left;'> Material Name </td>
					<td style='width: 10%;text-align: left;'> Invoice Number </td>
					<td style='width: 10%;text-align: left;'> Invoice Date </td>
					<td style='width: 10%;'>Actual Expenses </td>
					<td style='width: 10%;'>Budget Balance</td>
					<td style='width: 10%;'>PO Balance</td>
					
				</tr></table>";

	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
	
	$sqla = "";
	if(!empty($company_id)){
		$sqla = " AND project = '$company_id' ";
	}
	if(!empty($budget_name)){
		$sqla .= " AND budget_name = '$budget_name' ";
	}
	if(!empty($budget_head)){
		$sqla .= " AND budget_head = '$budget_head' ";
	}
	
	$sql 	= " SELECT * FROM sma_budget where 1 and account_year = '$account_year' and project in ($comid) $sqla order by project, budget_name ";
//echo $sql; exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$budget_id	 	= $row['id'];
		$project 		= $row['project'];
		$sql = "SELECT * from company where comp_id = '$project' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		
		$project 				= $r2['comp_name'];
		$budget_control_gst 	= $r2['budget_control_gst'];
		
		$budget_code 			= $row['budget_code'];		
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_head'];
		$board_approved_budget	= $row['board_approved_budget'];
		
		$account_year			= $row['account_year'];
		
		$total_budget			= $row['total_budget'];
		$used_budget			= $row['used_budget'];
		$blocked_budget			= $row['blocked_budget'];
		$adjustment_budget		= $row['adjustment_budget'];
		
		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$budget_name	= $rw['name'];
		
		$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_head 		= $r2['budget_head'];
			
		$sql = " SELECT sum(amount) as budget_adjustment FROM `budget_adjust` where budget_id = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_adjustment 		= $r2['budget_adjustment'];
		
		$sql = " SELECT sum(amount) as budget_transfer_from FROM `budget_adjust_from_to` where budget_id_from = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_transfer_from 		= ( $r2['budget_transfer_from'] * -1 );
		
		$sql = " SELECT sum(amount) as budget_transfer_to FROM `budget_adjust_from_to` where budget_id_to = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_transfer_to 		= $r2['budget_transfer_to'];
		
		$transfer_budget = 0;
		if($budget_transfer_to !=0){
			$transfer_budget			= $budget_transfer_to;
		}
		else if($budget_transfer_from !=0){
			$transfer_budget			= $budget_transfer_from;
		}
		
		$total_available_budget = $total_budget + $budget_adjustment + $transfer_budget;
		
		$factor	 = '';
		
		if( $transfer_budget!=0 && $total_budget!=0 ){
			$factor = round( ( ($transfer_budget / $total_budget ) * 100 ) ,2);
		}
		
		//$balance_budget	 = $total_available_budget - $used_budget;
		
		
		if( ($budget_head_prev != $budget_name || $project_prev != $project ) && !empty($budget_head_prev) && !empty($project_prev) ){
			
			$balance_budget_gtot = $total_available_budget_gtot - $used_budget_gtot;
	
			$balance_budget_ftot 	= $balance_budget_ftot + $balance_budget_gtot;
			
			$message .= "<tr style='background-color: #D3D3D3; font-weight: bold;'>
					<td>".$project_prev."</td>
					<td>".$budget_name_prev."</td>
					<td>".$budget_head_prev."</td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget_gtot)."</td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($po_amount_gtot)."</td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget_gtot) ."</td>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget_gtot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget_gtot)."</td>
					<td></td>
					</tr>";

			$total_budget_gtot 		= 0;
			$total_available_budget_gtot = 0;
			$used_budget_gtot 		= 0;
			$balance_budget_gtot 	= 0;
			$po_amount_gtot			= 0;
			
		}
		
		$total_budget_gtot 		= $total_budget_gtot + $total_budget;
		$total_available_budget_gtot = $total_available_budget_gtot + $total_available_budget;
		
		//$balance_budget_gtot 	= $balance_budget_gtot + $balance_budget;
		
		$total_budget_ftot 		= $total_budget_ftot + $total_budget;
		$total_available_budget_ftot = $total_available_budget_ftot + $total_available_budget;
		
		//$balance_budget_ftot 	= $balance_budget_ftot + $balance_budget;
		
		$message .= "<tr>
					<td>".$project."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head ."</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($budget_adjustment) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($transfer_budget) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($factor )." </td>
					
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget) ."</td>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td></td>
					</tr>";
			
			$budget_head_prev 	= $budget_head;
			$budget_name_prev	= $budget_name;
			$project_prev 		= $project;
			
			$po_number			= '';
			$po_dated			= '';
		$sql = "SELECT a.id as po_id, a.po_number, a.dated, a.subject, a.to_supplier, 
				sum((b.quantity * b.unit_rate) + (((b.quantity * b.unit_rate) * b.gst) / 100) ) as po_amount ,
					sum(((b.quantity * b.unit_rate) * b.gst) / 100) as gst_value,
					c.party_name, a.status as po_status, a.id as 'purchase_id'
					FROM sma_purchase_order a, sma_po_items b , sma_party_mst c
					WHERE 1 and a.id = b.purchase_id AND a.del !='Y' AND a.status != 'Rejected'
					AND a.to_supplier = c.id
					AND b.budget_id = '$budget_id'
					AND a.dated >= '$from_date' AND a.dated <= '$to_date'
					GROUP BY b.purchase_id ";
		$res2 = mysqli_query($con, $sql);
		while($r3  = mysqli_fetch_array($res2)){
			
			$po_id 			= $r3['po_id'];
			$po_number 		= $r3['po_number']. ' ID:'.$po_id;
			$po_dated 		= date('d-m-Y', strtotime($r3['dated']));
			$po_status 		= $r3['po_status'];
			$subject 		= $r3['subject'];
			$purchase_id 	= $r3['purchase_id'];
			$party_name		= $r3['party_name'];
			$po_amount 		= round($r3['po_amount'],0);
			$gst_value 		= round($r3['gst_value'],0);
			if($budget_control_gst=='N'){
				$po_amount 	= $po_amount - $gst_value;
			}
					
			$po_si_amount = 0;		
			if($po_status=='Closed'){
				$sql="SELECT qty, rate, gst , tds, sum((qty * rate) + (((qty * rate) * gst) / 100) ) as po_value,
						sum(((qty * rate) * gst) / 100) as gst_value , sum(credit_note_value) as credit_note_value
						FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b 
						WHERE 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$purchase_id' 
						AND a.created_date >= '$from_date' AND a.created_date <= '$to_date' 
						AND b.budget_id = '$budget_id' ";
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r1 = mysqli_fetch_array($res1)){
					
					$credit_note_value 	= round($r1['credit_note_value'],0);
					$po_si_amount 	= round($r1['po_value'],0) ;
					
					$gst_value 		= round($r1['gst_value'],0);
					
					if($budget_control_gst=='N'){
						$po_si_amount 	= $po_amount - $gst_value;
					}
					
				}
			}
		
			$po_amount_gtot		= $po_amount_gtot + $po_amount;
			$po_amount_ftot		= $po_amount_ftot + $po_amount;
			
			$po_balance =0;
			$supplier_invoice_no	='';
			$invoice_date			='';
			$used_budget			='';
			
			$sql="SELECT qty, rate, gst , tds, ((qty * rate) + (((qty * rate) * gst) / 100) ) as si_value , 
						(((qty * rate) * gst) / 100) as gst_value , credit_note_value ,
						a.invoice_date, a.supplier_invoice_no, b.material_name
					FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b 
					WHERE 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$purchase_id' 
						AND a.created_date >= '$from_date' AND a.created_date <= '$to_date' 
						AND b.budget_id = '$budget_id' ";
			$res1 = mysqli_query($con, $sql);
			$sicnt = mysqli_affected_rows($con);
			
			if($sicnt==0 && $po_status =='Closed'){
				$po_balance 	 = $po_amount - $po_si_amount;
				//$po_balance		= $po_balance *-1;
				$balance_po_gtot = $balance_po_gtot + $po_balance;
			}
			else if($sicnt==0){
				$po_balance 	 = $po_amount;
				$balance_po_gtot = $balance_po_gtot + $po_balance;
			}
		

					
			$message .= "<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>".$party_name."</td>
							<td>".$subject."</td>
							<td style='text-align: left;'>".$po_number." </td>
							<td style='text-align: left;'>".$po_dated." </td>
							<td style='text-align: right;'>".moneyFormatIndia($po_amount)." </td>
							<td></td>
							<td></td>
							
							<td></td>
							<td></td>
							<td></td>
							<td style='text-align: left;'>".$supplier_invoice_no." </td>
							<td style='text-align: left;'>".$invoice_date." </td>
							<td style='text-align: right;'>".moneyFormatIndia($used_budget)." </td>
							<td></td>
							<td></td>
							<td style='text-align: right;'>".number_format($po_balance)." </td>
							<td></td>
					</tr>";

//Supplier Invoice					
			$po_balance =0;
			$po_bal		=0;
			$jj = 0;
			$sql="SELECT qty, rate, gst , tds, ((qty * rate) + (((qty * rate) * gst) / 100) ) as si_value , 
						(((qty * rate) * gst) / 100) as gst_value , credit_note_value ,
						a.invoice_date, a.supplier_invoice_no, b.material_name
					FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b 
					WHERE 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$purchase_id' 
						AND a.created_date >= '$from_date' AND a.created_date <= '$to_date' 
						AND b.budget_id = '$budget_id' ";
			$res1 = mysqli_query($con, $sql);
			$sicnt = mysqli_affected_rows($con);
			echo mysqli_error($con);
			while($r1 = mysqli_fetch_array($res1)){
				$credit_note_value 	= round($r1['credit_note_value'],0);
				$used_budget 		= round($r1['si_value'],0);
				$used_budget 		= $used_budget - $credit_note_value;
				$gst_value	 		= round($r1['gst_value'],0);
				
				if($budget_control_gst=='N'){
					$used_budget 	= $used_budget - $gst_value;
				}
					
				$used_budget_ftot 	= $used_budget_ftot + $used_budget;
				$used_budget_gtot 	= $used_budget_gtot + $used_budget;
				$invoice_date 			= date('d-m-Y', strtotime($r1['invoice_date']));
				$supplier_invoice_no	= $r1['supplier_invoice_no'];
				$material_name			= $r1['material_name'];
				$po_bal					= $po_bal + $used_budget;
				$po_balance 			= $po_amount - $po_bal;
				
				
				$jj = $jj + 1;
				$message .= "<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>".$party_name."</td>
							<td>".$subject."</td>
							<td style='text-align: left;'>".$po_number." </td>
							<td style='text-align: left;'>".$po_dated." </td>
							<td style='text-align: right;'></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style='text-align: left;'>".$material_name." </td>
							<td style='text-align: left;'>".$supplier_invoice_no." </td>
							<td style='text-align: left;'>".$invoice_date." </td>
							<td style='text-align: right;'>".moneyFormatIndia($used_budget)." </td>
							<td></td>";
						if($sicnt==$jj && $po_status !='Closed'){
							$message .= "<td style='text-align: right;'>".number_format($po_balance)." </td> ";
							//$message .= "<td style='text-align: right;'>".number_format($po_bal)." ddd</td> ";
							$message .= "<td></td> ";
							$balance_po_gtot = $balance_po_gtot + $po_bal;
						}
						else {
							$message .= "<td></td> ";
						}	
				$message .= "</tr>";
			}
				
			if($po_status =='Closed'){
					$po_balance = $po_balance + $credit_note_value;
					
					$message .= "<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>Closed Amount</td>
							
							<td></td>";
							$message .= "<td style='text-align: right;'>".number_format($po_balance)." </td> ";
							//$balance_po_gtot = $balance_po_gtot + $po_balance;
					$message .= "<td></td>
								<td></td>
								</tr>";
				}
				
		}
		
//Travel Expense 		
$subject='';
		$sql = "SELECT a.onbehalf_emp_id, a.emp_id, a.exp_type, a.company_id, a.dated, b.dated as 'invoice_date', b.invoice_no, 
					sum(b.amount) as amount, b.reference 
					FROM `sma_travel_expenses` a , sma_expenses b 
					WHERE a.id = b.approval_ref_no and a.del !='Y' AND a.approval_status !='Rejected'
					AND b.budget_id = '$budget_id'
					AND a.dated >= '$from_date' AND a.dated <= '$to_date'
					group BY a.dated, a.emp_id, a.onbehalf_emp_id, a.company_id, a.exp_type, `b`.`reference` ASC; ";
//echo $sql. "<BR>";					
		$res2 = mysqli_query($con, $sql);
		while($r3  = mysqli_fetch_array($res2)){
			
			$onbehalf_emp_id 	= $r3['onbehalf_emp_id'];
			$exp_type 			= $r3['exp_type'];
			$company_id 		= $r3['company_id'];
			$invoice_no 		= $r3['invoice_no'];
			$reference 			= $r3['reference'];
			$used_budget		= $r3['amount'];
			$dated		 		= date('d-m-Y', strtotime($r3['dated']));
			$invoice_date		 = date('d-m-Y', strtotime($r3['invoice_date']));
			
			if($invoice_date == '01-01-1970' || $invoice_date == '31-12-1969' || $invoice_date =='30-11--0001'){
				$invoice_date='';
			}
			
			$sql="SELECT * from sma_user where id = '$onbehalf_emp_id'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$party_name = $r2['username'];
			
			if($exp_type=='R'){
				$po_number = 'Reguler Exp.';
			}
			else if($exp_type=='T'){
				$po_number = 'Travel Exp.';
			}
			else if($exp_type=='C'){
				$po_number 			= 'Operating Exp.';
				$onbehalf_emp_id 	= $r3['emp_id'];
				$sql="SELECT * from sma_party_mst where id = '$onbehalf_emp_id'";
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$party_name = $r2['party_name'];
			}
			$sql="SELECT * from sma_product where id = '$reference'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$material_name = $r2['name'];
										
			$sql="SELECT * from company where comp_id = '$company_id'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$comp_code = $r2['comp_code'];
			
			$used_budget_gtot = $used_budget_gtot + $used_budget;
			
			$message .= "<tr>
							<td>$comp_code</td>
							<td></td>
							<td></td>
							<td>".$party_name."</td>
							<td>".$subject."</td>
							<td style='text-align: left;'>".$po_number." </td>
							<td style='text-align: left;'>".$dated." </td>
							<td style='text-align: right;'></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style='text-align: left;'>".$material_name." </td>
							<td style='text-align: left;'>".$invoice_no." </td>
							<td style='text-align: left;'>".$invoice_date." </td>
							<td style='text-align: right;'>".moneyFormatIndia($used_budget)." </td>
							<td></td>
							<td></td>";
						
				$message .= "</tr>";
				
		}	
		
	}
	
	$balance_budget_gtot = $total_available_budget_gtot - $used_budget_gtot;
	
	$balance_budget_ftot 	= $balance_budget_ftot + $balance_budget_gtot;
	
	$message .= "<tr style='background-color: #D3D3D3; font-weight: bold;'>
					<td>".$project_prev."</td>
					<td>".$budget_name_prev."</td>
					<td>".$budget_head_prev."</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($po_amount_gtot)." </td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget_gtot)."</td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget_gtot) ."</td>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget_gtot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget_gtot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_po_gtot)."</td>
					
					</tr>";
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'budget_supplier_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}

    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'budget_supplier_export.pdf';
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
?>



<?php

}

?>

	

<script>

	
	function getccgroup(id){

		var sub    		= 'sub11';
//alert(sub);		
		var company_id  = document.getElementById("companY").value;
		var account_year  = document.getElementById("account_year").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id + ' ' + account_year);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,account_year:account_year,company_id:company_id,sub11:sub},function(result){
		      $('#getccgroup').html(result);
		});
		
	}
	
</script>	

<?php

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
				$nums[1] = '0';
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
