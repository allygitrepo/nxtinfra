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
	
	$tender_hdr_id	= $_GET['id'];
	$tableName		= "sma_tender_header";
	$sql 	= "SELECT * FROM $tableName where id = '$tender_hdr_id'";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$row = mysqli_fetch_array($result);
		
	$company_id				= $row['company_id'];
		
$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
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

	
	 $message .= '<div class="text-right" style="text-align:right;">
                        <button class="btn btn-info" type="button" onclick="javascript:window.print();"><i class="fa fa-print"></i> Print</button>
                    </div> ';
	
	 $message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
					
	 $message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: left;'>Tender Details</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> " . $comp_name."</td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 12px;'><tr><td style='width: 95%;'>Office Address : ". $comp_addr1.', '.$comp_addr2.', '.$comp_addr3.' '.$comp_city.' Pincode : '.$comp_pincode."</td></tr></table>";
 
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 20px;'>
			<tr><td style='width: 95%;'> Tender</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";
			
	$id				= $_GET['id'];
	$tender_hdr_id	= $_GET['id'];
	$tableName		= "sma_tender_header";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

//	$sql = $_SESSION['sqlex'];

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$tender_no				= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['created_date']));
		$deadline_date			= date('d-m-Y', strtotime($row['deadline_date']));
		$deadline_time			= $row['deadline_time'];
		$trans_type				= $row['trans_type'];
		$company				= $row['company_id'];
		$visible 				= $row['visible'];
		$price_visible			= $row['price_visible'];
		$department  			= $row['department'];
		$tender_title	 		= $row['tender_title'];
		$vender_notes 			= $row['vender_notes'];
		$background 			= $row['background'];
		$scope_of_work 			= $row['scope_of_work'];
		
		$maker					= $row['draft_by'];
		$maker_date				= date('d-m-Y  h:i:s', strtotime($row['draft_dated']));
		
		$sql 	= "SELECT * FROM sma_department where id = '$department'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$department  	 = $dep['name'];
		
	}
	
		$company_id		= $company;
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		$comp_name = $r2['comp_name'];
	
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'TN' and a.doc_id = '$tender_hdr_id' and b.id = a.create_by and status = 'Published' order by a.id desc  ";
		$bs 	= mysqli_query($con,$sql);
		$bs1 	= mysqli_fetch_array($bs);
		$published_date 		= date('d-m-Y', strtotime($bs1['create_date']));
			
	$message .= "<table cellspacing='0' border='1' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 40%;'> Tender No. </th><td style='width: 40%;'>$tender_no </td></tr>
			<tr><th style='width: 40%;'> Department</th><td style='width: 40%;'> $department</td></tr>
			<tr><th style='width: 40%;'>Dated </th><td style='width: 40%;'> $dated  </td></tr>
			<tr><th style='width: 40%;'>Published Date </th><td style='width: 40%;'> $published_date  </td></tr>
			<tr><th style='width: 40%;'>Closed Date</th><td style='width: 40%;'> $deadline_date  </td></tr>
			<tr><th style='width: 40%;'>Payment Terms</th><td style='width: 40%;'> $Payment_terms  </td></tr>
			<tr><th style='width: 40%;'>Retention %</th><td style='width: 40%;'> $retention </td></tr>
			</table>";

	if(!empty($vender_notes)){
		$message .= "<p><b> Internal Note </b></p>";
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			
			<tr><th style='width: 100%;'>  $vender_notes</th></tr>
			</table>";
	}
	
	if(!empty($background)){
		$message .= "<p><b> Vendor Scope of Work / Specification </b></p>";	
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='1'>
			<tr><td style='width: 100%;'>".$background." </td></tr>
			</table>";
		
	}
	
	
	if(!empty($scope_of_work)){
		$message .= "<p><b> Scope of Work </b></p>";		
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:0px;font-size: 12pt;' border='0'>
			<tr><td style='width: 100%;'>".$scope_of_work." </td></tr>
			</table>";
	}
	
	$message .= "<BR>";
		
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'>Product Details</th></tr></table> ";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: ; margin-left:0px; font-size: 13px;' >
			<tr><td style='width: 10%;text-align: Center;font-size:12px;'> SrNo.</td>
				<td style='width: 30%;text-align: left;'>Item Name </td>
				<td style='width: 30%;'> Description </td>
				<td style='width: 10%;text-align: right;'> Qty </td>
				<td style='width: 10%;text-align: right;'> </td>
				
			</tr></table>";
			
	$message .= '<table cellspacing="0" style="width: 95%; margin-left:0px; border: solid 1px #000000; ">';
	
	$total_selected_value = 0;
	$sql 	= "SELECT * FROM sma_tender_items where tender_hdr_id = '$tender_hdr_id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$category_id		= $row['category_id'];
		$category_id		= $row['category_id'];
		$material_name		= $row['material_name'];
		$material_desc   	= $row['material_desc'];
		$quantity			= $row['quantity'];

	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'> ".$i." </td>
				<td style='width: 30%;text-align: left;'>". $material_name . " </td>
				<td style='width: 30%;text-align: left;font-size: 12px;'> " . $material_desc . " </td>
				<td style='width: 10%;text-align: right;'> " . $quantity . " </td>
				<td style='width: 10%;text-align: right;'>  </td>
				
			</tr>";
	}
	
	$message .= "</table>";
	
	$message .= "<BR>";
	
//Supplier Details		
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'>Supplier Details</th></tr></table> ";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: ; margin-left:0px; font-size: 13px;' >
			<tr><td style='width: 10%;text-align: Center;font-size:12px;'> SrNo.</td>
				<td style='width: 30%;text-align: left;'>Supplier Name </td>
				<td style='width: 30%;'> Email ID </td>
				<td style='width: 10%;text-align: left;'> Received Status </td>
				
			</tr></table>";
			
	$message .= '<table cellspacing="0" style="width: 95%; margin-left:0px; border: solid 1px #000000; ">';
	
	$i = 0;
	$total_selected_value = 0;
	$sql 	= "SELECT * FROM sma_tender_supplier where tender_hdr_id = '$tender_hdr_id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$supplier_id		= $row['supplier_id'];
		$email_id			= $row['email_id'];
		$quotation_received	= $row['quotation_received'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_id'";
		$res = mysqli_query($con,$sql);
		$r2 = mysqli_fetch_array($res);
		$supplier_name		= $r2['party_name'];
		
		if($quotation_received=='Y'){
			$quotation_received = 'Yes';
		}
		
		$sql 	= "SELECT * FROM sma_tender_supplier_quote where supplier_id = '$supplier_id'";
		$res = mysqli_query($con,$sql);
		$affected_rows = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_array($res);
		$rate		= $r2['rate'];
		if($affected_rows==0){
			$quotation_received = 'No';
		}
		
	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'> ".$i." </td>
				<td style='width: 30%;text-align: left;'>". $supplier_name . " </td>
				<td style='width: 30%;text-align: left;font-size: 12px;'> " . $email_id . " </td>
				<td style='width: 10%;text-align: left;'> " . $quotation_received . " </td>
			</tr>";
	}
	
	$message .= "</table>";
	
	
		$message .= "<BR>";
	
//Comparison		
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'>Comparison</th></tr></table> ";

	$ii = 0;
	$i = 0;
	$sql 	= "SELECT * FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' order by supplier_id , tender_item_id ";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$supplier_id		= $row['supplier_id'];
		$tender_item_id		= $row['tender_item_id'];
		$quantity			= $row['quantity'];
		$rate				= $row['rate'];
		$gst				= $row['gst'];
		
		$selected_vendor	= $row['selected_vendor'];
		if($selected_vendor=='Y'){
			$supplier_id_selected = $row['supplier_id'];	
			$selected_amount	= $row['selected_amount'];
			$selected_remarks	= $row['selected_remarks'];
			$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_id_selected'";
			$res = mysqli_query($con,$sql);
			$r2 = mysqli_fetch_array($res);
			$supplier_name_selected		= $r2['party_name'];
		}
		
		if($supplier_id!=$supplier_id_prev){
			
			$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_id'";
			$res = mysqli_query($con,$sql);
			$r2 = mysqli_fetch_array($res);
			$supplier_name		= $r2['party_name'];
			
			$sql 	= " SELECT  sum((rate * quantity ) + ((rate * quantity) * gst /100 )) as supplier_total FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' and supplier_id = '$supplier_id' ";
			$res = mysqli_query($con,$sql);
			$r2 = mysqli_fetch_array($res);
			$supplier_total		= $r2['supplier_total'];
			
			$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: ; margin-left:0px; font-size: 13px;' >
				<tr><td style='width: 10%;text-align: Center;font-size:12px;'> Supplier ".++$ii. "</td>
					<td style='width: 30%;text-align: left;font-size:15px;'><b>".$supplier_name."</b></td>
					<th style='width: 30%;'> Overall Total Rs.". number_format($supplier_total,2)."</th>
				</tr></table>";
				
			$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: ; margin-left:0px; font-size: 13px;' >
				<tr><td style='width: 10%;text-align: Center;font-size:12px;'><b> SrNo.</b></td>
					<td style='width: 25%;text-align: left;'><b>Product Name </b></td>
					<td style='width: 20%;text-align: Center;'><b> Qty in Rs. </b></td>
					<td style='width: 15%;text-align: right;'><b> Rate in Rs. </b></td>
					<td style='width: 15%;text-align: right;'><b> GST in Rs. </b></td>
					<td style='width: 15%;text-align: right;'><b> Total Amount in Rs. </b></td>
				</tr>";
			
			$i = 0;
		}
		
			
			$sql 	= "SELECT a.* FROM sma_product a, sma_tender_items b where a.id = b.material_id and b.id = '$tender_item_id'";
		//echo $sql;	
			$res = mysqli_query($con,$sql);
			$error  = mysqli_error($con);
			if(!empty($error)){ echo "ERROR : " . $error; exit();}
			$r2 = mysqli_fetch_array($res);

			$product_name		= $r2['name'];
			
			++$i;
			$message .= "<tr>
					<td style='width: 10%;text-align: Center;'> ".$i." </td>
					<td style='width: 25%;text-align: left;'>". $product_name . " </td>
					
					<td style='width: 20%;text-align: center;font-size: 12px;'> " . $quantity . " </td>
					<td style='width: 15%;text-align: right;font-size: 12px;'> " . $rate . " </td>
					<td style='width: 15%;text-align: right;font-size: 12px;'> " . number_format((($rate * $quantity) * $gst /100 ),2) . " </td>
					<td style='width: 15%;text-align: right;font-size: 12px;'> " . number_format(($rate * $quantity) + (($rate * $quantity) * $gst /100 ),2) . " </td>
					
				</tr>";
		
		$supplier_id_prev =  $supplier_id;
	
	}
	
	$message .= "</table>";
	
	$message .= "<br><table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; background: ; font-size: 20px;' border='1' >";		
	$message .= "<tr><td>";
	$message .=  "<center><b>Final Amount : Rs.".number_format($selected_amount,2)."/- " . " Selected Vendor : ". $supplier_name_selected . "</b></center>";	
	$message .= "</td></tr></table>";
	
	$message .=  "<h4> Approval Process</h4>";	
	
$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; background: ; font-size: 13px;' border='1' >";		
$message .= "<tr>
					<th style='width: 35%;text-align: left;'>Decision by </th>
					<th style='width: 25%;text-align: left;'>Status </th>
					<th style='width: 40%;text-align: center;'> Date Time </th>				
					</tr></table>";			

$message .= "<table cellspacing='0' border='.3' style='width: 95%; border: solid 0px black;  font-size: 10pt;' > ";				
//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'TN' and a.doc_id = '$tender_hdr_id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			$message .= "<tr>
					<td style='width: 35%;text-align: left;'>". $approval . " </td>
					<td style='width: 25%;text-align: left;'>". $status . " </td>
					<td style='width: 40%;text-align: center;'>" . $approval_date . " </td>				
					</tr> ";			
			
		}
			
		$message .= "</table>";
//	}
	
	
	$message .=  "<h4> Documents</h4>";
	//athaangSI\tender
	$modulePath = "tender/";
	$baseurlsi  = "https://athaang.in/p2p2023/athaangSI/";
	//$baseurlsi	= $baseurlsi."athaangSI/";
	
	$baseurl2  = $baseurlsi.$modulePath;
	
	//$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_tender_file_upload where 1 and tender_hdr_id = '$tender_hdr_id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name 		= $row['file_name'];
		$file_path 		= $row['file_path'];
		$doc_type 		= $row['doc_type'];
		$supplier_id 	= $row['supplier_id'];
		$doc_desc	 	= $row['doc_desc'];
		$share_point_link = $row['share_point_link'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_id'";
		$res = mysqli_query($con,$sql);
		$r2 = mysqli_fetch_array($res);
		$supplier_name		= $r2['party_name'];
		
		$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
		//$baseurl1 = $share_point_link;
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr>
			<td style='width: 25%;' > $supplier_name</td>
			<td style='width: 25%;' >$doc_desc</td>
			<td style='width: 50%;text-align: left;font-size:12px;'><a href='$baseurl1' target='_blank'>$baseurl1</a></td></tr></table>";
	
	}
	
	$ln  = 2;
	$l   =  $i;
	
	for($l = $l; $l < $ln; $l++){
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:0px; font-size: 10pt;' border='0'>
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
		
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
	
	 $message .= '<div class="text-right" style="text-align:right;">
                        <button class="btn btn-info" type="button" onclick="javascript:window.print();"><i class="fa fa-print"></i> Print</button>
                    </div> ';
					
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
		
print $message;
exit();
	
    // get the HTML
    ob_start();
	
	$fl_name = "tender_view_".$tender_hdr_id.".pdf";
	
    // convert to PDF
	//if($prn=='pdf'){
	//$baseurl
		//$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'approval_notes_'.$id. '.pdf';
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
//	}
	
}
