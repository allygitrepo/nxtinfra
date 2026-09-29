<?php session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$user = $_SESSION['user'];
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
	$modulePath = "budget/budget_adjust.php?sub=list";
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="location" id="location" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$loc_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = "select * from sma_budget where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$balance_budget = $r2->balance_budget;
		$budget_head    = $r2->budget_category;
        
		
		$sql = "select * from sma_budget_category where id = '$budget_head' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_head    = $r2->category;
        
		$value ='<div class="col-md-3"><label class="control-label">Budget Head</label>';
		$value .= '<input type="text" class="form-control" id="budget_head" name="budget_head" readonly value="'.$budget_head.'" >';
		$value .= "</div>";	
		
		$value .='<div class="col-md-2"><label class="control-label">Budget Available</label>';
		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
		$value .= "</div>";	
		
		echo $value;
		
	}

  if(isset($_POST['sub3'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}

?>
				<div class="form-group">
					<label class="col-lg-2 control-label">From Cost Center Head</label>
					<div class="col-md-4">
						<select class="form-control" name="budget_id_from" id="budget_id_from" >
							<option value=""> Select </option>
							<?php $sql = "select * from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
						<?php } ?>
					</select>
					</div>
				</div>
<?php

	}
	
	
  if(isset($_POST['sub3A'])){
    
        $budget_name 	= $_POST['id'];
		$company_id 	= $_POST['company_id'];
		$dated		 	= $_POST['dated'];
		if($_POST['id'] == ''){$id = '';}
		
			$fyr		= date('Y', strtotime($dated));
			$fmth		= date('m', strtotime($dated));
			$fin_year	= '';
			if($fmth>=1 && $fmth<=3){
				$styr = $fyr - 1;
				$fin_year = $styr . '-'. $fyr;
			}
			else {
				$ltyr = $fyr + 1;
				$fin_year = $fyr . '-'. $ltyr;
			}		

 //echo $sql = "SELECT a.* FROM sma_budget_subgroup a, sma_budget b 
//							WHERE 1 AND a.id = b.budget_head AND b.budget_name = '$budget_name' 
//								AND project = '$company_id' AND account_year = '$fin_year' 
//								AND (( b.total_budget + b.adjustment_budget ) - ( b.used_budget + b.blocked_budget )) >0
//								ORDER BY budget_head ";  	
		
?>
				<div class="form-group">
					<label class="col-lg-2 control-label">From Cost Center Sub Group</label>
					<div class="col-md-4">
						<select class="form-control" name="budget_head_from" id="budget_head_from" >
							<option value=""> Select </option>
							<?php //$sql = "select * from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							//$sql = "select * from sma_budget_subgroup where 1 and budget_name  = '$budget_name' order by budget_head ";
							$sql = "SELECT a.* FROM sma_budget_subgroup a, sma_budget b 
							WHERE 1 AND a.id = b.budget_head AND a.transfer_flag !='Y' AND b.budget_name = '$budget_name' 
								AND project = '$company_id' AND account_year = '$fin_year' 
								AND (( b.total_budget + b.adjustment_budget ) - ( b.used_budget + b.blocked_budget )) >0
								ORDER BY budget_head "; 
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
						<?php } ?>
					</select>
					</div>
				</div>
<?php

	}
	
	
	
	if(isset($_POST['sub4'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		

?>
				<div class="form-group">
					<label class="col-lg-2 control-label">To Cost Center Head</label>
					<div class="col-md-4">
						<select class="form-control" name="budget_id_to" id="budget_id_to" >
							<option value=""> Select </option>
							<?php $sql = "select * from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
						<?php } ?>
					</select>
					</div>
				</div>
<?php

	}				
					

	if(isset($_POST['sub4A'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		$dated		 	= $_POST['dated'];
		if($_POST['id'] == ''){$id = '';}
		
			$fyr		= date('Y', strtotime($dated));
			$fmth		= date('m', strtotime($dated));
			$fin_year	= '';
			if($fmth>=1 && $fmth<=3){
				$styr = $fyr - 1;
				$fin_year = $styr . '-'. $fyr;
			}
			else {
				$ltyr = $fyr + 1;
				$fin_year = $fyr . '-'. $ltyr;
			}
		
//echo $sql = "SELECT a.* FROM sma_budget_subgroup a, sma_budget b 
//							WHERE 1 AND a.id = b.budget_head AND b.budget_name = '$budget_name' 
//								AND project = '$company_id' AND account_year = '$fin_year' 
//								ORDER BY budget_head "; 
								
?>
				<div class="form-group">
					<label class="col-lg-2 control-label">To Cost Center Sub Group</label>
					<div class="col-md-4">
						<select class="form-control" name="budget_head_to" id="budget_head_to" required >
							<option value=""> Select </option>
							<?php 
							//$sql = "select * from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							//$sql = "select * from sma_budget_subgroup where 1 and budget_name  = '$budget_name' order by budget_head ";
							$sql = "SELECT a.* FROM sma_budget_subgroup a, sma_budget b 
							WHERE 1 AND a.id = b.budget_head AND a.transfer_flag !='Y' AND b.budget_name = '$budget_name' 
								AND project = '$company_id' AND account_year = '$fin_year' 
								ORDER BY budget_head "; 
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
						<?php } ?>
						</select>
					</div>
				</div>
<?php

	}					
	
	if(isset($_POST['sub5'])){
		
		$company_id  = $_POST['company_id'];
		$adjust_flag = $_POST['adjust_flag'];
		$trans_type	 = $_POST['trans_type'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		//$comp_vertical = $r2['comp_vertical'];
		
		/* if($adjust_flag!='T'){
			$trans_type = 2;
		} */
		
		$sql = " SELECT * FROM sma_workflow 
					WHERE 1 and doc_type = 'BD' AND company_id = '$company_id'  ";
					//AND trans_type = '$trans_type'
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
//echo $sql. ' <<>> ' . $adjust_flag. '<<>>';		
		//if($adjust_flag=='T'){
			$approval_role_2 = $r2['approval_role_2'];
			$approval_role_3 = $r2['approval_role_3'];
			$approval_role_4 = $r2['approval_role_4'];
			$approval_role_5 = $r2['approval_role_5'];
			$approval_role_6 = $r2['approval_role_6'];
			$approval_role_7 = $r2['approval_role_7'];
			$approval_role_8 = $r2['approval_role_8'];
		//}
		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}

		if($adjust_flag=='T'){
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
			
		}
?>    
		<div class="box-footer">
								<div class="col-sm-1">
									<label class="control-label">&nbsp;</label>
								</div>
								<div class="col-sm-3">
									<label class="control-label">Approver 11</label>
								<?php	
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_1 FROM sma_workflow 
											where 1 and doc_type = 'BD' and company_id = '$company_id'  )
											order by username
											"; //
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
										
								?>		
									<select class="form-control  approver_1" name="approver_1"  required <?= $required1; ?> >
									<?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php	if( $approval_role_2> 0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
								<?php	
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_2 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_2 > 0 and company_id = '$company_id' ) order by username ";
											$rs = mysqli_query($con, $sql);
										$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_2" name="approver_2"  required <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php	} 
							if( $approval_role_3> 0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
								<?php
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_3 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_3 > 0 and company_id = '$company_id' ) order by username ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>	
									<select class="form-control  approver_3" name="approver_3"  required <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }
								if( ($adjust_flag=='T' && $approval_role_4> 0) || $approval_role_4> 0){ ?>
						
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
								<?php
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_4 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_4 > 0 and company_id = '$company_id' ) order by username ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_4" name="approver_4"  required <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php } 
						if( ($adjust_flag=='T' && $approval_role_5> 0 ) || $approval_role_5> 0){ ?>
						
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
								<?php
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_5 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_5 > 0 and company_id = '$company_id' ) order by username ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_5" name="approver_5"  required <?= $required5; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php } 
					if($adjust_flag=='T' && $approval_role_6> 0){ ?>
						
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
								<?php
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_6 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_6 > 0 and company_id = '$company_id' ) order by username ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_6" name="approver_6"  required <?= $required6; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php }
					if($adjust_flag=='T' && $approval_role_7> 0){ ?>
						
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
								<?php
									$sql = " select * from sma_user 
											where id in ( SELECT approval_role_7 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_7 > 0 and company_id = '$company_id' ) ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_7" name="approver_7"  required <?= $required7; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php }
					if($adjust_flag=='T' && $approval_role_8> 0){ ?>
						
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
								<?php
									$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_8 FROM sma_workflow 
											where 1 and doc_type = 'BD' and approval_role_8 > 0 and company_id = '$company_id' ), role ) ";
											$rs = mysqli_query($con, $sql);
											$single_user = mysqli_affected_rows($con);
											echo mysqli_error($con);
											$selected1='';
											if($single_user==1){
												$selected1 = 'SELECTED';
											}
								?>		
									<select class="form-control  approver_8" name="approver_8"  required <?= $required8; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php } ?>	
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
					<?php }
					?>
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getvalidate();getsubmit();" >
								</div>
								
							</div>
							
						</div>

					<BR>

<?php

	}

	if(isset($_POST['sub9'])){
		
		$bd_id 		= $_POST['bd_id'];
		$company_id = $_POST['company_id'];
		$approver 	= $_POST['approver'];
		$statusap   = $_POST['statusap'];
		$remarks    = $_POST['remarks'];
		$adjust_flag = $_POST['adjust_flag'];
		
		$userid   	= $_SESSION['usrid'];
		if($adjust_flag=='T'){
			$sql = " select * from budget_adjust_from_to where id = '$bd_id' ";
	
			$q2	=	mysqli_query($con, $sql);
			$r2 =	mysqli_fetch_array($q2);
			$budget_id_from		= $r2['budget_id_from'];
			$budget_id_to		= $r2['budget_id_to'];
//			$effect 			= $r2['effect'];
			$amount				= $r2['amount'];
			$project			= $r2['project'];
			$fin_year			= $r2['account_year'];
			$doc_type  = 'BT';
		}
		else {
			$sql = " select * from budget_adjust where id = '$bd_id' ";
	//echo $sql."<BR>"; 
			$q2	=	mysqli_query($con, $sql);
			$r2 =	mysqli_fetch_array($q2);
			$budget_name		= $r2['budget_name'];
			$budget_head		= $r2['budget_head'];
			$budget_code		= $r2['budget_code'];
			$budget_id			= $r2['budget_id'];
			$effect 			= $r2['effect'];
			$amount				= $r2['amount'];
			$project			= $r2['project'];
			$fin_year			= $r2['fin_year'];
			$doc_type  = 'BD';
		}
		
		$draft_by 				= $r2['draft_by'];
		$approver_1 			= $r2['approver_1'];
		$approver_1_status 		= $r2['approver_1_status'];
		$approver_2 			= $r2['approver_2'];
		$approver_3 			= $r2['approver_3'];
		$approver_4 			= $r2['approver_4'];
		$approver_2_status 		= $r2['approver_2_status'];
		$approver_3_status 		= $r2['approver_3_status'];
		$approver_4_status 		= $r2['approver_4_status'];
		
		if($adjust_flag != 'T'){
			$sql = " SELECT * from sma_budget where  project = '$project' 
								and	budget_name		= '$budget_name'
								and	budget_head		= '$budget_head'
								and account_year	= '$fin_year' ";
			$query= mysqli_query($con, $sql);	
			$r2 =	mysqli_fetch_array($query);			
			$budget_id  = $r2['id'];
		}
//echo $sql. "<BR>";
			
		if(empty($draft_by)){
			$draft_by = 'Admin';
		}
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		
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
			
		if($adjust_flag=='T' && $status == 'Completed' ){
			$sql = " update sma_budget set adjustment_budget = adjustment_budget + $amount 
							where id = '$budget_id_to' ";
			mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
							
			$sql = " update sma_budget set adjustment_budget = adjustment_budget - $amount 
							where id = '$budget_id_from' ";				
			mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
							
		}
		else if( $status == 'Completed' ){
			if($effect=='I'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget + $amount 
							where id = '$budget_id' ";
			}
			else if ($effect=='D'){
				$sql = " update sma_budget set adjustment_budget = adjustment_budget - $amount 
							where id = '$budget_id' ";
			}
			mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
		}	
//echo $sql. "<BR>";			
			
		$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
		values( '$doc_type', '$bd_id', '$approver', now(), 'Approved', '$to_approver', '$remarks', now() ) ";
//echo $sql. "<BR>";		
		$query = mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		
		if($adjust_flag=='T' ){
			$sql = " UPDATE budget_adjust_from_to  SET $status_field	= '$approval_status', status= '$status', approval_status = '$decision_status', 
			changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla  where id = '$bd_id' ";
			mysqli_query($con, $sql);
//echo $sql. "<BR>";			
			echo $error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		}	
		else if($adjust_flag!='T'){
			$sql = " UPDATE budget_adjust SET $status_field	= '$approval_status', status= '$status', approval_status = '$decision_status',  current_approver = '$to_approver', changed_date = now() $sqla  where id = '$bd_id' ";
			mysqli_query($con, $sql);
		}	
//echo $sql. "<BR>";
//exit();
		if($adjust_flag=='T' ){
			$modulePath = "budget/budget_adjust_from_to.php?sub=list";
			$baseurl1 = $baseurl.$modulePath;
		}	
		else {
			$baseurl1 = $baseurl.$modulePath;
		}
		echo "<script>window.location.href='$baseurl1';</script>";
		
	}

	if(isset($_POST['sub8'])){
		
		$bd_id 		= $_POST['bd_id'];
		$company_id = $_POST['company_id'];
		$statusap   = $_POST['statusap'];
		$remarks    = $_POST['remarks'];
		
		$userid   	= $_SESSION['usrid'];
		$approver	= $userid;
		
		$sql = " select * from budget_adjust where id = '$bd_id' ";
//echo $sql."<BR>";
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_1_status 		= $r2['approver_1_status'];
		$approver_2_status 		= $r2['approver_2_status'];
		$approver_3_status 		= $r2['approver_3_status'];
		
		if(empty($draft_by)){
			$draft_by  = 'Admin';
		}
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";	
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$to_approver 	    = $draft_by_id;
		
		$status_field_from ='';
		if( $approver_1== $approver ){
			$status_field	 = 'approver_1_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$status_field	 = 'approver_1_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_2== $approver ){
			$status_field	 = 'approver_2_status';
			$approval_status = '';
			$status      	 = 'Draft';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$status_field	 = 'approver_2_status';
			$approval_status = '';
			$status      	 = 'Draft';
		}
		if( $approver_3== $approver ){
			$status_field	 = 'approver_3_status';
			$approval_status = '';
			$status      	 = 'Draft';
		}
		
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
		values( 'BD', '$bd_id', '$approver', now(), 'Rejected', '$to_approver', '$remarks', now() ) ";
//echo $sql. "<BR>";		
		$query = mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sqla = '';
		
		$sql = " update budget_adjust set  status= '$status', approval_status = 'Rejected' where id = '$bd_id' ";
		mysqli_query($con, $sql);
		
		$sql = " update budget_adjust set  approver_1 = '',  approver_2 = '',  approver_3= '', approver_1_status= '',  approver_2_status= '',  approver_3_status= ''  where id = '$bd_id' ";
		mysqli_query($con, $sql);
		
//echo $sql. "<BR>";
//exit();

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		
//echo $sql. "<BR>";
		
	}


    if(isset($_POST['sub10'])){
    
        $budget_name = $_POST['id'];
		$company_id  = $_POST['company_id'];
		$account_year = $_POST['account_year'];
		if($_POST['id'] == ''){$id = '';}
		
		if(!empty($budget_name)){
			$sqla = " AND budget_name = '$budget_name' ";
			
		}
		
		$sqlb = " AND account_year = '$account_year' ";
		
		$sql = " SELECT * from sma_budget_subgroup where 1 $sqla 
					AND id in ( SELECT distinct(budget_head) FROM sma_budget WHERE 1 $sqlb $sqla AND project = '$company_id' ) order by budget_head ";

?>
		<select class="form-control" name="budget_head" id="budget_head" onchange="getcostcentercode(this.value);" >
			<option value=""> Select </option>
			<option value=""> All</option>
			<?php //$sql = "select * from sma_budget_subgroup where 1 and budget_name = '$budget_name' order by budget_head "; //and project = '$company_id'
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
			<?php } ?>
		</select>
<?php
		exit();
		
	}
	
?>

<?php
   if(isset($_POST['sub11'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		$account_year = $_POST['account_year'];
		if($_POST['id'] == ''){$id = '';}

		if(!empty($budget_name)){
			$sqla = " AND budget_name = '$budget_name' ";
			
		}
		
		$sqlb = " AND account_year = '$account_year' ";
		
		$sql = " SELECT * from sma_budget_subgroup where 1 $sqla 
					AND id in ( SELECT distinct(budget_head) FROM sma_budget WHERE 1 $sqlb $sqla AND project = '$company_id' ) order by budget_head ";
					
?>
		<select class="form-control" name="budget_head" id="budget_head" >
			<option value=""> Select </option>
			<option value=""> All </option>
			<?php 
			
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head']. ' ' . $r2['budget_code'];?></option>
			<?php } ?>
		</select>
					
<?php
		exit();
		
	}

	if(isset($_POST['sub12'])){
    
        $company_id = $_POST['company_id'];
		
//echo $sql = "select distinct(b.name), b.id from sma_budget a , sma_budget_name b where 1 and b.id = a.budget_name and a.project = '$company_id' order by b.name ";		
		
?>
		<select class="form-control" name="budget_name" id="budget_name" onchange="getcostcentergroup(this.value);" >
			<option value=""> Select </option>
			<option value="" > All </option>
			<?php $sql = "select distinct(b.name), b.id from sma_budget a , sma_budget_name b where 1 and b.id = a.budget_name and a.project = '$company_id' order by b.name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['name'];?></option>
			<?php } ?>
			</select>				
<?php
		exit();
		
	}
	
if(isset($_POST['sub13'])){
    
        $budget_head 	= $_POST['id'];
		$budget_name 	= $_POST['budget_name'];
		$company_id 	= $_POST['company_id'];
		$fin_year		= $_POST['fin_year'];
		if($_POST['id'] == ''){$id = '';}
		
		$sql  	= "select * from sma_budget where 1 and budget_name = '$budget_name' and project = '$company_id' and budget_head = '$budget_head' and account_year = '$fin_year' ";
//echo $sql;		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_code 	= $r2['budget_code'];
		$budget_id 		= $r2['id'];
?>		
		<input type="text" class="form-control" name='budget_code' readonly value="<?php echo $budget_code;?>" >
		<input type="hidden" class="form-control" name='budget_id' readonly value="<?php echo $budget_id;?>" >
<?php	
	}

if(isset($_POST['sub13a'])){
    
        $budget_head 	= $_POST['id'];
		$budget_name 	= $_POST['budget_name'];
		$company_id 	= $_POST['company_id'];
		$fin_year		= $_POST['fin_year'];
		if($_POST['id'] == ''){$id = '';}
		
		$sql  	= "select * from sma_budget where 1 and budget_name = '$budget_name' and project = '$company_id' and budget_head = '$budget_head' and account_year ='$fin_year' ";
//echo $sql;		
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$budget_code 		= $r2['budget_code'];
		$total_budget 		= $r2['total_budget'];
		$used_budget 		= $r2['used_budget'];
		$blocked_budget 	= $r2['blocked_budget'];
		$adjustment_budget 	= $r2['adjustment_budget'];
		$bal_budget			= $total_budget + $adjustment_budget - ($used_budget + $blocked_budget) ;
		$total_budget 		+= $adjustment_budget;
		
?>		
		<label class="col-lg-2 control-label">Total Budget</label>
		<div class="col-md-2">
		<input type="text" class="form-control" readonly value="<?= $total_budget;?>" >
		</div>
		<label class="col-lg-2 control-label">Balance Budget</label>
		<div class="col-md-2">
		<input type="text" class="form-control" readonly value="<?= $bal_budget;?>" >
		</div>					
<?php	
	}	

if(isset($_POST['sub14'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		$sqla = '';

		$sql = "SELECT * FROM sma_budget_name WHERE 1 and id in (select budget_name from sma_budget where project = '$company_id' and account_year = '2022-2023') ORDER BY `name` ";
		
?>
		<select class="form-control" name="budget_name" id="budget_name" onchange="getccgroup(this.value);" >
			<option value=""> Select </option>
			<option value=""> All </option>
			 
			<?php 
			$q2 = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
			<?php 
			} 
			?>
		</select>
		
<?php
		exit();
		
}

if(isset($_POST['sub14a'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		$sqla = '';

		$sql = "SELECT * FROM sma_budget_subgroup WHERE 1 and budget_name = '$budget_name' ORDER BY `budget_head` ";
		
?>
		<select class="form-control" name="budget_head" id="budget_head" onchange="getcccode(this.value);" >
			<option value=""> Select </option>
			<option value=""> All </option>
			 
			<?php 
			$q2 = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
			<?php 
			} 
			?>
		</select>
		
<?php
		exit();
		
}

if(isset($_POST['sub14b'])){
    
        $budget_head = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		$sqla = '';

		$sql = "SELECT * FROM sma_budget_subgroup WHERE 1 and id = '$budget_head' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$budget_code = $r2['budget_code'];
?>
		
		<input type="text" class="form-control" readonly id="budget_code" name="budget_code"  placeholder="" value="<?php echo $budget_code;?>" >
		
<?php
		exit();
		
}

   if(isset($_POST['sub15'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
?>
		
		<select class="form-control" name="location" id="location" required onchange="getccname(this.value); " >
			<option value=""> Select</option>
			<option value=""> All</option>
			<?php $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['loc_name'];?></option>
			<?php } ?>
		</select>
<?php

    }
	
	 if(isset($_POST['sub16'])){
    
        $short_fy_code = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$sql = " select * from sma_financial_year WHERE 1 and short_fy_code = '$short_fy_code' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$from_date 		= date('d-m-Y', strtotime($r2['from_date']));
		$to_date 		= date('d-m-Y', strtotime($r2['to_date']));
										
?>	

								<div class="col-md-2">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date" placeholder="" value="<?php echo $from_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
								
								<div class="col-md-2">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" placeholder="" value="<?php echo $to_date;; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
			
<?php

    }

	if(isset($_POST['sub8A'])){
		
		$bd_id 		= $_POST['bd_id'];
		$company_id = $_POST['company_id'];
		$statusap   = $_POST['statusap'];
		$remarks    = $_POST['remarks'];
		
		$userid   	= $_SESSION['usrid'];
		$approver	= $userid;
		
		$sql = " select * from budget_adjust_from_to where id = '$bd_id' ";
//echo $sql."<BR>";
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_1_status 		= $r2['approver_1_status'];
		$approver_2_status 		= $r2['approver_2_status'];
		$approver_3_status 		= $r2['approver_3_status'];
		
		if(empty($draft_by)){
			$draft_by  = 'Admin';
		}
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";	
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$to_approver 	    = $draft_by_id;
		
		$status_field_from ='';
		if( $approver_1== $approver ){
			$status_field	 = 'approver_1_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$status_field	 = 'approver_1_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_2== $approver ){
			$status_field	 = 'approver_2_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$status_field	 = 'approver_2_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		if( $approver_3== $approver ){
			$status_field	 = 'approver_3_status';
			$approval_status = 'Rejected';
			$status      	 = 'Draft';
		}
		
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
		values( 'BT', '$bd_id', '$approver', now(), 'Rejected', '$to_approver', '$remarks', now() ) ";
//echo $sql. "<BR>";		
		$query = mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sqla = '';
		
		$sql = " update budget_adjust_from_to set $status_field	= '$approval_status', status= '$status', approval_status = 'Rejected' ,
		approver_1_status = '',approver_2_status = '',approver_3_status = '',approver_4_status = '',approver_5_status = '',approver_6_status = '',approver_7_status = '',approver_8_status = '', approver_1 = '0', approver_2 = '0',approver_3 = '0',approver_4 = '0',approver_5 = '0',approver_6 = '0',approver_7 = '0',approver_8 = '0',current_approver = '$draft_by_id'
		where id = '$bd_id' ";
		mysqli_query($con, $sql);
//echo $sql. "<BR>";
//exit();

		$baseurl1 = $baseurl.$modulePath."budget_adjust_from_to.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
//echo $sql. "<BR>";
		
	}


    if(isset($_POST['sub6'])){
    
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

	