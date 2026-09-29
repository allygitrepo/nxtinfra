<?php

	include "../dbcon.php";
	
	$sql = " select * from ( select a.id as po_id , to_supplier, round(sum((quantity * unit_rate) + (((quantity * unit_rate * gst) / 100))),0) as tot_amount , a.paid_amount from sma_purchase_order a, sma_po_items b where a.id = b.purchase_id and a.advance_flag = 'Y' and a.status='Completed' and paid_amount >= 0 and a.del != 'Y' and paid_status != 'Paid' group by a.id ) DS where tot_amount > paid_amount and (tot_amount - paid_amount) > 1 "; //and a.project = '4'

	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)){
		
		$po_id = $row['po_id'];
		$sql = "SELECT sum(payment_adjusted) as payment_adjusted FROM `payment_details` a, payment_header b where b.del !='Y' and a.payment_hdr_id = b.id and a.supp_id = '$po_id' and b.st_flag = 'D' ";
		$q22 = mysqli_query($con, $sql);
		$affectrows = mysqli_affected_rows($con);
		$r22 = mysqli_fetch_array($q22);
		$payment_adjusted_v = $r22['payment_adjusted'];
		
		echo 'PO'. ' ' .$po_id. ' ' . $payment_adjusted_v. "<BR>";
		$paid_status = 'Paid';
		$sql = " UPDATE `sma_purchase_order` set paid_amount = '$payment_adjusted_v' where id = '$po_id' and advance_flag = 'Y' ";
		mysqli_query($con, $sql);
		mysqli_error($con);
//echo $sql. "<BR>";		

		$sql = " select * from sma_supplier_invoice where status != 'Rejected' and del !='Y' and our_po_ref_no = '$po_id' ";
//echo $sql. "<BR>";			
		$qry = mysqli_query($con, $sql);
		$affectrows = mysqli_affected_rows($con);
		while($rw2 = mysqli_fetch_array($qry)){
			
			$si_id = $rw2['id'];
		
			$sql = "SELECT sum(payment_adjusted) as payment_adjusted FROM `payment_details` a, payment_header b where b.del !='Y' and a.payment_hdr_id = b.id and a.supp_id = '$si_id' and b.st_flag = 'S' ";
			$q22 = mysqli_query($con, $sql);
			$affectrows = mysqli_affected_rows($con);
			$r22 = mysqli_fetch_array($q22);
			$payment_adjusted_v = $r22['payment_adjusted'];
//echo 'SI'. ' ' .$si_id. ' ' . $payment_adjusted_v. "<BR>";
			$paid_status = 'Paid';
			
			$sql = " SELECT * FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$si_id' and account_name like '%TDS%' and effect = 'Cr' and account_type = 'A' ";
			$q22 = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($q22);
			$tds_amount_v = $r22['amount'];
			if(!empty($tds_amount_v)){
				$abc ='';
			}	
			else {
				$tds_amount_v =0;	
			}	
			//$tds_amount_v = 0;
//echo 'SI'. ' ' .$si_id. ' ' . $tds_amount_v. "<BR>";
			
			$sql = " UPDATE `sma_purchase_order` set paid_amount = paid_amount + '$payment_adjusted_v' + $tds_amount_v where id = '$po_id' and advance_flag = 'Y' ";
			mysqli_query($con, $sql);
			mysqli_error($con);
			
echo $sql. "<BR>";	
		
		}
		
	}
	
?>
		