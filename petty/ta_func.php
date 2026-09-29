<?php
	
    include("../dbcon.php");
	session_start(); 	

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
		$r1 = mysqli_fetch_array($res1);
		$emp_id 		= $r1['emp_id'];
		$company_id 	= $r1['company_id'];
		$dated			= date('d-m-Y');
		$approval_ref_no	= $r1['id'];
		$advance_amount	= $r1['advance_amount'];
		$datedd			= date('Y-m-d');
		$exp_type		= 'T';
		
		$user   = $_SESSION['user'];
		
		$te_id = $_SESSION['te_id'];

		$sql="Insert into sma_pettycash (id, exp_type, emp_id, company_id, dated, approval_ref_no, advance_amount, status, draft_by, draft_dated ) values ('$te_id', '$exp_type', '$emp_id', '$company_id', '$datedd', '$approval_ref_no', '$advance_amount', 'Draft', '$user', now() ) ";
		$res1 = mysqli_query($con, $sql);

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
									
									$sql="SELECT * from sma_level where id = '$level_id' ";
									$res1 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res1);
									$level_id 		= $r1['level_name'];	
												
		$sql="SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$comp_id 		= $r1['comp_id'];
		$comp_name 		= $r1['comp_name'];
		
		$value ='';
		
		
				
?>						
									
					
						<div class="form-group"> 
											
							<label class="col-lg-2 control-label">Employee No.</label>			
							<div class="col-md-1">						
								<input type="hidden" id="emp_id" name="emp_id"  value="<?php echo $emp_id;?>">
								<input type="text" class="form-control"  style="text-align:left;font-size:11px;font-weight:400;" autocomplete="off" READONLY value="<?php echo $roll_no;?>">  
							</div> 
							<label class="col-lg-1 control-label">Name</label>			
							<div class="col-md-2">						
								<input type="text" class="form-control"  style="text-align:left;" READONLY value="<?php echo $username;?>">  
							</div> 
							<label class="col-lg-1 control-label">Category</label> 			
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;"  READONLY value="<?php echo $user_category;?>">  
							</div> 
							<label class="col-lg-1 control-label">Role</label> 			
							<div class="col-md-2"> 
								<input type="text" class="form-control"  style="text-align:left;" autocomplete="off" READONLY value="<?php echo $role;?>">  
							</div> 
						</div> 
								
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

		$value = '';
		$value = '<input type="text" class="form-control" id="estimated_days" name="estimated_days" readonly style="text-align:right;" autocomplete="off" value="'. $days.'" >';
 
		echo $value;

}

	
if(isset($_POST['sub5'])){ 
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT a.id as cat_id, a.description as product_category, c.id as budget_name_id, c.name as budget_namee, d.id as budget_head_id, d.category as budget_head, b.* 
				FROM `sma_product_group` a, sma_budget b, sma_budget_name c, sma_budget_category d 
				where a.id = '$id' and a.budget_head = b.budget_category and a.budget_name = b.budget_name 
					and c.id = a.budget_name and d.id = a.budget_head and b.project = '$company_id' ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$budget_head_id 	= $r2->budget_head_id;
			$budget_name_id 	= $r2->budget_name_id;
			
			$budget_head 	= $r2->budget_head;
			$budget_name 	= $r2->budget_namee;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			//$balance_budget = $r2->balance_budget;
			
			$balance_budget	= $total_budget - ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
				
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);			
			$project = $r2['comp_name'];					
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_head_id ?>" >
		
		<div class="well well-sm" >		
			<div class="form-group">
							
				<div class="col-md-8">
					<label class=" control-label">Company</label>
					<input type="text" class="form-control" id="project_a" readonly value="<?php echo $project ?>" >
				</div>
			</div>
											
			<div class="form-group">
				<div class="col-sm-4">
					<label class="control-label">Budget Name</label>
					<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_name ?>" >
				</div>
									
				<div class="col-sm-5">
					<label class="control-label">Budget Head</label>
					<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >
				</div>
			
				<div class="col-sm-3">
					<label class="control-label">Blocked Budget </label>
					<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $blocked_budget ?>" >
				</div>
			
			</div>
			
			<div class="form-group">
			
				<div class="col-sm-4">
					<label class="control-label">Used Budget </label>
					<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $used_budget ?>" >
				</div>
			
				<div class="col-sm-4">
				<label class="control-label">Total Budget </label>
					<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-4">
				<label class="control-label">Balance Budget </label>
					<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

       // echo $value;
	   
	}
	
if(isset($_POST['sub6'])){
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}

?>	

			<select class="form-control" name="approval_number" id="approval_number" >
				<option value=""> Select </option>
				<?php  //$sql = "SELECT dated, id FROM `sma_approval_memo` a, `sma_approval_details` b, sma_purchase_order c where a.id = b.approval_hdr_id and b.supplier_name = '$id' and b.vendor_selected = 'Y' and approval_status = 'Approved' and c.approval_memo_ref != a.id ";
					//$sql = "SELECT a.dated, a.id FROM `sma_approval_memo` a, sma_approval_details b where a.id =  b.approval_hdr_id and overhead_exp = 'Y' and approval_status = 'Approved' and b.supplier_name = '$id' and company = '$company_id' and id not in (select distinct(approval_number) from sma_pettycash where exp_type = 'C' and approval_number !='734' )  ";
					$sql = "SELECT a.dated, a.id , `values` as ap_amount, b.approval_hdr_id, b.supplier_name
					FROM `sma_approval_memo` a, sma_approval_details b 
					where a.id =  b.approval_hdr_id and overhead_exp = 'Y' and approval_status = 'Approved' 
					and b.supplier_name = '$id' and a.company = '$company_id' and b.values > 0 ";
			//echo $sql;	
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){
						$ap_id = $r2['id'];
						$ap_amount = $r2['ap_amount'];
						$sql = " select sum(b.amount) as amount from sma_pettycash a, sma_pettycash_exp b where a.id = b.approval_ref_no and a.exp_type = 'C' and a.approval_number ='$ap_id' ";
						$q3 	= mysqli_query($con, $sql);
						$r3 = mysqli_fetch_array($q3);
						$amount = $r2['amount'];
						if( $ap_amount <= $amount || $amount =0 ){
							continue;
						}
						
						$dated = date('d-m-Y', strtotime($r2['dated']));
						$ap_id = $r2['id'];
					?>
				<option value="<?php echo $ap_id;?>" > <?php echo $ap_id .' | '.$dated;?></option>
				<?php } ?>
			</select>		
<?php
//echo $sql = "SELECT dated, id FROM `sma_approval_memo` where overhead_exp = 'Y' and approval_status = 'Approved' and supplier_name = '$id' and company = '$company_id' and id not in (select distinct(approval_number) from sma_pettycash where exp_type = 'C' ) ";
//$value = $sql;
	    echo $value;
    }						
?>


