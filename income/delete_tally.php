<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$amount =  $_GET['amount'];
$doc_no =  $_GET['doc_no'];
$doc_type = $_GET['doc_type'];
$effect 	= $_GET['effect'];

$record_id 	= $_GET['record_id'];

$modulePath = "income/";
$sql = "DELETE from tally_journal_entry where record_id = " . $_GET['record_id'];
echo $sql. "<BR>";
if(mysqli_query($con, $sql)){
		
	if($effect=='Cr'){	
		$sql = " update tally_journal_entry set amount = amount + $amount where 1 and account_type in ( 'S') and doc_type = '$doc_type' and doc_no = '$doc_no' and effect = '$effect' "; 
		mysqli_query($con, $sql);
		echo mysqli_error($con);
	}
	else if($effect=='Dr'){		
		$sql = " update tally_journal_entry set amount = amount + $amount where account_type = 'S' and doc_type = '$doc_type' and doc_no = '$doc_no' and effect = '$effect' "; //and account_type in ( 'V', 'U') ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
	}
	
		/* $sql= " select * from tally_journal_entry where effect='CR' and account_type in( 'S' ) and doc_type='$doc_type' and doc_no='$doc_no' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$amount = $r1['amount'];
			$sql = "UPDATE sma_travel_expenses SET total_amount = '$amount', bal_amount = 0 where id ='$doc_no' ";
			mysqli_query($con, $sql); */
//echo $sql. "<BR>";
//exit();
			
	echo "<script type='text/javascript'> document.location = '" . $_GET['url'] . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
?>