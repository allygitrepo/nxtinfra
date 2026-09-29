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
	
	$fin_yr_from 	= '2021-04-01';
	$fin_yr_to 		= '2022-03-31';

$sql 	= " SELECT * FROM `company` order by comp_name ";
$comp_result 	= mysqli_query($con, $sql);
echo mysqli_error($con);
//Company Loop Start
while($comp_row = mysqli_fetch_array($comp_result)){
	
	$project_v   	= $comp_row['comp_id'];
	$project_name  	= $comp_row['comp_name'];
	$project_code  	= $comp_row['comp_code'];
	
	echo $project_v . ' ==> ' . $project_name . ' ==> ' . $project_code . "<BR>";
/*	
working query for PO
SELECT a.id as purchase_id, 'PO' as ttype, a.project as company_id, b.budget_id, c.budget_name, c.budget_category, ( (b.quantity * b.unit_rate ) + ( (b.quantity * b.unit_rate ) * b.gst / 100) ) as amount, b.product_id  
FROM `sma_purchase_order` a, sma_po_items b, sma_budget c 
where a.dated >= '2021-04-01' and a.dated <= '2022-03-31' and a.status = 'Completed' and a.id = b.purchase_id and b.budget_id = c.id and c.provisional_flag !='Y' and a.del !='Y' and c.project = a.project order by a.project, c.budget_name, c.budget_category
working query for PO
*/
//Ist PO process and add to Block budget 
//IInd SI process and deduct Block budget and Add to User Budget
			$sql = " SELECT a.id as purchase_id, 'PO' as ttype, a.project as company_id, b.budget_id, 
				c.budget_name, c.budget_category, ( (b.quantity * b.unit_rate ) + ( (b.quantity * b.unit_rate ) * b.gst / 100) ) as amount, b.product_id  
				FROM `sma_purchase_order` a, sma_po_items b, sma_budget c 
				where a.status = 'Completed' and a.id = b.purchase_id and b.budget_id = c.id and c.provisional_flag !='Y' and a.del !='Y' and c.project = a.project order by a.project, c.budget_name, c.budget_category
				and a.project in ( $project_v )
				and a.dated >= '$fin_yr_from' and a.dated <= '$fin_yr_to'
					ORDER BY budget_category , id ";
			   
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
					and c.locked 			!='Y' 
					and a.provisional_flag  !-'Y'
					and e.invoice_date >= ' $fin_yr_from' and e.invoice_date <= '$fin_yr_to'
					and e.del				!='Y' 
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

