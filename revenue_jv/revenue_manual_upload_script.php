<?php 
if($_GET['sub'] == 'list'){

	include("../header.php");
	$modulePath = "revenue_jv/revenue_manual_upload_script.php?sub=list";
	
	date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Revenue JV Upload
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Revenue</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
       		
			<form class="form-horizontal" action="revenue_manual_upload_script.php?sub=upload" method="post">
                      						
						<div class="form-group">	
								<div class="col-md-1">
									<label class="control-label">&nbsp;</label>

								</div>
								
								<label class="control-label col-md-2 ">Enter Date</label>
									
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker123" data-date-format="dd-mm-yyyy123">
										<input type="date" class="form-control" data-date-format="dd-mm-yyyy" id="revenue_date" name="revenue_date" placeholder="" value="" >
									<!--	<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									-->	
									</div>	
								</div>
								
								<label for="project" class="control-label col-sm-1">Company</label>
								<div class="col-sm-4">
								<select class="form-control select2" name="project" id="PROJECT" required >
                             		<option value=""> Select </option>
									<?php 
										$sql = " select * from company WHERE comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_code'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						
						
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">&nbsp;</label>

							</div>
								
							<div class="pull-right123 col-xs-1">
								<input class="btn btn-success" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
							
							
							<div class="pull-right123 col-xs-1">	
								<a href="revenue_manual_upload_script.php?sub=list&reset=1" name="btnCancel" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
								
							</div>
							
						</div>
						
				</form>

			</div>
			
		</div>	
    

	<!-- DataTables -->
	<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
	<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

	<script>
		
		$(document).ready(function () {
			$('.datepicker').datepicker({
				"format": 'd/M/Y',
				"autoclose": true
			});
			;
		});

	</script>

<?php

}


if($_GET['sub']=='upload'){
	
include("dbcon_mob.php");

$revenue_date = date('Y-m-d', strtotime($_POST['revenue_date']));
$project		= $_POST['project'];
$rowaffect = 0;

$sql = "SELECT * FROM `company` where comp_code = '$project' ";
$qry = mysqli_query($conp2p, $sql);
$r2  = mysqli_fetch_array($qry);
$comp_id		= $r2['comp_id'];
$comp_code		= $r2['comp_code'];
$comp_name		= $r2['comp_name'];

$dated_v = "'".$revenue_date."'";
//$dated_v = "'".date("Y-m-d")."'";
	
		$sql = " SELECT * from p2p_revenue_hdr WHERE 1 AND project_code = '$project' AND dated in ($dated_v) and tally_status != 'U' and status not in ('Submitted', 'Completed') ";
//echo $sql."<BR>";
//exit();
		$qry = mysqli_query($conp2p, $sql);	
		while($r2  = mysqli_fetch_array($qry)){
				
			$revenue_hdr_id		 		= $r2['id'];
			
			$sql = "DELETE FROM `p2p_revenue_hdr` WHERE 1 AND project_code = '$project' AND id = '$revenue_hdr_id' and tally_status != 'U' and status not in ('Submitted', 'Completed') ";
//echo $sql."<BR>";			
			mysqli_query($conp2p, $sql);
			echo mysqli_error($conp2p);
			
			$sql = "DELETE FROM workflow_history where doc_type = 'RV' and doc_id = '$revenue_hdr_id' ";
			mysqli_query($conp2p, $sql);
			
			$sql = "DELETE FROM `p2p_revenue_data` WHERE 1 AND revenue_hdr_id = '$revenue_hdr_id' ";
			mysqli_query($conp2p, $sql);
//echo $sql."<BR>";						
			echo mysqli_error($conp2p);
		
			$sql = " DELETE FROM `tally_journal_entry` where doc_type = 'RV' and doc_no = '$revenue_hdr_id' ";
			mysqli_query($conp2p, $sql);
			echo mysqli_error($conp2p);
			
		}

//exit();

$sql = "SELECT * FROM `project` where project_code = '$project' ";
$qry = mysqli_query($con, $sql);
$r2  = mysqli_fetch_array($qry);
$project_id		= $r2['id'];
			
		$sql = " SELECT project, tollplaza, dated FROM standard_data WHERE 1 AND project = '$project_id' AND dated in ($dated_v) group by project, tollplaza, dated ";
		
echo $sql."<BR>";
//exit();
		$qry = mysqli_query($con, $sql);
		$rowaffect = mysqli_affected_rows($con);		
		while($r2  = mysqli_fetch_array($qry)){
				
			$dated		 		= $r2['dated'];
			//$project		 	= $r2['project'];
			$tollplaza		 	= $r2['tollplaza'];
			
			$sql  = "select * from project where id = '$project' ";
			$q2   = mysqli_query($con, $sql);
			$rw2  = mysqli_fetch_array($q2);
			$project_name 		= $rw2['project'];
			$project_code 		= $rw2['project_code'];
					
			$sql  = "select * from tollplaza where id = '$tollplaza' ";
			$q2   = mysqli_query($con, $sql);
			$rw2  = mysqli_fetch_array($q2);
			$tollplaza_name  	= $rw2['tollplaza'];
					
			$sql = " INSERT INTO p2p_revenue_hdr ( project, project_name, project_code, tollplaza, tollplaza_name, dated, status, dtaft_date ) 
						VALUE ( '$comp_id', '$comp_name', '$comp_code', '$tollplaza', '$tollplaza_name' , '$dated', 'Draft', now() ) ";
			mysqli_query($conp2p, $sql);
			$revenue_hdr_id = mysqli_insert_id($conp2p);
			echo mysqli_error($conp2p);
echo $sql. "<BR>";

			$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
					VALUES( 'RV', '$revenue_hdr_id', '3', '$dated' , 'Draft', '', '', now())";
			$query=mysqli_query($conp2p, $sql);
			echo mysqli_error($conp2p);
echo $sql. "<BR>";

		}

//exit();

	$sql = " SELECT * FROM p2p_revenue_hdr where 1 AND project_code = '$project' AND dated in ($dated_v) and tally_status != 'U' and status not in ('Submitted', 'Completed') ";
//echo $sql."<BR>";

	$result = mysqli_query($conp2p, $sql);
//echo	$rowaffect = mysqli_affected_rows($conp2p);
//exit();		
	while($row  = mysqli_fetch_array($result)){
				
		$revenue_hdr_id		= $row['id'];
		$dated		 		= $row['dated'];
		//$project		 	= $row['project'];
		$tollplaza		 	= $row['tollplaza'];
						
	//$req_date_from_v= date('Y-m-d', strtotime($fldate));	and ( flag is NULL || flag = '' )
		$sql = " SELECT project, tollplaza, dated, revenue_group, vehicle_group, revenue_type, rev_id, vehicle_type, veh_id, sum(revenue) as revenue, sum(traffic) as traffic, flag 
		FROM standard_data 
		where 1 AND project = '$project_id' AND dated = '$dated' and tollplaza = '$tollplaza' group by revenue_group ";
		
echo $sql."<BR>";
//exit();
		$qry = mysqli_query($con, $sql);
		while($r2  = mysqli_fetch_array($qry)){
				
					//$project		 	= $r2['project'];
					$tollplaza		 	= $r2['tollplaza'];
					$dated		 		= $r2['dated'];
					$revenue_group		= $r2['revenue_group'];
					$vehicle_group	 	= $r2['vehicle_group'];
					$revenue_type		= $r2['revenue_type'];
					$rev_id		 		= $r2['rev_id'];
					$vehicle_type		= $r2['vehicle_type'];
					$veh_id		 		= $r2['veh_id'];
					$revenue		 	= $r2['revenue'];
					$traffic		 	= $r2['traffic'];
					$flag				= $r2['flag'];
					
					/* $sql = "select * from project where id = '$project'";
					$q2  = mysqli_query($con, $sql);
					$rw2  = mysqli_fetch_array($q2);
					$project_name 		= $rw2['project'];
					$project_code 		= $rw2['project_code']; */
					
					$sql = "select * from tollplaza where id = '$tollplaza'";
					$q2  = mysqli_query($con, $sql);
					$rw2  = mysqli_fetch_array($q2);
					$tollplaza_name  	= $rw2['tollplaza'];
					
					$sql = "select * from revenue_group where id = '$revenue_group'";
					$q2  = mysqli_query($con, $sql);
					$rw2  = mysqli_fetch_array($q2);
					$revenue_group_name  	= $rw2['revenue_group'];
	
					$sql = "select * from vehicle_group where id = '$vehicle_group'";
					$q2  = mysqli_query($con, $sql);
					$rw2  = mysqli_fetch_array($q2);
					$vehicle_group_name  	= $rw2['vehicle_group'];
					
					$sql = " INSERT INTO `p2p_revenue_data` (revenue_hdr_id, project, project_code, project_name, tollplaza, tollplaza_name, dated, revenue_group, vehicle_group, revenue_type, rev_id, vehicle_type, veh_id, revenue, traffic , revenue_group_name, vehicle_group_name, flag ) VALUES ( '$revenue_hdr_id','$comp_id', '$comp_code', '$comp_name', '$tollplaza', '$tollplaza_name', '$dated', '$revenue_group', '$vehicle_group', '$revenue_type', '$rev_id', '$vehicle_type', '$veh_id', '$revenue', '$traffic', '$revenue_group_name', '$vehicle_group_name', '$flag' ) ";
					mysqli_query($conp2p, $sql);
					echo mysqli_error($conp2p);
//echo $sql."<BR>";							
					
		}
		
	}
	
//P2P End

	
//exit("HELLO");//p2p_revenue_hdr 
//if($rowaffect>0){
//	echo "<script>window.close();</script>";	
//}	
//	exit();
	
	echo "Revenue JV data uploaded...";
	
	if($rowaffect>0){
		echo "<script>alert('Revenue JV data uploaded...');</script>";	
	}

	//$baseurl1 = $baseurl . "revenue_jv/revenue_manual_upload_script.php?sub=list";
	echo "<script>window.close();</script>";		
	$baseurl1 = $baseurl . "revenue_jv.php?sub=list";

	echo "<script>window.location.href='$baseurl1';</script>";
//	exit();
	
}

?>
