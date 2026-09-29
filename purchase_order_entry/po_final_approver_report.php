<?php
//echo $_GET['sub'] ."<BR>";
if($_GET['sub'] == 'pdf'){

	session_start();

	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";

	$prn		= "excel";
	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
	$department = $_POST['department'];	
	$supplier_id= $_POST['supplier_id'];	
	$company_id= $_POST['company_id'];	
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='15'> Purchase Order Register from ". $_POST['from_date'] . " TO ". $_POST['to_date'] . "</th></tr></table>";		

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 6%;'>Company </td>
					<td style='width: 6%;'>Location </td>
					<td style='width: 6%;'>Department </td>

					<td style='width: 06%;text-align: left;'>PO.Number</td>
					<td style='width: 06%;text-align: left;'>Doc. ID</td>
					<td style='width: 10%;text-align: left;'>Dated </td>
					<td style='width: 25%;'>Supplier </td>
					
					<td style='width: 08%;text-align: right;'>Total Amt. </td>
					
					<td style='width: 08%;text-align: left;'>CC Group</td>
					<td style='width: 08%;text-align: left;'>CC Sub Group</td>
					
					<td style='width: 08%;text-align: left;'>Final Approver Date</td>
					<td style='width: 08%;text-align: left;'>Final Approver</td>
					<td style='width: 08%;text-align: left;'>Final Status</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	$comid = $_SESSION['comid'];	
	
	$tableName	= "sma_purchase_order";
	
	$sql 		= " SELECT * FROM $tableName where 1 and status != 'Draft' and approval_status != 'Rejected' order by id ";

//echo $sql;
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$pur_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$company_id				= $row['project'];
		$department				= $row['department'];
		$status					= $row['status'];
		
		$approval_memo_ref		= $row['approval_memo_ref'];
		$sql = "select * from sma_approval_memo where id = '$approval_memo_ref' ";
		$res = mysqli_query($con,$sql);
		$rw = mysqli_fetch_array($res);
		$approval_memo_ref		= $row['approval_memo_ref']. '-'.date('d-m-Y', strtotime($rw['dated']));	
		
		$quotation_reference_no = $row['quotation_reference_no'];
		$po_number  			= $row['po_number'];
		$delivery_days			= $row['delivery_days'];
		$credit_days			= $row['credit_days'];

		$location	 			= $row['location'];
		$discount 				= $row['discount'];
		$transport 				= $row['transport'];
		$other_charges 			= $row['other_charges'];
		$terms 					= $row['terms'];
	
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		
		if($approver_1_status=='Approved'){
			$approver_id 		= $row['approver_1'];
		}
		if($approver_2_status=='Approved'){
			$approver_id 		= $row['approver_2'];
		}
		if($approver_3_status=='Approved'){
			$approver_id 		= $row['approver_3'];
		}
		if($approver_4_status=='Approved'){
			$approver_id 		= $row['approver_4'];
		}
		if($approver_5_status=='Approved'){
			$approver_id 		= $row['approver_5'];
		}
		if($approver_6_status=='Approved'){
			$approver_id 		= $row['approver_6'];
		}
		if($approver_7_status=='Approved'){
			$approver_id 		= $row['approver_7'];
		}
		if($approver_8_status=='Approved'){
			$approver_id 		= $row['approver_8'];
		}
		
		$sql 	= "SELECT * FROM `sma_user` where id = '$approver_id'";
		$res = mysqli_query($con,$sql);
		$lc 	= mysqli_fetch_array($res);
		$approver_name    = $lc['username'];
		
		$sql 	= "SELECT * FROM `sma_location` where id = '$location'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$lc 	= mysqli_fetch_array($res);
		$loc_name    = $lc['loc_name'];
		
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$row 	= mysqli_fetch_array($res);
		$party_name  = $row['party_name'];

		$sql = "SELECT * FROM `company` where comp_id = '$company_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$comp_name 		= $com['comp_name'];
		
		$sql = "SELECT * FROM `sma_department` where id = '$department' ";
		$dep 	= mysqli_query($con,$sql);
		$deps 		= mysqli_fetch_array($dep);
		$department = $deps['name'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'PO' and doc_id = '$pur_id' order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$final_approver_date	= date('d-m-Y', strtotime($com['create_date']));
		
		$sql 	= "SELECT * FROM sma_po_items where purchase_id = '$pur_id'";
	
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$budget_id		= $rw['budget_id'];

			$quantity		= $rw['quantity'];
			$unit_rate		= round($rw['unit_rate'],2);
			$pod_discount	= $rw['pod_discount'];
			$gst			= $rw['gst'];
						
			$actual_amt     = $quantity * $unit_rate;
			$total_amt		= $total_amt + $actual_amt;
			
			$net_amt  		= round($actual_amt + ($actual_amt * $gst / 100),0);
			
			$total_net_amt	= $total_net_amt + $net_amt;
			
			$delivery_date  = date('d-m-Y', strtotime($rw['delivery_date']));
			
			$product_id=$rw['product_id'];
			$sql="Select * from sma_product where id = '$product_id'";
			$output = mysqli_query($con,$sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($output);

			$product_name = $r2['name'];
			$product_desc = $r2['description'];
			$unit		  = $r2['uom'];
			$hsn_code	  = $r2['hsn_code'];
			
			$sql = "SELECT a.*, b.budget_head as budget_head, c.name as budget_name FROM `sma_budget` a, sma_budget_subgroup b , sma_budget_name c
						where b.id = a.budget_head and c.id = a.budget_name and a.id = '$budget_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_head 		= $com['budget_head'];
			$budget_name 		= $com['budget_name'];
			$total_budget 		= $com['total_budget'];
		
		}
		
		$message .= "<tr>
					<td>".$comp_name."</td>
					<td>".$loc_name."</td>
					<td>".$department."</td>
					<td>".$po_number ."</td>
					<td>".$pur_id ."</td>
					
					<td>".$dated."</td>
					<td>".$party_name ."</td>
					<td style='width: 8%;text-align: right;'> ".$total_amt." </td>
					<td style='width: 8%;text-align: left;'>". $budget_name . " </td> 
					<td style='width: 8%;text-align: left;'>". $budget_head . " </td> 
					<td style='width: 8%;text-align: left;'>". $final_approver_date . " </td> 
					<td style='width: 8%;text-align: left;'>". $approver_name . " </td> 
					<td style='width: 8%;text-align: left;'>". $status . " </td> 
					
					</tr>";
		
//echo $message;
//exit(" EXIT HERE....");
	
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
//   if($prn=='excel'){
		$fl_name = 'po_final_approver_report'. '.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
//	}	

	

}