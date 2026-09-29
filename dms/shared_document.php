<?php
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';
?>

<?php date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30) echo date('d-m-Y H:i:s'); ?>

<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<?php

	if($_GET['sub']=='list'){

?>
		
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Shared Document <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Shared Document List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
			<?php
				
				$sql = "delete from forward_share_doc where userid = '$usrid' ";
				mysqli_query($con, $sql);
				
				$targetpage = "shared_document.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['status'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['status'] or $_SESSION['searchf'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['status'];
					$approval_status= $_SESSION['approval_status'];
					$searchf 		= $_SESSION['searchf'];
					$search_data 	= $_SESSION['search_data'];
					$start_date 	= $_SESSION['start_date'];
					$end_date 		= $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['status'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['status'];
					$searchf = $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
				}
				
			?>
			
					<form class="form-horizontal" action="shared_document.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submited" <?php echo ($status == 'Received')?'selected="selected"':'';?>> Received </option>
										<option value="Verified" <?php echo ($status == 'Forwarded')?'selected="selected"':'';?>> Forwarded </option>
										<option value="Verified" <?php echo ($status == 'My Document')?'selected="selected"':'';?>> My Document </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Pending" <?php echo ($approval_status == 'Forwarded')?'selected="selected"':'';?>> Forwarded </option>
										<option value="Verified" <?php echo ($approval_status == 'Shared')?'selected="selected"':'';?>> Shared </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="shared_document.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> User Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> Description </option>
								</select>
											
							</div>
							
							<span id="getsearchf">
							<?php if( $searchf=='N' || $searchf=='S' || $searchf=='P' ){ ?>
								<div class="col-md-3">
								<?php if($searchf=='N' || $searchf=='P'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_user where active != '0' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
							<?php if($searchf=='D'){ 
								$start_date = date('d-m-Y', strtotime($start_date));
								$end_date = date('d-m-Y', strtotime($end_date));
								if($start_date =='01-01-1970'){
									$start_date = date('d-m-Y');
								}
								if($end_date =='01-01-1970'){
									$end_date = date('d-m-Y');
								}
							?>
								<label class="col-lg-1 control-label">Start.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<label class="col-lg-1 control-label">End.Date</label>
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							<?php } ?>		
									
							</span>
						
				
						
					<span class="pull-right"><a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
					</span>
											   
						</div>
						
				</form>

		<form action="shared_document.php?sub=forward" METHOD="POST" >
				
				<span class="pull-right">
									
						<!--<a href="#shareAuthority" class="btn btn-success" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i></a>&nbsp;&nbsp;&nbsp;-->
							
				</span>
		   </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
				
                    <th width="1%" ></th>
					<th width="4%">Docu.&nbsp;&nbsp; No.</th>
					<th width="10%">Shared Date</th>
					<th width="10%">Mode </th>
					<th width="10%">For Company</th>
					<th width="10%">Sent By</th>
					<th width="10%">Sent To</th>
					<th width="10%">For Department</th>
					<th width="25%">Description</th>
					<th width="8%">Action</th>
					<th width="1%" ></th>
					<!--<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
			</table>	
				<?php
					//$sql="SELECT * from dms_inward order by id desc";
					
//		echo $role;
					//FIND_IN_SET("q", "s,q,l");
					
					$userid   	    = $_SESSION['usrid'];
					
					if(!empty($comp_id)){
						$sqlcomp = " and b.company_for = '$comp_id' ";
					}
					
					$sql = "select * from shared_documents a, dms_inward b where a.reference_id	 = b.inward_no and a.module = 'IN' and shared_user_id = '$userid' and a.status = 'Shared' " . $sqlcomp ; 
					$qry = mysqli_query($con,$sql);
					
					$query = "select count(*) as num from shared_documents a, dms_inward b where a.reference_id = b.inward_no and a.module = 'IN' and a.shared_user_id = '$userid' and a.status = 'Shared' " . $sqlcomp ; 
					
					if($searchf=='P'){
						$sql .= " and b.remarks like ".  "'%". $search_data. "%'" ;
						$query .= " and b.remarks like ".  "'%". $search_data. "%'" ;
					}
					if($searchf=='N'){
						$sql   .= " and inward_no = '$search_data' ";
						$query .= " and inward_no = '$search_data' "	;
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
					
					
					$sql .= ' order by reference_id desc ';
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
		?>	
		
			<div style="height:350px;overflow:scroll;border:1px #999;">
				<table id="prtable123" class="table table-bordered table-striped">
		<?php
				while($row = mysqli_fetch_array($result)){
						
						$status  = $row['status'];
				
						$shared_date = date('d-m-Y h:i:s', strtotime($row['shared_date']));
						
						$sent_by = $row['current_user_id'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_by' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$sent_by  = $r2['username'];
						
						$sent_to = $row['shared_user_id'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$send_to  = $r2['username'];
						
						$inward_no = $row['reference_id'];
						
						$sql="SELECT * from dms_inward where inward_no ='$inward_no' ";
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$remarks		   = $r1['remarks'];
						
						$company = $r1['company_for'];
						//$date_of_received = date('d-m-Y h:i:s', strtotime($r1['date_of_received']));
						$outward_no = $r1['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$comp_name  = $r2['comp_name'];
						$company    = $r2['comp_code'];

						$department = $r1['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						
						$mode_of_receipt = $r1['mode_of_receipt'];
						if($mode_of_receipt=='C'){
							$mode_of_receipt = 'Courier';
						}
						else if($mode_of_receipt=='H'){
							$mode_of_receipt = 'Hand Delivery';
						}
						else if($mode_of_receipt=='E'){
							$mode_of_receipt = 'Email';
						}
						else if($mode_of_receipt=='S'){
							$mode_of_receipt = 'Self';
						}

				$styl="";
				$styl2="";
				//$status	='';
				//echo $status."<<>>";
			
				$j = $j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				
				$baseurl1 = $baseurl.$modulePath.'shared_document.php?sub=edit&inward_no='.$inward_no;

		?>
	<!--<a href="<?php echo $baseurl . $modulePath . "shared_document.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">-->
			<tr>
			
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="4%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $shared_date;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $mode_of_receipt;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $sent_by;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $send_to;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $department;?></td>
					<td width="25%" <?php echo $styl; ?>><?php echo $remarks;?></td>
					
					<td width="08%"<?php echo $styl; ?>>
						<input type="checkbox" name="forward_check[]" id="forward_check" value="<?php echo $inward_no; ?>" onclick="getchecked(this.value)" >
						&nbsp;&nbsp;
						<a href="<?php echo $baseurl . $modulePath . "shared_document.php?sub=edit&inward_no=". $inward_no?>" title='Edit' ><i class="fa fa-fw fa-edit"></i> </a>
					</td>
					
				</tr>
		<!--</a>-->
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  
		
			</div>
		</form>
		
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


if($_GET['sub']=='edit'){
	
	if($_POST['Save']){

			$inward_no			= $_POST['inward_no'];
			//$date_of_received	= date('Y-m-d', strtotime($_POST['date_of_received']));
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			//$sent_by_user_vendor= $_POST['sent_by_user_vendor'];
			$send_to_user_list	= explode("-", $_POST['sent_by_user_vendor']);
			$sent_by_user_vendor= $send_to_user_list['0'];
			$sent_by_user_type	= $send_to_user_list['1'];
			
			$send_to_user		= $_POST['send_to'];
			$doc_type			= $_POST['doc_type'];
			
			$forward_to			= $_POST['forward_to'];
			
			$storage_type		= $_POST['storage_type'];
			$storage_rack		= $_POST['storage_rack'];
			$shelf_no			= $_POST['shelf_no'];
			$file_no			= $_POST['file_no'];	
			$saved 				= 'Y';
			$status				= 'Shared';
			$outward_no			= $_POST['outward_no'];
			$approval_status	= $_POST['approval_status'];
			
			
  			$sql = "update dms_inward set storage_type	= '$storage_type',
										storage_rack	= '$storage_rack',
										shelf_no		= '$shelf_no',
										file_no			= '$file_no',
										company_for		= '$company_for',
										department_for	= '$department_for',
										remarks			= '$remarks',
										sent_by			= '$sent_by',
										sent_by_user_vendor= '$sent_by_user_vendor',
										sent_by_user_type= '$sent_by_user_type',
										doc_type		= '$doc_type',
										status			= '$status',
										approval_status	= '$approval_status',
										saved 			= '$saved'
					where inward_no='$inward_no' ";
					
			
//echo $sql;	send_to_user	= '$send_to_user',
										
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$userid   	    = $_SESSION['usrid'];
			
			$sql = "insert into shared_documents (doc_type, doc_id, create_by, create_date, status, shared_user_id, remarks, approved_date) values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
			$r2 = mysqli_query($con, $sql);
			
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
					
					$findex = $folder_path.'/shared_document.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO my_documents_files ( module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded, current_user_id, rack_no, shelf_no, forwarded_to ) VALUES('IN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $inward_no . ",'2018-01-01', '$userid', '$storage_rack', '$shelf_no', '$send_to_user' )";
					if (mysqli_query($con, $sql)){
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
			//$baseurl.=$modulePath.'shared_document.php?sub=list';
			$baseurl.=$modulePath.'shared_document.php?sub=edit&inward_no='.$inward_no;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	
	//echo $sql;
	
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	//$status 		= $row['status'];
	$inward_no		= $row['inward_no'];
	$send_to_user	= $row['send_to_user'];
	$saved 			= $row['saved'];
	
	$userid   	    = $_SESSION['usrid'];
	$sql = "SELECT * FROM `shared_documents` where module= 'IN' and status ='Shared' and shared_user_id = '$userid' and reference_id = '$inward_no' ";
//	echo $sql;
	
	$qry = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($qry);
	$reference_id = $rs['reference_id'];
	$status = $rs['status'];
	
	$readonly = '';
	
	$readonly = 'READONLY';
	
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Shared Document 
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Shared Document</a></li>
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
						<form id="form1" class="form-horizontal" action="shared_document.php?sub=edit" method="post" enctype="multipart/form-data">

		                <span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b></h4> </span>
						
						<span class="pull-right">
							<a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;
						</span>
								
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
                        <li class="<?php echo $active_1;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" >Shared Document</a></li>
                       <!-- <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="third_tab">Documents</a></li>-->
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<!--<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							
							<input type="hidden" name="outward_no" id = "oD" value="<?php echo $row['outward_no'];?>" >
							<input type="hidden" name="approval_status" id = "approval_status" value="<?php echo $row['approval_status'];?>" >
							
							<input type="hidden" name="status" id="statuS" value="<?php echo $status;?>" >
							
							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Document No.</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;font-size:24px; font-family: Arial, Helvetica, sans-serif;" value="<?php echo $inward_no;?>">
                                
								</div>
								<?php
									$date_of_received = date('d-m-Y', strtotime($row['date_of_received']));
									$date_of_received_time = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
									if($date_of_received=='01-01-1970'){
										$date_of_received_time='';
									}
								?>
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date</label>
                                    <!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>-->
                                        <input type="text" class="form-control" id="prDate" name="date_of_received" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_of_received_time;?>" readonly <?php echo $readonly; ?> >
                                    <!--</div>-->
									
								</div>	
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Mode </label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt"   required <?php echo $readonly; ?> >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self </option>
									</select>
                                </div>
								
								<?php //where comp_id in ($comid) ?>
                               <div class="col-sm-4">
									<label for="company_for" class="control-label">Company </label>
                                	<select class="form-control" name="company_for" id="companY"   required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company  order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Department</label>
									<select class="form-control" name="department_for" id="department_for"   required <?php echo $readonly; ?> >
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
									<select class="form-control  doc_type select2" name="doc_type" disabled  <?php echo $readonly; ?> >
										<option value="0">Select</option>
													<?php
													$sql="SELECT * FROM sma_document_type where type = 'DMS' ORDER BY document ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($row['doc_type'] == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
													<?php } ?>		
									</select>
								</div>
									
								<div class="col-md-4">		
									<label class=" control-label">Sender </label>
									<select class="form-control col-sm-2 select2"  name="sent_by_user_vendor" disabled  <?php echo $readonly; ?> >
										<option value="0">Select</option>
											<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active != '0' 
															union 
													   SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst`
															ORDER BY uvname ASC ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'].'-'.$r2['type'];?>" 
									<?php echo ($row['sent_by_user_vendor'].'-'.$row['sent_by_user_type'] == $r2['id'].'-'.$r2['type'])?'selected="selected"':'';?> >  
									<?php echo $r2['uvname'].' - ' . $r2['type'] ;?></option>
											<?php } ?>
										<option value="9999">Others</option>	
									</select>
								</div>
							
								<div class="col-md-4">		
									<label class="control-label">Sender Other</label>
									<input type="text" class="form-control" id="sent_by" name="sent_by" autocomplete="off"   <?php echo $readonly; ?> value="<?php echo $row['sent_by'];?>" >
								</div>
								
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">Inward Description</label>
								<div class="col-md-7">
									<input type="text" class="form-control" id="remarks"  autocomplete="off" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
								</div>
								
								<label class="control-label col-md-1">Doc.Ref.No.</label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="doc_ref_no"  autocomplete="off" name="doc_ref_no" <?php echo $readonly; ?> value="<?php echo $row['doc_ref_no'];?>" >
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label">Storage Type</label>
									<select class="form-control col-sm-2 " id="storage_TYPE" name="storage_type" <?php echo $readonly; ?> >
										<option value=""  >Select</option>
										<option value="D" <?php echo ($row['storage_type'] == 'D' )?'selected="selected"':'';?> >Digital</option>
										<option value="P" <?php echo ($row['storage_type'] == 'P' )?'selected="selected"':'';?> >Physical</option>
										<option value="B" <?php echo ($row['storage_type'] == 'B' )?'selected="selected"':'';?> >Both</option>
									</select>	
								</div>
							
							<div id = "getsrack">
								<div class="col-md-3">
									<label class="control-label">Storage Rack</label>
									<input type="text" class="form-control" id="storage_rack"  autocomplete="off" name="storage_rack" <?php echo $readonly; ?> value="<?php echo $row['storage_rack'];?>" >
								</div>
							</div>
							
							<div id = "getshelf">							
								<div class="col-md-3">
									<label class="control-label">Shelf No.</label>
									<input type="text" class="form-control" id="shelf_no"  autocomplete="off" name="shelf_no" <?php echo $readonly; ?> value="<?php echo $row['shelf_no'];?>" >
								</div>
							</div>
							
							<div id = "getfilen">		
								<div class="col-md-3">
									<label class="control-label">File No./ Name</label>
									<input type="text" class="form-control" id="file_no"  autocomplete="off" name="file_no" <?php echo $readonly; ?> value="<?php echo $row['file_no'];?>" >
								</div>
							</div>
								
							</div>
						
				<!--</div>-->
					
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
                <!--<div class="tab-pane" id="tab_2123">-->
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM my_documents_files WHERE module = 'IN' AND reference_id = '$inward_no' ";
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
								//	if($status !='Completed'){
								?>  
										<!--	  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td> -->
								<?php //} ?>              
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
            	
							<?php
							
								$_SESSION['inward_no'] 	= $inward_no;
								$_SESSION['status']  = $status;
							
							?>

							<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
							//	if ($status != 'Submited' && $user=='Admin' ){
							?>		
							<!--		<a href="<?php echo $baseurl.$modulePath."/delete.php?did=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>-->
							<?php //} 
							
							//echo $user."<<>>". $saved ."<>";?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php if ( $saved=='Y' && $status !='Shared' ){ ?>
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Accept </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											
									<?php } 
										else if ($saved!='Y' || $user=='Admin'){
									?>
									
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
									<?php }  ?>	
										<!--<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
										<a href="<?php echo $baseurl.$modulePath.'shared_document.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>							
										
								</div>
								
							</div>
							
						</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $inward_no;
									$s1  = "SELECT * from shared_documents where reference_id = '$srno' and module = 'IN' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['current_user_id'];
									$create_date	= date('d-m-Y', strtotime($r1['shared_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Shared By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
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
											  <th>Document Description</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from shared_documents where reference_id = '$srno' and module = 'IN' order by id desc";
										//echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$shared_user_id 	= $r1['shared_user_id'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['shared_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$shared_user_id' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$shared_user_id = $rw1['username'];
										//echo $shared_user_id . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['current_user_id'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['shared_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$shared_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $shared_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $shared_user_id;?></td>
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



<!--Forward Workflow Popup-->

<div class="modal fade" id="forwardAuthority<?php echo $rid?>" role="dialog" aria-labelledby="forwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="forwardAuthority">Forward To</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idF" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeF" name="mode" value='Send'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<input type="hidden" name="rid" id="ridF" value="<?php echo $rid; ?>" >
										
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Share To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control"  name="send_to" id="send_toF" <?php echo $readonly; ?> >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user where active != '0' ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
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
								<button type="button" class="btn btn-primary" id="submitSend">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	


<?php } ?>
	 


<!--Shared Workflow Popup-->
<div class="modal fade" id="shareAuthority" role="dialog" aria-labelledby="shareAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="shareAuthority">Share</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
									<form class="form-horizontal">
										<div class="box-body">    
										<?php   
											//$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idS" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Shared'>
										
										<!--<input type="hidden" id="statusS" name="status" value='<?php echo $status; ?>' >-->
										<input type="hidden" id="statusS" name="status" value="Accepted" >
										
											
										<div class="form-group">
											 <?php // multiple="multiple" ?>
											<div class="col-sm-12">
												<label class=" control-label">To Company (Company Wise for all employees)</label>
												<select class="form-control" name="send_to_comp" id="send_toS_comp"  >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM company ORDER BY comp_name ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['comp_id']?>" <?php echo ($rw['comp_id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['comp_name'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label class=" control-label">To Group (Group wise sharing)</label> <?php // multiple="multiple" ?>
												<select class="form-control" name="send_to_group" id="send_to_Group"  >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user_group ORDER BY user_group ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" ><?php echo $rw['user_group'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label class=" control-label"> To User (User wise)</label> <?php // multiple="multiple" ?>
												<select class="form-control select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;"  name="send_to" id="send_toS" <?php echo $readonly; ?> >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">To Email(Third Party email id sharing with comma seperated)</label>
                                            	<textarea class="form-control" rows="3" name="ext_email" id="ext_emaiL"></textarea>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Message</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitShared">Share</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>


<!--Shared Workflow Popup End -->	
	   
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
	
	$(function() {
		//$('#getsrack').hide();
		//$('#getshelf').hide(); 
		//$('#getfilen').hide(); 
		$('#storage_TYPE').change(function(){
			if($('#storage_TYPE').val() == 'D') {
			   $('#getfilen').show();
			   $('#getsrack').hide();
				$('#getshelf').hide(); 
			} else {
				$('#getsrack').show();
				$('#getshelf').show(); 
				$('#getfilen').show(); 
			} 
		});
	});
	
	
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
                     //alert(data);  
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
		var inward_no		=  $("#ap_idM").val();
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

//alert(sub + ' ' + mode  + ' ' + approver + ' ' + status );
		
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
        
		//alert(sub + ' ' + mode);		
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


    $("#submitSend").on("click", function(e){
        var sub = 'sub5';
		var mode		 	=  $("#modeS").val();
		var send_to			=  $("#send_toS").val();
        
		var inward_no		=  $("#ap_idS").val();		
		var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

//alert(sub + ' ' + mode + ' ' + send_to + ' ' + status );	

		 $('#sendAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						send_to:send_to,
						statusap:mode,
						remarks:remarks,
						sub5:sub},
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
		
		window.location.href='shared_document.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}

	$("#submitShared").on("click", function(e){
        var sub 			= 'sub7';
		//var mode		 	=  $("#modeS").val();
		var send_to			=  '('+ $("#send_toS").val() +')';
        var send_to_comp	=  $("#send_toS_comp").val();
		var inward_no		=  $("#ap_idS").val();
		var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();
		var send_to_group	=  $("#send_to_Group").val();   
		var ext_email		=  '(' + $("#ext_emaiL").val() +')';

		var doc_scr			= 'MYD';
		
		$('#shareAuthority').modal('hide');
		
//		$('#getcheck').html("Hello World....");	
//		$('#prshare').html("Hello World....");
//		alert(sub + ' ' + send_to + ' ' + status + ' ' + ext_email);

		$('#shareAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						send_to:send_to,
						send_to_comp:send_to_comp,
						remarks:remarks,
						send_to_group:send_to_group,
						ext_email:ext_email,
						doc_scr:doc_scr,
						sub7:sub},
						function(result){
		      $('#prshare').html(result);
			  
			  alert('Shared your document...');
			  
		});
		
		$("#ext_emaiL").val() = '';
		
		alert('Shared your document...');
		
	});



	function getchecked(id){
		
		//var forward_check: $('input#forward_check').prop('checked');
		var forward_check = $('input#forward_check').prop('checked');
		//alert(forward_check);
		if(forward_check==true){
			forward_check='Y';
		}
		else {
			forward_check='N';
		}	
		
//		alert(forward_check);
		//alert(id + ' ' + "Hello World");
		
		 var sub    = 'sub6';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,forward_check:forward_check,sub6:sub},function(result){
		      $('#getcheck').html(result);
		});

	}


function getsearchf(id){
    var sub = 'sub1';
//alert(id);
	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}

</script>

</body>
</html>
