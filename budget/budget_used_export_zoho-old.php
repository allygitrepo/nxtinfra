<?php
if($_GET['sub'] == 'pdf'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$sql = " TRUNCATE budget_used_zoho ";
	mysqli_query($con,$sql);
	
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
	$sql = "SELECT distinct(a.si_hdr_id) as id , 'SI' as ttype, a.material_id, a.description, e.company_id, a.budget_id, a.amount , 
			b.id as material_id , b.name as material_name, b.`group` as material_group, c.budget_category as budget_category, '' as invoice_no
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_product_group d, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id and e.status = 'Completed'
					and b.group 			= d.id
					and c.budget_category 	= d.budget_head
					and a.si_hdr_id 		= e.id
					and c.locked 			!='Y' 
					and e.del				!='Y' 
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'CO' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'C'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and d.locked 			!='Y'  
			   and c.del				!='Y' 
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'TE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'T'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and d.locked 			!='Y'  
			   and c.del				!='Y'
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'RE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'R'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and d.locked 			!='Y'  
			   and c.del				!='Y' 
			   ORDER BY budget_category , id ";
			    
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$ttype			= $row['ttype'];
			if($ttype=='SI'){
				$si_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_supplier_invoice` where id = '$si_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				$our_po_ref_no  = $r1['our_po_ref_no'];
				$suplier_name	= $r1['suplier_name'];
				$invoice_date 	= date('Y-m-d', strtotime($r1['invoice_date']));
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' ){
				$si_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_travel_expenses` where id = '$si_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				$suplier_name	= $r1['emp_id'];
				$invoice_date 	= date('Y-m-d', strtotime($r1['dated']));
			}
			
			$amount				= $row['amount'];
			$material_group 	= $row['material_group'];
			$budget_category	= $row['budget_category'];
			$material_name  	= $row['material_name'];
			
			if($ttype=='SI'){
				$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head , b.description
				FROM `sma_budget` a, sma_product_group b
				where a.locked !='Y' and a.budget_name = b.budget_name 
					and a.budget_category 	= b.budget_head
					and b.id				= '$material_group'
					and a.budget_category 	= '$budget_category'
					and a.project 			= '$company_id'
					and a.locked 			!='Y'
				ORDER BY `a`.`budget_name` ASC ";
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' ){
				$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head , b.account_name, '' as description
				FROM `sma_budget` a, account_mst b
				where a.locked !='Y' and a.budget_name = b.budget_name  
					and a.budget_category 	= b.budget_head
					and a.budget_category 	= '$budget_category'
					and a.project 			= '$company_id'
					and a.locked 			!='Y'
				ORDER BY `a`.`budget_name` ASC ";
			}
			
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$total_budget   	= $r2['total_budget'];
			$used_budget		= $amount;
			$balance_budget 	= $total_budget - $used_budget;
		
			$project 			= $r2['project'];
			$budget_name 		= $r2['budget_name'];
			$budget_head	 	= $r2['budget_category'];
			$budget_head	 	= $r2['budget_head'];
			
			$product_category	= $r2['description'];

			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3 = mysqli_fetch_array($res);
			$comp_name = $r3['comp_name'];
			
			$project_prev = $company_id;
			
			$budget_category = $budget_head;
			$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
		
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			
			$category = $r2['category'];
			
			$budget_name = $budget_name;
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			
			$bname = $r2['name'];
			
			if($ttype=='SI'){
				$sql  = " SELECT * FROM `sma_purchase_order` where id = '$our_po_ref_no' ";
				$q6 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r6 = mysqli_fetch_array($q6);
				$po_number   	= $r6['po_number'];
			}
			else {
				$po_number   	= $row['invoice_no'];
			}
			
			$sql  = " SELECT * FROM `sma_party_mst` where id = '$suplier_name' ";
			$q7 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r7 = mysqli_fetch_array($q7);
			$party_name   	= $r7['party_name'];
			
			if($ttype=='CO'){
				$doc_type		= 'Company';
			}
			else if($ttype=='SI'){
				$doc_type		= 'Supplier';
			}
			else if($ttype=='TE'){
				$doc_type		= 'Travel';
				
				$sql  = " SELECT * FROM `sma_user` where id = '$suplier_name' ";
				$q7 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r7 = mysqli_fetch_array($q7);
				$party_name   	= $r7['username'];
				
			}
			else if($ttype=='RE'){
				$doc_type		= 'Regular';
				$sql  = " SELECT * FROM `sma_user` where id = '$suplier_name' ";
				$q7 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r7 = mysqli_fetch_array($q7);
				$party_name   	= $r7['username'];
			}
			
			if( $budget_head_prev != $budget_head ){
				$bal_budget = $total_budget;
			}
			
			$budget_head_prev = $budget_head ;
			$category_prev    = $category;
			
			$bal_budget = $bal_budget - $used_budget;
			
			$sql= " INSERT INTO budget_used_zoho ( company, budget_name, budget_head, vendor_name, doc_tye, po_no, si_no, tran_date, category, material_name, total_budget, used_budget, balance_budget ) values ( '$comp_name', '$bname', '$category', '$party_name', '$doc_type', '$po_number', '$si_hdr_id', '$invoice_date', '$product_category', '$material_name', '$total_budget', '$used_budget', '$bal_budget' )";
			mysqli_query($con, $sql);
			
		}
			
echo 'Process Over....';
//echo $message;
exit();
	
    
}

		