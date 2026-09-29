<?php
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';
?>

<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<?php

	if($_GET['sub']=='list'){

?>
		
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Forwarded <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Forwarded List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
			<?php
			
				$targetpage = "forward_scr.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
			?>	
			
		   </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Inward Date</th>
					<th>Sent By</th>
					<th>For Company</th>
					<th>For Department</th>
					<th>Inward No.</th>
					<th>Sent To</th>
					<th>Mode of Receipt</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
<!--					<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
				<?php
					//$sql="SELECT * from dms_inward order by id desc";
					
					$role			= $_SESSION['role'];
					$user_category	= $_SESSION['user_category'];
					$sql = "SELECT * from sma_workflow where doc_type= 'IN' and user_category = '$user_category' ";
				
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					$user_category 		= $row['user_category'];
					$to_value 			= $row['to_value'];
					$project_manager	= $row['project_manager'];
					$project_incharge 	= $row['project_incharge'];
					$coo_cxo 			= $row['coo_cxo'];
//		echo $role;
					//FIND_IN_SET("q", "s,q,l");
					
					$userid   	    = $_SESSION['usrid'];
					
					$sql="SELECT * from dms_inward where status ='Forwarded' and FIND_IN_SET('$userid', send_to_user) and company in ( $comid ) ";
					$query="SELECT count(*) as num from dms_inward where status ='Forwarded' and FIND_IN_SET('$userid', send_to_user) and company in ( $comid ) ";
											
					
					if ($user =='Admin'){
						$sql="SELECT * from dms_inward where inward_no > 0 ";
						$query="SELECT count(*) as num from dms_inward where inward_no > 0   ";
					}
					
					$_SESSION['sqlex'] = $sql;
					
			//echo $search_data;		
			//echo $sql;
//exit();			
			//echo $query."<BR>";
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages[num];
			//echo $total_pages. ' <<<>>';		
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
					
					
						
					$sql .= ' order by inward_no desc ';
					$sql .= " LIMIT $start, $limit ";
					
					
//echo $sql;			
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	//echo $lastpage;
	//echo $paginate;
	if($lastpage > 1)
	{	
		$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
		// Previous
		if ($page > 1){
			$paginate.= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>previous</a></li>";	}
			
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li></span>";
				}else{
					$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}					
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<li><a href='$targetpage&page=$next'>next</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>next</a></li>";
			}
			
		$paginate.= "</ul></div>";
}
//end page

//echo $sql;
					$result = mysqli_query($con, $sql);
					echo mysqli_error($con);
					
					while($row = mysqli_fetch_array($result)){
						
						$rid = $row['inward_no'];
					
						$company = $row['company_for'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $row['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						$sent_by = $row['sent_by'];
						
						$mode_of_receipt = $row['mode_of_receipt'];
						if($mode_of_receipt=='C'){
							$mode_of_receipt = 'Courier';
						}
						else if($mode_of_receipt=='H'){
							$mode_of_receipt = 'Hand Delivery';
						}
						else if($mode_of_receipt=='M'){
							$mode_of_receipt = 'Email';
						}
							
						$j=$j+1;						
				
				$date_of_received = date('d-m-Y', strtotime($row['date_of_received']));
				
				$baseurl1 = $baseurl.$modulePath.'forward_scr.php?sub=edit&inward_no='.$row["inward_no"];

		?>
	<a href="<?php echo $baseurl . $modulePath . "forward_scr.php?sub=edit&inward_no=". $row['inward_no']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="10%"><?php echo $date_of_received;?></td>
					<td width="10%"><?php echo $sent_by;?></td>
					<td width="29%"><?php echo $company;?></td>
					<td width="10%"><?php echo $department;?></td>
					<td width="4%" style="text-align:right;"><?php echo $row['inward_no'];?></td>
					<td width="10%"><?php echo $send_to;?></td>
					<td width="10%"><?php echo $mode_of_receipt;?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="08%"><?php echo $row['status'];?></td>
					<td width="08%"><?php echo $row['approval_status'];?></td>
					
				</tr>
		</a>		
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  

<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
	echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
	echo $paginate;
  
?>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
<?php } ?>  
  
  
<?php  

	if($_POST['edit']){
		include "saveitem.php";
	}	

	
	if($_GET['sub']=='Save'){
			$inward_no			= $_POST['inward_no'];
			/*$date_of_received	= date('Y-m-d', strtotime($_POST['date_of_received']));
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			$send_to_user		= $_POST['send_to_user'];
			$doc_type			= $_POST['doc_type'];
			*/
			
			$forward_to			= $_POST['forward_to'];
			
			$storage_type		= $_POST['storage_type'];
			$storage_rack		= $_POST['storage_rack'];
			$shelf_no			= $_POST['shelf_no'];
			$file_no			= $_POST['file_no'];	
			
  			$sql = "update dms_inward set storage_type	= '$storage_type',
										storage_rack	= '$storage_rack',
										shelf_no		= '$shelf_no',
										file_no			= '$file_no'
				where inward_no='$inward_no' ";
	
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			
//print_r($arrDocType);			
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/in/" . $inward_no;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/forward_scr.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('IN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $inward_no . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/in/" . $inward_no . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
			
//echo $sql;
//exit();
			
		//	echo "Inward successful added";
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no 		= $_GET['inward_no'];
	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status 		= $row['status'];
	$inward_no		= $row['inward_no']; 
	$send_to_user	= $row['send_to_user']; 
	
	$readonly = '';
	
	if ( ($status == 'Submited' && $user!='Admin' ) || $status == 'Completed' || !empty($send_to_user) ){
		$readonly = 'READONLY';
	}
	
	if ( $user=='Admin' ){
		$readonly = '';
	}
	
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Forwarded
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Forwarded</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="forward_scr.php?sub=Save" method="post" enctype="multipart/form-data">

		                <span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>

                        <?php
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						?>
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" >InBox</a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="third_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<!--<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
							
							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Inward Number</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;" value="<?php echo $inward_no;?>">
                                
								</div>
								<?php
									$date_of_received = date('d-m-Y', strtotime($row['date_of_received']));
									if($date_of_received=='01-01-1970'){
										$date_of_received='';
									}
								?>
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="date_of_received" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_of_received;?>" readonly <?php echo $readonly; ?> >
                                    </div>
								</div>	
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Mode or Receipt</label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt" readonly required <?php echo $readonly; ?> >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									</select>	
                                </div>
								
                               <div class="col-sm-4">
									<label for="company_for" class="control-label">Company <span style="color:red;">**</span></label>
                                	<select class="form-control" name="company_for" id="companY" readonly required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Department</label>
									<select class="form-control" name="department_for" id="department_for" readonly required <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department_for'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
									</select>	
                                </div>
								
							</div>
							
							<div class="form-group">
								<div class="col-md-4">	
									<label class=" control-label">Document Type</label>
									<select class="form-control col-sm-2 doc_type" name="doc_type" readonly <?php echo $readonly; ?> >
										<option value="0">Select</option>
													<?php
													$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($row['doc_type'] == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
													<?php } ?>	
													
									</select>
								</div>	
							
								<div class="col-md-5">		
									<label class="control-label">Sent By</label>
									<input type="text" class="form-control" id="sent_by" name="sent_by" readonly <?php echo $readonly; ?> value="<?php echo $row['sent_by'];?>" >
								</div>
							
							
								
							</div>
							
							<div class="form-group">
								<div class="col-md-4">
								
									<label class="control-label">Remarks</label>
									<input type="text" class="form-control" id="to_Supplier" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['subject'];?>" >
								
								</div>
							</div>
							
	
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label">Storage Type</label>
									<select class="form-control col-sm-2 " name="storage_type" <?php echo $readonly; ?> >
										<option value=""  >Select</option>
										<option value="D" <?php echo ($row['storage_type'] == 'P' )?'selected="selected"':'';?> >Digital</option>
										<option value="P" <?php echo ($row['storage_type'] == 'P' )?'selected="selected"':'';?> >Physical</option>
										<option value="B" <?php echo ($row['storage_type'] == 'B' )?'selected="selected"':'';?> >Both</option>
									</select>	
								</div>
							
								<div class="col-md-3">
									<label class="control-label">Storage Rack</label>
									<input type="text" class="form-control" id="storage_rack" name="storage_rack" <?php echo $readonly; ?> value="<?php echo $row['storage_rack'];?>" >
								</div>
								
								<div class="col-md-3">
									<label class="control-label">Shelf No.</label>
									<input type="text" class="form-control" id="shelf_no" name="shelf_no" <?php echo $readonly; ?> value="<?php echo $row['shelf_no'];?>" >
								</div>
								
								<div class="col-md-3">
									<label class="control-label">File No.</label>
									<input type="text" class="form-control" id="file_no" name="file_no" <?php echo $readonly; ?> value="<?php echo $row['file_no'];?>" >
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-md-5">	
									<?php 
										$forward_to = $row['forward_to'];
										$forward_to_a = explode(",", $forward_to); 
									?>
									<label class="control-label">Forward To User</label>
									<select class="form-control col-sm-2 select2" multiple="multiple" name="forward_to[]" id="forward_toF" <?php echo $readonly; ?> >
										<option value="0">Select</option>
													<?php
													$sql="SELECT * FROM sma_user ORDER BY username ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo in_array($rw['id'], $forward_to_a)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
													<?php } ?>	
													
									</select>
								</div>
								
								<div class="col-md-3">
									<label class="control-label">&nbsp;</label><br>
									<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#forwardAuthority">Forward </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</div>
							
							</div>
					
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
				</div>
					
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane" id="tab_2">
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'IN' AND reference_id = " . $inward_no;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
								<?php	
									if($status !='Completed'){
								?>  
											  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
								<?php } ?>              
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						<?php	
							if($status !='Completed'){
						?>  
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
						<?php } ?>              
            				
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
							<?php
							
								$_SESSION['inward_no'] 	= $inward_no;
								$_SESSION['status']  = $status;
							
							?>

							<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
								if ($status != 'Submited' && $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."/delete.php?did=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									
											
										
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											
											<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
									
									<!--<button  onclick='$baseurl . "approval"' class="btn btn-default"> Cancel</a></button>-->
									
							
										<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>
							
										<span>&nbsp;&nbsp;</span>
									
								</div>
								
							</div>
							
						</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $inward_no;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IN' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'IN' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
						</div>
						
						
					</div>
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
			
				
                        </form>
                    </div>
                </div>
				
				
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Quotation </h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem.php?sub=Save" method="POST">

<!--							<input type="text" id="data_mode" value=<?php echo $data_mode; ?> > -->

							<input type="hidden" name="approval_hdr_id" value=<?php echo $inward_no; ?>>
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2-123" name="supplier_name">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['supplier_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-3 control-label">Quote Ref.No</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="quote_ref_no" placeholder=" QUOTE_REF_NO...">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">Vendor Selected.</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="vendor_selected" value="Y">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemvaluess" class="col-sm-3 control-label">Values</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control"  style="text-align:right;" name="values">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-3 control-label">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea rows="3" class="form-control"  name="remarks"></textarea>
                                </div>
                            </div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id='saveForm' >Save changes</button>
							</div>
                        </form>
                    </div>
                </section>
            </div>
<!--            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>


<!-- Modal Delete Item-->
<div class="modal fade" id="modalDeleteItem" role="dialog" aria-labelledby="modalDeleteItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteItemLabel">Delete Item of Approval </h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete item "Item 1"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Approve </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 		= $_SESSION['inward_no'];
											$status 		= $_SESSION['status'];
											$userid   	    = $_SESSION['usrid'];
											
										?>
										
										<input type="hidden" name="inward_no" id="inward_noA" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeA" name="mode" value='Approve'>
										<input type="hidden" id="statusA" name="status" value='<?php echo $status ?>' >
										
										<input type="hidden" id="approverA" name="approver" value='<?php echo $userid ?>' >
										
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Reject </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 		= $_SESSION['inward_no'];
											$status 		= $_SESSION['status'];
											$userid   	    = $_SESSION['usrid'];
											
										?>
										
										<input type="hidden" name="inward_no" id="inward_noR" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="statusR" name="status" value='<?php echo $status ?>' >
										
										<input type="hidden" id="approverR" name="approver" value='<?php echo $userid ?>' >
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  
<!--Forward Workflow Popup-->

<div class="modal fade" id="forwardAuthority" role="dialog" aria-labelledby="forwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="forwardAuthority">Forward </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status = $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idF" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeF" name="mode" value='Forward'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksF"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitForward">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	
	  
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'<option value="PAN CARD">PAN Card</option><option value="AADHAAR CARD">AADHAAR Card</option></select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->
	
	
<?php
include("../footer.php");
?>
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables 
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>
-->

<script>


   $("#submitMaker").on("click", function(e){
        var sub 			= 'sub11';
		var mode		 	=  $("#modeM").val();	
		var inward_no		 	=  $("#ap_idM").val();
		var remarks			=  $("#remarksM").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );
		 $('#makerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						remarks:remarks,
						sub11:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var inward_no		 	=  $("#ap_idE").val();
//		var department 		=  $("#departmentE option:selected").val();
//var department 		=  $("#departmentE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#chekerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	 
    $("#submitApprove").on("click", function(e){
        var sub 			= 'sub3';
		var mode		 	=  $("#modeA").val();
		
		var inward_no		=  $("#inward_noA").val();
	    var status 			=  $("#statusA").val();
		var approver		=  $("#approverA").val();
        var remarks			=  $("#remarksA").val();

alert(sub + ' ' + mode  + ' ' + approver + ' ' + status );
		
		$('#approvalAuthority').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						status:status,
						approver:approver,
						remarks:remarks,
						sub3:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub4';
		var mode		 	=  $("#modeR").val();		 
		var inward_no		=  $("#inward_noR").val();
		var status 			=  $("#statuR").val();
		var remarks			=  $("#remarksR").val();
		var approver		=  $("#approverR").val();
        
		alert(sub + ' ' + mode);		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+inward_no);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						status:status,
						approver:approver,
						remarks:remarks,
						sub4:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

    $("#submitForward").on("click", function(e){
        var sub = 'sub2';
		var mode		 	=  $("#modeF").val();
		var forward_to		=  $("#forward_toF").val();
        
		var inward_no		=  $("#ap_idF").val();		
		var status 			=  $("#statusF").val();
		var remarks			=  $("#remarksF").val();

alert(sub + ' ' + mode + ' ' + forward_to + ' ' + status );	
				
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+inward_no);

		 $('#forwardAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						forward_to:forward_to,
						statusap:mode,
						remarks:remarks,
						sub2:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

	
    $("#editSave").on("click", function(e){
        var sub = 'sub1';
	//	var mode = $("#mode").val();

		var approval_hdr_id =  $("#approval_hdr_id").val();
		var approval_srno 	=  $("#approval_srno").val();
        var supplier_id   	=  $("#supplier_name option:selected").val();
//      var name =          $("#itemName option:selected").html();
//		var catid =         $("#categoryId option:selected").val();
        var quote_ref_no 	=  $("#quote_ref_no").val();
//alert(sub1 + ' ' + quote_ref_no);	
        var vendor_selected =  $("#vendor_selected").val();
        var values 			=  $("#values").val();
		var remarks 		=  $("#remarks").val();
        $('#modalAddItem').modal('hide');
		var strURL = "saveitem.php";
		$.post(strURL,{ id:id,approval_hdr_id:approval_hdr_id,
							approval_srno:approval_srno,
							supplier_id:supplier_id,
							quote_ref_no:quote_ref_no,
							vendor_selected:vendor_selected,
							values:values,
							remarks:remarks,
							sub1:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });


    /*
				There is a bug in datepicker format due to which it does not set for AdminLTE 2 theme.
				Defaulting dates to mm/dd/yyyy format.

				$('.datepicker').datepicker({
						format: 'd/M/Y',
						autoClose: 1
				});
		*/
    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });
		


</script>

<script>

	function delete_appquote(approval_hdr_id, id){
		var sub = 'sub4';
        var approval_hdr_id = approval_hdr_id;
		var id	 = id;
//alert(approval_hdr_id + ' ' + id);
		$('#modalDeleteItem'+approval_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ approval_hdr_id:approval_hdr_id,id:id,sub4:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		
		window.location.href='forward_scr.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}


	function getbudgetname(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}

	function getbudget123(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}
	
	function getbudget(id){
		
        var sub    = 'sub2';
		var project = document.getElementById("companY").value;
		var account_year = document.getElementById("account_Year").value;

//alert(sub);		
		var strURL = "app_func.php";
	$.post(strURL,{id:id,sub2:sub,project:project,account_year:account_year},function(result){
				      $('#getbudgethead').html(result);
		});

	}

	function getavailbudget(id){
		
        var sub    = 'sub22';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
		      $('#getavailbudget').html(result);
		});

	}
	
</script>

</body>
</html>
