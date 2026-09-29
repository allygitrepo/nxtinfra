<?php
if($_GET['sub'] == 'pdf'){

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
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='7'> User List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 20%;'>User Name </td>
					<td style='width: 8%;'>User ID </td>
					<td style='width: 8%;'>Department</td>
					<td style='width: 08%;text-align: left;'> Role</td>
					<td style='width: 20%;text-align: left;'> Company</td>
					<td style='width: 10%;'> Category </td>
					<td style='width: 20%;'> Approver </td>
					<td style='width: 10%;'> Status </td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_user";
	
	$sql 		= " SELECT * FROM $tableName order by username ";
	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$username				= $row['username'];
		$userid					= $row['userid'];
		$user_category			= $row['user_category'];
		$role					= $row['role'];
		$department				= $row['department'];
		$company_id				= $row['company_id'];
		$level_1				= $row['level_1'];
		$level_2				= $row['level_2'];
		$level_3				= $row['level_3'];
		
		$active=$row['active'];
		if ($active=="1"){ 
			$active='Active';
		}
		else { 
			$active='Inactive';
		}

		$comp_name 	= '';
		$sql 		= "SELECT * FROM `company` where comp_id in ( $company_id ) ";
		$comresult 	= mysqli_query($con,$sql);
		while($com 		= mysqli_fetch_array($comresult)){
			$comp_name 	.= $com['comp_name']. '<BR>';
		}

		$approver_name 	= '';
		if(!empty($level_1)){
			$sql 		= "SELECT * FROM $tableName where id in ( $level_1 ) ";
			$comresult 	= mysqli_query($con,$sql);
			while($com 		= mysqli_fetch_array($comresult)){
				$approver_name 	= 'HOD-'.$com['username']. '<BR>';
			}
		}
		if(!empty($level_2)){
			$sql 		= "SELECT * FROM $tableName where id in ( $level_2 ) ";
			$comresult 	= mysqli_query($con,$sql);
			while($com 		= mysqli_fetch_array($comresult)){
				$approver_name 	.= 'HR-'.$com['username']. '<BR>';
			}
		}
		if(!empty($level_3)){
			$sql 		= "SELECT * FROM $tableName where id in ( $level_3 ) ";
			$comresult 	= mysqli_query($con,$sql);
			while($com 		= mysqli_fetch_array($comresult)){
				$approver_name 	.= 'Admin-'.$com['username'];
			}
		}
		
		$sql 		= "SELECT * FROM `sma_department` where id = '$department' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$department	= $com['name'];

		$sql 		= "SELECT * FROM `sma_role` where id = '$role' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$role		= $com['role'];

		if($user_category =='S'){
			$user_category = 'Site';
		}
		else if($user_category =='H'){
			$user_category = 'HO';
		}
		
		$message .= "<tr>
					<td>".$username."</td>
					<td>".$userid."</td>
					<td>".$department."</td>
					<td>".$role ."</td>
					<td>".$comp_name."</td>
					<td>".$user_category ."</td>
					<td>".$approver_name."</td>
					<td>".$active."</td>
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
    
	if($prn=='excel'){
		$fl_name = 'user_export.xls';
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
			$fl_name  = 'user_export.pdf';
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