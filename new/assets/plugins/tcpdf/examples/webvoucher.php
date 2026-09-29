<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../common/conn.php');
include('../../common/function.php');
ob_start();
$id=$_GET['id'];
$step1=$sf=fetch(query('SELECT * FROM  website_booking WHERE autobookingid="'.$id.'"'));
$it_id=$sf['itinerary_Id'];
$doj=$sf['doj'];
$travler=$sf['adult']+$sf['child'];
$doj_array=explode('/',$doj);
$autobooking_id=$_GET['id'];     
$p=fetch(query('SELECT * FROM `website_booking_payment_breakdown` WHERE autobooking_id="'.$autobooking_id.'"'));
$dt=fetch(query('SELECT * FROM detail  WHERE id=1'));

 		 $ndate=str_replace('/','-',$doj);
		 $parts = explode('-',$ndate);
		  $ndate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		 $ndate= date("Y-m-d", strtotime($ndate) );
		 $enddate=date('d M Y', strtotime($ndate . ' + '.$sf['duration'].' day'));	
		 $startdate=date('d M Y', strtotime($ndate));
		 $doj=date('Y-m-d', strtotime($ndate));	

$ad=$step1['adult'];
$ch=$step1['child'];
$inf=$step1['infant'];
$duration=$step1['duration'];
$m=fetch(query('SELECT * FROM `mockup`')); 

$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total']+$m['hotel'];}else{$hp= 0;} ;

if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total']+$m['logistics'];$gt=$gt+$p['grand_logi_total']+$m['logistics'];}else{ $lp = 0;};

if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total']+$m['ticket'];$gt=$gt+$p['grand_tkt_total']+$m['ticket'];}else{$tp= 0;}

if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total']+$m['ferry'];$gt=$gt+$p['grand_ferry_total']+$m['ferry'];}else{$fp=  0;};

$cost=$p['grand_total'];
$discount=0;
$payble=$cost-$discount;
$gst=$p['gst_total'];
$cv=$p['cv'];


$gsthalf=$gst/2;



require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->SetPrintHeader(false);
//define ('PDF_HEADER_LOGO', 'https://andamantrail.com/img/andaman-trail-logo.jpg');
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
      <td><img src="https://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
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
      <td><img src="https://andamantrail.com/images/vocb.png" width="680" alt="" /></td>
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
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
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


$sc=$overview;
$confirmation = '
<table width="680">
  <tbody>
    <tr>
      <td><img src="https://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
      <td align="center">TOUR CO-ORDINATOR</td>
      <td align="center">DESTINATION</td>
      <td align="center">CONTACT NUMBER</td>
    </tr>';$px=(query('SELECT * FROM `website_booking_staff`  WHERE autobooking_id="'.$id.'"'));$i=1;
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
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/ferryvocb.png" width="680" alt="" /></td>
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
	 'SELECT * FROM `website_booking_ferry` 
				 INNER JOIN ferry_route ON ferry_route.routeId=website_booking_ferry.route_id 
				 INNER JOIN ferry ON ferry.ferryId=ferry_route.ferry_id 
				 INNER JOIN seat ON seat.seatId=website_booking_ferry.seat_id 
				 WHERE autobookingid="'.$id.'" ';
	$ferry=(query('SELECT * FROM `website_booking_ferry` 
				 INNER JOIN ferry_route ON ferry_route.routeId=website_booking_ferry.route_id 
				 INNER JOIN ferry ON ferry.ferryId=ferry_route.ferry_id 
				 INNER JOIN seat ON seat.seatId=website_booking_ferry.seat_id 
				 WHERE autobookingid="'.$id.'" '));$i=1;
				 while($fr=fetch($ferry)){ 
				 $ondate=date('d M Y', strtotime($doj . ' +'.$i.' day'));	
				 
	$r=fetch(query('SELECT * FROM website_booking_ferry_tickets WHERE   autobookingid="'.$id.'" && wb_ferry_id="'.$fr['website_booking_ferryId'].'"'));
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
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="https://andamantrail.com/images/vehvocb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680" border="1" cellpadding="10">
  <tbody>
    <tr>
	 <td align="center">DAY</td>
      <td align="center">VEHICLE</td>
      <td align="center">CATEGORY</td>
      <td align="center">REG.#</td>
      <td align="center">DESTINATION</td>
      <td align="center">DRIVER NAME</td>
      <td align="center">CONTACT #</td>
    </tr>
	    ';$row=(query('SELECT * FROM `website_booking_logistic` WHERE autobookingid="'.$id.'"'));   
		
	
	$n=1;while($r=fetch($row)){
		$p=fetch(query('SELECT * FROM  itineraries_detail  WHERE  itineraries_detailId="'.$r['itineraries_detail_id'].'"'));
	
	 $a=(query('SELECT * FROM website_booking_logistic AS wbl WHERE autobookingid ="'.$id.'" && itineraries_detail_id="'.$r['itineraries_detail_id'].'"'));
	$am=fetch($a);
	
				$s4=$am['4_seater'];
				$s7=$am['7_seater'];
				$s17=$am['17_seater'];
				$s24=$am['24_seater'];
			$vvv='';	
      if($s4!=0){ $vvv=$vvv.' <p>4 seater X '.$s4.'</p>';}
	  if($s7!=0){ $vvv=$vvv.' <p>7 seater X '.$s7.'</p>';}
	   if($s17!=0){ $vvv=$vvv.' <p>17 seater X '.$s17.'</p>';}
	    if($s24!=0){ $vvv=$vvv.' <p>24 seater X '.$s24.'</p>';}
				
	$confirmation =$confirmation. '
     <tr>
	 <td align="center">'.$n.'</td>
	 <td align="center">'.$vvv.'</td>
      <td align="center">'.$r['cat'].'</td>
      <td align="center">'.$r['type'].'</td>
      <td align="center">'.$place['place_name'].'</td>
      <td align="center">'.$r['drivername'].'</td>
      <td align="center">'.$r['diver_contact'].'</td>
    </tr>
  ';
	$n++; }
	$confirmation =$confirmation. '
  </tbody>
</table>
';
$sc=$sc.$confirmation;

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
      <td><img src="https://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/logo.png" width="100" alt="" /></td>
    </tr>
    <tr>
      <td><img src="https://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="https://andamantrail.com/images/hotelvocb.png" width="680" alt="" /></td>
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
      <td align="center">EXTRA </td>
      <td align="center">STATUS</td>
    </tr>
	 ';$rs=1;
	 $sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
$day=1;
while($row_d=fetch($sql_d)){
	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));
$placeid=$row_d['place_id'];

$hotelquery=(query('SELECT * FROM website_booking_hotels WHERE autobooking_id="'.$id.'" 
&& place_id="'.$placeid.'"'));

while($hotelss=fetch($hotelquery))
{
	 $booking_hotelsId=$hotelss['booking_hotelsId'];
	$roomsql=query('SELECT * FROM `website_booking_rooms` WHERE website_booking_hotel_id ="'.$booking_hotelsId.'"');
	while($hotel=fetch($roomsql))
{	$place=fetch(query('SELECT * FROM place WHERE placeId="'.$hotelss['place_id'].'"'));
	$wbhotel=fetch(query('SELECT * FROM  hotel WHERE hotelId ="'.$hotel['hotel_id'].'"'));
	$wbrooms=fetch(query('SELECT * FROM `room` WHERE roomId ="'.$hotel['room_id'].'"'));
	$hotels =$hotels. '
    <tr>
      <td align="center">Day-'.$rs.'</td>
      <td align="center">'.$place['place_name'].'</td>
      <td align="center">'.$ondate=date('d M Y', strtotime($doj . ' +'.$rs.' day')).'</td>
      <td align="center">'.$wbhotel['hotel_name'].'</td>
      <td align="center">'.$wbrooms['discription'].'</td>
      <td align="center">'.$hotel['extra'].'</td>


      <td align="center">'.$hotelss['status'].'</td>
    </tr>
   	 ';
	$i++;}}$rs++;}
	$hotels =$hotels. ';
	
	
  </tbody>
</table>
';


$pdf->writeHTML($hotels, true, false, true, false, '');

//die($hotels);
// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('/home/andamantrail/public_html/admin/pdf/VOC-'.$id.'.pdf', 'F');
//$pdf->Output('preview.pdf', 'D');
//============================================================+
// END OF FILE
//============================================================+
?>