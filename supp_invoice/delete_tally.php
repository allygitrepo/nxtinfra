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
$record_id = $_GET['record_id'];

$modulePath = "supp_invoice/";

$sql = "select * from tally_journal_entry where record_id = '$record_id' ";
$q3 	= mysqli_query($con, $sql);
$r3 	= mysqli_fetch_array($q3);
$record_id		  	= $r3['record_id'];
$effect		  		= $r3['effect'];
$record_type  		= $r3['record_type'];
$doc_no		  		= $r3['doc_no'];
$account_id			= $r3['account_id'];
										
$sql = "DELETE from tally_journal_entry where record_id = " . $_GET['record_id'];
if(mysqli_query($con, $sql)){
			
			$sql = "SELECT * FROM account_mst where 1 and id = '$account_id' ";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3 	= mysqli_fetch_array($result);
			$retention_flag		= $r2['retention_flag'];
			$tds_percentage 	= $r3['percentage'];
			$account_type_a 	= $r3['account_type'];
			$deduction_from		= $r2['deduction_from'];
			
			$sql_retention = '';
			
		
		if( $amount > 0 && $effect =='Cr' ){
			$sql = " update tally_journal_entry set amount = amount + $amount $sql_retention where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id'";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

			if($retention_flag=='Y'){
				$sql_retention = " , retention_amount = retention_amount - '$amount', retention_flag = '' ";
			}
			$sql 	= " update sma_supplier_invoice set payable_amount = payable_amount + '$amount', bal_amount = bal_amount + '$amount' $sql_retention where id = '$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

		}
		else if( $amount > 0 && $effect =='Dr' ){
			$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			$sql 	= " update sma_supplier_invoice set payable_amount = payable_amount - '$amount', bal_amount = bal_amount - '$amount' where id = '$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}

		$sql= " select * from tally_journal_entry where effect='CR' and account_type in( 'V' ) and doc_type='SI' and doc_no='$doc_no' ";
//echo $sql."<BR>";		
		$qry = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 	= mysqli_fetch_array($qry);
		$amount = $r2['amount'];		
		$sql 	= " update sma_supplier_invoice set payable_amount =  '$amount', bal_amount = '$amount' where id = '$doc_no' ";
		mysqli_query($con, $sql);
//echo $sql."<BR>";
//echo $sql; exit();

	echo "<script type='text/javascript'> document.location = '" . $_GET['url'].'&IN=in' . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
		
/* 		$file = fopen("ravitest.txt","w");
		fwrite($file,$sql);
		fclose($file);
echo $sql; exit();
		
 */

?>