<!DOCTYPE html>
<?php
	include("../header.php");
	$modulePath = "purchase_order/";  
	$module_name = "department.php?sub=list";

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Purchase Order
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Purchase Order</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Order List</h3>
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="purchase_order.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
				</span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Approval Memo Ref.</th>
			<th>Dated</th>
			<th>Project</th>
			<th>Department</th>
			<th>Budget Name</th>
			<th>Budget Head</th>
			<th>Against Indent No.</th>
			<th>Quote Ref.No.</th>
			<th>Supplier</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_purchase_order";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$department = $row['department'];
		$sql 	= "select * from sma_department where id = '$department' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$department = $r2['name'];
		
		$project = $row['project'];
		$sql 	= "select * from sma_project where id = '$project' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$project = $r2['name'];		
		
		
		$budget_name = $row['budget_name'];
		$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
		
		
		$budget_head = $row['budget_head'];
		$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_head = $r2['category'];
		
	
?>

    <a href="purchase_order.php?sub=edit&id=<?php echo $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; "onclick="location.href='purchase_order.php?sub=edit&id=<?php echo $row["id"];?>'">

		<td width="10%"><?php echo $row['approval_memo_ref'];?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="20%"><?php echo $project;?></td>
		<td width="10%"><?php echo $department;?></td>
		<td width="10%"><?php echo $budget_name;?></td>
		<td width="10%"><?php echo $budget_head;?></td>
		<td width="10%"><?php echo $row['against_indent_no'];?></td>
		<td width="10%"><?php echo $row['quotation_reference_no'];?></td>
		<td width="10%"><?php echo $row['to_supplier'];?></td>
	
		<td width="10%" style="text-align:right;">
		<a href="purchase_order.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="purchase_order.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
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
		$sql="delete from sma_purchase_order where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="purchase_order.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){
			$srno				= $_POST['srno'];
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$department			= $_POST['department'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$against_indent_no	= $_POST['against_indent_no'];
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			$status				= $_POST['status'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];

/*			$item_name			= $_POST['item_name'];
			$description_product_category	= $_POST['description_product_category'];
			$qty				= $_POST['qty'];
			$unit				= $_POST['unit'];
			$rate				= $_POST['rate'];
			$cgst				= $_POST['cgst'];
			$sgst				= $_POST['sgst'];
			$igst				= $_POST['igst'];
			$amount				= $_POST['amount'];
*/			
//			$attachment_files	= $_POST['attachment_files'];

  			$sql="insert into sma_purchase_order (id, approval_memo_ref,dated, project, department, budget_name, budget_head, against_indent_no, quotation_reference_no, to_supplier, delivery_days, credit_days, payment_terms, status, delivery_date, terms) values('$srno', '$approval_memo_ref', '$dated', '$project', '$department', '$budget_name', '$budget_head', '$against_indent_no', '$quotation_reference_no', '$to_supplier', '$delivery_days', '$credit_days', '$payment_terms', '$status', '$delivery_date', '$terms')";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Purchase Order successful added";
			echo '<script>window.location.href="purchase_order.php?sub=list";</script>';
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
            <form class="form-horizontal" action="purchase_order.php?sub=add" method="post">
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
							<div class="col-md-2">
								<label class="control-label">Sr.Number</label>
								<input type="text" class="form-control" id="srno" name="srno" style="text-align:right;" placeholder="" value="<?php echo $srno;?>" >
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
								<label class="control-label">Approval Memo Ref</label>
								<input type="text" class="form-control" id="approval_memo_ref" name="approval_memo_ref" placeholder="" value="<?php echo $row['approval_memo_ref'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Project</label>
								<select class="form-control" name="project" id="project" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_project order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['project'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						 
							<div class="col-md-4">
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
						
						</div>
						
						<div class="form-group">
							
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
							
							<div class="col-md-3">
								<label class="control-label">Against PR No</label>
								<input type="text" class="form-control" id="against_indent_no" name="against_indent_no" placeholder="" value="<?php echo $row['against_indent_no'];?>" >
							</div>
						 
							<div class="col-md-3">
								<label class="control-label">Quotation Reference No</label>
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" placeholder="" value="<?php echo $row['quotation_reference_no'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-3">
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
								<input type="text" class="form-control" id="delivery_days" name="delivery_days" placeholder="" value="<?php echo $row['delivery_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" placeholder="" value="<?php echo $row['credit_days'];?>" >
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
						
							<div class="col-md-12">
								<label class="control-label">Terms</label>
								<textarea rows="2" class="form-control" id="terms" name="terms" placeholder="" value="<?php echo $row['terms'];?>" ></textarea>
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label">Item Name</label>
								<input type="text" class="form-control" id="item_name" name="item_name" placeholder="" value="<?php echo $row['item_name'];?>" >
							</div>
						
							<div class="col-md-1">
								<label class="control-label">Quantity</label>
								<input type="text" class="form-control" id="qty" name="qty" placeholder="" value="<?php echo $row['qty'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Unit</label>
								<input type="text" class="form-control" id="unit" name="unit" placeholder="" value="<?php echo $row['unit'];?>" >
							</div>
							
							<div class="col-md-1">
								<label class="control-label">Rate</label>
								<input type="text" class="form-control" id="rate" name="rate" placeholder="" value="<?php echo $row['rate'];?>" >
							</div>
							
							<div class="col-md-1">
								<label class="control-label">GST</label>
								<input type="text" class="form-control" id="igst" name="igst" placeholder="" value="<?php echo $row['igst'];?>" >
							</div>

							<div class="col-md-2">
								<label class="control-label">Amount</label>
								<input type="text" class="form-control" id="amount" name="amount" placeholder="" value="<?php echo $row['amount'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Delivery Date</label>
								<input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="" value="<?php echo date('d-m-Y', strtotime($row['delivery_date']));?>" >
							</div>
							
						</div>


							
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="purchase_order.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
			
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$department			= $_POST['department'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$against_indent_no	= $_POST['against_indent_no'];
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			$status				= $_POST['status'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			
  			$sql="update sma_purchase_order set approval_memo_ref	= '$approval_memo_ref',
						dated				= '$dated',
						project				= '$project',
						department			= '$department',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						against_indent_no	= '$against_indent_no',
						quotation_reference_no	= '$quotation_reference_no',
						to_supplier			= '$to_supplier',
						delivery_days		= '$delivery_days',
						credit_days			= '$credit_days',
						payment_terms		= '$payment_terms',
						status				= '$status',
						delivery_date		= '$delivery_date',
						terms				= '$terms'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="purchase_order.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <!-- Main content -->
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Order Edit</h3>
			  <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="purchase_order.php?sub=list&page=0" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Back</a>
				</span>
			</div>
			</div>
		</div>	
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="purchase_order.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Sr.Number</label>
								<input type="text" class="form-control" id="srno" name="srno" style="text-align:right;" readonly value="<?php echo $row['id'];?>" >
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
								<label class="control-label">Approval Memo Ref</label>
								<input type="text" class="form-control" id="approval_memo_ref" name="approval_memo_ref" placeholder="" value="<?php echo $row['approval_memo_ref'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Project</label>
								<select class="form-control" name="project" id="project" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_project order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['project'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						 
							<div class="col-md-4">
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
						
						</div>
						
						<div class="form-group">
							
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
							
							<div class="col-md-3">
								<label class="control-label">Against PR No</label>
								<input type="text" class="form-control" id="against_indent_no" name="against_indent_no" placeholder="" value="<?php echo $row['against_indent_no'];?>" >
							</div>
						 
							<div class="col-md-3">
								<label class="control-label">Quotation Reference No</label>
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" placeholder="" value="<?php echo $row['quotation_reference_no'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-3">
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
								<input type="text" class="form-control" id="delivery_days" name="delivery_days" placeholder="" value="<?php echo $row['delivery_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" placeholder="" value="<?php echo $row['credit_days'];?>" >
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
						
						<div class="form-group">
						
							<div class="col-md-12">
								<label class="control-label">Terms</label>
								<textarea rows="2" class="form-control" id="terms" name="terms" placeholder="" value="<?php echo $row['terms'];?>" ></textarea>
							</div>
							
						</div>


							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Item Details</h4>
                                        <span class="pull-right">
                                            <a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem">Add Item
                                            </a>
                                        </span>
                                    </div>
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                 <th>Item</th>
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
													$rate 	= $row['rate'];
													$gst	= $row['gst'];
													$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
													
													$rid = $row['id'];
												?>	
													<tr>
														<td width='15%'><?php echo $rid.' '.$row['product_name']?></td>
														<td width='15%'><?php echo $row['product_desc']?></td>	
														<td width='10%'><?php echo $row['quantity']?></td>	
														<td width='10%'><?php echo $row['uom']?></td>	
														<td width='10%'><?php echo $row['unit_rate']?></td>	
														<td width='10%'><?php echo $row['gst']?></td>
														<td width='10%'><?php echo $amount?></td>					 
														<td width='10%'><?php echo date('d-m-Y', strtotime($row['delivery_date']))?></td>
														<td>
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
														
<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Item to Purchase Order</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
					//echo $srno;
							
							$sql  = "SELECT * from sma_po_items where id = '$srno' ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							$unit	= $r1['uom'];
							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$product_desc	= $r1['product_desc'];
							$product_id	= $r1['product_id'];
						?>
                        <form class="form-horizontal" action="poitem_update.php" target="_blank" method="POST">
							<input type="hidden" id="rid" name="rid" value="<?php echo $srno;?>">
							<input type="text" id="rid_e123<?php echo $rid;?>" value="rid_e<?php echo $srno;?>">
                            <input type="hidden" id="purchaseId_e<?php echo $rid;?>" name="purchase_id" value="<?php echo $_GET['id'];?>">
							
                            <div class="form-group col-md-12">
                                <label for="itemName" class="col-sm-3 control-label">Item Name</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="itemName" id="itemname_e<?php echo $rid;?>">
                                    <?php
                                    	$sql="SELECT id, name FROM sma_product ORDER BY name ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $row['id']?>" <?php echo ($product_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
							<div class="form-group col-md-12">
                                <label for="itemDescription" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="itemDescription_e<?php echo $rid;?>" name="itemdescription" placeholder="Item Description..." value="<?php echo $product_desc;?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">Qty.</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="itemQuantity_e<?php echo $rid;?>" name="itemquantity" min="0" value="<?php echo $qty;?>" onkeyup="calculateTotalAmount();">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemUnits" class="col-sm-3 control-label">Units</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="itemUnits_e<?php echo $rid;?>" name="itemunits" value="<?php echo $unit;?>">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-3 control-label">Rate</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="number" class="form-control" id="itemRate_e<?php echo $rid;?>" name="itemrate" placeholder="0.00" value="<?php echo $rate;?>" min="0" onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">GST%</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="itemGST_e<?php echo $rid;?>" name="itemgst" min="0" value="<?php echo $gst;?>" onkeyup="calculateTotalAmount();">
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemAmount" class="col-sm-3 control-label">Total</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="itemAmount_e<?php echo $rid;?>" name="itemamount" readonly value="<?php echo $amount;?>">
                                </div>
                            </div>
							
							<div class="form-group col-md-12">
								<label class="col-sm-3 control-label">Delivery Date</label>
								<div class="col-sm-4">
						            <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="deliveryDate_e<?php echo $rid;?>" name="deliverydate" value="<?php echo $delivery_date;?>" >
									</div>
								</div>	
							</div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="editItem123" >Save changes</button>
            </div>
			
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Item-->
			
														<a href='#modalDeleteItem' id='delete-<?php echo $_GET['id'];?><?php echo $rid;?>' data-toggle='modal' data-id='<?php echo $_GET['id'];?><?php echo $rid;?>' data-target='#modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>'><i class='fa fa-trash-alt'></i></a></td>
<!-- Modal Delete Item-->
<div class="modal fade" id="modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>" role="dialog" aria-labelledby="modalDeleteItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteItemLabel">Delete Item of Purchase Requisition - NEW</h4>
            </div>
            <input type="hidden" id="itemTempId">
            <div class="modal-body" id="modalDeleteContent">
                Are you sure you want to delete item <?php echo $_GET['id'];?> <?php echo $rid;?> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnDeleteItemYes" onclick="delete_poItem(<?php echo $_GET['id'];?>, <?php echo $rid; ?>)" >Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Item-->
														
													</tr>
											<?php
												}
											?>		

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit</th>
												<th>Rate</th>
                                                <th>GST%</th>
                                                <th>Amount</th>
												<th>Delivery Date</th>
												<th>Action</th>
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
								<a href="purchase_order.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
      
<?php } 	?>

<!--Testing-->



<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Item to Purchase Requisition 123</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">
							
                            <div class="form-group col-md-12">
                                <label for="itemName" class="col-sm-3 control-label">Item Name</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="itemName">
                                    <?php
                                    	$sql="SELECT id, name FROM sma_product ORDER BY name ASC";
                                        $result = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($row = mysqli_fetch_array($result)){
                                    ?>
                                        <option value="<?php echo $row['id']?>"><?php echo $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
							<div class="form-group col-md-12">
                                <label for="itemDescription" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">Qty.</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="itemQuantity" min="0" onkeyup="calculateTotalAmount();">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemUnits" class="col-sm-3 control-label">Units</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="itemUnits">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-3 control-label">Rate</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="number" class="form-control" id="itemRate" placeholder="0.00" min="0" onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">GST%</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="itemGST" min="0" onkeyup="calculateTotalAmount();">
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemAmount" class="col-sm-3 control-label">Total</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="itemAmount" readonly>
                                </div>
                            </div>
							
							<div class="form-group col-md-12">
								<label class="col-sm-3 control-label">Delivery Date</label>
								<div class="col-sm-4">
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
            $("#err").html("Please add items to the Purchase Requisitions");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
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
        var description =   $("#itemDescription").val();
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
							description:description,
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
alert(sub);		
		var rid 		=  $("#rid_e").val();
		var purchase_id =  $("#purchaseId_e").val();		
        var id =            $("#itemName_e option:selected").val();
        var name =          $("#itemName_e option:selected").html();
        var description =   $("#itemDescription_e").val();
        var quantity =      $("#itemQuantity_e").val();
        var units =         $("#itemUnits_e").val();
        var rate =          $("#itemRate_e").val();
		var gst  =          $("#itemGST_e").val();
        var amount =        $("#itemAmount_e").val();
		var deliverydate =  $("#deliveryDate_e").val();
alert(deliverydate);
        $('#modalEditItem'+rid).modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ rid:rid,id:id,purchase_id:purchase_id,
							name:name,
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
		      $('#prItemsTableBody').html(result);
			});
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

</script>

</body>
</html>
