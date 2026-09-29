<?php
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

	$grand_total_amount = '';
	$budget_head_prev	= '';
	$project_prev = '';
		
	$sqlb  = '';
	$sqlh  = '';
	$sqlhd = '';
	$sqlbd = '';
	
$sql 	= " SELECT * FROM `company` order by comp_name ";
$comp_result 	= mysqli_query($con, $sql);
echo mysqli_error($con);
//Company Loop Start
while($comp_row = mysqli_fetch_array($comp_result)){
	
	$project_v   	= $comp_row['comp_id'];
	$project_name  	= $comp_row['comp_name'];
	$project_code  	= $comp_row['comp_code'];
	
	echo $project_v . ' ==> ' . $project_name . ' ==> ' . $project_code . "<BR>";
	
	$fin_yr_from 	= '2021-04-01';
	$fin_yr_to 		= '2022-03-31';

		$sql = " SELECT distinct(a.si_hdr_id) as id , 'SI' as ttype, a.material_id, a.description, e.company_id, a.budget_id, a.amount , 
			b.id as material_id , b.name as material_name, b.`group` as material_group, c.budget_category as budget_category, '' as invoice_no
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_product_group d, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id and e.status = 'Completed'
					and e.company_id	 	in ( $project_v )
					and b.group 			= d.id
					and c.id				= a.budget_id
					and d.budget_head		= c.budget_category
					and d.budget_name		= c.budget_name
					and a.si_hdr_id 		= e.id
					and c.locked 			!='Y' and e.invoice_date >= '$fin_yr_from' and e.invoice_date <= '$fin_yr_to'
					and e.del				!='Y' $sqlh $sqlb	
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'CO' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'C'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  and c.dated >= '$fin_yr_from' and c.dated <= '$fin_yr_to'
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'TE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'T'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y' and c.dated >= '$fin_yr_from' and c.dated <= '$fin_yr_to'
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'RE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'R'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  and c.dated >= '$fin_yr_from' and c.dated <= '$fin_yr_to'
			   and c.del				!='Y'  $sqlhd 	$sqlbd
			union
			SELECT 	distinct(a.approval_ref_no) as id, 'PC' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.expense_id as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_pettycash_exp a , account_mst b , sma_pettycash c , sma_budget d , sma_budget_category e
			 where b.id = a.expense_id and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  and c.dated >= '$fin_yr_from' and c.dated <= '$fin_yr_to'
			   and c.del				!='Y'  $sqlhd 	$sqlbd
			   
			   ORDER BY budget_category , id ";
//echo $sql."<BR>"; exit();

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
				$invoice_date 	= date('d-m-Y', strtotime($r1['invoice_date']));
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' ){
				$si_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_travel_expenses` where id = '$si_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				//$our_po_ref_no  = $row['invoice_no'];
				$suplier_name	= $r1['emp_id'];
				$invoice_date 	= date('d-m-Y', strtotime($r1['dated']));
			}
			else if($ttype=='PC'){
				$pc_hdr_id 		= $row['id'];
				$sql 	= " SELECT * FROM `sma_pettycash` where id = '$pc_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$company_id   	= $r1['company_id'];
				$location_id	= $r1['location_id'];
				$invoice_date 	= date('d-m-Y', strtotime($r1['dated']));
				
				$sql 	= " SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$pc_hdr_id' ";
				$q1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($q1);
				$suplier_name   	= $r1['spend_by'];
				$paid_to		   	= $r1['paid_to'];
				
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
					and a.budget_category 	= '$budget_category'
					and a.project 			= '$company_id'
					and a.locked 			!='Y
					and a.provisional_flag  !='Y'
				ORDER BY `a`.`budget_name` ASC ";
				
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' || $ttype=='PC'){
				$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head , b.account_name as description
				FROM `sma_budget` a, account_mst b
				where a.locked !='Y' and a.budget_name = b.budget_name  
					and a.budget_category 	= b.budget_head
					and a.budget_category 	= '$budget_category'
					and a.project 			= '$company_id'
					and a.locked 			!='Y'
					and a.provisional_flag  !-'Y'
				ORDER BY `a`.`budget_name` ASC ";
			}
	
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$total_budget   	= $r2['total_budget'];
			$used_budget		= $amount;
			
			$project 			= $r2['project'];
			$budget_name 		= $r2['budget_name'];
			$budget_head	 	= $r2['budget_category'];
			$budget_head	 	= $r2['budget_head'];
			
//echo $project_tmp. ' ' . $project_v. ' ' . $budget_head_v. ' ' . $budget_name_v."<BR>";

			if( $project_tmp != 'All' ){
				if(!empty($project_v) ){
					if( $project_v != $project ){
						continue;
					}
				}
			}
			
			if ( !empty($budget_head_v) && $budget_head_v != 'All' ){
				if( $budget_head_v != $budget_head ){
					continue;
				}
			}
			if ( !empty($budget_name_v)  ){
				if( $budget_name_v != $budget_name ){
					continue;
				}
			}
			
	//echo $budget_head_prev . ' != ' . $budget_head ."<BR>";		
			
			if( $budget_head_prev != $budget_head ){
					$sql = "update sma_budget set used_budget = '0' where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";
					mysqli_query($con, $sql);
					
				//echo $sql; exit();
				
			}
			
			$budget_head_prev = $budget_head ;
			if($ttype=='SI'){
					
					$sql = "update sma_budget set blocked_budget = blocked_budget + $amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";
					mysqli_query($con, $sql);
					
					$sql = "update sma_budget set blocked_budget = blocked_budget - $amount, used_budget = used_budget + $amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";
					mysqli_query($con, $sql);		
					//echo $sql; exit();
				
			}
			else {
						
				$sql = "update sma_budget set used_budget = used_budget + $amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";
				mysqli_query($con, $sql);
			}
			
//echo $sql."<BR>";			
			
		}
			
	}
//Company Loop End

		//	$sql = "update sma_budget set used_budget = $used_amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";

		//echo $sql; 
		echo "Process over....";
		
//echo $message;
exit();

