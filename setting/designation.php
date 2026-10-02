<?php

include("../header.php");
$modulePath = "setting/designation.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Designation
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Designation</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Designation</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=desig" class="btn btn-primary">Report</a></span>
                <span class="pull-right"><a href="designation.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Designation </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Designation</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$sql="SELECT * from sma_designation";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$baseurl1 = $baseurl.$modulePath1.'designation.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "designation.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['designation'];?></td>
	
		
		<td width="10%" style="text-align:right;">
		<a href="designation.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--<a href="designation.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_designation where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="designation.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>


<?php
	if(isset($_POST['Save'])){
			$designation	= $_POST['designation'];
			//$travel_per_diem= $_POST['travel_per_diem'];
            $travel_per_diem='';

            $sql = " SELECT * FROM sma_designation where 1 and designation = '$designation' ";
    		mysqli_query($con, $sql);
    		$rowaffect = mysqli_affected_rows($con);
    		if($rowaffect>0){
    		    echo "<script>alert('Error: Designation Already available ...');</script>";
    		    echo '<script>window.location.href="designation.php?sub=add";</script>';
    		    exit();
    		}
    		
  			$sql="insert into sma_designation (designation)	Values( '$designation' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Designation successful added";
			echo '<script>window.location.href="designation.php?sub=list";</script>';
	}

?>

    <section class="content-header">
	</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Designation</a></li>
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
            <form class="form-horizontal" action="designation.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Designation</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="designation" name="designation" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
						</div>
						
						<!--<div class="form-group">-->
						<!--	<label class="col-lg-2 control-label">Daily Allowance <BR>(During Official Travel)</label>-->
						<!--	<div class="col-md-3">-->
						<!--		<input type="text" class="form-control" id="travel_per_diem" name="travel_per_diem" placeholder="" autocomplete="off" value="">-->
						<!--	</div>-->
						<!--</div>-->

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
			$designation	= $_POST['designation'];
			$designation_prev	= $_POST['designation_prev'];
			
			if($designation_prev !=$designation){
			    $sql="SELECT * FROM sma_designation where 1 and designation ='$designation' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Designation Already available ...');</script>";
        		    echo "<script>window.location.href='designation.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
		//	$travel_per_diem= $_POST['travel_per_diem'];
            $travel_per_diem='';
  			$sql="update sma_designation set 	designation ='$designation',
					travel_per_diem ='$travel_per_diem'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="designation.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_designation where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Designation
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Designation</a></li>
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
            <form class="form-horizontal" action="designation.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Designation</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="designation" name="designation" placeholder="" value="<?php echo $row['designation'];?>" >
								
								<input type="hidden"  name="designation_prev"  value="<?php echo $row['designation'];?>" >
								
							</div>
						</div>
						
						<!--<div class="form-group">-->
						<!--	<label class="col-lg-2 control-label">Daily Allowance <BR>(During Official Travel)</label>-->
						<!--	<div class="col-md-3">-->
						<!--		<input type="text" class="form-control" id="travel_per_diem" name="travel_per_diem" placeholder="" autocomplete="off" value="<?php echo $row['travel_per_diem'];?>">-->
						<!--	</div>-->
						<!--</div>-->
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
								<a href="designation.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started 
								$sq2 = "SELECT COUNT(*) as total FROM `sma_user` where designation = '$did'";
								$q2  = mysqli_query($con, $sq2);
				
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/designation.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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

    function getDuplicate(id){
		var sub    = 'sub5';
		var table_name = 'sma_designation';
		var col_name   = 'designation';
//alert(sub);
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "account_func.php";
		$.post(strURL,{id:id,col_name:col_name,table_name:table_name,sub5:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}

</script>

</body>
</html>
