<?php
//	session_start();
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

	$upload_error ='';
	$doc_no_prev ='';
	$counter = 0;
	$message =' Vendor Data Updated to Tally';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<td style='width: 10%;'><b>Vendor Name</b> </td>
					<td style='width: 10%;'><b>GST No.</b> </td>
					<td style='width: 10%;'><b>PAN No.</b> </td>
					<td style='width: 10%;'><b>MSME No.</b> </td>
					<td style='width: 10%;'><b>Email</b> </td>
				</tr></table>";

	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";

	$sql = " SELECT * FROM `sma_party_mst` 
					WHERE 1 AND add_to_tally = 'R' ";
echo $sql. "<BR>";						 
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$party_id 					= $row['id'];
		$party_name 				= $row['party_name'];
		$tally_account_name			= $row['tally_account_name'];
		$party_email 				= $row['party_email'];
		$party_gst_number 			= $row['party_gst_number'];
		$party_pan_number 			= $row['party_pan_number'];
		$party_msme_number			= $row['party_msme_number'];
		
		$message .= "<tr>
				<td style='width: 10%;'>$party_name</td>
				<td style='width: 10%;'>$party_gst_number</td>
				<td style='width: 10%;'>$party_pan_number</td>
				<td style='width: 10%;'>$party_msme_number</td>
				<td style='width: 10%;'>$party_email</td>
				</tr>";
		
		$sql = "UPDATE sma_party_mst set add_to_tally ='Y' where id = '$party_id' ";
		mysqli_query($con,$sql);
//echo $sql. "<BR>";		
			$sql = "INSERT INTO tally_party_name ( party_name, party_contact_person_name, party_address_1, party_city, party_state, party_pincode, party_phone, party_mobile, party_websites, party_email,  party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tally_account_name, status ) 
			SELECT tally_account_name, party_contact_person_name, party_address_1, party_city, party_state, party_pincode, party_phone, party_mobile, party_websites, party_email,  party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tally_account_name, ''
			FROM sma_party_mst WHERE id = '$party_id' ";
			mysqli_query($con, $sql);
//echo $sql. "<BR>";
		$counter = $counter + 1;
		
	}

	$message .="</table>";
	
	echo $message ;
	
    echo " Vendor data transfer to Tally DB. ";

	if($counter > 0){
		include "vendor_tally_mail.php";
	}
	
	
	echo "<script>window.close();</script>";	
	
	exit();
	
	