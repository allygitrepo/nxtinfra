<?php 
	
	session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
	$finance_year = $_SESSION['finance_year'];
	
?>
<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<div class="col-md-3">
					<label class="control-label">Budget Name **</label>
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
		//$account_year = $_POST['account_year'];
		$value = '';
        
		$value ='<div class="col-md-3"><label class="control-label">Budget Head</label>';
		$value .='<select class="form-control" name="budget_head_id" id="budget_head_ID" required="true" onchange="getavailbudget(this.value)" >
									<option value=""> Select </option>';
	    $sql = "select * from sma_budget where project = '$project' and budget_name = '$id' and provisional_flag !='Y' and locked != 'Y' ";
//echo $sql;	//and account_year = '$account_year'
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
		$project 		= $_POST['project'];
		$budget_head 	= $_POST['budget_head'];
		$budget_category = $_POST['budget_category'];
		
		$value = '';

	    $sql = "select * from sma_budget where id = $id ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		//$balance_budget = $r2->balance_budget;
		$total_budget 	= $r2->total_budget;
		$blocked_budget	= $r2->blocked_budget;
		$used_budget	= $r2->used_budget;
		$balance_budget = $total_budget - $used_budget ;//- $blocked_budget
		$budget_head    = $r2->budget_category;
        
/* 		$value .='<div class="col-md-2"><label class="control-label">Budget Available</label>';
		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
		$value .= "</div>";	 */
?>		
		<div class="col-md-2">
			<label class="control-label">Total Budget</label>
			<input type="text" class="form-control"  readonly style="text-align:right;" value="<?php echo $total_budget;?>" >
		</div>
		<div class="col-md-2">
			<label class="control-label">Blocked Budget</label>
			<input type="text" class="form-control"  readonly style="text-align:right;" value="<?php echo $blocked_budget;?>" >
		</div>
									
		<div class="col-md-2">
			<label class="control-label">Used Budget</label>
			<input type="text" class="form-control"  readonly style="text-align:right;" value="<?php echo $used_budget;?>" >
		</div>
									
		<div class="col-md-2">
			<label class="control-label">Budget Available</label>
			<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="<?php echo $balance_budget;?>" >
		</div>
									
<?php
		//echo $value;
		
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

		$modulePath = "approval_adjustment/"; 
	
		$value ='';
        $ap_id = $_POST['ap_id'];
		$srno  = $ap_id;
		if($_POST['ap_id'] == ''){$ap_id = '';}
		$value ='';
        $ap_id 			= $_POST['ap_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_approval_memo where id = '$ap_id' "; 		
		
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$doc_ref_ap_no		= $r2['doc_ref_ap_no'];
		$subject			= $r2['subject'];
		$overhead_exp		= $r2['overhead_exp'];
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$against_po_flag 	= $r2['against_po_flag'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		$current_approver	= $r2['current_approver'];
			
		if($statusap=='Reject'){
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_username		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			//$userid		 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$to_approver		= $draft_by_id;
			$approval_status	= 'Rejected';
			$status				= 'Draft';
			$flow_flag 			= 'R';
			
			$sql = "UPDATE sma_approval_memo SET 
			approver_1 = '', approver_2 = '', approver_3 = '',approver_4 = '',
			approver_5 = '', approver_6 = '', approver_7 = '',approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() WHERE id = '$ap_id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

//Budget Revert  overhead_exp!='Y'
			if($overhead_exp!='Y'){
				$sql  = "SELECT * from sma_approval_items where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$quantity 		= $r2['quantity'];
					$rate 			= $r2['unit_rate'];
					$gst 			= $r2['gst'];
					$budget_id		= $r2['budget_id'];
					$amount	= round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
				
					$sql = " update sma_budget set blocked_budget = blocked_budget + $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
			}
			else if($overhead_exp=='Y'){
				$sql  = "SELECT * from sma_approval_expenses where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					$budget_id		= $r2['budget_id'];
					$amount			= $r2['amount'];
				
					$sql = " update sma_budget set blocked_budget = blocked_budget + $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
			}
			
//Budget Revert	
			if( empty($userid) ){
				$userid = $current_approver;
			}
			
			$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'DJ', '$ap_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		}
		else {
	//for Approve		
			
			$sql = " select * from sma_user where userid = '$draft_by' ";
			$q2 =mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$draft_by 			= $r2['userid'];
			$draft_name 		= $r2['username'];
			$draft_by_id 		= $r2['id'];
			$draft_email 		= $r2['email'];
			$draft_company_work	= $r2['company_work'];
			
//echo $sql."<BR>";
//echo $draft_by_id;
	
			$status_field_from ='';
			$decision_status	= '';
			if( $approver_1== $approver ){
				$to_approver 	 	= $approver_2;
				$status_field_from 	= 'approver_1_status';
				$status_field	 = 'approver_2_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_1== $approver && empty($approver_2) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_1_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_2== $approver && $approver_2_status=='Submitted' ){
				$to_approver 	 = $approver_3;
				$status_field_from = 'approver_2_status';
				$status_field	 = 'approver_3_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_2== $approver && empty($approver_3) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_2_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_3== $approver && $approver_3_status=='Submitted'  ){
				$to_approver 	 = $approver_4;
				$status_field_from = 'approver_3_status';
				$status_field	 = 'approver_4_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_3== $approver && empty($approver_4) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_3_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_4== $approver && $approver_4_status=='Submitted' ){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_5== $approver && $approver_5_status=='Submitted' ){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_6== $approver && $approver_6_status=='Submitted' ){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_7== $approver && $approver_7_status=='Submitted'  ){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			 if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			 if( $approver_8== $approver && $approver_8_status=='Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
//	echo ' #### 2 ### ' . $to_approver. "<BR>";		
//	echo $to_approver. ' ' .$approval_status."<BR>"; 
	//exit();
		if($approval_status =='Approved' || $approval_status =='Submitted'){
			
			$sqla = '';
			$status_field_from_v = '';
			if(!empty( $status_field_from )){
				$sqla = ", $status_field_from 	= 'Approved' ";
				$status_field_from_v 			= 'Approved';
			}
			$sql = " update sma_approval_memo set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$ap_id'";
	//echo $sql. "<BR>";		exit();
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);

				if(!empty($error)){echo $error; exit();}
				
			
//Budget Revert  overhead_exp!='Y' $overhead_exp!='Y'
			if( $status == 'Completed' ){
				
				$sql  = "SELECT * from sma_approval_items where approval_hdr_id = '$ap_id' ";
				$res  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($res)){
					
					$reversal_item_no 		= $r2['reversal_item_no'];
					$quantity 		= $r2['quantity'];
					
					$rate 			= $r2['unit_rate'];
					$gst 			= $r2['gst'];
					$budget_id		= $r2['budget_id'];
					$amount	= round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
				
					$sql = " UPDATE sma_budget SET blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
					
					$sql = " UPDATE sma_approval_items SET quantity = quantity - $quantity where id = '$reversal_item_no' and approval_hdr_id = '$doc_ref_ap_no' ";
					mysqli_query($con, $sql);
					
				}
			}
			
			$sql = " INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values('DJ', '$ap_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			
	//echo $sql. "<BR>";		
			
	}
//exit();
			
			
//Send mail to approver;
		$sql="select * from sma_user where id='$to_approver' and active='1' ";
//echo $sql. "<BR>";				
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	}

//exit('RAVINDRA Exit HERE...');
		
		$modulePath = "approval_adjustment/";
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$ap_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$ap_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$ap_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
		
		$msg = 'Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		
		include "ap_mail.php";
		
		$role		= $_SESSION['role'];
//echo $role;		
//exit("RAVINDRA STOPED...");

		if(!empty($status_field_from || $approval_status = 'Approved' || $approval_status = 'Rejected' )){
			$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=A";
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		else {	
			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		exit();
		
	}
	

	if(isset($_POST['sub10'])){

		$value ='';
        $ap_id 			= $_POST['ap_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_approval_memo where id = '$ap_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$against_po_flag 	= $r2['against_po_flag'];
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$draft_by_name		= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver ){
			$to_approver 	 	= $approver_2;
			$status_field_from 	= 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
			
		}
		if( $approver_1== $approver && empty($approver_2) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver ){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver ){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver ){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver ){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
		if( $approver_5== $approver && empty($approver_6) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
		}
		if( $approver_6== $approver ){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
		if( $approver_6== $approver && empty($approver_7) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
		}
		if( $approver_7== $approver ){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
		if( $approver_7== $approver && empty($approver_8) ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
		}
		if( $approver_8== $approver ){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_4_status';
			$approval_status = 'Approved';
			$status 		 = 'Completed';
			$decision_status = $approval_status;
		}
		
	if($approval_status =='Approved' || $mode =='Reject' ){
		$sql = " update sma_approval_memo set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now() $sqla where id = '$ap_id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) 
									values('DJ', '$ap_id', '$userid', now(), '$approval_status', '$to_approver', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
//Send mail to approver;
		$sql="select * from sma_user where id='$to_approver' and active='1' ";
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

//exit('Exit HERE...');

		$modulePath = "approval_adjustment/";
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id;
		
		$msg = '<br> For Checker, Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ap_mail.php";
		
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}

	
	if(isset($_POST['sub11'])){

		$value ='';
        $ap_id 			= $_POST['ap_id'];
		$mode		 	= $_POST['mode'];
		$remarks 		= $_POST['remarks'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		$approval_status = 'Pending';
		$status = 'Draft';
		
		$sql = "update sma_approval_memo set flow_flag = 'P', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$ap_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql 	= "select * from sma_approval_memo where id = '$ap_id'";
		$query	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($query);
		$approver   	= $r2['draft_by'];
		
		
//Send mail to approver;
		$sql="select * from sma_user where userid = '$approver' and active='1' ";
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

		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) 
									values('DJ', '$ap_id', '$userid', now(), '$approval_status', '$id', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$modulePath = "approval_adjustment/";
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id;
		
		$msg = '<br> For Maker to resend, Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		if(!empty($remarks)){
			$msg .= '<br> Remarks given by checker : '.$remarks;
		}
		
	//	exit();
		
		include "ap_mail.php";
		
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}

	
	
	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc = $_POST['party_id_doc'];
		if($_POST['id'] == ''){$id = '';}
?>			
			<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Party Name</th>
					<th>Document Type</th>
					<th>File Path</th>
					<th>File Name</th>
					<th>Inward No</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>

<?php				
			$sql = "SELECT a.id , a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
					FROM `my_documents_files` a, sma_document_type b , sma_party_mst c, dms_inward d 
					where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
					and a.reference_Id = d.inward_no and c.id = '$party_id_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><?php echo $row['file_name'];?></td>
					<td width="10%" ><?php echo $row['inward_no'];?></td>
					
					<td width="05%">
						<input type="checkbox" name="party_doc[]" <?php echo $checked; ?> id="party_doc" value="<?php echo $row['id']; ?>" >
					</td>
			</tr>
<?php			
			}
			
			
			$value = '';
			
					
//$value=$sql;

        echo $value;
    }
	
	if(isset($_POST['sub24'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}

			$sql = "SELECT * FROM sma_party_mst where id = '$id' "; 
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$row = mysqli_fetch_array($result);
			$pan_no = $row['party_pan_number'];
			$gst_no = $row['party_gst_number'];
			
?>				
				<label for="itemquote_ref_no" class="col-sm-2 control-label">PAN No.</label>
				<div class="col-sm-4">
						<input type="text" class="form-control" readonly value="<?php echo $pan_no ?> " >
				</div>
				<label for="itemquote_ref_no" class="col-sm-2 control-label">GST No.</label>
				<div class="col-sm-4">
						<input type="text" class="form-control" readonly value="<?php echo $gst_no ?> " >
				</div>
									
<?php			
				
//$value=$sql;

    }
	
	
    if(isset($_POST['sub25'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemName" id="itemName" required="true" onchange="getunit1(this.value);getcostcenter(this.value);" >
									<option value=""> Select </option>';
	    //$sql = "SELECT * FROM sma_product where 1 and `product_group` = '$id' ORDER BY name ASC";
		$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and a.product_group = '$id' and b.company_id = '$company_id' and b.budget_id > 0 order by name";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub26'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT a.id as cat_id, a.description as product_category, c.id as budget_name_id, c.name as budget_namee, d.id as budget_head_id, d.category as budget_head, b.* 
				FROM `sma_product_group` a, sma_budget b, sma_budget_name c, sma_budget_category d 
				where a.id = '$id' and a.budget_head = b.budget_category and a.budget_name = b.budget_name 
					and c.id = a.budget_name and d.id = a.budget_head and b.project = '$company_id' ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$budget_head_id 	= $r2->budget_head_id;
			$budget_name_id 	= $r2->budget_name_id;
			
			$budget_head 	= $r2->budget_head;
			$budget_name 	= $r2->budget_namee;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			//$balance_budget = $r2->balance_budget;
			
			$balance_budget	= $total_budget - ( $blocked_budget + $used_budget);//
			
			
            $budget_id 		 	= $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
			
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
									
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_head_id ?>" >
		
		<div class="well well-sm" >		
									
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Budget Name</label>
				<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_name ?>" >
			</div>
									
				<div class="col-sm-6">
				<label class="control-label">Budget Head</label>
				<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Blocked Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Used Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $used_budget ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Opening Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Balance Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemunits" id="itemUnits" readonly >
									<option value=""> Select </option>';

	    $sql = "SELECT * FROM sma_product where id = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			$r2 = mysqli_fetch_object($q2);
			$uom = $r2->uom;
			$description = $r2->name;
			$gst_type = $r2->gst_type;
            
		$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
		$sql = " SELECT * FROM `gst_mst` where id = '$gst_type' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$igst = $r2->igst;
//$value.=$sql;

        echo $uom. '-' . $description.'-'.$igst;
		
    }
	
    if(isset($_POST['sub27'])){
	
		$ap_id			= $_POST['approval_hdr_id'];
		$exp_type		= 'C';	
		$expence_name	= $_POST['expence_name'];
		$dated			= date('Y-m-d', strtotime($_POST['dated']));
		$amount			= $_POST['amount'];
		$spend_by		= $_POST['spend_by'];
		$advance_amount	= $_POST['advance_amount'];
		$invoice_nm		= $_POST['invoice_nm'];
		$remarks		= $_POST['remarks'];
		$gst_amount		= $_POST['gst_amount'];
		$budget_name	= $_POST['budget_name'];
		$budget_head	= $_POST['budget_head'];
		$budget_id		= $_POST['budget_id'];
		$emp_id			= $_POST['emp_id'];
		$comp_id		= $_POST['comp_id'];
		$dated_h		= date('Y-m-d', strtotime($_POST['dated_h']));
		
		$sql = " SELECT * from sma_budget where id = '$budget_id' ";
		$qry = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($qry);
		$total_budget 		= $r2->total_budget;
		$blocked_budget 	= $r2->blocked_budget;
		$used_budget 		= $r2->used_budget;
		$adjustment_budget 	= $r2->adjustment_budget;
		$balance_budget		= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);
		
		$sql="Insert into sma_approval_expenses ( exp_type ,approval_hdr_id, reference, dated, invoice_no, amount, spend_by, note, gst_amount, budget_name, budget_head, budget_id, total_budget, balance_budget ) values( 'C', '$ap_id', '$expence_name', '$dated', '$invoice_nm', '$amount', '$spend_by', '$remarks', '$gst_amount', '$budget_name', '$budget_head', '$budget_id',  '$total_budget', '$balance_budget' )";
		mysqli_query($con, $sql);
		echo mysqli_error($con);		

		$sql = " update sma_budget set blocked_budget = blocked_budget + $amount where id = '$budget_id' ";
		mysqli_query($con, $sql);

		$modulePath = "approval_adjustment/";
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id.'&active3=active';
	
		//$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
}	

   if(isset($_POST['sub28'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		//$provisional_flag = $_POST['provisional_flag'];
		
		$value = '';
		
		$sql   = "SELECT a.id as cat_id, a.account_name as product_category, c.id as budget_name_id, c.name as budget_namee, b.* 
				FROM `account_mst` a, sma_budget b, sma_budget_name c
				where a.id = '$id' and a.budget_code = b.budget_code and a.budget_name = b.budget_name 
					and c.id = a.budget_name and b.project = '$company_id' ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
		$row_affected  = mysqli_affected_rows($con);
		
			$r2 = mysqli_fetch_object($q2);
			//$budget_head_id 	= $r2->budget_head_id;
			$budget_name_id 	= $r2->budget_name_id;
			
			$budget_code 	= $r2->budget_code;
			$budget_head 	= $r2->budget_head;
			$budget_name 	= $r2->budget_namee;
			
			$total_budget 	= $r2->total_budget;
			$adjustment_budget 	= $r2->adjustment_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			
			$balance_budget	= ($total_budget + $adjustment_budget) - ( $blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
?>			
			
			<input type="hidden" class="form-control" id="row_AFFECTED" value="<?php echo $row_affected ?>" >
			
			<input type="hidden" class="form-control" id="company_id_a_exp" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id_exp" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget_exp" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget_exp" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Head_exp" readonly value="<?php echo $budget_head_id ?>" >
			<input type="hidden" class="form-control" id="budget_Name_exp" readonly value="<?php echo $budget_name_id ?>" >
			
			
		<div class="well well-sm" >		
								
			<div class="form-group">
				<div class="col-sm-4" style="text-align:left;">

					<label class="control-label">Cost Center Name</label>
					<input type="text" class="form-control" id="budget_name" readonly value="<?php echo $budget_name ?>" >
					
				</div>
				
				<div class="col-sm-2" style="text-align:left;">

					<label class="control-label">Cost Center Code</label>
					<input type="text" class="form-control" id="budget_code" readonly value="<?php echo $budget_code ?>" >
					
				</div>
									
				<div class="col-sm-6" style="text-align:left;">
					<label class="control-label">Cost Center Head</label>
					<input type="text" class="form-control" id="budget_head" readonly value="<?php echo $budget_head ?>" >
				
				</div>
				
			</div>
			
			<span id="gettotbudget">
				<!-- <div class="form-group">
					<div class="col-sm-6" style="text-align:left;">
					<label class="control-label">Blocked Budget </label>
					<input type="text" class="form-control" id="total_budget_a_E" readonly style="text-align:right;" value="<?php echo $blocked_budget ?>" >
					</div>
										
					<div class="col-sm-6" style="text-align:left;">
					<label class="control-label">Used Budget </label>
					<input type="text" class="form-control" id="balance_budget_a_E" readonly style="text-align:right;" value="<?php echo $used_budget ?>" >
					</div>
				</div>
				-->
				
				<div class="form-group">
					<div class="col-sm-4" style="text-align:left;">
					<label class="control-label">Opening Budget </label>
					<input type="text" class="form-control" id="total_budget_a_exp" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
					
					</div>
					
					<div class="col-sm-4" style="text-align:left;">
					<label class="control-label">Used Budget </label>
					<input type="text" class="form-control" id="total_budget_a_exp" style="text-align:right;" readonly value="<?php echo $used_budget ?>" >
					
					</div>
										
					<div class="col-sm-4" style="text-align:left;">
					<label class="control-label">Balance Budget </label>
					<input type="text" class="form-control" id="balance_budget_a_exp" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
					</div>
				</div>
			</span>
			
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

       // echo $value;
	   
    }
	
	

    if(isset($_POST['sub29'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM `account_mst` where id = '$id' ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$description 	= $r2->description;
?>
		<div class="form-group">
				<div class="col-sm-12" style="text-align:left;">
					<label class="control-label">Description</label>
					<input type="text" class="form-control" readonly value="<?php echo $description ?>" >
				</div>
			</div>
<?php			
			
	}
	

	
    if(isset($_POST['sub30'])){
    
        $product_id 	= $_POST['id'];
		$company_id 	= $_POST['company_id'];
		
		if($_POST['id'] == ''){$product_id = '';}
		
		$value = '';
		$sql = "SELECT * FROM sma_product where id = '$product_id' ";
//echo $sql."<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_code 	= $r2->budget_code;
        $product_id 	= $r2->id;
		$budget_name_id = $r2->budget_name;
		
		$sql = "SELECT * FROM sma_product_cost_center where product_id = '$product_id' and company_id = '$company_id ' ";
//echo $sql."<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_id 	= $r2->budget_id;
       
		$sql = "SELECT * from sma_budget_subgroup where id = '$budget_id' ";
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_array($q2);
		$budget_code  	= $r2['budget_code'];
		$budget_head_id = $r2['id'];
		$budget_head  	= $r2['budget_head'];
		$budget_name  	= $r2['budget_name'];
		
		//$sql = "SELECT * from sma_budget where budget_name = '$budget_name_id' and budget_code = '$budget_code' and project = '$company_id'";
		$sql = "SELECT * FROM sma_budget 
				WHERE account_year 	= '$finance_year' 
					AND project 	= '$company_id'
					AND budget_name	= '$budget_name' 
					AND budget_head	= '$budget_head_id' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$row_affected  = mysqli_affected_rows($con);
		$r2 = mysqli_fetch_array($q2);
		$budget_id  	= $r2['id'];
		$account_year  	= $r2['account_year'];
		$budget_code  	= $r2['budget_code'];
		//$budget_head  	= $r2['budget_head'];
		$budget_name  	= $r2['budget_name'];
		$total_budget  	= $r2['total_budget'];
		$adjustment_budget 	= $r2['adjustment_budget'];
		$blocked_budget = $r2['blocked_budget'];
		$used_budget  	= $r2['used_budget'];
		$balance_budget = ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);
		
		$sql 	= " SELECT * FROM sma_budget_name where 1 and id = '$budget_name' ";
		$q2  	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_object($q2);
		$name 	= $r2->name;
		$budget_name_id = $r2->id;
		
		if($row_affected==0){
			$budget_head = '';
			echo '<label class="control-label" style="color:red;" >Budget Not available for respective product...</label>';
		}

?>
		<div class="form-group">
		
			<div class="col-sm-2">
				<label for="itemName" class="control-label">Account Year</label>
				<input type="text" class="form-control" id="account_year" name="account_year" value = " <?=$account_year; ?>" readonly >
			</div>	
			<div class="col-sm-4">
				<label for="itemName" class="control-label">Cost Center Group</label>
				<input type="text" class="form-control" id="budget_group_name" name="budget_group_name" value = " <?=$name; ?>" readonly >
			</div>
			<div class="col-sm-2">
				<label for="itemName" class="control-label">Cost Center Code</label>
				<input type="text" class="form-control" id="budget_code" name="budget_code" value = " <?=$budget_code; ?>" readonly >
			</div>
			
			<div class="col-sm-4">
				<label for="itemName" class="control-label">Cost Center Name</label>
				<input type="text" class="form-control" id="budget_head_name" name="budget_head_name" value = " <?=$budget_head; ?>" readonly >
			</div>

		</div>	
		
		
			<input type="hidden" class="form-control" id="row_AFFECTED_a" value="<?php echo $row_affected ?>" >
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			
			<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_head_id ?>" >
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		

			<div class="form-group">
				<label class="control-label col-sm-2">Total Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
				
				<label class="control-label col-sm-2">Balance Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $balance_budget; ?>" >
				</div>
			</div>
		</div>	
		
<?php 
//$value=$sql;
		
        echo $value;
    }

    if(isset($_POST['sub30A'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name**</label>';
		$value .='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getcatbudgett(this.value)"  >
			<option value=""> Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id' and project = '$company_id' ";		
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name. "</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }

    if(isset($_POST['sub31'])){
		
        $budget_id 		= $_POST['id'];
		$company_id 	= $_POST['company_id'];
		$product_id 	= $_POST['product_id'];
		$budget_name	= $_POST['budget_name'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$budget_id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id 	= $r2->budget_name;
			
			$budget_head 		= $r2->budget_head;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			$adjustment_budget = $r2->adjustment_budget;
			
			$balance_budget	= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			
			<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_head_id ?>" >
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		

			<div class="form-group">
				<label class="control-label col-sm-2">Total Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<label class="control-label col-sm-2">Balance Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $balance_budget; ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
       // echo $value;
    }

    if(isset($_POST['sub32'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		//$value = '<label for="itemName" class="control-label">Cost Center Name**</label>';
		$value .='<select class="form-control" name="budget_id_exp" id="budget_ID_exp" required="true" onchange="getcatbudget_exp(this.value)"  >
			<option value="" selected > Select </option>';
//sma_budget_category
		$sql = "SELECT * from sma_budget where 1 and project = '$company_id'  and budget_name = '$id' order by budget_head "; 
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name. ' ' . $id."</option>";
        };
		$value .= '</select>';
		
        echo $value;
    }

   if(isset($_POST['sub33'])){
		
        $budget_id		= $_POST['id'];
		$company_id 	= $_POST['company_id'];
		$product_id 	= $_POST['product_id'];
		$budget_name	= $_POST['budget_name'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$budget_id' "; //and budget_category = '$budget_category' and project = '$company_id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id 	= $r2->budget_name;
			
			$budget_head_id 	= $r2->budget_category;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			$adjustment_budget = $r2->adjustment_budget;
			
			$balance_budget	= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_a_exp" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id_exp" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget_exp" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget_exp" readonly value="<?php echo $balance_budget ?>" >
			
			
			<input type="hidden" class="form-control" id="budget_Head_exp" readonly value="<?php echo $budget_head_id ?>" >
			<input type="hidden" class="form-control" id="budget_Name_exp" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		

			<div class="form-group">
				<label class="control-label col-sm-2">Total Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="total_budget_a_exp" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<label class="control-label col-sm-2">Balance Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="balance_budget_a_exp" readonly value="<?php echo $balance_budget; ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
       // echo $value;
    }
	
	if(isset($_POST['sub34'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		
		$sql 	= " SELECT * FROM `sma_workflow_type`  where doc_Type = 'DJ' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$trans_type = $r2['id'];
		
				$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
					and a.doc_type = 'DJ' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value
					and a.company_id = '$company_id' 
					and a.trans_type = '$trans_type' 
					";
//and a.trans_type = '$trans_type' 					
//echo $sql. "<BR>";
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];

		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
		}
		if($approval_role_4>0){
			$row_affected = $row_affected + 1;
			$required4 = 'REQUIRED';
		}
		if($approval_role_5>0){
			$row_affected = $row_affected + 1;
			$required5 = 'REQUIRED';
		}
		if($approval_role_6>0){
			$row_affected = $row_affected + 1;
			$required6 = 'REQUIRED';
		}
		if($approval_role_7>0){
			$row_affected = $row_affected + 1;
			$required7 = 'REQUIRED';
		}
		if($approval_role_8>0){
			$row_affected = $row_affected + 1;
			$required8 = 'REQUIRED';
		}

									
?>    
		<div class="box-footer">
					
					<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
					
								<div class="col-sm-1">
									<label class="control-label"><span style="color:red;">**</span></label>
								</div>
						<?php 
						
						if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
							<?php 		
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow a , sma_workflow_type b 
												where 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and a.company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_1 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
											
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php

								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_2 FROM 
												sma_workflow a , sma_workflow_type b 
												where 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and a.company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_2 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
										
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}	
							?>			
									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_3 FROM 
												sma_workflow a , sma_workflow_type b 
												where 1 and b.id = a.trans_type and b.status = 'Y'  
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_3 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_3" name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_4>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_4 FROM 
												sma_workflow a , sma_workflow_type b 
												where 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value 
												and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_4 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>			
								
									<select class="form-control  approver_4" name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_5 FROM 
												sma_workflow a , sma_workflow_type b 
												where 1 and b.id = a.trans_type and b.status = 'Y'  
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>

									<select class="form-control  approver_5" name="approver_5" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_6 FROM 
												sma_workflow a , sma_workflow_type b 
												WHERE 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>		
									<select class="form-control  approver_6" name="approver_6" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_7 FROM 
												sma_workflow a , sma_workflow_type b 
												WHERE 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>
							
									<select class="form-control  approver_7" name="approver_7" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_8 FROM 
												sma_workflow a , sma_workflow_type b 
												WHERE 1 and b.id = a.trans_type and b.status = 'Y' 
												and a.doc_type = 'DJ' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and a.trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
											and id != '$userid' 
											and FIND_IN_SET('$company_id', company_id ) ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>		
									<select class="form-control  approver_8" name="approver_8" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getsubmit();" >
								</div>
								
						</div>
						<br>
<?php						
	
	}

?>


<?php 
	if(isset($_POST['sub35'])){
		$modulePath = "approval_adjustment/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$ap_id		 		= $_POST['ap_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page					= $_POST['page']; 		
		$baseurl .=$modulePath.'edit.php?sub=edit&id='.$ap_id.'&page='.$page.'&active8=active';
		
		

		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$ap_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
				
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_approval_memo where id = '$ap_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];				
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$approver_id 			= $r2['id'];
		include("comment_mail.php");
		
		if(!empty($approver_1_status)){
			$approver_id		= $rw['approver_1'];	
			include("comment_mail.php");
		}		
		if(!empty($approver_2_status)){
			$approver_id		= $rw['approver_2'];	
			include("comment_mail.php");
		}
		if(!empty($approver_3_status)){
			$approver_id		= $rw['approver_3'];	
			include("comment_mail.php");
		}
		if(!empty($approver_4_status)){
			$approver_id		= $rw['approver_4'];	
			include("comment_mail.php");
		}
		if(!empty($approver_5_status)){
			$approver_id		= $rw['approver_5'];	
			include("comment_mail.php");
		}
		if(!empty($approver_6_status)){
			$approver_id		= $rw['approver_6'];	
			include("comment_mail.php");
		}
		if(!empty($approver_7_status)){
			$approver_id		= $rw['approver_7'];	
			include("comment_mail.php");
		}
		if(!empty($approver_8_status)){
			$approver_id		= $rw['approver_8'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
} 
  if(isset($_POST['sub36'])){
    
        $company_id 	= $_POST['id'];
		$overhead_exp	= $_POST['overhead_exp'];
		
		if($_POST['id'] == ''){$company_id = '';}
		
		$value = '';
		
		if( $overhead_exp=='N' ){
			$sql = " SELECT DS.pono, DS1.dated, DS.product_id, DS.approval_no, DS.po_value, DS1.apno, DS.project as company_id, DS1.ap_value FROM
					(
					(SELECT a.id as pono, a.dated, b.product_id, a.approval_memo_ref as approval_no, a.project , 
					round(sum( (b.quantity * b.unit_rate) + (( b.quantity * b.unit_rate) *  b.gst / 100 ) ),2) as po_value FROM sma_purchase_order a, `sma_po_items` b where a.del !='Y' and a.id = b.purchase_id and po_type = 'A' and a.project = '$company_id' group by a.id ) DS,
					(SELECT a.id as apno, a.dated, b.product_id, a.company, 
					round( sum( (b.quantity * b.unit_rate) + (( b.quantity * b.unit_rate) *  b.gst / 100 )) ,2) as ap_value  FROM sma_approval_memo a, `sma_approval_items` b where 1 and a.del !='Y' and overhead_exp = 'N' and a.id = b.approval_hdr_id and a.company = '$company_id' group by a.id  ) DS1
					) 
				WHERE DS.approval_no = DS1.apno AND DS.product_id = DS1.product_id AND po_value < ap_value ";
		}
		else if( $overhead_exp=='Y' ){
			$sql = " SELECT DS.pono, DS.dated, DS.product_id, DS.approval_no, DS.po_value, DS1.apno, DS.project as company_id, DS1.ap_value FROM
					(
					( SELECT a.id as pono, a.dated, b.reference as product_id, approval_number as approval_no, a.company_id as project, round(sum(b.amount + b.gst_amount),2) as po_value FROM `sma_travel_expenses` a, sma_expenses b where a.del !='Y' and a.exp_Type = 'C' and a.id = b.approval_ref_no and a.company_id = '$company_id' group by a.approval_number ) DS,
					( SELECT a.id as apno, a.dated, b.product_id, a.company, 
					round( sum( (b.quantity * b.unit_rate) + (( b.quantity * b.unit_rate) *  b.gst / 100 )) ,2) as ap_value  FROM sma_approval_memo a, `sma_approval_items` b where 1 and a.del !='Y' and overhead_exp = 'Y' and a.id = b.approval_hdr_id and a.company = '$company_id'  group by a.id  ) DS1
				) WHERE DS.approval_no = DS1.apno AND DS.product_id = DS1.product_id AND po_value < ap_value ";
		}
		
		$spcs = '';
		for($ij=0;$ij<15;++$ij){
			$spcs .= '&nbsp;';
		}
		
 //and a.id = 869 and approval_number = 869
?>
		
		<select class="form-control" name="apmemo_no" id="apmemo_no" required onchange="viewapmemo(this.value);" >
			<option value=""> Select</option>
			<option value="">AP Memo &nbsp; Date</option>
			
			<?php 
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['apno'];?>" ><?php echo $r2['apno']. $spcs . $r2['dated'] ;?></option>
			<?php } ?>
		</select>
<?php

    }

 if(isset($_POST['sub37'])){
    
		$ap_id 			= $_POST['id'];
		$overhead_exp	= $_POST['overhead_exp'];
		$baseurl1 = $baseurl.'approval/'.'edit.php?sub=edit&id='.$ap_id;
		
?>		<div class="col-sm-2">
															
			<label class="control-label">&nbsp; </label>
			<a href="<?php echo $baseurl1;?>" class="form-control btn btn-success" target="_blank" >Approval Memo View</a>
		</div>							
		
<?php
    }

?>

