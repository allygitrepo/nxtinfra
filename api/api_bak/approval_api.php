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
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		
		$approval_memo_ref	= $r2['approval_memo_ref'];	
		$subject			= $r2['subject'];
		$po_type			= $r2['po_type'];
		$to_supplier		= $r2['to_supplier'];
		$company_id 		= $r2['project'];
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";	
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		
	
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		//approver_1_status == 'Submitted' need to check for dupplicate approver 
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status == 'Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status == 'Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status == 'Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status == 'Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && $approver_6_status == 'Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && $approver_7_status == 'Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
		
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_purchase_order set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now(), mobile_upd_flag ='M' $sqla where id = '$po_id'";
//RAVI TEST		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update ) 
				values( 'PO', '$po_id', '$userid', now(), 'Approved',  '$to_approver', '$remarks', now(), 'Update by Mobile')";
//RAVI TEST		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}

$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		


//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
//echo $sql. "<BR>";
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);

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

		$modulePath = "purchase_order/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;
		$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';

		//include "po_mail.php";
		
		if($po_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
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
//echo $sql."<BR>";			
//exit();
		$q2	=	mysqli_query($con, $sql);
		$pr_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$subject			= $r2['subject'];
		$po_type			= $r2['po_type'];
		$to_supplier		= $r2['to_supplier'];
		$department_id		= $r2['department_id'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		
		$approver_1_status 		= $r2['approver_1_status'];
		$approver_2_status 		= $r2['approver_2_status'];
		$approver_3_status 		= $r2['approver_3_status'];
		$approver_4_status 		= $r2['approver_4_status'];
		$approver_5_status 		= $r2['approver_5_status'];
		$approver_6_status 		= $r2['approver_6_status'];
		$approver_7_status 		= $r2['approver_7_status'];
		$approver_8_status 		= $r2['approver_8_status'];
		
		$email_one				= $r2['email_one'];
		$email_two				= $r2['email_two'];
		$email_three			= $r2['email_three'];
		$email_four				= $r2['email_four'];
		$email_five				= $r2['email_five'];
		$email_six				= $r2['email_six'];
		$email_seven			= $r2['email_seven'];
		$email_eight			= $r2['email_eight'];
			
		$email_one_dept			= $r2['email_one_dept'];
		$email_two_dept			= $r2['email_two_dept'];
		$email_three_dept		= $r2['email_three_dept'];
		$email_four_dept		= $r2['email_four_dept'];
		$email_five_dept		= $r2['email_five_dept'];
		$email_six_dept			= $r2['email_six_dept'];
		$email_seven_dept		= $r2['email_seven_dept'];
		$email_eight_dept		= $r2['email_eight_dept'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver ){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver ){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver ){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver ){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver ){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver ){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver ){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_8== $approver ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
			
			$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_purchase_req set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$pr_id'";
//echo $sql." ##1<BR>";		
//RAVI TEST		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update) 
				values( 'PR', '$pr_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile')";
//RAVI TEST		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}
		
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
	
//RAVI TEST		include "pr_mail.php";
		
		if($pr_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);			
		}
		
	}
	else if($module=='grn'){
		
		$si_id = $id_parameter;
		$srno  = $si_id;
	
		$modulepath 	= 'supp_invoice/';	
		$doc_type		= 'PR';
		
		$sql = " select * from sma_supplier_invoice where id = '$si_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$grn_cnt = mysqli_affected_rows($con);
		$r2 =	mysqli_fetch_array($q2);
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$our_pr_no			= $r2['our_pr_no'];
		$grndraft_by 		= $r2['grndraft_by'];
		$invoice_date 		= $r2['invoice_date'];
		
		if(!empty($our_pr_no)){
			$sql = " select * from sma_purchase_req where id = '$our_pr_no' "; 
		}
		else {
			$sql = " select * from sma_purchase_order where id = '$our_po_ref_no' "; 	
		}	
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$draft_by 		= $r2['draft_by'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		$active				= $r2['active'];
		$active=0;//TEST
		if($active==1){
			$to_approver 		= $draft_by_id;
		}
		else if($approver>0 && $active==0){
			$to_approver 		= $approver;
			$sql = " select * from sma_user where id = '$approver' ";
//echo $sql." ###2<BR>";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_username		= $r2['username'];
		}

		if($status=='Submitted'){
			
			$sql = " select * from sma_user where userid = '$grndraft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_username		= $r2['username'];
			
			$to_approver 		= $draft_by_id;
			$approval_status = 'Completed';
			$sql = " update sma_supplier_invoice set status = 'Draft', grn_status = '$approval_status', grn_approval_status = 'Approved', grn_approver = '$to_approver', 
			changed_date = now() where id = '$si_id'";
//				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);
				if(!empty($error)){echo $error; exit();}

				$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update) 
				values('GR', '$si_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile' )";		
//				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
		
		}
		
		$sql="select * from sma_user where id='$to_approver' ";		
		$res = mysqli_query($con, $sql);
		$rowcount = mysqli_num_rows($res);
		while($r = mysqli_fetch_object($res)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$baseurl1 = $baseurl.$modulePath.'editgrn.php?id='.$srno;
		
		$baseurl1 = $baseurl.$modulePath.'editgrn.php?id='.$si_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$msg = 'GRN Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date('d-m-Y', strtotime($invoice_date));

		$doctype = 'GR';	
//		include "si_mail.php";
		
		if($grn_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
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
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$status_si	 		= $r2['status'];
		$invoice_booked_by	= $r2['invoice_booked_by'];
		
		$company_id 			= $r2['company_id'];
		$supplier_invoice_no	= $r2['supplier_invoice_no'];
		$invoice_date		= $r2['invoice_date'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
			
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status == 'Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status == 'Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status == 'Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status == 'Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && $approver_6_status == 'Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && $approver_7_status == 'Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}

	$ipc_flag = '';
	if($status	== 'Completed'){
		$sql = " select * from sma_user where userid in (select draft_by from sma_supplier_invoice where id = '$si_id') ";	
		$q4  = mysqli_query($con, $sql);
		$r4  = mysqli_fetch_object($q4);
		$draft_by_id   	= $r4->id;
		
		$to_approver	= $draft_by_id;
		
		$s="select * from sma_user where id='$userid' ";	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$approve_by_name 		= $r->username;
			$approve_by_email		= $r->email;
		}
		
		$ipc_flag = 'Y';
		
	}
	
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		
			$sql = " update sma_supplier_invoice set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now(), ipc_flag = '$ipc_flag' $sqla where id = '$si_id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update) 
			values('SI', '$si_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile' )";		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
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
	
//Create Tally JV	
		if($status=='Completed'){
			
			//create_tallyjv($si_id, 'SI');
			$sql 	= " UPDATE tally_journal_entry SET status = 'C' where doc_type = 'SI' and doc_no = '$si_id' ";
			$tally_affectedrow = mysqli_query($con, $sql);
			
			if($tally_affectedrow>0){
				$sql 	= " UPDATE sma_supplier_invoice SET tally_status='C', tally_ticked_by = '$userid' , tally_updated_on = now() where id = '$si_id' ";
				mysqli_query($con, $sql);
			}
			
		}
//Create Tally JV

		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$si_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
		
		
		$msg = 'Supplier Invoice Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date('d-m-Y', strtotime($invoice_date));

		$doctype = 'SI';
//		include "si_mail.php";
		
		if($si_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
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
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver ){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status=='Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status=='Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status=='Submitted'){
			$to_approver 	 = $approver_5;
			$status_field_from = 'approver_4_status';
			$status_field	 = 'approver_5_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && empty($approver_5) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_4_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_5== $approver && $approver_5_status=='Submitted'){
			$to_approver 	 = $approver_6;
			$status_field_from = 'approver_5_status';
			$status_field	 = 'approver_6_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_5== $approver && empty($approver_6) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_5_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		if( $approver_6== $approver && $approver_6_status=='Submitted'){
			$to_approver 	 = $approver_7;
			$status_field_from = 'approver_6_status';
			$status_field	 = 'approver_7_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_6== $approver && empty($approver_7) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_6_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		if( $approver_7== $approver && $approver_7_status=='Submitted'){
			$to_approver 	 = $approver_8;
			$status_field_from = 'approver_7_status';
			$status_field	 = 'approver_8_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_7== $approver && empty($approver_8) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_7_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		if( $approver_8== $approver && $approver_8_status=='Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field_from = 'approver_7_status';
			$status_field	 = 'approver_8_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_travel_expenses set $status_field = '$approval_status', 
		approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$re_id'";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update ) 
				values( 'CE', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile')";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

	if($status == 'Completed' ){
			
			$sql = " UPDATE sma_travel_expenses SET tally_status='C', tally_updated_on = now() , tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
			mysqli_query($con, $sql);
			
			$sql = " UPDATE tally_journal_entry SET status = 'C' where doc_type = 'CE' and doc_no = '$re_id' ";
			mysqli_query($con, $sql);
			
	}
	
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
		
	
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);			
		}
	
	}
	else if($module=='te'){
		
		$re_id = $id_parameter;
		$srno  = $re_id;
		
		$modulePath = "travel_approval/";
		$doc_type		= 'TE';
		$sql = " select * from sma_travel_expenses where id = '$re_id' "; 
		$q2		= mysqli_query($con, $sql);
		$re_cnt = mysqli_affected_rows($con);
		$r2 	= mysqli_fetch_array($q2);
		$po_type			= $r2['po_type'];
		$company_id 		= $r2['project'];
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver ){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status=='Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status=='Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status=='Submitted'){
			$to_approver 	 = $approver_5;
			$status_field_from = 'approver_4_status';
			$status_field	 = 'approver_5_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		
		if( $approver_4== $approver && empty($approver_5) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_4_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_5== $approver && $approver_5_status=='Submitted'){
			$to_approver 	 = $approver_6;
			$status_field_from = 'approver_5_status';
			$status_field	 = 'approver_6_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_5== $approver && empty($approver_6) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_5_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		if( $approver_6== $approver && $approver_6_status=='Submitted'){
			$to_approver 	 = $approver_7;
			$status_field_from = 'approver_6_status';
			$status_field	 = 'approver_7_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_6== $approver && empty($approver_7) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_6_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		if( $approver_7== $approver && $approver_7_status=='Submitted'){
			$to_approver 	 = $approver_8;
			$status_field_from = 'approver_7_status';
			$status_field	 = 'approver_8_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = $approval_status;
		}
		if( $approver_7== $approver && empty($approver_8) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_7_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_8== $approver && $approver_8_status=='Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field_from = 'approver_7_status';
			$status_field	 = 'approver_8_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_travel_expenses set $status_field = '$approval_status', 
		approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$re_id'";

//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date , mobile_update) 
				values( 'TE', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile')";
//		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql="select * from sma_user where id in ($to_approver)";
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
		
		$sql="select * from sma_user where find_in_set('$company_id', company_id) and role in (select id from sma_role where role ='Accountant') ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$ac_email		= $r->email;
		$ac_email_name	= $r->username;
		
		//create_tallyjv($te_id, 'TE');
		
		$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$te_id;
		
		$msg = 'Travel Expenses Memo Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Travel Expenses';
		$re_id = $te_id;
		$doc_type = 'TE';
//		include "te_mail.php";
		
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);			
		}
		
	}
	else if($module=='re'){
		
		$re_id = $id_parameter;
		$srno  = $re_id;
		
		$modulePath = "travel_approval/";
		$doc_type	= 'RE';
		$sql		= "SELECT * from sma_travel_expenses where id = '$re_id' ";
		$result 	= mysqli_query($con, $sql);
		$r2 		= mysqli_fetch_array($result);
		
		$tally_narration	= $r2['tally_narration'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		    $sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_name 		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_company_work	= $r2['company_work'];

			$status_field_from ='';
			$decision_status	= '';
			$approver = $userid;
			if( $approver_1== $approver ){
				$to_approver 	 	= $approver_2;
				$status_field_from 	= 'approver_1_status';
				$status_field	 = 'approver_2_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_1== $approver && empty($approver_2) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_1_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_2== $approver && $approver_2_status=='Submitted'){
				$to_approver 	 = $approver_3;
				$status_field_from = 'approver_2_status';
				$status_field	 = 'approver_3_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_2== $approver && empty($approver_3) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_2_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_3== $approver && $approver_3_status=='Submitted'){
				$to_approver 	 = $approver_4;
				$status_field_from = 'approver_3_status';
				$status_field	 = 'approver_4_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_3== $approver && empty($approver_4) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_3_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_4== $approver && $approver_4_status=='Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = $approval_status;
			}
			
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status=='Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
			if( $approver_6== $approver && $approver_6_status=='Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
			if( $approver_7== $approver && $approver_7_status=='Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
			if( $approver_8== $approver && $approver_8_status=='Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = " update sma_travel_expenses set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$re_id'";
	//			$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

//Double same approver start
			if($status=='Submitted'){
				
				$sql = " update sma_travel_expenses set approver_1_status = 'Approved', approver_2_status = 'Approved' where approver_1 = '$approver' and approver_2 = '$approver' and id = '$re_id'";
		//		$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit('Error while SI update');}
			}
//Double same approver end

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, mobile_update) 
				values('RE', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now(), 'Update by Mobile' )";		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}

		$sql="select * from sma_user where id in ($to_approver)";
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
		
	//	create_tallyjv($re_id, 'RE');
		
		$baseurl1 =$baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$re_id;
		
		$msg = ' Reimbursement Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = ' Reimbursement ';
		$doc_type = 'RE';
//		include "te_mail.php";
		
		if($re_cnt>0){
			$response['status'] = '1'; 
			$response['message'] = 'Approved successfull';
			$response['userid'] = $userid_v;
			$response['slug']  = trim($_POST['slug']);			
		}
		
	}
	
	echo json_encode($response);
	
?>		