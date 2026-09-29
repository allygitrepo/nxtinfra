<?php

include("../header.php");
$modulePath = "travel_approval/";

$compid = $_SESSION['comid'];
$userid   	= $_SESSION['usrid'];
$role   	= $_SESSION['role'];

$body  = '';

	$help_code = 'direct_expense_payment.php';
	include "../help_code.php";
	
$pgname = $help_code;
include("../viewonly.php");	

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
	//$sql = " delete from sma_travel_expenses where exp_type = 'D' and emp_id = '' and company_id = '' ";
	$yesterday_date = date("Y-m-d");
    $sql = " delete from sma_travel_expenses where exp_type = 'D' and emp_id = '' and company_id = '' and draft_dated < '$yesterday_date' ";
	mysqli_query($con, $sql);
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Direct Expense Payment
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard_athang.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Direct Expense Payment</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				<?php 
			
				$targetpage = "direct_expense_payment.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				//echo $_POST['comp_id']. " <<>>";
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss']  = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					if ($_POST['searchf']){
						//$_SESSION['comp_id'] = '';
						//$_SESSION['statuss']  = '';
						$_SESSION['approval_status'] = '';
					}	
					
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
					
				}
				/* if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
					<form class="form-horizontal" action="direct_expense_payment.php?sub=list" method="post">
                      
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
							<a href="direct_expense_payment.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
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
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> OWN </option>
									<option value="A" <?php echo ($searchf == 'A')?'selected="selected"':'';?>> Against AP.Srno. </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Ready for Tally Update </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> Tally Updated </option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> Tally Unticked</option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									
								</select>
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='A'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N' || $searchf=='A'){ ?>
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
						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						?>
								<!--<span>
									<div class="col-md-2" ><b class="btn btn-info">Own</b>&nbsp;&nbsp;
										<input type="checkbox"  <?php echo $checked;?> id="search_own" name="search_own" value="Y" >
									</div>
								</span>-->
								
				</form>
				
			<?php if ($user=='Payment@athaanginfra.in' || $role == 'Billdesk' || $user == 'Admin'){ ?>	
				<span class="pull-right"><a href="direct_expense_payment.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create </a>&nbsp;&nbsp;&nbsp;</span>
			<?php } ?>	
				<!--	<span class="pull-right"><a href="#modalExport"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalExport" class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;
											   
				</span>-->
				<span class="pull-right"><a href="regular_exp_export_func.php?sub=pdf&gtype=C" target="_blank" class="btn btn-primary"> Report </a>&nbsp;&nbsp;&nbsp;</span>
				
				
				<span class="pull-right"><a href="travelexp_summary_report.php?sub=pdf&gtype=C" target="_blank" class="btn btn-primary"> Summary </a>&nbsp;&nbsp;&nbsp;</span>
				
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Tally Status</th>
			<th>Date</th>
			<th>Total Amount</th>
			<th>Payment Status</th>
			<th>By</th>
			<th>Pending With</th>
			<th>Status</th>
			<th>Decision</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "travel_approval/";
	
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	$primary_role = $_SESSION['primary_role'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
		
	if($status=='U'){
		$searchfu = 'U';
		$status ='';
	}	
	
	if($searchf=='S'){
		$comid = $search_data;			
	}
					
	$department = $_SESSION['department'];
	//and draft_by = '$user'
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'D' and company_id in ( $comid )  and emp_id!='' and status != 'Withdraw' ";
		
	
	if ($user=='Admin' && $primaryrole =='COO' || $primaryrole == 'Director' ){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'D' ";
		$query = " SELECT count(*) as num from sma_travel_expenses where exp_type = 'D' ";
		
	}
	
	if($viewonly=='Y'){
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'D' and company_id in ( $comid )";
		$query = " SELECT count(*) as num from sma_travel_expenses where exp_type = 'D' company_id in ( $comid )";
	}	

	//$sql .= " and emp_id !='' and company_id !='' ";
	//$query .= " and emp_id !='' and company_id !='' ";

	$sql .= " and company_id !='' ";
	$query .= "  and company_id !='' ";

	
//status = 'Submitted' and

//echo $department . ' ' ;
//echo $sql;
					if ($comp_id){
						$sql .= " and emp_id =  '$comp_id' ";
						$query .= " and emp_id =  '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					
						/* if (  ($status) || ($approval_status)){
							$sql .= " or draft_by = '$user' ";
							$query .= " or draft_by = '$user' ";
						} */
					
					if($searchf=='R'){
						$sql .= " and tally_status in ('U') ";
					}
					if($searchf=='T'){
						$sql .= " and tally_status in ('R') ";
					}
					if($searchf=='U'){
						
						$sql .= " and tally_status not in ('U', 'R') ";
					}

					if($searchf=='A'){
						$sql .= " and approval_number = '$search_data' ";
						$query .= " and  approval_number = '$search_data' ";
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
					
					if( $search_own == 'Y' ){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}
					if($searchf=='S'){
						$sql .= " and company_id = '$search_data' ";
						$query .= " and company_id = '$search_data' " ;
					}
					//if( $searchf == 'U' ){
					//	$sql .= " and paid_status != 'Paid' ";
					//	$query .= " and paid_status != 'Paid' ";
					//}
					
					if($searchfu=='U'){
						$sql .= " and id  in (SELECT supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.utr_no ='' and b.st_flag = 'C') ";
						$query .= " and id  in (SELECT supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.utr_no ='' and b.st_flag = 'C')";
						$searchfu = 'U';
						//echo $sql;
					
					}
					
					/* if(empty($searchf) && empty($search_own) ){
						if (empty($status) && empty($comp_id) && empty($approval_status) && $user!='Admin' && $primaryrole!='COO' && empty($viewonly) ){
							$sql .= " and ( draft_by = '$user' ) ";
							$query .= " and ( draft_by = '$user' ) ";
						}
					} */
					
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
					
					$_SESSION['sqlrp'] = $sql;
					
					$_SESSION['sqlreg'] = $sql;
					
					/* 
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					*/
					
					$qresult = mysqli_query($con,$sql);
					
					$_SESSION['sqlex'] = $sql;	
		
//echo $sql;	
					
	// Initial page num setup
 					$total_pages = mysqli_affected_rows($con);
					//$total_pages = $total_pages[num];
					
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
					
					$sql .= ' order by id desc ';
					
					$sql .= " LIMIT $start, $limit ";
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}
					

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

		$tally_status  = $row['tally_status'];
		if($tally_status=='R' || $tally_status=='C'){
			$tally_status_a = 'Ready for Tally Upload';
		}	
		else if($tally_status=='U'){
			$tally_status_a = 'Updated to Tally';
		}
		else {
			$tally_status_a = 'Unticked';	
		}
		
		$company_id  = $row['company_id'];
		
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		$comp_code 	= $r1['comp_code'];

		$sma_vendor_id = $row['emp_id'];
		$sql  = "SELECT * from sma_party_mst where id = '$sma_vendor_id' ";
//echo $sql;		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$party_name		= $r1['party_name'];
		
		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount, sum(gst_amount) as gst_amount FROM `sma_expenses` where exp_type = 'D' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'] + $r1['gst_amount'];
		 
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
		
		$changed_by = $row['changed_by'];
		if(empty($changed_by)){
			$changed_by = $row['draft_by'];
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
				
		$ce_id = $row['id'];
		$sql = " SELECT a.* FROM payment_header a, payment_details b where b.supp_id = '$ce_id' and  a.id = b.payment_hdr_id and a.st_flag = 'C' and a.del !='Y' and a.status = 'Completed' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$utr_no = $r2['utr_no'];
		$paid_status ='';	
		if(!empty($utr_no)){
			$paid_status = 'Paid'.'/'.$utr_no ;
		}
		else {
			$paid_status = 'Unpaid';
		}	
		
		$baseurl1 	= $baseurl.$modulePath1.'direct_expense_payment.php?sub=edit&id='.$row["id"].'&page='.$page;
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "direct_expense_payment.php?sub=edit&id=". $row['id']?>'&page='.<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="20%"<?php echo $styl; ?>><?php echo $party_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="15%"<?php echo $styl; ?>><?php echo $tally_status_a?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $paid_status;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $changed_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $pending_by;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $status;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
		
<!--		<td width="5%" style="text-align:right;">
		<a href="direct_expense_payment.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		
		</td>
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
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){
        $id = $_GET['id'];
		
			$sql = "SELECT a.reference, a.amount, c.budget_id, b.company_id , b.status
					FROM `sma_expenses` a, sma_travel_expenses b, sma_product_cost_center c 
					where a.approval_ref_no = b.id and b.exp_type = 'D' and b.id = '$id' and a.reference = c.product_id and c.company_id = b.company_id ";
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->reference;
				$amount			= $r->amount;
				$budget_id		= $r->budget_id;
				//$budget_head	= $r->budget_head;
				$company_id		= $r->company_id;
				$status			= $r->status;
				
				if($status=='Completed'){
					$sql = " UPDATE `sma_budget` set used_budget = used_budget - $amount 
						where id = '$budget_id' ";
				
					mysqli_query($con, $sql);
				}
				
			}
			
		//$sql="delete from sma_travel_expenses where exp_type = 'D' and id='$id' ";
		$sql = "update sma_travel_expenses set del = 'Y' where exp_type = 'D' and id='$id' ";
		 
        $query1 = mysqli_query($con, $sql);
		echo 	mysqli_error($con);
        echo 	'<script>window.location.href="direct_expense_payment.php?sub=list";</script>';
		exit();
		
	}
?>

<?php

	if(isset($_POST['editItem'])){

		$rid     			= $_POST['rid'];
		
		$dated				= date('Y-m-d', strtotime($_POST['dated']));
		$approval_ref_no	= $_POST['approval_ref_no'];
		$reference 			= $_POST['reference'];
		$invoice_no 		= $_POST['invoice_no'];
		$amount 			= $_POST['amount'];
		$gst_amount			= $_POST['gst_amount'];
		$note 				= $_POST['remarks'];
		$gst_perc 			= $_POST['gst_perc'];
		$budget_name		= $_POST['budget_name'];
		$budget_head 		= $_POST['budget_head'];
		$total_budget 		= $_POST['total_budget'];
		$balance_budget 	= $_POST['balance_budget'];
		$budget_id 			= $_POST['budget_id'];
		$approval_number 	= $_POST['approval_number'];
		
		$gst_amount	= $amount * $gst_perc / 100;
		
			$sql = "SELECT * FROM `sma_expenses` where id = '$rid' and exp_type = 'D' ";
			$q2  = mysqli_query($con, $sql);
			$total_amount = 0 ;
			$r2  = mysqli_fetch_array($q2);
			$amount_p 			= $r2['amount'];
			$gst_amount_p 		= $r2['gst_amount'];
			$budget_id_p		= $r2['budget_id'];
		
		$sql = " SELECT * FROM sma_budget WHERE id = '$budget_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$budget_name 	= $r2['budget_name'];
		$budget_head	= $r2['budget_head'];
			
		$sql = "update `sma_expenses` set dated	= '$dated',
						reference 		= '$reference',
						invoice_no 		= '$invoice_no',
						amount 			= '$amount',
						gst_amount		= '$gst_amount',
						note 			= '$note',
						gst		 		= '$gst_perc',
						budget_name		= '$budget_name',
						budget_head 	= '$budget_head',
						balance_budget  = '$balance_budget',
						budget_id 		= '$budget_id'
				WHERE id = '$rid' ";		
		mysqli_query($con, $sql);

		if(empty($approval_number)){
			$sql = " update sma_budget SET 
					blocked_budget = blocked_budget - ($amount + $gst_amount) + ($amount_p + $gst_amount_p ) , 
					used_budget= used_budget + ($amount + $gst_amount) - ($amount_p + $gst_amount_p ) 
						WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
		}	
		else {
			$sql = " update sma_budget SET 
					used_budget= used_budget + ($amount + $gst_amount) - ($amount_p + $gst_amount_p ) 
						WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
		}
//echo $sql."<BR>";

		$sql = " SELECt * FROM sma_travel_expenses where id = '$approval_ref_no' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		$approval_ref_no = $r2['id'];
//echo $sql."<BR>";		
		$sql = " update sma_approval_expenses SET 
					bal_amount = bal_amount + $amount - $amount_p 
						WHERE reference = '$reference' and approval_hdr_id = '$approval_number' ";
//echo $sql."<BR>";	
		mysqli_query($con, $sql);
		
//exit('EXIT HERE....');
		
//	echo "<meta http-equiv='refresh' content='0'>";    
	//$baseurl.=$modulePath.'edit.php?approval_ref_no='.$approval_ref_no.'&active=active&987';
	//echo "<script>window.location.href='$baseurl';</script>";
	echo "<script>window.location.href='direct_expense_payment.php?sub=edit&id=$approval_ref_no';</script>";
	exit();
	
}
 
?>
 
<?php if($_GET['sub'] == 'add'){
?>


<?php
	if(isset($_POST['Save'])){
			
  			$id 				= $_POST['id'];
			$ce_id				= $_POST['id'];
			$emp_id 			= $_POST['sma_vendor_id'];
			$to_supplier		= $_POST['sma_vendor_id'];
			$company_id 		= $_POST['company_id'];
			$trans_type			= $_POST['trans_type'];
			$dated				= date('d-m-Y', strtotime($_POST['dated']));
			$approval_ref_no	= $_POST['approval_ref_no'];
			$datedd				= date('Y-m-d', strtotime($_POST['dated']));
			$approval_number 	= $_POST['approval_number'];
			$remarks	 	 	= $_POST['remarks_hdr'];
			$location			= $_POST['location'];
			
			$sql 	= "select * from company where comp_id = '$company_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$oe_limit_amount = $r2['oe_limit_amount'];
											
			if($total_amount>$oe_limit_amount && !empty($approval_number)){
				echo $total_amount . ' <<<>>>';
				echo "<script>alert('Note: Require Approval Memo for above Rs.$oe_limit_amount');window.location.href='direct_expense_payment.php?sub=list';</script>";
				exit();
			}
			
			//$purchase_requisition =	$_POST['purchase_requisition'];
			
			$status 			= 'Draft';

			$user   = $_SESSION['user'];
			
			$sql = "select count(*) as cnt from sma_travel_expenses where id = '$ce_id' ";
			$query=mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($query);
			$nct	=	$r2['cnt'];
			if($nct==0){
				$ce_id ='';
			}	
				$exp_type			= 'D';
				$sql="Insert into sma_travel_expenses ( id, exp_type, emp_id, company_id, dated, approval_ref_no, status, draft_by, draft_dated , total_amount, approval_number, trans_type, location ) values ( '$ce_id', '$exp_type', '$emp_id', '$company_id', '$datedd', '$approval_ref_no',  'Draft', '$user', now(), '$total_amount', '$approval_number', '$trans_type', '$location' ) ";
//echo $sql."<BR>";	
			$query=mysqli_query($con, $sql);
			$ce_id=mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = "UPDATE sma_travel_expenses set approval_ref_no = '$ce_id' where id = '$ce_id' ";
			mysqli_query($con, $sql);
			
		
		   
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status) values( 'DE', '$ce_id', '$userid', now(), 'Draft' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//			echo "Supplier Invoice - Against Approval  successful added";
//	exit();
	
			echo "<script>window.location.href='direct_expense_payment.php?sub=edit&id=$ce_id';</script>";
			
			//echo '<script>window.location.href="direct_expense_payment.php?sub=list";</script>';
			exit();
			
		}
	
							$sql = "select max(id) as id from sma_travel_expenses ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$re_id	=	$r2['id'] + 1;
							
							$exp_type			= 'D';
							$status 			= 'Draft';
							$user   			= $_SESSION['user'];
							
?>

    <section class="content-header">
        <h1>
            Direct Expense Payment
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Direct Expense Payment</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="direct_expense_payment.php?sub=add" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class=" control-label">SrNo.</label>
								<input type="text" class="form-control" id="approval_ref_No" name="approval_ref_no" readonly value="<?php echo $re_id; ?>">
								
							</div>
							
							<div class="col-md-2">
								<label class=" control-label">Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" required autocomplete="off" readonly <?php echo $readonly; ?> value="<?php echo date('d-m-Y');?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<div class="col-md-4">
								<label class=" control-label">Company </label>
								<select class="form-control" name="company_id" id="company_ID" required onchange="getlocation(this.value);getworkflow(this.value);" > 
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($compid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
						
							<label class="col-lg-2 control-label">Supplier</label>
							<div class="col-md-4">
							<span id="getsupplier123">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required  onchange="getpangst(this.value)">
                             		<option value=""> Select </option>
								<?php 
									$sql = "select * from sma_party_mst where 1 and party_kyc = 'Y' order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</span>	
							</div>
						
						</div>
						
						<div class="form-group col-md-12">
							<span id="getpangst">
							
							</span>
						
						</div>
						
						
						<div class="form-group">
							
							<div class="col-sm-5">
								<label for="company_id" class="control-label ">Workflow Type *</label>
							<span id="getworkflow">	
								<select class="form-control select3" name="trans_type" id="trans_type" required >
                             		<option value=""> Select </option>
										
								</select>
							</span>	
							</div>	
							
							<div class="col-sm-2">
								<label for="location" class="control-label ">Location</label>
								<span id="getlocation">	
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
										
									</select>	
								</span>
								
                            </div>
							
						</div>	
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">RTGS Narration</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="2" name="remarks_hdr"  autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
						
						</div>
						
			
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'direct_expense_payment.php?sub=list';?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
</section>  
<?php } 	?>

<?php

if(isset($_POST['editTally'])){
		 
		$record_id     		= $_POST['record_id'];
		$si_hdr_id 			= $_POST['si_hdr_id'];
		$doc_no 			= $_POST['si_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_id 		= $_POST['account_id'];
		$amount 			= $_POST['amount'];
		$amount_prev 	    = $_POST['amount_prev'];
		
		$narration 			= $_POST['narration'];
		$effect 			= $_POST['effect'];
		$sql = "update `tally_journal_entry` set 
					record_id     		= '$record_id',
					account_type 		= '$account_type',
					account_id 			= '$account_id',
					amount 				= '$amount',
					narration 			= '$narration',
					effect 				= '$effect'
				where doc_no = '$si_hdr_id' and record_id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);

//exit();
		/* $sql = "SELECT * FROM sma_product where id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		$doc_type 		= 'DE';
		 */
		$doc_type 		= 'DE';
		
			$sql = " update tally_journal_entry set amount = amount - $amount + $amount_prev  where  record_id = '$record_id' ";
			
			//doc_type = '$doc_type' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'B' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'direct_expense_payment.php?sub=edit&id='.$si_hdr_id.'&active5=active&zyx';
		echo "<script>window.location.href='$baseurl';</script>";
		
	}
	
?>

<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id			    = $_POST['id']; 
			$re_id			= $_POST['id']; 
			$company_id 	= $_POST['company_id'];
			$trans_type 	= $_POST['trans_type'];
			$emp_id		 	= $_POST['sma_vendor_id'];
			$total_amount 	= $_POST['total_amount'];
			$dated		 	= date('Y-m-d', strtotime($_POST['dated']));
			$invoice_received_date = date('Y-m-d', strtotime($_POST['invoice_received_date']));
			$approval_ref_no= $_POST['approval_ref_no'];
			$remarks	 	= $_POST['remarks_hdr'];
			$approval_number =	$_POST['approval_numbera'];
			
			$tally_status			= $_POST['tally_status'];
			$tally_narration		= $_POST['tally_narration'];

			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			$status				= $_POST['status'];
			$gst_flag			= $_POST['gst_flag'];
			
			$location			= $_POST['location'];
			
			$cash_bank_name		= $_POST['cash_bank_name'];
			$cheque_no 			= $_POST['cheque_no'];
			$prepared_dated 	= date('Y-m-d', strtotime($_POST['prepared_dated']));
			$total_amount_paid 	= $_POST['total_amount_paid'];
			$rtgs_narration		= $_POST['rtgs_narration'];
			
			$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$re_id' and exp_type = 'D' ";
				$q2  = mysqli_query($con, $sql);
//echo $sql. "<BR>";
			$total_amount = 0 ;
			while($r2 = mysqli_fetch_assoc($q2)){
				
				$total_amount += $r2['amount']+ $r2['gst_amount'];
				
			}

			$sql = "update sma_travel_expenses set company_id ='$company_id',
						approval_ref_no	= '$approval_ref_no',
						emp_id			= '$emp_id',
						trans_type		= '$trans_type',
						approval_number	= '$approval_number',
						total_amount    = '$total_amount',
						remarks 		= '$remarks',
						tally_narration	= '$tally_narration',
						gst_flag		= '$gst_flag',
						location		= '$location',
						cash_bank_name		= '$cash_bank_name',
						cheque_no 			= '$cheque_no',
						prepared_dated 		= '$prepared_dated',
						total_amount_paid 	= '$total_amount_paid',
						rtgs_narration		= '$rtgs_narration',
						invoice_received_date = '$invoice_received_date'
					where id='$id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if( $status == 'Draft' ){

				$email_approval_expected_role_1			= $_POST['email_approval_expected_role_1'];
				$email_approval_expected_role_2			= $_POST['email_approval_expected_role_2'];
				$email_approval_expected_role_3			= $_POST['email_approval_expected_role_3'];
				$email_approval_expected_role_4			= $_POST['email_approval_expected_role_4'];
				$email_approval_expected_role_5			= $_POST['email_approval_expected_role_5'];
				$email_approval_expected_role_6			= $_POST['email_approval_expected_role_6'];
				$email_approval_expected_role_7			= $_POST['email_approval_expected_role_7'];
				
				$email_approval_received_1				= $_POST['email_approval_received_1'];
				$email_approval_received_2				= $_POST['email_approval_received_2'];
				$email_approval_received_3				= $_POST['email_approval_received_3'];
				$email_approval_received_4				= $_POST['email_approval_received_4'];
				$email_approval_received_5				= $_POST['email_approval_received_5'];
				$email_approval_received_6				= $_POST['email_approval_received_6'];
				$email_approval_received_7				= $_POST['email_approval_received_7'];
				
				$sql = "UPDATE sma_travel_expenses SET 
								email_approval_expected_role_1 	= '$email_approval_expected_role_1',
								email_approval_expected_role_2 	= '$email_approval_expected_role_2',
								email_approval_expected_role_3 	= '$email_approval_expected_role_3',
								email_approval_expected_role_4 	= '$email_approval_expected_role_4',
								email_approval_expected_role_5 	= '$email_approval_expected_role_5',
								email_approval_expected_role_6 	= '$email_approval_expected_role_6',
								email_approval_expected_role_7 	= '$email_approval_expected_role_7',
								email_approval_received_1		= '$email_approval_received_1',
								email_approval_received_2		= '$email_approval_received_2',
								email_approval_received_3		= '$email_approval_received_3',
								email_approval_received_4		= '$email_approval_received_4',
								email_approval_received_5		= '$email_approval_received_5',
								email_approval_received_6		= '$email_approval_received_6',
								email_approval_received_7		= '$email_approval_received_7'
							WHERE id = '$id' ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_travel_expenses set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
//echo $sql."<BR>";
			
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'DE', '$re_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
//echo $sql."<BR>";				
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$modulePath = "travel_approval/";
				
				$baseurl1 =$baseurl.$modulePath.'direct_expense_payment.php?sub=edit&id='.$re_id;
				
				$msg = 'Direct Expenses Memo Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
				$msg1 = 'Direct Expenses';
				$doc_type = 'DE';
				include "te_mail.php";
				
			}
//exit();			
			
			if($tally_status=='R' || $tally_status=='C'){
				$sql="update sma_travel_expenses set tally_ticked_by = '$user', tally_status = '$tally_status', tally_updated_on = now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set status = '$tally_status', narration = '$tally_narration' where doc_no = '$id' and doc_type = 'DE' ";
				$r2  = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE

			}			
	
			// add attachments
			// file upload
			$doc_invoice_no		= $_POST["doc_invoice_no"];
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST["share_point_link"];
			$arrFUDoc 			= $_FILES["fudoc"];
		
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/de/" . $re_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} 
				$filename 		= $arrFUDoc['name'][$i];
				$tmpFileName 	= $arrFUDoc['tmp_name'][$i];
				if(!empty($filename)){
					$sql = "INSERT INTO file_uploads ( module, file_name, file_path, share_point_link, doc_type, doc_desc, reference_id, date_uploaded, doc_invoice_no ) VALUES( 'DE', '$filename', '$folder_path', '$share_point_link[$i]', '$arrDocType[$i]', '$arrDocDesc[$i]', '$re_id', now(), '$doc_invoice_no[$i]' )";
					if (mysqli_query($con, $sql)){
							move_uploaded_file($tmpFileName, $folder_path. "/" . $filename);
					}
					else {
							echo "Error: " . mysqli_error($con);
					}
						
				}
			}	
		
//echo $sql;			
//exit();
			$page					= $_POST['page']; 
			echo '<script>window.location.href="direct_expense_payment.php?sub=list";</script>';
			
		}
		
		$page = $_GET['page'];
		
		$id = $_GET['id'];
		$approval_ref_no = $_GET['approval_ref_no'];
		
		$sql="Select * from sma_travel_expenses where exp_type = 'D' and id ='$id' ";

//echo $sql;
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$re_id = $row['id'];
		$_SESSION['re_id'] = $row['id'];
		$status = $row['status'];
		$_SESSION['status'] = $status;

		$tally_status 	= $row['tally_status'];
		$tally_updated_on	= $row['tally_updated_on'];
		$tally_ticked_by 	= $row['tally_ticked_by'];
		$approval_number	= $row['approval_number'];
		
		$draft_by = $row['draft_by'];
		$paid_status = $row['paid_status'];
		$del 	  = $row['del'];
	
		$dated  	= date('d-m-Y', strtotime($row['dated']));
		$fyr		= date('Y', strtotime($dated));
		$fmth		= date('m', strtotime($dated));
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}	
		$_SESSION['finance_year'] = $fin_year;
		
		$approval_received_count = 0 ;			
		$email_approval_received_1	= $row['email_approval_received_1'];
		$email_approval_received_2	= $row['email_approval_received_2'];
		$email_approval_received_3	= $row['email_approval_received_3'];
		$email_approval_received_4	= $row['email_approval_received_4'];
		$email_approval_received_5	= $row['email_approval_received_5'];
		$email_approval_received_6	= $row['email_approval_received_6'];
		$email_approval_received_7	= $row['email_approval_received_7'];
		if($email_approval_received_1=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}
		if($email_approval_received_2=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_3=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_4=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_5=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_6=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_7=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}
		
		
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
		$approver_4_status 	= $row['approver_4_status'];									
		$approver_5_status 	= $row['approver_5_status'];									
		$approver_6_status 	= $row['approver_6_status'];									
		$approver_7_status 	= $row['approver_7_status'];									
		$approver_8_status 	= $row['approver_8_status'];									
										
		$approval_status = $row['approval_status'];
	
		$readonly = '';
		if ($status != 'Draft'){
			$readonly = 'READONLY';
		}
		
		if ($del == 'Y'){
			$readonly = 'READONLY';
		}
		
?>

    <section class="content-header">
        <h1>
            Direct Expense Payment
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Direct Expense Payment</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="direct_expense_payment.php?sub=edit" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php 
							$status = $row['status'];
							if ($del == 'Y'){
								$status = 'Deleted';
							}
						?>
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b></h4> </span>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . 'direct_expense_payment.php?sub=list&same_page='.$page; ?>" class="btn btn-danger" >Back </a>&nbsp;&nbsp;&nbsp;</span>
		                
					<?php
						
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						
						if ($_GET['next']){
							$active = $_GET['next'];
							$active_1 = ' ';
						}
						if($_GET['active5']){
							$active_1 ='';
							$active_tab5 = $_GET['active5'];
						}
						if($_GET['active8']){
							$active_1 ='';
							$active8 = $_GET['active8'];
						}
						
						$company_id 		= $row['company_id'];
						$emp_id 			= $row['emp_id'];
						
						$sql 	= "SELECT * FROM company WHERE comp_id = $company_id ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$oe_limit_amount = $r2['oe_limit_amount'];
						
						
						
						
						if(!empty($approval_number)){
							$sql  = " SELECT * from sma_approval_memo where id = '$approval_number' ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$location 			= $r1['location'];
						}
						else {
							$location = $row['location'];
							
						}	
						
							$sql = " select * from sma_location where id = '$location' ";
							$q2  = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_object($q2);
							$loc_name 	= $r2->loc_name;
							$loc_gst_no 	= $r2->loc_gst_no;
					?>
                   
				   <ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Direct Expense Payment</a></li>
						<?php if($role!='Maker'){ ?>
							  <li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >Tally Journal</a></li>
						<?php } ?>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>
				
						<li><a href="axis_neft_prn.php?sub=pdf&id=<?php echo $row['id'];?>&bank_id=<?php echo $row['cash_bank_name'];?>&r=1" class="btn btn-success"  target="_blank" >RTGS Print </a></li>
						
						<li><a href="voucher_prn.php?sub=pdf&id=<?php echo $re_id; ?>&company_id=<?php echo $company_id?>&vendor_id=<?php echo $emp_id;?>" class="btn btn-danger" target="_blank" >Voucher</a></li>
						
						
						  
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group">
						</div>
						
						<input type="hidden" name="page" value="<?= $page;?>">
						<input type="hidden" name="status" value="<?= $status;?>">
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">SrNo.</label>
								<input type="text" class="form-control" id="approval_ref_No" readonly  name="approval_ref_no" value="<?php echo $re_id; ?>">
							</div>
						<?php
							$dated				= date('d-m-Y', strtotime($row['dated']));
							if($dated=='01-01-1970'){ $dated='';}
							$approval_ref_no	= $re_id;
						?>						
						
							
							<div class="col-md-2">
								<label class=" control-label">Date </label>
								<input type="text" class="form-control" id="dated" name="dated" readonly placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
							</div>
					<?php
						$company_id = $row['company_id'];
						$sqlb = '';
						if($status !='Draft'){
							$sqlb = " AND comp_id = '$company_id' ";
						}	
					?>		
							<div class="col-md-4">
								<label class="control-label">Company </label>
								<select class="form-control" name="company_id" id="company_Id"  <?php echo $readonly ?> required onchange="getlocation(this.value);" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($compid) $sqlb order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Our company GST Number : </label><br>
								<label class="control-label"><?php echo $loc_gst_no; ?></label>
							</div>

						</div>
						
						<?php 
								$vendor_id = $row['emp_id'];
								$company_id = $row['company_id'];
								$party_id_doc = $row['emp_id'];			

									$sql 	= "select * from company where comp_id = '$company_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$comp_code = $r2['comp_code'];
									
									$sql 	= "select * from sma_party_mst where id = '$vendor_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$party_name = $r2['party_name'];
								
									$label_line .= '<b>Doc SrNo:</b>'.$row['id']. ' ' .
												' <b>Company:</b>'.$comp_code. ' ' . ' <b>Supplier Name :</b> ' .' '.$party_name		
						?>
					<div class="form-group">
						<label class="col-lg-1 control-label">Supplier</label>
							<div class="col-md-4">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" <?php echo $readonly ?> autocomplete="off" required onchange="getapproval(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where 1  order by party_name "; //party_kyc = 'Y' //and party_kyc = 'Y'
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['emp_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
						
					<?php	
						$sql = "SELECT * FROM sma_party_mst where id = '$vendor_id' "; 
						$result = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$ven = mysqli_fetch_array($result);
						$pan_no = $ven['party_pan_number'];
						$gst_no = $ven['party_gst_number'];
					?>				
						
							<label for="itemquote_ref_no" class="col-sm-1 control-label">PAN No.</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" readonly value="<?php echo $pan_no ?> " >
							</div>
							<label for="itemquote_ref_no" class="col-sm-2 control-label">Supplier GST No.</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" readonly value="<?php echo $gst_no ?> " >
							</div>		
						</div>	
						
					<?php 
							$company_id = $row['company_id'];
							$vendor_id  = $row['emp_id'];  
							
							$trans_type = $row['trans_type'];
							$sqlg = '';
							//echo $status;
							if($status != 'Draft'){
								$sqlg = " and b.id = '$trans_type' ";
							}
							else {
								$sqlg = " and b.status = 'Y' ";	
							}	
							
					?>
					
						<div class="form-group">
							<label for="company_id" class="control-label col-sm-2">Workflow Type *</label>
								
							<div class="col-sm-3">
								
								<select class="form-control select3" name="trans_type" id="trans_type" required <?php echo $readonly ?> >
                             		<option value=""> Select </option>
										<?php $sql = "SELECT distinct(b.id), b.workflow_type FROM `sma_workflow` a, sma_workflow_type b where b.id = a.trans_Type and company_id = '$company_id' and a.doc_Type = 'DE' " . $sqlg;
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label for="location" class="control-label col-sm-1">Location</label>
								
							<div class="col-sm-2">
								<span id="getlocation">	
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_location where loc_comp_id = '$company_id' order by loc_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
										<?php } ?>
									</select>	
								</span>
								
                            </div>
							
					<?php
						if(!empty($approval_number)){
					//approval_notes_prn.php?sub=pdf&id=87&comp_id=6&r=1		
						    $baseurl1 = $baseurl.'approval/'.'approval_notes_prn.php?sub=pdf&id='.$approval_number.'&comp_id='.$company_id;	
					?>		
							<label class="control-label col-sm-2">Document <br>
								<a href="<?= $baseurl1;?>" class="btn btn-success" target="_blank" >Approval Memo </a>	
							</label>
					<?php
						}
					?>

						</div>
						
						
						<div class="form-group">
							
							<div class="col-md-6">
								<label class=" control-label">Remark</label>
							
								<textarea class="form-control" rows="2" name="remarks_hdr" <?php echo $readonly321; ?>  autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
							
						<?php 	
							
							$sql = "SELECT b.* FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and a.supp_id = '$re_id' ";
							$q22 = mysqli_query($con, $sql);
							$r22 = mysqli_fetch_array($q22);
							$utr_no = $r22['utr_no'];
							if(!empty($utr_no)){
										
						?>	
							<div class="col-md-1">
								<input type="text" class="form-control" style="color:red;" readonly value="<?php echo $paid_status;?>">
							</div>
						<?php } ?>	
						<?php
						$sql ="SELECT b.* FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and a.supp_id = '$re_id' and st_flag = 'C' and b.del !='Y'  ";//and b.utr_no !=''
						//echo $sql. "<BR>";				
									$q2  = mysqli_query($con, $sql);
									$affectrow  = mysqli_affected_rows($con);
									if($affectrow>0){
										//echo "<BR>";	
										echo "<span class='label label-warning' style='font-size:14px;' > Payment</span>-";
									}	
									while($r2  = mysqli_fetch_array($q2)){
											
										$pay_no	= $r2['id'];
											
										$baseurl_py = $baseurl . "payment/edit.php?sub=edit&id=$pay_no";
										echo "<a href='$baseurl_py;' target='_blank'><span class='label label-warning' style='font-size:14px;' >".$pay_no .' </span></a>&nbsp;&nbsp;';
											
									}
								
								 $invoice_received_date = date('d-m-Y', strtotime($row['invoice_received_date']));
								if($invoice_received_date == '01-01-1970'){
									$invoice_received_date ='';
								}	
								
						?>						
						
							<div class="col-md-2">
									<label class="control-label">Invoice Received Date **</label>
									<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" required <?= $readonly;?> id="invoice_received_date" name="invoice_received_date" value="<?= $invoice_received_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
							
							
						</div>
						
						<input type="hidden" name="total_amount" id="total_amount" <?php echo $readonly ?>  value="<?php echo $row['total_amount'] ?>">
						
						<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>&nbsp;&nbsp; Product <span class="caret"></span></a></h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse in">
							
								<div class="panel-body">
								<?php if(!$readonly ){ ?>	
									<span class="pull-right">
										<a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
									</span>
										
								<?php } ?>	

						<div class="form-group">
							<div class="col-md-12">
							
								<span id="te_exp_edit">
									<table id="prtable" class="table table-bordered table-striped">
										 <tr>
												<th> SrNo.</th>
												<th> Product</th>
												<th> Cost Center Sub Group</th>
												<th> Invoice No.</th>
												<th> Invoice Date</th>
												<th style="text-align:right;"> Amount</th>
												<th style="text-align:right;"> TDS%</th>
												<th style="text-align:right;"> GST Amount</th>
												<th> Narration</th>
												<th style="text-align:right;"> Action</th>
										 </tr>
										
									<tbody>
									<?php
										$j = 0;
										$error_product_name = '';
										$invoice_no_exist=0;
										$error_budget_head ='';
										$modulePath1 = "travel_approval/";
										$errmsg = "";
								//	echo $ap_bal_value. "<>" . $approval_number;
										if($ap_bal_value<0 && $approval_number> 0 && $status == 'Draft' ){
											$errmsg = "<span style='color:red;'>Error : Expense Value exceed to Approval Memo !</span>";	
											echo $errmsg;
										}	
										$sql="SELECT budget_id as budget_id_cnt from sma_expenses where approval_ref_no = '$approval_ref_no' and exp_type = 'D' group by budget_id ";
										$result 	= mysqli_query($con, $sql);
										$budget_cnt	= mysqli_affected_rows($con);
										
										$sql="SELECT * from sma_expenses where approval_ref_no = '$approval_ref_no' and exp_type = 'D' ";
										$result 	= mysqli_query($con, $sql);
										$items_cnt	= mysqli_affected_rows($con);
										echo mysqli_error($con);
										while($row1 = mysqli_fetch_array($result)){
											$j = $j + 1;
											
											$reference = $row1['reference'];
											$budget_id = $row1['budget_id'];
											$sql="SELECT * from sma_product where id = '$reference'";
										//echo $sql;	
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$product_name = $r2['name'];
											if(empty($product_name)){
												$error_product_name = 'Error : Product Name should be available or Blank...';
											}
											
											$gst_amount = $row1['gst_amount'];
											$tds		= $row1['tds'];
											
											$tot_amount += $row1['amount'] + $gst_amount;
											
											$sql ="SELECT * FROM `sma_budget` where id = '$budget_id' ";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$budget_head = $r2['budget_head'];
											
											$sql ="SELECT * FROM `sma_budget_subgroup` where id = '$budget_head' ";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$budget_head = $r2['budget_head'];
											
											$dated = date('d-m-Y', strtotime($row1['dated']));
											if($dated=='30-11--0001' || $dated=='01-01-1970'){
												$dated='';
											}
											
											$dated_chk 	= $row1['dated'];
											$invoice_no = $row1['invoice_no'];
											$sql="SELECT * FROM sma_expenses a, sma_travel_expenses b WHERE b.del!='Y' and b.id = a.approval_ref_no and a.exp_type = 'D' and invoice_no = trim('$invoice_no') and a.approval_ref_no != '$approval_ref_no' and emp_id = '$vendor_id' and company_id = '$company_id' and a.dated >= '$finance_from_date' and a.dated <= '$finance_to_date' ";
											$q2 = mysqli_query($con, $sql);
											$invoice_no_exist = $invoice_no_exist + mysqli_affected_rows($con);	
											if($invoice_no_exist>0){
												$r2 = mysqli_fetch_array($q2);
												$dup_approval_ref_no = 'DE Ref.Srno. '.$r2['approval_ref_no']. ' Dated : ' . date('d-m-Y', strtotime($r2['dated']));	
												echo "<span style='color:red;'>Error : Invoice Number $invoice_no already available !
												<br>$dup_approval_ref_no</span>";
											}
											
											//$costcenter_name = '';
											if(empty($budget_id) || $budget_id ==0){ 
												$budget_head = "<b>Cost Center not Added in product master, Please Add !</b>";
												$stly = "style='Color:red;' ";
											}
											
											//start
									 $rid = $row1['id']; 
									if($budget_id =='0' or $status=='Draft' ){
										$sql="SELECT * FROM sma_product_cost_center where company_id = '$company_id' and product_id = '$reference' ";
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$cat = mysqli_fetch_array($res2);
										$budget_id_v = $cat['budget_id'];
						//echo $sql."<BR>";										
										$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_id_v' ";
										$cqry = mysqli_query($con,$sql);
						//echo $sql."<BR>";										
										echo mysqli_error($con);
										$com = mysqli_fetch_array($cqry);
										$budget_code 			= $com['budget_code'];
										$budget_head_id		 	= $com['id'];
										$budget_name 			= $com['budget_name'];
										$budget_head 			= $com['budget_head'];
										
										if(empty($budget_code)){ 
											$error_budget_head = "<b>Cost Center (For Tally) not Added in product master...</b>";
												$stly = "style='Color:red;' ";
										}
											
						//echo $sql."<BR>";								
										$fin_year = '2023-2024';
										$sql = "SELECT * FROM sma_budget where project = '$company_id' and budget_name = '$budget_name' 
														and budget_head = '$budget_head_id' and budget_code = '$budget_code' 
														and account_year = '$fin_year'  ";
										$res2 		= mysqli_query($con, $sql);
						//echo $sql."<BR>";				
										echo mysqli_error($con);
										$cat 		= mysqli_fetch_array($res2);
										$budget_id_a   = $cat['id'];
										
										if($budget_id_a != $budget_id){
											$sql = " UPDATE sma_expenses set budget_id = '$budget_id_a' where id = '$rid' ";	
											mysqli_query($con, $sql);
										}
										
									}
										
							// End	
							
											
									?>
										<tr>
											<td width="2%"><?php echo $j;?></td>
											<td width="20%"><?php echo $product_name;?></td>
											<td width="10%"><?php echo $budget_head;?></td>
											<td width="10%"><?php echo $row1['invoice_no'];?></td>
											<td width="10%"><?php echo $dated;?></td>
											<td width="10%" style="text-align:right;"><?php echo number_format($row1['amount'],2);?></td>
											<td width="05%" style="text-align:right;"><?php echo $tds;?></td>
											<td width="10%" style="text-align:right;"><?php echo number_format($gst_amount,2);?></td>
											<td width="15%"><?php echo $row1['note'];?></td>
											<td width="08%" style="text-align:right;">
											<?php $rid = $row1['id']; 
									if($status=='Draft'){		
											?>
											<a href='#modalEditItem<?= $rid;?>' data-id='<?= $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_dexp_func.php"; ?>							
<!-- Modal Edit Item-->						
												<!--<a href="direct_expense_payment.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
										<?php if (empty($readonly)){ ?>		
												<a href="delete_expenses.php?sub=delete&id=<?php echo $row1['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>&exp_type=D" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											</td>
											<?php } 
									}
									?>
										</tr>

										<?php }?>
									</tbody> 
										<?php $tot_amount = $tot_amount  ?>
										<tr> <th colspan="7" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo number_format($tot_amount,2); ?> </th><td colspan="1"></td></tr>
										
										<input type="hidden" id="total_amounTT"   value="<?php echo $tot_amount;?>" >
										
									<?php	
										$total_amounTT = $tot_amount;
										
										if($budget_cnt>1){
											$tot_amount = 0;
										}
									?>
										<input type="hidden" id="total_amounT"  value="<?php echo $tot_amount;?>" >
										
										<input type="hidden" id="amount_CGST"  value="<?php echo $gst_amount;?>" >
										
									</table> 
									
									
								</span>
							</div>	
						</div>
						
						</div>
						
				    </div>
								
				</div>
				
				
				<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepThree" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>&nbsp;&nbsp; Payment Details <span class="caret"></span></a></h4>
                            </div>
					<div id="stepThree" class="panel-collapse collapse in">
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Paid via<span style="color:red;"> **</span></label>
								<span id = "getbankname">
									<select class="form-control" id="cash_bank_name" name="cash_bank_name" required <?= $readonly;?> <?= $readonly;?> >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' and company_id = '$company_id' and company_id in ($comid) ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" <?php echo ($row['cash_bank_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['account_name']. ' | ' . $r2['account_number']; ?></option>
										<?php } ?>
									</select>
								</span>		
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Cheque /UTR Number</label>
								<input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="" value="<?= $row['cheque_no'];?>" >
							</div>
							
						<?php 
							
							$prepared_dated = date('d-m-Y', strtotime($row['prepared_dated']));
							if($prepared_dated=='30-11--0001' || $prepared_dated=='01-01-1970'){
								$prepared_dated=date('d-m-Y');
							}
											
						?>	
							<div class="col-md-2">
								<label class="control-label">Prepared Dated</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" <?= $readonly;?> id="prepared_dated" name="prepared_dated" placeholder="" value="<?php echo $prepared_dated; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
							
						<?php	
							
							$total_amount_paid = $row['total_amount_paid'];
							if($total_amount_paid==0){
								$total_amount_paid = $tot_amount;
							}
							
						?>	
							<div class="col-md-2">
								<label class="control-label">Total Amount Paid</label>
								<input type="text" class="form-control" id="total_amount_paid" <?= $readonly;?> style="text-align:right;" name="total_amount_paid" value="<?= $total_amount_paid;?>" >
							</div>
							
						</div>
						
						<div class="form-group">

							<div class="col-md-5">
								<label class="control-label">RTGS Narration</label>
								<textarea rows="2" class="form-control" id="rtgs_narration" <?= $readonly;?> name="rtgs_narration" autocomplete="off" <?php echo $rdonly; ?> ><?php echo $row['rtgs_narration'];?></textarea>
							</div>
							
						</div>
					  </div>
					</div>
					
			  </div>
			  
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_5" class="btn btn-primary" data-toggle="tab" onclick="$('#five_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
			</div>
				
				
							<?php
								$disabled = '';
								if($tally_status=='R' || $tally_status=='C' || $tally_status == 'U' ){
									
									$disabled = "DISABLED";
									
								}	
								if($user=='Admin'){
									$disabled = '';
								}	
								if ($accountant_role=='M' || $accountant_role=='Y' ){
									$disabled = "";
								}	
								
								if( $tally_status == 'C' ){
									$disabled = "DISABLED";    
								}
									
									
									
					if( ($accountant_role=='Y' || $accountant_role=='M' || $status=='Completed' || $status=='Submitted' ) ){
							
							if(!empty($error_product_name) ){
								$disabled = "DISABLED";  
								
							}
							
							if(!empty($error_budget_head) ){
								$disabled = "DISABLED";  
								
							}
							
							
					?>
											
							<div class="tab-pane <?php echo $active_tab5 ?> " id="tab_5">					
						
							    <div class="box123">
                                    <div class="box-header">
										<p><?= $label_line; ?></p>
										
                                        <h4 class="box-title">Tally Journal Account</h4>
										 
								<?php if (empty($disabled)){ ?>
                                        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
										 </span>	
								<?php  }
								
									if(!empty($error_product_name)){
										echo "<span style='color:red;'>".$error_product_name. "</span>";
									}
									
									if(!empty($error_budget_head)){
										echo "<span style='color:red;'>".$error_budget_head. "</span>";
									}
									
								?>			
                                       
                                    </div>
                                    <div class="box-body">
							
							
									<div id="tallyentry">
									
									<!-- Enter Here -->
								<?php if (empty($disabled)){ ?>	
										<span class="pull-right"><a href="#addLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addLine">Add </a></span>
								<?php  } ?>	
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="40%">Account Name</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;" >Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										$error_account_name = '';
										
										$sql = "SELECT * FROM tally_journal_entry 
													WHERE doc_no = '$re_id' AND doc_type = 'DE' 
														ORDER BY effect desc, record_id ";	
//echo $sql;
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date	 	 	= $r2['doc_date'];
											$invoice_no  		= $r2['supp_invoice_no'];
											$invoice_date  		= $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally  		= $r2['status'];
									
											if(empty($account_name)){
												$error_account_name = 'Error :  Account Name should not be blank...';
											}
											
											$sql = "SELECT * from account_mst where id = '$account_id' ";
											$qry2 	= mysqli_query($con, $sql);
											$r22 	= mysqli_fetch_array($qry2);
											$tds_flag = $r22['tds_flag'];
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
											
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
									<?php //if (empty($disabled123)){ ?>		
									<!--	<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
													<?php //  include "edit_tally_func.php"; ?>	
									-->	
									<?php if ($tds_flag=='Y'){ ?>
										<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>&amount=<?php echo $amount;?>&doc_no=<?php echo $doc_no ?>&doc_type=DE" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
												
													
									<?php  } ?>
												</td>
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = round($amount_dr + $amount,2);
											}
											else if($effect=='Cr'){
												$amount_cr = round($amount_cr + $amount,2);
											}
 
										}
										$emsg   ='';
										$stl	='';
										if($amount_dr != $amount_cr){
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
										}	
									?>
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,2); ?> </td>
												<td></td>
											</tr>
											
											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

									<div class="form-group">
								
								<?php $tally_status = $row['tally_status']; 
								
								// echo $status. ' ' .$tally_status . ' <<<>>> ';
								?>
									<div class="col-sm-3">
										
									
								<?php	
										echo "<span style='color:red;'> " . $error_account_name . "</span>";
										if( $tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label"> Jv Created</label>';
										}
										
										if ( $status == 'Completed' ){
										
									?>
										<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ready to Update Status &nbsp;&nbsp;: </label>
										<?php 
											if( $tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label"> Jv Created</label>';
											}
											if( $tally_status == 'C'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ticked</label>';
											}
											else if($tally_status == 'U'){
												
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Updated to Tally</label>';
											}
											
										
										if( (empty($tally_status) ||  $tally_status == 'R') && ( $accountant_role=='M' ||  $accountant_role=='Y' )  && $status == 'Completed' ){ 
										?>	
										    <label for="tally_status" style="position: relative;top: -4px;" class="control-label">Sync to Tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($status_tally == 'C' || $status_tally == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="C" >
										<?php } ?>	
										
									
									
									<?php 
									if( ($tally_status=='C' || $tally_status=='R' ) && $tally_access=='Y' ){ 
										?>
										    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" >
											
									<?php 
										}
									?>
										
									
									<?php  } ?>
									
									</div>
									<?php 
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970' || $chk_date =='30-11--0001'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y h:m i', strtotime($tally_updated_on));
										}	
									?>
										<div class="col-sm-2">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
										</div>
										
										<div class="col-sm-1">
											<label for="tally_narration" class="control-label">Narration: </label>
										</div>	
										<div class="col-sm-6">	
											<textarea rows="3" class="form-control"  name="tally_narration" id="tally_narration"  
											onBlur="saveToDatabase(this.value,'narration','<?php echo $re_id; ?>')"
											onClick="showEdit(this);" ><?php echo $row['tally_narration'];?></textarea>
										</div>
										
									</div>

										</div>
						
									</div>
								</div>
								
								
								<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous </a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
								</div>	
							
							</div>	
				<?php } ?>				
								
			<div class="tab-pane <?php echo $active;?>" id="tab_2">
			                <!-- Attachments -->
						<div class="box-header">	
							<p><?= $label_line; ?></p>
						</div>
							<!-- Attachments company_idd -->
							<?php	
							//if($status =='Completed' || $user =='Admin'){
							$sql = "SELECT count(*) as cnt FROM `my_documents_files` a, sma_document_type b , sma_party_mst c, dms_inward d 
									where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
									and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_id' "; //  limit 0,5 
							$res = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$cn1 = mysqli_fetch_array($res);
							$cnt = $cn1['cnt'];
						//$cnt=1;	
						if($cnt>0){
								
						?>  
    						
							<div class="col-sm-7" >&nbsp;</div>
							<div class="col-sm-5" >		
								<span style="font-size:18px;color:white;" class="btn btn-info" >Select Document from DMS </span>&nbsp;&nbsp;
								<span > &nbsp;&nbsp;</span>
								<input type ="checkbox" id="partyDoc" name="partydoc" value='Y' onclick="getpartydoc(this.value)" >
							</div>	
								<input type ="hidden" id="party_id_doc" name="party_id_doc" value="<?php echo $party_id_doc; ?>" >
								<input type ="hidden" id="company_idd_doc" name="company_idd_doc" value="<?php echo $company_id; ?>" >
						<?php } ?>
								
								
						<?php
                            $sql = "SELECT * FROM file_uploads WHERE module = 'DE' AND reference_id = " . $re_id;

                            $docResults = mysqli_query($con, $sql);
	                    ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="20%">Description</th>
										  <th width="20%">Share Point Link
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  </th>
										   <th  width="30%">File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type 		= $docRow['doc_type'];
													$doc_type 		= $docRow['doc_type'];
													$share_point_link = $docRow['share_point_link'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td width="20%"><?php echo $document; ?></td>
											  <td width="20%"><?php echo $docRow['doc_desc'] ?></td>
                                              <td width="20%"><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  <td width="30%"><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <?php if(!$readonly){ ?>
													<td width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
											  <?php } ?>  	
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
										</tbody>
                                    
									</table>
								
							<span id="gegpartyDoc">
								
							</span>								  
								  
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where 1  ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										
										<!--<td><label class="col-sm-2 control-label">Invoice.No.&nbsp;* </label>
                                            <select class="form-control col-sm-2 doctype" name="doc_invoice_no[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql = "select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type  = 'D' ";
												$rst = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rs = mysqli_fetch_array($rst)){
												?>
													<option value="<?php echo $rs['invoice_no']?>" ><?php echo $rs['invoice_no'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>-->
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="40%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td width="30%"><input type="file" name="fudoc[]" class="docfile">
										</td>										 
                                      <td width="10%"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
								
								<span id="predit">
								</span>
								
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

											
 $opt_invoice_no = '';	
$sql="select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type = 'D' ";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$invoice_no 		= $rw['invoice_no'];
	$opt_invoice_no .= "<option value='" . $invoice_no ."' > ".$invoice_no."</option>";
 }
 
?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
<input type="hidden"  value="<?php echo $opt_invoice_no ?>" name="opt_invoice_no" id="opt_invoice_no">
												
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_5" class="btn btn-primary" data-toggle="tab" onclick="$('#five_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

					<?php if ($del != 'Y'){?>		
							<div class="box-footer">
								
								<?php		
					
							echo '<div class="col-sm-12">	';
							//$checker_value = 100;
								$sql = " SELECT * FROM `sma_workflow` 
										where company_id = '$company_id'  and '$total_amounTT' <= to_value and '$total_amounTT' >= from_value 
										and trans_type = '$trans_type' and doc_type = 'DE' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$email_approval_expected_role_1 = $rw['email_approval_expected_role_1'];							
								$email_approval_expected_role_2 = $rw['email_approval_expected_role_2'];
								$email_approval_expected_role_3 = $rw['email_approval_expected_role_3'];
								$email_approval_expected_role_4 = $rw['email_approval_expected_role_4'];
								$email_approval_expected_role_5 = $rw['email_approval_expected_role_5'];
								$email_approval_expected_role_6 = $rw['email_approval_expected_role_6'];
								$email_approval_expected_role_7 = $rw['email_approval_expected_role_7'];
				//echo $sql.'<BR>';
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_1' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_1 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_2' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_2 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_3' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_3 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_4' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_4 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_5' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_5 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_6' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_6 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_7' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_7 = $rw['role'];	
								
								$created_date = $row['dated'];
								$expected_role_count = 0;
								if(!empty($email_approval_expected_role_1)){
									if($status!='Draft' && $created_date <='2023-08-15'){
										$email_approval_received_1	= 'Y';
										$email_approval_received_2	= 'Y';
										$email_approval_received_3	= 'Y';
										$email_approval_received_4	= 'Y';
										$email_approval_received_5	= 'Y';
										$email_approval_received_6	= 'Y';
										$email_approval_received_7	= 'Y';
									}
							?>
								<div class="col-md-12">
									<label class="control-label">Before Submitting, Email Approval to Expect from</label><br>
								</div>	
								<?php } ?>
							<?php if(!empty($email_approval_expected_role_1)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_1;?></label><br>
									<input type="hidden" id="email_approval_expected_role_1" name="email_approval_expected_role_1" value="<?= $email_approval_expected_role_1;?>" >
							<?php if($status=='Draft' ){ ?>	
									<input type="checkbox" id="email_approval_received_1" name="email_approval_received_1" <?php echo ($email_approval_received_1=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_1=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>
							<?php if(!empty($email_approval_expected_role_2)){
										$expected_role_count = $expected_role_count + 1;
							?>
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_2;?></label><br>
									<input type="hidden" id="email_approval_expected_role_2" name="email_approval_expected_role_2" value="<?= $email_approval_expected_role_2;?>" >
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_2" name="email_approval_received_2" <?php echo ($email_approval_received_2=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_2=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>	
							
								</div>	
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_3)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_3;?></label><br>
							<?php if($status=='Draft' ){ ?>		
									<input type="checkbox" id="email_approval_received_3" name="email_approval_received_3" <?php echo ($email_approval_received_3=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_3=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_4)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_4;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_4" name="email_approval_received_4" <?php echo ($email_approval_received_4=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_4=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_5)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_5;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_5" name="email_approval_received_5" <?php echo ($email_approval_received_5=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_5=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_6)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_6;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_6" name="email_approval_received_6" <?php echo ($email_approval_received_6=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_6=='Y' ){ ?>	
									<input type="text" readonly  class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_7)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_7;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_7" name="email_approval_received_7" <?php echo ($email_approval_received_7=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_7=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
						
						</div>
				
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>

								
									<span>&nbsp;&nbsp;</span>
								<?php 	
									if($del=='Y'){ ?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
											
								<?php
									}
									else if ( $approval_status=='Rejected' ||  $status=='Submitted' ){	
								?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
								<?php	
									}
								if ($status == 'Draft' ){
									?>		
									<!--<a href="<?php echo $baseurl.$modulePath."direct_expense_payment.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>-->
									<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>	
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
								
								
									<?php		
										$role		= $_SESSION['role'];
										$user   	= $_SESSION['user'];
										$userid   	= $_SESSION['usrid'];
										$send_to   	= $row['send_to'];
										$level_1   	= $row['level_1'];
										$baseurl1 = $baseurl.$modulePath."direct_expense_payment.php?sub=list";
										
									//	echo $role. ' ' . $level_1. ' <<>> ' . $userid. ' => ' . $rw['level_2']. ' => ' . $rw['level_3']. ' ' . $status;
										
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag='';
									if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' ||
										$userid == $approver_4 && $approver_4_status=='Submitted' ||
										$userid == $approver_5 && $approver_5_status=='Submitted' ||
										$userid == $approver_6 && $approver_6_status=='Submitted' ||
										$userid == $approver_7 && $approver_7_status=='Submitted' ||
										$userid == $approver_8 && $approver_8_status=='Submitted' ){
					
										if($approver_1_status=='Submitted' && 
												empty($approver_2_status) && empty($approver_3_status) && empty($approver_4_status) && empty($approver_5_status)
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
										
										$mode_status = 'Pending';
										if($userid==$approver_1 && empty($approver_2) && empty($approver_3)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_2 && empty($approver_3)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_3 && empty($approver_4)){
											$mode_status = 'Approve';
										}
										else if( $userid==$approver_4 && empty($approver_5)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_5){
											$mode_status = 'Approve';
										}
										
									}
								}	
								
								
										$sql 	= "select * from company where comp_id = '$company_id' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$oe_limit_amount = $r2['oe_limit_amount'];
									
										
										if ($status == 'Submitted' && $approver_flag=='Y'){
											
									?>
											<span>&nbsp;&nbsp;</span>
											<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Approve </a>
											<a href="#rejectAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#rejectAuthority">Reject </a>
									<?php } ?>
										
									<?php	
							//echo $status. "<<>>". $role;		
										if($invoice_no_exist>0){
											echo "<span style='color:red;'>Error : Invoice Number $invoice_no already available !</span>";
										}
										
										if(!empty($errmsg)){
											echo $errmsg;	
										}	
										
										//&& $role =='Billdesk'
									if($status=='Draft'  && $invoice_no_exist==0 && empty($errmsg) ){
									?>	
										<?php if( $items_cnt > 0 && $approval_status != 'Rejected' ){ ?>
									<span class='hidesend'>	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
									</span>	
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<?php } ?>
									
								<?php } ?>
								
									<span class='hidesend'>	
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
									</span>	
										
			<?php  $baseurl1 = $baseurl.$modulePath."direct_expense_payment.php?sub=list".'&same_page='.$page; ?>	
																		
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back </a>
								
								</div>
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
									<label class="control-label1">Approver 1</label><BR>
									<label class="control-label1"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
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
									<label class="control-label1">Approver 2</label><BR>
									<label class="control-label1"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
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
									<label class="control-label1">Approver 3</label><BR>
									<label class="control-label1"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
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
									<label class="control-label1">Approver 4</label><BR>
									<label class="control-label1"><?= $approver_4_name . " <BR> " . $approver_4_role; ?>
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
									<label class="control-label1">Approver 5</label><BR>
									<label class="control-label1"><?= $approver_5_name . " <BR> " . $approver_5_role; ?>
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
									<label class="control-label1">Approver 6</label><BR>
									<label class="control-label1"><?= $approver_6_name . " <BR> " . $approver_6_role; ?>
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
									<label class="control-label1">Approver 7</label><BR>
									<label class="control-label1"><?= $approver_7_name . " <BR> " . $approver_7_role; ?>
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
									<label class="control-label1">Approver 8</label><BR>
									<label class="control-label1"><?= $approver_8_name . " <BR> " . $approver_8_role; ?>
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
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
								<div class="box-footer">
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
								/*$approver_1 = $row['approver_1'];
								$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3']; */
					
								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
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
										$sql 	= " select * from sma_user where id = $approver_2 ";
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
										$sql 	= " select * from sma_user where id = $approver_3 ";
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
									<label class="control-label">	</label>
									<select class="form-control  approver_4" name="approver_4" >
                                        <option value="">Select</option>
										<?php
										$sql 	= " select * from sma_user where id = $approver_4 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } 
								
								if(!empty($approver_5)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
									<select class="form-control  approver_5" name="approver_5" >
                                        <option value="">Select</option>
										<?php
										$sql 	= " select * from sma_user where id = $approver_5 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 
								if(!empty($approver_6)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
									<select class="form-control  approver_6" name="approver_6" >
                                        <option value="">Select</option>
										<?php
										$sql 	= " select * from sma_user where id = $approver_6 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } 
								
								?>	
								
								<BR>
								
							</div>
						
						</span>
				<?php } ?>		
							
							
					</div>	
			<?php } ?>		
							
			</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<p><?= $label_line; ?></p>
								<?php 
									
									$srno = $re_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'DE' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Date: ".$create_date; ?>
								 
								</span>
									
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Date</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Date</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>Remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'DE' order by id desc";
										//echo $s1;
											$res  = mysqli_query($con, $s1);
											//echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												if(empty($reviewed_by)){
													$reviewed_by = '0';
												}
												
												$s2="SELECT * FROM sma_user where id in ($reviewed_by) ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$reviewed_by_name = '';
												$role_name = '';
												while ($rw1 = mysqli_fetch_array($r3)){
													$reviewed_by_name .= $rw1['username'].', ';
													$role1 = $rw1['primary_role'];

													$sl="SELECT * FROM sma_role where id = '$role1' ";
													$r4 = mysqli_query($con, $sl);
													$rw = mysqli_fetch_array($r4);
													$role_name .= $rw['role'].', ';
												}
										//echo $reviewed_by . " <<<<<BR>";
												
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
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by_name;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role_name;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
									</div>
								</section>
							  </div>
						
						</div>
						
<!--Comment Section Start-->				
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">		
					<p><?= $label_line; ?></p>	
				<?php 
				$doc_type = 'DE';
				$s1  = " SELECT * from sma_comment where doc_id = '$re_id' and doc_type = '$doc_type' order by id desc ";
				//echo $s1;
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$comment_datetime		= $r1['comment_datetime'];
				$comment_type			= $r1['comment_type'];
				$comment				= $r1['comment'];
				$parent_comment_id		= $r1['parent_comment_id'];
				$comment_by				= $r1['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($r1['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$create_by = $rw['username'];
					
				if(!empty($create_by)){	
					$tmp_var = "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$comment_datetime; 
				}
				?>			
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $re_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $re_id;?>,'<?= $doc_type; ?>','C',<?= $page;?>)" >Submit</button>		
						</div>
					</div>

			<span id="getcomment">	
			<?php
				$res  = mysqli_query($con, $s1);
				while($r1 = mysqli_fetch_array($res)){
					$comment_datetime		= $r1['comment_datetime'];
					$comment_type			= $r1['comment_type'];
					$comment				= $r1['comment'];
					$parent_comment_id		= $r1['parent_comment_id'];
					$comment_by				= $r1['comment_by'];
					$comment_datetime	    = date('d-m-Y h:i:s a', strtotime($r1['comment_datetime']));
					if($comment_datetime=='01-01-1970'){
						$comment_datetime='';
					}
					$sl="SELECT * FROM sma_user where id = '$comment_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$create_by = $rw['username'];
			?>
					<div class="col-md-12">
					
						<label class="control-label">On <?php echo $comment_datetime ?> <?php echo $create_by ;?> : wrote</label><br>
						<?= $comment; ?>
					<!--	<textarea style="background-color:#F5F5F5;" readonly rows='02' cols="150" ><?= $comment; ?></textarea> -->
					</div>
			<?php	
				}
			?>	
			</span>
			
			</div>
					</section>
					
				</div>
				
			 </div>
						
		</div>
<!--Comment Section End-->				
							
							
						
                    </fieldset>
				</div>	
            </form>
   					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
</section>
      
<?php } 	?>


<!--Add Expenses Popup-->

<div class="modal fade" id="addExpenses" role="dialog" aria-labelledby="addExpenses">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addExpenses">Expenses </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12123">
                                    <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                        
										<?php   
										
											$re_id 		= $_SESSION['re_id'];
											$status 	= $_SESSION['status'];
											$role		= $_SESSION['role']; //Maker
											$user_category = $_SESSION['user_category'];
											
										?>
										<input type="hidden" name="re_id" id="re_idE" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
									
								<div class="form-group">
							
                           
								<div class="col-sm-8">
                                <label for="itemName" class="control-label">Product *</label>
									<span id="getproduct" >
										<select class="form-control" name="expence_name" id="expence_Name" required autocomplete="off" onchange="getcatbudget(this.value)"; >
										<option value=""> Select </option>
									<?php 
										$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' $sqla order by name";
										$q2  = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['name'] ?> </option>	
										<?php } ?>
										</select>
									</span>
                                </div>
								
										
									</div>	
								
									<div class="form-group">		
										<div class="col-md-4">
											<label for="approver" class="control-label">Invoice No. * </label>
											<input type="text" class="form-control" name="invoice_no" required id="Invoice_nm" value="" >
										</div>
										
										<div class="col-md-3">
											<label for="approver" class="control-label">Invoice Date *</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value="">
											</div>
										</div>
										
										
									</div>
									
									<span id="getdesc">
									
									</span>
									
									<div class="form-group">
										<div class="col-sm-12">
											<span id="getcatbudget">
												
											</span>
										</div>
									</div>	
									
									
									<div class="form-group">
									
										
										<div class="col-md-3">
											<label for="approver" class="control-label">Gross Amount </label>
									<!--<span data-toggle="tooltip" title="Maximum 50,000 Allowed." class="badge bg-light-blue">!</span>-->
											<input type="text" class="form-control" name="amount" id="Amount" style="text-align:right;" value="0" onkeyup="gettotal()" >
										</div>
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >GST%</label><br>
											<input type="text" class="form-control" name="gst_perc" id="getgst" style="text-align:right;" value="0" onkeyup="gettotal()" > 
										</div>
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >GST Amount</label><br>
											<input type="text" class="form-control" name="gst_amount" id="Gst_amount_A" style="text-align:right;" value="0" onkeyup="gettotal()" > 
										</div>
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >Total</label><br>
											<input type="text" class="form-control" id="total_amount_A" style="text-align:right;" value="" > 
										</div>
									
									</div>
									
									<div class="form-group">
																			
										<div class="col-md-10">
											<label for="approver" class="control-label">Narration</label>
											<textarea rows="4" class="form-control" name="tally_narration" id="Tally_narration" ></textarea>
										</div>
										
									</div>
									
									<div id="showmsg" style = "text-color:red;" > </div>
									
								</form>
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
								<button type="button" class="btn btn-primary" id="saveExp">Save</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Expenses  Popup End -->
	  

<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											//$tot_amount
											$user_category = $_SESSION['user_category'];
										?>
										
										<input type="hidden" name="re_id" id="re_idC" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
								<?php if($status=='Draft' && $role=='Maker' ){
										if( $status=='Draft' ){
											$check_role = 'Accountant';
										}
										
										if ( $tot_amount < 100000 && $user_category == 'S' ){
											$category = 'S';
										}
										else {
											$category = 'H';
										}

//echo  $sql = "SELECT * FROM `sma_user`  where role in (select id from sma_role where role = '".$check_role."' ) and FIND_IN_SET ($company_id, company_id) and active ='1' ORDER BY username ASC "; 
										
								?>
										<div class="form-group col-md-12">
                                        	<label for="approver" class="col-sm-4 control-label">Approver Name </label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql = "SELECT * FROM `sma_user`  where role in (select id from sma_role where role = '".$check_role."' ) and FIND_IN_SET ($company_id, company_id) and active ='1' ORDER BY username ASC ";
														//	$sql = "SELECT * FROM `sma_user`  where regular_exp_approver = 'Y' and FIND_IN_SET ($company_id, company_id) ORDER BY username ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'];?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
								<?php } else if($role == 'Accountant' && ( $status=='Submitted' || $status=='Draft' ) ){	

										/* 
										if ( $tot_amount < 100000 && $user_category == 'S' ){
											 
Site Maker - Below 1 Lakh - Site Accountant - PM
OE - Site Maker - Above 1 Lakh - HO Accountant - PM
OE - HO Maker - Any - HO Accountant - Ticked user Name							
										} */
										
										if( $company_id == '9'){
											$rolev = 'Project Incharge' ;
										}
										else {
											$rolev = 'Project Manager';
										}
//echo $sql = "SELECT * FROM `sma_user` where regular_exp_approver = 'Y' and active = '1' and FIND_IN_SET ($company_id, company_id) and role in (select id from sma_role where role ='$rolev' ) ORDER BY username ASC";										
								?>
										<div class="form-group col-md-12">
                                        	<label for="approver" class="col-sm-4 control-label">Approver Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														if( $user_category == 'H' || $user_category == 'C' ){
															$sql = "SELECT * FROM `sma_user` where regular_exp_approver = 'Y' ORDER BY username ASC";
														}
														else {
															$sql = "SELECT * FROM `sma_user` where regular_exp_approver = 'Y' and active = '1' and FIND_IN_SET ($company_id, company_id) and role in (select id from sma_role where role ='$rolev' ) ORDER BY username ASC";
														}
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'];?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
								<?php }	 ?>		
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
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->



<!--Make to DraftPopup-->

<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$re_id 	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="re_id" id="re_idD" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
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
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
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


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$re_id 	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="re_id" id="re_idZ" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->


<!--Submit Workflow Popup-->

<div class="modal fade" id="submitAuthority" role="dialog" aria-labelledby="submitAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="submitAuthority">Submit To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idS" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Submit'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusS" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
								<button type="button" class="btn btn-primary" id="submitNext">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Submit Workflow Popup End -->


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
							<input type="hidden" id="re_idT" value="<?php echo $_GET['id'];?>">
						
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


<!--Add Line Popup-->
<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idA" value="<?php echo $re_id; ?>" >
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Supplier
												
                                            </div>
                                        </div>
										
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)" >
														<option value="">Select</option>
													<?php
														$sql = "SELECT * from account_mst where  account_type = 'D' order by account_name";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
															$percentage = $r3['percentage'];
															$percentage_v = '';
															if($percentage>0){
																$percentage_v = $percentage .'%';
															}	
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'].' '.$percentage_v;  ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
						
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectA" name="effect"  value='Dr' > Debit
												<input type="radio"  id="effectA" name="effect" checked value='Cr' > Credit
											</div>
                                        
											<div class="col-sm-6">
												<label for="approver" class="control-label">Amount </label>
												<span class="gettdsamt">	
													<input type="text" class="form-control" id="amountA" readonly autocomplete="off" style="text-align:right;;" name="amount" value="" >
												</span>
                                            </div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">If Any changes in amount enter here</label>
											</div>
                                        
											<div class="col-sm-6">
												<input type="text" class="form-control " id="amountABC" autocomplete="off" style="text-align:right;;" name="amounta" >
											</div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA"></textarea>
											</div>
										</div>

								</form>	

								</div>
								
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
								<button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Line Popup End -->


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										
											$re_id = $_SESSION['re_id'];
											$status = $_SESSION['status'];
										
										?>
										
										<input type="hidden" name="re_id" id="re_idP" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeP" name="mode" value='Approve'>

										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusP" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksP"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id 	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idR" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
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
	  

  <!-- Modal Report-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Travel Request data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="regular_exp_export_func.php?sub=pdf&gtype=C" target="_blank" method="POST" >
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
									<label for="itemCategory" class="control-label"> Company</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company where comp_id in ( $comid )order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Report-->	  
	  
	 	  
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
			var opt_invoice_no =  document.getElementById('opt_invoice_no').value;
			
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"><textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 
	function gettotal(){
		var amount		 	 =  parseInt($("#Amount").val());
		var gst_amount		 =  parseInt($("#Gst_amount_A").val());
		var gstperc			 =  parseInt($("#getgst").val());
	 
		if(amount== '' || amount== 0){
			amount = 0;
		}
		if(gst_amount=='' || gst_amount== 0){
			gst_amount = 0;
		}
		if(gstperc>0){
			gst_amount = amount * gstperc /100;
		}
		var total	= parseInt(amount) + parseInt(gst_amount);
		$('#total_amount_A').val(total);
		$('#Gst_amount_A').val(gst_amount);
	 
	}
 
  	function getpangst(id){
		
		var sub    = 'sub24';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub24:sub},function(result){
		      $('#getpangst').html(result);
		});
		
	}
	
	function getsupplier(id){
		
//alert('Hello');
		var sub    = 'sub8';
//alert(sub);		
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getsupplier').html(result);
		});
		
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub88:sub},function(result){
		      $('#getoeamt').html(result);
		});
		
	}

	function getapprover(){
	
		var company_id    	= document.getElementById("company_Id").value;
		var checker_value   = document.getElementById("total_amounTT").value;
		var trans_type    	= document.getElementById("trans_type").value;
		var doc_type		= 'DE';
		var sub = 'sub27';
//alert(sub + company_id + checker_value + ' <<>> ' + trans_type);
		$('.hidesend').hide();
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,doc_type:doc_type,checker_value:checker_value,trans_type:trans_type,sub27:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	
function getapproval(id){
//alert('Hello...');
		var sub    		= 'sub6';
		var company_id  = document.getElementById("company_ID").value;
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' '  + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub6:sub},function(result){
		      $('#getapproval').html(result);
		});
		
}	

</script>
 <!-- For Document Attachment End-->	  
	  

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>


<script>
  
   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var re_id		 	=  $("#re_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + re_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var re_id		 	=  $("#re_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + re_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});

	
    $("#saveExp").on("click", function(e){
        var sub = 'sub10';
				
		var row_affected   =  $("#row_AFFECTED_a").val();
//alert(row_affected);
		if(row_affected == 0){
			alert('Cost center Group and Name not available !!!');
			var errs = 'Cost center Group and Name not available !!!';
			$('#errbudget').html(errs);
			return true;
		}
		
	    var status 			=  $("#statuS").val();
		var approval_ref_no = $("#approval_ref_No").val();
		var expence_name		= $("#expence_Name").val();
		var dated			= $("#Dated").val();
		var amount			= $("#Amount").val();
		var total_amount	= $("#total_amounT").val();
		var invoice_nm		= $("#Invoice_nm").val();
		var gst_amount		= $("#Gst_amount_A").val();
		var gst_perc		= $("#getgst").val();
		var vendor_id	    = $("#sma_vendor_id").val();
		
//alert(sub + ' ' + vendor_id + ' ' +  approval_ref_no);

		if (invoice_nm==''){
			alert('Enter invoice number...');
			return false;
		}
		
		if (dated==''){
			alert('Enter invoice date...');
			return false;
		}
		var remarks			= $("#Tally_narration").val();
		var exp_type		= 'D';
		
        var company_id   =  $("#company_id_a").val();
		var budget_name  =  $("#budget_Name").val();
		var budget_head  =  $("#budget_Head").val();
		var total_budget  =  $("#total_Budget").val();
		var balance_budget  =  $("#balance_Budget").val();
		var budget_id  		=  $("#BUDGET_ID").val();

//alert(budget_id);		
//return;
	
		var total_amount  		=  $("#exp_amount_total_a").val();
//alert(total_amount + ' > ' + balance_budget);		
		var total_amount = parseInt(total_amount) + parseInt(amount) ;
//alert(total_amount + ' > ' + balance_budget);
//return false;
		if ( parseInt(total_amount) > parseInt(balance_budget) ){
			alert('AOP / Budget 100% reached, please increase AOP!!!');
			var shoid = 'AOP / Budget 100% reached, please increase AOP !!!';
			$("#showmsg").text(shoid);
			return false;
		}

		$('#addExpenses').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ approval_ref_no:approval_ref_no,
						expence_name:expence_name,
						dated:dated,
						amount:amount,
						exp_type:exp_type,
						invoice_nm:invoice_nm,
						remarks:remarks,
						gst_amount:gst_amount,
						gst_perc:gst_perc,
						company_id:company_id,
						budget_name:budget_name,
						budget_head:budget_head,
						total_budget:total_budget,
						balance_budget:balance_budget,
						budget_id:budget_id,
						vendor_id:vendor_id,
						sub10:sub},
						function(result){
		      $('#te_exp_edit').html(result);
			//alert(result);
		});
		
	});


 
   $("#submitChecker").on("click", function(e){
        var sub = 'sub14';
		var mode		 	=  $("#modeC").val();
		
		var re_id		 	=  $("#re_idC").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		var approver		=  $("#approverE").val();
		
//	alert(approver + ' ' + re_id );		 

		if(approver==''){
			alert("Checker User Name should select...");
			return;
		}
	
		 $('#checkerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						approver:approver,
						sub14:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
   $("#submitNext").on("click", function(e){
        var sub = 'sub5';
		var mode		 	=  $("#modeS").val();
		
		var re_id		 	=  $("#re_idS").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		$('#submitAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub5:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub15';
		var mode		 	=  $("#modeP").val();
//		alert(sub + ' ' + mode);		 
		var re_id		 	=  $("#re_idP").val();
	    var status 			=  mode;
		
        var remarks			=  $("#remarksP").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub15:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub15';
		var mode		 	=  $("#modeR").val();
		
		var re_id		 	=  $("#re_idR").val();
		var status 			=  mode;
		var remarks			=  $("#remarksR").val();
		
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}	
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);

		$('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub15:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});
	
function getempname(id){
    var sub = 'sub4';
//alert(id);
	var strURL = "ta_func.php";
	$.post(strURL,{ sub4:sub,id:id},function(result){
			  $('#getempname').html(result);
		});
}


function getcatbudget(id){
		
		var sub    		= 'sub55';
		var company_id  = document.getElementById("company_Id").value;
		var approval_ref_no = $("#approval_ref_No").val();
		var product_id  = id;
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{product_id:product_id,approval_ref_no:approval_ref_no,company_id:company_id,sub55:sub},function(result){
		      $('#getcatbudget').html(result);
		});
		
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getgst').val(result);
		}); 
		
		
	}
	

function getcatbudgetA(id){
		
		var sub    		= 'sub5';
		var company_id  = document.getElementById("company_Id").value;
		 
		var strURL = "ta_func.php";
///alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub5:sub},function(result){
		      $('#getcatbudgetA').html(result);
		});
		
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getgstA').val(result);
		});
		
	}


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


	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;

//alert(id + ' ' + sub + ' ' + party_id_doc);

			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}
	
	$("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub1';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		var amount 		= $("#amountA").val();
		var narration 	= $("#narrationA").val();
		var re_id 		= $("#re_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert(re_id + account_name + ' ' + type_ac + ' ' + effect);

		var doc_type = 'DE';
		$('#addLine').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ type_ac:type_ac,doc_type:doc_type,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,re_id:re_id,sub1:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });


    $("#addTallyEntry").on("click", function(e){
		
        var sub 	 = 'sub2';
		var mode 	 = $("#modeT").val();
		var re_id 	 = $("#re_idT").val();		
//alert(re_id);
		var doc_type = 'DE';
		
		
		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_di_func.php";
		$.post(strURL,{ mode:mode,re_id:re_id,doc_type:doc_type,sub2:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });
	
	function getaccount(id){
		
        var sub    = 'sub3';
//alert(sub + ' ' + id);
		var doc_type = 'DE';
		
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub3:sub},function(result){
		      $('#getaccount').html(result);
		});

	} 
	
	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#total_amounT").val();
		var re_id	 	= $("#re_idA").val();
		var exp_type 	= 'D';
		var amount_cgst = $("#amount_CGST").val();
		
//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,amount_dr:amount_dr,amount_cgst:amount_cgst,re_id:re_id,exp_type:exp_type,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}

	   function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
		function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
	//	alert("UPDATE `enqdetail` set " + editableObj);
		
//alert(editableval);
	var editableObj = editableObj.replace("&", "and");
  //alert(editableObj);	

			//$(editableObj).css("background","#FFF  no-repeat right");
			var doc_type = 'DE';
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id+'&doc_type='+doc_type,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function clearfld(){
		
		$('#itemDescription').html('');
		$('#itemQuantity').html('');
		$('#itemUnits').html('');
		$('#itemRate').html('');
		$('#itemGST').html('');
		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}

    function getcomment(comment,re_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + re_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,re_id:re_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
	
	function getproduct(id){
		var sub    = 'sub36';
//alert( sub + ' ' + id );		
		var strURL = "app_func.php";
		var company_id    = document.getElementById("company_Id").value;
		
//alert( sub + ' ' + id + ' ' + company_id );
		$.post(strURL,{id:id,company_id:company_id,sub36:sub},function(result){
		      $('#getproduct').html(result);
		});

	}

	function getproduct_A(id){
		var sub    = 'sub366';
//alert( sub + ' ' + id );		
		var strURL = "app_func.php";
		var company_id    = document.getElementById("company_Id").value;
		
//alert( sub + ' ' + id + ' ' + company_id );
		$.post(strURL,{id:id,company_id:company_id,sub366:sub},function(result){
		      $('#getproduct_A').html(result);
		});

	}
	
	function getgst(){
		
		if (document.getElementById('gst_FLAG').checked) {
		   // gst_flag = document.getElementById('gst_FLAG').value;
			var gst_flag = 'Y';
		}
		else {
			var gst_flag = '';
		}
		
		//alert(gst_flag);
		
	}


	function getlocation(id){
		
        var sub    = 'sub37';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub37:sub},function(result){
		      $('#getlocation').html(result);
		});

	}	
	
	function getworkflow(id){
		
        var sub    = 'sub38';
//alert(sub);
		var doc_type = 'DE';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub38:sub},function(result){
		      $('#getworkflow').html(result);
		});

	}	
	
	
</script>

	  
<?php 	
		include("../footer.php");	
?>

