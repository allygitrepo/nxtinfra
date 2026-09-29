<?php

include("../header.php");
$modulePath = "budget/";

$pgname = "budget/budget_adjust_from_to.php";
include("../viewonly.php");

$comid  	= $_SESSION['comid'];
$userid   	= $_SESSION['usrid'];
		
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Transfer Entry
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget Transfer</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Budget Transfer List</h3>
			  
			  
			  <?php
				if ($_POST['comp_id'] || $_POST['account_year']  ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['account_year'] 	= $_POST['account_year'];
				}
				
				if ( $_SESSION['comp_id'] || $_SESSION['account_year'] ){
					$comp_id 			= $_SESSION['comp_id'];
					$account_year 		= $_SESSION['account_year'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['account_year'] = '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$account_year 	= $_SESSION['account_year'];
					
				}
				
			?>
				<form class="form-horizontal" action="budget_adjust_from_to.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-5">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
										<option value=""> All </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>	
								</div>
								
							<div class="col-md-2">
								<label class="control-label">Fin.Year</label>
								<select class="form-control" name="account_year" id="account_year"  >
									<option value=""> Select </option>
									<option value=""> All </option>
									<?php
									$sql="SELECT * FROM sma_financial_year where status = 'Y' order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($account_year == $r2['short_fy_code'])?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
									<?php } ?>
									
								</select>
							</div>
							
						<div class="col-xs-2">
                            
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="budget_adjust_from_to.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
						</div>
							
				</form>
				
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="budget_adjust_from_to_export.php?sub=pdf" class="btn btn-primary">Report</a>
					&nbsp;&nbsp;&nbsp;
				<?php if ( $viewonly!='Y'){ ?>	
					<a href="budget_adjust_from_to.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
				<?php } ?>	
			    </span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<td width="0%" style="display:none;">#</td>
			<th>SrNo.</th>
			<th>Date</th>
			<th>Fin Year</th>
			<th>Company</th>
			<th>From Budget  Group</th>
			<th>From Budget  Sub Group</th>
			<th>To Budget  Group</th>
			<th>To Budget  Sub Group</th>
			<th style="text-align:right;">Amount</th>
			<th>Status</th>
			<th>Decision</th>
<!--		<th style="text-align:right;">Action</th>-->

		</tr>
	</thead>

<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from budget_adjust_from_to where 1 and adjust_flag = 'T' and project in ($comid) ";
	
	if(!empty($comp_id)){
		$sql .= " and project = '$comp_id' ";
	}
	
	if(!empty($account_year)){
		$sql .= " and account_year = '$account_year' ";
	}
	
	$sql .= " order by id desc ";
	
	$_SESSION['sqlbd'] = $sql;
	
//echo $sql;
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$project = $row['project'];
		$sql = "select * from company where comp_id = '$project' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
										
		$budget_id_from = $row['budget_id_from'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		//$budget_head_from = $r2['budget_head'];
		$budget_name_from = $r2['name'];
	
		$budget_id_to = $row['budget_id_to'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		//$budget_head_to = $r2['budget_head'];
		$budget_name_to = $r2['name'];
		
		$budget_head_to = $row['budget_head_to'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_to = $r2['budget_head'];
		
		$budget_head_from = $row['budget_head_from'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_from' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_from = $r2['budget_head'];
	
		$account_year 	= $row['account_year'];
		
		$amount 		= $row['amount'];
	/* $approved_by 	= $row['approved_by'];
	
	$sql = " SELECT * from sma_user where id = '$approved_by' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$approved_by = $r2['username']; */
	
	$dated 			= date('d-m-Y', strtotime($row['dated']));
	
	$amount 		= $row['amount'];
	
	$baseurl1 = $baseurl.$modulePath1.'budget_adjust_from_to.php?sub=edit&id='.$row["id"];
	
	$i = $i +1;
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget_adjust_from_to.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%" style="display:none;"><?php echo $i;?></td>
		<td width="04%" style="text-align:right;"><?php echo $row['id'];?></td>
		<td width="09%"><?php echo $dated;?></td>
		<td width="09%"><?php echo $account_year;?></td>
		<td width="06%"><?php echo $comp_code;?></td>
		<td width="14%"><?php echo $budget_name_from;?></td>
		<td width="15%"><?php echo $budget_head_from;?></td>
		<td width="16%"><?php echo $budget_name_to;?></td>
		<td width="17%"><?php echo $budget_head_to;?></td>
		<td width="08%" style="text-align:right;"><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="08%" ><?php echo $row['status'];?></td>
		<td width="08%" ><?php echo $row['approval_status'];?></td>
		
<!--		<td width="5%" style="text-align:right;">
			<a href="budget_adjust_from_to.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		</td>
-->		
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

<?php } ?>

<?php  
	if($_GET['sub'] == 'delete'){
        $id = $_GET['id'];
		
			$sql	="Select * from budget_adjust_from_to where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$company_id 			= $row['project'];
			$fin_year	 			= $row['fin_year'];
			
			$budget_id_from = $row['budget_id_from'];
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
	//echo $sql. "<BR>";		
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			//$budget_head_from = $r2['budget_head'];
			$budget_name_from = $r2['name'];
		
			$budget_id_to = $row['budget_id_to'];
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
	//echo $sql. "<BR>";		
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			//$budget_head_to = $r2['budget_head'];
			$budget_name_to = $r2['name'];
			
			$pgname 		= "budget_adjust_from_to.php";
			include "../viewonly.php";
			$description 	= $budget_name_from.', '.$budget_name_to.', '.$fin_year;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from budget_adjust_from_to where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="budget_adjust_from_to.php?sub=list";</script>';
	}
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$budget_name_from	= $_POST['budget_name_from'];
			$budget_name_to		= $_POST['budget_name_to'];
			$budget_id_from		= $_POST['budget_id_from'];
			
			$budget_head_to		= $_POST['budget_head_to'];
			$budget_head_from	= $_POST['budget_head_from'];
			
			$budget_id_to		= $_POST['budget_id_to'];
			//$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$trans_type			= $_POST['trans_type'];
			$account_year		= $_POST['account_year'];
			$status 			= 'Draft';
			
			//$dated  	= date('d-m-Y', strtotime($dated));
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
			
			$sql = "SELECT * FROM sma_budget 
						WHERE 1 AND budget_name = '$budget_name_from' AND budget_head = '$budget_head_from' 
							AND project = '$project' AND account_year = '$account_year' "; 
//echo $sql. "<BR>";							
			$q2 	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$budget_id_from 	= $r2['id'];
			$budget_head_from 	= $r2['budget_head'];
			$total_budget 		= $r2['total_budget'];
			$blocked_budget 	= $r2['blocked_budget'];
			$used_budget 		= $r2['used_budget'];
			$adjustment_budget 	= $r2['adjustment_budget'];
			$bal_budget			= ( $total_budget + $adjustment_budget ) - ( $used_budget + $blocked_budget );
			
			if($bal_budget >= $amount){
				
				$sql = "SELECT * FROM sma_budget 
							WHERE 1 AND budget_name = '$budget_name_to' AND budget_head = '$budget_head_to' 
								AND project = '$project' AND account_year = '$account_year' ";
	//echo $sql. "<BR>";
				$q2 	= mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$budget_id_to 		= $r2['id'];
				$budget_head_to 	= $r2['budget_head'];
									
				$user = $_SESSION['user'];
				
				$sql = " INSERT INTO budget_adjust_from_to ( project, trans_type, budget_name_from, budget_name_to,   budget_id_from, budget_id_to, budget_head_from, budget_head_to, amount, dated, approved_by, remarks,status, draft_by, draft_dated, adjust_flag, account_year ) 
					Values( '$project', '$trans_type', '$budget_name_from', '$budget_name_to', '$budget_id_from', '$budget_id_to', '$budget_head_from', '$budget_head_to', '$amount', '$dated', '$approved_by', '$remarks', '$status', '$user', now(), 'T' , '$account_year' )";
					
	//echo $sql. "<BR>";
	//exit();
						
				$query=mysqli_query($con, $sql);
				$bd_id = mysqli_insert_id($con);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
										
				$sql = " INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status)
								values( 'BT', '$bd_id', '$userid', now(), '$status' )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			}
			else if($bal_budget < $amount){
				echo "<script>alert('Insufficient Budget Balance...')</script>";
			}	
			
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_name_from = $r2['name'];
		
			$budget_id_to = $row['budget_id_to'];
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_name_to = $r2['name'];
			
			$pgname 		= "budget_adjust_from_to.php";
			include "../viewonly.php";
			$description 	= $budget_name_from.', '.$budget_name_to.', '.$fin_year. ', '. $amount;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Budget successful added";
			echo "<script>window.location.href='budget_adjust_from_to.php?sub=edit&id=$bd_id';</script>";
			exit();
			
		}

?>
   <section class="content-header">
        <h1>
            Budget Transfer
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="budget_adjust_from_to.php?sub=add" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-3">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" readonly id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off" REQUIRED
                                               value="<?= date("d-m-Y");?>" <?php echo $readonly; ?> >
									<div class="input-group-addon">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
							</div>
						</div>
						
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company</label>
								<div class="col-sm-5">
									<select class="form-control " name="project" id="projecT" REQUIRED >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
								<label for="company_id" class="control-label col-sm-2">Workflow Type *</label>
								
								<div class="col-sm-3">
									<span id="getworkflowtype">		
										<select class="form-control select3" name="trans_type" id="trans_type" required >
											<option value=""> Select </option>
												<?php $sql = "select * from sma_workflow_type where doc_type = 'BD' and status = 'Y'  ";
												$q2 	= mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" ><?php echo $r2['workflow_type']. ' '. $r2['id'];?></option>
												<?php } ?>
										</select>		
									</span>	
								</div>
										
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Financial Year </label>
							<div class="col-md-2" class="input-append">
								<select class="form-control" name="account_year" id="account_year" required <?= $readonly; ?> >
									<option value="">Select</option>	
									<?php
									$sql="SELECT * FROM sma_financial_year order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ( $r2['status']=='Y' )?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Budget  Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_from" id="budget_name_from" onchange="getbudgethead(this.value)" REQUIRED >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
				<!--			<label class="col-lg-6 control-label123" style="color:red;">Budgets for operating expenses can only be transferred, but budgets for capex need to be approved according to policy</label>-->
						</div>
						
						<span id="getbudgethead">
							<div class="form-group">
								<label class="col-lg-2 control-label">From Budget  Sub Group</label>
								<div class="col-md-4">
									<input type="text" class="form-control" >
								</div>
							</div>
						</span>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">To Budget  Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_to" id="budget_name_to" onchange="getbudgetheadto(this.value)" REQUIRED >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<span id="getbudgetheadto">
							<div class="form-group">
								<label class="col-lg-2 control-label">To Budget  Sub Group</label>
								<div class="col-md-4">
									<input type="text" class="form-control" >
								</div>
							</div>
						</span>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Reason</label>
							<div class="col-md-10">
								<textarea rows="3" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="" ></textarea>
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Amount</label>
								<input type="hidden" class="form-control" autocomplete="off" id="amount_prev" name="amount_prev" value="<?php echo $row['amount'];?>" >
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" required value="" >
							</div>
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'budget_adjust_from_to.php?sub=list';?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$bd_id				= $_POST['id']; 
			
			$budget_name_from	= $_POST['budget_name_from'];
			$budget_name_to		= $_POST['budget_name_to'];
			$budget_head_from	= $_POST['budget_head_from'];
			$budget_head_to		= $_POST['budget_head_to'];
			//$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$trans_type			= $_POST['trans_type'];
			$account_year		= $_POST['account_year'];
			$amount_prev		= $_POST['amount_prev'];
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			$statuss			= $_POST['status'];
			$tally_status			= $_POST['tally_status'];
			
			$sql = "SELECT * FROM sma_budget 
						WHERE 1 AND budget_name = '$budget_name_from' AND budget_head = '$budget_head_from' 
							AND project = '$project' AND account_year = '$account_year' "; 
//echo $sql. "<BR>";							
			$q2 	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$budget_id_from 	= $r2['id'];
			$budget_head_from 	= $r2['budget_head'];
			$total_budget 		= $r2['total_budget'];
			$blocked_budget 	= $r2['blocked_budget'];
			$used_budget 		= $r2['used_budget'];
			$adjustment_budget 	= $r2['adjustment_budget'];
			$bal_budget			= ( $total_budget + $adjustment_budget ) - ( $used_budget + $blocked_budget );
			
			if($bal_budget >= $amount){
				
			$sql = "SELECT * FROM sma_budget 
						WHERE 1 AND budget_name = '$budget_name_to' AND budget_head = '$budget_head_to' 
							AND project = '$project' AND account_year = '$account_year' ";
//echo $sql. "<BR>";
			$q2 	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$budget_id_to 		= $r2['id'];
			$budget_head_to 	= $r2['budget_head'];
			
//echo $statuss. "<BR>";
			if(!empty($approver_1)){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
			}
			
//echo $status. "<BR>";			
			if($statuss=='Draft'){
				
				$sql = " update budget_adjust_from_to set project	= '$project',
					 trans_type			= '$trans_type',
					 budget_head_from	= '$budget_head_from',
					 budget_head_to		= '$budget_head_to',
					 budget_name_from	= '$budget_name_from',
					 budget_name_to		= '$budget_name_to',
					 dated 				= '$dated',
					 amount				= '$amount',
					 account_year		= '$account_year',
					 remarks			= '$remarks',
					 current_approver	= '$approver_1',
					 approver_1			= '$approver_1',
					 approver_2			= '$approver_2',
					 approver_3			= '$approver_3',
					 approver_4			= '$approver_4',
					 approver_5			= '$approver_5',
					 approver_6			= '$approver_6',
					 approver_7			= '$approver_7',
					 approver_8			= '$approver_8',
					 approver_1_status	= '$approver_1_status',
					 adjust_flag		= 'T',
					 date_uploaded		= now()		
				where id = '$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){ echo $error; exit(); }

			}
//echo $sql."<BR>";
//exit();
			
			
			}
			else if($bal_budget < $amount){
				echo "<script>alert('Insufficient Budget Balance...')</script>";
			}	
			
			
			if($tally_status=='R' || $tally_status=='C' || !empty($tally_status) ){

				$sql="update budget_adjust_from_to set tally_ticked_by = '$user', tally_status = '$tally_status' , tally_updated_on = now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}

//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set status = '$tally_status'  where doc_no = '$id' and doc_type = 'BT' ";
				$r2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE

			}
			
			if(!empty($approver_1)){
				$status				= 'Submitted';
				$sql = " update budget_adjust_from_to set status = '$status' where id = '$id' ";
				mysqli_query($con, $sql);
			}
			
					
//Document Attachment					
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$share_point_link = $_POST["share_point_link"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/bd/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
					VALUES('BT', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', '" .$share_point_link[$i] . "', " . $id . ", now() )";
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, "uploads/bd/" . $id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
//Document Attachment
			//Mail send to First Approver	
				$s="select * from sma_user where id='$approver_1' ";		
				$sql = mysqli_query($con, $s);
				$rowcount = mysqli_num_rows($sql);
				while($r = mysqli_fetch_object($sql)){
					$username 		= $r->userid;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$s="select * from sma_user where id='$userid' ";		
				$sql = mysqli_query($con, $s);
				$rowcount = mysqli_num_rows($sql);
				while($r = mysqli_fetch_object($sql)){
					//$username 		= $r->userid;
					//$user_email		= $r->email;
					$user_name_by		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'budget_adjust_from_to.php?sub=edit&id='.$bd_id;
				include "bd_mail.php";
				
			//Mail send to First Approver
			
//echo $sql."<BR>";
//exit();					
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
														
			if(!empty($approver_1)){
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
			values( 'BT', '$id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_name_from = $r2['name'];
		
			//$budget_id_to = $row['budget_id_to'];
			$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_name_to = $r2['name'];
			
			$pgname 		= "budget_adjust_from_to.php";
			include "../viewonly.php";
			$description 	= $budget_name_from.', '.$budget_name_to.', '.$account_year. ', '. $amount;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
						VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql; exit();

			echo '<script>window.location.href="budget_adjust_from_to.php?sub=list";</script>';
			exit();
			
		}
		
		$id = $_GET['id'];
		$bj_id = $id;
		$sql="Select * from budget_adjust_from_to where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$approved_by	 = $row['approved_by'];
		$adjust_type	 = $row['adjust_type'];
		$status			 = $row['status'];

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
			
		$adjust_flag		= $row['adjust_flag'];	
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
		$approver_5_status 	= $row['approver_5_status'];									
		$approver_6_status 	= $row['approver_6_status'];									
		$approver_7_status 	= $row['approver_7_status'];									
		$approver_8_status 	= $row['approver_8_status'];									
									
		$readonly = '';
		if( $approved_by=='Y' ){
			$readonly = "READONLY";
		}
		
		if ( $viewonly=='Y' ){
			$readonly = "READONLY";
		}
		
		if ( $status == 'Submitted' || $status == 'Completed' ){
			$readonly = "READONLY";
		}
		
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Budget Transfer
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Transfer</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="budget_adjust_from_to.php?sub=edit" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<input type="hidden" name="status" value="<?= $row['status'];?>" >
						
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="adjust_flag" id="adjust_flagA" value="<?php echo $adjust_flag;?>">
					  
						
						<?php
							$_SESSION['bd_id']  = $row['id'];
							$bd_id 				= $row['id'];
							
							$dated = date('d-m-Y', strtotime($row['dated']));
							if($dated == '01-01-1970'){
								$dated = '';
							}
						?>
				<ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Adjust Budget</a></li>
					<li><a href="#tab_4" data-toggle="tab" id="fourth_tab" >Tally JV</a></li>
					<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						
					<li><a href="#tab_2" data-toggle="tab" class="btn btn-info" id="two_tab" >Workflow History</a> </li>
                </ul>
					
					<div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-3">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" readonly id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off"
                                               value="<?php echo $dated ; ?>" <?php echo $readonly; ?> >
									<div class="input-group-addon">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
							</div>
						</div>
						
						<?php
							$company_id = $row['project'];
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$comp_vertical = $r2['comp_vertical'];
						?>
						
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company *</label>
								<div class="col-sm-5">
									<select class="form-control " name="project" id="projecT" <?= $readonly ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							<?php
							$sqla = "";	
							if(isset($readonly)){
								$trans_type = $row['trans_type'];	
								$sqla = " AND id = '$trans_type' ";
							}	
							
					//echo $sql = "select * from sma_workflow_type where doc_type = 'BD' and status = 'Y' ". $sqla ;		
							?>
								<label for="company_id" class="control-label col-sm-2">Workflow Type *</label>
								<div class="col-sm-3">
									<span id="getworkflowtype">		
										<select class="form-control select3" <?= $readonly; ?>  name="trans_type" id="trans_type" required >
										<?php 
											if(!isset($readonly)){
										?>		
											<option value=""> Select </option>
											<?php } ?>	
											<?php $sql = "select * from sma_workflow_type where doc_type = 'BD' and status = 'Y' ";
												$q2 	= mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?>><?php echo $r2['workflow_type'];?></option>
												<?php } ?>
										</select>		
									</span>	
								</div>
										
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Financial Year </label>
							<div class="col-md-2" class="input-append">
								<select class="form-control" name="account_year" id="account_year" required <?= $readonly; ?> >
									<option value="">Select</option>	
									<?php
									$sql="SELECT * FROM sma_financial_year order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($row['account_year'] == $r2['short_fy_code'])?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">From Budget  Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_from" id="budget_name_from" onchange="getbudgethead(this.value)" <?= $readonly ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name_from'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						<?php
							$company_id = $row['project'];
							$budget_name_from = $row['budget_name_from'];
							$budget_name_to   = $row['budget_name_to'];

							$budget_head_from = $row['budget_head_from'];
							$budget_head_to   = $row['budget_head_to'];
							
							$fin_year 		= $row['account_year'] ;
							$budget_id_from	 = $row['budget_id_from'];
						?>
						<span id="getbudgethead">
							<div class="form-group">
							<label class="col-lg-2 control-label">From Budget  Sub Group</label>
							<div class="col-md-3">
								<select class="form-control" name="budget_head_from" id="budget_head_from" <?= $readonly ?> >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_budget_subgroup where 1 and budget_name  = '$budget_name_from' order by budget_head ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head_from'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['budget_head'];?></option>
								<?php } ?>
								</select>
							</div>
							
				<!--			<label class="col-lg-5 control-label123" style="color:red;" >Budgets for operating expenses can only be transferred, but budgets for capex need to be approved according to policy</label>-->
							
								<?php		
								if($budget_id_from>0){
									$sql  	= "select * from sma_budget where 1 and id= '$budget_id_from' ";		
								}
								else {
									$sql  	= "select * from sma_budget where 1 and budget_name= '$budget_name_from' and budget_head= '$budget_head_from' and account_year = '$fin_year' ";	
								}
		//echo $sql."<BR>";						
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$budget_code 		= $r2['budget_code'];
									$total_budget 		= $r2['total_budget'];
									$used_budget 		= $r2['used_budget'];
									$blocked_budget 	= $r2['blocked_budget'];
									$adjustment_budget 	= $r2['adjustment_budget'];
									$bal_budget			= $total_budget + $adjustment_budget - ($used_budget + $blocked_budget) ;
									$total_budget 		+= $adjustment_budget;
							?>		
									<label class="col-lg-1 control-label">Total&nbsp;Budget</label>
									<div class="col-md-2">
									<input type="text" class="form-control" style="text-align:right;" readonly value="<?= number_format($total_budget,0);?>" >
									</div>
									
									<label class="col-lg-1 control-label">Balance&nbsp;Budget</label>
									<div class="col-md-2">
									<input type="text" class="form-control" style="text-align:right;" readonly value="<?= number_format($bal_budget,0);?>" >
									</div>	
				
				
							</div>
						</span>
						
							
						
						<div class="form-group">
							<label class="col-lg-2 control-label">To Budget  Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_to" id="budget_name_to" onchange="getbudgetheadto(this.value)" <?= $readonly ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name_to'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<span id="getbudgetheadto">
							<div class="form-group">
								<label class="col-lg-2 control-label">To Budget  Sub Group</label>
								<div class="col-md-3">
									<select class="form-control" name="budget_head_to" id="budget_head_to" <?= $readonly ?> >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_subgroup where 1 and budget_name = '$budget_name_to' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head_to'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['budget_head'];?></option>
									<?php } ?>
									</select>
								</div>
								
								<?php		
								if($budget_id_to>0){
									$sql  	= "select * from sma_budget where 1 and id= '$budget_id_to' ";		
								}
								else {
									$sql  	= "select * from sma_budget where 1 and project = '$company_id' and budget_name= '$budget_name_to' and budget_head= '$budget_head_to' and account_year = '$fin_year' ";	
								}
	//echo $sql."<BR>";						
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$budget_code 		= $r2['budget_code'];
									$total_budget 		= $r2['total_budget'];
									$used_budget 		= $r2['used_budget'];
									$blocked_budget 	= $r2['blocked_budget'];
									$adjustment_budget 	= $r2['adjustment_budget'];
									$bal_budget			= ($total_budget + $adjustment_budget) - ($used_budget + $blocked_budget) ;
									$total_budget 		+= $adjustment_budget;
							?>		
									<label class="col-lg-1 control-label">Total&nbsp;Budget</label>
									<div class="col-md-2">
									<input type="text" class="form-control" style="text-align:right;" readonly value="<?= number_format($total_budget,0);?>" >
									</div>
									
									<label class="col-lg-1 control-label">Balance&nbsp;Budget</label>
									<div class="col-md-2">
									<input type="text" class="form-control" style="text-align:right;" readonly value="<?= number_format($bal_budget,0);?>" >
									</div>	
				
				
				
							</div>
						</span>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<textarea rows="3" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;"  <?= $readonly ?> placeholder=""><?= $row['remarks'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
						
							<input type="hidden" id="amount_prev" name="amount_prev" value = "<?php echo $row['amount'];?>" >
														
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" required placeholder="" value="<?php echo $row['amount'];?>" <?= $readonly ?> >
							</div>
						</div>
						
						
					
					<?php //if($viewonly!='Y' ){ ?>		
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
							
							<?php if( $status == 'Draft' ){ ?>	
								<a href="<?php echo $baseurl."budget/budget_adjust_from_to.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } ?>
							
							</div>
							
							<?php $baseurl1 = $baseurl.$modulePath.'budget_adjust_from_to.php?sub=list';?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								
						<?php	
							  if ( $status == 'Draft' ){ ?>
								<span class='hidesend' >
								<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								</span>
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php }
						//}
						//else {
						?>
						<!--	<div class="box-footer">
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
									
								</div>
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<div class="col-sm-6 text-right">
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>-->
								
								
						<?php //}
						
						$userid   	= $_SESSION['usrid'];

						
//ECHO $userid.  ' ' .$approver_1 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' . $approver_flag."<BR>";
					
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
										&& $approver_8_status=='Submitted'){
										$approver_flag='Y';
									}
									
									$mode_status = 'Pending';
									if($userid==$approver_1 && empty($approver_2) && empty($approver_3) && empty($approver_4) ){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_2 && empty($approver_3) && empty($approver_4)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_3 && empty($approver_4)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_4){
										$mode_status = 'Approve';
									}
										
								}
								
									
								
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
								
							?>
							
								<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
								<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
						<?php 
								}
							}
						?>		
						
			<span id="predit"></span>
			
							</div>
						</div>
				
					<?php  
						//if( $status == 'Submitted' && $approval_status!='Rejected'){
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
									<?= $approver_1_name . " <BR> " . $approver_1_role;?>
									
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
									<?= $approver_2_name . " <BR> " . $approver_2_role;?>
									
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
									<?= $approver_3_name . " <BR> " . $approver_3_role;?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 4</label><BR>
									<?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php	if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 5</label><BR>
									<?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 6</label><BR>
									<?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_7)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_7' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_7_name = $rw['username'];
								$approver_7_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 7</label><BR>
									<?= $approver_7_name . " <BR> " . $approver_7_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_8)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_8' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_8_name = $rw['username'];
								$approver_8_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 8</label><BR>
									<?= $approver_8_name . " <BR> " . $approver_8_role; ?>
									
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
								
								
							</div>
						
						</span>
						<?php 
							}
						?>
						
					</div>
		
		
					<div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
								
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'BT' AND reference_id = " . $id;
//						echo $sql;
						
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%">Document Type</th>
                                          <th width="25%">Share Point Link</th>
                                          <th width="20%">Description</th>
											<th width="25%">File</th>
											<th width="10%"></th>
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoctr.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$rw1 = mysqli_fetch_array($rs);
													$document = $rw1['document'];
											
											  ?>
                                          <tr>
										      <td  width="20%"><?php echo $document; ?></td>
                                              
                                              <td  width="25%"><a target="_blank" href="<?php echo $docRow['share_point_link'] ?>"><?php echo $docRow['share_point_link'] ?></a></td>
											  <td  width="20%"><?php echo $docRow['doc_desc'] ?></td>
											  
											  <td  width="20%"><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  
                                          <?php //if (empty($readonly)){ ?>
													
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
										<td  width="20%">
                                            <select class="form-control  doctype" name="doctype[]" <?= $readonly123;?>  >
                                            <option value="">Select</option>
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
										<td  width="25%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td  width="20%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td  width="20%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                        <td  width="10%">
										 <?php 
											//if($status != 'Completed'){
										?>
											<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
										<?php //} ?>	
										</td> 
										
                                    </tr>  
                               </table>  
                           
							</div> 
					    
						</div>
						
					<div class="tab-pane <?php echo $active;?> " id="tab_2">
						
						<div class="modal-header" >
								
								<?php 
									
									$srno = $bd_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BT' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									if($create_date=='01-01-1970'){
										$create_date='';
									}
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BT' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

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
						
						
					<?php
							$status = $row['status'];
							$disabled = '';
							if( $tally_status=='R' || $tally_status == 'U' || $status =='Completed' ){
								
								$disabled = "DISABLED";
								//|| $status =='Submitted'
							}	
							if($user=='Admin'){
								$disabled = '';
							}	
					?>
						
						<div class="tab-pane <?php echo $active_tab2 ?> " id="tab_4">					
						
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
								?>			
                                       
                                    </div>
                                    <div class="box-body">
					
									<div id="tallyentry">
									
									<!-- Enter Here -->
								<?php if (empty($disabled)){ ?>	
								<!--		<span class="pull-right"><a href="#addLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addLine">Add </a></span>-->
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
													WHERE doc_no = '$bj_id' AND doc_type = 'BT' 
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
											
											$sql = "SELECT * from account_mst where id = '$account_id' || account_name = '$account_name' ";
											$qry2 	= mysqli_query($con, $sql);
											$r22 	= mysqli_fetch_array($qry2);
											$tds_flag = $r22['tds_flag'];
											if(empty($account_type)){
												$account_type  		= $r22['account_type'];
											}
											$gstwd = strpos($account_name, "GST");
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
											
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
								
									<?php //if ($tds_flag=='Y' || (!empty($gstwd) ) ){
										if ( empty($disabled) ){
										//&& $account_type=='D' 
										?>
										<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>&amount=<?php echo $amount;?>&doc_no=<?php echo $doc_no ?>&doc_type=CE&effect=<?= $effect;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
													
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
								
								<?php $tally_status = $row['tally_status']; ?>
								
									<?php //echo $status. ' >><< ' .$tally_status . ' <<<>>> ';
									
										echo "<span style='color:red;'> " . $error_account_name . "</span>";
									
										if ( $status == 'Completed' ){
										
									?>
										<div class="col-sm-3">
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
									if( ($tally_status=='C' || $tally_status=='R') && $tally_access=='Y' && $status !='Completed'){ 
									?>
										    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" >
											
									<?php 
										}
									?>
										
									</div>
									
									<?php  } ?>
									
									<?php 
										$chk_date = date('d-m-Y', strtotime($row['tally_updated_on']));
										if($chk_date=='01-01-1970' || $chk_date =='30-11--0001'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y  h:i:sa', strtotime($row['tally_updated_on']));
										}	
									?>
										<div class="col-sm-2">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $row['tally_ticked_by']; ?>
											</label>
										</div>
										
										<div class="col-sm-1">
											<label for="tally_narration" class="control-label">Narration: </label>
										</div>	
										<div class="col-sm-6">	
											<textarea rows="3" class="form-control"  name="tally_narration" id="tally_narration"  
											onBlur="saveToDatabase(this.value,'narration','<?php echo $bj_id; ?>')"
											onClick="showEdit(this);" ><?php echo $row['tally_narration'];?></textarea>
										</div>
										
									</div>

										</div>
						
									</div>
								</div>

							</div>
							
						
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

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
	
	
						
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>


<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

<script>  
 $(document).ready(function(){
      var i=1;  
      $('#add').click(function(){  
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype col-sm-1" name="doctype[]" ><option value="">Select</option>'+opt+'</select></td><td width="25%"><textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"><input type="file" name="fudoc[]" class="docfile"></td><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
										
							$bd_id 			= $_SESSION['bd_id'];
							$mode_status 	= $status;
							$role			= $_SESSION['role']; //Maker
							//$user_category = $_SESSION['user_category'];
							?>	
										
							<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
							<input type="hidden" id="modeE" name="mode" value='<?= $mode_status ?>' >
							<input type="hidden" id="bd_idE" name="bd_id" value="<?= $bd_id; ?>" >
							<input type="hidden" id="adjust_flagE" name="adjust_flag" value="<?= $adjust_flag; ?>" >
										
							<div class="form-group">
								<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
									<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
								</div>
							</div>
							
							</form>	
									
                        </div>
			
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
							<input type="hidden" id="bj_idT" value="<?php echo $_GET['id'];?>">
						
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
<!-- Modal Add Tally-->

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Reject Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$bd_id 	= $_SESSION['bd_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="bd_id" id="bd_idR" value="<?php echo $bd_id; ?>" >
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


<?php 	
	include("../footer.php");	
?>
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>


<script>

    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

	function getbudgethead(id){
		var sub    = 'sub3A';
		var strURL = "app_func.php";
		var company_id    	= document.getElementById("projecT").value;
		var dated    		= document.getElementById("prDate").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,dated:dated,company_id:company_id,sub3A:sub},function(result){
		      $('#getbudgethead').html(result);
		});
	}


	function getbudgetheadto(id){
		var sub    = 'sub4A';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("projecT").value;
		var dated    		= document.getElementById("prDate").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,dated:dated,company_id:company_id,sub4A:sub},function(result){
		      $('#getbudgetheadto').html(result);
		});
	}

	function getapprover(){
		
		var sub = 'sub5';
		var company_id    = document.getElementById("projecT").value;
		var adjust_flag		=  $("#adjust_flagA").val();
		var trans_type 		=  $("#trans_type").val();
//alert(sub + ' ' + company_id + ' ' + adjust_flag);
		$('.hidesend').hide();
		var strURL = "app_func.php";
		$.post(strURL,{adjust_flag:adjust_flag,trans_type:trans_type,company_id:company_id,sub5:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	
	$("#submitApprove").on("click", function(e){
		
        var sub 			= 'sub9';
		var mode		 	=  $("#modeE").val();		
//alert(sub + ' ' + mode);
		var company_id		=  $("#projecT").val();
		var bd_id		 	=  $("#bd_idE").val();
		var adjust_flag		=  $("#adjust_flagE").val();
	    var statusap		=  mode;
		var approver		=  $("#approverC").val();
		var remarks			=  $("#remarksA").val();
//alert(adjust_flag);		
//return;
//alert( company_id + ' ' + statusap + ' #1# ' + approver + ' #5# ' + bd_id + ' ' + remarks );
//return;

		$('#approvalAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ bd_id:bd_id,
						mode:mode,
						adjust_flag:adjust_flag,
						company_id:company_id,	
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		
		
	});

    $("#submitReject").on("click", function(e){
        var sub 			= 'sub8A';
		var mode		 	= $("#modeR").val();
//		alert(sub + ' ' + mode);		 
		var bd_id		 	= $("#bd_idR").val();		
		var company			= $("#projecT").val();
		var remarks			= $("#remarksR").val();

//alert(mode + ' +' #2# '+ company + ' #5# ' + bd_id );

		var strURL = "app_func.php";
		$.post(strURL,{ bd_id:bd_id,
						company_id:company,
						statusap:mode,
						remarks:remarks,
						sub8A:sub},
						function(result){
		      $('#predit').html(result);
		});

	    $('#rejectAuthority').modal('hide');
		
	});
		
			
		
	$("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub2';
		var mode 	= $("#modeT").val();
		var bj_id 	 =  $("#bj_idT").val();		

		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_p_func.php";
		$.post(strURL,{ mode:mode,bj_id:bj_id,sub2:sub},
			function(result){
		    $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });
	
</script>

</body>
</html>
