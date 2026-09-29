<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../../ajax/conn.php');
include('../../../ajax/function.php');
ob_start();
$page='quotation';
$id=$_GET['id'];

$r=fetch(query('SELECT * FROM  quotation WHERE id="'.$id.'"'));

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

$o=fetch(query('SELECT * FROM `organization`'));
   
$pdf->SetFont('roboto', '', 12);
$pdf->SetFont('roboto', '', 8);



$html='
<table style="z-index:9999;font-family:Arial, Helvetica, sans-serif" width="100%" border="0">
	<tbody>
		<tr>
			<td>
				<table style="border-bottom:6px solid #000" width="100%" border="0">
					<tbody>
						<tr>
							<td style="color:#948A54;">
								<tr><td><img style="display:block;background-color:#fff;" src="http://crm.copsindia.in/assets/1595262375.png" alt="Copsindia" class="CToWUd" width="200" border="0"/></td></tr>
							
							</td>
							<td align="right">
							<tr><td>'.$o['name'].'</td></tr>
							<tr><td>'.$o['address'].'</td></tr>
							<tr><td>Phone: '.$o['contact_no'].'</td></tr>
							<tr><td>Website: '.$o['website'].'</td></tr>
							<tr><td>Mail: '.$o['email'].'</td></tr>
							<tr><td>GST: '.$o['gst_no'].'</td></tr>
							</td>
						</tr>
					</tbody>
				</table>
				<table width="100%" border="0" style="margin-top:50px">
					<tbody>
					<tr>
					<td><br><br>Ref. No: '.$r['ref'].'</td>
					</tr>
					<tr><td><br><br><br><br>To,</td></tr>
					<tr><td>'.$r['name'].'</td></tr>
				    <tr><td>'.$r['address'].' '.$r['pin'].' '.$r['city_id'].' '.$r['state_id'].'</td></tr>
					<tr><td>'.$r['mobile_no'].'</td></tr>
					<tr><td>'.$r['email_id'].'<br><br>
					<br><br></td></tr>
					
					<tr><td><strong>Subject :-</strong> '.$r['subject'].'<br><br>
					<br><br></td></tr>
					
						<tr style="margin-top:50px">
							<td colspan="3" style="color:#000000;font-weight:bold;">
							'.$r['detail'].'
							<br><br><br><br>
							</td>
						</tr>
					</tbody>
				</table>
			
						<table style="border-collapse:collapse" width="100%" border="0">
							<tbody>
								<tr style="border: 1px solid#3D6B86" bgcolor="#3D6B86">
									<th style="padding:5px; color:#fff; text-align: center; ">No.</th>
									<th style="padding:5px ; color:#fff; text-align: center; ">Service name</th>
									<th style="padding:5px ; color:#fff; text-align: center; ">Description</th>
								
									<th style="padding:5px ; color:#fff; text-align: center; ">Frequency</th>
									
										<th style="padding:5px ; color:#fff; text-align: center; ">Rate</th>
								</tr>
								
								';
								 $sql_invoice2 = query("SELECT * FROM quotation_services WHERE quotation_id=".$id); 
        $i=1;
        while($row_invoice2 =fetch($sql_invoice2)){	
									$html=$html.'<tr style="border: 1px solid#3D6B86"><td style="border-right:1px solid #000; border-left:1px solid #000; text-align: center;">'.$i.'</td>
									<td style="border-right:1px solid #000; border-left:1px solid #000; text-align: center;">'.get_service_name($row_invoice2['service_id']).'</td>
									<td style="border-right:1px solid #000; border-left:1px solid #000; text-align: center;">'.($row_invoice2['description']).'</td>
									<td style="border-right:1px solid #000; border-left:1px solid #000; text-align: center;">'.($row_invoice2['frequency']).'</td>
									<td style="border-right:1px solid #000; border-left:1px solid #000; text-align: center;">'.($row_invoice2['rate']).'</td></tr>';
	$i++;}
									$html=$html.'
								
							</tbody>
						</table>
				<table width="100%" border="0" style="margin-top:50px">
					<tbody>
					
					
					
						<tr style="margin-top:50px">
							<td colspan="3" style="color:#000000;font-weight:bold;">
							'.$r['other_detail'].'
							<br><br><br><br>
							</td>
						</tr>
					</tbody>
				</table>
							
						</td>
					</tr>
				</tbody>
			</table>
';
echo $html;
;
die();
// output the HTML content

$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_end_clean();

//Close and output PDF document
$pdf->Output('INV-'.$id.'.pdf', 'D');

//$pdf->Output('invoice.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
?>
