<?php
if($_GET['sub'] == 'list'){

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
			<tr><th style='width: 100%;' colspan='15'> Workflow Config List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><th>Company</th>
					<th>Doc.Type</th>
					
					<th>From Value</th>
					<th>To Value</th>
					<th>Approver Level 1</th>
					<th>Approver Level 2</th>
					<th>Approver Level 3</th>
					<th>Approver Level 4</th>
					<th>Approver Level 5</th>
					<th>Approver Level 6</th>
					<th>Approver Level 7</th>
					<th>Approver Level 8</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	//$id				= $_GET['id'];
	
	$tableName	= "sma_workflow";
	
	$sql 		= " SELECT * FROM $tableName where 1 order by company_id, doc_type, trans_type ";
//echo $sql; exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$project 	= $row['company_id'];
		$sql 	= "SELECT * from company where comp_id = '$project' ";
		$res 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($res);
		$project = $r2['comp_name'];
		
		$doc_type = $row['doc_type'];
		if($doc_type == 'AP'){
			$doc_type = 'Approval';
		}
		else if($doc_type == 'PR'){
			$doc_type = 'Purchase Requisition';
		}
		else if($doc_type == 'PO'){
			$doc_type = 'Purchase Order';
		}
		else if($doc_type == 'SI'){
			$doc_type = 'Supplier Invoice';
		}
		else if($doc_type == 'PY'){
			$doc_type = 'Payment';
		}
		else if($doc_type == 'IP'){
			$doc_type = 'IPC';
		}
		else if($doc_type == 'CE'){
			$doc_type = 'Operating Expense';
		}
		else if($doc_type == 'TA'){
			$doc_type = 'Travel Request';
		}
		else if($doc_type == 'TR'){
			$doc_type = 'Travel Expenses';
		}
		else if($doc_type == 'BD'){
			$doc_type = 'Budget Adjustment';
		}
		
		$company_id = $row['company_id'];
		$sql="SELECT * from company where comp_id = '$company_id' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$company_name = $r1['comp_name'];
		$company_code = $r1['comp_code'];
		
		$trans_type = $row['trans_type'];
		$sql="SELECT * from sma_workflow_type where id = '$trans_type' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$workflow_type = $r1['workflow_type'];
		
		$approval_role_1 = $row['approval_role_1'];
		$sql="SELECT * from sma_role where id = '$approval_role_1' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_1 = $r1['role'];
		
		$approval_role_2 = $row['approval_role_2'];
		$sql="SELECT * from sma_role where id = '$approval_role_2' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_2 = $r1['role'];
		
		$approval_role_3 = $row['approval_role_3'];
		$sql="SELECT * from sma_role where id = '$approval_role_3' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_3 = $r1['role'];
		
		$approval_role_4 = $row['approval_role_4'];
		$sql="SELECT * from sma_role where id = '$approval_role_4' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_4 = $r1['role'];
		
		$approval_role_5 = $row['approval_role_5'];
		$sql="SELECT * from sma_role where id = '$approval_role_5' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_5 = $r1['role'];
		
		$approval_role_6 = $row['approval_role_6'];
		$sql="SELECT * from sma_role where id = '$approval_role_6' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_6 = $r1['role'];
		
		$approval_role_7 = $row['approval_role_7'];
		$sql="SELECT * from sma_role where id = '$approval_role_7' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_7 = $r1['role'];
		
		$approval_role_8 = $row['approval_role_8'];
		$sql="SELECT * from sma_role where id = '$approval_role_8' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$approval_role_8 = $r1['role'];
		
		$message .= "<tr>
					<td>".$project."</td>
					<td>".$doc_type."</td>
					
					<td>".$row['from_value'] ."</td>
					<td>".$row['to_value'] ."</td>
					<td>".$approval_role_1."</td>
					
					<td>".$approval_role_2."</td>
					<td>".$approval_role_3."</td>
					<td>".$approval_role_4."</td>
					<td>".$approval_role_5."</td>
					<td>".$approval_role_6."</td>
					<td>".$approval_role_7."</td>
					<td>".$approval_role_8."</td>
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
		$fl_name = 'Workflow_config_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}


}