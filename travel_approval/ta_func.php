<?php
	session_start(); 	
    include("../dbcon.php");

    $finance_year = $_SESSION['finance_year'];
//	$finance_year 		= $_SESSION['short_fy_code'];
//	$short_fy_code 		= $_SESSION['short_fy_code'];

    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			
		$sql="SELECT * from sma_user where id = '$id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$roll_no 		= $r1['roll_no'];
			$user_category 	= $r1['user_category'];
			$role			= $r1['role'];
			$department		= $r1['department'];
			$designation	= $r1['designation'];
			$level_id		= $r1['level_id'];
			$emp_name		= $r1['username'];
		
		if ($user_category=='H'){
			$user_category = 'Head Office';
		}
		
		else if ($user_category=='S'){
			$user_category = 'Site Office';
		}
		$sql="SELECT * from sma_role where id = '$role' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$role 		= $r1['role'];
		
		$sql="SELECT * from sma_department where id = '$department' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$department 		= $r1['name'];
		
		$sql="SELECT * from sma_designation where id = '$designation' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$designation 		= $r1['designation'];
		
		$sql="SELECT * from sma_level where id = '$level_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$level_id 		= $r1['level_name'];
		
		$value ='';
		$value .='<div class="form-group">';
						
		$value .='<label class="col-lg-2 control-label">Employee No.</label>';			
		$value .='<div class="col-md-1">';						
		$value .='<input type="text" class="form-control"  style="text-align:left;font-size:11px;font-weight:400;" autocomplete="off" READONLY value="'.$roll_no.'"> ';
		$value .='</div>';
		$value .='<label class="col-lg-1 control-label">Name</label>';			
		$value .='<div class="col-md-2">';						
		$value .='<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$emp_name.'"> ';
		$value .='</div>';
		$value .='<label class="col-lg-1 control-label">Category</label>';			
		$value .='<div class="col-md-2">';
		$value .='<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$user_category.'"> ';
		$value .='</div>';
		$value .='<label class="col-lg-1 control-label">Role</label>';			
		$value .='<div class="col-md-2">';
		$value .='<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$role.'"> ';
		$value .='</div>';
		$value .='</div>';
		
		$value .='<div class="form-group">';
		$value .='<label class="col-lg-2 control-label">Department</label>';			
		$value .='<div class="col-md-2">';
		$value .= '<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$department.'"> ';
		$value .='</div>';
		$value .='<label class="col-lg-1 control-label">Designation</label>';			
		$value .='<div class="col-md-2">';
		$value .= '<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$designation.'"> ';
		$value .='</div>';
		$value .='<label class="col-lg-1 control-label">Lavel</label>';			
		$value .='<div class="col-md-2">';
		$value .= '<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="'.$level_id.'"> ';
		$value .='</div>';
		$value .='</div>';

		//$value = $sql;
		echo $value;
	
    }

    if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			
		$sql="SELECT * from sma_traval_approval where id = '$id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$row = mysqli_fetch_array($res1);
		$emp_id 			= $row['emp_id'];
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		$company_id 		= $row['company_id'];
		$dated				= date('d-m-Y');
		$approval_ref_no	= $row['id'];
		$advance_amount		= $row['advance_amount'];
		$location			= $row['location'];
		$datedd				= date('Y-m-d');
		$exp_type			= 'T';
		
		$user   = $_SESSION['user'];		
		$te_id = $_SESSION['te_id'];

		$sql="Insert into sma_travel_expenses (id, exp_type, emp_id, onbehalf_emp_id, company_id, dated, approval_ref_no, advance_amount, status, draft_by, draft_dated, location ) values ('$te_id', '$exp_type', '$emp_id', '$onbehalf_emp_id', '$company_id', '$datedd', '$approval_ref_no', '$advance_amount', 'Draft', '$user', now() , '$location' ) ";
		$res1 = mysqli_query($con, $sql);

		$start_date = date('d-m-Y', strtotime($row['start_date']));
		if($start_date =='01-01-1970' || $start_date =='31-12-1969'){
			$start_date = '';
		}
		else {
			$start_date = date('Y-m-d', strtotime($row['start_date']));
		}	
		$end_date   = date('d-m-Y', strtotime($row['end_date']));
		if($end_date =='01-01-1970' || $end_date =='31-12-1969'){
			$end_date = '';
		}
		else {
			$end_date   = date('Y-m-d', strtotime($row['end_date']));
		}	
		
		$start_place	= $row['traval_from'];
		$end_place		= $row['traval_to'];
		$start_time		= $row['start_time'];
		$end_time		= $row['end_time'];				
		$mode_of_travel	= $row['mode_of_travel'];
		//				= $row['type_of_travel'];
						
		$sql="Insert into sma_departure (approval_ref_no, start_date, start_place, start_time, end_date, end_place, finish_time, mode_of_travel) values('$te_id', '$start_date', '$start_place', '$start_time', '$end_date', '$end_place', '$end_time', '$mode_of_travel' )";
		
		//'$invoice_no', '$fare', '$gst_amount', , '$spend_by'
		$result = mysqli_query($con, $sql);
	
		//$last_row = mysqli_insert_id($con);	
		
		//$_SESSION['te_id'] = $last_row;

		//echo $last_row. "<<<>>>".$_SESSION['te_id'];

		$sql="SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$roll_no 		= $r1['roll_no'];
			$user_category 	= $r1['user_category'];
			$role			= $r1['role'];
			$department		= $r1['department'];
			$designation	= $r1['designation'];
			$level_id		= $r1['level_id'];
			$username		= $r1['username'];


								if ($user_category=='H'){
									$user_category = 'Head Office';
								}
								else if ($user_category=='S'){
									$user_category = 'Site Office';
								}
									$sql="SELECT * from sma_role where id = '$role' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$role 		= $r1['role'];
									
									$sql="SELECT * from sma_department where id = '$department' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$department 		= $r1['name'];
									
									$sql="SELECT * from sma_designation where id = '$designation' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
										$designation 		= $r1['designation'];
										$travel_per_diem 	= $r1['travel_per_diem'];
									
									$sql="SELECT * from sma_level where id = '$level_id' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
									$level_id 		= $r1['level_name'];	
									
			$sql="SELECT * from sma_location where id = '$location' ";
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$location 		= $r1['loc_name'];
										
		$sql="SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$comp_id 		= $r1['comp_id'];
		$comp_name 		= $r1['comp_name'];
		$_SESSION['comp_id_tr']	= $comp_id;
		
		$value ='';
		
?>						
									
					<div class="form-group"> 
											
						<label class="col-lg-1 control-label">Name</label>			
						<div class="col-md-2">						
							<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $username;?>">  
						</div> 
							 
						<label class="col-lg-1 control-label">Role</label> 			
						<div class="col-md-2"> 
							<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $role;?>">  
						</div> 
					</div> 
								
					<div class="form-group">
							<label class="col-lg-2 control-label">Company </label>
								<div class="col-md-4">
									<select class="form-control" name="company_id" id="company_Id"  disabled="disabled" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where comp_id = '$company_id' ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
							</div>
					
							<label class="col-lg-1 control-label">Dated </label>
							<div class="col-md-2">
								<!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="dated" name="dated" disabled="disabled"  placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
								</div>-->
								<input type="text" class="form-control" id="dated" name="dated" disabled="disabled"  placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
							</div>
							
							<label class="col-lg-1 control-label">Advance&nbsp;Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="advance_amount" name="advance_amount" readonly style="text-align:right;" autocomplete="off" value="<?php echo $advance_amount; ?>">
							</div>
							
					</div>
					
					<div class="form-group"> 
										
						<div class="col-md-2">						
							<label class="control-label">Travel Per Diem</label>			
							<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $travel_per_diem;?>">  
						</div> 
						
						<div class="col-md-2"> 
							<label class=" control-label">No.of Days</label>
							<input type="text" class="form-control" name="no_of_days" id="no_of_days"  style="text-align:left;" autocomplete="off" value="<?php echo $no_of_days;?>">  
						</div> 
						
						<div class="col-md-2"> 
							<label class=" control-label">Rupees</label>
							<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $total_rupees;?>">  
						</div>
						
						<div class="col-md-2"> 
							<label class=" control-label">Location</label>
							<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $location;?>">  
						</div>
						
					</div> 

<?php					
						
			echo "<script>window.location.href='travel_expence.php?sub=edit&id=$te_id';</script>";			
?>
							
<?php								
//$value = $sql; $travel_per_diem
		//echo $value;
   }

if(isset($_POST['sub22'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			
		$sql="SELECT * from sma_traval_approval where id = '$id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$emp_id 		= $r1['emp_id'];
		$company_id 	= $r1['company_id'];
		$dated			= date('d-m-Y');
		$approval_ref_no	= $r1['id'];
		$advance_amount	= $r1['advance_amount'];
		$datedd			= date('Y-m-d');
		$exp_type		= 'T';
		
		$user   = $_SESSION['user'];

		$sql="SELECT * from sma_user where id = '$emp_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
			$roll_no 		= $r1['roll_no'];
			$user_category 	= $r1['user_category'];
			$role			= $r1['role'];
			$department		= $r1['department'];
			$designation	= $r1['designation'];
			$level_id		= $r1['level_id'];
			$username		= $r1['username'];
		
		$sql="SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$comp_id 		= $r1['comp_id'];
		$comp_name 		= $r1['comp_name'];
		
		$value ='';
?>						
									
					<div class="form-group">
							<label class="col-lg-2 control-label">Company </label>
								<div class="col-md-3">
									<select class="form-control" name="company_id" id="company_id"  disabled="disabled" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where comp_id = '$company_id' ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
							</div>
					
							<label class="col-lg-1 control-label">Dated </label>
							<div class="col-md-2">
								<!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="dated" name="dated" disabled="disabled"  placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
								</div>-->
								<input type="text" class="form-control" id="dated" name="dated" disabled="disabled"  placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
							</div>
							
							<label class="col-lg-2 control-label">Advance Amount</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="advance_amount" name="advance_amount" readonly style="text-align:right;" autocomplete="off" value="<?php echo $advance_amount; ?>">
							</div>
							
					</div>
<?php								
//$value = $sql;
		//echo $value;
   }

   
if(isset($_POST['sub3'])){
    
        //$date1 = date_create(date("Y-m-d", strtotime($_POST['date1'])));
		//$date2 = date_create(date("Y-m-d", strtotime($_POST['date2'])));
		if($_POST['date1'] == ''){$date1 = '';}
		if($_POST['date2'] == ''){$date2 = '';}
		
		//$days=date_diff($date1,$date2);
				
		$date1 = date("Y-m-d", strtotime($_POST['date1']));
		$date2 = date("Y-m-d", strtotime($_POST['date2']));
		
		$datediff = strtotime($date2) - strtotime($date1);

		//$your_date = strtotime("2010-01-01");
		//$datediff = $now - $your_date;
//		echo round($datediff / (60 * 60 * 24));
		$days = round($datediff / (60 * 60 * 24)) + 1;

		if($days<0){
			$days=0;
		}	
		$value = '';
		$value = '<input type="text" class="form-control" id="estimated_days" name="estimated_days" readonly style="text-align:right;" autocomplete="off" value="'. $days.'" >';
 
		echo $value;

}

	
if(isset($_POST['sub6'])){
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}


	$sql = "SELECT distinct(a.id), a.dated,  `values` as ap_amount, b.approval_hdr_id, b.supplier_name
					FROM `sma_approval_memo` a, sma_approval_details b , sma_approval_items c
						where a.id =  b.approval_hdr_id and a.overhead_exp = 'Y' and a.del !='Y'
						and a.approval_status in ( 'Approved' )
						and b.supplier_name = '$id' and a.company = '$company_id' 
						and b.values > 0 
						and a.id = c.`approval_hdr_id`
                        and c.amount > c.bal_amount";
//echo $sql;	
?>				
			<select class="form-control" name="approval_number" id="approval_number" >
				<option value=""> Select </option>
				<?php  
				$sql = "SELECT distinct(a.id), a.dated,  `values` as ap_amount, b.approval_hdr_id, b.supplier_name
					FROM `sma_approval_memo` a, sma_approval_details b , sma_approval_items c
						where a.id =  b.approval_hdr_id and a.overhead_exp = 'Y' and a.del !='Y'
						and a.approval_status in ( 'Approved')
						and b.supplier_name = '$id' and a.company = '$company_id' 
						and b.values > 0 
						and a.id = c.`approval_hdr_id`
                        and c.amount > c.bal_amount";
			//, 'Submitted' 
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){
						
						$dated = date('d-m-Y', strtotime($r2['dated']));
						$ap_id = $r2['id'];
					?>
				<option value="<?php echo $ap_id;?>" > <?php echo $ap_id .' | '.$dated;?></option>
				<?php } ?>
			</select>		
<?php

//$value = $sql;
	    echo $value;
    }						
?>

<?php

    if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		$company_id 		= $_POST['company_id'];
		$product_id 		= $_POST['product_id'];
// 		$approval_number	= $_POST['approval_number'];
 		$approval_ref_no	= $_POST['approval_ref_no'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
	//	$sql = "SELECT * FROM `sma_product_cost_center` where product_id = '$product_id' and company_id = '$company_id' ";
		$sql = "SELECT * FROM `sma_product` where id = '$product_id' ";
//echo $sql ."<BR>";			
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_head 		= $r2->budget_head;
		//$budget_name_id 	= $r2->budget_name;
		
		$sql = "SELECT sum(amount + gst_amount) as exp_amount_total 
				FROM sma_expenses 
					WHERE approval_ref_no = '$approval_ref_no' and exp_type = 'C' 
					and reference = '$product_id' ";
//echo $sql ."<BR>";
		$res 	= mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$exp_amount_total = $r1['exp_amount_total'];
		if(empty($exp_amount_total)){
			$exp_amount_total = 0;	
		}	
											
		$sql = "SELECT * FROM sma_budget_subgroup WHERE id = '$budget_head' ";
//echo $sql ."<BR>";			
		$q2  = mysqli_query($con, $sql);
 		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_object($q2);
		$budget_name_id 	= $r2->budget_name;	
		$budget_head_id 	= $r2->id;	
		
		$sql = "SELECT * FROM sma_budget WHERE 1 and project = '$company_id' and account_year = '$finance_year' 
					AND budget_head = '$budget_head_id' 
					AND budget_name = '$budget_name_id' 
					 "; 
//	echo $sql. "<BR>";				
		
			
//echo $sql. "<BR>";	
		$q2  = mysqli_query($con, $sql);
 		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_object($q2);
			$account_year 		= $r2->account_year;
			$budget_name_id 	= $r2->budget_name;
			$budget_head 		= $r2->budget_head;
			$total_budget 		= $r2->total_budget;
			$blocked_budget 	= $r2->blocked_budget;
			$used_budget 		= $r2->used_budget;		
			$adjustment_budget	= $r2->adjustment_budget;
			$balance_budget		= ( $total_budget + $adjustment_budget ) - ( $blocked_budget + $used_budget );
			
            $budget_id 		 	= $r2->id;
			$total_budget 		= $total_budget + $adjustment_budget;
          
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);	
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
?>

            <input type="hidden" class="form-control" id="row_AFFECTED_a" value="<?php echo $row_affected; ?>" >
            
<?php			
			if($row_affected==0){
?>				
				<div class="form-group">
					
					<div class="col-sm-6">
						<label class="control-label" style="color:red;">Budget not available for Expense !!!</label>
					</div>
				</div>
<?php				
				exit();
			}
			
?>			
			<input type="hidden" class="form-control" name="exp_amount_total" id="exp_amount_total_a" readonly value="<?php echo $exp_amount_total ?>" >
			
			<input type="hidden" class="form-control" name="company_id" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="BUDGET_ID" name="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_Budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_Budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" name="budget_name"  id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			<input type="hidden" class="form-control" name="budget_head"  id="budget_Head" readonly value="<?php echo $budget_head ?>" >
		
		<div class="well well-sm" >		
		
			<div class="form-group">
			
			<?php	if($approval_number>0){ ?>	
				<div class="col-sm-4" style="text-align:left;">
				<label class="control-label">Approved for this Product </label>
				<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6" style="text-align:left;">
				<label class="control-label">Balance from Approval </label>
				<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
				</div>
			<?php } 	
				else { ?>
					<div class="col-sm-2">
						<label class="control-label " style="text-align:left;" >Account Year</label>
						<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >
					</div>
					<div class="col-sm-4" style="text-align:left;">
						<label class="control-label">Total Budget </label>
						<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
					</div>
										
					<div class="col-sm-6" style="text-align:left;">
						<label class="control-label">Balance Budget </label>
						<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
					</div>
			<?php } ?>	
			</div>
		</div>	
<?php 								
        
//$value=$sql;

       // echo $value;
	   
    }
	
	

    if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM `sma_product` where id = '$id' ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$description 	= $r2->description;
?>
		<div class="form-group">
				<div class="col-sm-12" style="text-align:left;">
					<label class="control-label">Description</label>
					<input type="text" class="form-control" readonly value="<?php echo $description ?>" >
				</div>
			</div>
<?php			
			
	}
	
	if(isset($_POST['sub1a'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
		$value .='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getcatbudget(this.value);getcatbudgetC(this.value)"  >
			<option value=""> Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id'  ";	//and project = '$company_id'
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }
	
	if(isset($_POST['sub1b'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
		$value .='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getcatbudgetD(this.value)"  >
			<option value=""> Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id'  ";		//and project = '$company_id'
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }
	
	if(isset($_POST['sub8'])){
	
		$company_id = $_POST['id'];
	
?>	
	
	<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required  onchange="getapproval(this.value); getpangst(this.value)">
        <option value=""> Select  </option>
		<?php $sql = "select * from sma_party_mst where 1 and id in (SELECT b.supplier_name FROM `sma_approval_memo` a, sma_approval_details b 
					where a.id =  b.approval_hdr_id and overhead_exp = 'Y'  and a.company = '$company_id' and b.values > 0 ) order by party_name ";//party_kyc = 'Y' and approval_status = 'Approved'
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
		<?php } ?>
		</select>
<?php
	}

	if(isset($_POST['sub9'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
	    $sql = "SELECT * FROM sma_product where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$uom = $r2->uom;
		$description 	= $r2->name;
		$gst_type 		= $r2->gst_type;
		
		$sql = " SELECT * FROM `gst_mst` where id = '$gst_type' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$igst = $r2->igst;
//$value.=$sql;

       // echo $uom. '-' . $description.'-'.$igst;
		echo $igst;
		
    }
	
	if(isset($_POST['sub88'])){
	
		$company_id = $_POST['id'];
		$sql="SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$oe_limit_amount 		= $r1['oe_limit_amount'];
		
		echo $oe_limit_amount;
		
	}	


	 if(isset($_POST['sub55'])){
    
        $id = $_POST['id'];
		$company_id 		= $_POST['company_id'];
		$product_id 		= $_POST['product_id'];
		$approval_ref_no	= $_POST['approval_ref_no'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		//$sql = "SELECT * FROM `sma_product_cost_center` where product_id = '$product_id' and company_id = '$company_id' ";
		$sql = "SELECT * FROM `sma_product` where id = '$product_id' ";
//echo $sql ."<BR>";			
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
	//	$budget_id 			= $r2->budget_id;
		$budget_head_id 	= $r2->budget_head;
		
		$sql = "SELECT * FROM sma_budget_subgroup WHERE id = '$budget_head_id' ";
//echo $sql ."<BR>";			
		$q2  = mysqli_query($con, $sql);
 		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_object($q2);
		$budget_name_id 	= $r2->budget_name;	
		$budget_head_id 	= $r2->id;	
		
		$sql = "SELECT * FROM sma_budget WHERE account_year = '$finance_year' 
					AND budget_name = '$budget_name_id' 
					AND budget_head = '$budget_head_id' 
					AND project     = '$company_id' ";		
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_id 		= $r2->budget_id;
//echo $sql ."<BR>";		
			
		$sql = "SELECT sum(amount + gst_amount) as exp_amount_total 
				FROM sma_expenses 
					WHERE approval_ref_no = '$approval_ref_no' and exp_type = 'D' 
					and budget_id = '$budget_id' ";
//echo $sql ."<BR>";
		$res 	= mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$exp_amount_total = $r1['exp_amount_total'];
		if(empty($exp_amount_total)){
			$exp_amount_total = 0;	
		}	
		
		$sql = "SELECT * FROM sma_budget WHERE account_year = '$finance_year' 
					AND budget_name = '$budget_name_id' 
					AND project     = '$company_id' ";
//echo $sql;					
		$q2  = mysqli_query($con, $sql);
 		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_object($q2);
			$account_year 		= $r2->account_year;
			$budget_name_id 	= $r2->budget_name;
			//$budget_head 		= $r2->budget_head;
			$total_budget 		= $r2->total_budget;
			$blocked_budget 	= $r2->blocked_budget;
			$used_budget 		= $r2->used_budget;		
			$adjustment_budget	= $r2->adjustment_budget;
			$balance_budget		= ( $total_budget + $adjustment_budget ) - ( $blocked_budget + $used_budget );
            $budget_id 		 	= $r2->id;
			$total_budget 		= $total_budget + $adjustment_budget;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);	
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
			if($row_affected==0){
?>				
				<div class="form-group">
					
					<input type="hidden" class="form-control" id="row_AFFECTED_a" value="<?php echo $row_affected ?>" >
					
					<div class="col-sm-6">
						<label class="control-label" style="color:red;">Budget not available for Expense !!!</label>
					</div>
				</div>
<?php				
				exit();
			}	
			
			

?>			
			<input type="hidden" class="form-control" name="exp_amount_total" id="exp_amount_total_a" readonly value="<?php echo $exp_amount_total ?>" >
			
			<input type="hidden" class="form-control" name="company_id" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="BUDGET_ID" name="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_Budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_Budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" name="budget_name"  id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			<!--<input type="hidden" class="form-control" name="budget_head"  id="budget_Head" readonly value="<?php echo $budget_head ?>" >-->
		
		<div class="well well-sm" >		
		
			<div class="form-group">
			
			<?php	if($approval_number>0){ ?>	
				<div class="col-sm-4" style="text-align:left;">
				<label class="control-label">Approved for this Product </label>
				<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6" style="text-align:left;">
				<label class="control-label">Balance from Approval </label>
				<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
				</div>
			<?php } 	
				else { ?>
					<div class="col-sm-2">
						<label class="control-label " style="text-align:left;" >Account Year</label>
						<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >
					</div>
					<div class="col-sm-4" style="text-align:left;">
						<label class="control-label">Total Budget </label>
						<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
					</div>
										
					<div class="col-sm-6" style="text-align:left;">
						<label class="control-label">Balance Budget </label>
						<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
					</div>
			<?php } ?>	
			</div>
		</div>	
<?php 								
        
//$value=$sql;

       // echo $value;
	   
    }
	
	
?>	
