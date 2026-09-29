<?php 

	if($_GET['sub'] == 'list'){
		
	include("../header.php");
//$modulePath = "budget/budget_trans_list.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Product Ledger Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Ledger</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
       		<?php 
				
			 
			    if ($_POST['project'] || $_POST['from_date'] || $_POST['product_name'] || $_POST['category'] ){
					$_SESSION['project'] 		= $_POST['project'];
					$_SESSION['product_name'] 	= $_POST['product_name'];
					$_SESSION['product_group'] 	= $_POST['product_group'];
					$_SESSION['category'] 	= $_POST['category'];
					
					$_SESSION['from_date'] 		= $_POST['from_date'];
					$_SESSION['to_date'] 		= $_POST['to_date'];
				}
				
				if ( $_SESSION['project'] ||  $_SESSION['from_date'] ||  $_SESSION['product_name'] || $_SESSION['category'] ){
					$project 			= $_SESSION['project'];
					$product_name 		= $_SESSION['product_name'];
					$product_group 		= $_SESSION['product_group'];
					$category 			= $_SESSION['category'];
					$from_date 			= $_SESSION['from_date'];
					$to_date 			= $_SESSION['to_date'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['project'] 		= '';
					$_SESSION['product_name'] 	= '';
					$_SESSION['product_group'] 	= '';
					$_SESSION['from_date'] 		= '';
					$_SESSION['to_date'] 		= '';
					$_SESSION['category'] 		= '';
					
					$project 		= $_SESSION['project'];
					$product_name 	= $_SESSION['product_name'];
					$product_group 	= $_SESSION['product_group'];
					$category 		= $_SESSION['category'];
					$from_date 		= $_SESSION['from_date'];
					$to_date 		= $_SESSION['to_date'];
					
				}
				
			
			?>
			<form class="form-horizontal" action="grn_ledger_report.php?sub=list&view=Y" method="post">
                      
						<div class="form-group">	
							<div class="col-sm-3">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required  onchange="getproduct(this.value);"  >
                             		<option value=""> Select </option>
									<?php 
										$sql = " select * from company WHERE comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						 
							<div class="col-md-3">
								<label class=" control-label">Product Group</label>
							  <span id ="getproduct">	
								<select class="form-control" required name="product_group" id="product_group" onchange="getproduct_group(this.value);" >
									<option value=""> Select </option>
									<option value="" <?php echo ($product_group == '')?'selected="selected"':'';?>> All </option>
										<?php $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$project' ) order by name ) order by product_group ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['product_group'];?></option>
										<?php } ?>
								</select>
							  </span>	
							</div>
				<?php //echo $sql = "select * from sma_product where `product_group` = '$product_group' and id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$project' ) order by trim(name) "; ?>
							<div class="col-md-4">
								<label class="control-label">Product Name</label>
							<span id="getproduct_group" >	
								<select class="form-control" required name="product_name" id="product_name" >
									<option value=""> Select </option>
									<option value="" <?php echo ($product_name == '')?'selected="selected"':'';?>> All </option>
									<?php 
									$sql = "select * from sma_product where `product_group` = '$product_group' and id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$project' ) order by trim(name) ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($product_name == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name']. ' ' . $r2['id'];?></option>
									<?php } ?>
								</select>
								</select>
							</span>
							</div>
							
							<div class="col-md-2">
										<label class="control-label">Category</label>
										<select class="form-control select2" name="category" id="category" >
											<option value=""> Select </option>
											<option value="M" <?= ($category == 'M')?'selected="selected"':'';?>> Material </option>
											<option value="S" <?= ($category == 'S')?'selected="selected"':'';?>> Service </option>		
											<option value="B" <?= ($category == 'B')?'selected="selected"':'';?>> Both </option>	
										</select>	
							</div>
									
						</div>
								
						<?php
						
							if(empty($_POST['from_date'])){	 
								$sql = "SELECT * from sma_financial_year where 1 and status = 'Y' ";
								$q2  = mysqli_query($con, $sql);
								$r2  = mysqli_fetch_array($q2);
								$comp_start_date = date('d-m-Y', strtotime($r2['from_date']));
								$comp_end_date   = date('d-m-Y', strtotime($r2['to_date']));
							}
							else { 
								$comp_start_date = date('d-m-Y', strtotime($from_date));
								$comp_end_date   = date('d-m-Y', strtotime($to_date));
							}	
							
						?>
						
						<div class="form-group">	
							<span id="getfindate">
								<div class="col-md-2">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date" placeholder="" value="<?php echo $comp_start_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
								
								<div class="col-md-2">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" placeholder="" value="<?php echo $comp_end_date;; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
							</span>						
							
							<div class="pull-right col-xs-1">	
								<a href="grn_ledger_report.php?sub=list&reset=1" name="btnCancel" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
							
							<div class="pull-right col-xs-1">	
								<a href="grn_ledger_report.php?sub=export" name="btnCancel" target="_blank" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>Export</a>
							</div>
							
							<div class="pull-right col-xs-1">
								<input class="btn btn-success" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
							
							
						</div>
						
				</form>

			</div>
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
	
	function getproduct_group(id){

		var sub    		= 'sub13';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id + ' ' + account_year);
		var strURL 		= "gin_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub13:sub},function(result){
		      $('#getproduct_group').html(result);
		});
		
	}
	
	function getproduct(id){

		var sub    		= 'sub14';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id + ' ' + account_year);
		var strURL 		= "gin_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub14:sub},function(result){
		      $('#getproduct').html(result);
		});
		
	}
	
</script>
	
<?php
}

if($_GET['view']=='Y' && !$_GET['page'] ){

	$modulePath1 = "report/";
		
	$sql = "TRUNCATE TABLE product_stock_ledger";
	mysqli_query($con, $sql);
	
		$company_id 	= $_POST['project'];
		$product_name 	= $_POST['product_name'];
		$product_group	= $_POST['product_group'];
		$from_date 		= date('Y-m-d', strtotime($_POST['from_date']));
		$to_date 		= date('Y-m-d', strtotime($_POST['to_date']));

	$sqlg = "";
//Product Stock Start				
	$sql = " SELECT * FROM sma_product_open_stock WHERE 1 ";
	if(!empty($product_group)){
		$sqlg = " AND product_group = '$product_group' ";
	}
	
	if(!empty($product_name)){
		$sql .= " AND product_name = '$product_name' ";
	}
	$sql .= " AND project	     = '$company_id' ";
	
	if($category=='M' || $category=='S'){
		$sql .= " AND product_name in (select id from sma_product where 1 and category = '$category' $sqlg ) ";
	}
//echo $sql;																					  
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['project'];
		$material_id	 	= $row['product_name'];
		$created_date	 	= $row['created_date'];
		$doc_no				= $row['id'];
		$qty		 		= $row['opening_stock'];
		$doctype 			= 'OP';
	
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, opening_stock) VALUES ('$company_id', '$material_id', '', '' , '$doctype', '$qty' ) ";
//echo $sql. "<BR>";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//Receipt Stock	
	$sql = " SELECT a.company_id, a.created_date, b.* FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id ";
	
	if(!empty($product_name)){
		$sql .= " AND b.material_id = '$product_name' ";
	}
	$sql .= " AND a.company_id   = '$company_id' ";
	
	if($category=='M' || $category=='S'){
		$sql .= " AND b.material_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	
	$sql .= " AND a.created_date < '$from_date' ";
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['material_id'];
		$qty		 		= $row['qty'];
		$doctype 			= 'GRN';
		
		$sql = " UPDATE product_stock_ledger set opening_stock = opening_stock + $qty where  company_id = '$company_id' AND product_id = '$material_id' ";
//echo $sql. "<BR>";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}

//GRN without PO  Start
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id ";
	if(!empty($product_name)){
		$sql .= " AND b.product_id = '$product_name' ";
	}
	if($category=='M' || $category=='S'){
		$sql .= " AND b.product_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	$sql .= " AND a.company_id   = '$company_id' ";
	$sql .= " AND a.dated < '$from_date' ";
	
//echo $sql;																					  
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$created_date	 	= $row['dated'];
		$doc_no				= $row['grn_hdr_id'];
		$qty		 		= $row['receipt_qty'];
		$doctype 			= 'GRN';
		
		$sql = " UPDATE product_stock_ledger set opening_stock = opening_stock + $qty where  company_id = '$company_id' AND product_id = '$material_id' ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GRN without PO END

//Issue Stock	
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id ";
	
	if(!empty($product_name)){
		$sql .= " AND b.product_id = '$product_name' ";
	}
	if($category=='M' || $category=='S'){
		$sql .= " AND b.product_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	$sql .= " AND a.company_id   = '$company_id' ";
	$sql .= " AND a.dated < '$from_date' ";
	
//echo $sql;																					  
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$qty		 		= $row['issue_qty'];
		$doctype 			= 'GIN';
		
		$sql = " UPDATE product_stock_ledger set opening_stock = opening_stock - $qty where  company_id = '$company_id' AND product_id = '$material_id' ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//Product Stock End
	
//GRN Start
	
	$sql = " SELECT a.company_id, a.created_date, b.* FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id ";
	
	if(!empty($product_name)){
		$sql .= " AND b.material_id = '$product_name' ";
	}
	
	$sql .= " AND a.company_id   = '$company_id' ";
	
	if($category=='M' || $category=='S'){
		$sql .= " AND b.material_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	
	$sql .= " AND a.created_date >= '$from_date' ";
	$sql .= " AND a.created_date <= '$to_date' ";
	
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

//	include "paginate.php";
	
	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['material_id'];
		$created_date	 	= $row['created_date'];
		$doc_no				= $row['si_hdr_id'];
		$qty		 		= $row['qty'];
		$doctype 			= 'GRN';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, receipts) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no' , '$doctype', '$qty' ) ";
//echo $sql. "<BR>";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GRN End	


//GRN without PO  Start
	
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id ";
	
	if(!empty($product_name)){
		$sql .= " AND b.product_id = '$product_name' ";
	}
	if($category=='M' || $category=='S'){
		$sql .= " AND b.product_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	$sql .= " AND a.company_id   = '$company_id' ";
	
	$sql .= " AND a.dated >= '$from_date' ";
	$sql .= " AND a.dated <= '$to_date' ";
	
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$created_date	 	= $row['dated'];
		$doc_no				= $row['grn_hdr_id'];
		$qty		 		= $row['receipt_qty'];
		$doctype 			= 'GRN';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, receipts) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no', '$doctype', '$qty' ) ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GRN without PO END


//GIN Start
	
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id ";
	
	if(!empty($product_name)){
		$sql .= " AND b.product_id = '$product_name' ";
	}
	if($category=='M' || $category=='S'){
		$sql .= " AND b.product_id in (select id from sma_product where 1 $sqlg and category = '$category' ) ";
	}
	$sql .= " AND a.company_id   = '$company_id' ";
	
	$sql .= " AND a.dated >= '$from_date' ";
	$sql .= " AND a.dated <= '$to_date' ";
	
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$created_date	 	= $row['dated'];
		$doc_no				= $row['gin_hdr_id'];
		$qty		 		= $row['issue_qty'];
		$doctype 			= 'GIN';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, issued) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no', '$doctype', '$qty' ) ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GIN END
		
?>
	
	
	<div class="col-md-12">
	
		<div class="box box-info">
		
	<div class="box">
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Company </th>
			<th>Product Name</th>
			<th>UOM</th>
			<th style="text-align:left;">Dated</th>
			<th>Doc Type</th>
			<th>Doc No.</th>
			<th  style="text-align:right;">Opening Stock</th>
			<th  style="text-align:right;">Receipts</th>
			<th  style="text-align:right;">Issued</th>
			<th  style="text-align:right;">Closing Stock</th>

		</tr>
	</thead>
<tbody>


<?php 

	$sql = "SELECT * from product_stock_ledger where 1 order by product_id, dated ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$product_id	 		= $row['product_id'];
		$dated			 	= date('d-m-Y', strtotime($row['dated']));
		
		$doc_no				= $row['doc_no'];
		$doc_type			= $row['doc_type'];
		$opening_stock		= $row['opening_stock'];
		$issued		 		= $row['issued'];	
		$receipts		 	= $row['receipts'];
			
		if($product_id_prev != $product_id && !empty($product_id_prev) ){	
?>
		<tr>
			<td width="10%" style="text-align:left;">&nbsp;</td>
			<th width="10%" style="text-align:left;">Total</th>
			<td width="20%" style="text-align:left;">&nbsp;</td>
			<td width="20%" style="text-align:left;">&nbsp;</td>
			<td width="10%" style="text-align:left;">&nbsp;</td>
			<td width="10%" style="text-align:left;">&nbsp;</td>
			<td width="10%" style="text-align:left;">&nbsp;</td>
			<td width="10%" style="text-align:right;"><?= $total_receipts;?></td>
			<td width="10%" style="text-align:right;"><?= $total_issue;?></td>
			<td width="10%" style="text-align:right;"><b><?php echo $closing_stock;?></b></td>
		
		</tr>
		
<?php
			$closing_stock 	= 0;
			$total_receipts	=0;
			$total_issue 	=0;
		}
	
		$product_id_prev = $product_id;
		
		if($doc_type=='OP'){
			$doc_type = 'Opening Stock';
			$closing_stock 		= round($closing_stock + $opening_stock,2);
			$issued ='';
			$receipts ='';
		}
		else if($doc_type=='GIN'){
			$closing_stock      = round($closing_stock - $issued,2);
		 	$total_issue 		= round($total_issue + $issued,2);
			$opening_stock ='';
			$receipts ='';
		}
		else if($doc_type=='GRN'){
			$closing_stock      = round($closing_stock + $receipts,2);
			$total_receipts	 	= round($total_receipts + $receipts,2);
			$opening_stock ='';
			$issued ='';
		}
		
		
		$sql = "select * from sma_product where id = '$product_id'  ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$product_name = $r2['name'];
		$uom = $r2['uom'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		if($dated=='30-11--0001' || $dated=='01-01-1970'){
			$dated='';
		}
?>
		
		<tr>
			<td width="10%" style="text-align:left;"><?php echo $comp_code ;?></td>
			<td width="20%" style="text-align:left;"><?php echo $product_name;?></td>
			<td width="20%" style="text-align:left;"><?php echo $uom;?></td>
			<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
			<td width="10%" style="text-align:left;"><?php echo $doc_type;?></td>
			<td width="10%" style="text-align:left;"><?php echo $doc_no;?></td>
			<td width="10%" style="text-align:right;"><?php echo $opening_stock;?></td>
			<td width="10%" style="text-align:right;"><?php echo $receipts;?></td>
			<td width="10%" style="text-align:right;"><?php echo $issued;?></td>
			<td width="10%" style="text-align:right;"><?php echo $closing_stock;?></td>
		</tr>
<?php	
	}

?>

	<tr>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="20%" style="text-align:left;">Total</td>
		<td width="20%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:right;"><?= $total_receipts;?></td>
		<td width="10%" style="text-align:right;"><?= $total_issue;?></td>
		<td width="10%" style="text-align:right;"><b><?php echo $closing_stock;?></b></td>
    </tr>
	
	<tr>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="20%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
    </tr>
</tbody> 
</table>

</div>
    </div>
</div>	


<?php 	
		include("../footer.php");

}
		
		
if($_GET['sub']=='export'){

	include("../dbcon.php");
	
	session_start();
	
	$message = "";
	
   $message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='9'>
				Product Stock Ledger Report for the Period ". date('d-m-Y', strtotime($_SESSION['from_date'])) . ' To ' . date('d-m-Y', strtotime($_SESSION['to_date'])) . " </th></tr>
			</table>";
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>";
	$message .= '<tr>
			<th>Company </th>
			<th>Product Name</th>
			<th style="text-align:left;">Dated</th>
			<th>Doc Type</th>
			<th>Doc No.</th>
			<th style="text-align:right;">Opening Stock</th>
			<th style="text-align:right;">Receipts</th>
			<th style="text-align:right;">Issued</th>
			<th style="text-align:right;">Closing Stock</th>
		</tr>';

	$sql = "SELECT * from product_stock_ledger where 1 order by product_id, dated ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$product_id	 		= $row['product_id'];
		$dated			 	= date('d-m-Y', strtotime($row['dated']));
		if($dated=='30-11--0001' || $dated=='01-01-1970'){
			$dated='';
		}
		$doc_no				= $row['doc_no'];
		$doc_type			= $row['doc_type'];
		$opening_stock		= $row['opening_stock'];
		$issued		 		= $row['issued'];	
		$receipts		 	= $row['receipts'];
			
		if($product_id_prev != $product_id && !empty($product_id_prev) ){
			$message .= '<tr>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<td width="20%" style="text-align:left;">&nbsp;</td>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<td width="10%" style="text-align:left;">&nbsp;</td>
				<th width="10%" style="text-align:left;">Closing Stock</th>
				<td width="10%" style="text-align:right;"><b>'. $closing_stock.'</b></td>
			</tr>';
			$closing_stock =0 ;
		}	
		
		
		$product_id_prev =  $product_id;
		
		if($doc_type=='OP'){
			$closing_stock 		= $closing_stock + $opening_stock;
			$receipts ='';
			$issued ='';
			$doc_type = '<b>Opening Stock</b>';
		}
		else if($doc_type=='GIN'){
			$closing_stock      = $closing_stock - $issued;
			$opening_stock ='';
			$receipts ='';
		}
		else if($doc_type=='GRN'){
			$closing_stock      = $closing_stock + $receipts;
			$opening_stock ='';
			$issued ='';
		}
		
		$sql = "select * from sma_product where id = '$product_id'  ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$product_name = $r2['name'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		$message .= '<tr>
			<td width="10%" style="text-align:left;">'. $comp_code.'</td>
			<td width="20%" style="text-align:left;">'. $product_name.'</td>
			<td width="10%" style="text-align:left;">'. $dated.'</td>
			<td width="10%" style="text-align:left;">'. $doc_type.'</td>
			<td width="10%" style="text-align:left;">'. $doc_no.'</td>
			<td width="10%" style="text-align:right;">'. $opening_stock.'</td>
			<td width="10%" style="text-align:right;">'. $receipts.'</td>
			<td width="10%" style="text-align:right;">'. $issued.'</td>
			<td width="10%" style="text-align:right;">'. $closing_stock.'</td>
		</tr>';

	}

	$message .= '<tr>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="20%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<th width="10%" style="text-align:left;">Closing Stock</th>
		<td width="10%" style="text-align:right;"><b>'. $closing_stock.'</b></td>
    </tr>';
	
	$message .= "</table>";	
		
//echo $message;
//exit();

		$fl_name = 'grn_ledger_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
		
}
?>

