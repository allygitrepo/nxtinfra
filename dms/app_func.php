<?php
	session_start();	
	include('../dbcon.php');
	
	include('../baseurl.php');
	
	if(isset($_POST['sub1'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		//var sub = 'sub1';
		
		$inward_no 			= $_POST['inward_no'];
		$send_to_user 		= $_POST['send_to_user'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		
		if( $status == 'Draft' ){
			$status = 'Received';
		}
		
		$checked= sizeof($send_to_user);
		if($checked>=1){
			foreach ($_POST['send_to_user'] as $send_to_user){
				$send_to_user_a .= $send_to_user.',';
			}
		}
		$send_to_user = $send_to_user_a.'0';
			
		$sql = "update `dms_inward` set send_to_user = '$send_to_user', status = '$status', approval_status = '', changed_by = '', changed_date = '' where inward_no = '$inward_no' ";
	
		$r2 = mysqli_query($con, $sql);

//		$sql="SELECT * from dms_inward where id = '$inward_no' ";
//$value1=$sql;
//		$result = mysqli_query($con, $sql);
//		echo mysqli_error($con);
		$value="";
//$value = $value1;
		
	//	$value = "<script>window.location.href='index.php';</script>";

$value=$sql;	
		echo $value;
		
	}

	if(isset($_POST['sub2'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$inward_no 			= $_POST['inward_no'];
		$forward_to 		= $_POST['forward_to'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$userid 		  	= $_SESSION['usrid'];
		
		$status = 'Forwarded';
			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
				values('IN', '$inward_no', '$userid', now(), '$status', '$forward_to', '$remarks', now())";

			$r2 = mysqli_query($con, $sql);

		$value = "<script>window.location.href='inbox_scr.php?sub=list';</script>";
//$value=$sql;
		echo $value;
		
	}


	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$inward_no 			= $_POST['inward_no'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$approver			= $_POST['approver'];
		
		//if( $status == 'Received' ||  $status == 'Draft' ){
		$userid   	= $_SESSION['usrid'];
		
			//$status 		 = 'My Document';
			$approval_status = 'Accepted';
		
		//}
		//, status = '$status'
		
		$sql = "update `dms_inward` set approval_status = '$approval_status', status = '$approval_status', changed_by = '$approver', changed_date = now() where inward_no = '$inward_no' ";
		$r2 = mysqli_query($con, $sql);
		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
						values('IN', '$inward_no', '$userid', now(), '$approval_status', '$approver', '$remarks', now())";
						
		$r2 = mysqli_query($con, $sql);
		
		$sql = "update `my_documents` set current_user_id = '$userid', forwarded_to = '' where reference_id = '$inward_no' ";
		$r2 = mysqli_query($con, $sql);
		
		$value = "<script>window.location.href='inbox_scr.php?sub=list';</script>";
//$value=$sql;	
		echo $value;
		
	}


	if(isset($_POST['sub4'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$inward_no 			= $_POST['inward_no'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$approver			= $_POST['approver'];
		
		//if( $status == 'Received' ||  $status == 'Draft' ){		
			$status 		 = 'Draft';
			$approval_status = 'Rejected';
		//}
		
		$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$approver', changed_date = now(), status = '$status' where inward_no = '$inward_no' ";
	
		$r2 = mysqli_query($con, $sql);

		$value = "<script>window.location.href='inbox_scr.php?sub=list';</script>";
//$value=$sql;	
		echo $value;
		
	}

	if(isset($_POST['sub5'])){
//Forwarded	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$doc_scr 			= $_POST['doc_scr'];
		
		$inward_no 			= $_POST['inward_no'];
		$rid	 			= $_POST['rid'];
		$statusap			= $_POST['statusap'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$send_to			= $_POST['send_to'];
		
		$userid   			= $_SESSION['usrid'];
		
		if($statusap == 'Forwarded'){
			$inward_status	 = 'Forwarded';
			$status 		 = 'Send';
			$approval_status = 'Forwarded';
		}
		else {
			$status 		 = 'Forwarded';
			$approval_status = 'Send';
		}
		
//		$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$approver', changed_date = now(), status = '$status' where inward_no = '$inward_no' ";
//		$r2 = mysqli_query($con, $sql);
		
		$sql = "select * from forward_share_doc where userid = '$userid' "; // and status = 'Forwarded' ";
//echo $sql."  <BR>";		
		$rs = mysqli_query($con, $sql);
		$numrow = mysqli_affected_rows($con);
//echo $numrow. " <<==<BR>";
//exit();
	
		echo mysqli_error($con);
		if($numrow>0){
			while($rw = mysqli_fetch_array($rs)){
				
				$userid  	= $rw['userid']; 
				$dated		= $rw['dated']; 
				$doc_id		= $rw['doc_id'];
				$doc_type	= $rw['doc_type']; 
				//$status		= $rw['status']; 
				$flag		= $rw['flag'];
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, inward_status) 
								values( 'IN', '$doc_id', '$userid', now(), '$status', '$send_to', '$remarks', now(), '$inward_status' )";
				mysqli_query($con, $sql);
				echo mysqli_error($con);

//echo $sql."<BR>";
				if($statusap!='Forwarded'){
					$sql = " INSERT INTO forwarded_documents ( module, reference_id, forwarded_user_id, current_user_id, forwarded_date, status, remarks ) 
									values ( 'IN', '$doc_id', '$send_to', '$userid', now(), '$status', '$remarks' ) ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
					
				$sql = " update `dms_inward` set status = '$status' where inward_no = '$inward_no' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
//exit();
				//$value = "<script>window.location.href='my_document.php?sub=list';</script>";
				//echo $value;
				
			}
		}
		else {
			$sql = " insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, inward_status) 
							values( 'IN', '$inward_no', '$userid', now(), '$status', '$send_to', '$remarks', now(), '$inward_status' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
//echo $sql."<BR>";
			if($statusap!='Forwarded'){
				$sql = " INSERT INTO forwarded_documents ( module, reference_id, forwarded_user_id, current_user_id, forwarded_date, status, remarks ) 
									values ( 'IN', '$inward_no', '$send_to', '$userid', now(), '$status', '$remarks' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
//echo $sql."<BR>";			
			$sql = " update `dms_inward` set status = '$status' where inward_no = '$inward_no' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql."<BR>";
	
	}
		
//exit();

//Forwarded TO ;
			$sql="select * from sma_user where id='$send_to' ";
//echo $sql . "<BR>";			
			$result = mysqli_query($con, $sql);
			while($r = mysqli_fetch_object($result)){
				$username 		= $r->userid;
				$id		 		= $r->id;
				$role	 		= $r->role;
				$company_id 	= $r->company_id;
				$user_category 	= $r->user_category;
				$user_email		= $r->email;
				$user_name		= $r->username;
			}

//Send By
			$sql="select * from sma_user where id='$userid' ";
//echo $sql . "<BR>";			
			$result = mysqli_query($con, $sql);
			while($r = mysqli_fetch_object($result)){
				$username_by 		= $r->userid;
				$id_by		 		= $r->id;
				$role_by	 		= $r->role;
				$company_id_by 	= $r->company_id;
				$user_category_by 	= $r->user_category;
				$user_email_by		= $r->email;
				$user_name_by		= $r->username;
			}

			$modulePath = "dms/"; 
			
			$msg = 'Document Number : '.$inward_no . ' ' . 'Dated : ' . date("d-m-Y");
			$subject = "HC1 DMS - Document Forwarded by ". $user_name_by;
	//exit("RAVINDRA STOPED...");
	
			if($doc_scr=='INW'){
				$baseurl1 =$baseurl.$modulePath.'inbox_scr.php?sub=edit&inward_no='.$inward_no;
			}
			else {
				$baseurl1 =$baseurl.$modulePath.'my_document.php?sub=list&inward_no='.$inward_no;
			}
			
			include "dms_mail.php";
			
			if($doc_scr=='INW'){
				$baseurl1 =$baseurl.$modulePath.'inbox_scr.php?sub=edit&inward_no='.$inward_no;
				//$baseurl1 = $baseurl."dashboard.php?sub=dash&sopt=P";
				$value = "<script>window.location.href='$baseurl1';</script>";
			}
			else {
				echo "Forwarded...";		
				$baseurl1 =$baseurl.$modulePath.'my_document.php?sub=list&inward_no='.$inward_no;
				$value = "<script>window.location.href='my_document.php?sub=list';</script>";		
			}	
		
//$value=$sql;	
		echo $value;
		exit();
		
	}


	if(isset($_POST['sub6'])){
	
		$value ='';
		$inward_no = $_POST['id'];
		if($_POST['id'] == ''){$inward_no = '';}
		$forward_check = $_POST['forward_check'];
		$status	='';
		$userid 		  	= $_SESSION['usrid'];
		
		$cnt = 0;
		$sql = " select * from forward_share_doc where userid = '$userid' and doc_id = '$inward_no' ";
		mysqli_query($con, $sql);
		$cnt = mysqli_affected_rows($con);
		if($cnt>0){
			$sql = " delete from forward_share_doc where userid = '$userid' and doc_id = '$inward_no' ";
			mysqli_query($con, $sql);
		}
		else {
			
			/* $sql = " delete from forward_share_doc where userid = '$userid' and doc_id = '$inward_no' ";
			mysqli_query($con, $sql);
			 */
			$sql = " INSERT into forward_share_doc ( userid, dated, doc_id, doc_type, status, flag ) values ( '$userid', now(), '$inward_no', 'IN', '$status',  '$forward_check'); ";
			mysqli_query($con, $sql);
		}
//echo $sql;			
		/* 
		if($forward_check=='Y'){
			$sql = " INSERT into forward_share_doc ( userid, dated, doc_id, doc_type, status, flag ) values ( '$userid', now(), '$inward_no', 'IN', '$status',  '$forward_check'); ";
			mysqli_query($con, $sql);
		}
		else {
			
			$sql = " delete from forward_share_doc where userid = '$userid' and doc_id = '$inward_no' ";// and status = 'Forwarded' ";
			mysqli_query($con, $sql);

		} 
		*/
		
//$value = "<script>window.location.href='my_document.php?sub=list';</script>";
//$value=$sql;
//		echo $value;
		
	}

	
	if(isset($_POST['sub7'])){
//Shared
		$inward_no 			= $_POST['inward_no'];
		//$rid	 			= $_POST['rid'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$send_to_group 		= $_POST['send_to_group'];
		$ext_email			= $_POST['ext_email'].',';
		$ext_email_arr 		= explode(",",$ext_email);
		if($ext_email_arr[0]!="()"){
//echo " ==> ".$ext_email_arr[0]. " HEllo WORLD ####1";
			$ext_email 			= str_replace("(","",$ext_email);
			$ext_email 			= str_replace(")","",$ext_email);
			$ext_email_arr 		= explode(",",$ext_email);
		}
		else {
			$ext_email_arr ='';
		}	

//print_r($ext_email_arr);
//exit();
		$send_to			= $_POST['send_to'].',';
		$send_to_comp		= $_POST['send_to_comp'];
		$doc_scr 			= $_POST['doc_scr'];
		//str_replace("world","Peter","Hello world!");
		
		$send_to 			= str_replace("(","",$send_to);
		$send_to 			= str_replace(")","",$send_to);
		$send_to_arr 		= explode(",",$send_to);
//print_r($send_to_arr). "<BR>";
$scnt = sizeof( $send_to_arr );
//exit();
		$userid 		  	= $_SESSION['usrid'];

		$status 		 	= 'Shared';
		$approval_status 	= 'Shared';
		
		if(!empty( $send_to_group )){
			$sql = " select * from sma_user_group where id = '$send_to_group' ";	
			$qry = mysqli_query($con, $sql);
			$rw	 = mysqli_fetch_array($qry);
			$user_group_id = $rw['user_group_id'];
			
			$send_comp_group ='';
			$send_comp_group_arr ='';
			$sql = " select * from sma_user where id in ($user_group_id) ";
			$qry = mysqli_query($con, $sql);
			while($rw	 = mysqli_fetch_array($qry)){
				
				$send_comp_group .= $rw['id'].',';
				
			}
			
			$send_comp_group 	.= ',';
			$send_comp_group_arr = explode(",",$send_comp_group);
			
			$sql = " select * from forward_share_doc where userid = '$userid' ";
			//$value.=$sql."<BR>";	
			//echo $value;
					$qry = mysqli_query($con, $sql);
					$numrow = mysqli_affected_rows($con);
			//echo $numrow. " << Row Number <BR>";
					if($numrow > 0){
						while($rw	 = mysqli_fetch_array($qry)){
							
							$inward_no = $rw['doc_id'];
							
							$sql 	= "INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, sent_to, remarks, approved_date) values( 'IN', '$inward_no', '$userid', now(), '$status', '',  '$send_to_comp', '$remarks', now() )";
							$r2  = mysqli_query($con, $sql);
									
							foreach ($send_comp_group_arr as $sendto){
								$status = 'Shared';
								if( !empty($sendto) ){
									
									$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id, shared_date, status, remarks ) values ( 'IN', '$inward_no', '$sendto', '$userid', now(), '$status', '$remarks' ) ";
									mysqli_query( $con, $sql);
									echo mysqli_error($con);
//echo $sql."<BR>";
								}

							}

						}
//exit();
					}
					else if(!empty($inward_no)){
						$scnt = sizeof( $send_comp_group_arr );
						if($scnt>0){
							foreach ($send_comp_group_arr as $sendto){
								$status = 'Shared';
										
								if( !empty($sendto) && $sendto != 'null' ){
											
									$sql 	= "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
											values( 'IN', '$inward_no', '$userid', now(), '$status', '$sendto', '$remarks', now() )";
									$r2  = mysqli_query($con, $sql);

									$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id,  shared_date, status, remarks ) values ( 'IN', '$inward_no', '$sendto', '$userid',  now(), '$status', '$remarks' ) ";
									mysqli_query($con, $sql);
									echo mysqli_error($con);
								}
										
							}

						}

					}
					
					
						
					
		}
		
		//FIND_IN_SET("q", "s,q,l");
		if( !empty($send_to_comp) ){
			$sql = " select * from sma_user where FIND_IN_SET('$send_to_comp', company_id) ";
//echo $sql."<BR>";	
			$qry = mysqli_query($con, $sql);
			$send_comp_user ='';
			$send_comp_user_arr ='';
			while($rw	 = mysqli_fetch_array($qry)){
				
				$send_comp_user .= $rw['id'].',';
				
			}
			$send_comp_user .= ',';
			$send_comp_user_arr = explode(",",$send_comp_user);
//	print_r($send_comp_user_arr);
//	echo "<BR>";
			//foreach ($send_comp_user_arr as $sendto){
			//	echo $sendto. " < , > ";
			//}
	//exit();
	
				$sql = " select * from forward_share_doc where userid = '$userid' ";
			//$value.=$sql."<BR>";	
			//echo $value;
					$qry = mysqli_query($con, $sql);
					$numrow = mysqli_affected_rows($con);
			//echo $numrow. " << Row Number <BR>";
					if($numrow > 0){
						while($rw	 = mysqli_fetch_array($qry)){
							
							$inward_no = $rw['doc_id'];
							
							$sql 	= "INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, sent_to, approved_date) values( 'IN', '$inward_no', '$userid', now(), '$status', '', '$remarks', '$send_to_comp', now() )";
							$r2  = mysqli_query($con, $sql);
									
							foreach ($send_comp_user_arr as $sendto){
								$status = 'Shared';
								if( !empty($sendto) ){
									
									$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id, shared_date, status, remarks ) values ( 'IN', '$inward_no', '$sendto', '$userid', now(), '$status', '$remarks' ) ";
									mysqli_query( $con, $sql);
									echo mysqli_error($con);
//echo $sql."<BR>";
								}

							}

						}
//exit();
					}

		}	
		
		$sql = " select * from forward_share_doc where userid = '$userid' ";
//$value.=$sql."<BR>";	
//echo $value;
		$qry = mysqli_query($con, $sql);
		$numrow = mysqli_affected_rows($con);
//echo $numrow. " << Row Number <BR>";
		if($numrow > 0){
			while($rw	 = mysqli_fetch_array($qry)){
				
				$inward_no = $rw['doc_id'];
				$scnt = sizeof( $send_to_arr );
				if($scnt>0){
					foreach ($send_to_arr as $sendto){
						$status = 'Shared';
						if( !empty($sendto) ){
							$sql 	= "INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
											values( 'IN', '$inward_no', '$userid', now(), '$status', '$sendto', '$remarks', now() )";
							$r2  = mysqli_query($con, $sql);
							
							$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id, shared_date, status, remarks ) 
										values ('IN', '$inward_no', '$sendto', '$userid', now(), '$status', '$remarks' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);
	//echo $sql."<BR>";
						}
					}	

				}
				
			}

		}
		else if(!empty($inward_no)){
				$scnt = sizeof( $send_to_arr );
				if($scnt>0){
					foreach ($send_to_arr as $sendto){
						$status = 'Shared';
						
				//echo $sendto;
//exit();				
						if( !empty($sendto) && $sendto != 'null' ){
							
							$sql 	= "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
											values( 'IN', '$inward_no', '$userid', now(), '$status', '$sendto', '$remarks', now() )";
							$r2  = mysqli_query($con, $sql);

							$sql = " INSERT INTO shared_documents ( module, reference_id, shared_user_id, current_user_id,  shared_date, status, remarks ) 
										values ( 'IN', '$inward_no', '$sendto', '$userid',  now(), '$status', '$remarks' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);

						}
						
					}

				}

		}

			$sql = " update `my_documents` set shared_to_user_id = '$send_to' where reference_id = '$inward_no' ";
			$r2  = mysqli_query($con, $sql);
				
//		echo "Shared...";

//Shared TO Group users;
	$scnt = sizeof( $send_comp_group_arr );
	if($scnt>0){
		foreach ($send_comp_group_arr as $sendto){
			$status = 'Shared';
			if( !empty($sendto) ){
			$sql="select * from sma_user where id='$sendto' ";
//echo $sql."<BR>";			
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_category 	= $r->user_category;
					$user_email		= $r->email;
					$user_name		= $r->username;

				}
			}
		}
	}
	
//Shared TO companies users;
	$scnt = sizeof( $send_to_arr );
//echo $scnt. "<BR>";	
	if($scnt>0){
		foreach ($send_to_arr as $sendto){
			$status = 'Shared';
			if( !empty($sendto) ){
			$sql="select * from sma_user where id='$sendto' ";
//echo $sql."<BR>";			
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_category 	= $r->user_category;
					$user_email		= $r->email;
					$user_name		= $r->username;

				}
			}
		}
	}
//exit();	
//Send By
			$sql="select * from sma_user where id='$userid' ";
//echo $sql."<BR>";			
			$result = mysqli_query($con, $sql);
			while($r = mysqli_fetch_object($result)){
				$username_by 		= $r->userid;
				$id_by		 		= $r->id;
				$role_by	 		= $r->role;
				$company_id_by 		= $r->company_id;
				$user_category_by 	= $r->user_category;
				$user_email_by		= $r->email;
				$user_name_by		= $r->username;
			}

			$modulePath = "dms/"; 
			
			$msg 	= 'Document Number : '.$inward_no . ' ' . 'Dated : ' . date("d-m-Y");
			$subject = "HC1 DMS - Document Shared by ". $user_name_by;
//	exit("RAVINDRA STOPED...");

			$baseurl1 =$baseurl.$modulePath.'shared_document.php?sub=edit&inward_no='.$inward_no;
				
			include "dms_mail.php";
			
			$sql = " DELETE from forward_share_doc where userid ='$userid' ";
			mysqli_query($con, $sql);

//exit();

			echo "Shared...";		
			$baseurl1 =$baseurl.$modulePath.'my_document.php?sub=list&inward_no='.$inward_no;
			$value = "<script>window.location.href='my_document.php?sub=list';</script>";		
			
//		$value = "<script>window.location.href='my_document.php?sub=list';</script>";
//$value.=$sql."<BR>";	
		echo $value;
		exit();
		
	}


if(isset($_POST['sub8'])){
	
		$value 	=	'';
        $id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$others = $_POST['id'];
		$value ="";
		
	if($others=='O'){
	?>
	
		<label for="prDate" class="control-label">To Others</label>
		<input type="text" class="form-control" id="others" autocomplete="on" name="others" value="">

	<?php									
	}
	
//$value=$sql;	
		//echo $value;
		
}
	

if(isset($_POST['sub9'])){
	
		$value 	=	'';
        $id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$others = $_POST['id'];
		$value ="";
		
	if($others=='9999'){
	?>
	
	<label class="control-label">Sender Other</label>
	<input type="text" class="form-control" id="sent_by" name="sent_by" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $row['sent_by'];?>" >
	<?php									
	}
	
//$value=$sql;	
		//echo $value;
		
}


if(isset($_POST['sub10'])){
	
		$data=[];
		$value 	=	'';
        $id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value ="";
		
		$id1 	= '';
		$uvname = 'Select';
		$data[]=['id1'=>$id1,'uvname'=>$uvname,];
		
		$id1 	= 'O';
		$uvname = 'Other';
		$data[]=['id1'=>$id1,'uvname'=>$uvname,];
		
		if($id=='I'){
			$sql = " SELECT id as id, username as uvname, 'U' as type FROM sma_user where active != '0' ORDER BY uvname ASC  ";
		}
		else {
			$sql = " SELECT id as id, party_name as uvname, 'P' as type FROM `sma_party_mst` ORDER BY uvname ASC ";
		}
		$q2 = mysqli_query($con, $sql);
		while($row=mysqli_fetch_assoc($q2))
		{
			$id1 	= $row['id'];
			$uvname = $row['uvname'];
			$data[]=['id1'=>$id1,'uvname'=>$uvname,];
			
		}
		
		echo json_encode($data);

//$value=$sql;	
		//echo $value;
		
}


if(isset($_POST['sub11'])){
	
		$value 	=	'';
        $id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value ="";
		if($id =='Y'){
	?>
		<div class="col-md-2">
										<label class="control-label">Reminder day</label>
										<select class="form-control  " id="in_days" name="in_days" <?php echo $readonly; ?> >
											<option value=""  >Days</option>
											<option value="1" <?php echo ($row['in_days'] == '1' )?'selected="selected"':'';?> >1</option>
											<option value="2" <?php echo ($row['in_days'] == '2' )?'selected="selected"':'';?> >2</option>
											<option value="3" <?php echo ($row['in_days'] == '3' )?'selected="selected"':'';?> >3</option>
											<option value="4" <?php echo ($row['in_days'] == '4' )?'selected="selected"':'';?> >4</option>
											<option value="5" <?php echo ($row['in_days'] == '5' )?'selected="selected"':'';?> >5</option>
											<option value="6" <?php echo ($row['in_days'] == '6' )?'selected="selected"':'';?> >6</option>
											<option value="7" <?php echo ($row['in_days'] == '7' )?'selected="selected"':'';?> >7</option>
											<option value="8" <?php echo ($row['in_days'] == '8' )?'selected="selected"':'';?> >8</option>
											<option value="9" <?php echo ($row['in_days'] == '9' )?'selected="selected"':'';?> >9</option>
											<option value="10" <?php echo ($row['in_days'] == '10' )?'selected="selected"':'';?> >10</option>
										</select>	
		</div>
									
		<div class="col-md-2">
					<label class="control-label">Reminder Date</label>
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
					<div class="input-group-addon">
						<i class="fa fa-calendar-alt"></i>
					</div>
                    <input type="text" class="form-control" id="reminder_date" name="reminder_date" placeholder="dd/mm/yyyy" value="<?php echo date('d-m-Y');?>">
					</div>
		</div>
		
		<div class="col-md-2" >
			<label class=" control-label">Stop Reminding</label><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" class="minimal" id="stop_remind" name="stop_remind" value="Y" onchange="getoutwno123(this.value)" > 
		</div>
		
		<span id="getoutwno123">
			
		</span>
<?php
	}
	else {

		echo "";
	}
}


if(isset($_POST['sub12'])){
	
		$value 	=	'';
        $id 	= $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value ="";
//		if($id =='Y'){
	?>
		
		<div class="col-md-3">
				<label class="control-label">Outward No.</label>
				<input type="text" class="form-control" id="outward_number1" name="outward_number1" value="">
		</div>
		
<?php
//		}
		
}




	if(isset($_POST['sub13'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$inward_no 			= $_POST['inward_no'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];
		$approver			= $_POST['approver'];
		
		$userid   	= $_SESSION['usrid'];
		$approval_status = 'Accepted';
		
		$sql = "select * from forward_share_doc where userid = '$userid' "; // and status = 'Forwarded' ";		
		$rs = mysqli_query($con, $sql);
		$numrow = mysqli_affected_rows($con);
//echo $numrow. " <<==<BR>";
//exit();
	
		echo mysqli_error($con);
		if($numrow>0){
			while($rw = mysqli_fetch_array($rs)){
				
				$userid  	= $rw['userid']; 
				$dated		= $rw['dated']; 
				$doc_id		= $rw['doc_id'];
				$doc_type	= $rw['doc_type']; 
				//$status	= $rw['status'];
				$flag		= $rw['flag'];
				
				$sql = "update `dms_inward` set approval_status = '$approval_status', status = '$approval_status', changed_by = '$approver', changed_date = now() where inward_no = '$doc_id' ";
				$r2 = mysqli_query($con, $sql);
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$doc_id', '$userid', now(), '$approval_status', '$approver', '$remarks', now())";
								
				$r2 = mysqli_query($con, $sql);
				
				$sql = "update `my_documents` set current_user_id = '$userid', forwarded_to = '' where reference_id = '$doc_id' ";
				$r2 = mysqli_query($con, $sql);
//exit();
				//$value = "<script>window.location.href='my_document.php?sub=list';</script>";
				//echo $value;
				
			}
		}
		
		$sql = "update `dms_inward` set approval_status = '$approval_status', status = '$approval_status', changed_by = '$approver', changed_date = now() where inward_no = '$inward_no' ";
		$r2 = mysqli_query($con, $sql);
		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
						values('IN', '$inward_no', '$userid', now(), '$approval_status', '$approver', '$remarks', now())";
						
		$r2 = mysqli_query($con, $sql);
		
		$sql = "update `my_documents` set current_user_id = '$userid', forwarded_to = '' where reference_id = '$inward_no' ";
		$r2 = mysqli_query($con, $sql);
		
		$value = "<script>window.location.href='inbox_scr.php?sub=list';</script>";
//$value=$sql;	
		echo $value;
		
	}



	if(isset($_POST['sub14'])){
//Shared
		$inward_no 			= $_POST['inward_no'];
		//$rid	 			= $_POST['rid'];
		$status 			= $_POST['status'];
		$remarks 			= $_POST['remarks'];		
		$doc_scr 			= $_POST['doc_scr'];
		$ie_flag 			= $_POST['ie_flag'];
		$send_to_user		= $_POST['send_to'];
		
		if($ie_flag=='I'){
			$user_type		= 'U';
			$sql="SELECT * FROM sma_user where id = '$send_to_user'";
			$rs = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rw = mysqli_fetch_array($rs);
			$s_name	= $rw['username'];
		}
		else if($ie_flag=='E'){
			$user_type		= 'P';
			$sql="SELECT * FROM sma_party_mst where id = '$send_to_user'";
			$rs = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$rw = mysqli_fetch_array($rs);
			$s_name	= $rw['party_name'];
		}
		$p_code 			 = substr($s_name,0,3);
	
//exit();
		$userid 		  	= $_SESSION['usrid'];

		$approval_status = 'Sent';
		$status 		 = 'Sent';
		$sent_by			= $_SESSION['user'];
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$sent_by_user_vendor= $userid;
		
		$sql = " select * from forward_share_doc where userid = '$userid' ";
		$qry = mysqli_query($con, $sql);
		$numrow = mysqli_affected_rows($con);
			//echo $numrow. " << Row Number <BR>";
			
		if($numrow > 0){
			while($rw	 = mysqli_fetch_array($qry)){
							
				$inward_no_ref = $rw['doc_id'];
				
				$sql = " SELECT max(inward_no) as inward_no, max(outward_no) as outward_no FROM `dms_inward` ";
				$qry = mysqli_query($con, $sql);
				$r2	 = mysqli_fetch_array($qry);
				$inw_no = $r2['inward_no'];
				$out_no = $r2['outward_no'];
				$inward_no  = $inw_no + 1;
				$outward_no = $out_no + 1;
				
				$sql = " SELECT * FROM `dms_inward` where inward_no = '$inward_no_ref' ";
				$qry = mysqli_query($con, $sql);
				$r2	 = mysqli_fetch_array($qry);
				$company_for		= $r2['company_for'];
				$mode_of_receipt	= $r2['mode_of_receipt'];
				//$send_to_user		= $r2['send_to_user'];
				//$user_type		= 'P';
				$sent_by_user_vendor = $r2['sent_by_user_vendor'];
				$sent_by_user_type	= 'P';
				$saved 				= 'Y';
				//$outward_doc_no 	= $r2['outward_doc_no'];
				
				$sql = "select * from company where comp_id = '$company_for' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$comp_code = $r2['comp_code'];
				
				$outward_doc_no		= $comp_code.'_'.$p_code.'_'.date("Y").'_'.$outward_no;		
				
				$remarks .= '(Ref.No. '.$inward_no_ref.')';
				$sql = " INSERT INTO dms_inward (inward_no, outward_no, date_of_received, company_for, mode_of_receipt, sent_by, send_to_user, status, remarks, draft_by, draft_dated, user_type, sent_by_user_vendor, sent_by_user_type, saved, ie_flag, outward_doc_no ) 
				Values( '$inward_no', '$outward_no', now(), '$company_for', '$mode_of_receipt', '$sent_by', '$send_to_user', '$status', '$remarks', '$user', now(), '$user_type', '$sent_by_user_vendor', 'U', '$saved', '$ie_flag', '$outward_doc_no' ) ";
				$qry = mysqli_query($con, $sql);
			
				$sql = "update `dms_srno` set inward_no = '$inward_no' , outward_no = '$outward_no' ";//where inward_no < '$inward_no' ";
				$qry = mysqli_query($con, $sql);
			
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, outward) 
						VALUES('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now(), 'O')";
				$r2  = mysqli_query($con, $sql);
				
				$sql = " insert into my_documents ( module, reference_id, current_user_id, date_uploaded ) 
					VALUES ( 'IN', '$inward_no', '$send_to_user', now() ) ";
				$r2  = mysqli_query($con, $sql);
							
			}

		}
					
//Send By
			$sql="select * from sma_user where id='$userid' ";
//echo $sql."<BR>";			
			$result = mysqli_query($con, $sql);
			while($r = mysqli_fetch_object($result)){
				$username_by 		= $r->userid;
				$id_by		 		= $r->id;
				$role_by	 		= $r->role;
				$company_id_by 		= $r->company_id;
				$user_category_by 	= $r->user_category;
				$user_email_by		= $r->email;
				$user_name_by		= $r->username;
			}

			$modulePath = "dms/"; 
			
			$msg 	 = 'Document Number : '.$inward_no . ' ' . 'Dated : ' . date("d-m-Y");
			$subject = "HC1 DMS - Document Outward by ". $user_name_by;
//	exit("RAVINDRA STOPED...");

			$baseurl1 =$baseurl.$modulePath.'outward.php?sub=edit&inward_no='.$inward_no;
				
			include "dms_mail.php";
			
			$sql = " DELETE from forward_share_doc where userid ='$userid' ";
			mysqli_query($con, $sql);

			echo "Outward...";		
			$baseurl1 =$baseurl.$modulePath.'outward.php?sub=edit&inward_no='.$inward_no;
			$value = "<script>window.location.href='$baseurl1';</script>";		
			
		echo $value;
		exit();
		
	}

	if(isset($_POST['sub15'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		//var sub = 'sub1';
		
		$courier_status ='B';
		
?>	
							
			<div class="form-group">
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Courier Status </label>
									<select class="form-control" name="courier_status" id="courier_status"  >
									<option value=""> Select </option>
									<option value="B" <?php echo ($courier_status == 'B')?'selected="selected"':'';?>> Booked </option>
									<option value="D" <?php echo ($courier_status == 'D')?'selected="selected"':'';?>> Delivered </option>
									<option value="R" <?php echo ($courier_status == 'R')?'selected="selected"':'';?>> Return </option>
									<option value="C" <?php echo ($courier_status == 'C')?'selected="selected"':'';?>> Cancel </option>
									</select>	
                                </div>
								
								<div class="col-md-5">	
									<label class="control-label">Courier Details</label>
									<input type="text" class="form-control" id="courier_details" name="courier_details" value="<?php echo $row['courier_details'] ?>">
								</div>
								
								<div class="col-sm-3">
									<label for="company_for" class="control-label">Courier Name </label>
                                	<select class="form-control select2" name="courier_name" id="courier_name"   required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_courier_mst order by courier_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['courier_name'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['courier_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<div class="col-sm-2">
									<label for="company_for" class="control-label">City </label>
                                	<select class="form-control select2" name="city" id="city"   required <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from cities order by city_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['city_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
			</div>
							
<?php 							
	}
	
?>

