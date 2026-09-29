<?php

include("../header.php");
$modulePath = "setting/po_order_type.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        PO Doc Type
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">PO Doc Type</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of PO Doc Type</h3>
                <span class="pull-right"><a href="po_order_type.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create  </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>PO Doc Type</th>
			<th>PO Doc </th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from po_order_type";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'po_order_type.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "po_order_type.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['po_doc_type'];?></td>
		<td width="20%"><?php echo $row['po_doc_desc'];?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="po_order_type.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="po_order_type.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from po_order_type where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="po_order_type.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
		
			$po_doc_type		= $_POST['po_doc_type'];
			$po_doc_desc		= $_POST['po_doc_desc'];
			$terms				= $_POST['terms'];
  			$sql = "INSERT INTO po_order_type ( po_doc_type, po_doc_desc, terms) Values('$po_doc_type', '$po_doc_desc', '$terms' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			echo "PO Doc Type successful added";
			echo '<script>window.location.href="po_order_type.php?sub=list";</script>';
	}

?>

    <section class="content-header">
        <h1>
            PO Doc Type
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">PO Doc Type</a></li>
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
            <form class="form-horizontal" action="po_order_type.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Doc </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="po_doc_desc" name="po_doc_desc" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Doc Type</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="po_doc_type" name="po_doc_type" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header">
									<p><?= $label_line; ?></p>
								<span class="box-title">Special Terms & Condition</span></div>
								
								<div class="box-body">
									<textarea class="form-control" id="reason" name="terms"  ></textarea>
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

?>
	
<?php 
// echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$po_doc_type			= $_POST['po_doc_type'];
			$po_doc_desc			= $_POST['po_doc_desc'];
			$terms					= $_POST['terms'];
			
  			$sql="update po_order_type set 	po_doc_type ='$po_doc_type', 
									po_doc_desc = '$po_doc_desc',
									terms		= '$terms'
								where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="po_order_type.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from po_order_type where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            PO Doc Type
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">PO Doc Type</a></li>
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
            <form class="form-horizontal" action="po_order_type.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Doc </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="po_doc_desc" name="po_doc_desc" placeholder="" autocomplete="off" value="<?php echo $row['po_doc_desc'];?>">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Doc Type</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="po_doc_type" name="po_doc_type" placeholder="" value="<?php echo $row['po_doc_type'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header">
									<p><?= $label_line; ?></p>
								<span class="box-title">Special Terms & Condition</span></div>
								
								<div class="box-body">
									<textarea class="form-control" id="reason" name="terms"  ><?= $row['terms'];?></textarea>
								</div>
							
							</div>
						</div>
						
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
									$po_doc_type = $row['po_doc_type'];
								//Mrunmayee started
								
								$sq4 = "SELECT COUNT(*) as total FROM `sma_purchase_order` where po_doc_type = '$po_doc_type' ";
							//echo $sq4;	
								$q4  = mysqli_query($con, $sq4);
								$r4  = mysqli_fetch_assoc($q4);
								$mycount = $r4['total'];

								if ($mycount <= 0 ) {?>
								<a href="<?php echo $baseurl."setting/po_order_type.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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


<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
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
        CKEDITOR.replace('reason3');
		
    });
	
</script>

</body>
</html>
