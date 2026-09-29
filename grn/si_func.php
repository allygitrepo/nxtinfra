<?php session_start();
	include('../dbcon.php');
//	$baseurl = "http://localhost:80/hc_template/";    //dev url
	
	include "../baseurl.php";
	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
	$comid  = $_SESSION['comid'];
	$userid   			= $_SESSION['usrid'];
	
	$finance_year = $_SESSION['finance_year'];
echo " ";	
?>

<?php
		
  if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$sql = "select * from sma_party_mst where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$supplier_gst_no = $r2->party_gst_number;
		$party_gst_number_twodgt = substr($supplier_gst_no,0,2);
		$readonly = '';
/* 		
echo $sql = "select distinct(a.id), a.po_number, a.dated  
				FROM sma_purchase_order a, sma_po_items b 
					WHERE a.id = b.purchase_id and a.project in ($comid) and a.project = '$company_id' 
						and a.status in ( 'Completed' ) and a.to_supplier = '$id'
						AND (( b.quantity > b.bal_si_qty ) 
						OR ( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount ) )";		 */	

?>
			<div class="col-md-2">
				<label class="control-label">GST .No.</label>
				<input type="text" class="form-control" id="supplier_gst_no" name="supplier_gst_no" placeholder="" readonly value="<?= $supplier_gst_no ?>" >
			</div>

<?php

		
?>							
			<div class="col-md-4">
				<label class="control-label">Our PO Ref.No.</label>
				<select class="form-control" name="our_po_ref_no" id="our_po_ref_NO" onchange="getcreditdays(this.value)" <?= $readonly; ?>>
				
<?php
			
			echo '<option value=""> Select </option>';
			$sql = "select distinct(a.id), a.po_number, a.dated  
				FROM sma_purchase_order a, sma_po_items b 
					WHERE a.id = b.purchase_id and a.project in ($comid) and a.project = '$company_id' 
						and a.status in ( 'Completed' ) and a.to_supplier = '$id'
						AND (( b.quantity > b.bal_si_qty ) 
						OR ( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount ) )";				
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$our_po_ref_no 	= $r2->po_number;
				$dated 			= $r2->dated;
				$id		 		= $r2->id;
?>			
				<option value="<?= $id ?>"> <?= $our_po_ref_no. '-' . $dated ?></option>
<?php       };
			
		?>
				</select>
			</div>
<?php
//		echo $value;
	
	}

	if(isset($_POST['sub2'])){
	
		$value ='';
       if($_POST['purchase_id'] == ''){$purchase_id = '';}
		
		$ida = explode('-',$_POST['purchase_id']);
		
		$material_id     = $ida['1'];
		$purchase_id     	 = $ida['0'];
		
		if(empty($material_id)){
			$material_id = $_POST['id'];
		}
		
		$grn_srn_hdr_id 		= $_POST['grn_srn_hdr_id'];
		$name 			= $_POST['name'];
		$description 	= $_POST['description'];
		//$account_year   = $_POST['account_year'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_id      = $_POST['budget_id'];
		$budget_head    = $_POST['budget_head'];
		$qty 			= $_POST['qty'];
		$units 			= $_POST['units'];
		$rate 			= $_POST['rate'];
		$gst 			= $_POST['gst'];
		$gst_id 		= $_POST['gst_id'];
		
		$sql = " select * from sma_grn_srn where id = '$grn_srn_hdr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$company_id 		= $r2['company_id'];
		
		//$material_id	= $_POST['id'];
		$sql = "SELECT * FROM sma_product where `id` = '$material_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$name = $r2['name'];
		
		$sql = " SELECT * FROM sma_budget  where id = '$budget_id' ";
			$re2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($re2);
			$budget_id 		= $r1['id'];
			$budget_name	= $r1['budget_name'];
			$budget_head    = $r1['budget_head'];
			
		$gstamt 		= round((($qty * $rate) * $gst /100),0);
		$amount 		= ($qty  * $rate) + $gstamt;
		
		$sql = "insert into `sma_grn_srn_details` (grn_srn_hdr_id, purchase_id, material_id, material_name, description, company_id, budget_name, budget_head, qty, unit, rate, gst, gst_id, amount, budget_id ) values ('$grn_srn_hdr_id', '$purchase_id', '$material_id', '$name', '$description', '$company_id', '$budget_name', '$budget_head', '$qty','$units','$rate','$gst', '$gst_id', '$amount', '$budget_id')";
		$r2 = mysqli_query($con, $sql);

		$si_amount = 0;
		
		$sql = "select * from sma_grn_srn_details where grn_srn_hdr_id = '$grn_srn_hdr_id'";
		$r2 = mysqli_query($con, $sql);
		while($r1 = mysqli_fetch_array($r2)){
			$si_amount = $si_amount + $r1['amount'];
		}
		
		if($si_amount > 0){
			$sql = " update `sma_grn_srn` set total_amount = '$si_amount', bal_amount = '$si_amount' where id = '$grn_srn_hdr_id' and paid_status != 'Paid' ";
			$r2 = mysqli_query($con, $sql);
//echo $sql;	
		}

			$our_po_ref_no = $purchase_id;

				$sql = "SELECT * FROM company where comp_id = '$company_id' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$budget_control_gst = $r2['budget_control_gst'];
				
			$sql = " update sma_budget set used_budget= used_budget + $amount where id = '$budget_id' ";
			$q3  = mysqli_query($con, $sql);
			
			if (!empty($our_po_ref_no) ){

				$sql = "update sma_po_items set bal_si_qty  = bal_si_qty + $qty where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";	
				$query=mysqli_query($con, $sql);

				$sql = " update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
				$q3  = mysqli_query($con, $sql);
			}
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   	= $r4->blocked_budget;
			if($block_budget<0){
				$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
				$q3  = mysqli_query($con, $sql);
			}
		
//exit();

//$value = $value1; &active=active
		$value = "<script>window.location.href='edit.php?sub=edit&id=$grn_srn_hdr_id';</script>";
	
		echo $value;
				
	}

	if(isset($_POST['sub3'])){
	
		$value ='';
        $dtl_id = $_POST['id'];
		$siid = $_POST['siid'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql = "SELECT * FROM `sma_grn_srn` where id = '$siid' ";
		$sires = mysqli_query($con, $sql);
		echo 	 mysqli_error($con);
		$sr    = mysqli_fetch_array($sires);
		$status 			= $sr['status'];
		$our_po_ref_no		= $sr['our_po_ref_no'];
		$company_id 		= $sr['company_id'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$sql = "SELECT * FROM `sma_grn_srn_details` where grn_srn_hdr_id = '$siid' and grn_srn_srno = '$dtl_id' ";
		$sires = mysqli_query($con, $sql);
		echo 	 mysqli_error($con);
		while($sr    = mysqli_fetch_array($sires)){
			
			$material_id 		= $sr['material_id'];
			$budget_head 		= $sr['budget_head'];
			$budget_id 			= $sr['budget_id'];
			$si_qty 			= $sr['qty'];
			$si_rate 			= $sr['rate'];
			$si_gst 			= $sr['gst'];
			
			$gstamt = round((($si_qty * $si_rate) * $si_gst / 100),0);
			if($budget_control_gst!='Y'){
				$gstamt = 0;
			}	
			$si_amount = $si_qty * $si_rate + $gstamt;
			
			if( $si_amount <= 0 ){
				$si_amount = 0;
			}
				
			/* if( $against_po_flag!='Y' ){
				$sql = "update sma_budget set used_budget = used_budget - $si_amount where id = '$budget_id' ";
				mysqli_query($con, $sql);
			} */
	
				$sql = "update sma_po_items set bal_si_qty  = bal_si_qty - $si_qty where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";

				$query=mysqli_query($con, $sql);		

		}

		$sql="delete from sma_grn_srn_details where grn_srn_srno = '$dtl_id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		
//$value = $value1;
		//echo "<meta http-equiv='refresh' content='0'>";    
		
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$siid';</script>";
		
		echo "<script>window.location.href='edit.php?sub=edit&id=$siid';</script>"; 
		
		echo $value;
		
		exit();
		
?>
		
<?php
		
	}

    if(isset($_POST['sub4'])){
    
        $id 		= $_POST['id'];
		$supplier_id = $_POST['suplier_id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_purchase_order where id = '$id' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$credit_days 			= $r2->credit_days;
		$supplier_location 		= $r2->location;
		$company_id 			= $r2->project;
		$department 			= $r2->department;
		$subject	 			= $r2->subject;
		$location 				= $r2->location;
		$dated	 				= date('d-m-Y', strtotime($r2->dated));
		$advance_paid_amount 	= $r2->paid_amount;
		$trans_type				= $r2->trans_type;
		
            //$credit_days ='123';
        //$value = '<input type="text" class="form-control" id="credit_days" name="credit_days" readonly style="text-align:right;" value="'.$credit_days.'" >';
		
		$sql = " select * from sma_location where id = '$location' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$loc_id 	= $r2->id;
		$loc_name 	= $r2->loc_name;
		$loc_gst_no = $r2->loc_gst_no;
        $loc_gst_no_twodgt = substr($loc_gst_no,0,2);
		
        $sql = " select * from sma_party_mst where id = '$supplier_id' ";
//echo $sql;		
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
			$supplier_location = 'O';
		}	
	
//echo $sql = "select * from company where comp_id in ($comid) and comp_id = '$company_id' order by comp_name ";
		
?>
	
		<input type="hidden" id="location_id" name="location_id" value="<?= $loc_id; ?>" >
							
		<div class="col-md-2">
			<label class="control-label">Location</label>
			<input type="text" class="form-control" readonly  value="<?= $loc_name; ?>" >
		</div>
	
<!--		<div class="col-md-4">
			<label for="project" class="control-label">Company</label>
			<select class="form-control select2" name="company_id" id="company_ID" onchange="getworkflowtype(this.value)" readonly required >
			<?php $sql = "select * from company where comp_id in ($comid) and comp_id = '$company_id' order by comp_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['comp_id'];?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
			<?php } ?>
			</select>
		</div>
-->

		<div class="col-md-3">
			<label class="control-label">Department</label>
			<select class="form-control" readonly  name="department" id="department" >
			<?php $sql = "select * from sma_department where 1 and id = '$department' order by name ";
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" <?php echo ($department == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
			<?php } ?>
			</select>
		</div>
							
		<div class="col-md-2">
			<label class="control-label">Tax Status</label>
			<input type="hidden" class="form-control" id="supplier_location" name="supplier_location"  value="<?= $supplier_location; ?>" >
			<input type="text" class="form-control" readonly  value="<?= $supplier_location_dis; ?>" >			
		</div>
								
		<div class="col-md-1">
			<label class="control-label">Credit&nbsp;Days</label>
			<input type="text" class="form-control" readonly  id="credit_days" name="credit_days"  maxlength="3" value="<?= $credit_days; ?>" style="text-align:right;">
								
		</div>
<!--		<div class="col-sm-2">
			<label for="company_id" class="control-label ">Workflow Type</label>
			<select class="form-control select3" name="trans_type" id="trans_type" READONLY required >
			
			<?php $sql = "select * from sma_workflow_type where id = '$trans_type' ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" <?php echo ($trans_type == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
			</select>										
		</div>-->
		
		<div class="col-md-6">
			<label class="control-label">Subject</label>
			<input type="text" class="form-control" readonly  value="<?= $subject; ?>" >
								
		</div>
		
	
<?php							
							
$value = '##'.$company_id.'##'.$advance_paid_amount.'##'.$dated;
        echo $value;
	   
    }

	
	if(isset($_POST['sub9'])){

		$modulePath = "grn/"; 
	
		$value ='';
        $si_id = $_POST['si_id'];
		$srno  = $si_id;
		if($_POST['si_id'] == ''){$si_id = '';}
		
		$mode		 	= $_POST['mode'];
		$department 	= $_POST['department'];
		$approver 		= $_POST['approver'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		$approved 		= $_POST['approved'];
		$our_po_ref_no	= $_POST['our_po_ref_no'];
		
		$user   		= $_SESSION['user'];
		$userid   		= $_SESSION['usrid'];
		$user_name_by 	= $_SESSION['user_name_by'];
		
		$sql = " select * from sma_grn_srn where id = '$si_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$our_po_ref_no 		= $r2['our_po_ref_no'];
		$company_id 		= $r2['company_id'];
		$supplier_invoice_no	= $r2['supplier_invoice_no'];
		$invoice_date		= $r2['invoice_date'];
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
		
//echo $to_approver. ' ' .$approval_status."<BR>"; 
//exit();

	// Without PO Ref.no
	//empty($our_po_ref_no)
	if($approval_status =='Approved' || $mode =='Reject' ){
					
		$sql="SELECT * from sma_grn_srn_details where grn_srn_hdr_id = '$si_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		$gst_amt = 0;
		while($rowd = mysqli_fetch_array($result)){
			
			$material_id 		= $rowd['material_id'];
			$budget_head 		= $rowd['budget_head'];
			
			$qty 	= $rowd['qty'];
			$rate 	= $rowd['rate'];
			$gst	= $rowd['gst'];
			
			if(empty($gst)){
				$gst = 0;
			}	
			
			$gst_amt = round((($qty * $rate) * $gst / 100),0);
			
			$sql = "SELECT * FROM company where comp_id = '$company_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$budget_control_gst = $r2['budget_control_gst'];
				
			$amount  = $qty * $rate + $gst_amt;
			$values  = $amount;
			$budget_head_id = $rowd['budget_name'];	
			$budget_id		= $rowd['budget_id'];			
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_object($q3);
			
			if($mode =='Reject'){
				
				$sql = "update sma_budget set used_budget = used_budget - $values, blocked_budget = blocked_budget + $values  where id = '$budget_id' ";
				mysqli_query($con, $sql);	

				/* $sql = "select * from sma_budget where id = '$budget_id' ";
				$q4  = mysqli_query($con, $sql);
				$r4  = mysqli_fetch_object($q4);
				$block_budget   	= $r4->blocked_budget;
				$used_budget   	= $r4->used_budget;
				if($block_budget<0){
					$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
					$q3  = mysqli_query($con, $sql);
				}
				
				if($used_budget<0){
					$sql = "update sma_budget set used_budget = 0 where id = '$budget_id' ";
					$q3  = mysqli_query($con, $sql);
				} */
				
				$sql="update sma_po_items set bal_si_qty  = bal_si_qty - $qty, bal_si_amount  = bal_si_amount - $values where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id'";
				$query=mysqli_query($con, $sql);		
				echo mysqli_error($con);
		
			}
		}
	}

//echo $sql. "<BR>";
//exit();
	$ipc_flag = '';
	if($status	== 'Completed'){
		$sql = " select * from sma_user where userid in (select draft_by from sma_grn_srn where id = '$si_id') ";	
		$q4  = mysqli_query($con, $sql);
		$r4  = mysqli_fetch_object($q4);
		$draft_by_id   	= $r4->id;
		
		$to_approver	= $draft_by_id;
		
		$s="select * from sma_user where id='$userid' ";	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$approve_by_name 		= $r->username;
			$approve_by_email		= $r->email;
		}
		
		$ipc_flag = 'Y';
		
	}
	
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		
		if($mode =='Reject'){
			
			$sql = " update sma_grn_srn set approver_1 = '', approver_2 = '', 
			approver_3 = '', approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', 
			approver_8 = '', approver_1_status='Submitted', approver_2_status='', approver_3_status='', 
			approver_4_status='', approver_5_status='', approver_6_status='', approver_7_status='',
			approver_8_status='', status = 'Draft', approval_status = 'Rejected', changed_by = '$user', 
			current_approver = '$draft_by_id', changed_date = now() where id = '$si_id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql = "update sma_grn_srn_details set qty = 0 where grn_srn_hdr_id = '$si_id' ";
			mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
			values('SI', '$si_id', '$userid', now(), 'Rejected', '$draft_by_id', '$remarks', now() )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$to_approver = $draft_by_id;
			
		}	
		else{
			$sql = " update sma_grn_srn set $status_field = '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver = '$to_approver', changed_date = now(), ipc_flag = '$ipc_flag' $sqla where id = '$si_id'";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);

			if(!empty($error)){echo $error; exit();}

			$sql = " INSERT into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
			values('SI', '$si_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now() )";		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
		}
//echo $sql."<BR>";
//exit();		

		$s="select * from sma_user where id='$to_approver' ";
//echo $s;
//exit();		
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$si_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
		
		
		$msg = 'GRN SRN  Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date('d-m-Y', strtotime($invoice_date));
//echo $user_email;
//EXIT ('EXIT HERE...');

		include "si_mail.php";
		
		$role		= $_SESSION['role']; 
//			$baseurl1 = $baseurl."dashboard.php?sub=dash";
//			echo "<script>window.location.href='$baseurl1';</script>";

			$baseurl1 = $baseurl.$modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		
		$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		//echo $value.$sql;
		exit();

	}
	
//Tally Entry Create
	if(isset($_POST['sub10'])){

		$modulePath = "grn/";
		$grn_srn_hdr_id 	= $_POST['grn_srn_hdr_id'];
		$gst_flag	= $_POST['gst_flag'];
	
		$sql 	= "SELECT * FROM payment_header a, `payment_details` b WHERE supp_id = '$grn_srn_hdr_id' and b.payment_hdr_id = a.id and a.st_flag = 'S' and del !='Y' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$value = "<script>alert('User should not create tally ... Already payment entry done !');window.location.href='edit.php?sub=edit&id=$grn_srn_hdr_id&IN=in';</script>";
			echo $value;
			exit();
		}
	
		$sql 	= "select * from tally_journal_entry where doc_no = '$grn_srn_hdr_id' and doc_type = 'SI' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$grn_srn_hdr_id' and doc_type = 'SI' ";
			$q2 	= mysqli_query($con, $sql);
		}
//echo $sql."<BR>";
		$sql 	= "UPDATE sma_grn_srn set gst_flag = '$gst_flag' where id = '$grn_srn_hdr_id' ";
		mysqli_query($con, $sql);
		
		$sql 	= "select * from sma_grn_srn where id = '$grn_srn_hdr_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_id 	= $r2['supplier_name'];
		$invoice_date   = date('d-m-Y', strtotime($r2['invoice_date']));
		$supplier_invoice_no = $r2['supplier_invoice_no'];
		$company_id 		 = $r2['company_id'];
		$tally_narration 	 = $r2['tally_narration'];
		$supplier_location 	 = $r2['supplier_location'];
		$gst_flag		 	 = $r2['gst_flag'];
		
		$deduction1_id 	 = $r2['deduction1_id'];
		$deduction2_id 	 = $r2['deduction2_id'];
		$deduction3_id 	 = $r2['deduction3_id'];
		
		$deduction1_amount 	 = $r2['deduction1_amount'];
		$deduction2_amount 	 = $r2['deduction2_amount'];
		$deduction3_amount 	 = $r2['deduction3_amount'];
		
		$sql = "select * from sma_party_mst where id = '$supplier_id' ";
		$q2 	  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$party_name 	= $r2['party_name'];
		$account_id 	= $r2['id'];
		$gst_no			= $r2['party_gst_number'];
		$state			= $r2['party_state'];
		$address		= $r2['party_address_1'];
		$mobile_no		= $r2['party_mobile'];
		$pan_no			= $r2['party_pan_number'];
		
		$tot_amount = 0;
		$prev_budget_head = '';
		$last_insert_id = '';
		$account_name_prev = '';
		$sql="SELECT * from sma_grn_srn_details where 1 and qty > 0 and grn_srn_hdr_id = '$grn_srn_hdr_id' order by budget_id, grn_srn_srno desc";
//echo $sql. "<BR>"; 
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";

		$i = 1;
		while($row = mysqli_fetch_array($result)){
			
			$qty 			= $row['qty'];
			$rate 			= $row['rate'];
			$gst			= $row['gst'];
			$gst_id			= $row['gst_id'];
			$material_id	= $row['material_id'];
			if(empty($gst)){
				$gst =0;
			}
					
			$budget_head 	= $row['budget_head'];
			$budget_id 		= $row['budget_id'];
		
			$sql = " SELECT * FROM `gst_mst` where 1 and `id` = '$gst_id' ";
//echo $sql. "<BR>";			
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$sgst   			= $r2['sgst'];
			$cgst   			= $r2['cgst'];
			$igst   			= $r2['igst'];
			$sgst_account_id   	= $r2['sgst_account_id'];
			$cgst_account_id   	= $r2['cgst_account_id'];
			$igst_account_id   	= $r2['igst_account_id'];
			
			//if($supplier_location=='L'){
			$sql    = "SELECT * FROM account_mst where id = $sgst_account_id ";
//echo $sql. "<BR>";						
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$sgst_account_id  	= $r2['id'];
			$sgst_account_name  = $r2['account_name'];
			$sgst_account_type	= $r2['account_type'];
			
			$sql    = "SELECT * FROM account_mst where id = $cgst_account_id ";
//echo $sql. "<BR>";						
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$cgst_account_id  	= $r2['id'];
			$cgst_account_name  = $r2['account_name'];
			$cgst_account_type	= $r2['account_type'];
			
			$sql    = "SELECT * FROM account_mst where id = $igst_account_id ";
//echo $sql. "<BR>";			
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$igst_account_id  	= $r2['id'];
			$igst_account_name  = $r2['account_name'];
			$igst_account_type	= $r2['account_type'];
			
			$sql = "SELECT * FROM `sma_budget` where id = '$budget_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$budget_head  = $r2['budget_head'];
//exit();			
			//$sql    = "SELECT a.id , b.budget_code FROM `sma_product` a, sma_product_group b,  where a.id = '$material_id' and a.`product_group` = b.id ";
			$sql  = "SELECT a.product_id, b.* FROM `sma_product_cost_center` a, sma_budget b where product_id = '$material_id' and company_id = '$company_id' and a.budget_id = b.id
			 ";
//echo $sql."<BR>"; exit(); //AND b.id = '$budget_id'
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id  = $r2['product_id'];
			$budget_code  = $r2['budget_code'];
			//$budget_head = ($r2['category']);//mysql_real_escape_string
			
			$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_head' ";
			$cqry = mysqli_query($con,$sql);
			$com = mysqli_fetch_array($cqry);
			$budget_code 			= $com['budget_code'];
			
			$gst_amt = round((($qty * $rate) * $gst / 100),0);
			$amount = round( ($qty * $rate),0);
			
			//$amount_gst = $amount_gst + $amount;
			
			$sgst_amt = 0;
			$cgst_amt = 0;
			if($supplier_location=='L'){
				$sgst_amt = round($gst_amt/2, 2);
				$cgst_amt = round($gst_amt/2 ,2);
			}
			
			$tot_amount = $tot_amount + $amount + $gst_amt;
			$effect 			= "Dr";
			$record_type 		= "Purchase-P2P";
			$doc_no				= $grn_srn_hdr_id;		
			$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$supp_invoice_no	= $supplier_invoice_no;
			$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
			$account_type		= 'A';
			$account_id			= $account_id;
			$account_name       = $budget_code;
			$effect				= $effect;
			
			if($gst_flag=='Y'){
				$sgst_amt = 0;
				$cgst_amt = 0;	
				$igst_account_name = $account_name;
				//$sgst_account_name = $account_name;
				//$cgst_account_name = $account_name;
			}
			
			$cheque_no			= '';
			
			if( $budget_code == $budget_code && $account_name_prev == $account_name &&
				$sgst_account_name_prev == $sgst_account_name &&
				$cgst_account_name_prev == $cgst_account_name &&
				$igst_account_name_prev == $igst_account_name ){
				
				$sql = "update tally_journal_entry set amount =  amount + $amount where record_id = '$last_insert_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				echo $sql. "<BR>";
			}
			else {
				
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
				//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
//echo $sql."<BR>";
			}
			
//echo $last_insert_id_cgst . ' ' . $last_insert_id_sgst. ' ' . $cgst_amt. ' ' . $sgst_amt ."<BR>";
			if( $igst_account_name_prev == $igst_account_name && $gst_amt > 0 && $last_insert_id_igst > 0){
				
				$sql = "update tally_journal_entry set amount =  amount + $gst_amt where record_id = '$last_insert_id_igst' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				echo $sql. " GST<BR>";//exit();
			}
			else if( $sgst_account_name_prev == $sgst_account_name && $sgst_amt > 0){
				
				$sql = "update tally_journal_entry set amount =  amount + $sgst_amt where record_id = '$last_insert_id_sgst' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. " SGST<BR>";
				
				$sql = "update tally_journal_entry set amount =  amount + $cgst_amt where record_id = '$last_insert_id_cgst' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. " CGST<BR>";
				
			}
			else if( $cgst_account_name_prev == $cgst_account_name && $cgst_amt > 0){
				
				$sql = "update tally_journal_entry set amount =  amount + $sgst_amt where record_id = '$last_insert_id_sgst' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. " SGST<BR>"; 
				
				$sql = "update tally_journal_entry set amount =  amount + $cgst_amt where record_id = '$last_insert_id_cgst' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				//echo $sql. " CGST<BR>";
			}
			else if( ($supplier_location=='O' || $gst_flag=='Y' ) && $gst_amt > 0){
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$igst_account_type', '$igst_account_id', '$igst_account_name',   '$effect', '$gst_amt', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
				//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id_igst = mysqli_insert_id($con);
				echo mysqli_error($con);
//echo $sql."<BR>";				
			}
			else if($supplier_location=='L' && $gst_amt > 0){
				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$cgst_account_type', '$cgst_account_id', '$cgst_account_name',   '$effect', '$cgst_amt', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
				//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id_sgst = mysqli_insert_id($con);
				echo mysqli_error($con);
//echo $sql."<BR>";

				$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, budget_head ) 
				VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$sgst_account_type', '$sgst_account_id', '$sgst_account_name',   '$effect', '$sgst_amt', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$pan_no', '$mobile_no', '$budget_head' ) ";
				//echo $sql."<BR>";
				mysqli_query($con, $sql);
				$last_insert_id_cgst = mysqli_insert_id($con);
				echo mysqli_error($con);
//echo $sql."<BR>";				
			}	
				
			$sgst_account_name_prev	= $sgst_account_name;
			$cgst_account_name_prev	= $cgst_account_name;
			$igst_account_name_prev	= $igst_account_name;
			$prev_budget_head 		= $budget_head;
			$prev_budget_code		= $budget_code;
			$account_name_prev		= $account_name;
	
		}
		
		if($deduction1_id>0){
			$sql    = "SELECT * FROM account_mst where id = $deduction1_id ";					
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id  	= $r2['id'];
			$account_name   = $r2['account_name'];
			$account_type	= $r2['account_type'];
			$percentage		= $r2['percentage'];
			//$deduction1_amount = $tot_amount * $percentage / 100;
		}	
		//echo $sql."<BR>"; exit();
		$record_type 		= "Purchase-P2P";
		$doc_no				= $grn_srn_hdr_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		$supp_invoice_no	= $supplier_invoice_no;
		$supp_invoice_date	= date('d-m-Y', strtotime($invoice_date));
		$account_type		= 'V';
		$account_id			= $supplier_id;
		$account_name       = $party_name;
		$effect				= 'Cr';
		$amount				= round($tot_amount,0);
		$narration			= $narration;
		$cheque_no			= '';
		
		 $amount = $amount - ($deduction1_amount + $deduction2_amount + $deduction3_amount);
		 $sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
		 VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql 	= " update sma_grn_srn set payable_amount = '$amount', tally_status='R', bal_amount = '$amount', tally_created_by = '$userid', tally_created_date = now() where id = '$grn_srn_hdr_id' ";
		mysqli_query($con, $sql);
		
		if($deduction1_id>0){
			$sql    = "SELECT * FROM account_mst where id = $deduction1_id ";					
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id  	= $r2['id'];
			$account_name   = $r2['account_name'];
			$account_type	= $r2['account_type'];
			$percentage		= $r2['percentage'];
			
			//$deduction1_amount = $tot_amount * $percentage / 100;
			$effect				= 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
			VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name', '$effect', '$deduction1_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql."<BR>";			
		}
		if($deduction2_amount>0){
			$sql    = "SELECT * FROM account_mst where id = $deduction2_id ";					
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id  	= $r2['id'];
			$account_name   = $r2['account_name'];
			$account_type	= $r2['account_type'];
			
			$effect				= 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
			VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name', '$effect', '$deduction2_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql."<BR>";			
		}
		if($deduction3_amount>0){
			$sql    = "SELECT * FROM account_mst where id = $deduction3_id ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$account_id  	= $r2['id'];
			$account_name   = $r2['account_name'];
			$account_type	= $r2['account_type'];
			
			$effect				= 'Cr';
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, budget_head) 
			VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_type', '$account_id', '$account_name', '$effect', '$deduction3_amount', '$tally_narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$budget_head' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql."<BR>";
		}
		
//echo $sql."<BR>";
//exit();

?>
	
<?php
	
		$value = "<script>window.location.href='edit.php?sub=edit&id=$grn_srn_hdr_id&IN=in';</script>";
	
		echo $value;
		
	}
					

	if(isset($_POST['sub11'])){
		
		$modulePath = "grn/";
		$actype = $_POST['id'];
		if($actype =='A'){
			$sql = "SELECT id, account_name as 'account_name' FROM account_mst where account_type = 'E' or account_type = 'A' or account_type = 'D' order by account_name ";
		
		}
		else if($actype =='V'){
		
			$sql = "SELECT id, party_name as 'account_name' FROM sma_party_mst order by party_name ";
		}
		else if($actype =='B'){
		
			$sql = "SELECT id, category as 'account_name' FROM sma_budget_category order by category ";
		}
?>	
		<div class="col-sm-12">
        <label for="approver" class=" control-label">Account Name </label>                                        
			<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)" >
			    <option value="">Select</option>
			<?php
			    $result = mysqli_query($con, $sql);
			    echo mysqli_error($con);
			    while($r3 = mysqli_fetch_array($result)){
			?>	
				<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'] ?></option>
			<?php } ?>
			</select>
	
		</div>
	
<?php 											
	}	


	if(isset($_POST['sub12'])){
		
		$modulePath 	= "grn/";
		$account_type 	= $_POST['type_ac'];
		$account_id 	= $_POST['account_id'];
		$account_name	= ($_POST['account_name']);//mysql_real_escape_string
		$effect 		= $_POST['effect'];
		$amount 		= round($_POST['amount'],0);
		//$narration 		= $_POST['narration'];
		$grn_srn_hdr_id 		= $_POST['grn_srn_hdr_id'];
//echo $amount. "<<>>";
		$sql 	= "select * from sma_grn_srn where id = '$grn_srn_hdr_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$invoice_date   = date('d-m-Y', strtotime($r2['invoice_date']));
		$supp_invoice_no = $r2['supplier_invoice_no'];
		$company_id		 = $r2['company_id'];
		$narration 		 = $r2['tally_narration'];
		$supplier_id	 = $r2['supplier_name'];
		$gst_flag		 = $r2['gst_flag'];
		
		$record_type 		= "Purchase-P2P";
		$doc_no				= $grn_srn_hdr_id;		
		$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
		
		$cheque_no			='';
		$address 			='';
		$gst_no 			='';
		$state				='';
		
		$sql="SELECT * FROM account_mst where id  = '$account_id' ";
		
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$retention_flag		= $r2['retention_flag'];
		$deduction_from		= $r2['deduction_from'];
		
		if($retention_flag=='Y'){
			$sql = "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$party_name 	= $r2['party_name'];
			$gst_no			= $r2['party_gst_number'];
			$state			= $r2['party_state'];
			$address		= $r2['party_address_1'];
			$mobile_no		= $r2['party_mobile'];
			$pan_no			= $r2['party_pan_number'];
			
			$account_type	= 'V';
			$account_name = $account_name.'- '.$party_name;
			
		}
		else {
			$account_type 	= $_POST['type_ac'];
		}	
		
		$sql = "SELECT * FROM account_mst where (account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//echo $sql. "<BR>";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage 	= $r3['percentage'];
		$account_type_a 	= $r3['account_type'];
		$deduction_from		= $r3['deduction_from'];

//echo deduction_from. ' ' . $tds_percentage . '> 0 '.  $account_type_a ."<BR>";

		if($amount >0 && ($effect=='Cr' || $effect=='Dr') && $deduction_from =='V'){
			$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and val_type = 'V' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			$sql 	= " update sma_grn_srn set payable_amount =  payable_amount - '$amount', bal_amount = bal_amount - '$amount' where id = '$grn_srn_hdr_id' ";
			mysqli_query($con, $sql);
		
		}
		else if($amount >0 && ($effect=='Cr' || $effect=='Dr') && $deduction_from =='A'){
			$sql = " update tally_journal_entry set amount = amount - $amount where doc_type = 'SI' and doc_no = '$doc_no' and effect = 'Dr' and account_type = 'A' and account_id = '$account_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
		}

		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name,   effect, amount, narration, cheque_no, address, gst_no, state, company_id, mobile_no, pan_no)
				VALUES('$record_type', 'SI', '$doc_no', '$doc_date', '$supp_invoice_no', '$invoice_date', '$account_type', '$account_id', '$account_name',   '$effect', '$amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', '$company_id', '$mobile_no','$pan_no' ) ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
		$sql_retention = '';		
		if($retention_flag=='Y'){
			$sql_retention = " , retention_amount = '$amount', retention_flag = 'Y' ";
		}	
		if($effect=='Cr' && $retention_flag=='Y' ){
			$sql 	= " update sma_grn_srn set retention_amount = '$amount', retention_flag = 'Y' where id = '$grn_srn_hdr_id' ";
			mysqli_query($con, $sql);
		}
		else if( $retention_flag=='Y' ){
			$sql 	= " update sma_grn_srn set retention_amount = '$amount', retention_flag = 'Y' where id = '$grn_srn_hdr_id' ";
			mysqli_query($con, $sql);
		}
		
		
		$value = "<script>window.location.href='edit.php?sub=edit&id=$grn_srn_hdr_id';</script>";
	
		echo $value;
	//	https://hcone.co.in/workflow2020/grn/edit.php?sub=edit&id=1901
	
	}

	if(isset($_POST['sub13'])){
		
		$modulePath 	= "grn/";

		$account_id 	= $_POST['id'];
		$amount_dr 		= $_POST['amount_dr'];
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
//	echo $sql;		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['percentage'];
		if($tds_percentage > 0){
			
			$tds_amount = $amount_dr * $tds_percentage / 100;
			//echo $tds_amount;
			echo '';
?>
			<input type="text" class="form-control amountA " id="amountA" autocomplete="off" style="text-align:right;" readonly name="amount" value="<?php echo $tds_amount ?>" >

<?php			
		}
		else {
?>			
			<input type="text" class="form-control amountA" id="amountAB" style="text-align:right;" readonly name="amount" value="" >
<?php
		}	//id="amountA"

	}

	if(isset($_POST['sub14'])){
		$company_id 	= $_POST['company_id'];
		
?>
		<select class="form-control" name="supplier_name" id="suplier_NAME" required onchange="getporefno(this.value); getstate(this.value)">
		<option value=""> Select </option>
		<?php 
			$sql = "select * FROM sma_party_mst where 1 AND id in ( select distinct(to_supplier) as to_supplier FROM sma_purchase_order a, sma_po_items b where a.id = b.purchase_id and  a.status in ( 'Completed' ) AND a.project = '$company_id' 
			AND ( (b.quantity > b.bal_si_qty ) OR 
			( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount ) ) ) 
			order by party_name  ";
		
				$q2 	  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
		<?php } ?>
		</select>		
<?php		
	}
?>		

