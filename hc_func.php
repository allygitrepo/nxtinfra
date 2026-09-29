<?php session_start();
	include('../dbcon.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="sub_group" id="sub_group" required="true" onchange="getsubitem(this.value)" >
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
		$value ='<select class="form-control" name="item_name" required="true" onchange="getdesc(this.value)" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_product where sub_group = '$id' order by name";
		$q2  = mysqli_query($con, $sql);
		while($row = mysqli_fetch_object($q2)){ 
			$description = $row->description;
            $id = $row->id;
			$name = $row->name;
            $uom = $row->uom;
			$hsn_code = $row->hsn_code;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';
//		$value = $sql;
        echo $value;
    }

   if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_product where id = '$id'";
		$q2  = mysqli_query($con, $sql);
		$row = mysqli_fetch_object($q2);
			$description = $row->description;
            $id = $row->id;
			$name = $row->name;
            $uom = $row->uom;
			$hsn_code = $row->hsn_code;
        $value = '<div class="form-group col-md-12">
					<label for="itemDescription" class="col-sm-3 control-label">Description</label> 
						<div class="col-sm-9">
							<input type="text" class="form-control" name="description" placeholder="Item Description..." value = "'. $description . '" >
						</div>
						</div>
						<div class="form-group col-md-12">
							<label for="itemUnits" class="col-sm-3 control-label">Units</label>
						<div class="col-sm-4">
							<input type="text" class="form-control"  name="unit" value = "'. $uom . '">
						</div>
						</div>'; 
									
//		$value = $sql;
        echo $value;
    }
	
	
?>

