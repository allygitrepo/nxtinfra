<?php 
include("../dbcon.php");

	$company_id	= $_GET['company_id'];
	$sql 	= "select * from company where comp_id = '$company_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$comp_code = $r2['comp_code'];
	$comp_name = $r2['comp_name'];
	
	if($comp_code=='AIPL'){
		include "aipl_neft_export.php";
		exit();
	}	
	
if($_GET['sub'] == 'list'){ 
	
	$modulePath = "payment/";
	$message = '';
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
	$message .= "<tr>
			<th>SrNo.</th>
			<th>Paid Date</th>
			<th>Paid via</th>
			<th>Paid To</th>
			<th>Dated.</th>
			<th>Supp.No.</td>
			<th>Inv Sr.No.</td>
			<th>Module Type</td>
			<th style='text-align:right;'>Amount Paid</th>
		</tr>";
	
		
	$sql   = "SELECT * from rtgs_temp where 1 and selected = 'Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$py_id					= $row['py_id'];
		$paid_date 				= $row['paid_date'];
		$cash_bank_name 		= $row['cash_bank_name'];
		$party_name 			= $row['party_name'];
		$dated 					= $row['dated'];
		$supplier_invoice_no 	= $row['supplier_invoice_no'];
		$supp_id				= $row['supp_id'];
		$st_flag 				= $row['st_flag'];
		$total_amount_paid 		= $row['total_amount_paid'];
		
		$message .= "<tr>
						<td>$py_id</td>
						<td>$paid_date</td>
						<td>$cash_bank_name</td>
						<td>$party_name</td>
						<td>$dated</td>
						<td>$supplier_invoice_no</td>
						<td style='text-align:right;'>$supp_id</td>
						<td>$st_flag</td>
						<td style='text-align:right;'>$total_amount_paid</td>
					</tr>";
					
	}
	
}
	$message .="</table>";
	//echo $message;
	//exit();
	
		$fl_name = 'rtgs_export_'.date("d-m-Y").'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
		
?>		
		