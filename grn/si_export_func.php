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
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$supplier_id = $_POST['supplier_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> GRN SRN  Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		

				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> SI.No. </td>
					<td style='width: 10%;text-align: left;'> Supplier Name</td>
					<td style='width: 6%;'> Supplier Inv.No. </td>
					<td style='width: 06%;text-align: left;'> Invoice Date</td>
					<td style='width: 6%;'> PO.Ref.No. </td>
					<td style='width: 10%;text-align: left;'> credit Days </td>
					<td style='width: 06%;text-align: left;'> Due Date</td>
					
					<td style='width: 5%;text-align: left;'> Sr.No.</td>
					<td style='width: 25%;'> GRN No. </td>
					<td style='width: 25%;'> Material Name </td>
					<td style='width: 25%;'> Description </td>
					<td style='width: 25%;'> Account Year </td>
					<td style='width: 25%;'> Company </td>
					<td style='width: 25%;'> Budget Name </td>
					<td style='width: 25%;'> Budget Head </td>
					
					<td style='width: 6%;'> Unit </td>
					<td style='width: 08%;text-align: right;'> Qty. </td>
					<td style='width: 08%;text-align: right;'> Rate </td>
					<td style='width: 08%;text-align: right;'> GST. </td>
					<td style='width: 08%;text-align: right;'> Total. </td>
					<td style='width: 08%;text-align: left;'> Approved By </td>
					<th>Workflow Type</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
		$comid = $_SESSION['comid'];	
		$id				= $_GET['id'];
	
	$tableName	= "sma_grn_srn";
	
	$sql 		= " SELECT * FROM $tableName where invoice_date >= '$from_date' and invoice_date <= '$to_date' ";
	
	if (!empty($supplier_id)){
		$sql  .= " and supplier_name = '$supplier_id' ";
	}
	
	$sql = $_SESSION['sqlex'];
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$del					= $row['del'];
		$status					= $row['status'];
		
		if($del=='Y' ){
			continue;
		}
		if($status	!= 'Completed'){
			continue;
		}
		
		$si_id					= $row['id'];
		$invoice_date  			= date('d-m-Y', strtotime($row['invoice_date']));
		$due_date  				= date('d-m-Y', strtotime($row['due_date']));
		
		if($due_date=='01-01-1970'){
			$due_date ='';	
		}
		
		$supplier_id			= $row['supplier_name'];
		$supplier_invoice_no	= $row['supplier_invoice_no'];
		
		$credit_days	 		= $row['credit_days'];
		$trans_type	 			= $row['trans_type'];
		
		if($credit_days==0){
			$credit_days ='';	
		}	
		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$workflow_type 		= $com['workflow_type'];
		
		$sql = "SELECT * FROM `sma_party_mst` where id = '$supplier_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$supplier_name 		= $com['party_name'];
		
		$our_po_ref_no = $row['our_po_ref_no'];
		$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$our_po_ref_no = $r2['po_number'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'SI' and doc_id = '$si_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 		= $com['create_by'];
		
		$sql = "SELECT * FROM `sma_user` where id = '$create_by' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$approver_name 		= $com['username'];
		
		$transport_lr_no = $row['transport_lr_no'];
		$transport_name  = $row['transporter_name'];
		
		
		/* $message .= "<tr>
					<td>".$supplier_name."</td>
					<td>".$supplier_invoice_no."</td>
					<td>".$invoice_date."</td>
					<td>".$our_po_ref_no ."</td>
					<td>".$credit_days."</td>
					<td>".$due_date."</td>"; */

		$sql 	= "SELECT * FROM sma_grn_srn_details where qty > 0 and grn_srn_hdr_id = '$si_id'";
		if($user != 'Admin'){
			$sql .= " and company_id in ( $comid ) ";
		}
		
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		if($row_affected = 0){
			//$message ="</tr>";
			continue;
		}
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$purchase_id			= $rw['purchase_id'];
			$qty			= $rw['qty'];
			
			if($qty==0){
				continue;
			}
			
			$unit			= $rw['unit'];
			$description	= $rw['description'];
			
			$account_year	= $rw['account_year'];
			if($account_year == '1'){$account_year = '2017-2018'; }
			else if($account_year == '2'){$account_year = '2018-2019'; }
			else if($account_year == '3'){$account_year = '2019-2020'; }
			else if($account_year == '4'){$account_year = '2020-2021'; }
			else if($account_year == '5'){$account_year = '2021-2022'; }
			
			$company_id		= $rw['company_id'];
			$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 				= mysqli_fetch_array($comresult);
			$comp_name 			= $com['comp_name'];
				
			$budget_id	= $rw['budget_id'];
			$sql = "SELECT * FROM `sma_budget`  where id = '$budget_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['budget_name'];
			$budget_head		= $com['budget_head'];	
			$account_year		= $com['account_year'];	
			
			$sql = "SELECT * FROM `sma_budget_name`  where id = '$budget_name' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['name'];
			
			$sql = "SELECT * FROM `sma_budget_subgroup`  where id = '$budget_head' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_head 		= $com['budget_head'];
			
			$product_id = $rw['material_id'];
			$sql="Select * from sma_product where id = '$product_id'";
			$output = mysqli_query($con,$sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($output);

			$product_name = $r2['name'];
			$unit		  = $r2['uom'];
			$hsn_code	  = $r2['hsn_code'];
			
			$unit_rate		= round($rw['rate'],2);
			$gst			= $rw['gst'];
			
			$tot_qty		= $qty;
			$actual_amt     = $qty * $unit_rate;
			$total_amt		= $total_amt + $actual_amt;
			
			$net_amt  		= round($actual_amt + ($actual_amt * $gst / 100),0);
			
			$total_net_amt	= $total_net_amt + $net_amt;
		
			++$i;
			//if($i > 1){	
				$message .= "<tr>
						<td>".$si_id."</td>
						<td>".$supplier_name."</td>
						<td>".$supplier_invoice_no."</td>
						<td>".$invoice_date."</td>
						<td>".$our_po_ref_no ."</td>
						<td>".$credit_days."</td>
						<td>".$due_date."</td>
						<td style='width: 6%;text-align: Center;'> ".$i." </td>
						<td style='width: 25%;text-align: left;'> " . $purchase_id . " </td>
						<td style='width: 25%;text-align: left;'> " . $product_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $description . " </td>
						<td style='width: 25%;text-align: left;'> " . $account_year . " </td>
						<td style='width: 25%;text-align: left;'> " . $comp_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_head . " </td>
						
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$qty." </td>
						<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
						<td style='width: 8%;text-align: right;'> ".$gst." </td>
						<td style='width: 8%;text-align: right;'> ".$net_amt." </td>
						<td style='width: 8%;text-align: right;'> ".$approver_name." </td>
						<td style='width: 8%;text-align: left;'> ".$workflow_type." </td>
					</tr>";
			/* }
			else {
			$message .= "<td style='width: 6%;text-align: Center;'> ".$i." </td>
						<td style='width: 25%;text-align: left;'> " . $purchase_id . " </td>
						<td style='width: 25%;text-align: left;'> " . $product_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $description . " </td>
						<td style='width: 25%;text-align: left;'> " . $account_year . " </td>
						<td style='width: 25%;text-align: left;'> " . $comp_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_head . " </td>
						
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$qty." </td>
						<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
						<td style='width: 8%;text-align: right;'> ".$gst." </td>
						<td style='width: 8%;text-align: right;'> ".$net_amt." </td>
						<td style='width: 8%;text-align: right;'> ".$approver_name." </td>
						<td style='width: 8%;text-align: left;'> ".$workflow_type." </td>
						
				";
			} */
			
		}
		
	}
	
	$message .= "</tr></table>";
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'supplier_invoice.xls';
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
			$fl_name = 'supplier_invoice.pdf';
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