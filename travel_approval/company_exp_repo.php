<?php
if($_GET['sub'] == 'pdf'){

//	include("../header.php");
	include "../dbcon.php";
	include "../baseurl.php";
?>

		
<?php
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

	$prn	= "pdf";
	$id  	= $_POST['id'];
		
	$message  ='';
	
	$message .= "<table border='.2' cellspacing='0' style='width: 90%; text-align: center; font-size: 12px;'>";
	
	
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;'>
			<tr><th style='width: 100%;' colspan='11'> Regular Expenses </th></tr></table>";
				
	$id			= $_GET['id'];
		
	$tableName	= "sma_travel_expenses";
	$sql 		= " SELECT * FROM $tableName where exp_type = 'C' and id = '$id' ";
//echo $sql;
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$emp_id = $row['emp_id'];
		$sql = "select * from sma_party_mst where id = '$emp_id' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$emp_name = $r2['party_name'];
		$level_id = $r2['level_id'];
		$emp_code = $r2['roll_no'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$approval_ref_no 	= $row['id'];
		$advance_amount 	= $row['advance_amount'];
		//$utr_no				= $row['utr_no'];
	
	$message ='';	
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th style='width: 20%;'>Company</th>
					<td style='width: 80%;'>".$comp_name."</td>
					</tr></table>";
		
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th  style='width: 10%;'>Vendor Name</th>
					<td  style='width: 30%;'>".$emp_name."</td>
					<th  style='width: 10%;'></th>
					<td style='width: 10%;'></td>
					<th style='width: 10%;'></th>
					<td style='width: 10%;'></td>
					<td style='width: 20%;'><b>Submited Date</b>:".$dated ."</td>
					</tr></table>";
		

    $message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th style='text-align:right;width: 100%;'>Expenses</th></tr></table>";

	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr>"."<th style='width: 5%;'>Sr.No.</th>
					<th style='width: 10%;'>Dated</th>
					<th style='width: 25%;'>Particular</th>
					<th style='width: 30%;'>Remarks</th>
					<th style='width: 10%;'>Invoice No.</th>
					<th style='width: 10%;'>Invoice Date</th>
					<th style='width: 10%;text-align:right;'>Amount</th>
					</tr></table>";

		//$message .= $hdr_msg;
	
		$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
					
		$sql  = " SELECT * FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$approval_ref_no' ";
//echo $sql;	
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$sr_no=0;
		$tot_amount = 0 ;
		while($r1 	= mysqli_fetch_array($res3)){
			
			$dated = date('d-m-Y', strtotime($r1['dated']));
			if($dated=='01-01-1970'){ $dated='';}
			$reference				= $r1['reference'];
			$invoice_no 	= $r1['invoice_no'];
			$amount 	= $r1['amount'];
			$note 		= $r1['note'];
			$gst_flag 	= $r1['gst_flag'];
			
			$sql="SELECT * from account_mst where id = '$reference'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$reference = $r2['account_name'];
			
			$sr_no = $sr_no + 1;
			
			$message .= "<tr><td style='width: 5%;text-align:right;'>".$sr_no."</td>
					<td style='width: 10%;'>".$dated ."</td>
					<td style='width: 25%;'>".$reference."</td>
					<td style='width: 30%;'>".$note."</td>
					<td style='width: 10%;'>".$invoice_no."</td>
					<td style='width: 10%;'>".$dated."</td>
					<td style='width: 10%;text-align:right;'>".$amount ."</td>
					</tr>";
					
			$tot_amount += $amount;
			
		}
			
		$message .= "</table>";	
		$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 12px;'><tr><th style='text-align:right;width: 90%;'> Total</th><td style='width: 10%;text-align:right;'><b>$tot_amount</b></td></tr>";

	}
	
	$message .= "</table>";

	$message .=  "<h4> Documents</h4>";
	
	$modulePath = "travel_approval/";
	$baseurl2  = $baseurl.$modulePath;
	
		$id		= $_GET['id'];
		$sql 	= "SELECT * FROM file_uploads where module = 'CE' and reference_id = '$id'";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name = $row['file_name'];
		$file_path = $row['file_path'];
		$doc_type = $row['doc_type'];
		$dms_module = $row['dms_module'];
		$doc_invoice_no = $row['doc_invoice_no'];
		
		if($dms_module=='IN'){
			$baseurl1 = $baseurl.'dms/'.$file_path.'/'.$file_name;
		}
		else {
			
			$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
			
		}	
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
	
	}
	
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'CE' and doc_id = '$id' and status = 'Approved' ";
		
		$res = mysqli_query($con, $sql);
		$rr  = mysqli_fetch_object($res);
		$create_by			= $rr->create_by;
		$approved_time		= $rr->create_date;
		$approved_time	 	= date('d-m-Y H:i a', strtotime($approved_time));
		$approved_date	 	= date('d-m-Y', strtotime($rr->create_date));
		
		$sql  = "select * from sma_user where id  = '$create_by' ";
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$approved_by		= $rr->username;
		
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'CE' and doc_id = '$id' and status = 'Pending' ";
		//echo $sql;
		$res = mysqli_query($con, $sql);
		$rr  = mysqli_fetch_object($res);
		$draft_by			= $rr->create_by;
		$draft_time		= $rr->create_date;
		$draft_time	 	= date('d-m-Y H:i a', strtotime($draft_time));
		$draft_date	 	= date('d-m-Y', strtotime($rr->create_date));
		
		$sql  = "select * from sma_user where id  = '$draft_by' ";
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$draft_by		= $rr->username;


		$message .= "<br><br>";
		
		if($approved_date=='01-01-1970'){$approved_time='';}
		
		$message .= "<table style='width: 100%;'> <tr><td  style='width: 30%;'><b>Prepared By</b>:$draft_by</td>";
		$message .= "<td  style='width: 10%;'></td>";
		$message .= "<td  style='width: 40%;'><b>Approved By</b>: $approved_by</td>";
		$message .= "</tr></table>";
		
		$message .= "<table style='width: 100%;'> <tr><td  style='width: 30%;'>$draft_time</td>";
		$message .= "<td  style='width: 10%;'></td>";
		$message .= "<td style='width: 40%;' > $approved_time </td></tr></table>";
		
		
echo $message;
exit();
	
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
