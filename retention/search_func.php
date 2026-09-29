<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$searchf = $_POST['id'];
		
		if($searchf=='S' || $searchf=='B' ){
			$value = '';
			$value .='<div class="col-md-4">';
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
		else if( $searchf=='N' || $searchf=='P' || $searchf=='M' ){
			$value = '';
			$value .='<div class="col-md-2">';
			$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= "</div>";	
		}
		else if($searchf=='D' || $searchf=='I'){
			//echo " HEllo World...";
			$value = '<div class="col-md-2">
						<label class=" control-label">From </label>
						<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';
			$value .= '<div class="col-md-2">
						<label class=" control-label">To</label>
						<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';	
		}
		else {
			$value ='<input type="hidden" id="search_data" name="search_data" value="" >';
		}	
		if($searchf=='B'){
			//$value .= '<div class="form-group">';
			$value .= '<div class="col-md-2">
						<label class=" control-label">From </label>
						<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';
			$value .= '<div class="col-md-2">
						<label class=" control-label">To</label>
						<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>
			';	

		}
//$value=$sql;

        echo $value;
    }
	
	
	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc 	 = $_POST['party_id_doc'];
		$company_idd_doc = $_POST['company_idd_doc'];
		
		if($_POST['id'] == ''){$id = '';}
?>			
			<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Party Name</th>
					<th>Document Type</th>
					<th>File Path</th>
					<th>File Name</th>
					<th>Inward No</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>

<?php				
		$sql = "SELECT a.id, a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><a href="<?php echo $baseurl.'dms/'.$row['file_path'].'/'.$row['file_name'];?>" target="_blank"><?php echo $row['file_name'];?></a> </td>
					<td width="10%" ><?php echo $row['inward_no'];?></td>
					
					<td width="05%">
						<input type="checkbox" name="party_doc[]" <?php echo $checked; ?> id="party_doc" value="<?php echo $row['id']; ?>" >
					</td>
			</tr>
<?php			
			}
			
			$value = '';
					
//$value=$sql;

        echo $value;
    }
	
?>	