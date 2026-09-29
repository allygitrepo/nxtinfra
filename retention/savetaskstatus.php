<?php
session_start();
require_once("../dbcon.php");

	$user 		= $_SESSION['user'];
	$userid 	= $_SESSION['usrid'];
	$role 		= $_SESSION['role'];
	$status 	= $_POST["editval"];
	$task_id 	= $_POST["id"];
	$column_name = $_POST["column"];
	
	$sql = "select * from sma_pending_task where id = '$task_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$record_id		  	= $r2['id'];
	$payment_id	  		= $r2['payment_id'];
	$document_id  		= $r2['document_id'];
	$status_task  		= $r2['status_task'];
	//completed_on
	//completed_by
	if($status=='P' || $status_task =='P'){
		$sql = "UPDATE `sma_pending_task` set " . $_POST["column"] . " = '" . $_POST["editval"] . "', task_update_on = now(), updated_by = '$user' ," . " completed_on='', completed_by ='' WHERE id = " . $_POST["id"];
	}
	else if($status=='C' || $status=='S' || $status_task =='P' || $status_task =='S' ){
		$sql = "UPDATE `sma_pending_task` set " . $_POST["column"] . " = '" . $_POST["editval"] . "', completed_on = now(), completed_by = '$user' " . " WHERE id = " . $_POST["id"];
	}
	mysqli_query($con,$sql);
	$error  = mysqli_error($con);

 /*  	$file = fopen("ravitest.txt","w");
	fwrite($file,$sql);
	fclose($file); 
 */
	if( $column_name!='task_remark' ){
		$sql = " INSERT into task_workflow( doc_type, doc_id, payment_id, create_by, create_date, status )
			values ( '$document_id', '$task_id', '$payment_id', '$userid', now(), '$status' ) ";
		mysqli_query($con,$sql);
/* 	
		$file = fopen("ravitest.txt","a");
		fwrite($file,$sql);
		fclose($file);  */
	}
	
/* $sql = "UPDATE `sma_pending_task` set " . $_POST["column"] . " = '" . $_POST["editval"] . "', $task_update_on = now(), $col_name_by = '$user' " . " WHERE id = " . $_POST["id"] . PHP_EOL ;	
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);  */

	//$val=$_POST["editval"];
	//if($val>00){echo "<h1>Value should not be more than 100!!!!!</h1><br>Please try again.";}

?>