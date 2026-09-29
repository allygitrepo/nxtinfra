<?php 

	session_start();
	include('../dbcon.php');
	//include('function.php');
	
?>
<?php
	
    if(isset($_POST['sub1'])){
    
        $budget_code = $_POST['id'];
		if($_POST['id'] == ''){$budget_code = '';}
		
		$value = '';

		$sql = "select distinct(budget_name) as budget_name , budget_code, budget_head from sma_budget where 1 and budget_code = '$budget_code' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 = mysqli_fetch_array($q22);
		$budget_name = $r22['budget_name'];
		$budget_head = $r22['budget_head'];
		
		$sql = "select * from sma_budget_name where 1 and id = '$budget_name' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 = mysqli_fetch_array($q22);
		$budget_name_id = $r22['id'];
		$budget_name = $r22['name'];
?>		
			<label class=" col-md-1 control-label">CC Group</label>
			<div class="col-md-2">
			<input type="hidden" class="form-control" name="budget_name" id="budget_name" readonly value="<?php echo $budget_name_id;?>" >
			
			<input type="text" class="form-control"  readonly value="<?php echo $budget_name;?>" >
			</div>
			
			<label class="control-label col-md-1">CC Sub Group</label>
			<div class="col-md-4">
			<input type="text" class="form-control" name="budget_head" id="budget_head" readonly value="<?php echo $budget_head;?>" >
			</div>
			
<?php							
    }
	
    if(isset($_POST['sub2'])){
    
        $company_id = $_POST['id'];
		
		$value = '';
?>	
		<select class="form-control select2" name="budget_code" id="budget_code" required="true" onchange="getaccount_data(this.value)" >
			<option value=""> Select </option>
			<option value="0"> NA </option>
			<?php $sql = "select distinct(budget_code) as budget_code from sma_budget where 1 and project = '$company_id' order by budget_code ";
			$q22 	= mysqli_query($con, $sql);
			while($r22 = mysqli_fetch_array($q22)){ ?>
			<option value="<?php echo $r22['budget_code'];?>" ><?php echo $r22['budget_code'];?></option>
			<?php } ?>
		</select>
<?php
	}

if(isset($_POST['sub3'])){
    
        $company_id = $_POST['id'];
?>
		
		<div class="form-group">
							<label class="col-md-2 control-label">Bank Name</label>
							<div class="col-md-4">
								
										<select class="form-control" id="bank_id" name="bank_id" >
											<option value="">Select..</option>	
										<?php
											$sql="SELECT * FROM account_mst where account_type = 'B' and company_id = '$company_id' ORDER BY account_name ASC";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['bank_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['account_name'] ?></option>
											<?php } ?>
										</select>
								
							</div>
						</div>
<?php
	}

    if(isset($_POST['sub4'])){
    
        $account_name = $_POST['id'];
        $sql="SELECT * FROM account_mst where 1 and account_name = '$account_name' ";
		mysqli_query($con, $sql);
		$rowaffect = mysqli_affected_rows($con);
		if($rowaffect>0){
		    echo "Error: Already available ... ";
		}
		
    }
    
    if(isset($_POST['sub5'])){
    
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

