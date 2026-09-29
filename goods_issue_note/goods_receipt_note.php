<?php

include("../header.php");
$modulePath = "goods_issue_note/goods_receipt_note.php?sub=list";

	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

$user    	= $_SESSION['user'];
$userid   	= $_SESSION['usrid'];
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "pluGRN s/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Goods Receipt Note-Without PO(GRN )-P2P
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Goods Receipt Note Without PO(GRN )-P2P</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <!-- <h3 class="box-title">Goods Receipt Note(GRN )-P2P List</h3>-->
			  <?php
			  if ($_POST['comp_id'] || $_POST['account_year'] || $_POST['product_name'] ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['product_name'] 	= $_POST['product_name'];
				}
				
				if ( $_SESSION['comp_id'] ||  $_SESSION['account_year'] ||  $_SESSION['product_name'] ){
					$comp_id 			= $_SESSION['comp_id'];
					$product_name 		= $_SESSION['product_name'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$product_name 	= $_SESSION['product_name'];
					
				}
				
			?>
				<form class="form-horizontal" action="goods_receipt_note.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-4">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where 1 and comp_id in ( $comid )  order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>	
								</div>
								
								
								
							
						<div class="col-xs-2">
                            
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="goods_receipt_note.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
						</div>
					</div>		
				</form>
				
				<div class="pull-right">
				
					<span class="sepV_c marGRN Right">
					<!--	<a href="product_opening_stock_export.php?sub=pdf" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;-->
						
					<?php //if ( $addonly=='Y'){ ?>
						<a href="goods_receipt_note.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php //} ?>	
					</span>
				</div>
				
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr><td></td>
			<td>SrNo.</td>
			<th>Company Name</th>
			<th  style="text-align:left;">Dated</th>
			
			<th style="text-align:left;">Receipt Location</th>
			<th style="text-align:left;">Receipt Person</th>
			<th style="text-align:left;">Department</th>
			<th style="text-align:left;">Product Name</th>
			<th>By</th>
			<th>Decision</th>
			<th style="text-align:left;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "goods_issue_note/";
	
	$sql="SELECT * from sma_goods_receipt_note where 1 and del != 'Y' and company_id in ($comid)";
	
	if ($viewonly =='Y' ){
		$sql="SELECT * from sma_goods_receipt_note where 1 and del != 'Y' and company_id in ($comid)";
	}
	
	if(!empty($comp_id)){
		$sql .= " and company_id = '$comp_id' ";
	}
	
	$sql .= " order by dated desc ";
	
	/* if(!empty($product_name)){
		$sql .= " and product_name = '$product_name' ";
	}
	 */
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$grn_hdr_id		= $row['id'];
		$company_id 	= $row['company_id'];
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		//echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$comp_name 	= $r2['comp_name'];
		$company_id 	= $r2['comp_code'];

	/* $product_name = $row['product_name'];
	$sql 	= "select * from sma_product where id = '$product_name' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$product_name = $r2['name'];
	 */										
		$dated 	= date('d-m-Y', strtotime($row['dated']));
		$receipt_location 		= $row['receipt_location'];
		
		$receipt_department 			= $row['receipt_department'];
		$receipt_person 			= $row['receipt_person'];
		$sql = "select * from sma_user where 1 and id = '$receipt_person' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$receipt_person			= $r2['username'];
		
		$sql = "select * from sma_department where 1 and id = '$receipt_department' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$receipt_department		= $r2['name'];
		
		$product_name = '';
		$sql = "select * from sma_goods_receipt_note_items where 1 and grn_hdr_id = '$grn_hdr_id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
			$product_id		= $r2['product_id'];
			$sql 	= "select * from sma_product where id = '$product_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$product_name .= $r2['name']."<br>";
		}
		
		$changed_by = $row['draft_by'];			
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];

	$baseurl1 = $baseurl.$modulePath1.'goods_receipt_note.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "goods_receipt_note.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" ></td>
		<td width="5%"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $company_id;?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($dated);?></td>
		
		<td width="10%" style="text-align:left;"><?php echo ($receipt_location);?></td>
		
		<td width="15%" style="text-align:left;"><?php echo ($receipt_person);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($receipt_department);?></td>
		<td width="15%" style="text-align:left;"><?php echo ($product_name);?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="goods_receipt_note.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
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
		
		$sql = "select * from sma_goods_receipt_note where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($query1);		
		$company_id   	= $r2['company_id'];
//echo $sql. "<BR>";		
		$sql = " SELECT * FROM sma_goods_receipt_note_items WHERE grn_hdr_id = '$id' ";
//echo $sql. "<BR>";		
		$q2=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){ echo $error; }
			while($r2 = mysqli_fetch_array($q2)){
				
				$item_id   		= $r2['id'];
				$receipt_qty 		= $r2['receipt_qty'];
				$product_id		= $r2['product_id'];
				
				$sql = "UPDATE sma_product_open_stock set receipts = receipts - $receipt_qty where product_name = '$product_id' and project = '$company_id' ";
//echo $sql. "<BR>";								
				//mysqli_query($con, $sql);
				echo mysqli_error($con);
				
			}
		$sql = "UPDATE sma_goods_receipt_note set del = 'Y' where id='$id' ";
//echo $sql. "<BR>";		
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//exit('Testing...');
		
        echo '<script>window.location.href="goods_receipt_note.php?sub=list";</script>';
		exit();
		
	} 
?>

<?php

	if($_POST['editSave']){

		$rid     		= $_POST['rid'];
		$grn_hdr_id 	= $_POST['grn_hdr_id'];
		$mrn_item_id	= $_POST['mrn_item_id'];
		$product_id		= $_POST['product_id'];
		$company_id		= $_POST['companyid'];
		
		$receipt_qty 		= $_POST['receipt_qty'];
		$rate		 		= $_POST['rate'];
		$rate_prev	 		= $_POST['rate_prev'];
		$receipt_qty_prev   = $_POST['receipt_qty_prev'];
		
		$sql = "SELECT * from sma_goods_receipt_note where id = '$grn_hdr_id' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		
			$sql = " UPDATE sma_goods_receipt_note_items SET saved = 'Y', receipt_qty = '$receipt_qty', rate = '$rate' where id = '$rid' ";
//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

			$sql = "UPDATE sma_product_open_stock SET receipts = ( receipts - $receipt_qty_prev ) + $receipt_qty, 
								rate = ( rate - $rate_prev ) + $rate 
						WHERE product_name = '$product_id' AND project = '$company_id' ";
//echo $sql. "<BR>";			
		//	mysqli_query($con, $sql);
			echo mysqli_error($con);
			
//exit('Testing...');			
		echo "<script>window.location.href='goods_receipt_note.php?sub=edit&id=$grn_hdr_id';</script>";
		exit();			
		//}
		
	}
	
?>		


<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$company_id			= $_POST['company_id'];
			$supplier_id		= $_POST['supplier_id'];
			$product_name		= $_POST['product_name'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$receipt_location		= $_POST['receipt_location'];
			
			$maker_mrn_id		= $_POST['maker_mrn_id'];
			$receipt_person		= $_POST['receipt_person'];
			$receipt_department	= $_POST['receipt_department'];
			$remarks			= $_POST['remarks'];
			$trans_type			= $_POST['trans_type'];
			$status				= 'Draft';
			
  			$sql="INSERT INTO sma_goods_receipt_note ( company_id, supplier_id, dated, receipt_location, maker_mrn_id, receipt_person, receipt_department, remarks,  status, draft_by, draft_dated, trans_type ) 
			Values( '$company_id', '$supplier_id', '$dated', '$receipt_location',  '$maker_mrn_id', '$receipt_person', '$receipt_department', '$remarks', '$status', '$user', now(), '$trans_type' )";
//echo $sql. "<BR>";
			$query=mysqli_query($con, $sql);
			$last_insert_id = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; }
//exit();			
			
			$sql = "INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status ) 
						VALUES('GN', '$last_insert_id', '$userid', now(), 'Draft' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//			exit('Testing...');
			//echo "Budget successful added";
			echo "<script>window.location.href='goods_receipt_note.php?sub=edit&id=$last_insert_id';</script>";
			exit();

		}
	
?>
   <section class="content-header">
        <h1>
            Goods Receipt Note Without PO(GRN )-P2P
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Goods Receipt Note Without PO(GRN )-P2P</a></li>
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
            <form class="form-horizontal" action="goods_receipt_note.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b></h4> </span>
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipt Notes No</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="grn_srno" name="grn_srno" style="text-align:right;" readonly placeholder="" value="" >
							</div>
							
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-2">
							<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                                <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy"  required
                                              value="<?php echo date('d-m-Y');?>">
                            </div>
							</div>
												
						</div>
						
						<div class="form-group">
								
							<label for="company_id" class="control-label col-sm-2"> Company Name *</label>
							<div class="col-sm-4">
									<select class="form-control select2" name="company_id" id="company_ID" required onchange="getmrnNo(this.value);" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name "; //comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?>>  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<label for="supplier_id" class="control-label col-sm-2"> Supplier Name </label>
							<div class="col-sm-4">
									<select class="form-control select2" name="supplier_id" <?= $readonly; ?> id="supplier_id"  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where 1 order by party_name "; 
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipt Location</label>
							<div class="col-md-2">
							<span id="getlocation">
								<input type="text" class="form-control" id="receipt_location" name="receipt_location"  placeholder="" value="<?php echo $row['receipt_location'];?>"  >
							</span>		
							</div>
						
							<label class="col-lg-2 control-label">Receipt to Person</label>
							<div class="col-md-2">
								<select class="form-control" id="receipt_person" name="receipt_person"  >
								<option value=""> Select </option>
						<?php		$sql = "select * from sma_user where 1 order by username ";
								$q2  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_object($q2)){
									$username = $r2->username;
									$id = $r2->id;
						?>
									<option value='<?= $id;?>' <?php echo ($row['receipt_person'] == $id )?'selected="selected"':'';?>><?= $username;?></option>
						<?php } ?>
								</select>
							</div>
							
							
							<label class="col-lg-2 control-label">Receipt Department</label>
							<div class="col-md-2">
								<select class="form-control" id="receipt_department" name="receipt_department" required="true" >
								<option value=""> Select </option>
						<?php		$sql = "select * from sma_department where 1 order by name ";
								$q2  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_object($q2)){
									$name = $r2->name;
									$id = $r2->id;
						?>
									<option value='<?= $id;?>' <?php echo ($row['receipt_department'] == $id )?'selected="selected"':'';?>><?= $name;?></option>
						<?php } ?>
								</select>
								
							</div>
							
						</div>
						
						<div class="form-group">
						
							<?php  
									$selected = '';
									$sql = "select * from sma_workflow_type where doc_type = 'GN' ";
									$q2 	= mysqli_query($con, $sql);
									$rowaffect = mysqli_affected_rows($con);
									if($rowaffect==1){
										$selected = 'SELECTED';
									}	
							
							?>		
							<label for="company_id" class="control-label col-sm-2 ">Workflow Type *</label>
							<div class="col-sm-4">									
								<select class="form-control select3" <?php echo $readonly; ?> name="trans_type" id="trans_type" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type where doc_type = 'GN' ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?= $selected;?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							</div>
						
						</div>
								
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="remarks" name="remarks"  placeholder="" value="<?php echo $row['Remarks'];?>"  >
							</div>
						
						</div>
						
						<span id="getProduct">
								<div class="col-md-12">
									<div class="box">
										<div class="box-header">
											<h4 class="box-title">Product Details</h4>
																	
										</div>
									</div>
								</div>
								
						</span>
						
						<div class="box-footer showSave">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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

			$id					= $_POST['id']; 
			$gi_id		        = $_POST['id']; 
			$company_id			= $_POST['company_id'];
			$supplier_id		= $_POST['supplier_id'];
			
			//$product_name		= $_POST['product_name'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$receipt_location		= $_POST['receipt_location'];
			$maker_mrn_id		= $_POST['maker_mrn_id'];
			$receipt_person		= $_POST['receipt_person'];
			$receipt_department	= $_POST['receipt_department'];
			$remarks			= $_POST['remarks'];
			$trans_type			= $_POST['trans_type'];
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			
			$status				= $_POST['status'];
				
  			$sql="update sma_goods_receipt_note set company_id	= '$company_id',
						supplier_id			= '$supplier_id',
						dated				= '$dated',
						receipt_location	= '$receipt_location',
						maker_mrn_id		= '$maker_mrn_id',
						receipt_person		= '$receipt_person',
						receipt_department 	= '$receipt_department',
						trans_type			= '$trans_type',	
						remarks				= '$remarks'
				where id = '$id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/gn/" . $gi_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('GN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $gi_id . ", now() )";

					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/gn/" . $gi_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_goods_receipt_note set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						approval_status		= '$status',
						status				= '$status'
					where id='$gi_id'";	
				$query=mysqli_query($con, $sql);	
//echo $sql. "<BR>";				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'GN', '$gi_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";				
				$modulePath = "goods_issue_note/";
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'goods_receipt_note.php?id='.$gi_id;
		
				//$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pr_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				
				$msg = 'Goods Receipt Note Number : '.$gi_id . ' ' . 'Dated : ' . date("d-m-Y");

			//	include "gi_mail.php";
				
			}

			echo '<script>window.location.href="goods_receipt_note.php?sub=list";</script>';
			exit();
			
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_goods_receipt_note where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$locked	 = $row['locked'];
		
		$status		 		= $row['status'];
		$approval_status	= $row['approval_status'];
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
										
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
	
//echo $sql;		
		$readonly='';
		
		if($locked=='Y'  || $viewonly=='Y' || $status == 'Submitted' || $status == 'Completed' ){
			$readonly = "READONLY";
		}
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Goods Receipt Note Without PO(GRN )-P2P
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Goods Receipt Note Without PO(GRN )-P2P</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="goods_receipt_note.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
			  <span class="pull-right"><button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button></span>
			  
			    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Goods Receipt Note(Without PO)</a></li>
						<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
                </ul>
				<div class="tab-content">
					<div class="tab-pane active " id="tab_1">
						
						<?php $grn_hdr_id = $row['id'];?>
						
						 &nbsp;&nbsp;
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b>&nbsp;&nbsp;&nbsp;</h4> </span>
						
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="status" value="<?php echo $row['status'];?>">
					  
					<?php
						$dated = date('d-m-Y', strtotime($row['dated']));
					?>	
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipts Notes No</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="grn_srno" name="grn_srno" style="text-align:right;" readonly placeholder="" value="<?php echo $row['id'];?>" >
							</div>
							
							<label class="col-lg-2 control-label">Dated</label>
							<div class="col-md-2">
							<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                                <input type="text" class="form-control" id="prDate" name="dated" <?= $readonly; ?> placeholder="dd/mm/yyyy"  required
                                              value="<?php echo $dated;?>">
                            </div>
							</div>
							
						</div>
						
						<?php $company_id = $row['company_id']; ?>
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2"> Company Name *</label>
							<div class="col-sm-4">
									<select class="form-control select2" name="company_id" <?= $readonly; ?> id="company_id" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name "; //comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<label for="supplier_id" class="control-label col-sm-2"> Supplier Name </label>
							<div class="col-sm-4">
									<select class="form-control select2" name="supplier_id" <?= $readonly; ?> id="supplier_id" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where 1 order by party_name "; 
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['supplier_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipt Location</label>
							<div class="col-md-2">
								<select class="form-control" id="receipt_location" name="receipt_location" <?= $readonly; ?>  >
								<option value=""> Select </option>
						<?php		$sql = "select * from sma_location where loc_comp_id = '$company_id' ";
								$q2  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_object($q2)){
									$loc_name = $r2->loc_name;
									$id = $r2->id;
						?>
									<option value='<?= $loc_name;?>' <?php echo ($row['receipt_location'] == $loc_name )?'selected="selected"':'';?>><?= $loc_name;?></option>
						<?php } ?>
								</select>
								
							</div>
						
							<label class="col-lg-2 control-label">Receipt to Person</label>
							<div class="col-md-2">
								<select class="form-control" id="receipt_person" name="receipt_person" <?= $readonly; ?> >
								<option value=""> Select </option>
						<?php		$sql = "select * from sma_user where 1 order by username ";
								$q2  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_object($q2)){
									$username = $r2->username;
									$id = $r2->id;
						?>
									<option value='<?= $id;?>' <?php echo ($row['receipt_person'] == $id )?'selected="selected"':'';?>><?= $username;?></option>
						<?php } ?>
								</select>
								
							</div>
							
							<label class="col-lg-2 control-label">Receipt Department</label>
							<div class="col-md-2">
								<select class="form-control" id="receipt_department" name="receipt_department" <?= $readonly; ?> required="true" >
								<option value=""> Select </option>
						<?php		$sql = "select * from sma_department where 1 order by name ";
								$q2  = mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_object($q2)){
									$name = $r2->name;
									$id = $r2->id;
						?>
									<option value='<?= $id;?>' <?php echo ($row['receipt_department'] == $id )?'selected="selected"':'';?>><?= $name;?></option>
						<?php } ?>
								</select>
								
							</div>

						</div>
						
						
						<div class="form-group">
						
							
						
							<?php $trans_type = $row['trans_type']; ?>		
							<label for="company_id" class="control-label col-sm-2 ">Workflow Type *</label>
							<div class="col-sm-4">									
								<select class="form-control select3" <?php echo $readonly; ?> name="trans_type" id="trans_type" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type where doc_type = 'GN' ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($trans_type == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							</div>
						
						</div>
						
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="remarks" name="remarks" <?= $readonly; ?> placeholder="" value="<?php echo $row['remarks'];?>"  >
							</div>
						
						</div>
						
						<div class="col-md-12">
                            <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Product Details</h4>
                                <?php 
								
								if( $status =='Draft' ) {  ?>
										<span class="pull-right">
                                            <a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem">Add 
                                            </a>
                                        </span>
								<?php  } ?>	
                                   </div>
								   
								<div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Specification</th>
												<th>Unit of Measurement</th>
												<th style="text-align:right;">Receipt Qty.</th>
												<th style="text-align:right;">Rate</th>
												<th style="text-align:right;">Value</th>
							                    <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
												
										 		$sql = "SELECT * from sma_goods_receipt_note_items where 1 and grn_hdr_id = '$grn_hdr_id' ";
//echo $sql. "<BR";												
												$res = mysqli_query($con, $sql);
												$itemcnt = mysqli_affected_rows($con);
												echo mysqli_error($con);
												$value="";
												while($r3 = mysqli_fetch_array($res)){

													$product_id		= $r3['product_id'];
													$specification	= $r3['specification'];
													$receipt_qty 		= $r3['receipt_qty'];
													$rate 		    = $r3['rate'];
													$units		 	= $r3['units'];
													$saved		 	= $r3['saved'];
													
													//$total_value 	= $r3['total_value'];
													
													$total_value 			= round($receipt_qty * $rate,2);
													
													$sql = "select * from sma_product where id = '$product_id'";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$product_name = $r1['name'];
													
											 		$rid = $r3['id'];
												?>	
													<tr>
														<td width='15%'><?= $product_name?></td>
														<td width='15%'><?= $specification?></td>	
														<td width='8%'><?= $units?></td>
														<td width='8%' style="text-align:right;"><?= $receipt_qty;?></td>
														<td width='8%' style="text-align:right;"><?= $rate;?></td>
														<td width='8%' style="text-align:right;"><?= $total_value;?></td>
														<!--<td width='8%' style="text-align:right;" ><input type="text" class="form-control" style="text-align:right;" value="<?= $total_value;?>" onkeyup="updValue(this.value, <?= $rid; ?> );" ></td>-->
														
														<td width='6%'>
										<?php  if(empty($readonly)){	?>			
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->
													<?php include "edit_grn_func.php"; ?>
										<?php } ?>			
<!-- Modal Edit Item-->
											<!--<a href='#modalDeleteItem' id='delete-<?php echo $_GET['id'];?><?php echo $rid;?>' data-toggle='modal' data-id='<?php echo $_GET['id'];?><?php echo $rid;?>' data-target='#modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>'><i class='fa fa-trash-alt'></i></a>
														-->
<!-- Modal Delete Item-->
													<?php //include "del_func.php"?>
<!-- Modal Delete Item-->
														</td>
														
													</tr>
											<?php
												}
											?>		

                                            </tbody>
                                        </table>
                                 
							</div>
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
				
				<div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
							
							 <?php
							  $gi_id = $_GET['id'];
                              $sql = "SELECT * FROM file_uploads WHERE module = 'GN' AND reference_id = " . $gi_id;
                              $docResults = mysqli_query($con, $sql);
							  
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%">Document Type</th>
                                          <th width="30%">Description</th>
										  <th width="30%">Document Name</th>
                                          <th width="10%"></th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$rw1 = mysqli_fetch_array($rs);
													$document = $rw1['document'];
											
											  ?>
                                          <tr>
                                              <td width="20%"><?php echo $document; ?></td>
                                              <td width="30%"><?php echo $docRow['doc_desc'] ?></td>
											  <td width="30%"><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                          
								<?php if(empty( $readonly) || $user=='Admin' ){ ?>
											   <td width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                <?php } ?>             
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
                                
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control doctype" name="doctype[]">
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
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="30%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td width="10%"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            
							</div> 
							
						<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<span id="predit"></span>
								
						</div>
						
						<?php  // echo $status. ' ' .	$approver_1. "<BR>";					  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
//echo $sql. "<BR>";									
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><BR>
									<label class="control-label"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><BR>
									<label class="control-label"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><BR>
									<label class="control-label"><?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><BR>
									<label class="control-label"><?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><BR>
									<label class="control-label"><?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									</label>
								</div>
					<?php	
							}
					?>		
							</div>
					<?php		
						}

					//echo $status ."<>";
					
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
								<div class="box-footer">
								
							
								<BR>
								
							</div>
						
						</span>
				<?php } ?>		
				
				
						<div class="box-footer">
							<div class="col-sm-6">
						<?php $did = $_GET['id']; 
							if( $status == 'Draft' ){	
						?>
								<a href="<?php echo $baseurl."goods_issue_note/goods_receipt_note.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
						<?php } ?>	
							</div>
							
							<?php $baseurl1 = $baseurl.$modulePath;?>
							
							<div class="col-sm-6 text-right">
							
							<?php 
								$approver_flag='';
							if( $status != 'Draft' ){
//echo $userid . ' ' . 	$approver_5 . ' ' . $approver_1_status. "<BR>";								
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
									$userid == $approver_2 && $approver_2_status=='Submitted' || 
									$userid == $approver_3 && $approver_3_status=='Submitted' ||
									$userid == $approver_4 && $approver_4_status=='Submitted' ||
									$userid == $approver_5 && $approver_5_status=='Submitted' ||
									$userid == $approver_6 && $approver_6_status=='Submitted' ||
									$userid == $approver_7 && $approver_7_status=='Submitted' ||
									$userid == $approver_8 && $approver_8_status=='Submitted' ){
				
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted' ){
										$approver_flag='Y';
									}
									
									
								}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){	
							?>	
							<span id="hideGI">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							</span>
							<?php } 
							
								}
							?>
							
					<?php



					?>	
							
							<span>&nbsp;&nbsp;</span>
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							<?php if ($role!='Checker' && $viewonly!='Y'){ ?>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							<?php } ?>	
							
							<?php if( $status == 'Draft'){ 
							//$itemcnt>0 &&?>	
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
							<?php } ?>
									
							
							</div>
						</div>
						
						
					</div>
				<!--TAB_3-->
						
					<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $gi_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'GN' order by id ";
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
								<span style="marGRN : 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
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
											
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'GN' order by id desc";
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
			
					
						
                </fieldset>
				
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>

	
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


<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add to Goods Receipt Note </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="grn_Id" value="<?php echo $_GET['id'];?>">
							
							<div class="form-group">
								
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group where 1 ORDER BY product_group ASC ";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
                                <div class="col-sm-8">
									<label for="itemName" class="control-label">Product Name</label>
									<span id="getmaterial1" >
										<select class="form-control" name="itemName" id="itemName" required >
											<option value="">Select</option>	
										</select>
									</span>
									
									<span id="getdupprd" style="color:red;"></span>
									
								</div>	
                                
                            </div>
							
							<div class="form-group col-md-12">
                                <div class="col-sm-12">
									<label for="itemDescription" class="control-label">Specification</label>
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
                            </div>
						
							 <div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" >
                                </div>
								
								<span class="getberror" style="color:red;" ></span>
                           
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Rate</label>
                                    <input type="text" class="form-control" id="ratE"  style="text-align:right;" >
                                </div>
								
								<span class="getberror" style="color:red;" ></span>
                            </div>
						
							<div class="form-group col-md-12">
								<span id="getunit2">
								
								<div class="col-sm-4 col-md-4">
									<label for="itemUnits" class="control-label">Unit of Measurement</label>
									
	                               <input type="text" class="form-control " readonly id="itemUnits"  style="text-align:right;" >
								</div>
								
								</span>   
								
							</div>
	
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary editItemSave" id="addItem">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Item-->


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send for Approval </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
								<form class="form-horizontal">
                                        
										<?php   
										
										//	$grn_hdr_id 		= $_SESSION['grn_hdr_id'];
										//	$status 			= $_SESSION['status'];
											$role				= $_SESSION['role']; //Maker
																				
										?>		
										<input type="hidden" name="grn_hdr_id" id="grn_hdr_idE" value="<?php echo $grn_hdr_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										
										<input type="hidden" id="approverE" name="approver" value='<?= $userid ?>' >
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    
								</form>	
								
									</div>

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
                <h4 class="modal-title" id="rejectAuthority">Reject Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
									<form class="form-horizontal">
                                        
										<?php   
											//$grn_hdr_id 	= $_SESSION['grn_hdr_id'];
											//$status = $_SESSION['status'];
										?>
										
										<input type="hidden" name="grn_hdr_id" id="grn_hdr_idR" value="<?php echo $grn_hdr_id; ?>" >
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
	  
	  
<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td width="30%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="30%" ><input type="file" name="fudoc[]" class="docfile"></td><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
<script src="<?php echo $baseurl . "pluGRN s/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "pluGRN s/datatables/dataTables.bootstrap.js"?>"></script>


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
            "paGRN g": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


	function getmaterial1(id){
		
        var sub    = 'sub4A';
		var company_id 	=  $("#company_id").val();
		var strURL = "gin_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub4A:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}
	
	function getunit2(id){	
        var sub    		= 'sub4';
		var company_id 	=  $("#company_id").val();
//alert(sub);
		var strURL = "gin_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub4:sub},function(result){
		      $('#getunit2').html(result);
		});
		
	}

    $("#addItem").on("click", function(e){
        var sub = 'sub11G';
	
		var grn_Id 			=  $("#grn_Id").val();		
        var product_id 		=  $("#itemName option:selected").val();
		var name 			=  $("#itemName option:selected").html();

		var description 	=  $("#itemDescription").val();
        var quantity 		=  parseInt($("#itemQuantity").val());
		var rate 			=  parseInt($("#ratE").val());
        var units 			=  $("#itemUnits").val();
		//var closestock 		=  $("#closeStock").val();
		
		$('.editItemSave').show();
		$('.getberror').html('');

		
		if(product_id=='' || product_id==0 || isNaN(product_id) ){
			var err = 'Product should select...';
			$('.getberror').html(err);
			$('.editItemSave'+srno).hide();
			return false;	
		}
		if(quantity==0 || isNaN(quantity) ){
			var err = 'Qty should not be zero...';
			$('.getberror').html(err);
			$('.editItemSave'+srno).hide();
			return false;	
		}
		
//alert(sub);
        $('#modalAddItem').modal('hide');
		var strURL = "gin_func.php";
		$.post(strURL,{ grn_Id:grn_Id,
							description:description,
							product_id:product_id,
							quantity:quantity,
							rate:rate,
							units:units,
							sub11G:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
    });

	function getapprover(){
		
		var company_id    	= document.getElementById("company_id").value;
		var trans_type    	= document.getElementById("trans_type").value;
		var doc_type = 'GN';
		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + ' ' + trans_type + ' ' + company_id );
		//checker_value:checker_value,
		var strURL = "gin_func.php";
		$.post(strURL,{company_id:company_id,doc_type:doc_type,trans_type:trans_type,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}
	
   $("#submitApprove").on("click", function(e){
        var sub 			= 'sub99';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var grn_hdr_id		 	=  $("#grn_hdr_idE").val();
	    var status 			=  $("#statuS").val();
//alert(grn_hdr_id);		
		var company			= $("#companY").val();
		var approver		=  $("#approverE").val();
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();
		
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
		
		$('#predit').html('Wait ...');
		
		$('#hideGI').hide();
		var strURL = "gin_func.php";
		$.post(strURL,{ grn_hdr_id:grn_hdr_id,
						mode:mode,
						company:company,
						status:status,
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub99:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});
	
	
    $("#submitReject").on("click", function(e){
        var sub = 'sub99';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var grn_hdr_id		 	=  $("#grn_hdr_idR").val();
		
		var status 			=  $("#statuS").val();
		var company			= $("#companY").val();
//		var budget_head_id	= $("#budget_head_Id").val();
//		var budget_name		= $("#budget_Name").val();		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+grn_hdr_id);
		
		$('#hideGI').hide();
		 $('#rejectAuthority').modal('hide');
		var strURL = "gin_func.php";
		$.post(strURL,{ grn_hdr_id:grn_hdr_id,
						mode:mode,
						company:company,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub99:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});


	function updValue(tvalue, rid){
		var sub = 'sub16';
		//alert(tvalue + ' >><< ' + rid);
		//var rid		 	=  $("#RID").val();
		var strURL = "gin_func.php";
		$.post(strURL,{ tvalue:tvalue,
						rid:rid,
						sub16:sub},
						function(result){
		});
		
	}	

</script>

</body>
</html>
