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
	$department = $_POST['department'];	
	$company_id = $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> Purchase Requisition Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> Company </td>
					<td style='width: 6%;'> Location </td>
					<td style='width: 6%;'> Department </td>

					<td style='width: 06%;text-align: left;'> PR.Number</td>
					<td style='width: 10%;text-align: left;'> Dated </td>
					<td style='width: 10%;text-align: left;'> Req.Dated </td>
					
					<td style='width: 08%;'>Delivery Loc.</td>
					<td style='width: 08%;text-align: right;'> Reason</td>
					
					<td style='width: 5%;text-align: left;'> Sr.No.</td>
					<td style='width: 25%;'> Description </td>
					<td style='width: 6%;'> Unit </td>
					<td style='width: 08%;text-align: right;'> Qty. </td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_purchase_req";
	
	$sql 		= " SELECT * FROM $tableName where date >= '$from_date' and date <= '$to_date' ";
	
	if (!empty($company_id)){
		$sql  .= " and company_id = '$company_id' ";
	}
	
	if (!empty($department)){
		$sql  .= " and department_id = '$department' ";
	}
	
//	echo $sql;
//	exit();
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$pur_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['date']));
		$readate  				= date('d-m-Y', strtotime($row['reqDate']));
		$company_id				= $row['company_id'];
		$department				= $row['department_id'];
		$delivery_location_id	= $row['delivery_location_id'];

		$location	 			= $row['project_id'];
		$reason 				= $row['reason'];
	
		$sql 	= "SELECT * FROM `sma_location` where id = '$location'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$lc 	= mysqli_fetch_array($res);
		$loc_name    = $lc['loc_name'];
		
		$sql = "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 		= $com['comp_name'];
		
		$sql = "SELECT * FROM `sma_department` where id = '$department' ";
		$dep 	= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$department = $deps['name'];
		
		$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$loc_name."</td>
					<td>".$department."</td>
					<td>".$pur_id ."</td>
					<td>".$dated."</td>
					<td>".$reqdate."</td>
					<td>".$delivery_location_id."</td>
					<td>".$reason."</td>";
				

		$sql 	= "SELECT * FROM sma_purchase_req_items where purchase_req_id = '$pur_id'";
	
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		if($row_affected = 0){
			$message ="</tr>";
			continue;
		}
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$quantity		= $rw['quantity'];
			$unit			= $rw['unit'];
			$description	= $rw['description'];
			

			++$i;
		if($i > 1){	
			$message .= "<tr><td colspan='08'>&nbsp;</td>
						<td style='width: 6%;text-align: Center;'> ".$i." </td>
						<td style='width: 25%;text-align: left;'> " . $description . " </td>
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$quantity." </td>
					</tr>";
			}
			else {
			$message .= "<td style='width: 6%;text-align: Center;'> ".$i." </td>
						<td style='width: 25%;text-align: left;'> " . $description . " </td>
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$quantity." </td>
				";
			}
		}
	}
	
	$message .= "</tr></table>";
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'pur_requisition.xls';
		//header("Content-type: application/xls");
		//header("Content-Type:'application/force-download'");
		//Header("Content-Disposition: attachment; filename=$fl_name");
	
		//print $message;
		
		$flname = 'pur_requisition.xls';
		$fp = fopen($flname, 'w');
		fwrite($fp,$message);
		fclose($fp);
		
		echo '<a href="'.$flname.'" target="_blank"> Process Done...Click here for download file</a>';
		echo '<br><br><br>';
		$baseurl1 = $baseurl."dashboard.php";
		echo "<a href='$baseurl1' > Go to Dashboard...Back</a>";
		
	}

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'pur_requisition.pdf';
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