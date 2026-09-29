<?php

require_once("../dbcon.php");
	
	$err ='';
	$rate 		 = trim($_POST["editval"]);
	$column_name = $_POST["column"];
	
	$sqla = '';
	if($column_name=='gst'){
		$sql = "select * from gst_mst where 1 and igst = '$rate' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 = mysqli_fetch_array($q22);
		$gst_id = $r22['id'];
		$sqla   = " , gst_id = '$gst_id' ";
	}
	
	$sql = "UPDATE `sma_supplier_invoice_details` set " . $_POST["column"] . " = '" . $rate . "'". $sqla ." WHERE si_hdr_id =" .  $_POST["si_hdr_id"] . " and si_srno = " . $_POST["line_no"];
	
	mysqli_query($con, $sql);
	$err = mysqli_error($con);

/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file); 
*/

?>