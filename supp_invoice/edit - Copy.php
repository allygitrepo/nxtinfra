<?php
include("../header.php");
$modulePath = "supp_invoice/";

$_SESSION['reset'] = '1';

?>


  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php  
	if($_GET['sub'] == 'delete'){ 
        $id  = $_GET['id'];
		$sql = "delete from sma_supplier_invoice where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql = "delete from sma_supplier_invoice_details where si_hdr_id = '$id' ";
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql="delete FROM `file_uploads` where module = 'SI' and reference_id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);

        //echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		$baseurl1 =$baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
			
	} 

	if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){
		$id					= $_POST['id'];
		$status				= $_POST['status'];
		
		if($_POST['submit1']){
			$approval_status	= $_POST['submit1'];
		}
		else if($_POST['submit2']){
			$approval_status	= $_POST['submit2'];
		}
		
		if($status =='Draft'){
			$approval_status 	= 'Pending';
		}
		$user   = $_SESSION['user'];

		$sql = "update sma_supplier_invoice set approval_status	= '$approval_status', status =  'Submited', changed_by = '$user', changed_date = now() where id = '$id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";

	}
	
?>

<?php
	if(isset($_POST['editItem'])){

		$rid     		= $_POST['rid'];
		$si_hdr_id 		= $_POST['si_hdr_id'];
		$material_id	= $_POST['material_id'];
		$grn_no			= $_POST['grn_no'];
		$description 	= $_POST['itemdescription'];
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		
		$quantity 		= $_POST['itemqty'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		
		$amount = $quantity * $rate + (($quantity * $rate) * $gst / 100);

		$sql = "select * from sma_product where id = '$material_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$material_name = $r1['name'];
		
		$sql = "update `sma_supplier_invoice_details` set material_id = '$material_id', 
						grn_no			= '$grn_no',
						material_name = '$material_name', 
						description   = '$description',
						account_year   = '$account_year',
						company_id     = '$company_id',
						budget_name    = '$budget_name',
						budget_head    = '$budget_head',
						qty			  = '$quantity', 
						unit		  = '$units',
						rate		  = '$rate', 
						amount		  = '$amount', 
						gst			  = '$gst'
				where si_hdr_id = '$si_hdr_id' and si_srno = '$rid' ";
		$r2 = mysqli_query($con, $sql);
	
	$amount = 0;
	
	$sql = "select * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id'";
//echo $sql;	
	$r2 = mysqli_query($con, $sql);
	while($r1 = mysqli_fetch_array($r2)){
		$amount = $amount + $r1['amount'];
	}
	
	if($amount > 0){
		$sql = " update `sma_supplier_invoice` set total_amount = '$amount', bal_amount = '$amount' where id = '$si_hdr_id' ";
		$r2 = mysqli_query($con, $sql);
//	echo $sql;
	}
//exit('######2');	
//	echo "<script>window.location.reload();</script>";
	echo "<meta http-equiv='refresh' content='0'>";    
	//echo "<script>window.location.href='supplier_invoice.php?sub=edit&id=$si_hdr_id&active=active&987';</script>";
	$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&active=active&987';
	echo "<script>window.location.href='$baseurl';</script>";
}

?>

<?php
	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$si_id			= $_POST['id'];
						
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$suplier_name			= $_POST['suplier_name'];
			$comp_id				= $_POST['comp_id'];
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
						company_id					= '$comp_id',
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

		$amount = 0;
	
		$sql = "select * from sma_supplier_invoice_details where si_hdr_id = '$id'";
	//echo $sql;	
		$r2 = mysqli_query($con, $sql);
		while($r1 = mysqli_fetch_array($r2)){
			$amount = $amount + $r1['amount'];
		}
		
		if($amount > 0){
			$sql = " update `sma_supplier_invoice` set total_amount = '$amount', bal_amount = '$amount' where id = '$id' ";
			$r2 = mysqli_query($con, $sql);
	//	echo $sql;
		}
			
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

	
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";
		
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
	
		$status = $row['status'];
		$si_id = $row['id'];
		$readonly = '';
		if ($status == 'Submited' || $status == 'Approved'){
			$readonly = 'READONLY';
		}		

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

					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
					<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
			
                    <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				
			<ul class="nav nav-tabs">
				  <li class="<?php echo $active_tab1; ?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Supplier Invoice</a></li>
				  <li  class="<?php echo $active_tab2; ?>"><a href="#tab_2" data-toggle="tab" id="second_tab" >Material Details</a></li>
				  <li  class="<?php echo $active_tab3; ?>"><a href="#tab_3" data-toggle="tab" id="third_tab" >Document</a></li>
				  
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane <?php echo $active_tab1;?>" id="tab_1">
					
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $row['id'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Supplier Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no" <?php echo $readonly; ?> value="<?php echo $row['supplier_invoice_no'];?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date" <?php echo $readonly; ?> value="<?php echo date('d-m-Y', strtotime($row['invoice_date']));?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<select class="form-control select2" name="comp_id" id="comp_id" >
								<option value=""> Select </option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<select class="form-control" name="suplier_name" id="suplier_name" <?php echo $readonly; ?> onchange="getporefno(this.value); getstate(this.value)">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php
								$our_po_ref_no = $row['our_po_ref_no'];
								//$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								//$res  = mysqli_query($con, $sql);
								//echo mysqli_error($con);
								//$r1 = mysqli_fetch_array($res);
								
							//	$our_po_ref_no = $r1['po_number'];
							?>
							<div class="col-md-4">
								<label class="control-label">Our PO Ref.No.</label>
								<span id="getporefno">
									<input type="text" class="form-control" id="our_po_ref_no" name="our_po_ref_no" readonly value="<?php echo $our_po_ref_no;?>" >
								</span>
							</div>
						
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<span id="getcreditdays">
								<input type="text" class="form-control" id="credit_days" name="credit_days" readonly style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
								</span>
							</div>
							<?php
								$credit_days = $row['credit_days'];
								$due_date =  date('d-m-Y', strtotime("$credit_days day",strtotime($row['invoice_date'])));
							?>
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="due_date" name="due_date" <?php echo $readonly; ?> value="<?php echo $due_date;?>" >
								
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
							
							<?php 
							$total_amount = $row['total_amount'];
							$bal_amount = $row['bal_amount'];
							$paid_amount = $total_amount - $bal_amount;
							if($total_amount>0){
							?>
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" id="total_amount" name="total_amoount" style="text-align:right;" readonly value="<?php echo number_format($total_amount,2);?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Paid Amount</label>
								<input type="text" class="form-control" id="paid_amount" name="paid_amount" style="text-align:right;" readonly value="<?php echo number_format($paid_amount,2);?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Out Standing Amount</label>
								<input type="text" class="form-control" id="bal_amount" name="bal_amount" style="text-align:right;" readonly value="<?php echo number_format($bal_amount,2);?>" >
							</div>
							
							<?php }	 ?>
							
						</div>	
							
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
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
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
					                              ?>
                                          <tr>
                                              <td><?php echo $document ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
											  <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td><label class="col-sm-1 control-label">Document</label></td>    
										<td>
                                            <select class="form-control select2 doctype" name="doctype[]">
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
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
							
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
							<div class="box-footer">
						
							<span id="predit"></span>
						
							<div class="col-sm-6">
								<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&si_id='.$si_id ; ?>
							
							<?php		
								if ($status == 'Draft' && $user=='Admin'){
							?>
								<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>
							<?php	} ?>
								<span>&nbsp;&nbsp;</span>
							</div>
							
							<div class="col-sm-6 text-right">
							
								<!--<button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button>-->
								<span>&nbsp;&nbsp;</span>
								<!--<input class="btn btn-primary" type="submit" value="Save" name="Save">-->
									
								<?php 
										$_session['si_id'] 	= $si_id;
										$_session['status']  = $status;
										
								?>

								<?php		
								$role = $_SESSION['role'];
								if ($status == 'Submited'  && $role != 'Maker'){
								?>
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
								<?php } 
									if ($status == 'Draft'){		
								?>	
									<input type="submit" class="btn btn-primary" value="Save" name="Save">
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#approvalAuthority">Send </a>
								<?php	} ?>
								
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button>
									
							<!--		<input type="submit" class="btn btn-primary"  name="submit" value="Submit" >-->
								
							</div>

						</div>
						
						</div>	
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
						
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
                                                <th style="text-align:right;">Qty</th>
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
													
													$material_id = $rowd['material_id'];
													$sql = "select * from sma_product where id = '$material_id' ";
													$q2  = mysqli_query($con, $sql);													
													$r2 = mysqli_fetch_object($q2);
													$material_name = $r2->name;
													
												?>	
													<tr>
														<td width='10%'><?php echo $rowd['grn_no']?></td>
														<td width='20%'><?php echo $material_name?></td>
														<td width='20%'><?php echo $rowd['description']?></td>	
														<td width='10%' style="text-align:right;"><?php echo $rowd['qty']?></td>	
														<td width='10%'><?php echo $rowd['unit']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $rowd['rate']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $rowd['gst']?></td>
														<td width='8%' style="text-align:right;"><?php echo $amount?></td>					 
														<td width='6%'>
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
														<?php  include "edit_func.php"; ?>							
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
							
								
	           				<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								</div>
							</div>	
							
							</div>
												
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						
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



<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_session['si_id'];
											$status = $_session['status'];
										/*	$sql="SELECT * FROM sma_supplier_invoice where id = '$si_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
										*/
										?>
										
										<input type="hidden" name="si_id" id="si_idE" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
										<div class="form-group col-md-12">
                                            <label for="department" class="col-sm-4 control-label">Department</label>
											<div class="col-sm-7">
												<select class="form-control " id="departmentE" name="department" onchange="getuser(this.value)" required="required" >
												<option value="">Select</option>
												<?php
													$sql="SELECT * FROM sma_department  ORDER BY name ASC";
													$result = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($row = mysqli_fetch_array($result)){
												?>
													<option value="<?php echo $row['id']?>" <?php echo ($row['id'] == $department_id)?'selected="selected"':'';?> ><?php echo $row['name'] ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required="required">
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where department = '$department_id' ORDER BY first_name ASC";
														    $result = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row = mysqli_fetch_array($result)){
														?>
														<option value="<?php echo $row['id']?>" <?php echo ($row['department'] == $department_id)?'selected="selected"':'';?>><?php echo $row['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_session['si_id'];
											$status = $_session['status'];
											
										/*	$sql="SELECT * FROM sma_supplier_invoice where id = '$si_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
										*/		
										?>
										
										<input type="hidden" name="si_id" id="si_idR" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										<div class="form-group col-md-12">
                                            <label for="department" class="col-sm-4 control-label">Department</label>
											<div class="col-sm-7">
												<select class="form-control " id="departmentR" name="department" onchange="getuser1(this.value)" required>
												<option value="">Select</option>
												<?php
													$sql="SELECT * FROM sma_department ORDER BY name ASC";
													$result = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($row = mysqli_fetch_array($result)){
												?>
													<option value="<?php echo $row['id']?>" <?php echo ($row['id'] == $department_id)?'selected="selected"':'';?> ><?php echo $row['name'] ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser1">
													<select class="form-control select2123" id="approverR" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where department = '$department_id' ORDER BY first_name ASC";
														    $result = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row = mysqli_fetch_array($result)){
														?>
														<option value="<?php echo $row['id']?>" <?php echo ($row['department'] == $department_id)?'selected="selected"':'';?>><?php echo $row['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
<!--                                               	<select class="form-control select2123" id="statusE" name="status">
													    <option value="">Select</option>
														<option value="Draft">Draft</option>
														<option value="Submitted">Submitted</option>
														<option value="Reviewed">Reviewed</option>
													</select>
												-->
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  
	  
	  
	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Material </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="edit.php?id=<?php $_GET['id'];?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="si_Id" value="<?php echo $_GET['id'];?>">
							<?php
								$id = $_GET['id'];
								$sql="SELECT * FROM sma_supplier_invoice where id = '$id' ";
                            //echo $sql;
								$rs1 = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                $rw1 = mysqli_fetch_array($rs1);
								$our_po_ref_no = $rw1['our_po_ref_no'];
								
							//	$sql="SELECT  distinct(a.material_id), b.our_po_ref_no, RPAD(c.name,30,' '), c.name, c.uom, b.id, b.received_date FROM `sma_grn_srn_details` a, sma_grn_srn b, sma_product c where b.our_po_ref_no = '$our_po_ref_no' and a.grn_srn_hdr_id = b.id and a.material_id = c.id ORDER BY received_date ASC";
                            //    echo $sql;
                                    
							?>
							
						<?php if (!empty($our_po_ref_no)){ ?>	
						
						<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> GRN / SRN</label>
                                    <select class="form-control" id="grn_No" name = "grn_no" onchange="getitemdetails(this.value)" >
										<option value="0">Select</option>
                                    <?php
										
                                    	$sql="SELECT a.material_id, b.our_po_ref_no, c.name as product_name, c.name, c.uom, b.id, b.received_date, (a.qty - a.si_qty) as qty, a.total_po_qty FROM `sma_grn_srn_details` a, sma_grn_srn b, sma_product c where b.our_po_ref_no = '$our_po_ref_no' and a.grn_srn_hdr_id = b.id and a.material_id = c.id and a.qty > a.si_qty ORDER BY received_date ASC ";
						
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
										
										$received_date = date('d-m-Y', strtotime($rw['received_date']));
                                    ?>
                                        <option value="<?php echo $rw['id'].'-'.$rw['material_id'];?>" ><?php echo 'Material name:'.$rw['product_name'] .' | GRN No.:'. $rw['id']. ' | Dated:' . $received_date .' | Qty:'.$rw['qty']  ?></option>
                                        <?php } ?>
                                    </select>
								</div>
							</div>
						<?php } ?>
							
                        <?php if (empty($our_po_ref_no)){
						?>		
							<div class="form-group">
							
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
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
								
                                <div class="col-sm-8">
									<label for="itemName" class="control-label">Material Name</label>
									<span id="getmaterial1" ><span id="getgrnitem" >
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
                        <?php } ?>
							
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
										else if ($yyear=='2020'){
											$account_year = '4';
										}
									?>
					<span id="getitemdetails">			
						<div class="well well-sm" >
                            <div class="form-group">
								<div class="col-md-4">
									<label class=" control-label">Account Year</label>
									<select class="form-control" name="account_year" id="account_YR" onchange="getcompany(this.value)" >
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
								<span id="getcompany">	
									<select class="form-control" name="company_id" id="company_ID" onchange="getbudgetname(this.value)" >
                             		<option value=""> Select </option>
									<?php $sql = "select * from company where comp_id in (select project from sma_budget where account_year = '$account_year') and comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</span>
								</div>
							</div>
						
							<div class="form-group" >
								<div class="col-sm-6">
									<label class="control-label">Budget Name</label>
									<span id="getbudgetname">
									<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "SELECT b.name, b.id, a.budget_name, a.id as bid FROM `sma_budget` a, sma_budget_name b where a.budget_name = b.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['budget_name'];?>" <?php echo ($row['budget_name'] == $r2['budget_name'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
									</select>
									</span>
								</div>
							
								<div class="col-sm-6">
									<label class="control-label">Budget Head</label>
									<span id="getbudgethead">	
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "SELECT b.category as category_name, a.budget_category as category FROM `sma_budget` a, sma_budget_category b where a.budget_category = b.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['category'];?>" <?php echo ($row['budget_head'] == $r2['category'])?'selected="selected"':'';?> >  <?php echo $r2['category_name'];?></option>
										<?php } ?>
									</select>
									</span>
								</div>
							</div>
						</div> 
						
                        <div class="form-group col-md-12">
                                <span id="getunit" > 
								<div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label">Units</label>
										<input type="text" class="form-control" id="itemUnits" name="itemunits" readonly value="" >
								</div>
                            	
                                
								<div class="col-sm-4">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate"  style="text-align:right;"  onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
								</span>
                            </div>
							
                            <div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemGST" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                            </div>
					</span>
                        
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


<script>
	function getitemdetails(id){
		
        var sub    = 'sub44';
		var grn_no = document.getElementById('grn_No').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id + ' ' + grn_no);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub44:sub},function(result){
		      $('#getitemdetails').html(result);
		});

	}

</script>

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        


<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
?>


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


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
		});

	}
	
</script>

<script>

  
    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idE").val();
		var department 		=  $("#departmentE option:selected").val();
		var approver		=  $("#approverE option:selected").val();
        //var department 		=  $("#departmentE").val();
        //var approver 		=  $("#approverE").val();
		var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		if(department=='' || approver==''){
			alert("Department OR User Name should select...");
			return;
		}
		//var approved		=  $("#approvedE").val();
//		var approved		=  $("#approvedE:checked").val();
//alert(status+ ' ' + department + ' ' +  mode);
		 $('#approvalAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						department:department,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idR").val();
		var department 		=  $("#departmentR option:selected").val();
		//var department 		=  $("#departmentE").val();
        //var approver 		=  $("#approverE").val();
		var approver		=  $("#approverR option:selected").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		if(department=='' || approver==''){
			alert("Department OR User Name should select...");
			return;
		}
		//var approved		=  $("#approvedE").val();
//		var approved		=  $("#approvedE:checked").val();
//alert(status+ ' ' + department + ' ' + mode);
		 $('#rejectAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						department:department,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


    $("#addItem").on("click", function(e){
        var sub = 'sub2';
	//	var mode = $("#mode").val();
		var grn_no		 =   $("#grn_No").val();
//alert(grn_no);
		if(grn_no != '0'){
			var si_hdr_id 	 =  $("#si_Id").val();		
			var id 			 =  $("#itemName").val();
			var name		 =  $("#itemName").html();
			var account_year =  $("#account_year").val();
			var company_id   =  $("#company_id").val();
			var budget_name  =  $("#budget_name").val();
			var budget_head  =  $("#budget_head").val();
//alert(account_year+ ' ' + company_id + ' ' + budget_name + ' ' + budget_head);
		}
		else {
			var si_hdr_id =  $("#si_Id").val();		
			var id =            $("#itemName option:selected").val();
			var name =          $("#itemName option:selected").html();
			var account_year =  $("#account_year option:selected").val();
			var company_id   =  $("#company_id option:selected").val();
			var budget_name  =  $("#budget_name option:selected").val();
			var budget_head  =  $("#budget_head option:selected").val();
		}
		
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
							grn_no:grn_no,
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
		      $('#prItemsTableBody').html(result);
			});
		window.location.href='edit.php?sub=edit&id='+siid+'&active=active';	
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

		function getmaterial1(id){
		
        var sub    = 'sub3';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}


	function getunit(id){
		
        var sub    = 'sub4';
		var grn_no = document.getElementById('grn_No').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + grn_no);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,grn_no:grn_no,sub4:sub},function(result){
		      $('#getunit').html(result);
		});

	}

		function getgrnitem(id){
		
        var sub    = 'sub5';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem').html(result);
		});

	}
	
	function getgrnitem1(id){
		
        var sub    = 'sub5';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem1').html(result);
		});

	}

	
	function getcompany(id){
		
        var sub    = 'sub8';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getcompany').html(result);
		});

	}
	
	function getcompany1(id){
		
        var sub    = 'sub88';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub88:sub},function(result){
		      $('#getcompany1').html(result);
		});
	}
		
	function getbudgetname(id){
		
        var sub    = 'sub7';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}

	function getbudgetname1(id){
		
        var sub    = 'sub77';
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub77:sub},function(result){
		      $('#getbudgetname1').html(result);
		});

	}


	function getbudget(id){
		
        var sub    = 'sub6';
		var company_id   = document.getElementById('company_ID').value;
		var account_year = document.getElementById('account_YR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub6:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}
	
	function getbudget1(id){
		
        var sub    = 'sub66';
		var company_id   = document.getElementById('company_iD').value;
		var account_year = document.getElementById('account_yR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub66:sub},function(result){
		      $('#getbudgethead1').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}


	function getuser(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser').html(result);
		});

	}
	function getuser1(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser1').html(result);
		});

	}
	
</script>


</body>
</html>
