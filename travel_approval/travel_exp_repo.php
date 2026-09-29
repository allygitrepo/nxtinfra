<?php
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
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		
	$message ='';
	
	$message .= "<br><br>";
	
	//$message .= "<table border='1' cellspacing='0' style='width: 95%;margin-left: 30px;'>";
	$message .= '<div style="width:100%;">
					<div style="margin-left:35px;margin-right:10px;">';    
	
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;'>Travel Expense Report </th></tr></table>";
		
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
		$id				= $_GET['id'];
	//$comid = $_SESSION['comid'];	
	
	$sql = "SELECT * from sma_travel_expenses where id = '$id' ";
	
//echo $sql."<BR>";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$emp_id			= $row['emp_id'];
			$req_id			= $row['id'];
			$company_id		= $row['company_id'];
			$approval_ref_no= $row['approval_ref_no'];
			$dated			= date('d-m-Y', strtotime($row['dated']));
			if($dated=='01-01-1970'){$dated='';}
			
/*			$start_date		= date('d-m-Y', strtotime($row['start_date']));
			$end_date		= date('d-m-Y', strtotime($row['end_date']));
			$advance_amount	= $row['advance_amount'];
			$purpose_visit	= $row['purpose_visit'];
			$traval_from	= $row['traval_from'];
			$traval_to		= $row['traval_to'];
			$estimated_days	= $row['estimated_days'];
			$remarks		= $row['remarks'];
			$start_time		= $row['start_time'];
			$end_time		= $row['end_time'];
			$booking_details= $row['booking_details'];
			$purpose_visit	= $row['purpose_visit'];
*/			
			$draft_by		= $row['draft_by'];
			$draft_time	 	= date('d-m-Y H:i a', strtotime($row['draft_dated']));
			
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		
		$s="select * from sma_user where id = '$emp_id' ";
//echo $s."<br>";	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		
		while($r = mysqli_fetch_object($sql)){
			
			$userid		 	= $r->userid;
			$user_name 		= $r->username;
			$role	 		= $r->role;
			$pan_no	 		= $r->pan_no;
			$roll_no	 	= $r->roll_no;
			$dept	 		= $r->department;
			$designation 	= $r->designation;
			$level		 	= $r->level_id;
			$mobile		 	= $r->mobile;
			$user_email	 	= $r->email;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$password_expired_date	= $r->password_expired_date;
			
		}
		
		$sql  = "SELECT * from sma_department where id = '$dept' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$department 		= $r1['name'];
		
		$sql  = "SELECT * FROM `sma_user` where role in (SELECT id FROM `sma_role` where role = 'HOD') and department = '$dept' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$rep_manager 		= $r1['username'];
		
		$sql  = "SELECT * from sma_designation where id = '$designation' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$designation 		= $r1['designation'];
		
		$sql  = "SELECT * from sma_level where id = '$level' ";
//echo $sql."<br>";
//exit();
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$level_name 		= $r1['level_name'];
	
//		$sql = " SELECT * FROM `workflow_history` where doc_type = 'TE' and doc_id = '$id' and status = 'Draft' ";
//echo $sql."<br>";
//exit();
//		$res = mysqli_query($con, $sql);
//		$rr = mysqli_fetch_object($res);
//		$draft_by		= $rr->create_by;
//		$draft_time		= $rr->create_date;
//echo $draft_time. "<BR>";		
//		$draft_time	 	= date('d-m-Y H:i a', strtotime($draft_time));
//echo $draft_time. "<BR>";		
		
//		$sql  = "select * from sma_user where id = '$draft_by' ";
//echo $sql."<br>";	
//		$res = mysqli_query($con, $sql);
//		$rr = mysqli_fetch_object($res);
//		$draft_by		= $rr->username;
		
		$sql = " SELECT * FROM `workflow_history` where doc_type = 'TE' and doc_id = '$id' and status = 'Approved' ";
//echo $sql."<br>";		
		$res = mysqli_query($con, $sql);
		$rr  = mysqli_fetch_object($res);
		$create_by			= $rr->create_by;
		$approved_time		= $rr->create_date;
		$approved_time	 	= date('d-m-Y H:i a', strtotime($approved_time));
		$approved_date	 	= date('d-m-Y', strtotime($rr->create_date));
		
		
		$sql  = "select * from sma_user where id  = '$create_by' ";
//echo $sql."<br>";	
//exit();
		
		$res = mysqli_query($con, $sql);
		$rr = mysqli_fetch_object($res);
		$approved_by		= $rr->username;
		
		
		
		//echo $approved_by. ' ' . 
		$message .= "<br><br>";
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'> <tr><td style='width: 50%;'><b>Visit For</b>: $comp_name </td><td style='width: 30%;'><b>Travel Request No.</b>: $approval_ref_no</td><td style='width: 20%;'><b>Expense No.</b>: $id</td></tr></table>";
		$message .= "<br>";
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'> <tr><td style='width: 50%;'><b>Employee Name</b>: $user_name</td><td style='width: 50%;'><b>Emp.Code</b>: $roll_no</td></tr></table>";
		
		$message.="<table style='width: 100%;' border='.02' cellspacing='0'> <tr><td style='width: 50%;'><b>Designation</b>: $designation</td><td style='width: 20%;' ><b>Level</b>: $level_name</td><td style='width: 30%;'><b>Date of Submit</b>: $dated</td></tr></table>";
		
		$sql = "SELECT * from sma_traval_approval where id = '$approval_ref_no'  ";
//echo $sql;		
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
		$advance_amount	= $row['advance_amount'];
		$purpose_visit	= $row['purpose_visit'];
		$utr_no			= $row['utr_no'];
		$pdate			= date('d-m-Y', strtotime($row['pdate']));

		$message .= "<br>";
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'> <tr><td style='width: 100%;'><b>Purpose</b> : $purpose_visit</td></tr></table>";
		$message .= "<br>";
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'> <tr><td style='width: 100%;'><b>Trip Details</b></td></tr></table>";

		$message .= "<table style='width: 100%;' border='.02'  cellspacing='0'>
					<tr><td style='width: 10%;'><b>Sr.No.</b></td>
					<td style='width: 27%;text-align:center;'><b>From</b></td>
					<td style='width: 27%;text-align:center;' ><b>To</b></td>
					<td style='width: 36%;'>&nbsp;</td>
					</tr></table>";
		
		$message .= "<table style='width: 100%;text-align:center;' border='.02' cellspacing='0'><tr>
			<td style='width: 09%;'><b>DATE</b></td>
			<td style='width: 11%;'><b>PLACE</b></td>
			<td style='width: 7%;'><b>Time</b></td>
			<td style='width: 09%;'><b>DATE</b></td>
			<td style='width: 11%;'><b>PLACE</b></td>
			<td style='width: 7%;'><b>Time</b></td>
			<td style='width: 10%;'><b>Spend By</b></td>
			<td style='width: 10%;'><b>Mode of Travel</b></td>
			<td style='width: 12%;'><b>Invoice No.</b></td>
			<td style='width: 10%;text-align:right;'><b>Amount in Rs.</b></td>
			<td style='width: 4%;font-size: 10px;text-align:left;'><b>GST</b></td>
			</tr></table>";						
	
		$sql = "SELECT * FROM `sma_departure` where approval_ref_no = '$req_id' ";
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1  = mysqli_fetch_array($result)){
			
			$mode_of_travel = $r1['mode_of_travel'];
			if($mode_of_travel =='Flight'){
				$mode_of_travel = 'Flight';
			}
			else if($mode_of_travel =='Bus'){
				$mode_of_travel = 'Bus';
			}
			else if($mode_of_travel =='Train'){
				$mode_of_travel = 'Train';
			}
			else if($mode_of_travel =='Car'){
				$mode_of_travel = 'Car';
			}
			
						
			$start_date  = date('d-m-Y', strtotime($r1['start_date']));
			$start_place = $r1['start_place'];
			$start_time  = $r1['start_time'];
			$end_date 	 = date('d-m-Y', strtotime($r1['end_date']));
			$end_place	 =  $r1['end_place'];
			$finish_time = $r1['finish_time'];
			$invoice_no =  $r1['invoice_no'];
			$fare 		=  $r1['fare'];
			$gst_flag	=  $r1['gst_flag'];

				
			$spend_by = $r1['spend_by'];
			
			if($gst_flag=='Y'){
				if($spend_by =='C'){
					$spend_by = 'Company';
					$com_fare_gst = $com_fare_gst + $r1['fare'];
					
				}
				else if($spend_by =='O'){
					$spend_by = 'OWN';
					$own_fare_gst = $own_fare_gst + $r1['fare'];
					
				}
			}
			else {

				if($spend_by =='C'){
					$spend_by = 'Company';
					$com_fare = $com_fare + $r1['fare'];
					
				}
				else if($spend_by =='O'){
					$spend_by = 'OWN';
					$own_fare = $own_fare + $r1['fare'];
					
				}
			}
			
			$tot_fare = $tot_fare + $r1['fare'];
			
			$j = $j + 1;	
							
			$message .= "<table style='width: 100%; font-size: 11px;' border='.02' cellspacing='0'><tr>
			<td style='width: 09%;'>$start_date</td>
			<td style='width: 11%;'>$start_place</td>
			<td style='width: 7%;'>$start_time</td>
			<td style='width: 09%;'>$end_date</td>
			<td style='width: 11%;'>$end_place</td>
			<td style='width: 07%;'>$finish_time</td>
			<td style='width: 10%;'>$spend_by</td>
			<td style='width: 10%;'>$mode_of_travel</td>
			<td style='width: 12%;'>$invoice_no</td>
			<td style='width: 10%;text-align:right;'>$fare</td>
			<td style='width: 4%;text-align:center;'>$gst_flag</td>
			</tr></table>";
	
		}
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 09%;'></td>
			<td style='width: 11%;'></td>
			<td style='width: 7%;'></td>
			<td style='width: 09%;'></td>
			<td style='width: 11%;'></td>
			<td style='width: 7%;'></td>
			<td style='width: 10%;'></td>
			<td style='width: 10%;'></td>
			<td style='width: 12%;text-align:right;'><b>Total</b></td>
			<td style='width: 10%;text-align:right;'>Rs.<b>$tot_fare</b></td>
			<td style='width: 4%;text-align:right;'></td>
			</tr></table>";
			
//echo $message;
//exit();

$message .= "<br>";

$message .= "<table style='width: 100%;' border='.02' cellspacing='0' > <tr><td style='width: 100%;'><b>Expenses</b></td></tr></table>";

$message .= "<table style='width: 100%;text-align:center;' border='.02' cellspacing='0'><tr>
			<td style='width: 10%;'><b>DATE</b></td>
			<td style='width: 25%;'><b>Particulars</b></td>
			<td style='width: 25%;'><b>Remarks</b></td>
			<td style='width: 16%;'><b>Invoice No.</b></td>
			<td style='width: 10%;text-align:right;'>Spend By</td>
			<td style='width: 10%;text-align:right;'><b>Amount in Rs.</b></td>
			<td style='width: 4%;font-size: 10px;'><b>GST</b></td>
			</tr></table>";						
	
	
		$sql = "SELECT * FROM `sma_expenses` where exp_type = 'T' and approval_ref_no = '$req_id' ";
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1  = mysqli_fetch_array($result)){
						
			$reference 	= $r1['reference'];
			$invoice_no = $r1['invoice_no'];
			$amount 	= round($r1['amount'],0);
			$note 		= $r1['note'];
			$gst_flag	= $r1['gst_flag'];
			$spend_by	= $r1['spend_by'];
			$dated 		= date('d-m-Y', strtotime($r1['dated']));
			
			
			
			if($gst_flag=='Y'){
				if($spend_by =='C'){
					$spend_by = 'Company';
					$com_amount_exp_gst = $com_amount_exp_gst + $r1['amount'];
					
				}
				else if($spend_by =='O'){
					$spend_by = 'OWN';
					$own_amount_exp_gst = $own_amount_exp_gst + $r1['amount'];
					
				}
			}
			else {

				if($spend_by =='C'){
					$spend_by = 'Company';
					$com_amount_exp = $com_amount_exp + $r1['amount'];
					
				}
				else if($spend_by =='O'){
					$spend_by = 'OWN';
					$own_amount_exp = $own_amount_exp + $r1['amount'];
					
				}
			}
			
			$reference_id = $reference;
			if( $reference_id=='42' ){
				
				$sql="SELECT * from sma_product where id = '$reference'";
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$reference_v    = $r2['name'];
				$amount_v		= $amount;
				$dated_v		= $dated;
				$note			= $note;
				continue;
			}
			else {
				$sql="SELECT * from sma_product where id = '$reference'";
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$reference = $r2['name'];
			}								
			
			$tot_amount += $amount;
			$exp_amount += $amount;
									
			
			
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 10%;'>$dated</td>
			<td style='width: 25%;'>$reference</td>
			<td style='width: 25%;'>$note</td>
			<td style='width: 16%;font-size:10px;'>$invoice_no</td>
			<td style='width: 10%;text-align:right;'>$spend_by</td>
			<td style='width: 10%;text-align:right;'>$amount</td>
			<td style='width: 4%;text-align:center;'>$gst_flag</td>
			
			</tr></table>";
			
		}
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 10%;'></td>
			<td style='width: 25%;'></td>
			<td style='width: 25%;'></td>
			<td style='width: 26%;text-align:right;'><b>Total</b></td>
			<td style='width: 10%;text-align:right;'>Rs.<b>$tot_amount</b></td>
			<td style='width: 4%;'></td>
			</tr></table>";
		
		$message .= "<br>";				
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 100%;'><b>Vendor Register Under GST</b></td>
			</tr></table>";
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'><b>Particulars</b></td>
			<td style='width: 45%;'><b>Spend By</b></td>
			<td style='width: 20%;text-align:right;'><b>Amount in Rs.</b></td>
			</tr></table>";
		
		if($com_fare_gst>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Trip Detail</td>
			<td style='width: 45%;'>Company</td>
			<td style='width: 20%;text-align:right;'>$com_fare_gst</td>
			</tr></table>";
		}
		
		if($own_fare_gst>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Trip Detail</td>
			<td style='width: 45%;'>Own</td>
			<td style='width: 20%;text-align:right;'>$own_fare_gst</td>
			</tr></table>";
		}
		
		if($com_amount_exp_gst>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Expenses</td>
			<td style='width: 45%;'>Company</td>
			<td style='width: 20%;text-align:right;'>$com_amount_exp_gst</td>
			</tr></table>";
		}
		
		if($own_amount_exp_gst>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Expenses</td>
			<td style='width: 45%;'>Own</td>
			<td style='width: 20%;text-align:right;'>$own_amount_exp_gst</td>
			</tr></table>";
		}
		
		$tot_exp_amount_gst = ($own_fare_gst + $own_amount_exp_gst + $com_amount_exp_gst) ;
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'></td>
			<td style='width: 45%;text-align:right;'><b>TOTAL  </b></td>
			<td style='width: 20%;text-align:right;'>Rs.<b>$tot_exp_amount_gst</b></td>
			</tr></table>";
		
		
		
		$message .= "<br>";				
		
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 100%;'><b>Vendor Not Register Under GST</b></td>
			</tr></table>";
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'><b>Particulars</b></td>
			<td style='width: 45%;'><b>Spend By</b></td>
			<td style='width: 20%;text-align:right;'><b>Amount in Rs.</b></td>
			</tr></table>";
		
		
		if($own_fare>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Trip Detail</td>
			<td style='width: 45%;'>Own</td>
			<td style='width: 20%;text-align:right;'>$own_fare</td>
			</tr></table>";
		}
		
		if($com_fare>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Trip Detail</td>
			<td style='width: 45%;'>Company</td>
			<td style='width: 20%;text-align:right;'>$com_fare</td>
			</tr></table>";
		}
		
		
		if($com_amount_exp>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Expenses</td>
			<td style='width: 45%;'>Company</td>
			<td style='width: 20%;text-align:right;'>$com_amount_exp</td>
			</tr></table>";
		}
		
		if($own_amount_exp>0){	
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'>Expences</td>
			<td style='width: 45%;'>Own</td>
			<td style='width: 20%;text-align:right;'>$own_amount_exp</td>
			</tr></table>";
		}
		
		$tot_exp_amount = $own_amount_exp + $com_amount_exp + $own_fare  + $com_fare;
		
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'></td>
			<td style='width: 45%;text-align:right;'><b>TOTAL  </b></td>
			<td style='width: 20%;text-align:right;'>Rs.<b>$tot_exp_amount</b></td>
			</tr></table>";
		
		$message .= "<BR>";
		
		if($advance_amount>0){
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 100%;'><b>Advance</b></td>
			</tr></table>";
			
			if($pdate=='01-01-1970' || $pdate=='30-11--0001'){$pdate='';}
			
			$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			
			<td style='width: 10%;'><b>UTR No.:</b></td>
			<td style='width: 25%;'>$utr_no</td>
			<td style='width: 10%;'><b>Date</b></td>
			<td style='width: 10%;'>$pdate</td>
			<td style='width: 35%;'><b>Payment Advance</b></td>
			<td style='width: 10%;text-align:right;'>Rs.$advance_amount</td>
			</tr></table>";
			
			$message .= "<br>";				
		}
		//echo $own_amount_exp. ' '.  'ABC';
		$tot_payable = ($own_amount_exp + $own_fare+ $own_fare_gst + $own_amount_exp_gst) - $advance_amount;
		$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
			<td style='width: 35%;'></td>
			<td style='width: 45%;text-align:right;'><b>TOTAL PAYABLE TO EMPLOYEE</b></td>
			<td style='width: 20%;text-align:right;'>Rs.<b>$tot_payable</b></td>
			</tr></table>";
		
	
		if($amount_v!=0){
				$message .= "<table style='width: 100%;' border='.02' cellspacing='0'><tr>
					<td style='width: 10%;'><b></b></td>
					<td style='width: 30%;'>$note</td>
					<td style='width: 10%;'><b>Date</b></td>
					<td style='width: 10%;'>$dated_v</td>
					<td style='width: 30%;'><b>$reference_v</b></td>
					<td style='width: 10%;text-align:right;'>Rs.$amount_v</td>
					</tr></table>";
		}

	
			$message .=  "<h4> Documents</h4>";
	
	$modulePath = "travel_approval/";
	$baseurl2  = $baseurl.$modulePath;
	
		$id		= $_GET['id'];
		$sql 	= "SELECT * FROM file_uploads where module = 'TE' and reference_id = '$id'";
	
		$result = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($row = mysqli_fetch_array($result)){
			$file_name = $row['file_name'];
			$file_path = $row['file_path'];
			$doc_type = $row['doc_type'];
			
			$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
			
			$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
				<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
		
		}
/* 
		if($approved_date=='01-01-1970'){$approved_time='';}
		
		$message .= "<br><br>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'>Prepared By:$draft_by</td>";
		$message .= "<td style='width: 50%;'>Verified By: $approved_by</td></tr></table>";
		$message .= "<table style='width: 100%;'> <tr><td style='width: 50%;'>$draft_time</td>";
		$message .= "<td style='width: 50%;'> $approved_time</td></tr></table>";
 */
		$doctype = 'TE';
		$ap_id   = $req_id;
		include "../workflow_process_to_mail.php";
		
		$message .= "<BR>";
		
	}
	   
    $message .= '</div></div>';
	
echo $message;
exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'travel_expense_repo.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
}

		
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}
