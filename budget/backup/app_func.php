<?php session_start();
	include('../dbcon.php');
	include('../baseurl.php');
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
					
	if(isset($_POST['sub5'])){
		
		$company_id = $_POST['company_id'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		//$comp_vertical = $r2['comp_vertical'];
		$sql = " SELECT * FROM sma_workflow 
					WHERE 1 and doc_type = 'BD' AND company_id = '$company_id' ";
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
								<div class="col-sm-1">
									<label class="control-label">&nbsp;</label>
								</div>
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"  required <?= $required1; ?> >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow 
											where 1 and doc_type = 'BD' and company_id = '$company_id' ), role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
									<select class="form-control  approver_2" name="approver_2" required <?= $required2; ?> >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_2 FROM sma_workflow 
											where 1 and doc_type = 'BD' and company_id = '$company_id' ), role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
									<select class="form-control  approver_3" name="approver_3" required <?= $required3; ?> >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_3 FROM sma_workflow 
											where 1 and doc_type = 'BD' and company_id = '$company_id' ), role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
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
		
		$userid   	= $_SESSION['usrid'];
		
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
		if( $approver_3== $approver ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status 		 = 'Completed';
			$decision_status = $approval_status;
		}
		
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
		values( 'BD', '$bd_id', '$approver', now(), 'Approved', '$to_approver', '$remarks', now() ) ";
//echo $sql. "<BR>";		
		$query = mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = " update budget_adjust set $status_field	= '$approval_status', status= '$status', approval_status = '$decision_status' $sqla  where id = '$bd_id' ";
		mysqli_query($con, $sql);

//echo $sql. "<BR>";

		$baseurl1 = $baseurl.$modulePath;
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
		values( 'BD', '$bd_id', '$approver', now(), 'Rejected', '$to_approver', '$remarks', now() ) ";
//echo $sql. "<BR>";		
		$query = mysqli_query($con, $sql);
		$error = mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sqla = '';
		
		$sql = " update budget_adjust set $status_field	= '$approval_status', status= '$status', approval_status = 'Rejected' where id = '$bd_id' ";
		mysqli_query($con, $sql);

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		
//echo $sql. "<BR>";
		
	}


    if(isset($_POST['sub10'])){
    
        $budget_name = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}

?>
						<select class="form-control" name="budget_head" id="budget_head" >
							<option value=""> Select </option>
							<option value=""> All</option>
							<?php $sql = "select distinct(budget_head)  from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['budget_head'];?>" > <?php echo $r2['budget_head'];?></option>
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
		if($_POST['id'] == ''){$id = '';}

?>
						<select class="form-control" name="budget_head" id="budget_head" >
							<option value=""> Select </option>
							<?php $sql = "select * from sma_budget where 1 and budget_name  = '$budget_name' and project = '$company_id' order by budget_head ";
							$q2 	= mysqli_query($con, $sql);
							while($r2 = mysqli_fetch_array($q2)){ ?>
						<option value="<?php echo $r2['id'];?>" > <?php echo $r2['budget_head'];?></option>
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
	
?>	




