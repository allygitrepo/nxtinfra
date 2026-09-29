<?php
	
	session_start();
	include('../dbcon.php');

?>

<?php

	if(isset($_POST['sub1'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$product_id     = $_POST['product_id'];
		$purchase_id 	= $_POST['purchase_id'];
		$product_name 	= $_POST['product_name'];
        $company_id     = $_POST['company_id'];
		$budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		
		$total_budget   = $_POST['total_budget'];
		$balance_budget = $_POST['balance_budget'];
		$budget_id    	= $_POST['budget_id'];
		
		$description 	= $_POST['description'];
		$quantity 		= $_POST['quantity'];
		$units 			= $_POST['units'];
		$rate 			= $_POST['rate'];
		$gst 			= $_POST['gst'];
		$gst_id			= $_POST['gst_id'];
		
		$sql 	= "select * from gst_mst where 1 and igst = '$gst' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$gst_id 		= $r22['id'];
									
		if(empty($gst)){
			$gst =0;
		}
		
		$gstamt 		= round((($rate * $quantity) * $gst / 100),0);
		
		$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		$approval_memo_ref	= $r2['approval_memo_ref'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$amount			= ($rate * $quantity) + $gstamt;

		$delivery_date 	= date('Y-m-d', strtotime($_POST['deliverydate']));
							
		$sql = "SELECT * FROM `sma_product` where  id = '$product_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$po_threashold 	= $r2['po_threashold'];
		$category 		= $r2['category'];
					
		$sql = " insert into `sma_po_items` ( purchase_id, product_id, product_name, product_desc,  company_id, budget_name, budget_head, quantity, uom, unit_rate, gst, gst_id, total_budget, balance_budget, budget_id, product_category, po_threashold , delivery_date )
		values ( '$purchase_id', '$product_id', '$product_name', '$description', '$company_id', '$budget_name', '$budget_head', '$quantity', '$units', '$rate', '$gst', '$gst_id', '$total_budget', '$balance_budget', '$budget_id' , '$category', '$po_threashold' , '$delivery_date' ) ";
//echo $sql."<BR>";	
		$error_msg ='';
		$_SESSION['error_msg']='';
		$r2 = mysqli_query($con, $sql);
		echo $error_msg = mysqli_error($con);		
		if(!empty($error_msg)){
			echo $error_msg . ' Duplicate Entry Error !!!';
			$_SESSION['error_msg'] = $error_msg;
			$value = "<script>window.location.href='edit.php?id=$purchase_id&912';</script>";
		    echo $value;
			exit();
		}
		
		if($po_type=='C'){
			$sql = "select * from sma_budget where id = '$budget_id' ";
			$q4  = mysqli_query($con, $sql);
			$r4  = mysqli_fetch_object($q4);
			$block_budget   = $r4->blocked_budget;
			$used_budget    = $r4->used_budget;
			$total_budget   = $r4->total_budget;
			$budget_adjustment	= $r4->budget_adjustment;
			$bal_budget 	= ($total_budget + $budget_adjustment) - ($block_budget + $used_budget);
			if($amount > $bal_budget ){
				$sql = "update sma_po_items set budget_err = 'Y' where id = '$po_dtl_id' ";
				mysqli_query($con, $sql);
			}
			else {
				$sql = "update sma_budget set blocked_budget = blocked_budget + $amount where id = '$budget_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
			}
		}
//echo $sql."<BR>";		
//exit();
		if($po_type=='A'){
		//approval_hdr_id  Approval Quantity and Value Update
			$sql = "UPDATE sma_approval_items SET po_quantity = '$quantity', po_value = '$amount' where approval_hdr_id = '$approval_memo_ref' and product_id = '$product_id' ";
			$q4  = mysqli_query($con, $sql);
		}


		$sql = "select sum((quantity * unit_rate) + (((quantity * unit_rate) * gst) /100)) as total_po_amount from sma_po_items where purchase_id = '$purchase_id' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$total_po_amount 		= $r22['total_po_amount'];
			
		$sql = " UPDATE sma_purchase_order set total_po_amount = '$total_po_amount' where id = '$purchase_id' "; 
		mysqli_query($con, $sql);
			
//echo $sql."<BR>";
//exit();
//		echo "<meta http-equiv='refresh' content='0'>";    
//exit();
		//$value = "<script>window.location.href='edit.php?id=$purchase_id&active=active&999';</script>";
		$value = "<script>window.location.href='edit.php?id=$purchase_id&999';</script>";
		echo $value;
		
	}

	if(isset($_POST['sub2'])){
	
		$value ='';
        $id = $_POST['id'];
		$poid = $_POST['poid'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$sql="delete from sma_po_items where id = '$id' ";
//$value1=$sql;
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);

		echo "<meta http-equiv='refresh' content='0'>";    
		
//$value = $value1;
		echo $value;
		
	}
	
	if(isset($_POST['sub3'])){
	
		$value ='';
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$product_id     = $_POST['id'];
		$rid     		= $_POST['rid'];
		$purchase_id 	= $_POST['purchase_id'];
		$name 			= $_POST['name'];
		$description 	= $_POST['description'];
		$quantity 		= $_POST['quantity'];
		$units 			= $_POST['units'];
		$rate 			= $_POST['rate'];
		$gst 			= $_POST['gst'];
		$amount 		= $_POST['amount'];
		$deliverydate 	= date('Y-m-d', strtotime($_POST['deliverydate']));
							
		$sql = "update `sma_po_items` set product_id='$product_id', product_name='$name', 
					product_desc='$description', quantity='$quantity', uom='$units',
					unit_rate='$rate', gst='$gst', delivery_date='$deliverydate' 
				where purchase_id = '$purchase_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
/*
		$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
//$value1=$sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		while($row = mysqli_fetch_array($result)){
			$qty 	= $row['quantity'];
			$rate 	= $row['rate'];
			$gst	= $row['gst'];
			$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
			$rid = $row['id'];		
			
			$value .= "<tr>
					 <td width='15%'>".$row['product_name']."</td>
					 <td width='15%'>".$row['product_desc']."</td>	
					 <td width='10%'>".$row['quantity']."</td>	
					 <td width='10%'>".$row['uom']."</td>	
					 <td width='10%'>".$row['unit_rate']."</td>	
					 <td width='10%'>".$row['gst']."</td>
					 <td width='10%'>".$amount."</td>					 
					 <td width='10%'>".date('d-m-Y', strtotime($row['delivery_date']))."</td>	
					 <td>
					 
						<a href='#modalEditItem' data-id='$rid' data-mode='edit' data-toggle='modal' data-target='#modalEditItem$rid' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;

						<a href='#modalDeleteItem' id='delete-$purchase_id$rid' data-toggle='modal' data-id='$purchase_id$rid' data-target='#modalDeleteItem$purchase_id$rid'><i class='fa fa-trash-alt'></i></a></td>
<!-- Modal Delete Item-->
<div class='modal fade' id='modalDeleteItem$purchase_id$rid' role='dialog' aria-labelledby='modalDeleteItemLabel'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span>
                </button>
                <h4 class='modal-title' id='modalDeleteItemLabel'>Delete Material of Purchase Order - NEW</h4>
            </div>
            <input type='hidden' id='itemTempId'>
            <div class='modal-body' id='modalDeleteContent'>
                Are you sure you want to delete item $purchase_id $rid ?
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>No</button>
                <button type='button' class='btn btn-danger' id='btnDeleteItemYes' onclick='delete_poItem($purchase_id,$rid)' >Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Item-->
						
					 </td>	
					 </tr>";
		}
	*/
	
//$value = $value1;
		echo $value;
		
	}

?>		