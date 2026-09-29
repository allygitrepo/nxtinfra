<?php
	include("../header.php");
	$modulePath = "approval_adjustment/";

	if($_GET['sub']=='Save'){
			$id					= $_POST['srno'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$account_year		= $_POST['account_year'];
			$company			= $_POST['company'];
			$department			= $_POST['department'];
			$location			= $_POST['location'];
			$trans_type			= $_POST['trans_type'];

			$budget_head		= $_POST['budget_head_id'];
			$against_indent_no	= $_POST['against_indent_no'];
			$tender_no			= $_POST['tender_no'];
			$budget_available	= $_POST['budget_available'];
			$subject			= '';
			$background			= '';
			$scope_of_work		= '';
			$deviations_from_sop= '';
			$important_terms_conditions	= '';
			$additional_costs	= '';

			$cost				= $_POST['cost'];
			$overhead_exp		= $_POST['overhead_exp'];
			
			$status 			= 'Draft';

			$user    			= $_SESSION['user'];
			$userid   			= $_SESSION['usrid'];
		
			$doctype			= 'AP-ADJ';
			$doc_ref_ap_no		= $_POST['apmemo_no'];
			
  			$sql="insert into sma_approval_memo (id, doctype, doc_ref_ap_no, dated, account_year, company, department, budget_head, against_indent_no, budget_available, subject, background, scope_of_work, deviations_from_sop,important_terms_conditions, additional_costs,  cost, status, draft_by, draft_dated , overhead_exp, trans_type, location, tender_no ) 
			Values('$id','$doctype', '$doc_ref_ap_no', '$dated', '$account_year', '$company', '$department', '$budget_head', '$against_indent_no', '$budget_available', '$subject', '$background', '$scope_of_work', '$deviations_from_sop', '$important_terms_conditions', '$additional_costs', '$cost', '$status', '$user', now(), '$overhead_exp', '$trans_type', '$location', '$tender_no' )";
//echo $sql. "<BR>";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			//$ap_id	= mysqli_insert_id($con);
			if(!empty($error)){echo $error; exit();}

			$sql = " UPDATE sma_approval_memo set doc_ref_ap_no = '$doc_ref_ap_no' where id = '$doc_ref_ap_no' ";
			mysqli_query($con, $sql);
//echo $sql. "<BR>";

			$sql = " INSERT INTO sma_approval_items ( reversal_item_no, approval_hdr_id, company_id, supplier_id, product_id, product_name, product_desc, product_category, budget_id, budget_name, budget_head, quantity, uom, unit_rate, gst, amount , ori_qty )
				SELECT id, '$id', company_id, supplier_id, product_id, product_name, product_desc, product_category, budget_id, budget_name, budget_head, quantity, uom, unit_rate, gst, amount, quantity  FROM `sma_approval_items` where approval_hdr_id = '$doc_ref_ap_no' ";
			mysqli_query($con, $sql);	
			
			$sql = "INSERT INTO sma_approval_details (approval_hdr_id, supplier_name, quote_Ref_no, vendor_selected, `values`, remarks )
					SELECT '$id', supplier_name, quote_Ref_no, vendor_selected, `values`, remarks FROM `sma_approval_details` where approval_hdr_id = '$doc_ref_ap_no' and vendor_selected = 'Y' ";
			mysqli_query($con, $sql);
			
			$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status ) 
						values('DJ', '$id', '$userid', now(), 'Draft' )";

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
	
	$help_code = $modulePath.'index.php';
	include "../help_code.php";
?>

	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Approval Memo Reversal
            <small>Add</small>
			<small>
				<a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo $baseurl . 'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Approval Memo and approval_number = 869</a></li>
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
                        <h3 class="box-title">Create Approval Memo Reversal</h3>
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
                                        <input type="text" class="form-control" id="prDate" name="dated" placeholder="dd/mm/yyyy" readonly required
                                               value="<?php echo date('d-m-Y');?>">
                                    </div>
								</div>	
                                
								<div class="col-md-3">
									<label class="control-label">Approval Memo Type : <span data-toggle="tooltip" title="" class="badge bg-light-blue"></span></label><BR>
									<input type="RADIO" CHECKED id="overhead_expN" name="overhead_exp" value="N" > <b>For PO</b> &nbsp;
									<input type="RADIO" id="overhead_expY" name="overhead_exp" value="Y" ><b> For Operating Expense </b>
								</div>
								
                                <div class="col-sm-4">
									<label for="company" class="control-label">Company <span style="color:red;">**</span></label>
                                	<select class="form-control" name="company" id="companY" onchange="getAPmemo(this.value);" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
							</div>
							
							<div class="form-group">
							
								<div class="col-sm-4">
									<label for="company" class="control-label">AP Memo <span style="color:red;">**</span></label>
                                	
									<span id="getAPmemo">
									
									</span>
								</div>
								
								<span id="viewapmemo">
								
								</span>
								
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

	function getAPmemo(id){
		
        var sub    = 'sub36';
//alert(sub);

		if (document.getElementById('overhead_expN').checked) {
		    overhead_exp = document.getElementById('overhead_expN').value;
		}
		if (document.getElementById('overhead_expY').checked) {
		    overhead_exp = document.getElementById('overhead_expY').value;
		}
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,overhead_exp:overhead_exp,sub36:sub},function(result){
		      $('#getAPmemo').html(result);
		});

	}


	function viewapmemo(id){
		var sub    = 'sub37';
//alert(sub);

		if (document.getElementById('overhead_expN').checked) {
		    overhead_exp = document.getElementById('overhead_expN').value;
		}
		if (document.getElementById('overhead_expY').checked) {
		    overhead_exp = document.getElementById('overhead_expY').value;
		}
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,overhead_exp:overhead_exp,sub37:sub},function(result){
		      $('#viewapmemo').html(result);
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
