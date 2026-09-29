<?php
include("../header.php");
$modulePath = "supp_invoice/";

$_SESSION['reset'] = '1';

$user       = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";

	$pgname = $help_code;
	include("../viewonly.php");
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php  
	if($_GET['sub'] == 'delete'){
        $id  = $_GET['si_id'];

		$sql = "select * from  sma_supplier_invoice where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$sr    = mysqli_fetch_array($query1);
		$our_po_ref_no 		= $sr['our_po_ref_no'];
		
		$sql = "SELECT * FROM `sma_supplier_invoice_details` where si_hdr_id = '$id' ";
		$sires = mysqli_query($con, $sql);
		echo 	 mysqli_error($con);
		while($sr    = mysqli_fetch_array($sires)){
			
			$material_id 		= $sr['material_id'];
			$budget_head 		= $sr['budget_head'];
			$budget_id 			= $sr['budget_id'];
			$si_qty 			= $sr['qty'];
			$si_rate 			= $sr['rate'];
			$si_gst 			= $sr['gst'];
			
			$gstamt = (($si_qty * $si_rate) * $si_gst / 100);
			$si_amount = $si_qty * $si_rate + $gstamt;
			
			if( $si_amount <= 0 ){
				$si_amount = 0;
			}
				
//Changed on 14-11-2025				
// 			$sql = "update sma_budget set blocked_budget = blocked_budget + $si_amount, used_budget = used_budget - $si_amount where id = '$budget_id' ";
// 			mysqli_query($con, $sql);
// 			echo mysqli_error($con);
//Changed on 14-11-2025
echo $sql . "<BR>";			
			$sql="update sma_po_items set bal_si_qty  = bal_si_qty - $si_qty where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id'";
			$query=mysqli_query($con, $sql);		
			echo mysqli_error($con);
echo $sql . "<BR>";			
		}
		
		$sql = "update sma_supplier_invoice_details set qty = 0 where si_hdr_id = '$id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql . "<BR>";		
//		$sql = "delete from sma_supplier_invoice where id='$id' ";
		$sql = "update sma_supplier_invoice set del = 'Y' where id='$id' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql . "<BR>";

/*		$sql = "delete from sma_supplier_invoice_details where si_hdr_id = '$id' ";
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql;
		$sql="delete FROM `file_uploads` where module = 'SI' and reference_id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
*/		
//echo $sql;
//exit();
        //echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		$baseurl1 =$baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
			
	} 

	if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){
		$id					= $_POST['id'];
		$status				= $_POST['status'];
		
		if($_POST['submit1']){
			$approval_status	= $_POST['submit1'];
		}
		else if($_POST['submit2']){
			$approval_status	= $_POST['submit2'];
		}
		
		if($status =='Draft'){
			$approval_status 	= 'Pending';
		}
		$user   = $_SESSION['user'];

		$sql = "update sma_supplier_invoice set approval_status	= '$approval_status', status =  'Submitted', changed_by = '$user', changed_date = now() where id = '$id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";

	}
	
?>

<?php
	
	if(isset($_POST['editTallycode'])){
		 
		$record_id     		= $_POST['record_id'];
		$si_hdr_id 			= $_POST['si_hdr_id'];
		$budget_head		= $_POST['budget_head'];
		$sql = "select * from sma_budget_subgroup where 1 and id =  '$budget_head' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r4 = mysqli_fetch_array($res);
		$account_name		= $r4['budget_code'];
//echo $sql. "<BR>";
		
		$sql = "update `tally_journal_entry` set account_name = '$account_name',
					budget_head     		= '$budget_head'
				where doc_no = '$si_hdr_id' and record_id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);

		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&IN=in';//'&active5=active&zyx'
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
	if(isset($_POST['editTally'])){
		 
		$record_id     		= $_POST['record_id'];
		$si_hdr_id 			= $_POST['si_hdr_id'];
		$doc_no				= $_POST['si_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_id 		= $_POST['account_id'];
		$amount 			= $_POST['amount'];
		$prev_amount		= $_POST['prev_amount'];
		$narration 			= $_POST['narration'];
		$effect 			= $_POST['effect'];
		$sql = "update `tally_journal_entry` set 
					record_id     		= '$record_id',
					account_type 		= '$account_type',
					account_id 			= '$account_id',
					amount 				= '$amount',
					narration 			= '$narration',
					effect 				= '$effect'
				where doc_no = '$si_hdr_id' and record_id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);

//echo $sql. "<BR>";
//exit();
	
		/* $sql = " SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage']; */
		if( $amount > 0 && $effect =='Cr' ){
			$sql = " update tally_journal_entry set amount = amount - $amount + $prev_amount where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//	echo $sql. "<BR>";		
			$sql 	= " update sma_supplier_invoice set payable_amount = payable_amount - '$amount' + $prev_amount, bal_amount = bal_amount - '$amount' + $prev_amount where id = '$si_hdr_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";			
		}
		else if( $amount > 0 && $effect =='Dr' ){
			$sql = " update tally_journal_entry set amount = amount + $amount - $prev_amount where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";			
			$sql 	= " update sma_supplier_invoice set payable_amount = payable_amount + '$amount' - $prev_amount, bal_amount = bal_amount + '$amount' - $prev_amount where id = '$si_hdr_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			$sql= " select * from tally_journal_entry where effect='Cr' and account_type in( 'V' ) and doc_type='SI' and doc_no='$doc_no' ";
//echo $sql."<BR>";		
			$qry = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 	= mysqli_fetch_array($qry);
			$amount = $r2['amount'];		
			$sql 	= " update sma_supplier_invoice set payable_amount =  '$amount', bal_amount = '$amount' where id = '$si_hdr_id' ";
			mysqli_query($con, $sql);
			
//echo $sql. "<BR>";			
		}
		

//echo $sql. "<BR>";
//exit();

		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&IN=in';//'&active5=active&zyx'
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
	if(isset($_POST['editItem'])){

		$rid     		= $_POST['rid'];
		$si_hdr_id 		= $_POST['si_hdr_id'];
		//$material_id	= $_POST['material_id'];
		$material_id	= $_POST['itemName'];
		//$grn_no		= $_POST['grn_no'];
		$ida 			= explode('-',$_POST['grn_no']);
		$grn_no			= $ida['0'];
		$description 	= $_POST['itemdescription'];
		$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_id	    = $_POST['budget_id'];
		
		$rate_p			= $_POST['itemrate_p'];
		$quantity_p		= $_POST['itemqty_p'];
		$budget_id_p	= $_POST['budget_id_p'];
		$quantity 		= $_POST['itemqty'];
		$units 			= $_POST['itemunits'];
		$rate 			= $_POST['itemrate'];
		$gst 			= $_POST['itemgst'];
		$gst_p			= $_POST['itemgst_p'];
		$gst_id			= $_POST['itemgst_id'];

		$sql 	= "select * from gst_mst where 1 and igst = '$gst' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$gst_id 		= $r22['id'];
		
		$sql = " select * from sma_supplier_invoice where id = '$si_hdr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$against_po_flag 	= $r2['against_po_flag'];
		
		$sql = "select * from sma_product where id = '$material_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$material_name 		= $r1['name'];
		$po_threashold 		= $r1['po_threashold'];
		$tolerance_level 	= $r1['tolerance_level'];
		
		$sql = " select * from sma_purchase_order where id = '$our_po_ref_no' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$po_type	 		= $r2['po_type'];
		$approval_hdr_id	= $r2['approval_memo_ref'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$gstamt 	= round((($quantity * $rate) * $gst / 100),0);
		$si_amount 	= round(($quantity * $rate) + $gstamt,0);
		if($budget_control_gst!='Y'){
			$gstamt = 0;
		}
		$amount 	= ($quantity * $rate) + $gstamt;
		
		$gst_amt_p 	= round((($quantity_p * $rate_p) * $gst_p / 100),0);
		
		if($budget_control_gst!='Y'){
			$gst_amt_p = 0;
		}
		
		$amount_p 	= ($quantity_p * $rate_p ) + $gst_amt_p;
		$amount 	= $amount - $amount_p;
		
		$sql = " select * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' and si_srno = '$rid' ";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$first_insert = $r1['first_insert'];
			
		
		$sql = "select * from sma_supplier_invoice where id = '$si_hdr_id' ";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$our_po_ref_no 		= $r1['our_po_ref_no'];
		$against_po_flag 	= $r1['against_po_flag'];

		if(!empty($our_po_ref_no)){
			$sql = "select * from sma_po_items where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$po_quantity 	= $r1['quantity'];
			$po_rate		= $r1['unit_rate'];	
			$rate		= $r1['unit_rate'];	
			$po_gst			= $r1['gst'];	
			$po_gst_amt 	= (($po_quantity * $po_rate) * $po_gst / 100);
			$po_amount 		= round(($po_quantity * $po_rate) + $po_gst_amt,0);
			 		
			$po_item_qty    = $r1['quantity'] - $r1['bal_si_qty'] + $quantity_p;
//echo $amount_p . ' ' . $po_amount. ' ' . $r1['bal_si_amount'];
			
			$po_amount      = ($po_amount - $amount_p) + $r1['bal_si_amount'];
			
			$bal_si_qty		= $r1['bal_si_qty'];
					
//echo $bal_si_qty. ' <<>> ' . $quantity . ' > ' . $po_item_qty . ' && ' . $po_threashold ."<<>>";
//exit();
 //2843800 2843800 3699300.00
// 2843800 > 1988300 && V
//echo $si_amount . ' > '. $po_amount . ' && ' . $po_threashold  . "<BR>"; exit();
		if($po_threashold =='V'){
			if( ($si_amount - $po_amount) <= 1 || ($po_amount - $si_amount ) <= 1 ){
				$si_amount = $po_amount;
			}	
		}
		
			 if( $quantity > $po_item_qty && $po_threashold =='Q' ){
				echo "<script>alert('SI Quantity should not be greater than PO Quantity !!!')</script>";
				echo "<meta http-equiv='refresh' content='0'>";    
				$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&A98=97&matid='.$material_id.'&budgetid='.$budget_id;//.'&active=active'
				echo "<script>window.location.href='$baseurl';</script>";
				exit();
			}
			else if($si_amount > $po_amount && $po_threashold =='V'){
				echo "<script>alert('SI Value should not be greater than PO Value !!!')</script>";
				echo "<meta http-equiv='refresh' content='0'>";    
				$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&A98=99&matid='.$material_id.'&budgetid='.$budget_id;//.'&active=active'
				echo "<script>window.location.href='$baseurl';</script>";
				exit();
			}
		}
		
		//exit('Quantity Check....!!!');
		
		if( $first_insert!='F'){
			$sql = "select * from sma_budget where id = '$budget_id'";
	//echo $sql. "<BR>"; 
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$budget_head = $r1['budget_head'];
			$budget_name = $r1['budget_name'];
			$total_budget 	= $r1['total_budget'];
			$blocked_budget = $r1['blocked_budget'] ; //+ $amount_p
			$adjustment_budget 	= $r1['adjustment_budget'];
			$used_budget 	= $r1['used_budget'];
			
			$balance_budget	= ($total_budget + $adjustment_budget ) - ( $blocked_budget + $used_budget)  ;
//echo $blocked_budget. ' < ' . $amount . ' ' .$balance_budget; exit();			
			if($blocked_budget < $amount ){
				echo "<script>alert('Insufficient Blocked Budget balance !!! ')</script>";
				echo "<meta http-equiv='refresh' content='0'>";    
				$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&A98=98&matid='.$material_id.'&budgetid='.$budget_id;
				echo "<script>window.location.href='$baseurl';</script>";
				exit();
			}
		}
		
//When cost center change below logic		
		/* if( $first_insert!='F'){
			if($against_po_flag == 'Y'){
				$sql = " update sma_budget set blocked_budget = blocked_budget + $amount_p where id = '$budget_id_p' ";
	//echo $sql. "<BR>";
				$q3  = mysqli_query($con, $sql);
			}
			
			$sql = " update sma_budget set used_budget = used_budget - $amount_p where id = '$budget_id_p' ";
	//echo $sql. "<BR>";
			$q3  = mysqli_query($con, $sql);
		} */
		
//Changed on 14-11-2025		
// 		$sql = " update sma_budget set blocked_budget = blocked_budget + $amount_p - $amount where id = '$budget_id' ";
// 		$q3  = mysqli_query($con, $sql);
// //echo $sql. "<BR>";			
// 		$sql = " update sma_budget set blocked_budget = 0 where blocked_budget < 0 id = '$budget_id' ";
// 		$q3  = mysqli_query($con, $sql);
	
// 		$sql = " update sma_budget set used_budget= used_budget - $amount_p + $amount where id = '$budget_id' ";
// 		$q3  = mysqli_query($con, $sql);
//Changed on 14-11-2025

//echo $sql. "<BR>";			
		$sql = "update `sma_supplier_invoice_details` set material_id = '$material_id', 
						grn_no		   = '$grn_no',
						material_name  = '$material_name', 
						description    = '$description',
						company_id     = '$company_id',
						budget_id      = '$budget_id',
						budget_name    = '$budget_name',
						budget_head    = '$budget_head',
						qty			   = '$quantity', 
						unit		   = '$units',
						rate		   = '$rate', 
						amount		   = '$amount', 
						gst			   = '$gst',
						gst_id		   = '$gst_id',
						first_insert   = ''
				where si_hdr_id = '$si_hdr_id' and si_srno = '$rid' ";
			mysqli_query($con, $sql);
		
		$sql = "update `sma_supplier_invoice_details` set amount =  ( qty * rate ) + ((( qty * rate ) + gst ) / 100 ) 
		                where si_hdr_id = '$si_hdr_id' and si_srno = '$rid' ";
		mysqli_query($con, $sql);
			
//echo $sql."<BR>";
//exit();
	$amount_tot = 0;
	
	$sql = "select * from sma_supplier_invoice where id = '$si_hdr_id'";
	$r2 = mysqli_query($con, $sql);
	$r1 = mysqli_fetch_array($r2);
	$our_po_ref_no 	= $r1['our_po_ref_no'];
	$company_id 	= $r1['company_id'];
	
	$sql = "select * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id'";
	$r2 = mysqli_query($con, $sql);
	while($r1 = mysqli_fetch_array($r2)){
		$amount_tot = $amount_tot + $r1['amount'];	
	}
	
	if($amount_tot > 0 ){
		$sql = " update `sma_supplier_invoice` set total_amount = '$amount_tot', payable_amount = '$amount_tot' where id = '$si_hdr_id' and paid_status != 'Paid' ";
		$r2 = mysqli_query($con, $sql);
//	echo $sql;
	}
	
	if($first_insert=='F'){
		$quantity_p = 0 ;
		$amount_p   = 0 ;
	}
	
	if($po_item_qty < $po_item_qty){
				
	}
			
	if(!empty($our_po_ref_no) ){
	    $sql="update sma_po_items set bal_si_qty  = bal_si_qty + $quantity - $quantity_p ,
				bal_si_amount = bal_si_amount + $amount - $amount_p
				where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
		mysqli_query($con, $sql);
		
		$sql="update sma_po_items set bal_si_amount = (bal_si_qty * unit_rate ) + ( ( (bal_si_qty * unit_rate ) * gst ) / 100 )
				where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
		mysqli_query($con, $sql);
//echo $sql. "<BR>";
//exit();

// 		if($po_threashold=='Q'){
// 			$sql="update sma_po_items set bal_si_qty  = bal_si_qty + $quantity - $quantity_p ,
// 				bal_si_amount = bal_si_amount + $amount - $amount_p
// 				where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
// 			mysqli_query($con, $sql);	
// 		}
// 		else if($po_threashold=='V'){
// 			$sql="update sma_po_items set bal_si_qty = bal_si_qty + $quantity - $quantity_p ,
// 				bal_si_amount = bal_si_amount + $amount - $amount_p
// 				where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
// 			mysqli_query($con, $sql);	
// 		}
		
		$sql="update sma_po_items set bal_si_qty  = po_qty 
			where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' and bal_si_qty > po_qty ";
		mysqli_query($con, $sql);
		
	}

	
//New Product Stock Start	
		$sql = "SELECT * FROM sma_product_open_stock where product_name = '$material_id' and project = '$company_id' ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rowaffected==0){
			$sql = "INSERT INTO sma_product_open_stock ( product_name, project, receipts, created_date, remarkv ) 
						VALUE ( '$material_id', '$company_id', '$quantity', now(), 'InsEdit' ) ";
//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else {
			$sql = "UPDATE sma_product_open_stock set receipts = receipts + $quantity where product_name = '$material_id' and project = '$company_id'  ";
//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		
//New Product Stock End	

//echo $sql."<BR>"; exit('EXIT HERE....');
 
//$file = fopen("ravitest.txt","a");
//fwrite($file,$sql);
//fclose($file);

//exit('######2');	
//	echo "<script>window.location.reload();</script>";
	echo "<meta http-equiv='refresh' content='0'>";    
	//echo "<script>window.location.href='supplier_invoice.php?sub=edit&id=$si_hdr_id&active=active&987';</script>";
	$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id;//.'&active=active&987'
	echo "<script>window.location.href='$baseurl';</script>";

}

?>

<?php
	if(isset($_POST['Save'])){
		
	
			$id				= $_POST['id']; 
			$si_id			= $_POST['id'];
						
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$created_date			= date('Y-m-d', strtotime($_POST['created_date']));
			$suplier_name			= $_POST['suplier_name'];
			$comp_id				= $_POST['comp_id'];
			$trans_type				= $_POST['trans_type'];
			
			$our_po_ref_no			= $_POST['our_po_ref_no'];
			$delivery_challen_no	= $_POST['delivery_challen_no'];
			$delivery_mode			= $_POST['delivery_mode'];
			//$budget_head			= $_POST['budget_head'];
			$delivery_date			= date('Y-m-d', strtotime($_POST['delivery_date']));
//			$transport_lr_no		= $_POST['transport_lr_no'];
//			$lr_date				= date('Y-m-d', strtotime($_POST['lr_date']));
//			$transporter_name		= $_POST['transporter_name'];
			$credit_days			= $_POST['credit_days'];
			$due_date				= date('Y-m-d', strtotime($_POST['due_date']));
			$state					= $_POST['state'];
			//$retention_flag			= $_POST['retention_flag'];
			$retention_flag			= '';

//			$tds_percentage			= $_POST['tds_percentage'];
			$gst_flag				= $_POST['gst_flag'];

			$additional_charges		= $_POST['additional_charges'];
			$additional_remarks		= $_POST['additional_remarks'];
			$supplier_gst_no		= $_POST['supplier_gst_no'];
			$supplier_location		= $_POST['supplier_location'];
			$bill_no				= $_POST['bill_no'];
			
			$retention_amount		= $_POST['retention_amount'];
			$compliances_amount		= $_POST['compliances_amount'];
			$recovery_amount		= $_POST['recovery_amount'];
			$hold_amount			= $_POST['hold_amount'];
			$retention_remarks      = $_POST['retention_remarks'];
			$compliances_remarks    = $_POST['compliances_remarks'];
			$recovery_remarks       = $_POST['recovery_remarks'];
			$hold_remarks           = $_POST['hold_remarks'];
			
			$department				= $_POST['department'];
			$against_po_flag		= $_POST['against_po_flag'];
			$invoice_type			= $_POST['invoice_type'];
			
			$invoice_booked_by		= $_POST['invoice_booked_by'];
			
			$deduction1_id			= $_POST['deduction1_id'];
			$deduction2_id			= $_POST['deduction2_id'];
			$deduction3_id			= $_POST['deduction3_id'];
			$deduction4_id			= $_POST['deduction4_id'];
			$deduction5_id			= $_POST['deduction5_id'];
			
			$deduction1_amount		= $_POST['deduction1_amount'];
			$deduction2_amount		= $_POST['deduction2_amount'];
			$deduction3_amount		= $_POST['deduction3_amount'];
			$deduction4_amount		= $_POST['deduction4_amount'];
			$deduction5_amount		= $_POST['deduction5_amount'];
			
			$deduction1_remarks		= $_POST['deduction1_remarks'];
			$deduction2_remarks		= $_POST['deduction2_remarks'];
			$deduction3_remarks		= $_POST['deduction3_remarks'];
			$deduction4_remarks		= $_POST['deduction4_remarks'];
			$deduction5_remarks		= $_POST['deduction5_remarks'];
			
			$tally_status			= $_POST['tally_status'];
			
			$tally_narration		= $_POST['tally_narration'];
	//echo $tally_status. "<<>>";		exit();

			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
		
			
			$location_id		= $_POST['location_id'];
			$status				= $_POST['status'];

		if ( $status == 'Draft' ){
  			$sql="update sma_supplier_invoice set supplier_invoice_no	= '$supplier_invoice_no',
						invoice_date				= '$invoice_date',
						created_date				= '$created_date',
						company_id					= '$comp_id',
						trans_type					= '$trans_type',
						our_po_ref_no				= '$our_po_ref_no',
						delivery_challen_no			= '$delivery_challen_no',
						delivery_date				= '$delivery_date',
						delivery_mode				= '$delivery_mode',
						suplier_name				= '$suplier_name',
						state						= '$state',
						retention_flag				= '$retention_flag',
						credit_days					= '$credit_days',
						due_date					= '$due_date',
						additional_charges			= '$additional_charges',
						additional_remarks			= '$additional_remarks',
						tally_narration				= '$tally_narration',
						supplier_gst_no				= '$supplier_gst_no',
						supplier_location			= '$supplier_location',
						bill_no						= '$bill_no',
						department					= '$department',
						against_po_flag				= '$against_po_flag',
						invoice_type				= '$invoice_type',
						deduction1_id				= '$deduction1_id',
						deduction2_id				= '$deduction2_id',
						deduction3_id				= '$deduction3_id',
						deduction4_id				= '$deduction4_id',
						deduction5_id				= '$deduction5_id',
						deduction1_amount			= '$deduction1_amount',
						deduction2_amount			= '$deduction2_amount',
						deduction3_amount			= '$deduction3_amount',
						deduction4_amount			= '$deduction4_amount',
						deduction5_amount			= '$deduction5_amount',
						deduction1_remarks			= '$deduction1_remarks',
						deduction2_remarks			= '$deduction2_remarks',
						deduction3_remarks			= '$deduction3_remarks',
						deduction4_remarks			= '$deduction4_remarks',
						deduction5_remarks			= '$deduction5_remarks',
						gst_flag					= '$gst_flag',
						location					= '$location_id',
						invoice_booked_by			= '$invoice_booked_by',
						retention_amount			= '$retention_amount',
						compliances_amount			= '$compliances_amount',
						recovery_amount				= '$recovery_amount',
						hold_amount					= '$hold_amount',
						retention_remarks           = '$retention_remarks',
						compliances_remarks         = '$compliances_remarks',
						recovery_remarks            = '$recovery_remarks',      
						hold_remarks                = '$hold_remarks'
				where id = '$id'";

				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
			}

			$sql="update sma_supplier_invoice set deduction1_id				= '$deduction1_id',
						deduction2_id				= '$deduction2_id',
						deduction3_id				= '$deduction3_id',
						deduction4_id				= '$deduction4_id',
						deduction5_id				= '$deduction5_id',
						deduction1_amount			= '$deduction1_amount',
						deduction2_amount			= '$deduction2_amount',
						deduction3_amount			= '$deduction3_amount',
						deduction4_amount			= '$deduction4_amount',
						deduction5_amount			= '$deduction5_amount',
						deduction1_remarks			= '$deduction1_remarks',
						deduction2_remarks			= '$deduction2_remarks',
						deduction3_remarks			= '$deduction3_remarks',
						deduction4_remarks			= '$deduction4_remarks',
						deduction5_remarks			= '$deduction5_remarks'
			where id = '$id'";
			$query=mysqli_query($con, $sql);
			
            $payment_adjusted  = 0;
            $sql = "select b.* from payment_header a, payment_details b where 1 and a.id =  b.payment_hdr_id and a.st_flag = 'S' and b.supp_id = '$si_id'";
			$r2 = mysqli_query($con, $sql);
			while($r1 = mysqli_fetch_array($r2)){
				$payment_adjusted  = $payment_adjusted  + $r1['payment_adjusted'];	
			}
            
            	$sql = "select * from sma_supplier_invoice_details where si_hdr_id = '$id'";
				$r2 = mysqli_query($con, $sql);
				while($r1 = mysqli_fetch_array($r2)){
					$amount_tot = $amount_tot + $r1['amount'] - $r1['credit_note_value'];	
				}
				
				$total_deduction = $retention_amount + $compliances_amount + $recovery_amount + $hold_amount + $deduction1_amount;
 				$amount_tot = $amount_tot - $total_deduction;
				
				
				if( $amount_tot > 0 || $amount_tot < 0 ){
 					$sql = " UPDATE `sma_supplier_invoice` SET total_amount = '$amount_tot', payable_amount = '$amount_tot', bal_amount = ('$amount_tot' - $payment_adjusted ) where id = '$id' ";
					$r2 = mysqli_query($con, $sql);
			        $sql = " UPDATE `sma_supplier_invoice` SET paid_status = 'Paid' where bal_amount <= 0 and id = '$id' ";
			        mysqli_query($con, $sql);
				}
				
//exit();

				if ( $status == 'Draft' || $status == 'Submitted' || $status=='Completed' ){
  			        $sql="update sma_supplier_invoice set 	deduction1_id	= '$deduction1_id',
						deduction1_amount			= '$deduction1_amount',
						deduction1_remarks			= '$deduction1_remarks',
						gst_flag					= '$gst_flag',
						retention_amount			= '$retention_amount',
						compliances_amount			= '$compliances_amount',
						recovery_amount				= '$recovery_amount',
						hold_amount					= '$hold_amount',
						retention_remarks           = '$retention_remarks',
						compliances_remarks         = '$compliances_remarks',
						recovery_remarks            = '$recovery_remarks',      
						hold_remarks                = '$hold_remarks'
    				where id = '$id'";
    
    				mysqli_query($con, $sql);
    				$error= mysqli_error($con);
    				if(!empty($error)){echo $error; exit();}

				}
//echo $sql. "<BR>";
//exit();
			
			if ( $status=='Completed' || $tally_status == 'C' || $tally_status =='U' ){
				$aa='';
			}
			else {	
				/* if($deduction1_id>0){
					$sql="select * from account_mst where id ='$deduction1_id' and retention_flag = 'Y' ";				
					$result = mysqli_query($con, $sql);
					$r 		= mysqli_fetch_object($result);
					$deduct_id 		= $r->id;
					if($deduction1_id==$deduct_id){
						$sql="update sma_supplier_invoice set retention_amount = '$deduction1_amount'
							where id='$id'";
							mysqli_query($con, $sql);
					}
				}
				if($deduction2_id>0){
					$sql="select * from account_mst where id ='$deduction2_id' and retention_flag = 'Y' ";				
					$result = mysqli_query($con, $sql);
					$r 		= mysqli_fetch_object($result);
					$deduct_id 		= $r->id;
					if($deduction2_id==$deduct_id){
						$sql="update sma_supplier_invoice set retention_amount = retention_amount + '$deduction2_amount'
							where id='$id'";
							mysqli_query($con, $sql);
					}
				}
				if($deduction3_id>0){
					$sql="select * from account_mst where id ='$deduction3_id' and retention_flag = 'Y' ";				
					$result = mysqli_query($con, $sql);
					$r 		= mysqli_fetch_object($result);
					$deduct_id 		= $r->id;
					if($deduction3_id==$deduct_id){
						$sql="update sma_supplier_invoice set retention_amount = retention_amount + '$deduction3_amount'
							where id='$id'";
							mysqli_query($con, $sql);
					}
				}
			    if($deduction4_id>0){
					$sql="select * from account_mst where id ='$deduction4_id' and retention_flag = 'Y' ";				
					$result = mysqli_query($con, $sql);
					$r 		= mysqli_fetch_object($result);
					$deduct_id 		= $r->id;
					if($deduction4_id==$deduct_id){
						$sql="update sma_supplier_invoice set retention_amount = retention_amount + '$deduction4_amount'
							where id='$id'";
							mysqli_query($con, $sql);
					}
				}
				if($deduction5_id>0){
					$sql="select * from account_mst where id ='$deduction5_id' and retention_flag = 'Y' ";				
					$result = mysqli_query($con, $sql);
					$r 		= mysqli_fetch_object($result);
					$deduct_id 		= $r->id;
					if($deduction5_id==$deduct_id){
						$sql="update sma_supplier_invoice set retention_amount = retention_amount + '$deduction5_amount'
							where id='$id'";
							mysqli_query($con, $sql);
					}
				} */
				
			}
			
			
			if( $status == 'Draft' ){

				$email_approval_expected_role_1			= $_POST['email_approval_expected_role_1'];
				$email_approval_expected_role_2			= $_POST['email_approval_expected_role_2'];
				$email_approval_expected_role_3			= $_POST['email_approval_expected_role_3'];
				$email_approval_expected_role_4			= $_POST['email_approval_expected_role_4'];
				$email_approval_expected_role_5			= $_POST['email_approval_expected_role_5'];
				$email_approval_expected_role_6			= $_POST['email_approval_expected_role_6'];
				$email_approval_expected_role_7			= $_POST['email_approval_expected_role_7'];
				
				$email_approval_received_1				= $_POST['email_approval_received_1'];
				$email_approval_received_2				= $_POST['email_approval_received_2'];
				$email_approval_received_3				= $_POST['email_approval_received_3'];
				$email_approval_received_4				= $_POST['email_approval_received_4'];
				$email_approval_received_5				= $_POST['email_approval_received_5'];
				$email_approval_received_6				= $_POST['email_approval_received_6'];
				$email_approval_received_7				= $_POST['email_approval_received_7'];
				
				$sql = "UPDATE sma_supplier_invoice SET 
								email_approval_expected_role_1 	= '$email_approval_expected_role_1',
								email_approval_expected_role_2 	= '$email_approval_expected_role_2',
								email_approval_expected_role_3 	= '$email_approval_expected_role_3',
								email_approval_expected_role_4 	= '$email_approval_expected_role_4',
								email_approval_expected_role_5 	= '$email_approval_expected_role_5',
								email_approval_expected_role_6 	= '$email_approval_expected_role_6',
								email_approval_expected_role_7 	= '$email_approval_expected_role_7',
								email_approval_received_1		= '$email_approval_received_1',
								email_approval_received_2		= '$email_approval_received_2',
								email_approval_received_3		= '$email_approval_received_3',
								email_approval_received_4		= '$email_approval_received_4',
								email_approval_received_5		= '$email_approval_received_5',
								email_approval_received_6		= '$email_approval_received_6',
								email_approval_received_7		= '$email_approval_received_7'
							WHERE id = '$id' ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				
				//Budger calc 
				$sql="SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_id' ";
				$result = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$value="";
				$gst_amt = 0;
				while($rowd = mysqli_fetch_array($result)){
					$qty 			= $rowd['qty'];
					$rate 			= $rowd['rate'];
					$gst			= $rowd['gst'];
					$credit_note_value			= $rowd['credit_note_value'];
					$budget_id		= $rowd['budget_id'];			
					
					if(empty($gst)){
						$gst = 0;
					}	
					
					$gst_amt = round((($qty * $rate) * $gst / 100),0);
					
					$sql = "SELECT * FROM company where comp_id = '$company_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$budget_control_gst = $r2['budget_control_gst'];
					if($budget_control_gst!='Y'){
						$gst_amt = 0;
					}	
					$amount  = $qty * $rate + $gst_amt - $credit_note_value;
				//Changed on 14-11-2025	
				// 	$sql = "update sma_budget set used_budget = used_budget + $amount, blocked_budget = blocked_budget - $amount  where id = '$budget_id' ";
				// 	mysqli_query($con, $sql);
					
				// 	$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' and blocked_budget < 0 ";// Blocked budget nagative
				// 	mysqli_query($con, $sql);
				//Changed on 14-11-2025
				
				}
				//Budger calc
		
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_supplier_invoice set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, inward_status )
									VALUES( 'SI', '$si_id', '$userid', now(), 'Draft' , 'edit.php') ";
				mysqli_query($con, $sql);
				
				$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					VALUES( 'SI', '$si_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
					
				$modulePath = "supp_invoice/";
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$si_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Supplier Invoice Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");

				include "si_mail.php";		
					
			}
						
			if($tally_status=='C'){
				$sql="update sma_supplier_invoice set tally_ticked_by = '$user', tally_status = '$tally_status', tally_updated_on	= now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
		//echo $sql. "<<<>>"; exit();		
			}
			
//TALLY STATUS UPDATE			
			$sql = "update `tally_journal_entry` set status = '$tally_status' where doc_no = '$si_id' and doc_type = 'SI' ";
			$r2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			//echo $sql; exit();
			
//TALLY STATUS UPDATE		

		$amount = 0;
		$amount = $additional_charges;
		$sql = "select sum(amount) as amount from sma_supplier_invoice_details where si_hdr_id = '$si_id'";
	//echo $sql;	
		$r2 = mysqli_query($con, $sql);
		while($r1 = mysqli_fetch_array($r2)){
			$amount = $amount + $r1['amount'];
		}
		
		$sql= " select * from tally_journal_entry where effect='Cr' and account_type in( 'V' ) and doc_type='SI' and doc_no='$si_id' ";
//echo $sql."<BR>";		
		$qry = mysqli_query($con, $sql);
		$rowcnt = mysqli_affected_rows($con);
		echo mysqli_error($con);
		$r2 	= mysqli_fetch_array($qry);
		if($rowcnt>0){
			$amount = $r2['amount'];		
		}


		if($amount > 0 && $status != 'Completed' ){ 
		//	$sql = " update `sma_supplier_invoice` set payable_amount = '$amount', bal_amount = '$amount' where id = '$si_id' and paid_status != 'Paid' ";
		//	mysqli_query($con, $sql);
		}
		else if($status == 'Completed'){
			
			$sql = "SELECT a.* FROM `payment_header` a , payment_details b where 1 and a.del !='Y' and a.id = b.payment_hdr_id and b.supp_id = '$si_id' and a.st_flag = 'S' ";
			mysqli_query($con, $sql);
			$si_cnt = mysqli_affected_rows($con);
			if($si_cnt==0){
		//		$sql = " update `sma_supplier_invoice` set payable_amount = '$amount', bal_amount = '$amount' where id = '$si_id' and paid_status != 'Paid' ";
		//		mysqli_query($con, $sql);
			}
			
		}
			
			// add attachments
			// file upload
			
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST['share_point_link'];
			$arrFUDoc 			= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++){
				
				$folder_path = "uploads/si/" . $si_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} 
				$filename 		= $arrFUDoc['name'][$i];
				$tmpFileName 	= $arrFUDoc['tmp_name'][$i];
				if(!empty($filename)){
						
					//$filename = str_replace("%","",$filename);
					
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
					VALUES( 'SI', '$filename', '$folder_path',  '$arrDocType[$i]', '$arrDocDesc[$i]', '$share_point_link[$i]', '$si_id', now() )";
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, $folder_path. "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
		
				}

			}

			if($_POST['party_doc']){
				$party_doc = $_POST['party_doc'];
				for($i = 0; $i < sizeof($party_doc); $i++){
				
					$doc_in = $party_doc[$i];
					$sql = "SELECT module, file_path, file_name, reference_id, doc_type FROM `my_documents_files` where id = '$doc_in' ";
					//echo $sql. "<BR>";
					$res = mysqli_query($con, $sql);
					$r11 			= mysqli_fetch_array($res);
					$filename 		= $r11['file_name'];
					$folder_path 	= $r11['file_path'];
					$arrDocType 	= $r11['doc_type'];
					$reference_id 	= $r11['reference_id'];
					
					$sql = "INSERT INTO file_uploads (module, dms_module, file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded) 
					VALUES('SI', 'IN', '$filename', '$folder_path', '$arrDocType', '$si_id', '$reference_id', now())";
					mysqli_query($con, $sql);
					//echo $sql. "<BR>";
					
					//exit();
				}
			}
			
			$sql 	= "select * from sma_party_mst where id = '$suplier_name' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $supplier_invoice_no. ','. $invoice_date. ',' .$department. ','. $party_name;
		    $affect 		= 'Add';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu', '$sub_menu', '$comp_id', '$description', '$affect')";
		    mysqli_query($con, $sql);
			
			$page					= $_POST['page']; 		
			$baseurl.=$modulePath.'index.php?sub=list&same_page='.$page;
			
			echo "<script>window.location.href='$baseurl';</script>";
		
		}
		
		$page = $_GET['page'];
		$id	 		= $_GET['id'];
		$si_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';
//			header('Location: '.$_SERVER['REQUEST_URI']);
		}
		if($_GET['active5']){
			$active_tab1 ='';
			$active_tab2 = '';
			$active_tab5 = $_GET['active5'];
		}
		
		$sql="Select * from sma_supplier_invoice where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
	
		$status = $row['status'];
		$our_po_ref_no = $row['our_po_ref_no'];
		
		
		$approval_status = $row['approval_status'];
		$del 	= $row['del'];
		$draft_by 		= $row['draft_by'];
		$tally_status 	= $row['tally_status'];
		$tally_updated_on	= $row['tally_updated_on'];
		$tally_ticked_by 	= $row['tally_ticked_by'];
		
		$dated  	= date('d-m-Y', strtotime($row['created_date']));
		$fyr		= date('Y', strtotime($dated));
		$fmth		= date('m', strtotime($dated));
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}
		$_SESSION['finance_year'] = $fin_year;
		
		$vendor_id 		= $row['suplier_name'];
		$company_id 	= $row['company_id'];		

		$approval_received_count = 0 ;			
		$email_approval_received_1	= $row['email_approval_received_1'];
		$email_approval_received_2	= $row['email_approval_received_2'];
		$email_approval_received_3	= $row['email_approval_received_3'];
		$email_approval_received_4	= $row['email_approval_received_4'];
		$email_approval_received_5	= $row['email_approval_received_5'];
		$email_approval_received_6	= $row['email_approval_received_6'];
		$email_approval_received_7	= $row['email_approval_received_7'];
		if($email_approval_received_1=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}
		if($email_approval_received_2=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_3=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_4=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_5=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_6=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}	
		if($email_approval_received_7=='Y'){
			$approval_received_count = $approval_received_count + 1;
		}
		
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];
		
		$draft_by_supplier	= $row['draft_by_supplier'];
										
//echo $tally_status. "<<<>>>";
		
		$si_id = $row['id'];
		$readonly = '';
		if ( $status == 'Submitted' || $status == 'Completed' ){
			$readonly = 'READONLY';
		}
		if (($status == 'Submitted' ) || $status == 'Completed' ){
			$disabled = 'DISABLED';
		}
	
		if ( $status == 'Draft' ){
			$readonly_draft = 'READONLY';
		}
		
		if($approval_status=='Rejected'){
			$readonly = 'READONLY';
		}		
	
		if($del == 'Y'){
			$readonly = 'READONLY';
		}
		
		$readonlyf = '';
		$readonly1 = 'READONLY';
		if ( $status=='Submitted' || $status=='Completed' || $tally_status == 'C' || $tally_status =='U' ){
			//$accountant_role=='Y' && || $status=='Submitted'
			$readonly1 = '';
			$readonlyf = 'READONLY';
		}
		if ($accountant_role=='M' || $accountant_role=='Y' ){
			$readonly1 = '';
		}
		
		$sql 	= "SELECT * FROM payment_header a, `payment_details` b WHERE supp_id = '$si_id' and b.payment_hdr_id = a.id and a.st_flag = 'S' and del !='Y' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
		    $readonly1 = 'READONLY';
		}
		
		if ( $status=='Draft' ){
			$readonly1 = '';
			$readonly = '';
		}
		
		if($role=='Journal F&A'){
			$readonlyf ='';
		}

 /* if($si_id==42 || $si_id==57){
	$readonly1 = '';
}  */
		//$readonly = '';

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
             <?= $sub_menu;?>
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> <?= $sub_menu;?></a></li>
            
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->

					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
					<?php
						$status   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
						
						if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active_tab1 = '';
							$active_tab2 = '';
							$active_tab5 = '';
						}
						if ($_GET['fupd']){
							$active8 = '';
							$active_tab1 = '';
							$active_tab2 = '';
							$active_tab5 = '';
							$active_tab3 = 'active';
						}
					?>
					<?php if($approval_status=='Rejected'){ ?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $approval_status;?></b></h4> </span>
					<?php }
					else {
					?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $stats .' ' .$status;?></b></h4> </span>
					<?php } ?>
					
					<input type="hidden" name="status" id="statuS" value="<?php echo $status;?>" >
					<input type="hidden" name="page"  value="<?php echo $page;?>" >
					
				<?php 
					$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
				?>	
					<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		      
                    <input type="hidden" name="id" value="<?php echo $row['id'];?>">
			
				<?php

								$our_po_ref_no = $row['our_po_ref_no'];
								if($against_po_flag=='N'){
									$our_po_ref_no ='';	
								}
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$advance_paid_amount	= $r1['paid_amount'];
								$total_po_amount		= $r1['total_po_amount'];
								$paid_against_invoice	= $r1['paid_against_invoice'];
								$location 			= $r1['location'];
								$our_po_ref_no_a  	= $r1['po_number'];
								$po_rev			 	= $r1['po_rev'];
								
								$po_rev_a 	=='';
								if($po_rev>0){
									$po_rev_a 	= '-'.	$po_rev;
								}
								
								if(!empty($our_po_ref_no)){
									$sql = " select * from sma_location where id = '$location' ";
									$q2  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_object($q2);
									$loc_name 	= $r2->loc_name;
									$loc_gst_no 	= $r2->loc_gst_no;
								}
//echo $status ."<BR>";								
				?>
							
			<ul class="nav nav-tabs">
				  <li class="<?php echo $active_tab1; ?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Supplier Invoice</a></li>
				  
				<!--	<li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >A/c Journal</a></li> -->
				  <li class="<?php echo $active_tab3; ?>"><a href="#tab_3" data-toggle="tab" id="third_tab" >Document</a></li>
				  <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
			<?php if( $status != 'Draft' ){	?>	
					<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
			<?php } ?>
				
				  <li><a href="purchase_voucher.php?sub=pdf&id=<?php echo $row['id']; ?>&company_id=<?php echo $company_id?>&vendor_id=<?php echo $vendor_id;?>" class="btn btn-danger" target="_blank" >Voucher</a></li>
				  
				  <?php 		
					$sql ="SELECT * FROM `sma_supplier_invoice`
								WHERE our_po_ref_no = '$our_po_ref_no' and del !='Y' and status in ( 'Submitted','Completed' , 'Draft' ) ";
					$q21  = mysqli_query($con, $sql);
					$rowaffect_si = mysqli_affected_rows($con);
					if($rowaffect_si>0){
				?>						
				  <li><a href="#tab_10" data-toggle="tab" class="btn btn-info" >Invoice Against PO</a></li>
				<?php  } ?>	
				  
				  <?php 		
					$sql = "SELECT b.*, c.*  FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y'  ";
					
					$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c where a.po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and b.paid_to = '$vendor_id' ";
					
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
					$q21  = mysqli_query($con, $sql);
					$rowaffect_po = mysqli_affected_rows($con);
					if($rowaffect_po>0){
				?>						
				  <li><a href="#tab_6" data-toggle="tab" class="btn btn-info" id="six_tab" >Advance Payment</a></li>
			<?php  } ?>	  	
			<?php 
					$sql ="SELECT b.*, c.*
									FROM `sma_supplier_invoice` a, payment_header b, payment_details c 
									WHERE c.supp_id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' 
									and b.del !='Y' and a.status = 'Completed' ";
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect_si = mysqli_affected_rows($con);
							if($rowaffect_si>0){
					?>
				  <li><a href="#tab_7" data-toggle="tab" class="btn btn-info" id="seven_tab" >SI Payment</a></li>
			<?php  } ?>	
			
			</ul>
				
		<div class="tab-content">
				
			<div class="tab-pane <?php echo $active_tab1;?>" id="tab_1">
					
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo $row['id'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Created Date</label>
									<input type="text" class="form-control" id="created_date" <?php echo $disabled; ?> name="created_date" readonly value="<?php echo date('d-m-Y', strtotime($row['created_date']));?>" >
									
							</div>	
					<?php
					    if($status=='Draft'){
					        $sqlk = " and comp_id in ($comid) ";
					    }     
					   // echo $row['company_id'].' ';
					?>
							<div class="col-sm-4">
								<input type="hidden" name="comp_id" id="comp_id" readonly value="<?php echo $row['company_id'];?>" >
								<input type="hidden" id="company_ID" readonly value="<?php echo $row['company_id'];?>" >
								<input type="hidden" id="suplier_NAME" readonly value="<?php echo $row['suplier_name'];?>" >
							
								<label for="company_id" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" disabled required <?php echo $readonly. ' ' . $disabled; ?> <?php echo $readonly_draft; ?> >
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>	
								<?php $sql = "select * from company where 1 $sqlk order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-4">
								<?php 
									$party_id_doc = $row['suplier_name'];
									$sqla = '';
									if ( $readonly || $readonly_draft ){
										$sqla = " and id = '$party_id_doc' ";
									}
									$supplier_location = $row['supplier_location'];	
									$party_id = $row['suplier_name'];
									
									$sql = "select * from sma_party_mst where 1 and id = '$party_id_doc' ";
									$q2 	  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$tax_category 	= $r2['tax_category'];
									$party_gst_number 	= $r2['party_gst_number'];
											
									if(substr($loc_gst_no,0,2)== substr($party_gst_number,0,2)){
										$supplier_location = 'L';	
										$sql = "UPDATE sma_supplier_invoice SET supplier_location = 'L' WHERE id = '$si_id' ";
										mysqli_query($con, $sql);										
									}
									else {	
										$supplier_location = 'O';
										$sql = "UPDATE sma_supplier_invoice SET supplier_location = 'O' WHERE id = '$si_id' ";
										mysqli_query($con, $sql);
									}
								?>
								
								<input type="hidden" name="tax_category" id="tax_category"  readonly value="<?php echo $tax_category;?>" >
								
								<input type="hidden" name="suplier_name" id="suplier_name"  readonly value="<?php echo $row['suplier_name'];?>" >
							
								<label class="control-label">Supplier Name <span style="color:red;"> **</span></label>
								<select class="form-control" required disabled <?php echo $readonly_draft; ?> onchange="getporefno(this.value); getstate(this.value)">
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>	
										<?php $sql = "select * from sma_party_mst where 1 $sqla order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>

						<div class="form-group">
						
							
							<span id="getporefno">
							<div class="col-md-2">
								<label class="control-label">Supplier GST No.</label>
								<input type="text" class="form-control" readonly id="supplier_gst_no" name="supplier_gst_no" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?= $party_gst_number; ?>" >
							</div>
								
							<div class="col-md-3">
								<label class="control-label">Our PO Ref.No.<span style="color:red;"> **</span></label>
								
									<input type="hidden" class="form-control" id="our_po_ref_no" name="our_po_ref_no" readonly value="<?php echo $our_po_ref_no;?>" >
									<input type="text" class="form-control" id="our_po_ref_no_a" name="our_po_ref_no_a" readonly value="<?php echo $our_po_ref_no_a . $po_rev_a;?>" >
								
							</div>
						<?PHP
							/* $our_po_ref_no 	= $row['our_po_ref_no'];
								$our_pr_no		= $row['our_pr_no'];
								if($against_po_flag=='N'){
									$our_po_ref_no ='';	
								}
								 */
						 		$sql  = " SELECT * from sma_purchase_req where id = '$our_pr_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$pr_number		= $r1['pr_number'];
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$advance_flag		= $r1['advance_flag'];
								
							if($advance_flag=='Y'){
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$approval_memo_ref		= $r1['approval_memo_ref'];
								$advance_paid_amount	= $r1['paid_amount'];
								$total_po_amount		= $r1['total_po_amount'];
								$paid_against_invoice	= $r1['paid_against_invoice'];
								$location 			= $r1['location'];
								$our_po_ref_no_a  	= $r1['po_number'];
								$po_rev			 	= $r1['po_rev'];
								$po_draft_by		= $r1['draft_by'];
							}
							else {
								$sql = "SELECT sum(c.payment_adjusted ) as paid_amount 
											FROM `sma_advance` a, payment_header b, payment_details c 
												WHERE a.po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id 
													AND b.st_flag = 'D' and b.del !='Y' and a.del !='Y' 
														AND a.status = 'Completed' ";
									$res = mysqli_query($con, $sql);
									$dep = mysqli_fetch_array($res);
									$advance_paid_amount = round($dep['paid_amount'],0);
									
							}	
							
							if(empty($pr_number)){	
							    
							    $sql  = " SELECT * from sma_approval_memo where id = '$approval_memo_ref' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$against_indent_no		= $r1['against_indent_no'];
								
								$sql  = " SELECT * from sma_purchase_req where id = '$against_indent_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$pr_number		= $r1['pr_number'];
							}
							
						?>	
													
						<?php	if($advance_paid_amount>0){ ?>
							<div class="col-md-2">
								<label class="control-label">Advance Paid against PO</label>
								<input type="text" class="form-control" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> style="text-align:right;" value="<?= $advance_paid_amount; ?>" >
							</div>
						<?php } ?>
						
							</span>

							<span id="getprrefno">
							<div class="col-md-3">
									<label class="control-label">MRN.No.</label>
									<input type="hidden" class="form-control" id="our_pr_no" name="our_pr_no" value="<?= $our_pr_no;?>"  >
									<input type="text" class="form-control" readonly value="<?= $pr_number;?>" >
							</div>
							</span>
							
							
						</div>
						
							<?php 
								$company_id = $row['company_id'];
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2 	= mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$party_id_doc = $row['suplier_name'];
								$sqla = '';
								if ($readonly || $readonly_draft){
									$sqla = " and comp_id = '$company_id' ";
								}	
								
							?>
								
						<div class="form-group">
						
						<span id="getcreditdays">
							
					<?php	if(!empty($our_po_ref_no)){ ?>	
							<div class="col-md-2">
								<label class="control-label">Location</label>
								<input type="text" class="form-control" readonly  value="<?= $loc_name; ?>" >
							</div>
					<?php } ?>
					
							<input type="hidden" id="location_id" name="location_id" value="<?= $location; ?>" >
							
							<div class="col-md-3">
							<?php
								$department = $row['department'];
								$sqla = '';
								if ($readonly || $readonly_draft){
									$sqla = " and id = '$department' ";
								} 
							?>
								<input type="hidden" name="department" id="department" readonly value="<?php echo $row['department'];?>" >
								
								<label class="control-label">Department <span style="color:red;"> **</span></label>
								<select class="form-control" required disabled <?php echo $readonly. ' ' . $disabled; ?> <?php echo $readonly_draft; ?> >
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>
										<?php $sql = "select * from sma_department where 1 $sqla order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php
							
							if($supplier_location=='L'){
								$supplier_loc= 'Local';
							}
							else if($supplier_location=='O'){
								$supplier_loc= 'Out of State';
							}
							?>
							<div class="col-md-2">
								<label class="control-label">Tax Status</label>
								<input type="hidden" class="form-control" id="supplier_location" name="supplier_location"  value="<?= $supplier_location; ?>" >
								<input type="text" readonly class="form-control" value="<?= $supplier_loc; ?>" >	
							</div>
							
							
							<div class="col-md-1">
								<label class="control-label">Credit&nbsp;Days</label>
								
								<input type="text" class="form-control" id="credit_days" name="credit_days"  style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
								
							</div>
							
							</span>
							
						<?php   $trans_type = $row['trans_type'];
						        $trans_type = 1;
						        
						?>
						
							<?php //echo  $status. "><";
								if ( $status=='Draft' ){
										$readonly = '';
										$readonly_draft='';
								}
								$supplier_invoice_no = $row['supplier_invoice_no'];
								
								$dup_invoice='';
								$sql = " SELECT * FROM `sma_supplier_invoice` where trim(supplier_invoice_no) = trim('$supplier_invoice_no') and suplier_name = '$party_id' and company_id = '$company_id' and del !='Y' ";
								mysqli_query($con, $sql);
								$invoice_cnt = mysqli_affected_rows($con);
								if($invoice_cnt>1){
									$dup_invoice='Invoice Number is duplicate...';	
								}	
							?>
							<div class="col-md-3">
								<label class="control-label">Tax Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no"  <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo $row['supplier_invoice_no'];?>" onblur="checkinvoice_no(this.value);" >
								<span style='color:red;text-weight:bold;'><?= $dup_invoice;?></span>
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date"  <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo date('d-m-Y', strtotime($row['invoice_date']));?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<?php 
								$company_idd = $row['company_id'] ; 
								
								$credit_days = $row['credit_days'];
								
								if($credit_days>0){
									$due_date =  date('d-m-Y', strtotime("$credit_days day",strtotime($row['invoice_date'])));
								}
								else {
									$due_date =  date('d-m-Y', strtotime($row['due_date']));
									$due_date='';
								}
								
								$due_date =  date('d-m-Y', strtotime($row['due_date']));
								if($due_date=='01-01-1970' || $due_date=='31-12-1969'){$due_date='';}
								
							?>
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
								<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="due_date"  name="due_date" <?php echo $readonly . ' ' . $disabled; ?> <?php echo $readonly_draft; ?> value="<?php echo $due_date;?>" >
								
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
					<?php
					
						$sql = "SELECT b.* FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status = 'Completed'  ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$utr_no 	= $r2['utr_no'];
							$paid_date 	= $r2['paid_date'];
						$pstat ='';	
						if(!empty($utr_no)){
							$pstat = 'Paid';
						}
							
					?>
						
					</php
						
							$delivery_date = date('d-m-Y', strtotime($row['delivery_date']));
							if($delivery_date == '01-01-1970'){
								$delivery_date ='';
							}	
							
						    $invoice_received_date = date('d-m-Y', strtotime($row['invoice_received_date']));
							if($invoice_received_date == '01-01-1970'){
								$invoice_received_date ='';
							}	
							
						?>
						
							<div class="col-md-2">
									<label class="control-label">Invoice Received Date</label>
									<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control"   id="invoice_received_date" name="invoice_received_date" value="<?= $invoice_received_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
						</div>	
						
						
												
						<div class="form-group">	
							<div class="col-md-10">
								<label class="control-label">Remarks </label>
								<input type="text" class="form-control" <?= $readonly; ?>  id="additional_remarks"  name="additional_remarks"  value="<?= $row['additional_remarks'];?>" >
							</div>
						
						</div>
						
						<div class="form-group col-md-12" id="checkinvoice_no" style="color:red;" >
								
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Delivery Challan No. <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" <?= $readonly; ?>  id="delivery_challen_no"  name="delivery_challen_no" placeholder="" value="<?= $row['delivery_challen_no'];?>" >
							</div>
							
							<div class="col-md-2">
									<label class="control-label">Delivery Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" <?= $readonly; ?>  id="bill_date" name="delivery_date" value="<?= $delivery_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mode of Delivery <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" <?= $readonly; ?>  id="delivery_mode"  name="delivery_mode" placeholder="" value="<?= $row['delivery_mode'];?>" >
							</div>
					<?php if(!empty($pstat)){ ?>
							<div class="col-md-1">
								<label  class="label label-success" style="font-size:14px;" ><?= $pstat;?></label>
							</div>
					<?php } ?>
						</div>
						
						<?PHP

				$deduction1_amount = $row['deduction1_amount'];
				$deduction2_amount = $row['deduction2_amount'];
				$deduction3_amount = $row['deduction3_amount'];
				$deduction4_amount = $row['deduction4_amount'];
				$deduction5_amount = $row['deduction5_amount'];
				
				$deduction1_id 		= $row['deduction1_id'];
				$deduction2_id 		= $row['deduction2_id'];
				$deduction3_id 		= $row['deduction3_id'];
				$deduction4_id 		= $row['deduction4_id'];
				$deduction5_id 		= $row['deduction5_id'];
				if($deduction1_amount>0 && ($deduction1_id==0 || empty($deduction1_id) ) ){
					$errmsg1 = "Deduction not selected !";	
				}
				if($deduction2_amount>0 && ($deduction2_id==0 || empty($deduction2_id) ) ){
					$errmsg2 = "Deduction not selected !";	
				}
				if($deduction3_amount>0 && ($deduction3_id==0 || empty($deduction3_id) ) ){
					$errmsg3 = "Deduction not selected !";	
				}
				if($deduction4_amount>0 && ($deduction4_id==0 || empty($deduction4_id) ) ){
					$errmsg3 = "Deduction not selected !";	
				}
				if($deduction5_amount>0 && ($deduction5_id==0 || empty($deduction5_id) ) ){
					$errmsg3 = "Deduction not selected !";	
				}
				
					$total_amount 	= $row['total_amount'];
					$bal_amount 	= $row['bal_amount'];
					$payable_amount = $row['payable_amount'];
					
				
			?>	
			
			<?php 
							
							$paid_amount = 0;
							
							if($status=='Completed' || $status=='Draft' || $status=='Submitted'){	
								if($bal_amount<=1){
									$paid_amount = $row['payable_amount'];
								}
								else if ($bal_amount>0){
									$paid_amount 	= $payable_amount - $bal_amount;
									$balance_amount = $payable_amount - $paid_amount;
								}
								else if ($bal_amount==0){
									$paid_amount = $payable_amount;
								}
								
								
							}		
			?>
						
						
							
						<div class="form-group">	
						<?php
							
							if($total_amount>0){
						?>
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo $total_amount;?>" >
							</div>
														
							<div class="col-md-2">
								<label class="control-label">Paid Amount</label>
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo $paid_amount;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Balance Amount</label>
								<input type="text" class="form-control"  style="text-align:right;" readonly value="<?php echo $balance_amount;?>" >
								
							</div>
							
						<?php }	?>
				
								<?php 
								
									$our_po_ref_no = $row['our_po_ref_no'];
									
									$sql = "SELECT * FROM sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no'  ";
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$po_id = $r2['id'];
									$approval_memo_ref = $r2['approval_memo_ref'];
									$location	 			= $r2['location'];
									$comp_id		   = $r2['project'];
									
									$sql="SELECT * FROM sma_approval_memo where id='$approval_memo_ref' ";
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$ap_id = $r2['id'];
									
									$sql = "select * from payment_details a, payment_header b where 1 and b.del !='Y' and b.id = a.payment_hdr_id and b.st_flag = 'D' and a.supp_id = '$our_po_ref_no' ";
						//echo $sql;
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$py_po_id = $r2['payment_hdr_id'];
									
									$sql = "select * from payment_details a, payment_header b where 1 and b.del !='Y' and b.id = a.payment_hdr_id and b.st_flag = 'S' and a.supp_id = '$id' ";
						//echo $sql;
						
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$py_id = $r2['payment_hdr_id'];
									
										
										$baseurl_py_po = $baseurl . "payment/edit.php?sub=edit&id=$py_po_id&st_flag=D";
										
										$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										
										$ap_id = $ap_id;
										$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";
									
										$baseurl_py = $baseurl . "payment/edit.php?sub=edit&id=$py_id&st_flag=S";
									
										//$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location&r=1&print_flag=V";
										
										$company_id = $row['company_id'];
										$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id;&r=1";
										
										$baseurl_powf = $baseurl . "purchase_order/po_wf_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
									
										$sql = "SELECT * FROM sma_ipc where sma_invoice_no = '$id' and del!='Y' order by id desc ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$ipc_id = $r2['id'];
										$compid	= $r2['sma_comp_id'];
									
										$ipc_id = $ipc_id;
										//$baseurl_ap = $baseurl . "grnsrn/edit.php?sub=edit&id=$srn_id";
										$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
									
									?>
								
									<div class="col-md-12">
										<label class="control-label" style="font-size:14px;" >Document : </label>
									<?php // if(!empty($ap_id)){ ?>	
									<!--		<a href="<?php echo $baseurl_ap; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Approval Memo</span></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
									<!--	<a href="<?= $baseurl_apvw;?>" target="_blank" ><span class="label label-success" style="font-size:14px;" >Approval Memo View </span></a>&nbsp;&nbsp;
									-->
											
									<?php //} ?>		
									<?php if(!empty($po_id)){ 
									
											$baseurl_apvw = $baseurl.'purchase_order/'."po_approval_notes_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&r=1";
											
									?>	
											
											<a href="<?php echo $baseurl_po; ?>" target ="_blank"><span class="label label-success" style="font-size:14px;" >Purchase Order</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
											
									<?php 	
										$supplier_invoice_no = $row['supplier_invoice_no'];
									 }
									
									$sql ="SELECT distinct(b.id) as pay_no, st_flag, our_po_ref_no as po_no, b.utr_no FROM `sma_supplier_invoice` a, payment_header b, payment_details c where our_po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status in ('Submitted', 'Completed')  ";
						//echo $sql. "<BR>";		//and b.utr_no !=''
									$q2  = mysqli_query($con, $sql);
									$affectrow  = mysqli_affected_rows($con);
									if($affectrow>0){
										//echo "<BR>";	
										echo "<span class='label label-warning' style='font-size:14px;' > Payment</span>-";
									}	
									while($r2  = mysqli_fetch_array($q2)){
											
										$pay_no	= $r2['pay_no'];
											
										$baseurl_py = $baseurl . "payment/edit.php?sub=edit&id=$pay_no";
										echo "<a href='$baseurl_py;' target='_blank'><span class='label label-warning' style='font-size:14px;' >".$pay_no .' </span></a>&nbsp;&nbsp;';
											
									}
									
								if($py_po_id>0){	
									echo "<a href='$baseurl_py_po;' target='_blank'><span class='label label-warning' style='font-size:14px;' >Advance: ".$py_po_id .' </span></a>&nbsp;&nbsp;';	
								}		
							?>
									
									</div>
							</div>
							
						<?php
						
							$amount_paid_po = 0;
							$sql ="SELECT b.id as pay_no, st_flag, our_po_ref_no as po_no, sum(c.payment_adjusted) as amount_paid_po, b.utr_no FROM `sma_supplier_invoice` a, payment_header b, payment_details c where our_po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";
			//echo $sql. "<BR>";				
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$amount_paid_po = $r2['amount_paid_po'];
		//echo $amount_paid_po . ' ####1 ' . "<BR>";					
							$sql = "SELECT b.id as pay_no, st_flag, a.id as po_no, sum(c.payment_adjusted) as amount_paid_po, b.utr_no as utr_no_po, b.paid_date as paid_date_po  FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$amount_paid_po = $amount_paid_po + $r2['amount_paid_po'];
		//echo $sql. "<BR>";				
							//echo $amount_paid_po;
		//echo $amount_paid_po . ' #####2 ' . "<BR>";					
							if($amount_paid_po>0){
						?>
						
							<div class="form-group">
								<label class="col-md-2 control-label">Payment Against PO</label>
								<div class="col-md-2">
									<span id="getcreditdays">
									<input type="text" class="form-control"  readonly style="text-align:right;font-weight: bold;" value="<?php echo number_format($amount_paid_po,0); ?>" >
									</span>
								</div>
							
							<?php 
								$paid_date = date('d-m-Y', strtotime($paid_date));
								if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
									$paid_date='';
								}
								
								if(empty($utr_no)){
									$utr_no = 'Unpaid';
								}
								
							   if(!empty($utr_no_po)){
									
									$paid_date = date('d-m-Y', strtotime($paid_date_po));
									if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
										$paid_date='';
									}
									$utr_no = 'Paid';
								}
							?>	

								<label class="col-md-2 control-label">Paid/Chq.Date</label>
								<div class="col-md-2">
									<input type="text" class="form-control" readonly style="text-align:right;font-weight: bold;" value="<?php echo $paid_date; ?>" >
								</div>

								<label class="col-md-2 control-label">Payment Confirmation</label>
								<div class="col-md-2">
									<input type="text" class="form-control" readonly style="text-align:right;font-weight: bold;" value="<?php echo $utr_no; ?>" >
								</div>
								
							</div>
						
						<?php } ?>
						
						<div class="form-group">
						<?php	
							$advance_adjust = $row['advance_adjust']; 
							if(empty($advance_adjust)){
								$advance_adjust = $advance_paid_amount;
							}
						?>
						
							<div class="col-md-2">	
								<label class="control-label">Advance Adjust </label>
								<input type="text" class="form-control" style="text-align:right;" <?= $readonly_r;?> id="advance_adjust"  name="advance_adjust"  value="<?= $advance_adjust;?>" >
							</div>
						
						</div>
						
<!-- Product Start -->
					<div class="box123">
                                    <div class="box-header">
                                        <h4 class="box-title">Product Details</h4>
                                    
                                    </div>
						<?php
							$berrmsg ='';
							if($_GET['A98'] ){
								$matid 		= $_GET['matid'];
								$budgetid 	= $_GET['budgetid'];
								$sql = "select * from sma_product where id = '$matid'";
								$r2 = mysqli_query($con, $sql);
								$r1 = mysqli_fetch_array($r2);
								$material_name = $r1['name'];
								
								$sql = "select * from sma_budget where id = '$budgetid'";
								$r2 = mysqli_query($con, $sql);
								$r1 = mysqli_fetch_array($r2);
								$budget_head = $r1['budget_head'];
								$budget_name = $r1['budget_name'];
								
									
								$sql = "select * from sma_budget_subgroup where id = '$budget_head'";
								$r2 = mysqli_query($con, $sql);
								$r1 = mysqli_fetch_array($r2);
								$budget_head = $r1['budget_head'];
								
								
						if($_GET['A98']== '97' ){	
						?>
								<label class="control-label" style="color:red;text-align:left" >SI Quantity should not be greater the PO Quantity !!! <BR> for Product : <?= $material_name; ?> and for Cost Center : <?= $budget_head; ?></label>
						<?php }	else 		
							if($_GET['A98']== '99' ){	
						?>
								<label class="control-label" style="color:red;text-align:left" >SI Value should not be greater than PO Value !!! <BR> for Product : <?= $material_name; ?> and for Cost Center : <?= $budget_head; ?></label>
						<?php }	else if($_GET['A98']== '98' ){
						?>
								<label class="control-label" style="color:red;text-align:left" > Budget 100% reached, please increase Budget !!! <BR> for Cost Center: <?= $budget_head; ?> and Product <?= $material_name; ?> </label>
						<?php }
						
							}
						?>
                                <div class="box-body">
                                        <!--<table id="prItemsTable" class="table table-bordered table-striped">-->
										<table id="prtable123" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                
                                                <th>Product Name</th>
												<th>A/c JV Name</th>
                                                <th>Description</th>
                                                <th style="text-align:right;">PO.Qty</th>
												<th style="text-align:right;">Received Qty/ Value</th>
										<!--		<th style="text-align:right;">Bal.Qty</th>-->
                                                <th>Unit</th>
                                                <th style="text-align:right;" >Rate</th>
												
												<th style="text-align:right;">GST%</th>
                                                <th style="text-align:right;" >Amount</th>
												
												<th style="text-align:center;" >Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
											//echo $user. "<<>>";
											$readonlyBill = '';
												
												$tot_amount =0;
												$si_hdr_id = $row['id'];
												

												$sql="SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' and qty > 0 ";
												$result 	= mysqli_query($con, $sql);
												$items_cnt = mysqli_affected_rows($con);
												//$items_cnt = 1;
												$poerrmsg ='';
												$sql = "SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' order by si_srno desc";
											//echo $sql;	
												$result = mysqli_query($con, $sql);
												;
												echo mysqli_error($con);
												$value="";
												$amount_dr_without_gst =0;
						//si_hdr_id, material_id, description, budget_id qty, rate, gst, unit, 						
												while($rowd = mysqli_fetch_array($result)){
													$po_qty 	= $rowd['po_qty'];
													$qty 	= $rowd['qty'];
													$rate 	= $rowd['rate'];
													$gst	= $rowd['gst'];
													
													$gstamt = round((($qty * $rate) * $gst / 100),0);
													$amount = round(($qty * $rate) + $gstamt,0);
													
													$tot_amount = $tot_amount + $amount;
												
													$amount_dr_without_gst += ($qty * $rate);
													
													$bal_qty = 0;
													if($po_qty>0){
														$bal_qty = $po_qty - $qty;
													}
													$rid = $rowd['si_srno'];
													$material_id = $rowd['material_id'];
													
													$sql = "select * from sma_product where id = '$material_id' ";
													$q2  = mysqli_query($con, $sql);													
													$r2 = mysqli_fetch_object($q2);
													$material_name = $r2->name;
													$category = $r2->category;
													$unit = $r2->uom;
													
										//validate PO value with SI total value		
													$sql = "SELECT product_id , round(sum((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100)),0) as po_total_value FROM `sma_po_items` where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$cat = mysqli_fetch_array($res2);
													$po_total_value 			= $cat['po_total_value'];
													
													$sql = "SELECT material_id , round(sum((qty * rate) + ((qty * rate) * gst / 100)),0) as si_total_value FROM `sma_supplier_invoice` a, sma_supplier_invoice_details b WHERE 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$our_po_ref_no' and material_id = '$material_id' and a.approval_status !='Rejected' and a.id != '$si_hdr_id' ";
//echo $sql ."<BR>";													
													$q2  = mysqli_query($con, $sql);													
													$r2 = mysqli_fetch_object($q2);
													$si_total_value = $r2->si_total_value;
													
								//	echo $po_total_value. ' <<>> ' .	$si_total_value."<BR>";	
													
													if($si_total_value> $po_total_value && $status =='Draft' ){
														$poerrmsg = "Error#: Product : ". $material_name ." - SI value exceed to PO Value";
														echo  "<span style='color:red;'>$poerrmsg</span><BR>" ;
													}	
												
											//To check SI value should not over to PO Value. || $status =='Submitted'	
												if($status=='Draft' ){
													$sql = "SELECT material_id , round(sum((qty * rate) + ((qty * rate) * gst / 100)),0) as si_total_value FROM `sma_supplier_invoice` a, sma_supplier_invoice_details b WHERE 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$our_po_ref_no' and material_id = '$material_id' and a.approval_status !='Rejected' ";//and a.id = '$si_hdr_id' 
//echo $sql ."<BR>";													
													$q2  = mysqli_query($con, $sql);													
													$r2 = mysqli_fetch_object($q2);
													$si_total_value = $r2->si_total_value;
													
									//echo $po_total_value. ' <<>> ' .	$si_total_value."<BR>";	
													
													if($si_total_value> $po_total_value ){
														$poerrmsg = "Error: Product : ". $material_name ." - SI value exceed to PO Value";
														echo  "<span style='color:red;'>$poerrmsg</span><BR>" ;
													}
												}	
													
									//echo $sql. "<BR>";
										//validate PO value with SI total value		
										
													$budget_id = $rowd['budget_id'];
													$sql = "SELECT * FROM sma_budget where id = '$budget_id' ";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$cat = mysqli_fetch_array($res2);
													$costcenter_name = $cat['budget_head'];
													
													$total_budget 		= $cat['total_budget'];
													$adjustment_budget 	= $cat['adjustment_budget'];
													$bal_budget			= $total_budget + $adjustment_budget;
													
													$berrmsg = ''; 
													$stly = ''; 
													if($bal_budget==0){
														$berrmsg = '<b> ->Budget not available !!!</b>';
														$stly = 'style="color:red;"';
													}
													
													$sql = "select * from sma_budget_subgroup where id = '$costcenter_name'";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$costcenter_name = $r1['budget_head'];
													
												$sql = "SELECT * FROM `sma_po_items` where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
												$res2 = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$cat = mysqli_fetch_array($res2);
												$poqty 		= $cat['quantity'];
												$bal_poqty 	= $cat['bal_si_qty'];
												$bal_si_amount	= $cat['bal_si_amount'];																									  
												$tds 		= $cat['tds'];
												$tds_id 	= $cat['tds_id'];
												
												$sql = " UPDATE sma_supplier_invoice_details SET tds = '$tds', tds_id = '$tds_id', amount = '$amount' WHERE si_srno = '$rid' ";
												mysqli_query($con, $sql);
								
													if($po_qty>0){
														$bal_qty = round($poqty - $bal_poqty,2);
													}
													
													$si_qty = $rowd['qty'];
													if($si_qty==0){
														//$si_qty = $cat['bal_si_qty'];
													}	
													
												?>	
													<tr>
														<td width='15%'><?php echo $material_name?></td>
														<td width='10%' <?= $stly; ?>><?php echo $costcenter_name . ' '. $berrmsg; ?></td>
														<td width='20%'><?php echo $rowd['description']?></td>
														<td width='08%' style="text-align:right;"><?php echo number_format($rowd['po_qty'],4)?></td>	
														<td width='10%' style="text-align:right;"><?php echo number_format($si_qty,4);?></td>
														<td width='6%'><?php echo $unit?></td>	
														<td width="8%" style="color:black;text-align:right;" border='1'  contenteditable123<?= $readonlyBill;?>="true" 
															onBlur="saveToDatabase_ln123(this,'rate','<?= $si_hdr_id; ?>', '<?= $rid; ?>');" ><?php  echo $rowd['rate']?>
														</td>
														<td width='6%' style="color:black;text-align:right;" border='1'  contenteditable123<?= $readonlyBill;?>="true" 
																		onBlur="saveToDatabase_ln123(this,'gst','<?= $si_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $rowd['gst']?></td>
														<td width='10%' style="text-align:right;"><?php echo number_format(round($amount,0),0)?>
														</td>
														
														<td width='10%'>
												<?php if (empty($readonly) && $approval_status!='Rejected' ){ ?>		
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
														<?php  include "edit_func.php"; ?>
												<?php } ?>	
<!-- Modal Edit Item-->									
														</td>
													</tr>
											<?php
												}
											?>		
                                            </tbody>
                        				
                                            <tfoot>
                                            <tr>
											
												<th width='15%'  colspan='6' ></th>
												
												<th width='10%' colspan='2'>Total Amount</th>
												<th width='10%' style="text-align:right;"><?php echo moneyFormatIndiaa(round($tot_amount,0),0);?></th>
											
											<th width='08%'></th>
										<input  type="hidden" id = "TOT_AMOUNT" value=<?php echo round($tot_amount,2);?> >
												
                                            </tr>
                                            </tfoot>
										</table>
										
									</div>

									<?php $checker_value = round($tot_amount,0); ?>
									<input type="hidden" id="checker_value" name="checker_value" value="<?= $checker_value;?>" >

								</div>
							
							
<!-- Product End -->		
					

<!--Deduction from Payment Start-->
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" class="btn btn-success" data-parent="#steps" href="#step2"><b style="color:white;"> Deduction from Payment</b></a></h4>
				</div>
							
				<?php
				    $sql = " SELECT * FROM `sma_retention_invoice` where 1 and dedtype = 'R' and si_hdr_id = '$si_id' ";
				    $q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$dedtype = $r2['dedtype'];
					$retention_id = $r2['id'];
				
				    $sql = " SELECT * FROM `sma_retention_invoice` where 1 and dedtype = 'C' and si_hdr_id = '$si_id' ";
				    $q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$dedtype = $r2['dedtype'];
					$compliances_id = $r2['id'];
				?>
						
                <div id="step2" class="panel-collapse collapse in">
					<div class="panel-body">	
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
							</div>	
							<div class="control-label col-md-2"><b>Amount &nbsp;</b></div>
							<div class="control-label col-md-2"><b>Paid &nbsp;</b></div>
							<div class="col-md-2"><b>Remarks</b></b></div>
						</div>
						
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">Retention </label>
							</div>	
							<div class="col-md-2">	
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="retention_amount"  name="retention_amount"  value="<?= $row['retention_amount'];?>" >
							</div>
						<?php	
                            
						    $sql = "SELECT b.* FROM `payment_header` a, payment_details b where 1 and del !='Y' and st_flag = 'R' and b.supp_id = '$retention_id' and a.id = b.payment_hdr_id ";
        			        $q2 	= mysqli_query($con, $sql);
        					$r2 	= mysqli_fetch_array($q2);
        					$payment_adjusted = $r2['payment_adjusted'];
        					$py_id            = $r2['payment_hdr_id'];
        					
        					$baseurl_py = $baseurl . "/payment/edit.php?sub=edit&id=$py_id";
        				    $bal_retention_amount = $row['bal_retention_amount'];
							$bal_retention_amount = $payment_adjusted;
						
						?>
							<div class="col-md-2">
								
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?= $bal_retention_amount;?>" >
							</div>
								
							<div class="col-sm-4">
								
								<input type="text" class="form-control" id="hold_remarks" name="retention_remarks" placeholder="" value="<?php echo $row['retention_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
					<?php if($py_id>0 ){ ?>			
							<div class="col-md-2">
								<label class="control-label">Paid</label>
								<a href="<?php echo $baseurl_py; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Payment - <?= $si_id; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
				    <?php } ?>			
						</div>
						
						<div class="form-group">		
							<div class="col-md-2">
								<label class="control-label">Compliances </label>
							</div>	
							<div class="col-md-2">	
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="compliances_amount"  name="compliances_amount"  value="<?= $row['compliances_amount'];?>" >
							</div>
						
						<?php	
						    $sql = "SELECT b.* FROM `payment_header` a, payment_details b where 1 and del !='Y' and st_flag = 'M' and b.supp_id = '$compliances_id' and a.id = b.payment_hdr_id ";
        			        $q2 	= mysqli_query($con, $sql);
        					$r2 	= mysqli_fetch_array($q2);
        					$payment_adjusted = $r2['payment_adjusted'];
        					$py_id            = $r2['payment_hdr_id'];
        					
        					$baseurl_py = $baseurl . "/payment/edit.php?sub=edit&id=$py_id";
        					
						//	$bal_compliances_amount = $row['bal_compliances_amount'];
							$bal_compliances_amount = $payment_adjusted;
						?>
							<div class="col-md-2">
								
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?= $bal_compliances_amount;?>" >
							</div>
								
							<div class="col-sm-4">
								
								<input type="text" class="form-control" id="hold_remarks" name="compliances_remarks" placeholder="" value="<?php echo $row['compliances_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
						<?php if($py_id>0 ){ ?>			
							<div class="col-md-2">
								<label class="control-label">Paid</label>
								<a href="<?php echo $baseurl_py; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Payment - <?= $si_id; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
				        <?php } ?>
						</div>
						
						<div class="form-group">		
							<div class="col-md-2">
								<label class="control-label">Recovery </label>
							</div>	
							<div class="col-md-2">		
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="recovery_amount"  name="recovery_amount"  value="<?= $row['recovery_amount'];?>" >
							</div>
						
						<?php	
							$bal_recovery_amount = $row['bal_recovery_amount'];
						?>
							<div class="col-md-2">
								
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?= $bal_recovery_amount;?>" >
							</div>
							
							<div class="col-sm-6">
								
								<input type="text" class="form-control" id="hold_remarks" name="recovery_remarks" placeholder="" value="<?php echo $row['recovery_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
							
						</div>
						
						<div class="form-group">	
							<div class="col-md-2">
							    
								<label class="control-label">Hold </label>
							</div>	
							<div class="col-md-2">	
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="hold_amount"  name="hold_amount"  value="<?= $row['hold_amount'];?>" >
							</div>
							
						<?php	
							$bal_hold_amount = $row['bal_hold_amount'];
						?>
						    <div class="col-md-2">
								
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?= $bal_hold_amount;?>" >
							</div>
							
							<div class="col-sm-6">
								
								<input type="text" class="form-control" id="hold_remarks" name="hold_remarks" placeholder="" value="<?php echo $row['hold_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-sm-4">
								<label for="approver" class=" control-label">TDS Account Name</label>                                        
								<select class="form-control select2" id="" name="deduction1_id" <?php echo $readonly1; ?>  >
								<option value="">Select</option>
								<?php
								$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'D' and tds_flag = 'Y' order by account_name ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								while($r3 = mysqli_fetch_array($result)){
								?>	
								<option value="<?php echo $r3['id']?>" <?php echo ($row['deduction1_id'] == $r3['id'])?'selected="selected"':'';?> ><?php echo $r3['account_name'] ?></option>
								<?php } ?>
								</select>
								<span style="color:red;"><?= $errmsg1; ?></span>
							</div>	
					<?php
						$ssp ='';
					    for($ik=0;$ik<35;$ik++){
					        $ssp .= '&nbsp;';
					        
					    }
					?>
							<div class="col-sm-2">
								<label><?= $ssp;?>TDS Amount</label>
								<input type="text" class="form-control" id="deduction1_amount" name="deduction1_amount" style="text-align:right;" placeholder="" value="<?php echo $row['deduction1_amount'];?>" <?php echo $readonly1; ?> >
							</div>
							
							<div class="col-sm-6">
								<label for="approver" class=" control-label">TDS Remarks</label>
								<input type="text" class="form-control" id="deduction1_remarks" name="deduction1_remarks" placeholder="" value="<?php echo $row['deduction1_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-4">
								<label for="approver" class=" control-label">TDS Account Name</label>                                        
								<select class="form-control select2" id="" name="deduction2_id" <?php echo $readonly1; ?>  >
								<option value="">Select</option>
								<?php
								$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'D' and tds_flag = 'Y' order by account_name ";
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								while($r3 = mysqli_fetch_array($result)){
								?>	
								<option value="<?php echo $r3['id']?>" <?php echo ($row['deduction2_id'] == $r3['id'])?'selected="selected"':'';?> ><?php echo $r3['account_name'] ?></option>
								<?php } ?>
								</select>
								<span style="color:red;"><?= $errmsg2; ?></span>
							</div>	
					<?php
						$ssp ='';
					    for($ik=0;$ik<35;$ik++){
					        $ssp .= '&nbsp;';
					        
					    }
					?>
							<div class="col-sm-2">
								<label><?= $ssp;?>TDS Amount</label>
								<input type="text" class="form-control" id="deduction2_amount" name="deduction2_amount" style="text-align:right;" placeholder="" value="<?php echo $row['deduction2_amount'];?>" <?php echo $readonly1; ?> >
							</div>
							
							<div class="col-sm-6">
								<label for="approver" class=" control-label">TDS Remarks</label>
								<input type="text" class="form-control" id="deduction2_remarks" name="deduction2_remarks" placeholder="" value="<?php echo $row['deduction2_remarks'];?>" <?php echo $readonly1; ?>  >
							</div>
						</div>
				
				<?php		
					
					$total_deduction = $row['retention_amount'] + $row['compliances_amount'] + $row['recovery_amount'] + $row['hold_amount'] + $deduction1_amount + $deduction2_amount;
				    $net_amount = $tot_amount - $total_deduction;
			//echo $tot_amount . ' -'. $total_deduction;
				    if($net_amount > 0 && $status=='Completed'){
						$sql = " update `sma_supplier_invoice` set total_amount = '$net_amount', payable_amount = '$net_amount', bal_amount = '$net_amount' where id = '$si_hdr_id' and paid_status != 'Paid' ";
						mysqli_query($con, $sql);
							
					}
							
						
				
				?>
					
					<table id="prtable123" class="table table-bordered table-striped">
					    <tfoot>
                                            <tr>
											
												<th width='60%'  ></th>
												
												<th width='23%' style="text-align:center;">Net Amount</th>
												<th width='10%' style="text-align:right;"><?php echo moneyFormatIndiaa(round($net_amount,0),0);?></th>
											
											<th width='10%'></th>
										<input  type="hidden" id = "NET_AMOUNT" value=<?php echo round($net_amount,2);?> >
												
                                            </tr>
                        </tfoot>
                        </table>
					
					<?php if( $approval_status!='Rejected' && $items_cnt > 0 && empty($poerrmsg) ){ ?>
							<span class='hidesend'>	
							<div class="col-sm-6 text-right">
								<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
							</div>	
							</span>	
					<?php 	}  ?>
					
					</div>
					
				
						
				</div>
			</div>
			
					<!--Deduction from Payment End-->
					
		<!--Tally Journal Start-->
		<?php 
	
		
		if ($accountant_role=='M' || $accountant_role=='Y' ){
			$disabled = "";
		}	
		
		if(!empty($py_id)){
			$disabled = "DISABLED"; 
		}	
			
		if( $tally_status == 'C' || $tally_status == 'U' ){
		    $disabled = "DISABLED";    
		}
		
		//echo $py_id. "<<>>";
		
		$accountant_role='Y';			
		
	//	if( ($accountant_role=='Y' || $accountant_role=='M') ){ 
		//&& ($status =='Completed' || $status=='Draft')
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" class="btn btn-info" data-parent="#steps" href="#step1"><b style="color:white;"> A/c Journal</b></a></h4>
				</div>

                <div id="step1" class="panel-collapse collapse in <?= $_GET['IN']?>">
					<div class="panel-body">
						<fieldset>
						<?php
							$gst_checked = '';
							$gst_flag = $row['gst_flag'];
							if($gst_flag =='Y'){
								$gst_checked = 'CHECKED';
							}
						?>		
							<!--<div class="form-group">-->
							<!--	<div class="col-md-6">-->
							<!--	<label class="control-label" style="text-align:left;">Post GST into Expense A/c associated with Product? No Reverse Credit:</label>-->
							<!--	<input type="checkbox" <?= $gst_checked ?> class="gst_FLAG" id="gst_FLAG" name="gst_flag" value="Y" >-->
							<!--	</div>-->
							<!--</div>		-->
						<div class="box-body">
							<div class="box-header">
                                        <h4 class="box-title">A/c Journal Account</h4>
							
								<?php if (empty($disabled)){ ?>
                                        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
										</span>	
								<?php  } ?>	
                                        
                                    </div>
                                    <div class="box-body">
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
								<?php if (empty($disabled)){ ?>	
										<span class="pull-right"><a href="#addLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addLine">Add </a></span>
								<?php  } ?>	
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="40%">Account Name</th>
													<th width="20%">Cost Center Name</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;">Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from tally_journal_entry where doc_no = '$si_id' and doc_type = 'SI' order by effect desc, record_id ";	
//echo $sql;			
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date	 	 	= $r2['doc_date'];
											$supp_invoice_no  	= $r2['supp_invoice_no'];
											$supp_invoice_date  = $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$budget_head  		= $r2['budget_head'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally 		= $r2['status'];
											
											$sql = "select * from sma_budget_subgroup where id = '$budget_head'";
											$r2 = mysqli_query($con, $sql);
											$r1 = mysqli_fetch_array($r2);
											$budget_head = $r1['budget_head'];
											
										if($account_type=='D'){
											$budget_head ='';
										}	
										
											$sql = "SELECT * from account_mst where id = '$account_id' ";
											$qry2 	= mysqli_query($con, $sql);
											$r22 	= mysqli_fetch_array($qry2);
											$account_type  = $r22['account_type'];

											$gstwd = strpos($account_name, "GST");
											
											$tdswd = strpos($account_name, "TDS");
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name; ?> </td>
												<td><?php echo $budget_head; ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
									<?php //if (account_type=='D' ){ ?>		
										<!--	<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp; -->
											
								<?php  	//	include "edit_tally_code_func.php"; 
								
										if ( (empty($disabled) && !empty($gstwd) && $account_type=='D') || (empty($disabled) && $account_type=='D' ) ){
								?>	
											<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>&amount=<?php echo $amount;?>&doc_no=<?php echo $doc_no ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
										
									<?php } ?>
												</td>
												
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = $amount_dr + $amount;
											}
											else if($effect=='Cr'){
												$amount_cr = $amount_cr + $amount;
											}

										}
										$emsg   ='';
										$stl	='';

										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
										$diff_amt = round($amount_dr - $amount_cr,0) ;
										
										echo '<input type="hidden" id="Mismatch_id" value="Y" >';
										if($diff_amt>0 || $diff_amt<0 ){
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
											
											echo '<input type="hidden" id="Mismatch_id" value="Y" >';
											
										}
										
										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
									?>
										<input type="hidden" id="amount_DR" value="<?php echo $amount_dr_without_gst;?>" >
										
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,2); ?> </td>
												<td></td>
											</tr>
											
								<?php	if($diff_amt>0 && $diff_amt<0 ){ ?>	
											<tr>
												<td></td>
												<td>Difference</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($diff_amt,2); ?> </td>
												<td></td>
											</tr>
								<?php 	}	?>

											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan='4'><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

								<div class="form-group">
									<?php 
									$tally_status = $row['tally_status'];
									//echo $tally_status. ' ' . $status;
									if ( $status == 'Completed' || $status == 'Submitted' ){ ?>
										<div class="col-sm-3">
											
										<?php 
										
											if($tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Created : </label>';
											}
											else if($tally_status == 'U'  ){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Synched :</label>';
											}
											else if($tally_status == 'C' ){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Checked :</label>';
											}
										
										if($tally_status == 'R' && $status == 'Completed' ){ 
										?>
										    <label for="tally_status" style="position: relative;top: -4px;" class="control-label">Sync to Tally? &nbsp;&nbsp;: </label>
										<?php
										}
										
//echo $tally_status.' && '. $accountant_role .' && '.$emsg ;
				
										if($tally_status=='R' && ( $accountant_role=='M' ||  $accountant_role=='Y' ) && empty($emsg) && $status == 'Completed' ){
										?>
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'C' || $tally_status == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="C" >
											
									<?php } 
											
										if( ($tally_status=='C' || $tally_status=='R' ) && $tally_access=='Y' && $status !='Completed'){ 
									?>
										    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" >
											
									<?php 
										}
									?>
										
										</div>
								<?php } ?>
									
									<?php 
										$tally_created_date_chk = date('d-m-Y', strtotime($row['tally_created_date']));
										if($tally_created_date_chk=='01-01-1970' || $tally_created_date_chk== '30-11--0001' || $tally_created_date_chk== '31-12-1969'){
											$tally_created_date ='';
										}
										else {
											$tally_created_date = date('d-m-Y h:m i', strtotime($row['tally_created_date']));
										}
										
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970' || $chk_date== '30-11--0001' || $chk_date== '31-12-1969'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y h:m i', strtotime($tally_updated_on));
										}	
									?>
										<div class="col-sm-2">
									<?php if(!empty($tally_updated_on)){ ?>
											<label for="tally_status" class="control-label">A/c updated on :<BR><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
									<?php 	}
										else if(!empty($tally_created_date)){
									?>
											<label for="tally_status" class="control-label">A/c created on :<BR><?php echo $tally_created_date; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
									<?php 	} ?>
									
											
										</div>
										<div class="col-sm-2">
											<label for="tally_narration" class="control-label">A/c Narration: </label>
										</div>	
										<div class="col-sm-5">	
											
											<textarea rows="4" cols="65" onBlur="saveToDatabase(this.value,'narration','<?php echo $si_id; ?>')" onClick="showEdit(this);" <?= $readonly; ?> name="tally_narration" id="tally_narration"  ><?php echo $row['tally_narration'];?></textarea>
											
										</div>
									</div>
									
								</div>
						
							</div>
								
						</div>
						
						</fieldset>
					</div>
				</div>
			
			</div>
		
		<?php //} ?>
		
		<!--Tally Journal Start-->

							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click');getvalidate();" >Next</a>
								</div>
							</div>	
							
							
		</div>
					
						<!-- Attachments - Upload Panel -->
        <div class="tab-pane <?= $active_tab3;?> " id="tab_3"  >
                            <!-- Attachments company_idd -->
					<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" style="font-weight:bold;" ><b>SI Number : <?php echo $si_id;?> 
					<?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
						$sql = "select * from sma_party_mst where 1 and id = '$party_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name	= $r2['party_name'];
						echo " Supplier Name : " . $party_name;
						
						$label_line = "<b>SI Number : " . $si_id. ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date'])). " Supplier Name : " . $party_name . "</b>";
					?>
						</b>	
					</span>		
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'SI' AND reference_id = " . $si_id;
							  
                              $docResults = mysqli_query($con, $sql);
                              $doc_cnt = mysqli_affected_rows($con);
                              
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="20%" >Description</th>
										  <th  width="20%">Share Point Link
										  
										  </th>
										  <th  width="30%" >File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  
													$delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
												// 	$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$si_id .'&fupd=987'.'&same_page='. $page; 
												 	
												// 	$delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . $baseurl1;
													$doc_desc = $docRow['doc_desc'];
													$doc_type = $docRow['doc_type'];
													$share_point_link = $docRow['share_point_link'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
													$dms_path = '';
													$dms_module = $docRow['dms_module'];
													if($dms_module=='IN'){
														$dms_path = $baseurl.'dms/';
														$doc_desc = 'Inward No:'.$docRow['doc_invoice_no'];
													}
													
					                              ?>
                                          <tr>
                                             <td width="20%" ><?php echo $document ?></td>
                                              <td width="20%"><?php echo $doc_desc ?></td>
											  <td width="20%"><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link; ?></a></td>
											  <td width="30%"><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
										<?php if ($user=='Admin' || $status == 'Draft'  || $status == 'Submitted' ){ ?>
                                              <td><a href="<?php echo $delDocUrl; ?>"  ><i class='fa fa-trash-alt'></i></a></td>
										<?php } ?>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						<?php //if (empty($readonly)){ ?>
						
						
						<span id="gegpartyDoc">
							
						</span>
						
						
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										    
										<td width="20%" >
                                            <select class="form-control select2 doctype" name="doctype[]"   >
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
                                            </select>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										
										<td width="30%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										
                                         <td width="10%" >
										<?php 
											if($status != 'Completed'){
										?>
											<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
										<?php } ?>
										 </td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						<?php
				          //  }
				        ?>	
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous </a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
					<div class="box-footer">
					
					<span id="predit"></span>
			
					<?php		
					
							echo '<div class="col-sm-12">	';
							//$checker_value = 100;
								$sql = " SELECT * FROM `sma_workflow` 
										where company_id = '$company_id'  and '$checker_value' <= to_value and '$checker_value' >= from_value 
										and doc_type = 'SI' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$email_approval_expected_role_1 = $rw['email_approval_expected_role_1'];							
								$email_approval_expected_role_2 = $rw['email_approval_expected_role_2'];
								$email_approval_expected_role_3 = $rw['email_approval_expected_role_3'];
								$email_approval_expected_role_4 = $rw['email_approval_expected_role_4'];
								$email_approval_expected_role_5 = $rw['email_approval_expected_role_5'];
								$email_approval_expected_role_6 = $rw['email_approval_expected_role_6'];
								$email_approval_expected_role_7 = $rw['email_approval_expected_role_7'];
				//echo $sql.'<BR>';
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_1' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_1 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_2' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_2 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_3' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_3 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_4' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_4 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_5' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_5 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_6' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_6 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_7' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_7 = $rw['role'];	
							
								$created_date = $row['created_date'];
								$expected_role_count = 0;
								if(!empty($email_approval_expected_role_1)){
									if($status!='Draft' && $created_date <='2023-08-15'){
										$email_approval_received_1	= 'Y';
										$email_approval_received_2	= 'Y';
										$email_approval_received_3	= 'Y';
										$email_approval_received_4	= 'Y';
										$email_approval_received_5	= 'Y';
										$email_approval_received_6	= 'Y';
										$email_approval_received_7	= 'Y';
									}
							?>
								<div class="col-md-12">
									<label class="control-label">Before Submitting, Email Approval to Expect from</label><br>
								</div>	
								<?php } ?>
							<?php if(!empty($email_approval_expected_role_1)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_1;?></label><br>
									<input type="hidden" id="email_approval_expected_role_1" name="email_approval_expected_role_1" value="<?= $email_approval_expected_role_1;?>" >
							
							<?php if($status=='Draft' ){ ?>	
									<input type="checkbox" id="email_approval_received_1" name="email_approval_received_1" <?php echo ($email_approval_received_1=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_1=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>
							<?php if(!empty($email_approval_expected_role_2)){
										$expected_role_count = $expected_role_count + 1;
							?>
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_2;?></label><br>
									<input type="hidden" id="email_approval_expected_role_2" name="email_approval_expected_role_2" value="<?= $email_approval_expected_role_2;?>" >
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_2" name="email_approval_received_2" <?php echo ($email_approval_received_2=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_2=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>	
							
								</div>	
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_3)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_3;?></label><br>
							<?php if($status=='Draft' ){ ?>		
									<input type="checkbox" id="email_approval_received_3" name="email_approval_received_3" <?php echo ($email_approval_received_3=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_3=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_4)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_4;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_4" name="email_approval_received_4" <?php echo ($email_approval_received_4=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_4=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_5)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_5;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_5" name="email_approval_received_5" <?php echo ($email_approval_received_5=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_5=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_6)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_6;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_6" name="email_approval_received_6" <?php echo ($email_approval_received_6=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_6=='Y' ){ ?>	
									<input type="text" readonly  class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_7)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_7;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_7" name="email_approval_received_7" <?php echo ($email_approval_received_7=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_7=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								
							</div>
							<?php } ?>	
					</div>
					
					<?php if($del != 'Y'){ ?>
							
							<div class="col-sm-6">
								
								<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&si_id='.$si_id ; ?>
							    
								<?php 
									
									$approval_status = $row['approval_status'];
								//	echo $status. ' ' . $user."<>";
									if($del=='Y' || ($user=='Admin123' &&  $status!='Draft' && empty($py_id) ) || ($user=='Admin' &&  $status!='Draft' && empty($py_id) ) || ( $approval_status=='Rejected' ) ) { ?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
													
								<?php
									}
									
//$status != 'Completed' && 							
								if ($status == 'Draft' && empty($py_id) ){
									
							?>
								<!--<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>-->
								<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>
								
							<?php	
								} 
							?>
								<span>&nbsp;&nbsp;</span>
								
					<?php			
						if	( $items_cnt == 0 && $status=='Draft'){ 
					?>	
							<span style="color:red;font-weight:bold;"><?= "Error : Product Not available OR Product Received Quantity is zero! "; ?></span>
					<?php	}
						if(!empty($poerrmsg)){
							echo  "<span style='color:red;'>$poerrmsg</span><BR>" ;
						}	
					?>	
					
							</div>
							
							<div class="col-sm-6 text-right">
							
						<?php 
								$_SESSION['si_id'] 	= $si_id;
								$_SESSION['status']  = $status;
								$_SESSION['our_po_ref_no']  = $our_po_ref_no;
								
								$approver_flag='';
								if( $status != 'Draft' ){
									
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' || 
										$userid == $approver_4 && $approver_4_status=='Submitted' ){
					
									if($approver_1_status=='Submitted' && empty($approver_2_status) && empty($approver_3_status) && empty($approver_4_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted' && empty($approver_3_status) && empty($approver_4_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Submitted' && empty($approver_4_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved' && $approver_4_status=='Submitted' ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved' && $approver_4_status=='Approved' 
										 ){
										$approver_flag='Y';
									}
								
									 
								}

//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								
								echo "<span style='color:red;'>".$berrmsg ."</span><BR>";
							
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
								</span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								}
						?>			
					
						<?php	
					
						if(!empty($dup_invoice)){
							echo $dup_invoice. "<BR>";	
						}	
						if( $doc_cnt==0 || empty($doc_cnt) ){
						    echo "<span style='color:red;text-weight:bold;'>Dcocument Attachments should be mandatory !</span>";
						    //echo "Dcocument Attachments should be mandatory !";
						    
						}
						
							$tally_status = $row['tally_status'];
							if($status=='Draft' && $approval_status!='Rejected' && $doc_cnt >0 ){
							    
								if( $items_cnt > 0 && empty($poerrmsg) && empty($dup_invoice) ){
						?>
								<span class='hidesend'>	
									<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								</span>	
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php 	} 
							}
						?>
					
						
						<?php if( $approval_status!='Rejected' && $items_cnt > 0 && empty($poerrmsg) ){ ?>
							<span class='hidesend'>	
								<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
							</span>	
						<?php 	}  ?>	
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<?php 
								$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
							?>		
								<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-default" >Back</a>&nbsp;&nbsp;&nbsp;</span>
					
						<label class="col-lg-2 control-label" style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></label>	
							<label style="color:red;text-align:center;" ><?php echo $emsg; ?></td>
							
						</div>
					<?php	
						}
					?>
								
						
					</div>
						
					<?php  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 1</label><BR>
									<label class="control-label1"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 2</label><BR>
									<label class="control-label1"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 3</label><BR>
									<label class="control-label1"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 4</label><BR>
									<label class="control-label1"><?= $approver_4_name . " <BR> " . $approver_4_role;?>
									</label>
								</div>
					<?php	
							}
					?>		
						
						</div>
						
					<?php
						}
					?>
					
					<span id="getapprover">

						<?php 						
						if( $status == 'Draft' ){
						?>
						
								<div class="box-footer">
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label>
								</div>
							
								<BR>
								
							</div>
						
						<?php } ?>		
						
						</span>
				

		</div>	
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
						  
		<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $si_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'SI' order by id asc ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									if($create_date=='01-01-1970'){
										$create_date ='';
									}	
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
							
							<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
							
							<?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
								echo " Supplier Name : " . $party_name;

							?>
						</b>	
					</span>
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'SI' order by id desc ";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$make_by_flag		= $r1['make_by_flag'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['primary_role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
												if($make_by_flag=='V'){
													$create_by		= $r1['create_by'];	
													$sl="SELECT * FROM sma_party_mst where id = '$create_by' ";
													$r3 = mysqli_query($con, $sl);
													$rw = mysqli_fetch_array($r3);
													$create_by = $rw['party_name'];
												}	
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
		</div>


<?php	//Advance Payment Start ?>
						<div class="tab-pane" id="tab_6">
							
							<div class="modal-header" >
								<p><?php echo $label_line; ?></p>
							</div>
							
							<div class="box-body">
							<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>
										<th>SrNo.</th>
										<th>Paid On</th>
										<th>Paid via</th>
										<th>UTR.No./ Cheque No.</th>
										<th style="text-align:right;">Amount Paid</th>
										
									</tr>
								</thead>
							<tbody>
							<?php

								$sql = "SELECT b.*, c.*  FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y'  ";
								
								$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c where a.po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and b.paid_to = '$vendor_id' ";
								
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect = mysqli_affected_rows($con);
						
						?>			
						<?php			
									$q21  = mysqli_query($con, $sql);
									while($r21  = mysqli_fetch_array($q21)){
										$py_id = $r21['payment_hdr_id'];
									
										$cash_bank_name = $r21['cash_bank_name'];
										$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$cash_bank_name = $r2['account_name'];
										
										$paid_to = $r21['paid_to'];
										$st_flag = $r21['st_flag'];
										if($st_flag =='A' || $st_flag =='T'){
											$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['username'];
										}
										else {
											$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['party_name'];
										}
										
										if($st_flag=='S'){
											$st_flag ='SI';
										}
										else if($st_flag=='A'){
											$st_flag ='TA';
										}
										else if($st_flag=='T'){
											$st_flag ='TE';
										}
										else if($st_flag=='C'){
											$st_flag ='OE';
										}
										else if($st_flag=='D'){
											$st_flag ='SA';
										}
										
										$dated = date('d-m-Y', strtotime($r21['dated']));
										if($dated =='01-01-1970'){
											$dated = '';
										}
										
										$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
										if($paid_date =='01-01-1970'){
											$paid_date = '';
										}
										
										$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$supplier_invoice_no  = $r2['supplier_invoice_no'];
										$supp_id			  = $r2['supp_id'];
										
										$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];
										
										$approval_status = $r21['approval_status'];
											
										$baseurl1 = $baseurl.'payment/'.'edit.php?sub=edit&id='.$py_id;
												
										?>
									<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=". $py_id;?>" target="_blank" title="Edit">
									<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="window.open('<?php echo $baseurl1;?>', '_blank')"  >
										<td width="2%" style="text-align:right;"><?php echo $r21['id'];?></td>
										<td width="10%" <?php echo $styl; ?>><?php echo $paid_date;?></td>
										<td width="12%" <?php echo $styl; ?>><?php echo $cash_bank_name;?></td>
										<td width="10%"<?php echo $styl; ?>><?php echo $r21['utr_no']. ' '. $r21['cheque_no'];?></td>
										<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo number_format($r21['total_amount_paid'],2);?></td>
										
									</tr>
									</a>
									
									<?php }
									
								?>
								</tbody> 
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<th>Total</th>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_paid_net,2);?></td>
									</tr>
								</table>

								
									</div>
						
						</div>
					<?php	//Advance Payment End ?>
					
<?php	//Supplier Invoice Payment Start ?>
						<div class="tab-pane" id="tab_7">
							
							<div class="modal-header" >
								<p><?php echo $label_line; ?></p>
							</div>
							
							<div class="box-body">
							<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>
										<th>SrNo.</th>
										<th>Paid Date</th>
										<th>Paid via</th>
										<th>UTR.No./ Cheque No.</th>
										<th>Invoice Number</th>
										<th style="text-align:right;">Amount Paid</th>
									</tr>
								</thead>
							<tbody>
							<?php
								$sql = "select * from payment_details a, payment_header b where 1 and b.id = a.payment_hdr_id and b.st_flag='S' and b.del !='Y' and supp_id = '$si_id' ";
								$total_amount_paid_net =0;
								/* $sql ="SELECT b.*, c.*
									FROM `sma_supplier_invoice` a, payment_header b, payment_details c 
									WHERE our_po_ref_no = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' 
									and b.del !='Y' and a.status = 'Completed'  "; */
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect = mysqli_affected_rows($con);
								
									$q21  = mysqli_query($con, $sql);
									while($r21  = mysqli_fetch_array($q21)){
										$py_id = $r21['payment_hdr_id'];
									
										$cash_bank_name = $r21['cash_bank_name'];
										$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$cash_bank_name = $r2['account_name'];
										
										$paid_to = $r21['paid_to'];
										$st_flag = $r21['st_flag'];
										if($st_flag =='A' || $st_flag =='T'){
											$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['username'];
										}
										else {
											$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['party_name'];
										}
										
										if($st_flag=='S'){
											$st_flag ='SI';
										}
										else if($st_flag=='A'){
											$st_flag ='TA';
										}
										else if($st_flag=='T'){
											$st_flag ='TE';
										}
										else if($st_flag=='C'){
											$st_flag ='OE';
										}
										else if($st_flag=='D'){
											$st_flag ='SA';
										}
										
										$dated = date('d-m-Y', strtotime($r21['dated']));
										if($dated =='01-01-1970'){
											$dated = '';
										}
										
										$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
										if($paid_date =='01-01-1970'){
											$paid_date = '';
										}
										
										$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$supplier_invoice_no  = $r2['supplier_invoice_no'];
										$supp_id			  = $r2['supp_id'];
										
										$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];
										
										$approval_status = $r21['approval_status'];
											
										$baseurl1 = $baseurl.'payment/'.'edit.php?sub=edit&id='.$py_id;
												
										?>
									<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=". $py_id;?>" target="_blank" title="Edit">
									<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="window.open('<?php echo $baseurl1;?>', '_blank')"  >
										<td width="2%" style="text-align:right;"><?php echo $r21['id'];?></td>
										<td width="10%" ><?php echo $paid_date;?></td>
										<td width="12%" ><?php echo $cash_bank_name;?></td>
										<td width="10%" ><?php echo $r21['utr_no']. ' ' . $r21['cheque_no'];?></td>
										<td width="12%" ><?php echo $supplier_invoice_no;?></td>
										<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo number_format($r21['payment_adjusted'],2);?></td>
										
									</tr>
									</a>
									
									<?php }
									
								?>
								</tbody> 
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_paid_net,2);?></td>
									</tr>
								</table>

									</div>
						
						</div>
<?php	//Supplier Invoice Payment End ?>
				
<!--Comment Section Start-->
	<?php //if($status!='Draft'){ 
	?>
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
					<p><?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
								echo " Supplier Name : " . $party_name;

							?></p>
				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$si_id' and doc_type = 'SI' order by id desc ";
				//echo $s1;
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$comment_datetime		= $r1['comment_datetime'];
				$comment_type			= $r1['comment_type'];
				$comment				= $r1['comment'];
				$parent_comment_id		= $r1['parent_comment_id'];
				$comment_by				= $r1['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($r1['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$create_by = $rw['username'];
					
				if(!empty($create_by)){	
					$tmp_var = "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$comment_datetime; 
				}
				?>			
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $si_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $si_id;?>,'SI','C',<?= $page;?>)" >Submit</button>		
						</div>
					</div>

			<span id="getcomment">	
			<?php
				$res  = mysqli_query($con, $s1);
				while($r1 = mysqli_fetch_array($res)){
					$comment_datetime		= $r1['comment_datetime'];
					$comment_type			= $r1['comment_type'];
					$comment				= $r1['comment'];
					$parent_comment_id		= $r1['parent_comment_id'];
					$comment_by				= $r1['comment_by'];
					$comment_datetime	    = date('d-m-Y h:i:s a', strtotime($r1['comment_datetime']));
					if($comment_datetime=='01-01-1970'){
						$comment_datetime='';
					}
					$sl="SELECT * FROM sma_user where id = '$comment_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$create_by = $rw['username'];
			?>
					<div class="col-md-12">
					
						<label class="control-label">On <?php echo $comment_datetime ?> <?php echo $create_by ;?> : wrote</label><br>
						<?= $comment; ?>
					<!--	<textarea style="background-color:#F5F5F5;" readonly rows='02' cols="150" ><?= $comment; ?></textarea> -->
					</div>
			<?php	
				}
			?>	
			</span>
			
			</div>
					</section>
					
				</div>
				
			 </div>
						
		</div>

	<?php //} ?>
	
<!--Comment Section End-->				
								
								
<?php	//Invoice Against PO ?>
							<div class="tab-pane" id="tab_10">

										<div class="modal-header">
											<div class="well well-sm"
												style="background-color: #f4f4f4; border-left: 5px solid #3c8dbc; padding: 10px 15px; margin-bottom: 20px; font-size: 16px; color: #333; font-weight: bold;">
												<?php echo $label_line; ?>
											</div>
										</div>

										<div class="box-body">
											<table id="prtable123" class="table table-bordered table-striped">

											<thead>
												<tr>
													<th>Sr.No.</th>
													<th>Created Date</th>
													<th>Tax.Inv.No.</th>
													<th style="text-align:right;">Amount</th>
													<th>Our PO Ref.NO.</th>
													<th>Supplier Name</th>
													<th>Payment Status</th>
													<th>Status</th>
												</tr>
											</thead>
									<tbody>
								<?php
										$modulePath_si = "supp_invoice/";
										$sql = " SELECT a.* FROM `sma_supplier_invoice` a, sma_purchase_order b
													WHERE a.our_po_ref_no = '$our_po_ref_no' and a.our_po_ref_no = b.id 
														and a.del !='Y'  order by id desc "; //and a.status = 'Completed'
													//echo $sql."<BR>";
										$q21 = mysqli_query($con, $sql);
										$rowaffect = mysqli_affected_rows($con);
										$q21 = mysqli_query($con, $sql);
										while ($r21 = mysqli_fetch_array($q21)) {
											$company_id = $r21['company_id'];
		$project_id = $r21['project_id'];
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		$sql 	= "select * from sma_project where id = '$project_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$project_name = $r2['project_name'];
		
		$supplier = $r21['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];

		$our_po_ref_no = $r21['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$po_number 	= $r1['po_number'];
		$po_rev			= $r1['po_rev'];
		if($po_rev>0){
			$po_number 	= $po_number .'-'.	$po_rev;
		}
				
		$due_date = date('d-m-Y', strtotime($r21['due_date']));
		if($due_date=='01-01-1970'){$due_date='';}
		
		$si_id = $r21['id'];
		$sql = "SELECT b.*, a.our_po_ref_no, a.status FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status in ( 'Completed', 'Draft','Submitted' )  ";
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$affected_row  = mysqli_affected_rows($con);
		$utr_no 	= $r2['utr_no'];
		$st_flag 	= $r2['st_flag'];
		$status_py		= $r2['status'];
		
		$paid_status ='';	
		if($affected_row==0){
			$paid_status = 'Unpaid';
		}
		else {
			$paid_status = 'Unpaid – Payment Note Created';
				
		}
		if(!empty($utr_no)){
			$paid_status = 'Paid#'.'/'.$utr_no ;
		}
	
		$sql = "SELECT b.* FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status in ('Closed', 'Completed' ) and a.advance_flag = 'Y' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$affected_row  = mysqli_affected_rows($con);
		$utr_no 	= $r2['utr_no'];
		$st_flag 	= $r2['st_flag'];
		
		if(!empty($utr_no)){
			$paid_status = 'Paid'.'/'.$utr_no ;
		}
		
		if($affected_row>0){
			$sql = "SELECT b.id as pay_no, st_flag, a.id as po_no, sum(c.payment_adjusted) as amount_paid_po, b.utr_no as utr_no_po, b.paid_date as paid_date_po  
				FROM `sma_purchase_order` a, payment_header b, payment_details c 
					WHERE a.id = '$our_po_ref_no' and a.id = c.supp_id and b.id = c.payment_hdr_id 
					AND st_flag = 'D' and b.del !='Y' and a.status = 'Completed' and b.utr_no !='' ";				
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$amount_paid_po = $amount_paid_po + $r2['amount_paid_po'];
			$utr_no_po		= $r2['utr_no_po'];
			$paid_date_po	= $r2['paid_date_po'];
			if(!empty($utr_no_po)){
				$paid_date = date('d-m-Y', strtotime($paid_date_po));
				if($paid_date == '01-01-1970' || $paid_date == '31-12-1969'){
					$paid_date='';
				}
				$paid_status = 'Advance Paid '.'/'.$utr_no_po ;
			}
		}
		
		$status_si = $r21['status'];
		
		$total_amount_v = $r21['total_amount'];
		$total_amount_si = $total_amount_si + $total_amount_v;
		
		$baseurl1 = $baseurl.$modulePath_si.'edit.php?sub=edit&id='.$r21["id"];
				
?>
	
	<a href="<?php echo $baseurl . $modulePath_si . "edit.php?sub=edit&id=". $r21['id'];?>" target="_blank" title="Edit" >
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)"
												onclick="window.open('<?php echo $baseurl1; ?>', '_blank')" >
		<!--<td width="0%"><input type="hidden" value="<?php echo $r21['id'];?>"></td>-->
		<td width="3%" style="text-align:right;<?= $styla; ?> "><?php echo $r21['id']?></td>
		<td width="11%" <?= $styl; ?>><?php echo date('d-m-Y', strtotime($r21['created_date']));?></td>
		<td width="10%" <?= $styl; ?> ><?php echo $r21['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;<?= $styla; ?>"><?php echo moneyFormatIndiaa($r21['total_amount'])?></td>
		<td width="15%"  <?= $styl; ?>><?php echo substr($po_number,0,25).'<BR>'.substr($po_number,25,50). ' <b>SPV:'. $comp_code. '</b> <br>'. substr($project_name,0,25)."<BR>".substr($project_name,25,50);?></td>
		<td width="17%"  <?= $styl; ?>><?php echo $supplier_name;?></td>
		<td width="09%"  <?= $styl; ?>><?php echo $paid_status;?></td>
		<td width="08%" <?= $styl; ?>><?php echo $status_si;?></td>
															</tr>
														</a>

													<?php }

													?>
												</tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td width="10%" style="text-align:right;"><?= moneyFormatIndiaa($total_amount_si); ?></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</table>

										</div>

									</div>
									<?php	// Invoice Against PO End ?>	
																	
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						
		</div>
					
                    </fieldset>

				</div>
				
            </form>
					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>  
</div>


<!--Add Line Popup-->

<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											
											$sqlu = "";
											/* if($tax_category=='U'){
												$sqlu = " and account_name not like '%GST%' ";
											} */	
										?>
										
										<input type="hidden" name="si_id" id="si_idA" value="<?php echo $si_id; ?>" >
										
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Supplier
												
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	
												
                                            </div>
                                        </div>

									
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)">
														<option value="">Select</option>
													<?php
														$sql = "SELECT * FROM account_mst where 1 and ( account_type = 'D' OR account_type = 'A' )$sqlu order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'].'-'.$r3['percentage'].'%'; ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectA" name="effect" value='Dr' > Debit
												<input type="radio"  id="effectA" name="effect" checked value='Cr' > Credit
											</div>
                                        
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Amount *</label>
												<span class="gettdsamt">
                                            	<input type="text" class="form-control amountA"  autocomplete="off" style="text-align:right;;" name="amount" id="amountA" >
												</span>
												
												<label for="approver" class="control-label">If Any changes in amount enter here</label>
												<input type="text" class="form-control " id="amountABC" autocomplete="off" style="text-align:right;;" name="amounta" value="" >
												
                                            </div>
                                        </div>
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA"></textarea>
											</div>
										</div>

								</form>

								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
							</div>
			
                            </div>
                        </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Line Popup End -->


<!--Make to DraftPopup-->
<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="si_id" id="si_idD" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="si_id" id="si_idZ" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   

											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$our_po_ref_no = $_SESSION['our_po_ref_no'];
											$role = $_SESSION['role'];
							
										?>
										
										<input type="hidden" name="si_id" id="si_idE" value="<?php echo $si_id; ?>" >
										
										<input type="hidden" id="our_po_ref_NO" name="our_po_ref_no" value="<?php echo $our_po_ref_no?>">
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											
										/*	$sql="SELECT * FROM sma_supplier_invoice where id = '$si_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
										*/		
										
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$r3   = mysqli_fetch_array($query);
											$approver = $r3['id'];

										?>
										
										<input type="hidden" name="si_id" id="si_idR" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
																				
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
<!--                                               	<select class="form-control select2123" id="statusE" name="status">
													    <option value="">Select</option>
														<option value="Draft">Draft</option>
														<option value="Submitted">Submitted</option>
														<option value="Reviewed">Reviewed</option>
													</select>
												-->
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-6 control-label" style="color:red;">Do you want to Reject ?</label>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    
									</form>	
								
								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  
	  
	  
	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="clearfld()" >&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Product </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="edit.php?id=<?php $_GET['id'];?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="si_Id" value="<?php echo $_GET['id'];?>">
							<?php
								$id = $_GET['id'];
								$sql="SELECT * FROM sma_supplier_invoice where id = '$id' ";
                            //echo $sql;
								$rs1 = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                $rw1 = mysqli_fetch_array($rs1);
								$our_po_ref_no = $rw1['our_po_ref_no'];

							?>
							
						<?php 
							if (!empty($our_po_ref_no)){
								$sql = "SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$rs = mysqli_query($con, $sql);
                                $rw = mysqli_fetch_array($rs);
								$received_date = date('d-m-Y', strtotime($rw['dated']));	
						?>
					
						<div class="form-group">
                                <label for="itemCategory" class="control-label col-sm-2">Purchase Order</label>
								<div class="col-sm-10">	
                                    <select class="form-control" id="grn_No" name = "grn_no" onchange="getitemdetails(this.value);" >
										<option value="0">Select</option>
                                    <?php
										
										$sql = "SELECT a.purchase_id as pid, a.product_id as 'material_id', ( a.quantity - a.bal_si_qty ) as qty , c.name as product_name, c.name as pname, c.uom FROM `sma_po_items` a, sma_product c where a.product_id = c.id and purchase_id = '$our_po_ref_no' and ((quantity > bal_si_qty or 
										((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100)) > bal_si_amount or 
										purchase_id = '$our_po_ref_no' ) )"; //a.purchase_id = 2136 || 
										
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
									?>
                                        <option value="<?php echo $rw['pid'].'-'.$rw['material_id'];?>" >
										<?php echo 'Product Name: '.$rw['product_name'].' Pending Qty:'. $rw['qty'] ; ?>
										</option>
                                     <?php } ?>
									 
                                    </select>
								</div>
							</div>
						<?php } 
								$readonlye = 'READONLY';
						?>
							
                        <?php if (empty($our_po_ref_no)){
								$readonlye = '';
						?>		
							<div class="form-group">
								<input type="hidden"id="grn_No" name = "grn_no" value=''>
								
								<label for="itemCategory" class="control-label col-sm-1"> Category</label>
								<div class="col-sm-3">
									<select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								<label for="itemName" class="control-label col-sm-2">Product Name</label>
                                <div class="col-sm-6">
									<span id="getmaterial1" ><span id="getgrnitem" >
										<select class="form-control" id="itemName">
											<option value="">Select</option>	
										<?php
											/* $sql="SELECT id, name FROM sma_product ORDER BY name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($result)){ */
										?>
										<!--	<option value="<?php echo $r2['id']?>"><?php echo $r2['name'] ?></option> -->
											<?php //} ?>
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-6">
								<label for="itemDescription" class="control-label ">Description</label>
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
								
								<div class="col-sm-6">
									<label class="control-label">Posting Account (DR)</label>
									<input type="text" class="form-control" id="posting_ACCOUNT_A"   readonly value="" >
								</div>
								
                            </div>
						
							
								<div class="form-group">
									
									<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group</label>
										<select class="form-control" id="costcenter_group" name="costcenter_group" required="true" onchange="getcostcenter(this.value);" >
											<option value="">Select</option>
										<?php
											$sql = " SELECT * FROM sma_budget_name where 1  ORDER BY name ASC ";
											$q2  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_object($q2)){
												$name = $r2->name;
												$id = $r2->id;
										?>		
											<option value='<?php echo $id ?>'><?php echo $name; ?></option>
										<?php }; ?>
										</select>
										
									</div>
								
									<div class="col-sm-6">
										<span id="getcostcenter" >
											
										<label for="itemName" class="control-label">Cost Center Name</label>
										
										</span>
									</div>
								</div>
									
								<div class="form-group">	
									<div class="col-sm-12">
										<span id="getcatbudget">
												
										</span>
									</div>
								</div>
								
                        <?php } ?>
							
						</div>
						
                        <div class="form-group col-md-12 ">
								
						<span id="getitemdetails">			
					
							<div class="col-sm-2">
								<label for="itemGST" class="control-label">GST Type</label>	
								<select class="form-control" name="itemg_id" id="itemg_id" onchange="getgst(this.value)" >
									<option value=""> Select </option>
									<?php 
									$sql = "select * from gst_mst where 1 order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'].'-'.$r22['igst'];?>" ><?php echo $r22['gst_name'].'-'.$r22['igst'];?></option>
										<?php } ?>
								</select>
							</div>
							
								<div class="col-sm-2">
									<label for="itemGST" class="control-label">GST%</label>
								
                                    <input type="text" class="form-control" id="itemGST"  style="text-align:right;" readonly onkeyup="calculateTotalAmount();">
									
									<input type="hidden" class="form-control" name="itemgst_id" id="itemGST_ID"  style="text-align:right;" readonly >
								
                                </div>

							
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
							<span id="getunit123" > 
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
										<input type="text" class="form-control itemUNITS" id="itemUNITS" name="itemunits" readonly value='' >
								</div>
                            </span>	
                                
								<div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate" style="text-align:right;" <?= $readonlye;?> onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
								
							
							
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total </label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                           </span>
							
                            </div>
						
							
					
					
                        <div style="color:red;font-weight:bold;" id="showmsg"> </div>
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
			<?PHP if ($against_po_flag=='Y'){ ?>	
                <button type="button" class="btn btn-primary" id="addItem" onclick="checkbudget();" >Save </button>
			<?php } 
			else { 
			?>
				<button type="button" class="btn btn-primary" id="addItemmanual" >Save</button>	
			<?php }  //onclick="checkbudget123();"
			?>
			
            </div>
			
                </section>
            </div>

        </div>
    </div>
</div>


<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTallyLabel">Do you want create A/c journal?</h4>
            </div>
            <div class="modal-body123">
                <section class="content123">
                    <div class="row123">
                        <form class="form-horizontal" action="#" method="POST" enctype="multipart/form-data">
						
                            <input type="hidden" id="modeT" value='Tally'>
							<input type="hidden" id="si_idT" value="<?php echo $_GET['id'];?>">
						
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry" >Submit</button>
            </div>
			<!--onclick="tallyentry123();"-->
                </section>
            </div>
        </div>
    </div>
</div>


<script>
	function checkbudget(){
		
	//	balance_budget_a
        var itemAmount 		= document.getElementById('itemAmount').value;
		
		var balance_budget 	= document.getElementById('balance_budget_a').value;
//alert(itemAmount + ' <<##1>>' + balance_budget );		
//return false;
		var check_balance	= parseInt(balance_budget) - parseInt(itemAmount);
//alert(check_balance  );			
		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP !!!');
			return false;
		}

	}
	
	function getitemdetails(id){
		
        var sub    = 'sub44';
		var comp_id = document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id + ' ' + grn_no);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,comp_id:comp_id,sub44:sub},function(result){
		      $('#getitemdetails').html(result);
		});

	}
	
	function gettotbudget(id){
		
        var sub    = 'sub45';
		
		var budget_name_id 	= document.getElementById('budget_name').value;
		var comp_id 		= document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,budget_name_id:budget_name_id,comp_id:comp_id,sub45:sub},function(result){
		      $('#gettotbudget').html(result);
		});

	}
	

</script>

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        


<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%" ><select class="form-control select2 doctype" name="doctype[]"  ><option value="">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"><textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"></td><td  width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->

 <?php 	
		include("../footer.php");	
?>


<script>
    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
			var company_id = result;
			  var splitString = result.split("##");
			  var company_id =  splitString['1'];
			  
			  var result = splitString['0'];
			  $('#getcreditdays').html(result);
			  
			    /* var sub    = 'sub12';
			    var strURL = "app_func.php";
				$.post(strURL,{id:company_id,sub12:sub},function(result){
					  $('#getworkflowtype').html(result);
				}); */
				
		      //$('#getcreditdays').html(result);
		});

	}
	
</script>

<script>


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var si_id		 	=  $("#si_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + si_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var si_id		 	=  $("#si_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + si_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});

  
    $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
		$('#predit').html('Wait...Processing !!');
//		alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idE").val();
		var approver 		=  $("#approverC").val();
        var our_po_ref_no 	=  $("#our_po_ref_NO").val();
		var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		
//alert( approver + ' ' + status + ' ' +  mode );
		 $('#approvalAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						approver:approver,
						our_po_ref_no:our_po_ref_no,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
		//alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idR").val();

        var approver 		=  $("#approverR").val();
		//var approver		=  $("#approverR option:selected").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(status+ ' ' + department + ' ' + mode + ' ' + approver);
		 $('#rejectAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


    $("#addItemmanual").on("click", function(e){
        var sub = 'sub2';
//alert(sub); return false;
		
		var si_hdr_id 	 =  $("#si_Id").val();
		var company_id   =  $("#comp_id").val();
		var id 			 =  $("#itemName").val();
		//var id 			 =  $(".itemName").val();
		//var name		 =  $("#itemName").html();
//alert('addItemmanual ' + id + ' <<#>>');
//return false;
			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
		var gst_id 			 =  $("#itemGST_ID").val();
        var amount 			 =  $("#itemAmount").val();
//alert(gst_id);
//return false;
		var budget_id  	 =  $("#budget_ID").val();
//alert('Budget ID : '+budget_id);
			var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_BUDGET').value;

		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' <<#1>>' + itemamount + ' <<<#2>>> ' + balance_budget + ' <<<#3>>> ' + tot_amount + ' <<<##>>>' );

		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP!!!');
			var shoid = 'AOP / Budget 100% reached, please increase AOP !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
		$('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,si_hdr_id:si_hdr_id,
							description:description,
							company_id:company_id,
							budget_id:budget_id,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							gst_id:gst_id,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		

	});

    $("#addItem").on("click", function(e){
        var sub = 'sub2';
	//	var mode = $("#mode").val();
			var grn_no		 =   $("#grn_No").val();
//alert(sub2);
//		if(grn_no != '0'){
			var si_hdr_id 	 =  $("#si_Id").val();		
			var id 			 =  $("#itemName").val();
			var name		 =  $("#itemName").html();
			var company_id   =  $("#comp_id").val();
			var budget_name  =  $("#budget_name").val();
			var budget_head  =  $("#budget_head").val();
			var budget_id  	 =  $("#budget_id").val();
			var budget_head_b  	 =  $("#budget_head_b").val();
			var budget_name_b  	 =  $("#budget_name_b").val();
			
//alert('addItem '+ grn_no + ' <<#>> ' + company_id);
//return false;

		var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_budget_a').value;
		var balance_budget	=  $(".balance_budget_a").val();
//alert('Balance Budget ' + balance_budget + ' #####1');
		
		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' ' + itemamount + ' <<<>>> ' + balance_budget + ' <<<>>> ' + tot_amount + ' <<<>>>' );
	
		var itemqtychk  	 =  $("#itemQtyChk").val();			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
        var amount 			 =  $("#itemAmount").val();

//alert(qty + ' ' + itemqtychk);

		 
		if ( parseInt(enter_amount) > parseInt(amount) && po_threashold=='V'){
			alert('Amount should not be greater then PO AMount...');
			var shoid = 'Amount should not be greater then PO AMount... !!!';
			$("#showmsg").text(shoid);
			return false;
		} 
		
		
		if ( parseInt(qty) > parseInt(itemqtychk) && po_threashold=='Q'){
			alert('Quantity should not be greater then PO QTY...');
			var shoid = 'Quantity should not be greater then PO QTY !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//return false;		
		
		if ( check_balance < 0 ){
			alert(' Budget 100% reached, please increase !!!');
			var shoid = ' Budget 100% reached, please increase  !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//alert(units + ' ' + qty);
        $('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,si_hdr_id:si_hdr_id,
							name:name,
							description:description,
							company_id:company_id,
							grn_no:grn_no,
							budget_name:budget_name,
							budget_head:budget_head,
							budget_id:budget_id,
							budget_head_b:budget_head_b,
							budget_name_b:budget_name_b,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//		location.reload();
//		window.location.href='supplier_invoice.php?sub=edit&id='+si_hdr_id+'&active=active';
		
//        saveItem(mode);
		
    });


    $("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub12';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		var amount 		= $(".amountA").val();
		var narration 	= $("#narrationA").val();
		var si_hdr_id 	= $("#si_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert( amount2 + ' ' + amount + ' <<>> ' + si_hdr_id + ' ' + account_name + ' ' + type_ac + ' ' + effect );

		$('#addLine').modal('hide');
		var strURL 		= "si_func.php";
		$.post(strURL,{ type_ac:type_ac,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,si_hdr_id:si_hdr_id,sub12:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

    $("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub10';
		var mode 	= $("#modeT").val();
		var si_hdr_id 	 =  $("#si_idT").val();		
//alert(si_hdr_id);
        var gst_flag = '';
// 		if (document.getElementById('gst_FLAG').checked) {
// 		   // gst_flag = document.getElementById('gst_FLAG').value;
// 			var gst_flag = 'Y';
// 		}
// 		else {
// 			var gst_flag = '';
// 		}
		


		$('#modalAddTally').modal('hide');
		var strURL 		= "si_func.php";
		$.post(strURL,{ mode:mode,si_hdr_id:si_hdr_id,gst_flag:gst_flag,sub10:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

	function getaccount(id){
		
        var sub    		= 'sub11';
		
//alert(sub + ' ' + id);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getaccount').html(result);
		});

	}

	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#amount_DR").val();
		
		var si_id	 	= $("#si_idA").val();
//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,si_id:si_id,amount_dr:amount_dr,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}
	
	function delete_siItem(si_id, id ){
		var sub = 'sub3';
        var siid = si_id;
		var id	 = id;
//alert(siid + ' ' + id);
		$('#modalDeleteItem'+siid+id).modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ siid:siid,id:id,sub3:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		window.location.href='edit.php?sub=edit&id='+siid+'&active=active';	
		//location.reload();
	}


	function getmaterial(id){
		
        var sub    = 'sub33';
		var dtl_id			= document.getElementById('dtl_id').value;
//alert(id + ' ' + sub + ' ' + ' ' + ' Edit Func' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('.getmaterial').html(result);
		});

	}

	function getmaterial1(id){
		
        var sub    = 'sub3';
		
//alert(sub + ' ' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}

	function getunit(id){
		
        var sub    = 'sub4';
		//var grn_no = document.getElementById('grn_No').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      //$('#getunit').html(result);
			//alert(result);  
				var splitString = result.split("##");
		
				var uom 			=  splitString['0'];
				var account_name 	= splitString['1'];
				var tolerance_level = splitString['2'];
				var po_threashold 	= splitString['3'];
		
			  $("#itemUNITS").val(uom);
			  $("#posting_ACCOUNT_A").val(account_name);
			  //document.getElementById("itemUNITS").value = result;
			  
		});

	}

		function getgrnitem(id){
		
        var sub    = 'sub5';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem').html(result);
		});

	}
	
	function getgrnitem1(id){
		
        var sub    = 'sub5';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem1').html(result);
		});

	}

	
	function getcompany(id){
		
        var sub    = 'sub8';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getcompany').html(result);
		});

	}
	
	function getcompany1(id){
		
        var sub    = 'sub88';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub88:sub},function(result){
		      $('#getcompany1').html(result);
		});
	}
		
	function getbudgetname(id){
		
        var sub    = 'sub7';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}

	function getbudgetname1(id){
		
        var sub    = 'sub77';
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub77:sub},function(result){
		      $('#getbudgetname1').html(result);
		});

	}


	function getbudget(id){
		
        var sub    = 'sub6';
		var company_id   = document.getElementById('company_ID').value;
		var account_year = document.getElementById('account_YR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub6:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}
	
	function getbudget1(id){
		
        var sub    = 'sub66';
		var company_id   = document.getElementById('company_iD').value;
		var account_year = document.getElementById('account_yR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub66:sub},function(result){
		      $('#getbudgethead1').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}


	function getuser(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser').html(result);
		});

	}
	function getuser1(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser1').html(result);
		});

	}


	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;
			
			
		//alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "search_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}



	   function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
		function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
//		alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function getworkflowtype(id){
		
        var sub    = 'sub12';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub12:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}

	function getapprover(){
		
//		var department    	= document.getElementById("department").value;
		var company_id    	= document.getElementById("comp_id").value;
		var checker_value   = document.getElementById("checker_value").value;
//		var trans_type    	= document.getElementById("trans_type").value;
		var our_po_ref_no	= document.getElementById("our_po_ref_no").value;
		
		var sub = 'sub24';
//alert(department);		

		$('.hidesend').hide();
		var strURL = "app_func.php";
		$.post(strURL,{our_po_ref_no:our_po_ref_no,company_id:company_id,checker_value:checker_value,sub24:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}


	function getcostcenter(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcenterC(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('.getcostcenter').html(result);
		});

	}
	
	function getcatbudget (id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}

	function getcatbudgetC(id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('.getcatbudget').html(result);
		});

	}
	
	

    function clearfld(){
		
		$('#itemDescription').html('');
		$('#itemQuantity').html('');
		$('#itemUnits').html('');
		$('#itemRate').html('');
		$('#itemGST').html('');
		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}

	
	function getvalidate(){
		
		var project 	=  $("#comp_id").val();
		var location 	=  $("#location").val();
		var department 	=  $("#department").val();
		var quotation_reference_no 	=  $("#our_po_ref_NO").val();
		var to_supplier 	= $("#suplier_name").val();
		
		var against_po_flag  = '';
		var against_po_flag1 = '';
		
		if (document.getElementById('against_po_FLAG1').checked) {
		    against_po_flag1 = document.getElementById('against_po_FLAG1').value;
			var against_po_flag = against_po_flag1;
		}
		if (document.getElementById('against_po_FLAG').checked) {
		    against_po_flag = document.getElementById('against_po_FLAG').value;
		}
		
		if(to_supplier==''){
			alert('Supplier selection mandatory !!!');
			return;
		}
		
 		if(quotation_reference_no==''){
			alert('PO Ref. No. selection mandatory !!!');
			return;
		}

		if(project==''){
			alert('Company selection mandatory !!!');
			return;
		}
/* 		if(po_doc_type==''){
			alert('Workflow type selection mandatory !!!');
			return;
		}
 */		
		if(location==''){
			alert('Location selection mandatory !!!');
			return;
		}
		if(department==''){
			alert('Department selection mandatory !!!');
			return;
		}
		
	}	
	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1 || row_affected==2 || row_affected==3 ){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}	
		if(row_affected==2 || row_affected==3 ){
			if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3 ){
			if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		
		
		return false;
		
	}
	
	function getgst(id){

//alert(id);		
		var splitString = id.split("-");
		
		var gst_id =  splitString['0'];
		var gst_perc = splitString['1'];
		
//alert(gst_id + ' ' + gst_perc);			
		$('#itemGST_ID').val(gst_id);
		$('#itemGST').val(gst_perc);
			  
	}	


	function gettdsperc1(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction1_amount').val(result);
		});
	}
	
	function gettdsperc2(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction2_amount').val(result);
		});
	}
	
	function gettdsperc3(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction3_amount').val(result);
		});
	}
	
	function getcomment(comment,si_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,si_id:si_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}

	function saveToDatabase_ln(editableObj,column,si_hdr_id,line_no) {
		    
		var editableObj = editableObj.innerHTML;	
		//alert("UPDATE `enqdetail` set " + editableObj  + ' ' + column);
		var sdivURL = "saveratedtl.php";
			$.post(sdivURL,{column:column,editval:editableObj,si_hdr_id:si_hdr_id,line_no:line_no },function(result){
			//	alert('Hello...');
				//$('#addbom_dtl123').html(result);
			});
			
	}
		

	function checkinvoice_no(id){
		
		var sub    = 'sub20';
		var suplier_id 	=  $("#suplier_NAME").val();
		var company_id  =  $("#company_ID").val();
//alert(sub+ ' ' + suplier_id + ' ' + id + ' ' + company_id); 
		$('#checkinvoice_no').html('');
		$('#hidesend').show();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,suplier_id:suplier_id,company_id:company_id,sub20:sub},function(result){
			var rowaffected = result;
			 //alert(rowaffected);
			  if(rowaffected>1){
				  var result = "Error : Invoice Number already available for supplier !";
				  alert(result);
				  $('#checkinvoice_no').html(result);
				  
				  $('#hidesend').hide();
			  }
			  else {
				$('#hidesend').show();	
			  }	  
		     // $('#checkinvoice_no').html(result);
			  
		});
	}		
</script>


</body>
</html>
