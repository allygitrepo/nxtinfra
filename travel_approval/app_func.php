<?php session_start();

	include('../dbcon.php');
	include('../baseurl.php');
	
	$userid   	= $_SESSION['usrid'];
	$role		= $_SESSION['role'];
	$primary_role = $_SESSION['primary_role'];
	$short_fy_code	= $_SESSION['short_fy_code'];
	
?>

<?php

$te_id = '';
$ta_id = '';

if(isset($_POST['sub1'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$ta_id		= $_POST['ta_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
	if($mode=='Checker'){
	
		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql = " select * from sma_user where userid='$user' ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->level_1;
		
		$sql = "update sma_traval_approval set approval_status = '$approval_status', status = '$status', changed_by = '$user', approver_1_status = 'Submitted', approver_1 = '$level_a', current_approver = '$level_a', level_1 = '$level_a', level_1_flag = 'Y', changed_date = now() where id = '$ta_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
		values( 'TA', '$ta_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now() )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

//echo $sql;
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
	}
	
	$modulePath = "travel_approval/";
		$doc_type = 'TA';
		$baseurl1 =$baseurl.$modulePath.'traval_app.php?sub=edit&id='.$ta_id;
		
		$msg = 'Travel Approval Memo Number : '.$ta_id . ' ' . 'Date : ' . date("d-m-Y");
		include "ta_mail.php";
		
	
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=list';
		
	//	echo $baseurl1;
		//exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}	


if(isset($_POST['sub3'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$ta_id		= $_POST['ta_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
	if($mode=='Submitted'){
	
		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql="select * from sma_user where userid in (select draft_by from sma_traval_approval where id = '$ta_id' ) ";
		//$sql="select * from sma_user where userid='$user' ";
//echo $sql;		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->level_2;
		
		$sql = "update sma_traval_approval set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', level_2 = '$level_a', level_1_flag = 'N', level_2_flag = 'Y' , level_3_flag = 'N', changed_date = now() where id = '$ta_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('TA', '$ta_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
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
		
	}
	
		$modulePath = "travel_approval/";
		
		$baseurl1 =$baseurl.$modulePath.'traval_app.php?sub=edit&id='.$ta_id;
		
		$msg = 'Travel Approval Memo Number : '.$ta_id . ' ' . 'Date : ' . date("d-m-Y");
		include "ta_mail.php";
		
	
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}	


if(isset($_POST['sub2'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$ta_id		= $_POST['ta_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
if($status=='Submitted'){
	
		$approval_status = 'Approved';
		$status 		 = 'Completed';
		$approver		 = $userid;
		
		$sql="select * from sma_traval_approval where id = '$ta_id' ";
		$result = mysqli_query($con, $sql);
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
			
//echo $sql."<BR>";
//echo $draft_by_id;
	
			$status_field_from ='';
			$decision_status	= '';
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
			
		if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = " update sma_traval_approval set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$ta_id'";
	//echo $sql. "<BR>";		//exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('TA', '$ta_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
	//echo $sql. "<BR>";		
			
		}
	
	}
		
//echo $sql;
		$sql="select * from sma_user where id in ($to_approver)";
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
		
		$baseurl1 =$baseurl.$modulePath.'traval_app.php?sub=edit&id='.$ta_id;
		
		$msg = 'Travel Approval Memo Number : '.$ta_id . ' ' . 'Date : ' . date("d-m-Y");
		include "ta_mail.php";
	
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
		$baseurl1 = $baseurl."dashboard.php?sub=dash";
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}



if(isset($_POST['sub4'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id		= $_POST['te_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
//echo $mode . ' ' . $te_id;
//exit();
	
	if($mode=='Checker'){
	
		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql="select * from sma_user where userid='$user' ";
		//echo $sql;		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->level_1;
		
		if(empty($level_a)){
			
			$level_a = 0;
			
			echo " Approver list should not be empty...";
			exit();
			
		}
		else {
			
			$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user',  approver_1_status = 'Submitted', approver_1 = '$level_a', current_approver = '$level_a', send_to = '$level_a', level_2 = '$level_a', level_1_flag = 'N', level_2_flag = 'Y', level_3_flag = 'N', changed_date = now() where id = '$te_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
	//echo $sql;

			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
										values('TE', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
	//echo $sql;
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
		}
	}	
	
		$modulePath = "travel_approval/";
			
			$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$te_id;
			
			$msg = 'Travel Expenses Memo Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
			$msg1 = 'Travel Expenses';
			$re_id = $te_id;
			$doc_type = 'TE';
			include "te_mail.php";
			
			echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
			
			$baseurl1 = $baseurl.$modulePath.'travel_expence.php?sub=list';
			
	//		echo $baseurl1;
	//		exit("Ravindra Exit");
		
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();

}	


if(isset($_POST['sub55'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id		= $_POST['te_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
	if($mode=='Submit'){
	
		//$approval_status = 'Submitted';
		//$status 		 = 'Submitted';
		
		$approval_status = 'Approved';
		$status 		 = 'Completed';
		
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$te_id' ) ";
		
//echo $sql;		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->level_1;
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', level_1 = '$level_a', level_1_flag = 'N', level_2_flag = 'Y', level_3_flag = 'N', changed_date = now() where id = '$te_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('TE', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
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
		
		$sql = " select company_id from sma_travel_expenses where id = '$te_id' ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$company_id		= $r->company_id;
		
		$sql="select * from sma_user where find_in_set('$company_id', company_id) and role in (select id from sma_role where role ='Accountant') ";
		
//echo $sql."<BR>";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$ac_email		= $r->email;
		$ac_email_name	= $r->username;
		
	}
	
	$modulePath = "travel_approval/";
		
		
		$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$te_id;
		
		$msg = 'Travel Approval Memo Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Travel Approval';
		$re_id = $te_id;
		$doc_type = 'TA';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'travel_expence.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
//	exit("Ravindra Exit");
}	


if(isset($_POST['sub6'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id		= $_POST['te_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$re_id		= $te_id;
	
//	echo $status. ' '. $te_id;
//	exit();
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	$approver	= $userid;
	$role		= $_SESSION['role'];
	$user_name_by 		= $_SESSION['user_name_by'];
	
		$sql = " select * from sma_travel_expenses where id = '$re_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
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
		
	if($statusap=='Reject'){
		
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
			
			//echo $sql . "<BR>";	
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
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
//Double same approver start
			if($status=='Submitted'){
				$sql = " update sma_travel_expenses set approver_1_status = 'Approved', approver_2_status = 'Approved' where approver_1 = '$approver' and approver_2 = '$approver' and id = '$re_id'";
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);
				if(!empty($error)){echo $error; exit('Error while SI update');}
			}
//Double same approver end

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'TE', '$re_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_travel_expenses set $status_field = '$approval_status', 
		approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$re_id'";

		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'TE', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

	}
	
//echo $sql;
		$sql="select * from sma_user where id in ($to_approver)";
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
	
/* 	//Budget Used Start//		
			$sql = "SELECT a.reference, a.amount, a.budget_name, a.budget_head, a.budget_id, b.company_id 
					FROM `sma_expenses` a, sma_travel_expenses b
					where a.approval_ref_no = b.id  and b.id = '$te_id' ";
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->reference;
				$amount			= $r->amount;
				$budget_name	= $r->budget_name;
				$budget_head	= $r->budget_head;
				$company_id		= $r->company_id;
				$budget_id		= $r->budget_id;
				
				$sql = " UPDATE `sma_budget` set used_budget = used_budget + $amount 
						where id = '$budget_id'	";
				mysqli_query($con, $sql);
				//echo $sql . "<BR>";	
			}
	
//Budget Used End//	 */	

	$modulePath = "travel_approval/";
		
		$sql="select * from sma_user where find_in_set('$company_id', company_id) and role in (select id from sma_role where role ='Accountant') ";
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$ac_email		= $r->email;
		$ac_email_name	= $r->username;
		
		create_tallyjv($te_id, 'TE');
		
		$baseurl1 =$baseurl.$modulePath.'travel_expence.php?sub=edit&id='.$te_id;
		
		$msg = 'Travel Expenses Memo Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Travel Expenses';
		$re_id = $te_id;
		$doc_type = 'TE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'travel_expence.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
		$baseurl1 = $baseurl."dashboard.php?sub=dash";
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
}


//Rejected Traval Expenses
if(isset($_POST['sub7'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id		= $_POST['te_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$te_id' ) ";
		
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Rejected';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', changed_date = now() where id = '$te_id'";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('TE', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
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
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'travel_expence.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
}

//Rejected Traval Approval
if(isset($_POST['sub8'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$ta_id		= $_POST['ta_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
		$sql="select * from sma_user where userid in (select draft_by from sma_traval_approval where id = '$ta_id' ) ";
		
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Draft';
		
		$sql = "update sma_traval_approval set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', current_approver ='$level_a', approver_1_status='', approver_2_status='', changed_date = now() where id = '$ta_id'";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('TA', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
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
		
		$baseurl1 =$baseurl.$modulePath.'traval_app.php?sub=edit&id='.$ta_id;
		
		$msg = 'Travel Approval Memo Number : '.$ta_id . ' ' . 'Date : ' . date("d-m-Y");
		include "ta_mail.php";
		
	
		$baseurl1 = $baseurl.$modulePath.'traval_app.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}

if(isset($_POST['sub9'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$start_date		= date('Y-m-d', strtotime($_POST['start_date']));
	$start_place	= $_POST['start_place'];
	$start_time		= $_POST['start_time'];
	$end_date		= date('Y-m-d', strtotime($_POST['end_date']));
	$end_place		= $_POST['end_place'];
	$end_time		= $_POST['end_time'];
	$mode_of_travel	= $_POST['mode_of_travel'];
	$spend_by		= $_POST['spend_by'];
	$invoice_no		= $_POST['invoice_no'];
	$fare			= $_POST['fare'];
	$gst_amount		= $_POST['gst_amount'];
	$file_attach	= $_POST['file_attach'];
	$vendor_id		= $_POST['vendor_id'];
	
	$folder_path = "uploads/te/";
	if (!file_exists($folder_path)){
		mkdir($folder_path, 0755, true);
	}
	
/*	$arrFUDoc = $_FILES["file_attach"];
	
	$filename = $arrFUDoc['name'];
	$tmpFileName = $arrFUDoc['tmp_name'];
*/
//echo $filename. ' ' . $tmpFileName;
//exit();

	$sql="Insert into sma_departure (approval_ref_no, start_date, start_place, start_time, end_date, end_place, finish_time, mode_of_travel, spend_by, invoice_no , fare, gst_amount, vendor_id ) 
	VALUES('$approval_ref_no', '$start_date', '$start_place', '$start_time', '$end_date', '$end_place', '$end_time', '$mode_of_travel', '$spend_by', '$invoice_no', '$fare', '$gst_amount', '$vendor_id' )";
	$result = mysqli_query($con, $sql);
/* echo $sql;
exit(); */

//	move_uploaded_file($tmpFileName, "uploads/te/" . $filename);
	
?>
	
<table id="prtable" class="table table-bordered table-striped">
									 <tr>
											<th> SrNo.</th>
											<th> Invoice No.</th>
											<th> Start Date</th>
											<th> Start Place</th>
											<th> Start Time</th>
											<th> End Date</th>
											<th> End Place</th>
											<th> Finish Time</th>
											<th> Mode of Travel / Spend By</th>
											<th style="text-align:right;"> Fare in Rs.</th>
											<th style="text-align:right;"> Action</th>
									 </tr>
									
<tbody>
<?php
	$modulePath1 = "travel_approval/";
	$sql="SELECT * from sma_departure where approval_ref_no = '$approval_ref_no' ";
echo '';
//echo $sql;
//exit();	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$j = $j + 1;
		
		$tot_fare += $row['fare'];
		
		$spend_by = $row['spend_by'];
		if($spend_by =='C'){
			$spend_by = 'Company';
		}
		else if($spend_by =='O'){
			$spend_by = 'OWN';
		}

?>
	<tr>
		<td width="2%"><?php echo $j;?></td>
		<td width="10%"><?php echo $row['invoice_no'];?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['start_date']));?></td>
		<td width="10%"><?php echo $row['start_place'];?></td>
		<td width="10%"><?php echo $row['start_time'];?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['end_date']));?></td>
		<td width="10%"><?php echo $row['end_place'];?></td>
		<td width="10%"><?php echo $row['finish_time'];?></td>
		<td width="13%"><?php echo $row['mode_of_travel']. '/ '.$spend_by;?></td>
		<td width="07%" style="text-align:right;"><?php echo $row['fare'];?></td>
		<td width="10%" style="text-align:right;">
		<?php $reid = $row['id']; ?>
				<a href='#modalEditExp' data-id='<?php echo $reid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditExp<?php echo $reid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
				<?php  include "edit_exp_func.php"; ?>							
<!-- Modal Edit Item-->								

<!--			<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
			<a href="delete_departure.php?sub=delete&id=<?php echo $row['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
			
		</td>
    </tr>

	<?php }?>
	<tr> <th colspan="9" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo $tot_fare; ?> </th><th colspan="2"></th></tr>
	
</tbody> 
</table>
<?php
	
}	
	
	
	
if(isset($_POST['sub10'])){
	
	$approval_ref_no= $_POST['approval_ref_no'];
	$exp_type		= $_POST['exp_type'];	
	$expence_name	= $_POST['expence_name'];
	$dated			= date('Y-m-d', strtotime($_POST['dated']));
	$amount			= $_POST['amount'];
	$spend_by		= $_POST['spend_by'];
	$advance_amount	= $_POST['advance_amount'];
	$invoice_nm		= $_POST['invoice_nm'];
	$remarks		= $_POST['remarks'];
	$gst_amount		= $_POST['gst_amount'];
	$gst_perc		= $_POST['gst_perc'];
	$budget_name	= $_POST['budget_name'];
	$budget_head	= $_POST['budget_head'];
	$budget_id		= $_POST['budget_id'];
	
	$comp_id		= $_POST['comp_id'];
	$company_id		= $_POST['company_id'];
	$tds_id			= $_POST['itemtds_id'];
	$vendor_id		= $_POST['vendor_id'];
	
	$sql = " SELECT * FROM account_mst WHERE id = '$tds_id' ";
	$q2  = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);
	$tds 	= $r2['percentage'];
		
	$dated_h		= date('Y-m-d', strtotime($_POST['dated_h']));

	if(empty($invoice_nm)){
		$invoice_nm	 ='NA';
	}
	
//exit();
	$sql = "SELECT * FROM  sma_travel_expenses where id = '$approval_ref_no' ";
	$res 	= mysqli_query($con, $sql);
	$r 		= mysqli_fetch_object($res);
	$approval_number= $r->approval_number;
	$company_id		= $r->company_id;
	
	$sql="Insert into sma_expenses ( exp_type ,approval_ref_no, reference, dated, invoice_no, amount, spend_by, note, gst_amount, gst, budget_name, budget_head, budget_id, tds, tds_id , vendor_id) values( '$exp_type', '$approval_ref_no', '$expence_name', '$dated', '$invoice_nm', '$amount', '$spend_by', '$remarks', '$gst_amount', '$gst_perc', '$budget_name', '$budget_head', '$budget_id', '$tds', '$tds_id', '$vendor_id' )";
// echo $sql."<BR>";	
// exit();	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);

		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		if($budget_control_gst=='Y'){
			$tot_amount = $amount + $gst_amount;	
		}
		else if($budget_control_gst=='N'){
			$tot_amount = $amount;	
		}
		
		$sql = "update sma_budget set used_budget = used_budget + $tot_amount where id = '$budget_id' ";
		mysqli_query($con, $sql);
		
	$sql 	= "UPDATE sma_travel_expenses SET no_budget = 'Y' WHERE id = '$approval_ref_no' ";
	mysqli_query($con, $sql);
	
//echo $sql. "<BR>";					
//exit('Exit Here Add...');	
	
	$modulePath1 = "travel_approval/";
	if($exp_type=='C'){
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$approval_ref_no;
	}
	else if($exp_type=='T'){
		$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$approval_ref_no;
	}
	else if($exp_type=='R'){
		$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$approval_ref_no;
	}
			
			$sql = "select * from sma_product where id = '$expence_name'";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$product_name 	= $r1['name'];
			
			$sql 	= "select * from sma_party_mst where id = '$vendor_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= 'company_expense.php';
			include "../viewonly.php";
			$description 	= $approval_number . ',' . $dated. ','. $tot_amount. ','. $product_name ;
		    $affect 		= 'Product Added';
			$user_name		= $_SESSION['user'];
			$sql = " INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action` ) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
	echo "<script>window.location.href='$baseurl1';</script>";
	exit();

}

if(isset($_POST['sub100'])){
	
	$approval_ref_no= $_POST['approval_ref_no'];
	$exp_type		= $_POST['exp_type'];	
	$expence_name	= $_POST['expence_name'];
	$dated			= date('Y-m-d', strtotime($_POST['dated']));
	$amount			= $_POST['amount'];
	$spend_by		= $_POST['spend_by'];
	$advance_amount	= $_POST['advance_amount'];
	$invoice_nm		= $_POST['invoice_nm'];
	$remarks		= $_POST['remarks'];
	$gst_amount		= $_POST['gst_amount'];
	$budget_name	= $_POST['budget_name'];
	$budget_head	= $_POST['budget_head'];
	$budget_id		= $_POST['budget_id'];
	$emp_id			= $_POST['emp_id'];
	$comp_id		= $_POST['comp_id'];
	$dated_h		= date('Y-m-d', strtotime($_POST['dated_h']));

	if (!empty($comp_id)){
		$sql = "update sma_travel_expenses set company_id ='$comp_id', emp_id = '$emp_id' , dated ='$dated_h' where id='$approval_ref_no' ";
		mysqli_query($con, $sql);
		mysqli_error($con);
	}
//echo $sql;	exit();

	if(empty($invoice_nm)){
		$invoice_nm	 ='NA';
	}
	
	$sql="Insert into sma_expenses ( exp_type ,approval_ref_no, reference, dated, invoice_no, amount, spend_by, note, gst_amount, budget_name, budget_head, budget_id ) values( '$exp_type', '$approval_ref_no', '$expence_name', '$dated', '$invoice_nm', '$amount', '$spend_by', '$remarks', '$gst_amount', '$budget_name', '$budget_head', '$budget_id' )";
//echo $sql;
//	exit();

	$result = mysqli_query($con, $sql);

	$sql = "update sma_budget set used_budget = used_budget + $amount where id = '$budget_id' ";
	mysqli_query($con, $sql);
			
	$modulePath1 = "travel_approval/";
	$baseurl1 	= $baseurl.$modulePath1.'regular_expense.php?sub=edit&id='.$approval_ref_no;
	
	echo "<script>window.location.href='$baseurl1';</script>";
	exit();
	
//	move_uploaded_file($tmpFileName, "uploads/te/" . $filename);

?>


<?php
	
}	

//Regular Expenses - Pending
if(isset($_POST['sub11'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$approver	= $_POST['approver'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];

	if($mode=='Checker'){
	
		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql="select * from sma_user where userid='$user' ";
		//echo $sql;		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->level_1;
		
		if(empty($level_a)){
			
			$level_a = 0;
			
			echo " Approver should not be empty...";
			exit();
			
		}
		else {
			
			$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user',  approver_1_status = 'Submitted', approver_1 = '$level_a', current_approver = '$level_a', send_to = '$level_a', level_2 = '$level_a', level_1_flag = 'N', level_2_flag = 'Y', level_3_flag = 'N', changed_date = now() where id = '$re_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
	//echo $sql;

			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
										values('RE', '$re_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
	//echo $sql;
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
		}
	}	
	
	$modulePath = "travel_approval/";
		
		$baseurl1 =$baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$re_id;
		
		$msg = 'Reimbursement Memo Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Reimbursement';
		$doc_type = 'RE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'regular_expense.php?sub=list';
		
//		echo $baseurl1;
//		exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

}


//Regular Expenses -  Approval
if(isset($_POST['sub12'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	//create_tallyjv($re_id, 'RE');
//	exit();
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
//	echo $status. ' '. $te_id;
//	exit();
if($status=='Submitted'){
	
		$approval_status = 'Approved';
		$status 		 = 'Completed';
		
		$sql="select * from sma_travel_expenses where id = '$re_id' ";
		$result = mysqli_query($con, $sql);
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
			
//echo $sql."<BR>";
//echo $draft_by_id;
	
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
			
		if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = " update sma_travel_expenses set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$re_id'";
	//echo $sql. "<BR>";		exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

//Double same approver start
			if($status=='Submitted'){
				
				$sql = " update sma_travel_expenses set approver_1_status = 'Approved', approver_2_status = 'Approved' where approver_1 = '$approver' and approver_2 = '$approver' and id = '$re_id'";
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit('Error while SI update');}
			}
//Double same approver end

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('RE', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
	//echo $sql. "<BR>";		
			
		}
	
	}

	
		$sql="select * from sma_user where id in ($to_approver)";
//echo $sql;
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
		
		create_tallyjv($re_id, 'RE');
		
		$baseurl1 =$baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$re_id;
		
		$msg = ' Reimbursement Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = ' Reimbursement ';
		$doc_type = 'RE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		$baseurl1 = $baseurl.$modulePath.'regular_expense.php?sub=list';
		
	//	echo $baseurl1;
//		exit("Ravindra Exit");
		$baseurl1 = $baseurl."dashboard.php?sub=dash";
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}


//Rejected Traval Expenses
if(isset($_POST['sub13'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$re_id' and exp_type = 'R') ";
		
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Rejected';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', changed_date = now() where id = '$re_id' and exp_type = 'R'";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('RE', '$re_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
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
		
		
		$baseurl1 =$baseurl.$modulePath.'regular_expense.php?sub=edit&id='.$re_id;
		
		$msg = 'Reimbursement Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Reimbursement' . ' ' . $approval_status;
		$doc_type = 'RE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		$baseurl1 = $baseurl.$modulePath.'regular_expense.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}


//Operating Expenses. Pending
if(isset($_POST['sub14'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$approver	= $_POST['approver'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];


		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$approver', level_1 = '$approver',  level_1_flag = 'N', level_2_flag = 'N', level_3_flag = 'Y', changed_date = now() where id = '$re_id' and exp_type = 'C'";
//echo $sql;
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
					values('CE', '$re_id', '$userid', now(), '$approval_status', '$approver', '$remarks', now())";
//echo $sql;


		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
		$sql="select * from sma_user where id in ($approver)";
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
				
		$baseurl1 =$baseurl.$modulePath.'company_expense.php?sub=edit&id='.$re_id;
		
		$msg = 'Operating Expenses Memo Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Operating Expenses';
		$doc_type = 'CE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'company_expense.php?sub=list';
		
//		echo $baseurl1;
//		exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

}


//Operating Expenses. Approval
if(isset($_POST['sub15'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	$statusap	= $status;
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	$approver	= $userid;
	$role		= $_SESSION['role'];
	//$company			= $_POST['company'];		
	$user_name_by 		= $_SESSION['user_name_by'];
	//$company_id	= $_SESSION['company_id'];
	
		$sql = " select * from sma_travel_expenses where id = '$re_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$exp_type			= $r2['exp_type'];
		if($exp_type=='D'){
			$doc_type = 'DE';
		}	
		else if($exp_type=='C'){
			$doc_type = 'CE';
		}
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
		
		
	if($statusap=='Reject'){
		
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
			
			//echo $sql . "<BR>";	
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
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";

		$s="select * from sma_user where id='$invoice_booked_by' ";		
		$sql = mysqli_query($con, $s);
		$r = mysqli_fetch_object($sql);
		$booked_by_email	= $r->email;
		$booked_by_name		= $r->username;

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( '$doc_type', '$re_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_travel_expenses set $status_field = '$approval_status', 
		approval_status = '$approval_status', no_budget = '', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$re_id'";

		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( '$doc_type', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

	}
	
	if($status == 'Completed' ){
			
			$sql = " UPDATE sma_travel_expenses SET  tally_status='C', tally_updated_on = now() , tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
			mysqli_query($con, $sql);
			
			$sql = " UPDATE tally_journal_entry SET status = 'C' where doc_type = '$doc_type' and doc_no = '$re_id' ";
			mysqli_query($con, $sql);
			
			$sql = "SELECT * FROM `tally_journal_entry` where doc_no = '$re_id' and doc_type = 'CE' and account_type = 'V' and effect = 'Cr';";
			$tqry  = mysqli_query($con, $sql);
			$t2 = mysqli_fetch_assoc($tqry);
			$total_amount 	 = $t2['amount'];
			$sql = "UPDATE sma_travel_expenses SET total_amount = '$total_amount' where id = '$re_id' and exp_type = 'C' ";
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
	
	if($doc_type=='DE'){
		$msg = ' Direct Expenses Memo Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = ' Direct Expenses ';
	}		
		
		//$doc_type = 'CE';
		include "te_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
//exit('Stopped for Testing....');		
	
		$role		= $_SESSION['role']; 
		
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash";
			echo "<script>window.location.href='$baseurl1';</script>";
		//	$baseurl1 = $baseurl.$modulePath.'company_expense.php?sub=list';
		//	echo "<script>window.location.href='$baseurl1';</script>";
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
}


//Operating Expenses. Rejected
if(isset($_POST['sub16'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
		$sql="select * from sma_user where userid in (select draft_by from sma_travel_expenses where id = '$te_id' and exp_type = 'C') ";
		
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Draft';
		
		$sql = "update sma_travel_expenses set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$level_a', changed_date = now() where id = '$te_id' and exp_type = 'C'";
//echo $sql;		
//exit();
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('CE', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
		$sql="select * from sma_user where id in ($level_a)";
//echo $sql;
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
		
		$baseurl1 =$baseurl.$modulePath.'company_expense.php?sub=edit&id='.$te_id;
		
		$msg = 'Operating Expenses Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Operating Expenses' . ' ' . $approval_status;
		$re_id = $te_id;
		$doc_type = 'CE';
		include "te_mail.php";
	
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl.$modulePath.'company_expense.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}


	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc 	 = $_POST['party_id_doc'];
		$company_idd_doc = $_POST['company_idd_doc'];
		
		if($_POST['id'] == ''){$id = '';}
?>			
			<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Party Name</th>
					<th>Document Type</th>
					<th>File Path</th>
					<th>File Name</th>
					<th>Inward No</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>

<?php				
		$sql = "SELECT a.id, a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><a href="<?php echo $baseurl.'dms/'.$row['file_path'].'/'.$row['file_name'];?>" target="_blank"><?php echo $row['file_name'];?></a> </td>
					<td width="10%" ><?php echo $row['inward_no'];?></td>
					
					<td width="05%">
						<input type="checkbox" name="party_doc[]" <?php echo $checked; ?> id="party_doc" value="<?php echo $row['id']; ?>" >
					</td>
			</tr>
<?php			
			}
			
			$value = '';
					
//$value=$sql;

        echo $value;
    }
	
	
	if(isset($_POST['sub24'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}

			$sql = "SELECT * FROM sma_party_mst where id = '$id' "; 
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$row = mysqli_fetch_array($result);
			$pan_no = $row['party_pan_number'];
			$gst_no = $row['party_gst_number'];
			
?>				
				<label for="itemquote_ref_no" class="col-sm-2 control-label">PAN No.</label>
				<div class="col-sm-4">
						<input type="text" class="form-control" readonly value="<?php echo $pan_no ?> " >
				</div>
				<label for="itemquote_ref_no" class="col-sm-2 control-label">GST No.</label>
				<div class="col-sm-4">
						<input type="text" class="form-control" readonly value="<?php echo $gst_no ?> " >
				</div>
<?php			
				
//$value=$sql;

    }

	if(isset($_POST['sub25'])){
    	
	    $id = $_POST['id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		//$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
		$value .='<select class="form-control" name="budget_id" id="budget_ID" required="true" onchange="getcatbudget(this.value)"  >
			<option value="" selected > Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id'  ";		
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
        echo $value;
	
	}
	
	if(isset($_POST['sub26'])){
		
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id = $r2->budget_name;
			$budget_head 	= $r2->budget_head;		
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			$balance_budget	= $total_budget - ($blocked_budget + $used_budget);
            $budget_id 		 	= $r2->id;
            
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
?>			
			<input type="hidden"  id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden"  id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			<input type="hidden"  id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		
			<div class="form-group">
				<label class="control-label col-sm-2">Total Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" style="text-align:right;" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<label class="control-label col-sm-2">Balance Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" style="text-align:right;" id="balance_budget_a" readonly value="<?php echo $balance_budget; ?>" >
				</div>
			</div>
		</div>	
<?php 				
    }

	if(isset($_POST['sub27'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
// 		$trans_type			= $_POST['trans_type'];
		$doc_type			= $_POST['doc_type'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
 		$sql = " SELECT * FROM sma_workflow 
					where 1 and doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' ";
				// 	and trans_type = '$trans_type' ";
//echo $sql;					
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];

		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
		}
		if($approval_role_4>0){
			$row_affected = $row_affected + 1;
			$required4 = 'REQUIRED';
		}
		if($approval_role_5>0){
			$row_affected = $row_affected + 1;
			$required5 = 'REQUIRED';
		}
		if($approval_role_6>0){
			$row_affected = $row_affected + 1;
			$required6 = 'REQUIRED';
		}
		if($approval_role_7>0){
			$row_affected = $row_affected + 1;
			$required7 = 'REQUIRED';
		}
		if($approval_role_8>0){
			$row_affected = $row_affected + 1;
			$required8 = 'REQUIRED';
		}

?>    
		<div class="box-footer">
						<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_1 FROM 
											sma_workflow  
											where 1 and active = '1' and doc_type = '$doc_type' 
												and '$checker_value' >= from_value and '$checker_value' <= to_value 
													and company_id = '$company_id' 
													and approval_role_1 >0 ) ";
											$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>
									<select class="form-control  approver_1" id="APPROVER_1" name="approver_1" required <?= $required1; ?> >
								<?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
								<?php	} ?>
										
										<?php
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php 	$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_2 FROM 
											sma_workflow  
											where 1 and active = '1' and doc_type = '$doc_type' 
												and '$checker_value' >= from_value and '$checker_value' <= to_value 
													and company_id = '$company_id' 
													and approval_role_2 >0 ) ";
											$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
									
							?>
									<select class="form-control  approver_2" id="APPROVER_2"  name="approver_2" <?= $required2; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php
									$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_3 FROM 
											sma_workflow  
											where 1 and active = '1' and doc_type = '$doc_type' 
												and '$checker_value' >= from_value and '$checker_value' <= to_value 
													and company_id = '$company_id' 
													and approval_role_3 >0 ) ";
											$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}			
							?>					
									<select class="form-control  approver_3" id="APPROVER_3"  name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
									<?php
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_4>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
								<?php 
									$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_4 
												FROM sma_workflow a , sma_workflow_type b 
											where 1 and active = '1' and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_4 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
									echo mysqli_error($con);
									$selected1='';
									if($single_user==1){
										$selected1 = 'SELECTED';
									}
								?>				
									<select class="form-control  approver_4" id="APPROVER_4"  name="approver_4" <?= $required4; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						
						<?php if($approval_role_5>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
								<?php 
									$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_5
												FROM sma_workflow a , sma_workflow_type b 
											where 1  and active = '1' and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_5 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
									echo mysqli_error($con);
									$selected1='';
									if($single_user==1){
										$selected1 = 'SELECTED';
									}
								?>				
									<select class="form-control  approver_5" id="APPROVER_5"  name="approver_5" <?= $required5; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						
						<?php if($approval_role_6>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
								<?php 
									$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_6 
												FROM sma_workflow a , sma_workflow_type b 
											where 1  and active = '1' and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_6 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
									echo mysqli_error($con);
									$selected1='';
									if($single_user==1){
										$selected1 = 'SELECTED';
									}
								?>				
									<select class="form-control  approver_6" id="APPROVER_6"  name="approver_6" <?= $required6; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						
						<?php if($approval_role_7>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
								<?php 
									$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_7 
												FROM sma_workflow a , sma_workflow_type b 
											where 1  and active = '1' and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_7 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
									echo mysqli_error($con);
									$selected1='';
									if($single_user==1){
										$selected1 = 'SELECTED';
									}
								?>				
									<select class="form-control  approver_7" id="APPROVER_7"  name="approver_7" <?= $required7; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						
						<?php if($approval_role_8>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
								<?php 
									$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_8 
												FROM sma_workflow a , sma_workflow_type b 
											where 1  and active = '1' and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_8 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
									echo mysqli_error($con);
									$selected1='';
									if($single_user==1){
										$selected1 = 'SELECTED';
									}
								?>				
									<select class="form-control  approver_8" id="APPROVER_8"  name="approver_8" <?= $required8; ?> >
                                        <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
										<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						
						
								<div class="col-sm-1">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getvalidate();getsubmit();" >
								</div>
							
						</div>
						<br>
<?php						
	
	}

?>


<?php
	if(isset($_POST['sub35'])){
		$modulePath = "travel_approval/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$re_id		 		= $_POST['re_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];
		
		
		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page					= $_POST['page']; 		
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
		VALUES ( '$re_id', '$doc_type', '$comment_type', " . '"'. $comment . '"'. ", '$userid', now())";
		mysqli_query($con, $sql);
		
		if($doc_type =='CE'){
			$doc_type = 'Company Expense';
			$baseurl .=$modulePath.'company_expense.php?sub=edit&id='.$re_id.'&page='.$page.'&active8=active';
			$table_name = 'sma_travel_expenses';
		}
		else if($doc_type =='RE'){
			$doc_type = 'Regular Expense';
			$baseurl .=$modulePath.'regular_expense.php?sub=edit&id='.$re_id.'&page='.$page.'&active8=active';
			$table_name = 'sma_travel_expenses';
		}
		else if($doc_type =='TE'){
			$doc_type = 'Travel Expense';
			$baseurl .=$modulePath.'travel_expence.php?sub=edit&id='.$re_id.'&page='.$page.'&active8=active';
			$table_name = 'sma_travel_expenses';
		}
		else if($doc_type =='TA'){
			$doc_type = 'Travel Request';
			$baseurl .=$modulePath.'traval_app.php?sub=edit&id='.$re_id.'&page='.$page.'&active8=active';
			$table_name = 'sma_traval_approval';
		}
		
		$sql  = "SELECT * FROM $table_name where id = '$re_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];				
//exit();
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$approver_id 			= $r2['id'];
		include("comment_mail.php");
		
		if(!empty($approver_1_status)){
			$approver_id		= $rw['approver_1'];	
			include("comment_mail.php");
		}		
		if(!empty($approver_2_status)){
			$approver_id		= $rw['approver_2'];	
			include("comment_mail.php");
		}
		if(!empty($approver_3_status)){
			$approver_id		= $rw['approver_3'];	
			include("comment_mail.php");
		}
		if(!empty($approver_4_status)){
			$approver_id		= $rw['approver_4'];	
			include("comment_mail.php");
		}
		if(!empty($approver_5_status)){
			$approver_id		= $rw['approver_5'];	
			include("comment_mail.php");
		}
		if(!empty($approver_6_status)){
			$approver_id		= $rw['approver_6'];	
			include("comment_mail.php");
		}
		if(!empty($approver_7_status)){
			$approver_id		= $rw['approver_7'];	
			include("comment_mail.php");
		}
		if(!empty($approver_8_status)){
			$approver_id		= $rw['approver_8'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
		
		exit();
		
} ?>

<?php
    if(isset($_POST['sub36'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
?>		
		
		<select class="form-control" name="expence_name" id="expence_Name" required autocomplete="off" onchange="getcatbudget(this.value)"; >
			<option value=""> Select </option>
			<?php 
			$sql = "SELECT a.* from sma_product a, sma_budget_subgroup b where b.id = a.budget_head and a.product_group = '$id' order by `name` ";
			$q2  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
			?>
			<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['name'] ?> </option>	
			<?php } ?>
		</select>
										
<?php
    }

if(isset($_POST['sub366'])){
    
        $id = $_POST['id'];
		//$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
?>		
		<select class="form-control" name="reference" id="reference_A" autocomplete="off" onchange="getcatbudgetA(this.value)" >
			<option value=""> Select </option>
		<?php 
		    $sql = "SELECT a.* from sma_product a, sma_budget_subgroup b where b.id = a.budget_head and a.product_group = '$id' order by `name` ";
			$q2  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
		?>
			<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['name'] ?> </option>	
			<?php } ?>
		</select>
<?php											
     //   echo $value;

    }

	if(isset($_POST['sub34'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
 		$docs_type 			= $_POST['docs_type'];
		$onbehalf_emp_id	= $_POST['onbehalf_emp_id'];
		
		//$sql = " SELECT * FROM sma_workflow 
		//			WHERE 1 and doc_type = 'TA' 
		//			AND company_id = '$company_id' ";
		$sqlz = '';
		//if($docs_type!='TE' && $docs_type!='TA' && $docs_type!='RE'){
		//$sqlz = " and '$checker_value' >= from_value and '$checker_value' <= to_value  ";
		//}
		
//$sqlv = " and id != '$userid' ";
		
		if( $docs_type=='TA'){
			$sqlz='';
			$sqlv='';
		}
		else {
			$sqlz = " and '$checker_value' >= from_value and '$checker_value' <= to_value  ";
		}
			
		if( $docs_type=='RE'){
			$sqlz .= " and trans_type = '$trans_type' ";
		}
		$sql = " SELECT * FROM sma_workflow 
					WHERE 1 ". $sqlz ."  and doc_type = '$docs_type' 
					AND company_id = '$company_id' ";			
//echo $sql . ' ' . $sqlz;
//and '$checker_value' >= from_value and '$checker_value' <= to_value and trans_type = '$trans_type' 		
//echo $sql. "<BR>";					
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];
		
		
		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
//echo $approval_role_2 . '=='. $primary_role;	
//if( $approval_role_3 == $primary_role ){
//				$approval_role_3 = 0;
//			}
			
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
			
				
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
			
		}
		if($approval_role_4>0){
			$row_affected = $row_affected + 1;
			$required4 = 'REQUIRED';
		}
		if($approval_role_5>0){
			$row_affected = $row_affected + 1;
			$required5 = 'REQUIRED';
		}
		if($approval_role_6>0){
			$row_affected = $row_affected + 1;
			$required6 = 'REQUIRED';
		}
		if($approval_role_7>0){
			$row_affected = $row_affected + 1;
			$required7 = 'REQUIRED';
		}
		if($approval_role_8>0){
			$row_affected = $row_affected + 1;
			$required8 = 'REQUIRED';
		}

?>    
		<div class="box-footer">
					
					<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
					
								<div class="col-sm-1">
									<label class="control-label"><span style="color:red;">**</span></label>
								</div>
								
						<?php 
						
						if( $docs_type=='TA'){
						?>	
							<div class="col-sm-3">
									<label class="control-label">Approver 1.</label>
							<?php 		
								
								$sql = " select * from sma_user where 1 and active =1 and id in ( select level_1 from sma_user 
												WHERE id = '$onbehalf_emp_id' ) ";			
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php		
							if($approval_role_1>0){ ?>	
						
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php 		
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_1) FROM sma_workflow 
												where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_1 >0 ), role ) 
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) "; 
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_2" name="approver_2" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						
						<?php 
						}	
						else if( $docs_type != 'TA'){
							if($approval_role_1>0){ ?>	
						
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
							<?php 		
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_1) FROM sma_workflow 
												where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_1 >0 ), role ) 
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) "; 
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php

								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_2) FROM sma_workflow 
											where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_2 >0 ), role ) 
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) ";
										
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}	
							?>			
									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_3) FROM sma_workflow 
											where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_3 >0 ), role )
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_3" name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_4>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_4) FROM sma_workflow 
											where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_4 >0 ), role ) 
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>			
								
									<select class="form-control  approver_4" name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_5) FROM sma_workflow 
											where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_5 >0 ), role ) 
											$sqlv 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>			
								
									<select class="form-control  approver_5" name="approver_5" <?= $required5; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_6) FROM sma_workflow 
											where 1 $sqlz and doc_type = '$docs_type' and company_id = '$company_id' and approval_role_6 >0 ), role ) 
											$sqlv  
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>			
								
									<select class="form-control  approver_6" name="approver_6" <?= $required6; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } 
						
						}
						
						?>
						
						<?php if($row_affected > 0){ ?>
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getsubmit();" >
								</div>
						<?php } ?>		
						</div>
						<br>
<?php						
	
	}


    if(isset($_POST['sub37'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
?>
		
		<select class="form-control" name="location" id="location" required >
			<option value=""> Select</option>
			<?php $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['loc_name'];?></option>
			<?php } ?>
		</select>
<?php

    }


   if(isset($_POST['sub38'])){
    
        $company_id = $_POST['id'];
		$doc_type	= $_POST['doc_type'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	
?>
		
		<select class="form-control select3" name="trans_type" id="trans_type" required >
            <option value=""> Select </option>
			<?php $sql = "SELECT DISTINCT(b.id), b.workflow_type FROM `sma_workflow` a, sma_workflow_type b where b.id = a.trans_Type and company_id = '$company_id' and b.status = 'Y' and a.doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>
<?php

    }

	if(isset($_POST['sub39'])){
		$modulePath = "supp_invoice/";
		
		//$userid   			= $_SESSION['usrid'];
		$doctype 			= $_POST['doctype'];
		$doctypeid 			= $_POST['doctypeid'];
		
		$sql  = "UPDATE file_uploads  set doc_type = '$doctype' WHERE 1 and id = '$doctypeid' "; //module = 'SI' AND
		$query= mysqli_query($con, $sql);
		
	}

	function create_tallyjv($re_id, $doc_type){
		
		include('../dbcon.php');
		
		$modulePath = "travel_approval/";
		//$re_id = $_POST['re_id'];
		
		//$doc_type 		= $_POST['doc_type'];
		if($doc_type=='CE' ){
			$exp_type ='C';
		}	
		else if($doc_type=='RE' ){
			$exp_type ='R';
		}
		else if($doc_type=='TE' ){
			$exp_type ='T';
		}
		
		$tally_narration_v ='';
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
		
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
		}	
		
		$sql 	= "select * from sma_travel_expenses where exp_type = '$exp_type' and id = '$re_id' ";
//echo $sql. "<BR>";		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		$status 		= $r2['status'];
		//$supplier_id 	= $r2['emp_id'];
		$supplier_id 	= $r2['onbehalf_emp_id'];
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		//$tally_narration 	 = $r2['tally_narration'];
		$remarks			 = $r2['remarks'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE a.exp_type = '$exp_type' AND a.approval_ref_no = '$re_id'  ";
		$q2 	= mysqli_query($con, $sql);
		while($r2 	= mysqli_fetch_array($q2)){
			$note 	= $r2['note'];
			if(!empty($note)){
				$tally_narration .= $note;
			}
		}
		
		$tally_narration_v = " Being amount paid against " . $tally_narration;
		
		//$invoice_no = $r2['invoice_no'];
		
		if($doc_type=='CE' ){
			$sql = "select * from sma_party_mst where id = '$supplier_id' ";
//	echo $sql. "<BR>";	exit();
				
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			$account_id = $r2['id'];
			$account_type_a	= 'V';
			$val_type_a 	= 'V';
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
		}
		else if($doc_type=='RE' || $doc_type=='TE' ){
			$sql = "select * from sma_user where id = '$supplier_id' ";
//echo $sql. "<BR>";			
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name = $r2['username'];
			$account_id = $r2['id'];
			$account_type_a = 'U';
			$val_type_a 	= 'V';
			$gst_no		= '';
			$state		= '';
			$address	= '';
			$mobile_no		= '';
			$pan_no			= '';
		}
		
		$tot_amount = 0;
		$prev_budget_head = '';
		$prev_account_name = '';
		
		$sql = " SELECT c.budget_code as account_name, b.id as account_id, 'A' as account_type, a.amount, a.invoice_no, a.dated, a.budget_id, c.budget_head, c.budget_name 
			FROM `sma_expenses` a, sma_product b, sma_budget c 
				WHERE a.exp_type = '$exp_type' and b.id = a.reference 
					AND a.approval_ref_no = '$re_id' and c.id = a.budget_id 
					AND d.product_id = a.reference and c.project = '$company_id'
					ORDER BY b.name, c.budget_name ";

//echo $sql. "<BR>"; exit();

		$result1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result1)){
			
			$amount 			= $row['amount'];
			
			$account_name 		= $row['account_name'];
			$account_type		= $row['account_type'];
			$budget_head 		= $row['budget_head'];
			$budget_code 		= $row['account_name'];
			$tally_narration_v .=  $account_name . ', ';
			$budget_id 			= $row['budget_id'];
			$invoice_no     	= $row['invoice_no'];
			$dated				= $row['dated'];
			$account_id  		= $row['account_id'];
			
			$sql = "select * from sma_budget_subgroup where id = '$budget_head' ";
			$qr2 =	mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($qr2);
			$budget_head 		= $r2['budget_head'];
				
			$tot_amount = $tot_amount + $amount;
			$effect 			= "Dr";
			$record_type 		= "Purchase-P2P";
			$doc_no				= $re_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			//$invoice_no			= $invoice_no;
			$invoice_date		= date('d-m-Y', strtotime($dated));
			
			$effect				= $effect;
			$amount				= $amount;
			//$narration			= $narration;
			$cheque_no			= '';
			
			
			if( $prev_account_name == $account_name ){
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. "<BR>";
			}
			else {
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$budget_code', '$budget_head',  '$effect', '$amount', '$tally_narration_v', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no') ";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
			}
			
			$prev_account_name = $account_name;
			$prev_budget_head  = $budget_head;
			$budget_code_prev	= $budget_code;
			
//echo $sql."<BR>";			exit();
	
		}

		$fare  		= 0;
 
		$record_type 		= "Purchase-P2P";
		$doc_no				= $re_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		//$invoice_no			= $invoice_no;
		$invoice_date		= date('d-m-Y', strtotime($invoice_date));
		
		$account_type_a	    = 'V';
		$val_type_a 	    = 'V';
		
		if( $doc_type=='RE' || $doc_type=='TE' ){
			$account_type_a	    = 'U';
		}
		
		$account_type		= $account_type_a;
		$val_type			= $val_type_a;
		
		$account_id			= $supplier_id;
		$account_name       = $party_name;
		$effect				= 'Cr';
		$amount				= $tot_amount + $fare;
		//$narration			= $narration;
		$cheque_no			= '';
		 
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$val_type', '$account_id', '$account_name', '$budget_head', '$effect', '$amount', '$tally_narration_v', '$cheque_no', '$address', '$gst_no', '$state', '$company_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		//$value = "<script>window.location.href='company_expense.php?sub=edit&id=$re_id&active5=active';</script>";
		
		$tally_narration_v .=  $remarks;

		if($status == 'Completed' ){
			
			$sql = " update sma_travel_expenses set tally_narration = '$tally_narration_v', tally_status='C', tally_updated_on = now() , tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
			mysqli_query($con, $sql);
			
			$sql = " UPDATE tally_journal_entry set narration = '$tally_narration_v', status = 'C' where doc_type = '$doc_type' and doc_no = '$re_id' ";
			mysqli_query($con, $sql);
			
		}
		else {
			
			$sql = " update sma_travel_expenses set tally_status='R', tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
			mysqli_query($con, $sql);

		}
		
	}	

    if(isset($_POST['sub40'])){
    
        $onbehalf_emp_id = $_POST['onbehalf_emp_id'];
		$dated 			 = date('Y-m-d', strtotime($_POST['dated']));

		$mobile_value = 0;	
 		$sql = "SELECT sum(b.amount) as mobile_value  from sma_travel_expenses a, sma_expenses b 
										where 1 and a.exp_type = 'R' and a.id = b.approval_ref_no and a.onbehalf_emp_id = '$onbehalf_emp_id' 
											and year(b.dated) = year('$dated') and month(b.dated) = month('$dated') ";	
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$mobile_value	= $r2['mobile_value'];
			if(empty($mobile_value)){
				$mobile_value = 0;	
			}
			
			echo $mobile_value.'##';
?>
<!--<input type='hidden' class='mobile_value' value='<?= $mobile_value;?>'>-->

<?php			
			
	
	}
	
	if(isset($_POST['sub41'])){
		
		$company_id = $_POST['id'];
    
		$rowcnt = 0;
		$selected = "";
 		$sql = "SELECT DISTINCT(a.id) as id, b.id as budget_id, a.budget_name, a.budget_head, a.budget_code 
				FROM `sma_budget_subgroup` a, sma_budget b 
				WHERE a.id = b.budget_head  and a.travel_module = 'Y' and b.project = '$company_id' and b.account_year = '$short_fy_code' order by budget_head ";
		$res 	= mysqli_query($con, $sql);
		$rowcnt = mysqli_affected_rows($con);
		if($rowcnt ==1){
			
			$selected   = "SELECTED";
			
		}
		if($rowcnt ==0){
			echo $valid_flag = "NO## <span style='color:red;'><b>Travel Expense Budget not available !!! </b></span>";	
		}
		else {	
			
?>	
			<select class="form-control" name="budget_head" required onchange="getBudgetCheck(this.value);" >
				<option value=""> Select</option>
				<?php $sql = "SELECT DISTINCT(a.id) as id, b.id as budget_id, a.budget_name, a.budget_head, a.budget_code FROM `sma_budget_subgroup` a, sma_budget b where a.id = b.budget_head  and a.travel_module = 'Y' and b.project = '$company_id' and b.account_year = '$short_fy_code' order by budget_head ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['budget_id'];?>" <?=  $selected;?> ><?php echo $r2['budget_head'];?></option>
				<?php } ?>
			</select>
			##
<?php

			if($rowcnt ==1){
				$r2 = mysqli_fetch_array($res);
				$budget_id	= $r2['budget_id'];
				$selected   = "SELECTED";
				echo '<span id="getBudgetCode_a">';
				include("po_budget_check_routine.php");
				echo '</span>';
				
			}
		}
	
	}
	
	if(isset($_POST['sub42'])){
		$budget_id = $_POST['id'];
 // echo   $budget_id. "<BR>";
		include("po_budget_check_routine.php");
		
		
	}
	
	if(isset($_POST['sub43'])){
		
		//$budget_id = $_POST['id'];
 		
?>			
		<div class="form-group">
		<label for="approver" class="col-md-1 control-label">Vendor</label>		
		<div class="col-md-8">
		<select class="form-control" name="vendor_id" id="vendor_id" required >
			<option value=""> Select </option>
		<?php 
			$sql = "SELECT * from sma_party_mst where 1 order by trim(party_name) ";
			$q2  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
		?>
			<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['party_name'] ?> </option>	
			<?php } ?>
		</select>
		</div>
		</div>

<?php		
	}
		
?>
