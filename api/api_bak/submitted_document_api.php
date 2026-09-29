<?php

	include "DbConnect.php";
	
	//$token  = $_GET['token'];
	$userid = $_POST['userid'];
	
		$sql="SELECT * from sma_purchase_req where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$mrn_cnt = mysqli_affected_rows($con);
		if($mrn_cnt<0){
			$mrn_cnt=0;
		}		
		$mrn = array(
					 'number'=>$mrn_cnt, 
					 'slug'=>'mrn',
					 'userid'=>$userid
					 );	
					 
		/* $response['status'] = '1'; 
		$response['message'] = 'successfull'; 
		$response['token'] = $token; */ 
		$response['MRN'] = $mrn; 		
		
		
		$sql="SELECT * from sma_purchase_order where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt<0){
			$po_cnt=0;
		}		
		$po = array(
					 'number'=>$po_cnt, 
					 'slug'=>'po',
					 'userid'=>$userid
					 );	
		
		$response['PO'] = $po; 
		
		
		
		$sql="SELECT * from sma_approval_memo where 1 AND del !='Y' AND status ='Submitted'  
					AND approval_status !='Rejected' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$ap_cnt = mysqli_affected_rows($con);
		if($ap_cnt<0){
			$ap_cnt=0;
		}		
		$ap = array(
					 'number'=>$ap_cnt, 
					 'slug'=>'ap',
					 'userid'=>$userid
					 );	
		
		$response['AP'] = $ap; 
		
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status ='Submitted' and grn_approval_status !='Rejected' ";
		//and grn_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$grn_cnt = mysqli_affected_rows($con);
		if($grn_cnt<0){
			$grn_cnt=0;
		}
		$grn = array(
					 'number'=>$grn_cnt, 
					 'slug'=>'grn',
					 'userid'=>$userid
					 );	
		
		$response['GRN'] = $grn; 		
				
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected'  ";
				
		//		and draft_by = '$user'  and company_id in ( $comid )
		$result = mysqli_query($con,$sql);
		$si_cnt = mysqli_affected_rows($con);
		if($si_cnt<0){
			$si_cnt=0;
		}	
		$si = array(
					 'number'=>$si_cnt, 
					 'slug'=>'si',
					 'userid'=>$userid
					 );	
		
		$response['SI'] = $si; 	
		
		
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status ='Submitted'  and grn_approval_status !='Rejected' ";
				//and grn_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$gr_cnt = mysqli_affected_rows($con);
		if($gr_cnt<0){
			$gr_cnt=0;
		}	
		$grn = array(
					 'number'=>$gr_cnt, 
					 'slug'=>'grn',
					 'userid'=>$userid
					 );	
		
		$response['GRN'] = $grn; 		
				
		$sql="SELECT * from sma_goods_issue_note where 1 and del !='Y' and status ='Submitted'  and approval_status !='Rejected' ";
				//and current_approver = '$userid' and company_id in ( $comid ) ";	
		$result = mysqli_query($con,$sql);
		$gi_cnt = mysqli_affected_rows($con);
		if($gi_cnt<0){
			$gi_cnt=0;
		}	
		$gi = array(
					 'number'=>$gi_cnt, 
					 'slug'=>'gin',
					 'userid'=>$userid
					);	
		
		$response['GIN'] = $gi; 		
				
		
		$sql="SELECT * from sma_goods_receipt_note where 1 and del !='Y' and grn_status ='Submitted' and approval_status !='Rejected' ";
				//and current_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$gr_cnt = mysqli_affected_rows($con);
		if($gr_cnt<0){
			$gr_cnt=0;
		}	
		$grnwpo = array(
					 'number'=>$gr_cnt, 
					 'slug'=>'grnwpo',
					 'userid'=>$userid
					 );	
		
		$response['GRNWPO'] = $grnwpo; 
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='C' and status ='Submitted' ";
				//and approval_status !='Rejected'  and current_approver = '$userid' ";
		$result = mysqli_query($con,$sql);
		$ce_cnt = mysqli_affected_rows($con);
		if($ce_cnt<0){
			$ce_cnt=0;
		}	
		$ce = array(
					 'number'=>$ce_cnt, 
					 'slug'=>'ce',
					 'userid'=>$userid
					);	
		
		$response['CE'] = $ce; 
		
		$sql="SELECT * from payment_header where 1 and del !='Y' and status ='Submitted'  and approval_status !='Rejected' ";
//		and current_approver = '$userid' and company_id in ( $comid )";//
		$result = mysqli_query($con,$sql);
		$py_cnt = mysqli_affected_rows($con);
		if($py_cnt<0){
			$py_cnt=0;
		}	
		$py = array(
					 'number'=>$py_cnt, 
					 'slug'=>'py',
					 'userid'=>$userid
					);	
		
		$response['PY'] = $py; 		
				
		$sql="SELECT * from sma_traval_approval where 1 and del !='Y' and status ='Submitted' and approval_status !='Rejected' ";
		//and current_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$ta_cnt = mysqli_affected_rows($con);
		if($ta_cnt<0){
			$ta_cnt=0;
		}	
		$ta = array(
					 'number'=>$ta_cnt, 
					 'slug'=>'ta',
					 'userid'=>$userid
					);	
		
		$response['TA'] = $ta; 

		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='T' and status ='Submitted' and approval_status !='Rejected' ";
//		and current_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$te_cnt = mysqli_affected_rows($con);
		if($te_cnt<0){
			$te_cnt=0;
		}	
		$te = array(
					 'number'=>$te_cnt, 
					 'slug'=>'te',
					 'userid'=>$userid
					);	
		
		$response['TE'] = $te; 
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='R' and status ='Submitted' AND approval_status !='Rejected' ";
				//and current_approver = '$userid' and company_id in ( $comid ) ";
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
		if($re_cnt<0){
			$re_cnt=0;
		}	
		$re = array(
					 'number'=>$re_cnt, 
					 'slug'=>'re',
					 'userid'=>$userid
					);	
		
		$response['RE'] = $re; 
		
		echo json_encode($response);
		
?>				 
    