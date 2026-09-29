
<?php
		$client  = $_SERVER['REMOTE_ADDR'];
//		if($client = '::1'){
//			$baseurl = "http://localhost:80/hc_template/";    //dev url
//		}
//		else {
//			$baseurl = "http://bidsdms.in/highwayc/";    //production url
//		}
//		//$baseurl = "http://bidsdms.in/highwayconcessions/";    //production url
		include("../baseurl.php");
		include("../dbcon.php");
		
	if($_GET['sub']=='Edit' or $_POST['sub1'] or $_POST['edit'] ){
		
			$tender_hdr_id  		= $_POST['tender_hdr_id'];
			$terms_srno  			= $_POST['terms_srno'];
			$terms_conditions		= $_POST['terms_conditions'];
			
			$sql = "UPDATE sma_tender_terms SET terms_conditions	= '$terms_conditions'
					WHERE tender_hdr_id 	= '$tender_hdr_id'
					  AND id   				= '$terms_srno' ";
//echo '';
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			$baseurl1 = $baseurl ."tender/edit.php?sub=edit&id=$tender_hdr_id&active=active&555";
			echo "<script>window.location.href='$baseurl1';</script>";
			
			exit();
			
	}

	if($_GET['sub']=='Save'){
		
			$tender_hdr_id  			= $_POST['tender_hdr_id'];
			$terms_conditions			= $_POST['terms_conditions'];
			
			$sql="insert into sma_tender_terms (tender_hdr_id, terms_conditions) 
					Values( '$tender_hdr_id', '$terms_conditions')";
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			if(!empty($error)){echo $error; exit();}
				$baseurl1 = $baseurl ."tender/edit.php?sub=edit&id=$tender_hdr_id";
				
				echo "<script>window.location.href='$baseurl1';</script>";
				exit();
				
	}
				
?>		
	