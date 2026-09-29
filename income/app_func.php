<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$modulePath = "income/";
	
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
	
?>

<select class="form-control" id="income_account_name" name="income_account_name"  >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' and company_id in ($company_id) ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'] ?></option>
										<?php } ?>
			</select>	
	
<?php
		
}	

	if(isset($_POST['sub2'])){
		
		$account_id 	= $_POST['account_id'];
		$narration	 	= $_POST['narration'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		
		$amount 		= round($_POST['amount'],0);
		$quantity 		= $_POST['quantity'];
		$income_hdr_id 		= $_POST['income_hdr_id'];
		$gst_id				= $_POST['gst_id'];
		$gst				= $_POST['gst'];
//echo $amount. "<<>>";
			
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		$sql = "INSERT INTO sma_income_dtl( income_hdr_id, account_id, quantity, amount, narration, tally_entry_date, gst_id, gst )
				VALUES( '$income_hdr_id', '$account_id', '$quantity', '$amount', '$narration', '$doc_date', '$gst_id', '$gst' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		$value = "<script>window.location.href='edit.php?sub=edit&id=$income_hdr_id';</script>";
	
		echo $value;
	
	}
	
	if(isset($_POST['sub3'])){

		$income_hdr_id 	= $_POST['income_hdr_id'];
		$gst_flag	= '';
	
		$sql 	= "select * from tally_journal_entry where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
//echo $sql. "<BR>";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
			$q2 	= mysqli_query($con, $sql);
		}
//echo $sql."<BR>";
		
		$sql 	= "select * from sma_income_hdr where id = '$income_hdr_id' ";
//echo $sql. "<BR>";		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$income_account_id 	= $r2['income_account_name'];
		$party_id 			= $r2['party_id'];
		$invoice_date   	= date('d-m-Y', strtotime($r2['dated']));
		$doc_date			= date('d-m-Y', strtotime($r2['dated']));
		$company_id 		 = $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		
		$sale_to_flag	 	= $r2['sale_to_flag'];
		$income_jv_flag 	= $r2['income_jv_flag'];
		$doctype='';
		if($income_jv_flag=='R'){
			$record_type 		= "Income-P2P";
		}
		else if($income_jv_flag=='S'){
			$record_type 		= "Sales-P2P";
		}
			
		if($sale_to_flag=='S'){
			$sql = "SELECT * FROM sma_party_mst where id = '$party_id' ";
	//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name  	= $r2['party_name'];
		}
		else if($sale_to_flag=='U'){
			$sql = "SELECT * FROM sma_user where id = '$party_id' ";
	//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name  	= $r2['username'];
		}	
		
		$sql = "SELECT * FROM account_mst where id = '$income_account_id' ";
//echo $sql. "<BR>";			 
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$income_account_name  	= $r2['account_name'];
			
		$tot_amount = 0;
		$prev_budget_head = '';
		$last_insert_id = '';
		$account_name_prev = '';
		
		
		$sql="SELECT * from sma_income_dtl where 1 and income_hdr_id = '$income_hdr_id' order by account_id desc";
//echo $sql. "<BR>";
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result)){
			
			$account_id	= $row['account_id'];
			$gst		= $row['gst'];
			$gst_id		= $row['gst_id'];
			$amount 		= $row['amount'];
			
			$tot_amount 	= $tot_amount + $amount ;
			
			$gst_amt		= round( ($amount * $gst ) / 100,0);
			
			$sql = "SELECT * FROM account_mst where id = '$account_id' ";
//echo $sql. "<BR>";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_name  	  = $r2['account_name'];
			$account_type_v	  = $r2['account_type'];
			
			$effect 			= 'Cr';
			
			$doc_no				= $income_hdr_id;		
			$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
			$account_type		= 'A';
			$account_id			= $account_id;
			
			$cheque_no			= '';
			
			if($income_jv_flag=='S'){
				if( $account_name_prev == $account_name ){
					
					$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
	//echo $sql."<BR>";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
					//echo $sql. "<BR>";
				}
				else {
					
					$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
					VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type_v', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
	//echo $sql."<BR>";
					mysqli_query($con, $sql);
					$last_insert_id = mysqli_insert_id($con);
					echo mysqli_error($con);

					if($gst_amt>0){
						$account_name_sgst ='Output SGST';
						$account_id_sgst	= 25;
						
						$gst_amt_v = $gst_amt / 2;
						$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
						VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id_sgst', '$account_name_sgst', '$effect', '$gst_amt_v', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
						mysqli_query($con, $sql);
						$account_name_cgst ='Output CGST';
						$account_id_cgst	= 24;
						
						$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
						VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id_cgst', '$account_name_cgst', '$effect', '$gst_amt_v', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
						mysqli_query($con, $sql);
					}	
					
				}
				
				$account_name_prev		= $account_name;
		
			}
		
		}
		
		//echo $sql."<BR>"; exit();
		
		$doc_no				= $income_hdr_id;		
		//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
		$account_type		= 'V';
		$account_id			= $income_account_id;
		$account_name       = $income_account_name;
		$effect				= 'Dr';
		
		$amount				= round($tot_amount,0);
		//$narration			= $narration;
		$cheque_no			= '';
		$gst_no				= '';
		
		if($income_jv_flag=='S'){
			$amount = $amount + $gst_amt;
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$party_id', '$party_name',   '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		}
		
		if($income_jv_flag=='R'){
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, reversal_flag,   effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name',  '', '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		$effect				= 'Cr';
		
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, reversal_flag,  effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$party_id', '$party_name', '',  '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		
		}
		
			
		$sql = " UPDATE sma_income_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'R' 
					WHERE id = '$income_hdr_id' ";
		mysqli_query($con, $sql);
				
		$sql = "UPDATE `tally_journal_entry` set status ='R' , tally_uploaded_on = now() where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
		mysqli_query($con,$sql);		
		
//exit();
	
		$value = "<script>window.location.href='edit.php?sub=edit&id=$income_hdr_id&IN=in';</script>";
	
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
		$income_jv_flag		= $_POST['income_jv_flag'];
	
		$doc_type = 'IN';
		
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


if(isset($_POST['sub6'])){

		$value ='';
        $st_flag = $_POST['st_flag'];
	if($st_flag=='S'){	
?>
		
		<select class="form-control" id="party_id" name="party_id" required >
			<option value="">Select Supplier</option>	
			<?php
			$sql="SELECT * FROM sma_party_mst where 1 ORDER BY party_name ASC";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
			?>
			<option value="<?php echo $r2['id']?>" ><?php echo $r2['party_name'] ?></option>
			<?php } ?>
		</select>
<?php
	}
	else if($st_flag=='U'){
?>
		
		<select class="form-control" id="party_id" name="party_id" required >
			<option value="">Select Employee</option>	
			<?php
			$sql="SELECT * FROM sma_user where 1 and active = '1' ORDER BY username ASC";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r2 = mysqli_fetch_array($q2)){
			?>
			<option value="<?php echo $r2['id']?>" ><?php echo $r2['username'] ?></option>
			<?php } ?>
		</select>
<?php
	}
	
}
									
if(isset($_POST['sub9'])){

		$value ='';
        $in_id = $_POST['in_id'];
		$srno  = $in_id;
		if($_POST['in_id'] == ''){$in_id = '';}
		
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
 
		$sql = " select * from sma_income_hdr where id = '$in_id' "; 
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
		$income_background	= $r2['income_background'];
		$income_jv_flag 	= $r2['income_jv_flag'];
		$doctype='';
		if($income_jv_flag=='R'){
			$doctype = 'Receipt';
		}
		else if($income_jv_flag=='S'){
			$doctype = 'Sales';
		}
			
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
		
		$sql = "update sma_income_hdr set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$in_id' ";
//echo $sql. "<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

			
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'IN', '$in_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
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
		$sql = "update sma_income_hdr set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$in_id'";
//echo $sql."<BR>";		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'IN', '$in_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
			
			create_provisional_jv($in_id);
			
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

		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$in_id;
		$msg = 'Purchase Order Number : '.$in_id . ' ' . 'Dated : ' . date("d-m-Y");
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		/* $baseurl1 = $baseurl.$modulePath.'edit.php?id='.$in_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$in_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$in_id. '&status=R'.'&emid='.$user_email;
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


	if(isset($_POST['sub22'])){
		
		$modulePath 	= "income/";
		//$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		$effect 		= $_POST['effect'];
		$amount 		= $_POST['amount'];
		$narration 		= $_POST['narration'];
		$income_hdr_id 		    = $_POST['income_hdr_id'];
		$doc_type 		= 'IN';
		
		$sql 	= " select * from sma_income_hdr where 1 and id = '$income_hdr_id' ";
//echo $sql. "<BR>";
		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$company_id		= $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$gst_flag		 	 = $r2['gst_flag'];
			
		$invoice_no = '';
		$amount_tds	= 0;
		$sql  = "SELECT * FROM `sma_income_dtl` where 1 and income_hdr_id = '$income_hdr_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r1 = mysqli_fetch_array($res1)){
		
			//$amount 		= $r1['amount'];	
		/* 	$invoice_no 	.= $r1['invoice_no'].' ';
			$invoice_date   = date('d-m-Y', strtotime($r1['dated']));
		 */	
		}
		
		$record_type 		= "Income-P2P";
		$doc_no				= $income_hdr_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		$cheque_no			='';
		$address 			='';
		$gst_no 			='';
		$state				='';
		
		$sql = "SELECT * FROM account_mst where id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$percentage			= $r3['percentage'];
		$account_type		= $r3['account_type'];
		$account_name		= $r3['account_name'];
		
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
				$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type in( 'S' ) and doc_type='$doc_type' and doc_no='$doc_no' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
		}
		else if($percentage > 0 && $account_type == 'A'){
			
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
		
			$sql = " UPDATE tally_journal_entry SET status = 'R' WHERE doc_type = '$doc_type' AND doc_no = '$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
			$sql 	= " UPDATE sma_income_hdr SET tally_status = 'R' where 1 and id = '$income_hdr_id' ";
			mysqli_query($con, $sql);
		
//echo $sql ."<BR>";
//exit();

		$value = "<script>window.location.href='edit.php?sub=edit&id=$income_hdr_id';</script>";
		echo $value;
	//	https://hcone.co.in/workflow2020/travel_approval/company_expense.php?sub=edit&id=1901
	
	}


function create_provisional_jv($income_hdr_id){
	
	include('../dbcon.php');
	
	$user   			= $_SESSION['user'];
	
		$sql 	= "select * from tally_journal_entry where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
			$q2 	= mysqli_query($con, $sql);
		}
		
		$sql 	= "select * from sma_income_hdr where id = '$income_hdr_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$income_account_id 	= $r2['income_account_name'];
		$party_id 			= $r2['party_id'];
		$invoice_date   	= date('d-m-Y', strtotime($r2['dated']));
		$doc_date			= date('d-m-Y', strtotime($r2['dated']));
		$company_id 		 = $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		
		$sale_to_flag 		= $r2['sale_to_flag'];
		$income_jv_flag 	= $r2['income_jv_flag'];
		$doctype='';
		if($income_jv_flag=='R'){
			$record_type 		= "Income-P2P";
		}
		else if($income_jv_flag=='S'){
			$record_type 		= "Sales-P2P";
		}
			
		if($sale_to_flag=='S'){
			$sql = "SELECT * FROM sma_party_mst where id = '$party_id' ";
	//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name  	= $r2['party_name'];
		}
		else if($sale_to_flag=='U'){
			$sql = "SELECT * FROM sma_user where id = '$party_id' ";
	//echo $sql. "<BR>";			 
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name  	= $r2['username'];
		}	
		
		$sql = "SELECT * FROM account_mst where id = '$income_account_id' ";
//echo $sql. "<BR>";			 
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$income_account_name  	= $r2['account_name'];
		$account_type_v			= $r2['account_type'];
			
		$tot_amount = 0;
		$prev_budget_head = '';
		$last_insert_id = '';
		$account_name_prev = '';
		
		
		$sql="SELECT * from sma_income_dtl where 1 and income_hdr_id = '$income_hdr_id' order by account_id desc";
//echo $sql. "<BR>";
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result)){
			
			$account_id	= $row['account_id'];
			$gst		= $row['gst'];
			$gst_id		= $row['gst_id'];
			$amount 		= $row['amount'];
			
			$tot_amount 	= $tot_amount + $amount ;
			
			$gst_amt		= round( ($amount * $gst ) / 100,0);
			
			$sql = "SELECT * FROM account_mst where id = '$account_id' ";
//echo $sql. "<BR>";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_name  	= $r2['account_name'];
			
			$effect 			= 'Cr';
			
			$doc_no				= $income_hdr_id;		
			$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
			$account_type		= 'A';
			$account_id			= $account_id;
			
			$cheque_no			= '';
			
			if($income_jv_flag=='S'){
				if( $account_name_prev == $account_name ){
					
					$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
	//echo $sql."<BR>";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
					//echo $sql. "<BR>";
				}
				else {
					
					$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
					VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type_v', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
	//echo $sql."<BR>";
					mysqli_query($con, $sql);
					$last_insert_id = mysqli_insert_id($con);
					echo mysqli_error($con);

					if($gst_amt>0){
						$gst_amt_v = $gst_amt / 2;
						
						$account_name_sgst ='Output SGST';
						$account_id_sgst	= 25;
						$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
						VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id_sgst', '$account_name_sgst', '$effect', '$gst_amt_v', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
						mysqli_query($con, $sql);
						
						$account_name_cgst ='Output CGST';
						$account_id_cgst	= 24;
						$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
						VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id_cgst', '$account_name_cgst', '$effect', '$gst_amt_v', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
						mysqli_query($con, $sql);
					}	
					
				}
				
				$account_name_prev		= $account_name;
		
			}
		
		}
		
		//echo $sql."<BR>"; exit();
		
		$doc_no				= $income_hdr_id;		
		//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
		$account_type		= 'V';
		$account_id			= $income_account_id;
		$account_name       = $income_account_name;
		$effect				= 'Dr';
		
		$amount				= round($tot_amount,0);
		//$narration			= $narration;
		$cheque_no			= '';
		$gst_no				= '';
		
		if($income_jv_flag=='S'){
			$amount = $amount + $gst_amt;
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$party_id', '$party_name',   '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		}
		
		if($income_jv_flag=='R'){
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, reversal_flag,   effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name',  '', '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		$effect				= 'Cr';
		
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, reversal_flag,  effect, amount, narration, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'IN', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$party_id', '$party_name', '',  '$effect', '$amount', '$tally_narration',  '$address', '$gst_no', '$state', '$company_id', '$account_name' ) ";
		mysqli_query($con, $sql);
		
		}
		
			
		$sql = " UPDATE sma_income_hdr SET tally_created_by = '$user', tally_created_date = now(), tally_ticked_by = '$user', 
				tally_status = 'C' 
					WHERE id = '$income_hdr_id' ";
		mysqli_query($con, $sql);
				
		$sql = "UPDATE `tally_journal_entry` set status ='C' , tally_uploaded_on = now() where doc_no = '$income_hdr_id' and doc_type = 'IN' ";
		mysqli_query($con,$sql);	
		
}		
?>	

