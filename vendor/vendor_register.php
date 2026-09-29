<?php

include("../header_v.php");
$modulePath = "vendor/vendor_register.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'add'){
?>

<?php

	if(isset($_POST['Save'])){
			$party_type                     = $_POST['party_type'];
			$party_name						= $_POST['party_name'];
			$party_category 				= $_POST['party_category'];
			$party_contact_person_name 		= $_POST['party_contact_person_name'];
			$party_address_1 				= $_POST['party_address_1'];
			$party_city 					= $_POST['party_city'];
			$party_state 					= $_POST['party_state'];
			$party_pincode 					= $_POST['party_pincode'];
			$party_area 					= $_POST['party_area'];
			$party_country 					= $_POST['party_country'];
			$party_phone 					= $_POST['party_phone'];
			$party_phone1 					= $_POST['party_phone1'];
			$party_phone2 					= $_POST['party_phone2'];
			$party_mobile 					= $_POST['party_mobile'];
			$party_mobile1 					= $_POST['party_mobile1'];
			$party_mobile2 					= $_POST['party_mobile2'];			
			$party_email 					= $_POST['party_email'];
			$party_gst_number 				= $_POST['party_gst_number'];
			$party_pan_number 				= $_POST['party_pan_number'];
			$party_msme_number				= $_POST['party_msme_number'];
			
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			
			$tax_category = $_POST['tax_category'];
			$tally_account_name = $_POST['tally_account_name'];
			
			$status = 'Draft';
			
  			$sql="insert into sma_party_mst ( party_type, party_name, party_category, party_contact_person_name, party_address_1, party_city, party_state, party_pincode, party_area, party_country, party_phone, party_phone1, party_phone2, party_mobile, party_mobile1, party_mobile2, party_email, party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tax_category,tally_account_name, status ) 
			Values( '$party_type', '$party_name', '$party_category', '$party_contact_person_name', '$party_address_1', '$party_city', '$party_state', '$party_pincode', '$party_area', '$party_country', '$party_phone', '$party_phone1', '$party_phone2', '$party_mobile', '$party_mobile1', '$party_mobile2', '$party_email', '$party_gst_number', '$party_pan_number', '$party_msme_number', '$party_bank_name', '$party_bank_account_type', '$party_bank_address', '$party_bank_account_no', '$party_bank_ifsc_code', '$party_beneficiary_name', '$tax_category', '$tally_account_name', '$status' )";

			$query=mysqli_query($con, $sql);
			$party_id= mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
				$userid   	    = $_SESSION['usrid'];
				$sql  = "insert into kyc_upd_log (create_by, created_on, party_id, party_kyc, status) values ( '$userid' , now(), '$party_id', '$party_kyc', 'Draft' )";
				$query= mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			echo "Supplier successful added";
			echo '<script>window.location.href="vendor_register.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Supplier Registration
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="#">Supplier Registration</a></li>
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
            <form class="form-horizontal" action="vendor_register.php?sub=add" enctype="multipart/form-data" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                	  
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Contact Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab">Bank Details</a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<div class="col-md-3">
							<label class=" control-label">Type</label>
								
								<select class="form-control" name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_party == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
										<?php } ?>
								</select>
							</div>

							<div class="col-md-3">
							<label class=" control-label">Supplier Category ** </label>
								<select class="form-control" name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_category == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" placeholder="" value="<?php echo $row['party_name'];?>" required >
							</div>
			
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class=" control-label">Tax Category</label><br>
								<input type="radio" id="tax_category" name="tax_category" checked value="R" > Registered  &nbsp;
								<input type="radio" id="tax_category" name="tax_category" value="U" > Unregistered  &nbsp;
							</div>
						
							<div class="col-md-6">
								<label class="control-label">Tally Account Name</label>
								<input type="text" class="form-control" id="tally_account_name" name="tally_account_name" placeholder="" value="<?php echo $row['tally_account_name'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class=" control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" placeholder="" value="<?php echo $row['party_contact_person_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Designation</label>
								<input type="text" class="form-control" id="party_designation" name="party_designation" placeholder="" value="" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Email</label>
								<input type="text" class="form-control" id="party_email" name="party_email" placeholder="" value="<?php echo $row['party_email'];?>" >
							</div>
							
						</div>
							
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Address </label>
								<textarea rows="2" cols="80" class="form-control" id="party_address_1" name="party_address_1" placeholder="" ><?php echo $row['party_address_1'];?> </textarea>
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Mobile-1</label>
								<input type="text" class="form-control" id="party_mobile" name="party_mobile" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-2</label>
								<input type="text" class="form-control" id="party_mobile1" name="party_mobile1" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-3</label>
								<input type="text" class="form-control" id="party_mobile2" name="party_mobile2" placeholder="" value="" >
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">State</label>
								<select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_state'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">City</label>
								<span id="getcity">
									<select class="form-control" name="party_city" id="party_city" >
										<option value=""> Select </option>
											<?php $sql = "select * from cities order by city_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_city'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
											<?php } ?>
									</select>
								</span>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Pincode</label>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" placeholder="" value="<?php echo $row['party_pincode'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone</label>
								<input type="text" class="form-control" id="party_phone" name="party_phone" placeholder="" value="" >
							</div>
							
							
							<div class="col-md-2">
								<label class="control-label">Phone-2</label>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-3</label>
								<input type="text" class="form-control" id="party_phone2" name="party_phone2" placeholder="" value="" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Area</label>
								<input type="text" class="form-control" id="party_area" name="party_area" placeholder="" value="<?php echo $row['party_area'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Country</label>
								<input type="text" class="form-control" id="party_country" name="party_country" placeholder="" value="<?php echo $row['party_country'];?>" >
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Website</label>
								<input type="text" class="form-control" id="party_websites" name="party_websites" placeholder="" value="<?php echo $row['party_websites'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-3">
								<label class=" control-label">GST Number</label>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" placeholder="" value="<?php echo $row['party_gst_number'];?>" >
							</div>
							
							<div class="col-md-3">
								<label class=" control-label">PAN Number</label>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" placeholder="" value="<?php echo $row['party_pan_number'];?>" >
							</div>
			
							<div class="col-md-3">
								<label class=" control-label">MSME Number</label>
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder="" value="<?php echo $row['party_msme_number'];?>" >
							</div>
						
						</div>
						
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-5">
							
								<label class="control-label">Beneficiary Name</label>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name"  value="" >
							
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" placeholder="" value="<?php echo $row['party_bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="party_bank_account_type" id="party_bank_account_type" >
									<option value=""> Select </option>
									<option value="Saving"> Saving</option>
									<option value="Current"> Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" placeholder="" value="<?php echo $row['party_bank_address'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" placeholder="" value="<?php echo $row['party_bank_account_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" placeholder="" value="<?php echo $row['party_bank_ifsc_code'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
				</div>	
                
					<!--	<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="vendor_register.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
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
			$party_name						= $_POST['party_name'];
			$party_category 				= $_POST['party_category'];
			$party_contact_person_name 		= $_POST['party_contact_person_name'];
			$party_address_1 				= $_POST['party_address_1'];
//			$party_address_2 				= $_POST['party_address_2'];
//			$party_address_3 				= $_POST['party_address_3'];
			$party_city 					= $_POST['party_city'];
			$party_state 					= $_POST['party_state'];
			$party_pincode 					= $_POST['party_pincode'];
			$party_area 					= $_POST['party_area'];
			$party_country 					= $_POST['party_country'];
			$party_phone 					= $_POST['party_phone'];
			$party_phone1 					= $_POST['party_phone1'];
			$party_phone2 					= $_POST['party_phone2'];
			$party_mobile 					= $_POST['party_mobile'];
			$party_mobile1 					= $_POST['party_mobile1'];
			$party_mobile2 					= $_POST['party_mobile2'];			
			$party_email 					= $_POST['party_email'];
			$party_websites					= $_POST['party_websites'];
			$party_gst_number 				= $_POST['party_gst_number'];
			$party_pan_number 				= $_POST['party_pan_number'];
			$party_msme_number				= $_POST['party_msme_number'];
			
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			$party_kyc						= $_POST['party_kyc'];
			$tax_category 					= $_POST['tax_category'];
			$tally_account_name 			= $_POST['tally_account_name'];
			$party_type                     = $_POST['party_type'];
			
			$approver_1			= $_POST['approver_1'];
			
  			$sql="update sma_party_mst set 	party_type ='$party_type',
						party_name ='$party_name',
						party_category 				= '$party_category',
						party_contact_person_name 	= '$party_contact_person_name',
						party_address_1 			= '$party_address_1',
						party_city 					= '$party_city',
						party_state 				= '$party_state',
						party_pincode 				= '$party_pincode',
						party_area 					= '$party_area',
						party_country 				= '$party_country',
						party_phone 				= '$party_phone',
						party_mobile 				= '$party_mobile',
						party_phone1 				= '$party_phone1',
						party_phone2 				= '$party_phone2',
						party_mobile1 				= '$party_mobile1',
						party_mobile2 				= '$party_mobile2',			
						party_email 				= '$party_email',
						party_websites				= '$party_websites',
						party_gst_number 			= '$party_gst_number',
						party_pan_number 			= '$party_pan_number',
						party_msme_number			= '$party_msme_number',
						party_bank_name 			= '$party_bank_name',
						party_bank_account_type 	= '$party_bank_account_type',
						party_bank_address 			= '$party_bank_address',
						party_bank_account_no 		= '$party_bank_account_no',
						party_bank_ifsc_code 		= '$party_bank_ifsc_code',
						party_beneficiary_name 		= '$party_beneficiary_name',
						tax_category 				= '$tax_category',
						tally_account_name          = '$tally_account_name',
						party_kyc					= '$party_kyc'
					where id='$id'";
//echo $sql;
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$party_kyc_prev = $_POST['party_kyc_prev'];
			if($party_kyc != $party_kyc_prev){
				$userid   	    = $_SESSION['usrid'];
				$sql  = "insert into kyc_upd_log ( user_id, updated_on, party_id, party_kyc ) values ( '$userid' , now(), '$id', '$party_kyc' )";
				$query= mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/vn/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/vn/" . $id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

			if( !empty($approver_1) && ( $status == 'Draft' || empty($status) ) ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_party_mst set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into kyc_upd_log (party_id, user_id, created_on, create_by,  updated_on, party_kyc, status ) 
				values('$id', '$userid', now(), '$approver_1', now(), '$party_kyc', 'Submitted')";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$modulePath = "vendor/"; 
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'vendor_register.php?id='.$id;
		
				$msg = 'Vendor NAme : '.$vendor_name . ' ' . 'Dated : ' . date("d-m-Y");

				include "vi_mail.php";
				
			}
			
			$page					= $_POST['page'];
			
//exit();			
			echo "<script>window.location.href='vendor_register.php?sub=list&same_page=$page';</script>";
			
			exit();
			
		}
		
		$page = $_GET['page'];
		$id = $_GET['id'];
		$vendor_id = $_GET['id'];
		$sql="Select * from sma_party_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$kyc 				= $row['party_kyc'];
		$approver_1			= $row['approver_1'];
		$approver_1_status	= $row['approver_1_status'];
		$readonly 			= '';
		//echo $user. ' <<>>>';
		
		$user_category = $_SESSION['user_category'];
		$role = $_SESSION['role'];
		
		//($role = 'Accountant' && $user_category=='H')
?>

    <section class="content-header">
        <h1>
            Supplier
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Supplier</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		    <!-- form start -->
            <form class="form-horizontal" action="vendor_register.php?sub=edit" enctype="multipart/form-data" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="page" value="<?= $page;?>">
					  
					  <input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
					  
					  
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Contact Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab" id="second_tab" >Bank Details</a></li>
                  <li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
				   <li><a href="#tab_4" data-toggle="tab" id="fourth_tab" >KYC Log</a></li>
				  
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<div class="col-md-3">
							<label class=" control-label">Type</label>
								
								<select class="form-control" name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_type'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3">
							<label class=" control-label">Supplier Category **</label>
								<select class="form-control" name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_category'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" <?php echo $readonly ?> value="<?php echo $row['party_name'];?>" >
							</div>
			
						</div>
						
						<?php $tax_category = $row['tax_category']; ?>
							
							<div class="form-group">
								
								<div class="col-md-3">
									<label class=" control-label">Tax Category</label><br>
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='R')?"CHECKED":''; ?> value="R" > Registered &nbsp;
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='U')?"CHECKED":''; ?> value="U" > Unregistered &nbsp;
								</div>
							
								<div class="col-md-6">
									<label class="control-label">Tally Account Name</label>
									<input type="text" class="form-control" id="tally_account_name" name="tally_account_name" placeholder="" value="<?php echo $row['tally_account_name'];?>" >
								</div>
							
							</div>
							
							
						<div class="form-group">
							<div class="col-md-4">
								<label class=" control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" <?php echo $readonly ?> value="<?php echo $row['party_contact_person_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Designation</label>
								<input type="text" class="form-control" id="party_designation" name="party_designation" <?php echo $readonly ?> value="<?php echo $row['party_designation'];?>" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Email</label>
								<input type="text" class="form-control" id="party_email" name="party_email" <?php echo $readonly ?> value="<?php echo $row['party_email'];?>" >
							</div>
							
						</div>
							
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Address </label>
								<textarea rows="2" cols="80" class="form-control" id="party_address_1" name="party_address_1" <?php echo $readonly ?> ><?php echo $row['party_address_1'];?> </textarea>
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Mobile-1</label>
								<input type="text" class="form-control" id="party_mobile" name="party_mobile" <?php echo $readonly ?> value="<?php echo $row['party_mobile'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-2</label>
								<input type="text" class="form-control" id="party_mobile1" name="party_mobile1" <?php echo $readonly ?> value="<?php echo $row['party_mobile1'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-3</label>
								<input type="text" class="form-control" id="party_mobile2" name="party_mobile2" <?php echo $readonly ?> value="<?php echo $row['party_mobile2'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">State</label>
								<select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value)" <?php echo $readonly ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_state'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">City</label>
								<span id="getcity">
									<select class="form-control" name="party_city" id="party_city" <?php echo $readonly ?>>
										<option value=""> Select </option>
											<?php $sql = "select * from cities order by city_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_city'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
											<?php } ?>
									</select>
								</span>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Pincode</label>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" <?php echo $readonly ?> value="<?php echo $row['party_pincode'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-1</label>
								<input type="text" class="form-control" id="party_phone" name="party_phone" <?php echo $readonly ?> value="<?php echo $row['party_phone'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-2</label>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" <?php echo $readonly ?> value="<?php echo $row['party_phone1'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-3</label>
								<input type="text" class="form-control" id="party_phone2" name="party_phone2" <?php echo $readonly ?> value="<?php echo $row['party_phone2'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Area</label>
								<input type="text" class="form-control" id="party_area" name="party_area" <?php echo $readonly ?> value="<?php echo $row['party_area'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Country</label>
								<input type="text" class="form-control" id="party_country" name="party_country" <?php echo $readonly ?> value="<?php echo $row['party_country'];?>" >
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Website</label>
								<input type="text" class="form-control" id="party_websites" name="party_websites" <?php echo $readonly ?> value="<?php echo $row['party_websites'];?>" >
							</div>
						</div>
						<?php
							$gsterr = '';
							$party_gst_number = $row['party_gst_number'];
							if(strlen($party_gst_number)<15){
								$gsterr = 'GST length should be 15 character !!!';	
							}	
							
						?>
						<div class="form-group">
						
							<div class="col-md-3">
								<label class=" control-label">GST Number</label>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" <?php echo $readonly ?> value="<?php echo $row['party_gst_number'];?>" >
							<?php if(!empty($gsterr)){ ?>	
								<label class=" control-label" style='color:red;'><?= $gsterr;?></label>
							<?php } ?>	
							</div>
							
							<div class="col-md-3">
								<label class=" control-label">PAN Number</label>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" <?php echo $readonly ?> value="<?php echo $row['party_pan_number'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class=" control-label">MSME Number</label>
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder="" value="<?php echo $row['party_msme_number'];?>" >
							</div>
							
							<?php 
							$kyc = $row['party_kyc'];
						//echo $kyc. ' ';	
							if($kyc =='Y' and $approver_1_status=='Approved'){
							?>
								<div class="col-md-2">
									<label class=" btn-info" ><h3>&nbsp; KYC Verified &nbsp;</h3> </label>
									
								</div>
							<?php
							}
							if($user=='Admin' || $accountant_role=='Y' || empty($kyc) || $kyc =='N'){
							?>
							<div class="col-md-2">
								<input type="hidden" name="party_kyc_prev" value="<?php echo $kyc; ?>" >
								
								<label class=" control-label">KYC</label><BR>
								<input type="radio" id="party_kyc" name="party_kyc" <?php echo ($kyc=='Y')?"CHECKED":''; ?> <?php echo $readonly ?> value="Y" > Yes &nbsp;
								<input type="radio" id="party_kyc" name="party_kyc" <?php echo ($kyc=='N')?"CHECKED":''; ?> <?php echo $readonly ?> value="N" > No &nbsp;
							</div>
							<?php } ?>
							
							
						</div>
													
						
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-5">
								<label class="control-label">Beneficiary Name</label>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name" <?php echo $readonly ?> value="<?php echo $row['party_beneficiary_name'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" <?php echo $readonly ?> value="<?php echo $row['party_bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="party_bank_account_type" id="party_bank_account_type" <?php echo $readonly ?> >
									<option value=""> Select </option>
									<option value="Saving" <?php echo ($row['party_bank_account_type'] == 'Saving')?'selected="selected"':'';?> > Saving</option>
									<option value="Current" <?php echo ($row['party_bank_account_type'] == 'Current')?'selected="selected"':'';?> > Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" <?php echo $readonly ?> value="<?php echo $row['party_bank_address'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" <?php echo $readonly ?> value="<?php echo $row['party_bank_account_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" <?php echo $readonly ?> value="<?php echo $row['party_bank_ifsc_code'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
					
						<div class="tab-pane <?php echo $active;?>" id="tab_3">
						
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'VN' AND reference_id = " . $id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										<?php if(empty($readonly)){ ?>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										<?php } ?>
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						</div>
					
					<div class="tab-pane <?php echo $active;?>" id="tab_4">
							<?php
                              $sql = "SELECT * FROM kyc_upd_log WHERE party_id ='$id' " ;
                              $ky = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Created On</th>
                                          <th>Created By</th>
										  <th>Updated On</th>
                                          <th>Changed By</th>
                                          <th>KYC </th>
										  <th>Remarks </th>
										  <th>Decision </th>
                                
                                      </tr>
                                      </thead>
                                      <tbody id="partyKYC">
				                              <?php
				                              echo mysqli_error($con);
				                              while($kyrow = mysqli_fetch_array($ky)) {
													
													$uid = $kyrow['user_id'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$username = $rw['username'];
													
													$uid = $kyrow['create_by'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$created_by = $rw['username'];
													
													$created_on = date('d-m-Y', strtotime($kyrow['created_on']));
													$updated_on = date('d-m-Y', strtotime($kyrow['updated_on']));
													
													if($created_on == '01-01-1970'){
														$created_on = '';
													}
													else {
														$created_on = date('d-m-Y h:i:s', strtotime($kyrow['created_on'])); 
													}
													
													if($updated_on == '01-01-1970'){
														$updated_on = '';
													}
													else {
														$updated_on = date('d-m-Y h:i:s', strtotime($kyrow['updated_on']));
													}
													
												?>
                                          <tr>
                                              <td width="10%"><?php echo $created_on ?></td>
											  <td width="15%"><?php echo $created_by ?></td>
											  <td width="10%"><?php echo $updated_on ?></td>
                                              <td width="15%"><?php echo $username; ?></td>
                                              <td width="5%"><?php echo $kyrow['party_kyc']; ?></td>
											  <td width="20%"><?php echo $kyrow['remarks']; ?></td>
											  <td width="10%"><?php echo $kyrow['status']; ?></td>
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						</div>	


				</div>	
  
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
			
						<!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="vendor_register.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

						<div class="box-footer">
							<div class="col-sm-6">
							<?php $did = $_GET['id'];
							//Mrunmayee Started
							$sq2 = "SELECT COUNT(*) as total FROM `sma_purchase_order` where to_supplier = '$did'";
							$q2  = mysqli_query($con, $sq2);
							$r2  = mysqli_fetch_assoc($q2);
							$mycount = $r2['total'];
							
							$sq3 = "SELECT COUNT(*) as total FROM `sma_supplier_invoice` where 	suplier_name = '$did'";
                			$q3  = mysqli_query($con, $sq3);
                			$r3  = mysqli_fetch_assoc($q3);
                			$mycount1 = $r3['total'];
                			if ($mycount <= 0 and $mycount1 <= 0) {
								if($kyc!='Y'){ 
							?>	
								<a href="<?php echo $baseurl."vendor/vendor_register.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
						    <?php } }//Mrunmayee ended?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							
						<?php	
						//echo $approver_1. "<<>>" . $approver_1_status;
						if($kyc !='Y' && empty($approver_1) ){ ?>		
								<span class='hidesend' >	
									<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
						<?php } ?>			
								<input class="btn btn-primary" type="submit" value="Save" name="Save" >&nbsp;&nbsp;&nbsp;
					<?php			
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag='';
									if( $usrid == $approver_1 && $approver_1_status=='Submitted'  ){
										$approver_flag='Y';
									}
//ECHO $usrid.  ' <<>> ' .$approver_1 . ' <<>> ' . $status. ' <2> '. $approver_1_status. ' << 22 >>' .$approver_flag."<BR>";
							?>
								
								
							<?php	
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
								<span class="hidden-reject_div">
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									</span>
									
							<?php }
							
								}
							?>	
							</div>
						</div>

				<span id="predit"></span>
						
                        <span id="getapprover">
							<div class="box-footer">
								<?php  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
						}
						
							if(!empty($approver_1)){
						?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <?php
										$sql = " select * from sma_user where 1 and id = $approver_1 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php } ?>
							</div>
						</span>
						
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


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   

										?>
										
										<input type="hidden" name="vn_id" id="vn_idE" value="<?php echo $vendor_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										
										<input type="hidden" id="modeC" name="mode" value='Approve' >
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  
	  

<!--Rejected Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Reject...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   

										?>
										
										<input type="hidden" name="vn_id" id="vn_idE" value="<?php echo $vendor_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										
										<input type="hidden" id="modeR" name="mode" value='Reject' >
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Rejected Workflow Popup End -->	  
	  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->

 
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


function validate(){
	
	var party_gst_number    = document.getElementById("party_gst_number").value;
alert(party_gst_number);
	return;
	
}	

function getcity(id){
        var sub    = 'sub1';
		var strURL = "v_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getcity').html(result);
		});
}

function getapprover(){
		
		//var company_id    	= document.getElementById("projecT").value;
		var company_id    	= 4;
		//var checker_value   = document.getElementById("checker_value").value;
		//var trans_type    	= 'VN';
		//var po_type  	   	= document.getElementById("po_typea").value;

		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + po_type + ' ' + trans_type + ' ' + company_id + ' ' + checker_value);	
		
		var strURL = "v_func.php";
		$.post(strURL,{company_id:company_id,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}


   $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeC").val();
		
		var vn_id		 	=  $("#vn_idE").val();
		var remarks			=  $("#remarksE").val();
		
//alert( sub + ' ' +  vn_id );
		 
			$('#approvalAuthority').modal('hide');
		 
		var strURL = "v_func.php";
		$.post(strURL,{ vn_id:vn_id,
						remarks:remarks,
						mode:mode,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


$("#submitReject").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
		var vn_id		 	=  $("#vn_idE").val();
		var remarks			=  $("#remarksE").val();
			 
		$('#rejectAuthority').modal('hide');
		 
		var strURL = "v_func.php";
		$.post(strURL,{ vn_id:vn_id,
						remarks:remarks,
						mode:mode,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
</script>

</body>
</html>
