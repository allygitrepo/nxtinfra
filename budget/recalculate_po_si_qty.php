<?php
include "../dbcon.php";

// PO Balance QTY Update Process Start
   	$sql = " SELECT b.* FROM sma_purchase_order a, sma_po_items b WHERE 1 and a.id = b.purchase_id and a.del !='Y' 
				and a.approval_status not in ( 'Rejected' ) and a.id in (212) order by b.purchase_id, b.id ";
echo $sql . "<br>"; //and a.id in (523)
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$po_id		 	    = $row['purchase_id'];
			$po_item_id	 		= $row['id'];
			//$product_name 		= $row['product_name'];
			$product_id			= $row['product_id'];
			/* $bal_si_qty			= $row['bal_si_qty'];
			$bal_si_amount		= $row['bal_si_amount'];
			$quantity			= $row['quantity'];
			$gst		 		= $row['gst'];
			$unit_rate			= $row['unit_rate']; 
			*/
	
			$sql = " SELECT b.* FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
						WHERE a.id = si_hdr_id AND our_po_ref_no = '$po_id' 
							AND b.material_id = '$product_id' AND a.del !='Y' 
							AND approval_Status != 'Rejected' 
							AND b.qty > 0 ";

echo $sql . "<br>";	
			$res = mysqli_query($con, $sql);
			$si_rowaffected = mysqli_affected_rows($con);
			if($si_rowaffected>0){
				$sql = " UPDATE sma_po_items SET bal_si_qty = 0, bal_si_amount = 0 WHERE purchase_id = '$po_id' and product_id = '$product_id' ";
echo $sql . "<br>";				
				mysqli_query($con, $sql);
					
				while($r2  = mysqli_fetch_array($res)){
					
					//$si_srno	 			= $r2['si_srno'];
					//$si_po_item_id 		= $r2['po_item_id'];
					//$against_po_flag	 	= $r2['against_po_flag'];
					$product_id				= $r2['material_id'];
					
					$qty 					= $r2['qty'];
					$rate 					= $r2['rate'];
					$si_gst 				= $r2['gst'];
					$si_bal_value 	    	= round( ($qty * $rate ) + (($qty * $rate) * $si_gst / 100),2) ;
					
					$sql = " UPDATE sma_po_items SET bal_si_qty = bal_si_qty + $qty, 
									bal_si_amount = bal_si_amount + $si_bal_value 
								WHERE purchase_id = '$po_id' and product_id = '$product_id' ";
								
echo $sql . "<br>";
					mysqli_query($con, $sql);
					
				}		
					
			}	

	} 
	 
exit('Exit HERE PO...');
// PO Balance QTY Update Process End

?>