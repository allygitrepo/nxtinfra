<?php
	include("../header.php");
	$modulePath = "vendor/states.php?sub=list";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">
    <section class="content-header">
      <h1>
        States
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">States</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of States</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="vendor_export.php?sub=states" class="btn btn-primary">Export</a></span>
                <span class="pull-right"><a href="states.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create States</a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>States</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from states";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
?>

    <a href="states.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='states.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="20%"><?php echo $row['state_name'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="states.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--		<a href="states.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from states where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="states.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>
  
<?php
	if(isset($_POST['Save'])){
			$state_name	= $_POST['state_name'];

  			$sql="insert into states (state_name) 
					Values('$state_name')";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//echo "States successful added";
			echo '<script>window.location.href="states.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            States
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">States</a></li>
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
            <form class="form-horizontal" action="states.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">States</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="state_name" name="state_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="states.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
      </div>
</section>	  
	  
  <!-- /.content-wrapper -->
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>
  
<?php

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$state_name	= $_POST['state_name'];

  			$sql="update states set state_name ='$state_name' where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="states.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from states where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <!-- Content Header (Page header) -->
        <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
           
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
        <!-- form start -->
            <form class="form-horizontal" action="states.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">States</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="state_name" name="state_name" placeholder="" value="<?php echo $row['state_name'];?>" >
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="states.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
                //Mrunmayee started
                $sq2 = "SELECT COUNT(*) as total FROM `sma_party_mst` where party_state = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."vendor/states.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
                <?php }//Mrunmayee ended ?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>

                    </fieldset>
            </form>
			</div>	
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

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

</body>
</html>
