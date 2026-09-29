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
	$department = $_POST['department'];	
	$company_id = $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> NOA Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> Company </td>
					<td style='width: 6%;'> Department </td>
					<td style='width: 6%;'> Location </td>

					<td style='width: 06%;text-align: left;'> NOA No.</td>
					<td style='width: 10%;text-align: left;'> Dated </td>
					<td style='width: 10%;text-align: left;'> Completed Date </td>
					
					<td style='width: 08%;'>Account Year</td>
					<td style='width: 08%;text-align: right;'> Budgget Head</td>
					
					<td style='width: 25%;'> Subject</td>
					
					<td style='width: 08%;text-align: right;'> Supplier Name </td>
					
					<td style='width: 08%;text-align: right;'> Quoted Amount </td>
					<td style='width: 25%;text-align: left;'> Deviations From SOP </td>
					<td style='width: 25%;text-align: left;'> Status </td>
				</tr></table>";
				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;vertical-align: top;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_approval_memo";
	
	$sql 		= " SELECT * FROM $tableName where status = 'Completed' and dated >= '$from_date' and dated <= '$to_date' ";
	
	if (!empty($company_id)){
		$sql  .= " and project = '$company_id' ";
	}
	
	if (!empty($department)){
		$sql  .= " and department = '$department' ";
	}
	
	$sql = $_SESSION['sqlex']; //. " AND status in ('Submitted', 'Completed', 'Draft' ) "

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$app_id					= $row['id'];
		$ap_number				= $row['ap_number'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		//$account_year  			= $row['account_year'];
		$company_id				= $row['company'];
		$department				= $row['department'];
		$location				= $row['location'];
		$subject				= $row['subject'];
		$trans_type				= $row['trans_type'];
		$status                 = $row['status'];
		//$budget_head 			= $row['budget_head'];
		$budget_available		= $row['budget_available'];
		$background				= $row['background'];
		$scope_of_work			= $row['scope_of_work'];
		$deviations_from_sop	= $row['deviations_from_sop'];
		$important_terms_conditions		= $row['important_terms_conditions'];
		$additional_costs		= $row['additional_costs'];	
		$cost 					= $row['cost'];
	
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

		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$dep 	= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$workflow_type = $deps['workflow_type'];
		

		$sql = "SELECT * FROM `workflow_history` where doc_id = '$app_id' and doc_type = 'AP' and status = 'Approved' order by id desc ";
		$dep 		= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$completed_date = date('d-m-Y', strtotime($deps['approved_date']));
			
		$sql="SELECT * from sma_approval_items where approval_hdr_id = '$app_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$budget_id 		= $r2['budget_id'];
//echo $sql. "<BR>";			
		$sql="SELECT a.budget_head, a.budget_code, b.name as budget_name , a.account_year
				FROM sma_budget a, sma_budget_name b 
				WHERE a.id = '$budget_id' and a.budget_name = b.id ";
			
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$budget_name 		= $r2['budget_name'];
		$budget_head 		= $r2['budget_head'];
		$budget_code 		= $r2['budget_code'];
		$account_year 		= $r2['account_year'];
													
		$sql = " SELECT * FROM sma_budget_subgroup WHERE id = '$budget_head' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$budget_head 		= $r2['budget_head'];
		
		$sql 	= "SELECT * FROM sma_approval_details where 1 and vendor_selected = 'Y' and approval_hdr_id = '$app_id' ";
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
		
			$supplier_name		= $rw['supplier_name'];
			$quote_ref_no		= $rw['quote_ref_no'];
			$vendor_selected	= $rw['vendor_selected'];
			$values				= $rw['values'];

			$sql = "SELECT * FROM `sma_party_mst` where id = '$supplier_name' ";
			$dep 			= mysqli_query($con,$sql);
			$deps 			= mysqli_fetch_array($dep);
			$supplier_name  = $deps['party_name'];
			
			$vselected = '';
			if($vendor_selected=='Y'){
				$vselected = 'Selected';
			}

			++$i;
		
		}
		
			$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$department."</td>
					<td>".$loc_name."</td>
					<td>".$ap_number ."</td>
					<td>".$dated."</td>
					<td>".$completed_date."</td>
					
					<td>".$account_year."</td>
					<td>".$budget_head. ' ' .$budget_code."</td>
					<td>".$subject."</td>
					<td style='width: 25%;text-align: left;'> " . $supplier_name . " </td>
						
					<td style='width: 8%;text-align: right;'> ".$values." </td>
					<td style='width: 30%;text-align: left;word-wrap:break-word;'> ".$deviations_from_sop." </td>
					<td style='width: 8%;text-align: right;'> ".$status." </td>
					</tr>";
					
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
		$fl_name = 'NOA_report'.'_'.date('Y-m-d').'.xls';
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