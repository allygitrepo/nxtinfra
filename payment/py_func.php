<?php 
	
	session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
	$userid   	= $_SESSION['usrid'];	
?>

<?php
		
	if(isset($_POST['sub2'])){
	
		$value ='';

		$payment_hdr_id 	= $_POST['payment_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_name 		= $_POST['account_name'];
		$against_invoice    = $_POST['against_invoice'];
        $invoice_number     = $_POST['invoice_number'];
		$debit_credit    	= $_POST['debit_credit'];
		$amount    			= $_POST['amount'];
		$remarks 			= $_POST['remarks'];
		
		$sql = "insert into `payment_details` (payment_hdr_id, account_type, account_name, against_invoice, invoice_number, debit_credit, amount, remarks ) 
		values ( '$payment_hdr_id', '$account_type', '$account_name', '$against_invoice', '$invoice_number', '$debit_credit', '$amount', '$remarks')";

		$r2 = mysqli_query($con, $sql);
		
		$file = fopen("ravitest.txt","w");
		fwrite($file,$sql);
		fclose($file);

//		$value .= $sql;
			
//$value = $value1;
		$value = "<script>window.location.href='edit.php?sub=edit&id=$payment_hdr_id&active=active';</script>";
	
		echo $value;
				
	}

	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		$py_id = $_POST['py_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from payment_details where id = '$id' ";

		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//$value = $value1;
		echo "<meta http-equiv='refresh' content='0'>";    
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$py_id&active=active&123';</script>";
		echo $value;
		
	}



	if(isset($_POST['sub5'])){
	
		$value ='';
        $id = $_POST['id'];		
		if($_POST['id'] == ''){$id = '';}
		
		$baseurl1 = $baseurl . "supplier_invoice/edit.php?sub=edit&id=$id";
		$value = "<script>window.location.href='$baseurl1';</script>";
		echo $value;
		
	}
	


	if(isset($_POST['sub8'])){
	
		$modulePath = "payment/"; 
	
		$value ='';
        $py_id 			= $_POST['py_id'];
		$supp_id 		= $_POST['supp_id'];
		$srno  			= $py_id;
		if($_POST['py_id'] == ''){$py_id = '';}		
		$mode		 	= $_POST['mode'];
		$approver 		= $_POST['approver'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		$paid_to 		= $_POST['paid_to'];
		$cheque_no		= $_POST['cheque_no'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from payment_header where id = '$py_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$st_flag	 		= $r2['st_flag'];
		$company_id 		= $r2['project'];
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
		
		$sql = " select * from sma_user where userid in (select draft_by from payment_header where id = '$py_id') ";
		$result=mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		$draft_by 			= $row['userid'];
		$draft_by_id 		= $row['id'];

		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
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
			 if( $approver_2== $approver && $approver_2_status=='Submitted' ){
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
			 if( $approver_3== $approver && $approver_3_status=='Submitted'  ){
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
			 if( $approver_4== $approver && $approver_4_status=='Submitted' ){
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
			 if( $approver_5== $approver && $approver_5_status=='Submitted' ){
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
			 if( $approver_6== $approver && $approver_6_status=='Submitted' ){
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
			 if( $approver_7== $approver && $approver_7_status=='Submitted'  ){
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
			 if( $approver_8== $approver && $approver_8_status=='Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
		
//echo $approval_status. ' ' .$approver_7 . ' == '. $approver. "<BR>"; 
//exit();
				
		if( $mode =='Reject' ){
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
			
			$sql = "update payment_header set approver_1_status = '', approver_2_status = '',approver_3_status = '',  approver_4_status = '', approver_5_status = '', approver_6_status = '',approver_7_status = '',approver_8_status = '', approver_1 = '', approver_2 = '', approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '',approver_7 = '',approver_8 = '', current_approver='$draft_by_id', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$py_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by,  remarks, approved_date) values('PY', '$py_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now() )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$to_approver = $draft_by_id;
			
		}
		else {
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = "update payment_header set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$py_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
					values( 'PY', '$py_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
		}	

		$s="select * from sma_user where id='$to_approver' ";
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	
		$party_name  = '';
		$party_email = '';
		if($status == 'Completed' && !empty($cheque_no) ){
			if($st_flag=='S' || $st_flag=='C' || $st_flag=='D'){
				$sql="SELECT * FROM sma_party_mst where id ='$paid_to' ";
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($q2);
				$party_name  = $r2['party_name'];
				$party_email = $r2['party_email'];
				//$user_name	 = $party_name;
			}
			else {
				$s="select * from sma_user where id='$paid_to' ";
				$sql = mysqli_query($con, $s);
				$rowcount = mysqli_num_rows($sql);
				$r = mysqli_fetch_object($sql);
				$party_email		= $r->email;
				$party_name		= $r->username;
			}
		}

//Create Tally JV			
		if($status == 'Completed'){
			$sql 	= " UPDATE payment_header SET tally_status='C', tally_ticked_by = '$userid' , tally_updated_on = now() where id = '$py_id' ";
			mysqli_query($con, $sql);
			
			$sql 	= " UPDATE tally_journal_entry SET status = 'C' where doc_type = 'PY' and doc_no = '$py_id' ";
			mysqli_query($con, $sql);
		}
//Create Tally JV	
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
		$msg = 'Payment Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");
		include "py_mail.php";

		echo "<script>alert('Thanks! Completed.. Please OK to Cont...')</script>";
		
		$role		= $_SESSION['role']; 
		if($role!='Accountant'){
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=Y";
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		else {
			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		
		//$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}

	if(isset($_POST['sub9'])){
	
		$modulePath = "payment/"; 
	
		$value ='';
        $py_id 			= $_POST['py_id'];
		$srno  			= $py_id;
		if($_POST['py_id'] == ''){$py_id = '';}		
		$mode		 	= $_POST['mode'];
		$st_flag		= $_POST['st_flag'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		
		
		$status 		= 'Draft';
		$approval_status	= '';
		if (!empty($remarks)){
			
			$user   	= $_SESSION['user'];
			$userid   	= $_SESSION['usrid'];
			$user_name_by 	= $_SESSION['user_name_by'];
				
			if( $st_flag == 'S' || $st_flag == 'D' || $st_flag == 'R' ){
				$sql = "update sma_supplier_invoice set approval_status	= '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id in ( SELECT supp_id  FROM `payment_details` where payment_hdr_id = '$py_id' ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			else if($st_flag=='A'){
				$sql = "update `sma_traval_approval` set approval_status = '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id in ( SELECT supp_id  FROM `payment_details` where payment_hdr_id = '$py_id' )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			else if($st_flag=='T'){
				$sql = " update sma_travel_expenses set approval_status = '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id in ( SELECT supp_id  FROM `payment_details` where payment_hdr_id = '$py_id' )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			else if($st_flag=='C'){
				$sql = " update sma_travel_expenses set approval_status = '$approval_status', status =  '$status', changed_by = '$user', changed_date = now() where id in ( SELECT supp_id  FROM `payment_details` where payment_hdr_id = '$py_id' )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}	
			}
			
		//echo $sql;
			
			$sql = " select * from payment_details where payment_hdr_id = '$py_id'  ";
	//echo $sql;
			$result = mysqli_query($con, $sql);
			while($r = mysqli_fetch_object($result)){
				$supplier_invoice_no = $r->supplier_invoice_no;
				$supp_id 		 = $r->supp_id;
				$id		 		 = $r->id;
				
			if( $st_flag=='S' || $st_flag=='D' || $st_flag=='R' ){
					$sql = " select * from sma_user where userid in (SELECT distinct(draft_by) FROM `sma_supplier_invoice` WHERE supplier_invoice_no = '$supplier_invoice_no' ) ";
					$sm_text = 'Supplier Invoice';
				}
				else if( $st_flag=='C'){
					$sql = " select * from sma_user where userid in (SELECT distinct(draft_by) FROM `sma_travel_expenses` WHERE id = '$supp_id' ) ";
					$sm_text = ' Operating Expense ';
				}
				else if( $st_flag=='A' || $st_flag=='T'){
					$sql = " select * from sma_user where userid in (SELECT distinct(draft_by) FROM `sma_traval_approval` WHERE id = '$supp_id' ) ";
					$sm_text = ' Reimbursement ';
					
				}
				
				$res = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($res)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_category 	= $r->user_category;
					$user_email		= $r->email;
					$user_name_by	= $r->username;
				}
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date) values('PY', '$py_id', '$userid', now(), '$status', '$id', '$approved', '$remarks', now() )";
		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;

				$msg = 'Payment Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");
				$msg .= "<br><br>". $sm_text . ' Number:'.$supplier_invoice_no ;
				$msg .= "<br><br>".$remarks;
				include "py_mail.php";
				
			}
//echo $msg;			
//exit();
			echo "Email Sent...";
			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
	}
?>		

<?php

	if(isset($_POST['sub24'])){

		$company_id 	= $_POST['company_id'];
		$checker_value  = $_POST['tot_payment_adjusted'];
		$st_flag		= $_POST['st_flag'];
		$py_id			= $_POST['py_id'];
		
		$doc_type 		= 'PY';
		
		if($st_flag=='D'){
			$doc_type 		= 'AD';
			//$doc_type 		= 'SI'; //As per Femi
		}
		if($st_flag=='R'){
			$doc_type 		= 'PYR';
		}
		if($st_flag=='M'){
			$doc_type 		= 'PYC';
		}
		
		$sqlq 			= "";
		
		$sql = "SELECT * FROM `payment_details` where payment_hdr_id ='$py_id' ";
		$query11 = mysqli_query($con, $sql);
		echo mysqli_error($con);

		$r3  = mysqli_fetch_array($query11);
		$supplier_invoice_no = $r3['supplier_invoice_no'];
		$supp_id			 = $r3['supp_id'];
		
		if($st_flag=='D'){

			$sql = "SELECT * from  payment_header where id = '$py_id' ";
			$query11 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3  = mysqli_fetch_array($query11);
			$trans_type  = $r3['trans_type'];
			$doc_type    = 'AD';
			//$doc_type 		= 'SI'; //As per Femi
			//$sqlq 		 = " and b.id = '$trans_type' ";

		}
		
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$company_id = $r2['comp_id'];
		
		$sql = " SELECT * FROM sma_workflow
					where 1 and doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' " ;
//echo $sql;
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];

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


?>    
		<div class="box-footer">
								<div class="col-sm-1">
									<label class="control-label"><span style="text-align:right;color:red;">**</span></label>
								</div>
							<?php if($approval_role_1>0){ ?>			
								<div class="col-sm-3">
									<label class="control-label">Approver 1 </label>
							<?php
							
								$sql = " select * from sma_user where 1 and active = '1' and id in( SELECT distinct(approval_role_1) FROM sma_workflow  
								            where 1 and doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
								                and company_id = '$company_id' and approval_role_1 >0 ) ";
								//and FIND_IN_SET($company_id,company_id) ";//  and id != '$userid'
								$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>

							
									<select class="form-control  approver_1" name="approver_1" <?= $required1; ?>   >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php } ?>	
							<?php if($approval_role_2>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php 
									$sql = " select * from sma_user where 1 and active = '1' and id in( SELECT distinct(approval_role_2) FROM sma_workflow  
								            where 1 and doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
								                and company_id = '$company_id' and approval_role_2 >0 ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>			
									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?>  >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if($approval_role_3>0){ ?>				
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php 
									$sql = " select * from sma_user where 1 and active = '1' and id in( SELECT distinct(approval_role_3 ) FROM sma_workflow  
								            where 1 and doc_type = '$doc_type'  and '$checker_value' >= from_value and '$checker_value' <= to_value 
								                and company_id = '$company_id' and approval_role_3 >0 ) ";
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
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if($approval_role_4>0){ ?>				
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
									
							<?php
									$sql = " SELECT * from sma_user where FIND_IN_SET( ( SELECT distinct(approval_role_4) FROM sma_workflow a , sma_workflow_type b 
									where 1 and b.id = a.trans_type and b.status = 'Y'
									and a.doc_type = '$doc_type' $sqlq   
									and '$checker_value' >= from_value and '$checker_value' <= to_value 
									and company_id = '$company_id' and approval_role_1 >0 ), role )  and FIND_IN_SET($company_id,company_id) ";//and id != '$userid'
								$rs = mysqli_query($con, $sql);
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
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if($approval_role_5>0){ ?>				
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php
								$sql = " select * from sma_user WHERE 1 and active = '1' and FIND_IN_SET( ( SELECT distinct(approval_role_5) FROM sma_workflow a , sma_workflow_type b 
									where 1 and b.id = a.trans_type and b.status = 'Y'
									and a.doc_type = '$doc_type' $sqlq  
								and '$checker_value' >= from_value and '$checker_value' <= to_value and company_id = '$company_id' and approval_role_1 >0 ), role )  and FIND_IN_SET($company_id,company_id) ";//and id != '$userid'
									$rs = mysqli_query($con, $sql);
									$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>
							
									<select class="form-control  approver_5" name="approver_5" <?= $required5; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							<?php if($approval_role_6>0){ ?>				
								<div class="col-sm-6">
									<label class="control-label">Approver 6</label>
							<?php
								$sql = " select * from sma_user where 1 and active = '1' and FIND_IN_SET( ( SELECT distinct(approval_role_6) FROM sma_workflow a , sma_workflow_type b 
								where 1 and b.id = a.trans_type and b.status = 'Y'
								and a.doc_type = '$doc_type' $sqlq  and '$checker_value' >= from_value and '$checker_value' <= to_value and company_id = '$company_id' and approval_role_1 >0 ), role ) and FIND_IN_SET($company_id,company_id) ";//and id != '$userid' 
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>			
									<select class="form-control  approver_6" name="approver_6" <?= $required6; ?> >
                                     <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?>  <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
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

    if(isset($_POST['sub27'])){
    
        $company_id = $_POST['id'];
		$st_flag	= $_POST['st_flag'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
//		$comp_vertical = $r2['comp_vertical'];
 
		if($st_flag=='D'){
			$doc_type = 'AD';	
		}	
		else {
			$doc_type = 'SI';	
		}	
 
//echo $sql = "SELECT DISTINCT(id) as id, workflow_type FROM sma_workflow_type a, `sma_workflow` b where a.id = b.trans_type and a.status = 'Y'  and company_id = '$company_id' and doc_type = 'SI' order by workflow_type ";
 
?>
		<div class="form-group">
			<div class="col-md-5">
				<label for="company_id" class="control-label ">Workflow Type</label>
				<select class="form-control select3" name="trans_type" id="trans_type" required >
					<option value=""> Select </option>
					<?php $sql = "SELECT DISTINCT(a.id) as id, workflow_type FROM sma_workflow_type a, `sma_workflow` b where a.id = b.trans_type and a.status = 'Y'  and company_id = '$company_id' and a.doc_type = '$doc_type' order by workflow_type ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['workflow_type'];?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	
<?php
	}

?>
<?php 
	if(isset($_POST['sub35'])){
		$modulePath = "payment/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$py_id		 		= $_POST['py_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page					= $_POST['page']; 		
		$baseurl .=$modulePath.'edit.php?sub=edit&id='.$py_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$py_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
				
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM payment_header where id = '$py_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		//$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];				
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$approver_id 			= $r2['id'];
		include("comment_mail.php");
		
		if(!empty($approver_1_status)){
			$approver_id		= $rw['approver_1'];	
			include("comment_mail.php");
		}		
		if(!empty($approver_2_status)){
			$approver_id		= $rw['approver_2'];	
			include("comment_mail.php");
		}
		if(!empty($approver_3_status)){
			$approver_id		= $rw['approver_3'];	
			include("comment_mail.php");
		}
		if(!empty($approver_4_status)){
			$approver_id		= $rw['approver_4'];	
			include("comment_mail.php");
		}
		if(!empty($approver_5_status)){
			$approver_id		= $rw['approver_5'];	
			include("comment_mail.php");
		}
		if(!empty($approver_6_status)){
			$approver_id		= $rw['approver_6'];	
			include("comment_mail.php");
		}
		if(!empty($approver_7_status)){
			$approver_id		= $rw['approver_7'];	
			include("comment_mail.php");
		}
		if(!empty($approver_8_status)){
			$approver_id		= $rw['approver_8'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
} ?>

