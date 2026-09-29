<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "budget_subgroup.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Sub Group
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget Sub Group</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Budget Sub Group</h3>
                <span class="pull-right"><a href="budget_subgroup.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Budget Sub Group </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Budget Group</th>
			<th>Budget Sub Group</th>
			<!--<th>Posting A/c Name</th>-->
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from sma_budget_subgroup";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$budget_name_id = $row['budget_name'];
		$sql = "select * from sma_budget_name where id = '$budget_name_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
		
?>

	<tr>
		<td width="20%"><?php echo $budget_name;?></td>
		<td width="20%"><?php echo $row['budget_head'];?></td>
		<!--<td width="20%"><?php echo $row['budget_code'];?></td>-->
		<td width="10%" style="text-align:right;">
		<a href="budget_subgroup.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		<a href="budget_subgroup.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		
		</td>
    </tr>
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql	="Select * from sma_budget_subgroup where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$budget_head 			= $row['budget_head'];
// 			$budget_code 			= $row['budget_code'];
			$budget_name 			= $row['budget_name'];
			
			$company_id 	= '';
			$budget_code = '';
			$pgname 		= "budget_subgroup.php";
			include "../viewonly.php";
			$description 	= $budget_head.','.$budget_code. ','.$budget_name;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from sma_budget_subgroup where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="budget_subgroup.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
	
?>

<?php
	if(isset($_POST['Save'])){
		
			$budget_name	= $_POST['budget_name'];
			$budget_head	= $_POST['budget_head'];
// 			$budget_code	= $_POST['budget_code'];
			$transfer_flag	= $_POST['transfer_flag'];
			$admin_flag		= $_POST['admin_flag'];
			$travel_module	= $_POST['travel_module'];
		
		    $budget_code ='';
		    $sql="SELECT * FROM sma_budget_subgroup where 1 and budget_head = '$budget_head' ";
        	mysqli_query($con, $sql);
        	$rowaffect = mysqli_affected_rows($con);
        	if($rowaffect>0){
        	    echo "<script>alert('Error: Already available ...');</script>";
        	    echo '<script>window.location.href="budget_subgroup.php?sub=add";</script>';
        	    exit();
        	}
        	
  			$sql = " INSERT INTO sma_budget_subgroup ( budget_name, budget_head, budget_code, transfer_flag, admin_flag, travel_module ) 
						VALUES( '$budget_name', '$budget_head' , '$budget_head', '$transfer_flag', '$admin_flag', '$travel_module' )";
			$query = mysqli_query( $con, $sql );
			$error = mysqli_error($con);
			if(!empty($error)){
				echo $error; exit();
			}
			
			$company_id 	= '';
			$pgname 		= "budget_subgroup.php";
			include "../viewonly.php";
			$description 	= $budget_head.','.$budget_code. ','.$budget_name;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`( `user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action` ) 
			VALUES ( '$user_name', NOW(), '$main_menu', '$sub_menu', '$company_id', '$description', '$affect' )";
		    mysqli_query($con, $sql);
			
			//echo "Budget Sub Group successful added";
			echo '<script>window.location.href="budget_subgroup.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Budget Sub Group
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Sub Group</a></li>
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
            <form class="form-horizontal" action="budget_subgroup.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
					  <div class="form-group">
							<label class="col-lg-2 control-label">Budget Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_budget_name order by name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
									<?php } ?>
								</select>
							</div>
							
							<!--<label class="col-lg-2 control-label">Budget Transfer Not Allowed</label>-->
							<!--<div class="col-md-1" style='padding-top: 6px;' >-->
							<!--	<input type="checkbox" id="transfer_flag" name="transfer_flag" placeholder="" value="<?php echo $transfer_flag;?>" >-->
							<!--</div>-->
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="budget_head" name="budget_head" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
							
							<!--<label class="col-lg-2 control-label">Show in Transfer Module</label>-->
							<!--<div class="col-md-2" style='padding-top: 6px;' >-->
							<!--	<input type="checkbox" id="travel_module" name="travel_module" <?= $travel_module_checked;?> value="Y" >-->
							<!--</div>-->
							
							
						</div>
						
						<!--<div class="form-group">-->
						<!--	<label class="col-lg-2 control-label">Posting A/c Name</label>-->
						<!--	<div class="col-md-3">-->
						<!--		<input type="text" class="form-control" id="budget_code" name="budget_code" placeholder="" value="<?php echo $row['budget_code'];?>" >-->
						<!--	</div>-->
						<!--</div>-->
						
			<?php if($user=='Admin'){ ?>			
						<div class="form-group">
							<!--<label class="col-lg-2 control-label">Admin Managed </label>-->
							<!--<div class="col-md-1" style='padding-top: 6px;'>-->
							<!--	<input type="checkbox" id="admin_flag" name="admin_flag" placeholder="" value="Y" >-->
							<!--</div>-->
							<!--<label class="col-lg-9 control-labelff" style='padding-top: 6px;' >(This CC will now be shown in list view and will have sufficient balance to raise PO)</label>-->
						</div>
			<?php } ?>			
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
								<a href="budget_subgroup.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
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


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$budget_head	= trim($_POST['budget_head']);
// 			$budget_code	= $_POST['budget_code'];
			$transfer_flag	= $_POST['transfer_flag'];
			$admin_flag		= $_POST['admin_flag'];
			$travel_module	= $_POST['travel_module'];
			$disabled		= $_POST['disabled'];
            $budget_head_prev		= trim($_POST['budget_head_prev']);
            $show_dashboard     = $_POST['show_dashboard'];
			
            $budget_code = '';
            
            if($budget_head_prev !=$budget_head){
			    $sql="SELECT * FROM sma_budget_subgroup where 1 and budget_head ='$budget_head' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Budget Head Already available ...');</script>";
        		    echo "<script>window.location.href='budget_subgroup.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
            
			if($disabled=='DISABLED'){
				$sql="update sma_budget_subgroup set transfer_flag	= '$transfer_flag',
						admin_flag		= '$admin_flag',
						travel_module	= '$travel_module'
						where id='$id'";
			}			
			else {
				$sql="update sma_budget_subgroup set budget_head ='$budget_head', 
						budget_code 	= '$budget_head',
						transfer_flag	= '$transfer_flag',
						admin_flag		= '$admin_flag',
						travel_module	= '$travel_module',
						show_dashboard      = '$show_dashboard'
						where id='$id'";
			}
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "budget_subgroup.php";
			include "../viewonly.php";
			$description 	= $budget_head.','.$budget_code. ','.$budget_name;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`, travel_module ) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect', '$travel_module' )";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="budget_subgroup.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_budget_subgroup where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$budget_name = $row['budget_name'];

		$disabled = '';
		$sql="Select * from sma_budget where 1 and account_year = '2024-2025' and budget_head ='$id' and budget_name = '$budget_name' ";
		mysqli_query($con, $sql);
		$rwaffect = mysqli_affected_rows($con);
		if($rwaffect >0 ){
			$disabled = 'DISABLED';
		}	
        
		
?>

    <section class="content-header">
        <h1>
            Budget Sub Group
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Sub Group</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="budget_subgroup.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="disabled" value="<?php echo $disabled;?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Group</label>
							<input type="hidden" name="budget_name" id="budget_name"  value="<?php echo $row['budget_name'];?>" >
							<div class="col-md-4">
								<select class="form-control" disabled >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_budget_name order by name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?>  > <?php echo $r2['name'];?></option>
									<?php } ?>
								</select>
							</div>
							
							<?php
								$checked = '';
								$transfer_flag = $row['transfer_flag'];
								if($transfer_flag=='Y'){
									$checked = 'CHECKED';
								}
							?>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-4"  >
								<input type="text" class="form-control" <?= $disabled;?> id="budget_head" name="budget_head" placeholder="" value="<?php echo $row['budget_head'];?>" >
								
									<input type="hidden"  name="budget_head_prev"  value="<?php echo $row['budget_head'];?>" >
									
							</div>
							
						<?php
								$travel_module_checked = '';
								$travel_module = $row['travel_module'];
								if($travel_module=='Y'){
									$travel_module_checked = 'CHECKED';
								}
						?>	
							<!--<label class="col-lg-2 control-label">Show in Transfer Module</label>-->
							<!--<div class="col-md-2" style='padding-top: 6px;' >-->
							<!--	<input type="checkbox" id="travel_module" name="travel_module" <?= $travel_module_checked;?> value="Y" >-->
							<!--</div>-->
							
						</div>
						
						
						<!--<div class="form-group">-->
						<!--	<label class="col-lg-2 control-label">Posting A/c Name</label>-->
						<!--	<div class="col-md-4">-->
						<!--		<input type="text" class="form-control" <?= $disabled;?> id="budget_code" name="budget_code" placeholder="" value="<?php echo $row['budget_code'];?>" >-->
						<!--	</div>-->
						<!--</div>-->
						
						<?php
								$checked = '';
						 		$admin_flag = $row['admin_flag'];
								if($admin_flag=='Y'){
									$checked = 'CHECKED';
								}
						?>
				<?php //if($user=='Admin'){ ?>				
						<div class="form-group">
							<!--<label class="col-lg-2 control-label">Admin Managed </label>-->
							<!--<div class="col-md-1" style='padding-top: 6px;'>-->
							<!--	<input type="checkbox" id="admin_flag" name="admin_flag" <?= $checked;?> placeholder="" value="Y" >-->
							<!--</div>-->
							<!--<label class="col-lg-9 control-labelff" style='padding-top: 6px;' >(This CC will now be shown in list view and will have sufficient balance to raise PO)</label>-->
							<?php 
								$show_dashboard = $row['show_dashboard'];
								if($show_dashboard=='Y'){
									$show_dashboard_checked = 'checked';
								}
								
							?>
							<label class="col-lg-2 control-label">Show to Dashboard</label>
							
							<div class="col-md-1" style='padding-top: 6px;'>
								<input type="checkbox" <?php echo $show_dashboard_checked; ?> name="show_dashboard" value="Y" > 
							</div>
							
						</div>
				<?php //} ?>		
                        <div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="budget_subgroup.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>

                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
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

    function getDuplicate(id){
		var sub    = 'sub6';
//alert(sub);
        var table_name = 'sma_budget_subgroup';
        var col_name = 'budget_head';
        
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "app_func.php";
		$.post(strURL,{id:id,col_name:col_name,table_name:table_name,sub6:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}
	
</script>

</body>
</html>
