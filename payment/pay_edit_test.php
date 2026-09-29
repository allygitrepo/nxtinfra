<?php
include("../header.php");
$modulePath = "payment/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php  
	if($_GET['sub'] == 'delete'){
		
		$id  = $_GET['id'];
		$st_flag  = $_GET['st_flag'];
		$sql = "SELECT * FROM `payment_details` where payment_hdr_id ='$id' ";
		$query11 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql."<BR>";		
		
		while($r3  = mysqli_fetch_array($query11)){
			$supplier_invoice_no = $r3['supplier_invoice_no'];
			$supp_id	= $r3['supp_id'];
			$total_amount = $r3['payment_adjusted'] ; //+  $r3['deduction_amt']  +  $r3['deduction_amt1']
			
			//Deduction amount should deduct from bal_amount.
					$ded_amount = 0;
					if($st_flag=='T' || $st_flag=='C'){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'D' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('TE','RE','CE') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
					}
					else if($st_flag=='S' ){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'A' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('SI') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
						
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where b.id = a.account_id and b.account_type = 'D' and  a.doc_type = 'SI' and a.account_type = 'A' and a.effect = 'Cr' and a.doc_no = '$supp_id' and account_name not in ('Retention', 'Retention Money' ) ";
						
						$sql1 = "SELECT * FROM `tally_journal_entry` a , account_mst b where b.id = a.account_id and b.account_type = 'E' and  a.doc_type = 'SI' and a.account_type = 'A' and a.effect = 'Cr' and a.doc_no = '$supp_id' and account_name in ('Advance' ) ";
						
						
						
					}
					if($st_flag=='T' || $st_flag=='C' || $st_flag=='S' ){
			//echo $sql;			
						$qr2   = mysqli_query($con, $sql);
						while ($res2  = mysqli_fetch_array($qr2)){
							$ded_amount      = $res2['amount'];
							$deduction_head1 = $res2['account_name'];
						}
						
						if($st_flag=='S'){
							$qr2   = mysqli_query($con, $sql1);
							while ($res2  = mysqli_fetch_array($qr2)){
								$ded_amount      = $res2['amount'];
								$deduction_head1 = $res2['account_name'];
							}
						}
						
					}
					
			if($st_flag=='S' || $st_flag=='R' ){
				$sql = "update `sma_supplier_invoice` set bal_amount = bal_amount + '$total_amount' + $ded_amount, paid_status='' where supplier_invoice_no = '$supplier_invoice_no' or id = '$supp_id' ";
		//echo $sql."<BR>";
		
			}
			else if($st_flag=='A'){
						
				$sql = "update `sma_traval_approval` set paid_amount = paid_amount - '$total_amount'+ $ded_amount, paid_status='' where id = '$supp_id' ";
						
			}
			else if($st_flag=='T'){
						
				$sql = " update sma_travel_expenses set bal_amount = total_amount - '$total_amount' + $ded_amount, paid_status='' where id = '$supp_id' ";
						
			}
			else if($st_flag=='C'){
						
				$sql = " update sma_travel_expenses set bal_amount = total_amount - '$total_amount' + $ded_amount, paid_status='' where id = '$supp_id' ";
						
			}
			else if($st_flag=='D'){
						
				$sql = "update sma_purchase_order set paid_amount = paid_amount - '$total_amount' + $ded_amount, paid_status = '' where id = '$supp_id' ";
						
			}
					
			$query1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			//echo $sql."<BR>";
		}
		
//exit();		
        $id  	= $_GET['id'];
		//$sql 	= "delete from payment_header where id='$id' ";
        $sql 	= "update payment_header set del = 'Y' where id = '$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql."<BR>";

/*		$sql = "delete from payment_details where payment_hdr_id = '$id' ";
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
*/
//echo $sql."<BR>";
//exit();
		
        //echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		$baseurl1 =$baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
			
	} 
?>


<?php
	
	if(isset($_POST['editTally'])){
		 
		$record_id     		= $_POST['record_id'];
		$si_hdr_id 			= $_POST['si_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_id 		= $_POST['account_id'];
		$amount 			= $_POST['amount'];
		$prev_amount		= $_POST['prev_amount'];
		$narration 			= $_POST['narration'];
		$effect 			= $_POST['effect'];
		
		if( $account_type =='A' ){
			$sql = "SELECT * FROM account_mst where id = '$account_id' ";
			$re = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r5 = mysqli_fetch_array($re);
			$account_name = $r5['account_name'];												
		}
		else if($account_type =='V'){
			$sql = "SELECT id, party_name as 'account_name' FROM sma_party_mst where id = '$account_id'  ";
			$re = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r5 = mysqli_fetch_array($re);
			$account_name = $r5['account_name'];
		}
		else if($account_type =='B'){
			$sql = "SELECT id, category as 'account_name' FROM sma_budget_category where id = '$account_id' ";
			$re = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r5 = mysqli_fetch_array($re);
			$account_name = $r5['account_name'];
		}
											
		$sql = "update `tally_journal_entry` set 
					record_id     		= '$record_id',
					account_type 		= '$account_type',
					account_id 			= '$account_id',
					account_name		= '$account_name',
					amount 				= '$amount',
					narration 			= '$narration',
					effect 				= '$effect'
				where doc_no = '$si_hdr_id' and record_id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);

//exit();
		
		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'edit.php?id='.$si_hdr_id.'&active5=active&zyx';
		echo "<script>window.location.href='$baseurl';</script>";
		
	}
	
?>


<?php
	if(isset($_POST['Save'])){

$file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file);
		
			$id				= $_POST['id']; 
			
			$py_id			= $_POST['id'];
			
			$paid_to				= $_POST['paid_to'];
			$paid_date				= date('Y-m-d', strtotime($_POST['paid_date']));
			$company_id				= $_POST['company_id'];
			$cash_bank_name			= $_POST['cash_bank_name'];
			$cheque_no				= $_POST['cheque_no'];
			$utr_no					= $_POST['utr_no'];
			$tds_amount				= $_POST['tds_amount'];
			$total_amount_paid		= $_POST['total_amount_paid'];
			$tds_amount_prev		= $_POST['tds_amount_prev'];
			$total_amount_paid_prev	= $_POST['total_amount_paid_prev'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$remarks_hdr			= $_POST['remarks_hdr'];
			$rtgs_narration			= $_POST['rtgs_narration'];
			$due_date 				=  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['paid_date'])));
			$st_flag 				= $_POST['st_flag']; 
			
			$tally_status			= $_POST['tally_status'];

//echo $tally_Status; exit();
			
			$net_total=0;
			$sql="update payment_header set paid_date = '$paid_date',
					company_id				= '$company_id',
					cash_bank_name			= '$cash_bank_name',
					cheque_no				= '$cheque_no',
					utr_no					= '$utr_no',
					dated					= '$dated',
					remarks					= '$remarks_hdr',
					rtgs_narration			= '$rtgs_narration',
					st_flag 				= '$st_flag'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if($tally_status=='R'){

				$sql="update payment_header set tally_ticked_by = '$user', tally_status = '$tally_status' , tally_updated_on = now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}

//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set paid_date = '$paid_date', cheque_no = '$cheque_no', status = '$tally_status'  where doc_no = '$id' and doc_type = 'PY' ";
				$r2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE

			}
			
//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set cheque_no = '$cheque_no' where doc_no = '$id' and doc_type = 'PY' ";
				$r2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE
	

			$supp_sid				= $_POST["supp_id"];
			
			$status				    = $_POST['status'];
			
			$material_name  = '';
			$party_name  = '';
			$party_email = '';

				$sql = "select * from company where comp_id = '$company_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];
								
			$mat_hdr = '';
			$bank_details = '';
			if($st_flag=='S' || $st_flag=='D' || $st_flag=='C' || $st_flag=='R' ){
				$sql="SELECT * FROM sma_party_mst where id ='$paid_to' ";
				$q2 = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$party_name  = $r2['party_name'];
				$party_email = $r2['party_email'];
				
				$party_bank_name 		= $r2['party_bank_name'];
				$party_bank_account_no 	= $r2['party_bank_account_no'];
				$party_bank_address 	= $r2['party_bank_address'];
				$party_bank_account_type = $r2['party_bank_account_type'];
				
				$bank_details = "<BR>"."Bank Details : <BR>";
				$bank_details .= "Bank Name : ". $party_bank_name. "<BR>";
				$bank_details .= "Account No. : ". $party_bank_account_no. ', ' . $party_bank_account_type . " Account<BR>";
				$bank_details .= "Branch : ". $party_bank_address. "<BR>";
				
				$user_name	 = $party_name;
		
			    $mat_hdr = 'Narration';
			}

			
//echo $sql."<BR>";
//exit();

//Start Table
				$msg_dtl='';
				if($st_flag=='S' || $st_flag=='D' || $st_flag=='C' || $st_flag=='R' || empty($st_flag)){
					$msg_dtl  = "<br><br>"."Vendor Name : ". $party_name;
				}	
				
				$msg_dtl  .= "<br><br><table cellspacing='0' border='1'>"; 
				$msg_dtl  .= '<tr><td>Invoice Date</td><td>Invoice No.</td><td>Cheque/UTR No.</td><td> Amount </td><td>'.$mat_hdr.'</td></tr>';
					
				if(sizeof($supp_sid>0)){
					$sql = "update payment_header set tds_amount = 0, total_amount_paid = 0 where id = '$id' ";
					$r2 = mysqli_query($con, $sql);
				}
				
				$pay_hdr_id = $id;
				
				for($i = 0; $i < sizeof($supp_sid); $i++){
					$py_dtl_id			= $_POST['py_dtl_id'][$i];
					$supp_id			= $_POST["supp_id"][$i];
					$invoice_date		= date('Y-m-d', strtotime($_POST["invoice_date"][$i]));
					$supplier_invoice_no= $_POST["supplier_invoice_no"][$i];
					$bal_amount			= $_POST["bal_amount"][$i];
					$payment_adjusted	= round($_POST["payment_adjusted"][$i],0);
					$deduction_head		= $_POST["deduction_head"][$i];
					$deduction_amt		= $_POST["deduction_amt"][$i];
					$deduction_head1	= $_POST["deduction_head1"][$i];
					$deduction_amt1		= $_POST["deduction_amt1"][$i];
					$retention_amt		= $_POST["retention_amt"][$i];
					
					//$actual_payment	= $_POST["actual_payment"][$i];
					$remarks_dtl		= $_POST["remarks_dtl"][$i];
					
					$bal_amount_prev	= $_POST["bal_amount_prev"][$i];
					$payment_adjusted_prev	= $_POST["payment_adjusted_prev"][$i];
					$deduction_amt_prev		= $_POST["deduction_amt_prev"][$i];
					$deduction_amt_prev1	= $_POST["deduction_amt_prev1"][$i];
					$tot_payment_adjusted_prev	= $payment_adjusted_prev;
					$tot_amount_prev 		= $tot_amount_prev + $tot_payment_adjusted_prev;
					$tot_tds_amount_prev	= $tot_tds_amount_prev + $deduction_amt_prev + $deduction_amt_prev1;			
					$actual_payment			= $payment_adjusted ;
					$tot_tds_amount			= ($deduction_amt + $deduction_amt1) - ($deduction_amt_prev + $deduction_amt_prev1);
					
					$message = '';

//echo $sql . ' ' . $payment_adjusted . ' + ' . $deduction_amt. ' ' . $deduction_amt1 . "<BR>"; exit();
					
					$sql = " delete from `payment_details` where payment_hdr_id = '$pay_hdr_id' and id = '$py_dtl_id' ";
					$r2  = mysqli_query($con, $sql);

					if( ( $payment_adjusted + $deduction_amt) >0 ){
						$srno = $pay_hdr_id;
						$sql = " insert into `payment_details` (payment_hdr_id, supp_id, invoice_date, supplier_invoice_no, bal_amount, payment_adjusted, deduction_head, deduction_head1, deduction_amt, deduction_amt1,  actual_payment, remarks, retention_amount ) values ('$srno', '$supp_id', '$invoice_date', '$supplier_invoice_no', '$bal_amount', '$payment_adjusted', '$deduction_head', '$deduction_head1', '$deduction_amt', '$deduction_amt1', '$actual_payment', '$remarks_dtl', '$retention_amt' ) ";
						$r2 = mysqli_query($con, $sql);

$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);
						
						$sql = '';
//		echo $sql. "<BR>";
//exit();
						$invoice_date = date('d-m-Y', strtotime($invoice_date));
						
					}
					
					if($st_flag=='S' || $st_flag=='R' || empty($st_flag)){
				
						$sql="SELECT a.*, b.* FROM sma_supplier_invoice_details a, sma_product b where a.si_hdr_id ='$supp_id' and b.id = a.material_id";
						$q2 = mysqli_query($con, $sql);
						while ($r2 = mysqli_fetch_array($q2)){
							$material_name  .= $r2['name'].' '.$r2['description']."<BR>";
						}
						
						$material_name = $remarks_dtl;
						
					}
					else if($st_flag=='D' ){
				
						$sql="SELECT a.*, b.* FROM sma_po_items a, sma_product b where a.purchase_id ='$supp_id' and b.id = a.product_id";
						$q2 = mysqli_query($con, $sql);
						while ($r2 = mysqli_fetch_array($q2)){
							$material_name  .= $r2['name'].' '.$r2['description']."<BR>";
						}
						
						$material_name = $remarks_dtl;
						
					}
					else if($st_flag=='C'){
						$supplier_invoice_no ='';
						$sql="Select * from sma_expenses where approval_ref_no = '$supp_id' and exp_type = 'C'";
						$q2 = mysqli_query($con, $sql);
						while($r2 = mysqli_fetch_array($q2)){
							$material_name  .= $r2['note']."<BR>";
							$invoice_date  = date('d-m-Y', strtotime($r2['dated']))."<BR>";
							$supplier_invoice_no .= $r2['invoice_no']."<BR>";
						}
					}
						
					$v_utr_no = '';	
					if(!empty($cheque_no)){
						$v_utr_no = $cheque_no . ' / '. $utr_no ; 	
					}
					else {
						$v_utr_no = $utr_no;
					}
					
					//Print remarks in Email 
					$material_name = $remarks_dtl;
					
					//$msg_dtl  .= '<tr><td>Invoice Date</td><td>Invoice No.</td><td>Cheque/UTR No.</td><td> Amount </td><td>'.$mat_hdr.'</td></tr>';
					$msg_dtl  .= '<tr><td>'. $invoice_date .'</td><td>'.$supplier_invoice_no.'</td><td>'.$v_utr_no.'</td><td  style="text-align:right;">'.moneyFormatIndia($payment_adjusted).'</td><td>'.$material_name.'</td></tr>';
					if($deduction_amt>0){
						$deduction_amt_v = ($deduction_amt * -1);
						$msg_dtl .= '<tr><td>&nbsp;</td><td>Deduction </td><td>'.$deduction_head.'</td><td style="text-align:right;"> '.bcadd($deduction_amt_v,0,2).'</td><td>&nbsp;</td></tr>';
					}
					if($deduction_amt1>0){
						$deduction_amt1_v = ($deduction_amt1 * -1);
						$msg_dtl.='<tr><td>&nbsp;</td><td>Deduction </td><td>'.$deduction_head1.' </td><td style="text-align:right;">'.bcadd($deduction_amt1_v,0,2).'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
					}
//echo $msg_dtl ; exit();
					
		//Deduction amount should deduct from bal_amount.
					$ded_amount = 0;
					if($st_flag=='T' || $st_flag=='C'){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'D' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('TE','RE','CE') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
					}
					else if($st_flag=='S' ){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'D' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('SI') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
						
					}
					
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);		
			
					if($st_flag=='T' || $st_flag=='C' || $st_flag=='S' ){
			//echo $sql;			
						$qr2   = mysqli_query($con, $sql);
						while ($res2  = mysqli_fetch_array($qr2)){
							$ded_amount      = $res2['amount'];
							$deduction_head1 = $res2['account_name'];
						}
						if($ded_amount>0){
							$ded_amount_v = ($ded_amount * -1);
							$msg_dtl.='<tr><td>&nbsp;</td><td>Deduction </td><td>'.$deduction_head1.' </td><td style="text-align:right;">'.bcadd($ded_amount_v,0,2).'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
						}
					}
					
//echo $msg_dtl ; exit();
		//Deduction amount should deduct from bal_amount.

					$net_total  = round($net_total + $payment_adjusted - ($deduction_amt + $deduction_amt1 ),2); //+ $ded_amount
					$net_deduct =  round($net_deduct + $deduction_amt + $deduction_amt1 + $ded_amount,2);
					
			//echo $sql."<BR>";
					$paid_status = 'Paid';					
					if($st_flag=='A'){
						
						$sql = " update sma_traval_approval set paid_amount = paid_amount - '$payment_adjusted_prev' + '$payment_adjusted', utr_no = '$utr_no' where id = '$supp_id' ";
						$r2 = mysqli_query($con, $sql);
						
					}
					else if($st_flag=='T'){
						
					$sql = " update sma_travel_expenses set bal_amount = bal_amount - '$payment_adjusted_prev' + '$payment_adjusted' , utr_no = '$utr_no' where id = '$supp_id' and ( exp_type = 'T' or exp_type = 'R' ) ";
						$r2 = mysqli_query($con, $sql);
						//echo $sql ; exit();
						//update sma_travel_expenses set bal_amount = bal_amount - '900.00' + '900' + 0, utr_no = '' where id = '6019' and ( exp_type = 'T' or exp_type = 'R' )
					
					}
					else if($st_flag=='C'){
						
						$sql = " update sma_travel_expenses set bal_amount = bal_amount - '$payment_adjusted_prev' + '$payment_adjusted' , utr_no = '$utr_no' where id = '$supp_id' and exp_type = 'C' ";
				//echo $sql; exit();
				
						$r2 = mysqli_query($con, $sql);
						
					}
					else if($st_flag=='S' || $st_flag=='R' || empty($st_flag)){
						
						$sql = " update sma_supplier_invoice set bal_amount = bal_amount + '$payment_adjusted_prev' - ('$payment_adjusted' ), paid_status = '$paid_status', bal_retention_amount = bal_retention_amount - '$retention_amt' where id = '$supp_id' ";
						$r2 = mysqli_query($con, $sql);
			//echo $sql;
$file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);			
						$sql = " update sma_supplier_invoice set bal_amount = 0 where id = '$supp_id' and bal_amount < 0 ";
						$r2 = mysqli_query($con, $sql);
						$sql = " update sma_supplier_invoice set bal_retention_amount = 0 where id = '$supp_id' and bal_retention_amount < 0 ";
						$r2 = mysqli_query($con, $sql);
						
						$compid ='';
				//IPC Print - SI	
						$ipc_id = 0;
						$sql = "SELECT * FROM sma_ipc where sma_invoice_no = '$supp_id' order by id desc "; 
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$ipc_id = $r2['id'];
						$compid	= $r2['sma_comp_id'];
						if($ipc_id >0){
							include "ipc_payment.php";
						}
						
					}
					else if($st_flag=='D'){
						
						$sql = "select sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)) as paid_amt from sma_po_items where purchase_id = '$supp_id' ";
						$r2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$popaid_amt = $r2['paid_amt'];
						
						$sql = " select * from sma_purchase_order where id = '$supp_id' ";
						$r2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$paid_amt = $r2['paid_amt'] - '$payment_adjusted_prev' + '$payment_adjusted' + $ded_amount ;
						
						$postatus = '';
						if($paid_amt >= $popaid_amt){
							$postatus = 'Paid';
						}
						
						$sql = "update sma_purchase_order set paid_amount = paid_amount - '$payment_adjusted_prev' + '$payment_adjusted' + $ded_amount, paid_status = '$postatus' where id = '$supp_id' ";
						$r2 = mysqli_query($con, $sql);
						
				//IPC Print -  PO 
						$sql = "SELECT * FROM sma_ipc where sma_po_no = '$supp_id' order by id desc "; 
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$ipc_id = $r2['id'];
						$compid	= $r2['sma_comp_id'];
						include "ipc_payment.php";
						
					}
					
//echo $sql."<BR>";		
					if($tot_tds_amount<0){
						$tot_tds_amount = $tot_tds_amount * -1;
					}
					
					$sql = "update payment_header set tds_amount = tds_amount + ('$deduction_amt' + '$deduction_amt1'), 
									total_amount_paid = total_amount_paid + '$payment_adjusted' - ('$deduction_amt' + '$deduction_amt1') 
								where id = '$pay_hdr_id' ";
	
					$r2 = mysqli_query($con, $sql);
//echo $msg_dtl;					
//echo $sql."<BR>";					
//exit();			

				}
				
				
				//$msg_dtl .= "<table border='1'>"; 
				$msg_dtl .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td>Net Total</td><td style="text-align:right;">'.moneyFormatIndia($net_total).'</td><td>&nbsp;</td></tr>';
				$msg_dtl  .= "</table>";
				$msg_dtl  .= $message ;
				$msg_dtl  .= $bank_details;
		
					if($st_flag=='A' ){
						
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where active = 1 and userid in ( Select draft_by from sma_traval_approval where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						$mail_to = '';
						$send_to = '$user_email';
						
					}
					else if($st_flag=='T'){
						
						$regular_exp_type = $_POST['regular_exp_type'];
						$travel_exp_type  = $_POST['travel_exp_type'];
						
						if($travel_exp_type =='T'){
							$sql   = "SELECT * FROM `sma_user` where  active = 1 and userid in ( Select draft_by from sma_travel_expenses where id = '$supp_id' and exp_type = 'T' ) ";
						}
						else if($regular_exp_type =='R'){
							$sql   = "SELECT * FROM `sma_user` where active = 1 and  userid in ( Select draft_by from sma_travel_expenses where id = '$supp_id' and exp_type = 'R' ) ";
						}
						
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						$send_to = '$user_email';
					
					}
					else if($st_flag=='C'){
						
						$sql = "SELECT * FROM `sma_party_mst` where id in ( Select emp_id from sma_travel_expenses where id = '$supp_id' and exp_type = 'C' )";
				//echo $sql;
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$mail_to = $row['party_email'];
						$party_name  = $row['party_name'];
						$party_email = $row['party_email'];
						$user_name	 = $party_name;

						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where active = 1 and  userid in ( Select draft_by from sma_travel_expenses where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						
				//echo $party_email;
				//exit();
				
					}
					else if($st_flag=='D'){
						
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where active = 1 and  userid in ( Select draft_by from sma_purchase_order where id = '$supp_id' and del!='Y' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						
						$sql   = "select * from sma_party_mst where id in ( Select to_supplier from sma_purchase_order where id ='$supp_id' and del!='Y' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$mail_to = $row['party_email'];
						$party_name = $row['party_name'];
						$party_email = $row['party_email'];
						$user_name	 = $party_name;
					
						//echo $user_email. " <=User<BR>";
						//echo $party_email. "<=PArty<BR>";
						//echo $checker_email. "<=Checker<BR>";
						//echo $approval_email. "<=approval<BR>";
						
					}
					else {
						
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where  active = 1 and userid in ( Select draft_by from sma_supplier_invoice where id = '$supp_id' and del!='Y'  ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						
						$sql   = "select * from sma_party_mst where id in ( Select suplier_name from sma_supplier_invoice where id ='$supp_id'  and del!='Y' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$mail_to = $row['party_email'];
						$party_name = $row['party_name'];
						$party_email = $row['party_email'];
						$user_name	 = $party_name;
					
						//echo $user_email. " <=User<BR>";
						//echo $party_email. "<=PArty<BR>";
						//echo $checker_email. "<=Checker<BR>";
						//echo $approval_email. "<=approval<BR>";
						
					}
						
					$sql = "SELECT a.create_by, a.create_date, a.status as status , b.username as username , b.email as email FROM `workflow_history` a, `sma_user` b where a.doc_type = 'PY' and a.doc_id = '$srno' and b.id = a.create_by order by a.id desc ";
				//echo $sql;		
						$bs 	= mysqli_query($con,$sql);
						while($bs1 	= mysqli_fetch_array($bs)){
							
							$status 		= $bs1['status'];
							if ($status == 'Submited' || $status == 'Prepared' || $status == 'Verified'){
								if(empty($checker)){
									$checker 		= $bs1['username'];
									$checker_email	= $bs1['email'];
								}
							}
							else if ($status == 'Completed' || $status == 'Approved'){
								$approval 		= $bs1['username'];
								$approval_email	= $bs1['email'];
								//HC || Nirmal || UEPL
								if(	$company_id = '4' || $company_id =  '5' || $company_id = '6' ){
									$approval_email	= '';
								}
								
							}
						}
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
				
				$msg =='';
				if($st_flag =='A'){
					$msg = 'We have paid against your Travel Advance Payment.';
					$doc_type = 'TA';
				}
				else if($st_flag =='T'){
					$msg = 'We have paid against your Travel Expenses Request.';
					$doc_type = 'TE';
				}
				else if($st_flag =='D'){
					$msg .= 'We have paid your Advance Payment against below Purchase Order Number (ID: '.$srno . ')' . ' Paid Date : ' . date('d-m-Y', strtotime($_POST['paid_date']));
					$doc_type = 'PY';
					$send_to = '$party_email';
				}
				else if($st_flag =='S' || $st_flag=='R' ){
					$msg .= 'We have paid your Payment against below invoice (ID: '.$srno . ')' . ' Paid Date : ' . date('d-m-Y', strtotime($_POST['paid_date']));
					$doc_type = 'PY';
					$send_to = '$party_email';
				}
				else if($st_flag =='C'){
					$msg .= 'We have paid your Payment against below invoice (ID: '.$srno . ')' . ' Paid Date : ' . date('d-m-Y', strtotime($_POST['paid_date']));
					$doc_type = 'CE';
					$send_to = '$party_email';
				}
				
				$msg .= '<br> Please check the below details ';
				$msg .= $msg_dtl;

//Send Mail	for UTR NO		
//	echo $user_email;
		//	exit();
				if(!empty($utr_no)){
					
					$ipc_user_email ='';
					
					if($st_flag =='S' || $st_flag =='D' || $st_flag=='R' ){
						$sql = "SELECT * FROM `sma_user` where  active = 1 and userid = ( SELECT draft_by FROM `sma_ipc` where sma_invoice_no ='$supp_id' ) ";
				//echo $sql;
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$ipc_id = $r2['id'];
						$ipc_user_email = $r2['email'];
						$ipc_user_name_by = $r2['username'];
					}
					
					$status				    = $_POST['status'];
					$email_flag			    = $_POST['email_flag'];
					if($email_flag!='Y'){
						
						include "py_mail.php";
						
						$sql  = "update payment_header set email_flag = 'Y' where id = '$pay_hdr_id' ";
						$r2   = mysqli_query($con, $sql);
						
					}
					
					$sql ="select max(id) as id from workflow_history where doc_id = '$srno' and doc_type = '$doc_type'";
					$res  = mysqli_query($con, $sql);
					$bs1  = mysqli_fetch_array($res);
					$max_doc_id = $bs1['id'];
								
					$s1  = "UPDATE workflow_history set sent_to = '$send_to' where doc_id = '$srno' and doc_type = '$doc_type' and id= '$max_doc_id' ";
					$res  = mysqli_query($con, $s1);
				}
				
				//	$msg  = 'Payment for Supplier Invoice Number : '.$supplier_invoice_no . ' ' . ' Date : ' . date('d-m-Y'). "<br>";
				//	$msg .= 'Payment Paid : ' . $payment_adjusted . "<br>";
				//	$msg .= 'Deduction under : ' . $deduction_head . ' : ' . $deduction_amt. "<br>";
				//include "py_mail.php";
			
//echo $msg;
//exit();			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];

			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/py/" . $py_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];

				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('PY', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $py_id . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/py/" . $py_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
					
					//echo $sql;
					
				}
			}
		
//exit("TESTING For...");

			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";
		
		}
		
		$id	 = $_GET['id'];
		$py_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';
//			header('Location: '.$_SERVER['REQUEST_URI']);
		}
		
		$sql="Select * from payment_header where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$py_id 	= $row['id'];
								
		$status = $row['status'];
		$del	= $row['del'];
		$email_flag = $row['email_flag'];
		$_SESSION['status'] = $row['status'];
		
		$tally_status			= $row['tally_status'];
		$tally_updated_on		= $row['tally_updated_on'];
		$tally_ticked_by = $row['tally_ticked_by'];
		
		$draft_by = $row['draft_by'];
		$readonly = '';
		if (($status == 'Submited' && $user!='Admin' ) || $status == 'Completed' ){
			$readonly = 'READONLY';
		}
		
		if($del=='Y'){
			$readonly = 'READONLY';
		}	
		
		if($_GET['active6']){
			$active_tab1 ='';
			$active_tab6 = $_GET['active6'];
		}
		else if($_GET['active5']){
			$active_tab1 ='';
			$active_tab5 = $_GET['active5'];
		}
		else {
			$active_tab1 = 'active';
			$active_tab5 = '';
		}	
		

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
            Payment
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Payment</a></li>
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
					
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                    
					<input type="hidden" name="email_flag" value="<?php echo $email_flag;?>">
					<input type="hidden" name="status" value="<?php echo $row['status'];?>">
					<input type="hidden" name="draft_by" value="<?php echo $row['draft_by'];?>">
					<input type="hidden" name="id" value="<?php echo $row['id'];?>">
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_tab1 ;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" > Payment </a></li>
						<?php if($role!='Maker'){ ?>
							<li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >Tally Journal</a></li>
						<?php } ?>	
                        <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<li><a href="neft_prn.php?sub=pdf&id=<?php echo $row['id'];?>&bank_id=<?php echo $row['cash_bank_name'];?>&r=1" class="btn btn-success"  target="_blank" >RTGS Print </a></li>
						<li><a href="voucher_prn.php?sub=pdf&id=<?php echo $row['id'];?>&vendor_id=<?php echo $row['paid_to'];?>&company_id=<?php echo $row['company_id'];?>&r=1" class="btn btn-danger"  target="_blank" >Voucher Print </a></li>
						
						<li class="<?php echo $active_tab6; ?>" ><a href="#tab_6" data-toggle="tab" class="btn btn-info" id="six_tab" >Task</a></li>
						
						<span class="pull-right" style="color:red;font-size:20px;"><b><?php echo $row['status'];?></b> </span>
						
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'index.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
					
                    </ul>
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_tab1 ;?>" id="tab_1">

						<?php $st_flag = $row['st_flag']; ?>

						<?php				
							$supplier_count  = '';
							$comid = $_SESSION['comid'];
							$sql="select  count(distinct(suplier_name), company_id) as scnt from sma_supplier_invoice where company_id in ($comid) and bal_amount > 0 and status='Completed' and total_amount > bal_amount and paid_status != 'Paid' ";
						
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$supplier_count  = $r2['scnt'];	
														
							$sql="select count(distinct(to_supplier), project) as scnt from `sma_purchase_order` WHERE advance_flag = 'Y' and project in ($comid) and paid_amount = 0 and status='Completed' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$po_count  = $r2['scnt'];
							//$po_count  = '10';
					
					//Traval Exp
							$sql="select count(distinct(emp_id), company_id) as scnt from sma_travel_expenses where exp_type = 'T' and company_id in ($comid) and status='Completed' and ( total_amount - advance_amount ) > 0 and bal_amount = 0  and del != 'Y' and company_id > 0";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$te_count  = $r2['scnt'];
							
							$sql="select count(distinct(emp_id), company_id) as scnt  from sma_travel_expenses where exp_type in ('R') and company_id in ($comid) and status='Completed' and bal_amount = 0  and del != 'Y'  and company_id > 0 ";

							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$te_count  = $r2['scnt'] + $te_count;	
							
							//$te_count  = '10';
				//echo $sql."<br>";			
							$sql="select  count(distinct(emp_id), company_id) as scnt from sma_traval_approval where company_id in ($comid) and advance_amount > 0 and paid_amount = 0 and (status='Booked' || status='Completed' ) ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$tr_count  = $r2['scnt'];	
							//$tr_count  = '10';
				//echo $sql."<br>";
				
							$sql="select count(distinct(emp_id), company_id) as scnt  from sma_travel_expenses where exp_type in ('C') and company_id in ($comid) and status='Completed' and bal_amount = 0  ";

							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$ce_count  = $r2['scnt'];
							
							
							
							$sql="SELECT count(*) as utr_cnt FROM `payment_header` where utr_no='' and draft_by = '$user' and status !='Completed' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$utr_count = $r2['utr_cnt'];
							
							$reten_count  = '';
							$comid = $_SESSION['comid'];
							$sql="select  count(distinct(suplier_name), company_id) as scnt from sma_supplier_invoice where company_id in ($comid) and company_id > 0 and bal_retention_amount > 0 and status='Completed' and bal_retention_amount <= retention_amount  ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$reten_count  = $r2['scnt'];	
														
						?>
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Payment Against</label>
							<div class="col-lg-10" style="padding-top: 6px;">
								<span class="label label-warning" style="font-size:14px;color:white;" >
								
								<input type="radio" name='st_flag' id='st_flaga' <?php echo ($st_flag=='S')?"CHECKED":''; ?>  value='S' > Supplier Invoice &nbsp;
									<?php if($supplier_count > 0){ ?>
										<span class="label123 label-warning123" ><?php echo "<b>&nbsp;".$supplier_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
								
								</span>&nbsp;
								
								<span class="label label-info" style="font-size:14px;color:white;" >
								<input type="radio" name='st_flag' id='st_flagd' <?php echo ($st_flag=='D')?"CHECKED":''; ?>  value='D' > Supplier Advance&nbsp;&nbsp;
								
									<?php if($po_count > 0){ ?>
										<span class="label123 label-info123" ><?php echo "<b>&nbsp;".$po_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
								</span>&nbsp;
								
								<span class="label label-success " style="font-size:14px;color:white;" >
								<input type="radio" name='st_flag' id='st_flagb' <?php echo ($st_flag=='A')?"CHECKED":''; ?>  value='A' > Travel Advance &nbsp;&nbsp;
									<?php if($tr_count > 0){ ?>
										<span class="label123 label-success123" ><?php echo "<b>&nbsp;".$tr_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
									
								</span>&nbsp;
								
								<span class="label label-danger" style="font-size:14px;color:white;" >
								<input type="radio" name='st_flag' id='st_flagc' <?php echo ($st_flag=='T')?"CHECKED":''; ?>  value='T' > Expenses &nbsp;&nbsp;
									<?php if($te_count > 0){ ?>
										<span class="label123 label-danger123" ><?php echo "<b>&nbsp;".$te_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
								</span>&nbsp;
								
								<span class="label label-primary " style="font-size:14px;color:white;" >
								<input type="radio" name='st_flag' id='st_flago' value='C'  <?php echo ($st_flag=='C')?"CHECKED":''; ?> > Operating Exp. &nbsp;&nbsp;
									<?php if(ce_count){ ?> 
										<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$ce_count."&nbsp;</b>" ;?></span>
									<?php } ?>
								</span>&nbsp;
								
								<span class="label label-info " style="font-size:14px;color:white;" >
								<input type="radio" name='st_flag' id='st_flagr' value='R' <?php echo ($st_flag=='R')?"CHECKED":''; ?> > Retention&nbsp;&nbsp;
								<?php if($reten_count>0){ ?> 
								<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$reten_count."&nbsp;</b>" ;?></span>
									</span>
								<?php } ?>
								
								<?php if($utr_count>0){ ?> 
								<br><br>
									<span class="label label-danger " style="font-size:14px;color:white;" >
										<label class="control-label"> UTR Blank</label>&nbsp;&nbsp;
										<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$utr_count."&nbsp;</b>" ;?></span>
									</span>
								<?php } ?>
								
								<input type="hidden" name='st_flag' value='<?php echo $st_flag; ?>' >
								
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" value="<?php echo $row['id'];?>" >
							</div>
							
							<?php $dated =  date('d-m-Y', strtotime($row['dated']));?>
							
							<div class="col-md-2">
								<label class="control-label">Prepared Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $dated; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
							
							<div class="col-sm-4">
								<?php 
									$_SESSION['company'] = $row['company_id'];
								?>
								<label for="company_id" class="control-label">Company</label>
								<select class="form-control select2" name="company_id" id="company_id" readonly onchange="getsupplier(this.value)" >
								<option value=""> Select </option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Paid To</label>
								
									<?php
									$paid_to = $row['paid_to'];
							//echo $paid_to. ' ' . $st_flag;
							
									if($st_flag=='A' || $st_flag=='T'){
										$sql = "SELECT * FROM sma_user where  active = 1 and id ='$paid_to' ";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r2 = mysqli_fetch_array($q2);
										$paid_userid = $r2['id'];
									}
									else {
										$sql = "SELECT * FROM sma_party_mst where id ='$paid_to' ";	
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r2 = mysqli_fetch_array($q2);
										$paid_userid = $r2['id'];
									}
									?>
								<span id="getsupplier">									
									<input type="hidden" class="form-control" id="paid_To" name="paid_to" readonly value="<?php echo $row['paid_to'] ?>" >
									<?php if( ($st_flag=='S'  || $st_flag=='R' || $st_flag=='D' || $st_flag=='C' ) ||empty($st_flag)){ ?>
									<input type="text" class="form-control"  name="paid_to_name" autocomplete="off" readonly value="<?php echo $r2['party_name'] ?>" >
									<?php }
										else if($st_flag=='A' || $st_flag=='T'){ ?>
									<input type="text" class="form-control"  name="paid_to_name" autocomplete="off" readonly value="<?php echo $r2['username'] ?>" >
									<?php } ?>
								</span>
								
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-4">
								<label class="control-label">Paid via</label>
										<select class="form-control" id="cash_bank_name" name="cash_bank_name" <?php echo $readonly; ?>  required >
											<option value="">Select</option>	
										<?php
											$sql="SELECT * FROM account_mst where account_type = 'B' and del !='Y' ORDER BY account_name ASC";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['cash_bank_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['account_name'] ?></option>
											<?php } ?>
										</select>
								
							</div>
												
							<div class="col-md-2">
								<label class="control-label">Total Amount</label>
								<input type="hidden" class="form-control" id="total_amount_paid_prev" style="text-align:right;" name="total_amount_paid_prev" value="<?php echo $row['total_amount_paid_prev'];?>">
								<input type="text" class="form-control" id="total_amount_paid" autocomplete="off" style="text-align:right;" name="total_amount_paid" readonly value="<?php echo number_format($row['total_amount_paid'],2);?>" >
							</div>

					<?php 	
							$rdonly =  '';
							$utr_no 	= $row['utr_no'];
							if(!empty($utr_no) && $user!='Admin' ){ $rdonly = "READONLY";} 
					?>
							 		
							<div class="col-md-4">
								<label class="control-label">RTGS Narration</label>
								<textarea rows="3" class="form-control" id="rtgs_narration" name="rtgs_narration" autocomplete="off" <?php echo $rdonly; ?> ><?php echo $row['rtgs_narration'];?></textarea>
							</div>
							
							
						</div>

						
						<div class="form-group">
							
							<?php 
								
								$paid_date 	= date('d-m-Y', strtotime($row['paid_date']));
							?>
							
							
							<label class="col-md-1 control-label">Paid/Chq.Date</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="paid_date" name="paid_date" autocomplete="off" <?php echo $rdonly; ?> value="<?php echo $paid_date; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
								
								<label class="col-md-2 control-label">Cheque Number</label>
							<div class="col-md-3">
							<?php //if($status == 'Completed'){	
							
								if ( $role == 'Maker' || $role == 'Accountant' || $role =='Checker - Account' || $user=='Admin' ){
							 ?>	
								<input type="text" class="form-control" id="cheque_No" name="cheque_no" autocomplete="off" <?php echo $rdonly; ?> value="<?php echo $row['cheque_no'];?>"  >
							<?php }
								//else { 
							?>
								<!--<input type="text" class="form-control" id="cheque_No" readonly name="cheque_no" placeholder="" value="<?php echo $row['cheque_no'];?>" >-->
								
							<?php //}
							//}
							?>
							</div>
							
							<label class="col-md-1 control-label">UTR.Number</label>
							<div class="col-md-3">
							<?php //if($status == 'Completed'){	
								$utr_no = $row['utr_no'];
								$rdonly = '';
								if(!empty($utr_no)){ $rdonly = "READONLY";}
							 	
								if ($role == 'Maker' || $role == 'Accountant' || $user=='Admin' || $role =='Checker - Account' ){
							?>	
									<input type="text" class="form-control" id="utr_No" name="utr_no" <?php echo $rdonly; ?>autocomplete="off" value="<?php echo $row['utr_no'];?>" >
							<?php }
								else {
							?>
								<input type="text" class="form-control" id="utr_No" readonly name="utr_no" value="<?php echo $row['utr_no'];?>" >
							<?php }
							
								
							?>
								
							</div>
							
						</div>
							<?php			
							if($st_flag=='A'){
								$label_n="Travel Req.No.";
								$label_n="Mode of Expnses";
							}
							else if($st_flag=='T'){
								$label_n="Rebursement No.";
								$label_n="Mode of Expnses";
							}
							else if($st_flag=='D'){
								$label_n = "Purchase Order No";
							}
							else if($st_flag=='S'){
								$label_n="Supp.Inv.No.";
							}
							else if($st_flag=='C'){
								$label_n="Operating Exp.";
							}
							else if($st_flag=='R'){
								$label_n="Retention";
							}
							
							
							?>
						<span id="getinvoice">
							
						<div class="box">
							<table id="prtable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Dated</th>
									<th><?php echo 'SrNo' ;?></th>	
									<th><?php echo $label_n;?></th>	
									<th style="text-align:right;">Balance Payment</th>
									<th style="text-align:right;">Payable</th>
									
									<th>Deduction Head</th>
									<th style="text-align:right;">Deduction</th>
									<th style="text-align:right;">Paid</th>
									<th>Narration</th>
									<th>Document View</th>
								</tr>
							</thead>
							<tbody>
						<?php
						
								$payment_hdr_id = $row['id'];
								$sql="SELECT * from payment_details where payment_hdr_id = '$py_id' order by id desc";
						//echo $sql;					
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$value="";
								while($key1 = mysqli_fetch_array($result)){
									$supp_id 				= $key1["supp_id"];
									$supplier_invoice_no 	= $key1["supplier_invoice_no"];
									$invoice_date 			= $key1["invoice_date"];
									
									$actual_payment = $key1['payment_adjusted'] - $key1['deduction_amt'] - $key1['deduction_amt1'];
									$tot_actual_payment		= $tot_actual_payment + $actual_payment; 
									
									$tot_payment_adjusted = $tot_payment_adjusted + $key1['payment_adjusted'] ;
									$tot_deduction_amt 	  = $tot_deduction_amt + $key1['deduction_amt'] + $key1['deduction_amt1'];
								
								$supp_id = $key1['supp_id'];
								$si_total_amount =0;
								 if($st_flag=='S' || $st_flag=='R' ){
									$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' ";		
							//echo $sql;		
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$bal_amount = 0;
									$si_total_amount = $r2['total_amount'];
									$bal_amount 	 = $r2['bal_amount'];
									$our_po_ref_no 	 = $r2['our_po_ref_no'];
									$bal_retention_amount = $r2['bal_retention_amount'];
									$retention_amount = $r2['retention_amount'];
									if($st_flag=='R' && $bal_retention_amount>0){
										$bal_amount = 0;
										$bal_amount = $retention_amount - $bal_retention_amount;
									}
						//
						//	Deduction amount should deduct from bal_amount.
									$sql = "SELECT * FROM `tally_journal_entry` a  , account_mst b where b.id = a.account_id and b.account_type = 'D' and a.doc_Type = 'SI' and a.account_type = 'A' and effect = 'Cr' and doc_no = '$supp_id' ";
						//echo $sql;
						
									$qr2   = mysqli_query($con, $sql);
									while ($res2  = mysqli_fetch_array($qr2)){
										$ded_amount  = $res2['amount'];
										$bal_amount  = $bal_amount - $ded_amount ;
									}
									if($bal_amount<1){
										$bal_amount =0;
									}
						//echo $bal_amount. ' <<>> '. $ded_amount;			
						//		
									
								 }
								 
								 if($st_flag=='D' ){
									$sql = "SELECT a.purchase_id as purchse_id, 
												round(sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as total_amount, 
												b.dated as invoice_date, 
												b.po_number as po_number, 
												b.paid_amount as paid_amount, 
												b.advance_flag as advance_flag
											FROM `sma_po_items` a, sma_purchase_order b 
												where a.purchase_id = b.id and a.purchase_id = '$supp_id' and b.del!='Y' 
														group by a.purchase_id ";
							//$sql = "SELECT * FROM sma_purchase_order where id = '$supp_id' ";		
							//echo $sql;		
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$total_amount 	= $r2['total_amount'];
									$paid_amount 	= $r2['paid_amount'];
									$bal_amount 	= $total_amount - $r2['paid_amount'];
									$our_po_ref_no 	= $r2['purchse_id'];
									
									if ($paid_amount ==0){
										$bal_amount = 0;
									}	
								 }
					//echo  $st_flag. "<<>>>";			 
								if( $st_flag=='C' ){
									$sql = " SELECT * FROM sma_travel_expenses where exp_type= 'C' and id = '$supp_id' ";		
							//echo $sql;		
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$bal_amount = 0;
									$bal_amount = $r2['total_amount'] - $r2['bal_amount'];
						//echo $bal_amount. ' ' . $r2['total_amount']. "<BR>";			
						//	Deduction amount should deduct from bal_amount.
									$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where b.id = a.account_id and b.account_type = 'D' and a.doc_Type = 'CE' and a.account_type in ('D','A') and effect = 'Cr' and doc_no = '$supp_id' ";
						//echo $sql;			
									$qr2   = mysqli_query($con, $sql);
									while ($res2  = mysqli_fetch_array($qr2)){
										$ded_amount  = $res2['amount'];
										$bal_amount  = $bal_amount - $ded_amount ;
									}
									if($bal_amount<=1){
										$bal_amount=0;
									}	
						
								}
								
								if( $st_flag=='S' || $st_flag=='D' || $st_flag=='R' ){
									
									$sql = "SELECT * FROM sma_purchase_order where (po_number = '$our_po_ref_no' or id = '$our_po_ref_no' ) and del!='Y' ";
							//echo $sql;
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$po_id = $r2['id'];
									$approval_memo_ref 		= $r2['approval_memo_ref'];
									$location	 			= $r2['location'];
									$comp_id				= $r2['project'];
									
									$sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref' and del!='Y'  ";
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$ap_id = $r2['id'];
									//$comp_id	= $r2['company'];
									
									$compid ='';
									if( $st_flag=='S' ){
										$sql = "SELECT * FROM sma_ipc where sma_invoice_no = '$supp_id' and del!='Y' order by id desc "; //|| sma_invoice_no = '$supp_id' 
							//echo $sql;
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$ipc_id = $r2['id'];
										$compid	= $r2['sma_comp_id'];
									}
									else if( $st_flag=='R' ){
										$sql = "SELECT * FROM sma_ipc where sma_invoice_no = '$supp_id' and sma_inv_adv = 'R' and del!='Y' order by id desc "; //|| sma_invoice_no = '$supp_id' 
							//echo $sql;
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$ipc_id = $r2['id'];
										$compid	= $r2['sma_comp_id'];
									}
									else if( $st_flag=='D' ){
										$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and del!='Y' order by id desc "; //|| sma_invoice_no = '$supp_id' 
							//echo $sql;
										/* $q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$ipc_id = $r2['id'];
										$compid	= $r2['sma_comp_id'];
										 */
										$ipc_link ='';
										$q2  = mysqli_query($con, $sql);
										while($r2  = mysqli_fetch_array($q2)){
											$ipc_id = $r2['id'];
											$compid	= $r2['sma_comp_id'];
											$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
											$ipc_link .= "<a href='". $baseurl_ipc."' target='_blank'><span class='label label-info'>IPC $ipc_id</span></a> ";
										}
										
									}
								  
								}
								  
									$invoice_date = date('d-m-Y', strtotime($key1['invoice_date']));
									if($invoice_date =='01-01-1970'){
										
										$invoice_date ='';
										
									}
									
							?>

								<?php 
									//Travel Reimbursement
									
										$approval_ref_no = $supp_id;
									
										if($st_flag=='T' || $st_flag=='C'){
											
											$tr_exp_amount ='';
											$sql = "SELECT * FROM sma_travel_expenses where id = '$approval_ref_no' and del!='Y'  ";
									//echo $sql;		
											$q2  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_assoc($q2)){
												$app_ref_no = $r2['approval_ref_no'];
												$approval_memo_ref = $r2['approval_number'];
												$bal_amount = $r2['bal_amount'];
										//echo $bal_amount. "<BR>";		
												//$paid_userid = $r2['draft_by'];
											}
											
											$sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref' and del!='Y'  ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$ap_id = $r2['id'];
											
											$sql = "SELECT * FROM `sma_departure` where approval_ref_no = '$approval_ref_no' and spend_by = 'O' and approval_ref_no in (SELECT id FROM `sma_travel_expenses` where emp_id = '$paid_userid' and status = 'Completed' and del!='Y' ) ";
											
											$q2  = mysqli_query($con, $sql);
										//echo $sql. "<br>";
												
											while($r2 = mysqli_fetch_assoc($q2)){
												
												$tot_amount += $r2['fare'];
												$tr_exp_amount += $r2['amount'];
												//$bal_amount =  $tr_exp_amount;	
											}
											
											if($st_flag=='T' ){
												$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$approval_ref_no' and exp_type = 'T' and approval_ref_no in (SELECT id FROM `sma_travel_expenses` where exp_type = 'T' and emp_id = '$paid_userid' and status = 'Completed') and spend_by = 'O' ";
											}
											else if ($st_flag=='C'){
												$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$approval_ref_no' and exp_type = 'C' ";
											}
											$q2  = mysqli_query($con, $sql);
										//echo $sql. ' '. $tot_amount ."<br>";		
												
											while($r2 = mysqli_fetch_assoc($q2)){
												
												$tot_amount += $r2['amount'];
												$tr_exp_amount += $r2['amount'];
												$bal_amount = $bal_amount - $r2['amount'];
												//echo $bal_amount. "<BR>";
											}

									if($st_flag=='T'){	
							//	Deduction amount should deduct from bal_amount.
										$sql = "SELECT * FROM `tally_journal_entry` a  , account_mst b 
										where b.id = a.account_id and b.account_type = 'D' and a.doc_Type = 'TE' and a.account_type = 'D' and effect = 'Cr' and doc_no = '$supp_id' ";
						
										$qr2   = mysqli_query($con, $sql);
										while ($res2  = mysqli_fetch_array($qr2)){
											//$ded_amount  = $res2['amount'];
											//$bal_amount  = $bal_amount - $ded_amount ;
										}
									}
							//
									if($bal_amount<=1){
										$bal_amount=0;
									}	
								
											//$baseurl_tr_req = $baseurl . "travel_approval/traval_app.php?sub=edit&id=$approval_ref_no" ;
											$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$approval_ref_no" ;
											
											$textar = 'Request';
											
											//$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&approval_ref_no=$approval_ref_no" ;
											$baseurl_tr = $baseurl . "travel_approval/travel_exp_repo.php?sub=pdf&id=$approval_ref_no" ;
											
											$texta = 'Expences';
										}
										
										if($st_flag=='A'){
											$sql = "SELECT * FROM sma_traval_approval where id = '$approval_ref_no' and del!='Y'  ";
									//echo $sql;		
											$q2  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_assoc($q2)){
												$advance_amount = $r2['advance_amount'];
												
											}
										
											$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$approval_ref_no" ;
											$textar = 'Request';
										}
									
									if($st_flag=='T'){
									
										$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$approval_ref_no' and exp_type = 'R' and approval_ref_no in (SELECT id FROM `sma_travel_expenses` where exp_type = 'R' and emp_id = '$paid_userid' and status = 'Completed' and del!='Y' )";
									//echo $sql. ' '. $tot_amount . "<br> ###2.";			
										$q2  = mysqli_query($con, $sql);
										$regular_exp_total = 0 ;
										$supplier_invoice_no = '';
										while($r2 = mysqli_fetch_assoc($q2)){
												
											$tot_amount += $r2['amount'];
											$regular_exp_total += $r2['amount'];
											$bal_amount 		= $bal_amount - $regular_exp_total;
											$supplier_invoice_no .= $r2['invoice_no'].' ';
										}
									
							//	Deduction amount should deduct from bal_amount.
										$sql = "SELECT * FROM `tally_journal_entry` a  , account_mst b where b.id = a.account_id and b.account_type = 'D' and a.doc_Type = 'RE' and a.account_type = 'D' and effect = 'Cr' and doc_no = '$supp_id' ";
							//echo $sql;			
										$qr2   = mysqli_query($con, $sql);
										while ($res2  = mysqli_fetch_array($qr2)){
											//$ded_amount  = $res2['amount'];
											//$bal_amount  = $bal_amount - $ded_amount ;
										}
									}
							//
										
									//echo $regular_exp_total;		
										$baseurl_re = $baseurl . "travel_approval/regular_expense.php?sub=edit&id=$approval_ref_no" ;
										//$baseurl_re = $baseurl . "travel_approval/regular_exp_repo.php?sub=pdf&id=$approval_ref_no";
										
							//Operating Expenses		
								if($st_flag=='C'){
									$sql = " SELECT * FROM `sma_expenses` where approval_ref_no = '$approval_ref_no' and exp_type = 'C' and approval_ref_no in (SELECT id FROM `sma_travel_expenses` where exp_type = 'C' and emp_id = '$paid_userid' and status = 'Completed' and del!='Y' ) ";
								//echo $sql;		
										$q2  = mysqli_query($con, $sql);
										$company_exp_total = 0 ;
										$supplier_invoice_no = '';
										while($r2 = mysqli_fetch_assoc($q2)){
												
											$tot_amount += $r2['amount'];
											$company_exp_total += $r2['amount'];
											$supplier_invoice_no .= $r2['invoice_no'].' ';
										}
								}	
								//	echo $sql. ' '. $company_exp_total . "<br> ###2.";	
										//$baseurl_ce = $baseurl . "travel_approval/company_exp_repo.php?sub=pdf&id=$approval_ref_no";
									 
										$baseurl_ce = $baseurl . "travel_approval/company_expense.php?sub=edit&id=$approval_ref_no";
										
									$regular_exp_type ='';
									$travel_exp_type = '';
									$operating_exp_type = '';
									$tr_label='';
									
									if($regular_exp_total>0){
										$regular_exp_type = 'R';	
										$tr_label = 'Regular Expnses';
									}
									if($tr_exp_amount>0){
										$travel_exp_type = 'T';
										$tr_label = 'Travel Expnses';
									}
									if($company_exp_total>0){
										$operating_exp_type = 'C';
										$tr_label = 'Operating Expenses';
									}
									if($advance_amount>0){
										$tr_label = 'Travel Advance';
									}
									
								if($bal_amount<=1){
									$bal_amount=0;
								}	
									
					//Travel Expnses End
							
								?>
									
								<tr>	
										<input type="hidden" name='regular_exp_type' value="<?php echo $regular_exp_type;?>">
										<input type="hidden" name='travel_exp_type' value="<?php echo $travel_exp_type;?>">
										<input type="hidden" name='operating_exp_type' value="<?php echo $operating_exp_type;?>">
										
										<input type="hidden" name='py_dtl_id[]' id='py_dtl_id' value="<?php echo $key1['id'];?>" >
										<input type="hidden" name='supp_id[]' value="<?php echo $key1['supp_id'];?>">
									
										<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
									<td width="08%"><?php echo $invoice_date;?></td>
									
									   <input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
									<td width="10%"><?php echo $key1['supp_id'];?></td>
									
									<?php if(!empty($tr_label)){ ?>
										<td width="10%"><?php echo $tr_label;?></td>
									<?php } 
									else  { ?>
										<td> <?php echo $supplier_invoice_no;?></td>
									<?php } ?>	
										
										<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
										<input type="hidden" name='bal_amount_prev[]' id='bal_amount_prev' value="<?php echo $bal_amount;?>" >
									<td width="10%" style="text-align:right;"><?php echo $bal_amount;?>
										<?php if($st_flag=='S'){ ?>
											<br><br><label  > Retention-</label>
										<?php } ?>	
									</td>
					<?php 
						if($status!='Draft'){
							$readonly1 = "READONLY";
						}	
					?>			
									<input type="hidden" class="form-control" id="payment_adjusted_prev" style="text-align:right;" 
										name="payment_adjusted_prev[]" value="<?php echo $key1['payment_adjusted'];?>" >
										
									<td width="10%"><input class="form-control col-md-2" type="text" autocomplete="off" name='payment_adjusted[]' id='payment_adjusted' 
									onblur="checkadjusted();getactual();"  style="text-align:right;" value="<?php echo $key1['payment_adjusted'];?>" <?php echo $readonly; echo $readonly1; ?>> 
									<?php if($st_flag=='S'){ ?>
										<input class="form-control col-md-2" type="text" autocomplete="off" name='retention_amt[]' id='retention_amt' style="text-align:right;" value="<?php echo $key1['retention_amount'];?>" <?php echo $readonly; ?> >
									<?php } ?>
									
									</td>
								
								<?php  $tot_amount_abc = $tot_amount_abc + $key1['payment_adjusted'] ;?>	
								
									<input type="hidden" id="total_amounT"  value="<?php echo $tot_amount_abc;?>" >
									
								<?php echo $row['deduction_head1'];?>
								
									<td width="10%">
									<select class="form-control" name="deduction_head[]" id="deduction_head" <?php echo $readonly;?> disabled  >
											<option value=""> Select </option>
											<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['account_name'];?>" <?php echo ($key1['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> ><?php echo $r2['account_name'];?></option>
											<?php } ?>
										</select>
										<select class="form-control" name="deduction_head1[]" id="deduction_head1" disabled <?php echo $readonly; ?>   >
											<option value=""> Select </option>
											<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['account_name'];?>" <?php echo ($key1['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> ><?php echo $r2['account_name'];?></option>
											<?php } ?>
										</select>
										
									</td>
									
									<input type="hidden" class="form-control" id="deduction_amt_prev[]" style="text-align:right;" name="deduction_amt_prev[]" value="<?php echo $key1['deduction_amt'];?>" disabled >
									<input type="hidden" class="form-control" id="deduction_amt_prev1[]" style="text-align:right;" name="deduction_amt_prev1[]" value="<?php echo $key1['deduction_amt1'];?>" disabled >
									<td width="10%">
										<input class="form-control col-md-2" type="text" autocomplete="off" name='deduction_amt[]' id='deduction_amt'   onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>" <?php echo $readonly; ?> disabled >
										<input class="form-control col-md-2" type="text" autocomplete="off" name='deduction_amt1[]' id='deduction_amt1'    onblur="getactual1()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>" <?php echo $readonly; ?> disabled >
										
									</td>
									
									<?php 
										$supp_id = $key1['supp_id'];
										$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										$baseurl_si_prn = $baseurl . "supp_invoice/supplier_invoice_prn.php?sub=pdf&id=$supp_id";
										
										$po_id = $po_id;
										//$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$ap_id = $ap_id;
										$company_id = $row['company_id'];
										//$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";
										$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id;&r=1";
										
										
										$po_id = $po_id;
										//$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										$baseurl_powf = $baseurl . "purchase_order/po_wf_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$ipc_id = $ipc_id;
										//$baseurl_ap = $baseurl . "grnsrn/edit.php?sub=edit&id=$srn_id";
										$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
										
									?>
									<input type="hidden" class="form-control" id="actual_payment_prev[]" style="text-align:right;" name="actual_payment_prev[]" placeholder="" value="<?php echo $key1['actual_payment'];?>" >
									<td width="12%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo number_format($actual_payment,2);?>"  ></td>
									
									<td width="10%"><textarea rows="1" autocomplete="off" <?php echo $readonly; ?> name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea>
									<?php if($si_total_amount>0){ ?>
										<BR> <b> Supplier Invoice Amt: <br><?php echo $si_total_amount ?> </b>
									<?php } ?>	
									</td>
									
									<td width="10%">
								<?php 
								if( $st_flag =='S' || $st_flag=='R'  ){ ?>
										
										<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>
										<a href="<?php echo $baseurl_si_prn;?>" target="_blank"><span class="label label-warning">Invoice Prn</span></a>
										
								<?php }
								
								if( $st_flag =='S' || $st_flag=='D' || $st_flag=='R' ){
								
								?>
										<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>
										<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-danger">Approval Notes</span></a>
									<?php
										$sql = " SELECT * FROM sma_grn_srn where our_po_ref_no = '$po_id' and del!='Y' ";//and supplier_invoice_no in (SELECT supplier_invoice_no FROM `sma_supplier_invoice` where id = '$supp_id')
										$q2  = mysqli_query($con, $sql);
										while ($r2  = mysqli_fetch_array($q2)){
											$srn_id = $r2['id'];
											$company_id = $row['company_id'];
											$baseurl_srn = $baseurl . "grnsrn/grnsrn_prn.php?sub=pdf&id=$srn_id&comp_id=$company_id;&r=1";
									?>	
											<a href="<?php echo $baseurl_srn;?>" target="_blank"><span class="label label-info">GRN-<?php echo $srn_id;?></span></a>&nbsp;
									<?php } ?>		
										<a href="<?php echo $baseurl_powf;?>" target="_blank"><span class="label label-info">PO Workflow</span></a>
									<?php if(!empty($compid)){ ?>
											<!--<a href="<?php echo $baseurl_ipc;?>" target="_blank"><span class="label label-info">IPC</span></a>-->
									<?php 
											if( $st_flag=='D' ){
												echo $ipc_link;
											}
											else {
									?>			
												<a href="<?php echo $baseurl_ipc;?>" target="_blank"><span class="label label-info">IPC</span></a>
									<?php	}
									
										} ?>
									
								<?php } ?>
									
							<?php //TRAVEL EXPENSE ?>	
										
										<!--<a href="<?php echo $baseurl_tr;?>" target="_blank"><span class="label label-warning">Travel Expenses</span></a>-->
								<?php if($advance_amount){ ?>
										<a href="<?php echo $baseurl_tr_req;?>" target="_blank"><span class="label label-warning">Travel Request</span></a>
								<?php } ?>
									
									<?php if($tr_exp_amount>0){
												
										//$baseurl_tr_req = $baseurl . "travel_approval/traval_app.php?sub=edit&id=$app_ref_no" ;
										$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$app_ref_no" ;
										
									?>
										
										<a href="<?php echo $baseurl_tr_req;?>" target="_blank"><span class="label label-warning">Travel Request</span></a>
										<a href="<?php echo $baseurl_tr;?>" target="_blank"><span class="label label-success">Travel Expenses </span></a>
									<?php } ?>
									
									<?php if($regular_exp_total>0){ ?>
											<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-info">Regular Expenses</span></a>
									<?php } ?>
									
									<?php if($company_exp_total>0){ ?>
											<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-danger">Approval Notes</span></a>
											<a href="<?php echo $baseurl_ce;?>" target="_blank"><span class="label label-info">Operating Expenses</span></a>
									<?php } ?>
									
									
							<?php }
							?>
								
									</td>
								</tr>
									
							</tbody>
							
							<tfoot>
								<tr>
									<th></th><th></th>
									<th  style="text-align:right;">Total</th>
									<th  style="text-align:right;"><?php echo $tot_payment_adjusted;?> </th>
									<th></th>
									<th></th>
									<th  style="text-align:right;"><?php echo $tot_deduction_amt;?> </th>
									<th  style="text-align:right;"><?php echo $tot_actual_payment;?> </th>
									<th></th><th></th>
									
									
								</tr>
							</tfoot>
							
							</table>
						</div>
						
						</span>
						
						
                       <!-- Attachments -->
					   <div class="box">
							<?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PY' AND reference_id = " . $py_id;
                              $docResults = mysqli_query($con, $sql);
							  $affected_rows =  mysqli_affected_rows($con);
							  
							  //echo $affected_rows;
							  if($affected_rows > 0){
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
													$delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
					                              ?>
                                          <tr>
                                              <td><?php echo $document ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
											  <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  <?php } ?>
								  
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td><label class="col-sm-1 control-label">Document</label></td>    
										<td>
                                            <select class="form-control select2 doctype" name="doctype[]"  >
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
                                            </select>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2"  placeholder="Enter document description..."></textarea>
										</td>
										<td>
											<input type="file" name="fudoc[]" class="docfile" <?php echo $readonly; ?>>
										</td>
                                         <td><button type="button" name="add" id="add_doc" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            
							</div>
						</div>
					<!-- Attachments -->
				</div>
				
				
					<?php
							$disabled = '';
							if($tally_status=='R' || $tally_status == 'U'){
								
								$disabled = "DISABLED";
								
							}	
							if($user=='Admin'){
								$disabled = '';
							}	
					?>
											
					<div class="tab-pane <?php echo $active_tab5;?>" id="tab_5">					
						
							    <div class="box123">
                                    <div class="box-header">
                                        <h4 class="box-title">Tally Journal Account</h4>
								<?php if (empty($disabled)){ ?>
                                        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
								<?php  } ?>			
                                        </span>
                                    </div>
                                    <div class="box-body">
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
								<?php if (empty($disabled)){ ?>	
										<span class="pull-right"><a href="#addLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addLine">Add </a></span>
								<?php  } ?>	
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="40%">Account Name</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;" >Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from tally_journal_entry where doc_no = '$py_id' and doc_type = 'PY' order by record_id ";	
//echo $sql;										
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date	 	 	= $r2['doc_date'];
											$supp_invoice_no  	= $r2['supp_invoice_no'];
											$supp_invoice_date  = $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$amount		   		= $r2['amount'];
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally 		= $r2['status'];
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
										<?php //if (empty($disabled) || $user=='Admin'){ ?>		
											<!--		<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
													<?php // include "edit_tally_func.php"; ?>	
													<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											-->		
										<?php // } ?>
												</td>
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = $amount_dr + $amount;
											}
											else if($effect=='Cr'){
												$amount_cr = $amount_cr + $amount;
											}

										}
										$emsg   ='';
										$stl	='';
										if($amount_dr != $amount_cr){
											$emsg = "Debit & Credit Total Mimatch...";	
											$stl  = "color:red;";
										}	
									?>
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,2); ?> </td>
												<td></td>
											</tr>
											
											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>
									
									<?php $tally_status = $row['tally_status'];
									
									?>
									
									<div class="form-group"> 
									<?php $tally_status = $row['tally_status']; ?>
									
									<div class="col-sm-4">
										<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ready to Update Status &nbsp;&nbsp;: </label>
											
									<?php if ($status == 'Completed' || $role=='Checker - Account' || $user=='Admin' || ($role=='Accountant' && $status=='Verified' ) ){ ?>
									<?php
									
										if($tally_status == 'R'){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ticked</label>';
											$rddonly = "READONLY";
										}
										else if($tally_status == 'U'){
											echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Updated to Tally</label>';
											$rddonly = "READONLY";
										}
											
										if(empty($tally_status)){
									?>		
									
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'R' || $tally_status == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="R" >
									<?php 
											$rddonly = "";
										}
										?>
										
									
									
									<?php } ?>
									
									
								
								
									</div>
									
									
									<?php 
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y h:m i', strtotime($tally_updated_on));
										}	
									?>
									
										<div class="col-sm-2">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
										</div>
										
										<div class="col-md-4">
											<label class="control-label">Tally Narration</label>
											<textarea rows="3" class="form-control" id="remarks_hdr" name="remarks_hdr" autocomplete="off" <?php echo $rddonly; ?> ><?php echo $row['remarks'];?></textarea>
										</div>
									
									
									
									</div>

										</div>
						
									</div>
								</div>
								
								
								<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous </a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_4" class="btn btn-primary" data-toggle="tab" onclick="$('#five_tab').trigger('click')" >Next</a>
								</div>
								</div>	
							
					</div>	
					
<!--PEnding TAsk -->								
					<div class="tab-pane" id="tab_6">
							
						<div class="modal-header" >
							<div class="box123">
                                    <div class="box-body">
									
								<?php if (empty($disabled)){ ?>	
										<span class="pull-right"><a href="#addTask" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addTask">Add </a></span>
								<?php  } ?>	
								
									<div id="taskentry">
									
									<!-- Enter Here -->
								
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="20%">Document Name</th>
													<th width="45%"> Task</th>
													<th width="15%">Status</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from sma_pending_task where payment_id = '$py_id' order by id ";	
//echo $sql;										
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['id'];
											$payment_id	  		= $r2['payment_id'];
											$document_id  		= $r2['document_id'];
											$pending_task		= $r2['pending_task'];
											$status_task		= $r2['status_task'];
											
											/* if($status_task=='S'){
												$status_task = 'Submit';
											}
											else if($status_task=='P'){
												$status_task = 'Pending';
											}
											else if($status_task=='C'){
												$status_task = 'Completed';
											}
											 */
											$sql="SELECT * FROM sma_module where id  = '$document_id' ";
											$q3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r3 = mysqli_fetch_array($q3);
											$module_name		= $r3['module_name'];
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $module_name ?> </td>
												<td><?php echo $pending_task ?> </td>
												<td style="text-align:left;"><select class="form-control" name="status_task" id="status_task" onBlur="savetaskstatus(this.value,'status_task','<?php echo $record_id; ?>')" onClick="showEdit(this);" > 
												<?php echo $status_task; ?> 
														<option value=''>Select</option>
														<option value='S' <?php echo ($status_task == 'S')?'selected="selected"':'';?>>Submit</option>
														<option value='P' <?php echo ($status_task == 'P')?'selected="selected"':'';?>>Pending</option>
														<option value='C' <?php echo ($status_task == 'C')?'selected="selected"':'';?>>Completed</option>
													</select>
												</td>
												<td>
										
													<!--<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;-->
													<?php // include "edit_task_func.php"; ?>	
													<a href="delete_task.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
												
												</td>
											</tr>
											
									<?php } ?>
											
										</tbody>
									</table>
									
									

										</div>
						
									</div>
								</div>
							
							
						</div>
							
							<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_4" class="btn btn-primary" data-toggle="tab" onclick="$('#forth_tab').trigger('click')" >Previous </a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									
								</div>
							</div>	
					</div>		
<!--PEnding TAsk End -->
									
					<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
							//		SELECT * FROM `workflow_history` where doc_type='PY'
									$srno = $py_id;
									
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									if($create_date	=='01-01-1970'){
										$create_date	='';
									}	
									
									$sl="SELECT * FROM sma_user where  active = 1 and id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>Remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status_w			= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												
												$sent_to='';
												$send_to = $r1['sent_to'];
												if(!empty($send_to)){
													$sent_to = ' Sent To=>'.$send_to;
												}
												$remarks 			= $r1['remarks'].$sent_to;
												
												
												$s2="SELECT * FROM sma_user where  active = 1 and id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where  active = 1 and id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status_w; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
						</div>
					
					</div>	
						
							<span id="predit"></span>
											
				</div>
											
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						<?php $did = $_GET['id']; ?>
						
						<div class="box-footer">	
						<div class="col-sm-6">
				<?php 	
					if($del=='Y'){ ?>
						
							<div class="col-sm-6">
								<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>	
							</div>			
				<?php
					}
					else if ( $status != 'Draft' && $user == 'Admin' ){	
				?>
						
								<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Make to Draft</a>
							
				<?php	
					}
							
						
					if($del!='Y'){ ?>		
						
								<?php $did = $_GET['id']; 
								if ( $status == 'Draft' || $user == 'Admin' ){
								?>	
									<!--<a href="<?php echo $baseurl.$modulePath."edit.php?id=$did&sub=delete&st_flag=$st_flag";?>" class="btn btn-danger" >Delete</a>-->
									<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>
								<?php } ?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							
							<div class="col-sm-6 text-right">
								<?php 
									$_SESSION['py_id'] 	= $py_id;
									$_SESSION['status']  = $status;
									
						//echo $status;
								?>

								<?php		
								$role = $_SESSION['role'];
								$company = $_SESSION['company'];
								// 
								if (($status == 'Submited' || $status == 'Verified') && ($role == 'HOD - Account' || $role == 'Project Manager') ){
									//echo $status. ' < ###1> ' . $role;
								?>
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } 
							
							
							/*	if (($status == 'Submited' || $status == 'Verified') {
									if ( $role == 'HOD - Account') && ( $company == '7' || $company =='10' ) ){
									//echo $status. ' < ###1> ' . $role;
								?>
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } 
								
								}
							*/
							
							//echo $status. ' <> ' . $role;
							
								else if ($status == 'Submited' && $role == 'Checker - Account'){
									//echo $status. ' <<>> ' . $role;
							
								?>
									<a href="#checkerAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#checkerAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } 
								//RAVI Today
								?>
								
								
								<?php
									if ($status == 'Draft' && ($role == 'Maker' || $role == 'Accountant' || $user == 'Admin')){
								?>
										<span>&nbsp;&nbsp;</span>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Submit</a>
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<a href="#makerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#makerAuthority">Send Back to Maker</a>
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php }

							else if ( ( $user == 'Admin' && $status != 'Draft') || ( ($role == 'Accountant' || $role =='Checker - Account' ) && empty($utr_no) && $status != 'Draft' ) || ($tally_status != 'R' && $tally_status != 'U' ) ){
								//&& $status=='Verified'
								?>
									<span>&nbsp;&nbsp;</span>
									<input class="btn btn-primary" type="submit" value="Save" name="Save">
								<?php }	
								?>
								
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back</a>
									
							</div>
						</div>

				<?php } ?>
				
				</div>
								
		</div>
				
                    </fieldset>

				</div>

<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">				
				
            </form>
					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>  
</div>



<!--Make to DraftPopup-->

<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="st_flag" id="st_flagD" value="<?php echo $st_flag; ?>" >	
										<input type="hidden" name="py_id" id="py_idD" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="st_flag" id="st_flagZ" value="<?php echo $st_flag; ?>" >	
										<input type="hidden" name="py_id" id="py_idZ" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->


<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company		 = $_SESSION['company'];
											$user_category = $_SESSION['user_category'];
								//Echo $role. ' <<>> ' . $company. ' <<>> '. $user_category;			
											$checker_sql = '';
											
											if($role=='Maker' || $role =='Accountant'){
													//BETPL / GEPL/ DBCPL Account Checker
												if($company =='8' || $company =='9' || $company =='3' || $company =='10' || $company =='7' || $company =='6'  || $company =='5' ){
													$account_role = "'Checker - Account'";
												
												}
												else if ( $company =='6' && $user_category =='S' ){
													$account_role = " 'Project Manager' " ;
												}	
												else {
													$account_role = "'HOD - Account'";
													if($company =='4' || $company =='5' || $company =='6' ){ // Highway C.
														$account_role = " 'Checker - Account', 'HOD - Account' ";
												    }
											//echo $account_role . " <<>>>";
												}
												
												$user_category = $_SESSION['user_category'];
												
												if($user_category=='S'){
													if ($checker_value <= 100000  && $company !='5') {
														$user_category = 'S';
													}
													else {
														$user_category = 'H'; 
													}
												}
												else {
													$user_category = 'H'; 
												}
												
												$checker_sql =  " and user_category = '$user_category' ";
												
											}
											else {
												$account_role = " 'HOD - Account' , 'Project Manager' " ;
												$status = 'Submited';
												$checker_sql = '';
											}
										
											//For DBCPL 
											if($company =='8' || $company =='9' || $company =='3' || $company =='7' || $company =='6' ){
												//$user_category = 'H'; 
												$user_category = " ('H', 'S') ";
												$checker_sql =  " and user_category in $user_category ";
											}
											
											if( $company == '7' || $company =='10' ){
													
												$account_role = "'HOD - Account'";	
												
											}
											
						//	echo $company . ' ' .$role . ' ' . $user_category. ' '. "<BR>";
						//	echo   $sql="SELECT * FROM sma_user where  active = 1 and FIND_IN_SET('$company', company_id)<>0 ". $checker_sql . " and role in ( select id from sma_role where role in ($account_role) )  ORDER BY first_name ASC";
							  
								?>
										<input type="hidden" name="py_id" id="py_idE" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
								
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where  active = 1 and FIND_IN_SET('$company', company_id)<>0 ". $checker_sql . " and role in ( select id from sma_role where role in ($account_role) )  ORDER BY first_name ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Narration</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
										</form>	
									</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->


<!--Maker Workflow Popup-->
<div class="modal fade" id="makerAuthority" role="dialog" aria-labelledby="makerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makerAuthority">Send To Maker </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company		 = $_SESSION['company'];
										?>
										
										
										<input type="hidden" name="st_flag" id="st_flagM" value="<?php echo $st_flag; ?>" >
										<input type="hidden" name="py_id" id="py_idM" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeM" name="mode" value='Maker'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusM" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Narration</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" required name="remarks" id="remarksM"></textarea>
											</div>
										</div>
										
										</form>	
									</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitMaker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Maker  Workflow Popup-->


<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTallyLabel">Do you want create tally journal?</h4>
            </div>
            <div class="modal-body123">
                <section class="content123">
                    <div class="row123">
                        <form class="form-horizontal" action="#" method="POST" enctype="multipart/form-data">
						
                            <input type="hidden" id="modeT" value='Tally'>
							<input type="hidden" id="py_idT" value="<?php echo $_GET['id'];?>">
						
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry"  >Yes</button>
            </div>
			
                </section>
            </div>
        </div>
    </div>
</div>


<!--Add Line Popup-->
<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="py_id" id="py_idA" value="<?php echo $py_id; ?>" >
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	<input type="radio"  id="type_acA" name="type_ac" value='B' onchange="getaccount(this.value)" > Budget
												<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Vendor
												
                                            </div>
                                        </div>
										
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)" >
														<option value="">Select</option>
													<?php
														$sql = "SELECT id, account_name as 'account_name' FROM account_mst where (account_type = 'B' or account_type = 'D' or account_type = 'A') order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'] ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectA" name="effect"  value='Dr' > Debit
												<input type="radio"  id="effectA" name="effect" checked value='Cr' > Credit
											</div>
                                        
											<div class="col-sm-6">
												<label for="approver" class="control-label">Amount *</label>
												<span class="gettdsamt">
                                            	<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" name="amount" value="" >
												</span>
                                            </div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Manual Entry Text before Box</label>
											</div>
                                        
											<div class="col-sm-6">
												<input type="text" class="form-control " id="amountABC" autocomplete="off" style="text-align:right;;" name="amounta" >
											</div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA"></textarea>
											</div>
										</div>

								</form>	

								</div>
								
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Line Popup End -->


<!--Add Task Line Popup-->
<div class="modal fade" id="addTask" role="dialog" aria-labelledby="addTask">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addTask">Add Task</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
										?>
										
										<input type="hidden" name="py_id" id="py_idP" value="<?php echo $py_id; ?>" >
										
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Document Type*</label>
													<select class="form-control select2" id="document_idA" name="document_id" required="required" >
														<option value="">Select</option>
													<?php
														$sql = "SELECT * FROM sma_module where 1 order by module_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['module_name'] ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label"> Task </label>
                                            	<textarea class="form-control" rows="2" name="pending_task" id="pending_taskA"></textarea>
											</div>
										</div>

								</form>	

								</div>
								
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitTask">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Task Popup End -->



<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company		 = $_SESSION['company'];
											
											// Only for JPEPL & SPEL
											if($company == '7' || $company =='10'){
												$status = 'Verified';
											}
											
											$sql   = "SELECT * FROM `sma_user` where active = 1 and  userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$row   = mysqli_fetch_array($query);
											$approver = $row['id'];
										?>
										<input type="hidden" name="py_id" id="py_idA" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeA" name="mode" value='Accept'>
										<input type="hidden" id="approverA" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusA" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
									<form class="form-horizontal">
										<?php
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
											$sql   = "SELECT * FROM `sma_user` where active = 1 and userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$row   = mysqli_fetch_array($query);
											$approver = $row['id'];
										?>
										<input type="hidden" id="py_idR" 	name="py_id" 	value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeR" 	name="mode"  	value='Reject' >
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>' >
										<input type="hidden" id="supp_idR"  name="supp_id" 	value='<?php echo $supp_id;?>' >
										<input type="hidden" id="st_flagR"  name="st_flag" 	value='<?php echo $st_flag;?>' >										
					
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusR" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>

									</form>
									
                                    </div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document1</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'<option value="PAN CARD">PAN Card</option><option value="AADHAAR CARD">AADHAAR Card</option></select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){          
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     //alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->


<script>

$("#submitMaker").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeM").val();
		var py_id		 	=  $("#py_idM").val();
		var st_flag		 	=  $("#st_flagM").val();
		
        var status 			=  $("#statusM").val();
		var remarks			=  $("#remarksM").val();
//alert(remarks + py_id + st_flag);
	
		 $('#makerAuthority').modal('hide');

		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						st_flag:st_flag,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

</script>


<script>


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var st_flag		 	=  $("#st_flagD").val();
		var py_id		 	=  $("#py_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + py_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						st_flag:st_flag,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var st_flag		 	=  $("#st_flagZ").val();
		var py_id		 	=  $("#py_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + py_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						st_flag:st_flag,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});


   $("#submitChecker").on("click", function(e){
        var sub = 'sub8';
		var mode		 	=  $("#modeC").val();
		var py_id		 	=  $("#py_idE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
//alert(remarks);
	
		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#checkerAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

</script>

<script>

   $("#submitApprove").on("click", function(e){
        var sub = 'sub8';
//alert(sub);
		var mode		 	=  $("#modeA").val();
		var py_id		 	=  $("#py_idA").val();
		var approver		=  $("#approverA").val();
        var status 			=  $("#statusA").val();
		var remarks			=  $("#remarksA").val();
		var paid_to			=  $("#paid_To").val();
		var cheque_no		=  $("#cheque_No").val();

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#approvalAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						cheque_no:cheque_no,
						status:status,
						paid_to:paid_to,
						remarks:remarks,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

	

   $("#submitReject").on("click", function(e){
        var sub = 'sub8';
//alert(sub);
		var mode		 	=  $("#modeR").val();
		var py_id		 	=  $("#py_idR").val();
		var approver		=  $("#approverR").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		var supp_id			=  $("#supp_idR").val();
		var st_flag		 	=  $("#st_flagR").val();

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#rejectAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						supp_id:supp_id,
						st_flag:st_flag,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
</script>

 <?php 	
		include("../footer.php");
	
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
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}
		
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables 
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>
-->

<script>
    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "py_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "py_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
		});

	}
	
</script>


<script>

	function getaccount(id){
		
        var sub    = 'sub1';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getaccount').html(result);
		});

	}
</script>


<script>

	function getaccount1(id){
		
        var sub    = 'sub11';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getaccount1').html(result);
		});

	}

</script>

<script>

	function getinvnumber(id){
		
        var sub    = 'sub2';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getinvnumber').html(result);
		});
	}

	function getdocview(id){
        var sub    = 'sub5';	
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getdocview').html(result);
		});	
		
	}

		
	function checkadjusted(){
//alert("HELLO ####1");		
		var bal_amount = document.getElementById("bal_amount").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		
//		if (parseInt(payment_adjusted) > parseInt(bal_amount) ){
//			alert(" Payment adjustment amount should not be greater then balance amount...");
//			document.getElementById("payment_adjusted").value = '';
//			return false;
//		}
		
	}
	
	function getactual(){
		var payment_adjusted_prev = document.getElementById("payment_adjusted_prev").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
//alert(payment_adjusted);		
		var deduction_amt    = document.getElementById("deduction_amt").value;
		var deduction_amt1    = document.getElementById("deduction_amt1").value;
		var actual_payment   = parseInt(payment_adjusted) - parseInt(deduction_amt) - parseInt(deduction_amt1);
		if (!isNaN(actual_payment)) {
       //  document.getElementById('txt3').value = result;
		   document.getElementById("actual_payment").value=actual_payment;
	//		alert(actual_payment);
		}
	//alert(payment_adjusted);
	}
	
	
	
	
	
</script>


<script>

	function getinvnumber(id){
		
        var sub    = 'sub2';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getinvnumber').html(result);
		});
	}

	function getdocview(id){
        var sub    = 'sub5';	
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getdocview').html(result);
		});	
		
	}

		
	function checkadjusted(){
//alert("HELLO ####1");		
		var bal_amount = document.getElementById("bal_amount").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		
//		if (parseInt(payment_adjusted) > parseInt(bal_amount) ){
//			alert(" Payment adjustment amount should not be greater then balance amount...");
//			document.getElementById("payment_adjusted").value = '';
//			return false;
//		}
		
	}
	
	function getactual(){
		var payment_adjusted_prev = document.getElementById("payment_adjusted_prev").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
//alert(payment_adjusted);		
		var deduction_amt    = document.getElementById("deduction_amt").value;
		var deduction_amt1    = document.getElementById("deduction_amt1").value;
		var actual_payment   = parseInt(payment_adjusted) - parseInt(deduction_amt) - parseInt(deduction_amt1);
		if (!isNaN(actual_payment)) {
       //  document.getElementById('txt3').value = result;
		   document.getElementById("actual_payment").value=actual_payment;
	//		alert(actual_payment);
		}
	//alert(payment_adjusted);
	}
	

	
</script>

<script type="text/javascript">
 var urlmenu = document.getElementById( 'menu1' );
 urlmenu.onchange = function() {
      window.open(  this.options[ this.selectedIndex ].value );
 };

function getsupplier(id){
	
		var sub    = 'sub6';
//alert(sub);
var st_flag = '';
		if (document.getElementById('st_flaga').checked) {
		    st_flag = document.getElementById('st_flaga').value;
		}
		if (document.getElementById('st_flagd').checked) {
		    st_flag = document.getElementById('st_flagd').value;
		}
		if (document.getElementById('st_flagb').checked) {
		    st_flag = document.getElementById('st_flagb').value;
		}
		if (document.getElementById('st_flagc').checked) {
		    st_flag = document.getElementById('st_flagc').value;
		}
		if (document.getElementById('st_flago').checked) {
		    st_flag = document.getElementById('st_flago').value;
		}
		
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,st_flag:st_flag,sub6:sub},function(result){
		      $('#getsupplier').html(result);
		});
	
	}
	
	function getinvoice(id){
	
		var sub    = 'sub4';

		var company_id = document.getElementById("company_Id").value;
//alert(company_id);		
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub4:sub},function(result){
		      $('#getinvoice').html(result);
		});
		
	}
		
</script>

<script type="text/javascript">
 var urlmenu = document.getElementById( 'menu1' );
 urlmenu.onchange = function() {
      window.open(  this.options[ this.selectedIndex ].value );
 };

 </script>

<script>

		
$("#submitTask").on("click", function(e){
		
        var sub 			= 'sub1';
		var document_id 	= $("#document_idA").val();
		var module_name 	= $("#document_idA option:selected").html();
		var pending_task 	= $("#pending_taskA").val();
		var py_id 			= $("#py_idP").val();		

//alert(py_id + module_name + ' ' + document_id + ' ' + pending_task);

		$('#addTask').modal('hide');
		var strURL 		= "task_func.php";
		$.post(strURL,{ document_id:document_id,module_name:module_name,pending_task:pending_task,py_id:py_id,sub1:sub},
							function(result){
		      $('#taskentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

		
$("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub1';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		
		var amount 		= $("#amountA").val();
		var narration 	= $("#narrationA").val();
		var py_id 	= $("#py_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert(py_id + account_name + ' ' + type_ac + ' ' + effect);

		$('#addLine').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ type_ac:type_ac,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,py_id:py_id,sub1:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });


    $("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub2';
		var mode 	= $("#modeT").val();
		var py_id 	 =  $("#py_idT").val();		
//alert(py_id);
		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ mode:mode,py_id:py_id,
							sub2:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });
	  
	  
	function getaccount(id){
		
        var sub    = 'sub3';
//alert(sub + ' ' + id);
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getaccount').html(result);
		});

	}	  
	
	
	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#total_amounT").val();
		var re_id	 	= $("#re_idA").val();
		var exp_type 	= 'C';
		//var amount_cgst = $("#amount_CGST").val();
		
//alert(sub + ' ' + id + ' ' +  amount_dr); 
//amount_cgst:amount_cgst,
		var strURL = "ce_func.php";
		$.post(strURL,{id:id,amount_dr:amount_dr,re_id:re_id,exp_type:exp_type,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}
	
	
	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
	function savetaskstatus(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
	//	alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetaskstatus.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }
	   
	   
</script>
	

</body>
</html>


<?php


?>