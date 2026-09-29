<?php if($_GET['sub'] == 'list'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;font-family: Arial, Helvetica, sans-serif;'>
			<tr><td style='width: 80%;;'>Product Master  </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12pt;font-family: Arial, Helvetica, sans-serif;'>
			<tr>
				<th style='width: 5%;text-align: right;'>Sr.No.</th>
				<th style='width: 8%;text-align: left;'>Product Name</th>
				<th style='width: 10%;text-align: left;'>Product Group</th>
				<th style='width: 10%;text-align: left;'>Budget Name</th>
				<th style='width: 10%;text-align: left;'>Budget Head</th>
				<th style='width: 10%;text-align: left;'>PO Threashold</th>
				<th style='width: 10%;text-align: left;'>Category</th>
				<th style='width: 10%;text-align: left;'>Tolarance Level</th>
				<th style='width: 10%;text-align: left;'>GST Type</th>
				<th style='width: 10%;text-align: left;'>UOM</th>
				<th style='width: 8%;text-align: left;'>HSN Code</th>
				
			</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12pt;font-family: Arial, Helvetica, sans-serif;'>";
		$i =0;
		$sql ='';
		$sql="SELECT * from sma_product where 1 order by vertical_type, product_group, category, name ";

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$name			= $row['name'];
			$group			= $row['product_group'];
			$po_threashold	= $row['po_threashold'];
			$category		= $row['category'];
			$uom			= $row['uom'];
			$tolerance_level= $row['tolerance_level'];
			$gst_type		= $row['gst_type'];
			$hsn_code		= $row['hsn_code'];
			$exp_flag		= $row['exp_flag'];
			$budget_head		= $row['budget_head'];
			$budget_name		= $row['budget_name'];
			
			$sql= "SELECT * FROM sma_budget_name where id = '$budget_name' ";
    		$q2 = mysqli_query($con, $sql);
    		$r2 = mysqli_fetch_array($q2);
    		$budget_name = $r2['name'];
		
    		$sql= "SELECT * FROM sma_budget_subgroup where id = '$budget_head' ";
    		$q2 = mysqli_query($con, $sql);
    		$r2 = mysqli_fetch_array($q2);
    		$budget_head = $r2['budget_head'];
		
			if($exp_flag=='Y'){
				$exp_flag = 'Yes';	
			}	
			
			if($category=='M'){
				$category = 'Material';
			}
			else if($category=='S'){
				$category = 'Service';
			}
			
			if($po_threashold=='Q'){
				$po_threashold = 'Qty';
			}
			else if($po_threashold=='V'){
				$po_threashold='Value';
			}	
			
			$product_group = $row['product_group'];
			$sql= "SELECT * FROM sma_product_group where id = '$product_group' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$product_group = $r2['product_group'];
			
			$sql = "select * from gst_mst where id = '$gst_type' ";
			$q22 = mysqli_query($con, $sql);
			$r22 = mysqli_fetch_array($q22);
			$gst_name =  $r22['gst_name'];
			
			$i = $i +1;	
			$message .= "<tr>
				<td style='width: 5%;text-align: right;'>".$i."</td>
				<td style='width: 25%'>".$name."</td>
				<td style='width: 15%'>". $product_group."</td>
				<td style='width: 15%'>". $budget_name."</td>
				<td style='width: 15%'>". $budget_head."</td>
				<td style='width:10%' >". $po_threashold."</td>
				<td style='width:10%' >". $category."</td>
				<td style='width:10%' >". $tolerance_level."</td>
				<td style='width:10%' >". $gst_name."</td>
				<td style='width:10%;text-align:left'>". $uom."</td>
				<td style='width:10%' >". $hsn_code."</td>	
				
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

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=product_master.xls");
		print $message;
		
		/* $flname = 'product_master.xls';
		$fp = fopen($flname, 'w');
		fwrite($fp,$message);
		fclose($fp);
		 */
		/* echo '<a href="'.$flname.'" target="_blank"> Process Done...Click here for download file</a>';
		echo '<br><br><br>';
		$baseurl1 = $baseurl."dashboard.php";
		echo "<a href='$baseurl1' > Go to Dashboard...Back</a>"; */
		
	}
	
    // convert to PDF
	if($prn == 'pdf'){
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output('vendor_master.pdf');
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
 }
 
 //Ruchi started
 if($_GET['sub'] == 'progrp'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
		
	$message.= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width:20%;text-align: right;'>Product Group</th>
							</tr>
			</table>";
		
		$message.= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		
		$sql="SELECT * from sma_product_group";

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$message.= "<tr>
				<td width=20%>".$row['product_group']."</td>
				
				</tr>";
	}
			
		$message.= "</table>";
	
	//echo $message;

    ob_start();
    

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=Product Group.xls");
		print $message;
		
		
		
	}
	
    // convert to PDF
	if($prn == 'pdf'){
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output('vendor_master.pdf');
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
 }
 
 if($_GET['sub'] == 'unit'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width:20%;text-align: right;'>Units</th>
							</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		
		$sql="SELECT * from sma_units";

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$message .= "<tr>
			<td width=20%>".$row['name']."</td>
				
				</tr>";
	}
			
		$message .= "</table>";
	
//echo $message;

    ob_start();
    

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=Unit.xls");
		print $message;
		
		
		
	}
	
    // convert to PDF
	if($prn == 'pdf'){
		require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output('vendor_master.pdf');
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
 }
 ?>
<!-- Ruchi ended-->