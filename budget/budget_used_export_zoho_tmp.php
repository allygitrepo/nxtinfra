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

	$sql = " TRUNCATE budget_used_zoho_tmp ";
	mysqli_query($con,$sql);
	
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
	/* $sql = "SELECT distinct(a.si_hdr_id) as id , 'SI' as ttype, a.material_id, a.description, e.company_id, a.budget_id, a.amount , 
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
			    */
			   
			$sql = "SELECT a.id as budget_id, project, budget_name as budget_name_id, budget_category as budget_head_id, total_budget, 		used_budget, b.comp_name as company_name, c.name as budget_name , d.category as budget_head
				FROM `sma_budget` a, company b, sma_budget_name c, sma_budget_category d
					WHERE a.project = b.comp_id and a.budget_name = c.id and a.budget_category = d.id";
	    
//echo $sql."<BR>";
//exit();
$i=0;
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			//$total_budget   	= $row['total_budget'];
			
			$company_name 		= $row['company_name'];
			$budget_name 		= $row['budget_name'];
			$budget_head	 	= $row['budget_head'];
			$total_budget	 	= $row['total_budget'] / 12;
			
			$project_id	 		= $row['project'];
			$budget_head_id	 	= $row['budget_head_id'];
			$budget_name_id	 	= $row['budget_name_id'];
			
			$sql = "SELECT * FROM `company` WHERE comp_id = '$project_id'";			
			$cmp = mysqli_query($con, $sql);
			$r = mysqli_fetch_array($cmp);
			$fin_date = $r['comp_start_date'];
			$fin_to_date = $r['comp_end_date'];
			
			$fin_year = date('Y', strtotime($fin_date)).'-'.date('Y', strtotime($fin_to_date));
			
			
			for($i=1;$i<13;$i++){
				
				if($i==1){
					$mm_yy = '2020-04-01';
					$fin_date = $mm_yy;
				}
				else if($i==2){
					$mm_yy = '2020-05-01';
					$fin_date = $mm_yy;
				}
				else 
					if($i==3){
					$mm_yy = '2020-06-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==4){
					$mm_yy = '2020-07-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==5){
					$mm_yy = '2020-08-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==6){
					$mm_yy = '2020-09-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==7){
					$mm_yy = '2020-10-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==8){
					$mm_yy = '2020-11-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==9){
					$mm_yy = '2020-12-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==10){
					$mm_yy = '2021-01-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==11){
					$mm_yy = '2021-02-01';
					$fin_date = $mm_yy;
				}
				else
					if($i==12){
					$mm_yy = '2021-03-01';
					$fin_date = $mm_yy;
				}

				$sql= " INSERT INTO budget_used_zoho_tmp (fin_year, fin_mm_yy, company, budget_name, budget_head, vendor_name, doc_type, po_no, si_no, tran_date, category, material_name, total_budget ) values ( '$fin_year', '$mm_yy', '$company_name', '$budget_name', '$budget_head', '', 'Op Bal', '', '', '$fin_date', '', '', '$total_budget' )";
				mysqli_query($con, $sql);

			}
			
//echo $sql."<BR>"; exit('EXIT HERE....');

			$sql = "SELECT dated, project, budget_name, budget_head, effect, amount, remarks 
				FROM `budget_adjust` a, company b, sma_budget_name c, sma_budget_category d
				WHERE a.project = b.comp_id and a.budget_name = c.id and a.budget_head = d.id 
				and	project= '$project_id' and budget_name= '$budget_name_id' and budget_head = '$budget_head_id' ";
			$cmpa = mysqli_query($con, $sql);
			while ($ra = mysqli_fetch_array($cmpa)){
			$adjust_date 	= $ra['dated'];
			$remarks 		= $ra['remarks'];
			$effect 		= $ra['effect'];
			$amount 		= $ra['amount'];
			if($effect=='D'){
				$amount = $amount * -1;
			}
			
				$sql= " INSERT INTO budget_used_zoho_tmp ( fin_year, company, budget_name, budget_head, vendor_name, doc_type, si_no, tran_date, category, material_name, total_budget, remarks ) 
					values ( '$fin_year', '$company_name', '$budget_name', '$budget_head', '', 'Adjustment',  '', 
					'$adjust_date', '', '', '$amount', '$remarks')";
				mysqli_query($con, $sql);
			}	
				
			$sql = "SELECT distinct(a.si_hdr_id) as si_id , 'SI' as ttype, a.material_id, a.description, e.company_id, e.suplier_name, 
					a.budget_id, a.amount, b.id as material_id , b.name as material_name, b.`group` as material_group, 
					c.budget_category as budget_category, e.our_po_ref_no, e.invoice_date, d.description
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_product_group d, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id and e.status = 'Completed'
					and e.company_id	 	= '$project_id'
					and b.group 			= d.id
					and c.id				= a.budget_id
					and d.budget_head		= c.budget_category
					and d.budget_name		= c.budget_name
					and c.budget_category 	= '$budget_head_id'
					and a.si_hdr_id 		= e.id
					and c.locked 			!='Y' 
					and e.del				!='Y' ";

			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
				
				$si_hdr_id			= $r2['si_id'];
				$invoice_date 		= date('Y-m-d', strtotime($r2['invoice_date']));
				$amount				= $r2['amount'];
				$material_group 	= $r2['material_group'];
				$material_name  	= $r2['material_name'];
				$suplier_id		  	= $r2['suplier_name'];
				$our_po_ref_no		= $r2['our_po_ref_no'];
				$product_category	= $r2['description'];
				$doc_type			= 'Supplier';
				$sql  	= " SELECT * FROM `sma_party_mst` where id = '$suplier_id' ";
				$q7 	= mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r7 	= mysqli_fetch_array($q7);
				$party_name   	= $r7['party_name'];
				$sql  = " SELECT * FROM `sma_purchase_order` where id = '$our_po_ref_no' ";
				$q6 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r6 = mysqli_fetch_array($q6);
				$po_number   	= $r6['po_number'];
				
				$sql= " INSERT INTO budget_used_zoho_tmp ( company, budget_name, budget_head, vendor_name, doc_type, po_no, si_no, tran_date, category, material_name, total_budget ) 
				values ( '$company_name', '$budget_name', '$budget_head', '$party_name', '$doc_type', '$po_number', '$si_hdr_id', '$invoice_date', '$product_category', '$material_name', '$amount')";
				mysqli_query($con, $sql);
				
			}
			
			
			$sql = "SELECT 	distinct(a.approval_ref_no) as ce_id, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no, c.emp_id, c.exp_type, c.dated
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type in ( 'C','T','R')  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_id )
			   and d.budget_category 	= '$budget_head_id' 
			   and d.locked 			!='Y'  
			   and c.del				!='Y' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
				
				$si_hdr_id			= $r2['ce_id'];
				$invoice_date 		= date('Y-m-d', strtotime($r2['dated']));
				$amount				= $r2['amount'];
				$material_group 	= $r2['material_group'];
				$material_name  	= $r2['material_name'];
				$emp_id	    		= $r2['emp_id'];
				$exp_type			= $r2['exp_type'];
				$product_category	= $r2['description'];
				$doc_type			= '';
				
				if($exp_type=='C'){
					$sql  	= " SELECT * FROM `sma_party_mst` where id = '$emp_id' ";
					$q7 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r7 	= mysqli_fetch_array($q7);
					$party_name   	= $r7['party_name'];
					$doc_type			= 'Company Expense';
				}
				else 
					if($exp_type=='R'){
					$sql  	= " SELECT * FROM `sma_user` where id = '$emp_id' ";
					$q7 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r7 	= mysqli_fetch_array($q7);
					$party_name   	= $r7['username'];
					$doc_type			= 'Regular Expense';
				}
				else if($exp_type=='T'){
					$sql  	= " SELECT * FROM `sma_user` where id = '$emp_id' ";
					$q7 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r7 	= mysqli_fetch_array($q7);
					$party_name   	= $r7['username'];
					$doc_type			= 'Travel Expense';
				}
				
				$sql= " INSERT INTO budget_used_zoho_tmp ( fin_year, company, budget_name, budget_head, vendor_name, doc_type, si_no, tran_date, category, material_name, total_budget ) 
				values ( '$fin_year', '$company_name', '$budget_name', '$budget_head', '$party_name', '$doc_type',  '$si_hdr_id', '$invoice_date', '$product_category', '$material_name', '$amount')";
				mysqli_query($con, $sql);
				
			}
			
//echo $sql."<BR>"; exit();
			
			/* $i=$i+1;
		if($i==3){	
			echo $sql."<BR>"; exit();
		} */
			
		}
			
echo 'Process Over....';
//echo $message;

	
    
}

		