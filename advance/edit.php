<?php
$pgname = "advance/index.php";
include("../viewonly.php");
$sub_menu_hdr = $main_menu;

include("../header.php");
$modulePath = "advance/"; 

$_SESSION['reset'] = '1';

$user       = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];
$short_fy_code 		= $_SESSION['short_fy_code'];

?>
<!-- Select2 -->
  <link rel="stylesheet" href="https://nxtinfra-p2p.com/plugins/select2/select2.css">
  
<div class="content-wrapper">
<?php
if($_GET['sub']=='delete'){
	$av_id	= $_GET['av_id'];
	
	$payment_adjusted = 0;
	$sql = " SELECT a.st_flag, b.* FROM payment_header a, payment_details b 
				WHERE 1 and a.id = b.payment_hdr_id and a.st_flag = 'D' and b.supp_id = '$av_id' "; 
//echo $sql ."<BR>";			
	$q2	=	mysqli_query($con, $sql);
	while ($r2 =	mysqli_fetch_array($q2)){
		$payment_adjusted = $payment_adjusted + $r2['payment_adjusted'];
		$st_flag = $r2['st_flag'];
	}
	$sql = " select * from sma_advance where id = '$av_id' "; 
	$q2	=	mysqli_query($con, $sql);
	$r2 =	mysqli_fetch_array($q2);
	$company_id		 = $r2['company_id'];
	$remarks		 = $r2['remarks'];
		
	if($payment_adjusted>0){
		$sql="UPDATE sma_advance SET del = 'Y', paid_amount = paid_amount - '$payment_adjusted', paid_status = '' where id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql="UPDATE sma_advance SET paid_amount = 0 where 1 and paid_amount < 0 and id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
	}
	else if($payment_adjusted==0){
		$sql="UPDATE sma_advance SET del = 'Y', paid_status = '' where id = '$av_id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
	}		
//echo $sql ."<BR>";	
	$modulePath = 'advance/';
	$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				VALUES( 'AV', '$av_id', '$userid', now(), 'Deleted', '', now() ) ";
	$query=mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
				
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $av_id. ','. $remarks;
		    $affect 		= 'Deleted';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);

//echo $sql ."<BR>";
			
//exit('####1');
				
	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

	if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){
		$id					= $_POST['av_id'];
		if($_POST['submit1']){
			$approval_status	= $_POST['submit1'];
		}
		else if($_POST['submit2']){
			$approval_status	= $_POST['submit2'];
		}

		$status				= $_POST['status'];
		
		if( $status =='Draft' || $status =='' ){
			$approval_status 	= 'Pending';
		}

		$user   = $_SESSION['user'];

		$sql = "update sma_advance set approval_status	= '$approval_status', status =  'Submitted', changed_by = '$user', changed_date = now() where id = '$id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";

	}

	if($_GET['sub']=='Save'){
		$id				= $_POST['id']; 
		$av_id			= $_POST['id']; 
		$dated						= date('Y-m-d', strtotime($_POST["dated"]));
		$company_id					= $_POST["company_id"];
		$supplier_id   				= $_POST["supplier_id"];

		$department_id 				= $_POST["department_id"];
		$location_id   				= $_POST["location_id"];
		$remarks			     	= $_POST["remarks"];
		$scope_of_work     			= '';
		$subject					= $_POST['subject'];
		
		$po_ref_no					= $_POST['po_ref_no'];
		$advance_amount				= $_POST['advance_amount'];
		$total_po_amount			= $_POST['total_po_amount'];
		$invoice_no					= $_POST['invoice_no'];
		
		$approver_1			= $_POST['approver_1'];
		$approver_2			= $_POST['approver_2'];
		$approver_3			= $_POST['approver_3'];
		$approver_4			= $_POST['approver_4'];
		$approver_5			= $_POST['approver_5'];
		$approver_6			= $_POST['approver_6'];
		$approver_7			= $_POST['approver_7'];
		$approver_8			= $_POST['approver_8'];
		$approver_9			= $_POST['approver_9'];
		$approver_10		= $_POST['approver_10'];
		$status				= $_POST['status'];
			
		$sql = "UPDATE sma_advance SET dated	= '$dated',
						company_id				= '$company_id',
						supplier_id   			= '$supplier_id',
						department 				= '$department_id',
						location	   			= '$location_id',
						remarks			     	= '$remarks',
						subject					= '$subject',
						
						po_ref_no				= '$po_ref_no',
						advance_amount			= '$advance_amount',
						total_po_amount			= '$total_po_amount',
						
						invoice_no				= '$invoice_no'
				where id='$av_id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_advance set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_9			= '$approver_9',
						approver_10			= '$approver_10',
						approver_1_status	= '$approver_1_status',
						approval_status		= '$status',
						status				= '$status'
					where id='$av_id'";	
				$query=mysqli_query($con, $sql);	
//echo $sql. "<BR>";				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'AV', '$av_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";				
				$modulePath = "advance/"; 
				
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
				
				$pr_number 		= $_POST['pr_number'];
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$av_id;
		
				//$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$av_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$msg = 'Advance Number : '.$pr_number ;

				include "av_mail.php";
				
			}
			
//exit('Exit Here.... ');
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/AV/" . $av_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded, uploaded_by) VALUES('AV', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $av_id . ", now(), '$usrid' )";

					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/AV/" . $av_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

			$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$po_number 		= $_POST['po_number'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $po_number. ','. $department. ','. $party_name;
		    $affect 		= 'Modified';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$baseurl.=$modulePath."?id=$av_id";
			echo "<script>window.location.href='$baseurl';</script>";

	}

		if(isset($_GET['id'])) {
			$id 	= $_GET['id'];
			$av_id = $_GET['id'];
			$sql = "SELECT * FROM sma_advance WHERE id=" . $_GET['id'];
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			echo mysqli_error($con);    
			$dated = date('d-m-Y', strtotime($row['date']));
		}
		else {
			header("Location: " . $baseurl . $modulePath);
			die();
		}
	
		$av_id 			= $row['id'];
		$prdate			= date('Y-m-d', strtotime($row["dated"]));
		
		$dated  	= date('d-m-Y', strtotime($prdate));
		$fyr		= date('Y', strtotime($dated));
		$fmth		= date('m', strtotime($dated));
		
		$supplier_id	= $row['supplier_id'];
		
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}	
		$_SESSION['short_fy_code'] = $fin_year;
		
		$dt_error = '';
		$dt_error_msg = '';
		
		$upd_flag_v = '';
		$draft_by 		    = $row['draft_by'];
		$upd_flag 		    = $row['upd_flag'];
		
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];
		$approver_9 		= $row['approver_9'];
		$approver_10 		= $row['approver_10'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
		$approver_9_status 	= $row['approver_9_status'];
		$approver_10_status = $row['approver_10_status'];
		
	$status 		 = $row['status'];
	$approval_status = $row['approval_status'];
	$readonly = '';
	//if ($status == 'Submitted' || $status == 'Completed' || $status == 'Approved'){
	if ( $status == 'Submitted' || $status == 'Completed' ){
		$readonly = 'READONLY';
	}
	
		if($viewonly=='Y'){
		    $readonly = 'READONLY';
		}
		
		//echo $draft_by. ' ' . $user. "<BR>";
		$maker = '';
		if($draft_by == $user){
			$maker = 'Y';
		}	
		

//Approved by approver make it READONLY 
	//	include "../readonly_approved.php";
		
	
?>

<!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $sub_menu;?> 
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"><?= $sub_menu;?> </a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="edit.php?sub=Save" method="post" enctype="multipart/form-data">
                          
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
		   <?php 
				$status = $row['status'];
				$del 	= $row['del'];
				$approval_status = $row['approval_status'];
				if($approval_status=='Rejected'){
					$status = 'Rejected';
				}
				if($del=='Y'){
					$status = 'Deleted';
				}				
			?>	          
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'index.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
						 &nbsp;&nbsp;
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $upd_flag_v. ' ' . $status;?></b>&nbsp;&nbsp;&nbsp;</h4> </span>


						<input type="hidden" name="id" value="<?php echo $id;?>">
						<input type="hidden" id="AvId_e" value="<?php echo $id;?>">
						
					  <input type="hidden" name="av_id" value="<?php echo $av_id;?>">
			
					  <input type="hidden" name="status" value="<?php echo $row['status'];?>">
					  
				<div class="box-body">		
				<?php
					if ($_GET['active']){
						$active = $_GET['active'];
						$active_1 = ' ';
					}
					else if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active = ' ';
							$active_1 = ' ';
							
					}
					else
					{
						$active_1 = 'active';
					}
					
					$company_id = $row['company_id'];
					
				?>
					
				<?php
					$po_ref_no  = $row['po_ref_no'];
					$sql  = " SELECT * from sma_purchase_order where id = '$po_ref_no' ";
						$res  = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
						
						$po_number			= $r1['po_number'];
						$location_id 		= $r1['location'];	
						$supplier_location	= $r1['supplier_location'];
						$comp_id 			= $r1['project'];
						$trans_type			= $r1['trans_type'];
						$suplier_name		= $r1['to_supplier'];
						$department_id			= $r1['department'];
						$credit_days		= $r1['credit_days'];
						$total_po_amount	= $r1['total_po_amount'];
						$paid_amount		= $r1['paid_amount'];
						$bal_amount = $total_po_amount - $paid_amount;
						
					$sqll = "";	
					if($po_ref_no==0){
						$sqll = " OR ( project = '$company_id' and to_supplier = '$supplier_id' and del !='Y' ) ";
						$sql = "select * FROM sma_purchase_order WHERE 1  and ( id = '$po_ref_no' ) $sqll ";	
					}				
					$sql_comp = "select comp_name from company where comp_id = '$company_id' ";
					$q_comp = mysqli_query($con, $sql_comp);
					$r_comp = mysqli_fetch_array($q_comp);
					$company_name_v = $r_comp['comp_name'];
				
					$sql_dept = "select name from sma_department where id = '$department_id' ";
					$q_dept = mysqli_query($con, $sql_dept);
					$r_dept = mysqli_fetch_array($q_dept);
					$department_name_v = $r_dept['name'];
				
					// Dated
					$pr_date_v = date('d/m/Y', strtotime($row['dated']));
				
					$label_line = '<b>Sr. No. :</b> '.$row['id']. ' &nbsp; ' . ' <b>PO No. :</b> '.$po_number. ' &nbsp; ' .
								' <b>Company :</b> '.$company_name_v. ' &nbsp; ' . 
								' <b>Dept :</b> ' .$department_name_v . ' &nbsp; ' . ' <b>Dated :</b> ' .$pr_date_v;
				?>

					
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" ><?= $sub_menu;?>  </a></li>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>
						<!--<li><a href="purchase_req_prn.php?sub=pdf&id=<?php echo $av_id;?>&comp_id=<?php echo $row['company_id'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>-->
						
				<?php
						$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c 
									where a.id  = '$id' and a.id = c.supp_id 
									and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.del !='Y'  ";
										//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
										$q21 = mysqli_query($con, $sql);
										$rowaffect_po = mysqli_affected_rows($con);
										if ($rowaffect_po > 0) {
											?>
											<li><a href="#tab_6" data-toggle="tab" class="btn btn-info" id="six_tab">Advance Payment</a></li>
						<?php } ?>
                    </ul>
				<div class="well well-sm" style="background-color: #f4f4f4; border-left: 5px solid #3c8dbc; padding: 10px 15px; margin-top: 10px; margin-bottom: 10px; font-size: 16px; color: #333;">
					<?php echo $label_line; ?>
				</div>
					
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group ">
						
						<?php  ?>
							<div class="col-xs-2">
								<label for="prDate" class=" control-label">Serial Number</label>
                                   <input type="text" class="form-control" id="srno" name="srno" readonly  style="text-align:right;" value="<?php echo $av_id ?>">
                                </div>
								
								<div class="col-xs-2">
									<label for="prDate" class=" control-label">Dated</label>
                                    <div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" readonly id="dated" name="dated" placeholder="dd-mm-yyyy"
                                               value="<?= date('d-m-Y', strtotime($row['dated'])); ?>">
                                    </div>
                                </div>
                            
								<div class="col-sm-4">
									<label for="company" class="control-label">Company*</label>
                                    <select class="form-control" required id="company_id" name="company_id" readonly <?= $readonly;?> > 
											
										<?php
                                            $sql="SELECT * FROM company where 1 and comp_id in ($comid) and comp_id = '$company_id' ORDER BY comp_name ASC";
                                            $res = mysqli_query($con, $sql);
                                            echo mysqli_error($con);
                                            while($r2 = mysqli_fetch_array($res)){
                                        ?>
                                            <option value="<?php echo $r2['comp_id']?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
									
									<div class="col-sm-4">	
										<label class="control-label">Supplier Name <span style="color:red;"> **</span></label>
										<select class="form-control select2 " required id="supplier_id" readonly <?= $readonly;?> name="supplier_id" onchange="getporefno(this.value);">
										
											<!--<option value=""> Select </option>-->
										
												<?php $sql = "select * from sma_party_mst where 1 and id = '$supplier_id' order by party_name ";
												$q2 	  = mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" <?php echo ($supplier_id == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
												<?php } ?>
										</select>
									</div>
							
						</div>
								
								
						<div class="form-group ">
						
							<span id="getporefno">	
								<div class="col-md-5">
									<label class="control-label">PO Ref.No.</label>
									<select class="form-control" name="po_ref_no" id="po_ref_no" readonly <?= $readonly;?> onchange="getproject(this.value);" >
						<?php
									//echo '<option value=""> Select </option>';
									$sql = "select * FROM sma_purchase_order WHERE 1  and ( id = '$po_ref_no' ) $sqll ";				
									$q2  = mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_object($q2)){
										$our_po_ref_no 	= $r2->po_number;
										$dated 			= $r2->dated;
										$id		 		= $r2->id;
						?>			
										<option value="<?= $id ?>" <?php echo ($po_ref_no == $id )?'selected="selected"':'';?> > <?= $our_po_ref_no. '-' . $dated ?></option>
						<?php       };
									
								?>
									</select>
								</div>
							</span>	
							
					<?php
						$sql = "select * FROM sma_po_items WHERE purchase_id = '$po_ref_no' and budget_head !='' and budget_name !='' ";				
						$q2  = mysqli_query($con, $sql);
						$bdcnt = mysqli_affected_rows($con);
						if(empty($bdcnt)){
							$sql = "select b.* FROM sma_po_items a, sma_budget b WHERE 1 and b.id = a.budget_id and purchase_id = '$po_ref_no' ";
							$q2  = mysqli_query($con, $sql);
						}	
						$r2 = mysqli_fetch_array($q2);
						
							$budget_name_id = $r2['budget_name'];			
							$budget_head_id = $r2['budget_head'];
							$sql = "SELECT * FROM sma_budget_name where id = '$budget_name_id' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_object($q2);
							$budget_name = $r2->name;
							
							$sql = "SELECT * FROM sma_budget_subgroup where id = '$budget_head_id' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_object($q2);
							$budget_head = $r2->budget_head;
						
					?>	
							<div class="col-md-3">
								<label class="control-label">Budget Group</label>
								<input type="text" class="form-control" readonly value="<?php echo $budget_name ?>">
							</div>
							<div class="col-md-4">
								<label class="control-label">Budget Sub Group</label>
								<input type="text" class="form-control" readonly value="<?php echo $budget_head ?>">
							</div>
						</div>
								
								
						<div class="form-group ">
								
    					<?php
							$sqld = "";	
    						if($status != 'Draft'){
    							$sqld = " and id = '$department_id' ";
    						}	
    				//echo "select DISTINCT(department_id) from sma_workflow where company_id = '$company_id' and doc_type = 'PR' ";
						?>		
								<div class="col-sm-4">
                                    <label for="department" class="col-sm-2 control-label">Department*</label>
                                    <select class="form-control " id="department" readonly name="department_id"  <?= $readonly;?>  >
								<?php if(empty($readonly)){ ?>      
                                 		<!--<option value=""> Select </option>-->
                                <?php } ?>
                                    <?php
                                    	$sql = "select * from sma_department where 1 and id = '$department_id' order by name ";
                                        $res = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($r2 = mysqli_fetch_array($res)){
                                    ?>
                                        <option value="<?php echo $r2['id']?>"  <?php echo ($r2['id'] == $department_id) ? "selected" : ""; ?> ><?php echo $r2['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                        <?php        
                            //$location_id    = $row['location'];
                            $sqld = "";	
    						if($status != 'Draft'){
    							$sqld = " and id = '$delivery_location_id' ";
    						}  
    					?>	
    							<div class="col-sm-4">
    						    <label for="company_id" class="control-label ">Plaza/Location Name *</label>
    						    <span id="getlocation">
    						        <select class="form-control select3"  readonly <?= $readonly;?> name="location_id" id="location_id" 
    						                    onchange="getdelivery_address(this.value);" >
    						<?php if(empty($readonly)){ ?>      
                                 		<!--<option value=""> Select </option>-->
                            <?php } ?>     		
    										<?php $sql = "select * from sma_location where id = '$location_id' order by `loc_name`";
    										$q2 	= mysqli_query($con, $sql);
    										while($r2 = mysqli_fetch_array($q2)){ ?>
    									<option value="<?php echo $r2['id'];?>" <?php echo ($location_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
    										<?php } ?>
    								</select>
    						    </span>      
    						    </div>
							</span>

						
							<div class="col-md-2">
								<label class="control-label">PO Value</label>
								<input class="form-control" readonly name="total_po_amount" style="text-align:right;" value ="<?= $total_po_amount;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Advance Balance</label>
								<input class="form-control" readonly style="text-align:right;" value ="<?= $bal_amount;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Amount Pay Now</label>
								<input class="form-control" name='advance_amount' id='advance_amount' <?= $readonly; ?> style="text-align:right;" value ="<?= $row['advance_amount'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Proforma Invoice No.</label>
								<input class="form-control" name='invoice_no' id='invoice_no' style="text-align:right;" value ="<?= $row['invoice_no'];?>" >
							</div>
							
						<?php
					//select * from payment_header a, payment_details b where 1 and del !='Y' and a.id = b.payment_hdr_id and b.supp_id ='23' and a.st_flag = 'D';		
							$sql="select a.* from payment_header a, payment_details b where 1 and del !='Y' and a.id = b.payment_hdr_id 
									and b.supp_id ='$po_ref_no' and a.st_flag = 'D' ";
							$qry = mysqli_query($con, $sql); //and status != 'Draft'
							$r2 = mysqli_fetch_array($qry);
							$py_id = $r2['id'];
								
								$sql="Select * from sma_purchase_order where 1 and del !='Y'  and id ='$po_ref_no'";
								$qry = mysqli_query($con, $sql); //and status != 'Draft'
								$r2 = mysqli_fetch_array($qry);
								$po_id = $r2['id'];
								$po_type = $r2['po_type'];
								if($po_type=='C'){
									$baseurl_mrn = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
								}	
								else if($po_type=='M'){
									$baseurl_mrn = $baseurl . "purchase_order_ml/edit.php?sub=edit&id=$po_id";
								}
							if($po_id>0){	
								
						?>
								<div class="col-md-2">		
								    <label class="control-label" style="font-size:14px;" >Document:</label>
									<a href="<?php echo $baseurl_mrn; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Purchase Order</span></a>
								</div>
							<?php } ?>	
						</div>
							        
						    
						
							<div class="form-group">
								
								<div class="col-sm-8">
								    <label for="company_id" class="control-label ">Remarks </label>
                                     <textarea rows = "2" class="form-control" id="remarks" name="remarks"  <?= $readonly;?> ><?= $row['remarks'];?></textarea>
                                </div>
                                
                            </div>
							
														
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
	
				</div>
				<?php
					if ($_GET['active']){
						$active = $_GET['active'];
					}
				?>
				
				
                        <div class="tab-pane" id="tab_2">
                            <!-- Attachments -->
							
							 <?php 
                              $sql = "SELECT * FROM file_uploads WHERE module = 'AV' AND reference_id = " . $av_id;
                              $docResults = mysqli_query($con, $sql);
							  
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%">Document Type</th>
                                          <th width="30%">Document Name</th>
                                          <th width="30%">Description</th>
                                          <th width="15%">Uploaded by</th>
										  <th width="5%"></th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id = '$doc_type'";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$rw1 = mysqli_fetch_array($rs);
													$document = $rw1['document'];
											        
											        $uploaded_by = $docRow['uploaded_by'];
											        $sql="SELECT * FROM sma_user where id = '$uploaded_by'";
													$rs = mysqli_query($con, $sql);
													$rw1 = mysqli_fetch_array($rs);
													$uploaded_by_name = $rw1['username'];
													
													$date_uploaded		= date('d-m-Y h:i:sa', strtotime($docRow['date_uploaded']));
														$date_uploaded		= date('d-m-Y h:i:sa', strtotime($docRow['date_uploaded']));
													$date_uploaded_v		= date('d-m-Y', strtotime($docRow['date_uploaded']));
													if($date_uploaded_v=='30-11--0001' || $date_uploaded_v=='01-01-1970' ){
													    $date_uploaded ='';
													}
											  ?>
                                          <tr>
                                              <td width="20%"><?php echo $document; ?></td>
                                              
											  <td width="20%" > <a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td width="30%"><?php echo $docRow['doc_desc'] ?></td>
                                              <td width="15%"><?php echo $uploaded_by_name. "<BR>".$date_uploaded; ?></td>
                                              
								<?php 
								    if( $status=='Draft' || $status=='Submitted' || $maker=='Y' ){ ?>
											   <td width="5%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                <?php } ?>             
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
                                
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control doctype" name="doctype[]">
                                            <option value="">Select</option>
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
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="30%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td width="10%"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div> 
						  
  							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<span id="predit"></span>
								
							</div>
							
					
					<div class="box-footer">
					
						<div class="col-sm-6">
							<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&av_id='.$av_id ; ?>
						
						<?php		
						//echo $po_id. "<>" . $py_id;
							//|| $user =='Admin' $status == 'Draft' ||  $po_id==0 || 
							if ($status == 'Draft' ){
						?>
						<!--	<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse"><span data-toggle="tooltip" title="If Draft" class="badge bg-light-blue">?</span> Delete</a>-->
							<a href="#makeDeleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDeleteAuthority"><span data-toggle="tooltip" title="If Payment not created" class="badge bg-light-blue">?</span> Delete</a>	
						<?php	} ?>
							<span>&nbsp;&nbsp;</span>
							
						<?php		
							if ( ( ( $status == 'Submitted' || $approval_status=='Rejected') && $user =='Admin' ) || ($$py_id==0 && $status != 'Draft' && $user =='Admin' ) || ($$py_id==0 && $status != 'Draft' ) ){
						?>	
							<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority"><span data-toggle="tooltip" title="If Not Draft OR Payment not created" class="badge bg-light-blue">?</span> Recall</a>	
						<?php } ?>
						
						<?php		
							if ( $status == 'Submitted' && ($user =='Admin' || $user =='Billdesk@athaanginfra.in' ) ){
						?>	
							<!--<a href="#MakeDuplicateMRN" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#MakeDuplicateMRN">Clone</a>	-->
						<?php } ?>
						
						</div>
				<?php		
				//	if ($status != 'Completed'){
				?>	
 			
						<div class="col-sm-6 text-right">
						<?php		
						$role			= $_SESSION['role'];
						$userid   	= $_SESSION['usrid'];
							//		echo $status . ' '."<BR>";
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag='';
									if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' ||
										$userid == $approver_4 && $approver_4_status=='Submitted' ||
										$userid == $approver_5 && $approver_5_status=='Submitted' ||
										$userid == $approver_6 && $approver_6_status=='Submitted' ||
										$userid == $approver_7 && $approver_7_status=='Submitted' ||
										$userid == $approver_8 && $approver_8_status=='Submitted' ||
										$userid == $approver_9 && $approver_9_status=='Submitted' ||
										$userid == $approver_10 && $approver_10_status=='Submitted' 
										){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) && empty($approver_9_status)
										&& empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_2) && $budget_chk=='Y'){
									        $approver_flag='';
										}
										
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_3) && $budget_chk=='Y'){
									        $approver_flag='';
										}
										
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_4) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_5) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_6) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_7) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) 
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_8) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted'
										&& empty($approver_9_status) && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_9) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Approved'
										&& $approver_9_status=='Submitted' && empty($approver_10_status)
										){
										$approver_flag='Y';
										if(empty($approver_10) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved'    && $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Approved'
										&& $approver_9_status=='Approved' && $approver_10_status=='Submitted'
										){
										$approver_flag='Y';
										if(empty($approver_10) && $budget_chk=='Y'){
									        $approver_flag='';
										}
									}
									
								}
					//	echo $approver_flag. " >><< <BR>";		empty($budget_chk)
								
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
						?>	
						
								<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Approve </a>
								<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
						<?php	}
							}
						
									$_SESSION['av_id'] 	= $av_id;
									$_SESSION['status']  = $row['status'];
						//	echo $status . ">><<<";
						
							if ($status == 'Draft' || $status == 'Completed' || $status == 'Submitted' ){
							?>	
							
							<span class='hideSave' >
								<input type="submit" class="btn btn-primary" value="Save" name="Save">
							</span>
					
								<span>&nbsp;&nbsp;</span>
									<!--<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#approvalAuthority">Send </a>-->
									
							<?php 
							//&& $budget_id > 0
							    if( $status == 'Draft' ){ ?>	
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
							<?php } ?>
							
					<?php	} ?>
						
								<span>&nbsp;&nbsp;</span>
								
								<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'index.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
								
						<!--		<input type="submit" class="btn btn-primary"  name="submit" value="Submit" >-->
							
					
						
						</div>
						
						<?php	//} ?>
						
					</div>	
					
				
						  
						  <?php  
//echo $status. ' ' .	$approver_1. "<BR>";					  
						if( $status == 'Submitted' ){
							include "../show_approver_name.php";
						}
								
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
							<div class="box-footer">
								
							</div>
						
						</span>
				<?php 	} ?>		
				
						</div>
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $av_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AV' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AV' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
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
												
												$role = $rw1['role'];

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
						
						<?php	//Advance Payment Start ?>
									<div class="tab-pane" id="tab_6">
										

										<div class="box-body">
											<table id="prtable123" class="table table-bordered table-striped">

												<thead>
													<tr>
														<th>SrNo.</th>
														<th>Paid On</th>
														<th>Paid via</th>
														<th>UTR.No./ Cheque No.</th>
														<th style="text-align:right;">Amount Paid</th>

													</tr>
												</thead>
												<tbody>
													<?php
													
													$sql = "SELECT b.*, c.*  FROM `sma_advance` a, payment_header b, payment_details c where a.id = '$av_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.del !='Y' ";
													
											//echo $sql."<BR>";								//and b.utr_no !='' and a.status = 'Completed' 
													$q21 = mysqli_query($con, $sql);
													$rowaffect = mysqli_affected_rows($con);

													?>
													<?php
													$q21 = mysqli_query($con, $sql);
													while ($r21 = mysqli_fetch_array($q21)) {
														$py_id = $r21['payment_hdr_id'];

														$cash_bank_name = $r21['cash_bank_name'];
														$sql = "select * from account_mst where id = '$cash_bank_name' ";
														$q2 = mysqli_query($con, $sql);
														$r2 = mysqli_fetch_array($q2);
														$cash_bank_name = $r2['account_name'];

														$paid_to = $r21['paid_to'];
														$st_flag = $r21['st_flag'];
														if ($st_flag == 'A' || $st_flag == 'T') {
															$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
															$q2 = mysqli_query($con, $sql);
															$r2 = mysqli_fetch_array($q2);
															$party_name = $r2['username'];
														} else {
															$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
															$q2 = mysqli_query($con, $sql);
															$r2 = mysqli_fetch_array($q2);
															$party_name = $r2['party_name'];
														}

														if ($st_flag == 'S') {
															$st_flag = 'SI';
														} else if ($st_flag == 'A') {
															$st_flag = 'TA';
														} else if ($st_flag == 'T') {
															$st_flag = 'TE';
														} else if ($st_flag == 'C') {
															$st_flag = 'OE';
														} else if ($st_flag == 'D') {
															$st_flag = 'SA';
														}

														$dated = date('d-m-Y', strtotime($r21['dated']));
														if ($dated == '01-01-1970') {
															$dated = '';
														}

														$paid_date = date('d-m-Y', strtotime($r21['paid_date']));
														if ($paid_date == '01-01-1970') {
															$paid_date = '';
														}

														$sql = "SELECT supplier_invoice_no, supp_id FROM `payment_details` where payment_hdr_id = '$py_id' ";
													
														$q2 = mysqli_query($con, $sql);
														$r2 = mysqli_fetch_array($q2);
														$supplier_invoice_no = $r2['supplier_invoice_no'];
														$supp_id = $r2['supp_id'];

														$total_amount_paid_net = $total_amount_paid_net + $r21['payment_adjusted'];

														$approval_status = $r21['approval_status'];

														$baseurl1 = $baseurl . 'payment/' . 'edit.php?sub=edit&id=' . $py_id;

														?>
														<a href="<?php echo $baseurl . 'payment/' . "edit.php?sub=edit&id=" . $py_id; ?>"
															target="_blank" title="Edit">
															<tr style="cursor:pointer; "
																onmouseover="ChangeBackgroundColor(this)"
																onmouseout="RestoreBackgroundColor(this)"
																onclick="window.open('<?php echo $baseurl1; ?>', '_blank')">
																<td width="2%" style="text-align:right;">
																	<?php echo $py_id; ?></td>
																<td width="10%" <?php echo $styl; ?>>
																	<?php echo $paid_date; ?></td>
																<td width="12%" <?php echo $styl; ?>>
																	<?php echo $cash_bank_name; ?></td>
																<td width="10%" <?php echo $styl; ?>>
																	<?php echo $r21['utr_no'] . ' ' . $r21['cheque_no']; ?>
																</td>
																<td width="10%"
																	style="text-align:right;<?php echo $styl2; ?>">
																	<?php echo number_format($r21['total_amount_paid'], 2); ?>
																</td>

															</tr>
														</a>

													<?php }

													?>
												</tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<th>Total</th>
													<td width="10%" style="text-align:right;">
														<?= number_format($total_amount_paid_net, 2); ?></td>
												</tr>
											</table>
								<?php
									if($total_amount_paid_net>0){	
										$sql = " UPDATE sma_purchase_order SET paid_amount = '$total_amount_paid_net' where id = '$our_po_ref_no' ";
										mysqli_query($con, $sql);
									}
								?>		


										</div>

									</div>
									<?php	//Advance Payment End ?>
									
<!--Comment Section Start-->				
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			

				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$av_id' and doc_type = 'AV' order by id desc ";
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
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $av_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $av_id;?>,'AV','C',<?= $page;?>)" >Submit</button>		
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
<!--Comment Section End-->				
												
						
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

					
                    </fieldset>
				
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$av_id 	= $_SESSION['av_id'];
											$status = $_SESSION['status'];
											
											$sql="SELECT * FROM sma_advance where id = '$av_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
												
										?>
										
										<input type="hidden" name="av_id" id="pr_idE" value="<?php echo $av_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										
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
										
                                    
									</form>	
								</div>

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
											$av_id 	= $_SESSION['av_id'];
											$status = $_SESSION['status'];
											
											$sql="SELECT * FROM sma_user where userid = '$draft_by' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$userid = $rw['id'];
										?>
										
										<input type="hidden" name="av_id" id="pr_idE" value="<?php echo $av_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverE" name="approver" value='Reject'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
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

<!--Reject Workflow Popup End -->	  


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
										
										<input type="hidden" name="av_id" id="av_idD" value="<?php echo $av_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										
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
	  

<!--Make to DraftPopup-->
<div class="modal fade" id="makeDeleteAuthority" role="dialog" aria-labelledby="makeDeleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDeleteAuthority">Do you want to Delete ? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										
										<input type="hidden" name="av_id" id="av_idL" value="<?php echo $av_id; ?>" >
										<input type="hidden" id="modeL" name="mode" value='Accept'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusL" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksL"></textarea>
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

<!--Make to DraftPopup-->					

<!--MRN Copy Popup Start -->
<div class="modal fade" id="MakeDuplicateMRN" role="dialog" aria-labelledby="MakeDuplicateMRN">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="MakeDuplicateMRN">Copy MRN To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$av_id 	= $_SESSION['av_id'];
											$status = $_SESSION['status'];
								//	echo	 $sql="SELECT * FROM company where 1 and comp_id in ($comid) ORDER BY comp_name ASC";	
										?>
										
										<input type="hidden" name="av_id" id="pr_idC" value="<?php echo $av_id; ?>" >
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-2 control-label">Status</label>
                                            <div class="col-sm-2">
												<input type="text" class="form-control" id="statusC" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
									<div class="form-group">	
										<label for="company" class="col-sm-2 control-label">Company*</label>
										<div class="col-sm-6">
											<select class="form-control" id="company_idC" name="company_id" required >
															
											<?php
											 $sql="SELECT * FROM company where 1 and comp_id in ($comid) ORDER BY comp_name ASC";
											$res = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($res)){
											?>
												<option value="<?php echo $r2['comp_id']?>" <?php echo ($r2['comp_id'] == $row['company_id']) ? "selected" : ""; ?>><?php echo $r2['comp_name'] ?></option>
											<?php } ?>
											</select>
										</div>
									</div>	
										
									<div class="form-group">
										<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                           <div class="col-sm-10">
											<textarea class="form-control" rows="3" name="remarks" id="remarksC"></textarea>
										</div>
									</div>
										
                                    
									</form>	
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" id="CopyMRN">Submit</button>
								</div>
				
                                </div>
								
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>


<!--/.col (right) -->

<!-- For Document Attachment Start
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td width="30%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="30%" ><input type="file" name="fudoc[]" class="docfile"></td><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<?php 	
		include("../footer.php");	
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>

<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!--File Input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/fileinput.min.js"></script>

<script>
	
    var itemArray = []; // stores all item details table values in memory

    function initSelect2() {
        $('.select2').each(function() {
            var modal = $(this).closest('.modal');
            if (modal.length > 0) {
                $(this).select2({
                    dropdownParent: modal
                });
            } else {
                $(this).select2();
            }
        });
    }

    $(document).ready(function () {
        $('.datepicker').datepicker();
        // $('.datepicker').datepicker({
        //     "format": 'd/M/Y',
        //     "autoclose": true
        // });
        initSelect2();
        CKEDITOR.replace('background_section');
        CKEDITOR.replace('scope_of_work');		
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

    function validateInputs() {
        if ($("#reqDate").val() === '') {
            return false;
        }
        if (itemArray.length == 0) {
            $("#err").html("Please add items to the Advance");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
    }

	
    function calculateTotalAmounte() {
		var qty1='';
		var rate1='';
		var amt1='';
		
//        var qty1 = $('#itemQuantity_e').val();
//        var rate1 = $('#itemRate_e').val();
//		var gst1 = $('#itemGST_e').val();
		var qty1 = document.getElementById("itemQuantity_e").value;
		var rate1 = document.getElementById("itemRate_e").value;
		var gst1 = document.getElementById("itemGST_e").value;
//alert(qty1 + ' <> ' + rate1 + ' <> ' +  amt1);
        var amt1 = qty1 * rate1;
//		var amt1 = amt1 ;
        amt = parseFloat(amt1);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount_e').val(amt1);

    }

	
    function calculateTotalAmount() {
        //itemQuantity  item_rate item_rate
        var qty = $('#itemQuantity').val();
        var rate = $('#item_rate').val();
//		var gst = $('#itemGST').val();
        var amt = qty * rate;
//		var amt = amt + (amt * gst /100);
        amt = parseFloat(amt);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#item_total_Value').val(amt);
    }

    $('#modalDeleteItem').on('show.bs.modal', function(e) {
        var tempId = $(e.relatedTarget).data('id');
        var i;
        for (i = 0; i < itemArray.length; i++) {
            var obj = itemArray[i];
            if (obj.tempId == tempId) {
                $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                $(e.currentTarget).find('div[class="modal-body"]').html('Are you sure you want to delete item "' + obj.name + "'");
                break;
            }
        }
    });

    

    function setSelectedValue(object, value) {
        for (var i = 0; i < object.options.length; i++) {
            if (object.options[i].text === value) {
                object.options[i].selected = true;
                return;
            }
        }

        // Throw exception if option `value` not found.
        var tag = object.nodeName;
        var str = "Option '" + value + "' not found";

        if (object.id != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@id='" + object.id + "']."
        }

        else if (object.name != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@name='" + object.name + "']."
        }

        else {
            str += "."
        }

        throw str;
    }

   
    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var av_id		 	=  $("#pr_idE").val();
		var approver		=  $("#approverC").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(approver + ' ' + status + ' ' + mode);		
		
		$('#predit').html('Wait...');
		
		 $('#approvalAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ av_id:av_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
			//alert(result);				
		      $('#predit').html(result);
		});
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);
		var av_id		 	=  $("#pr_idE").val();
        var approver 		=  $("#approverE").val();
		var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
		var statusap		= 'Reject';
		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ av_id:av_id,
						mode:mode,
						statusap:statusap,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

    
</script>

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
        $("#prItemsTablee").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

	function getproduct(id){
		var sub    = 'sub3';
		var strURL = "app_func.php";
//alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getproduct').html(result);
		});

	}
	
	function getbudgetcheck(id){	
        var sub    = 'sub1';
        var av_id 	=  $("#purchaseId").val();
        
        var budget_name 	=  $("#budget_name").val();
        
		var strURL = "app_func.php";
		$.post(strURL,{id:id,av_id:av_id,budget_name:budget_name,sub1:sub},function(result){
		      $('#getBUDGET').html(result);
		});
	}
	

	function getbudgethead(id){
		
        var sub     = 'sub2';
        var av_id 	=  $("#purchaseId").val();
//alert(sub + ' ' + av_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,av_id:av_id,sub2:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}

	function getunit2(id){	
        var sub    = 'sub4';
		var company_id 	=  $("#company_id").val();
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub4:sub},function(result){
		      $('#getunit2').html(result);
		});
	}

    function getdesc(id){	
        var sub    = 'sub4AB';
        
        $('.editItemSave').show();
        $('#addItem').show();
		$('.getberror').html('');
		
        var av_id 	=  $("#purchaseId").val();
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,av_id:av_id,sub4AB:sub},function(result){
		      //$('#getdesc').html(result);
		      var splitString = result.split("####");
				var msg 	=  splitString['0'];
				var rowcnt 	= splitString['1'];
			//alert(msg);	
				if(rowcnt==0){
			//	alert(msg);    
			//	    $('.editItemSave').hide();
			//	    $('#addItem').hide();
				    //$('.getberror').html(msg);
				    $('#getdesc').html(msg);
				   //$('#getdesc').html(result);
		        }
		        else {
		      $('#getdesc').html(msg);
		        }
		});
		
	}
	
	function getdupprd(id){	
        var sub    = 'sub4A';
		var purchase_req_id =  $("#purchaseId_e").val();
		$('#addItem').show();
		$('#getdupprd').html('');
//alert(sub + ' ' + purchase_req_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,purchase_req_id:purchase_req_id,sub4A:sub},function(result){
		        var splitString = result.split("###");
				var msg 	=  splitString['0'];
				var rowcnt 	= splitString['1'];
				if(rowcnt>0){
				//    $('#addItem').hide();
		        }
		      //$('#getdupprd').html(msg);
		});
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5A:sub},function(result){
		      $('#qty_value').html(result);
		});
		
		
	}

	function getunit1(id){	
        var sub    = 'sub44';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub44:sub},function(result){
		      $('#getunit1').html(result);
		});
	}
	
  function validateInputs() {
        if ($("#reqDate").val() === '') {
            return false;
        }
        if (itemArray.length == 0) {
            $("#err").html("Please add items to the Advance");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
    }


	function getdelvaddr(id){
        var sub    = 'sub6';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getdelvaddr').html(result);
		});
	}


	function getlocation(id){
		
        var sub    = 'sub5';
//alert(sub);
		var company_id =         $("#company_id").val();
        
//alert(sub + ' ' + company_id );
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,sub5:sub},function(result){
		      $('#getlocation').html(result);
		});
	}

	function getuser(id){
		
        var sub    = 'sub8';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getuser').html(result);
		});

	}
	
	function getapprover(){
		
		var av_id =  $("#AvId_e").val();
		//var department		=  $("#department").val();
		
		//var company_id    	= document.getElementById("company_id").value;
		var checker_value		=  $("#advance_amount").val();

//alert(checker_value);
		var sub = 'sub24';
		$('.hidesend').hide();
		$('.hideSave').hide();

		//checker_value:checker_value,
		var strURL = "app_func.php";
		$.post(strURL,{av_id:av_id,checker_value:checker_value,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}
	
	function getmaterial1(id){
		
        var sub    = 'sub3AB';
		//var company_id 	=  $("#company_id").val();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3AB:sub},function(result){
		      $('#getmaterial1').html(result);
		      initSelect2();
		});

	}


    function getsubGroup(id){
		
        var sub    = 'sub3A';
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3A:sub},function(result){
		      $('#getsubGroup').html(result);
		      initSelect2();
		});

	}
	
	function getcomment(comment,av_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
		$('#getcomment').html('Wait...');
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,av_id:av_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
	
	$("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var av_id		 	=  $("#av_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
//alert(remarks +  ' ' + av_id );

		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}

		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ av_id:av_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});


	$("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeL").val();
		var av_id		 	=  $("#av_idL").val();
        var status 			=  $("#statusL").val();
		var remarks			=  $("#remarksL").val();
//alert(remarks +  ' ' + av_id );

		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}

		$('#makeDeleteAuthority').modal('hide');
		var strURL = "py_del_func.php";
		$.post(strURL,{ av_id:av_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});


	function getworkflowtype(id){
		
        var sub    = 'sub27';
        var company_id =         $("#company_id").val();
        var department =         $("#department").val();
        
        $('#workflowtypeErr').html('');
        $('.hideSave').show();
        
//alert(sub  + ' ' +  company_id + ' ' + department);
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,department:department,sub27:sub},function(result){
		        var myArray = result.split("####");
		      var chkarr   = myArray['0'];
		      var chkarr1  = myArray['1'];
		      
		      if(chkarr == 0){
		          $('#getworkflowtype').html(chkarr1);
		          $('#workflowtypeErr').html('select workflow type...');
		          $('.hideSave').hide();
		      }
		      else {
		          $('#getworkflowtype').html(result);
		      }
		      
		});

	}
	
	 function getprojectname(id){
        
        var sub    = 'sub10';
         $('#getprojectname').html('result');
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub10:sub},function(result){
		      $('#getprojectname').html(result);
		});
        
    }
    
   function getdelivery_address(id){
        
         var sub    = 'sub6';
         
//alert(sub + ' ' +  id);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){

		      $('#getdelivery_address').html(result);
		});
        
    }
    
    function validateDate_edit(delivery_require_by){
	
		let date =
                document.getElementById('deliveryDate').value;

      
		$('#hideSave').show();
		$('#validateDate').html("");
		let dateArray = date.split("-");

			let ddate = `${dateArray[2]}/${dateArray[1]}/${dateArray[0]}`;
//alert(ddate);				
            let inpDate = new Date(ddate);	
            let currDate = new Date();		

//alert(inpDate + ' ' + currDate);
            $('.editItemSave').show();
            //if (inpDate.setHours(0, 0, 0, 0) == currDate.setHours(0, 0, 0, 0)) {
			if (inpDate.setHours(0, 0, 0) >= currDate.setHours(0, 0, 0)) {	
                // alert("The input date is today's date");
				//return false;
            }
            else {
				//alert(inpDate.setHours(0, 0, 0) + ' == ' + currDate.setHours(0, 0, 0) );
                //alert("The input date is" +" different from today's date");
				$dataa = currDate.setHours(0, 0, 0) - inpDate.setHours(0, 0, 0);
				if($dataa > 1000){
					$('.editItemSave').hide();
					
					$('#validateDate_edit').html("Selecting a past date is not allowed");
					//$('#validateDate').html("The input date is different from today's date "+inpDate.setHours(0, 0, 0) + ' == ' + currDate.setHours(0, 0, 0));
					return false;
				}
				
            }
	}
	
	
    function clearfld(){
		
// 		$('#itemDescription').html('');
// 		$('#itemQuantity').html('');
// 		$('#itemUnits').html('');
// 		$('#itemRate').html('');
// 		$('#itemGST').html('');
// 		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}
	
</script>

</body>
</html>