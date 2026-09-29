<?php
	include("../header.php");
	$modulePath = "product/units.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">
    <section class="content-header">
      <h1>
        Units
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Units</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Units</h3>
                <span class="pull-right"><a href="units.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Units</a></span>
                <!-- Ruchi started-->
                <span class="pull-right">
					        <a href="product_export.php?sub=unit" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				        </span>
                <!-- Ruchi ended-->
              </div>
            <!-- /.box-header -->
            <div class="box-body">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Units</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_units";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
?>

    <a href="units.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='units.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="20%"><?php echo $row['name'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="units.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--		<a href="units.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
			$sql	="Select * from sma_units where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$name 			= $row['name'];
			
			$company_id 	= '';
			$pgname 		= "units.php";
			include "../viewonly.php";
			$description 	= $name;
			$user_name		= $_SESSION['user'];
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from sma_units where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="units.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>
  
<?php
	if(isset($_POST['Save'])){
			$name	= $_POST['name'];
            
            $sql="SELECT * FROM sma_units where 1 and name = '$name' ";
        	mysqli_query($con, $sql);
        	$rowaffect = mysqli_affected_rows($con);
        	if($rowaffect>0){
        		    echo "<script>alert('Error: Units Already available ...');</script>";
        		    echo '<script>window.location.href="units.php?sub=add";</script>';
        		    exit();
        	}
        		
  			$sql="insert into sma_units (name) 
					Values('$name')";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "units.php";
			include "../viewonly.php";
			$description 	= $name;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Units successful added";
			echo '<script>window.location.href="units.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Units
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Units</a></li>
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
            <form class="form-horizontal" action="units.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Units</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="name" name="name" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
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
			$name		= trim($_POST['name']);
			$name_prev		= trim($_POST['name_prev']);
            if($name_prev !=$name){
			    $sql="SELECT * FROM sma_units where 1 and name ='$name' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Units Already available ...');</script>";
        		    echo "<script>window.location.href='units.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
  			$sql="update sma_units set 	name ='$name'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "units.php";
			include "../viewonly.php";
			$description 	= $name;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="units.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_units where id ='$id'";
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
            <form class="form-horizontal" action="units.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Units</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="name" name="name" placeholder="" value="<?php echo $row['name'];?>" >
								
								<input type="hidden"  name="name_prev"  value="<?php echo $row['name'];?>" >
								
							</div>
						</div>
						
                     	<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
                //Mrunmayee started
                $n = $row['name'];
                $sql="SELECT * FROM `sma_product` where uom='$n'";
                $query=mysqli_query($con, $sql);
                $error= mysqli_error($con);
                $row= mysqli_fetch_array($query);
                if($row <=0){?>
								<a href="<?php echo $baseurl."product/units.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
                <?php } //Mrunmayee ended ?>
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
  
   function getDuplicate(id){
		var sub    = 'sub9';
//alert(sub);
        var table_name = 'sma_units';
        var col_name = 'name';
        
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "account_func.php";
		$.post(strURL,{id:id,col_name:col_name,table_name:table_name,sub9:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}

</script>

</body>
</html>
