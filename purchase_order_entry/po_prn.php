<?php
	$modulePath = "purchase_order_entry/"; 
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	
	$user   	= $_SESSION['user'];
	$role		= $_SESSION['role']; //Maker
	$con = mysqli_connect("localhost","root","","athaangp2p");

$_GET['id'] =1;
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
	$comp_id	= '6';
	$location   = '1';
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
	
	if(empty($logo_file_name)){
		
		$logo_fl			= $logo_dir_name . 'SK1Logo.png';
		
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
	
	}
		
?>
<?php
require('../fpdf/fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
	
	
// Logo

	$this->Image('../fpdf/tutorial/logo.png',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(60);
	// Title
//	$this->Cell(30,10,"Title",1,0,'C');
	$this->Cell(99,10,$comp_name,0,0,'C');
	
	// Line break
	$this->Ln(20);
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

// Instanciation of inherited class
$pdf = new PDF();

$con = mysqli_connect("localhost","root","","athaangp2p");	
// Logo
$sql="SELECT * FROM `company` where comp_id = '$comp_id' ";
$comresult 	= mysqli_query($con,$sql);
$error  			= mysqli_error($con);
$com 				= mysqli_fetch_array($comresult);	
$comp_name 			= $com['comp_name'];
	
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(99,10,$comp_name,0,0,'C');
	
	// Line break
$pdf->Ln(20);
$pdf->Cell(0,10,'Printing line number '.$comp_name ,0,1);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Printing line number '.$comp_name.' '.$i,0,1);
$pdf->Output();
?>
