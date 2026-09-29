<?php
include("../header.php");
$modulePath = "retention/";

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
$pgname = $help_code;
include("../viewonly.php");

	//sma_retention_invoice
	$sql = " SELECT * FROM sma_supplier_invoice WHERE 1 and del != 'Y' and status = 'Completed' and company_id in ( $comid ) and ( retention_amount >0 || compliances_amount > 0 ) ";
//echo $sql. "<BR>";	
	$qry = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($r2 	= mysqli_fetch_array($qry)){
    	
    	$si_id = $r2['id'];
    	$retention_amount    = $r2['retention_amount'];
        $compliances_amount  = $r2['compliances_amount'];
        	    
    	    $sql = "SELECT * FROM sma_retention_invoice where si_hdr_id = '$si_id' ";
//echo $sql. "<BR>";	    	    
    	    $res = mysqli_query($con, $sql);
    	    $rowcnt = mysqli_affected_rows($con);
    		echo mysqli_error($con);
    		if($rowcnt==0){
        		$r3 	= mysqli_fetch_array($res);
        		
        	    if($retention_amount> 0){
        	        $sql = " INSERT INTO sma_retention_invoice (si_hdr_id, dedtype, `supplier_invoice_no`, `invoice_type`, `created_date`, `invoice_date`, `invoice_received_date`, `company_id`, `location`, `department`, `suplier_name`, `supplier_location`, `trans_type`, `bill_no`, `bill_date`, `retention_flag`, `total_amount`, `payable_amount`, `bal_amount`, `retention_amount`, `retention_remarks`, `bal_retention_amount`, `compliances_amount`, `compliances_remarks`, `bal_compliances_amount`, `recovery_amount`, `recovery_remarks`, `bal_recovery_amount`, `hold_amount`, `hold_remarks`, `bal_hold_amount`, `delivery_challen_no`, `delivery_date`, `delivery_mode`, `our_po_ref_no`, `our_pr_no`, `gst_flag`, `tds_percentage`, `transport_lr_no`, `lr_date`, `transporter_name`, `credit_days`, `due_date`, `supplier_gst_no`, `state`, `description`,  `status`, `approval_status`, `draft_by_supplier`, `draft_by`, draft_date, payable_retention_amount) 
        	        SELECT '$si_id', 'R', `supplier_invoice_no`, `invoice_type`, `created_date`, `invoice_date`, `invoice_received_date`, `company_id`, `location`, `department`, `suplier_name`, `supplier_location`, `trans_type`, `bill_no`, `bill_date`, `retention_flag`, `total_amount`, `payable_amount`, `bal_amount`, `retention_amount`, `retention_remarks`, '0', '', '0', `bal_compliances_amount`, `recovery_amount`, `recovery_remarks`, `bal_recovery_amount`, `hold_amount`, `hold_remarks`, `bal_hold_amount`, `delivery_challen_no`, `delivery_date`, `delivery_mode`, `our_po_ref_no`, `our_pr_no`, `gst_flag`, `tds_percentage`, `transport_lr_no`, `lr_date`, `transporter_name`, `credit_days`, `due_date`, `supplier_gst_no`, `state`, `description`,  'Draft', '', `draft_by_supplier`, `draft_by`, now() , retention_amount
        	        FROM sma_supplier_invoice where id = '$si_id' and retention_amount > 0 ";
        	        mysqli_query($con, $sql);
        	    }
        	    
        	    if($compliances_amount> 0){
        	        $sql = " INSERT INTO sma_retention_invoice (si_hdr_id, dedtype, `supplier_invoice_no`, `invoice_type`, `created_date`, `invoice_date`, `invoice_received_date`, `company_id`, `location`, `department`, `suplier_name`, `supplier_location`, `trans_type`, `bill_no`, `bill_date`, `retention_flag`, `total_amount`, `payable_amount`, `bal_amount`, `retention_amount`, `retention_remarks`, `bal_retention_amount`, `compliances_amount`, `compliances_remarks`, `bal_compliances_amount`, `recovery_amount`, `recovery_remarks`, `bal_recovery_amount`, `hold_amount`, `hold_remarks`, `bal_hold_amount`, `delivery_challen_no`, `delivery_date`, `delivery_mode`, `our_po_ref_no`, `our_pr_no`, `gst_flag`, `tds_percentage`, `transport_lr_no`, `lr_date`, `transporter_name`, `credit_days`, `due_date`, `supplier_gst_no`, `state`, `description`,  `status`, `approval_status`, `draft_by_supplier`, `draft_by`, draft_date, payable_compliances_amount ) 
        	        SELECT '$si_id', 'C', `supplier_invoice_no`, `invoice_type`, `created_date`, `invoice_date`, `invoice_received_date`, `company_id`, `location`, `department`, `suplier_name`, `supplier_location`, `trans_type`, `bill_no`, `bill_date`, `retention_flag`, `total_amount`, `payable_amount`, `bal_amount`, '0', '', '0', `compliances_amount`, `compliances_remarks`, `bal_compliances_amount`, `recovery_amount`, `recovery_remarks`, `bal_recovery_amount`, `hold_amount`, `hold_remarks`, `bal_hold_amount`, `delivery_challen_no`, `delivery_date`, `delivery_mode`, `our_po_ref_no`, `our_pr_no`, `gst_flag`, `tds_percentage`, `transport_lr_no`, `lr_date`, `transporter_name`, `credit_days`, `due_date`, `supplier_gst_no`, `state`, `description`,  'Draft', '', `draft_by_supplier`, `draft_by`, now() , compliances_amount
        	        FROM sma_supplier_invoice where id = '$si_id' and compliances_amount > 0 ";
        	        mysqli_query($con, $sql);
        	    }
    	    
    	}
	}
	
//exit('####1');	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        <?= $sub_menu;?> <small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active"><?= $sub_menu;?></li>
      </ol>
    </section>

<div class="col-md-12">
	<div class="box">
		<div class="box box-info">
            <div class="box-header with-border">
              
			<?php 
			
				$targetpage = "index.php?sub=list"; 
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
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
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
										<option value="P" <?php echo ($status == 'P')?'selected="selected"':'';?>> Paid </option>
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
							<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
				
						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-3">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="I" <?php echo ($searchf == 'I')?'selected="selected"':'';?>> Invoice Date </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Company Name & Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> PO.Srno. </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> Retention </option>
									<option value="C" <?php echo ($searchf == 'C')?'selected="selected"':'';?>> Compliances </option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deleted </option>
								</select>											
							</div>
							
							<span id="getsearchf">
							<?php if( $searchf=='N' || $searchf=='M' || $searchf=='S' || $searchf=='P' || $searchf=='B' ){ ?>	
								<div class="col-md-3">
								<?php if( $searchf=='N' || $searchf=='P' || $searchf=='M' ){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S' || $searchf=='B'){ ?>
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
							
							<?php if($searchf=='D' || $searchf=='I'){ 
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
				
							
						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						?>
								
							<?php 
								$role		= $_SESSION['role']; 
								
							?>
															   
						</div>
						
						<?php if($searchf=='B'){ ?>
						<div class="form-group">
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
						</div>	
						<?php } ?>		
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
			<th>Supplier Name</th>
			<th>Dated</th>
			<th>Tax.Inv.No.</th>
			<th>PO Number</th>
			<th style="text-align:right;">Amount</th>
			
			<th>Deduction Type</th>
		    
			<th>Status</th>
			
		</tr>
	</thead>
<tbody>
<?php

	//$sql="SELECT * from sma_retention_invoice order by id desc";
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	
	if($status=='U'){
		$searchfu = 'U';
		$status ='';
	}
	if($status=='P'){
		$searchfu = 'P';
		$status ='';
	}
	
	$sqlw = "";
	if($searchfu=='P' || $searchfu=='U'){
	    
	    $sql = "SELECT c.* FROM payment_header b, payment_details c where 1 and b.id = c.payment_hdr_id and st_flag in ('M', 'R' ) ";
		$q2  = mysqli_query($con, $sql);
		while($r2  = mysqli_fetch_array($q2)){
			$supp_id 	.= $r2['supp_id'].',';
	    }
	    $supp_id 	.= '0';
	    if($searchfu=='P'){
	        $sqlw = " AND id in ( $supp_id ) ";
	    }
	    if($searchfu=='U'){
	        $sqlw = " AND id not in ( $supp_id ) ";
	    }
	}
	
	$role			= $_SESSION['role'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
	
			$sql="SELECT * from sma_retention_invoice where 1 and status != 'Rejected' and company_id in ( $comid ) and ( retention_amount >0 || compliances_amount > 0 ) " .$sqlw;
			$query="SELECT count(*) as num  from sma_retention_invoice where 1 and status != 'Rejected' and company_id in ( $comid ) ". $sqlw;

					
			if ($user =='Admin' || $primaryrole =='COO' || $primaryrole == 'Director' ){
				$sql="SELECT * from sma_retention_invoice where 1 and status != 'Rejected' and id > 0  and ( retention_amount >0 || compliances_amount > 0 )".$sqlw;
				$query="SELECT count(*) as num  from sma_retention_invoice where 1 and status != 'Rejected' and id > 0  and ( retention_amount >0 || compliances_amount > 0 ) ".$sqlw;
			}
			
			if( $viewonly=='Y' || $accountant_role=='M' || $accountant_role=='Y' ){
				$sql	= "SELECT * from sma_retention_invoice where 1 and status != 'Rejected' and id > 0 and company_id in ($comid ) and ( retention_amount >0 || compliances_amount > 0 ) ".$sqlw;
				$query	= "SELECT count(*) as num  from sma_retention_invoice where 1 and status != 'Rejected' and id > 0 and company_id in ( $comid ) and ( retention_amount >0 || compliances_amount > 0 )".$sqlw;
			}
			
					if ($comp_id){
						$sql .= " and suplier_name =  '$comp_id' ";
						$query .= " and suplier_name =  '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					if( $search_own == 'Y' ){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}
				
					if($searchf=='R'){
						$sql .= " and dedtype in ( 'R') ";
					}
					if($searchf=='C'){
						$sql .= " and dedtype in ( 'C') ";
					}

					if($searchf=='B'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and company_id = '$search_data' and created_date >= '$start_date' and created_date <= '$end_date' ";
						$query .= " and company_id = '$search_data' and created_date >= '$start_date' and created_date <= '$end_date' ";
					}
					
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and created_date >= '$start_date' and created_date <= '$end_date' ";
						$query .= " and created_date >= '$start_date' and created_date <= '$end_date' ";
					}
					if($searchf=='I'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and invoice_date >= '$start_date' and invoice_date <= '$end_date' ";
						$query .= " and invoice_date >= '$start_date' and invoice_date <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					if($searchf=='M'){
						$sql .= " and supplier_invoice_no = '$search_data' ";
						$query .= " and  supplier_invoice_no = '$search_data' ";
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
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					//$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}
					//
					if (!empty($comp_id)  ){
						
						$sql .= " and suplier_name =  '$comp_id' ";
						$query .= " and suplier_name =  '$comp_id' ";
					
					}
				//echo $comp_id;	
					$comid = $_SESSION['comid'];
					if (!empty($comp_id) || !empty($searchf) || !empty($comid )){
					
						 $_SESSION['sqlex'] = $sql;	
					
					}
					
	
					$sql .= ' order by id desc ';

					$sql .= " LIMIT $start, $limit ";
//echo $total_pages. "<BR>";
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
		
		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];
		
		$draft_by_supplier = $row['draft_by_supplier'];			
		
		$changed_by = $row['draft_by'];			
		
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];
		
				
				
		$status =$row['status'];
		$del  = $row['del'];
		if($del=='Y'){
			$status ='Deleted';
		}	

       
		$si_id = $row['id'];

        $pending_by = '';
	
            //$status = $status_v ;
            if($status=='Draft'){
                $status = 'Processing for Payment';
            }
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
				$username = $r2['username'];
				if(!empty($username)){
				    $pending_by  = ' To '.$r2['username'];
				}
				
		
		$styl = "";
		$del = $row['del'];
		if($del=='Y'){
			$styl = "style='color:red;'";
			$styla = "color:red;";
		}
		
		$total_amount = $row['total_amount'];
		$total_amount = moneyFormatIndiaa($total_amount);
		
		$rid = $row['id'];
				
        $retention_amount       = $row['retention_amount'];
        $compliances_amount     = $row['compliances_amount'];
       	
        $dedtype =  $row['dedtype'];	
        if($dedtype=='R'){
			$deduction_type = 'Retention';
        }	
        if($dedtype=='C'){
			$deduction_type = 'Compliances';
			$retention_amount = $compliances_amount;
        }
        $pstat ='';	
        if($dedtype=='R'){
					
			$sql = "SELECT b.* FROM payment_header b, payment_details c where c.supp_id = '$si_id' and b.id = c.payment_hdr_id and st_flag = 'R' ";
			$q2  = mysqli_query($con, $sql);
			$pycnt = mysqli_affected_rows($con);
			$r2  = mysqli_fetch_array($q2);
			$utr_no 	= $r2['utr_no'];
			$paid_date 	= $r2['paid_date'];
			if(!empty($utr_no)){
				$pstat = 'Paid';
			}
			else if($pycnt>0){
			    $pstat = 'Payment Prepared';
			}
		}
		else if($dedtype=='C'){
					
	        $sql = "SELECT b.* FROM payment_header b, payment_details c where c.supp_id = '$si_id' and b.id = c.payment_hdr_id and st_flag = 'M' ";
			$q2  = mysqli_query($con, $sql);
			$pycnt = mysqli_affected_rows($con);
			$r2  = mysqli_fetch_array($q2);
			$utr_no 	= $r2['utr_no'];
			$paid_date 	= $r2['paid_date'];
			if(!empty($utr_no)){
				$pstat = 'Paid';
		    }
		    else if($pycnt>0){
			    $pstat = 'Payment Prepared';
			}
		}
		
		$our_po_ref_no = $row['our_po_ref_no'];
		$sql = "SELECT * FROM sma_purchase_order  where id = '$our_po_ref_no'  ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_number 	= $r2['po_number'];
		
        $baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&dedtype='.$dedtype.'&id='.$row["id"].'&page='.$page;
        
?>
		
    	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&dedtype='.$dedtype.'&id=". $row['id'].'&page='.$page;?>" title="Edit">
    	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
    		<!--<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>-->
    		<td width="3%" style="text-align:right;<?= $styla; ?> "><?php echo $row['id']?></td>
    		<td width="17%"  <?= $styl; ?>><?php echo $supplier_name;?></td>
    		<td width="11%" <?= $styl; ?>><?php echo date('d-m-Y', strtotime($row['created_date']));?></td>
    		<td width="10%" <?= $styl; ?> ><?php echo $row['supplier_invoice_no'];?></td>
    		<td width="10%" <?= $styl; ?> ><?php echo $po_number;?></td>
    		
    		<td width="10%" style="text-align:right;<?= $styla; ?>"><?php echo moneyFormatIndiaa($retention_amount)?></td>
    		
    		<td width="10%"  <?= $styl; ?>><?php echo $deduction_type. "<BR>".$pstat;?></td>
    		
    		<td width="08%" <?= $styl; ?>><?php echo $status. $pending_by;?></td>
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

