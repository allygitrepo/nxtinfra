<?php							
	
	include('dbcon.php');
	
	
$pcnt = 0;
								if ($role =='Checker' || $role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role =='COO' ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Pending', 'Verified')  
										and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Pending', 'Verified')  ) 
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' and status in('Pending', 'Verified') ) or draft_by = '$user' )  ";
								}
								else {
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and approval_status in('Pending', 'Verified')  and draft_by = '$user' ";
								}
								
								if($user == 'Admin' ){
									$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where approval_status in('Pending', 'Verified')  ";
								}
								$res = mysqli_query($con, $sql);
								while($r1 = mysqli_fetch_array($res)){
									$sts = $r1['approval_status'];
									$pcnt = $r1['cnt'];
								}
//All Pending
								$sql = "SELECT approval_status, count(*) as cnt FROM `sma_approval_memo` where project in ($comid) and status not in ('Completed', 'Draft') ";
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
								if ($role =='Checker' || $role =='Project Manager' || $role == 'Project Incharge'  ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Approved') 
									and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'AP' and status in('Approved'))  
										or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'AP' 	and status in('Approved')) or draft_by = '$user' )   ";
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
								if ($role =='Checker'  || $role =='Project Manager' || $role == 'Project Incharge'  ){
									$sql="SELECT approval_status, count(*) as cnt from sma_approval_memo where project in ( $comid ) and  approval_status in('Rejected') 
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
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 5px;">
			 <a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingAP()" ><?php echo $pcnt; ?><br> My Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 5px;">
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getallpendingAP()" ><?php echo $allcnt; ?><br> All Pending</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 5px;">
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getapproveAP()" ><?php echo $acnt; ?><br> Approved</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 5px;">
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getrejectAP()" ><?php echo $rcnt; ?><br> Rejected</a>
		</div>
		<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 10px;">
			<h3> Approval Note </h3>
		</div>
