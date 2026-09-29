
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

	$comid  = $_SESSION['comid'];

	$prn		= "excel";
	
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='10'> Budget Transfer List </th></tr></table>";		

	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'> Date </td>
					<td style='width: 10%;'> Company </td>
					<td style='width: 10%;'> Fin.Year </td>
					<td style='width: 10%;'> From Cost Center Group </td>
					<td style='width: 10%;text-align: left;'> From Cost Center Sub Group</td>
					<td style='width: 10%;'> To Cost Center Group </td>
					<td style='width: 10%;text-align: left;'> To Cost Center Sub Group</td>
					<td style='width: 10%;text-align: left;'> Amount</td>
					<td style='width: 10%;'>Remarks</td>
					<td style='width: 10%;'>Final Apporver Name</td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	//$sql 		= " SELECT * FROM `budget_adjust_from_to` order by project, dated ";
	
	$sql	= $_SESSION['sqlbd'];
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$btid 		= $row['id'];
		
		$dated		= date('d-m-Y', strtotime($row['dated']));
		$project = $row['project'];
		$sql = "select * from company where comp_id = '$project' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
										
		$budget_id_from = $row['budget_id_from'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_name_from = $r2['name'];
	
		$budget_id_to = $row['budget_id_to'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_name_to = $r2['name'];
		
		$budget_head_to = $row['budget_head_to'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_to = $r2['budget_head'];
		
		$budget_head_from = $row['budget_head_from'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_from' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_from = $r2['budget_head'];
	
		$account_year 		= $row['account_year'];
		
		$amount 			= $row['amount'];
		
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_id = '$btid' and doc_type = 'BT' 
					and status = 'Approved' ORDER BY id DESC ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$create_by 	= $r2->create_by;
		
		$sql = " select * from sma_user where id = '$create_by' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$final_approver 	= $r2->username;
								
		$message .= "<tr>
					<td style='width: 10%;'>".$dated."</td>
					<td style='width: 10%;'>".$comp_code."</td>
					<td style='width: 10%;'>".$account_year."</td>
					<td style='width: 10%;'>".$budget_name_from."</td>
					<td style='width: 10%;'>".$budget_head_from."</td>
					<td style='width: 10%;'>".$budget_name_to ."</td>
					<td style='width: 10%;'>".$budget_head_to."</td>
					<td style='text-align:right;width: 10%;'>".$amount."</td>
					<td style='width: 10%;'>".$remarks."</td>
					<td style='width: 10%;'>".$final_approver."</td>
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
	//$fl_name = 'poorder_'.$id;
    
//	if($prn=='excel'){
		$fl_name = 'budget_adjust_frto_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
//	}

    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'budget_export_frto.pdf';
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