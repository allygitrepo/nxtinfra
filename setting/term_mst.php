<?php

include("../header.php");
$modulePath = "setting/term_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Term & Conditions
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Term & Conditions</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Term & Conditions</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=terms" class="btn btn-primary">Export</a></span>
                <span class="pull-right"><a href="term_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Term & Conditions </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Sequence No.</th>
			<th>Terms</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$sql="SELECT * from sma_term";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

?>

	<tr>
		<td width="10%"><?php echo $row['order_no'];?></td>
		<td width="70%"><?php echo $row['special_terms'];?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="term_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
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
		$sql="delete from sma_term where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="term_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			
			$order_no				= $_POST['order_no'];
			$special_terms			= $_POST['special_terms'];
			
  			$sql = "insert into sma_term (order_no, special_terms) Values( '$order_no', '$special_terms' )";

			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

//			echo "Term successful added";
			echo '<script>window.location.href="term_mst.php?sub=list";</script>';
		}

?>

    <section class="content-header">
        <h1>
            Term & Condition
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Term & Condition</a></li>
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
            <form class="form-horizontal" action="term_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<div class="col-md-12">
								<label  class="control-label">Special Terms<span style="color:red;">**</span></label>
								<input type="text" class="form-control" id="special_terms" name="special_terms" required >
							</div>
						</div>
							
						<div class="form-group">
							
							<div class="col-md-2">
								<label  class="control-label">Sequence No.<span style="color:red;">**</span></label>
								<input type="text" class="form-control" id="order_no" name="order_no" required style="text-align:right;" >
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

?>
	
<?php 
 
	if(isset($_POST['Save'])){
			$id						= $_POST['id']; 
			$order_no				= $_POST['order_no'];
			$special_terms			= $_POST['special_terms'];
			
  			$sql = "update sma_term set order_no = '$order_no',
						special_terms	= '$special_terms'
					where id = '$id' ";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="term_mst.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_term where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Term & Conditions
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Term & Conditions</a></li>
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
            <form class="form-horizontal" action="term_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			    <fieldset>
			  
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<div class="col-md-12">
								<label  class="control-label">Special Terms<span style="color:red;">**</span></label>
								<input type="text" class="form-control" id="special_terms" name="special_terms" required value="<?= $row['special_terms'];?>" >
							</div>
						</div>
							
						<div class="form-group">
							<div class="col-md-2">
								<label  class="control-label">Sequence No.<span style="color:red;">**</span></label>
								<input type="text" class="form-control" id="order_no" name="order_no" required value="<?= $row['order_no'];?>" style="text-align:right;" >
							</div>
						</div>
							
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/term_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>


<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
	
	$(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>

</body>
</html>
