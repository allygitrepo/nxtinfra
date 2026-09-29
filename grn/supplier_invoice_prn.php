<?php
if($_GET['sub'] == 'pdf'){
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
	if(empty($_GET['id'])){
		echo "<script>alert('File not found...');window.close();</script>";
		return;
	}
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	

	$sql 	= "SELECT * FROM `sma_location` where id = '$location'";	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$loc_name    = $row['loc_name'];
		$loc_addr1   = $row['loc_addr1'];
		$loc_addr2   = $row['loc_addr2'];
		$loc_addr3   = $row['loc_addr3'];
		$loc_city    = $row['loc_city'];
		$loc_pincode = $row['loc_pincode'];
		$loc_phone   = $row['loc_phone'];
		$loc_mobile  = $row['loc_mobile'];
		$loc_email   = $row['loc_email'];
		$loc_pan_no  = $row['loc_pan_no'];
		$loc_gst_no  = $row['loc_gst_no'];
	}
	//sma_grn_srn_details
	$id				= $_GET['id'];
	$tableName		= "sma_grn_srn";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$si_no					= $row['id'];
		$comp_id				= $row['company_id'];
		$supplier_invoice_no	= $row['supplier_invoice_no'];
		$invoice_date  			= date('d-m-Y', strtotime($row['invoice_date']));
		$supplier_name			= $row['supplier_name'];
		$our_po_ref_no			= $row['our_po_ref_no'];
		$delivery_challen_no	= $row['delivery_challen_no'];
		$delivery_date			= date('d-m-Y', strtotime($row['delivery_date']));
		if($delivery_date=='01-01-1970'){
			$delivery_date='';
		}
		$delivery_mode			= $row['delivery_mode'];
		$lr_date				= date('d-m-Y', strtotime($row['lr_date']));
		if($lr_date=='01-01-1970'){$lr_date='';}
		
		$tranport_lr_no			= $row['transport_lr_no'];
		$tranporter_name		= $row['transporter_name'];
		$maker					= $row['draft_by'];
		$maker_date				= date('d-m-Y h:m ia', strtotime($row['draft_date']));

		$additional_charges		= $row['additional_charges'];
		$additional_remarks		= $row['additional_remarks'];

		$remarks				= $row['remarks']. ' ' . $additional_remarks;
		//echo $remarks;
		//exit();
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_name'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$supplier_name  	 = $s1['party_name'];
		
	}
	
/*		$sql 	= "SELECT * FROM sma_purchase_order where id = '$our_po_ref_no'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$comp_id	= $dep['project'];
*/
	
	$sql="SELECT * FROM `company` where comp_id = '$comp_id' ";
	$comresult 	= mysqli_query($con,$sql);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$error  			= mysqli_error($con);
	$com 				= mysqli_fetch_array($comresult);
	
	$comp_name 			= $com['comp_name'];
	$comp_addr1 		= $com['comp_addr1'];
	$comp_addr2 		= $com['comp_addr2'];
	$comp_addr3 		= $com['comp_addr3'];
	$comp_email 		= $com['comp_email'];
	$comp_office 		= $com['comp_office'];
	$comp_mobile 		= $com['comp_mobile'];
	$comp_city  		= $com['comp_city'];
	$comp_pincode 		= $com['comp_pincode'];
	$comp_country 		= $com['comp_country'];
	$comp_faxno 		= $com['comp_faxno'];
	$comp_cin_no 		= $com['comp_cin_no'];
	$comp_pan_no 		= $com['comp_pan_no'];
	
	$message ='';

	$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
	$message .= "<br><table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> $comp_name</td></tr></table>";
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 18px;'>
			<tr><td style='width: 95%;'>Good / Service Received Note</td></tr></table>";
			
$head = $message;

$message ='';
	
$message .= '<page backtop=26mm" backbottom="14mm" backleft="10mm" backright="2mm" pagegroup="new">
    <page_header>
        <table class="page_header" style="width: 103%; text-align: center;font-size: 18pt">
            <tr>
                <td style="width: 103%; text-align: center123;text-align: center;">
                    '.$head.'
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" >
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
</page>';

		$sql 	= "SELECT * FROM sma_purchase_order where id = '$our_po_ref_no'";
		$res = mysqli_query($con,$sql);
		$r1 	= mysqli_fetch_array($res);
		$our_po_ref_no_a 	= $r1['po_number'];
		$po_rev				= $r1['po_rev'];
		if($po_rev>0){
			$our_po_ref_no_a 	= $our_po_ref_no_a .'-'.	$po_rev;
		}

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; text-align: left;margin-left:10px; ' border='1'>
			<tr><th style='width: 50%;background: #E7E7E7;'> Serial Number </th><th style='width: 50%;'>&nbsp; $si_no </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> Dated </th><th style='width: 50%;'>&nbsp; $invoice_date </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> Supplier Name </th><th style='width: 50%;'>&nbsp;  $supplier_name </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> Our PO. Reference Number </th><th style='width: 50%;'>&nbsp;  $our_po_ref_no_a </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> GRN SRN  Number </th><th style='width: 50%;'>&nbsp;  $supplier_invoice_no </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> Delivery Challan Number </th><th style='width: 50%;'>&nbsp; $delivery_challen_no Delivery Date $delivery_date </th></tr>
			<tr><th style='width: 50%;background: #E7E7E7;'> Mode of Delivery </th><th style='width: 50%;'>&nbsp; $delivery_mode </th></tr>
			
			</table>";

			$message .=  "<br>";


	$message .=  "<h4> Material Details</h4>";
	
	$message .= "<table border='.2' cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 10%;'><b> # </b></td>
				<td style='width: 43%;text-align: Center;font-size:12px;'><b> Material</b></td>
				<td style='width: 12%;'><b> Total Qty  </b></td>
				<td style='width: 12%;text-align: center;'><b> Received Qty  </b></td>
				<td style='width: 11%;text-align: center;'><b> Balance Qty </b></td>
				<td style='width: 12%;text-align: center;'><b> Amount Rs. </b></td>
			</tr></table>";
			
	$message .= '<table border="0.2" cellspacing="0" style="width: 95%; margin-left:10px; border: solid 2px #000000; font-size: 12px;">';
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_grn_srn_details where grn_srn_hdr_id = '$id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
						
		$material_id		= $row['material_id'];
		$description		= $row['description'];
		$qty				= $row['qty'];
		$total_po_qty 			= $row['total_po_qty'];
		$rate				= $row['rate'];
		$amount				= $row['amount'];
		
		$bal_qty = 0;
		if($total_po_qty>0){
			$bal_qty = $total_po_qty - $qty;
		}
		
		$sql 	= "SELECT * FROM sma_product where id = '$material_id'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$material_name  	 = $s1['name'];
		
			
		$sql 	= "SELECT * FROM sma_grn_srn where id = '$id'";	
		$rs = mysqli_query($con,$sql);
		$r3 = mysqli_fetch_array($rs);
		$status		= $r3['status'];
					
		$sql 	= "SELECT * FROM `sma_po_items` where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";
//echo $sql; exit();
		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$total_po_qty  	 = $s1['quantity'];
		$bal_si_qty  	 = $s1['bal_si_qty'];
		
		if($bal_si_qty!=0){
			if($status=='Draft'){
				$bal_qty			= round($total_po_qty - $bal_si_qty,3);
			}
			else {
				$bal_qty			= round($total_po_qty - $bal_si_qty ,3);
			}
		}
		
		$tot_amount 	= $tot_amount + $amount;
		
	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'>".$i."</td>
				<td style='width: 43%;text-align: left;'>". $material_name . "</td>
				<td style='width: 12%;text-align: right;'>" . round($total_po_qty,4) . "</td>
				<td style='width: 12%;text-align: right;'>".round($qty,4)."</td>
				<td style='width: 11%;text-align: right;'>" . $bal_qty . "</td>
				<td style='width: 12%;text-align: right;'>" . $amount . "</td>
			</tr>";
	}
	
	if($additional_charges > 0){
		++$i;
		$message .= "<tr>
					<td style='width: 10%;text-align: Center;'>".$i."</td>
					<td style='width: 43%;text-align: left;font-size: 12px;'>" . $additional_remarks . "</td>
					<td style='width: 12%;text-align: right;'></td>
					<td style='width: 12%;text-align: right;'></td>
					<td style='width: 11%;text-align: right;'></td>
					<td style='width: 12%;text-align: right;'>" . $additional_charges . "</td>
				</tr>";
		$tot_amount 	= $tot_amount + $additional_charges;
	}
	
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'></td>
				<td style='width: 43%;text-align: left;font-size: 12px;'>Grand Total </td>
				<td style='width: 12%;text-align: right;'></td>
				<td style='width: 12%;text-align: right;'></td>
				<td style='width: 11%;text-align: right;'></td>
				<td style='width: 12%;text-align: right;'>" . number_format($tot_amount,2) . "</td>
			</tr>";
			
	$message .= "</table>";
	
			$message .=  "<br>";
	
	$message .=  "<h4> Remarks</h4>";
	
	$message .= "<table cellspacing='2' style='width: 100%; border: solid 1px black; margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 95%;text-align: left;font-size:12px;'> $remarks</td></tr></table>";

	
	$message .=  "<h4> Documents</h4>";
	
	$modulePath = "grn/";
	$baseurl2  = $baseurl.$modulePath;
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM file_uploads where module = 'SI' and reference_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $row_affected  = mysqli_affected_rows($con);
	if($row_affected>0){
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($row = mysqli_fetch_array($result)){
			$file_name 			= $row['file_name'];
			$file_path 			= $row['file_path'];
			$doc_type 			= $row['doc_type'];
			$share_point_link 	= $row['share_point_link'];
			
			$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
			if(!empty($share_point_link)){
			$message .= "<table cellspacing='2' style='width: 100%; border: solid 1px black; margin-left:10px; font-size: 12px;' >
				<tr><td style='width: 95%;text-align: left;font-size:12px;'><a href='$share_point_link'>$share_point_link</a></td></tr></table>";
			}	
		}
	}
	
	//$message .= "<table cellspacing='0' style='width: 95%; text-align: center; font-size: 01pt;'><tr><td style='width: 95%;'> <hr style='height: .5px;'> </td></tr></table>";
	
	$ln  = 2;
	$l   =  $i;
	
	for($l = $l; $l < $ln; $l++){
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:10px; font-size: 10pt;' border='0'>
					<tr><td style='width: 6%;text-align: Center;'> &nbsp;</td>
				<td style='width: 24%;text-align: left;'>&nbsp; </td>
				<td style='width: 30%;text-align: center;'> &nbsp; </td>
				<td style='width: 6%;text-align: center;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: center;'> &nbsp; </td>
				<td style='width: 10%;text-align: right;'> &nbsp;</td>
			</tr></table>";
	}
	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
	
	}

	$message .=  "<h4> Approval Process</h4>";
	
$message .= "<table cellspacing='-1' border='.3' style='width: 95%; border: solid 0px black; text-align: center; font-size: 10pt;' > ";
$message .= "<tr>
					<th style='width: 40%;'>Decision by </th>
					<th style='width: 20%;'>Status </th>
					<th style='width: 40%;'> Date Time </th>				
					</tr> ";			
				
//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'SI' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			$message .= "<tr>
					<td style='width: 40%;'>". $approval . " </td>
					<td style='width: 20%;'>". $status . " </td>
					<td style='width: 40%;'> " . $approval_date . " </td>				
					</tr> ";			
			
		}
		
			/* $message .= "<tr>
					<td style='width: 100%;' colspan='3'>Tally Journal Created by : ". $tally_created_by . ' ' . $tally_created_date." </td>
					</tr> "; */
					
		$message .= "</table>";
		

	
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
//	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
//			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";		

//	$message .= "</div>";
	
	
//print $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'grn_srn_rep_'.$id. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}	

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		//require_once($dirname.'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'grn_srn_rep_'.$id. '.pdf';
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