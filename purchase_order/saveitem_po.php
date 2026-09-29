
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
		
			$po_approval_hdr_id  		= $_POST['po_approval_hdr_id'];
			$approval_srno  		= $_POST['approval_srno'];
			$supplier_name			= $_POST['supplier_name'];
			
			if(empty($supplier_name)){
				echo "<script>alert('Vendor should be select...')</script>";
				$baseurl1 = $baseurl ."purchase_order/edit.php?sub=edit&id=$po_approval_hdr_id&active=active&555";
				echo "<script>window.location.href='$baseurl1';</script>";
			}	
			$quote_ref_no			= $_POST['quote_ref_no'];
			$vendor_selected		= $_POST['vendor_selected'];
			$values					= $_POST['values'];
			$remarks			    = $_POST['remarks'];
			
			if($vendor_selected=='Y'){
				
				$sql = "SELECT * FROM sma_po_approval_details WHERE po_approval_hdr_id = '$po_approval_hdr_id' AND approval_srno != '$approval_srno' ";
				$q2 =mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$vendor_selected = $r2['vendor_selected'];
				if($vendor_selected=='Y'){
					echo "<script>alert('Multiple vendor selection not allow !');</script>";
					$vendor_selected = '';
				}
				
			}
			
			if($vendor_selected=='Y'){
				$sql = "UPDATE sma_purchase_order SET to_supplier	= '$supplier_name' where id = '$po_approval_hdr_id'";
				mysqli_query($con, $sql);
			}
			
			$sql="update sma_po_approval_details set 
						supplier_name		= '$supplier_name',
						quote_ref_no		= '$quote_ref_no',
						vendor_selected		= '$vendor_selected',
						`values`			= '$values',
						remarks				= '$remarks'
					where po_approval_hdr_id = '$po_approval_hdr_id'
					  and approval_srno   = '$approval_srno'";
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			$baseurl1 = $baseurl ."purchase_order/edit.php?sub=edit&id=$po_approval_hdr_id&active=active&555";
			echo "<script>window.location.href='$baseurl1';</script>";
			
			exit();
			
	}

	if($_GET['sub']=='Save'){
		
			$po_approval_hdr_id  		= $_POST['po_approval_hdr_id'];
		//	$approval_srno  		= $_POST['approval_srno'];

			$supplier_name			= $_POST['supplier_name'];
			$quote_ref_no			= $_POST['quote_ref_no'];
			$vendor_selected		= $_POST['vendor_selected'];
			$values					= $_POST['values'];
			$remarks				= $_POST['remarks'];
			
			if($vendor_selected=='Y'){
				
				$sql = "SELECT * from sma_po_approval_details where  po_approval_hdr_id = '$po_approval_hdr_id' ";
				$q2 =mysqli_query($con, $sql);
				$rowaffect = mysqli_affected_rows($con);
				$r2 = mysqli_fetch_array($q2);
				$vendor_selected_v = $r2['vendor_selected'];
				if($vendor_selected_v=='Y' && $rowaffect>0){
					echo "<script>alert('Multiple vendor selection not allow !');</script>";
					$vendor_selected = '';
				}
				
			}
			
			$sql="insert into sma_po_approval_details (po_approval_hdr_id, supplier_name, quote_ref_no, vendor_selected, `values`, remarks ) 
					Values( '$po_approval_hdr_id', '$supplier_name', '$quote_ref_no', '$vendor_selected', '$values', '$remarks')";
			
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			if($vendor_selected=='Y'){
				$sql = "UPDATE sma_purchase_order SET to_supplier	= '$supplier_name' where id = '$po_approval_hdr_id'";
				mysqli_query($con, $sql);
			}
			
			
			$sql	="Select * from sma_purchase_order where id ='$po_approval_hdr_id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$company_id 		= $row['project'];
			$po_number 			= $row['po_number'];
			$subject 			= $row['subject'];
			$supplier_id		= $row['to_supplier'];
									
			$sql 	= "select * from sma_party_mst where id = '$supplier_name' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $subject. ','. $party_name;
		    $affect 		= 'Vendor Added ';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
//echo $sql;
//exit();		
			if(!empty($error)){echo $error; exit();}
				$baseurl1 = $baseurl ."purchase_order/edit.php?sub=edit&id=$po_approval_hdr_id";
				//echo "<script>window.location.href='$baseurl/purchase_order/edit.php?sub=edit&id=$po_approval_hdr_id&active=active&777';</script>";
				echo "<script>window.location.href='$baseurl1';</script>";
	}
				
?>		
	