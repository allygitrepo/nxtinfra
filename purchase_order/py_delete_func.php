<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "purchase_order/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$po_id  		= $_POST['po_id'];
		
		if($_GET['po_id']){
			$po_id  		= $_GET['po_id'];
		}
		
		$sql = " select * from sma_purchase_order where id = '$po_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$status		 		= $r2['status'];
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		$supplier_id		= $r2['to_supplier'];
		$approval_memo_ref	= $r2['approval_memo_ref'];

		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
	$sql="SELECT * from sma_po_items where 1 and quantity > 0 and purchase_id = '$po_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$product_id 	= $row['product_id'];
		$pr_item_id 	= $row['pr_item_id'];
		$po_item_id 	= $row['id'];
		$qty 			= $row['quantity'];
		$budget_head	= $row['budget_head'];
		$budget_id		= $row['budget_id'];
											
												
		$rate 			= $row['unit_rate'];
		$gst			= $row['gst'];
		
		$gstamt = round((($qty * $rate) * $gst / 100),0);
		
		$po_amount 		= round($qty * $rate + $gstamt,0);

        $sql = " UPDATE sma_approval_items SET `po_quantity` = po_quantity - '$qty', `po_value` = '0', po_no = 0 WHERE 1 and id = '$pr_item_id' and approval_hdr_id = '$approval_memo_ref' ";
		mysqli_query($con, $sql);
// $file = fopen("ravitest.txt","a");
// fwrite($file,$sql);
// fclose($file);

		$sql = "SELECT * from sma_product where 1 and id = '$product_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$category = $r2->category;
		
//For PO cum Approval budget revert	
	//	if($status=='Submitted' || $status=='Completed'){
			
			$sql = " UPDATE sma_budget SET blocked_budget = blocked_budget - $po_amount WHERE id = '$budget_id' ";
			mysqli_query($con, $sql);
			$sql = "select * sma_budget where id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$rw1 = mysqli_fetch_array($res);
			$blocked_budget		= $rw1['blocked_budget'];
			
			$sql = " UPDATE sma_budget SET blocked_budget = 0 WHERE id = '$budget_id' AND blocked_budget<0 ";
			mysqli_query($con, $sql);
			
	//	}	
			
// 		if($category=='S'){	
// 			$sql = "update sma_purchase_req_items set po_no ='', po_quantity = 1, po_value = po_value - $po_amount  where id = '$pr_item_id' ";
// 			mysqli_query($con, $sql);
// 			echo mysqli_error($con);
// 		}
// 		else {	
// 			$sql = "update sma_purchase_req_items set po_no ='', po_quantity = po_quantity - $qty, po_value = po_value - $po_amount  where id = '$pr_item_id' ";
// 			mysqli_query($con, $sql);
// 			echo mysqli_error($con);
// 		}	
//echo $sql;			
// 		$sql = "update sma_purchase_req_items set po_quantity = 0, po_value = 0 where po_quantity < 0 and id = '$pr_item_id' ";
// 		mysqli_query($con, $sql);
// 		echo mysqli_error($con);

	}

	$sql = "update sma_purchase_order set del = 'Y', status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', 
					approver_5	= '', approver_6 = '', approver_7 = '', approver_8 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', 
					approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', 
					current_approver= ''  where id = '$po_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
				values( 'PO', '$po_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit(" Delete ...");}
//exit();
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject;
		    $affect 		= 'Deleted';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



