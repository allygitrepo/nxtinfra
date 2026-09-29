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
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$department = $_POST['department'];	
	$supplier_id= $_POST['supplier_id'];	
	$company_id= $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='15'> Budget List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'> Company </td>
					<td style='width: 6%;'> Cost Center Group </td>
					<td style='width: 06%;text-align: left;'> Cost Center Sub Group</td>
					<td style='width: 06%;text-align: left;'> Cost Center Code</td>
					<td style='width: 06%;text-align: left;'> Accounting Year</td>
					<td style='width: 10%;text-align: left;'> Opening Budget</td>
					
					<td style='width: 10%;text-align: left;'> April</td>
					<td style='width: 10%;text-align: left;'> May</td>
					<td style='width: 10%;text-align: left;'> June</td>
					<td style='width: 10%;text-align: left;'> July</td>
					<td style='width: 10%;text-align: left;'> August</td>
					<td style='width: 10%;text-align: left;'> September</td>
					<td style='width: 10%;text-align: left;'> October</td>
					<td style='width: 10%;text-align: left;'> November</td>
					<td style='width: 10%;text-align: left;'> December</td>
					<td style='width: 10%;text-align: left;'> January</td>
					<td style='width: 10%;text-align: left;'> February</td>
					<td style='width: 10%;text-align: left;'> March</td>
					
					<td style='width: 10%;'> Blocked Budget </td>
					<td style='width: 10%;'> Used Budget </td>
					<td style='width: 10%;text-align: left;'> Adjust Budget</td>
					
					<td style='width: 08%;'>Balance Budget</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_budget";
	
	$sql 		= " SELECT * FROM $tableName where project in ($comid) order by project, budget_name ";
//echo $sql; exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$project = $row['project'];
		$sql = "SELECT * from company where comp_id = '$project' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		
		$project = $r2['comp_name'];
		
		
			$april				= $row['april'];
			$may				= $row['may'];
			$june				= $row['june'];
			$july				= $row['july'];
			$august				= $row['august'];
			$september			= $row['september'];
			$october			= $row['october'];
			$november			= $row['november'];
			$december			= $row['december'];
			$january			= $row['january'];
			$february			= $row['february'];
			$march				= $row['march'];
		
		$budget_code 			= $row['budget_code'];		
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_head'];
		
		/* $budget_category = $row['budget_category'];
		$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);	
		$budget_head 			= $r2['category'];
		
		 */
		$account_year			= $r2['account_year'];
		
		$total_budget			= $row['total_budget'];
		$used_budget			= $row['used_budget'];
		$blocked_budget			= $row['blocked_budget'];
		$adjustment_budget		= $row['adjustment_budget'];
		$balance_budget			= ($total_budget + $adjustment_budget) - ( $row['used_budget'] + $row['blocked_budget'] );
		$locked					= $row['locked'];
		$budget_status			= $row['budget_status'];
	
		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$budget_name	= $rw['name'];
		
		if($budget_status =='D'){
			$budget_status = 'Draft';
		}
		else if($budget_status =='F'){
			$budget_status = 'Final';
		}
		
		$message .= "<tr>
					<td>".$project."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head ."</td>
					<td>".$budget_code ."</td>
					<td>".$account_year ."</td>
					<td>".$total_budget."</td>
					
					<td>".$april."</td>
					<td>".$may."</td>
					<td>".$june."</td>
					<td>".$july."</td>
					<td>".$august."</td>
					<td>".$september."</td>
					<td>".$october."</td>
					<td>".$november."</td>
					<td>".$december."</td>
					<td>".$january."</td>
					<td>".$february."</td>
					<td>".$march."</td>
					
					<td>".$blocked_budget ."</td>
					<td>".$used_budget ."</td>
					<td>".$adjustment_budget ."</td>
					<td>".$balance_budget."</td>
					";
		
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
		$fl_name = 'budget_export.xls';
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