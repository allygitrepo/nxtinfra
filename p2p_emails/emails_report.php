<?php
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

	$prn		= "excel";
	
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='6'> GRN Email Export </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<th>Date</th>
                                <th>From</th>
                                <th>Supplier</th>
                                <th>Subject</th>
                                <th>GRN / OpEx No.</th>
								<th>Created Date</th>
                                <th>Type</th>
								
								<th>Approval Date</th>
								
								<th>Payment SR.No.</th>
								<th>Created Date</th>
								<th>Approval Date</th>
								<th>Paid Date</th>
								<th>Utr.Number</th>
								
								<th>Pending PO</th>
								<th>Status</th>
				</tr></table>";
				
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;vertical-align: top;'>";
		$sql = " SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                            LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                            WHERE E.deleted=0 
                                            ORDER BY E.message_date DESC";
		$sql = $_SESSION['sqlqry'];	
//echo $sql;
//exit();
		
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$app_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['message_date']));
		$grn_no					= $row['grn_no'];
		$status_po					= $row['status'];
		
		$s2="SELECT * FROM sma_supplier_invoice where id = '$grn_no' ";
		$r3 = mysqli_query($con, $s2);
		$rw1 = mysqli_fetch_array($r3);
		$si_id 			= $rw1['id'];
		$suplier_name = $rw1['suplier_name'];
		$created_date = date('d-m-Y', strtotime($rw1['created_date']));
		$status		  = $rw1['status'];
		if($created_date=='01-01-1970'){
			$created_date ='';
		}
		
		$s2="SELECT * FROM workflow_history where doc_id = '$si_id' and doc_type = 'GR' order by id desc ";
		$r3 = mysqli_query($con, $s2);
		$rw1 = mysqli_fetch_array($r3);
		$grn_approve_date	= date('d-m-Y', strtotime($rw1['create_date']));
		if($grn_approve_date=='01-01-1970'){
			$grn_approve_date ='';
		}
		
		$s2="SELECT a.* FROM payment_header a, payment_details b where a.id = b.payment_hdr_id  and a.st_flag= 'S' and b.supp_id = '$si_id' ";
		$r3 = mysqli_query($con, $s2);
		echo mysqli_error($con);
		$rw1 = mysqli_fetch_array($r3);
		$py_id 			= $rw1['id'];
		$utr_no			= $rw1['utr_no'];
		$paid_date		= date('d-m-Y', strtotime($rw1['paid_date']));
		if($paid_date=='01-01-1970'){
			$paid_date ='';
		}
		$py_create_date	= date('d-m-Y', strtotime($rw1['draft_dated']));
		if($py_create_date=='01-01-1970'){
			$py_create_date ='';
		}
		
		$s2="SELECT * FROM workflow_history where doc_id = '$py_id' and doc_type = 'PY' order by id desc ";
		$r3 = mysqli_query($con, $s2);
		$rw1 = mysqli_fetch_array($r3);
		$py_approve_date	= date('d-m-Y', strtotime($rw1['create_date']));
		if($py_approve_date=='01-01-1970'){
			$py_approve_date ='';
		}
		
		$supplier_name = '';
		$s2="SELECT * FROM sma_party_mst where id = '$suplier_name' ";
		$r3 = mysqli_query($con, $s2);
		$rw1 = mysqli_fetch_array($r3);
		$supplier_name = $rw1['party_name'];
		
		$message .= "<tr>
					<td width='10%'>".(empty($row['message_date']) ? '': date('d-M-Y', strtotime($row['message_date'])))."</td>
					<td width='10%'>". (empty($row['from']) ? '': $row['from'])."</td>
                    <td width='10%'>".$supplier_name."</td>
                    <td width='10%'>". (empty($row['subject']) ? '': $row['subject'])."</td>
                    <td width='10%'>". (empty($row['grn_no']) ? '': $row['grn_no'])."</td>
					<td width='10%'>".$created_date."</td>
                    <td width='10%'>". (empty($row['grn_type']) ? '': $row['grn_type'])."</td>
					
					<td width='10%'>".$grn_approve_date."</td>
					
					<td width='10%'>".$py_create_date."</td>
					<td width='10%'>".$py_id."</td>
					<td width='10%'>".$py_approve_date."</td>
					<td width='10%'>".$paid_date."</td>
					<td width='10%'>".$utr_no."</td>
					
					<td width='10%'>".$status_po."</td>
					<td width='10%'>".$status."</td>
					</tr>";
					
	}
	
	$message .= "</tr></table>";
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
		$fl_name = 'grn_email_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	
