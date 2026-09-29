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
/* 	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$department = $_POST['department'];	
	$supplier_id= $_POST['supplier_id'];	
	$company_id= $_POST['company_id'];	 */
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='15'> Balance Approval Memo Report</th></tr></table>";		

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'>Company </td>
					<td style='width: 6%;'>Location </td>
					<td style='width: 6%;'>Department </td>

					<td style='width: 06%;text-align: left;'>AP.Srno.</td>
					
					<td style='width: 10%;text-align: left;'>Dated </td>
					<td style='width: 25%;'>Supplier </td>
					
					<td style='width: 08%;'>Doc Type</td>
					<td style='width: 08%;'>PO / CE .No.</td>
					
					<td style='width: 08%;text-align: right;'>Total AP Amount</td>
					<td style='width: 08%;text-align: right;'>Used AP Amount</td>
					<td style='width: 08%;text-align: right;'>Bal. AP.Amount</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	$comid 			= $_SESSION['comid'];	
//echo $comid;	
	
	$sql = "SELECT * FROM sma_approval_memo 
				WHERE company in ($comid)
					AND del !='Y' 
					AND status = 'Completed' 
					ORDER BY company ";
	
//echo $sql;
//exit();
		
	$res1 = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($res1)){
		
		$ap_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$company_id				= $row['company'];
		$department				= $row['department'];
		$location	 			= $row['location'];
		
		$ap_values= '';
		$sql 	= "SELECT * FROM `sma_approval_details` where approval_hdr_id = '$ap_id' and vendor_selected = 'Y' ";
		$res = mysqli_query($con,$sql);
//echo $sql. "<BR>";		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($lc 	= mysqli_fetch_array($res)){
			
			$supplier_name    	= $lc['supplier_name'];
			$ap_values    		= $ap_values + $lc['values'];

		}
		
		
		$sql 	= "SELECT * FROM `sma_location` where id = '$location'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$lc 	= mysqli_fetch_array($res);
		$loc_name    = $lc['loc_name'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_name'";
		$res 	= mysqli_query($con,$sql);
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
		
		$total_ap_item_value =0 ;
		$sql 	= "SELECT * FROM `sma_approval_items` where approval_hdr_id = '$ap_id' and supplier_id = '$supplier_name' ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($lc 	= mysqli_fetch_array($res)){
			
			$quantity    		= $lc['quantity'];
			$unit_rate    		= $lc['unit_rate'];
			$gst    			= $lc['gst'];
			$total_ap_item_value	= $total_ap_item_value + round( ($quantity * $unit_rate ) + ( ($quantity * $unit_rate ) * $gst / 100) ,0);
			
		}
		
		
//Purchase Order Start
	    $tot_po_amt  	= 0;
		$doc_type 		= '';
		$po_id			= '';
		
		$sql 	= " SELECT * FROM sma_purchase_order WHERE approval_memo_ref = '$ap_id' and approval_memo_ref >0 and po_type = 'A' and del !='Y' ";
//echo $sql. " ####1<BR>";					
		$poresult 	= mysqli_query($con,$sql);
		$affected 	= mysqli_affected_rows($con);
		$s=0;
		//if(mysqli_affected_rows($con)>0){
		while($porec	 		= mysqli_fetch_array($poresult)){
				
				$doc_type 				= 'PO';
				$po_id					= $porec['id'];
				$invoice_date  			= date('d-m-Y', strtotime($porec['dated']));
				$supplier_id			= $porec['to_supplier'];
				$po_number				= $porec['po_number'];
				$advance_flag			= $porec['advance_flag'];
				$paid_amount			= $porec['paid_amount'];
				
//Purchase Order Items Start
			
			$sql 	= " SELECT * FROM sma_po_items WHERE purchase_id  = '$po_id' ";
			$suppresult 	= mysqli_query($con,$sql);
			$s =0 ;
		
			while($supp	 		= mysqli_fetch_array($suppresult)){
				$purchase_id			= $supp['purchase_id'];
				$qty					= $supp['quantity'];
				$rate					= $supp['unit_rate'];
				$gst					= $supp['gst'];

				$tot_po_amt  			= $tot_po_amt + round(( $rate * $qty ) + (($rate * $qty) * $gst / 100 ),0);
				
			}
			
			if($advance_flag=='Y' && $tot_po_amt==0){
				$tot_po_amt = $paid_amount;
			}
			
			if( $total_ap_item_value > $tot_po_amt ){
				
				$bal_ap_amt = $total_ap_item_value - $tot_po_amt;
				
				if($bal_ap_amt >= 0 && $bal_ap_amt<=2){
					continue;
				}
				
			}
			
		}
//Purchase Order End
		
		

//Operating Expense Start
	
	if($affected==0){
		$po_id 			= '';	
		$tot_po_amt  	= 0;
		$sql 	= " SELECT * FROM sma_travel_expenses WHERE approval_number  = '$ap_id' and approval_number  >0 and exp_type = 'C' and del !='Y' ";
//echo $sql. " ####2<BR>";						
		$poresult 	= mysqli_query($con,$sql);
		$s=0;
		//if(mysqli_affected_rows($con)>0){
			while($porec	 		= mysqli_fetch_array($poresult)){
				
				$doc_type 				= 'CE';
				$ce_id					= $porec['id'];
				$invoice_date  			= date('d-m-Y', strtotime($porec['dated']));
				$supplier_id			= $porec['to_supplier'];
				
//Operating Expense Items Start
			
			$sql 	= " SELECT * FROM sma_expenses WHERE approval_ref_no  = '$ce_id' ";
			$suppresult 	= mysqli_query($con,$sql);
			$s =0 ;
		
			while($supp	 		= mysqli_fetch_array($suppresult)){
				$purchase_id			= $supp['purchase_id'];
				$po_number				= $supp['invoice_no'];
				$amount					= $supp['amount'];
				$gst_amount				= $supp['gst_amount'];

				$tot_po_amt  			= $tot_po_amt + $amount + $gst_amount;
				
			}
			
			
			
		}
		
			
	}	
//Operating Expense End
		
//echo $total_ap_item_value . ' > ' . $tot_po_amt ."<BR>"; ;

			if( $total_ap_item_value > $tot_po_amt ){
				
				$bal_ap_amt = $total_ap_item_value - $tot_po_amt;
				
				if($bal_ap_amt >= 0 && $bal_ap_amt<=2){
					continue;
				}
				
				$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$loc_name."</td>
					<td>".$department."</td>
					<td>".$ap_id ."</td>
					<td>".$dated."</td>
					<td>".$party_name ."</td>
					<td>".$doc_type ."</td>
					<td>".$po_id."</td>
					<td style='width: 08%;text-align: right;' >".$total_ap_item_value."</td>
					<td style='width: 08%;text-align: right;'>".$tot_po_amt."</td>
					<td style='width: 08%;text-align: right;'>".$bal_ap_amt."</td>
					";
			}
			
	
	}
	
	$message .= "</tr></table>";

//echo $message;
//echo $fl_name = 'pending_poorder_'.date("d-m-Y"). '.xls';
//exit(" EXIT HERE....");
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'pending_apmemo_'.date("d-m-Y"). '.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}	

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'apmemo'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
}