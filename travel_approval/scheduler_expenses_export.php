<?php
	set_time_limit(0);
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

	$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$from_date 		= $r2['from_date'];
		$to_date   		= $r2['to_date'];
		$account_year 	= $r2['short_fy_code'];
		
	$flname = '../zoho/zoho-opex'.'.csv';
	$fp 	= fopen($flname, 'w');

	$message = "Tran.Type, #No., Name, Dated, App.Ref.No. , Company, Invoice No., Expenses, Dated, Amount, GST, Budget Name, Budget Head, Budget Amount, UTR.No., Approver Name, Workflow Type, Pending With, Status, Decision, \n ";			
//	fwrite($fp, $message);
	
	$tableName	= "sma_travel_expenses";
	
	$sql 		= " SELECT * FROM $tableName where 1 and del !='Y' 
			and dated >= $from_date and dated <= '$to_date'";
//echo $sql;
//exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

		$doc_id			 	= $row['id'];
		$emp_id 			= $row['emp_id'];
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		$gtype				= $row['exp_type'];
		$approval_status	= $row['approval_status'];
		if(empty($approval_status)){
			$approval_status = 'Approved';
		}
		
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
					
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				if(!empty($pending_by)){
					$sql = "select * from sma_user where id = '$pending_by' ";
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$pending_by  = $r2['username'];
				}
				else {
					$pending_by = 'Approved';	
				}
				
				if(empty($pending_by)){
					$pending_by = 'Approved';	
				}
				
			$status = $row['status'];
			if($searchfu=='U'){
				$status = "UnPaid";
			}
			
			
		if($gtype =='C'){
			$st_flag = 'C';
			$doc_type = 'CE';
		}
		else if($gtype =='R'){
			$st_flag = 'T';
			$doc_type = 'RE';
		}
		else if($gtype =='T'){
			$st_flag = 'T';
			$doc_type = 'TE';
		}
		
		$sql = "SELECT * FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id and a.st_flag = '$st_flag' and b.supp_id = '$doc_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		$com 		= mysqli_fetch_array($comresult);
		$utrno 		= '';
		if($row_affected>0){
			$utrno 		= $com['utr_no'];
		}
		
		if($gtype =='C'){
			
			$sql = "select * from sma_party_mst where id = '$emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['party_name'];	
		}
		else {
			
			$sql = "select * from sma_user where id = '$onbehalf_emp_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$emp_name = $r2['username'];
			
		}	
		
		$trans_type	 			= $row['trans_type'];
		
		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$workflow_type 		= $com['workflow_type'];
		
		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 		= $r1['comp_name'];
		$comp_code 		= $r1['comp_code'];
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated=='01-01-1970'){ $dated='';}
		
		$approval_ref_no 	= $row['approval_ref_no'];
		$idd			 	= $row['id'];
		$advance_amount 	= $row['advance_amount'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = '$doc_type' and doc_id = '$idd' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 		= $com['create_by'];
		
		$sql = "SELECT * FROM `sma_user` where id = '$create_by' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$approver_name 		= $com['username'];
		
		//$hdr_msg = "$doc_type, $idd, $emp_name, $dated, $approval_ref_no, $comp_name ,";
		
		$sql  = " SELECT * FROM `sma_expenses` where exp_type = '$gtype' and approval_ref_no = '$idd' ";
		$res3 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$ln=0;
		$tot_amount = 0 ;
		while($r1 	= mysqli_fetch_array($res3)){
			
			$dated = date('d-m-Y', strtotime($r1['dated']));
			if($dated=='01-01-1970'){ $dated='';}
			$reference				= $r1['reference'];
			$reference_id			= $r1['reference'];
			$reference_invoice_no 	= $r1['invoice_no'];
			$budget_id 	= $r1['budget_id'];
			$amount 	= $r1['amount'];
			$note 		= $r1['note'];
			$gst_flag 	= $r1['gst_flag'];
			
			$sql="SELECT * from sma_product where id = '$reference_id' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$reference = $r2['name'];
			
			$sql = " SELECT * FROM `sma_budget` where id = '$budget_id'  ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$budget_name  		= $r2['budget_name'];
			$budget_head	  	= $r2['budget_head'];
			$total_budget 		= $r2['total_budget'];
			
			$sql = "SELECT * FROM `sma_budget_name`  where id = '$budget_name' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
			$note  		 = str_replace(',', ' ', trim($note) );
			$reference_invoice_no = str_replace(',', ' ', $reference_invoice_no);
			$reference 	 = str_replace(',', ' ', $reference);
			$budget_name = str_replace(',', ' ', $budget_name);
			$budget_head = str_replace(',', ' ', $budget_head);
			
			$message .= "$doc_type, $idd, $emp_name, $dated, $approval_ref_no, $comp_name ," . "$reference_invoice_no,$reference,$dated ,$amount,$gst_flag,$budget_name,$budget_head,$total_budget,$utrno,$approver_name,	$workflow_type,	$pending_by,$status,$approval_status, \n";			
	
		}


	//	echo "<script>window.close();</script>";	
	//	exit();
		
	}

	
		fwrite($fp, $message);
		fclose($fp);

		include "zoho_mail.php";

		if($close=='Y'){
			echo "<script>window.close();</script>";	
			exit();
		}			
//echo $message;
//exit();
	
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
 
