<?php
	
	include("../dbcon.php");

  if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$comp_id = $_POST['comp_id'];	
		$sql = "SELECT * FROM sma_purchase_order where to_supplier = '$id' and project = '$comp_id' and status = 'Completed' and approval_Status !='Blocked'";
//	echo $sql;
		
		$value = '';
		$value = "<select class='form-control' id='sma_po_no' name='sma_po_no' onchange='getpoamt(this.value); getsuppno(this.value)' required>
						<option value=''>Select</option>";
		
		$sql = "SELECT * FROM sma_purchase_order where to_supplier = '$id' and project = '$comp_id' and status = 'Completed' and approval_Status !='Blocked'";
		
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
				$po_number = $r2->po_number;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$po_number."</option>";
		};
		
			
		$value .= "</select>";

//$value = $sql;
		echo $value;
	
	}


	if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
			
		$sql="SELECT * from sma_po_items where purchase_id = '$id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
				$qty 	= $r1['quantity'];
				$rate 	= $r1['unit_rate'];
				$gst	= $r1['gst'];
				$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
				$tot_amount = $tot_amount + $amount;
		}
		
		$value = '<input type="text" class="form-control" id="sma_po_amount" name="sma_po_amount" style="text-align:right;" autocomplete="off" value="'.$tot_amount.'"> ';
//$value = $sql;		
		echo $value;
	
    }

  if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sma_po_no = $_POST['sma_po_no'];
		$sma_inv_advA = $_POST['sma_inv_advA'];
		$sma_inv_advP = $_POST['sma_inv_advP'];
		if($sma_inv_advA =='A' || $sma_inv_advP =='P'){
			$required  = '';
		}
		else {
			$required  = "required";
		}
//$sql = " SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and our_po_ref_no = '$sma_po_no' and sma_supplier_invoice.del !='Y' and id not in ( SELECT sma_invoice_no FROM `sma_ipc` where sma_ipc.del !='Y'  and approval_Status != 'Rejected'  ) ORDER BY invoice_date ASC ";
//echo $sql;
		
		$value = '';
		$value = "<select class='form-control' id='sma_invoice_no' name='sma_invoice_no' onchange='getsuppamt(this.value)' " .$required. " >
						<option value=''>Select </option>";
		//and status = 'Completed'
		//and id not in(select sma_invoice_no from sma_ipc)
		$sql = " SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and our_po_ref_no = '$sma_po_no' and sma_supplier_invoice.del !='Y' and id not in ( SELECT sma_invoice_no FROM `sma_ipc` where sma_ipc.del !='Y' and approval_Status != 'Rejected' ) 
				ORDER BY invoice_date ASC ";
//echo $sql; //SELECT * FROM sma_supplier_invoice where id not in ( SELECT sma_invoice_no FROM `sma_ipc` )
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
				$supplier_invoice_no = $r2->supplier_invoice_no;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$id.' - ' . $supplier_invoice_no."</option>";
		};
		
		$value .= "</select>";

//$value = $sql;
		echo $value;
	
	}

	if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$value = '';	
		$sql="SELECT * from sma_supplier_invoice where id = '$id' "; // and status = 'Completed'  
//echo $sql;		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r1 = mysqli_fetch_array($res1)){
				$sma_invoice_amount 	= $r1['total_amount'];	
				$sma_invoice_no 	= $r1['supplier_invoice_no'];	
		}
		
		$value .= '<div class="col-md-2"><label class="control-label">Invoice Number</label>
					<input type="text" class="form-control" readonly value="'.$sma_invoice_no .'" ></div>';
		$value .= '<div class="col-md-2"><label class="control-label">Invoice Amount</label>';
		$value .= '<input type="text" class="form-control" id="sma_invoice_amount" name="sma_invoice_amount" style="text-align:right;" autocomplete="off" value="'.$sma_invoice_amount.'"> ';
		$value .= '</div>';
//$value = $sql;	
		echo $value;
	
    }
	
	?>