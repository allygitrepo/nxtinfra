<?php 
	session_start(); 	
	
	include "../baseurl.php";			
	include("../dbcon.php");

$userid   		= $_SESSION['usrid'];

echo $_GET['sub']. ' ' . $userid. "<BR>";
$supplier_id_arr  = $_POST['supplier_id'];
//print_r($supplier_id_arr);
//exit();

if($_GET['sub']=='Revise'){
		
	$tender_id 		= $_POST['tender_hdr_id'];
	$supplier_id_arr= $_POST['supplier_id'];
	$deadline_date		= date('Y-m-d', strtotime($_POST['deadline_date']));
	$deadline_time		= $_POST['deadline_time'];
	$deadline_date		.= ' ' .$deadline_time;
			
	$prev_tender_id = $tender_id;

	$sql = " SELECT * FROM sma_tender_header WHERE id = '$tnder_id' ";
	$qry = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($qry);
	$company_id 		= $r2['company_id'];
	$tender_rev_no 		= $r2['tender_rev_no'];
	$old_tender_id		= $r2['old_tender_id'];
	if($old_tender_id==0){
		$old_tender_id = $tender_id;
	}

	if($tender_rev_no==0){
		$tender_rev_no = 1;
	}
	else {
		$tender_rev_no = $tender_rev_no + 1;
	}
		
	$sql = " SELECT * FROM company WHERE comp_id = '$company_id' ";
	$qry = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($qry);
	$comp_code = $r2['comp_code'];

	$yyyy = date('y'). '-'. (date('y')+1);

	$sql = "INSERT INTO sma_tender_header ( tender_title, company_id, department, location, delivery_address, created_date, visible, price_visible, deadline_date, deadline_time, payment_within_days, retention, remarks, scope_of_work, background, trans_type, draft_by, draft_date, status ) 
	SELECT tender_title, company_id, department, location, delivery_address, created_date, visible, price_visible, '$deadline_date', '$deadline_time', payment_within_days, retention, remarks, scope_of_work, background, trans_type, draft_by, now(), 'Draft' FROM sma_tender_header WHERE id =  '$tender_id' ";
	mysqli_query($con, $sql);
	$new_tender_id = mysqli_insert_id($con);

	$sql = "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status) VALUES( 'TN', '$new_tender_id', '$userid', now(), 'Draft' )";
	mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
				
	//old_tender_id
	//new_tender_id

	$revno = 'R'.$tender_rev_no;
	$tender_number = $comp_code.'/'.str_pad($old_tender_id, 5, '0', STR_PAD_LEFT).'/'.$yyyy.'/'.$revno;
	$sql = " UPDATE sma_tender_header SET tender_number = '$tender_number',
											tender_rev_no = '$tender_rev_no',
											old_tender_id = '$prev_tender_id'
				WHERE id = '$new_tender_id' ";
	mysqli_query($con, $sql);

	$sql = " UPDATE sma_tender_header SET new_tender_id = '$new_tender_id'
				WHERE id = '$prev_tender_id' ";
	mysqli_query($con, $sql);

	//$supplier_id_arr
	for($i = 0; $i < sizeof($supplier_id_arr); $i++) {
				
		$supplier_id = $supplier_id_arr[$i];
		$sql = " INSERT INTO sma_tender_supplier( `tender_hdr_id`, `supplier_id`, `vendor_selected`, 
			`selected_amount`, `email_id`, `quotation_received`, `vendor_notes`, `remarks`, `status`, 
			`submit_date` )
				SELECT '$new_tender_id', '$supplier_id', `vendor_selected`, `selected_amount`, `email_id`,  '', `vendor_notes`, `remarks`, `status`, `submit_date` 
				FROM sma_tender_supplier 
				WHERE tender_hdr_id = '$tender_id' 
					AND $supplier_id = '$supplier_id' ";
		mysqli_query($con, $sql);
		
	}

	
	$sql = " INSERT INTO sma_tender_items(`tender_hdr_id`, `category_id`, `material_id`, `material_name`, 
				`material_desc`, `cost_center_group`, `cost_center_subgroup`, `quantity`, `uom`)
				SELECT '$new_tender_id', `category_id`, `material_id`, `material_name`, `material_desc`, `cost_center_group`,  `cost_center_subgroup`, `quantity`, `uom` `delivery_date` 
				FROM sma_tender_items WHERE tender_hdr_id = '$tender_id' ";
	mysqli_query($con, $sql);

	$sql = " INSERT INTO sma_tender_items(tender_hdr_id, supplier_id, file_name, file_path, doc_type, doc_desc, 
				share_point_link, date_uploaded )
				SELECT '$new_tender_id', supplier_id, file_name, file_path, doc_type, doc_desc, share_point_link, date_uploaded 
				FROM `sma_tender_file_upload` WHERE tender_hdr_id = '$tender_id' and supplier_id = 0 ";
	mysqli_query($con, $sql);

	$sql = " INSERT INTO sma_tender_terms(tender_hdr_id, terms_conditions)
				SELECT '$new_tender_id', terms_conditions FROM `sma_tender_terms` WHERE id = '$tender_id' ";
	mysqli_query($con, $sql);

	$modulePath = "tender/"; 
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$new_tender_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

}

?>


