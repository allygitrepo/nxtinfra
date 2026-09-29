<?php
include("../header.php");
$modulePath = "dms";
$_SESSION['reset'] = '1';

?>


<link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css"?>">

<?php

//$quote_ref_no			= $_POST['quote_ref_no'];
//echo $quote_ref_no;
//exit();

//	echo $_POST['status']. ' ' . $_POST['edit']. ' <> ' . $_POST['submit2']. ' <>  ' .$_POST['submit1']. ' <>  ' . $_POST['submit'];
//	exit();

	if($_POST['edit']){
		include "saveitem.php";
	}

	
	if($_GET['sub']=='Save'){
			$inward_no			= $_POST['inward_no'];
			$date_of_received	= date('Y-m-d', strtotime($_POST['date_of_received']));
			$company_for		= $_POST['company_for'];
			$department_for		= $_POST['department_for'];
			$remarks			= $_POST['remarks'];
			$sent_by			= $_POST['sent_by'];
			$send_to_user		= $_POST['send_to_user'];
			$doc_type			= $_POST['doc_type'];
			$mode_of_receipt	= $_POST['mode_of_receipt'];
				
  			$sql="update dms_inward set inward_no	= '$inward_no',
						date_of_received			= '$date_of_received',
						company_for			= '$company_for',
						department_for		= '$department_for',
						remarks				= '$remarks',
						sent_by				= '$sent_by',
						doc_type			= '$doc_type',
						mode_of_receipt		= '$mode_of_receipt',
						send_to_user		= '$send_to_user'
				where inward_no = '$inward_no' ";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			if(!empty($send_to_user) && $_POST['send'] ){
				$approval_status = 'Sent';
				$status 		 = 'Received';
				$sql = "update `dms_inward` set approval_status = '$approval_status', changed_by = '$send_to_user', changed_date = now(), status = '$status' 
						where inward_no = '$inward_no' ";
				$r2 = mysqli_query($con, $sql);
				
				$sql = "update workflow_history set reviewed_by = '$send_to_user', remarks = '$remarks' where doc_type ='IN', doc_id = '$inward_no' ";
				$r2 = mysqli_query($con, $sql);
			}
			
//echo $sql;
//exit();
			
		//	echo "Inward successful added";
			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";

	}

	$inward_no 		= $_GET['inward_no'];
	$inward_no		= $_GET['inward_no']; 
	$sql="select * from dms_inward where inward_no ='$inward_no'";
	$query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);	
	
	$status 		= $row['status'];
	$inward_no		= $row['inward_no']; 
	$send_to_user	= $row['send_to_user']; 
	
	$readonly = '';
	
	if ( ($status == 'Submited' && $user!='Admin' ) || $status == 'Completed' || !empty($send_to_user) ){
		$readonly = 'READONLY';
	}
	
	if ( $user=='Admin' ){
		$readonly = '';
	}
	
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Inward
            <small>Edit</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Inward</a></li>
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
						
						<div class="form-group">
							<input type="hidden" name="id" id = "iD" value="<?php echo $row['inward_no'];?>" >
							<input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
						</div>
						
							<div class="form-group">
								<input type="hidden" name="inward_no" value="<?php echo $row['inward_no'];?>" >
								
								<div class="col-xs-2">
									<label for="prDate" class="control-label">Inward Number</label>
                                    <input type="text" class="form-control" id="inward_no" name="inward_no" readonly style="text-align:right;" value="<?php echo $inward_no;?>">
                                
								</div>
								<?php 
									$date_of_received  = date('d-m-Y', strtotime($row['date_of_received']));
									$date_of_received1 = date('d-m-Y H:i:s', strtotime($row['date_of_received']));
									if($date_of_received=='01-01-1970'){
										$date_of_received1='';
									}
								?>
                                <div class="col-xs-2">
									<label for="prDate" class="control-label">Date</label>
                                    
                                    <input type="text" class="form-control" id="prDate" name="date_of_received" placeholder="dd/mm/yyyy"
                                               value="<?php echo $date_of_received1;?>" <?php echo $readonly; ?> >
										
								</div>	
								
								<div class="col-sm-2">
									<label for="department_for" class="control-label">Mode or Receipt</label>
									<select class="form-control" name="mode_of_receipt" id="mode_of_receipt" required <?php echo $readonly; ?> >
									<option value=""> Select </option>
									<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
									<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
									<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
									</select>	
                                </div>
								
								<?php 
										$send_to_user = $row['send_to_user'];
										//$send_to_user_a = explode(",", $send_to_user); 
								?>
								<div class="col-md-3">	
								<label class="control-label">Send To User</label> <?php // multiple="multiple"  ?>
									<select class="form-control col-sm-2 select2" name="send_to_user" id="send_to_USER" <?php echo $readonly; ?> >
										<option value="0">Select</option>
													<?php
													$sql="SELECT * FROM sma_user ORDER BY username ASC";
													$rs = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($rw = mysqli_fetch_array($rs)){
														
														//in_array($rw['id'], $send_to_user)
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to_user)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
													<?php } ?>	
													
									</select>
								</div>
							</div>
	
							<div class="form-group">
								<div class="col-md-12">
									<label class="control-label">Remarks</label>
									<input type="text" class="form-control" id="to_Supplier" name="remarks" <?php echo $readonly; ?> value="<?php echo $row['remarks'];?>" >
								</div>
							
							</div>
							
							
							<?php
							
								$_SESSION['inward_no'] 	= $inward_no;
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
									?>
									<!--<button  onclick='$baseurl . "approval"' class="btn btn-default"> Cancel</a></button>-->
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a>
									<span>&nbsp;&nbsp;</span>
									
									<input type="submit"  id="Accept" name="Accept" class="btn btn-primary" value="Accept" >
							
									<input type="submit"  id="save" name="save" class="btn btn-success" value="Save" >
										<span>&nbsp;&nbsp;</span>	
									<input type="submit"  id="send" name="send" class="btn btn-info" value="Send" >
						
									<span>&nbsp;&nbsp;</span>
									
								</div>
								
								
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


<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->

	
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

<!-- Select2 -->
<script src="http://localhost/hc_template/plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

</body>
</html>
