<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../common/conn.php');
include('../../common/function.php');
$scape='';
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
$admin_id=$step1['admin_id'];
$adm=fetch(query('SELECT * FROM `admin`  WHERE adminId="'.$admin_id.'"'));
$dt=fetch(query('SELECT * FROM detail  WHERE id=1'));

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
$doj=date('m/d/Y', strtotime($doj . ' -1 day'));
$ad=$step1['adults'];
$ch=$step1['childern'];
$inf=$step1['infant'];
   
$pdf->SetFont('roboto', '', 12);
$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total']+$p['m_hotel'];}else{$hp= 0;} ;
if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total']+$p['m_logistic'];$gt=$gt+$p['grand_logi_total']+$p['m_logistic'];}else{ $lp = 0;};
if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total']+$p['m_ticket'];$gt=$gt+$p['grand_tkt_total']+$p['m_ticket'];}else{$tp= 0;}
if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total']+$p['m_ferry'];$gt=$gt+$p['grand_ferry_total']+$p['m_ferry'];}else{$fp=  0;};
if($p['grand_act_total']!=0){ $ap= $p['grand_act_total']+$p['m_activity'];$gt=$gt+$p['grand_act_total']+$p['m_activity'];}else{$ap= 0;};
 $tex=($gt*5)/100;
$dis=$step1['discount'];
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
      <td>Dear '.$sf['name'].',<br />
        <br />
        <br />
        Your '.$sf['duration'].' Nights &amp; '.($sf['duration']+1) .' Days itinerary to Andaman looks perfect. Here is the costing for the inclusions we have selected.<br />
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
      <td align="left">₹  '. $hp .'</td>
    </tr>
    <tr>';
	if($lp!=0 || $lp!=''){
$overview =$overview . '
      <td align="right"><img src="http://andamantrail.com/images/taxi.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Vehicles</td>
      <td align="left" valign="middle">₹   '. $lp .'</td>
    </tr>';}
if($tp!=0 || $tp!=''){
$overview =$overview . '
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/tickets.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Tickets</td>
      <td align="left" valign="middle">₹  '. $tp .'</td>
    </tr>
	';};
	if($fp!=0 || $fp!=''){
$overview =$overview . '
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/ferry-boat.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Ferry</td>
      <td align="left" valign="middle">₹  '. $fp .'</td>
    </tr>';}
	
	if($ap!=0 || $ap!=''){
		$overview =$overview . '
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/goggles.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Activities</td>
      <td align="left" valign="middle">₹ '. $ap .'</td>
    </tr>'; }
	$overview =$overview . '
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/taxes.png" width="32" alt="" /></td>
      <td align="center" valign="middle">GST @ 5%</td>
      <td align="left" valign="middle">₹  '. $tex .'</td>
    </tr>';
	if($dis!=0 || $dis!=''){
$overview =$overview . '	
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/poster.png" width="32" alt="" /></td>
      <td align="center" valign="middle">Discount</td>
      <td align="left" valign="middle">₹ '.$dis.'</td>
    </tr>';
	}
	$overview =$overview . '
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
      <td align="center" valign="middle">TOTAL COST inc. of all taxes for '.$step1['adults'].' Adult(s), '.$step1['childern'].' Child(ren), '.$step1['infant'].' Infant(s)</td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></ br></ br> </td>
    </tr>
    <tr>
      <td align="center" valign="middle">₹ '.($gt+$tex-$dis) .'</td>
    </tr>
  </tbody>
</table>


';
 $scape=$scape.$overview; 

// output the HTML content

$pdf->writeHTML($overview, true, false, true, false, '');

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


$sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
$itnumrows=num_rows($sql_d);
$day=1;
while($row_d=fetch($sql_d)){
	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	 

				   
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
$pdf->SetFont('roboto', '', 10);
if($day<$itnumrows){
$hoteld1 = '
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
</table><table width="680">
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
</table>';
}


$hs=query('SELECT *,estimate_step_hotels.without_matress AS WS ,estimate_step_hotels.extra_matress AS EW  FROM `estimate_step_hotels` INNER JOIN  hotel ON hotel.hotelId=estimate_step_hotels.hotel_id  INNER JOIN  room ON room.roomId=estimate_step_hotels.room_id WHERE itineraries_detail_id ="'.$row_d['itineraries_detailId'].'" && step_1_id="'.$step_1_id.'"');
$rm=1;
			   while($ho=fetch($hs)){
				   $ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));
				   $hoteld1 = $hoteld1.'
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
      <td><p>#'.$rm.'-Number Of Rooms x '.$ho['number_of_rooms'].' | Child without Mattress x '.$ho['WS'].' | Adult with Mattress x '.$ho['EW'].' <!--- Accomodates '.$ho['ch'].' + 1 Extra Child or '.$inf.' Adult--></p>
	  <p>Meal: '.$ho['meal'].'</p>
	  </td>
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
      <td><img src="https://www.andamantrail.com/admin/images/'.$ho['image'].'" width="200" alt="" /></td>
      <td><img src="https://www.andamantrail.com/admin/images/'.$ho['img'].'" width="200" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table> <table width="680">
<tbody>
<tr>
<td align="left">
<p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['hotel_name'].'</strong></p>
<p>&nbsp;&nbsp;&nbsp;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($ho['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
<p>&nbsp;&nbsp;&nbsp;Address: '.$ho['address'].'</p>
<p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
$a=(query('SELECT * FROM hotel_aminities  WHERE hotel_id="'.$ho['hotelId'].'"'));
			while($am=fetch($a)){
					$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"'));
$hoteld1= $hoteld1 .' <p>&nbsp;&nbsp;&nbsp;'. $r['aminitie_name'] .'</p>';}
$hoteld1 = $hoteld1.'
</td>
<td align="left">
<p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['discription'].'</strong></p>
<p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
$a=(query('SELECT * FROM room_aminities  WHERE room_id="'.$ho['roomId'].'"'));
while($am=fetch($a)){
	 $r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"')); 	
	 $hoteld1 = $hoteld1.'<p>&nbsp;&nbsp;&nbsp;'.$r['aminitie_name'].'</p>';
}
 $hoteld1 = $hoteld1.'
</td>
</tr>
</tbody>
</table>

<table width="680">
  <tbody>
    <tr>
      <td align="left"><p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['hotel_name'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($ho['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
        <p>&nbsp;&nbsp;&nbsp;Address: '.$ho['address'].'</p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
		$a=(query('SELECT * FROM hotel_aminities  WHERE hotel_id="'.$ho['hotelId'].'"'));
			while($am=fetch($a)){
					$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"'));
				$hoteld1= $hoteld1 .' <p>&nbsp;&nbsp;&nbsp;'. $r['aminitie_name'] .'</p>';}
		$hoteld1= $hoteld1 .'</td>
      <td align="left"><p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['discription'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
       $a=(query('SELECT * FROM room_aminities  WHERE room_id="'.$ho['roomId'].'"'));
								while($am=fetch($a)){
								$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"')); 	
      	$hoteld1= $hoteld1 .  '<p>&nbsp;&nbsp;&nbsp;'.$r['aminitie_name'].'</p>';
   }  
  $hoteld1 =$hoteld1 .'
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
';
/*$hoteld1 =$hoteld1. '

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
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$inf.' Infant(s)</p>
        <p>Rooms x '.$ho['number_of_rooms'].' | Child without Mattress x '.$ho['WS'].' | Adult with Mattress x '.$ho['EW'].'</p>
        <p>Date of Stay: '.$ondate.'</p></td>
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
      <td><img src="http://andamantrail.com/admin/images/'.$ho['image'].'" width="200" alt="" /></td>
      <td><p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['hotel_name'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($ho['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
        <p>&nbsp;&nbsp;&nbsp;Address: # '.$ho['hotel_name'].'</p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>
		';
		$a=(query('SELECT * FROM hotel_aminities  WHERE hotel_id="'.$ho['hotelId'].'"'));
			while($am=fetch($a)){
					$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"'));
				$hoteld1= $hoteld1 .' <p>&nbsp;&nbsp;&nbsp;'. $r['aminitie_name'] .'</p>';}
			
				$hoteld1 =$hoteld1 .'</td>  </tr>
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

  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/admin/images/'.$ho['img'].'" width="200" alt="" /></td>
      <td><p>&nbsp;&nbsp;&nbsp;<strong>'.$ho['discription'].'</strong></p>
        <p>&nbsp;&nbsp;&nbsp;<strong>Amenities</strong></p>';
	 $a=(query('SELECT * FROM room_aminities  WHERE room_id="'.$ho['roomId'].'"'));
								while($am=fetch($a)){
								$r=fetch(query('SELECT * FROM aminities WHERE aminitieId="'.$am['aminitie_id'].'"')); 	
      	$hoteld1= $hoteld1 .  '<p>&nbsp;&nbsp;&nbsp;'.$r['aminitie_name'].'</p>';
   }  
  $hoteld1 =$hoteld1 .' </td> </tr>
  </tbody>
</table>
';
*/
$rm++;


}$pdf->writeHTML($hoteld1, true, false, true, false, '');
 $day++;

  $scape=$scape.$hoteld1; 
   $hoteld1='';
  }
//die( $scape);


$sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
$day=1;
while($row_d=fetch($sql_d)){
	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	 
	$log='SELECT *  FROM `estimate_step_logistic`  WHERE  step_1_id ="'.$step_1_id.'" && itineraries_detail_id="'.$row_d['itineraries_detailId'].'"';
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
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.'  Child(ren), '.$inf.' Infant(s)</p></td>
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
	 if(!(empty($log_array['4_seater']))||$log_array['4_seater']!=''){$vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;4 Seater x '.($log_array['4_seater']) .'</p>';}
    if(!(empty($log_array['7_seater']))||$log_array['7_seater']!=''){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;7 Seater x '.($log_array['7_seater']) .'</p>';}
    if(!(empty($log_array['17_seater']))||$log_array['17_seater']!=''){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;17 Seater x '.($log_array['17_seater']) .'</p>';}
    if(!(empty($log_array['24_seater']))||$log_array['24_seater']!=''){   $vehd1 =  $vehd1.'<p>&nbsp;&nbsp;&nbsp;24 Seater x '.($log_array['24_seater']) .'</p>';}
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

$tick='<tr>
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
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$inf.' Infant(s)</p></td>
    </tr>
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
//echo $my_html; die();
// output the HTML content


}$day++;

} $pdf->writeHTML($tick, true, false, true, false, '');
 $scape=$scape.$tick; 

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
       <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$inf.' Infant(s)</p></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
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
$fq='SELECT * FROM `estimate_step_ferry` INNER JOIN ferry_route ON ferry_route.routeId=estimate_step_ferry.ferry_id INNER JOIN ferry ON ferry.ferryId=ferry_route.ferry_id INNER JOIN seat ON seat.seatId=estimate_step_ferry.seat_id WHERE step_1_id="'.$id.'" ';
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
//echo $my_html; die();
// output the HTML content

if($fp!=0 && $fp!=''){$pdf->writeHTML($ferry, true, false, true, false, '');$scape=$scape.$ferry; }
if($ap!=0 && $ap!=''){

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
 $day=1;
 $activities = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/actb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
   $sql_d=query("SELECT * FROM itineraries_detail WHERE itinerary_id='".$it_id."'");
    while($row_d=fetch($sql_d)){	$ondate=date('d M Y', strtotime($doj . ' +'.$day.' day'));	
$activities =$activities .'<table width="680">
  <tbody>

	<tr>
                          <td align="center"><img src="http://andamantrail.com/images/d'.$day.'b.png" width="680" alt="" /></td>
                        </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td><p>Number of Travellers : '.$ad.' Adult(s), '.$ch.' Child(ren), '.$inf.' Infant(s)</p></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
';
// set font

$pdf->SetFont('roboto', '', 12);
 $acc=(query(' SELECT * FROM `estimate_step_activity` 
  INNER JOIN  activity ON activity.activityId=estimate_step_activity.activity_id WHERE step_1_id="'.$id.'" && itineraries_detail_id="'.$row_d['itineraries_detailId'].'"'));
  	 while($a=fetch($acc)){
$activities =$activities.'
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/admin/images/'.$a['icon'].'" width="200" alt="" /></td>
      <td><p>&nbsp;&nbsp;&nbsp;Activity: '.$a['activity_name'].'</p>
        <p>&nbsp;&nbsp;&nbsp;<img src="https://www.tripadvisor.com/img/cdsi/img2/ratings/traveler/'.number_format($a['rating'], 1).'-11942-5.svg" width="100" alt="" /></p>
        <p>&nbsp;&nbsp;&nbsp;No of Tickets # '.$a['ticktes'].'</p></td>
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
}if(num_rows($acc)==0){$activities =$activities. '<table width="680">
  <tbody>
    <tr>
      <td align="center">– Not Applicable—</td>
    </tr>
  </tbody>
</table> ';}  $day++; } 



if($ap!=0 && $ap!=''){$pdf->writeHTML($activities, true, false, true, false, '');}
$scape=$scape.$activities; 
	

$pdf->AddPage('P','A4');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
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
  </tbody>
</table>

<table width="680">
  <tbody>
   <tr>
      <th>S.no</th>
	  <th>Payment Details</th>
	    <th>Percentage</th>
		<th>Amount</th>
		<th>Due Date</th>
		<th>Immediate</th>
    </tr>
';
$pay=query('SELECT * FROM `estimate_payment` WHERE step_1_id="'.$id.'"');
$i=1;
while($pr=fetch($pay)){
	if($pr['immediate']==0){$imm='NO';}else{$imm='YES';}
$payment = $payment.'
 <tr>
  <td>'.$i.'</td>
      <td>'.$pr['payment_details'].'</td>
	   <td>'.$pr['percentage'].' %</td>
	    <td>'.(($gt+$tex-$dis)*$pr['percentage'])/100 .'</td>
		 <td>'.$pr['due_date'].'</td>
		  <td>'.$imm.'</td>
    </tr>
';

$i++;}
$payment = $payment.'
  </tbody>
</table>

';
$pdf->writeHTML($payment, true, false, true, false, '');
	 $scape= $scape.$payment ;
// add a page
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
$bank = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/bankb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td> '.$dt['c1'].' </td>
    </tr>
  </tbody>
</table>

';
//echo $my_html; die();
// output the HTML content

$pdf->writeHTML($bank, true, false, true, false, '');
	 $scape= $scape.$bank ;

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

$terms = '<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/tcb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td> '.$dt['c2'].' </td>
    </tr>
  </tbody>
</table>

';
//echo $my_html; die();
// output the HTML content

$pdf->writeHTML($terms, true, false, true, false, '');
	 $scape= $scape.$terms ;

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
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/refundb.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td> '.$dt['c3'].' </td>
    </tr>
  </tbody>
</table>
';
//echo $my_html; die();
// output the HTML content

$pdf->writeHTML($refund, true, false, true, false, '');
 $scape= $scape.$refund ;

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

$inc = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/incb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
';
$in_sql=query('SELECT * FROM `estimate_step_inclusion` INNER JOIN inclusion ON estimate_step_inclusion.inclusion_id=inclusion.inclusionId WHERE step_1_id="'.$id.'"');
while($inrow=fetch($in_sql)){
$inc = $inc.'<tr>
  <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
</tr>
<tr>
  <td>'.$inrow['name'].'</td>
</tr>
';
}
$inc = $inc.'</tbody>
</table>';
$pdf->writeHTML($inc, true, false, true, false, '');
 $scape= $scape.$inc ;

// add a page

$pdf->AddPage('P','A4');

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font

$pdf->SetFont('roboto', '', 12);

$exc = '
<table width="680">
  <tbody>
    <tr>
      <td align="right"><img src="http://andamantrail.com/images/logo.png" width="200" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/excb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>';
$ex_sql=query('SELECT * FROM `estimate_step_exclusions` INNER JOIN exclusions ON estimate_step_exclusions.exclusions_id=exclusions.exclusionsId WHERE step_1_id="'.$id.'"');
while($exrow=fetch($ex_sql)){
$exc =$exc. '
    <tr>
      <td align="center"><img src="http://andamantrail.com/images/whiten.png" width="680" alt="" /></td>
    </tr>
    <tr>
      <td>'.$exrow['name'].'</td>
    </tr>
';
}
$exc =$exc. '
  </tbody>
</table>';
$pdf->writeHTML($exc, true, false, true, false, '');
 $scape= $scape.$exc ;


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
</table>';

//echo $my_html; die();
// output the HTML content

$pdf->writeHTML($back, true, false, true, false, '');

//echo  $scape= $scape.$back ;
//die();

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_clean();
//Close and output PDF document
//$pdf->Output('preview.pdf', 'D');

$pdf->Output('/home/andamantrail/public_html/admin/pdf/ET-'.$id.'.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+
?>
