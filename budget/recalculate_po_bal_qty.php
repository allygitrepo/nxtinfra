<?php

/* 	include "../dbcon.php";

$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
	$q3  = mysqli_query($con, $sql);
	$r3  = mysqli_fetch_array($q3);
	$from_date 		= $r3['from_date'];
	$to_date 		= $r3['to_date'];
	$account_year 	= $r3['short_fy_code']; 
	*/
	
  // PO Balance QTY Update Process Start
   	$sql = " SELECT * FROM sma_purchase_order where 1 and del !='Y' and status in ('Draft', 'Closed', 'Amend', 'Completed', 'Submitted' ) 
				AND dated >= '$from_date' AND dated <= '$to_date'  ";
	//and id in (60) and id in ( 1000 , 1065 ) and id in (641)  and id in (1263) 
//echo $sql . "<br>";	
//exit();
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$po_id		 	= $row['id'];
		
		$sql = " UPDATE sma_po_items SET bal_si_qty = 0, bal_si_amount = 0 WHERE purchase_id = '$po_id' ";
//echo $sql . "<br>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		$sql = " SELECT * FROM `sma_po_items` where purchase_id = '$po_id' ";
//echo $sql . "<br>";	
		$respo = mysqli_query($con,$sql);
		echo mysqli_error($con);
		while($pod = mysqli_fetch_array($respo)){	
			
			$po_item_id	 		= $pod['id'];
			$product_name 		= $pod['product_name'];
			$product_id			= $pod['product_id'];
			$bal_si_qty			= $pod['bal_si_qty'];
			$bal_si_amount		= $pod['bal_si_amount'];
			$quantity			= $pod['quantity'];
			$gst		 		= $pod['gst'];
			$unit_rate			= $pod['unit_rate'];
			$purchase_id 		= $pod['purchase_id'];
			
			$sql = "select * from sma_product where id = '$product_id'";
//echo $sql . "<br>";			
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$category 		= $r1['category'];
											
			$sql = " SELECT * FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
							WHERE a.id = si_hdr_id AND our_po_ref_no = '$po_id' 
								AND b.po_item_id = '$po_item_id'
								AND b.material_id = '$product_id' AND a.del !='Y' 
								AND approval_Status != 'Rejected' ";
//echo $sql . "<br>";
				//exit();
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$si_rowaffected = mysqli_affected_rows($con);
			if($si_rowaffected>0){
					
			while($r2  = mysqli_fetch_array($res)){
						
				if($bal_si_qty<0 || $bal_si_amount<0){
					echo $purchase_id. ' ' . $po_item_id . ' ' . $bal_si_qty . ' || '. $bal_si_amount. ' NAGETIVE VALUE' . '<BR>';
				}
		
						$si_po_item_id		= $r2['po_item_id'];
						if($si_po_item_id>0){
							$po_item_id = $si_po_item_id;
						}	
						
						$credit_note_value 	= $r2['credit_note_value'];
						$qty 				= $r2['qty'];
						$rate 				= $r2['rate'];
						$si_gst 			= $r2['gst'];
						$si_bal_value 	    = round($qty * $rate + (($qty * $rate) * $si_gst / 100),2) ;
						
						if($credit_note_value!=0){
							$si_bal_value	= $si_bal_value - $credit_note_value;
						}
						
						if($category=='M'){
							$sql = " UPDATE sma_po_items SET bal_si_qty = bal_si_qty + $qty, 
									bal_si_amount = bal_si_amount + $si_bal_value 
										WHERE id = '$po_item_id' ";
						}
						else if($category=='S'){
							$qty = 1;
							$sql = " UPDATE sma_po_items SET bal_si_qty = $qty, 
									bal_si_amount = bal_si_amount + $si_bal_value 
										WHERE id = '$po_item_id' ";
				//echo $sql .' << ' .$purchase_id ." >><BR>";
						
						}	
						mysqli_query($con, $sql);
						$pi_rowaffected = mysqli_affected_rows($con);
						echo mysqli_error($con);
						if($pi_rowaffected>0){
							//echo $sql . " ###1<br>";
						}	
						//echo $sql . " ###2<br>";
						
						/* $sql = " UPDATE sma_po_items SET bal_si_qty = quantity, 
									bal_si_amount = bal_si_amount + $si_bal_value 
										WHERE id = '$po_item_id' and quantity < bal_si_qty";
						//echo $sql . "<br>";
						//mysqli_query($con, $sql);
						$pi_rowaffected = mysqli_affected_rows($con);
						echo mysqli_error($con);
						if($pi_rowaffected>0){
							echo $sql . " ###2<br>";
						} */
						
						//echo $po_id. ' PO Item ' . $qty. ' ' . $si_bal_value . '<BR> ' ;
					}
					
					//exit();	
				}
				else if($si_rowaffected==0){
					$sql = " UPDATE sma_po_items SET bal_si_qty = 0, bal_si_amount = 0
										WHERE id = '$po_item_id' ";
					mysqli_query($con, $sql);
						$pi_rowaffected = mysqli_affected_rows($con);
						echo mysqli_error($con);
						if($pi_rowaffected>0){
							echo $sql . " ###3<br>";
						}
	
				}
				
			}

	}  
 
//exit();
// PO Balance QTY Update Process End
 
?> 