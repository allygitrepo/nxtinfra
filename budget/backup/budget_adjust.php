<?php
include("../header.php");
$modulePath = "budget/budget_adjust.php?sub=list";

$comid  = $_SESSION['comid'];
$role   = $_SESSION['role'];

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
				
					<a href="budget_adjust.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
			
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
			<th>Cost Center Group</th>
			<th>Cost Center Sub Group</th>
			<th>Effect</th>
			<th  style="text-align:right;">Amount</th>
			
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
	$sql = "SELECT * from sma_budget_category where id = '$budget_head' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_head = $r2['category'];
	
	$budget_name = $row['budget_name'];
	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	$budget_name = $r2['name'];
	
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
			$effect 			= $_POST['effect'];
			$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$fin_year			= $_POST['fin_year'];
			$adjust_type		= $_POST['adjust_type'];
			$share_point_link	= $_POST['share_point_link'];
			$status 			= 'Draft';
			
			$user 				= $_SESSION['user'];
			
			if($effect=='I'){
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
			if(!empty($error)){echo $error; exit();}
//echo $sql. ' '; exit();
			if($rowaffected==0){
				$id = $_SESSION['id'];
				
				if(empty($id)){$id=0;}
				
				echo "<script>alert('Selected Cost Center Sub Group not available in Budget...');</script>";
				echo "<script>window.location.href='budget_adjust.php?sub=add&id=$id';</script>";
				exit();
			}
			else if($rowaffected>0){
  			$sql="insert into budget_adjust ( fin_year, project, budget_name, budget_head, amount, effect, dated, approved_by, remarks ,adjust_type, status, draft_by, draft_dated , share_point_link ) 
				Values( '$fin_year', '$project', '$budget_name', '$budget_head', '$amount', '$effect', '$dated', '$approved_by', '$remarks', '$adjust_type', '$status', '$draft_by', now() , '$share_point_link' )";
					
			$query=mysqli_query($con, $sql);
			$id = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
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
				
					$sql = " update budget_adjust set file_name	= '$filename',
							 file_path		= '$folder_path',
							 date_uploaded	= now()
						where id = '$id'";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
				}
				
			}
			
			//echo "Budget successful added";
			//echo '<script>window.location.href="budget_adjust.php?sub=list";</script>';
			echo "<script>window.location.href='budget_adjust.php?sub=add&id=$id';</script>";
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
									<select class="form-control " name="fin_year" id="fin_year"  >
                             		<option value=""> Select </option>
									<option value="2021_2022" <?php echo ($fin_year == '2021_2022')?'selected="selected"':'';?> > 2021_2022</option>
									<option value="2022_2023" <?php echo ($fin_year == '2022_2023')?'selected="selected"':'';?> > 2022_2023</option>
									<option value="2023_2024" <?php echo ($fin_year == '2023_2024')?'selected="selected"':'';?> > 2023_2024</option>
									<option value="2024_2025" <?php echo ($fin_year == '2024_2025')?'selected="selected"':'';?> > 2024_2025</option>
									
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
							
							<label for="project" class="control-label col-sm-2">Company 123</label>
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
							<label class="col-lg-2 control-label">Cost Center Group</label>
							<div class="col-md-4">
							<span id="getbudgetname">
								<select class="form-control" name="budget_name" id="budget_name" onchange="getcostcentergroup(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</span>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Cost Center Sub Group</label>
							<div class="col-md-4">
							<span id="getcostcentergroup">
								<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "select distinct(budget_head) from sma_budget group by budget_head order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['budget_head'];?>" <?php echo ($budget_head == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
								</select>
							</span>	
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $amount;?>" >
							</div>
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
							<label class="col-lg-2 control-label">Doc.Attach</label>
							<div class="col-md-6">
								<!--<input type="file" class="form-control" name="fudoc" class="docfile">-->
								<textarea rows='2' class="form-control" name="share_point_link" class="docfile"></textarea>
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
			
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$effect 			= $_POST['effect'];
			$approved_by 		= $_POST['approved_by'];
			$dated 				= date('Y-m-d', strtotime($_POST['dated']));
			$amount				= $_POST['amount'];
			$amount_prev		= $_POST['amount_prev'];
			$remarks			= $_POST['remarks'];
			$project			= $_POST['project'];
			$fin_year			= $_POST['fin_year'];
			$adjust_type		= $_POST['adjust_type'];
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			
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
				}
			
  			$sql = " update budget_adjust set project	= '$project',
					 budget_name		= '$budget_name',
					 budget_head		= '$budget_head',
					 effect 			= '$effect',
					 approved_by 		= '$approved_by',
					 dated 				= '$dated',
					 amount				= '$amount',
					 remarks			= '$remarks',
					 file_name			= '$filename',
					 file_path			= '$folder_path',
					 fin_year			= '$fin_year',
					 adjust_type		= '$adjust_type',
					 share_point_link	= '$share_point_link'
					 date_uploaded		= now()
				where id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){ echo $error; exit(); }
			
			if($effect=='I'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget + $amount - $amount_prev
							where  	project			= '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head	= '$budget_head' ";
			}
			else if($effect=='D'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget - $amount + $amount_prev
							where  	project			= '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head	= '$budget_head' ";
			}
//echo $sql; exit();							
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $sql; exit();
			echo '<script>window.location.href="budget_adjust.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from budget_adjust where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$approved_by	 = $row['approved_by'];
 		$adjust_type	 = $row['adjust_type'];
		
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
            <form class="form-horizontal" action="budget_adjust.php?sub=edit" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<?php
							$dated = date('d-m-Y', strtotime($row['dated']));
							if($dated == '01-01-1970'){
								$dated = '';
							} 
						?>
						
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Adjustment Type</label>
							<div class="col-sm-3">
								<select class="form-control select2" required name="adjust_type" id="adjust_type" >
								<option value=""> Select </option>
								<?php $sql = "select * from sma_budget_type where 1 order by budget_type ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($row['adjust_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['budget_type'];?></option>
								<?php } ?>
								</select>	
								
							</div>
							
							<label for="project" class="control-label col-sm-2">Financial Year</label>
							<div class="col-sm-2">
								<select class="form-control " name="fin_year" id="fin_year"  >
                             		<option value=""> Select </option>
									<option value="2021_2022" <?php echo ($row['fin_year'] == '2021_2022')?'selected="selected"':'';?> > 2021_2022</option>
									<option value="2022_2023" <?php echo ($row['fin_year'] == '2022_2023')?'selected="selected"':'';?> > 2022_2023</option>
									<option value="2023_2024" <?php echo ($row['fin_year'] == '2023_2024')?'selected="selected"':'';?> > 2023_2024</option>
									<option value="2024_2025" <?php echo ($row['fin_year'] == '2024_2025')?'selected="selected"':'';?> > 2024_2025 </option>
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
							<label class="col-lg-2 control-label">Cost Center Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name" id="budget_name" >
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
							<label class="col-lg-2 control-label">Cost Center Sub Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "select distinct(budget_head) from sma_budget where project = '$project' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['budget_head'];?>" <?php echo ($row['budget_head'] == $r2['budget_head'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
						
							<input type="hidden" id="amount_prev" name="amount_prev" value = "<?php echo $row['amount'];?>" >
														
							<label class="col-lg-2 control-label">Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" autocomplete="off" id="amount" name="amount" style="text-align:right;" placeholder="" value="<?php echo $row['amount'];?>" >
							</div>
						</div>
						
						<?php
							$effect 	 = $row['effect'];
							$approved_by = $row['approved_by'];
						?>
						
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
								<input type="text" class="form-control" autocomplete="off" id="remarks" name="remarks" style="text-align:left;" placeholder="" value="<?php echo $row['remarks'];?>" >
							</div>
						</div>
						<?php
							$file_name	= $row['file_name'];
							$file_path	= $row['file_path'];
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Attach</label>
							<div class="col-md-6">
							<!--	<input type="file" class="form-control" name="fudoc" class="docfile" >-->
								<textarea rows='2' class="form-control" name="share_point_link" class="docfile"><a href="<?php echo $file_path. '/' . $file_name;?>" target="_blank" ><?php echo $file_name ?></a></textarea>
							</div>
							<div class="col-md-4">
								<a href="<?php echo $file_path. '/' . $file_name;?>" target="_blank" ><?php echo $file_name ?></a>
							</div>
						</div>
						
				<!--		<div class="form-group">
							<label class="col-lg-2 control-label">Approved By</label>
							<div class="col-md-4">
									<select class="form-control select2"  name="approved_by"   <?php echo $readonly; ?> >
										<option value="0">Select</option>
											<?php /* $sql = " SELECT * FROM sma_user order by username ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ */ ?>
												<option value="<?php echo $r2['id'];?>" <?php echo ($row['approved_by'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'] ;?></option>
											<?php //} ?>
									</select>
							</div>
						</div>-->

						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
							<?php if ($user=='Admin'){ ?>	
								<a href="<?php echo $baseurl."budget/budget_adjust.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } ?>	
							</div>
							
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								
								<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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

								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow 
											where 1 and doc_type = 'BD' and company_id = '$project' ), role ) ";
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
											where 1 and doc_type = 'BD' and company_id = '$comp_vertical' ), role ) ";
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
											where 1 and doc_type = 'BD' and company_id = '$comp_vertical' ), role ) ";
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
							//}
						?>
						
                        
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


	
</script>

<script>

	function getbudgetname(id){
	
		var sub    = 'sub12';
		var strURL = "app_func.php";
		
		alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{company_id:id,sub12:sub},function(result){
		      $('#getbudgetname').html(result);
		});
		
	}
	
	function getcostcentergroup(id){
	
		var sub    = 'sub10';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("project").value;
		
//		alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub10:sub},function(result){
		      $('#getcostcentergroup').html(result);
		});
		
	}
	
	function getapprover(){
		
		var sub = 'sub5';
		var company_id    = document.getElementById("projecT").value;
//alert(sub + ' ' + company_id);		
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
