<?php

	session_start();
	
	include("../baseurl.php");
	include("../dbcon.php");
	$modulePath = "purchase_requisition/"; 
	
	$user			= $_SESSION['user'];
	$usrid			= $_SESSION['usrid'];
	
	$pr_id 			= $_POST['pr_id'];
	$company_id 	= $_POST['company_id'];
	$remarks		= $_POST['remarks'];
	
	$prdate	= date("Y-m-d");

	$sql = "SELECT max(prid) as srno FROM `sma_purchase_req` where date = '$prdate' ";
//echo $sql. "<BR>";
	$qry = mysqli_query($con, $sql);
	$r2	 = mysqli_fetch_array($qry);
	$srno = $r2['srno'] + 1;
	$pr_number = date('Ymd', strtotime($prdate)).'-'.$srno;

//MRN Header 
		$sql = " INSERT INTO sma_purchase_req (prid, pr_number, date, delivery_require_by,company_id, supplier_id, trans_type, delivery_address, 
					department_id, background_section, scope_of_work, created_by, status, draft_by, draft_date , subject)
				SELECT '$srno', '$pr_number', '$prdate', delivery_require_by, '$company_id', supplier_id, trans_type, delivery_address, department_id, background_section, scope_of_work, '$usrid', 'Draft', '$user', now(), subject from sma_purchase_req where id = '$pr_id' ";
//echo $sql. "<BR>";	
		mysqli_query($con, $sql);
        $last_id = mysqli_insert_id($con);
		$srno = $last_id;

//Products Details		
		$sql = "INSERT INTO sma_purchase_req_items (purchase_req_id, product_id, unit, quantity, description) 
				SELECT '$srno', product_id, unit, quantity, description from sma_purchase_req_items where purchase_req_id = '$pr_id' ";
		mysqli_query($con, $sql);
		
					
		$sql = "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, approved_date, remarks, status ) 
									VALUES( 'PR', '$srno', '$usrid', now(), now(), '$remarks', 'Draft' )";
//echo $sql. "<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
	
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$srno.'&active=active';
		echo "<script>window.location.href='$baseurl1';</script>";
		
		exit();
		
?>		