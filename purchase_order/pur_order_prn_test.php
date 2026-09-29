<?php 
if($_GET['sub'] == 'pdf'){
	session_start();
	ini_set('display_errors','Off');	
	//include("../header.php");
	include("../baseurl.php");
	include "../dbcon.php";
	
	$modulePath = "purchase_order/"; 
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	$print_flag	= $_GET['print_flag'];
	
	$user   	= $_SESSION['user'];
    $userid   	= $_SESSION['usrid'];
	$role		= $_SESSION['role'];
	
	$tableName		= "sma_purchase_order";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";
	$result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
	$to_supplier			= $row['to_supplier'];
	$draft_by				= $row['draft_by'];
	$po_number				= $row['po_number'];
		
	$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);	
	$party_email     = $row['party_email'];
				
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
		if(!($_POST['id'])){	
			echo "<script>alert('File not found...');window.close();</script>";
			
			return;
		}
	}
	
	$prn		= $_GET['sub'];
	
	if($_GET['id']){
		$id			= $_GET['id'];
		$comp_id	= $_GET['comp_id'];	
		$location   = $_GET['location'];
		$print_flag = $_GET['print_flag'];
	}
	/* else {
		
		$id			= $_POST['id'];
		$comp_id	= $_POST['comp_id'];	
		$location   = $_POST['location'];

	} */
//echo $id. ' ' . $comp_id. ' ' . $location.  ' <<<>>> '. $print_flag;
//exit();
	
	//$print_flag = $_POST['print_flag'];
	//$viewm		= $_POST['viewm'];
	
	$sql 	= "SELECT * FROM `sma_location` where id = '$location'";	
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$loc_name    = $row['loc_name'];
		$loc_addr1   = $row['loc_addr1'];
		$loc_state    = $row['loc_state'];
		$loc_pincode = $row['loc_pincode'];
		$loc_phone   = $row['loc_phone'];
		$loc_mobile  = $row['loc_mobile'];
		$loc_email   = $row['loc_email'];
		$loc_pan_no  = $row['loc_pan_no'];
	//	$loc_gst_no  = $row['loc_gst_no'];
		$loc_contact_person = $row['loc_contact_person'];
		$loc_contact_person_mobile = $row['loc_contact_person_mobile'];
	}
	
$sql="SELECT * FROM `company` where comp_id = '$comp_id' ";
$comresult 	= mysqli_query($con,$sql);
if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$error  			= mysqli_error($con);
	$com 				= mysqli_fetch_array($comresult);
	
	$comp_code 			= $com['comp_code'];
	$comp_name 			= $com['comp_name'];
	$comp_addr1 		= $com['comp_addr1'];
	$comp_addr2 		= $com['comp_addr2'];
	$comp_addr3 		= $com['comp_addr3'];
	$comp_email 		= $com['comp_email'];
	$comp_office 		= $com['comp_office'];
	$comp_mobile 		= $com['comp_mobile'];
	$comp_city  		= $com['comp_city'];
	$comp_state			= $com['comp_state'];
	$comp_pincode 		= $com['comp_pincode'];
	$comp_country 		= $com['comp_country'];
	$comp_faxno 		= $com['comp_faxno'];
	$comp_cin_no 		= $com['comp_cin_no'];
	$comp_pan_no 		= $com['comp_pan_no'];
	$general_terms		= $com['general_terms'];
	$header_terms		= $com['header_terms'];
	$comp_gst_no        = $com['comp_gst_no'];
	
    $point_of_name       = $com['point_of_name']; 
	$point_of_email      = $com['point_of_email'];
	$point_of_phone      = $com['point_of_phone'];
	$spv_head_name       = $com['spv_head_name'];
	$spv_head_phone      = $com['spv_head_phone'];
	
	$billing_address1	= $com['comp_register_address1'];
	$billing_address2	= $com['comp_register_address2'];
	$billing_address3	= $com['comp_register_address3'];
	$billing_pincode	= $com['comp_register_pincode'];
		
	
	//$loc_pan_no         = $comp_pan_no;
	//$loc_gst_no         = $com['comp_gst_no'];
	
	$logo_file_name		= $com['logo_file_name'];
	$logo_dir_name		= $baseurl.'setting/'.'upload/';
	
	$logo_fl			= $logo_dir_name.$logo_file_name;
	
/* 	if(empty($logo_file_name)){
		
		$logo_fl			= $logo_dir_name . 'SK1Logo.png';
	
	} */	

		//$locdir 			= 'c:/xampp-7.4/htdocs//p2p2023/';
		$locdir 			= '../';
		$logo_dir_name		= $locdir.'setting/'.'upload/';
		$logo_fl			= $logo_dir_name . 'AthanglogoColor.jpg';
		
		if($comp_code == 'DJHPL' || $comp_code == 'VKEPL'){
		    
		    $logo_fl=''    ;
		    
		}
		
	$message ='';

	$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
	
	if(!empty($comp_addr2)){
		$comp_addr1 .= $comp_addr2.'';	
	}
	if(!empty($comp_addr3)){
		$comp_addr1 .= "<BR>".$comp_addr3.'';	
	}
	
//	$id				= $_POST['id'];
	$tableName		= "sma_purchase_order";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$po_doc_type 			= $row['po_doc_type'];
		$old_po_no	 			= $row['old_po_no'];
		
		$old_po_number = '';
		if($old_po_no>0){
			$sql 	= "SELECT * FROM sma_purchase_order where id = '$old_po_no'";
			$qry 	= mysqli_query($con,$sql);
			$r2 	= mysqli_fetch_array($qry);
			$old_po_number	 		= $r2['po_number'];
			$old_dated  			= date('d-m-Y', strtotime($r2['dated']));
		}
		
		$po_desc = "Purchase Order";
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
		
		$approval_memo_ref		= $row['approval_memo_ref'];
		$quotation_reference_no = $row['quotation_reference_no'];
		$po_number  			= $row['po_number'];
		$po_rev		  			= $row['po_rev'];
		$location	 			= $row['location'];
		$discount 				= $row['discount'];
		$transport 				= $row['transport'];
		$other_charges 			= $row['other_charges'];
		$terms 					= $row['terms'];
		$subject				= $row['subject'];
		$notes					= $row['notes'];
		$status					= $row['status'];
		$delivery_address		= $row['delivery_address'];
		$supplier_location		= $row['supplier_location'];
		
		$sql 	= "SELECT create_by, create_date, status
				FROM `workflow_history` 
					where doc_type = 'PO' and doc_id = '$id' and status in ('Completed', 'Approved') order by id desc ";
		$bs 	= mysqli_query($con,$sql);
		$bs1 	= mysqli_fetch_array($bs);
		
		if( $status=='Completed' || $status == 'Approved' ){
		    $podated 	= date('d-m-Y', strtotime($bs1['create_date']));
		}
        else {
            $podated 	= $dated;
        }
		
		for($l = 0; $l < 9; $l++){
				$space2.='&nbsp;';
		} 
		$pono = $po_desc ." No.:". $po_number .$space2.$space2.$space2."&nbsp; Dated : ".$podated  ;
		
		$backtop = '45mm';
		if($old_po_no>0){
			$pono .= "<BR>Amended From ".$po_desc ." No.:". $old_po_number .$space2."Date &nbsp;&nbsp;: ".$old_dated  ;
			$backtop = '45mm';
		}
			
				
	$message .= "<table cellspacing='0' style='width: 93%; border: solid 0px black; margin-left:45px;'> ";
	
	if($comp_code != 'DJHPL' && $comp_code != 'VKEPL'){	
//	    $message .= "<tr><td style='width: 66%;font-size: 19px;text-align: left;'><b>" . $comp_name. "</b></td><td rowspan='5' style='width: 30%;text-align: left;'><img src='".$logo_fl."'height='20%' width='20%'></td></tr>";
	}
	else {
//	    $message .= "<tr><td style='width: 66%;font-size: 19px;text-align: left;'><b>" . $comp_name."</b></td><td rowspan='5' style='width: 30%;text-align: left;'></td></tr>";
	}

//	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; '><tr><td rowspan='5' style='text-align: right;'></td><td style='width: 70%;font-size: 20px;text-align: right;'>" . $comp_name."</td></tr>";
//$message .= "<tr><td style='width: 70%;font-size: 12px;text-align: right;'>".$loc_name."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>"."". $comp_addr1.' '.$comp_city.','.$comp_pincode.','.$comp_state."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>Phone: ".$comp_office.", E-mail : ".$comp_email."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>CIN No.: ".$comp_cin_no.", GST No.: ".$comp_gst_no."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>PAN No.: ".$comp_pan_no."</td></tr>";
	$message .= "</table>";
	

		//echo $terms;
//exit();		
		$po_doc_type 			= $row['po_doc_type'];
		
	}
	
	if( ($status =='Draft' || $status == 'Submitted' ) && $print_flag=='V' ){
		$messagee .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;color:#c9d6d6;font-size:72;'>&nbsp;Draft </td></tr></table>";
	}
	
$head = $message;

for ($ij=0;$ij<76;$ij++){
	$hline.='_';
}

$pono .= $messagee;

$message ='';
	
	
$message .= '<page backtop="'.$backtop.'" backbottom="16mm" backleft="10mm" backright="2mm" pagegroup="new">
    <page_header>
        <table class="page_header" style="width: 103%; text-align: center;font-size: 18pt">
            <tr>
                <td style="width: 103%; text-align: center123;text-align: center;">
                    '.$head .'
                </td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 12pt;text-align: left;margin-left:20px;margin-top:5px; padding-left: 48px;">
						'.$hline.
                '</td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 12pt;text-align: left;margin-left:20px;padding-top: 4px;padding-left: 48px;">
                    '.$pono .'
                </td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 5pt;text-align: left;margin-left:20px;margin-top:5px; padding-left: 48px;">&nbsp;
					</td>
            </tr>
			<br><br>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" style="width: 100%; text-align: right" >
            <tr>
                <td style="width: 100%; text-align: right">
                    Page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
</page>';
	
	
	if($po_rev>0){
			$po_number = $po_number . '-' . $po_rev;
	}	
	
	
	//$pono = $po_desc ."No.:". $po_number ."/ PO.Dated : ".$podated  ;
	//$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:10px; font-size: 14px;'>
	//		<tr><th style='width: 95%;'> $po_desc No.: $po_number / PO.Dated : $podated </th></tr></table>";

	$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$party_name  	 = $row['party_name'];
		//$party_name		 = $row['tally_account_name'];
		$party_address_1 = $row['party_address_1'];
		$party_address_2 = $row['party_address_2'];
		$party_address_3 = $row['party_address_3'];
		$party_city  	 = $row['party_city'];
		$party_pincode   = $row['party_pincode'];
		$party_mobile    = $row['party_mobile'];
		$party_email     = $row['party_email'];
		$party_gst_number    = $row['party_gst_number'];
		$party_contact_person_name = $row['party_contact_person_name'];
	}
	$sql 	= "SELECT * FROM cities where id = '$party_city'";
	$result = mysqli_query($con,$sql);
	$cty = mysqli_fetch_array($result);
	$party_city  = $cty['city_name'];

	$space1='';
	$space2='';
	for($l = 0; $l < 10; $l++){
		$space1.='&nbsp;';
	} 
	for($l = 0; $l < 14; $l++){
		$space2.='&nbsp;';
	} 
	
	$con_txt= '';
	$mob_txt= '';
	//if(!empty($party_contact_person_name)){
		$con_txt = "<br>".'Contact Person : '. $party_contact_person_name;
	//}
	
	//if(!empty($party_mobile)){
		$mob_txt = "<br>".'Mobile ' . ": ". $party_mobile;
	//}
	
	$message .= "<BR>";

//Contract Aggreement Start
if($po_doc_type!='CA'){
		
	$message .= "<table cellspacing='0' style='width: 96%; border:  0px black; text-align: left;margin-left:10px; font-size: 14px; ' border='0.5'>
			<tr>
			<td style='width: 50%;text-align: left; padding-left: 4px;'><div>To, </div>
			 "."<b>". $party_name ."</b>"
			 ."<br>". $party_address_1
			 ."<br>". $party_address_2."". $party_address_3."". $party_city." Pincode: ". $party_pincode
			
			 ."</td> 
			<td style='width: 50%;text-align: top;  padding-left: 4px;'><div  ><b>Quotation Details </b></div>"
			."Ref.No : ".$quotation_reference_no
			."<br>Date : ".$dated
			 ."<br>Email : ". $party_email
			 . $con_txt
			 . $mob_txt
			."<br> GST No. : ". $party_gst_number
			."&nbsp;&nbsp;</td></tr>";
			//</table>";
	
	if (!empty($loc_city)){
		$loc_city = 'City - '. $loc_city;
	}
	
	if (!empty($loc_mobile)){
		$loc_mobile = 'Mobile- '. $loc_mobile;
	}
	$loc_pincode = " Pincode: ". $loc_pincode ;
	
	$dadr = '';
	/* if(!empty($loc_addr1)){
		$dadr = explode(',',$loc_addr1);
		$bill_addr1 = $dadr['0']. ' '.$dadr['1'].',';
		$bill_addr2 = $dadr['2']. ' '. $dadr['3'].',';
		$bill_addr3 = $dadr['4']. ' ' . $dadr['5'].'';
		$bill_city  = $dadr['6']. ' '.$dadr['7'];
		$bill_pincode=$dadr['8'];
	} */
	
	$bill_addr1	 	= $billing_address1;
	$bill_addr2	 	= $billing_address2;
	$bill_addr3	 	= $billing_address3;
	$bill_pincode 	= $billing_pincode;
	
	$dadr =='';
	if(!empty($delivery_address)){
		$dadr = explode(',',$delivery_address);
		$loc_addr1 = $dadr['0']. ' '.$dadr['1'].',';
		$loc_addr2 = $dadr['2']. ' '. $dadr['3'].'';
		$loc_addr3 = $dadr['4']. ' ' . $dadr['5'].'';
		$loc_city  = $dadr['6']. ' '.$dadr['7'];
		$loc_pincode=$dadr['8'];
	}
	
	$gst_amt =0;
	
	$con_txt= '';
	$mob_txt= '';
	if(!empty($loc_contact_person)){
		$con_txt = "<br>".'Contact Person : '. $loc_contact_person;
	}
	
	if(!empty($loc_contact_person_mobile)){
		$mob_txt = "<br>".'Contact Mobile ' . ": ". $loc_contact_person_mobile;
		$lnbr1 = "<br>";
	}
	
	if(!empty($spv_head_name)){
		$spv_head_txt = "<br>".'SPV Head Name : '. $spv_head_name;
		$lnbr1 = "<br>";
	}
	
	if(!empty($spv_head_phone)){
		$spv_phone_txt = "<br>".'Phone : '. $spv_head_phone;
		$lnbr3 = "<br>";
	}
	
	if(!empty($loc_city) && !empty($loc_pincode) ){
	    $loc_city = "<br>".$loc_city. '' . " ". $loc_pincode ;
	}
	
	if(!empty($loc_addr3)){
	    
	    $loc_addr3 = "<br>".trim($loc_addr3);
	}
	
	//$message .= "<table cellspacing='0' style='width: 95%; border:  0px black; text-align: left;margin-left:10px; font-size: 14px;padding-left: 4px;'>
	$message .= "		<tr>
			<td style='width: 50%;text-align: left;padding-left: 3px;'> <div><b>Billing Address </b></div>". $bill_addr1 
			 ."<br>". $bill_addr2
			 ."<br>". $bill_addr3." ". $bill_pincode
			 . $con_txt
			 . $mob_txt
			 . trim($spv_head_txt)
			 . trim($spv_phone_txt)
			 ."</td>
			<td style='width: 50%;text-align: left; padding-left: 4px;'><div><b>Delivery Address</b></div>".trim($loc_addr1)
			."<br>".trim($loc_addr2)
			.$loc_addr3
			.$loc_city
			.$lnbr1
			.$lnbr2
			.$lnbr3
			."</td>
			</tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 5pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";

	//$message .=  " Subject : " .$subject;
	
	$message .= "<table border='0' cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 14px;'>
			<tr><td style='width: 101%;border-right: solid 0.5px #000;border-left: solid 0.5px #000;border-top: solid 0.5px #000;padding:3px;'><b> Subject </b> : $subject</td></tr></table> ";

	
	/* $message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";
background: #E7E7E7;
 */
	$message .= "<table border='0.5' cellspacing='0' style='width: 95%; text-align: center;margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 06%;font-size:12px;padding:3px;'><b> SrNo.</b></td>
				<td style='width: 37%;padding:3px;'><b> Item Description </b></td>
				
				<td style='width: 11%;padding:3px;'><b> Qty. </b></td>
				<td style='width: 10%;padding:3px;'><b> Unit </b></td>
				<td style='width: 5%;font-size:11px;padding:3px;'><b> TDS% </b></td>
				<td style='width: 10%;padding:3px;'><b> Rate </b></td>
				<td style='width: 07%;padding:3px; '><b> GST% </b></td>
				<td style='width: 15%;padding:3px;'><b> Amount (INR)</b></td>
			</tr>";//</table>
	
	$ln =0;
	
	//$id		= $_GET['id'];
	
	$product_desc_available = '';
	$sql 	= "SELECT count(*) as cnt FROM sma_po_items where 1 and quantity > 0 and purchase_id = '$id'";
	$result = mysqli_query($con,$sql);
	//$items_cnt = mysqli_affected_rows($con);
	$row = mysqli_fetch_array($result);
	//$cnt	= $row['cnt'];
		
	$sql 	= "SELECT * FROM sma_po_items where 1 and quantity > 0 and purchase_id = '$id'";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	
	while($row = mysqli_fetch_array($result)){
	
		$quantity		= $row['quantity'];
		$unit_rate		= round($row['unit_rate'],2);
		$pod_discount	= $row['pod_discount'];
		$gst			= $row['gst'];
		$tds			= $row['tds'];
		$product_desc   = $row['product_desc'];
		$unit		    = $row['uom'];
		$delivery_date  = date('d-m-Y', strtotime($row['delivery_date']));
		
		$tot_qty		= $quantity;
		$actual_amt     = $quantity * $unit_rate;
		$total_amt		= $total_amt + $actual_amt;
		
		//$net_amt  		= round($actual_amt - ($actual_amt * $pod_discount / 100),0);
		
		$net_amt  		= round($actual_amt ,0);
		
		
		$total_net_amt	= $total_net_amt + $net_amt;
		
		$delivery_date  = date('d-m-Y', strtotime($row['delivery_date']));
		if($delivery_date=='01-01-1970' || $delivery_date=='31-12-1969' || $delivery_date=='30-11--0001' ){
			$delivery_date ='';
		}	
		$product_id=$row['product_id'];
		$sql="Select * from sma_product where id = '$product_id'";
		$output = mysqli_query($con,$sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($output);

		$product_name = $r2['name'];
		if(empty($unit)){
		    $unit		  = $r2['uom'];
		}
		
		$hsn_code	  = $r2['hsn_code'];
		
		$gst_amt  	  = $gst_amt + round(($net_amt - $discount )* $gst / 100,0);

//echo $product_name. ' ' . $iz++;
		
		if($supplier_location != 'O'){
		    $sgst = $gst_amt / 2;
		    $cgst = $gst_amt / 2;
		}
		
	    ++$i;
		
		$ln = $ln + 1;
		
		if($cnt > 45 ){
			
			$message .= "<tr style='border-collapse:collapse;'><td colspan='8'> </td></tr> ";
					
			$message .= "
			<tr><td style='width: 06%;font-size:12px;'><b> SrNo.</b></td>
				<td style='width: 37%;'><b> Item Description </b></td>
				
				<td style='width: 11%;'><b> Qty. </b></td>
				<td style='width: 10%;'><b> Unit </b></td>
				<td style='width: 5%;font-size:12px;'><b> TDS% </b></td>
				<td style='width: 10%;'><b> Rate </b></td>
				<td style='width: 07%; '><b> GST% </b></td>
				<td style='width: 15%;'><b> Amount (INR)</b></td>
			</tr>";
			
			$cnt = 0;
			//$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
			//$message.="<table border='0' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;' >";
			//$message .= "<tr><td style='width: 6%;text-align: Center;'> &nbsp; </td></tr> ";
			//$message .= "</table>";
			$ln = 0;
			//$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
			//echo $message;
			//exit();
			
		}
		
		if($delivery_date=='31-12-1969' || $delivery_date=='01-01-1970'){
			$delivery_date ='';
		}
		
		$lin_cnt = 40;
		
		$product_len = strlen(trim($product_name));
		if($product_len > $lin_cnt){
		    if($product_len > $lin_cnt){
		        $product_ln_cnt = round($product_len / $lin_cnt,0);  
		        $cnt = $cnt + $product_ln_cnt;
	    	}
		    $cnt = $cnt + 1;
		
		}
		else {
		    $cnt = $cnt + 1;
		}
		
		$product_desc_len = strlen(trim($product_desc));
		if($product_desc_len > $lin_cnt){
		    $product_desc_available = 'Y';
		    $product_ln_cnt = round($product_desc_len / $lin_cnt,0);    
		    //$cnt = $cnt + 1;
		}
		else if($product_desc_len>1){
		    $product_desc_available = 'Y';
		    $cnt = $cnt + 1;
		}
		
		if($product_desc_len > $lin_cnt){
		    
		    $cnt = $cnt + 1;
		
		}
		if(!empty($product_desc)){
		    
		    $cnt = $cnt + $product_ln_cnt;
		
		}
		
	//	$message.="<table border='0.2' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 12px;' >";
		//$message .= "<tr><td style='width: 6%;text-align: Center;'> ".$i." </td>
		//		<th style='width: 45%;text-align: left;' >". $product_name . " </th><td></td><td></td><td></td><td></td><td></td></tr>"; $product_desc
		$message .= "<tr><td style='width: 6%;text-align: Center;valign=top;'> ".$i. ' ' ." </td>
				<td style='width: 37%;text-align: left;font-size: 11px;padding:2px;valign=top;'> <b><span font-size: 13px;>" .trim($product_name)."<br>".trim($product_desc)."</span></b></td>
				
				<td style='width: 11%;text-align: right;valign=top;'> &nbsp;&nbsp;".$quantity." </td>
				<td style='width: 10%;text-align: center;valign=top;'> " . $unit . " </td>
				<td style='width: 5%;text-align: center;valign=top;'> " . $tds . " </td>
				<td style='width: 10%;text-align: right;valign=top;'> ".moneyFormatIndia($unit_rate)." </td>
				<td style='width: 7%;text-align: center;valign=top;'> ".$gst." </td>
				<td style='width: 15%;text-align: right;valign=top;'> ".moneyFormatIndia($net_amt)." </td>
			</tr>";
		//$message .= "</table>";
	}
	
	$message .= "</table>";

//echo $message;
//exit();
	
	if($product_desc_available=='Y'){
	    $ln  = 30 - $cnt ;
	}
	else {
	   $ln  = 28 - $cnt ;
	}
	$l   =  $i;

	if($status=='Draft' || $status=='Completed' || $status=='Submitted'){
    	for($l = $l; $l < $ln; $l++){
    		$message .= "<table border='0' cellspacing='0' style='width: 95%; text-align: center;margin-left:10px; font-size: 12px;' >";
    		$message .= "<tr>
    					<td style='width: 6%;text-align: Center;border-left: solid 1px #000;'>  &nbsp;</td>
    				<td style='width: 42%;text-align: left;'> &nbsp; </td>
    				
    				<td style='width: 11%;text-align: center;'> &nbsp; </td>
    				<td style='width: 10%;text-align: center;'> &nbsp; </td>
    				<td style='width: 10%;text-align: right;'> &nbsp; </td>
    				<td style='width: 7%;text-align: right;'> &nbsp; </td>
    				<td style='width: 15%;text-align: center;border-right: solid 1px #000;'> &nbsp; </td>
    				</tr>";
    		$message .= "</table>";	
    	}
	}
	

	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
			<tr><td style='width: 78%;text-align: right;padding:3px;'> Net Total </td>
				<td style='width: 18%;text-align: right;padding:3px;' > ".moneyFormatIndia($total_net_amt)."</td>
			</tr></table>";
//	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
//			<tr><td style='width: 78%;text-align: right;'>Discount </td>
//				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($discount)." &nbsp;</td>
//			</tr></table>";

    if($gst_amt>0){
        if($supplier_location != 'O'){
    		    $sgst = $gst_amt / 2;
    		    $cgst = $gst_amt / 2;
    		    $message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
    			<tr><td style='width: 78%;text-align: right;padding:3px;'>SGST  </td>
    				<td style='width: 18%;text-align: right;padding:3px;' > ".moneyFormatIndia($sgst)."</td>
    			</tr></table>";	
    			$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
    			<tr><td style='width: 78%;text-align: right;padding:3px;'>CGST  </td>
    				<td style='width: 18%;text-align: right;padding:3px;' > ".moneyFormatIndia($cgst)."</td>
    			</tr></table>";	
    	}
    	else {
    	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
    			<tr><td style='width: 78%;text-align: right;padding:3px;'>GST </td>
    				<td style='width: 18%;text-align: right;padding:3px;' > ".moneyFormatIndia($gst_amt)."</td>
    			</tr></table>";	
    	}
    }
	
	$total_amt = $gst_amt + $total_net_amt - $discount ; 
	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center;margin-left:10px; font-size: 10pt;' border='.2'>
			<tr><td style='width: 78%;text-align: right;padding:3px;'> Grand Total </td>
				<td style='width: 18%;text-align: right;padding:3px;' > ".moneyFormatIndia($total_amt)."</td>
			</tr></table>";
	$amt_word=numbertoword($total_amt).' Only';
	$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:10px; font-size: 10pt;' border='.2'>
			<tr><td style='width: 101%;text-align: leftt;padding:3px;' > <b> Amount in word: Rupees  $amt_word </b></td>
			</tr></table>";
	
	if(!empty($notes)){
		$message .= "<table border='.5' cellspacing='0' style='width: 100%; text-align: left; margin-left:10px;font-size: 11pt;'>
			<tr><td style='width: 96%;'>Notes : $notes</td></tr></table> ";
	}

//$cnt<20 &&
	if( ($status=='Draft' || $status=='Completed' || $status=='Submitted' ) ){
		for($l = 0; $l < 1; $l++){
		
			$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
		
		}
	}
	
	
}
	

    
    $message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";

    //$message .= '<page orientation="portrait" format="150x200" style="font-size: 18px">';
	
	$message .= trim($terms);
	
	//$message .= '</page>';
	
	$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
	
	$message .= "<table cellspacing='0' border='.5' style='width: 90%; margin-left:0px;margin-right:30px;font-size: 11pt;text-align:center;border: 1px solid black;border-collapse: collapse;padding:10px;'>
	<tr>
	    <th style='width: 100%;padding:10px;' colspan='3'>Point of Contact</th>
	    
	</tr>
	<tr>
	    <td style='width: 40%;padding:10px;'>Name</td>
	    <td style='width: 40%;padding:10px;'>Email Id</td>
	    <td style='width: 20%;padding:10px;'>Phone No.</td>
	</tr>
	<tr>
	    <td style='width: 40%;padding:10px;'>$point_of_name</td>
	    <td style='width: 40%;padding:10px;'>$point_of_email</td>
	    <td style='width: 20%;padding:10px;'>$point_of_phone</td>
	</tr>
	</table>";

	
	if($status=='Draft' || $status == 'Submitted' || $status=='Completed') {
		for($l = 0; $l < 2; $l++){
		
			$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
			
		}
	}

	
	if($print_flag=='V'){
		$message .= "<Br><Br><table cellspacing='0' style='width: 98%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 15px;'><tr>
		<td style='width: .1%;'></td><td style='width: 62%;text-align: left;border:  0px black;'> For <b>" . $comp_name."</b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>Authorized Signatory  
		</td>
		<td style='width: 37.9%;border:  0px black;'>I agree and accept above in totality&nbsp; <br> For <b>$party_name </b>&nbsp;<br>&nbsp;<br>&nbsp;<br>Name :&nbsp;<br>Designation:</td>
		</tr></table>";
	}
	
	

	if($status == 'Completed' && $cnt<20 ){
		for($l = 0; $l < 5	; $l++){
		
			//$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
		
		}
	}
	
/* 	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'>
	<tr><td style='width: 44.9%;border: 0px black;'><b>General Terms & Conditions </b>: </td></tr></table>"; */
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 10pt;'>
	<tr><td style='width: 96%;border: 0px black;text-align: justify;'> $general_terms</td></tr></table>";
	
	
	if( $print_flag == 'M' && $status == 'Completed' ){
		
		//session_start();
		
		$remarks = "Mail sent to $party_name ". ' Email -' .$party_email;
		//$approval_status = "Electronics"
		$user   	= $_SESSION['user'];
//		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) values( 'PO', '$id', '$userid', now(), '', '', '$remarks', now() )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
	}
	
	if($print_flag=='M'){
		$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
		$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";	
	}
	 
//print $message;
//exit();
	
    // get the HTML
    ob_start();
    
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
	    $content = ob_get_clean();
		$po_number_fl = str_replace('/', '_', $po_number);
		$dirname = $baseurl;
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'poorder_'.$po_number_fl. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->setDefaultFont('freesans');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		    $html2pdf->writeHTML($message);
			//$html2pdf->Output($fl_name);
			
		    if($print_flag=='V'){
				$html2pdf->Output($fl_name);
		    }
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
	    $vw= $_GET['vw'];	
	    if($vw=='Y'){
	        exit();
	    }
	    
		//$status = 'Completed';
		if( $print_flag == 'M' && $status == 'Completed' ){
			
			$sql="select * from sma_user where id in ($userid)";
			$result = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($result);
			$username 		= $r->userid;
			$company_id 	= $r->company_work;
			$user_email		= $r->email;
			$user_name		= $r->username;
			$from_mobile	= $r->mobile_no;
			//$id		 		= $r->id;
			//$role	 		= $r->role;
			//$user_category 	= $r->user_category;
			
			$from_name 			= $user_name;
			$from_email 		= $user_email;
			$from_company_work	= $company_id;
		
			$sql = "SELECT * from company where comp_id = '$from_company_work' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$comp_name = $r2['comp_name'];	
			
			$pdf = $html2pdf->Output($fl_name, true);
			
			require '../PHPMailer-master/PHPMailerAutoload.php';
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			
			$mail->IsSMTP();
			
			include("../dbcon.php");
			$sql="SELECT * from smtp_dtl";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$row = mysqli_fetch_array($result);
			$host 			=	$row['host'];
			$host_name		=	$row['host_name'];
			$host_username 	=	$row['username'];
			$host_password  =	$row['password'];
			$host_port 		=	$row['port'];
			$disclaimer 	=	$row['disclaimer'];

			$mail->Host 		= $host;
			$mail->SMTPAuth 	= true;
			$mail->Username 	= $host_username;
			$mail->Password 	= $host_password;
			$mail->Port       	= $host_port;               // set the SMTP port
			
			$mail->setFrom($host_username, $host_name);

			//$party_email
			//$mail->setFrom('senderSMTP@yahoo.com', 'sender');
			$mail_to = 'ravindra.gandhile@gmail.com';
			/* 
			$mail->addBCC($mail_to, 'First Gmail');
			$mail->addReplyTo($mail_to, 'First Gmail');
			$mail->addAddress($mail_to, 'First Gmail'); 
			*/
			
 			//$mail->addReplyTo($user_email, $user_name);
			//$mail->addAddress($user_email, $user_name);
			$mail->addReplyTo($party_email, $party_name);
			$mail->addAddress($party_email, $party_name);

			$mail->Subject = 'Purchase / Workorder / Service Order from '.$subject. ', PO No. ' . $po_number . ' ' . $comp_name;
			//$mail->addAttachment($pdf, 'file.pdf');
			$mail->addStringAttachment($pdf, $fl_name);
			
			$body .= 'Dear Sir/ Madam,'. " \r\n". $party_name. " \r\n";
			
			$msg = "We are pleased to place an order. Please find attached PO copy for the same.  \r\n  \r\n We would appreciate if the order is delivered at the address given in purchase order.  \r\n  \r\n We hope to have a long business relationship with you. Please feel free to contact the undersigned for any clarifications or discrepancy in the order details. ". " \r\n";
						
			$body .= $msg;
			
			$body .= 'Click on link to Accept : ' . $baseurl1A = $baseurl.$modulePath.'editvn.php?id='.$id. '&status=A'.'&emid='.$party_email.'&vnid='.$to_supplier;

			$body .= " \r\n\r\n";
			
			$body .= "We kindly request that you follow the additional instructions below when submitting your invoices: - \r\n";
 
		$body .= "Invoice Submission: All tax or commercial invoices, proforma invoices, and delivery challans, along with a signed copy of the purchase order (PO) and vendor acceptance email (PO acceptance), should be sent to daksh.s@nxt-infra.com. It is recommended that you provide your acceptance of the PO through the link you would have received via email.\r\n\r\n"; 

		$body .= "PO Number: The PO number should be mentioned on all tax/commercial and proforma invoices. Invoices without a PO number will be rejected. PO number should be mentioned in the email subject line as well while sending invoice to the bill desk ID. \r\n\r\n";

		$body .= "CC to User: If you email invoices to the bill desk ID, the user must be marked in CC, and the invoice must be accompanied by the PO. We recommend that you send invoices directly to the bill desk ID as per the user's insistence. \r\n\r\n";

			$body .= "We appreciate your cooperation in following these additional instructions for submitting your bills. It will help us process your invoices accurately and efficiently, ensuring timely payment processing. \r\n\r\n";

			$body .= "Thank you for your continued support. \r\n\r\n";

			$body .= "This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.";
						
			$sql="select * from sma_user where userid = '$draft_by' ";
			$result = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($result);
			$maker_email		= $r->email;
							
			$body .= "You can review all your PO by login into our supplier portal, details of the same is given below. In case of any assistance, you can email us on $maker_email \r\n\r\n";
			
			
			$body .= "\n\r\n"."Thanks & Regards"."\n". $from_name."\n".
				$comp_name . "\r\n".
				'Email - ' .$from_email . "\n".
				'Mobile- ' .$from_mobile . "\n";
			//$body .= 'This is a computer-generated document. No signature is required.';
			$body .= "\n\r\n ".$disclaimer;
			$mail->Body = $body;
			 
			if($mail->send()){
				echo "<script> alert('Email has been sent to ". $party_email." successfully !');</script>";
				echo "<script>window.close();</script>";
				exit();	
			}
			else
			{
				echo $mail->ErrorInfo;
			}
			
	
		}
		else if( $print_flag=='V' && $status != 'Completed' ){
				
				$html2pdf->Output($fl_name);
		}

	}
}


function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}

?>

	
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>    	
<script>

function viewmail(id){

		
		//alert("PO "+id);
		var strURL = "prn_func.php";
		$.post(strURL,{id:id},function(result){
		      $('#viewmail').html(result);
		});
		
	}
	
</script>

<?php
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   
function numbertoword($num){
	   $number = $num;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
	  if(!empty($points)){
			$points = $points . " Paise";
		}
		else{$points='';}
	  //echo $result . "Rupees  " . $points . " Paise";
	  $words=ucwords($result) . " " . $points;
	  return $words;

}

?>
