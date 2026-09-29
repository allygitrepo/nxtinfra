<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<div class="col-md-4">
					<label class="control-label">Budget Name</label>
					<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget(this.value)" required="true" >
									<option value=""> Select </option>';

	    $sql = "select distinct(b.id), b.name from sma_budget a, sma_budget_name b  where project = '$id' and a.budget_name = b.id ";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
			$id = $r2->id;
            
            $value .= "<option value='".$id."'>".$id. ' ' .$name."</option>";
        };
		$value .= '</select></div>';

//$value=$sql;

        echo $value;
    }
	
	if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$project = $_POST['project'];
		$account_year = $_POST['account_year'];
		$value = '';

/*	    $sql = "select * from sma_budget where project = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$balance_budget = $r2->balance_budget;
		$budget_category  = $r2->budget_category;
		$budget_head_id   = $r2->id;
		
//    $value1 = $sql;
		
		$sql = "select * from sma_budget_category where id = '$budget_category' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_head    = $r2->category;
*/
        
		$value ='<div class="col-md-3"><label class="control-label">Budget Head</label>';
//		$value .='<input type="hidden" class="form-control" id="budget_head_id" name="budget_head_id" value="'. $budget_head_id .'" >';
						
//		$value .= '<input type="text" class="form-control" id="budget_head" name="budget_head" readonly value="'.$budget_head.'" >';

		$value .='<select class="form-control" name="budget_head_id" id="budget_head_id" required="true" onchange="getavailbudget(this.value)" >
									<option value=""> Select </option>';
	    $sql = "select * from sma_budget where project = '$project' and account_year = '$account_year' and budget_name = '$id' ";
//$value1 = $sql;	
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$balance_budget = $r2->balance_budget;
			$budget_category  = $r2->budget_category;
			$budget_head_id   = $r2->id;

			$sql = "select * from sma_budget_category where id = '$budget_category' ";
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_head    = $r3->category;
			
            $value .= "<option value='".$budget_head_id."'>".$budget_head."</option>";
        };
		
		$value .= '</select>';

		$value .= "</div>";
		
//		$value .='<div class="col-md-2"><label class="control-label">Budget Available</label>';
//		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
//		$value .= "</div>";	
		
//$value = $value1;
		echo $value;
		
	}
	
	if(isset($_POST['sub22'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = "select * from sma_budget where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$balance_budget = $r2->balance_budget;
		$budget_head    = $r2->budget_category;
        
		$value .='<div class="col-md-2"><label class="control-label">Budget Available</label>';
		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
		$value .= "</div>";	
		
		echo $value;
		
	}

	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$approval_hdr_id 		= $_POST['approval_hdr_id'];
		$quote_ref_no 			= $_POST['quote_ref_no'];
		$vendor_selected 	= $_POST['vendor_selected'];
		$values 			= $_POST['values'];
		$remarks 			= $_POST['remarks'];
		
		$sql = "insert into `sma_approval_details` (approval_hdr_id, quote_ref_no, vendor_selected,  values, remarks ) values ('$approval_hdr_id', '$quote_ref_no', '$vendor_selected', '$values','$remarks')";
//$value1=$sql;	
		$r2 = mysqli_query($con, $sql);

		$sql="SELECT * from sma_approval_details where approval_hdr_id = '$approval_hdr_id' ";
//$value1=$sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
//$value = $value1;
		
		$value = "<script>window.location.href='edit.php?sub=edit&id=$approval_hdr_id&active=active';</script>";
	
		echo $value;
		
	}

	if(isset($_POST['sub4'])){
	
		$value ='';
        $id = $_POST['id'];
		$approval_hdr_id = $_POST['approval_hdr_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_approval_details where approval_srno = '$id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
//$value = $value1;
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$approval_hdr_id&active=active&123';</script>";
		echo $value;
		
	}


	if(isset($_POST['sub9'])){

		$modulePath = "approval/"; 
	
		$value ='';
        $ap_id = $_POST['ap_id'];
		$srno  = $ap_id;
		if($_POST['ap_id'] == ''){$ap_id = '';}
		
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approved 		= $_POST['approved'];

		$account_year		= $_POST['account_year'];
		$company			= $_POST['company'];
		$budget_head_id		= $_POST['budget_head_id'];
		$budget_name		= $_POST['budget_name'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];

//echo $statusap. ' ##### 123 "<BR>';	
		if($status =='Draft' || $status == ''){
			$statusap = 'Pending';
		}
		
		//Budget calculation Start
		$values = 0;
		$sql = "select * from sma_approval_details where approval_hdr_id = '$ap_id' and vendor_selected = 'Y' ";
		$q3  = mysqli_query($con, $sql);
		while($r3  = mysqli_fetch_object($q3)){
			$values   = $values  + $r3->values;
		}

		$prev_values = $_POST['prev_values'];
		
		$sql = "select * from sma_budget where id = '$budget_head_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_object($q3);
			

		if($statusap =='Approve'){
			$blocked_budget   	= $r3->blocked_budget + $values;  //if Submitted
			$sql = "update sma_budget set blocked_budget= '$blocked_budget'  where id = '$budget_head_id' ";
			$q3  = mysqli_query($con, $sql);

		}
		else if($statusap =='Reject'){
			$blocked_budget   	= $r3->blocked_budget - $values ;  //if Rejected
			$sql = "update sma_budget set blocked_budget= '$blocked_budget' where id = '$budget_head_id' ";
			$q3  = mysqli_query($con, $sql);
			
		}
		
//Budget calculation END
//$file = fopen("ravitest.txt","w");
//fwrite($file,$sql);
//fclose($file);
				
		$sql = " select * from sma_user where userid in (select draft_by from sma_approval_memo where id = '$ap_id') ";
		
		$result=mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		$draft_by 			= $row['userid'];
		$draft_by_id 		= $row['id'];
		$user_category 		= $row['user_category'];

	if($statusap =='Approve' || $statusap =='Pending'){
		
		//$user_category	= $_SESSION['user_category'];
		$sql = "SELECT * from sma_workflow where doc_type= 'AP' and user_category = '$user_category' and to_value >= $values and from_value <= $values ";
		
//$file = fopen("ravitest.txt","a");
//fwrite($file,$sql);
//fclose($file);
		
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		$user_category 		= $row['user_category'];
		$to_value 			= $row['to_value'];
		$pm_flag			= $row['project_manager'];
		$pi_flag		 	= $row['project_incharge'];
		$cxo_flag			= $row['coo_cxo'];

		$company	= $_POST['company'];
		$role		= $_SESSION['role']; //Maker
		
		$sql = "SELECT * FROM `company` where comp_id = $company ";
//$file = fopen("ravitest.txt","a");
//fwrite($file,$sql);
//fclose($file);
		$res = mysqli_query($con, $sql);
		$r1  = mysqli_fetch_array($res);
		$project_manager  ='';
		$project_incharge ='';
		$coo_cxo 		  ='';
		if($pm_flag == 'Y'){
			$project_manager  = $r1['project_manager'];
		}
		if($pi_flag == 'Y'){
			$project_incharge = $r1['project_incharge'];
		}
		if($cxo_flag == 'Y'){
			$coo_cxo 		  = $r1['coo_cxo'];
		}
		
		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Submited';
			$flow_flag = 'P';
		}
		else if($statusap=='Approve'){
			$approval_status = 'Verified';
			$status = 'Submited';
			$flow_flag = 'P';
		//	$approval_status	= 'Approved';
		//	$status				= 'Completed';
		//	$flow_flag = 'A';
		}
		else if($statusap=='Reject'){
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
		}
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		
		if($project_manager == $userid ){
			if(empty($pi_flag) && empty($coo_cxo) ){
				$approval_status	= 'Approved';
				$status				= 'Completed';
				$flow_flag = 'A';
			}
		}
		
		if($project_incharge == $userid ){
			if(empty($pm_flag) && empty($coo_cxo) ){
				$approval_status	= 'Approved';
				$status				= 'Completed';
				$flow_flag = 'A';
			}
			
			if(!empty($pm_flag) && empty($coo_cxo) ){
				$approval_status	= 'Approved';
				$status				= 'Completed';
				$flow_flag = 'A';
			}
		}
		
		if($coo_cxo == $userid ){
			$approval_status	= 'Approved';
			$status				= 'Completed';
			$flow_flag = 'A';
		}

		$sql = "update sma_approval_memo set approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now(), flow_flag = '$flow_flag' where id = '$ap_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$s1   = "SELECT * from workflow_history where doc_id = '$ap_id' and doc_type = 'AP' and reviewed_by = '$userid' ";		
		$res  = mysqli_query($con, $s1);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$reviewed_by  = $r1['reviewed_by'];

		if($project_manager == $reviewed_by && !empty($project_manager) ){
			$approver	= $project_incharge;
//			echo $approver.' ###2<br>';
		}
		else if($project_incharge == $reviewed_by && !empty($project_incharge)){
			$approver	= $coo_cxo;
//			echo $approver.' ###3<br>';
		}
		else if($coo_cxo == $reviewed_by && !empty($coo_cxo)){
			$a='';
//			echo $approver.' ###4<br>';
			$approver	= $draft_by_id;
		}
		else {
		
			//$approver	= $project_manager;
			if(!empty($project_manager) ){
				$approver	= $project_manager;
//				echo $approver.' ###5<br>';
			}
			else if(empty($project_manager) ){
				$approver	= $project_incharge;
//				echo $approver.' ###5<br>';
			}
			else if(empty($project_incharge) ){
				$approver	= $coo_cxo;
//				echo $approver.' ###6<br>';
			}

		}	
	
	}

//echo $statusap. ' #####"<BR>';	
//echo $draft_by. ' ' . $draft_by_id. ' #####"<BR>';	

	if ($statusap=='Reject'){
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		$approver	= $draft_by_id;
			
		$sql = "update sma_approval_memo set approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now(), flow_flag = '$flow_flag' where id = '$ap_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

	}

		$status		= $approval_status;
		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag ) 
						values('AP', '$ap_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now(), '$flow_flag')";
//$file = fopen("ravitest.txt","a");
//fwrite($file,$sql);
//fclose($file);
//exit();
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//Send mail to approver;
		$sql="select * from sma_user where id='$approver' ";
//echo $sql. "<BR>";
	
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

		$modulePath = "approval/";
		
//exit("RAVINDRA STOPED...");
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id;
		
		$msg = 'Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ap_mail.php";
		
		
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}
	

	if(isset($_POST['sub10'])){

		$value ='';
        $ap_id = $_POST['ap_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Draft';
		}

		$sql = "update sma_approval_memo set flow_flag = 'P', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$ap_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) 
									values('AP', '$ap_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//Send mail to approver;
		$sql="select * from sma_user where id='$approver' ";
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

		$modulePath = "approval/";
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id;
		
		$msg = '<br> For Checker, Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ap_mail.php";
		
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}

	
?>

