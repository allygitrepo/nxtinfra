<?php
if($_GET['sub'] == 'exp'){

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
			<tr><th style='width: 100%;' colspan='4'> Account Master List </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 20%;'>Account</td>
					<td style='width: 8%;'>Account Group</td>
					<td style='width: 8%;'>Account Type</td>
					<td style='width: 08%;text-align: left;'> Vertical Type</td>
					
				</tr></table>";
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "account_mst";
	
	$sql 		= " SELECT * FROM $tableName order by account_name ";
	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$account_type = $row['account_type'];
		if($account_type =='A'){
			$account_type ='Purchase';
		}
		else if ($account_type =='B'){
			$account_type ='Cash';		
		}
		else if ($account_type =='D'){
			$account_type ='Deduction';		
		}
		else if ($account_type =='E'){
			$account_type ='Expense';		
		}
		$account_group = $row['account_group'];
		$sql = "SELECT * from sma_account_group where id = '$account_group' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$account_group = $r2['account_group'];
		
		$vertical_type = $row['vertical_type'];
		$sql = "SELECT * from sma_vertical where id = '$vertical_type' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$vertical_type = $r2['vertical_name'];
		
		$message .= "<tr>
						<td>".$row['account_name']."</td>
						<td>".$account_group."</td>
						<td>".$account_type."</td>
						<td>".$vertical_type ."</td>
					</tr>";
		}

	$message .= "</table>";
	
//	echo $message;
//	exit();
	
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


}