<?php
ini_set('max_execution_time', '300');
?>

<?php if($_GET['sub'] == 'list'){
		
include("../header.php");
$modulePath = "report/budget_monthly_used_trans.php?sub=list";


?>
  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Transactions
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
       		<?php 
			
				
//echo $_POST['short_fy_code']. ' <<>> ' . $_POST['project'] .' or '. $_POST['budget_name'] .' or '. $_POST['budget_head'];
				
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] || $_POST['short_fy_code']){
					$_SESSION['project_a'] = $_POST['project'];
					$_SESSION['location_a'] = $_POST['location'];
					$_SESSION['short_fy_code'] = $_POST['short_fy_code'];
					
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					$from_date		= $_POST['from_date'];
					$to_date		= $_POST['to_date'];
					
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a'] || $_SESSION['short_fy_code']){
					
					$short_fy_code 		= $_SESSION['short_fy_code'];
					$project_v 		= $_SESSION['project_a'];
					$location_v 	= $_SESSION['location_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
	
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
				
					$_SESSION['short_fy_code'] 		= '';
					$_SESSION['project_a'] 		= '';
					$_SESSION['location_a'] 	= '';
					$_SESSION['budget_name_a'] 	= '';
					$_SESSION['budget_head_a'] 	= '';
					$_SESSION['reset'] 			= '';
					$short_fy_code 		= $_SESSION['short_fy_code'];
					$project_v 			= $_SESSION['project_a'];
					$location_v 		= $_SESSION['location_a'];
					$budget_name_v 		= $_SESSION['budget_name_a'];
					$budget_head_v 		= $_SESSION['budget_head_a'];
				
				}

//echo $budget_name_v. ' ' . $budget_head_v. "<BR>";

			
	//	echo $project_v. ' >< '. $account_year_v;
			?>
					<form class="form-horizontal" action="budget_monthly_used_trans.php?sub=exl&view=Y" target="_blank" method="post" >
                      
						<div class="form-group">
							
							<div class="col-sm-4">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required onchange="getccname(this.value);getlocation(this.value);"  >
                             		<option value=""> Select </option>
									<option value=""> All </option>
									<?php 
										$sql = " select * from company WHERE comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-2" class="input-append">
								<label class="control-label">Financial Year </label>
								<select class="form-control" name="short_fy_code" id="short_fy_code" required <?= $readonly; ?> >
									<option value="">Select</option>
									<option value=""> All </option>
									<?php
										$sql="SELECT * FROM sma_financial_year order by id desc ";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
										?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($short_fy_code == $r2['short_fy_code'])?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
									<?php } ?>
								</select>
							</div>
							
						
							<div class="col-sm-2">
								<label for="location" class="control-label ">Location</label>
								<span id="getlocation">
									<select class="form-control" name="location" id="location" required >
									<option value=""> Select </option>
									<option value=""> All </option>
									<?php $sql = "select * from sma_location where loc_comp_id = '$project_v' order by loc_name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($location_v == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['loc_name'];?></option>
									<?php } ?>
									</select>	
								</span>
                            </div>
							
							<div class="col-sm-4">
								<label for="project" class="control-label ">Months</label>
								<select class="form-control select2" multiple name="months[]" id="MONTHS" required >
                             		<option value=""> Select </option>
									<option value=""> All </option>
									<option value="1" > January</option>
									<option value="2" > February</option>
									<option value="3" > March</option>
									<option value="4" > April</option>
									<option value="5" > May</option>
									<option value="6" > June</option>
									<option value="7" > July</option>
									<option value="8" > August</option>
									<option value="9" > September</option>
									<option value="10" > October</option>
									<option value="11" > November</option>
									<option value="12" > December</option>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">	
							<div class="col-md-3">
								<label class=" control-label">CC Group</label>
							<span id ="getccname">	
								<select class="form-control" required name="budget_name" id="budget_name" onchange="getccgroup(this.value);" >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_budget_name where id in (select budget_name from sma_budget where project = '$project_v' ) order by name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
									<?php } ?>
								</select>
							</span>	
							</div>
							
							<div class="col-md-3">
								<label class="control-label">CC Sub Group</label>
							<span id="getccgroup" >	
								<select class="form-control" required name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget WHERE budget_name = '$budget_name_v' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_head_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head']. ' - ' . $r2['budget_code'];?></option>
										<?php } ?>
								</select>
							</span>
								
							</div>
						</div>
												
												
						<div class="form-group">	
							
							<div class="pull-right col-xs-1">	
								<a href="budget_monthly_used_trans.php?sub=list&reset=1" name="btnCancel" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
								
							</div>
							
							<div class="pull-right col-xs-1">
								<input class="btn btn-success" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
							
						</div>
						
				</form>

			</div>
			
		</div>	


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

	function getccname(id){

		var sub    		= 'sub14';
//alert(sub);		
		//var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id);
		var strURL 		= "app_func.php";
		$.post(strURL,{company_id:id,sub14:sub},function(result){
		      $('#getccname').html(result);
		});
		
	}
	
	function getccgroup(id){

		var sub    		= 'sub11';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub11:sub},function(result){
		      $('#getccgroup').html(result);
		});
		
	}

	function getlocation(id){
		
        var sub    = 'sub15';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub15:sub},function(result){
		      $('#getlocation').html(result);
		});

	}	
		
</script>


<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

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
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });



</script>
    
<?php


	}

//exit();
	$modulePath1 = "report/";
//echo $from_date. ' <<>> ' . $to_date;	

if($_GET['view']=='Y' && $_GET['sub']=='exl' ){
	
	include("../dbcon.php");
	
	$mth_1 = '';
	$mth_2 = '';
	$mth_3 = '';
	$mth_4 = '';
	$mth_5 = '';
	$mth_6 = '';
	$mth_7 = '';
	$mth_8 = '';
	$mth_9 = '';
	$mth_10 = '';
	$mth_11 = '';
	$mth_12 = '';
	
	$sql = "truncate tmp_trans_data";
	mysqli_query($con, $sql);
	
	$sql = "truncate tmp_repo_budget ";
	mysqli_query($con, $sql);
		
	$project_v  		= $_POST['project'];
	$location_v 		= $_POST['location'];
	$finance_year 		= $_POST['short_fy_code'];
 	$months				= $_POST['months'];
	foreach($months as $value){
		$months_chk .= $value;
	}

	if(!empty($months_chk)){
		foreach($months as $value){
			
			if($value==1){
				$mth_1 = $value;
			}
			else if($value==2){
				$mth_2 = $value;
			}
			else if($value==3){
				$mth_3 = $value;
			}
			else if($value==4){
				$mth_4 = $value;
			}
			else if($value==5){
				$mth_5 = $value;
			}
			else if($value==6){
				$mth_6 = $value;
			}
			else if($value==7){
				$mth_7 = $value;
			}
			else if($value==8){
				$mth_8 = $value;
			}
			else if($value==9){
				$mth_9 = $value;
			}
			else if($value==10){
				$mth_10 = $value;
			}
			else if($value==11){
				$mth_11 = $value;
			}
			else if($value==12){
				$mth_12 = $value;
			}
			
		}	
	}	
	
	$sql="SELECT * FROM sma_financial_year where 1 AND short_fy_code = '$finance_year' ";
	$q2 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r2 = mysqli_fetch_array($q2);
	$from_date 	 = $r2['from_date'];
	$to_date 	 = $r2['to_date'];
					
	if( !empty($project_v) ){
		$sqla .= " and a.company_id = '$project_v' ";
	}
	if( !empty($location_v) ){
		$sqlb .= " and a.location = '$location_v' ";
	}
	if( !empty($budget_name_v) ){
		$sqlc .= " and c.budget_name = '$budget_name_v' ";
	}
	if( !empty($budget_head_v) ){
		$sqld .= " and c.id = '$budget_head_v' ";
	}

//Supplier Invoice Start
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company_id, 
					suplier_name as supplier_id, supplier_invoice_no as 'invoice_no' , our_po_ref_no
					FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
					WHERE 1 AND a.id = b.si_hdr_id 
						AND b.budget_id = c.id 
						AND a.del !='Y' 
						AND a.approval_status !='Rejected' 
						AND a.created_date >= '$from_date' AND a.created_date <= '$to_date'
						" . $sqla . $sqlc . $sqld;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type				= 'Supplier Invoice';
		$doc_no					= $row['id'];
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company_id'];
		$supplier_id 			= $row['supplier_id'];
		$invoice_no 			= $row['invoice_no'];
		$our_po_ref_no  		= $row['our_po_ref_no'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code					= $r3['comp_code'];
		$budget_control_gst			= $r3['budget_control_gst'];

		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name					= $r3['party_name'];
		
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){
			
			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];		
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			
			if($budget_control_gst=='N'){
				$amount = round(($quantity * $unit_rate),0);
			}
			else {
				$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			}
			
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res4 = mysqli_query($con, $sql);
			$r4 = mysqli_fetch_array($res4);
			$cost_center_group 		= $r4['budget_name'];
			$cost_center_sub_group 	= $r4['budget_head'];
			$budget_code 			= $r4['budget_code'];
			
			$sql = " INSERT INTO tmp_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, finance_year, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value, company_id, budget_id ) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', '$finance_year',
				'$invoice_no', '$product_category', '$product_name', '$party_name', 
				'$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount', '$company_id', '$budget_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End


//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id', a.approval_number 
			FROM `sma_travel_expenses` a, `sma_expenses` b, sma_budget c
				WHERE 1 and a.id = b.approval_ref_no 
					AND b.budget_id = c.id 
					and a.exp_type = 'C'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND a.dated >= '$from_date' AND a.dated <= '$to_date'
					" . $sqla .$sqlc. $sqld;

//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type				= 'Operating Expense';
		$doc_no					= $row['id'];
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		$approval_number 		= $row['approval_number'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code					= $r3['comp_code'];
		$budget_control_gst			= $r3['budget_control_gst'];
			
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['party_name'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];	
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			if($budget_control_gst=='Y'){
				$amount				= $amount + $gst_amount;
			}
			
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res4 = mysqli_query($con, $sql);
			$r4 = mysqli_fetch_array($res4);
			$cost_center_group 		= $r4['budget_name'];
			$cost_center_sub_group 	= $r4['budget_head'];
			$budget_code 			= $r4['budget_code'];
			
			$sql = " INSERT INTO tmp_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, finance_year, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value, company_id, budget_id) 
				VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', '$finance_year',
					'$approval_number', '$product_category', '$product_name', '$party_name', '$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount', '$company_id', '$budget_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End
	
	
//Travel Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c
				WHERE 1 and a.id = b.approval_ref_no 
					AND b.budget_id = c.id 
					and a.exp_type = 'T'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND a.dated >= '$from_date' AND a.dated <= '$to_date'
					" . $sqla . $sqlc . $sqld;


//echo $sql."<BR>"; 
//exit();	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_no					= $row['id'];
		$doc_type				= 'Travel Expense';
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code					= $r3['comp_code'];
		$budget_control_gst			= $r3['budget_control_gst'];
			
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['username'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
		
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res4 = mysqli_query($con, $sql);
			$r4 = mysqli_fetch_array($res4);
			$cost_center_group 		= $r4['budget_name'];
			$cost_center_sub_group 	= $r4['budget_head'];
			$budget_code 			= $r4['budget_code'];
			
			$sql = " INSERT INTO tmp_trans_data (doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, finance_year, doc_invoice_no, product_category, product_name, supplier_name, cost_center_group, cost_center_sub_group, budget_code, total_value, company_id, budget_id) 
					VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', '$finance_year',
					'$invoice_no', '$product_category', '$product_name', '$party_name', '$cost_center_group', '$cost_center_sub_group', '$budget_code', '$amount', '$company_id', '$budget_id') ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
//exit();
			
		}

	}		
						
//Travel Expense End
	

//Regular Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.company_id as company , emp_id as 'supplier_id'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c
				WHERE 1 and a.id = b.approval_ref_no 
					AND b.budget_id = c.id 
					and a.exp_type = 'R'
					and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND a.dated >= '$from_date' AND a.dated <= '$to_date'
					" . $sqla . $sqlc . $sqld;

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_no					= $row['id'];
		$doc_type				= 'Reimbursement';
		$doc_yyyymm				= date('Y-m', strtotime($row['dated']));
		$doc_date				= date('Y-m-d', strtotime($row['dated']));
		$company_id				= $row['company'];
		$supplier_id 			= $row['supplier_id'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code					= $r3['comp_code'];
		$budget_control_gst			= $r3['budget_control_gst'];
			
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name			= $r3['username'];
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
		
			$sql  = " SELECT a.name as product_name, b.product_group  
						FROM sma_product a, sma_product_group  b 
							WHERE b.id = a.product_group and a.id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name		= $r3['product_name'];
			$product_category	= $r3['product_group'];
			
			$sql = "SELECT a.budget_head, a.budget_code, b.name as budget_name from sma_budget a, sma_budget_name b where a.budget_name = b.id and a.id = '$budget_id' ";
			$res4 = mysqli_query($con, $sql);
			$r4 = mysqli_fetch_array($res4);
			$cost_center_group 		= $r4['budget_name'];
			$cost_center_sub_group 	= $r4['budget_head'];
			$budget_code 			= $r4['budget_code'];
			
			$sql = " INSERT INTO tmp_trans_data ( doc_no, comp_code, doc_type, doc_mm_yyyy, doc_date, 
						finance_year, doc_invoice_no, product_category, product_name, supplier_name, 
							cost_center_group, cost_center_sub_group, budget_code, total_value, company_id, budget_id ) 
					VALUES( '$doc_no', '$comp_code', '$doc_type', '$doc_yyyymm', '$doc_date', '$finance_year', 
						'$invoice_no', '$product_category', '$product_name', '$party_name', '$cost_center_group', 
						'$cost_center_sub_group', '$budget_code', '$amount', '$company_id', '$budget_id' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
			
		}

	}

//exit();

	$sql = "INSERT INTO tmp_repo_budget( id, project, location, budget_name, budget_code, budget_head, account_year ) 		
				SELECT id, project, location, budget_name, budget_code, budget_head, account_year from sma_budget where account_year = '$finance_year' ";
	mysqli_query($con, $sql);
	
	/* $sql 	= "UPDATE tmp_repo_budget SET 
		april = 0, may = 0, june = 0, uly = 0, august = 0, september = 0, october = 0, november = 0, december = 0, january = 0, february = 0, march = 0 ";
	mysqli_query($con, $sql);
	 */
	$sql = "SELECT sum(total_value) as total_value, comp_code, company_id, budget_id , doc_mm_yyyy, 
			cost_center_group, cost_center_sub_group, budget_code 
				FROM `tmp_trans_data` GROUP BY company_id, budget_id, doc_mm_yyyy ";
//echo $sql ."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$total_value					= $row['total_value'];
		$comp_code						= $row['comp_code'];
		$company_id						= $row['company_id'];
		$budget_id						= $row['budget_id'];
		$doc_mm_yyyy					= $row['doc_mm_yyyy'];
		$mmyyyy_list					= explode('-',$doc_mm_yyyy);
		
		$yyyy							= $mmyyyy_list['0'];
		$month							= $mmyyyy_list['1'];
		
		$budget_code					= $row['budget_code'];
		$cost_center_sub_group			= $row['cost_center_sub_group'];
		$cost_center_group				= $row['cost_center_group'];
		
		$actual_field_name ='';
		
		if($month=='01' ){
			$actual_field_name = " set january = ".  $total_value ;
		}
		else if($month=='02' ){
			$actual_field_name = " set february = ".  $total_value ;
		}
		else if($month=='03' ){
			$actual_field_name = " set march = ".  $total_value ;
		}
		else if($month=='04' ){
			$actual_field_name = " set april = ".  $total_value ;
		}
		else if($month=='05' ){
			$actual_field_name = " set may = ".  $total_value ;
		}
		else if($month=='06' ){
			$actual_field_name = " set june = ".  $total_value ;
		}
		else if($month=='07' ){
			$actual_field_name = " set july = ".  $total_value ;
		}
		else if($month=='08' ){
			$actual_field_name = " set august = ".  $total_value ;
		}
		else if($month=='09' ){
			$actual_field_name = " set september = ".  $total_value ;
		}
		else if($month=='10' ){
			$actual_field_name = " set october = ".  $total_value ;
		}
		else if($month=='11' ){
			$actual_field_name = " set november = ".  $total_value ;
		}
		else if($month=='12' ){
			$actual_field_name = " set december = ".  $total_value ;
		}
		
		if(!empty($actual_field_name)){
			$sql 	= "UPDATE tmp_repo_budget $actual_field_name where id = '$budget_id' ";
//echo $sql ."<BR>";			
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}
		
	}

//exit();
	
	$message = '';
	$message .= "<table border='0' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><td style='width: 10%;' ></td>
				<td style='width: 80%;;'>Budget Actual Vs Consumed  </td><td> Date:" . date('d-m-Y') ."</td></tr></table>";	
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; ; font-size: 12pt;'>
			<tr>
				<th style='width: 5%;text-align: right;'></th>
				<th style='width: 8%;'></th>
				<th style='width: 10%;text-align: left;'></th>
				<th style='width: 10%;text-align: left;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;'></th>
				<th style='width: 10%;text-align: left;'></th>";
			if($mth_4==4 || empty($months_chk) ){		
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>April</th>";
			}
			if($mth_5==5 || empty($months_chk) ){				
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>May</th>";
			}
			if($mth_6==6 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>June</th>";
			}
			if($mth_7==7 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>July</th>";
			}
			if($mth_8==8 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>Augsut</th>";
			}
			if($mth_9==9 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>September</th>";
			}
			if($mth_10==10 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>October</th>";
			}
			if($mth_11==11 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>November</th>";
			}
			if($mth_12==12 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>December</th>";
			}
			if($mth_1==1 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>January</th>";
			}
			if($mth_2==2 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>February</th>";
			}
			if($mth_3==3 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color: #00ff00;' colspan='3'>March</th>";
			}
			$message .= "</tr>";
//<th style='width: 10%;text-align: right;'>Over all Blocked Budget</th>			
	$message .= "<tr>
				<th style='width: 5%;text-align: left;'>Company Name</th>
				<th style='width: 8%;'>Cost Center Group</th>
				<th style='width: 10%;text-align: left;'>Cost Center Sub Group </th>
				<th style='width: 10%;text-align: left;'>Cost Center Code for Tally </th>
				<th style='width: 10%;text-align: left;'>Fin.Year</th>
				<th style='width: 10%;text-align: right;'>Open Budget</th>
				<th style='width: 10%;text-align: right;'> Budget Adjustment</th>
				<th style='width: 10%;text-align: right;'> Total Budget </th>
				
				<th style='width: 10%;text-align: right;'> Additional </th>
				<th style='width: 10%;text-align: right;'> Transfer </th>
				<th style='width: 10%;text-align: right;'> Board Approved Budget </th>
				
				
				<th style='width: 10%;text-align: right;'>Over all Blocked Budget</th>
				<th style='width: 10%;text-align: right;'>Over all Consumed Budget</th>
				<th style='width: 10%;text-align: right;'>Over all Balance Budget</th>";
			if($mth_4==4 || empty($months_chk) ){			
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_5==5 || empty($months_chk) ){					
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_6==6 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_7==7 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_8==8 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_9==9 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_10==10 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_11==11 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_12==12 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_1==1 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_2==2 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			if($mth_3==3 || empty($months_chk) ){	
			$message .= "	<th style='width: 10%;background-color:#E9905E;'>Budget</th>
				<th style='width: 10%;background-color:#7EF592;'>Actual Consumed</th>
				<th style='width: 10%;text-align:left;background-color:#F57E90;'>Variance</th>";
			}
			$message .= "</tr>";
		
		$sql 	= "SELECT a.project, a.location, a.budget_name, a.budget_code, a.budget_head, a.account_year, 
		a.april as april_consumed , a.may as may_consumed, a.june as june_consumed, a.july as july_consumed, a.august as august_consumed, a.september as september_consumed, a.october as october_consumed, a.november as november_consumed, a.december as december_consumed, a.january as january_consumed, a.february as february_consumed, a.march as march_consumed,
		b.total_budget, b.blocked_budget, b.used_budget, b.adjustment_budget, b.id as budget_id,
		b.april as april_actual , b.may as may_actual, b.june as june_actual, b.july as july_actual, b.august as august_actual, b.september as september_actual, b.october as october_actual, b.november as november_actual, b.december as december_actual, b.january as january_actual, b.february as february_actual, 
			b.march as march_actual , b.board_approved_budget
		FROM tmp_repo_budget a, sma_budget b 
		WHERE a.id = b.id ";

	if( !empty($project_v) ){
		$sql  .= " and b.project = '$project_v' ";
	}
	if( !empty($budget_name_v) ){
		$sql  .= " and b.budget_name = '$budget_name_v' ";
	}
	if( !empty($budget_head_v) ){
		$sql  .= " and b.id = '$budget_head_v' ";
	}
	
		$sql .= " order by a.project ";
//echo $sql. "<BR>";		
//exit();	
		$result 	= mysqli_query($con, $sql);
		mysqli_affected_rows($con);		
		echo mysqli_error($con);
		while($row 	= mysqli_fetch_array($result)){
			
			$project				= $row['project'];
			$location				= $row['location'];
			$budget_name			= $row['budget_name'];
			$budget_code			= $row['budget_code'];
			$budget_head			= $row['budget_head'];
			$account_year			= $row['account_year'];
			$total_budget			= $row['total_budget'];
			$blocked_budget			= $row['blocked_budget'];
			$used_budget			= $row['used_budget'];
			$adjustment_budget		= $row['adjustment_budget'];
			$board_approved_budget	= $row['board_approved_budget'];	
			$budget_id				= $row['budget_id'];
			
			$open_budget 			= ($total_budget + $adjustment_budget);
			
			$balance_budget 		= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);
			
			//$balance_budget 		= ($total_budget + $adjustment_budget) - ( $used_budget );
			
			$sql = "SELECT * from company where comp_id = '$project' ";
			$res = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($res);
			$comp_name 				= $r2['comp_name'];
			$comp_code 				= $r2['comp_code'];
			
			$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 				= $r2['name'];
			
			$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_head 				= $r2['budget_head'];
			
			$transfer_budget 	= 0;
			$additional_budget 	= 0;
			$sql = " SELECT (amount) as transfer_budget, effect, adjust_type FROM `budget_adjust` where budget_id = '$budget_id' and adjust_type = '1' "; // 1= Transfer, 5=Additional
			
			$res = mysqli_query($con, $sql);
			while($r2  = mysqli_fetch_array($res)){
				$effect 			= $r2['effect'];
				if($effect=='I'){
					$transfer_budget 		= $transfer_budget  + $r2['transfer_budget'];
				}
				else if($effect=='D'){
					$transfer_budget 		= $transfer_budget  - $r2['transfer_budget'];
				}
				
			}
			
			$sql = " SELECT (amount) as additional_budget, effect, adjust_type FROM `budget_adjust` where budget_id = '$budget_id' and adjust_type = '5' "; // 1= Transfer, 5=Additional
	
			$res = mysqli_query($con, $sql);
			while($r2  = mysqli_fetch_array($res)){
				$effect 			= $r2['effect'];
				if($effect=='I'){
					$additional_budget 		= $additional_budget  + $r2['additional_budget'];
					
				}
				else if($effect=='D'){
					$additional_budget 		= $additional_budget  - $r2['additional_budget'];
					
				}
				
			}

			$sql = " SELECT sum(amount) as transfer_budget FROM `budget_adjust_from_to` where budget_id_to = '$budget_id'  "; // 1= Transfer, 5=Additional and account_year = '2023-2024'	
			
			$res = mysqli_query($con, $sql);
			while($r2  = mysqli_fetch_array($res)){
				//$effect 			= $r2['effect'];
				$transfer_budget 	= $transfer_budget  + $r2['transfer_budget'];
				/* if($budget_id ==542 || $budget_id ==543 || $budget_id ==818){
					echo $transfer_budget. "<BR>";
				} */
			}
			
			$sql = " SELECT sum(amount) as transfer_budget FROM `budget_adjust_from_to` where budget_id_from = '$budget_id'  "; // 1= Transfer, 5=Additional and account_year = '2023-2024'		

			$res = mysqli_query($con, $sql);
			while($r2  = mysqli_fetch_array($res)){
				//$effect 			= $r2['effect'];
				$transfer_budget 	= $transfer_budget  + ( $r2['transfer_budget'] * -1 );
				
				/* if($budget_id ==632 ){
					echo $transfer_budget. "<BR>";
				} */
			}
			
/* if($budget_id ==964 ){
	echo $sql. "<BR>";
}  */

			$april_consumed			= $row['april_consumed'];
			$may_consumed			= $row['may_consumed'];
			$june_consumed			= $row['june_consumed'];
			$july_consumed			= $row['july_consumed'];
			$august_consumed		= $row['august_consumed'];
			$september_consumed		= $row['september_consumed'];
			$october_consumed		= $row['october_consumed'];
			$november_consumed		= $row['november_consumed'];
			$december_consumed		= $row['december_consumed'];
			$january_consumed		= $row['january_consumed'];
			$february_consumed		= $row['february_consumed'];
			$march_consumed			= $row['march_consumed'];
			
			$april_actual			= $row['april_actual'];
			$may_actual				= $row['may_actual'];
			$june_actual			= $row['june_actual'];
			$july_actual			= $row['july_actual'];
			$august_actual			= $row['august_actual'];
			$september_actual		= $row['september_actual'];
			$october_actual			= $row['october_actual'];
			$november_actual		= $row['november_actual'];
			$december_actual		= $row['december_actual'];
			$january_actual			= $row['january_actual'];
			$february_actual		= $row['february_actual'];
			$march_actual			= $row['march_actual'];

			$april_variance				= $april_actual 	- $april_consumed;
			$may_variance				= $may_actual 		- $may_consumed;
			$june_variance				= $june_actual 		- $june_consumed;
			$july_variance				= $july_actual 		- $july_consumed;
			$august_variance			= $august_actual 	- $august_consumed;
			$september_variance			= $september_actual - $september_consumed;
			$october_variance			= $october_actual 	- $october_consumed;
			$november_variance			= $november_actual 	- $november_consumed;
			$december_variance			= $december_actual 	- $december_consumed;
			$january_variance			= $january_actual 	- $january_consumed;
			$february_variance			= $february_actual 	- $february_consumed;
			$march_variance				= $march_actual 	- $march_consumed;

			if($april_consumed==0){
				$april_consumed	='';
			}
			if($may_consumed==0){
				$may_consumed	='';
			}
			if($june_consumed==0){
				$june_consumed	='';
			}
			if($july_consumed==0){
				$july_consumed	='';
			}
			if($august_consumed==0){
				$august_consumed	='';
			}
			if($september_consumed==0){
				$september_consumed	='';
			}
			if($october_consumed==0){
				$october_consumed	='';
			}
			if($november_consumed==0){
				$november_consumed	='';
			}
			if($december_consumed==0){
				$december_consumed	='';
			}
			if($january_consumed==0){
				$january_consumed	='';
			}
			if($february_consumed==0){
				$february_consumed	='';
			}
			if($march_consumed==0){
				$march_consumed	='';
			}
			
			if($april_variance==0){
				$april_variance	='';
			}
			if($may_variance==0){
				$may_variance	='';
			}
			if($june_variance==0){
				$june_variance	='';
			}
			if($july_variance==0){
				$july_variance	='';
			}
			if($august_variance==0){
				$august_variance	='';
			}
			if($september_variance==0){
				$september_variance	='';
			}
			if($october_variance==0){
				$october_variance	='';
			}
			if($november_variance==0){
				$november_variance	='';
			}
			if($december_variance==0){
				$december_variance	='';
			}
			if($january_variance==0){
				$january_variance	='';
			}
			if($february_variance==0){
				$february_variance	='';
			}
			if($march_variance==0){
				$march_variance	='';
			}
			
			//
				
			$message .= "<tr>
				<th style='width: 5%;text-align: left;'>$comp_name</th>
				<th style='width: 8%;text-align: left;'>$budget_name</th>
				<th style='width: 10%;text-align: left;'>$budget_head</th>
				<th style='width: 10%;text-align: left;'>$budget_code</th>
				<th style='width: 10%;text-align: left;'>$account_year</th>
				<th style='width: 10%;text-align:right;'>$total_budget</th>
				<th style='width: 10%;text-align:right;'>$adjustment_budget</th>
				<th style='width: 10%;text-align:right;'>$open_budget</th>
				
				<th style='width: 10%;text-align:right;'>$additional_budget</th>
				<th style='width: 10%;text-align:right;'>$transfer_budget</th>
				<th style='width: 10%;text-align:right;'>$board_approved_budget</th>
				
				<th style='width: 10%;text-align:right;'>$blocked_budget</th>
				<th style='width: 10%;text-align:right;'>$used_budget</th>
				<th style='width: 10%;text-align:right;'>$balance_budget</th>";
			
			if($mth_4==4 || empty($months_chk) ){	
				$message .= "	<th style='width: 10%;text-align:right;background-color:#E9905E;'>$april_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$april_consumed</th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$april_variance</th>";
			}
			if($mth_5==5 || empty($months_chk) ){
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$may_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$may_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$may_variance</th>";
			}
			if($mth_6==6 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$june_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$june_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$june_variance</th>";
			}
			if($mth_7==7 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$july_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$july_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$july_variance</th>";
			}
			if($mth_8==8 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$august_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$august_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$august_variance</th>";
			}
			if($mth_9==9 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$september_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$september_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$september_variance</th>";
			}
			if($mth_10==10 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$october_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$october_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$october_variance</th>";
			}
			if($mth_11==11 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$november_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$november_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$november_variance</th>";
			}
			if($mth_12==12 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$december_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$december_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$december_variance</th>";
			}	
			if($mth_1==1 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$january_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$january_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$january_variance</th>";
			}	
			if($mth_2==2 || empty($months_chk) ){
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$february_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$february_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$february_variance</th>";
			}
			if($mth_3==3 || empty($months_chk) ){	
			$message .= "<th style='width: 10%;text-align:right;background-color:#E9905E;'>$march_actual</th>
				<th style='width: 10%;text-align:right;background-color:#7EF592;'>$march_consumed </th>
				<th style='width: 10%;text-align:right;background-color:#F57E90;'>$march_variance</th>";
			}	
			$message .= "</tr>";
				
		}	
		
		$message .= "</table>";

//echo $message;
//exit();	
		$fl_name = "budget_actual_consumed_".date('Y-m-d').".xls ";
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");
		print $message;
		
		exit();

}

?>

<?php
		
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}

?>


</body>
</html>
