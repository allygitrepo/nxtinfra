
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
			<tr><th style='width: 100%;' colspan='15'> Budget List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<td style='width: 10%;'> Date </td>
					<td style='width: 10%;'> Company </td>
					<td style='width: 6%;'> Fin.Year </td>
					<td style='width: 10%;'> Budget Group </td>
					<td style='width: 10%;text-align: left;'> Budget Sub Group</td>
					<td style='width: 10%;text-align: left;'> Amount</td>
					<td style='width: 20%;'>Remarks</td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$sql 		= " SELECT fin_year, dated, project, budget_name, budget_head, effect, amount, remarks FROM `budget_adjust` order by project, dated ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$fin_year				= $row['fin_year'];
		$dated					= date('d-m-Y', strtotime($row['dated']));
		$company_id				= $row['project'];
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_head'];
		$effect					= $row['effect'];
		$amount					= $row['amount'];
		$remarks				= $row['remarks'];
		
	
		$sql 		= "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 	= $com['comp_name'];

		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$budget_name	= $rw['name'];
		
		$sql 		= "SELECT * FROM `sma_budget_subgroup` where id = '$budget_head'";
		$res    	= mysqli_query($con,$sql);
		$error  	= mysqli_error($con);
		$lc 		= mysqli_fetch_array($res);
		$budget_head    = $lc['budget_head'];
		
		if($effect =='I'){
			$effect = 'Add';
		}
		else if($effect =='D'){
			$effect = 'Less';
		}
		
		$message .= "<tr>
					
					<td>".$dated."</td>
					<td>".$comp_name."</td>
					<td>".$fin_year."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head ."</td>
					<td style='text-align:right;'>".$amount."</td>
					<td>".$remarks."</td>";
				
		}
	
	$message .= "</tr></table>";
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
		$fl_name = 'budget_adjust_export.xls';
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