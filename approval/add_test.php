<?php
	include("../header-old.php");
	$modulePath = "approval/";

	if($_GET['sub']=='Save'){
			$id					= $_POST['srno'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$account_year		= $_POST['account_year'];
			$company			= $_POST['company'];
			//$project			= $_POST['project'];
			$department			= $_POST['department'];
//			$aop_provision		= $_POST['aop_provision'];
			$budget_head		= $_POST['budget_head_id'];
			$against_indent_no	= $_POST['against_indent_no'];
			$budget_available	= $_POST['budget_available'];
			$subject			= $_POST['subject'];
			$background			= mysql_real_escape_string($_POST['background']);
			$scope_of_work		= mysql_real_escape_string($_POST['scope_of_work']);
			$deviations_from_sop= mysql_real_escape_string($_POST['deviations_from_sop']);
			$important_terms_conditions	= mysql_real_escape_string($_POST['important_terms_conditions']);
			$additional_costs	= $_POST['additional_costs'];
//			$quote_ref_no		= $_POST['quote_ref_no'];
			$cost				= $_POST['cost'];
			$overhead_exp		= $_POST['overhead_exp'];
			
			$status 			= 'Draft';

			$user    = $_SESSION['user'];
			$userid   	= $_SESSION['usrid'];
		
  			$sql="insert into sma_approval_memo (id, dated, account_year, company, department, budget_head, against_indent_no, budget_available, subject, background, scope_of_work, deviations_from_sop,important_terms_conditions, additional_costs,  cost, status, draft_by, draft_dated , overhead_exp ) 
			Values('$id', '$dated', '$account_year', '$company', '$department', '$budget_head', '$against_indent_no', '$budget_available', '$subject', '$background', '$scope_of_work', '$deviations_from_sop', '$important_terms_conditions', '$additional_costs', '$cost', '$status', '$user', now(), '$overhead_exp' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			//$ap_id	= mysqli_insert_id($con);
			if(!empty($error)){echo $error; exit();}

			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status ) 
						values('AP', '$id', '$userid', now(), 'Draft' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			
//			echo "Approval Memo successful added";
			//$baseurl.=$modulePath.'';
			$baseurl.=$modulePath.'edit.php?id='.$id.'&active=active';
//echo 	$baseurl;
//exit();		
			
			echo "<script>window.location.href='$baseurl';</script>";
			
			
	}
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Approval Memo
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl . 'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Approval Memo</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Approval Memo</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="add.php?sub=Save" method="post">

							<div class="form-group">
								<input type="hidden" name="id" value="<?php echo $row['id'];?>" >
								<?php
									$sql = "SELECT max(id) as srno FROM `sma_approval_memo` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$srno = $r2['srno'] + 1;
								?>
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Serial Number</label>
                                    <input type="text" class="form-control" id="srno" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>">
                                
								</div>
								
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" readonly
                                               value="<?php echo date('d-m-Y');?>">
                                    </div>
								</div>	
                               
								<div class="col-sm-2">
									<label for="department" class="control-label">Department</label>
									<select class="form-control" name="department" id="department" required >
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
									<input type="text" class="form-control" id="against_indent_no" name="against_indent_no" placeholder="" value="<?php echo $row['against_indent_no'];?>" >
								</div>
							
								<div class="col-md-4">
									<label class="control-label">Overhead Expense <span data-toggle="tooltip" title="For Operating expenses PO & GRN not required" class="badge bg-light-blue">!</span></label><BR>
									<input type="checkbox" id="overhead_exp" name="overhead_exp" value="Y" >
								</div>
								
							</div>
							
								<?php
										/* $yyear =  date("Y");
										if ($yyear=='2018'){
											$account_year = '2';
										}
										else if ($yyear=='2019'){
											$account_year = '3';
										} */
								?>
								
							<div class="form-group ">
							
                                <div class="col-sm-4">
									<label for="company" class="control-label">Company <span style="color:red;">**</span></label>
                                	<select class="form-control" name="company" id="companY" onchange="getproject(this.value)" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								
								<span id="getproject">		
								<div class="col-md-3">
									<label class="control-label">Budget Name<span style="color:red;"> **</span></label>
								
									<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "select distinct(b.name), a.budget_name as id from sma_budget a, sma_budget_name b where a.budget_name = b.id order by name ";
											//$sql = "select a.id, b.category from sma_budget a, sma_budget_category b where a.budget_category = b.id order by category ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
								</div>
								</span>
								
								<span id="getbudgethead">		
										
									<div class="col-md-3">
										<label class="control-label">Budget Head</label>
									
									</div>
								
								</span>
							</div>
							
							<div class="form-group ">
								
								<span id="getavailbudget">
									<div class="col-md-2">
										<label class="control-label">Total Budget</label>
										<input type="text" class="form-control"  readonly style="text-align:right;" value="" >
									</div>
									
									<div class="col-md-2">
										<label class="control-label">Blocked Budget</label>
										<input type="text" class="form-control"  readonly style="text-align:right;" value="" >
									</div>
									
									<div class="col-md-2">
										<label class="control-label">Used Budget</label>
										<input type="text" class="form-control"  readonly style="text-align:right;" value="" >
									</div>
									
									<div class="col-md-2">
										<label class="control-label">Budget Available</label>
										<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="" >
									</div>
									
								</span>	
								
								<!--<div class="col-md-3">
									<label class="control-label"  style="font-size:13px;">Balance Budget After this Approval Memo</label>
									<input type="text" class="form-control" id="balance_budget" name="balance_budget" placeholder="" value="<?php echo $row['balance_budget'];?>" >
								</div>-->
						
							</div>
						
	
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Subject</label>
									<input type="text" class="form-control" id="to_Supplier" name="subject" placeholder="" value="<?php echo $row['subject'];?>" >
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="background"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Scope of Work</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason1" name="scope_of_work"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>

							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Deviations from SOP</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason2" name="deviations_from_sop"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Term & Conditions</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason3" name="important_terms_conditions"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-10">
									<label class="control-label">Additional Cost Description</label>
									<input type="text" class="form-control" id="additional_costs" name="additional_costs" placeholder="" value="<?php echo $row['additional_costs'];?>" >
								</div>
							</div>
														
                        </form>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-sm-6">
<!--                        <button type="submit" class="btn btn-danger">Delete</button>-->
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Cancel</a>
                        <span>&nbsp;&nbsp;</span>
                        <button type="submit" class="btn btn-primary" form="form1" >Next </button>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->

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

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<script>
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
</script>



</body>
</html>
