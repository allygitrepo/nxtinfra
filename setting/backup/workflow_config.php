<?php

include("../header.php");
$modulePath = "setting/workflow_config.php?sub=list";
?>

  
  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Workflow
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
            <?php	
				$targetpage = "workflow_config.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];

				if ($_POST['company_id'] or $_POST['doc_type'] or $_POST['search'] or $_POST['trans_type'] ){
					$_SESSION['company_id'] 	= $_POST['company_id'];
					$_SESSION['trans_type'] 	= $_POST['trans_type'];
					$_SESSION['doc_type'] 		= $_POST['doc_type'];
					$_SESSION['search'] 		= $_POST['search'];
					$_SESSION['reset'] ='';
				}
				
				if ($_SESSION['company_id'] or $_SESSION['doc_type'] or $_SESSION['search'] or $_SESSION['trans_type'] ){
					$trans_type 	= $_SESSION['trans_type'];
					$company_id 	= $_SESSION['company_id'];
					$doc_type 		= $_SESSION['doc_type'];
					$search 		= $_SESSION['search'];
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['company_id'] 	= '';
					$_SESSION['doc_type'] 		= '';
					$_SESSION['trans_type'] 	= '';
					$_SESSION['search'] 		= '';
					$trans_type 	= $_SESSION['trans_type'];
					$company_id 	= $_SESSION['company_id'];
					$doc_type 		= $_SESSION['doc_type'];
					$search 		= $_SESSION['search'];
				}
		
				if(empty($company_id)){
					$company_id = '6';
				}	
			?>
			<form class="form-horizontal" action="workflow_config.php?sub=list" method="post">
						<div class="form-group">
								<div class="col-md-2">
									<label class=" control-label">Doc&nbsp;Type</label>
									<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									
									<option value="AP" <?php echo ($doc_type == 'AP')? "SELECTED":'';?> > Approval Memo </option>
									<option value="PO" <?php echo ($doc_type == 'PO')? "SELECTED":'';?> > Purchase Order </option>
									<option value="SI" <?php echo ($doc_type == 'SI')? "SELECTED":'';?> > Supplier Invoice </option>
									<option value="PY" <?php echo ($doc_type == 'PY')? "SELECTED":'';?> > Payment </option>										
									<option value="IP" <?php echo ($doc_type == 'IP')? "SELECTED":'';?> > IPC </option>	
									
									<option value="PC" <?php echo ($doc_type == 'PC')? "SELECTED":'';?> > Petty Cash</option>
															
									<option value="CE" <?php echo ($doc_type == 'CE')? "SELECTED":'';?> > Operating Expense </option>	
									<option value="TA" <?php echo ($doc_type == 'TA')? "SELECTED":'';?> > Travel Request </option>										
									<option value="TE" <?php echo ($doc_type == 'TE')? "SELECTED":'';?> > Travel Expenses </option>
									<option value="RE" <?php echo ($doc_type == 'RE')? "SELECTED":'';?> > Regular Expenses </option>
									<option value="BD" <?php echo ($doc_type == 'BD')? "SELECTED":'';?> > Budget Adjustment </option>
									</select>
								</div>
								
							<div class="col-md-4" class="input-append">
								<label class="control-label">Company* </label>
								<select class="form-control" name="company_id" id="company_id" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM company ORDER BY comp_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['comp_id']?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-sm-3">
								<label for="company_id" class="control-label ">Workflow Type </label>
								<select class="form-control select3" name="trans_type" id="trans_type"  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type order by workflow_type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($trans_type == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
                             </div>
							 
							<div class="col-md-2">
                                <label class="control-label">&nbsp;</label><BR>	
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="workflow_config.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
								</div>
							<?php } ?>
							
							</span>
											   
				</form>
				
			
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="workflow_config.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
					<a href="workflow_config_export.php?sub=list" target="_blank" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Report </a>
				</span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Company</th>
			<th>Doc.Type</th>
			<th>Workflow Type</th>
			<th style="text-align:right;">From Value</th>
			<th style="text-align:right;">To Value</th>
			<th>Approver Level 1</th>
			<th>Approver Level 2</th>
			<th>Approver Level 3</th>
			<th>Approver Level 4</th>
			<th style="text-align:right;">Action</th>
		</tr>
	</thead>
<tbody>
<?php

	$sql	="SELECT * from sma_workflow where 1 ";
	$query	="SELECT count(*) as num from sma_workflow where 1 ";
		if(!empty($company_id)){
			$sql   .= " and company_id = '$company_id' ";
			$query .= " and company_id = '$company_id' ";
		}

		if(!empty($doc_type)){
			$sql   .= " and doc_type = '$doc_type' ";
			$query .= " and doc_type = '$doc_type' ";
		}	
		
		if(!empty($trans_type)){
			$sql   .= " and trans_type = '$trans_type' ";
			$query .= " and trans_type = '$trans_type' ";
		}	
		
		
		
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	$doc_type = $row['doc_type'];
	if($doc_type == 'AP'){
		$doc_type = 'Approval';
	}
	else if($doc_type == 'PR'){
		$doc_type = 'Purchase Requisition';
	}
	else if($doc_type == 'PO'){
		$doc_type = 'Purchase Order';
	}
	else if($doc_type == 'SI'){
		$doc_type = 'Supplier Invoice';
	}
	else if($doc_type == 'PY'){
		$doc_type = 'Payment';
	}
	else if($doc_type == 'PC'){
		$doc_type = 'Petty Cash';
	}
	else if($doc_type == 'IP'){
		$doc_type = 'IPC';
	}
	else if($doc_type == 'CE'){
		$doc_type = 'Operating Expense';
	}
	else if($doc_type == 'TA'){
		$doc_type = 'Travel Request';
	}
	else if($doc_type == 'TE'){
		$doc_type = 'Travel Expenses';
	}
	else if($doc_type == 'RE'){
		$doc_type = 'Regular Expenses';
	}
	else if($doc_type == 'BD'){
		$doc_type = 'Budget Adjustment';
	}
	else if($doc_type == 'VN'){
		$doc_type = 'Vendor';
	}
	
	$company_id = $row['company_id'];
	$sql="SELECT * from company where comp_id = '$company_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$company_name = $r1['comp_name'];
	$company_code = $r1['comp_code'];
	
	$trans_type = $row['trans_type'];
	$sql="SELECT * from sma_workflow_type where id = '$trans_type' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$workflow_type = $r1['workflow_type'];
	
	$approval_role_1 = $row['approval_role_1'];
	$sql="SELECT * from sma_role where id = '$approval_role_1' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_1 = $r1['role'];
	
	$approval_role_2 = $row['approval_role_2'];
	$sql="SELECT * from sma_role where id = '$approval_role_2' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_2 = $r1['role'];
	
	$approval_role_3 = $row['approval_role_3'];
	$sql="SELECT * from sma_role where id = '$approval_role_3' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_3 = $r1['role'];
	
	$approval_role_4 = $row['approval_role_4'];
	$sql="SELECT * from sma_role where id = '$approval_role_4' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_4 = $r1['role'];
	
?>

	<tr>
		<td width="7%"><?php echo $company_code;?></td>
		<td width="10%"><?php echo $doc_type;?></td>
		<td width="10%"><?php echo $workflow_type;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo $row['from_value'];?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['to_value'];?></td>
		<td width="10%"><?php echo $approval_role_1;?></td>
		<td width="10%"><?php echo $approval_role_2;?></td>
		<td width="10%"><?php echo $approval_role_3;?></td>
		<td width="10%"><?php echo $approval_role_4;?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="workflow_config.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--<a href="workflow_config.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	
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
		$sql="delete from sma_workflow where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$doc_type			= $_POST['doc_type'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$company_id			= $_POST['company_id'];
			$trans_type			= $_POST['trans_type'];
			$approval_role_1	= $_POST['approval_role_1'];
			$approval_role_2	= $_POST['approval_role_2'];
			$approval_role_3	= $_POST['approval_role_3'];
			$approval_role_4	= $_POST['approval_role_4'];
			
			$approval_role_5	= $_POST['approval_role_5'];
			$approval_role_6	= $_POST['approval_role_6'];
			$approval_role_7	= $_POST['approval_role_7'];
			$approval_role_8	= $_POST['approval_role_8'];
			
  			$sql="insert into sma_workflow (doc_type, trans_type, from_value, to_value,  company_id,  approval_role_1, approval_role_2, approval_role_3, approval_role_4,
			approval_role_5, approval_role_6, approval_role_7, approval_role_8) 
				Values('$doc_type', '$trans_type', '$from_value', '$to_value', '$company_id', 
				'$approval_role_1', '$approval_role_2', '$approval_role_3', '$approval_role_4',
				'$approval_role_5', '$approval_role_6', '$approval_role_7', '$approval_role_8'	)";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//			echo "Workflow successful added";
			echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
		}
	

?>
   <section class="content-header">
        <h1>
            Workflow
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow</a></li>
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
            <form class="form-horizontal" action="workflow_config.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">For Company *</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="company_id" id="company_id" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">Type of Form *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="AP" > Approval Memo</option>
									<option value="PO"> Purchase Order </option>
									<option value="SI"> Supplier Invoice </option>	
									<option value="PY"> Payment </option>	
									<option value="PC"> Petty Cash </option>	
									<option value="IP"> IPC </option>	
									<option value="CE" > Operating Expense </option>
									<option value="TA"> Travel Request </option>
									<option value="TE"> Travel Expenses </option>
									<option value="RE"> Regular Expenses </option>
									<option value="BD"> Budget Adjustment </option>
									<option value="VN"> Vendor </option>
									
								</select>
							</div>

						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Workflow Type *</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="trans_type" id="trans_type" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
                             </div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Prefix (For PO type of form)</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="prefix" id="prefix" value="" >
							</div>
							
							<label for="company_id" class="control-label col-sm-1">Suffix</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="suffix" id="suffix" value="" >
							</div>
							

						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="" required >
							</div>
							
							<label class="col-lg-1 control-label">To Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="" required >
							</div>
						</div>
						
						
							<?php 
								$selected_ho 	= '';
								$selected_site 	= '';
								$company_id = $row['company_id'];
								
							?>
						<div class="form-group">
							<label class="control-label col-sm-2">&nbsp;</label>
							<label for="company_id" class="control-label col-sm-4">Approver Role - To be provided Sequentially, No gaps</label>
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 1*</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_1" id="approval_role_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 2</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_2" id="approval_role_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 3</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_3" id="approval_role_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label for="company_id" class="control-label col-sm-2">Approver Level 4</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_4" id="approval_role_4" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_4'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 5</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_5" id="approval_role_5" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_5'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 6</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_6" id="approval_role_6" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_6'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 7</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_7" id="approval_role_7" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_7'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 8</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_8" id="approval_role_8" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_8'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						
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
			
			$doc_type			= $_POST['doc_type'];
			$company_id			= $_POST['company_id'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$company_id			= $_POST['company_id'];
			$trans_type			= $_POST['trans_type'];
			$approval_role_1	= $_POST['approval_role_1'];
			$approval_role_2	= $_POST['approval_role_2'];
			$approval_role_3	= $_POST['approval_role_3'];
			$approval_role_4	= $_POST['approval_role_4'];
			
			$approval_role_5	= $_POST['approval_role_5'];
			$approval_role_6	= $_POST['approval_role_6'];
			$approval_role_7	= $_POST['approval_role_7'];
			$approval_role_8	= $_POST['approval_role_8'];
			
  			$sql="update sma_workflow set doc_type = '$doc_type',
						company_id			= '$company_id',
						from_value			= '$from_value',
						to_value			= '$to_value',
						company_id			= '$company_id',
						trans_type			= '$trans_type',
						approval_role_1		= '$approval_role_1',
						approval_role_2		= '$approval_role_2',
						approval_role_3		= '$approval_role_3',
						approval_role_4		= '$approval_role_4',
						approval_role_5		= '$approval_role_5',
						approval_role_6		= '$approval_role_6',
						approval_role_7		= '$approval_role_7',
						approval_role_8		= '$approval_role_8'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_workflow where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Workflow
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="workflow_config.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
											
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Company*</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="company_id" id="company_id" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<?php $doc_type = $row['doc_type']; ?>
						
						<div class="form-group">
							
							<label for="doc_type" class="control-label col-sm-2">Type of Form*</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="AP" <?php echo ($row['doc_type'] == 'AP')? "SELECTED":'';?> > Approval Memo</option>
									<option value="PO" <?php echo ($row['doc_type'] == 'PO')? "SELECTED":'';?> > Purchase Order </option>
									<option value="SI" <?php echo ($row['doc_type'] == 'SI')? "SELECTED":'';?> > Supplier Invoice </option>
									<option value="PY" <?php echo ($row['doc_type'] == 'PY')? "SELECTED":'';?> > Payment </option>
									<option value="PC" <?php echo ($row['doc_type'] == 'PC')? "SELECTED":'';?> > Petty Cash </option>	
									<option value="IP" <?php echo ($row['doc_type'] == 'IP')? "SELECTED":'';?> > IPC </option>
									
									<option value="CE" <?php echo ($doc_type == 'CE')? "SELECTED":'';?> > Operating Expence </option>	
									<option value="TA" <?php echo ($row['doc_type'] == 'TA')? "SELECTED":'';?> > Travel Request </option>										
									<option value="TE" <?php echo ($row['doc_type'] == 'TE')? "SELECTED":'';?> > Travel Expenses </option>
									<option value="RE" <?php echo ($row['doc_type'] == 'RE')? "SELECTED":'';?> > Regular Expenses </option>
									<option value="BD" <?php echo ($row['doc_type'] == 'BD')? "SELECTED":'';?> > Budget Adjustment </option>
									<option value="VN" <?php echo ($row['doc_type'] == 'VN')? "SELECTED":'';?>> Vendor </option>
								</select>
							</div>
						</div>
						
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Workflow Type *</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="trans_type" id="trans_type" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Prefix (For PO type of form)</label>
							<div class="col-sm-2">
								<input type="text" class="form-control " name="prefix" id="prefix" value="<?= $row['prefix']; ?>" >
							</div>
							
							<label for="company_id" class="control-label col-sm-1">Suffix</label>
							<div class="col-sm-2">
								<input type="text" class="form-control " name="suffix" id="suffix" value="<?= $row['suffix']; ?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="<?php echo $row['from_value'];?>" required >
							</div> 
							
							<label class="col-lg-1 control-label">To Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="<?php echo $row['to_value'];?>" required >
							</div>
						</div>
						
							<?php 	
								$selected_ho 	= '';
								$selected_site 	= '';
								$company_id = $row['company_id'];
								
							?>
						<div class="form-group">
							<label class="control-label col-sm-2">&nbsp;</label>
							<label for="company_id" class="control-label col-sm-4">Approver Role - To be provided Sequentially, No gaps</label>
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 1*</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_1" id="approval_role_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_1'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 2</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_2" id="approval_role_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_2'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 3</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_3" id="approval_role_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_3'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 4</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_4" id="approval_role_4" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_4'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 5</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_5" id="approval_role_5" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_5'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 6</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_6" id="approval_role_6" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_6'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 7</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_7" id="approval_role_7" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_7'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 8</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_8" id="approval_role_8" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_8'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/workflow_config.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
      
<?php } 	?>

<?php 	
		include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/iCheck/icheck.min.js" ?>"></script>
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


	function getlocation(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getlocation').html(result);
		});

	}


	function getbalbugdget(){
		var trans_type =  document.getElementById('trans_type').value;
		var approval_role_1 =  document.getElementById('approval_role_1').value;
		
		var balance_budget = trans_type - approval_role_1;
		
		//$('#balance_budget').attr('readonly', true);
		document.getElementById('balance_budget').value=balance_budget;
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Workflow should be less then total budget...");
			//var approval_role_1 = 0;
			document.getElementById('approval_role_1').value=0;
			
		}
		
	}	
	
//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });	
</script>


<!-- iCheck 1.0.1 -->


</body>
</html>

