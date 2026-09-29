<?php
session_start();
include("../dbcon.php");
include("../baseurl.php");

if($_GET['sub']=='pro'){
	
	$comid  		= $_SESSION['comid'];
	$role			= $_SESSION['role'];
	$readonly		= $_SESSION['readonly'];
	$user_category	= $_SESSION['user_category'];
					
	$sql = "truncate analysis_pending";
	mysqli_query($con, $sql);
	
//MRN
	$sql 	= "SELECT * from sma_purchase_req where id > 0 and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'PR';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'PR';
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, company_id, status) 
					VALUES ('$module','$doc_id','$pending_with', '$company_id', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//MRN
	
//GRN
	$sql 	= "SELECT * from sma_supplier_invoice where id > 0 and grn_status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$doc_id			= $row['id'];		
		$module			= 'GR';
		$company_id		= $row['company_id'];
		
		include "analysis_pending_common.php";
		
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, company_id, status) 
					VALUES ('$module','$doc_id','$pending_with', '$company_id', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//GRN
	
//Approval Memo	Start
	$sql 	= "SELECT * from sma_approval_memo where id > 0 and status = 'Submitted' and del!='Y' and company in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'AP';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		
		$company_id		= $row['company'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, company_id, status) 
					VALUES ('$module','$doc_id','$pending_with', '$company_id', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Approval Memo	End

//Purchase Order	Start
	$sql 	= "SELECT * from sma_purchase_order where id > 0 and status = 'Submitted' and del!='Y' and project in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'PO';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'PO';
		$company_id		= $row['project'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Purchase Order	End

//Supplier Invoice	Start
	$sql 	= "SELECT * from sma_supplier_invoice where id > 0 and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'SI';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];	
			
		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Supplier Invoice	End

//Company Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'C' and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'CE';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Company Expense	End

//Travel Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'T' and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'TE';
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Travel Expense	End

//Regular Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'R' and status = 'Submitted' and del!='Y' and company_id in ($comid)";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){

		$module			= 'RE';		
		include "analysis_pending_common.php";
		
		
		$doc_id			= $row['id'];		
		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Regular Expense	End

//Payment Start
	$sql 	= "SELECT * from payment_header where id > 0 and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'PY';
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);

		
	}
	
//Payment End

//Petty Cash Start
	$sql 	= "SELECT * from sma_pettycash where id > 0 and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'PC';
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Petty Cash End

//IPC Start
	$sql 	= "SELECT * from sma_ipc where id > 0 and status = 'Submitted' and del!='Y' and sma_comp_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'IP';
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//IPC End

//Travel Request Start
	$sql 	= "SELECT * from sma_traval_approval where id > 0 and status = 'Submitted' and del!='Y' and company_id in ($comid) ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		$module			= 'TA';
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$company_id		= $row['company_id'];
		$sql = "INSERT INTO analysis_pending (module, doc_id, company_id, pending_with, status) 
					VALUES ('$module','$doc_id',  '$company_id,', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Travel Request End

	echo "Process Over...";
//https://athaang.in/
	$baseurl1= $baseurl."report/analysis_pending.php?sub=list";
	echo "<script>window.location.href='$baseurl1';</script>";

}
?>

<?php

if($_GET['sub']=='list'){
	
include("../header.php");
$modulePath = "report/";

$userid   	= $_SESSION['usrid'];
$help_code = $modulePath.'analysis_pending.php';
include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");
	
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h4>
        Pending Approvals<small></small>
      </h4>
      <!--<ol class="breadcrumb">
		
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Pending Approvals</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header123">
			</div>
		
            <!-- /.box-header -->
            <div class="box-body">
              
<?php
	
	$sql = " TRUNCATE analysis_pending_matrix";
	mysqli_query($con, $sql);
	
	$sql="SELECT pending_with FROM `analysis_pending` where 1 group by  pending_with ";
	$sql .= ' order by pending_with, module ';
	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$pending_with		= $row['pending_with'];
			
			$sql = " INSERT INTO analysis_pending_matrix ( pending_with ) VALUE ('$pending_with' )";
			mysqli_query($con, $sql);
			
	}
			
	$sql="SELECT module, pending_with, count(*) as cnt FROM `analysis_pending` where 1 group by module, pending_with ";
	$sql .= ' order by pending_with, module ';
	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$count 				= $row['cnt'];
			$module 			= $row['module'];
			$pending_with		= $row['pending_with'];
			
			if($module=='PR'){
				$sql = " UPDATE analysis_pending_matrix SET PR = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='GR'){
				$sql = " UPDATE analysis_pending_matrix SET GR = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='AP'){
				$sql = " UPDATE analysis_pending_matrix SET AP = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='PO'){
				$sql = " UPDATE analysis_pending_matrix SET PO = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='SI'){
				$sql = " UPDATE analysis_pending_matrix SET SI = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='CE'){
				$sql = " UPDATE analysis_pending_matrix SET CE = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='TE'){
				$sql = " UPDATE analysis_pending_matrix SET TE = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='RE'){
				$sql = " UPDATE analysis_pending_matrix SET RE = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='TA'){
				$sql = " UPDATE analysis_pending_matrix SET TA = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='PY'){
				$sql = " UPDATE analysis_pending_matrix SET PY = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='IP'){
				$sql = " UPDATE analysis_pending_matrix SET IP = '$count' where pending_with = '$pending_with' ";
			}
			if($module=='PC'){
				$sql = " UPDATE analysis_pending_matrix SET PC = '$count' where pending_with = '$pending_with' ";
			}
			
			mysqli_query($con, $sql);
			
		}	
		
	$module_hdr ='';	
	$sql="SELECT sum(PR) as PR, sum(GR) as GR, sum(AP) as AP, sum(PO) as PO,sum(SI) as SI,sum(CE) as CE,sum(PY) as PY,sum(TE) as TE,sum(RE) as RE,sum(TA) as TA,sum(IP) as IP,sum(PC) as PC FROM `analysis_pending_matrix` where 1  ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
				
			$PR 				= $row['PR'];
			$GR 				= $row['GR'];
			$AP 				= $row['AP'];
			$PO 				= $row['PO'];
			$SI 				= $row['SI'];
			$CE 				= $row['CE'];
			$PY 				= $row['PY'];
			$TE 				= $row['TE'];
			$RE 				= $row['RE'];
			$TA 				= $row['TA'];
			$IP 				= $row['IP'];
			$PC 				= $row['PC'];
			
			$GPR += $PR ;
			$GGR += $GR ;
			$GAP += $AP ;
			$GPO += $PO ;
			$GSI += $SI ;
			$GCE += $CE ;
			$GPY += $PY ;
			$GTE += $TE ;
			$GRE += $RE ;
			$GTA += $TA ;
			$GIP +=	$IP ;
			$GPC += $PC;
			
			if($PR>0){
				$module_name = 'PRN';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			if($GR>0){
				$module_name = 'GRN';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			if($AP>0){
				$module_name = 'NOA';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			if($PO>0){
				$module_name = 'Purchase Order';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($SI>0){
				$module_name = 'Invoice Against GRN';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($CE>0){
				$module_name = 'Invoice Against OpEx';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($TE>0){
				$module_name = 'Travel Expense';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($RE>0){
				$module_name = 'Reimbursement';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($TA>0){
				$module_name = 'Travel Approval';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($PY>0){
				$module_name = 'Payment';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($PC>0){
				$module_name = 'Petty Cash';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($IP>0){
				$module_name = 'IPC';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			
		
	}

	
?>
	
	<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#A4F5CA;">
                    <!--<th></th>-->
					<th>Pending With</th>
                    <?= $module_hdr; ?>
					
				</tr>
                </thead>
                <tbody>
<?php				
	$sql="SELECT * FROM `analysis_pending_matrix` where 1 and pending_with >0 order by pending_with ";
//echo $sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
					
		while($row = mysqli_fetch_array($result)){
				
			$PR 				= $row['PR'];
			$GR 				= $row['GR'];
			$AP 				= $row['AP'];
			$PO 				= $row['PO'];
			$SI 				= $row['SI'];
			$CE 				= $row['CE'];
			$PY 				= $row['PY'];
			$TE 				= $row['TE'];
			$RE 				= $row['RE'];
			$TA 				= $row['TA'];
			$IP 				= $row['IP'];
			$PC 				= $row['PC'];
			
			$GTOT = $PR + $GR + $AP + $PO + $SI + $CE + $PY + $TE + $RE + $TA + $IP + $PC;
			$GPR += $PR ;
			$GGR += $GR ;
			$GAP += $AP ;
			$GPO += $PO ;
			$GSI += $SI ;
			$GCE += $CE ;
			$GPY += $PY ;
			$GTE += $TE ;
			$GRE += $RE ;
			$GTA += $TA ;
			$GIP +=	$IP ;
			$GPC += $PC;
			
			if($GPR>0){
				$module 			= 'PR';
			}
			if($GGR>0){
				$module 			= 'GR';
			}
			if($GAP>0){
				$module 			= 'AP';
			}
			if($GPO>0){
				$module 			= 'PO';
			}
			if($GSI>0){
				$module 			= 'SI';
			}
			if($GCE>0){
				$module 			= 'CE';
			}
			if($GTE>0){
				$module 			= 'TE';
			}
			if($GRE>0){
				$module 			= 'RE';
			}
			if($GTA>0){
				$module 			= 'TA';
			}
			if($GPY>0){
				$module 			= 'PY';
			}
			if($GIP>0){
				$module 			= 'IP';
			}
			if($GPC>0){
				$module 			= 'PC';
			}
			
			$pending_with		= $row['pending_with'];
				
			$sql = "select * from sma_user where id = '$pending_with' ";
			$q22  = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($q22);
			$pending_with_name  = $r22['username'];
						
			$j = $j +1;			
			$pending_with1		= $pending_with;
			$module1			= $module;
			
		?>
	
		<?php if($GTOT>0){ ?>
		<tr>
			<!--<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>-->
			<td width="20%" style="background-color:#94EABD;color:black;" ><?php echo $pending_with_name;?></td>
		<?php } ?>	
		<?php if($GPR>0){ 
				$module1 ='PR';
					$disbtn = '';
					if($PR>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$PR		= '';
					}
				?>	
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $PR;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GGR>0){ 
				$module1 ='GR';
					$disbtn = '';
					if($GR>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$GR		= '';
					}
				?>	
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $GR;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		
		<?php if($GAP>0){ 
				$module1 ='AP';
					$disbtn = '';
					if($AP>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$AP		= '';
					}
				?>	
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $AP;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>	
		<?php if($GPO>0){ $module1 ='PO';
					$disbtn = '';
					if($PO>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$PO		= '';
					}
		?>
			<th width="10%" style="text-align:center;" ><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>" style="text-align:center;"><?php echo $PO;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GSI>0){ $module1 ='SI';
					$disbtn = '';
					if($SI>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$SI		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $SI;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GCE>0){ $module1 ='CE';
					$disbtn = '';
					if($CE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$CE		= '';
					}		
			?>
			
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $CE;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GTE>0){ $module1 ='TE';
					$disbtn = '';
					if($TE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$TE		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $TE;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GRE>0){ $module1 ='RE';
					$disbtn = '';
					if($RE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$RE		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $RE;?> </a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GTA>0){ $module1 ='TA';
					$disbtn = '';
					if($TA>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$TA		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $TA;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GPY>0){ $module1 ='PY';
					$disbtn = '';
					if($PY>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$PY		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $PY;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GIP>0){ $module1 ='IP';
					$disbtn = '';
					if($IP>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$IP		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $IP;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		<?php if($GPC>0){ $module1 ='PC';
					$disbtn = '';
					if($PC>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$PC		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListPending<?php echo $pending_with1;?><?php echo $module1;?>"><?php echo $PC;?></a>
				<?php include "modalListPending.php"; ?>
			</th>
		<?php } ?>
		</tr>
<?php } 
?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

</div>
<!-- ./wrapper -->
<?php
include("../footer.php");
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

</script>

</body>
</html>

<?php

 } ?>


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
