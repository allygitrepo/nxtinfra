<?php
include("../header.php");
$modulePath = "budget/budget_adjust.php?sub=list";

$comid  = $_SESSION['comid'];
$role   = $_SESSION['role'];
$userid	= $_SESSION['usrid'];


	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");


?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Adjust
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget Adjust</li>
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
				<?php  
					//if ( $viewonly!='Y'){ ?>
					<a href="budget_adjust.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
				<?php //} ?>	
			
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
			<th>Fin.Year</th>
			<th>Company</th>
			<th>Budget Group</th>
			<th>Budget Sub Group</th>
			<th>Effect</th>
			<th  style="text-align:right;">Amount</th>
			<th  style="text-align:right;">Status</th>
			
			<th style="text-align:right;">Action</th>

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
										
	$budget_head = $row['budget_head'];
	
	$budget_name = $row['budget_name'];
	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_name = $r2['name'];
	
	$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_head = $r2['budget_head'];
	
	$effect 		= $row['effect'];
	if($effect == 'I'){
		$effect = 'Increase';
	}
	else if($effect == 'D'){
		$effect = 'Decrease';
	}	
	
	$amount 		= $row['amount'];
	/* $approved_by 	= $row['approved_by'];
	
	$sql = " SELECT * from sma_user where id = '$approved_by' ";
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$approved_by = $r2['username']; */
	
	$dated 			= date('d-m-Y', strtotime($row['dated']));
	
	$amount 		= $row['amount'];
	$fin_year 		= $row['fin_year'];
	
	
	
	$baseurl1 = $baseurl.$modulePath1.'budget_adjust.php?sub=edit&id='.$row["id"];
	
	$i = $i +1;
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget_adjust.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%" style="display:none;"><?php echo $i;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $fin_year;?></td>
		<td width="10%"><?php echo $comp_code;?></td>
		<td width="15%"><?php echo $budget_name;?></td>
		<td width="15%"><?php echo $budget_head;?></td>
		<td width="10%"><?php echo $effect;?></td>
		<td width="10%" style="text-align:right;"><?php echo $amount;?></td>
		<td width="08%" ><?php echo $row['status'];?></td>
					
		<td width="5%" style="text-align:right;">
			<a href="budget_adjust.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		</td>
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
		
			$sql	="Select * from budget_adjust where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$company_id 			= $row['project'];
			$budget_name 			= $row['budget_name'];
			$budget_head 			= $row['budget_head'];
			$fin_year	 			= $row['fin_year'];
			
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_name = $r2['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head = $r2['budget_head'];
			
			$pgname 		= "budget_adjust.php";
			include "../viewonly.php";
			$description 	= $budget_name.', '.$budget_head.', '.$fin_year;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from budget_adjust where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="budget_adjust.php?sub=list";</script>';
	}
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$budget_code		= $_POST['budget_code'];
			$budget_id			= $_POST['budget_id'];
			$effect 			= $_POST['effect'];
			$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$trans_type			= $_POST['trans_type'];
			$fin_year			= $_POST['fin_year'];
			$adjust_type		= $_POST['adjust_type'];
			$share_point_link	= $_POST['share_point_link'];
			$status 			= 'Draft';
			
			$user 				= $_SESSION['user'];
			
			$sql = " SELECT * from sma_budget where  	project	= '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head		= '$budget_head'
								and account_year	= '$fin_year' ";
			$query= mysqli_query($con, $sql);	
			$r2 =	mysqli_fetch_array($query);			
			$budget_id  = $r2['id'];			
			
			/* if($effect=='I'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget + $amount 
							where  	project			= '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head		= '$budget_head' ";
			}
			else if ($effect=='D'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget - $amount 
							where  	project			= '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head		= '$budget_head' ";
			}
			$query= mysqli_query($con, $sql);
			$rowaffected = mysqli_affected_rows($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();} */
//echo $sql. ' '; exit();
			/* if($rowaffected==0){
				$id = $_SESSION['id'];
				
				if(empty($id)){$id=0;}
				
				echo "<script>alert('Selected Budget Sub Group not available in Budget...');</script>";
				echo "<script>window.location.href='budget_adjust.php?sub=add&id=$id';</script>";
				exit();
			}
			else if($rowaffected>0){ */
  			$sql="insert into budget_adjust ( fin_year, project, trans_type, budget_name, budget_head, budget_code, budget_id, amount, effect, dated, approved_by, remarks ,adjust_type, status, draft_by, draft_dated , share_point_link ) 
				Values( '$fin_year', '$project', '$trans_type', '$budget_name', '$budget_head', '$budget_code', 
				'$budget_id', '$amount', '$effect', '$dated', '$approved_by', '$remarks', 
				'$adjust_type', '$status', '$draft_by', now() , '$share_point_link' )";
					
			$query=mysqli_query($con, $sql);
			$id = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$arrFUDoc 			= $_FILES["fudoc"];
			$arrFUDoc1			= $_FILES["fudoc1"];
			
				$folder_path = "uploads/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				
				$filename 	 = $arrFUDoc['name'];
				$tmpFileName = $arrFUDoc['tmp_name'];
				
				if( !empty($filename) ){
					move_uploaded_file($tmpFileName, $folder_path . "/" . $filename);
				
					$sql = " update budget_adjust set file_name	= '$filename',
							 file_path		= '$folder_path',
							 date_uploaded	= now()
						where id = '$id'";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
				}
				
				$filename1 	 = $arrFUDoc1['name'];
				$tmpFileName1 = $arrFUDoc1['tmp_name'];
				
				if( !empty($filename1) ){
					move_uploaded_file($tmpFileName1, $folder_path . "/" . $filename1);
				
					$sql = " UPDATE budget_adjust SET file_name	= '$filename1',
							 file_path		= '$folder_path',
							 date_uploaded	= now()
						WHERE id = '$id'";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
				}
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'BD', '$id', '$userid', now(), '$status', '','' ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
			//}
			
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_name = $r2['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head = $r2['budget_head'];
			
			$pgname 		= "budget_adjust.php";
			include "../viewonly.php";
			$description 	= $budget_name.', '.$budget_head.', '.$fin_year;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Budget successful added";
			//echo '<script>window.location.href="budget_adjust.php?sub=list";</script>';
			echo "<script>window.location.href='budget_adjust.php?sub=edit&id=$id';</script>";
			exit();
		}
	
	if($_GET['id']){
		$id = $_GET['id'];
		$_SESSION['id'] = $_GET['id'];
		$sql="Select * from budget_adjust where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);
		
			$budget_name		= $row['budget_name'];
			$budget_head		= $row['budget_head'];
			$budget_code		= $row['budget_code'];
			$budget_id			= $row['budget_id'];
			$effect 			= $row['effect'];
			$dated 				= date('d-m-Y', strtotime($row['dated']));
			$amount				= $row['amount'];
			$remarks			= $row['remarks'];
			$project			= $row['project'];
			$fin_year			= $row['fin_year'];
			$adjust_type		= $row['adjust_type'];
			
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
            <form class="form-horizontal" action="budget_adjust.php?sub=add" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
								
							<label for="project" class="control-label col-sm-2">Adjustment Type</label>
							<div class="col-sm-3">
								<select class="form-control select2" required name="adjust_type" id="adjust_type" required >
								<option value=""> Select </option>
								<?php $sql = "select * from sma_budget_type where 1 order by budget_type ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($row['adjust_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['budget_type'];?></option>
								<?php } ?>
								</select>	
								
							</div>
											   
							</div>
							
							
								<label for="project" class="control-label col-sm-2">Financial Year</label>
								<div class="col-sm-2">
									<select class="form-control " name="fin_year" id="FIN_YEAR"  >
                             		<option value=""> Select </option>
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
						
						<?php
							$dated = date('d-m-Y');
							
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off" value="<?php echo $dated ; ?>" <?php echo $readonly; ?> >
									<div class="input-group-addon">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
							</div>
						</div>
						
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2">Company</label>
							<div class="col-sm-4">
									<select class="form-control " name="project" id="project" onchange="getbudgetname(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
								
						</div>
						
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Group</label>
							<div class="col-md-4">
							<span id="getbudgetname">
								<select class="form-control" name="budget_name" id="budget_name" onchange="getcostcentergroup(this.value)" >
									<option value=""> Select </option>
										
								</select>
							</span>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-4">
							<span id="getcostcentergroup">
								<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
								</select>
							</span>	
							</div>
							
							<label class="col-lg-2 control-label">Budget Code</label>
							<div class="col-md-2">
							<span id="getcostcentercode">
								
							</span>
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $amount;?>" >
							</div>
							
							<span id="getccbalbudget">
								
							</span>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Effect</label>
							<div class="col-md-5">
								<input type="radio" id="effect" name="effect" <?php echo ($effect=='I')?"CHECKED":''; ?> value="I" > Increase &nbsp;
								<input type="radio" id="effect" name="effect" <?php echo ($effect=='D')?"CHECKED":''; ?> value="D" > Decrease
							</div>
						
						</div>
						
							<div class="form-group">
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<input type="text" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="<?php echo $remarks;?>" >
							</div>
						</div>
						
							
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Attach First</label>
							<div class="col-md-5">
								<textarea rows='2' class="form-control" name="share_point_link" class="docfile" placeholder="Share Point Link"></textarea>
							</div>
							<div class="col-md-5">
								<input type="file" class="form-control" name="fudoc" class="docfile">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Attach Second </label>
							<label class="col-lg-5 control-label">&nbsp; </label>
							
							<div class="col-md-5">
								<input type="file" class="form-control" name="fudoc1" class="docfile">
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
			
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$effect 			= $_POST['effect'];
			$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$amount_prev		= $_POST['amount_prev'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$trans_type			= $_POST['trans_type'];
			$fin_year			= $_POST['fin_year'];
			$adjust_type		= $_POST['adjust_type'];
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			
			$share_point_link	= $_POST['share_point_link'];
			
			if(!empty($approver_1)){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
			}

				$arrFUDoc 			= $_FILES["fudoc"];
				$folder_path = "uploads/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				
				$filename 	 = $arrFUDoc['name'];
				$tmpFileName = $arrFUDoc['tmp_name'];
				
				if( !empty($filename) ){
					move_uploaded_file($tmpFileName, $folder_path . "/" . $filename);
				
					$sql = " UPDATE budget_adjust SET file_name = '$filename',
									file_path			= '$folder_path',
									share_point_link	= '$share_point_link'
						where id = '$id'";
					$query=mysqli_query($con, $sql);
				}
				
				$arrFUDoc1 			= $_FILES["fudoc1"];
				
				$filename1 	  = $arrFUDoc1['name'];
				$tmpFileName1 = $arrFUDoc1['tmp_name'];
				
			if( !empty($filename1) ){
					move_uploaded_file($tmpFileName1, $folder_path . "/" . $filename1);
				
				$sql = " UPDATE budget_adjust SET file_name1 = '$filename1',
								file_path1			= '$folder_path'
							where id = '$id'";
				$query=mysqli_query($con, $sql);
			}
			
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];

			$status				= $_POST['status'];
			
			$sql = " UPDATE budget_adjust SET adjust_type	= '$adjust_type' WHERE id = '$id'";
			$query=mysqli_query($con, $sql);
			
			//file_name			= '$filename',
			//file_path			= '$folder_path',
			
		if( $status == 'Draft' ){
  			$sql = " update budget_adjust set project	= '$project',
					 trans_type			= '$trans_type',
					 budget_name		= '$budget_name',
					 budget_head		= '$budget_head',
					 effect 			= '$effect',
					 approved_by 		= '$approved_by',
					 dated 				= '$dated',
					 amount				= '$amount',
					 remarks			= '$remarks',
					 fin_year			= '$fin_year',
					 adjust_type		= '$adjust_type',
					 share_point_link	= '$share_point_link',
					 date_uploaded		= now()
				where id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){ echo $error; exit(); }
		
		}
		
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update budget_adjust set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				
				mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'BD', '$id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "approval/";
				
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
				
				$baseurl1 =$baseurl.$modulePath.'budget_adjust.php?id='.$id;
				
				$msg = 'Budget Adjustment Number : '.$id . ' ' . 'Dated : ' . date("d-m-Y");
				
				//include "ap_mail.php";	
					
			}
			
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_name = $r2['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head = $r2['budget_head'];
			
			$pgname 		= "budget_adjust.php";
			include "../viewonly.php";
			$description 	= $budget_name.', '.$budget_head.', '.$fin_year;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql; exit();
			echo '<script>window.location.href="budget_adjust.php?sub=list";</script>';
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
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];
		$approver_5_status 	= $row['approver_5_status'];
	
		$readonly='';
		if($approved_by=='Y' || $status=='Submitted' || $status=='Completed' || $viewonly!='Y' ){
			$readonly = "READONLY";
		}
		
		if( $viewonly=='Y' ){
			$readonly = "disabled";
		}	
		
		$readonly='';
		if($status!='Draft'){
			$readonly = "READONLY";
		}	
		
//echo $readonly. "<<>>";		
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
            <form class="form-horizontal" action="budget_adjust.php?sub=edit" method="post"  enctype="multipart/form-data">
              <div class="box-body">
					
					<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
					
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="status" value="<?php echo $row['status'];?>">
					  
						<?php
						
							$bd_id = $row['id'];
							
							$dated = date('d-m-Y', strtotime($row['dated']));
							if($dated == '01-01-1970'){
								$dated = '';
							} 
						?>
					<ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Budget Adjust</a></li>
                        <li class="#" ><a href="#tab_2" data-toggle="tab" id="second_tab">Workflow History</a></li>
					</ul>
					<div class="tab-content">
					<div class="tab-pane active " id="tab_1">
							
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Adjustment Type</label>
				<?php
					$adjust_type = $row['adjust_type'];
					$sqla = "";
					if(!empty($readonly)){
						$sqla = " and id = '$adjust_type' ";
					}
					
				?>	
							<div class="col-sm-3">
								<select class="form-control select2" <?= $readonly; ?> required name="adjust_type" id="adjust_type" >
				<?php		if(empty($readonly)){ ?>		
								<option value=""> Select </option>
				<?php } ?>			
								<?php $sql = "select * from sma_budget_type where 1 $sqla order by budget_type ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($row['adjust_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['budget_type'];?></option>
								<?php } ?>
								</select>	
								
							</div>
							
							<label for="project" class="control-label col-sm-2">Financial Year</label>
							<div class="col-sm-2">
								<select class="form-control " <?php echo $readonly; ?> name="fin_year" id="fin_year"  >
                             		<option value=""> Select </option>
								<?php	
									$sql="SELECT * FROM sma_financial_year order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($row['fin_year'] == $r2['short_fy_code'] )?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
								<?php } ?>
									
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" autocomplete="off"
                                               value="<?php echo $dated ; ?>" <?php echo $readonly; ?> >
									<div class="input-group-addon">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
							</div>
						</div>
						
						<?php  $project = $row['project']; ?>
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company</label>
								<div class="col-sm-4">
									<select class="form-control " <?php echo $readonly; ?> name="project" id="projecT" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
						</div>
						
						<?php 
							$budget_name = $row['budget_name'];
							$budget_head = $row['budget_head'];
							$fin_year	 = $row['fin_year'];
					
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Group</label>
							<div class="col-md-4">
								<select class="form-control" <?php echo $readonly; ?> name="budget_name" id="budget_name" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-4">
								<select class="form-control" <?php echo $readonly; ?> name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_subgroup	where budget_name = '$budget_name' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-2 control-label">Budget Code</label>
							<div class="col-md-2">
							<span id="getcostcentercode">
								<input type="text" class="form-control" <?php echo $readonly; ?> autocomplete="off" id="budget_id" name="budget_id" readonly value="<?php echo $budget_id;?>" >
							</span>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<input type="hidden" id="amount_prev" name="amount_prev" value = "<?php echo $row['amount'];?>" >
														
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" <?php echo $readonly; ?> autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $row['amount'];?>" >
							</div>
							
					<?php		
						if($budget_id>0){
							$sql  	= "select * from sma_budget where 1 and id= '$budget_id' ";		
						}
						else {
							$sql  	= "select * from sma_budget where 1 and project = '$project' and budget_name= '$budget_name' and budget_head= '$budget_head' and account_year = '$fin_year' ";	
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
							<label class="col-lg-2 control-label">Total Budget</label>
							<div class="col-md-2">
							<input type="text" class="form-control" readonly value="<?= $total_budget;?>" >
							</div>
							
							<label class="col-lg-2 control-label">Balance Budget</label>
							<div class="col-md-2">
							<input type="text" class="form-control" readonly value="<?= $bal_budget;?>" >
							</div>	
		
		
						</div>
						
						<?php
							$effect 	 = $row['effect'];
							$approved_by = $row['approved_by'];
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Effect</label>
							<div class="col-md-5">
						<?php if($effect=='I'){ ?>
								<input type="radio" id="effect" name="effect" <?php echo ($effect=='I')?"CHECKED":''; ?> value="I" > Increase &nbsp;
						<?php } ?>	
						<?php if($effect=='D'){ ?>		
								<input type="radio" id="effect" name="effect" <?php echo ($effect=='D')?"CHECKED":''; ?> value="D" > Decrease
						<?php } ?>		
							</div>
						</div>
					
			<?php	$readonlyb = '';
					if($status!='Draft'){		
						$readonlyb= "READONLY";
					}
			?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<input type="text" <?php echo $readonlyb; ?> class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="<?php echo $row['remarks'];?>" >
							</div>
						</div>
						<?php
							$file_name	= $row['file_name'];
							$file_path	= $row['file_path'];
							$share_point_link = $row['share_point_link'];
							
							$file_name1	= $row['file_name1'];
							$file_path1	= $row['file_path1'];
							
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Attach First</label>
							<div class="col-md-4">
								<textarea rows='2' <?php echo $readonly; ?> class="form-control" name="share_point_link" class="docfile"><?= $row['share_point_link']; ?></textarea>
							</div>
							<div class="col-md-4">
								<input type="file" class="form-control" name="fudoc" class="docfile" >
							</div>
					<?php if(!empty($file_name)){ ?>			
							<div class="col-md-4">Link :
								<a href="<?= $file_path.'/'.$file_name; ?>" target="_blank" > <?= $file_name;?></a>
							</div>
					<?php } ?>
					
						<?php if(!empty($share_point_link)){ ?>	
							<div class="col-md-4">
								<a href="<?= $share_point_link; ?>" target="_blank" >Share Point Link</a>
							</div>
						<?php } ?>	
							
						</div>
				
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Attach Second</label>
							<label class="col-lg-4 control-label">&nbsp;</label>
							
							<div class="col-md-4">
								<input type="file" class="form-control" name="fudoc1" class="docfile" >
							</div>
						</div>
				
						<div class="form-group">	
							<label class="col-lg-6 control-label">&nbsp;</label>
					<?php if(!empty($file_name1)){ ?>			
							<div class="col-md-4">Link :
								<a href="<?= $file_path.'/'.$file_name1; ?>" target="_blank" > <?= $file_name1;?></a>
							</div>
					<?php } ?>
					
							
							
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
							<?php if ($user=='Admin' && $status=='Draft'){ ?>	
								<a href="<?php echo $baseurl."budget/budget_adjust.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } ?>	
							</div>
							
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
					<?php
					//echo $status. "<BR>";
							$approver_flag='';
							if( $status != 'Draft' ){
//echo $userid . ' ' . 	$approver_1 . ' ' . $approver_1_status. "<BR>";								
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
									
								}

								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){	
							?>	
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
							<?php } 
							
								}
							?>
							
						<?php if( $status == 'Draft' ){ ?>	
								<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
						<?php } ?>
						
						<?php if( $viewonly != 'Y' ){ ?>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
						<?php } ?>
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							</div>
						</div>

						
						<span id="predit"></span>
				
					<?php  
						//if( $status == 'Draft' ){
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
								$approver_4 = $row['approver_4'];
								$approver_5 = $row['approver_5'];
							//	$approver_6 = $row['approver_6'];
								
						if( $status == 'Submitted' || $status == 'Completed' ){		
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
									<?= $approver_4_name . " <BR> " . $approver_4_role;?>
									
								</div>
					<?php	
							}
					?>
								
								<BR>
								
							</div>
						
						
						</span>
						<?php 
							}
						?>
					
				</div>
				
				</div>

<!--Workflow History -->
				<div class="tab-pane" id="tab_2">
							
							<div class="modal-header" >
								<p><?= $label_line; ?></p>
								<?php 
									
									
									$srno = $bd_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'BD' order by id ";
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
												
												$role = $rw1['primary_role'];

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
										
							//$bd_id 			= $_SESSION['bd_id'];
							$mode_status 	= $status;
							$role			= $_SESSION['role']; //Maker
							//$user_category = $_SESSION['user_category'];
							?>	
										
							<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
							<input type="hidden" id="modeE" name="mode" value='<?= $mode_status ?>' >
							<input type="text" id="bd_idE" name="bd_id" value="<?= $bd_id; ?>" >
										
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
										//	$bd_id 	= $_SESSION['bd_id'];
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


	
</script>

<script>

	function getbudgetname(id){
	
		var sub    = 'sub12';
		var strURL = "app_func.php";
		
//		alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{company_id:id,sub12:sub},function(result){
		      $('#getbudgetname').html(result);
		});
		
	}
	
	
	function getcostcentergroup(id){
	
		var sub    = 'sub10';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("project").value;
		var account_year    = document.getElementById("FIN_YEAR").value;
		
//		alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,account_year:account_year,company_id:company_id,sub10:sub},function(result){
		      $('#getcostcentergroup').html(result);
		});
		
	}
	
	
	function getcostcentercode(id){
	
		var sub    = 'sub13';

		var strURL = "app_func.php";
		var company_id     = document.getElementById("project").value;
		var budget_name    = document.getElementById("budget_name").value;
		var fin_year	= document.getElementById("FIN_YEAR").value;	
		
//		alert(sub + ' ' + fin_year + ' ' + id + ' ' + budget_name + ' ' + company_id);
		$.post(strURL,{id:id,fin_year:fin_year,budget_name:budget_name,company_id:company_id,sub13:sub},function(result){
		      $('#getcostcentercode').html(result);
		});
		
		$.post(strURL,{id:id,fin_year:fin_year,budget_name:budget_name,company_id:company_id,sub13a:sub},function(result){
		      $('#getccbalbudget').html(result);
		});
		
	}
	
	
	function getapprover(){
		
		var sub = 'sub5';
		var company_id      = document.getElementById("projecT").value;
		var trans_type 		=  $("#trans_type").val();
//alert(sub + ' ' + company_id);		
		var strURL = "app_func.php";
		$.post(strURL,{trans_type:trans_type,company_id:company_id,sub5:sub},function(result){
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
