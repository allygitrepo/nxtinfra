<!DOCTYPE html>
<?php

include("header.php");
$modulePath = "ipc/";

$mobtab = $_SESSION['mob'];

//if($_SESSION['alert_sts']){
//	include "popup_page.php";
	
//	unset($_SESSION['alert_sts']);
	//echo "<meta http-equiv='refresh' content='0' >";
	
//}

?>

	<style type="text/css">
.abc{
    height:65px;
    background:#FFA500;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:10px;
	font-size:16px;
}
.abc:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted #FFA500;
}

.abc1{
	float:left;
    height:65px;
    background:#0EF5E7;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc1:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted #0EF5E7;
}


.abc2{
	float:left;
    height:65px;
    background:yellow;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc2:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted yellow;
}


.abc3{
	float:left;
    height:65px;
    background:#7EF50E;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc3:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted #7EF50E;
}

.abc4{
	float:left;
    height:65px;
    background:skyblue;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc4:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted skyblue;
}


.abc5{
	float:left;
    height:65px;
    background:pink;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc5:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted pink;
}


.abc6{
	float:left;
    height:65px;
    background:#EE82EE;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc6:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted #EE82EE;
}

.abc7{
	float:left;
    height:65px;
    background:#c0d9a1;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc7:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted #c0d9a1;
}

.abc8{
	float:left;
    height:65px;
    background:yellow;
    color:#000000;
    position:relative;
    width:75px;
    text-align:center;
    line-height:30px;
	margin-left:40px;
	font-size:16px;
}


.abc8:after {
    content: "";
    position: absolute;
    height: 0px;
    width: 0px;
    left: 96%;
    top: -5px;
    border: 37px dotted transparent;
    border-left: 37px dotted yellow;
}

</style>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

    <section class="content-header">
        <h1>Dashboard</h1>
    </section>
<?php

	$comid  = $_SESSION['comid'];
	$role	= $_SESSION['role'];
	$user_category	= $_SESSION['user_category'];
	$user   	= $_SESSION['user'];
	$usrid   = $_SESSION['usrid'];

?>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body123">
            <!-- form start -->
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
					<!--<p style="font-size:20px;font-weight:600;"> This dashboard under testing, Please contact IT if any descripency. </p>-->
			<?php
			
//				include_once("dash_ot.php");
//Shared
				//$sql = "SELECT count(*) as cnt FROM `workflow_history` where doc_Type = 'IN' and status = 'Shared' and reviewed_by ='$usrid' and id in ( SELECT max(id) FROM `workflow_history` where doc_Type = 'IN' and status = 'Shared' group by reviewed_by) ";
				$sql = "select count(*) as cnt from shared_documents where module = 'IN' and shared_user_id = '$usrid' and status = 'Shared' "; 
				$res = mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1 = mysqli_fetch_array($res);
				$scnt = $r1['cnt'];
				
//Forwarded
				//$sql = "SELECT count(*) as cnt FROM `workflow_history` where doc_Type = 'IN' and status = 'Forwarded' and reviewed_by ='$usrid' and id in ( SELECT max(id) FROM `workflow_history` where doc_Type = 'IN' and status = 'Forwarded' group by reviewed_by) ";
				$sql = "select count(*) as num from forwarded_documents where module = 'IN' and forwarded_user_id = '$usrid' and status = 'Forwarded' ";
				$res 	= mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1 	= mysqli_fetch_array($res);
				$fcnt 	= $r1['cnt'];
				
//Inward	
				/* $sql = "select count(*) as cnt from workflow_history a INNER JOIN 
					(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and (reviewed_by = '$usrid' || create_by = '$usrid') group by  doc_id ) as DS
					ON a.id = DS.id and ( status  in( 'Received','Sent' ) or inward_status = 'Forwarded' ) and doc_type = 'IN' ";  */
				$sql = "select count(*) as cnt from workflow_history a INNER JOIN 
						(SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and (reviewed_by = '$usrid' || create_by = '$usrid') group by  doc_id ) as DS
						ON a.id = DS.id and ( status  in( 'Received','Sent', 'Draft' ) or inward_status = 'Forwarded' ) and doc_type= 'IN' ";	
			//echo $sql;		
				$res = mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1 = mysqli_fetch_array($res);
				$icnt = $r1['cnt'];
					
//My Document
				//$sql = "SELECT count(*) as cnt FROM `workflow_history` where doc_Type = 'IN' and status = 'Accepted' and create_by ='$usrid' and id in ( SELECT max(id) FROM `workflow_history` where doc_Type = 'IN' and status = 'Accepted' group by create_by) ";
				$sql = "select count(*) as cnt from workflow_history a , my_documents b, dms_inward c, 
				( SELECT max(id) as id FROM `workflow_history` where doc_type= 'IN' and status in( 'Accepted' ) and ( reviewed_by = '$usrid' ) group by  doc_id ) as DS
					where a.id = DS.id and a.doc_type= 'IN' and a.status in( 'Accepted' ) and reviewed_by = '$usrid' and a.doc_id = b.reference_id 
					and a.reviewed_by = b.current_user_id and a.doc_id = c.inward_no and department_for = '$department' and company_for in ($comid) ";
				$res = mysqli_query($con, $sql);
				echo mysqli_error($con);		
				$r1 = mysqli_fetch_array($res);
				$acnt = $r1['cnt'];
	
				//$sql = "SELECT count(*) as cnt FROM `dms_inward` where remind_me = 'Y' and stop_remind != 'Y' ";
				$sql = "select distinct(inward_no) from workflow_history a , my_documents b, dms_inward c
					where a.doc_type= 'IN' and a.status in( 'Accepted' ) and a.doc_id = b.reference_id 
					and a.doc_id = c.inward_no and remind_me = 'Y' and stop_remind != 'Y'";
					
				if ($user !='Admin'){
					$sql .= " and c.company_for in ($comid) and a.reviewed_by = '$usrid' ";
				}
				
				//$sql .= "group by c.inward_no";
		//echo $sql;
		
				$res = mysqli_query($con, $sql);
				echo mysqli_error($con);
				//$r1   = mysqli_fetch_array($res);
				//$rcnt = $r1['cnt'];
				$rcnt = mysqli_affected_rows($con);
	
			?>				
		<div class="box-body">
			<fieldset>	
			<div class="form-group">
				<?php if($mobtab!='Y'){ ?>
					<div class="col-sm-4" style="float:left; margin-top: 10px; " id="myp">
						<a href="<?php echo $baseurl . 'dms/my_document.php?sub=list&remind=R'; ?>" class="btn btn-lg btn-info" >Reply Reminder<br><?php echo $rcnt ?>&nbsp;</a>
					</div>
				<?php } ?>
				
				<div class="col-sm-4" style="float:left; margin-top: 10px; " id="myp">
				<?php if($mobtab=='Y'){ ?>
					<a href="<?php echo $baseurl . 'dms/outwardm.php?sub=list'; ?>" class="btn btn-lg btn-success" >&nbsp;&nbsp;&nbsp;&nbsp;Outward&nbsp;&nbsp;&nbsp;&nbsp; <br> &nbsp; </a>
				<?php } 
				else  { ?>		
					<a href="<?php echo $baseurl . 'dms/outward.php?sub=list'; ?>" class="btn btn-lg btn-success" >&nbsp;&nbsp;&nbsp;&nbsp;Outward&nbsp;&nbsp;&nbsp;&nbsp; <br> &nbsp;</a>
				<?php } ?>	
				</div>
				
				<div class="col-sm-4" style="float:left; margin-top: 10px; ">
					<?php if($mobtab=='Y'){ ?>
					<a href="<?php echo $baseurl . 'dms/inbox_scrm.php?sub=list&r=r'; ?>" class="btn btn-lg btn-info" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inward &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> <?php echo $icnt ?></a>
				<?php } 
				else  { ?>		
					<a href="<?php echo $baseurl . 'dms/inbox_scr.php?sub=list&r=r'; ?>" class="btn btn-lg btn-info"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inward &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><?php echo $icnt ?></a>
				<?php } ?>	
					 
				</div>
				
			</div>
			
			<?php if($mobtab!='Y'){ ?>
				<div class="form-group">
					<div class="col-sm-4" style="float:left; margin-top: 10px; " id="myp">
						<a href="<?php echo $baseurl . 'dms/my_document.php?sub=list'; ?>" class="btn btn-lg btn-danger" >My Document <br> <?php echo $acnt ?></a>
					</div>
					
					<div class="col-sm-4" style="float:left; margin-top: 10px; " id="myp">
						<a href="<?php echo $baseurl . 'dms/shared_document.php?sub=list'; ?>" class="btn btn-lg btn-primary" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Shared &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> <?php echo $scnt ?></a>
					</div>
					
					<div class="col-sm-4" style="float:left; margin-top: 10px; " id="myp">
						<a href="<?php echo $baseurl . 'dms/forwarded_document.php?sub=list'; ?>" class="btn btn-lg btn-success" >&nbsp; Forwarded &nbsp;<br> <?php echo $fcnt ?></a>
					</div>
				</div>
			<?php } ?>	
				
				<div class="progress">
					<div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
					  <span class="sr-only">40% Complete (success)</span>
					</div>
				</div>
			  			
						
				</fieldset>
            </div>
        </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
</section>
 
<?php 	
		include("footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
	
	$(".progress").hide();
		  
</script>


</body>
</html>


