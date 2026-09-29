<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "setting/useraccs_test.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	$role=$_SESSION['role'];
	
	$s   = "select * from useraccess where user_role in (select id from sma_role where role = '$role') ";
	include "rwep.php";
	
	if($_GET['sub'] == 'list' && $readY == 'Y' ){
?>	
	<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User Access Control
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User Access Control</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User Access Control</h3>
                <span class="pull-right"><a href="useraccss_test.php.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create User Access Control </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th>User Id</th>
        <th>Read</th>
		<th>Write</th>
		<th>Edit</th>
		<th>Print</th>
		<th>Menu</th>
		<th style="text-align:right">Action</th>
	</tr>
</thead>
<tbody>
<?php
	$sql   = "select distinct(user_role) from useraccess  a ";
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$user_role = $row['user_role'];
		$sql = "select * from sma_role where id = '$user_role' " ;
		$q2  = mysqli_query($con,"$sql");
		$r2 = mysqli_fetch_array($q2);
		$user_role = $r2['role'];
		
?>

    <tr>
		
		<td width="20%"><?php echo $user_role;?></td>
		<td width="10%"><?php echo $row['readonly'];?></td>
		<td width="10%"><?php echo $row['writeonly'];?></td>
		<td width="10%"><?php echo $row['editonly'];?></td>
		<td width="10%"><?php echo $row['printonly'];?></td>
		<td width="10%"><?php echo $row['menuonly'];?></td>
		<?php //if ($editY == 'Y'){ ?>
		<td width="10%" style="text-align:right">
			<a href="useraccs_test.php?sub=edit&id=<?php echo $row['user_role'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
		</td>
		<?php //} 
		//else { ?>
		<!--<td width="10%" ></td>-->
		<?php //}?>
    </tr>
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
        echo '<script>window.location.href="useraccss_test.php.php?sub=list";</script>';
	} 
?>

<?php	
	if($_GET['sub'] == 'edit' ){

		if(isset($_POST['Save'])){
			$user_role			= $_POST['user_role'];  
			$readonly		= $_POST['readonly1'];
			$writeonly 		= $_POST['writeonly1'];
			$editonly 		= $_POST['editonly1'];
			$printonly 		= $_POST['printonly1'];
			$menuonly 		= $_POST['menuonly1'];

			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='1' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly2'];
			$writeonly 		= $_POST['writeonly2'];
			$editonly 		= $_POST['editonly2'];
			$printonly 		= $_POST['printonly2'];
			$menuonly 		= $_POST['menuonly2'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='2' ";
			
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly3'];
			$writeonly 		= $_POST['writeonly3'];
			$editonly 		= $_POST['editonly3'];
			$printonly 		= $_POST['printonly3'];
			$menuonly 		= $_POST['menuonly3'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='3' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly4'];
			$writeonly 		= $_POST['writeonly4'];
			$editonly 		= $_POST['editonly4'];
			$printonly 		= $_POST['printonly4'];
			$menuonly 		= $_POST['menuonly4'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='4' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly5'];
			$writeonly 		= $_POST['writeonly5'];
			$editonly 		= $_POST['editonly5'];
			$printonly 		= $_POST['printonly5'];
			$menuonly 		= $_POST['menuonly5'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='5' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly6'];
			$writeonly 		= $_POST['writeonly6'];
			$editonly 		= $_POST['editonly6'];
			$printonly 		= $_POST['printonly6'];
			$menuonly 		= $_POST['menuonly6'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='6' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly7'];
			$writeonly 		= $_POST['writeonly7'];
			$editonly 		= $_POST['editonly7'];
			$printonly 		= $_POST['printonly7'];
			$menuonly 		= $_POST['menuonly7'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='7' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly8'];
			$writeonly 		= $_POST['writeonly8'];
			$editonly 		= $_POST['editonly8'];
			$printonly 		= $_POST['printonly8'];
			$menuonly 		= $_POST['menuonly8'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='8' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly9'];
			$writeonly 		= $_POST['writeonly9'];
			$editonly 		= $_POST['editonly9'];
			$printonly 		= $_POST['printonly9'];
			$menuonly 		= $_POST['menuonly9'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='9' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly10'];
			$writeonly 		= $_POST['writeonly10'];
			$editonly 		= $_POST['editonly10'];
			$printonly 		= $_POST['printonly10'];
			$menuonly 		= $_POST['menuonly10'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='10' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly11'];
			$writeonly 		= $_POST['writeonly11'];
			$editonly 		= $_POST['editonly11'];
			$printonly 		= $_POST['printonly11'];
			$menuonly 		= $_POST['menuonly11'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='11' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly12'];
			$writeonly 		= $_POST['writeonly12'];
			$editonly 		= $_POST['editonly12'];
			$printonly 		= $_POST['printonly12'];
			$menuonly 		= $_POST['menuonly12'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='12' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly13'];
			$writeonly 		= $_POST['writeonly13'];
			$editonly 		= $_POST['editonly13'];
			$printonly 		= $_POST['printonly13'];
			$menuonly 		= $_POST['menuonly13'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='13' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly14'];
			$writeonly 		= $_POST['writeonly14'];
			$editonly 		= $_POST['editonly14'];
			$printonly 		= $_POST['printonly14'];
			$menuonly 		= $_POST['menuonly14'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='14' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly15'];
			$writeonly 		= $_POST['writeonly15'];
			$editonly 		= $_POST['editonly15'];
			$printonly 		= $_POST['printonly15'];
			$menuonly 		= $_POST['menuonly15'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='15' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly16'];
			$writeonly 		= $_POST['writeonly16'];
			$editonly 		= $_POST['editonly16'];
			$printonly 		= $_POST['printonly16'];
			$menuonly 		= $_POST['menuonly16'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='16' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly17'];
			$writeonly 		= $_POST['writeonly17'];
			$editonly 		= $_POST['editonly17'];
			$printonly 		= $_POST['printonly17'];
			$menuonly 		= $_POST['menuonly17'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='17' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly18'];
			$writeonly 		= $_POST['writeonly18'];
			$editonly 		= $_POST['editonly18'];
			$printonly 		= $_POST['printonly18'];
			$menuonly 		= $_POST['menuonly18'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='18' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly19'];
			$writeonly 		= $_POST['writeonly19'];
			$editonly 		= $_POST['editonly19'];
			$printonly 		= $_POST['printonly19'];
			$menuonly 		= $_POST['menuonly19'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='19' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly20'];
			$writeonly 		= $_POST['writeonly20'];
			$editonly 		= $_POST['editonly20'];
			$printonly 		= $_POST['printonly20'];
			$menuonly 		= $_POST['menuonly20'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='20' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly21'];
			$writeonly 		= $_POST['writeonly21'];
			$editonly 		= $_POST['editonly21'];
			$printonly 		= $_POST['printonly21'];
			$menuonly 		= $_POST['menuonly21'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='21' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly22'];
			$writeonly 		= $_POST['writeonly22'];
			$editonly 		= $_POST['editonly22'];
			$printonly 		= $_POST['printonly22'];
			$menuonly 		= $_POST['menuonly22'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='22' ";
			
/*			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly23'];
			$writeonly 		= $_POST['writeonly23'];
			$editonly 		= $_POST['editonly23'];
			$printonly 		= $_POST['printonly23'];
			$menuonly 		= $_POST['menuonly23'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='23' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly24'];
			$writeonly 		= $_POST['writeonly24'];
			$editonly 		= $_POST['editonly24'];
			$printonly 		= $_POST['printonly24'];
			$menuonly 		= $_POST['menuonly24'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='24' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly25'];
			$writeonly 		= $_POST['writeonly25'];
			$editonly 		= $_POST['editonly25'];
			$printonly 		= $_POST['printonly25'];
			$menuonly 		= $_POST['menuonly25'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='25' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
			$readonly		= $_POST['readonly26'];
			$writeonly 		= $_POST['writeonly26'];
			$editonly 		= $_POST['editonly26'];
			$printonly 		= $_POST['printonly26'];
			$menuonly 		= $_POST['menuonly26'];
			$sql = "update useraccess set readonly='$readonly', writeonly='$writeonly', editonly='$editonly', printonly='$printonly', menuonly='$menuonly' where user_role = '$user_role' and menu_id='26' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));
*/

			echo '<script>window.location.href="useraccs_test.php?sub=list";</script>';

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
            <form class="form-horizontal" action="useraccss_test.php.php?sub=add" method="post">
                
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
											<label class="col-lg-1 control-label"><b>User Role</b></label>
											<div class="col-lg-2">
											<?php $role=$_SESSION['role']; ?>
											<input type="hidden" readonly="readonly" name="user" readonly value='<?php echo $row['user_role']; ?>' >
											<input type="text" readonly="readonly"  readonly value='<?php echo $role; ?>' >
											</div>
										</div>
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 1 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Purchase Requistion</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 2 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Approval Memo</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 3 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Purchase Order</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepFour">GRN SRN</a></h4>
                            </div>
                            <div id="stepFour" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 4 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">GRN SRN</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 5 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Supplier Invoice</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepSix">Payment</a></h4>
                            </div>
                            <div id="stepSix" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
								
									<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 6 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Payment</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 7 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Account Master</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepSeven">Reports</a></h4>
                            </div>
                            <div id="stepSeven" class="panel-collapse collapse ">
								<div class="panel-body">
									<fieldset>
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 8 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Report-1</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 9 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Report-2</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
										
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepEight">Budget</a></h4>
                            </div>
                            <div id="stepEight" class="panel-collapse collapse">
								<div class="panel-body">
									<fieldset>
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 10 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Budget Entry</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 11 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Name</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 12 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Head</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 13 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Materials </label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 14 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Material Category</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 15 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Vendors</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 16 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Vendor Category</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
										
										<table width='100%'>
										<tr>
											<th width='25%'>Menu Option </th>
											<th width='08%'>Read  </th>
											<th width='09%'>Write </th>
											<th width='08%'>Edit </th>
											<th width='09%'>&nbsp; Print </th>
											<th width='10%'>Menu </th>
											<th width='31%'></th>
										</tr>
										</table>
								<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 17 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>										
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Role</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly1' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly1' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly1' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly1' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly1' value='Y' <?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 18 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Users</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 19 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Department</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 20 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Document Type</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 21 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Company Type</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 22 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Company Profile</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 23 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Location</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
											</div>
										</div>
									
									<?php
									$id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id' and menu_id = 24 ";
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
									?>		
										<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Workflow Config</label>
											<div class="col-lg-1">
												<input type="checkbox" name='readonly2' value='Y'
												<?php $readonly2=$row['readonly']; if ($readonly2=="Y") echo "checked";?>>  
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly2' value='Y' <?php $writeonly=$row['writeonly']; if ($writeonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='editonly2' value='Y' <?php $editonly=$row['editonly']; if ($editonly=="Y") echo "checked";?>> 
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='printonly2' value='Y'<?php $printonly=$row['printonly']; if ($printonly=="Y") echo "checked";?>>
											</div>
											<div class="col-lg-1">
												<input type="checkbox" name='menuonly2' value='Y'<?php $menuonly=$row['menuonly']; if ($menuonly=="Y") echo "checked";?>> 
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
