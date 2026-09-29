<?php
include("../header.php");
$modulePath = "retention/";

$_SESSION['reset'] = '1';

$user       = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];
$dedtype    = $_GET['dedtype'];

$si_id      = $_GET['id'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";

	$pgname = $help_code;
	include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	if(isset($_POST['Save'])){
		
	
			$id				= $_POST['id']; 
			$si_id			= $_POST['id'];
						
			$supplier_invoice_no	= $_POST['supplier_invoice_no'];
			$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$created_date			= date('Y-m-d', strtotime($_POST['created_date']));
			$suplier_name			= $_POST['suplier_name'];
			$comp_id				= $_POST['comp_id'];
			
//	echo $tally_status. "<<>>";		exit();

			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
		
			
			$location_id		= $_POST['location_id'];
			$status				= $_POST['status'];

// 		if ( $status == 'Draft' ){
//   			$sql="update sma_retention_invoice set 1 = 1 where si_hdr_id = '$id'";
// 				$query=mysqli_query($con, $sql);
// 				$error= mysqli_error($con);
// 				if(!empty($error)){echo $error; exit();}
			
// 		}
				
//exit();
			
			if( !empty($approver_1) && $status == 'Draft' ){
				
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_retention_invoice set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = "INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, inward_status )
									VALUES( 'RI', '$si_id', '$userid', now(), 'Draft' , 'edit.php') ";
				mysqli_query($con, $sql);
				
				$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					VALUES( 'RI', '$si_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
					
				$modulePath = "retention/";
				
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
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$si_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$si_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Supplier Invoice Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");

				include "py_mail.php";		
					
			}
			
			// add attachments
			// file upload
			
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			
			$arrFUDoc 			= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++){
				
				$folder_path = "uploads/si/" . $si_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} 
				$filename 		= $arrFUDoc['name'][$i];
				$tmpFileName 	= $arrFUDoc['tmp_name'][$i];
				if(!empty($filename)){
						
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc,  reference_id, date_uploaded) 
					VALUES( 'RI', '$filename', '$folder_path',  '$arrDocType[$i]', '$arrDocDesc[$i]', '$si_id', now() )";
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, $folder_path. "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
		
				}

			}

			$sql 	= "select * from sma_party_mst where id = '$suplier_name' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $supplier_invoice_no. ','. $invoice_date. ',' .$department. ','. $party_name;
		    $affect 		= 'Add';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			                VALUES ('$user_name',NOW(),'$main_menu', '$sub_menu', '$comp_id', '$description', '$affect')";
		    mysqli_query($con, $sql);
			
			$page					= $_POST['page']; 		
			$baseurl.=$modulePath.'index.php?sub=list&same_page='.$page;
			
			echo "<script>window.location.href='$baseurl';</script>";
		
		}
		
		$page = $_GET['page'];
		$id	 		= $_GET['id'];
		$si_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';

		}
		if($_GET['active5']){
			$active_tab1 ='';
			$active_tab2 = '';
			$active_tab5 = $_GET['active5'];
		}
		
	
	    $sql="Select * from sma_retention_invoice where id ='$id'";
	
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
//echo $sql. "<BR>";	
		$status = $row['status'];
		$our_po_ref_no = $row['our_po_ref_no'];
		
		
		$approval_status = $row['approval_status'];
		$del 	= $row['del'];
		$draft_by 		= $row['draft_by'];
		$tally_status 	= $row['tally_status'];
		$tally_updated_on	= $row['tally_updated_on'];
		$tally_ticked_by 	= $row['tally_ticked_by'];
		
		$dated  	= date('d-m-Y', strtotime($row['created_date']));
		$fyr		= date('Y', strtotime($dated));
		$fmth		= date('m', strtotime($dated));
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}
		$_SESSION['finance_year'] = $fin_year;
		
		$vendor_id 		= $row['suplier_name'];
		$company_id 	= $row['company_id'];	
		$dedtype        = $row['dedtype'];

		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		
		
		$si_id = $row['id'];
		$readonly = '';
		if ( $status == 'Submitted' || $status == 'Completed' ){
			$readonly = 'READONLY';
		}
		if (($status == 'Submitted' ) || $status == 'Completed' ){
			$disabled = 'DISABLED';
		}
	
		if ( $status == 'Draft' ){
			$readonly_draft = 'READONLY';
		}
		
		if($approval_status=='Rejected'){
			$readonly = 'READONLY';
		}		
	
		if($del == 'Y'){
			$readonly = 'READONLY';
		}
		
		$readonlyf = '';
		$readonly1 = 'READONLY';
		if ( $status=='Completed' || $tally_status == 'C' || $tally_status =='U' ){
			//$accountant_role=='Y' && || $status=='Submitted'
		//	$readonly1 = 'READONLY';
			$readonlyf = 'READONLY';
		}
	
		
// 		$sql 	= "SELECT * FROM payment_header a, `payment_details` b WHERE supp_id = '$si_id' and b.payment_hdr_id = a.id and a.st_flag = 'S' and del !='Y' ";
// 		$q2 	= mysqli_query($con, $sql);
// 		$row_affected = mysqli_affected_rows($con);
// 		if($row_affected>0){
// 		    $readonly1 = 'READONLY';
// 		}
		
		if ( $status=='Draft' ){
			$readonly1 = '';
			$readonly = '';
		}
		
		if($role=='Journal F&A'){
			$readonlyf ='';
		}

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
             <?= $sub_menu;?>
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> <?= $sub_menu;?></a></li>
            
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->

					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
					<?php
						$status   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
						
						if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active_tab1 = '';
							$active_tab2 = '';
							$active_tab5 = '';
						}
					?>
					<?php if($approval_status=='Rejected'){ ?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $approval_status;?></b></h4> </span>
					<?php }
					else {
					?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $stats .' ' .$status;?></b></h4> </span>
					<?php } ?>
					
					<input type="hidden" name="status" id="statuS" value="<?php echo $status;?>" >
					<input type="hidden" name="page"  value="<?php echo $page;?>" >
					<input type="hidden" id="dedType" name="dedtype"  value="<?php echo $dedtype;?>" >
					
					
				<?php 
					$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
				?>	
					<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		      
                    <input type="hidden" name="id" value="<?php echo $row['id'];?>">
			
				<?php

								$our_po_ref_no = $row['our_po_ref_no'];
								if($against_po_flag=='N'){
									$our_po_ref_no ='';	
								}
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$advance_paid_amount	= $r1['paid_amount'];
								$total_po_amount		= $r1['total_po_amount'];
								$paid_against_invoice	= $r1['paid_against_invoice'];
								$location 			= $r1['location'];
								$our_po_ref_no_a  	= $r1['po_number'];
								$po_rev			 	= $r1['po_rev'];
								
								$po_rev_a 	=='';
								if($po_rev>0){
									$po_rev_a 	= '-'.	$po_rev;
								}
								
								if(!empty($our_po_ref_no)){
									$sql = " select * from sma_location where id = '$location' ";
									$q2  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_object($q2);
									$loc_name 	= $r2->loc_name;
									$loc_gst_no 	= $r2->loc_gst_no;
								}
//echo $status ."<BR>";								
				?>
							
			<ul class="nav nav-tabs">
				  <li class="<?php echo $active_tab1; ?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Supplier Invoice</a></li>
				  
				<!--	<li  class="<?php echo $active_tab5; ?>"><a href="#tab_5" data-toggle="tab" id="five_tab" >A/c Journal</a></li> -->
				  <li class="<?php echo $active_tab3; ?>"><a href="#tab_3" data-toggle="tab" id="third_tab" >Document</a></li>
				  <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
			<?php if( $status != 'Draft' ){	?>	
					<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
			<?php }  
					
				$sql ="SELECT b.*, c.*
									FROM `sma_retention_invoice` a, payment_header b, payment_details c 
									WHERE c.supp_id = '$si_id' and a.si_hdr_id = c.supp_id and b.id = c.payment_hdr_id and st_flag in ( 'R', 'M' ) 
									and b.del !='Y' and a.status = 'Completed' ";
//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
								$q21  = mysqli_query($con, $sql);
								$rowaffect_si = mysqli_affected_rows($con);
							if($rowaffect_si>0){
					?>
				  <li><a href="#tab_7" data-toggle="tab" class="btn btn-info" id="seven_tab" >SI Payment</a></li>
			<?php  } ?>	
			
			</ul>
				
		<div class="tab-content">
				
			<div class="tab-pane <?php echo $active_tab1;?>" id="tab_1">
					
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo $row['id'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Created Date</label>
									<input type="text" class="form-control"  readonly value="<?php echo date('d-m-Y', strtotime($row['created_date']));?>" >
									
							</div>	
							
							<div class="col-sm-4">
								<input type="hidden" name="comp_id" id="comp_id" readonly value="<?php echo $row['company_id'];?>" >
								<input type="hidden" id="company_ID" readonly value="<?php echo $row['company_id'];?>" >
								<input type="hidden" id="suplier_NAME" readonly value="<?php echo $row['suplier_name'];?>" >
							
								<label for="company_id" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" disabled required <?php echo $readonly. ' ' . $disabled; ?> <?php echo $readonly_draft; ?> >
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>	
								<?php $sql = "select * from company where 1 $sqla and comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-4">
								<?php 
									$party_id_doc = $row['suplier_name'];
									$sqla = '';
									if ( $readonly || $readonly_draft ){
										$sqla = " and id = '$party_id_doc' ";
									}
									$supplier_location = $row['supplier_location'];	
									$party_id = $row['suplier_name'];
									
									$sql = "select * from sma_party_mst where 1 and id = '$party_id_doc' ";
									$q2 	  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($q2);
									$tax_category 	= $r2['tax_category'];
									$party_gst_number 	= $r2['party_gst_number'];
											
									if(substr($loc_gst_no,0,2)== substr($party_gst_number,0,2)){
										$supplier_location = 'L';	
										$sql = "UPDATE sma_retention_invoice SET supplier_location = 'L' WHERE si_hdr_id = '$si_id' ";
										mysqli_query($con, $sql);										
									}
									else {	
										$supplier_location = 'O';
										$sql = "UPDATE sma_retention_invoice SET supplier_location = 'O' WHERE si_hdr_id = '$si_id' ";
										mysqli_query($con, $sql);
									}
								?>
								
								<input type="hidden" name="tax_category" id="tax_category"  readonly value="<?php echo $tax_category;?>" >
								
								<input type="hidden" name="suplier_name" id="suplier_name"  readonly value="<?php echo $row['suplier_name'];?>" >
							
								<label class="control-label">Supplier Name <span style="color:red;"> **</span></label>
								<select class="form-control" required disabled <?php echo $readonly_draft; ?> onchange="getporefno(this.value); getstate(this.value)">
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>	
										<?php $sql = "select * from sma_party_mst where 1 $sqla order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>

						<div class="form-group">
						
							
							<span id="getporefno">
							<div class="col-md-2">
								<label class="control-label">Supplier GST No.</label>
								<input type="text" class="form-control" readonly id="supplier_gst_no" name="supplier_gst_no" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?= $party_gst_number; ?>" >
							</div>
								
							<div class="col-md-3">
								<label class="control-label">Our PO Ref.No.<span style="color:red;"> **</span></label>
								
									<input type="hidden" class="form-control" id="our_po_ref_no" name="our_po_ref_no" readonly value="<?php echo $our_po_ref_no;?>" >
									<input type="text" class="form-control" id="our_po_ref_no_a" name="our_po_ref_no_a" readonly value="<?php echo $our_po_ref_no_a . $po_rev_a;?>" >
								
							</div>
						<?PHP
							/* $our_po_ref_no 	= $row['our_po_ref_no'];
								$our_pr_no		= $row['our_pr_no'];
								if($against_po_flag=='N'){
									$our_po_ref_no ='';	
								}
								 */
						 		$sql  = " SELECT * from sma_purchase_req where id = '$our_pr_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$pr_number		= $r1['pr_number'];
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$approval_memo_ref		= $r1['approval_memo_ref'];
								$advance_paid_amount	= $r1['paid_amount'];
								$total_po_amount		= $r1['total_po_amount'];
								$paid_against_invoice	= $r1['paid_against_invoice'];
								$location 			= $r1['location'];
								$our_po_ref_no_a  	= $r1['po_number'];
								$po_rev			 	= $r1['po_rev'];
								$po_draft_by		= $r1['draft_by'];
								
							if(empty($pr_number)){	
							    
							    $sql  = " SELECT * from sma_approval_memo where id = '$approval_memo_ref' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$against_indent_no		= $r1['against_indent_no'];
								
								$sql  = " SELECT * from sma_purchase_req where id = '$against_indent_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								$pr_number		= $r1['pr_number'];
								
							    $baseurl_po = $baseurl . "/purchase_order/edit.php?sub=edit&id=$against_indent_no";
							
							}
							
							$si_hdr_id = $row['si_hdr_id'];
							$baseurl_si = $baseurl . "/supp_invoice/edit.php?sub=edit&id=$si_hdr_id";
						?>	
							
						<?php	if($advance_paid_amount>0){ ?>
							<div class="col-md-2">
								<label class="control-label">Advance Paid against PO</label>
								<input type="text" class="form-control" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> style="text-align:right;" value="<?= $advance_paid_amount; ?>" >
							</div>
						<?php } ?>
						
							</span>

							<span id="getprrefno">
							<div class="col-md-3">
									<label class="control-label">MRN.No.</label>
									<input type="hidden" class="form-control" id="our_pr_no" name="our_pr_no" value="<?= $our_pr_no;?>"  >
									<input type="text" class="form-control" readonly value="<?= $pr_number;?>" >
							</div>
							</span>
							
							<div class="col-md-2">
								<label class="control-label">View Supplier Invoice</label>
								<a href="<?php echo $baseurl_si; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >SI - <?= $si_hdr_id; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
								<br>
								<label class="control-label">View PO</label>
								<a href="<?php echo $baseurl_po; ?>" target ="_blank"><span class="label label-info" style="font-size:14px;" >PO - <?= $against_indent_no; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>	
							
						</div>
						
							<?php 
								$company_id = $row['company_id'];
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2 	= mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$party_id_doc = $row['suplier_name'];
								$sqla = '';
								if ($readonly || $readonly_draft){
									$sqla = " and comp_id = '$company_id' ";
								}	
								
							?>
								
						<div class="form-group">
						
						<span id="getcreditdays">
							
					<?php	if(!empty($our_po_ref_no)){ ?>	
							<div class="col-md-2">
								<label class="control-label">Location</label>
								<input type="text" class="form-control" readonly  value="<?= $loc_name; ?>" >
							</div>
					<?php } ?>
					
							<input type="hidden" id="location_id" name="location_id" value="<?= $location; ?>" >
							
							<div class="col-md-3">
							<?php
								$department = $row['department'];
								$sqla = '';
								if ($readonly || $readonly_draft){
									$sqla = " and id = '$department' ";
								} 
							?>
								<input type="hidden" name="department" id="department" readonly value="<?php echo $row['department'];?>" >
								
								<label class="control-label">Department <span style="color:red;"> **</span></label>
								<select class="form-control" required disabled <?php echo $readonly. ' ' . $disabled; ?> <?php echo $readonly_draft; ?> >
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>
										<?php $sql = "select * from sma_department where 1 $sqla order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<?php
							
							if($supplier_location=='L'){
								$supplier_loc= 'Local';
							}
							else if($supplier_location=='O'){
								$supplier_loc= 'Out of State';
							}
							?>
							<div class="col-md-2">
								<label class="control-label">Tax Status</label>
								<input type="hidden" class="form-control" id="supplier_location" name="supplier_location"  value="<?= $supplier_location; ?>" >
								<input type="text" readonly class="form-control" value="<?= $supplier_loc; ?>" >	
							</div>
							
							
							<div class="col-md-1">
								<label class="control-label">Credit&nbsp;Days</label>
								
								<input type="text" class="form-control" id="credit_days" name="credit_days" readonly  style="text-align:right;" value="<?php echo $row['credit_days'];?>" >
								
							</div>
							
							</span>
							
						<?php   $trans_type = $row['trans_type'];
						        $trans_type = 1;
						        
						?>
						
						
							<?php //echo  $status. "><";
								if ( $status=='Draft' ){
										$readonly = '';
										$readonly_draft='';
								}
								$supplier_invoice_no = $row['supplier_invoice_no'];
								
							?>
							<div class="col-md-3">
								<label class="control-label">Tax Invoice No.</label>
								<input type="text" class="form-control" id="supplier_invoice_no" name="supplier_invoice_no" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo $row['supplier_invoice_no'];?>" onblur="checkinvoice_no(this.value);" >
								
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="invoice_date" name="invoice_date" readonly <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo date('d-m-Y', strtotime($row['invoice_date']));?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<?php 
								$company_idd = $row['company_id'] ; 
								
								$credit_days = $row['credit_days'];
								
								if($credit_days>0){
									$due_date =  date('d-m-Y', strtotime("$credit_days day",strtotime($row['invoice_date'])));
								}
								else {
									$due_date =  date('d-m-Y', strtotime($row['due_date']));
									$due_date='';
								}
								
								$due_date =  date('d-m-Y', strtotime($row['due_date']));
								if($due_date=='01-01-1970' || $due_date=='31-12-1969'){$due_date='';}
								
							?>
							<div class="col-md-2">
								<label class="control-label">Due Date</label>
								<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="due_date"  name="due_date" readonly <?php echo $readonly_draft; ?> value="<?php echo $due_date;?>" >
								
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>
					<?php
				if($dedtype=='R'){
					
						$sql = "SELECT b.* FROM `sma_retention_invoice` a, payment_header b, payment_details c where a.si_hdr_id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'R' and b.del !='Y' and a.status = 'Completed'  ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$utr_no 	= $r2['utr_no'];
							$paid_date 	= $r2['paid_date'];
						$pstat ='';	
						if(!empty($utr_no)){
							$pstat = 'Paid';
						}
				}
				else if($dedtype=='C'){
					
						$sql = "SELECT b.* FROM `sma_retention_invoice` a, payment_header b, payment_details c where a.si_hdr_id = '$si_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'M' and b.del !='Y' and a.status = 'Completed'  ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$utr_no 	= $r2['utr_no'];
							$paid_date 	= $r2['paid_date'];
						$pstat ='';	
						if(!empty($utr_no)){
							$pstat = 'Paid';
						}
				}
				
						
							$delivery_date = date('d-m-Y', strtotime($row['delivery_date']));
							if($delivery_date == '01-01-1970'){
								$delivery_date ='';
							}	
							
						    $invoice_received_date = date('d-m-Y', strtotime($row['invoice_received_date']));
							if($invoice_received_date == '01-01-1970'){
								$invoice_received_date ='';
							}	
							
						?>
						
							<div class="col-md-2">
									<label class="control-label">Invoice Received Date</label>
									<div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control"   id="invoice_received_date" readonly name="invoice_received_date" value="<?= $invoice_received_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Remarks </label>
								<input type="text" class="form-control" readonly  id="additional_remarks"  name="additional_remarks"  value="<?= $row['additional_remarks'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Delivery Challan No. <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" readonly  id="delivery_challen_no"  name="delivery_challen_no" placeholder="" value="<?= $row['delivery_challen_no'];?>" >
							</div>
							
							<div class="col-md-2">
									<label class="control-label">Delivery Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" readonly  id="bill_date" name="delivery_date" value="<?= $delivery_date;?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mode of Delivery <span style="color:red;"> </span> </label>
								<input type="text" class="form-control" readonly  id="delivery_mode"  name="delivery_mode" placeholder="" value="<?= $row['delivery_mode'];?>" >
							</div>
					<?php if(!empty($pstat)){ ?>
							<div class="col-md-1">
								<label  class="label label-success" style="font-size:14px;" ><?= $pstat;?></label>
							</div>
					<?php } 

					$total_amount 	= $row['total_amount'];
					$bal_amount 	= $row['bal_amount'];
					$payable_amount = $row['payable_amount'];
			
							
							$paid_amount = 0;
							
							if($status=='Completed' || $status=='Draft' || $status=='Submitted'){	
								if($bal_amount<=1){
									$paid_amount = $row['payable_amount'];
								}
								else if ($bal_amount>0){
									$paid_amount 	= $payable_amount - $bal_amount;
									$balance_amount = $payable_amount - $paid_amount;
								}
								else if ($bal_amount==0){
									$paid_amount = $payable_amount;
								}
								
								
							}		
		        		
							if($total_amount>0){
						?>
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo $total_amount;?>" >
							</div>
														
							<div class="col-md-2">
								<label class="control-label">Paid Amount</label>
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo $paid_amount;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Balance Amount</label>
								<input type="text" class="form-control"  style="text-align:right;" readonly value="<?php echo $balance_amount;?>" >
								
							</div>
						</div>	
							
						<?php }	?>
				
								<?php 
								
									$sql = "select * from payment_details a, payment_header b where 1 and b.del !='Y' and b.id = a.payment_hdr_id and b.st_flag = 'S' and a.supp_id = '$id' ";
						//echo $sql;
						
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$py_id = $r2['payment_hdr_id'];
									
							 ?>


<!--Deduction from Payment Start-->
			<div class="panel panel-default">
				<!--<div class="panel-heading">-->
    <!--                <h4 class="panel-title"><a data-toggle="collapse" class="btn btn-success" data-parent="#steps" href="#step2"><b style="color:white;"> Deduction from Payment</b></a></h4>-->
				<!--</div>-->
							
						
                <div id="step2" class="panel-collapse collapse in">
					<div class="panel-body">	
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
							</div>	
							<div class="control-label col-md-2"><b>Balance Amount &nbsp;</b></div>
							<div class="control-label col-md-2"><b>Payable &nbsp;</b></div>
							<div class="control-label col-md-2"><b>Remarks</b></div>
						</div>
			<?php 
			if($dedtype == 'R' ){
			        
			        $sql = "SELECT b.* FROM `payment_header` a, payment_details b where 1 and del !='Y' and st_flag = 'R' and b.supp_id = '$id' and a.id = b.payment_hdr_id ";
			        $q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$payment_adjusted = $r2['payment_adjusted'];
					$py_id            = $r2['payment_hdr_id'];
			        
			        $baseurl_py = $baseurl . "/payment/edit.php?sub=edit&id=$py_id";
			        
			        $retention_amount = $row['payable_retention_amount'] - $row['bal_retention_amount'];
			        $payable_retention_amount = $retention_amount;
			?>			
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">Retention </label>
							</div>	
							<div class="col-md-2">	
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="retention_amount"  name="retention_amount"  value="<?= $retention_amount;?>" >
								<input type="hidden" id="checker_value"  value="<?= $row['retention_amount'];?>" >
							</div>
						
							<div class="col-md-2">
								
								<input type="text" class="form-control" id="payable_retention_amount"  name="payable_retention_amount" style="text-align:right;" value="<?= $payable_retention_amount;?>" <?php echo $readonly;?>  >
							</div>
								
							<div class="col-sm-4">
								
								<input type="text" class="form-control"  name="retention_remarks" placeholder="" value="<?php echo $row['retention_remarks'];?>" <?php echo $readonly; ?>  >
							</div>
							<div class="col-md-2">
								<label class="control-label">Paid</label>
								<a href="<?php echo $baseurl_py; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Payment - <?= $si_hdr_id; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
							
						</div>
						
			<?php } ?>			
			<?php if($dedtype == 'C' ){ 
			        
			        $sql = "SELECT b.* FROM `payment_header` a, payment_details b where 1 and del !='Y' and st_flag = 'M' and b.supp_id = '$id' and a.id = b.payment_hdr_id ";
			        $q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$payment_adjusted = $r2['payment_adjusted'];
					$py_id            = $r2['payment_hdr_id'];
					$payment_adjusted   = $r2['payment_adjusted'];
			        
			        $baseurl_py = $baseurl . "/payment/edit.php?sub=edit&id=$py_id";
			        
			        $compliances_amount = $row['payable_compliances_amount'] - $row['bal_compliances_amount'];
			        $payable_compliances_amount = $compliances_amount;
			        
			?>			
						<div class="form-group">		
							<div class="col-md-2">
								<label class="control-label">Compliances </label>
							</div>	
							<div class="col-md-2">	
								<input type="text" class="form-control" <?= $readonly1; ?> style="text-align:right;" id="compliances_amount"  name="compliances_amount"  value="<?= $compliances_amount;?>" >
								<input type="hidden" id="checker_value"  value="<?= $row['retention_amount'];?>" >
							</div>
						
							<div class="col-md-2">
								
								<input type="text" class="form-control" id="payable_compliances_amount"  name="payable_compliances_amount" style="text-align:right;" value="<?= $payable_compliances_amount;?>" <? $readonly; ?>  >
							</div>
								
							<div class="col-sm-4">
								
								<input type="text" class="form-control"  name="compliances_remarks" placeholder="" value="<?php echo $row['compliances_remarks'];?>" <?= $readonly; ?>  >
							</div>
				<?php if($py_id>0 ){ ?>			
							<div class="col-md-2">
								<label class="control-label">Paid</label>
								<a href="<?php echo $baseurl_py; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Payment - <?= $si_hdr_id; ?></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
				<?php } ?>			
						</div>
			<?php } ?>			
					
					</div>
				</div>
			</div>
		
		<!--Tally Journal Start-->

							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click');getvalidate();" >Next</a>
								</div>
							</div>	
							
		</div>
					
						<!-- Attachments - Upload Panel -->
        <div class="tab-pane" id="tab_3">
                            <!-- Attachments company_idd -->
					<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" style="font-weight:bold;" ><b>SI Number : <?php echo $si_id;?> 
					<?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
						$sql = "select * from sma_party_mst where 1 and id = '$party_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$party_name	= $r2['party_name'];
						echo " Supplier Name : " . $party_name;
						
						$label_line = "<b>SI Number : " . $si_id. ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date'])). " Supplier Name : " . $party_name . "</b>";
					?>
						</b>	
					</span>		
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'RI' AND reference_id = " . $si_id;
							  
                              $docResults = mysqli_query($con, $sql);
                              $doc_cnt = mysqli_affected_rows($con);
                              
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="20%" >Description</th>
										  
										  <th  width="30%" >File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_desc = $docRow['doc_desc'];
													$doc_type = $docRow['doc_type'];
													
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
													$dms_path = '';
													$dms_module = $docRow['dms_module'];
													if($dms_module=='IN'){
														$dms_path = $baseurl.'dms/';
														$doc_desc = 'Inward No:'.$docRow['doc_invoice_no'];
													}
													
					                              ?>
                                          <tr>
                                             <td width="20%" ><?php echo $document ?></td>
                                              <td width="20%"><?php echo $doc_desc ?></td>
											 
											  <td width="30%"><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
										<?php //if ($user=='Admin' || $status == 'Draft' ){ ?>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
										<?php //} ?>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						<?php //if (empty($readonly)){ ?>
						
						
						<span id="gegpartyDoc">
							
						</span>
						
						
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										    
										<td width="20%" >
                                            <select class="form-control select2 doctype" name="doctype[]"   >
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
                                            </select>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										
										<td width="30%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										
                                         <td width="10%" >
										<?php 
											if($status != 'Completed'){
										?>
											<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
										<?php } ?>
										 </td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						<?php
				          //  }
				        ?>	
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous </a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
					<div class="box-footer">
					
					<span id="predit"></span>
			
					<?php		
					
							echo '<div class="col-sm-12">	';
							//$checker_value = 100;
								$sql = " SELECT * FROM `sma_workflow` 
										where company_id = '$company_id'  and '$checker_value' <= to_value and '$checker_value' >= from_value 
										and doc_type = 'SI' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$email_approval_expected_role_1 = $rw['email_approval_expected_role_1'];							
								$email_approval_expected_role_2 = $rw['email_approval_expected_role_2'];
								$email_approval_expected_role_3 = $rw['email_approval_expected_role_3'];
								$email_approval_expected_role_4 = $rw['email_approval_expected_role_4'];
								$email_approval_expected_role_5 = $rw['email_approval_expected_role_5'];
								$email_approval_expected_role_6 = $rw['email_approval_expected_role_6'];
								$email_approval_expected_role_7 = $rw['email_approval_expected_role_7'];
				//echo $sql.'<BR>';
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_1' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_1 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_2' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_2 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_3' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_3 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_4' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_4 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_5' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_5 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_6' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_6 = $rw['role'];	
								$sql = " SELECT * FROM `sma_role` where id = '$email_approval_expected_role_7' ";							
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$role_7 = $rw['role'];	
							
								$created_date = $row['created_date'];
								$expected_role_count = 0;
								if(!empty($email_approval_expected_role_1)){
									if($status!='Draft' && $created_date <='2023-08-15'){
										$email_approval_received_1	= 'Y';
										$email_approval_received_2	= 'Y';
										$email_approval_received_3	= 'Y';
										$email_approval_received_4	= 'Y';
										$email_approval_received_5	= 'Y';
										$email_approval_received_6	= 'Y';
										$email_approval_received_7	= 'Y';
									}
							?>
								<div class="col-md-12">
									<label class="control-label">Before Submitting, Email Approval to Expect from</label><br>
								</div>	
								<?php } ?>
							<?php if(!empty($email_approval_expected_role_1)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_1;?></label><br>
									<input type="hidden" id="email_approval_expected_role_1" name="email_approval_expected_role_1" value="<?= $email_approval_expected_role_1;?>" >
							
							<?php if($status=='Draft' ){ ?>	
									<input type="checkbox" id="email_approval_received_1" name="email_approval_received_1" <?php echo ($email_approval_received_1=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_1=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>
							<?php if(!empty($email_approval_expected_role_2)){
										$expected_role_count = $expected_role_count + 1;
							?>
								<div class="col-md-2">		
									<label class="control-label" class="btn btn-info" ><?= $role_2;?></label><br>
									<input type="hidden" id="email_approval_expected_role_2" name="email_approval_expected_role_2" value="<?= $email_approval_expected_role_2;?>" >
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_2" name="email_approval_received_2" <?php echo ($email_approval_received_2=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_2=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>	
							
								</div>	
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_3)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_3;?></label><br>
							<?php if($status=='Draft' ){ ?>		
									<input type="checkbox" id="email_approval_received_3" name="email_approval_received_3" <?php echo ($email_approval_received_3=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_3=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_4)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_4;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_4" name="email_approval_received_4" <?php echo ($email_approval_received_4=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_4=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_5)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_5;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_5" name="email_approval_received_5" <?php echo ($email_approval_received_5=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_5=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_6)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_6;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_6" name="email_approval_received_6" <?php echo ($email_approval_received_6=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_6=='Y' ){ ?>	
									<input type="text" readonly  class="form-control" value="Yes" >
							<?php } ?>		
							
								</div>		
							<?php } ?>	
							<?php if(!empty($email_approval_expected_role_7)){
										$expected_role_count = $expected_role_count + 1;
							?>	
								<div class="col-md-2">
									<label class="control-label" class="btn btn-info" ><?= $role_7;?></label><br>
							<?php if($status=='Draft' ){ ?>				
									<input type="checkbox" id="email_approval_received_7" name="email_approval_received_7" <?php echo ($email_approval_received_7=='Y')?"CHECKED":"";?> value="Y" >
							<?php } ?>		
							<?php if($status!='Draft' &&  $email_approval_received_7=='Y' ){ ?>	
									<input type="text" readonly class="form-control" value="Yes" >
							<?php } ?>		
							
								
							</div>
							<?php } ?>	
					</div>
					
					<?php if($del != 'Y'){ ?>
							
							<div class="col-sm-6">
								
								<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&si_id='.$si_id ; ?>
							    
								<?php 
									
									$approval_status = $row['approval_status'];
									//echo $status. "<>";
									if($del=='Y' || ($user=='Admin' &&  $status!='Draft' && empty($py_id) ) || ( $approval_status=='Rejected' ) ) { ?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Unpaid</a>
								<?php
									}
									
//$status != 'Completed' && 							
								if ($status == 'Draft' && empty($py_id) ){
									
							?>
								<!--<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>-->
								<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>
								
							<?php	
								} 
							?>
								<span>&nbsp;&nbsp;</span>
								
					
							</div>
							
							<div class="col-sm-6 text-right">
							
						<?php 
								$_SESSION['si_id'] 	= $si_id;
								$_SESSION['status']  = $status;
								$_SESSION['our_po_ref_no']  = $our_po_ref_no;
								
								$approver_flag='';
								if( $status != 'Draft' ){
									
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 ){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										 ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										 ){
										$approver_flag='Y';
									}
								
									 
								}

//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								
								echo "<span style='color:red;'>".$berrmsg ."</span><BR>";
							
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
								</span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								}
						?>			
					
						<?php	
					
						if(!empty($dup_invoice)){
							echo $dup_invoice. "<BR>";	
						}	
				// 		if( $doc_cnt==0 || empty($doc_cnt) ){
				// 		    echo "<span style='color:red;text-weight:bold;'>Dcocument Attachments should be mandatory !</span>";
				// 		    //echo "Dcocument Attachments should be mandatory !";
				// 		    //&& $doc_cnt >0 
				// 		}
						
					//	echo $status. ' ' . $approval_status;
							if($status=='Draft' && $approval_status!='Rejected' ){
							    
						?>
								<span class='hidesend'>	
									<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								</span>	
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php 	
							}
						?>
					
						
						<?php //if( $approval_status!='Rejected' && $items_cnt > 0 && empty($poerrmsg) ){ ?>
							<span class='hidesend'>	
								<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
							</span>	
						<?php 	//}  ?>	
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<?php 
								$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
							?>		
								<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-default" >Back</a>&nbsp;&nbsp;&nbsp;</span>
					
						<label class="col-lg-2 control-label" style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></label>	
							<label style="color:red;text-align:center;" ><?php echo $emsg; ?></td>
							
						</div>
					<?php	
						}
					?>
								
						
					</div>
						
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
									<label class="control-label1">Approver 1</label><BR>
									<label class="control-label1"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 2</label><BR>
									<label class="control-label1"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 3</label><BR>
									<label class="control-label1"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							
					?>		
						
						</div>
						
					<?php
						}
					?>
					
					<span id="getapprover">

						<?php 						
						if( $status == 'Draft' ){
						?>
						
								<div class="box-footer">
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
								/*$approver_1 = $row['approver_1'];
								$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3']; */
						
								if( (!empty($approver_1) && $status =='Submitted' && $items_cnt > 0 ) ){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_1 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><br>
									<?php echo 'Role :'. $rolenm;?>
									
									<select class="form-control  approver_1" name="approver_1"   >
                                        <?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }
							
								if(!empty($approver_2)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_2 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_2" name="approver_2" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_2 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_3 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_3" name="approver_3" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_3 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 	
									
							?>	
							
								<BR>
								
							</div>
						
						<?php } ?>		
						
						</span>
				

		</div>	
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
						  
		<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $si_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RI' order by id asc ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									if($create_date=='01-01-1970'){
										$create_date ='';
									}	
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
							
							<?php echo '&nbsp;&nbsp;&nbsp;&nbsp;'; ?>
							
							<?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
								echo " Supplier Name : " . $party_name;

							?>
						</b>	
					</span>
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'RI' order by id desc ";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$make_by_flag		= $r1['make_by_flag'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['primary_role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
												if($make_by_flag=='V'){
													$create_by		= $r1['create_by'];	
													$sl="SELECT * FROM sma_party_mst where id = '$create_by' ";
													$r3 = mysqli_query($con, $sl);
													$rw = mysqli_fetch_array($r3);
													$create_by = $rw['party_name'];
												}	
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
		</div>
					
<?php	//Supplier Invoice Payment Start ?>
						<div class="tab-pane" id="tab_7">
							
							<div class="modal-header" >
								<p><?php echo $label_line; ?></p>
							</div>
							
							<div class="box-body">
							<table id="prtable123" class="table table-bordered table-striped">

								<thead>
									<tr>
										<th>SrNo.</th>
										<th>Paid Date</th>
										<th>Paid via</th>
										<th>UTR.No./ Cheque No.</th>
										<th>Invoice Number</th>
										<th style="text-align:right;">Amount Paid</th>
									</tr>
								</thead>
							<tbody>
							<?php
								$sql = "select * from payment_details a, payment_header b where 1 and b.id = a.payment_hdr_id and b.st_flag in ('C', 'R') and b.del !='Y' and supp_id = '$si_id' ";
								$total_amount_paid_net =0;
								$q21  = mysqli_query($con, $sql);
								$rowaffect = mysqli_affected_rows($con);
								
									$q21  = mysqli_query($con, $sql);
									while($r21  = mysqli_fetch_array($q21)){
										$py_id = $r21['payment_hdr_id'];
									
										$cash_bank_name = $r21['cash_bank_name'];
										$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
										$q2 	= mysqli_query($con, $sql);
										$r2 	= mysqli_fetch_array($q2);
										$cash_bank_name = $r2['account_name'];
										
										$paid_to = $r21['paid_to'];
										$st_flag = $r21['st_flag'];
										if($st_flag =='A' || $st_flag =='T'){
											$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['username'];
										}
										else {
											$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
											$q2  = mysqli_query($con, $sql);
											$r2  = mysqli_fetch_array($q2);
											$party_name  = $r2['party_name'];
										}
										
										if($st_flag=='S'){
											$st_flag ='SI';
										}
										else if($st_flag=='A'){
											$st_flag ='TA';
										}
										else if($st_flag=='T'){
											$st_flag ='TE';
										}
										else if($st_flag=='C'){
											$st_flag ='OE';
										}
										else if($st_flag=='D'){
											$st_flag ='SA';
										}
										
										$dated = date('d-m-Y', strtotime($r21['dated']));
										if($dated =='01-01-1970'){
											$dated = '';
										}
										
										$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
										if($paid_date =='01-01-1970'){
											$paid_date = '';
										}
										
										$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
										$q2  = mysqli_query($con, $sql);
										$r2  = mysqli_fetch_array($q2);
										$supplier_invoice_no  = $r2['supplier_invoice_no'];
										$supp_id			  = $r2['supp_id'];
										
										$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];
										
										$approval_status = $r21['approval_status'];
											
										$baseurl1 = $baseurl.'payment/'.'edit.php?sub=edit&id='.$py_id;
												
										?>
									<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=". $py_id;?>" target="_blank" title="Edit">
									<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="window.open('<?php echo $baseurl1;?>', '_blank')"  >
										<td width="2%" style="text-align:right;"><?php echo $r21['id'];?></td>
										<td width="10%" ><?php echo $paid_date;?></td>
										<td width="12%" ><?php echo $cash_bank_name;?></td>
										<td width="10%" ><?php echo $r21['utr_no']. ' ' . $r21['cheque_no'];?></td>
										<td width="12%" ><?php echo $supplier_invoice_no;?></td>
										<td width="10%" style="text-align:right;<?php echo $styl2; ?>"><?php echo number_format($r21['total_amount_paid'],2);?></td>
										
									</tr>
									</a>
									
									<?php }
									
								?>
								</tbody> 
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td width="10%" style="text-align:right;"><?= number_format($total_amount_paid_net,2);?></td>
									</tr>
								</table>

									</div>
						
						</div>
<?php	//Supplier Invoice Payment End ?>
				
<!--Comment Section Start-->
	<?php //if($status!='Draft'){ 
	?>
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
					<p><?php echo ' Invoice Date: '. date('d-m-Y', strtotime($row['invoice_date']));
								echo " Supplier Name : " . $party_name;

							?></p>
				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$si_id' and doc_type = 'SI' order by id desc ";
				//echo $s1;
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$comment_datetime		= $r1['comment_datetime'];
				$comment_type			= $r1['comment_type'];
				$comment				= $r1['comment'];
				$parent_comment_id		= $r1['parent_comment_id'];
				$comment_by				= $r1['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($r1['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$create_by = $rw['username'];
					
				if(!empty($create_by)){	
					$tmp_var = "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$comment_datetime; 
				}
				?>			
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $si_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $si_id;?>,'SI','C',<?= $page;?>)" >Submit</button>		
						</div>
					</div>

			<span id="getcomment">	
			<?php
				$res  = mysqli_query($con, $s1);
				while($r1 = mysqli_fetch_array($res)){
					$comment_datetime		= $r1['comment_datetime'];
					$comment_type			= $r1['comment_type'];
					$comment				= $r1['comment'];
					$parent_comment_id		= $r1['parent_comment_id'];
					$comment_by				= $r1['comment_by'];
					$comment_datetime	    = date('d-m-Y h:i:s a', strtotime($r1['comment_datetime']));
					if($comment_datetime=='01-01-1970'){
						$comment_datetime='';
					}
					$sl="SELECT * FROM sma_user where id = '$comment_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$create_by = $rw['username'];
			?>
					<div class="col-md-12">
					
						<label class="control-label">On <?php echo $comment_datetime ?> <?php echo $create_by ;?> : wrote</label><br>
						<?= $comment; ?>
					<!--	<textarea style="background-color:#F5F5F5;" readonly rows='02' cols="150" ><?= $comment; ?></textarea> -->
					</div>
			<?php	
				}
			?>	
			</span>
			
			</div>
					</section>
					
				</div>
				
			 </div>
						
		</div>

	<?php //} ?>
	
<!--Comment Section End-->				
								
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						
		</div>
					
                    </fieldset>

				</div>
				
            </form>
					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>  
</div>


<!--Add Line Popup-->

<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											
											$sqlu = "";
											/* if($tax_category=='U'){
												$sqlu = " and account_name not like '%GST%' ";
											} */	
										?>
										
										<input type="hidden" name="si_id" id="si_idA" value="<?php echo $si_id; ?>" >
										
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Supplier
												
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	
												
                                            </div>
                                        </div>

									
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)">
														<option value="">Select</option>
													<?php
														$sql = "SELECT * FROM account_mst where 1 and ( account_type = 'D' OR account_type = 'A' )$sqlu order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'].'-'.$r3['percentage'].'%'; ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectA" name="effect" value='Dr' > Debit
												<input type="radio"  id="effectA" name="effect" checked value='Cr' > Credit
											</div>
                                        
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Amount *</label>
												<span class="gettdsamt">
                                            	<input type="text" class="form-control amountA"  autocomplete="off" style="text-align:right;;" name="amount" id="amountA" >
												</span>
												
												<label for="approver" class="control-label">If Any changes in amount enter here</label>
												<input type="text" class="form-control " id="amountABC" autocomplete="off" style="text-align:right;;" name="amounta" value="" >
												
                                            </div>
                                        </div>
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA"></textarea>
											</div>
										</div>

								</form>

								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
							</div>
			
                            </div>
                        </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Line Popup End -->


<!--Make to DraftPopup-->
<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="si_id" id="si_idD" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="si_id" id="si_idZ" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->


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

											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
											$our_po_ref_no = $_SESSION['our_po_ref_no'];
											$role = $_SESSION['role'];
							
										?>
										
										<input type="hidden" name="si_id" id="si_idE" value="<?php echo $si_id; ?>" >
										
										<input type="hidden" id="our_po_ref_NO" name="our_po_ref_no" value="<?php echo $our_po_ref_no?>">
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
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
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$si_id 	= $_SESSION['si_id'];
											$status = $_SESSION['status'];
										
										
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$r3   = mysqli_fetch_array($query);
											$approver = $r3['id'];

										?>
										
										<input type="hidden" name="si_id" id="si_idR" value="<?php echo $si_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
																				
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
<!--                                               	<select class="form-control select2123" id="statusE" name="status">
													    <option value="">Select</option>
														<option value="Draft">Draft</option>
														<option value="Submitted">Submitted</option>
														<option value="Reviewed">Reviewed</option>
													</select>
												-->
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-6 control-label" style="color:red;">Do you want to Reject ?</label>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    
									</form>	
								
								</div>

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

<!--Reject Workflow Popup End -->	  
	  
	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="clearfld()" >&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Product </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="edit.php?id=<?php $_GET['id'];?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="si_Id" value="<?php echo $_GET['id'];?>">
							<?php
								$id = $_GET['id'];
								$sql="SELECT * FROM sma_retention_invoice where si_hdr_id = '$id' ";
                            //echo $sql;
								$rs1 = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                $rw1 = mysqli_fetch_array($rs1);
								$our_po_ref_no = $rw1['our_po_ref_no'];

							?>
							
						<?php 
							if (!empty($our_po_ref_no)){
								$sql = "SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$rs = mysqli_query($con, $sql);
                                $rw = mysqli_fetch_array($rs);
								$received_date = date('d-m-Y', strtotime($rw['dated']));	
						?>
					
						<div class="form-group">
                                <label for="itemCategory" class="control-label col-sm-2">Purchase Order</label>
								<div class="col-sm-10">	
                                    <select class="form-control" id="grn_No" name = "grn_no" onchange="getitemdetails(this.value);" >
										<option value="0">Select</option>
                                    <?php
										
										$sql = "SELECT a.purchase_id as pid, a.product_id as 'material_id', ( a.quantity - a.bal_si_qty ) as qty , c.name as product_name, c.name as pname, c.uom FROM `sma_po_items` a, sma_product c where a.product_id = c.id and purchase_id = '$our_po_ref_no' and ((quantity > bal_si_qty or 
										((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100)) > bal_si_amount or 
										purchase_id = '$our_po_ref_no' ) )"; //a.purchase_id = 2136 || 
										
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
									?>
                                        <option value="<?php echo $rw['pid'].'-'.$rw['material_id'];?>" >
										<?php echo 'Product Name: '.$rw['product_name'].' Pending Qty:'. $rw['qty'] ; ?>
										</option>
                                     <?php } ?>
									 
                                    </select>
								</div>
							</div>
						<?php } 
								$readonlye = 'READONLY';
						?>
							
                        <?php if (empty($our_po_ref_no)){
								$readonlye = '';
						?>		
							<div class="form-group">
								<input type="hidden"id="grn_No" name = "grn_no" value=''>
								
								<label for="itemCategory" class="control-label col-sm-1"> Category</label>
								<div class="col-sm-3">
									<select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								<label for="itemName" class="control-label col-sm-2">Product Name</label>
                                <div class="col-sm-6">
									<span id="getmaterial1" ><span id="getgrnitem" >
										<select class="form-control" id="itemName">
											<option value="">Select</option>	
										<?php
											/* $sql="SELECT id, name FROM sma_product ORDER BY name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($result)){ */
										?>
										<!--	<option value="<?php echo $r2['id']?>"><?php echo $r2['name'] ?></option> -->
											<?php //} ?>
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-6">
								<label for="itemDescription" class="control-label ">Description</label>
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
								
								<div class="col-sm-6">
									<label class="control-label">Posting Account (DR)</label>
									<input type="text" class="form-control" id="posting_ACCOUNT_A"   readonly value="" >
								</div>
								
                            </div>
						
							
								<div class="form-group">
									
									<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group</label>
										<select class="form-control" id="costcenter_group" name="costcenter_group" required="true" onchange="getcostcenter(this.value);" >
											<option value="">Select</option>
										<?php
											$sql = " SELECT * FROM sma_budget_name where 1  ORDER BY name ASC ";
											$q2  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_object($q2)){
												$name = $r2->name;
												$id = $r2->id;
										?>		
											<option value='<?php echo $id ?>'><?php echo $name; ?></option>
										<?php }; ?>
										</select>
										
									</div>
								
									<div class="col-sm-6">
										<span id="getcostcenter" >
											
										<label for="itemName" class="control-label">Cost Center Name</label>
										
										</span>
									</div>
								</div>
									
								<div class="form-group">	
									<div class="col-sm-12">
										<span id="getcatbudget">
												
										</span>
									</div>
								</div>
								
                        <?php } ?>
							
						</div>
						
                        <div class="form-group col-md-12 ">
								
						<span id="getitemdetails">			
					
							<div class="col-sm-2">
								<label for="itemGST" class="control-label">GST Type</label>	
								<select class="form-control" name="itemg_id" id="itemg_id" onchange="getgst(this.value)" >
									<option value=""> Select </option>
									<?php 
									$sql = "select * from gst_mst where 1 order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'].'-'.$r22['igst'];?>" ><?php echo $r22['gst_name'].'-'.$r22['igst'];?></option>
										<?php } ?>
								</select>
							</div>
							
								<div class="col-sm-2">
									<label for="itemGST" class="control-label">GST%</label>
								
                                    <input type="text" class="form-control" id="itemGST"  style="text-align:right;" readonly onkeyup="calculateTotalAmount();">
									
									<input type="hidden" class="form-control" name="itemgst_id" id="itemGST_ID"  style="text-align:right;" readonly >
								
                                </div>

							
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
							<span id="getunit123" > 
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
										<input type="text" class="form-control itemUNITS" id="itemUNITS" name="itemunits" readonly value='' >
								</div>
                            </span>	
                                
								<div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate" style="text-align:right;" <?= $readonlye;?> onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
								
							
							
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total </label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                           </span>
							
                            </div>
						
							
					
					
                        <div style="color:red;font-weight:bold;" id="showmsg"> </div>
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
			<?PHP if ($against_po_flag=='Y'){ ?>	
                <button type="button" class="btn btn-primary" id="addItem" onclick="checkbudget();" >Save </button>
			<?php } 
			else { 
			?>
				<button type="button" class="btn btn-primary" id="addItemmanual" >Save</button>	
			<?php }  //onclick="checkbudget123();"
			?>
			
            </div>
			
                </section>
            </div>

        </div>
    </div>
</div>


<script>
	function checkbudget(){
		
	//	balance_budget_a
        var itemAmount 		= document.getElementById('itemAmount').value;
		
		var balance_budget 	= document.getElementById('balance_budget_a').value;
//alert(itemAmount + ' <<##1>>' + balance_budget );		
//return false;
		var check_balance	= parseInt(balance_budget) - parseInt(itemAmount);
//alert(check_balance  );			
		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP !!!');
			return false;
		}

	}
	
	function getitemdetails(id){
		
        var sub    = 'sub44';
		var comp_id = document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id + ' ' + grn_no);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,comp_id:comp_id,sub44:sub},function(result){
		      $('#getitemdetails').html(result);
		});

	}
	
	function gettotbudget(id){
		
        var sub    = 'sub45';
		
		var budget_name_id 	= document.getElementById('budget_name').value;
		var comp_id 		= document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,budget_name_id:budget_name_id,comp_id:comp_id,sub45:sub},function(result){
		      $('#gettotbudget').html(result);
		});

	}
	

</script>

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        


<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%" ><select class="form-control select2 doctype" name="doctype[]"  ><option value="">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"></td><td  width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
			var company_id = result;
			  var splitString = result.split("##");
			  var company_id =  splitString['1'];
			  
			  var result = splitString['0'];
			  $('#getcreditdays').html(result);
			  
			    /* var sub    = 'sub12';
			    var strURL = "app_func.php";
				$.post(strURL,{id:company_id,sub12:sub},function(result){
					  $('#getworkflowtype').html(result);
				}); */
				
		      //$('#getcreditdays').html(result);
		});

	}
	
</script>

<script>


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var si_id		 	=  $("#si_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + si_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var si_id		 	=  $("#si_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + si_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});

  
    $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
		$('#predit').html('Wait...Processing !!');
//		alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idE").val();
		var approver 		=  $("#approverC").val();
        var our_po_ref_no 	=  $("#our_po_ref_NO").val();
		var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		
//alert( approver + ' ' + status + ' ' +  mode );
		 $('#approvalAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						approver:approver,
						our_po_ref_no:our_po_ref_no,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
		//alert(sub + ' ' + mode);		 
		var si_id		 	=  $("#si_idR").val();

        var approver 		=  $("#approverR").val();
		//var approver		=  $("#approverR option:selected").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(status+ ' ' + department + ' ' + mode + ' ' + approver);
		 $('#rejectAuthority').modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ si_id:si_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


    $("#addItemmanual").on("click", function(e){
        var sub = 'sub2';
//alert(sub); return false;
		
		var si_hdr_id 	 =  $("#si_Id").val();
		var company_id   =  $("#comp_id").val();
		var id 			 =  $("#itemName").val();
		//var id 			 =  $(".itemName").val();
		//var name		 =  $("#itemName").html();
//alert('addItemmanual ' + id + ' <<#>>');
//return false;
			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
		var gst_id 			 =  $("#itemGST_ID").val();
        var amount 			 =  $("#itemAmount").val();
//alert(gst_id);
//return false;
		var budget_id  	 =  $("#budget_ID").val();
//alert('Budget ID : '+budget_id);
			var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_BUDGET').value;

		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' <<#1>>' + itemamount + ' <<<#2>>> ' + balance_budget + ' <<<#3>>> ' + tot_amount + ' <<<##>>>' );

		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP!!!');
			var shoid = 'AOP / Budget 100% reached, please increase AOP !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
		$('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,si_hdr_id:si_hdr_id,
							description:description,
							company_id:company_id,
							budget_id:budget_id,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							gst_id:gst_id,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		

	});

    $("#addItem").on("click", function(e){
        var sub = 'sub2';
	//	var mode = $("#mode").val();
			var grn_no		 =   $("#grn_No").val();
//alert(sub2);
//		if(grn_no != '0'){
			var si_hdr_id 	 =  $("#si_Id").val();		
			var id 			 =  $("#itemName").val();
			var name		 =  $("#itemName").html();
			var company_id   =  $("#comp_id").val();
			var budget_name  =  $("#budget_name").val();
			var budget_head  =  $("#budget_head").val();
			var budget_id  	 =  $("#budget_id").val();
			var budget_head_b  	 =  $("#budget_head_b").val();
			var budget_name_b  	 =  $("#budget_name_b").val();
			
//alert('addItem '+ grn_no + ' <<#>> ' + company_id);
//return false;

		var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_budget_a').value;
		var balance_budget	=  $(".balance_budget_a").val();
//alert('Balance Budget ' + balance_budget + ' #####1');
		
		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' ' + itemamount + ' <<<>>> ' + balance_budget + ' <<<>>> ' + tot_amount + ' <<<>>>' );
	
		var itemqtychk  	 =  $("#itemQtyChk").val();			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
        var amount 			 =  $("#itemAmount").val();

//alert(qty + ' ' + itemqtychk);

		 
		if ( parseInt(enter_amount) > parseInt(amount) && po_threashold=='V'){
			alert('Amount should not be greater then PO AMount...');
			var shoid = 'Amount should not be greater then PO AMount... !!!';
			$("#showmsg").text(shoid);
			return false;
		} 
		
		
		if ( parseInt(qty) > parseInt(itemqtychk) && po_threashold=='Q'){
			alert('Quantity should not be greater then PO QTY...');
			var shoid = 'Quantity should not be greater then PO QTY !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//return false;		
		
		if ( check_balance < 0 ){
			alert(' Budget 100% reached, please increase !!!');
			var shoid = ' Budget 100% reached, please increase  !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//alert(units + ' ' + qty);
        $('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,si_hdr_id:si_hdr_id,
							name:name,
							description:description,
							company_id:company_id,
							grn_no:grn_no,
							budget_name:budget_name,
							budget_head:budget_head,
							budget_id:budget_id,
							budget_head_b:budget_head_b,
							budget_name_b:budget_name_b,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//		location.reload();
//		window.location.href='supplier_invoice.php?sub=edit&id='+si_hdr_id+'&active=active';
		
//        saveItem(mode);
		
    });


    $("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub12';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		var amount 		= $(".amountA").val();
		var narration 	= $("#narrationA").val();
		var si_hdr_id 	= $("#si_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert( amount2 + ' ' + amount + ' <<>> ' + si_hdr_id + ' ' + account_name + ' ' + type_ac + ' ' + effect );

		$('#addLine').modal('hide');
		var strURL 		= "si_func.php";
		$.post(strURL,{ type_ac:type_ac,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,si_hdr_id:si_hdr_id,sub12:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

    $("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub10';
		var mode 	= $("#modeT").val();
		var si_hdr_id 	 =  $("#si_idT").val();		
//alert(si_hdr_id);
        var gst_flag = '';
// 		if (document.getElementById('gst_FLAG').checked) {
// 		   // gst_flag = document.getElementById('gst_FLAG').value;
// 			var gst_flag = 'Y';
// 		}
// 		else {
// 			var gst_flag = '';
// 		}
		


		$('#modalAddTally').modal('hide');
		var strURL 		= "si_func.php";
		$.post(strURL,{ mode:mode,si_hdr_id:si_hdr_id,gst_flag:gst_flag,sub10:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

	function getaccount(id){
		
        var sub    		= 'sub11';
		
//alert(sub + ' ' + id);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getaccount').html(result);
		});

	}

	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#amount_DR").val();
		
		var si_id	 	= $("#si_idA").val();
//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,si_id:si_id,amount_dr:amount_dr,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}
	
	function delete_siItem(si_id, id ){
		var sub = 'sub3';
        var siid = si_id;
		var id	 = id;
//alert(siid + ' ' + id);
		$('#modalDeleteItem'+siid+id).modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ siid:siid,id:id,sub3:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		window.location.href='edit.php?sub=edit&id='+siid+'&active=active';	
		//location.reload();
	}


	function getmaterial(id){
		
        var sub    = 'sub33';
		var dtl_id			= document.getElementById('dtl_id').value;
//alert(id + ' ' + sub + ' ' + ' ' + ' Edit Func' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('.getmaterial').html(result);
		});

	}

	function getmaterial1(id){
		
        var sub    = 'sub3';
		
//alert(sub + ' ' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}

	function getunit(id){
		
        var sub    = 'sub4';
		//var grn_no = document.getElementById('grn_No').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      //$('#getunit').html(result);
			//alert(result);  
				var splitString = result.split("##");
		
				var uom 			=  splitString['0'];
				var account_name 	= splitString['1'];
				var tolerance_level = splitString['2'];
				var po_threashold 	= splitString['3'];
		
			  $("#itemUNITS").val(uom);
			  $("#posting_ACCOUNT_A").val(account_name);
			  //document.getElementById("itemUNITS").value = result;
			  
		});

	}

		function getgrnitem(id){
		
        var sub    = 'sub5';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem').html(result);
		});

	}
	
	function getgrnitem1(id){
		
        var sub    = 'sub5';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem1').html(result);
		});

	}

	
	function getcompany(id){
		
        var sub    = 'sub8';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getcompany').html(result);
		});

	}
	
	function getcompany1(id){
		
        var sub    = 'sub88';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub88:sub},function(result){
		      $('#getcompany1').html(result);
		});
	}
		
	function getbudgetname(id){
		
        var sub    = 'sub7';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}

	function getbudgetname1(id){
		
        var sub    = 'sub77';
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub77:sub},function(result){
		      $('#getbudgetname1').html(result);
		});

	}


	function getbudget(id){
		
        var sub    = 'sub6';
		var company_id   = document.getElementById('company_ID').value;
		var account_year = document.getElementById('account_YR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub6:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}
	
	function getbudget1(id){
		
        var sub    = 'sub66';
		var company_id   = document.getElementById('company_iD').value;
		var account_year = document.getElementById('account_yR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub66:sub},function(result){
		      $('#getbudgethead1').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}


	function getuser(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser').html(result);
		});

	}
	function getuser1(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser1').html(result);
		});

	}


	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;
			
			
		//alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "search_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}



	   function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
		function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
//		alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function getworkflowtype(id){
		
        var sub    = 'sub12';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub12:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}

	function getapprover(){
		
//		var department    	= document.getElementById("department").value;
        var dedtype    	    = document.getElementById("dedType").value;
		var company_id    	= document.getElementById("comp_id").value;
//alert('1111' + ' ' + company_id);
        var checker_value   = document.getElementById("checker_value").value;
		
		var sub             = 'sub24';

		$('.hidesend').hide();
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,dedtype:dedtype,checker_value:checker_value,sub24:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}


	function getcostcenter(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcenterC(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('.getcostcenter').html(result);
		});

	}
	
	function getcatbudget (id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}

	function getcatbudgetC(id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('.getcatbudget').html(result);
		});

	}
	
	

    function clearfld(){
		
		$('#itemDescription').html('');
		$('#itemQuantity').html('');
		$('#itemUnits').html('');
		$('#itemRate').html('');
		$('#itemGST').html('');
		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}

	
	function getvalidate(){
		
		var project 	=  $("#comp_id").val();
		var location 	=  $("#location").val();
		var department 	=  $("#department").val();
		var quotation_reference_no 	=  $("#our_po_ref_NO").val();
		var to_supplier 	= $("#suplier_name").val();
		
		var against_po_flag  = '';
		var against_po_flag1 = '';
		
		if (document.getElementById('against_po_FLAG1').checked) {
		    against_po_flag1 = document.getElementById('against_po_FLAG1').value;
			var against_po_flag = against_po_flag1;
		}
		if (document.getElementById('against_po_FLAG').checked) {
		    against_po_flag = document.getElementById('against_po_FLAG').value;
		}
		
		if(to_supplier==''){
			alert('Supplier selection mandatory !!!');
			return;
		}
		
 		if(quotation_reference_no==''){
			alert('PO Ref. No. selection mandatory !!!');
			return;
		}

		if(project==''){
			alert('Company selection mandatory !!!');
			return;
		}
/* 		if(po_doc_type==''){
			alert('Workflow type selection mandatory !!!');
			return;
		}
 */		
		if(location==''){
			alert('Location selection mandatory !!!');
			return;
		}
		if(department==''){
			alert('Department selection mandatory !!!');
			return;
		}
		
	}	
	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1 || row_affected==2 || row_affected==3 ){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}	
		if(row_affected==2 || row_affected==3 ){
			if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3 ){
			if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		
		
		return false;
		
	}
	
	function getgst(id){

//alert(id);		
		var splitString = id.split("-");
		
		var gst_id =  splitString['0'];
		var gst_perc = splitString['1'];
		
//alert(gst_id + ' ' + gst_perc);			
		$('#itemGST_ID').val(gst_id);
		$('#itemGST').val(gst_perc);
			  
	}	


	function gettdsperc1(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction1_amount').val(result);
		});
	}
	
	function gettdsperc2(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction2_amount').val(result);
		});
	}
	
	function gettdsperc3(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction3_amount').val(result);
		});
	}
	
	function getcomment(comment,si_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,si_id:si_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}

	function saveToDatabase_ln(editableObj,column,si_hdr_id,line_no) {
		    
		var editableObj = editableObj.innerHTML;	
		//alert("UPDATE `enqdetail` set " + editableObj  + ' ' + column);
		var sdivURL = "saveratedtl.php";
			$.post(sdivURL,{column:column,editval:editableObj,si_hdr_id:si_hdr_id,line_no:line_no },function(result){
			//	alert('Hello...');
				//$('#addbom_dtl123').html(result);
			});
			
	}
		

	function checkinvoice_no(id){
		
		var sub    = 'sub20';
		var suplier_id 	=  $("#suplier_NAME").val();
		var company_id  =  $("#company_ID").val();
//alert(sub+ ' ' + suplier_id + ' ' + id + ' ' + company_id); 
		$('#checkinvoice_no').html('');
		$('#hidesend').show();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,suplier_id:suplier_id,company_id:company_id,sub20:sub},function(result){
			var rowaffected = result;
			 //alert(rowaffected);
			  if(rowaffected>1){
				  var result = "Error : Invoice Number already available for supplier !";
				  alert(result);
				  $('#checkinvoice_no').html(result);
				  
				  $('#hidesend').hide();
			  }
			  else {
				$('#hidesend').show();	
			  }	  
		     // $('#checkinvoice_no').html(result);
			  
		});
	}		
</script>


</body>
</html>
