<?php

include("../header.php");
$modulePath = "petty/";

$compid 		= $_SESSION['comid'];
$userid   		= $_SESSION['usrid'];


	$help_code = $modulePath.'pettycash_expense.php';
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
	
	$yesterday_date = date("Y-m-d");
    $sql = " delete from sma_pettycash where company_id = '' and draft_dated < '$yesterday_date' ";
	mysqli_query($con, $sql);
	
	
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Petty Cash 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Petty Cash </li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				<?php 
			
				$targetpage = "pettycash_expense.php?sub=list"; 
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
					<form class="form-horizontal" action="pettycash_expense.php?sub=list" method="post">
                      
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
							<a href="pettycash_expense.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
				
						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> OWN </option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Ready for Tally Update </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> Tally Updated </option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> Tally Unticked</option>
									
								</select>
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
					
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($compid) order by comp_name ";
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
						
				</form>
				
					&nbsp;&nbsp;
					<span class="pull-right"><a href="pettycash_export.php?sub=pdf" target="_blank" name="btnAdd" class="btn btn-danger"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;</span>
				
			<?php if ( $addonly=='Y'){ ?>	
					<span class="pull-right"><a href="pettycash_expense.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create </a>&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php } ?>
			
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr style="background-color:White;color:#5C5A58;" >
			<th>#</th>
			<th>SrNo.</th>
			<th>Company</th>
			<th>Location</th>
			<th>Date</th>
			<th>Type</th>
			<th>Total Amount</th>
			<th>Narration</th>
			<th>By</th>
			<th>Pending With</th>
			<th>Status</th>
			<th>Decision</th>
			<th>Tally Status</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "petty/";
	
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	
	if($status=='U'){
		$searchfu = 'U';
		$status ='';
	}	
	$department = $_SESSION['department'];
	//and draft_by = '$user'
		$sql = " SELECT * from sma_pettycash where company_id in ( $comid ) and draft_by = '$user' and status != 'Withdraw' ";
		     
		$query = " SELECT count(*) as num from sma_pettycash where  company_id in ($comid) and draft_by = '$user' and status != 'Withdraw' ";
	
	if ($user=='Admin'  ){
		
		$sql = " SELECT * from sma_pettycash where 1 ";
		$query = " SELECT count(*) as num from sma_pettycash where 1 ";
		
	}

	if ($viewonly =='Y' ){
		$sql = " SELECT * from sma_pettycash where company_id in ( $comid ) and status != 'Withdraw' ";
		     
		$query = " SELECT count(*) as num from sma_pettycash where  company_id in ($comid) and status != 'Withdraw' ";
		
	}
	
	$sql .= " and company_id !='' ";
	$query .= " and company_id !='' ";

//echo $department . ' ' .  $role;
//echo $sql; 
					if ($comp_id){
						$sql .= " and company_id =  '$comp_id' ";
						$query .= " and company_id =  '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					if ($role =='Checker' || $role =='Accountant'){
						if (($comp_id ) ||  ($status) || ($approval_status)){
							$sql .= " or draft_by = '$user' and del != 'Y'";
							$query .= " or draft_by = '$user' and del != 'Y'";
						}
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
					if($searchf=='S'){
						$sql .= " and company_id = '$search_data' ";
						$query .= " and company_id = '$search_data' " ;
					}
					
					
					if($searchf=='R'){
						$sql .= " and tally_status in ('U') ";
					}
					if($searchf=='T'){
						$sql .= " and tally_status in ('R') ";
					}
					if($searchf=='U'){
						
						$sql .= " and tally_status not in ('U', 'R') ";
					}


					if( $search_own == 'Y' ){
						$sql .= " and draft_by = '$user' and del != 'Y'";
						$query .= " and  draft_by = '$user'  and del != 'Y' ";
					}
								
					if($searchfu=='U'){
						$sql .= " and id  in (SELECT supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.utr_no ='' and b.st_flag = 'C') ";
						$query .= " and id  in (SELECT supp_id FROM `payment_details` a, payment_header b where a.payment_hdr_id = b.id and b.utr_no ='' and b.st_flag = 'C')";
						$searchfu = 'U';
						//echo $sql;
					
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
					
					$_SESSION['sqlrp'] = $sql;
					
					
//echo $sql. "<BR>";
//echo $query. "<BR>";
			//exit();
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages[num];
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
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


	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$tally_status  = $row['tally_status'];
		if($tally_status=='R'){
			$tally_status_a = 'Ready for Tally Upload';
		}
		else if($tally_status=='U'){
			$tally_status_a = 'Updated to Tally';
		}
		else if($tally_status=='N'){
			$tally_status_a = "Not Update to Tally";
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
		
		$location_id  = $row['location_id'];
		$sql  = "SELECT * from sma_location where id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$loc_name 	= $r1['loc_name'];
		$trans_type = $row['trans_type'];	
		$re_id = $row["id"];
		$amount = 0;
		$sql  = "SELECT sum(amount) as amount, trans_type FROM `sma_pettycash_exp` where  approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			if($trans_type =='R'){
				$amount		-= $r1['amount'];
				$changed_by = $row['draft_by'];
			}
			else if($trans_type =='P'){
				$amount		+= $r1['amount'];
				$changed_by = $row['changed_by'];
			}
		}
		
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
				
				
		if($amount<0){
			$amount = $amount * -1;
		}	
			 
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
			
		$baseurl1 	= $baseurl.$modulePath1.'pettycash_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "pettycash_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="06%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $loc_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="5%"<?php echo $styl; ?>><?php echo $trans_type;?></td>
		<td width="08%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $amount;?></td>
		
		<td width="12%"<?php echo $styl; ?>><?php echo $row['tally_narration'];?></td>
		<td width="09%"<?php echo $styl; ?>><?php echo $changed_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $pending_by;?></td>		
		<td width="08%"<?php echo $styl; ?>><?php echo $status;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
		<td width="09%"<?php echo $styl; ?>><?php echo $tally_status_a;?></td>
		
		
<!--		<td width="5%" style="text-align:right;">
		<a href="pettycash_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		
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
		
			$sql = "SELECT a.expense_id, a.amount, b.status, c.budget_name, c.budget_head , b.company_id , b.location_id, b.trans_type
					FROM `sma_pettycash_exp` a, sma_pettycash b, account_mst c 
					where a.approval_ref_no = b.id and b.id = '$id' and a.expense_id = c.id ";
//echo $sql."<BR>";				
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->expense_id;
				$status			= $r->status;
				$amount			= $r->amount;
				$budget_name	= $r->budget_name;
				$budget_head	= $r->budget_head;
				$company_id		= $r->company_id;
				$location_id	= $r->location_id;
				$trans_type		= $r->trans_type;
				
				// $status = 'Completed';
				if($status=='Completed'){
					$sql = "UPDATE `sma_budget` set used_budget = used_budget - $amount 
							    where budget_name = '$budget_name' and budget_category = '$budget_head' 
								   and project = '$company_id' and locked != 'Y' "; 
//echo $sql."<BR>";			
					mysqli_query($con, $sql);
					if($trans_type=='P'){
						$sql 	= "update sma_location set paid_total = paid_total - $amount where id = '$location_id' ";
					}
					else if($trans_type=='R'){
						$sql 	= "update sma_location set received_total = received_total - $amount where id = '$location_id' ";
					}
//echo $sql."<BR>";					
					$q2 	= mysqli_query($con, $sql);	
					
				 }
				
			}
// exit();			
		
		$sql = "update sma_pettycash set del = 'Y' where  id='$id' ";
		 
        $query1 = mysqli_query($con, $sql);
		echo 	mysqli_error($con);
        echo 	'<script>window.location.href="pettycash_expense.php?sub=list";</script>';
	}
?>


<?php

if(isset($_POST['editTally'])){
		 
		$record_id     		= $_POST['record_id'];
		$si_hdr_id 			= $_POST['si_hdr_id'];
		$doc_no 			= $_POST['si_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_id 		= $_POST['account_id'];
		$amount 			= $_POST['amount'];
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
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		$doc_type 		= 'PC';
		
		if($tds_percentage > 0 && ($account_type == 'U' )){
			$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type = 'U' and doc_type='$doc_type' and doc_no='$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else if($tds_percentage > 0 && ($account_type == 'E' || $account_type =='D' )){
			$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type = 'V' and doc_type='$doc_type' and doc_no='$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else if($tds_percentage > 0 && $account_type == 'A'){
			$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = '$doc_type' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'B' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		

//exit();
		
		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'pettycash_expense.php?sub=edit&id='.$si_hdr_id.'&active5=active&zyx';
		echo "<script>window.location.href='$baseurl';</script>";
		
	}
	
?>

<?php

	if(isset($_POST['editItem'])){

		$rid     		= $_POST['rid'];
		
		$dated			= date('Y-m-d', strtotime($_POST['dated']));
		$approval_ref_no= $_POST['approval_ref_no'];
		$expense_id 	= $_POST['expense_id'];
		$invoice_no 	= $_POST['invoice_no'];
		$amount 		= $_POST['amount'];
		$note 			= $_POST['remarks'];
		$trans_type 	= $_POST['trans_type'];
		$paid_to		= $_POST['paid_to'];
		$sma_vendor_id	= $_POST['sma_vendor_id'];
		$sma_vendor_id	= $_POST['sma_vendor_id'];
		$balance_cash	= $_POST['balance_cash'];
	
		$sql = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$approval_ref_no' ";
		$q2  = mysqli_query($con, $sql);
//echo $sql. "<BR>";
		$total_amount = 0 ;
		while($r2 = mysqli_fetch_assoc($q2)){
				
			$total_amount += $r2['amount'];
				
		}
			
		if($total_amount > $balance_cash){
			
			echo "<script>alert('Error: Expense amount should not be greater then Balance Cash !!!');window.location.href='pettycash_expense.php?sub=edit&id=$approval_ref_no';</script>";
			exit();
			
		}
	
		$sql = "update `sma_pettycash_exp` set dated	= '$dated',
						expense_id 		= '$expense_id',
						invoice_no 		= '$invoice_no',
						amount 			= '$amount',
						note 			= '$note',
						trans_type 		= '$trans_type',
						paid_to 		= '$paid_to',
						spend_by 		= '$sma_vendor_id'
				where id = '$rid' ";	
		$r2 = mysqli_query($con, $sql);
	
//echo $sql;
//exit();
			
	echo "<meta http-equiv='refresh' content='0'>";    
	//$baseurl.=$modulePath.'edit.php?approval_ref_no='.$approval_ref_no.'&active=active&987';
	//echo "<script>window.location.href='$baseurl';</script>";
	echo "<script>window.location.href='pettycash_expense.php?sub=edit&id=$approval_ref_no';</script>";
	exit();
	
}
 
?>
 
<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){
			
  			$id 				= $_POST['id'];
			$re_id				= $_POST['id'];
			
			$company_id 		= $_POST['company_id'];
			$location_id 		= $_POST['location_id'];
			$dated				= date('d-m-Y', strtotime($_POST['dated']));
			$approval_ref_no	= $_POST['approval_ref_no'];
			$datedd				= date('Y-m-d', strtotime($_POST['dated']));
			//$remarks	 	 	= $_POST['remarks'];
			$trans_type			= $_POST['tran_type'];
			$tally_narration	= $_POST['tally_narration'];
			
			if(empty($trans_type)){
				$trans_type = 'P';
			}	
	
			$status 			= 'Draft';

			$user   			= $_SESSION['user'];
			
			$sql = "select count(*) as cnt from sma_pettycash where id = '$id' ";
			$query=mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($query);
			$nct	=	$r2['cnt'];
//	trans_type		= '$trans_type',
			/* if($nct>0){
				$sql="update sma_pettycash set company_id ='$company_id',
						location_id		= '$location_id',
						approval_ref_no	= '$approval_ref_no',
						tally_narration = '$tally_narration',
						dated			= '$datedd'
					where id='$id'";
			}
			else {	 */
			$sql = "Insert into sma_pettycash ( company_id, location_id, dated,  status, draft_by, draft_dated, trans_type, tally_narration ) VALUES ( '$company_id', '$location_id', '$datedd', 'Draft', '$user', now(), '$trans_type', '$tally_narration' ) ";
			
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			$re_id= mysqli_insert_id($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "<script>window.location.href='pettycash_expense.php?sub=edit&id=$re_id';</script>";
			//&next=active
			//echo '<script>window.location.href="pettycash_expense.php?sub=list";</script>';
			exit();
			
		}
							
		$sql = "select max(id) as id from sma_pettycash ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$re_id	=	$r2['id'] + 1;
							
		$status 			= 'Draft';
		$user   			= $_SESSION['user'];
/* 		$sqlI="Insert into sma_pettycash ( id, status, draft_by, draft_dated ) values ('$re_id', 'Draft', '$user', now() ) ";
	//echo $sqlI;
		$query=mysqli_query($con, $sqlI);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();} */

?>

    <section class="content-header">
        <h1>
            Petty Cash 
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Petty Cash </a></li>
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
            <form class="form-horizontal" action="pettycash_expense.php?sub=add" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						
						<div class="form-group">
						
							<label class="col-lg-1 control-label">SrNo.</label>
							<div class="col-md-2">
							
								<input type="text" class="form-control" id="approval_ref_No" name="approval_ref_no" readonly value="<?php echo $re_id; ?>">
								
							</div>
							
							<label class="col-lg-2 control-label">Prepared Date</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" autocomplete="off" <?php echo $readonly; ?> readonly value="<?php echo date('d-m-Y');?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label class="col-lg-1 control-label">Company </label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" id="company_ID" autocomplete="off" required onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($compid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>	
												
						<div class="form-group">
							
							<label class="col-lg-1 control-label">Location </label>
							<div class="col-md-3">
							<span id="getlocation">
								<select class="form-control" name="location_id" id="location_ID" autocomplete="off" required onchange="getpettycashbal(this.value)" >
                             	<option value=""> Select </option>
									
								</select>
							</span>
							</div>
							
							<label class="col-md-2 control-label" >Transaction Type *</label>
							<div class="col-md-2" >
								<!--<input type="radio" name="trans_type" id="trans_type_A" value="R" onchange="hidefld(this.value)" > Receipt &nbsp;&nbsp;&nbsp;
								<input type="radio" name="trans_type" id="trans_type_A" checked onchange="hidefld(this.value)" value="P" > Payment
								-->
								<select class="form-control" name="tran_type" id="trans_type_A" required onchange="hidefld(this.value)" >
                             		<option value=""> Select </option>
									<option value="P" selected > Payment </option>
									<option value="R"> Receipt </option>
								</select>
								
							</div>
										
							<label class="col-lg-2 control-label">Balance Cash </label>
							<div class="col-md-2">
							<span id="getpettycashbal">	
								<input type="text" class="form-control" id="balance_cash" readonly name="balance_cash" value="">
							</span>	
							</div>
						
							
						</div>
						
						<div class="form-group">
							<div class="col-sm-2">
								<label for="tally_narration" class="control-label">Tally Narration: </label>
							</div>	
							<div class="col-sm-10">	
								<textarea rows="1" class="form-control" name="tally_narration" id="tally_narration">	</textarea>
							</div>
						</div>
						
						<!--<div class="form-group">
							
							<label class="col-lg-1 control-label">Remarks</label>
							<div class="col-md-11">
								<textarea class="form-control" rows="2" name="remarks"  id="remarksD" autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
						
						</div>-->
						
					<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
        <!--        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"><i class="fa fa-expand "></i>&nbsp;&nbsp; Data Entry <span class="caret"></span></a>
								
								</h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse in">
								<div class="panel-body">
								<?php //if(!$readonly) { ?>
								<span class="pull-right">
									<a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
								</span>
								<?php //} ?>
						<div class="form-group">
							<div class="col-md-12">
								
								<span id="te_exp_edit">
								
								</span>
							</div>	
						</div>
						</div>
					</div>		
				</div>-->
				
			</div>	
	
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'pettycash_expense.php?sub=list';?>
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


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
//echo $_POST['datedd'];		
			$id			    = $_POST['id']; 
			$re_id			= $_POST['id']; 
			$company_id 	= $_POST['company_id'];
			$location_id 	= $_POST['location_id'];
			$total_amount 	= $_POST['total_amount'];
			$dated		 	= date('Y-m-d', strtotime($_POST['datedd']));
			$approval_ref_no= $_POST['approval_ref_no'];
			$remarks	 	= $_POST['remarks'];
			$purchase_requisition =	$_POST['purchase_requisition'];
			$balance_cash =	$_POST['balance_cash'];
			$paid_cash 			= $_POST['paid_cash'];
			$trans_type 		= $_POST['tran_type'];
			$tally_status		= $_POST['tally_status'];
			$tally_narration	= $_POST['tally_narration'];
			$account_id 		= $_POST['account_id'];
			
			$tally_stat				= $_POST['tally_stat'];
			if($tally_stat=='N'){
				$tally_narration		= $_POST['tally_remark'];
				$tally_status			= $tally_stat;
			}
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];

			$status				= $_POST['status'];
			
			$sql = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$re_id' ";
				$q2  = mysqli_query($con, $sql);
//echo $sql. "<BR>";
			$total_amount = 0 ;
			while($r2 = mysqli_fetch_assoc($q2)){
				
				$total_amount += $r2['amount'];
				
			}

			//company_id ='$company_id',
						
  			$sql = "update sma_pettycash set approval_ref_no	= '$approval_ref_no',
						total_amount    = '$total_amount',
						dated			= '$dated',
						paid_cash		= '$paid_cash',
						remarks 		= '$remarks',
						tally_narration	= '$tally_narration',
						account_id 		= '$account_id'
					where id='$id'";

			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
 			if($tally_status=='R' || $tally_status=='N' ){
				
				$sql = "update sma_pettycash set tally_status = '$tally_status', tally_ticked_by = '$user', tally_updated_on = now() where id='$id'";
//echo $sql; 				
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();} 
			}	
//exit();			
//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set status = '$tally_status', narration = '$tally_narration' where doc_no = '$id' and doc_type = 'PC' ";
				$r2  = mysqli_query($con, $sql);
				//echo mysqli_error($con);
//TALLY STATUS UPDATE


///RAVI			
				$sql="Select * from sma_pettycash where  id ='$id' ";
				$query = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($query);	
				$status = $row['status'];
				
			//if( $status != 'Received' ){
				if($trans_type=="R"){
					//$sql 	= "update sma_location set received_total = received_total + $total_amount where id = '$location_id' ";
		//echo $sql; exit();			
					//$q2 	= mysqli_query($con, $sql);
					//if(!empty($error)){echo $error; exit();}
					
					$sql = "update sma_pettycash set status = 'Completed' where id='$id'";
					$query = mysqli_query($con, $sql);
					$error = mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
					$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'PC', '$re_id', '$userid', now(), 'Completed', '', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				}
			//}
			
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_pettycash set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$re_id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'PC', '$re_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "petty/";
				
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
				
				$baseurl1 =$baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$re_id;
				
				$msg = 'Petty Cash Number : '.$re_id . ' ' . 'Dated : ' . date("d-m-Y");
				
				include "pc_mail.php";	
					
			}
			
//echo $sql. "<BR>";
//exit();			
//RAVI
			
			// add attachments
			// file upload
			$doc_invoice_no = $_POST["doc_invoice_no"];
			$arrDocType 	= $_POST["doctype"];
			$arrDocDesc 	= $_POST["docdesc"];
			$arrFUDoc 		= $_FILES["fudoc"];
			
			$share_point_link 	= $_POST['share_point_link'];
			
	
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				/* $folder_path = "uploads/pc/" . $re_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} */
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($share_point_link[$i]) ){
					
					$sql = "INSERT INTO file_uploads (module, doc_type, doc_desc, doc_invoice_no, share_point_link, reference_id, date_uploaded) 
					VALUES( 'PC', '$arrDocType[$i]', '$arrDocDesc[$i]', '$doc_invoice_no[$i]', '$share_point_link[$i]', '$re_id', now() )";
					mysqli_query($con, $sql);
					/* 
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, "uploads/pc/" . $re_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					} */
				}
				
			}
//echo $sql;			
//exit();


//DMS Doc upload			
			$party_doc = $_POST['party_doc'];
			for($i = 0; $i < sizeof($party_doc); $i++){
			
				$doc_in = $party_doc[$i];
				$sql = "SELECT module, file_path, file_name, reference_id, doc_type FROM `my_documents_files` where id = '$doc_in' ";
				//echo $sql. "<BR>";
				$res = mysqli_query($con, $sql);
				$r11 			= mysqli_fetch_array($res);
				$filename 		= $r11['file_name'];
				$folder_path 	= $r11['file_path'];
				$arrDocType 	= $r11['doc_type'];
				$reference_id 	= $r11['reference_id'];
				
				$sql = "INSERT INTO file_uploads ( module,  file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded ) 
				VALUES( 'PC',  '$filename', '$folder_path', '$arrDocType', '$re_id', '$reference_id', now() )";
				mysqli_query($con, $sql);
				//echo $sql. "<BR>";
				
				//exit();
			}
//DMS Doc upload
			
//exit();
			echo '<script>window.location.href="pettycash_expense.php?sub=list";</script>';
			
		}
		
		$id = $_GET['id'];
		$approval_ref_no = $_GET['approval_ref_no'];
		
		$sql="Select * from sma_pettycash where  id ='$id' ";

//echo $sql;
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$re_id = $row['id'];
		$_SESSION['re_id'] = $row['id'];
		$status = $row['status'];
		$tally_status 	= $row['tally_status'];
		$tally_ticked_by = $row['tally_ticked_by'];
		
		$_SESSION['status'] = $status;
		
		$draft_by = $row['draft_by'];
		$paid_status = $row['paid_status'];
		$del 	  = $row['del'];
		
		$readonly = '';
		if ($status != 'Draft'){
			$readonly = 'READONLY';
		}
		
		if ($user == 'Admin'){
			$readonly = '';
		}
		
		if ($del == 'Y'){
			$readonly = 'READONLY';
		}
//echo $status. ' <<>> ' . $readonly;
	$approver_1 		= $row['approver_1'];
	$approver_2 		= $row['approver_2'];
	$approver_3 		= $row['approver_3'];
	$approver_4 		= $row['approver_4'];
	
	$approver_1_status 	= $row['approver_1_status'];
	$approver_2_status 	= $row['approver_2_status'];
	$approver_3_status 	= $row['approver_3_status'];
	$approver_4_status 	= $row['approver_4_status'];
?>

    <section class="content-header">
        <h1>
            Petty Cash 
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Petty Cash </a></li>
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
            <form class="form-horizontal" action="pettycash_expense.php?sub=edit" method="post" enctype="multipart/form-data" >
                
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
						
						<input type="hidden" id="status" name="status" value="<?php echo $status; ?>">
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . 'pettycash_expense.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		                
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
						
						
					?>
                   
				   <ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Petty Cash </a></li>
						<?php if($role!='Maker'){ ?>
							  <li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >Tally Journal</a></li>
						<?php } ?>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
						<li><a href="pettycash_exp_repo.php?sub=pdf&id=<?php echo $re_id;?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						<li><a href="voucher_prn.php?sub=pdf&id=<?php echo $re_id;?>&company_id=<?php echo $row['company_id']?>&r=1" class="btn btn-info"  target="_blank" >Voucher Print</a></li>
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group">
						</div>
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						<div class="form-group">
							<label class="col-lg-1 control-label">SrNo.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="approval_ref_No" readonly style="text-align:right;"  name="approval_ref_no" value="<?php echo $re_id; ?>">
							</div>
						<?php
							$dated				= date('d-m-Y', strtotime($row['dated']));
							$draft_by			= $row['draft_by'];
							if($dated=='01-01-1970'){ $dated='';}
							$approval_ref_no	= $re_id;
						?>						
						
							
							<label class="col-lg-2 control-label">Prepared Date </label>
							<div class="col-md-2">
								
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="datedd" autocomplete="off" <?php echo $readonly; ?> readonly value="<?php echo $dated; ?>">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<?php $company_id = $row['company_id'] ?>
							
							<label class="col-lg-1 control-label">Company </label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" id="company_Id"  disabled >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($compid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-1 control-label">Location </label>
							<div class="col-md-3">
								<select class="form-control" name="location_id" id="location_ID" readonly="readonly" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_location order by loc_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['location_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['loc_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php  
								$checked = '';
								$paid_cash = $row['paid_cash'];
								if( $paid_cash =='Y'){
									$checked = "CHECKED";
								}

								$trans_type = $row['trans_type'];
								$tran_type = $row['trans_type'];
							//echo $trans_type. ' >>><<<';	
								if($trans_type=='P'){
									$trans_ty = 'Payment Type';
								}
								else if($trans_type=='R'){
										$trans_ty = 'Receipt Type';
								}	
								
							if($trans_type=='P'){	
						?>
							<label class="col-lg-1 control-label">Paid </label>
							<div class="col-md-1">
						<?php if( $paid_cash ='Y' && $status =='Completed' ){ ?>
								<input type="text" class="form-control"  id="paid_cash" readonly name="paid_cash" value="Yes">
						<?php 	} 
							else {
						?>
								<input type="checkbox" id="paid_cash" style="position: relative;top: 5px;" <?php echo $checked; ?> name="paid_cash" value="Y">
						<?php 	} 
						?>
							</div>
						<?php }	?>
						
							<div class="col-md-2">
								<input type="hidden" id="trans_type_A" name="tran_type" value="<?php echo $trans_type; ?>" >
								<input type="text" class="form-control"  id="trans_ty" readonly name="trans_ty" style="font-weight:bold" value="<?php echo $trans_ty; ?>" >
							</div>
							
							<?php 
									$location_id = $row['location_id'];
									$sql 	= "select * from sma_location where id = '$location_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$op_balance 	= $r2['op_balance'];
									$received_total = $r2['received_total'];
									$paid_total 	= $r2['paid_total'];
									$balance_cash 	= $op_balance + $received_total - $paid_total;
							?>
							
							<label class="col-lg-2 control-label">Balance Cash </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="balance_cash" readonly name="balance_cash" style="text-align:right;" value="<?php echo $balance_cash;?>">
							</div>
					
					
						</div>
						
						<div class="form-group">
							<div class="col-sm-2">
								<label for="tally_narration" class="control-label">Tally Narration: </label>
							</div>	
							<div class="col-sm-10">	
								<textarea rows="1" class="form-control" name="tally_narration" id="tally_narration"  
								onBlur="saveToDatabase(this.value,'narration','<?php echo $re_id; ?>')"
								onClick="showEdit(this);" ><?php echo $row['tally_narration'];?></textarea>
							</div>
						</div>
						
				<?php

					$sql = "select * from company where comp_id = '$company_id' ";
					$q2 	  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$petty_cash_limit_amount =	$r2['petty_cash_limit_amount'];					
				
				?>				
						<div class="form-group">
					<?php if($trans_type=='P') { ?>		
							<div class="col-sm-3">
								<label class="control-label" style="color:red;">Petty Cash Limit is upto Rs.<?= $petty_cash_limit_amount;?> </label>
							</div>
					<?php } ?>	
							<label class="col-lg-2 control-label">Tally Account</label>
							<div class="col-md-4">
							<?php
								$sql = "select * from account_mst where 1 and account_type ='E' and company_id = '$company_id' order by account_name ";
								$q2 	= mysqli_query($con, $sql);
								$arow = mysqli_affected_rows($con);
									
							?>
								<select class="form-control" name="account_id" id="account_id"  required >
							<?php 
								if($arow>1 || $arow==0){
									$selected = 'SELECTED';	
							?>		
									<option value=""> Select </option>	
							<?php	
								}	
									while($r2 = mysqli_fetch_array($q2)){ 
							?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['account_id'] == $r2['id'])?'selected="selected"':''; ?> <?= $selected ?> >  <?php echo $r2['account_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<input type="hidden" name="total_amount" id="total_amount" <?php echo $readonly ?>  value="<?php echo $row['total_amount'] ?>">
						
						<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>&nbsp;&nbsp; Data Entry <span class="caret"></span></a></h4>
                            </div>
                    <div id="stepTwo" class="panel-collapse collapse in">
							
								<div class="panel-body">
								<?php if(!$readonly){ 
							$sql="SELECT sum(amount) as amount from sma_pettycash_exp where approval_ref_no = '$approval_ref_no' ";
							$result = mysqli_query($con, $sql);
							$row1 = mysqli_fetch_array($result);
							$tot_amt  	= $row1['amount'];	
							$errmst = '';
							
							if($tot_amt>10000 && $trans_type=='P'){
								$errmst = "Error : Petty cash should be allow only upto Rs.10000/-";
							}
								?>	
									<span class="pull-right">
							<?php if(!empty($errmst)){ ?>
								<label class="control-label" style="color:red;"><?= $errmst;?> </label>

							<?php } 
								else if($status=='Draft' || $status=='Received'){
							?>		
										<a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
									</span>
									
							<?php 	} 
								} 
								?>
						<div class="form-group123">
							<div class="col-md-12">
							
								<span id="te_exp_edit">
									<table id="prtable" class="table table-bordered table-striped">
										 <tr>
												<th> SrNo.</th>
												<th> Date</th>
												<th> Account Type</th>
												<th> Cost Center Group</th>
										<?php	if($trans_type=='R'){ ?>	
												<th> Received By</th>
										<?php } ?>
										<?php	if($trans_type=='P'){ ?>
												<th> Paid To</th>
										<?php } ?>										
												<th style="text-align:right;"> Amount</th>
												<th style="text-align:right;"> Action</th>
										 </tr>
										
									<tbody>
									<?php
										$j = 0;
										$modulePath1 = "petty/";
										
										$sql="SELECT * from sma_pettycash_exp where approval_ref_no = '$approval_ref_no' ";
									//echo $sql;
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$row_item = mysqli_affected_rows($con);
										while($row1 = mysqli_fetch_array($result)){
											$j = $j + 1;
											
											
											$paid_to  	= $row1['paid_to'];
											$spend_by 	= $row1['spend_by'];
											
											if($paid_to=='V'){
												$sql = "select * from sma_party_mst where id = '$spend_by' ";
												$res = mysqli_query($con, $sql);
												$r = mysqli_fetch_object($res);
												$spend_by		= $r->party_name;
											}
											else if($paid_to=='U'){
												$sql = "select * from sma_user where id = '$spend_by' ";
												$res = mysqli_query($con, $sql);
												$r = mysqli_fetch_object($res);
												$spend_by		= $r->username;
											}
											else if($paid_to=='B'){
												$sql = "select * from account_mst where id = '$spend_by' ";
												$res = mysqli_query($con, $sql);
												$r = mysqli_fetch_object($res);
												$spend_by		= $r->account_name;
											}
											$expense_id = $row1['expense_id'];
											$sql="SELECT * from sma_product a , sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' and a.id = '$expense_id' ";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$expense_id = $r2['name'];
											$budget_id = $r2['budget_id'];
											
											$sql="SELECT * from sma_budget where id = '$budget_id'";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$budget_head = $r2['budget_head'];
											
											$tot_amount += $row1['amount'];

									?>
										<tr>
											<td width="2%"><?php echo $j;?></td>
											<td width="10%"><?php echo date('d-m-Y', strtotime($row1['dated']));?></td>
											<td width="25%"><?php echo $expense_id;?></td>
											<td width="25%"><?php echo $budget_head;?></td>
											<td width="20%"><?php echo $spend_by;?></td>
											<td width="10%" style="text-align:right;"><?php echo number_format($row1['amount'],2);?></td>
											
											<td width="08%" style="text-align:right;">
											<?php $rid = $row1['id']; 
											if (empty($readonly)){
											?>
											<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_com_func.php"; ?>							
<!-- Modal Edit Item-->						
												<!--<a href="pettycash_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
												<a href="delete_expenses.php?sub=delete&id=<?php echo $row1['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											</td>
											<?php } ?>
										</tr>

										<?php }?>
									</tbody> 
										<?php $tot_amount = $tot_amount  ?>
										<tr> <th colspan="5" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo number_format($tot_amount,2); ?> </th><td colspan="3"></td></tr>
										
										<input type="hidden" id="total_amounT"  value="<?php echo $tot_amount;?>" >
										
									</table> 
									
								</span>
								
							</div>	
							
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
									 <a href="#tab_5" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
			</div>
				
				
			<?php
								$disabled = '';
								if($tally_status=='R' || $tally_status == 'U' || $tally_status == 'N'){
									
									$disabled = "DISABLED";
									
								}	
							if($user=='Admin'){
								$disabled = '';
							}
							?>
											
							<div class="tab-pane <?php echo $active_tab5 ?> " id="tab_5">					
						
							    <div class="box123">
                                    <div class="box-header">
                                        <h4 class="box-title">Tally Journal Account</h4>
								<?php //if (empty($disabled)){ ?>
                                <!--        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
								-->			
								<?php //} ?>			
                                        </span>
                                    </div>
									
                                <div class="box-body">
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
								<?php //if (empty($disabled)){ ?>
								<!--		<span class="pull-right"><a href="#addLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addLine">Add </a></span>
								-->
								<?php  //} ?>	
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
										
										$sql = "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = 'PC' order by effect desc, record_id ";	
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
											$amount		   		= round($r2['amount'],0);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally  		= $r2['status'];
									
											$url_var = urlencode($_SERVER['REQUEST_URI']);
											
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo round($amount,0); ?> </td>
												<td>
										<?php //if (empty($disabled)){ ?>		
									<!--				<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
									==>
													<?php  //include "edit_tally_func.php"; ?>	
									<!--	<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
										<?php  //} ?>
												</td>
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = round($amount_dr + $amount,0);
											}
											else if($effect=='Cr'){
												$amount_cr = round($amount_cr + $amount,0);
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
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,0); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,0); ?> </td>
												<td></td>
											</tr>
											
											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

									<div class="form-group">
									<?php $tally_status = $row['tally_status']; ?>
									<?php if ( $status == 'Completed' ){
									?>
										<div class="col-sm-4">
										<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ready to Update Status &nbsp;&nbsp;: </label>
										<?php 
										
											if($tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ticked</label>';
											}
											else if($tally_status == 'U'){
												
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Updated to Tally</label>';
											}
											
											if(empty($tally_status)){ 
										?>	
											
											<input type="checkbox" class="form-control123" <?php echo ($status_tally == 'R' || $status_tally == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="R" >
											<?php  } ?>
										</div>
									<?php  } ?>
									
									<?php 
										$chk_date = date('d-m-Y', strtotime($row['tally_updated_on']));
										if($chk_date=='01-01-1970' || $chk_date=='30-11--0001' ){
											$tally_updated_on ='';
										}
										else { 
											$tally_updated_on = date('d-m-Y h:i:s', strtotime($row['tally_updated_on']));
										}	
									?>
										<div class="col-sm-3">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' : ' . $tally_ticked_by; ?>
											</label>
										</div>
										
										
										
									</div>

										</div>
						
									</div>
								</div>
								
<!-- Don't update to TALLY -->
								<div class="form-group">
									<?php 
									$tally_status = $row['tally_status'];
									//echo $tally_status; || $status == 'Submitted'
										if ( $status == 'Completed' ){ ?>
										<div class="col-sm-3">
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Don't update to Tally &nbsp;&nbsp;: </label>
										<?php 
										
											if($tally_status == 'N'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ticked</label>';
											}
											
										if(empty($tally_status)){
										?>
											<input type="checkbox" class="form-control123" <?php echo ( $tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_stat" id="tally_status" value="N" 
											onclick="showrem(this.value)">
											
										<?php } ?>	
											
										</div>
									<?php 
										}
									?>
									
									
									
									<?php 
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970'){
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
								<?php 
									if($tally_status != 'N'){
										$disnone = "none";
									}
									else {
										$disnone = "block";
									}
								?>			
								<span id='showrem' style="display:<?php echo $disnone;?>">
										<div class="col-sm-1">
											<label for="tally_narration" class="control-label">Remark: </label>
										</div>	
										<div class="col-sm-6">	
											
											<textarea rows="1" cols="65" onBlur="saveToDatabase(this.value,'narration','<?php echo $si_id; ?>')" onClick="showEdit(this);" name="tally_remark" id="tally_remark"  ><?php echo $row['tally_narration'];?></textarea>
											
										</div>
								</span>		
									</div>
<!-- Don't Update to Tally -->			
								
								<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous </a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								</div>
								</div>	
							
							</div>	
								
								
								
			<div class="tab-pane <?php echo $active;?>" id="tab_2">
			                <!-- Attachments -->
							
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
								$sql = "SELECT * FROM file_uploads WHERE module = 'PC' AND reference_id = " . $re_id;
								$docResults = mysqli_query($con, $sql);
	                        ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>

										  <th width="10%" >Invoice.No</th>
										  
                                          <th  width="20%" >Description</th>
										  <th  width="40%">Share Point Link
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  </th>
                                        
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_desc = $docRow['doc_desc'];
													$doc_type = $docRow['doc_type'];
													$invoice_no = $docRow['doc_invoice_no'];
													$share_point_link = $docRow['share_point_link'];
													
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												?>
                                          <tr>
                                              <td><?php echo $document ?></td>
											  <td><?php echo $invoice_no ?></td>
                                              <td><?php echo $doc_desc ?></td>
											  <td><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  
											  <?php if(!$readonly){ ?>
													<td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
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
										<td>
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
										
										<td>
                                            <select class="form-control col-sm-2 doctype" name="doc_invoice_no[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql = "select id as id, invoice_no from sma_pettycash_exp  where approval_ref_no = '$approval_ref_no' ";
												$rst = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rs = mysqli_fetch_array($rst)){
												?>
													<option value="<?php echo $rs['invoice_no']?>" ><?php echo $rs['invoice_no'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" <?php echo $readonly; ?> placeholder="Enter document description..."></textarea>
										</td>
										<td width="40%" >
											 <textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										 <?php //if(!$readonly){ ?>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 <?php //} ?>
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
$sql="select id as id, invoice_no from sma_pettycash_exp  where approval_ref_no = '$approval_ref_no' ";
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
									 <a href="#tab_5" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>


					<div class="box-footer">	
							<div class="col-sm-6">
					<?php 	
						if($del=='Y'){ ?>
								
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>
					<?php
						}
						else if ( $status != 'Draft' && $user == 'Admin' ){	
					?>
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>
								
					<?php	
						}
						 if ($del != 'Y'){?>		
							
									<?php $did = $_GET['id']; ?>
								
							<?php		
								if ($status == 'Draft' || $user=='Admin' ){
							?>		
									<!--<a href="<?php echo $baseurl.$modulePath."pettycash_expense.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>-->
									<span>&nbsp;&nbsp;</span>
									
									<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>	
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
								
								
									<?php		
										$role		= $_SESSION['role'];
										$user   	= $_SESSION['user'];
										$userid   	= $_SESSION['usrid'];
										$send_to   	= $row['send_to'];
										$baseurl1 = $baseurl.$modulePath."pettycash_expense.php?sub=list";
									$approver_flag='';
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
										&& $approver_8_status=='Submitted' ){
										$approver_flag='Y';
									}
									
								}
								
										
							//echo $role . " <<< >>> ". $trans_type. ' <<>> ' . $status. ' ' . $tally_status;
									
										if( $status!='Draft' && $status!='Completed' && $approver_flag=='Y' && $tran_type=='P' ){
										?>
											<span>&nbsp;&nbsp;</span>
										<span class="hidden-div">	
											<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Approve </a>
										</span>	
											<a href="#rejectAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#rejectAuthority">Reject </a>
									<?php }
										
									if( $tran_type=='R' and $status != 'Received' && $trans_type!="R" ){
									?>
										<input class="btn btn-primary" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
									<?php		
									}
								
							if(!empty($errmst)){
									?>
									<label class="control-label" style="color:red;"><?= $errmst;?> </label>
								
							<?php	}
								
									if( $status == 'Draft' && $approval_status != 'Rejected' && ($row_item >0 ) && $trans_type!="R" && $tot_amt <=10000 ){
									?>
									<span class='hidesend'>	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
									</span>	
								<?php }

										if ($approval_status!='Rejected' && $tot_amt <=10000 ){
									?>		
											<input class="btn btn-primary" type="submit" value="Save " name="Save">&nbsp;&nbsp;&nbsp;
										
									<?php	}	
										else if ($status == 'Completed'){
									?>		
											<input class="btn btn-primary" type="submit" value="Save " name="Save">&nbsp;&nbsp;&nbsp;
										
									<?php	}	?>
										
								
								<?php //if ( $status == 'Draft' && $user == 'Admin' ){ ?>								
							<!--<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a> -->
								<?php //} ?>
								
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back</a>
								
								</div>
								
							</div>
					<?php } ?>		
						
						
				<span id="getapprover">	
					
					<?php 
						if( $status == 'Draft' ){
						?>
						
							<div class="box-footer">
								<div class="col-sm-1">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
							
								if(!empty($approver_1)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_1 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_2 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_2" name="approver_2" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_2 ";
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
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_3 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_3" name="approver_3" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_3 ";
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
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_4 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_4" name="approver_4" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_4 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
								
							</div>
							
							
					<?php } ?>		
						
					</span>

						
				</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $re_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PC' order by id ";
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
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$draft_by; ?> <?php echo "&nbsp;&nbsp; Date: ".$dated; ?>
								 
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PC' order by id desc";
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
													$role1 = $rw1['role'];

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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addExpenses">Data entry </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
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
										<div class="col-md-6">
									<?php if($trans_type=='R') { ?>
											<label for="approver" class="control-label"> Date *</label>
									<?php } ?>	
									<?php if($trans_type=='P') { ?>
											<label for="approver" class="control-label">Spend/Invoice Date *</label>
									<?php } ?>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value='<?php echo date("d-m-Y"); ?>'>
											</div>
										</div>
									
										
									</div>	
								
							<?php if($trans_type=='P') { ?>		
									<div class="form-group" id="hideflda" >
										
										<div class="col-md-12">
											<label class="control-label">Account Type * </label>
										
											<select class="form-control" name="expence_name" id="expence_Name" required autocomplete="off" onchange="getcatbudget(this.value)"; >
												<option value=""> Select </option>
												<?php 
													$sql = "SELECT * from sma_product where 1 order by name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['name'] ?> </option>	
												<?php } ?>
											</select>
										</div>
										
									</div>
							<?php } ?>		
									
									<div class="form-group " id="hidefldb">
										<div class="col-md-6" style="text-align:left;" >
											<label for="approver" class="control-label" >Paid To </label><br>
											<input type="radio" name="paidto" id="paidto_A" value="V" onchange="getvendor(this.value)" > Vendor &nbsp;
											<input type="radio" name="paidto" id="paidto_A" value="U" onchange="getvendor(this.value)" > User &nbsp;
											<input type="radio" name="paidto" id="paidto_A" value="O" onchange="getvendor(this.value)" > Others
											<?php if($trans_type=='R') { ?>	
											<input type="radio" name="paidto" id="paidto_A" value="B" checked onchange="getvendor(this.value)" > Bank
											<?php } ?>
										</div>
										
									</div>	
									
									<div class="form-group " id="hidefldc">
										<div class="col-md-10">
								<?php if($trans_type=='P') { ?>			
										<span id="getvendor">
											<label class=" control-label">Party Name</label>
											<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_iD" autocomplete="off" required >
												<option value=""> Select </option>
													
											</select>
										</span>	
										</div>
								<?php } ?>	
								<?php if($trans_type=='R') { ?>			
										<label class=" control-label">Received From</label>
										<span id="getvendor">
										<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_iD" autocomplete="off" required >
											<option value=""> Select </option>
											<?php 
											$sql = "SELECT * from account_mst where 1 and account_type = 'B' order by account_name";
											$q2  = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
											?>
											<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['account_name'] ?> </option>	
											<?php } ?>
										</select>
										</span>
										</div>
								<?php } ?>	
								
									</div>
								
									
									<div class="form-group">
								<?php if($trans_type=='P') { ?>		
										<div class="col-md-4 " id="hidefldd">
											<label for="approver" class="control-label">Invoice No. * </label>
											<input type="text" class="form-control" name="invoice_no" autocomplete="off" required id="Invoice_nm" value="" >
										</div>
								<?php } ?>
										<div class="col-md-4">
											<label for="approver" class="control-label">Amount *</label>
									<!--<span data-toggle="tooltip" title="Maximum 50,000 Allowed." class="badge bg-light-blue">!</span>-->
											<input type="text" class="form-control" name="amount" autocomplete="off" style="text-align:right;" id="Amount"  value="" >
										</div>
										
									<!--/* <div class="col-md-4" style="text-align:left;" >
											<label for="approver" class="control-label" >GST Applicable</label><br>
											<input type="radio" name="trans_type" id="Gst_flag_A" value="Y" > Yes &nbsp;
											<input type="radio" name="trans_type" id="Gst_flag_A" value="N" > No
										</div>
									 */-->
									</div>
									
									<div class="form-group">
																			
										<div class="col-md-12">
											<label for="approver" class="control-label">Narration</label>
											<textarea rows="2" class="form-control" name="remarks" autocomplete="off" id="Remarks" ></textarea>
										</div>
										
									</div>
										
								</form>
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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



<!--Add Line Popup-->
<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
											<div class="col-sm-4">
												<label for="approver" class="control-label">GST</label>
												
													<input type="text" class="form-control" id="amount_CGST" autocomplete="off" style="text-align:right;;" name="amount_Cgst" value="" >
												
                                            </div>
											
											
										</div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio" id="type_acB" name="account_type" value='U' onchange="getaccount(this.value)" > User
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	<input type="radio"  id="type_acA" name="type_ac" value='B' onchange="getaccount(this.value)" > Budget
												<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Vendor
												
                                            </div>
                                        </div>
										
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)" >
														<option value="">Select</option>
													<?php
														$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'E' or account_type = 'D' or account_type = 'A' order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'] ?></option>
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
												<label for="approver" class="control-label">Amount *</label>
												<span class="gettdsamt">	
													<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" name="amount" value="" >
												</span>
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
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry" onclick="tallyentry123();" >Yes</button>
            </div>
			
                </section>
            </div>
        </div>
    </div>
</div>


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


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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



<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
									
										$role = $_SESSION['role'];
								//echo $company_id . ' ' . $role. ' ' . $user_category.' ' . $status. "<BR>";
								
										if ( $status == 'Draft' && ( $role == 'Accountant' || $role == 'Maker' ) || $user =='Admin' ){
											
											if($user_category=='S'){
												$user_category = " 'S' ";
											}
											else if($user_category=='H' || $user_category=='C'){
												$user_category = " 'H' , 'C' ";
											}
										}	
										
										if($company_id=='9'){
												
											//$account_role = "'Project Incharge'";
											$user_category = "'H', 'S' "; 
														
										}
//echo  $sql="SELECT * FROM sma_user where user_category in ($user_category) and role in (select id from sma_role where role in ('HOD','HOD - Account', 'Project Manager','Project Incharge' ) ) ORDER BY username ASC";			
									?>
										
										<input type="hidden" name="re_id" id="re_idC" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
											<input type="hidden" id="modeE" name="mode" value='Accept'>
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required="required">
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where user_category in ($user_category) and role in (select id from sma_role where role in ('HOD','HOD - Account', 'Project Manager','Project Incharge' ) ) and active = '1' ORDER BY username ASC";
														    $result = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($r3 = mysqli_fetch_array($result)){
														?>
														<option value="<?php echo $r3['id']?>" <?php echo ($r3['department'] == $department_id)?'selected="selected"':'';?>><?php echo $r3['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
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


<!--Submit Workflow Popup-->

<div class="modal fade" id="submitAuthority" role="dialog" aria-labelledby="submitAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
										
									<?php
											$role = $_SESSION['role'];
								//echo $role. ' ' . $user_category."<BR>";
								
										//if ($status == 'Submitted'){
											if($user_category=='S'){
												//$user_category = " 'S' ";
												$user_category = " 'H','C' ";
											}
											else if($user_category=='H' || $user_category=='C'){
												$user_category = " 'H','C' ";
											}
										//}	
//echo $sql="SELECT * FROM sma_user where user_category in ($user_category) and role in (select id from sma_role where role = 'HOD - Account' ) ";		
									?>
									<!--	<div class="form-group col-md-12">
											<input type="hidden" id="modeE" name="mode" value='Accept'>
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required="required">
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where user_category in ($user_category) and role in (select id from sma_role where role in ( 'HOD', 'HOD - Account', 'Project Manager' ) ) ORDER BY username ASC";
														    $result = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($r3 = mysqli_fetch_array($result)){
														?>
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>-->
										
										<input type="hidden" name="approver" id="approverE" value="<?php echo $userid; ?>" >
										
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
	  

  <!-- Modal Report-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><select class="form-control select2 doc_invoice_no" name="doc_invoice_no[]"><option value="0">Select</option>'+opt_invoice_no+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="40%" ><textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>									</td></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
 
	function getapprover(){
		
		var sub = 'sub34';
//alert(sub );			
		var company_id    	= document.getElementById("company_Id").value;	
//alert(sub + ' ' + company_id + ' ' );
		$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,sub34:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	
	

</script>
 <!-- For Document Attachment End-->	  
	  
	  
<script>


function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		
		if(row_affected==1){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}
		else if(row_affected==2){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
	}	
</script>
	  
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


<script>

    $("#saveExp").on("click", function(e){
        var sub = 'sub10';
				
//		alert(sub + ' ' + mode);		 
	    var status 			=  $("#statuS").val();
		var approval_ref_no = $("#approval_ref_No").val();
		var expence_name	= $("#expence_Name").val();
		var dated			= $("#Dated").val();
		var amount			= parseInt($("#Amount").val());
		var invoice_nm		= $("#Invoice_nm").val();
		var trans_type		= $("#trans_type_A").val();
		var sma_vendor_id	= $("#sma_vendor_iD").val(); //Paid To
		var paid_to			= $("#paidto_A").val(); //Paid To
		var company_id   	=  $("#company_ID").val();
		var location_id   	=  $("#location_ID").val();		
		var hdr_date		= $("#dp1").val();
		var hdr_remarks		= $("#remarksD").val();
		//var trans_type		= $('input[name=trans_type]:checked', '#myForm').val();
		var paid_to  		= $('input[name=paidto]:checked', '#myForm').val();
		
		var balance_cash			= parseInt($("#balance_cash").val());
		
		
		if(amount>balance_cash && trans_type=='P' ){

			alert('Error: Expense amount should not be greater then Balance Cash !!!');
			return false;
			
		}
		
		if(amount>10000 && trans_type=='P'){

			alert('Error: Petty cash allow only upto Rs.10000/-');
			return false;
			
		}	
//alert(trans_type + ' ' + paid_to + ' ' + sma_vendor_id );

		var remarks			= $("#Remarks").val();
	
//alert( location_id +' <>' + company_id + ' <> ' + hdr_date);

		$('#addExpenses').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ approval_ref_no:approval_ref_no,
						expence_name:expence_name,
						dated:dated,
						amount:amount,
						invoice_nm:invoice_nm,
						remarks:remarks,
						trans_type:trans_type,
						company_id:company_id,
						sma_vendor_id:sma_vendor_id,
						paid_to:paid_to,
						hdr_date:hdr_date,
						location_id:location_id,
						hdr_remarks:hdr_remarks,
						
						sub10:sub},
						function(result){
		      $('#te_exp_edit').html(result);
		});
		
		/* setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload(); */
		
	});


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
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub15';

		var mode		 	=  $("#modeP").val();
//		alert(sub + ' ' + mode);		 
		var re_id		 	=  $("#re_idP").val();
	    var status 			=  $("#statusP").val();
		
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
        var sub = 'sub16';
		var mode		 	=  $("#modeR").val();
		
		var re_id		 	=  $("#re_idR").val();
		var status 			=  $("#statuS").val();
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);

		$('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub16:sub},
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
		
		var sub    		= 'sub5';
		var company_id  = document.getElementById("company_Id").value;
		 
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub5:sub},function(result){
		      $('#getcatbudget').html(result);
		});
		
	}
	
	
function getapproval(id){

		var sub    		= 'sub6';
		var company_id  = document.getElementById("company_Id").value;
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' '  + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub6:sub},function(result){
		      $('#getapproval').html(result);
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
	  
	
	function getpettycashbal(id){

		var sub    = 'sub24';
//alert(sub + id);	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub24:sub},function(result){
			$('#getpettycashbal').html(result);
		});
		
	}
	
	function getlocation(id){

		var sub    = 'sub26';
//alert(sub + ' ' + id);	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub26:sub},function(result){
			$('#getlocation').html(result);
		});
		
	}
	
	function getvendor(id){
		
		//alert("Hello...");
		var sub    = 'sub25';
//alert(sub + id);	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub25:sub},function(result){
			$('#getvendor').html(result);
		});
		
	}	
	
	function getvendora(id){
		
		//alert("Hello...");
		var sub    = 'sub25';
//alert(sub + id);	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub25:sub},function(result){
			$('#getvendora').html(result);
		});
		
	}	
	
	function getvendora_A(id){
		
		//alert("Hello...");
		var sub    = 'sub25';
//alert(sub + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub25:sub},function(result){
			$('.getvendora_A').html(result);
		});
		
	}
		
	
	function hidefld(id){
		
		//alert('Hello'+ ' ' + id);
		if(id=='R'){
			document.getElementById("hideflda").style.display = "none";
			document.getElementById("hidefldb").style.display = "none";
			document.getElementById("hidefldc").style.display = "none";
			document.getElementById("hidefldd").style.display = "none";
		}
		else {
			document.getElementById("hideflda").style.display = "block";
			document.getElementById("hidefldb").style.display = "block";
			document.getElementById("hidefldc").style.display = "block";
			document.getElementById("hidefldd").style.display = "block";
		}	
	}	

	

function removeElement() {
  document.getElementById("imgbox1").style.display = "none";
}

function changeVisibility() {
  document.getElementById("imgbox2").style.visibility = "hidden";
}

function resetElement() {
  document.getElementById("imgbox1").style.display = "block";
  document.getElementById("imgbox2").style.visibility = "visible";
}



	$("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub1';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount 		= $("#amountA").val();
		var narration 	= $("#narrationA").val();
		var re_id 	= $("#re_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
//alert(re_id + account_name + ' ' + type_ac + ' ' + effect);

		var doc_type = 'PC';
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
		var doc_type = 'PC';
		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ mode:mode,re_id:re_id,doc_type:doc_type,
							sub2:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });
	  
	  
	function getaccount(id){
		
        var sub    = 'sub3';
//alert(sub + ' ' + id);
		var doc_type = 'PC';
		
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub3:sub},function(result){
		      $('#getaccount').html(result);
		});

	} 
	
	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#total_amounT").val();
		var re_id	 	= $("#re_idA").val();
		var amount_cgst = $("#amount_CGST").val();
		
//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,amount_dr:amount_dr,re_id:re_id,amount_cgst:amount_cgst,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}
	
	
</script>


</body>
</html>
