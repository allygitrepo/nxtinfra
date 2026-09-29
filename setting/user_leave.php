<?php

include("../header.php");
$modulePath = "setting/user_leave.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User Leave
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User Leave</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User Leave</h3>
                <span class="pull-right"><a href="user_leave.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create User Leave </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>User Name </th>
			<th>Start Date </th>
			<th>To Date </th>
			<th>Dedicate to User Name </th>
			<th>Status </th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$sql = " SELECT * from sma_user_leave where 1 ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$user_id 		=	$row['user_id'];
		$start_date 	=	date('d-m-Y', strtotime($row['start_date']));
		$to_date 		=	date('d-m-Y', strtotime($row['to_date']));
		$dedicate_to_user	= $row['dedicate_to_user'];
		
		if($start_date=='01-01-1970'){
			$start_date= '';	
		}	
		
		if($to_date=='01-01-1970'){
			$to_date= '';	
		}
		
		$sql = " SELECT * from sma_user where 1 and id = '$user_id' ";
		$qry = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($qry);
		$user_name	= $r2['username'];
		
		$sql = " SELECT * from sma_user where 1 and id = '$dedicate_to_user' ";
		$qry = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($qry);
		$dedicate_to_user_name	= $r2['username'];
	
		$status 	= $row['status'];
		if($status =='Y'){
			$status = 'Active';
		}
		else {
			$status = 'Inactive';
		}
		
		$baseurl1 = $baseurl.$modulePath1.'user_leave.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "user_leave.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="30%"><?php echo $user_name;?></td>
		<td width="10%"><?php echo $start_date;?></td>
		<td width="10%"><?php echo $to_date;?></td>
		<td width="30%"><?php echo $dedicate_to_user_name;?></td>
		
		<td width="10%"><?php echo $status;?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="user_leave.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="user_leave.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->		
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
		$sql="delete from sma_user_leave where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="user_leave.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
		
			$user_id			= $_POST['user_id'];
			$start_date 		=	date('Y-m-d', strtotime($_POST['start_date']));
			$to_date 			=	date('Y-m-d', strtotime($_POST['to_date']));
			$dedicate_to_user 	= $_POST['dedicate_to_user'];
			$status				= 'Y';
			
  			$sql = "INSERT INTO sma_user_leave (user_id, start_date, to_date , dedicate_to_user, status ) 
					VALUES( '$user_id', '$start_date', '$to_date', '$dedicate_to_user', '$status')";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "User Leave successful added";
			echo '<script>window.location.href="user_leave.php?sub=list";</script>';
		}

?>

    <section class="content-header">
        <h1>
            User Leave
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User Leave</a></li>
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
            <form class="form-horizontal" action="user_leave.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">User Name *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="user_id" id="user_id" required >
									<option value=""> Select </option>
							<?php		
									$sql = " SELECT * from sma_user where 1 and active = '1' order by username ";
									$qry = mysqli_query($con, $sql);
									while($r2  = mysqli_fetch_array($qry)){
									$user_name	= $r2['username'];
							?>		
									<option value="<?= $r2['id'];?>"><?= $user_name;?></option>
									
							<?php } ?>		
								</select>
							</div>

							<label for="company_id" class="control-label col-sm-2">Dedicate to User Name *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="dedicate_to_user" id="dedicate_to_user" required >
									<option value=""> Select </option>
							<?php		
									$sql = " SELECT * from sma_user where 1 and active = '1' order by username ";
									$qry = mysqli_query($con, $sql);
									while($r2  = mysqli_fetch_array($qry)){
									$user_name	= $r2['username'];
							?>		
									<option value="<?= $r2['id'];?>"><?= $user_name;?></option>
									
							<?php } ?>		
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" required autocomplete="off" value="" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
								</div>
									
							</div>
							
							<label class="col-lg-2 control-label">To Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" autocomplete="off" required value="" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
								</div>
									
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
			$user_id		= $_POST['user_id'];
			$start_date 	=	date('Y-m-d', strtotime($_POST['start_date']));
			$to_date 		=	date('Y-m-d', strtotime($_POST['to_date']));
			$dedicate_to_user 	= $_POST['dedicate_to_user'];
			$status				= $_POST['status'];
			
  			$sql="update sma_user_leave set 	user_id ='$user_id',
						start_date 			= '$start_date',
						to_date 			= '$to_date',
						dedicate_to_user	= '$dedicate_to_user',
						status				= '$status'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="user_leave.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_user_leave where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
    <section class="content-header">
        <h1>
            User Leave
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User Leave</a></li>
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
            <form class="form-horizontal" action="user_leave.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">User Name *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="user_id" id="user_id" required >
									<option value=""> Select </option>
							<?php		
									$sql = " SELECT * from sma_user where 1 and active = '1' order by username ";
									$qry = mysqli_query($con, $sql);
									while($r2  = mysqli_fetch_array($qry)){
									$user_name	= $r2['username'];
							?>		
									<option value="<?= $r2['id'];?>" <?php echo ($row['user_id'] == $r2['id'])?'selected="selected"':'';?>  ><?= $user_name;?></option>
									
							<?php } ?>		
								</select>
							</div>

							<label for="company_id" class="control-label col-sm-2">Dedicate to User Name *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="dedicate_to_user" id="dedicate_to_user" required >
									<option value=""> Select </option>
							<?php		
									$sql = " SELECT * from sma_user where 1 and active = '1' order by username ";
									$qry = mysqli_query($con, $sql);
									while($r2  = mysqli_fetch_array($qry)){
									$user_name	= $r2['username'];
							?>		
									<option value="<?= $r2['id'];?>" <?php echo ($row['dedicate_to_user'] == $r2['id'])?'selected="selected"':'';?>  ><?= $user_name;?></option>
									
							<?php } ?>		
								</select>
							</div>
							
						</div>
						
						<?php 
							
							$start_date 		= date('d-m-Y', strtotime($row['start_date']));
							$to_date 		= date('d-m-Y', strtotime($row['to_date']));
							if($start_date=='01-01-1970'){
								$start_date ='';
							}	
							if($to_date=='01-01-1970'){
								$to_date ='';
							}
						?>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">From Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="start_date" name="start_date" required autocomplete="off" value="<?= $start_date;?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
									
							</div>
							
							<label class="col-lg-2 control-label">To Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="to_date" name="to_date" autocomplete="off" required value="<?= $to_date;?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
									
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
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `file_uploads` where doc_type = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/document.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
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
