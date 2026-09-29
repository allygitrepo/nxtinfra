<?php 
	session_start();
	include('../../dbcon.php');
	include('../../baseurl.php');

?>

<?php
	
    if(isset($_POST['sub1'])){
    
			$party_type                     = $_POST['party_type'];
			$party_category 				= $_POST['party_category'];
			$tax_category 					= $_POST['tax_category'];		
			$party_name						= $_POST['party_name'];
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
			$party_websites					= $_POST['party_websites'];
		
			if(empty($party_type)){
				echo "Please select party type !";
				exit();
			}
			
			if(empty($party_category)){
				echo "Please select party category !";
				exit();
			}
			
			if($tax_category=='R' && empty($party_gst_number) ){
				echo "Please enter Party GST number !";
				exit();
			}
			
			if($tax_category=='R'){
				$sql	= "Select * from sma_party_mst where party_gst_number = '$party_gst_number' ";
				$res 	= mysqli_query($con, $sql);
				echo  mysqli_error($con);
				$rowaffect 	= mysqli_affected_rows($con);
				while($r2 	= mysqli_fetch_array($res)){
					$vendor_id			= $r2['id'];
					$party_name_v		= $r2['party_name'];
				}
				
				if($rowaffect>0){
					echo "GST number already available for supplier name => ". $party_name_v;
					exit();
				}	
			}
			else if($tax_category!='R'){
				$party_gst_number='';
			}	
			
			if(empty($party_country)){
				$party_country='INDIA';
			}
			//$status = 'Draft';
			$status   = 'Submitted'; 
			
			$sql = "SELECT * from smtp_dtl";
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2  = mysqli_fetch_array($res);
			$vendor_mail_send_to	= $r2['vendor_mail_send_to'];

			$sql = " SELECT * from sma_user where 1 and email = '$vendor_mail_send_to' " ;
			$res = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2  = mysqli_fetch_array($res);
			$current_approver 	= $r2['id'];
	
			$sql	= "Select * from sma_party_mst where trim(party_name) = trim('$party_name') ";
			$query 	= mysqli_query($con, $sql);
			$rowaffect 	= mysqli_affected_rows($con);
			$row 		= mysqli_fetch_array($query);	
			$new_id		= $row['id'];
	
			if($rowaffect==0){
				$sql="insert into sma_party_mst ( party_type, party_name, party_category, party_contact_person_name, party_address_1, party_city, party_state, party_pincode, party_area, party_country, party_phone, party_phone1, party_phone2, party_mobile, party_mobile1, party_mobile2, party_email, party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tax_category,tally_account_name, party_websites, status , current_approver) 
				Values( '$party_type', '$party_name', '$party_category', '$party_contact_person_name', '$party_address_1', '$party_city', '$party_state', '$party_pincode', '$party_area', '$party_country', '$party_phone', '$party_phone1', '$party_phone2', '$party_mobile', '$party_mobile1', '$party_mobile2', '$party_email', '$party_gst_number', '$party_pan_number', '$party_msme_number', '$party_bank_name', '$party_bank_account_type', '$party_bank_address', '$party_bank_account_no', '$party_bank_ifsc_code', '$party_beneficiary_name', '$tax_category', '$tally_account_name', '$party_websites', '$status', '$current_approver' )";
				$query=mysqli_query($con, $sql);
				$new_id=mysqli_insert_id($con);
			}
		//while($row = mysqli_fetch_object($q2)){ 
			
        //echo $sql;
		ECHO "Supplier details successfuly save !!!";
		
			$baseurl1 = $baseurl.'vendor/newform/'."index.php?next=2&id=$new_id";
			echo "<script>window.location.href='$baseurl1';</script>";

    }
	
	if(isset($_POST['sub2'])){
		
		$vendor_id = $_POST['vendor_id'];
		
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_type 				= $_POST['party_bank_type'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			
			$sql="update sma_party_mst set 	
						party_bank_name 			= '$party_bank_name',
						party_bank_account_type 	= '$party_bank_account_type',
						party_bank_address 			= '$party_bank_address',
						party_bank_account_no 		= '$party_bank_account_no',
						party_bank_ifsc_code 		= '$party_bank_ifsc_code',
						party_beneficiary_name 		= '$party_beneficiary_name'
					where id='$vendor_id'";
			$query=mysqli_query($con, $sql);		

		ECHO "Supplier Bank details successfuly save !!!";
		
			$baseurl1 = $baseurl.'vendor/newform/'."index.php?next=3&id=$vendor_id";
			echo "<script>window.location.href='$baseurl1';</script>";

    }			
	
	if(isset($_POST['sub3'])){
		
		$vendor_id = $_POST['vendor_id'];
		$next      = $_POST['next'];
		if($next==3){
			$baseurl1 = $baseurl.'vendor/newform/'."index.php";
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}
		else {
			$baseurl1 = $baseurl.'vendor/newform/'."index.php?next=$next&id=$vendor_id";
			echo "<script>window.location.href='$baseurl1';</script>";
			exit();
		}	
	}	

	if(isset($_POST['sub4'])){
		
		$party_name		= $_POST['party_name'];
		$party_email 	= $_POST['party_email'];
		$party_otp		= $_POST['party_otp'];
			
		$rowaffect 	= 0;	
		if( empty($party_otp)){	
			$sql	= "Select * from sma_party_mst where trim(party_name) = trim('$party_name') OR trim(party_email) = trim('$party_email') ";
			$query 	= mysqli_query($con, $sql);
			$rowaffect 	= mysqli_affected_rows($con);
			$row 		= mysqli_fetch_array($query);	
			$vendor_id		= $row['id'];
		}
		
		if($rowaffect>0  ){
			echo "<span style='font-style: normal; font-size: 15px;' >We already have the supplier with same name/email id in our database. Please login to our supplier portal using the link below </span>". "<BR>";
			echo "<span style='font-style: normal; font-size: 15px;' >
			<a href='https://athaang.in/athaangSI'>https://athaang.in/athaangSI</a></span>";
		}
		else if(empty($party_otp)){
			
			include("otp_mail.php");
			
			echo "<span style='font-style: normal; font-size: 15px;' >Thank you for providing above information, we have sent OTP to above email id, Kindly enter below and proceed </span>". "<BR><BR>";
		?>	
			<div class="form-group">
				<div class="form-wrapper">
				<label for="">Enter OTP:</label>
					<div class="form-holder">
					<i style="font-style: normal; font-size: 15px;"></i>
					<input type="text" class="form-control" id="party_otp" name="party_otp" >
					</div>
				</div>
			
				<div class="button-holder" >
					<label for="">&nbsp;<br></label>
					<div class="form-holder" style="float:left;padding-top: 3%;">
					<button type="button" id="submitRegister"  onclick="resend_func();" >Rsend</button>
					</div>
				</div>
			
			</div>
			
		<?php			
			echo "";	
		}
		else if(!empty($party_otp)){
			//echo " Hello...";
			$_SESSION['party_name']		= 	$party_name;
			$_SESSION['party_email']	= 	$party_email;
			
			if( $party_otp == $_SESSION['otp_six_digit'] ){	
				$_SESSION['otp_six_digit']='';	
				$baseurl1 = $baseurl.'vendor/newform/'."index.php?next=1";
				echo "<script>window.location.href='$baseurl1';</script>";
				exit();	
			}
			else {
?>
				<div class="form-group">
					<div class="form-wrapper">
					<label for="">Enter OTP:</label>
						<div class="form-holder">
						<i style="font-style: normal; font-size: 15px;"></i>
						<input type="text" class="form-control" id="party_otp" name="party_otp" >
						</div>
					</div>
				
					<div class="button-holder" >
						<label for="">&nbsp;<br></label>
						<div class="form-holder" style="float:left;padding-top: 3%;">
						<button type="button" id="submitRegister"  onclick="resend_func();" >Rsend</button>
						</div>
					</div>
				</div>
<?php				
				
				echo "Invalid OTP entered !";
			}	
			
		}		
		
		exit();	
	}			
	
	if(isset($_POST['sub5'])){
		
		$gstno = $_POST['gstno'];
		$sql	= "Select * from sma_party_mst where party_gst_number = '$gstno' ";
			$query 	= mysqli_query($con, $sql);
			echo  mysqli_error($con);
			$rowaffect 	= mysqli_affected_rows($con);
			$row 		= mysqli_fetch_array($query);	
			$vendor_id		= $row['id'];
			$party_name		= $row['party_name'];
			if($rowaffect>0){
				echo "GST number already available for subbplier name => ". $party_name;
			}	
		
	}		