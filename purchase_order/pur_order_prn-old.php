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
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> " . $comp_name."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 10px;'><tr><td style='width: 95%;'>Office Address : ". $comp_addr1.', '.$comp_addr2.', '.$comp_addr3.' '.$comp_city.' Pincode : '.$comp_pincode."</td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 12px;'>
			<tr><td style='width: 95%;'> Phone: ".$comp_office.", E-mail : ".$comp_email."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 12px;'>
			<tr><td style='width: 95%;'> CIN No.: ".$comp_cin_no.", GST No.: ".$loc_gst_no.", PAN No.: ".$loc_pan_no."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";

//	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
//			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 18pt;margin-left:10px;'><tr><td style='width: 95%;'> " . $comp_name."</td></tr></table>";
/*	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 10px;'><tr><td style='width: 95%;'>Office Address : ". $comp_addr1.', '.$comp_addr2.', '.$comp_addr3.' '.$comp_city.' Pincode : '.$comp_pincode."</td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 12px;'>
			<tr><td style='width: 95%;'> Phone: ".$comp_office.", E-mail : ".$comp_email."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 12px;'>
			<tr><td style='width: 95%;'> CIN No.: ".$comp_cin_no.", GST No.: ".$loc_gst_no.", PAN No.: ".$loc_pan_no."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 05pt;'>
			<tr><td style='width: 95%;'> <hr style='height: 1px;'> </td></tr></table>";
*/			
	$id				= $_GET['id'];
	$tableName		= "sma_purchase_order";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$approval_memo_ref		= $row['approval_memo_ref'];
		$quotation_reference_no = $row['quotation_reference_no'];
		$po_number  			= $row['po_number'];
		$location	 			= $row['location'];
		$discount 				= $row['discount'];
		$transport 				= $row['transport'];
		$other_charges 			= $row['other_charges'];
		$terms 					= $row['terms'];
		$status					= $row['status'];

		//echo $terms;
//exit();		
		$po_doc_type 			= $row['po_doc_type'];
		
	}

	
	if($status =='Draft' || $status == 'Submited'){
		$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;color:#838B8B;font-size:72;'>&nbsp;Draft </td></tr></table>";		
		
	}
	
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
	
	if($po_doc_type=='PO'){
		$po_desc = "Purchase Order";
	}
	else if($po_doc_type=='WO'){
		$po_desc = "Work Order";
	}
	else if($po_doc_type=='SO'){
		$po_desc = "Service Order";
	}
	else if($po_doc_type=='CA'){
		$po_desc = "Contract Agreement";
	}
	
	$message .= "<table cellspacing='0' style='width: 95%; text-align: center;margin-left:10px; font-size: 13px;'>
			<tr><th style='width: 95%;'> $po_desc No.: $po_number Dated : $dated </th></tr></table>";

	$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$party_name  	 = $row['party_name'];
		$party_address_1 = $row['party_address_1'];
		$party_address_2 = $row['party_address_2'];
		$party_address_3 = $row['party_address_3'];
		$party_city  	 = $row['party_city'];
		$party_pincode   = $row['party_pincode'];
		$party_mobile    = $row['party_mobile'];
		$party_contact_person_name = $row['party_contact_person_name'];
	}
	$sql 	= "SELECT * FROM cities where id = '$party_city'";
	$result = mysqli_query($con,$sql);
	$cty = mysqli_fetch_array($result);
	$party_city  = $cty['city_name'];

	$message .= "<table cellspacing='0' style='width: 95%; border:  0px black; text-align: left;margin-left:10px; font-size: 12pt;'>
			<tr>
			<td style='width: 50%;text-align: left;border:  .5px black;'> To Supplier, <br>
			 ". $party_name 
			 ."<br>". $party_address_1
			 ."<br>". $party_address_2
			 ."". $party_address_3
			 ."". $party_city. ", Pincode: ". $party_pincode
			 ."<br>Mobile:". $party_mobile
			 ."</td>
			<td style='width: 50%;text-align: top; border:  .5px black;'>Quatation Ref.No: ".$quotation_reference_no
			."<br>Dated : ".$dated
			."<br>Contact Person : " . $party_contact_person_name
			."<br>Mobile : ". $party_mobile
			."<br><br><br><br><br></td>
			</tr></table>";
	
	if (!empty($loc_city)){
		$loc_city = 'City - '. $loc_city;
	}
	
	if (!empty($loc_mobile)){
		$loc_mobile = 'Mobile- '. $loc_mobile;
	}
	
	$message .= "<table cellspacing='0' style='width: 95%; border:  0px black; text-align: left;margin-left:10px; font-size: 12pt;'>
			<tr>
			<td style='width: 50%;text-align: left;border:  .5px black;'> <b>Billing Address </b> <br>". $loc_addr1 
			 ."<br>". $loc_addr2
			 ."<br>". $loc_addr3
			 ."<br>". $loc_city. " Pincode: ". $loc_pincode
			 ."<br>". $loc_mobile
			 ."</td>
			<td style='width: 50%;text-align: left; border:  .5px black;'><b>Delivery Address </b> <br>". $loc_addr1 
			."<br> ".$loc_addr2
			."<br> ".$loc_addr3
			."<br>".$loc_city. " Pincode: ". $loc_pincode
			."</td>
			</tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; text-align: center;margin-left:10px; font-size: 13px;' >
			<tr><td style='width: 06%;text-align: Center;font-size:12px;'><b> SrNo.</b></td>
				<td style='width: 24%;text-align: left;'><b> &nbsp; Material </b></td>
				<td style='width: 30%;'><b> Description </b></td>
				<td style='width: 08%;text-align: right;'><b> Qty. </b></td>
				<td style='width: 6%;'><b> Unit </b></td>
				<td style='width: 08%;text-align: right;'><b> Rate </b></td>
				<td style='width: 08%; text-align: center;'><b> GST% </b></td>
				<td style='width: 10%;text-align: right;'><b> Amount</b></td>
			</tr></table>";
			
	$message .= '<table cellspacing="0" style="width: 95%; margin-left:10px; border: solid 2px #000000; "> 
				<tr>
                <td style="width: 100%;">';
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_po_items where purchase_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$quantity		= $row['quantity'];
		$unit_rate		= round($row['unit_rate'],2);
		$pod_discount	= $row['pod_discount'];
		$gst			= $row['gst'];
		$product_desc   = $row['product_desc'];
		$tot_qty		= $quantity;
		$actual_amt     = $quantity * $unit_rate;
		$total_amt		= $total_amt + $actual_amt;
		
		//$net_amt  		= round($actual_amt - ($actual_amt * $pod_discount / 100),0);
		
		$net_amt  		= round($actual_amt ,0);
		
		$gst_amt  		= $gst_amt + round($actual_amt * $gst / 100,0);
		
		$total_net_amt	= $total_net_amt + $net_amt;
		
		$delivery_date  = date('d-m-Y', strtotime($row['delivery_date']));
		
		$product_id=$row['product_id'];
		$sql="Select * from sma_product where id = '$product_id'";
		$output = mysqli_query($con,$sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($output);

		$product_name = $r2['name'];
		
		$unit		  = $r2['uom'];
		$hsn_code	  = $r2['hsn_code'];

	    ++$i;
	$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;' >
			<tr><td style='width: 6%;text-align: Center;'> ".$i." </td>
				<td style='width: 24%;text-align: left;'>". $product_name . " </td>
				<td style='width: 30%;text-align: center;font-size: 11px;'> " . $product_desc . " </td>
				<td style='width: 10%;text-align: right;'> &nbsp;&nbsp;".$quantity." </td>
				<td style='width: 7%;text-align: center;'> " . $unit . " </td>
				<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
				<td style='width: 8%;text-align: center;'> ".$gst." </td>
				<td style='width: 10%;text-align: right;'> ".number_format($net_amt,2)." </td>
			</tr></table>";		
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
	
	$message .= "</td></tr></table>";
	
	$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
			<tr><td style='width: 82%;text-align: right;'> Grand Total </td>
				<td style='width: 18%;text-align: right;' > ".number_format($total_net_amt,2)." &nbsp;</td>
			</tr></table>";
	
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
			<tr><td style='width: 82%;text-align: right;'>GST  </td>
				<td style='width: 18%;text-align: right;' > ".number_format($gst_amt,2)." &nbsp;</td>
			</tr></table>";	
	
	$total_amt = $gst_amt + $total_net_amt ; 
	$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:10px; font-size: 10pt;' border='.2'>
			<tr><td style='width: 82%;text-align: right;'> Net Total </td>
				<td style='width: 18%;text-align: right;' > ".number_format($total_amt,2)." &nbsp;</td>
			</tr></table>";
			
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
	
	}
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'>
	<tr><td style='border: 0px black; text-align:justify;'><b>Terms & Conditions </b>: </td></tr></table>";
	
	//$terms = htmlspecialchars_decode(htmlspecialchars_decode($terms));
	
	//$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:40px;font-size: 12px;'><tr>
	// <span style='margin-left:40px;'> $terms </span> </tr></table>";
	
	//$message .= "<div style='width: 675px;border: 0px solid red;padding: 1px;margin: 10px;'><span style='margin-left:10px;'> $terms </span></div>";
	$message .=  $terms ;
	
//	$sql 	= "select * from sma_term where doc_type = 'PO' ";
//	$output = mysqli_query($con,$sql);
//	echo mysqli_error($con);
//	$r2 = mysqli_fetch_array($output);
//	$terms_condition = $r2['term'];

	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
	
	}
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'><tr><td style='width: 44.9%;border:  0px black;'>I agree and accept above in totality&nbsp; <br> For $party_name &nbsp;<br>&nbsp;<br>&nbsp;<br>Name :&nbsp;<br>Designation:</td><td style='width: .1%;'></td><td style='width: 55%;text-align: right;border:  0px black;'> For <b>" . $comp_name."</b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>Authorized Signatory  
	</td>
	</tr></table>";
	
	if($status =='Draft' || $status == 'Submited'){
		$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;font-size:24;'>&nbsp;Draft </td></tr></table>";		
		
	}
	
		
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
		$fl_name = 'poorder_'.$id. '.xls';
		header("Content-type: application/xls");
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
			$fl_name = 'poorder_'.$id. '.pdf';
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