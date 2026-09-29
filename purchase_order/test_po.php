<?php
//require('../fpdf.php');
require('../fpdf/fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
//	$this->Image('logo.png',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
//	$this->Cell(80);
	// Title
//	$this->Cell(30,10,'Title',1,0,'C');

	// Line break
//	$this->Ln(20);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}



	$con = mysqli_connect("localhost","root","","athaangp2p");
include("../baseurl.php");
// Instanciation of inherited class
//$pdf = new PDF();
	
//This is for HTML Code
	require('../fpdf/html2pdf.php');
    $pdf=new PDF_HTML();
	
	//$pdf->AddPage();
	
$pdf=new PDF('P','mm','A4');

$pdf->AliasNbPages();

$pdf->AddPage();

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
	
	$id = 3;
	$comp_id	= '4';
	$location   = '27';
	$print_flag = 'Y';
		
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
	
	//if(empty($logo_file_name)){
		
		$logo_fl			= $logo_dir_name . 'AthanglogoColor.jpg';
		
	//}	
	
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
		$pono = $po_desc ." No.:". $po_number . " PO.Dated : ".$podated  ;
	
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
		
	}
		

/*set font to arial, bold, 14pt*/
/*Cell(width , height , text , border , end line , [align] )*/

//$pdf->PageNo();
$pdf->SetTopMargin(100);
$pdf->SetAutoPageBreak(0.6);

	
$pdf->SetFont('Arial','B',16);
$pdf->Cell(01 ,10,'',0,0);
$pdf->Cell(69 ,5,$comp_name,0,0);
$pdf->Cell(59 ,6,'',0,1);
//$pdf-> Image('profileimage/'.$logo_fl,100,15,35,35);
$pdf->Image($logo_fl, 165, $pdf->GetY(), 35.78);

$pdf->SetFont('Arial','',11);
$pdf->Cell(01 ,10,'',0,0);
$pdf->Cell(69 ,4,$comp_addr1.''.$comp_city.''.$comp_pincode,0,0);
$pdf->Cell(59 ,6,'',0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(01 ,10,'',0,0);
$pdf->Cell(69 ,4,"Phone: ".$comp_office." E-mail : ".$comp_email ,0,0);
$pdf->Cell(59 ,6,'',0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(01 ,10,'',0,0);
$pdf->Cell(69 ,4,"GST No.: ".$loc_gst_no." PAN No.: ".$loc_pan_no ,0,0);
$pdf->Cell(59 ,10,'',0,1);

$pono = $po_desc ." No.:". $po_number . " PO.Dated : ".$podated  ;
$pdf->SetFont('Arial','',12);
$pdf->Cell(01 ,10,'',0,0);
$pdf->Cell(129 ,4,$po_desc ." No.:". $po_number ,0,0);
$pdf->Cell(59 ,4," PO.Dated : ".$podated  ,0,0);
$pdf->Cell(59 ,10,'',0,1);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(71 ,5,'To',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,"Quotation Details ",0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$party_name,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,"Ref.No: ".$quotation_reference_no,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$party_address_1,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,"Date " . " : ".$dated,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$party_address_2."". $party_address_3,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$con_txt,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$party_city. ", Pincode: ". $party_pincode,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$mob_txt,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$party_mobile.' '. $party_mobile,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'',0,1);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(71 ,5,'Billing Address',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,"Delivery Address ",0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$bill_addr1,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$loc_addr1,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$bill_addr2,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$loc_addr2,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$bill_addr3,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$loc_addr3,0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(71 ,5,$con_txt. ' ' . $mob_txt,0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,$loc_city. ' ' . $loc_pincode,0,1);

$pdf->line(31, 770, 565, 770);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(20 ,5,'Subject : ',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(160,5,$subject,0,0);
$pdf->Cell(59 ,5,'',0,1);


/*Heading Of the table*/
$pdf->SetFont('Arial','',10);
$pdf->Cell(10 ,6,'SrNo.',1,0,'C');
$pdf->Cell(55 ,6,'Particulars',1,0,'C');
$pdf->Cell(23 ,6,'Delivery Date',1,0,'C');
$pdf->Cell(20 ,6,'Qty',1,0,'C');
$pdf->Cell(20 ,6,'Unit',1,0,'C');
$pdf->Cell(20 ,6,'Unit Rate',1,0,'C');
$pdf->Cell(15 ,6,'GST% ',1,0,'C');
$pdf->Cell(25 ,6,'Amount (INR)',1,1,'C');/*end of line*/
/*Heading Of the table end*/

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
		
		if($delivery_date=='31-12-1969' || $delivery_date=='01-01-1970' || $delivery_date=='30-11--0001' ){
			$delivery_date ='';
		}
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10 ,6,$i,1,0,'C');
		$pdf->Cell(55 ,6,$product_name . ' ' . $product_desc,1,0,'');
		$pdf->Cell(23 ,6,$delivery_date,1,0,'');
		$pdf->Cell(20 ,6,$quantity,1,0,'R');
		$pdf->Cell(20 ,6,$unit,1,0,'C');
		$pdf->Cell(20 ,6,number_format($unit_rate,2),1,0,'R');
		$pdf->Cell(15 ,6,$gst,1,0,'R');
		$pdf->Cell(25 ,6,number_format($net_amt,0),1,1,'R');/*end of line*/

	}
	
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10 ,6,'',1,0,'C');
		$pdf->Cell(55 ,6,'Net Total',1,0,'R');
		$pdf->Cell(23 ,6,'',1,0,'');
		$pdf->Cell(20 ,6,'',1,0,'R');
		$pdf->Cell(20 ,6,'',1,0,'C');
		$pdf->Cell(20 ,6,'',1,0,'R');
		$pdf->Cell(15 ,6,'',1,0,'R');
		$pdf->Cell(25 ,6,number_format($total_net_amt,0),1,1,'R');		
	
	if($gst_amt>0){
        if($supplier_location != 'O'){
    		$sgst = $gst_amt / 2;
    		$cgst = $gst_amt / 2;
					
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(10 ,6,'',1,0,'C');
			$pdf->Cell(55 ,6,'SGST',1,0,'R');
			$pdf->Cell(23 ,6,'',1,0,'');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(20 ,6,'',1,0,'C');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(15 ,6,'',1,0,'R');
			$pdf->Cell(25 ,6,number_format($sgst,0),1,1,'R');	
			
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(10 ,6,'',1,0,'C');
			$pdf->Cell(55 ,6,'CGST',1,0,'R');
			$pdf->Cell(23 ,6,'',1,0,'');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(20 ,6,'',1,0,'C');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(15 ,6,'',1,0,'R');
			$pdf->Cell(25 ,6,number_format($cgst,0),1,1,'R');
			
    	}
    	else {
    	
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(10 ,6,'',1,0,'C');
			$pdf->Cell(55 ,6,'IGST',1,0,'R');
			$pdf->Cell(23 ,6,'',1,0,'');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(20 ,6,'',1,0,'C');
			$pdf->Cell(20 ,6,'',1,0,'R');
			$pdf->Cell(15 ,6,'',1,0,'R');
			$pdf->Cell(25 ,6,number_format($gst_amt,0),1,1,'R');
				
    	}
    }
	
	$total_amt = $gst_amt + $total_net_amt - $discount ; 
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(10 ,6,'',1,0,'C');
	$pdf->Cell(55 ,6,'Grand Total',1,0,'R');
	$pdf->Cell(23 ,6,'',1,0,'');
	$pdf->Cell(20 ,6,'',1,0,'R');
	$pdf->Cell(20 ,6,'',1,0,'C');
	$pdf->Cell(20 ,6,'',1,0,'R');
	$pdf->Cell(15 ,6,'',1,0,'R');
	$pdf->Cell(25 ,6,number_format($total_amt,0),1,1,'R');
	
	$amt_word=numbertoword(rtrim($total_amt)).'Only';
	
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(188 ,6,'Amount : '.$amt_word,1,1,'');

	if(!empty($notes)){
		
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(188 ,6,'Notes : '.WordWrap($notes),0,1,'');
	
	}


//

//echo $terms;
//exit();
 	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(188 ,6,'',0,1,'');
	
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(188 ,6,'Special Terms & Conditions ',0,1,'');
	//$message .=  $terms ; str_replace(' ', '-', $string);
//	$pdf->Cell(108 ,6,WordWrap($terms,120),0,1,'');	
//	$nb=$pdf->WordWrap($terms,180);
//	$pdf->Write(5,"This paragraph has $nb lines:\n\n");
//	$pdf->Write(5,$terms); 

//	$pdf->SetFont('Arial','',12);	
//    $pdf->WriteHTML($terms);

/* 
$pdf->SetFont('Arial','',15);
$pdf->SetXY(80,35);
$pdf->drawTextBox('This sentence is centered in the middle of the box.', 50, 50, 'C', 'M');	
 */
//	$pdf->Write(190,5,$terms,1,1,'FJ',1); 
	
	//$pdf->Line(11, 45, 210-10, 45); 
	$pdf->Ln(1); 
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(188 ,16,'',0,1,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(120 ,6,'I agree and accept above in totality ',0,0,'');
	$pdf->Cell(30 ,6,'for  : '.$comp_name,0,0,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(188 ,6,'',0,1,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(120 ,6,'For '.$party_name,0,0,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(188 ,16,'',0,1,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(110 ,6,'Name : ',0,0,'');
	$pdf->Cell(80 ,6,'Authorized Signatory ',0,0,'R');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(188 ,6,'',0,1,'');
	
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(30 ,6,'Designation:',0,0,'');	
	
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(188 ,6,'',0,1,'');
	
/* $pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Printing line number '.$i,0,1);
 */
$pdf->Output();


?>



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
	  $words=ucwords($result);
	  return $words;

}

?>

