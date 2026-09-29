<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

	$userid   	= $_SESSION['usrid'];
	$comid  = $_SESSION['comid'];
?>

<?php
	if(isset($_POST['sub1'])){
		
		$modulePath 	= "payment/";
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= $_POST['account_name'];
		$effect 		= $_POST['effect'];
		$amount 		= $_POST['amount'];
		$narration 		= $_POST['narration'];
		$py_id 		    = $_POST['py_id'];
		
		
		$sql 	= " select * from payment_header where  id = '$py_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$paid_date_a		= date('Y-m-d', strtotime($r2['paid_date']));
		$paid_date		    = date('Y-m-d', strtotime($r2['paid_date']));
		$utr_no			= $r2['utr_no'];
		$company_id		= $r2['company_id'];
		$tally_narration= $r2['remarks'];
		$cheque_no			= $r2['cheque_no'];
		$paid_to			= $r2['paid_to'];
		
		$narration 			= $narration;
		
		$invoice_no = '';
		$sql  = "SELECT * FROM `payment_details` where  payment_hdr_id = '$py_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r1 = mysqli_fetch_array($res1)){
		
			$invoice_no .= $r1['supplier_invoice_no'].',';
			$supplier_id .= $r1['supp_id'].',';
			
		}
		
		$address 			='';
		$gst_no 			='';
		$state				='';
		$mobile_no			='';
		$pan_no				='';
		
		$sql="SELECT * FROM account_mst where id  = '$account_id' ";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$retention_flag		= $r2['retention_flag'];
		if($retention_flag=='Y'){
			$sql = "select * from sma_party_mst where id = '$paid_to' ";
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name 	= $r2['party_name'];
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['party_state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
			
			$account_name = $party_name.'-'.$account_name;
			
		}
		
		$record_type 		= "Payment-P2P";
		$doc_no				= $py_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$doc_type			= 'PY';
		
		/* $sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supplier_id, supp_invoice_no, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no)
				VALUES( '$record_type', 'PY', '$doc_no', '$doc_date', '$supplier_id', '$invoice_no', '$invoice_date', '$paid_date_a', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con); */
		//echo $sql."<BR>";
        $cheque_no			='';
		$address 			='';
		$gst_no 			='';
		$state				='';
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
echo $sql; 
//exit();		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
//		$tds_percentage = $r3['tds_percentage'];
		$percentage 	= $r3['percentage'];
		$account_type	= $r3['account_type'];
		//$percentage > 0 &&
		if( ($account_type == 'E' || $account_type =='D' )){
			//	$effect = 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supplier_id, supp_invoice_no, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no)
				VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$supplier_id', '$invoice_no', '$invoice_date', '$paid_date_a', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql."<BR>";//exit();
			
			if($effect=='Dr'){
				echo '';
			}
			else if($effect=='Cr'){
				$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type in( 'A' ) and doc_type='$doc_type' and doc_no='$doc_no' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
//echo $sql; 
//exit();
			
		}
		

//exit();
			
		$value = "<script>window.location.href='edit.php?sub=edit&id=$py_id&IN=in';</script>";
	
		echo $value;
	//	https://hcone.co.in/workflow2020/payment/edit.php?sub=edit&id=1901
		exit();
		
	}
?>		


<?php

	if(isset($_POST['sub2'])){

		$modulePath = "payment/";
		$py_id = $_POST['py_id'];
		
		$prev_account_name_a ='';
		$invoice_no_prev	 = array();
		$amount_array		 = array();
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$py_id' and doc_type = 'PY' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$py_id' and doc_type = 'PY' ";
			$q2 	= mysqli_query($con, $sql);
		}
		
		$sql 	= "select * from payment_header where id = '$py_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		$paid_to 		= $r2['paid_to'];
		$paid_date_a	 = date('Y-m-d', strtotime($r2['paid_date']));
		$paid_date		 = date('Y-m-d', strtotime($r2['paid_date']));
		$cash_bank_name  = $r2['cash_bank_name'];
		$utr_no			 = $r2['utr_no'];
		$tally_narration = $r2['remarks'];
		$st_flag		 = $r2['st_flag'];
		$cheque_no		 = $r2['cheque_no'];
		
		$sql="SELECT * FROM account_mst where account_type = 'B' and id  = '$cash_bank_name' ";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$account_id_dr		= $r2['id'];
		$account_name_a     = ($r2['account_name']);//mysql_real_escape_string
		$bank_name          = ($r2['account_name']);//mysql_real_escape_string

//echo $sql. ' ' . $account_id_dr . ' ' . $account_name_a. ' ' . $bank_name;
//exit();

		if($st_flag=='S' || $st_flag =='D' || $st_flag =='C'  ){	
			$sql = "select * from sma_party_mst where id = '$paid_to' ";
			$q2 	  	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name 	= $r2['party_name'];
			$account_id_cr 	= $r2['id'];
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['party_state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
		}
		else if($st_flag=='T' || $st_flag =='A' ){	
			$sql = "select * from sma_user where id = '$paid_to' ";
			$q2 	  	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name 	= $r2['username'];
			$account_id_cr 	= $r2['id'];
			$address		= $r2['party_address_1'];
		}

		$tot_amount = 0;
		$sql="SELECT * FROM `payment_details` where payment_hdr_id = '$py_id' ";
		
//echo $sql. "<BR>"; exit();

		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
?>
		
		
<?php
		$i = 1;
		$tot_amount = 0;
		while($row = mysqli_fetch_array($result)){
			
			$dtl_id 			= $row['id'];
			
			$amount 			= round($row['payment_adjusted'],0);
			
			$tds_percentage 	= $row['tds_percentage'];
			$tds_amount		 	= $row['tds_amount'];
			
			$deduction_head 	= $row['deduction_head'];
			$deduction_amount 	= $row['deduction_amt'];
			
			$deduction_head1 	= $row['deduction_head1'];
			$deduction_amount1 	= $row['deduction_amt1'];
			
			$deduction_head2 	= $row['deduction_head2'];
			$deduction_amount2 	= $row['deduction_amt2'];
			
			//$retention_amount 	= $row['retention_amount'];
			
			$tot_amount 		= $tot_amount + $amount ;
			$effect 			= "Cr";
			$record_type 		= "Payment-P2P";
			$doc_no				= $py_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$invoice_no			= $row['supplier_invoice_no'];
			$invoice_no_prev[]	= $row['supplier_invoice_no'];
			$amount_array[]		= $amount;
			$invoice_date		= date('d-m-Y', strtotime($row['invoice_date']));
			$supplier_id 		= $row['supp_id'];
			$account_type		= 'A';
			/* $account_id			= $account_id;
			$account_name       = $budget_head; */
			$effect				= $effect;
			$amount				= round($amount - ($tds_amount + $deduction_amount + $deduction_amount1 + $deduction_amount2),0) ;
			$narration			= $tally_narration;
			
//echo $prev_account_name_a .' !='.  $account_name_a .'&&' . $invoice_no_prev	.' != '. $invoice_no."<BR>";			
		if($amount>0){
			if( $prev_account_name_a != $account_name_a  ){
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name, bank_name,  effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', 
				'$account_id_dr', '$account_name_a', '$bank_name', '$effect', '$amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', 
				'$company_id', '$pan_no', '$mobile_no') ";
				
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
//echo $sql. "<BR>";
			}
			else {
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. "<BR>";
			}
		}
			
			if($deduction_amount>0){
				$account_type		= 'A';	
				$sql="SELECT * FROM account_mst where account_name = '$deduction_head' ";

				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$account_id_dr		  = $r2['id'];
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id ,  pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id_dr', '$deduction_head', '$effect', '$deduction_amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
			if($tds_amount>0){
				$account_type		= 'A';	
				$sql="SELECT * FROM account_mst where account_name = '$tds_percentage' ";

				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$account_id_dr		  = $r2['id'];
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id ,  pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id_dr', '$tds_percentage', '$effect', '$tds_amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
			if($deduction_amount1>0){
				$account_type		= 'A';	
				$sql="SELECT * FROM account_mst where account_name = '$deduction_head1' ";			
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$account_id_dr		  = $r2['id'];
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id_dr', '$deduction_head1', '$effect', '$deduction_amount1', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
			if($deduction_amount2>0){
				$account_type		= 'A';	
				$sql="SELECT * FROM account_mst where account_name = '$deduction_head2' ";			
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$account_id_dr		  = $r2['id'];
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id_dr', '$deduction_head2', '$effect', '$deduction_amount2', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			
/* 			if($retention_amount>0){
				$account_type		= 'A';
				$sql = "SELECT * FROM account_mst where account_name = 'Retention' ";			
				$q2  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2  = mysqli_fetch_array($q2);
				$account_id_dr		  = $r2['id'];
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id_dr', 'Retention', '$effect', '$retention_amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
 */			
			
			$prev_account_name_a = $account_name_a;
			//$invoice_no_prev[]	= $invoice_no;
			
//echo $sql."<BR>";			
?>

			
<?php
			
		}

		$record_type 		= "Payment-P2P";
		$doc_no				= $py_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		//$invoice_no			= $invoice_no;
		$invoice_date		= $invoice_date;
		$account_type		= 'V';
		$account_id			= $paid_to;
		$account_name_p     = $party_name;
		$effect				= 'Dr';
		$amount				= round($tot_amount,0);
		$narration			= $tally_narration;
		
		$sql 	= "select * from payment_header where id = '$py_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		$paid_to 		= $r2['paid_to'];
		$paid_date_a	 = date('Y-m-d', strtotime($r2['paid_date']));
		$paid_date		 = date('Y-m-d', strtotime($r2['paid_date']));
		$cash_bank_name  = $r2['cash_bank_name'];
		$utr_no			 = $r2['utr_no'];
		$tally_narration = $r2['remarks'];
		$st_flag		 = $r2['st_flag'];
		$cheque_no		 = $r2['cheque_no'];
		
		if($st_flag=='T' || $st_flag =='A' ){	
			$sql = "select * from sma_user where id = '$paid_to' ";
			$q2 	  	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name 	= $r2['username'];
			$account_id_cr 	= $r2['id'];
			$address		= $r2['party_address_1'];
			$account_type		= 'U';
			$val_type		    = 'V';
			$account_name_p  = $party_name;
		}
		
		if($account_type=='V'){
			$sql = "select * from sma_party_mst where id = '$paid_to' ";
			$q2 	  	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$account_id		= $paid_to;
			$party_name 	= $r2['party_name'];
			$account_name_p = $party_name;
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['party_state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
		}
		
		//$invoice_no_prev[];
		$i =0;
		$acnt = count($invoice_no_prev);
//echo $acnt."<BR>";		
		if ($acnt>1){
			foreach($invoice_no_prev as $invoice_no){
				$amount = round($amount_array[$i],0);	
				
				if($amount==0){continue;}
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, val_type, account_id, account_name, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$val_type', '$account_id', '$account_name_p', '$effect', '$amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id','$pan_no', '$mobile_no') ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				$i = $i +1;

			}
		}
		else {
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supplier_id, supp_invoice_date, paid_date, account_type, account_id, account_name, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
			VALUES('$record_type', 'PY', '$doc_no', '$doc_date', '$invoice_no', '$supplier_id', '$invoice_date', '$paid_date_a', '$account_type', '$account_id', '$account_name_p', '$effect', '$amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id','$pan_no', '$mobile_no') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
//echo $sql."<BR>"; exit();
		
		$sql = " update payment_header set tally_status='R', tally_created_by = '$userid', tally_created_date = now() where id = '$py_id' ";
		mysqli_query($con, $sql);

//exit();
		$value = "<script>window.location.href='edit.php?sub=edit&id=$py_id&IN=in';</script>";//&active5=active
	
		echo $value;
		
	}


	if(isset($_POST['sub3'])){
		
		$modulePath = "payment/";
		$actype = $_POST['id'];
		if($actype =='A'){
			$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'E' or account_type = 'D' or account_type = 'A' order by account_name ";
		
		}
		else if($actype =='V'){
		
			$sql = "SELECT id, party_name as 'account_name' FROM sma_party_mst order by party_name ";
		}
		else if($actype =='B'){
		
			$sql = "SELECT id, category as 'account_name' FROM sma_budget_category order by category ";
		}
?>	
		<div class="col-sm-12">
        <label for="approver" class=" control-label">Account Name</label>                                        
			<select class="form-control select2" id="account_idA" name="account_id" required="required">
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
		/* $amount_cgst	= $_POST['amount_cgst'];
		
		$amount_gst_tot	= $amount_cgst;
		 */
		 
		$amount_tds	= 0;
		$amount_gst = 0;
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//echo $sql;		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		
		if($tds_percentage > 0 && ( $account_type== 'E' || $account_type=='D' )){
			
			$amount_dr_gst = $amount_dr ;
			$tds_amount = round(($amount_dr_gst * $tds_percentage) / 100,0);
			
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" readonly name="amount" value="'.$tds_amount.'" >';
		}
		else if($tds_percentage > 0 && ( $account_type== 'A' )){
			
			$amount_dr_gst = $amount_dr ;
			$tds_amount = round(($amount_dr_gst * $tds_percentage) / 100,0);
						
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;" readonly name="amount" value="'.$tds_amount.'" >';
		}
		else {
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;"  name="amount" value="" >';
		}

	}

?>
