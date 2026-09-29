<?php

$message = "Hello...";
$message .= "Hello...";
$message .= "Hello...";
$message .= "Hello...";

require_once('../html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'poorder_test'. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		   // $html2pdf->writeHTML($message);
				
			$html2pdf->Output($fl_name);
		    
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}

?>
