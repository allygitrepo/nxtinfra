<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$modulePath = "income/";

	$user   = $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	
?>

<?php

	$income_hdr_id 		= $_GET['in_id'];
	
	$sql 	= "INSERT INTO `sma_income_hdr` ( `fin_year`, `mm_yyyy`, `dated`, `company_id`, `trans_type`, `provisional_account_name`, `gross_amount`, `status`,  `draft_by`, `draft_date`, `current_approver`) 
	SELECT `fin_year`, `mm_yyyy`, `dated`, `company_id`, `trans_type`, `provisional_account_name`, `gross_amount`, 'Draft', `draft_by`, now(), `current_approver`
	FROM sma_income_hdr 
		WHERE id = '$income_hdr_id' ";
	mysqli_query($con, $sql);
	$new_id = mysqli_insert_id($con);
	
	
	$sql 	= "INSERT INTO `sma_income_dtl` ( `income_hdr_id`, `account_id`, `effect`, `amount`, `narration`, `tally_entry_date`, `reversal_entry`) 
	SELECT  '$new_id', `account_id`, `effect`, `amount`, `narration`, `tally_entry_date`, `reversal_entry`
		FROM sma_income_dtl 
		WHERE income_hdr_id = '$income_hdr_id' ";
	mysqli_query($con, $sql);
//echo $sql ."<BR>";
//exit();	
	$sql = "SELECT * from sma_income_hdr where id = '$income_hdr_id' "; 
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$mm_yyyy 	= $r2['mm_yyyy'];
	$mmth  = substr($mm_yyyy,0,2);
	$myear = substr($mm_yyyy,3,4);
	
	if($mmth==12){
		$mmth = '01';
		$myear = $myear + 1;
	}
	else {
		$mmth  = $mmth + 1;
	}
	if($mmth=='04' || $mmth=='06' || $mmth=='09' || $mmth=='11' ){
		$dday = 30;
	}
    else if ($mmth=='01' || $mmth=='03' || $mmth=='05' || $mmth=='07' || $mmth=='08' || $mmth=='10' || $mmth=='12' ){
		$dday = 31;
	}
	else if ($mmth=='02'){
		$dday = 28;
	}
	
	$mm_yyyy = $mmth. '-'.$myear;
	$dated   = $myear .'-'.$mmth.'-'.$dday;
	$sql = "UPDATE sma_income_hdr set `mm_yyyy` = '$mm_yyyy' , `dated` = '$dated' WHERE id = '$new_id' ";
	mysqli_query($con, $sql);
	
	$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'IN', '$new_id', '$userid', now(), 'Draft' )";

	$query=mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
			
	$value = "<script>window.location.href='edit.php?sub=edit&id=$new_id&IN=in';</script>";
	echo $value;
		
?>

