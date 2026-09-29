<?php if($_GET['sub'] == 'list'){
?>
<?php

include("../header.php");
$modulePath = "budget/budget_used_SI.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Used Transactions 
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
				//echo $_POST['project'].'<> ';
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] ){
					$account_year			= $_POST['account_year'];
					$_SESSION['project_b'] = $_POST['project'];
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					
				}
				
				if ($_SESSION['project_b'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a']){
					$project_v = $_SESSION['project_b'];
					$budget_name_v = $_SESSION['budget_name_a'];
					$budget_head_v = $_SESSION['budget_head_a'];
					
				}
	
				if (!empty($_GET['reset']) || !empty($_SESSION['reset']) ) {
					$_SESSION['project_b'] = '';
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
				
				$comid  = $_SESSION['comid'];
			
//echo $project_v. ' >< '. $budget_name_v. ' >< '. budget_head_v ;

			?>
					<form class="form-horizontal" action="budget_used_SI.php?sub=pdf" target="_blank" method="post">
                      
								
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Fin.Year</label>
								<select class="form-control" name="account_year" id="account_year" required >
									<option value=""> Select </option>
									<option value="2021-2022" <?php echo ($account_year=='2021-2022')?'selected="selected"':'';?> >2021-2022</option>
									<option value="2022-2023" <?php echo ($account_year=='2022-2023')?'selected="selected"':'';?>>2022-2023</option>
									<option value="2023-2024" <?php echo ($account_year=='2023-2024')?'selected="selected"':'';?>>2023-2024</option>
									<option value="2024-2025" <?php echo ($account_year=='2024-2025')?'selected="selected"':'';?>>2024-2025</option>
									<option value="2025-2026" <?php echo ($account_year=='2025-2026')?'selected="selected"':'';?>>2025-2026</option>
								</select>
							</div>
							
							<div class="col-sm-4">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required onchange="getcostcenter(this.value);" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Cost Center Group</label>
							<span id="getcostcenter">	
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
									
								</select>
							</span>	
							</div>

							
							<div class="col-md-3">
									<label class="control-label">Cost Center Sub Group</label>
								<span id="getcostcentergroup">	
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
									
									</select>
								</span>		
							</div>
						</div>
												
						<div class="form-group">
							<div class="col-xs-3">
							</div>
							
							<div class="col-xs-1">
                                		
								<input class="btn btn-success" target="_blank" type="submit" value="Export" name="Save">&nbsp;&nbsp;&nbsp;
								
							
							</div>
							
							<div class="col-xs-1">
                                		
								<a href="<?php echo $baseurl . "dashboard.php"?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
							
							</div>

						</div>
						
				</form>

			</div>
			
		</div>	
    <div class="box">
    
	</div>
    </div>
</div>	

<?php
	include("../footer.php");	
	
?>

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


	function getcostcenter(id){
		
        var sub    = 'sub12';
		
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{company_id:id,sub12:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcentergroup(id){
		
        var sub    = 'sub10';
		var company_id  = document.getElementById("PROJECT").value;
		var account_year  = document.getElementById("account_year").value;
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,account_year:account_year,company_id:company_id,sub10:sub},function(result){
		      $('#getcostcentergroup').html(result);
		});

	}

</script>

<?php

   }
?>

<?php if($_GET['sub'] == 'pdf'){	

	include("../dbcon.php");

		$account_year			= $_POST['account_year'];
		$budget_name_v 			= $_POST['budget_name'];
		$budget_head_v 			= $_POST['budget_head'];
		
//exec('mysqldump --user=... --password=... --host=... DB_NAME > /path/to/output/file.sql');
//mysqldump -u <db_username> -h <db_host> -p db_name table_name > table_name.sql
//exec('mysqldump -h localhost -u athaangp2p -p 12345 athaangp2p sma_budget > athaangp2p_A.sql');

		$sql = "truncate budget_view";
		mysqli_query($con, $sql);
		
//Approval Memo Start
		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var'
			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
			WHERE 1 and a.id = b.approval_hdr_id and b.budget_id = c.id and a.del !='Y' 
			  AND a.approval_status !='Rejected' 
			  AND c.account_year = '$account_year' ";
	
//echo $sql."<BR>";	//and a.company = '$project_v' and b.budget_id = 60
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'AP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 		= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}
	
//exit();
//Approval Memo End


//Purchase Order Start (only for PO cum Approval)
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company, to_supplier as supplier_id, po_number, approval_memo_ref, po_type as 'check_var'
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 and a.id = b.purchase_id 
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND c.account_year = '$account_year' "; //and po_type = 'C'
	
//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 			= 'B';
		$doc_type			= 'PO';
		$doc_no 			= $row['id'];
		$doc_date			= $row['dated'];
		$company_id			= $row['company'];
		$po_number 			= $row['po_number']; 
		$approval_memo_ref 	= $row['approval_memo_ref'];
		$check_var 			= $row['check_var'];
		$supplier_id		= $row['supplier_id'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name = $r3['party_name'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, po_number, approval_no, party_name, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', 
					'$po_number', '$approval_memo_ref', '$party_name', '$budget_id', '$product_name', 
					'$amount', '0', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);

//echo $sql. "<BR>";
	
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, suplier_name as supplier_id, supplier_invoice_no as 'invoice_no' , our_po_ref_no ,  '' as 'check_var' 
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND c.account_year = '$account_year' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 			= 'C';
		$doc_type			= 'SI';
		$doc_no 			= $row['id'];
		$doc_date			= $row['dated'];
		$company_id			= $row['company'];
		$supplier_id		= $row['supplier_id'];
		$invoice_no 		= $row['invoice_no'];
		$our_po_ref_no  	= $row['our_po_ref_no'];
		$check_var 			= $row['check_var'];
		
		$sql = "SELECT * FROM sma_purchase_order WHERE id = '$our_po_ref_no' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$po_number 		= $r3['po_number']; 
		$approval_no	= $r3['approval_memo_ref'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name = $r3['party_name'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
		
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, party_name, invoice_no, approval_no, po_number, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$party_name', '$invoice_no', '$approval_no', '$po_number', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, emp_id as supplier_id, a.company_id as company , a.approval_number as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'C'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND c.account_year = '$account_year' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'OP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		$supplier_id		= $row['supplier_id'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name = $r3['party_name'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, party_name, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$party_name', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End
	
	
//Travel Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, emp_id as supplier_id, a.company_id as company , '' as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'T'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND c.account_year = '$account_year' ";


//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'TE';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		$supplier_id		= $row['supplier_id'];
		
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name = $r3['username'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, party_name, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
				VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$party_name', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Travel Expense End
	

//Regular Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, emp_id as supplier_id, a.company_id as company , '' as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and a.exp_type = 'R'
					and b.budget_id = c.id and a.del !='Y' 
					AND a.approval_status !='Rejected' 
					AND c.account_year = '$account_year' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$doc_type		= 'RE';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var 			= $row['check_var'];
		$supplier_id		= $row['supplier_id'];
		
		$sql = "SELECT * FROM sma_user WHERE id = '$supplier_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_name = $r3['username'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, party_name, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
				VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date', '$party_name', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Regular Expense End
	
	
	//	exit();
	
?>


	
<?php		
	

	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='8'> Used Budget Transaction </th></tr></table>";
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr>
					<th>Comp Code</th>
					<th>Doc Type.</th>
					<th>Doc.No.</th>
					<th  style='text-align:left;'>Dated</th>
					<th>Supplier Name</th>
					<th>Invoice No.</th>
					<th>Approval Memo No.</th>
					<th>PO.Number</th>
					<th>Party/Items/Expense</th>
					<th>Cost Center Group</th>
					<th>Cost Center Sub Group</th>
					<th  style='text-align:right;'>Blocked</th>
					<th  style='text-align:right;'>Consumed</th>
					<th  style='text-align:right;'>Adjustment</th>
					<th  style='text-align:right;'>Balance</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
		
	//$sql = " SELECT * FROM budget_view WHERE 1 order by budget_id, doc_date, doc_type, doc_no "; //sort_type, doc_type, budget_id,
	
	$sql = " SELECT a.doc_type, a.doc_no, a.items, a.budget_id, a.check_var, a.doc_date, 
			a.party_name, a.invoice_no, a.approval_no, a.po_number, a.comp_code,
			a.blocked_budget, a.used_budget, a.adjustment_budget, b.total_budget 
		FROM budget_view a, sma_budget b 
			WHERE  1 and b.id = a.budget_id 
			AND account_year = '$account_year' ";
				
	$project_v = $_POST['project'];
	$sql .= "and b.project = '$project_v' ";
//	and b.budget_name = '$budget_name_v' and b.budget_head = '$budget_head_v' 
	if( !empty($budget_name_v) ){
		$sql .= " and b.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and b.budget_head = '$budget_head_v' ";
	}

	$sql .= " ORDER BY budget_id, doc_date, doc_type, doc_no ";
	
//echo $sql." ##0<BR>";
//exit();

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$doc_type	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];		
		$product_name 		= $row['items'];
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		
		$party_name			= $row['party_name'];
		$invoice_no			= $row['invoice_no'];
		$approval_no		= $row['approval_no'];
		$po_number			= $row['po_number'];
		$comp_code 			= $row['comp_code'];
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		
		if($budget_id_prev	!= $budget_id){

			$total_budget 		= $row['total_budget'];
			$running_balance_budget = $total_budget;
			
			$sql = " SELECT b.budget_head, a.name as budget_name FROM sma_budget_name a, sma_budget b 
						WHERE 1 and b.budget_name = a.id and b.id = '$budget_id' ";
			$re = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($re);
			$budget_head = $r2['budget_head'];
			$budget_name = $r2['budget_name'];
			
			$sql="SELECT * FROM sma_budget_subgroup where id = '$budget_head' ";
			$res2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$cat = mysqli_fetch_array($res2);
			$budget_head = $cat['budget_head'];
			
			$message .= "<tr>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='15%' style='text-align:left;'><b>Opening Balance</b></td>
				<td width='10%' style='text-align:left;'>$budget_name</td>
				<td width='10%' style='text-align:left;'>$budget_head</td>
				
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:left;'></td>
				<td width='10%' style='text-align:right;'>". moneyFormatIndia($total_budget)."</td>
			</tr> ";

//echo $sql." ##1<BR>";
		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];

		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
		
			$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
			if($check_var=='C'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
			}
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		
		$message .= '<tr>
			<td width="10%" style="text-align:left;">'. $comp_code.'</td>
			<td width="10%" style="text-align:left;">'. $doc_type.'</td>
			<td width="10%" style="text-align:left;">'. $doc_no.'</td>
			<td width="10%" style="text-align:left;">'. $dated.'</td>
			<td width="10%" style="text-align:left;">'. $party_name.'</td>
			<td width="10%" style="text-align:left;">'. $invoice_no.'</td>
			<td width="10%" style="text-align:left;">'. $approval_no.'</td>
			<td width="10%" style="text-align:left;">'. $po_number.'</td>
			<td width="15%" style="text-align:left;">'. $product_name.'</td>
			<td width="15%" style="text-align:left;">'. $budget_name.'</td>
			<td width="15%" style="text-align:left;">'. $budget_head.'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($blocked_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($used_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($adjustment_budget).'</td>
			<td width="10%" style="text-align:right;">'. moneyFormatIndia($running_balance_budget).'</td>
			</tr>';
	
	}
		
//echo $message. "<BR>";
//exit();
		
//echo "Budget Process Over.."."<BR>";	

    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	//if($prn=='excel'){
		$fl_name = 'budget_used_trans_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	//}
	
///

} 


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
