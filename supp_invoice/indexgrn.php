<?php
include("../header.php");
$modulePath = "supp_invoice/";

	$help_code = $modulePath.'indexgrn.php';
	include "../help_code.php";
	
$pgname = $help_code;
include("../viewonly.php");
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        GRN <small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">GRN</li>
      </ol>
    </section>

<div class="col-md-12">
	<div class="box">
		<div class="box box-info">
            <div class="box-header with-border">
              
			<?php 
			
				$targetpage = "indexgrn.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss']  = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					
					$_SESSION['search_data'] = $_POST['search_data'];
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
					$search_own = $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
					$_SESSION['Createdby'] ='';
					
				}
				
				/* if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
					<form class="form-horizontal" action="indexgrn.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Supplier Name</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_party_mst order by party_name  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($comp_id == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['party_name'];?></option>
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
										<option value="U" <?php echo ($status == 'U')?'selected="selected"':'';?>> Unpaid </option>
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
							<a href="indexgrn.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
				
						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-3">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> PO.Srno. </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> JV Created </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> JV Synched </option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> JV Pending</option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deleted </option>
								</select>											
							</div>
							
							<span id="getsearchf">
							<?php if( $searchf=='N' || $searchf=='S' || $searchf=='P' ){ ?>	
								<div class="col-md-3">
								<?php if( $searchf=='N' || $searchf=='P' ){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>"  <?php echo ($search_data == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
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
				
							<input class="btn btn-success" type="submit" value="Self Created" name="Createdby">&nbsp;&nbsp;&nbsp;
						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						?>
								
							<?php 
								$role		= $_SESSION['role'];		
							?>
									<span class="pull-right"><?php for($x=0;$x<10;$x++){echo '&nbsp;';}?></span>
							<?php //if ( $role=='Billdesk'  ){ ?>		
									<span class="pull-right"><a href="addgrn.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>Create </a></span>
							<?php //} ?>	
								<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "grn_export_func.php?sub=pdf"?>" class="btn btn-primary">Report</a> &nbsp;&nbsp;</span>
								
								<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "si_summary_report.php?sub=pdf"?>" class="btn btn-primary">Summary</a> &nbsp;&nbsp;</span>
														   
						</div>
						
				</form>

            <div class="pull-right">
				<span class="sepV_c marginRight">
				
				</span>
			</div>
			</div>
		</div>	
	
    
	<div class="box-body">
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			
			<th>Sr.No.</th>
			<th>Created Date</th>
			<th>Invoice Date</th>
			<th>Tax.Inv.No.</th>
			<th style="text-align:right;">Quantity</th>
			<th>Our PO Ref.NO.</th>
			<th>Supplier Name</th>
			<th>Payment Status</th>
		    <th>By</th>
			<th>Pending With</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
    
		</tr>
	</thead>
<tbody>
<?php

	//$sql="SELECT * from sma_supplier_invoice order by id desc";
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	
	if($status=='U'){
		$searchfu = 'U';
		$status ='';
	}
	
	$role			= $_SESSION['role'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
	
	$sql = "SELECT * from sma_workflow where doc_type= 'SI' ";
		
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$to_value 			= $row['to_value'];
	$approval_role_1	= $row['approval_role_1'];
	$approval_role_2 	= $row['approval_role_2'];
	$approval_role_3 	= $row['approval_role_3'];
				
	//echo $role. ' <<<>>> ' . $user. ' '. $usrid;
	
					
			$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid )   ";
			$query="SELECT count(*) as num  from sma_supplier_invoice where company_id in ( $comid )   ";

			$sql .= " and (grn_status = 'Draft' or grn_status = 'Submitted' or grn_status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or grndraft_by = '$user' ) ";
/* 			(grn_approver = '$usrid' && (grn_approver_status='Submitted' || grn_approver_status='Approved')) ||
			(approver_2 = '$usrid' && (approver_2_status='Submitted' || approver_2_status='Approved')) ||
			(approver_3 = '$usrid' && (approver_3_status='Submitted' || approver_2_status='Approved')) ";
 */								
			$query .= " and (grn_status = 'Draft' or grn_status = 'Submitted' or grn_status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or grndraft_by = '$user' ) ";
					
			if ($user =='Admin' || $primaryrole =='COO' || $primaryrole == 'Director' ){
				$sql="SELECT * from sma_supplier_invoice where id > 0";
				$query="SELECT count(*) as num  from sma_supplier_invoice where id > 0 ";
			}
			
			if($viewonly=='Y' ){
				$sql	= "SELECT * from sma_supplier_invoice where id > 0 and company_id in ($comid ) ";
				$query	= "SELECT count(*) as num  from sma_supplier_invoice where id > 0 and company_id in ( $comid ) ";
			}
			
			if($_POST['Createdby'] || $_SESSION['Createdby'] ){
				$_SESSION['Createdby'] = $_POST['Createdby'];	
				$sql="SELECT * from sma_supplier_invoice where company_id in ( $comid )  and grndraft_by = '$user' ";
				$query="SELECT count(*) as num  from sma_supplier_invoice where company_id in ( $comid )  and grndraft_by = '$user' ";
			}
					
					if ($comp_id){
						$sql .= " and suplier_name =  '$comp_id' ";
						$query .= " and suplier_name =  '$comp_id' ";
					}
					if ($status){
						$sql .= " and grn_status = '$status' ";
						$query .= " and grn_status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					
					if( $search_own == 'Y' ){
						$sql .= " and grndraft_by = '$user' ";
						$query .= " and  grndraft_by = '$user'  ";
					}
				
					if($searchf=='R'){
						$sql .= " and tally_status in ( 'R') ";
					}
					if($searchf=='T'){
						$sql .= " and tally_status in ('U', 'C') ";
					}
					if($searchf=='U'){
						$sql .= " and tally_status not in ('U', 'R', 'C') ";
					}
					
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and invoice_date >= '$start_date' and invoice_date <= '$end_date' ";
						$query .= " and invoice_date >= '$start_date' and invoice_date <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					if($searchf=='S'){
						$sql .= " and our_po_ref_no in ( SELECT id FROM `sma_purchase_order` where project = '$search_data' ) ";
						$query .= " and our_po_ref_no in ( SELECT id FROM `sma_purchase_order` where project = '$search_data' ) " ;
					}
					if($searchf=='P'){
						$sql .= " and our_po_ref_no = '$search_data' ";
						$query .= " and our_po_ref_no = '$search_data' " ;
					}
					//Active / Deactive records
	
					if($searchf=='V'){
						$sql 	.= " and del = 'Y' ";
						$query 	.= " and del = 'Y' ";
					}
					else {
						$sql 	.= " and del != 'Y' ";
						$query 	.= " and del != 'Y' ";
					}
					
					if($searchfu=='U'){
						
						//$sql .= " and status = 'Completed' ";
						//$sql .= " and utr_no ='' ";
						
						/*$sql .= " and id in ( SELECT c.supp_id FROM payment_header b, payment_details c 
							WHERE 1 and b.id = c.payment_hdr_id and b.del !='Y' and b.utr_no ='' ) ";
						$query 	.= " and id in ( SELECT c.supp_id FROM payment_header b, payment_details c 
							WHERE 1 and b.id = c.payment_hdr_id and b.del !='Y' and b.utr_no ='' )	";
							
						*/
					}
					
//echo $sql."<BR>";
			
					//$qresult = mysqli_query($con,$query);
					//echo mysqli_error($con);
					//$total_pages = mysqli_fetch_array($qresult);
					
					$qresult = mysqli_query($con,$sql);
					
					$total_pages = mysqli_affected_rows($con) ;
					//$total_pages = $total_pages[num];
					
					//$total_pages = $total_pages + $raffect;
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($_GET['same_page']){
						$page = $_GET['same_page'];
					}
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	

					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}
					
					//$_SESSION['sqlex'] = $sql;	
					if (!empty($comp_id)  ){
						
						$sql .= " and suplier_name =  '$comp_id' ";
						$query .= " and suplier_name =  '$comp_id' ";
					
					}
					
					if (!empty($comp_id) || !empty($searchf) ){
					
						$_SESSION['sqlex'] = $sql;	
					
					}
					
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
		
		$si_hdr_id = $row['id'];
		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];


		$sql 	= "select sum(qty) as qty from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$tot_qty = $r2['qty'];
		
		if($tot_qty>1){
            $tot_qty = moneyFormatIndiaa($tot_qty);
		}
		$our_po_ref_no = $row['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$po_number 	= $r1['po_number'];
		$po_rev			= $r1['po_rev'];
		if($po_rev>0){
			$po_number 	= $po_number .'-'.	$po_rev;
		}
		
		$draft_by_supplier = $row['draft_by_supplier'];
		$changed_by = $row['grndraft_by'];			
		
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];
		
				$grn_approver			= $row['grn_approver'];
				
				$grn_approval_status	= $row['grn_approval_status'];
				
				if($grn_approval_status == 'Submitted'){
					$pending_by  = $grn_approver;	
				}
				
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
		$due_date = date('d-m-Y', strtotime($row['due_date']));
		if($due_date=='01-01-1970'){$due_date='';}
		
		$grn_status =$row['grn_status'];
		$del  = $row['del'];
		if($del=='Y'){
			$grn_status ='Deleted';
		}
		
		$si_id = $row['id'];
		$sql = "SELECT b.*, a.our_po_ref_no FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.grn_status = 'Completed'  ";
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$affected_row  = mysqli_affected_rows($con);
		$utr_no 	= $r2['utr_no'];
		$st_flag 	= $r2['st_flag'];
		
		$paid_status ='';	
		if(!empty($utr_no)){
			$paid_status = 'Paid'.'/'.$utr_no ;
		}
		else {
			$paid_status = 'Unpaid';
		}
		
		$sql = "SELECT b.* FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and a.advance_flag = 'Y' ";
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
					WHERE a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id 
					AND st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";				
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$amount_paid_po = $amount_paid_po + $r2['amount_paid_po'];
			$utr_no_po		= $r2['utr_no_po'];
			$paid_date_po	= $r2['paid_date_po'];
			if(!empty($utr_no_po)){
				$paid_date = date('d-m-Y', strtotime($paid_date_po));
				if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
					$paid_date='';
				}
				$paid_status = 'Paid'.'/'.$utr_no_po ;
			}
		}
		
		if( $searchfu == 'U' && $paid_status != 'Unpaid' ){
			//|| $status!='Completed'
			continue;
		}
		
		$styl = "";
		$del = $row['del'];
		if($del=='Y'){
			$styl = "style='color:red;'";
			$styla = "color:red;";
		}
		
		$invoice_date = date('d-m-Y', strtotime($row['invoice_date']));
		if($invoice_date=='30-11--0001'){
			$invoice_date='';
		}	
		
		
		$rid = $row['id'];
		$baseurl1 = $baseurl.$modulePath.'editgrn.php?sub=edit&id='.$row["id"].'&page='.$page;;
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "editgrn.php?sub=edit&id=". $row['id'].'&page='.$page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<!--<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>-->
		<td width="3%" style="text-align:right;<?= $styla; ?>"><?php echo $row['id']?></td>
		<td width="10%" <?= $styl; ?>><?php echo date('d-m-Y', strtotime($row['created_date']));?></td>
		<td width="10%" <?= $styl; ?>><?php echo $invoice_date;?></td>
		<td width="10%" <?= $styl; ?>><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;<?= $styla; ?>"><?php echo ($tot_qty)?></td>
		<td width="15%" <?= $styl; ?>><?php echo $po_number;?></td>
		<td width="17%" <?= $styl; ?>><?php echo $supplier_name;?></td>
		<td width="09%" <?= $styl; ?>><?php echo $paid_status;?></td>
		<td width="09%" <?= $styl; ?>><?php echo $changed_by. ' '.$draft_by_supplier ;?></td>
		
		<td width="10%" <?= $styl; ?>><?php echo $pending_by;?></td>
		<td width="08%"  <?= $styl; ?>><?php echo $grn_status;?></td>
		<td width="08%" <?= $styl; ?>><?php echo $row['grn_approval_status'];?></td>

    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>

<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo $paginate;
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  
?>

	</div>
    </div>
	
	
  <!-- Modal Add Item-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Purchase Requisition data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="si_export_func.php?sub=pdf" target="_blank" method="POST" >
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
									<label for="itemCategory" class="control-label"> Supplier</label>
									<select class="form-control" name="supplier_id" id="supplierId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
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

</div>	


<?php
include("../footer.php");
?>

<!-- jQuery 2.2.3 -->
<script src="<?php echo $baseurl . "plugins/jQuery/jquery-2.2.3.min.js"?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $baseurl . "bootstrap/js/bootstrap.min.js"?>"></script>
<!-- DataTables -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"?>"></script>

<!-- SlimScroll -->
<script src=<?php echo $baseurl . "plugins/slimScroll/jquery.slimscroll.min.js"?>"></script>
<!-- FastClick -->
<script src="<?php echo $baseurl . "plugins/fastclick/fastclick.js"?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $baseurl . "dist/js/app.min.js"?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $baseurl . "dist/js/demo.js"?>"></script>
<!-- page script -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>


<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

<script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true
    });
	
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

