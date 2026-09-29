<?php
include("../header.php");
$modulePath = "dms/";
$_SESSION['reset'] = '1';

$mobtab = $_SESSION['mob'];


date_default_timezone_set("Asia/Kolkata");

?>

<?php

	if($_GET['sub']=='list'){

?>
		
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Outward <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Outward List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			
					<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "outwardm.php?sub=add"?>" class="btn btn-primary">Create Outward</a> &nbsp;&nbsp;&nbsp;</span>
					
					<?php if($mobtab=='Y'){	?>
					<span class="pull-right"><a href="<?php echo $baseurl .  "dashbmob.php"?>" class="btn btn-primary">Dashboard</a> &nbsp;&nbsp;&nbsp;</span>
					<?php } ?>
				
			<?php
			
				$targetpage = "outwardm.php?sub=list"; 
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
					<th>Outward Date</th>
					<th>Sent By</th>
					<th>For Company</th>
					<th style="text-align:left;">Type of user</th>
					<th>Sent To</th>
					<th>Outward Doc. No.</th>
					<th>Mode </th>
					<th>Status</th>
					<!--<th>Decision</th>-->
					
				
				</tr>
                </thead>
                <tbody>
				<?php
					//$sql="SELECT * from dms_inward order by id desc";
					
					$role			= $_SESSION['role'];
					$user_category	= $_SESSION['user_category'];
					
					//FIND_IN_SET("q", "s,q,l");
					
					$userid   	    = $_SESSION['usrid'];
					$user   		= $_SESSION['user'];
				
					$sql = "select a.* from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN'  and reviewed_by = '$userid'  ) as DS
					ON a.id = DS.id "; //, 'Forwarded' group by  doc_id // and status  in( 'Received' ) 
					$qry = mysqli_query($con,$sql);
				
					while($rs = mysqli_fetch_array($qry)){
						$id_var .= $rs['doc_id'].',';
					}
					$id_var .= '0';
						
					//$query="SELECT count(*) as num from dms_inward where inward_no in ($id_var) "; //and company_for in ( $comid )
					
					if ($user =='Admin'){
						$sql="SELECT * from dms_inward where inward_no > 0 and outward_no > 0 ";
						$query="SELECT count(*) as num from dms_inward where inward_no > 0 and outward_no > 0 ";
					}
					else {
						$sql="SELECT * from dms_inward where inward_no > 0 and outward_no > 0 and sent_by = '$user' ";//and company_for in ( $comid )";
						$query="SELECT count(*) as num from dms_inward where inward_no > 0 and outward_no > 0 and sent_by = '$user'"; // and company_for in ('$comid') ";
					}
			//echo $sql."<BR>";		
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
					
//echo $sql;
					
					$sql .= ' order by inward_no desc ';
					$sql .= " LIMIT $start, $limit ";
					
					
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
				
						$sent_by 	= $row['sent_by'];
						$outward_no = $row['outward_doc_no'];
						
						$ie_flag 	= $row['ie_flag'];
						$sent_to 	= $row['send_to_user'];
						if($ie_flag == 'I'){
							$sql 	= "SELECT * FROM `sma_user` where id = '$sent_to' ";
							$q2  	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$send_to  = $r2['username'];
						}
						else if($ie_flag == 'E'){
							$sql 	= "SELECT * FROM `sma_party_mst` where id = '$sent_to' ";
							$q2  	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$send_to  = $r2['party_name'];
						}
//echo $sql. "<BR>";						
						
						$inward_no = $row['inward_no'];
						
						$company = $row['company_for'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						//$company  = $r2['comp_name'];
						$company  = $r2['comp_code'];

						$department = $row['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
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
						
				$ie_flag			= $row['ie_flag'];
				$courier_details	= $row['courier_details'];
				$from_user			= $row['from_user'];
				if($ie_flag=='I'){
					$ie_flag = 'Internal';
				}
				else {
					$ie_flag = 'External';
				}
						
						$j=$j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				$date_of_received = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
				$baseurl1 = $baseurl.$modulePath.'outwardm.php?sub=edit&inward_no='.$inward_no;

		?>
	<a href="<?php echo $baseurl . $modulePath . "outwardm.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
		<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="15%"><?php echo $date_of_received;?></td>
					<td width="10%"><?php echo $sent_by;?></td>
					<td width="10%"><?php echo $company;?></td>
					<td width="08%"><?php echo $ie_flag;?></td>
					
					<td width="15%"><?php echo $send_to;?></td>
					<td width="15%" style="text-align:left;"><?php echo $outward_no;?></td>
					<td width="10%"><?php echo $mode_of_receipt;?></td>
					<td width="08%"><?php echo $status;?></td>
					<!--<td width="08%"><?php echo $row['approval_status'];?></td>-->
					
					<?php //<a href="#sendAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#sendAuthority">Send</a> ?>
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
	if($_GET['sub'] == 'delete'){
        $did  = $_GET['did'];

		$sql = " delete from dms_inward where inward_no = '$did'";
		$r2 = mysqli_query($con, $sql);
		
	}
?>
<?php  

if($_GET['sub']=='add'){
		
	if( $_POST['save'] || $_POST['send'] ){
		
			$inward_no			= $_POST['inward_no'];
			$outward_no			= $_POST['outward_no'];
			$date_of_received	= date('Y-m-d h:i:s', strtotime($_POST['date_of_received']));
			$remarks			= $_POST['remarks'];
			$others				= $_POST['others'];
			$mode_of_receipt	= $_POST['mode_of_receipt'];
			$company_for		= $_POST['company_for'];
			$sent_by_company	= $_POST['sent_by_company'];
			$ie_flag			= $_POST['ie_flag'];
			$courier_details	= $_POST['courier_details'];
			$from_user			= $_POST['from_user'];
// CO/VendorName(Short Letter)/Department/Year/Number
			
			//$send_to_user_list		= explode("-", $_POST['send_to_user']);
			
			//$send_to_user		= $send_to_user_list['0'];
			//$user_type			= $send_to_user_list['1'];
			
			$send_to_user		= $_POST['send_to_user'];
//echo $send_to_user;
//exit();
			
			$sql = "select * from company where comp_id = '$company_for' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$comp_code = $r2['comp_code'];
			
			//SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst`
			if($ie_flag=='E'){
				$user_type			 = 'P';	
				$sql = "SELECT * FROM sma_party_mst where id = '$send_to_user' ";
				//echo $sql . "<BR>";
				$q2 	= mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				//$party_code = substr($r2['party_name'],0,3);
				$parr 		= explode(" ",$r2['party_name']);
				$p_code		= '';
				$p 			= 0;
				foreach($parr as $pstr){
					
					echo $p. ' ' .$pstr. "<BR>";
					
					if($p<=2){
						$p_code .= substr($pstr,0,1);
					}
					
					$p = $p + 1;
					
				}
			}
			else if($ie_flag=='I'){
				$user_type			= 'U';
				$sql = "SELECT * FROM sma_user where id = '$send_to_user' ";
				//echo $sql . "<BR>";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$p_code = substr($r2['username'],0,2);
			}
			
			
			if(!empty($others)){
				
				$sql = "insert into sma_party_mst (party_name ) Values( '$others' ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				//if(!empty($error)){echo $error; exit();}
				if(empty($error)){
					$send_to_user		 = mysqli_insert_id($con);
					$user_type			 = 'P';
					$p_code				 = substr($others,0,2);
				}
			}
			
			$outward_doc_no		= $comp_code.'_'.$p_code.'_'.date("Y").'_'.$outward_no;		
			
			//$send_to_user		= $_POST['send_to_user'];
			//list($month, $day, $year) = split('[/.-]', $date);
			
			
			$status 			= 'Draft';
			$sent_by			= $_SESSION['user'];
			$user   			= $_SESSION['user'];
			$userid   			= $_SESSION['usrid'];
			$sent_by_user_vendor= $userid;
			
			$saved 				= 'Y';
			
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
			
  			$sql="insert into dms_inward (inward_no, outward_no, date_of_received, company_for, mode_of_receipt, sent_by, send_to_user, status, remarks, draft_by, draft_dated, user_type, sent_by_user_vendor, sent_by_user_type, saved, to_others, ie_flag, courier_details, from_user, outward_doc_no ) Values('$inward_no', '$outward_no', '$date_of_received', '$company_for', '$mode_of_receipt', '$sent_by', '$send_to_user', '$status', '$remarks', '$user', now(), '$user_type', '$sent_by_user_vendor', 'U', '$saved', '$others', '$ie_flag', '$courier_details', '$from_user', '$outward_doc_no' )";
//echo $sql;
//exit();

			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = "update `dms_srno` set inward_no = '$inward_no' , outward_no = '$outward_no' where inward_no < '$inward_no' ";
			$qry = mysqli_query($con, $sql);


			if(!empty($send_to_user) && $_POST['send']){
				$approval_status = 'Sent';
				$status 		 = 'Sent';
				$saved 			 = '';
				$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$send_to_user', changed_date = now(), status = '$status', saved = '$saved' where inward_no = '$inward_no' ";
				$r2 = mysqli_query($con, $sql);
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, outward) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now(), 'O')";
				$r2 = mysqli_query($con, $sql);
				
				$sql = " insert into my_documents ( module, reference_id, current_user_id, date_uploaded ) values ( 'IN', '$inward_no', '$send_to_user', now() ) ";
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
		
//			echo "Outward Memo successful added";
			$baseurl.=$modulePath.'outwardm.php?sub=add';
			//$baseurl.=$modulePath.'edit.php?id='.$id.'&active=active';
			

//echo 	$baseurl;
//exit();		
			//echo $baseurl;
			echo "<script>window.location.href='$baseurl';</script>";
			exit();
	}
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Outward 
            <small>Add</small>
        </h1>
		
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Outward </a></li>
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
                        <h3 class="box-title">Create Outward </h3>
						<span class="pull-right"><a href="<?php echo $baseurl .  "dashbmob.php"?>" class="btn btn-primary">Dashboard</a> &nbsp;&nbsp;&nbsp;</span>
                    </div>
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="outwardm.php?sub=add" method="post">

							<div class="form-group">
								<?php
									
									$sql = "SELECT * FROM `dms_srno` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
									$outward_no = $r2['outward_no'] + 1;
									
									/*$sql = "SELECT max(inward_no) as inward_no FROM `dms_inward` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
								*/	
								?>
								
								<div class="col-sm-5">
									<label class="control-label">Document No.</label>
									<input type="text" class="form-control" id="outward_doc_no" name="outward_doc_no" readonly value="<?php echo $row['outward_doc_no'] ?>">
								</div>
							</div>
							<div class="form-group">
							
									<input type="hidden" id="inward_no" name="inward_no" value="<?php echo $inward_no ;?>">
									<input type="hidden" name="outward_no"  id="outward_no"  value="<?php echo $outward_no;?>" >
								
                                <div class="col-sm-5">
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
								
								<div class="col-md-5" style="padding-top: 25px;">	
									<b>Internal</b>
									<input type="radio" class="minimal" id="ie_flag" name="ie_flag" value="I" onchange="getparty(this.value)" > 
									<b>External</b>
									<input type="radio" class="minimal" id="ie_flag" name="ie_flag" value="E" onchange="getparty(this.value)" > 
								</div>
								
							</div>
							
							<div class="form-group">	
								<div class="col-sm-6">
									<label for="company_for" class="control-label">Company </label>
                                	<select class="form-control select2" name="company_for" id="companY"   required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company  order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
							</div>
							<div class="form-group">
								
								<div class="col-sm-6">	
									<label class="control-label">Send on behalf of</label>
									<select class="form-control  doc_type select2" name="from_user" >
										<option value="">Select</option>
										<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active != '0' 
															ORDER BY uvname ASC ";
										$q2 = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['uvname'].' - ' . $r2['type'] ;?></option>
										<?php } ?>
									</select>
								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Mode </label>
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
								<label class="control-label">Send to User / Vendor</label>
								<span id="getparty">
									<select class="form-control doc_user_vendor select2" name="send_to_user" required onchange="getothers(this.value)" >
										<option value="">Select</option>
										<option value="O"> Others </option>
										
									</select>
								</span>
								</div>
							</div>
							
						
							<div class="form-group">
	
								<div class="col-sm-5">
									<span id="getothers">
										<!--<label for="prDate" class="control-label">To Others</label>
										<input type="text" class="form-control" id="others"  name="others" value="">-->
									</span>
                                </div>	
							
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Courier Status </label>
									<select class="form-control" name="courier_status" id="courier_status"  >
									<option value=""> Select </option>
									<option value="B"> Booked </option>
									<option value="D"> Delivered </option>
									<option value="R"> Return </option>
									<option value="C"> Cancel </option>
									</select>	
                                </div>
							</div>	
	
							<div class="form-group">
								<div class="col-md-6">	
									<label class="control-label">Courier Details</label>
									<input type="text" class="form-control" id="courier_details" name="courier_details" value="">
								</div>
							</div>
							
							<div class="form-group">
							
								<div class="col-md-6">
									<label class="control-label">Remarks</label>
									<input type="text" class="form-control" id="remarks" name="remarks" placeholder="" value="<?php echo $row['remarks'];?>" >
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-md-12" style="text-align:left;" >
									
									<a href="<?php echo $baseurl.$modulePath.'outwardm.php?sub=list';?>" class="btn btn-info" >Back</a>
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

<?php } ?>

<?php	
if($_GET['sub']=='edit'){
	
	if($_POST['Save'] || $_POST['Send']){
			
			$userid   	    	= $_SESSION['usrid'];
			
			$inward_no			= $_POST['inward_no'];
			$outward_no			= $_POST['outward_no'];
			//$outward_doc_no		= $_POST['outward_doc_no'];
			$remarks			= $_POST['remarks'];
			$others				= $_POST['others'];
			
			$ie_flag			= $_POST['ie_flag'];
			$courier_details	= $_POST['courier_details'];
			$from_user			= $_POST['from_user'];
			
			
			$mode_of_receipt	= $_POST['mode_of_receipt'];
			$company_for		= $_POST['company_for'];
			
			$send_to_user_list	= explode("-", $_POST['send_to_user']);
			
			$send_to_user		= $send_to_user_list['0'];
			$user_type			= $send_to_user_list['1'];
			//$sent_by			= $userid;
			//$date_of_received	= date('Y-m-d', strtotime($_POST['date_of_received']));
			//$department_for	= $_POST['department_for'];
			//$sent_by_user_vendor= $_POST['sent_by_user_vendor'];
			
			//$saved 				= 'Y';
			
			if(!empty($others)){
				
				$sql = "insert into sma_party_mst (party_name ) Values( '$others' ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				//if(!empty($error)){echo $error; exit();}
				if(empty($error)){
					$send_to_user 	= mysqli_insert_id($con);
					$user_type		 = 'P';
					$p_code				 = substr($others,0,2);
				}
			}
			
  			$sql = "update dms_inward set company_for	= '$company_for',
										remarks			= '$remarks',
										mode_of_receipt	= '$mode_of_receipt',
										to_others 		= '$others',
										send_to_user	= '$send_to_user',
										user_type		= '$user_type',
										ie_flag			= '$ie_flag',
										courier_details	= '$courier_details',
										from_user		= '$from_user'
					where inward_no = '$inward_no' ";
										
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $send_to_user;

			if( !empty($send_to_user) && $_POST['Send'] ){
				
				$approval_status = 'Sent';
				$status 		 = 'Sent';
				$saved 			 = '';
				$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$send_to_user', changed_date = now(), status = '$status', saved = '$saved' where inward_no = '$inward_no' ";
				$r2 = mysqli_query($con, $sql);
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
				$r2 = mysqli_query($con, $sql);
				
			}
			
		//	echo "Outward successful added";
			//$baseurl.=$modulePath.'outwardm.php?sub=list';
			$baseurl.=$modulePath.'outwardm.php?sub=edit&inward_no='.$inward_no;
			
//echo $baseurl;
//exit();	
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no 		= $_GET['inward_no'];
	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	
	//echo $sql;
	
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status 		= $row['status'];
	$inward_no		= $row['inward_no'];
	$outward_no		= $row['outward_no'];
	
	$send_to_user	= $row['send_to_user'];
	//$saved 			= $row['saved'];
	
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
	
	if ( ($status == 'Submited' && $user!='Admin' ) || $status == 'Completed' ){
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
            Outward 
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Outward</a></li>
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
						<form id="form1" class="form-horizontal" action="outwardm.php?sub=edit" method="post" enctype="multipart/form-data">

		                <span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b></h4> </span><br><br>

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
					
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								<input type="hidden" name="outward_no"  id="outward_no"  value="<?php echo $outward_no;?>" >
									
								<div class="form-group">
								
									<div class="col-sm-5">
										<label class="control-label">Document No.</label>
										<input type="text" class="form-control" id="outward_doc_no" name="outward_doc_no" readonly value="<?php echo $row['outward_doc_no'] ?>">
									</div>
								</div>	
								
								<?php
									$date_of_received = date('d-m-Y', strtotime($row['date_of_received']));
									$date_of_received_time = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
									if($date_of_received=='01-01-1970'){
										$date_of_received_time='';
									}
								?>
								
							
							<div class="form-group">	
							
                                <div class="col-sm-5">
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
							
							<?php $ie_flag = $row['ie_flag']; ?>
							
							<div class="form-group">		
								<div class="col-md-5" style="padding-top: 25px;">	
									<b>Internal</b>
									<input type="radio" class="minimal" <?php echo ($row['ie_flag'] == 'I')?'checked="CHECKED"':'';?> id="ie_flag" name="ie_flag" value="I"> 
									<b>External</b>
									<input type="radio" class="minimal" <?php echo ($row['ie_flag'] == 'E')?'checked="CHECKED"':'';?> id="ie_flag" name="ie_flag" value="E"> 
								</div>
								
							</div>
							
							<div class="form-group">	
							
								<div class="col-sm-6">
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
							</div>	
							
							<div class="form-group">	
								<div class="col-sm-6">	
									<label for="company_for" class="control-label">Send on behalf of</label>
									<select class="form-control select2" name="from_user" id="from_user" >
										<option value="">Select</option>
										<?php $sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active != '0' 
															ORDER BY uvname ASC ";
										$q2 = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" <?php echo ($row['from_user'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['uvname'] ;?></option>
										<?php } ?>
									</select>
								</div>
							</div>	
							
							<div class="form-group">	
								<div class="col-sm-6">
									<label for="department_for" class="control-label">Mode </label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt"   required <?php echo $readonly; ?> >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									<option value="S" <?php echo ($row['mode_of_receipt'] == 'S')?'selected="selected"':'';?> > Self </option>
									</select>	
                                </div>
							
							</div>
							
							<div class="form-group">
								<?php
								
									if($ie_flag =='I'){
										$sql = "SELECT id as id, username as uvname, 'U' as type FROM sma_user where active != '0' ";
									}
									else if($ie_flag =='E'){
										$sql = "SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst` ORDER BY uvname ASC ";
									}
									
								?>							
								<div class="col-sm-6">
									<label for="company_for" class="control-label">Send to User / Vendor </label>
                                	<select class="form-control doc_user_vendor select2" name="send_to_user" required <?php echo $readonly; ?> onchange="getothers(this.value)" >
									<option value=""> Select </option>
									<option value="O"> Others </option>
									<?php 	
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'].'-'.$r2['type'];?>" 
									<?php echo ($row['send_to_user'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['uvname'] ;?></option>
										<?php } ?>
									</select>		
								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-xs-6">
									<span id="getothers">
								<?php $to_others = $row['to_others']; 
									if( !empty($to_others) ){
								?>	
										<label for="prDate" class="control-label">To Others</label>
										<input type="text" class="form-control" id="others"  name="others" value="<?php echo $to_others ?>">
								<?php 
									} 
								?>
									
									</span>
                                </div>	
							
							</div>
							
							<div class="form-group">
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Courier Status </label>
									<select class="form-control" name="courier_status" id="courier_status"  >
									<option value=""> Select </option>
									<option value="B" <?php echo ($row['courier_status'] == 'B')?'selected="selected"':'';?>> Booked </option>
									<option value="D" <?php echo ($row['courier_status'] == 'D')?'selected="selected"':'';?>> Delivered </option>
									<option value="R" <?php echo ($row['courier_status'] == 'R')?'selected="selected"':'';?>> Return </option>
									<option value="C" <?php echo ($row['courier_status'] == 'C')?'selected="selected"':'';?>> Cancel </option>
									</select>	
                                </div>
							</div>	
								
							<div class="form-group">
								<div class="col-md-6">	
									<label class="control-label">Courier Details</label>
									<input type="text" class="form-control" id="courier_details" name="courier_details" value="<?php echo $row['courier_details'] ?>">
								</div>
							</div>	
								
							<div class="form-group">	
								<div class="col-md-6">
									<label class="control-label">Remarks</label>
									<input type="text" class="form-control" id="to_Supplier" name="remarks" placeholder="" value="<?php echo $row['remarks'];?>" >
								</div>
							</div>
							
							<?php
							
								$_SESSION['inward_no'] 	= $inward_no;
								$_SESSION['status']  = $status;
							
							?>

							<span id="predit"></span>
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $inward_no; ?>
								
							<?php		
								if ($status == 'Draft'  ){  //  && $user=='Admin'
							?>		
									<a href="<?php echo $baseurl.$modulePath."/outwardm.php?did=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
								<?php		
								if ($status == 'Draft'){
								?>										
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="submit" name="Send" class="btn btn-info" value="Send" >&nbsp;&nbsp;
								<?php } ?>			
										<a href="<?php echo $baseurl.$modulePath.'outwardm.php?sub=list';?>" class="btn btn-primary" >Back</a><span>&nbsp;&nbsp;</span>
									
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
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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

	function getothers(id){
		
        var sub    = 'sub8';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getothers').html(result);
		});

	}
	
	function getparty(id){
		var id=id;
		//alert(id);
		var sub    = 'sub10';
		
		$('.doc_user_vendor').html('');
			
		$.ajax({
			url:'app_func.php',
			type:'post',
			data:{
				sub10:'',
				id:id
			},
			success:function(data){
				//alert(data);
				   var get_details=JSON.parse(data);
				 
					for(i=0; i<get_details.length; i++)
                 {
                   var id1=get_details[i].id1;
				   
                   var uvname=get_details[i].uvname;
				   
				   //alert(id1+" "+uvname);
   
				    $('.doc_user_vendor').append($("<option></option>").attr("value",id1).text(uvname)); 
                
               }
				
			}
			
		})

		
	}	
	

	
</script>


</body>
</html>
