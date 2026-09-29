<?php

	include "../dbcon.php";
	
	$sql = "SELECT * from sma_product_open_stock where 1 and (opening_stock + receipts) - issue < 0 ";
	//$sql = "SELECT * from sma_product_open_stock where 1 and issue < 0 ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$rid = $row['id'];
		
		$company_id = $row['project'];
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		//echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$comp_name 	= $r2['comp_name'];
		$project 	= $r2['comp_code'];

		$product_id 	= $row['product_name'];
		
		$opening_stock 	= $row['opening_stock'];
		$receipts 		= $row['receipts'];
		$issue			= $row['issue'];
		
		$sql = " SELECT sum(b.qty) as qty FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
					and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id 
					AND b.material_id = '$product_id'
					AND a.company_id   = '$company_id' ";
//echo $sql ."<BR>";						
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$receipts_qty 	= $r2['qty'];

	//echo $receipts_qty ." ###1<BR>";
	
			$sql = " UPDATE sma_product_open_stock SET receipts = '$receipts_qty' where id = '$rid' ";
	//echo $sql ."<BR>";		
			mysqli_query($con, $sql);
			$receipts 		= $receipts_qty;
		
		$sql = " SELECT sum(b.receipt_qty) as receipt_qty FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
					and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id 
					AND b.product_id = '$product_id'
					AND a.company_id   = '$company_id' ";	
//echo $sql ."<BR>";			
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$receipt_qty 	= $r2['receipt_qty'];
	//echo $receipt_qty ." ###2<BR>";	
		
			$sql = " UPDATE sma_product_open_stock SET receipts = receipts + '$receipt_qty' where id = '$rid' ";
			mysqli_query($con, $sql);
			$receipts 		= $receipts + $receipt_qty;
		
		$sql = " SELECT sum(b.issue_qty) as issue_qty FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
					and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id 
					AND b.product_id = '$product_id'
					AND a.company_id   = '$company_id' ";	
//echo $sql ."<BR>";			
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$issue_qty 	= $r2['issue_qty'];
	//echo $issue_qty. " ###3<BR>";
		
			$sql = " UPDATE sma_product_open_stock SET issue = '$issue_qty' where id = '$rid' ";
			mysqli_query($con, $sql);
			$issue 		= $receipts_qty;
		
	//	exit();
			
	}
	

	$sql = " UPDATE sma_product_open_stock SET opening_stock =  ((opening_stock + receipts) - issue) * -1 WHERE 1 and (opening_stock + receipts) - issue < 0 and opening_Stock =0;";
	mysqli_query($con, $sql);
	
?>

