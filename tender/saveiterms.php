
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
			$approval_srno  		= $_POST['approval_srno'];
			$supplier_id			= $_POST['supplier_id'];
			$remarks			    = $_POST['remarks'];
			
			$sql="update sma_tender_supplier set 
						supplier_id			= '$supplier_id',
						remarks				= '$remarks'
					where tender_hdr_id 	= '$tender_hdr_id'
					  and approval_srno   	= '$approval_srno'";
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
		
			$tender_hdr_id  		= $_POST['tender_hdr_id'];
			$supplier_id			= $_POST['supplier_id'];
			$remarks				= $_POST['remarks'];
			
			$sql = "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 = mysqli_query($con, $sql);
			$r2 		= mysqli_fetch_array($q2);
			$party_email 	= $r2['party_email'];
									
			$sql="insert into sma_tender_supplier (tender_hdr_id, supplier_id, email_id, remarks ) 
					Values( '$tender_hdr_id', '$supplier_id', '$party_email', '$remarks')";
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			if(!empty($error)){echo $error; exit();}
				$baseurl1 = $baseurl ."tender/edit.php?sub=edit&id=$tender_hdr_id";
				//echo "<script>window.location.href='$baseurl/tender/edit.php?sub=edit&id=$tender_hdr_id&active=active&777';</script>";
				echo "<script>window.location.href='$baseurl1';</script>";
	}
				
?>		
	