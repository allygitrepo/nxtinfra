<?php  session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../common/conn.php');
include('../../common/function.php');
ob_start();
$page='estimate';
$id=$_GET['id'];
$sf=fetch(query('SELECT * FROM  website_booking WHERE autobookingid="'.$id.'"'));
$it_id=$sf['itinerary_Id'];
$doj=$sf['doj'];
$travler=$sf['adult']+$sf['child'];
$doj_array=explode('/',$doj);
$step_1_id=$id=$_GET['id'];   
$p=fetch(query('SELECT * FROM `website_booking_payment_breakdown` WHERE autobooking_id="'.$id.'"'));
$it_id=$sf['itinerary_Id'];
$step1=fetch(query('SELECT * FROM `website_booking` WHERE autobookingid="'.$step_1_id.'"'));
$dt=fetch(query('SELECT * FROM detail  WHERE id=1'));

$uid=$step1['user_id'];
$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total'];}else{$hp= 0;} ;
if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total'];$gt=$gt+$p['grand_logi_total'];}else{ $lp = 0;};
if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total'];$gt=$gt+$p['grand_tkt_total'];}else{$tp= 0;}
if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total'];$gt=$gt+$p['grand_ferry_total'];}else{$fp=  0;};

$ep=fetch(query('SELECT SUM(orderAmount) AS rp FROM `website_booking_transection` WHERE orderId="'.$id.'" '));
$dd=fetch(query('SELECT due_date FROM `estimate_payment` WHERE step_1_id="'.$id.'" ORDER BY estimate_paymentId DESC'));
 $doj=$sf['doj'];
 $ndate=str_replace('/','-', $doj);
		 $parts = explode('-',$ndate);
		 $ndate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		
 $ondate=date('d M Y', strtotime($ndate));	 
 $tex=($gt*5)/100;
require_once('tcpdf_include.php');

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
// ---------------------------------------------------------

// set font

$doj=date('m/d/Y', strtotime($doj . ' -1 day'));
$ad=$step1['adult'];
$ch=$step1['child'];
$inf=$step1['infant'];
   
$pdf->SetFont('roboto', '', 12);
$gt=0; if($p['grand_hotel_total']!=0){ $hp= $gt=$gt+$p['grand_hotel_total'];}else{$hp= 0;} ;
if($p['grand_logi_total']!=0){ $lp =$p['grand_logi_total'];$gt=$gt+$p['grand_logi_total'];}else{ $lp = 0;};
if($p['grand_tkt_total']!=0){ $tp= $p['grand_tkt_total'];$gt=$gt+$p['grand_tkt_total'];}else{$tp= 0;}
if($p['grand_ferry_total']!=0){ $fp= $p['grand_ferry_total'];$gt=$gt+$p['grand_ferry_total'];}else{$fp=  0;};
$user=fetch(query('SELECT * FROM `user`  WHERE userId="'.$uid.'"'));

$cost=$p['grand_total'];
$discount=0;
$payble=$cost-$discount;
$gst=($payble*5)/100;
$gsthalf=$gst/2;
$pdf->SetFont('roboto', '', 8);


$overview = '<table width="680">
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
      <td align="left"> Exotrail Destination Management Private Limited<br />
        CIN: '.$dt['t0'].'<br />
        PAN: '.$dt['t5'].'<br />
        GSTIN: '.$dt['t6'].'<br />
        PLACE OF SUPPLY: A&N ISLANDS - 35 </td>
      <td align="right"> Total Cost: &#x20b9; '.$payble.'<br />
        Invoice#: INV-'.$id.'<br />
        Ref#: Booking-'.$id.'<br />
        Date: '.date('d M Y').' </td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td><img src="http://andamantrail.com/images/invb.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="left"> CUSTOMER NAME<br />
        '.$user['name'].'<br />
      </td>
      <td align="center">
        EMAIL:<br /> '.$user['email'].' </td>
      <td align="center"> 
        CONTACT:<br /> '.$user['mobile'].' </td>
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
<table width="680">
  <tbody>
    <tr>
      <td align="left"> COUNTRY OF SUPPLY:<br />
        <br />
        India - IN </td>
      <td align="center"> PLACE OF SUPPLY:<br />
        <br />
        Andaman & Nicobar - (35) </td>
      <td align="center"> DUE DATE<br />
        <br />
        '.$ondate.' </td>
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
<table width="680">
  <tbody>
    <tr>
      <td> PARTICULARS </td>
      <td> HSN / SAC </td>
      <td> COST </td>
      <td> IGST Rate </td>
      <td> Taxable Value </td>
      <td> CGST<br />
        @ 2.5% </td>
      <td> UTGST<br />
        @ 2.5% </td>
      <td> Total </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td> Hotels </td>
      <td> 998552 </td>
      <td>&#x20b9; '.$hp.' </td>
      <td> 5% </td>
      <td>&#x20b9; '.$t=($hp*5/100).' </td>
      <td>&#x20b9; '.($hp*5/200) .'</td>
      <td>&#x20b9; '.($hp*5/200) .' </td>
      <td>&#x20b9; '.($hp+($hp*5/100)).'</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td> Vehicle </td>
      <td> 998551 </td>
     <td>&#x20b9; '.$lp.' </td>
      <td> 5% </td>
      <td>&#x20b9; '.$t=($lp*5/100).' </td>
      <td>&#x20b9; '.($lp*5/200) .'</td>
      <td>&#x20b9; '.($lp*5/200) .' </td>
      <td>&#x20b9; '.($lp+($lp*5/100)).'</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td> Tickets, Permits and Fares </td>
      <td> 998554 </td>
       <td>&#x20b9; '.$tp.' </td>
      <td> 5% </td>
      <td>&#x20b9; '.$t=($tp*5/100).' </td>
       <td>&#x20b9; '.($tp*5/200) .'</td>
      <td>&#x20b9; '.($tp*5/200) .' </td>
      <td>&#x20b9; '.($tp+($tp*5/100)).'</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td> Ferry Tickets </td>
      <td> 998558 </td>
      <td>&#x20b9; '.$fp.' </td>
      <td> 5% </td>
      <td>&#x20b9; '.$t=($fp*5/100).' </td>
   <td>&#x20b9; '.($fp*5/200) .'</td>
      <td>&#x20b9; '.($fp*5/200) .' </td>
      <td>&#x20b9; '.($fp+($fp*5/100)).'</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    
    
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td> Total: </td>
      <td>&#x20b9; '.$tex.' </td>
      <td>&#x20b9; '.$tex/2 .' </td>
      <td>&#x20b9; '.$tex/2 .' </td>
      <td>&#x20b9; '.$cost .' </td>
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
<table width="680">
  <tbody>
    <tr>
      <td align="right"> Total Amount(+): &#x20b9; '.$cost .'<br /></td>
    </tr>
    <tr>
      <td align="right"> <br /></td>
    </tr>
    <tr>
      <td align="right"> Invoice Amount: &#x20b9; '.$payble.' </td>
    </tr>
    <tr>
      <td><img src="http://andamantrail.com/images/invl.png" width="680" alt="" /></td>
    </tr>
  </tbody>
</table>
<table width="680">
  <tbody>
    <tr>
      <td align="center">Amount Paid: &#x20b9; '.$ep['rp']/2 .'</td>
      <td align="center">Amount Due: &#x20b9; '.($payble-($ep['rp']/2)) .'</td>
      <td align="center">Due Date:  '.$ondate.' </td>
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
//echo $overview; die();
// output the HTML content

$pdf->writeHTML($overview, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('/home/andamantrail/public_html/admin/pdf/INV-'.$id.'.pdf', 'F');

//$pdf->Output('invoice.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
?>
