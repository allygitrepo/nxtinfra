<?php session_start();
	include('../dbcon.php');
	//include('function.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="sub_group" id="sub_group" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_product_subgroup where group_code = '$id' order by description";
		$q2  = mysqli_query($con, $sql);
		while($row = mysqli_fetch_object($q2)){ 
			$description = $row->description;
            $id = $row->id;
            $value .= "<option value='".$id."'>".$description."</option>";
        };
		$value .= '</select>';
//		$value = $sql;
        echo $value;
    }
	
	
	
	
    if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$sql = "select a.id, a.description, b.name as budget_name, c.category as budget_head from sma_product_group a, sma_budget_name b, sma_budget_category c where a.budget_name = b.id and a.budget_head = c.id and  a.id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$row = mysqli_fetch_object($q2);
			$description = $row->description;
			$budget_name = $row->budget_name;
			$budget_head = $row->budget_head;
            $id = $row->id;
		//$value = $sql;
        //echo $value;
?>
		<div class="form-group">
			<label class="col-lg-2 control-label">Budget Name</label>
			<div class="col-md-3">
				<input type="text" class="form-control" readonly value="<?php echo $budget_name ?>" >
			</div>
			
			<label class="col-lg-2 control-label">Budget Head</label>
			<div class="col-md-3">
				<input type="text" class="form-control" readonly value="<?php echo $budget_head ?>" >
			</div>
		</div>

<?php
    }
	
	if(isset($_POST['sub24'])){

		/* $company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		$po_type	 		= $_POST['po_type']; */
//echo $po_type;		
		$doc_type = 'PD';
		$company_id = '6';
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and company_id = '$company_id' ";	//and trans_type = '$trans_type'
					
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
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_1 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_2 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_2 >0 ), role ) 
											
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_3 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_4 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_5 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_6 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_7 FROM sma_workflow a , sma_workflow_type b 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_8 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
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
	
?>

