<?php 
	session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "approval/";	
	$user   = $_SESSION['user'];

$remarks 		= $_POST['remarks'];
$ap_id  		= $_POST['ap_id'];

$userid   			= $_SESSION['usrid'];		
$old_ap_no = $ap_id;
 
//Purchase Order	
	$sql = " Select * from sma_approval_memo where 1 and del !='Y' and ap_old_no = '$old_ap_no' || id = '$old_ap_no' order by ap_rev desc ";
echo $sql."<BR>";	
	$qry = mysqli_query($con, $sql);
    $rw  = mysqli_fetch_array($qry);
	$approval_memo_ref	= $rw['id'];
	$company_id 		= $rw['company'];
	$department			= $rw['department'];
	$draft_by 			= $rw['draft_by'];
	$ap_rev				= $rw['ap_rev'];
	
	$sql = "SELECT * FROM sma_department where id = '$department' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$dept_code = $r2['dept_code'];
echo $sql."<BR>";			
	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$po_last_number = $r2['po_last_number']+1;
	$header_text	= $r2['header_text'];
	$comp_code 		= $r2['comp_code'];
echo $sql."<BR>";				
	if($ap_rev >0){
		$old_ap_no 		= $rw['old_ap_no'];
		$ap_rev_var		= $ap_rev + 1;
	}
	else {
		$ap_rev_var		= 1;	
	}

		$sql = " select * from sma_user where userid = '$draft_by' ";
echo $sql."<BR>";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		$user_maker			= $draft_by;
	
//approval_memo_ref
//Old Approval Memo Updated as Suspend
$sql = " INSERT INTO sma_approval_memo 	
			(`dated`,`account_year`,`company`,`project`,`trans_type`,`location`,
				`department`,`aop_provision`,`against_indent_no`,`budget_head`,
			`budget_available`, `balance_budget`,`subject`,`background`,
			`scope_of_work`, `supplier_name`,`quote_ref_no`,
			`value`,`vendor_selected`,`remarks`,`overhead_exp`,
			`deviations_from_sop`,`important_terms_conditions`,`additional_costs`,
			`description`,`approver_1`,`approver_1_status`,`approver_2`,
			`approver_2_status`,`approver_3`,`approver_3_status`,
			`approver_4`,`approver_4_status`,`approver_5`,`approver_5_status`,
			`approver_6`,`approver_6_status`,`approver_7`,`approver_7_status`, 
			`approver_8`, `approver_8_status`,`current_approver`,`cost`,
			`status`,`approval_status`,`draft_by`, `draft_dated`,`changed_by`,
			`changed_date`, `send_to`,`flow_flag`,`close_flag`,`ap_rev`,`ap_old_no`,
			`ap_new_no`, `ap_amend`,`po_old_no`, `del` )  
		SELECT `dated`,`account_year`,`company`,`project`,`trans_type`,`location`,
			`department`,`aop_provision`,`against_indent_no`,`budget_head`,
			`budget_available`, `balance_budget`,`subject`,`background`,
			`scope_of_work`, `supplier_name`,`quote_ref_no`,
			`value`,`vendor_selected`,`remarks`,`overhead_exp`,
			`deviations_from_sop`,`important_terms_conditions`,`additional_costs`,
			`description`,`approver_1`,`approver_1_status`,`approver_2`,
			`approver_2_status`,`approver_3`,`approver_3_status`,
			`approver_4`,`approver_4_status`,`approver_5`,`approver_5_status`,
			`approver_6`,`approver_6_status`,`approver_7`,`approver_7_status`, 
			`approver_8`, `approver_8_status`,`current_approver`,`cost`,
			`status`,`approval_status`,`draft_by`, `draft_dated`,`changed_by`,
			`changed_date`, `send_to`,`flow_flag`,`close_flag`,`ap_rev`,`ap_old_no`,
			`ap_new_no`, `ap_amend`,`po_old_no`, `del` 
		FROM `sma_approval_memo` WHERE 1 and id = '$ap_id' ";
		mysqli_query($con, $sql);
		$new_ap_no = mysqli_insert_id($con);
echo $sql."<BR>";
echo $new_ap_no ."<br>";
//New Amend AP
$sql = " update sma_approval_memo set ap_old_no = '$old_ap_no' where id = '$new_ap_no' ";
mysqli_query($con, $sql);
echo mysqli_error($con);
echo $sql."<BR>";

//approval_memo_ref
//Old Amend AP
$sql = " update sma_approval_memo set status='Amend', approval_status = 'Amend', ap_new_no = '$new_ap_no', ap_amend='Y' where id = '$old_ap_no' ";
//RAVI mysqli_query($con, $sql);
echo mysqli_error($con);
echo $sql."<BR>";

$sql = "INSERT into `sma_approval_items` ( approval_hdr_id, `company_id`,`supplier_id`,`product_id`,`product_code`, `product_name`, 
	`product_desc`, `product_category`,`budget_id`,`budget_name`,`budget_head`,
	`total_budget`,`balance_budget`,`uom`, quantity ,`gst`,`amount`, bal_amount ) 
	SELECT '$new_ap_no', `company_id`,`supplier_id`,`product_id`, `product_code`,
	`product_name`, `product_desc`,`product_category`,`budget_id`,`budget_name`,
	`budget_head`,`total_budget`,`balance_budget`,`uom`, quantity , `gst`, 
	`amount`, bal_amount
		FROM `sma_approval_items` where approval_hdr_id = '$old_ap_no' ";
mysqli_query($con, $sql);
echo mysqli_error($con);
echo $sql."<BR>";

$sql = "SELECT * FROM `sma_travel_expenses` 
			WHERE `approval_number` = '$old_ap_no' ";
echo $sql."<BR>";			
$q2 =mysqli_query($con, $sql);
while($r2 = mysqli_fetch_array($q2)){
	
	$approval_ref_no 			= $r2['approval_ref_no'];

	$sql = "SELECT sum(amount) as amount, sum(gst_amount) as gst_amount, reference, budget_id FROM `sma_expenses` WHERE 1 and exp_type = 'C' and approval_ref_no = '$approval_ref_no' group by reference, budget_id ";
	$q22 =mysqli_query($con, $sql);
echo $sql."<BR>";	
	while($r22 = mysqli_fetch_array($q22)){
		$amount 			= $r22['amount'];
		$gst_amount 		= $r22['gst_amount'];
		$reference 			= $r22['reference'];
		$budget_id 			= $r22['budget_id'];
		
		$sql = " UPDATE `sma_approval_items` set amount = amount - ($amount + $gst_amount ) WHERE approval_hdr_id = '$new_ap_no' and product_id = '$reference' and budget_id = '$budget_id' ";
		mysqli_query($con, $sql);
echo $sql."<BR>";		
		
	}	
	
}

$sql = " INSERT INTO sma_approval_details (approval_hdr_id , supplier_name, 	
				quote_ref_no, vendor_selected, `values`, remarks)
		SELECT '$new_ap_no' , supplier_name, quote_ref_no, vendor_selected, `values`, remarks FROM `sma_approval_details` where approval_hdr_id = '$old_ap_no' "; 
mysqli_query($con, $sql);
echo $sql."<BR>";	

	$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'AP', '$ap_id', '$userid', now(), 'Amend', '$draft_by_id', '$remarks', now() )";
	mysqli_query($con, $sql);
echo $sql."<BR>";	
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
	
		
	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r2  = mysqli_fetch_array($q2);
	$budget_control_gst = $r2['budget_control_gst'];
	

$sql = "Insert into file_uploads (`module`, `file_name`,`file_path`, `doc_type`, `doc_desc`, `reference_id`, `date_uploaded`, `doc_invoice_no`, share_point_link) SELECT `module`, `file_name`, `file_path`, `doc_type`, `doc_desc`, '$new_ap_no', now(), `doc_invoice_no`, share_point_link FROM `file_uploads` where module = 'AP' and reference_id = '$ap_id' "; 
$rt = mysqli_query($con, $sql);
echo mysqli_error($con);
echo $sql."<BR>";

$srno = $new_ap_no;

echo $baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';
exit();

	$baseurl.=$modulePath.'edit.php?id='.$srno;
	echo "<script>window.location.href='$baseurl';</script>";

?>

