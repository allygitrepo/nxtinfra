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

	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	

	$sql 	= "SELECT * FROM `sma_location` where loc_comp_id = '$comp_id'";	
	
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
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> $comp_name</td></tr></table>";
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 18px;'>
			<tr><td style='width: 95%;'> Purchase Requisition Note </td></tr></table>";
		
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

	$id				= $_GET['id'];
	$tableName		= "sma_purchase_req";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$pur_req_no				= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['date']));
		$req_dated  			= date('d-m-Y', strtotime($row['reqDate']));
		$company_id				= $row['company_id'];
		$pr_number				= $row['pr_number'];
		$project_id				= $row['project_id'];
		$department_id			= $row['department_id'];
		$delivery_location_id	= $row['delivery_location_id'];
		$reason					= $row['reason'];
		$subject				= $row['subject'];
		$scope_of_work			= $row['scope_of_work'];
		$background_section		= $row['background_section'];
		$trans_type				= $row['trans_type'];
		$maker					= $row['draft_by'];
		$delivery_address		= $row['delivery_address'];
		$maker_date				= date('d-m-Y h:m i', strtotime($row['draft_dated']));
		$delivery_require_by	= date('d-m-Y', strtotime($row['delivery_require_by']));
		
		$sql 	= "SELECT * FROM sma_location where id = '$project_id'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$loc_name  	 = $s1['loc_name'];
		
		$sql 	= "SELECT * FROM sma_department where id = '$department_id'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$department_name  	 = $s1['name'];
		
		$sql 	= "SELECT * FROM sma_workflow_type where id = '$trans_type'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$workflow_type  	 = $s1['workflow_type'];
		
	}

	$message .= "<table cellspacing='0' style='width: 95%; padding-bottom: 5px;padding-top: -5px; border: solid 0px black; text-align: left;margin-left:10px; font-size: 14px;'' border='0' >
			<tr><th style='width: 25%;'> Date  </th><td style='width: 50%;'>:&nbsp;  $dated </td></tr>
			<tr><th style='width: 25%;'> Serial Number </th><td style='width: 50%;'>:&nbsp; $pr_number </td></tr>
			<tr><th style='width: 25%;'> Department </th><td style='width: 50%;'>:&nbsp;  $department_name </td></tr>
			<tr><th style='width: 25%;'> Delivery Address </th><td style='width: 50%;padding:5px;'>: $delivery_address </td></tr>
			</table>";


	$message .=  "<br>";

	$message .= "<table cellspacing='0' style='width: 95%;padding-bottom: 5px;padding-top: -5px; border: solid 0px black; text-align: left;margin-left:10px; font-size: 14px;'' border='0'>
			<tr><th style='width: 25%;'> Required By  </th><td style='width: 50%;'>:&nbsp;  $delivery_require_by </td></tr>
			
			<tr><th style='width: 25%;'> Subject </th><td style='width: 50%;'>:&nbsp;  $subject </td></tr>
			</table>";

	$message .=  "<br>";
	
	$message .=  "<table style='margin-left:10px;'><tr><td ><h4> Background</h4></td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; margin-left:10px; font-size: 13px;' >
			<tr><td style='width: 100%;padding-bottom: 5px;padding-top: -5px;text-align: left;font-size:12px;'> $background_section</td>
			</tr></table>";

	$message .=  "<br>";
	
	$message .=  "<table style='margin-left:10px;'><tr><td ><h4>  Scope of Work</h4></td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; margin-left:10px; font-size: 13px;'  >
			<tr><td style='width: 100%;padding-bottom: 5px;padding-top: -5px;text-align: left;font-size:12px;'> $scope_of_work</td>
			</tr></table>";


	$message .=  "<br>";
			
	$message .=  "<table style='margin-left:10px;'><tr><td ><h4>  Details</h4></td></tr></table>";

	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black;  margin-left:10px; font-size: 12px;' border='.5' >
			<tr><td style='width: 05%;text-align: center;'><b> # </b></td>
				<td style='width: 25%;text-align: lefft;font-size:12px;'><b> Material</b></td>
				<td style='width: 10%;text-align: left;'><b>UOM</b></td>
				<td style='width: 30%;text-align: left;'><b>Specification </b></td>
				
				<td style='width: 10%;text-align: center;'><b> PR Qty. </b></td>
				<td style='width: 10%;text-align: left;padding:5px;'><b>Ref.NOA No.</b></td>
			</tr>";
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_purchase_req_items where purchase_req_id = '$id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$material_id		= $row['product_id'];
		$description		= $row['description'];
		$qty				= $row['quantity'];
		$unit				= $row['unit'];

		$sql 	= "SELECT * FROM sma_product where id = '$material_id'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$material_name  	 = $s1['name'];
		$unit  	 		 = $s1['uom'];
		
		$sql = " SELECT * FROM `sma_purchase_order` where approval_memo_ref = '$id' ";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$ref_po_no  	 = $s1['id'];
		
		$sql = " SELECT * FROM `sma_po_items` where purchase_id = '$ref_po_no' and product_id = '$material_id'";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$po_qty  	 = $s1['quantity'];
		
	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'> ".$i." </td>
				<td style='width: 25%;text-align: left;'>". $material_name . " </td>
				<td style='width: 10%;text-align: left;'> " . $unit . " </td>
				<td style='width: 25%;text-align: left;'>". $description . " </td>
				<td style='width: 10%;text-align: center;'> ".$qty." </td>
				<td style='width: 10%;text-align: center;'> ".$ref_po_no." </td>
				
			</tr>";
	}
	
	$message .= "</table>";
	
			$message .=  "<br>";
	
	$message .=  "<table style='margin-left:10px;'><tr><td ><h4>  Documents</h4></td></tr></table>";
	
	$modulePath = "purchase_requisition/";
	$baseurl2  = $baseurl.$modulePath;
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM file_uploads where module = 'PR' and reference_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name = $row['file_name'];
		$file_path = $row['file_path'];
		$doc_type = $row['doc_type'];
		
		$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
		
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
	}

	$doctype = 'PR';
	$ap_id   = $id;
//	include "../workflow_process_to_mail.php";

	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
    // get the HTML
    ob_start();
    // convert to PDF
//	if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'purchase_req_rep_'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->setDefaultFont('freesans');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
//	}
	
}