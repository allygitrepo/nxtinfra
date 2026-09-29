<?php
//	session_start();
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/*
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
 
	
	//require '../PHPMailerAutoload.php';
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
	$mail = new PHPMailer;
	
	$upload_error ='';
	$doc_no_prev ='';
	$message =' Data Updated to Tally';
	
	
	$sql = "SELECT a.*, b.supp_id, b.invoice_date,b.supplier_invoice_no, b.remarks FROM `payment_header` a, payment_details b 
			WHERE a.id = b.payment_hdr_id and ( utr_upd_flag = 'Y' )"; //|| a.id in ('6464','6449')

//echo $sql."<BR>"; exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$payment_id 			= $row['id'];
		$srno		 			= $row['id'];
		$company_id				= $row['company_id'];
		$paid_to 				= $row['paid_to'];
		$st_flag				= $row['st_flag'];
		$paid_date				= date('d-m-Y', strtotime($row['paid_date']));
		$supp_id				= $row['supp_id'];
		$remarks_dtl			= $row["remarks"];
		$invoice_date			= date('d-m-Y', strtotime($row['invoice_date']));
		$utr_no					= $row['utr_no'];
		$cheque_no				= $row['cheque_no'];
		$supplier_invoice_no	= $row['supplier_invoice_no'];
		
		$sql = "SELECT * FROM `tally_journal_entry` where doc_no= '$payment_id' and doc_type = 'PY' and account_type = 'V' and effect = 'Dr' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($py = mysqli_fetch_array($res)){;
		
			$payment_adjusted				= $py['amount'];
		}	
		
		$sql = "SELECT * FROM `tally_journal_entry` where doc_no= '$payment_id' and doc_type = 'PY' and effect = 'Cr' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($py = mysqli_fetch_array($res)){;
		
			$amount				= $py['amount'];
			$account_id			= $py['account_id'];
			
			$sql = "select * from account_mst where id = '$account_id' ";
			$acc = mysqli_query($con,$sql);
			$acrow= mysqli_fetch_array($acc);
			$account_ty = $acrow['account_type'];
			
			if($account_ty =='D'){
				$deduction_amt		= $amount;	
				$deduction_head		= $acrow['account_name'];
			}
			
		}
			
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
			
		$msg_dtl='';
		$msg ='';
		$body = '';
				if($st_flag=='S' || $st_flag=='D' || $st_flag=='C' || $st_flag=='R' || empty($st_flag)){
					$msg_dtl  = "<br><br>"."Vendor Name : ". $party_name;
				}	
				
				$msg_dtl  .= "<br><br><table cellspacing='0' border='1'>"; 
				$msg_dtl  .= '<tr><td>Invoice Date</td><td>Invoice No.</td><td>Cheque/UTR No.</td><td> Amount </td><td>'.$mat_hdr.'</td></tr>';
					
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
					
					
		
		$ded_amount = 0;
		if($st_flag=='T' || $st_flag=='C'){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'D' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('TE','RE','CE') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
		}
		else if($st_flag=='S' ){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'D' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('SI') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
						
		}
				
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
					
		$net_total = $payment_adjusted - $deduction_amt	- $ded_amount;
		
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
						
						
						$sql   = "SELECT * FROM `sma_user` where  active = 1 and userid in ( Select draft_by from sma_travel_expenses where id = '$supp_id' ) ";
						
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name_by = $row['username'];
						$send_to = '$user_email';
					
		}
		else if($st_flag=='C'){
						
						$sql = "SELECT * FROM `sma_party_mst` where id in ( Select emp_id from sma_travel_expenses where id = '$supp_id' and exp_type = 'C' )";
				
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
		$msg ='';
				if($st_flag =='A'){
					$msg = 'We have paid against your Travel Advance Payment.';
					$doc_type = 'TA';
				}
				else if($st_flag =='T'){
					$msg = 'We have paid against your Travel Expenses Request.';
					$doc_type = 'TE';
				}
				else if($st_flag =='D'){
					$msg .= 'We have paid your Advance Payment against below Purchase Order Number (ID: '.$srno . ')' . ' Paid Date : ' . $paid_date;
					$doc_type = 'PY';
					
				}
				else if($st_flag =='S' || $st_flag=='R' ){
					$msg .= 'We have paid your Payment against below invoice (ID: '.$srno . ')' . ' Paid Date : ' . $paid_date;
					$doc_type = 'PY';
					
				}
				else if($st_flag =='C'){
					$msg .= 'We have paid your Payment against below invoice (ID: '.$srno . ')' . ' Paid Date : ' . $paid_date;
					$doc_type = 'CE';
					
				}
				
				$msg .= '<br> Please check the below details ';
				$msg .= $msg_dtl;
//echo $msg ."  #####1 <BR>"; 				
				
		$sql = "SELECT a.create_by, a.create_date, a.status as status , b.username as username , b.userid as user_id, b.email as email FROM `workflow_history` a, `sma_user` b where a.doc_type = 'PY' and a.doc_id = '$payment_id' and b.id = a.create_by order by a.id desc ";
//echo $sql;		
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
							
			$status 		= $bs1['status'];
			if ($status == 'Draft' ){
				
					$drafyby 		= $bs1['username'];
					$drafyby_email	= $bs1['email'];
				
			}
			if ($status == 'Submited' ){
				if(empty($checker)){
					$checker 		= $bs1['username'];
					$checker_email	= $bs1['email'];
					$checker_id		= $bs1['user_id'];
				}
			}
			else if ( $status == 'Prepared' || $status == 'Verified'){
				
					$verified 		= $bs1['username'];
					$verified_email	= $bs1['email'];
				
			}
			else if ($status == 'Completed' || $status == 'Approved'){
				$approval 		= $bs1['username'];
				$approval_email	= $bs1['email'];
				//HC || Nirmal || UEPL
				if(	$company_id == '4' || $company_id ==  '5' || $company_id == '6' ){
					$approval_email	= '';
				}

			}

		}
		
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_name = $r2['comp_name'];
				
		if($st_flag =='S' || $st_flag =='D' || $st_flag=='R' ){
			$sql = "SELECT * FROM `sma_user` where  active = 1 and userid = ( SELECT draft_by FROM `sma_ipc` where sma_invoice_no ='$supp_id' ) ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$ipc_id = $r2['id'];
			$ipc_user_email = $r2['email'];
			$ipc_user_name_by = $r2['username'];
		}

		$user = $_SESSION['user'];
		
 /* echo $user_email. " <=User<BR>";
echo $party_email. " <=Party<BR>";
echo $checker_email. " <=Checker<BR>";
echo $approval_email. " <=approval<BR>";
echo $ipc_user_email. " <=IPC <BR>";	
echo $drafyby_email . " <= Draft By";
echo $verified_email . " <= verified By"; */
//echo $msg;
//exit('STOP HERE....');		

 		$sql = "UPDATE `payment_header` set utr_upd_flag ='' where id = '$payment_id' ";
//echo $sql."<BR>";		
		mysqli_query($con,$sql); 
		$error  = mysqli_error($con);

//echo $msg.' #####123 <BR>';

		include "utr_mail.php";

//exit('STOP HERE....');

	}

echo "Process Over...";
echo "<script>window.close();</script>";
exit('STOP HERE....');


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
		
	
	
