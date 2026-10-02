<?php

include("../header.php");
$modulePath = "vendor/cities.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Cities
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Cities</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Cities</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="vendor_export.php?sub=cities" class="btn btn-primary">Export</a></span>
                <span class="pull-right"><a href="cities.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Cities </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>States</th>
			<th>Cities</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT  b.id, b.state_name, a.id, a.city_name , a.states_id from cities a, states b where b.id = a.states_id order by b.state_name ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
?>

    <a href="cities.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='cities.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="20%"><?php echo $row['state_name'];?></td>
		<td width="20%"><?php echo $row['city_name'];?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="cities.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--	<a href="cities.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
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
		$sql="delete from cities where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="cities.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){
			$states_id		= $_POST['states_id'];
			$city_name	= $_POST['city_name'];

  			$sql="insert into cities (states_id, city_name) 
					Values('$states_id', '$city_name')";
				
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//echo "Cities successful added";
			echo '<script>window.location.href="cities.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Cities
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Cities</a></li>
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
            <form class="form-horizontal" action="cities.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">States</label>
							<div class="col-md-3">
								<select class="form-control" name="states_id" id="states_id" required="true">
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['states_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
									<?php } ?>
								</select>
								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Cities</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="city_name" name="city_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="cities.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$states_id		= $_POST['states_id'];
			$city_name	= $_POST['city_name'];

  			$sql="update cities set 	city_name ='$city_name', 
					states_id = '$states_id'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="cities.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from cities where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <section class="content-header">
        <h1>
            Cities
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Cities</a></li>
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
            <form class="form-horizontal" action="cities.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">States</label>
							<div class="col-md-3">
								<select class="form-control" name="states_id" id="states_id" required="true">
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['states_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
									<?php } ?>
								</select>
								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Cities</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="city_name" name="city_name" placeholder="" value="<?php echo $row['city_name'];?>" >
							</div>
						</div>
						
                       <!-- <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="cities.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `sma_party_mst` where party_city = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."vendor/cities.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
								<?php } //Mrunmayee ended?>
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
