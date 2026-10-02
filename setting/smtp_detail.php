<?php

include("../header.php");
$modulePath = "setting/smtp_detail.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        SMTP Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">SMTP Detail</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">SMTP Detail</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=smtp" class="btn btn-primary">Export</a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th> Host</th>
			<th>User Name</th>

			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$sql="SELECT * from smtp_dtl";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$baseurl1 = $baseurl.$modulePath1.'smtp_detail.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "smtp_detail.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['host'];?></td>
		<td width="70%"><?php echo $row['username'];?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="smtp_detail.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="smtp_detail.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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


<?php if($_GET['sub'] == 'edit'){

?>
	
<?php 
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$host			= $_POST['host'];
			$username		= $_POST['username'];
			$password		= $_POST['password'];
			$port			= $_POST['port'];
			
  			$sql="update smtp_dtl set 	host ='$host',  
					username ='$username',
					password ='$password',
					port ='$port'";
					
				//where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="smtp_detail.php?sub=list";</script>';
	}
		
		//$id = $_GET['id'];
		$sql="Select * from smtp_dtl";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            SMTP Detail
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">SMTP Detail</a></li>
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
            <form class="form-horizontal" action="smtp_detail.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">	
							<label for="project" class="col-lg-2 control-label">HOST</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="host" name="host" value="<?php echo $row['host'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Password</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="password" name="password" value="<?php echo $row['password'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Port</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="port" name="port" value="<?php echo $row['port'];?>" >
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
