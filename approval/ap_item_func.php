<?php 
	
	session_start();
	include('../dbcon.php');

	$modulePath = "approval/";
	
	$finance_year = $_SESSION['finance_year'];
	
?>

<?php

	if(isset($_POST['sub1'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$product_id     = $_POST['id'];
		$approval_hdr_id = $_POST['approval_hdr_id'];
		$name 			= $_POST['name'];
//      $catname 		= $_POST['catname'];
		$catid	 		= $_POST['catid'];   
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		
		$total_budget   = $_POST['total_budget'];
		$balance_budget = $_POST['balance_budget'];
		$budget_id    	= $_POST['budget_id'];
		
		$description 	= $_POST['description'];
		$quantity 		= $_POST['quantity'];
		$units 			= $_POST['units'];
		$rate 			= $_POST['rate'];
		$gst 			= $_POST['gst'];
		$amount 		= $_POST['amount'];
		$itemtds_id		= $_POST['itemtds_id'];
		$supplier_id	= $_POST['supplier_id'];
		//$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));
		
		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name 	= $r1['name'];
		$category 		= $r1['category'];
		$units 		    = $r1['uom'];
		
		$sql = "SELECT * FROM account_mst where id = '$itemtds_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$tds = $r2['percentage'];
		
		$amount = round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
		
		$sql = "insert into `sma_approval_items` (approval_hdr_id, product_id, product_name, product_desc, product_category, company_id, budget_name, budget_head, quantity, uom, unit_rate, gst, total_budget, balance_budget, budget_id, supplier_id, amount, tds, tds_id)
			values ('$approval_hdr_id', '$product_id', '$name', '$description', '$catid', '$company_id', '$budget_name', '$budget_head', '$quantity', '$units', '$rate', '$gst', '$total_budget', '$balance_budget', '$budget_id', '$supplier_id', '$amount', '$tds', '$itemtds_id'  )";
//echo $sql;			
//$value=$sql;	
		    mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		if($budget_control_gst =='Y'){
			$amount = round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
		}
		else {
			$amount = round($quantity	* $rate,0);
		}
		
		$sql = " update sma_budget set blocked_budget = blocked_budget + $amount where id = '$budget_id' ";
		$q3  = mysqli_query($con, $sql);
		
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $approval_hdr_id. ','. $dated. ','. $product_name. ','. $quantity;
		    $affect 		= 'Add Product';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name', NOW(), '$main_menu', '$sub_menu', '$company_id', '$description', '$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql."<BR>";		
//exit();
//$value = $value1;
//		echo "<meta http-equiv='refresh' content='0'>";    
		//$value = 
//exit();
		$value = "<script>window.location.href='edit.php?id=$approval_hdr_id&active2=active&991';</script>";
		echo $value;
		
	}

	if(isset($_POST['sub2'])){
	
		$value ='';
        $id = $_POST['id'];
		$poid = $_POST['poid'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql="delete from sma_approval_items where id = '$id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);

		echo "<meta http-equiv='refresh' content='0'>";    
		
//$value = $value1;
		echo $value;
		
	}

?>		