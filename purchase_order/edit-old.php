<?php
include("../header.php");
$modulePath = "purchase_order/"; 

$_SESSION['reset'] = '1';

?>

<?php

if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){

		$id						= $_POST['id'];
		$po_id					= $_POST['id'];
		$status					= $_POST['status'];
		$approval_memo_ref		= $_POST['approval_memo_ref'];

		$purchase_id = $po_id;
		$tot_amount = '';
		
		if(!empty($approval_memo_ref)){
		
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
			
			$values = $tot_amount;
	
			$sql = "SELECT * FROM `sma_budget` where id in (SELECT budget_head FROM `sma_approval_memo` where id = '$approval_memo_ref')";
										
			$bd = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$bdrw = mysqli_fetch_array($bd);
			$budget_head_id 	= $bdrw['id'];	
			$account_year 		= $bdrw['account_year'];
			$budget_name  		= $bdrw['budget_name'];
			$budget_head	 	= $bdrw['budget_category'];
			$company 			= $bdrw['project'];
			
			$sql = "select * from sma_budget where id = '$budget_head_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_object($q3);
			if($_POST['submit1']=='Approve'){
				$blocked_budget   	= $r3->blocked_budget - $values;  //if Approved - Less
				$used_budget   	    = $r3->used_budget + $values;  //if Approved - Add
				$sql = "update sma_budget set blocked_budget= '$blocked_budget', used_budget = '$used_budget' where id = '$budget_head_id' ";
				$q3  = mysqli_query($con, $sql);
			}
			else if($_POST['submit2']=='Reject'){
				$blocked_budget   	= $r3->blocked_budget - $values ;  //if Rejected
				$sql = "update sma_budget set blocked_budget= '$blocked_budget' where id = '$budget_head_id' ";
				$q3  = mysqli_query($con, $sql);
				//$r3  = mysqli_fetch_object($q3);
			}
		}
		else {

			$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r1 = mysqli_fetch_array($res1)){
				$qty 	= $r1['quantity'];
				$rate 	= $r1['unit_rate'];
				$gst	= $r1['gst'];
				$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
				//$tot_amount = $tot_amount + $amount;
			
				$values = $amount;
			
				$budget_head_id 	= $r1['id'];	
				$account_year 		= $r1['account_year'];
				$budget_name  		= $r1['budget_name'];
				$budget_head	 	= $r1['budget_category'];
				$company 			= $r1['project'];
				
				$sql = "select * from sma_budget where id = '$budget_head_id' ";
				$q3  = mysqli_query($con, $sql);
				$r3  = mysqli_fetch_object($q3);
				if($_POST['submit']=='Submit'){
					$blocked_budget	= $r3->blocked_budget + $values;  //if Approved - Less
					$sql = "update sma_budget set blocked_budget= '$blocked_budget' where id = '$budget_head_id' ";
					$q3  = mysqli_query($con, $sql);
				}
				else if($_POST['submit1']=='Approve'){
					$used_budget    = $r3->used_budget + $values;  //if Approved - Add
					$sql = "update sma_budget set used_budget = '$used_budget' where id = '$budget_head_id' ";
					$q3  = mysqli_query($con, $sql);
				}
				else if($_POST['submit2']=='Reject'){
					$blocked_budget = $r3->blocked_budget - $values ;  //if Rejected
					$sql = "update sma_budget set blocked_budget= '$blocked_budget' where id = '$budget_head_id' ";
					$q3  = mysqli_query($con, $sql);
					//$r3  = mysqli_fetch_object($q3);
				}
				
			} // While Loop
			
		}
		
//Budget calculation END		
		
		if($_POST['submit1']){
			$approval_status	= 'Approved';
			$status				= 'Completed';
		}
		else if($_POST['submit2']){
			$approval_status	= 'Rejected';
			$status				= 'Draft';
		}
	
		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Submited';
		}
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		
		$sql = "update sma_purchase_order set approval_status	= '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id = '$id'";
		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		$company	= $_POST['project'];
		$role		= $_SESSION['role']; //Maker
		
		$sql = "SELECT * FROM `company` where comp_id = $company ";
//echo $sql."<BR>";
		$res = mysqli_query($con, $sql);
		$r1  = mysqli_fetch_array($res);
		
		$project_manager  = $r1['project_manager'];
		$project_incharge = $r1['project_incharge'];
		$coo_cxo 		  = $r1['coo_cxo'];

		$s1   = "SELECT * from workflow_history where doc_id = '$po_id' and doc_type = 'PO' and reviewed_by = '$userid' ";
		$res  = mysqli_query($con, $s1);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$reviewed_by  = $r1['reviewed_by'];

		if($project_manager == $reviewed_by){
			$approver	= $project_incharge;
		}
		else if($project_incharge == $reviewed_by){
			$approver	= $coo_cxo;
		}
		else if($coo_cxo == $reviewed_by){
			$a='';
		}
		else {
		
			$approver	= $project_manager;
			if(empty($project_manager) ){
				$approver	= $project_incharge;
			}
			if(empty($project_incharge) ){
				$approver	= $coo_cxo;
			}

		}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date) 
									values('PO', '$po_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now() )";

//echo $sql."<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$sql="select * from sma_user where id='$approver' ";
//echo $sql."<BR>";
		$result = mysqli_query($con, $sql);
		//$rowcount = mysqli_num_rows($result);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
		
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;
		
		$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "po_mail.php";

		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";

}

if($_GET['sub']=='delete'){
	$po_id	= $_GET['po_id'];
	$sql="delete from sma_purchase_order where id = '$po_id' ";
	//$value1=$sql;
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$sql="delete from sma_po_items where purchase_req_id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$sql="delete FROM `file_uploads` where module = 'PO' and reference_id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

if($_POST['editSave']){

		$rid     		= $_POST['rid'];
		$purchase_id 	= $_POST['purchase_id'];
		$product_id		= $_POST['product_id'];
		$categoryid		= $_POST['categoryid'];
		$description 	= $_POST['itemdescription'];
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		$quantity 		= $_POST['itemquantity'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		$amount 		= $_POST['itemamount'];
		$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));

		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name = $r1['name'];
		
		$sql = "update `sma_po_items` set product_id='$product_id', product_name='$product_name', product_category = '$categoryid',
					product_desc='$description', 
					account_year   = '$account_year',
					company_id     = '$company_id',
					budget_name    = '$budget_name',
					budget_head    = '$budget_head',
					quantity='$quantity', uom='$units',
					unit_rate='$rate', gst='$gst', delivery_date='$deliverydate' 
				where purchase_id = '$purchase_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
	
//echo $sql;
//exit();

$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&active=active&888';
//echo $baseurl1;

//exit();
	echo "<meta http-equiv='refresh' content='0'>";    
	echo "<script>window.location.href='$baseurl1';</script>";
	//echo "<script>window.location.href='purchase_order.php?sub=edit&id=$purchase_id&active=active&888';</script>";

	}
	
	if($_GET['sub']=='Save'){
			$id				= $_POST['id']; 
			$po_id			= $_POST['id']; 
			$po_number			= $_POST['po_number']; 
			$po_doc_type		= $_POST['po_doc_type'];
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$location			= $_POST['location'];
			$delivery_address   = $_POST['delivery_address'];
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
		//	$status				= $_POST['status'];
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
			
			$po_number = $comp_code.'/'.$yyyy.'/'.$loc_code.'/'.$srno;
			
  			$sql="update sma_purchase_order set approval_memo_ref	= '$approval_memo_ref', 
						po_number			= '$po_number', 
						po_doc_type			= '$po_doc_type',
						dated				= '$dated',
						project				= '$project',
						location			= '$location',
						delivery_address    = '$delivery_address',
						department			= '$department',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						quotation_reference_no	= '$quotation_reference_no',
						to_supplier			= '$to_supplier',
						delivery_days		= '$delivery_days',
						credit_days			= '$credit_days',
						payment_terms		= '$payment_terms',
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
			
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	
		$id = $_GET['id'];
		$po_id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$po_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

	
	$status = $row['status'];
	$readonly = '';
	if ($status == 'Submited'){
		$readonly = 'READONLY';
	}
		
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Order
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Order</a></li>
            <li class="active">Edit</li>
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
						<form id="form1" class="form-horizontal" action="edit.php?sub=Save" method="post" enctype="multipart/form-data">
                          
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
		                
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>

						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
						
				<div class="box-body">		
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
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" > Order </a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab" >Materials</a></li>
						<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<li><a href="pur_order_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1" class="btn btn-success" target="_blank" >Print </a></li>
						
                    </ul>
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<?php $_SESSION['project'] = $row['project'];
							  $_SESSION['status']  = $row['status'];						
						?>
						
						<div class="form-group">
							
							<div class="col-sm-2">
								<label for="project" class="control-label">Order Type</label>
								<select class="form-control " name="po_doc_type" id="po_doc_type" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
									<option value="PO" <?php echo ($row['po_doc_type'] == "PO" )?'selected="selected"':'';?> > Purchase Order </option>
									<option value="SO" <?php echo ($row['po_doc_type'] == "SO" )?'selected="selected"':'';?> > Service Order </option>
									<option value="WO" <?php echo ($row['po_doc_type'] == "WO" )?'selected="selected"':'';?> > Work Order </option>
									<option value="CA" <?php echo ($row['po_doc_type'] == "CA" )?'selected="selected"':'';?> > Contract Agreement </option>
										
								</select>
							</div>
								
							
                            <div class="col-sm-4">
								<label for="project" class="control-label">Company</label>
								<select class="form-control " name="project" id="projecT" onchange="getlocation(this.value)" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-sm-2">
							<label for="deliveryLocation" class="control-label">Location</label>
									<span id="getlocation">
										<select class="form-control" id="location" name="location" onchange="getdelvaddr(this.value)" <?php echo $readonly; ?> >
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
						
							<div class="col-md-4">
								<label class="control-label">Delivery Address</label>
								<span id="getdelvaddr">
								<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address" <?php echo $readonly; ?> ><?php echo $row['delivery_address'];?></textarea>
								</span>
							</div>
										
						</div>
						
						<div class="form-group">
						
							<div class="col-md-3">
								<label class="control-label">PO.Number</label>
								<input type="text" class="form-control" id="po_number" name="po_number" style="text-align:left;" readonly value="<?php echo $row['po_number'];?>" >
							</div>
							
							<div class="col-md-3">
								<label class="control-label"> Order Date</label>
						            <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="dated" name="dated" <?php echo $readonly; ?> value="<?php echo date('d-m-Y', strtotime($row['dated']));?>">
									</div>
							</div>
							
							<?php $approval_memo_ref = $row['approval_memo_ref']; ?>
							
							<div class="col-md-3">
								<label class="control-label">Against Approval Memo Ref.</label>
								<select class="form-control" name="approval_memo_ref" id="approval_memo_Ref" <?php echo $readonly; ?> onchange="getsupplier(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_approval_memo where status = 'Completed' order by id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_memo_ref'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['id'].
									' ('. date('d-m-Y', strtotime($r2['dated'])).')';?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Department</label>
								<select class="form-control" name="department" id="department" <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">To Supplier</label>
								<span id="getsupplier">	
								<select class="form-control" name="to_supplier" id="to_Supplier" <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['to_supplier'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
								</span>
								
							</div>

							<div class="col-md-3">
								<label class="control-label">Supplier Quote Ref.No.</label>
								<span id="getqref">
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" <?php echo $readonly; ?> value="<?php echo $row['quotation_reference_no'];?>" >
								</span>
								
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Delivery within</label>
								<input type="text" class="form-control" id="delivery_days" name="delivery_days" maxlength="3" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $row['delivery_days'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" <?php echo $readonly; ?> maxlength="3" style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Discount Amount</label>
								<input type="text" class="form-control" id="discount" name="discount" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $row['discount'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Transport Amount</label>
								<input type="text" class="form-control" id="transport" name="transport" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $row['transport'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Other Charges</label>
								<input type="text" class="form-control" id="other_charges" name="other_charges" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $row['other_charges'];?>" >
							</div>
						
	
						</div>
							
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header"><span class="box-title">Terms & Conditions</span></div>
								<div class="box-body">
									<textarea class="form-control" id="reason" name="terms" <?php echo $readonly; ?> ><?php echo $row['terms'];?> </textarea>
								</div>
							</div>
						</div>


						 <?php $user=$_SESSION['user']; ?>						
<!--						<div class="form-group">							
							<div class="col-md-2">
									<label class="control-label">Prepared By</label>
									<select class="form-control" name="prepared_by" id="prepared_by" <?php echo $readonly; ?> >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_user order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['userid'];?>" <?php echo (strtoupper($user) == strtoupper($r2['userid']))?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
									</select>	
							</div>
							<?php 
								$status = $row['status'];
								if($status == 'Submited'){
							?>
										<div class="col-md-2">
											<label class="control-label">Approval Status</label>
											<select class="form-control" name="approval_status" id="approval_status" >
												<option value=""> Select </option>
												<option value="Pending" <?php echo ($row['approval_status'] == 'Pending')?'selected="selected"':'';?> >Pending</option>
												<option value="Approved" <?php echo ($row['approval_status'] == 'Approved')?'selected="selected"':'';?> >Approved</option>
												<option value="Rejected" <?php echo ($row['approval_status'] == 'Rejected')?'selected="selected"':'';?> >Rejected</option>
												<option value="Discarded" <?php echo ($row['approval_status'] == 'Discarded')?'selected="selected"':'';?> >Discarded</option>
											</select>
										</div>
								<?php
								}
								?>				
										<div class="col-md-2">
											<label class="control-label">Status</label>
											<input type="text" class="form-control" name='status' readonly value="<?php echo $row['status'];?>" >
										</div>

						<!--<div class="col-md-2">
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
							
							
						</div>-->
						
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
				<?php
					if ($_GET['active']){
						$active = $_GET['active'];
					}
				?>
				
				<div class="tab-pane <?php echo $active;?> " id="tab_2">

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
                                                <th style="text-align:right;">PO.Qty</th>
												<th style="text-align:right;">Received Qty.</th>
												<th style="text-align:right;">Bal.Qty.</th>
                                                <th>Unit</th>
                                                <th style="text-align:right;">Rate</th>
												<th style="text-align:right;">GST%</th>
                                                <th style="text-align:right;">Amount</th>
												
												<th>Action</th>
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
													$bal_grn_qty 	= $row['bal_grn_qty'];
													$bal_qty = $qty - $bal_grn_qty;
													
													$rate 	= $row['unit_rate'];
													$gst	= $row['gst'];
													$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
													$tot_amount = $tot_amount + $amount;
													
													$delivery_date = date('d-m-Y', strtotime($row['delivery_date']));
													if($delivery_date == '01-01-1970'){
														$delivery_date = '';
													}
													$rid = $row['id'];
												?>	
													<tr>
														<td width='15%'><?php echo $row['product_name']?></td>
														<td width='15%'><?php echo $row['product_desc']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $row['quantity']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $bal_grn_qty;?></td>
														<td width='8%' style="text-align:right;"><?php echo $bal_qty;?></td>
														<td width='8%'><?php echo $row['uom']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $row['unit_rate']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $row['gst']?></td>
														<td width='10%' style="text-align:right;"><?php echo $amount?></td>					 
														<!--<td width='10%'><?php echo $delivery_date?></td>-->
														<td width='6%'>
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
												
                                                <th colspan="2">Total Amount</th>
                                                <th style="text-align:right;"><?php echo $tot_amount;?></th>
												<th></th>
												<th></th>
                                            </tr>
                                            </tfoot>
											
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
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PO' AND reference_id = " . $po_id;
//						echo $sql;
						
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
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$rw1 = mysqli_fetch_array($rs);
													$document = $rw1['document'];
											
											  ?>
                                          <tr>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                              <td><?php echo $document; ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
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

							<?php
							
								$_session['po_id'] 	= $po_id;
								$_session['status']  = $status;
							
							?>
							
							<span id="predit"></span>
						  
							<div class="box-footer">
								<div class="col-sm-6">
									<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&po_id='.$id ; ?>
								<?php		
									if ($status != 'Submited'  && $user=='Admin'){
								?>	
									<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>							
								<?php	} ?>
									<span>&nbsp;&nbsp;</span>
									
								</div>
								
								<div class="col-sm-6 text-right">
								<?php
								$role			= $_SESSION['role'];
							//	echo $role;
								if ( ($status == 'Submited' || $status == 'Completed') && ($role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role == 'CEO' || $role =='COO') ) {
									
										$userid   	= $_SESSION['usrid'];											
										$s1   = "SELECT count(*) as cnt from workflow_history where doc_id = '$po_id' and doc_type = 'PO' and reviewed_by = '$userid' ";
						//	echo $s1;			
										$res  = mysqli_query($con, $s1);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res);
										$cnt  = $r1['cnt'];
										//$create_by  = $r1['create_by'];
										if($cnt>0){
								?>	
									<?php 
										$s1   = "SELECT count(*) as cnt from workflow_history where doc_id = '$po_id' and doc_type = 'PO' and create_by = '$userid' ";
						//	echo $s1;			
										$res  = mysqli_query($con, $s1);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res);
										$cnt  = $r1['cnt'];
										//$create_by  = $r1['create_by'];
										if($cnt==0){ 
									?>	
											
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php	
											}
										}
									} ?>

									
									<span>&nbsp;&nbsp;</span>
									<!--<input class="btn btn-primary" type="submit" value="Save" name="Save">-->
								<?php		
									if ($status != 'Submited' && $status != 'Completed'){
								?>	
									<?php if($role=='Maker'){ ?>
										<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>
									<?php } 
									
									?>
									
										<input type="submit" class="btn btn-primary" value="Save Draft" name="Save">
									<?php if($role=='Checker' ){ ?>	
										<span>&nbsp;&nbsp;</span>
										<!--<input type="submit" class="btn btn-primary" value="Submit" name="submit" >-->
										<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Submit </a>
									<?php   }
										} ?>
										
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button>
									
								</div>
							</div>	
						  
						</div>
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $po_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id ";
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id desc";
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
			
					
                    </fieldset>
				
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Material to  Order </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">
							
							<input type="hidden" name="app_remo_ref" id="app_remo_ref" value="<?php echo $approval_memo_ref ?>" >
							
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
                                    <textarea rows='03' class="form-control" id="itemDescription" placeholder="Item Description..."></textarea>
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
							
							<?php
							
								$b_readonly = '';
								if(!empty($approval_memo_ref)){
									
									echo "<center><b>Approval Memo Reference No. : ".$approval_memo_ref. "</b></center>";
									
									$sql = "SELECT * FROM `sma_budget` where id in (SELECT budget_head FROM `sma_approval_memo` where id = '$approval_memo_ref')";
									$bd = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    $bdrw = mysqli_fetch_array($bd);
									$budget_head 		= $bdrw['id'];	
									$account_year 		= $bdrw['account_year'];
									$budget_name  		= $bdrw['budget_name'];
									$budget_category 	= $bdrw['budget_category'];
									$project 			= $bdrw['project'];
									$b_readonly   		= "READONLY";
									
									
									$sql = "SELECT * from company where comp_id = '$project' ";
									$res = mysqli_query($con, $sql);
									//echo mysqli_error($con);
									$r2 = mysqli_fetch_array($res);
									
									$project = $r2['comp_name'];
									
									if ($account_year=='1'){
										$acyr = '2017-2018';
									}
									else if ($account_year=='2'){
										$acyr = '2018-2019';
									} 
									else if ($account_year=='3'){
										$acyr = '2019-2020';
									} 
									else if ($account_year=='4'){
										$acyr = '2020-2021';
									}
									else if ($account_year=='5'){
										$acyr = '2021-2022';
									}	
									else if ($account_year=='6'){
										$acyr = '2022-2023';
									}	
									else if ($account_year=='7'){
										$acyr = '2023-2024';
									}	
									else if ($account_year=='8'){
										$acyr = '2024-2025';
									}	
									else if ($account_year=='9'){
										$acyr = '2025-2026';
									}	
									else if ($account_year=='10'){
										$acyr = '2026-2027';
									}
									
									$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									
									$category = $r2['category'];
									
									$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									
									$bname = $r2['name'];

								?>
							 
								<input type="hidden" class="form-control" id="account_year" readonly value="<?php echo $account_year ?>" >
								<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name ?>" >
								<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_category ?>" >
							
								<div class="form-group">
									<div class="col-md-4">
										<label class=" control-label">Account Year</label>
										<input type="text" class="form-control" id="itemDescription" readonly value="<?php echo $acyr ?>" >
									</div>
									
									<div class="col-md-6">
										<label class=" control-label">Company</label>
										<input type="text" class="form-control" id="itemDescription" readonly value="<?php echo $project ?>" >
									</div>
								</div>
											
								<div class="form-group">
									<div class="col-sm-6">
										<label class="control-label">Budget Name</label>
										<input type="text" class="form-control" id="itemDescription" readonly value="<?php echo $bname ?>" >
									</div>
									
									<div class="col-sm-6">
										<label class="control-label">Budget Head</label>
										<input type="text" class="form-control" id="itemDescription" readonly value="<?php echo $category ?>" >
									</div>
								</div>
								
							<?php	
								}
								else {
							?>
							
                            <div class="form-group">
								<div class="col-md-4">
									<label class=" control-label">Account Year</label>
									<select class="form-control" name="account_year" id="account_year" onchange="getbudget(this.value)" >
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
							<!--
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
							-->
		
								</div>
							
							<div class="form-group">
							
								<div class="col-sm-6">
									<label class="control-label">Budget Name</label>
								<span id="getbudget">
									<select class="form-control" name="budget_name" id="budget_Name" onchange="getbudgethead(this.value)">
									<option value=""> Select </option>
										<?php $sql = "SELECT name, id from sma_budget_name where id in (select budget_name FROM `sma_budget` where account_year = '$account_year')";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  >  <?php echo $r2['id']. '-' .$r2['name'];?></option>
										<?php } ?>
									</select>
								</span>	
								</div>
							
							<div class="col-sm-6">
									<label class="control-label">Budget Head</label>
									<span id="getbudgethead">
										<select class="form-control" name="budget_head" id="budget_Head" >
										<option value=""> Select </option>
											<?php //$sql = "SELECT b.category as category_name, a.budget_category as category FROM `sma_budget` a, sma_budget_category b where a.budget_category = b.id ";
											//$q2 	= mysqli_query($con, $sql);
											//while($r2 = mysqli_fetch_array($q2)){ ?>
										<!--<option value="<?php echo $r2['category'];?>" <?php echo ($row['budget_head'] == $r2['category'])?'selected="selected"':'';?> >  <?php //echo $r2['category_name'];?></option>-->
											<?php //} ?>
										</select>
									</span>	
								</div>
								
							</div>
							
							<?php 
								} 
							?>
							
						</div>
						 
							 <div class="form-group">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label">Units</label>
									<span id="getunit1">
										<select class="form-control" id="itemUnits" name ="itemunits" >
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
                                        <input type="text" class="form-control" id="itemRate"  style="text-align:right;"  onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>

							</div>

                            <div class="form-group">
                            
                                <div class="col-sm-4">
									<label for="itemGST" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST"  style="text-align:right;" onkeyup="calculateTotalAmount();">
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
										
											$po_id 	= $_session['po_id'];
											//$status = $status;
										
										?>
										
										<input type="hidden" name="po_id" id="po_idE" value="<?php echo $po_id; ?>" >
										
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
											$po_id 	= $_session['po_id'];
											$status = $_session['status'];
											
										?>
										
										<input type="hidden" name="po_id" id="po_idR" value="<?php echo $po_id; ?>" >
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

<!--/.col (right) -->

<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document1</label></td><td><select class="form-control select2 doctype col-sm-1" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
											$po_id 	= $_SESSION['po_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$company		 = $_SESSION['project'];
										?>
										
										<input type="hidden" name="status" id="statusE" value="<?php echo $status; ?>" >
										<input type="hidden" name="po_id" id="po_idE" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and role in ( select id from sma_role where role = 'Checker' )  ORDER BY first_name ASC";
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

   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var po_id		 	=  $("#po_idE").val();
//		var department 		=  $("#departmentE option:selected").val();
//var department 		=  $("#departmentE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#checkerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		 $('#checkerAuthority').modal('hide');
				 
	});
	

    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);

		var company		 	=  $("#projecT").val();
		var po_id		 	=  $("#po_idE").val();
	    var status 			=  $("#statuS").val();
		var to_supplier		= $("#to_Supplier").val();
		var approval_memo_ref	= $("#approval_memo_Ref").val();
		var budget_head_id	= $("#budget_Head").val();
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();
		
//alert(company + ' ' + statusap+' #0# '+status+' #1# '+approval_memo_ref+' #5# '+po_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						company:company,	
						approval_memo_ref:approval_memo_ref,
						budget_head_id:budget_head_id,
						to_supplier:to_supplier,
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
		var po_id		 	=  $("#po_idR").val();
		
		var status 			=  $("#statuS").val();
		//var po_id			=  $("#iD").val();
		
		var account_year	= $("#account_Year").val();
		var company			= $("#companY").val();
		var budget_head_id	= $("#budget_head_Id").val();
		var budget_name		= $("#budget_Name").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+po_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						account_year:account_year,
						company:company,
						budget_head_id:budget_head_id,
						budget_name:budget_name,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

	
    var itemArray = []; // stores all item details table values in memory

/*    $(document).ready(function () {
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
*/	

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
            $("#itemQuantity").val("");
            $("#itemUnits").val("");
            $("#itemRate").val("");
			$("#itemGST").val("");
            $("#itemAmount").val("");
			$("#deliveryDate").val("");
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
		var budget_name  =  $("#budget_Name option:selected").val();
		var budget_head  =  $("#budget_Head option:selected").val();
		
        var catname =       $("#categoryId option:selected").html();
		var description =   $("#itemDescription").val();

//alert(budget_head + ' ' + budget_name+' '+description);

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

	function getunit2(id){	
        var sub    = 'sub4';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getunit2').html(result);
		});
	}

	function getunit1(id){	
        var sub    = 'sub44';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub44:sub},function(result){
		      $('#getunit1').html(result);
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
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getsupplier').html(result);
		});
	}


	function getqref(id){
        var sub    = 'sub8';
        var approval_hdr_id    = document.getElementById("approval_memo_Ref").value;
//alert(approval_hdr_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub,approval_hdr_id:approval_hdr_id},function(result){
		      $('#getqref').html(result);
		});
	}
		
</script>

</body>
</html>
