<?php
if($_GET['sub'] == 'pdf'){
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
			<tr><th style='width: 100%;' colspan='16'> Petty Cash Transaction List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 07%;'><b>Company</b></td>
					<td style='width: 10%;'><b>Location</b> </td>
					<td style='width: 10%;'><b>Date</b></td>
					<td style='width: 15%;'><b>Spend By</b></td>
					<td style='width: 08%;'><b>Trans.Type</b></td>
					<td style='width: 10%;'><b>Invoice No.</b></td>
					<td style='width: 15%;'><b>Expenses</b></td>
					<td style='width: 08%;'><b>Amount</b></td>
					<td style='width: 17%;'><b>Note</b></td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
	$sql = "SELECT a.id, a.company_id, a.location_id, a.dated, a.trans_type, a.total_amount, b.expense_id, b.invoice_no, b.amount, b.note, b.spend_by, b.paid_to  FROM `sma_pettycash` a , sma_pettycash_exp b where a.id = b.approval_ref_no and a.del != 'Y' ";
	$sql .= " order by a.company_id, a.location_id, a.id ";
//echo $sql."<BR>"; exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$dated 				= date('d-m-Y', strtotime($row['dated']));
		$company_id 		= $row['company_id'];
		$location_id		= $row['location_id'];
		$trans_type 		= $row['trans_type'];
		$total_amount 		= $row['total_amount'];
		$expense_id 		= $row['expense_id'];
		$invoice_no 		= $row['invoice_no'];
		$amount 			= $row['amount'];
		$note 				= $row['note'];
		$spend_by 			= $row['spend_by'];
		$paid_to 			= $row['paid_to'];
		
		$sql="SELECT * FROM company where comp_id = '$company_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$comp_name = $r2->comp_name;
		$comp_code = $r2->comp_code;
		
		$sql="SELECT * FROM sma_location where id = '$location_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$location_name = $r2->loc_name;
		
		if($paid_to !='O'){
			$sql="SELECT * FROM sma_user where id = '$spend_by' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$spend_by = $r2->username;
		}
		
		$sql="SELECT * FROM account_mst where id = '$expense_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$account_name = $r2->account_name;
		
		$message .= "<tr><td style='width: 07%;'>".$comp_code."</td>
						<td style='width: 10%;'>".$location_name."</td>
						<td style='width: 10%;'>".$dated ."</td>
						<td style='width: 15%;'>".$spend_by."</td>
						<td style='width: 08%;'>".$trans_type."</td>
						<td style='width: 10%;'>".$invoice_no."</td>
						<td style='width: 15%;'>".$account_name."</td>
						<td style='width: 08%;text-align: right;'>".bcadd($total_amount , 0,2)."</td>
						<td style='width: 17%;'>".$note."</td>
					</tr>";
			
		}
			
	
	$message .= "</table>";
/* 
echo $message;
exit();
 */	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'pettycash_trans_export.xls';
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

		