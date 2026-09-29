<?php 

	session_start();
	include('../dbcon.php');
	//include('function.php');

	$sql = " SELECT from_date, to_date, short_fy_code, finyear_prefix, status FROM `sma_financial_year` where status = 'Y' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$short_fy_code 	= $r2['short_fy_code'];	
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
				<?php //$sql = " select * from sma_budget_subgroup where 1 and budget_name = '$budget_name' order by budget_head "; //and project = '$company_id'
					$sql = " SELECT distinct(a.id), a.budget_name, a.budget_head, a.budget_code  
							FROM sma_budget_subgroup a, sma_budget b 
							WHERE 1 and a.id = b.budget_head AND a.budget_name = b.budget_name AND b.project = '$company_id' 
									AND (b.account_year = '$short_fy_code' || b.account_year = '2024-2025' ) AND a.budget_name = '$budget_name' 
									ORDER BY a.budget_head  ";
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
		
		$sql = " select * from sma_budget_subgroup where 1 and id = '$editval' ";
		$q22 	= mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r22 = mysqli_fetch_array($q22);
		$budget_code = $r22['budget_code'];
		
		echo $budget_code;
		
	}
	
	 if(isset($_POST['sub5'])){
    
		$product_name = $_POST['product_name'];
		
		$value = '';
		$rowaffect=0;
		$sql = "select * from sma_product where 1 and `name` = '$product_name' ";
		$q22 	= mysqli_query($con, $sql);
		$rowaffect 	= mysqli_affected_rows($con);
		$r22 = mysqli_fetch_array($q22);
		$product_name = $r22['name'];
		
		if($rowaffect>0){
			echo $rowaffect;
		}


    }
	
	if(isset($_POST['sub6'])){
    
		$product_group = $_POST['id'];
		
		$sql = "select * from sma_product where 1 and `product_group` = '$product_group' order by name ";
?>		
		<select class="form-control" name="product_name" id="product_name" required onchange="product_dupplicate();" >
			<option value=""> Select </option>
			<?php 
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
			<?php } ?>
		</select>
<?php 
    }
	
	if(isset($_POST['sub7a'])){
    
		$product_group = $_POST['id'];
		$company_id = $_POST['company_id'];
		$sqla = '';
		
		
//echo $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$company_id' ) order by name ) order by product_group ";
		
		//$sql = "select * from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock ) " . $sqla;
		//$sql .= " order by name ";
?>		
		<select class="form-control"  onchange="getproductv(this.value);" name="product_group" required >
			<option value=""> Select </option>
				<option value=""> All </option>
				<?php  $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$company_id' ) order by name )  order by product_group ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['product_group'];?></option>
			<?php } ?>
		</select>
<?php 
    }
	
	if(isset($_POST['sub7'])){
    
		$product_group = $_POST['id'];
		$company_id = $_POST['company_id'];
		$sqla = '';
		if(!empty($product_group)){
			$sqla = " and `product_group` = '$product_group' ";
		}
		
		//$sql = "select * from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock ) " . $sqla;
		//$sql .= " order by name ";
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

	if(isset($_POST['sub8'])){
    
		$product_name 	= $_POST['product_name'];
		$company_id 	= $_POST['company_id'];
		$sqla 	= '';
		$sql 	= " SELECT * FROM `sma_product_open_stock` WHERE product_name = '$product_name' AND project = '$company_id' ";
		mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		echo "";
		if($row_affected>=1){	
			echo "Error :  Duplicate Product...";
		}

    }

    if(isset($_POST['sub9'])){
    
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


