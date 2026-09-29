<?php 
	session_start();
	
	error_reporting(0);
	
	include('../dbcon.php');
	
	include('../baseurl.php');
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
//	include('../header.php');

	$userid   			= $_SESSION['usrid'];		
	
	$finance_year = $_SESSION['finance_year'];
		
?>

<?php
	  if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getbudgetheada(this.value)"  >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.name from sma_budget a, sma_budget_name b where project = '$id' and b.id = a.budget_name order by project";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

		$value=$sql;

        echo $value;
    }
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		//$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
	
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
		$value .='<select class="form-control" name="budget_name" id="budget_NAME" required="true" onchange="getcatbudget(this.value)"  >
			<option value="" selected > Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id' ";		//and project = '$company_id' 
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }

    if(isset($_POST['sub1A'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
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

	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$value = '';
		$value ='<select class="form-control" name="budget_head" id="budget_Head" >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.category from sma_budget a, sma_budget_category b where b.id = a.budget_category and budget_name = '$id' ";
		//in (select budget_name from sma_budget where id = '$id') group by project, category";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->category;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$id.' '.$name."</option>";
        };
		$value .= '</select>';
//$value = $sql;
		echo $value;
		
	}

    if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemName" id="itemName" required="true" onchange="getunit1(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT * FROM sma_product where `group` = '$id' ORDER BY name ASC";
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

   if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemName" id="itemName" required="true" onchange="getunit2(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT id, name FROM sma_product where `group` = '$id' ORDER BY name ASC";
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
		
	if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$sql="SELECT * FROM sma_product where id = '$id' ";							

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$uom 		= $r2->uom;
			$gst_type   = $r2->gst_type;
			$tolerance_level = $r2->tolerance_level;
			$po_threashold 	 = $r2->po_threashold;
			//$account_name 	 = $r2->account_name;
			$account_name 	 = '';
 		
		
		$value = $uom.'##'.$account_name.'##'.$tolerance_level.'##'.$po_threashold;
		
		//$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
//$value.=$sql;
        echo $value;
    }

    if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		$company_id    = $_POST['company_id'];
		
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
			$description = $r2->description;
			$gst_type = $r2->gst_type;
            
		$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
//$value.=$sql;

		$sql="SELECT * FROM `gst_mst` where id = '$gst_type' ";
//echo $sql;						
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$igst = $r2['igst'];
		$igst_id = $r2['id'];
		
        echo $uom. '-' . $description.'-'.$igst.'-'.$igst_id;
		
    }

    if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="location" id="LOCATION" required="true" onchange="getdelvaddr(this.value)" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$loc_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_location where id = '$id' ";

		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$loc_addr = $r2->loc_addr1;
		if(!empty($r2->loc_addr2)){
			$loc_addr .= $r2->loc_addr2.',';
		}
		if(!empty($r2->loc_addr3)){
			$loc_addr .= $r2->loc_addr3.',';
		}
		if(!empty($r2->loc_city)){
			$loc_addr .= $r2->loc_city.',';
		}
		if(!empty($r2->loc_pincode)){
			$loc_addr .= '-'.$r2->loc_pincode;
		}
?>		
		<div class="col-md-6">
			<label class="control-label">Delivery Address</label>
			<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address" <?php echo $readonly; ?> ><?= $loc_addr;?></textarea>
		</div>
							
<?php	
//$value=$sql;

//        echo $value;
    }
	
    if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		$po_type = $_POST['po_type'];
		if($_POST['id'] == ''){$id = '';}
//getqref(this.value);getloc(this.value);		
		$value = '';
		$sqla = '';
		
		if($po_type=='A'){
			$sqla = "AND id in ( SELECT b.supplier_name 
					FROM `sma_approval_memo` a, `sma_approval_details` b, sma_approval_items c 
						WHERE 1 AND a.id = b.approval_hdr_id AND a.company = '$id' AND b.vendor_selected = 'Y' AND a.overhead_exp !='Y'
						AND c.approval_hdr_id = a.id
						AND a.approval_status = 'Approved'
						AND c.po_quantity < c.quantity
						AND c.po_value < ((c.quantity * c.unit_rate) + ((c.quantity * c.unit_rate) * c.gst / 100)) )";
		}
//$sql = " select * from sma_party_mst where 1 ". $sqla ;
//echo $sql;		
		$value ='<select class="form-control" name="to_supplier" id="to_supplier" onchange="get_taxstatus(this.value);getapproval(this.value);"  required >
									<option value=""> Select ..</option>';

	    //$sql = " select * from sma_party_mst where id in (select supplier_name from sma_approval_details where approval_hdr_id = '$id' and vendor_selected = 'Y' ) "; //party_kyc = 'Y' and 
		$sql = " select * from sma_party_mst where 1 and party_kyc = 'Y'  ". $sqla ;
//echo $sql;		 select * from sma_party_mst where 1 and party_kyc = 'Y'
		$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
			$party_name = $r2->party_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$party_name."</option>";
        };
		
		$value .= '</select>';

//$value = $sql;

        echo $value;
		
    }
	
    if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		$location = $_POST['location'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = " select * from sma_location where id = '$location' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$loc_gst_no = $r2->loc_gst_no;
        $loc_gst_no_twodgt = substr($loc_gst_no,0,2);
		
        $sql = " select * from sma_party_mst where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$party_gst_number = $r2->party_gst_number;
		$party_gst_number_twodgt = substr($party_gst_number,0,2);
		
		if($party_gst_number_twodgt==$loc_gst_no_twodgt){
			$supplier_location_dis = 'Local';
			$supplier_location = 'L';
		}
		else {
			$supplier_location_dis = 'Out of State';
			$supplier_location = 'Out of State';
		}	
		$value .= '<input type="text" class="form-control" readonly value="'. $supplier_location_dis . '" >';
		$value .= '<input type="hidden" id="supplier_location" name="supplier_location" value="'. $supplier_location . '" >';
		
//$value = $sql;

        echo $value;
		
    }
		
	if(isset($_POST['sub9'])){

		$modulePath = "tender/";
	
		$value ='';
        $tender_id = $_POST['tender_id'];
		$srno  = $tender_id;
		if($_POST['tender_id'] == ''){$tender_id = '';}
		
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approved 			= $_POST['approved'];
		//RAVI $approverC			= $_POST['approver'];
		$approver 			= $_POST['approver'];

		$company			= $_POST['company'];		
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_tender_header where id = '$tender_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
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
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		if( $approver_1== $approver ){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
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
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
//echo $approval_status."<BR>"; 
//exit();		

	if($statusap=='Reject'){
		
		$approval_status	= 'Rejected';
		$status				= 'Rejected';
		$flow_flag 			= 'R';
		
		$sql = "update sma_tender_header set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',
			approval_status = '$approval_status', status = '$status', current_approver='$draft_by_id' where id = '$tender_id' ";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'TN', '$tender_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_tender_header set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', current_approver='$to_approver' $sqla where id = '$tender_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'TN', '$tender_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}

	}
	
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
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

		$modulePath = "tender/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$tender_id;
		$msg = 'Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		include "tn_mail.php";
		
		$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		exit();
		
	}

	if(isset($_POST['sub10'])){

		$value ='';
        $tender_id 			= $_POST['tender_id'];
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approver			= $_POST['approver'];
		
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];

		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Draft';
		}

		$sql = "update sma_tender_header set flow_flag = 'P', approval_status = '$approval_status', status = '$status' where id = '$tender_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql;
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) values('TN', '$tender_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql;
		
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

		$modulePath = "tender/"; 
		
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$tender_id;

		
		$msg = '<br> For Checker, Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");
//echo $msg;
//exit("Stoped by Ravindra...");

		include "tn_mail.php";
	
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}
				
    if(isset($_POST['sub12'])){
    
        $supplier_id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		$po_type = $_POST['po_type'];
		if($_POST['id'] == ''){$id = '';}


		$value = '';
		if($po_type=='A'){
			$value .='<select class="form-control select2" name="approval_memo_ref" id="approval_memo_Ref" onchange="getquoteref(this.value);" >
							<option value=""> Select </option>';
			$sql = "SELECT distinct(a.id),  a.dated
				FROM `sma_approval_memo` a, `sma_approval_details` b, sma_approval_items c
					WHERE 1 AND a.id = b.approval_hdr_id AND a.company = '$company_id' 
					AND b.supplier_name ='$supplier_id' AND b.vendor_selected = 'Y' 
					AND a.status = 'Completed' AND a.overhead_exp!='Y' 
					AND c.approval_hdr_id = a.id
					AND c.po_quantity < c.quantity
					AND c.po_value < ((c.quantity * c.unit_rate) + ((c.quantity * c.unit_rate) * c.gst / 100))"; 
//and a.id not in ( select approval_memo_ref from sma_tender_header where 1 and project = '$company_id' ) order by a.id desc";

			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$dated = $r2->dated;
				$id = $r2->id;
				$value .='<option value="'.$id.'" > '. $id.' ('. date('d-m-Y', strtotime($dated)). ')</option>';
			};
			$value .= '</select>';
		}
		else{
			$value = '<input type="text" class="form-control"  name="approval_memo_ref" id="approval_memo_Ref" READONLY value="">';
		}	
        echo $value;
		
		
    }



//Blocked para	
	if(isset($_POST['sub13'])){

		$value ='';
        $tender_id 			= $_POST['tender_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		if($status=='Completed'){
			
			$sql="SELECT * from sma_tender_header where id = '$tender_id' ";
	//echo $sql."<br>";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$row = mysqli_fetch_array($result);
			$po_number	 	= $row['po_number'];
			
			$sql="SELECT * from sma_po_items where purchase_id = '$tender_id' ";
	//echo $sql."<br>";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			while($row = mysqli_fetch_array($result)){
				$product_id 	= $row['product_id'];
				$qty 			= $row['quantity'];
													
				$rate 	= $row['unit_rate'];
				$gst	= $row['gst'];
				$po_amount = $qty * $rate + (($qty * $rate) * $gst / 100);
				
				$sql = "SELECT * FROM `sma_supplier_invoice_details` where material_id = '$product_id' and si_hdr_id in ( SELECT id FROM `sma_supplier_invoice` where our_po_ref_no = '$po_number' or our_po_ref_no = '$tender_id' )";
	//echo $sql."<br>";

				$sires = mysqli_query($con, $sql);
				echo 	 mysqli_error($con);
				$sr    = mysqli_fetch_array($sires);
				$material_id 	= $sr['material_id'];
				$budget_head 	= $sr['budget_head'];
				$si_qty 			= $sr['qty'];
				$si_rate 			= $sr['rate'];
				$si_gst 			= $sr['gst'];
				
				$si_amount = $si_qty * $si_rate + (($si_qty * $si_rate) * $si_gst / 100);
				if($si_amount <= 0 ){
					$si_amount = 0;
				}
				
				if($budget_head>0){
					$sql = "update sma_budget set total_budget = total_budget + ($po_amount - $si_amount) where id = '$budget_head' ";
					mysqli_query($con, $sql);
				}
	//echo $sql."<br>";
				
			}
		}
		
		$sql = "update sma_tender_header set close_flag = 'Y', flow_flag = 'B', approval_status = 'Blocked' where id = '$tender_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql."<br>";
		
//Send mail to approver;
		
		$modulePath = "tender/"; 
		
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$tender_id;

		
//		$msg = '<br> For Checker, Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");
//echo $msg;
//exit("Stoped by Ravindra...");

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}
	
	
    if(isset($_POST['sub14'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$product_id = $_POST['product_id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql = "SELECT * FROM sma_product where id = '$id' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$name = $r2->name;
			$product_id   = $r2->id;
			$budget_code   = $r2->budget_code;
		
		$sql = "SELECT * FROM sma_product_cost_center where product_id = '$product_id' and company_id = '$company_id' ";
//echo $sql."<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_id 			= $r2->budget_id;
		$budget_head_id 	= $r2->budget_id;
		
		$sql = " SELECT * FROM sma_budget_subgroup where id = '$budget_head_id' ";
//echo $sql."<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_name_id 	= $r2->budget_name;
		$budget_head	 	= $r2->budget_head;
		
		//$sql   = "SELECT * FROM sma_budget where id = '$budget_id' ";
		$sql   = "SELECT * FROM sma_budget where account_year = '$finance_year' and budget_head = '$budget_head_id' and budget_name = '$budget_name_id' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$row_affected  = mysqli_affected_rows($con);
			$r2 = mysqli_fetch_object($q2);
			$budget_code   	= $r2->budget_code;
			$budget_name_id = $r2->budget_name;
			
			//$budget_head 	= $r2->budget_head;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			$adjustment_budget 	= $r2->adjustment_budget;
			

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
			
			if($row_affected==0 ){
?>				
				<div class="form-group">
					
					<input type="hidden" class="form-control" id="row_affected_a" value="<?php echo $row_affected ?>" >
					
					<div class="col-sm-6">
						<label class="control-label" style="color:red;">Budget not available for product !!!</label>
					</div>
				</div>
<?php				
				exit();
			}
			
			if($balance_budget == 0 ){
?>				
				<div class="form-group">
					
					<div class="col-sm-6">
						<label class="control-label" style="color:red;">No sufficient Budget available for product !!!</label>
					</div>
				</div>
<?php				
				
			}	
?>			
			
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_BUDGET" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			<input type="hidden" class="form-control" id="budget_Head" readonly value="<?php echo $budget_head_id ?>" >
			
		<div class="well well-sm" >		
									
			<div class="form-group">
				<div class="col-sm-4">
					<label class="control-label">Cost Center Group</label>
					<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_name ?>" >
				</div>
				
				<div class="col-sm-5">
					<label class="control-label">Cost Center Name </label>
					<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head; ?>" >
				</div>
				
				<div class="col-sm-3">
					<label class="control-label">Balance </label>
					<input type="text" class="form-control" style="text-align:right;" readonly value="<?= number_format($balance_budget,2); ?>" >
				</div>
				
			</div>
		
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

       // echo $value;
    }


    if(isset($_POST['sub14A'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$product_id = $_POST['product_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id 	= $r2->budget_name;
			
			$budget_head 	= $r2->budget_head;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			
			$balance_budget	= $total_budget - ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            $_SESSION['budget_id']= $budget_id ;
			
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
			
			<input type="hidden" class="form-control" id="company_id_aa" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" id="budget_ida" name="budget_ida" value="<?= $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budgeta" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budgeta" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Namea" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Total Budget </label>
				<input type="text" class="form-control" id="total_budget_aa" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Balance Budget </label>
				<input type="text" class="form-control" id="balance_budget_aa" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
<?php 								

    }

	
	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc 	 = $_POST['party_id_doc'];
		$company_idd_doc = $_POST['company_idd_doc'];
		
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
		$sql = "SELECT a.id, a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><a href="<?php echo $baseurl.'dms/'.$row['file_path'].'/'.$row['file_name'];?>" target="_blank"><?php echo $row['file_name'];?></a> </td>
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

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		$po_type	 		= $_POST['po_type'];
		$department	 		= $_POST['department'];
//echo $po_type;		
		$doc_type = 'TN';
			

		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' 
					and trans_type = '$trans_type'";	
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

		$sql_dept1 = '';$sql_dept2 = '';$sql_dept3 = '';$sql_dept4 = '';$sql_dept5 = '';$sql_dept6 = '';$sql_dept7 = '';$sql_dept8 = '';
		if($approval_role_1=='42'){
			$sql_dept1 = " and department = '$department' ";
		}
		if($approval_role_2=='42'){
			$sql_dept2 = " and department = '$department' ";
		}
		if($approval_role_3=='42'){
			$sql_dept3 = " and department = '$department' ";
		}
		if($approval_role_4=='42'){
			$sql_dept4 = " and department = '$department' ";
		}
		if($approval_role_5=='42'){
			$sql_dept5 = " and department = '$department' ";
		}
		if($approval_role_6=='42'){
			$sql_dept6 = " and department = '$department' ";
		}
		if($approval_role_7=='42'){
			$sql_dept7 = " and department = '$department' ";
		}
		if($approval_role_8=='42'){
			$sql_dept8 = " and department = '$department' ";			
		}

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
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where FIND_IN_SET( ( SELECT approval_role_1 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value 
											and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_1 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept1 ";
										//and id 			!= '$userid' 	
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_1" id="APPROVER_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_2 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_2 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept2";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_2" id="APPROVER_2"  name="approver_2" <?= $required2; ?> >
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
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_3 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_3 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept3";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_3" id="APPROVER_3"  name="approver_3" <?= $required3; ?> >
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
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_4 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_4 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept4";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_4" id="APPROVER_4"  name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_5 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept5";
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
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_6 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept6";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
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
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_7 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept7";
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
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where FIND_IN_SET( ( SELECT approval_role_8 FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept8";
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
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getvalidate();getsubmit();" >
								</div>
							
						</div>
						<br>
<?php						
	
	}

	if(isset($_POST['sub25'])){
		$company_id = $_POST['id'];
?>
		
		<select class="form-control select2" id='sterms' onchange="getspecialterms(this.value);" >
			<option value=""> Select </option>
			<?php $sql = "select * from sma_term where company_id = '$company_id' ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" ><?php echo $r2['special_terms'];?></option>
			<?php } ?>
		</select>

<?php
	}


	if(isset($_POST['sub26'])){

		include('../header.php');
	
		$term_id = $_POST['id'];
		
		$sql = "select * from sma_term where id = '$term_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$special_terms = $r2['special_terms'];
?>
		<div class="box-body">
			<textarea class="form-control" id="reason" name="terms" ><?= $special_terms;?></textarea>
		</div>


<?php
//include("../footer1.php");
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

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


</script>
  		
<?php 
	}
		
    if(isset($_POST['sub27'])){
    
        $company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
?>
		<select class="form-control select3" name="po_doc_type" id="po_doc_type" required >
			<option value=""> Select </option>
			<?php $sql = "select * from sma_workflow where doc_type = 'TN' order by trans_type ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['trans_type'];?></option>
			<?php } ?>
		</select>
	
<?php
	}
	
   if(isset($_POST['sub3A'])){
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			
//echo $sql = "SELECT id, name FROM sma_product where `product_group` = '$id' ORDER BY name ASC";

		$value = '';	
?>
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit2(this.value);getcatbudget123(this.value);" >
			<option value=""> Select.. </option>
<?php
			$sql = "SELECT id, name FROM sma_product where `product_group` = '$id' ORDER BY name ASC";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>
		
<?php
//        echo $value;
    }
	
	
	if(isset($_POST['sub4a'])){
	
		$value ='';
        $id 		= $_POST['id'];
		$tender_hdr_id = $_POST['tender_hdr_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_tender_supplier where id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
//exit();
		
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$tender_hdr_id';</script>";
		echo $value;
		
	}	
	
	if(isset($_POST['sub4b'])){
	
		$value ='';
        $id 		= $_POST['id'];
		$tender_hdr_id = $_POST['tender_hdr_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_tender_terms where id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
//exit();
		
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$tender_hdr_id';</script>";
		echo $value;
		
	}	

	if(isset($_POST['sub4c'])){
	
		$value ='';
        $id 		= $_POST['id'];
		$tender_hdr_id = $_POST['tender_hdr_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_tender_items where id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
//exit();
		
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$tender_hdr_id';</script>";
		echo $value;
		
	}	
	
	if(isset($_POST['sub28'])){
	
		$value ='';
        $id = $_POST['id'];
		$approval_hdr_id = $_POST['id'];
			
		$sql = "SELECT a.approval_hdr_id, a.quote_ref_no, b.department , b.trans_type
					FROM `sma_approval_details` a, sma_approval_memo b 
						WHERE 1 and approval_hdr_id = approval_hdr_id and a.vendor_selected ='Y' and b.id = a.approval_hdr_id and b.id = '$approval_hdr_id' ";		
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$quote_ref_no = $r2->approval_hdr_id;
		$department	  = $r2->department;
		$quote_ref_no = $r2->quote_ref_no;
		$trans_type   = $r2->trans_type;
?>

		<div class="col-md-3">
			<label class="control-label">Department <span style="color:red;"> **</span></label>
			<select class="form-control" name="department" id="department" READONLY required >
			
			<?php $sql = "select * from sma_department where id = '$department' order by name ";
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" <?php echo ($department == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
			<?php } ?>
			</select>
		</div>
										
		<div class="col-md-3">
			<label class="control-label">Supplier Quote Ref.No.</label>
		    <span id="getqref">
			<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" READONLY value="<?= $quote_ref_no ?>" >
			</span>
											
		</div>
									
		<div class="col-sm-4">
			<label for="company_id" class="control-label ">Workflow Type</label>
			<select class="form-control select3" name="trans_type" id="trans_type" READONLY required >
			
			<?php $sql = "select * from sma_workflow_type where doc_type = 'TN' and id = '$trans_type' ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" <?php echo ($trans_type == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
			</select>										
		</div>
<?php										
//		echo $quote_ref_no;
		
	}			
?>	

<?php 
	if(isset($_POST['sub35'])){
		$modulePath = "tender//";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$tender_id		 		= $_POST['tender_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page				= $_POST['page']; 		
		$baseurl .=$modulePath.'edit.php?sub=edit&id='.$tender_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$tender_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_tender_header where id = '$tender_id' ";
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


 if(isset($_POST['sub29'])){
    
        $doc_type = $_POST['doc_type'];
		if($_POST['id'] == ''){$id = '';}
		
		
?>
		<select class="form-control select3" name="trans_type" id="trans_type" required >
            <option value=""> Select.. </option>
			<?php $sql = "select * from sma_workflow_type where doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" > <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>
	
<?php

	}
	
	if(isset($_POST['sub99'])){

		$modulePath = "tender/";
	
		$value ='';
        $tender_id = $_POST['tender_id'];
		$srno  = $tender_id;
		if($_POST['tender_id'] == ''){$tender_id = '';}
		
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approved 			= $_POST['approved'];
		$approver 			= $_POST['approver'];
		$company			= $_POST['company'];		
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_tender_header where id = '$tender_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		$draft_by 			= $r2['draft_by'];
		$tender_title 		= $r2['tender_title'];
		$deadline_date		= date('d-m-Y', strtotime($r2['deadline_date']));
		$deadline_time		= $r2['deadline_time'];
		$approver_1			= $r2['approver_1'];
		$approver_2			= $r2['approver_2'];
		$approver_3			= $r2['approver_3'];
		$approver_4			= $r2['approver_4'];
		
			
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst']; 
		$comp_name 			= $r2['comp_name']; 
			
		$sql = " SELECT * from sma_user WHERE userid = '$draft_by' ";	
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		//require '../PHPMailerAutoload.php';
		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$sql="select * from sma_tender_supplier where tender_hdr_id = '$tender_id' ";

		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r22 =mysqli_fetch_array($rt)){
			
			$to_supplier 	= $r22['supplier_id'];
		
			$sql = " SELECT * from sma_party_mst WHERE 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		
			/* $modulePath = "athaangSI/tender/"; 
			$baseurl1 =$baseurl.$modulePath.'editSI.php?id='.$tender_id. '&direct=D&supplier_id='.$to_supplier;
			 */
			$encrypted = encryptIt( $to_supplier );
	
			$modulePath = "athaangSI/tender/"; 
			$baseurl1 = $baseurl.$modulePath.'editSI.php?id='.$tender_id. '&direct=D&supplier_id='.$encrypted;
			
			$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 8px 12px;text-align: center;font-weight: 400;" >Click here to view the tender </a>';
			
			$msg = 'Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");

			include "tn_vender_mail.php";
			
		}
		
		$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date )
			values( 'TN', '$tender_id', '$userid', now(), 'Published', '$approver_1', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		$sql = "UPDATE sma_tender_header SET status = 'Published' WHERE id = '$tender_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");
		
		$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		exit();
		
	}
	
if(isset($_POST['sub30'])){
    
    $supplier_id = $_POST['id'];
	if($_POST['id'] == ''){$supplier_id = '';}
	$sql = " SELECT * from sma_party_mst WHERE 1 and id = '$supplier_id' " ;
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_object($q2);
	$party_name 	= $r2->party_name;
	$party_email 	= $r2->party_email;
?>
	<label for="itemquote_ref_no" class="col-sm-4 control-label">Email ID </label>
    <div class="col-sm-8">
		<input type="text" class="form-control" name="email_id" readonly value="<?= $party_email;?>" >
	</div>
								
<?php			
}		



//$input = "29";
//$encrypted = encryptIt( $input );
//$decrypted = decryptIt( $encrypted );

//echo $encrypted . '<br />' . $decrypted;

function encryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}

?>	

