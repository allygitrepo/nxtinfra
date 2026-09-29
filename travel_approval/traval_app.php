<?php
include("../header.php");
$modulePath = "travel_approval/";

$pgname = "travel_approval/traval_app.php";
include("../viewonly.php");
$user   = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

	$help_code = $modulePath.'traval_app.php';
	include "../help_code.php";

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Travel Request
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Travel Request</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              
			  <?php 
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss']  = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] ){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
					$searchf = $_SESSION['searchf'];
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
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
					
				}
				
				?>
              
					<form class="form-horizontal" action="traval_app.php?sub=list" method="post">
                      
					
						
						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="Draft" <?php echo ($searchf == 'Draft')?'selected="selected"':'';?> > Draft </option>
									<option value="Submitted" <?php echo ($searchf == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
									<option value="Completed" <?php echo ($searchf == 'Completed')?'selected="selected"':'';?>> Completed </option>
									<option value="Rejected" <?php echo ($searchf == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deleted </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									
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
							
							
						<div class="col-xs-2">
                                		
								<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="traval_app.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>	
							
				</form>
				
				<span class="pull-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<?php //if ( $addonly=='Y'){ ?>
					<span class="pull-right"><a href="traval_app.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create Travel Request </a></span>
				<?php //} ?>
				<span class="pull-right"><a href="#modalExport"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalExport" class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;
				</span>
			   </div>
			   
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th></th>
			<th>#</th>
			
			<th>Name</th>
			<th>From Location </th>
			<th>From Date</th>
			<th>To Location </th>
			<th>To Date </th>
			<th>Company</th>
			<th>Advance Amount</th>
			<th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
			
		</tr>
	</thead>
<tbody>
<?php
	
	$department = $_SESSION['department'];
	
	$sql = "SELECT count(*) as cnt from sma_traval_approval where (emp_id = '$usrid' || onbehalf_emp_id ='$usrid' ) and status != 'Withdraw' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1  = mysqli_fetch_array($result);
	$cnt = $r1['cnt'];
	$sqla ='';
	$querya = '';
	
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sqla .= " and dated >= '$start_date' and dated <= '$end_date' ";
						$querya .= " and dated >= '$start_date' and dated <= '$end_date' ";
					}
					if($searchf=='N'){
						$sqla .= " and id = '$search_data' ";
						$querya .= " and  id = '$search_data'  ";
					}
					if($searchf=='S'){
						$sqla .= " and company_id = '$search_data' ";
						$querya .= " and company_id = '$search_data' " ;
					}
					
					if( $searchf == 'U' ){
						$sqla .= " and paid_status != 'Paid' ";
						$querya .= " and paid_status != 'Paid' ";
					}
					if($searchf=='V'){
						$sqla .= " and del = 'Y' ";
						$querya .= " and del = 'Y' ";
					}
					else if($searchf=='B'){
						$sqla .= "";
						$querya .= " ";
					}
					else { // Default
						$sqla .= " and del != 'Y' ";
						$querya .= " and del != 'Y' ";
					}
					
					if( $searchf == 'Draft' ||  $searchf == 'Submitted' ||  $searchf == 'Completed'){
						$sqla .= " and status = '$searchf' ";
						$querya .= " and status = '$searchf' ";
					}
					if( $searchf == 'Rejected' ){
						$sqla .= " and approval_status = '$searchf' ";
						$querya .= " and approval_status = '$searchf' ";
					}
					
	//echo $sqla;

	if ($cnt>0){
		
		$sql = "SELECT * from sma_traval_approval where (emp_id = '$usrid' || onbehalf_emp_id ='$usrid') and status != 'Withdraw' ". $sqla;
	}
	else {
		$sql = " SELECT * from sma_traval_approval where (emp_id = '$usrid' || onbehalf_emp_id ='$usrid') and status != 'Withdraw' $sqla
			union 
			SELECT * from sma_traval_approval where ( approver_1 ='$usrid' || approver_2 ='$usrid' || approver_3 ='$usrid' ) and status != 'Withdraw' $sqla ";
	}
	
//echo $user. "<<>";	
				if(empty($searchf)){
						if (empty($status) && empty($comp_id) && empty($approval_status) ){
							$sql .= " and ( draft_by = '$user' and emp_id !='' and company_id !='' ) ";
							$query .= " and ( draft_by = '$user' and emp_id !='' and company_id !='' ) ";
						}
					}
					
	if ($user=='Admin' ){
		
		$sql = " SELECT * from sma_traval_approval where id > 0 $sqla ";
		
	}	
	
					
	//$sql .= " and del !='Y' ";
	
	 $sql .=" order by id desc";
//echo $searchf;
//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		//$emp_id = $row['emp_id'];
		$emp_id = $row['onbehalf_emp_id'];
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
		
		if($start_date=='01-01-1970' || $start_date=='31-12-1969' ){ $start_date='';}
		if($end_date=='01-01-1970' || $end_date=='31-12-1969' ){ $end_date='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){

				$styl  = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";

			}
			
		$changed_by = $row['changed_by'];
		if(empty($changed_by)){
			$changed_by = $row['draft_by'];
		}
		$sql = "select * from sma_user where userid = '$changed_by' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$changed_by = $r2['username'];
		
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath . "traval_app.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="2%" style="text-align:right;<?php echo $styl2; ?>"><?php echo $row['id'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $emp_name;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $row['traval_from'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $start_date;?></td>
		<td width="13%"<?php echo $styl; ?>><?php echo $row['traval_to'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $end_date;?></td>
		<td width="04%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo moneyFormatIndiaa($row['advance_amount']);?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $changed_by;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['status'];?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>

<!--		<td width="10%" style="text-align:right;">
		<a href="traval_app.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="traval_app.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		</td>-->
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>

    <?php }?>

<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		//$sql="delete from sma_traval_approval where id='$id' ";
        $sql="update sma_traval_approval set del = 'Y' where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="traval_app.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
	

	
	if(isset($_POST['Save'])){
 
			$emp_id			= $_POST['emp_id'];
			$onbehalf_emp_id= $_POST['onbehalf_emp_id'];
			$company_id		= $_POST['company_id'];
			$dated			= date('Y-m-d', strtotime($_POST['dated']));
			$start_date		= date('Y-m-d', strtotime($_POST['start_date']));
			$end_date		= date('Y-m-d', strtotime($_POST['end_date']));
			$start_time			= $_POST['start_time'];
			$end_time			= $_POST['end_time'];
			$advance_amount	= $_POST['advance_amount'];
			$purpose_visit	= $_POST['purpose_visit'];
			$traval_from	= $_POST['traval_from'];
			$traval_to		= $_POST['traval_to'];
			$estimated_days	= $_POST['estimated_days'];
			$advance_amount	= $_POST['advance_amount'];
			$remarks		= $_POST['remarks_hdr'];
			
			$booking_details= $_POST['booking_details'];
			$mode_of_travel = $_POST['mode_of_travel'];
			$type_of_travel = $_POST['type_of_travel'];
			$location			= $_POST['location'];
			$reason_travel		= $_POST['reason_travel'];
			$balance_budget		= $_POST['balance_budget'];
			$budget_id			= $_POST['budget_id'];
			$estimated_days_in_nights = $_POST['estimated_days_in_nights'];
			
			$status 			= 'Draft';

			$user   = $_SESSION['user'];

  			$sql="insert into sma_traval_approval ( company_id, dated, emp_id, onbehalf_emp_id, traval_from, traval_to, start_date, end_date, booking_details ,purpose_visit,  estimated_days, advance_amount, remarks, status, draft_by, draft_dated,  mode_of_travel, type_of_travel, start_time, end_time, location, reason_travel, balance_budget, budget_id, estimated_days_in_nights ) 
			Values('$company_id', '$dated', '$emp_id', '$onbehalf_emp_id', '$traval_from', '$traval_to', '$start_date', '$end_date', '$booking_details', '$purpose_visit', '$estimated_days', '$advance_amount', '$remarks', '$status', '$user', now(), '$mode_of_travel', '$type_of_travel', '$start_time', '$end_time', '$location', '$reason_travel', '$balance_budget', '$budget_id', '$estimated_days_in_nights' )";
					
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$ta_id = mysqli_insert_id($con);
			$userid = $_SESSION['usrid'];
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, flow_flag ) 
						values('TA', '$ta_id', '$userid', now(), '$status', 'P')";
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			echo "Travel Request successful added";
			echo "<script>window.location.href='traval_app.php?sub=edit&id=$ta_id&next=active';</script>";
		}

?>

    <section class="content-header">
        <h1>
            Travel Request
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl . $modulePath."traval_app.php?sub=list" ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath."traval_app.php?sub=list" ?>">Travel Request</a></li>
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
            <form class="form-horizontal" action="traval_app.php?sub=add" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
							<?php 
							
								$user   = $_SESSION['user'];
								$userid   	= $_SESSION['usrid'];

									$sql = "select * from sma_user where userid = '$user' || id = '$userid' ";
										$res1 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res1);
										$emp_id 		= $r1['id'];
										$role			= $r1['role'];
										$department		= $r1['department'];
										$designation	= $r1['designation'];
										$emp_name		= $r1['username'];
								
									$onbehalf_emp_id = $emp_id;
									
									$sql="SELECT * from sma_role where id = '$role' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$role 		= $r1['role'];
									
									$sql="SELECT * from sma_department where id = '$department' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$department 		= $r1['name'];
									
									$sql="SELECT * from sma_designation where id = '$designation' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$designation 		= $r1['designation'];
									
									$sql="Select max(id) as id  from sma_traval_approval";
									$query = mysqli_query($con, $sql);
									$row = mysqli_fetch_array($query);	

									$asrno = $row['id']+1;
		
							?>
						
						<div class="form-group">
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Request No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="asrno" name="asrno" style="text-align:right;" readonly value="<?php echo $asrno;?>">
							</div>
							
							<label class="col-lg-1 control-label">Date</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" autocomplete="off" value="<?php echo date('d-m-Y');?>"> 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label class="col-lg-1 control-label">Company *</label>
							<div class="col-md-5">
								<select class="form-control" name="company_id" id="company_id" autocomplete="off" required onchange="getlocation(this.value);getBudgetCode(this.value);" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group"> 
							
							<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $emp_id;?>"> 
							
							<label class="col-lg-2 control-label">Name</label>			
							<div class="col-md-2">						
								<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $emp_name;?>">  
							</div> 
							
							<label class="col-lg-1 control-label">Role</label> 			
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $role;?>">  
							</div> 
							
							<label class="col-lg-2 control-label">On Behalf of *</label>
							<div class="col-md-3">
								<select class="form-control" name="onbehalf_emp_id" id="onbehalf_emp_id" autocomplete="off" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($onbehalf_emp_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div> 
							
						<div class="form-group"> 
							<label class="col-lg-2 control-label">Department</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $department;?>">  
							</div> 
							<label class="col-lg-1 control-label">Designation</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $designation;?>">  
							</div> 
							
							<label for="location" class="control-label col-sm-1">Location *</label>
							<div class="col-sm-2">
								<span id="getlocation">	
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
										
									</select>	
								</span>
								
                            </div>
							
						</div> 
						
				<!--		<span id="getBudgetCode_b">
							<div class="form-group"> 
								<label for="location" class="control-label col-sm-2">Budget Head *</label>
								<div class="col-sm-8">
								<span id="getBudgetCode">
								</span>
								</div>
							</div>
						
							<div class="form-group">	
								<label class="control-label col-sm-2">&nbsp;</label>
								<div class="col-sm-8">	
								<span class="getBudgetCheck">
									
								</span>
								</div>
							</div>
						</span>
				-->
				
						<div class="form-group">

							<label class="col-lg-2 control-label">Reason for Travel *</label>
							<div class="col-md-10">
								<select class="form-control" name="reason_travel" id="reason_travel"  required >
									<option value=""> Select </option>
										<?php $sql = "select * from reason_travel order by reason_travel ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['reason_travel'];?></option>
										<?php } ?>
								</select>
							</div>

						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Details of Visit *</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="3" id="purpose_visit" name="purpose_visit" autocomplete="off" ></textarea>
							</div>
						
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">From City *</label>
							<div class="col-md-2">
								<select class="form-control" name="traval_from" id="traval_from"  required >
									<option value=""> Select </option>
										<?php $sql = "select * from cities order by city_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['city_name'];?>"  <?php echo ($row['traval_from'] == $r2['city_name'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-1 control-label">Start&nbsp;Date&nbsp;*</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="start_date" name="start_date" required autocomplete="off" value="" onchange="sdate()" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Travel Time &nbsp;*</label>
							<div class="col-sm-2">
								<input type="time" class="form-control" id="start_time" name="start_time" required style="text-align:right;" autocomplete="off" value="">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">To City *</label>
							<div class="col-md-2">
								<select class="form-control" name="traval_to" id="traval_to" required >
									<option value=""> Select </option>
										<?php $sql = "select * from cities order by city_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['city_name'];?>"  ><?php echo $r2['city_name'];?></option>
										<?php } ?>
								</select>
								
							</div>
							
							<label class="col-lg-1 control-label">Return&nbsp;Date&nbsp;*</label>
							<div class="col-md-2">
							<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control" id="end_date" name="end_date" required autocomplete="off" value="" onchange="sdate()" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Travel Time &nbsp;*</label>
							<div class="col-sm-2">
								<input type="time" class="form-control" id="end_time" name="end_time" style="text-align:right;" autocomplete="off" value="">
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="type_of_travel" class="control-label col-sm-2">Type Of Travel *</label>
							<div class="col-sm-2">
								<select class="form-control" id="type_of_travel" name="type_of_travel" required="true" >
								<option value="">Select</option>
									
								<option value='D'>Domestic</option>
								<option value='I'>International</option>
								</select>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Mode Of Travel *</label>
							<div class="col-sm-2">
								<select class="form-control" id="mode_of_travel" name="mode_of_travel" required="true" >
									<option value="">Select</option>
									<option value='F'>Flight</option>
									<option value='R'>Rail</option>
									<option value='D'>Road</option>
								</select>
							</div>
						</div>	
							
						<div class="form-group">
							
						<!--	<label class="col-lg-2 control-label">Advance Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="advance_amount" name="advance_amount" style="text-align:right;" autocomplete="off" value="">
							</div>
							-->
							<label class="col-lg-2 control-label">Estimated Travel Days </label>
							<div class="col-md-2">
								<span id="getdays">
									<input type="text" class="form-control" id="estimated_days" name="estimated_days" style="text-align:right;" autocomplete="off" value="" >
								</span>	
							</div>
						
							<label class="col-lg-2 control-label">Estimated&nbsp;Hotel&nbsp; Accommodation (in Nights) </label>
							<div class="col-md-2">
								<span id="getdays">
									<input type="text" class="form-control" id="estimated_days_in_nights" name="estimated_days_in_nights" style="text-align:right;" autocomplete="off" value="">
								</span>	
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Booking Details</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="3" name="booking_details"  autocomplete="off" ></textarea>
							</div>
						
						</div>
						
						
					<!--	<div class="form-group">
							
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="2" name="remarks_hdr"  autocomplete="off" ></textarea>
							</div>
						
						</div>
					-->	
						<div class="form-group">
						<center>
                            <div>
							<span id="hideSave">	
                                <input class="btn btn-info" type="submit" value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</span>
							
								<a href="traval_app.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
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
	
	if(isset($_POST['Withdraw'])){
		$id					= $_POST['id']; 	
		$Withdraw			= $_POST['Withdraw'];
		
			$sql="update sma_traval_approval set status = '$Withdraw'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
		//echo $sql;
		
		//exit();
		
		echo "Travel Request successful Edited";
		echo '<script>window.location.href="traval_app.php?sub=list";</script>';
			
	}
	
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$ta_id				= $_POST['id']; 
			$company_id			= $_POST['company_id'];
			//$emp_id				= $_POST['emp_id'];
			$onbehalf_emp_id	= $_POST['onbehalf_emp_id'];
			$purpose_visit		= $_POST['purpose_visit'];
			$traval_from		= $_POST['traval_from'];
			$traval_to			= $_POST['traval_to'];
			$start_date			= date('Y-m-d', strtotime($_POST['start_date']));
			$end_date			= date('Y-m-d', strtotime($_POST['end_date']));
			$start_time			= $_POST['start_time'];
			$end_time			= $_POST['end_time'];
			$advance_amount		= $_POST['advance_amount'];
			$estimated_days		= $_POST['estimated_days'];
			$advance_amount		= $_POST['advance_amount'];
			$remarks			= $_POST['remarks_hdr'];
			$booking_details	= $_POST['booking_details'];
			//$budget_id		    = $_POST['budget_id'];
			$mode_of_travel = $_POST['mode_of_travel'];
			$type_of_travel = $_POST['type_of_travel'];
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];

			$location			= $_POST['location'];
			$reason_travel		= $_POST['reason_travel'];
			
			$status				= $_POST['status'];
			$estimated_days_in_nights = $_POST['estimated_days_in_nights'];
			
  			$sql="update sma_traval_approval set traval_from	= '$traval_from',
						traval_to			= '$traval_to',
						company_id			= '$company_id',
						start_date			= '$start_date',
						end_date			= '$end_date',
						start_time			= '$start_time',
						end_time			= '$end_time',
						advance_amount		= '$advance_amount',
						purpose_visit		= '$purpose_visit',
						estimated_days		= '$estimated_days',
						remarks				= '$remarks',
						advance_amount		= '$advance_amount',
						booking_details 	= '$booking_details',
						mode_of_travel 		= '$mode_of_travel',
						type_of_travel 		= '$type_of_travel',
						onbehalf_emp_id		= '$onbehalf_emp_id',
						location			= '$location',
						reason_travel		= '$reason_travel',
						estimated_days_in_nights	= '$estimated_days_in_nights'
					where id = '$id' ";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$share_point_link = $_POST["share_point_link"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/ta/" . $ta_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) VALUES('TA', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', '" . $share_point_link[$i] . "',". $ta_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/ta/" . $ta_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_traval_approval set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$ta_id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'TA', '$ta_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "travel_approval/";
				
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
				
				$baseurl1 =$baseurl.$modulePath.'traval_app.php?sub=edit&id='.$ta_id;
				
				$msg = 'Travel Request Number : '.$ta_id . ' ' . 'Dated : ' . date("d-m-Y");
				
				include "ta_mail.php";	
					
			}
			
//	exit();
	
			echo '<script>window.location.href="traval_app.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_traval_approval where id ='$id'";
//echo $sql;		
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$status = $row['status'];
		$ta_id 	= $id;
		$draft_by = $row['draft_by'];
		$paid_status = $row['paid_status'];
		$current_approver = $row['current_approver'];
		
		
	$approver_1 		= $row['approver_1'];
	$approver_2 		= $row['approver_2'];
	$approver_3 		= $row['approver_3'];
	$approver_4 		= $row['approver_4'];
	
	$approver_1_status 	= $row['approver_1_status'];
	$approver_2_status 	= $row['approver_2_status'];
	$approver_3_status 	= $row['approver_3_status'];
	$approver_4_status 	= $row['approver_4_status'];
	
		$readonly = '';
		if ($status != 'Draft' ){
			$readonly = 'READONLY';
		}

		$del   = $row['del'];
		if($del =='Y'){
			$readonly = 'READONLY';
		}
		
		/* if ( $viewonly=='Y'){
			$readonly = 'READONLY';
		} */

//echo $status. ' <<>> ' . $del . ' <<>> ' . $viewonly;
		
?>

    <section class="content-header">
        <h1>
            Travel Request
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl . $modulePath."traval_app.php?sub=list" ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath."traval_app.php?sub=list" ?>">Travel Request</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="traval_app.php?sub=edit" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'traval_app.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		                
					<input type="hidden" id="status" name="status" value="<?php echo $status; ?>">
					
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
							
							<?php 
								
								$_SESSION[''] = $row[''];
								$_SESSION['ta_id'] 	= $id;
								$ta_id	= $id;
								$_SESSION['status'] = $status;

							?>
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
						if($_GET['active8']){
							$active_1 ='';
							$active8 = $_GET['active8'];
						}
						?>
						
					<ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Travel Request</a></li>
                        <li class="<?php echo $active;?>"><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
					<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
					<?php } ?>	
						<li><a href="travel_book_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row[''];?>&r=1" class="btn btn-success"  target="_blank" >Ticket Booking </a></li>
						<li><a href="travel_form_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row[''];?>&r=1" class="btn btn-success"  target="_blank" > HR TR Form </a></li>
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<?php 
								$user   = $_SESSION['user'];
								$emp_id 		= $row['emp_id'];
								
									$sql = "select * from sma_user where id = '$emp_id' ";
										$res1 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res1);
										$emp_id 		= $r1['id'];
										$role			= $r1['role'];
										$department		= $r1['department'];
										$designation	= $r1['designation'];
										$emp_name		= $r1['username'];
								
									$sql="SELECT * from sma_role where id = '$role' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$role 		= $r1['role'];
									
									$sql="SELECT * from sma_department where id = '$department' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$department 		= $r1['name'];
									
									$sql="SELECT * from sma_designation where id = '$designation' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
									$designation 		= $r1['designation'];
									
							?>
							
						<div class="form-group">
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label"> Request No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="asrno" name="asrno" style="text-align:right;" readonly value="<?php echo $row['id']?>">
							</div>
							
							<label class="col-lg-1 control-label">Date</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" autocomplete="off" <?php echo $readonly; ?> value="<?php echo date('d-m-Y', strtotime($row['dated']));?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							 <?php if($readonly){
									$disable = 'disabled="disabled"';
								} 
								
								$company_id = $row['company_id'];
								$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and comp_id = '$company_id' ";
							}
							 ?>
							 
							<label class="col-lg-1 control-label">Company  *</label>
							<div class="col-md-5">
								<select class="form-control" name="company_id" id="company_id" autocomplete="off" required <?php  echo $readonly; ?> >
                             		<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
								<?php } ?>
										<?php $sql = "select * from company where 1 $sqla order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group"> 
											
							<label class="col-lg-2 control-label">Name</label>			
							<div class="col-md-2">						
								<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $emp_name;?>">  
							</div> 
							
							<label class="col-lg-1 control-label">Role</label> 			
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $role;?>">  
							</div> 
							<?php 
								$onbehalf_emp_id = $row['onbehalf_emp_id']; 
								$sqlu ='';
								
								if($readonly){
									$sqlu = " and id = '$onbehalf_emp_id' ";
								}

								?>
							<label class="col-lg-2 control-label">On Behalf of *</label>
							<div class="col-md-3">
								<select class="form-control" name="onbehalf_emp_id" id="onbehalf_emp_id" autocomplete="off" required <?php echo $readonly; ?> >
							<?php	if(!$readonly){ ?>
                             		<option value=""> Select </option>
							<?php } ?>		
										<?php $sql = "select * from sma_user where 1 $sqlu order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($onbehalf_emp_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div> 
							
						<div class="form-group"> 
							<label class="col-lg-2 control-label">Department</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $department;?>">  
							</div> 
							<label class="col-lg-1 control-label">Designation</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $designation;?>">  
							</div> 
							
						<?php	
							$location = $row['location'];
						$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and id = '$location' ";
							}
						?>	
							<label for="location" class="control-label col-sm-1">Location *</label>
							<div class="col-sm-2">
									<select class="form-control" name="location" id="location" required >
									<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
								<?php } ?>
										<?php $sql = "select * from sma_location where 1 and loc_comp_id = '$company_id' $sqla order by loc_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
										<?php } ?>
									</select>	
                            </div>
								
								
						</div> 
			
			<?php	
				$budget_id   = $row['budget_id'];
			if(empty($budget_id)){	
				$sql = "SELECT DISTINCT(a.id) as id, b.id as budget_id, a.budget_name, a.budget_head, a.budget_code FROM `sma_budget_subgroup` a, sma_budget b where a.id = b.budget_head  and a.travel_module = 'Y' and b.project = '$company_id' and b.account_year = '$short_fy_code' order by budget_head ";
				$res2 			= mysqli_query($con, $sql);
				echo mysqli_error($con);
				$cat 		= mysqli_fetch_array($res2);
				$budget_id   = $cat['budget_id'];
			}
			
				$sql = "SELECT * FROM sma_budget where id = '$budget_id'  ";
				$res2 			= mysqli_query($con, $sql);
				$budget_sub_id 	= mysqli_affected_rows($con);
//echo $sql."<BR>";				
				echo mysqli_error($con);
				$cat 		= mysqli_fetch_array($res2);
				$budget_id   = $cat['id'];
				$budget_name_id		= $cat['budget_name'];
				$budget_head_id 	= $cat['budget_head'];
				$open_budget 		= $cat['total_budget'];
				$used_budget 		= $cat['used_budget'];
				$blocked_budget		= $cat['blocked_budget'];
				$adjustment_budget	= $cat['adjustment_budget'];
				$balance_budget 	= ($open_budget + $adjustment_budget) - ( $blocked_budget + $used_budget); 
				
				$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_head_id' ";
//echo $sql."<BR>";				
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_code 			= $com['budget_code'];
				$budget_head_name		= $com['budget_head'];
				$budget_head		 	= $com['id'];
				$sql="SELECT * from sma_budget_name where 1 and id = '$budget_name_id' ";
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_name 		= $com['name'];
				
		?>		
		
				<!--		<span id="getBudgetCode_b">
							<div class="form-group"> 
								<label for="location" class="control-label col-sm-2">Budget Head *</label>
								<div class="col-sm-8">
								<span id="getBudgetCode">
								<div class="box-body">
									<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>		
										<th>Budget Name</th>
										<th>Budget Head</th>
										<th>Budget Code</th>
										<th style="text-align:right;">Balance Budget </th>
									</tr>
								</thead>
								<tbody>
								<tr>
									<td><?= $budget_name;?></td>
									<td><?= $budget_head_name;?></td>
									<td><?= $budget_code;?></td>
									<td style="text-align:right;"><?= moneyFormatIndiaa($balance_budget);?></td>
									
								</tr>
								</tbody>
								</table>
								
								</span>
								</div>
								</div>
							</div>
						
							<div class="form-group">	
								<label class="control-label col-sm-2">&nbsp;</label>
								<div class="col-sm-8">	
								<span class="getBudgetCheck">
									
								</span>
								</div>
							</div>
						</span>
				-->
				
						<div class="form-group">

							<label class="col-lg-2 control-label">Reason for Travel *</label>
							<div class="col-md-10">
								<select class="form-control" name="reason_travel" id="reason_travel"  <?php echo $readonly; ?> required >
									<option value=""> Select </option>
										<?php $sql = "select * from reason_travel order by reason_travel ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['reason_travel'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['reason_travel'];?></option>
										<?php } ?>
								</select>
							</div>

						</div>
						
				<?php
					$required = "";
					if($status=='Draft'){
						$required = "required";	
					}	
				?>	
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Details of Visit *</label>
							<div class="col-md-9">
								<textarea class="form-control" rows="3" id="purpose_visit" name="purpose_visit"  <?php echo $required .' ' . $readonly; ?> autocomplete="off" ><?php echo $row['purpose_visit']?></textarea>
							</div>
						
							<div class="col-md-1">
								<input type="text" class="form-control" style="color:red;" readonly value="<?php echo $paid_status;?>">
							</div>
							
						</div>
						
						
						<?php
						
							$start_date = date('d-m-Y', strtotime($row['start_date']));
							if($start_date =='01-01-1970'){
								$start_date = '';
							}
							
							$end_date   = date('d-m-Y', strtotime($row['end_date']));
							if($end_date =='01-01-1970'){
								$end_date = '';
							}
							
							
						?>
						<?php	
							$traval_from = $row['traval_from'];
						$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and city_name = '$traval_from' ";
							}
						?>
						<div class="form-group">
							
							<label class="col-lg-2 control-label">From City *</label>
							<div class="col-md-2">
								<select class="form-control" name="traval_from" id="traval_from"  required <?php echo $readonly; ?> >
									 <?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "select * from cities where 1 $sqla order by city_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['city_name'];?>"  <?php echo ($row['traval_from'] == $r2['city_name'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
										<?php } ?>
								</select>
								
							</div>
							
							<label class="col-lg-1 control-label">Start&nbsp;Date&nbsp;*</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="start_date" name="start_date" required <?php echo $readonly; ?> autocomplete="off" value="<?php echo $start_date;?>" onchange="sdate()"> 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Travel Time  *</label>
							<div class="col-sm-2">
								<input type="time" class="form-control" id="start_time" name="start_time" required <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['start_time']?>">
							</div>
							
						</div>
						<?php	
							$traval_to = $row['traval_to'];
							$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and city_name = '$traval_to' ";
							}
						?>	
						<div class="form-group">
						
							<label class="col-lg-2 control-label"> To City *</label>
							<div class="col-md-2">
								<select class="form-control" name="traval_to" id="traval_to" required <?php echo $readonly; ?> >
									 <?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "select * from cities where 1 $sqla order by city_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['city_name'];?>"  <?php echo ($row['traval_to'] == $r2['city_name'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
										<?php } ?>
								</select>
							</div>
						
							<label class="col-lg-1 control-label">Return&nbsp;Date&nbsp;*</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control" id="end_date" name="end_date" required <?php echo $readonly; ?> autocomplete="off" value="<?php echo $end_date?>" onchange="sdate()">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Travel Time *</label>
							<div class="col-sm-2">
								<input type="time" class="form-control" id="end_time" name="end_time" required <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['end_time']?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="type_of_travel" class="control-label col-sm-2">Type Of Travel *</label>	
							<div class="col-sm-2">
								<select class="form-control" id="type_of_travel" name="type_of_travel" required="true" <?php echo $readonly; ?>>
								 <?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
									
								<option value='D' <?php echo ($row['type_of_travel'] == 'D')?'selected="selected"':'';?>>Domestic</option>
								<option value='I' <?php echo ($row['type_of_travel'] == 'I')?'selected="selected"':'';?> >International</option>
								</select>
							</div>
							
							<label for="type_of_travel" class="control-label col-sm-2">Mode Of Travel *</label>
							<div class="col-sm-2">
								<select class="form-control" id="mode_of_travel" name="mode_of_travel" required="true" <?php echo $readonly; ?> >
									 <?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
									<option value='F' <?php echo ($row['mode_of_travel'] == 'F')?'selected="selected"':'';?>>Flight</option>
									<option value='R' <?php echo ($row['mode_of_travel'] == 'R')?'selected="selected"':'';?>>Rail</option>
									<option value='D' <?php echo ($row['mode_of_travel'] == 'D')?'selected="selected"':'';?>>Road</option>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							
						<!--	<label class="col-lg-2 control-label">Advance Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="advance_amount" name="advance_amount" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['advance_amount']?>">
							</div>
						-->	
					<?php
						$estimated_days = $row['estimated_days'];
						if($estimated_days<0){
							$estimated_days='';	
						}	
						
					?>
					
							<label class="col-lg-2 control-label">Estimated Travel Days </label>
							<div class="col-md-2">
								<span id="getdays">
									<input type="text" class="form-control" id="estimated_days" name="estimated_days" style="text-align:right;" readonly autocomplete="off" value="<?php echo $estimated_days?>">
								</span>	
							</div>
							
							<label class="col-lg-2 control-label">Estimated&nbsp;Hotel&nbsp; Accommodation (in Nights) </label>
							<div class="col-md-2">
								<span id="getdays">
									<input type="text" class="form-control" id="estimated_days_in_nights" name="estimated_days_in_nights" style="text-align:right;"  autocomplete="off" value="<?php echo $row['estimated_days_in_nights']?>">
								</span>	
							</div>
						
						</div>
						
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Booking Details</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="3" name="booking_details"  autocomplete="off" <?php echo $readonly; ?> ><?php echo $row['booking_details']?></textarea>
							</div>
						
						</div>
						
					<!--	<div class="form-group">
							
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="2" name="remarks_hdr"  autocomplete="off" <?php echo $readonly; ?> ><?php echo $row['remarks']?></textarea>
							</div>
						
					</div>-->
						
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
				</div>
						 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
						<div class="tab-pane <?php echo $active;?>" id="tab_2">
						
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'TA' AND reference_id = " . $ta_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          
                                          <th width="20%">Document Type</th>
                                          <th width="25%">Share Point Link</th>
                                          <th width="20%">Description</th>
										  <th width="25%">Document Name</th>
											<th width="10%">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td  width="20%"><?php echo $document; ?></td>
											  <td  width="25%"><a target="_blank" href="<?php echo $docRow['share_point_link'] ?>"><?php echo $docRow['share_point_link'] ?></a></td>
                                              <td  width="20%"><?php echo $docRow['doc_desc'] ?></td>
                                              <td  width="25%"><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <?php if(!$readonly){ ?>
													<td  width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
											  <?php } ?>  	
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]"   <?php echo $readonly; ?>>
                                                 <?php	if($status == 'Draft'){ ?>
											<option value=""> Select </option>
										<?php } ?>
												<?php
												$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td width="25%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" <?php echo $readonly; ?> placeholder="Enter document description..."></textarea>
										</td>
										
										<td>
											<input type="file" name="fudoc[]" <?php echo $readonly; ?> class="docfile">
										</td>
										 <?php //if(!$readonly){ ?>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 <?php //} ?>
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>

<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">

						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

							
						<span id="predit"></span>
					
					<?php //if($del !='Y'){ ?>			
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
						
							//if ( $viewonly!='Y'){
								if ($status == 'Draft'  ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."traval_app.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php }
							
								if ( $approval_status=='Rejected' || $status!='Completed' || $user=='Admin' ){	
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
							<?php	
								}
							//}  ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php		
										$role		= $_SESSION['role'];
										$user   	= $_SESSION['user'];
										$userid   	= $_SESSION['usrid'];
										$sent_to   	= $row['sent_to'];
										$baseurl1 = $baseurl.$modulePath."traval_app.php?sub=list";
								
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
									$userid == $approver_2 && $approver_2_status=='Submitted' || 
									$userid == $approver_3 && $approver_3_status=='Submitted' ||
									$userid == $approver_4 && $approver_4_status=='Submitted'
									 ){
				
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted' && empty($approver_3_status) && empty($approver_4_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted' ){
										$approver_flag='Y';
									}
									
								}
								?>
								
								
								<?php		if ( $status == 'Submitted' && $approver_flag=='Y'){
										?>
									
									<span class='approve_btn'>	
											<span>&nbsp;&nbsp;</span>
											<a href="#approvalAuthority" class="btn btn-primary " data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Approve</a>
											&nbsp;&nbsp;
											<a href="#rejectAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#rejectAuthority">Reject</a>
											&nbsp;&nbsp;
									</span>
									
									<?php } ?>
								
								
								<?php	
										if ($status == 'Draft' ){
											
									?>
										
								
								
								
						<?php //echo $viewonly; $viewonly!='Y' && 
						if ( $status == 'Draft' && $approval_status != 'Rejected' ){ ?>	
									<span class='hidesend'>	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
									</span>	
						<?php }
						?>
								
								<!--<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>-->
						<?php			
									 if ( $rol == 'Maker' ){
									?>
										<input class="btn btn-primary" type="submit" value="Withdraw" name="Withdraw">&nbsp;&nbsp;&nbsp;
										
								<?php } 
								 }	
								?>
								
								<?php 	//if ( $viewonly!='Y'){ ?>	
								<span class='approve_btn'>
										<input class="btn btn-primary" type="submit" value="Save " name="Save">&nbsp;&nbsp;&nbsp;
								</span>		
									<?php //} ?>
									
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back</a>
								
								</div>
								
							</div>
						<?php //} ?>
						
						
						
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
									
									$srno = $ta_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'TA' order by id ";
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
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'TA' order by id desc ";
										//echo $s1;
											$res  = mysqli_query($con, $s1);
											//echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$approved_dt		= date('d-m-Y', strtotime($r1['approved_date']));
												if($approved_dt=='01-01-1970' || $approved_dt =='31-12-1969'){
													$approved_date ='';
												}
												
												$remarks 			= $r1['remarks'];
												
												if(empty($reviewed_by)){
													$reviewed_by = '0';
												}
												
												$s2="SELECT * FROM sma_user where id in ($reviewed_by) ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$reviewed_by = '';
												while ($rw1 = mysqli_fetch_array($r3)){
													$reviewed_by .= $rw1['username'].', ';
													$role 		= $rw1['primary_role'];
												}
										//echo $reviewed_by . " <<<<<BR>";

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
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
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
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
				<?php 
				$doc_type = 'TA';
				$s1  = " SELECT * from sma_comment where doc_id = '$ta_id' and doc_type = '$doc_type' order by id desc ";
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
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $ta_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $ta_id;?>,'<?= $doc_type; ?>','C',1<?= $page;?>)" >Submit</button>		
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
							
							
						
						
					</div>
					
                    <!--    <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="traval_app.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
                        
							
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



<!--Make to DraftPopup-->

<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority" data-keyboard="false" data-backdrop="static">
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
										<input type="hidden" name="ta_id" id="ta_idDR" value="<?php echo $ta_id;; ?>" >
										
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


<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To Approver... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ta_id	= $_SESSION['ta_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="ta_id" id="ta_idE" value="<?php echo $ta_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
									<!--	<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>-->
										
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
											$ta_id	= $_SESSION['ta_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
											
										?>
										
										<input type="hidden" name="ta_id" id="ta_idS" value="<?php echo $ta_id; ?>" >
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
										
											$ta_id = $_SESSION['ta_id'];
											$status = $_SESSION['status'];
										
										?>
										
										<input type="hidden" name="ta_id" id="ta_idP" value="<?php echo $ta_id; ?>" >
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
											$ta_id 	= $_SESSION['ta_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ta_id" id="ta_idR" value="<?php echo $ta_id; ?>" >
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
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Traval Request data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="tr_export_func.php?sub=pdf" target="_blank" method="POST" >
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
			
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
 </script>
 <!-- For Document Attachment End-->

 
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
 
   $("#submitChecker").on("click", function(e){
        var sub = 'sub1';
		var mode		 	=  $("#modeC").val();
		
		var ta_id		 	=  $("#ta_idE").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
		
//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		 $('#checkerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ ta_id:ta_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub1:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
   $("#submitNext").on("click", function(e){
        var sub = 'sub2';
		var mode		 	=  $("#modeS").val();
		
		var ta_id		 	=  $("#ta_idS").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		$('#submitAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ ta_id:ta_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub2:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub2';
		var mode		 	=  $("#modeP").val();
//		alert(sub + ' ' + mode);		 
		var ta_id		 	=  $("#ta_idP").val();
	    var status 			=  $("#statusP").val();
		
        var remarks			=  $("#remarksP").val();
		
		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ta_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ ta_id:ta_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub2:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub8';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var ta_id		 	=  $("#ta_idR").val();
		
		var status 			=  $("#statuS").val();
		var remarks			=  $("#remarksR").val();
		
		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ta_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ta_id:ta_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

</script>
	
<script>
function getempname(id){
    var sub = 'sub1';
//alert(id);
	var strURL = "ta_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getempname').html(result);
		});
}

function sdate(){
	var sub = 'sub3';
	var date1		 	=  $("#start_date").val();
	var date2		 	=  $("#end_date").val();
//alert(date1 + '  ' + date2);
	var strURL = "ta_func.php";
	
	$.post(strURL,{ sub3:sub,date1:date1,date2:date2},function(result){
		  $('#getdays').html(result);
		});

}


function getsearchf(id){
    var sub = 'sub1';

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}

	function getcostcenter(id){
		
        var sub    = 'sub25';
		//var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub25:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcatbudget (id){
	
		var sub    = 'sub26';
		var strURL = "app_func.php";
		
//alert(sub + ' ' + id + ' ' + ' ' + strURL);
		$.post(strURL,{id:id,sub26:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}

	function getapprover(){
		
		var sub = 'sub34';
//alert(sub );			
		var company_id    	= document.getElementById("company_id").value;
		var onbehalf_emp_id    	= document.getElementById("onbehalf_emp_id").value;
		
		
		var docs_type		= 'TA';
//alert(sub + ' ' + company_id + ' ' );
		$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,onbehalf_emp_id:onbehalf_emp_id,docs_type:docs_type,sub34:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	

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
	
	$("#submitDraft").on("click", function(e){
        var ta_id		 	=  $("#ta_idDR").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
//alert(remarks +  ' ' + ta_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "ta_draft_func.php";
		$.post(strURL,{ ta_id:ta_id,
						status:status,
						doc_type:'TA',
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	function getcomment(comment,ta_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ta_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,re_id:ta_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}

	function getlocation(id){
		
        var sub    = 'sub37';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub37:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getBudgetCode(id){
		
        var sub    = 'sub41';
//alert(sub);
		$('#getBudgetCheck').hide();	
		$('#hideSave').show();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub41:sub},function(result){
			//$('#getBudgetCode').html(result);
				var fields = result;
				//alert(result);
				var myArray = fields.split("##");
				var fields  = myArray ['0'];
			//alert(fields);	
				fields = fields.trim();
				fields = fields.slice(0, 2);
				var result  = myArray ['1'];
			if(fields == 'NO'){
				$('#getBudgetCode_a').hide();
				$('#getBudgetCode').html(result);
				$('#hideSave').hide();
				
			}
			else {
				var result  = myArray ['0'];	
				
				$('#getBudgetCode').html(result);
				
				var result  = myArray ['2'];	
				//alert(result);
				$('.getBudgetCheck').html(result);
			}	
			
		});

	}
	
	function getBudgetCheck(id){
		
        var sub    = 'sub42';
//alert(sub + ' ' + id);
		$('#hideSave').show();
		$('#getBudgetCode_a').hide();	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub42:sub},function(result){
			//alert(result);
		      
			  var fields = result;
				//alert(result);
				var myArray = fields.split("##");
				var fields  = myArray ['0'];
				fields = fields.trim();
				fields = fields.slice(0, 2);
				var result  = myArray ['1'];
				$('.getBudgetCheck').html(result);
			if(fields == 'NO'){
				$('#hideSave').hide();
			}	
		});

	}
	
</script>



</body>
</html>
