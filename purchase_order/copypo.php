<?php
include("../header.php");
$modulePath = "purchase_order/";

?>


<?php

$po_id     			= $_GET['po_id'];
$old_po_no 			= $_GET['old_po_no'];
$po_number 			= $_GET['po_number'];
$po_rev_var	   		= $_GET['po_rev'];
$approval_memo_ref 	= $_GET['approval_memo_ref'];

$user_maker			= $_POST['user_maker'];

	if($old_po_no==0){
		$old_po_no = $po_id;
	}

//Purchase Order	
	$sql="Select * from sma_purchase_order where old_po_no = '$old_po_no' || id = '$old_po_no' order by po_rev desc ";
//echo $sql."<BR>";
//echo $user_maker;
//exit();

	$qry = mysqli_query($con, $sql);
    $rw  = mysqli_fetch_array($qry);
	$po_rev		= $rw['po_rev'];
	$old_po_no 		= $po_id;
	if($po_rev >0){
		$old_po_no 	= $rw['old_po_no'];
		$po_rev_var	= $po_rev + 1;
	}

$sql = " Insert into sma_purchase_order(`po_doc_type`, `po_number`, `po_rev`, `approval_memo_ref`, `dated`, `project`, `location`, `delivery_address`, `department`, `budget_name`, `budget_head`, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, `status`, `approval_status`, `draft_by`, `draft_date`, `changed_by`, `changed_date`, `name_of_person`, `item_name`, `description_product_category`, `qty`, `unit`, `rate`, `cgst`, `sgst`, `igst`, `amount`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag`)  select `po_doc_type`, '$po_number', '$po_rev_var', `approval_memo_ref`, now(), `project`, `location`, `delivery_address`, `department`, `budget_name`, `budget_head`, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, 'Draft', '', '$user_maker', now(), '', '', `name_of_person`, `item_name`, `description_product_category`, `qty`, `unit`, `rate`, `cgst`, `sgst`, `igst`, `amount`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag` from sma_purchase_order where id = '$po_id' ";

//echo $sql."<BR>";

$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
$last_id = mysqli_insert_id($con);

//New Amend PO
$sql = " update sma_purchase_order set old_po_no = '$old_po_no' where id = '$last_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql;

//Old PO Updated
$sql = " update sma_purchase_order set status='Blocked', approval_status = 'Blocked', po_amend='Y' where id = '$po_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql;

$sql = "Insert into `sma_po_items` (`purchase_id`,`account_year`,`company_id`,`product_id`,`product_code`,`product_name`,`product_desc`, `product_category`,`budget_id`,`budget_name`,`budget_head`,`total_budget`, `balance_budget`, `quantity`, `uom`, `unit_rate`, bal_grn_qty, bal_si_qty, bal_si_amount, `gst`, `delivery_date`) 
SELECT '$last_id',`account_year`,`company_id`,`product_id`,`product_code`,`product_name`,`product_desc`,`product_category`,`budget_id`,`budget_name`,
`budget_head`,`total_budget`, `balance_budget`, (`quantity`-bal_si_qty) as quantity, `uom`, `unit_rate`, bal_grn_qty, 0, 0, `gst`, `delivery_date` FROM `sma_po_items` WHERE purchase_id = '$po_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
//echo $sql."<BR>";

$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$value="";
	$tot_amount = 0 ;
	while($row = mysqli_fetch_array($result)){
		$qty 	= $row['quantity'];
		$rate 	= $row['unit_rate'];
		$gst	= $row['gst'];
		$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
		$tot_amount = $tot_amount + $amount;
	}											

$sql = "Insert into file_uploads (`module`, `file_name`,`file_path`, `doc_type`, `doc_desc`, `reference_id`, `date_uploaded`, `doc_invoice_no`) SELECT `module`, `file_name`, `file_path`, `doc_type`, `doc_desc`, '$last_id', now(), `doc_invoice_no` FROM `file_uploads` where module = 'PO' and reference_id = '$po_id' "; 
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
echo $sql."<BR>";


$srno = $last_id;
//exit();


//Approval Memo Start
$sql = " select * from sma_approval_memo where ap_old_no = '$approval_memo_ref' order by ap_rev desc ";
$rt = mysqli_query($con, $sql);
$rw  = mysqli_fetch_array($rt);
$ap_rev_v			= $rw['ap_rev'];
if($ap_rev_v>0){
	$ap_rev			= $rw['ap_rev']+1;
	$ap_old_no		= $rw['ap_old_no'];
}
else {
	$ap_rev			= 1;
	$ap_old_no		= $approval_memo_ref;
}
$sql = "INSERT INTO `sma_approval_memo` ( `dated`, `account_year`, `company`, `project`, `department`, `aop_provision`, `against_indent_no`, `budget_head`, `budget_available`, `balance_budget`, `subject`, `background`, `scope_of_work`, `supplier_name`, `quote_ref_no`, `value`, `vendor_selected`, `remarks`, `deviations_from_sop`, `important_terms_conditions`, `additional_costs`, `description`, `cost`, `status`, `approval_status`, `draft_by`, `draft_dated`, `changed_by`, `changed_date`, `send_to`, `flow_flag`, `close_flag`, `ap_rev`, ap_old_no, ap_amend )
select now(), `account_year`, `company`, `project`, `department`, `aop_provision`, `against_indent_no`, `budget_head`, `budget_available`, `balance_budget`, `subject`, `background`, `scope_of_work`, `supplier_name`, `quote_ref_no`, `value`, `vendor_selected`, `remarks`, `deviations_from_sop`, `important_terms_conditions`, `additional_costs`, `description`, `cost`, 'Draft', '', '$user_maker', now(), '', '', '', `flow_flag`, `close_flag`, '$ap_rev', '$ap_old_no', 'Y' from sma_approval_memo where id = '$approval_memo_ref' ";
//echo $sql.'<BR>';
$rt = mysqli_query($con, $sql);
//echo mysqli_error($con);
$ap_last_id = mysqli_insert_id($con);

$sql = "INSERT INTO `sma_approval_details` (`approval_hdr_id`, `supplier_name`, `quote_ref_no`, `vendor_selected`, `values`, `remarks`) 
select '$ap_last_id', `supplier_name`, `quote_ref_no`, `vendor_selected`, '$tot_amount', `remarks` from sma_approval_details where approval_hdr_id = '$approval_memo_ref' and vendor_selected = 'Y' ";
//echo $sql.'<BR>';
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);

$sql = "Insert into file_uploads (`module`, `file_name`,`file_path`, `doc_type`, `doc_desc`, `reference_id`, `date_uploaded`, `doc_invoice_no`) SELECT `module`, `file_name`, `file_path`, `doc_type`, `doc_desc`, '$ap_last_id', now(), `doc_invoice_no` FROM `file_uploads` where module = 'AP' and reference_id = '$approval_memo_ref' "; 
//echo $sql.'<BR>';
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);



//New Approval Memo Number update to new PO.
$sql = " update sma_purchase_order set approval_memo_ref = '$ap_last_id' where id = '$last_id' ";
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);

//exit();

	$baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';
	echo "<script>window.location.href='$baseurl';</script>";

?>