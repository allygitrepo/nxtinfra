<?php
include("../header.php");
$modulePath = "setting/user.php?sub=list";
?>

<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<script type="text/javascript">

function cf(){
   
    var a = new Array();
    a[0] = document.getElementById('password').value;
    a[1] = document.getElementById('cpassword').value;

    var b = new Array();
    b[0] = "<span style='color:red'>Please type your password!</span>";
    b[1] = "<span style='color:red'>Please confirm your password!</span>";

    var divs = new Array("mpassword", "mcpassword");
       
        for(i in a){
       
            var error = b[i];
            var div = divs[i];
           
            if(a[i]==""){
                document.getElementById(div).innerHTML = error;
            }else{
                document.getElementById(div).innerHTML = "OK!";
            }
               
        }
       
    }
   
   
function pass(){

    var first = document.getElementById('password').value;
    var second = document.getElementById('cpassword').value;

    if(second==first){
        document.getElementById('mcpassword').innerHTML = "OK!";
    }else{
        document.getElementById('mcpassword').innerHTML = "<span style='color: red'>Your passwords don't match!</span>";

	}
   
}

</script>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User</h3>
			 <?php  if($user=='Admin'){ ?>
				<span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=pdf" class="btn btn-primary">Report</a></span>
					&nbsp;&nbsp;&nbsp;
			  <?php } ?>		
                <span class="pull-right"><a href="user.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create User </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

        <thead>
    <tr>
        <th>User Name</th>
		<th>User Id</th>
		<th>Email Id</th>
		<th>Mobile</th>
		<th>Role</th>
		<th>Status</th>
		<th style="text-align:right;">Action</th>
		
    </tr>
</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	$role = '';
	$sql = "SELECT * from sma_user ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){

		$role ='';
		$role_id = $row['role'];
		if(empty($role_id)){
			$role_id = '0';
		}	
		$sql = "select * from sma_role where id in ( $role_id )";

		$q2 	= mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
			$role .= $r2['role'].',';
		}	

	$baseurl1 = $baseurl.$modulePath1.'user.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "user.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['username'];?></td>
		<td width="20%"><?php echo $row['userid'];?></td>
		<td width="20%"><?php echo $row['email'];?></td>
		<td width="20%"><?php echo $row['mobile_no'];?></td>
		<td width="20%"><?php echo $role;?></td>
		<?php $active=$row['active'];
			if ($active=="1"){ $active='Active';}
			else { $active='Inactive';}
		?> 
		<td width="10%"><?php echo $active;?></td>
		<td width="10%" style="text-align:right;">
		<a href="user.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
	<?php if ($user=='Admin'){ ?>
		<a href="user.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
	<?php } ?>
		</td>
    </tr>
	</a>
	
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
		$sql="delete from sma_user where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="user.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
			$username	= $_POST['username'];
			$userid		= $_POST['userid'];
			$email		= $_POST['email'];
			$mobile_no		= $_POST['mobile_no'];
			$pan_no		= $_POST['pan_no'];
			$roll_no		= $_POST['roll_no'];
			$password	= md5($_POST['password']);
			$designation	= $_POST['designation'];
		//	$password		= $_POST['password'];
			$ps				= $_POST['password'];
			$active 		= $_POST['active'];
			$company_work   = $_POST['company_work'];
			$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];

			
			$bank_name 				= $_POST['bank_name'];
			$bank_type 				= $_POST['bank_type'];
			$bank_branch 			= $_POST['bank_branch'];
			$bank_ac_no 			= $_POST['bank_ac_no'];
			$bank_ifsc 				= $_POST['bank_ifsc'];
			
			$regular_exp_approver	= $_POST['regular_exp_approver'];
			$mis_alert_email		= $_POST['mis_alert_email'];
			
						
			$role			= $_POST['role'];
			$department		= $_POST['department'];
			$mac_address	= $_POST['mac_address'];
			
			$company_id	= $_POST['company_id'];
			$checked= sizeof($company_id);
			if($checked>=1){
				foreach ($_POST['company_id'] as $company_id){
					$comp_id .= $company_id.',';
				}
			}
			$company_id = $comp_id.'0';
			
			$role_id	= $_POST['role_id'];
			$checked= sizeof($role_id);
			if($checked>=1){
				foreach ($_POST['role_id'] as $roleid){
					$rl_id .= $roleid.',';
				}
			}
			$role_id = $rl_id.'0';
			
			
			$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];

			
  			$sql="insert into sma_user (username, userid, email, roll_no, mobile_no, company_work, designation, active, password, company_id, role, department, password_expired_date, password_expired_days, pan_no, bank_name, bank_type, bank_branch, bank_ac_no, bank_ifsc,  ps)
			Values('$username', '$userid', '$email', '$roll_no', '$mobile_no', '$company_work', '$designation', '$active', '$password', '$company_id', '$role_id', '$department', '$password_expired_date', '$password_expired_days', '$pan_no', '$bank_name', '$bank_type', '$bank_branch', '$bank_ac_no', '$bank_ifsc', '$ps' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//$sql = "INSERT INTO `useraccess` (`userid`, `menu_id`, `manu_name`, `readonly`, `writeonly`, `editonly`, `printonly`, `menuonly`) VALUES
			//		('$userid', 10, 'Company Profile', 	'', '', '', '', '');";
			//$query=mysqli_query($con, $sql);
			//$error= mysqli_error($con);
			//if(!empty($error)){echo $error; exit();}
			
			echo "User successful added";
			echo '<script>window.location.href="user.php?sub=list";</script>';
			
		}
	

?>

    <section class="content-header">
        <h1>
            User
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User</a></li>
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
            <form class="form-horizontal" action="user.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>

				<ul class="nav nav-tabs">
				  
				  <li class="active"><a href="#tab_1" data-toggle="tab">User Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab">Bank Details</a></li>
				
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">User Id</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="userid" name="userid" placeholder="" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">Name of the User</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="username" name="username" placeholder="" autocomplete="off" value="">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="password" name="password" placeholder="" autocomplete="off" onkeyup="cf()" value="">
								<span class="help-block">Enter your password</span>
								<div id="mpassword"></div>
							</div>
						
							<label class="col-lg-2 control-label">Confirm Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="" autocomplete="off" onkeyup="pass()" value="">
								<span class="help-block">Repeat password</span>
								<div id="mcpassword"></div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Mobile</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="mobile_no" name="mobile_no" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-1 control-label">Email</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="email" name="email" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Emp.No.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="roll_no" name="roll_no" autocomplete="off" value="">
							</div>
						</div>	
							
						<div class="form-group">
							<label for="role" class="control-label col-sm-2">Designation</label>
							<div class="col-sm-3">
								<select class="form-control select2" name="designation" id="designation" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_designation ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['designation'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['designation'];?></option>
										<?php } ?>
								</select>	
							</div>
						
							<label for="department" class="control-label col-sm-1">Department</label>
							<div class="col-sm-3">
								<select class="form-control select2" name="department" id="department" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_department ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>	
							</div>
							
								<label class="col-lg-1 control-label">PAN No.</label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="pan_no" name="pan_no" autocomplete="off" value="<?php echo $row['pan_no']; ?>">
								</div>
							
							
							
						</div>
						
						<div class="form-group">
						
							<label class="control-label col-sm-1"></label>
							
							<div class="col-sm-5">
								<label class="control-label ">Working for Company</label>
								<select class="form-control select2" name="company_work" id="company_work" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>	
							</div>
							
							<div class="col-sm-3">
							<label class="control-label">Password Expiry after days </label>
								<input type="text" class="form-control" id="password_expired_days" name="password_expired_days" value="" onblur="getexpiredate(this.value)">
							</div>
							
							<div class="col-sm-3">
							<label class="control-label">Password to expire on date</label>
								<div id="getexpiredate">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="password_expired_date" name="password_expired_date" value="" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								</div>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-3 control-label">Access to Companies</label>
							<label class="col-lg-4 control-label">Role</label>
						</div>
						
						<div class="form-group">	
								<?php 
									$sql = "select * from company order by comp_name";	
									$q2 = mysqli_query($con, $sql);
								?>
								<label class="col-lg-1 control-label">&nbsp;</label>
									
								<div class="col-md-5">
									<div style="height:200px;width:400px;overflow:scroll;border:1px #999;">
										<table id="myTable" class="table table-hover panel panel-default table-bordered" >
									
											<tbody>
													<?php 
														while($r2 = mysqli_fetch_array($q2)){ 
													?>
													<tr>
														<td width="25%" style="text-align:left"><?php echo $r2['comp_name'];?></td>
														<td width="5%" style="text-align:center"><input type="checkbox" id="company_id" name="company_id[]" <?php if ($r2['comp_id'] == $company_id){echo "checked"; }?> value="<?php echo $r2['comp_id'];?>" /> </td>
														
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								</div>
								
								<?php 
										$sql = "select * from sma_role order by role";	
										$q2 = mysqli_query($con, $sql);
									?>
								<div class="col-md-5">
									
									<div style="height:200px;width:300px;overflow:scroll;border:1px #999;">
										<table id="myTable" class="table table-hover panel panel-default table-bordered" >
									
											<tbody>
													<?php 
														while($r2 = mysqli_fetch_array($q2)){ 
													?>
													<tr>
														<td width="25%" style="text-align:left"><?php echo $r2['role'];?></td>
														<td width="5%" style="text-align:center"><input type="checkbox" id="role_id" name="role_id[]" value="<?php echo $r2['id'];?>" /> </td>
														
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								</div>
								
							</div>
							
			                </div>
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="" value="<?php echo $row['bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="bank_type" id="bank_type" >
									<option value=""> Select </option>
									<option value="Saving" <?php echo ($row['bank_type']=='Saving')?'selected="selected"':'';?>> Saving</option>
									<option value="Current" <?php echo ($row['bank_type']=='Current')?'selected="selected"':'';?>> Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="bank_branch" name="bank_branch" placeholder="" value="<?php echo $row['bank_branch'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="bank_ac_no" name="bank_ac_no" placeholder="" value="<?php echo $row['bank_ac_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="bank_ifsc" name="bank_ifsc" placeholder="" value="<?php echo $row['bank_ifsc'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
					
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active'  checked="checked"  value='1'> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='0'> Inactive
							</div>

							<?php //$mac_address = Get_MAC(); ?>
						<!--	<label class="col-lg-4 control-label">Access only from the system having Mac Address </label>
							<div class="col-lg-3" style="padding-top: 6px;">
								<input type="text" name='mac_address' readonly value="<?php echo $mac_address;?>" >
							</div>
						-->	

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


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$username		= $_POST['username'];
			$userid			= $_POST['userid'];
			$email			= $_POST['email'];
			$roll_no		= $_POST['roll_no'];
			$mobile_no			= $_POST['mobile_no'];
			$designation	= $_POST['designation'];
			$ps				= $_POST['password'];
			$password		= md5($_POST['password']);
			//$password		= $_POST['password'];
			$active 		= $_POST['active']; 
			$role			= $_POST['role'];
			$role_id		= $_POST['role_id'];
			$pan_no			= $_POST['pan_no'];
			$department		= $_POST['department'];
			$company_id		= $_POST['company_id'];
			$company_work   = $_POST['company_work'];
			$primary_role   = $_POST['primary_role'];
			
			
			$accountant_role	= $_POST['accountant_role'];

			$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];
			$mac_address	= $_POST['mac_address'];
			
			$bank_name 				= $_POST['bank_name'];
			$bank_type 				= $_POST['bank_type'];
			$bank_branch 			= $_POST['bank_branch'];
			$bank_ac_no 			= $_POST['bank_ac_no'];
			$bank_ifsc 				= $_POST['bank_ifsc'];
			
			$level_1				= $_POST['level_1'];
			$checked= sizeof($company_id);
			
			if($checked>=1){
				foreach ($_POST['company_id'] as $company_id){
					$comp_id .= $company_id.',';
				}
			}
			$company_id = $comp_id.'0';
			
			
			
			$checked= sizeof($role_id);
			
			if($checked>=1){
				foreach ($_POST['role_id'] as $roleid){
					$rl_id .= $roleid.',';
				}
			}
			$role_id = $rl_id.'0';
			
  			$sql="update sma_user set username	= '$username',
									userid		= '$userid',
									email		= '$email',
									pan_no		= '$pan_no',
									mobile_no		= '$mobile_no',
									roll_no		= '$roll_no',
									designation	= '$designation',
									company_id	= '$company_id',
									password_expired_date	= '$password_expired_date',
									password_expired_days	= '$password_expired_days',
									role		= '$role_id',
									department	= '$department',
									active		= '$active',
									company_work	= '$company_work',
									bank_name 		= '$bank_name',
									bank_type 		= '$bank_type',
									bank_branch 	= '$bank_branch',
									bank_ac_no 		= '$bank_ac_no',
									bank_ifsc 		= '$bank_ifsc',
									mac_address		= '$mac_address',
									level_1			= '$level_1',
									ps				= '$ps',
									password		= '$password',
									accountant_role = '$accountant_role',
									primary_role	= '$primary_role'
							where id='$id' ";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="user.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_user where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$userid = $row['userid'];
		
		//echo $user;
		
		if($user !='Admin'){ $readonly ="READONLY"; }

?>

    <section class="content-header">
        <h1>
            User
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User</a></li>
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
            <form class="form-horizontal" action="user.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">User Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab">Bank Details</a></li>
				  
		
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
	
						<?php if ($user=='Admin'){ ?>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">User Id</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="userid" name="userid" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['userid'];?>" >
							</div>
							<label class="col-lg-2 control-label">Name of the user</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="username" name="username" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['username'];?>" >
							</div>
						
						</div>
						<?php }
						else { ?>
						<div class="form-group">
							
							<label class="col-lg-2 control-label">User Id</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="userid" name="userid" <?php echo $readonly; ?>  autocomplete="off" value="<?php echo $row['userid'];?>" readonly="readonly">
							</div>
							
							<label class="col-lg-2 control-label">Name of the user</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="username" name="username" <?php echo $readonly; ?>  autocomplete="off" value="<?php echo $row['username'];?>" readonly="readonly">
							</div>
							
						</div>
						<?php } ?>
						
						<?php 
							
							$password	= $row['password']; 
							$password	= $row['ps']; 
							$cpassword	= $password;
							
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="password" name="password" <?php echo $readonly; ?>  autocomplete="off" onkeyup="cf()" value="<?php echo $password;?>">
								<span class="help-block">Enter your password</span>
								<div id="mpassword"></div>
							</div>
							
							<label class="col-lg-2 control-label">Confirm Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="cpassword" name="cpassword" <?php echo $readonly; ?>  autocomplete="off" onkeyup="pass()" value="<?php echo $cpassword;?>">
								<span class="help-block">Repeat password</span>
								<div id="mcpassword"></div>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Mobile</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="mobile_no" name="mobile_no" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['mobile_no']; ?>">
							</div>
						
						<label class="col-lg-1 control-label">Email</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="email" name="email" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['email']; ?>">
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Emp.No.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="roll_no" <?php echo $readonly; ?> name="roll_no" autocomplete="off" value="<?php echo $row['roll_no']; ?>">
							</div>
						</div>	
						
						<div class="form-group">
							<label for="role" class="control-label col-sm-2">Designation</label>
							<div class="col-sm-3">
								<select class="form-control select2" name="designation" id="designation" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_designation ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['designation'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['designation'];?></option>
										<?php } ?>
								</select>	
							</div>
						
												
							<label for="department" class="control-label col-sm-1">Department</label>
							<div class="col-sm-3">
								<select class="form-control select3" name="department" id="department" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_department ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>	
							
							<label class="col-lg-1 control-label">PAN No.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="pan_no" name="pan_no" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['pan_no'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label class="control-label col-sm-1"></label>
							
							<div class="col-sm-5">
								<label class="control-label ">Working for Company</label>
								<select class="form-control select2" name="company_work" id="company_work" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_work'] == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>	
							</div>
							
							<?php 
					
							$password_expired_date	= date('d-m-Y', strtotime($row['password_expired_date'])); 
							if($password_expired_date=='01-01-1970'){
								$password_expired_date ='';
							}
							?>
						
							<div class="col-sm-3">	
								<label class="control-label">Password Expiry after days </label>
								<input type="text" class="form-control" id="password_expired_days" <?php echo $readonly; ?> name="password_expired_days" value="<?php echo $row['password_expired_days']; ?>" onblur="getexpiredate(this.value)" >
							</div>
							
							<div class="col-sm-3">
								<label class="control-label">Password to expire on date</label>
								<div id="getexpiredate">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="password_expired_date" <?php echo $readonly; ?> name="password_expired_date" value="<?php echo $password_expired_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							</div>
							
						</div>
							
						<div class="form-group">
							<label class="col-lg-3 control-label">Access to Companies</label>
							<label class="col-lg-5 control-label">Role</label>
						</div>
						
						<div class="form-group">
							<label class="col-lg-1 control-label">&nbsp;</label>
								  <?php 
									$com_id = $row['company_id'];
								
									$comid = explode(",", $com_id);
									$sql = "select * from company order by comp_name";	
									$q2 = mysqli_query($con, $sql);
									?>
							<div class="col-md-6">
								<div style="height:200px;width:450px;overflow:scroll;border:1px #999;">
									<table id="myTable" class="table table-hover panel panel-default table-bordered" >
								
										<tbody>
												<?php 
													while($r2 = mysqli_fetch_array($q2)){ 
														$comp_id = $r2['comp_id'];
														
														$checked='';
														if (in_array($comp_id, $comid)){
															$checked = "CHECKED" ;
														}
												?>
												<tr>
													<td width="25%" style="text-align:left"><?php echo $r2['comp_name'];?></td>
													<td width="5%" style="text-align:center"><input type="checkbox" id="company_id" name="company_id[]" <?php echo $checked;?> value="<?php echo $r2['comp_id'];?>" /> </td>
													
												</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
							
							<?php 
								$rol_id = $row['role'];
								$rolid = explode(",", $rol_id);
								$sql = "select * from sma_role order by role";	
								$q2 = mysqli_query($con, $sql);
							?>
							
								<div class="col-md-3">
									<div style="height:200px;width:350px;overflow:scroll;border:1px #999;">
										<table id="myTable" class="table table-hover panel panel-default table-bordered" >
									
											<tbody>
													<?php 
														while($r2 = mysqli_fetch_array($q2)){ 
															$rl_id = $r2['id'];
															
															$checked='';
															if (in_array($rl_id, $rolid)){
																$checked = "CHECKED" ;
															}
													?>
													<tr>
														<td width="25%" style="text-align:left"><?php echo $r2['role'];?></td>
														<td width="5%" style="text-align:center"><input type="checkbox" id="role_id" name="role_id[]" <?php echo $checked;?> value="<?php echo $r2['id'];?>" /> </td>
														
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								</div>
								
							</div>

							
						<div class="form-group">
							<label for="role" class="control-label col-sm-5">&nbsp; </label>	
							<label for="role" class="control-label col-sm-3">Primary Role for Menu Display </label>
							<div class="col-sm-3">
								<select class="form-control select2" name="primary_role" id="primary_role" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['primary_role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>	
							</div>
						</div>	
						
			<div class="box box-success">
            <div class="box-header with-border">
				<div class="form-group">
					<div class="col-md-5">
						<h3 class="box-title">Select Travel Request and Expense Approver</h3>
					</div>
					<div class="col-md-1">&nbsp;</div>
					<div class="col-md-6">
						<h3 class="box-title">Supplier Invoice Tally Journal Role</h3>
					</div>
				</div>
						<h4></h4>
						<div class="form-group">
								  
							<div class="col-md-5">
								<label class="control-label">Name</label>
								<select class="form-control select2" name="level_1" id="level_1" >
                             	<option value=""> Select </option>
								<?php $sql = "select * from sma_user order by username";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['id'];?>" <?php echo ($r2['id']==$row['level_1'])?'selected="selected"':'';?>> <?php echo $r2['username'];?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-1">&nbsp;</div>	
							<div class="col-md-4">
								<label class="control-label">Supplier Invoice Tally Journal Role</label>
								<select class="form-control select2" name="accountant_role" id="accountant_role" >
									<option value=""> Select </option>
									<option value="Y" <?php echo ($row['accountant_role']=='Y')?'selected="selected"':'';?>> Accountant </option>
									<option value="M" <?php echo ($row['accountant_role']=='M')?'selected="selected"':'';?>> AP Manager </option>
								</select>
							</div>
							
						</div>
					</div>
				</div>	
				
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active' value='1' <?php $active=$row['active'];
									if ($active=="1") echo "checked";?>> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='0' <?php $active=$row['active'];
									if ($active=="0") echo "checked";?>> Inactive
							</div>

							<?php
									$mac_address = $row['mac_address'];
									if(empty($mac_address)){
										$mac_address = Get_MAC(); 
									}
							//	echo $mac_address = Get_MAC();	
							?>
							
						<!--	<label class="col-lg-2 control-label">Access only from the system having Mac Address </label>
							<div class="col-lg-3" style="padding-top: 6px;">
								<input type="text" name='mac_address' readonly  value="<?php echo $mac_address;?>" >
							</div>
							
						</div>
						-->
                        </div>
						
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="" value="<?php echo $row['bank_name'];?>" >
							</div>
						
								<div class="col-md-3">
									<label class="control-label">Account Type </label>
									<select class="form-control" name="bank_type" id="bank_type" >
										<option value=""> Select </option>
										<option value="Saving" <?php echo ($row['bank_type']=='Saving')?'selected="selected"':'';?>> Saving</option>
										<option value="Current" <?php echo ($row['bank_type']=='Current')?'selected="selected"':'';?>> Current</option>
									</select>	
								</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="bank_branch" name="bank_branch" placeholder="" value="<?php echo $row['bank_branch'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="bank_ac_no" name="bank_ac_no" placeholder="" value="<?php echo $row['bank_ac_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="bank_ifsc" name="bank_ifsc" placeholder="" value="<?php echo $row['bank_ifsc'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
   					
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/user.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
  </div>
</section>
      
<?php } 	?>

<?php 	

	function Get_MAC(){
    ob_start();
    system('getmac');
    $Content = ob_get_contents();
    ob_clean();
    return substr($Content, strpos($Content,'\\')-20, 17);
}

function GetClientMAC(){
    $macAddr=false;
    $arp=`arp -n`;
    $lines=explode("\n", $arp);

    foreach($lines as $line){
        $cols=preg_split('/\s+/', trim($line));

        if ($cols[0]==$_SERVER['REMOTE_ADDR']){
            $macAddr=$cols[2];
        }
    }

    return $macAddr;
}

	include("../footer.php");	
		
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

	function getexpiredate(id){
	
		var sub    = 'sub1';
		var strURL = "user_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getexpiredate').html(result);
		});
	
	}
			
</script>

<!-- Select2 -->
<script src="http://localhost/hc_template/plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

</body>
</html>
