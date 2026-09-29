<?php session_start();
	include('../dbcon.php');
	include "../baseurl.php";
	$comid  = $_SESSION['comid'];	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='accountName' name='account_name' >
						<option value=''>Select</option>";
	
		if ($id == 'S'){
			$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$party_name = $r2->party_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$party_name."</option>";
			};
		}
		else if ($id == 'A'){
			$sql = "SELECT * FROM account_mst ORDER BY account_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$account_name = $r2->account_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$account_name."</option>";
			};
		}
		
		$value .= "</select>";
		
//$value = $sql;
		echo $value;
	
	}
	
	
    if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='account_Name' name='account_name' >
						<option value=''>Select</option>";
	
		if ($id == 'S'){
			$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$party_name = $r2->party_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$party_name."</option>";
			};
		}
		else if ($id == 'A'){
			$sql = "SELECT * FROM account_mst ORDER BY account_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$account_name = $r2->account_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$account_name."</option>";
			};
		}
		
		$value .= "</select>";
		
//$value = $sql;
		echo $value;
	
	}
	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='invoiceNumber' name='invoice_Number' >
						<option value=''>Select</option>";
		
		if ($id == 'Y'){
			$sql = "SELECT * FROM sma_supplier_invoice ORDER BY invoice_date ASC ";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
					$supplier_invoice_no = $r2->supplier_invoice_no;
					$id = $r2->id;
					$value .= "<option value='".$supplier_invoice_no."'>".$supplier_invoice_no."</option>";
			};
		}
		else {
			$value .="<input type='text' class='form-control' id='invoiceNumber' > ";
		}
		
		$value .= "</select>";

//$value = $sql;
		echo $value;
	
	}
		
	 if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$sql = "SELECT * FROM sma_supplier_invoice where suplier_name = '$id' and status = 'Completed' ORDER BY invoice_date ASC LIMIT 0 , 1";
		
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_assoc($q2)){
			$rows[] = $r2;
		}
			
		$data = $rows;	
		if(count($data)>0){
			
	?>
	
	<div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th></th>
			<th>Supp.Inv.No.</th>	
			<th>Dated</th>
			<th style="text-align:right;">Balance Payment</th>
		    <th style="text-align:right;">Payment Adjusted</th>
			
			<th>Deduction Head</th>
			<th style="text-align:right;">Deduction</th>
			<th style="text-align:right;">Actual Payment</th>
			<th>Remarks</th>
			<th>Document</th>
			
		</tr>
	</thead>
<tbody>
<?php
	foreach($data as $key1){
		$supplier_invoice_no 	= $key1["supplier_invoice_no"];
		$invoice_date 			= $key1["invoice_date"];
				
		$role			= $_SESSION['role'];
		$user_category	= $_SESSION['user_category'];
		
		$total_amount = $key1['total_amount'];
		$bal_amount   = $key1['bal_amount'];
		$actual_payment = $key1['actual_payment'];
		if($bal_amount<=0){
			continue;
		}
		
		$supp_id = $key1['id'];
		$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' ";	
//echo $sql."<BR>";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$bal_amount = $r2['bal_amount'];
		$our_po_ref_no = $r2['our_po_ref_no'];
								
		$sql = "SELECT * FROM sma_purchase_order where po_number = '$our_po_ref_no'  ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$po_id = $r2['id'];
		$approval_memo_ref = $r2['approval_memo_ref'];							
		$location	 			= $r2['location'];
		$comp_id				= $r2['project'];
									
		$sql = "SELECT * FROM sma_approval_memo where id = '$approval_memo_ref'  ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$ap_id = $r2['id'];

?>

	<tr>
		<td width="1%"><input type="hidden" name='supp_id[]' value="<?php echo $key1['id'];?>"></td>
			<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
		<td width="10%"><?php echo $key1['supplier_invoice_no'];?></td>
		
			<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
		<td width="08%"><?php echo date('d-m-Y', strtotime($key1['invoice_date']));?></td>
		
			<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
		<td width="8%" style="text-align:right;"><?php echo $bal_amount;?></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='payment_adjusted[]' id='payment_adjusted' onblur="getactual(); return checkadjusted();" style="text-align:right;" value="<?php echo $payment_adjusted;?>" > </td>		
		
		<td width="10%"><select class="form-control" name="deduction_head[]" id="deduction_head" >
				<option value=""> Select </option>
				<option value="T"> TDS </option>
				<option value="R"> Retention </option>
				<option value="A"> Adjustment against advance</option>
				<option value="H"> on Hold</option>
			</select>
		</td>
		<td width="10%"><input class="form-control col-md-2" type="text" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>"></td>
		
		<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo $actual_payment;?>"></td>
		
		<td width="10%"><textarea rows="1" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
		<!--<td width="5%"><?php echo $key1['id'];?></td>-->
		<?php 
			//$supp_id = $key1['supp_id'];
			$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
			$po_id = $po_id;
			$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										
			$ap_id = $ap_id;
			$company_id = $row['company_id'];
			//$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";	
			$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id&r=1";
//echo $baseurl_si;
		?>
		<td width="10%">
			<a href="<?php echo $baseurl_si; ?>" target="_blank" >Invoice</a><br>
			<a href="<?php echo $baseurl_po; ?>" target="_blank" >PO</a><br>
			<a href="<?php echo $baseurl_ap; ?>" target="_blank" >Approval Notes</a><br>
			<a href="<?php echo $baseurl1; ?>" target="_blank" >GRN</a><br>
		</td>
    </tr>
	<?php }?>
</tbody> 
</table>
	</div>
	 <?php
			}
		
//	echo  $data;
		return  $data;
	}

	if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value .= '<select class="form-control" id="paid_to" name="paid_to" onchange="getinvoice(this.value)" >
										<option value="">Select</option>';	
		$sql="SELECT * FROM sma_party_mst where id in (select suplier_name from sma_supplier_invoice where company_id = '$id' and bal_amount > 0 and status='Completed' ) ORDER BY party_name ASC";
		$q2 = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$id = $r2->id;
			$party_name = $r2->party_name;
			$value .= "<option value='".$id."'>".$party_name. "</option>";
		}
		$value .= "</select>";
//$value = $sql;
		echo $value;
	
	}
	
	
?>


