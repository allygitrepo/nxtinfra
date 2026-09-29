<?php

include("../header.php");
$modulePath = "setting/";
?>

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

<?php if($_GET['sub'] == 'edit'){

	if(isset($_POST['Save'])){
			$id			= $_POST['id'];
			$username	= $_POST['username'];
			$userid		= $_POST['userid'];
//			$email		= $_POST['email'];
//			$phone		= $_POST['phone'];
			
			$ps				= $_POST['password'];
			$password		= md5($_POST['password']);
			
			$bank_name 				= $_POST['bank_name'];
			$bank_type 				= $_POST['bank_type'];
			$bank_branch 			= $_POST['bank_branch'];
			$bank_ac_no 			= $_POST['bank_ac_no'];
			$bank_ifsc 				= $_POST['bank_ifsc'];
			
  			$sql="update sma_user set password = '$password',
									bank_name 		= '$bank_name',
									bank_type 		= '$bank_type',
									bank_branch 	= '$bank_branch',
									bank_ac_no 		= '$bank_ac_no',
									bank_ifsc 		= '$bank_ifsc',
									ps 				= '$ps'
					where id='$id'";
//echo $sql; exit();
			$query= mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
	
			echo '<script>alert("Login with new password...");window.location.href="'.$baseurl.'logout.php'.'"</script>';
			
		}
		
		$user_id = $_GET['user_id'];
		$sql="Select * from sma_user where userid ='$user_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            User Profile
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User Profile</a></li>
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
            <form class="form-horizontal" action="user_profile.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="username" name="username" placeholder="" value="<?php echo $row['username'];?>" >
							</div>
						
							<label class="col-lg-2 control-label">User Id</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="userid" name="userid" placeholder="" autocomplete="off" value="<?php echo $row['userid'];?>" readonly="readonly" >
							</div>
						</div>
						
						<?php 
							
							$password	= $row['password']; 
							$password	= $row['ps']; 
							$cpassword	= $password;
							
							$readonly ="";
		                    //if($user !='Admin' && $user_name_by !='Admin'){ $readonly ="READONLY"; }

						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" id="password" name="password" placeholder="" autocomplete="off" onkeyup="cf()" value="<?php echo $password;?>">
								<span class="help-block">Enter your password</span>
								<div id="mpassword"></div>
							</div>
						</div>
						
						<div class="form-group">
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
								<input type="text" class="form-control" id="email" name="email" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $row['email']; ?>">
							</div>
						
							<label class="col-lg-1 control-label">Phone</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="phone" name="phone" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $row['phone']; ?>">
							</div>
						</div>
						
						<?php 
								$selected_ho 	= '';
								$selected_site 	= '';
								$user_category = $row['user_category'];
								if($user_category=='S' ){
									$selected_site = 'checked';
									$ucat	= 'Site';
								}
								else if($user_category=='H'){
									$selected_ho = 'checked';
									$ucat	= 'Head Office';
								}
								else if($user_category==''){
									$selected_ho = '';
								}
						?>
							
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">User Category</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<span> <?php echo $ucat ?> </span>
							</div>
						
					<?php 
					    $primary_role   = $row['primary_role'];
					    $department     = $row['department'];
					?> 
							<label for="role" class="control-label col-sm-1">Primary Role</label>
							<div class="col-sm-3">
								<select class="form-control select3" name="primary_role" id="primary_role" readonly onchange="getlocation(this.value)" >
                             		
										<?php $sql = "select * from sma_role where 1 and id = '$primary_role' ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['primary_role'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['role'];?></option>
										<?php } ?>
								</select>
							</div>
							
							
							<label for="department" class="control-label col-sm-1">Department</label>
							<div class="col-sm-3">
								<select class="form-control select3" name="department" id="department" readonly >
                             		
										<?php $sql = "select * from sma_department where 1 and id = '$department' ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>	
							
						</div>
						

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
													<td width="5%" style="text-align:center">
												<?php if (!empty($readonly)){	
														$ys = '';
														if($checked == "CHECKED"){
															$ys = 'Y';
														}		
												?>
													<?php echo $ys;?> 
													
												<?php }
												else {?>	
													<input type="checkbox" id="company_id" name="company_id[]"  <?php echo $checked;?> value="<?php echo $r2['comp_id'];?>" /> 
												<?php } ?>	
													</td>
												</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
							
							</div>
						
								
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<!--<a href="<?php echo $baseurl."setting/users.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> -->
							</div>
							<?php $baseurl1 = $baseurl."dashboard.php?sub=list";?>
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
</script>

</body>
</html>
