<?php 
	session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "purchase_order_entry/";	
	$user   = $_SESSION['user'];

$remarks 		= $_POST['remarks'];
$po_id  		= $_POST['po_id'];

$userid   			= $_SESSION['usrid'];		
/* 	if($old_po_no==0){
		$old_po_no = $po_id;
	}
*/

 $old_po_no = $po_id;
 
//Purchase Order	
	$sql = " Select * from sma_purchase_order where 1 and del !='Y' and old_po_no = '$old_po_no' || id = '$old_po_no' order by po_rev desc ";
	$qry = mysqli_query($con, $sql);
    $rw  = mysqli_fetch_array($qry);
	$approval_memo_ref	= $rw['approval_memo_ref'];
	$company_id 		= $rw['project'];
	$department			= $rw['department'];
	$background				= $rw['background'];
	$scope_of_work			= $rw['scope_of_work'];
	$deviations_from_sop	= $rw['deviations_from_sop']; 
	$draft_by 			= $rw['draft_by'];
	$po_number			= $rw['po_number'];
	$po_rev				= $rw['po_rev'];
	$old_po_no 			= $po_id;
	
	$sql = "SELECT * FROM sma_department where id = '$department' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$dept_code = $r2['dept_code'];
			
	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$po_last_number = $r2['po_last_number']+1;
	$header_text	= $r2['header_text'];
	$comp_code 		= $r2['comp_code'];
	
	$sql = "UPDATE company SET po_last_number = '$po_last_number' WHERE comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	
	$mth = date("m");
	if($mth >=1 and $mth<=3){
		$yyyy = date('Y')-1;
		$yyyy .= '-';
		$yyyy .= date('y');
	}
	else if($mth >=4 and $mth<=12){
		$yyyy = date('Y').'-';
		$yyyy .= date('y')+1;					
	}
	
	$po_number = $comp_code.'/'.$dept_code.'/'.$yyyy.'/'.$po_last_number; // As per Rangnathan - 06-10-2023 , giving next number.

	if($po_rev >0){
		$old_po_no 		= $rw['old_po_no'];
		$po_rev_var		= $po_rev + 1;
	}
	else {
		$po_rev_var		= 1;	
	}

		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		$user_maker			= $draft_by;
	
//approval_memo_ref
//Old Approval Memo Updated as Suspend
/* $sql = " select * from sma_approval_memo where id = '$approval_memo_ref' and del !='Y' ";
$q2 =mysqli_query($con, $sql);
$r2 = mysqli_fetch_array($q2);
echo mysqli_error($con);
$background				= $r2['background'];
$scope_of_work			= $r2['scope_of_work'];
$deviations_from_sop	= $r2['deviations_from_sop']; */
		
//echo $sql."<BR>"; background, scope_of_work, deviations_from_sop
	
$sql = " Insert into sma_purchase_order(`po_doc_type`, po_type, trans_type, `po_number`, `po_rev`, `approval_memo_ref`, `dated`, `project`, `location`, `delivery_address`, `department`, `budget_name`, `budget_head`, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, `status`, `approval_status`, `draft_by`, `draft_date`, `changed_by`, `changed_date`, `name_of_person`, `item_name`, `description_product_category`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag`, subject, notes, supplier_location, background, scope_of_work, deviations_from_sop )  
SELECT `po_doc_type`, 'C', trans_type, '$po_number', '$po_rev_var', `approval_memo_ref`, now(), `project`, `location`, `delivery_address`, `department`, `budget_name`, `budget_head`, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, 'Draft', '', '$user_maker', now(), '', '', `name_of_person`, `item_name`, `description_product_category`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag`, subject, notes, supplier_location, '$background', '$scope_of_work', '$deviations_from_sop'  from sma_purchase_order where id = '$po_id' ";

//echo $sql."<BR>";

$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
$last_id = mysqli_insert_id($con);

//New Amend PO
$sql = " update sma_purchase_order set old_po_no = '$old_po_no' where id = '$last_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>";

//Old PO Updated as Suspend
$sql = " update sma_purchase_order set status='Amend', approval_status = 'Amend', po_new_no = '$last_id',  po_amend='Y' where id = '$po_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>";

//approval_memo_ref
//Old Approval Memo Updated as Suspend
/* $sql = " update sma_approval_memo set status='Amend', approval_status = 'Amend', po_old_no = '$old_po_no' , po_amend='Y' where id = '$approval_memo_ref' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>"; */

$sql = "Insert into `sma_po_items` (`purchase_id`,`account_year`,`company_id`,`product_id`,`product_code`,`product_name`,`product_desc`, `product_category`,`budget_id`,`budget_name`,`budget_head`,`total_budget`, `balance_budget`, `quantity`, `uom`, `unit_rate`, `gst`, gst_id, `delivery_date`, first_insert, pr_quantity) 
SELECT '$last_id', `account_year`, `company_id`, `product_id`, `product_code`, `product_name`, `product_desc`, `product_category`, `budget_id`, `budget_name`, `budget_head`, `total_budget`, `balance_budget`, (`quantity` - bal_si_qty ) as qty, `uom`, `unit_rate`, `gst`, gst_id, `delivery_date` , 'F' , if( (`quantity` - bal_si_qty )=0, pr_quantity, (`quantity` - bal_si_qty) ) as pr_quantity FROM `sma_po_items` WHERE purchase_id = '$po_id' ";
mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>"; IF(500<1000, "YES", "NO");

	$sql = "INSERT INTO `sma_po_approval_details` ( po_approval_hdr_id, supplier_name, quote_Ref_no, vendor_selected, `values`, remarks ) SELECT '$last_id', supplier_name, quote_Ref_no, vendor_selected, `values`, remarks FROM `sma_po_approval_details` where po_approval_hdr_id = '$old_po_no' and vendor_selected = 'Y' ";
	mysqli_query($con, $sql);
	echo mysqli_error($con);


	$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'PO', '$po_id', '$userid', now(), 'Amend', '$draft_by_id', '$remarks', now() )";
	mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
		
	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r2  = mysqli_fetch_array($q2);
	$budget_control_gst = $r2['budget_control_gst'];
	
		
//echo $sql."<BR>";		
	$sql="SELECT * from sma_po_items where purchase_id = '$last_id' order by budget_id ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
//echo $sql."<BR>";	
	$value="";
	$tot_amount = 0 ;
	$budget_id_prev ='';
	while($row = mysqli_fetch_array($result)){
		$po_dtl_id 		= $row['id'];
		$product_id 	= $row['product_id'];
		$bal_si_qty 	= $row['bal_si_qty'];
		$qty 			= $row['quantity'];
		$budget_head	= $row['budget_head'];
		$budget_id		= $row['budget_id'];	
		$rate 			= $row['unit_rate'];
		$gst			= $row['gst'];
		
		$gstamt 		= round((($qty * $rate) * $gst / 100),0);
		if($budget_control_gst!='Y'){
			$gstamt = 0;
		}
		
		$po_amount 		= $qty * $rate + $gstamt;
		if( $po_amount <= 0 ){
			$po_amount = 0;
		}

		$sql = " SELECT * from sma_budget where id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$total_budget 		= $r2['total_budget'];
		$blocked_budget		= $r2['blocked_budget'];
		$used_budget 		= $r2['used_budget'];
		$adjustment_budget	= $r2['adjustment_budget'];
		$bal_budget			= ( $total_budget + $adjustment_budget ) - ( $blocked_budget - $used_budget );
		
		if( $budget_id_prev	= $budget_id && empty($budget_id_prev) ){
			$sql = " UPDATE sma_po_items SET balance_budget = $bal_budget WHERE id = '$po_dtl_id' ";
			mysqli_query($con, $sql);
		}
		else if( $budget_id_prev != $budget_id ){
			$sql = " UPDATE sma_po_items SET balance_budget = $bal_budget WHERE id = '$po_dtl_id' ";
			mysqli_query($con, $sql);
		}
		else {
			$sql = " UPDATE sma_po_items SET balance_budget = $bal_budget_prev WHERE id = '$po_dtl_id' ";
			mysqli_query($con, $sql);
		}
		
		$budget_id_prev		= $budget_id;
		$bal_budget_prev	= $bal_budget - $po_amount;
		$po_amount_prev		= $po_amount;

	}

$sql = "Insert into file_uploads (`module`, `file_name`,`file_path`, `doc_type`, `doc_desc`, `reference_id`, `date_uploaded`, `doc_invoice_no`, share_point_link) SELECT `module`, `file_name`, `file_path`, `doc_type`, `doc_desc`, '$last_id', now(), `doc_invoice_no`, share_point_link FROM `file_uploads` where module = 'PO' and reference_id = '$po_id' "; 
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>";

$srno = $last_id;
//exit();

	$baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';
	echo "<script>window.location.href='$baseurl';</script>";

?>

