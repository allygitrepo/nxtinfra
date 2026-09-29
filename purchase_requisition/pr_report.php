<?php
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
			<tr><th style='width: 100%;' colspan='11'> Purchase Requisition Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'> Company </td>
					<td style='width: 10%;'> Location </td>
					<td style='width: 10%;'> Department </td>

					<td style='width: 10%;text-align: left;'> Srno.</td>
					<td style='width: 10%;text-align: left;'> PR.Number</td>
					<td style='width: 10%;text-align: left;'> Dated </td>
					<td style='width: 10%;text-align: left;'> Req.Dated </td>
					
					<td style='width: 10%;'>Delivery Loc.</td>
					<td style='width: 10%;text-align: right;'> Reason</td>
					<td style='width: 10%;text-align: right;'> Status</td>
					<td style='width: 10%;text-align: right;'> Pending With</td>
					
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_purchase_req";
	
	$sql 		= " SELECT * FROM $tableName where date >= '$from_date' and date <= '$to_date' ";
	
	if (!empty($company_id)){
		$sql  .= " and company_id = '$company_id' ";
	}
	
	if (!empty($department)){
		$sql  .= " and department_id = '$department' ";
	}
	
	$sql = $_SESSION['sqlpr'];
//	echo $sql;
//	exit();
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$pur_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['date']));
		$readate  				= date('d-m-Y', strtotime($row['reqDate']));
		$company_id				= $row['company_id'];
		$department				= $row['department_id'];
		$delivery_location_id	= $row['delivery_location_id'];

		$pr_number	 			= $row['pr_number'];
		$location	 			= $row['project_id'];
		$reason 				= $row['reason'];
		$status 				= $row['status'];
	
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
				$pending_by  = '';
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
				if(!empty($pending_by)){
    				$sql = "select * from sma_user where id = '$pending_by' ";
    				$q2  = mysqli_query($con, $sql);
    				$r2 = mysqli_fetch_array($q2);
    				$pending_by  = ' To '.$r2['username'];
				}
				
		$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$loc_name."</td>
					<td>".$department."</td>
					<td>".$pur_id ."</td>
					<td>".$pr_number ."</td>
					<td>".$dated."</td>
					<td>".$reqdate."</td>
					<td>".$delivery_location_id."</td>
					<td>".$reason."</td>
					<td>".$status."</td>
					<td>".$pending_by."</td>";
			
	}
	
	$message .= "</tr></table>";
	
//echo $message . "<BR>";
//exit();

    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
		$fl_name = 'pur_requisition_report.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
		
// 		$flname = 'pur_requisition.xls';
// 		$fp = fopen($flname, 'w');
// 		fwrite($fp,$message);
// 		fclose($fp);
		
// 		echo '<a href="'.$flname.'" target="_blank"> Process Done...Click here for download file</a>';
// 		echo '<br><br><br>';
// 		$baseurl1 = $baseurl."dashboard.php";
// 		echo "<a href='$baseurl1' > Go to Dashboard...Back</a>";


