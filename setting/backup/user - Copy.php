	<!DOCTYPE html>
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
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User</h3>
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
		<th>Phone</th>
		<th>Role</th>
		<th>Status</th>
		<th style="text-align:right;">Action</th>
		
    </tr>
</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	
	$sql = "SELECT * from sma_user ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$role_id = $row['role'];
		$sql = "select * from sma_role where id = '$role_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$role = $r2['role'];
		
	$baseurl1 = $baseurl.$modulePath1.'user.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "user.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['username'];?></td>
		<td width="20%"><?php echo $row['userid'];?></td>
		<td width="20%"><?php echo $row['email'];?></td>
		<td width="20%"><?php echo $row['phone'];?></td>
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
			$phone		= $_POST['phone'];
//			$password	= md5($_POST['password']);
			$level_id		= $_POST['level_id'];
			$roll_no		= $_POST['roll_no'];
			$designation	= $_POST['designation'];
			$password	= $_POST['password'];
			$active 	= $_POST['active'];
			$user_category	= $_POST['user_category'];
			$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];
			
			$role			= $_POST['role'];
			$department		= $_POST['department'];
			
			$company_id	= $_POST['company_id'];
			$checked= sizeof($company_id);
			if($checked>=1){
				foreach ($_POST['company_id'] as $company_id){
					$comp_id .= $company_id.',';
				}
			}
			$company_id = $comp_id.'0';
			
			$level_1		= $_POST['level_1'];
			$checked= sizeof($level_1);
			if($checked>=1){
				foreach ($_POST['level_1'] as $level_1){
					$level_id_1 .= $level_1.',';
				}
			}
			$level_1 = $level_id_1.'0';
			
			$level_2		= $_POST['level_2'];
			$checked= sizeof($level_2);
			
			if($checked>=1){
				foreach ($_POST['level_2'] as $level_2){
					$level_id_2 .= $level_2.',';
				}
			}
			$level_2 = $level_id_2.'0';
			
			$level_3		= $_POST['level_3'];
			$checked= sizeof($level_3);
			
			if($checked>=1){
				foreach ($_POST['level_3'] as $level_3){
					$level_id_3 .= $level_3.',';
				}
			}
			$level_3 = $level_id_3.'0';
			
						$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];

			
  			$sql="insert into sma_user (username, userid, email, phone, level_id, roll_no, designation, active, password, company_id, level_1, level_2, level_3, user_category, role, department, password_expired_date, password_expired_days ) 
			Values('$username', '$userid', '$email', '$phone', '$level_id', '$roll_no', '$designation', '$active', '$password', '$company_id', '$level_1', '$level_2', '$level_3', '$user_category', '$role', '$department', '$password_expired_date', '$password_expired_days' )";
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
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="username" name="username" placeholder="" autocomplete="off" value="">
							</div>
						
							<label class="col-lg-1 control-label">User Id</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="userid" name="userid" placeholder="" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">Employee Number</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="" autocomplete="off" value="">
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
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="email" name="email" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-1 control-label">Phone</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="phone" name="phone" autocomplete="off" value="">
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
						
							<label for="role" class="control-label col-sm-2">Employee Level</label>
							<div class="col-sm-3">
								<select class="form-control select2" name="level_id" id="level_id" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_level ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['level_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['level_name'];?></option>
										<?php } ?>
								</select>	
							</div>
							
						</div>
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">User Category</label>
							<div class="col-sm-3" style="padding-top: 6px;">
								<input type="radio" class="minimal"  <?php echo $selected_site; ?> name="user_category" id="user_category" value="S" >Site &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_ho; ?> name="user_category" id="user_category" value="H" >Head Office &nbsp;
								<input type="radio"  class="minimal" <?php echo $selected_co; ?> name="user_category" id="user_category" value="C" >Corporate
							</div>
						
							<label for="role" class="control-label col-sm-1">Role</label>
							<div class="col-sm-2">
								<select class="form-control select2" name="role" id="role" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
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
						</div>
						
						<div class="form-group">
						
							<label class="control-label col-sm-1"></label>
							<label class="control-label">Password Expired After Days </label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="password_expired_days" name="password_expired_days" value="" >
							</div>
							
							<label class="control-label">Password Expired After Date</label>
							<div class="col-sm-3">
							<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control" id="password_expired_date" name="password_expired_date" value="" >
								<div class="input-group-addon">
									<i class="fa fa-calendar-alt"></i>
								</div>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Company</label>
							<div class="col-md-3">
								  <?php 
									$sql = "select * from company order by comp_name";	
									$q2 = mysqli_query($con, $sql);
									?>
							<div class="col-md-5">
								<div style="height:200px;width:550px;overflow:scroll;border:1px #999;">
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
							
							</div>
										
						</div>
						
			<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Select Travel Workflow</h3>
						<div class="form-group">
								  
							<div class="col-md-4">
								<label class="control-label"> HOD Approver</label>
								<select class="form-control select2" multiple="multiple" name="level_1[]" id="level_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								
								<label class="control-label"> HR Approver</label>
								<select class="form-control select2" multiple="multiple" name="level_2[]" id="level_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label"> Admin Approver</label>
								<select class="form-control select2" multiple="multiple" name="level_3[]" id="level_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
					</div>	
				</div>		
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active'  checked="checked"  value='1'> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='0'> Inactive
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


<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			$username	= $_POST['username'];
			$userid		= $_POST['userid'];
			$email		= $_POST['email'];
			$phone		= $_POST['phone'];
			$level_id		= $_POST['level_id'];
			$roll_no		= $_POST['roll_no'];
			$designation	= $_POST['designation'];
			//$password	= md5($_POST['password']);
			$password	= $_POST['password'];
			$active 	= $_POST['active']; 
			$user_category	= $_POST['user_category'];
			$role			= $_POST['role'];
			$department		= $_POST['department'];
			$company_id		= $_POST['company_id'];
			$password_expired_date	= date('Y-m-d', strtotime($_POST['password_expired_date']));
			$password_expired_days	= $_POST['password_expired_days'];
			
			$checked= sizeof($company_id);
			
			if($checked>=1){
				foreach ($_POST['company_id'] as $company_id){
					$comp_id .= $company_id.',';
				}
			}
			$company_id = $comp_id.'0';
			
			$level_1		= $_POST['level_1'];
			$checked= sizeof($level_1);
			
			if($checked>=1){
				foreach ($_POST['level_1'] as $level_1){
					$level_id_1 .= $level_1.',';
				}
			}
			$level_1 = $level_id_1.'0';
			
			$level_2		= $_POST['level_2'];
			$checked= sizeof($level_2);
			
			if($checked>=1){
				foreach ($_POST['level_2'] as $level_2){
					$level_id_2 .= $level_2.',';
				}
			}
			$level_2 = $level_id_2.'0';
			
			$level_3		= $_POST['level_3'];
			$checked= sizeof($level_3);
			
			if($checked>=1){
				foreach ($_POST['level_3'] as $level_3){
					$level_id_3 .= $level_3.',';
				}
			}
			$level_3 = $level_id_3.'0';
			
  			$sql="update sma_user set password  = '$password',
									username	= '$username',
									userid		= '$userid',
									email		= '$email',
									phone		= '$phone',
									level_id	= '$level_id',
									roll_no		= '$roll_no',
									designation	= '$designation',
									company_id	= '$company_id',
									password_expired_date	= '$password_expired_date',
									password_expired_days	= '$password_expired_days',
									level_1		= '$level_1',
									level_2		= '$level_2',
									level_3		= '$level_3',
									role		= '$role',
									department	= '$department',
									active		= '$active',
									user_category	= '$user_category'
							where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="user.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_user where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

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
						
						<?php if ($user=='Admin'){ ?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="username" name="username" autocomplete="off" value="<?php echo $row['username'];?>" >
							</div>
						
							<label class="col-lg-1 control-label">User Id</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="userid" name="userid" autocomplete="off" value="<?php echo $row['userid'];?>" >
							</div>
							
							<label class="col-lg-2 control-label">Employee Number</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="" autocomplete="off" value="<?php echo $row['roll_no'];?>">
							</div>
							
						</div>
						<?php }
						else { ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="username" name="username" autocomplete="off" value="<?php echo $row['username'];?>" readonly="readonly">
							</div>
						
							<label class="col-lg-1 control-label">User Id</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="userid" name="userid" autocomplete="off" value="<?php echo $row['userid'];?>" readonly="readonly">
							</div>
							
							<label class="col-lg-2 control-label">Roll Number</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="roll_no" name="roll_no" placeholder="" autocomplete="off" value="<?php echo $row['roll_no'];?>" readonly="readonly" >
							</div>
							
							
						</div>
						<?php } ?>
						
						<?php 
							
							$password	= $row['password']; 
							$cpassword	= $password;
							
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="password" name="password" placeholder="" autocomplete="off" onkeyup="cf()" value="<?php echo $password;?>">
								<span class="help-block">Enter your password</span>
								<div id="mpassword"></div>
							</div>
							
							<label class="col-lg-2 control-label">Confirm Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="" autocomplete="off" onkeyup="pass()" value="<?php echo $cpassword;?>">
								<span class="help-block">Repeat password</span>
								<div id="mcpassword"></div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="email" name="email" autocomplete="off" value="<?php echo $row['email']; ?>">
							</div>
						
							<label class="col-lg-1 control-label">Phone</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="phone" name="phone" autocomplete="off" value="<?php echo $row['phone']; ?>">
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
						
							<label for="role" class="control-label col-sm-2">Employee Level</label>
							<div class="col-sm-3">
								<select class="form-control select2" name="level_id" id="level_id" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_level ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['level_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['level_name'];?></option>
										<?php } ?>
								</select>	
							</div>
							
						</div>
						<?php 
								$selected_co 	= '';
								$selected_ho 	= '';
								$selected_site 	= '';
								$user_category = $row['user_category'];
								if($user_category=='S' ){
									$selected_site = 'checked';
								}
								else if($user_category=='H'){
									$selected_ho = 'checked';
								}
								else if($user_category=='C'){
									$selected_co = 'checked';
								}
								else if($user_category==''){
									$selected_ho = '';
								}
						?>
							
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">User Category</label>
							<div class="col-sm-3" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $selected_site; ?> name="user_category" id="user_category" value="S" >Site &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_ho; ?> name="user_category" id="user_category" value="H" >Head Office &nbsp;
								<input type="radio"  class="minimal" <?php echo $selected_co; ?> name="user_category" id="user_category" value="C" >Corporate
							</div>
						
							<label for="role" class="control-label col-sm-1">Role</label>
							<div class="col-sm-2">
								<select class="form-control select3" name="role" id="role" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_role ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
							
							<label for="department" class="control-label col-sm-1">Department</label>
							<div class="col-sm-3">
								<select class="form-control select3" name="department" id="department" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_department ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>	
							
						</div>

						<?php 
							$password_expired_date	= date('d-m-Y', strtotime($row['password_expired_date'])); 
							if($password_expired_date=='01-01-1970'){
								$password_expired_date ='';
							}
						?>
						
						<div class="form-group">
							
							<label class="control-label col-sm-1"></label>
							<div class="col-sm-3">	
								<label class="control-label">Password Expired After Days </label>
								<input type="text" class="form-control" id="password_expired_days" name="password_expired_days" value="<?php echo $row['password_expired_days']; ?>" onblur="getexpiredate(this.value)" >
							</div>
							
							<div class="col-sm-3">
								<label class="control-label">Password Expired After Date</label>
								<div id="getexpiredate">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="password_expired_date" name="password_expired_date" value="<?php echo $password_expired_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							</div>
							
						</div>
							
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Company</label>
							<div class="col-md-3">
								  <?php 
									$com_id = $row['company_id'];
								
									$comid = explode(",", $com_id);
									$sql = "select * from company order by comp_name";	
									$q2 = mysqli_query($con, $sql);
									?>
							<div class="col-md-5">
								<div style="height:200px;width:550px;overflow:scroll;border:1px #999;">
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
							
							</div>
						
								
						</div>
						
			<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Select Travel Workflow</h3>

						<h4></h4>
						<div class="form-group">
								  <?php 
									$lvl_id = $row['level_1'];
								
									$lvlid = explode(",", $lvl_id);
									$sql = "select * from sma_user order by username";	
									$q2 = mysqli_query($con, $sql);
									?>
							<div class="col-md-4">
								<label class="control-label">HOD Approvr</label>
								
								<?php 
										$lvl_id = $row['level_1'];
										$lvlid = explode(",", $lvl_id); 
								?>
								<select class="form-control select2" multiple="multiple" name="level_1[]" id="level_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo in_array($r2['id'], $lvlid)?'selected="selected"':'';?>> <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
								
							</div>
							
							<div class="col-md-4">
								<?php 
									$lvl_id = $row['level_2'];
									$lvlid = explode(",", $lvl_id);
								?>
								<label class="control-label">HR Approver</label>
								<select class="form-control select2" multiple="multiple" name="level_2[]" id="level_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo in_array($r2['id'], $lvlid)?'selected="selected"':'';?>> <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<?php 
									$lvl_id = $row['level_3'];
									$lvlid = explode(",", $lvl_id);
								?>
								<label class="control-label">Admin Approver</label>
								<select class="form-control select2" multiple="multiple" name="level_3[]" id="level_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo in_array($r2['id'], $lvlid)?'selected="selected"':'';?>> <?php echo $r2['username'];?></option>
										<?php } ?>
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
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
								<a href="user.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
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
