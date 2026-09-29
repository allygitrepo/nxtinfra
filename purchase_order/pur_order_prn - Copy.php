<?php if($_GET['sub'] == 'prn'){
	include("../header.php");
	include("../baseurl.php");
	$modulePath = "purchase_order/"; 
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	
	$user   	= $_SESSION['user'];
	$role		= $_SESSION['role']; //Maker

?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="pur_order_prn.php?sub=pdf" method="post"  id="myForm123" enctype="multipart/form-data">
              <div class="box-body">
			  
					<input type="hidden" name ="id" value ="<?php echo $id;?>" >
					<input type="hidden" name ="comp_id" value ="<?php echo $comp_id;?>" >
					<input type="hidden" name ="location" value ="<?php echo $location;?>" >
					
					<div class="form-group">
							<div class="col-sm-3">
							</div>

							<div class="col-sm-3" style="padding-top: 6px;">
								<label class="control-label">Print Mode</label><br>
								<?php if($role!='Audit-PO'){ ?>
									<input type="radio" class="minimal"  name="print_flag" id="print_flag" value="E" onclick="viewmail(this.value);">Mail &nbsp;&nbsp;
								<?php } ?>
                            	<input type="radio" class="minimal"  name="print_flag" id="print_flag" value="M" onclick="viewmail(this.value);">Print  &nbsp;&nbsp;
							</div>
					</div>
					
					<div id="viewmail">
						
						
							
					</div>
					<div class="form-group">					
						<div class="col-sm-6 text-right">
								<?php $baseurl1 = $baseurl.$modulePath;
								$close = "<script>window.close();</script> " ?>
								<input class="btn btn-primary" type="button" value="Cancel" name="Cancel" onClick="self.close()">&nbsp;&nbsp;&nbsp;
								<span>&nbsp;&nbsp;</span>
								
								<input class="btn btn-primary" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
							</div>	
					</div>
					
				</div>			
			</form>
</div>
</div>
</div>
</div>
</section>
</div>

<?php
}
	
if($_GET['sub'] == 'pdf'){
//	include "../dbcon.php";
//	include "../baseurl.php";

	$con = mysqli_connect("localhost","root","","athaangp2p");


$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
//echo $message1;			
require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'poorder_test'. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		   //$html2pdf->writeHTML($message1);
				
		    
			//	$html2pdf->Output($fl_name);
		
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}

exit();


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
	else {
		
		$id			= $_POST['id'];
		$comp_id	= $_POST['comp_id'];	
		$location   = $_POST['location'];

	}
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
		$loc_gst_no  = $row['loc_gst_no'];
		$loc_contact_person = $row['loc_contact_person'];
		$loc_contact_person_mobile = $row['loc_contact_person_mobile'];
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
	$general_terms		= $com['general_terms'];
	$header_terms		= $com['header_terms'];
	
	$billing_address1	= $com['comp_register_address1'];
	$billing_address2	= $com['comp_register_address2'];
	$billing_address3	= $com['comp_register_address3'];
	$billing_pincode	= $com['comp_register_pincode'];
		
	
	//$loc_pan_no         = $comp_pan_no;
	//$loc_gst_no         = $com['comp_gst_no'];
	
	$logo_file_name		= $com['logo_file_name'];
	$logo_dir_name		= $baseurl.'setting/'.'upload/';
	
	$logo_fl			= $logo_dir_name.$logo_file_name;
	
	if(empty($logo_file_name)){
		
		$logo_fl			= $logo_dir_name . 'SK1Logo.png';
		
	}	

	$message ='';

	$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'poorder_'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		   $html2pdf->writeHTML($message);
				
		    if($print_flag=='V'){
				$html2pdf->Output($fl_name);
		    }
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
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
		
		for($l = 0; $l < 17; $l++){
				$space2.='&nbsp;';
		} 
		$pono = $po_desc ." No.:". $po_number .$space2.$space2." PO.Dated : ".$podated  ;
		
	$message .= "<table cellspacing='0' style='width: 93%; border: solid 0px black; margin-left:42px;'>";
	$message .= "<tr><td style='width: 65%;font-size: 20px;text-align: left;'>" . $comp_name."</td><td rowspan='5' style='text-align: right;'></td></tr>";
	
	//$message .= "<tr><td style='width: 65%;font-size: 20px;text-align: left;'>" . $comp_name."</td><td rowspan='5' style='text-align: right;'><img src='".$logo_fl."'height='10%' width='10%'></td></tr>";
	
//	$message .= "<table cellspacing='0' style='width: 90%; border: solid 0px black; '><tr><td rowspan='5' style='text-align: right;'></td><td style='width: 70%;font-size: 20px;text-align: right;'>" . $comp_name."</td></tr>";
//$message .= "<tr><td style='width: 70%;font-size: 12px;text-align: right;'>".$loc_name."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>"."". $comp_addr1.' '.$comp_city.','.$comp_pincode."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>Phone: ".$comp_office.", E-mail : ".$comp_email."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>CIN No.: ".$comp_cin_no.", GST No.: ".$loc_gst_no."</td></tr>";
	$message .= "<tr><td style='width: 65%;font-size: 12px;text-align: left;'>PAN No.: ".$loc_pan_no."</td></tr>";
	$message .= "</table>";
	

		//echo $terms;
//exit();		
		$po_doc_type 			= $row['po_doc_type'];
		
	}

	/* if($status =='Draft' || $status == 'Submited'){
		$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;color:#c9d6d6;font-size:72;'>&nbsp;Draft </td></tr></table>";		
		
	} */
	
$head = $message;

$message ='';
	
$message .= '<page backtop=36mm" backbottom="16mm" backleft="10mm" backright="2mm" pagegroup="new">
    <page_header>
        <table class="page_header" style="width: 103%; text-align: center;font-size: 18pt">
            <tr>
                <td style="width: 103%; text-align: center123;text-align: center;">
                    '.$head .'
                </td>
            </tr>
			<tr>
                <td style="width: 100%;font-size: 12pt;text-align: left;margin-left:20px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$pono .'
                </td>
            </tr>
			
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
		$party_address_1 = $row['party_address_1'];
		$party_address_2 = $row['party_address_2'];
		$party_address_3 = $row['party_address_3'];
		$party_city  	 = $row['party_city'];
		$party_pincode   = $row['party_pincode'];
		$party_mobile    = $row['party_mobile'];
		$party_email     = $row['party_email'];
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
	if(!empty($party_contact_person_name)){
		$con_txt = 'Contact Person : '. $party_contact_person_name;
	}
	
	if(!empty($party_mobile)){
		$mob_txt = 'Mobile ' . ": ". $party_mobile;
	}
	
	$message .= "<table cellspacing='0' style='width: 95%; border:  0px black; text-align: left;margin-left:10px; font-size: 14px;'>
			<tr>
			<td style='width: 50%;text-align: left;border: 0.5px black;'><div  >To, </div>
			 "."<b>". $party_name ."</b>"
			 ."<br>". $party_address_1
			 ."<br>". $party_address_2
			 ."". $party_address_3
			 ."". $party_city. ", Pincode: ". $party_pincode
			 ."<br>Mobile:". $party_mobile
			 ."<br>Email :". $party_email
			 ."</td> 
			<td style='width: 50%;text-align: top; border: 0.5px black;'><div  >Quotation Details </div>"
			."Ref.No: ".$quotation_reference_no
			."<br>Date " . $space1 ." : ".$dated
			."<br>" . $con_txt
			."<br>". $mob_txt
			."&nbsp;&nbsp;<br>&nbsp;</td>
			</tr></table>";
	
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
		$con_txt = 'Contact Person : '. $loc_contact_person;
	}
	
	if(!empty($loc_contact_person_mobile)){
		$mob_txt = 'Contact Mobile ' . ": ". $loc_contact_person_mobile;
	}
	$message .= "<table cellspacing='0' style='width: 95%; border:  0px black; text-align: left;margin-left:10px; font-size: 14px;'>
			<tr>
			<td style='width: 50%;text-align: left;border:  .5px black;'> <div  >Billing Address </div> ". $bill_addr1 
			 ."<br>". $bill_addr2
			 ."<br>". $bill_addr3." ". $bill_pincode
			 ."<br>". $con_txt
			 ."<br>". $mob_txt
			 ."</td>
			<td style='width: 50%;text-align: left; border:  .5px black;'><div  >Delivery Address </div> ". $loc_addr1 
			."<br> ".$loc_addr2
			."<br> ".$loc_addr3
			."<br>".$loc_city. ' ' . " ". $loc_pincode 
			."<br>"
			."<br>"
			."</td>
			</tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";

	//$message .=  "Subject : " .$subject;
	
	$message .= "<table border='.5' cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 11pt;'>
			<tr><td style='width: 101%;'>Subject : $subject</td></tr></table> ";

	
	/* $message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";
 */
	$message .= "<table border='0.2' cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; text-align: center;margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 06%;text-align: Center;font-size:12px;'><b> SrNo.</b></td>
				<td style='width: 35%;'><b> Particulars </b></td>
				<td style='width: 10%;text-align: right;'><b> Delivery Date </b></td>
				<td style='width: 13%;text-align: right;'><b> Qty. </b></td>
				<td style='width: 7%;'><b> Unit </b></td>
				<td style='width: 08%;text-align: right;'><b> Unit Rate </b></td>
				<td style='width: 07%; text-align: center;'><b> GST% </b></td>
				<td style='width: 15%;text-align: right;'><b> Amount (INR)</b></td>
			</tr></table>";
	
	$ln =0;
	
	//$id		= $_GET['id'];
	
	$sql 	= "SELECT count(*) as cnt FROM sma_po_items where purchase_id = '$id'";
	$result = mysqli_query($con,$sql);
	//$items_cnt = mysqli_affected_rows($con);
	$row = mysqli_fetch_array($result);
	$cnt	= $row['cnt'];
		
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
		$delivery_date  = date('d-m-Y', strtotime($row['delivery_date']));
		
		$tot_qty		= $quantity;
		$actual_amt     = $quantity * $unit_rate;
		$total_amt		= $total_amt + $actual_amt;
		
		//$net_amt  		= round($actual_amt - ($actual_amt * $pod_discount / 100),0);
		
		$net_amt  		= round($actual_amt ,0);
		
		
		$total_net_amt	= $total_net_amt + $net_amt;
		
		$delivery_date  = date('d-m-Y', strtotime($row['delivery_date']));
		if($delivery_date=='01-01-1970'){
			$delivery_date ='';
		}	
		$product_id=$row['product_id'];
		$sql="Select * from sma_product where id = '$product_id'";
		$output = mysqli_query($con,$sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($output);

		$product_name = $r2['name'];
		
		$unit		  = $r2['uom'];
		$hsn_code	  = $r2['hsn_code'];
		
		$gst_amt  	  = $gst_amt + round(($net_amt - $discount )* $gst / 100,0);
		
		if($supplier_location != 'O'){
		    $sgst = $gst_amt / 2;
		    $cgst = $gst_amt / 2;
		}
		
	    ++$i;
		
		$ln = $ln + 1;
		
		if($cnt > 15 && $ln >16){
		
			$cnt = 0;
			//$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
			$message.="<table border='0' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;' >";
			$message .= "<tr><td style='width: 6%;text-align: Center;'> &nbsp; </td></tr> ";
			$message .= "</table>";
			$ln = 0;
		}
		
		if($delivery_date=='31-12-1969' || $delivery_date=='01-01-1970'){
			$delivery_date ='';
		}
		$message.="<table border='0.2' cellspacing='-1' style='width: 95%; text-align: center; margin-left:10px;font-size: 12px;' >";
		//$message .= "<tr><td style='width: 6%;text-align: Center;'> ".$i." </td>
		//		<th style='width: 45%;text-align: left;' >". $product_name . " </th><td></td><td></td><td></td><td></td><td></td></tr>";
		$message .= "<tr><td style='width: 6%;text-align: Center;valign=top;'> ".$i." </td>
				<td style='width: 35%;text-align: left;font-size: 11px;'> <b><span font-size: 13px;>" .$product_name."</span></b><br>". $product_desc . " </td>
				<td style='width: 10%;text-align: right;valign=top;'> ".$delivery_date." </td>
				<td style='width: 13%;text-align: right;valign=top;'> &nbsp;&nbsp;".$quantity." </td>
				<td style='width: 7%;text-align: center;valign=top;'> " . $unit . " </td>
				<td style='width: 8%;text-align: right;valign=top;'> ".$unit_rate." </td>
				<td style='width: 7%;text-align: center;valign=top;'> ".$gst." </td>
				<td style='width: 15%;text-align: right;valign=top;'> ".moneyFormatIndia($net_amt)." </td>
			</tr>";
		$message .= "</table>";
	}
	
	
	$ln  = 30 - $cnt ;
	$l   =  $i;

	
	for($l = $l; $l < $ln; $l++){
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:10px; font-size: 10pt;' >
					<tr>
					<td style='width: 6%;text-align: Center;border-left: solid .2px #000;'> &nbsp;</td>
				<td style='width: 35%;text-align: left;'>&nbsp; </td>
				<td style='width: 10%;text-align: center;'> &nbsp; </td>
				<td style='width: 13%;text-align: center;'> &nbsp; </td>
				<td style='width: 7%;text-align: center;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 7%;text-align: right;'> &nbsp; </td>
				<td style='width: 15%;text-align: center;border-right: solid .2px #000;'> &nbsp; </td>
				
			</tr></table>";
	}


	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
			<tr><td style='width: 78%;text-align: right;'> Net Total </td>
				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($total_net_amt)."</td>
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
    			<tr><td style='width: 78%;text-align: right;'>SGST  </td>
    				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($sgst)."</td>
    			</tr></table>";	
    			$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
    			<tr><td style='width: 78%;text-align: right;'>CGST  </td>
    				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($cgst)."</td>
    			</tr></table>";	
    	}
    	else {
    	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center; margin-left:10px;font-size: 10pt;' border='.2'>
    			<tr><td style='width: 78%;text-align: right;'>IGST  </td>
    				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($gst_amt)."</td>
    			</tr></table>";	
    	}
    }
	
	$total_amt = $gst_amt + $total_net_amt - $discount ; 
	$message .= "<table border='0' cellspacing='-1' style='width: 100%; text-align: center;margin-left:10px; font-size: 10pt;' border='.2'>
			<tr><td style='width: 78%;text-align: right;'> Grand Total </td>
				<td style='width: 18%;text-align: right;' > ".moneyFormatIndia($total_amt)."</td>
			</tr></table>";
	$amt_word=numbertoword($total_amt).' Only';
	$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:10px; font-size: 10pt;' border='.2'>
			<tr><td style='width: 101%;text-align: leftt;' > <b>Amount</b> $amt_word</td>
			</tr></table>";
	
	if(!empty($notes)){
		$message .= "<table border='.5' cellspacing='0' style='width: 95%; text-align: left; margin-left:10px;font-size: 11pt;'>
			<tr><td style='width: 101%;'>Notes : $notes</td></tr></table> ";
	}

	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
	
	}
	
	//$message .="<page backleft='0mm' backtop='0mm' backright='0mm' backbottom='0mm'> &nbsp; </page>";
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'>
	<tr><td style='width: 44.9%;border: 0px black;'><b>Special Terms & Conditions </b>: </td></tr></table>";
	
	//$message .=  $terms ; str_replace(' ', '-', $string);
	
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 11pt;'>
	<tr><td style='width: 95%;border: 0px black;'>$terms</td></tr></table>";
	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
	
	}
	
	
	
	if($print_flag=='V'){
		$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'><tr>
		<td style='width: .1%;'></td><td style='width: 55%;text-align: left;border:  0px black;'> For <b>" . $comp_name."</b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>Authorized Signatory  
		</td>
		<td style='width: 44.9%;border:  0px black;'>I agree and accept above in totality&nbsp; <br> For <b>$party_name </b>&nbsp;<br>&nbsp;<br>&nbsp;<br>Name :&nbsp;<br>Designation:</td>
		</tr></table>";
	}
	
	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0'  ><tr><td> &nbsp;</td></tr></table>";
	
	}
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'>
	<tr><td style='width: 44.9%;border: 0px black;'><b>General Terms & Conditions </b>: </td></tr></table>";
	//$message .=  $general_terms;
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 10pt;'>
	<tr><td style='width: 95%;border: 0px black;text-align: justify;'>$general_terms</td></tr></table>";
	
//	$message .= '<page backtop=28mm" backbottom="14mm" backleft="10mm" backright="2mm" pagegroup="new"></page>	';
	
	/* $message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: left; margin-left:10px;font-size: 12pt;'>
	<tr><td style='width: 44.9%;border: 0px black;'><b>General Terms & Conditions </b>: </td></tr></table>";
	
	$message .=  $general_terms; */
	
	
	//if(empty($print_flag)){$print_flag='M';};
	
	if( $print_flag == 'M' && $status == 'Completed' ){
		
		session_start();
		
		$remarks = "Mail sent to $party_name ". ' Email -' .$party_email;
		//$approval_status = "Electronics"
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values( 'PO', '$id', '$userid', now(), '', '', '$remarks', now() )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
	}
	
	
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
	/* $message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";		
	 */
print $message;
exit();
	
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
		$dirname = $baseurl;
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'poorder_'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		   $html2pdf->writeHTML($message);
				
		    if($print_flag=='V'){
				$html2pdf->Output($fl_name);
		    }
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
		exit();
		
	}
		
		
		if( $print_flag != 'V' && $status == 'Completed' ){
			
			$sql="select * from sma_user where id in ($userid)";

			$result = mysqli_query($con, $sql);
			$r = mysqli_fetch_object($result);
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
			
			$pdf = $html2pdf->Output($fl_name, true);
			
			$mail_to = 'ravindra.gandhile@gmail.com';

			require '../PHPMailer-master/PHPMailerAutoload.php';
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			
			$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
			$mail->SMTPAutoTLS = false;
			
			$mail->IsSMTP();
			
			/*$mail->SMTPAuth = true;
			
			 $mail->Host = "outlook.office365.com";
			//$mail->SMTPSecure = "ssl";
			$mail->Username = 'workflow@sekura.in';
			$mail->Password = 'sekura@1234';
			$mail->Port       = "587";                    // set the SMTP port
			
			$mail->setFrom('workflow@sekura.in', 'Sekura -Workflow');
 */
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

			$mail->Host 		= $host;
			$mail->SMTPAuth 	= true;
			$mail->Username 	= $host_username;
			$mail->Password 	= $host_password;
			$mail->Port       	= $host_port;               // set the SMTP port
			
			$mail->setFrom($host_username, $host_name);

			//$party_email
			//$mail->setFrom('senderSMTP@yahoo.com', 'sender');
			//$mail->addAddress($mail_to, 'test');
			$mail->addBCC($mail_to, 'First Gmail');
			
			$mail->addReplyTo($user_email, $user_name);
			$mail->addAddress($user_email, $user_name);
			$mail->addAddress($party_email, $party_name);
			
			$mail->Subject = 'Sekura Workflow - Purchase Order  '. $comp_name;
			//$mail->addAttachment($pdf, 'file.pdf');
			$mail->addStringAttachment($pdf, $fl_name);
			
			$body .= 'Dear Sir/ Madam,'. " \r\n". $party_name. " \r\n";
			
			$msg = "We are pleased to place an order. Please find attached PO copy for the same.  \r\n  \r\n We would appreciate if the order is delivered at the address given in purchase order.  \r\n  \r\n We hope to have a long business relationship with you. Please feel free to contact the undersigned for any clarifications or discrepancy in the order details. ";
			
			$body .= $msg;
			$body .= "Thank & Regards"."\n\r\n \r\n"."Sekura Group of Companies";
			//$body .= 'This is a computer-generated document. No signature is required.';
			
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
