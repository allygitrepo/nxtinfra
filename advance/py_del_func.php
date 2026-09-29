<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "advance/";	
	
		$remarks 		= $_POST['remarks'];
		$av_id  		= $_POST['av_id'];
		
		$payment_adjusted = 0;
	$sql = " SELECT a.st_flag, b.* FROM payment_header a, payment_details b 
				WHERE 1 and a.id = b.payment_hdr_id and a.st_flag = 'D' and b.supp_id = '$av_id' "; 
//echo $sql ."<BR>";			
	$q2	=	mysqli_query($con, $sql);
	while ($r2 =	mysqli_fetch_array($q2)){
		$payment_adjusted = $payment_adjusted + $r2['payment_adjusted'];
		$st_flag = $r2['st_flag'];
	}
	$sql = " select * from sma_advance where id = '$av_id' "; 
	$q2	=	mysqli_query($con, $sql);
	$r2 =	mysqli_fetch_array($q2);
	$company_id		 = $r2['company_id'];
	$remarks		 = $r2['remarks'];
		
	if($payment_adjusted>0){
		$sql="UPDATE sma_advance SET del = 'Y', paid_amount = paid_amount - '$payment_adjusted', paid_status = '' where id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql="UPDATE sma_advance SET paid_amount = 0 where 1 and paid_amount < 0 and id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
	}
	else if($payment_adjusted==0){
		$sql="UPDATE sma_advance SET del = 'Y', paid_status = '' where id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
	}		
//echo $sql ."<BR>";	
	$modulePath = 'advance/';
	$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date,remarks) 
				VALUES( 'AV', '$av_id', '$userid', now(), 'Deleted', '', now(), '$remarks' ) ";
	$query=mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
				
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $av_id. ','. $remarks;
		    $affect 		= 'Deleted';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);

//echo $sql ."<BR>";
			
//exit('####1');
				
	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";

?>		


