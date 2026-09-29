<?php

	include "../baseurl.php";
	include "DbConnect.php";
	
	$id_parameter   = $_POST['id_parameter'];
	$module 		= trim($_POST['slug']);
	$userid 		= $_POST['userid'];
	$userid_v 		= $_POST['userid'];
	
	if($module=='po'){
		
		$modulepath = 'purchase_order/';	
		
		$sql="SELECT * from sma_comment where doc_id = '$id_parameter' and doc_type = 'PO' order by id desc ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			while($row = mysqli_fetch_array($result)){
				
				$doc_id		= $row['doc_id'];
				$comment_datetime		= $row['comment_datetime'];
				$comment_type			= $row['comment_type'];
				$comment				= $row['comment'];
				$parent_comment_id		= $row['parent_comment_id'];
				$comment_by				= $row['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($row['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$comment_by = $rw['username'];
				
				
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$doc_id, 
						 'comment_datetime'=>$comment_datetime,
						 'comment'=>$comment,
						 'comment_by'=>$comment_by
						 );	
			}
			
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='mrn'){
		
		$modulepath = 'purchase_requisition/';	
		
		$sql="SELECT * from sma_purchase_req where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
				$company_id = $row['company_id'];
				$sql 	= "select * from sma_project where id = '$company_id' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$project = $r2['name'];		
				
				$location				= $row['location'];
				$approval_memo_ref		= $row['approval_memo_ref'];
				
				$to_supplier = $row['to_supplier'];
				$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$to_supplier = $r2['party_name'];
			
				$po_id 		= $row['id'];
				$po_dated 	= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				
				/* $po_pdf_path = $baseurl.$modulepath.'pur_order_prn.php?sub=pdf&id='.$po_id.'&comp_id='.$project.'&location='.$location.'&r=1&print_flag=V';
			
				$button[] = array(
						 'label'=>'View PO', 
						 'pdf_link'=>$po_pdf_path
						 );	
				 */
				$prn_path = $baseurl.'purchase_requisition/purchase_req_prn.php?sub=pdf&id='.$approval_memo_ref.'&comp_id='.$project.'&r=1';
				
				$button[] = array(
						 'label'=>'View MRN', 
						 'pdf_link'=>$prn_path
						 );	
						 
				$sql = "SELECT * FROM file_uploads WHERE module = 'PO' AND reference_id = " . $po_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$po_id, 
						 'dated'=>$po_dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='grn'){
		
		$modulepath = 'supp_invoice/';	
		
		$sql="SELECT * from sma_supplier_invoice where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
				
				//https://athaang.in/p2p2023/supp_invoice/supplier_invoice_prn.php?sub=pdf&id=1625
				$po_pdf_path = $baseurl.'supp_invoice/supplier_invoice_prn.php?sub=pdf&id='.$si_id;
			
				$button[] = array(
						 'label'=>'View GRN', 
						 'pdf_link'=>$po_pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'GR' AND reference_id = " . $si_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module,
						 'userid'=>$userid, 
						 'trans_id'=>$si_id, 
						 'dated'=>$invoice_date,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='si'){
		
		$modulepath = 'supp_invoice/';	
		
		$sql="SELECT * from sma_supplier_invoice where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
				
				//https://athaang.in/p2p2023/supp_invoice/purchase_voucher.php?sub=pdf&id=1622&company_id=5&vendor_id=87
				$pdf_path = $baseurl.'supp_invoice/purchase_voucher.php?sub=pdf&id=1622&company_id=5&vendor_id='.$si_id;
			
				$button[] = array(
						 'label'=>'View Voucher', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'SI' AND reference_id = " . $si_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$si_id, 
						 'dated'=>$invoice_date,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='ap'){
		
		$modulepath = 'approval/';	
		
		$sql="SELECT * from sma_approval_memo where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
					
				
				$ap_id 		= $row['id'];
				$dated 	= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				
				//https://athaang.in/p2p2023/approval/approval_notes_prn.php?sub=pdf&id=1047&comp_id=6&r=1
				$pdf_path = $baseurl."approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=6&r=1&vw=Y";
			
				$button[] = array(
						 'label'=>'View AP Memo', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'AP' AND reference_id = " . $ap_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module,
						 'userid'=>$userid, 						 
						 'trans_id'=>$ap_id, 
						 'dated'=>$dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='ce'){
		
		$modulepath = 'travel_approval/';	
		
		$sql="SELECT * from sma_travel_expenses where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";

		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
					
				
				$ce_id 		= $row['id'];
				$dated 		= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				
				//https://athaang.in/p2p2023/approval/voucher_prn.php?sub=pdf&id=4158&company_id=9&vendor_id=731
				$pdf_path = $baseurl."travel_approval/voucher_prn.php?sub=pdf&id=$ce_id&company_id=$company_id&vendor_id=$emp_id&vw=Y";
			
				$button[] = array(
						 'label'=>'View Voucher', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'CE' AND reference_id = " . $ce_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$ce_id, 
						 'dated'=>$dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='te'){
		
		$modulepath = 'travel_approval/';	
		
		$sql="SELECT * from sma_travel_expenses where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";

		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
					
				
				$te_id 		= $row['id'];
				$dated 		= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				
				$pdf_path = $baseurl."travel_approval/travel_exp_prn.php?sub=pdf&id=$te_id&&r=1";
			
				$button[] = array(
						 'label'=>'Report', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$pdf_path = $baseurl."travel_approval/voucher_prn.php?sub=pdf&id=$te_id&company_id=$company_id&vendor_id=$emp_id&vw=Y";
				$button[] = array(
						 'label'=>'View Voucher', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'TE' AND reference_id = " . $te_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$te_id, 
						 'dated'=>$dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='re'){
		
		$modulepath = 'travel_approval/';	
		
		$sql="SELECT * from sma_travel_expenses where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";

		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
					
				
				$re_id 		= $row['id'];
				$dated 		= date('d-m-Y', strtotime($row['dated']));
				$status		= $row['status'];
				
				$pdf_path = $baseurl."travel_approval/regular_exp_repo.php?sub=pdf&id=$re_id&&r=1";
				$button[] = array(
						 'label'=>'View Voucher', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$pdf_path = $baseurl."travel_approval/voucher_prn.php?sub=pdf&id=$re_id&company_id=$company_id&vendor_id=$emp_id&vw=Y";
				$button[] = array(
						 'label'=>'View Voucher', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'RE' AND reference_id = " . $re_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$re_id, 
						 'dated'=>$dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
		}
		
	}
	else if($module=='ta'){
		
		$modulepath = 'travel_approval/';	
		
		$sql="SELECT * from sma_traval_approval where 1 AND del !='Y' AND id = '$id_parameter' "; // AND current_approver = '$userid' AND company_id in ( $comid ) ";

		$result = mysqli_query($con,$sql);
		$po_cnt = mysqli_affected_rows($con);
		if($po_cnt>0){
			echo mysqli_error($con); 
			$row = mysqli_fetch_array($result);
				
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
				
				$pdf_path = $baseurl."travel_approval/travel_form_prn.php?sub=pdf&id=$re_id&comp_id=$company_id&r=1";
				$button[] = array(
						 'label'=>'HR TR Form', 
						 'pdf_link'=>$pdf_path
						 );	
				
				$sql = "SELECT * FROM file_uploads WHERE module = 'TA' AND reference_id = " . $re_id;
                $docResults = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($docRow = mysqli_fetch_array($docResults)) {
				    $doc_id			= $docRow['id'];
					$doc_desc 		= $docRow['doc_desc'];
					$doc_type 		= $docRow['doc_type'];
					$file_path		= $docRow['file_path']; 
					$file_name		= $docRow['file_name'];  
					
					$document_url	= $baseurl . $modulepath . $file_path . '/' .$file_name;
					
					$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$rw1 = mysqli_fetch_array($rs);
					$document_name = $rw1['document'];
					
					$document[] = array(
						 'slug'=>$module, 
						 'name'=>$document_name, 
						 'pdf_link'=>$document_url
						 );	
					 
				}
				$data[] = array(
						 'slug'=>$module, 
						 'userid'=>$userid, 
						 'trans_id'=>$re_id, 
						 'dated'=>$dated,
						 'buttons'=>$button,
						 'document'=>$document
						 );	
		}
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'successfull'; 
			$response['data'] = $data;
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	
	if($po_cnt<=0){
		$response['status'] = '0'; 
		$response['message'] = 'No records found...'; 
	}
	
	echo json_encode($response);
	
?>				 
    