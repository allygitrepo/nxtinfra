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
	
	
?>

