<?php
session_start(); 	

include("header.php");
include("dbcon.php");
$modulePath = "setting/useraccs_new.php?sub=list";

	$role		= $_SESSION['role'];
	$userid   	= $_SESSION['usrid'];
	$user   	= $_SESSION['user'];
	$comid      = $_SESSION['comid'];

?>
<div class="content-wrapper">

<section class="content">
			
      <div class="row">
		
        <div class="col-xs-12">
			<section class="content-header">
			<h1>
			Rejected List
			<small>
				<a href="<?php echo $help_link;?>" target="_blank" class="btn btn-info"><i class="fa fa-anchor"></i> Help</a>
			</small>
			
		  </h1>
		  </section>
		  <div class="box">
			
			
<!-- Approval Memo Pending -->			
			<div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_approval_memo where 1 and approval_status ='Rejected' and current_approver = '$userid' and project in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$ap_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0"><b>  Approval Memo</b> ( <span style="font-size:18px;"><?= $ap_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step0" class="panel-collapse collapse in123	">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
        <th>Sr.No.</th>
        <th>Dated</th>
		<th>Doc.Type</th>
		<th>Company</th>
		<th>Supplier Name</th>
		<th style="text-align:right;">Amount</th>
		<th>By</th>
		<th>Status</th>
		<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "approval/";
	$sql="SELECT * from sma_approval_memo where 1 and approval_status ='Rejected' and current_approver = '$userid' and project in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$rid = $row['id'];
		$overhead_exp	= $row['overhead_exp'];
		if($overhead_exp=='N'){
			$overhead_exp_type = 'For PO';
		}
		else if($overhead_exp=='Y'){
			$overhead_exp_type = 'For Operating Expense';
		}
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $row['department'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						$party_name ='';
						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name " ;
			
						$ij=0;
						$party_name  = '';
						$amount		 =0;
						$q2  = mysqli_query($con, $sql);
						$raffect = mysqli_affected_rows($con);
						while($r2 = mysqli_fetch_array($q2)){
							
							if($ij>0){$party_name.=', <BR> ' ;}
							
							$party_name  .= $r2['party_name'];
							$amount		 += $r2['values'];
							
							$ij = $ij + 1;
							
						}
						
						$ap_amend = $row['ap_amend'];
						$backcolor = '';
						if($ap_amend=='Y'){
							$backcolor = ' background-color: coral; ';
						}
						
						$styl  =  '';
						$styl2 = '';
						$del = $row['del'];
						if($del =='Y'){
							
							$styl = "style='bgcolor:powderblue;color:red;' ";
							$styl2 = "bgcolor:powderblue;color:red; ";
							
						}
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
		<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
			<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>
			<td width="4%" <?php echo $styl; ?> ><?php echo $row['id'];?></td>
			<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
			<td width="12%" <?php echo $styl; ?>><?php echo $overhead_exp_type;?></td>
			<td width="29%" <?php echo $styl; ?>><?php echo $company;?></td>
			<td width="20%" <?php echo $styl; ?>><?php echo $party_name;?></td>
			<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo $amount;?></td>
			<td width="10%" <?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
			<td width="08%" <?php echo $styl; ?>><?php echo $row['status'];?></td>
			<td width="08%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
		</tr>
	</a>	
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
  <!-- /.Approval Memo box-header -->
			
			<div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_purchase_order where 1 and approval_status='Rejected'  and project in ( $comid ) ";
				
				$result = mysqli_query($con,$sql);
				$po_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step1"><b>  Purchase Order</b> ( <span style="font-size:18px;"><?= $po_cnt;?></span> )<span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step1" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableA" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
        <th>PO.No.</th>
		<th>Dated</th>
		<th>Supplier</th>
		<th style="text-align:right;">Total</th>
		<th>By</th>
		
		<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "purchase_order/";
	$sql="SELECT * from sma_purchase_order where 1 and approval_status='Rejected'  and project in ( $comid ) order by id desc";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$approval_memo_ref = $row['approval_memo_ref'];;
		$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$dated = date('d-m-Y', strtotime($r2['dated']));
		if($dated=='01-01-1970'){$dated='';}
		$app_no_date = $approval_memo_ref. '/'.$dated;
		//$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
		
		$project = $row['project'];
		$sql 	= "select * from sma_project where id = '$project' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$project = $r2['name'];		
		
		
		$budget_name = $row['budget_name'];
		$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
		$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
				
		$budget_head = $row['budget_head'];
		$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_head = $r2['category'];
		
		$to_supplier = $row['to_supplier'];
		$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$to_supplier = $r2['party_name'];
	
		$purchase_id = $row['id'];
		$tot_amount = 0;
		$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
//echo $sql;		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			$qty 	= $r1['quantity'];
			$rate 	= $r1['unit_rate'];
			$gst	= $r1['gst'];
			$amount = $qty * $rate + ((($qty * $rate) * $gst) / 100);
			$tot_amount = $tot_amount + $amount;
		}										
		
								
			$rid = $row['id'];
			
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
			$po_rev = $row['po_rev'];
			$po_number = $row['po_number'];
			if($po_rev>0){
				$po_number .= '-'.$po_rev;
			}
			
			$po_amend = $row['po_amend'];
			$backcolor = '';
			if($po_amend=='Y'){
				$backcolor = ' background-color: coral; ';
			}
			
			$changed_by = $row['draft_by'];			
			$sql="SELECT * from sma_user where userid = '$changed_by' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$changed_by 	= $r1['username'];
			
			$po_rev 	= $row['po_rev'];
			$po_number 	= $row['po_number'];
			if($po_rev>0){
				$po_number .= $po_rev;
			}
			else {
				$po_number = $row['po_number'];
			}
			
			$approval_status = $row['approval_status'];
			
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="18%" <?php echo $styl; ?>><?php echo $po_number;?></td>
		<td width="08%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="19%" <?php echo $styl; ?>><?php echo $to_supplier;?></td>
		<td width="10%" style="text-align:right; <?php echo $styl2; ?>" ><?php echo $tot_amount;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
		
		<td width="15%" <?php echo $styl; ?>><?php echo $row['status'].'-'.$row['approval_status'];?></td>

    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->

<!-- Purchase Order End -->


<!-- Supplier Invoice Start -->			
			<div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_supplier_invoice where 1 and approval_status='Rejected' and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$si_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2"><b>  Supplier Invoice</b> ( <span style="font-size:18px;"><?= $si_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableB" class="table table-bordered table-striped">

            <thead>
    <tr>
		
        <th>Sr.No.</th>
			<th>Dated</th>
			<th>Supp.Inv.No.</th>
			<th style="text-align:right;">Amount</th>
			<th>Our PO Ref.NO.</th>
			<th>Supplier Name</th>
		    <th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "supp_invoice/";
	$sql="SELECT * from sma_supplier_invoice where 1 and approval_status='Rejected'  and company_id in ( $comid ) order by id desc";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$tally_status  = $row['tally_status'];
		if($tally_status=='R'){
			$tally_status_a = 'Ready for Tally Upload';
		}	
		else if($tally_status=='U'){
			$tally_status_a = 'Updated to Tally';
		}
		else {
			$tally_status_a = 'Unticked';	
		}
		
		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];

		$our_po_ref_no = $row['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$our_po_ref_no 	= $r1['po_number'];
		$po_rev			= $r1['po_rev'];
		if($po_rev>0){
			$our_po_ref_no 	= $our_po_ref_no .'-'.	$po_rev;
		}
		
		$changed_by = $row['draft_by'];			
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];
		
		$due_date = date('d-m-Y', strtotime($row['due_date']));
		if($due_date=='01-01-1970'){$due_date='';}
		
		$rid = $row['id'];
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<!--<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>-->
		<td width="3%" style="text-align:right;"><?php echo $row['id']?></td>
		<td width="11%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
		<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
		<td width="17%"><?php echo $supplier_name;?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		
		<td width="11%"><?php echo $tally_status_a;?></td>
		<td width="15%"><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!--Supplier Invoice End -->



<!--Company Expense Start -->						
    <div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='C' and approval_status ='Rejected' and company_id in ( $comid ) ";
				
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step5c"><b> Operating Expense</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step5c" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableC" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Date</th>
			<th>Approval Ref.no.</th>
			<th style="text-align:right;">Amount</th>
			<th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='C' and approval_status ='Rejected' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$tally_status  = $row['tally_status'];
		if(empty($tally_status)){
			$tally_status_a = 'JV Pending';
		}
		else if($tally_status=='R'){
			$tally_status_a = 'JV Created';
		}	
		else if($tally_status=='C'){
			$tally_status_a = 'JV Checked ';
		}
		else if($tally_status=='U'){
			$tally_status_a = 'JV Synched';	
		}
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['username'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
		$exp_amount = 0;
		$fare		 = 0;
		$approval_ref_no = $row['approval_ref_no'];	
		$id = $row['id'];	
		$sql="SELECT * from sma_departure where approval_ref_no = '$id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$fare += $d1['fare'];
		}			
					
		$sql="SELECT * from sma_expenses where exp_type = 'C' and approval_ref_no = '$id' ";
		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$exp_amount += $d1['amount'];
		}			
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 	= $baseurl.$modulePath.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($exp_amount);?></td>
		<td width="09%"><?php echo $row['changed_by'];?></td>
		<td width="09%"><?php echo $tally_status_a;?></td>
		<td width="15%"><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
		<!--<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>-->
    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->
<!--Company Expense End-->



<!-- Payment Start-->
<div class="panel panel-default">
		<?php
				$sql="SELECT * from payment_header where 1 and approval_status='Rejected' and company_id in ( $comid )";//
				$result = mysqli_query($con,$sql);
				$py_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2p"><b>  Payment</b> ( <span style="font-size:18px;"><?= $py_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2p" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
						<table id="prtableD" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>SrNo.</th>
			<th>Paid Date</th>
			<th>Paid via</th>
			<th>Paid To</th>
			<th>UTR.No.</th>
			<th>Dated.</th>
			<th>Supp.No.</td>
			<th>Inv Sr.No.</td>
			<th style="text-align:right;">Amount Paid</th>
		    <th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
			
		</tr>
	</thead>
	<tbody>
<?php
	$sql="SELECT * from payment_header where 1 and approval_status='Rejected' and company_id in ( $comid ) order by id desc ";//
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$tally_status  = $row['tally_status'];
		if($tally_status=='R'){
			$tally_status_a = 'JV Created';
		}	
		else if($tally_status=='U'){
			$tally_status_a = 'JV Synched';
		}
		else {
			$tally_status_a = 'JV Pending';	
		}
		
		
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
			$party_name  = $r2['username'];
		}
		else {
			$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$party_name  = $r2['party_name'];
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
		$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$rid' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$supplier_invoice_no  = $r2['supplier_invoice_no'];
		$supp_id			  = $r2['supp_id'];
		
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
			}
			
		$modulePath = "payment/";	
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="2%" style="text-align:right;<?php echo $styl2; ?>"><?php echo $row['id'];?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="12%" <?php echo $styl; ?>><?php echo $cash_bank_name;?></td>
		<td width="12%"<?php echo $styl; ?>><?php echo $party_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $row['utr_no'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $supplier_invoice_no;?></td>
		<td width="8%"<?php echo $styl; ?>><?php echo $supp_id . '-' . $st_flag;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo moneyFormatIndia($row['total_amount_paid']);?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $tally_status_a;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
    </tr>
	</a>
	
	<?php } ?>
</tbody> 
</table>
						</div>
						
						</fieldset>
									
		  </div>
	</div>
</div>
<!-- Payment end -->

<!--Travel Request Start -->						
    <div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_traval_approval where 1 and approval_status='Rejected'  and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$ta_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step3"><b>  Travel Request</b> ( <span style="font-size:18px;"><?= $ta_cnt;?></span> )<span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step3" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableE" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
        <th>SrNo.</th>
			<th>Name</th>
			<th>Location From</th>
			<th>Date From</th>
			<th>Location To</th>
			<th>Date To</th>
			<th>Company</th>
			<th>Advance Amount</th>
			<th>By</th>
			
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_traval_approval where 1 and approval_status='Rejected' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$emp_id = $row['emp_id'];
		$sql = "select * from sma_user where id = '$emp_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$emp_name = $r2['username'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		$start_date = date('d-m-Y', strtotime($row['start_date']));
		$end_date 	= date('d-m-Y', strtotime($row['end_date']));
		
		if($start_date=='01-01-1970'){ $start_date='';}
		if($end_date=='01-01-1970'){ $end_date='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;<?php echo $styl2; ?>"><?php echo $row['id'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $emp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $row['traval_from'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $start_date;?></td>
		<td width="13%"<?php echo $styl; ?>><?php echo $row['traval_to'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $end_date;?></td>
		<td width="04%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $row['advance_amount'];?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
		
		<td width="15%"<?php echo $styl; ?>><?php echo $row['status'].'-'.$row['approval_status'];?></td>
	</tr>
	
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->
<!--Travel REquest End-->	
      
	  
<!--Travel Expense Start -->						
    <div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='T' and approval_status='Rejected'  and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step4"><b>  Travel Expense</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> )<span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step4" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableF" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Date</th>
			<th>Approval Ref.no.</th>
			<th style="text-align:right;">Trip.Amount</th>
			<th style="text-align:right;">Exp.Amount</th>
			<th>By</th>
			
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='T' and approval_status='Rejected'  and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['username'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
		$exp_amount = 0;
		$fare		 = 0;
		$approval_ref_no = $row['approval_ref_no'];	
		$id = $row['id'];	
		$sql="SELECT * from sma_departure where approval_ref_no = '$id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$fare += $d1['fare'];
		}			
					
		$sql="SELECT * from sma_expenses where exp_type = 'T' and approval_ref_no = '$id' ";
		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$exp_amount += $d1['amount'];
		}			
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 	= $baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($fare);?></td>
		<td width="10%" style="text-align:right;"><?php echo number_format($exp_amount);?></td>
		<td width="09%"><?php echo $row['changed_by'];?></td>
		<td width="15%"><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
		<!--<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>-->
    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->
<!--Travel Expense End-->	

<!--Reimbursement Start -->						
    <div class="panel panel-default">
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='R' and approval_status='Rejected'  and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step5"><b>  Reimbursement</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> )<span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step5" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableG" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Date</th>
			
			<th style="text-align:right;">Exp.Amount</th>
			<th>By</th>
			
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_travel_expenses where 1 and exp_type ='R' and approval_status='Rejected'  and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['username'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
		$exp_amount = 0;
		$fare		 = 0;
		$approval_ref_no = $row['approval_ref_no'];	
		$id = $row['id'];	
		$sql="SELECT * from sma_departure where approval_ref_no = '$id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$fare += $d1['fare'];
		}			
					
		$sql="SELECT * from sma_expenses where exp_type = 'R' and approval_ref_no = '$id' ";
		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$j = 0;
		while($d1 = mysqli_fetch_array($res)){
			$exp_amount += $d1['amount'];
		}			
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 	= $baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo number_format($exp_amount);?></td>
		<td width="09%"><?php echo $row['changed_by'];?></td>
		<td width="15%"><?php echo $row['approval_status'];?></td>
		
		<!--<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>-->
    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->
<!--Reimbursement End-->	



<!--Budget Adjust Start -->						
    <div class="panel panel-default">
				<?php
				$sql="SELECT * from budget_adjust where 1 and approval_status ='Rejected' and current_approver = '$userid' and project in ($comid) order by id desc ";				
				$result = mysqli_query($con,$sql);
				$bd_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step6"><b>  Budget Adjust </b> ( <span style="font-size:18px;"><?= $bd_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step6" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtableH" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
			<td width="0%" style="display:none;">#</td>
			<th>Date</th>
			<th>Fin.Year</th>
			<th>Company</th>
			<th>Cost Center Group</th>
			<th>Cost Center Sub Group</th>
			<th>Effect</th>
			<th  style="text-align:right;">Amount</th>
			
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from budget_adjust where 1 and approval_status ='Rejected' and current_approver = '$userid'  and project in ($comid) order by id desc ";
//echo $sql;
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$project = $row['project'];
		$sql = "select * from company where comp_id = '$project' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
										
	$budget_head = $row['budget_head'];
	$sql = "SELECT * from sma_budget_category where id = '$budget_head' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_head = $r2['category'];
	
	$budget_name = $row['budget_name'];
	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_name = $r2['name'];
	
	$effect 		= $row['effect'];
	if($effect == 'I'){
		$effect = 'Increase';
	}
	else if($effect == 'D'){
		$effect = 'Decrease';
	}	
	
	$amount 		= $row['amount'];
	/* $approved_by 	= $row['approved_by'];
	
	$sql = " SELECT * from sma_user where id = '$approved_by' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$approved_by = $r2['username']; */
	
	$dated 			= date('d-m-Y', strtotime($row['dated']));
	
	$amount 		= $row['amount'];
	$fin_year 		= $row['fin_year'];
	
	$baseurl1 = $baseurl.$modulePath1.'budget_adjust.php?sub=edit&id='.$row["id"];
	
	$i = $i +1;
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget_adjust.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%" style="display:none;"><?php echo $i;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $fin_year;?></td>
		<td width="10%"><?php echo $comp_code;?></td>
		<td width="15%"><?php echo $budget_name;?></td>
		<td width="15%"><?php echo $budget_head;?></td>
		<td width="10%"><?php echo $effect;?></td>
		<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
		<td width="15%"><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
            <!-- /.box-header -->
<!--Budget Adjust End-->

				</div>
			</div> 
		</div> 
	</section> 
	

<?php 	
		include("footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
		$("#prtableA").DataTable();
		$("#prtableB").DataTable();
		$("#prtableC").DataTable();
		$("#prtableD").DataTable();
		$("#prtableE").DataTable();
		$("#prtableF").DataTable();
		$("#prtableG").DataTable();
		$("#prtableH").DataTable();
		$("#prtableI").DataTable();
		$("#prtableJ").DataTable();
    });
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
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}
?>	
		