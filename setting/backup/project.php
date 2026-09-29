<?php

include("../header.php");
$modulePath = "setting/project.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Location
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Location</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Location</h3>
                <span class="pull-right"><a href="project.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Location </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Location</th>
			<th>Company</th>
			<th>Address</th>
			<th>Contact No.</th>
			<th>Email</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	
//	$sql="SELECT comp_name, comp_start_date, comp_end_date, comp_ac_year_to, b.loc_name from company a INNER JOIN sma_location AS b on a.comp_id = b.loc_comp_id";

	$sql="SELECT a.id, a.loc_name, a.loc_addr1, a.loc_contact_person_mobile, a.loc_email, b.comp_name as loc_company_name from sma_location a INNER JOIN company b on a.loc_comp_id = b.comp_id order by loc_name ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'project.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "project.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['loc_name'];?></td>
		<td width="20%"><?php echo $row['loc_company_name'];?></td>
		<td width="30%"><?php echo $row['loc_addr1'];?></td>
		<td width="20%"><?php echo $row['loc_contact_person_mobile'];?></td>
		<td width="20%"><?php echo $row['loc_email'];?></td>

		<td width="10%" style="text-align:right;">
		<a href="project.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--<a href="project.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_location where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="project.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			
			$loc_name 			= $_POST['loc_name'];
			$loc_comp_id		= $_POST['loc_comp_id'];
			
			$loc_addr1 			= $_POST['loc_addr1'];
			$loc_addr2 			= $_POST['loc_addr2'];
			$loc_addr3 			= $_POST['loc_addr3'];
			$loc_email 			= $_POST['loc_email'];
			//$loc_office 		= $_POST['loc_office'];
			//$loc_mobile 		= $_POST['loc_mobile'];
			$loc_pincode 		= $_POST['loc_pincode'];
			$loc_state 		= $_POST['loc_state'];
			$loc_faxno			= $_POST['loc_faxno'];
			$loc_website  		= $_POST['loc_website'];
			$loc_gst_no 		= $_POST['loc_gst_no'];
			$loc_pan_no  		= $_POST['loc_pan_no'];
			
			$loc_contact_person_mobile = $_POST['loc_contact_person_mobile'];
			$loc_contact_person = $_POST['loc_contact_person'];

  			$sql="insert into sma_location (loc_name, loc_comp_id, loc_addr1, loc_addr2, loc_addr3, loc_email, loc_pincode, loc_state, loc_faxno, loc_website, loc_gst_no, loc_pan_no, loc_contact_person, loc_contact_person_mobile ) 
			Values('$loc_name', '$loc_comp_id', '$loc_addr1', '$loc_addr2', '$loc_addr3', '$loc_email',  '$loc_pincode', '$loc_state', '$loc_faxno', '$loc_website', '$loc_gst_no', '$loc_pan_no', '$loc_contact_person', '$loc_contact_person_mobile' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Location successful added";
			echo '<script>window.location.href="project.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Location
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Location</a></li>
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
            <form class="form-horizontal" action="project.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							
							<div class="col-md-4">
							<label class="control-label">Location Name<span class="f_req">&nbsp;*</span></label>
								<input type="text" class="form-control" id="loc_name" name="loc_name" placeholder="Location Name" value="<?php echo $loc_name;?>">
							</div>
							
							<div class="col-sm-4">
                            <label for="itemName" class="control-label">Under Company Name</label>
                                     <select class="form-control" id="loc_comp_id" name="loc_comp_id">
										<option value="">Select</option>	
                                    <?php
                                    	$sql="SELECT * FROM company ORDER BY comp_name ASC";
                                        $result = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($row = mysqli_fetch_array($result)){
                                    ?>
                                        <option value="<?php echo $row['comp_id']?>" ><?php echo $row['comp_name'] ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
								
							<div class="col-md-4" class="input-append">
								<label class="control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="loc_contact_person" name="loc_contact_person" autocomplete="off" value="">
							</div>	
								
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Contact Person Mobile</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control" id="loc_contact_person_mobile" name="loc_contact_person_mobile" autocomplete="off" value="" >
							</div>
							
							<label class="col-lg-2 control-label">Contact Person Email</label>
							<div class="col-sm-4 col-md-4">
								<input type="email" class="form-control" id="loc_email" name="loc_email" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Delivery Address </label>
							<div class="col-md-5" class="input-append">
								<textarea rows="3" class="form-control" id="loc_addr1" name="loc_addr1" autocomplete="off" ></textarea>
							</div>						
						
							<label class="col-lg-1 control-label">State</label>
							<div class="col-md-3" class="input-append">
								<select class="form-control" id="loc_state" name="loc_state">
									<option value="">Select</option>	
                                    <?php
                                   	$sql="SELECT * FROM states ORDER BY state_name ASC";
                                    $res = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    while($r2 = mysqli_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $r2['state_name']?>" ><?php echo $r2['state_name'] ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						
						<div class="form-group">
						<!--	<label class="col-lg-1 control-label">Mobile</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_mobile"  name="loc_mobile" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-1 control-label">Office No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_office" name="loc_office" autocomplete="off" value="">
							</div>	
						-->	
							<label class="col-lg-1 control-label">GST No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_gst_no" name="loc_gst_no" autocomplete="off" value="">
							</div>						
						
							<label class="col-lg-1 control-label">PAN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_pan_no" name="loc_pan_no" autocomplete="off" value="">
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
 //echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$loc_name 			= $_POST['loc_name'];
			$loc_comp_id		= $_POST['loc_comp_id'];
			$loc_addr1 			= $_POST['loc_addr1'];
			$loc_email 			= $_POST['loc_email'];
			//$loc_office 		= $_POST['loc_office'];
			//$loc_mobile 		= $_POST['loc_mobile'];
			$loc_pincode 		= $_POST['loc_pincode'];
			$loc_state 			= $_POST['loc_state'];
			$loc_gst_no 		= $_POST['loc_gst_no'];
			$loc_pan_no  		= $_POST['loc_pan_no'];
			
			$loc_contact_person_mobile = $_POST['loc_contact_person_mobile'];
			$loc_contact_person = $_POST['loc_contact_person'];
			
			
  			$sql="update sma_location set loc_name  = '$loc_name', 
								loc_comp_id			= '$loc_comp_id',
								loc_addr1 			= '$loc_addr1', 
								loc_email 			= '$loc_email', 
								loc_pincode 		= '$loc_pincode',
								loc_state 			= '$loc_state',
								loc_gst_no 			= '$loc_gst_no',
								loc_pan_no 			= '$loc_pan_no',
								loc_contact_person_mobile = '$loc_contact_person_mobile',
								loc_contact_person = '$loc_contact_person'
				where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="project.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_location where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Location
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Location</a></li>
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
            <form class="form-horizontal" action="project.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label">Location Name<span class="f_req">&nbsp;*</span></label>
								<input type="text" class="form-control" id="loc_name" name="loc_name" placeholder="Location Name" value="<?php echo $row['loc_name'];?>">
							</div>

							<div class="col-sm-4">
									<label for="itemName" class="control-label">Company Name</label>
                                    <select class="form-control" id="loc_comp_id" name="loc_comp_id">
										<option value="">Select</option>	
                                    <?php
                                    	$sql="SELECT * FROM company ORDER BY comp_name ASC";
                                        $result = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($r2 = mysqli_fetch_array($result)){
                                    ?>
                                        <option value="<?php echo $r2['comp_id']?>" <?php echo ($row['loc_comp_id'] == $r2['comp_id'])?'selected="selected"':'';?>><?php echo $r2['comp_name'] ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
							
						
							<div class="col-md-4" class="input-append">
								<label class="control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="loc_contact_person" name="loc_contact_person" autocomplete="off" value="<?php echo $row['loc_contact_person'];?>">
							</div>	
						</div>
						
						<div class="form-group">	
							<label class="col-lg-2 control-label">Contact Person Mobile</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control" id="loc_contact_person_mobile" name="loc_contact_person_mobile" autocomplete="off" value="<?php echo $row['loc_contact_person_mobile'];?>" >
							</div>
							
							<label class="col-lg-2 control-label">Contact Person Email</label>
							<div class="col-sm-4">
								<input type="email" class="form-control" id="loc_email" name="loc_email" autocomplete="off" value="<?php echo $row['loc_email'];?>">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Deliery Address</label>
							<div class="col-md-5" class="input-append">
								<textarea rows="3" class="form-control" id="loc_addr1" name="loc_addr1" autocomplete="off"><?php echo $row['loc_addr1'];?></textarea>
							</div>
							
							<label class="col-lg-1 control-label">State</label>
							<div class="col-md-3" class="input-append">
								<select class="form-control" id="loc_state" name="loc_state">
									<option value="">Select</option>	
                                    <?php
                                   	$sql="SELECT * FROM states ORDER BY state_name ASC";
                                    $res = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    while($r2 = mysqli_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $r2['state_name']?>" <?php echo ($row['loc_state'] == $r2['state_name'])?'selected="selected"':'';?>><?php echo $r2['state_name'] ?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
						
						<div class="form-group">
						<!--	<label class="col-lg-1 control-label">Mobile</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  id="loc_mobile"  name="loc_mobile" autocomplete="off" value="<?php echo $row['loc_mobile'];?>">
							</div>
							
							<label class="col-lg-1 control-label">Office No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_office" name="loc_office" autocomplete="off" value="<?php echo $row['loc_office'];?>">
							</div>
						-->
						<?php
							$gsterr = '';
							$loc_gst_no = $row['loc_gst_no'];
							if(strlen($loc_gst_no)<15){
								$gsterr = 'GST length should be 15 character !!!';
							}	
							
							
						?>
							<label class="col-lg-1 control-label">GST No.</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control" id="loc_gst_no" name="loc_gst_no" autocomplete="off" value="<?php echo $row['loc_gst_no'];?>">
								<?php if(!empty($gsterr)){ ?>	
								<br><label class=" control-label" style='color:red;'><?= $gsterr;?></label>
							<?php } ?>
							</div>						
							
							
							<label class="col-lg-1 control-label">PAN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="loc_pan_no" name="loc_pan_no" autocomplete="off" value="<?php echo $row['loc_pan_no'];?>">
							</div>						
						</div>
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/project.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
