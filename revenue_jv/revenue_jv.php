<?php
include("../header.php");

include("../dbcon_mob.php");
$modulePath = "revenue_jv/revenue_jv.php?sub=list";

	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

$user   = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

?>

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Revenue JV Sync
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Revenue JV Sync</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
            
				
					<div class="box-header">
				
				<?php
					
				if ($_POST['comp_id'] or $_POST['tollplaza_name'] or $_POST['start_date'] or $_POST['end_date'] ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['tollplaza_name'] = $_POST['tollplaza_name'];
					
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];					
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['tollplaza_name']  or $_SESSION['end_date'] or $_SESSION['start_date'] ){
					$comp_id = $_SESSION['comp_id'];
					$tollplaza_name = $_SESSION['tollplaza_name'];
					
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['tollplaza_name'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					
					$comp_id = $_SESSION['comp_id'];
					$tollplaza_name = $_SESSION['tollplaza_name'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					
				}
				
			?>
					
					<form class="form-horizontal" action="revenue_jv.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-4">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company where 1 and comp_id in ($comid) order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_code'];?>" <?php echo ($comp_id == $r2['comp_code'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								<div class="col-md-2">
									<label class="control-label">Tollplaza</label>
									<select class="form-control select2" name="tollplaza_name" id="tollplaza_name" >
										<option value=""> Select </option>
											<?php $sql = "SELECT distinct(tollplaza_name) as tollplaza_name FROM `p2p_revenue_hdr` order by tollplaza_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['tollplaza_name'];?>" <?php echo ($tollplaza_name == $r2['tollplaza_name'])?'selected="selected"':'';?>  ><?php echo $r2['tollplaza_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
							
								<div class="col-md-2">
									<label class=" control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
								<div class="col-md-2">
									<label class=" control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
							<div class="col-xs-2">
                                		
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="revenue_jv.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>Reset</a>
							</div>
						
						</div>
						
				</form>	
<?php
if ( $accountant_role=='Y' ){
?>	
				<span class="pull-right"><a href="<?php echo $baseurl . "revenue_jv/revenue_jv.php?sub=add"; ?>" class="btn btn-primary">Create </a> &nbsp;&nbsp;&nbsp;&nbsp; </span> 
<?php } ?>				
				<span id="predit"></span>
				
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>Srno.</th>
			<th>Date</th>
			<th>Project Name</th>
			<th>Tollplaza Name</th>
			<th style="text-align:right;" >Revenue </th>
			<th style="text-align:right;" >Adjustment </th>
			<th>Status </th>
			<th style="text-align:left;">Tally Status</th>
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "revenue_jv/";
	
	$comp_code = '';
	$sql="SELECT * FROM `company` where 1 and comp_id in ($comid)";
//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		$comp_code .= "'".$row['comp_code']."',";
	}
	$comp_code .= "'..'";
	
	$sql="SELECT * FROM `p2p_revenue_hdr` where 1 and project_code in ($comp_code)";
	if(!empty($comp_id)){
		$sql .= " and project_code = '$comp_id' ";
	}
	if(!empty($tollplaza_name)){
		$sql .= " and tollplaza_name = '$tollplaza_name' ";
	}
	
	if(!empty($start_date)){
		$start_date = date('Y-m-d', strtotime($start_date));
									
		$sql .= " and dated >= '$start_date' ";
	}
	if(!empty($end_date)){
		$end_date = date('Y-m-d', strtotime($end_date));
		$sql .= " and dated <= '$end_date' ";
	}
	
	$sql .= " order by dated desc ";
	
//	$sql .= " limit 0,10";
	
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$revenue_hdr_id		= $row['id'];
		$dated 				= $row['dated'];
		$project			= $row['project'];
		$tollplaza			= $row['tollplaza'];
		$project_name		= $row['project_name'];
		$tollplaza_name		= $row['tollplaza_name'];
		$status				= $row['status'];
		$tally_status 		= $row['tally_status'];
		
		if(empty($tally_status)){
			$tally_status_a = 'JV Pending';
		}
		else if($tally_status=='R'){
			$tally_status_a = 'JV Created';
		}	
		else if($tally_status=='C'){
			$tally_status_a = 'JV Checked ';
		}
		else if($tally_status=='U'){
			$tally_status_a = 'JV Synched';	
		}
		
		
		$sql = "SELECT sum(revenue) as revenue, sum(adjustment) as adjustment FROM `p2p_revenue_data` where 1 and revenue_hdr_id = '$revenue_hdr_id' "; //dated = '$dated' and project= '$project' and tollplaza = '$tollplaza' ";
		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($res);
		$revenue 				= $r2['revenue'];
		$adjustment				= $r2['adjustment'];
		
		/* if($revenue + $adjustment ==0){
			continue;
		}	
		 */
		$revenue_v 			= moneyFormatIndiaa($revenue);
		$adjustment_v 		= moneyFormatIndiaa($adjustment);
		 
		$baseurl1 = $baseurl.$modulePath1.'revenue_jv.php?sub=edit&revenue_hdr_id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "revenue_jv.php?sub=edit&revenue_hdr_id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" ></td>
		<td width="6%"><?= $revenue_hdr_id;?></td>
		<td width="10%"><?= date('d-m-Y', strtotime($dated));?></td>
		<td width="30%"><?= $project_name;?></td>
		<td width="10%"><?= $tollplaza_name;?></td>
		<td width="10%" style="text-align:right;" ><?= $revenue_v;?></td>
		<td width="10%" style="text-align:right;"><?= $adjustment_v;?></td>
		<td width="10%"><?= $status;?></td>
		<td width="10%" style="text-align:left;"><?= $tally_status_a; ?></td>
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

<?php } ?>


<?php 

	if($_GET['sub'] == 'add'){
		
		if($_POST['Save']=='Save'){

			$project_name			= $_POST['project_name'];
			$tollplaza_name			= $_POST['tollplaza_name'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			
			$sql="SELECT * FROM `company` where 1 and comp_name = '$project_name' ";
//echo $sql."<BR>";	
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
			$project_code = $r2['comp_code'];
			$project 	  = $r2['comp_id'];
			
			$sql="SELECT * FROM `tollplaza` where 1 and tollplaza = '$tollplaza_name' ";
//echo $sql."<BR>";	
			$res = mysqli_query($conmob, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
			$tollplaza_id = $r2['id'];
			
			$sql="INSERT INTO p2p_revenue_hdr ( project, project_name, project_code, tollplaza, tollplaza_name, dated , draft_date, status, manual_entry) 
					VALUES ( '$project', '$project_name', '$project_code', '$tollplaza_id', '$tollplaza_name', '$dated', now(), 'Draft', 'Y' ) ";
			$query=mysqli_query($con, $sql);
			$srno = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit('INSERT ERROR sma_purchase_order !!!');}
//echo $sql."<BR>";

			$sql = " SELECT DISTINCT(revenue_group_name) as revenue_group_name, revenue_group FROM `p2p_revenue_data` ORDER BY revenue_group_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ 
				$revenue_group_name = $r2['revenue_group_name'];
				$revenue_group 		= $r2['revenue_group'];
				
				$sql = "INSERT INTO p2p_revenue_data ( revenue_hdr_id, project, project_name, project_code, tollplaza, tollplaza_name, dated, revenue_group_name, revenue_group ) 
						VALUES ( '$srno', '$project', '$project_name', '$project_code', '$tollplaza_id', '$tollplaza_name', now(), '$revenue_group_name', '$revenue_group' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql."<BR>";
				
			}
			
			$sql = "INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status) values( 'RV', '$srno', '$userid', now(), 'Draft' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//echo $sql."<BR>";
			
			$pgname 		= "revenue_jv.php";
			include "../viewonly.php";
			$description 	= $tollplaza_name. ','. $dated;
		    $affect 		= 'Added';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$project_name','$description','$affect')";
		    mysqli_query($con, $sql);
			echo mysqli_error($con);
			
//echo $sql."<BR>";	
//exit('EXIT HERE....');				
			$baseurl .= "revenue_jv/revenue_jv.php?sub=edit&revenue_hdr_id=".$srno;
			echo "<script>window.location.href='$baseurl';</script>";
			exit();
			
					
		}	
		
	
?>
	
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Revenue JV
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Order</a></li>
            <li class="active">Revenue JV</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="revenue_jv.php?sub=add" method="post">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
									
								<div class="form-group">
									<div class="col-md-3">
											<label class="control-label"> Date</label>
												<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
													<div class="input-group-addon">
														<i class="fa fa-calendar-alt"></i>
													</div>
													<input type="text" class="form-control" id="dated" name="dated" placeholder="dd-mm-yyyy"  value="<?php echo date("d-m-Y");?>">
												</div>
									</div>
										
										<div class="col-sm-4">

											<label for="project" class="control-label">Company<span style="color:red;"> **</span></label>
											<select class="form-control select2123" name="project_name" id="project_name" required 
														onchange="gettollplaza(this.value);" >
												<option value=""> Select </option>
													<?php $sql = "SELECT distinct(project_name) as project_name FROM `p2p_revenue_hdr` order by project_name ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['project_name'];?>"><?php echo $r2['project_name'];?></option>
													<?php } ?>
											</select>
										</div>
										
										<div class="col-md-2">
											<label class="control-label">Tollplaza <span style="color:red;"> **</span></label>
										<span id="gettollplaza">		
											<select class="form-control" name="tollplaza_name" id="tollplaza_name" required >
												<option value=""> Select </option>
													<?php $sql = "SELECT distinct(tollplaza_name) as tollplaza_name FROM `p2p_revenue_hdr` order by tollplaza_name ";
													$q2 	  = mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['tollplaza_name'];?>" > <?php echo $r2['tollplaza_name'];?></option>
													<?php } ?>
											</select>
										</span>	
										</div>
										
								</div>
										
										
								
								<div class="box-footer">
									
									<div class="col-sm-6 text-right">
										<span>&nbsp;&nbsp;</span>
										
									</div>
									<div class="col-sm-6 text-right">
										
										<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
										
										<span>&nbsp;&nbsp;</span>
									<span id="hideSave">	
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
									</span>	
									</div>
								</div>	
								
								</fieldset>
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

<?php if($_GET['sub'] == 'edit'){
	
	if(isset($_POST['Save'])){
			
			$revenue_hdr_id				= $_POST['revenue_hdr_id']; 
			
			$approver_1			  		= $_POST['approver_1'];
			$approver_2					= $_POST['approver_2'];
			$approver_3					= $_POST['approver_3'];
			$approver_4					= $_POST['approver_4'];
			$approver_5					= $_POST['approver_5'];
			$approver_6					= $_POST['approver_6'];
			$approver_7					= $_POST['approver_7'];
			$approver_8					= $_POST['approver_8'];
			
			$status						= $_POST['status'];
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update p2p_revenue_hdr set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$revenue_hdr_id'";	
				$query=mysqli_query($con, $sql);	
//echo $sql. "<BR>";	
				
				$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					VALUES( 'RV', '$revenue_hdr_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";					
				$modulePath = "revenue_jv/";
				
				$sql="SELECT * FROM sma_user where id='$approver_1' and active='1' ";				
//echo $sql. "<BR>";
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'revenue_jv.php?id='.$revenue_hdr_id;
		
				$baseurl1 = $baseurl.$modulePath.'revenue_jv.php?id='.$revenue_hdr_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'revenue_jv.php?id='.$revenue_hdr_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'revenue_jv.php?id='.$revenue_hdr_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Revenue JV Number : '.$revenue_hdr_id . ' ' . 'Dated : ' . date("d-m-Y");

				include "revenue_mail.php";		
					
			}
//echo $sql. "<BR>";
//exit();
						
			$page					= $_POST['page']; 		
			$baseurl.=$modulePath.'revenue_jv.php?sub=list&same_page='.$page;
			
			echo "<script>window.location.href='$baseurl';</script>";			
			exit();
			
	}
	
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Revenue JV Sync
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Revenue JV Sync</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <h3 class="box-title">Revenue JV Sync List</h3>
			  
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
						<a href="revenue_jv.php?sub=list" class="btn btn-primary">Back</a>&nbsp;&nbsp;
					</span>
				</div>
				
				<span id="predit"></span>
				
			</div>
		</div>	
    <div class="box">
	
			<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Revenue JV</a></li>
				  
				  <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				 
			</ul>
				
		<div class="tab-content">
				
			<div class="tab-pane active" id="tab_1">
					
<?php
	$revenue_hdr_id = $_GET['revenue_hdr_id'];
	
	$sql="SELECT * FROM `p2p_revenue_hdr` where 1 and id = '$revenue_hdr_id' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	
		$dated 				= $row['dated'];
		$project_name		= $row['project_name'];
		$project_code		= $row['project_code'];
		$tollplaza_name		= $row['tollplaza_name'];
		$manual_entry		= $row['manual_entry'];
		
		$del	 			= $row['del'];
		$status 			= $row['status'];
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		
		$tally_narration	= $row['tally_narration'];
		$tally_status		= $row['tally_status'];
		
		$readonly = '';
		if($status=='Completed' || $status =='Submitted'){	
			$readonly = 'READONLY';
		}
		
		if($del=='Y'){
			$status = 'Deleted';
		}	
?>

		<input type="hidden" id ="projecT" value="<?= $project_code; ?>">
		
		<div class="pull-left123">		
<?php		echo "<p style='font-size:18px;'>"."<b>Date : </b>".date('d-m-Y', strtotime($dated)) . '&nbsp; <b> Project : </b>' .	$project_name . '&nbsp; <b>Tollplaza : </b>' . $tollplaza_name . 
		"<span style='margin-left:150px;'> Status :</span> <b style='font-size:18px;color:red;'> ".$status. '</b>'.'</p>'; ?>
		</div>
			
			
		
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr><td>#</td>
			<th>Revenue Type </th>
			<th style="text-align:right;">Revenue</th>
			<th style="text-align:right;">Adjust</th>
			<th style="text-align:right;">Total</th>			
			<th style="text-align:left;">Remarks</th>	
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "revenue_jv/";
	
	$sql="SELECT * from p2p_revenue_data where 1 and revenue_hdr_id = '$revenue_hdr_id' ";
	
	$sql .= " order by id ";
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
//	echo mysqli_affected_rows($con);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$revenue_group_name 	= $row['revenue_group_name'];
		$vehicle_group_name 	= $row['vehicle_group_name'];
		
		$revenue 				= $row['revenue'];
		$adjustment				= $row['adjustment'];
		$remarks				= $row['remarks'];
		
		$total					= $revenue + $adjustment;
		
		if($total==0 && $manual_entry !='Y'){
			continue;
		}
		
		$grand_revenue 				= $grand_revenue + $revenue;
		$grand_adjustment			= $grand_adjustment + $adjustment;
		$grand_total				= $grand_total + $total;
		
		$rid					= $row['id'];

		$revenue_v 		= moneyFormatIndiaa($revenue);
		$adjustment_v 	= moneyFormatIndiaa($adjustment);
		$total_v 		= moneyFormatIndiaa($total);
		
		$baseurl1 = $baseurl.$modulePath1.'revenue_jv.php?sub=edit&id='.$row["id"];

	?>
	<tr>
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" > </td>
<?php 
	if($manual_entry=='Y'){
?>		
		<td width="20%">
		
		<select class="form-control" name="revenue_group_name" id="revenue_group_name" style="width:90%;" onchange="getrevenue_group_name(this.value,  <?= $rid;?> );" disabled >
			<option value=""> Select </option>
			<?php $sql = " SELECT DISTINCT(revenue_group_name) as revenue_group_name FROM `p2p_revenue_data` ORDER BY revenue_group_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['revenue_group_name'];?>" <?php echo ($revenue_group_name == $r2['revenue_group_name'])?'selected="selected"':'';?> ><?php echo $r2['revenue_group_name'];?></option>
			<?php } ?>
		</select>
		</td>
		
		<td width="10%" style="color:red;text-align:right;" border='1' bgcolor="lightgrey" contenteditable<?= $readonly;?>="true" 
						onBlur="saveToDatabase_ln(this,'revenue','<?= $revenue_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $revenue_v;?>
		</td>
		
<?php 
	}
	else {
?>		
		<td width="20%"><?php echo $revenue_group_name;?></td>
		<td width="10%" style="text-align:right;" ><?php echo $revenue_v;?></td>
<?php 
	}
?>
		
		<td width="10%" style="color:red;text-align:right;" border='1' bgcolor="lightgrey" contenteditable<?= $readonly;?>="true" 
						onBlur="saveToDatabase_ln(this,'adjustment','<?= $revenue_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $adjustment_v;?>
		</td>
		
		<td width="10%" style="text-align:right;" ><?= $total_v;?></td>
		<td width="30%" style="color:red;text-align:left;" border='1' bgcolor="lightgrey" contenteditable<?= $readonly;?>="true" 
						onBlur="saveToDatabase_ln(this,'remarks','<?= $revenue_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $remarks;?>
		</td>
		
    </tr>
</tbody> 

	<?php }
	
		$grand_revenue_v 		= moneyFormatIndiaa($grand_revenue);
		$grand_adjustment_v 	= moneyFormatIndiaa($grand_adjustment);
		$grand_total_v 			= moneyFormatIndiaa($grand_total);
	?>
</tfoot> 
		<tr>
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" > </td>
		<th width="20%" style="text-align:right;">Total</th>
		<th width="10%" style="text-align:right;" ><?= $grand_revenue_v;?></th>
		<th width="10%" style="text-align:right;" ><?= $grand_adjustment_v;?></th>
		
		<th width="10%" style="text-align:right;" ><?= $grand_total_v;?></th>
		<td width="30%" ></td>
		
    </tr>
</tfoot> 
</table>
	
 
<div class="box-footer">
	<form class="form-horizontal" action="revenue_jv.php?sub=edit" method="post" enctype="multipart/form-data" >
		<div class="col-sm-12 text-right">
					<input type="hidden" id = "revenue_hdr_id" name = "revenue_hdr_id"  value="<?= $revenue_hdr_id;?>" >
					
					<input type="hidden" id = "status" name="status" value="<?= $status;?>" >
					
					
<?php
								$role			= $_SESSION['role'];
								$userid   		= $_SESSION['usrid'];
									
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag = '';
									if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' ||
										$userid == $approver_4 && $approver_4_status=='Submitted' ||
										$userid == $approver_5 && $approver_5_status=='Submitted' ||
										$userid == $approver_6 && $approver_6_status=='Submitted' ||
										$userid == $approver_7 && $approver_7_status=='Submitted' ||
										$userid == $approver_8 && $approver_8_status=='Submitted' ){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted'){
										$approver_flag='Y';
									}
									
										
									}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'){
							?>
							
								
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
									
									if( $status=='Completed' && $user == 'Admin' ){
							?>
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
									
							<?php
									}
								}
							?>
							
							
							
<?php //echo $status. "<<<>>";
	if($status=='Draft' ){
?>	
		<span class='hidesend' >
			<div class="col-sm-6 text-left">	
			
<?php
		if($manual_entry =='Y'){
?>		
			
				<a href="#makeDeleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDeleteAuthority">Delete</a>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>						
			
<?php } ?>
			</div>
			
			<div class="col-sm-6 text-right">
				<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
				<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
			</div>
		</span>	
<?php } ?>
								

<?php  
	if( $status == 'Submitted' ){
?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><BR>
									<label class="control-label"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><BR>
									<label class="control-label"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><BR>
									<label class="control-label"><?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><BR>
									<label class="control-label"><?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><BR>
									<label class="control-label"><?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_7)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_7' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_7_name = $rw['username'];
								$approver_7_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label><BR>
									<label class="control-label"><?= $approver_7_name . " <BR> " . $approver_7_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_8)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_8' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_8_name = $rw['username'];
								$approver_8_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label><BR>
									<label class="control-label"><?= $approver_8_name . " <BR> " . $approver_8_role; ?>
									</label>
								</div>
					<?php	
							}
					?>		
							</div>
<?php		
						}
?>
						
<?php
	//echo $status ."<BR>";
		if( $status == 'Draft' ){
?>
						<span id="getapprover">
								<div class="box-footer">
								
							<?php	
								
								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	
							
								if(!empty($approver_2)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
									<select class="form-control  approver_2" name="approver_2" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
									<select class="form-control  approver_3" name="approver_3" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 
								if(!empty($approver_4)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
									<select class="form-control  approver_4" name="approver_4" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								<?php } ?>	
							<?php if(!empty($approver_5)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
									<select class="form-control  approver_5" name="approver_5" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							<?php 
								if(!empty($approver_6)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
									<select class="form-control  approver_6" name="approver_6" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_7)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
									<select class="form-control  approver_7" name="approver_7" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_8)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
									<select class="form-control  approver_8" name="approver_8" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
							
								
								<BR>
								
							</div>
						
						</span>
				<?php } ?>
				
		</div>

	</form>
	
</div>	


<?php
								$disabled = '';
								if($tally_status=='R' || $tally_status=='C' || $tally_status == 'U'){
									
									$disabled = "DISABLED";
									
								}	
								if($user=='Admin'){
								//	$disabled = '';
								}	
								if ($accountant_role=='M' || $accountant_role=='Y' ){
									$disabled = "";
								}	
								
								if( $tally_status == 'C' || $tally_status == 'U' ){
									$disabled = "DISABLED";    
								}
									
					if( ($accountant_role=='Y' || $accountant_role=='M' || $status=='Completed' || $status=='Submitted' ) ){
							?>
											
							<div class="tab-pane <?php echo $active_tab5 ?> " id="tab_5">					
						
							    <div class="box">
                                    <div class="box-header">
										<p><?= $label_line; ?></p>
										
                                        <h4 class="box-title">Tally Journal Account</h4>
								<?php if (empty($disabled)){ ?>
                                        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
										 </span>	
								<?php  } ?>			
                                       
									   
                                    </div>
                                    
						<div class="box-body">
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
								
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="40%">Account Name</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;" >Amount</th>
													<th width="10%"></th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "SELECT * FROM tally_journal_entry 
													WHERE doc_no = '$revenue_hdr_id' AND doc_type = 'RV' 
														ORDER BY effect desc, record_id ";	
//echo $sql;
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date	 	 	= $r2['doc_date'];
											$invoice_no  		= $r2['supp_invoice_no'];
											$invoice_date  		= $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally  		= $r2['status'];
									
											$url_var = urlencode($_SERVER['REQUEST_URI']);
											
											$amount_v = moneyFormatIndiaa($amount);
											
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo $amount_v; ?> </td>
												<td>
									
												</td>
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = round($amount_dr + $amount,2);
											}
											else if($effect=='Cr'){
												$amount_cr = round($amount_cr + $amount,2);
											}
 
										}
										$emsg   ='';
										$stl	='';
										if($amount_dr != $amount_cr){
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
										}	
										$amount_dr = moneyFormatIndiaa($amount_dr);
										$amount_cr = moneyFormatIndiaa($amount_cr);
									?>
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo $amount_dr; ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo $amount_cr; ?> </td>
												<td></td>
											</tr>
											
											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

									<div class="form-group">
								
									<?php //echo $status. ' ' .$tally_status . ' <<<>>> ';
									$status = 'Completed';
										if ( $status == 'Completed' ){
										
									?>
										<div class="col-sm-3">
										<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ready to Update Status &nbsp;&nbsp;: </label>
										<?php 
											if( $tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label"> Jv Created</label>';
											}
											if( $tally_status == 'C'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Ticked</label>';
											}
											else if($tally_status == 'U'){
												
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Updated to Tally</label>';
											}
											
											else if($tally_status == 'N'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do Not Sync</label>';
											}
											
										
										if( (empty($tally_status) ||  $tally_status == 'R' || $tally_status == 'N') && ( $accountant_role=='M' ||  $accountant_role=='Y' )  && $status == 'Completed' ){ 
										?>	
										    <label for="tally_status" style="position: relative;top: -4px;" class="control-label">Sync to Tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($status_tally == 'C' || $status_tally == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="C" onchange="gettallyStatus(this.value);">
										<?php } ?>	
										
									
									
									<?php 
									//if( ($tally_status=='C' || $tally_status=='R' ) && $tally_access=='Y' ){ 
										?>
								<!--		    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" onchange="gettallyStatus(this.value);" >
								-->			
									<?php 
								//		}
									?>
										
									</div>
									
									<?php  } ?>
									
									<?php 
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970' || $chk_date =='30-11--0001'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y h:m i', strtotime($tally_updated_on));
										}	
									?>
										<div class="col-sm-2">
											<label for="tally_status" class="control-label"><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
										</div>
										
										<div class="col-sm-1">
											<label for="tally_narration" class="control-label">Narration: </label>
										</div>	
										<div class="col-sm-6">	
											<textarea rows="3" class="form-control"  name="tally_narration" id="tally_narration"  
											onBlur="saveToDatabase(this.value,'narration','<?php echo $revenue_hdr_id; ?>')"
											onClick="showEdit(this);" ><?php echo $tally_narration;?></textarea>
										</div>
										
									</div>

										</div>
						
									</div>
								</div>
						</div>	
			</div>				
						
								
<!-- Start -->

			<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $revenue_hdr_id;
						 			$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RV' order by id asc ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
							
						</b>	
					</span>
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RV' order by id desc ";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$make_by_flag		= $r1['make_by_flag'];
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
												
												$role = $rw1['primary_role'];

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

<!-- End -->

			</div>
								
						</div>	
	<?php } ?>	
				

<?php } ?>


<!--Make to DraftPopup-->
<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										
										<input type="hidden" name="revenue_hdr_id" id="revenue_hdr_idD" value="<?php echo $revenue_hdr_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
									<!--	<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>-->
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete Popup-->
<div class="modal fade" id="makeDeleteAuthority" role="dialog" aria-labelledby="makeDeleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDeleteAuthority">Do you want to Delete ? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										
										<input type="hidden" name="revenue_hdr_id" id="revenue_hdr_idD" value="<?php echo $revenue_hdr_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
									<!--	<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>-->
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!-- Delete Popup-->

<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTallyLabel">Do you want create tally journal?</h4>
            </div>
            <div class="modal-body123">
                <section class="content123">
                    <div class="row123">
                        <form class="form-horizontal" action="#" method="POST" enctype="multipart/form-data">
						
                            <input type="hidden" id="modeT" value='Tally'>
							<input type="hidden" id="rev_idT" value="<?php echo $revenue_hdr_id;?>">
						
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">No</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry" onclick="tallyentry123();" >Yes</button>
            </div>
			
                </section>
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
                <h4 class="modal-title" id="approvalAuthority">Send To...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
								<form class="form-horizontal">
                                        
										<input type="hidden" name="revenue_hdr_id" id="revenue_hdr_idE" value="<?php echo $revenue_hdr_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
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
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$r3   = mysqli_fetch_array($query);
											$approver = $r3['id'];

										?>
										
										<input type="hidden" name="revenue_hdr_id" id="revenue_hdr_idR" value="<?php echo $revenue_hdr_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
																				
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    
									</form>	
								
								</div>

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

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


	function saveToDatabase_ln(editableObj,column,revenue_hdr_id,line_no) {
		    
		var editableObj = editableObj.innerHTML;	
		//alert("UPDATE `enqdetail` set " + editableObj + qty);
		var sdivURL = "saveauditjobdtl.php";
			$.post(sdivURL,{column:column,editval:editableObj,revenue_hdr_id:revenue_hdr_id,line_no:line_no },function(result){
				//alert('Hello...');
				//$('#addbom_dtl123').html(result);
			});
			
	}
	
	function updateAudit_Status(id){
			
		var sub = 'sub15';
		var audit_status	 		=  $("#audit_status"+id).val();
	//	alert(id + ' ' +audit_status);
		
		audit_status = '';
		if (document.getElementById('audit_status'+id).checked) {
		    audit_status = document.getElementById('audit_status'+id).value;
			if(audit_status==id){
				audit_status = 'Y';	
			}	
		}
		
	//	alert(id + ' ' +audit_status);
	//	alert('Hello !');
		var strURL = "gin_func.php";
		$.post(strURL,{ audit_status:audit_status,
						id:id,
						sub15:sub},
						function(result){
		      //$('#predit').html(result);
		});
	}	

	function getapprover(){
		
		var company_id    	= document.getElementById("projecT").value;
		var trans_type    	= '100';
		//var trans_type    	= document.getElementById("trans_type").value;
		//var checker_value   = document.getElementById("checker_value").value;
		//var department    	= document.getElementById("departMENT").value;
		//var po_type  	   	= document.getElementById("po_typea").value;
		
		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + ' ' + trans_type + ' ' + company_id );
		
		var strURL = "revenue_func.php";
		$.post(strURL,{company_id:company_id,trans_type:trans_type,sub24:sub},function(result){
		      $('#getapprover').html(result);
			
		});
		
	}

	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
	function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
//		alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function gettallyStatus(id){
	
		var sub    = 'sub6';
//alert(sub);
		var revenue_hdr_id    	= document.getElementById("revenue_hdr_id").value;
		var tally_status = '';
		if (document.getElementById('tally_status').checked) {
		    tally_status = document.getElementById('tally_status').value;
		}
		
//		alert(revenue_hdr_id+ ' ' + tally_status + ' ' + sub);
		var strURL = "revenue_func.php";
		$.post(strURL,{tally_status:tally_status,revenue_hdr_id:revenue_hdr_id,sub6:sub},function(result){
		   //   $('#tallyentry').html(result);
			
		});
		
	}
	
	$("#addTallyEntry").on("click", function(e){
		
        var sub 	 = 'sub2';
		var mode 	 = $("#modeT").val();
		var rev_id 	 = $("#rev_idT").val();		
//alert(re_id);
		var doc_type = 'RV';
		
		$('#modalAddTally').modal('hide');
		var strURL 		= "ce_func.php";
		$.post(strURL,{ mode:mode,rev_id:rev_id,doc_type:doc_type,
							sub2:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1 || row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}	
		if(row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==7 || row_affected==8){
			if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if( row_affected==8){
			if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		
		return false;
		
	}
	
	
	 $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var revenue_hdr_id		 	=  $("#revenue_hdr_idE").val();
		var approver 		=  $("#approverC").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		
//alert(revenue_hdr_id + ' ' + approver + ' ' + status + ' ' +  mode );
//return;
		 $('#approvalAuthority').modal('hide');
		var strURL = "revenue_func.php";
		$.post(strURL,{ revenue_hdr_id:revenue_hdr_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	

    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var revenue_hdr_id		=  $("#revenue_hdr_idR").val();
		var status 				=  $("#statusR").val();
		var remarks				=  $("#remarksR").val();
		
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+po_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "revenue_func.php";
		$.post(strURL,{ revenue_hdr_id:revenue_hdr_id,
						mode:mode,
						statusap:mode,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});
	
	$("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var revenue_hdr_id 	=  $("#revenue_hdr_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + revenue_hdr_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ revenue_hdr_id:revenue_hdr_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	$("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var revenue_hdr_id 	=  $("#revenue_hdr_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + revenue_hdr_id );
	
		$('#makeDeleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ revenue_hdr_id:revenue_hdr_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	function gettollplaza(id){
	
		var sub    = 'sub16';
//alert(id);
		var strURL = "revenue_func.php";
		$.post(strURL,{id:id,sub16:sub},function(result){
		      $('#gettollplaza').html(result);
		});
	
	}	
</script>

</body>
</html>
