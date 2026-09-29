<?php

require_once("../dbcon.php");
	
	$err ='';
	$sql = "UPDATE `audit_job_details` set " . $_POST["column"] . " = '" . $_POST["editval"] . "' WHERE audit_job_hdr_id =" .  $_POST["audit_job_hdr_id"] . " and id = " . $_POST["line_no"];
	
	mysqli_query($con, $sql);
	$err = mysqli_error($con);

/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
 */

?>