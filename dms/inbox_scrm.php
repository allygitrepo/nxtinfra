<?php

$mobtab 	= $_SESSION['mob'];
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';

$mobtab 	= $_SESSION['mob'];

?>

<?php date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30) echo date('d-m-Y H:i:s'); ?>

<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<?php

	if($_GET['sub']=='list'){
		
		$targetpage = "inbox_scrm.php?sub=list"; 
				$limit = 10; 
				$start = 0;	

?>
		
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inward <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Inward List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
						
				<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "inbox_scrm.php?sub=add"?>" class="btn btn-primary">Create Inward</a> &nbsp;&nbsp;&nbsp;</span>
				
			<?php if($mobtab=='Y'){	 ?>
				<span class="pull-right"><a href="<?php echo $baseurl .  "dashbmob.php"?>" class="btn btn-primary">Dashboard</a> &nbsp;&nbsp;&nbsp;</span>
			<?php } ?>		
				
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
					<th>Sent To</th>
					<th>Document No.</th>
					<th>Mode </th>
					<th>Status</th>
					<th>Location</th>
					<!--<th style="text-align:right;">Action</th>-->
				
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
					
					$sql = "select a.* from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN'  and reviewed_by = '$userid' group by  doc_id ) as DS
					ON a.id = DS.id and status  in( 'Received' ) "; //, 'Forwarded'
					$qry = mysqli_query($con,$sql);
			//echo $sql."<BR>";		
					while($rs = mysqli_fetch_array($qry)){
						$id_var .= $rs['doc_id'].',';
					}
					$id_var .= '0';
						
					//$query="SELECT count(*) as num from dms_inward where inward_no in ($id_var) "; //and company_for in ( $comid )
					
					$sql = "select a.* from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and (reviewed_by = '$userid' || create_by = '$userid') group by  doc_id ) as DS
					ON a.id = DS.id and status  in( 'Received','Sent', 'Draft' ) and doc_type= 'IN' "; //, 'Forwarded' 'Accepted', and a.outward !='O'
					$qry = mysqli_query($con,$sql);
					
					$query = "select count(*) as num from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and (reviewed_by = '$userid' || create_by = '$userid') group by  doc_id ) as DS
					ON a.id = DS.id and status  in( 'Received','Sent', 'Draft' )  and doc_type= 'IN'  "; //, 'Forwarded'/ 'Accepted', and a.outward !='O'
				
					$rowaff = mysqli_affected_rows($con);
						
					if ($user =='Admin'){
						$sql = " select a.* from workflow_history a INNER JOIN 
							(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN'  group by  doc_id ) as DS
							ON a.id = DS.id and status  in( 'Received','Sent' ,'Forwarded','Accepted', 'Draft')  and doc_type= 'IN'";
						
						$query = "select count(*) as num from workflow_history a INNER JOIN 
							(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN'  group by  doc_id ) as DS
							ON a.id = DS.id and status  in( 'Received','Sent','Forwarded','Accepted', 'Draft' )  and doc_type= 'IN' ";
					}
					
					$_SESSION['sqlex'] = $sql;
					
			//echo $search_data;		
//echo $query;
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
					
					
					$sql .= ' order by doc_id desc ';
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
						
						$status  = $row['status'];
				
						$sent_by = $row['create_by'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_by' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$sent_by  = $r2['username'];
						
						$sent_to = $row['reviewed_by'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$send_to  = $r2['username'];
						
						
						$inward_no = $row['doc_id'];
						
						$sql="SELECT * from dms_inward where inward_no ='$inward_no' ";
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						$company = $r1['company_for'];
						if( $status =='Sent' ){
							$status = $r1['status'];
						}
						
						$date_of_received = date('d-m-Y h:i:s', strtotime($r1['date_of_received']));
						$outward_no = $r1['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

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
				$approval_status = $row['approval_status'];
				if( $outward_no>0 && $status =='Sent' ){
					$styl	= "style='background-color: #D6E0F8;'";
					$styl2	= "background-color: #D6E0F8;";
					$status	= 'Not Received';
					$approval_status = 'Branch Sent';
				}
				
				if( $status =='Accepted' ){
					$status = 'My Document';
				}
				else if( $status =='Sent' ){
					$status = 'Received';
				}
				
				$j = $j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				
				$baseurl1 = $baseurl.$modulePath.'inbox_scrm.php?sub=edit&inward_no='.$inward_no;

		?>
	<a href="<?php echo $baseurl . $modulePath . "inbox_scrm.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $date_of_received;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $sent_by;?></td>
					<td width="29%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $department;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $send_to;?></td>
					<td width="4%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $mode_of_receipt;?></td>
					<td width="08%"<?php echo $styl; ?>><?php echo $status;?></td>
					<td width="08%"<?php echo $styl; ?>><?php echo $approval_status;?></td>
					<!--<td><a href="#sendAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#sendAuthority">Send</a></td>-->
					
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
	if($_GET['sub']=='add'){

//echo $_GET['sub']. ' ' . $_POST['save'];

		if($_POST['save'] || $_POST['send']){
			
			$inward_no			= $_POST['inward_no'];
			$date_of_received	= date('Y-m-d h:i:s', strtotime($_POST['date_of_received']));
			$account_year		= $_POST['account_year'];
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			//$doc_type			= $_POST['doc_type'];
			$mode_of_receipt	= $_POST['mode_of_receipt'];
			
			$send_to_user		= $_POST['send_to_user'];
			$status 			= 'Draft';

			$user   			= $_SESSION['user'];
			$userid   			= $_SESSION['usrid'];
			
  			$sql="insert into dms_inward (inward_no, date_of_received, company_for, department_for, mode_of_receipt, sent_by_user_vendor,  sent_by, send_to_user, status, remarks, draft_by, draft_dated, filing_yn ) Values('$inward_no', '$date_of_received', '$company_for', '$department_for', '$mode_of_receipt', '$userid', '$sent_by', '$send_to_user', '$status', '$remarks', '$user', now(), 'Y' )";
//echo $sql;
//exit();
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			
			$sql = "update `dms_srno` set inward_no = '$inward_no' where inward_no < '$inward_no' ";
			$qry = mysqli_query($con, $sql);
							
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
			$r2 = mysqli_query($con, $sql);
				
			if(!empty($error)){echo $error; exit();}
			
			if(!empty($send_to_user) && $_POST['send']){
				
				$approval_status = 'Sent';
				$status 		 = 'Sent';
				//$status 		 = 'Received';
				
				$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$send_to_user', changed_date = now(), status = '$status' 
						where inward_no = '$inward_no' ";
				$r2  = mysqli_query($con, $sql);
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
				$r2  = mysqli_query($con, $sql);
				
				$sql = " insert into my_documents ( module, reference_id, current_user_id, date_uploaded ) values ( 'IN', '$inward_no', '$userid', now() ) ";
				$r2  = mysqli_query($con, $sql);
				
				//Send By
				$sql="select * from sma_user where id='$send_to_user' ";
	//echo $sql."<BR>";			
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_category 	= $r->user_category;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}

				$modulePath = "dms/"; 
				
				$status  = 'Sent';
				$msg 	 = 'Inward Number : '.$inward_no . ' ' . 'Dated : ' . date("d-m-Y");
				$subject = "HC DMS - Document Send by ". $user;
	//	exit("RAVINDRA STOPED...");

				$baseurl1 =$baseurl.$modulePath.'inbox_scr.php?sub=edit&inward_no='.$inward_no;

				include "dms_mail.php";
				
			}

//			echo "Inward Memo successful added";
			$baseurl.=$modulePath.'inbox_scrm.php?sub=add';
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
            Inward 
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Inward </a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
			<!-- right column -->
            <div class="col-sm-12 col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Inward </h3>
					<?php if($mobtab=='Y'){	 ?>
						<span class="pull-right"><a href="<?php echo $baseurl .  "dashbmob.php"?>" class="btn btn-primary">Dashboard</a> &nbsp;&nbsp;&nbsp;</span>
					<?php } ?>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="inbox_scrm.php?sub=add" method="post">

							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								<?php
									
									$sql = "SELECT inward_no FROM `dms_srno` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
									
								/*$sql = "SELECT max(inward_no) as inward_no FROM `dms_inward` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
								*/	
								?>
								<div class="col-sm-6">
									<label for="prDate" class="control-label">Document No.</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;font-size:24px; font-family: Arial, Helvetica, sans-serif;" value="<?php echo $inward_no;?>">
                                </div>
							</div>	
							<div class="form-group">	
                                <div class="col-sm-6">
									<label for="prDate" class="control-label">Date</label>
                                    <!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>-->
                                        <input type="text" class="form-control" id="prDate" style="text-align:left;font-size:22px; font-family: Arial, Helvetica, sans-serif;" name="date_of_received" readonly placeholder="dd/mm/yyyy"
                                               value="<?php echo date('d-m-Y H:i:s');?>">
                                   <!-- </div>-->
								</div>	
							</div>	
							<div class="form-group">	
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Mode <span class="f_req">*</span></label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt" required >
									<option value=""> Select </option>
									<option value="C"> Courier </option>
									<option value="H"> Hand Delivery </option>
									<option value="E"> Email </option>
									<option value="S"> Self </option>
									</select>	
                                </div>
							</div>	
							<div class="form-group">	
								<div class="col-sm-6">	
								<label class="control-label">Company </label>
									<select class="form-control doc_type select2 " name="company_for"  >
										<option value="">Select</option>
											<?php
												$sql = "select * from company order by comp_name ";
												$q2  = mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){
													$company  = $r2['comp_name'];
												?>
										<option value="<?php echo $r2['comp_id']?>" ><?php echo $r2['comp_name'] ?></option>
											<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="form-group">	
								<div class="col-sm-6">	
								<label class="control-label">For User </label>
									<select class="form-control doc_type select2 " name="send_to_user"   >
										<option value="">Select</option>
													<?php
													$sql="SELECT * FROM sma_user where active != '0' ORDER BY username ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($send_to_user == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
													<?php } ?>
									</select>
								</div>
							</div>
	
							<div class="form-group">
								<div class="col-sm-6">
									<label class="control-label">Inward Description</label>
									<input type="text" class="form-control" id="remarks" name="remarks" placeholder="" value="<?php echo $row['subject'];?>" >
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-12" style="text-align:left;" >
										<a href="<?php echo $baseurl.$modulePath."inbox_scrm.php?sub=list&reset=1";?>" class="btn btn-primary" >Back</a>
									<div style="text-align:right;">
										<input type="submit"  id="save" name="save" class="btn btn-success" value="Save" >
										<span>&nbsp;&nbsp;</span>
										<input type="submit"  id="send" name="send" class="btn btn-info" value="Send" >
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
			//$doc_type			= $_POST['doc_type'];
			
			$forward_to			= $_POST['forward_to'];
			$saved 				= 'Y';
			$status				= 'Received';
			$outward_no			= $_POST['outward_no'];
			$approval_status	= $_POST['approval_status'];
			$outward_number		= $_POST['outward_number'];
			
			
/*			$storage_type		= $_POST['storage_type'];
			$storage_rack		= $_POST['storage_rack'];
			$shelf_no			= $_POST['shelf_no'];
			$file_no			= $_POST['file_no'];	
			$doc_ref_no			= $_POST['doc_ref_no'];
			$reminder_date		= date('Y-m-d', strtotime($_POST['reminder_date']));
			
			$remind_me			= $_POST['remind_me'];
			$stop_remind		= $_POST['stop_remind'];
*/
		
			if($outward_no>0){
				$approval_status = 'Branch Sent';
			}
			
  			$sql = "update dms_inward set company_for	= '$company_for',
										department_for	= '$department_for',
										remarks			= '$remarks',
										sent_by			= '$sent_by',
										sent_by_user_vendor= '$sent_by_user_vendor',
										sent_by_user_type= '$sent_by_user_type',
										status			= '$status',
										doc_ref_no		= '$doc_ref_no',
										approval_status	= '$approval_status',
										saved 			= '$saved'
					where inward_no='$inward_no' ";
			
//echo $sql;	send_to_user	= '$send_to_user',
										
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			
			$userid   	    = $_SESSION['usrid'];
			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
			$r2 = mysqli_query($con, $sql);
				
			if(!empty($send_to_user)){
				$sql = "update dms_inward set send_to_user	= '$send_to_user' where inward_no='$inward_no' ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
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
					
					$findex = $folder_path.'/inbox.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					
					//$sql = " delete from my_documents_files where reference_id = '$inward_no' ";
					//mysqli_query($con, $sql);
					
					$now = date("Y-m-d");
					$sql = "INSERT INTO my_documents_files ( module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded, current_user_id, rack_no, shelf_no, forwarded_to ) VALUES('IN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . " '$inward_no', '$now', '$userid', '$storage_rack', '$shelf_no', '$send_to_user' )";
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
			//$baseurl.=$modulePath.'inbox_scrm.php?sub=list';
			$baseurl.=$modulePath.'inbox_scrm.php?sub=edit&inward_no='.$inward_no;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no 		= $_GET['inward_no'];
	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	
//	echo $sql;
	
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status 		= $row['status'];
	$inward_no		= $row['inward_no'];
	$send_to_user	= $row['send_to_user'];
	$sent_by_user_vendor	= $row['sent_by_user_vendor'];
	$saved 			= $row['saved'];
	
	$userid   	    = $_SESSION['usrid'];

	$sql = "SELECT max(doc_id) as doc_id , status FROM `workflow_history` where doc_type= 'IN' and status ='Received' and reviewed_by = '$userid' and doc_id = '$inward_no' ";
//	echo $sql;
	
	$qry = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($qry);
	$doc_id = $rs['doc_id'];
	if($doc_id>0){
		$status = $rs['status'];
	}
	
	$readonly = '';
	$select2	= 'select2';
	
	if ( $status == 'Accepted' ){  //|| $status == 'Received' 
		$readonly = 'READONLY';
		$disable	= 'DISABLED';
		
	}
	 
	if( $sent_by_user_vendor == $userid && $status == 'Sent' ){
		
		$readonly = 'READONLY';
		$disable	= 'DISABLED';
		
	}

	
	if ( $user=='Admin' ){
		$readonly = '';
		$disable	= '';
	}
	
	
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Inward 
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Inward</a></li>
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
						<form id="form1" class="form-horizontal" action="inbox_scrm.php?sub=edit" method="post" enctype="multipart/form-data">
							
					<?php  //echo $sent_by_user_vendor . ' == '. $userid ; ?>
					
							<?php if($status=='Accepted'){ ?>
								<span class="pull-right"><h4 style="color:red;"><b><?php echo 'My Document'?></b></h4> </span>
							<?php }
							else { ?>
								<span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?> </b></h4> </span>
							<?php } ?>

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
                        <li class="<?php echo $active_1;?>" ><a href="#tab_1" data-toggle="tab" id="first_tab" >Inward</a></li>
                       <!-- <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="third_tab">Documents</a></li>-->
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<!--<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							
							<input type="hidden" name="outward_no" id = "oD" value="<?php echo $row['outward_no'];?>" >
							<input type="hidden" name="approval_status" id = "approval_status" value="<?php echo $row['approval_status'];?>" >
							
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								
								<div class="col-sm-6">
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
                            </div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<label for="prDate" class="control-label">Date</label>
                                    <!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>-->
                                        <input type="text" class="form-control" id="prDate" name="date_of_received" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_of_received_time;?>" readonly <?php echo $readonly; ?> >
                                    <!--</div>-->
									
								</div>	
							</div>	
							
							<div class="form-group">	
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Mode <span class="f_req">*</span></label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt"   required <?php echo $readonly; ?> >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self</option>
									</select>	
                                </div>
							</div>	
							
							<div class="form-group">
								<?php //where comp_id in ($comid) ?>
                                <div class="col-sm-6">
									<label for="company_for" class="control-label">Company </label>
                                	<select class="form-control col-sm-2" name="company_for" id="companY"  <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company  order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							</div>	
							
							<!--<div class="form-group">	
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Department <span class="f_req">*</span></label>
									<select class="form-control" name="department_for" id="department_for"   required <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php //$sql = "select * from sma_department order by name ";
										//$q2 	= mysqli_query($con, $sql);
										//while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department_for'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php//} ?>
									</select>	
                                </div>-->
								
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">		
									<label class=" control-label">For User </label>
									<select class="form-control  select2 " <?php echo $disable; ?> name="sent_by_user_vendor" onchange="getsendothers(this.value)" <?php echo $readonly; ?> >
										<option value="0">Select</option>
										<option value="9999" <?php echo ($row['sent_by_user_vendor'] == '9999')?'selected="selected"':'';?> >Others</option>
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
										
									</select>
								</div>
							</div>	
							
							<div class="form-group">
								<div class="col-sm-6">
									<span id="getsendothers">
								<?php $sent_by = $row['sent_by']; 
									if( !empty($sent_by) ){
								?>	
										<label class="control-label">Provide Sender Name (if not available in dropdown)</label>
										<input type="text" class="form-control" id="sent_by" name="sent_by" autocomplete="off" <?php echo $readonly; ?> 
										value="<?php echo $sent_by;?>" >
								<?php 
									}
								?>
								
									</span>
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<label class="control-label ">Inward Description</label>
								
									<input type="text" class="form-control" id="remarks"  autocomplete="off" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
								</div>
							</div>	
							
							<!--<div class="form-group">
								<div class="col-sm-6">
								<label class="control-label ">Doc.Ref.No.</label>
								
									<input type="text" class="form-control" id="doc_ref_no"  autocomplete="off" name="doc_ref_no" <?php echo $readonly; ?> value="<?php echo $row['doc_ref_no'];?>" >
								</div>
								
							</div>-->
							
							
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
										
										<span class="pull-left" id="prshare">  </span>
									
								</div>
								
								<div class="col-sm-6 text-right">
								
									<?php //echo $saved. ' ' . $status . ' ' . $send_to_user .' == ' .  $userid ;
										if($status=='Draft'){ ?>
									
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
										
									<?php } ?>
									
									
									<?php if ($saved=='Y' && $status =='Received'){ ?>
											<label class="control-label">&nbsp;</label>
									
									
									<?php 		
									if( $send_to_user == $userid ){ ?>									
											<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
											
											<a href="#sendAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#sendAuthority">Forward </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php if($status!='Accepted'){ ?>
											<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">My Document </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php }
								
									}
								?> 
										
											<!--<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
									<?php } 
										else if (( $saved!='Y' ) || $user=='Admin'){
									
										if( $send_to_user == $userid ){ 
									?>	
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
									<?php 	}  
										}
									?>	
										<!--<button type="submit" class="btn btn-primary" form="form1" >Save </button><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
										<a href="<?php echo $baseurl.$modulePath.'inbox_scrm.php?sub=list';?>" class="btn btn-primary" >Back</a><span>&nbsp;&nbsp;</span>							
										
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
$sql="SELECT * FROM sma_document_type where type = 'D' ORDER BY document ASC";
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

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Accept </h4>
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
								<button type="button" class="btn btn-primary" id="submitApprove">Accept</button>
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


<!--Send Workflow Popup-->

<div class="modal fade" id="sendAuthority" role="dialog" aria-labelledby="sendAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="sendAuthority">Forward To</h4>
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
										
										<input type="hidden" name="inward_no" id="ap_idS" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Send'>
										
										<input type="hidden" id="statusS" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Send To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control"  name="send_to" id="send_toS" <?php echo $readonly; ?> >
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
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitSend">Forward</button>
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
        var sub 			= 'sub5';
		var mode		 	=  $("#modeS").val();
		var send_to			=  $("#send_toS").val();
        
		var inward_no		=  $("#ap_idS").val();		
		var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

		var doc_scr			= 'INW';
//alert(sub + ' ' + mode + ' ' + send_to + ' ' + status );	

		$('#sendAuthority').modal('hide');
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
		
		window.location.href='inbox_scrm.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
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

	function getsendothers(id){
		
        var sub    = 'sub9';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getsendothers').html(result);
		});

	}

</script>



</body>
</html>
