<?php
	include("../header.php");
	
	date_default_timezone_set('Asia/Kolkata');
	
	$modulePath = "tender/"; 

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
	if($_GET['sub'] == 'delete'){
        $id = $_GET['id'];
		$sql="delete from sma_tender_header where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        $baseurl.=$modulePath;
			echo "<script>window.project.href='$baseurl';</script>";
	}
	
	if($_GET['sub']=='Save'){

			$tender_title		= $_POST['tender_title'];
			$company_id			= $_POST['company_id'];
			$location			= $_POST['location'];
			$delivery_address   = $_POST['delivery_address'];
			$department			= $_POST['department'];
			$created_date		= date('Y-m-d', strtotime($_POST['created_date']));
			$visible			= $_POST['visible'];
			$price_visible 		= $_POST['price_visible'];
			$deadline_date		= date('Y-m-d', strtotime($_POST['deadline_date']));
			$deadline_time		= $_POST['deadline_time'];
			$deadline_time		= "23:55:00";
			$deadline_date		.= ' ' .$deadline_time;
			$payment_within_days= $_POST['payment_within_days'];
			$retention			= $_POST['retention'];
			$remarks			= $_POST['remarks'];
			$background			= $_POST['background'];
			$scope_of_work		= $_POST['scope_of_work'];
			$trans_type			= $_POST['trans_type'];
			
			$draft_by			=$_SESSION['user'];
			$user    	= $_SESSION['user'];
			$userid   	= $_SESSION['usrid'];
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
	
			$status				= 'Draft';
			//$po_number = $comp_code.'/'.$dept_code.'/'.$yyyy.'/'.$po_last_number;
//echo $po_number. "<BR>";		'/'.$revno
  			$sql="insert into sma_tender_header ( tender_title, company_id, department, location, delivery_address, created_date, visible, price_visible, deadline_date, deadline_time, payment_within_days, retention, remarks, scope_of_work, background, trans_type, draft_by, draft_date, status ) VALUES( '$tender_title', '$company_id', '$department', '$location',
			'$delivery_address', '$created_date', '$visible', '$price_visible', '$deadline_date', '$deadline_time', 
			'$payment_within_days', '$retention',  '$remarks', '$scope_of_work', '$background', 
			'$trans_type', '$user', now(), '$status' )";
//echo $sql."<BR>";

			$query=mysqli_query($con, $sql);
			$tender_id = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
//Term & Conditions	Start
			$terms_conditions	= $_POST["terms_conditions"];
			for( $i = 0; $i < sizeof($terms_conditions); $i++ ) {
				
				$terms_cond 	= $terms_conditions[$i];
				
				if( !empty($terms_cond) ){
					$sql="INSERT INTO sma_tender_terms (tender_hdr_id, terms_conditions) 
					VALUES( '$tender_id', '$terms_cond')";
					mysqli_query($con, $sql);
				}

			}
//Term & Conditions	End	
			
			$userid   	= $_SESSION['usrid'];
		
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status) values( 'TN', '$tender_id', '$userid', now(), 'Draft' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
//exit();
			$baseurl.=$modulePath.'edit.php?id='.$tender_id.''; //&active=active
			echo "<script>window.location.href='$baseurl';</script>";
			exit();
			
	}
	
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Tender / RFP
            <small>Add</small>
		<!--<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender / RFP</a></li>
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
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" action="add.php?sub=Save" method="post">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
									
									<?php
									
										$sql="Select max(id) as id from sma_tender_header ";
										$query = mysqli_query($con, $sql);
										$r2 = mysqli_fetch_array($query);
										$srno = $r2['id'] + 1;
									?>
							<div class="form-group">
								<div class="col-md-1">
									<label for="project" class="control-label">Srno.</label>
									<input type="text" class="form-control" id="srno" name="srno" style="text-align:right;" readonly value="<?php echo $srno;?>" >
								</div>
								
								<div class="col-md-6">
									<label for="project" class="control-label">Tender Title.</label>
									<input type="text" class="form-control" id="tender_title" name="tender_title" style="text-align:left;" value="<?php echo $tender_title;?>" >
								</div>

								<div class="col-md-5">
									<label for="project" class="control-label">Company<span style="color:red;"> **</span></label>
									<select class="form-control select2123" name="company_id" id="company_id" 
									onchange="getlocation(this.value);  getcompanyterm(this.value);getsupplier(this.value);" required >
										<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							
							</div>
								
							<div class="form-group">			
								<div class="col-md-3">
									<label class="control-label">Department <span style="color:red;"> **</span></label>
											
									<select class="form-control" name="department" id="department" required >
										<option value=""> Select </option>
										<?php $sql = "select * from sma_department order by name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" > <?php echo $r2['name'];?></option>
										<?php } ?>
									</select>
								</div>
										
								<div class="col-md-3">
									<label class="control-label"> Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="created_date" name="created_date" placeholder="dd-mm-yyyy" readonly value="<?php echo date("d-m-Y");?>">
									</div>
								</div>
										
							</div>
							
								<div class="form-group">
								
										<div class="col-sm-2">
										<label for="deliveryLocation" class="control-label">Delivery Location**</label>
												<span id="getlocation">
													<select class="form-control" id="location" name="location" required >
														<option value="">Select</option>
													
													</select>
												</span>
										</div>
										
										<span id="getdelvaddr">
										<div class="col-md-6">
											<label class="control-label">Delivery Address</label>
											
												<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address"></textarea>
										
										</div>
										</span>
								</div>
								
								<div class="form-group">
									<div class="col-md-2">
										<label class="control-label">Tender Visibility <span style="color:red;"> **</span></label><BR>
										<input type="radio"	name="visible" id="visible" CHECKED value ="V"  >Private &nbsp;
										<input type="radio"	name="visible" id="visible"  value ="P"  >Public
										
									</div>
									
									<div class="col-md-3">
										<label class="control-label">Pricing Visibility <span style="color:red;"> **</span></label><BR>
										<input type="radio"	name="price_visible" id="price_visible"  value ="O"  >Open View &nbsp;
										<input type="radio"	name="price_visible" id="price_visible" value ="E" CHECKED >Employer Closed View	
										
									</div>
								<?php	
									$dy = date("d")+1;
									$tdy_date = $dy . '-'. date("m-Y");
								?>	
									<div class="col-md-3">
										<label class="control-label"> Deadline Date</label>
										<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
										<input type="text" class="form-control" id="deadline_date" name="deadline_date" placeholder="dd-mm-yyyy" value="<?= $tdy_date;?>">
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="bootstrap-timepicker">
											<label>Time </label>
											<div class="input-group">
												<input type="time" class="form-control timepicker123" id="deadline_time" name="deadline_time"  
												value="23:55:00" >

												<!--<div class="input-group-addon">
												  <i class="fa fa-clock-o"></i>
												</div>-->
											</div>
										</div>
									</div>
									 
								</div>		
								
								<div class="form-group">
									<div class="col-md-3">
										<label class="control-label"> Payment Terms (in Days)</label>
										<input type="text" class="form-control" id="payment_within_days" name="payment_within_days"  style="text-align:right;" placeholder="" value="<?php echo $payment_within_days;?>" >
									</div>
									
									<div class="col-md-2">
										<label class="control-label"> Retention %</label>
										<input type="text" class="form-control" id="retention" name="retention" style="text-align:right;" placeholder="" value="<?php echo $retention;?>" >
									</div>
								<?php
									$selected = '';
									$sql = "select * from sma_workflow_type where doc_type = 'TN' and status = 'Y'  ";
									mysqli_query($con, $sql);
									$rowaffect 	= mysqli_affected_rows($con);
									if($rowaffect==1){
										$selected = "SELECTED";
									}	
								
								?>
									<div class="col-sm-4">
										<label for="company_id" class="control-label ">Workflow Type *</label>
										<select class="form-control select3" name="trans_type" id="trans_type" required >
												<option value=""> Select </option>
													<?php $sql = "select * from sma_workflow_type where doc_type = 'TN' and status = 'Y'  ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" <?= $selected; ?> >  <?php echo $r2['workflow_type'];?></option>
													<?php } ?>
										</select>		
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-12">
										<h3> Internal Notes</h3>
										<textarea rows="5" class="form-control" id="reasona" name="background" ></textarea>
									</div>
								</div>	

								<div class="form-group">
									<div class="col-md-12">
										<h3> Vendor Scope of Work / Specification</h3>
										<textarea rows="5" class="form-control" id="reasonb" name="scope_of_work" ></textarea>
									</div>
								</div>


<!-- Terms Confitions -->


			<div class="panel panel-default">
				
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTM"><b>  Terms & Conditions</b> <span class="caret"></span> </a></h4>
				</div>
							
                <div id="stepTM" class="panel-collapse collapse in">
					<div class="panel-body">
							<div class="box-header">	
                                       
							<div class="table-responsive">  
                                <table class="table table-bordered" id="dynamic_terms">  
                                    <tr> 
										<td width="80%">
											 <textarea class="form-control terms_conditions" name="terms_conditions[]" rows="2" placeholder="Enter Terms Conditions..."></textarea>
										</td>
										
                                        <td width="10%">
									
											<button type="button" name="add" id="addTERMS" class="btn btn-success">Add More</button>
										
										</td>
                                    </tr>  
                                </table>  
                           
                            </div>
						</div>
				</div>
			</div>
		</div>	
						
<!--Terms Condition -->


								<div class="box-footer">
									
									<div class="col-sm-6 text-right">
										<span>&nbsp;&nbsp;</span>
								
									</div>
									<div class="col-sm-6 text-right">
										
										<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
										
										<span>&nbsp;&nbsp;</span>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
										
									</div>

								</div>	
								
								</fieldset>
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


<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

 For Terms Start -->
<script>  
 $(document).ready(function(){
      var ii=1;  
      $('#addTERMS').click(function(){  
			//var opt =  document.getElementById('opt').value;
           ii++;  
           $('#dynamic_terms').append('<tr id="row'+ii+'"><td width="80%"><textarea class="form-control docdesc" name="terms_conditions[]" rows="2" placeholder="Enter Terms Conditions..."></textarea></td><td><button type="button" name="remove" id="'+ii+'" class="btn btn-danger btn_remove">X</button></td></tr>');
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      
 });  
 </script>				
<!-- For Terms End -->
		
		
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

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>

<script>
   
    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reasona');
        CKEDITOR.replace('reasonb');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
		
    });

	function getlocation(id){
		
        var sub    = 'sub5';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getdelvaddr(id){
        var sub    = 'sub6';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getdelvaddr').html(result);
		});
	}


</script>

</body>
</html>
