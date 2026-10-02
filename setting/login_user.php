<?php

include("../header.php");
$modulePath = "setting/login_user.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User Login
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User Login</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User Login</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=user_login" class="btn btn-primary">Export</a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th># </th>
			<th>User </th>
			<th>Login Date Time</th>
			<th>LogOut Date Time</th>
			
			
		</tr>
	</thead>
<tbody>
<?php
	$ii =0;
	$modulePath1 = "setting/";
	$sql="SELECT * from user_login where 1 and userid !='' group by userid, tdate, logout_date order by id desc; ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$userid 		= $row['userid'];
		$s2="SELECT * FROM sma_user where userid = '$userid' ";
		$r3 = mysqli_query($con, $s2);
		$rowaffect = mysqli_affected_rows($con);
		$rw1 = mysqli_fetch_array($r3);
		$user_name 		= $rw1['username'];
		if($rowaffect==0){
			$user_name = $userid;
		}	
		
		$otp_date			= date('d-m-Y h:i:sa', strtotime($row['otp_date']));
		$logout_date		= date('d-m-Y h:i:sa', strtotime($row['logout_date']));		
		
		$check_year 		= date('d-m-Y', strtotime($row['logout_date']));
		if($check_year =='01-01-1970' || $check_year =='30-11-0001' ){
			$logout_date = '';
			$logout_date = date('d-m-Y h:i:sa', strtotime('+15 minutes', strtotime($row['otp_date'])) );
		}
		
	?>
	<tr >
		<td width="1%"><input type="hidden" value="<?= $ii++;?>"> </td>
		<td width="40%"><?= $user_name;?></td>
		<td width="25%"><?= $otp_date;?></td>
		<td width="25%"><?= $logout_date;?></td>
		
    </tr>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>



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
</script>

</body>
</html>
