<?php
include("../header.php");
$modulePath = "approval";
$_SESSION['reset'] = '1';

?>

<?php

//$quote_ref_no			= $_POST['quote_ref_no'];
//echo $quote_ref_no;
//exit();

//	echo $_POST['status']. ' ' . $_POST['edit']. ' <> ' . $_POST['submit2']. ' <>  ' .$_POST['submit1']. ' <>  ' . $_POST['submit'];
//	exit();

	if($_POST['edit']){
		include "saveitem.php";
	}	

	
	if($_POST['submit1']=='Approve' || $_POST['submit2']=='Reject' || $_POST['submit']=='Submit' ){

	
		$id					= $_POST['id'];
		$ap_id				= $_POST['id'];
		$status				= $_POST['status'];
		
		$account_year		= $_POST['account_year'];
		$company			= $_POST['company'];
		$budget_head_id		= $_POST['budget_head_id'];
		$budget_name		= $_POST['budget_name'];

//echo $_POST['submit']." ####1<BR>";

//Budget calculation Start
		$sql = "select * from sma_approval_details where approval_hdr_id = '$id' and vendor_selected = 'Y' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_object($q3);
		$values   = $r3->values;
//echo $sql. ' ' . $values." ####2<BR>";
			
		$prev_values = $_POST['prev_values'];
		
		$sql = "select * from sma_budget where id = '$budget_head_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_object($q3);
//echo $sql." ####3<BR>";
			
//		if($_POST['submit']=='Submit'){	
//			$blocked_budget   	= $r3->blocked_budget + $values;  //if Submitted
//			$sql = "update sma_budget set blocked_budget= '$blocked_budget'  where id = '$budget_head_id' ";
//			$q3  = mysqli_query($con, $sql);
//			//$r3  = mysqli_fetch_object($q3);
//		}
//		else 
		if($_POST['submit1']=='Approve'){
			$blocked_budget   	= $r3->blocked_budget + $values;  //if Submitted
			$sql = "update sma_budget set blocked_budget= '$blocked_budget'  where id = '$budget_head_id' ";
			$q3  = mysqli_query($con, $sql);

//			$used_budget   	= $r3->used_budget + $values ; // If Approved
//			$balance_budget = $r3->balance_budget - $values ; // If Approved
//			$sql = "update sma_budget set blocked_budget= blocked_budget - '$blocked_budget', used_budget = '$used_budget', balance_budget= '$balance_budget'  where id = '$budget_head_id' ";
//			$q3  = mysqli_query($con, $sql);
		}
		else if($_POST['submit2']=='Reject'){
			$blocked_budget   	= $r3->blocked_budget - $values ;  //if Rejected
			$sql = "update sma_budget set blocked_budget= '$blocked_budget' where id = '$budget_head_id' ";
			$q3  = mysqli_query($con, $sql);
			//$r3  = mysqli_fetch_object($q3);
		}

//echo $sql;
//exit();

//Budget calculation END
		
		if($_POST['submit1']){
			$approval_status	= 'Approved';
			$status				= 'Completed';
		}
		else if($_POST['submit2']){
			$approval_status	= 'Rejected';
			$status				= 'Draft';
		}
	
		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Submited';
		}
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];
		
		$sql = "update sma_approval_memo set approval_status	= '$approval_status', status =  '$status' , changed_by = '$user', changed_date = now() where id = '$id'";
//echo $sql."<BR>";
		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$company	= $_POST['company'];
		$role		= $_SESSION['role']; //Maker
		
		$sql = "SELECT * FROM `company` where comp_id = $company ";
//echo $sql."<BR>";
		$res = mysqli_query($con, $sql);
		$r1  = mysqli_fetch_array($res);
		$project_manager  = $r1['project_manager'];
		$project_incharge = $r1['project_incharge'];
		$coo_cxo 		  = $r1['coo_cxo'];

//echo $userid .' != ' .  $project_manager.' '. $project_incharge. ' ' . $coo_cxo."<BR>";
//exit();
		$s1   = "SELECT * from workflow_history where doc_id = '$ap_id' and doc_type = 'AP' and reviewed_by = '$userid' ";
		
		$res  = mysqli_query($con, $s1);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$reviewed_by  = $r1['reviewed_by'];

		if($project_manager == $reviewed_by){
			$approver	= $project_incharge;
		}
		else if($project_incharge == $reviewed_by){
			$approver	= $coo_cxo;
		}
		else if($coo_cxo == $reviewed_by){
			$a='';
		}
		else {
		
			$approver	= $project_manager;
			if(empty($project_manager) ){
				$approver	= $project_incharge;
			}
			if(empty($project_incharge) ){
				$approver	= $coo_cxo;
			}

		}

/*		
		if($userid != $project_manager && !empty($project_manager) ){
			$approver		  = $project_manager;	
		}
		else if($userid != $project_incharge && !empty($project_incharge) ){
			$approver		  = $project_incharge;	
		}
		else if($userid != $coo_cxo && !empty($coo_cxo) ){
			$approver		  = $coo_cxo;
		}
*/
		
//		$sql = "SELECT * FROM `sma_user` where id = '$approver' ";
//echo $sql."<BR>";
//		$res = mysqli_query($con, $sql);
//		$r1  = mysqli_fetch_array($res);
//		$comid		 = $r1['company_id'];
		
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date) 
									values('AP', '$ap_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now() )";
//echo $sql."<BR>";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$sql="select * from sma_user where id='$approver' ";
//echo $sql."<BR>";
		$result = mysqli_query($con, $sql);
		//$rowcount = mysqli_num_rows($result);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
//exit("RAVINDRA STOPED...");
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$srno;
		
		$msg = 'Approval Memo Number : '.$ap_id . ' ' . 'Dated : ' . date("d-m-Y");
		include "ap_mail.php";
		
		$baseurl.=$modulePath;
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
	if($_GET['sub']=='Save'){
			$id					= $_POST['id'];
			$ap_id				= $_POST['id']; 
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$account_year		= $_POST['account_year'];
			$company			= $_POST['company'];
//			$project			= $_POST['project'];
			$department			= $_POST['department'];
			$budget_head		= $_POST['budget_head_id'];
			$budget_name		= $_POST['budget_name'];
			$against_indent_no	= $_POST['against_indent_no'];
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

  			$sql="update sma_approval_memo set id	= '$id',
						dated				= '$dated',
						account_year		= '$account_year',
						company				= '$company',
						department			= '$department',
						budget_head			= '$budget_head',
						against_indent_no	= '$against_indent_no',
						budget_available	= '$budget_available',
						subject				= '$subject',
						background			= '$background',
						scope_of_work		= '$scope_of_work',
						deviations_from_sop	= '$deviations_from_sop',
						important_terms_conditions	= '$important_terms_conditions',
						description			= '$description',
						cost				= '$cost',
						additional_costs	= '$additional_costs'

				where id='$id'";
		
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/ap/" . $ap_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('AP', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $ap_id . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/ap/" . $ap_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
			
		//	echo "Approval Memo successful added";
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$id 		= $_GET['id'];
	$ap_id		= $_GET['id']; 
	$sql="select * from sma_approval_memo where id ='$id'";
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status = $row['status'];
	$readonly = '';
	if ($status == 'Submited'){
		$readonly = 'READONLY';
	}
	
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Approval Memo
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
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
						<form id="form1" class="form-horizontal" action="edit.php?sub=Save" method="post" enctype="multipart/form-data">

		                <span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>

                        <?php
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						?>
					<ul class="nav nav-tabs">
                        <li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Approval Memo</a></li>
                        <li class="<?php echo $active;?>" ><a href="#tab_2" data-toggle="tab" id="second_tab">Suppliers</a></li>
                        <li><a href="#tab_3" data-toggle="tab" id="third_tab">Documents</a></li>
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['id'];?>" >
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
							
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
                                        <input type="text" class="form-control" id="prDate" <?php echo $readonly; ?> name="dated" placeholder="dd/mm/yyyy"
                                               value="<?php echo date('d-m-Y', strtotime($row['dated']));?>">
                                    </div>
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
								
									<div class="col-md-2">		
									<label class="control-label">Purchase requisition No.</label>
									<input type="text" class="form-control" id="against_indent_no" name="against_indent_no"  <?php echo $readonly; ?> value="<?php echo $row['against_indent_no'];?>" >
								</div>
									

							</div>
							
							<div class="form-group">

								
								<div class="col-md-2">
										<label class="control-label">Account Year</label>
										<select class="form-control" name="account_year" id="account_Year"  <?php echo $readonly; ?> >
											<option value=""> Select </option>
											<option value="1" <?php echo ($row['account_year'] == '1')?'selected="selected"':'';?> > 2017-2018 </option>
											<option value="2" <?php echo ($row['account_year'] == '2')?'selected="selected"':'';?> > 2018-2019 </option>
											<option value="3" <?php echo ($row['account_year'] == '3')?'selected="selected"':'';?> > 2019-2020 </option>
											<option value="4" <?php echo ($row['account_year'] == '4')?'selected="selected"':'';?> > 2020-2021 </option>
											<option value="5" <?php echo ($row['account_year'] == '5')?'selected="selected"':'';?> > 2021-2022 </option>
											<option value="6" <?php echo ($row['account_year'] == '6')?'selected="selected"':'';?> > 2022-2023 </option>
											<option value="7" <?php echo ($row['account_year'] == '7')?'selected="selected"':'';?> > 2023-2024 </option>
											<option value="8" <?php echo ($row['account_year'] == '8')?'selected="selected"':'';?> > 2024-2025 </option>
											<option value="9" <?php echo ($row['account_year'] == '9')?'selected="selected"':'';?> > 2025-2026 </option>
											<option value="10" <?php echo ($row['account_year'] == '10')?'selected="selected"':'';?> > 2026-2027 </option>									
										</select>	
									</div>
									
								<?php $_SESSION['company'] = $row['company']; ?>
								
                                <div class="col-sm-4">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company" id="companY" onchange="getbudgetname(this.value)"  <?php echo $readonly; ?> >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							</div>	
							<?php
								$budget_head_id = $row['budget_head'];
								$sql = "select * from sma_budget where id = '$budget_head_id' ";
		//echo $sql;	
								$q3  = mysqli_query($con, $sql);
								$r3  = mysqli_fetch_object($q3);
								$budget_head    = $r3->budget_category;
								$budget_name    = $r3->budget_name;
								
								//$sql = "select * from sma_budget_name where id = '$budget_name' ";
								//	$q3  = mysqli_query($con, $sql);
								//	$r3  = mysqli_fetch_object($q3);
								//	$budget_name    = $r3->name;
										
							?>
							<div class="form-group">
							
								<div class="col-md-4">
									<label class="control-label">Budget Name **</label>
									<span id="getbudgetname">
									<select class="form-control" name="budget_name" id="budget_Name" onchange="getbudget(this.value)"  <?php echo $readonly; ?> >
										<option value=""> Select </option>
											<?php $sql = "select distinct(b.name) as name, a.budget_name as id from sma_budget a, sma_budget_name b where a.budget_name = b.id order by name ";
											//$sql = "select a.id, b.category from sma_budget a, sma_budget_category b where a.budget_category = b.id order by category ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
									</span>
								</div>
								<?php
									
									$sql = "select * from sma_budget_category where id = '$budget_head' ";
									$q3  = mysqli_query($con, $sql);
									$r3  = mysqli_fetch_object($q3);
									$budget_head_name    = $r3->category;
									
									$budget_available 	= $row['budget_available'];
									$balance_budget		= $row['balance_budget'];
									$sql = "select * from sma_approval_details where approval_hdr_id = '$id' and vendor_selected = 'Y' ";
									$q3  = mysqli_query($con, $sql);
									$r3  = mysqli_fetch_object($q3);
									$balance_budget   = $budget_available - $r3->values;
									
								?>

									<input type="hidden" class="form-control" id="prev_values" name="prev_values" readonly value="<?php echo $values;?>" >
									<input type="hidden" class="form-control" id="budget_head_Id" name="budget_head_id" readonly value="<?php echo $budget_head_id;?>" >
											
									<span id="getbudgethead">
										
										<div class="col-md-3">
											<label class="control-label">Budget Head</label>
											<input type="text" class="form-control" id="budget_head_name" name="budget_head_name" readonly value="<?php echo $budget_head_name;?>" >
										</div>
										
										<span id="getavailbudget">
												<div class="col-md-2">
												<label class="control-label">Budget Available</label>
													<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="<?php echo $row['budget_available'];?>" >
												</div>

										</span>
										
									</span>
								
								<div class="col-md-3">
									<label class="control-label" style="font-size:13px;">Balance Budget After this Approval Memo</label>
									<input type="text" class="form-control" id="balance_budget" name="balance_budget" style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $balance_budget;?>" >
								</div>
								
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

							<div class="form-group">
								<div class="col-md-6">
									<label class="control-label">Additional Cost Description</label>
									<input type="text" class="form-control" id="additional_costs" name="additional_costs"  <?php echo $readonly; ?> value="<?php echo $row['additional_costs'];?>" >
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
						 
					
<!---------------------------------------------------------------------------------------------------------------------------------------------------------------->
						
						<div class="tab-pane <?php echo $active;?>" id="tab_2">
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Quotation Details</h4>
										<?php $data_mode = 'Add';?>
                                        <span class="pull-right">
                                            <a href="#"
                                               class="btn btn-primary" data-mode='Add'
                                               data-toggle="modal" data-target="#modalAddItem<?php echo $data_mode;?>">Add
                                            </a>
                                        </span>
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
												
												$selected = '';
												$vendor_selected = $row['vendor_selected'];
												if($vendor_selected=='Y'){
													$selected = 'Selected';
												}
												
											?>

                                            <tr>
                                 
<!--                                                <td>    
													<a href="#" data-toggle="modal" data-target="#modalEditItem" ><i class="fa fa-edit"></i></a>
                                                    &nbsp;&nbsp;
                                                    <a href="#" data-toggle="modal" data-target="#modalDeleteItem"><i class="fa fa-trash-alt"></i></a>
                                                </td>-->
                                            		<td width="20%" style="text-align:left"><?php echo $supplier_name;?></td>
													<td width="20%" style="text-align:left"><?php echo $row['quote_ref_no'];?></td>
													<td width="10%" style="text-align:left"><?php echo $selected;?></td>
													<td width="10%" style="text-align:right"><?php echo $row['values'];?></td>
													<td width="10%" style="text-align:right">
													<a href='#modalEditItem' data-id='<?php echo $approval_srno;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $approval_srno;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
														<?php include "edit_func.php"; ?>							
<!-- Modal Edit Item-->														
														<a href='#modalDeleteItem' id='delete-<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>' data-toggle='modal' data-id='<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>' data-target='#modalDeleteItem<?php echo $approval_hdr_id;?><?php echo $approval_srno;?>'><i class='fa fa-trash-alt'></i></a>
														</td>
<!-- Modal Delete Item-->								
														<?php include "del_func.php"?>							
<!-- Modal Delete Item-->
								               
<?php 
/* echo '<td width="10%" ><a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editUser(\'' . $approval_hdr_id . '\',\'' . $approval_srno . '\' )"></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm(\'Are you sure to delete data?\')?userAction(\'delete\',\''.$approval_hdr_id.'\',\''. $approval_srno . '\'):false;"></a></td>';
*/
?>
											</tr>
											    <?php }?>
                                            </tbody>
                                            <tfoot>
                                            <!--<tr>
                                                    <th style="text-align:left">Supplier Name</th>
													<th style="text-align:left">Quote Ref. No</th>
													<th style="text-align:right">Quantity</th>
													<th style="text-align:right">Values</th>
													<th style="text-align:left">Remarks</th>
													<th>Actions</th>
													
                                                </tr>-->
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
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click')" >Next</a>
								</div>
							</div>
				
					</div>
					
				
                    <div class="tab-pane" id="tab_3">
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'AP' AND reference_id = " . $ap_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
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
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
							
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Previous</a>
									
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
											
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
								if ($status != 'Submited' && $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."/delete.php?did=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php		
									$role			= $_SESSION['role'];
									if ( ($status == 'Submited' || $status == 'Completed') && ($role =='Project Manager' || $role == 'Project Incharge' || $role == 'CXO' || $role == 'CEO' || $role =='COO') ) {
										
										$userid   	= $_SESSION['usrid'];											
										$s1   = "SELECT count(*) as cnt from workflow_history where doc_id = '$ap_id' and doc_type = 'AP' and reviewed_by = '$userid' ";
										$res  = mysqli_query($con, $s1);
										echo mysqli_error($con);
										$r1 = mysqli_fetch_array($res);
										$cnt  = $r1['cnt'];
										//$create_by  = $r1['create_by'];
										if($cnt>0){
									?>	
								<!--		<input type="submit" class="btn btn-info"  name="submit1" value="Approve" >
										<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<input type="submit" class="btn btn-danger" name="submit2" value="Reject" >
								-->		
										<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									
									<?php
										
										}	
									} ?>
									<!--<button  onclick='$baseurl . "approval"' class="btn btn-default"> Cancel</a></button>-->
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php		
							
								if ($status != 'Submited' && $status != 'Completed'){
								
							?>	
									<button type="submit" class="btn btn-primary" form="form1" >Save Draft</button>
									<span>&nbsp;&nbsp;</span>
									<?php if($role=='Maker'){ ?>
										<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>
									<?php } 
									
									?>
									
									<span>&nbsp;&nbsp;</span>
							<!--		<input type="submit" class="btn btn-primary"  name="submit" value="Submit" >-->
									<?php if($role=='Checker' ){ ?>
										<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Submit </a>
									<?php } 
									
									}
									
									?>
							
								</div>
								
							</div>
							
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

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem<?php echo $data_mode;?>" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Quotation </h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="saveitem.php?sub=Save" method="POST">

<!--							<input type="text" id="data_mode" value=<?php echo $data_mode; ?> > -->

							<input type="hidden" name="approval_hdr_id" value=<?php echo $id; ?>>
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2-123" name="supplier_name">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['supplier_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-3 control-label">Quote Ref.No</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="quote_ref_no" placeholder=" QUOTE_REF_NO...">
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



<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
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
											$user_department = $_SESSION['user_department'];
											$company		 = $_SESSION['company'];
											
										?>
										
										<input type="hidden" name="ap_id" id="ap_idE" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and role in ( select id from sma_role where role = 'Checker' )  ORDER BY first_name ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
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
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
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
										
										<input type="hidden" name="ap_id" id="ap_idE" value="<?php echo $ap_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
										
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
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
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
	  
	  
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'<option value="PAN CARD">PAN Card</option><option value="AADHAAR CARD">AADHAAR Card</option></select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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

   $("#submitChecker").on("click", function(e){
        var sub = 'sub10';
		var mode		 	=  $("#modeC").val();
		
		var ap_id		 	=  $("#ap_idE").val();
//		var department 		=  $("#departmentE option:selected").val();
//var department 		=  $("#departmentE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#approvalAuthority').modal('hide');
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
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var ap_id		 	=  $("#ap_idE").val();
	    var status 			=  $("#statuS").val();
		
		var account_year	= $("#account_Year").val();
		var company			= $("#companY").val();
		var budget_head_id	= $("#budget_head_Id").val();
		var budget_name		= $("#budget_Name").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksA").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ap_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						account_year:account_year,
						company:company,
						budget_head_id:budget_head_id,
						budget_name:budget_name,
						status:status,
						statusap:mode,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
//		alert(sub + ' ' + mode);		 
		var ap_id		 	=  $("#ap_idR").val();
		
		var status 			=  $("#statuS").val();
		//var ap_id			=  $("#iD").val();
		
		var account_year	= $("#account_Year").val();
		var company			= $("#companY").val();
		var budget_head_id	= $("#budget_head_Id").val();
		var budget_name		= $("#budget_Name").val();
		
        var statusap		=  mode;
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+ap_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ ap_id:ap_id,
						mode:mode,
						account_year:account_year,
						company:company,
						budget_head_id:budget_head_id,
						budget_name:budget_name,
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

	function getbudget123(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}
	
	function getbudget(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
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
	
</script>

</body>
</html>
