<?php session_start();
	include('../dbcon.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="party_city" id="party_city" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from cities where states_id = '$id' order by city_name";
		$q2  = mysqli_query($con, $sql);
		while($row = mysqli_fetch_object($q2)){ 
			$city_name = $row->city_name;
            $id = $row->id;
            $value .= "<option value='".$id."'>".$city_name."</option>";
        };
		$value .= '</select>';
//		$value = $sql;
        echo $value;
    }
	
	
?>

