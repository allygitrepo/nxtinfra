<?php
ini_set('max_execution_time', 0);
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
			<tr><th style='width: 100%;' colspan='11'> GRN Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		

				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> GRN.SRNo. </td>
					<td style='width: 25%;'> Company </td>
					<td style='width: 10%;text-align: left;'> Supplier Name</td>
					<td style='width: 6%;'> Supplier Inv.No. </td>
					<td style='width: 06%;text-align: left;'> Created Date</td>
					<td style='width: 06%;text-align: left;'> Invoice Date</td>
					<td style='width: 6%;'> MRN.No. </td>
					<td style='width: 6%;'> PO.Ref.No. </td>
					<td style='width: 6%;'> PO Created Date </td>
					<td style='width: 6%;'> PO Approved Date </td>
					
					<td style='width: 06%;text-align: left;'> Due Date</td>
					<td style='width: 06%;text-align: left;'> Invoice Received Date</td>
					<td style='width: 08%;text-align: left;'> Approved Date </td>
					
					<td style='width: 25%;'> GRN No. </td>
					
					<td style='width: 25%;'> GRN Created Date </td>
					<td style='width: 25%;'> GRN Approved Date </td>
					<td style='width: 25%;'> Material Name </td>
					<td style='width: 25%;'> Description </td>
					<td style='width: 25%;'> Account Year </td>
					<td style='width: 25%;'> Budget Name </td>
					<td style='width: 25%;'> Budget Head </td>
					
					<td style='width: 6%;'> Unit </td>
					<td style='width: 08%;text-align: right;'> Qty. </td>
					<td style='width: 08%;text-align: right;'> Rate </td>
					<td style='width: 08%;text-align: right;'> GST. </td>
					<td style='width: 08%;text-align: right;'> Total. </td>
					<td style='width: 08%;text-align: right;'>  Created Date </td>
					<td style='width: 08%;text-align: left;'> Payment Approved Date </td>
					<td style='width: 10%;text-align: left;'> UTR.No. </td>
					<td style='width: 10%;text-align: left;'> Paid Date </td>
					<td style='width: 08%;text-align: left;'> Approved By </td>
					<th>Workflow Type</th>
					<th>Pending With</th>
					<th>Status</th>
					<th>Decision</th>
					<th>Narration</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
		$comid = $_SESSION['comid'];	
		$id				= $_GET['id'];
	
	$tableName	= "sma_supplier_invoice";
	
	$sql 		= " SELECT * FROM $tableName where invoice_date >= '$from_date' and invoice_date <= '$to_date' ";
	
	if (!empty($supplier_id)){
		$sql  .= " and suplier_name = '$supplier_id' ";
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
		/* if($status	!= 'Completed'){
			continue;
		} */
		
		$grn_date = date('d-m-Y', strtotime($row['grndraft_date']));
		if($grn_date=='01-01-1970'){
			$grn_date='';	
		}
			
		$approval_status	= $row['approval_status'];
		
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
					
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
		
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
		$si_id					= $row['id'];
		$created_date  			= date('d-m-Y', strtotime($row['created_date']));
		$invoice_date  			= date('d-m-Y', strtotime($row['invoice_date']));
		$due_date  				= date('d-m-Y', strtotime($row['due_date']));
		$invoice_received_date	= date('d-m-Y', strtotime($row['invoice_received_date']));
		
		if($due_date=='01-01-1970'){
			$due_date ='';	
		}
		if($invoice_received_date=='01-01-1970'){
			$invoice_received_date ='';	
		}
		
		$supplier_id			= $row['suplier_name'];
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
		$po_id 			= $row['our_po_ref_no'];
		$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$our_po_ref_no = $r2['po_number'];
		$po_date = date('d-m-Y', strtotime($r2['draft_date']));
		if($po_date=='01-01-1970'){
			$po_date='';	
		}
		
		$our_pr_no		= $row['our_pr_no'];
		$sql  = " SELECT * from sma_purchase_req where id = '$our_pr_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$pr_number		= $r1['pr_number'];
								
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'PO' and doc_id = '$po_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$po_approved_date 		= date('d-m-Y', strtotime($com['create_date']));
		if($po_approved_date=='01-01-1970'){
			$po_approved_date='';	
		}
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'GR' and doc_id = '$si_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$grn_approved_date 		= date('d-m-Y', strtotime($com['create_date']));
		if($grn_approved_date=='01-01-1970'){
			$grn_approved_date='';	
		}
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'SI' and doc_id = '$si_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 			= $com['create_by'];
		$approver_date 		= date('d-m-Y', strtotime($com['create_date']));
		if($approver_date=='01-01-1970'){
			$approver_date='';	
		}	
		
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

		$sql = "SELECT * FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id and a.st_flag = 'S' and b.supp_id = '$si_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		$com 		= mysqli_fetch_array($comresult);
		$utrno 			= '';
		$paid_date  	= '';
		if($row_affected>0){
			$py_id 			= $com['id'];
			$utrno 			= $com['utr_no'];
			$paid_date  	= date('d-m-Y', strtotime($com['paid_date']));
			$payment_created_date = date('d-m-Y', strtotime($com['draft_dated']));
		}
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'PY' and doc_id = '$py_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 			= $com['create_by'];
		$payment_approved_date	= date('d-m-Y', strtotime($com['create_date']));
		if($payment_approved_date=='01-01-1970'){
			$payment_approved_date='';	
		}
		
		$sql 	= "SELECT * FROM sma_supplier_invoice_details where qty > 0 and si_hdr_id = '$si_id'";
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
		
			$grn_no			= $rw['si_hdr_id'];
			
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
			$comp_code 			= $com['comp_code'];
				
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
		
			$sql = "SELECT * FROM `tally_journal_entry` where 1 and doc_type = 'SI' and doc_no = '$si_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$narration 	= $com['narration'];
		
			++$i;
			//if($i > 1){	
				$message .= "<tr>
						<td>".$si_id."</td>
						<td style='width: 25%;text-align: left;'> " . $comp_code . " </td>
						<td>".$supplier_name."</td>
						<td>".$supplier_invoice_no."</td>
						<td>".$created_date."</td>
						<td>".$invoice_date."</td>
						
						<td>".$pr_number ."</td>
						<td>".$our_po_ref_no ."</td>
						<td>".$po_date ."</td>
						<td>".$po_approved_date ."</td>
						<td>".$due_date."</td>
						<td>".$invoice_received_date."</td>
						<td style='width: 8%;text-align: left;'> ".$approver_date." </td>
						
						<td style='width: 25%;text-align: left;'> " . $grn_no . " </td>
						<td>".$grn_date ."</td>
						<td>".$grn_approved_date ."</td>
						<td style='width: 25%;text-align: left;'> " . $product_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $description . " </td>
						<td style='width: 25%;text-align: left;'> " . $account_year . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_name . " </td>
						<td style='width: 25%;text-align: left;'> " . $budget_head . " </td>
						
						<td style='width: 6%;text-align: left;'> " . $unit . " </td>
						<td style='width: 8%;text-align: right;'> ".$qty." </td>
						<td style='width: 8%;text-align: right;'> ".$unit_rate." </td>
						<td style='width: 8%;text-align: right;'> ".$gst." </td>
						<td style='width: 8%;text-align: right;'> ".$net_amt." </td>
						<td style='width: 8%;text-align: right;'> ".$payment_created_date." </td>
						<td style='width: 8%;text-align: right;'> ".$payment_approved_date." </td>
						<td style='width: 10%;text-align: left;'> ".$utrno." </td>
						<td style='width: 10%;text-align: left;'> ".$paid_date." </td>
						
						<td style='width: 8%;text-align: left;'> ".$approver_name." </td>
						<td style='width: 8%;text-align: left;'> ".$workflow_type." </td>
						<td>". $pending_by."</td>
						<td>". $status."</td>
						<td>". $approval_status."</td>
						<td>". $narration."</td>
					</tr>";
			
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