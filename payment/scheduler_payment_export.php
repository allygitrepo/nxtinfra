<?php

	session_start();
	include "../dbcon.php";
	include "../baseurl.php";
		
	$sql = " SELECT * FROM `sma_financial_year` where status = 'Y' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$from_date 		= $r2['from_date'];
		$to_date   		= $r2['to_date'];
		$account_year 	= $r2['short_fy_code'];
		
	$message ='';
	$flname = '../zoho/zoho-payment'.'.csv';
	$fp 	= fopen($flname, 'w');
	$message = "Payment Serial No.,Prepared date, Company, Paid To, Paid Via,PO Number,Type, Total Amount, TDS Amount,Paid Date,Supplier Invoice No.,Supplier Invoice Amount,Payable Amount,Deduction Head,Deduction Amount,Deduction Head-2,Deduction Amount,Paid Amount,Bal. Amount,Cheque No.,UTR No.,By,Pending With,Decision, \n ";	
	
	$sql = "SELECT a.*, b.supplier_invoice_no, b.invoice_date, b.deduction_head, b.deduction_amt, b.deduction_head1, b.deduction_amt1, b.actual_payment 
	FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and del !='Y' 
	and paid_date >= $from_date and paid_date <= '$to_date' ";
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$paid_date 				= date('d-m-Y', strtotime($row['paid_date']));
		$invoice_date 			= date('d-m-Y', strtotime($row['invoice_date']));
		$company_id				= $row['company_id'];
		$payment_no	 			= $row['id'];
		$paid_to 				= $row['paid_to'];
		$cash_bank_name 		= $row['cash_bank_name'];
		$cheque_no 				= $row['cheque_no'];
		$utr_no 				= $row['utr_no'];
		$total_amount_paid 		= $row['total_amount_paid'];
		$tds_amount 			= $row['tds_amount'];
		$st_flag 				= $row['st_flag'];
		
		$approval_status 		= $row['approval_status'];
		if($approval_status 	== 'Rejected' ){
			$approval_status 	= $row['status'];
		}	
		if(empty($approval_status)){
			$approval_status = 'Approved';
		}
			
		$changed_by = $row['changed_by'];
				if(empty($changed_by)){
					$changed_by = $draft_by;
				}	
				
				$sql = "select * from sma_user where userid = '$changed_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$changed_by  = $r2['username'];
				
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
				
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
				if(empty($pending_by)){
					$pending_by = 'Approved';	
				}
				
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$company_name = $r2['comp_name'];

		if($st_flag=='S' || $st_flag =='C' || $st_flag =='D'){
			$sql = "SELECT * from sma_party_mst where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to = $r2['party_name'];
		}
		else if($st_flag=='T' || $st_flag=='A'){
			$sql = "SELECT * from sma_user where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to = $r2['username'];
        }

		$type = "Regular";
		if($st_flag == 'D'){
			$type = "Advance";
		}
		
		$sql = "SELECT * from account_mst where id = '$cash_bank_name' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$account_name = $r2['account_name'];
		
		$sql = "SELECT * FROM payment_details where payment_hdr_id = '$payment_no' ";
//echo $sql;		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
	
			$supplier_invoice_no 	= $rw['supplier_invoice_no'];
			$supp_id			 	= $rw['supp_id'];
			$deduction_head 		= $rw['deduction_head'];
			$deduction_amt 			= $rw['deduction_amt'];
			$deduction_head1 		= $rw['deduction_head1'];
			$deduction_amt1 		= $rw['deduction_amt1'];
			$actual_payment 		= $rw['actual_payment'];
			$payment_adjusted 		= $rw['payment_adjusted'];
			
			$po_number = '';
			$si_total_amount = 0;
			if($st_flag =='S' || $st_flag=='R' ){
				$sql = "SELECT * FROM sma_supplier_invoice a, sma_purchase_order b where a.our_po_ref_no = b.id and a.id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
						$si_total_amount = $r2['total_amount'];
									
			}
			else if($st_flag =='D'){
				$sql = "SELECT * FROM  sma_purchase_order b where id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
			}
			
			$bal_payment = $si_total_amount - $actual_payment;
			
			$supplier_invoice_no 	= str_replace(',', ' ', $supplier_invoice_no);
			
			$message .= "$payment_no,$paid_date,$company_name,$paid_to,$account_name,$po_number,$type, $total_amount_paid,	$tds_amount,$paid_date,	$supplier_invoice_no,$si_total_amount,$payment_adjusted,$deduction_head,$deduction_amt,$deduction_head1,$deduction_amt1,$actual_payment,	$bal_payment,$cheque_no ,$utr_no,$changed_by,$pending_by,$approval_status, \n";

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
	