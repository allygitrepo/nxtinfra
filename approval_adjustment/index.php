<?php
include("../header.php");
$modulePath = "approval_adjustment/";

$userid   	= $_SESSION['usrid'];


	$help_code = $modulePath.'index.php';
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");
	
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Approval Memo Adjustment<small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
		
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Approval Memo Adjustment List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
			<?php
			
				$targetpage = "index.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['statuss'];
					$approval_status= $_SESSION['approval_status'];
					$searchf 		= $_SESSION['searchf'];
					$search_own		= $_SESSION['search_own'];
					$search_data 	= $_SESSION['search_data'];
					$start_date 	= $_SESSION['start_date'];
					$end_date 		= $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_own'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_own = $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$approval_status = $_SESSION['approval_status'];
					$_SESSION['Createdby_ap'] ='';
				}
				/* if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
			
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
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
										<option value="Submitted" <?php echo ($status == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control select2" name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Supplier Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Sr.No. </option>
									<!--<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> OWN </option>-->
									<option value="V" <?php echo ($searchf == 'V')?'selected="selected"':'';?>> Deactive </option>
									<option value="B" <?php echo ($searchf == 'B')?'selected="selected"':'';?>> Both </option>
								</select>
							</div>
							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
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
							
							<input class="btn btn-success" type="submit" value="Self Created" name="Createdby">&nbsp;&nbsp;&nbsp;
							
						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						?>
								<span>
								<!--	<div class="col-md-2" ><b class="btn btn-info">Own</b>&nbsp;&nbsp;
										<input type="checkbox"  <?php echo $checked;?> id="search_own" name="search_own" value="Y" >
									</div>
								-->	
								</span>
					<?php if ( $viewonly!='Y'){ ?>			
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "add.php"?>" class="btn btn-primary">Create </a> &nbsp;&nbsp;&nbsp;&nbsp; </span> <!-- As per Amit and Rajesh Instruction on 23.12.19-->
					<?php } ?>
					
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "ap_export_func.php?sub=pdf"?>" target="_blank" class="btn btn-primary">Report</a>&nbsp;&nbsp;&nbsp;&nbsp; </span>
				
					</div>
						
				</form>

			
		   </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr >
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>By</th>
					<th>Pending With</th>
					<th>Status</th>
					<th>Decision</th>
<!--					<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
				<?php
					//$sql="SELECT * from sma_approval_memo order by id desc";
					
					$role			= $_SESSION['role'];
					$readonly		= $_SESSION['readonly'];
					$user_category	= $_SESSION['user_category'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
					
					$sql	="Delete from sma_approval_memo where company ='' and department=0 and dated ='1970-01-01' ";
					mysqli_query($con, $sql);
					
//		echo $role; (status = 'Submitted' or status = 'Completed' or
					$sql	="SELECT * from sma_approval_memo where company in ( $comid ) and (status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or draft_by = '$user' ) and doctype = 'AP-ADJ' ";
					$query  ="SELECT count(*) as num from sma_approval_memo where company in ( $comid ) and ( status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected' or current_approver = '$usrid' or draft_by = '$user' ) and doctype = 'AP-ADJ'  ";
					
					if ($user =='Admin' || $primaryrole=='COO' || $primaryrole == 'Director' ){
						$sql="SELECT * from sma_approval_memo where id > 0 and doctype = 'AP-ADJ' ";
						$query="SELECT count(*) as num from sma_approval_memo where id > 0 and doctype = 'AP-ADJ'   ";
					}
					
					if ($viewonly =='Y' ){
						$sql	= "SELECT * from sma_approval_memo where id > 0 and doctype = 'AP-ADJ'  and company in ( $comid ) ";
						$query	= "SELECT count(*) as num from sma_approval_memo where id > 0 and doctype = 'AP-ADJ'  and company in ( $comid ) ";
					}
					
					if($_POST['Createdby'] || $_SESSION['Createdby_ap'] ){
						$_SESSION['Createdby_ap'] = $_POST['Createdby'];	
						$sql	= "SELECT * from sma_approval_memo where id > 0 and doctype = 'AP-ADJ' and company in ( $comid ) and draft_by = '$user' ";
						$query	= "SELECT count(*) as num from sma_approval_memo where id > 0 and doctype = 'AP-ADJ' and company in ( $comid ) and draft_by = '$user' ";
					}
					
//echo $sql. ' ' . $readonly[2]. ' '. $role;					
					if ($comp_id){
						$sql .= " and company = '$comp_id' ";
						$query .= " and company = '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					if ($approval_status){
						$sql .= " and approval_status = '$approval_status' ";
						$query .= " and approval_status = '$approval_status' ";
					}
					
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and dated >= '$start_date' and dated <= '$end_date' ";
						$query .= " and dated >= '$start_date' and dated <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					$sqlvn ='';
					if($searchf=='S'){
							$sqlvn .= " party_name like ". "'%". $search_data."%'" ;
							$sql .= " and id in ( SELECT approval_hdr_id from sma_approval_details where vendor_selected = 'Y' and supplier_name = '$search_data' )  ";
							$query .= " and id in ( SELECT approval_hdr_id from sma_approval_details where vendor_selected = 'Y' and supplier_name = '$search_data' ) " ;
					}
					
					if($search_own=='Y'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user'  ";
					}
					
					//Active / Deactive records
					if($searchf=='V'){
						$sql .= " and del = 'Y' ";
						$query .= " and del = 'Y' ";
					}
					else if($searchf=='B'){
						$sql .= "";
						$query .= " ";
					}
					else { // Default
						$sql .= " and del != 'Y' ";
						$query .= " and del != 'Y' ";
					}
					
					$_SESSION['sqlex'] = $sql;
					
			//echo $search_data;		
//	echo $sql;
//	echo $query;
//exit();			
			//echo $query."<BR>";
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages['num'];
			//echo $total_pages. ' <<<>>';		
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					
					$page = ($_GET['page']);
					if($_GET['same_page']){
						$page = $_GET['same_page'];
					}

					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}						
						
					$sql .= ' order by id desc ';
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
						
						$rid 				= $row['id'];
						$overhead_exp		= $row['overhead_exp'];
						
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company_name  	= $r2['comp_name'];
						$company  		= $r2['comp_code'];

						$department = $row['department'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						$party_name ='';
						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name " ;
			
						$ij=0;
						$party_name  = '';
						$amount		 =0;
						$q2  = mysqli_query($con, $sql);
						$raffect = mysqli_affected_rows($con);
						while($r2 = mysqli_fetch_array($q2)){
							
							if($ij>0){$party_name.=', <BR> ' ;}
							
							$party_name  .= $r2['party_name'];
							$amount		 += $r2['values'];
							
							$ij = $ij + 1;
							
						}
					
					//if($overhead_exp!='Y'){					
						$sql 	= "select * from sma_approval_items where approval_hdr_id = '$ap_id' ";$amount = 0;			
						$qry 	= mysqli_query($con,$sql);
						$error  = mysqli_error($con);
						while ($r2 	= mysqli_fetch_array($qry)){
					
							$quantity	  	 = $r2['quantity'];
							$unit_rate	  	 = $r2['unit_rate'];
							$gst		  	 = $r2['gst'];
							
							$amount += round(($quantity * $unit_rate) + ( (($quantity * $unit_rate) * $gst) / 100 ),0);
						}	
					//}
						
					
						$ap_amend = $row['ap_amend'];
						$backcolor = '';
						if($ap_amend=='Y'){
							$backcolor = ' background-color: coral; ';
						}
						
						$styl  =  '';
						$styl2 = '';
						$del = $row['del'];
						if($del =='Y'){
							
							$styl = "style='bgcolor:powderblue;color:red;' ";
							$styl2 = "bgcolor:powderblue;color:red; ";
							
						}
						
				$overhead_exp	= $row['overhead_exp'];
				if($overhead_exp=='Y'){
					$ttype = "For Operating Expense";
				}
				else{
					$ttype = "For PO";
				}	
				
				$draft_by = $row['draft_by'];
				
				$changed_by = $row['changed_by'];
				if(empty($changed_by)){
					$changed_by = $draft_by;
				}	
				
				$sql = "select * from sma_user where userid = '$changed_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$changed_by  = $r2['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated ='';
				}	
				
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"].'&page='.$page;
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
	
					<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="4%" <?php echo $styl; ?> ><?php echo $row['id'];?></td>
					<td width="12%" <?php echo $styl; ?>><?php echo $dated;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="15%" <?php echo $styl; ?>><?php echo $ttype;?></td>
					
					<td width="25%" <?php echo $styl; ?>><?php echo $party_name;?></td>
					<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $pending_by;?></td>
					<td width="08%" <?php echo $styl; ?>><?php echo $row['status'];?></td>
					<td width="08%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
					
<!--					<td width="5%" style="text-align:right;" >
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						
						<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
						<?php include "view_history.php"; ?>
						<a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>

					</td>-->
		<!--				<td width="10%" style="text-align:right;">
						<a href="purchase_requisition.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
						
						<a href="purchase_requisition.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a></td>-->
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

  <!-- /.content-wrapper -->

  <!-- Modal Add Item-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Approval Memo data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="ap_export_func.php?sub=pdf" target="_blank" method="POST" >
                            <input type="hidden" id="mode" value='Export'>
                            <input type="hidden" id="tempId">
<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">-->
							
							<div class="form-group">
                                
								<div class="col-sm-4">
									<label class="control-label">From Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="fromDate" name="from_date" required="required" >
									</div>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">To Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="toDate" name="to_date" >
									</div>
								</div>
							</div>
								
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Company</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company where comp_id in ( $comid )order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							<div class="form-group">
                                
								<div class="col-sm-6">
                                <label class="control-label">Department</label>
									<select class="form-control" name="department" id="departMent" <?php echo $readonly; ?> >
										<option value=""> Select </option>
										<option value=""> All </option>
											<?php $sql = "select * from sma_department order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
                                </div>
                            </div>
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Add Item-->

</div>
<!-- ./wrapper -->
<?php
include("../footer.php");
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

	
function getsearchf(id){
    var sub = 'sub1';
//alert(id);

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}
	
</script>

</body>
</html>

