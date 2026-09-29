<?php
	include("../header.php");
	$modulePath = "purchase_order_entry/";

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
	$pgname = $help_code;
include("../viewonly.php");
	
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_purchase_order where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        $baseurl.=$modulePath;
			echo "<script>window.project.href='$baseurl';</script>";
	} 
	
	if($_GET['sub']=='Save'){

			$srno				= $_POST['srno'];
			$po_type			= $_POST['po_type'];
			$po_doc_type		= $_POST['po_doc_type'];
			$po_number			= $_POST['po_number'];
			$approval_memo_ref	= $_POST['approval_memo_ref'];

			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$trans_type			= '';
			$tender_no			= $_POST['tender_no'];
			
			$fyr		= date('Y', strtotime($dated));
			$fmth		= date('m', strtotime($dated));
			$fin_year	= '';
			if($fmth>=1 && $fmth<=3){
				$styr = $fyr - 1;
				$fin_year = $styr . '-'. $fyr;
			}
			else {
				$ltyr = $fyr + 1;
				$fin_year = $fyr . '-'. $ltyr;
			}	
			
			$department			= $_POST['department'];
			$delivery_address   = $_POST['delivery_address'];
			$location			= $_POST['location'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$supplier_location		= $_POST['supplier_location'];
			
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			$advance_flag		= $_POST['advance_flag'];
			
			$header_text 		= $_POST['header_text'];
			
			$user=$_SESSION['user'];
			$userid   	= $_SESSION['usrid'];
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
			$prepared_by		= $user;
			$approved_by		= $_POST['approved_by'];
			$checked_by			= $_POST['checked_by'];
			$status				= $_POST['status'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			$other_charges		= $_POST['other_charges'];
			$discount			= $_POST['discount'];
			$transport			= $_POST['transport'];
			$status 			= 'Draft';
			$subject			= $_POST['subject'];
			$notes				= $_POST['notes'];

			$sql = "SELECT * FROM po_order_type WHERE 1 and po_doc_type = '$po_doc_type' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$terms	  = $r2->terms;

		
			$sql = "SELECT * FROM sma_department where id = '$department' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$dept_code = $r2['dept_code'];
			
			$yyyy = date('Y').'-';
			$yyyy .= date('y')+1;
			
			$sql = "SELECT * FROM company where comp_id = '$project' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$comp_code 		= $r2['comp_code'];
			$prefix 		= $r2['prefix'];
			//$po_last_number = $r2['po_last_number']+1;
			//$wo_last_number = $r2['wo_last_number']+1;
			$header_terms	= $r2['header_terms'];
			$fix_terms 		= $r2['general_terms'];
		
			$header_text	= '';
		
			$sql="Select * from sma_financial_year where status = 'Y' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);	
			$yyyy 		= $r2['finyear_prefix'];
			$po_last_number = $r2['po_last_number']+1;
		
			$sqlb = "";
// 			if($po_doc_type =='PO'){
			    
			 //   $sqlb = " po_last_number = '$po_last_number' ";
// 			}
//             else if($po_doc_type =='WO'){
			    
// 			    $sqlb = " wo_last_number = '$wo_last_number' ";
// 			    $po_last_number = $wo_last_number;
// 			}
			
			//Nxt-Infra/SPV/CGRG/PO/2025-26/0XXX
		//	$po_number = $prefix.$comp_code.'/'.$po_doc_type.'/'.$yyyy.'/'.$po_last_number;
			
			$sql = " UPDATE sma_financial_year set po_last_number = '$po_last_number' where status = 'Y' ";
			$q2  = mysqli_query($con, $sql);
			
			$approval_memo_ref = 'Open PO';
//echo $po_number. "<BR>";		'/'.$revno
  			$sql="insert into sma_purchase_order (id, po_doc_type, po_type, po_number, approval_memo_ref,dated, project, delivery_address, quotation_reference_no, to_supplier, delivery_days, credit_days, payment_terms, delivery_date, terms, header_text, other_charges, discount, transport, advance_flag, prepared_by, approved_by, checked_by, status, draft_by, draft_date, department, location, subject, notes, supplier_location , trans_type, tender_no, fix_terms, background, scope_of_work ) values('$srno', '$po_doc_type', '$po_type', '$po_number', '$approval_memo_ref', '$dated', '$project', '$delivery_address', '$quotation_reference_no', '$to_supplier', '$delivery_days', '$credit_days', '$payment_terms', '$delivery_date', '$terms', '$header_text', '$other_charges', '$discount', '$transport', '$advance_flag', '$prepared_by', '$approved_by', '$checked_by', '$status' , '$user', now(), '$department', '$location', '$subject', '$notes','$supplier_location', '$trans_type', '$tender_no', '$fix_terms', '$background_section', '$scope_of_work' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit('INSERT ERROR sma_purchase_order !!!');}

			$userid   	= $_SESSION['usrid'];
		
		
		$sql = "SELECT * FROM sma_approval_items WHERE 1 and quantity > 0 and approval_hdr_id = '$approval_memo_ref'";
//echo $sql. "<BR>";
//exit();				
		$qry2  = mysqli_query($con, $sql);	
		while($rw = mysqli_fetch_array($qry2)){	
		
			$pr_item_id			= $rw['id'];
			$unit				= $rw['unit'];
			$description		= $rw['product_desc'];
			$gst                = $rw['gst'];
			
			$product_id			= $rw['product_id'];
			$sql = "SELECT * from sma_product where 1 and id = '$product_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$category   = $r2->category;
			$gst_type   = $r2->gst_type;

			$quantity			= $rw['quantity'];
			$po_quantity		= $rw['po_quantity'];
			$rate				= $rw['unit_rate'];
			$pr_quantity		= $quantity - $po_quantity;
			$quantity			= $quantity - $po_quantity;
			
			$sql = "INSERT into sma_po_items (purchase_id, pr_item_id, company_id, product_id, product_desc, pr_quantity, quantity, uom, unit_rate, gst_id, gst, first_insert ) VALUES
			( '$srno', '$pr_item_id', '$project', '$product_id', '$description', '$pr_quantity', '$quantity', '$unit', '$rate', '$gst_type', '$gst', 'F' )";		
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}
		
		/* $sql = "SELECT * FROM sma_approval_details WHERE 1 and approval_hdr_id = '$approval_memo_ref'";
echo $sql."<BR>";			
		$qry2  = mysqli_query($con, $sql);	
		while($rw = mysqli_fetch_array($qry2)){
		
			$ap_item_id			= $rw['id'];
			$supplier_name		= $rw['supplier_name'];
			$quote_ref_no		= $rw['quote_ref_no'];
			$vendor_selected	= $rw['vendor_selected'];
			$values			    = $rw['values'];
			$remarks			= $rw['remarks'];
			
			if($vendor_selected=='Y'){
			    $sql = "UPDATE sma_purchase_order SET to_supplier	= '$supplier_name' 	where id='$srno'";
			    mysqli_query($con, $sql);
			}
			
			$sql = "INSERT into sma_po_approval_details (po_approval_hdr_id, supplier_name, quote_ref_no, vendor_selected, `values`, remarks ) VALUES
			        ( '$srno', '$supplier_name', '$quote_ref_no', '$vendor_selected', '$values', '$remarks' )";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
echo $sql."<BR>";	

		} */
//exit();

			$company_id = $project;
			
			/* $sql = "SELECT * from sma_po_items where purchase_id = '$srno'";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$value="";
echo $sql."<BR>";				
			while($row2 = mysqli_fetch_array($result)){
				
				$po_item_id 	= $row2['id'];
				$pr_item_id		= $row2['pr_item_id'];
				$quantity		= $row2['quantity'];
				$product_id 	= $row2['product_id'];
				$sql="SELECT * FROM sma_product where id = '$product_id' ";		
				$res2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$mat = mysqli_fetch_array($res2);
				$product_name 		= $mat['name'];
				$gst_type	  		= $mat['gst_type'];
				$product_category 	= $mat['group'];
				$category 			= $mat['category'];
				$budget_sub_id 		= $mat['budget_head'];
									
				$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_sub_id' ";
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_code 			= $com['budget_code'];
				$budget_head		 	= $com['id'];
				$budget_name 			= $com['budget_name'];
				
//echo $sql."<BR>";								
				$sql = "SELECT * FROM sma_budget where project = '$company_id' and budget_name = '$budget_name' 
								and budget_head = '$budget_head' and account_year = '$fin_year'  ";
				$res2 		= mysqli_query($con, $sql);
//echo $sql."<BR>";				
				echo mysqli_error($con);
				$cat 		= mysqli_fetch_array($res2);
				$budget_id   = $cat['id'];
				$budget_name_id		= $cat['budget_name'];
				$budget_head 		= $cat['budget_head'];
				//$project 			= $cat['project'];
				$adjustment_budget	= $cat['adjustment_budget'];
				$total_budget		= $cat['total_budget'] + $adjustment_budget;
				$used_budget		= $cat['used_budget'];
				$blocked_budget		= $cat['blocked_budget'];
				$balance_budget		= ( $total_budget ) - ( $used_budget + $blocked_budget );
				$sql	= "SELECT * FROM gst_mst where 1 and id = '$gst_type' ";
				$rs 	= mysqli_query($con, $sql);
				echo mysqli_error($con);
				$rw 	= mysqli_fetch_array($rs);
				$igst   = $rw['igst'];
//echo $sql."<BR>";				
				
				$sql = "UPDATE sma_po_items set product_name = '$product_name', 
							balance_budget= '$balance_budget', total_budget = '$total_budget',
						budget_id = '$budget_id'
						where id = '$po_item_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
//echo $sql."<BR>";
				if($igst==0 || empty($igst) ){
					$igst = '0';
				}	
				$sql = "UPDATE sma_approval_items SET po_no = '$srno', po_quantity = po_quantity + quantity , 
					po_value = po_value + (( quantity * unit_rate ) + (( (quantity * unit_rate ) * $igst ) / 100) ),
					reversal_item_no = '$po_item_id'
					WHERE id = '$pr_item_id' "; //purchase_req_id = '$approval_memo_ref' AND product_id = '$product_id'
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
			} */

			/* $sql = " SELECT * FROM `file_uploads` where 1 and module = 'PR' and reference_id = '$approval_memo_ref' ";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$value="";
//echo $sql."<BR>";				
			while($row2 = mysqli_fetch_array($result)){
				
				$file_name 	= $row2['file_name'];
				$doc_type 	= $row2['doc_type'];
				$doc_desc 	= $row2['doc_desc'];
				
				$folder_path = "uploads/po/" . $srno;
					if (!file_exists($folder_path)){
						mkdir($folder_path, 0755, true);
						$findex = $folder_path.'/index.php';
						fopen($findex,'w');
					}
					
				$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) 
					VALUES( 'PO', '$filename', '$folder_path' , '$doc_type',  '$doc_desc',  '$srno', now() )";
				mysqli_query($con, $sql);
				$folder_path_from = "uploads/pr/" . $approval_memo_ref.'/'.$filename;
				$folder_path_to   = "uploads/po/" . $srno.'/'.$filename;
				
				copy($folder_path_from, $folder_path_to);
				
			} */
//echo $sql."<BR>";	
//exit('EXIT HERE....');			
		
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, remarks) values( 'PO', '$srno', '$userid', now(), 'Draft' , 'Open PO without NOA')";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			//echo "Purchase Order successful added";

			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject. ','. ' Open PO without NOA';
		    $affect 		= 'Added';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql."<BR>";	
//exit('EXIT HERE....');				
			$baseurl.=$modulePath.'edit.php?id='.$srno.''; //&active=active
			echo "<script>window.location.href='$baseurl';</script>";
			exit();
			
	}
	
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             <?= $sub_menu;?> 
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> <?= $sub_menu;?> </a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="add.php?sub=Save" method="post">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
									
									<?php
									
										$sql="Select max(id) as id from sma_purchase_order ";
										$query = mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($query);
										$srno = $r2['id'] + 1;
									?>
									<div class="form-group">
										<input type="hidden" class="form-control" id="srno" name="srno" style="text-align:right;" placeholder="" value="<?php echo $srno;?>" >

										
											<div class="col-sm-2">
											    <label for="project" class="control-label ">Order Type **</label>
											<select class="form-control " name="po_doc_type" id="po_doc_type" required <?php echo $readonly; ?> >
												<option value=""> Select </option>
												<!--<option value="PO" <?php echo ($row['po_doc_type'] == "PO" )?'selected="selected"':'';?> > Purchase Order </option>
												<option value="SO" <?php echo ($row['po_doc_type'] == "SO" )?'selected="selected"':'';?> > Service Order </option>
												<option value="WO" <?php echo ($row['po_doc_type'] == "WO" )?'selected="selected"':'';?> > Work Order </option>
												<option value="CA" <?php echo ($row['po_doc_type'] == "CA" )?'selected="selected"':'';?> > Contract Agreement </option>-->
													
													<?php $sql = "select * from po_order_type where 1 order by po_doc_type ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['po_doc_type'];?>" <?php echo ($row['po_doc_type'] == $r2['po_doc_type'])?'selected="selected"':'';?> >  <?php echo $r2['po_doc_desc'];?></option>
													<?php } ?>
											</select>
										</div>
											
										<input type="hidden" name='po_type' id='po_typec' value='C' >
										
										<div class="col-sm-4">

											<label for="project" class="control-label">Company<span style="color:redd;"> **</span></label>
											<select class="form-control select2123" name="project" id="project" 
											onchange="getlocation(this.value);  getcompanyterm(this.value);getsupplier(this.value);getapproval(this.value);getworkflowtype(this.value);" required >
												<option value=""> Select </option>
													<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
													<?php } ?>
											</select>		
										</div>
										
									<!--	<div class="col-md-4">
											<label class="control-label">Against NOA Number<span style="color:redd;"> **</span></label>
											<span id ="getapproval">
											<select class="form-control select2" required name="approval_memo_ref" id="approval_memo_Ref"  required >
												<option value=""> Select </option>
													
											</select>
											</span>
										</div>
										-->
								</div>
								
								<span id="getquoteref">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label">Department <span style="color:red;"> **</span></label>
											
											<select class="form-control" name="department" id="department" required >
												<option value=""> Select </option>
													<?php $sql = "select * from sma_department order by name ";
													$q2 	  = mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
													<?php } ?>
											</select>
										</div>
									
									</div>	
										
										<div class="form-group">
										
											<div class="col-sm-12">
											<label for="company_id" class="control-label ">Subject</label>
												
												<input type="text" class="form-control" id="subject" name="subject" value="<?php echo $row['subject'];?>" >
											</div>
											
										</div>
										
										
								</span>
								
								<span id="getbudgetError" style="color:red;font-size:16px;">
								</span>
								
								<span id="getbudgetOK" style="color:#00CA00;font-size:16px;">
								</span>
								
								<div class="form-group">
								
										<div class="col-sm-2">
										<label for="deliveryLocation" class="control-label">Delivery Location**</label>
												<span id="getlocation">
													<select class="form-control" id="location" name="location" required >
														<option value="">Select</option>
													
													</select>
												</span>
										</div>
										
										<span id="getdelvaddr">
										<div class="col-md-5">
											<label class="control-label">Delivery Address</label>
											
												<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address"></textarea>
										
										</div>
										</span>
										
									<!--<span id ="gettenderNO"> 	-->
									<!--	<div class="col-md-2">-->
									<!--		<label class="control-label">Tender No.</label>-->
									<!--		<input type="text" class="form-control" id="tender_no" name="tender_no" value="<?php echo $row['tender_no'];?>" onchange="gettenderTitle(this.value)" >-->
									<!--	</div>-->
																			
									<!--	<div class="col-md-3">-->
									<!--		<span id="gettenderTitle"> </span>-->
									<!--	</div>-->
									<!--</span>	-->
										
								</div>
								
									<div class="form-group">
										
										<div class="col-md-3">
											<label class="control-label"> Order Number</label>
											<input type="text" class="form-control" id="po_number" name="po_number"  style="text-align:left;" placeholder="" value="" >
										</div>
										
										<div class="col-md-3">
											<label class="control-label"> Order Date</label>
												<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
													<div class="input-group-addon">
														<i class="fa fa-calendar-alt"></i>
													</div>
													<input type="text" class="form-control" id="dated" name="dated" placeholder="dd-mm-yyyy" readonly value="<?php echo date("d-m-Y");?>">
												</div>
										</div>
										
										<div class="col-md-2">
											<label class="control-label">Credit Days</label>
											<input type="text" class="form-control" id="credit_days" name="credit_days" maxlength="3" style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
										</div>
									
									</div>
									
									<div class="form-group">
									
										<!--<div class="col-md-2">
											<label class="control-label">Discount Amount</label>
											<input type="text" class="form-control" id="discount" name="discount" style="text-align:right;" value="" >
										</div>
									
										<div class="col-md-2">
											<label class="control-label">Transport Amount</label>
											<input type="text" class="form-control" id="transport" name="transport" style="text-align:right;" value="" >
										</div>
										
										<div class="col-md-2">
											<label class="control-label">Other Charges</label>
											<input type="text" class="form-control" id="other_charges" name="other_charges" style="text-align:right;" value="" >
										</div>
										-->
										<span id="hide_advance">
											<div class="col-md-3">
												<label class="control-label">Advance Payment Required?</label><br>&nbsp;&nbsp;&nbsp;
												<input type="checkbox" id="advance_flag" name="advance_flag" value="Y" >
											</div>
										</span>
								
									</div>
								
								
								<div class="form-group">
										
									<div class="col-md-12">
										<label class="control-label">Notes</label>
										<input type="text" class="form-control" id="notes" name="notes" value="<?php echo $row['notes'];?>" >
									</div>
									
								</div>
								
								<div class="box-footer">
									
									<div class="col-sm-6 text-right">
										<span>&nbsp;&nbsp;</span>
										
									</div>
									<div class="col-sm-6 text-right">
										
										<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
										
										<span>&nbsp;&nbsp;</span>
									<span id="hideSave123">	
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
									</span>	
									</div>
								</div>	
								
								</fieldset>
							</div>	
						</form>
                    </div>
                </div>
                
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->

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
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<script>
   
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

	

	function getproject(id){
		
        var sub    = 'sub1';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getproject').html(result);
		});

	}

	function getbudget(id){
		
        var sub    = 'sub2';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}

	
	function getlocation(id){
		
        var sub    = 'sub5';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getdelvaddr(id){
        var sub    = 'sub6';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getdelvaddr').html(result);
		});
	}

	function getsupplier(id){
        var sub    = 'sub7';
		
		var po_type = 'C';
//alert(sub + ' ' + po_type);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,po_type:po_type,sub7:sub},function(result){
		      $('#getsupplier').html(result);
		});
	}


	/* function getqref(id){
        var sub    = 'sub8';
        var approval_hdr_id    = document.getElementById("approval_memo_Ref").value;
//alert(approval_hdr_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub,approval_hdr_id:approval_hdr_id},function(result){
		      $('#getqref').html(result);
		});
	} */

	function get_taxstatus(id){
        var sub    = 'sub8';
		
        var location    = document.getElementById("LOCATION").value;
//alert(id + ' ' + location);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub,location:location},function(result){
		      $('#get_taxstatus').html(result);
		});
	}
	
	function getapproval(id){
		
        var sub    = 'sub12';
		
		var company_id    = document.getElementById("project").value;
		
			$('#getapproval').html('Wait');
			var strURL = "app_func.php";
			$.post(strURL,{id:id,company_id:company_id,sub12:sub},function(result){
				  $('#getapproval').html(result);
			});
		
	}
	
	function getquoteref(id){
		
        var sub    = 'sub28';
		var company_id    = document.getElementById("project").value;
//alert(sub);
		$('#hideSave').show();
			var strURL = "app_func.php";
			$.post(strURL,{id:id,company_id:company_id,sub28:sub},function(result){
				
				  $('#getquoteref').html(result);
				  
				  $('#hide_advance').hide();
			
				  /* var fields = result.split('##');

					var quotation_reference_no = fields[0];
					var department = fields[1];
				$('#quotation_reference_no').val(quotation_reference_no);	 
				*/
				
			});
			
// 			var dated = document.getElementById("dated").value;
// 			var strURL = "app_func.php";
// 			$.post(strURL,{id:id,dated:dated,sub38:sub},function(result){
// 				var fields = result;
// 				//alert(result);
// 				var myArray  = fields.split("XX");
// 				var fields = myArray ['0'];
// 				fields = fields.trim();
// 				//alert(fields);
// 				fields = fields.slice(0, 2);
// 				//fields = fields.trim();
// 				if(fields == 'OK'){
// 					$('#getbudgetError').html('');	
// 					var fields = myArray ['1'];
// 					$('#getbudgetOK').html(fields);
// 					$('#hideSave').show();
// 				}
// 				else {	
// 					$('#getbudgetOK').html('');	
// 					$('#getbudgetError').html(result);
// 					$('#hideSave').hide();
// 				}
				
// 			});	   
		
	}
	
	function getcompanyterm(id){
		var sub    = 'sub25';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub25:sub},function(result){
		      $('#getcompanyterm').html(result);
		});
	}	
	
	function getspecialterms(id){
		var sub    = 'sub26';
//alert(id+ sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub26:sub},function(result){
		      $('#getspecialterms').html(result);
		});
	}	

	function getworkflowtype(id){
		
        var sub    = 'sub27';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub27:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
	
	function getworkflowtype123(id){
		
        var sub    = 'sub29';
//alert(sub);

		if (document.getElementById('po_typec').checked){
		    po_type = document.getElementById('po_typec').value;
		}
		else if (document.getElementById('po_typea').checked){
		    po_type = document.getElementById('po_typea').value;
		}
		
		if( po_type=='C' ){
			var doc_type = 'AP';
			
		}
		else {
			var doc_type = 'PO';
		}	

		var strURL = "app_func.php";
			$.post(strURL,{doc_type:doc_type,sub29:sub},function(result){
				  $('#getworkflowtype').html(result);
			});
	}
	
	function gettenderNO(id){
		var sub    = 'sub36';
		
		if (document.getElementById('po_typec').checked){
		    po_type = document.getElementById('po_typec').value;
		}
		
//alert(sub + ' ' + po_type);
		if(po_type=='C'){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub36:sub},function(result){
				  $('#gettenderNO').html(result);
			});
		}
		
	}
	
	function gettenderTitle(id){
		
        var sub    = 'sub37';
		var company_id = document.getElementById("project").value;
		
		if (document.getElementById('po_typec').checked){
		    po_type = document.getElementById('po_typec').value;
		}
		
//alert(sub + ' ' + company_id);
		if(po_type=='C'){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,company_id:company_id,sub37:sub},function(result){
				  $('#gettenderTitle').html(result);
			});
		}

	}
	
</script>

</body>
</html>
