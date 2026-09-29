<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$modulePath = "provisional_jv/";
	
	$user   			= $_SESSION['user'];
	$userid   			= $_SESSION['usrid'];
	
?>

<?php
	
if(isset($_POST['sub1'])){
	
	$company_id = $_POST['company_id'];
	$st_flag = $_POST['st_flag'];
	
	$sql = "select * from company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$provional_jv_name  		= $r2['provional_jv_name'];
	$provional_revenue_jv_name  = $r2['provional_revenue_jv_name'];
	if($st_flag=='E'){
		$provional_jv_name = $provional_jv_name;
	}
	else if($st_flag=='R'){
		$provional_jv_name = $provional_revenue_jv_name;
	}
?>

	<div class="form-group">
	
		<label class="col-md-1 control-label">Provisional&nbsp;A/c</label>
		<div class="col-md-4">
			<input type="text" class="form-control" id="provional_jv_name" name="provional_jv_name" readonly autocomplete="off" value="<?php echo $provional_jv_name;?>" >
		</div>

	</div>
	
<?php
		
}	

	if(isset($_POST['sub2'])){
		
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		$effect 		= $_POST['effect'];
		$amount 		= round($_POST['amount'],0);
		$provisional_jv_hdr_id 		= $_POST['provisional_jv_hdr_id'];
//echo $amount. "<<>>";
			
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		/* $sql = "SELECT * FROM account_mst where (account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//echo $sql. "<BR>";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage 	= $r3['percentage'];
		$account_type_a 	= $r3['account_type'];
		$deduction_from		= $r3['deduction_from']; */

		$sql = "INSERT INTO sma_provisional_jv_details( provisional_jv_hdr_id, account_id, effect, amount, narration, tally_entry_date, reversal_entry )
				VALUES( '$provisional_jv_hdr_id', '$account_id', '$effect', '$amount', '$narration', '$doc_date', 'N' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		$value = "<script>window.location.href='edit.php?sub=edit&id=$provisional_jv_hdr_id';</script>";
	
		echo $value;
	
	}
	
	if(isset($_POST['sub3'])){

		$provisional_jv_hdr_id 	= $_POST['provisional_jv_hdr_id'];
		$gst_flag	= '';
	
		$sql 	= "select * from tally_journal_entry where doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' ";
//echo $sql. "<BR>";

		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' ";
			$q2 	= mysqli_query($con, $sql);
		}
//echo $sql."<BR>";
		
		$sql 	= "select * from sma_provisional_jv_hdr where id = '$provisional_jv_hdr_id' ";
//echo $sql. "<BR>";		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$provional_jv_name 	= $r2['provisional_account_name'];
		$invoice_date   	= date('d-m-Y', strtotime($r2['dated']));
		$doc_date			= date('d-m-Y', strtotime($r2['dated']));
		$company_id 		 = $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$provisional_jv_flag 	 = $r2['provisional_jv_flag'];
		
		$tot_amount = 0;
		$prev_budget_head = '';
		$last_insert_id = '';
		$account_name_prev = '';
		$sql="SELECT * from sma_provisional_jv_details where 1 and provisional_jv_hdr_id = '$provisional_jv_hdr_id' order by account_id desc";
//echo $sql. "<BR>";
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result)){
			
			$account_id	= $row['account_id'];
			
			$sql = "SELECT * FROM sma_budget where id = '$account_id' ";
			$sql = "SELECT b.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' and a.id  = '$account_id' ";
//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			//$account_id   = $r2['product_id'];
			//$budget_code  = $r2['budget_code'];
			$budget_head_id  = $r2['budget_id'];
			
			$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_head_id' ";
//echo $sql. "<BR>";				
			$cqry = mysqli_query($con,$sql);
			$com = mysqli_fetch_array($cqry);
			$budget_code 			= $com['budget_code'];
			$budget_head 			= $com['budget_head'];
			
			$amount 		= $row['amount'];
			
			$tot_amount 	= $tot_amount + $amount ;
			$effect 			= $row['effect'];
			
			if($provisional_jv_flag=='R' && $effect == 'Dr'){
				$effect = 'Cr';
			}
			else if($provisional_jv_flag=='R' && $effect == 'Cr'){
				$effect = 'Dr';
			}
			
			$record_type 		= "Provisional-P2P";
			
			if($provisional_jv_flag=='R'){
				$record_type = 'Provisional-Income-P2P';
			}
			
			$doc_no				= $provisional_jv_hdr_id;		
			//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
			$account_type		= 'A';
			$account_id			= $account_id;
			$account_name       = $budget_code;
			
			$cheque_no			= '';
			
			if( $account_name_prev == $account_name ){
				
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
//echo $sql."<BR>";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. "<BR>";
			}
			else {
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'PV', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);

			}
			
			$prev_budget_head 		= $budget_head;
			$prev_budget_code		= $budget_code;
			$account_name_prev		= $account_name;
	
		}
		
		
		//echo $sql."<BR>"; exit();
		$record_type 		= "Provisional-P2P";
		$doc_no				= $provisional_jv_hdr_id;		
		//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
		$account_type		= 'V';
		$account_id			= $company_id;
		$account_name       = $provional_jv_name;
		$effect				= 'Cr';
		
		if($provisional_jv_flag == 'R' && $effect == 'Cr'){
			$effect				= 'Dr';
		}
		
		$amount				= round($tot_amount,0);
		$narration			= $narration;
		$cheque_no			= '';
		$gst_no				= '';
		
		$amount = $amount - ($deduction1_amount + $deduction2_amount + $deduction3_amount);
		 $sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'PV', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//Reversal JV start		
//	echo $sql. "<BR>"; reversal_flag 01-09-2023
		$mmth	= substr($doc_date,3,2);
		$yyear	= substr($doc_date,6,4);
		
		if($mmth==01){
			$mmth_word = 'January';
		}
		else if($mmth==02){
			$mmth_word = 'February';
		}
		else if($mmth==03){
			$mmth_word = 'March';
		}
		else if($mmth==04){
			$mmth_word = 'April';
		}
		else if($mmth==05){
			$mmth_word = 'May';
		}
		else if($mmth==06){
			$mmth_word = 'June';
		}
		else if($mmth==07){
			$mmth_word = 'July';
		}
		else if($mmth=='08'){
			$mmth_word = 'August';
		}
		else if($mmth=='09'){
			$mmth_word = 'September';
		}
		else if($mmth==10){
			$mmth_word = 'October';
		}
		else if($mmth==11){
			$mmth_word = 'November';
		}
		else if($mmth==12){
			$mmth_word = 'December';
		}
		
		$narration = "Being " . $provional_jv_name . " created for the month of ".$mmth_word.' '.$yyear ;
		$sql = "UPDATE tally_journal_entry SET status = 'C' , narration =  '$narration' WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and reversal_flag != 'R' ";
		mysqli_query($con, $sql);
		
		$sql = " UPDATE sma_provisional_jv_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'C' , tally_narration = '$narration' 
					WHERE id = '$provisional_jv_hdr_id' ";
		mysqli_query($con, $sql);
				
		if($mmth==12){
			$mmth 	= '01';
			$yyear 	= $yyear + 1;
		}
		else {
			$mmth 	= $mmth + 1;
		} 
		
		$doc_date = '01-'.$mmth.'-'.$yyear;
		
		if($provisional_jv_flag == 'R' ){
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Cr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Dr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Dr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Cr' and reversal_flag !='R' ";
			mysqli_query($con, $sql);
		}	
		else {
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Dr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Cr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Cr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Dr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
		}
		
		
		$narration = "Being " .$provional_jv_name. " reversed for the month of ".$mmth_word.' '.$yyear ;
		$sql = "UPDATE tally_journal_entry SET status = 'C' , narration =  '$narration' WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and reversal_flag = 'R' ";
		mysqli_query($con, $sql);

		$sql = " UPDATE sma_provisional_jv_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'C' , tally_narration_reversal = '$narration' 
					WHERE id = '$provisional_jv_hdr_id' ";
		mysqli_query($con, $sql);

	
		$value = "<script>window.location.href='edit.php?sub=edit&id=$provisional_jv_hdr_id&IN=in';</script>";
	
		echo $value;
		
	}
					
if(isset($_POST['sub4'])){
	
	$mm 	= substr($_POST['mmyyyy'],0,2);
	$yyyy 	= substr($_POST['mmyyyy'],3,4);
	
	//echo $mm.' ' .$yyyy;
	
	if($mm=='04' || $mm=='06' || $mm=='09' || $mm=='11' ){
		$dd = 30;
	}
    else if ($mm=='01' || $mm=='03' || $mm=='05' || $mm=='07' || $mm=='08' || $mm=='10' || $mm=='12' ){
		$dd = 31;
	}
	else if ($mm=='02'){
		$dd = 28;
	}
	
	$fdate = $dd. '-'.$mm.'-'.$yyyy;
?>

	<input type="text" class="form-control" id="dated" name="dated" readonly value="<?= $fdate; ?>" >
	
<?php
		
}


	if(isset($_POST['sub5'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
	
		$doc_type = 'PV';
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' 
					and trans_type = '$trans_type'";	
//echo $sql. "<BR>";
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];

		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
		}
		if($approval_role_4>0){
			$row_affected = $row_affected + 1;
			$required4 = 'REQUIRED';
		}
		if($approval_role_5>0){
			$row_affected = $row_affected + 1;
			$required5 = 'REQUIRED';
		}
		if($approval_role_6>0){
			$row_affected = $row_affected + 1;
			$required6 = 'REQUIRED';
		}
		if($approval_role_7>0){
			$row_affected = $row_affected + 1;
			$required7 = 'REQUIRED';
		}
		if($approval_role_8>0){
			$row_affected = $row_affected + 1;
			$required8 = 'REQUIRED';
		}

?>    
		<div class="box-footer">
						<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_1) FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_1 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id )  ";
											
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_1" id="APPROVER_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_2) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_2 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_2" id="APPROVER_2"  name="approver_2" <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_3) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_3 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_3" id="APPROVER_3"  name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_4>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_4) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_4 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_4" id="APPROVER_4"  name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_5) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_5" name="approver_5" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_6) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_6" name="approver_6" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_7) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_7" name="approver_7" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_8) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_8" name="approver_8" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getvalidate();getsubmit();" >
								</div>
							
						</div>
						<br>
<?php						
	
	}

if(isset($_POST['sub9'])){

		$value ='';
        $pv_id = $_POST['pv_id'];
		$srno  = $pv_id;
		if($_POST['pv_id'] == ''){$pv_id = '';}
		
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approved 			= $_POST['approved'];
		$approver 			= $_POST['approver'];

		$company			= $_POST['company'];		
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_provisional_jv_hdr where id = '$pv_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		//approver_1_status == 'Submitted' need to check for dupplicate approver 
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status == 'Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status == 'Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status == 'Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status == 'Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && $approver_6_status == 'Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && $approver_7_status == 'Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
//echo $statusap . ' <<>> ' . $approval_status."<BR>"; 
//exit();		
		
	if($statusap=='Reject'){
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "update sma_provisional_jv_hdr set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$pv_id' ";
//echo $sql. "<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

			
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'PV', '$pv_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_provisional_jv_hdr set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$pv_id'";
//echo $sql."<BR>";		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'PV', '$pv_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
			
			create_provisional_jv($pv_id);
			
		}

	}
	
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
//echo $sql. "<BR>";
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$pv_id;
		$msg = 'Purchase Order Number : '.$pv_id . ' ' . 'Dated : ' . date("d-m-Y");
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		/* $baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pv_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$pv_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$pv_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
 */
		include "pv_mail.php";
		
		$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		//$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}


function create_provisional_jv($provisional_jv_hdr_id){
	
	include('../dbcon.php');
	
	$sql 	= "select * from tally_journal_entry where doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' ";
//echo $sql. "<BR>";

		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' ";
			$q2 	= mysqli_query($con, $sql);
		}
//echo $sql."<BR>";
		
		$sql 	= "select * from sma_provisional_jv_hdr where id = '$provisional_jv_hdr_id' ";
//echo $sql. "<BR>";		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$provional_jv_name 	= $r2['provisional_account_name'];
		$invoice_date   	= date('d-m-Y', strtotime($r2['dated']));
		$doc_date			= date('d-m-Y', strtotime($r2['dated']));
		$company_id 		 = $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$provisional_jv_flag 	 = $r2['provisional_jv_flag'];
		
		$tot_amount = 0;
		$prev_budget_head = '';
		$last_insert_id = '';
		$account_name_prev = '';
		$sql="SELECT * from sma_provisional_jv_details where 1 and provisional_jv_hdr_id = '$provisional_jv_hdr_id' order by account_id desc";
//echo $sql. "<BR>";
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result)){
			
			$account_id	= $row['account_id'];
			
			$sql = "SELECT * FROM sma_budget where id = '$account_id' ";
			$sql = "SELECT b.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' and a.id  = '$account_id' ";
//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			//$account_id   = $r2['product_id'];
			//$budget_code  = $r2['budget_code'];
			$budget_head_id  = $r2['budget_id'];
			
			$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_head_id' ";
//echo $sql. "<BR>";				
			$cqry = mysqli_query($con,$sql);
			$com = mysqli_fetch_array($cqry);
			$budget_code 			= $com['budget_code'];
			$budget_head 			= $com['budget_head'];
			
			$amount 		= $row['amount'];
			
			$tot_amount 	= $tot_amount + $amount ;
			$effect 			= $row['effect'];
			
			if($provisional_jv_flag=='R' && $effect == 'Dr'){
				$effect = 'Cr';
			}
			else if($provisional_jv_flag=='R' && $effect == 'Cr'){
				$effect = 'Dr';
			}
			
			$record_type 		= "Provisional-P2P";
			
			if($provisional_jv_flag=='R'){
				$record_type = 'Provisional-Income-P2P';
			}
			
			$doc_no				= $provisional_jv_hdr_id;		
			//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
			$account_type		= 'A';
			$account_id			= $account_id;
			$account_name       = $budget_code;
			
			$cheque_no			= '';
			
			if( $account_name_prev == $account_name ){
				
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
//echo $sql."<BR>";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. "<BR>";
			}
			else {
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'PV', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);

			}
			
			$prev_budget_head 		= $budget_head;
			$prev_budget_code		= $budget_code;
			$account_name_prev		= $account_name;
	
		}
		
		
		//echo $sql."<BR>"; exit();
		$record_type 		= "Provisional-P2P";
		$doc_no				= $provisional_jv_hdr_id;		
		//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
		$account_type		= 'V';
		$account_id			= $company_id;
		$account_name       = $provional_jv_name;
		$effect				= 'Cr';
		
		if($provisional_jv_flag == 'R' && $effect == 'Cr'){
			$effect				= 'Dr';
		}
		
		$amount				= round($tot_amount,0);
		$narration			= $narration;
		$cheque_no			= '';
		$gst_no				= '';
		
		$amount = $amount - ($deduction1_amount + $deduction2_amount + $deduction3_amount);
		 $sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'PV', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//Reversal JV start		
//	echo $sql. "<BR>"; reversal_flag 01-09-2023
		$mmth	= substr($doc_date,3,2);
		$yyear	= substr($doc_date,6,4);
		
		if($mmth==01){
			$mmth_word = 'January';
		}
		else if($mmth==02){
			$mmth_word = 'February';
		}
		else if($mmth==03){
			$mmth_word = 'March';
		}
		else if($mmth==04){
			$mmth_word = 'April';
		}
		else if($mmth==05){
			$mmth_word = 'May';
		}
		else if($mmth==06){
			$mmth_word = 'June';
		}
		else if($mmth==07){
			$mmth_word = 'July';
		}
		else if($mmth=='08'){
			$mmth_word = 'August';
		}
		else if($mmth=='09'){
			$mmth_word = 'September';
		}
		else if($mmth==10){
			$mmth_word = 'October';
		}
		else if($mmth==11){
			$mmth_word = 'November';
		}
		else if($mmth==12){
			$mmth_word = 'December';
		}
		
		$narration = "Being " . $provional_jv_name . " created for the month of ".$mmth_word.' '.$yyear ;
		$sql = "UPDATE tally_journal_entry SET status = 'C' , narration =  '$narration' WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and reversal_flag != 'R' ";
		mysqli_query($con, $sql);
		
		$sql = " UPDATE sma_provisional_jv_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'C' , tally_narration = '$narration' 
					WHERE id = '$provisional_jv_hdr_id' ";
		mysqli_query($con, $sql);
				
		if($mmth==12){
			$mmth 	= '01';
			$yyear 	= $yyear + 1;
		}
		else {
			$mmth 	= $mmth + 1;
		} 
		
		$doc_date = '01-'.$mmth.'-'.$yyear;
		
		if($provisional_jv_flag == 'R' ){
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Cr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Dr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Dr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Cr' and reversal_flag !='R' ";
			mysqli_query($con, $sql);
		}	
		else {
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Dr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Cr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
			
			$sql 	= "INSERT INTO tally_journal_entry( reversal_flag, record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head )
			SELECT 'R', record_type, doc_type, doc_no, '$doc_date', supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, 'Cr', amount, narration, cheque_no, address, gst_no, state, company_id, budget_head  from tally_journal_entry WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and effect = 'Dr' and reversal_flag !='R' ";
	//echo $sql. "<BR>";
			mysqli_query($con, $sql);
		}
		/* if($mmth==01){
			$mmth_word = 'January';
		}
		else if($mmth==02){
			$mmth_word = 'February';
		}
		else if($mmth==03){
			$mmth_word = 'March';
		}
		else if($mmth==04){
			$mmth_word = 'April';
		}
		else if($mmth==05){
			$mmth_word = 'May';
		}
		else if($mmth==06){
			$mmth_word = 'June';
		}
		else if($mmth==07){
			$mmth_word = 'July';
		}
		else if($mmth=='08'){
			$mmth_word = 'August';
		}
		else if($mmth=='09'){
			$mmth_word = 'September';
		}
		else if($mmth==10){
			$mmth_word = 'October';
		}
		else if($mmth==11){
			$mmth_word = 'November';
		}
		else if($mmth==12){
			$mmth_word = 'December';
		}
		 */
		
		$narration = "Being " .$provional_jv_name. " reversed for the month of ".$mmth_word.' '.$yyear ;
		$sql = "UPDATE tally_journal_entry SET status = 'C' , narration =  '$narration' WHERE doc_no = '$provisional_jv_hdr_id' and doc_type = 'PV' and reversal_flag = 'R' ";
		mysqli_query($con, $sql);

		$sql = " UPDATE sma_provisional_jv_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'C' , tally_narration_reversal = '$narration' 
					WHERE id = '$provisional_jv_hdr_id' ";
		mysqli_query($con, $sql);
		
}		
?>	

