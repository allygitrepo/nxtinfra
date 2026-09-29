<?php
include("../header.php");
$modulePath = "purchase_requisition/"; 

$_SESSION['reset'] = '1';

$user   = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

?>
<div class="content-wrapper">
<?php
if($_GET['sub']=='delete'){
	$pr_id	= $_GET['pr_id'];
	
	$sql="UPDATE sma_purchase_req SET del = 'Y' where id = '$pr_id' ";
	//$value1=$sql;
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

/* 	$sql="delete from sma_purchase_req_items where purchase_req_id = '$pr_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$sql="delete FROM `file_uploads` where module = 'PR' and reference_id = '$pr_id' ";
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
 */
 
	$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				VALUES( 'PR', '$pr_id', '$userid', now(), 'Deleted', '', now() ) ";
	$query=mysqli_query($con, $sql);
	$error= mysqli_error($con);
	if(!empty($error)){echo $error; exit();}
				
				
	$baseurl1 = $baseurl . $modulePath;
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

	if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){
		$id					= $_POST['pr_id'];
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

		$sql = "update sma_purchase_req set approval_status	= '$approval_status', status =  'Submitted', changed_by = '$user', changed_date = now() where id = '$id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";

	}

	
if($_POST['editSave']){

		$rid     				= $_POST['rid'];
		$purchase_req_id		= $_POST['purchase_req_id'];
		$description			= str_replace("'","",$_POST["description"]);
		$units					= $_POST['units'];
		$quantity				= $_POST['quantity'];
		$product_id				= $_POST['product_id'];
		
		$sql = "select * from sma_product where id = '$product_id'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$product_name 	= $r1['name'];
		$units 	        = $r1['uom'];
			
		$sql = "SELECT * from sma_purchase_req_items where purchase_req_id = '$purchase_req_id' and product_id = '$product_id' ";
		$q2  = mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		$r2  = mysqli_fetch_object($q2);
		
		if($rowaffected==1){
			$sql = "UPDATE `sma_purchase_req_items` SET description = '$description',
					product_id 		= '$product_id',
					unit 			= '$units',
					quantity 		= '$quantity'	
					where purchase_req_id = '$purchase_req_id' and id = '$rid' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
		else {
			echo "Duplicate product not allowed...";
		}
		
				
			$sql = "select * from sma_purchase_req where id = '$purchase_req_id'";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$pr_number 	= $r1['pr_number'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $pr_number.','. $product_name. ','. $description. ',' . $quantity;
		    $affect 		= 'Product Modified';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_req_id.'&active=active&888';
//echo $baseurl1;

//exit();
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
}
	
	if($_GET['sub']=='Save'){
		$id				= $_POST['id']; 
		$pr_id			= $_POST['id']; 
		$prdate 			= date('Y-m-d', strtotime($_POST['prdate']));
		$delivery_require_by    	= date('Y-m-d', strtotime($_POST['delivery_require_by']));
		
		$company_id					= $_POST["company_id"];
		$supplier_id   				= $_POST["supplier_id"];
		$trans_type					= '';
		$department 				= $_POST["department_id"];

		$delivery_address   		= $_POST["delivery_address"];
		$background_section     	= $_POST["background_section"];
		$subject					= $_POST['subject'];
		$reason                     = $_POST['reason_remark'];
		$scope_of_work     			= $_POST["scope_of_work"];
		
		$approver_1			= $_POST['approver_1'];
		$approver_2			= $_POST['approver_2'];
		$approver_3			= $_POST['approver_3'];
		$approver_4			= $_POST['approver_4'];
		
		$supplier_id  = '';
		$status				= $_POST['status'];
			
		$close_mrn			= $_POST['close_mrn'];
		
		$sql = "UPDATE sma_purchase_req SET date = '$prdate',
							delivery_require_by = '$delivery_require_by',
							company_id 			= '$company_id',
							supplier_id 		= '$supplier_id',
							department_id 		= '$department',
							delivery_address	= '$delivery_address',
							background_section 	= '$background_section',
							scope_of_work 		= '$scope_of_work',
							subject		 		= '$subject',
							reason              = '$reason'
				where id='$pr_id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if($status == 'Completed'){
				$sql   = "UPDATE sma_purchase_req SET close_mrn = '$close_mrn' where id='$pr_id'";
				$query = mysqli_query($con, $sql);
				$error = mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_purchase_req set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_1_status	= '$approver_1_status',
						approval_status		= '$status',
						status				= '$status'
					where id='$pr_id'";	
				$query=mysqli_query($con, $sql);	
//echo $sql. "<BR>";				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'PR', '$pr_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";				
				$modulePath = "purchase_requisition/"; 
				
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
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pr_id;
		
				//$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pr_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$pr_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Purchase Requisition Note Number : '.$pr_id . ' ' . 'Dated : ' . date("d-m-Y");

				include "pr_mail.php";
				
			}
			
//exit('Exit Here.... ');
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/pr/" . $pr_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('PR', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $pr_id . ", now() )";

					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/pr/" . $pr_id . "/" . $filename);
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
			
			$pr_number 		= $_POST['pr_number'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $pr_number. ','. $department. ','. $party_name;
		    $affect 		= 'Modified';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$baseurl.=$modulePath."?id=$pr_id";
			echo "<script>window.location.href='$baseurl';</script>";

	}

		if(isset($_GET['id'])) {
			$id 	= $_GET['id'];
			$pr_id = $_GET['id'];
			$sql = "SELECT * FROM sma_purchase_req WHERE id=" . $_GET['id'];
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			echo mysqli_error($con);    
			$dated = date('d-m-Y', strtotime($row['date']));
		}
		else {
			header("Location: " . $baseurl . $modulePath);
			die();
		}
	
		$purchase_req_id = $row['id'];
		$pr_id 			= $purchase_req_id;
		
		$draft_by 		= $row['draft_by'];
		
		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
										
		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];
		
		
	$status = $row['status'];
	$readonly = '';
	if ($status == 'Submitted' || $status == 'Completed' || $status == 'Approved'){
		$readonly = 'READONLY';
	}
	
	$company_id = $row['company_id'];
	
	$approval_status = $row['approval_status'];
	if($purchase_req_id==743){
	    $readonly = '';
	}
	//echo $purchase_req_id. "<BR>";
?>

<!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Purchase Requisition Note
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Purchase Requisition Note</a></li>
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
		            $del = $row['del'];
		            if($del=='Y'){
		                $status = 'Deleted';
		            }
		            
		            if($approval_status == 'Rejected'){
		                $status = $approval_status;
		            }
		      ?>
						<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'index.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
						 &nbsp;&nbsp;
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b>&nbsp;&nbsp;&nbsp;</h4> </span>


						<input type="hidden" name="id" value="<?php echo $id;?>">
						<input type="hidden" id="purchaseId_e" value="<?php echo $id;?>">
						
					  <input type="hidden" name="pr_id" value="<?php echo $pr_id;?>">
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
					
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Purchase Requisition Note </a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab" >Products</a></li>
						<li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>
						<li><a href="purchase_req_prn.php?sub=pdf&id=<?php echo $pr_id;?>&comp_id=<?php echo $row['company_id'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						
                    </ul>
					
					<div class="tab-content">
					    <div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group ">
						
						<?php  ?>
						
								<label for="prDate" class="col-sm-1 control-label">Dated</label>
                                <div class="col-xs-2">
                                    <div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" readonly id="prdate" name="prdate" placeholder="dd-mm-yyyy"
                                               value="<?= date('d-m-Y', strtotime($row['date'])); ?>">
                                    </div>
                                </div>
                            
                                <label for="prDate" class="col-sm-1 control-label">Serial&nbsp;Number</label>
                                <div class="col-xs-3">
									<input type="text" class="form-control" id="pr_number" name="pr_number" readonly  style="text-align:left;" value="<?= $row['pr_number'] ?>">
									
                                    <input type="hidden" class="form-control" id="srno" name="srno" readonly  value="<?php echo $pr_id ?>">
                                </div>
						<?php
							$company_id = $row['company_id'];
						$sqlx = "";	
						if($status != 'Draft'){
							$sqlx = " and comp_id = '$company_id' ";
						}	
						?>	
                                    <label for="company" class="col-sm-1 control-label">Company*</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="company_id" name="company_id" <?= $readonly;?> >
										
										<?php
                                            $sql="SELECT * FROM company where 1 $sqlx ORDER BY comp_name ASC";
                                            $res = mysqli_query($con, $sql);
                                            echo mysqli_error($con);
                                            while($r2 = mysqli_fetch_array($res)){
                                        ?>
                                            <option value="<?php echo $r2['comp_id']?>" <?php echo ($r2['comp_id'] == $row['company_id']) ? "selected" : ""; ?>><?php echo $r2['comp_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
								</div>
								
							<div class="form-group ">
						<?php
							$department_id = $row['department_id'];
						$sqld = "";	
						if($status != 'Draft'){
							$sqld = " and id = '$department_id' ";
						}	
						?>		
								<label for="department" class="col-sm-2 control-label">Department*</label>
                                <div class="col-sm-4">
                                    <select class="form-control " id="department" name="department_id" required <?= $readonly;?> >
									
                                    <?php
                                    	$sql="SELECT id, name FROM sma_department where 1 $sqld ORDER BY name ASC";
                                        $res = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($r2 = mysqli_fetch_array($res)){
                                    ?>
                                        <option value="<?php echo $r2['id']?>"  <?php echo ($r2['id'] == $row['department_id']) ? "selected" : ""; ?> ><?php echo $r2['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                    <?php            
                            $sql 	= " SELECT * FROM `sma_approval_memo` where 1 and del != 'Y' and against_indent_no = '$id'  ";
        					$q2 	= mysqli_query($con, $sql);
        					$r2 	= mysqli_fetch_array($q2);
        					$against_noa_no = $r2['id'];
        					if($against_noa_no>0){
        					    $noa_link = "$baseurl/approval/approval_notes_prn.php?sub=pdf&id=$against_noa_no&comp_id=$company_id" ;
        					}
        					
        					$sql 	= " SELECT * FROM `sma_purchase_order` where 1 and del != 'Y' and  approval_memo_ref = '$against_noa_no'  ";
        					$q2 	= mysqli_query($con, $sql);
        					$r2 	= mysqli_fetch_array($q2);
        					$against_po_no = $r2['id'];
        					if($against_po_no>0){
        					    $po_link = "$baseurl/purchase_order/edit.php?sub=edit&id=$against_po_no";
        					}
        					
					            if($against_noa_no>0){
					?>
								<div class="col-md-2">
									<label class="control-label" style="font-size:14px;" >Document : </label>
									<a href="<?php echo $noa_link; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >NOA </span></a>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if($against_po_no>0){ ?>
									<a href="<?php echo $po_link; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >PO </span></a>&nbsp;&nbsp;&nbsp;&nbsp;
					        <?php } ?>				
								</div>
					<?php } ?>	
					
					
                            </div>
						
						<?php
						
						    $delivery_address = $row['delivery_address'];
						    
						    $sql = " SELECT * FROM `sma_location` where loc_comp_id = '$company_id' ";
						    $res = mysqli_query($con, $sql);
                            echo mysqli_error($con);
                            $r2 = mysqli_fetch_array($res);
						    $delivery_address = $r2['loc_addr1'];
						    
						?>
						
							<div class="form-group ">
								<label for="delivery_Address" class="col-sm-2 control-label">Delivery Address</label>
                                <div class="col-xs-6">
                                    <textarea class="form-control" id="delivery_address" readonly name="delivery_address"  <?= $readonly;?> ><?= $delivery_address; ?></textarea>
                                    
                                </div>
					<?php $delivery_require_by = date('d-m-Y', strtotime($row['delivery_require_by']));
						if($delivery_require_by=='01-01-1970'){
							$delivery_require_by ='';
						}	
					?>
								<label for="delivery_require_by" class="col-sm-2 control-label">Required by Date</label>
                                <div class="col-xs-2">
                                    <div class="input-group date" data-provide="datepicker<?= $readonly;?>" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text"  class="form-control" id="delivery_require_by" name="delivery_require_by" placeholder="dd-mm-yyyy" value="<?php echo $delivery_require_by; ?>" <?= $readonly;?> >
                                    </div>
                                </div>
								
							</div>
                            
							<div class="form-group ">
						
								
								
						<?php
								
							$sql 	= " SELECT * FROM `sma_approval_memo` where 1 and del != 'Y' and against_indent_no = '$purchase_req_id'  ";
				    
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$against_noa_no = $r2['id'];
					$noa_status     = $r2['status'];
					$ap_number      = $r2['ap_number'];
					$noa_link = '';
					if($against_noa_no>0){
					    $noa_link = '<a href="'.$baseurl . "approval/edit.php?sub=edit&id=".$against_noa_no.'"  target="_blank" ><B>NOA</b>:'.substr($ap_number,0,10).'<BR>'.substr($ap_number,10,30).' '.$noa_status.' </a>';
					
    					$sql 	= " SELECT * FROM `sma_purchase_order` where 1 and del != 'Y' and  approval_memo_ref = '$against_noa_no'  ";
 
                        $q2 	= mysqli_query($con, $sql);
    					$r2 	= mysqli_fetch_array($q2);
    					$po_id = $r2['id'];
    				
							if($po_id>0){	
								$baseurl_mrn = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
						?>
								<div class="col-md-1">
									<label class="control-label" style="font-size:14px;" >Document:</label>
								</div>
								<div class="col-md-2">		
									<a href="<?php echo $baseurl_mrn; ?>" target ="_blank"><span class="label label-danger" style="font-size:14px;" >Purchase Order</span></a>
								</div>
					<?php } 
						
						}
					?>	
								
						<?php //if($status=='Completed'){ ?>		
									<!--<div class="col-sm-2" >		-->
									<!--	<span style="font-size:18px;color:white;" class=" btn-danger" >Mark to Close </span>&nbsp;&nbsp;-->
									<!--	<span > &nbsp;&nbsp;</span>-->
									<!--	<input type ="checkbox" id="close_mrn" name="close_mrn" value='Y' >-->
									<!--</div>	-->
						<?php //} ?>
						
							</div>
							
							<div class="form-group">
								<label for="company_id" class="control-label col-sm-2">Subject *</label>
								<div class="col-sm-10">
                                     <input type="text" class="form-control" id="subject" name="subject"  value="<?= $row['subject']; ?>" <?= $readonly;?> >
                                </div>
                            </div>
							
							
							
							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Remarks</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" rows="2" name="reason_remark" ><?= $row['reason']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Background Section</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="background_section" name="background_section"
                                                <?= $readonly;?>  placeholder="Enter text ..."><?= $row['background_section']; ?></textarea>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Scope of Work Section</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="scope_of_work" name="scope_of_work"
                                                <?= $readonly;?>  placeholder="Enter text ..."><?= $row['scope_of_work']; ?></textarea>
                                    </div>
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
					
					echo $purchase_req_id. "<BR>";
				?>
				
				<div class="tab-pane <?php echo $active;?> " id="tab_2">

							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Product Details</h4>
					<?php  if (empty($readonly) || $purchase_req_id==876){ ?>			
                                        <span class="pull-right">
                                            <a href="#modalAddItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddItem">Add 
                                            </a>
                                        </span>
					<?php } ?>					
                                    </div>
					<?php	
										$sql = "SELECT * from sma_purchase_req_items where quantity > 0 and purchase_req_id = '$purchase_req_id' ";
										$res = mysqli_query($con, $sql);
										$itemcnt = mysqli_affected_rows($con);
										echo mysqli_error($con);
										$value="";
										while($r3 = mysqli_fetch_array($res)){
											$product_id		= $r3['product_id'];
											$sql = "select * from sma_product where id = '$product_id'";
											$r2 = mysqli_query($con, $sql);
											$r1 = mysqli_fetch_array($r2);
											$product_name 	= $r1['name'];
											$category 		= $r1['category'];
										}
										if($category=='M'){
											$txtv =  'Qty.';
										}
										else {
											$txtv =  'Value';
										}
					?>								
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                
												<th>Specification</th>
												<th>Budget Head</th>
												<th></th>
												<!--<th style="text-align:right;">Existing Stock.</th>-->
                                                <th style="text-align:right;">PR.QTY/Value </th>
                                                <th>Unit of <br> Measurement</th>
							                    <th>Ref.NOA No.</th>
							                    <th>NOA Qty.</th>
												
												<th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
												
										 		$sql = "SELECT * from sma_purchase_req_items where quantity > 0 and purchase_req_id = '$purchase_req_id' ";
												$res = mysqli_query($con, $sql);
												$itemcnt = mysqli_affected_rows($con);
												echo mysqli_error($con);
												$value="";
												while($r3 = mysqli_fetch_array($res)){
													$product_id		= $r3['product_id'];
													$po_no			= $r3['po_no'];
													$unit           = $r3['unit'];
													
													$quantity 		= $r3['quantity'];
													$po_quantity 	= $r3['po_quantity'];
													$bal_qty		= $quantity - $po_quantity;
													
													$sql = "select * from sma_product where id = '$product_id'";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$product_name 	= $r1['name'];
													$category 		= $r1['category'];
													$unit 		    = $r1['uom'];
													$budget_head_id 	= $r1['budget_head'];
													
													$sql = "select * from sma_budget_subgroup where id = '$budget_head_id' ";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$budget_head 	= $r1['budget_head'];
													
												// 	if($category=='S'){
												// 	    $unit = 'INR';
												// 	}
													
													$sql = "select * from sma_purchase_order where id = '$po_no'";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$po_dated = date('Y-m-d', strtotime($r1['dated']));
													if($po_dated=='1970-01-01'){
														$po_dated ='';
													}
														
												// 	$sql = "SELECT product_name, project, opening_stock, receipts, issue  FROM `sma_product_open_stock` where product_name  = '$product_id' and project = '$company_id'";
												// 	$r2 = mysqli_query($con, $sql);
												// 	$r1 = mysqli_fetch_array($r2);
												// 	$opening_stock 	= $r1['opening_stock'];
												// 	$receipts 		= $r1['receipts'];
												// 	$issue 			= $r1['issue'];
												// 	$close_Stock	= ($opening_stock + $receipts) - $issue;
													
													$rid = $r3['id'];
													$ap_item_no = $r3['ap_item_no'];
													
													$sql = "select * from sma_approval_items where id = '$ap_item_no'";
													$r2 = mysqli_query($con, $sql);
													$r1 = mysqli_fetch_array($r2);
													$ap_qty = round($r1['quantity'],2);
													
													$quantity = round($r3['quantity'],2);
													
												?>
													<tr>
														<td width='15%'><?= $product_name?></td>
														<td width='15%'><?= $r3['description']?></td>	
														
														<td width='8%'><?= $budget_head;?></td>
														<td width='8%'><?= $category;?></td>
														
													<!--	<td width='8%' style="text-align:right;"><?= $r3['existing_qty'];?></td>	-->
														<td width='8%' style="text-align:right;"><?= $quantity;?></td>	
														<td width='8%'><?= $unit;?></td>
														<td width='8%' style="text-align:right;"><?= $ap_item_no;?></td>
														<td width='8%' style="text-align:right;"><?= $ap_qty;?></td>
														
														<td width='6%'>
										<?php  if (empty($readonly)){ ?>				
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->
													<?php include "edit_func.php"; ?>
<!-- Modal Edit Item-->
										<?php  }
											if (empty($readonly)){ ?>
														<a href='#modalDeleteItem' id='delete-<?php echo $_GET['id'];?><?php echo $rid;?>' data-toggle='modal' data-id='<?php echo $_GET['id'];?><?php echo $rid;?>' data-target='#modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>'><i class='fa fa-trash-alt'></i></a>
														
<!-- Modal Delete Item-->
													<?php include "del_func.php"?>							
										<?php } ?>			
<!-- Modal Delete Item-->
														</td>
														
													</tr>
											<?php
												}
											?>		

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
							
               				<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								</div>
							</div>
						
					
				</div>
				
                        <div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PR' AND reference_id = " . $pr_id;
                              $docResults = mysqli_query($con, $sql);
							  
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%">Document Type</th>
                                          <th width="30%">Document Name</th>
                                          <th width="30%">Description</th>
										  <th width="10%"></th>
                                          
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
											
											  ?>
                                          <tr>
                                              <td width="20%"><?php echo $document; ?></td>
                                              <td width="30%"><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td width="30%"><?php echo $docRow['doc_desc'] ?></td>
								<?php if(empty( $readonly) || $user=='Admin' ){ ?>
											   <td width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
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
						        	
						        	<span id="predit"></span>
						        	
							<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&pr_id='.$pr_id ; ?>
						
						<?php		
						//echo $po_id. "<>";
							//|| $user =='Admin' $status == 'Draft' ||
							//if ( $po_id==0 || $status == 'Draft' ){
							
							$del = $row['del'];
							
							if ( $status == 'Draft' ){
						?>
							<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>
						<?php	} ?>
							<span>&nbsp;&nbsp;</span>
							
						<?php 
							if ( ($del =='Y' ) || ( ( $approval_status == 'Rejected' || $status == 'Submitted' ) && $user =='Admin123' ) || ($po_id==0 && $user =='Admin123' && $status =='Completed' ) ){
						?>	
							<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
						<?php } ?>
						
						<?php		
							if ( $status == 'Submitted' && ($user =='Admin' ) ){
						?>	
							<a href="#MakeDuplicateMRN" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#MakeDuplicateMRN">Copy PR</a>	
						<?php } ?>
						
						</div>
				<?php		
				//	if ($status != 'Completed'){
				?>	
 			
						<div class="col-sm-6 text-right">
						<?php		
						$role			= $_SESSION['role'];
						$userid   	= $_SESSION['usrid'];
									
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag='';
									if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' || 
										$userid == $approver_4 && $approver_4_status=='Submitted'
										 ){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) && empty($approver_4_status) 
										){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status)  ){
										$approver_flag='Y';
									}
									
									if($approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Submitted'
										&& empty($approver_4_status) ){
										$approver_flag='Y';
									}
									if($approver_1_status=='Approved' && $approver_2_status=='Approved' && $approver_3_status=='Approved'
										&& $approver_4_status=='Submitted' ){
										$approver_flag='Y';
									}
									
								}
								
						//echo $status . ' >><< ' . $approver_flag. "<BR>";
						
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
						?>	
						
								<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Approve </a>
								<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
						<?php	}
							}
						
									$_SESSION['pr_id'] 	= $pr_id;
									$_SESSION['status']  = $row['status'];
						//	echo $status . ">><<<";
							if ($status == 'Draft' || $status == 'Completed' || $status == 'Submitted' ){	
							?>	
							
								<input type="submit" class="btn btn-primary" value="Save" name="Save">
								<span>&nbsp;&nbsp;</span>
									<!--<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#approvalAuthority">Send </a>-->
							<?php if($itemcnt>0 && $status == 'Draft'){ ?>	
									<span class='hidesend' >	
										<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									</span>	
							<?php } ?>
							
					<?php	} ?>
							
								<span>&nbsp;&nbsp;</span>
								
							<!--		<span class="pull-right"><a href="<?php echo $baseurl . $modulePath .'index.php?sub=list' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>-->
								
						
						</div>
						
						<?php	//} ?>
						
					</div>	
					
				
						  
						  <?php  
//echo $status. ' ' .	$approver_1. "<BR>";					  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								
//echo $sql. "<BR>";									
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label" style="text-align:left;" >Approver 1</label><BR>
									<label class="control-labela"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
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
									<label class="control-label">Approver 2</label><BR>
									<label class="control-labela "><?= $approver_2_name . " <BR> " . $approver_2_role;?>
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
									<label class="control-label">Approver 3</label><BR>
									<label class="control-labela "><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><BR>
									<label class="control-labela "><?= $approver_4_name . " <BR> " . $approver_4_role;?>
									</label>
								</div>
					<?php	
							}
							
					?>		
							</div>
					<?php		
						}
								
						if( $status == 'Draft' ){
					?>
						<span id="getapprover">
								<div class="box-footer">
								
								<BR>
								
							</div>
						
						</span>
				<?php } ?>		
				
						</div>
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $pr_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PR' order by id ";
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PR' order by id desc";
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
						

<!--Comment Section Start-->				
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
					<p><?= $label_line; ?></p>
				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$pr_id' and doc_type = 'PR' order by id desc ";
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
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $pr_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $pr_id;?>,'PR','C',<?= $page;?>)" >Submit</button>		
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
											$pr_id 	= $_SESSION['pr_id'];
											$status = $_SESSION['status'];
											
											$sql="SELECT * FROM sma_purchase_req where id = '$pr_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
												
										?>
										
										<input type="hidden" name="pr_id" id="pr_idE" value="<?php echo $pr_id; ?>" >
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
											$pr_id 	= $_SESSION['pr_id'];
											$status = $_SESSION['status'];
											
											$sql="SELECT * FROM sma_user where userid = '$draft_by' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$userid = $rw['id'];
										?>
										
										<input type="hidden" name="pr_id" id="pr_idE" value="<?php echo $pr_id; ?>" >
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
										<?php $pr_id 			= $purchase_req_id; ?>
										<input type="hidden" name="pr_id" id="pr_idD" value="<?php echo $pr_id; ?>" >
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
	  
					

<!--MRN Copy Popup Start -->
<div class="modal fade" id="MakeDuplicateMRN" role="dialog" aria-labelledby="MakeDuplicateMRN">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="MakeDuplicateMRN">Copy PR To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$pr_id 	= $_SESSION['pr_id'];
											$status = $_SESSION['status'];
								//	echo	 $sql="SELECT * FROM company where 1 and comp_id in ($comid) ORDER BY comp_name ASC";	
										?>
										
										<input type="hidden" name="pr_id" id="pr_idC" value="<?php echo $pr_id; ?>" >
										
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

<!--MRN Copy Popup End -->
					
					
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add to Purchase Requisition Note </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">
							
							<div class="form-group">
								
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
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
								
                                <div class="col-sm-8">
									<label for="itemName" class="control-label">Product Name</label>
									<span id="getmaterial1" >
										<select class="form-control" name="itemName" id="itemName" required >
											<option value="">Select</option>	
										</select>
									</span>
									
									<span id="getdupprd" style="color:red;"></span>
									
								</div>	
                                
                            </div>
							
							<div class="form-group col-md-12">
                                <div class="col-sm-12">
									<label for="itemDescription" class="control-label">Description</label>
                                    <textarea rows = "3" class="form-control" id="itemDescription" placeholder="Item Description..."></textarea>
                                </div>
                            </div>
						
							 <div class="form-group col-md-12">
                                <div class="col-sm-4">
								<span  id="qty_value" >
									<label for="itemQuantity" class="control-label" id="qty_value" >Quantity</label>
								</span>	
                                    <input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" >
                                </div>
								
								<!--<div class="col-sm-8" style="color:red;" > If it's a service order, please enter the total amount (Excluding all taxes).-->
								<!--In case of a purchase order (Product), please enter the total quantity.-->
								<!--</div>-->

								<span class="getberror" style="color:red;" ></span>
                            </div>
						
							<div class="form-group col-md-12">
                            
								<span id="getunit2">
								<div class="col-sm-4 col-md-4">
									<label for="itemUnits" class="control-label">Unit of Measurement</label>
								   <input type="text" class="form-control " readonly id="itemUnits"  style="text-align:right;" >
								</div>
								</span>   
								
							</div>
	
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary editItemSave" id="addItem">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Item-->

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

	function delete_prItem(purchase_req_id, id){
		var sub = 'sub7';
        var purchase_req_id = purchase_req_id;
		var id	 = id;
//alert(purchase_req_id + ' ' + id);
		$('#modalDeleteItem'+purchase_req_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ purchase_req_id:purchase_req_id,id:id,sub7:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		
		window.location.href='edit.php?sub=edit&id='+purchase_req_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}

	
    var itemArray = []; // stores all item details table values in memory

    $(document).ready(function () {
        $('.datepicker').datepicker();
        // $('.datepicker').datepicker({
        //     "format": 'd/M/Y',
        //     "autoclose": true
        // });
        $('.select2').select2();
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
            $("#err").html("Please add items to the Purchase Requisition Note");
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
        var qty = $('#itemQuantity').val();
        var rate = $('#itemRate').val();
//		var gst = $('#itemGST').val();
        var amt = qty * rate;
//		var amt = amt + (amt * gst /100);
        amt = parseFloat(amt);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount').val(amt);
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

    $("#btnDeleteItemYes").on("click", function(e){
        var i;
        for (i = 0; i < itemArray.length; i++) {
            //var obj = itemArray[i];
            if (itemArray[i].tempId == $("#itemTempId").val()) {
                itemArray.splice(i, 1);
                break;
            }
        }
        buildItemsTable();
        $('#modalDeleteItem').modal('hide');
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

    $('#modalAddItem').on('show.bs.modal', function(e) {
        var mode = $(e.relatedTarget).data('mode');
        $(e.currentTarget).find('input[id="mode"]').val(mode);
        if (mode === 'add') {
            // clear existing values
            $("#itemDescription").val("");
            $("#itemQuantity").val("");
            $("#itemUnits").val("");
            $("#itemRate").val("");
			$("#itemGST").val("");
            $("#itemAmount").val("");
			$("#deliveryDate").val("");
        }
        else {
            var tempId = $(e.relatedTarget).data('id');
            $(e.currentTarget).find('input[id="tempId"]').val(tempId);
            var i;
            for (i = 0; i < itemArray.length; i++) {
                var obj = itemArray[i];
                if (obj.tempId == tempId) {
                    $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                    //$(e.currentTarget).find('select[id="itemName"]').val(obj.id);
                    setSelectedValue($(e.currentTarget).find('select[id="itemName"]')[0], obj.name);
					setSelectedValue($(e.currentTarget).find('select[id="categoryId"]')[0], obj.name);
					$(e.currentTarget).find('input[id="itemDescription"]').val(obj.description);
                    $(e.currentTarget).find('input[id="itemQuantity"]').val(obj.quantity);
                    $(e.currentTarget).find('input[id="itemUnits"]').val(obj.units);
                    $(e.currentTarget).find('input[id="itemRate"]').val(obj.rate);
					$(e.currentTarget).find('input[id="itemGST"]').val(obj.gst);
                    $(e.currentTarget).find('input[id="itemAmount"]').val(obj.amount);
					$(e.currentTarget).find('input[id="deliveryDate"]').val(obj.deliverydate);
                    break;
                }
            }
        }
    });

	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var pr_id		 	=  $("#pr_idE").val();
		var approver		=  $("#approverC").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
//alert(approver + ' ' + status + ' ' + mode);		
		
		$('#predit').html('Wait...');
		
		 $('#approvalAuthority').modal('hide');
		var strURL = "pr_func.php";
		$.post(strURL,{ pr_id:pr_id,
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
		var pr_id		 	=  $("#pr_idE").val();
        var approver 		=  $("#approverE").val();
		var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksR").val();

		var statusap		= 'Reject';
		 $('#rejectAuthority').modal('hide');
		var strURL = "pr_func.php";
		$.post(strURL,{ pr_id:pr_id,
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

    $("#addItem").on("click", function(e){
        var sub = 'sub1';
	//	var mode = $("#mode").val();
	
		var purchase_req_id =  $("#purchaseId").val();		
        var product_id 				=  $("#itemName option:selected").val();
		var name 			=  $("#itemName option:selected").html();

		var description =  $("#itemDescription").val();
        var quantity 	=  parseFloat($("#itemQuantity").val());
        var units 		=  $("#itemUnits").val();

		$('.editItemSave').show();
		$('.getberror').html('');
		
		if(product_id=='' || product_id==0 || isNaN(product_id) ){
			var err = 'Product should select...';
			$('.getberror').html(err);
			$('.editItemSave'+srno).hide();
			return false;	
		}
		if(quantity==0 || isNaN(quantity) ){
			var err = 'Qty should not be zero...';
			$('.getberror').html(err);
			$('.editItemSave'+srno).hide();
			return false;	
		}
		
//alert(sub);
        $('#modalAddItem').modal('hide');
		var strURL = "pr_func.php";
		$.post(strURL,{ purchase_req_id:purchase_req_id,
							description:description,
							product_id:product_id,
							quantity:quantity,
							units:units,
							sub1:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
    });

	
    $("#editItem").on("click", function(e){
//    function(editItem){    
		var sub = 'sub3';

		var rid 		=  $("#rid_e").val();
		var purchase_req_id =  $("#purchaseId_e").val();		
        var id =            $("#itemName_e option:selected").val();
        var name =          $("#itemName_e option:selected").html();
		var catid =         $("#categoryId_e option:selected").val();
        var catname =       $("#categoryId_e option:selected").html();
        var description =   $("#itemDescription_e").val();
        var quantity =      $("#itemQuantity_e").val();
        var units =         $("#itemUnits_e").val();
        var rate =          $("#itemRate_e").val();
		var gst  =          $("#itemGST_e").val();
        var amount =        $("#itemAmount_e").val();
		var deliverydate =  $("#deliveryDate_e").val();
        $('#modalEditItem'+rid).modal('hide');
		var strURL = "po_func.php";
		$.post(strURL,{ rid:rid,id:id,purchase_req_id:purchase_req_id,
							name:name,
							catname:catname,
							catid:catid,
							description:description,
							quantity:quantity,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							deliverydate:deliverydate,
							sub3:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });


	$("#CopyMRN").on("click", function(e){
        var sub = 'sub1';
		var company_id		 	=  $("#company_idC").val();
		
//		alert(sub + ' ' + mode);		 
		var pr_id		 	=  $("#pr_idC").val();
		var status 			=  $("#statusC").val();
		var remarks			=  $("#remarksC").val();
//alert(pr_id + ' ' + status + ' ' + company_id + ' ' + remarks);		

		$('#MakeDuplicateMRN').modal('hide');
		$('#predit').html('Wait...');
		
		var strURL = "copy_mrn.php";
		$.post(strURL,{ pr_id:pr_id,
						company_id:company_id,
						status:status,
						remarks:remarks,
						sub1:sub},
						function(result){
			//alert(result);				
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
	
	
	function getbudget(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getbudget').html(result);
		});

	}

	function getbudgethead(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
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

	function getdupprd(id){	
        var sub    = 'sub4A';
		var purchase_req_id =  $("#purchaseId_e").val();
		
		
		$('#addItem').show();
//alert(sub + ' ' + purchase_req_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,purchase_req_id:purchase_req_id,sub4A:sub},function(result){
		        var splitString = result.split("###");
				var msg 	=  splitString['0'];
				var rowcnt 	= splitString['1'];
				if(rowcnt>0){
				    $('#addItem').hide();
				}
		      $('#getdupprd').html(msg);
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
            $("#err").html("Please add items to the Purchase Requisition Note");
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
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
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
		
		var purchase_req_id =  $("#purchaseId_e").val();
		//var department		=  $("#department").val();
		var company_id    	= document.getElementById("company_id").value;
		
		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + ' ' + ' ' + company_id );	
		//checker_value:checker_value,
		var strURL = "app_func.php";
		$.post(strURL,{purchase_req_id:purchase_req_id,company_id:company_id,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}
	
	function getmaterial1(id){
		
        var sub    = 'sub3A';
		var company_id 	=  $("#company_id").val();
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub3A:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}

	
	function getcomment(comment,pr_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,pr_id:pr_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
	
	$("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var pr_id		 	=  $("#pr_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + pr_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ pr_id:pr_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

	function getworkflowtype(id){
		
        var sub    = 'sub27';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub27:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
	
</script>

</body>
</html>
