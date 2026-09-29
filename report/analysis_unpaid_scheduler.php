<?php
//session_start();
include("../dbcon.php");
include("../baseurl.php");

//if($_GET['sub']=='pro'){
	
//	$comid  = $_SESSION['comid'];
	
	$sql = "truncate analysis_unpaid";
	mysqli_query($con, $sql);

ini_set('max_execution_time', 0);

//Supplier Invoice Start
	$sql 	= "SELECt a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_supplier_invoice a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_supplier_invoice  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'S' and c.del !='Y' ) as DS 
				ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
				and a.del !='Y' 
				ORDER BY `a`.`id` ASC";
				
	$sql 	= " SELECT * FROM sma_supplier_invoice
						 WHERE status = 'Completed' AND del !='Y'  ";
	$result = mysqli_query($con, $sql);
	//$si_rowaffect = mysqli_affected_rows($con);
	echo mysqli_error($con);
//echo $sql."<BR>";	
echo $si_rowaffect . ' ' . "Processing...Wait !!!";
//exit();
	while($row = mysqli_fetch_array($result)){
		
		$del				= $row['del'];	
		//$utr_no				= $row['utr_no'];
		$module				= 'SI';
		$status				= $row['status'];
		$our_po_ref_no		= $row['our_po_ref_no'];
		$doc_id				= $row['id'];		
		
		$sql = " SELECT a.* FROM payment_header a, payment_details b 
					WHERE  1 and del !='Y' and b.payment_hdr_id = a.id AND a.st_flag = 'S' AND b.supp_id = '$doc_id' order by utr_no ";
		$res = mysqli_query($con, $sql);	
		//echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$utr_no = $r1['utr_no'];
		if(!empty($utr_no) ){
			continue;
		}
		
		$sql  = " SELECT count(*) as si_rowaffect from tally_journal_entry where doc_type = 'SI' and effect = 'Cr' and doc_no = '$doc_id' and account_name like '%Advance%' ";
		$res = mysqli_query($con, $sql);		
		//$si_rowaffect = mysqli_affected_rows($con);
		//echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$si_rowaffect = $r1['si_rowaffect'];
		if($si_rowaffect>0){
			continue;
		}

//echo $sql ."<BR>";
//exit();
			
		include "analysis_pending_common.php";
		
		$doc_id				= $row['id'];		
		$company_id			= $row['company_id'];
		$amount				= $row['payable_amount'];
		//$utr_no				= $row['utr_no'];
		$module				= 'SI';
		$status				= $row['status'];

		if($status=='Completed'){
			$pending_with='';
		}
		
			
			$sql  = " SELECT * from sma_purchase_order where advance_flag = 'Y' and del !='Y' and id = '$our_po_ref_no' ";
					
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$advance_paid_amount	= $r1['paid_amount'];
			$total_po_amount		= $r1['total_po_amount'];
			$paid_status			= $r1['paid_status'];
			if($paid_status=='Paid'){
				continue;
			}
					
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
			mysqli_error($con);

		}
		
	}
//Supplier Invoice End
//exit('SI Exit Here...');

//Operating Expense Start
		$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_travel_expenses a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_travel_expenses  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'C' and a.total_amount >1 and c.del !='Y' ) as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y' and a.status = 'Completed' 
					where a.total_amount >1
				ORDER BY `a`.`id` ASC ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$ce_rowaffect = mysqli_affected_rows($con);
//echo $sql."<BR>";	
//echo $ce_rowaffect . ' ' . "Processing...Wait !!!";
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		
		$utr_no				= $row['utr_no'];
		$status				= $row['status'];
		if(!empty($utr_no) ){
			continue;
		}
		
		if($del =='Y'){
			continue;
		}
		include "analysis_pending_common.php";
		
		$doc_id				= $row['id'];
		if($doc_id == 38){
			continue;
		}			
		$company_id			= $row['company_id'];
		
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
		//$module			= 'CE';
		$status				= $row['status'];
		$exp_type			= $row['exp_type'];
		if($exp_type=='C'){
			$module				= 'CE';
		}
		if($status=='Completed'){
			$pending_with='';
		}

		if(empty($utr_no) && $exp_type=='C' && $status=='Completed' ){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id', '$utr_no', '$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Operating Expense End

//Travel/Regular Expense Start
		$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_travel_expenses a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_travel_expenses  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'T' and c.del !='Y') as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y' and a.status = 'Completed' 
				ORDER BY `a`.`id` ASC ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
$ce_rowaffect = mysqli_affected_rows($con);
//echo $sql."<BR>";	
//echo $ce_rowaffect . ' ' . "Processing...Wait !!!";
						
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		$utr_no				= $row['utr_no'];
		$status				= $row['status'];
		$exp_type			= $row['exp_type'];
		if($exp_type=='C' || $exp_type=='D'){
			continue;
		}
		
		if(!empty($utr_no) ){
			continue;
		}
		
		if($del =='Y'){
			continue;
		}
		include "analysis_pending_common.php";
		
		
		$doc_id				= $row['id'];	
		$company_id			= $row['company_id'];
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
			
		if($exp_type=='T'){
			$module				= 'TE';
		}
		if($exp_type=='R'){
			$module				= 'RE';
		}
		
		$status				= $row['status'];
		if($status=='Completed'){
			$pending_with = '';
		}
	
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Travel /Regular Expense End

	echo "Process Over...";

	$sql = " TRUNCATE analysis_unpaid_matrix";
	mysqli_query($con, $sql);
	
	$sql="SELECT company_with FROM `analysis_unpaid` where 1  group by  company_with ";
	$sql .= ' order by company_with, module ';
	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$company_with		= $row['company_with'];
			
			$sql = " INSERT INTO analysis_unpaid_matrix ( company_with ) VALUE ('$company_with' )";
			mysqli_query($con, $sql);
			
	}
			
	$sql="SELECT module, company_with, count(*) as cnt, sum(amount) as amount  FROM `analysis_unpaid` where 1  group by module, company_with ";
	$sql .= ' order by company_with, module ';

	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$count 				= $row['cnt'];
			$amount 			= $row['amount'];
			$module 			= $row['module'];
			$company_with		= $row['company_with'];
			
			
			if($module=='PO'){
				$sql = " UPDATE analysis_unpaid_matrix SET PO = '$count', PO_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='SI'){
				$sql = " UPDATE analysis_unpaid_matrix SET SI = '$count', SI_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='CE'){
				$sql = " UPDATE analysis_unpaid_matrix SET CE = '$count', CE_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='TE'){
				$sql = " UPDATE analysis_unpaid_matrix SET TE = '$count', TE_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='RE'){
				$sql = " UPDATE analysis_unpaid_matrix SET RE = '$count', RE_amount = '$amount' where company_with = '$company_with' ";
			}
			
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}	
		
//exit();

	echo "<script>window.close();</script>";

//	$baseurl1= $baseurl."report/analysis_unpaid.php?sub=list";
//	echo "<script>window.location.href='$baseurl1';</script>";
	
//}

?>