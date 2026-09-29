<?php							
	
	include('dbcon.php');
	
	$pcnt = 0;
	$doc_type	= 'AP';
	
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD' || $role == 'CXO' || $role =='COO' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo DS
											INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified','Prepared') 
											 and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'AP'  group by doc_id) ) DS1 
											 ON DS1.doc_id = DS.id where DS.project in (  $comid  ) and DS.approval_status in('Pending', 'Verified','Prepared') ";
											 //and reviewed_by = '$usrid'
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' ";
									$sql = "SELECT approval_status, count(*) as cnt  FROM `sma_approval_memo` INNER JOIN (select doc_id from `workflow_history` INNER JOIN (
											select max(id) as id from `workflow_history` where doc_Type = 'AP' group by doc_id )DS ON workflow_history.id=DS.id and doc_Type = 'AP' and status in('Pending', 'Verified','Prepared') ) DS1 ON sma_approval_memo.id = DS1.doc_id where approval_status in('Pending', 'Verified','Prepared') and status = 'Draft' and project in ( $comid ) and draft_by = '$user' ";
							//echo $sql;				
								}
								
								if($user == 'Admin'){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status in('Pending', 'Verified')  ";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
//All Pending
								if ($role =='Maker' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and draft_by = '$user' and status not in ('Completed', 'Draft') ";
								}
								else {
									//$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and status not in ('Completed', 'Draft') ";	
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = '$doc_type' and status in('Pending', 'Verified','Prepared') 
										and id in (SELECT max(id) FROM `workflow_history` where doc_Type = '$doc_type' and reviewed_by = '$usrid'  group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Pending', 'Verified','Prepared') and DS.status != 'Draft' ";		
									//and create_by = '$usrid' 	
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
								if ($role =='Checker' || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD'  ){
									//$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Approved') 
									//and id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Approved')) ";
									$sql = "SELECT approval_status, count(*) as cnt from sma_approval_memo DS
										INNER JOIN (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'AP' and id in (SELECT max(id) FROM `workflow_history` where doc_Type = 'AP' and create_by = '$usrid' group by doc_id) ) DS1 ON DS1.doc_id = DS.id where DS.project in (  $comid ) and DS.approval_status in('Approved') ";	
	
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
								if ($role =='Checker'  || $role =='Project Manager' ||  $role == 'Project Incharge'  || $role == 'HOD'  ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Rejected') 
										and id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Rejected'))  ";
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
		
<?php $i = $menu_id[2]; if ( $dashboard[$i] =='Y' ){ ?>		
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
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 10px;">
			<h3> Approval Note </h3>
		</div>
<?php } ?>	