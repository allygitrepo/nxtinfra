<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "accounts_year.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Account Year
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Account Year</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Account Year</h3>
                <span class="pull-right"><a href="accounts_year.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Account Year </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

    <thead>
    <tr>
        <th>Years</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th style="text-align:right;">Action</th>	
    </tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from accounts_year ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){

?>

	<tr>

		<td width="34%"><?php echo $row['year'];?></td>
		<td width="34%"><?php echo date('d-m-Y', strtotime($row['ac_start_date']));?></td>
		<td width="34%"><?php echo date('d-m-Y', strtotime($row['ac_end_date']));?></td>
		<td width="10%" style="text-align:right;">
		<a href="accounts_year.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="accounts_year.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
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
		$sql="update accounts_year set del = 'Y' where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="accounts_year.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(($_POST['Save'])){
			$id	= $_POST['id'];
			$year	= date('Ym', strtotime($_POST['ac_start_date'])).date('Ym', strtotime($_POST['ac_end_date']));
			$ac_start_date		= date('Y-m-d', strtotime($_POST['ac_start_date']));
			$ac_end_date		= date('Y-m-d', strtotime($_POST['ac_end_date']));			
			
  			$sql="insert into accounts_year(year, ac_start_date, ac_end_date) Values( '$year', '$ac_start_date', '$ac_end_date')";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){
				
				echo '<div class="content-wrapper"><div class="col-md-12">';
				echo $error; 
				echo '.... Duplicate entry error !!! <br><br>'; 
				echo '<a href="accounts_year.php?sub=add" > <h3>Click here </3></a>';
				echo '</div></div>';
				
				exit();
				}
			
			echo "Accounts Year  successful added";
			echo '<script>window.location.href="accounts_year.php?sub=list";</script>';
		}

?>

    <section class="content-header">
        <h1>
            Account Year
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account Year</a></li>
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
            <form class="form-horizontal" action="accounts_year.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<!--<div class="form-group">
							<label class="col-lg-2 control-label">Years</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="year" name="year" readonly value="<?php echo $row['year'];?>" >
							</div>
						</div>-->
						
						<div class="form-group">
							<label class="col-lg-2 control-label"> Start Date</label>
								<div class="col-lg-2">
									<input type="text" class="form-control" id="start_date" name="ac_start_date"  value="<?php echo $row['ac_start_date'];?>" >
							</div>
							
							<label class="col-lg-2 control-label"> End Date</label>
								<div class="col-lg-2">
									<input type="text" class="form-control" id="end_date" name="ac_end_date" value="<?php echo $row['ac_end_date'];?>" >
							</div>	
						</div>
						
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="accounts_year.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
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


<?php 	
    if($_GET['sub'] == 'edit'){

		if(isset($_POST['Save'])){
			
			$id			= $_POST['id']; 
			//$year		= $_POST['year'];

			$year_prev	= $_POST['year_prev'];
			$year_cur	= date('Ym', strtotime($_POST['ac_start_date'])).date('Ym', strtotime($_POST['ac_end_date']));

			$ac_start_date		= date('Y-m-d', strtotime($_POST['ac_start_date']));
			$ac_end_date		= date('Y-m-d', strtotime($_POST['ac_end_date']));

  			$sql="update accounts_year set 
					ac_start_date	='$ac_start_date',
					ac_end_date		='$ac_end_date'
				where id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){
				echo '<div class="content-wrapper"><div class="col-md-12">';
				echo $error; 
				echo '</div></div>';
				exit();
			}
			
			echo '<script>window.location.href="accounts_year.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from accounts_year where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);
		
		$year_prev	= $row['year'];
?>
    <section class="content-header">
        <h1>
            Account Year
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account Year</a></li>
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
            <form class="form-horizontal" action="accounts_year.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
				
						<input type="hidden" name="year_prev" value="<?php echo $year_prev;?>" >
						
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Years</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="year" name="year" readonly value="<?php echo $row['year'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label"> Start Date</label>
								<div class="col-lg-2">
									<input type="text" class="form-control" id="start_date" name="ac_start_date" value="<?php echo date('d-m-Y', strtotime($row['ac_start_date']));?>" >
							</div>
							
							
							<label class="col-lg-2 control-label"> End Date</label>
								<div class="col-lg-2">
									<input type="text" class="form-control" id="end_date" name="ac_end_date"  value="<?php echo date('d-m-Y', strtotime($row['ac_end_date']));?>" >
							</div>
							
						</div>
						
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="accounts_year.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>
                         
                    </fieldset>
			  
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
        <!--/.content-wrapper -->
    </div>
  </div>
</section>
	
<?php } ?>


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
