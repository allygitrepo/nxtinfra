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
		
	$message  ='';
	$message1 ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><th style='width: 100%;' colspan='11'> Travel Trip Register from ".$_POST['from_date']." TO ".$_POST['to_date']."</th></tr></table>";
	
	
	$message1 .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><th style='width: 100%;' colspan='11'> Travel Expenses Register from ".$_POST['from_date']." TO ".$_POST['to_date']."</th></tr></table>";
	
	
	$head .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
				<tr><th>Name</th>
					<th>Dated</th>
					<th>App.Ref.No.</th>
					<th>Company</th>
					<th>Advance Amount</th>";
					
	$message .= $head. "	<th>Invoice No.</th>
					<th>Start Data</th>
					<th>Start Place</th>
					<th>Start Time</th>
					<th>End Data</th>
					<th>End Place</th>
					<th>Finish Time</th>
					<th>Mode of Travel</th>
					<th>Spand By</th>
					<th>Fare</th>
					<th>GST</th>
				</tr></table>";	

	$message1 .= $head."<th>Invoice No.</th>
					<th>Expenses</th>
					<th>Dated</th>
					<th>Amount</th>
					<th>Remarks</th>
					<th>GST</th>
					</tr></table>";
					
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
		$message1 .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
		
	$id				= $_GET['id'];
	
	
	$tableName	= "sma_travel_expenses";
	
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
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$approval_ref_no 	= $row['approval_ref_no'];
		$advance_amount 	= $row['advance_amount'];
		//$utr_no				= $row['utr_no'];
		
		$hdr_msg = "<tr>
					<td>".$emp_name."</td>
					<td>".$dated ."</td>
					<td>".$approval_ref_no ."</td>
					<td>".$comp_name."</td>
					<td>".$advance_amount."</td>";
		
		$message .= $hdr_msg;
					
		$sql  = " SELECT * FROM `sma_departure` where approval_ref_no = '$approval_ref_no' ";
//echo $sql;	
		$res2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$ln = 0;
		$tot_fare = 0;
		while($r1 	= mysqli_fetch_array($res2)){
			
			$invoice_no 	= $r1['invoice_no'];
			$start_date 	= date('d-m-Y', strtotime($r1['start_date']));
			if($start_date=='01-01-1970'){ $start_date='';}
			$start_place 	= $r1['start_place'];
			$start_time 	= $r1['start_time'];
			$end_date 		= date('d-m-Y', strtotime(r1));
			if($end_date=='01-01-1970'){ $end_date='';}
			$end_place 		= $r1['end_place'];
			$finish_time 	= $r1['finish_time'];
			$mode_of_travel	= $r1['mode_of_travel'];
			$spend_by		= $r1['spend_by'];
			$gst_flag		= $r1['gst_flag'];
			$fare 			= $r1['fare'];
										
										if($mode_of_travel =='Flight'){
											$mode_of_travel = 'Flight';
										}
										else if($mode_of_travel =='Bus'){
											$mode_of_travel = 'Bus';
										}
										else if($mode_of_travel =='Train'){
											$mode_of_travel = 'Train';
										}
										else if($mode_of_travel =='Car'){
											$mode_of_travel = 'Car';
										}
										
										if($spend_by =='C'){
											$spend_by = 'Company';
										}
										else if($spend_by =='O'){
											$spend_by = 'OWN';
										}
			if($ln==0){
				$message .= "<td>".$invoice_no."</td>
						<td>".$start_date ."</td>
						<td>".$start_place ."</td>
						<td>".$start_time."</td>
						<td>".$end_date ."</td>
						<td>".$end_place ."</td>
						<td>".$finish_time."</td>
						<td>".$mode_of_travel."</td>
						<td>".$spend_by ."</td>
						<td>".$fare ."</td>
						<td>".$gst_flag."</td></tr>";
				$ln = $ln + 1;		
			}
			else if($ln>0){
					$ln = $ln + 1;
				$message .= "<tr><td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>".$invoice_no."</td>
						<td>".$start_date ."</td>
						<td>".$start_place ."</td>
						<td>".$start_time."</td>
						<td>".$end_date ."</td>
						<td>".$end_place ."</td>
						<td>".$finish_time."</td>
						<td>".$mode_of_travel."</td>
						<td>".$spend_by ."</td>
						<td>".$fare ."</td>
						<td>".$gst_flag."</td>
						</tr>";
			}
			$tot_fare += $fare;
		}
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'><tr><td colspan='14' style='text-align:right;'> Total <td><b>$tot_fare</b></td></td><td ></td></tr>";
		
		
		
		$sql  = " SELECT * FROM `sma_expenses` where exp_type = 'T' and approval_ref_no = '$approval_ref_no' ";
//echo $sql;	
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$ln=0;
		$tot_amount = 0 ;
		while($r1 	= mysqli_fetch_array($res3)){
			
			$dated = date('d-m-Y', strtotime($r1['dated']));
			if($dated=='01-01-1970'){ $dated='';}
			$reference				= $r1['reference'];
			$reference_invoice_no 	= $r1['reference_invoice_no'];
			$amount 	= $r1['amount'];
			$note 		= $r1['note'];
			$gst_flag 	= $r1['gst_flag'];
			
			$sql="SELECT * from account_mst where id = '$reference'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$reference = $r2['account_name'];
			
			if($ln==0){
				$message1 .= $hdr_msg."<td>".$reference_invoice_no ."</td>
					<td>".$reference."</td>
					<td>".$dated ."</td>
					<td>".$amount ."</td>
					<td>".$note."</td>
					<td>".$gst_flag."</td></tr>";
			$ln = $ln + 1;		
			}
			else if($ln>0){
					$ln = $ln + 1;
					$message1 .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
					<tr><td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>".$reference_invoice_no."</td>
					<td>".$reference."</td>
					<td>".$dated ."</td>
					<td>".$amount ."</td>
					<td>".$note."</td>
					<td>".$gst_flag."</td></tr></table>";
			}		
			$tot_amount += $amount; 		
		}
		
		$message1 .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'><tr><td colspan='8' style='text-align:right;'>Total</td><td><b>$tot_amount</b></td><td></td></tr>";
	
	}
	
	$message .= "</table>";
	$message1 .= "</table>";

//echo $message1;
///exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		
		$fl_name1 = 'tr_export_trip_data.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name1");
		
		$fl_name = 'tr_export_exp_data.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
		print $message1;
		
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