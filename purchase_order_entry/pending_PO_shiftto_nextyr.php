<?php session_start();
	include('../dbcon.php');
	include("../baseurl.php");
	$modulePath = "purchase_order_entry/";	
?>

<?php

		$remarks 		= $_POST['remarks'];
		$po_id  		= $_POST['po_id'];
		$old_po_no 		= $po_id;
				//'Draft', 'Submitted'
	$sql = " SELECT distinct(b.id) as id_number, b.* 
			FROM `sma_po_items` a , sma_purchase_order b 
				where a.purchase_id = b.id and a.quantity > a.bal_si_qty and status in ( 'Completed' )  and dated < '2022-04-01' and b.id = 28 " ;
echo $sql."<BR>";//and b.id in ( 2, 9, 13 )
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){ 
		
		$company_id 		= $row['project'];
		$dated 				= $row['dated'];
		$old_po_no 			= $row['id'];
		$po_no 				= $row['id'];
		$po_number 			= $row['po_number'];
		$company_id 		= $row['project'];
		$department			= $row['department'];
		$po_doc_type		= $row['po_doc_type'];
		$po_number			= $row['po_number'];
		$po_rev				= $row['po_rev'] + 1;
		$user_maker			= $row['draft_by'];
		
			$sql = "SELECT * FROM company where comp_id = '$company_id' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$comp_code 			= $r2['comp_code'];
			$budget_control_gst = $r2['budget_control_gst'];
			$doc_type 			= 'PO';
			
//Budget Validation Start
	$dated = date('Y').'-04-01';
	$fyr		= date('Y', strtotime($dated));
	$fmth		= date('m', strtotime($dated));
	$fin_year	= '';
	if($fmth>=1 && $fmth<=3){
		$styr = $fyr - 1;
		$fin_year = $styr . '-'. $fyr;
		$yyyy = $styr . '-'. date('y');
	}
	else {
		$ltyr = $fyr + 1;
		$fin_year = $fyr . '-'. $ltyr;
		$yyyy = date('Y'). '-'. (date('y')+1);
	}
	
	    $sql 	= "update sma_purchase_order set status = 'Suspend', `approval_status` = 'Suspend' 	where id = '$old_po_no' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con); 
echo $sql. "<BR>";

		$sql 	= "select * from sma_user where userid = ( select draft_by from sma_purchase_order where id = '$old_po_no' ) ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($query1);
		$makerid = $r2['id'];
		
		$userid   	= '3';//Admin
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'PO', '$old_po_no', '$userid', now(), 'Suspend', '$makerid', '', now(), 'Balance PO Shift to Next Year' )";			
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Shift to NExt Year ...");}

echo $sql. "<BR>";
//exit();

//Shift to Next Year Start
	
			$revno = $po_rev;
			//$po_number = $comp_code.'/'.$dept_code.'/'.$prefix.'/'.$yyyy.'/'.$po_last_number.'/'.$revno;
			$po_number = $po_number .'/'.$revno;
		$sql = " INSERT into sma_purchase_order(`po_doc_type`, po_type, `po_number`, `po_rev`, trans_type, `approval_memo_ref`, `dated`, `project`, `location`, supplier_location, `delivery_address`, `department`, `budget_name`, `budget_head`, subject, notes, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, `status`, `approval_status`, `draft_by`, `draft_date`, `changed_by`, `changed_date`, `name_of_person`, `item_name`, `description_product_category`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag`, cf_po)  
		SELECT `po_doc_type`, po_type, '$po_number', '$po_rev', trans_type, `approval_memo_ref`, '$dated', `project`, `location`, supplier_location, `delivery_address`, `department`, `budget_name`, `budget_head`, subject, notes, `advance_amount`, `against_indent_no`, `quotation_reference_no`, `to_supplier`, `advance_flag`, `paid_amount`, `paid_status`, `delivery_days`, `credit_days`, `discount`, `transport`, `other_charges`, `payment_terms`, 'Completed', 'Approved', '$user_maker', now(), '', '', `name_of_person`, `item_name`, `description_product_category`, `delivery_date`, `header_text`, `terms`, `prepared_by`, `approved_by`, `checked_by`, `attachment_files`, `flow_flag`, `close_flag`, `print_flag` ,'Y' from sma_purchase_order where id = '$old_po_no' ";
echo $sql."<BR>";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$new_po_no = mysqli_insert_id($con);

			
		//Shift to Next Year PO
		$sql = " update sma_purchase_order set old_po_no = '$old_po_no' where id = '$new_po_no' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql."<BR>";

		//Old PO Updated as Short Close
		$sql = " update sma_purchase_order set new_po_no = $new_po_no, status = 'Suspend', `approval_status` = 'Suspend', po_amend='Y' where id = '$old_po_no' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql."<BR>";

		$sql = "Insert into `sma_po_items` (`purchase_id`,`account_year`,`company_id`,`product_id`,`product_code`,`product_name`,`product_desc`, `product_category`,`budget_id`,`budget_name`,`budget_head`,`total_budget`, `balance_budget`, `quantity`, `uom`, `unit_rate`, `gst`, gst_id, `delivery_date`, first_insert) 
		SELECT '$new_po_no', `account_year`, `company_id`, `product_id`, `product_code`, `product_name`, `product_desc`, `product_category`, `budget_id`, `budget_name`, `budget_head`, `total_budget`, `balance_budget`, (`quantity` - bal_si_qty ) as qty, `uom`, `unit_rate`, `gst`, gst_id, `delivery_date` , 'F' FROM `sma_po_items` WHERE purchase_id = '$old_po_no' and `quantity` > bal_si_qty ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql."<BR>";
	
	$sql="SELECT * from sma_po_items where purchase_id = '$new_po_no' ";
	$resultd = mysqli_query($con, $sql);
	echo mysqli_error($con);
echo $sql."<BR>";	
	$value="";
	$tot_amount 		  = 0 ;
	$budget_adjust_amount = 0 ;
	while($rowd = mysqli_fetch_array($resultd)){
		$po_dtl_id 		= $rowd['id'];
		$product_id 	= $rowd['product_id'];
		$bal_si_qty 	= $rowd['bal_si_qty'];
		$qty 			= $rowd['quantity'];
		$budget_head	= $rowd['budget_head'];
		$old_budget_id	= $rowd['budget_id'];	
		$rate 			= $rowd['unit_rate'];
		$gst			= $rowd['gst'];
		
		$gstamt 		= round((($qty * $rate) * $gst / 100),0);
		if($budget_control_gst!='Y'){
			$gstamt = 0;
		}
		
		$po_amount 		= $qty * $rate + $gstamt;
		if( $po_amount <= 0 ){
			$po_amount = 0;
		}

		/* $sql = "UPDATE sma_budget SET blocked_budget = blocked_budget - $po_amount, adjustment_budget = adjustment_budget - $po_amount WHERE id = '$old_budget_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con); */
//echo $sql."<BR>";
		$sql = "select * from sma_budget where id = '$old_budget_id' ";
echo $sql. "<BR>";		
		$q4  = mysqli_query($con, $sql);
		$r4  = mysqli_fetch_object($q4);
		$block_budget   = $r4->blocked_budget;
		$budget_head    = $r4->budget_head;
		$budget_name    = $r4->budget_name;
		/* if( $block_budget < 0 ){
			$sql = "update sma_budget set block_budget = 0 where id = '$old_budget_id' ";
			mysqli_query($con, $sql);
		} */
		
		$sql = "select * from sma_budget where budget_head = '$budget_head' and budget_name = '$budget_name' and account_year = '$fin_year' ";
echo $sql. "<BR>";			
		$q4  = mysqli_query($con, $sql);
		$r4  = mysqli_fetch_object($q4);
		$blocked_budget   	= $r4->blocked_budget;
		$budget_head      	= $r4->budget_head;
		$budget_name      	= $r4->budget_name;
		$budget_code		= $r4->budget_code;
		$new_budget_id    	= $r4->id;
		
		$sql = "UPDATE sma_budget SET blocked_budget = blocked_budget + $po_amount, adjustment_budget = adjustment_budget + $po_amount WHERE id = '$new_budget_id' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql. "<BR>";
	
		$sql="UPDATE sma_po_items SET budget_id = '$new_budget_id' where id = '$po_dtl_id' AND purchase_id = '$new_po_no' ";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql. "<BR>";
	
		$budget_adjust_amount = $budget_adjust_amount + $po_amount;

	}

	$remarks_var = 'Against last year 21-22 pending PO '.$old_po_no ;
	$sql = "INSERT into budget_adjust (fin_year, dated, project, budget_name, budget_head, budget_code, budget_id , effect, amount, adjust_type, remarks ) VALUES ( '$fin_year', '2023-04-01', '$company_id', '$budget_name', '$budget_head', '$budget_code', '$new_budget_id', 'I', '$budget_adjust_amount', '1' , '$remarks_var' ) "; 
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
echo $sql. "<BR>";

	$sql = "INSERT into file_uploads (`module`, `file_name`,`file_path`, `doc_type`, `doc_desc`, `reference_id`, `date_uploaded`, `doc_invoice_no`) SELECT `module`, `file_name`, `file_path`, `doc_type`, `doc_desc`, '$new_po_no', now(), `doc_invoice_no` FROM `file_uploads` where module = 'PO' and reference_id = '$old_po_no' "; 
	$rt = mysqli_query($con, $sql);
	echo mysqli_error($con);
echo $sql."<BR>";
	
		$remarks = 'Balance PO Shift to Next Year Old PO Srno. '. $old_po_no ;
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, approved_date, remarks ) 
		values( 'PO', '$new_po_no', '$makerid', now(), 'Completed', '', '', now(), '$remarks' )";
		$query=mysqli_query($con, $sql);
		if(!empty($error)){echo $error; exit(" Shift to Next Year ...");}
echo $sql. "<BR>";		
		

//Shift to Next Year End
		
}

echo  "Process Shift to Next Year End	";

//$srno = $new_po_no;
//exit();

?>		


