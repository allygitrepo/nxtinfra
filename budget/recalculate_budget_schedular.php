<?php
	
	include("../dbcon.php");

	ini_set('max_execution_time', 0);
	
	$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
	$q3  = mysqli_query($con, $sql);
	$r3  = mysqli_fetch_array($q3);
	$from_date 		= $r3['from_date'];
	$to_date 		= $r3['to_date'];
	$account_year 	= $r3['short_fy_code'];
	
	// for 17-04-2024 
//	include("recalculate_po_bal_qty.php");
//	exit();
	
	// for 17-04-2024 
	
//exec('mysqldump --user=... --password=... --host=... DB_NAME > /path/to/output/file.sql');
//mysqldump -u <db_username> -h <db_host> -p db_name table_name > table_name.sql
//exec('mysqldump -h localhost -u athaangp2p -p 12345 athaangp2p sma_budget > athaangp2p_A.sql');

		$sql = "drop table budget_view_dump";
		mysqli_query($con, $sql);
		$sql = " create table budget_view_dump select * from sma_budget ";
		mysqli_query($con, $sql);
	
		$sql = "truncate table budget_calct";
		mysqli_query($con, $sql);

$sqlab = "";
//$sqlab = " AND b.budget_id = '78' ";
//$sqlab = " AND b.budget_id = '1076' ";
//$sqlab = " AND b.budget_id = '1485' ";
//$sqlab = " AND b.budget_id = '1081' ";
//$sqlab = " AND b.budget_id = '1556' ";

//Approval Memo Start c.account_year = '2022-2023'
		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var', a.status, a.overhead_exp
			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
			WHERE 1 and a.id = b.approval_hdr_id and b.budget_id = c.id and a.del !='Y' 
			  AND a.approval_status not in ( 'Amend',  'Rejected' ) 
			  AND c.account_year = '$account_year'
			   " . $sqlab ;
	
echo $sql."<BR>";	//and a.company = '$project_v' and b.budget_id = 60 'Suspend',
//exit();

	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'AP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 		= $row['check_var'];
		$status			= $row['status'];
		$overhead_exp	= $row['overhead_exp'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' ";
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
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($status=='Suspend' || $status=='Closed'){
				if($overhead_exp=='Y'){
					$sql  = "SELECT sum(b.amount) as amount_tot, sum(gst_amount) as gst_amount_tot, reference FROM `sma_travel_expenses` a, sma_expenses b  WHERE 1 and a.del !='Y' and a.exp_type ='C' and a.id = b.approval_ref_no  and approval_number = '$doc_no' and reference = '$product_id' ";
					$res  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r2 = mysqli_fetch_array($res);
					$amount_totp		=  $r2['amount_tot'];
					$gst_amount 		=  $r2['gst_amount_tot'];
					$reference_id 		=  $r2['reference'];
					$bal_amount = round($amount - ($amount_totp + $gst_amount) ,2) ;
					/* if($bal_amount<1){
						$bal_amount = 0;
					} */
					
					$amount = $amount - $bal_amount;
					
				}
			}
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var' ) ";
	//		mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}
	
//exit('Approval Memo End');
//Approval Memo End


//Purchase Order Start (only for PO cum Approval)
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var', a.status
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 and a.id = b.purchase_id 
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status not in ( 'Draft',  'Suspend', 'Rejected') 
					AND c.account_year = '$account_year' " . $sqlab ; //and po_type = 'C'
//	AND no_budget = ''  
echo $sql."<BR>";	//AND a.po_type = 'C' 'Amend',
					
	$project_v = $_POST['project'];
	if( !empty($budget_name_v) ){
		$sql .= " and c.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_head_v' ";
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'B';
		$doc_type		= 'PO';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 		= $row['check_var'];
		$status			= $row['status'];
		
		if($status	=='Draft'){
			continue;
		}
		
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' ";
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
			
			if($status=='Closed'){
				if($quantity == $bal_si_qty){
					$a='';
					//$quantity = $bal_si_qty;
				}
				else {
					$quantity = $quantity - $bal_si_qty;
				}
				//echo $status ." <<##1>> ". $quantity . ' ' . $bal_si_qty . "<BR>"; 
				
				if($bal_si_qty ==0 && $bal_si_amount==0){
					continue;	
				}
				
			}
			//bal_si_qty, bal_si_amount
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			if($status=='Closed'){
				if($quantity == $bal_si_qty){
					$amount =  $bal_si_amount;
				}
				else {
					$a='';
					$amount		= $r2['bal_si_amount'];
				}
				
				//echo $status ." <<##2>> ". $amount . ' '. $bal_si_amount. "<BR>"; 
			}
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	= $r3['name'];
			$category 		= $r3['category'];
			
			$bal_amount = 0;
			if($status=='Suspend' || $status=='Amend'){
				//$amount = $amount * -1;
				$bal_si_qty			= $r2['bal_si_qty'];
				$bal_si_amount		= $r2['bal_si_amount'];
				
				$bal_amount			= ($amount - $bal_si_amount ) * -1;
			
			}
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$status' ) ";
			mysqli_query($con, $sql);
//echo $sql. " ###3<BR>";			
			
			if($bal_amount<0){
				$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status) 
						VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$bal_amount', '0', '0', '0' , '$check_var', '$status' ) ";
				mysqli_query($con, $sql);
			}
//if($status=='Closed'){
//	echo $sql. " ###3<BR>";
//}
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' 
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected'  AND c.account_year = '$account_year' " . $sqlab ;

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
			$credit_note_value  = $r2['credit_note_value'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$amount = $amount - $credit_note_value;
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
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
					AND a.approval_status not in ('Amend','Rejected') AND c.account_year = '$account_year' " . $sqlab ;

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
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
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
					AND a.approval_status !='Rejected' AND c.account_year = '$account_year' " . $sqlab ;


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
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
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
					AND a.approval_status !='Rejected' AND c.account_year = '$account_year' " . $sqlab ;

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
			
			$sql = " INSERT INTO budget_calct (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Regular Expense End
	
	
//		exit();
	
?>


	
<?php		
				
	$sql = " SELECT b.* FROM budget_calct b WHERE 1 $sqlab order by budget_id, doc_date, doc_type, doc_no "; //sort_type, doc_type, budget_id,
	$res		 = mysqli_query($con, $sql);
	//$total_pages = mysqli_affected_rows($con);
				
//echo $sql." ##0<BR>";

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
		
		if($budget_id == 820 || $budget_id == 385 || $budget_id == 1081 || $budget_id == 1083){
			continue;	
		}	
		
		if($budget_id_prev	!= $budget_id){

			$sql = " UPDATE sma_budget set  blocked_budget = 0, used_budget = 0 WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql." ##2<BR>";		
		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];

		if( $doc_type=='BA' ){
			
			$doc_type = 'Budget Adjustment';
		
			$sql = " UPDATE sma_budget set blocked_budget = blocked_budget + $blocked_budget WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql." ##2<BR>";		
		}
		else if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
		
			$sql = " UPDATE sma_budget set blocked_budget = blocked_budget + $blocked_budget WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";			
/* if($budget_id== 389){
    echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";
} */	
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
				$sql = " UPDATE sma_budget set  blocked_budget = blocked_budget + $blocked_budget WHERE id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
//echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";				
/* if($budget_id== 389){
    echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";
} */				
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
			
			$sql = " UPDATE sma_budget set  blocked_budget = blocked_budget - $used_budget, used_budget = used_budget + $used_budget WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";				
/* if($budget_id== 389){
    echo $sql. "<BR>";
} */			
	//echo $sql." ##4<BR>";
				
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				
				$sql = " UPDATE sma_budget set  used_budget = used_budget + $used_budget WHERE id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
//echo $sql." ##5<BR>";		
/* if($budget_id== 389){
    echo $sql. "<BR>";
} */		
			}
			else {
				$sql = " UPDATE sma_budget set  blocked_budget = blocked_budget - $used_budget, used_budget = used_budget + $used_budget WHERE id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
//echo $sql. ' ' . $doc_type. ' ' . $doc_no. "<BR>";				
/* if($budget_id== 389){
    echo $sql. "<BR>";
} */

			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			$sql = " UPDATE sma_budget set  used_budget = used_budget + $used_budget WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
	//echo $sql." ##7<BR>";		
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			$sql = " UPDATE sma_budget set  used_budget = used_budget + $used_budget WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
	//echo $sql." ##8<BR>";
			
		}		
		
	}
	
		
	
		$sql = " UPDATE sma_budget set  blocked_budget = 0 WHERE blocked_budget < 0 
						AND account_year = '2025-2026'  ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
//include "recalculate_budget_mail.php";				

echo "Budget Recalculate Process Over.."."<BR>";	
//exit();
echo "<script>window.close();</script>";	
echo "<script>window.close();</script>";	
//	exit();
	
	?>
	