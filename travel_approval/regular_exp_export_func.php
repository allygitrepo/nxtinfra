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
	$company_id = $_POST['company_id'];	
		
	$message  ='';
		
	$gtype		   = $_GET['gtype'];
		
	if($gtype =='R'){
		$d_type = 'Regular'	;
	}
	else if($gtype =='C'){
		$d_type = 'Company'	;
	}
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><th style='width: 100%;' colspan='11'> $d_type Expenses Register from ".$_POST['from_date']." TO ".$_POST['to_date']."</th></tr></table>";
	
	
	$head .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
				<tr><th>#No.</th>
					<th>Name</th>
					<th>Created Date</th>
					<th>Invoice Received Date</th>
					<th>App.Ref.No.</th>
					<th>Company</th>";
		
	$message .= $head."<th>Invoice No.</th>
					<th>Expenses</th>
					<th>Invoice Dated</th>
					<th>Amount</th>
					<th>Remarks</th>
					<th>GST</th>
					<th>Budget Name</th>
					<th>Budget Head</th>
					<th>Budget Amount</th>
					<th>UTR.No.</th>
					<th>Paid Date</th>
					<th>Approver Name</th>
					<th>Approver Date</th>
					<th>Workflow Type</th>
					<th>Pending With</th>
					<th>Status</th>
					<th>Decision</th>
					<th>Reason</th>
					</tr></table>";
					
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";

	
	$tableName	= "sma_travel_expenses";
	
//	$sql 		= " SELECT * FROM $tableName where exp_type = '$gtype' and dated >= '$from_date' and dated <= '$to_date' ";
	
/* 	if (!empty($company_id)){
		$sql  .= " and company_id = '$company_id' ";
	} */
	
	//if($gtype =='C'){
		$sql = $_SESSION['sqlreg'];
	//}
//echo $sql;
//exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$doc_id			 	= $row['id'];
		$emp_id 			= $row['emp_id'];
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		$gtype				= $row['exp_type'];
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
			
			
		if($gtype =='C'){
			$st_flag = 'C';
			$doc_type = 'CE';
		}
		else if($gtype =='R'){
			$st_flag = 'T';
			$doc_type = 'RE';
		}
		else if($gtype =='T'){
			$st_flag = 'T';
			$doc_type = 'TE';
		}
		
		$sql = "SELECT * FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id and a.st_flag = '$st_flag' and b.supp_id = '$doc_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		$com 		= mysqli_fetch_array($comresult);
		$utrno 		= '';
		$paid_date  	='';
		if($row_affected>0){
			$utrno 		= $com['utr_no'];
			$paid_date  	= date('d-m-Y', strtotime($com['paid_date']));
		}
		
		if($gtype =='C'){
			
			$sql = "select * from sma_party_mst where id = '$emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['party_name'];	
		}
		else {
			
			$sql = "select * from sma_user where id = '$onbehalf_emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['username'];
			
		}	
		
		$trans_type	 			= $row['trans_type'];
		
		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$workflow_type 		= $com['workflow_type'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		
		
		$invoice_received_date = date('d-m-Y', strtotime($row['invoice_received_date']));
		if($invoice_received_date=='01-01-1970'){ $invoice_received_date='';}
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$reason_travel 		= '';
		if($gtype =='T'){
			$approval_ref_no 	= $row['approval_ref_no'];
			$sql  = "SELECT * from sma_traval_approval where id = '$approval_ref_no' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 	= mysqli_fetch_array($res1);
			$reason_travel 		= $r1['purpose_visit'];
		}
		else {
			$approval_ref_no	= $row['approval_number'];
		}
		$idd			 	= $row['id'];
		$advance_amount 	= $row['advance_amount'];
		//$utr_no				= $row['utr_no'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = '$doc_type' and doc_id = '$idd' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 		= $com['create_by'];
		$approver_date 		= date('d-m-Y', strtotime($com['create_date']));
		if($approver_date=='01-01-1970'){
			$approver_date='';	
		}	
		
		$sql = "SELECT * FROM `sma_user` where id = '$create_by' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$approver_name 		= $com['username'];
		
		$hdr_msg = "<tr>
					<td>".$idd."</td>
					<td>".$emp_name."</td>
					<td>".$dated ."</td>
					<td>".$approval_ref_no ."</td>
					<td>".$comp_name."</td>";
		
		//$message .= $hdr_msg;
					
		$sql  = " SELECT * FROM `sma_expenses` where exp_type = '$gtype' and approval_ref_no = '$idd' ";
//echo $sql;	
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$ln=0;
		$tot_amount = 0 ;
		while($r1 	= mysqli_fetch_array($res3)){
			
			$dated = date('d-m-Y', strtotime($r1['dated']));
			if($dated=='01-01-1970'){ $dated='';}
			$reference				= $r1['reference'];
			$reference_id			= $r1['reference'];
			$reference_invoice_no 	= $r1['invoice_no'];
			$budget_id 	= $r1['budget_id'];
			$amount 	= $r1['amount'];
			$note 		= $r1['note'];
			$gst_flag 	= $r1['gst_flag'];
			
			$sql="SELECT * from sma_product where id = '$reference_id' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$reference = $r2['name'];
			
			$sql = " SELECT * FROM `sma_budget` where id = '$budget_id'  ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$budget_name  		= $r2['budget_name'];
			$budget_head	  	= $r2['budget_head'];
			$total_budget 		= $r2['total_budget'];
			
			$sql = "SELECT * FROM `sma_budget_name`  where id = '$budget_name' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
			//if($ln==0){
				
			$message .= $hdr_msg."<td>".$reference_invoice_no ."</td>
					<td>".$reference."</td>
					<td>".$dated ."</td>
					<td>".$invoice_received_date ."</td>
					
					<td>".$amount ."</td>
					<td>".$note."</td>
					<td>".$gst_flag."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head."</td>
					<td>".$total_budget."</td>
					<td>".$utrno."</td>
					<td>".$paid_date ."</td>
					
					<td>".$approver_name."</td>
					<td style='width: 8%;text-align: left;'> ".$approver_date." </td>
					<td> ".$workflow_type." </td>
					<td>". $pending_by."</td>
					<td>". $status."</td>
					<td>". $approval_status."</td>
					<td>". $reason_travel."</td>
					</tr>";
			$ln = $ln + 1;
			
			/* }
			else if($ln>0){
					$ln = $ln + 1;
					$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
					<tr>".$hdr_msg.
					"<td>".$reference_invoice_no."</td>
					<td>".$reference."</td>
					<td>".$dated ."</td>
					<td>".$amount ."</td>
					<td>".$note."</td>
					<td>".$gst_flag."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head."</td>
					<td>".$total_budget."</td>
					<td>".$approver_name."</td>
					<td> ".$workflow_type." </td>
					</tr></table>";
			} */		
			$tot_amount += $amount; 

		}
		
		/* if($tot_amount>0 && $ln>0){
			$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'><tr><td colspan='07' style='text-align:right;'> Total</td><td><b>$tot_amount</b></td><td></td><td></td><td></td></tr>";
			$tot_amount =0;
			$ln=0;
		} */
		
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
		
		$fl_name = 'regular_exp_export_data.xls';
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