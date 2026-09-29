<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<label class="col-md-2 control-label">Budget Name </label><div class="col-md-2">
					<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget(this.value)" required="true" >
									<option value=""> Select </option>';

	    $sql = "select distinct(b.id), b.name from sma_budget a, sma_budget_name b  where project = '$id' and a.budget_name = b.id ";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
			$id = $r2->id;
            
            $value .= "<option value='".$id."'>".$id. ' ' .$name."</option>";
        };
		$value .= '</select></div>';

//$value=$sql;

        echo $value;
    }
	
	if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$project = $_POST['project'];
		//$account_year = $_POST['account_year'];
		$value = '';
        
		$value ='<label class="col-md-2 control-label">Budget Head</label><div class="col-md-3">';
		$value .='<select class="form-control" name="budget_head_id" id="budget_head_id" required="true" onchange="getavailbudget(this.value)" >
					<option value=""> Select </option>';
	    $sql = "select * from sma_budget_category where id = '$id' and locked != 'Y' ";
//$value1 = $sql;	
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$balance_budget = $r2->balance_budget;
			$budget_category  = $r2->budget_category;
			$budget_head_id   = $r2->id;

			$sql = "select * from sma_budget_category where id = '$budget_category' ";
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_head    = $r3->category;
			
            $value .= "<option value='".$budget_head_id."'>".$budget_head."</option>";
        };
		
		$value .= '</select>';

		$value .= "</div>";
		
		
//$value = $value1;
		echo $value;
		
	}
	
	if(isset($_POST['sub22'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = "select * from sma_budget where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		//$balance_budget = $r2->balance_budget;
		$total_budget 	= $r2->total_budget;
		$used_budget	= $r2->used_budget;
		$balance_budget = $total_budget - $used_budget;
		$budget_head    = $r2->budget_category;
        
		$value .='<label class="col-md-2 control-label">Budget Available</label><div class="col-md-2">';
		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
		$value .= "</div>";	
		
		echo $value;
		
	}

	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$approval_hdr_id 		= $_POST['approval_hdr_id'];
		$quote_ref_no 			= $_POST['quote_ref_no'];
		$vendor_selected 	= $_POST['vendor_selected'];
		$values 			= $_POST['values'];
		$remarks 			= $_POST['remarks'];
		
		$sql = "insert into `sma_approval_details` (approval_hdr_id, quote_ref_no, vendor_selected,  values, remarks ) values ('$approval_hdr_id', '$quote_ref_no', '$vendor_selected', '$values','$remarks')";
//$value1=$sql;	
		$r2 = mysqli_query($con, $sql);

		$sql="SELECT * from sma_approval_details where approval_hdr_id = '$approval_hdr_id' ";
//$value1=$sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
//$value = $value1;
		
		$value = "<script>window.location.href='edit.php?sub=edit&id=$approval_hdr_id&active=active';</script>";
	
		echo $value;
		
	}	
	
	
	if(isset($_POST['sub4'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$company_id = $_POST['company_id'];
		$checked= sizeof($company_id);
			
			
			
			if($_POST['id'] != ''){
?>		
					<div class="col-md-3">
								  <?php 
						if($checked>=1){
							$sql ='';	
							foreach ($_POST['company_id'] as $company_id){		  
									$sql .= "select * from sma_user where active = '1' and FIND_IN_SET($company_id, company_id) 
											union ";
							//echo $sql;
							}
						}

							$sql .= ' select * from sma_user where id = 0 order by username';
						//echo $sql;	
									$q2 = mysqli_query($con, $sql);
									?>
							<div class="col-md-5">
								<div style="height:300px;width:550px;overflow:scroll;border:1px #999;">
									<table id="myTable" class="table table-hover panel panel-default table-bordered" >
								
										<tbody>
												<?php 
													while($r2 = mysqli_fetch_array($q2)){ 
												?>
												<tr>
													<td width="20%" style="text-align:left"><?php echo $r2['username'];?></td>
													<td width="10%" style="text-align:left"><?php echo $r2['userid'];?></td>
													<td width="5%" style="text-align:center"><input type="checkbox" id="user_group_id" name="user_group_id[]" <?php if ($r2['id'] == $user_group_id){echo "checked"; }?> value="<?php echo $r2['id'];?>" /> </td>
													
												</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
							
					</div>
							
<?php
			}
		}
	
?>

