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

	$message ='';
	
	$sql = "TRUNCATE budget_for_zoho ";
	mysqli_query($con,$sql);
	
	//where project in ($comid) 
	$sql 		= " SELECT * FROM sma_budget order by project, budget_name, budget_Category ";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$company_id				= $row['project'];
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_category'];
		$total_budget			= $row['total_budget'];
		$used_budget			= $row['used_budget'];
		$blocked_budget			= $row['blocked_budget'];
		$balance_budget			= $total_budget - ( $row['used_budget'] + $row['blocked_budget'] );
		$locked					= $row['locked'];
		$budget_status			= $row['budget_status'];
	
		$sql 		= "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 	= $com['comp_name'];

		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$budget_name	= $rw['name'];
		
		$sql 		= "SELECT * FROM `sma_budget_category` where id = '$budget_head'";
		$res    	= mysqli_query($con,$sql);
		$error  	= mysqli_error($con);
		$lc 		= mysqli_fetch_array($res);
		$budget_head    = $lc['category'];
		
		    $sql = "INSERT into budget_for_zoho (project, budget_name, budget_head, total_budget, used_budget, blocked_budget, balance_budget, budget_status ) values ('$comp_name','$budget_name', '$budget_head', '$total_budget', '$used_budget', '$blocked_budget', '$balance_budget', '$budget_status' ) ";
			mysqli_query($con,$sql);
			
	}
	
	
	
}