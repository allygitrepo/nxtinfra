<?php

	session_start();
	include "../dbcon.php";
	include "../baseurl.php";
	
	include "budget_export_scheduler_include.php";

//exit();

	$flname = '../zoho/zoho-budget'.'.csv';
	$fp 	= fopen($flname, 'w');
	
	$message ='';
	
	$message .= "Company, Doc Type.,Doc.No.,Against AP/PO SrNo.,Dated,budget Group,budget Sub Group,Budget Code for Tally,Board Approved Budget,Product ,Supplier/Employee,Actual Value (AP/PO), Blocked By PO.,Blocked By Approval,Consumed,Adjustment,Total Budget,Balance, Status \n";
		$grand_total_amount = '';
		$budget_head_prev	= '';
		$project_prev = '';
		
/* 	$sql = " SELECT a.doc_type, a.doc_no, a.items, a.budget_id, b.id as budget_id_bd, a.check_var, a.po_srno , a.doc_date, 
			a.party_name, a.invoice_no, a.approval_no, a.po_number, a.comp_code,
			a.blocked_budget, a.used_budget, a.adjustment_budget, b.total_budget ,
			a.location, a.department, a.status, a.party_id, a.doctype, a.budget_id_transfer
				FROM budget_view a, sma_budget b 
					WHERE  1 and b.id = a.budget_id order by b.id"; */
					
	$sql = "SELECT a.doc_type, a.doc_no, a.items, a.actual_value, a.budget_id as budget_id_bd, b.id as budget_id, a.check_var, a.po_srno , a.doc_date, 
			a.party_name, a.invoice_no, a.approval_no, a.po_number, a.comp_code,
			a.blocked_budget, a.used_budget, a.adjustment_budget, b.total_budget , b.board_approved_budget,
			a.location, a.department, a.status, a.party_id, a.doctype, a.budget_id_transfer, b.project as bd_company_id
				FROM sma_budget b LEFT JOIN  budget_view a 
					ON b.id = a.budget_id where (b.total_budget+b.adjustment_budget) > 0  
					and b.account_year = '$account_year'
					ORDER BY a.budget_id ASC ";				
					
/* SELECT column_name(s)
FROM table1
LEFT JOIN table2
ON table1.column_name = table2.column_name;
 */
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_type	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];
		$po_srno			= $row['po_srno'];
		$actual_value		= $row['actual_value'];
		$product_name 		= $row['items'];
		$status		 		= $row['status'];
		
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		$party_id 			= $row['party_id'];
		$doctype 			= $row['doctype'];
		$comp_code			= $row['comp_code'];
		$budget_id_transfer	= $row['budget_id_transfer'];
		$bd_company_id		= $row['bd_company_id'];
		
		if(empty($comp_code)){
			$sql = " SELECT * from company WHERE comp_id = '$bd_company_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$comp_code 		= $r2['comp_code'];
		}
		
		if($doc_type =='AP' || $doc_type =='PO' ){
			$po_srno = $doc_no;
		}
		
		$party_name = '';
		if($doc_type =='AP' || $doc_type =='PO' || $doc_type =='SI'  || $doc_type =='CE' || $doc_type =='OP' ){
			$sql = " SELECT * from sma_party_mst WHERE id = '$party_id' ";
				
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['party_name'];
		}
		else if($doc_type =='BT' ){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		=  $r2['name'];
			$product_name 		= str_replace(',', ' ',$budget_name) . ' ' . $budget_head;
			
//Transfer Budget			
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id_transfer' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= str_replace(',', ' ',$r2['budget_head']);
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= str_replace(',', ' ', $r2['name']);
			if($check_var=='From'){
				$product_name 		= $check_var.' ' .$product_name. '<br> To ' .$budget_name . ' ' . $budget_head;
			}
			else if($check_var=='To'){
				$product_name 		= $check_var.' ' .$product_name. '<br> From ' .$budget_name . ' ' . $budget_head;
			}
			
		}	
		else {
			$sql = " SELECT * from sma_user WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['username'];
		}
		
		$closing_balance	= 0;
		$total_budget		= 0;
		$adjustment_budget	= 0;
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 			= $r2['total_budget'];
			$board_approved_budget	= $r2['board_approved_budget'];
			$budget_name 			= $r2['budget_name'];
			$budget_head 			= $r2['budget_head'];
			$budget_code 			= $r2['budget_code'];
			$blocked_budget 		= $r2['blocked_budget'];
			$used_budget 			= $r2['used_budget'];
			$adjustment_budget 		= $r2['adjustment_budget'];
			//$total_budget			= $total_budget + $adjustment_budget;
			$running_balance_budget = $total_budget;
			$closing_balance		= $total_budget - ($used_budget + $blocked_budget);
//echo $sql. "<BR>";
//exit();			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= str_replace(',', ' ', $r2['name']);
			//$running_balance_budget = $total_budget + $adjustment_budget;
		
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_head 		= str_replace(',', ' ',$r2['budget_head']);
			$budget_code 		= str_replace(',', ' ',$r2['budget_code']);
	
			$doc_typea = 'Budget Balances';
			$tdate = '01-04-2024';
			$filler = '';
			$message .= "$comp_code,$doc_typea, $filler , $filler, $tdate ,$budget_name,$budget_head,$budget_code,$board_approved_budget,$filler,$filler,$filler,$filler,$filler,$filler,$adjustment_budget,$total_budget,$filler, $filler, \n";
			
			$adjustment_budget = '';
			$total_budget ='';
			$board_approved_budget='';
			$closing_balance = '';
			$doc_typea = '';
		}
		
		$budget_id_prev		= $budget_id;
		
		if( empty($doc_type)){
			continue;	
		}
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		
		if($dated=='01-01-1970'){
			$dated = '';
		}
		
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		//$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		
		$po_amount = '';
		//$blocked_budget	='';
		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
			if($doctype=='AP-ADJ'){
				if($blocked_budget==0){
					continue;	
				}	
				$doc_type = 'Approval Memo Reversal';
				$adjustment_budget	= $blocked_budget * -1;
				$blocked_budget ='';
			}	
			else if($status=='Suspend'){
					
				$blocked_budget	= $blocked_budget * -1;
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
				
			}
			else {
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
			}
			
			if($blocked_budget<0){
				$doc_type .= ' - '.$status;
			}
			
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
			$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
			$po_amount 	= $blocked_budget;
			$blocked_budget = '';
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget  - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='BD' ){
			
			$doc_type = 'Budget Adjustment';
			
			if($check_var=='I'){
				$running_balance_budget	= $running_balance_budget + $adjustment_budget ; 
			}
			else if($check_var=='D'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget ; 
				$adjustment_budget 		= $adjustment_budget * -1;
			}
			
		}
		else if( $doc_type=='BT' && $check_var == 'Less'){
			$doc_type = 'Budget Adjustment From';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		else if( $doc_type=='BT' && $check_var == 'Add'){
			$doc_type = 'Budget Adjustment To';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
		
		if($po_amount>0){
			$po_amount = ($po_amount);
		}
		else if ($po_amount<0){
			$po_amount = ($po_amount);
		}
		
		if($blocked_budget>0){
			$blocked_budget = ($blocked_budget);
		}
		else if ($blocked_budget<0){
			$blocked_budget = ($blocked_budget);
		}
	
		if($adjustment_budget==0){
			$adjustment_budget ='';
		}
		else {
			$adjustment_budget = ($adjustment_budget);
		}
		
		if($party_id> 0){
			$party_name = $party_name ;
		}
		
		if($running_balance_budget>0){
			$running_balance_budget_v = ($running_balance_budget);
		}
		else {
			$running_balance_budget_v = ($running_balance_budget);
			
		}
		
		if($used_budget>0){
			$used_budget_v = ($used_budget);
		}
		else {
			$used_budget_v = ($used_budget);
		}
		
		//Budget Balances
	
		$product_name = str_replace(',', '-',$product_name);
		$party_name   = str_replace(',', '-',$party_name);
		
		$message .= "$comp_code,$doc_type,$doc_no,$po_srno,$dated,$budget_name,$budget_head,$budget_code,$board_approved_budget,$product_name,$party_name,$actual_value, $po_amount,$blocked_budget,$used_budget_v,$adjustment_budget,$total_budget,$closing_balance, $status, \n";
	
	 }
	
//echo $message;
//exit();


		fwrite($fp, $message);
		fclose($fp);
		
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
 