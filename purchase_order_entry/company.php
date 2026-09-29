<?php

include("../header.php");
$modulePath = "setting/company.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Company
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Company</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Company</h3>
                <span class="pull-right"><a href="company.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Company </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

        <thead>
    <tr>
        <th>company Name</th>
		<th>Code</th>
		<th>Vertical</th>

		<th style="text-align:right;">Action</th>
		
    </tr>
</thead>
<tbody>
<?php
	$modulePath1 = "setting/";
	
//	$sql="SELECT comp_name, comp_start_date, comp_end_date, comp_ac_year_to, b.loc_name from company a INNER JOIN sma_location AS b on a.comp_id = b.loc_comp_id";
	$sql="SELECT * from company ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'company.php?sub=edit&comp_id='.$row["comp_id"];
		
		$comp_vertical = $row['comp_vertical'];
		$sql="SELECT * FROM sma_vertical where id = '$comp_vertical' ";
		
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_vertical = $r2['vertical_name'];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company.php?sub=edit&comp_id=". $row['comp_id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="20%"><?php echo $row['comp_name'];?></td>
		<td width="20%"><?php echo $row['comp_code'];?></td>
		<td width="20%"><?php echo $comp_vertical;?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="company.php?sub=edit&comp_id=<?php echo $row['comp_id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--<a href="company.php?sub=delete&comp_id=<?php echo $row['comp_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
        $comp_id = $_GET['comp_id'];
		$sql="delete from company where comp_id='$comp_id' ";
			
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="company.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
/*echo '	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
';
echo "ravi drararas".$_GET['sub'].$_POST['Save'];
echo '</div></div>';
*/
	if(($_POST['Save'])){
			$comp_name 			= $_POST['comp_name'];
			$comp_code 			= $_POST['comp_code'];

			$comp_start_date 	= date('Y-m-d', strtotime($_POST['comp_start_date']));
			$comp_end_date 		= date('Y-m-d', strtotime($_POST['comp_end_date']));
			
			$comp_addr1 		= $_POST['comp_addr1'];
			$comp_addr2 		= $_POST['comp_addr2'];
			$comp_addr3 		= $_POST['comp_addr3'];
			$comp_email 		= $_POST['comp_email'];
			$comp_office 		= $_POST['comp_office'];
			$comp_mobile 		= $_POST['comp_mobile'];
			$comp_city  		= $_POST['comp_city'];
			$comp_pincode 		= $_POST['comp_pincode'];
			$comp_state 		= $_POST['comp_state'];
			$comp_website  		= $_POST['comp_website'];
			$comp_ac_year_from 	= $_POST['comp_ac_year_from'];
			$comp_ac_year_to 	= $_POST['comp_ac_year_to'];
			$comp_cin_no 		= $_POST['comp_cin_no'];
			$comp_pan_no  		= $_POST['comp_pan_no'];
			$comp_gst_no		= $_POST['comp_gst_no'];
			$budget_control_gst	= $_POST['budget_control_gst'];
			$project_manager	= $_POST['project_manager'];
			$project_incharge	= $_POST['project_incharge'];
			$coo_cxo			= $_POST['coo_cxo'];
			$comp_vertical		= $_POST['comp_vertical'];
			$comp_slogan		= $_POST['comp_slogan'];
			$general_terms		= $_POST['general_terms'];
			$header_terms		= $_POST['header_terms'];
			
			$po_last_number		= $_POST['po_last_number'];
			
			$logo_file_name 	= $_FILES['logo_file_name']['name'];
			$file_loc 			= $_FILES['logo_file_name']['tmp_name'];
			$logo_file_size 	= $_FILES['logo_file_name']['size'];
			$logo_file_type 	= $_FILES['logo_file_name']['type'];
			$logo_dir_name		= 'upload/';
			$logo_file_size 	= $logo_file_size/1024;  
			
			
	//echo $logo_file_name;			
			$sql="insert into company(comp_name, comp_code, comp_addr1, comp_addr2, comp_addr3, comp_email, comp_office,comp_mobile,comp_city, comp_pincode, comp_state, comp_website, comp_ac_year_from, comp_ac_year_to, comp_start_date, comp_end_date, comp_cin_no, comp_pan_no, comp_gst_no, budget_control_gst, project_manager,  project_incharge, coo_cxo,  logo_file_name, logo_dir_name, logo_file_type, logo_file_size, comp_vertical , comp_slogan, general_terms, po_last_number, header_terms ) 
			Values('$comp_name', '$comp_code', '$comp_addr1', '$comp_addr2', '$comp_addr3', '$comp_email', '$comp_office', '$comp_mobile', '$comp_city', '$comp_pincode', '$comp_state', '$comp_website', '$comp_ac_year_from', '$comp_ac_year_to', '$comp_start_date', '$comp_end_date', '$comp_cin_no', '$comp_pan_no', '$comp_gst_no', '$budget_control_gst', '$project_manager', '$project_incharge', '$coo_cxo', '$logo_file_name', '$logo_dir_name', '$logo_file_type', '$logo_file_size' , '$comp_vertical', '$comp_slogan', '$general_terms', '$po_last_number', '$header_terms' )";

			$query=mysqli_query($con,$sql);
			echo mysqli_error($con);
			
				if(!empty($logo_file_name)){
					if(move_uploaded_file($file_loc,$logo_dir_name.$logo_file_name)){
						echo "Logo file uploaded...";
					}
					else
					{
						echo " ..Error while file upload.. "; exit();
					}
				}
			
			echo "Company Information Successful Added";
			echo '<script>window.location.href="company.php?sub=list";</script>';

		}

?>

    <section class="content-header">
        <h1>
            Company
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Company</a></li>
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
            <form class="form-horizontal" action="company.php?sub=add" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                  	
						<div class="form-group">
							<label class="col-lg-2 control-label">Company Name<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="comp_name" name="comp_name" placeholder="Company Name" value="">
							</div>
							
							<label class="col-lg-2 control-label">Company Short Code<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="comp_code" name="comp_code" required placeholder="Short Code" value="">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Vertical Type </label>
							<div class="col-md-3" class="input-append">
								<select class="form-control" name="comp_vertical" id="comp_vertical" required >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_vertical ORDER BY vertical_name ASC";
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($result)){
										?>
									<option value="<?php echo $r2['id']?>" ><?php echo $r2['vertical_name'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-2 control-label">Budget Control</label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active' checked="checked"  value='Y'> With GST &nbsp;&nbsp;
							<input type="radio" name='active' value='N'> Without GST
							</div>
							
						</div>	
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Corporate Office Address</label>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr1" name="comp_addr1" autocomplete="off" value="">
							</div>						
						
							<label class="col-lg-1 control-label">Mobile.No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_mobile"  name="comp_mobile" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label"></label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr2" name="comp_addr2" autocomplete="off" value="">
							</div>						
						
							<label class="col-lg-1 control-label">Office No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_office" name="comp_office" autocomplete="off" value="">
							</div>
							
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label"></label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr3" name="comp_addr3" autocomplete="off" value="">
							</div>						
						
							<label class="col-lg-1 control-label">Email Id</label>
							<div class="col-sm-4 col-md-4">
								<input type="email" class="form-control" id="comp_email" name="comp_email" autocomplete="off" value="">
							</div>				
											
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Pincode </label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_pincode" name="comp_pincode" autocomplete="off" value="">
							</div>						
						
							<label class="col-lg-1 control-label">City</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_city" name="comp_city" autocomplete="off" value="">
							</div>		
						</div>
						
						<div class="form-group">
							<label class="col-lg-3 control-label" style="text-align:left;" >Registered Office Address</label>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address1" name="comp_register_address1" autocomplete="off" value="<?php echo $row['comp_register_address1'];?>">
							</div>
						
							<label class="col-lg-1 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address2" name="comp_register_address2" autocomplete="off" value="<?php echo $row['comp_register_address2'];?>">
							</div>						
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address3" name="comp_register_address3" autocomplete="off" value="<?php echo $row['comp_register_address3'];?>">
							</div>
						
							<label class="col-lg-2 control-label">Pincode</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_register_pincode" name="comp_register_pincode" autocomplete="off" value="<?php echo $row['comp_register_pincode'];?>">
							</div>						
						
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">State</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_state" name="comp_state" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">WebSite URL</label>
							<div class="col-md-3" class="input-append">
							
								<input type="text" class="form-control" id="comp_website" name="comp_website" autocomplete="off" value="">
							
							</div>						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">CIN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_cin_no" name="comp_cin_no" autocomplete="off" value="">
							</div>						
							
							<label class="col-lg-2 control-label">GST No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_gst_no" name="comp_gst_no" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-1 control-label">PAN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_pan_no" name="comp_pan_no" autocomplete="off" value="">
							</div>						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Year Start<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="comp_start_date" name="comp_start_date" value="" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	

							<label class="col-lg-2 control-label">Account Year End<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="comp_end_date"  name="comp_end_date" value="" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>								
						</div>
						
<!--						<div class="form-group">
							<label class="col-lg-2 control-label">Travel Request Approval By </label>
							<div class="col-md-3" class="input-append">
								<label >Project Manager </label>
								<select class="form-control" name="project_manager" id="project_manager" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($row = mysqli_fetch_array($result)){
										?>
									<option value="<?php echo $row['id']?>" ><?php echo $row['username'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3" class="input-append">
							
								<label >Project Incharge </label>
								<select class="form-control" name="project_incharge" id="project_incharge" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($row = mysqli_fetch_array($result)){
										?>
									<option value="<?php echo $row['id']?>" ><?php echo $row['username'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3" class="input-append">
							
								<label >COO/CXO </label>
								<select class="form-control" name="coo_cxo" id="coo_cxo" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($row = mysqli_fetch_array($result)){
										?>
									<option value="<?php echo $row['id']?>" ><?php echo $row['username'] ?></option>
									<?php } ?>
								</select>
								
							</div>
												
						</div>
-->
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Attach Company Logo</label>
							<div class="col-md-3" class="input-append">
								<input class="text" name="logo_file_name" type="file" />
							</div>	
							
						</div>
						
						<!--<h3 class="heading">Bank Details</h3>
												
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank A/C Title</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_title" autocomplete="off" value="">
							</div>	
							<label class="col-lg-1 control-label">IFSC Code</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  name="bank_ifsc_code" autocomplete="off" value="">
							</div>	
							
							<label class="col-lg-1 control-label">A/c Type</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_type" autocomplete="off" value="">
							</div>	
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank A/C Number</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_number" autocomplete="off" value="">
							</div>	
							
							<label class="col-lg-1 control-label">Branch </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_branch" autocomplete="off" value="">
							</div>	
						</div> -->
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Slogan</label>
							<div class="col-md-10" class="input-append">
								<input type="text" class="form-control" name="comp_slogan" autocomplete="off" value="<?php echo $row['comp_slogan'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Gerneral Terms</label>
							<div class="col-md-10" class="input-append">
								<textarea class="form-control"  name="general_terms" autocomplete="off"><?php echo $row['general_terms'];?></textarea>
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

/*echo '	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
';
echo "ravi drararas".$_GET['sub'].$_POST['Save'];
*/


		if($_POST['Save']){
			$comp_id 			= $_POST['comp_id'];
			$comp_name 			= $_POST['comp_name'];
			$comp_code 			= $_POST['comp_code'];
			$comp_addr1 		= $_POST['comp_addr1'];
			$comp_addr2 		= $_POST['comp_addr2'];
			$comp_addr3 		= $_POST['comp_addr3'];
			$comp_email 		= $_POST['comp_email'];
			$comp_office 		= $_POST['comp_office'];
			$comp_mobile 		= $_POST['comp_mobile'];
			$comp_city  		= $_POST['comp_city'];
			$comp_pincode 		= $_POST['comp_pincode'];
			$comp_state 		= $_POST['comp_state'];
			$comp_website  		= $_POST['comp_website'];
			$comp_ac_year_from 	= $_POST['comp_ac_year_from'];
			$comp_ac_year_to 	= $_POST['comp_ac_year_to'];
			$comp_start_date 	= date('Y-m-d', strtotime($_POST['comp_start_date']));
			$comp_end_date 		= date('Y-m-d', strtotime($_POST['comp_end_date']));
			$comp_cin_no 		= $_POST['comp_cin_no'];
			$comp_pan_no  		= $_POST['comp_pan_no'];
			$comp_gst_no		= $_POST['comp_gst_no'];
			$budget_control_gst	= $_POST['budget_control_gst'];
			$text_one	 		= $_POST['text_one'];
			$text_two		  	= $_POST['text_two'];
			/* $bank_ac_title		= $_POST['bank_ac_title'];
			$bank_ifsc_code		= $_POST['bank_ifsc_code'];
			$bank_ac_number		= $_POST['bank_ac_number'];
			$bank_ac_type		= $_POST['bank_ac_type'];
			$bank_ac_branch		= $_POST['bank_ac_branch'];
			$project_manager	= $_POST['project_manager'];
			$project_incharge	= $_POST['project_incharge'];
			$coo_cxo			= $_POST['coo_cxo']; */
			$comp_vertical		= $_POST['comp_vertical'];
			$comp_slogan		= $_POST['comp_slogan'];
			$general_terms		= $_POST['general_terms'];
			$po_last_number		= $_POST['po_last_number'];
			$header_terms		= $_POST['header_terms'];
			$comp_register_address1	= $_POST['comp_register_address1'];
			$comp_register_address2	= $_POST['comp_register_address2'];
			$comp_register_address3	= $_POST['comp_register_address3'];
			$comp_register_pincode	= $_POST['comp_register_pincode'];
			
			$sql = "update company set comp_name   = '$comp_name', comp_code 	= '$comp_code',
										comp_addr1 = '$comp_addr1', 
										comp_addr2 = '$comp_addr2', 
										comp_addr3 = '$comp_addr3', 
										comp_email = '$comp_email', 
										comp_office = '$comp_office', 
										comp_mobile = '$comp_mobile', 
										comp_city   = '$comp_city',
										comp_pincode 		= '$comp_pincode',
										comp_state 		= '$comp_state',
										comp_website  		= '$comp_website',
										comp_ac_year_from 	= '$comp_ac_year_from',
										comp_ac_year_to 	= '$comp_ac_year_to',
										comp_start_date 	= '$comp_start_date',
										comp_end_date 		= '$comp_end_date',
										comp_cin_no 		= '$comp_cin_no',
										comp_pan_no 		= '$comp_pan_no',
										comp_gst_no			= '$comp_gst_no',
										budget_control_gst	= '$budget_control_gst',
										project_manager		= '$project_manager',
										project_incharge	= '$project_incharge',
										coo_cxo				= '$coo_cxo',
										comp_vertical		= '$comp_vertical',
										comp_slogan			= '$comp_slogan',
										general_terms		= '$general_terms',
										po_last_number		= '$po_last_number',
										header_terms		= '$header_terms',
										comp_register_address1	= '$comp_register_address1',
										comp_register_address2	= '$comp_register_address2',
										comp_register_address3	= '$comp_register_address3',
										comp_register_pincode	= '$comp_register_pincode'
								where comp_id = '$comp_id'";
			
					$query=mysqli_query($con, $sql);
					echo mysqli_error($con);
			
					if(mysqli_error($con)) { exit();}
					
				$logo_file_name = $_FILES['logo_file_name']['name'];
				$file_loc 		= $_FILES['logo_file_name']['tmp_name'];
				$logo_file_size 	= $_FILES['logo_file_name']['size'];
				$logo_file_type 	= $_FILES['logo_file_name']['type'];
				$logo_dir_name	= 'upload/';
				

				if( ( $logo_file_type != 'image/jpeg' && $logo_file_type != 'image/png' ) && !empty($logo_file_type) ){
					
					$msg = " Please select image file as JPG or PNG format !";
					echo "<script>window.location.href='company.php?sub=edit&comp_id=$comp_id&msg=$msg';</script>";
					return;
				
				}
			
				$logo_file_size = $logo_file_size/1024;  
		//echo $logo_file_name;
				
				if(!empty($logo_file_name)){
					if(move_uploaded_file($file_loc,$logo_dir_name.$logo_file_name)){
					
						$sql = "update company set logo_file_name = '$logo_file_name', logo_dir_name = '$logo_dir_name', logo_file_type = '$logo_file_type', logo_file_size = '$logo_file_size' where comp_id = '$comp_id'";

						mysqli_query($con, $sql);
						$error=mysqli_error($con);
						if ($error) { echo "....Error while Logo file update..."; exit();}
					}
					else
					{
						echo " ..Error while file upload.. "; exit();
					}
				}
				
					echo "Company Information Successfully Updated";		
					echo '<script>window.location.href="company.php?sub=list";</script>';
			}
				
				$comp_id = $_GET['comp_id'];
				$sql="Select * from company where comp_id ='$comp_id'";
				$query = mysqli_query($con, $sql);
                $row = mysqli_fetch_array($query);
				$comp_name 			= $row['comp_name'];
				$comp_start_date 	= $row['comp_start_date'];
				$comp_end_date 		= $row['comp_end_date'];	
				

?>

    <section class="content-header">
        <h1>
            Company
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Company</a></li>
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
            <form class="form-horizontal" action="company.php?sub=edit" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
					<?php if(!empty($_GET['msg'])){ ?>
						<div class="form-group">
							<label class="col-lg-10"><?= $_GET['msg'] ?> </label>
						</div>
					<?php } ?>
					
							<input type="hidden" name="comp_id" value="<?php echo $row['comp_id'];?>">
						<div class="form-group">
							<label class="col-lg-2 control-label">Company Name<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="comp_name" name="comp_name" placeholder="Company Name" value="<?php echo $comp_name;?>">
							</div>
							
							<label class="col-lg-2 control-label">Company Short Code<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="comp_code" name="comp_code" required placeholder="Short Code" value="<?php echo $row['comp_code'];?>">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Vertical Type </label>
							<div class="col-md-3" class="input-append">
								<select class="form-control" name="comp_vertical" id="comp_vertical" required >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_vertical ORDER BY vertical_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
										?>
									<option value="<?php echo $r2['id']?>" <?php echo ($row['comp_vertical'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['vertical_name'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-2 control-label">Budget Control</label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='budget_control_gst' <?php $bcg=$row['budget_control_gst']; if ($bcg=="Y") echo "CHECKED";?>  value='Y'> With GST &nbsp;&nbsp;
							<input type="radio" name='budget_control_gst' <?php $bcg=$row['budget_control_gst']; if ($bcg=="N") echo "CHECKED";?>  value='N'> Without GST
							</div>
							
						</div>	
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Corporate Office Address </label>
							<label class="col-lg-2 control-label"></label>
							<label class="col-lg-3 control-label">PO.Last Number</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  id="po_last_number"  name="po_last_number" autocomplete="off" value="<?php echo $row['po_last_number'];?>">
							</div>	
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr1" name="comp_addr1" autocomplete="off" value="<?php echo $row['comp_addr1'];?>">
							</div>						
						
							<label class="col-lg-1 control-label">Mobile.No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  id="comp_mobile"  name="comp_mobile" autocomplete="off" value="<?php echo $row['comp_mobile'];?>">
							</div>						
												
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr2" name="comp_addr2" autocomplete="off" value="<?php echo $row['comp_addr2'];?>">
							</div>						
						
							<label class="col-lg-1 control-label">Office No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_office" name="comp_office" autocomplete="off" value="<?php echo $row['comp_office'];?>">
							</div>

						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_addr3" name="comp_addr3" autocomplete="off" value="<?php echo $row['comp_addr3'];?>">
							</div>
						
							
							<label class="col-lg-1 control-label">Email Id</label>
							<div class="col-sm-3">
								<input type="email" class="form-control" id="comp_email" name="comp_email" autocomplete="off" value="<?php echo $row['comp_email'];?>">
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Pincode </label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_pincode" name="comp_pincode" autocomplete="off" value="<?php echo $row['comp_pincode'];?>">
							</div>						
							
							<label class="col-lg-1 control-label">City</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_city" name="comp_city" autocomplete="off" value="<?php echo $row['comp_city'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-3 control-label" style="text-align:left;" >Registered Office Address</label>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Address </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address1" name="comp_register_address1" autocomplete="off" value="<?php echo $row['comp_register_address1'];?>">
							</div>
						
							<label class="col-lg-2 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address2" name="comp_register_address2" autocomplete="off" value="<?php echo $row['comp_register_address2'];?>">
							</div>						
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">&nbsp;</label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control" id="comp_register_address3" name="comp_register_address3" autocomplete="off" value="<?php echo $row['comp_register_address3'];?>">
							</div>
						
							<label class="col-lg-2 control-label">Pincode</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_register_pincode" name="comp_register_pincode" autocomplete="off" value="<?php echo $row['comp_register_pincode'];?>">
							</div>						
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">State</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_state" name="comp_state" autocomplete="off" value="<?php echo $row['comp_state'];?>">
							</div>						
						
							<label class="col-lg-2 control-label">WebSite URL</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control" id="comp_website" name="comp_website" autocomplete="off" value="<?php echo $row['comp_website'];?>">
							</div>						
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">CIN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_cin_no" name="comp_cin_no" autocomplete="off" value="<?php echo $row['comp_cin_no'];?>">
							</div>						
							
							<label class="col-lg-2 control-label">GST No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_gst_no" name="comp_gst_no" autocomplete="off" value="<?php echo $row['comp_gst_no'];?>">
							</div>
							
							<label class="col-lg-1 control-label">PAN No.</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control" id="comp_pan_no" name="comp_pan_no" autocomplete="off" value="<?php echo $row['comp_pan_no'];?>">
							</div>
							
						</div>
						<?php
							$comp_start_date = date('d-m-Y', strtotime($row['comp_start_date']));
							$comp_end_date = date('d-m-Y', strtotime($row['comp_end_date']));
						if ($comp_start_date=='01-01-1970' || $comp_start_date== '31-12-1969'){
							$comp_start_date='';	
						}
						if ($comp_end_date =='01-01-1970' || $comp_end_date== '31-12-1969'){
							$comp_end_date='';	
						}	
						
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Year Start<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="comp_start_date" <?php echo $readonly; ?> name="comp_start_date" value="<?php echo $comp_start_date; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	

							<label class="col-lg-2 control-label">Account Year End<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="comp_end_date" <?php echo $readonly; ?> name="comp_end_date" value="<?php echo $comp_end_date; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>								
								
						</div>
					
<!--						<div class="form-group">
							<label class="col-lg-2 control-label">Travel Request Approval By </label>
							<div class="col-md-3" class="input-append">
								<label >Project Manager </label>
								<select class="form-control" name="project_manager" id="project_manager" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
										?>
									<option value="<?php echo $r2['id']?>" <?php echo ($row['project_manager'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3" class="input-append">
							
								<label >Project Incharge </label>
								<select class="form-control" name="project_incharge" id="project_incharge" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
										?>
									<option value="<?php echo $r2['id']?>" <?php echo ($row['project_incharge'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['username'] ?></option>
									<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3" class="input-append">
							
								<label >COO/CXO </label>
								<select class="form-control" name="coo_cxo" id="coo_cxo" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_user ORDER BY username ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
										?>
									<option value="<?php echo $r2['id']?>" <?php echo ($row['coo_cxo'] == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['username'] ?></option>
									<?php } ?>
								</select>
								
							</div>
						
						</div>
-->
						
					<?php 
						
						$user=$_SESSION['user'];
				
						if ($user=='admin' || $user=='Admin'){
							$logo_file_name = $row['logo_file_name'];
							
					?>
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Attach Company Logo</label>
							<div class="col-md-3" class="input-append">
								<input class="text" name="logo_file_name" type="file" value="<?php echo $row['logo_file_name']; ?>"/>
							</div>	
							<div class="col-md-4" class="input-append">
								<a href="<?php echo $row['logo_dir_name'].$row['logo_file_name']; ?>" target="_blank" ><b><?php echo $row['logo_file_name']; ?> </b></a>
							</div>	
						<?php 
							if(!empty($logo_file_name)){
						?>
							<div class="col-md-3" class="input-append">
								<img src="<?php echo $row['logo_dir_name'].$row['logo_file_name']; ?>" width="50%" height="50%" > </img>
							</div>
						<?php } ?>	
						</div>
						
						
					<!--	<h3 class="heading">Bank Details</h3>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank A/C Title</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_title" autocomplete="off" value="<?php echo $row['bank_ac_title'];?>">
							</div>	
							<label class="col-lg-1 control-label">IFSC Code</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  name="bank_ifsc_code" autocomplete="off" value="<?php echo $row['bank_ifsc_code'];?>">
							</div>	
							
							<label class="col-lg-1 control-label">A/c Type</label>
							<div class="col-md-2" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_type" autocomplete="off" value="<?php echo $row['bank_ac_type'];?>">
							</div>	
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank A/C Number</label>
							<div class="col-md-3" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_number" autocomplete="off" value="<?php echo $row['bank_ac_number'];?>">
							</div>	
							
							<label class="col-lg-1 control-label">Branch </label>
							<div class="col-md-4" class="input-append">
								<input type="text" class="form-control"  name="bank_ac_branch" autocomplete="off" value="<?php echo $row['bank_ac_branch'];?>">
							</div>	
						</div>
					-->
					<?php
						}
					?>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">Slogan</label>
							<div class="col-md-10" class="input-append">
								<input type="text" class="form-control" name="comp_slogan" autocomplete="off" value="<?php echo $row['comp_slogan'];?>">
							</div>	
						</div>	
						
						<div class="form-group">
									<div class="col-md-12">
										<div class="box-header"><span class="box-title">Subject</span></div>
										<div class="box-body">
											<textarea class="form-control" id="reason1" name="header_terms" ><?php echo $row['header_terms'];?></textarea>
										</div>
									</div>
						</div>		
						
						<div class="form-group">
									<div class="col-md-12">
										<div class="box-header"><span class="box-title">Gerneral Terms</span></div>
										<div class="box-body">
											<textarea class="form-control" id="reason" name="general_terms" ><?php echo $row['general_terms'];?></textarea>
										</div>
									</div>
						</div>
						
								
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="company.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $row['comp_id']; ?>
								<a href="<?php echo $baseurl."setting/company.php?comp_id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
        <!--/.content-wrapper -->
    </div>
  </div>
</section>
	
<?php } 	?>

<?php 	
		include("../footer.php");	
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
	
    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

	
</script>

</body>
</html>
