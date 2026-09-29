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

	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='10'>  Purchase Order Report</th></tr></table>";		

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
				    <td style='width: 6%;'>SRNo. </td>
				    <td style='width: 06%;text-align: left;'>WO/PO.Number</td>
					
					<td style='width: 6%;'>SPV </td>
					<td style='width: 10%;text-align: left;'>WO Date </td>
					
					<td style='width: 25%;'>Supplier </td>
					<td style='width: 25%;'>WO/PO for </td>
					<td style='width: 08%;text-align: right;'>Total PO Amount</td>
					<td style='width: 08%;text-align: right;'>Used PO Amount</td>
					<td style='width: 08%;text-align: right;'>Bal. PO.Amount</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$comid 			= $_SESSION['comid'];	
//echo $comid;	
	
	$sql = "SELECT * FROM sma_purchase_order 
				WHERE project in ($comid)  and del !='Y' and approval_status !='Rejected'
					AND status not in ('Closed ','Amend', 'Suspend', 'Blocked')
					ORDEr BY dated ";
	
	$sql = $_SESSION['sqlex'];
	$sql .= "  and approval_status !='Rejected'
					ORDEr BY dated";
					
//echo $sql; //AND status not in ('Closed ','Amend', 'Suspend', 'Blocked')
//exit();
		
	$res1 = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($res1)){
		
		$pur_id					= $row['id'];
		$po_dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$company_id				= $row['project'];
		$department				= $row['department'];
		$status					= $row['status'];
		$subject					= $row['subject'];
		
		if($po_dated=='01-01-1970' ){
			$po_dated ='';
		}
		
		$s1  = "SELECT * from workflow_history where doc_id = '$pur_id' and doc_type = 'PO' order by id desc";
							//echo $s1;		
		$res  = mysqli_query($con, $s1);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$create_date_v	= date('d-m-Y', strtotime($r1['create_date']));
		//date('d-m-Y h:i:sa', strtotime(
		$po_dated	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
		if($create_date_v	=='01-01-1970'){
			$po_dated	='';
		}	
									
		$approval_memo_ref		= $row['approval_memo_ref'];
		$sql = "select * from sma_approval_memo where id = '$approval_memo_ref' ";
		$res = mysqli_query($con,$sql);
		$rw = mysqli_fetch_array($res);
		$dated					= date('d-m-Y', strtotime($rw['dated']));
		if($dated=='01-01-1970' || $dated=='01-01-1970'){
			$dated ='';
		}	
		$approval_memo_ref		= $row['approval_memo_ref']. ' - '.$dated ;	
		
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
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$row 	= mysqli_fetch_array($res);
		$party_name  = $row['party_name'];
		$party_address  = $row['party_address_1'].','.$row['party_address_2'].','.$row['party_address_3'].','.$row['party_pincode'];

		$sql = "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 		= $com['comp_name'];
		$comp_code 		= $com['comp_code'];
		
		$sql = "SELECT * FROM `sma_department` where id = '$department' ";
		$dep 	= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$department = $deps['name'];
		
		$total_po_amt =0 ;
		$sql 	= "SELECT * FROM sma_po_items WHERE purchase_id = '$pur_id'" ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$budget_id		= $rw['budget_id'];
			$quantity		= $rw['quantity'];
			$unit_rate		= round($rw['unit_rate'],2);
			$pod_discount	= $rw['pod_discount'];
			$gst			= $rw['gst'];
			
			$total_po_amt  = $total_po_amt + round(($quantity * $unit_rate) + (($quantity * $unit_rate) * $gst / 100),0);
			
		}
			
		
//Supp.Invoice Start
		$tot_supp_amt  = 0;
		$sql 	= " SELECT * FROM sma_supplier_invoice WHERE 1 and del!='Y' and approval_status !='Rejected' and our_po_ref_no = '$pur_id' ";
//echo $sql. " ####1<BR>";						
		$suppresulthdr 	= mysqli_query($con,$sql);
		$s=0;
		//if(mysqli_affected_rows($con)>0){
			while($supphdr	 		= mysqli_fetch_array($suppresulthdr)){
				
				$si_hdr_id				= $supphdr['id'];
				$invoice_date  			= date('d-m-Y', strtotime($supphdr['invoice_date']));
				$supplier_id			= $supphdr['suplier_name'];
				$supplier_invoice_no	= $supphdr['supplier_invoice_no'];
				
//Supp.Invoice Start
			
			$sql 	= " SELECT * FROM sma_supplier_invoice_details WHERE 1 and si_hdr_id  = '$si_hdr_id' ";
	//echo $sql. " ###2<BR>";
			$suppresult 	= mysqli_query($con,$sql);
			//echo mysqli_errno($con);
			$s =0 ;
		
			while($supp	 		= mysqli_fetch_array($suppresult)){
				$si_hdr_id				= $supp['si_hdr_id'];
				$invoice_date  			= date('d-m-Y', strtotime($supp['invoice_date']));
				$supplier_id			= $supp['suplier_name'];
				$supplier_invoice_no	= $supp['supplier_invoice_no'];
				$qty					= $supp['qty'];
				$rate					= $supp['rate'];
				$gst					= $supp['gst'];

				$tot_supp_amt  			= $tot_supp_amt + round(( $rate * $qty ) + (($rate * $qty) * $gst / 100 ),0);
				
				
			}
			
		}
//Supp.Invoice End
		
		$bal_po_amt = $total_po_amt - $tot_supp_amt;
		
				$message .= "<tr>
				    <td>".++$ii ."</td>
					<td>".$po_number ."</td>
					<td>".$comp_code."</td>
					<td>".$po_dated."</td>
					<td>".$party_name."<BR>".$party_address ."</td>
					<td>".$subject."</td>
					
					<td style='width: 08%;text-align: right;' >".$total_po_amt."</td>
					<td style='width: 08%;text-align: right;'>".$tot_supp_amt."</td>
					<td style='width: 08%;text-align: right;'>".$bal_po_amt."</td>
					</tr>";
			
	}
	
	$message .= "</table>";

//echo $message;
//exit();
	
	$prn		= "excel";	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'poorder_'.$id. '.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}	

	
}