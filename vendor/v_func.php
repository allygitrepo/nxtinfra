<?php session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$comid  	= $_SESSION['comid'];
	$userid   	= $_SESSION['usrid'];	
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="party_city" id="party_city" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from cities where states_id = '$id' order by city_name";
		$q2  = mysqli_query($con, $sql);
		while($row = mysqli_fetch_object($q2)){ 
			$city_name = $row->city_name;
            $id = $row->id;
            $value .= "<option value='".$id."'>".$city_name."</option>";
        };
		$value .= '</select>';
//		$value = $sql;
        echo $value;
    }


	  
	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		//$checker_value 		= $_POST['checker_value'];
		$checker_value 		= '1000';
		$doc_type = 'VN';
		
		$sql = "select * from company where comp_id in ( '$comid' ) ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id'";	
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
												and approval_role_1 >0 ), role ) 
											and FIND_IN_SET('$company_id', company_id ) ";
										//and id 			!= '$userid' 	
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
												and approval_role_2 >0 ), role ) 
											and FIND_IN_SET('$company_id', company_id )";
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
												and approval_role_3 >0 ), role ) 
											
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
												and approval_role_4 >0 ), role ) 
											
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
												and approval_role_5 >0 ), role ) 
											
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
												and approval_role_6 >0 ), role ) 
											
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
												and approval_role_8 >0 ), role ) 
											
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

		$modulePath = "vendor/"; 
	
		$value ='';
        $vn_id = $_POST['vn_id'];
		$srno  = $vn_id;
		if($_POST['vn_id'] == ''){$vn_id = '';}
		
		$remarks 		= $_POST['remarks'];
		$mode			= $_POST['mode'];
//echo $mode. "<BR>";

		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$approver 		= $userid;
		
		$sql = " select * from sma_party_mst where id = '$vn_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$vendor_name 		= $r2['party_name'];
		$draft_by 			= $r2['draft_by'];
		//$draft_by_name	= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2 		= $r2['approver_2'];
		$approver_2_status 	= $r2['approver_2_status'];
		
		
		if(empty($draft_by) || $draft_by==0){
			$sql = " SELECT * from kyc_upd_log where party_id = '$vn_id' order by id asc LIMIT 0,1";
			$q2	=	mysqli_query($con, $sql);
			$r2 =	mysqli_fetch_array($q2);
			$draft_by	= $r2['create_by'];
			if( $draft_by == 0 || empty($draft_by) ){
				$draft_by	= $r2['user_id'];
			}
			
		}
		$msg = '<br>Vendor Name : '.$vendor_name ."<BR>";

		$sql = " select * from sma_user where id = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$to_approver		= $r2['id'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		
		if($mode=='Approve'){
			$status = 'Approved';
			
		
			$status_field_from ='';
			$decision_status	= '';
			if( $approver_1== $approver ){
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
		}
		else if($mode=='Reject'){
			$status = 'Rejected';
			$party_kyc='N';
		}
		
		if($mode=='Reject'){
			$sql = " update sma_party_mst set status = 'Draft', approval_status = '$status', approver_1 = '0', approver_2 = '0', approver_1_status= '', approver_2_status= '', current_approver = '$to_approver' , party_kyc = '$party_kyc' , kyc_dated = now() where id = '$vn_id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
//echo $sql. "<BR>";
//exit();
				$sql  = "INSERT INTO kyc_upd_log (create_by, created_on, user_id, updated_on, party_id, party_kyc, status, remarks) 
						VALUES ( '$userid' , now(), '$to_approver', now(), '$vn_id', '$party_kyc', '$status', '$remarks' )";
				
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
		}	
		else if($mode=='Approve'){
			
			$sqla = '';
			$sqlb =  '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from = 'Approved' ";
			}
			if($status== 'Submitted'){
				$status_submit    	 = 'Submitted';
			}
			else {
				$status_submit    	 = 'Completed';
				$party_kyc = 'Y';
				$sqlb = ", party_kyc = '$party_kyc' ";
			}		
			$sql = "update sma_party_mst set $status_field	= '$approval_status', status = '$status_submit', approval_status = '$status_submit', current_approver='$to_approver',  kyc_dated = now() $sqla $sqlb where id = '$vn_id'";
	//echo $sql."<BR>";		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}


				if(!empty($error)){echo $error; exit();}
			
			if($status == 'Approved' && $party_kyc == 'Y'){
				$sql = " update sma_party_mst set add_to_tally = 'R' where id = '$vn_id'";
				$query = mysqli_query($con, $sql);
			}
			
				$sql  = "INSERT INTO kyc_upd_log (create_by, created_on, user_id, updated_on, party_id, party_kyc, status, remarks) 
						VALUES ( '$userid' , now(), '$to_approver', now(), '$vn_id', '$party_kyc', '$status', '$remarks' )";
				
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
		}	
		
//echo $sql."<BR>";
//exit();		

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
	
		$baseurl1 = $baseurl.$modulePath.'vendor.php?id='.$srno.'&sub=edit';
		
//echo $user_email;
//EXIT ('EXIT HERE...');

		include "vn_mail.php";
	
			$baseurl1 = $baseurl.$modulePath.'vendor.php?id='.$srno.'&sub=list';
			echo "<script>window.location.href='$baseurl1';</script>";
		
		exit();

	}


    if(isset($_POST['sub10'])){
    
        $account_name   = $_POST['id'];
        $table_name     = $_POST['table_name'];
        $col_name       = $_POST['col_name'];
        $sql="SELECT * FROM $table_name where 1 and $col_name = '$account_name' ";
//echo $sql. "<BR>";        
		mysqli_query($con, $sql);
		$rowaffect = mysqli_affected_rows($con);
		if($rowaffect>0){
		    echo "Error: Already available ... ";
		}
		
    }	
?>

