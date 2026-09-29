<?php
include("dbcon.php");
session_start(); 	

	$role		= $_SESSION['role'];
	$userid   	= $_SESSION['usrid'];
	$comid      = $_SESSION['comid'];

	$company_id	= $_POST['company_id'];
	
	$financial_year_from = '';
	$financial_year_to = '';
					
	$fyear 	= $_POST['fyear'];
	$cyear 	= date('Y');
	$cmth	= date('m');
	
	/* if($cmth=='01' || $cmth=='02' || $cmth=='03' ){
		$financial_year_from	= ($fyear-1).'-04-01';
		$financial_year_to		= ($fyear).'-03-31';
	}
	else { */
	$financial_year_from	= $fyear.'-04-01';
	$financial_year_to		= ($fyear+1).'-03-31';
	//}		

if(isset($_POST['sub1'])){
	
?>
<section class="content">
			
    <div class="row">
		
        <div class="col-xs-12">
		
			<div class="box">
			
<!-- Approval Memo Pending -->			
			
			<?php
					
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0"><b>  Pending Approval Memo Against PO </b><span class="caret"></span> </a></h4>
				</div>
			<?php  
				 ?>
			
                <div id="step0" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * FROM sma_approval_memo  where 1 and overhead_exp ='N' and del !='Y' and status = 'Completed' 
	and dated >= '$financial_year_from' and dated <= '$financial_year_to'
	and company in ($comid) and id not in ( SELECT approval_memo_ref FROM `sma_purchase_order` where 1 and po_type = 'A' and del !='Y' and project in ($comid) and project = '$company_id' ) and company = '$company_id' ";
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
					
			
			</div>
	    </div> 
	</div> 
</section> 

<?php }

?>

<?php

if(isset($_POST['sub2'])){
	
?>
<section class="content">
			
    <div class="row">
		
        <div class="col-xs-12">
		
			<div class="box">
			
<!-- Comapny Expense Pending -->			
			
			<?php
					
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0"><b>  Pending Approval Memo Against Operating Expense </b><span class="caret"></span> </a></h4>
				</div>
			<?php  
				 ?>
			
                <div id="step0" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
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
	$sql="SELECT * FROM sma_approval_memo  where 1 and overhead_exp ='Y'  and del !='Y' and status = 'Completed' 
	and dated >= '$financial_year_from' and dated <= '$financial_year_to'
	and company in ($comid) and id not in (SELECT approval_number FROM `sma_travel_expenses` where 1 and exp_type = 'C' and del !='Y'  and company_id in ($comid) ) and company = '$company_id' ";	
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
            <!-- /.Company Expense box-header -->
					
			
			</div>
	    </div> 
	</div> 
</section> 

<?php }

?>


<?php

if(isset($_POST['sub3'])){
	
?>
<section class="content">
			
    <div class="row">
		
        <div class="col-xs-12">
		
			<div class="box">
			
<!-- Comapny Expense Pending -->			
			
			<?php
					
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0"><b>  Pending Purchase Order against SI </b><span class="caret"></span> </a></h4>
				</div>
			<?php  
				 ?>
			
                <div id="step0" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
        <th>#</th>
		<th>PO.No.</th>
		<th>Dated</th>
		<th>Doc.Type</th>
		<th>Supplier</th>
		<th style="text-align:right;">Total</th>
		<th>Created By</th>
		<th>By</th>
		<th>Decision</th>
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "purchase_order/";
	$sql = " SELECT * FROM sma_purchase_order  where 1 and dated != '2022-04-01' and del !='Y' and status = 'Completed' 
	and dated >= '$financial_year_from' and dated <= '$financial_year_to'
	and project in ($comid) and id not in (SELECT our_po_ref_no FROM `sma_supplier_invoice` where 1 and del !='Y'  and company_id in ($comid) ) and project = '$company_id' ";	
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
			
			$draft_by = $row['draft_by'];			
			
			$sql="SELECT * from sma_user where userid = '$draft_by' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$created_by 	= $r1['username'];
			
			$changed_by = $row['changed_by'];	
			$sql="SELECT * from sma_user where userid = '$changed_by' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$changed_by 	= $r1['username'];
			
			
			$po_rev = $row['po_rev'];
			$po_number = $row['po_number'];
			if($po_rev>0){
				$po_number .= $po_rev;
			}
			else {
				$po_number = $row['po_number'];
			}
			
			$approval_status = $row['approval_status'];
			
			$po_type		= $row['po_type'];
			if($po_type=='A'){
				$po_type = 'PO Against Approval Memo';
			}
			else if($po_type=='C'){
				$po_type = 'PO Cum Approval Memo';
			}
			
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="18%" <?php echo $styl; ?>><?php echo $po_number;?></td>
		<td width="08%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $po_type;?></td>
		<td width="19%" <?php echo $styl; ?>><?php echo $to_supplier;?></td>
		<td width="10%" style="text-align:right; <?php echo $styl2; ?>" ><?php echo $tot_amount;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $created_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $row['status'].'-'.$row['approval_status'];?></td>

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
            <!-- /.Company Expense box-header -->
					
			
			</div>
	    </div> 
	</div> 
</section> 

<?php }

?>


<?php

if(isset($_POST['sub4'])){
				
	$financial_year_from = '';
	$financial_year_to = '';
					
	$fyear 	= $_POST['fyear'];
	$cyear 	= date('Y');
	$cmth	= date('m');
	
	/* if($cmth=='01' || $cmth=='02' || $cmth=='03' ){
		$financial_year_from	= ($fyear-1).'-04-01';
		$financial_year_to		= ($fyear).'-03-31';
	}
	else { */
	$financial_year_from	= $fyear.'-04-01';
	$financial_year_to		= ($fyear+1).'-03-31';
	//}		
?>
<?php
				$sql = "TRUNCATE table pending_ap_po_si";
				mysqli_query($con, $sql);
				
				$sql = "select * from company where 1 ";
				$q2  = mysqli_query($con, $sql);
				while ($r2 = mysqli_fetch_array($q2)){
					
					$company_name  	= $r2['comp_name'];
					$company_code	= $r2['comp_code'];
					
					$comp_id		= $r2['comp_id'];
			 		$sql = " INSERT INTO pending_ap_po_si (company_id) VALUES ('$comp_id') ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
						
				}
				
				
				$sql="SELECT count(*) as pending_ap , project as company_id 
						FROM sma_purchase_order  
						WHERE 1 and del !='Y' and status in ( 'Completed') 
						and dated >= '$financial_year_from' and dated <= '$financial_year_to' 
						and project in ($comid) group by project ";	
//echo $sql ."<BR>";				
				$result 	= mysqli_query($con,$sql);
				while($r1 = mysqli_fetch_array($result)){
					$company_id 	= $r1['company_id'];
					$pending_ap 	= $r1['pending_ap'];
					
						$sql = "select * from company where comp_id = '$company_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  	= $r2['comp_name'];
						$company_code	= $r2['comp_code'];
						
					$sql = " UPDATE pending_ap_po_si set ap_po_count = '$pending_ap' where company_id 
								= '$company_id' ";
						mysqli_query($con, $sql);
						
				} ?>
				
			<?php
					
				$sql="SELECT count(*) as pending_ap, company as company_id FROM sma_approval_memo  where 1 and overhead_exp ='Y'  and del !='Y' and status = 'Completed' 
				and dated >= '$financial_year_from' and dated <= '$financial_year_to'
				and company in ($comid) and id not in (SELECT approval_number FROM `sma_travel_expenses` where 1 and exp_type = 'C' and del !='Y'  and company_id in ($comid) ) group by company  ";			
				$result 	= mysqli_query($con,$sql);
				while($r1 = mysqli_fetch_array($result)){
					$company_id 	= $r1['company_id'];
					$pending_ap 	= $r1['pending_ap'];
					$pending_ap_perc = ($r1['pending_ap'] / $total_ap) * 100;
					
						$sql = "select * from company where comp_id = '$company_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  	= $r2['comp_name'];
						$company_code	= $r2['comp_code'];
						
						$sql = " UPDATE pending_ap_po_si set ap_ce_count = '$pending_ap' where company_id 
								= '$company_id' ";
						mysqli_query($con, $sql);
				 } ?>	
			
			<?php
				
				$sql="SELECT count(*) as pending_po, project as company_id FROM sma_purchase_order  where 1 
				and del !='Y' and status = 'Completed' 
				and dated >= '$financial_year_from' and dated <= '$financial_year_to'
				and project in ($comid) and id not in (SELECT our_po_ref_no FROM `sma_supplier_invoice` where 1 and del !='Y'  and company_id in ($comid) ) group by project ";				
				$result 	= mysqli_query($con,$sql);
				while($r1 = mysqli_fetch_array($result)){
					$company_id 	 = $r1['company_id'];
					$pending_po 	 = $r1['pending_po'];
					$pending_po_perc = ($r1['pending_po'] / $total_po) * 100;
					$sql = "select * from company where comp_id = '$company_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  	= $r2['comp_name'];
						$company_code	= $r2['comp_code'];
						
						$sql = " UPDATE pending_ap_po_si set po_si_count = '$pending_po' where company_id = '$company_id' ";
						mysqli_query($con, $sql);
				}	
			?>	
                
			<table class="table table-striped">
                <tr>
                  <th  style="width: 1%">#</th>
                 
                  <th  style="width: 09%">Company</th>
				  <!--<td style="width: 10%"> Against PO</td>
				  <td style="width: 20%">%</td>-->
				  <td style="width: 10%">Purchase Order</td>
				  <td style="width: 20%">%</td>
				  <td style="width: 10%">Approval for OpEx</td>
				  <td style="width: 20%">%</td>
				  
                </tr>
		<?php 		
				$sql = "select sum(ap_po_count) as ap_po_count, sum(ap_ce_count) as ap_ce_count, sum(po_si_count) as po_si_count from pending_ap_po_si where 1 ";
				$result 	= mysqli_query($con,$sql);
				$r2 = mysqli_fetch_array($result);
				$ap_po_tot 	 = $r2['ap_po_count'];
				$ap_ce_tot 	 = $r2['ap_ce_count'];
				$po_si_tot 	 = $r2['po_si_count'];
					
				$sql = "SELECt * from pending_ap_po_si where 1 and company_id in ($comid) order by company_id ";
				$result 	= mysqli_query($con,$sql);
				while($r1 = mysqli_fetch_array($result)){
					$company_id 	 = $r1['company_id'];
					
					$company_id 	 = $r1['company_id'];
					
					$ap_po_count 	 = $r1['ap_po_count'];
					$pending_ap_perc = round(($r1['ap_po_count'] / $ap_po_tot) * 100,0);
					
					$ap_ce_count 	 = $r1['ap_ce_count'];
					$pending_ce_perc = round(($r1['ap_ce_count'] / $ap_ce_tot) * 100,0);
			
					$po_si_count 	 = $r1['po_si_count'];
					$pending_po_perc = round(($r1['po_si_count'] / $po_si_tot) * 100,0);
					
					$sql = "select * from company where comp_id = '$company_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  	= $r2['comp_name'];
						$company_code	= $r2['comp_code'];
						
		?>				
				<tr>
                  <td  style="width: 1%"><?= ++$ii;?></td>
				  <td  style="width: 09%"><?= $company_code;?></td>
				  <!-- <td  style="width: 10%"><a href="#" ONCLICK="pending_AP_PO(<?= $company_id;?>);" ><span class="badge bg-red" style="font-size:16px;" ><?= $ap_po_count;?></span></a></td>
				 <td style="width: 20%">
                    <div class="progress progress-xs progress-striped ">
                      <div class="progress-bar progress-bar-danger" style="width: <?= $pending_ap_perc;?>%"></div>
                    </div>
                  </td>-->
				  
				  <td  style="width: 10%"><a href="#" ONCLICK="pending_PO_SI(<?= $company_id;?>);" > <span class="badge bg-light-blue" style="font-size:16px;" ><?= $po_si_count;?></span></a></td>
				  <td style="width: 20%">
                    <div class="progress progress-xs progress-striped ">
                      <div class="progress-bar progress-bar-primary" style="width: <?= $pending_po_perc;?>%"></div>
                    </div>
                  </td>
				  
				  <td  style="width: 10%"><a href="#" ONCLICK="pending_AP_CE(<?= $company_id;?>);" ><span class="badge bg-yellow" style="font-size:16px;" ><?= $ap_ce_count;?></span></a></td>
				  <td style="width: 20%">
                    <div class="progress progress-xs progress-striped ">
                      <div class="progress-bar progress-bar-yellow" style="width: <?= $pending_ce_perc;?>%"></div>
                    </div>
                  </td>
				  
                  
                </tr>
				
		<?php } ?>	
            </table>
			
<?php 
	}		
?>			