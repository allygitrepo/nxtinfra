<?php

include "../dbcon.php";

	$next_fin_year = '2022-2023';
	$sql = " SELECT a.project, a.dated, b.* FROM `sma_purchase_order` a, sma_po_items b WHERE 1 and a.id = b.purchase_id and b.quantity > b.bal_si_qty and status in ('Completed') and a.del !='Y' and dated >= '2021-04-01' and a.id in (2985,2999,3009,3011) ";
echo $sql ."<br>";		
	$res = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($res)){
	
		$po_item_id				= $row['id'];
		$dated					= date('d-m-Y', strtotime($row['dated']));
		$project				= $row['project'];
		$purchase_id			= $row['purchase_id'];
		$product_id				= $row['product_id'];
		$product_name			= $row['product_name'];
		$product_desc			= $row['product_desc'];
		$budget_id				= $row['budget_id'];
		$quantity				= $row['quantity'];
		$unit_rate				= $row['unit_rate'];
		$gst					= $row['gst'];
		$bal_si_qty				= $row['bal_si_qty'];
		$bal_qty				= $quantity - $bal_si_qty;
		
		$tot_value = round(($bal_qty * $unit_rate) + (($bal_qty * $unit_rate) * $gst / 100),0);
		
		$sql ="SELECT * FROM `sma_budget` where id = '$budget_id' ";
echo $sql ."<br>";			
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($q2);
		$budget_head = $r2['budget_category'];
		$budget_name = $r2['budget_name'];
		$project 	 = $r2['project'];
		
		$sql = "SELECT * FROM `sma_budget` 
					WHERE budget_name 		= '$budget_name' 
						AND budget_category = '$budget_head' 
						AND project 		= '$project' 
						AND account_year	= '$next_fin_year' ";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$next_year_budget_id = $r2['id'];
echo $sql ."<br>";											
echo $next_year_budget_id. ' <BR> ' ;
//exit();

		$sql = " UPDATE sma_po_items SET budget_id = '$next_year_budget_id'  WHERE 1 and id = '$po_item_id' and purchase_id = '$purchase_id' ";
echo $sql ."<br>";		
	mysqli_query($con, $sql);
//	exit();

		$sql = " UPDATE sma_budget set blocked_budget =  blocked_budget +  '$tot_value' , budget_adjustment = budget_adjustment + '$tot_value'  where id = '$next_year_budget_id'  ";
		mysqli_query($con, $sql);
echo $sql ."<br>";	

	}	

//exit("Process OVer..");
?>

