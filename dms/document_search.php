<?php
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';

$userid   	    = $_SESSION['usrid'];

$_SESSION['back']='';

?>

<?php date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30) echo date('d-m-Y H:i:s'); ?>

<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<?php

	if($_GET['sub']=='list'){
		
		
		$sql = " delete from forward_share_doc where userid = '$userid' ";// and status = 'Forwarded' ";
//echo $sql;		
		mysqli_query($con, $sql);
			
		$targetpage = "document_search.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				//$j = 0;						
				
?>
		
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Search Document List <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">My Document List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
				<span class="pull-right">
							<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" 
									data-target="#forwardAuthority" title="Forward" ><i class='fa fa-sign-out-alt'></i> Handover</a>&nbsp;&nbsp;&nbsp;
								<?php //include "forward_func.php"; ?>
									
							<a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;
							
							<a href="#outwardAuthority" class="btn btn-info" data-toggle="modal" data-mode="Share" data-target="#outwardAuthority" title="Share"><i class='fa fa-camera-retro'></i> Outward</a>&nbsp;&nbsp;&nbsp;
							
							<button type="button" class="btn btn-primary " onclick="selectall()" >Select All</button> &nbsp
							
							<a href="<?php echo $baseurl.$modulePath.'document_search.php?sub=list';?>" class="btn btn-default" >Reset/Uncheck</a> &nbsp
							
							<a href="<?php echo $baseurl.$modulePath.'document_search.php?sub=search';?>" class="btn btn-default" >Back</a>
				</span>
			</div>	
		<form action="document_search.php?sub=forward" METHOD="POST" >
		
				
		 
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
				
                    <th width="1%" ></th>
					<th width="10%">Inward Date</th>
					<th width="10%">Sent By</th>
					<th width="29%">For Company</th>
					<th width="10%">For Department</th>
					<th width="10%">Sent To</th>
					<th width="4%">Document No.</th>
					<th width="10%">Mode </th>
					<th width="8%">Status</th>
					<th width="8%">Action</th>
					<th width="1%" ></th>
					<!--<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
			</table>	
				<?php
				
					$userid   	    = $_SESSION['usrid'];
				
					$sql = $_SESSION['sqls'];

//echo $sql; //exit();
					$qresult = mysqli_query($con,$sql);
					echo mysqli_error($con);
					$total_pages = mysqli_affected_rows($con);
//echo $total_pages."<BR>";					
					//$total_pages = $total_pages[num];
		
		//echo $total_pages. " <<>>";
				
					$stages = 3;
					
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
//echo $sql. "<BR>";;
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
		?>	
		
	<span id = "selectall">
	
			<!--<div style="height:350px;overflow:scroll;border:1px #999;">-->
				<table id="prtable" class="table table-bordered table-striped">
		<?php
				while($row = mysqli_fetch_array($result)){
						
						$status    = $row['status'];
						$inward_no = $row['inward_no'];
						
						$company   = $row['company_for'];
						$date_of_received = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
						$outward_no = $row['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $row['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$department  = $r2['name'];						
						
						$mode_of_receipt = $row['mode_of_receipt'];
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

				$baseurl1 = $baseurl.$modulePath.'document_search.php?sub=edit&inward_no='.$inward_no;

		?>
	<!--<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">-->
			<tr>
			
					<td width="0%"><input type="hidden" value="<?php echo $inward_no;?>" > </td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $date_of_received;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $sent_by;?></td>
					<td width="29%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $department;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $send_to;?></td>
					<td width="4%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $mode_of_receipt;?></td>
					<td width="08%"<?php echo $styl; ?>><?php echo $status;?></td>
					
					<td width="08%"<?php echo $styl; ?>>
						<input type="checkbox" class="abc" name="forward_check[]" id="forward_check" value="<?php echo $inward_no; ?>" onclick="getchecked(this.value)" >
					&nbsp;&nbsp;
						<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>&back=s" title='Edit' ><i class="fa fa-fw fa-edit"></i> </a>
					</td>
					
			</tr>
		<!--</a>-->
				<?php } ?>
				
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  
		</span>
		
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

if($_GET['sub']=='search'){
	
	if($_POST['search']){
		
		$inward_no			= $_POST['inward_no'];
		$inward_no_to		= $_POST['inward_no_to'];
		$company_for		= $_POST['company_for'];
		$department_for		= $_POST['department_for'];
		
		$doc_type			= $_POST['doc_type'];
		
		$remarks			= $_POST['remarks'];
		$sent_by			= $_POST['sent_by'];
		$sent_by_user_vendor= $_POST['sent_by_user_vendor'];
			
		$send_to_user		= $_POST['send_to'];
		$storage_type		= $_POST['storage_type'];
		$storage_rack		= $_POST['storage_rack'];
		$shelf_no			= $_POST['shelf_no'];
		$file_no			= $_POST['file_no'];	
		$outward_no			= $_POST['outward_no'];
		
		$date_from		= date('Y-m-d', strtotime($_POST['date_from']));
		if($date_from=='1970-01-01'){
			$date_from='';
		}
		$date_to		= date('Y-m-d', strtotime($_POST['date_to']));
		if($date_to=='1970-01-01'){
			$date_to='';
		}
	
		$reminder_date		= date('Y-m-d', strtotime($_POST['reminder_date']));
		if($reminder_date=='1970-01-01'){
			$reminder_date='';
		}
		$in_days			= $_POST['in_days'];
		$outward_number		= $_POST['outward_number'];
		$doc_ref_no			= $_POST['doc_ref_no'];
		
		$sql ='';
	
		if(!empty($date_from)){
			$sql .= " and date_of_received	>= '$date_from' ";
		}
		if(!empty($date_to)){
			$sql .= " and date_of_received	<= '$date_to' ";
		}
		if(!empty($inward_no)){
			$sql .= " and inward_no			>= '$inward_no' "; 
		}
		if(!empty($inward_no_to)){
			$sql .= " and inward_no			<= '$inward_no_to' "; 
		}
		if(!empty($company_for)){
			$sql .= " and company_for			= '$company_for' "; 
		}
		if(!empty($department_for)){
			$sql .= " and department_for	= '$department_for' "; 
		}
		
		if(!empty($doc_type)){
			$sql .= " and a.doc_type	= '$doc_type' "; 
		}
		if(!empty($remarks)){
			$sql .= " and remarks	like '%".$remarks. "%'"; 
		}
		if(!empty($sent_by_user_vendor)){
			$sent_by_user_vendor_a 		= explode("-", $sent_by_user_vendor);
			$sent_by_user_vendor		= $sent_by_user_vendor_a['0'];
			$sql .= " and sent_by_user_vendor = '$sent_by_user_vendor' "; 
		}
		if(!empty($send_to_user)){
			$sql .= " and send_to_user		='$send_to_user' "; 
		}

		if(!empty($outward_no)){
			$sql .= " and outward_no		='$outward_no' "; 
		}
		
		if(!empty($outward_number)){
			$sql .= " and outward_number	='$outward_number' "; 
		}
		if(!empty($doc_ref_no)){
			$sql .= " and doc_ref_no		='$doc_ref_no' "; 
		}
		if(!empty($outward_no)){
			$sql .= " and outward_no		='$outward_no' "; 
		}

//echo $sql;
//exit();
		//$sql ="SELECT * from dms_inward where 1  " . $sql;
		
		if($user=='Admin' || $role=='CXO' ){
			$sql = "SELECT * from dms_inward a, my_documents b where a.inward_no = b.reference_id " . $sql ;
		}
		else{
			$sql = "SELECT * from dms_inward a, my_documents b where a.inward_no = b.reference_id and b.current_user_id = '$userid' " . $sql ;	
		}
		
//and inward_no >= '100005' and inward_no <= '100009' order by inward_no desc LIMIT 0, 10
	
		$_SESSION['sqls'] = $sql;
		
		//$res = mysqli_query($con, $sql);
		//echo mysqli_error($con);
		//$r1 = mysqli_fetch_array($res);
//echo $sql;		

			$baseurl.=$modulePath.'document_search.php?sub=list';
			echo "<script>window.location.href='$baseurl';</script>";

exit();

	}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            My Document Search
          
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">My Document Search</a></li>
            <li class="active">Search</li>
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
					
						<form id="form1" class="form-horizontal" action="document_search.php?sub=search" method="post" enctype="multipart/form-data">

						<!--<span class="pull-right">
							<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" 
									data-target="#forwardAuthority" title="Forward" ><i class='fa fa-sign-out-alt'></i> Forward</a>&nbsp;&nbsp;&nbsp;
								
							<a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;
						</span>-->
								
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
                        <li class="<?php echo $active_1;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" >My Document</a></li>
                       <!-- <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="third_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>-->
						<!--<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<div class="form-group">
								
								<div class="col-xs-2">
									<?php 
									//echo $user;
										if ($user=='Admin'){
											$tsql = " " ;
										}
										else {
											$tsql = " where current_user_id = '$userid' " ;
										}	
										$sql = "select * from my_documents ". $tsql . " order by reference_id ";
										$q2 	= mysqli_query($con, $sql);
									?>		
									<label for="prDate" class="control-label">Document No. From</label>
                                    <select class="form-control select2" name="inward_no" id="inward_no" >
										<option value=""> Select </option>
										<?php	
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['reference_id'];?>" > <?php echo $r2['reference_id'];?></option>
										<?php } ?>
									</select>
																	
									</div>

									<div class="col-xs-2">								
									<label for="prDate" class="control-label">To </label>
                                    <select class="form-control select2" name="inward_no_to" id="inward_no_to" >
										<option value=""> Select </option>
											<?php //$sql = "select * from dms_inward  order by inward_no ";
												$sql = "select * from my_documents ". $tsql . " order by reference_id ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['reference_id'];?>" > <?php echo $r2['reference_id'];?></option>
										<?php } ?>
									</select>
									
								</div>
								
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date From </label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="date_from" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_from;?>" >
                                    </div>
									
								</div>	
								
								   <div class="col-xs-2">
									<label for="prDate" class="control-label">To </label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="date_to" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_to;?>" >
                                    </div>
									
								</div>
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Mode </label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt"    >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self</option>
									</select>	
                                </div>
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Department</label>
									<select class="form-control" name="department_for" id="department_for"    <?php echo $readonly; ?> >
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
								
                               <div class="col-sm-4">
									<label for="company_for" class="control-label">Company </label>
                                	<select class="form-control" name="company_for" id="companY"    <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company  order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<div class="col-md-4">	
									<label class=" control-label">Document Type</label>
									<select class="form-control doc_type select2" name="doc_type"   <?php echo $readonly; ?> >
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
									<select class="form-control col-sm-2 select2"  name="sent_by_user_vendor"   <?php echo $readonly; ?> >
										<option value="0">Select</option>
											<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user 
															union 
													       SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst`
															ORDER BY uvname ASC ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'].'-'.$r2['type'];?>" 
									<?php echo ($row['sent_by_user_vendor'].'-'.$row['sent_by_user_type'] == $r2['id'].'-'.$r2['type'])?'selected="selected"':'';?> >  
									<?php echo $r2['uvname'].' - ' . $r2['type'] ;?></option>
											<?php } ?>
									</select>
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-md-7">
									<label class="control-label ">Inward Description</label>
									<input type="text" class="form-control" id="remarks"  autocomplete="off" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
								</div>
								
								<div class="col-md-2">
									<label class="control-label ">Doc.Ref.No.</label>
									<select class="form-control select2" name="doc_ref_no" id="doc_ref_no" >
										<option value=""> Select </option>
											<?php $sql = "select * from dms_inward  order by doc_ref_no ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['doc_ref_no'];?>" > <?php echo $r2['doc_ref_no'];?></option>
										<?php } ?>
									</select>
									
								</div>
								
							</div>
							
							<?php $storage_type = $row['storage_type']  ?>
							
						<!--	<div class="form-group">
								<div class="col-md-3">
									<label class="control-label">Storage Type</label>
									<select class="form-control col-sm-2 " id="storage_type" name="storage_type" <?php echo $readonly; ?> >
										<option value=""  >Select</option>
										<option value="D" <?php echo ( $row['storage_type'] == 'D' )?'selected="selected"':'';?> >Digital</option>
										<option value="P" <?php echo ( $row['storage_type'] == 'P' )?'selected="selected"':'';?> >Physical</option>
										<option value="B" <?php echo ( $row['storage_type'] == 'B' )?'selected="selected"':'';?> >Both</option>
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
						-->
						
						</div>
						
						
						<div class="form-group">
								
								<!--<span id="getremind_me">
								
									<div class="col-md-2">
										<label class="control-label">Reminder day</label>
										<select class="form-control  " id="in_days" name="in_days" <?php echo $readonly; ?> >
											<option value=""  >Days</option>
											<option value="1" <?php echo ($row['in_days'] == '1' )?'selected="selected"':'';?> >1</option>
											<option value="2" <?php echo ($row['in_days'] == '2' )?'selected="selected"':'';?> >2</option>
											<option value="3" <?php echo ($row['in_days'] == '3' )?'selected="selected"':'';?> >3</option>
											<option value="4" <?php echo ($row['in_days'] == '4' )?'selected="selected"':'';?> >4</option>
											<option value="5" <?php echo ($row['in_days'] == '5' )?'selected="selected"':'';?> >5</option>
											<option value="6" <?php echo ($row['in_days'] == '6' )?'selected="selected"':'';?> >6</option>
											<option value="7" <?php echo ($row['in_days'] == '7' )?'selected="selected"':'';?> >7</option>
											<option value="8" <?php echo ($row['in_days'] == '8' )?'selected="selected"':'';?> >8</option>
											<option value="9" <?php echo ($row['in_days'] == '9' )?'selected="selected"':'';?> >9</option>
											<option value="10" <?php echo ($row['in_days'] == '10' )?'selected="selected"':'';?> >10</option>
										</select>	
									</div>
									<div class="col-md-3">
										<label class="control-label">Reminder Date</label>
										<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
											<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
											</div>
											<input type="text" class="form-control" id="reminder_date" name="reminder_date" <?php echo $readonly; ?> placeholder="dd/mm/yyyy" value="">
										</div>
									</div>
									-->
									<div class="col-md-3">
										<label class="control-label">Outward No.</label>
										<select class="form-control select2" name="outward_number" id="outward_number" >
										<option value=""> Select </option>
											<?php $sql = "select * from dms_inward  order by outward_number ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['outward_number'];?>" > <?php echo $r2['outward_number'];?></option>
										<?php } ?>
										</select>
									
									</div>
									
								</span>
								
							</div>
							
				<!--</div>-->
					
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
                <!--<div class="tab-pane" id="tab_2123">-->
                            <!-- Attachments -->
							
							<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									
								</div>
								
								<div class="col-sm-6 text-right">
									
										<!--<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
										<input class="btn btn-primary" type="submit" value="Search" name="search">&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="<?php echo $baseurl.'dms/document_search.php?sub=search';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>							
										
								</div>
								
							</div>
							
							<span class="pull-left" id="prshare">  </span>
							
						</div>
						
					</div>
			
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



<?php } ?>
	   
	 
<!--Forward Workflow Popup-->

<div class="modal fade" id="forwardAuthority" role="dialog" aria-labelledby="forwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="forwardAuthority">Handover To</h4>
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
										<input type="hidden" id="modeF" name="mode" value='Forward'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Handover To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control col-sm-10 " name="send_to" id="send_toF"  >
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
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksF"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitForward">Handover</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	



	   
<!--Shared Workflow Popup-->
<div class="modal fade" id="shareAuthority" role="dialog" aria-labelledby="shareAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="shareAuthority">Shared To</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
									<form class="form-horizontal">
										<div class="box-body">    
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idS" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Shared'>
										
										<!--<input type="hidden" id="statusS" name="status" value='<?php echo $status; ?>' >-->
										<input type="hidden" id="statusS" name="status" value="Accepted" >
										
											
										<div class="form-group">
											<div class="col-sm-12">
											<label class=" control-label">To Company (Company Wise for all employees)</label> <?php // multiple="multiple" ?>
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
												<label class=" control-label">To User (User wise)</label> <?php // multiple="multiple" ?>
												<select class="form-control select2" multiple name="send_to" id="send_toS" <?php echo $readonly; ?> >
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



<!--Outward Workflow Popup-->
<div class="modal fade" id="outwardAuthority" role="dialog" aria-labelledby="outwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="outwardAuthority">Outward</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                	<form class="form-horizontal">
										<div class="box-body123">    
										<?php   
											//$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idO" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeO" name="mode" value='Outward'>
										
										<input type="hidden" id="statusO" name="status" value="Outward" >
									
										<div class="form-group">									
											<div class="col-md-4" style="padding-top: 25px;">	
												<b>Internal</b>
												<input type="radio" class="minimal" id="ie_flag" name="ie_flag" value="I" onchange="getname(this.value)" > 
												<b>External</b>
												<input type="radio" class="minimal" id="ie_flag" name="ie_flag" value="E" onchange="getname(this.value)" > 
											</div>
										</div>
										
										<span id="getname">
										
											
										</span>
								
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksO"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitOutward">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>
<!--Outward Workflow Popup-->
	   
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
        
		var doc_scr			= 'MYD';
		
		//alert(sub + ' ' + mode);		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+inward_no);

	$('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						status:status,
						approver:approver,
						remarks:remarks,
						doc_scr:doc_scr,
						sub4:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

    $("#submitForward").on("click", function(e){
        var sub 			= 'sub5';
		var mode		 	=  $("#modeF").val();
		var send_to			=  $("#send_toF").val();
        
		var inward_no		=  $("#ap_idF").val();		
		var status 			=  $("#statusF").val();
		var remarks			=  $("#remarksF").val();

		var doc_scr			= 'MYD';
//alert(sub + ' ' + inward_no + ' ' + send_to + ' ' + status );	

		 $('#forwardAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						send_to:send_to,
						statusap:mode,
						remarks:remarks,
						doc_scr:doc_scr,
						sub5:sub},
						function(result){
		      $('#prshare').html(result);
		});
		
	});

	
	$("#submitShared").on("click", function(e){
        var sub 			= 'sub7';
		//var mode		 	=  $("#modeS").val();
		var send_to			=  '('+ $("#send_toS").val() +')';
        var send_to_comp	=  $("#send_toS_comp").val();
		var inward_no		=  $("#ap_idS").val();
		var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();
		
		var doc_scr			= 'MYD';
		
		$('#shareAuthority').modal('hide');
		
//		$('#getcheck').html("Hello World....");	
//		$('#prshare').html("Hello World....");
//		alert(sub + ' ' + send_to + ' ' + status );

		$('#shareAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						send_to:send_to,
						send_to_comp:send_to_comp,
						remarks:remarks,
						doc_scr:doc_scr,
						sub7:sub},
						function(result){
		      $('#prshare').html(result);
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
		
		window.location.href='document_search.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}

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
	
	
function getremind_me(id){
		
		var sub    = 'sub11';
//alert(id);
		var checkBox = document.getElementById("remind_me");
		if (checkBox.checked == true){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub11:sub},function(result){
				  $('#getremind_me').html(result);
			});
		}
		else{	
			$('#getremind_me').html('');
		}
	}	
	
	
	function getoutwno(id){
		
		var sub    = 'sub12';
//alert(id);
		var checkBox = document.getElementById("stop_remind");
		if (checkBox.checked == true){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub12:sub},function(result){
				  $('#getoutwno').html(result);
			});
		}
		else{	
			$('#getoutwno').html('');
		}
	}
	
	
	function selectall(id){
		
	    //var i = ParseInt(i) + 1;
		//alert('Hello Selec all....' );
		var sub = 'sub2';			
		var strURL = "search_func.php";
		$.post(strURL,{ sub2:sub},function(result){
				  $('#selectall').html(result);
			});
		
	}
	
	function getname(id){
		
		var sub    = 'sub5';			
		var strURL = "search_func.php";
		
		//alert('Hello Selec all....getname ==> ' + id );
		
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getname').html(result);
		});

	}	
	
	
    $("#submitOutward").on("click", function(e){
        var sub 			= 'sub14';
		//var mode		 	=  $("#modeS").val();
		var inward_no		=  $("#ap_idO").val();
		var status 			=  $("#statusO").val();
		var remarks			=  $("#remarksO").val();
		var ie_flag			=  $("#ie_flag").val();
		var send_to			=  $("#send_toS_a").val();
		var doc_scr			= 'MYD';
		
		$('#submitOutward').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						remarks:remarks,
						doc_scr:doc_scr,
						ie_flag:ie_flag,
						send_to:send_to,
						sub14:sub},
						function(result){
		      $('#prshare').html(result);
		});
		
		
		
	});
	
</script>

</body>
</html>
