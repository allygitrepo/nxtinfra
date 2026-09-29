<?php session_start();
    
    date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
    
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<link href="https://cdnjs.cloudflare.com" rel="stylesheet" />

<?php
	  if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getbudgetheada(this.value)"  >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.name from sma_budget a, sma_budget_name b where project = '$id' and b.id = a.budget_name order by project";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

$value=$sql;

        echo $value;
    }
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getbudgethead(this.value)"  >
									<option value=""> Select </option>';

	    //$sql = "select a.id, a.project, a.budget_category, b.name from sma_budget a, sma_budget_name b where account_year = '$id' and b.id = a.budget_name order by project";
		$sql = "SELECT name, id from sma_budget_name where id in ( select budget_name FROM `sma_budget` where account_year = '$id' )";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
//		$value .= '<input type="text" class="form-control" id="budget_head" name="budget_head" readonly value="'.$budget_head.'" >';
		$value = '';
		$value ='<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.category from sma_budget a, sma_budget_category b where b.id = a.budget_category and budget_name in (select budget_name from sma_budget where id = '$id') group by project, category";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->category;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';
//$value = $sql;
		echo $value;
		
	}

    if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control js-example-basic-single" name="itemName" id="itemName" required="true" onchange="getunit1(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT id, name FROM sma_product where `group` = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

   if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control js-example-basic-single" name="itemName" id="itemName" required="true" onchange="getunit2(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT id, name FROM sma_product where `group` = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
		
	if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
	
		if($_POST['id'] == ''){$id = '';}
		
		$sql = "SELECT * from sma_product  where 1 and id = '$id'  and budget_head > 0 ";
		$q2  		 = mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_array($q2);
		$category   = $r2['category'];
		$uom        = $r2['uom'];
		$budget_head = $r2['budget_head'];
		
// 			if($category=='S'){
// 				$uom = 'INR';
// 			}
							
		$sql = "SELECT * from sma_budget_subgroup  where 1 and id = '$budget_head' ";
		$q2  		 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$budget_head = $r2['budget_head'];
		

//		if($category=='M'){
?>
		<div class="col-sm-4 col-md-4">
			<label for="itemUnits" class="control-label">Unit of Measurement</label>
		   <input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "<?= $uom;?>" >
		</div>
<?php		
//		}
?>		
		<!--<div class="col-sm-4 col-md-4">-->
		<!--	<label for="itemUnits" class="control-label">Budget Head</label>-->
		<!--   <input type="text" class="form-control" readonly value = "<?= $budget_head;?>" >-->
		<!--</div>-->
		
<?php		
		if($rowaffected==0){
			//$value .= "<span style='color:red;'><b>No Budget defined in the Product</b></span>";
?>			
			<div class="col-sm-4 col-md-4">
				<span style='color:red;'><b>Warning : No Budget defined in the Product, Please add Budget before generating PO.</b></span>
			</div>
<?php		
		}
//$value.=$sql;
        //echo $value;
    }

    if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemunits" id="itemUnits" readonly >
									<option value=""> Select </option>';

	    $sql = "SELECT * FROM sma_product where id = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			$r2 = mysqli_fetch_object($q2);
			$uom = $r2->uom;
            
		$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
//$value.=$sql;

        echo $value;
    }

    if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="project" id="project" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name";
		$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$loc_name."</option>";
        };
		$value .= '</select>';
        echo $value;
    }

    if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_location where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$loc_addr = $r2->loc_addr1;
		if(!empty($r2->loc_addr2)){
			$loc_addr .= $r2->loc_addr2.',';
		}
		if(!empty($r2->loc_addr3)){
			$loc_addr .= $r2->loc_addr3.',';
		}
		if(!empty($r2->loc_city)){
			$loc_addr .= $r2->loc_city.',';
		}
		if(!empty($r2->loc_pincode)){
			$loc_addr .= '-'.$r2->loc_pincode;
		}
		
        $value .= '<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address">'.$loc_addr.'</textarea>';
    
//$value=$sql;

        echo $value;
    }

	if(isset($_POST['sub7'])){
	
		$value ='';
        $id = $_POST['id'];
		$purchase_req_id = $_POST['purchase_req_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_purchase_req_items where id = '$id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//$value = $value1;
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$purchase_req_id&active=active&123';</script>";
		echo $value;
		
	}


    if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="approver" id="approverE" required >
					<option value=""> Select </option>';

	    $sql = "SELECT * FROM sma_user where department = '$id' ORDER BY first_name ASC ";
		$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
			//$user_name = $r2->first_name. ' ' . $r2->last_name;
            $id = $r2->id;
			$username = $r2->username;
            $value .= "<option value='".$id."'>".$username."</option>";
        };
		$value .= '</select>';
        echo $value;
    }

	
?>

<?php

	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		//$department			= $_POST['department'];
		//$checker_value 		= $_POST['checker_value'];
		
		$purchase_req_id	= $_POST['purchase_req_id'];
		
		$checker_value 		= 100000000;
		
		$doc_type = 'PR';
		
		$sql="SELECT * FROM sma_purchase_req where id = '$purchase_req_id' ";
//echo $sql;
        $rs = mysqli_query($con, $sql);
        echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$approver_fhead  = $rw['approver_1'].','.$rw['approver_2'].','.$rw['approver_3'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT * FROM sma_workflow 
					where 1 and doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' 
					 ";	
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
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active = 1 and id = ( SELECT approval_role_1 
								            FROM sma_workflow  
											WHERE 1 and doc_type = '$doc_type' 
											and '$checker_value' >= from_value and '$checker_value' <= to_value 
											and company_id = '$company_id' 
												and approval_role_1 >0 ) ";
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
								$sql = " select * from sma_user where 1 and active = 1 and id = ( SELECT approval_role_2 
								            FROM sma_workflow  
											WHERE 1 and doc_type = '$doc_type' 
											and '$checker_value' >= from_value and '$checker_value' <= to_value 
											and company_id = '$company_id' 
												and approval_role_2 >0 ) ";
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
								$sql = " select * from sma_user where 1 and active = 1 and id = ( SELECT approval_role_3 
								            FROM sma_workflow  
											WHERE 1 and doc_type = '$doc_type' 
											and '$checker_value' >= from_value and '$checker_value' <= to_value 
											and company_id = '$company_id' 
												and approval_role_3 >0 ) ";
											
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
								$sql = " select * from sma_user where 1 and active = 1 and id = ( SELECT approval_role_4 
								            FROM sma_workflow  
											WHERE 1 and doc_type = '$doc_type' 
											and '$checker_value' >= from_value and '$checker_value' <= to_value 
											and company_id = '$company_id' 
												and approval_role_4 >0 ) ";
											
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
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
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
 
    if(isset($_POST['sub3A'])){
        $id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
				
		$value = '';	
//echo $sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and a.product_group = '$id' and b.company_id = '$company_id' and b.budget_id > 0 order by name";		
?>
			<select class="form-control itemName js-example-basic-single" name="itemName" id="itemName" required="true" onchange="getunit2(this.value);getdupprd(this.value);" >
			<option value=""> Select..</option>
<?php
			//$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and a.product_group = '$id' and b.company_id = '$company_id' and b.budget_id > 0 order by name";
			
			$sql = "SELECT * from sma_product where 1 and active = 'Y' and product_group = '$id' order by name";
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
		
        $product_id 		= $_POST['id'];
        $purchase_req_id 	= $_POST['purchase_req_id'];
	
		$sql = "SELECT * from sma_purchase_req_items where quantity > 0 and purchase_req_id = '$purchase_req_id' and product_id = '$product_id' ";
		$q2  = mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		$r2  = mysqli_fetch_object($q2);
		
		if($rowaffected>0){
			echo "Duplicate product not allowed...".'###'.$rowaffected;
		}
//		$name = $r2->name;

    }
	
	if(isset($_POST['sub5A'])){
        
		$product_id 		= $_POST['id'];
	
		$sql = "SELECT * from sma_product where 1 and id = '$product_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$category = $r2->category;

		if($category=='S' || empty($category) ){
			echo "<label class='control-label' >Value</label>";	
		}
		else if($category=='M') {
			echo "<label class='control-label' >Quantity</label>";	
		}
		
    }
	
	if(isset($_POST['sub35'])){
		
		$modulePath = "purchase_requisition/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$pr_id		 		= $_POST['pr_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page				= $_POST['page']; 		
		$baseurl .= $modulePath.'edit.php?sub=edit&id='.$pr_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$pr_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_purchase_order where id = '$pr_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
					
		
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
		
			
		echo "<script>window.location.href='$baseurl';</script>";
} 

    if(isset($_POST['sub27'])){
    
        $company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
?>
		<select class="form-control select3" name="trans_type" id="trans_type" required >
			<option value=""> Select </option>
			<?php //$sql = " SELECT * FROM sma_workflow_type WHERE doc_type = 'PR123' ";
			$sql = "SELECT b.* FROM `sma_workflow` a, sma_workflow_type  b where 1 and b.status = 'Y' and a.doc_type = 'PR' and a.doc_type = b.doc_type and b.id = a.trans_type and a.company_id = '$company_id'  order by trans_type " ;
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>

<?php
    }
    
    if(isset($_POST['sub28'])){
    
        $comp_id = $_POST['id'];
		$sql 	= "SELECT * FROM `sma_location` where loc_comp_id = '$comp_id'";	
    	$result = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($result);
    	$loc_addr1   = $row['loc_addr1'];
?>    	
    	<textarea class="form-control" id="delivery_address" name="delivery_address" ><?= $loc_addr1; ?></textarea>
    	
<?php

    }	
?> 

    <!-- Include jQuery -->
    <script src="https://code.jquery.com"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
    
    