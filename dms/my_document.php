<?php
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';

$userid   	    = $_SESSION['usrid'];
if($_GET['back']){
	$_SESSION['back'] =	$_GET['back'];
	
}

if($_SESSION['back']){
	$back   	      = $_SESSION['back'];
}	

//echo $back . " <<<>>>";

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
        My Document <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">My Document List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
			<?php
				//echo $role. '<<>>' . $department;
				$sql = "delete from forward_share_doc where userid = '$usrid' ";
				mysqli_query($con, $sql);
		//echo $sql;
		
				$targetpage = "my_document.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['status'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] = $_POST['searchf'];
					$_SESSION['search_data'] = $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];
					$_SESSION['search_own'] = $_POST['search_own'];
					
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['status'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['status'];
					$searchf 		= $_SESSION['searchf'];
					$search_data 	= $_SESSION['search_data'];
					$start_date 	= $_SESSION['start_date'];
					$end_date 		= $_SESSION['end_date'];
					$search_own 	= $_SESSION['search_own'];
					$_SESSION['reset']='';
				}
				
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){

					$_SESSION['search_own'] = '';
					$_SESSION['comp_id'] 	= '';
					$_SESSION['status'] 	= '';
					$_SESSION['searchf'] 	= '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id 	= $_SESSION['comp_id'];
					$status 	= $_SESSION['status'];
					$searchf 	= $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date 	= $_SESSION['end_date'];
					$search_own = $_SESSION['search_own'];
					
				}
				
			?>
			
					<form class="form-horizontal" action="my_document.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-5">
									<select class="form-control " name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
							<div class="col-xs-2">
                                		
								<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="my_document.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-1 control-label">Search.On</label>
							<div class="col-md-2">
								<select class="form-control " name="searchf" id="searchf" onchange="getsearchf(this.value)">
									<option value=""> Select </option>
									<option value="S" <?php echo ($searchf == 'S')?'selected="selected"':'';?>> Sender Name </option>
									<option value="D" <?php echo ($searchf == 'D')?'selected="selected"':'';?>> Date </option>
									<option value="N" <?php echo ($searchf == 'N')?'selected="selected"':'';?>> Document No. </option>
									<option value="C" <?php echo ($searchf == 'C')?'selected="selected"':'';?>> Common Document </option>
									<option value="P" <?php echo ($searchf == 'P')?'selected="selected"':'';?>> Description </option>
									<option value="O" <?php echo ($searchf == 'O')?'selected="selected"':'';?>> Doc.Ref.No. </option>
									<option value="T" <?php echo ($searchf == 'T')?'selected="selected"':'';?>> Storage Type </option>
									<option value="R" <?php echo ($searchf == 'R')?'selected="selected"':'';?>> Storage Rack </option>
									<option value="F" <?php echo ($searchf == 'F')?'selected="selected"':'';?>> File No./Name </option>
								</select>
							</div>  

						<?php	
							$checked ='';
							//echo $search_own." >><<";
							if($search_own=='Y'){$checked ='CHECKED';} 
						?>
								<span>
									<div class="col-md-2" ><b class="btn btn-info">Own</b>&nbsp;&nbsp;
										<input type="checkbox"  <?php echo $checked;?> id="search_own" name="search_own" value="Y" >
									</div>
								</span>

							
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S' || $searchf=='D' || $searchf=='T' || $searchf=='P' || $searchf=='F'){ ?>	
								<div class="col-md-4">
								<?php if( $searchf=='N' || $searchf=='P' ){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
								<?php if($searchf=='S'){ ?>
										<select class="form-control select2-123" name="search_data">
										<option value=""> Select </option>
											<?php $sql = " select * from sma_party_mst order by party_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
											<?php } ?>
										</select>
								<?php } ?>
								
								
								<?php if($searchf=='N'){ ?>
										<select class="form-control col-sm-2 " id="search_data" name="search_data" >
										<option value=""  >Select</option>
										<option value="D" <?php echo ($search_data == 'D' )?'selected="selected"':'';?> >Digital</option>
										<option value="P" <?php echo ($search_data == 'P' )?'selected="selected"':'';?> >Physical</option>
										<option value="B" <?php echo ($search_data == 'B' )?'selected="selected"':'';?> >Both</option>
										</select>
									
								<?php } ?>
								
								</div>
								
							<?php } ?>
							
							<?php 
								if($searchf=='D'){
									
									$start_datea = date('d-m-Y', strtotime($start_date));
									$end_datea   = date('d-m-Y', strtotime($end_date));
								if($start_datea =='01-01-1970'){
									$start_datea = date('d-m-Y');
								}
								if($end_datea =='01-01-1970'){
									$end_datea = date('d-m-Y');
								}
							?>
								<div class="col-md-2">
									<label class="control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_datea;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_datea;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							<?php } ?>		
									
							</span>
						
				<!--<span class="pull-right"><a href="#modalExport"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalExport" class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;</span>-->
											   
						</div>
						
				</form>

				<span class="pull-right">
					
							<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" 
									data-target="#forwardAuthority" title="Handover" ><i class='fa fa-sign-out-alt'></i> Handover</a>&nbsp;&nbsp;&nbsp;
								<?php //include "forward_func.php"; ?>
														
							<a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;
							
							<a href="#outwardAuthority" class="btn btn-info" data-toggle="modal" data-mode="Share" data-target="#outwardAuthority" title="Outward"><i class='fa fa-camera-retro'></i> Outward</a>&nbsp;&nbsp;&nbsp;
							
						<!--<span class="pull-right">			
								<button type="button" class="btn btn-primary " onclick="selectall()" >&nbsp; All &nbsp;</button> &nbsp
								<a href="<?php // echo $baseurl.$modulePath.'my_document.php?sub=list';?>" class="btn btn-default" >Reset/Uncheck</a> &nbsp
							</span>-->
							
				</span>
				
				<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=add"?>" class="btn btn-primary">Create My Document</a> &nbsp;&nbsp;&nbsp;</span>
							
				
		   </div>

		<form action="my_document.php?sub=forward" METHOD="POST" >
		
				            <!-- /.box-header -->
            <div class="box-body">
				<span id = "selectall">
				</span>
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
				
                    <th width="1%" ></th>
					<th width="8%">Document No.</th>
					<th width="10%">Inward Date</th>
					<th width="10%">For Company</th>
					<th width="20%">From Sender</th>
					
					<th width="20%" style="text-align:left;" >Doc Ref.No.</th>
					
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
					//FIND_IN_SET("q", "s,q,l");
					
					$userid   	    = $_SESSION['usrid'];
					
					$vstatus = "";
					if($searchf =='C' ){
						$vstatus = " and common_doc = 'Y' ";
					}
					
					$sqlown ='';
					if($search_own=='Y'){
						$sqlown = " and reviewed_by = '$userid' ";
					}
					
					if(!empty($comp_id)){
						$sqlcomp = " and company_for = '$comp_id' ";
					}
					
					$sent_by ='';
					if($searchf=='S' ){
						$sent_by = " and sent_by_user_vendor = '$search_data' ";
					}
					
					//$query="SELECT count(*) as num from dms_inward where inward_no in ($id_var) "; //and company_for in ( $comid )
					//$sql   = " select * from my_documents where current_user_id  = '$userid' ";
					$queryx = '';
					$sqlx = '';
					if ($user !='Admin'){
						$sql = " select common_doc, doc_type, doc_id, create_by, create_date, status, reviewed_by, inward_no , sent_by_user_vendor, 
										date_of_received, remarks, file_no, storage_type , company_for from
							( select c.common_doc, a.doc_type, a.doc_id, a.create_by, a.create_Date, a.status, a.reviewed_by, c.inward_no as inward_no, 
							 c.date_of_received, c.remarks, c.file_no, c.storage_type, c.sent_by_user_vendor, c.company_for from workflow_history a, my_documents b, 
							dms_inward c, ( SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and status in( 'Accepted' ) and ( reviewed_by = '$userid' ) group by doc_id ) as DS where a.id = DS.id and a.doc_type= 'IN' and a.status in( 'Accepted' ) and a.doc_id = b.reference_id and a.reviewed_by = b.current_user_id and a.doc_id = c.inward_no and c.company_for in ($comid) and a.reviewed_by = '$userid' 
							union 
							select ' ' as 'common_doc', a.doc_type, a.doc_id, a.create_by, a.create_Date, a.status, a.reviewed_by, a.doc_id as inward_no , '' as date_of_received, '' as remarks, '' as file_no, '' as storage_type , '' as sent_by_user_vendor, '' as company_for
							from workflow_history a, ( select max(id) as id from workflow_history a , dms_inward c where a.doc_id = c.inward_no and a.status='Accepted' and c.on_behalf = '$userid' group by doc_id ) as DS1 where a.id = DS1.id
							union
							select c.common_doc, a.doc_type, a.doc_id, a.create_by, a.create_Date, a.status, a.reviewed_by, c.inward_no as inward_no , c.date_of_received, c.remarks, c.file_no, c.storage_type , '' as sent_by_user_vendor, c.company_for
							from workflow_history a, my_documents b, dms_inward c
							where a.doc_type= 'IN' and a.status in( 'Accepted' ) and a.doc_id = b.reference_id and a.reviewed_by = b.current_user_id 
							  and a.doc_id = c.inward_no and c.common_doc = 'Y' and c.company_for in ($comid) 
							) DS2 where 1 $vstatus $sqlown $sent_by $sqlcomp group by doc_id " ;
//echo $sql;
					}
					
					if ($user =='Admin'){
						
						$sql = " SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and status in( 'Accepted' ) group by doc_id " ;
						$qry = mysqli_query($con,$sql);
						while($row = mysqli_fetch_array($qry)){
							$var_id .= $row['id'].',';
						}
						$var_id .= '0';
						/* 
						 $sql = "select a.*, c.inward_no from workflow_history a , my_documents b, dms_inward c, 
							(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and status in( 'Accepted' )  group by  doc_id ) as DS
							where a.id = DS.id and a.doc_type= 'IN' and a.status in( 'Accepted' )  and a.doc_id = b.reference_id 
							 and a.doc_id = c.inward_no " . $sqlcomp ; //, 'Forwarded' and a.reviewed_by = b.current_user_id
						$qry = mysqli_query($con,$sql);
						  */
						 $sql = "select a.*, c.inward_no from workflow_history a , my_documents b, dms_inward c 
							where a.id in ($var_id) and a.doc_type= 'IN' and a.status in( 'Accepted' )  and a.doc_id = b.reference_id 
							 and a.doc_id = c.inward_no " . $sqlcomp . $vstatus . $sqlown  .$sent_by ; 
					//	$qry = mysqli_query($con,$sql); 
					//echo $sql;	
					}
					
					if ($_GET['remind']){
						
						$sql = "select a.*, c.inward_no from workflow_history a , my_documents b, dms_inward c 
							where a.doc_type= 'IN' and a.status in( 'Accepted' )  and a.doc_id = b.reference_id 
								and a.doc_id = c.inward_no and remind_me = 'Y' and stop_remind != 'Y'  "; 
						if ($user !='Admin'){
							$sql .= " and c.company_for in ($comid) and a.reviewed_by = '$userid' ";
						}
						$sql .= "group by c.inward_no";
						
					}
		//echo $sql;
		
		//Filing Role			
					$role = $_SESSION['role'];
					if ($role =='Filing'){
						$sqlx = '';	
						$sql = "select * from my_documents b, dms_inward c where c.inward_no = b.reference_id and status = 'Accepted' and c.filing_yn !='N'"; //, 'Forwarded'
						$qry = mysqli_query($con,$sql);
						
						$query = "select count(*) as num from my_documents b, dms_inward c where c.inward_no = b.reference_id and status = 'Accepted'  and c.filing_yn !='N' "; //, 'Forwarded'/
					}
					
//echo $role;
//echo $sql;
					
					
					if($searchf=='N' ){
						$sql .= " and  inward_no = '$search_data' ";
						$query .= " and  inward_no = '$search_data' ";
						
						$sqlx = '';
						$queryx = '';
						
					}
					else if	( $searchf=='D' ){
						$start_date = date('Y-m-d', strtotime($start_date));
						$end_date   = date('Y-m-d', strtotime($end_date));						
						$sql .= " and date_of_received >= '$start_date' and date_of_received <= '$end_date' ";	
					}
					else if	( $searchf=='P' ){
						$sql .= " and c.remarks like '%".$search_data."%' ";
					}
					else if	( $searchf=='F' ){
						$sql .= "  and file_no like '%".$search_data."%' ";
					}
					else if	( $searchf=='T' ){
						$sql .= " and storage_type = '$search_data' ";
					}
					
					$sql .= $sqlx;
					$_SESSION['sqlex'] = $sql;

//echo $sql . "<BR>";
//echo $query;
//exit();
//$query .= $queryx;
//echo $query."<BR>";
					//$qresult = mysqli_query($con,$query);
					$qresult = mysqli_query($con,$sql);
					$total_pages = mysqli_affected_rows($con);
					echo mysqli_error($con);
					//$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					//RAVI $total_pages = $total_pages[num];
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
		?>	
		
			<div style="height:350px;overflow:scroll;border:1px #999;">
				<table id="prtable123" class="table table-bordered table-striped">
		<?php
				while($row = mysqli_fetch_array($result)){
						
						$status  = $row['status'];
				
						$sent_by = $row['create_by'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_by' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$sent_by  = $r2['username'];
						
						
						$inward_no = $row['inward_no'];
						$sent_to   = $row['reviewed_by'];
						
						$sql="SELECT * from dms_inward where inward_no ='$inward_no' ";
				//echo $sql . "<BR>";		
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$sent_by_user_type = $r1['sent_by_user_type'];
						$on_behalf		   = $r1['on_behalf'];
						$doc_ref_no		   = $r1['doc_ref_no'];
						$remarks		   = $r1['remarks'];
						
						
					if($role=='Filing'){
							
						$sql = " SELECT max(doc_id) as doc_id , status, inward_status, reviewed_by FROM `workflow_history` where doc_type= 'IN' and status in ( 'Sent', 'Accepted' ) and doc_id = '$inward_no' ";
						//echo $sql."<BR>";
						$qry= mysqli_query($con, $sql);
						$r3 = mysqli_fetch_array($qry);
						$sent_to   	    = $r3["reviewed_by"];
						$statusa		= $r3["status"];

					}
					
					if(!empty($on_behalf)){
						$sent_to = $on_behalf;
						$sent_by_user_type ='';
					}
					
				/*	if($sent_by_user_type=='P'){
						$sql = "SELECT * FROM `sma_party_mst` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$send_to  = $r2['party_name'];
					}
					else {
				*/		
						$sql = "SELECT * FROM `sma_user` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$send_to  = $r2['username'];
					//}
		//echo $sent_by_user_type. ' ' .$sql."  >>><<<<BR>";				
						$to_others = $r1['to_others'];
						if( !empty($to_others) ){
							$send_to = $to_others;
						}
		//echo $to_others." <<<>>><BR>";
						$company = $r1['company_for'];
						$date_of_received = date('d-m-Y h:i:s', strtotime($r1['date_of_received']));
						$outward_no = $r1['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_code'];

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

					$sent_by_user_vendor	= $r1['sent_by_user_vendor'];
					$sent_by_user_type 		= $r1['sent_by_user_type'];
					if ($sent_by_user_type =='P'){
						$sql = "SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst`  where id = '$sent_by_user_vendor' ";
					}
					else {
						$sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user  where id = '$sent_by_user_vendor' ";
					}	
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$sender_name 	= $r2['uvname'];
					
					$sent_by		= $r1['sent_by'];
					if(!empty($sent_by)){
						$sender_name 	= $sent_by;
					}
					
					$storage_type	= $r1['storage_type'];
					if($storage_type == 'D'){
						$storage_type = 'Digital';
					}
					else if($storage_type == 'P'){
						$storage_type = 'Physical';
					}
					else if($storage_type == 'B'){
						$storage_type = 'Both';
					}
				
				$styl="";
				$styl2="";
				//$status	='';
				//echo $status."<<>>";
			
				$j = $j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				
				$baseurl1 = $baseurl.$modulePath.'my_document.php?sub=edit&inward_no='.$inward_no;

		?>
	<!--<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">-->
			<tr>
			
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="8%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $date_of_received;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="20%" <?php echo $styl; ?>><?php echo $sender_name;?></td>
					<td width="20%" <?php echo $styl; ?>><?php echo $doc_ref_no;?></td>
					<td width="25%" <?php echo $styl; ?>><?php echo $remarks;?></td>
					
					<td width="08%"<?php echo $styl; ?>>
						<input type="checkbox" name="forward_check[]" id="forward_check" class="forward_check" value="<?php echo $inward_no; ?>" onclick="getchecked(this.value)" >
					&nbsp;&nbsp;
						<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>" title='Edit' ><i class="fa fa-fw fa-edit"></i> </a>
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
		
		<div id ="getcheck"></div>
		<div id ="prshare"></div>
		
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
	
	if( $_GET['sub'] == 'delete' ){

        $did  = $_GET['did'];

		$sql = " delete from dms_inward where inward_no = '$did' ";
//echo $sql; exit();
		$r2  = mysqli_query($con, $sql);
		
		$baseurl .= $modulePath.'my_document.php?sub=list';
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
?>


<?php  
	if($_GET['sub']=='add'){
		echo $_POST['save'];
		//exit();
		
			if($_POST['save']){
			$inward_no			= $_POST['inward_no'];
			$date_of_received	= date('Y-m-d h:i:s', strtotime($_POST['date_of_received']));
			$account_year		= $_POST['account_year'];
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			
			$reminder_date		= date('Y-m-d', strtotime($_POST['reminder_date']));
			$in_days			= $_POST['in_days'];
			$outward_number		= $_POST['outward_number'];
			$remind_me			= $_POST['remind_me'];
			$stop_remind		= $_POST['stop_remind'];
			
			$storage_type		= $_POST['storage_type'];
			$storage_rack		= $_POST['storage_rack'];
			$shelf_no			= $_POST['shelf_no'];
			$file_no			= $_POST['file_no'];
			$doc_ref_no			= $_POST['doc_ref_no'];
			$on_behalf			= $_POST['on_behalf'];
			$importance			= $_POST['importance'];
			
			$document_date      = date('Y-m-d', strtotime($_POST['document_date']));
			
			$filing_yn			= $_POST['filing_yn'];
			$common_doc			= $_POST['common_doc'];
			
			$saved 				= 'Y';
			
			$sent_by_user_vendor_list		= explode("-", $_POST['sent_by_user_vendor']);
			
			$sent_by_user_vendor= $sent_by_user_vendor_list['0'];
			$user_type			= $sent_by_user_vendor_list['1'];
			
			if(!empty($sent_by)){
				
				$sql = "insert into sma_party_mst (party_name ) Values( '$sent_by' ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				//if(!empty($error)){echo $error; exit();}
				if(empty($error)){
					$sent_by_user_vendor = mysqli_insert_id($con);
					$user_type			 = 'P';
				}
			}	
			
			
			$doc_type			= $_POST['doc_type'];
			$mode_of_receipt	= $_POST['mode_of_receipt'];
			
			$status 			= 'Accepted';

			$user   			= $_SESSION['user'];
			$userid   			= $_SESSION['usrid'];
			$send_to_user		= $userid;
				
			if ($role =='Filing'){
				$send_to_user = $on_behalf ;
			}
			
			$sql = " SELECT inward_no FROM `dms_inward` where inward_no = '$inward_no' ";
			$qry = mysqli_query($con, $sql);
			$r2	 = mysqli_fetch_array($qry);
			$inw_no = $r2['inward_no'];
			if( $inw_no == '$inward_no' ){
				$sql = " SELECT max(inward_no) as inward_no FROM `dms_inward` where inward_no = '$inward_no' ";
				$qry = mysqli_query($con, $sql);
				$r2	 = mysqli_fetch_array($qry);
				$inw_no = $r2['inward_no'];
				$inward_no = $inw_no + 1;
			}
			
  			$sql = "insert into dms_inward ( inward_no, date_of_received,  company_for, department_for, mode_of_receipt, doc_type, sent_by, send_to_user, status, remarks, draft_by, draft_dated,  user_type, sent_by_user_vendor, sent_by_user_type, storage_type, storage_rack, shelf_no, file_no , doc_ref_no, reminder_date, outward_number, remind_me, stop_remind , in_days, filing_yn, on_behalf, document_date, importance, common_doc ) 
			Values ( '$inward_no', '$date_of_received', '$company_for', '$department_for', '$mode_of_receipt', '$doc_type', '$sent_by', '$send_to_user', '$status', '$remarks', '$user', now(), 'U', '$sent_by_user_vendor', '$user_type', '$storage_type', '$storage_rack', '$shelf_no', '$file_no', '$doc_ref_no', '$reminder_date', '$outward_number', '$remind_me', '$stop_remind', '$in_days', '$filing_yn', '$on_behalf', '$document_date', '$importance', '$common_doc' )";

			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error.'####1'; exit();}
		

//DMS_Description Insert
			$sql = "select * from dms_description where description = '$remarks' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$description = $r2['description'];
			if( $description != '$remarks' && (!empty($remarks)) ){
				$sql = "insert into dms_description ( description, updated_on, updated_by) values ('$remarks', now(), '$userid') ";
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);
				if(!empty($error)){echo $error.'####1 DMS'; exit();}
			}
//DMS_Description Insert			
		
			$sql = " insert into my_documents ( module, reference_id, current_user_id, date_uploaded, common_doc ) values ( 'IN', '$inward_no', '$send_to_user', now(), '$common_doc' ) ";
			$r2  = mysqli_query($con, $sql);
			if(!empty($error)){echo $error.'####2'; exit();}
			
			$sql = " insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
			$r2  = mysqli_query($con, $sql);
			if(!empty($error)){echo $error.'####3'; exit();}
			
			$sql = "update `dms_srno` set inward_no = '$inward_no' where inward_no < '$inward_no' ";
			$qry = mysqli_query($con, $sql);
			if(!empty($error)){echo $error.'####4'; exit();}
					
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc   = $_FILES["fudoc"];

//echo $arrFUDoc;
		
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/in/" . $inward_no;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/inbox.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
			
				$tmpFileName = $arrFUDoc['tmp_name'][$i];

//echo "<BR>". $filename. ' <<<>>> ' . $tmpFileName . " <<<>>>" ;
				
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

				include "mail_on_behalf.php";
				
//echo 	$sql;	
//exit();
	
//			echo "Inward Memo successful added";
			$baseurl.=$modulePath.'my_document.php?sub=list';
			//$baseurl.=$modulePath.'edit.php?id='.$id.'&active=active';
//echo 	$baseurl;
//exit();
			
			echo "<script>window.location.href='$baseurl';</script>";
			
	}
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            My Document 
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">My Document </a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create My Document </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="my_document.php?sub=add" method="post"  enctype="multipart/form-data">

							<?php
									
									$sql = "SELECT inward_no FROM `dms_srno` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
									
							?>
							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Document No.</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;font-size:24px; font-family: Arial, Helvetica, sans-serif;" value="<?php echo $inward_no;?>">
                                
								</div>
								<?php
									//$date_of_received = date('d-m-Y');
									$date_of_received_time = date('d-m-Y h:i:s');
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
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self</option>
									</select>	
                                </div>
								
								
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
									<select class="form-control col-sm-2 doc_type select2" name="doc_type"   <?php echo $readonly; ?> >
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
									<select class="form-control col-sm-2 select2"  name="sent_by_user_vendor"   <?php echo $readonly; ?> onchange="getsendothers(this.value)" >
										<option value="0">Select</option>
										<option value="9999">Add New (Others)</option>	
											<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active='1'
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
							
								<div class="col-md-4">
								<span id="getsendothers">
									
								</span>
								</div>
								
								<div class="col-md-2" >
									<label class=" control-label">Filing ?</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="filing_yn" name="filing_yn" value="Y" > 
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label"> Description *</label>
								<?php 
									$sql = "select * from dms_description  order by description ";
								?>
									<select class="form-control  select2"  name="description" onchange="getremarks(this.value)" >
										<option value="0">Select Description</option>
											<?php 
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" <?php echo ($row['remarks'] == $r2['description'])?'selected="selected"':'';?> > <?php echo $r2['description'] ;?></option>
											<?php } ?>
									</select>

									<span id= "getremarks">	
										<input type="text" class="form-control" id="remarks" required autocomplete="off" placeholder="New Description Enter." name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
									</span>
									
								</div>
							</div>
							
							<div class="form-group">	
								<div class="col-md-4">
									<label class="control-label">Doc.Ref.No.</label>
									<input type="text" class="form-control" id="doc_ref_no"  autocomplete="off" name="doc_ref_no" <?php echo $readonly; ?> value="<?php echo $row['doc_ref_no'];?>" >
								</div>
								
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Document Date</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="document_date" placeholder="dd/mm/yyyy"
                                               value="<?php echo $document_date;?>"  <?php echo $readonly; ?> >
                                    </div>
								</div>
								
								<div class="col-md-2">
									<label class="control-label">Importance</label>
									<select class="form-control select2"  name="importance" id="importance" <?php echo $readonly; ?> >
										<option value="">Select</option>
										<option value="H">High</option>
										<option value="M">Medium</option>
										<option value="L">Low</option>
									</select>
								</div>
								
							</div>
							
							<?php $on_behalf = $row['on_behalf']; ?>
							<div class="form-group">	
								<div class="col-md-3">
									<label class="control-label">On Behalf</label>
									<select class="form-control  select2"  name="on_behalf" id="on_behalf" <?php echo $readonly; ?> >
										<option value="0">Select</option>
											<?php $sql = " SELECT * FROM sma_user where active='1' ORDER BY username ASC ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" 
											<?php echo ($on_behalf == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['username'] ;?></option>
											<?php } ?>
									</select>
								</div>
							
								
								<div class="col-md-2" >
									<label class=" control-label">Common Document</label><br> &nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="common_doc" name="common_doc" value="Y" > 
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
							
								<div id = "getsrack123">
									<div class="col-md-3">
										<label class="control-label">Storage Rack</label>
										<input type="text" class="form-control" id="storage_rack"  autocomplete="on" name="storage_rack" <?php echo $readonly; ?> value="<?php echo $row['storage_rack'];?>" >
									</div>
								</div>
								
								<div id = "getshelf123">							
									<div class="col-md-3">
										<label class="control-label">Shelf No.</label>
										<input type="text" class="form-control" id="shelf_no"  autocomplete="on" name="shelf_no" <?php echo $readonly; ?> value="<?php echo $row['shelf_no'];?>" >
									</div>
								</div>
								
								<div id = "getfilen123">		
									<div class="col-md-3">
										<label class="control-label">File No./ Name</label>
										<input type="text" class="form-control" id="file_no"  autocomplete="on" name="file_no" <?php echo $readonly; ?> value="<?php echo $row['file_no'];?>" >
									</div>
								</div>
									
							</div>
							
							<div class="form-group">
								
								<div class="col-md-2" >
								<label class=" control-label">Remind Me</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="remind_me" name="remind_me" value="Y" onchange="getremind_me(this.value)" > 
								</div>
								
								<span id="getremind_me">
								
							<?php 
								$reminder_date = date('d-m-Y', strtotime($row['reminder_date']));
								if($reminder_date=='01-01-1970'){
									$reminder_date='';
								}
									
								$remind_me = $row['remind_me'];
								//$remind_me = 'Y';
								if($remind_me=='Y'){ ?>
									<div class="col-md-2">
										<label class="control-label">Remind before - Day</label>
										<select class="form-control  " id="in_days" name="in_days" <?php echo $readonly; ?> >
											<option value=""  >Select</option>
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
											<input type="text" class="form-control" id="reminder_date" name="reminder_date" <?php echo $readonly; ?> placeholder="dd/mm/yyyy" value="<?php echo date('d-m-Y');?>">
										</div>
									</div>
									
									<div class="col-md-2" >
										<label class=" control-label">Stop Reminding</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="checkbox" class="minimal" id="stop_remind" name="stop_remind" value="Y" > 
									</div>
									
								<?php } ?>	
								</span>
								
								<div class="col-md-3">
									<label class="control-label">Outward No.</label>
									<input type="text" class="form-control" id="outward_number" name="outward_number" value="<?php echo $outward_number;?>">
								</div>
								
							</div>
							
						
				<!--</div>-->
					
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
                <!--<div class="tab-pane" id="tab_2123">-->
                            <!-- Attachments -->
							
							 <?php
							
							if($role=='Filing'){		
								$sql = "SELECT * FROM my_documents_files WHERE module = 'IN' AND reference_id = '$inward_no' ";
							}
							else {	
								$sql = "SELECT * FROM my_documents_files WHERE module = 'IN' AND reference_id = '$inward_no' and ( current_user_id = '$userid' or forwarded_to = '$userid' || '$userid' = '$on_behalf' ) ";
							}

			
					
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
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' and type = 'DMS' ";
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
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where type = 'DMS' ORDER BY document ASC";
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
									<?php $did = $inward_no; ?>
								
							<?php		
								if ( $status != 'Submited' && $user=='Admin' ){
							?>
									<a href="<?php echo $baseurl.$modulePath."my_document.php?sub=delete&did=$did";?>" class="btn btn-danger" >Delete </a>
									<span>&nbsp;&nbsp;</span>
							<?php } 
							
							//echo $user."<<>>". $saved ."<>";?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
								
								<?php
									if($role=='Filing'){
								?>		
										<input class="btn btn-primary" type="submit" value="My Document" name="save">&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
									}
								?>
								
								<?php if ($saved=='Y'){ ?>
											<label class="control-label">&nbsp;</label>
											<a href="#sendAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#sendAuthority">Forward </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Accept </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											
											<!--<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
									<?php } 
										else if ($saved!='Y' || $user=='Admin'){
									?>
									
										<input class="btn btn-primary" type="submit" value="Save" name="save">&nbsp;&nbsp;&nbsp;&nbsp;
									<?php }  ?>	
										<!--<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
										<a href="<?php echo $baseurl.$modulePath.'my_document.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>							
										
								</div>
								
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
<?php	
	
	}
?>
  
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
			$status				= 'Accepted';
			$outward_no			= $_POST['outward_no'];
			$approval_status	= $_POST['approval_status'];
			
			$reminder_date		= date('Y-m-d', strtotime($_POST['reminder_date']));
//echo $reminder_date. ' <<>> '; exit();			
			$in_days			= $_POST['in_days'];
			$outward_number		= $_POST['outward_number'];
			$outward_number1	= $_POST['outward_number1'];
			$remind_me			= $_POST['remind_me'];
			$stop_remind		= $_POST['stop_remind'];
			$filing_yn			= $_POST['filing_yn'];
			$common_doc			= $_POST['common_doc'];
			$importance			= $_POST['importance'];
			
			$doc_ref_no			= $_POST['doc_ref_no'];
			$on_behalf			= $_POST['on_behalf'];
			$document_date      = date('Y-m-d', strtotime($_POST['document_date']));
			
  			$sql = "update dms_inward set storage_type	= '$storage_type',
										storage_rack	= '$storage_rack',
										shelf_no		= '$shelf_no',
										file_no			= '$file_no',
										company_for		= '$company_for',
										department_for	= '$department_for',
										remarks			= '$remarks',
										document_date	= '$document_date',
										sent_by			= '$sent_by',
										sent_by_user_vendor= '$sent_by_user_vendor',
										sent_by_user_type= '$sent_by_user_type',
										doc_type		= '$doc_type',
										status			= '$status',
										approval_status	= '$approval_status',
										doc_ref_no		= '$doc_ref_no',
										reminder_date	= '$reminder_date',
										outward_number	= '$outward_number',
										outward_number1 = '$outward_number1',
										remind_me		= '$remind_me',
										stop_remind		= '$stop_remind',
										in_days			= '$in_days',
										filing_yn		= '$filing_yn',
										common_doc		= '$common_doc',
										on_behalf		= '$on_behalf',
										importance		= '$importance',
										saved 			= '$saved'
					where inward_no='$inward_no' ";
					
			
//echo $sql;	exit();//send_to_user	= '$send_to_user',
										
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$userid   	    = $_SESSION['usrid'];
//echo $sql;	exit();			
			
//DMS_Description Insert
			$sql = "select * from dms_description where description = '$remarks' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$description = $r2['description'];
			if( $description != '$remarks' && (!empty($remarks)) ){
				$sql = "insert into dms_description ( description, updated_on, updated_by) values ('$remarks', now(), '$userid') ";
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);
				if(!empty($error)){echo $error.'####1 DMS'; exit();}
			}
//DMS_Description Insert

//On Behalf
			if ($role =='Filing'){
				$send_to_user = $on_behalf ;
			}
			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
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
					
					$findex = $folder_path.'/my_document.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					
					//$sql = " delete from my_documents_files where reference_id = '$inward_no' ";
					//mysqli_query($con, $sql);
					
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
			//$baseurl.=$modulePath.'my_document.php?sub=list';
			$baseurl.=$modulePath.'my_document.php?sub=list&inward_no='.$inward_no;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	
	//echo $sql;
	
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status 		= $row['status'];
	$inward_no		= $row['inward_no'];
	$send_to_user	= $row['send_to_user'];
	$saved 			= $row['saved'];
	
	$userid   	    = $_SESSION['usrid'];
	$sql = "SELECT max(doc_id) as doc_id , status FROM `workflow_history` where doc_type= 'IN' and (status ='Received' or status ='Accepted' ) and reviewed_by = '$userid' and doc_id = '$inward_no' ";
	//echo $sql;
	
	$qry = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($qry);
	$doc_id = $rs['doc_id'];
	if($doc_id>0){
		$status = $rs['status'];
	}
	
	$readonly = '';
	
	if ( ($status == 'Submited' && $user!='Admin' )  ){ //|| $status == 'Accepted'
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
            My Document 
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">My Document</a></li>
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
						<form id="form1" class="form-horizontal" action="my_document.php?sub=edit" method="post" enctype="multipart/form-data">

						<?php	
							$last_status = $row['status'];
							if ( $last_status == 'Accepted' || $last_status == 'Forwarded' ){
								
								$last_status = 'My Document';
								
							}
						?>
		                <span class="pull-right"><h4 style="color:red;"><b><?php echo $last_status;?></b></h4> </span>


						<span class="pull-right">
							
					<?php 
					if($back=='s'){ ?>	
							<a href="<?php echo $baseurl.$modulePath.'document_search.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>
					<?php } 
					else { ?>
							<a href="<?php echo $baseurl.$modulePath.'my_document.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>	
					<?php } ?>
					
							<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" 
									data-target="#forwardAuthority" title="Forward" ><i class='fa fa-sign-out-alt'></i> Forward</a>&nbsp;&nbsp;&nbsp;
								<?php //include "forward_func.php"; ?>
									
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
                        <li class="<?php echo $active_1;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" >My Document</a></li>
                       <!-- <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="third_tab">Documents</a></li>-->
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<!--<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
							<?php 
								$_SESSION['inward_no'] 	= $row['inward_no']; 
								$inward_no 				= $row['inward_no']; 
							?>
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							
							<input type="hidden" name="outward_no" id = "oD" value="<?php echo $row['outward_no'];?>" >
							<input type="hidden" name="approval_status" id = "approval_status" value="<?php echo $row['approval_status'];?>" >
							
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
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
									<label for="prDate" class="control-label">Create Date</label>
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
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt" required <?php echo $readonly; ?> onchange="hidesender(this.value);">
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self</option>
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
								<div class="col-md-3">	
									<label class=" control-label">Document Type</label>
									<select class="form-control  doc_type select2" name="doc_type"   <?php echo $readonly; ?> >
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
									
								<?php
									$sent_by_user_type = $row['sent_by_user_type'];
									/* if($sent_by_user_type=='P'){
										$sql =" SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst` ORDER BY uvname ASC ";
									}
									else {
										$sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user ORDER BY uvname ASC ";
									} */
								?>

								<div class="col-md-4" id="hidesender"  >		
									<label class=" control-label">Sender </label>
									<select class="form-control col-sm-2 select2"  name="sent_by_user_vendor"   <?php echo $readonly; ?> onchange="getsendothers(this.value)" >
										<option value="0">Select</option>
										<option value="9999">Add New (Others)</option>	
											<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active='1'
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
							
								<div class="col-md-4">		
									<span id="getsendothers">
									<?php $sent_by = $row['sent_by']; 
										if( !empty($sent_by) ){
									?>
										<label class="control-label">Provide Sender Name (if not available in dropdown)</label>
										<input type="text" class="form-control" id="sent_by" name="sent_by" autocomplete="off" <?php echo $readonly; ?> 
										value="<?php echo $sent_by;?>" >
									<?php } ?>
									</span>
								</div>
							
						<?php 
							$filing_checked = '';
							$filing_yn = $row['filing_yn'];
							if( $filing_yn == 'Y' ){
								$filing_checked = "CHECKED";
							}
						?>
								<div class="col-md-1" >
									<label class=" control-label">Filing?</label><br> &nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="filing_yn" name="filing_yn" <?php echo $filing_checked; ?> value="Y" > 
								</div>
								
						
						</div>
							
						<?php $sql = "select * from dms_description  order by description ";
						
						?>
						<div class="form-group">
								<div class="col-md-12">
									<label class="control-label "> Description * </label>
									<select class="form-control select2"  name="description" onchange="getremarks(this.value)" >
										<option value="0">Select Description</option>
											<?php 
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" <?php // echo ($row['remarks'] == $r2['description'])?'selected="selected"':'';?> > <?php echo $r2['description'] ;?></option>
											<?php } ?>
									</select>

									<span id="getremarks">	
										<input type="text" class="form-control" id="remarks" required autocomplete="off" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
									</span>
								</div>
						</div>
						
						<div class="form-group">						
								<div class="col-md-4">
									<label class="control-label ">Doc.Ref.No.</label>
									<input type="text" class="form-control" id="doc_ref_no"  autocomplete="off" name="doc_ref_no" <?php echo $readonly; ?> value="<?php echo $row['doc_ref_no'];?>" >
								</div>
								<?php
									$document_date = date('d-m-Y', strtotime($row['document_date']));
									$document_date = date('d-m-Y', strtotime($row['document_date']));
									if($document_date=='01-01-1970'){
										$document_date='';
									}
								?>
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Document Date</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="document_date" placeholder="dd/mm/yyyy"
                                               value="<?php echo $document_date;?>"  <?php echo $readonly; ?> >
                                    </div>
									
								</div>
								<?php 
									$importance = $row['importance'];
								
								?>
								<div class="col-md-2">
									<label class="control-label">Importance</label>
									<select class="form-control  select2"  name="importance" id="importance" <?php echo $readonly; ?> >
										<option value="">Select</option>
										<option value="H" <?php echo ($importance == 'H')?'selected="selected"':'';?> >High</option>
										<option value="M" <?php echo ($importance == 'M')?'selected="selected"':'';?> >Medium</option>
										<option value="L" <?php echo ($importance == 'L')?'selected="selected"':'';?> >Low</option>
									</select>
								</div>
								
							</div>
							<?php $on_behalf = $row['on_behalf']; ?>
							<div class="form-group">
								
								<div class="col-md-3">
									<label class="control-label">On Behalf</label>
									<select class="form-control col-sm-2 select2"  name="on_behalf" id="on_behalf" <?php echo $readonly; ?> >
											<option value="0">Select</option>
												<?php $sql = " SELECT * FROM sma_user where active='1' ORDER BY username ASC ";
												$q2 = mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
													<option value="<?php echo $r2['id'];?>" 
												<?php echo ($on_behalf == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['username'] ;?></option>
												<?php } ?>
											
									</select>
									
								</div>
						

						<?php 
							$common_doc_checked = '';
							$common_doc = $row['common_doc'];
							if( $common_doc == 'Y' ){
								$common_doc_checked = "CHECKED";
							}
						?>
								<div class="col-md-2" >
									<label class=" control-label">Common Document</label><br> &nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="common_doc" name="common_doc" <?php echo $common_doc_checked; ?> value="Y" > 
								</div>
								
								
							</div>
							
							<?php $storage_type = $row['storage_type']  ?>
							
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label">Storage Type</label>
									<select class="form-control col-sm-2 " id="storage_TYPE" name="storage_type" <?php echo $readonly; ?> >
										<option value=""  >Select</option>
										<option value="D" <?php echo ( $row['storage_type'] == 'D' )?'selected="selected"':'';?> >Digital</option>
										<option value="P" <?php echo ( $row['storage_type'] == 'P' )?'selected="selected"':'';?> >Physical</option>
										<option value="B" <?php echo ( $row['storage_type'] == 'B' )?'selected="selected"':'';?> >Both</option>
									</select>	
								</div>
							
								<div id = "getsrack123">
									<div class="col-md-3">
										<label class="control-label">Storage Rack</label>
										<input type="text" class="form-control" id="storage_rack"  autocomplete="on" name="storage_rack" <?php echo $readonly; ?> value="<?php echo $row['storage_rack'];?>" >
									</div>
								</div>
								
								<div id = "getshelf123">							
									<div class="col-md-3">
										<label class="control-label">Shelf No.</label>
										<input type="text" class="form-control" id="shelf_no"  autocomplete="on" name="shelf_no" <?php echo $readonly; ?> value="<?php echo $row['shelf_no'];?>" >
									</div>
								</div>
								
								<div id = "getfilen123">		
									<div class="col-md-3">
										<label class="control-label">File No./ Name</label>
										<input type="text" class="form-control" id="file_no"  autocomplete="on" name="file_no" <?php echo $readonly; ?> value="<?php echo $row['file_no'];?>" >
									</div>
								</div>
							
						</div>
						
						<?php 
							$checked = '';
							$remind_me = $row['remind_me'];
							if($remind_me =='Y'){
								$checked = "CHECKED";
							}
						?>
						
						<div class="form-group">
								
								<div class="col-md-2" >
								<label class=" control-label">Remind Me</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="checkbox" class="minimal" id="remind_me" name="remind_me" <?php echo $checked; ?> value="Y" onchange="getremind_me(this.value)" > 
								</div>
								
								<span id="getremind_me">
								
							<?php
							
								$reminder_date = date('d-m-Y', strtotime($row['reminder_date']));
								if($reminder_date=='01-01-1970'){
									$reminder_date=	date('d-m-Y');
								}
									
								$remind_me = $row['remind_me'];
								//$remind_me = 'Y';
								if($remind_me=='Y'){ ?>
									<div class="col-md-2">
										<label class="control-label">Remind before - Day</label>
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
											<input type="text" class="form-control" id="reminder_date" name="reminder_date" <?php echo $readonly; ?> placeholder="dd/mm/yyyy" value="<?php echo $reminder_date ;?>">
										</div>
									</div>
									
									<div class="col-md-2" >
										<label class=" control-label">Stop Reminding</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="checkbox" class="minimal" id="stop_remind" <?php echo ($row['stop_remind'] == 'Y' )?'CHECKED="CHECKED"':'';?>  name="stop_remind" value="Y" > 
									</div>
									
								<?php	
									$sent_by_user_vendor = $row['sent_by_user_vendor'];
									$sent_by_user_type   = $row['sent_by_user_type'];
									
									$stop_remind = $row['stop_remind'];
									if($stop_remind == 'Y' ){
										$outward_number = $row['outward_number'];
									}	
									
								?>
								
									
								<?php } ?>
								</span>
								
								<?php 
										$outward_number = $row['outward_number']; 
										$outward_number1 = $row['outward_number1']; 
								 ?>
								
								<div class="col-md-3">
									<label class="control-label">Outward No.</label>
									<?php //if ($sent_by_user_type=='P'){ ?>
										
									<select class="form-control col-sm-2 select2" name="outward_number" onchange="getoutwno(this.value)" >
										<option value="">Select</option>
										<option value="O" <?php echo ($row['outward_number']=='O')?'selected="selected"':'';?>>Other</option>
											<?php $sql = " SELECT * FROM `dms_inward` where outward_no > 0 
													and send_to_user = '$sent_by_user_vendor' order by outward_doc_no ";
											$q2 = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['outward_doc_no'];?>" 
												<?php echo ($row['outward_number'] == $r2['outward_doc_no'])?'selected="selected"':'';?> >  
												<?php echo $r2['outward_doc_no'] ;?></option>
												<?php } ?>
									</select>
					
								</div>
								
								<span id="getoutwno">
							<?php if($outward_number=='O'){ ?>	
									<div class="col-md-3">
											<label class="control-label">Outward No.</label>
											<input type="text" class="form-control" id="outward_number1" name="outward_number1" 
												value="<?php echo $outward_number1 ?>">
									</div>
							<?php } ?>			
								</span>
								
							</div>
							
				<!--</div>-->
					
<!-------------------------------------------------------------------------------------------------------------------------------------------------->
                <!--<div class="tab-pane" id="tab_2123">-->
                            <!-- Attachments -->
							
							 <?php
							 //$role = $_SESSION['role'];
							 
								if($user=='Admin' || $role=='Filing' || $common_doc == 'Y' ){
									$sql = " SELECT * FROM my_documents_files WHERE module = 'IN' AND reference_id = '$inward_no' ";
								}
								else {
									$sql = "SELECT * FROM my_documents_files WHERE module = 'IN' AND reference_id = '$inward_no' and ( current_user_id = '$userid' or forwarded_to = '$userid' || '$userid' = '$on_behalf' ) ";
								}
							
							//echo $sql;    
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type </th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' and type = 'DMS' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
								<?php	
									if($user =='Admin'){
											$delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
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
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where type = 'DMS' ORDER BY document ASC";
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
								$_SESSION['status']     = $status;
								
							//echo $inward_no. "<>>>>><<<";
							?>

							<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $inward_no; ?>
								
							<?php		
								if ($status != 'Submited' && $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."my_document.php?sub=delete&did=$did";?>" class="btn btn-danger" >Delete </a>
									<span>&nbsp;&nbsp;</span>
							<?php } 
							
							//echo $user." <<>> ". $saved ." <> " . $status ;?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
								
								<?php
									if($role=='Filing'){
								?>		
										<input class="btn btn-primary" type="submit" value="My Document" name="save">&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
									}
								?>
								
									<?php if ( $saved=='Y' && $status !='Accepted' && $status !='Forwarded'  ){ ?>
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Accept </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
											
									<?php }
										else if ($saved!='Y' || $user=='Admin'){
											$abc='';
									 } ?>	
										<!--<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
									<?php 
					if($back=='s'){ ?>	
								<a href="<?php echo $baseurl.$modulePath.'document_search.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>
					<?php } 
					else { ?>
								<a href="<?php echo $baseurl.$modulePath.'my_document.php?sub=list';?>" class="btn btn-default" >Back</a><span>&nbsp;&nbsp;</span>							
					<?php }  ?>					
								</div>
								
							</div>
							
							<span class="pull-left" id="prshare">  </span>
							
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
											  
											  <th>Document Description</th>
											  
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
												
												if($reviewed_by!=0){
													$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
											//echo $s2. "<BR>";
													$r3 = mysqli_query($con, $s2);
													$rw1 = mysqli_fetch_array($r3);
													$reviewed_by = $rw1['username'];
											//echo $reviewed_by . " <<<<<BR>";
												}
												else {
													$reviewed_by = 	$r1['sent_to'];
												}
												
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
$sql="SELECT * FROM sma_document_type where type = 'DMS' ORDER BY document ASC";
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



<?php } ?>
	   
	 
<!--Forward Workflow Popup-->

<div class="modal fade" id="forwardAuthority" role="dialog" aria-labelledby="forwardAuthority">
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
										<input type="hidden" id="modeF" name="mode" value='Forward'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Forward To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control col-sm-10 " name="send_to" id="send_toF"  >
													<option value="0">Select</option>
													<?php
														$sql = "SELECT * FROM sma_user where active='1' ORDER BY username ASC";
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
								<button type="button" class="btn btn-primary" id="submitForward">Forward</button>
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
												<select class="form-control" name="send_to_group" id="send_to_Group" >
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


<!--Outward Workflow Popup End -->	
	   
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]" required required="true" ><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
		var send_to_group	=  $("#send_to_Group").val();
		var ext_email		=  '(' + $("#ext_emaiL").val() +')';

		var doc_scr			= 'MYD';
		
		$('#shareAuthority').modal('hide');
		
//		$('#getcheck').html("Hello World....");	
//		$('#prshare').html("Hello World....");

		//alert(sub + ' ' + send_to + ' ' + send_to_group + ' ' + ext_email);

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
		});
		
		$("#ext_emaiL").val() = '';
		
		alert('Shared your document...');
		
	});


$("#submitOutward").on("click", function(e){
        var sub 			= 'sub14';
		//var mode		 	=  $("#modeS").val();
		var inward_no		=  $("#ap_idO").val();
		var status 			=  $("#statusO").val();
		var remarks			=  $("#remarksO").val();
		var ie_flag			=  $("#ie_flag").val();
		var send_to			=  $("#send_toS_a").val();
		var doc_scr			= 'MYD';
		var ie_flag = $('input#ie_flag').prop('checked');
	//	alert(ie_flag);
		if(ie_flag==true){
			ie_flag='I';
		}
		else {
			ie_flag='E';
		}	

//alert( send_to + ' ' + ie_flag );

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
		
		window.location.href='my_document.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}

	function getchecked(id){
		
		//var forward_check= $('input.forward_check').prop('checked');
//	alert(id);
	
		var forward_check = $('input#forward_check').prop('checked');
//		alert(forward_check);
		if(forward_check==true){
			forward_check='Y';
		}
		else {
			forward_check='N';
		}	
		
			
//		alert(forward_check+' <<<>>>' );
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
		/* var checkBox = document.getElementById("stop_remind");
		if (checkBox.checked == true){ */
		if(id=='O'){
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub12:sub},function(result){
				  $('#getoutwno').html(result);
			});
	 	}

/*		else{	
			$('#getoutwno').html('');
		} */
	}
	
	function getsendothers(id){
        var sub    = 'sub9';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getsendothers').html(result);
		});

	}


	function selectall(id){
		
	    //var i = ParseInt(i) + 1;
		//alert('Hello Selec all....' );
		var sub = 'sub4';			
		var strURL = "search_func.php";
		$.post(strURL,{ sub4:sub},function(result){
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
	
	
	
	function getremarks(id){
		
		var sub    = 'sub7';			
		var strURL = "search_func.php";
		
		//alert('Hello Selec all....getname ==> ' + id );
		
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getremarks').html(result);
		});
	}


	function hidesender(id){
		if(id=='S'){
			$('#hidesender').hide();
		}
		else {
			$('#hidesender').show();
		}
	}
	
</script>

</body>
</html>
