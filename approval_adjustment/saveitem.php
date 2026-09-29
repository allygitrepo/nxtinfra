
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
		
			$approval_hdr_id  		= $_POST['approval_hdr_id'];
			$approval_srno  		= $_POST['approval_srno'];
			$supplier_name			= $_POST['supplier_name'];
			$quote_ref_no			= $_POST['quote_ref_no'];
			$vendor_selected		= $_POST['vendor_selected'];
			$values					= $_POST['values'];
			$remarks			    = $_POST['remarks'];
			
			$sql="update sma_approval_details set 
						supplier_name		= '$supplier_name',
						quote_ref_no		= '$quote_ref_no',
						vendor_selected		= '$vendor_selected',
						`values`			= '$values',
						remarks				= '$remarks'
					where approval_hdr_id = '$approval_hdr_id'
					  and approval_srno   = '$approval_srno'";
//echo '';
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			$baseurl1 = $baseurl ."approval/edit.php?sub=edit&id=$approval_hdr_id&active=active&555";
			echo "<script>window.location.href='$baseurl1';</script>";
			//echo "<script>window.location.href='$baseurl/approval/edit.php?sub=edit&id=$approval_hdr_id';</script>";
				//echo "<script>window.location.href='purchase_requisition/edit.php?sub=edit&id=$approval_hdr_id';</script>";
			exit();
			
	}

	if($_GET['sub']=='Save'){
		
			$approval_hdr_id  		= $_POST['approval_hdr_id'];
		//	$approval_srno  		= $_POST['approval_srno'];

			$supplier_name			= $_POST['supplier_name'];
			$quote_ref_no			= $_POST['quote_ref_no'];
			$vendor_selected		= $_POST['vendor_selected'];
			$values					= $_POST['values'];
			$remarks				= $_POST['remarks'];
			
			$sql="insert into sma_approval_details (approval_hdr_id, supplier_name, quote_ref_no, vendor_selected, `values`, remarks ) 
					Values( '$approval_hdr_id', '$supplier_name', '$quote_ref_no', '$vendor_selected', '$values', '$remarks')";
			
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			if(!empty($error)){echo $error; exit();}
				$baseurl1 = $baseurl ."approval/edit.php?sub=edit&id=$approval_hdr_id&active=active&777";
				//echo "<script>window.location.href='$baseurl/approval/edit.php?sub=edit&id=$approval_hdr_id&active=active&777';</script>";
				echo "<script>window.location.href='$baseurl1';</script>";
	}
				
?>		
	