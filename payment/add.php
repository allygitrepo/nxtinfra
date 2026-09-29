<?php
include("../header.php");
$modulePath = "payment/";

	$help_code = $modulePath.'index.php';
	include "../help_code.php";

	$role		= $_SESSION['role'];		
								
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	if(isset($_POST['Save'])){
			
			$srno					= $_POST['srno'];
			$py_id					= $srno;
			$company_id				= $_POST['company_id'];
			$paid_to				= $_POST['paid_to'];
			$paid_date				= date('Y-m-d', strtotime($_POST['paid_date']));
			$cash_bank_name			= $_POST['cash_bank_name'];
			$cheque_no				= $_POST['cheque_no'];
			$tds_amount				= $_POST['tds_amount'];
			$total_amount_paid		= $_POST['total_amount_paid'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$remarks_hdr			= $_POST['remarks_hdr'];
			$rtgs_narration			= $_POST['rtgs_narration'];
			$hold_reason			= $_POST['hold_reason'];
			$st_flag				= $_POST['st_flag'];
			$trans_type				= $_POST['trans_type'];
			//$trans_type				= $_POST['po_doc_type'];
			
			$status 			    = 'Draft';
			$changed_by             = $_SESSION['user'];
			$user   				= $_SESSION['user'];
			$transfer_from_account		= $_POST['transfer_from_account'];
			$transfer_to_account		= $_POST['transfer_to_account'];

			$due_date =  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['paid_date'])));

  			$sql = "insert into payment_header (id, st_flag, paid_to, company_id, paid_date, cash_bank_name, cheque_no, dated, tds_amount, total_amount_paid, remarks, status, draft_by, draft_dated, changed_by , rtgs_narration, trans_type, hold_reason, transfer_from_account, transfer_to_account )
			Values('$srno', '$st_flag', '$paid_to', '$company_id', '$paid_date', '$cash_bank_name', '$cheque_no', '$dated', '$tds_amount', '$total_amount_paid', '$remarks_hdr' , '$status', '$user', now(), '$changed_by', '$rtgs_narration', '$trans_type', '$hold_reason', 
			'$transfer_from_account', '$transfer_to_account' )";
			
/* $file = fopen("ravitest.txt","w");
fwrite($file,$sql);
fclose($file); */
							
//echo $sql."<BR>";	
		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$supp_sid				= $_POST["supp_id"];

//exit("RAVI FIRST");

			$sql = "select * from company where comp_id = '$company_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$comp_name = $r2['comp_name'];

			for($i = 0; $i < sizeof($supp_sid); $i++){
				$supp_id			= $_POST["supp_id"][$i];
				$invoice_date		= date('Y-m-d', strtotime($_POST["invoice_date"][$i]));
				$supplier_invoice_no= $_POST["supplier_invoice_no"][$i];
				$payment_adjusted	= $_POST["payment_adjusted"][$i];
				$bal_amount			= $_POST["bal_amount"][$i] - $payment_adjusted;
				$deduction_head		= $_POST["deduction_head"][$i];
				$deduction_amt		= $_POST["deduction_amt"][$i];
				$deduction_head1	= $_POST["deduction_head1"][$i];
				$deduction_amt1		= $_POST["deduction_amt1"][$i];
				$deduction_head2	= $_POST["deduction_head2"][$i];
				$deduction_amt2		= $_POST["deduction_amt2"][$i];
				$retention_amt		= $_POST['retention_amt'][$i];
				//$compliances_amount	= $_POST['compliances_amount'][$i];
				
				
				if(empty($payment_adjusted)){
					continue;
				}	
				
				//$actual_payment		= $_POST["actual_payment"][$i];
				$remarks_dtl		= $_POST["remarks_dtl"][$i];
				
				//Deduction amount should deduct from bal_amount.
					$ded_amount = 0;
					if($st_flag=='T' || $st_flag=='C'){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'A' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('TE','RE','CE') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
					}
					else if($st_flag=='S' ){
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b where a.account_type = 'A' and b.account_type = 'D' and a.account_id = b.id and a.doc_type in ('SI') and a.effect = 'Cr' and a.doc_no = '$supp_id' ";
	
					}
					
					if($st_flag=='T' || $st_flag=='C' || $st_flag=='S' ){
			//echo $sql;			
						$qr2   = mysqli_query($con, $sql);
						while ($res2  = mysqli_fetch_array($qr2)){
							$ded_amount      = $res2['amount'];
							//$deduction_head1 = $res2['account_name'];
						}
						
						$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
							where b.id = a.account_id and b.account_type in ( 'E','A') and  a.doc_type = 'SI' and a.account_type = 'V' 
							and a.effect = 'Cr' and a.doc_no = '$supp_id' and b.id in (select id from account_mst where account_name in ('Advance', 'Labour Cess Payable' ) ) ";
/* $file = fopen("ravitest.txt","a");
fwrite($file,$sql);
fclose($file);	 */						
						$qr2   = mysqli_query($con, $sql);
						while ($res2  = mysqli_fetch_array($qr2)){
							$ded_amount      = $ded_amount + $res2['amount'];
							//$deduction_head1 = $res2['account_name'];
						}
						
					}
					
				//Deduction amount should deduct from bal_amount.	
					
				$actual_payment			= $payment_adjusted - ($deduction_amt + $deduction_amt1 + $deduction_amt2 + $ded_amount + $retention_amt );
				$tot_payment_adjusted	= $payment_adjusted + $deduction_amt + $deduction_amt1 + $deduction_amt2 + $ded_amount + $retention_amt;
				
				$tot_tds_amount		= $tot_tds_amount + $deduction_amt + $deduction_amt1 + $deduction_amt2 + $retention_amt;
				$tot_amount			= $actual_payment;

				if( ($payment_adjusted + $deduction_amt + $ded_amount) >0 ){
					$sql = " insert into `payment_details` (payment_hdr_id, supp_id, invoice_date, supplier_invoice_no, bal_amount, payment_adjusted, deduction_head, deduction_head1, deduction_head2, deduction_amt, deduction_amt1, deduction_amt2, actual_payment, remarks, retention_amount,  advance_amt) values ('$srno', '$supp_id', '$invoice_date', '$supplier_invoice_no', '$bal_amount', '$payment_adjusted', '$deduction_head', '$deduction_head1', '$deduction_head2', '$deduction_amt', '$deduction_amt1', '$deduction_amt2', '$actual_payment', '$remarks_dtl', '$retention_amt', '$advance_amt' ) ";
//echo $sql."<BR>";
					$r2 = mysqli_query($con, $sql);
					echo mysqli_error($con);

					$sql = " update payment_header set tds_amount = tds_amount + ('$deduction_amt' + '$deduction_amt1' + '$deduction_amt2' + $ded_amount), total_amount_paid = total_amount_paid + '$payment_adjusted' - ('$deduction_amt' + '$deduction_amt1' + '$deduction_amt2' + '$retention_amt' ) where id = '$srno' ";
					$r2 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					
					$paid_status = 'Paid';
//echo $st_flag. "<BR>";					
					if( $st_flag=='D' ){
						
						$sql = " update sma_purchase_order set paid_amount = paid_amount + $payment_adjusted , paid_status='$paid_status' where id = '$supp_id' ";
//echo $sql."<BR>";						
	
						$r2 = mysqli_query($con, $sql);
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where userid in (Select draft_by from sma_purchase_order where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name = $row['username'];
						
						$sql   = "select * from sma_party_mst where id in (Select to_supplier from sma_purchase_order where id ='$supp_id') ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$mail_to = $row['party_email'];
						$party_name = $row['party_name'];
					}
					else if( $st_flag =='S' || $st_flag =='R' ||  $st_flag =='M' ){
						//Supplier Invoice
						if( $st_flag =='S'){
							$sql = " update sma_supplier_invoice set bal_amount = bal_amount - $payment_adjusted  where id = '$supp_id' "; //+ $ded_amount
	//echo $sql ."<BR>";							
							$r2 = mysqli_query($con, $sql);
						}
						else if( $st_flag =='R'){
							$sql = " update sma_supplier_invoice set bal_retention_amount = bal_retention_amount + ($payment_adjusted )  where id = '$supp_id' ";
							mysqli_query($con, $sql);
							$sql = " update sma_retention_invoice set bal_retention_amount = bal_retention_amount + ($payment_adjusted )  where id = '$supp_id' ";
							mysqli_query($con, $sql);
						}
						else if( $st_flag =='M'){
							$sql = " update sma_supplier_invoice set bal_compliances_amount = bal_compliances_amount + ($payment_adjusted )  where id = '$supp_id' ";
							mysqli_query($con, $sql);
							$sql = " update sma_retention_invoice set bal_compliances_amount = bal_compliances_amount + ($payment_adjusted )  where id = '$supp_id' ";
							mysqli_query($con, $sql);
						}
						
						
						$sql   = "SELECT * FROM `sma_supplier_invoice` where id= '$supp_id' ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$our_po_ref_no	= $row['our_po_ref_no'];
						$total_amount 	= $row['total_amount'];
						$payable_amount	= $row['payable_amount'];
						$bal_amount 	= $row['bal_amount'];
						if($bal_amount<=1){
							$sql = " update sma_supplier_invoice set paid_status='$paid_status' where id = '$supp_id' ";
							$r2 = mysqli_query($con, $sql);
						}
						
						$sql = "update sma_purchase_order set paid_amount = paid_amount + '$payment_adjusted' + $ded_amount, paid_against_invoice = paid_against_invoice + '$payment_adjusted' + $ded_amount where id = '$our_po_ref_no' ";
						$r2 = mysqli_query($con, $sql);
						
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where userid in (Select draft_by from sma_supplier_invoice where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name = $row['username'];
						
						$sql   = "select * from sma_party_mst where id in (Select suplier_name from sma_supplier_invoice where id ='$supp_id') ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$mail_to = $row['party_email'];
						$party_name = $row['party_name'];
					}
					else if($st_flag=='A'){
						//Travel Request
						$sql = " update sma_traval_approval set paid_amount = paid_amount + $payment_adjusted, paid_status='$paid_status' where id = '$supp_id' ";
						$r2 = mysqli_query($con, $sql);
						echo mysqli_error($con);
			//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where userid in (Select draft_by from sma_traval_approval where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name = $row['username'];
					}
					else if( $st_flag == 'T' || $st_flag == 'C'){
						//Travel & Regular Expense 
						$sql = " update sma_travel_expenses set bal_amount = bal_amount + $payment_adjusted + $ded_amount , paid_status='$paid_status' where id = '$supp_id' ";
						$r2 = mysqli_query($con, $sql);
						echo mysqli_error($con);
//echo $sql."<BR>";
						//mail to draft user
						$sql   = "SELECT * FROM `sma_user` where userid in (Select draft_by from sma_travel_expenses where id = '$supp_id' ) ";
						$query = mysqli_query($con, $sql);
						$row   = mysqli_fetch_array($query);
						$user_email = $row['email'];
						$user_name = $row['username'];
					}
					
					
					$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'PY', '$py_id', '$usrid', now(), 'Draft', '', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					

				//	$msg = 'Payment for Supplier Invoice Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date("d-m-Y"). "<br>";
				//	$msg .= 'Payment Paid : ' . $payment_adjusted . "<br>";
				//	$msg .= 'Deduction under : ' . $deduction_head . ' : ' . $deduction_amt. "<br>";
				//	include "py_mail.php";
					
					$payment_adjusted_m = $payment_adjusted_m + $payment_adjusted;
					$deduction_amt_m = $deduction_amt_m + $deduction_amt;
				}
			}
			
//echo $msg;			
//exit("RAVINDRA EXIT");			
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];

			$arrDocDesc 		= $_POST["docdesc"];
			$arrFUDoc 			= $_FILES["fudoc"];
			$share_point_link 	= $_POST['share_point_link'];
			
			for($i = 0; $i < sizeof($arrDocType); $i++){
				if(!empty($arrDocType[$i])){
					$folder_path = "uploads/py/" . $py_id;
					if (!file_exists($folder_path)){
						mkdir($folder_path, 0755, true);
						$findex = $folder_path.'/index.php';
						fopen($findex,'w');
					}
					$filename = $arrFUDoc['name'][$i];
					$tmpFileName = $arrFUDoc['tmp_name'][$i];
					
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
					VALUES( 'PY', '$filename', '$folder_path' , '$arrDocType[$i]',  '$arrDocDesc[$i]', '$share_point_link[$i]', '$py_id', now() )";
					//mysqli_query($con, $sql);
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/py/" . $py_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
					
				}
			}
			
//			exit("Hello... Stoped");
			
			$baseurl.=$modulePath;
//$baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';

			echo "<script>window.location.href='$baseurl';</script>";
		
		}

?>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
            Payment Entry
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Payment Entry</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="add.php?sub=add" method="post"  id="myForm123" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php
							$sql  = " SELECT max(id) as srno from payment_header ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$srno = $r1['srno']+1;
														
						?>
						
						<?php				
							$supplier_count  = '';
							$comid = $_SESSION['comid'];
							$sql="select  count(distinct(suplier_name), company_id) as scnt from sma_supplier_invoice where company_id in ($comid) and company_id > 0 and bal_amount > 0 and status = 'Completed' and bal_amount <= payable_amount and del != 'Y' and paid_status !='Paid' ";

							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$supplier_count  = $r2['scnt'];	
							
							
							$sql =" select count(*) as scnt from (select a.id, to_supplier, project, round(sum((quantity * unit_rate) + (((quantity * unit_rate * gst) / 100))),0) 	as tot_amount , a.paid_amount, a.paid_against_invoice
								from sma_purchase_order a, sma_po_items b 
								where a.id = b.purchase_id and a.advance_flag = 'Y' and a.project in($comid) and a.status='Completed' and paid_against_invoice >= 0 and a.del != 'Y' group by a.id ) 
								DS where ( tot_amount > paid_against_invoice && tot_amount != paid_amount && (tot_amount - paid_against_invoice) > 1)";
								
							$sql =" select count(distinct(supplier_id)) as scnt, company_id
								FROM sma_advance
									WHERE 1 and company_id in($comid) and status='Completed' and del != 'Y' 
										and advance_amount > paid_against_invoice && advance_amount != paid_amount 
										&& (advance_amount - paid_against_invoice) > 1 ";	
//echo $sql."<br>";									
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$po_count  = $r2['scnt'];
							//$po_count  = '10';
				//echo $sql."<br>";			
							//$sql="select  count(distinct(emp_id), company_id) as scnt from sma_travel_expenses where company_id in ($comid) and bal_amount = 0 and status='Completed' ";
							
							$sql = "select count(distinct(emp_id), company_id) as scnt from sma_travel_expenses where exp_type = 'T' and company_id in ($comid) and status='Completed' and ( total_amount - advance_amount ) > 0 and bal_amount = 0 and del != 'Y' and company_id > 0";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$te_count  = $r2['scnt'];
				//echo $te_count. "<BR>";			
							$sql = "select count(distinct(emp_id), company_id) as scnt  from sma_travel_expenses where exp_type in ('R') and company_id in ($comid) and status='Completed' and bal_amount = 0 and del != 'Y' and company_id > 0 ";
					
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$te_count  = $r2['scnt'] + $te_count;	
							//$te_count  = '10';
//echo $te_count. "<BR>";							
				//echo $sql."<br>";			
							$sql="select  count(distinct(emp_id),  company_id) as scnt from sma_traval_approval where company_id in ($comid) and advance_amount > 0 and paid_amount = 0 and (status='Booked' || status='Completed' ) and del != 'Y' ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$tr_count  = $r2['scnt'];	
							//$tr_count  = '10';

							//select count(distinct(emp_id),  company_id) as scnt from sma_traval_approval where company_id in (8,3,9,0) and paid_amount = 0 and (status='Booked' || status='Completed' )
			
				//echo $sql."<br>";
								
							$sql="SELECT count(*) as utr_cnt FROM `payment_header` where utr_no='' and draft_by = '$user' and status !='Completed' and del != 'Y'  ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$utr_count = $r2['utr_cnt'];
							
							$sql="select count(distinct(emp_id), company_id) as scnt  from sma_travel_expenses where exp_type in ('C') and company_id in ($comid) and status='Completed' and total_amount - bal_amount > 0 and del != 'Y'  and paid_status != 'Paid' ";
//echo $sql;
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$ce_count  = $r2['scnt'];
						
							$reten_count  = '';
							$comid = $_SESSION['comid'];
							$sql="select  count(distinct(suplier_name), company_id) as scnt from sma_retention_invoice where company_id in ($comid) and company_id > 0 and status='Completed' and payable_retention_amount > 0 and retention_amount > bal_retention_amount and del != 'Y'  ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$reten_count  = $r2['scnt'];
							
							$compliances_count ='';
							$sql="select  count(distinct(suplier_name), company_id) as scnt from sma_retention_invoice where company_id in ($comid) and company_id > 0 and status='Completed' and payable_compliances_amount > 0 and payable_compliances_amount > bal_compliances_amount and del != 'Y'  ";
							$q2 = mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$compliances_count  = $r2['scnt'];
							
						?>
						
						<div class="form-group" >
					
							<label class="col-lg-2 control-label">Payment Against </label>
					
					<?php //if ( $role!='Billdesk'){  ?>			
							<div class="col-lg-12" style="padding-top: 6px;">
								<span class="label label-warning" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >
								<input type="radio" name='st_flag' id='st_flaga' <?php echo ($st_flag=='S')?"CHECKED":''; ?> CHECKED value='S' onclick="getclear();" > Supplier Invoice &nbsp;
									<?php if($supplier_count > 0){ ?>
										<span class="label123 label-warning123" ><?php echo "<b>&nbsp;".$supplier_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
								
								</span>&nbsp;
					<?php //} ?>
					
					<?php	//if ( $role=='Billdesk' ){ ?>				
								<!--<span class="label label-info" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >-->
								<!--<input type="radio" name='st_flag' id='st_flagd' <?php echo ($st_flag=='D')?"CHECKED":''; ?>  value='D' onclick="getclear();" >Supplier Advance&nbsp;-->
									<?php if($po_count > 0){ ?>
										<!--<span class="label123 label-info123" ><?php echo "<b>&nbsp;".$po_count."&nbsp;</b>"  ;?></span>-->
									<?php } ?>
									
									<!--<input type="hidden" name='st_flagd' id='st_flagd' value="" >-->
									<!--<input type="hidden" name='st_flaga' id='st_flaga' value="" >-->
									<!--<input type="hidden" name='st_flago' id='st_flago' value="" >-->
									<!--<input type="hidden" name='st_flagb' id='st_flagb' value="" >-->
									<!--<input type="hidden" name='st_flagc' id='st_flagc' value="" >-->
									<!--<input type="hidden" name='st_flagn' id='st_flagn' value="" >-->
									<!--<input type="hidden" name='st_flagm' id='st_flagm' value="" >-->
									
								<!--</span>&nbsp;-->
					<?php //} else { ?>			
					
								<span class="label label-info" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >
								<input type="radio" name='st_flag' id='st_flagd' <?php echo ($st_flag=='D')?"CHECKED":''; ?>  value='D' onclick="getclear();" >Supplier Advance&nbsp;
									<?php if($po_count > 0){ ?>
										<span class="label123 label-info123" ><?php echo "<b>&nbsp;".$po_count."&nbsp;</b>"  ;?></span>
									<?php } ?>
								</span>&nbsp;
								
								<span class="label label-primary " 
								style="padding-top:6px;padding-bottom:6px;font-size:16px;color:white;">
								<input type="radio" name='st_flag' id='st_flago' value='C'  <?php echo ($st_flag=='C')?"CHECKED":''; ?> > Operating Expenses &nbsp;
									<?php if($ce_count){ ?> 
										<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$ce_count."&nbsp;</b>" ;?></span>
									<?php } ?>
								</span>&nbsp;
								
								<!--<span class="label label-success " style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >-->
								<!--<input type="radio" name='st_flag' id='st_flagb' <?php echo ($st_flag=='A')?"CHECKED":''; ?>  value='A' onclick="getclear();" >Travel Advance &nbsp;-->
								<!--	<?php if($tr_count > 0){ ?>-->
								<!--		<span class="label123 label-success123" ><?php echo "<b>&nbsp;".$tr_count."&nbsp;</b>"  ;?></span>-->
								<!--	<?php } ?>-->
									
								<!--</span>&nbsp;-->
								
								<!--<span class="label label-danger" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >-->
								<!--<input type="radio" name='st_flag' id='st_flagc' <?php echo ($st_flag=='T')?"CHECKED":''; ?>  value='T' onclick="getclear();" >Expenses &nbsp;-->
								<!--	<?php if($te_count > 0){ ?>-->
								<!--		<span class="label123 label-danger123" ><?php echo "<b>&nbsp;".$te_count."&nbsp;</b>"  ;?></span>-->
								<!--	<?php } ?>-->
								<!--</span>&nbsp;-->
								
								<span class="label label-info" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >
									<input type="radio" name='st_flag' id='st_flagn' value='R' > Retention&nbsp;
									<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$reten_count."&nbsp;</b>" ;?></span>
								</span>&nbsp;
								
								<span class="label label-info" style="padding-top: 6px;padding-bottom: 6px;font-size:16px;color:white;" >
									<input type="radio" name='st_flag' id='st_flagm' value='M' > Compliances&nbsp;
									<span class="label123 label-primary123" ><?php echo "<b>&nbsp;".$compliances_count."&nbsp;</b>" ;?></span>
								</span>
								
								
					<?php //} ?>		
								
							</div>
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Paid Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="paid_date" name="paid_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
							
							<div class="col-sm-4">
								<label for="company_id" class="control-label">Company<span style="color:red;"> **</span></label>
								<select class="form-control select2" required name="company_id" id="company_Id" 
									onchange="getsupplier(this.value);getbankname(this.value);getBankTransfer(this.value);" >
								<option value=""> Select </option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								// $sql = "select * from company  order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Paid To<span style="color:red;"> **</span></label>
								<span id="getsupplier">
									
								</span>	
							</div>
						
						</div>
						
						<span id="getBankTransfer">
						    <div class="form-group">
							
							<div class="col-md-6">
								<label class="control-label">Transfer From Account <span data-toggle="tooltip" title="" class="badge bg-light-blue"></span></label>
								
									<select class="form-control" id="transfer_from_account" name="transfer_from_account"  >
										<option value="">Select</option>	
									
									</select>
									
							</div>
							
							<div class="col-md-6">
								<label class="control-label">To Account<span style="color:red;"></span></label>
								
									<select class="form-control" id="transfer_to_account" name="transfer_to_account"  >
										<option value="">Select</option>	
								
									</select>
									
							</div>
							
		                    </div>		
						</span>	
						
						
						<div class="form-group">
							
							<div class="col-md-6">
								<label class="control-label">Paid via<span style="color:red;"> **</span></label>
								<span id = "getbankname">
									<select class="form-control" id="cash_bank_name" name="cash_bank_name" required >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' and company_id in ($comid) ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'] ?></option>
										<?php } ?>
									</select>
								</span>		
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Cheque /UTR Number</label>
								<input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Prepared Dated</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>

							<div class="col-md-2">
								<label class="control-label">Total Amount Paid</label>
								<input type="text" class="form-control" id="total_amount_paid" style="text-align:right;" name="total_amount_paid" readonly value="" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Narration</label>
								<textarea rows="2" class="form-control" id="remarks_hdr" name="remarks_hdr" ></textarea>
							</div>
						</div>
						
						<div class="form-group">

							<div class="col-md-5">
								<label class="control-label">RTGS Narration</label>
								<textarea rows="2" class="form-control" id="rtgs_narration" name="rtgs_narration" autocomplete="off" <?php echo $rdonly; ?> ><?php echo $row['rtgs_narration'];?></textarea>
							</div>
							
							
							<div class="col-md-3">
								<label class="control-label">Reason for Hold (If any)</label>
								<textarea rows="2" class="form-control" id="hold_reason" name="hold_reason" autocomplete="off" ></textarea>
							</div>
							
						</div>
						
						<span id="getworkflowtype">
						
						</span>
						
						
						<span id="getinvoice">
						
						</span>
						
						
                       <!-- Attachments -->
						<div class="box">	
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%" >
                                            <select class="form-control select2 doctype" name="doctype[]">
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
										<td width="20%" >
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<td width="30%"><input type="file" name="fudoc[]" class="docfile"></td>
                                         <td width="10%" ><button type="button" name="add" id="add_doc" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            
							</div>
						</div>	
						<!-- Attachments -->
						
						<div class="box-footer">
							<div class="col-sm-6">
									<?php $did = $_GET['id']; 
										$baseurl1 = $baseurl.$modulePath;
									?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
						
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
 
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Write Text&hellip;</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
							
			<div class="col-md-6">
				<textarea name="mail_text" ></textarea>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
$('#myForm').on('submit', function(e){
  $('#myModal').modal('show');
  e.preventDefault();
});
</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}
	
	function getcreditdays(id){
		
        var sub    = 'sub4';
//		var paid_date = document.getElementById(paid_date);
//	alert(paid_date);,paid_date:paid_date

		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}
		
</script>
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
		
	
<!-- For Document Attachment Start-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>
 $(document).ready(function(){  
      var i=1;  
      $('#add_doc').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"><textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"></td><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
  <!-- For Document Attachment End-->
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

	
	function getinvoice(id){
	
		var sub    = 'sub4';
		
		var st_flag = '';
		if (document.getElementById('st_flaga').checked) {
		    st_flag = document.getElementById('st_flaga').value;
		}
		if (document.getElementById('st_flagd').checked) {
		    st_flag = document.getElementById('st_flagd').value;
		}
		if (document.getElementById('st_flago').checked) {
		    st_flag = document.getElementById('st_flago').value;
		}
// 		if (document.getElementById('st_flagb').checked) {
// 		    st_flag = document.getElementById('st_flagb').value;
// 		}
// 		if (document.getElementById('st_flagc').checked) {
// 		    st_flag = document.getElementById('st_flagc').value;
// 		}
		
 		if (document.getElementById('st_flagn').checked) {
		    var st_flag = document.getElementById('st_flagn').value;
		}
		
		if (document.getElementById('st_flagm').checked) {
		    var st_flag = document.getElementById('st_flagm').value;
		}
		
		
		var company_id = document.getElementById("company_Id").value;
//alert(company_id);		
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,company_id:company_id,st_flag:st_flag,sub4:sub},function(result){
		      $('#getinvoice').html(result);
		});
		
	}
	
	function getactual(){

		var payment_adjusted = document.getElementById("payment_adjusted").value;
		var deduction_amt    = document.getElementById("deduction_amt").value;
		var deduction_amt1   = document.getElementById("deduction_amt1").value;
		var deduction_amt2   = document.getElementById("deduction_amt2").value;
		var advance_amt	 	 = document.getElementById("advance_AMT").value;
		var retention_amt	 = document.getElementById("retention_AMT").value;
		
		if(payment_adjusted==''){
			payment_adjusted=0;
		}
		if(retention_amt==''){
			retention_amt=0;
		}
		if(advance_amt==''){
			advance_amt=0;
		}
		if(deduction_amt==''){
			deduction_amt=0;
		}
		if(deduction_amt1==''){
			deduction_amt1=0;
		}
		if(deduction_amt2==''){
			deduction_amt2=0;
		}

//alert(payment_adjusted + ' ' + advance_amt);
		var actual_payment   = parseInt(payment_adjusted) - parseInt(deduction_amt) - parseInt(deduction_amt1) - parseInt(deduction_amt2) - parseInt(advance_amt) - parseInt(retention_amt);
//alert(actual_payment);		
		if (!isNaN(actual_payment)) {
       //  document.getElementById('txt3').value = result;
		   document.getElementById("actual_payment").value=actual_payment;
	//		alert(actual_payment);
		}
	
	}
	
	
	function checkadjusted(){
		
		var bal_amount 		 = parseInt(document.getElementById("bal_amount").value);
		var payment_adjusted = parseInt(document.getElementById("payment_adjusted").value);
		var advance_amt	 	 = parseInt(document.getElementById("retention_AMT").value);

//alert(payment_adjusted + ' ' + bal_amount);
		/* if(advance_amt==''){
           var advance_amt = 0; 
        }
        if(payment_adjusted==''){
           var payment_adjusted = 0; 
        }
		var payment_adjusted = payment_adjusted + advance_amt;
		 */
		if (payment_adjusted > bal_amount){
			alert(" Payment adjustment amount should not be greater then balance amount...");
			document.getElementById("payment_adjusted").value = '0';
			document.getElementById("retention_AMT").value = '0';
			document.getElementById("actual_payment").value = '0';
			
			return false;
		}

	}
	
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
		if (document.getElementById('st_flago').checked) {
		    st_flag = document.getElementById('st_flago').value;
		}
// 		if (document.getElementById('st_flagb').checked) {
// 		    st_flag = document.getElementById('st_flagb').value;
// 		}

// 		if (document.getElementById('st_flagc').checked) {
// 		    st_flag = document.getElementById('st_flagc').value;
// 		}

		
		if (document.getElementById('st_flagn').checked) {
		    var st_flag = document.getElementById('st_flagn').value;
		}
		
		if (document.getElementById('st_flagm').checked) {
		    var st_flag = document.getElementById('st_flagm').value;
		}
		
		
//alert(st_flag+ '##3');	
		
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,st_flag:st_flag,sub6:sub},function(result){
		      $('#getsupplier').html(result);
		});
	
	}
	
	function getBankTransfer(id){
	
		var sub    = 'sub28';
//alert(sub);
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub28:sub},function(result){
		      $('#getBankTransfer').html(result);
		});
	
	}
	
	function getbankname(id){
	
		var sub    = 'sub7';
//alert(sub);
				
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getbankname').html(result);
		});
	
	}
 
 	function getworkflowtype(id){
		
        var sub    = 'sub27';
//alert(sub);
		if (document.getElementById('st_flagd').checked) {
		    st_flag = document.getElementById('st_flagd').value;
		}
		if(st_flag=='D'){
			var strURL = "py_func.php";
			$.post(strURL,{id:id,st_flag:st_flag,sub27:sub},function(result){
				  $('#getworkflowtype').html(result);
			});
		}

	}

	function getclear123(){
		
	   window.location.reload();
	   
	}	

	/* function getworkflowtype(id){
	
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		var bal_amount    	 = document.getElementById("bal_amount").value;
		var advance_amt	 	 = document.getElementById("retention_AMT").value;
		
		if(advance_amt==''){
			advance_amt=0;
		}	
		
		var actual_payment   =  parseInt(payment_adjusted) + parseInt(advance_amt);
		var bal_amount 		 =  parseInt(bal_amount);
		
alert(actual_payment + ' ' + bal_amount);	
		if( actual_payment > bal_amount){
			alert("Payment adjustment amount should not be greater then balance amount...");
		}	
		
		
	} */
</script>


