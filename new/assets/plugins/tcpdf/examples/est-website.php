<?php    ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../common/conn.php');
include('../../common/function.php');
$autobooking_id= $id=$_GET['id'];
 
 //echo "SELECT * FROM `website_booking`  WHERE autobookingid='".$id."'";
 $sql=query("SELECT * FROM `website_booking`  WHERE autobookingid='".$id."'");
 $row=fetch($sql);
 $sql_user=query("SELECT * FROM user WHERE userId='".$row['user_id']."'");
 $row_user=fetch($sql_user);
 $adm=fetch(query('SELECT * FROM `admin`  WHERE adminId="'.$row_user['ai'].'"'));
 $p=fetch(query('SELECT * FROM `website_booking_payment_breakdown` WHERE autobooking_id="'.	 $autobooking_id.'"'));
 $dt=fetch(query('SELECT * FROM detail  WHERE id=1'));
 $doj=$row['doj'];
 $ndate=str_replace('/','-', $doj);
		 $parts = explode('-',$ndate);
		 $ndate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		 $doj=date('Y-m-d', strtotime($ndate . ' -1 day'));	
 $ad=$row['adult'];
 $ch=$row['child'];
 $in=$row['infant'];
 $it_id=$row['itinerary_Id'];
 $pr=fetch(query('SELECT * FROM `website_booking_payment` WHERE autobooking_id="'.$autobooking_id.'"'));
 $break=fetch(query('SELECT * FROM `website_booking_payment_breakdown` WHERE autobooking_id="'.$autobooking_id.'"'));
	$tr=fetch(query('SELECT * FROM `website_booking_transection` WHERE referenceId="'.$pr['ref_id'].'"'));
//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
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


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$scape='';
$overview = '

<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></ br></ br> </td>
    </tr>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/estovb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></ br></ br> </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td>Dear '.$row_user['name'].',<br />
        <br />
        <br />
        Your '.$row['duration'].' Nights &amp; '.($row['duration']+1 ).' Days itinerary to Andaman looks perfect. Here is the costing for the inclusions we have selected.<br />
        <br />
        <br />
        If you feel like giving a read on the destination you are heading to, make use of Andaman Trail guides!<br />
        <br />
        <br />
        Also, giving you a heads-up! The prices of airlines and hotels fluctuate a lot. Book your trip before the rates change!<br />
        <br /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/bedroom.png" width="32" alt="" /></td>
      <td align="center">Hotels</td>
      <td align="left">₹  '.$p['grand_hotel_total'].'</td>
    </tr>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/taxi.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Vehicles</td>
      <td align="left" valign="middle">₹  '.$p['grand_logi_total'].'</td>
    </tr>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/tickets.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Tickets</td>
      <td align="left" valign="middle">₹  '.$p['grand_tkt_total'].'</td>
    </tr>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/ferry-boat.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Ferry</td>
      <td align="left" valign="middle">₹  '.$p['grand_ferry_total'].'</td>
    </tr>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/taxes.png" width="32" alt="" /></td>
      <td align="center" valign="middle">GST @ 5%</td>
      <td align="left" valign="middle">₹ '.$p['gst_total'].'</td>
    </tr>
	<tr>
      <td align="right"><img src="http://andamantrail.com/images/taxes.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Conveyance charges @ 1%</td>
      <td align="left" valign="middle">₹ '.$p['cv'].'</td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></ br></ br> </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center" valign="middle">TOTAL COST inc. of all taxes for '.$ad.' Adult(s), '.$ch.' Child(ren), '.$in.' Infant(s)</td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></ br></ br> </td>
    </tr>
    <tr>
      <td align="center" valign="middle">₹  '.$p['grand_total'].'</td>
    </tr>
  </tbody>
</table>
';
$pdf->writeHTML($overview, true, false, true, false, '');

$scape=$scape.$overview; 
//die($scape);

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
// Set some content to print


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -




// ITINERARY DAY 1
$sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
				$day=1;
					 while($row_d=fetch($sql_d)){
					$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	 
// ITINERARY DAY 

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
$pdf->SetFont('roboto', '', 12);
$itineraryd1 = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/itib.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/d'.$day.'b.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="right"> Date : '.$ondate.' </td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img style="width: 100%;"  src="http://andamantrail.com/admin/images/'.$row_d['image'].'" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td>'.$row_d['day_details'].' </td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/line.png" width="200" alt="" /></td>
    </tr>
  </tbody>
</table>
';
$pdf->writeHTML($itineraryd1, true, false, true, false, '');
 $scape=$scape.$itineraryd1; 
 $day++; };

// add a page
$hoteldhead ='
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/hotelb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>';



// ---------------------------------------------------------

// set font

$pdf->SetFont('roboto', '', 10);

$sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
$day=1;$rs=1;
while($row_d=fetch($sql_d)){
	if($rs<=$row['duration']){
	$pdf->AddPage('P','A4');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));
$hoteld1 =$hoteldhead.'
<table width="680">
  <tbody>
    <tr>
      <td><p>Date of Stay: '.$ondate.'</p></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/d'.$day.'b.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
$placeid=$row_d['place_id'];

$hotelquery=(query('SELECT * FROM website_booking_hotels WHERE autobooking_id="'.$id.'" 
&& place_id="'.$placeid.'"'));

while($hotel=fetch($hotelquery))
{
	 $booking_hotelsId=$hotel['booking_hotelsId'];
	$roomsql=query('SELECT * FROM `website_booking_rooms` WHERE website_booking_hotel_id ="'.$booking_hotelsId.'"');
	while($hotel=fetch($roomsql))
{
	
	$wbhotel=fetch(query('SELECT * FROM  hotel WHERE hotelId ="'.$hotel['hotel_id'].'"'));
	$wbrooms=fetch(query('SELECT * FROM `room` WHERE roomId ="'.$hotel['room_id'].'"'));
	$rtype=$wbrooms['occupancy'];
		if($rtype=='Single Bed'){$occ=1;}
		if($rtype=='Double Bed'){$occ=2;}
		if($rtype=='Triple Bed'){$occ=3;}
		if($rtype=='Four Bed Family'){$occ=4;}
		if($rtype=='Six Bed Family'){$occ=6;}
$hoteld1 =$hoteld1.'
<table width="680">
  <tbody>
    <tr>
      <td><p>Room # '.$rs.' - Accomodates '. $occ .' + '.$hotel['extra'].'</p></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="https://andamantrail.com/admin/images/'.$wbhotel['image'].'" width="200" alt="" /></td>
      <td><img src="https://andamantrail.com/admin/images/'.$wbrooms['img'].'" width="200" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="left"><p>&nbsp;&nbsp;&nbsp;<strong>'.$wbhotel['hotel_name'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($wbhotel['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
        <p>&nbsp;&nbsp;&nbsp;Address: # '.$wbhotel['address'].'</p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
		$a=(query('SELECT * FROM hotel_aminities  WHERE hotel_id="'.$wbhotel['hotelId'].'"'));
			while($am=fetch($a)){
					$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"'));
			$hoteld1= $hoteld1 .' <p>&nbsp;&nbsp;&nbsp;'. $r['aminitie_name'] .'</p>';}
			$hoteld1= $hoteld1 .'</td> <td align="left"><p>&nbsp;&nbsp;&nbsp;<strong>'.$wbrooms['discription'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
         $a=(query('SELECT * FROM room_aminities  WHERE room_id="'.$wbrooms['roomId'].'"'));
								while($am=fetch($a)){
								$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"')); 	
      	$hoteld1= $hoteld1 .  '<p>&nbsp;&nbsp;&nbsp;'.$r['aminitie_name'].'</p>';
   }  
  $hoteld1 =$hoteld1 .'
    </td></tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>


';

}$pdf->writeHTML($hoteld1, true, false, true, false, '');
$day++;$scape=$scape.$hoteld1;} }$rs++; }


//die($hoteld1);



$sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
$day=1;
while($row_d=fetch($sql_d)){
	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	 
	$log='SELECT *  FROM `website_booking_logistic`  WHERE  autobookingid ="'.$id.'" && itineraries_detail_id="'.$row_d['itineraries_detailId'].'"';
	$logsql=query($log);
	while($log_array=fetch($logsql)){
	 $log_z=fetch(query('SELECT * FROM logistics WHERE logisticsId="'.$log_array['logistic_id'].'" '));

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
$pdf->SetFont('roboto', '', 12);

$vehd1 = '

<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/vehb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.'  Child(ren), '.$in.' Infant(s)</p></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/d'.$day.'b.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="right"> Date : '.$ondate.' </td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/cab.jpg" width="200" alt="" /></td>
      <td>';
	 if(!(empty($log_array['4_seater'])) && $log_array['4_seater']!=''&&$log_array['4_seater']!='0' ){$vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;4 Seater x '.($log_array['4_seater']) .'</p>';}
    if(!(empty($log_array['7_seater']))&& $log_array['7_seater']!='' && $log_array['7_seater']!='0'){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;7 Seater x '.($log_array['7_seater']) .'</p>';}
    if(!(empty($log_array['17_seater']))&& $log_array['17_seater']!=''){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;17 Seater x '.($log_array['17_seater']) .'</p>';}
    if(!(empty($log_array['24_seater']))&& $log_array['24_seater']!=''){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;24 Seater x '.($log_array['24_seater']) .'</p>';}
	  $vehd1 =  $vehd1.'</td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/line.png" width="200" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><p>'.$log_z['description'].'</p></td>
    </tr>
  </tbody>
</table>


';


$pdf->writeHTML($vehd1, true, false, true, false, '');
}$day++;$scape=$scape.$vehd1;  }
//die($scape);


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
$pdf->SetFont('roboto', '', 12);

$tick='<table width="680">
<tbody><tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/ticketb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
	 <tr>
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$in.' Infant(s)</p></td>
    </tr>
	</tbody></table>
	
	';
  $sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
				$day=1;
					 while($row_d=fetch($sql_d)){
					$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	
						$esti_tkt=query('SELECT * FROM `itineraries_ticket` INNER JOIN tickets ON tickets.ticketsId=itineraries_ticket.ticket_id   WHERE itineraries_detail_id="'.$row_d['itineraries_detailId'].'"');
				while($row_tkt=fetch($esti_tkt)){ 
$tick =$tick. '<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/d'.$day.'b.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="right"> Date : '.$ondate.'</td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><p>'.$row_tkt['discription'].'</p></td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    
  </tbody>
</table>
';



}$day++;

} $pdf->writeHTML($tick, true, false, true, false, '');
 $scape=$scape.$tick; 
// die($scape);

$ferry ='<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/ferryb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
       <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$in.' Infant(s)</p></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
$fp=1;
if($fp!=0 && $fp!=''){
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
}
$pdf->SetFont('roboto', '', 12);
 $fq='SELECT * FROM `website_booking_ferry` INNER JOIN ferry_route ON ferry_route.routeId=website_booking_ferry.route_id 
INNER JOIN ferry ON ferry.ferryId=ferry_route.ferry_id 
INNER JOIN seat ON seat.seatId=website_booking_ferry.seat_id WHERE autobookingid="'.$id.'" ';
	$day=1; $fer=query($fq);
				 while($fr=fetch($fer)){
					 	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));
					 $place=fetch(query('SELECT * FROM place WHERE placeId="'.$fr['origin'].'"'));
					 $place2=fetch(query('SELECT * FROM place WHERE placeId="'.$fr['destination'].'"'));
					 
$ferry =$ferry.'
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/admin/images/'.$fr['img'].'" width="200" alt="" /></td>
      <td><p>&nbsp;&nbsp;&nbsp;Ferry: '.$fr['name'].'</p>
        <p>&nbsp;&nbsp;&nbsp;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($fr['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
        <p>&nbsp;&nbsp;&nbsp;Seat: '.$fr['type'].'</p>
        <p>&nbsp;&nbsp;&nbsp;Date : '.$ondate.'</p>
        <p>&nbsp;&nbsp;&nbsp;Route: '.$place['place_name'].' to  '.$place2['place_name'].'</p>
	  </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
 $day++; }


if($fp!=0 && $fp!=''){$pdf->writeHTML($ferry, true, false, true, false, '');$scape=$scape.$ferry; }




$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// add a page

$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$payment = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/payb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>

      <td><p>Total: ₹ '.$break['grand_total'].'</p>
        <p>Paid : ₹ '. $tr['orderAmount'].'</p>
        <p>On Arrival: ₹ '.($break['grand_total']-$tr['orderAmount']).'</p></td>
    </tr>
  </tbody>
</table>
';


$pdf->writeHTML($payment, true, false, true, false, '');
$scape=$scape.$payment;
// add a page
$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$bank = '

<table width="680">
<tbody>

<tr>
<td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/bankb.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td>
'.$dt['c1'].'
</td>
</tr>
</tbody></table>
';


$pdf->writeHTML($bank, true, false, true, false, '');

$scape=$scape.$bank;
 
// add a page

$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$terms = '

<table width="680">
<tbody>

<tr>
<td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/tcb.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td>
'.$dt['c2'].' 
</td>
</tr>



</tbody></table>



';


$pdf->writeHTML($terms, true, false, true, false, '');
$scape=$scape.$terms;


// add a page

$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$refund = '

<table width="680">
<tbody>

<tr>
<td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/refundb.png" width="680" alt="" />
</td>
</tr>

<tr>
<td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" />
</td>
</tr>

<tr>
<td>
'.$dt['c3'].' 
</td>
</tr>



</tbody></table>



';


$pdf->writeHTML($refund, true, false, true, false, '');
$scape=$scape.$refund;
 //die($scape);
// add a page

$pdf->AddPage('P','A4');


$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


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

$pdf->SetFont('roboto', '', 12);

$back = '<table width="680">
  <tbody>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/logo.png" width="300" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><p></p>
        <p></p>
        <p></p>
        <p></p>
        <p>Your Customer Relationship Officer</p>
        <p>Documented by: '.$adm['admin_name'].'</p>
		<p>Designation: '.$adm['designation'].'</p>
		<p>Direct Contact: '.$adm['desk'].'</p>
		<p>Extension: '.$adm['extension'].'</p>
        <p>Email: '.$adm['email'].'</p></td>
    </tr>
    <tr>
      <td><p></p>
        <p></p>
        <p></p>
        <p></p>
        <p>Andaman Trail is a brand owned by Exotrail Destination Manaagement Private Limited</p>
        <p>All rights reserved by Exotrail Destination Management Private Limited</p>
        <p>CIN: '.$dt['t0'].'</p>
        <p>PAN: '.$dt['t5'].'</p>
        <p>TAN: '.$dt['t6'].'</p>
        <p>For any queries, please call: 1800-200-5100</p></td>
    </tr>
  </tbody>
</table>';;


// output the HTML content

$pdf->writeHTML($back, true, false, true, false, '');

$scape=$scape.$back;
 //die($scape);






// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('/home/andamantrail/public_html/admin/pdf/ET-'.$id.'.pdf', 'F');
ob_end_flush(); 
//============================================================+
// END OF FILE
//============================================================+
?>
