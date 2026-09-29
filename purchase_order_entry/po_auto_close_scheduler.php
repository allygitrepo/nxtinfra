<?php

session_start();
include("../dbcon.php");
include("../baseurl.php");

require '../PHPMailer-master/PHPMailerAutoload.php';

	$sql = " SELECT distinct(id) as purchase_id, po_number FROM sma_purchase_order where status in ( 'Completed' )	";	
echo $sql. "<BR>";			
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$purchase_id 	= $row['purchase_id'];	
		$po_number		= $row['po_number'];
		
		$sql = " SELECT * FROM sma_supplier_invoice where our_po_ref_no = '$purchase_id' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$r2 = mysqli_fetch_array($res);
		$status 		= $r2['status'];
		if($status=='Draft'){
			Continue;
		}
			
		$status 	= '';	
		$auto_close = '';
		$sql = " SELECT b.* FROM sma_purchase_order a, sma_po_items b where a.id = b.purchase_id and bal_si_amount > 0 and b.purchase_id = '$purchase_id' ";
		$sql = " SELECT b.* FROM sma_purchase_order a, sma_po_items b where a.id = b.purchase_id and bal_si_amount > 0 and b.purchase_id = '1113' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($r2 = mysqli_fetch_array($res)){
			
			$quantity 		= $r2['quantity'];
			$unit_rate 		= $r2['unit_rate'];
			$gst 			= $r2['gst'];
			$bal_si_amount 	= round($r2['bal_si_amount'],0);
			$bal_si_qty 	= $r2['bal_si_qty'];
			
			$po_value = round(($quantity * $unit_rate) + (($quantity * $unit_rate) * $gst /100),0) ;
			
			$auto_close = 'Y';
			if( ( $po_value - $bal_si_amount > 1 ) || ( $bal_si_amount - $po_value > 1 ) ){
				$auto_close = 'N';
				echo 	$auto_close. ' ' .$bal_si_amount .' - '. $po_value. ' BB ' .$purchase_id. "<BR>";
			}
			
			/* if( ($bal_si_amount - $po_value ) > 1 ){
				//echo 	$bal_si_amount .' - '. $po_value. ' ' .$purchase_id. "<BR>";
			}
			else if( ($po_value - $bal_si_amount ) <= 1 ){
				if($auto_close == 'Y'){
					//echo 	$auto_close. ' ' .$bal_si_amount .' - '. $po_value. ' AA ' .$purchase_id. "<BR>";
				}
			} */
			
		}
 		
exit();
		
		if($auto_close == 'Y'){
			$status			= 'Auto Closed';
			$sql = " UPDATE sma_purchase_order SET status = '$status' WHERE id = '$purchase_id' ";
			echo mysqli_query($con, $sql);
			echo mysqli_error($con);
	//echo $sql. "<BR>";		
			$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
			$q2	=	mysqli_query($con, $sql);
			$r2 =	mysqli_fetch_array($q2);
			$draft_by 		= $r2['draft_by'];
			
			$sql = " select * from sma_user where userid = '$draft_by' ";
	//echo $sql. "<BR>";		
			$q2 =mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_username		= $r2['username'];
			
			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
					values('PO', '$purchase_id', '3', now(), '$status', '$draft_by_id', '$status', now() )";
	//echo $sql. "<BR>";					
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
			include "po_auto_close_mail.php";
			
/* if($auto_close == 'Y'){				
	exit('####1');
} */
		
		}
				
	}	

//echo "<script>window.close();</script>";

//exit();
			
?>