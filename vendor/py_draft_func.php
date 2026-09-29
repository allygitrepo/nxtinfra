<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "vendor/vendor.php?sub=list";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$party_id  		= $_POST['party_id'];

        $sql 	= "UPDATE sma_party_mst set status = 'Draft', `approval_status` = '', 
					approver_1	= '', approver_2 = '',
					approver_1_status = '', approver_2_status = '', current_approver= '',
					party_kyc = 'N'
					WHERE id = '$party_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql. "<BR>";

		/* $sql 	= "select * from sma_user where userid = ( select draft_by from sma_party_mst where id = '$party_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id']; */
		
		$userid   	= $_SESSION['usrid'];
		$sql = "insert into kyc_upd_log ( party_id, create_by, created_on, status, party_kyc, remarks ) 
		values( '$party_id', '$userid', now(), 'Draft', 'N', '$remarks' )";		
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}
//echo $sql. "<BR>";
//exit();
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


