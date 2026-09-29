<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../common/conn.php');
include('../../common/function.php');
ob_start();
$page='estimate';
$id=$_GET['id'];
$sf=fetch(query('SELECT * FROM  estimate_step1 WHERE estimateId="'.$id.'"'));
$it_id=$sf['itinerary_id'];
$doj=$sf['doj'];
$travler=$sf['adults']+$sf['childern'];
$doj_array=explode('/',$doj);
$step_1_id=$_GET['id'];     
$p=fetch(query('SELECT * FROM `estimate_payment_breakdown` WHERE step_1_id="'.$step_1_id.'"'));
$step1=fetch(query('SELECT * FROM `estimate_step1` WHERE estimateId="'.$step_1_id.'"'));
$it_id=$step1['itinerary_id'];
$step1=fetch(query('SELECT * FROM `estimate_step1` WHERE estimateId="'.$step_1_id.'"'));
$dt=fetch(query('SELECT * FROM detail  WHERE id=1'));
$doj=date('m/d/Y', strtotime($doj . ' -1 day'));
$ad=$step1['adults'];
$ch=$step1['childern'];
$inf=$step1['infant'];
$duration=$step1['duration'];
   

$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total']+$p['m_hotel'];}else{$hp= 0;} ;
if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total']+$p['m_logistic'];$gt=$gt+$p['grand_logi_total']+$p['m_logistic'];}else{ $lp = 0;};
if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total']+$p['m_ticket'];$gt=$gt+$p['grand_tkt_total']+$p['m_ticket'];}else{$tp= 0;}
if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total']+$p['m_ferry'];$gt=$gt+$p['grand_ferry_total']+$p['m_ferry'];}else{$fp=  0;};
if($p['grand_act_total']!=0){ $ap= $p['grand_act_total']+$p['m_activity'];$gt=$gt+$p['grand_act_total']+$p['m_activity'];}else{$ap= 0;};
$cost=$step1['total'];
$discount=$step1['discount'];
$payble=$cost-$discount;
$gst=($payble*5)/100;
$gsthalf=$gst/2;



require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->SetPrintHeader(false);
//define ('PDF_HEADER_LOGO', 'http://andamantrail.com/img/andaman-trail-logo.jpg');
$pdf->SetPrintFooter(false);
// set document information
$pdf->SetCreator('Andaman Trail');
$pdf->SetAuthor('Andaman Trail');
$pdf->SetTitle('Estimate');
$pdf->SetSubject('EST - 9151');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// ITINERARY OVERVIEW

$pdf->AddPage('P','A4');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'andamantrail.co', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font

$pdf->SetFont('roboto', '', 8);


$overview = '
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="left">Exotrail Destination Management Private Limited<br />
        CIN: '.$dt['t0'].'<br />
        PAN: '.$dt['t5'].'<br />
        GSTIN: '.$dt['t6'].'<br />
        PLACE OF SUPPLY: A&N ISLANDS - 35 </td>
      <td align="right"> Total Invoice Amount: &#x20b9; '.$payble.'<br />
        Invoice Ref #: INV-'.$id.'<br />
        Estimate Ref#: EST-'.$id.'<br />
        Date: '.date('d M Y').' </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/vocb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td>Dear Customer,<br />
        <br />
        We are hereby confirming the below servies for tour confirmation. Please find the details below.<br /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="left"> Confirmation No. </td>
      <td align="left"> VOC-'.$id.' </td>
    </tr>
    <tr>
      <td align="left"> Number of Adult(s) </td>
      <td align="left"> '.$ad.' </td>
    </tr>
    <tr>
      <td align="left"> Number of Child(ren) </td>
      <td align="left"> '.$ch.' </td>
    </tr>
    <tr>
      <td align="left"> Number of Infant(s) </td>
      <td align="left"> '.$inf.' </td>
    </tr>
    <tr>
      <td align="center"> TRAVELLER NAMES </td>
      <td align="center"> AGE </td>
    </tr>';
	$px=query('SELECT * FROM `px_detail`  WHERE step_1_id="'.$id.'"');$i=1;
	while($pxr=fetch($px)){
	$overview =$overview .'
    <tr>
      <td align="left"> '. $i.'. '.$pxr['px_name'].' </td>
      <td align="left"> '.$pxr['age'].' </td>
    </tr>
   '; $i++;}
	$overview =$overview .' 
    <tr>
      <td align="left"> Arrival Date </td>
      <td align="left"> '.$ondate=date('d M Y', strtotime($doj)).' </td>
    </tr>
    <tr>
      <td align="left"> Departure Date </td>
      <td align="left"> '.$ondate=date('d M Y', strtotime($doj . ' +'.$duration.' day')).' </td>
    </tr>
    <tr>
      <td align="left"> Arrival Timing </td>
      <td align="left"> '.$step1['flight_arrival'].' </td>
    </tr>
    <tr>
      <td align="left"> Departure Timing </td>
      <td align="left"> '.$step1['flight_departure'].' </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>

';


$pdf->writeHTML($overview, true, false, true, false, '');
$pdf->AddPage('P','A4');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('roboto', '', 8);


$confirmation = '
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="center">TOUR CO-ORDINATOR</td>
      <td align="center">DESTINATION</td>
      <td align="center">CONTACT NUMBER</td>
    </tr>';$px=query('SELECT * FROM `estimate_staff`   WHERE step_1_id="'.$id.'"');$i=1;
	while($pxr=fetch($px)){
		 $place=fetch(query('SELECT * FROM place WHERE placeId="'.$pxr['place_id'].'"'));
	$confirmation =$confirmation. '
    <tr>
      <td>'.$pxr['name'].'</td>
      <td>'.$place['place_name'].'</td>
      <td>'.$pxr['contact'].'</td>
    </tr>';
	; $i++;}
	$confirmation =$confirmation. '
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/ferryvocb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="center">CRUISE</td>
      <td align="center">SEAT CATEGORY</td>
      <td align="center">ROUTE</td>
      <td align="center">DOJ</td>
      <td align="center">TICKET #</td>
      <td align="center">PNR #</td>
      <td align="center">STATUS</td>
      <td align="center">TICKETS</td>
    </tr>';
	$ferry=(query('SELECT * FROM `estimate_step_ferry` 
				 INNER JOIN ferry_route ON ferry_route.routeId=estimate_step_ferry.ferry_id 
				 INNER JOIN ferry ON ferry.ferryId=ferry_route.ferry_id 
				 INNER JOIN seat ON seat.seatId=estimate_step_ferry.seat_id 
				 WHERE step_1_id="'.$id.'" '));$i=1;
				 while($fr=fetch($ferry)){ 
				 $ondate=date('d M Y', strtotime($doj . ' +'.$i.' day'));	
	$r=fetch(query('SELECT * FROM estimate_ferry_tickets WHERE estimate_step_ferry_id="'.$fr['estimate_step_ferryId'].'"'));
	 $place=fetch(query('SELECT * FROM place WHERE placeId="'.$fr['origin'].'"'));
	 $place2=fetch(query('SELECT * FROM place WHERE placeId="'.$fr['destination'].'"'));
	$confirmation =$confirmation. '
    <tr>
      <td align="center">'.$fr['name'].'</td>
      <td align="center">'.$fr['type'].'</td>
      <td align="center">'.$place['place_name'].' to '.$place2['place_name'].'</td>
      <td align="center">'.$ondate.'</td>
      <td align="center">'.$r['ticket'].'</td>
      <td align="center">'.$r['pnr'].'</td>
       <td align="center">'.$r['status'].'</td>
      <td align="center">'.$fr['tickets'].'</td>
    </tr>
    ';
	 $i++;};
	$confirmation =$confirmation. '
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/vehvocb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="center">VEHICLE</td>
      <td align="center">CATEGORY</td>
      <td align="center">REG.#</td>
      <td align="center">DESTINATION</td>
      <td align="center">DRIVER NAME</td>
      <td align="center">CONTACT #</td>
    </tr>
	    ';
	 $d=(query('SELECT * FROM estimate_drivers WHERE step_1_id="'.$id.'" '));
	 while($r=fetch($d)){$place=fetch(query('SELECT * FROM place WHERE placeId="'.$r['place_id'].'"'));
	$confirmation =$confirmation. '
    <tr>
      <td align="center">'.$r['type'].'</td>
      <td align="center">'.$r['cat'].'</td>
      <td align="center">'.$r['vehicle'].'</td>
      <td align="center">'.$place['place_name'].'</td>
      <td align="center">'.$r['name'].'</td>
      <td align="center">'.$r['contact'].'</td>
    </tr>
  ';
	 }
	$confirmation =$confirmation. '
  </tbody>
</table>
';
$pdf->writeHTML($confirmation, true, false, true, false, '');

$pdf->AddPage('P','A4');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('roboto', '', 6);

$hotels = '
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/hotelvocb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="center">DAY #</td>
      <td align="center">DESTINATION</td>
      <td align="center">DATE</td>
      <td align="center">HOTEL</td>
      <td align="center">ROOM</td>
      <td align="center">MEAL PLAN</td>
      <td align="center">NUMBER OF ROOMS</td>
      <td align="center">CHILD WITHOUT MATTRESS</td>
      <td align="center">EXTRA MATTRESS</td>
      <td align="center">STATUS</td>
    </tr>
	 ';
	$hs=query('SELECT *,estimate_step_hotels.without_matress AS WS ,estimate_step_hotels.extra_matress AS EW  FROM `estimate_step_hotels` INNER JOIN  hotel ON hotel.hotelId=estimate_step_hotels.hotel_id 				INNER JOIN  room ON room.roomId=estimate_step_hotels.room_id WHERE step_1_id ="'.$id.'"');$i=1;
	while($ho=fetch($hs)){
	$place=fetch(query('SELECT * FROM place WHERE placeId="'.$ho['place'].'"'));

	$hotels =$hotels. '
    <tr>
      <td align="center">Day-'.$i.'</td>
      <td align="center">'.$place['place_name'].'</td>
      <td align="center">'.$ondate=date('d M Y', strtotime($doj . ' +'.$i.' day')).'</td>
      <td align="center">'.$ho['hotel_name'].'</td>
      <td align="center">'.$ho['discription'].'</td>
      <td align="center">'.$ho['meal'].'</td>
      <td align="center">'.$ho['number_of_rooms'].'</td>
      <td align="center">'.$ho['WS'].'</td>
      <td align="center">'.$ho['EW'].'</td>
      <td align="center">'.$ho['status'].'</td>
    </tr>
   	 ';
	$i++;}
	$hotels =$hotels. ';
	
	
  </tbody>
</table>

';


$pdf->writeHTML($hotels, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('/home/andamantrail/public_html/admin/pdf/VOC-'.$id.'.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+
?>