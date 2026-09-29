<?php session_start();
	include('../dbcon.php');
	include "../baseurl.php";
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
	$comid  = $_SESSION['comid'];	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='accountName' name='account_name' >
						<option value=''>Select</option>";
	
		if ($id == 'S'){
			$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$party_name = $r2->party_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$party_name."</option>";
			};
		}
		else if ($id == 'A'){
			$sql = "SELECT * FROM account_mst where (account_type = 'B' or account_type = 'D' or account_type = 'A') and display_flag = 'Y' and del != 'Y'ORDER BY account_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$account_name = $r2->account_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$account_name."</option>";
			};
		}
		
		$value .= "</select>";
		
//$value = $sql;
		echo $value;
	
	}
	
	
    if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='account_Name' name='account_name' >
						<option value=''>Select</option>";
	
		if ($id == 'S'){
			$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$party_name = $r2->party_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$party_name."</option>";
			};
		}
		else if ($id == 'A'){
			$sql = "SELECT * FROM account_mst where del !='Y' ORDER BY account_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$account_name = $r2->account_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$account_name."</option>";
			};
		}
		
		$value .= "</select>";
		
//$value = $sql;
		echo $value;
	
	}
	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='invoiceNumber' name='invoice_Number' >
						<option value=''>Select</option>";
		
		if ($id == 'Y'){
			$sql = "SELECT * FROM sma_supplier_invoice where del != 'Y' ORDER BY invoice_date ASC ";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
					$supplier_invoice_no = $r2->supplier_invoice_no;
					$id = $r2->id;
					$value .= "<option value='".$supplier_invoice_no."'>".$supplier_invoice_no."</option>";
			};
		}
		else {
			$value .="<input type='text' class='form-control' id='invoiceNumber' > ";
		}
		
		$value .= "</select>";

//$value = $sql;
		echo $value;
	
	}
		
	if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$company_id = $_POST['company_id'];
		$st_flag 	= $_POST['st_flag'];
		
		$sql = "select * from company where comp_id = '$company_id' ";

		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
//		$vertical_type = $r2['comp_vertical'];
				
			if($st_flag=='A'){
				$label_n="Travel Req.No.";
			}
			else if($st_flag=='T'){
				$label_n="Mode of Expenses";
			}
			else if($st_flag=='S' || $st_flag=='R'){
				$label_n="Supp.Inv.No.";
			}
			else if($st_flag=='C'){
				$label_n="Operating Expenses";
			}
			else if($st_flag=='R'){
				$label_n="Retention";
			}
		
		
//Travel Reimbursement
		if($st_flag =='T' || $st_flag =='A' || $st_flag =='C'){
		
?>
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>ID</th>
			<th><?php echo $label_n; ?></th>	
			<th>Dated</th>
			<th style="text-align:right;">Balance Payment</th>
		    <th style="text-align:right;">Payable</th>
			
			<th style="text-align:right;display: none">Deduction Head</th>
			<th style="text-align:right;display: none">Deduction</th>
			<th style="text-align:right;"> Paid</th>
			<th>Narration</th>
			<th>Document</th>

		</tr>
	</thead>
<tbody>

 <?php
			
			$sub5 = '5';
			//echo $sub5. ' ' .$st_flag;
			$value = '';
			$tot_amount = 0;
			if($st_flag =='A'){
				$sql = "SELECT * FROM sma_traval_approval where onbehalf_emp_id = '$id' and company_id = '$company_id' and (status = 'Booked' || status = 'Completed') and advance_amount > 0 and paid_amount = 0 and del !='Y' ORDER BY id DESC, dated";
		//echo $sql;	
				$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_assoc($q2)){
					//$tot_amount += $r2['advance_amount'];
					$approval_ref_no = $r2['id'];
					$dated			 = date('d-m-Y', strtotime($r2['dated']));
					$advance_amount = $r2['advance_amount'];
					$invoice_date	 = date('d-m-Y', strtotime($r2['dated']));
				}

			}
			else if($st_flag =='C'){
//Company Expenses		
				
				
				$sql = "SELECT * FROM sma_travel_expenses where exp_type = 'C' and emp_id = '$id' and company_id = '$company_id' and status = 'Completed' and total_amount - bal_amount > 0 and del != 'Y' ";
						//and bal_amount > 0
//echo $sql.' '. $tot_amount ."<br>";			
				$q22  = mysqli_query($con, $sql);
				while($r22 = mysqli_fetch_assoc($q22)){
					$regular_exp_id  = $r22['id'];
					$dated			 = date('d-m-Y', strtotime($r22['dated']));
					$approval_ref_no = $r22['approval_ref_no'];
					$reg_exp_no		 = $r22['id'];
					$invoice_date	 = date('d-m-Y', strtotime($r22['dated']));
					if($invoice_date== '01-01-1970'){
						$invoice_date ='';
					}
					$total_amount 	 = $r22['total_amount'];
					$bal_amount		 = $r22['bal_amount'];
					$approval_number	= $r22['approval_number'];
					//echo $bal_amount. "<BR>";
				
					$company_id 	= $r22['company_id'];
					
					$sql = "SELECT * FROM `tally_journal_entry` where doc_no = '$regular_exp_id' and doc_type = 'CE' and account_type = 'V' and effect = 'Cr';";
					$tqry  = mysqli_query($con, $sql);
					$t2 = mysqli_fetch_assoc($tqry);
					$total_amount 	 = $t2['amount'];
					$sql = "UPDATE sma_travel_expenses set total_amount = '$total_amount' where id = '$regular_exp_id' and exp_type = 'C' ";
					mysqli_query($con, $sql);
					
				$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$approval_ref_no' and exp_type = 'C' ";
		//echo $sql. ' '. $tot_amount . "<br> ###2.";			
				$q3  = mysqli_query($con, $sql);
				$company_exp_total = 0 ;
				$supplier_invoice_no='';
				while($r3 = mysqli_fetch_assoc($q3)){
					
					$tot_amount 	= $r3['amount'];
					$tds_amount		= $r3['tds'];
					$gst_amount 	= $r3['gst_amount'];
					$company_exp_total += $r3['amount'] + $gst_amount + $tds_amount;
					$supplier_invoice_no .= $r3['invoice_no'].' ';
				}
	
		//echo $total_amount. "<br>";
				
				if($total_amount>0){
					
					$total_amount = $total_amount - $bal_amount;
//echo $company_exp_total. "<br>";						
//Deduction amount should deduct from bal_amount.

		/* if($gst_amount==0){
			
			$sqlg = " && b.account_name not like ('%GST%' )";
			$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
				where b.id = a.account_id and b.account_type = 'D' 
				and a.doc_type = 'CE' and (a.account_type = 'A' || a.account_type = 'D' || a.account_type = '') and a.effect = 'Cr' 
				and a.doc_no = '$approval_ref_no' and ( b.account_name not in ('Retention', 'Retention Money' ) 
												$sqlg )"; //&& b.account_name not like ('%GST%' )
//echo $sql."<BR>";				
//echo $company_exp_total. "<br>";	
			$qr2   = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while ($res2  = mysqli_fetch_array($qr2)){
				$ded_amount  = $res2['amount'];
				$company_exp_total  = $company_exp_total - $ded_amount ;
			}

		}
		
		 */
//echo $total_amount. "<BR>";
			if($total_amount<=1 || ($total_amount<=1 && $total_amount>=-1 ) ){
				
				continue;
				
			}
				?>
					<tr>
						<input type="hidden" name='supp_id[]' value="<?php echo $regular_exp_id;?>">
					<td width="1%"><?php echo $approval_ref_no;?></td>
						<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
					<td width="10%" style="text-align:left;">Company Expenses- <br></td>
					
						<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
					<td width="08%"><?php echo $invoice_date;?></td>
					
						<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $total_amount;?>" >
					<td width="8%" style="text-align:right;"><?php echo round($total_amount,0);?></td>
					
					<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
					
					<td width="10%" style="text-align:right;display: none">
							<select class="form-control" name="deduction_head[]" id="deduction_head" disabled >
							<option value=""> Select </option>
								<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
								<?php } ?>
							</select>
							<select class="form-control" name="deduction_head1[]" id="deduction_head1" disabled  >
							<option value=""> Select </option>
								<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
								<?php } ?>
							</select>
					</td>
					<td width="10%" style="text-align:right;display: none">
						<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>" disabled  >
						<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>" disabled  >
					</td>
					
					<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
					
					<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
					<!--<td width="5%"><?php echo $key1['id'];?></td>-->
					<?php 
						
						//$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&approval_ref_no=$approval_ref_no";
						if($st_flag=='T'){
							//$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&approval_ref_no=$approval_ref_no" ;
							$baseurl_tr = $baseurl . "travel_approval/travel_exp_repo.php?sub=pdf&id=$approval_ref_no" ;
							$texta = 'Expences';
						}
						
						//$baseurl_re = $baseurl . "travel_approval/company_exp_repo.php?sub=pdf&id=$reg_exp_no" ;
					if($st_flag=='C'  ){	
						$baseurl_re = $baseurl . "travel_approval/company_expense.php?sub=edit&id=$approval_ref_no" ;
					}	
						$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$approval_number&comp_id=$company_id&r=1";
			
					?>
					
				<td width="10%">
					<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-warning">Operating Expense  </span></a>
		
				</td>
			</tr>
				
		<?php
					}
				}	
			}
			else {
				
				$sql = " SELECT * FROM sma_travel_expenses where exp_type = 'T' and onbehalf_emp_id = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount = 0 and del != 'Y' ";
				//echo $sql."<br>";	
	// and bal_amount > 0  and advance_amount > 0
				$exp_no = '';
				$reg_exp_no = '';
				$tr_exp_amount ='';
				$tr_exp_amount_d ='';
				
				$qr2  = mysqli_query($con, $sql);
			//Start Loop qr2	
				while($rq2 = mysqli_fetch_array($qr2)){
					$advance_amt = $rq2['advance_amount'];
					$approval_ref_no = $rq2['approval_ref_no'];
					$exp_no 		 = $rq2['id'];
					$dated			 = date('d-m-Y', strtotime($rq2['dated']));
					$invoice_date	 = date('d-m-Y', strtotime($rq2['dated']));
					if($invoice_date== '01-01-1970'){
						$invoice_date ='';
					}
				//echo $exp_no."<BR>";
				$tr_exp_amount_d = 0;
				$tr_exp_amount	=0;
				if(!empty($approval_ref_no)){
					$sql = "SELECT * FROM `sma_departure` where approval_ref_no = '$exp_no' and spend_by = 'O' ";
					$q3  = mysqli_query($con, $sql);
			//echo $sql. "<br>";		
					
					/* while($r3 = mysqli_fetch_assoc($q3)){
					
						$tot_amount += $r3['fare'];
						$tr_exp_amount += $r3['fare'];
						$tr_exp_amount_d += $r3['fare'];
					} */
				}
				
				
				if(!empty($approval_ref_no)){
						
					$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$exp_no' and spend_by = 'O' and exp_type = 'T' ";
					$q4  = mysqli_query($con, $sql);
			//echo $sql. ' '. $tot_amount ."<br>";		
					$supplier_invoice_no = '';
					while($r4 = mysqli_fetch_assoc($q4)){
					
						$tot_amount += $r4['amount'];
						$tr_exp_amount += $r4['amount'];
						$tr_exp_amount_d += $r4['amount'];
						$supplier_invoice_no .= $r4['invoice_no'].' ';
					}
				}
				
			
			$tr_exp_amount = $tr_exp_amount - $advance_amt;
			
			$tr_exp_amount_d = round($tr_exp_amount_d - $advance_amt,2);
			
			//echo $sql. ' '. $tr_exp_amount ."<br> ###0.";
			
	if($tr_exp_amount>0){


//Deduction amount should deduct from bal_amount.
		$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
				where b.id = a.account_id and b.account_type = 'D' and a.doc_type = 'TE' and (a.account_type = 'A' || a.account_type = 'D')
					and a.effect = 'Cr' and a.doc_no = '$approval_ref_no' and account_name not in ('Retention', 'Retention Money' ) ";
					
		$qr22   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr22)){
			
			
			$ded_amount  = $res2['amount'];
			$tr_exp_amount_d  = $tr_exp_amount_d - $ded_amount ;
		}
//Deduction amount should deduct from bal_amount.
		
	?>
		
		<tr>
			<input type="hidden" name='supp_id[]' value="<?php echo $exp_no;?>">
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
		<td width="1%" style="text-align:right;"><?php echo $exp_no;?></td>
		<td width="10%" style="text-align:right;">Travel Expenses</td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $tr_exp_amount_d;?>" >
		<td width="8%" style="text-align:right;"><?php echo round($tr_exp_amount_d,0);?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>" > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>"   >
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		
		<td width="10%">
		 
		<?php //if($advance_amt>0){
			$baseurl_tr_req = $baseurl . "travel_approval/traval_app.php?sub=edit&id=$approval_ref_no" ;
			$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$approval_ref_no" ;
			//travel_form_prn.php?sub=pdf&id
			$textar = 'Request';
		?>	
			<a href="<?php echo $baseurl_tr_req;?>" target="_blank"><span class="label label-warning">Travel <?php echo $textar?></span></a>
		<?php //} ?>
		
				
		<?php if($tr_exp_amount>0){ 
				$baseurl_tr = $baseurl . "travel_approval/travel_exp_repo.php?sub=pdf&id=$exp_no" ;
				$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&id=$exp_no" ;
				$texta = 'Expences';
		?>		
			<a href="<?php echo $baseurl_tr;?>" target="_blank"><span class="label label-success">Travel <?php echo $texta?></span></a>
		<?php } ?>
		
		</td>
    </tr>
	
<?php
			}

		}
	//End Loop qr2		
				
//Reguar Expenses

				
				$sql = "SELECT * FROM sma_travel_expenses where exp_type = 'R' and onbehalf_emp_id = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount = 0  and del != 'Y' ";
	//	echo $sql;		
				//and bal_amount > 0
		//echo $sql.' '. $tot_amount ."<br> ###1.";			
				$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_assoc($q2)){
					$regular_exp_id  = $r2['id'];
					$dated			 = date('d-m-Y', strtotime($r2['dated']));
					$approval_ref_no = $r2['approval_ref_no'];
					$reg_exp_no			 = $r2['id'];
					$invoice_date	 = date('d-m-Y', strtotime($r2['dated']));
					if($invoice_date== '01-01-1970'){
						$invoice_date ='';
					}	
						
				$regular_exp_total = 0 ;
				
				$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$reg_exp_no' and exp_type = 'R' ";
		//echo $sql. ' '. $tot_amount . "<br> ###2.";			
				$q22  = mysqli_query($con, $sql);
				$supplier_invoice_no = '';
				while($r22 = mysqli_fetch_assoc($q22)){
					
					$tot_amount += $r22['amount'];
					$regular_exp_total += $r22['amount'];
					$supplier_invoice_no .= $r22['invoice_no'].' ';
				}
				
	if($regular_exp_total>0){

//Deduction amount should deduct from bal_amount.
		$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
				where b.id = a.account_id and b.account_type = 'D' and a.doc_type = 'RE' 
				and (a.account_type = 'A' || a.account_type = 'D')
				and a.effect = 'Cr' and a.doc_no = '$approval_ref_no' 
				and b.account_name not in ('Retention', 'Retention Money' ) ";
//echo $sql;
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$ded_amount  = $res2['amount'];
			$regular_exp_total  = $regular_exp_total - $ded_amount ;
		}
//Deduction amount should deduct from bal_amount.
		
	?>
		<tr>
			<input type="hidden" name='supp_id[]' value="<?php echo $regular_exp_id;?>">
		<td width="1%"><?php echo $approval_ref_no;?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
		<td width="10%" style="text-align:right;">Reimbursement- <br></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $regular_exp_total;?>" >
		<td width="8%" style="text-align:right;"><?php echo round($regular_exp_total,0);?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q21 	= mysqli_query($con, $sql);
					while($r21 = mysqli_fetch_array($q21)){ ?>
					<option value="<?php echo $r21['account_name'];?>" <?php echo ($row['deduction_head'] == $r21['account_name'])?'selected="selected"':'';?> >  <?php echo $r21['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1"    >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q23 	= mysqli_query($con, $sql);
					while($r23 = mysqli_fetch_array($q23)){ ?>
					<option value="<?php echo $r23['account_name'];?>" <?php echo ($row['deduction_head1'] == $r23['account_name'])?'selected="selected"':'';?> >  <?php echo $r23['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>"   >
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			
			//$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&approval_ref_no=$approval_ref_no";
			if($st_flag=='T'){	
				//$baseurl_tr = $baseurl . "travel_approval/travel_expence.php?sub=edit&approval_ref_no=$approval_ref_no" ;
				$baseurl_tr = $baseurl . "travel_approval/travel_exp_repo.php?sub=pdf&id=$approval_ref_no" ;
				$texta = 'Expences';
			}
			
			$baseurl_re = $baseurl . "travel_approval/regular_expense.php?sub=edit&id=$reg_exp_no" ;
			//$baseurl_re = $baseurl . "travel_approval/regular_exp_repo.php?sub=pdf&id=$reg_exp_no" ;
			
			
		?>
		<td width="10%">
		
		<?php if($regular_exp_total>0){ ?>
				<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-info">Reimbursement <?php echo $texta ?></span></a>
		<?php } 
		
			if($st_flag=='C' ){		
				$baseurl_re = $baseurl . "travel_approval/company_expense.php?sub=edit&id=$approval_ref_no" ;
		?>		
				<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-info">Operating Expense </span></a>
		<?php	}
		?>			
		</td>
    </tr>
	
<?php
			}
			
		}	

	}
		
	//echo ' '. $tot_amount ;
		
?>
			
			<div class="box">

<?php
	$invoice_date = date('d-m-Y', strtotime($dated));
	if($invoice_date =='01-01-1970'){									
		$invoice_date ='';
	}

	 if($advance_amount>0){
		 
 //Deduction amount should deduct from bal_amount.
		$sql = "SELECT * FROM `tally_journal_entry` a  , account_mst b 
				where b.id = a.account_id and b.account_type = 'D' 
				and a.doc_type = 'TA' and a.account_type = 'A' and a.effect = 'Cr' and a.doc_no = '$approval_ref_no' and account_name not in ('Retention', 'Retention Money' ) ";
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$ded_amount  = $res2['amount'];
			$advance_amount  = $advance_amount - $ded_amount ;
		}
//Deduction amount should deduct from bal_amount.
	
?>

	<tr>
		<input type="hidden" name='supp_id[]' value="<?php echo $approval_ref_no;?>">
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $approval_ref_no;?>" >
		<td width="1%" style="text-align:right;"><?php echo $approval_ref_no;?></td>
		<td width="10%" style="text-align:right;">Travel Request</td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $advance_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo round($advance_amount,0);?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1"    >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>"   >
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			
			//$baseurl_tr_req = $baseurl . "travel_approval/traval_app.php?sub=edit&id=$approval_ref_no" ;
			$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$approval_ref_no" ;
			
			$textar = 'Request';			
			
		?>
		<td width="10%">	
		
		<?php if($advance_amount>0){ ?>
			<a href="<?php echo $baseurl_tr_req;?>" target="_blank"><span class="label label-warning">Travel <?php echo $textar?></span></a>
		<?php } ?>
		
		</td>
    </tr>

<?php } ?>
 
</tbody> 
</table>
	</div>
	
<?php	
			
			exit();
			
	}
	else if( $st_flag=='S' || $st_flag=='R' || $st_flag=='M' ){
			
//Supplier Invoice		
		$value = '';
		
		if( $st_flag=='S' ){
			$sql = "SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount > 0 and bal_amount <= payable_amount and del != 'Y' ORDER BY our_po_ref_no DESC, invoice_date";
		}
		else if( $st_flag=='R' ){
			//$sql = "SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount > 0 and bal_amount < payable_amount and del != 'Y' ORDER BY our_po_ref_no DESC, invoice_date";
			
//Supplier Invoice	Retention
			$sql = " select * from sma_retention_invoice where suplier_name = '$id' and company_id = '$company_id' and payable_retention_amount > 0 and status='Completed' 
			            and payable_retention_amount > bal_retention_amount and del != 'Y' ORDER BY id ASC, invoice_date DESC";
//echo $sql."<BR>";			
		}
		else if( $st_flag=='M' ){
//Supplier Invoice	Compliances Amount
			$sql = " select * from sma_retention_invoice where suplier_name = '$id' and company_id = '$company_id' and payable_compliances_amount > 0 and status='Completed' 
			            and payable_compliances_amount > bal_compliances_amount and del != 'Y' ORDER BY id ASC, invoice_date DESC";
//echo $sql."<BR>";			
		}
		
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_assoc($q2)){
				$rows[] = $r2;
			}
				
			$data = $rows;
			$advance = '';
			
		if(empty($data)){
			$data= array();
		}	
//print_r($data);
		if(count($data)>0 ){
			
	?>
	
	<div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#No.</th>
			<th>Supp.Inv.No.</th>	
			<th>Dated</th>
			<th style="text-align:right;">Balance Payment</th>
		    <th style="text-align:right;">Payable</th>
			
			<th style="display: none">Deduction Headdd</th>
			<th style="text-align:right;display: none;">Deduction</th>
			<th style="text-align:right;"><?php echo $advance;?> Pay to Vendor</th>
			<th> Narration</th>
			<th>Document 
			
			
			</th>
			
		</tr>
	</thead>
<tbody>
<?php
	foreach($data as $key1){
		$supplier_invoice_no 	= $key1["supplier_invoice_no"];
		$invoice_date 			= $key1["invoice_date"];
				
		$role			= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		
		$total_amount = $key1['total_amount'];
		$bal_amount   = $key1['bal_amount'];
		$actual_payment = $key1['actual_payment'];
		$retention_amount 		= $key1['retention_amount'];
		$bal_retention_amount 	= $key1['bal_retention_amount'];
		
		$compliances_amount		= $key1['compliances_amount'];
		$bal_compliances_amount = $key1['bal_compliances_amount'];	
		
		if($st_flag=='R'){
			$bal_amount 	= $retention_amount - $bal_retention_amount;
			$actual_payment = $bal_retention_amount;
			//echo $supp_id. ' ###444 '. $bal_amount. ' ' . $st_flag. "<BR>";
		}
		
		if($st_flag=='M'){
			$bal_amount 	= $compliances_amount - $bal_compliances_amount;
			$actual_payment = $bal_compliances_amount;
			//echo $supp_id. ' ###444 '. $bal_amount. ' ' . $st_flag. "<BR>";
		}
		
		if($bal_amount<=0){
			continue;
		}
		
		$supp_id = $key1['id'];
		if($st_flag=='S'){
		    $sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' and del != 'Y' ";	
    		$si_total_amount = 0;
    		$q2  = mysqli_query($con, $sql);
    		$r2  = mysqli_fetch_array($q2);
    		$bal_amount 		= $r2['bal_amount'];
    		$si_total_amount	= $r2['total_amount'];
    		$our_po_ref_no 		= $r2['our_po_ref_no'];
		}

		$deduction1_id 		= $r2['deduction1_id'];
		$deduction2_id 		= $r2['deduction2_id'];
		$deduction3_id 		= $r2['deduction3_id'];
		
		$deduction1_amount 	= $r2['deduction1_amount'];
		$deduction2_amount 	= $r2['deduction2_amount'];
		$deduction3_amount 	= $r2['deduction3_amount'];

		$deduction1_remarks = $r2['deduction1_remarks'];
		$deduction2_remarks = $r2['deduction2_remarks'];
		$deduction3_remarks = $r2['deduction3_remarks'];
		$deduction1_id 		= '';
		$deduction2_id 		= '';
		$deduction3_id 		= '';
		
		$deduction1_amount 	= '';
		$deduction2_amount 	= '';
		$deduction3_amount 	= '';

		$deduction1_remarks = '';
		$deduction2_remarks = '';
		$deduction3_remarks = '';

		$sql = "select * from tally_journal_entry where effect='Cr' and account_name like '%tds%' and doc_type='SI' and doc_no='$supp_id' ";		
		$q22 = mysqli_query($con, $sql);
		$r22 = mysqli_fetch_array($q22);
		$tds_amount  = $r22['amount'];
//Deduction amount should deduct from bal_amount.
		$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
				where b.id = a.account_id and b.account_type = 'D' and  a.doc_type = 'SI' and a.account_type = 'A' 
				and a.effect = 'Cr' and a.doc_no = '$supp_id' and b.account_name not in ('Retention', 'Retention Money' ) ";
//	echo $sql;			
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			//$ded_amount  = $res2['amount'];
			$bal_amount  = $bal_amount - $ded_amount ;
		}
		$sql = "SELECT * FROM `tally_journal_entry` a , account_mst b 
				where b.id = a.account_id and b.account_type in ( 'E','A') and  a.doc_type = 'SI' and a.account_type = 'A' 
				and a.effect = 'Cr' and a.doc_no = '$supp_id' and b.account_name in ('Advance', 'Labour Cess Payable' ) ";
		$qr2   = mysqli_query($con, $sql);
		while ($res2  = mysqli_fetch_array($qr2)){
			$ded_amount  = $res2['amount'];
			$bal_amount  = $bal_amount - $ded_amount ;
		}
//Deduction amount should deduct from bal_amount.	

//echo "<BR>". $bal_amount. "<BR>";

		$sql = "SELECT * FROM sma_purchase_order where po_number = '$our_po_ref_no' or id = '$our_po_ref_no'  ";
//$val = $sql;	
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_id = $r2['id'];
		$approval_memo_ref = $r2['approval_memo_ref'];							
		$location	 			= $r2['location'];
		$comp_id				= $r2['project'];
		$advance_paid_amount	= $r2['paid_amount'];
									
		/* $sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref'  ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$ap_id = $r2['id'];
		 */
		/* $sql = "SELECT * FROM sma_grn_srn where our_po_ref_no = '$our_po_ref_no'  ";

		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$gs_id = $r2['id']; */
		
		$compid ='';
		$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and sma_comp_id ='$comp_id' and sma_invoice_no ='$supp_id' ";
		
		if($st_flag=='R'){
			$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and sma_comp_id ='$comp_id' and sma_invoice_no ='$supp_id' and sma_inv_adv = 'R' ";
		}
		
//echo $sql. "<BR>";
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
		
		if($bal_amount<1){
			$bal_amount=0;
		}	
?>
			

	<tr>
		
		<input type="hidden" name='supp_id[]' value="<?php echo $key1['id'];?>">
		<td width="1%"><?php echo $key1['id'];?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
		<td width="10%"><?php echo $key1['supplier_invoice_no'];?>
			<?php //if($advance_paid_amount>0){ ?>
			<!--<br><br><label  > Adv.Paid <?= $advance_paid_amount; ?></label>-->
			<?php //} ?>
		</td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
		<td width="08%"><?php echo date('d-m-Y', strtotime($key1['invoice_date']));?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo round($bal_amount,0);?>
		
		</td>
		
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > 
		
		</td>		
		
		<td width="10%" style="display: none">
			<select class="form-control" name="tds_percentage[]" id="tds_percentage" <?php echo $readonly;?>   >
				<option value=""> Select TDS</option>
				<?php $sql = "select * from account_mst where account_type = 'D' and tds_flag = 'Y'  order by account_name ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['account_name'];?>" ><?php echo $r2['account_name'];?></option>
			<?php } ?>
			</select>
									
				<select class="form-control" name="deduction_head[]" id="deduction_head"  >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and tds_flag != 'Y' and del !='Y'  order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($deduction1_id == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1"  >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' and tds_flag != 'Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($deduction2_id == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head2[]" id="deduction_head2"  >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' and tds_flag != 'Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($deduction3_id == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				
		</td>
		<td width="10%" style="display: none">
			<input class="form-control col-md-2" type="text" name='tds_percentage[]' id='tds_percentage' onblur="getactual123(); return checkadjusted();" style="text-align:right;" value=""  >
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $deduction1_amount;?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $deduction2_amount;?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt2[]' id='deduction_amt2' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $deduction3_amount;?>"   >
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea>
		<?php if($si_total_amount>0){ ?>
			<BR> <b> Supplier Invoice Amt: <br><?php echo $si_total_amount ?> </b>
		<?php } ?>
		<?php if($tds_amount>0){ ?>
			<BR> <b> TDS Amt: <?php echo $tds_amount ?> </b>
		<?php } ?>	
		</td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			//$supp_id = $key1['supp_id'];
			
			$baseurl_ri = $baseurl . "retention/edit.php?sub=edit&id=$supp_id";
			
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
			$po_id = $po_id;
			$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
			
			$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location&r=1&print_flag=V" ;
										
			$ap_id = $ap_id;
			$company_id = $row['company_id'];
			//$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";	
			//$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id&r=1";
			
			$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id&r=1";
	
			//GRNSRN
			$baseurl_gs = $baseurl . "grnsrn/grnsrn_prn.php?sub=pdf&id=$gs_id&comp_id=company_id&r=1";
			
			$ipc_id = $ipc_id;
			$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
//echo $baseurl_ipc;
		
		?>
		<td width="10%">
		
		<?php	
		    if($st_flag=='R' || $st_flag=='M'){
		?>
		        <a href="<?php echo $baseurl_ri;?>" target="_blank"><span class="label label-warning">Retention/Compliances</span></a>
		<?php } ?>
			<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>
										
			<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>
			
		<?php
			if(!empty($compid)){
		?>	
				<a href="<?php echo $baseurl_ipc;?>" target="_blank"><span class="label label-info">IPC</span></a>
				
		<?php 	//echo $ipc_link;
			} ?>	
		
		</td>
    </tr>
	<?php }?>
</tbody> 
</table>
	</div>
	 <?php
	
			}
		
//	echo  $data;
//$data = $vsql;
//echo $data;
		return  $data;
		
	}
	else if($st_flag=='D'){
			
//Supplier Invoice		
		$value = '';
		
		$amount = $quantity * $unit_rate + (($quantity * $unit_rate) * $gst / 100);
		
		$sql = "SELECT a.purchase_id as purchse_id, 
						round(sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as total_amount, 
						b.dated as invoice_date, 
						b.po_number as po_number, 
						b.paid_amount as paid_amount, 
						b.advance_flag as advance_flag
					FROM `sma_po_items` a, sma_purchase_order b 
						WHERE a.purchase_id = b.id and b.to_supplier = '$id' and b.project = '$company_id' 
							AND b.status = 'Completed'  and advance_flag = 'Y' AND del !='Y'
							AND b.id not in ( SELECT our_po_ref_no from sma_supplier_invoice where company_id = '$id' and del !='Y' )
								group by a.purchase_id "; //and b.paid_amount = 0 and paid_status != 'Paid'
								
		$sql = "SELECT id as purchse_id, advance_amount as total_amount, dated as invoice_date, po_ref_no as po_number, paid_amount 
					FROM sma_advance
						WHERE 1 and supplier_id = '$id' and company_id = '$company_id' 
							AND status = 'Completed' and del !='Y'
							group by id ";		

//echo $sql;
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_assoc($q2)){
				$rows[] = $r2;
			}
				
			$data = $rows;
			$advance = '';
				
//print_r($data);		
		if(count($data)>0){
			
	?>
	
	<div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>PO.No.</th>	
			<th>Dated</th>
			<th style="text-align:right;">Balance Payment</th>
		    <th style="text-align:right;">Payable</th>
			
			<th style="text-align:right;display: none">Deduction Head</th>
			<th style="text-align:right;display: none">Deduction</th>
			<th style="text-align:right;"><?php echo $advance;?> Paid</th>
			<th>Narration</th>
			<th>Document 
			
			</th>
			
		</tr>
	</thead>
<tbody>
<?php
	foreach($data as $key1){
	
		$supp_id			 	= $key1["purchse_id"];
		$po_number 	= $key1["po_number"];
		$invoice_date 			= date('d-m-Y', strtotime($key1["invoice_date"]));
				
		$role			= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		
		$total_amount 	= $key1['total_amount'];
		$paid_amount   	= $key1['paid_amount'];
		$bal_amount   	= $key1['total_amount'] - $paid_amount;
		$advance_flag   = $key1['advance_flag'];
		
		//$actual_payment = $key1['actual_payment'];
		if($bal_amount<=0){
			continue;
		}

		$sql = "SELECT * FROM sma_purchase_order where id = '$supp_id' ";
//$val = $sql;	
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_id = $r2['id'];
		$approval_memo_ref = $r2['approval_memo_ref'];							
		$location	 			= $r2['location'];
		$comp_id				= $r2['project'];
									
		$sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref'  ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$ap_id = $r2['id'];
		
		$sql = "SELECT * FROM sma_grn_srn where our_po_ref_no = '$our_po_ref_no'  ";

		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$gs_id = $r2['id'];
		
		$compid ='';
		//$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and sma_comp_id ='$comp_id' and sma_invoice_no ='$supp_id' ";
		$sql = "SELECT * FROM sma_ipc where sma_po_no = '$supp_id' and sma_comp_id ='$comp_id'  ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$ipc_id = $r2['id'];
		$compid	= $r2['sma_comp_id'];
		
		if($bal_amount<1){
			$bal_amount=0;
		}	
?>

	<tr>
		
		<input type="hidden" name='supp_id[]' value="<?php echo $key1['purchse_id'];?>">
		<td width="1%"><?php echo $key1['purchse_id'];?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $po_number;?>" >
		<td width="10%"><?php echo $po_number;?></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo round($bal_amount,0);?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%" style="text-align:right;display: none">
				<select class="form-control" name="deduction_head[]" id="deduction_head"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1"   >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%" style="text-align:right;display: none">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>"   >
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>"   >
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			//$supp_id = $key1['supp_id'];
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
			$po_id = $po_id;
			$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
			
			$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location&r=1&print_flag=V" ;
										
			$ap_id = $ap_id;
			$company_id = $row['company_id'];
			//$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";	
			$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id&r=1";
			
			$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id&r=1";
	
			//GRNSRN
			$baseurl_gs = $baseurl . "grnsrn/grnsrn_prn.php?sub=pdf&id=$gs_id&comp_id=company_id&r=1";
			
			$ipc_id = $ipc_id;
			$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
//echo $baseurl_si;
		?>
		<td width="10%">
		
			
			<!--<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>-->
			
			<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>

		<?php
			if(!empty($compid)){
		?>	
				<a href="<?php echo $baseurl_ipc;?>" target="_blank"><span class="label label-info">IPC</span></a>
		<?php } ?>	
		
		</td>
    </tr>
	<?php }?>
</tbody> 
</table>
	</div>
	 <?php
	
			}
		
//	echo  $data;
//$data = $vsql;
//echo $data;
		return  $data;
		
		
		}
		
	}
	
	if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$st_flag = $_POST['st_flag'];
		$value = '';
//echo $st_flag. "<BR>";
//echo $sql = " SELECT * FROM sma_party_mst where id in (select suplier_name from sma_retention_invoice where company_id = '$id' and payable_compliances_amount > 0 and status='Completed' and payable_compliances_amount > bal_compliances_amount and del != 'Y' ) ORDER BY party_name ASC";
		$value .= '<select class="form-control" id="paid_to" name="paid_to" onchange="getinvoice(this.value)" >
										<option value="">Select</option>';	
	if($id > 0){
		if( $st_flag=='S'  ){
//Supplier Invoice			
//echo $sql = " select  count(distinct(suplier_name), company_id) as scnt from sma_supplier_invoice where company_id in ($comid) and company_id > 0 and bal_amount > 0 and status = 'Completed' and bal_amount <= total_amount and bal_amount>=1 and del != 'Y' ";

			$sql="SELECT * FROM sma_party_mst where id in (select suplier_name from sma_supplier_invoice where company_id = '$id' and company_id > 0 and bal_amount > 0 and status = 'Completed' and bal_amount <= payable_amount and bal_amount>=1 and del != 'Y' and paid_status !='Paid' ) ORDER BY party_name ASC";
			
//echo $sql;
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}

		}
		if( $st_flag=='R'){
//Supplier Invoice	Retention
			$sql = " SELECT * FROM sma_party_mst where id in (select suplier_name from sma_retention_invoice where company_id = '$id' and payable_retention_amount > 0 and status='Completed' and payable_retention_amount > bal_retention_amount  and del != 'Y' ) ORDER BY party_name ASC";
			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		if( $st_flag=='M'){
//Supplier Invoice	Retention
			$sql = " SELECT * FROM sma_party_mst where id in (select suplier_name from sma_retention_invoice where company_id = '$id' and payable_compliances_amount > 0 and status='Completed' and payable_compliances_amount > bal_compliances_amount and del != 'Y' ) ORDER BY party_name ASC";
			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		if($st_flag=='C'){
//Operating Expenses			
 			$sql="SELECT * FROM sma_party_mst where id in (select emp_id from sma_travel_expenses where exp_type = 'C' and company_id = '$id' and status='Completed' and total_amount - bal_amount > 0 and del != 'Y' and paid_status != 'Paid' ) ORDER BY party_name ASC";
//echo $sql;			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		else if($st_flag=='D' ){
//Supplier Advance			
			
			$sql = "SELECT * FROM sma_party_mst where id in ( select to_supplier 
				FROM (
				select  to_supplier, round(sum((quantity * unit_rate) + (((quantity * unit_rate * gst) / 100))),0) as tot_amount , a.paid_amount FROM sma_purchase_order a, sma_po_items b where a.id = b.purchase_id and a.advance_flag = 'Y' and a.project = '$id'  and a.status='Completed' and paid_amount >= 0 and a.del != 'Y' 
				group by a.id ) DS 
				where tot_amount > paid_amount and (tot_amount - paid_amount) > 1  ) ";	
				
			$sql = "SELECT * FROM sma_party_mst where id in ( select supplier_id FROM sma_advance 
							WHERE 1 and company_id = '$id' and status='Completed' 
								AND paid_amount >= 0 and del != 'Y' and advance_amount > paid_amount 
								AND (advance_amount - paid_amount) > 1  ) order by party_name ";	
								
//echo $sql; //paid_status != 'Paid' and //paid_status != 'Paid' and and paid_amount = 0
// tot_amount > paid_against_invoice && tot_amount != paid_amount && (tot_amount - paid_against_invoice) > 1
//round(sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as total_amount,

			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		else if($st_flag=='A' ){
//Travel Advance		
			$sql="SELECT * FROM sma_user where id in ( SELECT onbehalf_emp_id FROM `sma_traval_approval` where company_id = '$id' and (status='Booked' || status='Completed' ) and advance_amount > 0 and paid_amount = 0 and del !='Y' )
			ORDER BY username ASC";
			
		//echo $sql;
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->username;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}

		}
		else if($st_flag=='T' ){
//Travel Expenses			
			$sql = "SELECT * FROM sma_user where active in (0, 1 ) and id in 
			( select onbehalf_emp_id from sma_travel_expenses where exp_type = 'T' and company_id = '$id' and status='Completed'  and del != 'Y' 
					and ( total_amount  ) > 0 and bal_amount = 0
				union 
				select onbehalf_emp_id from sma_travel_expenses where exp_type = 'R' and company_id = '$id' and status='Completed' and bal_amount = 0  and del != 'Y' 
			)
				ORDER BY username ASC";
//echo $sql; //- advance_amount
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->username;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		
	}	// ID Check condition end
		$value .= "</select>";
//$value = $sql;
		echo $value;
	

	}


if(isset($_POST['sub7'])){
    
        $id 		= $_POST['id'];
		$company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$st_flag = $_POST['st_flag'];
		$value = '';
		$value .= '<select class="form-control" id="cash_bank_name" name="cash_bank_name" required >
					<option value="">Select</option>';
		$sql="SELECT * FROM account_mst where account_type = 'B' and company_id = '$company_id' ORDER BY account_name ASC"; 
			$q2 = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$account_name = $r2->account_name;
				$account_number = $r2->account_number;
				$value .= "<option value='".$id."'>".$account_name.' | '.$account_number. "</option>";
			}
		$value .= "</select>";
//$value = $sql;
		echo $value;
	
	}

    if(isset($_POST['sub27'])){
    
        $company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		//$comp_vertical = $r2['comp_vertical'];
?>
		<select class="form-control select3" name="po_doc_type" id="po_doc_type" required >
			<option value=""> Select </option>
			<?php $sql = "select * from sma_workflow where doc_type = 'PY' and company_id = '$company_id' order by trans_type ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['trans_type'];?></option>
			<?php } ?>
		</select>
	
<?php
	}
	
    if(isset($_POST['sub28'])){	
		$company_id = $_POST['id'];
?>
		<div class="form-group">
							
							<div class="col-md-6">
								<label class="control-label">Transfer From Account <span data-toggle="tooltip" title="" class="badge bg-light-blue"></span></label>
								
									<select class="form-control" id="transfer_from_account" name="transfer_from_account"  >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' and company_id in ($company_id) ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'].'| '.$r2['account_number'] ?></option>
										<?php } ?>
									</select>
									
							</div>
							
							<div class="col-md-6">
								<label class="control-label">To Account<span style="color:red;"></span></label>
								
									<select class="form-control" id="transfer_to_account" name="transfer_to_account"  >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' and company_id in ($company_id) ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'].'| '.$r2['account_number'] ?></option>
										<?php } ?>
									</select>
									
							</div>
							
		</div>
<?php
	}
	
?>						