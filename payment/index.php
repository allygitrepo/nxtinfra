<?php
include("../header.php");
$modulePath = "payment/";
$usrid  = $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
$pgname = $help_code;
include("../viewonly.php");
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Payment Entry
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Payment Entry</li>
      </ol>
    </section>

<div class="col-md-12">
	 <div class="box">
		<div class="box box-info">
			<?php 
				
				$targetpage = "index.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if ( $_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					$_SESSION['search_own'] = $_POST['search_own'];
					
					
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['statuss'];
					$approval_status= $_SESSION['approval_status'];
					$searchf 		= $_SESSION['searchf'];
					$search_own		= $_SESSION['search_own'];
					$search_data 	= $_SESSION['search_data'];
					$start_date 	= $_SESSION['start_date'];
					$end_date 		= $_SESSION['end_date'];
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
				}
				
				/* if(!$_POST['Save']){
					$search_own='Y';
				}	
				 */
			?>
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
<?php
	$user   = $_SESSION['user'];
	$role	= $_SESSION['role'];
	$comid  = $_SESSION['comid'];
	//$sql="SELECT * from payment_header order by id desc";

//echo $role.' ' . $user."<BR>"; exit();

	?>								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where 1 and comp_id in ($comid) order by comp_name ";
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
										<option value="Holding" <?php echo ($status == 'Holding')?'selected="selected"':'';?>> Holding Reason </option>
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
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Paid To Supplier</option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> Paid To Employee</option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> PO.SrNo. </option>
									
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> UTR.No.</option>
									
									<option value="E" <?php echo ($searchf == 'E')?'selected="selected"':'';?>> JV Created </option>
									<option value="L" <?php echo ($searchf == 'L')?'selected="selected"':'';?>> JV Synched </option>
									<option value="C" <?php echo ($searchf == 'C')?'selected="selected"':'';?>> JV Pending</option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									<!--<option value="X" <?php echo ($searchf == 'X')?'selected="selected"':'';?>> Operating Exp </option>-->
									<!--<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Travel Exp </option>-->
									<!--<option value="M" <?php echo ($searchf == 'M')?'selected="selected"':'';?>> Reimbursement </option>-->
									<!--<option value="A" <?php echo ($searchf == 'A')?'selected="selected"':'';?>> Travel Advance </option>-->
									<option value="I" <?php echo ($searchf == 'I')?'selected="selected"':'';?>> Supplier Invocie </option>
									<option value="J" <?php echo ($searchf == 'J')?'selected="selected"':'';?>> Supplier Advance </option>
									<option value="F" <?php echo ($searchf == 'F')?'selected="selected"':'';?>> Compliances </option>
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> Retention </option>
								</select>
								
							</div>

							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='U' || $searchf=='R' || $searchf=='P' || $searchf=='X'  || $searchf=='I' || $searchf=='J' ){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N' || $searchf=='R' || $searchf=='P' || $searchf=='X' || $searchf=='I' || $searchf=='J' ){ 
										?>
									
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
									<option value=""> All</option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
							<?php }
							
							?>
							<?php if($searchf=='U'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
									<option value=""> All</option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
							<?php if($searchf=='D'){ 
								$start_date = date('d-m-Y', strtotime($start_date));
								$end_date = date('d-m-Y', strtotime($end_date));
							?>	
								<label class="col-lg-1 control-label">Start.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>';
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
								
									
									<span class="pull-right"><?php for($x=0;$x<8;$x++){echo '&nbsp;';}?></span>
									
									<span class="pull-right"><a href="payment_export.php?sub=pdf" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp; Report </a></span>
									
									
									<span class="pull-right"><a href="grid_utrno_edit.php" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Grid UTR No. Edit </a>&nbsp;&nbsp;</span>
						<?php 
							if ( $viewonly!='Y'){ ?>						
									<span class="pull-right"><a href="add.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create </a>&nbsp;&nbsp;</span>
						<?php } ?>			
									
						</div>

						
				</form>

		</div>	
	
   
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
		    <th>Holding Reason</th>
			<th>Pending With</th>
			<th>Account Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->

		</tr>
	</thead>
<tbody>
<?php	
	$user   = $_SESSION['user'];
	$role	= $_SESSION['role'];
	$comid = $_SESSION['comid'];
	//$sql="SELECT * from payment_header order by id desc";

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];

	$sql   = "SELECT * from payment_header where 1 ";
	$query = "SELECT count(*) as num  from payment_header where 1 ";
	
	/* if(empty($searchf) && empty($comp_id) ){
		$sql .= " and draft_by = '$user' || 
				(approver_1 = '$usrid' && approver_1_status='Submitted' ) || 
				( approver_2 = '$usrid' && approver_2_status='Submitted' ) || 
				(approver_3 = '$usrid' && approver_3_status='Submitted' ) ";
				
				$query .= " and draft_by = '$usrid' || 
				(approver_1 = '$usrid' && approver_1_status='Submitted' ) || 
				( approver_2 = '$usrid' && approver_2_status='Submitted' ) || 
				(approver_3 = '$usrid' && approver_3_status='Submitted' )";
	}
	else {
		//$sql .= " and draft_by = '$user' ";
		$sql .= "";
	} */		
					
	if($user=='Admin' || $primaryrole=='COO' || $primaryrole =='Journal F&A'){
		$sql="SELECT * from payment_header where id > 0  ";
		$query="SELECT count(*) as num  from payment_header where id > 0 ";
	}
	
	if($viewonly=='Y' ){
		$sql   = "SELECT * from payment_header where id > 0 and company_id in ( $comid ) ";
		$query = "SELECT count(*) as num  from payment_header where id > 0 and company_id in ( $comid ) ";
	}
	
	//echo $_POST['comp_id']. ' <<>> '. $comp_id;
			
	if ($comp_id){
		$sql .= " and company_id = '$comp_id' ";
		$query .= " and company_id = '$comp_id' ";
	}
	if ($status=='Holding'){
		$sql .= " and hold_reason != '' ";
		$query .= " and hold_reason != '' ";
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
						$sql .= " and paid_date >= '$start_date' and paid_date <= '$end_date' ";
						$query .= " and paid_date >= '$start_date' and paid_date <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					
					
					if($searchf=='F'){
						$sql .= " and st_flag = 'M' ";
						$query .= " and st_flag = 'M' ";
					}
					if($searchf=='O'){
						$sql .= " and st_flag = 'R' ";
						$query .= " and st_flag = 'R' ";
					}
					
					if($searchf=='P'){
						$qr = "SELECT payment_hdr_id FROM `sma_supplier_invoice` a , payment_details b where a.id = b.supp_id and a.our_po_ref_no = '$search_data' ";	
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['payment_hdr_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno )";
						$query .= " and id in ( $pno ) " ;
					}
					
					if($searchf=='M'){
						$qr = "SELECT c.id as reg_id FROM `sma_travel_expenses` a , payment_details b , payment_header c where a.id = b.supp_id and c.id = b.payment_hdr_id and c.st_flag = 'T' and a.exp_type = 'R' ";	
						
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['reg_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno ) and st_flag = 'T' ";
						$query .= " and id in ( $pno ) and st_flag = 'T'  " ;
					}
					
					if($searchf=='T'){
						$qr = "SELECT c.id as reg_id FROM `sma_travel_expenses` a , payment_details b , payment_header c where a.id = b.supp_id and c.id = b.payment_hdr_id and c.st_flag = 'T' and a.exp_type = 'T' ";	
				//echo $qr;		
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['reg_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno ) and st_flag = 'T' ";
						$query .= " and id in ( $pno ) and st_flag = 'T'  " ;
					}
					
					if($searchf=='X'){
						$qr = "SELECT c.id as reg_id FROM `sma_travel_expenses` a , payment_details b , payment_header c where a.id = b.supp_id and c.id = b.payment_hdr_id and c.st_flag = 'C' and a.exp_type = 'C' ";	
						
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['reg_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno ) and st_flag = 'C' ";
						$query .= " and id in ( $pno ) and st_flag = 'C' " ;
					}
					
					//Search Supplier Invoice 
					if($searchf=='I' && !empty($search_data) ){
						$qr = " SELECT payment_hdr_id FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id 
								and a.st_flag = 'S' and b.supp_id = '$search_data' ";	
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['payment_hdr_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno )";
						$query .= " and id in ( $pno ) " ;
					}
					
					//Search PO Advance 
					if($searchf=='J' && $search_data >0){
						$qr = " SELECT payment_hdr_id FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id 
								and a.st_flag = 'D' and b.supp_id = '$search_data' ";	
						$qre = mysqli_query($con,$qr);
						echo mysqli_error($con);
						$pno = '';
						while($tr = mysqli_fetch_array($qre)){
							$pno .= $tr['payment_hdr_id'].',';
						}	
						$pno .= '0';
						$sql .= " and id in ( $pno )";
						$query .= " and id in ( $pno ) " ;
					}
					
					if($searchf=='R' ){
						if(empty($search_data)){
							$sql .= " and utr_no = '' ";
							$query .= " and  utr_no  = '' ";							
						}
						else {
							$sql .= " and utr_no like '%". $search_data ."%' ";
							$query .= " and  utr_no like '%". $search_data ."%' ";
						}
					}
					
					if($search_own=='Y'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}
					
					$sqlvn ='';
						
					if($searchf=='S'){
						if(!empty($search_data)){
							$sql .= " and paid_to = '$search_data' and (st_flag ='S' OR st_flag ='D' OR st_flag ='C' )";
							$query .= " and paid_to = '$search_data' and (st_flag ='S' OR st_flag ='D'  OR st_flag ='C' ) " ;
						}
						else {
							$sql .= "  and st_flag ='S' OR st_flag ='D' ";
							$query .= " and st_flag ='S' OR st_flag ='D' " ;
						}	
					}
					if($searchf=='U'){
						if(!empty($search_data)){
							$sql .= " and paid_to = '$search_data' and (st_flag ='A' OR st_flag ='T') ";
							$query .= " and paid_to = '$search_data' and (st_flag ='A' OR st_flag ='T')" ;
						}
						else {
							$sql .= "  and st_flag ='A' OR st_flag ='T' ";
							$query .= " and st_flag ='A' OR st_flag ='T' " ;
						}
						
					}
					
					if($searchf=='X'){
						$sql .= " and st_flag = 'C' ";
						$query .= " and st_flag = 'C' ";
					}
					else if($searchf=='T'){
						$sql .= " and (st_flag = 'T'  or st_flag = 'R') ";
						$query .= " and (st_flag = 'T'  or st_flag = 'R') ";
					}
					else if($searchf=='A'){ //Tranvel Advance
						$sql .= " and st_flag = 'A' ";
						$query .= " and st_flag = 'A' ";
					}
					else if($searchf=='I'){ //Supplier Invoice
						$sql .= " and st_flag = 'S' ";
						$query .= " and st_flag = 'S' ";
					}
					else if($searchf=='J'){  //Supplier Advance
						$sql   .= " and st_flag = 'D' ";
						$query .= " and st_flag = 'D' ";
					}
					
									
					//Active / Deactive records
					if($searchf=='V'){
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
					
					//Tally Updated 
					
					if($searchf=='E'){ //Tally Updated Ready
						$sql .= " and tally_status in ('R') ";
					}
					if($searchf=='L'){ //Tally Updated 
						$sql .= " and tally_status in ('U') ";
					}
					if($searchf=='C'){ //Tally Updated UnTick
						$sql .= " and tally_status not in ('U', 'R') ";
					}
//echo $sql;
					/* $qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					$total_pages = $total_pages[num];
					 */
					
					$qresult = mysqli_query($con,$sql);
					$total_pages = mysqli_affected_rows($con);
					
					
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
//		echo $sql;

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
	
//echo $sql."<BR>";
	
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
			$st_flag_v ='Supp Invoice';
		}
		else if($st_flag=='A'){
			$st_flag ='TA';
			$st_flag_v ='Travel Advance';
		}
		else if($st_flag=='T'){
			$st_flag ='TE';
			$st_flag_v ='Travel Expenses';
		}
		else if($st_flag=='C'){
			$st_flag ='OE';
			$st_flag_v ='OpEx';
		}
		else if($st_flag=='D'){
			$st_flag_v ='Supp. Advance';
		}
		else if($st_flag=='R'){
			$st_flag_v ='Retention';
		}
		else if($st_flag=='M'){
			$st_flag_v ='Compliances';
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
				
				
		/* if($st_flag =='OE' ){
			$sql = "SELECT * FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$supp_id' ";
			//echo $sql; exit();
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$supplier_invoice_no  = $r2['invoice_no'].' ' ;
		} */
		
			$approval_status = $row['approval_status'];
			if($approval_status == 'Rejected' ){
				$approval_status = $row['status'];
			}	
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
				//$styl = "style='bgcolor:powderblue;color:red;' ";
				//$styl2 = "bgcolor:powderblue;color:red; ";
				$approval_status = 'Deleted';
			}
			
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
		<td width="8%"<?php echo $styl; ?>><?php echo $supp_id . '-' . $st_flag_v;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo moneyFormatIndiaa($row['total_amount_paid']);?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['hold_reason'];?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $pending_by;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $tally_status_a;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $approval_status;?></td>

<!--	
		<td width="5%" style="text-align:right;">
		<a href="edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>

		<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
			<?php include "view_history.php"; ?>
		
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
<!--		</td>-->
    </tr>
	</a>
	
	<?php }
	
	

?>
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

