<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "dms/courier_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Courier Master
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Courier Master</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Courier Name</h3>
                <span class="pull-right"><a href="courier_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Courier Name </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Name </th>
			<th>Mobile </th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "dms/";
	$sql="SELECT * from sma_courier_mst order by courier_name ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'courier_mst.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "courier_mst.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="30%"><?php echo $row['courier_name'];?></td>
		<td width="10%"><?php echo $row['mobile'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="courier_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="courier_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_courier_mst where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="courier_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
			$courier_name	= $_POST['courier_name'];
			$address		= $_POST['address'];
			$mobile	= $_POST['mobile'];
			$status	= $_POST['status'];
			
  			$sql="insert into sma_courier_mst (courier_name, address, mobile, status) 
				Values( '$courier_name', '$address', '$mobile', '$status' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Courier Master successful added";
			echo '<script>window.location.href="courier_mst.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Courier Master
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Courier Master</a></li>
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
            <form class="form-horizontal" action="courier_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Name </label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="courier_name" name="courier_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="address" name="address" placeholder="" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Mobile </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="mobile" name="mobile" placeholder="" value="" >
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
</section>  
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$courier_name	= $_POST['courier_name'];
			$address		= $_POST['address'];
			$mobile			= $_POST['mobile'];
			$status			= $_POST['status'];
			
  			$sql="update sma_courier_mst set 	courier_name ='$courier_name',
						address	= '$address',
						mobile	= '$mobile',
						status	= '$status'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="courier_mst.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_courier_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
    <section class="content-header">
        <h1>
            Courier Master
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Courier Master</a></li>
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
            <form class="form-horizontal" action="courier_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Name </label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="courier_name" name="courier_name" placeholder="" value="<?php echo $row['courier_name'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="address" name="address" placeholder="" value="<?php echo $row['address'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Mobile </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="mobile" name="mobile" placeholder="" value="<?php echo $row['mobile'];?>" >
							</div>
						</div>
						
                        <div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."dms/courier_name.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
