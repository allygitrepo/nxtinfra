<?php session_start();
	include('dbcon.php');
?>

<?php
	
	$comid  = $_SESSION['comid'];
	$role	= $_SESSION['role'];
	$user_category	= $_SESSION['user_category'];
	$user   = $_SESSION['user'];
	$usrid	= $_SESSION['usrid'];

if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
		
		$pcnt = 0;

								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD'  || $role == 'HOD - Account' || $role == 'CXO' || $role =='COO' ){
									$sql ="SELECT approval_status, count(*) as cnt from sma_approval_memo DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
											 and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'AP'  group by doc_id) ) DS1 
											 ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared')";
								//echo $sql;			 //and reviewed_by = '$usrid'
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_approval_memo` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = 'AP' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = 'AP' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_approval_memo.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
								}
								
								if($user == 'Admin'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status in('Pending', 'Verified','Prepared')  ";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
						//echo $role . ' <<>> ' . $sql;		
//All Pending
								$allcnt=0;
								if ($role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and draft_by = '$user' and status not in ('Completed', 'Draft') ";
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and status not in ('Completed', 'Draft') ";	
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.status != 'Draft' ";	
								//echo $sql; //and create_by = '$usrid'
								
								}
								
								if($user == 'Admin' || $role == 'CXO' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where status not in ('Completed', 'Draft')";
							//echo $sql;
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts 	= $r1['approval_status'];
									$allcnt = $r1['cnt'];
								}
//Approved
								
								$acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD'){
									//$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Approved') 
									//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Approved'))  
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' 	and status in('Approved')) or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') ";		
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Approved' ";
								}	
						
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Approved'  and draft_by = '$user'  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
								$rcnt = 0;
								if ($role =='Checker'  || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Rejected')) 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Rejected')) or draft_by = '$user' )  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Rejected' ";
								}
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status = 'Rejected' and draft_by = '$user'  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
								
?>		
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingAP()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingAP()" ><?php echo $allcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveAP()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectAP()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			<h3> Approval Note </h3>
		</div>
		
<?php		
        //echo $value;
    }
	
	
//Purchase Order	
if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
		
								$pcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
//									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
//										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
//										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type'  group by doc_id) ) DS1 
//										ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";	
									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type'   group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";		
	//and reviewed_by = '$usrid'
								//echo $sql;
									
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_purchase_order` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_purchase_order.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
							//echo $sql;				
								}
								
							if($user == 'Admin'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status in('Pending', 'Verified','Prepared') ";
							}
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status in('Pending', 'Verified')  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
							
//All Pending							
							if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' ";
							}
							else {
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') ";
							//	$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
							//			INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
							//			and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";
								$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type'  group by doc_id) ) DS1 
										ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";	
		//echo $sql;
		
							}
							
							if($user == 'Admin' || $role == 'CXO' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where status not in('Completed', 'Draft') ";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
								?> 
								<?php	
								$acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD'  ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_purchase_order where project in ( $comid ) 
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Approved')) 
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Approved')) or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PO' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'PO' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') "	;
										
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Approved'  and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status = 'Approved' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
									?>
								
								<?php
								$rcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD'  ){
									$sql="SELECT approval_status, count(*) as cnt from sma_purchase_order where project in ( $comid ) 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Rejected')) 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Rejected')) or draft_by = '$user' )   ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status = 'Rejected'  and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_purchase_order` where approval_status = 'Rejected' ";
								}	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
								
?>		
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingPO()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingPO()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapprovePO()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectPO()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Purchase Order </H3>
		</div>
		
<?php
    }
	

//GRN SRN	
if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
			
								$pcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_grn_srn where   approval_status in('Pending', 'Verified','Prepared')  
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS'   and status in('Pending', 'Verified','Prepared') ) ) ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
											ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared')";	
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_grn_srn` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = 'GS' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = 'GS' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_grn_srn.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and draft_by = '$user' ";
								}
								
							if($user == 'Admin'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status in('Pending', 'Verified','Prepared') ";
							}
//echo $sql."<BR>";
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where approval_status in('Pending', 'Verified')  ";
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
							
//All Pending	
							if ($role =='Maker'){
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') and draft_by = '$user' ";
							}
							else {
								//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') ";
							}
							
								if($user == 'Admin'  || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where status not in('Completed', 'Draft') ";
								}

//echo $sql."<BR>";

								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
								?> 
								<?php	
								$acnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_grn_srn where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Approved')) 
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Approved')) or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'GS' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'GS' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Approved') "	;	
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status = 'Approved'  and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status = 'Approved' ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								?>
								
								<?php
								$rcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_grn_srn where approval_status in('Rejected') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Rejected')) 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Rejected')) or draft_by = '$user' )   ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status = 'Rejected'  and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_grn_srn` where  approval_status = 'Rejected' ";
								}	

//echo $sql;
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
								
?>		
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingGS()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingGS()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveGS()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectGS()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> GRN SRN </H3>
		</div>
<?php

}


//Supplier Invoice	
if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
						
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";									
								}
								else if ($role =='Accountant'  ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";	
//echo $sql;											
								}
								else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') )";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_supplier_invoice` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_supplier_invoice.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user' ";
								}
								
							//	if($user == 'Admin'){
							//		$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where approval_status in('Pending', 'Verified','Prepared')  ";
							//	}
//echo $sql;
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
					
// All Pending								
								if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ( $comid ) and status not in('Completed', 'Draft') and draft_by = '$user' ";
								}
								else {	
								//	$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ( $comid ) and status not in('Completed', 'Draft') ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where company_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";
								}
									
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where status not in('Completed', 'Draft') ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
//Approved																
								$acnt = 0;
								if ($role =='Checker' || $role =='Accountant'){
									//$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Approved') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) or draft_by = '$user'";
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') ";
								}
								else if ( $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where  approval_status = 'Approved' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
								$rcnt = 0;
								if ($role =='Checker' || $role =='Accountant'){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid )) and approval_status in('Rejected') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) or draft_by = '$user'";	
								}
								else if ( $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type')
									or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
								}
								
								if($user == 'Admin'|| $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_supplier_invoice` where  approval_status = 'Rejected' ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
					
?>	
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingSI()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingSI()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveSI()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectSI()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
		<H3> Supplier Invoice </H3>
		</div>
<?php

}


//IPC	
if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								if ( $role =='Accountant' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO'){
									$sql="SELECT approval_status, count(*) as cnt  from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') and (id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type'  ) or draft_by = '$user' )  ";
					//echo $sql;					
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status in('Pending', 'Verified','Prepared') and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_ipc` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_ipc.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and sma_comp_id in ( $comid ) and draft_by = '$user' ";
//							echo $sql;				
								}
								
								if($user == 'Admin' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared')  ";
								}
			
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts  = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
								
//All Pending								
								if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' ";
								}
								else {
									//$sql ="SELECT approval_status, count(*) as cnt from sma_ipc DS
									//	INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
									//	and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";	
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where status not in('Completed', 'Draft') ";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
//Approval																
								$acnt = 0;
								if ($role =='Accountant' || $role =='Maker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Approved') 
									//	and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
									//	or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )   ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Approved')";	
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Approved' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where  approval_status = 'Approved' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
								
								$rcnt = 0;
								if ($role =='Accountant' || $role =='Maker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD'){
									$sql="SELECT approval_status, count(*) as cnt from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Rejected') 
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = '$doc_type') 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type') or draft_by = '$user' )  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where sma_comp_id in ($comid) and approval_status = 'Rejected' and draft_by = '$user' ";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_ipc` where  approval_status = 'Rejected' ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
					
?>		
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingIP()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingIP()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveIP()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectIP()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> I P C </H3>
		</div>
<?php

}
	
	

//Payment
if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								
								if ($role =='HOD - Account' || $role =='Project Manager'){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where approval_status in('Pending', 'Verified','Prepared','Submited') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') )";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where approval_status in('Pending', 'Verified','Prepared') and company_id in ($comid) and draft_by = '$user' order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') ";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where approval_status in('Pending', 'Verified','Prepared') and company_id in ($comid) order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									//$sql = "SELECT approval_status, count(*) as cnt from payment_header where approval_status in('Pending', 'Verified','Prepared') and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `payment_header` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') ) DS1 ON payment_header.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared','Submited') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user' ";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user' order by id desc";
								}

								//if($user == 'Admin'){
								//	$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where approval_status in('Pending', 'Verified','Prepared')  ";
								//}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
//All Pending								
								if ($role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where company_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' ";
								}
								else {
									//$sql ="SELECT approval_status, count(*) as cnt from payment_header DS
									//		INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
									//		and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') ";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in ( $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') ";		
								//echo $sql;			
								
								}	
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where status not in('Completed', 'Draft') ";
								}
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
//Approved																
								$acnt = 0;
								if ($role =='HOD - Account' || $role =='Project Manager' ){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Approved') or draft_by = '$user' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Approved') or draft_by = '$user' order by id desc";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and  approval_status in('Approved') order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Approved') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt from payment_header where status = 'Completed' and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Approved') order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user' and  approval_status in('Approved') order by id desc";
								}
								
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where  approval_status = 'Approved' ";
								}	
								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}

//Reject								
								$rcnt = 0;
								if ($role =='HOD - Account' || $role =='Project Manager'){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Rejected') or draft_by = '$user' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql="SELECT approval_status, count(*) as cnt from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type')) and  approval_status in('Rejected') or draft_by = '$user' order by id desc";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT approval_status, count(*) as cnt from payment_header where company_id in ($comid) and  approval_status in('Rejected') order by id desc";
									$sql = "SELECT approval_status, count(*) as cnt from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') ";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT approval_status, count(*) as cnt from payment_header where company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Rejected') order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT approval_status, count(*) as cnt from payment_header where draft_by = '$user' and  approval_status in('Rejected') order by id desc";
								}
																
								if($user == 'Admin' || $role == 'CXO'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `payment_header` where approval_status = 'Rejected' ";
								}
//echo $sql;								
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
								
?>		
		<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingPY()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingPY()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapprovePY()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectPY()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Payment </H3>
		</div>
<?php
}


// Travel Approval
if(isset($_POST['sub77'])){
//SELECT * FROM `workflow_history` where doc_id = 11 and doc_type = 'TA' and status in ('Pending', 'Prepared', 'Approved')
$id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								

								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $role=='accountant' || $role =='CXO'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status = 'Pending' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where approval_status in('Pending') and status != 'Withdraw' ";
									
								}	
//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}

//All Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role =='CXO' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in ('Prepared') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in ('Approved') ";
									//echo $sql;	
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_traval_approval where status != 'Withdraw' and approval_status in ('Rejected') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}								
?>


<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingTR()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingTR()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveTR()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectTR()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Travel Request </H3>
		</div>
		
<?php
		
}


//sma_travel_expenses
if(isset($_POST['sub88'])){
//SELECT * FROM `workflow_history` where doc_id = 11 and doc_type = 'TE' and status in ('Pending', 'Prepared', 'Approved')
$id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO' ){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}	
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}

//All Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role == 'CXO'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and status in ('Prepared') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin'|| $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Approved') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Rejected') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}								
?>


<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingTE()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingTE()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveTE()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectTE()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Travel Expenses</H3>
		</div>
		
<?php
		
}



//sma_travel_expenses
if(isset($_POST['sub99'])){
//SELECT * FROM `workflow_history` where doc_id = 11 and doc_type = 'TE' and status in ('Pending', 'Prepared', 'Approved')
$id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' ) and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and  approval_status in('Pending') and status != 'Withdraw' ";
									
								}	
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}

//All Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'R' and status != 'Withdraw' and status in ('Prepared') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Approved') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin'|| $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Rejected') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}								
?>


<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingRE()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingRE()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveRE()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectRE()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Regular Expenses</H3>
		</div>
		
<?php
		
}




//Operating Expenses
if(isset($_POST['sub100'])){
//SELECT * FROM `workflow_history` where doc_id = 11 and doc_type = 'TE' and status in ('Pending', 'Prepared', 'Approved')
$id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			$doc_type	= $_POST['doc_type'];
								$pcnt = 0;
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending				
//echo $role. ' ' . $department;

								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'C' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and status != 'Withdraw' and approval_status = 'Pending' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'C' and  approval_status in('Pending') and status != 'Withdraw' ";
									
								}	
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}

//All Pending								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'C' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where exp_type = 'C' and status != 'Withdraw' and status in ('Prepared') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Approved') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT approval_status, count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin'|| $role=='CXO' ){
		
									$sql = " SELECT approval_status, count(*) as cnt from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Rejected') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$rcnt = $r1['cnt'];
								}
?>


<br>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingCE()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingCE()" ><?php echo $allpcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveCE()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectCE()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Operating Expenses</H3>
		</div>
		
<?php
		
}



//Approval Notes
if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending </H3>
		</div>
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
		
	    				
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT DS.* from sma_approval_memo DS
					INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
					 and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'AP'  group by doc_id) ) DS1 
					 ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared') order by id desc ";
						
			}
			else {
				//$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_approval_memo` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_approval_memo.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
			}
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
			
			$modulePath1 = 'approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No. </th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="10%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
							
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		</div>	
       </div>
     </div>
	
<?php		
}	
?>

<?php
//Approval Notes - All Pending
if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];

?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending </H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user'
				order by id desc ";
			}
			else {
				//$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and status not in('Completed', 'Draft') order by id desc ";
				//$sql ="SELECT * from sma_approval_memo DS
				//						INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
				//						and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') order by DS.id desc";
				$sql = "SELECT * from sma_approval_memo DS
							INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.status != 'Draft'  order by DS.id desc ";							
			}	
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where status not in('Completed', 'Draft') order by id desc ";
			}

			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AP' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
									
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="10%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
					
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Approval Notes - Approved
if(isset($_POST['sub9'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];

?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>

		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
								
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_approval_memo where project in ( $comid ) and  approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT * from sma_approval_memo DS
					INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') order by DS.id desc";
					
			}
			
			else {
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
			
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$approval_status = $row['approval_status'];
						if($approval_status=='Approved'){
							$approval_status = 'Pending';
						}
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="10%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Approval Notes - Approved
if(isset($_POST['sub10'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected </H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
				
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_approval_memo where project in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Rejected')) or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_approval_memo` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_approval_memo` where approval_status = 'Rejected' order by id desc ";
			}
			
			$modulePath1 = 'approval/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
						
						$approval_hdr_id = $row['id'];
						$sql 	= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 	= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$tot_amount	= $r3['total_amount'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="10%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="20%"><?php echo $company;?></td>
					<td width="10%"><?php echo $party_name;?></td>
					<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Purchase Order-->

<?php		
//Purchase Order -  My Pending
if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending </H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">

				<!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT DS.* from sma_purchase_order DS
				//	INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
				//	and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id) ) DS1 
				//	ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  order by id desc ";
				$sql = "SELECT * from sma_purchase_order DS
							INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
							and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";	
			//and reviewed_by = '$usrid'
			
			}
			else {
				//$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_purchase_order` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_purchase_order.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>				

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
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
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
								
						$j=$j+1;						
						
						$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		</div>
		</div>
	</div>	
		
<?php		
}	
?>

<?php
//Purchase Order - All Pending
if(isset($_POST['sub12'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending </H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">				
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' order by id desc ";
			}
			else {
				//$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and status not in('Completed', 'Draft') order by id desc ";
				//$sql = "SELECT * from sma_purchase_order DS
				//						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
				//						and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by //doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')";
				$sql="SELECT DS.* from sma_purchase_order DS
					INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
					and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' group by doc_id) ) DS1 
					ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared')  order by id desc ";
										
			}
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where status not in('Completed', 'Draft') order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
        <div class="col-md-12">
			<div class="box"> </div>	
			
			<table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						
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
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Purchase Order - Approved
if(isset($_POST['sub13'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved </H3>
		</div>

		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_purchase_order where project in ( $comid ) and  approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT DS.* from sma_purchase_order DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PO' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'PO' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') order by DS.id desc "	;
			}
			else {
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			
	
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
						
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
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
							
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
			
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Purchase Order - Rejected
if(isset($_POST['sub14'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_purchase_order where project in ( $comid ) and  approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO' and status in('Rejected')) or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_purchase_order` where project in ($comid) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_purchase_order` where approval_status = 'Rejected' order by id desc ";
			}
			
			$modulePath1 = 'purchase_order/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
            <tr>
                    <th></th>
					<th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
					while($row = mysqli_fetch_array($result)){
										
						$approval_memo_ref = $row['approval_memo_ref'];
						$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
						
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
						$tot_amount = '';
						$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						while($r1 = mysqli_fetch_array($res1)){
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$tot_amount = $tot_amount + $amount;
						}										
						
						$rid = $row['id'];
							
						$approval_status = $row['approval_status'];
							
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
					<td width="18%"><?php echo $row['po_number'];?></td>
					<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="19%"><?php echo $to_supplier;?></td>
					<td width="10%"><?php echo $app_no_date;?></td>
					<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
			</tr>
		</a>
				<?php } ?>	
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>
<!--Purchase Order-->


<!--GRN SRN-->
<?php		
//GRN SRN -  My Pending
if(isset($_POST['sub15'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_grn_srn where  approval_status in('Pending', 'Verified','Prepared')  
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Pending', 'Verified','Prepared') ) )  order by id desc ";
				$sql = "SELECT DS.* from sma_grn_srn DS
						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
						and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
						ON DS1.doc_id = DS.id where DS.approval_status in('Pending', 'Verified','Prepared') order by id desc";
			}
			else {
				//$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_grn_srn` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_grn_srn.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
			}
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>			

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//GRN SRN - All Pending
if(isset($_POST['sub16'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') and draft_by = '$user' order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where status not in('Completed', 'Draft') order by id desc ";
			}
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>To</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];

						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
					
					$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'GS' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $status;?></td>
						<td width="09%"><?php echo $send_to;?></td>	
		
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//GRN SRN - Approved
if(isset($_POST['sub17'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role=='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				//$sql="SELECT * from sma_grn_srn where approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql="SELECT *  from sma_grn_srn DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'GS' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'GS' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.approval_status in('Approved') order by DS.id desc";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin'|| $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
            <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
					</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//GRN SRN - Rejected
if(isset($_POST['sub18'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge' || $role == 'HOD - Account'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
				$sql="SELECT * from sma_grn_srn where approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'GS' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'GS' and status in('Rejected')) or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_grn_srn` where approval_status = 'Rejected' order by id desc ";
			}
			
			$modulePath1 = 'grnsrn/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>SrNo.</th>
					<th>Dated</th>
					<th>Supplier Name</th>
					<th>Supp.Inv.No.</th>
					<th>Our PO Ref.NO.</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
				while($row = mysqli_fetch_array($result)){
					$supplier_id = $row['supplier_name'];
					$sql="SELECT * from sma_party_mst where id = '$supplier_id' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$supplier_name = $r2['party_name'];
					
					$our_po_ref_no = $row['our_po_ref_no'];
					$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$our_po_ref_no = $r2['po_number'];
					
					$j=$j+1;
					
					$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
								
						?>
					<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
					<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
						<td width="0%"><input type="hidden" value="<?php echo $j;?>" > </td>
						<td width="6%" style="text-align:right;"><?php echo $row['id'];?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['received_date']));?></td>
						<td width="23%"><?php echo $supplier_name;?></td>
						<td width="09%"><?php echo $row['supplier_invoice_no'];?></td>
						<td width="17%"><?php echo $our_po_ref_no;?></td>
						
						<td width="09%"><?php echo $row['changed_by'];?></td>
						<td width="09%"><?php echo $row['status'];?></td>
						<td width="09%"><?php echo $row['approval_status'];?></td>	
		
			</tr>
					</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<!--GRN SRN-->

<!--Supplier Invoice-->
<?php		
//Supplier Invoice -  My Pending
if(isset($_POST['sub19'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending </H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Checker' || $role =='Checker - Account' || $role =='HOD - Account' ){
				//$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Pending', 'Verified','Prepared') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') ) ";	
				$sql="SELECT * from sma_supplier_invoice DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
											ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') order by DS.id desc";
			}
			else if ($role =='Accountant'  ){
				$sql = "SELECT * from sma_supplier_invoice DS
						INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
					and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 
					ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') order by DS.id desc";	
//echo $sql;											
			}
			else if ( $role =='Maker' ){
			//	$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared') 
			//			and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
			//			or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) order by id desc ";
				$sql = "SELECT * FROM `sma_supplier_invoice` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_supplier_invoice.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and company_id in ( $comid ) and draft_by = '$user' order by id desc";		
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ($comid) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc";
			}
								
	//		if($user == 'Admin'){
	//			$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
	//		}
			
//echo $sql;
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
				$j  =0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Supplier Invoice - All Pending
if(isset($_POST['sub20'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
	
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and  status not in('Completed', 'Draft') and draft_by = '$user' order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and  status not in('Completed', 'Draft') order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where status not in('Completed', 'Draft') order by id desc ";
			}
			
			$modulePath1 = 'supp_invoice/';

			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>TO</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
				$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'SI' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
								
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $status;?></td>
				<td width="10%"><?php echo $send_to;?></td>
			
			</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//Supplier Invoice - Approved
if(isset($_POST['sub21'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Accountant'){
				//$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Approved') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI')) or draft_by = '$user'";	
				$sql = "SELECT * from sma_supplier_invoice DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') order by DS.id desc";
			}
			else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Approved') 
						and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
						or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
				?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//Supplier Invoice - Rejected
if(isset($_POST['sub22'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Checker' || $role =='Accountant'){
				$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and  approval_status in('Rejected') and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI')) or draft_by = '$user' order by id desc";	
			}
			else if (  $role =='Checker - Account' || $role =='HOD - Account' || $role =='Maker' ){
				$sql = "SELECT * from sma_supplier_invoice where company_id in ( $comid ) and  approval_status in('Rejected') 
						and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'SI') 
						or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI') or draft_by = '$user' ) order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_supplier_invoice` where company_id in ( $comid ) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_supplier_invoice` where approval_status = 'Rejected' order by id desc ";
			}
			
			$modulePath1 = 'supp_invoice/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
					
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>SrNo</th>
					<th>Dated</th>
					<th>Supp.Inv.No.</th>
					<th style="text-align:right;">Amount</th>
					<th>Our PO Ref.NO.</th>
					<th>Due Date</th>
					<th>Supplier Name</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
		
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
			while($row = mysqli_fetch_array($result)){
						
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$supplier_name = $r2['party_name'];

				$our_po_ref_no = $row['our_po_ref_no'];
				
				$rid = $row['id'];
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				$j = $j + 1;		
						
			?>
			<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				<td width="6%" style="text-align:right;"><?php echo $row['id']?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
				<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
				<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
				<td width="15%"><?php echo $our_po_ref_no;?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
				<td width="20%"><?php echo $supplier_name;?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="09%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			
			</tr>
			</a>

				<?php } ?>
				                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Supplier Invoice-->


<!--IPC-->

<?php		
//IPC -  My Pending
if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			if ($role =='HOD - Account' || $role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and  approval_status in('Pending', 'Verified','Prepared')  
				and (id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type'  ) or draft_by = '$user' )  order by id desc ";
			//echo $sql;	
			}
			else {
				//$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status in('Pending', 'Verified','Prepared')  and draft_by = '$user' order by id desc ";
				$sql = "SELECT * FROM `sma_ipc` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
						select max(id) as id from `workflow_history` where doc_Type = '$doc_type' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = '$doc_type' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_ipc.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and sma_comp_id in ( $comid ) and draft_by = '$user' order by id desc ";	
			}
			
//			if($user == 'Admin'){
//				$sql = "SELECT * FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
//			}
								
						if($user == 'Admin' ){
							$sql = "SELECT * FROM `sma_ipc` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
						}
//echo $sql;			
			$modulePath1 = 'ipc/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
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
			$j = 0;
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
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				
				$approval_status = $row['approval_status'];
				
				if ($approval_status == 'Verified' || $approval_status == 'Prepared'){
					$approval_status = 'Pending';
				}
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
				$j = $j +1;
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $approval_status;?></td>

				</tr>
				</a>

			<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//IPC - All Pending
if(isset($_POST['sub24'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>

		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='Maker'){
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and status not in('Completed', 'Draft') and draft_by = '$user' order by id desc ";
			}
			else {
				//$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and status not in('Completed', 'Draft') order by id desc ";	
				$sql ="SELECT * from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') order by DS.id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where status not in('Completed', 'Draft') order by id desc ";
			}
			$modulePath1 = 'ipc/';
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
					<th>Party</th>
					<th>PO.Number</th>
					<th>Invoice No.</th>
					<th>PO Amount</th>
					<th>Invoice Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>To</th>
	
				</tr>
                </thead>
                <tbody>
			<?php
			$j = 0;
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
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$srno = $row['id'];
					$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id desc limit 0,1 ";
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						
				$status = $row['status'];
				if($status!='Completed'){
					$status = 'Pending';
				}
						
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $status;?></td>
					<td width="8%"><?php echo $send_to;?></td>

				</tr>
			</a>

				<?php } ?>
				
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
//IPC - Approved
if(isset($_POST['sub25'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					
            <!-- /.box-header -->
	<?php
	
			if ($role =='HOD - Account' || $role=='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				//$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and approval_status in('Approved') 
				//and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP' and status in('Approved')) 
				//or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP' and status in('Approved')) or draft_by = '$user' )  order by id desc ";
				$sql = "SELECT * from sma_ipc DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.sma_comp_id in (  $comid ) and DS.approval_status in('Approved') order by DS.id desc";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status = 'Approved' and draft_by = '$user' order by id desc";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'ipc/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                <tr>
					<th>#</th>
        			<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
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
			$j = 0;
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
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

			<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
				
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $row['approval_status'];?></td>

				</tr>
			</a>

				<?php } ?>
		        
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
//IPC - Rejected
if(isset($_POST['sub26'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
	
			if ($role =='HOD - Account' || $role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' ){
				$sql="SELECT * from sma_ipc where sma_comp_id in ( $comid ) and approval_status in('Rejected') 
				and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'IP' and status in('Rejected')) 
				or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'IP' and status in('Rejected')) or draft_by = '$user' )  order by id desc ";
			}
			else {
				$sql = "SELECT * FROM `sma_ipc` where sma_comp_id in ( $comid ) and approval_status = 'Rejected' and draft_by = '$user' order by id desc ";
			}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `sma_ipc` where approval_status = 'Rejected' order by id desc ";
			}
			
			$modulePath1 = 'ipc/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
				<div class="col-md-12">
			<div class="box"> </div>	
		

              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
        			<th>#</th>
					<th>Sr.No.</th>
					<th>Dated</th>
					<th>Company</th>
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
			$j = 0;
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
				
				$sma_invoice_no = $row['sma_invoice_no'];
				$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$sma_invoice_no = $r2['supplier_invoice_no'];
				
				$ipc_date	= date('d-m-Y', strtotime($row['ipc_date']));
				if($ipc_date == '01-01-1970'){
					$ipc_date	='';
				}
				
				$j = $j +1;
				
				$baseurl1 = $baseurl.$modulePath1.'ipc.php?sub=edit&id='.$row["id"];
		?>

				<a href="<?php echo $baseurl . $modulePath1 . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
				<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="0%" > <input type="hidden" style="text-align:right;"  value="<?php echo $j?>" ></td>	
					<td width="5%"><?php echo $row['id'];?></td>
					<td width="8%"><?php echo $ipc_date;?></td>
					<td width="20%"><?php echo $comp_name;?></td>
					<td width="15%"><?php echo $sma_vendor_name;?></td>
					<td width="9%"><?php echo $po_number;?></td>
					<td width="8%"><?php echo $sma_invoice_no;?></td>
					<td width="9%"><?php echo $row['sma_po_amount'];?></td>
					<td width="9%"><?php echo $row['sma_invoice_amount'];?></td>
					<td width="8%"><?php echo $row['changed_by'];?></td>
					<td width="8%"><?php echo $row['status'];?></td>
					<td width="8%"><?php echo $row['approval_status'];?></td>

				</tr>
			</a>

				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--IPC-->

<?php
//Payment
if(isset($_POST['sub27'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Pending', 'Verified','Prepared','Submited')  order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') order by DS.id desc";	
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and approval_status in('Pending', 'Verified','Prepared','Submited') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') order by DS.id desc";	
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where approval_status in('Pending', 'Verified','Prepared','Submited') and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) order by id desc";
									
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Pending', 'Verified','Prepared','Submited') order by id desc";
								}
													
//			if($user == 'Admin'){
//				$sql = "SELECT * FROM `payment_header` where approval_status in('Pending', 'Verified','Prepared') order by id desc ";
//			}
//echo $sql;			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>				
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;
			while($row = mysqli_fetch_array($result)){
				$cash_bank_name = $row['cash_bank_name'];
				$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$cash_bank_name = $r2['account_name'];
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated = '';
				}
				
				$paid_to = $row['paid_to'];
				$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$party_name  = $r2['party_name'];
														
				$rid = $row['id'];
				$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
				$j =$j +1;
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
							
			?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
				<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
				<td width="1%"><?php echo $row['id'];?></td>
				<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
				<td width="10%"><?php echo $cash_bank_name;?></td>
				<td width="12%"><?php echo $party_name;?></td>
				<td width="10%"><?php echo $row['utr_no'];?></td>
				<td width="10%"><?php echo $dated;?></td>
				<td width="10%"><?php echo $supplier_invoice_no;?></td>
				<td width="10%" style="text-align:right;"><?php echo number_format($row['total_amount_paid'],2);?></td>
				<td width="10%"><?php echo $row['changed_by'];?></td>
				<td width="10%"><?php echo $row['status'];?></td>
				<td width="10%"><?php echo $row['approval_status'];?></td>
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub28'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
			
							if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status not in('Completed', 'Draft') or draft_by = '$user' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status not in('Completed', 'Draft') and company_id in ($comid) order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared','Submited') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared','Submited') order by DS.id desc ";
								}
								else if ( $role == 'Checker'){
									$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status not in('Completed', 'Draft') order by id desc";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * FROM `payment_header` where company_id in ($comid) and status not in('Completed', 'Draft') and draft_by = '$user' order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status not in('Completed', 'Draft') order by id desc";
								}
			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where status not in('Completed', 'Draft') order by id desc ";
			}
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>To</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
				while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
						
						$status = $row['status'];
						if($status!='Completed'){
							$status = 'Pending';
						}
						
						$srno = $row['id'];
						$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc limit 0,1 ";
					//echo $s1;	
						$res  = mysqli_query($con, $s1);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$reviewed_by		= $r1['reviewed_by'];
						$sl="SELECT * FROM sma_user where id = '$reviewed_by' ";
					
						$r3 = mysqli_query($con, $sl);
						$rw = mysqli_fetch_array($r3);
						$send_to = $rw['username'];
						
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $status;?></td>
					<td width="10%"><?php echo $send_to;?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub29'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					
            <!-- /.box-header -->
	<?php
							if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Approved') order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status in('Approved') or draft_by = '$user' order by id desc";
								}
								else if ( $role == 'Checker'){
									//$sql="SELECT * from payment_header where company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) and  approval_status in('Approved') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Approved') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Approved') order by DS.id desc ";	
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Approved') order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Approved') order by id desc";
								}
								
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where approval_status in('Approved') order by id desc ";
			}
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
					
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
					while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
						
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub30'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
		
							if ($role =='HOD - Account'){
									$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Rejected') or draft_by = '$user' order by id desc";
								}
								else if ($role =='Checker - Account' || $role =='Accountant'){
									//$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') ) and  approval_status in('Rejected') or draft_by = '$user' order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') order by DS.id desc";
								}
								else if ( $role == 'Checker' ){
									//$sql="SELECT * from payment_header where  company_id in ($comid) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY')) and approval_status in('Rejected') order by id desc";
									$sql = "SELECT * from payment_header DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Rejected') 
											and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.company_id in (  $comid ) and DS.approval_status in('Rejected') order by DS.id desc";
								//	echo $sql;
								}
								else if ( $role =='Maker'){
									$sql = "SELECT * from payment_header where company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) and approval_status in('Rejected') order by id desc";
									//echo $sql;
								}
								else {
									$sql="SELECT * from payment_header where draft_by = '$user' and  approval_status in('Rejected') order by id desc";
								}
																			
			if($user == 'Admin' || $role == 'CXO'){
				$sql = "SELECT * FROM `payment_header` where approval_status in('Rejected') order by id desc ";
			}
			
			$modulePath1 = 'payment/';
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rowcount=mysqli_num_rows($result);
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
		
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>#</th>
					<th>Paid Date</th>
					<th>Paid via</th>
					<th>Paid To</th>
					<th>UTR.No.</th>
					<th>Dated.</th>
					<th>Supp.No.</td>
					<th style="text-align:right;">Amount Paid</th>
					<th>Created By</th>
					<th>Decision</th>
					<th>By</th>
				
				</tr>
                </thead>
                <tbody>
			<?php
				$j =0;
					while($row = mysqli_fetch_array($result)){
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$cash_bank_name = $r2['account_name'];
						
						$dated = date('d-m-Y', strtotime($row['dated']));
						if($dated =='01-01-1970'){
							$dated = '';
						}
					
						$deduction_amt		= $row["tds_amount"];
						$total_amount_paid	= $row['total_amount_paid'];
						$actual_paid		= $total_amount_paid - $deduction_amt;
							
						$paid_to = $row['paid_to'];
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						
						$rid = $row['id'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$supplier_invoice_no  = $r2['supplier_invoice_no'];
								
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath1.'edit.php?sub=edit&id='.$row["id"];
				
				?>
		<a href="<?php echo $baseurl . $modulePath1 . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>"></td>
					<td width="1%"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
					<td width="10%"><?php echo $cash_bank_name;?></td>
					<td width="12%"><?php echo $party_name;?></td>
					<td width="10%"><?php echo $row['utr_no'];?></td>
					<td width="10%"><?php echo $dated;?></td>
					<td width="10%"><?php echo $supplier_invoice_no;?></td>
					<td width="10%" style="text-align:right;"><?php echo number_format($actual_paid,2);?></td>
					<td width="08%"><?php echo $row['draft_by'];?></td>
					<td width="10%"><?php echo $row['approval_status'];?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
				
			</tr>
		</a>
				<?php } ?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Payment-->

<?php
//Travel Request
if(isset($_POST['sub31'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $role=='accountant' || $role=='CXO' ){
									
									$sql = " SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status in ('Pending') and reviewed_by = '$usrid' ) and approval_status = 'Pending' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_traval_approval where approval_status in('Pending') and status != 'Withdraw' ";
									
								}	

				$sql .=" order by id desc";
			 
//	 echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
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
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub32'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14' || $role=='CXO'){
									
									$sql = " SELECT * from sma_traval_approval where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TA' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and approval_status in ('Prepared') ";
									
								}

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
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
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];

								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_traval_approval where approval_status = 'Approved' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and approval_status in ('Approved') ";
									
								}

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
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
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub34'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_traval_approval where emp_id = '$usrid' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];

								
								if ($cnt_my>0){
									$sql="SELECT * from sma_traval_approval where emp_id = '$usrid' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_traval_approval where approval_status = 'Rejected' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_traval_approval where status != 'Withdraw' and status in ('Rejected') ";
									
								}

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
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
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 = $baseurl.$modulePath1.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath1 . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $emp_name;?></td>
		<td width="10%"><?php echo $row['traval_from'];?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="13%"><?php echo $row['traval_to'];?></td>
		<td width="10%"><?php echo $end_date;?></td>
		<td width="04%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

    </tr>
	</a>
				<?php } ?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Travel Request-->



<?php
//Travel Expenses
if(isset($_POST['sub35'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid'  and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' )  and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'|| $role=='CXO' ){
		
									$sql = " SELECT *  from sma_travel_expenses where approval_status in('Pending') and exp_type = 'T' and status != 'Withdraw' ";
									
								}	
								
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}


				$sql .=" order by id desc";
			 
		//	 echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub36'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//All Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'T' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin'  ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Prepared') ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub37'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Approved') ";
									
								}	

						$sql .=" order by id desc";
								
//							echo $cnt_my. ' >>><<< ' .$sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub38'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'T' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'T' and approval_status in ('Rejected') ";
									
								}
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "travel_expence.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Travel Expenses-->



<?php
//Regular Expenses
if(isset($_POST['sub39'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'R' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT *  from sma_travel_expenses where approval_status in('Pending') and exp_type = 'R' and status != 'Withdraw' ";
									
								}	
								
						//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}


				$sql .=" order by id desc";
			 
	//		 echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub40'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//All Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'TE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and exp_type = 'R' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Prepared', 'Pending' ) ";
									
								}
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub41'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Approved') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub42'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'R' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'R' and approval_status in ('Rejected') ";
									
								}
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
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
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="15%"><?php echo $username;?></td>
		<td width="15%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Regular Expenses-->




<?php

//Operating Expenses
if(isset($_POST['sub43'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3>Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];
								
								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
//My Pending								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status in ('Pending') and reviewed_by = '$usrid' ) and exp_type = 'C' and status != 'Withdraw' and approval_status = 'Pending'";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT *  from sma_travel_expenses where approval_status = 'Pending' and exp_type = 'C' and status != 'Withdraw' ";
									
								}	
								
				//echo $sql;	
						
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}


				$sql .=" order by id desc";
			 
	//		 echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<?php
if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> All Pending</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//All Pending								
								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Pending') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and id in ( SELECT doc_id FROM `workflow_history` where doc_type = 'CE' and status = 'Prepared' and create_by = '$usrid' ) and approval_status = 'Prepared' and status != 'Withdraw' ";
									
								}

								if ($user=='Admin' ){
		
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and status != 'Withdraw' and status in ('Prepared') ";
									$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and status != 'Withdraw' and status = 'Prepared' ";
								}
//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$allpcnt = $r1['cnt'];
								}
								

				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
				$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub45'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Approved</H3>
		</div>
		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Approved								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Approved') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Approved') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Approved') ";
									
								}	
							//echo $sql;	
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$acnt = $r1['cnt'];
								}
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
				$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
    </tr>
	</a>
	<?php }?>

                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>


<?php
if(isset($_POST['sub46'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$doc_type	= $_POST['doc_type'];
		
?>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;" >
			<H3> Rejected</H3>
		</div>

		
		<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
						
            <!-- /.box-header -->
	<?php
								
								$department = $_SESSION['department'];

								$sql = "SELECT count(*) as cnt from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and status != 'Withdraw' ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1  = mysqli_fetch_array($result);
								$cnt_my = $r1['cnt'];
								
//Rejected								
								if ($cnt_my>0){
									$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid' and exp_type = 'C' and approval_status in('Rejected') and status != 'Withdraw' ";
								}
								else if($department=='12' || $department=='13' || $department=='14'){
									
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in('Rejected') ";
									
								}

								if ($user=='Admin' || $role=='CXO' ){
		
									$sql = " SELECT * from sma_travel_expenses where status != 'Withdraw' and exp_type = 'C' and approval_status in ('Rejected') ";
									
								}
				$sql .=" order by id desc";
			 
			 //echo $sql;
							
			$modulePath1 = 'travel_approval/';
			
		?>
		<div class="col-md-12">
			<div class="box"> </div>	
				
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>#</th>
					<th>SrNo.</th>
					<th>Party Name</th>
					<th>Company</th>
					<th>Date</th>
					<th>Approval Ref.no.</th>
					<th>Advance Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$j=0;

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		//$comp_name 		= $r1['comp_name'];
		$comp_name 		= $r1['comp_code'];

		$emp_id = $row['emp_id'];
		$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['party_name'];
		
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="25%"><?php echo $username;?></td>
		<td width="05%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $row['approval_ref_no'];?></td>
		<td width="10%"><?php echo $row['advance_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
		
    </tr>
	</a>
	<?php }?>
                
                </tbody>
              </table>
            </div>
		
<?php		
}	
?>

<!--Operating Expenses-->


<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

