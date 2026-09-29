<?php
include("header.php");
$modulePath = "setting/useraccs_new.php?sub=list";

	$help_code = 'dashboard.php';
	include "help_code.php";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	$role		= $_SESSION['role'];
	$userid   	= $_SESSION['usrid'];
	$comid      = $_SESSION['comid'];
	$user      = $_SESSION['user'];
	
	if($_GET['sub'] == 'list' || $_GET['sub'] == 'dash' ){
?>	
	<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Dashboard
		<small>
			<a href="<?php echo $help_link;?>" target="_blank" class="btn btn-info"><i class="fa fa-anchor"></i> Help</a>
		</small>
		
      </h1>
	  
      <ol class="breadcrumb">
		
        <li><a href="<?php echo $baseurl.'dashboard_athang.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Dashboard <?= $role.' ';?> </li>
				
      </ol>
    </section>
			
			
			<div class="box">
			
			<br>
			
            <span class="pull-left123 ">
				<div class="col-xs-2">
					<a href="#" class="btn btn-primary " id = "pending_color" ONCLICK="pending_dash();" ><span style="background-Color:grey123;" ><b >Pending</b></span></a>
				</div>
			</span>
			
			
			<span class="pull-left123">
				<div class="col-xs-2">
					<a href="dash_athang_approved_list.php" target="_blank" class="btn btn-success " id = "approved_color" ONCLICK="approved_dash();" ><b>Approved</b></a> 
				</div>
			</span>
			
			<span class="pull-left123">
				<div class="col-xs-2">
					<a href="#" class="btn btn-danger " id ="rejected_color" ONCLICK="rejected_dash();" ><b>Rejected</b></a> 
				</div>
			</span>
			
		<?php //echo $accountant_role . ' <<<>>>' ; 
		//	if( $accountant_role=='M' ||  $accountant_role=='Y' ){?>	
			<!--<span class="pull-left123">-->
			<!--	<div class="col-xs-2">-->
			<!--		<a href="#" class="btn btn-primary " id ="jvpending_color" ONCLICK="jvpending_dash();" ><b>JV Pending</b></a> -->
			<!--	</div>-->
			<!--</span>-->
			
			<!--<span class="pull-left123">-->
			<!--	<div class="col-xs-2">-->
			<!--		<a href="#" class="btn btn-danger " id ="jvcreated_color" ONCLICK="jvcreated_dash();" ><b>JV Created</b></a> -->
			<!--	</div>-->
			<!--</span>-->
			<?php
			$sql = " SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status = 'Completed' and status = 'Draft' and company_id in ( $comid ) ";
			mysqli_query($con,"$sql");
			$pinvcnt = mysqli_affected_rows($con);
	
			?>
			
			<!--<span class="pull-left123">-->
			<!--	<div class="col-xs-2">-->
			<!--		<a href="#" class="btn btn-success " id ="jvpending_invoice_color" ONCLICK="pending_invoice_dash();" ><b>Pending Invoice [<?= $pinvcnt;?>]</b></a> -->
			<!--	</div>-->
			<!--</span>-->
			
		<?php //} ?>	
			
			</div>
			
<span id="athang_dash">			
    <section class="content">
			
      <div class="row">
		
        <div class="col-xs-12123">
		
          <div class="box">
		  
<!-- Vendor Memo Pending -->	

<?php
 			$sql="SELECT * from sma_party_mst where 1 and status ='Submitted' and current_approver = '$userid' and ( add_to_tally != 'V' || party_kyc!='Y') ";
				$result = mysqli_query($con,$sql);
				$vp_cnt = mysqli_affected_rows($con);
				
				if($vp_cnt>0){	
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepV"><b> Vendor</b> ( <span style="font-size:18px;"><?= $vp_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="stepV" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<th>Supplier</th>
			<th>Type</th>
			<th>City</th>
			<th>GST No.</th>
			<th>KYC</th>
			
			<th style="text-align:right;">Action</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "vendor/";
	$sql="SELECT * from sma_party_mst where 1 and status ='Submitted' and current_approver = '$userid' and ( add_to_tally != 'V' || party_kyc!='Y')  ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$city = $row['party_city'];
		$sql = "select * from cities where id = '$city' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$city = $r2['city_name'];

		$category = $row['party_category'];
		$sql = "select * from sma_categories where id = '$category' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$category = $r2['name'];
										
		$party_type 					= $row['party_type'];
		$sql = "select * from sma_type where id = '$party_type' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$party_type = $r2['type'];
		
		$party_gst_number 				= $row['party_gst_number'];
		$party_pan_number 				= $row['party_pan_number'];
			
		$party_kyc						= $row['party_kyc'];
		$status							= $row['status'];
		
		if($status =='Submitted'){
			$party_kyc = 'N';
		}
		
		$baseurl1 = $baseurl.$modulePath.'vendor.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "vendor.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>&page=<?= $page;?>'">
		<td width="20%"><?php echo $row['party_name'];?></td>
		<td width="15%"><?php echo $party_type;?></td>
		<td width="15%"><?php echo $city;?></td>
		
		<td width="15%"><?php echo $party_gst_number;?></td>
		<td width="10%"><?php echo $party_kyc;?></td>
		
		<td width="10%" style="text-align:right;">
			<a href="vendor.php?sub=edit&id=<?php echo $row['id'];?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		</td>
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
	<?php } ?>
		  	
<!-- Vendor Pending -->			  
		  	

<!--  Income JV -->	

<?php
 			$sql="SELECT * from sma_income_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
				$result = mysqli_query($con,$sql);
				$vp_cnt = mysqli_affected_rows($con);
				
				if($vp_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepRV"><b> Receipt/Sales </b> ( <span style="font-size:18px;"><?= $vp_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="stepRV" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<tr>
			<th></th>
			<th>Sr.No.</th>
					<th>Company</th>
                    <th>Prepared On</th>
					<th>Document Type</th>
					<th>Received From</th>
					<th style="text-align:right;">Amount</th>
					<th>By</th>
					
					<th>Status</th>
					<th>Decision</th>
		</tr>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "income/";
	$sql="SELECT * from sma_income_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$rid 				= $row['id'];
						
			$company = $row['company_id'];
			$sql = "select * from company where comp_id = '$company' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$company_name  	= $r2['comp_name'];
			$company  		= $r2['comp_code'];

						
			$draft_by 		= $row['draft_by'];
			$income_jv_flag = $row['income_jv_flag'];
			$doctype='';
			if($income_jv_flag=='R'){
				$doctype = 'Receipt';
			}
			else if($income_jv_flag=='S'){
				$doctype = 'Sales';
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
				
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated ='';
				}	
				
				$in_id = $row['id'];
				$sql = "select sum(amount) as amount from sma_income_dtl where income_hdr_id = '$in_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$amount  		= $r2['amount'];
				
				$sql = "select * from sma_income_dtl where income_hdr_id = '$in_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$account_id  		= $r2['account_id'];
				
				$income_account_name = $row['income_account_name'];
				$sql="SELECT * FROM account_mst where id  = '$income_account_name' ";				
				$qry = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($qry);
				$account_name		= $r2['account_name'];
				
				$party_id = $row['party_id'];
				$sale_to_flag		= $row['sale_to_flag'];	
				if($sale_to_flag=='U'){
					$sql = "select * from sma_user where id = '$party_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$party_name  = $r2['username'];
		
				}	
				else {
					$sql="SELECT * FROM sma_party_mst where id  = '$party_id' ";				
					$qry = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r2 = mysqli_fetch_array($qry);
					$party_name		= $r2['party_name'];
				}
								
				$j=$j+1;				

				$status = $row['status'];		
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"].'&page='.$page;
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
	
					<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="4%" <?php echo $styl; ?> ><?php echo $row['id'];?></td>
					<td width="6%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $dated;?></td>
					
					<td width="18%" <?php echo $styl; ?>><?php echo $doctype;?></td>
					<td width="18%" <?php echo $styl; ?>><?php echo $party_name;?></td>
					<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
					
					<td width="08%" <?php echo $styl; ?>><?php echo $row['status'];?></td>
					<td width="08%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
					
<!--					<td width="5%" style="text-align:right;" >
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						
						<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
						<?php include "view_history.php"; ?>
						<a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>

					</td>-->
						<td width="6%" style="text-align:center;">
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center" ><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
				<?php if($status == 'Completed' ){ ?>		
						<a href="copy_pv_jv_.php?sub=copy&in_id=<?php echo $row['id'];?>" title="Copy" onclick="return confirm('Are you sure you want to copy?');"><i class="fa fa-copy"></i></a>
				<?php } ?>
		
						</td>
				
				</tr>
				
	</a>
	<?php }?>
	
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
	<?php } ?>
		  	
<!-- Income JV  -->			  



<!-- <!-- Provisional / Reversal JV -->	

<?php
 			$sql="SELECT * from sma_provisional_jv_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
				$result = mysqli_query($con,$sql);
				$vp_cnt = mysqli_affected_rows($con);
				
				if($vp_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepRV"><b> Provisional JV</b> ( <span style="font-size:18px;"><?= $vp_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="stepRV" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<tr>
			<th></th>
			<th>Sr.No.</th>
			<th>Company</th>
            <th>Prepared On</th>
			<th>First Account</th>
			<th style="text-align:right;">Amount</th>
			<th>Status </th>
			<th style="text-align:left;">Tally Status</th>
		</tr>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "provisional_jv/";
	$sql="SELECT * from sma_provisional_jv_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$rid 				= $row['id'];
						
			$company = $row['company_id'];
			$sql = "select * from company where comp_id = '$company' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$company_name  	= $r2['comp_name'];
			$company  		= $r2['comp_code'];

						
			$draft_by = $row['draft_by'];
				
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
				
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated ='';
				}	
				
				$pv_id = $row['id'];
				$sql = "select sum(amount) as amount from sma_provisional_jv_details where provisional_jv_hdr_id = '$pv_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$amount  		= $r2['amount'];
				
				$sql = "select * from sma_provisional_jv_details where provisional_jv_hdr_id = '$pv_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$account_id  		= $r2['account_id'];
				
				//$sql="SELECT * FROM sma_budget where id  = '$account_id' ";
				$sql="SELECT * FROM sma_product where id  = '$account_id' ";				
				$qry = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($qry);
				$account_name		= $r2['name'];
											
				$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"].'&page='.$page;
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
	
			<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>
			<td width="4%" <?php echo $styl; ?> ><?php echo $row['id'];?></td>
			<td width="10%" <?php echo $styl; ?>><?php echo $company;?></td>
			<td width="12%" <?php echo $styl; ?>><?php echo $dated;?></td>
					
			<td width="25%" <?php echo $styl; ?>><?php echo $account_name;?></td>
			<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
			<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
					
			<td width="08%" <?php echo $styl; ?>><?php echo $row['status'];?></td>
			<td width="08%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
			<td width="10%" style="text-align:right;">
				
			</td>
		</tr>		
	</a>
	<?php }?>
	
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
	<?php } ?>
		  	
<!-- Provisional / Reversal JV  -->		

<!-- Revenue JV Pending -->	

<?php
 			$sql="SELECT * from p2p_revenue_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
				$result = mysqli_query($con,$sql);
				$vp_cnt = mysqli_affected_rows($con);
				
				if($vp_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepRV"><b> Revenue JV</b> ( <span style="font-size:18px;"><?= $vp_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="stepRV" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<tr>
			<th>#</th>
			<th>Date</th>
			<th>Project Name</th>
			<th>Tollplaza Name</th>
			<th style="text-align:right;" >Revenue </th>
			<th style="text-align:right;" >Adjustment </th>
			<th>Status </th>
			<th style="text-align:left;">Tally Status</th>
		</tr>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "revenue_jv/";
	$sql="SELECT * from p2p_revenue_hdr where 1 and status ='Submitted' and current_approver = '$userid'  ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$revenue_hdr_id		= $row['id'];
		$dated 				= $row['dated'];
		$project			= $row['project'];
		$tollplaza			= $row['tollplaza'];
		$project_name		= $row['project_name'];
		$tollplaza_name		= $row['tollplaza_name'];
		$status				= $row['status'];
		$tally_status 		= $row['tally_status'];
		
		$sql = "SELECT sum(revenue) as revenue, sum(adjustment) as adjustment FROM `p2p_revenue_data` where 1 and dated = '$dated' and project= '$project' and tollplaza = '$tollplaza' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($res);
		$revenue 				= $r2['revenue'];
		$adjustment				= $r2['adjustment'];
		
		$baseurl1 = $baseurl.$modulePath.'revenue_jv.php?sub=edit&revenue_hdr_id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "revenue_jv.php?sub=edit&revenue_hdr_id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" ></td>
		<td width="10%"><?= date('d-m-Y', strtotime($dated));?></td>
		<td width="30%"><?= $project_name;?></td>
		<td width="10%"><?= $tollplaza_name;?></td>
		<td width="10%" style="text-align:right;" ><?= moneyFormatIndiaa($revenue);?></td>
		<td width="10%" style="text-align:right;"><?= moneyFormatIndiaa($adjustment);?></td>
		<td width="10%"><?= $status;?></td>
		<td width="10%" style="text-align:left;"><?= $tally_status; ?></td>
    </tr>
	</a>
	<?php }?>
	
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
	<?php } ?>
		  	
<!-- Revenue JV Pending -->			  
		  	

<!-- Budget Proposal -->	

<?php
 			$sql="SELECT * from sma_budget_proposal where 1 and status ='Submitted' and current_approver = '$userid'  ";
				$result = mysqli_query($con,$sql);
				$vp_cnt = mysqli_affected_rows($con);
				
				if($vp_cnt>0){
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepBP"><b> Budget Proposal </b> ( <span style="font-size:18px;"><?= $vp_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="stepBP" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<tr>
			<th></th>
			<th>Srno.</th>
			<th>Account Year</th>
			<th>company Name</th>
			<th>Current Version</th>
			<th>Created By </th>
			<th>Last Updated </th>
			<th>Status </th>
			<th style="text-align:left;">&nbsp;</th>
		</tr>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "budget_proposal/";
	$sql="SELECT * from sma_budget_proposal where 1 and status ='Submitted' and current_approver = '$userid'  ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$hdr_id				= $row['id'];
		$company_id 		= $row['company_id'];
		$sql="SELECT * FROM `company` where 1 and comp_id in ($company_id)";
//echo $sql."<BR>";	
		$req = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($req);
		$comp_code		= $r2['comp_code'];
		$comp_name		= $r2['comp_name'];
		
		$account_year 		= $row['account_year'];
		$current_version 	= $row['current_version'];
		$status				= $row['status'];
		$tally_status 		= $row['tally_status'];
		$draft_by 			= $row['draft_by'];
		$draft_by 			= $row['draft_by'];
		$company_id 		= $row['company_id'];
		$sql="SELECT * FROM `sma_user` where 1 and userid = '$draft_by' ";
//echo $sql."<BR>";	
		$req = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($req);
		$draft_by		= $r2['username'];
		
		$baseurl1 = $baseurl.$modulePath.'budget_proposal.php?sub=edit&hdr_id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "budget_proposal.php?sub=edit&hdr_id=". $row['id']?>" title="Edit">
		<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		
			<td width="1%"><input type="hidden" value="<?= $ii++;?>" ></td>
			<td width="5%"><?= $hdr_id;?></td>
			<td width="10%"><?= $account_year;?></td>
			<td width="29%"><?= $comp_name;?></td>
			<td width="10%"><?= $current_version;?></td>
			<td width="10%"><?= $draft_by;?></td>
			<td width="10%"><?= $draft_by;?></td>
			<td width="10%"><?= $status;?></td>
			<td width="10%">	
			</td>
		</tr>	
	</a>
	<?php }?>
	
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
	<?php } ?>
		  	
<!-- Budget Proposal  -->			  

			
<!-- Purchase Requisition Pending -->			
			
			<?php
				$sql="SELECT * from sma_purchase_req where 1 and del !='Y' and status ='Submitted'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
//echo $sql."<BR>";	
//exit();			
				$result = mysqli_query($con,$sql);
				$ap_cnt = mysqli_affected_rows($con);
				if($ap_cnt>=0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step00"><b>  Purchase Requisition</b> ( <span style="font-size:18px;"><?= $ap_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			<?php } 
				 ?>
			
                <div id="step00" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>	
                  <th>SR.No.</th>
				  <th>Date</th>
                  <th>Company</th>
				  <th>Department</th>
				  <th>By</th>
				  <th>Status</th>
				  <th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "purchase_requisition/";
	$sql="SELECT * from sma_purchase_req where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$rid = $row['id'];
		
						$company_id 	= $row['company_id'];
					$sql 	= "select * from company where comp_id = '$company_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$comp_code = $r2['comp_code'];
					
					/* $supplier_id 	= $row['supplier_id'];
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name = $r2['party_name']; */
		
					$department_id 	= $row['department_id'];
					$sql 	= "select * from sma_department where id = '$department_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$department_name = $r2['name'];
					
					$delivery_require_by 	= $row['delivery_require_by'];
					$dated 					= $row['date'];
					$changed_by 	= $row['changed_by'];
					$sql 	= "select * from sma_user where id = '$changed_by' ";
					$draft_by 	= $row['draft_by'];
					$sql 	= "select * from sma_user where userid = '$draft_by' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$changed_by = $r2['username'];
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
		<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
				<td width="1%"> <input type="hidden" value="<?= ++$ii; ?>" > </td>	 	
				  <td width="5%" style="text-align:left;"> <?php echo $row['pr_number']; ?> </td>	
                  <td width="10%"><?php echo DateTime::createFromFormat('Y-m-d', $dated)->format('d-m-Y'); ?></td>
                  <td width="20%"><?php echo $comp_code; ?></td>
				  <td width="10%"><?php echo $department_name; ?></td>
				  <td width="10%"><?php echo $changed_by;?></td>
				  <td width="10%"><?php echo $row['status'];?></td>
				  <td width="10%"><?php echo $row['approval_status'];?></td>
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
            <!-- /.Purchase Requisition box-header -->					
		  		  
				  
<!-- Note for Approval(NOA) Pending -->			
			
			<?php
				$sql="SELECT * from sma_approval_memo where 1 and del !='Y' and doctype != 'AP-ADJ' and status ='Submitted'  and approval_status !='Rejected'  and current_approver = '$userid' and project in ( $comid ) ";
//echo $sql."<BR>";				
				$result = mysqli_query($con,$sql);
				$ap_cnt = mysqli_affected_rows($con);
				if($ap_cnt>=0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0"><b>  Note for Approval(NOA)</b> ( <span style="font-size:18px;"><?= $ap_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="step0" class="panel-collapse collapse in123">
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
	$sql="SELECT * from sma_approval_memo where 1 and del !='Y' and doctype != 'AP-ADJ'  and status ='Submitted' and approval_status !='Rejected'  and current_approver = '$userid' and project in ( $comid ) order by id desc ";
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
			<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
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
	<?php } ?>
									
            <!-- /.Note for Approval(NOA) box-header -->
					
		  
<!-- Note for Approval(NOA) Reversal  Pending -->			
			
			<?php
				$sql="SELECT * from sma_approval_memo where 1 and del !='Y' and doctype = 'AP-ADJ' and status ='Submitted'  and approval_status !='Rejected'  and current_approver = '$userid' and project in ( $comid ) ";
				
				$result = mysqli_query($con,$sql);
				$ap_cnt = mysqli_affected_rows($con);
				if($ap_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step0R"><b>  Note for Approval(NOA) Reversal </b> ( <span style="font-size:18px;"><?= $ap_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="step0R" class="panel-collapse collapse in123">
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
	$modulePath = "approval_adjustment/";
	$sql="SELECT * from sma_approval_memo where 1 and del !='Y' and doctype = 'AP-ADJ' and status ='Submitted' and approval_status !='Rejected'   and current_approver = '$userid' and project in ( $comid ) order by id desc ";
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
			<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
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
	<?php } ?>
									
            <!-- /.Note for Approval(NOA) Reversal box-header -->
					
		  
		  
<!--Purchase OrderStart -->			
				<?php
				$sql="SELECT * from sma_purchase_order where 1 and del !='Y' and status not in ('Amend', 'Suspend','Completed', 'Auto Closed', 'Closed' ) and approval_status not in ('Rejected')   and current_approver = '$userid'  ";
//echo $sql. "<BR>";//'Amend',// and project in ( $comid )
				$result = mysqli_query($con,$sql);
				$po_cnt = mysqli_affected_rows($con);
			if($po_cnt>=0){
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step1"><b> Purchase Order </b> ( <span style="font-size:18px;"><?= $po_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
						
							
                <div id="step1" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
        <th>PO.No.</th>
		<th>Dated</th>
		<th>Doc.Type</th>
		<th>Supplier</th>
		<th style="text-align:right;">Total</th>
		<th>By</th>
		<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "purchase_order/";
	$sql="SELECT * from sma_purchase_order where 1 and del !='Y' and status not in ('Amend','Suspend','Completed', 'Auto Closed', 'Closed' )  and approval_status !='Rejected'  and current_approver = '$userid' and project in ( $comid ) order by id desc ";
	
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
				$po_type = 'PO Against Note for Approval(NOA)';
			}
			else if($po_type=='C'){
				$po_type = 'PO Cum Note for Approval(NOA)';
			}
			
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="18%" <?php echo $styl; ?>><?php echo $po_number;?></td>
		<td width="08%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="19%" <?php echo $styl; ?>><?php echo $po_type;?></td>
		<td width="19%" <?php echo $styl; ?>><?php echo $to_supplier;?></td>
		<td width="10%" style="text-align:right; <?php echo $styl2; ?>" ><?php echo moneyFormatIndiaa($tot_amount);?></td>
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
	<?php } ?>
										
            <!-- /.box-header -->

<!--Purchase Order End -->



<!-- GRN Start -->			
			
				<?php
				$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status !='Completed' and grn_approval_status !='Rejected' and grn_approver = '$userid' and company_id in ( $comid ) ";
				
//union SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Draft' and draft_by = '$user' and draft_by_supplier != '' and company_id in ( $comid )";
	//echo $sql."<BR>";// exit();
	
				$result = mysqli_query($con,$sql);
				$grn_cnt = mysqli_affected_rows($con);
			if($grn_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step12"><b> GRN</b> ( <span style="font-size:18px;"><?= $grn_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step12" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status !='Completed' and grn_approval_status !='Rejected' and grn_approver = '$userid' and company_id in ( $comid )";
//union SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Draft' and draft_by = '$user' and draft_by_supplier != '' and company_id in ( $comid )
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
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
		$baseurl1 = $baseurl.$modulePath.'editgrn.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "editgrn.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<!--<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>-->
		<td width="3%" style="text-align:right;"><?php echo $row['id']?></td>
		<td width="11%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
		<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($row['total_amount'])?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
		<td width="17%"><?php echo $supplier_name;?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="15%" ><?php echo $row['grn_status'].'-'.$row['grn_approval_status'];?></td>
		
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
<?php } ?>							
<!--GRN End -->



<!-- Supplier Invoice Start -->			
			
				<?php
				$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid )
				union 
				SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Draft' and draft_by = '$user' and draft_by_supplier != '' and company_id in ( $comid )";
//echo $sql;
				$result = mysqli_query($con,$sql);
				$si_cnt = mysqli_affected_rows($con);
			if($si_cnt>=0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2"><b> Supplier Invoice</b> ( <span style="font-size:18px;"><?= $si_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and status !='Completed' and approval_status !='Rejected'   and current_approver = '$userid' and company_id in ( $comid ) 
	union 
	SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Draft' and draft_by = '$user' and draft_by_supplier != '' and company_id in ( $comid )
		order by id desc ";
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
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($row['total_amount'])?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
		<td width="17%"><?php echo $supplier_name;?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="11%"><?php echo $tally_status_a;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
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
<?php } ?>							
<!--Supplier Invoice End -->



<!-- Retention / Complainces  Start -->			
			
				<?php
				$sql="SELECT * from sma_retention_invoice where 1 and del !='Y' and status ='Submitted'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
//echo $sql;
				$result = mysqli_query($con,$sql);
				$si_cnt = mysqli_affected_rows($con);
			if($si_cnt>=0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2a"><b> Retention / Compliances Invoice</b> ( <span style="font-size:18px;"><?= $si_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2a" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$modulePath = "retention/";
	$sql="SELECT * from sma_retention_invoice where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected'   and current_approver = '$userid' and company_id in ( $comid ) 
		order by id desc ";
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
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($row['total_amount'])?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
		<td width="17%"><?php echo $supplier_name;?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="11%"><?php echo $tally_status_a;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
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
<?php } ?>							
<!--Retention / Complainces Invoice End -->


<!--Good Receipt Note Start -->			
			
				<?php
				$sql="SELECT * from sma_goods_receipt_note where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
//echo $sql. "<BR>";				
				$result = mysqli_query($con,$sql);
				$gi_cnt = mysqli_affected_rows($con);
			if($gi_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2GI"><b> Good Receipt Note</b> ( <span style="font-size:18px;"><?= $gi_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2GI" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
			<td>SrNo.</td>
			<th>Company Name</th>
			<th  style="text-align:left;">Dated</th>
			
			<th style="text-align:left;">Receipt Location</th>
			<th style="text-align:left;">Receipt Person</th>
			<th style="text-align:left;">Department</th>
			<th style="text-align:left;">Product Name</th>
		    <th>By</th>
			<th>Decision</th>
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "goods_issue_note/";
	$sql="SELECT * from sma_goods_receipt_note where 1 and del !='Y' and status !='Completed' and approval_status !='Rejected' and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$grn_hdr_id		= $row['id'];
		$company_id 	= $row['company_id'];
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		//echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$comp_name 	= $r2['comp_name'];
		$company_id 	= $r2['comp_code'];
			
		$dated 	= date('d-m-Y', strtotime($row['dated']));
		$receipt_location 		= $row['receipt_location'];
		
		$receipt_department 			= $row['receipt_department'];
		$receipt_person 			= $row['receipt_person'];
		$sql = "select * from sma_user where 1 and id = '$receipt_person' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$receipt_person			= $r2['username'];
		
		$sql = "select * from sma_department where 1 and id = '$receipt_department' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$receipt_department		= $r2['name'];
		
		$product_name = '';
		$sql = "select * from sma_goods_receipt_note_items where 1 and grn_hdr_id = '$grn_hdr_id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
			$product_id		= $r2['product_id'];
			$sql 	= "select * from sma_product where id = '$product_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$product_name .= $r2['name']."<br>";
		}
		
		$changed_by = $row['draft_by'];			
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];

	$baseurl1 = $baseurl.$modulePath.'goods_receipt_note.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "goods_receipt_note.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" ></td>
		<td width="5%"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $company_id;?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($dated);?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($receipt_location);?></td>
		
		<td width="15%" style="text-align:left;"><?php echo ($receipt_person);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($receipt_department);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($product_name);?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="goods_receipt_note.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
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
<?php } ?>							
<!--Good Receipt Note End -->

<!--Good Issue Note Start -->			
			
				<?php
				$sql="SELECT * from sma_goods_issue_note where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
//echo $sql. "<BR>";				
				$result = mysqli_query($con,$sql);
				$gi_cnt = mysqli_affected_rows($con);
			if($gi_cnt>0){		
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2GI"><b> Good Issue Note</b> ( <span style="font-size:18px;"><?= $gi_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2GI" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		
        <th>Sr.No.</th>
			<th>Company Name</th>
			<th  style="text-align:left;">Dated</th>
			<th  style="text-align:left;">Against MRN No</th>
			<th style="text-align:left;">Issue Location</th>
			<th style="text-align:left;">Issue Person</th>
			<th style="text-align:left;">Department</th>
			<th style="text-align:left;">Product Name</th>
		    <th>By</th>
			<th>Decision</th>
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "goods_issue_note/";
	$sql="SELECT * from sma_goods_issue_note where 1 and del !='Y' and status !='Completed' and approval_status !='Rejected' and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$gin_hdr_id		= $row['id'];
		$company_id 	= $row['company_id'];
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		//echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$comp_name 	= $r2['comp_name'];
		$company_id 	= $r2['comp_code'];
	
		$dated 	= date('d-m-Y', strtotime($row['dated']));
		$issue_location 		= $row['issue_location'];
		$against_mrn_no			= $row['against_mrn_no'];
		
		$issue_department 			= $row['issue_department'];
		$issue_person 			= $row['issue_person'];
		$sql = "select * from sma_user where 1 and id = '$issue_person' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$issue_person			= $r2['username'];
		
		$sql = "select * from sma_department where 1 and id = '$issue_department' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$issue_department		= $r2['name'];
		
		$product_name = '';
		$sql = "select * from sma_goods_issue_note_items where 1 and gin_hdr_id = '$gin_hdr_id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
			$product_id		= $r2['product_id'];
			$sql 	= "select * from sma_product where id = '$product_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$product_name .= $r2['name']."<br>";
		}

		$changed_by = $row['draft_by'];			
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];
		
	$baseurl1 = $baseurl.$modulePath.'goods_issue_note.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "goods_issue_note.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="3%" style="text-align:right;"><?php echo $row['id']?></td>
		<td width="10%"><?php echo $company_id;?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($dated);?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($against_mrn_no);?></td>
		<td width="10%" style="text-align:left;"><?php echo ($issue_location);?></td>
		
		<td width="15%" style="text-align:left;"><?php echo ($issue_person);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($issue_department);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($product_name);?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		
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
<?php } ?>							
<!--Good Issue Note End -->


<!--Company Expense Start -->						
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='C' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' ";
				
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				if($te_cnt>0){	
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step5c"><b> Operating Expense</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
						
                <div id="step5c" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='C' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' order by id desc ";
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
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
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
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($exp_amount);?></td>
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
	<?php } ?>													
            <!-- /.box-header -->
<!--Company Expense End-->



<!--Direct Expense Start -->						
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='D' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' ";
				
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				if($te_cnt>0){	
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step5cd"><b> Direct Expense</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
						
                <div id="step5cd" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='D' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' order by id desc ";
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
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
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
					
		$sql="SELECT * from sma_expenses where exp_type = 'D' and approval_ref_no = '$id' ";
		
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
			
		$baseurl1 	= $baseurl.$modulePath.'direct_expense_payment.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "direct_expense_payment.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($exp_amount);?></td>
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
	<?php } ?>													
            <!-- /.box-header -->
<!--Direct Expense End-->


<!-- IPC Start Pending -->			
				<?php
				$sql="SELECT * from sma_ipc where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected' and current_approver = '$userid' and sma_comp_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$ip_cnt = mysqli_affected_rows($con);
				if($ip_cnt>0){	
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepip"><b>  IPC</b> ( <span style="font-size:18px;"><?= $ip_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
				
                <div id="stepip" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
        <th>Sr.No.</th>
			<th>Dated</th>
			<th>Party</th>
			<th>PO.Number</th>
			<th>Invoice No.</th>
			<th>PO Amount</th>
			<th>Invoice Amount</th>
		<th>By</th>
		<th>Status</th>
		<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "ipc/";
	$sql="SELECT * from sma_ipc where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected' and current_approver = '$userid' and sma_comp_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$sma_vendor_id = $row['sma_vendor_id'];
		$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$sma_vendor_name = $r2['party_name'];
		
		$sma_comp_id = $row['sma_comp_id'];
		$sql = "select * from company where comp_id = '$sma_comp_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_name = $r2['comp_name'];
		
		$sma_po_no = $row['sma_po_no'];
		$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_number = $r2['po_number'];
		$po_rev	   = $r2['po_rev'];
		if($po_rev>0){
			$po_number = $po_number.'-'.$po_rev;
		}	
		
		$sma_invoice_no = $row['sma_invoice_no'];
		$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$sma_invoice_no = $r2['supplier_invoice_no'];
		
		$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
		if($ipc_date == '01-01-1970'){
			$ipc_date	='';
		}	
		
		$sma_inv_adv = $row['sma_inv_adv'];
		if($sma_inv_adv =='P'){
			$status = 'Previous';
		}
		else {
			$status = $row['status'];
		}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
	<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="3%" <?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="12%" <?php echo $styl; ?>><?php echo $ipc_date;?></td>
		<td width="25%" <?php echo $styl; ?>><?php echo $sma_vendor_name;?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo $po_number;?></td>
		<td width="8%" <?php echo $styl; ?>><?php echo $sma_invoice_no;?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo moneyFormatIndiaa($row['sma_po_amount']);?></td>
		<td width="9%" <?php echo $styl; ?>><?php echo moneyFormatIndiaa($row['sma_invoice_amount']);?></td>
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
	<?php } ?>							
<!-- /.IPC End -->

<!-- Payment Start-->
				<?php
				$sql="SELECT * from payment_header where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid )";//
				$result = mysqli_query($con,$sql);
				$py_cnt = mysqli_affected_rows($con);
				if($py_cnt>0){	
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2p"><b>  Payment</b> ( <span style="font-size:18px;"><?= $py_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
				
                <div id="step2p" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from payment_header where 1  and del !='Y' and status !='Completed' and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";//
	$result = mysqli_query($con, $sql);
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
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo moneyFormatIndiaa($row['total_amount_paid']);?></td>
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

<?php } ?>							

<!-- Payment end -->


<!-- Advance Note-->
<?php  
	$modulePath = "advance/";
		
		$sql="SELECT * from sma_advance where 1 and del !='Y' and ( (status !='Completed' and current_approver = '$userid')  )  and approval_status !='Rejected'  and company_id in ( $comid )";
		$result = mysqli_query($con,$sql);
		//echo $sql;	
		$ad_cnt = mysqli_affected_rows($con);
				if($ad_cnt>0){
				?>
			<div class="panel panel-default">
	
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2ad"><b>  Advance Note</b> ( <span style="font-size:18px;"><?= $ad_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="step2ad" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th></th>
			<th>SR.No.</th>
			<th>Supplier Name</th>
			<th>Dated</th>
			<th>company</th>
			<th>PO number</th>
			<th>PP Amount</th>
			<th>Advance Amount</th>
			<th>By</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
<?php
	$sql="SELECT * from sma_advance where 1  and del !='Y' and status !='Completed' and approval_status !='Rejected' and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$rid = $row['id'];
		$baseurl1 = $baseurl . $modulePath . 'edit.php?sub=edit&id=' . $row["id"];
									
		$po_ref_no 			= $row['po_ref_no'];
		$advance_amount 	= $row['advance_amount'];
		$total_po_amount 	= $row['total_po_amount'];

		$sql = " SELECT * FROM `sma_purchase_order` where id  = '$po_ref_no' and del !='Y' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$po_number = $r2['po_number'];
									
		$company_id = $row['company_id'];
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];


		$supplier_id 	= $row['supplier_id'];
		$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$party_name = $r2['party_name'];

		$department_id = $row['department_id'];
		$sql = "select * from sma_department where id = '$department_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$department_name = $r2['name'];

		$dated = $row['dated'];
		$changed_by = $row['changed_by'];
		$draft_by = $row['draft_by'];
		$sql = "select * from sma_user where userid = '$draft_by' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$changed_by = $r2['username'];

		$approver_1 = $row['approver_1'];
		$approver_2 = $row['approver_2'];
		$approver_3 = $row['approver_3'];
		$approver_4 = $row['approver_4'];
		$approver_5 = $row['approver_5'];
		$approver_6 = $row['approver_6'];
		$approver_7 = $row['approver_7'];
		$approver_8 = $row['approver_8'];
		$approver_9 = $row['approver_9'];

		$approver_1_status = $row['approver_1_status'];
		$approver_2_status = $row['approver_2_status'];
		$approver_3_status = $row['approver_3_status'];
		$approver_4_status = $row['approver_4_status'];
		$approver_5_status = $row['approver_5_status'];
		$approver_6_status = $row['approver_6_status'];
		$approver_7_status = $row['approver_7_status'];
		$approver_8_status = $row['approver_8_status'];
		$approver_9_status = $row['approver_9_status'];
		$pending_by = '';
		if ($approver_1_status == 'Submitted') {
			$pending_by = $approver_1;
		}
		if ($approver_2_status == 'Submitted') {
			$pending_by = $approver_2;
		}
		if ($approver_3_status == 'Submitted') {
			$pending_by = $approver_3;
		}
		if ($approver_4_status == 'Submitted') {
			$pending_by = $approver_4;
		}
		if ($approver_5_status == 'Submitted') {
			$pending_by = $approver_5;
		}
		if ($approver_6_status == 'Submitted') {
			$pending_by = $approver_6;
		}
		if ($approver_7_status == 'Submitted') {
			$pending_by = $approver_7;
		}
		if ($approver_8_status == 'Submitted') {
			$pending_by = $approver_8;
		}
		if ($approver_9_status == 'Submitted') {
			$pending_by = $approver_9;
		}

		$upd_flag_v = '';
		$upd_flag = $row['upd_flag'];
		if ($upd_flag == 'O') {
			$upd_flag_v = '(Uploaded)';
		}

		if (!empty($pending_by)) {
			$sql = "select * from sma_user where id = '$pending_by' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$pending_by = ' To <br>' . $r2['username'];
		}

		$styl = "";
		$del = $row['del'];
		if ($del == 'Y') {
			$styl = "style='bgcolor:powderblue;color:red;' ";
							
		}

?>
								
		<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=" . $row['id'] ?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)"
											onclick="location.href='<?php echo $baseurl1; ?>'">

				<td width="0%"><input type="hidden" value="<?= ++$ii; ?>"> </td>
				<td width="5%" <?= $styl; ?> style="text-align:right;"> <?php echo $row['id']; ?></td>
				<td width="20%" <?= $styl; ?>><?php echo $party_name; ?></td>
				<td width="10%" <?= $styl; ?>><?php echo DateTime::createFromFormat('Y-m-d', $dated)->format('d-m-Y'); ?></td>
											
				<td width="10%" <?= $styl; ?>><?php echo $comp_code; ?></td>

				<td width="15%" <?= $styl; ?> style="text-align:right;"><?php echo $po_number; ?> </td>
				<td width="10%" <?= $styl; ?> style="text-align:right;"><?php echo $advance_amount; ?></td>
				<td width="10%" <?= $styl; ?> style="text-align:right;"><?php echo $total_po_amount; ?></td>
				<td width="10%" <?= $styl; ?>><?php echo $changed_by; ?></td>

				<td width="10%" <?= $styl; ?>><?php echo $row['status'] . ' ' . $pending_by . "<BR>" . $upd_flag_v; ?></td>
											

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

<?php } ?>							

<!-- Advance Note -->


<!-- Petty Cash Start-->
				<?php  
				$sql="SELECT * from sma_pettycash where 1 and del !='Y' and ( (status !='Completed' and current_approver = '$userid')  )  and approval_status !='Rejected'  and company_id in ( $comid )";//
				$result = mysqli_query($con,$sql);
			//echo $sql;	
				$py_cnt = mysqli_affected_rows($con);
				if($py_cnt>0){
				?>
			<div class="panel panel-default">
	
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2pc"><b>  Petty Cash</b> ( <span style="font-size:18px;"><?= $py_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			
                <div id="step2pc" class="panel-collapse collapse in123">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>SrNo.</th>
			<th>Company</th>
			<th>Location</th>
			<th>Date</th>
			<th>Type</th>
			<th>Total Amount</th>
			<th>Narration</th>
		    <th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
		</tr>
	</thead>
	<tbody>
<?php
	$sql="SELECT * from sma_pettycash where 1  and del !='Y' and status !='Completed' and approval_status !='Rejected' and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
	$result = mysqli_query($con, $sql);
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
		$comp_name 	= $r1['comp_name'];
		$comp_code 	= $r1['comp_code'];
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		$trans_type = $row['trans_type'];	
		$re_id = $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
		
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
		
		$modulePath = "petty/";	
		$baseurl1 	= $baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><?php echo $row['id'];?></td>
		<td width="06%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="08%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="12%"<?php echo $styl; ?>><?php echo $row['tally_narration'];?></td>
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

<?php } ?>							

<!-- Petty Cash end -->


<!--Travel Request Start -->						
				<?php
				$sql="SELECT * from sma_traval_approval where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$ta_cnt = mysqli_affected_rows($con);
				if($ta_cnt>0){	
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step3"><b>  Travel Request</b> ( <span style="font-size:18px;"><?= $ta_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
						
							
                <div id="step3" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	$sql="SELECT * from sma_traval_approval where 1 and del !='Y' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
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
		<td width="10%"<?php echo $styl; ?>><?php echo moneyFormatIndiaa($row['advance_amount']);?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['changed_by'];?></td>
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
<?php }  ?>	
			
            <!-- /.box-header -->
<!--Travel Request End-->	
      
	  
<!--Travel Expense Start -->						
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='T' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
			if($te_cnt>0){		
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step4"><b>  Travel Expense</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
						
                <div id="step4" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
			<th>Tally Status</th>
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='T' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
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
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($exp_amount);?></td>
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
	<?php } ?>														
            <!-- /.box-header -->
<!--Travel Expense End-->	



<!--Reimbursement Start -->						
				<?php
				$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='R' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' ";
				
				$result = mysqli_query($con,$sql);
				$te_cnt = mysqli_affected_rows($con);
				if($te_cnt>0){	
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step5"><b>  Reimbursement</b> ( <span style="font-size:18px;"><?= $te_cnt;?></span> )<span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step5" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Date</th>
			
			<th style="text-align:right;">Exp.Amount</th>
			<th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath = "travel_approval/";
	$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='R' and status !='Completed'  and approval_status !='Rejected'  and current_approver = '$userid' order by id desc ";
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
			
		$baseurl1 	= $baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($exp_amount);?></td>
		<td width="09%"><?php echo $row['changed_by'];?></td>
		<td width="09%"><?php echo $tally_status_a;?></td>
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
	<?php } ?>							
            <!-- /.box-header -->
<!--Reimbursement End-->


<!--Budget Adjust Start -->						
				<?php
				$sql="SELECT * from budget_adjust where 1 and status ='Submitted' and current_approver = '$userid' and project in ($comid) order by id desc ";				
				$result = mysqli_query($con,$sql);
				$bd_cnt = mysqli_affected_rows($con);
			if($bd_cnt>0){		
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step6"><b>  Budget Adjust </b> ( <span style="font-size:18px;"><?= $bd_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step6" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

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
	
	$sql="SELECT * from budget_adjust where 1 and status ='Submitted' and current_approver = '$userid'  and project in ($comid) order by id desc ";
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
	$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_head = $r2['budget_head'];
	
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
		<td width="0%" style="display:none123;"><input type="hidden" value='<?= $i;?>' ></td>
		
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $fin_year;?></td>
		<td width="10%"><?php echo $comp_code;?></td>
		<td width="15%"><?php echo $budget_name;?></td>
		<td width="15%"><?php echo $budget_head;?></td>
		<td width="10%"><?php echo $effect;?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($amount);?></td>
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
	<?php } ?>													
            <!-- /.box-header -->
<!--Budget Adjust End-->

<!--Budget Adjust From TO Start -->						
				<?php
				$sql="SELECT * from budget_adjust_from_to where 1 and status ='Submitted' and current_approver = '$userid' and project in ($comid) order by id desc ";				
				$result = mysqli_query($con,$sql);
				$bd_cnt = mysqli_affected_rows($con);
			if($bd_cnt>0){		
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step6a"><b>  Budget Adjust </b> ( <span style="font-size:18px;"><?= $bd_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
							
                <div id="step6a" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<td width="0%" style="display:none;">#</td>
			<th>Date</th>
			<th>Fin Year</th>
			<th>Company</th>
			<th>From Cost Center Group</th>
			<th>From Cost Center Sub Group</th>
			<th>To Cost Center Group</th>
			<th>To Cost Center Sub Group</th>
			<th style="text-align:right;">Amount</th>	
			<th>Decision</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from budget_adjust_from_to where 1 and status ='Submitted' and current_approver = '$userid'  and project in ($comid) order by id desc ";
//echo $sql;
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		
		$project = $row['project'];
		$sql = "select * from company where comp_id = '$project' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
										
		$budget_id_from = $row['budget_id_from'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		//$budget_head_from = $r2['budget_head'];
		$budget_name_from = $r2['name'];
	
		$budget_id_to = $row['budget_id_to'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		//$budget_head_to = $r2['budget_head'];
		$budget_name_to = $r2['name'];
		
		$budget_head_to = $row['budget_head_to'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_to = $r2['budget_head'];
		
		$budget_head_from = $row['budget_head_from'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_from' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_from = $r2['budget_head'];
	
		$account_year 	= $row['account_year'];
		
		$amount 		= $row['amount'];
	/* $approved_by 	= $row['approved_by'];
	
	$sql = " SELECT * from sma_user where id = '$approved_by' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$approved_by = $r2['username']; */
	
	$dated 			= date('d-m-Y', strtotime($row['dated']));
	
	$amount 		= $row['amount'];
	
	$baseurl1 = $baseurl.$modulePath1.'budget_adjust_from_to.php?sub=edit&id='.$row["id"];
	
	$i = $i +1;
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget_adjust_from_to.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%" style="display:none;"><?php echo $i;?></td>
		<td width="09%"><?php echo $dated;?></td>
		<td width="09%"><?php echo $account_year;?></td>
		<td width="06%"><?php echo $comp_code;?></td>
		<td width="15%"><?php echo $budget_name_from;?></td>
		<td width="15%"><?php echo $budget_head_from;?></td>
		<td width="15%"><?php echo $budget_name_to;?></td>
		<td width="15%"><?php echo $budget_head_to;?></td>
		<td width="08%" style="text-align:right;"><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="08%" ><?php echo $row['approval_status'];?></td>
		
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
	<?php } ?>													
            <!-- /.box-header -->
<!--Budget Adjust From TO End-->


<!--TASK Start -->						
				<?php
					$doc_type	= 'TK';
								
					$rcnt_pc = 0;
					$pending_cnt 	= '0';
					$submit_cnt 	= '0';
					$completed_cnt 	= '0';
					
					$sql = "select * from sma_pending_task a, payment_header b where 1 and a.payment_id = b.id and b.company_id in ($comid) ";
//echo $sql;
					//$sql .= ' order by payment_id desc ';						

		$modulePath1 = 'payment/';
			
		?>
		
			<?php
			$j=0;
		
		$ptext = '';
		$stext = '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$payment_id  		 = $row['payment_id'];
		$status_task  		 = $row['status_task'];
		
		$sql = "select a.*, c.module_name, c.module_code, b.st_flag , d.supp_id
					from sma_pending_task a, payment_header b, sma_module c , payment_details d
						where b.id = a.payment_id and c.id = a.document_id and b.id = d.payment_hdr_id and a.payment_id = '$payment_id' 
							order by a.id  ";
		
//echo $sql;		
		$q3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 = mysqli_fetch_array($q3);
		$supp_id			= $r3['supp_id'];
		$module_code		= $r3['module_code'];
		$st_flag			= $r3['st_flag'];
		
		if($st_flag=='S' || $st_flag=='R'){
			$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$draft_by		= $r3['draft_by'];
		}
		else if($st_flag=='A'){
			$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$our_po_ref_no 	 = $r3['our_po_ref_no'];
			$draft_by		 = $r3['draft_by'];
		}
		else if($st_flag=='D'){
			$sql = "SELECT * FROM sma_purchase_order where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$our_po_ref_no 	 	= $r3['our_po_ref_no'];
			$approval_memo_ref 	= $r3['approval_memo_ref'];
			$draft_by			= $r3['draft_by'];
		}
		else if($st_flag=='C' || $st_flag=='R' || $st_flag=='T'){
			$sql = "SELECT * FROM sma_travel_expenses where id = '$supp_id'  and del!='Y' ";		
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$approval_ref_no 	 = $r3['id'];
			$draft_by		= $r3['draft_by'];
		}
		else if($module_code=='I'){
			$sql = "SELECT b.* FROM sma_supplier_invoice a, sma_ipc b 
					where a.id = b.sma_invoice_no and a.id = '$supp_id' and b.del !='Y' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$draft_by		= $r3['draft_by'];
		}

		$aok ='';
		if( $draft_by == $user || $user=='Admin' || $role =='Accountant' || $role=='CXO' || $role=='Billdesk'){
			$aok='Y';
		}
		else {
			continue;
		}	
		
		if($status_task=='P'){
			$pending_cnt = $pending_cnt  + 1;
			$ptext = 'Pending';
		}
		else if($status_task=='S'){
			$submit_cnt = $submit_cnt  + 1;
			$stext = 'Submitted';
		}
		else if($status_task=='C'){
			$completed_cnt = $completed_cnt  + 1;
		}
		
		$baseurl1 	= $baseurl.$modulePath1.'task_status_change.php?sub=edit&payment_id='.$payment_id;
				
	?>
	
	<?php }
	
			if($pending_cnt>0 || $submit_cnt >0){		
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTK"><b>  Task </b> ( <span style="font-size:18px;"><?= $ptext. ' [' .$pending_cnt .']';?></span> ) ( <span style="font-size:18px;"><?= $stext. ' [' .$submit_cnt.']';?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
		<?php	
				$baseurla = '#';
				if($pending_cnt>0){
					$baseurla = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=P";
				}
				
				if($submit_cnt>0){
					$baseurlas = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=S";
				}
				if($completed_cnt>0){
					$baseurlac = $baseurl . $modulePath1 . "task_status_change.php?sub=edit&status=C";
				}
		?>					
                <div id="stepTK" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
					<div class="box-body">
						
						<div class="col-xs-2">
							<a href="<?php echo $baseurla;?>" style="font-size:18px;" target="_blank" class="btn btn-primary " id = "pending_color" ><span  ><b >Pending</b> <?php echo $sps.'['. $pending_cnt .']' .$sps;?></span></a>
						</div>
						
						<div class="col-xs-2">
							<a href="<?php echo $baseurlas;?>" style="font-size:18px;" target="_blank" class="btn btn-success " id = "pending_color" ><span  ><b >Submitted</b> <?php echo $sps. '['.$submit_cnt .']'.$sps;?></span></a>
						</div>
						
						<div class="col-xs-2">
							<a href="<?php echo $baseurlac;?>" style="font-size:18px;" target="_blank" class="btn btn-danger " id = "pending_color" ><span  ><b >Completed</b> <?php echo $sps. '['.$completed_cnt .']'.$sps;?></span></a>
						</div>
						
					</div>
			
				</fieldset>
			
				  </div>
				</div>
             </div>
	<?php } ?>													
            <!-- /.box-header -->
<!--TASK End-->



<!--Tender Start -->						
				<?php
				$sql="SELECT * from sma_tender_header where 1 and status ='Submitted' and current_approver = '$userid' and company_id in ($comid) order by id desc ";				
				$result = mysqli_query($con,$sql);
				$tn_cnt = mysqli_affected_rows($con);
			if($tn_cnt>0){		
				?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTN"><b>  Tender </b> ( <span style="font-size:18px;"><?= $tn_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
			<?php } 
			?>				
							
                <div id="stepTN" class="panel-collapse collapse ">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>#</th>
		<th>Tender No.</th>
		<th>Tender Title</th>
		<th>comp_code</th>
		<th>Post Dated</th>
		<th>Deadline</th>
		<th>Sent To</th>
		<th>Quote Received</th>
		<th>Status</th>
		
	</tr>
</thead>
<tbody>
<?php
	$modulePath1 = "tender/";
	
	$sql="SELECT * from sma_tender_header where 1 and status ='Submitted' and current_approver = '$userid'  and company_id in ($comid) order by id desc ";
//echo $sql;
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id = $row['company_id'];
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_name 	= $r2['comp_name'];
		$comp_code		= $r2['comp_code'];
		
		$remarks		= $row['remarks'];
		
		$to_supplier = $row['to_supplier'];
		$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$to_supplier = $r2['party_name'];
	
		$tender_hdr_id = $row['id'];
		$quote_sent_to = '0';
		$sql="SELECT * from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$quote_sent_to  = mysqli_affected_rows($con);
		
		$sql="SELECT * from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' and quotation_received = 'Y' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$quotation_received  = mysqli_affected_rows($con);
		
		$rid = $row['id'];
			
			$draft_by 	= $row['draft_by'];
			$status 	= $row['status'];
			
			$sql="SELECT * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";			
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$draft_by 	= $r1['username'];
			
			$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="8%"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $row['tender_title'];?></td>
		<td width="15%" <?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['created_date']));?></td>
		<td width="15%" <?php echo $styl; ?>><?php echo date('Y-m-d h:i A', strtotime($row['deadline_date'])). ' '. $row['deadline_time'];?></td>
		<td width="10%" style="text-align:right; " ><?= $quote_sent_to; ?></td>
		<td width="10%" style="text-align:right; " ><?= $quotation_received; ?></td>
		<td width="15%" <?php echo $styl; ?>><?php echo $status;?></td>
		
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
<!--Tender End-->

<!-- Budget Dashboard Start -->

<?php if($zoho_dashboard_view=='Y'){ ?>	
<div class="col-md-12">
        <h4>
        Budget Balances
      </h4>
<div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>SPV NAme</th>
			<th>Budget Group</th>
			<th>Fin.Year</th>
			
			<th  style="text-align:right;">Opening Budget</th>
			
			<th  style="text-align:right;">Blocked Budget</th>
			<th  style="text-align:right;">Used Budget</th>
			<th style="text-align:right;">Balance Budget</th>
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from sma_budget where project in ($comid)";
	
	if ($viewonly =='Y' ){
		$sql="SELECT * from sma_budget where project in ($comid)";
	}	
	
	if(!empty($comp_id)){
		$sql .= " and project = '$comp_id' ";
	}
	
	if(!empty($account_year)){
		$sql .= " and account_year = '$account_year' ";
	}
	
	if(!empty($budget_name)){
		$sql .= " and budget_name = '$budget_name' ";
	}
	
	$sql .= " AND budget_head in ( select id from sma_budget_subgroup where show_dashboard = 'Y' )";
	
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	$account_year = $row['account_year'];
	
	$project = $row['project'];
	$sql = "SELECT * from company where comp_id = '$project' ";
	$res = mysqli_query($con, $sql);
	//echo mysqli_error($con);
	$r2 = mysqli_fetch_array($res);
	$comp_name 	= $r2['comp_name'];
	$project 	= $r2['comp_code'];

	//$board_approved_budget = $row['board_approved_budget'];
    $board_approved_budget = '';
	$budget_head = $row['budget_head'];
	$budget_head_id = $row['budget_head'];
	
	$budget_name = $row['budget_name'];
	$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$budget_name = $r2['name'];
	$admin_flag 	= $r2['admin_flag'];
	
	$sql 	= "select * from sma_budget_subgroup where id = '$budget_head_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$budget_head 	= $r2['budget_head'];
	$admin_flag 	= $r2['admin_flag'];
	
	$styl = '';
	if($admin_flag=='Y' ){
		if( $user=='Admin' ){
			$styl = ' style = "color:red;" ';
		}
		else{
			$styl = ' style = "color:red;" ';
			//continue;	
		}
	}
											
	$open_budget 	= $row['total_budget'];
	$used_budget 	= $row['used_budget'];
	$blocked_budget	= $row['blocked_budget'];
	$adjustment_budget	= $row['adjustment_budget'];
	$locked 		= $row['locked'];
	
	$total_budget		= $open_budget + $adjustment_budget;
	
	
	$balance_budget = ($open_budget + $adjustment_budget) - ( $blocked_budget + $used_budget); 

	//$total_budget 	= $total_budget + $adjustment_budget;
	
	if($balance_budget<0){
		$balance_budget = number_format($balance_budget,2);
	}
	else {
		$balance_budget = moneyFormatIndiaa(round($balance_budget,2));
	}
	
	if($blocked_budget<0){
		$blocked_budget = number_format($blocked_budget,2);
	}
	else {
		$blocked_budget = moneyFormatIndiaa($blocked_budget,2);
	}
	
	if($used_budget<0){
		$used_budget = number_format($used_budget,2);
	}
	else {
		//$used_budget = moneyFormatIndiaa($used_budget,2);
		$used_budget = number_format($used_budget,2);
	}
	
	if($total_budget<0){
		$total_budget = number_format($total_budget,2);
	}
	else {
		$total_budget = moneyFormatIndiaa(round($total_budget,2));
	}
	
	$baseurl1 = $baseurl.$modulePath1.'budget.php?sub=edit&id='.$row["id"];
				
	?>
	<!--<a href="<?php echo $baseurl . $modulePath1 . "budget.php?sub=edit&id=". $row['id']?>" title="Edit">-->
	<tr>
		<td width="20%" ><?php echo $comp_name;?></td>
		<td width="20%" <?= $styl; ?> ><?php echo $budget_head;?></td>
		<td width="10%" <?= $styl; ?>><?php echo $account_year;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo ($total_budget);?></td>
		
		<td width="10%" style="text-align:right;"><?php echo ($blocked_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo ($used_budget);?></td>
		
		<td width="10%" style="text-align:right;"><?php echo ($balance_budget);?></td>
		
		<!--<td width="5%" style="text-align:right;">-->
		<!--<a href="./budget/budget.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" target="_blank" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		
		<!--</td>-->
    </tr>
	<!--</a>-->
	
	<?php }?>
</tbody> 
</table>
	</div>
</div>	
<?php } ?>

<!-- Budget Dashboard End -->	

<!--ZOHO Dashbaord Start -->						
	<?php if($zoho_dashboard_view=='Y'){ ?>			
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step_zoho"><b>  ZOHO Dashboard </b>  <span style="font-size:18px;"></span>  <span class="caret"></span> </a></h4>
				</div>
					
                <div id="step_zoho" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
						     <iframe frameborder=0 width="1250" height="600" src="https://analytics.zoho.in/open-view/352467000001195658"></iframe>
                        </div>
                        
                        </fieldset>
						</div>
                </div>
            <!-- /.box-header -->
    <?php } ?>        
<!--ZOHO Dashbaord End-->

 </div>
 
 
<?php 
//exit(); 
?>
<!--Start-->

	

<!--Start-->

			<!--<div class="box">-->
   <!--         <div class="box-header">-->
			<!--<div class="form-group ">-->
			<!--	<label for="Unused" class="col-sm-2 control-label"><h4><b>Unused Approvals</b></h4></label> -->
			<!--	<div class="col-sm-2">-->
					
			<!--		<Select id='FYEAR' class="form-control" name ="fyear" onchange="getUnusedTrans();">-->
			<!--			<option value=''>Select Fin.Year</option>-->
			<!--			<option value='2024'  >2024</option>-->
			<!--			<option value='2025' selected >2025</option>-->
			<!--			<option value='2026'  >2026</option>-->
			<!--		</select>-->
				
			<!--	</div>-->
			<!--</div>	-->
				
			  
            <!--</div>	-->
            <!-- /.box-header -->
            <div class="box-body no-padding">
           
			
			<span id="athang_ap_po_dash"><br></span>
			
            </div>
            <!-- /.box-body -->
          </div>
<!--End-->			

			
				</div>
			</div> 
		</div> 
	
	</section> 


	
</span> 
	  
<?php
 }
 
 
?>



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

		include("footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>


<script>
	function pending_dash(){
		
//		alert('Hello...pending_dash');
		
		document.getElementById("pending_color").innerHTML = "<b>PENDING </b>";
		document.getElementById("pending_color").style.backgroundColor = "#00a65a"
		
		//document.getElementById("approved_color").innerHTML = "<b>Approved </b>";
		//document.getElementById("approved_color").style.backgroundColor = "#00a65a"

		document.getElementById("rejected_color").innerHTML = "<b>Rejected </b>";
		document.getElementById("rejected_color").style.backgroundColor = "#dd4b39"

		var sub    = 'sub1';
		var trtype = 'P';
		var strURL = "dash_athang_pending_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub1:sub},function(result){
		      $('#athang_dash').html(result);
		});
	}
	
	function approved_dash(){
		
		document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00";

		//document.getElementById("approved_color").innerHTML = "<b>APPROVED </b>";
		//document.getElementById("approved_color").style.backgroundColor = "grey";

		document.getElementById("rejected_color").innerHTML = "<b>Rejected </b>";
		document.getElementById("rejected_color").style.backgroundColor = "#dd4b39";
		
//		alert('Hello...approved_dash');
		var sub    = 'sub2';
		var trtype = 'A';
		var strURL = "dash_athang_approved_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub2:sub},function(result){
		      $('#athang_dash').html(result);
		});
	}

	function rejected_dash(){
		
		document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00";
		
		//document.getElementById("approved_color").innerHTML = "<b>APPROVED </b>";
		//document.getElementById("approved_color").style.backgroundColor = "grey";

		document.getElementById("rejected_color").innerHTML = "<b>Rejected </b>";
		document.getElementById("rejected_color").style.backgroundColor = "#dd4b39";
		
		/* document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00";

		document.getElementById("approved_color").innerHTML = "<b>Approved </b>";
		document.getElementById("approved_color").style.backgroundColor = "#00a65a";

		document.getElementById("rejected_color").innerHTML = "<b>REJECTED </b>";
		document.getElementById("rejected_color").style.backgroundColor = "grey"; */
		
	//	alert('Hello$!...rejected_dash');
		var sub    = 'sub3';
		var trtype = 'R';
		var strURL = "dash_athang_rejected_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub3:sub},function(result){
		      $('#athang_dash').html(result);
		});

	}
 
 
	function jvpending_dash(){
		
		document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00"

		//document.getElementById("jvcreated_color").innerHTML = "<b>JVCreated </b>";
		//document.getElementById("jvcreated_color").style.backgroundColor = "#dd4b39"

		document.getElementById("jvpending_color").innerHTML = "<b>JVPending </b>";
		document.getElementById("jvpending_color").style.backgroundColor = "grey"
		
//		alert('Hello...rejected_dash');
		var sub    = 'sub5';
		var trtype = 'R';
		var strURL = "dash_athang_jvpending_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub4:sub},function(result){
		      $('#athang_dash').html(result);
		});
	}	
 
 	function jvcreated_dash(){
		
		document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00"

		document.getElementById("jvcreated_color").innerHTML = "<b>JVCreated </b>";
		document.getElementById("jvcreated_color").style.backgroundColor = "grey"

		document.getElementById("jvpending_color").innerHTML = "<b>JVPending </b>";
		document.getElementById("jvpending_color").style.backgroundColor = "#ff8c00"
		
//		alert('Hello...rejected_dash');
		var sub    = 'sub5';
		var trtype = 'R';
		var strURL = "dash_athang_jvcreated_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub5:sub},function(result){
		      $('#athang_dash').html(result);
		});
	}
	
	
	function pending_invoice_dash(){
		
		document.getElementById("pending_color").innerHTML = "<b>Pending </b>";
		document.getElementById("pending_color").style.backgroundColor = "#ff8c00"

		document.getElementById("jvcreated_color").innerHTML = "<b>JVCreated </b>";
		document.getElementById("jvcreated_color").style.backgroundColor = "ff8c00"

		document.getElementById("jvpending_color").innerHTML = "<b>JVPending </b>";
		document.getElementById("jvpending_color").style.backgroundColor = "#ff8c00"
		
		document.getElementById("jvpending_invoice_color").innerHTML = "<b>Pending Invoice</b>";
		document.getElementById("jvpending_invoice_color").style.backgroundColor = "#grey"
		
//		alert('Hello...rejected_dash');
		var sub    = 'sub5';
		var trtype = 'R';
		var strURL = "dash_pending_invoice_func.php";
//		alert('###1');
		$.post(strURL,{trtype:trtype,sub5:sub},function(result){
		      $('#athang_dash').html(result);
		});
	}
 
 
	function pending_AP_PO(company_id){
		
//		alert('Hello...pending_dash');
		
		var sub    			= 'sub1';
		var company_id 		= company_id;
		var fyear 			=  $("#FYEAR").val();
		
		var strURL = "dash_athang_pending_ap_po_func.php";
//		alert('###1');
		$.post(strURL,{company_id:company_id,fyear:fyear,sub1:sub},function(result){
		      $('#athang_ap_po_dash').html(result);
		});
	}
	
	function pending_AP_CE(company_id){
		
		var sub    			= 'sub2';
		var company_id 		= company_id;
		var fyear 			=  $("#FYEAR").val();
//alert(fyear + ' ' + sub);		
		var strURL = "dash_athang_pending_ap_po_func.php";
//		alert('###1');
		$.post(strURL,{company_id:company_id,fyear:fyear,sub2:sub},function(result){
		      $('#athang_ap_po_dash').html(result);
		});
	}
	
	
	
	function pending_PO_SI(company_id){
		
		var sub    = 'sub3';
		var company_id = company_id;
		var fyear 			=  $("#FYEAR").val();
		
		$('#athang_ap_po_dash').html('<b>Wait....</b>');
		
		var strURL = "dash_athang_pending_ap_po_func.php";
//		alert('###1');
		$.post(strURL,{company_id:company_id,fyear:fyear,sub3:sub},function(result){
		      $('#athang_ap_po_dash').html(result);
		});
	}
	
	function getUnusedTrans(){
		
		var sub    = 'sub4';
		var fyear 			=  $("#FYEAR").val();
		var strURL = "dash_athang_pending_ap_po_func.php";
//		alert('###1' + ' ' + fyear);
		$.post(strURL,{fyear:fyear,sub4:sub},function(result){
		      $('#athang_unsed_dash').html(result);
			  $('#athang_ap_po_dash').html('');
		});
	}	
	
</script>

</body>
</html>
