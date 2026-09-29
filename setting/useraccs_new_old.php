<?php
include("../header.php");
$modulePath = "setting/useraccs_new.php?sub=list";
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
        Role based Access
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Role based Access</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Role based Access</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

            <thead>
    <tr>
		<th></th>
		<th>Role Name</th>
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
	
	$baseurl1 = $baseurl.$modulePath1.'useraccs_new.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "useraccs_new.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="20%"><?php echo $row['role'];?></td>
		<?php //if ($editY == 'Y'){ ?>
		<td width="10%" style="text-align:right">
			<a href="useraccs_new.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
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
        echo '<script>window.location.href="useraccs_new.php?sub=list";</script>';
	} 
?>

<?php	
	if($_GET['sub'] == 'edit' ){

		if(isset($_POST['Save'])){
			
			$row_id			= $_POST['row_id'];  
			$user_role		= $_POST['user_role']; 
			
//print_r($row_id);
			foreach($row_id as $value){
				
				$readonly		= $_POST["readonly$value"];
				$menuonly 		= $_POST["menuonly$value"];
				$dashboard 		= $_POST["dashboard$value"];
				$writeonly 		= $_POST["writeonly$value"];
				
				$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', 
							dashboard = '$dashboard', writeonly = '$writeonly'
								WHERE user_role = '$user_role' and id = '$value' ";
				$query=mysqli_query($con,$sql) or die(mysqli_error($con));
//				echo $sql. "<BR>";
//				echo $readonly. ' '. $menuonly. ' ' . $dashboard . ' ' . $value. "<BR>";
				
			}	
//exit('#####2');

			$readonly		= $_POST['readonly1'];
			$menuonly 		= $_POST['menuonly1'];
			$dashboard 		= $_POST['dashboard1'];
			$writeonly 		= $_POST['writeonly1'];
			
			$sql = "update useraccess set readonly='$readonly', menuonly='$menuonly', 
					dashboard = '$dashboard', writeonly = '$writeonly'
						WHERE user_role = '$user_role' and id = '$row_id' ";
			$query=mysqli_query($con,$sql) or die(mysqli_error($con));

			$tally_access	= $_POST['tally_access'];
			$sql    = "update sma_role set tally_access = '$tally_access' where id = '$user_role' ";
			$query	= mysqli_query($con, $sql);
			$error	= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="useraccs_new.php?sub=list";</script>';

	}		

?>

    <section class="content-header">
        <h1>
            Role based Access
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Role based Access</a></li>
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
            <form class="form-horizontal" action="useraccs_new.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="panel-group" id="steps">
                        
						<?php
									$id = $_GET['id'];
									$role_id = $_GET['id'];
									$sql="Select * from useraccess where user_role ='$id'";
						//echo $sql;
								
									$query = mysqli_query($con,$sql);
									$row = mysqli_fetch_array($query);
								?>	
										<div class="form-group">
											<label class="col-lg-2 control-label"><b>User Role</b></label>
											<div class="col-lg-4">
											<?php $role=$_SESSION['role']; 
											$sql="Select * from sma_role where id ='$id'";
											$q1 = mysqli_query($con,$sql);
											$r1 = mysqli_fetch_array($q1);
											
											?>
											<input type="hidden" readonly="readonly" name="user_role"  value='<?php echo $row['user_role']; ?>' >
											<input type="text" class="form-control" readonly value="<?php echo $r1['role']; ?>" >
											</div>
										</div>
										
						<!-- Step 1 -->
						<?php
							
							$ii = 0;
							$sql=" SELECT id as 'main_menu_id', menu_name as 'main_menu_name' FROM sma_main_menu where id > 1 order by order_no ";
							$query = mysqli_query($con,$sql);
		//echo $sql."<BR>";		
							while ($r2 = mysqli_fetch_array($query)){
								$main_menu_name 	= $r2['main_menu_name'];
								$main_menu_id	 	= $r2['main_menu_id'];
								
								$ii += 1;
								
								$in_collapse ='';
								if($ii==1){
									$in_collapse = 'in';
								}
								
								$user_id    = $_GET['id'];
								$sql   = "Select * from useraccess where user_role ='$user_id' and main_menu_id = '$main_menu_id' and menu_id = 0";
								
								$res2  = mysqli_query($con,$sql);
								$row   = mysqli_fetch_array($res2);
								$name_id			= $row['id'];
										
						?>	
								
                        <div class="panel panel-default">
                            
							<div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step<?php echo $main_menu_id ?>"><b><?php echo $main_menu_name; ?></b></a></h4>
							</div>
							
							
                            <div id="step<?php echo $main_menu_id ?>" class="panel-collapse collapse <?php echo $in_collapse ?> in123">
								<div class="panel-body">
									<fieldset>
								
									<div class="form-group">
										<label class="col-lg-3 control-label" style="text-align:left;">Menu Option</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Dashboard </label>
										<label class="col-lg-1 control-label" style="text-align:left;">Menu</label>
										<label class="col-lg-1 control-label" style="text-align:left;">View</label>
										<label class="col-lg-1 control-label" style="text-align:left;">Add</label>

									</div>	
									
								<div class="form-group">
								
									<label class="col-lg-4 control-label" style="text-align:left;">Parent Main Menu</label>
									<input type="hidden" name='row_id[]' value='<?= $name_id ?>' >
									<div class="col-lg-2">
										<input type="checkbox" name='menuonly<?= $name_id ?>' value='Y' <?php $menuonly1=$row['menuonly']; if ($menuonly1=="Y") echo "checked";?>> 
									</div>
								
								</div>
								
								<?php
								
									$user_id    = $_GET['id'];
									$sql   = "Select a.* from useraccess a, sma_menu b where a.user_role ='$user_id' and a.main_menu_id = '$main_menu_id' and a.menu_id >0
									and a.menu_id = b.id order by order_no ";

									$res2 = mysqli_query($con,$sql);
									while($row   = mysqli_fetch_array($res2)){
										$sub_menu_id		= $row['menu_id'];
										$name_id			= $row['id'];
										
										$sql=" SELECT * FROM `sma_menu` where id = '$sub_menu_id' and status = 'Y'  ";				
						
										$res3 = mysqli_query($con,$sql);
										$row_affect = mysqli_affected_rows($con);										
										$r3 = mysqli_fetch_array($res3);
										$sub_menu_id		= $r3['id'];
										$sub_menu_name	 	= $r3['sub_menu_name'];

										if($row_affect>0){
								?>										
										<div class="form-group">
											<label class="col-lg-3 control-label" style="text-align:left;"><?php echo $sub_menu_name; ?></label>
											<input type="hidden" name='row_id[]' value='<?= $name_id ?>' > 
											
											<div class="col-lg-1">
												<input type="checkbox" name='dashboard<?= $name_id ?>' value='Y' 
													<?php $dashboard1=$row['dashboard']; if($dashboard1=="Y"){ echo "checked"; }?>>
											</div>

											<div class="col-lg-1">
												<input type="checkbox" name='menuonly<?= $name_id ?>' value='Y' <?php $menuonly1=$row['menuonly']; if ($menuonly1=="Y") echo "checked";?>> 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='readonly<?= $name_id ?>' value='Y' 
												<?php $readonly1=$row['readonly']; if ($readonly1=="Y") echo "checked";?> > 
											</div>
											
											<div class="col-lg-1">
												<input type="checkbox" name='writeonly<?= $name_id ?>' value='Y' 
												<?php $writeonly1=$row['writeonly']; if ($writeonly1=="Y") echo "checked";?> > 
											</div>
											
										</div>

									<?php }

										}
									?>
									
									</fieldset>
									
								  </div>
								</div>
                            </div>
                        
						<?php } ?>		
							
							
					<?php	
						
						$sql="Select * from sma_role where id ='$role_id'";
						$qry = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($qry);
					?>		
					<div class="form-group">
							<label class="control-label col-md-2">&nbsp;</label>
							<div class="col-md-5">
							<label class="control-label">Allow to remove Tally Journal from the Queue?</label><br>	
								<input type="checkbox" id="tally_access" name="tally_access" placeholder="" value="Y" <?php echo ($r2['tally_access']=='Y')?"CHECKED":'';?> >
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
