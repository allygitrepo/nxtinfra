<?php 
	session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');

	$modulePath = "goods_issue_note/goods_issue_note.php?sub=list";

	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}

	$userid   			= $_SESSION['usrid'];		
	
?>

<?php
	if(isset($_POST['sub1'])){
    
        $company_id = $_POST['id'];
	
		$value = '';
		$value ='<select class="form-control" id="against_mrn_no" name="against_mrn_no"  required="true" onchange="getmrnMaker(this.value)"  >
					<option value=""> Select </option>';
							
	   // $sql = "select * from sma_purchase_req where company_id = '$id' order by id ";
		$sql = " SELECT distinct(b.pr_number), b.id FROM sma_purchase_req_items a, sma_purchase_req b , sma_product c
					WHERE 1 and b.del !='Y' and b.id = a.purchase_req_id and quantity > gin_qty and c.id = a.product_id and c.category = 'M' and company_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$pr_number 	= $r2->pr_number;
            $id 		= $r2->id;
            $value .= "<option value='".$pr_number."'>".$pr_number."</option>";
        };
		$value .= '</select>';

		//$value=$sql;

        echo $value;
		
    }
	
	if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
 	    $sql = "select * from sma_purchase_req where pr_number = '$id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$draft_by = $r2->draft_by;
			$subject  = $r2->subject;
        };
    
 		$sql = "select * from sma_user where userid = '$draft_by' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$usrid = $r2->id;
			$username = $r2->username;
        };
?>

	<label class="col-lg-2 control-label">Maker of PR </label>
		<div class="col-md-2">
		<input type="hidden" class="form-control" id="maker_mrn_id" name="maker_mrn_id" value="<?php echo $usrid;?>" >

		<input type="text" class="form-control" <?php echo $readonly;?> value="<?php echo $username;?>" readonly  >
	</div>
							
	<label class="col-lg-1 control-label">Subject</label>
	<div class="col-md-3">
		<input type="text" class="form-control" value="<?php echo $subject;?>" readonly  >
	</div>
<?php		
		
	}

	if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" id="issue_location" name="issue_location" required="true" "  >
					<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$loc_name."'>".$loc_name."</option>";
        };
		$value .= '</select>';

		//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub3A'])){
        $id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
				
		$value = '';	
?>
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit2(this.value);getdupprd(this.value);" >
			<option value=""> Select..</option>
<?php

			//$sql = "SELECT * from sma_product where 1 and product_group = '$id' order by name";
			$sql = "select * from sma_product where 1 and product_group = '$id' and id in ( SELECT product_name from sma_product_open_stock where (opening_stock + receipts - issue)>0 )";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>		
<?php
    }

	if(isset($_POST['sub4A'])){
        $id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
				
		$value = '';	
?>
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit2(this.value);getdupprd(this.value);" >
			<option value=""> Select..</option>
<?php

			$sql = "select * from sma_product where 1 and category = 'M' and product_group = '$id' order by name ";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>		
<?php
    }
	
	if(isset($_POST['sub4'])){
    
        $product_id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		//$value ='<select class="form-control" name="itemunits" id="itemUnits" readonly >
		//							<option value=""> Select </option>';
		
		$sql = " SELECT * from sma_product_open_stock where product_name = '$product_id' and project = '$company_id'";
		$q2  = mysqli_query($con, $sql);

		$r2 = mysqli_fetch_object($q2);
		$close_stock 	= ($r2->opening_stock + $r2->receipts) - $r2->issue;
		
	    $sql = "SELECT * FROM sma_product where id = '$product_id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);

//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			$r2 = mysqli_fetch_object($q2);
			$uom 			= $r2->uom;
			
?>
        <div class="col-sm-4 col-md-4">
			<label for="itemUnits" class="control-label">Unit of Measurement</label>   
			<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "<?= $uom; ?>" >
		</div>
		
		<div class="col-sm-4 col-md-4">
			<label for="itemUnits" class="control-label">Closing Stock</label>
			<input type="text" class="form-control closeStock" id="closeStock" readonly value = "<?= $close_stock; ?>" >
		</div>
		
<?php		
//$value.=$sql;
        //echo $value;
    }

	if(isset($_POST['sub11'])){
	
		$value ='';
        
		$product_id     	= $_POST['product_id'];
		$ginid			 	= $_POST['ginid'];
		
		$description 		= $_POST['description'];
		$quantity 			= $_POST['quantity'];
		$units 				= $_POST['units'];
		
		$sql = "SELECT * from sma_goods_issue_note where id = '$ginid' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$issue_against 	= $r2['issue_against'];
		$company_id 	= $r2['company_id'];	
	
		if($quantity > 0){
			$sql = "SELECT * from sma_goods_issue_note_items where gin_hdr_id = '$ginid' and product_id = '$product_id' ";
			$q2  = mysqli_query($con, $sql);
			$rowaffected = mysqli_affected_rows($con);
			
			$r2  = mysqli_fetch_array($q2);
			if($rowaffected == 0){
				$sql = "INSERT INTO `sma_goods_issue_note_items` (product_id, gin_hdr_id, specification, issue_qty, units) 
					values ( '$product_id', '$ginid', '$description', '$quantity', '$units')";
				mysqli_query($con, $sql);
				
			}
		}
		
		if( $issue_against=='O' ){
			$sql = "UPDATE sma_product_open_stock set issue = issue + $quantity where product_name = '$product_id' and project = '$company_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		
		$value = "<script>window.location.href='goods_issue_note.php?sub=edit&id=$ginid&active=active&999';</script>";
		echo $value;
		
	}

if(isset($_POST['sub12'])){

	$against_mrn_no = $_POST['id'];
	
?>
		
	<div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h4 class="box-title">Product Details</h4>
                                        
            </div>
							   
			<div class="box-body">
            <table id="prItemsTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Specification</th>
					<th>Unit of Measurement</th>
					<th style="text-align:right;">Issued Qty.</th>
					<th>Action</th>
                </tr>
                </thead>
                <tbody id="prItemsTableBody">
<?php	
												
				$error_flag = '';											
		 		$sql = "SELECT a.id, product_id, description, unit, (quantity - gin_qty) as qty, (quantity - gin_qty) as qty , b.company_id
						FROM sma_purchase_req_items a, sma_purchase_req b 
						WHERE b.id = a.purchase_req_id and quantity > gin_qty and b.pr_number = '$against_mrn_no' ";
				$res = mysqli_query($con, $sql);
				$itemcnt = mysqli_affected_rows($con);
				echo mysqli_error($con);
				$value="";
				while($r3 = mysqli_fetch_array($res)){
					$company_id		= $r3['company_id'];
					$product_id		= $r3['product_id'];
					$specification	= $r3['description'];
					$qty	 		= $r3['qty'];
					$units		 	= $r3['unit'];
												
					$sql = "select * from sma_product where id = '$product_id'";
					$r2 = mysqli_query($con, $sql);
					$r1 = mysqli_fetch_array($r2);
					$product_name = $r1['name'];
											
					$sql = "SELECT * FROM sma_product_open_stock where product_name = '$product_id' and project = '$company_id' ";
	//echo $sql. "<BR>";//opening_stock + receipts ) >= issue
					$r2 = mysqli_query($con, $sql);
					$r1 = mysqli_fetch_array($r2);
					$rowaffected = mysqli_affected_rows($con);
					echo mysqli_error($con);
					$opening_stock		= $r1['opening_stock'];
					$receipts			= $r1['receipts'];
					$issue				= $r1['issue'];
					$pr_qty				= ( $opening_stock + $receipts ) - $issue;

					$error_v = '';
//echo $pr_qty . ' ' . $qty. "<BR>";					
					/* if($pr_qty < $qty){
						$error_v = "Error: No stock to Issue!" . $pr_qty . ' < ' . $qty;
						$error_flag = 'Y';
					} */
					
					/* if($receipts < $qty){
						$error_v = "Error: No stock to Issue!" . $pr_qty . ' < ' . $qty;
						$error_flag = 'Y';
					} */
					
?>	
				<tr>
				<td width='15%'><?= $product_name?></td>
				<td width='15%'><?= $specification?></td>	
				<td width='8%'><?= $units?></td>
				<td width='8%' style="text-align:right;"><?= $qty;?></td>
				<td width='6%' style="color:red;"><?= $error_v;?></td>
				</tr>
<?php
			}
?>		

                </tbody>
            </table>
                                 
			</div>
		</div>	
	</div>
	
<?php 	if(empty($error_flag)){ ?>
			<div class="box-footer">
				<div class="col-sm-6">
				</div>
				<?php $baseurl1 = $baseurl.$modulePath;?>
				<div class="col-sm-6 text-right">
					<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
					<span>&nbsp;&nbsp;</span>
					<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
				</div>
			</div>
<?php 	} ?>
						
<?php
}
	
if(isset($_POST['sub13'])){
		$product_group = $_POST['id'];
		$company_id = $_POST['company_id'];
		
		//$sql = "select * from sma_product where 1 and `product_group` = '$product_group' order by trim(name) ";
		//select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$project' 
		$sql = "select * from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$company_id'  ) and `product_group` = '$product_group' order by trim(name) ";
?>		
		<select class="form-control" name="product_name" id="product_name" required >
			<option value=""> Select </option>
			<option value=""> All </option>
			<?php 
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
			<?php } ?>
		</select>

<?php 		
}


if(isset($_POST['sub14'])){
		$company_id = $_POST['company_id'];
?>		
		<select class="form-control" required name="product_group" id="product_group" onchange="getproduct_group(this.value);" >
			<option value=""> Select </option>
			<option value="" <?php echo ($product_group == '')?'selected="selected"':'';?>> All </option>
				<?php $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$company_id' ) order by name ) order by product_group ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['product_group'];?></option>
				<?php } ?>
		</select>
<?php 
}

if(isset($_POST['sub15'])){
		$rid 			= $_POST['id'];
		$audit_status 	= $_POST['audit_status'];
		
		$sql = " UPDATE audit_job_details SET audit_status = '$audit_status' WHERE id = '$rid' ";
		mysqli_query($con, $sql);
		
		exit();
}
	

if(isset($_POST['sub16'])){
		$tvalue 			= $_POST['tvalue'];
		$rid	 			= $_POST['rid'];
		
		$sql = " UPDATE sma_goods_receipt_note_items SET total_value = '$tvalue' WHERE id = '$rid' ";
		mysqli_query($con, $sql);
		
		exit();
		
}

	
	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		//$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		//$po_type	 		= $_POST['po_type'];
		//$department	 		= $_POST['department'];
		
		$doc_type 			= $_POST['doc_type'];
//echo $po_type;	
		if($doc_type=='GN'){
			$doc_type =='GN';
		}
		else {
			$doc_type = 'GI';
		}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and company_id = '$company_id' 
					and trans_type = '$trans_type'";	
//echo $sql. "<BR>"; //and '$checker_value' >= from_value and '$checker_value' <= to_value 
					
		$q2 = mysqli_query($con, $sql);
		$rowaffect = mysqli_affected_rows($con);
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
						<?php
							if($rowaffect==1){
						?>		
								<div class="col-sm-3">
									<label class="control-label">&nbsp; <span style="color:red;">**</span></label>
								</div>	
						<?php	
							}
						?>
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_1) FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_1 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id ) ";
										//and department = '$department' // and '$checker_value' >= from_value 	and '$checker_value' <= to_value 
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
											and a.doc_type = '$doc_type' 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
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
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_4) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_5) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_6) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_7) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_8) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' 
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

if(isset($_POST['sub7'])){
    
		$product_group = $_POST['id'];
		
		$sql = "select * from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock ) and `product_group` = '$product_group' order by name ";
?>		
		<select class="form-control" name="product_name" id="product_name" required >
			<option value=""> Select </option>
			<?php 
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
			<?php } ?>
		</select>
<?php 
    }
	
	if(isset($_POST['sub25'])){
		$company_id = $_POST['id'];
?>
		
		<select class="form-control select2" id='sterms' onchange="getspecialterms(this.value);" >
			<option value=""> Select </option>
			<?php $sql = "select * from sma_term where company_id = '$company_id' ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" ><?php echo $r2['special_terms'];?></option>
			<?php } ?>
		</select>

<?php
	}


	if(isset($_POST['sub9'])){

		$modulePath = "goods_issue_note/"; 
	
		$value ='';
        $gin_hdr_id = $_POST['gin_hdr_id'];
		$srno  = $gin_hdr_id;
		if($_POST['gin_hdr_id'] == ''){$gin_hdr_id = '';}
		$value ='';
        $gin_hdr_id 			= $_POST['gin_hdr_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_goods_issue_note where id = '$gin_hdr_id' "; 		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
//echo $sql."<BR>";		
		$subject 			= $r2['remarks'];
		$company_id 		= $r2['company_id'];
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
		$current_approver	= $r2['current_approver'];

//echo $statusap. " <> <BR>";

		if($statusap=='Reject'){
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_username		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			//$userid		 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$to_approver		= $draft_by_id;
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
			
			$sql = "UPDATE sma_goods_issue_note SET 
			approver_1 = '', approver_2 = '', approver_3 = '',approver_4 = '',
			approver_5 = '', approver_6 = '', approver_7 = '',approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() WHERE id = '$gin_hdr_id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			if( empty($userid) ){
				$userid = $current_approver;
			}
			
			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'GI', '$gin_hdr_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
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
			
//echo $sql."<BR>";
//echo $draft_by_id."<BR>";
	
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
//	echo ' #### 2 ### ' . $to_approver. "<BR>";		
	//echo $to_approver. ' ' .$approval_status."<BR>"; 
	//exit();
	if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			$status_field_from_v = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from 	= 'Approved' ";
				$status_field_from_v 			= 'Approved';
			}
			$sql = " update sma_goods_issue_note set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$gin_hdr_id'";
	//echo $sql. "<BR>"; exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values('GI', '$gin_hdr_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
	//echo $sql. "<BR>";		
			
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
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	}

//exit('RAVINDRA Exit HERE...');
		
		$modulePath = "goods_issue_note/";
		
		$baseurl1 = $baseurl.$modulePath.'goods_issue_note.php?sub=edit&id='.$gin_hdr_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$msg = 'Goods Issue Notes Number : '.$gin_hdr_id . ' ' . 'Dated : ' . date("d-m-Y");
		
		include "gin_mail.php";
		
		$role		= $_SESSION['role'];
//echo $role;		
//exit("RAVINDRA STOPED...");

		if(!empty($status_field_from || $approval_status = 'Approved' || $approval_status = 'Rejected' )){
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=A";
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		else {	
			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		exit();
		
	}
	
	if(isset($_POST['sub10'])){
		
    	$audit_job_name = trim($_POST['audit_job_name']);
		$company_id 	= $_POST['company_id'];
		$product_group  = $_POST['product_group'];
		/* $product_name  	= $_POST['product_name'];
		$category  		= $_POST['category']; */
		
		$sql = " INSERT INTO audit_job_header( audit_job_name , company_id, created_date, created_by ) VALUES( '$audit_job_name', '$company_id', now(), '$userid' ) ";
		mysqli_query($con, $sql);
//echo $sql."<BR>";
	
		$audit_job_hdr_id = mysqli_insert_id($con);
		if($audit_job_hdr_id>0){
			
			$sql="SELECT * from sma_product_open_stock where 1 "; //project in ($comid)
	
			if(!empty($company_id)){
				$sql .= " and project = '$company_id' ";
			}
			
			/* if(!empty($product_name)){
				$sql .= " and product_name = '$product_name' ";
			}
			
			if($category=='M' || $category =='S'){
				$sql .= " and product_name in (select id from sma_product where category = '$category')";
			} */
			
	//	echo $sql."<BR>";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			while($row = mysqli_fetch_array($result)){
			
				$project = $row['project'];
				$sql = "SELECT * from company where comp_id = '$project' ";
				$res = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($res);
				$comp_name 	= $r2['comp_name'];
				$project 	= $r2['comp_code'];

				$product_id 	= $row['product_name'];
				/* 
				$sql 	= "select * from sma_product where id = '$product_name' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$product_name 	= $r2['name'];
				$unit 			= $r2['uom'];
				 */
				
				$opening_stock 	= $row['opening_stock'];
				$receipts 		= $row['receipts'];
				$issue			= $row['issue'];
			
				$closing_stock = ( $opening_stock + $receipts ) - ( $issue );
			
				$sql = " INSERT INTO audit_job_details( audit_job_hdr_id, product_id, closing_stock, physical_stock ) 
							VALUES( '$audit_job_hdr_id', '$product_id', '$closing_stock', '0' ) ";
				mysqli_query($con, $sql);

	//echo $sql. "<BR>";

			}
		
			$baseurl1 = $baseurl.'goods_issue_note/'."manage_audit_job_list.php?sub=list"; //&audit_job_hdr_id=$audit_job_hdr_id
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
			
		}
		else {
			echo "Audit Name already available....";
		}	
		
	}
	
	
	if(isset($_POST['sub11G'])){
	
		$value ='';
        
		$product_id     	= $_POST['product_id'];
		$grn_Id			 	= $_POST['grn_Id'];
		
		$description 		= $_POST['description'];
		$quantity 			= $_POST['quantity'];
		$rate 				= $_POST['rate'];
		$units 				= $_POST['units'];
		
		$sql = "SELECT * from sma_goods_issue_note where id = '$grn_Id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$issue_against 	= $r2['issue_against'];
		$company_id 	= $r2['company_id'];
//echo $sql. "<BR>";	
		if($quantity > 0){
			$sql = "SELECT * from sma_goods_receipt_note_items where grn_hdr_id = '$grn_Id' and product_id = '$product_id' ";
			$q2  = mysqli_query($con, $sql);
			$rowaffected = mysqli_affected_rows($con);
			$r2  = mysqli_fetch_array($q2);
			if($rowaffected == 0){
				$sql = "INSERT INTO `sma_goods_receipt_note_items` (product_id, grn_hdr_id, specification, receipt_qty, rate, units) 
					values ( '$product_id', '$grn_Id', '$description', '$quantity', '$rate', '$units')";
				mysqli_query($con, $sql);
			}
		}
//echo $sql. "<BR>";	
		/* if($quantity > 0){
			$sql ="UPDATE sma_product_open_stock set receipts = receipts + $quantity where product_name = '$product_id' and project = '$company_id' ";
			mysqli_query($con, $sql);
			$rowaffected = mysqli_affected_rows($con);
			echo mysqli_error($con);
			if($rowaffected == 0){
				$sql = "INSERT INTO `sma_product_open_stock` (product_name, project, receipts, uom, created_date, remarkv, rate) 
					VALUES ( '$product_id', '$company_id', '$quantity', '$units', now(), 'Inserted', '$rate' )";
				mysqli_query($con, $sql);
			}
		} */
//echo $sql. "<BR>";
//exit();		
		$value = "<script>window.location.href='goods_receipt_note.php?sub=edit&id=$grn_Id&active=active&999';</script>";
		echo $value;
		
	}

	if(isset($_POST['sub99'])){

		$modulePath = "goods_issue_note/"; 
	
		$value ='';
        $grn_hdr_id = $_POST['grn_hdr_id'];
		$srno  = $grn_hdr_id;
		if($_POST['grn_hdr_id'] == ''){$grn_hdr_id = '';}
		$value ='';
        $grn_hdr_id 			= $_POST['grn_hdr_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_goods_receipt_note where id = '$grn_hdr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
//echo $sql."<BR>";		
		$subject 			= $r2['remarks'];
		$company_id 		= $r2['company_id'];
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
		$current_approver	= $r2['current_approver'];

//echo $statusap. " <> <BR>";

		if($statusap=='Reject'){
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_username		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			//$userid		 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$to_approver		= $draft_by_id;
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
			
			$sql = "UPDATE sma_goods_receipt_note SET 
			approver_1 = '', approver_2 = '', approver_3 = '',approver_4 = '',
			approver_5 = '', approver_6 = '', approver_7 = '',approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() WHERE id = '$grn_hdr_id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			if( empty($userid) ){
				$userid = $current_approver;
			}
			
			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'GN', '$grn_hdr_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
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
			
//echo $sql."<BR>";
//echo $draft_by_id."<BR>";
	
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
//	echo ' #### 2 ### ' . $to_approver. "<BR>";		
//	echo $status. ' ' .$approval_status."<BR>"; 
	//exit();
	if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			$status_field_from_v = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from 	= 'Approved' ";
				$status_field_from_v 			= 'Approved';
			}
			$sql = " update sma_goods_receipt_note set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$grn_hdr_id'";
	//echo $sql. "<BR>"; //exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values('GN', '$grn_hdr_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
			if($status =='Completed' ){
				$sql = " SELECT * FROM sma_goods_receipt_note_items WHERE grn_hdr_id = '$grn_hdr_id' ";
//echo $sql. "<BR>";		
				$q2=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){ echo $error; }
					while($r2 = mysqli_fetch_array($q2)){
						
						$item_id   		= $r2['id'];
						$receipt_qty 	= $r2['receipt_qty'];
						$product_id		= $r2['product_id'];
						
						$sql = "UPDATE sma_product_open_stock set receipts = receipts + $receipt_qty where product_name = '$product_id' and project = '$company_id' ";							
						mysqli_query($con, $sql);
						$rowaffected = mysqli_affected_rows($con);
						echo mysqli_error($con);
						if($rowaffected==0){
							$sql = "INSERT INTO `sma_product_open_stock` (product_name, project, receipts, uom, created_date, remarkv) 
							values ( '$product_id', '$company_id', '$receipt_qty', '', now(), 'Completed' )";
							mysqli_query($con, $sql);
							
						}	
					//echo $sql. "<BR>";		
					
					}
			}	
			
//	echo $sql. "<BR>";		
			
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
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	}

//exit('RAVINDRA Exit HERE...');
		
		$modulePath = "goods_issue_note/";
		
		$baseurl1 = $baseurl.$modulePath.'goods_receipt_note.php?sub=edit&id='.$grn_hdr_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$msg = 'Goods Issue Notes Number : '.$grn_hdr_id . ' ' . 'Dated : ' . date("d-m-Y");
		
		include "gin_mail.php";
		
		$role		= $_SESSION['role'];
//echo $role;		
//exit("RAVINDRA STOPED...");

		if(!empty($status_field_from || $approval_status = 'Approved' || $approval_status = 'Rejected' )){
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=A";
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		else {	
			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		exit();
		
	}
?>
