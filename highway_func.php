<?php session_start();
	include('dbcon.php');
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
	
	
?>

