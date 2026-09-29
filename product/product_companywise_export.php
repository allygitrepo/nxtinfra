<?php if($_GET['sub'] == 'list'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;font-family: Arial, Helvetica, sans-serif;'>
			<tr><td style='width: 80%;;'>Product Master  </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12pt;font-family: Arial, Helvetica, sans-serif;'>
			<tr>
				<th style='width: 5%;text-align: right;'>Sr.No.</th>
				<th style='width: 10%;text-align: left;'>Company</th>
				<th style='width: 10%;text-align: left;'>Product Name</th>
				<th style='width: 10%;text-align: left;'>Product Group</th>			
				<th style='width: 10%;text-align: left;'>Budget Group</th>
				<th style='width: 10%;text-align: left;'>Budget Sub Group</th>
				<th style='width: 10%;text-align: left;'>Posting A/c Name</th>
			</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 11pt;font-family: Arial, Helvetica, sans-serif;'>";
		$i =0;
		$sql ='';
		$sql="SELECT * from sma_product_cost_center where 1 order by company_id, product_id ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con,$sql);
		while($pcc = mysqli_fetch_array($res)){
			
			$product_id			= $pcc['product_id'];
			$company_id			= $pcc['company_id'];
			$budget_id			= $pcc['budget_id'];
			
			$sql="SELECT * from sma_product where 1 and id = '$product_id' ";			
//echo $sql. "<BR>";
			$result = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($result)){
				
				$name			= $row['name'];
				$product_group  = $row['product_group'];
				$sql= "SELECT * FROM sma_product_group where id = '$product_group' ";
				$q2 = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$product_group = $r2['product_group'];
				
				$sql = "select * from company where comp_id = '$company_id' ";
				$q22 = mysqli_query($con, $sql);
				$r22 = mysqli_fetch_array($q22);
				$comp_code =  $r22['comp_code'];
				
				$sql = "select b.name as 'budget_name', c.budget_head, c.budget_code 
					FROM sma_budget a, sma_budget_name b, sma_budget_subgroup c 
					WHERE a.id = '$budget_id' 
					AND a.budget_name = b.id
					AND a.budget_head = c.id 
					AND b.id = c.budget_name ";
//echo $sql. "<BR>";					
				$q22 = mysqli_query($con, $sql);
				$r22 = mysqli_fetch_array($q22);
				$budget_name =  $r22['budget_name'];
				$budget_head =  $r22['budget_head'];
				$budget_code =  $r22['budget_code'];
				
				$i = $i +1;	
				$message .= "<tr>
					<td style='width: 5%;text-align: right;'>".$i."</td>
					<td style='width: 10%'>".$comp_code."</td>
					<td style='width: 10%'>".$name."</td>
					<td style='width: 10%'>". $product_group."</td>
					<td style='width: 10%'>". $budget_name."</td>
					<td style='width: 10%'>". $budget_head."</td>
					<td style='width: 10%'>". $budget_code."</td>
					
					</tr>";
				
		}		
	
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
		Header("Content-Disposition: attachment; filename=product_companywise_master.xls");
		print $message;
		
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