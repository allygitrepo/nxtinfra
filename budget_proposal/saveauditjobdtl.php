<?php

require_once("../dbcon.php");
	
	
	$editval  = str_replace(",", "", trim($_POST["editval"]));
	$err ='';
	$sql = "UPDATE `sma_budget_proposal_details` set " . $_POST["column"] . " = '" . $editval . "' WHERE hdr_id =" .  $_POST["hdr_id"] . " and id = " . $_POST["line_no"];
	
	mysqli_query($con, $sql);
	$err = mysqli_error($con);
 
/* $file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file); */
 
?>