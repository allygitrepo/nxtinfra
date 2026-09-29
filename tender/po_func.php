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
		$tender_id 		= $_POST['tender_id'];
		$product_name 	= $_POST['product_name'];
        $budget_name    = $_POST['budget_name'];
		$budget_head    = $_POST['budget_head'];
		
		$description 	= $_POST['description'];
		$quantity 		= $_POST['quantity'];
		$units 			= $_POST['units'];
		
		$sql = " select * from sma_tender_header where id = '$tender_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['company_id'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$delivery_date 	= date('Y-m-d', strtotime($_POST['deliverydate']));
							
		$sql = "SELECT * FROM `sma_product` where  id = '$product_id' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$category 		= $r2['product_group'];
					
		$sql = " INSERT into `sma_tender_items` ( tender_hdr_id,  material_id, material_name, material_desc, cost_center_group, cost_center_subgroup, quantity, uom,  category_id, delivery_date )
		values ( '$tender_id', '$product_id', '$product_name', '$description', '$budget_name', '$budget_head', '$quantity', '$units', '$category', '$delivery_date' ) ";
echo $sql."<BR>";	
		$error_msg ='';
		$_SESSION['error_msg']='';
		$r2 = mysqli_query($con, $sql);
		echo $error_msg = mysqli_error($con);		
		
//		echo "<meta http-equiv='refresh' content='0'>";    
//exit();
		//$value = "<script>window.location.href='edit.php?id=$tender_id&active=active&999';</script>";
		$value = "<script>window.location.href='edit.php?id=$tender_id&999';</script>";
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
		$tender_id 	= $_POST['tender_id'];
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
				where tender_id = '$tender_id' and id = '$rid' ";
//$value1=$sql;
		$r2 = mysqli_query($con, $sql);
/*
		$sql="SELECT * from sma_po_items where tender_id = '$tender_id' ";
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

						<a href='#modalDeleteItem' id='delete-$tender_id$rid' data-toggle='modal' data-id='$tender_id$rid' data-target='#modalDeleteItem$tender_id$rid'><i class='fa fa-trash-alt'></i></a></td>
<!-- Modal Delete Item-->
<div class='modal fade' id='modalDeleteItem$tender_id$rid' role='dialog' aria-labelledby='modalDeleteItemLabel'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span>
                </button>
                <h4 class='modal-title' id='modalDeleteItemLabel'>Delete Material of Purchase Order - NEW</h4>
            </div>
            <input type='hidden' id='itemTempId'>
            <div class='modal-body' id='modalDeleteContent'>
                Are you sure you want to delete item $tender_id $rid ?
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>No</button>
                <button type='button' class='btn btn-danger' id='btnDeleteItemYes' onclick='delete_poItem($tender_id,$rid)' >Yes</button>
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