<?php session_start();
	include('dbcon.php');
	//include('function.php');
?>

<?php
	
    if(isset($_POST['sub1'])){
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql="Select * from bom_group WHERE stage_no = '$id' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
		
//		$value ='<label  class="col-sm-2 control-label"> Our Qtote.Ref.No.</label><div class="col-lg-3">';
		$value .= '<select class="form-control"  id="group_no" name="group_no" onchange="getbomitem(this.value)">';
        $value .= '<option value="0"> Select Group</option>';
        while($row = mysql_fetch_object($query01)){
            $group_no = $row->group_no;
            $group_name = $row->group_name;
            $value .= "<option value='".$group_no."'>".$group_name."</option>";
        }
        $value .= '</select>';
	//	$value .= '</div></div>';
	$value = $sql;
        echo $value;
    }

	if(isset($_POST['sub2'])){
        $id 		= $_POST['id'];
		$stage_no   = $_POST['stage_no'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql="Select * from bom_item_rate WHERE stage_no = '$stage_no' and group_no = '$id' ";
		
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
		
		$value .= '<select class="form-control"  id="item_description" name="item_description" onchange="getspec(this.value)">';
        $value .= '<option value="0"> Select Item</option>';
        while($row = mysql_fetch_object($query01)){
            $id 			= $row->id;
            $item_description 	= $row->item_description;
            $value .= "<option value='".$id."'>".$item_description."</option>";
        }
        $value .= '</select>';
	//$value .= '</div></div>';
	//$value = $sql;
        echo $value;
    }
	
	if(isset($_POST['sub3'])){
        $id 	= $_POST['id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql = "Select * from bom_item_rate WHERE id = '$id' ";
		
		$query01 	= mysql_query($sql);
		echo mysql_error();
		$error 		= mysql_error();
		
        while($row = mysql_fetch_object($query01)){
            $id 		= $row->id;
            $item_no 	= $row->item_no;
            $code_no 	= $row->code_no;
            $rate	 	= $row->rate;
            $specification_size = $row->specification;
        }
        $value = '<label class="col-lg-2 control-label">Specification / Size</label>
					<div class="col-lg-2">
					<input type="text" class="form-control" style="text-align:left" id="specification_size" name="specification_size" placeholder="" autocomplete="off" value="'.$specification_size.'">
				</div>';
				
        echo $value;
    }
	
	if(isset($_POST['sub4'])){
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}

		$sql="Select * from bom_quotation_detail WHERE quote_no_hdr = '$id' ";
		$query01 = mysql_query($sql);

		echo mysql_error();
		$error = mysql_error();

        while($row = mysql_fetch_object($query01)){
            $rate 	 = $row->rate;
            $material_cost = material_cost + $rate;
        }

		//$value = $sql;
		$value = '<input type="text" class="form-control" style="text-align:right" id="material_cost" name="material_cost" autocomplete="off" value="'. $material_cost .'">';
        echo $value;
    }
	
	if(isset($_POST['sub5'])){
        $car_travel = $_POST['car_travel'];
		$over_head  = $_POST['over_head'];
		$pit_height = $_POST['pit_height'];
		
		//if($_POST['id'] == ''){$id = '';}

		$clear_well_height = ($car_travel + $over_head + $pit_height) / 1000;
        
		$value = '<input type="text" class="form-control" style="text-align:right" id="clear_well_height" name="clear_well_height" readonly value="'. $clear_well_height .'">';
        echo $value;
    }								
			
	if(isset($_POST['sub6'])){
        $sswidth = $_POST['sswidth'];

		$mrwidth = $sswidth + 1500;
        
		$value = '<input type="text" class="form-control" style="text-align:right" id="machine_room_size_width" name="machine_room_size_width" required="true" value="'. $mrwidth .'">';
        echo $value;
    }		
	
	if(isset($_POST['sub7'])){
        $ssdepth = $_POST['ssdepth'];

		$mrdepth = $ssdepth + 1500;
        $mrheight = 2750;
		$value = '<div class="col-md-1" class="input-append"><input type="text" class="form-control" style="text-align:right" id="machine_room_size_depth" name="machine_room_size_depth" required="true" value="'. $mrdepth .'">
		</div>	
		<div class="col-md-1" class="input-append">
		<input type="text"class="form-control" id="machine_room_size_height" name="machine_room_size_height" placeholder="Height" autocomplete="off" value="'. $mrheight .'"></div>	';
        echo $value;
    }

	if(isset($_POST['sub8'])){
        $passenger 	= $_POST['passenger'];
		$lift_type	= $_POST['lift_type'];
		if ($lift_type == 1){
			if ($passenger >= 4 && $passenger <= 6) {
				$carentrance = 700;
				$car_entrance_height = 2000;
			}
			if ($passenger >= 8 && $passenger <= 10) {
				$carentrance = 800;
				$car_entrance_height = 2100;
			}
			if ($passenger >= 13 && $passenger <= 15) {
				$carentrance = 900;
				$car_entrance_height = 2200;
			}
			if ($passenger >= 16 && $passenger <= 20) {
				$carentrance = 1000;
				$car_entrance_height = 2300;
			}
		}

		if ($lift_type == 2){
			if ($passenger == 4) {
				$carentrance = 700;
				$car_entrance_height = 2000;
			}
			if ($passenger >= 5) {
				$carentrance = 760;
				$car_entrance_height = 2100;
			}
			if ($passenger >= 8) {
				$carentrance = 800;
				$car_entrance_height = 2200;
			}
			if ($passenger >= 13) {
				$carentrance = 900;
				$car_entrance_height = 2300;
			}
			if ($passenger >= 16) {
				$carentrance = 1000;
				$car_entrance_height = 2400;
			}
		}

		$value = '<div class="col-md-1" class="input-append">
<input type="text" class="form-control" style="text-align:right" id="car_entrance_width" name="car_entrance_width" required="true" value="'. $carentrance .'"></div>';
		
		$value .= '<div class="col-md-1" class="input-append">
					<input type="text"class="form-control" id="car_entrance_height" name="car_entrance_height" placeholder="Height" autocomplete="off" value="'.$car_entrance_height.'"></div>';
		
        echo $value;
    }

	if(isset($_POST['sub9'])){
        $passenger = $_POST['passenger'];

		$capacity = $passenger * 68;
        
		$value = '<input type="text" class="form-control" style="text-align:right" id="capacity" name="capacity" required="true" value="'. $capacity .'">';
        echo $value;
    }

	if(isset($_POST['sub10'])){
        $passenger 	= $_POST['passenger'];
		$lift_type	= $_POST['lift_type'];
		if ($lift_type == 1){
			if ($passenger >= 4) {
				$landingclear = 700;
				$landing_clear_open_height = 2000;
			}
			if ($passenger >= 8) {
				$landingclear = 800;
				$landing_clear_open_height = 2100;
			}
			if ($passenger >= 13) {
				$landingclear = 900;
				$landing_clear_open_height = 2200;
			}
			if ($passenger >= 16) {
				$landingclear = 1000;
				$landing_clear_open_height = 2300;
			}
		}

		if ($lift_type == 2){
			if ($passenger == 4) {
				$landingclear = 760;
				$landing_clear_open_height = 2000;
			}
			if ($passenger >= 6) {
				$landingclear = 760;
				$landing_clear_open_height = 2100;
			}
			if ($passenger >= 8) {
				$landingclear = 800;
				$landing_clear_open_height = 2200;				
			}
			if ($passenger >= 13) {
				$landingclear = 900;
				$landing_clear_open_height = 2300;				
			}
			if ($passenger >= 16) {
				$landingclear = 1000;
				$landing_clear_open_height = 2400;				
			}
		}

		$value = '<div class="col-md-1" class="input-append">
				<input type="text" class="form-control" style="text-align:right" id="landing_clear_open_width" name="landing_clear_open_width" required="true" value="'. $landingclear .'"></div>';

        $value .= '<div class="col-md-1" class="input-append"><input type="text"class="form-control" id="landing_clear_open_height" name="landing_clear_open_height" placeholder="Height" autocomplete="off" value="'. $landing_clear_open_height.'"></div>';
		
		echo $value;
    }

    if(isset($_POST['sub11'])){
        $category = $_POST['category'];
		if($_POST['category'] == ''){$category = '';}
//		$sql="Select a.id, a.category_id, a.sub_group_id, a.item_id, a.lift_type, a.passenger, a.speed, a.size_result, b.item_description as group_name from bom_item_group a, bom_item_rate b WHERE a.item_id = b.id and a.category_id = '$category' ";
		$sql="Select * FROM `sub_group`  where category_id = '$category' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
		
		$value .= '<select class="form-control" id="group_id" name="group_id" >';
        $value .= '<option value="0"> Select Group Item</option>';
        while($row = mysql_fetch_object($query01)){
            $id 	= $row->id;
            $group_name = $row->sub_group_name;
            $value .= "<option value='".$id."'>".$group_name.' | '.$id."</option>";
        }
        $value .= '</select>';
        echo $value;
    }

	if(isset($_POST['sub12'])){
		$id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "Select * from bom_item_rate WHERE id = '$id' ";
		
		$query01 	= mysql_query($sql);
		echo mysql_error();
		$error 	= mysql_error();
	    $row    = mysql_fetch_object($query01);
        $id 	= $row->id;
        $size 	= $row->specification;
        
		$value = '<input type="text" class="form-control" id="size_result" name="size_result" autocomplete="off" value="' . $size.'">';
        echo $value;
    }

	if(isset($_POST['sub13'])){
		$height 	= $_POST['height'];
		$travel 	= $_POST['travel'];
		
		$tottravel = $height + $travel;
		
		$value = '	<input type="text" class="form-control" style="text-align:left" id="car_travel" name="car_travel" onchange="getcwheight(this.value)" value="' . $tottravel. '">';

							
        echo $value;
    }

	if(isset($_POST['sub14'])){
		$mrl 	= $_POST['mrl'];
		if($mrl == 4){
			$value = '<label class="col-lg-1 control-label">Machine.Location<span class="f_req">*</span></label>
							<div class="col-md-2" class="input-append">
							<select class="form-control" id="machine_location" name="machine_location" readonly ><option value="0"> Select</option><option </select>	</div>';
			$value .= '<label class="col-lg-7 control-label">Machine Room Size</label> <div class="col-md-1" class="input-append" ></div>	';

		}
		else {
			$value = '<label class="col-lg-1 control-label">Machine.Location<span class="f_req">*</span></label>
							<div class="col-md-2" class="input-append">
							<select class="form-control" id="machine_location" name="machine_location" ><option value="0">Select</option><option value="1" >Above</option><option value="2">Side</option><option value="3" >Down</option></select></div>
			<label class="col-lg-2 control-label">Machine Room Size</label>
							<div class="col-md-1" class="input-append">
							<span id="mrsizew">
								<input type="text"class="form-control" id="machine_room_size_width" name="machine_room_size_width" placeholder="Width" autocomplete="off" value="">
							</span>	
							</div>	
							<span id="mrsized">
							<div class="col-md-1" class="input-append">
								<input type="text"class="form-control" id="machine_room_size_depth" name="machine_room_size_depth" placeholder="Depth" autocomplete="off" value="">
							</div>	
							<div class="col-md-1" class="input-append">
								<input type="text"class="form-control" id="machine_room_size_height" name="machine_room_size_height" placeholder="Height" autocomplete="off" value="">
							</div>	
							</span>';
		}
        echo $value;
    }

    if(isset($_POST['sub15'])){
        $csw 				= $_POST['csw'];
		$passenger 			= $_POST['passenger'];
		$lift_type 			= $_POST['lift_type'];
        $cwt 				= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		
	//	$value ='';
		
//
		$sql="SELECT shaft_width_max, shaft_width_min FROM `shaftsizes` WHERE person = '$passenger' and (shaft_width_min >= '$csw') and 
		lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		
		$query01 = mysql_query($sql);
		$row = mysql_fetch_object($query01);
		$shaft_width_max = $row->shaft_width_max;
        if ($shaft_width_max > 0 ){
			$shaft_width_max = $shaft_width_max - 50;
//Ravi			$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and shaft_width_min >= '$csw' and shaft_width_min <= '$shaft_width_max' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
			$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and shaft_width_min <= '$csw' and shaft_width_max >= '$csw' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		}
		else{
			// while ($csw % 50 != 0) {
			// 	$csw++;
			// }
			 $sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and shaft_width_min <= '$csw' and shaft_width_max >= '$csw' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		}
		
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
		
		$value .= '<select class="form-control" id="cabin_size_width" name="cabin_size_width" onchange="getcsw1(this.value)" required >';
		//$value .= ' oninvalid="this.setCustomValidity'. "('Car Size - Width cannot be empty.')". '" ';
		//$value .= ' onchange="this.setCustomValidity' . "('')" . '" >';
		$value .= '<option value="">Select</option>';
        while($row = mysql_fetch_object($query01)){
            $id 	= $row->id;
			$cabin_inside_width = $row->cabin_inside_width;
            $value .= "<option value='".$cabin_inside_width."'>".$cabin_inside_width."</option>";
        }
        $value .= '</select>';
//$abc .= ' oninvalid="this.setCustomValidity'. '("Car Size - Width cannot be empty.")'. '" ';
//$abc .= ' onchange="this.setCustomValidity' . "('')" . '" ';
//	$value = $sql1;
	    echo $value;
    }

	
    if(isset($_POST['sub16'])){
        $csd 			= $_POST['csd'];
		$passenger 		= $_POST['passenger'];
		$lift_type 		= $_POST['lift_type'];
        $cwt 			= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		$cabin_size_width 	= $_POST['cabin_size_width'];

		//$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and (shaft_depth_min >= '$csd' and shaft_depth_min <= '$csd') and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and (shaft_depth_min >= '$csd') and cabin_inside_width = '$cabin_size_width' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
		
		$value = '<div class="col-md-1" class="input-append"><select class="form-control" id="cabin_size_depth" name="cabin_size_depth"  required>';
        $value .= '<option value="">Select</option>';
        while($row = mysql_fetch_object($query01)){
            $id 	= $row->id;
			$cabin_inside_depth = $row->cabin_inside_depth;
            $value .= "<option value='".$cabin_inside_depth."'>".$cabin_inside_depth."</option>";
        }
        $value .= '</select></div>';
		
			if ($passenger >= 4) {
				$cabin_size_height = 2000 + 200;
			}
			if ($passenger >= 8) {
				$cabin_size_height = 2100 + 200;
			}
			if ($passenger >= 13) {
				$cabin_size_height = 2200 + 200;
			}
			if ($passenger >= 16) {
				$cabin_size_height = 2300 + 200;
			}

		$value .= '<div class="col-md-1" class="input-append">
					<input type="text"class="form-control" id="cabin_size_height" name="cabin_size_height" placeholder="Height" autocomplete="off" value="'.$cabin_size_height.'">
					</div>';
//$value = $sql;
		echo $value;
    }

	
    if(isset($_POST['sub17'])){
        $passenger  = $_POST['passenger'];
		$lift_type	= $_POST['lift_type'];

		if ($lift_type == 1){
			if ($passenger >= 4) {
				$cabin_size_height = 2000 + 200;
			}
			if ($passenger >= 8) {
				$cabin_size_height = 2100 + 200;
			}
			if ($passenger >= 13) {
				$cabin_size_height = 2200 + 200;
			}
			if ($passenger >= 16) {
				$cabin_size_height = 2300 + 200;
			}
		}

		if ($lift_type == 2){
			if ($passenger == 4) {
				$cabin_size_height = 2000 + 200;
			}
			if ($passenger >= 6) {
				$cabin_size_height = 2100 + 200;
			}
			if ($passenger >= 8) {
				$cabin_size_height = 2200 + 200;				
			}
			if ($passenger >= 13) {
				$cabin_size_height = 2300 + 200;		
			}
			if ($passenger >= 16) {
				$cabin_size_height = 2400 + 200;		
			}
		}

		$value .= '<input type="text"class="form-control" id="cabin_size_height" name="cabin_size_height" placeholder="Height" autocomplete="off" value="'.$cabin_size_height.'">';

		echo $value;
    }

	if(isset($_POST['sub18'])){
		
        $csd 				= $_POST['csd'];
		$passenger 			= $_POST['passenger'];
		$lift_type 			= $_POST['lift_type'];
        $cwt 				= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		$cabin_size_width 	= $_POST['cabin_size_width'];
		
//		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and (shaft_width_min >= '$csd') and cabin_inside_width = '$cabin_size_width' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and cabin_inside_width = '$cabin_size_width' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();

		$num_rows = mysql_num_rows($query01);
//$val = $sql;
		if($num_rows == 0){ 
			
//			$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and (shaft_width_max >= '$csd') and cabin_inside_width = '$cabin_size_width' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
			$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and cabin_inside_width = '$cabin_size_width' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
			$query01 = mysql_query($sql);
			echo mysql_error();
			$error = mysql_error();
		}
		//$value = '<div class="col-md-1" class="input-append">';
		$value = '<select class="form-control" id="cabin_size_depth" name="cabin_size_depth" onchange="getshaftdepth(this.value);" >';
        $value .= '<option value="0">Select car depth</option>';
        while($row = mysql_fetch_object($query01)){
            $id 	= $row->id;
			$cabin_inside_depth = $row->cabin_inside_depth;
            $value .= "<option value='".$cabin_inside_depth."'>".$cabin_inside_depth."</option>";
        }
        $value .= '</select>';

//$value .= '</div>';
		
//$value = $val . ' ' . $sql;
	
	    echo $value;
    }
	
	if(isset($_POST['sub19'])){
        $build_type		= $_POST['build_type'];
		if($build_type 	== '1' || $build_type == '2' || $build_type == '5'){
			$selection 	= '2';
		}
		
		$value = "<select class='form-control'  id='selection' name='selection' >
					<option value='0'> Select </option>	";
		
		if ($selection == '2'){ 
			$value .= "<option value='1' >Down Collective</option>";
			$value .= "<option value='2' selected>Full Collective</option>";
		}
		else { 
			$value .= "<option value='1' selected >Down Collective</option>";
			$value .= "<option value='2' >Full Collective</option>";
		}
		
        $value .= '</select>';
		
//$value = $selection . ' ' . $build_type;
    
		echo $value;
    
	}
	
	
	if(isset($_POST['sub20'])){
        $lift_type		= $_POST['lift_type'];
		
		$value = '<select class="form-control"  id="type_of_car_door" name="type_of_car_door" onblur="getcsw(); getcsd();getlandoor();">
					<option value="0"> Select</option>';
		
		if ($lift_type == '1'){ 
			$value .= "<option value='1' selected >Centre Opening</option>
						<option value='2' >Telescopic </option>";
		}
		else {
			$value .= "<option value='3' selected >Collapsible</option>
						<option value='4' >Imperforated</option>";
		}
		
        $value .= '</select>';
		
//$value = $selection . ' ' . $build_type;
    
		echo $value;
    
	}
	
	if(isset($_POST['sub21'])){
        $lift_type			= $_POST['lift_type'];
		$type_of_car_door	= $_POST['type_of_car_door'];
		$value = '<select class="form-control"  id="type_of_landing_door" name="type_of_landing_door" >
					<option value="0"> Select</option>';
		if ($lift_type == '1'){ 
				if ($type_of_car_door == '1'){ 
					$value .= "<option value='1' selected >Centre Opening</option>";
				}
				if ($type_of_car_door == '2'){ 
					$value .= "<option value='2' selected >Telescopic </option>";
				}
				else {	
					$value .= "<option value='1' selected >Centre Opening</option>";
				}
		}
		else {
			$value .= "<option value='3' selected >Collapsible</option>
						<option value='4' >Swing Door</option>";
		}
		
        $value .= '</select>';
		
//$value = $selection . ' ' . $build_type;
    
		echo $value;
    
	}
	
if(isset($_POST['sub22'])){
        $speed			= $_POST['speed'];

		if ($speed >= '1.5'){ 
		$value = '<select class="form-control" id="machine" name="machine" >
			<option value="0"> Select</option>';
			$value .= "<option value='1' >GEARED</option>";
			$value .= "<option value='2' selected >GEARELESS </option>";
			$value .= "<option value='3' >HYDRAULIC</option>";
			$value .= "<option value='4'>MRL</option>";
			$value .= '</select>';
			echo $value;
		}
		
	}
	
								
	if(isset($_POST['sub23'])){
		
        $csd 				= $_POST['csd'];
		$csw 				= $_POST['csw'];
		$passenger 			= $_POST['passenger'];
		$lift_type 			= $_POST['lift_type'];
        $cwt 				= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		$cabin_size_width 	= $_POST['cabin_size_width'];
		$cabin_size_depth   = $_POST['cabin_size_depth'];
		
		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and cabin_inside_width = '$cabin_size_width' and cabin_inside_depth = '$cabin_size_depth' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();
        while($row = mysql_fetch_object($query01)){
			$shaft_depth_min = $row->shaft_depth_min;
			$shaft_depth_max = $row->shaft_depth_max;
        }
		if ($shaft_depth_max >= $csd && $shaft_depth_min <= $csd){
			$value = '';
			$value = '<input type="hidden" class="form-control" value="">';
		}
		else {
			$value = '<input type="text"class="form-control" readonly style="color:red;" value=" Select Depth Range Between :' . $shaft_depth_min . ' To ' .  $shaft_depth_max . '">';
		}

	//$value = $sql;
	    echo $value;
    }	

	if(isset($_POST['sub24'])){

		$x = $_POST['x'];
		
		if ($x=='Y'){
			$value = '<div class="col-md-2" class="input-append"> 
					<input value="Y" name="angle_width" id="angle_width" type="checkbox" onchange="getchannelwidth(this.value)"> Channel Width Required
					</div>';
		
			$value .= '<div class="col-md-2" class="input-append">
					<input value="Y" name="angle_depth" id="angle_depth" type="checkbox" onchange="getchanneldepth(this.value)"> Channel Depth Required
					</div>';
	    }
		else {$value ='';}

	    echo $value;
    }

	if(isset($_POST['sub25'])){
	
		$passenger 			= $_POST['passenger'];
		$lift_type 			= $_POST['lift_type'];
        $cwt 				= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		$cabin_size_width 	= $_POST['cabin_size_width'];
		$x = $_POST['x'];
		
		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();

		$num_rows = mysql_num_rows($query01);

		if ($x=='Y'){
			$value = '<div class="col-md-1" ><select class="form-control" id="shaft_size_width" name="shaft_size_width_r" onblur="getmrsizew(this.value), getcsw()"  >';
			$value .= '<option value="0">Width</option>';
			while($row = mysql_fetch_object($query01)){
				$id 	= $row->id;
				$shaft_width_min = $row->shaft_width_min;
				$value .= "<option value='".$shaft_width_min."'>".$shaft_width_min."</option>";
			}
			$value .= '</select></div>';
		}	
		else {$value ='';}

	    echo $value;
    }

	
	if(isset($_POST['sub26'])){
	
		$passenger 			= $_POST['passenger'];
		$lift_type 			= $_POST['lift_type'];
        $cwt 				= $_POST['cwt'];
		$car_door_type 		= $_POST['car_door_type'];
		$cabin_size_width 	= $_POST['cabin_size_width'];
		$x = $_POST['x'];
		
		$sql="SELECT * FROM `shaftsizes` WHERE person = '$passenger' and lifttype_no = '$lift_type' and cwt_no = '$cwt' and cardoor_no = '$car_door_type' ";
		$query01 = mysql_query($sql);
		echo mysql_error();
		$error = mysql_error();

		$num_rows = mysql_num_rows($query01);
		if ($x=='Y'){
			$value = '<div class="col-md-1" ><select class="form-control" id="shaft_size_depth" name="shaft_size_depth_r" onblur="getmrsized(this.value), getcsd()" onchange="getshaftdepth(this.value)" >';
			$value .= '<option value="0">Depth </option>';
			while($row = mysql_fetch_object($query01)){
				$id 	= $row->id;
				$shaft_depth_min = $row->shaft_depth_min;
				$value .= "<option value='".$shaft_depth_min."'>".$shaft_depth_min."</option>";
			}
			$value .= '</select></div>';
		}
		else {$value ='';}
		
	    echo $value;
    }

?>

