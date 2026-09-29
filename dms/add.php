<?php
	include("../header.php");
	$modulePath = "dms/";

	date_default_timezone_set("Asia/Kolkata");
	 
	if($_GET['sub']=='Save'){
			$inward_no			= $_POST['inward_no'];
			$date_of_received	= date('Y-m-d h:i:s', strtotime($_POST['date_of_received']));
			$account_year		= $_POST['account_year'];
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			$doc_type			= $_POST['doc_type'];
			$mode_of_receipt	= $_POST['mode_of_receipt'];
			
			$send_to_user		= $_POST['send_to_user'];
			$status 			= 'Draft';

			$user   			= $_SESSION['user'];
			
  			$sql="insert into dms_inward (inward_no, date_of_received,  company_for, department_for, mode_of_receipt, doc_type, sent_by, send_to_user, status, remarks, draft_by, draft_dated ) Values('$inward_no', '$date_of_received', '$company_for', '$department_for', '$mode_of_receipt', '$doc_type', '$sent_by', '$send_to_user', '$status', '$remarks', '$user', now() )";
//echo $sql;
//exit();

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$userid   			= $_SESSION['usrid'];
			
			if(!empty($send_to_user) && $_POST['send']){
				$approval_status = 'Sent';
				$status 		 = 'Received';
				$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$send_to_user', changed_date = now(), status = '$status' 
						where inward_no = '$inward_no' ";
				$r2 = mysqli_query($con, $sql);
				
				$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date) 
								values('IN', '$inward_no', '$userid', now(), '$status', '$send_to_user', '$remarks', now())";
				$r2 = mysqli_query($con, $sql);
			}
		
		
			$sql = "update `dms_srno` set inward_no = '$inward_no' where inward_no < '$inward_no' ";
			$qry = mysqli_query($con, $sql);
			
									
//			echo "Inward Memo successful added";
			$baseurl.=$modulePath.'';
			//$baseurl.=$modulePath.'edit.php?id='.$id.'&active=active';
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
            Inward 
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Inward </a></li>
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
                        <h3 class="box-title">Create Inward </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="add.php?sub=Save" method="post">

							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								<?php
									
									$sql = "SELECT inward_no FROM `dms_srno` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
									
									/*$sql = "SELECT max(inward_no) as inward_no FROM `dms_inward` ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$inward_no = $r2['inward_no'] + 1;
								*/	
								?>
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Inward Number</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;font-size:24px; font-family: Arial, Helvetica, sans-serif;" value="<?php echo $inward_no;?>">
                                </div>
								
                                <div class="col-xs-3">
									<label for="prDate" class="control-label">Date</label>
                                    <!--<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>-->
                                        <input type="text" class="form-control" id="prDate" style="text-align:left;font-size:22px; font-family: Arial, Helvetica, sans-serif;" name="date_of_received" readonly placeholder="dd/mm/yyyy"
                                               value="<?php echo date('d-m-Y H:i:s');?>">
                                   <!-- </div>-->
								</div>	
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Mode or Receipt</label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt" required >
									<option value=""> Select </option>
									<option value="C"> Courier </option>
									<option value="H"> Hand Delivery </option>
									<option value="E"> Email </option>
									</select>	
                                </div>
								
                            
								<div class="col-md-3">	
								<label class="control-label">Send To User</label>
									<select class="form-control col-sm-2 doc_type select2" name="send_to_user" required >
										<option value="">Select</option>
													<?php
													$sql="SELECT * FROM sma_user ORDER BY username ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($doc_type == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
													<?php } ?>
									</select>
								</div>
							</div>
	
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Remarks</label>
									<input type="text" class="form-control" id="to_Supplier" name="remarks" placeholder="" value="<?php echo $row['subject'];?>" >
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-md-12" style="text-align:left;" >
									
										<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Cancel</a>
										
									
									<div style="text-align:right;">
										<input type="submit"  id="save" name="save" class="btn btn-success" value="Save" >
										<span>&nbsp;&nbsp;</span>
										<input type="submit"  id="send" name="send" class="btn btn-info" value="Send" >
									</div>
								</div>	
							</div>
														
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
		var account_year = document.getElementById("account_Year").value;
//alert(project);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub,project:project,account_year:account_year},function(result){
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
