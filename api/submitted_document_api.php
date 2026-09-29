<?php

	include "DbConnect.php";
	
	//$token  = $_GET['token'];
	$userid = $_POST['userid'];
	
	$sql = " select * from sma_user where userid = '$userid' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$user_id 			= $r2['id'];
	$user				= $r2['username'];
	$comid				= $r2['company_id'];
/*  $file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file); */

	if($userid=='admin' || $userid=='admin123'){
		$sqla = '';
		$sqlb = '';
		$sqlc = '';
		$sqld = '';
	}
	else {
		$sqla = " AND current_approver = '$user_id' AND company_id in ( $comid ) ";
		$sqlb = " AND current_approver = '$user_id' AND project in ( $comid ) ";
		$sqlc = " AND current_approver = '$user_id' AND company in ( $comid ) ";
		$sqld = " AND grn_approver = '$user_id' and company_id in ( $comid ) ";
	}	
	
	$at_cnt = 0;
	
		$sql="SELECT * from sma_purchase_req where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected'  ". $sqla;
		$result = mysqli_query($con,$sql);
		$mrn_cnt = mysqli_affected_rows($con);
		if($mrn_cnt>0){
			$at_cnt = $mrn_cnt;
		}
		if($mrn_cnt<=0){
			$mrn_cnt=0;
		}
		else {	
			$mrn = array(
					 'number'=>$mrn_cnt, 
					 'slug'=>'Purchase Requisition Note',
					 'userid'=>$userid
					 );	
			$response['MRN'] = $mrn; 		
				 
		}
		
		
		$sql="SELECT * from sma_purchase_order where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected' ". $sqlb;
					//AND current_approver = '$user_id' AND project in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			$at_cnt = $at_cnt + $po_cnt;
		}
		if($po_cnt<=0){
			$po_cnt=0;
		}		
		else {
			$po = array(
					 'number'=>$po_cnt, 
					 'slug'=>'Purchase Order',
					 'userid'=>$userid
					 );	
			$response['PO'] = $po; 
				 
		}
		
		
		$sql="SELECT * from sma_approval_memo where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected' ". $sqlc;
					//AND current_approver = '$user_id' AND company in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$ap_cnt = mysqli_affected_rows($con);
		if($ap_cnt>0){
			$at_cnt = $at_cnt + $ap_cnt;
		}
		if($ap_cnt<=0){
			$ap_cnt=0;
		}
		else {
			$ap = array(
					 'number'=>$ap_cnt, 
					 'slug'=>'NOA',
					 'userid'=>$userid
					 );	
			$response['AP'] = $ap; 
		}
		
		
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status ='Submitted' 
				and grn_approval_status !='Rejected' ". $sqld;
				//AND grn_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$grn_cnt = mysqli_affected_rows($con);
		
		if($grn_cnt>0){
			$at_cnt = $at_cnt + $grn_cnt;
		}	
		if($grn_cnt<=0){
			$grn_cnt=0;
		}
		else {	
			$grn = array(
					 'number'=>$grn_cnt, 
					 'slug'=>'Goods Received Note',
					 'userid'=>$userid
					 );	
		
			$response['GRN'] = $grn; 		
		}
		
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Submitted' 
				and approval_status !='Rejected' ". $sqla;
				//AND draft_by = '$userid'  and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$si_cnt = mysqli_affected_rows($con);
		if($si_cnt>0){
			$at_cnt = $at_cnt + $si_cnt;
		}
		if($si_cnt<=0){
			$si_cnt=0;
		}
		else {			
			$si = array(
					 'number'=>$si_cnt, 
					 'slug'=>'Supplier Invoice',
					 'userid'=>$userid
					 );	
			$response['SI'] = $si; 	
				 
		}
		
				
		$sql="SELECT * from sma_goods_issue_note where 1 and del !='Y' and status ='Submitted'  
				and approval_status !='Rejected' ". $sqla;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";	
		$result = mysqli_query($con,$sql);
		$gi_cnt = mysqli_affected_rows($con);
		if($gi_cnt>0){
			$at_cnt = $at_cnt + $gi_cnt;
		}
		if($gi_cnt<=0){
			$gi_cnt=0;
		}
		else {	
			$gi = array(
					 'number'=>$gi_cnt, 
					 'slug'=>'gin',
					 'userid'=>$userid
					);	
		
			$response['GIN'] = $gi; 		
		}		
		
		$sql="SELECT * from sma_goods_receipt_note where 1 and del !='Y' and grn_status ='Submitted' 
					and approval_status !='Rejected' ". $sqla;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$gr_cnt = mysqli_affected_rows($con);
		if($gr_cnt>0){
			$at_cnt = $at_cnt + $gr_cnt;
		}
		if($gr_cnt<=0){
			$gr_cnt=0;
		}
		else {			
			$grnwpo = array(
					 'number'=>$gr_cnt, 
					 'slug'=>'grnwpo',
					 'userid'=>$userid
					 );	
			$response['GRNWPO'] = $grnwpo; 
		}
		
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='C' and status ='Submitted' 
					AND approval_status !='Rejected' ". $sqla;
//					and current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$ce_cnt = mysqli_affected_rows($con);
		if($ce_cnt>0){
			$at_cnt = $at_cnt + $ce_cnt;
		}
		if($ce_cnt<=0){
			$ce_cnt=0;
		}
		else {	
			$ce = array(
					 'number'=>$ce_cnt, 
					 'slug'=>'Operating Exp.',
					 'userid'=>$userid
					);	
		
			$response['CE'] = $ce; 
		}

	
		
		$sql="SELECT * from payment_header where 1 and del !='Y' and status ='Submitted'  
				and approval_status !='Rejected' ". $sqla;
				//AND current_approver = '$user_id' and company_id in ( $comid )";
				
		$result = mysqli_query($con,$sql);
		$py_cnt = mysqli_affected_rows($con);
/* $file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file);	
$file = fopen("ravitest.txt","a");
fwrite($file,$py_cnt);
fclose($file) */;	
		if($py_cnt>0){
			$at_cnt = $at_cnt + $py_cnt + 1;
		}
		
		if($py_cnt<=0){
			$py_cnt=0;
		}	
		else {	
			$py = array(
					 'number'=>$py_cnt, 
					 'slug'=>'Payments',
					 'userid'=>$userid
					);	
		
			$response['PY'] = $py; 		
		}


		
		$sql="SELECT * from sma_traval_approval where 1 and del !='Y' and status ='Submitted' 
				and approval_status !='Rejected' ". $sqla;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$ta_cnt = mysqli_affected_rows($con);
		if($at_cnt>0){
			$at_cnt = $at_cnt + $ta_cnt;
		}
		
		if($ta_cnt<=0){
			$ta_cnt=0;
		}	
		else {	
			$ta = array(
					 'number'=>$ta_cnt, 
					 'slug'=>'Travel Approval',
					 'userid'=>$userid
					);	
		
			$response['TA'] = $ta; 
		}
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='T' and status ='Submitted' 
						and approval_status !='Rejected' ". $sqla; 
			//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$te_cnt = mysqli_affected_rows($con);
		if($te_cnt>0){
			$at_cnt = $at_cnt + $te_cnt;
		}
		if($te_cnt<=0){
			$te_cnt=0;
		}
		else {			
			$te = array(
					 'number'=>$te_cnt, 
					 'slug'=>'Travel Exp.',
					 'userid'=>$userid
					);	
		
			$response['TE'] = $te;
		}
	
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='R' and status ='Submitted' 
				AND approval_status !='Rejected' ". $sqla;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
		if($re_cnt>0){
			$at_cnt = $at_cnt + $re_cnt;
		}	
		if($re_cnt<=0){
			$re_cnt=0;
		}
		else {			
			$re = array(
					 'number'=>$re_cnt, 
					 'slug'=>'Reimbursement',
					 'userid'=>$userid
					);	
		
			$response['RE'] = $re; 
		}

		$sql="SELECT * from budget_adjust where 1 and status ='Submitted' 
				AND approval_status !='Rejected' ". $sqlb;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
		if($re_cnt>0){
			$at_cnt = $at_cnt + $re_cnt;
		}	
		if($re_cnt<=0){
			$re_cnt=0;
		}
		else {			
			$re = array(
					 'number'=>$re_cnt, 
					 'slug'=>'Budget Adjustment',
					 'userid'=>$userid
					);	
		
			$response['BA'] = $re; 
		}
		
		$sql="SELECT * from budget_adjust_from_to where 1 and status ='Submitted' 
				AND approval_status !='Rejected' ". $sqlb;
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
		if($re_cnt>0){
			$at_cnt = $at_cnt + $re_cnt;
		}	
		if($re_cnt<=0){
			$re_cnt=0;
		}
		else {			
			$re = array(
					 'number'=>$re_cnt, 
					 'slug'=>'Budget Transfer',
					 'userid'=>$userid
					);	
		
			$response['BT'] = $re; 
		}
		
/* $file = fopen("ravitest.txt","a");
fwrite($file,$at_cnt);
fclose($file); */		
		if($at_cnt>0){
		
			echo json_encode($response);
		}
		else {
			$response['status'] = '0'; 
				$response['message'] = 'No Data Found !';
				echo json_encode($response);
		}
		
?>				 
    