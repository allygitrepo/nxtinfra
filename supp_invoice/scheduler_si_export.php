<?php

	session_start();
	include "../dbcon.php";
	include "../baseurl.php";
		
	ini_set('max_execution_time', 0);
	
	$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$from_date 		= $r2['from_date'];
		$to_date   		= $r2['to_date'];
		$account_year 	= $r2['short_fy_code'];
		
	$message ='';
	$close   ='';				
	$flname = '../zoho/zoho-invoice'.'.csv';
	$fp 	= fopen($flname, 'w');
	$message = "SI.No. , Supplier Name,Supplier Inv.No., Created Date, Invoice Date,PO.Ref.No., Due Date, Sr.No.,GRN No., Material Name,Description,Account Year ,Company,Budget Name, Budget Head, Unit, Qty., Rate,GST., Total.,UTR.No., Approved By, Workflow Type, Pending With,Status, Decision, \n ";			
//	fwrite($fp, $message);

	$tableName	= "sma_supplier_invoice";
	
	$sql 		= " SELECT * FROM $tableName where 1 and del !='Y' 
					and created_date >= $from_date and created_date <= '$to_date' ";
echo $sql."<BR>";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$del					= $row['del'];
		$status					= $row['status'];
		
		if($del=='Y' ){
			continue;
		}
		
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
			
		$si_id					= $row['id'];
		$created_date  			= date('d-m-Y', strtotime($row['created_date']));
		$invoice_date  			= date('d-m-Y', strtotime($row['invoice_date']));
		$due_date  				= date('d-m-Y', strtotime($row['due_date']));
		
		if($due_date=='01-01-1970'){
			$due_date ='';	
		}
		
		$supplier_id			= $row['suplier_name'];
		$supplier_invoice_no	= $row['supplier_invoice_no'];
		
		$credit_days	 		= $row['credit_days'];
		$trans_type	 			= $row['trans_type'];
		
		if($credit_days==0){
			$credit_days ='';	
		}	
		$sql = "SELECT * FROM `sma_workflow_type` where id = '$trans_type' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$workflow_type 		= $com['workflow_type'];
		
		$sql = "SELECT * FROM `sma_party_mst` where id = '$supplier_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$supplier_name 		= $com['party_name'];
		
		$our_po_ref_no = $row['our_po_ref_no'];
		$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$our_po_ref_no = $r2['po_number'];
		
		$sql = "SELECT * FROM `workflow_history` where doc_type = 'SI' and doc_id = '$si_id' and status in ('Approved', 'Submitted') order by id desc ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$create_by 		= $com['create_by'];
		
		$sql = "SELECT * FROM `sma_user` where id = '$create_by' ";
		$comresult 	= mysqli_query($con,$sql);
		$com 		= mysqli_fetch_array($comresult);
		$approver_name 		= $com['username'];
		
		$transport_lr_no = $row['transport_lr_no'];
		$transport_name  = $row['transporter_name'];
		
		$sql = "SELECT * FROM `payment_header` a , payment_details b where a.id = b.payment_hdr_id and a.st_flag = 'S' and b.supp_id = '$si_id' ";
		$comresult 	= mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		$com 		= mysqli_fetch_array($comresult);
		$utrno 		= '';
		if($row_affected>0){
			$utrno 		= $com['utr_no'];
		}
		
		$sql 	= "SELECT * FROM sma_supplier_invoice_details where qty > 0 and si_hdr_id = '$si_id'";
		
		$i = 0 ;
		$res = mysqli_query($con,$sql);
		$row_affected = mysqli_affected_rows($con);
		
		if($row_affected = 0){
			
			continue;
		}
		
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
		
			$grn_no			= $rw['grn_no'];
			$qty			= $rw['qty'];
			
			if($qty==0){
				continue;
			}
			
			$unit			= $rw['unit'];
			$description	= $rw['description'];
			
			$company_id		= $rw['company_id'];
			$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 				= mysqli_fetch_array($comresult);
			$comp_name 			= $com['comp_name'];
				
			$budget_id	= $rw['budget_id'];
			$sql = "SELECT * FROM `sma_budget`  where id = '$budget_id' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['budget_name'];
			$budget_head		= $com['budget_head'];	
			$account_year		= $com['account_year'];	
			
			$sql = "SELECT * FROM `sma_budget_name`  where id = '$budget_name' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_name 		= $com['name'];
			
			$sql = "SELECT * FROM `sma_budget_subgroup`  where id = '$budget_head' ";
			$comresult 	= mysqli_query($con,$sql);
			$com 		= mysqli_fetch_array($comresult);
			$budget_head 		= $com['budget_head'];
			
			$product_id = $rw['material_id'];
			$sql="Select * from sma_product where id = '$product_id'";
			$output = mysqli_query($con,$sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($output);

			$product_name = $r2['name'];
			$unit		  = $r2['uom'];
			$hsn_code	  = $r2['hsn_code'];
			
			$unit_rate		= round($rw['rate'],2);
			$gst			= $rw['gst'];
			
			$tot_qty		= $qty;
			$actual_amt     = $qty * $unit_rate;
			$total_amt		= $total_amt + $actual_amt;
			
			$net_amt  		= round($actual_amt + ($actual_amt * $gst / 100),0);
			
			$total_net_amt	= $total_net_amt + $net_amt;
		
			++$i;
			
			$budget_name 	= str_replace(',', ' ', $budget_name);
			$budget_head 	= str_replace(',', ' ', $budget_head);
			$description 	= str_replace(',', ' ', $description);
			$product_name 	= str_replace(',', ' ', $product_name);
			$supplier_invoice_no 	= str_replace(',', ' ', $supplier_invoice_no);
			
			$message .= "$si_id,$supplier_name,	$supplier_invoice_no,$created_date,	$invoice_date, $our_po_ref_no, $due_date,$i,$grn_no,$product_name ,$description ,$account_year ,$comp_name ,$budget_name, $budget_head ,$unit ,$qty,$unit_rate,$gst,$net_amt,$utrno,$approver_name,	$workflow_type,	$pending_by,$status,$approval_status, \n";			
	
		}
			
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
	