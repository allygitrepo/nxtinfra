<?php
if($_GET['sub'] == 'pdf'){

	session_start();

	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$prn		= "excel";
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$department = $_POST['department'];	
	$supplier_id= $_POST['supplier_id'];	
	$company_id= $_POST['company_id'];	
		
	$message ='';
	//from ". $_POST['from_date'] . " TO ". $_POST['to_date']
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='15'> Purchase Order Detail Register </th></tr></table>";		

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'>Company </td>
					<td style='width: 6%;'>Location </td>
					<td style='width: 6%;'>Department </td>
					<td style='width: 6%;'>Status </td>

					<td style='width: 06%;text-align: left;'>PO.Number</td>
					<td style='width: 10%;text-align: left;'>Dated </td>
					<td style='width: 25%;'>Supplier </td>
					
					<td style='width: 08%;'>NOA No.</td>
					<td style='width: 08%;'>Supp.Quote.Ref.No.</td>
					<td style='width: 08%;'>Delivery Days </td>
					<td style='width: 08%;'>Credit Days </td>
					<td style='width: 08%; text-align: center;'>Discount </td>
					<td style='width: 08%;text-align: right;'>Transport</td>
					<td style='width: 08%;text-align: right;'>Other Charges</td>
					
					<td style='width: 5%;text-align: left;'>Sr.No.</td>
					<td style='width: 23%;text-align: left;'>Material </td>
					<td style='width: 25%;'>Description </td>
					<td style='width: 6%;'>Unit </td>
					<td style='width: 08%;text-align: right;'>Qty. </td>
					<td style='width: 08%;text-align: right;'>Rate </td>
					<td style='width: 08%;text-align: right;'>Total Amt. </td>
					<td style='width: 08%; text-align: center;'>GST% </td>
					<td style='width: 08%;text-align: right;'>Net Amt.</td>

					<td style='width: 08%;text-align: left;'>Budget Head</td>
					
					<td style='width: 08%;text-align: left;'>Supp.No.</td>
					<td style='width: 08%;text-align: left;'>Dated</td>
					<td style='width: 08%;text-align: left;'>Supplier Name</td>
					<td style='width: 08%;text-align: left;'>Supp.Inv.No.</td>
					<td style='width: 08%;text-align: right;'>Qty.</td>
					<td style='width: 08%;text-align: right;'>GST.</td>
					<td style='width: 08%;text-align: right;'>Amount</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	$comid = $_SESSION['comid'];	
	
	$tableName	= "sma_purchase_order";
	
	$sql 		= " SELECT * FROM $tableName where 1 ";

//echo $sql;
	
	if($from_date=='1970-01-01'){
		$from_date = '';
		$to_date = '';
	}
	
	if(!empty($from_date)){
		$sql = " and dated >= '$from_date' and dated <= '$to_date' ";
	}
	if($user != 'Admin'){
		$sql .= " and project in ( $comid ) ";
	}
	
	if (!empty($company_id)){
		$sql  .= " and project = '$company_id' ";
	}
	
	if (!empty($department)){
		$sql  .= " and department = '$department' ";
	}
	
	if (!empty($supplier_id)){
		$sql  .= " and to_supplier = '$supplier_id' ";
	}
	
	$sql = $_SESSION['sqlex'];
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$pur_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$company_id				= $row['project'];
		$department				= $row['department'];
		$status				    = $row['status'];
		
		$approval_memo_ref		= $row['approval_memo_ref'];
		$sql = "select * from sma_approval_memo where id = '$approval_memo_ref' ";
		$res = mysqli_query($con,$sql);
		$rw = mysqli_fetch_array($res);
		$approval_memo_ref		= $row['approval_memo_ref']. '-'.date('d-m-Y', strtotime($rw['dated']));	
		
		$quotation_reference_no = $row['quotation_reference_no'];
		$po_number  			= $row['po_number'];
		$delivery_days			= $row['delivery_days'];
		$credit_days			= $row['credit_days'];

		$location	 			= $row['location'];
		$discount 				= $row['discount'];
		$transport 				= $row['transport'];
		$other_charges 			= $row['other_charges'];
		$terms 					= $row['terms'];
	
		$sql 	= "SELECT * FROM `sma_location` where id = '$location'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$lc 	= mysqli_fetch_array($res);
		$loc_name    = $lc['loc_name'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$row 	= mysqli_fetch_array($res);
		$party_name  = $row['party_name'];

		$sql = "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 		= $com['comp_name'];
		
		$sql = "SELECT * FROM `sma_department` where id = '$department' ";
		$dep 	= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$department = $deps['name'];
		
		$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$loc_name."</td>
					<td>".$department."</td>
					<td>".$status."</td>
					<td>".$po_number ."</td>
					<td>".$dated."</td>
					<td>".$party_name ."</td>
					<td>".$approval_memo_ref."</td>
					<td>".$quotation_reference_no."</td>
					<td>".$delivery_days."</td>
					<td>".$credit_days."</td>
					<td>".$discount."</td>
					<td>".$transport."</td>
					<td>".$other_charges."</td>";

		$sql 	= "SELECT * FROM sma_po_items where purchase_id = '$pur_id'";
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		if($row_affected = 0){
			$message .="</tr>";
			continue;
		}
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$budget_id		= $rw['budget_id'];

			$quantity		= $rw['quantity'];
			$unit_rate		= round($rw['unit_rate'],2);
			$pod_discount	= $rw['pod_discount'];
			$gst			= $rw['gst'];
			$product_desc	= $rw['product_desc'];
						
			$actual_amt     = $quantity * $unit_rate;
			$total_amt		= $total_amt + $actual_amt;
			
			$net_amt  		= round($actual_amt + ($actual_amt * $gst / 100),0);
			
			$total_net_amt	= $total_net_amt + $net_amt;
			
			$delivery_date  = date('d-m-Y', strtotime($rw['delivery_date']));
			
			$product_id=$rw['product_id'];
			$sql="Select * from sma_product where id = '$product_id'";
			$output = mysqli_query($con,$sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($output);

			$product_name = $r2['name'];
			//$product_desc = $r2['description'];
			$unit		  = $r2['uom'];
			$hsn_code	  = $r2['hsn_code'];
			
			
			$sql = "SELECT a.*, b.budget_head as budget_head FROM `sma_budget` a, sma_budget_subgroup b 
						where b.id = a.budget_head and a.id = '$budget_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_head 		= $com['budget_head'];
			//$total_budget 		= $com['total_budget'];
		
			++$i;
		if($i > 1){
			$message .= "<tr><td colspan='13'>&nbsp;</td>
						<td style='width: 6%;text-align: Center;'> ".$i." </td>
						<td style='width: 23%;text-align: left;'>". $product_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $product_desc . " </td>
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$quantity." </td>
						<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
						<td style='width: 8%;text-align: right;'> ".$actual_amt." </td>
						<td style='width: 8%;text-align: left;'> ".$gst." </td>
						<td style='width: 8%;text-align: right;'> ".$net_amt." </td>
						<td style='width: 8%;text-align: left;'>". $budget_head . " </td> 
						";

//					</tr>";
			
//Supp.Invoice Start
	
		$sql 	= " SELECT * FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
						where a.id = b.si_hdr_id  and a.our_po_ref_no = '$pur_id' and b.material_id = '$product_id' ";
//echo $sql. " ####1<BR>";						
		$suppresult 	= mysqli_query($con,$sql);
		$s=0;
		if(mysqli_affected_rows($con)>0){
			while($supp	 		= mysqli_fetch_array($suppresult)){
				
				++$s;
				
			//echo $sql."<BR>";	
				$si_hdr_id				= $supp['si_hdr_id'];
				$invoice_date  			= date('d-m-Y', strtotime($supp['invoice_date']));
				$supplier_id			= $supp['suplier_name'];
				$supplier_invoice_no	= $supp['supplier_invoice_no'];
				//$budget_id				= $supp['budget_id'];
				$qty					= $supp['qty'];
				$gst					= $supp['gst'];
				
				$tot_supp_amt  			= $unit_rate * $qty + (($unit_rate * $qty) * $gst / 100 );
				
				$sql = "SELECT * FROM `sma_party_mst` where id = '$supplier_id' ";
				$comresult 	= mysqli_query($con,$sql);
				$com 		= mysqli_fetch_array($comresult);
				$supplier_name 		= $com['party_name'];
				//<td colspan='22'>&nbsp;</td>
				if($s>1){
					$message .= "<tr><td colspan = '24' ></td> ";
				}
				$message .= "
							<td style='width: 8%;text-align: left;'>". $si_hdr_id . " </td>
							<td style='width: 8%;text-align: left;'>". $invoice_date . " </td>
							<td style='width: 8%;text-align: left;'>". $supplier_name . " </td>
							<td style='width: 8%;text-align: left;'>". $supplier_invoice_no . " </td>
							<td style='width: 8%;text-align: right;'>". $qty . " </td>
							<td style='width: 8%;text-align: right;'>". $gst . " </td>
							<td style='width: 8%;text-align: right;'>". $tot_supp_amt . " </td> 
							</tr> ";
				if($s>1){
					$message .= "</tr>";
				}
			
			}
		}
		else {
			$message .= "</tr>";
		}	
//Supp.Invoice End
			
		}
		else {
			$message .= "<td style='width: 6%;text-align: Center;'> ".$i." </td>
					<td style='width: 23%;text-align: left;'>". $product_name . " </td>
					<td style='width: 25%;text-align: left;'> " . $product_desc . " </td>
					<td style='width: 6%;text-align: left;'> " . $unit . " </td>
					<td style='width: 8%;text-align: right;'> ".$quantity." </td>
					<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
					<td style='width: 8%;text-align: right;'> ".$actual_amt." </td>
					<td style='width: 8%;text-align: left;'> ".$gst." </td>
					<td style='width: 8%;text-align: right;'> ".$net_amt." </td>
					<td style='width: 8%;text-align: left;'>". $budget_head . " </td>
				";
				
//Supp.Invoice Start
	
		$sql 	= " SELECT * FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
						where a.id = b.si_hdr_id  and a.our_po_ref_no = '$pur_id' and b.material_id = '$product_id' ";
//echo $sql. " ###2<BR>";
		$suppresult 	= mysqli_query($con,$sql);
		$s =0 ;
		if(mysqli_affected_rows($con)>0){
			while($supp	 		= mysqli_fetch_array($suppresult)){
			
				++$s;
		//	echo $sql."<BR>";
				$si_hdr_id				= $supp['si_hdr_id'];
				$invoice_date  			= date('d-m-Y', strtotime($supp['invoice_date']));
				$supplier_id			= $supp['suplier_name'];
				$supplier_invoice_no	= $supp['supplier_invoice_no'];
				//$budget_id				= $supp['budget_id'];
				$qty					= $supp['qty'];
				$gst					= $supp['gst'];

				$tot_supp_amt  			= $unit_rate * $qty + (($unit_rate * $qty) * $gst / 100 );

				$sql = "SELECT * FROM `sma_party_mst` where id = '$supplier_id' ";
				$comresult 	= mysqli_query($con,$sql);
				$com 		= mysqli_fetch_array($comresult);
				$supplier_name 		= $com['party_name'];
				if($s>1){
					$message .= "<tr><td colspan = '24' ></td> ";
				}
				$message .= "<td style='width: 8%;text-align: left;'>". $si_hdr_id . " </td>
							<td style='width: 8%;text-align: left;'>". $invoice_date . " </td>
							<td style='width: 8%;text-align: left;'>". $supplier_name . " </td>
							<td style='width: 8%;text-align: left;'>". $supplier_invoice_no . " </td>
							<td style='width: 8%;text-align: right;'>". $qty . " </td>
							<td style='width: 8%;text-align: right;'>". $gst . " </td>
							<td style='width: 8%;text-align: right;'>". $tot_supp_amt . " </td> 
							";
				if($s>1){
					$message .= "</tr>";

				}
							
			}
		}
//Supp.Invoice End

			}
			
		}
//echo $message;
//exit(" EXIT HERE....");
	
	}
	
	$message .= "</tr></table>";

//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'poorder'.'_'.date('Y-m-d').'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}	

	
}