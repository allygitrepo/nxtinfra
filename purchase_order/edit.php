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
			//$sql = "update sma_budget set blocked_budget = blocked_budget - $po_amount where id = '$budget_id' ";
			//mysqli_query($con, $sql);
				
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


if($_POST['editvendor']){
	
		$po_approval_hdr_id     	= $_POST['po_approval_hdr_id'];
		$approval_srno     			= $_POST['approval_srno'];
		
		$vendor_selected     		= $_POST['vendor_selected'];
		
		$quote_ref_no     			= $_POST['quote_ref_no'];
		$supplier_name	     		= $_POST['supplier_name'];
		$values			     		= $_POST['values'];
		$remarks		     		= $_POST['remarks'];
		$rowaffect = 0;
		
		if(empty($supplier_name)){
				echo "<script>alert('Vendor should be select...')</script>";
				$baseurl1 = $baseurl ."purchase_order/edit.php?sub=edit&id=$po_approval_hdr_id&active=active&555";
				echo "<script>window.location.href='$baseurl1';</script>";
		}

		$sql = "UPDATE sma_purchase_order SET to_supplier	= '' where id = '$po_approval_hdr_id'";
		mysqli_query($con, $sql);
			
		if($vendor_selected=='Y'){
			$sql = "select * from sma_po_approval_details where vendor_selected = 'Y' and supplier_name not in ( '$supplier_name' ) AND po_approval_hdr_id = '$po_approval_hdr_id'";
			$qry = mysqli_query($con, $sql);
			$rowaffect = mysqli_affected_rows($con);
			
			$sql = "UPDATE sma_purchase_order SET to_supplier	= '$supplier_name' where id = '$po_approval_hdr_id'";
			mysqli_query($con, $sql);
			
		}
		
		if($rowaffect == 0){
			$sql = " UPDATE sma_po_approval_details SET vendor_selected = '$vendor_selected' ,
								quote_ref_no     		= '$quote_ref_no',
								supplier_name	     	= '$supplier_name',
								`values`			     = '$values',
								remarks		     		= '$remarks'
				WHERE approval_srno ='$approval_srno' 
				AND po_approval_hdr_id = '$po_approval_hdr_id' ";
				
			mysqli_query($con, $sql);
		}
		else {
				echo '<script>alert("Only single vendor selection is allow...")</script>';
		}	
		
			$sql	="Select * from sma_purchase_order where id ='$po_approval_hdr_id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$company_id 		= $row['project'];
			$po_number 			= $row['po_number'];
			$subject 			= $row['subject'];
			$supplier_id		= $row['to_supplier'];
			
			$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
									
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject. ','. $party_name;
		    $affect 		= 'Vendor Modified ';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$po_approval_hdr_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
		
}

if($_POST['editSave']){

		$rid     		= $_POST['rid'];
		$purchase_id 	= $_POST['purchase_id'];
		$product_id		= $_POST['product_id'];
		$description 	= str_replace("'",'',$_POST['itemdescription']);
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		$budget_id		= $_POST['budget_id_curr'];
		$first_insert	= $_POST['first_insert'];
		$deliverydate	= $_POST['deliverydate'];	
		
		$itemtds_id			= $_POST['itemtds_id'];	
		
		$quantity 		= $_POST['itemquantity'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$itemrate_prev 			= $_POST['itemrate_prev'];
		
		//$gst 			= $_POST['itemGST_e'];;
		$gst_id			= $_POST['itemgst_id'];
//ECHO $description. ' ' . $units . ' ' .$rate. ' ' . $gst;
//exit();
		
		$sql = "update `sma_po_items` set delivery_date	= '$deliverydate' , 
		                                    product_desc = '$description',
		                                    uom = '$units'
				                    where purchase_id = '$purchase_id' and id = '$rid' ";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);

	if($rate>0){
		
		$po_type		= $_POST['po_type'];
		
		$sql 	= "select * from account_mst where 1 and id = '$itemtds_id' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$tds 			= $r22['percentage'];
		$tds_id			= $r22['id'];	

		//$sql 	= "select * from gst_mst where 1 and igst = '$gst' ";
		$sql 	= "select * from gst_mst where 1 and id = '$gst_id' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$gst_id 		= $r22['id'];
		$gst 			= $r22['igst'];
						
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
		
		$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		$approval_memo_ref 	= $r2['approval_memo_ref'];
//echo $po_quantity .' > '. $ap_quantity .' || ' . $po_value .' > ' . $ap_value;		
		if( ($po_quantity  > $ap_quantity || $po_value > $ap_value ) && $po_type !='C' ){
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
		
		if($itemrate_prev>0){
			$gstamt_prev 		= round((($itemrate_prev * $quantity) * $gst / 100),0);
		}
		
		$gstamt 		= round((($rate * $quantity) * $gst / 100),0);
		
			
		if($budget_control_gst=='N'){
			$gstamt = 0 ;
			$gstamt_prev = 0;
		}		
		
		if($itemrate_prev>0){
			$amount_prev			= ($itemrate_prev * $quantity) + $gstamt;
		}
		
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
			$errmsg = 'Budget Group must be select.....';
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
		$product_name 	= $r1['name'];
		$category 		= $r1['category'];
		
//		if($po_type=='C'){
			
 			$sql   = "SELECT * FROM sma_budget where id = '$budget_id' ";	
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$budget_name 		= $r2['budget_name'];
			$budget_head 	    = $r2['budget_head'];
			$total_budget		= $r2['total_budget'];
			$blocked_budget		= $r2['blocked_budget'];
			$used_budget		= $r2['used_budget'];
			$adjustment_budget	= $r2['adjustment_budget'];
			$check_budget		= ($total_budget + $adjustment_budget) - ($block_budget + $used_budget) + $amount_prev;
//echo $check_budget. ' < ' . $amount;	

			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";		
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$admin_flag 		= $r2->admin_flag;
			$budget_head_name 	= $r2->budget_head;
									
			if ($check_budget < $amount && $admin_flag !='Y' ){
				$_SESSION['budget_id'] ='';	
				echo "<script>alert('Insufficient Budget for Product Name $product_name')</script>";
				$errmsg = 'Insufficient Budget for Product Name :' . $product_name . ' / Budget Group : ' . $budget_head_name;
				echo $errmsg;
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888&errmsg='.$errmsg;
				echo "<meta http-equiv='refresh' content='0'>";    
				echo "<script>window.location.href='$baseurl1';</script>";
				exit();	
			}
			
			//31-03-2024 $sql = " update sma_budget set blocked_budget = blocked_budget + $amount - $amount_prev where id = '$budget_id' ";
//echo $sql."<BR>";			
			//31-03-2024 mysqli_query($con, $sql);
					
//		}


//, tds = '$tds', tds_id = '$tds_id'

		if($first_insert=='F'){
			$sql = "update `sma_po_items` set quantity 		= '$quantity', 
											unit_rate		= '$rate', 
											gst 			= '$gst',
											gst_id 			= '$gst_id',
											first_insert	= ''
				where purchase_id = '$purchase_id' and id = '$rid' ";				
//echo $sql."<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else {
			$sql = "update `sma_po_items` set quantity 		= quantity - '$quantity_prev' + '$quantity' ,
											unit_rate		= '$rate', 
											gst 			= '$gst',
											gst_id 			= '$gst_id',
											first_insert	= ''
				where purchase_id = '$purchase_id' and id = '$rid' ";				
//echo $sql."<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
//Check value here //
		if($category=='M'){
			if($first_insert=='F'){
				$sql = "UPDATE sma_purchase_req_items SET po_quantity = '$quantity' , 
						po_value= po_value+ $amount - $amount_prev 
						WHERE purchase_req_id = '$approval_memo_ref' and product_id = '$product_id'";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			else {
				$sql = "UPDATE sma_purchase_req_items SET po_quantity = po_quantity - '$quantity_prev' + '$quantity' , 
						po_value= po_value+ $amount - $amount_prev 
						WHERE purchase_req_id = '$approval_memo_ref' and product_id = '$product_id'";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
		}
		else if($category=='S'){
		
			$amount = round($rate * $quantity,0);
			$amount_prev = round($rate_prev * $quantity_prev,0);
			if($first_insert=='F'){
				$sql = "UPDATE sma_purchase_req_items SET po_quantity = '$amount' , 
						po_value= po_value+ $amount - $amount_prev 
						WHERE purchase_req_id = '$approval_memo_ref' and product_id = '$product_id'";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			else {
				$sql = "UPDATE sma_purchase_req_items SET po_quantity = po_quantity - '$amount_prev' + '$amount' , 
						po_value= po_value+ $amount - $amount_prev 
						WHERE purchase_req_id = '$approval_memo_ref' and product_id = '$product_id'";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
		}
//echo $sql. "<BR>";
		
		$sql = "select sum((quantity * unit_rate) + (((quantity * unit_rate) * gst) /100)) as total_po_amount from sma_po_items where purchase_id = '$purchase_id' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$total_po_amount 		= $r22['total_po_amount'];
		
		$sql = " UPDATE sma_purchase_order set total_po_amount = '$total_po_amount' where id = '$purchase_id' "; 
		mysqli_query($con, $sql);
	
	}
	
			$sql	="Select * from sma_purchase_order where id ='$purchase_id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$company_id 		= $row['project'];
			$po_number 			= $row['po_number'];
			$subject 			= $row['subject'];
			$supplier_id		= $row['to_supplier'];
									
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject. ','. $product_name;
		    $affect 		= 'Product Modified ';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
	
//exit('TESTING EXIT...');			
	$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888';
	//echo $baseurl1;
	//exit();
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
	//echo "<script>window.location.href='purchase_order.php?sub=edit&id=$purchase_id&active=active&888';</script>";
		exit();
		
	}
	
	
	if($_GET['sub']=='Save'){
			$id				= $_POST['id']; 
			$po_id			= $_POST['id']; 
			$purchase_id		= $_POST['id']; 
			$po_number			= $_POST['po_number']; 
			$po_type			= $_POST['po_type'];
			$po_doc_type		= $_POST['po_doc_type'];
		//	$approval_memo_ref	= $_POST['approval_memo_ref'];
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
			$tender_no			= $_POST['tender_no'];
			$without_tender_flag= $_POST['without_tender_flag'];
			
		//	$status				= $_POST['location'];
			$delivery_date		= date('Y-m-d', strtotime($_POST['delivery_date']));
			$terms				= $_POST['terms'];
			
		//	$replaceClosingTag = str_replace('</p>', '<br>', $terms);
        //    $terms = str_replace('<p>', '', $replaceClosingTag);

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
			
				//po_number			= '$po_number', 
  			$sql="update sma_purchase_order set dated				= '$dated',
						project				= '$project',
						trans_type			= '$trans_type',
						po_doc_type			= '$po_doc_type',
						location			= '$location',
						delivery_address    = '$delivery_address',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						quotation_reference_no	= '$quotation_reference_no',
						to_supplier			= '$to_supplier',
						delivery_days		= '$delivery_days',
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
						supplier_location	= '$supplier_location',
						tender_no			= '$tender_no',
						without_tender_flag = '$without_tender_flag'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject;
		    $affect 		= 'Modified';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project','$description','$affect')";
		    mysqli_query($con, $sql);
			 
			
			if( !empty($approver_1) && $status == 'Draft' ){
				//$PO_id = $id;	
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_purchase_order set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
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
				
				$sql = "SELECT * from sma_po_items where purchase_id = '$po_id'";								
				$result = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$value="";
				while($row2 = mysqli_fetch_array($result)){
										
					$po_item_id 	= $row2['id'];
					$budget_err 	= $row2['budget_err'];
					$qty 			= $row2['quantity'];
					$pr_quantity 	= $row2['pr_quantity'];
					$bal_si_qty		= $row2['bal_si_qty'];
					$bal_si_amount	= $row2['bal_si_amount'];
					$budget_id 		= $row2['budget_id'];
									
				// 	$sql="SELECT * FROM sma_product_cost_center where company_id = '$company_id' and product_id = '$product_id' ";
				// 	$res2 = mysqli_query($con, $sql);
				// 	echo mysqli_error($con);
				// 	$cat = mysqli_fetch_array($res2);
				// 	$budget_id_v = $cat['budget_id'];
																			
				// 	$sql = "SELECT * from sma_budget_subgroup where id = '$budget_id_v' ";		
				// 	$q2  = mysqli_query($con, $sql);
				// 	$r2 = mysqli_fetch_object($q2);
				// 	$budget_head = $r2->id;
				// 	$budget_name = $r2->budget_name;
											
				// 	$sql = "SELECT * FROM sma_budget where project = '$company_id' and budget_name = '$budget_name' and budget_head = '$budget_head' and account_year = '$fin_year'  ";
				// 	$res2 		= mysqli_query($con, $sql);
				// 	echo mysqli_error($con);
				// 	$cat 		= mysqli_fetch_array($res2);
				// 	$budget_id_a   = $cat['id'];
					
					$sql  = "SELECT * FROM sma_budget where id = '$budget_id' ";
					$res2 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$cat  = mysqli_fetch_array($res2);
					$budget_id   		= $cat['id'];
					$cost_center 		= $cat['budget_head'];							
					$blocked_budget 	= $cat['blocked_budget'];
					$used_budget 		= $cat['used_budget'];
					$adjustment_budget 	= $cat['adjustment_budget'];
					$total_budget 		= $cat['total_budget'] + $adjustment_budget;
					$bal_budget			= $total_budget - ( $used_budget + $blocked_budget ) + $blocked_budget ;
					$balance_budget		= $total_budget - ( $used_budget + $blocked_budget );
												
					if($balance_budget<0){
						$budget_bal_error = 'Y';
					}
												
					$rate 	= $row2['unit_rate'];
					$gst	= $row2['gst'];
					$gstamt = round((($qty * $rate) * $gst / 100),2);
									
					$amount =  round($qty * $rate,2);
												
					if($budget_control_gst=='N'){
						$gstamt=0;
					}
												
					$amount =  round($amount + $gstamt,0);
					
					$sql = " UPDATE sma_budget SET blocked_budget = blocked_budget + $amount WHERE id = '$budget_id' ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
//echo $sql. "<BR>";			
					$sql = " UPDATE sma_purchase_order SET no_budget = 'Y' where id = '$po_id' ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
//echo $sql. "<BR>";
					
				}
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
		
				//$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");

				include "po_mail.php";
				
			}
			
			// add attachments
			// file upload
			$flpath 			= $po_id.'_'.$comp_code;
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST["share_point_link"];
			$arrFUDoc 			= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++){
				$folder_path = "uploads/PO/" . $flpath;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename 	= $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				$tmpDocType = $arrFUDoc['arrDocType'][$i];
				
				if(!empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, share_point_link, doc_type, doc_desc, reference_id, date_uploaded) 
					VALUES('PO', '$filename', '$folder_path', '$share_point_link[$i]', '$arrDocType[$i]', '$arrDocDesc[$i]', '$po_id','now()' )";
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, $folder_path . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
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
					if( !empty($filename )){
					    $sql = "INSERT INTO file_uploads (module, dms_module, file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded) 
					                VALUES('PO', 'IN', '$filename', '$folder_path', '$arrDocType', '$po_id', '$reference_id', now())";
					    mysqli_query($con, $sql);
					}
					//echo $sql. "<BR>";
					
					//exit();
				}
			}
//DMS Doc upload	
			
			$sql = "select sum((quantity * unit_rate) + (((quantity * unit_rate) * gst) /100)) as total_po_amount from sma_po_items where purchase_id = '$purchase_id' ";
			$q22 	= mysqli_query($con, $sql);
			$r22 	= mysqli_fetch_array($q22);
			$total_po_amount 		= $r22['total_po_amount'];
			
			$sql = " UPDATE sma_purchase_order set total_po_amount = '$total_po_amount' where id = '$purchase_id' "; 
			mysqli_query($con, $sql); 
			
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$_SESSION['budget_id'] ='';
		$id = $_GET['id'];
		$po_id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$po_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

	
	$status 	= $row['status'];
	$del 		= $row['del'];
	$new_po_no 	= $row['new_po_no'];
	$po_new_no 	= $row['po_new_no'];
	$old_po_no 	= $row['old_po_no'];
	$no_budget	= $row['no_budget'];
	
		$dated  	= date('d-m-Y', strtotime($row['dated']));
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
		$_SESSION['finance_year'] = $fin_year;
		
		
	$approver_1 		= $row['approver_1'];
	$approver_2 		= $row['approver_2'];
	$approver_3 		= $row['approver_3'];
	$approver_4 		= $row['approver_4'];

	$approver_1_status 	= $row['approver_1_status'];
	$approver_2_status 	= $row['approver_2_status'];
	$approver_3_status 	= $row['approver_3_status'];
	$approver_4_status 	= $row['approver_4_status'];
				
	$approval_status = $row['approval_status'];
	$po_amend 		 = $row['po_amend'];
	
	$readonly = '';
	if (($status == 'Submitted' ) || $status == 'Completed' || $status == 'Suspend'){
		$readonly = 'READONLY';
	}
	/* if ( $user=='Admin' && $status != 'Draft' ){
		$readonly = '';
	}
	 */
	if($approval_status=='Rejected' || $status == 'Completed' || $status == 'Closed' || $status == 'Auto Closed' ){
		$readonly = 'READONLY';
	}
	
	if($del =='Y'){
		$readonly = 'READONLY';
	}
	
	$readonlyam = '';

	if($old_po_no>0){
		$readonlyam = 'READONLY';
		
	}
	
	if( $old_po_no>0 && $status == 'Draft' ){
		$readonly = '';
	}
	
	/* if($po_id == 1458){
		$readonly = '';
	} */
	echo $readonly. ">><<<BR>";
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
	//echo $sql. ' ' . $approver_6;					
						$status   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
						
						if($status=='Blocked'){
							$status = 'Order Completed';
						}
						
						?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $status;?></b></h4> </span>
						
						<?php $approval_status = $row['approval_status']; 
						if($approval_status=='Rejected'){
						?>
							<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['approval_status'];?> &nbsp;&nbsp;&nbsp;&nbsp;</b></h4> </span>
							
						<?php } ?>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
						
		            <?php if($status=='Completed'){ ?>    
						<span class="pull-right"><a href="pur_order_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1&print_flag=M" class="btn btn-info " target="_blank" onclick="return confirm('Do you want to send email to vendor?');" >Mail </a>&nbsp;&nbsp;&nbsp;</span>
					<?php } ?>
					
				<?php $id_v = $row['id'];
				if($id_v==781123){
				?>	
						<span class="pull-right"><a href="pur_order_prn_781.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1&print_flag=V" class="btn btn-success " target="_blank" >Print </a>&nbsp;&nbsp;&nbsp;</span>
				<?php } 
					else {
				?>		
						<span class="pull-right"><a href="pur_order_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&location=<?php echo $row['location'];?>&r=1&print_flag=V" class="btn btn-success " target="_blank" >Print </a>&nbsp;&nbsp;&nbsp;</span>
				<?php } ?>		
						
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<input type="hidden" id="PO_ID" value="<?php echo $row['id'];?>">
						
						<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
						
				<div class="box-body">		
				<?php
					$purchase_id = $row['id'];
					$our_po_ref_no = $row['id'];
					
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
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" > Order </a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab" >Terms</a></li>
						<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>
				
				
						<!--<li><a href="po_approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['project'];?>&vw=Y" class="btn btn-success"  target="_blank" > View </a></li>-->
						
					<?php
						if( ($status == 'Completed'  || $po_amend =='Y' ) ){	
					?>
						<li><a href="#tab_5" data-toggle="tab"  class="btn btn-danger" id="five_tab" >PO Amendment</a></li>
					<?php
						}
					?>	
				<?php 		
					$sql ="SELECT * FROM `sma_supplier_invoice`
								WHERE our_po_ref_no = '$our_po_ref_no' and del !='Y' and status in ( 'Submitted','Completed' , 'Draft' ) "; 
					$q21  = mysqli_query($con, $sql);
					$rowaffect_si = mysqli_affected_rows($con);
					if($rowaffect_si>0){
				?>						
				  <li><a href="#tab_10" data-toggle="tab" class="btn btn-info" >Invoice Against PO</a></li>
				<?php  } ?>	
						
					<?php 		
					
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
						
					$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c where a.po_ref_no  = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.del !='Y'  ";
					$q21 = mysqli_query($con, $sql);
					$rowaffect_po = mysqli_affected_rows($con);
					if ($rowaffect_po == 0){
						$sql = "SELECT b.*, c.*  FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and a.advance_flag = 'Y' and b.del !='Y'  ";					
						$q21  = mysqli_query($con, $sql);
						$rowaffect_po = mysqli_affected_rows($con);
					}
										
					if($rowaffect_po>0){
				?>						
				  <li><a href="#tab_6" data-toggle="tab" class="btn btn-info" id="six_tab" >Advance Payment</a></li>
			<?php  } ?>	  	
			<?php 
					$sql ="SELECT b.*, c.* FROM `sma_supplier_invoice` a, payment_header b, payment_details c , sma_purchase_order d
							WHERE a.our_po_ref_no = '$our_po_ref_no' and a.our_po_ref_no = d.id 
								and a.id = c.supp_id and b.id = c.payment_hdr_id and b.st_flag = 'S' 
								and b.del !='Y' and a.status = 'Completed' ";
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect_si = mysqli_affected_rows($con);
					if($rowaffect_si>0){
					?>
				  <li><a href="#tab_7" data-toggle="tab" class="btn btn-info" id="seven_tab" >SI Payment</a></li>
			<?php  } ?>	
					
						
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
								$comp_code = $r2['comp_code'];
								
									$vendor_id = $row['to_supplier'];								
									$sql 	= "select * from sma_party_mst where id = '$vendor_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$party_name = $r2['party_name'];
									$party_pan_number = $r2['party_pan_number'];
								 	$party_pan_number_v = substr($party_pan_number,3,1);
							//echo $party_pan_number. ' ' . $party_pan_number_v. ">>><<";	
									$label_line .= '<b>PO : </b>'. $row['po_number']. ' &nbsp; '. ' <b> Doc SrNo:</b>'.$row['id']. ' ' .
												' <b>Company:</b>'.$comp_code. ' ' . ' <b>Supplier Name :</b> ' .' '.$party_name;
									
									$po_doc_type = $row['po_doc_type'];	

								
						?>
						<br>
						<div class="form-group">
					<?php
						$sqlp = "";	
						if($status != 'Draft'){
							$sqlp = " and po_doc_type = '$po_doc_type' ";
						}	
					?>	
							<label for="project" class="control-label col-sm-2">Order Type</label>
							<div class="col-sm-2">
								
								<select class="form-control " name="po_doc_type" id="po_doc_type" required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
								<!--	<option value="PO" <?php echo ($row['po_doc_type'] == "PO" )?'selected="selected"':'';?> > Purchase Order </option>
									<option value="SO" <?php echo ($row['po_doc_type'] == "SO" )?'selected="selected"':'';?> > Service Order </option>
									<option value="WO" <?php echo ($row['po_doc_type'] == "WO" )?'selected="selected"':'';?> > Work Order </option>
									<option value="CA" <?php echo ($row['po_doc_type'] == "CA" )?'selected="selected"':'';?> > Contract Agreement </option>-->
									<?php $sql = "select * from po_order_type where 1 $sqlp order by po_doc_type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['po_doc_type'];?>" <?php echo ($row['po_doc_type'] == $r2['po_doc_type'])?'selected="selected"':'';?> >  <?php echo $r2['po_doc_desc'];?></option>
									<?php } ?>
								</select>
							</div>
									
									
						<!--	<div class="col-lg-7" style="padding-top: 6px;">
					<?php //if($po_type=='A'){ ?>		
								<span class="label label-warning" style="font-size:14px;color:white;" >
								<input type="hidden" name='po_type' id='po_typea' value='A'  >
								<input type="radio" name='po_type' id='po_typea' <?php echo ($po_type=='A')?"CHECKED":''; ?>  value='A' onclick="getclear(this.value);" > PO Against Approval Memo &nbsp;
					<?php //$doc_type = 'PO';	
							//} ?>		
					<?php //if($po_type=='C'){ ?>			
								<input type="hidden" name='po_type' id='po_typea' value='C'  >
								<input type="radio" name='po_type' id='po_typec' <?php echo ($po_type=='C')?"CHECKED":''; ?>  value='C' onclick="getclear(this.value);" > PO cum Approval Memo &nbsp;
								</span>&nbsp;
					<?php 
							//$doc_type = 'AP';
							//}
					?>
							</div> -->

					<?php //$doc_type = 'PR';
						$doc_type = 'PO';
					?>
					
								<input type="hidden" name='po_type' id='po_typec' value='C' >
								
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
                             		<!--<option value=""> Select </option>-->
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
						
						<?php		
							$to_supplier = $row['to_supplier'];
							$supplier_id = $row['to_supplier'];
							
							$sql = "SELECT b.supplier_name FROM `sma_purchase_order` a, `sma_po_approval_details` b WHERE 1 and a.id = b.po_approval_hdr_id and a.project = '$project' and b.vendor_selected = 'Y' and a.id = '$po_id' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$to_supplier = $r2['supplier_name'];
							
							$sql = "select * from sma_party_mst where id = '$to_supplier' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$party_name = $r2['party_name'];
						if(!empty($to_supplier)){	
								
						?>				
												
						<!--<div class="col-md-3">
								<label class="control-label">To Supplier<span style="color:red;"> **</span></label>
						-->		
							<input type="hidden" name="to_supplier" id="to_Supplier" value="<?= $to_supplier;?>" >
							
						<!--	<input type="text" class="form-control" readonly  value="<?= $party_name;?>" >
						
						</div>-->
						<?php } ?>	
							
						<?php 
						
							$approval_memo_ref = $row['approval_memo_ref'];
							$sqla = " and a.id = '$approval_memo_ref' ";
							
						?>
							
							<div class="col-md-3">
								<label class="control-label">Against NOA Number</label>
								<select class="form-control" name="approval_memo_ref" id="approval_memo_Ref" <?php echo $readonly; ?> readonly onchange="getsupplier(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select a.ap_number, a.id , b.username as username 
												from sma_approval_memo a,  sma_user b 
													where 1 AND a.del !='Y' and a.company = '$company_id'  and b.userid = a.draft_by  $sqla  order by a.id ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_memo_ref'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['ap_number']. ' | ' . $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						<?php  //} ?>	
							
						<div class="col-md-2" >
								<label class="control-label ">Tax Status</label><br>
						<?php //style="padding-top: 6px;"
						
							$location = $row['location'];
							$sql = "select * from sma_location where id = '$location' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$loc_name = $r2['loc_name'];
							$loc_gst_no = $r2['loc_gst_no'];
							$loc_gst_no_twodgt = substr($loc_gst_no,0,2);
							
							$sql = " select * from sma_party_mst where id = '$to_supplier' ";
							$q2  = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_object($q2);
							$party_gst_number = $r2->party_gst_number;
							$party_gst_number_twodgt = substr($party_gst_number,0,2);
					//echo $party_gst_number_twodgt. ' == ' .$loc_gst_no_twodgt;
					
							if($party_gst_number_twodgt==$loc_gst_no_twodgt){
								$supplier_location_dis = 'Local';
								$supplier_location = 'L';
							}
							else {
								$supplier_location_dis = 'Out of State';
								$supplier_location = 'O';
							}	
							/* $supplier_location = $row['supplier_location'];
							if($supplier_location=='L'){
								$supplier_location = 'Local';
							}
							else if($supplier_location=='O'){
								$supplier_location = 'Out of State';
							} */
						?>
								<input type="hidden" name="supplier_location" id="supplier_location" value="<?= $supplier_location;?>" >
								
								<input type='text' class="form-control" readonly value="<?= $supplier_location_dis; ?>">
						
						</div>
														
					
						<?php 
							$company_id = $row['project']; 
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$comp_vertical 		= $r2['comp_vertical'];
							$budget_control_gst = $r2['budget_control_gst'];
						?>
							 
							<div class="col-md-3">
								<label class="control-label">Department<span style="color:red;"> **</span></label>
						<?php
						if($status =='Draft'){
						?>		
								<select class="form-control" <?php echo $readonly; ?> name="department" id="departMENT" required readonly >
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
							<input type="hidden" name="department" id="departMENT" value="<?= $department;?>" >
							<input type="text" class="form-control" readonly  value="<?= $dept_name;?>" >
						<?php }
						
						$sql = "SELECT b.* FROM `sma_purchase_order` a, `sma_po_approval_details` b 
								WHERE 1 and a.id = b.po_approval_hdr_id and a.id = '$po_id' and a.project = '$project' and b.vendor_selected = 'Y' and supplier_name = '$supplier_id' ";
						$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$quotation_reference_no = $r2['quote_ref_no'];
						?>
						
							</div>
						<?php if(!empty($to_supplier)){	?>	
								<input type="hidden" class="form-control" id="quotation_reference_no" name="quotation_reference_no"  <?php echo $readonly; ?> value="<?php echo $quotation_reference_no;?>" >
						<!--	<div class="col-md-3">
								<label class="control-label">Supplier Quote Ref.No.</label>
								<span id="getqref">
								<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no"  <?php echo $readonly; ?> value="<?php echo $quotation_reference_no;?>" >
								</span>
								
							</div>
						-->	
						<?php } ?>
						
								
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
							<div class="col-md-5">
								<label class="control-label">Delivery Address</label>
								<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address" <?php echo $readonly; ?> ><?php echo $row['delivery_address'];?></textarea>
								
							</div>
						</span>
						
					<?php	
						$mrn_id = $row['approval_memo_ref'];
						if(!empty($supplier_id)){
							$sql = "select * from sma_party_mst where id = '$supplier_id' ";
						}
						else {
							$sql = "select a.* from sma_party_mst a, sma_approval_details b where 1 and a.id = b.supplier_name and b.approval_hdr_id = '$mrn_id'  order by a.party_name ";
						}
									
					?>	
							<div class="col-md-5">
								<label class="control-label">To Supplier</label>
								<select class="form-control select2-123" <?php echo $readonly; ?>  required name="supplier_id" >
									
							<?php if($status!='Closed'){ ?>			
									<option value=""> Select </option>
							<?php } ?>	
										<?php 
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
										    $selected = '';
										     $vendor_selected =   $r2['vendor_selected'];  
										     if($vendor_selected=='Y'){
										         $selected = 'SELECTED';
										     }
										?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($supplier_id == $r2['id'])?'selected="$selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                </select>
								
							</div>
							
						</div>
						
						
						<?php
								$po_number 		= $row['po_number'];
								$po_number_v 	= $po_number;
								$po_rev	   		= $row['po_rev'];
								$old_po_no	   	= $row['old_po_no'];
									
								if($po_rev > 0){
									$po_number_v = $po_number.'-'.$po_rev;
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
						//if($po_type=='A'){	
							
							$mrn_id = $row['approval_memo_ref'];
							$baseurl_mrn = $baseurl . "/approval/edit.php?sub=edit&id=$mrn_id";
						?>
							<div class="col-md-2">
								<label class="control-label" style="font-size:14px;" >Document : </label>
								<a href="<?php echo $baseurl_mrn; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >NOA </span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>	
						</div>
						
						<div class="form-group">
							
						<?php $advance_flag = $row['advance_flag']; 
						
						if($advance_flag=='Y'){
							?>
							<div class="col-md-3">
								<label class="control-label"><span style="font-size:14px;text-align:right;">Advance Payment Required?</span></label><br>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($status=='Draft'){ 
						 ?>
								<input type="checkbox" <?= $readonly; ?> id="advance_flag" name="advance_flag" <?php echo ($advance_flag=='Y')?"CHECKED":"";?> value="Y" >
						<?php } 
							else {
								$advance_flagv = '';
								$advance_flagv = $advance_flag;	
								if($advance_flag!='Y'){
									$advance_flagv='N0';	
								}	
						?>		
								<label class="control-label"><?= $advance_flagv;?></label>
								
								<input type="hidden" <?= $readonly; ?> id="advance_flag" name="advance_flag" value="<?= $advance_flag;?>" >
								
						<?php } ?>		
							</div>
						<?php } ?>	
						
							<?php 
							
							$paid_amount 			= $row['paid_amount'];
							$paid_against_invoice 	= $row['paid_against_invoice'];
							$tota_paid_amt			= round($paid_amount + $paid_against_invoice,0);
							
							if($advance_flag!='Y'){
								$tota_paid_amt		= round($paid_against_invoice,0);
							}
							
							$sql = "SELECT sum(c.payment_adjusted ) as paid_amount FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.our_po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' 
							and a.status = 'Completed' ";
							$res 	= mysqli_query($con,$sql);
							$dep 	= mysqli_fetch_array($res);
							$paid_against_invoice  	 = $dep['paid_amount'];
							
							$sql = "SELECT sum(c.payment_adjusted ) as paid_amount FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' 
							and a.status = 'Completed' and a.advance_flag = 'Y' ";
							$res 	= mysqli_query($con,$sql);
							$dep 	= mysqli_fetch_array($res);
							$tota_paid_amt  	 = $dep['paid_amount'];
							if($advance_flag=='Y'){
								$paid_amount		= round($tota_paid_amt,0);
							}
							
							if($advance_flag!='Y'){
								$sql = "SELECT sum(c.payment_adjusted ) as paid_amount 
											FROM `sma_advance` a, payment_header b, payment_details c 
												WHERE a.po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id 
													AND b.st_flag = 'D' and b.del !='Y' and a.del !='Y' 
														AND a.status = 'Completed' ";
									$res = mysqli_query($con, $sql);
									$dep = mysqli_fetch_array($res);
									$tota_paid_amt = $dep['paid_amount'];
									$paid_amount = round($tota_paid_amt, 0);
							}
							
							$tota_paid_amt			= round($paid_amount + $paid_against_invoice,0);
							
							$sql = " SELECT purchase_id, quantity, unit_rate, gst, sum((quantity * unit_rate) + ((( quantity * unit_rate) *  gst) / 100 ) ) as po_total  
								FROM sma_po_items WHERE purchase_id = '$our_po_ref_no' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$po_total = $r2['po_total'];
					//echo $po_total . ' - '. $tota_paid_amt. ' ' . $paid_amount .' + '. $paid_against_invoice;
							$tota_paid_amt = round($po_total- $tota_paid_amt,0);
							
							if($paid_amount >0 ){
							//&& $advance_flag=='Y'	
								?>
							<div class="col-md-2">
								<label class="control-label">Advance Paid</label>
								<input type="text" class="form-control" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo moneyFormatIndiaa($paid_amount);?>" >
							</div>
							<?php 
							}
							
							if($paid_against_invoice >0){?>
							<div class="col-md-2">
								<label class="control-label">Adjusted Against Invoice</label>
								<input type="text" class="form-control" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo moneyFormatIndiaa($paid_against_invoice);?>" >
							</div>
							<?php 
								}
								//&& $advance_flag=='Y'
							if($tota_paid_amt >0 ){?>
							<div class="col-md-2">
								<label class="control-label">Balance Advance</label>
								<input type="text" class="form-control" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo moneyFormatIndiaa($tota_paid_amt);?>" >
							</div>
							<?php 
								}	
							?>
							
							<?php
					
						$sql = "SELECT distinct(b.id) as pay_no, st_flag, b.utr_no FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$po_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status = 'Completed' ";
//echo $sql. "<BR>";
							$q2  = mysqli_query($con, $sql);
							$affectrow  = mysqli_affected_rows($con);
							if($affectrow>0){
								echo "<span class='label label-warning' style='font-size:14px;' > Payment</span>-";
							}	
							while($r2  = mysqli_fetch_array($q2)){
								$utr_no 	= $r2['utr_no'];
								$paid_date 	= $r2['paid_date'];	
								$pay_no		= $r2['pay_no'];
								$baseurl_py = $baseurl . "payment/edit.php?sub=edit&id=$pay_no";
								echo "<a href='$baseurl_py;' target='_blank'><span class='label label-warning' style='font-size:14px;' >".$pay_no .' </span></a>&nbsp;&nbsp;';
							}
					?>				
							
						</div>	
					
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
								
				<?php 
					//$po_type='CC';
				?>	
						<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
								        <h4 class="box-title">Supplier Name / Vendor Comparison **</h4>
										<?php $data_mode = 'Add';?>
						<?php  
							if( $status == 'Draft' ){
								$readonly = '';
							}
				
							if($status=='Draft'){ ?>				
                                        <span class="pull-right">
                                            <!--<a href="#"-->
                                            <!--   class="btn btn-primary" data-mode='Add'-->
                                            <!--   data-toggle="modal" data-target="#modalAddVendor<?php echo $data_mode;?>">Add-->
                                            <!--</a>-->
                                        </span>
						<?php } ?>				
								    </div>
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
								            <thead>
                                            <tr>
                                                
                                                    <th style="text-align:left">Supplier Name</th>
													<th style="text-align:left">GST Number</th>
													<th style="text-align:left">Quote Ref.No.</th>
													<th style="text-align:left">Vendor Selected</th>
													<th style="text-align:right">Value</th>

													<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
								            <tbody id="prItemsTableBody">
									<?php
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
												$party_gst_number  = $r2['party_gst_number'];
												
												$party_id_doc   = '';
												$selected = '';
												$vendor_selected = $rowd['vendor_selected'];
												if($vendor_selected=='Y'){
													$party_kyc  	= $r2['party_kyc'];
													$party_kyc_v	= $party_kyc;
													
													$selected = 'Selected';
													$selected_vendor_value = $rowd['values'];
													
													$party_id_doc   = $r2['id'];
													
												}
									
											?>
										
                                            <tr>
                                 
                                            	<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
												<td width="10%" style="text-align:left"><?php echo $party_gst_number;?></td>
												
												<td width="20%" style="text-align:left"><?php echo $rowd['quote_ref_no'];?></td>
												<td width="10%" style="text-align:left"><?php echo $selected;?></td>
												<td width="10%" style="text-align:right"><?php echo moneyFormatIndiaa($rowd['values']);?></td>
												<td width="10%" style="text-align:right">
												<a href='#modalEditItemq' data-id='<?php echo $approval_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItemq<?php echo $approval_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_vendor_func.php"; ?>							
<!-- Modal Edit Item-->														
												<?php  if (empty($readonly)){ ?>
														<!--<a href='#modalDeleteItem' id='delete-<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>' data-toggle='modal' data-id='<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>' data-target='#modalDeleteItem<?php echo $po_approval_hdr_id;?><?php echo $approval_srno;?>'><i class='fa fa-trash-alt'></i></a>-->
														</td>
												
<!-- Modal Delete Item-->								
												<?php include "del_vendor_func.php"?>
<!-- Modal Delete Item-->
												<?php } ?>
												
											</tr>
									<?php 		}
										?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
										
										<input type="hidden" id="selected_vendor_value" name="selected_vendor_value" value="<?php echo $selected_vendor_value;?>" >
										
                                    </div>
                                </div>
                            </div>
							
<!--Vendor Comparision --> 
							
						<div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
						
                            <h4 class="box-title">Product Details</h4>
							
							<?php if (empty($readonly) ){ ?>
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
							
							<?php echo "<b style='color:grey;'>Note: Please provide qty against the items, which you want to order in this PO.  Keep qty ZERO, if you dont want to include in this order</b>"; 
								
							?>	
							
                                    <table id="prItemsTable123" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
										<th>Unit</th>
                                        <!--<th style="text-align:right;">NOA Qty/Value</th>-->
										<th style="text-align:right;">PO.Qty/Value</th>
										<!--<th style="text-align:right;">NOA Bal.Qty/Value</th>-->
										<th style="text-align:right;">Invoice Qty/Value</th>
                                        <th style="text-align:right;">Rate</th>
										<th style="text-align:right;">GST%</th>
										
                                        <th style="text-align:right;">Value</th>
										
										<th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="prItemsTableBody">
									<?php	
									//$purchase_id = $row['id'];
									$budget_bal_error ='';
									$po_no		 = $purchase_id;
									
									$sql = "SELECT * from company where comp_id = '$project' ";
									$res = mysqli_query($con, $sql);
									//echo mysqli_error($con);
									$r2 = mysqli_fetch_array($res);
									$budget_control_gst = $r2['budget_control_gst'];
									
									$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' and ( quantity > 0 ) 	";
									mysqli_query($con, $sql);
									$items_cnt = mysqli_affected_rows($con);
									
									$sql = "SELECT * from sma_po_items where purchase_id = '$purchase_id'";
//echo $sql ."<BR>";									
									$result = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$value="";
									while($row2 = mysqli_fetch_array($result)){
									
										$po_item_id 	= $row2['id'];
										$budget_err 	= $row2['budget_err'];
										$qty 			= $row2['quantity'];
										$pr_quantity 	= $row2['pr_quantity'];
										$bal_si_qty		= $row2['bal_si_qty'];
										$bal_si_amount	= $row2['bal_si_amount'];
										$product_desc   = $row2['product_desc'];
												
										$bal_qty 		= $pr_quantity - $qty;
						//	echo $bal_qty ."<BR>";			
										if($bal_qty==0 && ( $status =='Submitted' || $status =='Completed' ) ){
										//	continue;
										}
										
										$unit 			= $row2['uom'];
										$product_id 	= $row2['product_id'];
										$sql="SELECT * FROM sma_product where id = '$product_id' ";
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$mat = mysqli_fetch_array($res2);
										$product_name = $mat['name'];
										$gst_type	  = $mat['gst_type'];
										$product_category = $mat['group'];
										$category 			= $mat['category'];
										$budget_id_v		= $mat['budget_head'];
										if($category =='S'){
											$category_v = 'Service';	
										}
										else if($category =='M'){
											$category_v = 'Material';	
										}
										
										
										if(empty($unit)){
											$unit = $mat['uom'];
										}	
										
										$budget_id 	= $row2['budget_id'];
							
							//start
								if( $status=='Draft' ){
										//$budget_id =='0' or
								// 		$sql="SELECT * FROM sma_product_cost_center where company_id = '$company_id' and product_id = '$product_id' ";
								// 		$res2 = mysqli_query($con, $sql);
								// 		echo mysqli_error($con);
								// 		$cat = mysqli_fetch_array($res2);
								// 		$budget_id_v = $cat['budget_id'];
						//echo $sql."<BR>";										
																	
										$sql = "SELECT * from sma_budget_subgroup where 1 and id = '$budget_id_v' ";		
										$q2  = mysqli_query($con, $sql);
										$r2 = mysqli_fetch_object($q2);
										$budget_head = $r2->id;
										$budget_name = $r2->budget_name;
										$admin_flag = $r2->admin_flag;
									
										//$fin_year = '2024-2025';
										$sql = "SELECT * FROM sma_budget where project = '$company_id' and budget_name = '$budget_name' and budget_head = '$budget_head' and account_year = '$fin_year'  ";
										$res2 		= mysqli_query($con, $sql);
						//echo $sql."<BR>";				
										echo mysqli_error($con);
										$cat 		= mysqli_fetch_array($res2);
										$budget_id_a   = $cat['id'];
										
										
										if($budget_id_a != $budget_id){
											$sql = " UPDATE sma_po_items set budget_id = '$budget_id_a' where id = '$po_item_id' ";	
											$budget_id	   = $cat['id'];
											mysqli_query($con, $sql);
										}
										//echo $sql."<BR>";		
									}
										
							// End	
							
										$sql  = "SELECT * FROM sma_budget where id = '$budget_id' ";
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$cat  = mysqli_fetch_array($res2);
										$budget_id   = $cat['id'];
										$cost_center = $cat['budget_head'];
										
										$blocked_budget 	= $cat['blocked_budget'];
										$used_budget 		= $cat['used_budget'];
										$adjustment_budget 	= $cat['adjustment_budget'];
										$total_budget 		= $cat['total_budget'] + $adjustment_budget;
										$bal_budget			= $total_budget - ( $used_budget + $blocked_budget ) + $blocked_budget ;
										$balance_budget		= $total_budget - ( $used_budget + $blocked_budget );
										
										if($balance_budget<0 && $admin_flag !='Y' ){
											$budget_bal_error = 'Y';
										}
										
										$rate 	= $row2['unit_rate'];
										$gst	= $row2['gst'];
										$gstamt = round((($qty * $rate) * $gst / 100),2);
										
										$amount =  round($qty * $rate,2) ;
										
										$amount =  round($amount + $gstamt,0);
										//echo $amount. ' >><< ' . $bal_si_amount. ' <<>> ' . $gstamt;
										
										$bal_amount = round($amount - $bal_si_amount,0);
										
										
										$po_amount = $amount;
										
								// 		if($budget_control_gst=='N'){
								// 			$sql = "SELECT round(sum((quantity * unit_rate)),2) as item_value from sma_po_items where 1 and budget_id = '$budget_id' and purchase_id = '$purchase_id'";
								// 			$res3 = mysqli_query($con, $sql);
								// 			echo mysqli_error($con);
								// 			$r3  = mysqli_fetch_array($res3);
								// 			$item_value = $r3['item_value'];
								// 			//echo $sql. "<BR>";	
								// 			$po_amount_v = $amount - $gstamt;
								// 		}
								// 		else if($budget_control_gst=='Y'){
											$sql = "SELECT round(sum((quantity * unit_rate) + ((quantity * unit_rate ) * gst / 100)),2) as item_value from sma_po_items where 1 and budget_id = '$budget_id' and purchase_id = '$purchase_id'";
											$res3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r3  = mysqli_fetch_array($res3);
											$item_value = $r3['item_value'];
											//echo $sql. "<BR>";	
								// 		}
									
										if($budget_control_gst=='N'){
											$gstamt=0;
										}
										$check_amount = round(($qty * $rate) + $gstamt,0);
										$tot_amount = $tot_amount + $amount;
//echo $check_amount . ' > ' . $bal_budget;
										$cc_aop_error= '';				
										 if( ($check_amount > $bal_budget || $budget_bal_error =='Y') && (  $status =='Draft' ) && $admin_flag !='Y' ){
											//echo " Insufficient budget !!!"; $status =='Submitted' ||
											$budget_err  	= 'Y';
											$cc_aop_error	= '<BR><span style="color:red;" >'.'Insufficient budget, check product details !!!</span>';
										}
										
										$delivery_date = date('d-m-Y', strtotime($row2['delivery_date']));
										if($delivery_date == '01-01-1970'){
											$delivery_date = '';
										}
										
										$rid = $row2['id'];
										
									$sql = "SELECT * from sma_budget_subgroup where 1 and  id = '$cost_center' ";		
									$q2  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_object($q2);
									$cost_center = $r2->budget_head;
									
										if($status =='Draft'){
//											echo $status."<>";
											$sql = "UPDATE sma_po_items set balance_budget= ('$balance_budget' + '$check_amount'), total_budget = '$total_budget'
													where id = '$po_item_id' ";
											mysqli_query($con, $sql);
											echo mysqli_error($con);
										}	
									//echo $balance_budget.' + '.$check_amount . ' ' . $total_budget	;
									
									?>	
									<?php 
										if( $budget_err == 'Y' && $status =='Draft' && $admin_flag !='Y' ){ 
											if($check_amount > $bal_budget){
									?>
											<tr>
												<td width='94%' colspan='9' style="color:red;	"><?php echo 'Insufficient Budget for  Product Name : ' . $product_name . '  Budget Group : '. $cost_center; ?></td>
												<td width='6%'></td>
											</tr>
									<?php 	}
											else {
												$sql = "update sma_po_items set budget_err = '' where id = '$rid' ";
												mysqli_query($con, $sql);
											}	
										}
										
										$unit_rate = $row2['unit_rate'];	
										if($unit_rate>=1){
											$unit_rate = moneyFormatIndiaa($row2['unit_rate']);
										}
										else {
											$unit_rate = number_format($unit_rate,2);
										}	
										
										if($category =='S'){
											//$bal_si_qty = $bal_si_amount;
											//$unit_rate = 1;
										}
										else if($category =='M'){
										    //$bal_si_qty = $qty;	
											
										}
										
					//	echo				$sql  = "SELECT * FROM sma_budget where id = '$budget_id' ";					
						//echo $status. ' ' .				$no_budget. ">><< <br>";
									if( ( $status=='Draft' ) || ($status=='Submitted' && $no_budget =='Y' ) ){
										$sql  = "SELECT * FROM sma_budget where id = '$budget_id' ";			
										$res2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$cat  = mysqli_fetch_array($res2);
										//$budget_id   = $cat['id'];
										//$cost_center = $cat['budget_head'];
										
										$blocked_budget 	= $cat['blocked_budget'];
										$used_budget 		= $cat['used_budget'];
										$adjustment_budget 	= $cat['adjustment_budget'];
										$total_budget 		= $cat['total_budget'] + $adjustment_budget;
										$balance_budget		= $total_budget - ( $used_budget + $blocked_budget );
										if($no_budget =='Y'){
											$balance_budget = $balance_budget + $item_value;
										}	
										$cc_aop_error= '';	
										if($balance_budget<=0){
											$budget_bal_error = 'Y';
											$error_budget_code = 'Y';
											$budget_err   = 'Y';
											$cc_aop_error = '<BR><span style="color:red;" >'.'Insufficient budget, check product details !!!</span>';
										}
								//echo $balance_budget .' < '. $po_amount_v . ' ' .$item_value; 
										if($balance_budget < $item_value){
										   // echo $balance_budget .' < '. $po_amount_v . ' ' .$item_value; 
											$budget_bal_error = 'Y';
											$error_budget_code = 'Y';
											$budget_err   = 'Y';
											$cc_aop_error = '<BR><span style="color:red;" >'.'Insufficient budget, check product details !!!</span>';
										}
										
									}	
										
										$tds 	= $row2['tds'];
										$tds_id = $row2['tds_id'];
										$sql = "SELECT * FROM `account_mst` where tds_flag = 'Y' and account_type = 'D' and id = '$tds_id' ";
										$q22 	 = mysqli_query($con, $sql);
										$tds_cnt = mysqli_affected_rows($con);
										//$r22     = mysqli_fetch_array($q22);
										//echo $status;
										
									
									?>
										<tr>
											<td width='30%'><?php echo $product_name. '<br>'. substr($product_desc,0,100) .' '.$cc_aop_error;?></td>
											<td width='10%'><?php echo $category_v;?></td>	
											<td width='08%'><?php echo $unit;?></td>	
											<!--<td width='8%' style="text-align:right;"><?php echo $row2['pr_quantity']?></td>	-->
											<td width='10%' style="text-align:right;"><?php echo $row2['quantity'];?></td>
											<!--<td width='8%' style="text-align:right;"><?php echo bcadd($bal_qty,0,2);?></td>-->
											<td width='10%' style="text-align:right;"><?php echo number_format($bal_si_qty,2);?></td>
											
											<td width='10%' style="text-align:right;"><?php echo $unit_rate;?></td>	
											<td width='06%' style="text-align:right;"><?php echo $row2['gst']?></td>
											
											<td width='10%' style="text-align:right;"><?php echo moneyFormatIndiaa($po_amount)?></td>
																					
											<td width='6%'>
												<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
														
<!-- Modal Edit Item-->
											<?php include "edit_func.php"; ?>
<!-- Modal Edit Item-->
											<?php  //if (empty($readonly)){ ?>
													
											<!--<a href="del_poitem.php?sub=delete&po_no=<?php echo $po_no;?>&id_no=<?php echo $rid;?>" title="Delete" onclick="return confirm('Once deleted could not recover this Material, Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
											<?php
											//} ?>							
<!-- Modal Delete Item-->
                                                </td>
											</tr>
											<?php
												
											}
												
											$checker_value = $tot_amount;
												
											?>		

                                            </tbody>
											<?php
											
											if(!empty($error_budget_code)){
												echo '<tr><td colspan="6" style="color:red" >'.$error_budget_code. '</td></tr>';
											}
											
											if($_GET['errmsg']){
												echo '<tr><td colspan="6" style="color:red" >'.$_GET['errmsg']. '</td></tr>';
											}	
											?>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
												
												<th colspan="2">Total Amount</th>
												<th></th>
                                                <th style="text-align:right;"><?php echo moneyFormatIndiaa($tot_amount);?></th>
												
												<th></th>
                                            </tr>
                                            </tfoot>
											
                                        </table>
								<?php $checker_value = $tot_amount; 
								
									if($checker_value > $selected_vendor_value){
										$items_cnt =0;
										//$cc_aop_error= '<BR><span style="color:red;" >'.'Error : Product value should not greater than selected vendor value!!!</span>';
										echo $cc_aop_error;
									}	
									else if ($checker_value > $selected_vendor_value && $checker_value>0 && $selected_vendor_value>0 ){
										$cc_aop_error_v= '<span style="color:red;font-size:16px;font-weight:600;" >'."The vendor's value differs from the product's value, Please check and submit for approval</span>";
										echo '<h4 class="box-title"><center>'.$cc_aop_error_v.'</center></h4>';
									}	
									
									$tendor_error='';
									
								// 	if($selected_vendor_value > 200000 && $tender_no ==0 && $without_tender_flag !='Y' && $status !='Completed'){
								// 		$tendor_error= '<BR><span style="color:red;" >'.'Error : Tendor should be require for greater than 2 Lakh value!!!</span>';
								// 		echo $tendor_error;	
								// 	}	
								?>		
										
										<input type="hidden" id="checker_value" name="checker_value" value="<?php echo $checker_value;?>" >
										
					<?php echo '<span style="color:red;">'.$_SESSION['error_msg']."</span>";?>
                                    </div>
								</div>
							</div>			
						
				
						
					<?php 	
					//if ($po_type=='C'){ ?>
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="background"
                                                  placeholder="Enter text ..."  <?php echo $readonly; ?> ><?php echo stripslashes($row['background']);?></textarea>
                                    </div>
									
									
								</div>
							</div>
						
						<!--<div class="form-group">-->
						<!--		<div class="col-md-12">-->
      <!--                              <div class="box-header"><span class="box-title">Scope of Work</span></div>-->
      <!--                              <div class="box-body">-->
      <!--                                  <textarea class="form-control" id="reason1" name="scope_of_work"  <?php echo $readonly; ?>-->
      <!--                                            placeholder="Enter text ..."><?php //echo stripslashes($row['scope_of_work']);?></textarea>-->
      <!--                              </div>-->
									
									
      <!--                          </div>-->
									
						<!--</div>-->
						
						<!--<div class="form-group">-->
						<!--	<div class="col-md-12">-->
      <!--                          <div class="box-header"><span class="box-title">Deviations from SOP</span></div>-->
      <!--                          <div class="box-body">-->
      <!--                              <textarea class="form-control" id="reason2" name="deviations_from_sop"  <?php echo $readonly; ?>-->
      <!--                                            placeholder="Enter text ..."><?php //echo  stripslashes($row['deviations_from_sop']);?></textarea>-->
      <!--                          </div>-->
						<!--	</div>-->
						<!--</div>-->

						
				<?php //}  ?>
				
				
						
						
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
							$fix_terms = $row['fix_terms'];
							if(empty($fix_terms)){
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2  = mysqli_query($con, $sql);
								$r2  = mysqli_fetch_array($q2);
								$fix_terms = $r2['general_terms'];
							}
						
						$terms = $row['terms'];
						if(empty($terms)){
							$sql = "SELECT * FROM po_order_type WHERE 1 and po_doc_type = '$po_doc_type' ";
							$q2  = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_object($q2);
							$terms	  = $r2->terms;
						}	
						?>	
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header">
									<p><?= $label_line; ?></p>
								<span class="box-title">Special Terms & Condition</span>  <a href="https://syminfotech2-my.sharepoint.com/:w:/g/personal/nitin_syminfotech_com/EURB4w9RshBCtAt0qorDfX4BfgsXQwtDcbGMYVNa-2YrYQ?rtime=1xrCr1bE3Ug" target="_blank" class="btn btn-primary pull-right" > View Word Template</a></div>
								
								<div class="box-body">
									<textarea class="form-control" id="reason3" name="terms" <?php echo $readonly123; ?> ><?= $terms;?></textarea>
								</div>
							
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<div class="box-header">
									<span class="box-title">Fix Terms </span>
								</div>
								
								<div class="box-body">
									<textarea class="form-control" id="reason4" READONLY name="fix_terms" <?php echo $readonly; ?> ><?= $fix_terms;?></textarea>
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
							<p><?= $label_line; ?></p>
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
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PO' AND file_name != '' AND reference_id = " . $po_id;
//						echo $sql;
						
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                           <th width="20%" >Document Type</th>
                                          <th  width="25%" >Description</th>
										  <th  width="25%">Share Point Link
										 
										  </th>
										  <th width="20%">File</th>
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
										      <td width="20%" ><?php echo $document; ?></td>
											  <td width="25%" ><?php echo $doc_desc; ?></td>
                                              <td width="25%" ><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  
											  <td width="20%" > <a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              
                                          <?php //if (empty($readonly) ){ ?>
													
                                              <td width="10%" ><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
										  <?php //} ?>	  
                                          
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
                                            <select class="form-control  doctype"  name="doctype[]"  >
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
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="25%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td width="20%" >
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                        <td width="10%" >
										 
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
							
					<span id="predit" style="color:red;"></span>

							<?php //if($del !='Y'){ ?>
									
						<div class="box-footer">
								
						
					<?php		
							//if($status=='Draft' && $company_id !='9'){
//echo $company_id. "<<>>";								
							
							echo '<div class="col-sm-12">	';
							//$checker_value = 100;
								$sql = " SELECT * FROM `sma_workflow` 
										where company_id = '$company_id'  and '$checker_value' <= to_value and '$checker_value' >= from_value 
										and trans_type = '$trans_type' and doc_type = 'PO' ";
//echo $sql. "<BR>";										
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								
								$approval_role_1 = $rw['approval_role_1'];
								$approval_role_2 = $rw['approval_role_2'];
								$approval_role_3 = $rw['approval_role_3'];
								$approval_role_4 = $rw['approval_role_4'];
								$approval_role_5 = $rw['approval_role_5'];
								$approval_role_6 = $rw['approval_role_6'];
								$approval_role_7 = $rw['approval_role_7'];
								$approval_role_8 = $rw['approval_role_8'];
								
								if($po_id == '1708'){
									$approval_role_1 = $rw['approval_role_2'];
									$approval_role_2 = $rw['approval_role_3'];
									$approval_role_3 = $rw['approval_role_4'];
									$approval_role_4 = $rw['approval_role_5'];
									$approval_role_5 = $rw['approval_role_6'];
									$approval_role_6 = $rw['approval_role_7'];
									$approval_role_7 = $rw['approval_role_8'];
									$approval_role_8 = $rw['approval_role_9'];
								}	
								
								$email_approval_expected_role_1 = $rw['email_approval_expected_role_1'];							
								$email_approval_expected_role_2 = $rw['email_approval_expected_role_2'];
								$email_approval_expected_role_3 = $rw['email_approval_expected_role_3'];
								$email_approval_expected_role_4 = $rw['email_approval_expected_role_4'];
								$email_approval_expected_role_5 = $rw['email_approval_expected_role_5'];
								$email_approval_expected_role_6 = $rw['email_approval_expected_role_6'];
								$email_approval_expected_role_7 = $rw['email_approval_expected_role_7'];
				//echo $sql.'<BR>';
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_1' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_1 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_2' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_2 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_3' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_3 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_4' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_4 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_5' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_5 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_6' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_6 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_7' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_7 = $rw['role'];	
						
								$expected_role_count = 0;
								if(!empty($email_approval_expected_role_1)){
							?>
								<div class="col-md-12">
									<label class="control-label">Before submitting, Email approval to expect from below given person... You cannot submit without ticking the box below.</label><br>
								</div>	
								<?php } ?>
							<?php if(!empty($email_approval_expected_role_1)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_1;?></label><br>
									<input type="hidden" id="email_approval_expected_role_1" name="email_approval_expected_role_1" value="<?= $email_approval_expected_role_1;?>" >
							<?php if($status=='Draft' ){ ?>	
									<input type="checkbox" id="email_approval_received_1" name="email_approval_received_1" <?php echo ($email_approval_received_1=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_1=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>
							<?php if(!empty($email_approval_expected_role_2)){
										$expected_role_count = $expected_role_count + 1;
							?>
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_2;?></label><br>
									<input type="hidden" id="email_approval_expected_role_2" name="email_approval_expected_role_2" value="<?= $email_approval_expected_role_2;?>" >
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_2" name="email_approval_received_2" <?php echo ($email_approval_received_2=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_2=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>	
							
								</div>	
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_3)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_3;?></label><br>
							<?php if($status=='Draft' ){ ?>		
									<input type="checkbox" id="email_approval_received_3" name="email_approval_received_3" <?php echo ($email_approval_received_3=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_3=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_4)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_4;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_4" name="email_approval_received_4" <?php echo ($email_approval_received_4=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_4=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_5)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_5;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_5" name="email_approval_received_5" <?php echo ($email_approval_received_5=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_5=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_6)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_6;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_6" name="email_approval_received_6" <?php echo ($email_approval_received_6=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_6=='Y' ){ ?>	
									<input type="text" readonly  class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_7)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_7;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_7" name="email_approval_received_7" <?php echo ($email_approval_received_7=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_7=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							
						</div>
						
									<div class="col-sm-6">
									
										<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&po_id='.$id ; ?>
													
									<?php 
									//echo $approval_status;
									$cnt 	= 0;
									$sql = "SELECT count(*) as cnt FROM `sma_supplier_invoice` where our_po_ref_no = '$po_id' and del !='Y' ";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$row 	= mysqli_fetch_array($result);
											$cnt 	= $row['cnt'];
											
								//echo $cnt.">><<";
								
										if($del=='Y' && $user=='Admin' ){
											//|| $status=='Submitted'
									?>
											
									<?php
										}
										else if ( ( ( $approval_status=='Rejected' || $user=='Admin' ) && $cnt==0 ) ){
										    //|| ( $user_email == 'daksh.s@nxt-infra.com' && $status=='Completed' ) 
									?>
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
											
									<?php if ( $user=='Admin' || $user=='Admin123' ){ ?>		
										<!--	<a href="#makeAmend" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeAmend">Amend</a>-->
											
									<?php } 
									}
										
										if ( $status=='Completed' ){
											if ( $user=='Admin' || $user=='Admin123' || $user='Daksh123'){
									?>	
											<a href="#makeSuspend" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeSuspend">Suspend</a>
											
											<a href="#makeAmend" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeAmend">Amend</a>
										<?php } ?>	
										
											<a href="#makeClose" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeClose">Close PO</a>
											
									<?php	
										}
																				
									?>
									<span>&nbsp;&nbsp;</span>
									<?php
									if ($status == 'Draft' && $cnt==0 ){
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
										$userid == $approver_4 && $approver_4_status=='Submitted' ){
					
    									if($approver_1_status=='Submitted' && empty($approver_2_status) 
												&& empty($approver_3_status) && empty($approver_4_status) 
    										){
    										$approver_flag='Y';
    									}
    
    									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
											&& empty($approver_3_status) && empty($approver_4_status) ){
    										$approver_flag='Y';
    									}
    									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 
											&& $approver_3_status=='Submitted' && empty($approver_4_status)  ){
    										$approver_flag='Y';
    									}
										if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 
											&& $approver_3_status=='Approved' && $approver_4_status=='Submitted' ){
    										$approver_flag='Y';
    									}
										
									}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								
								}
								// if($party_kyc_v!='Y'){
								// 	echo "Check Vendor KYC !<BR>"; && $party_kyc_v == 'Y'
								// }
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'  ){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								
							?>
									<input type='hidden' id="budget_err" value ="<?= $budget_err;?>" >
									
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<?php	//echo $status; $approval_status ='';//without_tender_flag
									echo $cc_aop_error; 
									
									if(!empty($error_budget_code)){
										echo '<span colspan="7" style="color:red" >'.$error_budget_code. '</span>';
									}
											
								// 	$tendor_error='';
								// 	if($selected_vendor_value > 200000 && $tender_no == 0 && $without_tender_flag !='Y' && $status !='Completed' ){
								// 		$tendor_error= '<BR><span style="color:red;" >'.'Error : Tendor should be require for greater than 2 Lakh value!!!</span>';
								// 	//	echo $tendor_error;	
								// 	}
								//	echo $expected_role_count .' == '.  $approval_received_count. ' '.$error_budget_code; //&& $expected_role_count == $approval_received_count 
									if( $status=='Draft' && empty($error_budget_code) ){ //
									?>	
										<!--<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>-->
										<?php if(  $approval_status != 'Rejected'  ){ //
											
										?>
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
										<?php } ?>
									
								<?php } ?>
                
								<?php if( $approval_status != 'Rejected'  ){ //&& empty($budget_err)
								?>
								<span class='hidesend' >
									<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
								</span>	
								<?php } ?>
								
								<?php $baseurl1 = $baseurl.$modulePath.'index.php?sub=list' ?>
								
								<span>&nbsp;&nbsp;</span>
								
								<a href="<?php echo $baseurl1 ?>" class="btn btn-default" >Back</a>
									
								</div>
							</div>	

					<?php //} ?>
					
					<?php  
					//	if( $status == 'Submitted' ){
								
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, a.primary_role FROM sma_user a, sma_role b 
									WHERE 1 and a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['primary_role'];
								
								$sql = " SELECT * FROM  sma_role WHERE 1 AND id = '$approver_1_role' ";
								$rs = mysqli_query($con, $sql);
								//a.primary_role = b.id
								$rw = mysqli_fetch_array($rs);
								$approver_1_role = $rw['role'];
								
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label1"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, a.primary_role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['primary_role'];
								
								$sql = " SELECT * FROM  sma_role WHERE 1 AND id = '$approver_2_role' ";
								$rs = mysqli_query($con, $sql);
								//a.primary_role = b.id
								$rw = mysqli_fetch_array($rs);
								$approver_2_role = $rw['role'];
								
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><BR>
									<label class="control-label1"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, a.primary_role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['primary_role'];
								
								$sql = " SELECT * FROM  sma_role WHERE 1 AND id = '$approver_3_role' ";
								$rs = mysqli_query($con, $sql);
								//a.primary_role = b.id
								$rw = mysqli_fetch_array($rs);
								$approver_3_role = $rw['role'];
								
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><BR>
									<label class="control-label1"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, a.primary_role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['primary_role'];
								
								$sql = " SELECT * FROM  sma_role WHERE 1 AND id = '$approver_4_role' ";
								$rs = mysqli_query($con, $sql);
								//a.primary_role = b.id
								$rw = mysqli_fetch_array($rs);
								$approver_4_role = $rw['role'];
								
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><BR>
									<label class="control-label1"><?= $approver_4_name . " <BR> " . $approver_4_role;?>
									</label>
								</div>
					<?php	
							}
						
					?>		
					
							</div>
					<?php		
					//	}
								
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
								 ?>
							
								<BR>
								
							</div>
						
						</span>
				<?php } ?>		
							
			</div>
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								<p><?= $label_line; ?></p>
								<?php 
									
									$srno = $po_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PO' order by id asc  ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
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
												
												$vendor_flag		= $r1['vendor_flag'];
												
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
												
												if($vendor_flag=='V'){
													$create_by		= $r1['create_by'];
													$s2="SELECT * FROM sma_party_mst where id = '$create_by' ";
											//echo $s2. "<BR>";
													$r3 = mysqli_query($con, $s2);
													$rw1 = mysqli_fetch_array($r3);
													$create_by = $rw1['party_name'];
												}
												
												
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
							
								<p><?= $label_line; ?></p>
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
					<th>Srno.</th>
                    <th>PO.No.</th>
					<th>Dated</th>
					<th>Supplier</th>
					
					<th style="text-align:right;">Total</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
					
<!--				<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>

			<?php
			
//echo $po_new_no. ' ' . $new_po_no. "<BR>";
			$sql = '';
			$sql="Select * from sma_purchase_order where id  = 0 ";
			if(!empty($old_po_no)){	
				$sql="Select * from sma_purchase_order where id  = '$old_po_no' and del !='Y' order by po_rev desc ";
			}
			else if( !empty($new_po_no) || !empty($po_new_no) ){
				$new_po_no = $po_new_no;
				$sql="Select * from sma_purchase_order where id = '$new_po_no' and del !='Y' order by po_rev desc ";
			}
//echo $sql . "<<>>";
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
					$tot_amount = 0;
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
							$po_number .= '-'.$po_rev;
						}
						
						$po_amend = $row['po_amend'];
						$backcolor = '';
						if($po_amend=='Y'){
							$backcolor = ' background-color: coral; ';
						}
						
						$dated_v = date('d-m-Y', strtotime($row['dated']));
						if($dated_v=='01-01-1970'){
							$dated_v = '';	
						}	
						
					?>
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
						<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

						<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
						<td width="1%"><?php echo $row['id'];?></td>
						<td width="20%"><?php echo $po_number;?></td>
						<td width="08%"><?php echo $dated_v;?></td>
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
<!-- End Tab5-->						



<?php	//Advance Payment Start ?>
						<div class="tab-pane" id="tab_6">
							
							<div class="modal-header" >
								<p><?php echo $label_line; ?></p>
							</div>
							
							<div class="box-body">
							<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>
										<th>SrNo.</th>
										<th>Paid On</th>
										<th>Paid via</th>
										<th>UTR.No./ Cheque No.</th>
										<th style="text-align:right;">Amount Paid</th>
										
									</tr>
								</thead>
							<tbody>
							<?php

								$sql = "SELECT b.*, c.*  FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y'  ";
								
								$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c where a.po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y'  ";
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect = mysqli_affected_rows($con);
						
						?>			
						<?php			
									$q21  = mysqli_query($con, $sql);
									while($r21  = mysqli_fetch_array($q21)){
										$py_id = $r21['payment_hdr_id'];
									
										$cash_bank_name = $r21['cash_bank_name'];
										$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$cash_bank_name = $r2['account_name'];
										
										$paid_to = $r21['paid_to'];
										$st_flag = $r21['st_flag'];
										if($st_flag =='A' || $st_flag =='T'){
											$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['username'];
										}
										else {
											$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['party_name'];
										}
										
										if($st_flag=='S'){
											$st_flag ='SI';
										}
										else if($st_flag=='A'){
											$st_flag ='TA';
										}
										else if($st_flag=='T'){
											$st_flag ='TE';
										}
										else if($st_flag=='C'){
											$st_flag ='OE';
										}
										else if($st_flag=='D'){
											$st_flag ='SA';
										}
										
										$dated = date('d-m-Y', strtotime($r21['dated']));
										if($dated =='01-01-1970'){
											$dated = '';
										}
										
										$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
										if($paid_date =='01-01-1970'){
											$paid_date = '';
										}
										
										$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$supplier_invoice_no  = $r2['supplier_invoice_no'];
										$supp_id			  = $r2['supp_id'];
										
										$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];
										
										$approval_status = $r21['approval_status'];
											
										$baseurl1 = $baseurl.'payment/'.'edit.php?sub=edit&id='.$py_id;
												
										?>
									<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=". $py_id;?>" target="_blank" title="Edit">
									<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="window.open('<?php echo $baseurl1;?>', '_blank')"  >
										<td width="2%" style="text-align:right;"><?php echo $py_id;?></td>
										<td width="10%" <?php echo $styl; ?>><?php echo $paid_date;?></td>
										<td width="12%" <?php echo $styl; ?>><?php echo $cash_bank_name;?></td>
										<td width="10%"<?php echo $styl; ?>><?php echo $r21['utr_no']. ' '. $r21['cheque_no'];?></td>
										<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo number_format($r21['total_amount_paid'],2);?></td>
										
									</tr>
									</a>
									
									<?php }
									
								?>
								</tbody> 
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<th>Total</th>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_paid_net,2);?></td>
									</tr>
								</table>

								
									</div>
						
						</div>
					<?php	//Advance Payment End ?>
					
<?php	//Supplier Invoice Payment Start ?>
						<div class="tab-pane" id="tab_7">
							
							<div class="modal-header" >
								<p><?php echo $label_line; ?></p>
							</div>
							
							<div class="box-body">
							<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>
										<th>SrNo.</th>
										<th>Paid Date</th>
										<th>Paid via</th>
										<th>UTR.No./ Cheque No.</th>
										<th>Invoice Number</th>
										<th style="text-align:right;">Invoice Amount</th>
										<th style="text-align:right;">Amount Paid</th>
									</tr>
								</thead>
							<tbody>
							<?php
								$sql = "select * from payment_details a, payment_header b where 1 and b.id = a.payment_hdr_id and b.st_flag='S' and b.del !='Y' and supp_id = '$si_id' ";
								
								$total_amount_paid_net =0;
								
								$sql = " SELECT b.*, c.* , a.total_amount FROM `sma_supplier_invoice` a, payment_header b, 
											payment_details c , sma_purchase_order d
									WHERE a.our_po_ref_no = '$our_po_ref_no' and a.our_po_ref_no = d.id 
									and a.id = c.supp_id and b.id = c.payment_hdr_id and b.st_flag = 'S' 
									and b.del !='Y' and a.status = 'Completed' ";
								
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect = mysqli_affected_rows($con);
								
									$q21  = mysqli_query($con, $sql);
									while($r21  = mysqli_fetch_array($q21)){
										$py_id = $r21['payment_hdr_id'];
										$total_amount = $r21['total_amount'];
										
									
										$cash_bank_name = $r21['cash_bank_name'];
										$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$cash_bank_name = $r2['account_name'];
										
										$paid_to = $r21['paid_to'];
										$st_flag = $r21['st_flag'];
										if($st_flag =='A' || $st_flag =='T'){
											$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['username'];
										}
										else {
											$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['party_name'];
										}
										
										if($st_flag=='S'){
											$st_flag ='SI';
										}
										else if($st_flag=='A'){
											$st_flag ='TA';
										}
										else if($st_flag=='T'){
											$st_flag ='TE';
										}
										else if($st_flag=='C'){
											$st_flag ='OE';
										}
										else if($st_flag=='D'){
											$st_flag ='SA';
										}
										
										$dated = date('d-m-Y', strtotime($r21['dated']));
										if($dated =='01-01-1970'){
											$dated = '';
										}
										
										$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
										if($paid_date =='01-01-1970'){
											$paid_date = '';
										}
										
										$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$supplier_invoice_no  = $r2['supplier_invoice_no'];
										$supp_id			  = $r2['supp_id'];
										
										$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];
										
										$total_amount_si_net = $total_amount_si_net + $total_amount;
										
										$approval_status = $r21['approval_status'];
											
										$baseurl1 = $baseurl.'payment/'.'edit.php?sub=edit&id='.$py_id;
												
										?>
									<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=". $py_id;?>" target="_blank" title="Edit">
									<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="window.open('<?php echo $baseurl1;?>', '_blank')"  >
										<td width="2%" style="text-align:right;"><?php echo $py_id;?></td>
										<td width="10%" ><?php echo $paid_date;?></td>
										<td width="12%" ><?php echo $cash_bank_name;?></td>
										<td width="10%" ><?php echo $r21['utr_no']. ' ' . $r21['cheque_no'];?></td>
										<td width="12%" ><?php echo $supplier_invoice_no;?></td>
										<td width="10%" style="text-align:right;"><?php echo number_format($total_amount,2);?></td>
										<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo number_format($r21['total_amount_paid'],2);?></td>
										
									</tr>
									</a>
									
									<?php }
									
								?>
								</tbody> 
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_si_net,2);?></td>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_paid_net,2);?></td>
									</tr>
								</table>

									</div>
						
						</div>
<?php	//Supplier Invoice Payment End ?>

<!--Comment Section Start-->				
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
					<p><?= $label_line; ?></p>
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
						
						<?php	//Invoice Against PO ?>
							<div class="tab-pane" id="tab_10">

										<div class="modal-header">
											<div class="well well-sm"
												style="background-color: #f4f4f4; border-left: 5px solid #3c8dbc; padding: 10px 15px; margin-bottom: 20px; font-size: 16px; color: #333; font-weight: bold;">
												<?php echo $label_line; ?>
											</div>
										</div>

										<div class="box-body">
											<table id="prtable123" class="table table-bordered table-striped">

											<thead>
												<tr>
													<th>Sr.No.</th>
													<th>Created Date</th>
													<th>Tax.Inv.No.</th>
													<th style="text-align:right;">Amount</th>
													<th>Our PO Ref.NO.</th>
													<th>Supplier Name</th>
													<th>Payment Status</th>
													<th>Status</th>
												</tr>
											</thead>
									<tbody>
								<?php
										$modulePath_si = "supp_invoice/";
										$sql = " SELECT a.* FROM `sma_supplier_invoice` a, sma_purchase_order b
													WHERE a.our_po_ref_no = '$our_po_ref_no' and a.our_po_ref_no = b.id 
														and a.del !='Y'  order by id desc "; //and a.status = 'Completed'
													//echo $sql."<BR>";
										$q21 = mysqli_query($con, $sql);
										$rowaffect = mysqli_affected_rows($con);
										$q21 = mysqli_query($con, $sql);
										while ($r21 = mysqli_fetch_array($q21)) {
											$company_id = $r21['company_id'];
		$project_id = $r21['project_id'];
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		$sql 	= "select * from sma_project where id = '$project_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$project_name = $r2['project_name'];
		
		$supplier = $r21['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];

		$our_po_ref_no = $r21['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$po_number 	= $r1['po_number'];
		$po_rev			= $r1['po_rev'];
		if($po_rev>0){
			$po_number 	= $po_number .'-'.	$po_rev;
		}
				
		$due_date = date('d-m-Y', strtotime($r21['due_date']));
		if($due_date=='01-01-1970'){$due_date='';}
		
		$si_id = $r21['id'];
		$sql = "SELECT b.*, a.our_po_ref_no, a.status FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status in ( 'Completed', 'Draft','Submitted' )  ";
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$affected_row  = mysqli_affected_rows($con);
		$utr_no 	= $r2['utr_no'];
		$st_flag 	= $r2['st_flag'];
		$status_py		= $r2['status'];
		
		$paid_status ='';	
		if($affected_row==0){
			$paid_status = 'Unpaid';
		}
		else {
			$paid_status = 'Unpaid – Payment Note Created';
				
		}
		if(!empty($utr_no)){
			$paid_status = 'Paid#'.'/'.$utr_no ;
		}
	
		$sql = "SELECT b.* FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status in ('Closed', 'Completed' ) and a.advance_flag = 'Y' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$affected_row  = mysqli_affected_rows($con);
		$utr_no 	= $r2['utr_no'];
		$st_flag 	= $r2['st_flag'];
		
		if(!empty($utr_no)){
			$paid_status = 'Paid'.'/'.$utr_no ;
		}
		
		if($affected_row>0){
			$sql = "SELECT b.id as pay_no, st_flag, a.id as po_no, sum(c.payment_adjusted) as amount_paid_po, b.utr_no as utr_no_po, b.paid_date as paid_date_po  
				FROM `sma_purchase_order` a, payment_header b, payment_details c 
					WHERE a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id 
					AND st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";				
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$amount_paid_po = $amount_paid_po + $r2['amount_paid_po'];
			$utr_no_po		= $r2['utr_no_po'];
			$paid_date_po	= $r2['paid_date_po'];
			if(!empty($utr_no_po)){
				$paid_date = date('d-m-Y', strtotime($paid_date_po));
				if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
					$paid_date='';
				}
				$paid_status = 'Advance Paid '.'/'.$utr_no_po ;
			}
		}
		
		$status_si = $r21['status'];
		
		$total_amount_v = $r21['total_amount'];
		$total_amount_si = $total_amount_si + $total_amount_v;
		
		$baseurl1 = $baseurl.$modulePath_si.'edit.php?sub=edit&id='.$r21["id"];
				
?>
	
	<a href="<?php echo $baseurl . $modulePath_si . "edit.php?sub=edit&id=". $r21['id'];?>" target="_blank" title="Edit" >
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)"
												onclick="window.open('<?php echo $baseurl1; ?>', '_blank')" >
		<!--<td width="0%"><input type="hidden" value="<?php echo $r21['id'];?>"></td>-->
		<td width="3%" style="text-align:right;<?= $styla; ?> "><?php echo $r21['id']?></td>
		<td width="11%" <?= $styl; ?>><?php echo date('d-m-Y', strtotime($r21['created_date']));?></td>
		<td width="10%" <?= $styl; ?> ><?php echo $r21['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;<?= $styla; ?>"><?php echo moneyFormatIndiaa($r21['total_amount'])?></td>
		<td width="15%"  <?= $styl; ?>><?php echo substr($po_number,0,25).'<BR>'.substr($po_number,25,50). ' <b>SPV:'. $comp_code. '</b> <br>'. substr($project_name,0,25)."<BR>".substr($project_name,25,50);?></td>
		<td width="17%"  <?= $styl; ?>><?php echo $supplier_name;?></td>
		<td width="09%"  <?= $styl; ?>><?php echo $paid_status;?></td>
		<td width="08%" <?= $styl; ?>><?php echo $status_si;?></td>
															</tr>
														</a>

													<?php }

													?>
												</tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td width="10%" style="text-align:right;"><?= moneyFormatIndiaa($total_amount_si); ?></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</table>

										</div>

									</div>
									<?php	// Invoice Against PO End ?>	
									
									
						
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
                                    <select class="form-control select2-123" required name="supplier_name" onchange="getpangst(this.value)">
						<?php if($status!='Closed'){ ?>			
									<option value=""> Select </option>
						<?php } ?>			
										<?php $sql = "select * from sma_party_mst where 1 order by party_name ";
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
							
						$sqlv = " AND id = 10 ";	
						if( $old_po_no>0 && $status == 'Draft' ){
							$readonly = '';
							$sqlv = "";
						}	
							
							
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
                                    	$sql="SELECT * FROM sma_product_group where 1 $sqlv ORDER BY product_group ASC";
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
									<label for="itemName" class="control-label">Budget Group </label>
								</div>
								
								<div class="col-sm-6">
									<label for="itemName" class="control-label">Budget Sub Group </label>
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
                                	<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
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
                                        <input type="text" class="form-control" id="itemRate"  style="text-align:right;"  onkeyup="calculateTotalAmount();">
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
                                        <input type="text" class="form-control" id="deliveryDATE" >
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
											<label for="approver" class="col-sm-6 control-label" style="color:red;">Do you want to Reject ?</label>
										</div>
										
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype col-sm-1" name="doctype[]" ><option value="">Select</option>'+opt+'</select></td><td width="25%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="25%">	<textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="20%" ><input type="file" name="fudoc[]" class="docfile"></td><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
									if($cnt>0){
										echo "<b>Error : PO should not be convert to Draft, Reference PO for Supplier Invoice already available...</b>";
									}
									
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




<!--Make to Close-->
<div class="modal fade" id="makeClose" role="dialog" aria-labelledby="makeClose">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeClose">Do you want to Close? </h4>
				<h4 class="modal-title" id="makeClose">The PO can't be reopened once it has been closed, so check before clicking YES </h4>
				
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
										<input type="hidden" name="po_id" id="po_idC" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Accept'>
										<input type="hidden" id="approverC" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusC" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksC"></textarea>
											</div>
										</div>
                                    </div>
								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitClose">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>
<!--Make to Close-->

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
										
									if($cnt>0){
										echo "<b>Error : PO should not be deleted, Reference PO for Supplier Invoice already available...</b>";
									}
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


<!--Make to Block-->
<div class="modal fade" id="makeBlock" role="dialog" aria-labelledby="makeBlock">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeBlock">Do you want to Order Completed ? </h4>
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
										<input type="hidden" name="po_id" id="po_idB" value="<?php echo $po_id; ?>" >
										<input type="hidden" id="modeB" name="mode" value='Accept'>
										<input type="hidden" id="approverB" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusB" readonly name="status" value="<?php echo $status ?>" >
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
<!--Make to Block-->
										
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
	
	$("#submitClose").on("click", function(e){
        var mode		 	=  $("#modeC").val();
		var po_id		 	=  $("#po_idC").val();
        var status 			=  $("#statusC").val();
		var remarks			=  $("#remarksC").val();
		
//alert(remarks +  ' ' + po_id + ' ' + status);

		$('#makeClose').modal('hide');
		var strURL = "py_closed_func.php";
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
		$('#predit').html('<center><h1>Wait ...</h1></center>');
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
        var mode		 	=  $("#modeB").val();
		var po_id		 	=  $("#po_idB").val();
        var status 			=  $("#statusB").val();
		var remarks			=  $("#remarksB").val();
		
//alert(remarks +  ' ' + po_id + ' ' + status);

		$('#makeBlock').modal('hide');
		var strURL = "py_block_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});


/* 	$("#submitBlock").on("click", function(e){
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
		
 */	
    var itemArray = []; // stores all item details table values in memory

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
		
		//var selected_vendor_value	= parseInt($("#selected_vendor_value").val());
		var selected_vendor_value  	= parseInt(document.getElementById("selected_vendor_value").value);
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
			alert('Budget Group must be select.....');
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

		var budget_err = document.getElementById("budget_err").value;
//alert(budget_err);		
       
		if(budget_err=='Y'){
			alert('Insufficient budget, check product details !!!');
			return false;
		}
		
		var company_id    	= document.getElementById("projecT").value;
		var checker_value   = document.getElementById("checker_value").value;
		
		var po_id    		= document.getElementById("PO_ID").value;
		
		var sub = 'sub244';
//alert(sub);		
// 		var strURL = "app_func.php";
// 		$.post(strURL,{company_id:company_id,po_id:po_id,sub244:sub},function(result){
// 		      $('#getapprover').html(result);
// 			  $chk_flag = result.trim();
// 			  if($chk_flag=='Y'){
// 				 alert('Insufficient budget balance !'); 
// 				 location.reload();
// 				 return false;
// 			  }
// 		});
		
		
		var sub = 'sub24';
//		$('.hidesend').hide();
//alert(sub + ' ' + ' ' + company_id + ' ' + checker_value);	
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,sub24:sub},function(result){
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
		var company_id 	=  $("#projecT").val();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub3A:sub},function(result){
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
		CKEDITOR.replace('reason4');
		
		//config.line_height="1px;1.1px;1.2px;1.3px;1.4px;1.5px" ;
		//CKEDITOR.plugins.setLang('lineheight','en', {
        //    title: 'Line Height'
        //} );

    });

	function gettenderTitle(id){
		
        var sub    = 'sub37';
		var company_id = document.getElementById("projecT").value;
		
		//if (document.getElementById('po_typec').checked){
		    po_type = document.getElementById('po_typec').value;
		//}
		
//alert(po_type + ' ' + sub + ' ' + company_id);
		if(po_type=='C'){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,company_id:company_id,sub37:sub},function(result){
				  $('#gettenderTitle').html(result);
			});
		}

	}
	

	CKEDITOR.on("instanceReady", function(event) {
        event.editor.on("beforeCommandExec", function(event) {
            // Show the paste dialog for the paste buttons and right-click paste
            if (event.data.name == "paste") {
                event.editor._.forcePasteDialog = true;
            }
            // Don't show the paste dialog for Ctrl+Shift+V
            if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
                event.cancel();
            }
           
        })
        
    });
    
</script>

</body>
</html>
