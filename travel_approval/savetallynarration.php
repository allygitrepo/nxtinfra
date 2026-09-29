<?php

require_once("../dbcon.php");
	
	$doc_type = $_POST["doc_type"];
	
	
	$sql = "UPDATE `tally_journal_entry` set " . $_POST["column"] . " = '" . $_POST["editval"] . "' WHERE doc_type ='$doc_type' " . 
	" and doc_no = " . $_POST["id"];
	$result = mysqli_query($con,$sql );
	$error = mysqli_error($con);
	
	$sql = "UPDATE `sma_travel_expenses` set tally_narration = '" . $_POST["editval"] . "' WHERE id  = " . $_POST["id"] ;
	$result = mysqli_query($con, $sql);
	$error = mysqli_error($con);

/* 	
$sql = "UPDATE `tally_journal_entry` set " . $_POST["column"] . " = '" . $_POST["editval"] . "' WHERE doc_type ='$doc_type'" . 
	" and doc_no = " . $_POST["id"] ;
$file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file); 

$sql = "UPDATE `sma_travel_expenses` set tally_narration = '" . $_POST["editval"] . "' WHERE id  = " . $_POST["id"] ;
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);  */

	//$val=$_POST["editval"];
	//if($val>00){echo "<h1>Value should not be more than 100!!!!!</h1><br>Please try again.";}

?>