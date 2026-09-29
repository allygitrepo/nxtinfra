<?php
if($_GET['sub'] == 'list'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$prn		= "excel";

		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='7'> Document History Details </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><th>Document Date</th>
					<th>Company</th>
					<th>Action</th>
					<th>Sent By</th>
					<th>Sent To</th>
					<th>Document No.</th>
					
					<th>Remarks</th>
					
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		
	$sql = $_SESSION['sqlhistory'];
	
//echo $sql."<BR>";


	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
						$inward_no = $row['doc_id'];
										
						$sent_by   = $row['create_by'];
						$sent_to   = $row['reviewed_by'];
						
						//$sent_date = date('d-m-Y', strtotime($row['create_date']));
						$sent_date = date('d-m-Y h:i:s', strtotime($row['create_date']));
						
						$status		= $row['status'];
						if($status = 'Accepted'){
							$status		= 'My Document';
						}	
						
						$sql="SELECT * from dms_inward where inward_no = '$inward_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						
						$company_for		 = $r2['company_for'];
						$sql = "select * from company where comp_id = '$company_for' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_code'];
						
						$mode_of_receipt = $r1['mode_of_receipt'];
						if($mode_of_receipt=='C'){
							$mode_of_receipt = 'Courier';
						}
						else if($mode_of_receipt=='H'){
							$mode_of_receipt = 'Hand Delivery';
						}
						else if($mode_of_receipt=='E'){
							$mode_of_receipt = 'Email';
						}
						else if($mode_of_receipt=='S'){
							$mode_of_receipt = 'Self';
						}
						
						$sql = "SELECT * FROM `sma_user` where id = '$sent_by' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sent_by  = $r2['username'];
						
						$sql = "SELECT * FROM `sma_user` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sent_to  = $r2['username'];
						
						$j=$j+1;						
		
			$message .= "<tr>
						<td>".$sent_date."</td>
						<td>".$company."</td>
						<td>".$status."</td>
						<td>".$sent_by."</td>
						<td>".$sent_to."</td>
						<td>".$inward_no."</td>
						<td>".$row['remarks'] ."</td>";
						
			$message .= "</tr>";
			
	}
	
	$message .= "</table>";

//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'document_history_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}

    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'budget_export.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
}

		