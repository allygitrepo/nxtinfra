<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

?>

<?php
	if(isset($_POST['sub1'])){
		
		$modulePath 	= "petty/";
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= $_POST['account_name'];
		$effect 		= $_POST['effect'];
		$amount 		= $_POST['amount'];
		$narration 		= $_POST['narration'];
		$re_id 		    = $_POST['re_id'];
		$doc_type 		= $_POST['doc_type'];
		
		
		$sql 	= " select * from sma_pettycash where id = '$re_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$company_id		= $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		
			
		$invoice_no = '';
		$amount_tds	= 0;
		$sql  = "SELECT * FROM `sma_pettycash_exp` where  approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r1 = mysqli_fetch_array($res1)){
		
			$invoice_no 	.= $r1['invoice_no'].' ';
			
		}
		
		$record_type 		= "Petty-P2P";
		$doc_no				= $re_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		$cheque_no			='';
		$address 			='';
		$gst_no 				='';
		$state				='';
		
		
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		
		if($tds_percentage > 0 && ($account_type == 'E' || $account_type =='D' )){
				$effect = 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id)
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		//echo $sql."<BR>";exit();
		
			$sql= " update tally_journal_entry set amount = amount - '$amount' where effect='CR' and account_type = 'V' and doc_type='$doc_type' and doc_no='$doc_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else if($tds_percentage > 0 && $account_type == 'A'){
			$effect = 'Dr';
			
			$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = '$doc_type' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'B' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
	
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id)
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		
		}

		
			$value = "<script>window.location.href='pettycash_expense.php?sub=edit&id=$re_id&active5=active';</script>";
		
		
		echo $value;
	//	https://hcone.co.in/workflow2020/petty/company_expense.php?sub=edit&id=1901
	
	}
?>		


<?php

	if(isset($_POST['sub2'])){

		$modulePath 	= "petty/";
		$re_id 			= $_POST['re_id'];
		$doc_type 		= $_POST['doc_type'];
		
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
		}	
		
		$sql 	= "select * from sma_pettycash where id = '$re_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 	= $r2['company_id'];
		$supplier_id 	= $r2['emp_id'];
		$invoice_date   = date('d-m-Y', strtotime($r2['dated']));
		$tally_narration 	 = $r2['remarks'];
		$location_id		 = $r2['location_id'];
		
		$sql = " SELECT * FROM `sma_location` where id = '$location_id' "; 
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$location_name 	= $r2['loc_name'];
		
		$tot_amount = 0;
		
		$sql = " SELECT distinct(b.budget_name), b.budget_head, b.account_name, a.amount, a.invoice_no, a.spend_by, a.paid_to, c.id as 'budget_id', c.project FROM `sma_pettycash_exp` a, account_mst b, sma_budget c 
				where b.id = a.expense_id and b.budget_name = c.budget_name and b.budget_head = c.budget_category 
				and c.project = '$company_id' and a.approval_ref_no = '$re_id'  ";
		
		$result1 = mysqli_query($con, $sql);
		$rows_affect = mysqli_affected_rows($con);
		echo mysqli_error($con);
		$value="";
		
		if($rows_affect==0){
			$sql = " SELECT distinct(b.budget_name), b.budget_head, b.account_name, b.id as account_id, a.amount, a.invoice_no, a.spend_by, a.paid_to FROM `sma_pettycash_exp` a, account_mst b
				where b.id = a.expense_id and a.approval_ref_no = '$re_id'  ";	
			$result1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$value="";	
		}	
		
		
		
		
//echo $rows_affect. ' <<>> ' . $sql. "<BR>"; exit();		
?>
		<span class="pull-right"><button type="button" class="btn btn-primary" id="addline" >Add</button></span>
		
		<table id="prtablea" class="table table-bordered table-striped" width="100%" >
			<thead>
				<tr>
					<th width="10%">#</th>
					<th width="40%">Account Name</th>
					<th width="10%">Effect</th>
					<th width="10%">Amount</th>
					<th width="10%">Action</th>
				</tr>
			</thead>
			
			<tbody>
		
<?php
		$i = 1;
		while($row = mysqli_fetch_array($result1)){
			
			$amount 		= $row['amount'];
			
			if($rows_affect>0){
				$budget_head 	= $row['budget_head'];
				$budget_id 		= $row['budget_id'];
				$invoice_no     = $row['invoice_no'];
				$spend_by		= $row['spend_by'];
				$paid_to		= $row['paid_to'];
				
				$sql 	= "select * from sma_budget_category where id = '$budget_head' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$account_id  		= $r2['id'];
				$budget_head 		= mysql_real_escape_string($r2['category']);
				$account_name       = $budget_head;
				$account_type		= 'B';
			}
			else {
				
				$account_name       = $row['account_name'];
				$account_id         = $row['account_id'];
				$account_type		= 'A';
			}
			
			$tot_amount = $tot_amount + $amount;
			$effect 			= "Dr";
			$record_type 		= "Petty-P2P";
			$doc_no				= $re_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$invoice_date		= date('d-m-Y', strtotime($invoice_date));
			
			$account_id			= $account_id;
			$effect				= $effect;
			$amount				= $amount;
			//$narration			= $narration;
			$cheque_no			= '';
			$address			= $address;
			$gst_no				= $gst_no;
			$state				= $state;

			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id) 
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql."<BR>";			
?>

			<tr>
				<td><?php echo $i++ ; ?> </td>
				<td><?php echo $budget_head ?> </td>
				<td><?php echo $effect ?> </td>
				<td style="text-align:right;"><?php echo $amount; ?> </td>
				<td>
					<a href='#modalEditTally' data-id='<?php echo $re_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $re_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
					<?php  include "edit_tally_func.php"; ?>	
					<a href="delete_tally.php?sub=delete&record_id=<?php echo $re_id;?>&url=<?php echo $url_var ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
				</td>
			</tr>
<?php
			
		}

		if($paid_to=='U'){
			$sql = "select * from sma_user where id = '$spend_by' ";
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name = $r2['username'];
			$account_id 	= $r2['id'];
			$address			= '';
			$gst_no				= '';
			$state				= '';
		}
		else if($paid_to=='V'){
			$sql = "select * from sma_party_mst where id = '$spend_by' ";
			$q2 	  = mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name 	= $r2['party_name'];
			$account_id 	= $r2['id'];
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['party_state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
		
		}
		else{
			$account_name       = $spend_by;
			$address			= '';
			$gst_no				= '';
			$state				= '';
		}	
			$record_type 		= "Petty-P2P";
			$doc_no				= $re_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$invoice_no			= $invoice_no;
			$invoice_date		= date('d-m-Y', strtotime($invoice_date));
			//$account_type		= $paid_to;
			//$account_id			= $supplier_id;
			//$account_name       = $party_name;
			$account_type		= 'A';
			$account_id			= '1';
			$account_name       = 'Imprest Project Site Expenses' . ' - '. $location_name;
			$effect				= 'Cr';
			$amount				= $tot_amount;
			//$narration		= $narration;
			$cheque_no			= '';
			
			/* $address			= $address;
			$gst_no				= $gst_no;
			$state				= $state; */
			
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
//echo $sql."<BR>";
		
?>
			<tr>
				<td><?php echo $i++ ; ?> </td>
				<td><?php echo $party_name; ?> </td>		
				<td><?php echo "Cr" ?> </td>
				<td style="text-align:right;"><?php echo $tot_amount; ?> </td>
				<td>
					<a href="company_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;</a>
					<a href="delete.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
				</td>
			</tr>
			</tbody>
		</table>
		
		<div class="form-group">
			<div class="col-sm-4">
				<label for="tally_status" class="control-label">Tally Update Status</label>
				<select class="form-control select2" name="tally_status" id="tally_status" >
					<option value="D"> Draft </option>
					<option value="R"> Ready to Update </option>
					<option value="U"> Updated </option>
				</select>
			</div>
		</div>

	
<?php
		$value = "<script>window.location.href='pettycash_expense.php?sub=edit&id=$re_id&active5=active';</script>";
		
		echo $value;
		
	}


	if(isset($_POST['sub3'])){
		
		$modulePath = "petty/";
		$actype = $_POST['id'];
		$doc_type 		= $_POST['doc_type'];
		
		if($actype =='A'){
			$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'E' or account_type = 'D' order by account_name ";
		
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
		$amount_cgst	= $_POST['amount_cgst'];
		
		$amount_gst_tot	= $amount_cgst;
		
		$amount_tds	= 0;
		$amount_gst = 0;
		$sql  = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$re_id' ";
//echo $sql;
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while ( $r2 = mysqli_fetch_array($res1)){
		
			$invoice_no 	.= $r2['invoice_no'].',';
			$amount 		= $r2['amount'];
			$gst_flag		= $r2['gst_flag'];

			//$amount_tot		= $amount_tot + $r2['amount'];
			if($gst_flag=='Y'){
				$amount_gst		= $amount_gst + (($amount * 18) / 100);
			}

		}
			
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//echo $sql;		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		$account_type	= $r3['account_type'];
		
		if($tds_percentage > 0 && ( $account_type== 'E' || $account_type=='D' )){
			//$tds_amount = ($amount_dr - $amount_gst) * $tds_percentage / 100;
			//Total Bill Amount-(Total Bill Amount-(Total Bill Amount*100/(100+Input GST)))* TDS /100
			
			$amount_dr_gst = $amount_dr - ($amount_gst_tot);
			$tds_amount = round(($amount_dr_gst * $tds_percentage) / 100,0);
			
			//echo $tds_amount. ' ' . $amount_dr. ' ' .$tds_percentage. ' ' .$amount_dr_gst;
			
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" name="amount" value="'.$tds_amount.'" >';
		}
		if($tds_percentage > 0 && ( $account_type== 'A' )){
			//$tds_amount = ($amount_dr - $amount_gst) * $tds_percentage / 100;
			//Total Bill Amount-(Total Bill Amount-(Total Bill Amount*100/(100+Input GST)))* TDS /100
			
			$amount_dr_gst = $amount_dr - ($amount_gst_tot);
			//$tds_amount = round($amount_dr_gst - ($amount_dr_gst * 100 / (100+ $tds_percentage)),0);
			$tds_amount = round(($amount_dr_gst * $tds_percentage) / 100,0);
				
//echo $tds_amount. ' ' . $amount_dr. ' ' .$tds_percentage. ' ' .$amount_dr_gst;
						
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" name="amount" value="'.$tds_amount.'" >';
		}
		else {
			echo '<input type="text" class="form-control" id="amountA" autocomplete="off" style="text-align:right;;" name="amount" value="" >';
		}

	}

?>
