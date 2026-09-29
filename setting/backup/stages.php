<?php

include("../header.php");
$modulePath = "setting/stages.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Stages
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Stages</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Stages</h3>
                <span class="pull-right"><a href="stages.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Stages </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Document</th>
			<th>Stage</th>
			<th>Description</th>
			<th>Responsible User</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_stages";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
?>

	<tr>

		<td width="20%"><?php echo $row['document_id'];?></td>
		<td width="30%"><?php echo $row['stage_id'];?></td>
		<td width="20%"><?php echo $row['stage_description'];?></td>
		<td width="20%"><?php echo $row['responsible_user_id'];?></td>

		<td width="10%" style="text-align:right;">
		<a href="stages.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>	
<!--<a href="stages.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_stages where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="stages.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			$document_id		= $_POST['document_id'];
			$stage_id			= $_POST['stage_id'];
			$stage_description	= $_POST['stage_description'];
			$responsible_user_id	= $_POST['responsible_user_id'];

  			$sql="insert into sma_stages (document_id, stage_id, stage_description, responsible_user_id) 
					Values('$document_id', '$stage_id', '$stage_description', '$responsible_user_id')";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Stages successful added";
			echo '<script>window.location.href="stages.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Stages
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Stages</a></li>
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
            <form class="form-horizontal" action="stages.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Document</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="document_id" name="document_id" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Stage</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="stage_id" name="stage_id" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="stage_description" name="stage_description" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Responsible User</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="responsible_user_id" name="responsible_user_id" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="stages.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
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
</section>  
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

?>
	
<?php 
 echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$document_id			= $_POST['document_id'];
			$stage_id 		= $_POST['stage_id'];
			$stage_description 	= $_POST['stage_description'];
			$responsible_user_id 			= $_POST['responsible_user_id'];

  			$sql="update sma_stages set 	document_id ='$document_id',
							stage_id 	= '$stage_id',
							stage_description = '$stage_description',
							responsible_user_id 		= '$responsible_user_id'
				where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="stages.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_stages where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Stages
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Stages</a></li>
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
            <form class="form-horizontal" action="stages.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Document</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="project" name="document_id" placeholder="" value="<?php echo $row['document_id'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Stage</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="stage_id" name="stage_id" placeholder="" value="<?php echo $row['stage_id'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="stage_description" name="stage_description" placeholder="" value="<?php echo $row['stage_description'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Responsible User</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="responsible_user_id" name="responsible_user_id" placeholder="" value="<?php echo $row['responsible_user_id'];?>" >
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="stages.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/stages.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

</body>
</html>
