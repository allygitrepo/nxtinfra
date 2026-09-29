<!DOCTYPE html>
<?php
	include("../header.php");
	$modulePath = "product/product_group.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">
    <section class="content-header">
      <h1>
        Material Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Material Category</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Material Category</h3>
		<?php
			$user   = $_SESSION['user'];
			if($user=='Admin'){  
        ?>
			<span class="pull-right"><a href="product_group.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Material Category</a></span>
		<?php
			}
		?>			
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Material Category</th>
			<th>Budget Head</th>
			<th>Budget Name</th>
			<th>Status</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_product_group";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		
		$budget_category = $row['budget_head'];
		$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		
		$category = $r2['category'];
		
		$budget_name = $row['budget_name'];
		$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		
		$bname = $r2['name'];
		
		$status = $row['status'];
		if($status=='1' ){
			$status = 'Active';
		}
		else if(empty($status)){
			$status = 'InActive';
		}
?>

    <a href="product_group.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='product_group.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="20%"><?php echo $row['description'];?></td>
		<td width="20%"><?php echo $category;?></td>
		<td width="20%"><?php echo $bname;?></td>
		<td width="10%"><?php echo $status;?></td>
		<td width="10%" style="text-align:right;">
		<a href="product_group.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--		<a href="product_group.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
			
			$company_id 	= '';
			$sql	="Select * from sma_product_group where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$budget_head 			= $row['budget_head'];
			$budget_name 			= $row['budget_name'];
			$description 			= $row['description'];
			$pgname 		= "product_group.php";
			include "../viewonly.php";
			$description 	= $budget_head.', '. $budget_name. ','. $description;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Deleted';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="DELETE FROM sma_product_group where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="product_group.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>
  
<?php
	if(isset($_POST['Save'])){
			$description	= $_POST['description'];
			$budget_name			= $_POST['budget_name'];
			$budget_head			= $_POST['budget_head'];
			$status					= $_POST['status'];


  			$sql="insert into sma_product_group (description, budget_name, budget_head , status) 
					Values('$description', '$budget_name','$budget_head', '$status' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "product_group.php";
			include "../viewonly.php";
			$description 	= $budget_head.', '. $budget_name. ','. $description;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Added';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Material Category successful added";
			echo '<script>window.location.href="product_group.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Material Category
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Material Category</a></li>
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
            <form class="form-horizontal" action="product_group.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Category</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="description" name="description" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                    <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="product_group.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						
						<div class="form-group">

							
								<span id="getbudgetname">
									<label class=" col-md-2 control-label">Budget Name </label>
									<div class="col-md-2">
									<select class="form-control" name="budget_name" id="budget_Name" onchange="getbudget(this.value)"  <?php echo $readonly; ?> >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_budget_name  order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
									
									</div>
								</span>
								

								<span id="getbudgethead123">
										
									<label class=" col-md-2 control-label">Budget Head</label>
									<div class="col-md-3">
									<select class="form-control" name="budget_head" id="budget_head" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_budget_category  order by category ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['category'];?></option>
											<?php } ?>
									</select>
									</div>
										
								</span>
								
						</div>	
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-3" style="padding-top: 6px;">
								<input type="radio" class="minimal"  checked name="status" id="status" value="1" >Active &nbsp;
                            	<input type="radio"  class="minimal"  name="status" id="status" value="" >In-Active &nbsp;
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
	  
  <!-- /.content-wrapper -->
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>
  
<?php

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$description	= $_POST['description'];
			$budget_name			= $_POST['budget_name'];
			$budget_head			= $_POST['budget_head'];
			$status					= $_POST['status'];



  			$sql="update sma_product_group set 	budget_name	= '$budget_name',
												budget_head	= '$budget_head',
												description = '$description',
												status		= '$status'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "product_group.php";
			include "../viewonly.php";
			$description 	= $budget_head.', '. $budget_name. ','. $description;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Modified';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="product_group.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_product_group where id ='$id'";
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
            <form class="form-horizontal" action="product_group.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Category</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="description" name="description" placeholder="" value="<?php echo $row['description'];?>" >
							</div>
						</div>
						
                       <!-- <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="product_group.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

						<div class="form-group">
							
								<span id="getbudgetname">
									<label class=" col-md-2 control-label">Budget Name </label>
									<div class="col-md-2">
									<select class="form-control" name="budget_name" id="budget_Name" onchange="getbudget(this.value)"  <?php echo $readonly; ?> >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_budget_name  order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
									
									</div>
								</span>
								

								<span id="getbudgethead123">
										
									<label class=" col-md-2 control-label">Budget Head</label>
									<div class="col-md-3">
									<select class="form-control" name="budget_head" id="budget_head" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_budget_category  order by category ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['category'];?></option>
											<?php } ?>
									</select>
									</div>
										
								</span>
								
						</div>	
						
						<?php 
								$status = $row['status'];
								if($status=='1' ){
									$selected_active = 'checked';
								}
								else if(empty($status)){
									$selected_inactive = 'checked';
								}
						?>		
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-3" style="padding-top: 6px;">
								<input type="radio" class="minimal"  <?php echo $selected_active; ?> name="status" id="status" value="1" >Active &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_inactive; ?> name="status" id="status" value="" >In-Active &nbsp;
							</div>
						
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."product/product_group.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
