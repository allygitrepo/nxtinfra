<?php
if($_GET['sub'] == 'exp'){

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
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='4'> Account Master List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 20%;'>Account</td>
					<td style='width: 8%;'>Account Group</td>
					<td style='width: 8%;'>Account Type</td>
					<td style='width: 08%;text-align: left;'> Vertical Type</td>
					
				</tr></table>";
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "account_mst";
	
	$sql 		= " SELECT * FROM $tableName order by account_name ";
	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$account_type = $row['account_type'];
		if($account_type =='A'){
			$account_type ='Purchase';
		}
		else if ($account_type =='B'){
			$account_type ='Cash';		
		}
		else if ($account_type =='D'){
			$account_type ='Deduction';		
		}
		else if ($account_type =='E'){
			$account_type ='Expense';		
		}
		$account_group = $row['account_group'];
		$sql = "SELECT * from sma_account_group where id = '$account_group' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$account_group = $r2['account_group'];
		
		$vertical_type = $row['vertical_type'];
		$sql = "SELECT * from sma_vertical where id = '$vertical_type' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$vertical_type = $r2['vertical_name'];
		
		$message .= "<tr>
						<td>".$row['account_name']."</td>
						<td>".$account_group."</td>
						<td>".$account_type."</td>
						<td>".$vertical_type ."</td>
					</tr>";
		}

	$message .= "</table>";
	
//	echo $message;
//	exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'user_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}


}
//Ruchi started
if($_GET['sub'] == 'accgrp'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width:20%;text-align: right;'>Account Group</th>
							</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		
		$sql="SELECT * from sma_account_group";

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
						
			$message .= "<tr>
			<td width=20%>".$row['account_group']."</td>
				
				</tr>";
	}
			
		$message .= "</table>";
	
//echo $message;

    ob_start();
    

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=Account Group.xls");
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

 if($_GET['sub'] == 'gst'){

	
	include("../dbcon.php");

	
	$prn='excel';
		
	$message = '';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>
			<tr>
				<th style='width:20%;text-align: right;'>TAX Description</th>
				<th style='width:20%;text-align: right;'>GST % Rate</th>
				<th style='width:20%;text-align: right;'>SGST Account Name</th>
				<th style='width:20%;text-align: right;'>CGST Account Name</th>
				<th style='width:20%;text-align: right;'>IGST Account Name</th>
				<th style='width:20%;text-align: right;'>Status</th>
							</tr>
			</table>";
		
		$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12px;'>";
		
		$sql="SELECT * from gst_mst";

		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
			$id = $row['id'];
			$sql1="Select * from gst_mst where id ='$id'";
		$query1 = mysqli_query($con, $sql1);
        $row1 = mysqli_fetch_array($query1);
			$sgst_account_id = $row1['sgst_account_id'];
			$cgst_account_id = $row1['cgst_account_id'];
			$igst_account_id = $row1['igst_account_id'];
			$sql2 = "select * from account_mst where id ='$sgst_account_id'";
			$query2 = mysqli_query($con, $sql2);
        	$row2 = mysqli_fetch_array($query2);

			$sql3 = "select * from account_mst where id ='$cgst_account_id'";
			$query3 = mysqli_query($con, $sql3);
        	$row3 = mysqli_fetch_array($query3);

			$sql4 = "select * from account_mst where id ='$igst_account_id'";
			$query4 = mysqli_query($con, $sql4);
        	$row4 = mysqli_fetch_array($query4);
			$status = $row1['status'];
								if($status=='Y' ){
									$selected = "Active";
								}
								else if($status=='N'){
									$selected = "Inactive";
								}			
			$message .= "<tr>
			<td width=20%>".$row['gst_name']."</td>
			<td width=20%>".$row['igst']."</td>
			<td width=20%>".$row2['account_name']."</td>
			<td width=20%>".$row3['account_name']."</td>
			<td width=20%>".$row4['account_name']."</td>
			<td width=20%>".$selected."</td>

				
				</tr>";
	}
			
		$message .= "</table>";
	
//echo $message;

    ob_start();
    

	if($prn=='excel'){
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=GST Master.xls");
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
 //ruchi ended
?>