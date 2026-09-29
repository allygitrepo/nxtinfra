<?php
	include("../header.php");
	$modulePath = "supp_invoice/";
	
	$help_code = $modulePath.'indexgrn.php';
	include "../help_code.php";
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php

	if(isset($_POST['Save'])){
			
			$srno					= $_POST['srno'];
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_type			= $_POST['invoice_type'];
			
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$created_date			= date('Y-m-d', strtotime($_POST['created_date']));
			$company_id				= $_POST['company_id'];
		//	$trans_type				= $_POST['trans_type'];
			$suplier_name			= $_POST['suplier_name'];
			$our_po_ref_no			= $_POST['our_po_ref_no'];
			$our_pr_no				= $_POST['our_pr_no'];
			$gst_flag				= $_POST['gst_flag'];
			//$tds_percentage			= $_POST['tds_percentage'];
			
			$delivery_challen_no	= $_POST['delivery_challen_no'];
			$delivery_mode			= $_POST['delivery_mode'];
			$budget_head			= $_POST['budget_head'];
			$delivery_date			= date('Y-m-d', strtotime($_POST['delivery_date']));
			$transport_lr_no		= $_POST['transport_lr_no'];
			$lr_date				= date('Y-m-d', strtotime($_POST['lr_date']));
			$transporter_name		= $_POST['transporter_name'];
			$credit_days			= $_POST['credit_days'];
			$due_date				= date('Y-m-d', strtotime($_POST['due_date']));
			$state					= $_POST['state'];
			$file_attachment		= $_POST['file_attachment'];
			$status 				= 'Draft';
			//$retention_flag			= $_POST['retention_flag'];
			$retention_flag			= '';
			$supplier_gst_no		= $_POST['supplier_gst_no'];
			$supplier_location		= $_POST['supplier_location'];
			$bill_no				= $_POST['bill_no'];
			//$bill_date 				= date('Y-m-d', strtotime($_POST['bill_date']));
			$department				= $_POST['department'];
			$trans_type				= $_POST['trans_type'];
			
			$prev_date_flag				= $_POST['prev_date_flag'];
			$prev_date_flag_creatby 	= $_POST['prev_date_flag_creatby'];
			$prev_date_flag_date		= $_POST['prev_date_flag_date'];
			$prev_date_flag_remark		= $_POST['prev_date_flag_remark '];

			$location_id		= $_POST['location_id'];
			
			if(empty($our_pr_no) ||  $our_pr_no==0){
				$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$our_pr_no		= $r1['approval_memo_ref'];
			}
			
			if(!empty($credit_days)){
 				$due_date =  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['invoice_date'])));
			}
			
			$invoice_received_date = date('Y-m-d', strtotime($_POST['invoice_received_date']));
			
			$user   	= $_SESSION['user'];
			$userid     = $_SESSION['usrid'];
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'indexgrn.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
  			$sql="INSERT INTO sma_supplier_invoice (id, supplier_invoice_no, company_id, invoice_date, created_date, our_po_ref_no, our_pr_no, delivery_challen_no, delivery_date, delivery_mode, suplier_name, transport_lr_no, lr_date, transporter_name, credit_days, due_date, state, retention_flag, grn_status , grndraft_by, grndraft_date, supplier_gst_no, supplier_location, bill_no, department,  invoice_type, trans_type, gst_flag , location, prev_date_flag, prev_date_flag_creatby, prev_date_flag_date, prev_date_flag_remark, invoice_received_date )
			Values('$srno', '$supplier_invoice_no', '$company_id', '$invoice_date', '$created_date', '$our_po_ref_no', '$our_pr_no', '$delivery_challen_no', '$delivery_date', '$delivery_mode', '$suplier_name', '$transport_lr_no', '$lr_date', '$transporter_name', '$credit_days', '$due_date', '$state', '$retention_flag', '$status' , '$user', now(), '$supplier_gst_no', '$supplier_location', '$bill_no', '$department',  '$invoice_type', '$trans_type', '$gst_flag', '$location_id' , '$prev_date_flag', '$userid', now(), '$prev_date_flag_remark', '$invoice_received_date' )";
//echo $sql."<BR>";			
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){
				echo "<script>alert('".$error."')</script>";
				$baseurl.=$modulePath.'editgrn.php?id='.$srno;//.'&active=active'
				echo "<script>window.location.href='$baseurl';</script>";	
				exit();	
			}
			
//echo $sql; exit();
//echo $our_po_ref_no. "<BR>";
		    if(!empty($our_po_ref_no)){
				$gst_cal = " + ((quantity * unit_rate) * gst) / 100 ";
				$sql = " INSERT INTO sma_supplier_invoice_details ( si_hdr_id, po_item_id, grn_no, material_id, material_name, description, product_category, po_threashold, budget_id, budget_name, budget_head, po_qty,  unit, rate, gst, gst_id, amount, delivery_date, company_id, first_insert ) 
				SELECT '$srno', id, purchase_id, product_id, product_name, product_desc, product_category, po_threashold, budget_id, budget_name, budget_head, (quantity - bal_si_qty) as quantity, uom, unit_rate, gst, gst_id, round(((quantity * unit_rate) $gst_cal),0) as amount, delivery_date, company_id, 'F' 
				FROM `sma_po_items` 
				WHERE 1 and ( (quantity > bal_si_qty ) or ((quantity * unit_rate) $gst_cal >= bal_si_amount) )
					and purchase_id = '$our_po_ref_no' ";
//echo $sql;
//exit();					
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
	
			}
//echo $sql."<BR>";	exit();
					
			//echo "GRN successful added";

			
			$sql = " SELECT a.*, b.company_id FROM sma_supplier_invoice_details a, sma_supplier_invoice b 
							WHERE b.id = a.si_hdr_id and a.si_hdr_id = '$srno' ";
//echo $sql. "<BR>";
			$q2=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){ echo $error; }
			while($r2 = mysqli_fetch_array($q2)){
				
				$item_id  		= $r2['id'];
				$product_id  	= $r2['material_id'];
				$unit		  	= $r2['unit'];
				//$bal_qty  		= $r2['po_qty'];
				
				$bal_qty  		= 0;
				
				$sql = "SELECT * FROM sma_product_open_stock where product_name = '$product_id' and project = '$company_id' ";
//echo $sql. "<BR>";
				mysqli_query($con, $sql);
				$rowaffected = mysqli_affected_rows($con);
				echo mysqli_error($con);
				if($rowaffected==0){
					$sql = " INSERT INTO sma_product_open_stock ( product_name, project, issue, created_date, remarkv , uom) 
								VALUE ( '$product_id', '$company_id', '$bal_qty', now(), 'Inserted', '$unit' ) ";
//echo $sql. "<BR>";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
				else {
					$sql = "UPDATE sma_product_open_stock set issue = issue + $bal_qty where product_name = '$product_id' and project = '$company_id'  ";//and ( opening_stock + receipts ) >= issue
//echo $sql. "<BR>";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
			
			}	
			

			$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'GR', '$srno', '$userid', now(), '$status' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $sql."<BR>";			
//exit();
			$baseurl.=$modulePath.'editgrn.php?id='.$srno;//.'&active=active'

			echo "<script>window.location.href='$baseurl';</script>";
		
		}

?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            GRN 
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath.'indexgrn.php' ?>">GRN </a></li>
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
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form class="form-horizontal" action="addgrn.php?sub=add" method="post" enctype="multipart/form-data">
							<?php
							$sql  = " SELECT max(id) as srno from sma_supplier_invoice ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$srno = $r1['srno']+1;
							?>
					
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Created Date</label>
								<div class="input-group date" data-provide="datepicker123" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="created_date" name="created_date" readonly value="<?php echo date('d-m-Y'); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	
							
							<div class="col-md-4">
								<label for="project" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" name="company_id" id="company_ID" onchange="getsupplier(this.value);getworkflowtype(this.value);" required >
								<option value=""> Select</option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>
							</div>
							
							
							<div class="col-md-4">
								<label class="control-label">Supplier Name <span style="color:red;"> **</span> </label>
							<span id="getsupplier">	
								
								<select class="form-control" name="suplier_name" id="suplier_NAME" required onchange="getporefno(this.value); getstate(this.value)">
									<option value=""> Select </option>
								</select>
							</span>		
							</div>
							
						</div>
						
						<div class="form-group">
							<span id="getporefno">
							
								<div class="col-md-2">
									<label class="control-label">GST .No.</label>
									<input type="text" class="form-control" id="supplier_gst_no" name="supplier_gst_no" readonly placeholder="" value="" >
								</div>
								
								<div class="col-md-4">
									<label class="control-label">Our PO Ref.No.</label>
									<input type="text" class="form-control" id="our_po_ref_no" name="our_po_ref_no" placeholder="" value=""  >
								</div>
								
								
							</span>
							
							<div class="col-md-2">
									<label for="po_date" class="control-label">PO Date</label>
									<input type="text" class="form-control" style="text-align:left;" readonly id="PO_DATE" value='' >
							</div>	
							
							<span id="getprrefno">
							<div class="col-md-2">
									<label class="control-label">PR.No.</label>
									<input type="text" readonly class="form-control" id="our_pr_no" name="our_pr_no" placeholder="" value=""  >
							</div>
							</span>		
								
							<div class="col-md-2">
								<label for="advance_paid_amount" class="control-label">Advance Amount</label>
								<input type="text" class="form-control" style="text-align:right;" readonly id="advance_paid_amount" value='' >
							</div>	
							
						</div>
						
						<div class="form-group">
						
						<span id="getcreditdays">
							
							<div class="col-md-4">
								<label class="control-label">Department <span style="color:red;"> <span style="color:red;"> **</span></span></label>
								<select class="form-control" name="department" id="department" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
									<label class="control-label">Tax Status<span style="color:red;"> **</span></label>
									<select class="form-control select3" name="supplier_location" id="supplier_location" required >
										<option value=""> Select </option>
										<option value="L" <?php echo ($row['supplier_location'] == 'L')?'selected="selected"':'';?> selected > Local </option>
										<option value="O" <?php echo ($row['supplier_location'] == 'O')?'selected="selected"':'';?>> Out of State </option>
									</select>
							</div>
								
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								
								<input type="text" class="form-control" id="credit_days" name="credit_days"  maxlength="3" value="" >
								
							</div>
								
							
						</span>	
						
							<!--<div class="col-md-3">	-->
							<!--	<label class="control-label">Workflow Type</label>-->
							<!--<span id="getworkflowtype123">-->
							<!--	<select class="form-control select3" name="trans_type" id="trans_type" required123 >-->
							<!--	<option value=""> Select </option>-->
							<!--	</select>-->
							<!--</span>-->
							<!--</div>-->
							
									
						</div>
						
						<div class="form-group col-md-12" id="checkinvoice_no" style="color:red;" >
								
						</div>
						
					<!--	<div class="form-group">
							<div class="col-md-6">
							<label class="control-label" style="text-align:left;">Invoice is Before PO ? (If Ticked, Allow Invoice date before PO Date) </label>
								<input type="checkbox" <?= $prev_date_flag ?> class="prev_date_flag" id="prev_date_FLAG" name="prev_date_flag" value="Y" onchange="checkinvDate(this.value);"  >
							</div>
						</div>
					-->
					
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Tax Invoice No. <span style="color:red;"> **</span> </label>
								<input type="text" class="form-control" id="supplier_invoice_no" required 
								name="supplier_invoice_no" placeholder="" value="" 
								onblur="checkinvoice_no(this.value);" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date<span style="color:red;"> **</span></label>
								
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date" required value="<?php echo date('d-m-Y'); ?>" onchange="checkinvDate(this.value);" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
								<span id="checkinvDate" style="color:red;" ></span>
								
							</div>	
							
								
								<div class="col-md-2">
									<label class="control-label">Due Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="due_date" name="due_date"  value="" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							    </div>
						
								<div class="col-md-2">
									<label class="control-label">Invoice Received Date</label>
									<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control"   id="invoice_received_date" name="invoice_received_date" value="<?= $invoice_received_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
							
							
								<div class="col-md-2">
								<label class="control-label">Delivery Challan No. <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" id="delivery_challen_no"  name="delivery_challen_no" placeholder="" value="" >
								</div>
							
								<div class="col-md-2">
									<label class="control-label">Delivery Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="bill_date" name="delivery_date" value="" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>

								<div class="col-md-2">
								<label class="control-label">Mode of Delivery <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" id="delivery_mode"  name="delivery_mode" placeholder="" value="" >
								</div>

							<!--<div class="col-md-6">
								<label class="control-label">Post GST into Expense A/c associated with Product? No Reverse Credit:</label><br>
								<input type="checkbox" checked id="gst_flag" name="gst_flag" placeholder="" value="Y" >
							</div>-->
							
						</div>
						
							
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl.$modulePath.'indexgrn.php';?>" class="btn btn-default" >Back</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								
							<span id="hidediv">							
								<input class="btn btn-primary" type="submit" onclick="getvalidate()"; value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</span>
							
							</div>
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
 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  -->
<script>

	function getsupplier(id){
		
        var sub    				= 'sub14';
		//var company_id		 	=  $("#company_id").val();
		var company_id		 	= id;

		var strURL = "si_func.php";
//alert(sub + ' ' + company_id);		
		$.post(strURL,{company_id:company_id,sub14:sub},function(result){
		      $('#getsupplier').html(result);
			  //alert('result');
		});

	}
	
	function getporefno(id){
		
        var sub    = 'sub1';
		var company_id		 	=  $("#company_ID").val();
//alert(sub + ' ' + company_id);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}
	
	function getprrefno(id){
		var sub    = 'sub44';
		
		var strURL = "si_func.php";
		$.post(strURL,{po_id:id,sub44:sub},function(result){
		//alert(result);      
			  $('#getprrefno').html(result);
			  
		});

	}

	
	function getcreditdays(id){
		
        var sub    = 'sub4';
		//var suplier_id = document.getElementById("suplier_NAME");
		var suplier_id		 =  $("#suplier_NAME").val();
	//alert(suplier_id);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,suplier_id:suplier_id,sub4:sub},function(result){
		//alert(result);      
			  var company_id = result;
			  var splitString = result.split("##");
			  var company_id =  splitString['1'];
			  var advance_paid_amount =  splitString['2'];
			  var po_date	 =  splitString['3'];
	  
			  var result = splitString['0'];
			  $('#getcreditdays').html(result);
			  
			  $('#advance_paid_amount').val(advance_paid_amount);
			  $('#PO_DATE').val(po_date);
			  
/* 			    var sub    = 'sub12';
			    var strURL = "app_func.php";
				$.post(strURL,{id:company_id,sub12:sub},function(result){
					  $('#getworkflowtype').html(result);
				}); */
				
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

	function getworkflowtype(id){
		
        var sub    = 'sub12';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub12:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
		
	function getvalidate(){
		
		var project 	=  $("#company_ID").val();
		var po_doc_type =  $("#trans_type").val();
		var location 	=  $("#location").val();
		var department 	=  $("#department").val();
		var quotation_reference_no 	=  $("#our_po_ref_NO").val();
		var to_supplier 	= $("#suplier_NAME").val();
		
		if(to_supplier==''){
			alert('Supplier selection mandatory !!!');
			return;
		}
		
 		if(quotation_reference_no=='' ){
			alert('PO Ref. No. selection mandatory !!!');
			return;
		}

		if(project==''){
			alert('Company selection mandatory !!!');
			return;
		}
/* 		if(po_doc_type==''){
			alert('Workflow type selection mandatory !!!');
			return;
		} 
*/
		
		if(location==''){
			alert('Location selection mandatory !!!');
			return;
		}
		if(department==''){
			alert('Department selection mandatory !!!');
			return;
		}
				
		
	}	
	
	
	function checkinvoice_no(id){
		
		var sub    = 'sub20';
		var suplier_id 	=  $("#suplier_NAME").val();
		var company_id  =  $("#company_ID").val();
//alert(sub+ ' ' + suplier_id + ' ' + id + ' ' + company_id); 
		$('#checkinvoice_no').html('');
		
			var strURL = "app_func.php";
			$.post(strURL,{id:id,suplier_id:suplier_id,company_id:company_id,sub20:sub},function(result){
				var rowaffected = result;
				 //alert(rowaffected);
				  if(rowaffected>0){
					  var result = "Error : Invoice Number already available for supplier !";
					  alert(result);
					  $('#checkinvoice_no').html(result);
					  
					  $('#hidediv').hide();
				  }
				  else {
					$('#hidediv').show();	
				  }	  
				 // $('#checkinvoice_no').html(result);
				  
			});
		
		
	}	
	
	function checkinvDate(invdate){
	  
		var sub    = 'sub23';
		var our_po_ref_no  =  $("#our_po_ref_NO").val();
		//alert(our_po_ref_no+ ' ###1 ' + invdate);
		var prev_date_flag = '';
		
		$('#checkinvDate').html('');
		$('#hidediv').show();
		if (document.getElementById('prev_date_FLAG').checked) {
		   // gst_flag = document.getElementById('gst_FLAG').value;
			var prev_date_flag = 'Y';
		}
		
		if(prev_date_flag!='Y'){
			var strURL = "app_func.php";
			$.post(strURL,{our_po_ref_no:our_po_ref_no,invdate:invdate,sub23:sub},function(result){
				var rowaffected = result;
				// alert(rowaffected + ' ###2 ');
				  if(rowaffected>0){
					  var result = "Error : Invoice date should be greater than PO Date !";
					  //alert(result);
					  $('#checkinvDate').html(result);
					  
					  $('#hidediv').hide();
				  }
				  else {
					$('#hidediv').show();
					$('#checkinvDate').html(' ');
				  }

			});
		
		}
		
	}
	
</script>

</body>
</html>
