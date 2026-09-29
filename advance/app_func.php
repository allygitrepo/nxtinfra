<!-- Select2 -->
  <link rel="stylesheet" href="https://nxtinfra-p2p.com/plugins/select2/select2.css">
<?php session_start();
    
    date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
 
	include('../dbcon.php');
	include('../baseurl.php');

     date_default_timezone_set("Asia/Kolkata");
  
  
 
    if(isset($_POST['sub1'])){
    
        $company_id  = $_POST['company_id'];
		$supplier_id = $_POST['supplier_id'];
		
?>		
        <div class="col-md-5">
			<label class="control-label">PO Ref.No.</label>
			<select class="form-control" name="po_ref_no" id="po_ref_no" onchange="getproject(this.value);" >
<?php
			
			echo '<option value=""> Select </option>';
			$sql = "select distinct(a.id), a.po_number, a.dated  
				FROM sma_purchase_order a, sma_po_items b 
					WHERE a.id = b.purchase_id and a.project = '$company_id' 
						and a.status in ( 'Completed' ) and a.to_supplier = '$supplier_id'
						AND ( ( b.quantity > b.bal_si_qty ) 
						OR ( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount ) )";				
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$our_po_ref_no 	= $r2->po_number;
				$dated 			= $r2->dated;
				$id		 		= $r2->id;
?>			
				<option value="<?= $id ?>"> <?= $our_po_ref_no. '-' . $dated ?></option>
<?php       };
			
		?>
			</select>
		</div>
<?php            
		
	}
	
	if(isset($_POST['sub2'])){
    
        $po_ref_no  = $_POST['po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$po_ref_no' ";
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			
			$approval_memo_ref	= $r1['approval_memo_ref'];
			$location 			= $r1['location'];	
			$supplier_location	= $r1['supplier_location'];
			$comp_id 			= $r1['project'];
			$trans_type			= $r1['trans_type'];
			$suplier_name		= $r1['to_supplier'];
			$department			= $r1['department'];
			$credit_days		= $r1['credit_days'];
			$total_po_amount	= $r1['total_po_amount'];
			$paid_amount		= $r1['paid_amount'];
			$bal_amount = $total_po_amount - $paid_amount;
		
		
		$sql  = " SELECT * from sma_department where id = '$department' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$department_name		= $r1['name'];	
		
		$sql  = " SELECT * from sma_location where id = '$location' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$loc_name		= $r1['loc_name'];	
		
?>

		<div class="col-md-3">
			<label class="control-label">Department</label>
			<input type="hidden" name="department_id" value ="<?= $department;?>" >
			
			<input class="form-control" readonly value ="<?= $department_name;?>" >
		</div>
			
		<div class="col-md-3">
			<label class="control-label">Location</label>
			<input type="hidden" name="location_id" value ="<?= $location;?>" >
			<input class="form-control" readonly value ="<?= $loc_name;?>" >
		</div>

<!--		<div class="col-md-2">
			<label class="control-label">&nbsp;</label>
			<input class="form-control" readonly value ="<?= '&nbsp';?>" >
		</div>-->
		
		<div class="col-md-2">
			<label class="control-label">PO Value</label>
			<input class="form-control" readonly name="total_po_amount" style="text-align:right;" value ="<?= $total_po_amount;?>" >
		</div>
		
		<div class="col-md-2">
			<label class="control-label">Advance Balance</label>
			<input class="form-control" readonly style="text-align:right;" value ="<?= $$bal_amount;?>" >
		</div>
		
<?php
	
	}	
	
	if(isset($_POST['sub3'])){
        $company_id  = $_POST['company_id'];
?>		
		<select class="form-control select2 " required id="supplier_id" name="supplier_id" onchange="getporefno(this.value);">
			<option value=""> Select... </option>	
			<?php 
			$sql = "select * from sma_party_mst where 1 and id in ( 
						SELECT distinct(a.to_supplier)  
						FROM sma_purchase_order a, sma_po_items b 
							WHERE a.id = b.purchase_id AND a.project = '$company_id' 
								AND a.status in ( 'Completed' ) 
								AND ( ( b.quantity > b.bal_si_qty ) 
								OR ( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount )) 
						)
								order by party_name ";
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo$r2['party_name'];?></option>
			<?php } ?>
		</select>						
<?php

	}
	
	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		
		$department			= $_POST['department'];
		$checker_value 		= $_POST['checker_value'];
		$av_id				= $_POST['av_id'];
		
		//$checker_value 		= 10000000;
		//$doc_type = 'AH';//Adhoc Payment
		//$doc_type = 'AD';
		
		$sql="SELECT * FROM sma_advance where id = '$av_id' ";
//echo $sql;
        $rs = mysqli_query($con, $sql);
        echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$company_id 		= $rw['company_id'];
		$department			= $rw['department'];
		$adhoc_payment		= $rw['adhoc_payment'];
		$checker_value		= $rw['advance_amount'];
		
		$doc_type = 'AD';
		if($adhoc_payment=='Y'){
			$doc_type = 'AH';
		}
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT * FROM sma_workflow 
					where 1 
				    and doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' ";	
//echo $sql. "<BR>";
		$q2 = mysqli_query($con, $sql);
		$rwcnt = mysqli_affected_rows($con);
		if(empty($rwcnt)){
			echo "Unable to Submit, There is no workflow defined for the matching criteria";
		}
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];
		$approval_role_9 = $r2['approval_role_9'];
		$approval_role_10 = $r2['approval_role_10'];
		$department_id   = $r2['department_id'];
		
// 		if($department != $department_id){
// 		    echo " Selected workflow config missing ! ";
// 		}

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
		if($approval_role_9>0){
			$row_affected = $row_affected + 1;
			$required9 = 'REQUIRED';
		}
		if($approval_role_10>0){
			$row_affected = $row_affected + 1;
			$required10 = 'REQUIRED';
		}
		
?>    
		<div class="box-footer">
						<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_1 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_1 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
										
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1 = '';
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
                                        <option value="<?php echo $rw['id']?>" <?php echo $selected1;?> ><?php echo $rw['username']; ?></option>
										<?php } ?>	
                                    </select>
							
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_2 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_2 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
							//echo $sql . "<BR>";				
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
						    	$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_3 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_3 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_4 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_4 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_5 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_5 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_6 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_6 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_7 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_7 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_8 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_8 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
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
						<?php if($approval_role_9>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 9</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											WHERE 1 and active =1 and id in ( SELECT distinct(approval_role_9) FROM sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_9 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_9" name="approver_9" <?= $required9; ?> >
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
						
						<?php if($approval_role_10>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 10</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user where 1 and active =1 and id in ( SELECT approval_role_10 FROM 
											sma_workflow 
											where 1 
											and doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_10 >0 )
											and FIND_IN_SET('$company_id', company_id )  ";
											
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_10" name="approver_10" <?= $required10; ?> >
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

		$modulePath = "advance/";
	
		$value ='';
        $av_id = $_POST['av_id'];
		$srno  = $av_id;
		if($_POST['av_id'] == ''){$av_id = '';}

		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];	
		$approver 			= $_POST['approver'];
		$statusap			= $_POST['statusap'];

		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_advance where id = '$av_id' "; 
//echo $sql."<BR>";			
//exit();
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$subject			= $r2['subject'];
		$pr_number			= $r2['pr_number'];
		$po_type			= $r2['po_type'];
		$to_supplier		= $r2['to_supplier'];
		$department_id		= $r2['department_id'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_9 		= $r2['approver_9'];
		$approver_10 		= $r2['approver_10'];
		
		$approver_1_status 		= $r2['approver_1_status'];
		$approver_2_status 		= $r2['approver_2_status'];
		$approver_3_status 		= $r2['approver_3_status'];
		$approver_4_status 		= $r2['approver_4_status'];
		$approver_5_status 		= $r2['approver_5_status'];
		$approver_6_status 		= $r2['approver_6_status'];
		$approver_7_status 		= $r2['approver_7_status'];
		$approver_8_status 		= $r2['approver_8_status'];
		$approver_9_status 		= $r2['approver_9_status'];
		$approver_10_status 	= $r2['approver_10_status'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) && $approver_1_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status == 'Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) && $approver_2_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status == 'Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) && $approver_3_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status == 'Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) && $approver_4_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status == 'Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) && $approver_5_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && $approver_6_status == 'Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) && $approver_6_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && $approver_7_status == 'Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) && $approver_7_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $approver_9;
				$status_field_from = 'approver_8_status';
				$status_field	 = 'approver_9_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_8== $approver && empty($approver_9) && $approver_8_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_9== $approver && $approver_9_status == 'Submitted'){
				$to_approver 	 = $approver_10;
				$status_field_from = 'approver_9_status';
				$status_field	 = 'approver_10_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_9== $approver && empty($approver_10) && $approver_9_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_9_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_10== $approver && $approver_10_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_10_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
		
	if($statusap=='Reject'){
		
		$approval_status	= 'Rejected';
		$status				= 'Rejected';
		$flow_flag 			= 'R';
		
		$sql = "update sma_advance set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = approver_9 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',approver_9_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$av_id' ";
//echo $sql. "<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
				
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'AV', '$av_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_advance set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now() $sqla where id = '$av_id'";
//echo $sql." ##1<BR>";		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'AV', '$av_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}

	}
	
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
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

		$modulePath = "advance/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$av_id;
		$msg = "Advance Note Note Number ".$av_id ;
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		include "av_mail.php";
		
		$baseurl1 = $baseurl."dashboard_p2p.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		//$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}
	
	if(isset($_POST['sub35'])){
		$modulePath = "advance/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$av_id		 		= $_POST['av_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page				= $_POST['page']; 		
		$baseurl .=$modulePath.'edit.php?sub=edit&id='.$av_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$av_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_purchase_order where id = '$av_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_9			= $rw['approver_9'];
		$approver_10			= $rw['approver_10'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];			
		$approver_9_status	= $rw['approver_9_status'];	
		$approver_10_status	= $rw['approver_10_status'];	
		
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
		if(!empty($approver_9_status)){
			$approver_id		= $rw['approver_9'];	
			include("comment_mail.php");
		}
		if(!empty($approver_10_status)){
			$approver_id		= $rw['approver_10'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
} 

?>
 	
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
