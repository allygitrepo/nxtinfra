<?php
include("../header.php");
$modulePath = "travel_approval/";

$pgname = "travel_approval/regular_expense.php";
include("../viewonly.php");

$userid   	= $_SESSION['usrid'];

$user       = $_SESSION['user'];
	$help_code = 'regular_expense.php';
	include "../help_code.php";
?>

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
	
	$yesterday_date = date("Y-m-d");
	$sql = " delete from sma_travel_expenses where exp_type = 'R' and emp_id = '' and company_id = '' and draft_dated < '$yesterday_date' ";
	mysqli_query($con, $sql);
	
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Reimbursement  
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Reimbursement</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				 
			<?php 
			
			
				$targetpage = "regular_expense.php?sub=list"; 
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
              
					<form class="form-horizontal" action="regular_expense.php?sub=list" method="post">
                      
					<div class="form-group">
						
						<div class="form-group">
						
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-3">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Company Name </option>
									<option value="E" <?php echo ($searchf == 'E')?'selected="selected"':'';?>> Employee Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									searchf
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> Ready for Tally Update</option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Tally Updated </option>
									<option value="L" <?php echo ($searchf == 'L')?'selected="selected"':'';?>> Tally Unticked</option>
									<option value="Draft" <?php echo ($searchf == 'Draft')?'selected="selected"':'';?> > Draft </option>
									<option value="Submitted" <?php echo ($searchf == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
									<option value="Completed" <?php echo ($searchf == 'Completed')?'selected="selected"':'';?>> Completed </option>
									<option value="Rejected" <?php echo ($searchf == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deleted </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
									<option value="U" <?php echo ($searchf == 'U')?'selected="selected"':'';?>> Unpaid </option>
								</select>
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='E'){ ?>	
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
							
							<?php if($searchf=='E'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
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
                                		
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="regular_expense.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>	
							
				</form>
				
				
             <?php //echo $addonly."<<>>";
				//	if ( $addonly=='Y'){ ?>
                <span class="pull-right"><a href="regular_expense.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			 <?php //} ?>	
				<span class="pull-right"><a href="regular_exp_export_func.php?sub=pdf&gtype=R"
                                                target="_blank"  class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;
				</span>
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>SrNo.</th>
			<th>On Behalf of Name</th>
			<th>Company</th>
			<th>Date</th>
			<th  style="text-align:right;">Total Amount</th>
			<th>Payment Status</th>
			<th>By</th>
			<th>Pending With</th>
			<th>Status</th>
			<th>Decision</th>
			<th>Tally Status</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "travel_approval/";
	
	$department = $_SESSION['department'];
	
	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];

	//$sql="SELECT * from sma_travel_expenses where emp_id = '$usrid'";
	$sql = "SELECT count(*) as cnt from sma_travel_expenses 
				where exp_type = 'R' and ( emp_id = '$usrid' || onbehalf_emp_id = '$usrid' ) 
					and status != 'Withdraw' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1  = mysqli_fetch_array($result);
	$cnt = $r1['cnt'];
//echo $role. ' ' . $accountant_role;

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
					
					if($searchf=='E'){
						$sqla .= " and (emp_id = '$search_data' || onbehalf_emp_id = '$search_data' ) ";
						$querya .= " and (emp_id = '$search_data' || onbehalf_emp_id = '$search_data' )" ;
					}
					
					if($searchf=='R'){
						$sqla .= " and tally_status in ('R') ";
						$querya .= " and paid_status != 'Paid' ";
					}
					if($searchf=='T'){
						$sqla .= " and tally_status in ('U') ";
						$querya .= " and paid_status != 'Paid' ";
					}
					if($searchf=='L'){
						$sqla .= " and tally_status not in ('U', 'R') ";
						$querya .= " and paid_status != 'Paid' ";
					}
					
					if( $searchf == 'U' ){
						$sqla .= " and paid_status != 'Paid' ";
						$querya .= " and paid_status != 'Paid' ";
					}
					
					if( $searchf == 'V' ){
						$sqla .= " and del = 'Y' ";
						$querya .= " and del = 'Y' ";
					}
					else {
						$sqla .= " and del !='Y' ";	
						$querya .= " and del !='Y' ";	
					}
					if( $searchf == 'Draft' ||  $searchf == 'Submitted' ||  $searchf == 'Completed'){
						$sqla .= " and status = '$searchf' ";
						$querya .= " and status = '$searchf' ";
					}
					if( $searchf == 'Rejected' ){
						$sqla .= " and approval_status = '$searchf' ";
						$querya .= " and approval_status = '$searchf' ";
					}
					
		
	if ($cnt>0){
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'R' and (emp_id = '$usrid' || onbehalf_emp_id = '$usrid') and status != 'Withdraw' $sqla ";
		
	}
	else {
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'R' and (emp_id = '$usrid' || onbehalf_emp_id = '$usrid') and status != 'Withdraw' $sqla
		         union 
				 SELECT * from sma_travel_expenses where exp_type = 'R' and level_1 = '$usrid' $sqla ";
	}
	
	
	if ( $primaryrole == 'Athaang Functional Representative' || $primaryrole == 'HR Approver'){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'R' and company_id in ($comid) $sqla";
		
	}
	
	if ($accountant_role=='Y' || $viewonly =='Y' ){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'R' and company_id in ( $comid )  $sqla";
		
	}
	else if(empty($searchf)){
			if (empty($status) && empty($comp_id) && empty($approval_status) && $user!='Admin' ){
				$sql .= " and ( ( draft_by = '$user' || onbehalf_emp_id = '$usrid' ) and emp_id !='' and company_id !='' and del !='Y' and exp_type = 'R'  ) ";
				$query .= " or ( ( draft_by = '$user' || onbehalf_emp_id = '$usrid' ) and emp_id !='' and company_id !='' and del !='Y' and exp_type = 'R' ) ";
			}
	}
	if ($user=='Admin' || $primaryrole =='COO' || $primaryrole == 'Director' || $department==6){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'R' $sqla";
		
	}
					
	$sql .= " and emp_id != '' and company_id != '' ";
	
	$_SESSION['sqlreg'] = $sql;
	
			$qresult = mysqli_query($con,$sql);
			$total_pages = mysqli_affected_rows($con);
					
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
	
	$sql.=" order by id desc ";
	//$sql .= " LIMIT $start, $limit ";		

	//include("paginate.php");
	
//echo $department . ' ' ;
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
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];
		$comp_code 	= $r1['comp_code'];

		//$emp_id = $row['emp_id'];
		$emp_id = $row['onbehalf_emp_id'];
		$sql  = "SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$username		= $r1['username'];
		
		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'R' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
			$styl  = '';
			$styl2 = '';
			$del   = $row['del'];
			if($del =='Y'){
						
				$styl = "style='bgcolor:powderblue;color:red;' ";
				$styl2 = "bgcolor:powderblue;color:red; ";
							
			}
		
		$re_id = $row['id'];
		$sql = " SELECT a.* FROM payment_header a, payment_details b where b.supp_id = '$re_id' and  a.id = b.payment_hdr_id and a.st_flag = 'T' and a.del !='Y' and a.status = 'Completed' ";
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

		$changed_by = $row['changed_by'];
		if(empty($changed_by)){
			$changed_by = $row['draft_by'];
		}	
		
		$sql = "select * from sma_user where userid = '$changed_by' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$changed_by  = $r2['username'];
		
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$row["id"].'&page='.$page;
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "regular_expense.php?sub=edit&id=". $row['id'].'&page='. $page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"<?php echo $styl; ?>><?php echo $row['id'];?></td>
		<td width="15%"<?php echo $styl; ?>><?php echo $username;?></td>
		<td width="08%"<?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="10%"><?php echo $paid_status;?></td>
		<td width="09%"<?php echo $styl; ?>><?php echo $changed_by;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $pending_by;?></td>
		<td width="09%"<?php echo $styl; ?>><?php echo $row['status'];?></td>
		<td width="09%"<?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
		<td width="10%"<?php echo $styl; ?>><?php echo $tally_status_a;?></td>
		
		<!--<td width="5%" style="text-align:right;">
		<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>-->
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>
<?php
/*   $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  echo $paginate;
 */
 ?>
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){
        $id = $_GET['id'];
	//	$sql="delete from sma_travel_expenses where exp_type = 'R' and id='$id' ";
		$sql = "update sma_travel_expenses set del = 'Y'  where exp_type = 'R' and id='$id' ";
		
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);

	/*	$sql="delete from sma_expenses where exp_type = 'R' and approval_ref_no ='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql="delete from workflow_history where doc_type = 'RE' and doc_id ='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
	*/
        echo '<script>window.location.href="regular_expense.php?sub=list";</script>';
	} 
?>

<?php

	if(isset($_POST['editItem'])){

		$rid     		= $_POST['rid'];
		$dated			= date('Y-m-d', strtotime($_POST['dated']));
		$approval_ref_no= $_POST['approval_ref_no'];
		$reference 		= $_POST['reference'];
		$invoice_no 	= $_POST['invoice_no'];
		$amount 		= $_POST['amount'];
		$amount_p 		= $_POST['amount_p'];
		$note 			= $_POST['remarks'];
		$gst_flag 		= $_POST['gst_flag'];

		$budget_name	= $_POST['budget_name'];
		$budget_head 	= $_POST['budget_head'];
		$total_budget 	= $_POST['total_budget'];
		$balance_budget = $_POST['balance_budget'];
		$budget_id 		= $_POST['budget_id'];
		$onbehalf_emp_id	= $_POST['onbehalf_emp_id_v'];
		
 		$mobile_reimbure_flag 	= $_POST['mobile_reimbure_flag_v'];
//exit('####');
		if($mobile_reimbure_flag=='Y'){
			$sql = "SELECT sum(b.amount) as mobile_value  from sma_travel_expenses a, sma_expenses b 
										where 1 and a.exp_type = 'R' and a.id = b.approval_ref_no and a.onbehalf_emp_id = '$onbehalf_emp_id' 
											and year(b.dated) = year('$dated') and month(b.dated) = month('$dated'); ";	
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$mobile_value	= $r2['mobile_value'];
			if(empty($mobile_value)){
				$mobile_value = 0;	
			}
		
			//$mobile_value 			= $_POST['mobile_value'];
			$mobile_reimburse_limit = $_POST['mobile_reimburse_limit_v'];
			
			$cur_amount		= $mobile_value + $amount - $amount_p;
			if($cur_amount > $mobile_reimburse_limit){
				echo "<script>alert('Mobile reimbursement limit exceeds for this months ##2!!!');</script>";
				echo "<script>window.location.href='regular_expense.php?sub=edit&id=$approval_ref_no';</script>";
				exit();
			}	
		}

		$sql = "update `sma_expenses` set dated	= '$dated',
						reference 		= '$reference',
						invoice_no 		= '$invoice_no',
						amount 			= '$amount',
						note 			= '$note',
						gst_flag 		= '$gst_flag',
						budget_name		= '$budget_name',
						budget_head 	= '$budget_head',
						balance_budget  = '$balance_budget',
						budget_id 		= '$budget_id'

				where id = '$rid' ";
		$r2 = mysqli_query($con, $sql);

		$sql = "update sma_budget set used_budget = used_budget - $amount_p + $amount where id = '$budget_id' ";
		mysqli_query($con, $sql);

	echo "<meta http-equiv='refresh' content='0'>";    
	//$baseurl.=$modulePath.'edit.php?approval_ref_no='.$approval_ref_no.'&active=active&987';
	//echo "<script>window.location.href='$baseurl';</script>";
	echo "<script>window.location.href='regular_expense.php?sub=edit&id=$approval_ref_no';</script>";
}
 
?>
 
<?php if($_GET['sub'] == 'add'){
?>


<?php
	if(isset($_POST['Save'])){
			
			
			$id 				= $_POST['id'];
			
  			//$emp_id 			= $_POST['emp_id'];
			$emp_id 			= $userid ;
			$onbehalf_emp_id	= $_POST['onbehalf_emp_id'];
			$company_id 		= $_POST['comp_id'];
			$dated				= date('d-m-Y', strtotime($_POST['dated']));
			$approval_ref_no	= $_POST['approval_ref_no'];
			$re_id				= $_POST['approval_ref_no'];
			$location			= $_POST['location'];
			$total_amount		= $_POST['total_amount'];
			$datedd				= date('Y-m-d', strtotime($_POST['dated']));
			$remarks	 	 	= $_POST['remarkss'];
			$trans_type			= $_POST['trans_type'];
			$exp_type			= 'R';
			
			$status 			= 'Draft';

			$user=$_SESSION['user'];
			
			$total_amount =0 ;
			
			$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$re_id' and exp_type = 'R' ";
				$q2  = mysqli_query($con, $sql);
//echo $sql. "<BR>";				
			while($r2 = mysqli_fetch_assoc($q2)){
				
				$total_amount += $r2['amount'];
				
			}	
			
			$sql = "select count(*) as cnt from sma_travel_expenses where id = '$id' ";

//echo $sql. ' <<>> ' . $company_id. ' <<>> '; exit();

			$query=mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($query);
			$nct	=	$r2['cnt'];
			if($nct>0){
				$sql="update sma_travel_expenses set company_id ='$company_id',
						approval_ref_no	= '$approval_ref_no',
						emp_id			= '$emp_id',
						onbehalf_emp_id	= '$onbehalf_emp_id',
						dated			= '$datedd',
						location		= '$location',
						total_amount	= '$total_amount',
						trans_type		= '$trans_type',		  
						remarks 		= '$remarks'
					where id='$id'";
			}
			else {
				$exp_type			= 'R';
				$sql="Insert into sma_travel_expenses ( id, exp_type, emp_id, onbehalf_emp_id, company_id, dated, approval_ref_no, total_amount, status, draft_by, draft_dated, total_amount, location, trans_type ) values ('$id', '$exp_type', '$emp_id', '$onbehalf_emp_id', '$company_id', '$datedd', '$approval_ref_no', '$tot_amount', 'Draft', '$user', now(), '$total_amount' , '$location', '$trans_type' ) ";
			}
			
  			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status ) 
										values('RE', '$id', '$userid', now(), 'Draft')";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//echo $sql;
//exit();
			
			//$re_id = mysqli_insert_id($con);
//exit();			
//			echo "Reimbursement successful added";
			
			echo "<script>window.location.href='regular_expense.php?sub=edit&id=$re_id';</script>";
			//echo "<script>window.location.href='regular_expense.php?sub=edit&id=$re_id&next=active';</script>";			
			
			exit();
			
			//echo '<script>window.location.href="regular_expense.php?sub=list";</script>';
		
		}
	
		$sql = "select max(id) as id from sma_travel_expenses ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$re_id	=	$r2['id'] + 1;
							
							$exp_type			= 'R';
							$status 			= 'Draft';
							$user   			= $_SESSION['user'];
							$sqlI="Insert into sma_travel_expenses ( exp_type, id, status, draft_by, draft_dated ) values ('$exp_type', '$re_id', 'Draft', '$user', now() ) ";
			//echo $sqlI;
							$query=mysqli_query($con, $sqlI);
							$error= mysqli_error($con);
							if(!empty($error)){echo $error; exit();}

?>

    <section class="content-header">
        <h1>
            Reimbursement
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Reimbursement</a></li>
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
            <form class="form-horizontal" action="regular_expense.php?sub=add" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php 
								
								//$sql = "select max(id) as id from sma_travel_expenses ";
								//$q2 = mysqli_query($con, $sql);
								//$r2 = mysqli_fetch_array($q2);
								//$re_id	=	$r2['id'] + 1;
								
								
						?>				
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						<?php 
							
							$user   = $_SESSION['user'];

									$sql = "select * from sma_user where userid = '$user' ";
										$res1 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res1);
										$emp_id 		= $r1['id'];
										$onbehalf_emp_id= $emp_id ;
										$roll_no 		= $r1['roll_no'];
										$role			= $r1['role'];
										$department		= $r1['department'];
										$designation	= $r1['designation'];
										$level_id		= $r1['level_id'];
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
									
									$sql="SELECT * from sma_level where id = '$level_id' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
									$level_id 		= $r1['level_name'];	
										
							?>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">SrNo.</label>
							<div class="col-md-2">
							
								<input type="text" class="form-control" id="approval_ref_No" name="approval_ref_no" readonly style="text-align:right;" value="<?php echo $re_id; ?>">
								
							</div>
							
							<label class="col-lg-1 control-label">Date <span style="color:red;">*</span></label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" autocomplete="off" required <?php echo $readonly; ?> value="<?php echo date('d-m-Y');?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label class="col-lg-1 control-label">Company&nbsp;*</label>
							<div class="col-md-4">
								<select class="form-control" name="comp_id" id="company_ID" autocomplete="off" required onchange="getlocation(this.value);getworkflow(this.value);" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							<span id="getvertical"></span>
						</div>
						
						
						<div class="form-group"> 
								
							<label class="col-lg-1 control-label">Name</label>			
							<div class="col-md-2">						
								<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $emp_name;?>">  
							</div> 
							
							<label class="col-lg-1 control-label">Role</label> 			
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $role;?>">  
							</div> 
							
							<label class="col-lg-2 control-label">On Behalf of</label>
							<div class="col-md-3">
								<select class="form-control" name="onbehalf_emp_id" id="onbehalf_emp_id" autocomplete="off" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
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
							
							<label for="location" class="control-label col-sm-1">Location</label>
							<div class="col-sm-2">
								<span id="getlocation">	
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
										
									</select>	
								</span>
								
                            </div>
							
						</div> 
						
						<div class="form-group">
							<div class="col-sm-5">
								<label for="company_id" class="control-label ">Workflow Type *</label>
							<span id="getworkflow">		
								<select class="form-control select3" name="trans_type" id="trans_type" required >
                             		<option value=""> Select </option>
									</option>
										
								</select>
							</span>	
							</div>	
						
							
							<div class="col-md-7">
								<label class=" control-label">Remarks</label>
								<textarea class="form-control" rows="2" name="remarkss" required autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
						
						</div>
						
							<!-- Step 1 -->
				<!--	<div class="panel-group" id="steps">
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"><i class="fa fa-expand "></i>&nbsp;&nbsp; Expenses <span class="caret"></span></a>
								
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
						
				</div>
			</div>	-->
								
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'regular_expense.php?sub=list';?>
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

		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		$doc_type 		= 'RE';
		
		if(($account_type == 'E' || $account_type =='D' )){
			$sql= " update tally_journal_entry set amount = amount - '$amount' + $amount_prev where effect='CR' and account_type = 'V' and doc_type='$doc_type' and doc_no='$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else if( $account_type == 'A'){
			$sql = " update tally_journal_entry set amount = amount - $amount + $amount_prev where doc_type = '$doc_type' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'B' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		
		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'company_expense.php?sub=edit&id='.$si_hdr_id.'&active5=active&zyx';
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
			$onbehalf_emp_id	= $_POST['onbehalf_emp_id'];
			$total_amount 	= $_POST['total_amount'];
			$dated		 	= date('Y-m-d', strtotime($_POST['dated']));
			$approval_ref_no= $_POST['approval_ref_no'];
			$remarks	 	= $_POST['remarkss'];
			$tally_status			= $_POST['tally_status'];
			$tally_narration		= $_POST['tally_narration'];
			$trans_type				= $_POST['trans_type'];

			$location			= $_POST['location'];
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];

			$status				= $_POST['status'];
			
//echo $tally_status. "<>BR"; exit();
			
			$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$re_id' and exp_type = 'R' ";
				$q2  = mysqli_query($con, $sql);
//echo $sql. "<BR>";				
			while($r2 = mysqli_fetch_assoc($q2)){
				
				$tot_amount += $r2['amount'];
				
			}
			
  			$sql="update sma_travel_expenses set company_id ='$company_id',
						approval_ref_no	= '$approval_ref_no',
						total_amount	= '$tot_amount',
						remarks 		= '$remarks',
						onbehalf_emp_id	= '$onbehalf_emp_id',
						location		= '$location',
						trans_type		= '$trans_type',
						dated			= '$dated',
						tally_narration	= '$tally_narration'
					where id='$id'";
//echo $sql."<BR>";
//exit();
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $tally_status. "<<>>";		
			if($tally_status=='R' || $tally_status=='C' || $tally_status=='N'){
				$sql="update sma_travel_expenses set tally_status = '$tally_status', tally_ticked_by = '$user', tally_updated_on = now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set status = '$tally_status', narration = '$tally_narration' where doc_no = '$id' and doc_type = 'RE' ";
				$r2  = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE

			}

//echo $sql."<BR>";
//exit();
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
					where id='$re_id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'RE', '$re_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
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
				
				$baseurl1 =$baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$re_id;
				
				$msg = ' Reimbursement Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
				$msg1 = ' Reimbursement ';
				$doc_type = 'RE';
				include "te_mail.php";	
					
			}
			
			
			// add attachments
			// file upload
			$arrDocType 		= $_POST["doctype"];
			//$share_point_link 	= $_POST["share_point_link"];
			$arrDocDesc 		= $_POST["docdesc"];
			$doc_invoice_no		= $_POST['doc_invoice_no'];
			$arrFUDoc 			= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++){
				$doc_invoice	= $doc_invoice_no[$i];
				$folder_path = "uploads/re/" . $re_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if(!empty($filename)){

					$sql = "INSERT INTO file_uploads ( module, doc_type, doc_desc, file_name, file_path, reference_id, date_uploaded, doc_invoice_no ) VALUES('RE', '$arrDocType[$i]', 
					'$arrDocDesc[$i]', '$filename' , '$folder_path', '$re_id', now(), '$doc_invoice' )";
					if (mysqli_query($con, $sql)) {
							move_uploaded_file($tmpFileName, "uploads/re/" . $re_id . "/" . $filename);
						}
						else {
							echo "Error: " . mysqli_error($con);
					}
					
				}
//echo $sql;				
			}
//echo $sql;			
//exit();
			$page					= $_POST['page']; 
			echo "<script>window.location.href='regular_expense.php?sub=list&same_page=$page';</script>";
			
		}
		
		$page = $_GET['page'];
		
		$id = $_GET['id'];
		$approval_ref_no = $_GET['approval_ref_no'];
		
		$sql="Select * from sma_travel_expenses where exp_type = 'R' and id ='$id' ";

//echo $sql;
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$re_id = $row['id'];
		$_SESSION['re_id'] = $row['id'];
		$status 			= $row['status'];
		$_SESSION['status'] = $status;
		
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
		
		$tally_status 	= $row['tally_status'];
		$tally_updated_on	= $row['tally_updated_on'];
		
	//echo	substr($tally_updated_on,0,2);
		if(substr($tally_updated_on,0,2)=='00'){
			$tally_updated_on ='';
		}	
		
		$tally_ticked_by 	= $row['tally_ticked_by'];
		$current_approver 	= $row['current_approver'];
		$approval_status 	= $row['approval_status'];
		
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
		
		$draft_by 	= $row['draft_by'];
		$paid_status = $row['paid_status'];
		
		$readonly = '';
		if ($status != 'Draft'){
			$readonly = 'READONLY';
		}
		
		$del   = $row['del'];
		if($del =='Y'){
			$readonly = 'READONLY';
		}	
		
		
		if ( $viewonly=='Y'){
			//$readonly = 'READONLY';
		}

//echo $status. ' ' . $readonly. "<BR>";		

?>

    <section class="content-header">
        <h1>
            Reimbursement
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Reimbursement</a></li>
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
            <form class="form-horizontal" action="regular_expense.php?sub=edit" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<input type="hidden" name="page" value="<?= $page;?>">
						
					<input type="hidden" id="status" name="status" value="<?php echo $status; ?>">
					
					<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
						
					<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'regular_expense.php?sub=list'.'&same_page='.$page; ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
						
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

						$company_id 		= $row['company_id'];
						$emp_id 			= $row['emp_id'];
						
					?>
                   
				   <ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Reimbursement</a></li>
						<?php //if($role!='Maker'){ ?>
						<!--	  <li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >Tally Journal</a></li> -->
						<?php //} ?>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>	
						<li><a href="regular_exp_repo.php?sub=pdf&id=<?php echo $re_id;?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						<li><a href="voucher_prn.php?sub=pdf&id=<?php echo $re_id; ?>&company_id=<?php echo $company_id?>&vendor_id=<?php echo $emp_id;?>" class="btn btn-danger" target="_blank" >Voucher</a></li>
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group">
						</div>
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						<div class="form-group">
							<label class="col-lg-2 control-label">SrNo.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="approval_ref_No" name="approval_ref_no" readonly style="text-align:right;"  value="<?php echo $re_id; ?>">
							</div>
						<?php
							$dated				= date('d-m-Y', strtotime($row['dated']));
							$dated_v 			= $row['dated'];
							if($dated=='01-01-1970'){ $dated='';}
							$approval_ref_no	= $re_id;
							$advance_amount		= $row['advance_amount'];
							
							$company_id = $row['company_id'];
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$vertical_type 	= $r2['comp_vertical'];
							$comp_code 		= $r2['comp_code'];
							
							$emp_id 		= $row['emp_id'];
									$sql 	= "select * from sma_user where id = '$emp_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$user_name = $r2['username'];
								
							$label_line .= '<b>Doc SrNo:</b>'.$row['id']. ' ' .
												' <b>Company:</b>'.$comp_code. ' ' . ' <b>User Name :</b> ' .' '.$user_name	
							
//readonly="readonly"							
						?>						
						
							
							<label class="col-lg-1 control-label">Date </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="dated" name="dated"  placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
							</div>
					<?php
						$company_id = $row['company_id'];
						$sqlb = '';
						if($status !='Draft'){
							$sqlb = " AND comp_id = '$company_id' ";
						}	
					?>	
							<label class="col-lg-1 control-label">Company </label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" id="company_Id"  readonly="readonly" required >
                             		<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "select * from company where 1 $sqlb order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
					<?php 
							
							$user   = $_SESSION['user'];
							$emp_id 		= $row['emp_id'];
							
									$sql = "select * from sma_user where id = '$emp_id' ";
							//echo $sql;		
										$res1 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res1);
										$emp_id 		= $r1['id'];
										$roll_no 		= $r1['roll_no'];
										$user_category 	= $r1['user_category'];
										$role			= $r1['role'];
										$department		= $r1['department'];
										$designation	= $r1['designation'];
										$level_id		= $r1['level_id'];
										$emp_name		= $r1['username'];
								
									if ($user_category=='H'){
										$user_category = 'Head Office';
									}
									
									else if ($user_category=='S'){
										$user_category = 'Site Office';
									}
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
									
									$sql="SELECT * from sma_level where id = '$level_id' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
									$level_id 		= $r1['level_name'];	
									
							?>
							
						
						<div class="form-group"> 
											
							
							<label class="col-lg-1 control-label">Name</label>			
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
								
								$sql = "select * from sma_user where 1 and id = '$onbehalf_emp_id' ";
								$q2  = mysqli_query($con, $sql);
								$r2  = mysqli_fetch_array($q2);
								$company_work = $r2['company_work'];
											
							?>	
							
							<label class="col-lg-2 control-label">On Behalf of</label>
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
							
							<input type="hidden" id="company_work" value="<?= $company_work; ?>">
							
						<div class="form-group"> 
							<label class="col-lg-1 control-label">Department</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $department;?>">  
							</div> 
							<label class="col-lg-1 control-label">Designation</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $designation;?>">  
							</div> 
							<label class="col-lg-1 control-label">Level</label> 		
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $level_id;?>">  
							</div>
							
							<label for="location" class="control-label col-sm-1">Location</label>
							<div class="col-sm-2">
									<select class="form-control" name="location" id="location" required <?php echo $readonly; ?> >
									<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "select * from sma_location where loc_comp_id = '$company_id' order by loc_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
										<?php } ?>
									</select>	
                            </div>
							
						</div> 
						
						<?php 
								$selected = '';
								$trans_type  = $row['trans_type'];
						 		$sql = "SELECT b.*, a.doa_desc, a.mobile_reimbure_flag FROM `sma_workflow` a, sma_workflow_type b where b.id = a.trans_Type and company_id = '$company_id' and b.status = 'Y' and a.doc_Type = 'RE' ";
								$q2 	= mysqli_query($con, $sql);
								$affectrow = mysqli_affected_rows($con);
								$selected = 'SELECTED';
								
										
						?>				
						<?php
						$trans_type = $row['trans_type'];
						$sqlb = '';
						if($status !='Draft'){
							$sqlb = " AND b.id = '$trans_type' ";
						}
						
						$doa_desc	= '';
						$mobile_value =0;
						$mobile_reimbure_flag='';
						$mobile_reimburse_limit =0;
						if($affectrow>0){
							
								$sql = $sql . " AND b.id = '$trans_type' " ;
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							
							$doa_desc	= $r2['doa_desc'];
 							$mobile_reimbure_flag	= $r2['mobile_reimbure_flag'];
						}
						
						if($mobile_reimbure_flag =='Y'){
							
							$sql = "SELECT * from sma_user where 1 and id = '$onbehalf_emp_id' ";	
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
			 				$mobile_reimburse_limit	= $r2['mobile_reimburse_limit'];
							if(empty($mobile_reimburse_limit)){
								$mobile_reimburse_limit = 0;	
							}	
							$sql = "SELECT sum(b.amount) as mobile_value  from sma_travel_expenses a, sma_expenses b 
										where 1 and a.exp_type = 'R' and a.id = b.approval_ref_no and a.onbehalf_emp_id = '$onbehalf_emp_id' 
											and year(b.dated) = year('$dated_v') and month(b.dated) = month('$dated_v'); ";	
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$mobile_value	= $r2['mobile_value'];
							if(empty($mobile_value)){
								$mobile_value = 0;	
							}	
								
						}	
						//echo $sql ."<BR>";
					?>
						<div class="form-group">
							
							<input type="hidden" id="mobile_reimbure_flag" name="mobile_reimbure_flag" value="<?= $mobile_reimbure_flag;?>">
						
							<input type="hidden" id="MOBILE_VALUE" name="mobile_value" value="">
						
							<input type="hidden" id="mobile_reimburse_limit" name="mobile_reimburse_limit" value="<?= $mobile_reimburse_limit;?>">
							
							<div class="col-sm-5">
								<label for="company_id" class="control-label ">Workflow Type *</label>
								<select class="form-control select3" name="trans_type" id="trans_TYPE" required <?php echo $readonly; ?> >
                             		<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "SELECT b.* FROM `sma_workflow` a, sma_workflow_type b where b.id = a.trans_Type and company_id = '$company_id' and b.status = 'Y' and a.doc_Type = 'RE' $sqlb ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							</div>	
						
							
							<div class="col-md-7">
								<label class=" control-label">Remarks</label>
								<textarea class="form-control" rows="2" name="remarkss" required autocomplete="off" <?php echo $readonly; ?> ><?php echo $row['remarks']?></textarea>
							</div>
							
							<div class="col-md-1">
								<input type="text" class="form-control" style="color:red;" readonly value="<?php echo $paid_status;?>">
							</div>
							
						</div>
						
						<input type="hidden" name="total_amount" id="total_amount"  value="<?php echo $row['total_amount'] ?>">
						
						<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>&nbsp;&nbsp; Expenses <span class="caret"></span></a></h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse in">
							
								<div class="panel-body">
								<?php if(!$readonly){ ?>	
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
												<th> Expense Type</th>
												<th> Cost Center</th>
												<th> Invoice No.</th>
												<th> Date</th>
												<th style="text-align:right;"> Amount</th>
												<th> Remarks</th>
												
												<th style="text-align:right;"> Action</th>
										 </tr>
										
									<tbody>
									<?php
										$j = 0;
										$modulePath1 = "travel_approval/";
										$sql="SELECT * from sma_expenses where approval_ref_no = '$approval_ref_no' and exp_type = 'R' ";
									
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($row1 = mysqli_fetch_array($result)){
											$j = $j + 1;
											
											
											$reference = $row1['reference'];
											$sql="SELECT * from sma_product where id = '$reference'";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$reference = $r2['name'];
											
											$budget_id 	= $row1['budget_id'];
											
											$sql="SELECT * FROM sma_budget where id = '$budget_id' ";
											$res2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$cat = mysqli_fetch_array($res2);
											$costcenter_name = $cat['budget_head'];
											
											$sql="SELECT * FROM sma_budget_subgroup where id = '$costcenter_name' ";
											$res2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$cat = mysqli_fetch_array($res2);
											$costcenter_name = $cat['budget_head'];
											
											$stly = '';
											if(empty($budget_id)){ 
												$costcenter_name = "<b>Cost Center not Added, Please Add !</b>";
												$stly = "style='Color:red;' ";
											}
											
											$tot_amount += $row1['amount'];

									?>
										<tr>
											<td width="2%"><?php echo $j;?></td>
											<td width="25%"><?php echo $reference;?></td>
											<td width="20%" <?= $stly; ?>><?php echo $costcenter_name;?></td>
											<td width="10%"><?php echo $row1['invoice_no'];?></td>
											<td width="10%"><?php echo date('d-m-Y', strtotime($row1['dated']));?></td>
											<td width="10%" style="text-align:right;"><?php echo number_format($row1['amount'],2);?></td>
											<td width="20%"><?php echo $row1['note'];?></td>
											
											<td width="08%" style="text-align:right;">
										<?php  $viewonly. ' <<>> ' . $readonly;
											//	if ( $viewonly!='Y' && empty($readonly) ){ ?>	
											<?php $rid = $row1['id']; ?>
											<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_reg_func.php"; ?>
<!-- Modal Edit Item-->						
												<!--<a href="regular_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
												<a href="delete_expenses.php?sub=delete&id=<?php echo $row1['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>&exp_type=R" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
										<?php //} ?>		
											</td>
											</td>
										</tr>

										<?php }?>
									</tbody> 
										<?php $tot_amount = $tot_amount  ?>
										<tr> <th colspan="5" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo number_format($tot_amount,2); ?> </th><td colspan="3"></td></tr>
										
									</table> 
									
									<input type="hidden" id="total_amounT"  value="<?php echo $tot_amount;?>" >
								
								
								</span>
							</div>	
						</div>
						
						</div>
						
				    </div>
								
				</div>

			  </div>
			  
				<?php 	//echo $tally_status . ' <<>> ' .$accountant_role;
								$disabled = '';
					if ($accountant_role=='M' || $accountant_role=='Y' ){
						$disabled = "";
					}						
					if($tally_status=='C' || $tally_status == 'U'){
						$disabled = "DISABLED";									
					}	
					if(!empty($py_id)){
						$disabled = "DISABLED"; 
					}				

				?>
			
			<?php 
				if($accountant_role=='Y' ){ // && $status =='Completed' ?>	
						
					<div class="panel panel-default">
                            <div class="panel-heading">
								
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepThree" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>Tally Journal<span class="caret"></span></a></h4>
                            </div>
                            <div id="stepThree" class="panel-collapse collapse in <?= $_GET['IN'];?>">
								<div class="panel-body">
								<div class="box123">
                                    <div class="box-header">
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
									<?php  } ?>		
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
													<th width="20%">Account Name</th>
													<th width="20%">Cost Center Name</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;" >Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = 'RE' order by record_id ";	
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
											$budget_head  		= $r2['budget_head'];
											$amount		   		= $r2['amount'];
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally  		= $r2['status'];
											
											
											$sql = "select * from sma_budget_subgroup where id = '$budget_head' ";	
//echo $sql;										
											$q22 	= mysqli_query($con, $sql);
											$r22 	= mysqli_fetch_array($q22);
											$budget_head		  	= $r22['budget_head'];
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $budget_head ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
										<?php //if (empty($disabled)){ ?>		
										<!--			<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' 
													<i class='fa fa-edit'></i></a>&nbsp;&nbsp;
													<?php // include "edit_tally_func.php"; ?>	
													<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>&amount=<?php echo $amount;?>&doc_no=<?php echo $doc_no ?>&doc_type=RE" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
										-->			
										<?php // } ?>
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
										$terr   = '';
										if($amount_dr != $amount_cr){
											$terr = 'Y';	
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
										}	
									?>
											<tr>
												<td></td>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
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
									
									//echo $status . ' <<>> ' . $tally_status;
									
									?>
									
									<?php if ( $status == 'Completed' ){ ?>
										<div class="col-sm-3">
											
										<?php
										if($tally_status == 'R'){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Created : </label><br>';
										}
										else if($tally_status == 'U'  ){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Synched :</label><br>';
										}
										else if($tally_status == 'C' ){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Checked :</label><br>';
										}
										else if($tally_status == 'N' ){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync :</label><br>';
										}
										
										if($tally_status=='R' && ( $accountant_role=='M' || $accountant_role=='Y' ) && empty($emsg)){
											if ( $terr!='Y' ){
										?>
										    <label for="tally_status" style="position: relative;top: -4px;" class="control-label">Sync to Tally? &nbsp;&nbsp; </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($status_tally == 'C' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="C" >
										<?php 
											} 
										}

										if( ($tally_status=='C' || $tally_status=='R' ) && $tally_access=='Y' && $status !='Completed'){ 
										?>
										    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" >
											
										<?php 
											}
										?>
									
										</div>
									<?php } ?>
									
										<div class="col-sm-2">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
										</div>
										
										<div class="col-sm-1">
											<label for="tally_narration" class="control-label">Narration: </label>
										</div>	
										<div class="col-sm-6">	
											<input type="text" class="form-control"  name="tally_narration" id="tally_narration" value="<?php echo $row['tally_narration'];?>" 
											onBlur="saveToDatabase(this.value,'narration','<?php echo $re_id; ?>')"
											onClick="showEdit(this);" >
										</div>
										
									</div>

										</div>
						
									</div>
								</div>
								
								</div>
							</div>
					</div>

			<?php } ?>

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
							
			<div class="tab-pane <?php echo $active;?>" id="tab_2">
			                <!-- Attachments -->
						<div class="box-header">	
							<p><?= $label_line; ?></p>
						</div>
							 <?php
							$sql = "SELECT * FROM file_uploads WHERE module = 'RE' AND reference_id = " . $re_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%">Document Type</th>
                                          <th width="30%">Description</th>
										  <th width="40%">Document Name</th>
                                          
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
											  
											  <td  width="30%"><?php echo $docRow['doc_desc'] ?></td>
											  
                                              <td  width="40%"><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											
											  <?php //if(!$readonly){ ?>
													<td  width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
											  <?php //} ?>  	
                                          
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
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2"  placeholder="Enter document description..."></textarea>
										</td>
										
										<td width="40%">
											<input type="file" name="fudoc[]" <?php echo $readonly; ?> class="docfile">
										</td>
										
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 
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
$sql="select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type = 'R' ";
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
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

					<?php //if( $userid == $approver_1 && $approver_1_status=='Submitted'){ ?>		
							<div class="box-footer">
								<div class="col-sm-6">
									<?php echo "";?>
								</div>
								<div class="col-sm-6 ">
									<?php echo "<span style='color:red;font-weight:bold;'>".$doa_desc."!!!</span>"; ?>
								</div>	
							</div>
					<?php //} ?>		
					
					<?php if($del !='Y'){ ?>		
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
							//echo $status. ">><";
							//if ( $viewonly!='Y'){
								if ( $status == 'Draft' || $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."regular_expense.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } 
								
							?>
							
							
									<?php 
																
									if($del=='Y'){ ?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
									<?php
										}
									else if ( $approval_status=='Rejected' || $approval_status=='Submitted' || $status=='Submitted' ){	
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
							<?php	
									}
								//}	
							?>	
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php		
										$role		= $_SESSION['role'];
										$user   	= $_SESSION['user'];
										$userid   	= $_SESSION['usrid'];
										$send_to   	= $row['send_to'];
										$level_1   	= $row['level_1'];
										$baseurl1 = $baseurl.$modulePath."regular_expense.php?sub=list";
										
										$approver_flag='';
						//echo $approver_2. ' >><< ' . $approver_2_status. ' <<>> ' . $userid. "<BR>";
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
										else if($userid==$approver_4){
											$mode_status = 'Approve';
										}
										
									}
									
								
//echo $role. ' ' .$current_approver. ' <<>> ' . $userid. ' ## => ##' . $level_1. ' => ' . $rw['level_3']. ' ' . $status;
//echo $status . ' ' . $approver_flag . "<BR>";

									if ( $status == 'Submitted' && $approver_flag=='Y' ){
										?>
										<span class='approve_btn'>
											<span>&nbsp;&nbsp;</span>
											<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Approve </a>
											<a href="#rejectAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#rejectAuthority">Reject </a>
										</span>
										
									<?php }
									
									if ( $status == 'Draft' ){
									 // if ( $viewonly!='Y'){ ?>
									
										<span class='hidesend'>	
											<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										</span>	
									
								<?php //}
								}
					
								//if ( $viewonly!='Y'){ ?>
									<span class='approve_btn'>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
									</span>	
								<?php //}
									 ?>
									
								<a href="<?php echo $baseurl1.$modulePath .'regular_expense.php?sub=list' .'&same_page='.$page;?>" class="btn btn-default" >Back</a>
								
								</div>
								
							</div>

					<?php } ?>
					
					
					<span id="getapprover">	
					
					<?php 
						if( $status == 'Draft' || $status == 'Submitted'){
						?>
						
							<div class="box-footer">
								
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
									<?php echo 'Role :'. $rolenm."<BR>";?>
									    <?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										$rw = mysqli_fetch_array($rs);
										echo 'Name :'. $rw['username'];
										?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_2 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm = $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><br>
									<?php echo 'Role :'. $rolenm."<BR>";?>
									<?php
										$sql = " select * from sma_user where id = $approver_2 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$rw = mysqli_fetch_array($rs);
										echo 'Name :'. $rw['username'];
									?>	
								</div>
							<?php }
							
								if(!empty($approver_3)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_3 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm = $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><br>
									<?php echo 'Role :'. $rolenm."<BR>";?>
									    <?php
										$sql = " select * from sma_user where id = $approver_3 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$rw = mysqli_fetch_array($rs);
										echo 'Name :'. $rw['username'];
									?>		
								</div>
							<?php } 
								if(!empty($approver_4)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_4 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm = $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><br>
									<?php echo 'Role :'. $rolenm."<BR>";?>
									    <?php
										$sql = " select * from sma_user where id = $approver_4 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$rw = mysqli_fetch_array($rs);
										echo 'Name :'. $rw['username'];
										?>
                                    
								</div>
							<?php } 
							
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
									<?= $approver_5_role . " <BR> " . $approver_5_name ; ?>
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
									<?= $approver_6_role . " <BR> " . $approver_6_name ; ?>
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
									<?= $approver_7_role . " <BR> " . $approver_7_name ; ?>
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
							
							
					<?php } ?>		
						
					</span>
					
					
						</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								<p><?= $label_line; ?></p>
								<?php 
									
									$srno = $re_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RE' order by id ";
									
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
								   
										<table id="prtable123" class="table table-bordered table-striped">
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RE' order by id  desc ";
										//echo $s1;
											$res  = mysqli_query($con, $s1);
											//echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												
												$create_by			= $r1['create_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$approved_dt		= date('d-m-Y', strtotime($r1['approved_date']));
												if($approved_dt=='01-01-1970' || $approved_dt =='31-12-1969'){
													$approved_date ='';
												}
												$remarks 			= $r1['remarks'];
												
												if($approver_1 == $create_by ){
													$status				= 'Verified';
												}
												
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
					
				$doc_type = 'RE';
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

<div  class="modal fade" id="addExpenses" role="dialog" aria-labelledby="addExpenses" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addExpenses">Expenses </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <form id="myForm" class="form-horizontal"  method="post" enctype="multipart/form-data" >
                                        
										<?php   
										
											$re_id 	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role		= $_SESSION['role']; //Maker
											$user_category = $_SESSION['user_category'];
											
										?>
										<input type="hidden" name="re_id" id="re_idE" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										<input type="hidden" id="advance_amounT" name="advance_amount" value='<?php echo $advance_amount ?>'>

									<?php 
										
									//echo	$vertical_type = $_SESSION['vertical_type'];
										
									?>
									<div class="form-group">
										
										<div class="col-md-6">
											<label class="control-label">Expense Type *</label>
										
											<select class="form-control" name="expence_name" id="expence_Name" autocomplete="off" required onchange="getcatbudget(this.value)"; >
												<option value=""> Select </option>
												<?php 
													
													$sql = "SELECT a.* from sma_product a, sma_budget_subgroup b where b.id = a.budget_head and exp_flag = 'Y' order by name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['name'] ?> </option>	
												<?php } ?>
											</select>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">Invoice No.<span class="required-mark">*</span></label>
											<input type="text" class="form-control" name="invoice_no"  autocomplete="off" id="Invoice_nm" value="" >
										</div>
										
									</div>

									<div class="form-group">
										<div class="col-sm-12">
											<span id="getcatbudgetB">
												
											</span>
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-3">
											<label for="approver" class="control-label">On Date</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value=<?= date('d-m-Y'); ?> >
											</div>
										</div>
										
										<div class="col-md-2">
											<label for="approver" class="control-label">Amount*</label>
											<input type="text" class="form-control" style="text-align:right;" required autocomplete="off" name="amount" id="Amount" value="" onblur="checklimit(this.value);" >
										</div>
												
										<div class="col-md-7">
											<label for="approver" class="control-label">Remarks</label>
											<textarea rows="1" class="form-control" name="remarks" id="Remarks" ></textarea>
										</div>
										
									</div>

									<div style="color:red;font-weight:bold;" id="showmsg"> </div>
									
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
											//$tot_amount
											$user_category = $_SESSION['user_category'];
											$company_id_v		= $company_id;
										?>
										
									<input type="hidden" name="re_id" id="re_idC" value="<?php echo $re_id; ?>" >
									<input type="hidden" id="modeC" name="mode" value='Checker'>
										
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
										<input type="hidden" name="re_id" id="re_idDR" value="<?php echo $re_id; ?>" >
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


<!--Add Line Popup-->
<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine" data-keyboard="false" data-backdrop="static">
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
										
									<!--	<div class="form-group">
											<div class="col-sm-4">
												<label for="approver" class="control-label">GST</label>
												
													<input type="text" class="form-control" id="amount_CGST" autocomplete="off" style="text-align:right;;" name="amount_Cgst" value="" >
										    </div>
										</div>
									-->	
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
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
														$sql = "SELECT * FROM account_mst where 1 and  account_type = 'D' order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'].' - '.$r3['percentage'].'%'; ?></option>
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
												<label for="approver" class="control-label">Narration </label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA" ></textarea>
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
                        <form class="form-horizontal" action="regular_exp_export_func.php?sub=pdf&gtype=R" target="_blank" method="POST" >
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
									<label for="itemCategory" class="control-label"> Company *</label>
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="40%"><input type="file" name="fudoc[]" <?php echo $readonly; ?> class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>  </td></tr>');  
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
                     //alert(data);  
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

function checklimit(id){
	
    var sub = 'sub40';
	var mobile_reimbure_flag = $("#mobile_reimbure_flag").val();
	var onbehalf_emp_id = $("#onbehalf_emp_id").val();
	var dated			= $("#Dated").val();
	var amount			= parseFloat(id);
//alert(id);
	if(mobile_reimbure_flag=='Y' && amount >0 ){
			var strURL = "app_func.php";
				$.post(strURL,{ onbehalf_emp_id:onbehalf_emp_id,
								dated:dated,
								sub40:sub},
								function(result){
						//alert(result);
							var str_array = result.split('##');	
							var mobile_value = str_array['0'];
							//alert(mobile_value);
							if (mobile_value.match(/[^$,.\d]/)){
								mobile_value = 0;
							}
							
							$('#MOBILE_VALUE').val(mobile_value);
							
							var cur_month_value = parseFloat(mobile_value) + parseFloat(amount);
							var mobile_reimburse_limit	= $("#mobile_reimburse_limit").val();
						//alert(mobile_value + ' <<>> ##2cur_month_value => ' + cur_month_value + ' ' + mobile_reimburse_limit);
						if(cur_month_value > mobile_reimburse_limit){
							alert('Mobile reimbursement limit exceeds for this months ##3!!!');
							return false;	
						}

				});		
	}
	
}
	
    $("#saveExp").on("click", function(e){
        var sub = 'sub100';
				
//		alert(sub + ' ' + mode);		 
	    var status 			=  $("#statuS").val();
		var approval_ref_no = $("#approval_ref_No").val();
		var expence_name		= $("#expence_Name").val();
		var dated			= $("#Dated").val();
		var amount			= $("#Amount").val();
		var invoice_nm		= $("#Invoice_nm").val();
		var remarks			= $("#Remarks").val();
		var exp_type		= 'R';
		var mobile_reimbure_flag = $("#mobile_reimbure_flag").val();
		var onbehalf_emp_id = $("#onbehalf_emp_id").val();
//alert(amount);
		if(amount==0){
			alert("Please enter amount...");
			return true;
		}

		if(mobile_reimbure_flag=='Y' ){
				mobile_value = 0;
				var strURL = "app_func.php";
				$.post(strURL,{ onbehalf_emp_id:onbehalf_emp_id,
								dated:dated,
								sub40:sub},
								function(result){
						//alert(result);
							var str_array = result.split('##');	
							var mobile_value = str_array['0'];
							//alert(mobile_value+' ####');
							if (mobile_value.match(/[^$,.\d]/)){
				//		alert("Error");
								mobile_value = 0;
							}
				});
			
				var cur_month_value = parseFloat(mobile_value) + parseFloat(amount);
				var mobile_reimburse_limit	= $("#mobile_reimburse_limit").val();
			//	alert('##1cur_month_value= > '+cur_month_value + ' mobile_reimburse_limit=> ' + mobile_reimburse_limit);
									
				if(cur_month_value > mobile_reimburse_limit){
					alert('Mobile reimbursement limit exceeds for this months ##1!!!');
					return false;	
				}
		}
						
		var gst_flag		= $('input[name=gst_flag]:checked', '#myForm').val();
		
        var company_id   =  $("#company_id_a").val();
		var budget_name  =  $("#budget_Name").val();
		var budget_head  =  $("#budget_Head").val();
		var total_budget  =  $("#total_Budget").val();
		var balance_budget  =  $("#balance_Budget").val();
		//var budget_id  		=  $("#budget_id").val();
		var budget_id  		=  $("#BUDGET_ID").val();

		var comp_id   		=  $("#company_ID").val();
		var emp_id		   	=  $("#emp_ID").val();
		var dated_h			=  $("#dp1").val();
		
		var costcenter_group  		=  $("#costcenter_GROUP").val();

//alert(total_budget + ' ' + balance_budget);		
//return true;

//alert( comp_id + ' <<>> '  + budget_id + ' <<>> ' + budget_name );
//	alert( comp_id + budget_id );
//return false;
		if (invoice_nm=='' ){
			 alert("Please enter Invoice Number...");
			return true;
		}
		
		if(amount==0){
			alert("Please enter amount...");
			return true;
		}	
		
		if(!budget_id){
			 alert("Error : Budget not available for Expense !!!");
			return true;
		}
		
		 if ( budget_name == null || budget_head == null  || budget_id == null  ) { 
			alert("Please select cost center group / name...");
			return true;
		}

	/*	if (typeof  costcenter_group === 'undefined') { 
			alert("Please select cost center group / head...");
			return true;
		 
		} */
		
		if(balance_budget<=0){
			alert("Budget id not enough...");
			var shoid = 'Budget is not enough !!!';
			$("#showmsg").text(shoid);
			return true;
		 
		}	

		
		$('#addExpenses').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ approval_ref_no:approval_ref_no,
						expence_name:expence_name,
						dated:dated,
						amount:amount,
						exp_type:exp_type,
						invoice_nm:invoice_nm,
						gst_flag:gst_flag,
						company_id:company_id,
						budget_name:budget_name,
						budget_head:budget_head,
						total_budget:total_budget,
						balance_budget:balance_budget,
						budget_id:budget_id,
						comp_id:comp_id,
						emp_id:emp_id,
						dated_h:dated_h,
						remarks:remarks,
						sub100:sub},
						function(result){
		      $('#te_exp_edit').html(result);
		});
		
	});


 
   $("#submitChecker").on("click", function(e){
        var sub = 'sub11';
		var mode		 	=  $("#modeC").val();
		
		var re_id		 	=  $("#re_idC").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		var approver		=  $("#approverE").val();
		
		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
//	alert(approver + ' ' + re_id );		 

//		if(approver==''){
//			alert("User Name should select...");
//			return;
//		}
	
		$('#checkerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						approver:approver,
						sub11:sub},
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
        var sub = 'sub12';
		var mode		 	=  $("#modeP").val();
//		alert(sub + ' ' + mode);		 
		var re_id		 	=  $("#re_idP").val();
	    var status 			=  $("#statusP").val();
		
        var remarks			=  $("#remarksP").val();
		
		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub12:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub13';
		var mode		 	=  $("#modeR").val();
		
		var re_id		 	=  $("#re_idR").val();
		var status 			=  $("#statuS").val();
		var remarks			=  $("#remarksR").val();
		
		$('#predit').html('Process...wait');
	    $('.approve_btn').hide();
		
//alert(re_id + ' ' + sub);

		$('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub13:sub},
						function(result){
		      $('#predit').html(result);
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
  
	
function getempname(id){
    var sub = 'sub4';
//alert(id);
	var strURL = "ta_func.php";
	$.post(strURL,{ sub4:sub,id:id},function(result){
			  $('#getempname').html(result);
		});
}



	/* function getcatbudget(id){
		
		var sub    		= 'sub5';
alert('Hello...123' + id);
		
alert(sub + ' 23523424' + id );
		 
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getcatbudget').html(result);
		});
		
	}
	
		function getcatbudgetB(id){
		
		var sub    		= 'sub5';
//alert('Hello...');
		var company_id  = document.getElementById("company_Id").value;
		 
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub5:sub},function(result){
		      $('#getcatbudgetB').html(result);
		});
		
	}
	
	 */
	 
	$("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub1';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		var amount 		= $("#amountA").val();
		var narration 	= $("#narrationA").val();
		var re_id 	= $("#re_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert(re_id + account_name + ' ' + type_ac + ' ' + effect);
		var doc_type = 'RE';
		$('#addLine').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ type_ac:type_ac,doc_type:doc_type,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,re_id:re_id,sub1:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });


    $("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub2';
		var mode 	= $("#modeT").val();
		var re_id 	 =  $("#re_idT").val();		
//alert(re_id);
		var doc_type = 'RE';
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
		var doc_type = 'RE';
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub3:sub},function(result){
		      $('#getaccount').html(result);
		});

	}	  

	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#total_amounT").val();
		var re_id	 	= $("#re_idA").val();
		var exp_type 	= 'R';
		
		var amount_cgst = $("#amount_CGST").val();

//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,amount_dr:amount_dr,re_id:re_id,amount_cgst:amount_cgst,exp_type:exp_type,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}

	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
	}
		
	function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
	//	alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			var doc_type = 'RE';
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id+'&doc_type='+doc_type,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	}

	function getcostcenter(id){
		var sub    = 'sub1a';
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub1a:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcatbudget(id){
		
		var sub    		= 'sub5';
//alert('Hello...');		
		var company_id  = document.getElementById("company_Id").value;
		var product_id	= id;
//alert('Hello...' + company_id);			 
		var strURL = "ta_func.php";
		$.post(strURL,{product_id:product_id,company_id:company_id,sub5:sub},function(result){
		      $('#getcatbudgetB').html(result);
		});
		
	}
		 
   function clearfld(){
		
		$('#expence_Name').html('');
		$('#Invoice_nm').html('');
		$('#costcenter_group').html('');
		$('#Amount').html('');
		$('#Dated').html('');
		$('#spend_BY').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();

	}		 

	$("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var re_id		 	=  $("#re_idDR").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + te_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						doc_type:'RE',
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	function getcostcenterD(id){
		
        var sub    = 'sub1b';

//alert(sub);
		var strURL = "ta_func.php";
		$.post(strURL,{id:id,sub1b:sub},function(result){
		      $('.getcostcenterD').html(result);
		});
 
	}

	function getcatbudgetD(id){
		
		var sub    		= 'sub5';
//alert('Hello...');		
		var company_id  = document.getElementById("company_Id").value;
//alert('Hello...' + company_id);			 
		var strURL = "ta_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub5:sub},function(result){
		      $('.getcatbudgetD').html(result);
		});
		
	}
	
	function getapprover(){
		
		var sub 			= 'sub34';
//alert(sub );			
		//var company_id    	= document.getElementById("company_Id").value;
		var company_id    	= document.getElementById("company_work").value;
		
		var checker_value 	= $("#total_amounT").val();
		var trans_type		= $("#trans_TYPE").val();
		var	docs_type		= 'RE';
//alert(sub + ' ' + company_id + ' ' + checker_value);
		$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,docs_type:docs_type,trans_type:trans_type,sub34:sub},function(result){
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
		var doc_type = 'RE';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub38:sub},function(result){
		      $('#getworkflow').html(result);
		});

	}
	
	
</script>


</body>
</html>
