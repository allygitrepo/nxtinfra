<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "supp_invoice/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Supplier Invoice <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.$modulePath; ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Supplier Invoice</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              
			<?php 
			
			//echo $_POST['status']. ' ' . $_POST['comp_id'] ;	
			
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['status'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['status']){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['status'];
					$approval_status = $_SESSION['approval_status'];
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['status'] = '';
					$_SESSION['approval_status'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['status'];
					$approval_status = $_SESSION['approval_status'];
				}
				
			?>
								<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Supplier Name</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_party_mst order by party_name  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($comp_id == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['party_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submited" <?php echo ($status == 'Submited')?'selected="selected"':'';?>> Submited </option>
										<option value="Verified" <?php echo ($status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Pending" <?php echo ($approval_status == 'Pending')?'selected="selected"':'';?>> Pending </option>
										<option value="Verified" <?php echo ($approval_status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
								
				</form>

            <div class="pull-right">
				<span class="sepV_c marginRight">
				<?php 
					$role		= $_SESSION['role']; 
					if($role=='Maker'){
				?>
						<span class="pull-right"><a href="add.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Invoice </a></span>
					<?php } ?>	
					<span class="pull-right"><a href="#modalExport"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalExport" class="btn btn-primary">Export</a> &nbsp;&nbsp;&nbsp;</span>
				</span>
			</div>
			</div>
		</div>	
	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th></th>
			<th>Sr.No.</th>
			<th>Dated</th>
			<th>Supp.Inv.No.</th>
			<th style="text-align:right;">Amount</th>
			<th>Our PO Ref.NO.</th>
			<th>Due Date</th>
			<th>Supplier Name</th>
		    <th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
    
		</tr>
	</thead>
<tbody>
<?php

	//$sql="SELECT * from sma_supplier_invoice order by id desc";
	$user   = $_SESSION['user'];
	$comid = $_SESSION['comid'];
	
	$role			= $_SESSION['role'];
	$user_category	= $_SESSION['user_category'];
	$sql = "SELECT * from sma_workflow where doc_type= 'SI' and user_category = '$user_category' ";
		
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$user_category 		= $row['user_category'];
	$to_value 			= $row['to_value'];
	$project_manager	= $row['project_manager'];
	$project_incharge 	= $row['project_incharge'];
	$coo_cxo 			= $row['coo_cxo'];
				
	//echo $role. ' <<<>>> ' . $user;
	
					if($role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role == 'CEO' || $role =='COO' ){	
						
						$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and (status = 'Submited' or status = 'Completed' or draft_by = '$user') ";
					}
					else if ($role =='Checker' || $role =='Accountant'){
						$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'SI') 
							or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'SI')) ";
					}
					else if ( $role =='Checker - Account' ){
						$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid )) ";
					}
					else {
						$sql="SELECT * from sma_supplier_invoice where id in (SELECT distinct(si_hdr_id) FROM `sma_supplier_invoice_details` where company_id in ( $comid ) ) and (draft_by = '$user' ) ";
					}
					
					if ($user =='Admin'){
						$sql="SELECT * from sma_supplier_invoice where id > 1 ";
					}
					
					if ($comp_id){
						$sql .= " and suplier_name =  '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
					}
					
					if ($role =='Checker' || $role =='Accountant'){
						if (($comp_id ) ||  ($status) || ($approval_status)){
							$sql .= " or draft_by = '$user' ";
						}
					}
					
					
					if (empty($comp_id) && empty($status) && empty($approval_status) ){
						$sql .= " or ( draft_by = '$user' and status = 'Draft')";
					}

					
	//				else if ( $role == 'Checker' || $role =='Maker'){
	//					$sql="SELECT * from sma_supplier_invoice where status = 'Completed' and company_id in ($comid) ";
	//				}
					$sql .= ' order by id desc ';

//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){

		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];

		$our_po_ref_no = $row['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$our_po_ref_no = $r1['po_number'];
		
		$rid = $row['id'];
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="3%" style="text-align:right;"><?php echo $row['id']?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
		<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['total_amount']?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
<!--		<td width="10%"><?php echo $row['delivery_challen_no'];?></td>-->
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['due_date']));?></td>
		<td width="15%"><?php echo $supplier_name;?></td>
<!--		<td width="10%"><?php echo $row['transport_lr_no'];?></td>-->
		<td width="09%"><?php echo $row['changed_by'];?></td>
		<td width="09%"><?php echo $row['status'];?></td>
		<td width="09%"><?php echo $row['approval_status'];?></td>
	
<!--		<td width="5%" style="text-align:right;">
		<a href="edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;</a>
		<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
			<?php include "view_history.php"; ?>
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
<!--		</td>-->
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
	
	
  <!-- Modal Add Item-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Purchase Requisition data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="si_export_func.php?sub=pdf" target="_blank" method="POST" >
                            <input type="hidden" id="mode" value='Export'>
                            <input type="hidden" id="tempId">
<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">-->
							
							<div class="form-group">
                                
								<div class="col-sm-4">
									<label class="control-label">From Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="fromDate" name="from_date" required="required" >
									</div>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">To Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="toDate" name="to_date" >
									</div>
								</div>
							</div>
								
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Supplier</label>
									<select class="form-control" name="supplier_id" id="supplierId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Add Item-->

</div>	


<?php
include("../footer.php");
?>

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

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>


<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

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

