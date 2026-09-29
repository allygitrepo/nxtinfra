<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "supp_invoice/supplier_invoice.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?><link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Supplier Invoice
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.$modulePath; ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Supplier Invoice</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Supplier Invoice List</h3>
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<span class="pull-right"><a href="supplier_invoice.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Invoice </a></span>
				</span>
			</div>
			</div>
		</div>	
	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Dated</th>
			<th>Supp.Inv.No.</th>
			<th>Our PO Ref.NO.</th>
			<th>Delivery Challen No</th>
			<th>Delivery Date</th>
			<th>Supplier Name</th>
			<th>Transport LR No.</th>
			
			<th style="text-align:right;">Action</th>
    
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_supplier_invoice";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];
?>

	<tr>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
		<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%"><?php echo $row['our_po_ref_no'];?></td>
		<td width="10%"><?php echo $row['delivery_challen_no'];?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['delivery_date']));?></td>
		<td width="20%"><?php echo $supplier_name;?></td>
		<td width="10%"><?php echo $row['transport_lr_no'];?></td>
	
		<td width="10%" style="text-align:right;">
		<a href="supplier_invoice.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="supplier_invoice.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
		</td>
    </tr>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

<!-- jQuery 2.2.3 -->
<script src="<?php echo $baseurl . "plugins/jQuery/jquery-2.2.3.min.js"?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $baseurl . "bootstrap/js/bootstrap.min.js"?>"></script>
<!-- DataTables -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"?>"></script>

<!-- SlimScroll -->
<script src=<?php echo $baseurl . "plugins/slimScroll/jquery.slimscroll.min.js"?>"></script>
<!-- FastClick -->
<script src="<?php echo $baseurl . "plugins/fastclick/fastclick.js"?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $baseurl . "dist/js/app.min.js"?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $baseurl . "dist/js/demo.js"?>"></script>
<!-- page script -->
<script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true
    });
	
  });
</script>

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_supplier_invoice where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$suplier_name			= $_POST['suplier_name'];
			$our_po_ref_no			= $_POST['our_po_ref_no'];
			$delivery_challen_no	= $_POST['delivery_challen_no'];
			$budget_head			= $_POST['budget_head'];
			$delivery_date			= date('Y-m-d', strtotime($_POST['delivery_date']));
			$transport_lr_no		= $_POST['transport_lr_no'];
			$lr_date				= date('Y-m-d', strtotime($_POST['lr_date']));
			$transporter_name		= $_POST['transporter_name'];
			$credit_days			= $_POST['credit_days'];
			$due_date				= date('Y-m-d', strtotime($_POST['due_date']));
			$state					= $_POST['state'];
//			$supplier_gst_no		= $_POST['supplier_gst_no'];
//			$grn_no					= $_POST['grn_no'];
			$file_attachment		= $_POST['file_attachment'];

  			$sql="insert into sma_supplier_invoice (supplier_invoice_no, invoice_date, our_po_ref_no, delivery_challen_no, delivery_date, suplier_name, transport_lr_no, lr_date, transporter_name, credit_days, due_date, state )
			Values('$supplier_invoice_no', '$invoice_date', '$our_po_ref_no', '$delivery_challen_no', '$delivery_date', '$suplier_name', '$transport_lr_no', '$lr_date', '$transporter_name', '$credit_days', '$due_date', '$state' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Supplier Invoice successful added";
			echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		}

?>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Supplier Invoice
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Supplier Invoice</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="supplier_invoice.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" placeholder="" value="<?php echo $row['id'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Supplier Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no" placeholder="" value="<?php echo $row['supplier_invoice_no'];?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
						</div>
						
						<div class="form-group">
						
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<select class="form-control" name="suplier_name" id="suplier_name" onchange="getporefno(this.value);">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Our PO Ref.No.</label>
								<span id="getporefno">
									<input type="text" class="form-control" id="our_po_ref_no" name="our_po_ref_no" placeholder="" value="" >
								</span>
							</div>
						
<!--							<div class="col-md-4">
								<label class="control-label">Budget Head</label>
								<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "SELECT a.id as id, b.category as category_name, a.budget_category as category FROM `sma_budget` a, sma_budget_category b where a.budget_category = b.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['category_name'];?></option>
										<?php } ?>
								</select>
							</div>
-->						
						</div>
						
						<!--<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Delivery Challen No.</label>
								<input type="text" class="form-control" id="delivery_challen_no" name="delivery_challen_no" placeholder="" value="<?php echo $row['delivery_challen_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Delivery Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>

							<div class="col-md-2">
								<label class="control-label">Delivery Mode</label>
								<select class="form-control" name="delivery_mode" id="delivery_mode" >
									<option value=""> Select </option>
									<option value="H" <?php echo ($row['delivery_mode'] == 'H')?'selected="selected"':'';?>> Hand Delivery </option>
									<option value="C" <?php echo ($row['delivery_mode'] == 'C')?'selected="selected"':'';?>> Courier </option>
								</select>	
							</div>

							<div class="col-md-2">
								<label class="control-label">Transport LR No.</label>
								<input type="text" class="form-control" id="transport_lr_no" name="transport_lr_no" placeholder="" value="<?php echo $row['transport_lr_no'];?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">LR Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="lr_date" name="lr_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
						
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Transporter Name</label>
								<input type="text" class="form-control" id="transporter_name" name="transporter_name" placeholder="" value="" >
							</div>
						
						</div>
						-->
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" placeholder="" value="" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="due_date" name="due_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
						
							<div class="col-md-2">
								<label class="control-label">State</label>
								<input type="text" class="form-control" id="state" name="state" placeholder="" value="" >
							</div>
							
						</div>
						
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="supplier_invoice.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$si_id			= $_POST['id'];
						
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$suplier_name			= $_POST['suplier_name'];
			$our_po_ref_no			= $_POST['our_po_ref_no'];
			$delivery_challen_no	= $_POST['delivery_challen_no'];
			$budget_head			= $_POST['budget_head'];
			$delivery_date			= date('Y-m-d', strtotime($_POST['delivery_date']));
			$transport_lr_no		= $_POST['transport_lr_no'];
			$lr_date				= date('Y-m-d', strtotime($_POST['lr_date']));
			$transporter_name		= $_POST['transporter_name'];
			$credit_days			= $_POST['credit_days'];
			$due_date				= date('Y-m-d', strtotime($_POST['due_date']));
			$state					= $_POST['state'];
//			$supplier_gst_no		= $_POST['supplier_gst_no'];
//			$grn_no					= $_POST['grn_no'];

  			$sql="update sma_supplier_invoice set supplier_invoice_no	= '$supplier_invoice_no',
						invoice_date				= '$invoice_date',
						our_po_ref_no				= '$our_po_ref_no',
						delivery_challen_no			= '$delivery_challen_no',
						delivery_date				= '$delivery_date',
						suplier_name				= '$suplier_name',
						transport_lr_no				= '$transport_lr_no',
						lr_date						= '$lr_date',
						transporter_name			= '$transporter_name',
						state						= '$state',
						budget_head					= '$budget_head',
						credit_days					= '$credit_days',
						due_date					= '$due_date'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];

			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/si/" . $si_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];

				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('SI', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $si_id . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/si/" . $si_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
					
					echo $sql;
					
				}
			}
			
			echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		}
		
		$id	 = $_GET['id'];
		$si_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';
//			header('Location: '.$_SERVER['REQUEST_URI']);
		}
		
		$sql="Select * from sma_supplier_invoice where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Supplier Invoice
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Supplier Invoice</a></li>
            
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
					
            <form class="form-horizontal" action="supplier_invoice.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				
			<ul class="nav nav-tabs">
				  <li class="<?php echo $active_tab1; ?>"><a href="#tab_1" data-toggle="tab">Supplier Invoice</a></li>
				  <li  class="<?php echo $active_tab2; ?>"><a href="#tab_2" data-toggle="tab">Material Details</a></li>
				  <li  class="<?php echo $active_tab3; ?>"><a href="#tab_3" data-toggle="tab">Document</a></li>
				  
				  <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane <?php echo $active_tab1;?>" id="tab_1">
					
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" placeholder="" value="<?php echo $row['id'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Supplier Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no" placeholder="" value="<?php echo $row['supplier_invoice_no'];?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date" placeholder="" value="<?php echo date('d-m-Y', strtotime($row['invoice_date']));?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<select class="form-control" name="suplier_name" id="suplier_name" onchange="getporefno(this.value);">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Our PO Ref.No.</label>
								<span id="getporefno">
									<input type="text" class="form-control" id="our_po_ref_no" name="our_po_ref_no" placeholder="" value="<?php echo $row['our_po_ref_no'];?>" >
								</span>
							</div>
						
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" placeholder="" value="<?php echo $row['credit_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="due_date" name="due_date" placeholder="" value="<?php echo $row['due_date'];?>" >
								
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
							<div class="col-md-2">
								<label class="control-label">State</label>
								<input type="text" class="form-control" id="state" name="state" placeholder="" value="<?php echo $row['state'];?>" >
							</div>
							
						</div>
					</div>	
						<!-- Attachments - Upload Panel -->
                        <div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'SI' AND reference_id = " . $si_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th></th>
                                          <th>Document Type</th>
                                          <th>Document Name</th>
                                          <th>Description</th>
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
					                              ?>
                                          <tr>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                              <td><?php echo $docRow['doc_type'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td><label class="col-sm-1 control-label">Document1</label></td>    
										<td>
                                            <select class="form-control select2 doctype" name="doctype[]">
                                                <option value="PAN CARD">PAN Card</option>
                                                <option value="AADHAAR CARD">AADHAAR Card</option>
                                            </select>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						</div>	
						
                    <div class="tab-pane <?php echo $active_tab2 ?> " id="tab_2">					
						
							    <div class="box123">
                                    <div class="box-header">
                                        <h4 class="box-title">Material Details</h4>
                                        <span class="pull-right">
                                            <a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem">Add 
                                            </a>
                                        </span>
                                    </div>
                                    <div class="box-body">
                                        <!--<table id="prItemsTable" class="table table-bordered table-striped">-->
										<table id="prtable123" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>GRN No.</th>
                                                <th>Material</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit</th>
                                                <th>Rate</th>
												<th>GST%</th>
                                                <th>Amount</th>
												<th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
												$si_hdr_id = $row['id'];
												$sql="SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' order by si_srno desc";
												$result = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$value="";
												while($rowd = mysqli_fetch_array($result)){
													$qty 	= $rowd['qty'];
													$rate 	= $rowd['rate'];
													$gst	= $rowd['gst'];
													$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
													$tot_amount = $tot_amount + $amount;
												
													$rid = $rowd['si_srno'];
												?>	
													<tr>
														<td width='10%'><?php echo $rowd['grn_no']?></td>
														<td width='20%'><?php echo $rid.' '.$rowd['material_name']?></td>
														<td width='20%'><?php echo $rowd['description']?></td>	
														<td width='10%' style="text-align:right;"><?php echo $rowd['qty']?></td>	
														<td width='10%'><?php echo $rowd['unit']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $rowd['rate']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $rowd['gst']?></td>
														<td width='8%' style="text-align:right;"><?php echo $amount?></td>					 
														<td width='6%'>
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
														<?php include "edit_func.php"; ?>							
<!-- Modal Edit Item-->														
														<a href='#modalDeleteItem' id='delete-<?php echo $_GET['id'];?><?php echo $rid;?>' data-toggle='modal' data-id='<?php echo $_GET['id'];?><?php echo $rid;?>' data-target='#modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>'><i class='fa fa-trash-alt'></i></a>
														</td>
<!-- Modal Delete Item-->								
														<?php include "del_func.php"?>							
<!-- Modal Delete Item-->

													</tr>
											<?php
												}
											?>		

                                            </tbody>
                        					
                                        </table>	
						                <table id="pr" class="table table-bordered table-striped">
                                            <tfoot>
                                            <tr>
                                                <th width='60%'></th>
                                                <th width='10%'>Total Amount</th>
                                                <th width='10%' style="text-align:right;"><?php echo $tot_amount;?></th>
												<th width='20%'></th>
                                            </tr>
                                            </tfoot>
										</table>
										
									</div>
								</div>
							
							</div>
												
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="supplier_invoice.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>
					</div>
					
					
                    </fieldset>
				
            </form>
					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>



<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Material to Supplier Invoice </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="supplier_invoice.php?id=<?php $_GET['id'];?>" method="POST">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="si_Id" value="<?php echo $_GET['id'];?>">
							
							<div class="form-group">
                                <div class="col-sm-3">
									<label for="itemCategory" class="control-label"> GRN / SRN</label>
                                    <select class="form-control" id="grn_no" onchange="getgrnitem(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_grn_srn ORDER BY received_date ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['id']. '-' . $rw['received_date'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
							
								<div class="col-sm-3">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" onchange="getmaterial(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT id, description FROM sma_product_group ORDER BY description ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['description'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
                                <div class="col-sm-6">
									<label for="itemName" class="control-label">Material Name</label>
									<span id="getmaterial" >
										<select class="form-control" id="itemName">
											<option value="">Select</option>	
										<?php
											$sql="SELECT id, name FROM sma_product ORDER BY name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($row = mysqli_fetch_array($result)){
										?>
											<option value="<?php echo $row['id']?>"><?php echo $row['name'] ?></option>
											<?php } ?>
										</select>
									</span>
								</div>	
                                
                            </div>
                            
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemDescription" class="control-label">Description</label>
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
                            </div>
						
									<?php
										$yyear =  date("Y");
										if ($yyear=='2018'){
											$account_year = '2';
										}
										else if ($yyear=='2019'){
											$account_year = '3';
										}
									?>
									
						<div class="well well-sm" >
                            <div class="form-group">
								<div class="col-md-4">
									<label class=" control-label">Account Year</label>
									<select class="form-control" name="account_year" id="account_year" >
										<option value=""> Select </option>
										<option value="1" <?php echo ($account_year == '1')?'selected="selected"':'';?> > 2017-2018 </option>
										<option value="2" <?php echo ($account_year == '2')?'selected="selected"':'';?> > 2018-2019 </option>
										<option value="3" <?php echo ($account_year == '3')?'selected="selected"':'';?> > 2019-2020 </option>
										<option value="4" <?php echo ($account_year == '4')?'selected="selected"':'';?> > 2020-2021 </option>
										<option value="5" <?php echo ($account_year == '5')?'selected="selected"':'';?> > 2021-2022 </option>
										<option value="6" <?php echo ($account_year == '6')?'selected="selected"':'';?> > 2022-2023 </option>
										<option value="7" <?php echo ($account_year == '7')?'selected="selected"':'';?> > 2023-2024 </option>
										<option value="8" <?php echo ($account_year == '8')?'selected="selected"':'';?> > 2024-2025 </option>
										<option value="9" <?php echo ($account_year == '9')?'selected="selected"':'';?> > 2025-2026 </option>
										<option value="10" <?php echo ($account_year == '10')?'selected="selected"':'';?> > 2026-2027 </option>									
									</select>	
								</div>
							
								<div class="col-sm-8">
								<label for="company_id" class="control-label">Company</label>
									<select class="form-control" name="company_id" id="company_id" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>	
							</div>
						
							<div class="form-group" >
								<div class="col-sm-6">
									<label class="control-label">Budget Name</label>
								
									<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
										<?php $sql = "SELECT b.name, b.id, a.budget_name, a.id as bid FROM `sma_budget` a, sma_budget_name b where a.budget_name = b.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['bid'];?>" <?php echo ($row['budget_name'] == $r2['bid'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
									</select>
								</div>
							
								<div class="col-sm-6">
									<label class="control-label">Budget Head</label>
								
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "SELECT b.category as category_name, a.budget_category as category FROM `sma_budget` a, sma_budget_category b where a.budget_category = b.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['category'];?>" <?php echo ($row['budget_head'] == $r2['category'])?'selected="selected"':'';?> >  <?php echo $r2['category_name'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div> 
						
                        <div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="number" class="form-control" id="itemQuantity" min="0" style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label">Units</label>
                                	<select class="form-control" id="itemUnits">
										<option value="">Select</option>
										<option value="Meters" <?php echo ($unit == 'Meters')?'selected="selected"':'';?> >Meters</option>
										<option value="Kgs" <?php echo ($unit == 'Kgs')?'selected="selected"':'';?> >Kgs</option>
										<option value="Liters" <?php echo ($unit == 'Liters')?'selected="selected"':'';?> >Liters</option>
										<option value="Nos" <?php echo ($unit == 'Nos')?'selected="selected"':'';?> >Nos</option>
										<option value="Grams" <?php echo ($unit == 'Grams')?'selected="selected"':'';?> >Grams</option>
										<option value="Inches" <?php echo ($unit == 'Inches')?'selected="selected"':'';?> >Inches</option>
									</select>

                                </div>
                            
								<div class="col-sm-4">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="number" class="form-control" id="itemRate" placeholder="0.00" style="text-align:right;" min="0" onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemGST" class="control-label">GST%</label>
                                    <input type="number" class="form-control" id="itemGST" min="0" style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                            </div>
							
                        </form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addItem">Save changes</button>
            </div>
                </section>
            </div>
<!--            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addItem">Save changes</button>
            </div>
-->			
        </div>
    </div>
</div>
<!-- Modal Add Item-->

<?php 	
 if($_GET['sub'] != 'list'){
 
 ?>
 
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document1</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="PAN CARD">PAN Card</option><option value="AADHAAR CARD">AADHAAR Card</option></select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->
 
<?php
		include("../footer.php");	
}

?>

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
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}
	
</script>

<script>


    $("#addItem").on("click", function(e){
        var sub = 'sub2';
	//	var mode = $("#mode").val();
		var si_hdr_id =  $("#si_Id").val();		
        var id =            $("#itemName option:selected").val();
        var name =          $("#itemName option:selected").html();
//		var catid =         $("#categoryId option:selected").val();
//        var catname =       $("#categoryId option:selected").html();
        var account_year =  $("#account_year option:selected").val();
        var company_id   =  $("#company_id option:selected").val();
		var budget_name  =  $("#budget_name option:selected").val();
		var budget_head  =  $("#budget_head option:selected").val();
		
		var description =   $("#itemDescription").val();
        var qty 		=   $("#itemQuantity").val();
        var units =         $("#itemUnits").val();
        var rate =          $("#itemRate").val();
		var gst  =          $("#itemGST").val();
        var amount =        $("#itemAmount").val();
//alert(units + ' ' + qty);
        $('#modalAddItem').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ id:id,si_hdr_id:si_hdr_id,
							name:name,
							description:description,
							account_year:account_year,
							company_id:company_id,
							budget_name:budget_name,
							budget_head:budget_head,
														qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//		location.reload();
//		window.location.href='supplier_invoice.php?sub=edit&id='+si_hdr_id+'&active=active';
		
//        saveItem(mode);
		
    });


	function delete_siItem(si_id, id){
		var sub = 'sub3';
        var siid = si_id;
		var id	 = id;
//alert(gsid + ' ' + id);
		$('#modalDeleteItem'+siid+id).modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ siid:siid,id:id,sub3:sub},
							function(result){
		      $('#prItemsTableBody123').html(result);
			});
		window.location.href='supplier_invoice.php?sub=edit&id='+siid+'&active=active';	
		//location.reload();
	}


	function getmaterial(id){
		
        var sub    = 'sub3';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial').html(result);
		});

	}

	function getunit(id){
		
        var sub    = 'sub4';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getunit').html(result);
		});

	}
		
</script>


</body>
</html>
