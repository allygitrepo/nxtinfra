<?php if($_GET['sub'] == 'list'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><td style='width: 80%;;'>Vendor Master  </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width: 5%;text-align: right;'>Sr.No.</th>
				<th style='width: 8%;'>Vendor Name</th>
				<th style='width: 10%;text-align: left;'>Type</th>
				<th style='width: 10%;text-align: left;'>Category</th>
				<th style='width: 5%;'>Contact Person </th>
				<th style='width: 5%;text-align: left;'>Address</th>
				<th style='width: 8%;'>City</th>
				<th style='width: 10%;'>State</th>
				<th style='width: 10%;text-align:left'>Pincode</th>
				<th style='width: 10%;text-align:left'>Area</th>
				<th style='width: 10%;text-align:left'>Country</th>
				<th style='width: 10%;text-align:left'>Phone-1</th>
				<th style='width: 10%;text-align:left'>Phone-2</th>
				<th style='width: 10%;text-align:left'>Phone-3</th>
				
				<th style='width: 10%;text-align:left'>Mobile-1</th>
				<th style='width: 10%;text-align:left'>Mobile-2</th>
				<th style='width: 10%;text-align:left'>Mobile-3</th>
				<th style='width: 10%;text-align:left'>Email Id</th>
				<th style='width: 10%;text-align:left'>GST No.</th>
				<th style='width: 10%;text-align:left'>Tally Account Name</th>
				<th style='width: 10%;text-align:left'>Bank Name</th>
				<th style='width: 10%;text-align:left'>Bank Account Type</th>
				<th style='width: 10%;text-align:left'>Beneficiary Name</th>
				<th style='width: 10%;text-align:left'>Address</th>
				<th style='width: 10%;text-align:left'>Account No.</th>
				<th style='width: 10%;text-align:left'>IFSC Code</th>
				
			</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		$i =0;
		$sql ='';
		$sql   = "SELECT * FROM `sma_party_mst` order by party_name";
		
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$party_name						= $row['party_name'];
			
			$party_type						= $row['party_type'];
			$sql 	= "select * from sma_type where id = '$party_type' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_type 				= $r2['type'];
			
			$party_category 				= $row['party_category'];
			$sql = "select * from sma_categories where id = '$party_category' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);					
			$party_category 				= $r2['name'];
			
			$party_contact_person_name 		= $row['party_contact_person_name'];
			$party_address_1 				= $row['party_address_1'];
			$party_city 					= $row['party_city'];
				$sql = "select * from cities where id = '$party_city' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$party_city = $r2['city_name'];

	
			$party_state 					= $row['party_state'];
			$sql = "select * from states where id = '$party_state' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$party_state = $r2['state_name'];
			$party_pincode 					= $row['party_pincode'];
			$party_area 					= $row['party_area'];
			$party_country 					= $row['party_country'];
			$party_phone 					= $row['party_phone'];
			$party_phone1 					= $row['party_phone1'];
			$party_phone2 					= $row['party_phone2'];
			$party_mobile 					= $row['party_mobile'];
			$party_mobile1 					= $row['party_mobile1'];
			$party_mobile2 					= $row['party_mobile2'];			
			$party_email 					= $row['party_email'];
			$party_gst_number 				= $row['party_gst_number'];
			$tally_account_name				= $row['tally_account_name'];
			$party_beneficiary_name			= $row['party_beneficiary_name'];
			$party_bank_name				= $row['party_bank_name'];
			$party_bank_account_type		= $row['party_bank_account_type'];
			$party_bank_address				= $row['party_bank_address'];
			$party_bank_account_no			= $row['party_bank_account_no'];
			$party_bank_ifsc_code			= $row['party_bank_ifsc_code'];
			
			$i = $i +1;	
			$message .= "<tr>
				<td style='width: 5%;text-align: right;'>".$i."</td>
				<td style='width: 8%'>".$party_name."</td>
				<td style='width: 8%'>".$party_type."</td>
				
				<td style='width: 10%'>". $party_category."</td>
				<td style='width:5%' >". $party_contact_person_name."</td>
				<td style='width:5%;text-align:left'>". $party_address_1."</td>
				<td style='width:8%' >". $party_city."</td>
				<td style='width:10%'; text-align:left'>". $party_state."</td>
				<td style='width:10%'>". $party_pincode."</td>
				<td style='width:10%'; '>". $party_area."</td>
				<td style='width:10%'>". $party_country."</td>
				<td style='width:10%' >". $party_phone."</td>
				<td style='width:10%' >". $party_phone1."</td>
				<td style='width:10%' >". $party_phone2."</td>
				<td style='width:10%' >". $party_mobile."</td>
				<td style='width:10%' >". $party_mobile1."</td>
				<td style='width:10%' >". $party_mobile2."</td>
				<td style='width:10%' >". $party_email."</td>
				<td style='width:10%' >". $party_gst_number."</td>
				<td style='width:10%' >". $tally_account_name."</td>
				
				<td style='width:10%' >". $party_bank_name."</td>
				<td style='width:10%' >". $party_bank_account_type."</td>
				<td style='width:10%' >". $party_beneficiary_name."</td>
				<td style='width:10%' >". $party_bank_address."</td>
				<td style='width:10%;ext-align:left'  >'". $party_bank_account_no."</td>
				<td style='width:10%' >". $party_bank_ifsc_code."</td>
				
				</tr>";
	
	}
			
			
		$message .= "</table>";
	
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=vendor_master.xls");
		print $message;
	}
	
    // convert to PDF
	if($prn == 'pdf'){
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output('vendor_master.pdf');
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
 }	?>
	


