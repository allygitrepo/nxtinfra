<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Data Dump 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Data Dump</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="data_dump.php?sub=pdf" method="post">
                      
							<div class="form-group">
								<div class="col-sm-4">
									<label for="project" class="control-label">Module</label>
									<select class="form-control " name="module" id="module" >
										<option value=""> Select </option>
										<option value="PO" > Purchase Order </option>
										<option value="SI" > Supplier Invoice </option>
										<option value="CE" > Company Expense </option>
										<option value="TE" > Travel Expense </option>
										<option value="RE" > Regular Expense </option>
										<option value="PC" > Petty Cash </option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
				</form>
<?php 

	include("../footer.php");

}
 
if($_GET['sub'] == 'pdf'){
	session_start();
	include "../dbcon.php";
	include "../baseurl.php";
	
	$module = $_POST['module'];
echo $module. "<BR>";	
	$sql = "truncate data_dump";
	mysqli_query($con, $sql);

if($module=='PO'){
//Purchase Order Start 

$doc_type =''; 
$comp_name='';
$doc_date=''; 
$doc_no='';
$supplier_emp_name=''; 
$po_invoice_number='';
$product_name='';
$quantity='';
$received_si_qty='';
$rate='';
 $gst='';
$order_amount='';
$received_si_amount='';
$status='';
$budget_name='';
$budget_head='';
$analysis_group='';
$analysis_sub_group='';

	$sql = " SELECT * FROM `sma_purchase_order` WHERE 1 order by id"; //and po_type = 'C'

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type			= 'Purchase Order';
		$doc_no 			= $row['id'];
		$doc_date			= $row['dated'];
		$company_id			= $row['project'];
		$supplier_emp_id	= $row['to_supplier'];
		$po_invoice_number	= $row['po_number'];
		$del				= $row['del'];
		$status				= $row['status'];
		
		if($del=='Y'){
			$status				= 'Deleted';	
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_name = $r3['comp_name'];
		$comp_name = $r3['comp_code'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_emp_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$supplier_emp_name = $r3['party_name'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$analysis_sub_group	= $r2['analysis_sub_group'];
			$budget_id			= $r2['budget_id'];
			$received_si_qty	= $r2['bal_si_qty'];
			$received_si_amount	= $r2['bal_si_amount'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$order_amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	= $r3['name'];
			
			$sql  = " SELECT b.name as budget_name, a.budget_head
					FROM sma_budget a, sma_budget_name b
						WHERE a.budget_name = b.id 
							AND a.id = '$budget_id' ";
							
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$budget_name	= $r3['budget_name'];
			$budget_head	= $r3['budget_head'];
			
			$sql  = " SELECT a.description, a.analysis_code, b.analysis_group as analysis_group, b.code 
					FROM analysis_sub_group a, analysis_group_mst b 
						WHERE a.analysis_group = b.id 
							AND a.id = '$analysis_sub_group' ";
						
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$analysis_sub_group	= $r3['description'].'-'.$r3['analysis_code'];
			$analysis_group		= $r3['analysis_group'].'-'.$r3['code'];
			
			$sql = " INSERT INTO data_dump (doc_type, company_name, doc_date, doc_no, `supplier_emp_name`,`po_invoice_number`,`product_expense_name`,
				`order_qty`,`received_qty`,`rate`,`gst`,`amount_value`, received_amount_value, `status`,`budget_name`, `budget_head`,`analysis_group`,`analysis_sub_group`) 
				VALUES( '$doc_type', '$comp_name', '$doc_date', '$doc_no', '$supplier_emp_name', 
					'$po_invoice_number', '$product_name', '$quantity', '$received_si_qty', 
					'$rate', '$gst', '$order_amount', '$received_si_amount', '$status', 
					'$budget_name', '$budget_head', '$analysis_group', '$analysis_sub_group' )";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		

}			
//Purchase Order End


if($module=='SI'){
//Supplier Invoice Start 

$doc_type =''; 
$comp_name='';
$doc_date=''; 
$doc_no='';
$supplier_emp_name=''; 
$po_invoice_number='';
$product_name='';
$quantity='';
$received_si_qty='';
$rate='';
 $gst='';
$order_amount='';
$received_si_amount='';
$status='';
$budget_name='';
$budget_head='';
$analysis_group='';
$analysis_sub_group='';
	$sql = " SELECT * FROM `sma_supplier_invoice` WHERE 1 order by id";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type			= 'Supplier Invoice';
		$doc_no 			= $row['id'];
		$doc_date			= $row['invoice_date'];
		$company_id			= $row['company'];
		$supplier_emp_id	= $row['suplier_name'];
		$po_invoice_number	= $row['supplier_invoice_no'];
		$del				= $row['del'];
		$status				= $row['status'];
		
		if($del=='Y'){
			$status				= 'Deleted';	
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_name = $r3['comp_name'];
		$comp_name = $r3['comp_code'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_emp_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$supplier_emp_name = $r3['party_name'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];
			$analysis_sub_group	= $r2['analysis_sub_group'];
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			
			$order_amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	= $r3['name'];
			
			$sql  = " SELECT b.name as budget_name, a.budget_head
					FROM sma_budget a, sma_budget_name b
						WHERE a.budget_name = b.id 
							AND c.id = a.budget_category
							AND a.id = '$budget_id' ";
	
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$budget_name	= $r3['budget_name'];
			$budget_head	= $r3['budget_head'];
			
			$sql  = " SELECT a.description, a.analysis_code, b.analysis_group as analysis_group, b.code 
					FROM analysis_sub_group a, analysis_group_mst b 
						WHERE a.analysis_group = b.id 
							AND a.id = '$analysis_sub_group' ";
						
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$analysis_sub_group	= $r3['description'].'-'.$r3['analysis_code'];
			$analysis_group		= $r3['analysis_group'].'-'.$r3['code'];
			
			$sql = " INSERT INTO data_dump (doc_type, company_name, doc_date, doc_no, `supplier_emp_name`,`po_invoice_number`,`product_expense_name`,
				`order_qty`,`received_qty`,`rate`,`gst`,`amount_value`, received_amount_value, `status`,`budget_name`, `budget_head`,`analysis_group`,`analysis_sub_group`) 
				VALUES( '$doc_type', '$comp_name', '$doc_date', '$doc_no', '$supplier_emp_name', 
					'$po_invoice_number', '$product_name', '$quantity', '$received_si_qty', 
					'$rate', '$gst', '$order_amount', '$received_si_amount', '$status', 
					'$budget_name', '$budget_head', '$analysis_group', '$analysis_sub_group' )";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}
}
//Supplier Invoice End 


if($module=='CE' || $module=='TE' || $module=='RE'){
//Travel Expenses Start 

$doc_type =''; 
$comp_name='';
$doc_date=''; 
$doc_no='';
$supplier_emp_name=''; 
$po_invoice_number='';
$product_name='';
$quantity='';
$received_si_qty='';
$rate='';
$gst='';
$order_amount='';
$received_si_amount='';
$status='';
$budget_name='';
$budget_head='';
$analysis_group='';
$analysis_sub_group='';
	$sql = " SELECT * FROM `sma_travel_expenses` WHERE 1 order by id";

echo $sql."<BR>";

	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$exp_type 		= $row['exp_type'];

		if($exp_type=='T' && $module=='TE'){
			$doc_type		= 'Travel Expense';
		}
		else if($exp_type=='C' && $module=='CE'){
			$doc_type		= 'Company Expense';
		}
		else if($exp_type=='R' && $module=='RE'){
			$doc_type		= 'Regular Expense';
		}
		else {
			
			continue;	
		}

		$doc_no 			= $row['id'];
		$doc_date			= $row['dated'];
		$company_id			= $row['company_id'];
		$supplier_emp_id	= $row['emp_id'];
		
		if(empty($company_id) && empty($supplier_emp_id) ){
			continue;
		}

		$supplier_emp_id	= $row['onbehalf_emp_id'];
		
		$del				= $row['del'];
		$status				= $row['status'];
		
		if($del=='Y'){
			$status				= 'Deleted';	
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_name = $r3['comp_name'];
		$comp_name = $r3['comp_code'];
		
		if($exp_type=='C'){
			$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_emp_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$supplier_emp_name = $r3['party_name'];
		}	
		else if($exp_type!='C'){
			$sql = "SELECT * FROM sma_user WHERE id = '$supplier_emp_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$supplier_emp_name = $r3['username'];
		}
		
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$analysis_sub_group	= $r2['analysis_sub_group'];
			$budget_id			= $r2['budget_id'];
			$po_invoice_number	= $r2['invoice_no'];
			$order_amount	    = $r2['amount'];
			$gst		 	    = $r2['gst_amount'];
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	= $r3['name'];
			
			$sql  = " SELECT b.name as budget_name, a.budget_head
					FROM sma_budget a, sma_budget_name b
						WHERE a.budget_name = b.id 
							AND c.id = a.budget_category
							AND a.id = '$budget_id' ";
	
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$budget_name	= $r3['budget_name'];
			$budget_head	= $r3['budget_head'];
			
			$sql  = " SELECT a.description, a.analysis_code, b.analysis_group as analysis_group, b.code 
					FROM analysis_sub_group a, analysis_group_mst b 
						WHERE a.analysis_group = b.id 
							AND a.id = '$analysis_sub_group' ";
						
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$analysis_sub_group	= $r3['description'].'-'.$r3['analysis_code'];
			$analysis_group		= $r3['analysis_group'].'-'.$r3['code'];
			
			$sql = " INSERT INTO data_dump (doc_type, company_name, doc_date, doc_no, `supplier_emp_name`,`po_invoice_number`,`product_expense_name`,
				`order_qty`,`received_qty`,`rate`,`gst`,`amount_value`, received_amount_value, `status`,`budget_name`, `budget_head`,`analysis_group`,`analysis_sub_group`) 
				VALUES( '$doc_type', '$comp_name', '$doc_date', '$doc_no', '$supplier_emp_name', 
					'$po_invoice_number', '$product_name', '$quantity', '$received_si_qty', 
					'$rate', '$gst', '$order_amount', '$received_si_amount', '$status', 
					'$budget_name', '$budget_head', '$analysis_group', '$analysis_sub_group' )";
			mysqli_query($con, $sql);
//echo $sql. "<BR>";			
//exit();
//echo $sql. "<BR>";
			
		}

	}		
}
//exit('Exit Here...');
//Travel Expense End

if($module=='PC'){
//Petty Cash Start 
$doc_type =''; 
$comp_name='';
$doc_date=''; 
$doc_no='';
$supplier_emp_name=''; 
$po_invoice_number='';
$product_name='';
$quantity='';
$received_si_qty='';
$rate=''; 
$gst='';
$order_amount='';
$received_si_amount='';
$status='';
$budget_name='';
$budget_head='';
$analysis_group='';
$analysis_sub_group='';

	$sql = " SELECT * FROM `sma_pettycash` WHERE 1 order by id";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$doc_type			= 'Petty Cash';
		$doc_no 			= $row['id'];
		$doc_date			= $row['dated'];
		$company_id			= $row['company_id'];
		$del				= $row['del'];
		$status				= $row['status'];
		
		if($del=='Y'){
			$status				= 'Deleted';	
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_name = $r3['comp_name'];
		$comp_name = $r3['comp_code'];
		
		$sql = "SELECT * FROM sma_party_mst WHERE id = '$supplier_emp_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$supplier_emp_name = $r3['party_name'];
			
		$sql = " SELECT * FROM `sma_pettycash_exp` WHERE approval_ref_no = '$doc_no' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['expense_id'];
			$budget_id			= $r2['budget_id'];
			$po_invoice_number	= $r2['invoice_no'];
			$analysis_sub_group	= $r2['analysis_sub_group'];
			$order_amount		= $r2['amount'];
			
			$paid_to  			= $r2['paid_to'];
			$supplier_emp_name 	= $r2['spend_by'];
			$spend_by		 	= $r2['spend_by'];
			
			$sql  = " SELECT * FROM account_mst WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	= $r3['account_name'];
							
				if($paid_to=='V'){
					$sql = "select * from sma_party_mst where id = '$spend_by' ";
					$res = mysqli_query($con, $sql);
					$r = mysqli_fetch_object($res);
					$supplier_emp_name		= $r->party_name;
				}
				else if($paid_to=='U'){
					$sql = "select * from sma_user where id = '$spend_by' ";
					$res = mysqli_query($con, $sql);
					$r = mysqli_fetch_object($res);
					$supplier_emp_name		= $r->username;
				}
											
			$sql  = " SELECT b.name as budget_name, a.budget_head
					FROM sma_budget a, sma_budget_name b
						WHERE a.budget_name = b.id 
							AND c.id = a.budget_category
							AND a.id = '$budget_id' ";
	
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$budget_name	= $r3['budget_name'];
			$budget_head	= $r3['budget_head'];
			
			$sql  = " SELECT a.description, a.analysis_code, b.analysis_group as analysis_group, b.code 
					FROM analysis_sub_group a, analysis_group_mst b 
						WHERE a.analysis_group = b.id 
							AND a.id = '$analysis_sub_group' ";
						
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$analysis_sub_group	= $r3['description'].'-'.$r3['analysis_code'];
			$analysis_group		= $r3['analysis_group'].'-'.$r3['code'];
			
			$sql = " INSERT INTO data_dump (doc_type, company_name, doc_date, doc_no, `supplier_emp_name`,`po_invoice_number`,`product_expense_name`,
				`order_qty`,`received_qty`,`rate`,`gst`,`amount_value`, received_amount_value, `status`,`budget_name`, `budget_head`,`analysis_group`,`analysis_sub_group`) 
				VALUES( '$doc_type', '$comp_name', '$doc_date', '$doc_no', '$supplier_emp_name', 
					'$po_invoice_number', '$product_name', '$quantity', '$received_si_qty', 
					'$rate', '$gst', '$order_amount', '$received_si_amount', '$status', 
					'$budget_name', '$budget_head', '$analysis_group', '$analysis_sub_group' )";
			mysqli_query($con, $sql);

		}

	}
}			
//Petty Cash End 

//exit('Exit Here...');

	$message .= "";
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'> Data Dump </th></tr></table>";
							
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 5%;'><b>Doc Type</b></td>
					<td style='width: 10%;'><b>Company</b></td>
					<td style='width: 5%;'><b>Doc Id</b></td>
					<td style='width: 10%;'><b>Date</b></td>	
					<td style='width: 10%;'><b>Vendor Name</b></td>
					<td style='width: 10%;'><b>Invoice No.</b></td>
					<td style='width: 10%;'><b>Product / Expense Name</b></td>
					<td style='width: 10%;'><b>Order Quantity</b></td>
					<td style='width: 10%;'><b>Received Quantity</b></td>
					<td style='width: 10%;'><b>Rate</b></td>
					<td style='width: 10%;'><b>GST</b></td>		
					<td style='width: 5%;'><b>Amount Value</b></td>
					<td style='width: 10%;'><b>Received Amount Value</b></td>
					<td style='width: 10%;'><b>Status</b></td>
					<td style='width: 10%;'><b>Budget Name</b></td>
					<td style='width: 10%;'><b>Budget Head</b></td>
					<td style='width: 10%;'><b>Analysis Group</b></td>
					<td style='width: 10%;'><b>Analysis Sub Group</b></td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
	$sql = " SELECT * FROM `data_dump` where 1 ";
//echo $sql."<BR>";

	$result = mysqli_query($con, $sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_type				= $row['doc_type'];
		$doc_no					= $row['doc_no'];
		$company_name			= $row['company_name'];
		$doc_date				= date('d-m-Y',strtotime($row['doc_date']));
		$supplier_emp_name		= $row['supplier_emp_name'];
		$po_invoice_number		= $row['po_invoice_number'];
		$product_expense_name	= $row['product_expense_name'];
		$order_qty				= $row['order_qty'];
		$received_qty			= $row['received_qty'];
		$rate					= $row['rate'];
		$gst					= $row['gst'];
		$amount_value			= $row['amount_value'];
		$received_amount_value	= $row['received_amount_value'];
		$status					= $row['status'];
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_head'];
		$analysis_group			= $row['analysis_group'];
		$analysis_sub_group		= $row['analysis_sub_group'];
				
		$message .= "<tr>
						<td style='width: 5%;'>$doc_type</td>
						<td style='width: 10%;'>$company_name</td>
						<td style='width: 5%;'>$doc_no</td>
						<td style='width: 10%;'>$doc_date</td>
						<td style='width: 10%;'>$supplier_emp_name</td>
						<td style='width: 10%;'>$po_invoice_number</td>
						<td style='width: 10%;text-align:right;'>$product_expense_name</td>
						<td style='width: 10%;'>$order_qty</td>
						<td style='width: 10%;'>$received_qty</td>
						<td style='width: 10%;'>$rate</td>
						<td style='width: 10%;'>$gst</td>
						<td style='width: 05%;'>$amount_value</td>
						<td style='width: 10%;'>$received_amount_value</td>
						<td style='width: 10%;'>$status</td>
						<td style='width: 10%;'>$budget_name</td>
						<td style='width: 10%;'>$budget_head</td>
						<td style='width: 10%;'>$analysis_group</td>
						<td style='width: 10%;'>$analysis_sub_group</td>
					";
		$message .= "</tr>";
		
	}
	
	$message .= "</table>";

//echo $message;
//exit();
	
    // get the HTML
		ob_start();
    
		$fl_name = 'data_dump_'.$module.'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	


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

}
?>
