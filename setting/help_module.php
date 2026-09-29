<?php

include("../header.php");
$modulePath = "setting/help_module.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Module Help
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Module Help</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Module Help</h3>
                <span class="pull-right"><a href="help_module.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-code_letter_add"></i>&nbsp;&nbsp;Create </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Code </th>
			<th>Module </th>
			<th>Status </th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$sql="SELECT * from sma_help where 1  ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		
		$status = $row['status'];
		if($status =='Y'){
			$status = 'Active';
		}
		else {
			$status = 'Inactive';
		}	
		$baseurl1 = $baseurl.$modulePath1.'help_module.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "help_module.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="10%"><?php echo $row['code'];?></td>
		<td width="10%"><?php echo $row['program_name'];?></td>
		<td width="10%"><?php echo $status;?></td>
		<td width="5%" style="text-align:right;">
		<a href="help_module.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="help_module.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		</td>
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
		$sql="delete from sma_help where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="help_module.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
			$code					= $_POST['code'];
			$description			= $_POST['description'];
			$program_name			= $_POST['program_name'];
			
			$status				= $_POST['status'];
			
  			$sql="insert into sma_help (code, description, program_name, status ) 
					Values( '$code', '$description', '$program_name', '$status' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Module Help successful added";
			echo '<script>window.location.href="help_module.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Module Help
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Module Help</a></li>
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
            <form class="form-horizontal" action="help_module.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Code </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="code" name="code" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="col-md-12">
                            <div class="box-header"><span class="box-title">Description</span></div>
                            <div class="box-body">
                                <textarea class="form-control" id="reason" name="description"
                                placeholder="Enter text ..."><?php echo stripslashes($row['description']);?></textarea>
                            </div>
									
						</div>
								
						<div class="form-group">
							<label class="col-lg-2 control-label">Program Name </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="program_name" name="program_name" placeholder="" value="<?php echo $row['program_name'];?>" >
							</div>
						</div>
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-8" style="padding-top: 6px;">
								
								<input type="radio" class="minimal"   name="status" id="status" checked value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal"  name="status" id="status" value="N" > Inactive &nbsp;
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
</section>  
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id						= $_POST['id']; 
			$code					= $_POST['code'];
			$description			= $_POST['description'];
			$program_name			= $_POST['program_name'];
			
			$status				= $_POST['status'];
			
  			$sql = "UPDATE sma_help SET 	code ='$code',
						description			= '$description',
						program_name		= '$program_name',
						status				= '$status'
					WHERE id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="help_module.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_help where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
    <section class="content-header">
        <h1>
            Module Help
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Module Help</a></li>
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
            <form class="form-horizontal" action="help_module.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Code </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="code" name="code" placeholder="" value="<?php echo $row['code'];?>" >
							</div>
						</div>
						
						<div class="col-md-12">
                            <div class="box-header"><span class="box-title">Description</span></div>
                            <div class="box-body">
                                <textarea class="form-control" id="reason" name="description"
                                placeholder="Enter text ..."><?php echo stripslashes($row['description']);?></textarea>
                            </div>
									
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Program Name </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="program_name" name="program_name" placeholder="" value="<?php echo $row['program_name'];?>" >
							</div>
						</div>
						
						<?php $status = $row['status']; ?>
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-8" style="padding-top: 6px;">
								
								<input type="radio" class="minimal"  <?php echo ($status=='E')?"CHECKED='CHECKED'":''; ?>  name="status" id="status" checked value="Y" > Active &nbsp;
                            	<input type="radio" class="minimal" <?php echo ($status=='E')?"CHECKED='CHECKED'":''; ?> name="status" id="status" value="N" > Inactive &nbsp;
						</div>
						
						
                        <div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/code.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
  </div>
</section>
      
<?php } 	?>


<?php 	
		include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

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
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });
	

</script>

</body>
</html>
