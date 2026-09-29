<?php
include("../header.php");
$modulePath = "purchase_order/";
$usrid  = $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
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
      <h1>
        Purchase Order <small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Purchase Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
   			<?php 
				$targetpage = "index.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] 	= $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					$_SESSION['search_data']= $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];					
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
					$searchf = $_SESSION['searchf'];
					$search_own		= $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_own'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$search_own = $_SESSION['search_own'];
					$approval_status = $_SESSION['approval_status'];
					$_SESSION['Createdby_po'] = '';
					
				}
				/* 
				if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submitted" <?php echo ($status == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										<option value="Auto Closed" <?php echo ($status == 'Auto Closed')?'selected="selected"':'';?>> Auto Closed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
							<div class="form-group">
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Supplier Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> PO.Number </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="M" <?php echo ($searchf == 'M')?'selected="selected"':'';?>> PR SrNo. </option>
									<option value="A" <?php echo ($searchf == 'A')?'selected="selected"':'';?>> Advance Payment </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Deleted. </option>
									
								</select>
											
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='P' || $searchf=='M' ){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N' || $searchf=='P'  || $searchf=='M'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
							<?php if($searchf=='D'){ 
								$start_date = date('d-m-Y', strtotime($start_date));
								$end_date = date('d-m-Y', strtotime($end_date));
								if($start_date =='01-01-1970'){
									$start_date = date('d-m-Y');
								}
								if($end_date =='01-01-1970'){
									$end_date = date('d-m-Y');
								}
								
							?>	
								<label class="col-lg-1 control-label">Start.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<label class="col-lg-1 control-label">End.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							<?php } ?>		
									
							</span>
							
							<!--<input class="btn btn-success" type="submit" value="Self Created" name="Createdby">&nbsp;&nbsp;&nbsp;-->
							
						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						
						?>
						<?php 
						    
						if ( $viewonly!='Y'){ ?>					
							<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "add.php"?>" class="btn btn-primary">Create</a> &nbsp;&nbsp;&nbsp;</span>
						<?php } ?>	
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "po_export_func.php?sub=pdf"?>" class="btn btn-primary">PO Detailed Report</a> &nbsp;&nbsp;&nbsp;</span>
						
					<!--	<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "po_cum_ap_export_func.php?sub=pdf"?>" target="_blank" class="btn btn-primary">PO cum AP Report</a> &nbsp;&nbsp;&nbsp;</span>-->
						
						<!--<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "po_pending_func.php?sub=pdf"?>" target="_blank" class="btn btn-primary">Balance PO Report</a>
						&nbsp;&nbsp;&nbsp;</span>-->
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "po_total_value_export.php?sub=pdf"?>" class="btn btn-primary">PO Summary Report</a> &nbsp;&nbsp;&nbsp;</span>

						
						</div>					   
											   
				</form>
				
			
            </div>
			
			<span id="prItemsTableBody123"> </span>
			
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtableabc" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Srno.</th>
                    <th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Subject</th>
					<th style="text-align:right;">Total</th>
					<!--<th>Advance Paid</th>-->
					<th>Against </th>
					
					<th>By</th>
					<th>Status</th>
					<th>Remarks / Comments</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>
	<?php
		//$sql="SELECT * from sma_purchase_order order by id desc";
		$role			= $_SESSION['role'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];

		
		$sql = "SELECT * from sma_workflow where doc_type= 'PO'  ";

		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		
		$to_value 			= $row['to_value'];
		$approval_role_1	= $row['approval_role_1'];
		$approval_role_2 	= $row['approval_role_2'];
		$approval_role_3 	= $row['approval_role_3'];

//echo $role . ' ' . $user;

// 		//if ($status =='Submitted' || $status=='Draft'){
// 			$sql="SELECT * from sma_purchase_order where project in ( $comid ) ";
// 			$query="SELECT count(*) as num  from sma_purchase_order where project in ( $comid ) ";
// 		//}
		
// 			$sql .= " and (status = 'Closed' or status = 'Auto Closed' or status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or draft_by = '$user' ) ";
			
// /* 			(approver_1 = '$usrid' && (approver_1_status='Submitted' || approver_1_status='Approved')) ||
// 			(approver_2 = '$usrid' && (approver_2_status='Submitted' || approver_2_status='Approved')) ||
// 			(approver_3 = '$usrid' && (approver_3_status='Submitted' || approver_2_status='Approved')) ";
//  */				
// 			$query .= " and (status = 'Closed' or status = 'Auto Closed' or status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or draft_by = '$user' )";
		//echo $viewonly. ">><<";
		
	
		
			$sql="SELECT * from sma_purchase_order where project in ( $comid )  "; //and draft_by = '$user'
			$query="SELECT count(*) as num  from sma_purchase_order where project in ( $comid )  "; //and draft_by = '$user'
		
		if($viewonly=='Y' ){
			$sql="SELECT * from sma_purchase_order where 1 and project in ( $comid ) ";
			$query="SELECT count(*) as num  from sma_purchase_order where 1 and project in ( $comid ) ";
		}
		
		if($user=='Admin123' || $user=='Admin' ||  $primaryrole =='COO' || $primaryrole == 'Director' ){
			$sql="SELECT * from sma_purchase_order where 1 ";
			$query="SELECT count(*) as num  from sma_purchase_order where 1 ";
		}
		
		if($primaryrole=='Admin' && $user=='Raviraj123' ){
		    $sql="SELECT * from sma_purchase_order where 1 ";
			$query="SELECT count(*) as num  from sma_purchase_order where 1 ";
		    $sql 	.= " and status in ( 'Completed' ) ";
			$query 	.= " and status in ( 'Completed' ) ";
		    
		}
		
		if (!empty($comp_id)){
			$sql .= " and project = '$comp_id' ";
			$query .= " and project = '$comp_id' ";
		}
		
		if ($status=='Auto Closed'){
			$sql 	.= " and status in ('Closed', '$status' ) ";
			$query 	.= " and status in ('Closed',  '$status' ) ";
		}
		else if ($status){
			$sql .= " and status = '$status' ";
			$query .= " and status = '$status' ";
		}
		
		
		
		if ($approval_status){
			$sql .= " and approval_status = '$approval_status' ";
			$query .= " and approval_status = '$approval_status' ";
		}
		
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and dated >= '$start_date' and dated <= '$end_date' ";
						$query .= " and dated >= '$start_date' and dated <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					
					if($searchf=='P'){
						$sql .= " and po_number like '%$search_data%' ";
						$query .= " and  po_number like '%$search_data%'  ";
					}
					if($searchf=='E'){
						$sql .= " and ( status  = 'Closed' || po_rev > 0) ";
						$query .= " and ( status  = 'Closed' || po_rev > 0) ";
					}
					if($searchf=='S'){
							$sql .= " and to_supplier = '$search_data'   ";
							$query .= " and to_supplier = '$search_data'  " ;
					}
					if($searchf=='M'){
							$sql .= " and approval_memo_ref = '$search_data'   ";
							$query .= " and approval_memo_ref = '$search_data'  " ;
					}
					if($searchf=='A'){
							$sql .= " and advance_flag = 'Y'   ";
							$query .= " and advance_flag = 'Y'  " ;
					}
					if($search_own=='Y'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user' ";
					}
		
					//Active / Deactive records
					if($searchf=='T'){
						$sql .= " and del = 'Y' ";
						$query .= " and del = 'Y' ";
					}
					else if($searchf=='B'){
						$sql .= "";
						$query .= " ";
					}
					else { // Default
						$sql .= " and del != 'Y' ";
						$query .= " and del != 'Y' ";
					} 
										
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages['num'];
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	

		$_SESSION['sqlex'] = $sql;		
					
		$sql .= ' order by id desc ';
		
		$sql .= " LIMIT $start, $limit ";
				
//echo $sql;					

	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	//echo $lastpage;
	//echo $paginate;
	if($lastpage > 1)
	{	
		$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
		// Previous
		if ($page > 1){
			$paginate.= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>previous</a></li>";	}
			
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li></span>";
				}else{
					$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}					
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<li><a href='$targetpage&page=$next'>next</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>next</a></li>";
			}
			
		$paginate.= "</ul></div>";
}
//end page
//echo $sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	while($row = mysqli_fetch_array($result)){
		
		$approval_memo_ref = $row['approval_memo_ref'];;
		$sql 	= "select * from sma_approval_memo where 1 and del != 'Y' and id = '$approval_memo_ref' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$noa_status     = $r2['status'];
		$ap_number      = $r2['ap_number'];
		$against_indent_no = $r2['against_indent_no'];
		$dated = date('d-m-Y', strtotime($r2['dated']));
		if($dated=='01-01-1970'){$dated='';}
		$app_no_date = $approval_memo_ref. '/'.$dated;
		
		if($approval_memo_ref>0){
		    $noa_link = '<a href="'.$baseurl . "approval/edit.php?sub=edit&id=".$approval_memo_ref.'" target="_blank" ><b>NOA</b>:'.$ap_number.' '.$noa_status.' </a>';
		}
					
		$sql 	= " SELECT * FROM `sma_purchase_req` where 1 and del != 'Y' and id = '$against_indent_no'  ";
    	$q2 	= mysqli_query($con, $sql);
    	$r2 	= mysqli_fetch_array($q2);
    	$against_pr_no = $r2['id'];
    	$pr_status     = $r2['status'];
    	$pr_number     = $r2['pr_number'];
    	if($against_pr_no>0){
    	    $pr_link = '<a href="'.$baseurl . "purchase_requisition/edit.php?sub=edit&id=".$against_pr_no.'"  target="_blank" ><b>PR</b>:'.$pr_number.' '.$pr_status.' </a>';
    	}
    					
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
		
		$rid = $row['id'];
	
		$to_supplier = $row['to_supplier'];
		
		if(empty($to_supplier)){
			
			$sql = "SELECT b.supplier_name FROM `sma_purchase_order` a, `sma_po_approval_details` b WHERE 1 and a.id = b.po_approval_hdr_id and a.project = '$project' and b.vendor_selected = 'Y' and a.id = '$rid' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$to_supplier = $r2['supplier_name'];
			
			$sql = " UPDATE sma_purchase_order set to_supplier = '$to_supplier' where a.id = '$rid' ";
			mysqli_query($con, $sql);
			
		}	
		
		$status   = $row['status'];
		$approved_date ='';
		if($status=='Completed'){
    		$sql = "select * from workflow_history where doc_type = 'PO' and doc_id = '$rid' order by id desc ";
    		$q2  = mysqli_query($con, $sql);
    		$r2 = mysqli_fetch_array($q2);
    		$approved_date  = date('d-m-Y h:i:sa', strtotime($r2['approved_date']));
    		$approved_date_v  = date('d-m-Y', strtotime($r2['approved_date']));
    		if($approved_date_v =='01-01-1970'){
    			$approved_date ='';
    		}	
		}
				
		$po_remark = '';		
		$sql = "select * from workflow_history where doc_type = 'PO' and doc_id = '$rid' ";
    	$q2  = mysqli_query($con, $sql);
    	while($r2 = mysqli_fetch_array($q2)){
    	    if(!empty($r2['remarks'])){
    	        $po_remark .= $r2['remarks'].'<br>';
    	    }
    	}
    	
		$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$to_supplier = $r2['party_name'];
	
		$purchase_id = $row['id'];
		$tot_amount = '0';
		$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			$qty 	= $r1['quantity'];
			$rate 	= $r1['unit_rate'];
			$gst	= $r1['gst'];
			$amount = round($qty * $rate + ((($qty * $rate) * $gst) / 100),0);
			$tot_amount = $tot_amount + $amount;
		}										
		
		if($status=='Closed'){
			$sql="SELECT qty, rate, gst , tds, sum((qty * rate) + (((qty * rate) * gst) / 100) ) as po_value FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b where 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$purchase_id' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r1 = mysqli_fetch_array($res1)){
				$tot_amount 	= round($r1['po_value'],0);
			}	
		}
								
		$tot_amount = round($tot_amount,0);
		
			$rid = $row['id'];
			
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
				//continue;		
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
			
			$po_rev = $row['po_rev'];
			$po_number = $row['po_number'];
			if($po_rev>0){
				$po_number .= '-'.$po_rev;
			}
			else {
				$po_number = $row['po_number'];
			}	
			
			$po_amend = $row['po_amend'];
			$backcolor = '';
			if($po_amend=='Y'){
				$backcolor = ' background-color: coral; ';
			}
			
			/* $changed_by = $row['changed_by'];
			if(empty($changed_by)){
				$changed_by = $row['draft_by'];
			}
			 */
			$changed_by = $row['draft_by'];
			
			$sql="SELECT * from sma_user where userid = '$changed_by' ";
//echo $sql."<BR>";			
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$changed_by 	= $r1['username'];
			
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
				
				$pending_by  = '';
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
				
				if(!empty($pending_by)){
    				$sql = "select * from sma_user where id = '$pending_by' ";
    				$q2  = mysqli_query($con, $sql);
    				$r2 = mysqli_fetch_array($q2);
    				$pending_by  = ' To '. $r2['username'];
				}
				
				
			
			$del   = $row['del'];
			if($del=='Y'){
				$status = 'Deleted';
			}	
			if($status=='Blocked'){
				$status = 'Order Completed';
			}
			$approval_status = $row['approval_status'];
			if($approval_status=='Blocked'){
				$approval_status = 'Order Completed';
			}
			
			//$po_rev = $row['po_rev'];
			//$po_number = $row['po_number'].$po_rev;
			$amount_paid_po = '';
			$sql = "SELECT b.* FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$purchase_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and a.advance_flag = 'Y' ";
//echo $sql;		
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$affected_row  = mysqli_affected_rows($con);
			$utr_no 	= $r2['utr_no'];
			$st_flag 	= $r2['st_flag'];
			if(!empty($utr_no)){
				$paid_status = 'Paid'.'/'.$utr_no ;
			}
			
			if($affected_row>0){
				$sql = "SELECT b.id as pay_no, st_flag, a.id as po_no, sum(c.payment_adjusted) as amount_paid_po, b.utr_no as utr_no_po, b.paid_date as paid_date_po  
					FROM `sma_purchase_order` a, payment_header b, payment_details c 
						WHERE a.id = '$purchase_id' and a.id = c.supp_id and b.id = c.payment_hdr_id 
						AND st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";				
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$amount_paid_po = $amount_paid_po + $r2['amount_paid_po'];
//echo $amount_paid_po. "<BR>";				
				$utr_no_po		= $r2['utr_no_po'];
				$paid_date_po	= $r2['paid_date_po'];
				if(!empty($utr_no_po)){
					$paid_date = date('d-m-Y', strtotime($paid_date_po));
					if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
						$paid_date='';
					}
					$paid_status = 'Advance Paid '.'/'.$utr_no_po ;
				}
			}
			
			$subject = $row['subject'];
			
			if($approval_status=='Submitted'){
			    
			   $approval_status=''; 
			   
			}
			
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="1%"><?php echo $row['id'];?></td>
		<td width="17%" <?php echo $styl; ?>><?php echo substr($po_number,0,14).'<BR>'.substr($po_number,14,24);?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="18%" <?php echo $styl; ?>><?php echo $to_supplier;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $subject;?></td>
		<td width="10%" style="text-align:right; <?php echo $styl2; ?>" ><?php echo moneyFormatIndiaa($tot_amount);?></td>
		<!--<td width="10%" style="text-align:right; <?php echo $styl2; ?>" ><?php echo moneyFormatIndiaa($amount_paid_po);?></td>-->
		
		<td width="10%" <?= $styl; ?>><?php echo $pr_link.' <br> '.$noa_link; ?></td>
		
		<td width="10%" <?php echo $styl; ?>><?php echo $changed_by ;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $status.' <br>'. $pending_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $po_remark.' ' .$approval_status.'<BR>'.$approved_date;?></td>
		
		<td width="4%" style="text-align:right;">
	<!--			<a href="edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
			
			<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
			<?php //include "view_history.php"; ?>
			&nbsp;
	<?php //if($status=='Approved'){ ?>	
			<a href="pur_order_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>-->
	<?php //} ?>
	<?php if($status=='Draft'){ ?>	
			<a href="py_delete_func.php?sub=delete&po_id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
		</td>
	<?php } ?>
    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
			  
<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  echo $paginate;
?>
			  
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

  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Export Purchase order data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="po_export_func.php?sub=pdf" target="_blank" method="POST" >
                            <input type="hidden" id="mode" value='Export'>
                            <input type="hidden" id="tempId">
<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">-->
							
							<div class="form-group">
                                
								<div class="col-sm-4">
									<label class="control-label">From Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="fromDate" name="from_date" required="required" >
									</div>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">To Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="toDate" name="to_date" >
									</div>
								</div>
							</div>
								
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Compnay</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							
							<div class="form-group">
                                <div class="col-sm-6">
									<label for="itemCategory" class="control-label"> Supplier</label>
									<select class="form-control" name="supplier_id" id="toSupplier" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>
							
                                </div>
                            
								<div class="col-sm-6">
                                <label class="control-label">Department</label>
									<select class="form-control" name="department" id="departMent" <?php echo $readonly; ?> >
										<option value=""> Select </option>
										<option value=""> All </option>
											<?php $sql = "select * from sma_department order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
                                </div>
                            </div>
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Add Item-->
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

  $(function () {
        $("#prtablea").DataTable();
		$("#prtableb").DataTable();
    });

	
function getsearchf(id){
    var sub = 'sub1';
//alert(id);

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}
		
</script>

</body>
</html>

