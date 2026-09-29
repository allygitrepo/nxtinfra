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
		
	$tableName	= "sma_pettycash";
	$sql 		= " SELECT * FROM $tableName where id = '$id' ";
//echo $sql;
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$draft_by		= $row['draft_by'];
		$draft_time		= $row['draft_dated'];

		$company_id  = $row['company_id'];
		$location_id  = $row['location_id'];
		$sql  = "SELECT a.*, b.* from company a, sma_location b where a.comp_id = b.loc_comp_id and b.id = '$location_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		$loc_name 		= $r1['loc_name'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$approval_ref_no 	= $row['id'];
		$advance_amount 	= $row['advance_amount'];
		//$utr_no				= $row['utr_no'];
	
			$trans_type = $row['trans_type'];
			if($trans_type =='R'){
				$trans_type = 'Receipt';
			}
			else if($trans_type =='P'){
				$trans_type = 'Paid';
			}
			
	$message ='';	
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th style='width: 20%;'>Company</th>
					<td style='width: 30%;'>".$comp_name."</td>
					<th style='width: 20%;'>location</th>
					<td style='width: 30%;'>".$loc_name."</td>
					</tr></table>";
		
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th  style='width: 10%;'>Sr.No.</th>
					<td  style='width: 10%;'>".$id."</td>
					<th  style='width: 30%;'></th>
					<td style='width: 20%;'><b>Prepared Date</b>:".$dated ."</td>
					<td style='width: 30%;'></td>
					</tr></table>";
		

    $message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr><th style='text-align:left;width: 100%;'><b>Petty Cash</b></th></tr></table>";

	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
	$message .= "<tr>"."<th style='width: 5%;'>Sr.No.</th>
					<th style='width: 10%;'>Date</th>
					<th style='width: 25%;'>Expense Type</th>
					<th style='width: 20%;'>Paid To</th>
					<th style='width: 10%;'>Effect</th>
					<th style='width: 10%;text-align:right;'>Amount</th>
					<th style='width: 20%;'>Narrations</th>
					</tr></table>";
		//$message .= $hdr_msg;
	
		$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left; font-size: 10pt;'>";
					
		$sql  = " SELECT * FROM `sma_pettycash_exp` where  approval_ref_no = '$approval_ref_no' ";
//echo $sql;	
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$sr_no=0;
		$tot_amount = 0 ;
		while($row1 	= mysqli_fetch_array($res3)){
			
			
											
			$paid_to  	= $row1['paid_to'];
			$spend_by 	= $row1['spend_by'];
											
			if($paid_to=='V'){
				$sql = "select * from sma_party_mst where id = '$spend_by' ";
				$result = mysqli_query($con, $sql);
				$r = mysqli_fetch_object($result);
				$spend_by		= $r->party_name;
			}
			else if($paid_to=='U'){
				$sql = "select * from sma_user where id = '$spend_by' ";
				$result = mysqli_query($con, $sql);
				$r = mysqli_fetch_object($result);
				$spend_by		= $r->username;
			}
											
			$expense_id = $row1['expense_id'];
			$sql="SELECT * from account_mst where id = '$expense_id'";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$expense_id = $r2['account_name'];
										
			$amount  = $row1['amount'];
			$remarks = $row1['note'];
			
			$dated = date('d-m-Y', strtotime($row1['dated']));
			
			$sr_no = $sr_no + 1;
											
			$message .= "<tr><td style='width: 5%;text-align:right;'>".$sr_no."</td>
					<td style='width: 10%;'>".$dated ."</td>
					<td style='width: 25%;'>".$expense_id."</td>
					<td style='width: 20%;'>".$spend_by."</td>
					<td style='width: 10%;'>".$trans_type."</td>
					<td style='width: 10%;text-align:right;'>".$amount ."</td>
					<td style='width: 20%;text-align:left;'>".$remarks ."</td>
					</tr>";
					
			$tot_amount += $amount;
			
		}
			
		$message .= "</table>";	
		$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 12px;'><tr><th style='text-align:right;width: 70%;'> Total</th><td style='width: 10%;text-align:right;'><b>".number_format($tot_amount,2)."</b></td><td style='width: 20%'></td></tr>";

	}
		
	$message .= "</table>";

	
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'PC' and doc_id = '$id' and status = 'Approved' ";
		
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
		
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'PC' and doc_id = '$id' and status = 'Pending' ";
		//echo $sql;
		$res = mysqli_query($con, $sql);
		$rr  = mysqli_fetch_object($res);
		$draft_time	 	= date('d-m-Y H:i a', strtotime($draft_time));
		$draft_date	 	= date('d-m-Y', strtotime($rr->create_date));
		
	/* 	$sql  = "select * from sma_user where id  = '$draft_by' ";
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
	//	$draft_by		= $rr->username;

 */
		$message .= "<br><br>";
		
		if($approved_date=='01-01-1970'){$approved_time='';}
		
		$message .= "<table style='width: 100%;'> <tr>
					<td  style='width: 25%;'><b>Prepared By</b>:$draft_by</td>";
		$message .= "<td  style='width: 10%;'></td>";
		$message .= "<td  style='width: 25%;'><b>Approved By</b>: $approved_by</td>";
		$message .= "<td  style='width: 25%;'><b>Received By</b>:<br><br></td>";
		$message .= "</tr></table>";
		
		$message .= "<table style='width: 100%;'> <tr>
					<td  style='width: 25%;'>$draft_time</td>";
		$message .= "<td  style='width: 10%;'></td>";
		$message .= "<td style='width: 25%;' > $approved_time </td>";
		$message .= "<td  style='width: 25%;'>_______________________</td>";
		$message .= "</tr></table>";
		
		
		$message .=  "<br><h4> Documents</h4>";
	
	$modulePath = "petty/";
	$baseurl2  = $baseurl.$modulePath;
	
		$id		= $_GET['id'];
		$sql 	= "SELECT * FROM file_uploads where module = 'PC' and reference_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name = $row['file_name'];
		$file_path = $row['file_path'];
		$doc_type = $row['doc_type'];
		
		$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
		
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
	
	}
	
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
