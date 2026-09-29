<?php 

	session_start();
	include('../dbcon.php');
	//include('function.php');
	
?>
<?php
	
    if(isset($_POST['sub1'])){
    
        $budget_head = $_POST['id'];
		$budget_name = $_POST['budget_name'];
		if($_POST['id'] == ''){$budget_head = '';}
		
		$value = '';

		$sql = "select * from sma_budget where 1 and budget_head = '$budget_head' and budget_name = '$budget_name' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 = mysqli_fetch_array($q22);
		$budget_code = $r22['budget_code'];

?>		
			
			<div class="col-md-2">
				<label class=" control-label">Posting A/c</label>
				<input type="text" class="form-control" name="budget_code" id="budget_code" readonly value="<?php echo $budget_code;?>" >
			</div>
<?php							
    }
	
?>

<?php
	
    if(isset($_POST['sub2'])){
    
        $budget_name = $_POST['id'];
		if($_POST['id'] == ''){$budget_name = '';}
		
		$value = '';

?>		
			<div class="col-md-4">
			<label class=" control-label">Cost Center Sub Group</label>
			<select class="form-control select2" name="budget_head" id="budget_head" required="true" onchange="getaccount_data(this.value)" >
				<option value=""> Select </option>
				<?php $sql = " select distinct(budget_head) from sma_budget where 1 and budget_name = '$budget_name' order by budget_head ";
					$q22 	= mysqli_query($con, $sql);
					while($r22 = mysqli_fetch_array($q22)){ ?>
					<option value="<?php echo $r22['budget_head'];?>" ><?php echo $r22['budget_head'];?></option>
				<?php } ?>
			</select>
			</div>
								
<?php							
    }
	
?>

<?php
	
    if(isset($_POST['sub3'])){
    
        $budget_name 	= $_POST['budget_name'];
		$company_id 	= $_POST['company_id'];
		$product_id 	= $_POST['product_id'];
		if($_POST['budget_name'] == ''){$budget_name = '';}
//	echo $sql = " select * from sma_budget where 1 and budget_name = '$budget_name' and project = '$company_id' order by budget_head ";
?>		
			<select class="form-control select2 " name="cost_center_sub_group" id="cost_center_sub_group"  onchange="saveToDatabase(this.value,'budget_id', <?= $company_id;?>, <?= $product_id;?> )" >
				<option value=""> Select </option>
				<option value=""> NA </option>
				<?php $sql = " select * from sma_budget where 1 and budget_name = '$budget_name' and project = '$company_id' order by budget_head ";
					$q22 	= mysqli_query($con, $sql);
					while($r22 = mysqli_fetch_array($q22)){ ?>
					<option value="<?php echo $r22['id'];?>" ><?php echo $r22['budget_head'];?></option>
				<?php } ?>
			</select>
								
<?php							
    }
	
	if(isset($_POST['sub4'])){
	
		$company_id = $_POST["company_id"];
		$product_id = $_POST["product_id"];
		$column		= $_POST["column"];
		$editval	= $_POST["editval"];
		
		$sql = "UPDATE `sma_product_cost_center` set $column = '$editval' WHERE company_id = '$company_id' and product_id = '$product_id' ";
		$re = mysqli_query($con, $sql);
		$err = mysqli_error($con);
		
		$sql = " select * from sma_budget where 1 and id = '$editval' ";
		$q22 	= mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r22 = mysqli_fetch_array($q22);
		$budget_code = $r22['budget_code'];
		
		echo $budget_code;
		
	}
	
?>

