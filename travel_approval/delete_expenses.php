<?php 
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
?>

<?php
	
	$approval_ref_no= $_GET['approval_ref_no'];
	$id				= $_GET['id'];
	$exp_type		= $_GET['exp_type'];

	$sql = "SELECT a.reference, a.amount, a.gst_amount, a.budget_name, a.budget_head , a.budget_id, b.company_id , b.status, b.approval_number, b.emp_id as vendor_id
					FROM `sma_expenses` a, sma_travel_expenses b
					where a.approval_ref_no = b.id and b.id = '$approval_ref_no' and a.id ='$id' "; //and b.exp_type = '$exp_type'
//echo $sql; exit();					
			$res 			= mysqli_query($con, $sql);
			while ($r 		= mysqli_fetch_object($res)){

				$exp_id			= $r->reference;
				$amount			= $r->amount;
				$gst_amount		= $r->gst_amount;
				$budget_name	= $r->budget_name;
				$budget_head	= $r->budget_head;
				$budget_id		= $r->budget_id;
				$company_id		= $r->company_id;
				$status			= $r->status;
				$approval_number= $r->approval_number;
				$vendor_id		= $r->vendor_id;
				
				//if( $status == 'Completed' ){
				//	$sql = " UPDATE `sma_budget` set used_budget = used_budget - $amount 
				//				where id = '$budget_id' ";
				//	mysqli_query($con, $sql);
				//}
				$tot_amount = $amount + $gst_amount;	
				if(!empty($approval_number) && $exp_type =='C'){
					$sql = "UPDATE sma_budget SET blocked_budget = blocked_budget + $tot_amount, used_budget = used_budget - $tot_amount  
								WHERE id = '$budget_id' ";
		//echo $sql."<BR>";				
					mysqli_query($con, $sql);
					
					$sql = "UPDATE sma_approval_items SET bal_amount = bal_amount - $tot_amount 
								WHERE approval_hdr_id = '$approval_number' 
									and product_id = '$exp_id' and supplier_id = '$vendor_id' ";
		//echo $sql."<BR>";			
					mysqli_query($con, $sql);
					
				}
				else {
					$sql = "update sma_budget set used_budget = used_budget - $tot_amount where id = '$budget_id' ";
					mysqli_query($con, $sql);
				}
				
			}
			
	$sql    = " delete from sma_expenses where id ='$id' ";
	$result = mysqli_query($con, $sql);

	//$baseurl1 	= $baseurl.$modulePath1.'travel_expence.php?sub=edit&id='.$row["id"];
	if($exp_type=='R'){
		echo "<script>window.location.href='regular_expense.php?sub=edit&id=$approval_ref_no';</script>";
	}
	if($exp_type=='C'){
		echo "<script>window.location.href='company_expense.php?sub=edit&id=$approval_ref_no';</script>";
	}
	else{
		echo "<script>window.location.href='travel_expence.php?sub=edit&id=$approval_ref_no';</script>";
	}
?>