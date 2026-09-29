<?php
include("../header.php");
$modulePath = "supp_invoice/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	if(isset($_POST['Save'])){
			
			$srno					= $_POST['srno'];
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$company_id				= $_POST['company_id'];
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
			$status 				= 'Draft';

			$due_date =  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['invoice_date'])));
			
			$user   	= $_SESSION['user'];
			$userid     = $_SESSION['usrid'];

  			$sql="insert into sma_supplier_invoice (id, supplier_invoice_no, company_id, invoice_date, our_po_ref_no, delivery_challen_no, delivery_date, suplier_name, transport_lr_no, lr_date, transporter_name, credit_days, due_date, state, status , draft_by, draft_date )
			Values('$srno', '$supplier_invoice_no', '$company_id', '$invoice_date', '$our_po_ref_no', '$delivery_challen_no', '$delivery_date', '$suplier_name', '$transport_lr_no', '$lr_date', '$transporter_name', '$credit_days', '$due_date', '$state', '$status' , '$user', now() )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//echo "Supplier Invoice successful added";

			$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'SI', '$srno', '$userid', now(), '$status' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';

			echo "<script>window.location.href='$baseurl';</script>";
		
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
            <form class="form-horizontal" action="add.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
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
								<label class="control-label">Supplier Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" required name="supplier_invoice_no" placeholder="" value="<?php echo $row['supplier_invoice_no'];?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="prDate" name="invoice_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
							
							<div class="col-sm-4">

								<label for="project" class="control-label">Company<span style="color:red;"> **</span></label>
								<select class="form-control select2" name="company_id" id="company_id" required >
								<option value=""> Select </option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
										
						</div>
						
						<div class="form-group">
						
							<div class="col-md-4">
								<label class="control-label">Supplier Name<span style="color:red;"> **</span></label>
								<select class="form-control" name="suplier_name" id="suplier_name" required onchange="getporefno(this.value); getstate(this.value)">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Our PO Ref.No.<span style="color:red;"> **</span></label>
								<span id="getporefno">
									<input type="text" class="form-control" id="our_po_ref_no" name="our_po_ref_no" placeholder="" value="" >
								</span>
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<span id="getcreditdays">
									<input type="text" class="form-control" id="credit_days" name="credit_days"  maxlength="3" value="" >
								</span>	
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
									
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="prDate" name="due_date" value="<?php echo date("d-m-Y"); ?>" >
									
								</div>	
							</div>
							
						</div>
						
                    <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="<?php echo $baseurl.=$modulePath;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
                        
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl;?>" class="btn btn-default" >Back</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								<input class="btn btn-primary" type="submit" value="Next" name="Save">&nbsp;&nbsp;&nbsp;
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

<?php 	
		include("../footer.php");	
?> 


<script>

    /*
				There is a bug in datepicker format due to which it does not set for AdminLTE 2 theme.
				Defaulting dates to mm/dd/yyyy format.

				$('.datepicker').datepicker({
						format: 'd/M/Y',
						autoClose: 1
				});
	*/

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
 
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
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
//		var invoice_date = document.getElementById(invoice_date);
//	alert(invoice_date);,invoice_date:invoice_date
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
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
		
</script>



</body>
</html>
	