<?php

	include "DbConnect.php";
	
	$order_status   = $_POST['order_status'];
	$module 		= $_POST['slug'];
	$userid 		= $_POST['userid'];

	$sql = " select * from sma_user where userid = '$userid' ";
//echo $sql. ' ' . $module;
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$user_id 			= $r2['id'];
	$user				= $r2['username'];
	$comid				= $r2['company_id'];

	if($userid=='admin'){
		$sqla = '';
		$sqlb = '';
		$sqlc = '';
		$sqld = '';
	}
	else {
		$sqlaa = " AND current_approver = '$user_id' AND company_id in ( $comid ) ";
		$sqlb = " AND current_approver = '$user_id' AND project in ( $comid ) ";
		$sqlc = " AND current_approver = '$user_id' AND company in ( $comid ) ";
		$sqld = " AND grn_approver = '$user_id' and company_id in ( $comid ) ";
	}	
	
/*  $file = fopen("ravitest.txt","w");
fwrite($file,$sqlb);
fclose($file);

$file = fopen("ravitest.txt","a");
fwrite($file,$module);
fclose($file); */
	
	//$order_status   = 'pending';
	//$module 		= '';
	
	$data = array();
	$po_cnt = 0;
	$sqla = "";
	if($order_status=='pending'){
		$sqla = " AND status ='Submitted' AND approval_status !='Rejected' ";
	}
	else if($order_status=='rejected'){
		$sqla = " AND approval_status ='Rejected' ";
	}
	else if($order_status=='accepted'){
		$sqla = " AND status ='Completed' ";
	}
	
	if($module=='Purchase Order'){
		$sql="SELECT * from sma_purchase_order where 1 AND del !='Y' $sqla $sqlb ";
				//AND current_approver = '$user_id' AND project  in ( $comid ) "; 
//echo $sql. ' ' . $module;
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$project = $row['project'];
				$sql 	= "select * from company where comp_id = '$project' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];		
				
				$to_supplier = $row['to_supplier'];
				$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$to_supplier = $r2['party_name'];
			
				$purchase_id = $row['id'];
				$tot_amount = 0;
				$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r1 = mysqli_fetch_array($res1)){
					$qty 	= $r1['quantity'];
					$rate 	= $r1['unit_rate'];
					$gst	= $r1['gst'];
					$amount = $qty * $rate + ((($qty * $rate) * $gst) / 100);
					$tot_amount = $tot_amount + $amount;
				}										
			
				$del   = $row['del'];
				
				$po_rev = $row['po_rev'];
				$po_number = $row['po_number'];
				if($po_rev>0){
					$po_number .= '-'.$po_rev;
				}
				
				$po_amend = $row['po_amend'];
				
				$draft_by = $row['draft_by'];			
				
				$sql="SELECT * from sma_user where userid = '$draft_by' ";
	//echo $sql."<BR>";			
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res1);
				$draft_by 	= $r1['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Approved'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Approved'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Approved'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Approved'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Approved'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Approved'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Approved'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Approved'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$approved_by  = $r2['username'];
				
				
				$po_rev = $row['po_rev'];
				$po_number = $row['po_number'];
				if($po_rev>0){
					$po_number .= $po_rev;
				}
				else {
					$po_number = $row['po_number'];
				}
				
				$approval_status = $row['approval_status'];
				
				$po_id 		= $row['id'];
				$po_dated 	= date('d-m-Y', strtotime($row['dated']));
				$subject	= $row['subject'];
				$status		= $row['status'];
				
				$properties = array();
				$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
				$properties[] = array('label'=>'Company/PO No.',
										'value'=>$po_number);
				$properties[] = array('label'=>'Supplier Name', 
										'value'=>$to_supplier);
				$properties[] = array('label'=>'PO Value', 
										'value'=>$tot_amount);
				$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
								
			
				$data[] = array(
							'slug'=>$module, 
							'status'=>$status,
							'dated'=>$po_dated,
							'userid'=>$userid,
							'id_parameter'=>$po_id,
							'properties'=>$properties
						);
				
			}	
		
		}

//print_r($data);		
//echo $po_cnt. ">><<";
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			
			echo json_encode($response);
	
			//exit('Exit HERE');

		}
		
	}
	else if($module=='Purchase Requisition Note'){
		$sql="SELECT * from sma_purchase_req where 1 AND del !='Y' $sqla $sqlaa ";
					//AND current_approver = '$user_id' AND company_id in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$mrn_cnt = mysqli_affected_rows($con);
		if($mrn_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$pr_id = $row['id'];
				$project = $row['company_id'];
				$sql 	= "select * from company where comp_id = '$project' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];		
				
				$to_supplier = $row['to_supplier'];
				$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$to_supplier = $r2['party_name'];
			
				$department_id 	= $row['department_id'];
					$sql 	= "select * from sma_department where id = '$department_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$department_name = $r2['name'];
					
				$pr_number = $row['pr_number'];	
				
				$purchase_id = $row['id'];
				
				$draft_by = $row['draft_by'];			
				
				//$changed_by = $row['draft_by'];
			
				$sql="SELECT * from sma_user where userid = '$draft_by' ";
	//echo $sql."<BR>";			
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res1);
				$draft_by		 	= $r1['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Approved'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Approved'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Approved'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Approved'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Approved'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Approved'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Approved'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Approved'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$approved_by  = $r2['username'];
				
				$approval_status = $row['approval_status'];
				
			//	$po_id 		= $row['id'];
				$po_dated 	= date('d-m-Y', strtotime($row['date']));
				$status		= $row['status'];
				$subject	= $row['subject'];
				
				$sql = " SELECT * FROM `sma_purchase_req_items` where purchase_req_id = '$pr_id' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$quantity_value = $r2['quantity'];
				
				$properties = array();
				$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
				$properties[] = array('label'=>'Company/MRN No.',
										'value'=>$comp_code.'/'.$pr_number);
				$properties[] = array('label'=>'Quantity/Value', 
										'value'=>$quantity_value);
				$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				if(!empty($approved_by)){
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
				}
				
				$data[] = array(
							'slug'=>$module, 
							'status'=>$status,
							'dated'=>$po_dated,
							'userid'=>$userid,
							'id_parameter'=>$pr_id,
							'properties'=>$properties
						);
						
			}	
		
		}
		
		$po_cnt = $mrn_cnt;
		if($mrn_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='NOA'){
		$sql="SELECT * from sma_approval_memo where 1 AND del !='Y' $sqla $sqlc ";
					//AND current_approver = '$user_id' AND company in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$ap_cnt = mysqli_affected_rows($con);
		if($ap_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$project = $row['company'];
				$sql 	= "select * from company where comp_id = '$project' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];		
				
				$to_supplier = $row['to_supplier'];
				$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$to_supplier = $r2['party_name'];
			
				$department_id 	= $row['department_id'];
					$sql 	= "select * from sma_department where id = '$department_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$department_name = $r2['name'];
					
				$party_name ='';
				$ap_id = $row['id'];
				$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name " ;
			
				$amount		 =0;
				$q2  = mysqli_query($con, $sql);
				$raffect = mysqli_affected_rows($con);
				while($r2 = mysqli_fetch_array($q2)){
							
					$party_name  .= $r2['party_name'];
					$amount		 += $r2['values'];
						
				}
				
				$purchase_id = $row['id'];
				
				$draft_by = $row['draft_by'];			
				
				$sql="SELECT * from sma_user where userid = '$draft_by' ";
	//echo $sql."<BR>";			
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res1);
				$draft_by 			= $r1['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Approved'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Approved'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Approved'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Approved'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Approved'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Approved'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Approved'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Approved'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$approved_by  = $r2['username'];
				
				$approval_status = $row['approval_status'];
				
				$po_id 		= $row['id'];
				$po_dated 	= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				$subject	= $row['subject'];
				
				$properties = array();
				$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
				$properties[] = array('label'=>'Company/Sr.No.',
										'value'=>$comp_code.'/'.$po_id);
				$properties[] = array('label'=>'Supplier Name', 
										'value'=>$party_name);
				$properties[] = array('label'=>'Amount', 
										'value'=>$amount);
				
				$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);						
				$data[] = array(
							'slug'=>$module, 
							'status'=>$status,
							'dated'=>$po_dated,
							'userid'=>$userid,
							'id_parameter'=>$po_id,
							'properties'=>$properties
						);
			/* 			
				$data[] = array(
						 'slug'=>$module, 
						 'trans_id'=>$po_id, 
						 'text_number'=>$po_id,
						 'dated'=>$po_dated,
						 'supplier_name'=>$party_name,
						 'amount'=>$amount,
						 'draft_by'=>$changed_by,
						 'pending_by'=>$pending_by,
						 'status'=>$status
						 ); */	
			
			}	
		
		}
		
		$po_cnt =  $ap_cnt;
		if($ap_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Goods Received Note'){
		
		if($order_status=='pending'){
			$sqla = " AND grn_status ='Submitted' ";
		}
		else if($order_status=='rejected'){
			$sqla = " AND grn_approval_status ='Rejected' ";
		}
		else if($order_status=='accepted'){
			$sqla = " AND grn_status ='Completed' ";
		}
		
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y'  $sqla $sqld ";
		//		AND grn_approver = '$user_id' and company_id in ( $comid ) ";
			
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$grn_cnt = mysqli_affected_rows($con);
		if($grn_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$project = $row['company'];
				$sql 	= "select * from company where comp_id = '$project' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];		
				
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$party_name = $r2['party_name'];
				
				$our_po_ref_no = $row['our_po_ref_no'];
				$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$our_po_ref_no 	= $r1['po_number'];
				$subject	 	= $r1['subject'];
				$po_rev			= $r1['po_rev'];
				if($po_rev>0){
					$our_po_ref_no 	= $our_po_ref_no .'-'.	$po_rev;
				}
				
				$purchase_id = $row['id'];
				
				$draft_by = $row['grndraft_by'];			
				
				$sql="SELECT * from sma_user where userid = '$draft_by' ";
	//echo $sql."<BR>";			
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res1);
				$draft_by 	= $r1['username'];
				
				$pending_by			= $row['grn_approver'];
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$approved_by  = $r2['username'];
				
				$approval_status = $row['approval_status'];
				
				$si_id 					= $row['id'];
				$invoice_date 			= date('d-m-Y', strtotime($row['invoice_date']));
				$supplier_invoice_no	= $row['supplier_invoice_no'];
				$total_amount			= $row['total_amount'];
				$status					= $row['grn_status'];
				
				$sql 	= "select sum(qty) as qty from sma_supplier_invoice_details where si_hdr_id = '$si_id' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$tot_qty = $r2['qty'];
				
				$properties = array();
				$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
				$properties[] = array('label'=>'Company/PO Number',
										'value'=>$comp_code.'/'.$our_po_ref_no);
				$properties[] = array('label'=>'Supplier Name', 
										'value'=>$party_name);
				$properties[] = array('label'=>'Amount/Quantity', 
										'value'=>$tot_qty);
				$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
				$data[] = array(
							'slug'=>$module, 
							'status'=>$status,
							'dated'=>$invoice_date,
							'userid'=>$userid,
							'id_parameter'=>$si_id,
							'properties'=>$properties
						);
						/* 
				$data[] = array(
						 'slug'=>$module, 
						 'trans_id'=>$si_id, 
						 'text_number'=>$supplier_invoice_no,
						 'dated'=>$invoice_date,
						 'supplier_name'=>$supplier_name,
						 'amount'=>$tot_qty,
						 'draft_by'=>$changed_by,
						 'pending_by'=>$pending_by,
						 'status'=>$status
						 );	 */
			 
			}	
		
		}
		
		$po_cnt = $grn_cnt;
		if($grn_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Supplier Invoice'){
		
		//$sqla = " AND status ='Submitted' AND approval_status !='Rejected' ";
		$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' $sqla $sqlaa ";
				//AND current_approver = '$user_id' and company_id in ( $comid )";
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$si_cnt = mysqli_affected_rows($con);
		if($si_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$project = $row['company'];
				$sql 	= "select * from company where comp_id = '$project' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];		
				
				$supplier = $row['suplier_name'];
				$sql 	= "select * from sma_party_mst where id = '$supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$party_name = $r2['party_name'];
				
				$our_po_ref_no = $row['our_po_ref_no'];
				$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$our_po_ref_no 		= $r1['po_number'];
				$po_rev				= $r1['po_rev'];
				$subject			= $r1['subject'];
				if($po_rev>0){
					$our_po_ref_no 	= $our_po_ref_no .'-'.	$po_rev;
				}
				
				$purchase_id = $row['id'];
				
				$draft_by = $row['draft_by'];			
				
				$sql="SELECT * from sma_user where userid = '$draft_by' ";
	//echo $sql."<BR>";			
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res1);
				$draft_by 	= $r1['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Approved'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Approved'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Approved'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Approved'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Approved'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Approved'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Approved'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Approved'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$approved_by  = $r2['username'];
				
				$approval_status = $row['approval_status'];
				
				$si_id 					= $row['id'];
				$invoice_date 			= date('d-m-Y', strtotime($row['invoice_date']));
				$supplier_invoice_no	= $row['supplier_invoice_no'];
				$total_amount			= $row['total_amount'];
				$status					= $row['status'];
/* $datav .= 	$si_id. ' ' .	$invoice_date. ' ' . $supplier_invoice_no. ' '. $total_amount. ' '.'/n/n';		
$file = fopen("ravitest.txt","w");
fwrite($file,$datav);
fclose($file); */				
				$properties = array();
				$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
				$properties[] = array('label'=>'Company/PO Number',
										'value'=>$comp_code.'/'.$our_po_ref_no);
				$properties[] = array('label'=>'Supplier Name', 
										'value'=>$party_name);
				$properties[] = array('label'=>'Amount', 
										'value'=>$total_amount);
				$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
				$data[] = array(
							'slug'=>$module, 
							'status'=>$status,
							'dated'=>$invoice_date,
							'userid'=>$userid,
							'id_parameter'=>$si_id,
							'properties'=>$properties
						);
				/* 		
				$data[] = array(
						 'slug'=>$module, 
						 'trans_id'=>$si_id, 
						 'text_number'=>$supplier_invoice_no,
						 'dated'=>$invoice_date,
						 'supplier_name'=>$supplier_name,
						 'amount'=>$total_amount,
						 'draft_by'=>$changed_by,
						 'pending_by'=>$pending_by,
						 'status'=>$status
						 );	
			  */
			}	
		
		}
		
		$po_cnt  = $si_cnt;
		if($si_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Payments'){
		
		//$data[] = array();
		$sql="SELECT * from payment_header where 1 and del !='Y' $sqla $sqlaa ";
			//AND current_approver = '$user_id' and company_id in ( $comid ) ";
				 
/* $file = fopen("ravitest.txt","a");
fwrite($file,$py_cnt);
fclose($file); */
				 
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$py_cnt = mysqli_affected_rows($con);
			if($py_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$project = $row['company_id'];
					$sql 	= "select * from company where comp_id = '$project' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$comp_code = $r2['comp_code'];		
					
					$tally_status  = $row['tally_status'];
					if(empty($tally_status)){
						$tally_status_a = 'JV Pending';
					}
					else if($tally_status=='R'){
						$tally_status_a = 'JV Created';
					}	
					else if($tally_status=='C'){
						$tally_status_a = 'JV Checked ';
					}
					else if($tally_status=='U'){
						$tally_status_a = 'JV Synched';	
					}
					
					
					$cash_bank_name = $row['cash_bank_name'];
					$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$cash_bank_name = $r2['account_name'];
					
					$paid_to = $row['paid_to'];
					$st_flag = $row['st_flag'];
					if($st_flag =='A' || $st_flag =='T'){
						$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['username'];
					}
					else {
						$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
					}
					
					$dated = date('d-m-Y', strtotime($row['dated']));
					if($dated =='01-01-1970'){
						$dated = '';
					}
					
					$py_id = $row['id'];
					$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$supplier_invoice_no  = $r2['supplier_invoice_no'];
					$supp_id			  = $r2['supp_id'];
					
					$total_amount_paid = $row['total_amount_paid'];
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$draft_by = $row['draft_by'];
					$sql = "select * from sma_user where userid = '$draft_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$draft_by  = $r2['username'];
					
					$status 	= $row['approval_status'];
					$st_flag 	= $row['st_flag'];
					if($st_flag=='S'){
						$subject = 'Supplier Invoice';
					}
					else if($st_flag=='C'){
						$subject = 'Company Expanse';
					}
					else if($st_flag=='T'){
						$subject = 'Travel Expense';
					}
					else if($st_flag=='D'){
						$subject = 'Supplier Advance';
					}
					
					
					$properties = array();
					$properties[] = array('label'=>'Subject', 
										'value'=>$subject);
					$properties[] = array('label'=>'Company/SrNo.',
											'value'=>$comp_code.'/'.$py_id);
					$properties[] = array('label'=>'Paid To', 
											'value'=>$party_name);
					$properties[] = array('label'=>'Amount', 
											'value'=>$total_amount_paid);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
				$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$py_id,
								'properties'=>$properties
							);
					
			}	
		
		}
		
		$po_cnt = $py_cnt;
		if($py_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Operating Exp.'){
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='C' $sqla $sqlaa ";
			//and current_approver = '$user_id' and company_id in ( $comid ) ";
				 
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$ce_cnt = mysqli_affected_rows($con);
			if($ce_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$tally_status  = $row['tally_status'];
					if(empty($tally_status)){
						$tally_status_a = 'JV Pending';
					}
					else if($tally_status=='R'){
						$tally_status_a = 'JV Created';
					}	
					else if($tally_status=='C'){
						$tally_status_a = 'JV Checked ';
					}
					else if($tally_status=='U'){
						$tally_status_a = 'JV Synched';	
					}
					
					$company_id  = $row['company_id'];
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					$emp_id = $row['emp_id'];
					$sql="SELECT * from sma_party_mst where id = '$emp_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$party_name		= $r1['party_name'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$exp_amount = 0;
					$fare		 = 0;
					$approval_ref_no = $row['approval_ref_no'];	
					$ce_id = $row['id'];	
					$sql="SELECT * from sma_departure where approval_ref_no = '$ce_id' ";
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$fare += $d1['fare'];
					}			
								
					$sql="SELECT * from sma_expenses where exp_type = 'C' and approval_ref_no = '$ce_id' ";
					
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$exp_amount += $d1['amount'];
						$supplier_invoice_no = $d1['invoice_no'];
					}
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$draft_by = $row['draft_by'];
					$sql = "select * from sma_user where userid = '$draft_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$draft_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/Invoice No.',
											'value'=>$comp_code.'/'.$supplier_invoice_no);
					$properties[] = array('label'=>'Supplier Name', 
											'value'=>$party_name);
					$properties[] = array('label'=>'Amount', 
											'value'=>$exp_amount);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$ce_id,
								'properties'=>$properties
							);
					
			}	
		
		}
		
		$po_cnt = $ce_cnt;
		if($ce_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Travel Exp.'){
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='T' $sqla $sqlaa ";
			//AND current_approver = '$userid' and company_id in ( $comid ) ";
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$te_cnt = mysqli_affected_rows($con);
			if($te_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$tally_status  = $row['tally_status'];
					if(empty($tally_status)){
						$tally_status_a = 'JV Pending';
					}
					else if($tally_status=='R'){
						$tally_status_a = 'JV Created';
					}	
					else if($tally_status=='C'){
						$tally_status_a = 'JV Checked ';
					}
					else if($tally_status=='U'){
						$tally_status_a = 'JV Synched';	
					}
					
					$company_id  = $row['company_id'];
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					$emp_id = $row['emp_id'];
					$sql="SELECT * from sma_user where id = '$emp_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$party_name		= $r1['username'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$exp_amount = 0;
					$fare		 = 0;
					$approval_ref_no = $row['approval_ref_no'];	
					
					$te_id = $row['id'];	
					$sql="SELECT * from sma_departure where approval_ref_no = '$te_id' ";
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$fare += $d1['fare'];
					}			
								
					$sql="SELECT * from sma_expenses where exp_type = 'T' and approval_ref_no = '$te_id' ";
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$exp_amount += $d1['amount'];
						$supplier_invoice_no = $d1['invoice_no'];
					}
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$draft_by = $row['draft_by'];
					$sql = "select * from sma_user where userid = '$draft_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$draft_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/Invoice No.',
											'value'=>$comp_code.'/'.$supplier_invoice_no);
					$properties[] = array('label'=>'User Name', 
											'value'=>$party_name);
					$properties[] = array('label'=>'Amount', 
											'value'=>$exp_amount);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$te_id,
								'properties'=>$properties
							);	
			 
			}	
		
		}
		
		$po_cnt = $te_cnt;
		if($te_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Reimbursement'){
		
		$sql="SELECT * from sma_travel_expenses where 1 and del !='Y' and exp_type ='R' $sqla $sqlaa ";
			//AND current_approver = '$user_id' and company_id in ( $comid ) ";
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
			if($re_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$tally_status  = $row['tally_status'];
					if(empty($tally_status)){
						$tally_status_a = 'JV Pending';
					}
					else if($tally_status=='R'){
						$tally_status_a = 'JV Created';
					}	
					else if($tally_status=='C'){
						$tally_status_a = 'JV Checked ';
					}
					else if($tally_status=='U'){
						$tally_status_a = 'JV Synched';	
					}
					
					$company_id  = $row['company_id'];
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					$emp_id = $row['emp_id'];
					$sql="SELECT * from sma_user where id = '$emp_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$party_name		= $r1['username'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$exp_amount = 0;
					$fare		 = 0;
					$approval_ref_no = $row['approval_ref_no'];	
					
					$re_id = $row['id'];	
					$sql="SELECT * from sma_departure where approval_ref_no = '$re_id' ";
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$fare += $d1['fare'];
					}			
								
					$sql="SELECT * from sma_expenses where exp_type = 'R' and approval_ref_no = '$re_id' ";
					
					$res = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$j = 0;
					while($d1 = mysqli_fetch_array($res)){
						$exp_amount += $d1['amount'];
						$supplier_invoice_no = $d1['invoice_no'];
					}
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$draft_by = $row['draft_by'];
					$sql = "select * from sma_user where userid = '$draft_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$draft_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/Invoice No.',
											'value'=>$comp_code.'/'.$supplier_invoice_no);
					$properties[] = array('label'=>'User Name', 
											'value'=>$party_name);
					$properties[] = array('label'=>'Amount', 
											'value'=>$exp_amount);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$re_id,
								'properties'=>$properties
							);
			 
			}	
		
		}
		
		$po_cnt = $re_cnt;
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Travel Approval'){
		
		$sql="SELECT * from sma_traval_approval where 1 and del !='Y' $sqla $sqlaa ";
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
			if($re_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$company_id  = $row['company_id'];
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					$emp_id = $row['emp_id'];
					$emp_id = $row['onbehalf_emp_id'];
					$sql="SELECT * from sma_user where id = '$emp_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$party_name		= $r1['username'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$re_id 			= $row['id'];	
					
					$traval_from 	= $row['traval_from'];
					$traval_to 		= $row['traval_to'];
					
					$start_date = date('d-m-Y', strtotime($row['start_date']));
					$end_date 	= date('d-m-Y', strtotime($row['end_date']));
					
					if($start_date=='01-01-1970' || $start_date=='31-12-1969' ){ $start_date='';}
					if($end_date=='01-01-1970' || $end_date=='31-12-1969' ){ $end_date='';}
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$draft_by = $row['draft_by'];
					$sql = "select * from sma_user where userid = '$draft_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$draft_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/ User Name',
											'value'=>$comp_code.'/'.$party_name. ' ' . $re_id);
					$properties[] = array('label'=>'From Location/ To Location ', 
											'value'=>$traval_from. ' To '. $traval_to);
					$properties[] = array('label'=>'Start Date/End Date', 
											'value'=>$start_date . ' To '. $end_date);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$re_id,
								'properties'=>$properties
							);
			 
			}	
		
		}
		
		$po_cnt = $re_cnt;
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Budget Adjustment'){
		
		$sql="SELECT * from budget_adjust where 1  $sqla $sqlb ";
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";

/* $file = fopen("ravitest.txt","a");
fwrite($file,$module);
fclose($file); */
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
			if($re_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$budget_name  = $row['budget_name'];
					$budget_head  = $row['budget_head'];
					$amount		  = $row['amount'];
					
					$company_id  = $row['project'];
					
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					
					$sql  = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_head 		= $r1['budget_head'];
					
					$sql  = "SELECT * from sma_budget_name where id = '$budget_name' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_name 		= $r1['name'];
					
					$draft_by = $row['draft_by'];			
									
					$sql="SELECT * from sma_user where userid = '$draft_by' ";
						//echo $sql."<BR>";			
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$draft_by 	= $r1['username'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$re_id 				= $row['id'];
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Submitted'){
						$pending_by  = $approver_1;	
					}
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/ User Name',
											'value'=>$comp_code.'/'.$party_name. ' ' . $re_id);
					$properties[] = array('label'=>'Budget Name', 
											'value'=>$budget_name);
					$properties[] = array('label'=>'Budget Head', 
											'value'=>$budget_head);
					$properties[] = array('label'=>'budget Value', 
											'value'=>$amount);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$re_id,
								'properties'=>$properties
							);
			 
			}	
		
		}
		
		$po_cnt = $re_cnt;
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	else if($module=='Budget Transfer'){
		
		$sql="SELECT * from budget_adjust_from_to where 1  $sqla $sqlb ";
				//AND current_approver = '$user_id' and company_id in ( $comid ) ";

 /* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);  */
				
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$re_cnt = mysqli_affected_rows($con);
			if($re_cnt>0){
				echo mysqli_error($con); 
				while($row = mysqli_fetch_array($result)){
					
					$budget_name  = $row['budget_name_from'];
					$budget_head  = $row['budget_head_from'];
					
					$budget_name_to  = $row['budget_name_to'];
					$budget_head_to  = $row['budget_head_to'];
					
					$amount		  = $row['amount'];
					
					$company_id  = $row['project'];
					
					$sql  = "SELECT * from company where comp_id = '$company_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$comp_code 		= $r1['comp_code'];

					
					$sql  = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_head 		= $r1['budget_head'];
					
					$sql  = "SELECT * from sma_budget_name where id = '$budget_name' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_name 		= $r1['name'];
					
					$sql  = "SELECT * from sma_budget_subgroup where id = '$budget_head_to' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_head_to 		= $r1['budget_head'];
					
					$sql  = "SELECT * from sma_budget_name where id = '$budget_name_to' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$budget_name_to 		= $r1['name'];
					
					$draft_by = $row['draft_by'];			
									
					$sql="SELECT * from sma_user where userid = '$draft_by' ";
						//echo $sql."<BR>";			
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res1);
					$draft_by 	= $r1['username'];
					
					$dated 		=  date('d-m-Y', strtotime($row['dated']));
					if( $dated=='01-01-1970' ){ $dated='';}
					
					$re_id 				= $row['id'];
					
					$approver_1			= $row['approver_1'];
					$approver_2			= $row['approver_2'];
					$approver_3			= $row['approver_3'];
					$approver_4			= $row['approver_4'];
					$approver_5			= $row['approver_5'];
					$approver_6			= $row['approver_6'];
					$approver_7			= $row['approver_7'];
					$approver_8			= $row['approver_8'];
					
					$approver_1_status	= $row['approver_1_status'];
					$approver_2_status	= $row['approver_2_status'];
					$approver_3_status	= $row['approver_3_status'];
					$approver_4_status	= $row['approver_4_status'];
					$approver_5_status	= $row['approver_5_status'];
					$approver_6_status	= $row['approver_6_status'];
					$approver_7_status	= $row['approver_7_status'];
					$approver_8_status	= $row['approver_8_status'];
					
					if($approver_1_status == 'Submitted'){
						$pending_by  = $approver_1;	
					}
					
					if($approver_1_status == 'Approved'){
						$pending_by  = $approver_1;	
					}
					if($approver_2_status == 'Approved'){
						$pending_by  = $approver_2;	
					}
					if($approver_3_status == 'Approved'){
						$pending_by  = $approver_3;	
					}
					if($approver_4_status == 'Approved'){
						$pending_by  = $approver_4;	
					}
					if($approver_5_status == 'Approved'){
						$pending_by  = $approver_5;	
					}
					if($approver_6_status == 'Approved'){
						$pending_by  = $approver_6;	
					}
					if($approver_7_status == 'Approved'){
						$pending_by  = $approver_7;	
					}
					if($approver_8_status == 'Approved'){
						$pending_by  = $approver_8;	
					}
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$approved_by  = $r2['username'];
					
					$status 	= $row['status'];
					
					$properties = array();
					
					$properties[] = array('label'=>'Company/ User Name',
											'value'=>$comp_code.'/'.$party_name. ' ' . $re_id);
					$properties[] = array('label'=>'Budget Name From', 
											'value'=>$budget_name);
					$properties[] = array('label'=>'Budget Head From', 
											'value'=>$budget_head);
					$properties[] = array('label'=>'Budget Name To', 
											'value'=>$budget_name_to);
					$properties[] = array('label'=>'Budget Head To', 
											'value'=>$budget_head_to);						
					$properties[] = array('label'=>'budget Value', 
											'value'=>$amount);
					$properties[] = array('label'=>'Created By ', 
										'value'=>$draft_by);
					$properties[] = array('label'=>'Approver By', 
										'value'=>$approved_by);
											
					$data[] = array(
								'slug'=>$module, 
								'status'=>$status,
								'dated'=>$dated,
								'userid'=>$userid,
								'id_parameter'=>$re_id,
								'properties'=>$properties
							);
			 
			}	
		
		}
		
		$po_cnt = $re_cnt;
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			echo json_encode($response);
		}
		
	}
	
//echo ">><<". $po_cnt. ">><<";	
	if($po_cnt<=0){
		$response['status'] = '0'; 
		$response['message'] = 'No records found...'; 
		echo json_encode($response);
	}
	
	
?>				 
    