<?php
	include("../header.php");
	$modulePath = "approval/";

	if($_GET['sub']=='Save'){
			$id					= $_POST['srno'];
			$dated				= date('Y-m-d', strtotime($_POST['dated']));
			$account_year		= $_POST['account_year'];
			$company			= $_POST['company'];
			//$project			= $_POST['project'];
			$department			= $_POST['department'];
			$location			= $_POST['location'];
			$remarks			= $_POST['remarks'];
			$trans_type			= '';
//			$aop_provision		= $_POST['aop_provision'];
			$budget_head		= $_POST['budget_head_id'];
			$against_indent_no	= $_POST['against_indent_no'];
			//$tender_no			= $_POST['tender_no'];
			$tender_no			= '';
			//$advance_flag		= $_POST['advance_flag'];
			$advance_flag		= '';
			$budget_available	= $_POST['budget_available'];
			$subject			= trim(mysqli_real_escape_string($con, stripslashes($_POST['subject'])));
			$background			= trim(mysqli_real_escape_string($con, stripslashes($_POST['background'])));
			$scope_of_work		= trim(mysqli_real_escape_string($con, stripslashes($_POST['scope_of_work'])));
			$deviations_from_sop= trim(mysqli_real_escape_string($con, stripslashes($_POST['deviations_from_sop'])));
			$important_terms_conditions	= $_POST['important_terms_conditions'];
			$additional_costs	= $_POST['additional_costs'];
//			$quote_ref_no		= $_POST['quote_ref_no'];
			$cost				= $_POST['cost'];
			$overhead_exp		= $_POST['overhead_exp'];
			
			$status 			= 'Draft';

			$user    	= $_SESSION['user'];
			$userid   	= $_SESSION['usrid'];
		
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			 
			$account_year = $_SESSION['short_fy_code'];
			
			$sql = " SELECT * FROM `sma_purchase_req` where 1 and id = '$against_indent_no' " ;
            $qry = mysqli_query($con, $sql);
            $r2	 = mysqli_fetch_array($qry);
    	//	$subject            = $r2['subject'];
    	//	$background         = $r2['background_section'];
    	//	$scope_of_work      = $r2['scope_of_work'];
    		$department         = $r2['department_id'];
    			
    		$sql = "SELECT * FROM company where comp_id = '$company' ";
        	$q2  = mysqli_query($con, $sql);
        	$r2  = mysqli_fetch_array($q2);
        	$comp_code 		= $r2['comp_code'];
        	$prefix 		= $r2['prefix'];
        	$ion_last_number = $r2['ion_last_number']+1;
        			
        	$sql="Select * from sma_financial_year where status = 'Y' ";
        	$q2 = mysqli_query($con, $sql);
        	$r2 = mysqli_fetch_array($q2);	
        	$yyyy 		= $r2['finyear_prefix'];
        			
        			//Nxt-Infra/SPV/CGRG/PO/2025-26/0XXX
        	$ap_number = $prefix.$comp_code.'/'.'ION'.'/'.$yyyy.'/'.$ion_last_number;
        			
        	$sql = " UPDATE company set  ion_last_number = '$ion_last_number' where comp_id = '$company' ";
        	echo mysqli_error($con);
        	$q2  = mysqli_query($con, $sql);

  			$sql="insert into sma_approval_memo (id, dated, ap_number, account_year, company, department, budget_head, against_indent_no, budget_available, subject, background, scope_of_work, deviations_from_sop,important_terms_conditions, additional_costs,  cost, status, draft_by, draft_dated , overhead_exp, trans_type, location, 
			tender_no, advance_flag , remarks) 
			Values('$id', '$dated', '$ap_number', '$account_year', '$company', '$department', '$budget_head', '$against_indent_no', '$budget_available', '$subject', '$background', '$scope_of_work', '$deviations_from_sop', '$important_terms_conditions', '$additional_costs', '$cost', '$status', '$user', now(), '$overhead_exp', '$trans_type', '$location', '$tender_no', '$advance_flag', '$remarks' )";
//echo $sql."<BR>";
			$query=mysqli_query($con, $sql);
			$new_ap_id = mysqli_insert_id($con);
			
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

            $account_year = $_SESSION['short_fy_code'];
            $sql = " SELECT * FROM `sma_purchase_req_items` where 1 and purchase_req_id = '$against_indent_no' " ;
echo $sql."<BR>";            
            $qry=mysqli_query($con, $sql);
            while($r2	 = mysqli_fetch_array($qry)){
    			$reversal_item_no   = $r2['id'];
    			$product_id     = $r2['product_id'];
    			$description    = $r2['description'];
    			$quantity       = $r2['quantity'];
    			$unit           = $r2['unit'];
    			
    			$sql  = "SELECT * from sma_product where id = '$product_id' ";
				$res2  = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r12 = mysqli_fetch_array($res2);
				$budget_name 	= $r12['budget_name'];
				$budget_head 	= $r12['budget_head'];
				$unit 	        = $r12['uom'];
				
				$sql = "SELECT * FROM `sma_budget` where account_year = '$account_year' 
				            AND project = '$company'
				            AND budget_name = '$budget_name'
				            AND budget_head = '$budget_head' ";
//echo $sql ."<BR>";

				$bd = mysqli_query($con, $sql);
                echo mysqli_error($con);
                $bdrw = mysqli_fetch_array($bd);
				$budget_id 		= $bdrw['id'];	
//exit();

    			$sql = " INSERT INTO sma_approval_items(approval_hdr_id , reversal_item_no , product_id, product_desc, quantity, uom, budget_id ) 
    			            VALUES ('$new_ap_id', '$reversal_item_no', '$product_id', '$description', '$quantity', '$unit', '$budget_id' ) ";
    			$bd2 = mysqli_query($con, $sql);
    			$new_ap_item_id = mysqli_insert_id($con);
    		//	echo $sql."<BR>";
    			
    			$sql = " UPDATE `sma_purchase_req_items` set ap_no = '$new_ap_id', ap_item_no = '$new_ap_item_id', ap_quantity = quantity where id = '$reversal_item_no' " ;
                mysqli_query($con, $sql);
          //      echo $sql."<BR>";
                
            }
            
   // exit('Exit Here');        
            
			$sql = " INSERT INTO workflow_history (doc_type, doc_id, create_by, create_date, status ) 
						values('AP', '$id', '$userid', now(), 'Draft' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $id. ','. $dated. ','. $department. ','. $subject;
		    $affect 		= 'Add';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company','$description','$affect')";
		    mysqli_query($con, $sql);
			
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
            Note for Approval(NOA)
            <small>Add</small>
			<small>
				<a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo $baseurl . 'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Note for Approval(NOA)</a></li>
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
                        <h3 class="box-title">Create </h3>
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
                                
                                <div class="col-sm-4">
									<label for="company" class="control-label">Company <span style="color:red;">**</span></label>
                                	<select class="form-control" name="company" id="companY" onchange="getlocation(this.value);getPR(this.value);" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								<input type="hidden" id="overhead_exp" name="overhead_exp" value="Y" >
								
							
    							    <div class="col-sm-4">
    									<label for="against_indent_no" class="control-label"> Against PR Number</label>
    								<span id="getPR">
    									<select class="form-control" name="against_indent_no" id="against_indent_no" required >
    									<option value=""> Select </option>
    										<?php $sql = "select * from sma_purchase_req where 1 and del !='Y' and company_id = '$company_id' order by date desc ";
    										$q2 	= mysqli_query($con, $sql);
    										while($r2 = mysqli_fetch_array($q2)){ ?>
    									<option value="<?php echo $r2['id'];?>"  ><?php echo $r2['pr_number']. ' '.$r2['date']. ' ' . $r2['id'];?></option>
    										<?php } ?>
    									</select>
    								</span>		
                                    </div>
                              
							</div>
							
							   
						<span id = "getSubject">
										
							<div class="form-group">	
								
								<div class="col-md-12">
									<label class="control-label">Subject</label>
									<input type="text" class="form-control" required  name="subject" placeholder="" value="<?php echo $row['subject'];?>" >
								</div>
							
							</div>
					    
                            <div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Remarks</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control " rows = "2" name="remarks" ></textarea>
                                    </div>
                                </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Background</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control " id="reason" name="background"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-12">
                                    <div class="box-header"><span class="box-title">Scope of Work</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="reason1" name="scope_of_work" placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
							</div>
                        </span>	
                        	
                        	
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
        CKEDITOR.replace('reason4');
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

	function getlocation(id){
		
        var sub    = 'sub36';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub36:sub},function(result){
		      $('#getlocation').html(result);
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
	
	
	function getPR(id){
		
        var sub    = 'sub38';
		var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub38:sub},function(result){
		      $('#getPR').html(result);
		});

	}
	
	
	function getSubject(id){
		
        var sub    = 'sub39';
		var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub39:sub},function(result){
		      $('#getSubject').html(result);
		});
		
// 		var strURL = "app_func.php";
// 		$.post(strURL,{id:id,company_id:company_id,sub40:sub},function(result){
// 		      $('#reason').html(result);
// 		});
		

	}
	
</script>



</body>
</html>
