<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "setting/account_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Account
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Account</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Account</h3>
                <span class="pull-right"><a href="account_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Account </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Account</th>
			<th>Account Type</th>
			<th style="text-align:right;">Action</th>	
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from account_mst";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		$account_type = $row['account_type'];
		if($account_type =='A'){
			$account_type ='Account';
		}
		else if ($account_type =='B'){
			$account_type ='Cash/Bank';		
		}
		else if ($account_type =='D'){
			$account_type ='Deduction';		
		}
?>

	<tr>

		<td width="20%"><?php echo $row['account_name'];?></td>
		<td width="20%"><?php echo $account_type;?></td>

		<td width="10%" style="text-align:right;">
		<a href="account_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="account_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from account_mst where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="account_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			$account_name		= $_POST['account_name'];
			$account_type		= $_POST['account_type'];
			$branch				= $_POST['branch'];
			$address		 	= $_POST['address'];
			$email			 	= $_POST['email'];
			$mobile			 	= $_POST['mobile'];
			$account_number		= $_POST['account_number'];
			$isfc_code			= $_POST['isfc_code'];
			
  			$sql="insert into account_mst (account_name, account_type, branch, address, email, mobile, account_number, isfc_code ) 
					Values('$account_name', '$account_type', '$branch', '$address', '$email', '$mobile', '$account_number', '$isfc_code' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Account successful added";
			echo '<script>window.location.href="account_mst.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Account
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account</a></li>
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
            <form class="form-horizontal" action="account_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Account</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_name" name="account_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Type</label>
							<div class="col-md-3">
								<select class="form-control" name="account_type" id="account_type" required >
									<option value=""> Select </option>
									<option value="A"> Account</option>
									<option value="B"> Bank/Cash </option>
									<option value="D"> Deduction</option>
								</select>	
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Branch</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="branch" name="branch" autocomplete="off" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address</label>
							<div class="col-md-6">
								<textarea class="form-control" rows="3" id="address" autocomplete="off" name="address"></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Email Id</label>
							<div class="col-md-5">
								<input type='text' class="form-control" id="email" autocomplete="off" name="email" value="" >
							</div>
							
							<label class="col-lg-1 control-label">Mobile</label>
							<div class="col-md-4">
								<input type='text' class="form-control" id="mobile" autocomplete="off" name="mobile" value="" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">ISFC Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="isfc_code" name="isfc_code" autocomplete="off" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Number</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_number" name="account_number" autocomplete="off" value="" >
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

?>
	
<?php 
 echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$account_name		= $_POST['account_name'];
			$account_type		= $_POST['account_type'];
			$branch				= $_POST['branch'];
			$address		 	= $_POST['address'];
			$email			 	= $_POST['email'];
			$mobile			 	= $_POST['mobile'];
			$account_number		= $_POST['account_number'];
			$isfc_code			= $_POST['isfc_code'];
			
  			$sql="update account_mst set account_name ='$account_name',
							account_type 	= '$account_type',
							branch			= '$branch',
							address			= '$address',
							email		 	= '$email',
							mobile		 	= '$mobile',
							account_number	= '$account_number',
							isfc_code		= '$isfc_code'
					where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="account_mst.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from account_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Account
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account</a></li>
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
            <form class="form-horizontal" action="account_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_name" autocomplete="off" name="account_name" placeholder="" value="<?php echo $row['account_name'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Type</label>
							<div class="col-md-3">
								<select class="form-control" name="account_type" id="account_type" required >
									<option value=""> Select </option>
									<option value="A" <?php echo ($row['account_type'] == 'A')?'selected="selected"':'';?> > Account</option>
									<option value="B" <?php echo ($row['account_type'] == 'B')?'selected="selected"':'';?> > Bank/Cash </option>
									<option value="D" <?php echo ($row['account_type'] == 'D')?'selected="selected"':'';?> > Deduction</option>
								</select>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Branch</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="branch" name="branch" autocomplete="off" value="<?php echo $row['branch'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address</label>
							<div class="col-md-6">
								<textarea class="form-control" rows="3" id="address" autocomplete="off" name="address"><?php echo $row['address'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Email Id</label>
							<div class="col-md-5">
								<input type='text' class="form-control" id="email" autocomplete="off" name="email" value="<?php echo $row['email'];?>" >
							</div>
							
							<label class="col-lg-1 control-label">Mobile</label>
							<div class="col-md-4">
								<input type='text' class="form-control" id="mobile" autocomplete="off" name="mobile" value="<?php echo $row['mobile'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">ISFC Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="isfc_code" name="isfc_code" autocomplete="off" value="<?php echo $row['isfc_code'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Number</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_number" name="account_number" autocomplete="off" value="<?php echo $row['account_number'];?>" >
							</div>
						</div>
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/account_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
</script>

</body>
</html>
