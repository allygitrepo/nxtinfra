<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

	$userid   	= $_SESSION['usrid'];
	$comid  = $_SESSION['comid'];
	
?>

<?php
	if(isset($_POST['sub1'])){
		
		$modulePath 	= "travel_approval/";
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		$effect 		= $_POST['effect'];
		$amount 		= $_POST['amount'];
		$narration 		= $_POST['narration'];
		$re_id 		    = $_POST['re_id'];
		$doc_type 		= $_POST['doc_type'];
		if($doc_type=='CE' ){
			$exp_type ='C';
		}	
		else if($doc_type=='RE' ){
			$exp_type ='R';
		}
		
		$sql 	= " select * from sma_travel_expenses where exp_type = '$exp_type' and id = '$re_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$company_id		= $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$gst_flag		 	 = $r2['gst_flag'];
		
			
		$invoice_no = '';
		$amount_tds	= 0;
		$sql  = "SELECT * FROM `sma_expenses` where exp_type = '$exp_type' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r1 = mysqli_fetch_array($res1)){
		
			$invoice_no 	.= $r1['invoice_no'].' ';
			$invoice_date   = date('d-m-Y', strtotime($r1['dated']));
			
		}
		
		$record_type 		= "Purchase-P2P";
		$doc_no				= $re_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		$cheque_no			='';
		$address 			='';
		$gst_no 			='';
		$state				='';
		
		$sql = "SELECT * FROM sma_product where id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		//$percentage 	= $r3['percentage'];
		$account_type		= $r3['account_type'];
		$deduction_from		= $r3['deduction_from'];
		if($account_type=='E'){
			$budget_code	= $r3['budget_code'];
		}
		
		if($gst_flag=='Y'){
				$sgst_amt = 0;
				$cgst_amt = 0;	
				$igst_account_name = $account_name;
				//$sgst_account_name = $account_name;
				//$cgst_account_name = $account_name;
			}
			
		if($percentage > 0 && ($account_type == 'E' || $account_type =='D' )){
			//	$effect = 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id)
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		//echo $sql."<BR>";exit();
			
			if($effect=='Dr'){
				echo '';
			}
			else if($effect=='Cr'){
				$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type in( 'V','U' ) and doc_type='$doc_type' and doc_no='$doc_no' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
		}
		else if($percentage > 0 && $account_type == 'A'){
			//$effect = 'Dr';
			if($effect=='Cr'){
				$sql= " update tally_journal_entry set amount = amount + '$amount' where effect='Dr' and account_type = 'B' and doc_type='$doc_type' and doc_no='$doc_no' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
			else if($effect=='Dr'){
				$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = '$doc_type' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'B' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
	
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id)
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name', '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		}

		if($doc_type=='CE' ){
			$value = "<script>window.location.href='company_expense.php?sub=edit&id=$re_id&IN=in';</script>";
		}
		else if($doc_type=='RE' ){
			$value = "<script>window.location.href='regular_expense.php?sub=edit&id=$re_id&IN=in';</script>";
		}
		else if($doc_type=='TE' ){
			$value = "<script>window.location.href='travel_expence.php?sub=edit&id=$re_id&IN=in';</script>";
		}
		
		echo $value;
	//	https://hcone.co.in/workflow2020/travel_approval/company_expense.php?sub=edit&id=1901
	
	}
?>		

<?php

	if(isset($_POST['sub2'])){

		$modulePath = "travel_approval/";
		$re_id = $_POST['re_id'];
		$doc_type 		= $_POST['doc_type'];
		
		$gst_flag 		= $_POST['gst_flag'];
		if($doc_type=='CE' ){
			$exp_type ='C';
		}
		
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
		$gst_flag		= $r2['gst_flag'];
		
		//$invoice_no = $r2['invoice_no'];
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_gst_no = $r2['comp_gst_no'];
			
		if($doc_type=='CE' ){
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
		}
		
			
		$tot_amount = 0;
		$prev_budget_head = '';
		$prev_account_name = '';
		
		$sql = " SELECT a.amount, a.gst_amount, a.invoice_no, a.dated, a.budget_id, a.budget_head, a.budget_name , reference as account_id , a.tds_id
		FROM `sma_expenses` a
		WHERE exp_type = '$exp_type' and a.approval_ref_no = '$re_id' ";

//echo $sql. "<BR>"; exit(); //$company_id

		$result1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		$i = 1;
		while($row = mysqli_fetch_array($result1)){
			
			$gst_amount			= $row['gst_amount'];
			$gst_amount_v		= $gst_amount_v + $row['gst_amount'];
			$amount 			= $row['amount'];
			
			$tds_id				= $row['tds_id'];
			$budget_id 			= $row['budget_id'];
			$tds_amount		= 0;
			if($tds_id>0){
				$sql = "SELECT * from account_mst where id = '$tds_id' ";
				$qry = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2  = mysqli_fetch_array($qry);
				$tds 			= $r2['percentage'];
				$tds_name 		= $r2['account_name'];
				$tds_amount		= round($amount * $tds / 100,0);
			}
			
			$sql = "SELECT * from sma_budget where id = '$budget_id' ";
			$qry = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2  = mysqli_fetch_array($qry);
			$budget_code 		= $r2['budget_code'];
			
			$account_name 		= $budget_code;
			$account_type		= $row['account_type'];
			$budget_head 		= $row['budget_head'];
			
			$invoice_no     	= $row['invoice_no'];
			$dated				= $row['dated'];
			$account_id  		= $row['account_id'];
			
			$tot_amount = $tot_amount + $amount ;
			$effect 			= "Dr";
			$record_type 		= "Purchase-P2P";
			$doc_no				= $re_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$invoice_date		= date('d-m-Y', strtotime($dated));
			
			$effect				= $effect;
			$amount				= $amount;
			//$narration			= $narration;
			$cheque_no			= '';
			
			if($gst_amount >0 ){
				$gst_nm = 'GST';
				if($gst_flag=='Y'){
					$gst_nm = $account_name;
					
				}
				if(substr($gst_no,0,2) != substr($comp_gst_no,0,2)){
					$gst_nm = 'IGST';
					
					if($gst_flag=='Y'){
					
						$gst_nm = $account_name;
						
					}	
					
					$account_id_igst = '26';
					$effect_c 			= "Dr";
					$sql = "INSERT INTO tally_journal_entry( record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no ) 
					VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'D', '$account_id_igst', '$gst_nm', '$budget_head',  '$effect_c', '$gst_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
					mysqli_query($con, $sql);
					$last_insert_id = mysqli_insert_id($con);
					echo mysqli_error($con);	
				}
				else {
					
					if($gst_flag=='Y'){
					
						$gst_nm = $account_name;
						$effect_c 			= "Dr";
						$sql = "INSERT INTO tally_journal_entry( record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no ) 
						VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'D', '$account_id', '$gst_nm', '$budget_head',  '$effect_c', '$gst_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
						mysqli_query($con, $sql);
						$last_insert_id = mysqli_insert_id($con);
						echo mysqli_error($con);
						
					}
					else { 
						$gst_amount = $gst_amount /2;
					
						$account_id_sgst = '25';
						$effect_c 			= "Dr";
						$sql = "INSERT INTO tally_journal_entry( record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no ) 
						VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'D', '$account_id_sgst', 'SGST', '$budget_head',  '$effect_c', '$gst_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
						mysqli_query($con, $sql);
						$last_insert_id = mysqli_insert_id($con);
						echo mysqli_error($con);
						
						$account_id_cgst = '24';
						$sql = "INSERT INTO tally_journal_entry( record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no ) 
						VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'D', '$account_id_cgst', 'CGST', '$budget_head',  '$effect_c', '$gst_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
						mysqli_query($con, $sql);
						$last_insert_id = mysqli_insert_id($con);
						echo mysqli_error($con);
					}
				}
				
			}
			
			if($tds_amount >0 ){
				
				$sql = "INSERT INTO tally_journal_entry( record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no ) 
				VALUES( '$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$tds_id', '$tds_name', '$budget_head',  'Cr', '$tds_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
				
				$tot_amount = $tot_amount - $tds_amount;
				
			}
				
/* 			if( $prev_account_name == $account_name ){
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. "<BR>";
			}
			else {
 */				
				$amount_v = $amount ;
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'E', '$account_id', '$budget_code', '$budget_head',  '$effect', '$amount_v', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no') ";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
//			}
			
			
			$prev_account_name = $account_name;
			$prev_budget_head  = $budget_head;
			
//echo $sql."<BR>";			exit();
	
		}

		$fare  		= 0;
 
		$record_type 		= "Purchase-P2P";
		$doc_no				= $re_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		//$invoice_no			= $invoice_no;
		$invoice_date		= date('d-m-Y', strtotime($invoice_date));
		
		$account_type_a	    = 'V';
		$val_type_a 	    = 'V';
		
		if( $doc_type=='RE' || $doc_type=='TE' ){
			$account_type_a	    = 'U';
		}
		
		$account_type		= $account_type_a;
		$val_type			= $val_type_a;
		
		$account_id			= $supplier_id;
		$account_name       = $party_name;
		$effect				= 'Cr';
 		$amount				= $tot_amount + $fare + $gst_amount_v;
		//$narration			= $narration;
		$cheque_no			= '';
		 
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$val_type', '$account_id', '$account_name', '$budget_head', '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		//$value = "<script>window.location.href='company_expense.php?sub=edit&id=$re_id&active5=active';</script>";
		//bal_amount = '$amount'
		$sql = " UPDATE sma_travel_expenses SET total_amount = '$amount', bal_amount = 0 , tally_status='R', tally_created_by = '$userid', tally_created_date = now() where id = '$re_id' ";
		mysqli_query($con, $sql);
//echo $sql."<BR>";
//exit();
		
		if($doc_type=='CE' ){
			$value = "<script>window.location.href='company_expense.php?sub=edit&id=$re_id&IN=in';</script>";
		}
		
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

			//$amount_tot		= $amount_tot + $r2['amount'];
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
