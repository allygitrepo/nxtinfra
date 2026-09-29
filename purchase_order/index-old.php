<?php
include("../header.php");
$modulePath = "purchase_order/";
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Purchase Order <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Purchase Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
   			<?php 
				
				$comid  = $_SESSION['comid'];
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status']){
					$_SESSION['comp_id_a'] = $_POST['comp_id'];
					$_SESSION['status_a'] = $_POST['status'];
					$_SESSION['approval_status_a'] = $_POST['approval_status'];
				}
				
				if ($_SESSION['comp_id_a'] or $_SESSION['approval_status_a'] or $_SESSION['status_a']){
					$comp_id = $_SESSION['comp_id_a'];
					$status = $_SESSION['status_a'];
					$approval_status = $_SESSION['approval_status_a'];
				}
	
				if (!empty($_GET['reset']) || !empty($_SESSION['reset']) ) {
					$_SESSION['comp_id_a'] = '';
					$_SESSION['status_a'] = '';
					$_SESSION['approval_status_a'] = '';
					$_SESSION['reset'] = '';
					$comp_id = $_SESSION['comp_id_a'];
					$status = $_SESSION['status_a'];
					$approval_status = $_SESSION['approval_status_a'];
				}
			?>
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
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
				
			<?php if($role=='Maker'){ ?>
                <span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "add.php"?>" class="btn btn-primary">Create Purchase Order</a> </span>
			<?php  } ?>	
				<span class="pull-right"><a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem" class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;</span>
            </div>
			
			<span id="prItemsTableBody123"> </span>
			
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					<th>Approval Notes</th>
					<th style="text-align:right;">Total</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
					
<!--					<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
	<?php
		//$sql="SELECT * from sma_purchase_order order by id desc";
		$role			= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'PO' and user_category = '$user_category' ";

		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		$user_category 		= $row['user_category'];
		$to_value 			= $row['to_value'];
		$project_manager	= $row['project_manager'];
		$project_incharge 	= $row['project_incharge'];
		$coo_cxo 			= $row['coo_cxo'];

//echo $role;

		if($role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role == 'HOD' || $role =='COO'){
			$sql="SELECT * from sma_purchase_order where project in ($comid) and (status = 'Submited' or status = 'Completed' or draft_by = '$user') ";
		}
		else if ($role =='Checker' ){
			$sql="SELECT * from sma_purchase_order where project in ( $comid ) 
					and (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_Type = 'PO' )
						or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PO') ) or draft_by = '$user' ";
		}
		else if($role =='Checker - Account' || $role =='Accountant'  || $role =='HOD - Account'){
			$sql="SELECT * from sma_purchase_order where project in ( $comid ) ";
		}
		else {
			$sql="SELECT * from sma_purchase_order where project in ( $comid ) and draft_by = '$user'  ";
		}
		
		if ($user =='Admin'){
			$sql="SELECT * from sma_purchase_order where project in ( $comid ) ";			
		}
		
		if ($comp_id){
			$sql .= " and project = '$comp_id' ";
		}
		if ($status){
			$sql .= " and status = '$status' ";
		}
		if ($approval_status){
			$sql .= " and approval_status = '$approval_status' ";
		}

		$sql .= ' order by id desc ';
//echo $sql;					
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	while($row = mysqli_fetch_array($result)){
		
		
		$approval_memo_ref = $row['approval_memo_ref'];;
		$sql 	= "select * from sma_approval_memo where id = '$approval_memo_ref' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$app_no_date = $approval_memo_ref. '/'.date('d-m-Y', strtotime($r2['dated']));
		
		$project = $row['project'];
		$sql 	= "select * from sma_project where id = '$project' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$project = $r2['name'];		
		
		
		$budget_name = $row['budget_name'];
		$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
		$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
				
		$budget_head = $row['budget_head'];
		$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_head = $r2['category'];
		
		$to_supplier = $row['to_supplier'];
		$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$to_supplier = $r2['party_name'];
	
		$purchase_id = $row['id'];
		$tot_amount = '';
		$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
			$qty 	= $r1['quantity'];
			$rate 	= $r1['unit_rate'];
			$gst	= $r1['gst'];
			$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
			$tot_amount = $tot_amount + $amount;
		}										
		
			$rid = $row['id'];
			
			$approval_status = $row['approval_status'];
			
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="18%"><?php echo $row['po_number'];?></td>
		<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="19%"><?php echo $to_supplier;?></td>
		<td width="10%"><?php echo $app_no_date;?></td>
		<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
<!--		<td width="8%" style="text-align:right;">
			<a href="edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
			
			<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
			<?php include "view_history.php"; ?>
			&nbsp;
	<?php //if($approval_status=='Approved'){ ?>	
			<a href="pur_order_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>
	<?php //} ?>					
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
<!--		</td>-->

    </tr>
	</a>
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Export Purchase order data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="po_export_func.php?sub=pdf" target="_blank" method="POST" >
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
									<label for="itemCategory" class="control-label"> Compnay</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							
							<div class="form-group">
                                <div class="col-sm-6">
									<label for="itemCategory" class="control-label"> Supplier</label>
									<select class="form-control" name="supplier_id" id="toSupplier" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>
							
                                </div>
                            
								<div class="col-sm-6">
                                <label class="control-label">Department</label>
									<select class="form-control" name="department" id="departMent" <?php echo $readonly; ?> >
										<option value=""> Select </option>
										<option value=""> All </option>
											<?php $sql = "select * from sma_department order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['name'];?></option>
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

