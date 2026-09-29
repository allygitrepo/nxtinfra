<?php
	$modulePath1 = "budget/";
	include "../dbcon.php";
//echo $from_date. ' <<>> ' . $to_date;	
		
		//$account_year	= $_POST['account_year'];
		//$project_v 		= $_POST['project'];
		
		$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$from_date 		= $r2['from_date'];
		$to_date   		= $r2['to_date'];
		$account_year 	= $r2['short_fy_code'];
		
		$sql = "truncate table budget_view";
		mysqli_query($con, $sql);
		
		
//Approval Memo Start
		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var', b.budget_id, a.approval_status as status, a.doctype, a.overhead_exp
			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
			WHERE 1 and approval_status not in ( 'Amend','Rejected' ) AND a.id = b.approval_hdr_id AND b.budget_id = c.id AND a.del !='Y'  
				AND c.account_year = '$account_year'
				AND dated 	>= '$from_date' 
				AND dated 	<= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'AP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$budget_id		= $row['budget_id'];
		$doctype		= $row['doctype'];
		$status			= $row['status'];
		$overhead_exp	= $row['overhead_exp'];
		
		$sql = "SELECT * FROM sma_approval_details WHERE approval_hdr_id = '$doc_no' and vendor_selected = 'Y' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_id 		= $r3['supplier_name'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
		$comp_code			= $r3['comp_code'];
			
		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$actual_value = $amount;
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$statuss = $status;
			if($status=='Suspend'){
				$statuss = 'Created';
			}
			
			//if($overhead_exp=='Y'){
				$sql  = "SELECT sum(b.amount) as amount_tot, sum(gst_amount) as gst_amount_tot, reference FROM `sma_travel_expenses` a, sma_expenses b  WHERE 1 and a.del !='Y' and a.exp_type ='C' and a.id = b.approval_ref_no  and approval_number = '$doc_no' and b.budget_id = '$budget_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($res);
				$amount_totp		=  $r2['amount_tot'];
				$gst_amount 		=  $r2['gst_amount_tot'];
				$reference_id 		=  $r2['reference'];
				$amount = round($amount - ($amount_totp + $gst_amount) ,2) ;
				if($bal_amount<1){
					$bal_amount = 0;
				}	
			//}
													
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var , party_id , doctype, status, actual_value) 
					VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$party_id' , '$doctype', '$statuss', '$actual_value' ) ";
			mysqli_query($con, $sql);

			if($status=='Suspend'){
				$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var , party_id , doctype, status, actual_value) 
						VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$party_id' , '$doctype', '$status', '$actual_value' ) ";
				mysqli_query($con, $sql);
			}
			
			
//echo $sql. "<BR>";
			
		}

	}
	
//exit();
//Approval Memo End


//Purchase Order Start
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var', b.budget_id, a.approval_status as status, approval_memo_ref, a.to_supplier as party_id
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 
					and a.id = b.purchase_id  and approval_status not in ( 'Suspend', 'Amend','Rejected' )
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND dated 	>= '$from_date' 
					 AND dated 	<= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'B';
		$doc_type		= 'PO';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		$status			= $row['status'];
		$approval_memo_ref	= $row['approval_memo_ref'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
		$comp_code			= $r3['comp_code'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql."<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$bal_si_qty			= $r2['bal_si_qty'];
			$bal_si_amount		= $r2['bal_si_amount'];
							
			$pi_amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$pi_amount = $quantity * $unit_rate;
			}
			
			$actual_value = $pi_amount;
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			$category		=$r3['category'];
			
			$amount =0;
			/* if($category=='S'){
				
				$amount = round(($bal_si_amount * $unit_rate) + ((($bal_si_amount * $unit_rate) * $gst) / 100),0);
				if($budget_control_gst =='N'){
					$amount = $bal_si_amount * $unit_rate;
				}
				
			}
			else {
				 */
				 
				$amount = round(($bal_si_qty * $unit_rate) + ((($bal_si_qty * $unit_rate) * $gst) / 100),0);
				if($budget_control_gst =='N'){
					$amount = $bal_si_qty * $unit_rate;
					
			 	}
			//}
			
			$amount = $pi_amount - $amount;
			
			if($amount < 0 ){
				$amount = $amount  * -1;	
			}
			
			$bal_amount = 0;
			if($status=='Suspend' || $status=='Amend'){
				//$amount = $amount * -1;
				$bal_si_qty			= $r2['bal_si_qty'];
				$bal_si_amount		= $r2['bal_si_amount'];
				
				$bal_amount			= ($amount - $bal_si_amount ) * -1;
				
				continue;
				
			}						  
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, actual_value) 
					VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount', '0', '0', '0' , '$check_var', '$status', '$party_id', '$actual_value' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' ,b.budget_id, a.our_po_ref_no, suplier_name as party_id, a.status
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 AND a.approval_status !='Rejected' and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND created_date 	>= '$from_date' 
					 AND created_date 	<= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'C';
		$doc_type		= 'SI';
		$doc_no 		= $row['id'];
		$our_po_ref_no	= $row['our_po_ref_no'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		
		$status			= $row['status'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
		$comp_code			= $r3['comp_code'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($amount>0){

				$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no,  po_srno, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id, status) 
						VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$our_po_ref_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var', '$party_id', '$status' ) ";
				mysqli_query($con, $sql);
				
			}

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.exp_type, a.company_id as company , a.approval_number as 'check_var', b.budget_id, approval_number, emp_id as party_id, onbehalf_emp_id, a.status
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1  AND a.approval_status !='Rejected' and a.id = b.approval_ref_no 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND a.dated 		>= '$from_date' 
					 AND a.dated 		<= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		$status				= $row['status'];
		
		$sort_type 		= 'D';
		$exp_type 		= $row['exp_type'];
		if($exp_type=='T'){
			$doc_type		= 'TE';
			$party_id 		= $onbehalf_emp_id;
		}
		else if($exp_type=='C'){
			$doc_type		= 'OP';
			$party_id 			= $row['party_id'];
		}
		if($exp_type=='R'){
			$doc_type		= 'RE';
			$party_id 		= $onbehalf_emp_id;
		}
		
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$approval_number	= $row['approval_number'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
		$comp_code 			= $r3['comp_code'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
					
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id, status ) 
					VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$approval_number', '$budget_id', '$product_name', '0', '$amount', '0', '0' , '$check_var', '$party_id', '$status' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End



//Budget Adjustment Start
	
		$sql = " SELECT id, dated, project, budget_name, budget_head, budget_code, budget_id, effect, amount, last_year_cf_block FROM `budget_adjust`  where 1  and status = 'Completed' ";
		//and status = 'Completed'
		
		$sql .= " AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'E';
		$doc_type		= 'BD';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$check_var 		= $row['effect'];
		$budget_id		= $row['budget_id'];
		$amount			= $row['amount'];
		$last_year_cf_block = $row['last_year_cf_block'];
		
		$blocked_budget = 0;
		if($last_year_cf_block=='Y'){
			$blocked_budget 	= $amount;
			
		}	
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id', '$amount', '$blocked_budget', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";

	}		
	
//Budget Adjustment End


//Budget Adjustment From To Start
	
		$sql = " SELECT id, dated, project, budget_id_from, budget_id_to, amount FROM `budget_adjust_from_to`  where 1  and status = 'Completed' ";
		
		$sql .= " AND project = '$project_v' 
				  AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'F';
		$doc_type		= 'BT';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$budget_id_from 	= $row['budget_id_from'];
		$budget_id_to		= $row['budget_id_to'];
		$amount				= $row['amount'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
		$amount_v = $amount * -1;
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_from', '$amount', 0, 'Add' , '$budget_id_to' ) ";
		mysqli_query($con, $sql);
				
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_to', '$amount_v', 0, 'Less', '$budget_id_from'  ) ";
		mysqli_query($con, $sql);
		
//echo $sql. "<BR>";

	}
	
//Budget Adjustment From To End