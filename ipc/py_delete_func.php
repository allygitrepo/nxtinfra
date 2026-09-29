<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "ipc/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$ipc_id  		= $_POST['ipc_id'];
				
		$sql = "UPDATE sma_ipc set del = 'Y' WHERE id = " .$ipc_id  ; 
		//and id not in ( SELECT approval_memo_ref FROM `sma_purchase_order` where approval_memo_ref = '$ipc_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rowaffect  = mysqli_affected_rows($con);
		
		if($rowaffect>0){
			$userid   	= $_SESSION['usrid'];
			$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks ) 
					values( 'IP', '$ipc_id', '$userid', now(), 'Deleted', '', '', '$remarks' )";				
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit(" Delete ...");}
		}
//exit();
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=list';
		echo "<script>window.location.href='$baseurl1';</script>";

?>		



