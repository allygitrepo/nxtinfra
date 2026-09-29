<?php

include("../header.php");
$modulePath = "budget/transfer_budget_adjust.php?sub=list";

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
        Transfer Budget Adjust
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Transfer Budget Adjust</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Budget Adjust List</h3>
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="budget_adjust_export.php?sub=pdf" class="btn btn-primary">Report</a>
					&nbsp;&nbsp;&nbsp;
					<a href="transfer_budget_adjust.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
			    </span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<td width="0%" style="display:none;">#</td>
			<th>Date</th>
			<th>Company</th>
			<th>From Cost Center Name</th>
			<th>From Cost Center Head</th>
			<th>To Cost Center Name</th>
			<th>To Cost Center Head</th>
			<th style="text-align:right;">Amount</th>
			<th>Status</th>
			<th>Decision</th>
<!--		<th style="text-align:right;">Action</th>-->

		</tr>
	</thead>

<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from budget_adjust where 1 and project in ($comid) order by id desc ";
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
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_from = $r2['budget_head'];
		$budget_name_from = $r2['name'];
	
		$budget_id_to = $row['budget_id_to'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_to = $r2['budget_head'];
		$budget_name_to = $r2['name'];
	
		$amount 		= $row['amount'];
	/* $approved_by 	= $row['approved_by'];
	
	$sql = " SELECT * from sma_user where id = '$approved_by' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$approved_by = $r2['username']; */
	
	$dated 			= date('d-m-Y', strtotime($row['dated']));
	
	$amount 		= $row['amount'];
	
	$baseurl1 = $baseurl.$modulePath1.'transfer_budget_adjust.php?sub=edit&id='.$row["id"];
	
	$i = $i +1;
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "transfer_budget_adjust.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%" style="display:none;"><?php echo $i;?></td>
		<td width="09%"><?php echo $dated;?></td>
		<td width="06%"><?php echo $comp_code;?></td>
		<td width="15%"><?php echo $budget_name_from;?></td>
		<td width="15%"><?php echo $budget_head_from;?></td>
		<td width="15%"><?php echo $budget_name_to;?></td>
		<td width="15%"><?php echo $budget_head_to;?></td>
		<td width="08%" style="text-align:right;"><?php echo moneyFormatIndiaa($amount);?></td>
		<td width="08%" ><?php echo $row['status'];?></td>
		<td width="08%" ><?php echo $row['approval_status'];?></td>
		
<!--		<td width="5%" style="text-align:right;">
			<a href="transfer_budget_adjust.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
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
		$sql="delete from budget_adjust where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="transfer_budget_adjust.php?sub=list";</script>';
	}
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$budget_name_from	= $_POST['budget_name_from'];
			$budget_name_to		= $_POST['budget_name_to'];
			$budget_id_from		= $_POST['budget_id_from'];
			$budget_id_to		= $_POST['budget_id_to'];
			//$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$status 			= 'Draft';
			
			$user = $_SESSION['user'];
			
  			$sql = " INSERT INTO budget_adjust ( project, budget_name_from, budget_name_to,   budget_id_from, budget_id_to, amount, dated, approved_by, remarks,status, draft_by, draft_dated  ) 
				Values( '$project', '$budget_name_from', '$budget_name_to', '$budget_id_from', '$budget_id_to', '$amount', '$dated', '$approved_by', '$remarks', '$status', '$draft_by', now() )";
					
			$query=mysqli_query($con, $sql);
			$bd_id = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = " update sma_budget set total_budget = total_budget + $amount 
							where id = '$budget_id_to' ";
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
							
			$sql = " update sma_budget set total_budget = total_budget - $amount 
							where id = '$budget_id_from' ";
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
									
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status)
							values( 'BD', '$bd_id', '$userid', now(), '$status' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//echo "Budget successful added";
			echo '<script>window.location.href="transfer_budget_adjust.php?sub=list";</script>';
			
		}

?>
   <section class="content-header">
        <h1>
            Budget Adjust
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
            <form class="form-horizontal" action="transfer_budget_adjust.php?sub=add" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-3">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off"
                                               value="" <?php echo $readonly; ?> >
									<div class="input-group-addon">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
							</div>
						</div>
						
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company</label>
								<div class="col-sm-5">
									<select class="form-control " name="project" id="projecT"  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Cost Center Name</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_from" id="budget_name_from" onchange="getbudgethead(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<span id="getbudgethead">
							<div class="form-group">
								<label class="col-lg-2 control-label">From Cost Center Head</label>
								<div class="col-md-4">
									<input type="text" class="form-control" >
								</div>
							</div>
						</span>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">To Cost Center Name</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_to" id="budget_name_to" onchange="getbudgetheadto(this.value)" >
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
								<label class="col-lg-2 control-label">To Cost Center Head</label>
								<div class="col-md-4">
									<input type="text" class="form-control" >
								</div>
							</div>
						</span>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Reason</label>
							<div class="col-md-10">
								<input type="text" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="" >
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Amount</label>
								<input type="hidden" class="form-control" autocomplete="off" id="amount_prev" name="amount_prev" value="<?php echo $row['amount'];?>" >
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $row['amount'];?>" >
							</div>
						</div>
						
					<!--	<div class="form-group">
							<label class="col-lg-2 control-label">Approved By</label>
							<div class="col-md-4">
									<select class="form-control select2"  name="approved_by"   <?php echo $readonly; ?> >
										<option value="0">Select</option>
											<?php /* $sql = " SELECT * FROM sma_user order by username ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){  */?>
												<option value="<?php echo $r2['id'];?>" ><?php echo $r2['username'] ;?></option>
											<?php //} ?>
									</select>
							</div>
						</div>-->
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
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
			$id			= $_POST['id']; 
			
			$budget_name_from	= $_POST['budget_name_from'];
			$budget_name_to		= $_POST['budget_name_to'];
			$budget_id_from		= $_POST['budget_id_from'];
			//$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$budget_id_to		= $_POST['budget_id_to'];
			$amount_prev		= $_POST['amount_prev'];
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			
			if(!empty($approver_1)){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
			}
			/* if(!empty($approver_2)){
				$approver_2_status  = 'Submitted';
			}
			if(!empty($approver_3)){
				$approver_3_status  = 'Submitted';
			} 
			approver_2_status	= '$approver_2_status',
			approver_3_status	= '$approver_3_status',
			
			*/
			
  			$sql = " update budget_adjust set project	= '$project',
					 budget_id_from		= '$budget_id_from',
					 budget_id_to		= '$budget_id_to',
					 budget_name_from	= '$budget_name_from',
					 budget_name_to		= '$budget_name_to',
					 dated 				= '$dated',
					 amount				= '$amount',
					 remarks			= '$remarks',
					 status				= '$status',
					 approver_1			= '$approver_1',
					 approver_2			= '$approver_2',
					 approver_3			= '$approver_3',
					 approver_1_status	= '$approver_1_status',
					 date_uploaded		= now()		
				where id = '$id'";
//echo $sql."<BR>";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){ echo $error; exit(); }
					
			$sql = " update sma_budget set total_budget = total_budget + $amount - $amount_prev
							where id = '$budget_id_to' ";
//echo $sql."<BR>";							
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = " update sma_budget set total_budget = total_budget - $amount + $amount_prev
							where id = '$budget_id_from' ";
//echo $sql."<BR>";
//exit();					
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
														
			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
		values( 'BD', '$id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//echo $sql; exit();
			echo '<script>window.location.href="transfer_budget_adjust.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from budget_adjust where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$approved_by	 = $row['approved_by'];
		$adjust_type	 = $row['adjust_type'];
		$status			 = $row['status'];

		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
										
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];									
									
		$readonly='';
		if($approved_by=='Y'){
			$readonly = "READONLY";
		}
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Budget Adjust
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Adjust</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="transfer_budget_adjust.php?sub=edit" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<?php
							$_SESSION['bd_id']  = $row['id'];
							$bd_id 				= $row['id'];
							
							$dated = date('d-m-Y', strtotime($row['dated']));
							if($dated == '01-01-1970'){
								$dated = '';
							}
						?>
				<ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" > Adjust Budget </a></li>
                       
						<li><a href="#tab_2" data-toggle="tab" class="btn btn-info" id="two_tab" >Workflow History</a></li>
						
                </ul>
					
					<div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-3">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off"
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
							
								<label for="project" class="control-label col-sm-2">Company</label>
								<div class="col-sm-5">
									<select class="form-control " name="project" id="projecT" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
						</div>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">From Cost Center Name</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_from" id="budget_name_from" onchange="getbudgethead(this.value)" >
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

						?>
						<span id="getbudgethead">
							<div class="form-group">
							<label class="col-lg-2 control-label">From Cost Center Head</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_id_from" id="budget_id_from" >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_budget where 1 and budget_name  = '$budget_name_from' and project = '$company_id' order by budget_head ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_id_from'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['budget_head'];?></option>
								<?php } ?>
								</select>
							</div>
							</div>
						</span>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">To Cost Center Name</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name_to" id="budget_name_to" onchange="getbudgetheadto(this.value)" >
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
								<label class="col-lg-2 control-label">To Cost Center Head</label>
								<div class="col-md-4">
									<select class="form-control" name="budget_id_to" id="budget_id_to" >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_budget where 1 and budget_name  = '$budget_name_to' and project = '$company_id' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_id_to'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['budget_head'];?></option>
									<?php } ?>
									</select>
								</div>
							</div>
						</span>
						
						
						<div class="form-group">
						
							<input type="hidden" id="amount_prev" name="amount_prev" value = "<?php echo $row['amount'];?>" >
														
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $row['amount'];?>" >
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<input type="text" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="<?php echo $row['remarks'];?>" >
							</div>
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							<?php if( $status == 'Draft' ){ ?>	
								<a href="<?php echo $baseurl."budget/transfer_budget_adjust.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } ?>
							
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
						<?php  
							if( $status == 'Draft' ){
						?>	
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
						
								<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php }
							
							$userid   	= $_SESSION['usrid'];

//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' . $approver_flag."<BR>";
					
							if( $status != 'Draft' ){
									
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
									$userid == $approver_2 && $approver_2_status=='Submitted' || 
									$userid == $approver_3 && $approver_3_status=='Submitted' ){
				
									if($approver_1_status=='Submitted' && 
											empty($approver_2_status) && empty($approver_3_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
											&& empty($approver_3_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Submitted' ){
										$approver_flag='Y';
									}
									
									$mode_status = 'Pending';
									if($userid==$approver_1 && empty($approver_2) && empty($approver_3)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_2 && empty($approver_3)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_3){
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
						
							</div>
						</div>
				
		<span id="predit"></span>
				
					<?php  
						if( $status == 'Draft' ){
					?>	
						<span id="getapprover">
							<div class="box-footer">
								<div class="col-sm-3">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
								$approver_1 = $row['approver_1'];
								$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3'];

								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow 
											where 1 and doc_type = 'BD' and vertical_type = '$comp_vertical' ), role ) ";
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
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_2 FROM sma_workflow 
											where 1 and doc_type = 'BD' and vertical_type = '$comp_vertical' ), role ) ";
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
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_3 FROM sma_workflow 
											where 1 and doc_type = 'BD' and vertical_type = '$comp_vertical' ), role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } ?>	
								
								<BR>
								
							</div>
						
						
						</span>
						<?php 
							}
						?>
						
					</div>
		
					<div class="tab-pane <?php echo $active;?> " id="tab_2">
						
						<div class="modal-header" >
								
								<?php 
									
									$srno = $bd_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BD' order by id ";
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BD' order by id desc";
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
						
						
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>



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
		var sub    = 'sub3';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub3:sub},function(result){
		      $('#getbudgethead').html(result);
		});
	}


	function getbudgetheadto(id){
		var sub    = 'sub4';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub4:sub},function(result){
		      $('#getbudgetheadto').html(result);
		});
	}

	function getapprover(){
		
		var sub = 'sub5';
		var company_id    = document.getElementById("projecT").value;
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,sub5:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}
	
	$("#submitApprove").on("click", function(e){
		
        var sub 			= 'sub9';
		var mode		 	=  $("#modeE").val();		
//alert(sub + ' ' + mode);
		var company_id		=  $("#projecT").val();
		var bd_id		 	=  $("#bd_idE").val();
	    var statusap		=  mode;
		var approver		=  $("#approverC").val();
		var remarks			=  $("#remarksA").val();
//alert( company_id + ' ' + statusap + ' #1# ' + approver + ' #5# ' + bd_id + ' ' + remarks );

		var strURL = "app_func.php";
		$.post(strURL,{ bd_id:bd_id,
						mode:mode,
						company_id:company_id,	
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

    $("#submitReject").on("click", function(e){
        var sub 			= 'sub8';
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
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});

	    $('#rejectAuthority').modal('hide');
		
	});
		
</script>

</body>
</html>
