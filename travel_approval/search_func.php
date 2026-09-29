<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$searchf = $_POST['id'];
		
		if($searchf=='S' || $searchf=='C'){
			$value = '';
			$value .='<div class="col-md-3">';
			//$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= '<select class="form-control select2-123" name="search_data">';
				$sql = "select * from company order by comp_name ";
				$q2 	= mysqli_query($con, $sql);
										
			$value .='<option value=""> Select </option>';
					while($r2 = mysqli_fetch_array($q2)){ 	
			$value .='<option value="'. $r2['comp_id'].'">'.$r2['comp_name'].'</option>';
					}
			$value .='</select>';
			$value .= "</div>";

		}
		else if($searchf=='E'){
			$value = '';
			$value .='<div class="col-md-3">';
			$value .= '<select class="form-control select2-123" name="search_data">';
			$sql 	= "select * from sma_user order by username ";
			$q2 	= mysqli_query($con, $sql);
					
			$value .='<option value=""> Select </option>';
				while($r2 = mysqli_fetch_array($q2)){ 	
			$value .='<option value="'. $r2['id'].'">'.$r2['username'].'</option>';
					}
			$value .='</select>';
			$value .= "</div>";

		}
		else if($searchf=='N' || $searchf=='A' || $searchf=='P'){
			$value = '';
			$value .='<div class="col-md-2">';
			$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= "</div>";	
		}
		else if($searchf=='D'){
			$value = '<label class="col-lg-1 control-label">From</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';
			$value .= '<label class="col-lg-1 control-label">To</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';	
		}

		if($searchf=='C'){
			$value .= '<div class="form-group">
				<div class="col-md-2">
					<label class=" control-label">Start.Date</label>
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';
			$value .= '
				<div class="col-md-2">
					<label class="control-label">End.Date</label>
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>
			</div>	';	
		}
		
//$value=$sql;

        echo $value;
    }
	
?>	