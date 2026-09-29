<?php
if($_GET['sub'] == 'pdf'){

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
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$company_id = $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> Travel Request Register from ".$_POST['from_date']." TO ".$_POST['to_date']."</th></tr></table>";
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><th>Name</th>
					<th>Location From</th>
					<th>Date From</th>
					<th>Start Time</th>
					<th>Location To</th>
					<th>Date To</th>
					<th>End Time</th>
					<th>Company</th>
					<th>Advance Amount</th>
					<th>Airline Freq.No.</th>
					<th>Purpose of Visit</th>
					<th>Estimated Days</th>
					<th>UTR No.</th>
				</tr></table>";
				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_traval_approval";
	
	$sql 		= " SELECT * FROM $tableName where dated >= '$from_date' and dated <= '$to_date' ";

//echo $sql;
	
	if (!empty($company_id)){
		$sql  .= " and company_id = '$company_id' ";
	}
	
	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$emp_id = $row['emp_id'];
		$sql = "select * from sma_user where id = '$emp_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$emp_name = $r2['username'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		$start_date = date('d-m-Y', strtotime($row['start_date']));
		$end_date 	= date('d-m-Y', strtotime($row['end_date']));
		
		if($start_date=='01-01-1970'){ $start_date='';}
		if($end_date=='01-01-1970'){ $end_date='';}
		
		$traval_from 	= $row['traval_from'];
		$traval_to 		= $row['traval_to'];
		$start_time		= $row['start_time'];
		$end_time		= $row['end_time'];
		$advance_amount = $row['advance_amount'];
		$airline_frequent_no= $row['airline_frequent_no'];
		$purpose_visit		= $row['purpose_visit'];
		$estimated_days		= $row['estimated_days'];
		$utr_no				= $row['utr_no'];
		
		$message .= "<tr>
					<td>".$emp_name."</td>
					<td>".$traval_from."</td>
					<td>".$start_date ."</td>
					<td>".$start_time ."</td>
					<td>".$traval_to."</td>
					<td>".$end_date."</td>
					<td>".$end_time ."</td>
					<td>".$comp_name."</td>
					<td>".$advance_amount."</td>
					<td>".$airline_frequent_no."</td>
					<td>".$purpose_visit."</td>
					<td>".$estimated_days."</td>
					<td>".$utr_no."</td>
					</tr>";
		
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
		$fl_name = 'tr_export_data.xls';
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
			$fl_name = 'approval_memo.pdf';
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