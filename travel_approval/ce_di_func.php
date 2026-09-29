<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

	$userid   	= $_SESSION['usrid'];
	$comid  = $_SESSION['comid'];
	
?>


<?php

	if(isset($_POST['sub2'])){

		$modulePath = "travel_approval/";
		$re_id 			= $_POST['re_id'];
		$doc_type 		= $_POST['doc_type'];
		
		$gst_flag 		= $_POST['gst_flag'];
		
		$exp_type ='D';
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
		}	
		$sql 	= "update sma_travel_expenses set gst_flag = '$gst_flag' where exp_type = '$exp_type' and id = '$re_id' ";
		mysqli_query($con, $sql);
		
		$sql 	= "select * from sma_travel_expenses where exp_type = '$exp_type' and id = '$re_id' ";
//echo $sql. "<BR>";		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		$supplier_id 	= $r2['emp_id'];
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$tally_narration 	 = $r2['tally_narration'];
		$gst_flag			= $r2['gst_flag'];
		
		$cash_bank_name		= $r2['cash_bank_name'];
		$cheque_no 			= $r2['cheque_no'];
		$prepared_dated 	= date('Y-m-d', strtotime($r2['prepared_dated']));
		$amount			 	= $r2['total_amount_paid'];
		$rtgs_narration		= $r2['rtgs_narration'];
		
		$sql="SELECT * FROM account_mst where account_type = 'B' and id = '$cash_bank_name' ";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$account_name		= $r2['account_name'];
		$account_type		= $r2['account_type'];
		$account_id			= $r2['id'];
		$address			= $r2['address'];
		$branch				= $r2['branch'];
									
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_gst_no = $r2['comp_gst_no'];
			
		$record_type 		= "Payment-P2P";
		$doc_no				= $re_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		//$invoice_no			= $invoice_no;
		$invoice_date		= date('d-m-Y', strtotime($invoice_date));
			
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '', '$account_id', '$account_name', '', 'Cr', '$amount', '$tally_narration', '$cheque_no', '$address', '', '$branch', '$company_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
			$sql = "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			$account_id = $r2['id'];
			$account_type_a	= 'V';
			$val_type_a 	= 'V';
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
			$gst_no			= $r2['party_gst_number'];
		
		$account_type_a	    = 'V';
		$val_type_a 	    = 'V';
		
		$account_type		= $account_type_a;
		$val_type			= $val_type_a;
		
		$account_id			= $supplier_id;
		$account_name       = $party_name;
		$effect				= 'Dr';
		$cheque_no			= '';
		 
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$val_type', '$account_id', '$account_name', '$budget_head', '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql = " UPDATE sma_travel_expenses SET total_amount = '$amount', bal_amount = '$amount' , tally_status='R', tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
		mysqli_query($con, $sql);
//echo $sql."<BR>";
//exit();
		
		
		$value = "<script>window.location.href='direct_expense_payment.php?sub=edit&id=$re_id&IN=in';</script>";
		
		//&active5=active
		echo $value;
		
	}


	if(isset($_POST['sub3'])){
		
		$modulePath = "travel_approval/";
		$actype = $_POST['id'];
		$doc_type 		= $_POST['doc_type'];
		
		if($actype =='A'){
			$sql = "SELECT id, account_name as 'account_name' FROM sma_product where account_type = 'E' or account_type = 'D' order by account_name ";
		
		}
		else if($actype =='V'){
		
			$sql = "SELECT id, party_name as 'account_name' FROM sma_party_mst order by party_name ";
		}
		else if($actype =='B'){
		
			$sql = "SELECT id, category as 'account_name' FROM sma_budget_category order by category ";
		}
?>	
		<div class="col-sm-12">
        <label for="approver" class=" control-label">Account Name *</label>                                        
			<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)" >
			    <option value="">Select</option>
			<?php
			    $result = mysqli_query($con, $sql);
			    echo mysqli_error($con);
			    while($r3 = mysqli_fetch_array($result)){
			?>	
				<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'] ?></option>
			<?php } ?>
			</select>
	
		</div>
	
<?php 											
	}	

	if(isset($_POST['sub13'])){
		
		$account_id 	= $_POST['id'];
		$amount_dr 		= $_POST['amount_dr'];
		$re_id	 		= $_POST['re_id'];
		$exp_type		= $_POST['exp_type'];
		$amount_cgst	= $_POST['amount_cgst'];
		
		$amount_gst_tot	= $amount_cgst;
		
		$amount_tds	= 0;
		$amount_gst = 0;
		$sql  = "SELECT * FROM `sma_expenses` where exp_type = '$exp_type' and approval_ref_no = '$re_id' ";
//echo $sql. "<BR>";		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r2 = mysqli_fetch_array($res1)){
		
			$invoice_no 	.= $r2['invoice_no'].' ';
			$amount 		= $r2['amount'];
			$gst_flag		= $r2['gst_flag'];

			if($gst_flag=='Y'){
				$amount_gst		= $amount_gst + (($amount * 18) / 100);
			}

		}
			
		$sql = "SELECT * FROM sma_product where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//echo $sql. "<BR>";		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$percentage 		= $r3['percentage'];
		$account_type		= $r3['account_type'];
		$deduction_from		= $r3['deduction_from'];

		if($percentage > 0 && ( $account_type== 'E' || $account_type=='D' )){		
			$amount_dr_gst = $amount_dr - ($amount_gst_tot);
			$tds_amount = round(($amount_dr_gst * $percentage) / 100,0);
			
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" readonly name="amount" value="'.$tds_amount.'" >';
		}
		else if($percentage > 0 && ( $account_type== 'A' )){
			$amount_dr_gst = $amount_dr - ($amount_gst_tot);
			$tds_amount = round(($amount_dr_gst * $percentage) / 100,0);
						
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;" readonly name="amount" value="'.$tds_amount.'" >';
		}
		else {
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;"  name="amount" value="" >';
		}

	}

?>
