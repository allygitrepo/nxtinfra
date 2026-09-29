<?php session_start();
	include('../dbcon.php');
	include "../baseurl.php";
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
			$sql = "SELECT * FROM account_mst where del != 'Y'ORDER BY account_name ASC ";
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
		
			if($st_flag=='A'){
				$label_n="Travel Req.No.";
			}
			else if($st_flag=='D'){
				$label_n="Supplier Advance";
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
			
			<th>Deduction Head</th>
			<th style="text-align:right;">Deduction</th>
			<th style="text-align:right;"> Paid</th>
			<th>Remarks</th>
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
				$sql = "SELECT * FROM sma_traval_approval where emp_id = '$id' and company_id = '$company_id' and (status = 'Booked' || status = 'Completed') and advance_amount > 0 and paid_amount = 0 and del !='Y' ORDER BY id DESC, dated";
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
//echo $sql.' '. $tot_amount ."<br> ###1.";			
				$q22  = mysqli_query($con, $sql);
				while($r22 = mysqli_fetch_assoc($q22)){
					$regular_exp_id  = $r22['id'];
					$dated			 = date('d-m-Y', strtotime($r22['dated']));
					$approval_ref_no = $r22['approval_ref_no'];
					$reg_exp_no		 = $r22['id'];
					$invoice_date	 = date('d-m-Y', strtotime($r22['dated']));
					$total_amount 	 = $r22['total_amount'];
					$bal_amount		 = $r22['bal_amount'];
					$approval_number	= $r22['approval_number'];
					//echo $bal_amount. "<BR>";
				
					$company_id = $r22['company_id'];
					
				$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$reg_exp_no' and exp_type = 'C' ";
		//echo $sql. ' '. $tot_amount . "<br> ###2.";			
				$q3  = mysqli_query($con, $sql);
				$company_exp_total = 0 ;
				while($r3 = mysqli_fetch_assoc($q3)){
					
					$tot_amount = $r3['amount'];
					$company_exp_total += $r3['amount'];
				}
	
		//echo $company_exp_total. "<br>";
	
				if($company_exp_total>0){
					
					$company_exp_total = $company_exp_total - $bal_amount;
					
				?>
					<tr>
						<input type="hidden" name='supp_id[]' value="<?php echo $regular_exp_id;?>">
					<td width="1%"><?php echo $approval_ref_no;?></td>
						<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $approval_ref_no;?>" >
					<td width="10%" style="text-align:right;">Company Expenses- <br></td>
					
						<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
					<td width="08%"><?php echo $invoice_date;?></td>
					
						<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $company_exp_total;?>" >
					<td width="8%" style="text-align:right;"><?php echo $company_exp_total;?></td>
					
					<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
					
					<td width="10%">
							<select class="form-control" name="deduction_head[]" id="deduction_head" >
							<option value=""> Select </option>
								<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
								<?php } ?>
							</select>
							<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
							<option value=""> Select </option>
								<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
								<?php } ?>
							</select>
					</td>
					<td width="10%">
						<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
						<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
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
						
						//$baseurl_re = $baseurl . "travel_approval/regular_expense.php?sub=edit&id=$reg_exp_no" ;
						$baseurl_re = $baseurl . "travel_approval/company_exp_repo.php?sub=pdf&id=$reg_exp_no" ;
						
						$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$approval_number&comp_id=$company_id&r=1";
			
					?>
					<td width="10%">
					
					<?php if($company_exp_total>0){ ?>
							<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-info">Operating Expense <?php echo $texta ?></span></a>
							<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-info">Approval Memo<?php echo $texta ?></span></a>
					<?php } ?>
					</td>
				</tr>
				
		<?php
					}
				}	
			}
			else {
				
				$sql = " SELECT * FROM sma_travel_expenses where exp_type = 'T' and emp_id = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount = 0 and del != 'Y' ";
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
					
				//echo $exp_no."<BR>";
				$tr_exp_amount_d = 0;
				$tr_exp_amount	=0;
				if(!empty($approval_ref_no)){
					$sql = "SELECT * FROM `sma_departure` where approval_ref_no = '$exp_no' and spend_by = 'O' ";
					$q3  = mysqli_query($con, $sql);
			//echo $sql. "<br>";		
					
					while($r3 = mysqli_fetch_assoc($q3)){
					
						$tot_amount += $r3['fare'];
						$tr_exp_amount += $r3['fare'];
						$tr_exp_amount_d += $r3['fare'];
					}
				}
				
				
				if(!empty($approval_ref_no)){
						
					$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$exp_no' and spend_by = 'O' and exp_type = 'T' ";
					$q4  = mysqli_query($con, $sql);
		//	echo $sql. ' '. $tot_amount ."<br>";		
					
					while($r4 = mysqli_fetch_assoc($q4)){
					
						$tot_amount += $r4['amount'];
						$tr_exp_amount += $r4['amount'];
						$tr_exp_amount_d += $r4['amount'];
					
					}
				}
				
			
			$tr_exp_amount = $tr_exp_amount - $advance_amt;
			
			$tr_exp_amount_d = round($tr_exp_amount_d - $advance_amt,2);
			
			//echo $sql. ' '. $tr_exp_amount ."<br> ###0.";
			
	if($tr_exp_amount>0){
		
	?>
		
		<tr>
			<input type="hidden" name='supp_id[]' value="<?php echo $exp_no;?>">
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $approval_ref_no;?>" >
		<td width="1%" style="text-align:right;"><?php echo $exp_no;?></td>
		<td width="10%" style="text-align:right;">Travel Expenses</td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $tr_exp_amount_d;?>" >
		<td width="8%" style="text-align:right;"><?php echo $tr_exp_amount_d;?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>" > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		
		<td width="10%">
		 
		<?php if($advance_amt>0){ 
			$baseurl_tr_req = $baseurl . "travel_approval/traval_app.php?sub=edit&id=$approval_ref_no" ;
			$baseurl_tr_req = $baseurl . "travel_approval/travel_form_prn.php?sub=pdf&id=$approval_ref_no" ;
			//travel_form_prn.php?sub=pdf&id
			$textar = 'Request';
		?>	
			<a href="<?php echo $baseurl_tr_req;?>" target="_blank"><span class="label label-warning">Travel <?php echo $textar?></span></a>
		<?php } ?>
		
				
		<?php if($tr_exp_amount>0){ 
				$baseurl_tr = $baseurl . "travel_approval/travel_exp_repo.php?sub=pdf&id=$exp_no" ;
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

				
				$sql = "SELECT * FROM sma_travel_expenses where exp_type = 'R' and emp_id = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount = 0  and del != 'Y' ";
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
					
				$regular_exp_total = 0 ;
				
				$sql = "SELECT * FROM `sma_expenses` where approval_ref_no = '$reg_exp_no' and exp_type = 'R' ";
		//echo $sql. ' '. $tot_amount . "<br> ###2.";			
				$q22  = mysqli_query($con, $sql);
				
				while($r22 = mysqli_fetch_assoc($q22)){
					
					$tot_amount += $r22['amount'];
					$regular_exp_total += $r22['amount'];
				}
				
	if($regular_exp_total>0){
		
	?>
		<tr>
			<input type="hidden" name='supp_id[]' value="<?php echo $regular_exp_id;?>">
		<td width="1%"><?php echo $approval_ref_no;?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $approval_ref_no;?>" >
		<td width="10%" style="text-align:right;">Regular Expenses- <br></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $regular_exp_total;?>" >
		<td width="8%" style="text-align:right;"><?php echo $regular_exp_total;?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q21 	= mysqli_query($con, $sql);
					while($r21 = mysqli_fetch_array($q21)){ ?>
					<option value="<?php echo $r21['account_name'];?>" <?php echo ($row['deduction_head'] == $r21['account_name'])?'selected="selected"':'';?> >  <?php echo $r21['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q23 	= mysqli_query($con, $sql);
					while($r23 = mysqli_fetch_array($q23)){ ?>
					<option value="<?php echo $r23['account_name'];?>" <?php echo ($row['deduction_head1'] == $r23['account_name'])?'selected="selected"':'';?> >  <?php echo $r23['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
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
			
			//$baseurl_re = $baseurl . "travel_approval/regular_expense.php?sub=edit&id=$reg_exp_no" ;
			$baseurl_re = $baseurl . "travel_approval/regular_exp_repo.php?sub=pdf&id=$reg_exp_no" ;
			
			
		?>
		<td width="10%">
		
		<?php if($regular_exp_total>0){ ?>
				<a href="<?php echo $baseurl_re;?>" target="_blank"><span class="label label-info">Regular <?php echo $texta ?></span></a>
		<?php } ?>
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
	
?>

	<tr>
		<input type="hidden" name='supp_id[]' value="<?php echo $approval_ref_no;?>">
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $approval_ref_no;?>" >
		<td width="1%" style="text-align:right;"><?php echo $approval_ref_no;?></td>
		<td width="10%" style="text-align:right;">Travel Request</td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $dated;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $advance_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo $advance_amount;?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
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
	else if( $st_flag=='S' || $st_flag=='R' ){
			
//Supplier Invoice		
		$value = '';
		
		if( $st_flag=='S' ){
			$sql = "SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount > 0 and bal_amount = total_amount and del != 'Y' ORDER BY our_po_ref_no DESC, invoice_date";
		}
		else if( $st_flag=='R' ){
			$sql = "SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and company_id = '$company_id' and status = 'Completed' and bal_amount > 0 and bal_amount < total_amount and del != 'Y' ORDER BY our_po_ref_no DESC, invoice_date";
		}	
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
			<th>#No.</th>
			<th>Supp.Inv.No.</th>	
			<th>Dated</th>
			<th style="text-align:right;">Balance Payment</th>
		    <th style="text-align:right;">Payable</th>
			
			<th>Deduction Head</th>
			<th style="text-align:right;">Deduction</th>
			<th style="text-align:right;"><?php echo $advance;?> Paid</th>
			<th>Remarks</th>
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
		if($bal_amount<=0){
			continue;
		}
		
		$supp_id = $key1['id'];
		$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' and del != 'Y' ";	
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$bal_amount = $r2['bal_amount'];
		$our_po_ref_no = $r2['our_po_ref_no'];
								
		$sql = "SELECT * FROM sma_purchase_order where po_number = '$our_po_ref_no' or id = '$our_po_ref_no'  ";
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
		$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and sma_comp_id ='$comp_id' and sma_invoice_no ='$supp_id' ";
		
		if($st_flag=='R'){
			$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and sma_comp_id ='$comp_id' and sma_invoice_no ='$supp_id' and sma_inv_adv = 'R' ";
		}
		
//echo $sql. "<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$ipc_id = $r2['id'];
		$compid	= $r2['sma_comp_id'];
?>
			

	<tr>
		
		<input type="hidden" name='supp_id[]' value="<?php echo $key1['id'];?>">
		<td width="1%"><?php echo $key1['id'];?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
		<td width="10%"><?php echo $key1['supplier_invoice_no'];?></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
		<td width="08%"><?php echo date('d-m-Y', strtotime($key1['invoice_date']));?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo $bal_amount;?>
			<br><br><label  > Retention-</label>
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > 
			<input class="form-control col-md-2" type="text" autocomplete="off" name='retention_amt[]' id='retention_amt' style="text-align:right;" value="<?php echo $key1['retention_amt'];?>" <?php echo $readonly; ?> >
		</td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			//$supp_id = $key1['supp_id'];
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
			$po_id = $po_id;
			$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
			
			$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location&r=1" ;
										
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
		
			<!--<a href="<?php echo $baseurl_si; ?>" target="_blank" >Invoice</a><br>
			<a href="<?php echo $baseurl_po; ?>" target="_blank" >PO</a><br>
			<a href="<?php echo $baseurl_ap; ?>" target="_blank" >Approval Notes</a><br>
			<a href="<?php echo $baseurl_gs; ?>" target="_blank" >GRN</a><br>-->
			
			<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>
										
			<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>
			<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-danger">Approval Notes</span></a>
			<a href="<?php echo $baseurl_gs;?>" target="_blank"><span class="label label-info">GRN</span></a>
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
	else if($st_flag=='D'){
			
//Supplier Invoice		
		$value = '';
		
		$amount = $quantity * $unit_rate + (($quantity * $unit_rate) * $gst / 100);
		//SELECT round((quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as matterial_amount FROM `sma_po_items`
		
		//$sql = "SELECT  round((quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as material_amount FROM `sma_po_items` where purchase_id in (SELECT id FROM sma_purchase_order where to_suplier = '$id' and project = '$company_id' and status = 'Completed' and paid_amount = 0 
		//	ORDER BY our_po_ref_no DESC, invoice_date) ";

		$sql = "SELECT a.purchase_id as purchse_id, 
						round(sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)),2) as total_amount, 
						b.dated as invoice_date, 
						b.po_number as po_number, 
						b.paid_amount as paid_amount, 
						b.advance_flag as advance_flag
					FROM `sma_po_items` a, sma_purchase_order b 
						where a.purchase_id = b.id and b.to_supplier = '$id' and b.project = '$company_id' 
							and b.status = 'Completed'  and advance_flag = 'Y' and paid_status != 'Paid'
								group by a.purchase_id "; //and b.paid_amount = 0
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
			
			<th>Deduction Head</th>
			<th style="text-align:right;">Deduction</th>
			<th style="text-align:right;"><?php echo $advance;?> Paid</th>
			<th>Remarks</th>
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
?>

	<tr>
		
		<input type="hidden" name='supp_id[]' value="<?php echo $key1['purchse_id'];?>">
		<td width="1%"><?php echo $key1['purchse_id'];?></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $po_number;?>" >
		<td width="10%"><?php echo $po_number;?></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
		<td width="08%"><?php echo $invoice_date;?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo $bal_amount;?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>"  > </td>		
		
		<td width="10%">
				<select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
				<select class="form-control" name="deduction_head1[]" id="deduction_head1" >
				<option value=""> Select </option>
					<?php $sql = "select * from account_mst where account_type = 'D' and del !='Y' order by account_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head1'] == $r2['account_name'])?'selected="selected"':'';?> >  <?php echo $r2['account_name'];?></option>
					<?php } ?>
				</select>
		</td>
		<td width="10%">
			<input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>">
			<input class="form-control col-md-2" type="text" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>">
		</td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			//$supp_id = $key1['supp_id'];
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
			$po_id = $po_id;
			$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
			
			$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location&r=1" ;
										
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
		
			<!--<a href="<?php echo $baseurl_si; ?>" target="_blank" >Invoice</a><br>
			<a href="<?php echo $baseurl_po; ?>" target="_blank" >PO</a><br>
			<a href="<?php echo $baseurl_ap; ?>" target="_blank" >Approval Notes</a><br>
			<a href="<?php echo $baseurl_gs; ?>" target="_blank" >GRN</a><br>-->
			
			<!--<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>-->
			
			<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>
			<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-danger">Approval Notes</span></a>
			<a href="<?php echo $baseurl_gs;?>" target="_blank"><span class="label label-info">GRN</span></a>
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
		
//$sql="SELECT * FROM sma_user where id in ( select emp_id from sma_travel_expenses where exp_type = 'T' and company_id = '$id' and status='Completed' and bal_amount = 0
//union select emp_id from sma_travel_expenses where exp_type = 'R' and company_id = '$id' and status='Completed' and bal_amount = 0 )
//ORDER BY username ASC"."<BR>";
		
//$sql = "SELECT * FROM sma_party_mst where id in (select suplier_name from sma_supplier_invoice where company_id = '$id' and bal_amount > 0 and status='Completed' and paid_Status= 'Paid' and bal_amount < total_amount) ORDER BY party_name ASC";
		
echo $st_flag. "<BR>";	
//echo $sql;
		
		$value = '';
		$value .= '<select class="form-control" id="paid_to" name="paid_to" onchange="getinvoice(this.value)" >
										<option value="">Select</option>';	
	if($id > 0){
		if( $st_flag=='S'  ){
//Supplier Invoice			
			$sql="SELECT * FROM sma_party_mst where id in (select suplier_name from sma_supplier_invoice where company_id = '$id' and bal_amount > 0 and status='Completed' and bal_amount = total_amount  and del != 'Y' ) ORDER BY party_name ASC";
			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}

		}
		if( $st_flag=='R'){
//Supplier Invoice			
			$sql="SELECT * FROM sma_party_mst where id in (select suplier_name from sma_supplier_invoice where company_id = '$id' and bal_amount > 0 and status='Completed' and bal_amount < total_amount  and del != 'Y' ) ORDER BY party_name ASC";
			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		if($st_flag=='C'){
//Operating Expenses			
			$sql="SELECT * FROM sma_party_mst where id in (select emp_id from sma_travel_expenses where exp_type = 'C' and company_id = '$id' and status='Completed' and total_amount - bal_amount > 0 and del != 'Y' ) ORDER BY party_name ASC";
echo $sql;			
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		else if($st_flag=='D' ){
//Supplier Advance			
			//$sql="SELECT * FROM sma_party_mst where id in (select to_supplier from `sma_purchase_order` WHERE advance_flag = 'Y' and project = '$id'  and status='Completed' ) ORDER BY party_name ASC"; //and paid_amount = 0
			
			$sql = "SELECT * FROM sma_party_mst where id in (select to_supplier from `sma_purchase_order` WHERE del != 'Y' and advance_flag = 'Y' and project = '$id' and paid_status != 'Paid' and status='Completed' and paid_amount < (select sum(quantity * unit_rate + ((quantity * unit_rate) * gst / 100)) as tot_amount from sma_purchase_order a, sma_po_items b where a.id =  b.purchase_id and a.advance_flag = 'Y' and a.project = '$id' and paid_status != 'Paid' and a.status='Completed'  and a.del != 'Y' )) ORDER BY party_name ASC"; 			
//echo $sql;	
			$q2 = mysqli_query($con, $sql);

			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$party_name = $r2->party_name;
				$value .= "<option value='".$id."'>".$party_name. "</option>";
			}
			
		}
		else if($st_flag=='A' ){
//Travel Advance		
			$sql="SELECT * FROM sma_user where id in ( SELECT emp_id FROM `sma_traval_approval` where company_id = '$id' and (status='Booked' || status='Completed' ) and advance_amount > 0 and paid_amount = 0 and del !='Y' )
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
			$sql = "SELECT * FROM sma_user where id in 
			( select emp_id from sma_travel_expenses where exp_type = 'T' and company_id = '$id' and status='Completed'  and del != 'Y' 
					and ( total_amount - advance_amount ) > 0 and bal_amount = 0
				union 
				select emp_id from sma_travel_expenses where exp_type = 'R' and company_id = '$id' and status='Completed' and bal_amount = 0  and del != 'Y' 
			)
				ORDER BY username ASC";
//echo $sql;
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
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$st_flag = $_POST['st_flag'];
		$value = '';
		$value .= '<select class="form-control" id="cash_bank_name" name="cash_bank_name" >
					<option value="">Select</option>';
		$sql="SELECT * FROM account_mst where account_type = 'B' and del !='Y' and company_id = '$id' ORDER BY account_name ASC";
			$q2 = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$id = $r2->id;
				$account_name = $r2->account_name;
				$value .= "<option value='".$id."'>".$account_name. "</option>";
			}
		$value .= "</select>";
//$value = $sql;
		echo $value;
	
	}
		

?>


