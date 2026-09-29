<?php
	
	include("../dbcon.php");

		$sql = "truncate dashboard_trans_data";
		mysqli_query($con, $sql);

	set_time_limit(0);

	$sql = "SELECT from_date, to_date, short_fy_code FROM `sma_financial_year` where status = 'Y' ";
	$qry = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($qry);
	$finance_from_date = $r2['from_date'];
	$finance_to_date   = $r2['to_date'];
	$short_fy_code	   = $r2['short_fy_code'];
			
	$sqla = " AND a.dated >= '$finance_from_date' and a.dated <= '$finance_to_date' ";
	
//Purchase Order Start (only for PO cum Approval)
//Group query
//SELECT comp_code, doc_type, doc_mm_yyyy,  sum(total_value) as total_value FROM `dashboard_trans_data` group by comp_code, doc_type, doc_mm_yyyy
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , a.to_supplier as supplier_id, po_number, approval_memo_ref
			FROM `sma_purchase_order` a, `sma_po_items` b 
				WHERE 1 and a.id = b.purchase_id 
					AND del !='Y'
					AND a.approval_status !='Rejected' " . $sqla ; 
echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type				= 'Purchase Order';
		$doc_no					= $row['id'];
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		$po_number 				= $row['po_number']; 
		$approval_memo_ref 		= $row['approval_memo_ref'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code			= $r3['comp_code'];
		if(empty($comp_code)){
			continue;
		}
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['party_name'];
		
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){
			
			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$cost_center_group 		= $r2['budget_name'];
			$cost_center_sub_group 	= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			
			$sql = " INSERT INTO dashboard_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date',
					'$po_number', '$product_category', '$product_name', '$party_name', 
					'$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount') ";
			mysqli_query($con, $sql);
//echo $sql. "<BR>";
			echo mysqli_error($con);


//exit('####1');			
		}

	}		
//exit('####1');			

//Purchase Order End


//Supplier Invoice Start
			
	$sqla = " AND a.created_date >= '$finance_from_date' and a.created_date <= '$finance_to_date' ";		
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company_id, suplier_name as supplier_id, supplier_invoice_no as 'invoice_no' , our_po_ref_no
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b 
				WHERE 1 and a.id = b.si_hdr_id 
					and a.del !='Y' 
					AND a.approval_status !='Rejected' " . $sqla;
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type				= 'Supplier Invoice';
		$doc_no					= $row['id'];
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$supplier_id 			= $row['supplier_id'];
		$invoice_no 			= $row['invoice_no'];
		$our_po_ref_no  		= $row['our_po_ref_no'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code					= $r3['comp_code'];
		$budget_control_gst			= $r3['budget_control_gst'];
			
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name					= $r3['party_name'];
		
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){
			
			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];		
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			
			if($budget_control_gst=='N'){
				$amount = round( ($quantity * $unit_rate),0);
			}
			else {
				$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			}
			
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$cost_center_group 		= $r2['budget_name'];
			$cost_center_sub_group 	= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			
			$sql = " INSERT INTO dashboard_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', 
				'$invoice_no', '$product_category', '$product_name', '$party_name', 
				'$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start

	$sqla = " AND a.dated >= '$finance_from_date' and a.dated <= '$finance_to_date' ";
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id', a.approval_number 
			FROM `sma_travel_expenses` a, `sma_expenses` b
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'C'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' " . $sqla;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type				= 'Operating Expense';
		$doc_no					= $row['id'];
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		$approval_number 		= $row['approval_number'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code			= $r3['comp_code'];
			
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['party_name'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];	
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$cost_center_group 		= $r2['budget_name'];
			$cost_center_sub_group 	= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			
			$sql = " INSERT INTO dashboard_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', 
					'$approval_number', '$product_category', '$product_name', '$party_name', '$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End
	
	
//Travel Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id'
			FROM `sma_travel_expenses` a, `sma_expenses` b 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'T'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' " . $sqla;


echo $sql."<BR>"; 
//exit();	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_no					= $row['id'];
		$doc_type				= 'Travel Expense';
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code			= $r3['comp_code'];
			
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['username'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
		
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$cost_center_group 		= $r2['budget_name'];
			$cost_center_sub_group 	= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			
			$sql = " INSERT INTO dashboard_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value) 
					VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', 
					'$invoice_no', '$product_category', '$product_name', '$party_name', '$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
//exit();
			
		}

	}		
						
//Travel Expense End
	

//Regular Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id'
			FROM `sma_travel_expenses` a, `sma_expenses` b 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'R'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' " . $sqla ;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_no					= $row['id'];
		$doc_type				= 'Reimbursement';
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code			= $r3['comp_code'];
			
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['username'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
		
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT c.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b , sma_budget_subgroup c where a.budget_name = b.id and a.budget_head = c.id and a.id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$cost_center_group 		= $r2['budget_name'];
			$cost_center_sub_group 	= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			
			$sql = " INSERT INTO dashboard_trans_data ( doc_no, comp_code, doc_type, doc_mm_yyyy,  doc_date, doc_invoice_no, product_category, product_name, supplier_name, 
			cost_center_group, cost_center_sub_group, budget_code, total_value ) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', '$invoice_no', 
						'$product_category', '$product_name', '$party_name', '$cost_center_group', 
						'$cost_center_sub_group', '$budget_code', '$amount' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
			
		}

	}
						
//Regular Expense End
	
//		exit();
	
	
echo "Dashboard Transaction Data Process Over.."."<BR>";	
//echo "<script>window.close();</script>";	
	exit();
	
	?>
	
