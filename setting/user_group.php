<?php

include("../header.php");
$modulePath = "setting/user_group.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        User Group
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">User Group</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of User Group</h3>
			 <?php  if($user=='Admin'){ ?>
				<span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=pdf" class="btn btn-primary">Report</a></span>
					&nbsp;&nbsp;&nbsp;
			  <?php } ?>		
                <span class="pull-right"><a href="user_group.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create User Group</a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

        <thead>
    <tr>
        <th>User Group</th>
		<th>Company</th>
		<th>User Name</th>
		<th>Status</th>
		<th style="text-align:right;">Action</th>
		
    </tr>
</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	
	$sql = "SELECT * from sma_user_group ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		$company = '';
		$company_id = $row['company_id'];
		$abc = explode(',',$company_id);
		if(count($abc)>1){
			$sql 	= "select * from company where comp_id in ( $company_id )";		
			$q2 	= mysqli_query($con, $sql);
			while($r2 	= mysqli_fetch_array($q2)){
				$company .= $r2['comp_name'].', ';
			}
		}
		
		$username 	= '';
		$user_group_id = $row['user_group_id'];
		$sql 		= "select * from sma_user where id in ($user_group_id) ";
	//echo $sql;	
		$q2 		= mysqli_query($con, $sql);
		while($r2 		= mysqli_fetch_array($q2)){
			$username 	.= $r2['username']. ', ';
		}
		
		$active=$row['status'];
		if ($active=="Y"){ 
			$active='Active';
		}
		else { 
			$active='Inactive';
		}
			
	$baseurl1 = $baseurl.$modulePath1.'user_group.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "user_group.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="15%"><?php echo $row['user_group'];?></td>
		<td width="20%"><?php echo $company;?></td>
		<td width="54%"><?php echo $username;?></td>
		<td width="5%"><?php echo $active;?></td>
		
		<td width="6%" style="text-align:right;">
		<a href="user_group.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
	<?php if ($user=='Admin'){ ?>
		<a href="user_group.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
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
		$sql="delete from sma_user_group where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="user_group.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){

	if(isset($_POST['Save'])){
		
			$user_group				= $_POST['user_group'];
			$active					= $_POST['active'];
			
			$company_id				= $_POST['company_id'];
			$checked= sizeof($company_id);
			if($checked>=1){
				foreach ($_POST['company_id'] as $company_id){
					$com_id .= $company_id.',';
				}
			}
			$company_id = $com_id.'0';
			
			$user_group_id				= $_POST['user_group_id'];
			$checked= sizeof($user_group_id);
			if($checked>=1){
				foreach ($_POST['user_group_id'] as $user_group_id){
					$usr_id .= $user_group_id.',';
				}
			}
			$user_group_id = $usr_id.'0';
			
  			$sql="insert into sma_user_group (user_group, user_group_id, company_id, status) Values('$user_group', '$user_group_id', '$company_id', '$active' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "User Group successful added";
			echo '<script>window.location.href="user_group.php?sub=list";</script>';
			
		}
	

?>

    <section class="content-header">
        <h1>
            User Group
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">User Group</a></li>
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
            <form class="form-horizontal" action="user_group.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>

				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User Group Name</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="user_group" name="user_group" placeholder="" autocomplete="off" value="">
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Company</label>
							<div class="col-md-10">
								<select class="form-control select2 " multiple name="company_id[]" id="company_Id" onchange="getusername(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "select * from company ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
								</select>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Users</label>
							<span id="getusername" >
							
							</span>
										
						</div>
						
					
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active'  checked="checked"  value='Y'> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='N'> Inactive
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
			$id						= $_POST['id']; 
			$user_group				= $_POST['user_group'];
			$company_id				= $_POST['company_id'];
			$active					= $_POST['active'];
			
			$user_group_id				= $_POST['user_group_id'];
			$checked= sizeof($user_group_id);
			
//	echo $checked;
//exit();	
			if($checked>=1){
				foreach ($_POST['user_group_id'] as $user_group_id){
					$usr_id .= $user_group_id.',';
				}
			}
			$user_group_id = $usr_id.'0';
			
			$sql="update sma_user_group set user_group	= '$user_group',
									user_group_id		= '$user_group_id',
									company_id			= '$company_id',
									status				= '$active'
							where id='$id' ";
//echo $sql;
//exit();
			
  	
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="user_group.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_user_group where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$user_group_id = $row['user_group_id'];
		
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
            <form class="form-horizontal" action="user_group.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
		
						<div class="form-group">
							<label class="col-lg-2 control-label">User Group</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="user_group" name="user_group" <?php echo $readonly; ?> autocomplete="off" value="<?php echo $row['user_group'];?>" >
							</div>
						
						</div>
						
						<?php $compid = $row['company_id']; 
								$compid = explode(",", $compid);
						
?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Company</label>
							<div class="col-md-10">
								<select class="form-control select2 " multiple name="company_id[]" id="company_Id" onchange="getusername(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "select * from company ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ 
												foreach ($compid as $company_id){													
											?>	
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
											<?php 
												} 
											}
											?>
								</select>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Users</label>
							<span id="getusername" >
							
								<div class="col-md-3">
									  <?php 
										$sql ='';
										$abc = explode(',',$company_id);
								
										$user_group_id = $row['user_group_id'];
									
										$usrsid = explode(",", $user_group_id);
										//$sql = "select * from sma_user where find_in_set($company_id, company_id) order by username ";
									$sql ='';
									if(count($abc)>1){	
										foreach ($compid as $company_id){		  
											$sql .= "select * from sma_user where active = '1' and FIND_IN_SET($company_id, company_id) 
												union ";
									
										}
									}
									else {
										$sql .= "select * from sma_user where active = '1' 
											union ";
									}	
									
										$sql .= ' select * from sma_user where id = 0 order by username';
									
										$q2 = mysqli_query($con, $sql);
										?>
								<div class="col-md-5">
									<div style="height:300px;width:550px;overflow:scroll;border:1px #999;">
										<table id="myTable" class="table table-hover panel panel-default table-bordered" >
									
											<tbody>
													<?php 
														while($r2 = mysqli_fetch_array($q2)){ 
															$usr_id = $r2['id'];
															
															$checked='';
															if (in_array($usr_id, $usrsid)){
																$checked = "CHECKED" ;
															}
													?>
													<tr>
														<td width="20%" style="text-align:left"><?php echo $r2['username'];?></td>
														<td width="10%" style="text-align:left"><?php echo $r2['userid'];?></td>
														<td width="5%" style="text-align:center"><input type="checkbox" id="user_group_id" name="user_group_id[]" <?php echo $checked;?> value="<?php echo $r2['id'];?>" /> </td>
														
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								</div>
								
								</div>
								
							</span>
							
						</div>
						
						<?php $active = $row['status'];?>
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active' value='Y' <?php if ($active=="Y") echo "checked";?>> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='N' <?php if ($active=="N") echo "checked";?>> Inactive
							</div>

							
						</div>

					</div>

					
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/user_group.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
  
  
  
  
function getusername(id){
    var sub = 'sub4';
	
	var company_id =  $("#company_Id").val();
//alert(company_id);

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "app_func.php";
	$.post(strURL,{ sub4:sub,id:id, company_id:company_id},function(result){
			  $('#getusername').html(result);
		});
}
  
</script>

</body>
</html>
