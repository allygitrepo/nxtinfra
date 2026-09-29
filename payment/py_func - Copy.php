<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	
?>

<?php
		
	if(isset($_POST['sub2'])){
	
		$value ='';

		$payment_hdr_id 	= $_POST['payment_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_name 		= $_POST['account_name'];
		$against_invoice    = $_POST['against_invoice'];
        $invoice_number     = $_POST['invoice_number'];
		$debit_credit    	= $_POST['debit_credit'];
		$amount    			= $_POST['amount'];
		$remarks 			= $_POST['remarks'];
		
		$sql = "insert into `payment_details` (payment_hdr_id, account_type, account_name, against_invoice, invoice_number, debit_credit, amount, remarks ) 
		values ( '$payment_hdr_id', '$account_type', '$account_name', '$against_invoice', '$invoice_number', '$debit_credit', '$amount', '$remarks')";

		$r2 = mysqli_query($con, $sql);
		
		$file = fopen("ravitest.txt","w");
		fwrite($file,$sql);
		fclose($file);

//		$value .= $sql;
			
//$value = $value1;
		$value = "<script>window.location.href='edit.php?sub=edit&id=$payment_hdr_id&active=active';</script>";
	
		echo $value;
				
	}

	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		$py_id = $_POST['py_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from payment_details where id = '$id' ";

		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//$value = $value1;
		echo "<meta http-equiv='refresh' content='0'>";    
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$py_id&active=active&123';</script>";
		echo $value;
		
	}



	if(isset($_POST['sub5'])){
	
		$value ='';
        $id = $_POST['id'];		
		if($_POST['id'] == ''){$id = '';}
		
		$baseurl1 = $baseurl . "supplier_invoice/edit.php?sub=edit&id=$id";
		$value = "<script>window.location.href='$baseurl1';</script>";
		echo $value;
		
	}
	

	if(isset($_POST['sub8'])){
	
		$modulePath = "payment/"; 
	
		$value ='';
        $py_id 			= $_POST['py_id'];
		$supp_id 		= $_POST['supp_id'];
		$srno  			= $py_id;
		if($_POST['py_id'] == ''){$py_id = '';}		
		$mode		 	= $_POST['mode'];
		$approver 		= $_POST['approver'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		$paid_to 		= $_POST['paid_to'];
		$cheque_no		= $_POST['cheque_no'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		if ($status =='Draft'){
		
			//$status = 'Submited';
			$status = 'Verified';
			$approval_status = 'Pending';
			
		}
		else if ($status =='Submited'){
			if($mode =='Accept'){
				$status = 'Completed';
				$approval_status = 'Approved';
			}
			else if($mode =='Reject'){
				$status = 'Draft';
				$approval_status = 'Rejected';
			}
		}
	
		$sql = "update payment_header set approval_status	= '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id = '$py_id'";

		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		if($mode =='Reject'){
			$sql = "update sma_supplier_invoice set approval_status	= '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id = '$supp_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date) 
									values('PY', '$py_id', '$userid', now(), '$status', '$approver', '$approved', '$remarks', now() )";
		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$s="select * from sma_user where id='$approver' ";
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->first_name . ' ' . $r->last_name;
		}
	
		$party_name  = '';
		$party_email = '';
		if($status == 'Completed' && !empty($cheque_no) ){
			$sql="SELECT * FROM sma_party_mst where id ='$paid_to' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$party_name  = $r2['party_name'];
			$party_email = $r2['party_email'];
		}
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
		$msg = 'Payment Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");
		include "py_mail.php";

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
	
	}
?>		

