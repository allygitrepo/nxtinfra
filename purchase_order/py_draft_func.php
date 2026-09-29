<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "purchase_order/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$po_id  		= $_POST['po_id'];

		$sql = " select * from sma_purchase_order where id = '$po_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$status		 		= $r2['status'];
		$company_id 		= $r2['project'];
		$po_type	 		= $r2['po_type'];
		$approval_memo_ref  = $r2['approval_memo_ref'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
	$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
			
	while($row = mysqli_fetch_array($result)){
		
		$po_dtl_id 		= $row['id'];
		$pr_item_id 		= $row['pr_item_id'];
		$product_id 	= $row['product_id'];
		$qty 			= $row['quantity'];
		$budget_head	= $row['budget_head'];
		$budget_id		= $row['budget_id'];
												
		$rate 			= $row['unit_rate'];
		$gst			= $row['gst'];
		
		$gstamt 		= round((($qty * $rate) * $gst / 100),0);
		
		$po_amount 		= $qty * $rate + $gstamt;

		$sql = "SELECT * from sma_product where 1 and id = '$product_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$category = $r2->category;
		
//Fort PO cum Approval budget revert		
//		if( $po_type=='C' ){
		if($status=='Submitted' || $status=='Completed'){
			$sql = "update sma_budget set blocked_budget = blocked_budget - $po_amount where id = '$budget_id' ";
			mysqli_query($con, $sql);
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rw1 = mysqli_fetch_array($res);
			$blocked_budget		= $rw1['blocked_budget'];
			if($blocked_budget<0){
				$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
				mysqli_query($con, $sql);
			}
		}
		
		if($category=='S'){	
			$sql = "update sma_purchase_req_items set po_no ='', po_quantity = 1, po_value = po_value - $po_amount  where id = '$pr_item_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else {	
			$sql = "update sma_purchase_req_items set po_no ='', po_quantity = po_quantity - $qty, po_value = po_value - $po_amount  where id = '$pr_item_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		
//echo $sql;			
			$sql = "update sma_purchase_req_items set po_quantity = 0, po_value = 0 where po_quantity < 0 and id = '$pr_item_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
//		}
		
	//echo $sql. "<BR>";
	}

    $sql="SELECT * FROM `sma_approval_details` where approval_hdr_id = '$approval_memo_ref' and supplier_name not in ( select supplier_name from sma_po_approval_details where po_approval_hdr_id = '$po_id' )";
	$res = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($r2 = mysqli_fetch_array($res)){
	    
	    $po_approval_hdr_id = $approval_memo_ref;
	    $supplier_name  = $r2['supplier_name'];
	    $quote_ref_no   = $r2['quote_ref_no'];
	    $vendor_selected = $r2['vendor_selected'];
	    $values         = $r2['values'];
	    $remarks        = $r2['remarks'];
	    $sql = " INSERT INTO sma_po_approval_details (po_approval_hdr_id, supplier_name, quote_ref_no, vendor_selected, `values`, remarks) 
	                VALUES ( '$po_approval_hdr_id', '$supplier_name', '$quote_ref_no', '$vendor_selected, '$values', '$remarks' ) ";
	    mysqli_query($con, $sql);             
	    
	}
//exit();

        $sql 	= "update sma_purchase_order set status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '', approver_3 = '', approver_4 = '', 
					approver_5	= '', approver_6 = '', approver_7 = '', approver_8 = '', 
					approver_1_status = '', approver_2_status = '', approver_3_status = '', approver_4_status = '', 
					approver_5_status = '', approver_6_status = '', approver_7_status = '', approver_8_status = '', 
					current_approver= '', del ='' 
					where id = '$po_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql. "<BR>";

		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_purchase_order where id = '$po_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'PO', '$po_id', '$userid', now(), 'Draft', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}

//echo $sql. "<BR>";
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


