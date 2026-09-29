<?php 
	
	session_start();
	include('../dbcon.php');

		$modulePath = "purchase_requisition/"; 
	//$baseurl = "http://localhost:80/hc_template/";    //dev url
	
	include "../baseurl.php";
	
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
?>

<?php

	if(isset($_POST['sub1'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$product_id     	= $_POST['product_id'];
		$purchase_req_id 	= $_POST['purchase_req_id'];
		
		$description 		= $_POST['description'];
		$quantity 			= $_POST['quantity'];
		$units 				= $_POST['units'];
		
		
		if($quantity > 0){
			$sql = "SELECT * from sma_purchase_req_items where purchase_req_id = '$purchase_req_id' and product_id = '$product_id' ";
			$q2  = mysqli_query($con, $sql);
			$rowaffected = mysqli_affected_rows($con);
			$r2  = mysqli_fetch_object($q2);
			if($rowaffected == 0){
				$sql = "INSERT INTO `sma_purchase_req_items` (product_id, purchase_req_id, description, quantity, unit) 
					values ( '$product_id', '$purchase_req_id', '$description',  '$quantity', '$units')";
				mysqli_query($con, $sql);
			}
			else {
					$sql = "UPDATE `sma_purchase_req_items` set description = '$description', quantity =  '$quantity'
					 WHERE product_id = '$product_id' and purchase_req_id = '$purchase_req_id' ";
				mysqli_query($con, $sql);
			}	
		}
//echo $sql."<BR>";	
			$sql = "select * from sma_product where id = '$product_id'";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$product_name 	= $r1['name'];
											
			$sql = "select * from sma_purchase_req where id = '$purchase_req_id'";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$pr_number 	= $r1['pr_number'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $pr_number.','. $product_name. ','. $description. ',' . $quantity;
		    $affect 		= 'Product Added';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu', '$company_id', '$description','$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql."<BR>";		
//exit();

		$value = "<script>window.location.href='edit.php?id=$purchase_req_id&active=active&999';</script>";
//$value=$sql;
		echo $value;
		
	}

	if(isset($_POST['sub2'])){
	
		$value ='';
        $id = $_POST['id'];
		$poid = $_POST['poid'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql="delete from sma_purchase_req_items where id = '$id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);

		echo "<meta http-equiv='refresh' content='0'>";    
		
//$value = $value1;
		echo $value;
		
	}
	
	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$product_id     = $_POST['id'];
		$rid     		= $_POST['rid'];
		$purchase_req_id 	= $_POST['purchase_req_id'];
		$description 	= $_POST['description'];
		$quantity 		= $_POST['quantity'];
		$units 			= $_POST['units'];
		$rate 			= $_POST['rate'];
//		$amount 		= $_POST['amount'];
							
		$sql = "update `sma_purchase_req_items` set product_id='$product_id'
					description='$description', quantity='$quantity', uom='$units',
					unit_rate='$rate' 
				where purchase_req_id = '$purchase_req_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
	
//$value = $value1;
		echo $value;
		
	}


	if(isset($_POST['sub9'])){

		$modulePath = "purchase_requisition/";
	
		$value ='';
        $pr_id = $_POST['pr_id'];
		$srno  = $pr_id;
		if($_POST['pr_id'] == ''){$pr_id = '';}
		
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approved 			= $_POST['approved'];
		$approver 			= $_POST['approver'];

		$company			= $_POST['company'];
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_purchase_req where id = '$pr_id' "; 
//echo $sql."<BR>";			
//exit();
		$q2	=	mysqli_query($con, $sql);
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
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) && $approver_1_status == 'Submitted'){
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
		if( $approver_2== $approver && empty($approver_3) && $approver_2_status == 'Submitted'){
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
		if( $approver_3== $approver && empty($approver_4) && $approver_3_status == 'Submitted'){
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
			if( $approver_4== $approver && empty($approver_5) && $approver_4_status == 'Submitted'){
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
			if( $approver_5== $approver && empty($approver_6) && $approver_5_status == 'Submitted' ){
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
			if( $approver_6== $approver && empty($approver_7) && $approver_6_status == 'Submitted' ){
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
			if( $approver_7== $approver && empty($approver_8) && $approver_7_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			/* if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			} */
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $approver_9;
				$status_field_from = 'approver_8_status';
				$status_field	 = 'approver_9_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_8== $approver && empty($approver_9) && $approver_8_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_9== $approver && $approver_9_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_9_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
			
//echo $approval_status."<BR>"; 

//exit();
		
	if($statusap=='Reject'){
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "update sma_purchase_req set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = approver_9 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',approver_9_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$pr_id' ";
//echo $sql. "<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
				
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'PR', '$pr_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
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
		$sql = "update sma_purchase_req set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$pr_id'";
//echo $sql." ##1<BR>";		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'PR', '$pr_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}

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
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		 $baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pr_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
	/*	
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$pr_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$pr_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>'; */

		include "pr_mail.php";
		
		$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		//$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}


?>		