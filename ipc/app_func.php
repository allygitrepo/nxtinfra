<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	if(isset($_POST['sub9'])){

		$modulePath = "ipc/"; 
	
		$value ='';
        $ipc_id = $_POST['ipc_id'];
		$srno  = $ipc_id;
		if($_POST['ipc_id'] == ''){$ipc_id = '';}
		
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approved 		= $_POST['approved'];
		$approverC		= $_POST['approver'];
		$company			= $_POST['company'];
		
		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_ipc where id = '$ipc_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		
		$company_id 		= $r2['sma_comp_id'];
		$sma_invoice_no		= $r2['sma_invoice_no'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
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
			   
		if($statusap=='Reject'){
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_username		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$to_approver		= $draft_by_id;
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
			
			$sql = "UPDATE sma_ipc SET 
			approver_1 = '', approver_2 = '', approver_3 = '',approver_4 = '',
			approver_5 = '', approver_6 = '', approver_7 = '',approver_8 = '',
			approver_1_status='Submitted', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() WHERE id = '$ipc_id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

//Budget Revert  overhead_exp!='Y'
			if($overhead_exp!='Y'){
				$sql  = "SELECT * from sma_approval_items where approval_hdr_id = '$ipc_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$quantity 		= $r2['quantity'];
					$rate 			= $r2['unit_rate'];
					$gst 			= $r2['gst'];
					$budget_id		= $r2['budget_id'];
					$amount	= round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
				
					$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
			}
			else if($overhead_exp=='Y'){
				$sql  = "SELECT * from sma_approval_expenses where approval_hdr_id = '$ipc_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$budget_id		= $r2['budget_id'];
					$amount			= $r2['amount'];
				
					$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
			}
			
//Budget Revert	
			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'IP', '$ipc_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		}
		else {
	//for Approve		
			
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_name 		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_company_work	= $r2['company_work'];
			
			
			$approver = $userid;
//echo $sql."<BR>";
//echo $approver_1 . ' == ' . $approver. "<BR>";
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
			if( $approver_5== $approver ){
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
			if( $approver_6== $approver ){
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
			if( $approver_7== $approver ){
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
			if( $approver_8== $approver ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
	//echo ' #### 2 ### ' . $to_approver. "<BR>";		
	//echo $to_approver. ' ' .$approval_status."<BR>"; 
	//exit();
		if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			$sql = " update sma_ipc set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$ipc_id'";
	//echo $sql. "<BR>";		
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('IP', '$ipc_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";		
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
	//echo $sql. "<BR>";		
			
		}
		
	}
		
//exit();
			
//Send mail to approver;
		$sql="select * from sma_user where id='$to_approver' and active='1' ";
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

		
		$modulePath = "ipc/";
		
//exit("RAVINDRA STOPED...");
		$baseurl1   = $baseurl.$modulePath.'ipc.php?sub=edit&id='.$ipc_id;
		
		$msg = 'IPC Number : '.$ipc_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ipc_mail.php";
		
		$role		= $_SESSION['role']; 
		if($role!='Maker'){
			$baseurl1 = $baseurl."dashboard.php?sub=dash&sopt=I";
			echo "<script>window.location.href='$baseurl1';</script>";
			$baseurl_lk = $baseurl.'dashboard.php' ;
			$baseurl_link = "<a href='$baseurl_lk'>".' Click here </a>';
			
		}
		else {
			$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=list';
			$baseurl_lk = $baseurl.'dashboard.php' ;
			$baseurl_link = "<a href='$baseurl_lk'>".' Click here </a>';
			
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		
		//$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=list';
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}
	

	if(isset($_POST['sub10'])){

		$value ='';
        $ipc_id = $_POST['ipc_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		if($status =='Draft' || $status == ''){
			$approval_status = 'Prepared';
			$status = 'Draft';
		}

		$sql = "update sma_ipc set flow_flag = 'P', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$ipc_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) 
									values('IP', '$ipc_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//Send mail to approver;
		$sql="select * from sma_user where id='$approver' ";
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

		$modulePath = "ipc/";
		$baseurl1 =$baseurl.$modulePath.'ipc.php?sub=edit&id='.$ipc_id;
		
		$msg = '<br> For Checker, IPC Number : '.$ipc_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ipc_mail.php";
		
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=list';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}

	
	if(isset($_POST['sub11'])){

		$value ='';
        $ipc_id 			= $_POST['ipc_id'];
		$mode		 	= $_POST['mode'];
		$remarks 		= $_POST['remarks'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		$approval_status = 'Prepared';
		$status = 'Draft';
		
		$sql = "update sma_ipc set flow_flag = 'P', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$ipc_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql 	= "select * from sma_ipc where id = '$ipc_id'";
		$query	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($query);
		$approver   	= $r2['draft_by'];
		
		
//Send mail to approver;
		$sql="select * from sma_user where userid = '$approver' ";
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

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) 
									values('IP', '$ipc_id', '$userid', now(), '$approval_status', '$id', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$modulePath = "ipc/";
		$baseurl1 =$baseurl.$modulePath.'ipc.php?sub=edit&id='.$ipc_id;
		
		$msg = '<br> For Maker to resend, IPC Number : '.$ipc_id . ' ' . 'Dated : ' . date("d-m-Y");
		if(!empty($remarks)){
			$msg .= '<br> Remarks given by checker : '.$remarks;
		}
		
	//	exit();
		
	include "ipc_mail.php";
		
		$baseurl1 = $baseurl.$modulePath.'ipc.php?sub=list';
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
		$sql = "SELECT a.id, a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
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
		

	if(isset($_POST['sub34'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$checker_valuepo	= $_POST['checker_valuepo'];
		$trans_type 		= $_POST['trans_type'];
		
		if($checker_value==0){
			$checker_value = $checker_valuepo;
		}	
		$sql = " SELECT * FROM sma_workflow 
					where 1 and doc_type = 'IP' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value
					and company_id = '$company_id' 
					and trans_type = '$trans_type' ";
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
					
								<div class="col-sm-1">
									<label class="control-label"><span style="color:red;">**</span></label>
								</div>
						<?php 
						
						if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
							<?php 		
								$sql = " select * from sma_user where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow 
												where 1 and doc_type = 'IP'and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_1 >0 ), role ) 
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
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_2 >0 ), role ) 
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
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_3 >0 ), role ) 
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
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_4 >0 ), role ) 
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
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_5 FROM sma_workflow 
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
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

									<select class="form-control  approver_5" name="approver_5" <?= $required4; ?> >
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
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_6 FROM sma_workflow 
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
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
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_7 FROM sma_workflow 
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
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
							
									<select class="form-control  approver_7" name="approver_7" <?= $required4; ?> >
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
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_8 FROM sma_workflow 
											where 1 and doc_type = 'IP' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
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
									<select class="form-control  approver_8" name="approver_8" <?= $required4; ?> >
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

?>
