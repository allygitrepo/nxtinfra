<?php session_start();

	include('../dbcon.php');
	include('../baseurl.php');
	$modulePath = "petty/";	
?>

<?php

$te_id = '';
$ta_id = '';

if(isset($_POST['sub10'])){

	$approval_ref_no= $_POST['approval_ref_no'];
	//$trans_type		= $_POST['trans_type'];	
	$expence_name	= $_POST['expence_name'];
	$dated			= date('Y-m-d', strtotime($_POST['dated']));
	$amount			= $_POST['amount'];
	$sma_vendor_id	= $_POST['sma_vendor_id'];
	$invoice_nm		= $_POST['invoice_nm'];
	$remarks		= $_POST['remarks'];
	$trans_type		= $_POST['trans_type'];
	$paid_to		= $_POST['paid_to'];
	$company_id     = $_POST['company_id'];
	$hdr_date     	= date('Y-m-d', strtotime($_POST['hdr_date']));
	$location_id    = $_POST['location_id'];
	$hdr_remarks    = $_POST['hdr_remarks'];
	
/* 	$budget_name	= $_POST['budget_name'];
	$budget_head	= $_POST['budget_head']; */
	
	$budget_name	= '';
	$budget_head	= '';
	
			$sql = "select count(*) as cnt, company_id  from sma_pettycash where id = '$approval_ref_no' ";
			$query=mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($query);
			$nct	=	$r2['cnt'];
			$comp_id = $r2['company_id'];
			if( $nct>0 && empty($comp_id) ){
				$sql="update sma_pettycash set company_id ='$company_id',
						dated			= '$hdr_date',
						location_id		= '$location_id',
						remarks 		= '$hdr_remarks',
						trans_type		= '$trans_type'
					where id='$approval_ref_no'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}	
			}
					
			
//echo $filename;
//exit();
	$sql="Insert into sma_pettycash_exp (approval_ref_no, expense_id, dated, invoice_no, amount, spend_by, note, paid_to, budget_name, budget_head ) values(  '$approval_ref_no', '$expence_name', '$dated', '$invoice_nm', '$amount', '$sma_vendor_id', '$remarks', '$paid_to', '$budget_name', '$budget_head' )";

	$result = mysqli_query($con, $sql);
//echo $sql;
	//exit(); 
				if($trans_type=='P'){
						$sql 	= "update sma_location set paid_total = paid_total + $amount where id = '$location_id' ";
				}
				else if($trans_type=='R'){
					$sql 	= "update sma_location set received_total = received_total + $amount where id = '$location_id' ";
				}				
				$q2 	= mysqli_query($con, $sql);	
				
				
	
		$baseurl1 =$baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$approval_ref_no;
		
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}	

//Petty Cash Expenses. Pending
if(isset($_POST['sub14'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$approver	= $_POST['approver'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];

		$approval_status = 'Pending';
		$status 		 = 'Submitted';
		
		$sql = "update sma_pettycash set approval_status = '$approval_status', status = '$status', changed_by = '$user', send_to = '$approver', level_1 = '$approver',  level_1_flag = 'N', level_2_flag = 'N', level_3_flag = 'Y', changed_date = now() where id = '$re_id' ";
//echo $sql;
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
					values('PC', '$re_id', '$userid', now(), '$approval_status', '$approver', '$remarks', now())";
//echo $sql;

		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
		$sql="select * from sma_user where id in ($approver)";
//echo $sql;
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
	
	$modulePath = "petty/";
		
		$baseurl1 =$baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$re_id;
		
		$msg = 'Petty Cash Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = 'Petty Cash Expenses';

//echo $msg. ' '. $msg1; exit();
		
		include "pc_mail.php";
		
		$baseurl1 = $baseurl.$modulePath.'pettycash_expense.php?sub=list';
		
//		echo $baseurl1;
//		exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();

}


//Petty Cash Expenses. Approval
if(isset($_POST['sub15'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$re_id		= $_POST['re_id'];
	$mode		= $_POST['mode'];
	$status		= $_POST['status'];
	$remarks	= $_POST['remarks'];
	
	$user   	= $_SESSION['user'];
	$userid   	= $_SESSION['usrid'];
	$approver	= $userid;
	
	if($status=='Submitted'){
	
		$approval_status = 'Approved';
		$status 		 = 'Completed';
		
		$sql="select * from sma_pettycash where id = '$re_id' ";
		$result = mysqli_query($con, $sql);
		$r2 		= mysqli_fetch_array($result);
		$trans_type			= $r2['trans_type'];
		
		$tally_narration	= $r2['tally_narration'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_name 		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_company_work	= $r2['company_work'];
			
//echo $sql."<BR>";
//echo $draft_by_id;
	
			$status_field_from ='';
			$decision_status	= '';
			if( $approver_1== $approver ){
				$to_approver 	 	= $approver_2;
				$status_field_from 	= 'approver_1_status';
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
			if( $approver_2== $approver ){
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
			if( $approver_3== $approver ){
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
			if( $approver_4== $approver ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
		if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = " update sma_pettycash set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$re_id'";
	//echo $sql. "<BR>";		//exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('PC', '$re_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
	//echo $sql. "<BR>";		
			
	}
	
	if($status == 'Completed'){
		$sql="select * from sma_user where find_in_set('$company_id', company_id) and role in (select id from sma_role where role ='Accountant') ";
		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$ac_email		= $r->email;
		$ac_email_name	= $r->username;
	}		
		
			$sql = "SELECT a.id as pc_id, a.expense_id,  a.budget_id, a.amount, b.company_id, b.location_id as location_id, b.account_id
					FROM `sma_pettycash_exp` a, sma_pettycash b, sma_product c 
					where a.approval_ref_no = b.id and a.expense_id = c.id and b.id = '$re_id' ";		
//echo $sql."<BR>"; 				
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$pc_id			= $r->pc_id;
				$exp_id			= $r->expense_id;
				$amount			= $r->amount;
				$budget_id		= $r->budget_id;
				$company_id		= $r->company_id;
				$location_id	= $r->location_id;
				$tally_account_id	= $r->account_id;
				
				if($trans_type=="P"){
					$sql 	= "update sma_location set paid_total = paid_total + $amount where id = '$location_id' ";
				//echo $sql. "<BR>";	
					$q2 	= mysqli_query($con, $sql);		
				}
				else if($trans_type=="R"){
					$sql 	= "update sma_location set received_total = received_total + $amount where id = '$location_id' ";
					$q2 	= mysqli_query($con, $sql);
				}
	
			}
		//echo $sql. "<BR>";	
//TALLY Journal and STATUS UPDATE after approved	

			create_tally_journal($re_id);

//exit();
			$sql = "update `tally_journal_entry` set status = 'R',  narration = '$tally_narration' where doc_no = '$re_id' and doc_type = 'PC' ";
			$r2  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			
//TALLY STATUS UPDATE after approved
	}
	
//echo $sql;
		$sql="select * from sma_user where id = '$to_approver' ";
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
	
		$modulePath = "petty/";
		
		$baseurl1 =$baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$re_id;
		
		$msg = ' Petty Cash Number : '.$re_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 = ' Petty Cash Expenses ';
		
		//echo $msg . ' ' . $msg1; exit();
 		include "pc_mail.php";
		
		$role		= $_SESSION['role']; 
		if(!empty($status_field_from || $approval_status = 'Approved' )){
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash";
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		else {
			$baseurl1 = $baseurl.$modulePath.'pettycash_expense.php?sub=list';
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();

}

//Petty Cash Expenses. Rejected
if(isset($_POST['sub16'])){
	
	//$remarks 		= $_POST['remarks'];
	$approval_ref_no= $_POST['approval_ref_no'];
	$te_id			= $_POST['re_id'];
	$mode			= $_POST['mode'];
	$status			= $_POST['status'];
	$remarks		= $_POST['remarks'];
	
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	
		$sql="select * from sma_user where userid in (select draft_by from sma_pettycash where id = '$te_id' ) ";
//echo $sql."<BR>";		
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$level_a		= $r->id;
	
		$approval_status = 'Rejected';
		$status 		 = 'Draft';
		
		$sql = "update sma_pettycash SET approver_1 = '', approver_2 = '', approver_3 = '',approver_4 = '', approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', approval_status = '$approval_status', 
		status = '$status', changed_by = '$user', changed_date = now() 
		WHERE id = '$te_id' ";
		
			
//echo $sql;		
//exit();
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
									values('PC', '$te_id', '$userid', now(), '$approval_status', '$level_a', '$remarks', now())";
//echo $sql;		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//echo $sql;
		$sql="select * from sma_user where id in ($level_a)";
//echo $sql;
//exit();
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
	
		$modulePath = "petty/";
		
		$baseurl1 =$baseurl.$modulePath.'pettycash_expense.php?sub=edit&id='.$te_id;
		
		$msg 	= 'Petty Cash Expenses Number : '.$te_id . ' ' . 'Date : ' . date("d-m-Y");
		$msg1 	= 'Petty Cash Expenses';
		$re_id	= $te_id;
		include "pc_mail.php";
	
		$baseurl1 = $baseurl.$modulePath.'pettycash_expense.php?sub=list';
		
	//	echo $baseurl1;
	//	exit("Ravindra Exit");
	
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}


	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc 	 = $_POST['party_id_doc'];
		$company_idd_doc = $_POST['company_idd_doc'];
		
		if($_POST['id'] == ''){$id = '';}
?>			
			<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Party Name</th>
					<th>Document Type</th>
					<th>File Path</th>
					<th>File Name</th>
					<th>Inward No</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>

<?php				
		$sql = "SELECT a.id, a.expense_id_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.expense_id_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><a href="<?php echo $baseurl.'dms/'.$row['file_path'].'/'.$row['file_name'];?>" target="_blank"><?php echo $row['file_name'];?></a> </td>
					<td width="10%" ><?php echo $row['inward_no'];?></td>
					
					<td width="05%">
						<input type="checkbox" name="party_doc[]" <?php echo $checked; ?> id="party_doc" value="<?php echo $row['id']; ?>" >
					</td>
			</tr>
<?php			
			}
			
			$value = '';
					
//$value=$sql;

        echo $value;
    }
	

	if(isset($_POST['sub24'])){
    
        $loc_id = $_POST['id'];
		
		$sql 	= "select * from sma_location where id = '$loc_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$op_balance 	= $r2['op_balance'];
		$received_total = $r2['received_total'];
		$paid_total 	= $r2['paid_total'];
		$balance_cash 	= $op_balance + $received_total - $paid_total;
?>		
		<input type="text" class="form-control" id="balance_cash" readonly name="balance_cash" style="text-align:right;" value="<?php echo number_format($balance_cash,2); ?>" >
<?php		
	}

	if(isset($_POST['sub25'])){
    
        $paidto = $_POST['id'];
		
		if($paidto =='V' ){
?>		
		<label class=" control-label">Party Name </label>
			<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_iD" autocomplete="off" required >
				<option value=""> Select </option>
			<?php $sql = "select * from sma_party_mst where 1 order by party_name ";
				$q2 	  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name']. ' ' . $r2['id'];?></option>
			<?php } ?>
		</select>
<?php		
		}
		else if($paidto =='U' ){
?>		
		<label class=" control-label">User Name </label>
			<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_iD" autocomplete="off" required >
				<option value=""> Select </option>
			<?php $sql = "select * from sma_user where active = '1' order by username ";
				$q2 	  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" > <?php echo $r2['username']. ' ' . $r2['id'];?></option>
			<?php } ?>
		</select>
<?php		
		}
		else 
		if($paidto =='O' ){
?>			
			<label class=" control-label">Paid To </label>
			<input type="text" class="form-control" id="sma_vendor_iD"  name="sma_vendor_id" autocomplete="off" placeholder="Enter Others" value="" >
<?php
		}
	
	}	



	if(isset($_POST['sub26'])){
    
        $company_id = $_POST['id'];
		
?>
			<select class="form-control" name="location_id" id="location_ID" autocomplete="off" required onchange="getpettycashbal(this.value)" >
				<option value=""> Select </option>
				<?php $sql = "select * from sma_location where loc_comp_id = '$company_id' ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['id'];?>" <?php echo ($row['location_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
				<?php } ?>
			</select>
								
<?php		
	}
	
	if(isset($_POST['sub34'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		
		$sql = " SELECT * FROM sma_workflow 
					WHERE 1 and doc_type = 'PC' 
					AND company_id = '$company_id' ";

//and '$checker_value' >= from_value and '$checker_value' <= to_value and trans_type = '$trans_type' 		
//echo $sql. "<BR>";					
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		
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
		

									
?>    
		<div class="box-footer">
					
					<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
					
								<div class="col-sm-1">
									<label class="control-label"><span style="color:red;">**</span></label>
								</div>
						<?php 
						
						if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
							<?php 		
								$sql = " select * from sma_user where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow 
												where 1 and doc_type = 'PC' and company_id = '$company_id' and approval_role_1 >0 ), role ) 
											and id != '$userid' 
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
									<select class="form-control  approver_1" name="approver_1" required <?= $required1; ?> >
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
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php

								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_2 FROM sma_workflow 
											where 1 and doc_type = 'PC' and company_id = '$company_id' and approval_role_2 >0 ), role ) 
											and id != '$userid' 
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
									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?> >
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
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_3 FROM sma_workflow 
											where 1 and doc_type = 'PC' and company_id = '$company_id' and approval_role_3 >0 ), role )
											and id != '$userid' 
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
							
									<select class="form-control  approver_3" name="approver_3" <?= $required3; ?> >
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
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_4 FROM sma_workflow 
											where 1 and doc_type = 'PC' and company_id = '$company_id' and approval_role_4 >0 ), role ) 
											and id != '$userid' 
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
								
									<select class="form-control  approver_4" name="approver_4" <?= $required4; ?> >
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
						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getsubmit();" >
								</div>
								
						</div>
						<br>
<?php						
	
	}


function create_tally_journal($re_id){
	
		include('../dbcon.php');
		
		$modulePath 	= "petty/";
		//$re_id 			= $_POST['re_id'];
		$doc_type 		= 'PC';
		
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$re_id' and doc_type = '$doc_type' ";
//echo $sql; exit();
		
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
		$tally_account_id	 = $r2['account_id'];
		
		$sql = " SELECT * FROM `sma_location` where id = '$location_id' "; 
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$location_name 	= $r2['loc_name'];
		
		$tot_amount = 0;
		
		$sql = " SELECT c.budget_code, c.budget_head, b.name as 'account_name', a.amount, a.invoice_no, a.spend_by, a.paid_to, c.id as 'budget_id', c.project 
			FROM `sma_pettycash_exp` a, sma_product b, sma_budget c, sma_product_cost_center d  
			WHERE b.id = a.expense_id and a.approval_ref_no = '$re_id'
				AND d.product_id = a.expense_id and d.company_id = '$company_id'
				AND c.id = d.budget_id and c.project = d.company_id
				AND c.project = '$company_id' ";
	
//echo $sql."<BR>";		
		$result1 = mysqli_query($con, $sql);
		$rows_affect = mysqli_affected_rows($con);
		echo mysqli_error($con);
		$value="";
		
		/* if($rows_affect==0){
			$sql = " SELECT distinct(b.budget_name), b.budget_head, b.account_name, b.id as account_id, a.amount, a.invoice_no, a.spend_by, a.paid_to FROM `sma_pettycash_exp` a, account_mst b
				where b.id = a.expense_id and a.approval_ref_no = '$re_id'  ";	
			$result1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$value="";	
		} */

//echo $sql. "<BR>"; exit();
		
?>
		
<?php
		$i = 1;
		while($row = mysqli_fetch_array($result1)){
			
			$amount 		= $row['amount'];
			
			if($rows_affect>0){
				$budget_head 	= $row['budget_head'];
				$budget_code 	= $row['budget_code'];
				$budget_id 		= $row['budget_id'];
				$invoice_no     = $row['invoice_no'];
				$spend_by		= $row['spend_by'];
				$paid_to		= $row['paid_to'];
				
				$account_name       = $budget_code;
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
			$invoice_date		= date('d-m-Y', strtotime($invoice_date));
			
			$account_type		= 'A';
			//$account_id			= '1';
			
			$sql 	= "select * from account_mst where id = '$tally_account_id' ";
//echo $sql. "<br>";			
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id		= $r2['account_id'];
			$account_name 	= $r2['account_name'];
			/* if(empty($account_name)){
				$account_name       = 'Imprest Project Site Expenses' . ' - '. $location_name;
			} */
			$effect				= 'Cr';
			$amount				= $tot_amount;
			$cheque_no			= '';
			
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no) 
			VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<br>";			

//exit();
		
}	
				
?>
			
