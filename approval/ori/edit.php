<?php
include("../header.php");
$modulePath = "approval/";
$_SESSION['reset'] = '1';

$userid   	= $_SESSION['usrid'];

?>
<?php

//Product Edit
	if(isset($_POST['editItem'])){
		
		$rid     				= $_POST['rid'];
		$approval_hdr_id 		= $_POST['approval_hdr_id'];
		$itemgst				= $_POST['itemgst'];
		$itemrate				= $_POST['itemrate'];
		$itemquantity			= $_POST['itemquantity'];
		$budget_id	    		= $_POST['budget_id'];
		$product_id				= $_POST['product_id'];
		
		$itemgst_p				= $_POST['itemgst_p'];
		$itemrate_p				= $_POST['itemrate_p'];
		$itemquantity_p			= $_POST['itemquantity_p'];
		$product_id_p			= $_POST['product_id_p'];
		//$budget_id_p			= $_POST['budget_id_p'];
		$itemdescription		= $_POST['itemdescription'];

		$sql = " SELECT sum(`values`) as checker_value 
				FROM sma_approval_details 
					WHERE approval_hdr_id   = '$approval_hdr_id' 
						AND vendor_selected = 'Y' "; //
		$qry = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($qry);
		$checker_value 		= $r2['checker_value'];

		$sql = " SELECT sum(quantity * unit_rate) as product_value 
					FROM sma_approval_items 
						WHERE approval_hdr_id   = '$approval_hdr_id' "; //
		$qry = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($qry);
		$product_value 		= $r2['product_value'];

//echo $product_value . ' > ' . $checker_value;
//exit();
		if($product_value > $checker_value){
			$baseurl.=$modulePath.'edit.php?id='.$approval_hdr_id.'&active2=active&emsg=Y ';	
			echo "<script>alert('Product total should not be greater then Approval Value !!!');window.location.href='$baseurl';</script>";
			exit();
		}	
		
		$sql = "SELECT * FROM sma_approval_memo WHERE id  = '$approval_hdr_id' "; //
		$qry = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($qry);
		$company_id 		= $r2['company'];
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		if($budget_control_gst =='Y'){	
			$amount = round(($itemquantity * $itemrate ) + ((($itemquantity * $itemrate ) * $itemgst) /100),0) ;
		}
		else if($budget_control_gst =='N'){	
			$amount = round(($itemquantity * $itemrate ),0) ;
		}
		
		$sql  = "SELECT * from sma_approval_items where id = '$rid' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$quantity 		= $r2['quantity'];
		$rate 			= $r2['unit_rate'];
		$gst 			= $r2['gst'];
		$budget_id		= $r2['budget_id'];
		
		if($budget_control_gst =='Y'){
			$amount_p = round(($quantity	* $rate ) + ((($quantity	* $rate ) * $gst) /100),0) ;
		}
		else if($budget_control_gst =='N'){	
			$amount_p = round(($quantity	* $rate ),0);
		}
		
		$sql = " update sma_budget set blocked_budget = blocked_budget + $amount - $amount_p where id = '$budget_id' ";
		mysqli_query($con, $sql);
	
		$sql="SELECT * FROM sma_product where `id` = '$product_id' ";
        $rs = mysqli_query($con, $sql);
		echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$product_name	= $rw['name'];
										
		$sql  = "UPDATE sma_approval_items set 
					product_id		= '$product_id',
					quantity		= '$itemquantity',
					unit_rate		= '$itemrate',
					gst				= '$itemgst',
					product_name 	= '$product_name',
					product_desc	= '$itemdescription'
				where id = '$rid' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		//echo "<script>window.location.reload();</script>";
		echo "<meta http-equiv='refresh' content='0'>";    
		//echo "<script>window.location.href='supplier_invoice.php?sub=edit&id=$approval_hdr_id&active=active&987';</script>";
		$baseurl.=$modulePath.'edit.php?id='.$approval_hdr_id.'&active2=active';//.'&active=active&987'
		echo "<script>window.location.href='$baseurl';</script>";
		
	}

//Expense Edit	
	if(isset($_POST['editExp'])){

		$rid     				= $_POST['rid'];
		$approval_hdr_id 		= $_POST['approval_hdr_id'];
		$reference				= $_POST['reference'];
		$reference_p			= $_POST['reference_p'];
		$amount					= $_POST['amount'];
		$amount_p				= $_POST['amount_p'];
//		$budget_id_p			= $_POST['budget_id_p'];
		$budget_id	    		= $_POST['budget_id'];

		$sql = " select * from sma_approval_memo where id = '$approval_hdr_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		
		$sql = " select * from sma_approval_expenses where approval_hdr_id = '$approval_hdr_id' and id = '$rid' ";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$first_insert = $r1['first_insert'];
			
		$sql = "select * from account_mst where id = '$reference'";
		$r2 = mysqli_query($con, $sql);
		$r1 = mysqli_fetch_array($r2);
		$account_name 		= $r1['account_name'];
		
		$sql = " update sma_budget set blocked_budget= blocked_budget + $amount - $amount_p where id = '$budget_id' ";
		$q3  = mysqli_query($con, $sql);
			
		$sql = "update `sma_approval_expenses` set reference = '$reference', 
						budget_id      = '$budget_id',
						budget_name    = '$budget_name',
						budget_head    = '$budget_head',
						amount		   = '$amount', 
						first_insert   = ''
				where approval_hdr_id = '$approval_hdr_id' and id = '$rid' ";
			$r2 = mysqli_query($con, $sql);

	$amount_tot = 0;
	
	$sql = "select * from sma_approval_memo where id = '$approval_hdr_id'";
	$r2 = mysqli_query($con, $sql);
	$r1 = mysqli_fetch_array($r2);
	$our_po_ref_no = $r1['our_po_ref_no'];
	
	$sql = "select * from sma_approval_expenses where approval_hdr_id = '$approval_hdr_id'";
	$r2 = mysqli_query($con, $sql);
	while($r1 = mysqli_fetch_array($r2)){
		$amount_tot = $amount_tot + $r1['amount'];	
	}

//	echo "<script>window.location.reload();</script>";
	echo "<meta http-equiv='refresh' content='0'>";    
	//echo "<script>window.location.href='supplier_invoice.php?sub=edit&id=$approval_hdr_id&active=active&987';</script>";
	$baseurl.=$modulePath.'edit.php?id='.$approval_hdr_id.'&active3=active';//.'&active=active&987'
	echo "<script>window.location.href='$baseurl';</script>";
}


//Edit Supplier 
	if($_POST['edit']){
		include "saveitem.php";
	}

	if($_GET['sub']=='Save'){
			
			$id					= $_POST['id'];
			$ap_id				= $_POST['id']; 
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$account_year		= $_POST['account_year'];
			$company			= $_POST['company'];
//			$project			= $_POST['project'];
			$department			= $_POST['department'];
			$location			= $_POST['location'];
																	 
			$trans_type			= $_POST['trans_type'];
			$budget_head		= $_POST['budget_head_id'];
			$budget_name		= $_POST['budget_name'];
			$against_indent_no	= $_POST['against_indent_no'];
			$tender_no			= $_POST['tender_no'];
			$budget_available	= $_POST['budget_available'];
			$subject			= $_POST['subject'];
			//$body = mysql_real_escape_string($_POST["myeditor"]);
			$background			= trim(mysqli_real_escape_string($con, stripslashes($_POST['background'])));
			$scope_of_work		= trim(mysqli_real_escape_string($con, stripslashes($_POST['scope_of_work'])));
			$deviations_from_sop= trim(mysqli_real_escape_string($con, stripslashes($_POST['deviations_from_sop'])));
			$important_terms_conditions	= trim(mysqli_real_escape_string($con, stripslashes($_POST['important_terms_conditions'])));
			$description		= $_POST['description'];
			$cost				= $_POST['cost'];
			$additional_costs	= $_POST['additional_costs'];
			$overhead_exp		= $_POST['overhead_exp'];
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];

			$status				= $_POST['status'];
			
  			$sql="update sma_approval_memo set id	= '$id',
						dated				= '$dated',
						account_year		= '$account_year',
						company				= '$company',
						department			= '$department',
						location			= '$location',	   
						trans_type			= '$trans_type',
						budget_head			= '$budget_head',
						against_indent_no	= '$against_indent_no',
						budget_available	= '$budget_available',
						subject				= '$subject',
						tender_no			= '$tender_no',
						background			= '$background',
						scope_of_work		= '$scope_of_work',
						deviations_from_sop	= '$deviations_from_sop',
						important_terms_conditions	= '$important_terms_conditions',
						description			= '$description',
						cost				= '$cost',
						additional_costs	= '$additional_costs',
						overhead_exp		= '$overhead_exp'
				where id='$id'";
		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
		
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_approval_memo set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'AP', '$id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "approval/";
				
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
				
				$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$ap_id;
				
				$msg = 'Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
				
				include "ap_mail.php";	
					
			}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$share_point_link = $_POST["share_point_link"];
			//echo sizeof($arrDocType); exit();
			
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				if(!empty($share_point_link[$i])){
					$sql = "INSERT INTO file_uploads (module, doc_type, share_point_link, doc_desc, reference_id, date_uploaded) 
					VALUES('AP', '$arrDocType[$i]', '$share_point_link[$i]', '$arrDocDesc[$i]', $ap_id, now())";
					mysqli_query($con, $sql);
				
				}
			}
	
			if($_POST['party_doc']){
				$party_doc = $_POST['party_doc'];
				for($i = 0; $i < sizeof($party_doc); $i++){
				
					$doc_in = $party_doc[$i];
									
					$sql = "SELECT module, file_path, file_name, reference_id, doc_type FROM `my_documents_files` where id = '$doc_in' ";
					$res = mysqli_query($con, $sql);
					$r11 			= mysqli_fetch_array($res);
					$filename 		= $r11['file_name'];
					$folder_path 	= $r11['file_path'];
					$arrDocType 	= $r11['doc_type'];
					$reference_id 	= $r11['reference_id'];
					
					$sql = "INSERT INTO file_uploads (module, dms_module, file_name, file_path, doc_type, reference_id, doc_invoice_no, date_uploaded) 
					VALUES('AP', 'IN', '$filename', '$folder_path', '$arrDocType', '$ap_id', '$reference_id', now())";
					mysqli_query($con, $sql);
					
				}
			}

//echo $sql. ' <<>> ' . sizeof($party_doc). ' <<>> ' . sizeof($arrDocType);
//exit('STOPED HERE 123');			
			
		//	echo "Approval Memo successful added";
		
			
			$submit_save_a		= $_POST['submit_save_a'];
			$submit_save_b		= $_POST['submit_save_b'];
			$submit_save_c		= $_POST['submit_save_c'];
			$submit_save_d		= $_POST['submit_save_d'];
			if($submit_save_a=='Save' || $submit_save_b=='Save' || $submit_save_c=='Save' || $submit_save_d == 'Save' ){
				
				$baseurl.=$modulePath.'/edit.php?sub=edit&id='.$id;
				echo "<script>window.location.href='$baseurl';</script>";
				exit();
 
			}

			$page					= $_POST['page']; 		
			$baseurl.=$modulePath.'index.php?sub=list&same_page='.$page;
			echo "<script>window.location.href='$baseurl';</script>";
			exit();
			
	}

	$page = $_GET['page'];
	$id 		= $_GET['id'];
	$ap_id		= $_GET['id']; 
	$sql="select * from sma_approval_memo where id ='$id'";
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status = $row['status'];
	$approval_status = $row['approval_status'];

		$dated  	= date('d-m-Y', strtotime($row['dated']));
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
			
	$del = $row['del'];
	
	$readonly = '';
	
	if (($status == 'Submitted' ) || $status == 'Completed' ){
		$readonly = 'READONLY';
	}

	if($del=='Y'){
		$readonly = 'READONLY';
	}	
	
 	$overhead_exp = $row['overhead_exp'];
	
	$approver_1 		= $row['approver_1'];
	$approver_2 		= $row['approver_2'];
	$approver_3 		= $row['approver_3'];
	$approver_4 		= $row['approver_4'];
	$approver_5 		= $row['approver_5'];
	$approver_6 		= $row['approver_6'];
	$approver_7 		= $row['approver_7'];
	$approver_8 		= $row['approver_8'];

	$approver_1_status 	= $row['approver_1_status'];
	$approver_2_status 	= $row['approver_2_status'];
	$approver_3_status 	= $row['approver_3_status'];
	$approver_4_status 	= $row['approver_4_status'];	
	$approver_5_status 	= $row['approver_5_status'];
	$approver_6_status 	= $row['approver_6_status'];
	$approver_7_status 	= $row['approver_7_status'];
	$approver_8_status 	= $row['approver_8_status'];

?>
<script>
        $(document).ready(function() {
            $(".doctype").select2();
            
            $("#btnaddmore").click(function() {
                var lastdocrow = $(".docrow:last");
                var totalrows = $(".docrow").length;
                var newdocrow = $(lastdocrow).clone();
                $(newdocrow).find(".control-label").html("Document " + (totalrows + 1));
                $(newdocrow).find(".doctype").val("PAN CARD");
                $(newdocrow).find(".docdesc").val("");
                $(newdocrow).find(".docfile").val("");
                $(newdocrow).find(".select2-container").remove();
                $(newdocrow).find(".doctype").select2();
                $(".docpanel").append(newdocrow);
            });
        });
</script>

<?php

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	/* 
	$sql = "SELECT * FROM `sma_help` where 1 and code = '$help_code' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$help_description = $r2['description']; */
											
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Approval Memo
            <small>Edit</small>
			<small>
				<a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
		
        </h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Approval Memo</a></li>
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
						<form id="form1" class="form-horizontal" action="edit.php?sub=Save&same_page=<?= $page ?>" method="post" enctype="multipart/form-data">
					<?php
						if($approval_status=='Rejected'){
							$status = $approval_status;
						}
					?>	
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $status;?></b></h4> </span>
						<?php 
							$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
						?>
						<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		                						
                        <?php
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active = ' ';
							$active1 = ' ';
							$active3 = '';
							$active2 = '';
						}
						else if ($_GET['active2']){
							$active2 = $_GET['active2'];
							$active = ' ';
							$active1 = ' ';
						}
						else if ($_GET['active3']){
							$active3 = $_GET['active3'];
							$active = ' ';
							$active1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						?>
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Approval Memo</a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab">Supplier Quotes</a></li>
				<?php //if($overhead_exp!='Y'){ ?>		
						<li class="<?php echo $active2;?>" ><a href="#tab_6" data-toggle="tab" id="six_tab" >Product</a></li>
				<?php //} ?>				
				<?php //if($overhead_exp=='Y'){ ?>		
                    <!--    <li class="<?php echo $active3;?>" ><a href="#tab_7" data-toggle="tab" id="seven_tab" >Expense</a></li>-->
				<?php //} ?>		
                        
						<li><a href="#tab_3" data-toggle="tab" id="third_tab">Documents</a></li>
						<li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
				<?php if( $status != 'Draft' ){	?>	
						<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
				<?php } ?>		
						<li><a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						<?php
						if($user=='Admin' && ($status == 'Completed'  || $ap_amend =='Y' ) ){
						?>
						<!--		<li><a href="#tab_5" data-toggle="tab"  class="btn btn-danger" id="five_tab" >AP Amendment</a></li>
						-->
						<?php
							}
						?>	
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['id'];?>" >
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
							<input type="hidden" name="page" value="<?= $page;?>">
							
							<div class="form-group">
							
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Serial Number</label>
                                    <input type="text" class="form-control" id="srno" name="srno" style="text-align:right;" readonly value="<?php echo $row['id'];?>">
								</div>
								
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" <?php echo $readonly; ?> name="dated" placeholder="dd/mm/yyyy" readonly
                                               value="<?php echo date('d-m-Y', strtotime($row['dated']));?>">
                                    </div>
                                </div>
								
								
								<?php $_SESSION['company'] = $row['company']; 
								
									$company_id = $row['company'];
									
									$sql 	= "select * from company where comp_id = '$company_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$comp_code = $r2['comp_code'];
									
									$location = $row['location'];
									$sql 	= "select * from sma_location where id = '$location' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$loc_name = $r2['loc_name'];
									
									$label_line .= '<b>SI SrNo:</b>'.$row['id']. ' ' .
												' <b>Company:</b>'.$comp_code. ' ' . ' <b>Location :</b> ' .''.$loc_name
									
								?>
								
                                <div class="col-sm-4">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company" id="companY" onchange="getproject(this.value)" <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							
								
								<div class="col-sm-3">
								<label for="company_id" class="control-label ">Workflow Type</label>
								<select class="form-control select3" name="trans_type" id="trans_type" required <?php echo $readonly; ?>  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_workflow_type where doc_type = 'AP'  ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
										<?php } ?>
								</select>
								</div>
								
							</div>
							
							<div class="form-group">
								
								<div class="col-sm-2">
									<label for="location" class="control-label">Location</label>
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_location where loc_comp_id = '$company_id' order by loc_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['location'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['loc_name'];?></option>
										<?php } ?>
									</select>	
								</div>
							
								
								<div class="col-sm-3">
									<label for="department" class="control-label">Department</label>
									<select class="form-control" name="department" id="department"  <?php echo $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
									</select>	
                                </div>
							
							<?php
									$checked_y ='';
									$checked_n ='';
									$overhead_exp = $row['overhead_exp'];
									if($overhead_exp!='Y' ){
										$checked_n = "CHECKED";
									}
									else if($overhead_exp=='Y'){
										$checked_y = "CHECKED";
									}
							?>
								
								<div class="col-md-3">
									<label class="control-label">Approval Memo Type :<span data-toggle="tooltip" title="#" class="badge bg-light-blue"></span></label><br>
								<!--	<input type="RADIO"  id="overhead_exp" name="overhead_exp" <?php echo $checked_n ?> value="<?php echo $overhead_exp; ?>" > <b>For PO</b> &nbsp;-->
							<?php if($overhead_exp!='Y' || $user =='Admin' ){ ?>	
									<input type="RADIO"  id="overhead_exp" name="overhead_exp" <?php echo $checked_n ?> value="<?php echo $overhead_exp; ?>" >
									<label class="control-label" style="color:red;"> For PO</label>
							<?php } ?>		
							<?php if($overhead_exp=='Y' || $user =='Admin' ){ ?>		
									<input type="RADIO"  id="overhead_exp" name="overhead_exp" <?php echo $checked_y ?> value="<?php echo $overhead_exp; ?>" >
									<label class="control-label" style="color:red;"> For Operating Expense</label>
							<?php } ?>		
								</div>
							<?php
								$tender_no 		= $row['tender_no'];
								
								$sql 	= " SELECT * FROM `sma_tender_header` WHERE id = '$tender_no'  ";
						//echo $sql;		 and company_id = '$company_id'
								$q2 	= mysqli_query($con, $sql);
								$tender_rowaffected = mysqli_affected_rows($con);
								$r2 	= mysqli_fetch_array($q2);
								$tender_title = $r2['tender_title'];
								if($tender_rowaffected >0){	
								    $tender_module = $baseurl.'tender/'."edit.php?sub=edit&id=$tender_no";
						?>
									
								<div class="col-md-2">
									<label class="control-label">Tender No.</label>
									<input type="text" class="form-control" id="tender_no" name="tender_no" value="<?php echo $row['tender_no'];?>" onchange="gettenderTitle(this.value)" >
								</div>
								
								<div class="col-md-3">
								<span id="gettenderTitle"> 
									<textarea rows="3" class="form-control" readonly id="tender_title" name="tender_title" ><?php echo $tender_title;?></textarea>
									<span class="pull-left"><a href="<?= $tender_module;?>" target="_blank" class="btn btn-info">Open Tender / RFP</a>
									</span>
								
								</span>
						
								</div>
							<?php } ?>	
							
							<?php
								$budget_head_id = $row['budget_head'];
								$sql = "select * from sma_budget where id = '$budget_head_id' ";
		//echo $sql;	
								$q3  = mysqli_query($con, $sql);
								$r3  = mysqli_fetch_object($q3);
								$budget_head    = $r3->budget_category;
								$budget_name    = $r3->budget_name;
								//$balance_budget = $r3->balance_budget;

								$used_budget	= $r3->used_budget;
								$blocked_budget	= $r3->blocked_budget;
								$total_budget	= $r3->total_budget;
						
							?>
							
							</div>
							
							<div class="form-group">
							
								<div class="col-md-12">
									<label class="control-label">Subject</label>
									<input type="text" class="form-control" id="to_Supplier" name="subject"  <?php echo $readonly; ?> value="<?php echo $row['subject'];?>" >
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="background"
                                                  placeholder="Enter text ..."  <?php echo $readonly; ?> ><?php echo stripslashes($row['background']);?></textarea>
                                    </div>
									
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Scope of Work</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason1" name="scope_of_work"  <?php echo $readonly; ?>
                                                  placeholder="Enter text ..."><?php echo stripslashes($row['scope_of_work']);?></textarea>
                                    </div>
									
								
                                </div>
								
								
								
							</div>

							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Deviations from SOP</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason2" name="deviations_from_sop"  <?php echo $readonly; ?>
                                                  placeholder="Enter text ..."><?php echo  stripslashes($row['deviations_from_sop']);?></textarea>
                                    </div>
									
								
                                </div>
								
								
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Term & Conditions</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason3" name="important_terms_conditions"  <?php echo $readonly; ?> 
                                                  placeholder="Enter text ..."><?php echo  stripslashes($row['important_terms_conditions']);?></textarea>
                                    </div>
                                
								</div>								
								
							</div>

							<!--<div class="form-group">
								<div class="col-md-6">
									<label class="control-label">Additional Cost Description</label>
									<input type="text" class="form-control" id="additional_costs" name="additional_costs"  <?php echo $readonly; ?> value="<?php echo $row['additional_costs'];?>" >
								</div>
								
								
							</div>-->
					
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
						 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
						<div class="tab-pane <?php echo $active;?>" id="tab_2">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
									
									<p><?= $label_line; ?></p>
									
                                        <h4 class="box-title">Supplier Quotes</h4>
									<?php $data_mode = 'Add';
										if($status =='Draft'){
									?>
                                        <span class="pull-right">
                                            <a href="#"
                                               class="btn btn-primary" data-mode='Add'
                                               data-toggle="modal" data-target="#modalAddItem<?php echo $data_mode;?>">Add
                                            </a>
                                        </span>
									<?php } ?>
                                    </div>
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                
                                                    <th style="text-align:left">Supplier Name</th>
													<th style="text-align:left">Quote Ref.No.</th>
													<th style="text-align:left">Supplier Selected</th>
													<th style="text-align:right">Quoted Amount</th>

													<th width="10%" style="text-align:right">Actions</th>
											</tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php
												//$approval_hdr_id=$_GET['approval_hdr_id'];
												$sql="SELECT * from sma_approval_details where approval_hdr_id='$id'";
												$result = mysqli_query($con, $sql);
												echo mysqli_error($con);
												
												while($row = mysqli_fetch_array($result)){
												$approval_hdr_id = $row['approval_hdr_id'];
												$approval_srno = $row['approval_srno'];
												
												$supplier_name = $row['supplier_name'];
												$sql = "select * from sma_party_mst where id = '$supplier_name' ";
												$q2  = mysqli_query($con, $sql);
												$r2 = mysqli_fetch_array($q2);
												$supplier_name  = $r2['party_name'];
												
												$party_id_doc   = '';
												$selected = '';
												$vendor_selected = $row['vendor_selected'];
												if($vendor_selected=='Y'){
													$selected = 'Selected';
													$checker_value += $row['values'];
													
													$party_id_doc   = $r2['id'];
													
												}
												
											?>

                                            <tr>
                                 
                                            		<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
													<td width="20%" style="text-align:left"><?php echo $row['quote_ref_no'];?></td>
													<td width="10%" style="text-align:left"><?php echo $selected;?></td>
													<td width="10%" style="text-align:right"><?php echo $row['values'];?></td>
													<td width="10%" style="text-align:right">
													<a href='#modalEditItemq' data-id='<?php echo $approval_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItemq<?php echo $approval_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
														<?php include "edit_func.php"; ?>							
<!-- Modal Edit Item-->														
												<?php if($status =='Draft'){ ?>
														<a href='#modalDeleteItem' id='delete-<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>' data-toggle='modal' data-id='<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>' data-target='#modalDeleteItem<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>'><i class='fa fa-trash-alt'></i></a>
														</td>
												<?php } ?>		
<!-- Modal Delete Item-->								
														<?php include "del_func.php"?>							
<!-- Modal Delete Item-->

											</tr>
											    <?php }?>
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
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
						<?php if($overhead_exp!='Y'){  ?>			
									 <a href="#tab_6" class="btn btn-primary" data-toggle="tab" onclick="$('#six_tab').trigger('click')" >Next </a>
						<?php } 
							  else { ?>		  
								   <a href="#tab_6" class="btn btn-primary" data-toggle="tab" onclick="$('#six_tab').trigger('click')" >Next </a>
						<?php 	  }	  ?>
								</div>
							</div>
				
							<input type="hidden" id="checker_value" name="checker_value" readonly value="<?= $checker_value;?>" >
							
					</div>
					
					<div class="tab-pane <?php echo $active2;?> " id="tab_6">

							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
									
										<p><?= $label_line; ?></p>
									
                                        <h4 class="box-title">Product Details</h4>
							<?php if (empty($readonly)){ ?>
                                    			
                                        <span class="pull-right">
                                            <a href="#modalAddAPItem"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddAPItem">Add 
                                            </a>
                                        </span>
							<?php } ?>
                                    			
                                    </div>
						
					<span id="APItemsTableBody">
					<?php
						if($_GET['emsg']=='Y'){
					?>		
							<label class="control-label" style="color:red;" >Error : Product total should not be greater then Approval Value !!!</label>	
					<?php } ?>	
						
					</span>
					
                                    <div class="box-body">
                                        <table id="prItemsTable" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Cost Center</th>
												<th>Supplier</th>
    											<th style="text-align:right;">Quantity.</th>
												<th style="text-align:right;">Bal.Qty.</th>
                                                <th>Unit</th>
                                                <th style="text-align:right;">Rate</th>
												<th style="text-align:right;">GST%</th>
                                                <th style="text-align:right;">Amount</th>
												<th style="text-align:right;">Bal.Amount</th>
												
												<th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="prItemsTableBody">
											<?php	
												$bal_budget_flag ='';
												$approval_hdr_id = $ap_id;
												
								 			$sql="SELECT * from sma_approval_items where approval_hdr_id = '$approval_hdr_id' ";
												$result = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$row_item = mysqli_affected_rows($con);
												$value="";
												while($row = mysqli_fetch_array($result)){
													$qty 				= $row['quantity'];
													$po_value	 		= $row['po_value'];
													$po_quantity		= $row['po_quantity'];
													$budget_id			= $row['budget_id'];
																									
													$bal_qty 			= number_format($qty - $po_quantity,4);
													
													$supplier_id 	= $row['supplier_id'];
													$sql="SELECT * FROM sma_party_mst where id = '$supplier_id' ";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$mat = mysqli_fetch_array($res2);
													$party_name = $mat['party_name'];
													
													$product_id 	= $row['product_id'];
													$sql="SELECT * FROM sma_product where id = '$product_id' ";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$mat = mysqli_fetch_array($res2);
													$product_name = $mat['name'];
													$product_category = $mat['group'];
													
													$budget_id 		= $row['budget_id'];
													$sql="SELECT * FROM sma_budget where id = '$budget_id'";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$cat = mysqli_fetch_array($res2);
													$category_description = $cat['budget_head'];
													$total_budget 			= $cat['total_budget'];
													$used_budget 			= $cat['used_budget'];
													$blocked_budget 		= $cat['blocked_budget'];
													$adjustment_budget 		= $cat['adjustment_budget'];
													$bal_budget = ($total_budget + $adjustment_budget) - ($used_budget + $blocked_budget ); 
													if($bal_budget <0 ){
														$bal_budget_flag = 'Y';
													}	
													
													$sql="SELECT * FROM sma_budget_subgroup where id = '$category_description'";
													$res2 = mysqli_query($con, $sql);
													echo mysqli_error($con);
													$cat = mysqli_fetch_array($res2);
													$category_description = $cat['budget_head'];
													
													
													$rate 	= $row['unit_rate'];
													$gst	= $row['gst'];
													$amount = round(($qty * $rate) + (($qty * $rate) * $gst / 100),0);
													
													$bal_amount = round($amount - $po_value,2) ;
													if($bal_amount<1){
														$bal_amount = 0;
													}	
													
													if($overhead_exp=='Y'){
														$sql  = "SELECT sum(b.amount) as amount_tot, sum(gst_amount) as gst_amount_tot, reference FROM `sma_travel_expenses` a, sma_expenses b  WHERE 1 and a.id = b.approval_ref_no  and approval_number = '$ap_id' and reference = '$product_id' ";
														$res  = mysqli_query($con, $sql);
														echo mysqli_error($con);
														$r2 = mysqli_fetch_array($res);
														$amount_totp		=  $r2['amount_tot'];
														$gst_amount 		=  $r2['gst_amount_tot'];
														$reference_id 		=  $r2['reference'];
														$bal_amount = round($amount - $amount_totp,2) ;
														if($bal_amount<1){
															$bal_amount = 0;
														}	
													}
													
													$tot_amount = $tot_amount + $amount;
													
													$delivery_date = date('d-m-Y', strtotime($row['delivery_date']));
													if($delivery_date == '01-01-1970'){
														$delivery_date = '';
													}
													$rid = $row['id'];
												?>	
													<tr>
														<td width='20%'><?php echo $product_name;?></td>
														<td width='20%'><?php echo $category_description;?> </td>
														<td width='20%'><?php echo $party_name;?> </td>
														<td width='8%' style="text-align:right;"><?php echo $row['quantity']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $bal_qty;?></td>	
														
														<td width='8%'><?php echo $row['uom']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $row['unit_rate']?></td>	
														<td width='8%' style="text-align:right;"><?php echo $row['gst']?></td>
														<td width='10%' style="text-align:right;"><?php echo $amount?></td>
														<td width='10%' style="text-align:right;"><?php echo $bal_amount?></td>	
													
														<!--<td width='10%'><?php echo $delivery_date?></td>-->
														<td width='6%'>
														<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->
													<?php include "edit_apitem_func.php"; ?>
<!-- Modal Edit Item-->
													<?php if($status!='Completed' && empty($readonly) ){?>
														<a href='#modalDeleteAPItem' data-id='<?php echo $approval_hdr_id;?><?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalDeleteAPItem<?php echo $approval_hdr_id;?><?php echo $rid;?>' <i class='fa fa-trash-alt'></i></a>
														
														<?php include "del_apitem_func.php"; ?>
													<?php } ?>	
												</td>
													
<!-- Modal Delete Item-->								
												<!--	<a href="del_apitem_func.php?sub=delete&ap_id=<?php echo $ap_id;?>&id_no=<?php echo $rid;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
												-->
													
<!-- Modal Delete Item-->
														
													</tr>
											<?php
												}
												
												//$checker_value = $tot_amount;
												
											?>		

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
												<th></th>
												<th></th>
												
                                                <th colspan="2">Total Amount</th>
                                                <th style="text-align:right;"><?php echo $tot_amount;?></th>
												
                                            </tr>
                                            </tfoot>
											
                                        </table>
										
									<input type='hidden' id='product_value' name='product_value'  value='<?= $tot_amount; ?>'>
									
                                    </div>
                                </div>
                            </div>
							
	           				<div class="box-footer">
								<div class="col-sm-6">
									<a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									
									<span>&nbsp;&nbsp;</span>
							<?php //if($overhead_exp!='Y'){  ?>			
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
							<?php //} ?>
									 
								</div>
							</div>					
					
				</div>
				
				
				<div class="tab-pane <?php echo $active3;?> " id="tab_7">

							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
										<p><?= $label_line; ?></p>
										
                                        <h4 class="box-title">Expense Details</h4>
							<?php if (empty($readonly)){ ?>
                                    			
                                        <span class="pull-right">
                                            <a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
                                        </span>
							<?php } ?>
                           
                                    </div>
															
					<span id="te_exp_edit"></span>
					
                                    <div class="box-body">
                                        <div class="form-group">
							<div class="col-md-12">
							
								
									<table id="prtable" class="table table-bordered table-striped">
										 <tr>
												<th> SrNo.</th>
												<th> Expense Type</th>
												<th> Cost Center</th>
												
												<th style="text-align:right;"> Amount</th>
												<!--<th> GST Amount</th>-->
												<th> Narration</th>
												<th style="text-align:right;"> Action</th>
										 </tr>
										
									<tbody>
									<?php
										$j = 0;
										$tot_gst_amount = 0;
										//$modulePath1 = "travel_approval/";
										$sql = " SELECT * from sma_approval_expenses where approval_hdr_id = '$ap_id' ";
										$result = mysqli_query($con, $sql);
										$row_exp = mysqli_affected_rows($con);
										echo mysqli_error($con);
										while($row1 = mysqli_fetch_array($result)){
											$j = $j + 1;
											
											$reference = $row1['reference'];
											$budget_id = $row1['budget_id'];
											$sql="SELECT * from account_mst where id = '$reference'";
											
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$reference = $r2['account_name'];
											
											$tot_exp_amount += $row1['amount'] + $row1['gst_amount'];
											$tot_gst_amount += $row1['gst_amount'];
											
											$sql ="SELECT * FROM `sma_budget` where id = '$budget_id' ";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$budget_head = $r2['budget_head'];

											$dated = date('d-m-Y', strtotime($row1['dated']));
											if($dated=='01-01-1970'){
												$dated ='';
											}	
											
									?>
										<tr>
											<td width="2%"><?php echo $j;?></td>
											<td width="25%"><?php echo $reference;?></td>
											<td width="25%"><?php echo $budget_head;?></td>
											
											<td width="10%" style="text-align:right;"><?php echo number_format($row1['amount'],2);?></td>
											<!--<td width="05%" style="text-align:right;"><?php echo $row1['gst_amount'];?></td>-->
											<td width="30%"><?php echo $row1['note'];?></td>
											<td width="08%" style="text-align:right;">
											<?php $rid = $row1['id']; 
											if (empty($readonly)){
											?>
											<a href='#modalEditItemexp' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItemexp<?php echo $rid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_com_func.php"; ?>
<!-- Modal Edit Item-->						
												<a href="delete_expenses.php?sub=delete&id=<?php echo $row1['id'];?>&ap_id=<?php echo $ap_id;?>&exp_type=C" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											</td>
											<?php } ?>
										</tr>

										<?php }?>
									</tbody> 
										<?php $tot_exp_amount = $tot_exp_amount  ?>
										<tr> <th colspan="3" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo number_format($tot_exp_amount,2); ?> </th><td colspan="3"></td></tr>
										
										<input type="hidden" id="total_exp_amounT"  value="<?php echo $tot_exp_amount;?>" >
										
									</table> 
									
							</div>	
						</div>
                                    </div>
                                </div>
                            </div>
							
                           
	           				<div class="box-footer">
								<div class="col-sm-6">
							<?php if($overhead_exp!='Y'){  ?>		
									<a href="#tab_6" class="btn btn-primary" data-toggle="tab" onclick="$('#six_tab').trigger('click')" >Previous</a>
							<?php } ?>
							<?php if($overhead_exp=='Y'){  ?>		
									<a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>
							<?php } ?>
							
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
						<div class="box-header">
							<p><?= $label_line; ?></p>
						</div>
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'AP' AND reference_id = " . $ap_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="30%" >Description</th>
										  <th  width="40%">Share Point Link&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  
										  </th>
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
													$share_point_link = $docRow['share_point_link'];
													
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
													$dms_path = '';
													$dms_module = $docRow['dms_module'];
													if($dms_module=='IN'){$dms_path = $baseurl.'dms/';}
													
										  ?>
                                          <tr>
                                              <td><?php echo $document ?></td>
                                              <td><?php echo $doc_desc ?></td>
											  <td><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link ?></a></td>
											  
                                            <!--  <td><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>-->
								<?php	
									if($status !='Completed' || $user=='Admin'){
								?>  
											  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
								<?php } ?>              
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  
						<div class="table-responsive">
						
						<?php	
							
							$sql = "SELECT count(*) as cnt FROM `my_documents_files` a, sma_document_type b , sma_party_mst c, dms_inward d 
									where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
									and a.reference_Id = d.inward_no and c.id = '$party_id_doc' "; //  limit 0,5 
							$res = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$cn1 = mysqli_fetch_array($res);
							$cnt = $cn1['cnt'];
							
						if($cnt>0){
								
						?>  
    						
							<div class="col-sm-1" >&nbsp;</div>
							<div class="col-sm-6" >		
								 <span style="font-size:24px;color:blue;">Select Document from DMS : </span>
								<input type ="checkbox" id="partyDoc" name="partydoc" value='Y' onclick="getpartydoc(this.value)"  >
								<input type ="hidden" id="party_id_doc" name="party_id_doc" value="<?php echo $party_id_doc; ?>" >
							</div>	
					<?php } ?>
							
					<span id="gegpartyDoc">
						
					</span>
					
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control col-sm-2 doctype" name="doctype[]"  >
                                                <option value="0">Select</option>
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
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="40%">
											 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										<!--<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>-->
                                         <td width="10%"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
					
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
							
						
							<div class="box-footer">
								<div class="col-sm-6">
								<?php if($overhead_exp!='Y'){  ?>		
									<a href="#tab_6" class="btn btn-primary" data-toggle="tab" onclick="$('#six_tab').trigger('click')" >Previous </a>
								<?php } ?>
								<?php if($overhead_exp=='Y'){  ?>
									 <a href="#tab_6" class="btn btn-primary" data-toggle="tab" onclick="$('#six_tab').trigger('click')" >Previous </a>
								<?php } ?>	
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
							<?php
							
								$_SESSION['ap_id'] 	= $ap_id;
								$_SESSION['status']  = $status;
							
							?>

							<span id="predit"></span>
						
					<?php //if($del!='Y'){ 
							$sql = " select count(*) as cnt from sma_purchase_order where 1 and del !='Y' and approval_status != 'Rejected' and approval_memo_ref  = '$ap_id' ";
						$res = mysqli_query($con,$sql);
						$affected_row = mysqli_affected_rows($con);
						echo mysqli_error($con);
						$r3 = mysqli_fetch_array($res);
						$affected_row = $r3['cnt'];
						?>
					
							<div class="box-footer">
								
								<div class="col-sm-6">
								
								<?php 	
									if($del=='Y' || $status=='Suspend' || ($user=='Admin' &&  $status!='Draft' ) ){ ?>
										
										<div class="col-sm-6">
											<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
										</div>			
								<?php
									}
									else if ( ($approval_status=='Rejected' || $approval_status=='Approved' ) && $affected_row==0 ){	
								?>
										
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
											
								<?php	
									} ?>
									
									<?php $did = $_GET['id']; ?>
								
									
							<?php		// as per Rajesh
							
								if ( $status == 'Draft'  ){
									
									$sq = " SELECT approval_memo_ref FROM `sma_purchase_order` where approval_memo_ref = '$did' and approval_status !='Rejected' and del !='Y' ";
								//echo $sq;
								
									$res = mysqli_query($con,$sq);
									echo mysqli_error($con);
									$rowcount=mysqli_num_rows( $res);
									if ($rowcount=='0' ){
							?>		
									<!--a href="<?php echo $baseurl.$modulePath."/delete.php?did=$did";?>" class="btn btn-danger" >Delete</a>-->
										<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>
										<span>&nbsp;&nbsp;</span>
							<?php 	}
								}

									// as per Rajesh
							
								if ( $status == 'Completed' || $status == 'Submitted'  ){
									
							?>		
									<a href="#suspendAuthority" class="btn btn-info" data-toggle="modal" data-mode="Suspend" data-target="#suspendAuthority">Suspend</a>
								
							<?php	
								}
								
								
						//echo 	$_SESSION['role']. ' ' . $status;	
							?>
							
								</div>
								
								<div class="col-sm-6 text-right">
							<?php		
									$role			= $_SESSION['role'];
									$userid   		= $_SESSION['usrid'];	
							
						 		$_SESSION['ap_id'] 	= $ap_id;
								$_SESSION['status']  = $status;
								
								$approver_flag='';
							if( $status != 'Draft' ){
//echo $userid . ' ' . 	$approver_5 . ' ' . $approver_1_status. "<BR>";								
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
									$userid == $approver_2 && $approver_2_status=='Submitted' || 
									$userid == $approver_3 && $approver_3_status=='Submitted' ||
									$userid == $approver_4 && $approver_4_status=='Submitted' ||
									$userid == $approver_5 && $approver_5_status=='Submitted' ||
									$userid == $approver_6 && $approver_6_status=='Submitted' ||
									$userid == $approver_7 && $approver_7_status=='Submitted' ||
									$userid == $approver_8 && $approver_8_status=='Submitted' ){
				
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted' ){
										$approver_flag='Y';
									}
									
									/* $mode_status = 'Pending';
									if($userid==$approver_1 && empty($approver_2) && empty($approver_3) && empty($approver_4)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_2 && empty($approver_3) && empty($approver_4) ){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_3 && empty($approver_4) ){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_4){
										$mode_status = 'Approve';
									} */
									
								}
//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){	
							?>	
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
							<?php } 
							
								}
							?>
							
							<?php 
								$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
							?>		
									
									<span>&nbsp;&nbsp;</span>
							
							<?php		//echo $role. ' ' . $reviewed_by . ' ' . $userid . ' ' . $c_role. ' <<>> '. $row['approval_status'];
							//|| $role=='HOD - Account' 
							if($bal_budget_flag == 'Y'){
								echo "<span style='color:red;'>Error : Selected Material does not have sufficient budget...</span>";
							}	
								if( $status == 'Draft' && $approval_status != 'Rejected' && ($row_item >0 || $row_exp >0 ) && $bal_budget_flag != 'Y'){
							?>
								<span class='hidesend'>	
									<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								</span>	
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php 	} 
								if ( $approval_status!='Rejected' ){
						?>
								<span class='hidesend'>	
									<button type="submit" class="btn btn-primary" form="form1" >Save </button>
								</span>	
						<?php 	}  ?>			
								
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="<?php echo $baseurl2;?>" class="btn btn-default" >Back</a>
									
							
								</div>
						</div>		
								
					<?php  
						//if( $status == 'Submitted' && $approval_status!='Rejected'){
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
									<?= $approver_1_name . " <BR> " . $approver_1_role;?>
									
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
									<?= $approver_2_name . " <BR> " . $approver_2_role;?>
									
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
									<?= $approver_3_name . " <BR> " . $approver_3_role;?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 4</label><BR>
									<?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php	if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 5</label><BR>
									<?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 6</label><BR>
									<?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_7)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_7' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_7_name = $rw['username'];
								$approver_7_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 7</label><BR>
									<?= $approver_7_name . " <BR> " . $approver_7_role; ?>
									
								</div>
					<?php	
							}
					?>
					<?php		if(!empty($approver_8)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_8' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_8_name = $rw['username'];
								$approver_8_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 8</label><BR>
									<?= $approver_8_name . " <BR> " . $approver_8_role; ?>
									
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
							
								if(!empty($approver_1)){
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
								if(!empty($approver_4)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_4 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_4" name="approver_4" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_4 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							<?php if(!empty($approver_5)){ 
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_5 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}												
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_5" name="approver_5" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_5 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							<?php if(!empty($approver_6)){ 
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_6 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_6" name="approver_6" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_6 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							
							<?php if(!empty($approver_7)){ 
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_7 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
											$rolenm .= $rw['role'];
										}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_7" name="approver_7" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_7 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>	
							
							<?php if(!empty($approver_8)){ 
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_8 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
											$rolenm .= $rw['role'];
										}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_8" name="approver_8" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_8 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							
							
							<?php } ?>	
							
								<BR>
								
							</div>
							
							
						<?php } ?>		
						
						</span>
						
							
							
					<?php //}  ?>
		
						
						
		</div>	
						
						
						<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								<p><?= $label_line; ?></p>
								<?php 
									
									
									$srno = $ap_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AP' order by id ";
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
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'AP' order by id desc";
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
				<?php 
												
				$s1  = "SELECT * from sma_comment where doc_id = '$ap_id' and doc_type = 'AP' order by id desc ";
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
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $ap_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $ap_id;?>,'AP','C',<?= $page;?>)" >Submit</button>		
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
						
						
						<div class="tab-pane" id="tab_5">
							
							<div class="modal-header" >
								
								<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Sr.No.</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Supplier Name</th>
					<th style="text-align:right;">Amount</th>
					<th>By</th>
					<th>Status</th>
					<th>Decision</th>
<!--					<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>
		<?php
					$modulePath = "approval/";
					
					$sql="SELECT * from sma_approval_memo where ap_old_no = '$ap_id' ";
					$result = mysqli_query($con, $sql);
					echo mysqli_error($con);
					
					while($row = mysqli_fetch_array($result)){
						
						$rid = $row['id'];
					
						$company = $row['company'];
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $row['department'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						$party_name ='';
						$ap_id = $row['id'];
						$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$ap_id' and b.id = a.supplier_name " ;
			
						$ii='';
						$party_name  = '';
						$amount		 =0;
						$q2  = mysqli_query($con, $sql);
						$raffect = mysqli_affected_rows($con);
						while($r2 = mysqli_fetch_array($q2)){
							
							if($ii>0){$party_name.=', <BR> ' ;}
							
							$party_name  .= $r2['party_name'];
							$amount		 += $r2['values'];
							
							$ii+=1;
							
						}
						
						//if($raffect==0){continue;}
				//echo $sql;		
			//exit();										
						$j=$j+1;						
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
					<td width="1%"><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="4%" style="text-align:right;"><?php echo $row['id'];?></td>
					<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
					<td width="29%"><?php echo $company;?></td>
					<td width="20%"><?php echo $party_name;?></td>
					<td width="10%"  style="text-align:right;" ><?php echo $amount;?></td>
					<td width="10%"><?php echo $row['changed_by'];?></td>
					<td width="08%"><?php echo $row['status'];?></td>
					<td width="08%"><?php echo $row['approval_status'];?></td>
					
<!--					<td width="5%" style="text-align:right;" >
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						
						<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
						<?php include "view_history.php"; ?>
						<a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>

					</td>-->
		<!--				<td width="10%" style="text-align:right;">
						<a href="purchase_requisition.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
						
						<a href="purchase_requisition.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a></td>-->
				</tr>
		</a>		
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>	
			  
							</div>
						
						</div>
						
						
					</div>
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1  ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
				
                        </form>
                    </div>
                </div>
				
				
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->


<!--Add Expenses Popup-->
<div class="modal fade" id="addExpenses" role="dialog" aria-labelledby="addExpenses">
    <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addExpenses">Expenses </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12123">
                                    <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data" >
                                        
										<?php   
										
											//$ap_id 		= $_SESSION['ap_id'];
											$status 	= $_SESSION['status'];
											$role		= $_SESSION['role']; //Maker
											$user_category = $_SESSION['user_category'];
											
										?>
										<input type="hidden" name="ap_id" id="ap_idE" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
									
									<div class="form-group">
										
										<div class="col-md-6">
											<label class="control-label">Expense Type * </label>
										
											<select class="form-control" name="expence_name" id="expence_Name" required autocomplete="off" onchange="getcatbudgetexp(this.value)"; > 
												<option value=""> Select </option>
												<?php //getcostcenter_exp
													$sql = "SELECT * from account_mst where account_type = 'E' and budget_code!= '' order by account_name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['account_name'] ?> </option>	
												<?php } ?>
											</select>
										</div>
									
									</div>
									
									
							<!--		<div class="form-group">
										<div class="col-sm-12">
											<span id="getcatbudgetexp">
												
											</span>
										</div>
									</div>	-->
						<span id="getcatbudgetexp">			
							<div class="form-group">
                                
								<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group**</label>
									<select class="form-control" id="costcenter_group_exp" name="costcenter_group_exp" required="true" onchange="getcostcenter_exp(this.value);" >
										<option value="">Select</option>
									<?php
										/* 
										$sql = " SELECT * FROM sma_budget_name where 1  ORDER BY name ASC ";
										$q2  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_object($q2)){
											$name = $r2->name;
											$id = $r2->id; */
									?>		
									<!--	<option value='<?php echo $id ?>'><?php echo $name. ' '. $id; ?></option>-->
									<?php //}; ?>
									</select>
                                </div>
								
								<div class="col-sm-6">
									
									<label for="itemName" class="control-label">Cost Center Name**</label>
									<span id="getcostcenter_exp" >	
										<select class="form-control" required="true" >
											<option value="">Select ...</option>
										</select>
									</span>
								</div>
								
                            </div>
							
							<div class="form-group">	
								<div class="col-sm-12">
									<span id="getcatbudget_exp">
											
									</span>
								</div>
							</div>
						</span>		
								
									<div class="form-group">
									
										<div class="col-md-3">
											<label for="approver" class="control-label"> Amount </label>
									
											<input type="text" class="form-control" autocomplete='off' onblur="total_amt();" name="amount" id="Amount" value="" style="text-align:right;" >
										</div>
										
										<div class="col-md-9">
											<label for="approver" class="control-label">Narration</label>
											<textarea rows="1" class="form-control" name="remarks" id="Remarks" ></textarea>
										</div>
										
									</div>
									
									<div style="color:red;font-weight:bold;" id="showmsg" > </div>
									
								</form>
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
								<button type="button" class="btn btn-primary" id="saveExp">Save</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Expenses  Popup End -->
	  
	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddAPItem" role="dialog" aria-labelledby="modalAddAPItemLabel">
    <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddAPItemLabel">Add Product to Order </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="purchaseId" value="<?php echo $ap_id;?>">
							
							<div class="form-group">
                                <div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" name="categoryId" required onchange="getproduct(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group where 1 ORDER BY product_group  ASC";
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
									<span id="getproduct" >
										<select class="form-control" id="itemName" name="itemName" >
											<option value="">Select</option>	
										</select>
									</span>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemDescription" class="control-label">Description</label>
								<span id = "getdesc">	
                                    <textarea rows='02' class="form-control" id="itemDescription" placeholder="Item Description..."></textarea>
								</span>
                                </div>
                            </div>
							
						<span id="getcostcenter" >
											
                            <div class="form-group">
                                
								<div class="col-sm-4">
									<label for="itemName" class="control-label">Cost Center Group</label>
                                </div>
								
								<div class="col-sm-2">
									<label for="itemName" class="control-label">Cost Center Code</label>
								</div>
								
								<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Name</label>
								</div>
								
                            </div>
							
							<div class="form-group">	
								<div class="col-sm-12">
									<span id="getcatbudget">
											
									</span>
								</div>
							</div>
								
						</span>
								
						<!--<div class="well well-sm" > -->
							
							<?php
							
								$b_readonly = '';
						
							?>
							
							<div class="form-group col-md-12">
							    <div class="col-sm-12">
									<label for="itemName" class="control-label">Supplier for Purchase Order</label>
									<select class="form-control select2-123" name="supplier_id" id="supplier_ID" >
									<option value=""> Select </option>
										<?php $sql = "SELECT b.* FROM `sma_approval_details` a, sma_party_mst b where vendor_selected = 'Y' and a.supplier_name = b.id and approval_hdr_id = '$ap_id' order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="text" class="form-control" id="itemQuantity" autocomplete=='off'  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
									<span id="getunit1">
										<input type="text" class="form-control" id="itemUnits"  name='itemunits' readonly >
										
									</span>

                                </div>
                                <div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate" autocomplete=='off' style="text-align:right;"  onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>

                                <div class="col-sm-2">
									<label for="itemGST" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST" value="0" autocomplete=='off' style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>

                                <div class="col-sm-2">
									<label for="itemamount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                            
							</div>
							
							<span id="errbudget" style="color:red;" ></span>
							
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
                <button type="button" class="btn btn-primary"  onclick="calculateTotalAmount();" id="addItem">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Item-->


<!-- Modal Add Vendor Quotaion-->
<div class="modal fade" id="modalAddItem<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add - Supplier Quotes
				<span style="float:right;margin-right:50px;color:red;" >Only verified KYC suppliers are displayed in the list. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem.php?sub=Save" method="POST">

<!--							<input type="text" id="data_mode" value=<?php echo $data_mode; ?> > -->

							<input type="hidden" name="approval_hdr_id" value=<?php echo $ap_id; ?>>
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2-123" name="supplier_name" onchange="getpangst(this.value)">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where 1 and party_kyc ='Y' order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['supplier_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
							
                            <div class="form-group col-md-12">
								<span id="getpangst">
									
								</span>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-3 control-label">Quote Ref.No</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="quote_ref_no" placeholder=" Enter QUOTE REF. Number">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-3 control-label">Vendor Selected.</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="vendor_selected" value="Y">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemvaluess" class="col-sm-3 control-label">Values</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control"  style="text-align:right;" name="values">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-3 control-label">Remarks</label>
                                <div class="col-sm-9">
                                    <textarea rows="3" class="form-control"  name="remarks"></textarea>
                                </div>
                            </div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id='saveForm' >Save changes</button>
							</div>
                        </form>
                    </div>
                </section>
            </div>
<!--            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send for Approval </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										
											$ap_id 		= $_SESSION['ap_id'];
											$status 	= $_SESSION['status'];
											$role		= $_SESSION['role']; //Maker
																				
										?>		
										<input type="hidden" name="ap_id" id="ap_idE" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										
										<input type="hidden" id="approverE" name="approver" value='<?= $userid ?>' >
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
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
                <h4 class="modal-title" id="rejectAuthority">Reject Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ap_id" id="ap_idR" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										
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
	  
	  
<!-- Modal Delete Item-->
<div class="modal fade" id="modalDeleteItem" role="dialog" aria-labelledby="modalDeleteItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteItemLabel">Delete Item of Approval </h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete item "Item 1"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger">Yes</button>
            </div>
        </div>
    </div>
</div>



<!--Maker Workflow Popup-->

<div class="modal fade" id="makerAuthority" role="dialog" aria-labelledby="makerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makerAuthority">Send Back To Maker </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
								<form class="form-horizontal">
                                        
										<?php   
											
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$user_department = $_SESSION['user_department'];
											$company		 = $_SESSION['company'];
											
										?>
										
										<input type="hidden" name="ap_id" id="ap_idM" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeM" name="mode" value='Checker'>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksM"></textarea>
											</div>
										</div>
									
								</form>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary" id="submitMaker">Submit</button>
									</div>
			
                                </div>
                            </div>		
			</section>
		</div>
    </div>
  </div>
</div>

<!--Maker Workflow Popup End -->


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
										
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="ap_id" id="ap_idD" value="<?php echo $ap_id; ?>" >
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
										
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="ap_id" id="ap_idZ" value="<?php echo $ap_id; ?>" >
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


<!--Suspend  Popup-->

<div class="modal fade" id="suspendAuthority" role="dialog" aria-labelledby="suspendAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="suspendAuthority">Do you want to Suspend? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="ap_id" id="ap_idZ" value="<?php echo $ap_id; ?>" >
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
								<button type="button" class="btn btn-primary" id="submitSuspend">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Suspend Popup End -->

<!--Approval Workflow Popup-->

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Reject Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$ap_id 	= $_SESSION['ap_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="ap_id" id="ap_idR" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										
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

<?php
	include "../help_model.php";
?>

	  
	  
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><select class="form-control select2 doctype" name="doctype[]"  ><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="40%">			 <textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables 
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>
-->

<script>


   $("#submitMaker").on("click", function(e){
        var sub 			= 'sub11';
		var mode		 	=  $("#modeM").val();	
		var ap_id		 	=  $("#ap_idM").val();
		var remarks			=  $("#remarksM").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );
		 $('#makerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						remarks:remarks,
						sub11:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		var ap_id		 	=  $("#ap_idE").val();
		//var approver		=  $("#approverE option:selected").val();
		var approver		=  $("#approverE").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		 $('#checkerAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub 			= 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var ap_id		 	=  $("#ap_idE").val();
	    var status 			=  $("#statuS").val();
		
		var company			= $("#companY").val();
		var approver		=  $("#approverE").val();
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();

//alert(approver + ' ' + status + ' ' + statusap);
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ap_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						company:company,
						status:status,
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var ap_id		 	=  $("#ap_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + ap_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var ap_id		 	=  $("#ap_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + ap_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});

$("#submitSuspend").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var ap_id		 	=  $("#ap_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + ap_id + ' ' + st_flag);
	
		$('#suspendAuthority').modal('hide');
		var strURL = "py_suspend_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});
	
		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var ap_id		 	=  $("#ap_idR").val();
		
		var status 			=  $("#statuS").val();
		var company			= $("#companY").val();
//		var budget_head_id	= $("#budget_head_Id").val();
//		var budget_name		= $("#budget_Name").val();		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ap_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						company:company,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

	
    $("#editSave").on("click", function(e){
        var sub = 'sub1';
	//	var mode = $("#mode").val();

		var approval_hdr_id =  $("#approval_hdr_id").val();
		var approval_srno 	=  $("#approval_srno").val();
        var supplier_id   	=  $("#supplier_name option:selected").val();
//      var name =          $("#itemName option:selected").html();
//		var catid =         $("#categoryId option:selected").val();
        var quote_ref_no 	=  $("#quote_ref_no").val();
//alert(sub1 + ' ' + quote_ref_no);	
        var vendor_selected =  $("#vendor_selected").val();
        var values 			=  $("#values").val();
		var remarks 		=  $("#remarks").val();
        $('#modalAddItem').modal('hide');
		var strURL = "saveitem.php";
		$.post(strURL,{ id:id,approval_hdr_id:approval_hdr_id,
							approval_srno:approval_srno,
							supplier_id:supplier_id,
							quote_ref_no:quote_ref_no,
							vendor_selected:vendor_selected,
							values:values,
							remarks:remarks,
							sub1:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//        saveItem(mode);
		
    });


    /*
				There is a bug in datepicker format due to which it does not set for AdminLTE 2 theme.
				Defaulting dates to mm/dd/yyyy format.

				$('.datepicker').datepicker({
						format: 'd/M/Y',
						autoClose: 1
				});
		*/
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
		CKEDITOR.replace('reason4');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });
		


</script>

<script>

	function delete_appquote(approval_hdr_id, id){
		var sub = 'sub4';
        var approval_hdr_id = approval_hdr_id;
		var id	 = id;
//alert(approval_hdr_id + ' ' + id);
		$('#modalDeleteItem'+approval_hdr_id+id).modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ approval_hdr_id:approval_hdr_id,id:id,sub4:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		
		window.location.href='edit.php?sub=edit&id='+approval_hdr_id+'&active=active';
			
		setTimeout(function(){
			   location.reload();
		   },100);	
		location.reload();	
	}


	function getbudgetname(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}


	function getproject(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getproject').html(result);
		});

	}
	
	function getbudget(id){
		
        var sub    = 'sub2';
		var project = document.getElementById("companY").value;
	//	var account_year = document.getElementById("account_Year").value;
//alert(project);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub,project:project},function(result){
		      $('#getbudgethead').html(result);
		});

	}

	function getavailbudget(id){
		
        var sub    = 'sub22';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
		      $('#getavailbudget').html(result);
		});

	}
	
	function getpangst(id){
		
		var sub    = 'sub24';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub24:sub},function(result){
		      $('#getpangst').html(result);
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
			
	//	alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "app_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}


    $("#addItem").on("click", function(e){
        var sub = 'sub1';
	
		var row_affected   =  $("#row_AFFECTED_a").val();
		if(row_affected == 0){
			alert('Cost center Group and Name not available !!!');
			var errs = 'Cost center Group and Name not available !!!';
			$('#errbudget').html(errs);
			return true;
		}
		
		var approval_hdr_id =  $("#purchaseId").val();		
        var id =            $("#itemName option:selected").val();
        var name =          $("#itemName option:selected").html();
		var catid =         $("#categoryId option:selected").val();

		var supplier_id		= $("#supplier_ID").val();
		var company_id   =  $("#companY").val();
		var budget_name  =  $("#budget_Name").val();
		var budget_head  =  $("#budget_Head").val();
		var total_budget  =  $("#total_budget_a").val();
		var balance_budget  =  parseInt($("#balance_budget_a").val());
		var budget_id  		=  $("#budget_id").val();
		
		var description =   $("#itemDescription").val();

		if(supplier_id==''){
			alert('Supplier should be select...');
			return true;
		}
		
//alert(id + ' ' + approval_hdr_id + ' ' + company_id);		

        var quantity =      $("#itemQuantity").val();
        var units =         $("#itemUnits").val();
        var rate =          $("#itemRate").val();
		var gst  =          $("#itemGST").val();
        var amount =        parseInt($("#itemAmount").val());
		
		var product_value = parseInt($("#product_value").val());
		var checker_value = parseInt($("#checker_value").val());

//alert(product_value + ' <<>> ' + checker_value + ' <<<>> ' + amount);
//return true;		
		if(product_value > checker_value || amount > checker_value ){
			alert('Product total should not be greater then Approval Value !!!');
			var errs = 'Product total should not be greater then Approval Value !!!';
			$('#errbudget').html(errs);
			return true;
		}

//return true;
		
		//var deliverydate =  $("#deliveryDate").val();

//alert(total_budget + ' <<>> ' + balance_budget + ' <<<>> ' + amount);
//return true;
		if(amount > balance_budget){
			alert('Insufficient Budget...xyz');
			var errs = 'Insufficient Budget...';
			$('#errbudget').html(errs);
			return true;
		}	

		
		$('#modalAddAPItem').modal('hide');
		var strURL = "ap_item_func.php";
		$.post(strURL,{ id:id,approval_hdr_id:approval_hdr_id,
							name:name,
							catid:catid,
							description:description,
							company_id:company_id,
							supplier_id:supplier_id,
							budget_name:budget_name,
							budget_head:budget_head,
							total_budget:total_budget,
							balance_budget:balance_budget,
							budget_id:budget_id,
							quantity:quantity,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							sub1:sub},
							function(result){
		      $('#APItemsTableBody').html(result);
			  
			  //alert(result);
		});
		
//        saveItem(mode);
		
    });

	function getproduct(id){
		var sub    = 'sub25';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("companY").value;
		
//alert(sub + ' ' + id + ' ' + strURL + ' <<>> ' + company_id);
		$.post(strURL,{id:id,company_id:company_id,sub25:sub},function(result){
		      $('#getproduct').html(result);
		});

/* 		$.post(strURL,{id:id,company_id:company_id,sub26:sub},function(result){
		      $('.getcatbudget').html(result);
			 //alert(result);
		});
TESTING */		
	}

	function getunit1(id){	
        var sub    = 'sub44';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub44:sub},function(result){
		      //$('#getunit1').html(result);
			  
			var fields = result.split('-');

			var unit = fields[0];
			var description = fields[1];
			var igst = fields[2];
			
//alert(unit+ ' ' + description);			
			$('#itemUnits').val(unit);
			$('#itemDescription').val(description);
			$('#itemGST').val(igst);

		});
		
	}

	function delete_apItem(ap_id,id_no){
		
		$('#modalDeleteAPItem'+ap_id+id_no).modal('hide');
		
		var strURL = "del_apitem.php";
		$.post(strURL,{ap_id:ap_id,id_no:id_no},function(result){
		      //$('#delete_apItem').html(result);
			  location.reload();
		});
		
	}

    $("#saveExp").on("click", function(e){
        var sub = 'sub27';
			
	    var row_affected 		=  $("#row_AFFECTED").val();
		if(row_affected == 0){
			alert('Cost center Group and Name not available !!!');
			var errs = 'Cost center Group and Name not available !!!';
			$('#showmsg').html(errs);
			return true;
		}
		
		var status 				=  $("#statuS").val();
		var approval_hdr_id 	=  $("#ap_idE").val();
		var expence_name		= $("#expence_Name").val();
		//var dated				= $("#Dated").val();
		var amount				= $("#Amount").val();
		//var gst_amount		= $("#gst_Amount").val();
		//var total_amount	= $("#total_amounT").val();
		
		var amount			 = parseInt(amount);
		var total_exp_amount = parseInt($("#total_exp_amounT").val());
		var checker_value 	 = parseInt($("#checker_value").val());
		
//alert(amount + ' ' + total_exp_amount + ' > ' + checker_value );

		
		if(total_exp_amount > checker_value || amount > checker_value){
			alert('Expense total should not be greater then Approval Value !!!');
			var errs = 'Expense total should not be greater then Approval Value !!!';
			$('#errbudget').html(errs);
			return true;
		}

		//var invoice_nm		= $("#Invoice_nm").val();
		//var gst_flag		= $("#Gst_flag_A").val();
		/* if (invoice_nm==''){
			alert('Enter invoice number...');
			return false;
		}
		if (gst_amount=='' ){
			alert('Enter GST amount...');
			return false;
		}
		 */
		var gst_flag		= $('input[name=gst_flag]:checked', '#myForm').val();
		var remarks			= $("#Remarks").val();
		var exp_type		= '';
		var company_id      =  $("#companY").val();
		var balance_budget  =  $("#balance_budget_a_exp").val();
		var budget_name  =  $("#budget_Name_exp").val();
		var budget_head  =  $("#budget_Head_exp").val();
		var total_budget  =  $("#total_budget_exp").val();
		var balance_budget  =  $("#balance_budget_exp").val();
		var budget_id  		=  $("#budget_id_exp").val();
		
		$('#addExpenses').modal('hide');
		
 		var strURL = "app_func.php";
		$.post(strURL,{ approval_hdr_id:approval_hdr_id,
						expence_name:expence_name,
						amount:amount,
						exp_type:exp_type,
						remarks:remarks,
						company_id:company_id,
						budget_name:budget_name,
						budget_head:budget_head,
						total_budget:total_budget,
						balance_budget:balance_budget,
						budget_id:budget_id,
						sub27:sub},
						function(result){
		      $('#te_exp_edit').html(result);
		});
		
	});


function getcatbudgetexp(id){
		
		var sub    		= 'sub28';
		var company_id  = document.getElementById("companY").value;
		
		var strURL = "app_func.php";
//alert(sub + ' ' + id + ' <<>>' + company_id + ' <<>> ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,sub28:sub},function(result){
		      $('#getcatbudgetexp').html(result);
		});
		
/* 		var strURL = "app_func.php";
//alert(sub + ' ' + id + ' ' + company_id + ' ' + strURL);
		$.post(strURL,{id:id,sub29:sub},function(result){
		      $('#getdesc').html(result);
		}); */
		
	}
	
	
	function getcostcenter(id){
		
        var sub    = 'sub30';
		var company_id    = document.getElementById("companY").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub30:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcenterr(id){
		
        var sub    = 'sub30A';
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + company_id  );

		 var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub30A:sub},function(result){
		      $('.getcostcenterr').html(result);
		});
 
	}
 	
	function getcatbudget (id){
		var sub    = 'sub31';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("companY").value;
		var product_id    = document.getElementById("itemName").value;
		var budget_name    = document.getElementById("costcenter_group").value;
		
//alert(sub + ' ' + id + ' ' + budget_name + ' << ' + company_id + '>> ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,budget_name:budget_name,company_id:company_id,product_id:product_id,sub31:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}

	function getcostcenter_exp(id){
		
        var sub    = 'sub32';
		var company_id    = document.getElementById("companY").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub32:sub},function(result){
		      $('#getcostcenter_exp').html(result);
		});

	}

	function getcatbudget_exp(id){
		var sub    = 'sub33';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("companY").value;
		var product_id    = document.getElementById("itemName").value;
		var budget_name   = document.getElementById("costcenter_group_exp").value;
		
//alert(sub + ' ' + id + ' ' + budget_name + ' << ' + company_id + '>> ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,budget_name:budget_name,company_id:company_id,product_id:product_id,sub33:sub},function(result){
		      $('#getcatbudget_exp').html(result);
		});

	}

	function getapprover(){
			
		var company_id    	= document.getElementById("companY").value;
		var checker_value   = document.getElementById("checker_value").value;
		var trans_type    	= document.getElementById("trans_type").value;
		
		var sub = 'sub34';
//alert(sub + ' ' + company_id + ' ' + checker_value + ' ' + trans_type);
		$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,trans_type:trans_type,sub34:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}

	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}
		else if(row_affected==2){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		
		if(row_affected==5){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==6){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if(row_affected==7){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if(row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
			else if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
			else if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
			else if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
			else if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
			else if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
			else if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
			else if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		

		
		return false;
		
	}
	
	function calculateTotalAmount() {
		//alert('HERLOO...');
//        var qty1 = $('#itemQuantity_e').val();
//        var rate1 = $('#itemRate_e').val();
//		var gst1 = $('#itemGST_e').val();
		var qty1 = parseInt(document.getElementById("itemQuantity").value);
		var rate1 = parseInt(document.getElementById("itemRate").value);
		var gst1 = parseInt(document.getElementById("itemGST").value);
		var balance_budget = parseInt(document.getElementById("balance_budget").value);
//alert(qty1 + ' <> ' + rate1 + ' <> ' +  gst1 + ' <<>> ' + balance_budget);

		if(qty1==''){
			var qty1 =0;
		}
		if(rate1==''){
			var rate1 =0;
		}
		if(gst1=='' || gst1== 0){
			var gst1 =0;
		}
		//&& gst1 >0
		if(qty1>0 && rate1>0 ){
			var amt1 = parseInt(qty1) * parseInt(rate1);
//alert(amt1);	
			var amt1 = parseInt(amt1) + parseInt((parseInt(amt1) * parseInt(gst1) /100));
//alert(amt1);
		
        //amt = parseFloat(amt1);
       // amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
			
		}
	
		$('#itemAmount').val(amt1);
	
		//if(amt1 > balance_budget){
		//	 alert("Budget goes into negative balance ... Please confirm...");
			 //return true;
		//}
	//return	
	
    }
	
	function clearfld(){
		
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}	


	function getcomment(comment,ap_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,ap_id:ap_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
	
		
	function gettenderTitle(id){
		
        var sub    = 'sub37';
		var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub37:sub},function(result){
		      $('#gettenderTitle').html(result);
		});

	}
	
		
</script>

</body>
</html>
