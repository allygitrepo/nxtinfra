<?php

include("../header.php");
$modulePath = "setting/useraccs.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	$role=$_SESSION['role'];
	
	if($_GET['sub'] == 'list' ){
?>	
	<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User Access Control
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User Access Control</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User Access Control</h3>
                <span class="pull-right"><a href="useraccs.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create User Access Control </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
		<th>User Id</th>
		<th style="text-align:right">Action</th>
	</tr>
</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$sql   = "select id, role from sma_role order by id ";
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
	$baseurl1 = $baseurl.$modulePath1.'useraccs.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "useraccs.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="20%"><?php echo $row['role'];?></td>
		<?php //if ($editY == 'Y'){ ?>
		<td width="10%" style="text-align:right">
			<a href="useraccs.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
		</td>
		<?php //} 
		//else { ?>
		<!--<td width="10%" ></td>-->
		<?php //}?>
    </tr>
	</a>
    <?php }?>
</tbody> 
</table>
    </div>
</div> 
<?php
 }
?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_role where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="useraccs.php?sub=list";</script>';
	} 
?>

<?php	
	if($_GET['sub'] == 'edit' ){

		if(isset($_POST['Save'])){
			$user_role			= $_POST['user_role'];  
			$readonly		= $_POST['readonly1'];
			$menuonly 		= $_POST['menuonly1'];
			$dashboard 		= $_POST['dashboard1'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='1' ";

			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly2'];
			$menuonly 		= $_POST['menuonly2'];
			$dashboard 		= $_POST['dashboard2'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='2' ";
			
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly3'];
			$menuonly 		= $_POST['menuonly3'];
			$dashboard 		= $_POST['dashboard3'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='3' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly4'];
			
			$menuonly 		= $_POST['menuonly4'];
			$dashboard 		= $_POST['dashboard4'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='4' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly5'];
			
			$menuonly 		= $_POST['menuonly5'];
			$dashboard 		= $_POST['dashboard5'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='5' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly6'];
			
			$menuonly 		= $_POST['menuonly6'];
			$dashboard 		= $_POST['dashboard6'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='6' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly7'];
			
			$menuonly 		= $_POST['menuonly7'];
			$dashboard 		= $_POST['dashboard7'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='7' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly8'];
			
			$menuonly 		= $_POST['menuonly8'];
			$dashboard 		= $_POST['dashboard8'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='8' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly9'];
			$menuonly 		= $_POST['menuonly9'];
			$dashboard 		= $_POST['dashboard9'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='9' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly30'];
			$menuonly 		= $_POST['menuonly30'];
			$dashboard 		= $_POST['dashboard30'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id='30' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly31'];
			$menuonly 		= $_POST['menuonly31'];
			$dashboard 		= $_POST['dashboard31'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id = '31' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly34'];
			$menuonly 		= $_POST['menuonly34'];
			$dashboard 		= $_POST['dashboard34'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', dashboard = '$dashboard' where user_role = '$user_role' and menu_id = '34' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly10'];
			$menuonly 		= $_POST['menuonly10'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='10' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly11'];
			
			$menuonly 		= $_POST['menuonly11'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='11' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly12'];
			
			$menuonly 		= $_POST['menuonly12'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='12' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly13'];
			
			$menuonly 		= $_POST['menuonly13'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='13' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly14'];
			
			$menuonly 		= $_POST['menuonly14'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='14' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly15'];
			
			$menuonly 		= $_POST['menuonly15'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='15' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly16'];
			
			$menuonly 		= $_POST['menuonly16'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='16' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly17'];
			
			$menuonly 		= $_POST['menuonly17'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='17' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly18'];
			
			$menuonly 		= $_POST['menuonly18'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='18' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly19'];
			
			$menuonly 		= $_POST['menuonly19'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='19' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly20'];
			
			$menuonly 		= $_POST['menuonly20'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='20' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly21'];
			
			$menuonly 		= $_POST['menuonly21'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='21' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly22'];
			
			$menuonly 		= $_POST['menuonly22'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='22' ";
			
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly23'];
			
			$menuonly 		= $_POST['menuonly23'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='23' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly24'];
			
			$menuonly 		= $_POST['menuonly24'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='24' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly25'];
			
			$menuonly 		= $_POST['menuonly25'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='25' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$readonly		= $_POST['readonly26'];
			
			$menuonly 		= $_POST['menuonly26'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='26' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$readonly		= $_POST['readonly27'];
			
			$menuonly 		= $_POST['menuonly27'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='27' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$readonly		= $_POST['readonly28'];
			
			$menuonly 		= $_POST['menuonly28'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='28' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$readonly		= $_POST['readonly29'];			
			$menuonly 		= $_POST['menuonly29'];
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='29' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$readonly		= $_POST['readonly35'];
			$menuonly 		= $_POST['menuonly35'];
			$sql   = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='35' ";
			$query = mysqli_query($con,$sql) or die(mysqli_error($con));
			
			$readonly		= $_POST['readonly36'];
			$menuonly 		= $_POST['menuonly36'];
			$sql   = "update useraccess set readonly='$readonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='36' ";
			$query = mysqli_query($con,$sql) or die(mysqli_error($con));
			
			echo '<script>window.location.href="useraccs.php?sub=list";</script>';

	}		

?>

    <section class="content-header">
        <h1>
            User Access Control
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User Access Control</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="useraccs.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="panel-group" id="steps">
                        
						<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepOne">Purchase Requisition</a></h4>
                            </div>
                            <div id="stepOne" class="panel-collapse collapse in">
								<div class="panel-body">
									<fieldset>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id'";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>	
										<div class="form-group">
											<label class="col-lg-2 control-label"><b>User Role</b></label>
											<div class="col-lg-2">
											<?php $role=$_SESSION['role']; 
											$sql="Select * from sma_role where id ='$id'";
											$q1 = mysqli_query($con,$sql);
											$r1 = mysqli_fetch_array($q1);
											
											?>
											<input type="hidden" readonly="readonly" name="user_role"  value='<?php echo $row['user_role']; ?>' >
											<input type="text" class="form-control" readonly value="<?php echo $r1['role']; ?>" >
											</div>
										</div>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 1 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Purchase Requistion</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard1' value='Y' 
													<?php $dashboard=$row['dashboard']; if($dashboard=="Y"){ echo "checked"; }?>>
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
                        			
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo">Approval Memo</a></h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 2 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Approval Memo</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard2' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepThree">Purchase Order</a></h4>
                            </div>
                            <div id="stepThree" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
									
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 3 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Purchase Order</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly3' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly3' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard3' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
										
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>

<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepFive">Supplier Invoice</a></h4>
                            </div>
                            <div id="stepFive" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 4 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Supplier Invoice</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly4' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly4' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard4' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepSix">Account</a></h4>
                            </div>
                            <div id="stepSix" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 5 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Payment</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly5' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly5' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard5' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 6 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Petty Cash</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly6' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly6' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard6' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->

						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepSeven">IPC</a></h4>
                            </div>
                            <div id="stepSeven" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 7 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">IPC</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly7' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly7' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard7' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->

						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step8">Travel Request</a></h4>
                            </div>
                            <div id="step8" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 8 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Travel Request</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly8' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly8' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard8' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->

<!-- Step 1 -->

						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step9">Travel Expenses</a></h4>
                            </div>
                            <div id="step9" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 9 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Travel Expenses</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly9' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly9' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard9' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->


<!-- Step 1 -->

						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step30">Regular Expenses</a></h4>
                            </div>
                            <div id="step30" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 10 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Regular Expenses</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly10' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly10' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard10' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->

						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step31">Operating Expenses</a></h4>
                            </div>
                            <div id="step31" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 11 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Regular Expenses</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly11' value='Y' <?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly11' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard11' value='Y' <?php $dashboard=$row['dashboard']; if ($dashboard=="Y") echo "checked";?>> 
											</div>
											
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepEight">Budget</a></h4>
                            </div>
                            <div id="stepEight" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 12 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>
										<div class="form-group">
											<label class="col-lg-3 control-label" style="text-align:left;">Budget Entry</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly12' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly12' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 13 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Name</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly13' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly13' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 14 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Head</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly14' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly14' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
												
								<?php
									$id    = $_GET['id'];
									$sql   = "Select * from useraccess where user_role ='$id' and menu_id = 15 ";
									$query = mysqli_query($con,$sql);
									$row   = mysqli_fetch_array($query);
								?>
										<div class="form-group">
											<label class="col-lg-3 control-label" style="text-align:left;">Budget Transaction</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly15' value='Y' <?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked"; ?> >
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly15' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?> >
											</div>
										</div>
								
								<?php
									$id    = $_GET['id'];
									$sql   = "Select * from useraccess where user_role ='$id' and menu_id = 16 ";
									$query = mysqli_query($con,$sql);
									$row   = mysqli_fetch_array($query);
								?>
										<div class="form-group">
											<label class="col-lg-3 control-label" style="text-align:left;">Used Budget </label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly16' value='Y' <?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked"; ?> >
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly16' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?> >
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepNine">Materials</a></h4>
                            </div>
                            <div id="stepNine" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 17 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Materials </label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly17' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly17' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 18 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Material Category</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly18' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly18' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTen">Masters</a></h4>
                            </div>
                            <div id="stepTen" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 19 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Vendors</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly19' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly19' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 20 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Vendor Category</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly20' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly20' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
										
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 17 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Vendor Type</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly17' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly17' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 18 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">State</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly18' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly18' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 19 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">City</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly19' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly19' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 20 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Stages</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly20' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly20' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
							
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepElev">Settings</a></h4>
                            </div>
                            <div id="stepElev" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:right;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard</label>
											
									</div>	
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 21 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Role</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly21' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly21' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 22 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Users</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly22' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly22' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 23 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Users Access Level</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly23' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly23' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 24 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Department</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly24' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly24' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 25 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Document Type</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly25' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly25' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 26 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Company Profile</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly26' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly26' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 27 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Location</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly27' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly27' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 28 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Workflow Config</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly28' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly28' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 29 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Account Master</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly29' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly29' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
	
									</fieldset>
									
								  </div>
								</div>
                            </div>


					<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
</section>  
<?php } 	?>



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
