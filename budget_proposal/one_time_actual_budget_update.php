<?php
	
	include("../dbcon.php");

	ini_set('max_execution_time', 0);
	
 		$sql = "truncate table one_budget_calc ";
		mysqli_query($con, $sql);

$sqlab = "";
$from_date = '2023-04-01';
$to_date   = '2023-12-31';
//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' 
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected'  
					AND a.created_date >= '$from_date' and a.created_date <= '$to_date' ";

//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'C';
		$doc_type		= 'SI';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' ";
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
			
			$sql = " INSERT INTO one_budget_calc  (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , a.approval_number as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'C'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status not in ('Amend','Rejected') AND a.dated >= '$from_date' AND a.dated <= '$to_date' " ;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'OP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
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
			
			$sql = " INSERT INTO one_budget_calc  (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End
	
	
//Travel Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , '' as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'T'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected'  AND a.dated >= '$from_date' AND a.dated <= '$to_date' " ;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'TE';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
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
			
			$sql = " INSERT INTO one_budget_calc  (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Travel Expense End
	

//Regular Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , '' as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'R'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' AND a.dated >= '$from_date' AND a.dated <= '$to_date' " ;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'RE';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
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
			
			$sql = " INSERT INTO one_budget_calc  (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Regular Expense End

//exit('Exit HERE...');	 
?>



<?php		
	$sql = " UPDATE sma_budget_proposal_details SET current_year_comsume = 0";
	mysqli_query($con, $sql);
	
	$sql = " SELECT * FROM one_budget_calc  WHERE 1 order by budget_id, doc_date, doc_type, doc_no "; //sort_type, doc_type, budget_id,
	$res		 = mysqli_query($con, $sql);
echo $sql." ##0<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$doc_type	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];		
		$product_name 		= $row['items'];
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		$status		 		= $row['status'];
		
		if($budget_id==0){
			continue;
		}	
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];

		$sql = " SELECT * FROM sma_budget where id = '$budget_id' ";
//echo $sql ."<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($res);
		$project 		= $r2['project'];
		$budget_name 	= $r2['budget_name'];
		$budget_head 	= $r2['budget_head'];
		
		$sql = " UPDATE sma_budget_proposal_details set current_year_comsume = 0";
		
		if( $doc_type=='SI' ){
			
			$sql = " UPDATE sma_budget_proposal a, sma_budget_proposal_details b SET current_year_comsume = current_year_comsume + $used_budget 
					WHERE a.id = b.hdr_id AND a.company_id = '$project' AND b.cost_group_id = '$budget_name' 
					AND b.cost_subgroup_id = '$budget_head' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
				
		}
		else if( $doc_type=='OP' ){
			
				$sql = " UPDATE sma_budget_proposal a, sma_budget_proposal_details b SET current_year_comsume = current_year_comsume + $used_budget 
					WHERE a.id = b.hdr_id AND a.company_id = '$project' AND b.cost_group_id = '$budget_name' 
					AND b.cost_subgroup_id = '$budget_head' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
		
		}
		else if( $doc_type=='TE' ){
			
			$sql = " UPDATE sma_budget_proposal a, sma_budget_proposal_details b SET current_year_comsume = current_year_comsume + $used_budget 
					WHERE a.id = b.hdr_id AND a.company_id = '$project' AND b.cost_group_id = '$budget_name' 
					AND b.cost_subgroup_id = '$budget_head' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}
		else if( $doc_type=='RE' ){
			
			$sql = " UPDATE sma_budget_proposal a, sma_budget_proposal_details b SET current_year_comsume = current_year_comsume + $used_budget 
					WHERE a.id = b.hdr_id AND a.company_id = '$project' AND b.cost_group_id = '$budget_name' 
					AND b.cost_subgroup_id = '$budget_head' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}

//echo $sql ."<BR>";
//exit('TEST STOP...');
		
	}

echo "One Time Budget Recalculate Process Over.."."<BR>";	
//exit();
//echo "<script>window.close();</script>";	
	exit();
	
	?>
	