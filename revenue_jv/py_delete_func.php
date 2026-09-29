<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	
	$modulePath = "revenue_jv/revenue_jv.php?sub=list";
?>

<?php

		$remarks 				= $_POST['remarks'];
		$revenue_hdr_id  		= $_POST['revenue_hdr_id'];

		$sql 	= "select * from sma_user where userid = ( select draft_by from p2p_revenue_hdr where id = '$revenue_hdr_id' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];

        $sql 	= "UPDATE p2p_revenue_hdr SET del = 'Y' where id = '$revenue_hdr_id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql. "<BR>";
		
		$userid   	= $_SESSION['usrid'];
		$sql = "INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
				VALUES( 'RV', '$revenue_hdr_id', '$userid', now(), 'Deleted', '$makerid', '', now(), '$remarks' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Draft ...");}

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";

?>		


