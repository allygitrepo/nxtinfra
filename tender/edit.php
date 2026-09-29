<style>

.megamenu {
    width: 3000px;
    overflow: hidden;
     max-height:500px;
    -webkit-box-shadow: 0px -1px 12px rgba(0, 0, 0, 0.48);
    -moz-box-shadow:    0px -1px 12px rgba(0, 0, 0, 0.48);
    box-shadow:         0px -1px 12px rgba(0, 0, 0, 0.48);
    padding-bottom:45px;
    padding-top:5px;
    background: grey;
    border-top:1px solid #767676;
    font-family:Verdana, Tahoma, Sans-Serif;
    z-index: 100;
}

</style>
<?php
include("../header.php");

date_default_timezone_set('Asia/Kolkata');

$modulePath = "tender/"; 

$_SESSION['reset'] = '1';
$userid   	= $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
?>

<?php

if($_GET['sub']=='delete'){
	$tender_id	= $_GET['tender_id'];

	$sql="update sma_tender_header set del = 'Y' where id = '$tender_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

if($_GET['sub']=='Selected'){
	
		$tender_hdr_id    			= $_POST['tender_id_v'];
		$selected_vendor     		= $_POST['sel_supplier_id'];
		$selected_amount     		= $_POST['selected_amount'];
		$selected_remarks			= $_POST['selected_remarks'];
		
		$terms_conditions     		= $_POST['terms_conditions'];
		
		$sql = " UPDATE sma_tender_supplier_quote SET selected_vendor = 'Y', 
						selected_amount = '$selected_amount',
						selected_remarks= '$selected_remarks',
						final_status	= 'Final'
				WHERE supplier_id ='$selected_vendor' 
				AND tender_hdr_id = '$tender_hdr_id' ";
		mysqli_query($con, $sql);
		
		$sql = " UPDATE sma_tender_header SET final_status	= 'Final' WHERE id = '$tender_hdr_id' ";
		mysqli_query($con, $sql);
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$tender_hdr_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}

if($_POST['editterms']){
	
		$tender_hdr_id     			= $_POST['tender_hdr_id'];
		$terms_srno     			= $_POST['terms_srno'];
		
		$terms_conditions     		= $_POST['terms_cond'];
		
		$sql = " UPDATE sma_tender_terms SET terms_conditions = '$terms_conditions' 
				WHERE id ='$terms_srno' 
				AND tender_hdr_id = '$tender_hdr_id' ";
				
		mysqli_query($con, $sql);
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$tender_hdr_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}

if($_POST['editvendor']){
	
		$tender_hdr_id     	= $_POST['tender_hdr_id'];
		$approval_srno     			= $_POST['approval_srno'];
		
		$vendor_selected     		= $_POST['vendor_selected'];
		
		$quote_ref_no     			= $_POST['quote_ref_no'];
		$supplier_name	     		= $_POST['supplier_name'];
		$values			     		= $_POST['values'];
		$remarks		     		= $_POST['remarks'];
		
		$sql = "select * from sma_party_mst where id = '$supplier_name' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$email_id  = $r2['party_email'];
		$sql = " UPDATE sma_tender_supplier SET vendor_selected = '$vendor_selected' , email_id = '$email_id',
								supplier_id	     	= '$supplier_name',
								remarks		     		= '$remarks'
				WHERE id ='$approval_srno' 
				AND tender_hdr_id = '$tender_hdr_id' ";
//echo $sql. "<BR>";
//exit();				
		mysqli_query($con, $sql);
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$tender_hdr_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
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
		$po_value 			= $_POST['po_value'] + $amount;
		$po_quantity 		= $_POST['po_quantity'] + $quantity ;
		$approval_memo_ref 	= $_POST['approval_memo_ref'];
//echo $po_quantity .' > '. $ap_quantity .' || ' . $po_value .' > ' . $ap_value;		
		if($po_quantity  > $ap_quantity || $po_value > $ap_value ){
			$errmsg = 'Quantity / Value should not be overflow for Approved quantity / value...';
			echo $errmsg;
			$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
			echo "<meta http-equiv='refresh' content='0'>";    
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}	
		
		$sql = " select * from sma_tender_header where id = '$purchase_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		
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
		$sql = "update `sma_po_items` set product_id = '$product_id', 
					product_name	= '$product_name', 			
					product_desc	= '$description', 
					quantity		= quantity + '$quantity' - '$quantity_prev', 
					uom				= '$units',
					unit_rate		= '$rate', 
					gst				= '$gst',
					gst_id			= '$gst_id',
					budget_head		= '$budget_head',
					budget_name		= '$budget_name',
					budget_id 		= '$budget_id',
					delivery_date	= '$deliverydate'
				where purchase_id = '$purchase_id' and id = '$rid' ";				
//echo $sql."<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
	
		$sql = "UPDATE sma_approval_items SET po_quantity = po_quantity + '$quantity' - '$quantity_prev', 
					po_value= po_value+ $amount - $amount_prev 
					WHERE approval_hdr_id = '$approval_memo_ref' and product_id = '$product_id'";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		$sql = "select sum((quantity * unit_rate) + (((quantity * unit_rate) * gst) /100)) as total_po_amount from sma_po_items where purchase_id = '$purchase_id' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$total_po_amount 		= $r22['total_po_amount'];
		
		$sql = " UPDATE sma_tender_header set total_po_amount = '$total_po_amount' where id = '$purchase_id' "; 
		mysqli_query($con, $sql);
			
	//exit('TESTING EXIT...');			
	$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888';
	//echo $baseurl1;
	//exit();
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";

	}
	
	
	if($_GET['sub']=='Save'){
			$id					= $_POST['id']; 
			$tender_id			= $_POST['tender_id']; 
			$tender_title		= $_POST['tender_title'];
			$company_id			= $_POST['company_id'];
			$department			= $_POST['department'];
			$created_date		= date('Y-m-d', strtotime($_POST['created_date']));
			$visible			= $_POST['visible'];
			$price_visible 		= $_POST['price_visible'];
			//$deadline_date		= date('Y-m-d', strtotime($_POST['deadline_date']));
			//$deadline_time		= $_POST['deadline_time'];
			
			//$deadline_date		.= ' ' . $deadline_time;
			//$deadline_date		= date('Y-m-d h:i a', strtotime($deadline_date));
			
			$deadline_date		= date('Y-m-d', strtotime($_POST['deadline_date']));
			$deadline_time		= $_POST['deadline_time'];
			$deadline_date		.= ' ' .$deadline_time;
			
//date("h.i A", $timestamp);
			$payment_within_days= $_POST['payment_within_days'];
			$retention			= $_POST['retention'];
			$remarks			= $_POST['remarks'];
			$background			= $_POST['background'];
			$scope_of_work		= $_POST['scope_of_work'];
			$location			= $_POST['location_v'];
			$trans_type			= $_POST['trans_type'];
			$status				= $_POST['status'];
			$delivery_address	= $_POST['delivery_address'];
			
  			$sql="update sma_tender_header set company_id = '$company_id',
					department			= '$department',
					tender_title		= '$tender_title',
					created_date		= '$created_date',
					visible				= '$visible',
					price_visible 		= '$price_visible',
					deadline_date		= '$deadline_date',
					deadline_time		= '$deadline_time',
					payment_within_days	= '$payment_within_days',
					retention			= '$retention',
					remarks				= '$remarks',
					background			= '$background',
					scope_of_work		= '$scope_of_work',
					trans_type			= '$trans_type',
					location			= '$location',
					delivery_address	= '$delivery_address'
				where id='$tender_id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $sql ."<BR>";
//exit();			
			// add attachments
			// file upload
			
			$fyr		= date('Y', strtotime($created_date));
			$fmth		= date('m', strtotime($created_date));
			$fin_year	= '';
			if($fmth>=1 && $fmth<=3){
				$styr = $fyr - 1;
				$fin_year = $styr . '-'. $fyr;
			}
			else {
				$ltyr = $fyr + 1;
				$fin_year = $fyr . '-'. $ltyr;
			}
			
//Term & Conditions	Start
			$terms_conditions	= $_POST["terms_conditions"];
			for( $i = 0; $i < sizeof($terms_conditions); $i++ ) {
				
				$terms_cond 	= $terms_conditions[$i];
				
				if( !empty($terms_cond) ){
					$sql="INSERT INTO sma_tender_terms (tender_hdr_id, terms_conditions) 
					VALUES( '$tender_id', '$terms_cond')";
					mysqli_query($con, $sql);
				}

			}

//attachment			
			$flpath 		= $tender_id.'_'.$fin_year;
			$arrexceldoc 	= $_FILES["exceldoc"];
			$folder_path 	= "uploads/" . $flpath;
			$filenamee 		= $arrexceldoc['name'];
			$tmpFileNamee 	= $arrexceldoc['tmp_name'];

			if(!empty($filenamee)){
				$sql="update sma_tender_header set file_name = '$filenamee',
								file_path = '$folder_path'
							where id='$tender_id'";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				move_uploaded_file($tmpFileNamee, "uploads/" . $flpath . "/" . $filenamee);	
			}
			
//Term & Conditions	End
			
			$flpath 		= $tender_id.'_'.$fin_year;
			$arrDocDesc 	= $_POST["docdesc"];
			$docs_type	 	= $_POST["docs_type"];
			$arrFUDoc 		= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocDesc); $i++) {
				$folder_path = "uploads/" . $flpath;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename 	= $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO sma_tender_file_upload ( file_name, file_path,  doc_desc,  tender_hdr_id, date_uploaded, doc_type) VALUES( '$filename', '$folder_path', '$arrDocDesc[$i]', '$tender_id', now(), '$docs_type[$i]' )";
				//echo $sql. "<BR>";
				
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, "uploads/" . $flpath . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
			
			
			$flpath 		= $tender_id.'_'.$fin_year;
			$arrDocDesc 	= $_POST["docdesc_o"];
			$docs_type	 	= $_POST["docs_type_o"];
			$arrFUDoc 		= $_FILES["fudoc_o"];
//print_r($arrFUDoc);
			for($i = 0; $i < sizeof($arrDocDesc); $i++) {
				$folder_path = "uploads/" . $flpath;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename 	 = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads ( module, file_name, file_path,  doc_desc,  reference_id, date_uploaded, doc_type) VALUES( 'TN', '$filename', '$folder_path', '$arrDocDesc[$i]', '$tender_id', now(), '$docs_type[$i]' )";
				//echo $sql. "<BR>";
				
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, "uploads/" . $flpath . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
			
//exit();
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
		
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_tender_header set current_approver = '$approver_1',
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
					where id='$tender_id'";	
				mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'TN', '$tender_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$modulePath = "tender/"; 
				
				$subject = $tender_title;
				$sql="select * from sma_user where id='$approver_1' and active='1' ";	
			//echo $sql."<BR>";		
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
		 		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$tender_id;
		
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 8px 12px;text-align: center;font-weight: 400;" >Tender Click Here </a>';
		
				$msg = 'Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");

				include "tn_mail.php";
				
			}
//exit();		
			$Save_hdr = $_POST['Save_hdr'];
			if(!empty($Save_hdr)){
				$baseurl.=$modulePath. 'edit.php?sub=edit&id='.$tender_id;
				echo "<script>window.location.href='$baseurl';</script>";
				
			}
			else {
			
				$baseurl.=$modulePath;
				echo "<script>window.location.href='$baseurl';</script>";
			}
			
			exit();

	}

		$id = $_GET['id'];
		$tender_id = $_GET['id'];
		$sql="Select * from sma_tender_header where id ='$tender_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

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
										
		$approval_status 	= $row['approval_status'];
		
		$final_status 		= $row['final_status'];
		
		$approval_statusA 	= $row['approval_status'];
		$statusA 			= $row['status'];
		$draft_by			= $row['draft_by'];
		
		
		$file_name			= $row['file_name'];
		$file_path			= $row['file_path'];
		
		$deadline_date 	= $row['deadline_date'];
		$deadline_time 	= $row['deadline_time'];
		
		$created_date  	= date('d-m-Y', strtotime($row['created_date']));
		$fyr		= date('Y', strtotime($created_date));
		$fmth		= date('m', strtotime($created_date));
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}	
		$_SESSION['finance_year'] = $fin_year;
			
	
		$status = $row['status'];
		$del 	= $row['del'];
	
		$dated  	= date('d-m-Y', strtotime($row['dated']));
		
		$readonly = "";
		if($statusA!='Draft'){
			$readonly = "READONLY";
			$readonlya = '';
		}
		
		if($statusA=='Published' ){
			$readonlya = "READONLY";
		}
		
		if($_GET['ext']=='Extend'){
			$extend = $_GET['ext'];
			$readonlya = '';
		}	
		if($status=='Draft'){
			$readonly= '';
			$readonlya = '';
		}	
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Tender / RFP
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard_athang.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender / RFP</a></li>
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
							$statuss   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
							
						$today_date = date('Y-m-d');
						$today_time = date('H:i:s');
						if( date('Y-m-d', strtotime($deadline_date)) <= $today_date && $deadline_time < $today_time && $status!='Opened' && $status!='Draft'){
								
								$status = 'Expired';
								$readonlya = "READONLY";
								$readonlyc = "READONLY";
								
						}
						
						/* if($status=='Opened' ){
							$readonly = "";	
						} */
						
						if( $status=='Submitted' || $status=='Completed'){
							$readonlya = "READONLY";
							$readonly  = "READONLY";
						}
						if( $status=='Completed'){
							$readonlyb = "";
							
						}
						if( date('Y-m-d', strtotime($deadline_date)) <= $today_date && $deadline_time < $today_time ){
							$readonlya = "READONLY";
							$readonly  = "READONLY";
						}
						
						if($final_status=='Final'){
							$readonlya = "READONLY";
							$readonly  = "READONLY";
							$readonlyb = "READONLY";
						}	
						
						if($status=='Draft'){
							$readonly= '';
							$readonlya = '';
							$readonlyb = '';
						}
						
						if($status=='Published'){
							$disabled= 'DISABLED';
							$readonlyb = "READONLY";
						}
						
						?>
						
					<?php	
						if($approval_status=='Rejected'){ ?>
							<span class="pull-right"><h4 style="color:red;"><b><?= $approval_status;?></b></h4></span>
					<?php 	
						} 
						if($final_status=='Final'){
					?>		
							<span class="pull-right"><h4 style="color:red;"><b><?= $final_status;?></b></h4>
							</span>
					<?php 	}	
						else {
					?>
							<span class="pull-right"><h4 style="color:red;"><b><?= $status;?></b></h4>
							</span>
							
							
							<span class="pull-right"><h4 style="color:red;"><b><?= $extend;?></b>&nbsp;&nbsp;&nbsp;</h4>
							
							</span>
							
					<?php } ?>	
					
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
					
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
						
						<input type="hidden" name="extend" id="extend" value="<?php echo $extend;?>" >
						
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
					
					$po_type = $row['po_type'];
					
				?>
					
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" > Tender </a></li>
                  <?php	if(  $status=='Opened' || $status=='Expired'){ ?>	
							<li>
								<a href="#tab_2" data-toggle="tab" class="btn btn-danger" id="second_tab" >Comparative Chart</a>
							</li>
				<?php 	}  ?>	
						<li><a href="#tab_5" data-toggle="tab" id="fifth_tab" >Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="third_tab" >Workflow</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-danger" id="fourth_tab" >Log</a></li>
				 <?php	if(  $status=='Opened' || $status=='Expired' || $status=='Submitted'){ ?>			
						<li><a href="tender_view_prn.php?sub=pdf&id=<?php echo $row['id'];?>" class="btn btn-success" target="_blank" >View</a></li>
				<?php 	}  ?>
				
					</ul>
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<?php $_SESSION['company_id'] = $row['company_id'];
							  $_SESSION['status']  = $row['status'];						
						?>
						<?php 
								$company_id = $row['company_id'];
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2 	= mysqli_query($con, $sql);
								$r2  = mysqli_fetch_array($q2);
						?>
						<br>
						<div class="form-group">
								<div class="col-md-1">
									<label for="project" class="control-label">Srno.</label>
									<input type="text" class="form-control" id="tender_id" name="tender_id" style="text-align:right;" readonly value="<?= $tender_id;?>" >
								</div>
								
								<div class="col-md-6">
									<label for="project" class="control-label">Tender Title.</label>
									<input type="text" class="form-control" id="tender_title" name="tender_title" style="text-align:left;" <?= $readonly; ?> value="<?php echo $row['tender_title'];?>" >
								</div>
								
								<div class="col-md-5">
									<label for="project" class="control-label">Company<span style="color:red;"> **</span></label>
									
								<?php 
									$company_id = $row['company_id'];
									if(!empty($readonly)){ 
										$sql = "select * from company where comp_id = '$company_id' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($q2);
										$comp_name = $r2['comp_name'];
								?>
										<input type="hidden" class="form-control" name="company_id" id="company_id" <?= $readonly; ?> value="<?= $company_id;?>" >
										
										<input type="text" class="form-control" style="text-align:left;" <?= $readonly; ?> value="<?php echo $comp_name;?>" >
								
								<?php } 
									else { 
								?>		
									<select class="form-control select2"  name="company_id" id="company_id" required >
										<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								<?php } ?>	
									
								</div>
							
								
							</div>
								
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label">Department <span style="color:red;"> **</span></label>
								<?php 
									$department_id = $row['department'];
									if(!empty($readonly)){ 
										$sql = "select * from sma_department where id = '$department_id' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($q2);
										$dept_name = $r2['name'];
								?>
										<input type="hidden" class="form-control" name="department" id="department" <?= $readonly; ?> value="<?= $department_id;?>" >
										
										<input type="text" class="form-control" style="text-align:left;" <?= $readonly; ?> value="<?php echo $dept_name;?>" >
								
								<?php } 
									else {
								?>			
									<select class="form-control" name="department" <?= $readonly; ?> id="department" required >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
									</select>
								<?php } ?>	
								</div>
										
							<?php  
								$created_date = date('d-m-Y', strtotime($row['created_date'])); 
								$deadline_date = date('d-m-Y', strtotime($row['deadline_date']));
							?>
							
								<div class="col-md-3">
									<label class="control-label"> Date</label>
									<div class="input-group date" <?= $readonly; ?> readonly data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="created_date" name="created_date" placeholder="dd-mm-yyyy" readonly value="<?=$created_date;?>">
									</div>
								</div>
									
										
							</div>
							
								
                        <div class="form-group">    
							<div class="col-sm-2">
							<label for="deliveryLocation" class="control-label">Delivery Location<span style="color:red;"> **</span></label>
						<?php
						if($status =='Draft'){
						?>	
									<span id="getlocation">
										<select class="form-control" required id="location" name="location_v" onchange="getdelvaddr(this.value)" <?php echo $readonly; ?> >
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
						
								<div class="form-group">
									<div class="col-md-2">
										<label class="control-label">Tender Visibility <span style="color:red;"> **</span></label><BR>
							<?php if(empty($readonlya )){ ?>
										<input type="radio"	name="visible" id="visible" <?php echo ($row['visible'] == 'V')?'CHECKED="CHECKED"':'';?>  value ="V"  >Private &nbsp;
										<input type="radio"	name="visible" id="visible" <?php echo ($row['visible'] == 'P')?'CHECKED="CHECKED"':'';?>  value ="P"  >Public
							<?php } 
								  else {
									 $visible = $row['visible'];
									 if($visible=='V'){
										echo "<B>Private </b>";
									 }
									 else if($visible=='P'){
										 echo "<b>Public </b>";
									 } 
							} ?>
									</div>
									
									<div class="col-md-3">
										<label class="control-label">Pricing Visibility <span style="color:red;"> **</span></label><BR>
							<?php if(empty($readonlya )){ ?>			
										<input type="radio"	name="price_visible" id="price_visible" <?php echo ($row['price_visible'] == 'O')?'CHECKED="CHECKED"':'';?>  value ="O"  >Open View &nbsp;
										<input type="radio"	name="price_visible" id="price_visible" <?php echo ($row['price_visible'] == 'E')?'CHECKED="CHECKED"':'';?>  value ="E"  >Employee Closed View	
							<?php } 
								  else {
									 $price_visible = $row['price_visible'];
									 if($price_visible=='O'){
										echo "<B>Open View </b>";
									 }
									 else if($price_visible=='E'){
										 echo "<b>Employee Closed View	 </b>";
									 } 
							} ?>			
									</div>
									
									<div class="col-md-2">
										<label class="control-label"> Deadline Date</label>
										<div class="input-group date" data-provide="datepicker<?= $readonlya; ?>" data-date-format="dd-mm-yyyy">
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
										<input type="text" class="form-control" <?= $readonlyb; ?> id="deadline_date" name="deadline_date" placeholder="dd-mm-yyyy" value="<?= $deadline_date;?>">
										</div>
									</div>
									
									<div class="col-md-2">
									<div class="bootstrap-timepicker">
										<label>Time </label>
										<div class="input-group">
											<input type="time" class="form-control timepicker123" id="deadline_time" name="deadline_time" <?= $readonlyb; ?> value="<?= $row['deadline_time'];?>" >

										<!--	<div class="input-group-addon">
											  <i class="fa fa-clock-o"></i>
											</div>-->
										</div>
									</div>
									</div>
									
								</div>		
								
								<div class="form-group">
									<div class="col-md-3">
										<label class="control-label"> Payment Terms (in Days)</label>
										<input type="text" class="form-control" <?= $readonly; ?> id="payment_within_days" name="payment_within_days"  style="text-align:right;" placeholder="" value="<?php echo $row['payment_within_days'];?>" >
									</div>
									
									<div class="col-md-2">
										<label class="control-label"> Retention %</label>
										<input type="text" class="form-control" <?= $readonly; ?> id="retention" name="retention" style="text-align:right;" placeholder="" value="<?php echo $row['retention'];?>" >
									</div>
									
									<div class="col-sm-4">
										<label for="company_id" class="control-label ">Workflow Type *</label>
								<?php 
									$trans_type = $row['trans_type'];
									if(!empty($readonly)){ 
										$sql = "select * from sma_workflow_type where id = '$trans_type' ";
										$q2 		= mysqli_query($con, $sql);
										
										$r2 = mysqli_fetch_array($q2);
										$workflow_type = $r2['workflow_type'];
								?>
										<input type="hidden" class="form-control" name="trans_type" id="trans_type" <?= $readonly; ?> value="<?= $trans_type;?>" >
										
										<input type="text" class="form-control" style="text-align:left;" <?= $readonly; ?> value="<?php echo $workflow_type;?>" >
								
								<?php } 
									else { 
								?>				
										<select class="form-control select3" <?= $readonly; ?> name="trans_type" id="trans_type" required >
											<option value=""> Select </option>
											<?php $sql = "select * from sma_workflow_type where doc_type = 'TN' and status = 'Y'  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
											<?php } ?>
										</select>	
								<?php }  ?>	
								
									</div>
									
								</div>		
								
								<div class="form-group">
									<div class="col-md-12">
										<h3> Internal Notes</h3>
										<textarea rows="5" class="form-control" <?= $readonly; ?> id="reason1" name="background" ><?= $row['background'];?></textarea>
									</div>
								</div>	

								<div class="form-group">
									<div class="col-md-12">
										<h3> Vendor Note / Scope of Work / Specification</h3>
										<textarea rows="5" class="form-control" <?= $readonly; ?> id="reason2" name="scope_of_work" ><?= $row['scope_of_work'];?></textarea>
									</div>
								</div>	
							
						<div class="box-footer">
							<div class="col-sm-6 text-right">	
							<?php if( $status=='Draft' || $extend=='Extend' ){ ?>
								<span class='hidesend' >
									<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save_hdr">
								</span>	
							<?php } ?>
							</div>
						</div>					
								
								<!--<div class="form-group">
									<div class="col-md-12">
										<h3> Terms & Condition</h3>
										<textarea rows="5" class="form-control" <?= $readonly; ?> id="reason" name="remarks" ><?= $row['remarks'];?></textarea>
									</div>
								</div>	-->

<!-- Terms Confitions -->


			<div class="panel panel-default">
				
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTM"><b>  Terms & Conditions </b> <span class="caret"></span> </a></h4>
					
					
										
				</div>
							
                <div id="stepTM" class="panel-collapse collapse in">
					<div class="form-group">
						<div class="col-md-1" >
									&nbsp;
						</div>
						<div class="col-md-3" >
					<?php	if(!empty($file_name)){ ?>
							<label>File Link : <a href="<?= $file_path.'/'.$file_name;?>" target="_blank"><?= $file_name;?> </a></label>
					<?php } ?>		
						</div>
						
					<!--	<label class="control-label col-md-2">Attachment :</label>
						<div class="col-md-6">
							<input type="file" class="form-control" name="exceldoc" class="docfile">
						</div>
					-->	
					</div>	
								
					<div class="panel-body">
							<div class="box-header">	
                                        <table id="prItemsTablea" class="table table-bordered table-striped">
									
                                            <thead>
                                            <tr>
                                                <th style="text-align:left">Terms & Conditions </th>
												
												<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
																			
                                            <tbody id="prItemsTableBody">
									<?php
												
									 		$sql="SELECT * from sma_tender_terms where tender_hdr_id = '$tender_id' ";
										 		
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
												
											while($rowd = mysqli_fetch_array($result)){
												$tender_hdr_id 		= $rowd['tender_hdr_id'];
												$terms_conditions 	= $rowd['terms_conditions'];
												$terms_srno			= $rowd['id'];
												
												//$delDocUrl = "del_terms_func.php?id=" . $terms_srno . "&url=" . urlencode($_SERVER['REQUEST_URI']);
												
											?>
										
                                            <tr>
                                 
                                            	<td width="80%" style="text-align:left"><?php echo $terms_conditions;?></td>
												
												<td width="10%" style="text-align:right">
										<?php 
											//if(empty($readonly)){ 
										?>			
												<a href='#modalEditTerms' data-id='<?php echo $terms_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTerms<?php echo $terms_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php 
													include "edit_terms_func.php"; 
												?>				
<!-- Modal Edit Item-->														
												<?php if($status =='Draft'){ ?>
														<a href='#modalDeleteTerms' id='delete-<?php echo $tender_id;?><?php echo $terms_srno;?>' data-toggle='modal' data-id='<?php echo $tender_id;?><?php echo $terms_srno;?>' data-target='#modalDeleteTerms<?php echo $tender_id;?><?php echo $terms_srno;?>'><i class='fa fa-trash-alt'></i></a>
												
<!-- Modal Delete Item-->								
												<?php include "del_terms_func.php"?>
<!-- Modal Delete Item-->
												<?php } ?>	
												
												</td>
										<?php //} ?>
										
											</tr>
									<?php 		
											}?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
										
										<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_terms">  
                                   <?php	
										if(empty($readonly)){  
									?> 
									<tr> 
										
										<td width="80%">
											 <textarea class="form-control terms_conditions" name="terms_conditions[]" rows="2" placeholder="Enter Terms Conditions..."></textarea>
										</td>
									
                                        <td width="10%">
									<?php	
										if(empty($readonly)){  
									?> 
											<button type="button" name="add" id="addTERMS" class="btn btn-success">Add More</button>
									<?php 
										} 
									?>	
										</td> 
										
                                    </tr> 
								<?php 
									} 
								?>				
                               </table>  
                           
                            </div>
						</div>
				</div>
			</div>
		</div>	
						
<!--Terms Condition -->


<!--Send TO Supplier -->

			<div class="panel panel-default">
				
					<div class="panel-heading">
						       	
						<span class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepPD"><b style="color:black;">  Product Details</b> <span class="caret"></span> </a></span>
						<?php if($status=='Draft'){	 ?>	
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

					<div id="stepPD" class="panel-collapse collapse in">
						<div class="panel-body">
						
                                    <table id="prItemsTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Description</th>
										<th>UOM</th>
                                        <th style="text-align:right;">Qty</th>
												
										<th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="prItemsTableBody">
									<?php	
									//$purchase_id = $row['id'];
									
									$sql="SELECT * from sma_tender_items where tender_hdr_id = '$tender_id' ";
									mysqli_query($con, $sql);
									$items_cnt = mysqli_affected_rows($con);
									
									$sql="SELECT * from sma_tender_items where tender_hdr_id = '$tender_id' ";
									$result = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$value="";
									while($row2 = mysqli_fetch_array($result)){
										
										$quantity 				= $row2['quantity'];
										$delivery_location 	= $row2['delivery_location'];
										$material_desc 		= $row2['material_desc'];
										
										$unit 				= $row2['uom'];
										$material_id 		= $row2['material_id'];
										$sql="SELECT * FROM sma_product where id = '$material_id' ";
												
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$mat = mysqli_fetch_array($res2);
										$product_name 		= $mat['name'];
										$product_category 	= $mat['group'];
										
										if(empty($unit)){
											$unit = $mat['uom'];
										}	
										
										$delivery_date = date('d-m-Y', strtotime($row2['delivery_date']));
										if($delivery_date == '01-01-1970'){
											$delivery_date = '';
										}
										
										$rid = $row2['id'];
										
									?>	
										<tr>
											<td width='30%'><?php echo $product_name;?></td>
											<td width='30%'><?php echo $material_desc;?></td>
											<td width='10%'><?php echo $unit;?></td>
											<td width='10%' style="text-align:right;"><?php echo $quantity?></td>	
																					
											<td width='10%'>
										<?php 
											if(empty($readonly)){
										?>		
												<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->
											<?php 
												include "edit_func.php"; 
											?>
<!-- Modal Edit Item-->
												<a href='#modalDeleteProducts' id='delete-<?= $tender_id;?><?= $rid;?>' data-toggle='modal' data-id='<?= $tender_id;?><?php echo $rid;?>' data-target='#modalDeleteProducts<?= $tender_id;?><?= $rid;?>'><i class='fa fa-trash-alt'></i></a>
												
<!-- Modal Delete Item-->								
												<?php include "del_product_func.php"?>
											<?php
													
											 } ?>							
<!-- Modal Delete Item-->
												</td>		
											</tr>
											<?php
												
											}
												
											$checker_value = $tot_amount;
												
											?>		

                                            </tbody>
											
                                            <tfoot>
                                            <tr>
                                                
                                            </tr>
                                            </tfoot>
											
                                        </table>
								
						</div>
					</div>			
				</div>
			
<!--Send TO Supplier -->
								
			
<!--Vendor Comparision -->
												
			<div class="panel panel-default">
				
				<div class="panel-heading">
					<div class="box-header">
                    <span class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepSS"><b style="color:black;">  Send To Supplier</b> <span class="caret"></span> </a></span>
					<?php  if($status=='Draft' || $user=='Admin' || $status=='Opened'){
					//Added "Admin" As per Ranganathan - 06-02-2023
					?>
                                    
								       <?php $data_mode = 'Add';?>
                                        <span class="pull-right">
                                            <a href="#"
                                               class="btn btn-primary" data-mode='Add'
                                               data-toggle="modal" data-target="#modalAddVendor<?php echo $data_mode;?>">Add
                                            </a>
                                        </span>
								    
					<?php } ?>
						
					</div>	
				</div>
							
                <div id="stepSS" class="panel-collapse collapse in">
					<div class="panel-body">
								
                                        <table id="prItemsTablea" class="table table-bordered table-striped">
									
                                            <thead>
                                            <tr>
                                                <th style="text-align:left">Supplier Name</th>
												<th style="text-align:left">Email Id</th>
												<th style="text-align:left">Status</th>
												<th style="text-align:left">Quotation Received</th>
												<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
																			
                                            <tbody id="prItemsTableBody">
									<?php
												//$approval_hdr_id=$_GET['approval_hdr_id'];
											$sql="SELECT * from sma_tender_supplier where tender_hdr_id = '$tender_id' ";
											mysqli_query($con, $sql);
											$supplier_cnt = mysqli_affected_rows($con);
									
									 		$sql="SELECT * from sma_tender_supplier where  tender_hdr_id = '$tender_id' ";
										 		
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
												
											while($rowd = mysqli_fetch_array($result)){
												$tender_hdr_id 		= $rowd['tender_hdr_id'];
												$approval_srno 		= $rowd['id'];
												$quotation_received = $rowd['quotation_received'];
												
												$supplier_id = $rowd['supplier_id'];
												$sql = "select * from sma_party_mst where id = '$supplier_id' ";
												$q2  = mysqli_query($con, $sql);
												$r2 = mysqli_fetch_array($q2);
												$supplier_name  = $r2['party_name'];
												
												if($status == 'Draft'){
													$vn_status = 'Not Sent';
												}
												else if($status == 'Published'){
													$vn_status = 'Sent to supplier';
												}
												if($quotation_received=='Y'){
													$vn_status = 'Quotation Received';
												}
												
												if($selected=='Y'){
													$vn_status = 'Selected for Order';
												}
												
												$sql 	= "SELECT * FROM sma_tender_supplier_quote where supplier_id = '$supplier_id'";
												$res = mysqli_query($con,$sql);
												$affected_rows = mysqli_affected_rows($con);
												$r2 = mysqli_fetch_array($res);
												$rate		= $r2['rate'];
												if($affected_rows==0){
													$quotation_received = '';
												}
												else if($quotation_received=='Y'){
													$quotation_received = 'Yes';
												}	
												
											?>
										
                                            <tr>
                                 
                                            	<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
												<td width="20%" style="text-align:left"><?php echo $rowd['email_id'];?></td>
												<td width="10%" style="text-align:left"><?php echo $vn_status;?></td>
												<td width="10%" style="text-align:left"><?php echo $quotation_received;?></td>
												
												<td width="10%" style="text-align:right">
													
												<a href='#modalEditItemq' data-id='<?php echo $approval_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItemq<?php echo $approval_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php 
													include "edit_vendor_func.php"; 
												?>				
<!-- Modal Edit Item-->					
										<?php 
												if(empty($readonly)){ 
										?>									
												<?php //if($status !='Completed'){ ?>
														<a href='#modalDeleteItem' id='delete-<?php echo $tender_id;?><?php echo $approval_srno;?>' data-toggle='modal' data-id='<?php echo $tender_id;?><?php echo $approval_srno;?>' data-target='#modalDeleteItem<?php echo $tender_id;?><?php echo $approval_srno;?>'><i class='fa fa-trash-alt'></i></a>
														
												<?php //} ?>		
<!-- Modal Delete Item-->								
												<?php include "del_vendor_func.php"?>
<!-- Modal Delete Item-->
												<?php } 
												if( $status == 'Received' || $status =='Published' ){
												?>
												<span class="pull-right">
													<a href="<?php echo $baseurl . $modulePath . "tn_vender_resend_mail.php?sub=resend&tender_id=".$row['id']."&supplier_id=$supplier_id";?>" class="btn btn-primary" target="_blank" onclick="return confirm('Do you want to resend email to vendor?');" >ReSend Mail </a>
												</span>
												<?php } ?>

												</td>
										
											</tr>
									<?php 		
											}?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
										
										<input type="hidden" id="selected_vendor_value" name="selected_vendor_value" value="<?php echo $selected_vendor_value;?>" >
										
                                   
                                </div>
                            
						</div>
				</div>
						
<!--Vendor Comparision -->
				
			<div class="panel panel-default">
				
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepDA"><b> Supporting Documents by Creator</b> <span class="caret"></span> </a></h4>
				</div>
							
                <div id="stepDA" class="panel-collapse collapse in">
					<div class="panel-body">
						
                            <!-- Attachments -->
								
							 <?php
								$baseurlsi = "https://athaang.in/p2p2023/athaangSI/";
							$sqly = " AND supplier_id = 0 ";	
							if( $status == 'Opened' || $status == 'Expired' ){
								$sqly = " ";
							}	
                              $sql = "SELECT * FROM sma_tender_file_upload WHERE 1 AND tender_hdr_id = " . $tender_id . $sqly;
						//echo $sql;
						
                              $docResults = mysqli_query($con, $sql);
							  $rwaffect   = mysqli_affected_rows($con);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
							<?php if($rwaffect>0){ ?>	  
                                      <thead>
                                      <tr>
										  <th  width="10%">Doc Type</th>
                                          <th  width="20%" >Supplier Name</th>
										  <th  width="30%" >Description</th>
										  <th width="30%">File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
							<?php } ?>		  
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													$doc_desc 		= $docRow['doc_desc'];
													
													$docs_type		= $docRow['doc_type'];
													
													$supplier_id	= $docRow['supplier_id'];
														
													$sql = "select * from sma_party_mst where id = '$supplier_id' ";
													$q2  	= mysqli_query($con, $sql);
													$r2 	= mysqli_fetch_array($q2);
													$supplier_name  = $r2['party_name'];
													
													if($supplier_id==0){
														$baseurlsi = "https://athaang.in/p2p2023/";
													}
													else {
														$baseurlsi = "https://athaang.in/p2p2023/athaangSI/";
													}
													
													if(empty($docs_type)){
														$docs_type = 'N';
													}	
											  ?>
                                          <tr>
										      <td width="20%">
												<select class="form-control" name="docs_type" id="docs_type" required <?= $disabled;?> >
												<option value=""> Select </option>
												<option value="V" <?= ($docs_type == 'V')?'selected="selected"':'';?> > For Vender </option>
												<option value="I" <?= ($docs_type == 'I')?'selected="selected"':'';?> > For Internal </option>
												<option value="N" <?= ($docs_type == 'N')?'selected="selected"':'';?> > None </option>
												</select>
											 </td>
											  <td width="20%"><?php echo $supplier_name; ?></td>
											  <td width="30%"><?php echo $doc_desc; ?></td>
                                              
											  <td width="30%"><a target="_blank" href="<?php echo $baseurlsi.'tender/'.$docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              
                                          <?php if (empty($readonly)){ ?>
													
                                              <td width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
										  <?php } ?>	  
                                          
										  </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  
						<span id="gegpartyDoc">
							
						</span>
						
					<?php if (empty($readonlya)){ ?>	
					
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
												<select class="form-control" name="docs_type[]" id="docs_type" >
												<option value=""> Select </option>
												<option value="V" > For Vender </option>
												<option value="I"> For Internal </option>
												</select>
										</td>
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										
										<td width="40%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										
                                        <td width="10%">
									<?php	
										if(empty($readonly)){  
									?> 
											<button type="button" name="add" id="addDOC" class="btn btn-success">Add More</button>
									<?php 
										} 
									?>	
										</td> 
										
                                    </tr>  
                               </table>  
                           
							</div> 
					<?php } ?>		
					</div>
				</div>
			</div>					
			
			<div class="panel panel-default">
				
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepQR"><b> Submission</b> <span class="caret"></span> </a></h4>
				</div>
							
                <div id="stepQR" class="panel-collapse collapse in">
					<div class="panel-body">
							
                            <table id="prItemsTablea" class="table table-bordered table-striped">
									
                                            <thead>
                                            <tr>
                                                <th style="text-align:left">Supplier Name</th>
												<th style="text-align:left">Email Id</th>
												<th style="text-align:left">Quotation Received</th>
												<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
																			
                                            <tbody id="prItemsTableBody">
									<?php
												//$approval_hdr_id=$_GET['approval_hdr_id'];
												
									 		$sql="SELECT * from sma_tender_supplier where  tender_hdr_id = '$tender_id' ";
										 		
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
												
											while($rowd = mysqli_fetch_array($result)){
												$tender_hdr_id 		= $rowd['tender_hdr_id'];
												$approval_srno 		= $rowd['id'];
												$quotation_received = $rowd['quotation_received'];
												
												$supplier_id = $rowd['supplier_id'];
												$sql = "select * from sma_party_mst where id = '$supplier_id' ";
												$q2  = mysqli_query($con, $sql);
												$r2 = mysqli_fetch_array($q2);
												$supplier_name  = $r2['party_name'];
												
												$sql 	= "SELECT * FROM sma_tender_supplier_quote where supplier_id = '$supplier_id'";
												$res = mysqli_query($con,$sql);
												$affected_rows = mysqli_affected_rows($con);
												$r2 = mysqli_fetch_array($res);
												$rate		= $r2['rate'];
												if($affected_rows==0){
													$quotation_received = 'No';
												}
												else if($quotation_received=='Y'){
													$quotation_received = 'Yes';
												}	
												
											?>
										
                                            <tr>
                                 
                                            	<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
												<td width="20%" style="text-align:left"><?php echo $rowd['email_id'];?></td>
												<td width="10%" style="text-align:left"><?php echo $quotation_received;?></td>
												<td width="10%" style="text-align:right">
							<?php if($quotation_received=='Y'){ 
									$baseurla = $baseurl."athaangSI/tender/edit.php?sub=edit&supplier_id=".$supplier_id.'&id='.$tender_id;
							?>					
										<a href="<?= $baseurla;?>" class="btn btn-primary" target="_blank" > Click</a>
							<?php } ?>			
												</td>
										
											</tr>
									<?php 		
											}?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
										
										<input type="hidden" id="selected_vendor_value" name="selected_vendor_value" value="<?php echo $selected_vendor_value;?>" >
										
                                    
                                </div>
                            </div>
						
						</div>	
							
							<?php
							
								$_SESSION['tender_id'] 	= $tender_id;
								$_SESSION['status']  = $statusA;
								$status			 = $statusA;
								$approval_status = $approval_statusA;
							
							?>
							
					<span id="predit"></span>

						<div class="box-footer">
								<div class="col-sm-6">
									
								</div>
								
								<div class="col-sm-6 text-right">
								<!--<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								-->
								</div>
						</div>
						
								<div class="box-footer">
									<div class="col-sm-6">
										<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&tender_id='.$id ; ?>
													
									<?php  $approval_status = $row['approval_status'];
									//echo $user. "<BR>";
									 if ( $approval_status=='Rejected' || $status=='Rejected' || $user =='Admin' ){	
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
											
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
									$userid   		= $_SESSION['usrid'];
								
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
									
										
									}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 -->>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								}
							?>
							
							<?php
							$sql = "SELECT distinct(supplier_id) from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' and supplier_id in ( SELECT distinct(supplier_id) as supplier_id FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' and rate > 0 ) ";
							//echo $sql ."<BR>";
							$res1 = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$quotation_received  = mysqli_affected_rows($con); 
		//echo $tender_hdr_id."<BR>";
							if( ($status=='Completed' && $status!='Opened' && $quotation_received==0 ) || ( $tender_hdr_id== 275|| $tender_hdr_id==272 ) ){
									$sql = " SELECT * FROM sma_user WHERE userid = '$draft_by' ";
									$rs = mysqli_query($con, $sql);
									$rw = mysqli_fetch_array($rs);
									$draft_by_id = $rw['id'];
						
								if($draft_by_id == $userid ){
							?>
									<span class='hidesend' >	
										<a href="#approvalPublished" class="btn btn-primary" data-toggle="modal" data-mode="Approve" data-target="#approvalPublished">Publish </a>
									</span>
						<?php 	}
							}
						?>
								
									
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<?php	
									//echo $status ."<BR>";
									if($status=='Draft'){
									?>	
										<?php if( $supplier_cnt >0 && $items_cnt > 0 && $approval_status != 'Rejected' ){ ?>
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Submit Tender" >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
										<?php } ?>
									
										
								<?php } ?>

								<?php if( $status=='Draft' || $extend=='Extend'  || $final_status=='Final'){ ?>
								<span class='hidesend' >
									<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save_hdr">
								</span>	
								<?php } ?>
								
								<?php $baseurl1 = $baseurl.$modulePath.'index.php?sub=list' ?>
								
								<span>&nbsp;&nbsp;</span>
								
								<a href="<?php echo $baseurl1 ?>" class="btn btn-default" >Back</a>

						<input type='button' class="btn btn-primary" onclick="getapprover()" value="Submit Tender" >
						
							<?php if(  $status=='Opened' || $status=='Expired'){ ?>
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
							<?php } ?>
							
						</div>
							</div>	

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
					?>		
							</div>
					<?php		
						}
								
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
								<div class="box-footer">
								
							<?php	
								
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
			
<!-- End Here -->
			
			<div class="tab-pane" id="tab_5">
                            <!-- Attachments -->
							<p><?= $label_line; ?></p>
                            	
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'TN' AND reference_id = " . $tender_id;
//						echo $sql;
						
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                           <th width="20%" >Document Type</th>
                                          <th  width="25%" >Description</th>
										  <th  width="25%">Share Point Link
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  </th>
										  <th width="20%">File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc_fl.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
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
										      <td width="20%" ><?php echo $document; ?></td>
											  <td width="25%" ><?php echo $doc_desc; ?></td>
                                              <td width="25%" ><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  
											  <td width="20%" > <a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              
                                          <?php if (empty($readonly) || $user =='Admin' ){ ?>
													
                                              <td width="10%" ><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
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
                               <table class="table table-bordered" id="dynamic_field_o">
                                    <tr> 
										<td width="20%">
                                            <select class="form-control  doctype"  name="docs_type_o[]"  >
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
										<td width="25%">
											 <textarea class="form-control docdesc" name="docdesc_o[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="25%">
											 <textarea class="form-control share_point_link" name="share_point_link_o[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td width="20%" >
											<input type="file" name="fudoc_o[]" class="docfile">
										</td>
                                        <td width="10%" >
										 
											<button type="button" name="add" id="addDOC_o" class="btn btn-success">Add More</button>
										
										</td> 
										
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div> 
							
						<div class="box-footer">
								<div class="col-sm-6">
						<?php
								if(  $status=='Opened' || $status=='Expired'){ 
									$tab_v = '#tab_2';
									$tab_n = '#second_tab';
								}
								else {
									$tab_v = '#tab_1';
									$tab_n = '#first_tab';
								}	
						?>		
									<a href="<?= $tab_v ?>" class="btn btn-primary" data-toggle="tab" onclick="$('<?= $tab_n ?>').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
							
								</div>
								
								<div class="col-sm-6 text-right">
								
								<?php if( $status=='Draft' || $extend=='Extend' || $final_status=='Final'){ ?>
									<span class='hidesend' >
										<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save_hdr">
									</span>	
								<?php } ?>
								
										<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								</div>
						</div>
					    
			</div>
			
			<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $tender_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'TN' order by id  ";
								
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									if($created_date=='01-01-1970'){
										$created_date ='';
									}
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$created_date; ?>
								 
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'TN' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$statuswh				= $r1['status'];
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
												
												$role = $rw1['primary_role'];

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
												<td width="10%" style="text-align:left;"><?php echo $statuswh; ?></td>
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
						
						<div class="box-footer">
								<div class="col-sm-6">
						<?php
								if(  $status=='Opened' || $status=='Expired'){ 
									$tab_v = '#tab_5';
									$tab_n = '#fifth_tab';
								}
								else {
									$tab_v = '#tab_5';
									$tab_n = '#fifth_tab';
								}	
						?>		
									<a href="<?= $tab_v ?>" class="btn btn-primary" data-toggle="tab" onclick="$('<?= $tab_n ?>').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
							
								</div>
								
								<div class="col-sm-6 text-right">
							 
								</div>
						</div>
							
				</div>
			
				<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $tender_id;
									
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> 
								 
								</span>
												
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>OTP Dated</th>
											  <th>Supplier Name</th>
											   <th>OTP Open Date</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from tender_open_otp where tender_id = '$srno' and one_time = '1' order by id desc  ";
							//echo $s1;		
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while ($r1 = mysqli_fetch_array($res)){
												$email_id		= $r1['email_id'];
												$otp_date		= $r1['otp_date'];
												$otp_date		= date('d-m-Y h:i:sa', strtotime($r1['otp_date']));
												
												$otp_open_date		= date('d-m-Y', strtotime($r1['otp_open_date']));
												if($otp_open_date=='01-01-1970'){
													$otp_open_date ='';
												}
												else {
													$otp_open_date	= date('d-m-Y h:i:sa', strtotime($r1['otp_open_date']));
												}
												
												$id 				= $r1['id'];
												
												$sl="SELECT * FROM sma_party_mst where party_email = '$email_id' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$party_name = $rw['party_name'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $otp_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $party_name; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $otp_open_date; ?></td>
												
												
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
									</div>
								</section>
						</div>
							
				</div>
				

			<div class="tab-pane" id="tab_2" >
							
						<div class="modal-header" >
							<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$created_date; ?>
								 
							</span>
							
							<span class="pull-right"><a href="<?= 'comparative_report.php?sub=pdf&tender_id='.$tender_id ?>" class="btn btn-success" target="_blank" >Download</a></span>
							
							
				<?php if($final_status != 'Final'){ ?>			
							<div class="col-sm-6 text-right">
								<a href="#makeReviseTender" class="btn btn-info" data-toggle="modal" data-mode="Revise" data-target="#makeReviseTender">Revise Tender</a>	
							</div>
						</div>
				<?php } ?>
				
				<span id="rtedit"></span>
				
				<div style="width:101%;height:425px;overflow:scroll;border:1px #999;scroll-behavior: smooth;" class='megamenu123'>	
							
								<?php 
									
									$srno = $tender_id;
									$s1  = " SELECT distinct(a.supplier_id ) as supplier_id 
										FROM `sma_tender_supplier_quote` a, sma_tender_items b 
											WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and a.supplier_id >0  order by a.supplier_id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									while($r1 = mysqli_fetch_array($res)){;
										$supplier_id		= $r1['supplier_id'];
										
										$supplier_arr[]		= $r1['supplier_id'];
										
										$sql 	= " select * from sma_party_mst where id = '$supplier_id' "; 
										$q2		=	mysqli_query($con, $sql);
										$r2 	=	mysqli_fetch_array($q2);
										$party_name_arr[]		= $r2['party_name'];
										
									}	
							//print_r($party_name_arr);		
							?>		
									<table id="prtable" class="table table-bordered table-striped" style="font-size:12px;">
										<thead>
											<tr>
											  <th width="10%" >Product</th>	
											  <th width="10%" >Description</th>	
											  <th width="10%" >Quantity</th>	
											  <th width="10%" >UOM</th>	
								<?php	for($i = 0; $i < sizeof($party_name_arr); $i++) { ?>		  
											  <th ><?= $party_name_arr[$i];?></th>	
										<?php } ?>			 
											  
											</tr>
										</thead>
											
							<?php
									
								?>			
							
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
									<tbody>
									<?php
										
								$sql  = " SELECT distinct(b.material_id ) as material_id , a.quantity, b.material_desc
										FROM `sma_tender_supplier_quote` a, sma_tender_items b 
											WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0  order by a.supplier_id, b.material_id";
//echo $sql. "<BR>";										
									$ress  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r11 = mysqli_fetch_array($ress)){
										//$supplier_id_var	= $r11['supplier_id'];
										$material_id		= $r11['material_id'];
										$quantity			= $r11['quantity'];
										$material_desc 		= $r11['material_desc'];
										
										$sql= " select * from sma_product where id = '$material_id' "; 
										$q2	= mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($q2);
										$product_name		= $r2['name'];	
										$uom				= $r2['uom'];
										
								?>
										
										<tr>
											<td width="10%"><?= $product_name . ' ' . $material_id;?></td>
											<td width="10%"><?= $material_desc;?></td>
											<td width="06%"><?= $quantity;?></td>
											<td width="06%"><?= $uom;?></td>
											
								<?php			
										$supplier_id_arr	= array();
										$rate_arr 			= array();
										$quantity_arr		= array();
											
										$material_id_arr	= array();
										$sql  = " SELECT a.supplier_id, a.tender_hdr_id, a.quantity, a.rate, a.gst, b.material_id  
										FROM `sma_tender_supplier_quote` a, sma_tender_items b 
											WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and b.material_id = '$material_id' and supplier_id >0 order by supplier_id ";
//echo $sql. "<BR>";	//	and a.supplier_id = '$supplier_id_var'
										$res  = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r1 = mysqli_fetch_array($res)){
											$supplier_id_arr[]		= $r1['supplier_id'];
											$material_id_arr[]		= $r1['material_id'];
											$rate_arr[]				= $r1['rate'];
											$gst_arr[]				= $r1['gst'];
											$quantity_arr[]			= $r1['quantity'];
										}
						
										for($i = 0; $i < sizeof($supplier_id_arr); $i++){
																			
											$rate			= $rate_arr[$i];
											$gst			= $gst_arr[$i];
											$quantity		= $quantity_arr[$i];
											$net_total_arr[$i] 	= round( $net_total_arr[$i] + ( ($rate * $quantity) + (($rate_arr[$i] * $quantity_arr[$i]) * $gst_arr[$i] ) / 100 ) ,2);		
									?>							
											
											<td width="06%" style="text-align:right;" ><?= number_format(($rate_arr[$i] * $quantity_arr[$i]) ,2); ?></td>
												
								<?php	}
										echo "</tr>";
									
								}
									
								?>
								<tr>
											<th width="20%">Total </th>
											<th width="06%"></th>
											<th width="06%"></th>
											<th width="06%"></th>
								<?php			
								//print_r($net_total_arr);
								$sql = "SELECT supplier_id, round(sum( a.quantity * a.rate ),2) as amount_v, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
									FROM `sma_tender_supplier_quote` a, sma_tender_items b 
								WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 group by supplier_id ";
									$ress  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r11 = mysqli_fetch_array($ress)){
										$net_total		= $r11['amount_v'] 
									//for($ij = 0; $ij < sizeof($net_total_arr); $ij++){	  		
									//	$net_total 			= $net_total_arr[$ij];
								?>		
										<th width="10%" style="text-align:right;" ><?= number_format($net_total,2); ?></th>
								<?php }
									echo "</tr>";
								?>
							
							
									<tr>
											<td width="20%"><b>GST</b></td>
											<td width="06%"></td>
											<th width="06%"></th>
											<td width="06%"></td>
								<?php			
									$sql = "SELECT supplier_id, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
										FROM `sma_tender_supplier_quote` a, sma_tender_items b 
											WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 group by supplier_id ";
									$ress  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r11 = mysqli_fetch_array($ress)){
										$gst_value		= $r11['gst_value'];		
									//for($i = 0; $i < sizeof($supplier_id_arr); $i++) {	
																			
											$gst			= $gst_arr[$i];
											
									?>							
											
											<td width="06%" style="text-align:right;" ><?= number_format($gst_value ,2); ?></td>
												
								<?php	}
									echo "</tr>";
									
									$supplier_id_arr	= array();
									$rate_arr 			= array();
									$quantity_arr		= array();
										
									$material_id_arr	= array();
								//print_r($supplier_id_arr);
								
								?>		
											
									<tr>
											<th width="20%">Total Value</th>
											<th width="06%"></th>
											<th width="06%"></th>
											<th width="06%"></th>
								<?php			
								//print_r($net_total_arr);
								$sql = "SELECT supplier_id, round(sum( a.quantity * a.rate ),2) as amount_v, round( sum(( ( a.quantity * a.rate ) * a.gst) / 100), 2) as gst_value 
									FROM `sma_tender_supplier_quote` a, sma_tender_items b 
								WHERE b.id = a.`tender_item_id` and b.tender_hdr_id = '$tender_id' and supplier_id >0 group by supplier_id ";
									$ress  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r11 = mysqli_fetch_array($ress)){
										$net_total		= $r11['amount_v'] + $r11['gst_value'];	
								
									//for($ij = 0; $ij < sizeof($net_total_arr); $ij++){	  		
									//	$net_total 			= $net_total_arr[$ij];
								?>		
										<th width="10%" style="text-align:right;" ><?= number_format($net_total,2); ?></th>
								<?php }
									echo "</tr>";
								?>
							
									<tr>
											  <th width="20%" ></th>	
											  <th width="06%" ></th>
												<th width="06%"></th>											  
											  <th width="06%" ></th>	
								<?php	for($i = 0; $i < sizeof($supplier_arr); $i++) { ?>		  
											  <th ><span class="pull-right"><a href="<?php echo $baseurl.'athaangSI/' . $modulePath. 'edit.php?sub=edit&id='.$tender_id.'&supplier_id='.$supplier_arr[$i] ?>" class="btn btn-danger" target="_blank" >View</a></span></th>	
										<?php } ?>			 
											  
									</tr>
							<!-- View Supplier Quote -->
							
										</tbody>
									</table>
									
							<!-- TERMS Start -->
								<table id="prtable" class="table table-bordered table-striped" style="font-size:12px;">
										<thead>
											<tr>
											  <th width="35%" >Terms & Conditions</th>	
											  
											  
								<?php	for($i = 0; $i < sizeof($party_name_arr); $i++) { ?>		  
											  <th ><?= $party_name_arr[$i];?></th>	
										<?php } ?>			 
											  
											</tr>
										</thead>
								

								<?php
										
									$sql = " SELECT distinct(b.id ) as terms_id , b.terms_conditions, b.tender_hdr_id
										FROM `sma_tender_supplier_terms` a, sma_tender_terms b 
											WHERE b.id = a.`terms_id` and b.tender_hdr_id = '$tender_id' order by b.id ";
							//	echo $sql;			
								
								$ress  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r11 = mysqli_fetch_array($ress)){
										//$supplier_id_var	= $r11['supplier_id'];
										$terms_conditions		= $r11['terms_conditions'];
										$terms_id				= $r11['terms_id'];
										
								?>
										
									<tr>
										<td width="35%"><?= $terms_conditions;?></td>
										
							<?php
								$sql = " SELECT (b.id ) as terms_id , b.terms_conditions, b.tender_hdr_id,  a.terms_flag, a.remarks, a.supplier_id
										FROM `sma_tender_supplier_terms` a, sma_tender_terms b 
											WHERE b.id = a.`terms_id` and b.id = '$terms_id' and b.tender_hdr_id = '$tender_id' order by a.supplier_id, b.id ";
								
									$res  = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r1 = mysqli_fetch_array($res)){;
										$supplier_id_arr[]		= $r1['supplier_id'];
										$remarks_arr[]			= $r1['remarks'];
									
										$terms_id_arr[]			= $r1['terms_id'];
										$terms_flag_arr[]		= $r1['terms_flag'];
											
									}
									
										for($i = 0; $i < sizeof($supplier_id_arr); $i++) {	
																			
											$terms_flagg			= $terms_flag_arr[$i];
											$remarks_var			= $remarks_arr[$i];
											if($terms_flagg=='A'){
												$terms_flagg = 'N';	
											}
											
											if($terms_flagg == 'N'){
												$terms_flagg = 'No';
											}
											else if($terms_flagg == 'Y'){
												$terms_flagg = 'Yes';
											}
		
									?>							
											
											<td width="20%" style="text-align:center;" ><?= $terms_flagg ."<BR>" . $remarks_var ; ?></td>
												
								<?php	}
										echo "</tr>";
									
										$terms_flag_arr 		= array();
										$terms_id_arr			= array();
										$supplier_id_arr		= array();
										$remarks_arr			= array();
										
								?>		
								<?php } ?>			
								</table>			
							<!-- TERMS End -->			
										
							</div>
								
								
						<?php 
							$sel_party_name = '';
							$supplier_var 	= '';
							$readonlyd 		= '';
							
							$sql = " select sum(selected_amount) as selectedamount 
										FROM sma_tender_supplier_quote 
										WHERE tender_hdr_id = '$tender_hdr_id' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$selectedamount = $r2['selectedamount'];
							if($selectedamount>0){
								$readonlyd = 'READONLY';
							}
							else {
								$readonlyc 		= '';	
							}	
								
							$sql = " select * from sma_tender_supplier_quote where tender_hdr_id = '$tender_hdr_id' ";
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){
								
								$supplier_var .= $r2['supplier_id'].',';
								
								$selected_vendor = $r2['selected_vendor'];
								if($selected_vendor == 'Y'){
									$sel_party_name  = $r2['supplier_id'];
									$selected_amount = $r2['selected_amount'];
									$selected_remarks= $r2['selected_remarks'];
								}
							}
							
							$supplier_var .= '0';

						?>					
	 
					<form id="form1" class="form-horizontal" action="edit.php?sub=Selected&same_page=<?= $page ?>" method="post" enctype="multipart/form-data">
						<div class="form-group">
							
							<div class="col-sm-2">
								<input type="hidden" class="form-control" id="tender_id_v" name="tender_id_v" value="<?= $tender_hdr_id;?>" >
							</div>
							<div class="col-sm-4">
								<label for="Company" class="control-label">Supplier Selected for Tender</label>
                                <select class="form-control" name="sel_supplier_id" <?= $readonlyc. ' '. $readonlyd; ?> id="sel_supplier_id" >
                             	<option value=""> Select </option>
								<?php $sql = "select * from sma_party_mst where id in ($supplier_var) order by party_name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?= $r2['id'];?>" <?= ($sel_party_name == $r2['id'])?'selected="selected"':'';?> ><?= $r2['party_name'];?></option>
								<?php } ?>
								</select>
							</div>
						
							<div class="col-xs-2">
									<label for="prDate" class="control-label">Amount</label>
                                    <input type="text" class="form-control" <?= $readonlyc.' '. $readonlyd; ?> id="selected_amount" name="selected_amount" style="text-align:right;" value="<?= $selected_amount;?>" >
								</div>
								
						</div>
						
						<div class="form-group">
							<div class="col-sm-12">
								<label for="prDate" class="control-label">Remarks</label>
								<input type="text" class="form-control" <?= $readonlyc.' '. $readonlyd; ?> id="selected_remarks" name="selected_remarks" value="<?= $selected_remarks;?>" >
							</div>
						</div>	
						<div class="form-group">
							<div class="col-xs-10">
								<label>&nbsp;</label>
							</div>
							
						<?php if(empty($readonlyc) && empty($readonlyd) ){ ?>	
							<div class="col-sm-2 ">
								<input type="submit" class="btn btn-primary" name='saveSel' value="Final" >
							</div>
						<?php } ?>
						
						</div>
						
					</form>
					
					<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
						<?php 
						if( $statuss=='Opened' ){ ?>			
									
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next </a>
						<?php } ?>			 
								</div>
					</div>
						
						
				 </section>
							 
			 </div>
						
					</div>
					
				</div>
				
				
				
				</div>
			</div>	

			
                    </fieldset>
				
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      

<!-- Terms Start -->
<div class="modal fade" id="modalAddTerms<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddTermsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTermsLabel">Add - Terms</h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem.php?sub=Save" method="POST">

							<input type="hidden" name="tender_hdr_id" value=<?php echo $tender_id; ?>>
                            
                            <div class="form-group ">
                                <div class="col-md-12">
									<label for="itemRate" class="control-label">Terms & Conditions </label>
                                    <input type="text" class="form-control" name="terms_conditions" value="" >
                                </div>
                            </div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id='saveFormT' >Save</button>
							</div>
                        </form>
                    </div>
                </section>
            </div>

            </div>
        </div>
    </div>
</div>
<!-- Terms End -->	  
	  
<!-- Modal Add Vendor-->
<div class="modal fade" id="modalAddVendor<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddVendorLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddVendorLabel">Add - Send To Supplier </h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem_po.php?sub=Save" method="POST">

							<input type="hidden" name="tender_hdr_id" value=<?php echo $tender_id; ?>>
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-4 control-label">Supplier</label>
                                <div class="col-sm-8" >
                                    <select class="form-control select2123" name="supplier_id" onchange="getpangst(this.value)" >
									<option value="" > Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['supplier_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
							
                            <div class="form-group col-md-12">
								<span id="getpangst">
									
								</span>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-4 control-label">Remarks</label>
                                <div class="col-sm-8">
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
                <h4 class="modal-title" id="modalAddItemLabel">Add Product to Tender </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="tenderId" value="<?php echo $_GET['id'];?>">
						
							
							<div class="form-group">
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control select2123" id="categoryId" onchange="getmaterial1(this.value)">
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
									<span id="getmaterial1" >
									<!--<span id="getgrnitem" >-->
										<select class="form-control" id="itemName" required >
											<option value="">Select</option>	
										
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
							    		
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
									<label for="itemQuantity" class="control-label col-sm-1">Qty.</label>
                                	<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" value="0">
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label col-sm-1">Units</label>
									<span id="getunit2">
										<input type="text" class="form-control" id="itemUnits" name='itemunits' readonly >
									</span>

                                </div>
								
							<!--	<div class="col-sm-3">
									<label class="control-label ">Delivery Date </label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="deliveryDATE" >
									</div>
								</div>-->
								
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
				
				<span id="hidesave">
					<button type="button" class="btn btn-primary " id="addItem">Save</button>
				</span>
				
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
										
							$tender_id 		= $_SESSION['tender_id'];
							//$status 	= $status;
							$role		= $_SESSION['role']; //Maker
							
							?>	
										
							<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
							<input type="hidden" id="modeE" name="mode" value='Approve' >
							<input type="hidden" id="tender_idE" name="tender_id" value="<?= $tender_id; ?>" >
										
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
											$tender_id 	= $_SESSION['tender_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="tender_id" id="po_idR" value="<?php echo $tender_id; ?>" >
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



<!--Published Popup Start -->

<div class="modal fade" id="approvalPublished" role="dialog" aria-labelledby="approvalPublished">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalPublished">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="box-body">
                        <div class="col-md-12">
                        <div class="box-body">
							<form class="form-horizontal">
                                        
							<?php   
										
							$tender_id 		= $_SESSION['tender_id'];
							//$status 	= $status;
							$role		= $_SESSION['role']; //Maker
							
							?>	
										
							<input type="hidden" id="approverP" name="approver" value='<?= $userid ?>' >
							<input type="hidden" id="modeP" name="mode" value='Publish' >
							<input type="hidden" id="tender_idP" name="tender_id" value="<?= $tender_id; ?>" >
										
							<div class="form-group">
								<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
									<textarea class="form-control" rows="3" name="remarks" id="remarksP"></textarea>
								</div>
							</div>
							
							</form>	
									
                        </div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="submitPublish">Submit</button>
					</div>
			
                </div>
             </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Published End -->


<!--/.col (right) -->


<!--Make to Revise Tender-->
<div class="modal fade" id="makeReviseTender" role="dialog" aria-labelledby="makeReviseTender">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeReviseTender">Do you want to Make Revise Tender? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$tender_id 	= $_SESSION['tender_id'];
											$status 	= $_SESSION['status'];
											$company	= $_SESSION['company'];
								
										?>
										<input type="hidden" name="tender_id" id="tender_idRT" value="<?php echo $tender_id; ?>" >
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksRT"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitRevise">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>
<!--Make to Revise Tender-->
	
	
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
										
											$tender_id 	= $_SESSION['tender_id'];
											$status = $_SESSION['status'];
											$company= $_SESSION['company'];
								
										?>
										<input type="hidden" name="tender_id" id="tender_idD" value="<?php echo $tender_id; ?>" >
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
										
											$tender_id 	= $_SESSION['tender_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
									
										?>
										<input type="hidden" name="tender_id" id="tender_idZ" value="<?php echo $tender_id; ?>" >
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

<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

<script>  
 $(document).ready(function(){
      var i=1;  
      $('#addDOC').click(function(){  
			//var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control" name="docs_type[]" id="docs_type" ><option value=""> Select </option><option value="V"> For Vender </option><option value="I"> For Internal </option></select></td><td width="30%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="40%"><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
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
 
 
 
 $(document).ready(function(){
      var i=1;  
      $('#addDOC_o').click(function(){  
			//var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field_o').append('<tr id="row'+i+'"><td width="20%"><select class="form-control" name="docs_type_o[]" id="docs_type" ><option value=""> Select </option><option value="V"> For Vender </option><option value="I"> For Internal </option></select></td><td width="30%"><textarea class="form-control docdesc" name="docdesc_o[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="25%"><textarea class="form-control share_point_link" name="share_point_link_o[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="40%"><input type="file" name="fudoc_o[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
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
				

<!-- For Terms Start -->
<script>  
 $(document).ready(function(){
      var i=1;  
      $('#addTERMS').click(function(){  
			//var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_terms').append('<tr id="row'+i+'"><td width="80%"><textarea class="form-control docdesc" name="terms_conditions[]" rows="2" placeholder="Enter Terms Conditions..."></textarea></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      
 });  
 </script>				
<!-- For Terms End -->
		
<?php 	
		include("../footer.php");	
?>


<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>


<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>


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
		
		$("#prItemsTablea").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
		
    });

</script>

<script>

	 $("#submitRevise").on("click", function(e){
        var tender_id		= $("#tender_idRT").val();
        var remarks			= $("#remarksRT").val();
		
//alert(remarks +  ' ' + tender_id );
	
		$('#makeReviseTender').modal('hide');
		var strURL = "revise_func.php";
		$.post(strURL,{ tender_id:tender_id,
						remarks:remarks},
						function(result){
		      $('#rtedit').html(result);
		});
		
	});


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var tender_id		 =  $("#tender_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + tender_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ tender_id:tender_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

    $("#submitApprove").on("click", function(e){
		
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);

		var company		 		=  $("#projecT").val();
		var tender_id		 	=  $("#tender_idE").val();
	    var status 				=  $("#statuS").val();
		//var to_supplier			= $("#to_Supplier").val();
		//var budget_head_id		= $("#budget_Head").val();		
        var statusap			=  mode;
		var approver			=  $("#approverC").val();
		var remarks				=  $("#remarksA").val();
//alert(company + ' ' + statusap+' #0# '+status+' #1# '+approval_memo_ref+' #5# '+tender_id);
		
		var strURL = "app_func.php";
		$.post(strURL,{ tender_id:tender_id,
						mode:mode,
						company:company,	
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
		var tender_id		 	=  $("#po_idR").val();
		
		var status 			=  $("#statuS").val();
		//var tender_id			=  $("#iD").val();
		
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
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+tender_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ tender_id:tender_id,
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

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var tender_id		 	=  $("#tender_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + tender_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ tender_id:tender_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});


    $("#addItem").on("click", function(e){
        var sub = 'sub1';
	
		var tender_id  =  $("#tenderId").val();		
        var product_id   =  $("#itemName option:selected").val();
        var product_name =  $("#itemName option:selected").html();
		
		var budget_name   	=  $("#budget_Name").val();
		var budget_head   	=  $("#budget_Head").val();
		var balance_budget  =  $("#balance_BUDGET").val();
		
		var description 	=  $("#itemDescription").val();
				
		var quantity 		= parseFloat($("#itemQuantity").val());
        var units 			= $("#itemUnits").val();
        var deliverydate 	= $("#deliveryDATE").val();
		
		var row_affected  =  parseInt($("#row_affected_a").val());
	
//alert(parseInt(row_affected) + ' ' + parseInt(balance_budget));		
/* 	
As per Ranganathan 21-12-2022
	if(parseInt(row_affected)==0 || parseInt(balance_budget)==0){
			alert('Budget not available for product !!!');
			return false;
		}
 As per Ranganathan 21-12-2022*/
 
		if(product_id==''){
			alert('Product must be select.....');
			return false;
		}
		
		if(parseInt(quantity)==0 ){
			
			alert('Quantity must be enter.....');
			return false;
		}

//alert(sub + ' Hello ' + tender_id);		

        $('#modalAddItem').modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ product_id:product_id,
							tender_id:tender_id,
							product_name:product_name,
							description:description,
							budget_name:budget_name,
							budget_head:budget_head,
							quantity:quantity,
							units:units,
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

	function delete_terms(tender_hdr_id, id){
		var sub = 'sub4b';
        var tender_hdr_id = tender_hdr_id;
		var id	 = id;
//alert(tender_hdr_id + ' ' + id);
		$('#modalDeleteTerms'+tender_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ tender_hdr_id:tender_hdr_id,id:id,sub4b:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
	
			});
		
		window.location.href='edit.php?sub=edit&id='+tender_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
		
	}

	function delete_product(tender_hdr_id, id){
		var sub = 'sub4c';
        var tender_hdr_id = tender_hdr_id;
		var id	 = id;
//alert(tender_hdr_id + ' ' + id);
		$('#modalDeleteProducts'+tender_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ tender_hdr_id:tender_hdr_id,id:id,sub4c:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
	
			});
		
		window.location.href='edit.php?sub=edit&id='+tender_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
		
	}
	
	function delete_appquote(tender_hdr_id, id){
		var sub = 'sub4a';
        var tender_hdr_id = tender_hdr_id;
		var id	 = id;
//alert(tender_hdr_id + ' ' + id);
		$('#modalDeleteItem'+tender_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ tender_hdr_id:tender_hdr_id,id:id,sub4a:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
	
			});
		
		window.location.href='edit.php?sub=edit&id='+tender_hdr_id+'&active=active';
			
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
	
	function getcatbudget (id){
		var sub    			= 'sub14';
		var strURL 			= "app_func.php";
		var company_id    	= document.getElementById("company_id").value;
		var product_id    	= document.getElementById("itemName").value;
		
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14:sub},function(result){
		      $('#getcatbudget').html(result);
			  var balance_budget    	= document.getElementById("balance_BUDGET").value;
			  $('#hidesave').show();
			  if(balance_budget==0){
					$('#hidesave').hide();
			  }	 
			  
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
		
		var company_id    	= document.getElementById("company_id").value;
		//var checker_value   = document.getElementById("checker_value").value;
		var trans_type    	= document.getElementById("trans_type").value;
		var department    	= document.getElementById("department").value;
		//var po_type  	   	= document.getElementById("po_typea").value;
		var checker_value   = 100;
		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + po_type + ' ' + trans_type + ' ' + company_id + ' ' + checker_value);	
		
		var strURL = "app_func.php";
		$.post(strURL,{department:department,company_id:company_id, checker_value:checker_value, trans_type:trans_type, sub24:sub},function(result){
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
	
	function getworkflowtype123(id){
		
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
	
	function getcomment(comment,tender_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,tender_id:tender_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}	
	
	$("#submitPublish").on("click", function(e){
		
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub99';
		var mode		 		=  $("#modeP").val();
		var company		 		=  $("#projecT").val();
		var tender_id		 	=  $("#tender_idP").val();
	    var status 				=  $("#statuS").val();
	    var statusap			=  mode;
		var approver			=  $("#approverP").val();
		var remarks				=  $("#remarksP").val();
		
		var strURL = "app_func.php";
		$.post(strURL,{ tender_id:tender_id,
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
		
		$('#approvalPublished').modal('hide');
		
	});
	
	
	function getpangst(id){
		var sub    = 'sub30';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub30:sub},function(result){
		      $('#getpangst').html(result);
		});

	}	
</script>

</body>
</html>
