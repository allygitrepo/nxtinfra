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
					<td style='width: 10%;'>Email ID </td>
					<td style='width: 8%;'>Department</td>
					<td style='width: 08%;text-align: left;'> Role</td>
					<td style='width: 20%;text-align: left;'> Company</td>
					
					<td style='width: 10%;'> Status </td>
					<td style='width: 10%;'> Created On </td>
					<td style='width: 10%;'> Bank Name </td>
					<td style='width: 10%;'> Account Type </td>
					<td style='width: 10%;'> Bank Address </td>
					<td style='width: 10%;'> Account Number </td>
					<td style='width: 10%;'> Account IFSC Code </td>
					
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
		$primary_role			= $row['primary_role'];
		$department				= $row['department'];
		$company_id				= $row['company_id'];
		$level_1				= $row['level_1'];
		$level_2				= $row['level_2'];
		$level_3				= $row['level_3'];
		$created_on				= date('d-m-Y', strtotime($row['created_on']));
		$party_bank_name		= $row['bank_name'];
		$party_bank_account_type= $row['bank_type'];
		$party_bank_address		= $row['bank_branch'];
		$party_bank_account_no	= $row['bank_ac_no'];
		$party_bank_ifsc_code	= $row['bank_ifsc'];
		$email              	= $row['email'];
		
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

		$sql 		= "SELECT * FROM `sma_role` where id = '$primary_role' ";
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
					<td style='vertical-align: top;'>".$username."</td>
					<td style='vertical-align: top;'>".$userid."</td>
					<td style='vertical-align: top;'>".$email."</td>
					<td style='vertical-align: top;'>".$department."</td>
					<td style='vertical-align: top;'>".$role ."</td>
					<td style='vertical-align: top;'>".$comp_name."</td>
				
					<td style='vertical-align: top;'>".$active."</td>
					<td style='vertical-align: top;'>".$created_on."</td>
					
					<td style='vertical-align: top;'>".$party_bank_name."</td>
					<td style='vertical-align: top;'>".$party_bank_account_type."</td>
					<td style='vertical-align: top;'>".$party_bank_address."</td>
					<td style='vertical-align: top;'>".$party_bank_account_no."</td>
					<td style='vertical-align: top;'>".$party_bank_ifsc_code."</td>
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