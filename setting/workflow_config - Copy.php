<!DOCTYPE html>
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
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Workflow List</h3>
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="workflow_config.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
				</span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Doc.Type</th>
			<th>User Category</th>
			<th style="text-align:right;">From Value</th>
			<th style="text-align:right;">To Value</th>
			<th>Project Manager</th>
			<th>Project Incharge</th>
			<th>COO/CXO</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_workflow";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	$doc_type = $row['doc_type'];
	if($doc_type == 'AP'){
		$doc_type = 'Approval';
	}
	else if($doc_type == 'PO'){
		$doc_type = 'Purchase Order';
	}
	else if($doc_type == 'GR'){
		$doc_type = 'GRN/SRN';
	}
	else if($doc_type == 'SI'){
		$doc_type = 'Supplier Invoice';
	}
	
	$user_category = $row['user_category'];
	if($user_category == 'S'){
		$user_category = 'Site';
	}
	else if($user_category == 'H'){
		$user_category = 'Head Office';
	}
	
?>

	<tr>
		<td width="10%"><?php echo $doc_type;?></td>
		<td width="10%"><?php echo $user_category;?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['from_value'];?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['to_value'];?></td>
		<td width="10%"><?php echo $row['project_manager'];?></td>
		<td width="10%"><?php echo $row['project_incharge'];?></td>
		<td width="10%"><?php echo $row['coo_cxo'];?></td>
		
		<td width="10%" style="text-align:right;">
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
			$user_category		= $_POST['user_category'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$project_manager	= $_POST['project_manager'];
			$project_incharge	= $_POST['project_incharge'];
			$coo_cxo			= $_POST['coo_cxo'];
			
  			$sql="insert into sma_workflow (doc_type, user_category, from_value, to_value,  project_manager, project_incharge, coo_cxo) 
				Values('$doc_type', '$user_category', '$from_value', '$to_value', '$project_manager', '$project_incharge', '$coo_cxo' )";
					
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
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
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
							
							<label for="user_category" class="control-label col-sm-2">Document Type</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="PR"> Purchase Requisition</option>
									<option value="AP"> Approval Memo </option>
									<option value="PO"> Purchase Order </option>
									<option value="GR"> GRN/SRN </option>
									<option value="SI"> Supplier Invoice </option>										
								</select>
							</div>

						</div>
						
							<?php 
								$selected_ho 	= '';
								$selected_site 	= '';
								$user_category = $row['user_category'];
								if($user_category=='S' ){
									$selected_site = 'checked';
								}
								else if($user_category=='H'){
									$selected_ho = 'checked';
								}
								else if($user_category==''){
									$selected_ho = '';
								}
							?>
							
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">User Category</label>
							<div class="col-sm-4">
								<label>
								<input type="radio" class="minimal" name="user_category" id="user_category" value="S" > Site &nbsp;&nbsp;&nbsp;
								</label>
								<label>
                            	<input type="radio" class="minimal" name="user_category" id="user_category" value="H" > Head Office
								</label>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="" >
							</div>
							
							<label class="col-lg-1 control-label">To Value</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Project Manager</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal" id="project_manager" <?php echo $selected_pm; ?> name="project_manager" value="Y" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Project Incharge</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal" id="project_incharge" <?php echo $selected_pi; ?> name="project_incharge" value="Y" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">COO / CXO</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal"  id="coo_cxo" name="coo_cxo" <?php echo $selected_site; ?> value="Y" >
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
			$user_category		= $_POST['user_category'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$project_manager	= $_POST['project_manager'];
			$project_incharge	= $_POST['project_incharge'];
			$coo_cxo			= $_POST['coo_cxo'];
			
  			$sql="update sma_workflow set 
						doc_type			= '$doc_type',
						user_category		= '$user_category',
						from_value			= '$from_value',
						to_value			= '$to_value',
						project_manager		= '$project_manager',
						project_incharge	= '$project_incharge',
						coo_cxo				= '$coo_cxo'
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
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
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
							
							<label for="doc_type" class="control-label col-sm-2">Document Type</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="AP" <?php echo ($row['doc_type'] == 'AP')? "SELECTED":'';?> > Approval </option>
									<option value="PO" <?php echo ($row['doc_type'] == 'PO')? "SELECTED":'';?> > Purchase Order </option>
									<option value="GR" <?php echo ($row['doc_type'] == 'GR')? "SELECTED":'';?> > GRN/SRN </option>
									<option value="SI" <?php echo ($row['doc_type'] == 'SI')? "SELECTED":'';?> > Supplier Invoice </option>										
								</select>
							</div>
						</div>
						
							<?php 
								$selected_ho 	= '';
								$selected_site 	= '';
								$user_category = $row['user_category'];
								if($user_category=='S' ){
									$selected_site = 'checked';
								}
								else if($user_category=='H'){
									$selected_ho = 'checked';
								}
								else if($user_category==''){
									$selected_ho = '';
								}
							?>
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">User Category</label>
							<div class="col-sm-4">
								<input type="radio" class="minimal" <?php echo $selected_site; ?> name="user_category" id="user_category" value="S" >Site &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_ho; ?> name="user_category" id="user_category" value="H" >Head Office
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="<?php echo $row['from_value'];?>" >
							</div> 
							
							<label class="col-lg-2 control-label">To Value</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="<?php echo $row['to_value'];?>" >
							</div>
						</div>
							
						<?php 
							$selected_pm 	= '';
							$project_manager = $row['project_manager'];
							if($project_manager == 'Y'){
								$selected_pm = 'CHECKED';
							}
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Project Manager</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal" id="project_manager" <?php echo $selected_pm; ?> name="project_manager" value="Y" >
							</div>
						</div>
						
						<?php 
							$selected_pi = '';
							$project_incharge = $row['project_incharge'];
							if($project_incharge=='Y'){
								$selected_pi = 'CHECKED';
							}
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Project Incharge</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal" id="project_incharge" <?php echo $selected_pi; ?> name="project_incharge" value="Y" >
							</div>
						</div>
						<?php 
							$selected_coo = '';
							$coo_cxo = $row['coo_cxo'];
							if($coo_cxo=='Y'){
								$selected_coo = 'CHECKED';
							}
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">COO / CXO</label>
							<div class="col-md-2">
								<input type="checkbox" class="minimal" id="coo_cxo" name="coo_cxo" <?php echo $selected_coo; ?> value="Y" >
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
		var project_incharge =  document.getElementById('project_incharge').value;
		var coo_cxo =  document.getElementById('coo_cxo').value;
		
		var balance_budget = project_incharge - coo_cxo;
		
		//$('#balance_budget').attr('readonly', true);
		document.getElementById('balance_budget').value=balance_budget;
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Workflow should be less then total budget...");
			//var coo_cxo = 0;
			document.getElementById('coo_cxo').value=0;
			
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
