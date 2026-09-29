<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "product_subgroup.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Product Sub Group
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Product Sub Group</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Product Sub Group</h3>
                <span class="pull-right"><a href="product_subgroup.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Product Sub Group </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Product Group</th>
			<th>Product Sub Group</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT  b.id, b.description as 'group', a.id, a.description as 'sub_group', a.group_code from sma_product_subgroup a, sma_product_group b where b.id = a.group_code order by b.description ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
?>

    <a href="product_subgroup.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='product_subgroup.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="20%"><?php echo $row['group'];?></td>
		<td width="20%"><?php echo $row['sub_group'];?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="product_subgroup.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="product_subgroup.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
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
		$sql="delete from sma_product_subgroup where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="product_subgroup.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){
			$group_code		= $_POST['group_code'];
			$description	= $_POST['description'];

  			$sql="insert into sma_product_subgroup (group_code, description) 
					Values('$group_code', '$description')";
				
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Product Sub Group successful added";
			echo '<script>window.location.href="product_subgroup.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Product Sub Group
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Product Sub Group</a></li>
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
            <form class="form-horizontal" action="product_subgroup.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group</label>
							<div class="col-md-3">
								<select class="form-control" name="group_code" id="group_code" required="true">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group order by description ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['group'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['description'];?></option>
									<?php } ?>
								</select>
								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Sub Group</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="description" name="description" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="product_subgroup.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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


<?php if($_GET['sub'] == 'edit'){
?>
	
<?php

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$group_code		= $_POST['group_code'];
			$description	= $_POST['description'];

  			$sql="update sma_product_subgroup set 	description ='$description', 
					group_code = '$group_code'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="product_subgroup.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_product_subgroup where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <section class="content-header">
        <h1>
            Product Sub Group
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Product Sub Group</a></li>
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
            <form class="form-horizontal" action="product_subgroup.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group</label>
							<div class="col-md-3">
								<select class="form-control" name="group_code" id="group_code" required="true">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group order by description ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['group_code'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['description'];?></option>
									<?php } ?>
								</select>
								
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Sub Group</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="description" name="description" placeholder="" value="<?php echo $row['description'];?>" >
							</div>
						</div>
						
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="product_subgroup.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
