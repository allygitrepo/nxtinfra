<?php

include("../header.php");
$modulePath = "setting/gst_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        GST Master
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">GST Master</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of GST Master</h3>
                <span class="pull-right"><a href="gst_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create GST Master </a></span>
				<!-- Ruchi started-->
				<span class="pull-right">
					<a href="account_export.php?sub=gst" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<!-- Ruchi ended-->
			</div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>TAX Description</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from gst_mst";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		
		$baseurl1 = $baseurl.$modulePath1.'gst_mst.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "gst_mst.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		
		<td width="20%"><?php echo $row['gst_name'];?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="gst_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="gst_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		
			$sql	="Select * from gst_mst where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$gst_name 			= $row['gst_name'];
			$igst	 			= $row['igst'];
			
			$company_id 	= '';
			$pgname 		= "gst_mst.php";
			include "../viewonly.php";
			$description 	= $gst_name. ' ,'.$igst;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from gst_mst where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="gst_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
		
			$gst_name		= $_POST['gst_name'];
			
			$igst			= $_POST['igst'];
			$sgst			= $igst/ 2 ;
			$cgst			= $igst/ 2 ;
			$status			= $_POST['status'];
			
			$sgst_account_id= $_POST['sgst_account_id'];
			$cgst_account_id= $_POST['cgst_account_id'];
			$igst_account_id= $_POST['igst_account_id'];
			
  			$sql="insert into gst_mst ( gst_name, sgst, cgst, igst, sgst_account_id, cgst_account_id, igst_account_id ) Values( '$gst_name', '$sgst', '$cgst', '$igst', '$sgst_account_id', '$cgst_account_id', '$igst_account_id' )";
					
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "gst_mst.php";
			include "../viewonly.php";
			$description 	= $gst_name. ' ,'.$igst;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo "GST Master successful added";
			echo '<script>window.location.href="gst_mst.php?sub=list";</script>';
			
	}

?>

    <section class="content-header">
        <h1>
            GST Master
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">GST Master</a></li>
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
            <form class="form-horizontal" action="gst_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">TAX Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="gst_name" name="gst_name" placeholder="" autocomplete="off" value="">
							</div>
							
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">GST % Rate</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="igst" name="igst" autocomplete="off" value="">
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<label class="col-lg-2 control-label">Posting Account</label>
							
						</div>
						
						<span id ="vertical_type_data" >
							
							<div class="form-group">
							<label for="user_category" class="control-label col-sm-2">SGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="sgst_account_id" id="sgst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($sgst_account_id == 0)?'selected="selected"':'';?> > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 and account_name like '%gst%' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						    </div>
						
						<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">CGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="cgst_account_id" id="cgst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($cgst_account_id == 0)?'selected="selected"':'';?>  > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 and account_name like '%gst%' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">IGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="igst_account_id" id="igst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($igst_account_id == 0)?'selected="selected"':'';?>  > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						
						</span>
						
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal"   name="status" checked id="status" value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal"  name="status" id="status" value="N" > Inactive &nbsp;
								
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

	if($_GET['sub'] == 'edit'){

?>
	
<?php 
 
	if(isset($_POST['Save'])){
		
			$id				= $_POST['id']; 
			$gst_name		= $_POST['gst_name'];
			$igst			= $_POST['igst'];
			$sgst			= $igst/ 2 ;
			$cgst			= $igst/ 2 ;
			$status			= $_POST['status'];
			
			$sgst_account_id= $_POST['sgst_account_id'];
			$cgst_account_id= $_POST['cgst_account_id'];
			$igst_account_id= $_POST['igst_account_id'];
			
  			$sql="update gst_mst set gst_name = '$gst_name', 
					sgst			= '$sgst',
					cgst			= '$cgst',
					igst			= '$igst',
					status			= '$status',
					sgst_account_id = '$sgst_account_id',
					cgst_account_id = '$cgst_account_id',
					igst_account_id = '$igst_account_id'
					where id = '$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "gst_mst.php";
			include "../viewonly.php";
			$description 	= $gst_name. ' ,'.$igst;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="gst_mst.php?sub=list";</script>';
			
	}
		
		$id = $_GET['id'];
		$sql="Select * from gst_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            GST Master
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">GST Master</a></li>
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
            <form class="form-horizontal" action="gst_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">TAX Description</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="gst_name" name="gst_name" placeholder="" value="<?php echo $row['gst_name'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">GST % Rate</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="igst" name="igst" autocomplete="off" value="<?php echo $row['igst'] ?>">
							</div>
						</div>
						
						<?php
						
							$sgst_account_id = $row['sgst_account_id'];
							$cgst_account_id = $row['cgst_account_id'];
							$igst_account_id = $row['igst_account_id'];
						
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<label class="col-lg-2 control-label">Posting Account</label>
						</div>
						
					<span id="vertical_type_data">	
						<div class="form-group">
							<label for="user_category" class="control-label col-sm-2">SGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="sgst_account_id" id="sgst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($sgst_account_id == 0)?'selected="selected"':'';?> > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 and account_name like '%gst%' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($sgst_account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">CGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="cgst_account_id" id="cgst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($cgst_account_id == 0)?'selected="selected"':'';?>  > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 and account_name like '%gst%' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($cgst_account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">IGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="igst_account_id" id="igst_account_id" >
									<option value=""> Select </option>
									<option value="0" <?php echo ($igst_account_id == 0)?'selected="selected"':'';?>  > Not Applicable </option>
										<?php $sql = "select * from account_mst where 1 and account_name like '%gst%' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($igst_account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
					</span>	
					
						<?php
								$selected_active 	= '';
								$selected_inactive	= '';
								$status = $row['status'];
								if($status=='M' ){
									$selected_active = 'checked';
								}
								else if($status=='S'){
									$selected_inactive = 'checked';
								}
						?>

						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $selected_active; ?> name="status" checked id="status" value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_inactive; ?> name="status" id="status" value="N" > Inactive &nbsp;
								
							</div>
							
						</div>
						
						
                        <div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `sma_product` where gst_type = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/gst_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
								<?php } //Mrunmayee ended?>
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
	
	
	/* function vertical_type_data (id){
		var sub    = 'sub1';
		var strURL = "gst_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#vertical_type_data').html(result);
		});
		
	}	 */
	
</script>

</body>
</html>
