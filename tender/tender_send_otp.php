<?php
	include("../header.php");
	
	date_default_timezone_set('Asia/Kolkata');
	
	$modulePath = "tender/"; 

if($_GET['sub']=='send') {	

		$tender_id = $_GET['tender_id'];
		
?>
<!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $baseurl . 'plugins/select2/select2.min.css'; ?> ">
  
	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Tender
            <small>Send OTP</small>
		<!--<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender</a></li>
            <li class="active">Send OTP</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" method="post" 
										action="tender_send_otp.php?sub=otpmail">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
	
						<label class="control-label">Quotation Received from Vendor</label>
						
				<?php 
					
					$sql 	= " SELECT * FROM `sma_tender_header` WHERE id = '$tender_id' ";
//echo $sql."<BR>";					
					$q2 	= mysqli_query($con, $sql);	
					$r2 	= mysqli_fetch_array($q2);
					$company_id = $r2['company_id'];
						
					$sql = " SELECT b.* FROM `sma_tender_supplier` a,  sma_party_mst b where a.tender_hdr_id = '$tender_id' and b.id = a.supplier_id and a.quotation_received = 'Y' ";
//echo $sql."<BR>";
					$q2 	= mysqli_query($con, $sql);	
					$ii =0 ;
					while($r2 = mysqli_fetch_array($q2)){ 
						$party_name = $r2['party_name'];
						$ii = $ii +1;
				?>		
						<div class="form-group">	
						<div class="col-md-5">
							<label class="control-label"><?= $ii. '. ' . $party_name; ?></label>
						</div>
						</div>
						
				<?php					
					}
					
					$sql = " SELECT supplier_id, tender_hdr_id, 
							round(sum((quantity * rate) + ((quantity * rate) * gst) / 100),2) as tender_total
								FROM `sma_tender_supplier_quote` a
									WHERE tender_hdr_id = '$tender_id' 
									GROUP BY supplier_id ORDER BY tender_total desc ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$tender_total = $r2['tender_total'];
//echo $sql."<BR>";					
				?>
				
						
						<div class="form-group">
							
							<div class="col-md-3">
								<input type="hidden" name="tender_id" value="<?= $_GET['tender_id'];?>">
							</div>
							
							<label for="project" class="control-label">OTP Send to below User for Tender Opening <span style="color:red;"> </span></label>
							
							<div class="col-md-12">
							<?php 
							
							$sql = " SELECT * FROM sma_workflow WHERE company_id ='$company_id' 
										AND doc_type = 'TO' 
										AND $tender_total >= from_Value 
										AND $tender_total <= to_value ";
	//echo $sql."<BR>";
								$q2 	= mysqli_query($con, $sql);	
								$r2 = mysqli_fetch_array($q2);
								$approval_role_1 = $r2['approval_role_1'];
								$approval_role_2 = $r2['approval_role_2'];
								$approval_role_3 = $r2['approval_role_3'];
								$approval_role_4 = $r2['approval_role_4'];
								$approval_role_5 = $r2['approval_role_5'];
								$approval_role_6 = $r2['approval_role_6'];
								$approval_role_7 = $r2['approval_role_7'];
								$approval_role_8 = $r2['approval_role_8'];
									
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_1, role) AND FIND_IN_SET($company_id, company_id) and active = '1' " ;
								$q22 	= mysqli_query($con, $sql);	
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
							?>
							<div class="col-sm-3 ">
								<label class="control-label">Approver 1</label>
								<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                </select>
							</div>			
						<?php  }
								
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_2, role) AND FIND_IN_SET($company_id, company_id) AND active = '1' AND $approval_role_2 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 2</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
									<?php } ?>	
                            </select>
						</div>	
						<?php  }
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_3, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_3 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 3</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php 	 }	
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_4, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_4 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 4</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php  }		
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_5, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_5 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 5</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php }
						
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_6, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_6 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 6</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php  }		
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_7, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_7 >0 " ;
								$q22 	= mysqli_query($con, $sql);
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 7</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php }	
								$sql = "select * from sma_user where 1 AND FIND_IN_SET($approval_role_8, role) AND FIND_IN_SET($company_id, company_id)  and active = '1' AND $approval_role_8 >0 " ;
								$q22 	= mysqli_query($con, $sql);	
								$selected1 	= mysqli_affected_rows($con);	
							if($selected1>0){	
						?>		
						<div class="col-sm-3 ">
							<label class="control-label">Approver 8</label>
							<select class="form-control  " name="otp_user[]" required <?= $required1; ?> >
                                    <?php if($selected1>1){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										while( $rw = mysqli_fetch_array($q22) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                            </select>
						</div>	
						<?php 	
							}	
						?>
							
								
						</div>
					</div>
									
						</div>
						
						<div class="box-footer">
									
							<div class="col-sm-3 text-right">
										<span>&nbsp;&nbsp;</span>
										
							</div>
							<div class="col-sm-4 text-right">
										
								<a class="btn btn-default" href="<?= $baseurl . 'tender/' ?>">Cancel</a>
								
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Submit" name="otpmail">
										
							</div>
							
						</div>	
								
					</fieldset>
					</div>
					
			</form>					
		</div>
	</div>
</div>
</div>	
							
<?php	
	
?>
	

<?php
	include("../footer.php");

}


if($_GET['sub']=='otpmail'){ 
		
	/**
	 * This example shows sending a message using a local sendmail binary.
	 */

    session_start();
	
	$_POST['otpmail'] = '';
	$user   		= $_SESSION['user'];
	$userid   		= $_SESSION['usrid'];
	
	$otp_user		= $_POST['otp_user'];
	$tender_id		= $_POST['tender_id'];

    $tmp_var = sizeof($otp_user);
	
	//$mail_to = 'ravindra.gandhile@gmail.com';

	//require '../PHPMailerAutoload.php';
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
	for($ij = 0; $ij < sizeof($otp_user); $ij++){
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		// optional
		// used only when SMTP requires authentication  
		
		$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
		$mail->SMTPAutoTLS = false;
		
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		
		include("../dbcon.php");
		$sql="SELECT * from smtp_dtl";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$row = mysqli_fetch_array($result);
		$host 			=	$row['host'];
		$host_name		=	$row['host_name'];
		$host_username 	=	$row['username'];
		$host_password  =	$row['password'];
		$host_port 		=	$row['port'];
		$disclaimer 		=	$row['disclaimer'];

		$mail->Host 		= $host;
		$mail->SMTPAuth     = true;
		$mail->Username 	= $host_username;
		$mail->Password 	= $host_password;
		$mail->Port       	= $host_port;               // set the SMTP port
		
		$mail->setFrom($host_username, $host_name);

		//Set who the message is to be sent to
		//$mail->addBCC($mail_to, 'First Gmail');
			
		$sql = " select * from sma_tender_header where id = '$tender_id' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$tender_title 			= $r2['tender_title'];
				
		//Set the subject line
		$mail->Subject = 'OTP for Open the Tender number '. $tender_id  ;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.php'), dirname(__FILE__));
			
		$userrid = $otp_user[$ij];
		
			$sql = " select * from sma_user where id = '$userrid' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$from_by 			= $r2['userid'];
			$user_name 			= $r2['username'];
			$sent_otp_id 		= $r2['id'];
			$user_email 		= $r2['email'];
			$from_mobile 		= $r2['mobile_no'];
			$from_company_work	= $r2['company_work'];
		
		$mail->addReplyTo($user_email, $user_name);
	
		$mail->addAddress($user_email, $user_name);
	
		//$mail->addBCC('ranganathan.n@athaanginfra.in', 'Ranganathan N');
	
		//$mail->addAddress($mail_to, 'First Gmail');
	
		$message = "Status : " . $status;
		$body .= 'Hi '. $user_name. "<br><br>";
		$body .= "Please find here OTP for Tender number ". $tender_id ." to Open". "<br>" . "Today Date: ". date('d-m-Y') . "<br><br>";
		$body .= " Tender Title : ". $tender_title."<br><br>";
		
			$six_digit_random_number = mt_rand(100000, 999999);
			$tdate = date("Y-m-d");
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$one_time = '';
					
			$sql  = "insert into user_login(`userid`, `otp`, `tdate`, `ipaddress`, tender_open, tender_id) values( '$user_email', '$six_digit_random_number', '$tdate', '$ipaddress', 'Y' , '$tender_id' )";
			mysqli_query($con, $sql);
			
			$userid   		= $_SESSION['usrid'];
			$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'TN', '$tender_id', '$userid', now(), 'OTP Sent', '$sent_otp_id', now() ) ";
			$query=mysqli_query($con, $sql);
				
		$body .= "OTP : " .$six_digit_random_number ."<br><br>";
			
		$body .= "<br>".'This is an automatically generated email from P2P application, please do not reply to it. If you have any queries regarding, please email personally to the respective user.';
		$body .= "<br><br>"."Thank & Regards"."<br>";

		$body .= "<br><br>".$disclaimer;
//	echo $body;
//	exit();	
		$mail->MsgHTML($body);

		//if ($ij <= $tmp_var){
		//send the message, check for errors
			if (!$mail->send()){
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				//echo "Message sent!";
				
			}  
		//}
		$body ='';
		
	}
	
//exit();
		$baseurl.= $modulePath. "tender_send_otp.php?sub=otpenter&tender_id=$tender_id";
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
}

if( $_GET['sub']=='otpok' ){
	
		$tender_id 	= $_POST['tender_id'];
		$otp 	= $_POST['otp'];
		$otpval	= $_POST['otpval'];
		for($i = 0; $i < sizeof($otp); $i++){
		
			$otpdb = $otp[$i];
			$otpvalin = $otpval[$i];
			if($otpdb != $otpvalin){
				$baseurl.= $modulePath. "tender_send_otp.php?sub=otpenter&tender_id=$tender_id&err=err";
				echo "<script>window.location.href='$baseurl';</script>";
				exit();
			}	
		}
		
		$sql      = " select * from user_login where tender_id = '$tender_id' ";
		$result   = mysqli_query($con, $sql);
		$rowcount = mysqli_num_rows($result);
		
		$sql      = " update user_login set one_time = '1' where tender_id='$tender_id' ";
		mysqli_query($con, $sql);
		
		$sql      = " update sma_tender_header set status = 'Opened' where id = '$tender_id' ";
		mysqli_query($con, $sql);
		
		$userid   		= $_SESSION['usrid'];
		
		for($i = 0; $i < sizeof($otp); $i++){
		
			$otpdb = $otp[$i];
			$sql   = " SELECT b.id as user_id 
						FROM `user_login` a, sma_user b 
							WHERE 1 and a.userid = b.userid and otp = '$otpdb' 
								AND tender_id = '$tender_id'";
			$result   = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			$otp_user_id = $row['user_id'];
			
			$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					VALUES( 'TN', '$tender_id', '$otp_user_id', now(), 'Opened', '$userid', now() ) ";
			mysqli_query($con, $sql);
			
		}
		
	$baseurl.= $modulePath. "edit.php?sub=edit&id=$tender_id";
	echo "<script>window.location.href='$baseurl';</script>";
	exit();
		
}	


if($_GET['sub']=='otpenter') {	

		$tender_id = $_GET['tender_id'];
		
?>
<!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $baseurl . 'plugins/select2/select2.min.css'; ?> ">
  
	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Tender
            <small>Enter OTP</small>
		<!--<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender</a></li>
            <li class="active">Enter OTP</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" method="post" 
										action="tender_send_otp.php?sub=otpok">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
	
						<input type="hidden" name="tender_id" value="<?= $_GET['tender_id'];?>">
						
						<label class="control-label">Quotation Received from Vendor</label>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">&nbsp;</label>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Enter OTP ...</label>
							</div>

						</div>


				<?php 
					
					$sql = " SELECT distinct(b.party_name) as party_name FROM `sma_tender_supplier` a,  sma_party_mst b where a.tender_hdr_id = '$tender_id' and b.id = a.supplier_id and a.quotation_received = 'Y' ";
					
					$q2 	= mysqli_query($con, $sql);	
					$ii =0 ;
					while($r2 = mysqli_fetch_array($q2)){ 
						$party_name = $r2['party_name'];
						$ii = $ii +1;
				?>		
						<div class="form-group">	
						<div class="col-md-5">
							<label class="control-label"><?= $ii. '. ' . $party_name; ?></label>
						</div>
						</div>
						
			<?php if($_GET['err']){ ?>			
					<div class="form-group">
						<div class="col-md-4">
							<label class="control-label">&nbsp;</label>
						</div>
						
						<div class="col-md-4">
							<label class="control-label" style="color:red;">Error : Enter Valid OTP </label>
						</div>
					</div>
			<?php } ?>	
			
				<!--	<div class="form-group">
						<div class="col-md-4">
							<label class="control-label">&nbsp;</label>
						</div>
						
						<div class="col-md-4">
							<label class="control-label">Enter OTP </label>
						</div>

					</div>-->
					
				<?php					
					}
				
					$tdate = date('Y-m-d');
					$sql = "SELECT distinct(b.username), a.otp, a.id FROM `user_login` a, sma_user b 
							WHERE a.tender_id = '$tender_id' and tdate = '$tdate'
							AND ( b.email = a.userid || b.userid = a.userid ) AND tender_open = 'Y' 
							AND one_time = '' and a.id in ( SELECT max(id) FROM `user_login` where tender_id = '$tender_id' AND tdate = '$tdate' AND tender_open = 'Y' AND one_time = '' GROUP BY userid )";
//echo $sql;
					$q2 	= mysqli_query($con, $sql);	
					$ii =0 ;
					while($r2 = mysqli_fetch_array($q2)){
						$username 	= $r2['username'];
						$otp 		= $r2['otp'];
						$otpid 		= $r2['id'];
				?>	
				
					<div class="form-group">
						<div class="col-md-4">
							<input type='text' class="form-control" readonly name ="usernm[]" value="<?= $username; ?>" >
							<input type='hidden' name ="otpuserid[]" value="<?= $userid; ?>" >
							<input type='hidden' class="otp" name ="otp[]" value="<?= $otp; ?>" >
							<input type='hidden' name ="otpid[]" value="<?= $otpid; ?>" >
						</div>
						
						<div class="col-md-3">
							<input type='text' class="form-control " id ="otpval" name ="otpval[]" value="" onblur="getotpvalidate123(this.value);">
						</div>
					</div>
					
				<?php		
					}
				?>
					
					<span id="predit"> </span>
				
						<div class="box-footer">
									
							<div class="col-sm-3 text-right">
										<span>&nbsp;&nbsp;</span>
										
							</div>
							<div class="col-sm-4 text-right">
										
								<a class="btn btn-default" href="<?= $baseurl . 'tender/' ?>">Cancel</a>
										
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Submit" name="otpok">
										
							</div>
							
						</div>	
								
					</fieldset>
				</form>
			</div>	
         </div>				

<?php
	include("../footer.php");

}

?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
	
	

<script>
   
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

	function getotpvalidate(id){
		var otp		 		=  $(".otp").val();
		
		$('#predit').html('');
//alert(id + ' <<>> ' + otp);		
		if(otp!=id){
			$('#predit').html('Enter valie OTP !');
		}
	
	}	
</script>	
	