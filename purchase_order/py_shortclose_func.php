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
		$company_id 		= $r2['project'];
		
echo		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
echo		$budget_control_gst = $r2['budget_control_gst'];
		
echo	$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
			
	while($row = mysqli_fetch_array($result)){
		
		$po_dtl_id 		= $row['id'];
		$product_id 	= $row['product_id'];
		
		$bal_si_qty 	= $row['bal_si_qty'];
		$qty 			= $row['quantity'] - $bal_si_qty;
echo $qty. "<BR>";		
		$budget_head	= $row['budget_head'];
		$budget_id		= $row['budget_id'];
												
		$rate 			= $row['unit_rate'];
		$gst			= $row['gst'];
		
		$gstamt 		= round((($qty * $rate) * $gst / 100),0);
		if($budget_control_gst!='Y'){
			$gstamt = 0;
		}
		
echo		$po_amount 		= ($qty * $rate) + $gstamt;

		if( $po_amount <= 0 ){
			$po_amount = 0;
		}

			$sql = "update sma_budget set blocked_budget = blocked_budget - $po_amount where id = '$budget_id' ";
			mysqli_query($con, $sql);
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   = $r4->blocked_budget;
			if( $block_budget < 0 ){
				$sql = "update sma_budget set block_budget = 0 where id = '$budget_id' ";
				mysqli_query($con, $sql);
			}
		
	//echo $sql. "<BR>";
	}

	    $sql 	= "update sma_purchase_order set status = 'Short Close', `approval_status` = 'Short Close' 
					where id = '$po_id' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con); 
//echo $sql. "<BR>";

		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_purchase_order where id = '$po_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'PO', '$po_id', '$userid', now(), 'Short Close', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Short Close ...");}

//echo $sql. "<BR>";
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
?>		


