<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "ipc/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        IPC
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">IPC</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of IPC</h3>
                <span class="pull-right"><a href="ipc.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create IPC </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>Company</th>
			<th>Party</th>
			<th>PO.Number</th>
			<th>Invoice No.</th>
			<th>PO Amount</th>
			<th>Invoice Amount</th>
			<th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
			
		</tr>
	</thead>
<tbody>
<?php

	$sql="SELECT * from sma_ipc order by id desc";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
//echo $sql;
	
	while($row = mysqli_fetch_array($result)){
		
		$sma_vendor_id = $row['sma_vendor_id'];
		$sql = "select * from sma_party_mst where id = '$sma_vendor_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$sma_vendor_name = $r2['party_name'];
		
		$sma_comp_id = $row['sma_comp_id'];
		$sql = "select * from company where comp_id = '$sma_comp_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_name = $r2['comp_name'];
		
		$sma_po_no = $row['sma_po_no'];
		$sql = "select * from sma_purchase_order where id = '$sma_po_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_number = $r2['po_number'];
		
		$sma_invoice_no = $row['sma_invoice_no'];
		$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$sma_invoice_no = $r2['supplier_invoice_no'];
		
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$row["id"];
?>

	<a href="<?php echo $baseurl . $modulePath . "ipc.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="20%"><?php echo $comp_name;?></td>
		<td width="20%"><?php echo $sma_vendor_name;?></td>
		<td width="10%"><?php echo $po_number;?></td>
		<td width="10%"><?php echo $sma_invoice_no;?></td>
		<td width="10%"><?php echo $row['sma_po_amount'];?></td>
		<td width="10%"><?php echo $row['sma_invoice_amount'];?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>

<!--		<td width="10%" style="text-align:right;">
		<a href="ipc.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="ipc.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		</td>-->
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
		$sql="delete from sma_ipc where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="ipc.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
 
			$sma_comp_id		= $_POST['sma_comp_id'];
			$sma_vendor_id		= $_POST['sma_vendor_id'];
			$sma_po_no			= $_POST['sma_po_no'];
			$sma_inv_adv		= $_POST['sma_inv_adv'];
			$sma_invoice_no		= $_POST['sma_invoice_no'];
			$sma_po_amount		= $_POST['sma_po_amount'];
			$sma_invoice_amount			= $_POST['sma_invoice_amount'];
			$sma_variation_order_amt	= $_POST['sma_variation_order_amt'];
			$sma_variation_in_price		= $_POST['sma_variation_in_price'];
			$sma_material_advance		= $_POST['sma_material_advance'];
			$sma_material_advance_recovery	= $_POST['sma_material_advance_recovery'];
			$sma_deduct_retention_money		= $_POST['sma_deduct_retention_money'];
			$sma_release_retention_money	= $_POST['sma_release_retention_money'];
			$sma_variation_due_to_arbitratioon		= $_POST['sma_variation_due_to_arbitratioon'];
			$sma_deduction_work_contract_tax		= $_POST['sma_deduction_work_contract_tax'];
			$sma_deduction_liquidated_damage		= $_POST['sma_deduction_liquidated_damage'];
			$sma_recovered_amount			= $_POST['sma_recovered_amount'];
			$sma_debit_note_amount			= $_POST['sma_debit_note_amount'];
			$sma_deduction_royalty			= $_POST['sma_deduction_royalty'];
			$sma_other_deduction			= $_POST['sma_other_deduction'];
			$sma_other_deduction_desc		= $_POST['sma_other_deduction_desc'];

			$status 			= 'Draft';

			$user   = $_SESSION['user'];

  			$sql="insert into sma_ipc (sma_comp_id, sma_vendor_id, sma_po_no, sma_inv_adv, sma_invoice_no, sma_po_amount, sma_invoice_amount, sma_variation_order_amt, sma_variation_in_price, sma_material_advance, sma_material_advance_recovery, sma_deduct_retention_money, sma_release_retention_money, sma_variation_due_to_arbitratioon, sma_deduction_work_contract_tax, sma_deduction_liquidated_damage, sma_recovered_amount, sma_debit_note_amount, sma_deduction_royalty, sma_other_deduction, sma_other_deduction_desc , status, draft_by, draft_dated ) 
			Values( '$sma_comp_id', '$sma_vendor_id', '$sma_po_no', '$sma_inv_adv', '$sma_invoice_no', '$sma_po_amount', '$sma_invoice_amount', '$sma_variation_order_amt', '$sma_variation_in_price', '$sma_material_advance', '$sma_material_advance_recovery', '$sma_deduct_retention_money', '$sma_release_retention_money', '$sma_variation_due_to_arbitratioon', '$sma_deduction_work_contract_tax', '$sma_deduction_liquidated_damage', '$sma_recovered_amount', '$sma_debit_note_amount', '$sma_deduction_royalty', '$sma_other_deduction', '$sma_other_deduction_desc', '$status', '$user', now() )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "IPC successful added";
			echo '<script>window.location.href="ipc.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            IPC
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath."ipc.php?sub=list" ?>">IPC</a></li>
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
            <form class="form-horizontal" action="ipc.php?sub=add" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Company</label>
							<div class="col-md-3">
								<select class="form-control" name="sma_comp_id" id="sma_comp_id" autocomplete="off" required >
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['sma_comp_id'] == $r2['comp_id'])?'selected="selected"':'';?> > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-2 control-label">Vendor Name</label>
							<div class="col-md-3">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" onchange="getpono(this.value); getsuppno(this.value) " >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_vendor_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Number</label>
							<div class="col-md-3">
								<span id="getpono">
								<select class="form-control" name="sma_po_no" id="sma_po_no" autocomplete="off" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_purchase_order order by po_number ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_po_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['po_number'];?></option>
										<?php } ?>
								</select>
							    </span>
							</div>
						
							<label class="col-lg-2 control-label">PO Amount</label>
							<div class="col-md-2">
								<span id="getpoamt">
									<input type="text" class="form-control" id="sma_po_amount" name="sma_po_amount" style="text-align:right;" autocomplete="off" value="">
								</span>	
							</div>
						
							
						</div>
						
						
						<div class="form-group">
						
						<label class="col-lg-2 control-label">Against</label>
							<div class="col-md-2">
								<input type="radio"  id="sma_inv_adv" name="sma_inv_adv" autocomplete="off" value="I"> Invoice
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv" autocomplete="off" value="A"> Advance
							</div>
							
							<label class="col-lg-2 control-label">Invoice Number</label>
							<div class="col-md-2">
								<span id="getsuppno">
								<select class="form-control" name="sma_invoice_no" id="sma_invoice_no" autocomplete="off" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_supplier_invoice order by supplier_invoice_no ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_invoice_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['supplier_invoice_no'];?></option>
										<?php } ?>
								</select>
								</span>	
							</div>
						
							<label class="col-lg-2 control-label">Invoice Amount</label>
							<div class="col-md-2">
							<span id="getsuppamt">
								<input type="text" class="form-control" id="sma_invoice_amount" name="sma_invoice_amount" style="text-align:right;" autocomplete="off" value="">
							</span>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation Order Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_order_amt" name="sma_variation_order_amt" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Variation in Price(VOP)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_in_price" name="sma_variation_in_price" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Advance </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance" name="sma_material_advance" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Material Advance Recovery</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance_recovery" name="sma_material_advance_recovery" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduct Retention Money</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduct_retention_money" name="sma_deduct_retention_money" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Release of Retention Money.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_release_retention_money" name="sma_release_retention_money" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation due to Arbitration/ Claims or disputes</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_due_to_arbitratioon" name="sma_variation_due_to_arbitratioon" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Deduction Work contract Tax</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_work_contract_tax" name="sma_deduction_work_contract_tax" style="text-align:right;" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduction Liquidated Damage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_liquidated_damage" name="sma_deduction_liquidated_damage" style="text-align:right;" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-3 control-label">Recovered Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_recovered_amount" name="sma_recovered_amount" autocomplete="off" style="text-align:right;" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Debit note amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_debit_note_amount" name="sma_debit_note_amount" autocomplete="off" style="text-align:right;" value="">
							</div>
							
							<label class="col-lg-3 control-label">Deduction Royalty / Seinorage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_royalty" name="sma_deduction_royalty" autocomplete="off" style="text-align:right;" value="">
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Other Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_other_deduction" name="sma_other_deduction" autocomplete="off" style="text-align:right;" value="">
							</div>
						
							<label class="col-lg-3 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="sma_other_deduction_desc" name="sma_other_deduction_desc" autocomplete="off"  value="">
							</div>
						</div>
						 
						
						
						<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="ipc.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
</section>  

<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$ipc_id				= $_POST['id']; 
			$sma_comp_id		= $_POST['sma_comp_id'];
			$sma_vendor_id		= $_POST['sma_vendor_id'];
			$sma_po_no			= $_POST['sma_po_no'];
			$sma_inv_adv		= $_POST['sma_inv_adv'];
			$sma_invoice_no		= $_POST['sma_invoice_no'];
			$sma_po_amount		= $_POST['sma_po_amount'];
			$sma_invoice_amount			= $_POST['sma_invoice_amount'];
			$sma_variation_order_amt	= $_POST['sma_variation_order_amt'];
			$sma_variation_in_price		= $_POST['sma_variation_in_price'];
			$sma_material_advance		= $_POST['sma_material_advance'];
			$sma_material_advance_recovery	= $_POST['sma_material_advance_recovery'];
			$sma_deduct_retention_money		= $_POST['sma_deduct_retention_money'];
			$sma_release_retention_money	= $_POST['sma_release_retention_money'];
			$sma_variation_due_to_arbitratioon		= $_POST['sma_variation_due_to_arbitratioon'];
			$sma_deduction_work_contract_tax		= $_POST['sma_deduction_work_contract_tax'];
			$sma_deduction_liquidated_damage		= $_POST['sma_deduction_liquidated_damage'];
			$sma_recovered_amount			= $_POST['sma_recovered_amount'];
			$sma_debit_note_amount			= $_POST['sma_debit_note_amount'];
			$sma_deduction_royalty			= $_POST['sma_deduction_royalty'];
			$sma_other_deduction			= $_POST['sma_other_deduction'];
			$sma_other_deduction_desc		= $_POST['sma_other_deduction_desc'];

  			$sql="update sma_ipc set 	sma_vendor_id = '$sma_vendor_id', 
						sma_comp_id 		='$sma_comp_id',						
						sma_po_no			= '$sma_po_no',
						sma_inv_adv			= '$sma_inv_adv',
						sma_invoice_no		= '$sma_invoice_no',
						sma_po_amount		= '$sma_po_amount',
						sma_invoice_amount			= '$sma_invoice_amount',
						sma_variation_order_amt		= '$sma_variation_order_amt',
						sma_variation_in_price		= '$sma_variation_in_price',
						sma_material_advance		= '$sma_material_advance',
						sma_material_advance_recovery	= '$sma_material_advance_recovery',
						sma_deduct_retention_money		= '$sma_deduct_retention_money',
						sma_release_retention_money	= '$sma_release_retention_money',
						sma_variation_due_to_arbitratioon	= '$sma_variation_due_to_arbitratioon',
						sma_deduction_work_contract_tax		= '$sma_deduction_work_contract_tax',
						sma_deduction_liquidated_damage		= '$sma_deduction_liquidated_damage',
						sma_recovered_amount			= '$sma_recovered_amount',
						sma_debit_note_amount			= '$sma_debit_note_amount',
						sma_deduction_royalty			= '$sma_deduction_royalty',
						sma_other_deduction				= '$sma_other_deduction',
						sma_other_deduction_desc		= '$sma_other_deduction_desc'
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
				$folder_path = "uploads/ip/" . $ipc_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('IP', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $ipc_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/ip/" . $ipc_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

	//exit();
	
			echo '<script>window.location.href="ipc.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_ipc where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$status = $row['status'];
		$ipc_id 	= $id;
								
		$readonly = '';
		if ($status == 'Submited'){
			$readonly = 'READONLY';
		}

?>

    <section class="content-header">
        <h1>
            IPC
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">IPC</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="ipc.php?sub=edit" method="post" enctype="multipart/form-data">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
						
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
							
							<?php 
								
								$_SESSION['sma_comp_id'] = $row['sma_comp_id'];
								$_SESSION['ipc_id'] 	= $id;
								$_SESSION['status']  = $status;

							?>
						<?php
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						?>
						
					<ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >IPC</a></li>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
						<li><a href="ipc_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['sma_comp_id'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Company</label>
							<div class="col-md-3">
								<select class="form-control" name="sma_comp_id" id="sma_companY" autocomplete="off" <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['sma_comp_id'] == $r2['comp_id'])?'selected="selected"':'';?> > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>

							<label class="col-lg-2 control-label">Vendor Name</label>
							<div class="col-md-3">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" onchange="getpono(this.value); getsuppno(this.value) " <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_vendor_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Number</label>
							<div class="col-md-2">
							<span id="getpono">
								<select class="form-control" name="sma_po_no" id="sma_po_no" autocomplete="off" <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_purchase_order  order by po_number "; //where status = 'Completed'
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_po_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['po_number'];?></option>
										<?php } ?>
								</select>
							</span>
							</div>
						
							<label class="col-lg-2 control-label">PO Amount</label>
							<div class="col-md-2">
							<span id="getpoamt">
								<input type="text" class="form-control" id="sma_po_amount" name="sma_po_amount" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_po_amount'];?>">
							</span>	
							</div>
						
							
						</div>
						<?php
							$inv_checked ='';
							$adv_checked ='';
							$sma_inv_adv = $row['sma_inv_adv'];
							if($sma_inv_adv=='I'){
								$inv_checked = "CHECKED";
							}
							else if($sma_inv_adv=='A'){
								$adv_checked = "CHECKED";
							}
						?>
						
						<div class="form-group">
						
						<label class="col-lg-2 control-label">Against</label>
							<div class="col-md-2">
								<input type="radio"  id="sma_inv_adv" name="sma_inv_adv"  <?php echo $inv_checked;?> value="I"> Invoice
								<input type="radio" id="sma_inv_adv" name="sma_inv_adv" <?php echo $adv_checked;?> value="A"> Advance
							</div>
							
							
							<label class="col-lg-2 control-label">Invoice Number</label>
							<div class="col-md-2">
							<span id="getsuppno">
								<select class="form-control" name="sma_invoice_no" id="sma_invoice_no" autocomplete="off" <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_supplier_invoice  order by supplier_invoice_no " ;//where status = 'Completed'
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['sma_invoice_no'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['supplier_invoice_no'];?></option>
										<?php } ?>
								</select>
							</span>	
							</div>
						
							<label class="col-lg-2 control-label">Invoice Amount</label>
							<div class="col-md-2">
							<span id="getsuppamt">
								<input type="text" class="form-control" id="sma_invoice_amount" name="sma_invoice_amount" style="text-align:right;" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['sma_invoice_amount'];?>">
							</span>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation Order Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_order_amt" name="sma_variation_order_amt" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_order_amt'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Variation in Price(VOP)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_in_price" name="sma_variation_in_price" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_in_price'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Material Advance </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance" name="sma_material_advance" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_material_advance'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Material Advance Recovery</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_material_advance_recovery" name="sma_material_advance_recovery" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_material_advance_recovery'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduct Retention Money</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduct_retention_money" name="sma_deduct_retention_money" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduct_retention_money'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Release of Retention Money.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_release_retention_money" name="sma_release_retention_money" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_release_retention_money'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Variation due to Arbitration/ Claims or disputes</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_variation_due_to_arbitratioon" name="sma_variation_due_to_arbitratioon" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_variation_due_to_arbitratioon'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Deduction Work contract Tax</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_work_contract_tax" name="sma_deduction_work_contract_tax" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduction_work_contract_tax'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deduction Liquidated Damage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_liquidated_damage" name="sma_deduction_liquidated_damage" <?php echo $readonly; ?> style="text-align:right;" autocomplete="off" value="<?php echo $row['sma_deduction_liquidated_damage'];?>">
							</div>
						
							<label class="col-lg-3 control-label">Recovered Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_recovered_amount" name="sma_recovered_amount" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_recovered_amount'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Debit note amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_debit_note_amount" name="sma_debit_note_amount" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_debit_note_amount'];?>">
							</div>
							
							<label class="col-lg-3 control-label">Deduction Royalty / Seinorage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_deduction_royalty" name="sma_deduction_royalty" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_deduction_royalty'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Other Deduction </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="sma_other_deduction" name="sma_other_deduction" autocomplete="off" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['sma_other_deduction'];?>">
							</div>
						
							<label class="col-lg-3 control-label"> Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="sma_other_deduction_desc" name="sma_other_deduction_desc" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $row['sma_other_deduction_desc'];?>">
							</div>
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
						 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
						<div class="tab-pane <?php echo $active;?>" id="tab_2">
						
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'IP' AND reference_id = " . $ipc_id;
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
                                              <td><?php echo $document; ?></td>
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
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
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
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
							
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

							
						<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
								if ($status != 'Submited' && $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."/delete.php?did=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php		
									$role			= $_SESSION['role'];
									if ( ($status == 'Submited' || $status == 'Completed') && ($role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role == 'CEO' || $role =='COO') ){
										
										$userid   	= $_SESSION['usrid'];											
										$s1   = "SELECT count(*) as cnt from workflow_history where doc_id = '$ipc_id' and doc_type = 'IP' and reviewed_by = '$userid' ";
										$res  = mysqli_query($con, $s1);
				//echo $role. ' ' .$s1;
										echo mysqli_error($con);
										$r1   = mysqli_fetch_array($res);
										$cnt  = $r1['cnt'];
										//$create_by  = $r1['create_by'];
										if($cnt>0){
									?>	
									<?php 
										$s1   = "SELECT count(*) as cnt from workflow_history where doc_id = '$ipc_id' and doc_type = 'IP' and create_by = '$userid' or id > (SELECT id from workflow_history where doc_id = '$ipc_id' and doc_type = 'IP' and create_by = '$userid' and status = 'Reject' ) ";
				//echo $role. ' ' . $s1;						

										$res  = mysqli_query($con, $s1);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res);
										$cnt  = $r1['cnt'];
										if($cnt==0){
									?>	
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
									<?php
										}	
									  }	
									} ?>
									<!--<button  onclick='$baseurl . "approval"' class="btn btn-default"> Cancel</a></button>-->
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php		
							
								if ($status != 'Submited' && $status != 'Completed'){
									$c_role  = '';
									$userid   	= $_SESSION['usrid'];											
									//$s1   = "SELECT * from workflow_history where doc_id = '$ipc_id' and doc_type = 'AP' and reviewed_by = '$userid' order by id desc ";
									$s1   = "SELECT a.id, c.role as role, b.role as role_id, a.reviewed_by, b.username FROM `workflow_history` a, sma_user b, sma_role c where doc_type = 'IP' and doc_id = '$ipc_id'  and a.reviewed_by = b.id and b.role = c.id order by a.id desc";
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$c_role  = $r1['role'];
									$reviewed_by = $r1['reviewed_by'];
						//echo $c_role. ' ' . $reviewed_by;
									if(($c_role =='Maker' && $reviewed_by == $userid) || ($c_role =='Checker' && $reviewed_by == $userid  ) ){	
							?>	
										
										<input class="btn btn-primary" type="submit" value="Save Draft" name="Save">&nbsp;&nbsp;&nbsp;
										<?php if($c_role =='Maker') { ?>
											<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>
										<?php } ?>
							<?php 	}
									if(empty($c_role)) {
							?>
									<input class="btn btn-primary" type="submit" value="Save Draft" name="Save">&nbsp;&nbsp;&nbsp;
										
									<!--<button type="submit" class="btn btn-primary" form="form1" >Save Draft 123</button>-->
									<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>
							<?php 	} 
							?>
							
									<span>&nbsp;&nbsp;</span>
									<?php if($role=='Checker' && $c_role =='Checker' && $reviewed_by == $userid  ){ ?>
										<a href="#makerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#makerAuthority">Send to Maker</a>
										<span>&nbsp;&nbsp;</span>
										<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Submit </a>
									<?php }
									
									}
									
									?>
							
								</div>
								
							</div>
							
						</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $ipc_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IP' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
						</div>
						
						
					</div>
					
                    <!--    <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="ipc.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
                        
							
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



<!--Maker Workflow Popup-->

<div class="modal fade" id="makerAuthority" role="dialog" aria-labelledby="makerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makerAuthority">Send Back To Maker </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
								<form class="form-horizontal">
                                        
										<?php   
											
											$ipc_id = $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$sma_comp_id		 = $_SESSION['sma_comp_id'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idM" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeM" name="mode" value='Checker'>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksM"></textarea>
											</div>
										</div>
									
								</form>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary" id="submitMaker">Submit</button>
									</div>
			
                                </div>
                            </div>		
			</section>
		</div>
    </div>
  </div>
</div>

<!--Maker Workflow Popup End -->

<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ipc_id	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$sma_comp_id		 = $_SESSION['sma_comp_id'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idE" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$sma_comp_id', company_id)<>0 and role in ( select id from sma_role where role = 'Checker' )  ORDER BY first_name ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'] ?></option>
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
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										
											$ipc_id = $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
										
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idE" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
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
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ipc_id 	= $_SESSION['ipc_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ipc_id" id="ipc_idR" value="<?php echo $ipc_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										
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


<script>

   $("#submitMaker").on("click", function(e){
        var sub 			= 'sub11';
		var mode		 	=  $("#modeM").val();	
		var ipc_id		 	=  $("#ipc_idM").val();
		var remarks			=  $("#remarksM").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );
		 $('#makerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						remarks:remarks,
						sub11:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var ipc_id		 	=  $("#ipc_idE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		if(approver==''){
			alert("User Name should select...");
			return;
		}
	
		 $('#chekerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var ipc_id		 	=  $("#ipc_idE").val();
	    var status 			=  $("#statuS").val();
		
//		var account_year	= $("#account_Year").val();
		var company			= $("#sma_companY").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ipc_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						company:company,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var ipc_id		 	=  $("#ipc_idR").val();
		
		var status 			=  $("#statuS").val();
		//var ipc_id			=  $("#iD").val();
		
		//var account_year	= $("#account_Year").val();
		var company			= $("#sma_companY").val();
		var budget_head_id	= $("#budget_head_Id").val();
		var budget_name		= $("#budget_Name").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ipc_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ipc_id:ipc_id,
						mode:mode,
						company:company,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

</script>
	
<script>
function getpono(id){
    var sub = 'sub1';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getpono').html(result);
		});
}


function getpoamt(id){
    var sub = 'sub2';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub2:sub,id:id},function(result){
			  $('#getpoamt').html(result);
		});
}

function getsuppno(id){
    var sub = 'sub3';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub3:sub,id:id},function(result){
			  $('#getsuppno').html(result);
		});
}

function getsuppamt(id){
    var sub = 'sub4';
//alert(id);
	var strURL = "ipc_func.php";
	$.post(strURL,{ sub4:sub,id:id},function(result){
			  $('#getsuppamt').html(result);
		});
}

</script>


</body>
</html>
