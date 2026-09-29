<?php
include("../header.php");
$modulePath = "purchase_order/"; 

$_SESSION['reset'] = '1';
$userid   	= $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
?>

<?php

if($_GET['sub']=='delete'){
	$po_id	= $_GET['po_id'];

	$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
			
	while($row = mysqli_fetch_array($result)){
		
		$product_id 	= $row['product_id'];
		$qty 			= $row['quantity'];
		$budget_head	= $row['budget_head'];
		$budget_id		= $row['budget_id'];
												
		$rate 			= $row['unit_rate'];
		$gst			= $row['gst'];
		$gstamt			= (($qty * $rate) * $gst / 100);
		$po_amount 		= ($qty * $rate) + $gstamt;

		if($po_amount <= 0 ){
			$po_amount = 0;
		}

		if($budget_id>0){
			$sql = "update sma_budget set blocked_budget = blocked_budget - $po_amount where id = '$budget_id' ";
			mysqli_query($con, $sql);
				
			$sql = "select * frpm sma_budget where id = '$budget_id' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$blocked_budget = $r2['blocked_budget'];
				
			if($blocked_budget<0){
				$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
				mysqli_query($con, $sql);
			}
		}
	
	}

	//$sql="delete from sma_purchase_order where id = '$po_id' ";
	$sql="update sma_purchase_order set del = 'Y' where id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

/*	$sql="delete from sma_po_items where purchase_id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$sql="delete FROM `file_uploads` where module = 'PO' and reference_id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	$sql="delete FROM `workflow_history` where doc_type ='PO' and doc_id ='$po_id'";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
*/	
	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

if($_POST['editSave']){

		$rid     		= $_POST['rid'];
		$purchase_id 	= $_POST['purchase_id'];
		$product_id		= $_POST['product_id'];
		$description 	= $_POST['itemdescription'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		$budget_id		= $_POST['budget_id_curr'];
		/* if($_SESSION['budget_id']){
			$budget_id = $_SESSION['budget_id'];
		} */
		
		$quantity 		= $_POST['itemquantity'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		$gst_id			= $_POST['itemgst_id'];
		
		$po_type		= $_POST['po_type'];

		$sql 	= "select * from gst_mst where 1 and igst = '$gst' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$gst_id 		= $r22['id'];
						
		if(empty($gst)){
			$gst =0;	
		}	
			
		$gstamt 			= round((($rate * $quantity) * $gst / 100),0);
		$amount				= ($rate * $quantity) + $gstamt;

		$ap_value 			= $_POST['ap_value'];
		$ap_quantity		= $_POST['ap_quantity'];
		$po_value 			=  $amount;//$_POST['po_value'] +
		$po_quantity 		= $_POST['po_quantity'] + $quantity ;
		$approval_memo_ref 	= $_POST['approval_memo_ref'];

//echo $quantity . ' ##1 ' . $po_quantity .' > '. $ap_quantity .' || ' . $po_value .' > ' . $ap_value."<BR>";
		if($po_quantity  > $ap_quantity || $po_value > $ap_value ){
			$errmsg = 'Quantity / Value should not be overflow for Approved quantity / value...';
			echo $errmsg;
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
			echo "<meta http-equiv='refresh' content='0'>";    
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}	

		$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		$approval_memo_ref 	= $r2['approval_memo_ref'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		if(empty($gst)){
			$gst =0;	
		}	
			
		$gstamt 		= round((($rate * $quantity) * $gst / 100),0);
		$amount			= ($rate * $quantity) + $gstamt;
		
		$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));

		if(empty($product_id)){
			$errmsg = 'Product must be select.....';
			echo $errmsg;
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
			echo "<meta http-equiv='refresh' content='0'>";    
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
		
		if(empty($budget_id)){
			$errmsg = 'Cost Center must be select.....';
			echo $errmsg;
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
			echo "<meta http-equiv='refresh' content='0'>";    
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
		
		if( empty($quantity) || empty($rate) ){
			$errmsg = 'Quantity / Rate must be enter.....';
			echo $errmsg;
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
			echo "<meta http-equiv='refresh' content='0'>";    
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
		
		$budget_id_prev = $_POST['budget_id_prev'];
		$quantity_prev 	= $_POST['qty_prev'];
		$rate_prev 		= $_POST['rate_prev'];
		$gst_prev 		= $_POST['gst_prev'];

		if(empty($gst_prev)){
			$gst_prev =0;	
		}
		$gstamt_prev = round((($rate_prev * $quantity_prev) * $gst_prev / 100),0);		
		$amount_prev	= ($rate_prev * $quantity_prev) + $gstamt_prev;

		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name = $r1['name'];
		
		if($po_type=='C'){
			
			$sql   = "SELECT * FROM sma_budget where id = '$budget_id' ";	
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$budget_name 		= $r2['budget_name'];
			$budget_head 	    = $r2['budget_head'];
			$total_budget		= $r2['total_budget'];
			$blocked_budget		= $r2['blocked_budget'];
			$used_budget		= $r2['used_budget'];
			$budget_adjustment	= $r2['budget_adjustment'];
			$check_budget		= ($total_budget + $budget_adjustment) - ($block_budget + $used_budget);
			
			if ($check_budget < $amount){
				$_SESSION['budget_id'] ='';	
				echo "<script>alert('Insufficient Budget for Product Name $product_name')</script>";
				$errmsg = 'Insufficient Budget for Product Name :' . $product_name . ' / Cost Center : ' . $budget_head;
				echo $errmsg;
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
				echo "<meta http-equiv='refresh' content='0'>";    
				echo "<script>window.location.href='$baseurl1';</script>";
				exit();	
			}
			
			$sql = " update sma_budget set blocked_budget = blocked_budget + $amount - $amount_prev where id = '$budget_id' ";
//echo $sql."<BR>";			
			mysqli_query($con, $sql);
			
			
		}
//exit();
		//((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100))
	if($po_type=='A'){
		$sql = "update `sma_po_items` set product_id = '$product_id', 
					product_name	= '$product_name', 			
					product_desc	= '$description', 
					bal_si_qty		= bal_si_qty + '$quantity' - '$quantity_prev', 
					bal_si_amount	= '$amount', 
					uom				= '$units',
					unit_rate		= '$rate', 
					gst				= '$gst',
					gst_id			= '$gst_id',
					budget_head		= '$budget_head',
					budget_name		= '$budget_name',
					budget_id 		= '$budget_id',
					delivery_date	= '$deliverydate'
				where purchase_id = '$purchase_id' and id = '$rid' ";
	}
	else {
		$sql = "update `sma_po_items` set product_id = '$product_id', 
					product_name	= '$product_name', 			
					product_desc	= '$description', 
					quantity		= quantity + '$quantity' - '$quantity_prev', 
					bal_si_qty		= '$quantity', 
					bal_si_amount	= '$amount', 
					uom				= '$units',
					unit_rate		= '$rate', 
					gst				= '$gst',
					gst_id			= '$gst_id',
					budget_head		= '$budget_head',
					budget_name		= '$budget_name',
					budget_id 		= '$budget_id',
					delivery_date	= '$deliverydate'
				where purchase_id = '$purchase_id' and id = '$rid' ";	
	}		
echo $sql."<BR>";
//exit();
		mysqli_query($con, $sql);
		echo mysqli_error($con);
	
		$sql = "UPDATE sma_approval_items SET po_quantity = po_quantity + '$quantity' - '$quantity_prev', 
					po_value= po_value+ $amount - $amount_prev 
					WHERE approval_hdr_id = '$approval_memo_ref' and product_id = '$product_id'";
echo $sql."<BR>";					
			mysqli_query($con, $sql);
			echo mysqli_error($con);
	//exit('TESTING EXIT...');			
	$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888';
	//echo $baseurl1;
//	exit();
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
	//echo "<script>window.location.href='purchase_order.php?sub=edit&id=$purchase_id&active=active&888';</script>";

	}
	
	
	if($_GET['sub']=='Save'){
			$id				= $_POST['id']; 
			$po_id			= $_POST['id']; 
			$po_number			= $_POST['po_number']; 
			$po_type			= $_POST['po_type'];
			$approval_memo_ref	= $_POST['approval_memo_ref'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$project			= $_POST['project'];
			$trans_type			= $_POST['trans_type'];
			$location			= $_POST['location'];
			$delivery_address   = $_POST['delivery_address'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
//			$against_indent_no	= $_POST['against_indent_no'];
			$quotation_reference_no	= $_POST['quotation_reference_no'];
			$to_supplier		= $_POST['to_supplier'];
			$delivery_days		= $_POST['delivery_days'];
			$credit_days		= $_POST['credit_days'];
			$payment_terms		= $_POST['payment_terms'];
			
		//	$status				= $_POST['location'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			$other_charges		= $_POST['other_charges'];
			$discount			= $_POST['discount'];
			$transport			= $_POST['transport'];
			$advance_flag		= $_POST['advance_flag'];
			$header_text		= $_POST['header_text'];
			$department			= $_POST['department'];
			
			$background			= $_POST['background'];
			$scope_of_work		= $_POST['scope_of_work'];
			$deviations_from_sop	= $_POST['deviations_from_sop'];
			$important_terms_conditions	= $_POST['important_terms_conditions'];
						
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			
			$status				= $_POST['status'];
			$subject			= $_POST['subject'];
			$notes				= $_POST['notes'];
			$supplier_location	= $_POST['supplier_location'];
			
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
			
		//	$po_number = $comp_code.'/'.$yyyy.'/'.$loc_code.'/'.$srno;
			
  			$sql="update sma_purchase_order set approval_memo_ref	= '$approval_memo_ref', 
						po_number			= '$po_number', 
						dated				= '$dated',
						project				= '$project',
						trans_type			= '$trans_type',
						location			= '$location',
						delivery_address    = '$delivery_address',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						quotation_reference_no	= '$quotation_reference_no',
						to_supplier			= '$to_supplier',
						delivery_days		= '$delivery_days',
						department			= '$department',
						credit_days			= '$credit_days',
						header_text			= '$header_text',
						payment_terms		= '$payment_terms',
						delivery_date		= '$delivery_date',
						other_charges		= '$other_charges',
						discount			= '$discount',
						transport			= '$transport',
						advance_flag		= '$advance_flag',
						terms				= '$terms',
						subject				= '$subject',
						background			= '$background',
						scope_of_work		= '$scope_of_work',
						deviations_from_sop	= '$deviations_from_sop',
						important_terms_conditions	= '$important_terms_conditions',
						notes				= '$notes',
						supplier_location	= '$supplier_location'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_purchase_order set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'PO', '$po_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$modulePath = "purchase_order/"; 
				
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
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
		
				$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");

				include "po_mail.php";
				
			}
			
			// add attachments
			// file upload
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST["share_point_link"];
			for($i = 0; $i < sizeof($share_point_link); $i++){
				if(!empty($share_point_link[$i])){
					$sql = "INSERT INTO file_uploads (module, share_point_link, doc_type, doc_desc, reference_id, date_uploaded) 
					VALUES('PO', '$share_point_link[$i]', '$arrDocType[$i]', '$arrDocDesc[$i]', '$po_id','now()' )";
					mysqli_query($con, $sql);
				}
			}

//DMS Doc upload
			if($_POST['party_doc']){
				$party_doc = $_POST['party_doc'];
				for($i = 0; $i < sizeof($party_doc); $i++){
				
					$doc_in = $party_doc[$i];
					$sql = "SELECT module, file_path, file_name, reference_id, doc_type FROM `my_documents_files` where id = '$doc_in' ";
					//echo $sql. "<BR>";
					$res = mysqli_query($con, $sql);
					$r11 			= mysqli_fetch_array($res);
					$filename 		= $r11['file_name'];
					$folder_path 	= $r11['file_path'];
					$arrDocType 	= $r11['doc_type'];
					$reference_id 	= $r11['reference_id'];
					
					$sql = "INSERT INTO file_uploads (module, dms_module, file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded) 
					VALUES('PO', 'IN', '$filename', '$folder_path', '$arrDocType', '$po_id', '$reference_id', now())";
					mysqli_query($con, $sql);
					//echo $sql. "<BR>";
					
					//exit();
				}
			}
//DMS Doc upload	
			
			
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$_SESSION['budget_id'] ='';
		$id = $_GET['id'];
		$po_id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$po_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

	
	$status = $row['status'];
	$del 	= $row['del'];
	
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
									
	$approval_status = $row['approval_status'];
	$po_amend 		 = $row['po_amend'];
	
	$readonly = '';
	if (($status == 'Submitted' ) || $status == 'Completed' || $status == 'Suspend'){
		$readonly = 'READONLY';
	}
	if ( $user=='Admin' && $status != 'Draft' ){
		$readonly = 'READONLY';
	}
	
	if($approval_status=='Rejected'){
		$readonly = 'READONLY';
	}
	
	if($del =='Y'){
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
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard_athang.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
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
		                
						<?php
						
						$status   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
						
						?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $status;?></b></h4> </span>
						
						<?php $approval_status = $row['approval_status']; 
						if($approval_status=='Rejected'){
						?>
							<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['approval_status'];?> &nbsp;&nbsp;&nbsp;&nbsp;</b></h4> </span>
							
						<?php } ?>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		                <span class="pull-right"><a href="pur_order_prn.php?sub=prn&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1&print_flag=V" class="btn btn-success " target="_blank" >Print </a>&nbsp;&nbsp;&nbsp;</span>
						
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
						
				<div class="box-body">		
				<?php
					$purchase_id = $row['id'];
					
					if ($_GET['active']){
						$active = $_GET['active'];
						$active_1 = ' ';
					}
					else if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active = ' ';
							$active_1 = ' ';
							
					}
					else
					{
						$active_1 = 'active';
					}
				?>
					
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" > Order </a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab" >Terms</a></li>
						<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>
						<li><a href="po_approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
					<?php
						if( ($status == 'Completed'  || $po_amend =='Y' ) ){	
					?>
					<!--	<li><a href="#tab_5" data-toggle="tab"  class="btn btn-danger" id="five_tab" >PO Amendment</a></li>-->
					<?php
						}
					?>	
						
                    </ul>
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<?php $_SESSION['project'] = $row['project'];
							  $_SESSION['status']  = $row['status'];						
						?>
						<?php 
								$company_id = $row['project'];
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2 	= mysqli_query($con, $sql);
								$r2  = mysqli_fetch_array($q2);
								
								$po_type = $row['po_type'];
						?>
						<div class="form-group">
							<div class="col-lg-7" style="padding-top: 6px;">
					<?php if($po_type=='A'){ ?>		
								<span class="label label-warning" style="font-size:14px;color:white;" >
								<input type="hidden" name='po_type' id='po_typea' value='A'  >
								<input type="radio" name='po_type' id='po_typea' <?php echo ($po_type=='A')?"CHECKED":''; ?>  value='A' onclick="getclear(this.value);" > PO Against Approval Memo &nbsp;
					<?php } ?>		
					<?php if($po_type=='C'){ ?>			
								<input type="hidden" name='po_type' id='po_typea' value='C'  >
								<input type="radio" name='po_type' id='po_typec' <?php echo ($po_type=='C')?"CHECKED":''; ?>  value='C' onclick="getclear(this.value);" > PO cum Approval Memo &nbsp;
								</span>&nbsp;
					<?php } ?>
							</div>
							
						</div>
										
						<div class="form-group">
							<div class="col-sm-4">
								<label for="project" class="control-label">Company<span style="color:red;"> **</span></label>
					<?php
						if($status =='Draft'){
							
							$project = $row['project'];
					?>		
							
								<select class="form-control " name="project" id="projecT" 
								onchange="getlocation(this.value);getcompanyterm(this.value);" <?php echo $readonly; ?> required readonly >
                             		<option value=""> Select </option>
									<?php 
									$sql = "select * from company where 1 and comp_id = '$project' and comp_id in ($comid) order by comp_name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							
					<?php }
						else {
							$project = $row['project'];
							$sql = "select * from company where comp_id in ($comid) and comp_id = '$project' ";
							
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$comp_name = $r2['comp_name'];
					?>				
							<input type="hidden" name="project" id="projecT" value="<?= $project;?>" >
							<input type="text" class="form-control" readonly  value="<?= $comp_name;?>" >
					<?php } ?>	
						
						</div>	
							
						<div class="col-md-3">
								<label class="control-label">To Supplier<span style="color:red;"> **</span></label>
						<?php
						if($status =='Draft'){

//echo $sql = " select * from sma_party_mst where id in ( SELECT b.supplier_name FROM `sma_approval_memo` a, `sma_approval_details` b WHERE 1 and a.id = b.approval_hdr_id and a.company = '$project' and b.vendor_selected = 'Y' ) ";
							if($po_type=='C'){
							
								$sql = " select * from sma_party_mst where id in ( SELECT b.supplier_name FROM `sma_purchase_order` a, `sma_po_approval_details` b WHERE 1 and a.id = b.po_approval_hdr_id and a.project = '$project' and b.vendor_selected = 'Y' ) ";
							}
							
							else {
								$sql = " select * from sma_party_mst where id in ( SELECT b.supplier_name FROM `sma_approval_memo` a, `sma_approval_details` b WHERE 1 and a.id = b.approval_hdr_id and a.company = '$project' and b.vendor_selected = 'Y' ) ";
							}
					//echo $sql;		
						?>		
								<span id="getsupplier">	
								<select class="form-control" name="to_supplier" id="to_Supplier" <?php echo $readonly; ?> required 
									onchange="get_taxstatus(this.value);getapproval(this.value);" >
									<option value=""> Select </option>
										<?php //$sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['to_supplier'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
								</span>
						<?php }
							else {
							$to_supplier = $row['to_supplier'];
							$sql = "select * from sma_party_mst where id = '$to_supplier' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$party_name = $r2['party_name'];
						?>				
							<input type="hidden" name="to_supplier" id="to_Supplier" value="<?= $to_supplier;?>" >
							<input type="text" class="form-control" readonly  value="<?= $party_name;?>" >
						<?php }
						?>
							</div>
							
							
						<?php 
						if($po_type=='A'){
							$approval_memo_ref = $row['approval_memo_ref']; ?>
							
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
						<?php  } ?>	
							
						<div class="col-md-2" >
								<label class="control-label ">Tax Status</label><br>
						<?php //style="padding-top: 6px;"
							$supplier_location = $row['supplier_location'];
							if($supplier_location=='L'){
								$supplier_location = 'Local';
							}
							else if($supplier_location=='O'){
								$supplier_location = 'Out of State';
							}
						?>
								<input type="hidden" name="supplier_location" id="supplier_location" value="<?= $row['supplier_location'];?>" >
								
								<input type='text' class="form-control" readonly value="<?= $supplier_location; ?>">
						
						</div>
														
					</div>
						
						<?php 
							$company_id = $row['project']; 
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$comp_vertical 		= $r2['comp_vertical'];
							$budget_control_gst = $r2['budget_control_gst'];
						?>
							
						<div class="form-group">  
							<div class="col-md-3">
								<label class="control-label">Department<span style="color:red;"> **</span></label>
						<?php
						if($status =='Draft'){
						?>		
								<select class="form-control" <?php echo $readonly; ?> name="department" id="department" required readonly >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_department order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
									<?php } ?>
								</select>
						<?php } 
							else {
							$department = $row['department'];
							$sql = "select * from sma_department where id = '$department' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$dept_name = $r2['name'];
						?>				
							<input type="hidden" name="department" id="department" value="<?= $department;?>" >
							<input type="text" class="form-control" readonly  value="<?= $dept_name;?>" >
						<?php }
						?>
						
							</div>
							
								

							<div class="col-md-3">
								<label class="control-label">Supplier Quote Ref.No.</label>
								<span id="getqref">
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no"  <?php echo $readonly; ?> value="<?php echo $row['quotation_reference_no'];?>" >
								</span>
								
							</div>
							<?php $trans_type = $row['trans_type']; ?>		
							<div class="col-sm-3">
								<label for="company_id" class="control-label ">Workflow Type *</label>
								<select class="form-control select3" name="trans_type" id="trans_type" required READONLY >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type where id = '$trans_type' ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($trans_type == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
							
							</div>
							
							
						</div>
						
							
                        <div class="form-group">    
							<div class="col-sm-2">
							<label for="deliveryLocation" class="control-label">Delivery Location<span style="color:red;"> **</span></label>
						<?php
						if($status =='Draft'){
						?>	
									<span id="getlocation">
										<select class="form-control" required id="location" name="location" onchange="getdelvaddr(this.value)" <?php echo $readonly; ?> >
											<option value="">Select</option>
										<?php
											$sql="SELECT id, loc_name FROM sma_location where loc_comp_id = '$company_id' ORDER BY loc_name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($result)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'] ?></option>
											<?php } ?>
										</select>
									</span>
						<?php } 
							else {
							$location = $row['location'];
							$sql = "select * from sma_location where id = '$location' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$loc_name = $r2['loc_name'];
						?>				
							<input type="hidden" name="location" id="location" value="<?= $location;?>" >
							<input type="text" class="form-control" readonly  value="<?= $loc_name;?>" >
						<?php }
						?>
						
							</div>		
						
						<span id="getdelvaddr">								
							<div class="col-md-6">
								<label class="control-label">Delivery Address</label>
								<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address" <?php echo $readonly; ?> ><?php echo $row['delivery_address'];?></textarea>
								
							</div>
						</span>
						</div>
						
						
						<?php
								$po_number 		= $row['po_number'];
								$po_number_v 	= $po_number;
								$po_rev	   		= $row['po_rev'];
								$old_po_no	   	= $row['old_po_no'];
									
								if($po_rev > 0){
									$po_number_v = $po_number.$po_rev;
								}
						?>
							
						<div class="form-group">
							
										
							<div class="col-md-3">
								<label class="control-label">PO.Number</label>
								<input type="hidden" class="form-control" id="po_number" name="po_number" style="text-align:left;" readonly value="<?php echo $row['po_number'];?>" >
								<input type="text" class="form-control"  style="text-align:left;" readonly value="<?php echo $po_number_v;?>" >
							</div>
							
							<div class="col-md-3">
								<label class="control-label"> Order Date</label>
						<?php
							if($status =='Draft'){
						?>		
						        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                    <div class="input-group-addon">
                                       <i class="fa fa-calendar-alt"></i>
                                    </div>
                                    <input type="text" class="form-control" id="dated" name="dated" <?php echo $readonly; ?> readonly value="<?php echo date('d-m-Y', strtotime($row['dated']));?>">
								</div>
						<?php } 
							else {
								$dated  = date('d-m-Y', strtotime($row['dated']));
						?>		
								<input type="text" class="form-control" name="dated" id="dated" readonly value="<?= $dated;?>" >
						<?php	}
							?>			
							</div>
							
							<div class="col-md-3">
								<label class="control-label">Credit Days</label>
								<input type="text" class="form-control" id="credit_days" name="credit_days" <?php echo $readonly; ?> maxlength="3" style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
							</div>
					
						
						<?php 
							$party_id_doc = $row['to_supplier'];
							
						?>
							
							<div class="col-md-1">
								<label class="control-label">&nbsp;</label>
							</div>
						<?php
						if($po_type=='A'){	
							$ap_id = $row['approval_memo_ref'];
							$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id;&r=1";
						?>
							<div class="col-md-2">
								<label class="control-label" style="font-size:14px;" >Document : </label>
								<a href="<?php echo $baseurl_ap; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Approval Memo</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>	
						<?php }  ?>		
						</div>
						
					<!--	<div class="form-group">
							
							<?php $advance_flag = $row['advance_flag']; ?>
							<div class="col-md-3">
								<label class="control-label"><span style="font-size:14px;text-align:right;">Advance Payment Required?</span></label><br>&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="checkbox" id="advance_flag" name="advance_flag" <?php echo ($advance_flag=='Y')?"CHECKED":"";?> value="Y" >
								
							</div>
							
							<?php $paid_amount = $row['paid_amount'];
							if($paid_amount >0){?>
							<div class="col-md-2">
								<label class="control-label">Open Unadjusted Advance ?</label>
								<input type="text" class="form-control" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $row['paid_amount'];?>" >
							</div>
							<?php 
								}
							?>
						</div>	
					-->	
							
						<div class="form-group">
								
							<div class="col-md-12">
								<label class="control-label">Subject</label>
								<input type="text" class="form-control" id="subject" name="subject" <?php echo $readonly; ?> value="<?php echo $row['subject'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
										
									<div class="col-md-12">
										<label class="control-label">Notes</label>
										<input type="text" class="form-control" id="notes" name="notes" <?php echo $readonly; ?> value="<?php echo $row['notes'];?>" >
									</div>
									
						</div>
								
				<?php if($po_type=='C' || $po_type=='A'){ ?>				
						<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
								<?php if($po_type=='C' ){ ?>	
                                        <h4 class="box-title">Vendor Comparison </h4>
										<?php $data_mode = 'Add';?>
                                        <span class="pull-right">
                                            <a href="#"
                                               class="btn btn-primary" data-mode='Add'
                                               data-toggle="modal" data-target="#modalAddVendor<?php echo $data_mode;?>">Add
                                            </a>
                                        </span>
								<?php } ?>		
                                    </div>
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
								<?php if($po_type=='C' ){ ?>		
                                            <thead>
                                            <tr>
                                                
                                                    <th style="text-align:left">Supplier Name</th>
													<th style="text-align:left">Quote Ref.No.</th>
													<th style="text-align:left">Vendor Selected</th>
													<th style="text-align:right">Value</th>

													<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
								<?php } ?>												
                                            <tbody id="prItemsTableBody">
											<?php
												//$approval_hdr_id=$_GET['approval_hdr_id'];
									 		$sql="SELECT * from sma_po_approval_details where  po_approval_hdr_id='$po_id'";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
												
											while($rowd = mysqli_fetch_array($result)){
												$po_approval_hdr_id = $rowd['po_approval_hdr_id'];
												$approval_srno = $rowd['approval_srno'];
												
												$supplier_name = $rowd['supplier_name'];
												$sql = "select * from sma_party_mst where id = '$supplier_name' ";
												$q2  = mysqli_query($con, $sql);
												$r2 = mysqli_fetch_array($q2);
												$supplier_name  = $r2['party_name'];
												
												$party_id_doc   = '';
												$selected = '';
												$vendor_selected = $rowd['vendor_selected'];
												if($vendor_selected=='Y'){
													$selected = 'Selected';
													$selected_vendor_value = $rowd['values'];
													
													$party_id_doc   = $r2['id'];
													
												}
												
											?>

                                            <tr>
                                 
                                            	<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
												<td width="20%" style="text-align:left"><?php echo $rowd['quote_ref_no'];?></td>
												<td width="10%" style="text-align:left"><?php echo $selected;?></td>
												<td width="10%" style="text-align:right"><?php echo number_format($rowd['values'],2);?></td>
												<td width="10%" style="text-align:right">
												<a href='#modalEditItemq' data-id='<?php echo $approval_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItemq<?php echo $approval_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_vendor_func.php"; ?>							
<!-- Modal Edit Item-->														
												<?php if($status !='Completed'){ ?>
														<a href='#modalDeleteItem' id='delete-<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>' data-toggle='modal' data-id='<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>' data-target='#modalDeleteItem<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>'><i class='fa fa-trash-alt'></i></a>
														</td>
												<?php } ?>		
<!-- Modal Delete Item-->								
												<?php include "del_vendor_func.php"?>
<!-- Modal Delete Item-->

											</tr>
											<?php }?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
										
										<input type="hidden" id="selected_vendor_value" name="selected_vendor_value" value="<?php echo $selected_vendor_value;?>" >
										
                                    </div>
                                </div>
                            </div>
				<?php } ?>			
<!--Vendor Comparision -->
							
						<div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
						
                            <h4 class="box-title">Product Details</h4>
							<?php if (empty($readonly) && $po_type=='C'){ ?>
                                    			
                                <span class="pull-right">
									<a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem">Add 
                                    </a>
                                </span>
							<?php } ?>
                                    			
                                </div>
                                <div class="box-body">
                                    <table id="prItemsTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Cost Center</th>
                                        <th style="text-align:right;">PO.Qty</th>
										<th style="text-align:right;">PO Amount</th>
										<th style="text-align:right;">Received Qty.</th>
										<th style="text-align:right;">Bal.Qty.</th>
                                        <th>Unit</th>
                                        <th style="text-align:right;">Rate</th>
										<th style="text-align:right;">GST%</th>
                                        <th style="text-align:right;">Bal.Amount</th>
												
										<th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="prItemsTableBody">
									<?php	
									//$purchase_id = $row['id'];
									
									$po_no		 = $purchase_id;
									
									$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' and ( quantity > 0 ) 	";
									mysqli_query($con, $sql);
									$items_cnt = mysqli_affected_rows($con);
									
									
									$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id'";
									$result = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$value="";
									while($row2 = mysqli_fetch_array($result)){
										
										$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
										$q2	=	mysqli_query($con, $sql);
										$r2 =	mysqli_fetch_array($q2);
										$po_type			= $r2['po_type'];
										
										$budget_err 	= $row2['budget_err'];
										$qty 			= $row2['quantity'];
										//$bal_grn_qty 	= $row2['bal_grn_qty'];
										$bal_si_qty		= $row2['bal_si_qty'];
										$bal_si_amount	= $row2['bal_si_amount'];
												
										$bal_qty 		= $qty - $bal_si_qty;
								//echo $bal_qty .' = ' . $qty . ' - ' . $bal_si_qty;		
										
										$unit = $row2['uom'];
										$product_id 	= $row2['product_id'];
										$sql="SELECT * FROM sma_product where id = '$product_id' ";
												
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$mat = mysqli_fetch_array($res2);
										$product_name = $mat['name'];
										$product_category = $mat['group'];
										
										if(empty($unit)){
											$unit = $mat['uom'];
										}	
										
										$budget_id 	= $row2['budget_id'];
										$sql="SELECT * FROM sma_budget where id = '$budget_id' ";
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$cat = mysqli_fetch_array($res2);
										$cost_center = $cat['budget_head'];
										$blocked_budget = $cat['blocked_budget'];
										$used_budget 	= $cat['used_budget'];
										$total_budget 	= $cat['total_budget'];
										$bal_budget	= $total_budget - ( $used_budget + $blocked_budget );
										
										$rate 	= $row2['unit_rate'];
										$gst	= $row2['gst'];
										$gstamt = round((($qty * $rate) * $gst / 100),0);
										
										$amount = $qty * $rate + $gstamt;
										
										$bal_amount = $amount - $bal_si_amount;
										
										$po_amount = $amount;
										
										$check_amount = $qty * $rate + $gstamt;
										$tot_amount = $tot_amount + $amount;
													
										$delivery_date = date('d-m-Y', strtotime($row2['delivery_date']));
										if($delivery_date == '01-01-1970'){
											$delivery_date = '';
										}
										$rid = $row2['id'];
									?>	
									<?php 
										if($budget_err=='Y'){ 
											if($check_amount > $bal_budget){
									?>
											<tr>
												<td width='94%' colspan='9' style="color:red;	"><?php echo 'Insufficient Budget for  Product Name : ' . $product_name . '  Cost Center : '. $cost_center; ?></td>
												<td width='6%'></td>
											</tr>
									<?php 	}
											else {
												$sql = "update sma_po_items set budget_err = '' where id = '$rid' ";
												mysqli_query($con, $sql);
											}	
										}
										
									?>
										<tr>
											<td width='15%'><?php echo $product_name;?></td>
											<td width='15%'><?php echo $cost_center;?> </td>	
											<td width='8%' style="text-align:right;"><?php echo $row2['quantity']?></td>	
											<td width='10%' style="text-align:right;"><?php echo number_format($po_amount,2)?></td>
											<td width='8%' style="text-align:right;"><?php echo $bal_si_qty;?></td>
											<td width='8%' style="text-align:right;"><?php echo $bal_qty;?></td>
											<td width='8%'><?php echo $unit;?></td>	
											<td width='8%' style="text-align:right;"><?php echo $row2['unit_rate']?></td>	
											<td width='8%' style="text-align:right;"><?php echo $row2['gst']?></td>
											
											<td width='10%' style="text-align:right;"><?php echo number_format($bal_amount,2)?></td>											
											<td width='6%'>
												<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
														
<!-- Modal Edit Item-->
											<?php include "edit_func.php"; ?>
<!-- Modal Edit Item-->
											<?php  if (empty($readonly)){ ?>
													
												<a href="del_poitem.php?sub=delete&po_no=<?php echo $po_no;?>&id_no=<?php echo $rid;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											<?php
													
											 } ?>							
<!-- Modal Delete Item-->
														
											</tr>
											<?php
												
											}
												
											$checker_value = $tot_amount;
												
											?>		

                                            </tbody>
											<?php
											if($_GET['errmsg']){
												echo '<tr><td colspan="7" style="color:red" >'.$_GET['errmsg']. '</td></tr>';
											}	
											?>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
												<th></th>
												<th></th>
												<th></th>
                                                <th colspan="2">Total Amount</th>
                                                <th style="text-align:right;"><?php echo number_format($tot_amount,2);?></th>
												
												<th></th>
                                            </tr>
                                            </tfoot>
											
                                        </table>
								<?php $checker_value = $tot_amount; ?>		
										
										<input type="hidden" id="checker_value" name="checker_value" value="<?php echo $checker_value;?>" >
										
					<?php echo '<span style="color:red;">'.$_SESSION['error_msg']."</span>";?>
                                    </div>
								</div>
							</div>			
						
				
						
					<?php 	
					if ($po_type=='C'){ ?>
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="background"
                                                  placeholder="Enter text ..."  <?php echo $readonly; ?> ><?php echo stripslashes($row['background']);?></textarea>
                                    </div>
									
									
								</div>
							</div>
						
						<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Scope of Work</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason1" name="scope_of_work"  <?php echo $readonly; ?>
                                                  placeholder="Enter text ..."><?php echo stripslashes($row['scope_of_work']);?></textarea>
                                    </div>
									
									
                                </div>
									
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
                                <div class="box-header"><span class="box-title">Deviations from SOP</span></div>
                                <div class="box-body">
                                    <textarea class="form-control" id="reason2" name="deviations_from_sop"  <?php echo $readonly; ?>
                                                  placeholder="Enter text ..."><?php echo  stripslashes($row['deviations_from_sop']);?></textarea>
                                </div>
							</div>
						</div>

						
				<?php }  ?>
				
				
						
						
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click');getvalidate();" >Next</a>
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
									
						<?php
							$terms = $row['terms'];
						if(empty($terms)){
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$terms = $r2['header_terms'];
						}	
						?>	
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header"><span class="box-title">Special Terms & Condition</span></div>
								
								<div class="box-body">
									<textarea class="form-control" id="reason3" name="terms" <?php echo $readonly; ?> ><?= $terms;?></textarea>
								</div>
							
							</div>
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
				</div>
				
                        <div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
							
                            <!-- Attachments company_idd -->
							<?php	
							
							$sql = "SELECT count(*) as cnt FROM `my_documents_files` a, sma_document_type b , sma_party_mst c, dms_inward d 
									where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
									and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_id' "; //  limit 0,5 
							$res = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$cn1 = mysqli_fetch_array($res);
							$cnt = $cn1['cnt'];
						//$cnt=1;	
							if($cnt>0){
						?>  
    						
							<div class="col-sm-7" >&nbsp;</div>
							<div class="col-sm-5" >		
								<span style="font-size:18px;color:white;" class="btn btn-info" >Select Document from DMS </span>&nbsp;&nbsp;
								<span > &nbsp;&nbsp;</span>
								<input type ="checkbox" id="partyDoc" name="partydoc" value='Y' onclick="getpartydoc(this.value)" >
							</div>	
								<input type ="hidden" id="party_id_doc" name="party_id_doc" value="<?php echo $party_id_doc; ?>" >
								<input type ="hidden" id="company_idd_doc" name="company_idd_doc" value="<?php echo $company_id; ?>" >
						<?php } ?>
								
								
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PO' AND reference_id = " . $po_id;
//						echo $sql;
						
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                           <th width="20%" >Document Type</th>
                                          <th  width="30%" >Description</th>
										  <th  width="40%">Share Point Link
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  </th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													$doc_desc 		= $docRow['doc_desc'];
													$doc_type 		= $docRow['doc_type'];
													$share_point_link = $docRow['share_point_link'];
													$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$rw1 = mysqli_fetch_array($rs);
													$document = $rw1['document'];
											
											  ?>
                                          <tr>
										      <td><?php echo $document; ?></td>
											  <td><?php echo $doc_desc; ?></td>
                                              <td><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  
											<!--  <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>-->
                                              
                                          <?php if (empty($readonly)){ ?>
													
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
										  <?php } ?>	  
                                          
										  </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  
						<span id="gegpartyDoc">
							
						</span>
								  
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control  doctype" name="doctype[]" <?= $readonly;?>  >
                                            <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
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
										<td width="40%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
									<!--	<td>
											<input type="file" name="fudoc[]" class="docfile">
										</td>-->
                                        <td>
										 
											<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
										
										</td> 
										
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
							
								$_SESSION['po_id'] 	= $po_id;
								$_SESSION['status']  = $status;
							
							?>
							
					<span id="predit"></span>

							<?php if($del !='Y'){ ?>
									
								<div class="box-footer">
									<div class="col-sm-6">
										<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&po_id='.$id ; ?>
													
									<?php 	
										if($del=='Y'){
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
									<?php
										}
										else if ( $approval_status=='Rejected' ){	
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
									<?php	
										}
										else if ( $status=='Completed' ){	
									?>
											<a href="#makeSuspend" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeSuspend">Suspend</a>
											
											<a href="#makeAmend" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeAmend">Amend</a>
									<?php	
										}	
									?>
									<span>&nbsp;&nbsp;</span>
									<?php
									if ($status == 'Draft' ){
								?>	
										<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>		
								<?php	
									}
								?>
								
								</div>
								
								<div class="col-sm-6 text-right">
								<?php
									$role			= $_SESSION['role'];
									$userid   	= $_SESSION['usrid'];
									
								$approver_flag='';
								if( $status != 'Draft' ){
									
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
										&& $approver_8_status=='Submitted'){
										$approver_flag='Y';
									}
									
										/* $mode_status = 'Pending';
										if($userid==$approver_1 && empty($approver_2) && empty($approver_3)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_2 && empty($approver_3)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_3 && empty($approver_4)){
											$mode_status = 'Approve';
										}
										else if($userid==$approver_4){
											$mode_status = 'Approve';
										} */
										
									}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								}
							?>
									
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<?php	
									if($status=='Draft'){
									?>	
										<!--<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>-->
										<?php if( $items_cnt > 0 && $approval_status != 'Rejected' ){ ?>
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
										<?php } ?>
									
										
								<?php } ?>

								<?php if( $approval_status != 'Rejected' ){ ?>
								<span class='hidesend' >
									<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
								</span>	
								<?php } ?>
								
								<?php $baseurl1 = $baseurl.$modulePath.'index.php?sub=list' ?>
								
								<span>&nbsp;&nbsp;</span>
								
								<a href="<?php echo $baseurl1 ?>" class="btn btn-default" >Back</a>
									
								</div>
							</div>	

					<?php } ?>
					
					<?php  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
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
					?>		
							</div>
					<?php		
						}
								
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
								<div class="box-footer">
								
							<?php	
								/*$approver_1 = $row['approver_1'];
								$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3']; */
						
								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
									<select class="form-control  approver_2" name="approver_2" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
									<select class="form-control  approver_3" name="approver_3" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 
								if(!empty($approver_4)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
									<select class="form-control  approver_4" name="approver_4" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } ?>	
							<?php if(!empty($approver_5)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
									<select class="form-control  approver_5" name="approver_5" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_6)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
									<select class="form-control  approver_6" name="approver_6" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_7)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
									<select class="form-control  approver_7" name="approver_7" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_8)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
									<select class="form-control  approver_8" name="approver_8" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							
								
								<BR>
								
							</div>
						
						</span>
				<?php } ?>		
							
			</div>
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $po_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id desc  ";
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
						
						
						<div class="tab-pane" id="tab_5">
							
							<div class="modal-header" >
								<?php //echo $baseurl . $modulePath . "copypo.php"
									$po_rev		= $po_rev+1;
									$po_number 	= $po_number;
									$old_po_no 	= $old_po_no;
									
									$company		 = $_SESSION['project'];
									
					 
					?>

				<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					
					<th style="text-align:right;">Total</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
					
<!--					<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>

			<?php
				$sql="Select * from sma_purchase_order where old_po_no = '$po_id' and po_amend != 'Y' order by po_rev desc ";
				$result = mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				while($row = mysqli_fetch_array($result)){
				
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
						
						$po_rev = $row['po_rev'];
						$po_number = $row['po_number'];
						if($po_rev>0){
							$po_number .= $po_rev;
						}
						
						$po_amend = $row['po_amend'];
						$backcolor = '';
						if($po_amend=='Y'){
							$backcolor = ' background-color: coral; ';
						}
						
					?>
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
						<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

						<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
						<td width="20%"><?php echo $po_number;?></td>
						<td width="08%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
						<td width="19%"><?php echo $to_supplier;?></td>
						
						<td width="10%" style="text-align:right;"><?php echo $tot_amount;?></td>
						<td width="10%"><?php echo $row['changed_by'];?></td>
						<td width="10%"><?php echo $row['status'];?></td>
						<td width="10%"><?php echo $row['approval_status'];?></td>
						
						</tr>
						</a>
						<?php } ?>
								
								
								</tbody>
								<tfoot>
								
								</tfoot>
							  </table>

							</div>
						
						</div>
						


<!--Comment Section Start-->				
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$po_id' and doc_type = 'PO' order by id desc ";
				//echo $s1;
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$comment_datetime		= $r1['comment_datetime'];
				$comment_type			= $r1['comment_type'];
				$comment				= $r1['comment'];
				$parent_comment_id		= $r1['parent_comment_id'];
				$comment_by				= $r1['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($r1['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$create_by = $rw['username'];
					
				if(!empty($create_by)){	
					$tmp_var = "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$comment_datetime; 
				}
				?>			
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $po_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $po_id;?>,'PO','C',<?= $page;?>)" >Submit</button>		
						</div>
					</div>

			<span id="getcomment">	
			<?php
				$res  = mysqli_query($con, $s1);
				while($r1 = mysqli_fetch_array($res)){
					$comment_datetime		= $r1['comment_datetime'];
					$comment_type			= $r1['comment_type'];
					$comment				= $r1['comment'];
					$parent_comment_id		= $r1['parent_comment_id'];
					$comment_by				= $r1['comment_by'];
					$comment_datetime	    = date('d-m-Y h:i:s a', strtotime($r1['comment_datetime']));
					if($comment_datetime=='01-01-1970'){
						$comment_datetime='';
					}
					$sl="SELECT * FROM sma_user where id = '$comment_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$create_by = $rw['username'];
			?>
					<div class="col-md-12">
					
						<label class="control-label">On <?php echo $comment_datetime ?> <?php echo $create_by ;?> : wrote</label><br>
						<?= $comment; ?>
					<!--	<textarea style="background-color:#F5F5F5;" readonly rows='02' cols="150" ><?= $comment; ?></textarea> -->
					</div>
			<?php	
				}
			?>	
			</span>
			
			</div>
					</section>
					
				</div>
				
			 </div>
						
		</div>
<!--Comment Section End-->				
						
				</div>
			</div>	
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
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
      
<!-- Modal Add Quotaion-->
<div class="modal fade" id="modalAddVendor<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddVendorLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddVendorLabel">Add - Vendor Comparison & Supplier to Vendor </h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem_po.php?sub=Save" method="POST">

<!--							<input type="text" id="data_mode" value=<?php echo $data_mode; ?> > -->

							<input type="hidden" name="po_approval_hdr_id" value=<?php echo $po_id; ?>>
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2-123" name="supplier_name" onchange="getpangst(this.value)">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['supplier_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
							
                            <div class="form-group col-md-12">
								<span id="getpangst">
									
								</span>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-3 control-label">Quote Ref.No</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="quote_ref_no" placeholder=" Enter QUOTE REF. Number">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">Vendor Selected.</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="vendor_selected" value="Y">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemvaluess" class="col-sm-3 control-label">Values</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control"  style="text-align:right;" name="values">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-3 control-label">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea rows="3" class="form-control"  name="remarks"></textarea>
                                </div>
                            </div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id='saveForm' >Save changes</button>
							</div>
                        </form>
                    </div>
                </section>
            </div>

            </div>
        </div>
    </div>
</div>

	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="clearfld()" >&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Product to Order </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">
							
							<input type="hidden" name="app_remo_ref" id="app_remo_ref" value="<?php echo $approval_memo_ref ?>" >
						
						<?php	
							
							$sql = " SELECT SUM(`values`) AS `values` FROM `sma_approval_details` where approval_hdr_id = '$approval_memo_ref' and vendor_selected = 'Y' ";
						//echo $sql;	
							$rs1 = mysqli_query($con, $sql);
                            echo mysqli_error($con);
							$rw1 = mysqli_fetch_array($rs1);
							$values = $rw1['values'];
							
							if( empty($tot_amount) || $tot_amount ==0 ){ $tot_amount =0 ; }
						?>
						
							<input type="hidden" name="values" id="Values" value="<?php echo $values ?>" >
							<input type="hidden" name="tot_amount" id="TOT_amount" value="<?php echo $tot_amount ?>" >
							
							<input type="hidden" name="comp_vertical" id="comp_Vertical" value="<?php echo $comp_vertical ?>" > 
							
							<div class="form-group">
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
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
									<span id="getmaterial1" ><span id="getgrnitem" >
										<select class="form-control" id="itemName" required >
											<option value="">Select</option>	
										
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
							    
									<?php
										$sql = " SELECT * FROM sma_product where 1  ORDER BY name ASC ";
										$q2  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_object($q2)){
											$name = $r2->name;
											$id = $r2->id;
										}
									?>		
										
								<div class="col-sm-6">
									<label for="itemDescription" class="control-label col-sm-2">Description</label>
									<span id = "getdesc">	
										<textarea rows='01' class="form-control" id="itemDescription" placeholder="Item Description..."></textarea>
									</span>
								</div>
								
                            </div>
                            
						<span id="getcatbudget" >
							<div class="form-group">
                               <div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group</label>
								</div>
								
								<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Name</label>
								</div>								
                            </div>
							
							<div class="form-group">	
								<div class="col-sm-12">
									
								</div>
							</div>
						</span>		
							
						<!--<div class="well well-sm" > -->
							
							<?php
							
								$b_readonly = '';
						
							?>
							
							 <div class="form-group">
								<div class="col-sm-2">
									<label for="itemGST" class="control-label ">GST Type</label>
								<select class="form-control" name="itemgst_id" id="itemGST_id" onchange="getgst(this.value)" >
									<option value=""> Select </option>
								<?php 
									$sql = "select * from gst_mst where 1 order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
								?>
									<option value="<?php echo $r22['id'].'-'.$r22['igst']; ?>" ><?php echo $r22['gst_name'];?></option>
									<?php } ?>
								</select>
								</div>
								
								<div class="col-sm-2">
									<label for="itemGST" class="control-label col-sm-1">GST%</label>
									<input type="text" class="form-control" id="itemGST" name="itemgst" style="text-align:right;" readonly onkeyup="calculateTotalAmount();" value=''>
									
									<input type="hidden" name="itemgst_id" id="itemGST_ID" >
																	
								</div>
								
								<div class="col-sm-2">
									<label for="itemQuantity" class="control-label col-sm-1">Qty.</label>
                                	<input type="text" class="form-control" id="itemQuantity" autocomplete='off' style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label col-sm-1">Units</label>
									<span id="getunit2">
										<input type="text" class="form-control" id="itemUnits" name='itemunits' readonly >
									</span>

                                </div>
								
								<div class="col-sm-2">
									<label for="itemRate" class="control-label ">Rate</label>
                                	<div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate" autocomplete='off' style="text-align:right;"  onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>

								<div class="col-sm-2">
									<label for="itemAmount" class="control-label ">Total</label>
                                	<input type="text" class="form-control" id="itemAmount_display" style="text-align:right;" readonly>
									
									<input type="hidden" class="form-control" id="itemAmount" >
									
                                </div>
								
							</div>
							
							<div class="form-group">
				           
								<label class="control-label col-sm-2">Delivery Date </label>
								<div class="col-sm-3">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" autocomplete='off' id="deliveryDATE" >
									</div>
								</div>
								
								<div class="col-sm-6">
									<span id="errormsg" style="color:red;" ></span>
								</div>
								
							</div>
							
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
                <button type="button" class="btn btn-primary" id="addItem">Save</button>
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
										
							$po_id 		= $_SESSION['po_id'];
							//$status 	= $status;
							$role		= $_SESSION['role']; //Maker
							
							?>	
										
							<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
							<input type="hidden" id="modeE" name="mode" value='Approve' >
							<input type="hidden" id="po_idE" name="po_id" value="<?= $po_id; ?>" >
										
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
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype col-sm-1" name="doctype[]" ><option value="">Select</option>'+opt+'</select></td><td width="30%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="40%">	<textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
	
<!--Make to DraftPopup-->
<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
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
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="po_id" id="po_idD" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Make to Suspend-->
<div class="modal fade" id="makeSuspend" role="dialog" aria-labelledby="makeSuspend">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeSuspend">Do you want to Suspend? </h4>
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
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="po_id" id="po_idS" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Accept'>
										<input type="hidden" id="approverS" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusS" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
                                    </div>
								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitSuspend">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>
<!--Make to Suspend-->


<!--Make to Amend-->
<div class="modal fade" id="makeAmend" role="dialog" aria-labelledby="makeAmend">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeAmend">Do you want to Amend? </h4>
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
											$company= $_SESSION['company'];
										?>
										<input type="hidden" name="po_id" id="po_idN" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeN" name="mode" value='Accept'>
										<input type="hidden" id="approverN" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusN" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksN"></textarea>
											</div>
										</div>
                                    </div>
								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitAmend">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>
<!--Make to Amend-->

<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
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
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="po_id" id="po_idZ" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->

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
											
											$user_category = $_SESSION['user_category'];
											
											if($user_category=='S'){
												if ($checker_value <= 100000){
													$user_category = 'S';
													$account_role = " 'Project Manager' " ;
													
													if($company=='9'){
														$user_category = 'H';
														$account_role = " 'Project Incharge' ";
													}
													
												}
												else {
													$user_category = 'H';
													$account_role = " 'Checker' " ;
												}
											}
											else {
												$user_category = 'H';
												$account_role = " 'Checker' " ;
											}
//echo $sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and user_category = '$user_category' and role in ( select id from sma_role where role in ($account_role) ) and active = '1'  ORDER BY first_name ASC";											
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
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and user_category = '$user_category' and role in ( select id from sma_role where role in ($account_role) ) and active = '1'  ORDER BY first_name ASC";
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


<!--Blocked -->

<div class="modal fade" id="blockAuthority" role="dialog" aria-labelledby="blockAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="blockAuthority">Want to Block?</h4>
				<p>Remaining amount will be added back to respective budget head account once blocked.</p>
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
										
										<input type="hidden" name="po_id" id="po_idB" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeB" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusB" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksB"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitBlock">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!-- Blocked -->
	
										
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

   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var po_id		 	=  $("#po_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + po_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	$("#submitSuspend").on("click", function(e){
        var mode		 	=  $("#modeS").val();
		var po_id		 	=  $("#po_idS").val();
        var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();
		
//alert(remarks +  ' ' + po_id + ' ' + status);

		$('#makeSuspend').modal('hide');
		var strURL = "py_suspend_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	$("#submitAmend").on("click", function(e){
        var mode		 	=  $("#modeN").val();
		var po_id		 	=  $("#po_idN").val();
        var status 			=  $("#statusN").val();
		var remarks			=  $("#remarksN").val();
//alert(remarks +  ' ' + po_id + ' ' + status);

		$('#makeAmend').modal('hide');
		var strURL = "py_amend_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});
	
   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var po_id		 	=  $("#po_idE").val();
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
		
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
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
		var approver		=  $("#approverC").val();
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
						approver:approver,
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
		
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
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


	$("#submitBlock").on("click", function(e){
        var sub = 'sub13';
		var mode		 	=  $("#modeB").val();
		
		var po_id		 	=  $("#po_idB").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusB").val();
		var remarks			=  $("#remarksB").val();

//		alert(po_id+  ' ' + sub + ' ' + mode + ' ' + status );		 
		
		$('#blockAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub13:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		 $('#blockAuthority').modal('hide');
				 
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
		var qty1 	= parseInt(document.getElementById("itemQuantity_e").value);
		var rate1 	= parseInt(document.getElementById("itemRate_e").value);
		var gst1 	= parseInt(document.getElementById("itemGST_e").value);
		var balance_budget = parseInt(document.getElementById("balance_budget_ab").value);
//alert(qty1 + ' <> ' + rate1 + ' <> ' +  amt1);
        var amt1 = qty1 * rate1;
		var amt1 = amt1 + (amt1 * gst1 /100);
		
        amt = parseFloat(amt1);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount_e').val(amt1);
//alert(amt1 + '>' + balance_budget);		
		if(amt1 > balance_budget){
			 alert("Budget goes into negative balance ... Please confirm...");
		}
		
    }

	
    function calculateTotalAmount() {
        var qty = $('#itemQuantity').val();
        var rate = $('#itemRate').val();
		var gst = $('#itemGST').val();
        var amt = qty * rate;
		var amt = amt + (amt * gst /100);
        amt = parseFloat(amt);
		amta = parseFloat(amt);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount').val(amta);
		$('#itemAmount_display').val(amt);
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

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var po_id		 	=  $("#po_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + po_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});


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
		var budget_id  		= '';
		var purchase_id  =  $("#purchaseId").val();		
        var product_id   =  $("#itemName option:selected").val();
        var product_name =  $("#itemName option:selected").html();
		
		var app_remo_ref	= $("#app_remo_ref").val();
		var company_id    	=  $("#company_id_a").val();
		var budget_name   	=  $("#budget_Name").val();
		var budget_head   	=  $("#budget_name_a").val();
		var total_budget  	=  $("#total_budget").val();
		var balance_budget  =  $("#balance_budget_a").val();
		var budget_id  		=  $("#budget_id").val();
        var description 	=  $("#itemDescription").val();
		
		var budget_NAME 	= $("#budget_NAME").val();
		
		var quantity 		= parseInt($("#itemQuantity").val());
        var units 			= $("#itemUnits").val();
        var rate 			= $("#itemRate").val();
		var gst  			= $("#itemGST").val();
		var gst_id 			= $("#itemGST_ID").val();
        var amount 			= parseInt($("#itemAmount").val());
		var deliverydate 	= $("#deliveryDATE").val();
		
		var row_affected  =  parseInt($("#row_affected_a").val());
		if(row_affected==0){
			alert('Budget not available for product !!!');
			return false;
		}
		
		var selected_vendor_value	= parseInt($("#selected_vendor_value").val());
		var checker_value   		= parseInt(document.getElementById("checker_value").value);
		
		var checker_value	= parseInt(checker_value) + parseInt(amount);
//alert(budget_NAME + ' <#> ' + quantity + ' <<>> ' + rate + ' <<>> ' + product_id);
//alert(checker_value + ' > ' +selected_vendor_value + ' ' + amount);        
		if(checker_value>selected_vendor_value){
			alert('Product amount should not greater then selected vendor value.....');
			return false;
		}
	
		if(product_id==''){
			alert('Product must be select.....');
			return false;
		}
		
		if(budget_NAME==''){
			alert('Cost Center must be select.....');
			return false;
		}
		
		if(quantity==0 || rate==0){
			
			alert('Quantity / Rate must be enter.....');
			return false;
		}

//alert(balance_budget + ' <> ' + amount + ' <> ' + budget_name + ' <> ' + company_id + ' <> ' + budget_id );
//return false;
        
		if( parseInt(amount) > parseInt(balance_budget) ){
			alert('Insufficient Budget for Product Name '+ product_name);
			var msg = 'Insufficient Budget for Product Name: '+ product_name;
			$('#errormsg').html(msg);
			return false;
		}
//return false;
//alert(deliverydate);

		var app_values =  parseInt($("#Values").val());
		var tot_amount =  parseInt($("#TOT_amount").val());
		var check_value = parseInt(parseInt(rate) * parseInt(quantity)) + parseInt((((parseInt(rate) ) * parseInt(quantity) ) * gst) / 100);
//alert('Check Value##0: ' + check_value);		
//		var check_value = parseInt(check_value) + parseInt(tot_amount);

        $('#modalAddItem').modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ product_id:product_id,purchase_id:purchase_id,
							product_name:product_name,
							description:description,
							company_id:company_id,
							budget_name:budget_name,
							budget_head:budget_head,
							total_budget:total_budget,
							balance_budget:balance_budget,
							budget_id:budget_id,
							quantity:quantity,
							units:units,
							rate:rate,
							gst:gst,
							gst_id:gst_id,
							amount:amount,
							deliverydate:deliverydate,
							sub1:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });

//Disable click outside of bootstrap modal area to close modal 
$('#modalAddItem123').modal({backdrop123: 'static', keyboard123: false}) 
//Disable click outside of bootstrap modal area to close modal 	

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


	function delete_appquote(po_approval_hdr_id, id){
		var sub = 'sub4a';
        var po_approval_hdr_id = po_approval_hdr_id;
		var id	 = id;
//alert(po_approval_hdr_id + ' ' + id);
		$('#modalDeleteItem'+po_approval_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ po_approval_hdr_id:po_approval_hdr_id,id:id,sub4a:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
	
			});
		
		window.location.href='edit.php?sub=edit&id='+po_approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}


</script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });


<?php if ($po_type=='A'){ ?>
						
	$(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
//        CKEDITOR.replace('reason');
//        CKEDITOR.replace('reason1');
//        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });
<?php } ?>

<?php if ($po_type=='C'){ ?>
						
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
<?php } ?>
	
	function getcatbudget (id){
		var sub    			= 'sub14';
		var strURL 			= "app_func.php";
		var company_id    	= document.getElementById("projecT").value;
		var product_id    	= document.getElementById("itemName").value;
		var app_remo_ref	= document.getElementById("app_remo_ref").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,app_remo_ref:app_remo_ref,sub14:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}
	
	function getcatbudgett(id){
		var sub    = 'sub14A';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("projecT").value;
		var product_id    = document.getElementById("itemName").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14A:sub},function(result){
		      $('.getcatbudgett').html(result);
		});

	}
	
	function getcostcenter(id){
		
        var sub    = 'sub1';
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcenterr(id){
		
        var sub    = 'sub1A';
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + company_id  );

		 var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1A:sub},function(result){
		      $('.getcostcenterr').html(result);
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
		     // $('#getunit2').html(result);
			
			  var splitString = result.split("##");
		
				var uom 			=  splitString['0'];
				var account_name 	= splitString['1'];
			//alert(account_name);	
			  $("#itemUnits").val(uom);
			  //$("#posting_ACCOUNT_A").val(account_name);
			  
		});
	}

	function getunit3(id){	
        var sub    = 'sub4';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getunit3').html(result);
		});
	}
	
	function getunit1(id){	
        var sub    = 'sub44';
		var comp_vertical    = document.getElementById("comp_Vertical").value;
		var company_id       = document.getElementById("projecT").value;
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,comp_vertical:comp_vertical,sub44:sub},function(result){
		      //$('#getunit1').html(result);
			  
			  //alert(result);
			  
			 //var input = 'john smith~123 Street~Apt 4~New York~NY~12345';

			var fields = result.split('-');

			var unit = fields[0];
			var description = fields[1];
			var igst	= fields[2];
			var igst_id	= fields[3];
			
//alert(unit+ ' ' + description);			
			$('#itemUnits').val(unit);
			$('#itemDescription').val(description);
			$('#itemGST').val(igst);
			//$('#itemGST_ID').val(igst_id);

// etc.

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
		
		
	function delete_poItem(po_no, id_no){

		
		//alert("Delete PO Item");
		//alert(po_no + ' ' + id_no);
		var strURL = "del_poitem.php";
		$.post(strURL,{po_no:po_no,id_no:id_no},function(result){
		      $('#delete_poItem').html(result);
		});
		
	}
	

	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;
			
			
		//alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}
	
	function getapprover(){
		
		var company_id    	= document.getElementById("projecT").value;
		var checker_value   = document.getElementById("checker_value").value;
		var trans_type    	= document.getElementById("trans_type").value;
		var po_type  	   	= document.getElementById("po_typea").value;

		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + po_type + ' ' + trans_type + ' ' + company_id + ' ' + checker_value);	
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,trans_type:trans_type,po_type:po_type,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
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
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub26:sub},function(result){
		      $('#getspecialterms').html(result);
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
	
	function getworkflowtype(id){
		
        var sub    = 'sub27';
//	alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub27:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
	
	function getworkflowtype(id){
		
        var sub    = 'sub27';
//	alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub27:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
	
	function getmaterial1(id){
		
        var sub    = 'sub3A';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3A:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}

	function clearfld(){
		
		$('#itemDescription').html('');
		$('#itemQuantity').html('');
		$('#itemUnits').html('');
		$('#itemRate').html('');
		$('#itemGST').html('');
		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}
	
	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}
		else if(row_affected==2){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==5){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==6){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==7){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if(row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
			else if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		
		
		
		return false;
		
	}
	
	function getvalidate(){
		
		var project 	=  $("#projecT").val();
		var location 	=  $("#location").val();
		var department 	=  $("#department").val();
		//var quotation_reference_no 	=  $("#quotation_reference_no").val();
		var to_supplier 	=  $("#to_Supplier").val();
		
		
		
		if(project==''){
			alert('Company selection mandatory !!!');
			return;
		}
		
		if(location==''){
			alert('Location selection mandatory !!!');
			return;
		}
		if(department==''){
			alert('Department selection mandatory !!!');
			return;
		}
		
		if(to_supplier==''){
			alert('Supplier selection mandatory !!!');
			return;
		}
		
	}	
	
	
	function getgst(id){

//alert(id);		
		var splitString = id.split("-");
		
		var gst_id =  splitString['0'];
		var gst_perc = splitString['1'];
		
//alert(gst_id + ' ' + gst_perc);			
		$('#itemGST_ID').val(gst_id);
		$('#itemGST').val(gst_perc);
			  
	}
	
	
	function getgst1(id){

//alert(id);		
		var splitString = id.split("-");
		
		var gst_id =  splitString['0'];
		var gst_perc = splitString['1'];
		
//alert(gst_id + ' ' + gst_perc);			
		$('.itemGST_id1').val(gst_id);
		$('.itemGST_e').val(gst_perc);
			  
	}
	
	function getcomment(comment,po_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,po_id:po_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
		
</script>

</body>
</html>
