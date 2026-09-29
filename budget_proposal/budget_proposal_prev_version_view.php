<?php
if($_GET['sub']=='exit'){
	echo "<script>window.close();</script>";	
	exit();
}	

if($_GET['sub']=='edit'){
	include("../header_v.php");
}
else {
	include("../header.php");
}

$modulePath = "budget_proposal/budget_proposal.php?sub=list";

	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

$user   = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

?>


<style>
div.ex1 {
  
  width: 1450px;
  height: 520px;
  overflow: scroll;
}
</style>

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'edit'){
	
	
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Proposal 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget Proposal </li>
      </ol>
    </section>
<?php 
	$hdr_id = $_GET['hdr_id'];	
?>	
<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
            
				<div class="form-group">
					<label class="col-md-1 control-label">Cost Group:</label>
					<div class="col-md-5">
							<select class="form-control select2" name="budget_name" id="budget_NAME"  onchange="getBudgetProposal123();" >
								<option value=""> Select </option>
								<?php $sql = "select * from sma_budget_name where 1 order by name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['name'];?></option>
								<?php } ?>
							</select>
					</div>	
				</div>
						
				<input type="hidden" id ="hdr_ID" value="<?= $hdr_id; ?>">	
							
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
						<a href="budget_proposal_prev_version_view.php?sub=exit" class="btn btn-primary">Exit/Back</a>&nbsp;&nbsp;
					</span>
					
				</div>
				
				<span id="predit"></span>
				
			</div>
		</div>	
	
    <div class="box">
	
			<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Budget Proposal</a></li>
				  
				  <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				  
			</ul>
				
		<div class="tab-content">

			<div class="tab-pane active" id="tab_1">

<span id="getBudgetProposal">
<?php
	
	$sql="SELECT * FROM `sma_budget_proposal` where 1 and id = '$hdr_id' ";
//echo $sql ."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	
		$dated 				= $row['draft_date'];
		$company_id			= $row['company_id'];
		$account_year		= $row['account_year'];
		$current_version	= $row['current_version'];
		
		$status 			= $row['status'];
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		
		$tally_narration	= $row['tally_narration'];
		$tally_status		= $row['tally_status'];
		
		$readonly = '';
		if($status=='Completed' || $status =='Submitted'){
			$readonly = 'READONLY';
		}
		
		$sql 	= "select * from company where 1 and comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_name 	= $r2['comp_name'];

		$sql = " SELECT from_date, to_date, short_fy_code, finyear_prefix, status FROM `sma_financial_year` where status = 'Y' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$from_date 	= $r2['from_date'];
		$last_year		= date('Y', strtotime($from_date)) - 2;
		$last_year		.= '-'. (date('y', strtotime($from_date)) - 1);
		
		$current_year	= date('Y', strtotime($from_date))-1;
		$current_year	.= '-'.(date('y', strtotime($from_date)));
		
		$next_year		= date('Y', strtotime($from_date));
		$next_year		.= '-'.(date('y', strtotime($from_date)) + 1);
		
?>

		<input type="hidden" id ="company_ID" value="<?= $company_id; ?>">
		
		<form class="form-horizontal">
			<div class="form-group" >
									
				<label class=" control-label col-md-1" style='font-size:18px;' >Date</label>					
				<div class="col-md-2">
					
					<input type="text" class="form-control" style='font-size:18px;' readonly value="<?php echo date('d-m-Y', strtotime($dated)); ?>" >
				</div>
				
				<label class=" control-label col-md-1" style='font-size:18px;' >Company</label>					
				<div class="col-md-4">
					
					<input type="text" class="form-control" style='font-size:18px;' readonly value="<?php echo $company_name; ?>" >
				</div>
				
				<label class=" control-label col-md-1" style='font-size:18px;' >Account&nbsp;Year</label>					
				<div class="col-md-2">
					
					<input type="text" class="form-control" style='font-size:18px;' readonly value="<?php echo $account_year; ?>" >
				</div>
				<div class="col-md-1">
					<input type="text" class="form-control" style='font-size:16px;text-align:left;' readonly value="Version <?= $current_version;?>&nbsp;" >
				</div>
				
			</div>
			
		</form>
		
	<!--	<div class="pull-left123">		
<?php		echo "<p style='font-size:18px;margin-left:10px;'>"."<b>Date : </b>".date('d-m-Y', strtotime($dated)) . '&nbsp; <b> Company : </b>' .	$company_name . '&nbsp; <b>Account Year : </b>' . $account_year . "&nbsp;&nbsp; <b>Version : </b>". $current_version. ' ' . 
		"<span style='margin-left:280px;'> Status :</span> <b style='font-size:18px;color:red;'> ".$status. '</b>'.'</p>'; ?>
		</div>
-->		

<div class="ex1abc">
    	
    <table id="prtablea123" class="table table-bordered table-striped" width="100%">
		<thead>
		<tr><td width="3%" style="text-align:right;vertical-align:top;">#</td>
			<th width="12%" style="text-align:left;vertical-align:top;">Cost Sub Group </th>
<!--			<th width="10%" style="text-align:right;vertical-align:top;"><?= $last_year;?> Total</th>
			<th width="10%" style="text-align:right;vertical-align:top;"><?= $last_year;?> Actual</th>-->
			
			<th width="10%" style="text-align:right;vertical-align:top;">Board Approved Budget</th>
			<th width="10%" style="text-align:right;vertical-align:top;"><?= $current_year;?> Total</th>
			<th width="10%" style="text-align:right;vertical-align:top;">Upto 31-12-23 Actual</th>
			<th width="10%" style="text-align:right;vertical-align:top;"><?= $current_year;?> Actual Tally </th>
			<th width="10%" style="text-align:right;vertical-align:top;"><?= $current_year;?> Balance</th>
			<th width="10%" style="text-align:right;vertical-align:top;"><?= $next_year;?> Provisional</th>
			<th width="10%" style="text-align:right;vertical-align:top;"> Proposed Total Budget</th>
			
			<th width="15%" style="text-align:left;vertical-align:top;"> Remark</th>
			
		</tr>
		</thead>
<tbody>
<?php
	$modulePath1 = "budget_proposal/";
	
	$readonly1='READONLY';
	$readonly2='READONLY';
	$readonly3='READONLY';
	$readonly4='READONLY';
	$readonly5='READONLY';
	$readonly='READONLY';
	
	$bgcolor1 = '';
	$bgcolor2 = '';
	$bgcolor3 = '';
	$bgcolor4 = '';
	$bgcolor5 = '';
	
	$color1 = '';
	$color2 = '';
	$color3 = '';
	$color4 = '';
	$color5 = '';
	

	
	$sql="SELECT * from sma_budget_proposal_details where 1 and hdr_id = '$hdr_id' ";
	
	$sql .= " order by id ";
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$cost_group_id		 	= $row['cost_group_id'];
		$cost_subgroup_id 		= $row['cost_subgroup_id'];
		
		$last_year_open_bal 		= $row['last_year_open_bal'];
		$last_year_comsume			= $row['last_year_comsume'];
		
		$current_year_op_bal 		= $row['current_year_op_bal'];
		$current_year_comsume		= $row['current_year_comsume'];
		
		$current_year_provision		= $row['current_year_provision'];
		$next_year_budget			= $row['next_year_budget'];
		
		$actual_tally				= $row['actual_tally'];
		$remarks					= $row['remarks'];
		$board_approved_budget		= $row['board_approved_budget'];
		$board_approved_budget_tot	= $board_approved_budget_tot + $board_approved_budget;
		
		$sql 	= "SELECT * FROM sma_budget_name where 1 and id = '$cost_group_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cost_group				= $r2['name'];
		
		$sql 	= "SELECT * FROM sma_budget_subgroup WHERE 1 AND id = '$cost_subgroup_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cost_subgroup				= $r2['budget_head'];
		$budget_code				= $r2['budget_code'];
		
		$rid						= $row['id'];

		$current_year_provision_total = $current_year_provision_total +  $current_year_provision;
		$next_year_budget_total	 	  = $next_year_budget_total +  $next_year_budget;
		
		$last_year_open_bal_v 		= ($last_year_open_bal);
		$last_year_comsume_v 		= ($last_year_comsume);
		
		$current_year_op_bal_v 		= ($current_year_op_bal);
		$current_year_comsume_v 	= ($current_year_comsume);
		
		/* $sql 	= " SELECT * FROM sma_budget WHERE 1 AND budget_name = '$cost_group_id' AND budget_head = '$cost_subgroup_id' and account_year = '$short_fy_code' and project = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$current_year_comsume		= $r2['used_budget'];
		$current_year_comsume_v 	= ($current_year_comsume); */
		
		$current_year_balance_v		= ($current_year_op_bal - $current_year_comsume);
		
		$last_year_open_bal_tot		= $last_year_open_bal_tot + $last_year_open_bal_v;
		$last_year_comsume_tot		= $last_year_comsume_tot + $last_year_comsume_v;	
		$current_year_op_bal_tot	= $current_year_op_bal_tot + $current_year_op_bal_v;	
		$current_year_comsume_tot	= $current_year_comsume_tot + $current_year_comsume_v;	
		$current_year_balance_tot	= $current_year_balance_tot + $current_year_balance_v;	
		
		$actual_tally_tot			= $actual_tally_tot + $actual_tally;
		
		$baseurl1 = $baseurl.$modulePath1.'budget_proposal.php?sub=edit&id='.$row["id"];

	?>
	<tr>
		<td width="3%"  style="text-align:right;"><?= ++$ii;?></td>
		<td width="12%" ><?php echo $cost_subgroup;?></td>
	
		<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($board_approved_budget);?></td>
		<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($current_year_op_bal_v);?></td>
		<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($current_year_comsume_v);?></td>
		
		<td width="10%" style="color:<?= $colora;?>;text-align:right;" border='1' bgcolor="<?= $bgcolor;?>" contenteditable<?= $readonly;?>="true"
			onBlur="saveToDatabase_ln(this,'actual_tally','<?= $hdr_id; ?>', '<?= $rid; ?>');" ><?php echo moneyFormatIndiaa($actual_tally);?>
		</td>
		
		<td width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($current_year_balance_v);?></td>
		<td width="10%" style="color:<?= $colora;?>;text-align:right;" border='1' bgcolor="<?= $bgcolor;?>" contenteditable<?= $readonly;?>="true"
			onBlur="saveToDatabase_ln(this,'current_year_provision','<?= $hdr_id; ?>', '<?= $rid; ?>', '<? $current_year_op_bal;?>', '<?= $current_year_comsume;?>');"  ><?php echo moneyFormatIndiaa($current_year_provision);?>
		</td>
		
		<td width="10%" style="color:<?= $colora;?>;text-align:right;" border='1' bgcolor="<?= $bgcolor;?>" contenteditable<?= $readonly;?>="true" onBlur="saveToDatabase_ln(this,'next_year_budget','<?= $hdr_id; ?>', '<?= $rid; ?>');" ><?php echo moneyFormatIndiaa($next_year_budget);?>
		</td>
		
		<td width="15%" style="color:<?= $colora;?>;text-align:left;" border='1' bgcolor="<?= $bgcolor;?>" contenteditable<?= $readonly;?>="true" onBlur="saveToDatabase_ln(this,'remarks','<?= $hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $remarks;?>
		</td>
	
		
    </tr>
</tbody> 

	<?php }
	
	?>
<tfoot> 
		
</tfoot> 
</table>
	
	</span>
	<table id="prtablea123" class="table table-bordered table-striped" width="100%">
	
	<tr>
		<td width="3%"  style="text-align:right;"></td>
		<th width="12%" >Total</th>
<!--		<td width="10%" style="text-align:right;" ><?php echo number_format($last_year_open_bal_tot,2);?></td>
		<td width="10%" style="text-align:right;" ><?php echo number_format($last_year_comsume_tot,2);?></td>
-->		
		<th width="10%" style="text-align:right;" ><?php echo number_format($board_approved_budget_tot,2);?></th>
		<th width="10%" style="text-align:right;" ><?php echo number_format($current_year_op_bal_tot,2);?></th>
		<th width="10%" style="text-align:right;" ><?php echo number_format($current_year_comsume_tot,2);?></th>
		<th width="10%" style="text-align:right;" ><?php echo number_format($actual_tally_tot,2);?></th>
		
		<th width="10%" style="text-align:right;" ><?php echo number_format($current_year_balance_tot,2);?></th>
		<th width="10%" style="text-align:right;" ><?php echo number_format($current_year_provision_total,2);?></th>
		<th width="10%" style="text-align:right;" ><?php echo number_format($next_year_budget_total,2);?></th>
		<th width="20%" style="text-align:right;" ></th>
		
    </tr>
</table>
	</div>
 
<div class="box-footer">
	<form class="form-horizontal" action="budget_proposal.php?sub=edit" method="post" enctype="multipart/form-data" >
		<div class="col-sm-12 text-right">
					<input type="hidden" id = "hdr_id" name = "hdr_id"  value="<?= $hdr_id;?>" >
					
					<input type="hidden" id = "status" name="status" value="<?= $status;?>" >
					
					
<?php
								$role			= $_SESSION['role'];
								$userid   		= $_SESSION['usrid'];
									
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag = '';
									if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' ||
										$userid == $approver_4 && $approver_4_status=='Submitted' ||
										$userid == $approver_5 && $approver_5_status=='Submitted' ||
										$userid == $approver_6 && $approver_6_status=='Submitted' ||
										$userid == $approver_7 && $approver_7_status=='Submitted' ||
										$userid == $approver_8 && $approver_8_status=='Submitted' ){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted'){
										$approver_flag='Y';
									}
									
										
									}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
									if( $status=='Completed' && $user == 'Admin' ){
							?>
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
									
							<?php
									}
								}
							?>
							
							
							
<?php
	if($status=='Draft' ){
?>	
		<span class='hidesend' >	
			<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
			<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		</span>	
<?php } ?>
								

<?php  
	if( $status == 'Submitted' ){
?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><BR>
									<label class="control-label"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><BR>
									<label class="control-label"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><BR>
									<label class="control-label"><?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><BR>
									<label class="control-label"><?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><BR>
									<label class="control-label"><?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_7)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_7' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_7_name = $rw['username'];
								$approver_7_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label><BR>
									<label class="control-label"><?= $approver_7_name . " <BR> " . $approver_7_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_8)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_8' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_8_name = $rw['username'];
								$approver_8_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label><BR>
									<label class="control-label"><?= $approver_8_name . " <BR> " . $approver_8_role; ?>
									</label>
								</div>
					<?php	
							}
					?>		
							</div>
<?php		
						}
?>
						
<?php
	//echo $status ."<BR>";
		if( $status == 'Draft' ){
?>
						<span id="getapprover">
								<div class="box-footer">
								
							<?php	
								
								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
									<select class="form-control  approver_2" name="approver_2" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
									<select class="form-control  approver_3" name="approver_3" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 
								if(!empty($approver_4)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
									<select class="form-control  approver_4" name="approver_4" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } ?>	
							<?php if(!empty($approver_5)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
									<select class="form-control  approver_5" name="approver_5" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							<?php 
								if(!empty($approver_6)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
									<select class="form-control  approver_6" name="approver_6" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_7)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
									<select class="form-control  approver_7" name="approver_7" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_8)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
									<select class="form-control  approver_8" name="approver_8" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							
								
								<BR>
								
							</div>
						
						</span>
				<?php } ?>
				
		</div>

	</form>
	
</div>	


			</div>				
						
								
<!-- Start -->

			<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $hdr_id;
						 			$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BP' order by id asc ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
							
						</b>	
					</span>
								</span>
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtableb" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BP' order by id desc ";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$make_by_flag		= $r1['make_by_flag'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['primary_role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
			</div>

<!-- End -->

			</div>
								
						</div>	
				

<?php } ?>


<!--Make to DraftPopup-->
<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										
										<input type="hidden" name="hdr_id" id="revenue_hdr_idD" value="<?php echo $hdr_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
									<!--	<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>-->
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTallyLabel">Do you want create tally journal?</h4>
            </div>
            <div class="modal-body123">
                <section class="content123">
                    <div class="row123">
                        <form class="form-horizontal" action="#" method="POST" enctype="multipart/form-data">
						
                            <input type="hidden" id="modeT" value='Tally'>
							<input type="hidden" id="rev_idT" value="<?php echo $hdr_id;?>">
						
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">No</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry" onclick="tallyentry123();" >Yes</button>
            </div>
			
                </section>
            </div>
        </div>
    </div>
</div>


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
								<form class="form-horizontal">
                                        
										<input type="hidden" name="hdr_id" id="revenue_hdr_idE" value="<?php echo $hdr_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$r3   = mysqli_fetch_array($query);
											$approver = $r3['id'];

										?>
										
										<input type="hidden" name="hdr_id" id="revenue_hdr_idR" value="<?php echo $hdr_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
																				
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    
									</form>	
								
								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  
	  

<?php 	
		include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>

    $(function () {
        $("#prtable").DataTable();
		$("#prtablea").DataTable();
		$("#prtableb").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


	function saveToDatabase_ln(editableObj,column,hdr_id,line_no, total_budget, used_budget) {
		    
		var editableObj = editableObj.innerHTML;	
//alert("UPDATE `enqdetail` set " + editableObj + used_budget +  ' <<>> ' + total_budget);
		var balance_budget = (total_budget - used_budget);
//alert(balance_budget + ' ' + editableObj );		
		if( balance_budget < editableObj ){
			alert('Provisional budget should not allow more than balance budget ! ' + balance_budget + ' < ' + editableObj );
			return;
		}
		
		var sdivURL = "saveauditjobdtl.php";
			$.post(sdivURL,{column:column,editval:editableObj,hdr_id:hdr_id,line_no:line_no },function(result){
				//alert('Hello...');
				//$('#addbom_dtl123').html(result);
			});
			
	}
	
	function updateAudit_Status(id){
			
		var sub = 'sub15';
		var audit_status	 		=  $("#audit_status"+id).val();
	//	alert(id + ' ' +audit_status);
		
		audit_status = '';
		if (document.getElementById('audit_status'+id).checked) {
		    audit_status = document.getElementById('audit_status'+id).value;
			if(audit_status==id){
				audit_status = 'Y';	
			}	
		}
		
	//	alert(id + ' ' +audit_status);
	//	alert('Hello !');
		var strURL = "gin_func.php";
		$.post(strURL,{ audit_status:audit_status,
						id:id,
						sub15:sub},
						function(result){
		      //$('#predit').html(result);
		});
	}	

	function getapprover(){
		
		var company_id    	= document.getElementById("company_ID").value;
		var trans_type    	= '74';
		//var trans_type    	= document.getElementById("trans_type").value;
		//var checker_value   = document.getElementById("checker_value").value;
		
		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + ' ' + trans_type + ' ' + company_id );
		
		var strURL = "bp_func.php";
		$.post(strURL,{company_id:company_id,trans_type:trans_type,sub24:sub},function(result){
		      $('#getapprover').html(result);
			
		});
		
	}

	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
	function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
//		alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function gettallyStatus(id){
	
		var sub    = 'sub6';
//alert(sub);
		var hdr_id    	= document.getElementById("hdr_id").value;
		var tally_status = '';
		if (document.getElementById('tally_status').checked) {
		    tally_status = document.getElementById('tally_status').value;
		}
		
//		alert(hdr_id+ ' ' + tally_status + ' ' + sub);
		var strURL = "bp_func.php";
		$.post(strURL,{tally_status:tally_status,hdr_id:hdr_id,sub6:sub},function(result){
		   //   $('#tallyentry').html(result);
			
		});
		
	}
	
	$("#addTallyEntry").on("click", function(e){
		
        var sub 	 = 'sub2';
		var mode 	 = $("#modeT").val();
		var rev_id 	 = $("#rev_idT").val();		
//alert(re_id);
		var doc_type = 'RV';
		
		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ mode:mode,rev_id:rev_id,doc_type:doc_type,
							sub2:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1 || row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}	
		if(row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==7 || row_affected==8){
			if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if( row_affected==8){
			if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		
		return false;
		
	}
	
	
	 $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var hdr_id		 	=  $("#revenue_hdr_idE").val();
		var approver 		=  $("#approverC").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		
//alert(hdr_id + ' ' + approver + ' ' + status + ' ' +  mode );
//return;
		 $('#approvalAuthority').modal('hide');
		var strURL = "bp_func.php";
		$.post(strURL,{ hdr_id:hdr_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	

    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var hdr_id		=  $("#revenue_hdr_idR").val();
		var status 				=  $("#statusR").val();
		var remarks				=  $("#remarksR").val();
		
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+po_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "bp_func.php";
		$.post(strURL,{ hdr_id:hdr_id,
						mode:mode,
						statusap:mode,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});
	
	$("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var hdr_id 	=  $("#revenue_hdr_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + hdr_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ hdr_id:hdr_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	function getBudgetProposal(){
		
		var sub = 'sub16';
//alert('Hello...' );
		var budget_name 	= $("#budget_NAME").val();
		var company_id 		=  $("#company_ID").val();
		var hdr_id			=  $("#hdr_ID").val();
//alert(budget_name + ' '  + company_id + ' ' + hdr_id);
		var strURL = "bp_func.php";
//alert(budget_name + ' <<>> '  + company_id + ' >><< ' + hdr_id);		
		$.post(strURL,{ budget_name:budget_name,
						hdr_id:hdr_id,
						sub16:sub,
						company_id:company_id},
						function(result){
		      $('#getBudgetProposal').html(result);
		});
		
	}
	
</script>

</body>
</html>
