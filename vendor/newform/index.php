<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Registration Form </title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
		
		<!-- STYLE CSS -->
		<link rel="stylesheet" href="css/style.css">
	</head>	
<?php 

$next = $_GET['next'];
include "../../dbcon.php";
include "../../baseurl.php";
if( $next==0 || empty($next) ){
?>
<body>

		<div class="wrapper" style="background-image: url('images/bg-registration-form-3.jpg');">
			<div class="inner">
				<center><img src="../../img/final logo.jpg" width="30%" height="20%" ></img></center>
				<form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" >
					
					<h3>Supplier Registration Form</h3>
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Supplier Name*:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o"></i>
								<input type="text" required class="form-control" id="party_name" name="party_name" value="" >
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">&nbsp;</label>
							<div class="button-holder" style="float:left;">
							<button type="button" ><a href="images/Vendor registration form.pdf" target="_blank" style="color:white;" > Help</a> </button>
							</div>
						</div>
						
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Email*:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;">@</i>
								<input type="email"   class="form-control" id="party_emaill" name="party_email" required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Invalid email address" value="">
							</div>
						</div>
					</div>
					
					<span id ="predit">
						<input type="hidden" class="form-control" id="party_otp" value="">
					</span>
					
					<div class="form-group">
						<div class="checkbox123">
							<label>
								 <?php for($i==0;$i<1;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
						
						<div class="button-holder" style="float:left;">
							<button type="button" id="submitRegister" onclick="checkName_func();" >Submit</button>
							
						</div>
						
						
						<div class="checkbox123">
							<label>
								
								 <?php for($i==0;$i<150;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
							
					</div>
					
					
<?php } ?>
					
<?php					
if( $next!='2' && $next!='3' && $next!='4' && $next!='0' && !empty($next) ){ 
	
	if($_GET['id']){	
		$vendor_id 	= $_GET['id'];
		$sql	= "Select * from sma_party_mst where id = '$vendor_id' ";
		$query 	= mysqli_query($con, $sql);
        $row 	= mysqli_fetch_array($query);	
		$party_name			= $row['party_name'];
		$party_email		= $row['party_email'];
	}

	if($_SESSION['party_name']){
		$party_name 	= $_SESSION['party_name'];
		$party_email 	= $_SESSION['party_email'];
	}
?>
	<body>

		<div class="wrapper" style="background-image: url('images/bg-registration-form-3.jpg');">
			<div class="inner">
				<center><img src="../../img/final logo.jpg" width="30%" height="20%" ></img></center>
				<form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" >
					
					<h3>Supplier Registration Form</h3>
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Type *:</label>
							<div class="form-holder">
								<select class="form-control" name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_party == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>	
						
						<div class="form-wrapper">
							<label for="">Supplier Category *:</label>
							<div class="form-holder">
								<select class="form-control" name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_category == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Supplier Name*:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o"></i>
								<input type="text" required readonly class="form-control" id="party_name" name="party_name" value="<?= $party_name; ?>" >
							</div>
							<span id="suppName_check"></span>
						</div>
						
						<div class="form-wrapper">
							<label for="">Contact Person Name:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" value="<?= $row['party_contact_person_name']; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						
						<div class="form-wrapper">
							<label for="">Designation:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_designation" name="party_designation" value="<?= $row['party_designation']; ?>">
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">Email*:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;">@</i>
								<input type="email" required readonly class="form-control" id="party_email" name="party_email" value="<?= $party_email; ?>">
							</div>
							<span id="Email_check"></span>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Address:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<textarea rows="4" class="form-control" id="party_address_1" name="party_address_1"><?= $row['party_address_1']; ?></textarea>
							</div>
						</div>
						<div class="form-wrapper">
							<label for="">State:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value);" >
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_state'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">City:</label>
							<span id="getcity">
								<select class="form-control" name="party_city" id="party_city" >
									<option value=""> Select </option>
											
								</select>
							</span>
						</div>
						<div class="form-wrapper">
							<label for="">Pincode:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" value="<?= $row['party_pincode']; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Country:</label>
							<div class="form-holder select">
								<select name="" id="" class="form-control" id="party_country" name="party_country" >
									<option value="INDIA" selected >INDIA</option>
									
								</select>
								<i class="zmdi zmdi-pin"></i>
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">Website:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;" class="zmdi zmdi-airplay"></i>
								<input type="text" class="form-control" id="party_websites" name="party_websites" value="<?= $row['party_websites']; ?>">
							</div>
						
						</div>
						
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Mobile 1:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_mobile" name="party_mobile" value="<?= $row['party_mobile']; ?>">
							</div>
						</div>
						<div class="form-wrapper">
							<label for="">Mobile 2:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_mobile1" name="party_mobile1" value="<?= $row['party_mobile1']; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Phone 1:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_phone" name="party_phone"  value="<?= $row['party_phone']; ?>">
							</div>
						</div>
						<div class="form-wrapper">
							<label for="">Phone 2:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" value="<?= $row['party_phone1']; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						
						<div class="form-wrapper">
							<label for="">Tax Category:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="radio" checked id="tax_category" name="tax_category" checked value="R" > Registered  &nbsp;
								
								<input type="radio"  id="tax_category" name="tax_category" value="U" > Unregistered  &nbsp;
								
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">GST Number :</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" onblur="checkGST(this.value);" value="<?= $row['party_gst_number']; ?>">
							</div>
							<span id="checkGST"></span>
							
						</div>
						
					</div>
					
					<div class="form-group">	
						<div class="form-wrapper">
							<label for="">PAN Number:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" value="<?= $row['party_pan_number']; ?>">
							</div>
						</div>
					
						
						<div class="form-wrapper">
							<label for="">MSME Number:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" value="<?= $row['party_msme_number']; ?>">
							</div>
						</div>
					</div>
				
				<span id ="predit" style="color:red;"></span>
				
					<div class="form-group">
						<div class="checkbox123">
							<label>
								<!--<input type="checkbox">-->	
								 <?php for($i==0;$i<50;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
					
						<div class="button-holder">
							<button onclick="backbank_func('3', 0);" >Cancel</button>
						</div>
						
						<div class="button-holder">
							<button type="button" id="submitRegister" onclick="validate_func();" >Register Now</button>
						</div>
						
						<div class="checkbox123">
							<label>
								
								 <?php for($i==0;$i<100;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
							
					</div>
						
				</form>
			</div>
		</div>
<?php  } ?>
		

<?php 
	if($next=='2'){ 
	
		if($_GET['id']){
			$vendor_id 	= $_GET['id'];
			$sql	= "Select * from sma_party_mst where id = '$vendor_id' ";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);	
			$party_name			= $row['party_name'];
		}
?>
	<body>

		<div class="wrapper" style="background-image: url('images/bg-registration-form-3.jpg');">
			<div class="inner">
				<center><img src="../../img/final logo.jpg" width="30%" height="20%" ></img></center>
				
				<form class="form-horizontal" action="#" enctype="multipart/form-data" method="post" >
					
					<h3>Registration Form - Bank Details</h3>
					<div class="form-group">
						<div class="form-wrapper">
							<label for=""><?= $party_name; ?></label>
							<input type="hidden" id="vendor_id" name="vendor_id" value="<?= $vendor_id ?>" >
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Bank Beneficiary Name:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o"></i>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name" value="<?= $row['party_beneficiary_name']; ?>" >
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">Bank Name:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" value="<?= $row['party_bank_name']; ?>">
							</div>
						</div>
					</div>
					
					<?php $row['party_bank_account_type']; ?> 
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Account Type:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="radio" checked id="party_bank_account_type" name="party_bank_account_type" value="Current"> Current
								
								<input type="radio"  id="party_bank_account_type" name="party_bank_account_type" value="Saving"> Saving
								
							</div>
						</div>
						
						<div class="form-wrapper">
							<label for="">Bank Address:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" value="<?= $row['party_bank_address']; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">Account Number:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" value="<?= $row['party_bank_account_no']; ?>">
							</div>
						</div>
						<div class="form-wrapper">
							<label for="">Account IFSC Code:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" value="<?= $row['party_bank_ifsc_code']; ?>">
							</div>
						</div>
					</div>
				
				<span id ="predit" style="color:red;" ></span>
				
					<div class="form-group">
						<div class="checkbox123">
							<label>
								<!--<input type="checkbox">-->	
								 <?php for($i==0;$i<50;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="button-holder">
							<button onclick="backbank_func('1', <?= $vendor_id; ?>);" >Back A</button>
						</div>
						
						<div class="button-holder">
							<button type="button" id="submitRegister" onclick="bankdtl_func();" >Save</button>
						</div>
						
						<div class="checkbox123">
							<label>
								
								 <?php for($i==0;$i<100;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
							
					</div>
						
				</form>
			</div>
		</div>
<?php  } ?>


<?php 

	if($_POST['next_id']){
		$next = $_POST['next_id'];
	}
	if($next=='3'){

		if($_POST['Save']){
			//echo ('HEllo..');
			//exit();
			$vendor_id 		= $_POST['vendor_id'];	
			$gst_file 		= $_FILES['gst_file'];
			$pan_file 		= $_FILES['pan_file'];
			$msme_file 		= $_FILES['msme_file'];
			$cheque_file 	= $_FILES['cheque_file'];
			
				$folder_path = "uploads/vn/" . $vendor_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename 		= $gst_file['name'];
				$tmpFileName 	= $gst_file['tmp_name'];				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . '1' . "', '" . '' . "', " . $vendor_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "../uploads/vn/" . $vendor_id . "/" . $filename);
					}
					else {
						echo "Error in GST Upload :" . mysqli_error($con);
					}
				}
				
				$filename 		= $pan_file['name'];
				$tmpFileName 	= $pan_file['tmp_name'];				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . '5' . "', '" . '' . "', " . $vendor_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "../uploads/vn/" . $vendor_id . "/" . $filename);
					}
					else {
						echo "Error in PAN Upload :" . mysqli_error($con);
					}
				}
				
				$filename 		= $msme_file['name'];
				$tmpFileName 	= $msme_file['tmp_name'];				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . '6' . "', '" . '' . "', " . $vendor_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "../uploads/vn/" . $vendor_id . "/" . $filename);
					}
					else {
						echo "Error in MSME Upload :" . mysqli_error($con);
					}
				}
				
				$filename 		= $cheque_file['name'];
				$tmpFileName 	= $cheque_file['tmp_name'];				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . '12' . "', '" . '' . "', " . $vendor_id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "../uploads/vn/" . $vendor_id . "/" . $filename);
					}
					else {
						echo "Error in CHEQUE Upload :" . mysqli_error($con);
					}
				}

			ECHO "Supplier details successfuly save !!!";
		//'vendor/newform/'.
			$baseurl1 = $baseurl."vendor/newform/index.php?next=4&id=$vendor_id";
	
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
			
		}
			
		$vendor_id 	= $_GET['id'];
		$sql	= "Select * from sma_party_mst where id = '$vendor_id' ";
		$query 	= mysqli_query($con, $sql);
        $row 	= mysqli_fetch_array($query);	
		$party_name			= $row['party_name'];
?>
	<body>

		<div class="wrapper" style="background-image: url('images/bg-registration-form-3.jpg');">
			<div class="inner">
				<center><img src="../../img/final logo.jpg" width="30%" height="20%" ></img></center>
				
				<form class="form-horizontal" action="index.php?next=3" enctype="multipart/form-data" method="post" >
					
					<h3>Registration Form - Upload Documents</h3>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for=""><?= $party_name; ?></label>
							<input type="hidden" id="vendor_id" name="vendor_id" value="<?= $vendor_id ?>" >
							<input type="hidden" id="next_id" name="next_id" value="<?= $next ?>" >
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-wrapper">
							<label for="">GST Certificate*:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o"></i>
								<input type="file" class="form-control" id="gst_file" name="gst_file" >
							</div>
						</div>
					</div>
					
					<div class="form-group">	
						<div class="form-wrapper">
							<label for="">PAN Card Copy*:</label>
							<div class="form-holder">
								<i class="zmdi zmdi-account-o1"></i>
								<input type="file" class="form-control" id="pan_file" name="pan_file" >
							</div>
						</div>
					</div>
					
					<div class="form-group">
						
						<div class="form-wrapper">
							<label for="">MSME Certificate:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="file" class="form-control" id="msme_file" name="msme_file" >
							</div>
						</div>
					</div>
					
					<div class="form-group">	
						<div class="form-wrapper">
							<label for="">Cheque copy:</label>
							<div class="form-holder">
								<i style="font-style: normal; font-size: 15px;"></i>
								<input type="file" class="form-control" id="cheque_file" name="cheque_file" >
							</div>
						</div>
					</div>
					
				<span id ="predit" style="color:red;"></span>
				
					<div class="form-group">
						<div class="checkbox123">
							<label>
								<!--<input type="checkbox">-->	
								 <?php for($i==0;$i<50;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
						
						<div class="button-holder">
							<button type="button" onclick="backbank_func('2', <?= $vendor_id; ?>);" >Back</button>
						</div>
						
						<div class="button-holder">
							<button type="submit" id="submitRegister" name='Save' value='Save' onclick="docdtl_func();" >Save</button>
							
							
						</div>
						
						
						
						<div class="checkbox123">
							<label>
								 <?php for($i==0;$i<100;$i++){echo '&nbsp;'.' ';} ?>
								<span class="checkmark"></span>
							</label>
						</div>
							
					</div>
						
				</form>
			</div>
		</div>
<?php  }

if($next=='4'){
		
		$vendor_id 	= $_GET['id'];
		$sql		= "Select * from sma_party_mst where id = '$vendor_id' ";
		$query 		= mysqli_query($con, $sql);
        $row 		= mysqli_fetch_array($query);	
		$party_name	= $row['party_name'];
		
		$baseurl1 	= $baseurl.'vendor/'."vendor.php?sub=edit&id=$vendor_id";
		
		include("confirm_mail.php");
		//arvind.mishra@athaanginfra.in
?>
	<body>

		<div class="wrapper" style="background-image: url('images/bg-registration-form-3.jpg');">
			<div class="inner">
				<center><img src="../../img/final logo.jpg" width="30%" height="20%" ></img></center>
				<h3>Thank you for registering with us, our team will verify the your information. We will connect to you shortly !!!
					</h3>
				<p><?= $party_name; ?></p>
			</div>
		</div>
<?php  } ?>
		
	</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
		
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  		
<?php 	
	include("../../footer.php");	
?>
		
<script>
	function validate_func(){
//	$("#submitRegister").on("click", function(e){	
//			alert('Hello...');
		
		//var party_name		 		=  document.getElementById("party_name").value;
		var party_name		 		=  $("#party_name").val();
		var party_contact_person_name =  $("#party_contact_person_name").val();
		var party_address_1			=  $("#party_address_1").val();
		var party_city				=  $("#party_city").val();
		var party_state				=  $("#party_state").val();
		var party_pincode			=  $("#party_pincode").val();
		var party_country			=  $("#party_country").val();
		var party_phone				=  $("#party_phone").val();
		var party_phone1			=  $("#party_phone1").val();
		var party_mobile			=  $("#party_mobile").val();
		var party_mobile1			=  $("#party_mobile1").val();
		var party_email				=  $("#party_email").val();
		var party_gst_number		=  $("#party_gst_number").val();
		var party_pan_number		=  $("#party_pan_number").val();
		var party_msme_number		=  $("#party_msme_number").val();
		var party_websites			=  $("#party_websites").val();
		//var tax_category			=  $("#tax_category").val();
		var party_type				=  $("#party_type").val();
		var party_category			=  $("#party_category").val();
		
		var tax_category ='';
		if (document.getElementById('tax_category').checked) {
		    tax_category = document.getElementById('tax_category').value;
		}
		if (document.getElementById('tax_categorya').checked) {
		    tax_category = document.getElementById('tax_categorya').value;
		}
		
		
		//$('#rejectAuthority').modal('hide');
//alert(sub + ' #2 ' + party_name);
//return;		 

		var sub 					= 'sub1';

		var strURL = "newv_func.php";
		$.post(strURL,{ party_name:party_name,
						party_contact_person_name:party_contact_person_name,
						tax_category:tax_category,
						party_type:party_type,
						party_category:party_category,
						party_address_1:party_address_1,
						party_city:party_city,
						party_state:party_state,
						party_pincode:party_pincode,
						party_country:party_country,
						party_phone:party_phone,
						party_phone1:party_phone1,
						party_mobile:party_mobile,
						party_mobile1:party_mobile1,
						party_email:party_email,
						party_gst_number:party_gst_number,
						party_pan_number:party_pan_number,
						party_msme_number:party_msme_number,
						party_websites:party_websites,
						sub1:sub},
						function(result){
		      $('#predit').html(result);
		});
		
};

	function bankdtl_func(){
//	$("#submitRegister").on("click", function(e){	
			
		var sub 					= 'sub2';

		//var party_name		 		=  document.getElementById("party_name").value;
		
		var vendor_id 						=  $("#vendor_id").val();
//alert(vendor_id);
//return;				
		var party_beneficiary_name		 	=  $("#party_beneficiary_name").val();
		var party_bank_name 				=  $("#party_bank_name").val();
		var party_bank_account_type			=  $("#party_bank_account_type").val();
		var party_bank_address				=  $("#party_bank_address").val();
		var party_bank_account_no			=  $("#party_bank_account_no").val();
		var party_bank_ifsc_code			=  $("#party_bank_ifsc_code").val();
			 
		var strURL = "newv_func.php";
		$.post(strURL,{ party_beneficiary_name:party_beneficiary_name,
						vendor_id:vendor_id,
						party_bank_name:party_bank_name,
						party_bank_account_type:party_bank_account_type,
						party_bank_address:party_bank_address,
						party_bank_account_no:party_bank_account_no,
						party_bank_ifsc_code:party_bank_ifsc_code,
						sub2:sub},
						function(result){
		      $('#predit').html(result);
		});
		
};

	function backbank_func(next, vendor_id){
		var sub 					= 'sub3';
		var strURL = "newv_func.php";
		$.post(strURL,{ next:next,
						vendor_id:vendor_id,
						sub3:sub},
						function(result){
		      $('#predit').html(result);
		});
	}
	
	
	function checkName_func(){
		var sub 					= 'sub4';
		var party_name				=  $("#party_name").val();
		var party_email				=  $("#party_emaill").val();
		var party_otp				=  $("#party_otp").val();

		if(party_name=='' ){
			$('#predit').html('Enter Supplier Name !');	
			return false;
		}
		else if(party_email=='' ){
			$('#predit').html('Enter Supplier Email !');	
			return false;
		}
		
		var strURL = "newv_func.php";
		$.post(strURL,{ party_name:party_name,
						party_email:party_email,
						party_otp:party_otp,
						sub4:sub},
						function(result){
		      $('#predit').html(result);
		});
	}
	
	function resend_func(){
		var sub 					= 'sub4';
		var party_name				=  $("#party_name").val();
		var party_email				=  $("#party_emaill").val();
		var party_otp				=  '';

		if(party_name=='' ){
			$('#predit').html('Enter Supplier Name !');	
			return false;
		}
		else if(party_email=='' ){
			$('#predit').html('Enter Supplier Email !');	
			return false;
		}
		 
		var strURL = "newv_func.php";
		$.post(strURL,{ party_name:party_name,
						party_email:party_email,
						party_otp:party_otp,
						sub4:sub},
						function(result){
		      $('#predit').html(result);
		});
	}
	
	function getcity(id){
        var sub    = 'sub1';
		var strURL = "../v_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getcity').html(result);
		});
	}

	function checkGST(gstno){
		 var sub    = 'sub5';
//alert(sub. ' ' .gstno);		 
		var strURL = "newv_func.php";
		$.post(strURL,{gstno:gstno,sub5:sub},function(result){
		      $('#checkGST').html(result);
		});
		
	}	
</script>
