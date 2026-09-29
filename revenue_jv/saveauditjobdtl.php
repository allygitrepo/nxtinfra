<?php

require_once("../dbcon.php");
	
	$err ='';
	$sql = "UPDATE `p2p_revenue_data` set " . $_POST["column"] . " = '" . $_POST["editval"] . "' WHERE revenue_hdr_id =" .  $_POST["revenue_hdr_id"] . " and id = " . $_POST["line_no"];
	
	mysqli_query($con, $sql);
	$err = mysqli_error($con);

/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
 */

?>