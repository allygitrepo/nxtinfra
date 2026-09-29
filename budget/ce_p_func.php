<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

	$userid   	= $_SESSION['usrid'];
	$comid  = $_SESSION['comid'];
	
?>

<?php
	if(isset($_POST['sub1'])){
		
		$modulePath 	= "budget/";
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		$effect 		= $_POST['effect'];
		$amount 		= $_POST['amount'];
		$narration 		= $_POST['narration'];
		$bj_id 		    = $_POST['bj_id'];
		$doc_type = 'BT';
		
		$sql 	= " select * from sma_travel_expenses where exp_type = '$exp_type' and id = '$bj_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$company_id		= $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$gst_flag		 	 = $r2['gst_flag'];
		
			
		$invoice_no = '';
		$amount_tds	= 0;
		$sql  = "SELECT * FROM `sma_expenses` where exp_type = '$exp_type' and approval_ref_no = '$bj_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r1 = mysqli_fetch_array($res1)){
		
			$invoice_no 	.= $r1['invoice_no'].' ';
			$invoice_date   = date('d-m-Y', strtotime($r1['dated']));
			
		}
		
		$record_type 		= "Journal-P2P";
		$doc_no				= $bj_id;		
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

		$value = "<script>window.location.href='budget_adjust_from_to.php?sub=edit&id=$bj_id&IN=in';</script>";
		
		
		echo $value;
	//	https://hcone.co.in/workflow2020/budget/budget_adjust_tally_jv.php?sub=edit&id=1901
	
	}
?>		

<?php

	if(isset($_POST['sub2'])){

		$modulePath = "budget/";
		$bj_id = $_POST['bj_id'];
		$doc_type = 'BT';
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$bj_id' and doc_type = '$doc_type' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$bj_id' and doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
		}	
		
		$sql 	= "select * from budget_adjust_from_to where 1 and id = '$bj_id' ";
//echo $sql. "<BR>";		
		$result 	= mysqli_query($con, $sql);
		$row 	= mysqli_fetch_array($result);
		
		$amount 		= $row['amount'];
		$remarks 		= $row['remarks'];

		$project = $row['project'];
		$sql = "select * from company where comp_id = '$project' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		$company_id = $r2['comp_id'];
										
		$budget_id_from = $row['budget_id_from'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_from' ";
//echo $sql. "<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_name_from   = $r2['name'];
		$account_name_from 	= $r2['budget_code'];
	
		$budget_id_to = $row['budget_id_to'];
		$sql = "select * from sma_budget a, sma_budget_name b where b.id = a.budget_name and a.id = '$budget_id_to' ";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_name_to 	= $r2['name'];
		$account_name_to 	= $r2['budget_code'];
		
		$budget_head_to = $row['budget_head_to'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_to' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_to = $r2['budget_head'];
//echo $sql. "<BR>";	
		
		$budget_head_from = $row['budget_head_from'];
		$sql = "select * from sma_budget_subgroup where id = '$budget_head_from' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_head_from = $r2['budget_head'];
//echo $sql. "<BR>";	
	
		$account_year 	= $row['account_year'];
		$tally_narration	= $remarks;
		
		$dated 			= date('d-m-Y', strtotime($row['dated']));
	
			$effect 			= "Cr";
			$record_type 		= "Journal-P2P";
			$doc_no				= $bj_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$invoice_date		= $dated;
			$cheque_no='';
			$invoice_no ='';
			$val_type ='';
			$gst_no ='';
			$state ='';
			$address ='';
			$pan_no ='';
			$mobile_no ='';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
				VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'B', '$budget_id_from', '$account_name_from', '$budget_head_from',  '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no') ";
			mysqli_query($con, $sql);
			//	$last_insert_id = mysqli_insert_id($con);
			echo mysqli_error($con);
			
		$doc_no				= $bj_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$effect				= 'Dr';
 		
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', 'B', '$val_type', '$budget_id_to', '$account_name_to', '$budget_head_to', '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql = " UPDATE budget_adjust_from_to SET tally_status='C', tally_narration = '$remarks', tally_created_by = '$userid', tally_created_date = now() where id = '$bj_id' ";
		mysqli_query($con, $sql);
//echo $sql."<BR>";
//exit();

		$tally_status = 'C';
		$sql="update budget_adjust_from_to set tally_ticked_by = '$user', tally_status = '$tally_status' , tally_updated_on = now() 
			where id='$bj_id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}

//TALLY STATUS UPDATE			
				$sql = "update `tally_journal_entry` set  status = '$tally_status' 	where doc_no = '$bj_id' and doc_type = 'BT' ";
				$r2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
//TALLY STATUS UPDATE

		$value = "<script>window.location.href='budget_adjust_from_to.php?sub=edit&id=$bj_id&IN=in';</script>";
		
		echo $value;
		
	}


	if(isset($_POST['sub3'])){
		
		$modulePath = "budget/";
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
		$bj_id	 		= $_POST['bj_id'];
		$exp_type		= $_POST['exp_type'];
		$amount_cgst	= $_POST['amount_cgst'];
		
		$amount_gst_tot	= $amount_cgst;
		
		$amount_tds	= 0;
		$amount_gst = 0;
		$sql  = "SELECT * FROM `sma_expenses` where exp_type = '$exp_type' and approval_ref_no = '$bj_id' ";
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
