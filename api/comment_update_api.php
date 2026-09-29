<?php

	include "../baseurl.php";
	include "DbConnect.php";
	
	$id_parameter   = $_POST['id_parameter'];
	$module 		= trim($_POST['slug']);
	$userid 		= $_POST['userid'];
	$userid_v 		= $_POST['userid'];
	$comment 		= $_POST['comment'];
	
	$doc_id			= $id_parameter;
	
	$sql = " select * from sma_user where userid = '$userid' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$usr_id 			= $r2['id'];

	require '../PHPMailer-master/PHPMailerAutoload.php';
	
	$comment_type	= 'C';	
	if($module=='Purchase Order'){
		$modulepath 	= 'purchase_order/';	
		$doc_type		= 'PO';
	}
	else if($module=='Supplier Invoice'){
		$doc_type		= 'SI';	
	}
	else if($module=='Material Req.'){
		$doc_type		= 'PR';	
	}
	else if($module=='Goods Received Note'){
		$doc_type		= 'GR';	
	}
	else if($module=='NOA'){
		$doc_type		= 'AP';	
	}
	else if($module=='Operating Exp.'){
		$doc_type		= 'CE';	
	}
	else if($module=='Travel Exp.'){
		$doc_type		= 'TE';	
	}
	else if($module=='Travel Approval'){
		$doc_type		= 'TA';	
	}
	else if($module=='Reimbursement'){
		$doc_type		= 'RE';	
	}
	else if($module=='Payments'){
		$doc_type		= 'PY';	
	}
	
			$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
						VALUES ( '$id_parameter', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$usr_id', now() )";
			mysqli_query($con, $sql);
			
			$sql  = "SELECT * FROM sma_purchase_order where id = '$id_parameter' ";
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
				
		$response['status'] = '1'; 
		$response['message'] = 'Comment added successfully'; 
		$response['userid'] = $userid_v;
		$response['slug']  = trim($_POST['slug']);	
		//$response['data'] = $data;
	
	echo json_encode($response);	
	
?>	