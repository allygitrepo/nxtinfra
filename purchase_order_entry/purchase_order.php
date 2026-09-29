<!DOCTYPE html>
<?php
	include("../header.php");
	$modulePath = "purchase_order_entry/";  
	$module_name = "purchase_order_entry.php?sub=list";

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_purchase_order where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="purchase_order_entry.php?sub=list";</script>';
		
		
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){
			$srno				= $_POST['srno'];
			$po_number			= $_POST['po_number'];
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$location			= $_POST['location'];
			$department			= $_POST['department'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
//			$against_indent_no	= $_POST['against_indent_no'];
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			$prepared_by		= $_POST['prepared_by'];
			$approved_by		= $_POST['approved_by'];
			$checked_by			= $_POST['checked_by'];
			$status				= $_POST['status'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			$other_charges		= $_POST['other_charges'];
			$discount			= $_POST['discount'];
			$transport			= $_POST['transport'];
			

			$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
			
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget = $r2['name'];
			
			$sql = "SELECT * FROM company where comp_id = '$project' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$comp_code = $r2['comp_code'];
			
			$sql = "SELECT * FROM sma_location where id = '$location' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$loc_code = $r2['loc_code'];
			
			$yyyy = date('Y'). '-'. (date('y')+1);
						
			$po_number = $comp_code.'/'.$yyyy.'/'.$loc_code.'/'.$budget.'/'.$srno;
		
  			$sql="insert into sma_purchase_order (id, po_number, approval_memo_ref,dated, project, location, department, budget_name, budget_head, quotation_reference_no, to_supplier, delivery_days, credit_days, payment_terms, status, delivery_date, terms, other_charges, discount, transport, prepared_by, approved_by, checked_by) values('$srno', '$po_number', '$approval_memo_ref', '$dated', '$project', '$location', '$department', '$budget_name', '$budget_head', '$quotation_reference_no', '$to_supplier', '$delivery_days', '$credit_days', '$payment_terms', '$status', '$delivery_date', '$terms',  '$other_charges', '$discount', '$transport', '$prepared_by', '$approved_by', '$checked_by')";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Purchase Order successful added";
			echo '<script>window.location.href="purchase_order_entry.php?sub=list";</script>';
		}

?>

   <section class="content-header">
        <h1>
            Purchase Order
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Purchase Order</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="purchase_order_entry.php?sub=add" method="post">
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
							
							<div class="col-md-3">
								<label class="control-label">PO.Number</label>
								<input type="text" class="form-control" id="po_number" name="po_number" readonly style="text-align:left;" placeholder="" value="<?php echo $po_number;?>" >
							</div>
							
							<div class="col-md-3">
								<label class="control-label">Purchase Order Date</label>
						            <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="dated" name="dated" placeholder="dd-mm-yyyy" value="">
									</div>
							</div>
							
							<div class="col-md-3">
								<label class="control-label">Against Approval Memo Ref</label>
								<select class="form-control select2" name="approval_memo_ref" id="approval_memo_ref" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_approval_memo order by id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_memo_ref'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['id'].' '. date('d-m-Y', strtotime($r2['dated']));?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Department</label>
								<select class="form-control" name="department" id="department" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<!--
						<div class="form-group">
							
							<div class="col-sm-4">
								<label for="project" class="control-label">Company</label>
								<select class="form-control select2" name="project" id="project" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<div class="col-sm-4">
							<label for="deliveryLocation" class="control-label">Location</label>
									<span id="getlocation">
										<select class="form-control" id="location" name="location">
											<option value="">Select</option>
										<?php
											$sql="SELECT id, loc_name FROM sma_location ORDER BY loc_name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($row = mysqli_fetch_array($result)){
										?>
											<option value="<?php echo $row['id']?>"><?php echo $row['loc_name'] ?></option>
											<?php } ?>
										</select>
									</span>
							</div>		
						 
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
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
						
							<div class="col-md-4">
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
							
						</div>-->
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Supplier Quote Ref.No.</label>
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" placeholder="" value="<?php echo $row['quotation_reference_no'];?>" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">To Supplier</label>
								<select class="form-control" name="to_supplier" id="to_supplier" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['to_supplier'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Delivery Days</label>
								<input type="text" class="form-control" id="delivery_days" name="delivery_days" style="text-align:right;" value="<?php echo $row['delivery_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Status</label>
								<select class="form-control" name="status" id="status" >
									<option value=""> Select </option>
									<option value="Prepared"> Prepared </option>
									<option value="Checked"> Checked </option>
									<option value="Not Approved"> Not Approved </option>
									<option value="Approved"> Approved </option>
									<option value="Released"> Released </option>
									<option value="Cancelled"> Cancelled </option>
								</select>	
							</div>
							
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
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
							
						</div>
						
						<div class="form-group">
						
							<div class="col-md-12">
								<label class="control-label">Terms</label>
								<textarea rows="2" class="form-control" id="terms" name="terms" placeholder="" value="<?php echo $row['terms'];?>" ></textarea>
							</div>
							
						</div>
							
                    <!--    <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="purchase_order_entry.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>
                        -->
					<div class="box-footer">
						<div class="col-sm-6">
							
							<a href="purchase_order_entry.php?sub=delete&id=<?php echo $id; ?>"  class="btn btn-danger btn-inverse">Delete</a>							
						</div>
						<div class="col-sm-6 text-right">
							<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
							
							<span>&nbsp;&nbsp;</span>
							<input class="btn btn-primary" type="submit" value="Save" name="Save">

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
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$po_id			= $_POST['id']; 
			$po_number			= $_POST['po_number']; 
			
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$location			= $_POST['location'];
			$department			= $_POST['department'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
//			$against_indent_no	= $_POST['against_indent_no'];
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			$prepared_by		= $_POST['prepared_by'];
			$approved_by		= $_POST['approved_by'];
			$checked_by			= $_POST['checked_by'];
			$status				= $_POST['status'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			$other_charges		= $_POST['other_charges'];
			$discount			= $_POST['discount'];
			$transport			= $_POST['transport'];
			
			$sql = " SELECT * FROM sma_budget_name where id in ( select budget_name from `sma_budget` where id = '$budget_name') ";
			
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget = $r2['name'];
			
			$sql = "SELECT * FROM company where comp_id = '$project' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$comp_code = $r2['comp_code'];
			
			$sql = "SELECT * FROM sma_location where id = '$location' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$loc_code = $r2['loc_code'];
			
			$yyyy = date('Y'). '-'. (date('y')+1);
			
			$srno = $id;
			
			$po_number = $comp_code.'/'.$yyyy.'/'.$loc_code.'/'.$budget.'/'.$srno;
			
  			$sql="update sma_purchase_order set approval_memo_ref	= '$approval_memo_ref', 
						po_number			= '$po_number', 
						dated				= '$dated',
						project				= '$project',
						location			= '$location',
						department			= '$department',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						quotation_reference_no	= '$quotation_reference_no',
						to_supplier			= '$to_supplier',
						delivery_days		= '$delivery_days',
						credit_days			= '$credit_days',
						payment_terms		= '$payment_terms',
						status				= '$status',
						delivery_date		= '$delivery_date',
						prepared_by			= '$prepared_by',
						approved_by			= '$approved_by',
						checked_by			= '$checked_by',
						other_charges		= '$other_charges',
						discount			= '$discount',
						transport			= '$transport',
						terms				= '$terms'
				where id='$id'";
//echo $sql. "<BR>";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/po/" . $po_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('PO', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $po_id . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/po/" . $po_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
			
//			echo $sql;
//			exit();
			
			echo "<script>window.location.href='purchase_order_entry.php?sub=edit&id=$id';</script>";
			
		}
		
		$id = $_GET['id'];
		$po_id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	<script>
        $(document).ready(function() {
            $(".doctype").select2();
            
            $("#btnaddmore").click(function() {
                var lastdocrow = $(".docrow:last");
                var totalrows = $(".docrow").length;
                var newdocrow = $(lastdocrow).clone();
                $(newdocrow).find(".control-label").html("Document " + (totalrows + 1));
                $(newdocrow).find(".doctype").val("PAN CARD");
                $(newdocrow).find(".docdesc").val("");
                $(newdocrow).find(".docfile").val("");
                $(newdocrow).find(".select2-container").remove();
                $(newdocrow).find(".doctype").select2();
                $(".docpanel").append(newdocrow);
            });
        });
</script>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <!-- Main content -->
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Order Edit</h3>
			  <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="<?php echo $baseurl . $modulePath ?>" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Back</a>
				</span>
			</div>
			</div>
		</div>	
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="purchase_order_entry.php?sub=edit" method="post" enctype="multipart/form-data">
              
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				<div class="box-body">		
					<ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Purchase Order </a></li>
                        <li><a href="#tab_2" data-toggle="tab">Documents</a></li>                        
                    </ul>
					<div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
							<div class="form-group">
						
							<div class="col-md-3">
								<label class="control-label">PO.Number</label>
								<input type="text" class="form-control" id="po_number" name="po_number" style="text-align:left;" readonly value="<?php echo $row['po_number'];?>" >
							</div>
							
							<div class="col-md-3">
								<label class="control-label">Purchase Order Date</label>
						            <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="dated" name="dated" value="<?php echo date('d-m-Y', strtotime($row['dated']));?>">
									</div>
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Against Approval Memo Ref.</label>
								<select class="form-control select2" name="approval_memo_ref" id="approval_memo_ref" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_approval_memo order by id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_memo_ref'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['id'].' '. date('d-m-Y', strtotime($r2['dated']));?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Department</label>
								<select class="form-control" name="department" id="department" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<!--
						<div class="form-group">
							
                            <div class="col-sm-4">
								<label for="project" class="control-label">Company</label>
								<select class="form-control select2" name="project" id="project" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<div class="col-sm-4">
							<label for="deliveryLocation" class="control-label">Location</label>
									<span id="getlocation">
										<select class="form-control" id="location" name="location">
											<option value="">Select</option>
										<?php
											$sql="SELECT id, loc_name FROM sma_location ORDER BY loc_name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($result)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'] ?></option>
											<?php } ?>
										</select>
									</span>
							</div>		
						 
							
						</div>
						-->
						
						<!--<div class="form-group">
							
							<div class="col-md-4">
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
						
							
							<div class="col-md-4">
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
						
						-->
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Supplier Quote Ref.No.</label>
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" placeholder="" value="<?php echo $row['quotation_reference_no'];?>" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">To Supplier</label>
								<select class="form-control" name="to_supplier" id="to_supplier" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['to_supplier'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Delivery Days</label>
								<input type="text" class="form-control" id="delivery_days" name="delivery_days" style="text-align:right;" value="<?php echo $row['delivery_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
							</div>
						
							
							
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Discount Amount</label>
								<input type="text" class="form-control" id="discount" name="discount" style="text-align:right;" value="<?php echo $row['discount'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Transport Amount</label>
								<input type="text" class="form-control" id="transport" name="transport" style="text-align:right;" value="<?php echo $row['transport'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Other Charges</label>
								<input type="text" class="form-control" id="other_charges" name="other_charges" style="text-align:right;" value="<?php echo $row['other_charges'];?>" >
							</div>
							
						</div>
							
						<div class="form-group">
						
							<div class="col-md-12">
								<label class="control-label">Terms</label>
								<textarea rows="2" class="form-control" id="terms" name="terms" placeholder="" value="<?php echo $row['terms'];?>" ></textarea>
							</div>
							
						</div>


							<div class="col-md-12">
                                <div class="box">
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
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                 <th>Material</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit</th>
                                                <th>Rate</th>
												<th>GST%</th>
                                                <th>Amount</th>
												<th>Delivery Date</th>
												<th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
												$purchase_id = $row['id'];
												$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
												$result = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$value="";
												while($row = mysqli_fetch_array($result)){
													$qty 	= $row['quantity'];
													$rate 	= $row['unit_rate'];
													$gst	= $row['gst'];
													$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
													$tot_amount = $tot_amount + $amount;
												
													$rid = $row['id'];
												?>	
													<tr>
														<td width='15%'><?php echo $rid.' '.$row['product_name']?></td>
														<td width='15%'><?php echo $row['product_desc']?></td>	
														<td width='10%' style="text-align:right;"><?php echo $row['quantity']?></td>	
														<td width='10%'><?php echo $row['uom']?></td>	
														<td width='10%' style="text-align:right;"><?php echo $row['unit_rate']?></td>	
														<td width='10%' style="text-align:right;"><?php echo $row['gst']?></td>
														<td width='10%' style="text-align:right;"><?php echo $amount?></td>					 
														<td width='10%'><?php echo date('d-m-Y', strtotime($row['delivery_date']))?></td>
														<td>
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
														
<!-- Modal Edit Item-->
													<?php include "edit_func.php"; ?>
<!-- Modal Edit Item-->
			
														<a href='#modalDeleteItem' id='delete-<?php echo $_GET['id'];?><?php echo $rid;?>' data-toggle='modal' data-id='<?php echo $_GET['id'];?><?php echo $rid;?>' data-target='#modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>'><i class='fa fa-trash-alt'></i></a></td>
<!-- Modal Delete Item-->
													<?php include "del_func.php"?>							
<!-- Modal Delete Item-->
														
													</tr>
											<?php
												}
											?>		

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
												<th></th>
                                                <th>Total Amount</th>
                                                <th style="text-align:right;"><?php echo $tot_amount;?></th>
												<th></th>
												<th></th>
                                            </tr>
                                            </tfoot>
											
                                        </table>
                                    </div>
                                </div>
                            </div>
							
                            <?php $user=$_SESSION['user']; ?>						
						<div class="form-group">							
							<div class="col-md-2">
									<label class="control-label">Prepared By</label>
									<select class="form-control" name="prepared_by" id="prepared_by" >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['userid'];?>" <?php echo (strtoupper($user) == strtoupper($r2['userid']))?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
									</select>	
							</div>
							<div class="col-md-2">
									<label class="control-label">Checked By</label>
									<select class="form-control" name="checked_by" id="checked_by" >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['userid'];?>" <?php echo (strtoupper($row['checked_by']) == strtoupper($r2['userid']))?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
									</select>	
							</div>
							<div class="col-md-2">
									<label class="control-label">Approved By</label>
									<select class="form-control" name="approved_by" id="approved_by" >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['userid'];?>" <?php echo (strtoupper($row['approved_by']) == strtoupper($r2['userid']))?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
									</select>	
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Status</label>
								<select class="form-control" name="status" id="status" >
									<option value=""> Select </option>
									<option value="Prepared" <?php echo ($row['status'] == 'Prepared')?'selected="selected"':'';?> > Prepared </option>
									<option value="Checked" <?php echo ($row['status'] == 'Checked')?'selected="selected"':'';?>> Checked </option>
									<option value="Not Approved" <?php echo ($row['status'] == 'Not Approved')?'selected="selected"':'';?>> Not Approved </option>
									<option value="Approved" <?php echo ($row['status'] == 'Approved')?'selected="selected"':'';?>> Approved </option>
									<option value="Released" <?php echo ($row['status'] == 'Released')?'selected="selected"':'';?>> Released </option>
									<option value="Cancelled" <?php echo ($row['status'] == 'Cancelled')?'selected="selected"':'';?>> Cancelled </option>
								</select>	
							</div>
							
						</div>
						
                    <!--    <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="purchase_order_entry.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>
						-->
						
					
				</div>
				
                        <div class="tab-pane" id="tab_2">
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PO' AND reference_id = " . $po_id;
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
                                <!-- Attachments - Upload Panel -->
                        <!--        <div class="col-md-12 docpanel">
                                    <div class="docrow">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Document1</label>
                                            <div class="col-sm-2">
                                                <select class="form-control select2 doctype" name="doctype[]">
                                                    <option value="PAN CARD">PAN Card</option>
                                                    <option value="AADHAAR CARD">AADHAAR Card</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="file" name="fudoc[]" class="docfile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px">
                                    <button type="button" class="btn btn-default" id="btnaddmore">Add More...</button>
                                </div>
						-->
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
				</div>
			</div>	
					<div class="box-footer">
						<div class="col-sm-6">
							<a href="purchase_order_entry.php?sub=delete&id=<?php echo $id; ?>"  class="btn btn-danger btn-inverse">Delete</a>							
						</div>
						
						<div class="col-sm-6 text-right">
							<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
							
							<span>&nbsp;&nbsp;</span>
							<input class="btn btn-primary" type="submit" value="Save" name="Save">

						</div>
					</div>	
					
                    </fieldset>
				
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>

<!--Testing-->

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Material to Purchase Order </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">
							
							<div class="form-group">
                                <div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" name="categoryId" required onchange="getproduct(this.value)">
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
									<span id="getproduct" >
										<select class="form-control" id="itemName" name="itemName" >
											<option value="">Select</option>	
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
									<select class="form-control" name="company_id" id="company_id" onchange="getbudget(this.value)" >
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
								<span id="getbudget">
									<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
										<?php// $sql = "SELECT b.name, b.id, a.budget_name, a.id as bid FROM `sma_budget` a, sma_budget_name b where a.budget_name = b.id ";
										//$q2 	= mysqli_query($con, $sql);
									//	while($r2 = mysqli_fetch_array($q2)){ ?>
									<!--<option value="<?php echo $r2['bid'];?>" <?php echo ($row['budget_name'] == $r2['bid'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>-->
										<?php //} ?>
									</select>
								</span>	
								</div>
							
								<div class="col-sm-6">
									<label class="control-label">Budget Head</label>
									<span id="getbudgethead">
										<!--<select class="form-control" name="budget_head" id="budget_head" >
										<option value=""> Select </option-->
											<?php //$sql = "SELECT b.category as category_name, a.budget_category as category FROM `sma_budget` a, sma_budget_category b where a.budget_category = b.id ";
											//$q2 	= mysqli_query($con, $sql);
											//while($r2 = mysqli_fetch_array($q2)){ ?>
										<!--<option value="<?php echo $r2['category'];?>" <?php echo ($row['budget_head'] == $r2['category'])?'selected="selected"':'';?> >  <?php //echo $r2['category_name'];?></option>-->
											<?php //} ?>
										</select>
									</span>	
								</div>
								
							</div>
						</div> 
						 
							 <div class="form-group">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="number" class="form-control" id="itemQuantity" min="0" style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label">Units</label>
									<span id="getunit">
										<select class="form-control" id="itemUnits">
											<option value="">Select</option>
											<option value="Meters" <?php echo ($unit == 'Meters')?'selected="selected"':'';?> >Meters</option>
											<option value="Kgs" <?php echo ($unit == 'Kgs')?'selected="selected"':'';?> >Kgs</option>
											<option value="Liters" <?php echo ($unit == 'Liters')?'selected="selected"':'';?> >Liters</option>
											<option value="Nos" <?php echo ($unit == 'Nos')?'selected="selected"':'';?> >Nos</option>
											<option value="Grams" <?php echo ($unit == 'Grams')?'selected="selected"':'';?> >Grams</option>
											<option value="Inches" <?php echo ($unit == 'Inches')?'selected="selected"':'';?> >Inches</option>
										</select>
									</span>

                                </div>
                                <div class="col-sm-4">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="number" class="form-control" id="itemRate" placeholder="0.00" style="text-align:right;" min="0" onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>

							</div>

                            <div class="form-group">
                            
                                <div class="col-sm-4">
									<label for="itemGST" class="control-label">GST%</label>
                                    <input type="number" class="form-control" id="itemGST" min="0" style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>

                                <div class="col-sm-4">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                            
								<div class="col-sm-4">
									<label class="control-label">Delivery Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="deliveryDate" >
									</div>
								</div>
								
							</div>
							
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addItem">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Item-->




<!--Testing-->


<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
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
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>

    var itemArray = []; // stores all item details table values in memory

    $(document).ready(function () {
        $('.datepicker').datepicker();
        // $('.datepicker').datepicker({
        //     "format": 'd/M/Y',
        //     "autoclose": true
        // });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

    function validateInputs() {
        if ($("#reqDate").val() === '') {
            return false;
        }
        if (itemArray.length == 0) {
            $("#err").html("Please add items to the Purchase Order");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
    }

	
    function calculateTotalAmounte() {
		var qty1='';
		var rate1='';
		var amt1='';
		var gst1='';
		
//        var qty1 = $('#itemQuantity_e').val();
//        var rate1 = $('#itemRate_e').val();
//		var gst1 = $('#itemGST_e').val();
		var qty1 = document.getElementById("itemQuantity_e").value;
		var rate1 = document.getElementById("itemRate_e").value;
		var gst1 = document.getElementById("itemGST_e").value;
//alert(qty1 + ' <> ' + rate1 + ' <> ' +  amt1);
        var amt1 = qty1 * rate1;
		var amt1 = amt1 + (amt1 * gst /100);
        amt = parseFloat(amt1);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount_e').val(amt1);

    }

	
    function calculateTotalAmount() {
        var qty = $('#itemQuantity').val();
        var rate = $('#itemRate').val();
		var gst = $('#itemGST').val();
        var amt = qty * rate;
		var amt = amt + (amt * gst /100);
        amt = parseFloat(amt);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount').val(amt);
    }

    $('#modalDeleteItem').on('show.bs.modal', function(e) {
        var tempId = $(e.relatedTarget).data('id');
        var i;
        for (i = 0; i < itemArray.length; i++) {
            var obj = itemArray[i];
            if (obj.tempId == tempId) {
                $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                $(e.currentTarget).find('div[class="modal-body"]').html('Are you sure you want to delete item "' + obj.name + "'");
                break;
            }
        }
    });

    $("#btnDeleteItemYes").on("click", function(e){
        var i;
        for (i = 0; i < itemArray.length; i++) {
            //var obj = itemArray[i];
            if (itemArray[i].tempId == $("#itemTempId").val()) {
                itemArray.splice(i, 1);
                break;
            }
        }
        buildItemsTable();
        $('#modalDeleteItem').modal('hide');
    });

    function setSelectedValue(object, value) {
        for (var i = 0; i < object.options.length; i++) {
            if (object.options[i].text === value) {
                object.options[i].selected = true;
                return;
            }
        }

        // Throw exception if option `value` not found.
        var tag = object.nodeName;
        var str = "Option '" + value + "' not found";

        if (object.id != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@id='" + object.id + "']."
        }

        else if (object.name != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@name='" + object.name + "']."
        }

        else {
            str += "."
        }

        throw str;
    }

    $('#modalAddItem').on('show.bs.modal', function(e) {
        var mode = $(e.relatedTarget).data('mode');
        $(e.currentTarget).find('input[id="mode"]').val(mode);
        if (mode === 'add') {
            // clear existing values
            $("#itemDescription").val("");
            $("#itemQuantity").val(0);
            $("#itemUnits").val("");
            $("#itemRate").val(0);
			$("#itemGST").val(0);
            $("#itemAmount").val(0);
			$("#deliveryDate").val(0);
        }
        else {
            var tempId = $(e.relatedTarget).data('id');
            $(e.currentTarget).find('input[id="tempId"]').val(tempId);
            var i;
            for (i = 0; i < itemArray.length; i++) {
                var obj = itemArray[i];
                if (obj.tempId == tempId) {
                    $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                    //$(e.currentTarget).find('select[id="itemName"]').val(obj.id);
                    setSelectedValue($(e.currentTarget).find('select[id="itemName"]')[0], obj.name);
					setSelectedValue($(e.currentTarget).find('select[id="categoryId"]')[0], obj.name);
					$(e.currentTarget).find('input[id="itemDescription"]').val(obj.description);
                    $(e.currentTarget).find('input[id="itemQuantity"]').val(obj.quantity);
                    $(e.currentTarget).find('input[id="itemUnits"]').val(obj.units);
                    $(e.currentTarget).find('input[id="itemRate"]').val(obj.rate);
					$(e.currentTarget).find('input[id="itemGST"]').val(obj.gst);
                    $(e.currentTarget).find('input[id="itemAmount"]').val(obj.amount);
					$(e.currentTarget).find('input[id="deliveryDate"]').val(obj.deliverydate);
                    break;
                }
            }
        }
    });

    $("#addItem").on("click", function(e){
        var sub = 'sub1';
	//	var mode = $("#mode").val();
	
		var purchase_id =  $("#purchaseId").val();		
        var id =            $("#itemName option:selected").val();
        var name =          $("#itemName option:selected").html();
		var catid =         $("#categoryId option:selected").val();

        var account_year =  $("#account_year option:selected").val();
        var company_id   =  $("#company_id option:selected").val();
		var budget_name  =  $("#budget_name option:selected").val();
		var budget_head  =  $("#budget_head option:selected").val();
		
        var catname =       $("#categoryId option:selected").html();
		var description =   $("#itemDescription").val();
//alert(catid + ' ' + catname+' '+description);
        var quantity =      $("#itemQuantity").val();
        var units =         $("#itemUnits").val();
        var rate =          $("#itemRate").val();
		var gst  =          $("#itemGST").val();
        var amount =        $("#itemAmount").val();
		var deliverydate =  $("#deliveryDate").val();
//alert(deliverydate);
        $('#modalAddItem').modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ id:id,purchase_id:purchase_id,
							name:name,
							catid:catid,
							description:description,
							account_year:account_year,
							company_id:company_id,
							budget_name:budget_name,
							budget_head:budget_head,
							quantity:quantity,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							deliverydate:deliverydate,
							sub1:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });

	
    $("#editItem").on("click", function(e){
//    function(editItem){    
		var sub = 'sub3';

		var rid 		=  $("#rid_e").val();
		var purchase_id =  $("#purchaseId_e").val();		
        var id =            $("#itemName_e option:selected").val();
        var name =          $("#itemName_e option:selected").html();
		var catid =         $("#categoryId_e option:selected").val();
        var catname =       $("#categoryId_e option:selected").html();
        var description =   $("#itemDescription_e").val();
        var quantity =      $("#itemQuantity_e").val();
        var units =         $("#itemUnits_e").val();
        var rate =          $("#itemRate_e").val();
		var gst  =          $("#itemGST_e").val();
        var amount =        $("#itemAmount_e").val();
		var deliverydate =  $("#deliveryDate_e").val();
        $('#modalEditItem'+rid).modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ rid:rid,id:id,purchase_id:purchase_id,
							name:name,
							catname:catname,
							catid:catid,
							description:description,
							quantity:quantity,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							deliverydate:deliverydate,
							sub3:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });

	function delete_poItem(po_id, id){
		var sub = 'sub2';
        var poid = po_id;
		var id	 = id;

		$('#modalDeleteItem'+poid+id).modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ poid:poid,id:id,sub2:sub},
							function(result){
		      $('#prItemsTableBody123').html(result);
			});
			
		window.location.href='purchase_order_entry.php?sub=edit&id='+poid+'&active=active';	
	
	}

    function saveItem123(mode) {    
        if (mode === 'add') {
            var objItem = new Object();
            objItem.tempId = 621355968000000000 + (new Date().getTime() * 10000);
            objItem.id =            $("#itemName option:selected").val();
            objItem.name =          $("#itemName option:selected").html();
            objItem.description =   $("#itemDescription").val();
            objItem.quantity =      $("#itemQuantity").val();
            objItem.units =         $("#itemUnits").val();
            objItem.rate =          $("#itemRate").val();
			objItem.gst =          $("#itemGST").val();
            objItem.amount =        $("#itemAmount").val();
			objItem.deliverydate =  $("#deliveryDate").val();

            itemArray.push(objItem);
        }
        else {
            for (var i = 0; i < itemArray.length; i++) {
                if (itemArray[i].tempId == $("#tempId").val()) {
                    itemArray[i].id =            $("#itemName option:selected").val();
                    itemArray[i].name =          $("#itemName option:selected").html();
                    itemArray[i].description =   $("#itemDescription").val();
                    itemArray[i].quantity =      $("#itemQuantity").val();
                    itemArray[i].units =         $("#itemUnits").val();
                    itemArray[i].rate =          $("#itemRate").val();
					itemArray[i].gst =          $("#itemGST").val();
                    itemArray[i].amount =        $("#itemAmount").val();
                    itemArray[i].deliverydate =        $("#deliveryDate").val();
					break;
                }
            }
        }
        $("#items").val(itemArray);
        //console.log($("#items").val());

        // clear existing values
        $("#itemDescription").val("");
        $("#itemQuantity").val(0);
        $("#itemUnits").val("");
        $("#itemRate").val(0);
		$("#itemGST").val(0);
        $("#itemAmount").val(0);
		$("#deliveryDate").val(0);

        buildItemsTable();
    }

    function buildItemsTable() {
        $("#prItemsTableBody123").empty();
        var tableRef = document.getElementById('prItemsTable').getElementsByTagName('tbody')[0]

        if (itemArray.length == 0) {
            var newRow = tableRef.insertRow(tableRef.rows.length);
            // action buttons
            var newCell = newRow.insertCell(0);
            var no_rows_content = document.createElement("SPAN");
            no_rows_content.innerHTML = "No data available in table";
            newCell.class = "dataTables_empty";
            newCell.colSpan = 8;
            newCell.vAlign = "top";
            newCell.appendChild(no_rows_content);
        }
        var i, j;    
        for (i = 0; i < itemArray.length; i++) {
            var obj = itemArray[i];
            var newRow = tableRef.insertRow(tableRef.rows.length);
            // action buttons
            var newCell = newRow.insertCell(0);
            var actionBtns = document.createElement("SPAN");
            actionBtns.innerHTML = "<a href='#modalAddItem' data-id='" + obj.tempId + "' data-mode='edit' data-toggle='modal' data-target='#modalAddItem'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;<a href='#modalDeleteItem' id='delete-" + obj.tempId +  "' data-toggle='modal' data-id='" + obj.tempId +  "' data-target='#modalDeleteItem'><i class='fa fa-trash-alt'></i></a>";
            newCell.style.width = "148px";
            newCell.appendChild(actionBtns);

            // item name
            newCell = newRow.insertCell(1);
            var newText = document.createTextNode(obj.name);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(2);
            newText = document.createTextNode(obj.description);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(3);
            newText = document.createTextNode(obj.quantity);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(4);
            newText = document.createTextNode(obj.units);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(5);
            newText = document.createTextNode(obj.rate);
            newCell.appendChild(newText);

            // item GST
            newCell = newRow.insertCell(6);
            newText = document.createTextNode(obj.gst);
            newCell.appendChild(newText);
			
            // item name
            newCell = newRow.insertCell(7);
            newText = document.createTextNode(obj.amount);
            newCell.appendChild(newText);
			
            // item delivery date
            newCell = newRow.insertCell(8);
            newText = document.createTextNode(obj.deliverydate);
            newCell.appendChild(newText);
        }
    }
</script>	

</script>

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

	function getproduct(id){
		var sub    = 'sub3';
		var strURL = "app_func.php";
//alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getproduct').html(result);
		});

	}
	
	
	function getbudget(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getbudget').html(result);
		});

	}

	function getbudgethead(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudgethead').html(result);
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

  function validateInputs() {
        if ($("#reqDate").val() === '') {
            return false;
        }
        if (itemArray.length == 0) {
            $("#err").html("Please add items to the Purchase Requisitions");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
    }
	
</script>

</body>
</html>
