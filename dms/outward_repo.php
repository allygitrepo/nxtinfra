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
			<tr><th style='width: 100%;' colspan='7'> Outward Document Details </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<th>Outward Date</th>
					<th>Courier Details</th>
					<th>For Company</th>
					<th>On Behalf</th>
					<th>Sent To</th>
					<th>Outward Doc. No.</th>
					<th>Remarks</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		
	$sql = $_SESSION['sqlout'];
	
//echo $sql."<BR>";


	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$status  = $row['status'];
				
						$sent_by 	= $row['sent_by'];
						$from_user 	= $row['from_user'];
						$sql 	= "SELECT * FROM `sma_user` where id = '$from_user' ";
						$q2  	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$from_user  = $r2['username'];
							
						$outward_no = $row['outward_doc_no'];
						
						$user_type 	= $row['user_type'];
						$sent_to 	= $row['send_to_user'];
						if($user_type =='U'){
							$sql 	= "SELECT * FROM `sma_user` where id = '$sent_to' ";
							$q2  	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$send_to  = $r2['username'];
						}
						else if($user_type =='P'){
							$sql 	= "SELECT * FROM `sma_party_mst` where id = '$sent_to' ";
							$q2  	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$send_to  = $r2['party_name'];
						}
//echo $sql. "<BR>";						
						
						$inward_no = $row['inward_no'];
						
						$company = $row['company_for'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						//$company  = $r2['comp_name'];
						$company  = $r2['comp_code'];

						$department = $row['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						
						$mode_of_receipt = $row['mode_of_receipt'];
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
						
						//$courier_status		= $_POST['courier_status'];
						
				$ie_flag			= $row['ie_flag'];
				$courier_details	= $row['courier_details'];
				//$from_user			= $row['from_user'];
				if($ie_flag=='I'){
					$ie_flag = 'Internal';
				}
				else {
					$ie_flag = 'External';
				}

				$to_others = $row['to_others'];
				if( !empty($to_others) ){
					$send_to = $to_others;
				}
				
					$j=$j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				$date_of_received = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
				
				$j=$j+1;
			$message .= "<tr>
						<td>".$date_of_received."</td>
						<td>".$courier_details."</td>
						<td>".$company."</td>
						<td>".$from_user."</td>
						<td>".$send_to."</td>
						<td>".$outward_no."</td>
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
		$fl_name = 'outward_doc_export.xls';
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

		