<?php

	include "../baseurl.php";
	include "DbConnect.php";
	
	$id_parameter   = $_POST['id_parameter'];
	$module 		= trim($_POST['slug']);
	$userid 		= $_POST['userid'];
	$userid_v 		= $_POST['userid'];
	$remarks 		= $_POST['remarks'];
		
	$po_id = $id_parameter;
	$srno  = $po_id;
		
	$sql = " select * from sma_user where userid = '$userid' ";
	$q2 =mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$userid 			= $r2['id'];
	$user_name_by		= $r2['username'];
	$user				= $r2['username'];
$file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file);
			
	if($module=='po'){
		
		$po_id = $id_parameter;
		$srno  = $po_id;
		
		$modulepath 	= 'purchase_order/';	
		$doc_type		= 'PO';
		
		$sql = " select * from sma_purchase_order where id = '$po_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$po_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$draft_by 			= $r2['draft_by'];
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
	
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		
		$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
	
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$gst_amt = 0;
		$amount = 0 ;
		while($r1 = mysqli_fetch_array($res1)){
			$qty 		= $r1['quantity'];
			$rate 		= $r1['unit_rate'];
			$gst		= $r1['gst'];
			$budget_id	= $r1['budget_id'];
			
			$gst_amt = round((($qty * $rate) * $gst / 100),0);
			
			$amount = $qty * $rate + $gst_amt;
			
			$sql = "update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
			$q3  = mysqli_query($con, $sql);
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   	= $r4->blocked_budget;
			if($block_budget<0){
				$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
				$q3  = mysqli_query($con, $sql);
			}
			
		}
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "UPDATE sma_purchase_order SET approver_1 = '', approver_2 = '', approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
			approver_1_status='Submitted', approver_2_status='', approver_3_status='', approver_4_status='', approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$po_id' ";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
				
		$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date,mobile_update ) 
		VALUES( 'PO', '$po_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now(), 'Update by Mobile')";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
		
		$to_approver = $draft_by_id;
		
//Send mail to next approver;
		$sql="SELECT * FROM sma_user WHERE id='$to_approver' ";
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$username 		= $r->userid;
		$id		 		= $r->id;
		$role	 		= $r->role;
		$company_id 	= $r->company_id;
		$user_category 	= $r->user_category;
		$user_email		= $r->email;
		$user_name		= $r->username;

		$modulePath = "purchase_order/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;
		$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
		
	//include "po_mail.php";
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	else if($module=='mrn'){
		
		$pr_id = $id_parameter;
		$srno  = $pr_id;
		
		$modulepath 	= 'purchase_requisition/';	
		$doc_type		= 'PR';
		
		$sql = " select * from sma_purchase_req where id = '$pr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$pr_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$subject			= $r2['subject'];
		$po_type			= $r2['po_type'];
		$to_supplier		= $r2['to_supplier'];
		$department_id		= $r2['department_id'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "update sma_purchase_req set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$pr_id' ";
//echo $sql. "<BR>";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
				
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'PR', '$pr_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		
		$to_approver = $draft_by_id;
		
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
//echo $sql. "<BR>";
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$modulePath = "purchase_requisition/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$pr_id;
		$msg = 'Material Requisition Note Number : '.$pr_id . ' ' . 'Dated : ' . date("d-m-Y");
		
		 $baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pr_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		include "pr_mail.php";
		
		if($pr_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	else if($module=='grn'){
		
		$si_id = $id_parameter;
		$srno  = $si_id;
	
		$modulepath 	= 'supp_invoice/';	
		$doc_type		= 'GR';
		
		$sql = " select * from sma_supplier_invoice where id = '$si_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$grn_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 			= $r2['company_id'];
		$supplier_invoice_no	= $r2['supplier_invoice_no'];
		$invoice_date			= $r2['invoice_date'];
		$grndraft_by 			= $r2['grndraft_by'];
		
		$sql = " select * from sma_user where userid = '$grndraft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
	
			$sql = " update sma_supplier_invoice set approver_1 = '', approver_2 = '', 
			approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', 
			approver_8 = '', approver_1_status='', approver_2_status='', approver_3_status='', 
			approver_4_status='', approver_5_status='', approver_6_status='', approver_7_status='',
			approver_8_status='', status = 'Draft', approval_status = 'Rejected', changed_by = '$user', 
			current_approver = '$draft_by_id', changed_date = now(), tally_status = '', grn_status='Rejected', grn_approval_status = 'Rejected', grn_approver = '' where id = '$si_id'";
//			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = "update sma_supplier_invoice_details set qty = 0 where si_hdr_id = '$si_id' ";
			mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
			values('GR', '$si_id', '$userid', now(), 'Rejected', '$draft_by_id', '$remarks', now() )";
//			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$to_approver = $draft_by_id;
		
		$s="select * from sma_user where id='$to_approver' ";
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$msg = 'GRN Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date('d-m-Y', strtotime($invoice_date));
		
		$doctype = 'GR';
		include "si_mail.php";
		
		if($grn_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	else if($module=='si'){
		
		$si_id = $id_parameter;
		$srno  = $si_id;
	
		$modulepath 	= 'supp_invoice/';	
		$doc_type		= 'SI';
		$sql = " select * from sma_supplier_invoice where id = '$si_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$si_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];		
				
		$sql = " select * from sma_user where userid in (select draft_by from sma_supplier_invoice where id = '$si_id') ";	
		$q4  = mysqli_query($con, $sql);
		$r4  = mysqli_fetch_object($q4);
		$draft_by_id   	= $r4->id;
		
		$to_approver	= $draft_by_id;
				
		$sql="SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		$gst_amt = 0;
		while($rowd = mysqli_fetch_array($result)){
			
			$material_id 		= $rowd['material_id'];
			$budget_head 		= $rowd['budget_head'];
			
			$qty 	= $rowd['qty'];
			$rate 	= $rowd['rate'];
			$gst	= $rowd['gst'];
			
			if(empty($gst)){
				$gst = 0;
			}
			
			$gst_amt = round((($qty * $rate) * $gst / 100),0);
			
			$sql = "SELECT * FROM company where comp_id = '$company_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_control_gst = $r2['budget_control_gst'];
				
			$amount  = $qty * $rate + $gst_amt;
			$values  = $amount;
			$budget_head_id = $rowd['budget_name'];	
			$budget_id		= $rowd['budget_id'];			
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_object($q3);
			
			$sql = "update sma_budget set used_budget = used_budget - $values, blocked_budget = blocked_budget + $values  where id = '$budget_id' ";
//				mysqli_query($con, $sql);	

			$sql="update sma_po_items set bal_si_qty  = bal_si_qty - $qty, bal_si_amount  = bal_si_amount - $values where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id'";
//			$query=mysqli_query($con, $sql);		
			echo mysqli_error($con);
		
		}
		
		$sql = " update sma_supplier_invoice set approver_1 = '', approver_2 = '', 
			approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', 
			approver_8 = '', approver_1_status='Submitted', approver_2_status='', approver_3_status='', 
			approver_4_status='', approver_5_status='', approver_6_status='', approver_7_status='',
			approver_8_status='', status = 'Draft', approval_status = 'Rejected', changed_by = '$user', 
			current_approver = '$draft_by_id', changed_date = now(), tally_status = '' where id = '$si_id'";
//			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$s="select * from sma_user where id='$invoice_booked_by' ";		
			$sql = mysqli_query($con, $s);
			$r = mysqli_fetch_object($sql);
			$booked_by_email	= $r->email;
			$booked_by_name		= $r->username;
		
			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
			values('SI', '$si_id', '$userid', now(), 'Rejected', '$draft_by_id', '$remarks', now() )";
//			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
		$to_approver = $draft_by_id;
		$sql ="select * from sma_user where id='$to_approver' ";	
		$sql = mysqli_query($con, $sql);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
			
		$msg = 'Supplier Invoice Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date('d-m-Y', strtotime($invoice_date));

		$doctype = 'SI';
		include "si_mail.php";
	
		if($si_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}	
	else if($module=='ce'){
		
		$re_id = $id_parameter;
		$srno  = $re_id;
		
		$modulePath = "travel_approval/";
		$doc_type		= 'CE';
		
		$sql = " select * from sma_travel_expenses where id = '$re_id' "; 
		$q2		= mysqli_query($con, $sql);
		$re_cnt = mysqli_affected_rows($con);
		$r2 	= mysqli_fetch_array($q2);
		$po_type			= $r2['po_type'];
		$company_id 		= $r2['project'];
		$invoice_booked_by	= $r2['invoice_booked_by'];
		$draft_by 			= $r2['draft_by'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "SELECT a.reference, a.amount, a.budget_name, a.budget_head, a.budget_id, b.company_id, b.approval_number
					FROM `sma_expenses` a, sma_travel_expenses b
					where a.approval_ref_no = b.id  and b.id = '$re_id' ";
		$res 			= mysqli_query($con, $sql);
		while ($r 		= mysqli_fetch_object($res)){

			$exp_id			= $r->reference;
			$amount			= $r->amount;
			$budget_name	= $r->budget_name;
			$budget_head	= $r->budget_head;
			$company_id		= $r->company_id;
			$budget_id		= $r->budget_id;
			$approval_number= $r->approval_number;
			
			if(!empty($approval_number)){
				$sql = "update sma_budget set blocked_budget = blocked_budget + $amount, used_budget = used_budget - $amount  where id = '$budget_id' ";
				mysqli_query($con, $sql);
			}
			else if(empty($approval_number)){
				$sql = "update sma_budget set used_budget = used_budget - $amount where id = '$budget_id' ";
				mysqli_query($con, $sql);
			}
							
		}
	
		$sql = "update sma_travel_expenses set approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', approver_1 = '', approver_2 = '', 
		approver_3 = '', approver_4 = '',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$re_id' ";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";

		$s="select * from sma_user where id='$invoice_booked_by' ";		
		$sql = mysqli_query($con, $s);
		$r = mysqli_fetch_object($sql);
		$booked_by_email	= $r->email;
		$booked_by_name		= $r->username;

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'CE', '$re_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		$to_approver = $draft_by_id;
		
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
//exit();
		$result = mysqli_query($con, $sql);

		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;

		}
	
		$modulePath = "travel_approval/";
		
		$baseurl1 =$baseurl.$modulePath.'company_expense.php?sub=edit&id='.$re_id;
		
		$msg = ' Operating Expenses Memo Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = ' Operating Expenses ';
		$doc_type = 'CE';
		include "te_mail.php";
		
		if($re_id>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	else if($module=='te'){
		
		$te_id = $id_parameter;
		$srno  = $te_id;
		
		$modulePath = "travel_approval/";
		$doc_type		= 'TE';
		
		$sql = " select * from sma_travel_expenses where id = '$te_id' "; 
		$q2		= mysqli_query($con, $sql);
		$re_cnt = mysqli_affected_rows($con);
		$r2 	= mysqli_fetch_array($q2);
		
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$te_id' ) ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Rejected';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', changed_date = now() where id = '$te_id'";	
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('TE', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
	
		$sql="select * from sma_user where id in ($level_a)";
//echo $sql;
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;

		}
		$modulePath = "travel_approval/";

		$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$te_id;
		
		$msg = 'Travel Expenses Memo Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Travel Expenses';
		$re_id = $te_id;
		$doc_type = 'TE';
		include "te_mail.php";
		
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	else if($module=='re'){
		
		$re_id = $id_parameter;
		$srno  = $re_id;
		
		$modulePath = "travel_approval/";
		$doc_type		= 'RE';
		
		$sql = " select * from sma_travel_expenses where id = '$re_id' "; 
		$q2		= mysqli_query($con, $sql);
		$re_cnt = mysqli_affected_rows($con);
		$r2 	= mysqli_fetch_array($q2);
		
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$re_id' ) ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Rejected';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', changed_date = now() where id = '$re_id'";	
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('RE', '$re_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
	
		$sql="select * from sma_user where id in ($level_a)";
//echo $sql;
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;

		}
		$modulePath = "travel_approval/";

		$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$re_id;
		
		$msg = 'Reimbursement Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Reimbursement' . ' ' . $approval_status;
		
		$doc_type = 'RE';
		include "te_mail.php";
		
		
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Rejected successfull'; 
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);	
		}
		
	}
	
	echo json_encode($response);
?>		