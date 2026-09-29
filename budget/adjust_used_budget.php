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

	$prn		= "excel";
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		

		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
	//$tableName	= "sma_budget";
		$_SESSION['project_a'] 		= $_POST['project'];
		$_SESSION['budget_name_a'] 	= $_POST['budget_name'];
		$_SESSION['budget_head_a'] 	= $_POST['budget_head'];
					
		$project_v 		= $_SESSION['project_a'];
		$budget_name_v 	= $_SESSION['budget_name_a'];
		$budget_head_v 	= $_SESSION['budget_head_a'];
		
		if ($budget_head_v!='All'){
			$sqlhd = " and budget_category = '$budget_head_v'";
		}	
		
		if(!empty($project_v)){
			$sql = "update sma_budget set used_budget = '0' where project= '$project_v' and budget_name = '$budget_name_v' ". $sqlhd ;
//RAVI			mysqli_query($con,$sql);
		}

//echo $sql. '<br>';

/* 		$project_v 		= 3;
		$budget_name_v 	= 1;
		$budget_head_v 	= 4; */		
		
	$sqlb = '';
	$sqlh = '';
	if( !empty($budget_head_v) && $budget_head_v != 'All' ){
		$sqlh .= " and c.budget_category 	= '$budget_head_v' ";
		$sqlhd .= " and d.budget_category 	= '$budget_head_v' ";
	}
	if(!empty($budget_name_v)){
		$sqlb .= " and c.budget_name		= '$budget_name_v' ";
		$sqlbd .= " and d.budget_name		= '$budget_name_v' ";
	}

	/* $sql = "SELECT distinct(a.si_hdr_id) as id , 'SI' as ttype, a.material_id, a.description, e.company_id, a.budget_id, a.amount , 
			b.id as material_id , b.name as material_name, b.`group` as material_group, c.budget_category as budget_category, '' as invoice_no
				FROM sma_supplier_invoice_details a, `sma_product` b , `sma_budget` c, sma_product_group d, sma_supplier_invoice e
					WHERE si_srno > 0 and a.material_id =  b.id and e.status = 'Completed'
					and b.group 			= d.id
					and c.budget_category 	= d.budget_head
					and a.si_hdr_id 		= e.id
					and e.company_id 		in ( $project_v )
					and c.locked 			!='Y' 
					and e.del				!='Y' $sqlh 	$sqlb */
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
					and e.del				!='Y' $sqlh $sqlb	
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'CO' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'C'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'TE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'T'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
			   and c.del				!='Y' $sqlhd 	$sqlbd
			union					
			SELECT 	distinct(a.approval_ref_no) as id, 'RE' as ttype, b.id as material_id , a.note as description, c.company_id, '' as budget_id, a.amount, a.reference as material_id,  b.account_name as material_name, b.budget_head as material_group,  d.budget_category as budget_category, a.invoice_no as invoice_no
			  from sma_expenses a , account_mst b , sma_travel_expenses c , sma_budget d , sma_budget_category e
			 where b.id = a.reference and a.exp_type = 'R'  and c.status = 'Completed'
			   and a.approval_ref_no 	= c.id 
			   and d.budget_category 	= b.budget_head
			   and d.budget_category 	= e.id
			   and c.company_id 		in ( $project_v )
			   and d.locked 			!='Y'  
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
					and a.locked 			!='Y'
				ORDER BY `a`.`budget_name` ASC ";
				
			}
			else if($ttype=='CO' || $ttype=='TE' || $ttype=='RE' ){
				$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head , b.account_name as description
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
			
			$sql = "update sma_budget set used_budget = used_budget + $amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";
			mysqli_query($con, $sql);
//echo $sql."<BR>";			
			
		}
			
			$sql = "update sma_budget set used_budget = $used_amount where project= '$project' and budget_name = '$budget_name' and budget_category = '$budget_head' ";

		//echo $sql; 
		echo "Process over....";
		$baseurl1 = $baseurl . "budget/adjust_budget.php?sub=list" ;
		echo "<script>window.location.href='$baseurl1';</script>";
		
//echo $message;
//exit();

}

		