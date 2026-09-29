<?php
	include("../header.php");
	$modulePath = "income/";
	
	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php

	if(isset($_POST['Save'])){
			
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$company_id				= $_POST['company_id'];
			$trans_type				= $_POST['trans_type'];
			$income_account_name	= $_POST['income_account_name'];
			$party_id				= $_POST['party_id'];
			$income_background		= $_POST['income_background'];
			$invoice_no 			= $_POST['invoice_no'];
			$income_jv_flag			= $_POST['income_jv_flag'];
			$sales_date			= date('Y-m-d', strtotime($_POST['sales_date']));
			$received_date			= date('Y-m-d', strtotime($_POST['received_date']));
			if($income_jv_flag=='R'){
				$sales_date ='';	
			}
			if($income_jv_flag=='S'){
				$received_date ='';	
			}
			
			$sale_to_flag			= $_POST['sale_to_flag'];

			$tally_narration = "";
			$user   	= $_SESSION['user'];
			$userid     = $_SESSION['usrid'];
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
			$status = 'Draft';
  			$sql="INSERT INTO sma_income_hdr ( dated, company_id, trans_type, income_account_name, party_id, status , draft_by, draft_date,tally_narration, income_background, received_date, invoice_no, sales_date, income_jv_flag, sale_to_flag)
			Values( '$dated', '$company_id', '$trans_type', '$income_account_name', '$party_id', '$status', '$user', now(),  '$tally_narration', '$income_background', '$received_date', '$invoice_no', '$sales_date', '$income_jv_flag', '$sale_to_flag' )";
//echo $sql."<BR>";			

			$query=mysqli_query($con, $sql);
			$srno = mysqli_insert_id($con);
			$error= mysqli_error($con);

			if(!empty($error)){
				echo "<script>alert('".$error."')</script>";
				$baseurl.=$modulePath.'edit.php?id='.$srno;//.'&active=active'
				echo "<script>window.location.href='$baseurl';</script>";	
				exit();	
			}
			
			$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'IN', '$srno', '$userid', now(), '$status' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$baseurl.=$modulePath.'edit.php?id='.$srno;//.'&active=active'

			echo "<script>window.location.href='$baseurl';</script>";
		
		}

?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sales/Receipt Entry
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Sales/Receipt</a></li>
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
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form class="form-horizontal" action="add.php?sub=add" method="post" enctype="multipart/form-data">
							<?php
							$sql  = " SELECT max(id) as srno from sma_income_hdr ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$srno = $r1['srno']+1;
							?>
						
					
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>" >
							</div>
							
							<div class="col-md-2">	
								
								<label class="control-label">Supplier</label>&nbsp;
								<input type="radio" id="sale_to_sup_flag" name="sale_to_flag" checked value="S" onchange="getPartyname(this.value);" >
							
								<label class="control-label">&nbsp;Employee</label>&nbsp;
								<input type="radio" id="sale_to_emp_flag" name="sale_to_flag" value="U" onchange="getPartyname(this.value);" >
							</div>
							
							<div class="col-md-2">	
								<label class="control-label">&nbsp;&nbsp;Receipt</label>&nbsp;
								<input type="radio" id="income_rec_jv_flag" name="income_jv_flag" checked value="R" onchange="getIncName(this.value);" >
							
								<label class="control-label">&nbsp;Sales</label>&nbsp;
								<input type="radio" id="income_sal_jv_flag" name="income_jv_flag" value="S" onchange="getIncName(this.value);" >
							</div>	
							
							<div class="col-md-4">
								<label for="project" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" name="company_id" id="company_ID" onchange="getbankname(this.value);getworkflowtype(this.value);" required >
								<option value=""> Select</option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>
							</div>
							
							
							<div class="col-md-2">
								<label class="control-label">Date</label>
							<span id ="getDate">	
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" value="<?php echo date('d-m-Y'); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</span>	
							</div>	
							
						</div>
						
						<div class="form-group">
							<span id="receiptA">
							<div class="col-md-5">
								<label class="control-label">Bank Name<span style="color:red;"> **</span></label>
								<span id = "getbankname">
									<select class="form-control" id="income_account_name" name="income_account_name"  >
										<option value="">Select</option>	
									
									</select>
								</span>		
							</div>
							</span>
						
							<div class="col-md-5">
								<label class="control-label">Party Name<span style="color:red;"> **</span></label>
							<span id = "getPartyname">	
									<select class="form-control" id="party_id" name="party_id" required >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM sma_party_mst where 1 ORDER BY party_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['party_name'] ?></option>
										<?php } ?>
									</select>
							</span>	
							
							</div>
							
						</div>
						
						<div class="form-group">
						
							<div class="col-sm-5">
								<label for="company_id" class="control-label ">Workflow Type *</label>
							<span id="getworkflowtype">		
								<select class="form-control select3" name="trans_type" id="trans_type" required >
								<option value=""> Select </option>
								<?php $sql = "select * from sma_workflow_type where doc_type = 'IN' and status = 'Y' ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['workflow_type'];?></option>
								<?php } ?>
								</select>
							</span>										
							</div>
							
							<span id="receiptB">
							<div class="col-md-2">
								<label class="control-label">Date of Received</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="received_date" name="received_date" value="<?php echo date('d-m-Y'); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	
							</span>
							
							<span id="salesA" >		
								<div class="col-md-2">
									<label class="control-label">Invoice No</label>
									<input type="text" class="form-control" id="invoice_no" name="invoice_no" value="" >
								</div>	
								
								<div class="col-md-2">
									<label class="control-label">Sales Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="sales_date" name="sales_date" value="" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>	
							</span>	
							
						</div>
							
					<span id="Background">		
						<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason" name="income_background"
                                                  placeholder="Enter text ..."  <?php echo $readonly; ?> ></textarea>
                                    </div>
									
									
								</div>
						</div>
					</span>		
						
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								
							<span id="hidediv">							
								<input class="btn btn-primary" type="submit" onclick="getvalidate()"; value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</span>
							
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
 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  -->
<script>

	function getsupplier(id){
		
        var sub    				= 'sub14';
		//var company_id		 	=  $("#company_id").val();
		var company_id		 	= id;

		var strURL = "si_func.php";
//alert(sub + ' ' + company_id);		
		$.post(strURL,{company_id:company_id,sub14:sub},function(result){
		      $('#getsupplier').html(result);
			  //alert('result');
		});

	}
	
	
function getbankname(id){
    var sub = 'sub1';
//alert(id);
	
	var company_id = document.getElementById('company_ID').value;
	var st_flag = '';

//alert(id + ' >><< '+ st_flag +' <<>> ' +company_id);			
	var strURL = "app_func.php";
	$.post(strURL,{ sub1:sub,company_id:company_id},function(result){
			  $('#getbankname').html(result);
		});
}


function getDate(id){
    var sub = 'sub4';

	mm = document.getElementById('mm').value;
	yyyy = document.getElementById('yyyy').value;
	var mmyyyy = mm + '-' + yyyy;
//alert(mmyyyy);	
	var strURL = "app_func.php";
	$.post(strURL,{ sub4:sub,mmyyyy:mmyyyy},function(result){
			  $('#getDate').html(result);
		});
}




	function getPartyname(id){
		
		var sub = 'sub6';
		if (document.getElementById('sale_to_sup_flag').checked) {
			st_flag = document.getElementById('sale_to_sup_flag').value;
		}
		else if (document.getElementById('sale_to_emp_flag').checked) {
		    st_flag = document.getElementById('sale_to_emp_flag').value;
		}
		
		var strURL = "app_func.php";
		$.post(strURL,{ sub6:sub,st_flag:st_flag},function(result){
			  $('#getPartyname').html(result);
		});
		
	}
	
	function getIncName(id){
		
		var sub = 'sub1';
		if (document.getElementById('income_sal_jv_flag').checked) {
			st_flag = document.getElementById('income_sal_jv_flag').value;
		}
		else if (document.getElementById('income_rec_jv_flag').checked) {
		    st_flag = document.getElementById('income_rec_jv_flag').value;
		}
		
		if(st_flag=='S'){
			$('#salesA').show();
			$('#salesB').show();
			$('#Background').show();
			$('#receiptA').hide();
			$('#receiptB').hide();
		}
		if(st_flag=='R'){
			$('#receiptA').show();
			$('#receiptB').show();
			$('#salesA').hide();
			$('#salesB').hide();
			$('#Background').hide();
			
			
		}
		
	}

    $(window).load(function(){
        $('#salesA').hide();
		$('#salesB').hide();
		$('#Background').hide();
    });
	
</script>

</body>
</html>
