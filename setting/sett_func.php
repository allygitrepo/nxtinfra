<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		$doc_type = $_POST['doc_type'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		if($doc_type=='SI'){
				
			$sql = "select * from sma_supplier_invoice a, sma_party_mst b where a.id = '$id' and a.suplier_name = b.id " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$party_name 		 = $r2->party_name;
				$supplier_invoice_no = $r2->supplier_invoice_no;
				$invoice_date 		= $r2->invoice_date;
				$company_id 		= $r2->company_id;
				$our_po_ref_no 		= $r2->our_po_ref_no;
				$total_amount 		= $r2->total_amount;
				
			};
?>

						<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Supplier Invoice No.</label>
								<input type="text" class="form-control" readonly value="<?php echo $supplier_invoice_no;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label">Invoice Date</label>
								<input type="text" class="form-control" readonly  value="<?php echo date('d-m-Y', strtotime($invoice_date));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name ?>">									
							</div>
							
							<?php
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$our_po_ref_no_a = $r1['po_number'];
									
							?>
							<div class="col-md-4">
								<label class="control-label">Our PO Ref.No.</label>
								
								<input type="text" class="form-control" readonly value="<?php echo $our_po_ref_no_a . $po_rev_a;?>" >
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
							
<?php		
		}
		
		if($doc_type=='OE'){
				
			$sql = "select * from sma_travel_expenses a, sma_party_mst b where a.exp_type = 'C' and a.id ='$id' and a.emp_id = b.id " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$party_name 		 = $r2->party_name;
				$invoice_date 		= $r2->dated;
				$company_id 		= $r2->company_id;
				$total_amount 		= $r2->total_amount;
				
			};

?>
			
			<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Nnumber</label>
								<input type="text" class="form-control" readonly value="<?php echo $id;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label"> Date</label>
								<input type="text" class="form-control" readonly value="<?php echo date('d-m-Y', strtotime($invoice_date));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name ?>">									
							</div>
							
							<?php
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$our_po_ref_no_a = $r1['po_number'];
									
							?>
							
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
						
<?php						
						
		}
		

		if($doc_type=='RE'){
				
			$sql = "select * from sma_travel_expenses a, sma_party_mst b where a.exp_type = 'R' and a.id ='$id' and a.emp_id = b.id " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$party_name 		 = $r2->party_name;
				$invoice_date 		= $r2->dated;
				$company_id 		= $r2->company_id;
				$total_amount 		= $r2->total_amount;
				
			};

?>
			
			<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Nnumber</label>
								<input type="text" class="form-control" readonly value="<?php echo $id;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label"> Date</label>
								<input type="text" class="form-control" readonly value="<?php echo date('d-m-Y', strtotime($invoice_date));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name ?>">									
							</div>
							
							<?php
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$our_po_ref_no_a = $r1['po_number'];
									
							?>
							
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
						
<?php						
						
		}
		
		if($doc_type=='PY'){
				
			$sql = "select * from payment_header a, sma_party_mst b where a.id ='$id' and a.paid_to = b.id " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$party_name 		= $r2->party_name;
				$invoice_date 		= $r2->paid_date;
				$company_id 		= $r2->company_id;
				$utr_no		 		= $r2->utr_no. ' / ' . $r2->cheque_no;;
				$total_amount 		= $r2->total_amount_paid;
				$cash_bank_name		= $r2->cash_bank_name;
				
			};

?>
			
			<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Nnumber</label>
								<input type="text" class="form-control" readonly value="<?php echo $id;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label"> Paid Date</label>
								<input type="text" class="form-control" readonly value="<?php echo date('d-m-Y', strtotime($invoice_date));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name ?>">									
							</div>
													
							<div class="col-md-2">
								<label class="control-label">Paid Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
						
						<?php
								
								$sql  = " SELECT * from account_mst where id = '$cash_bank_name' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$bank_name = $r1['account_name'];
									
						?>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $bank_name ?>">									
							</div>
							
							
							
							<div class="col-md-4">
								<label class="control-label">UTR No./Cheque No.</label>
								<input type="text" class="form-control" readonly value="<?php echo $utr_no;?>" >
							</div>
							
						</div>
						
<?php						
						
		}


	if($doc_type=='PC'){
				
			$sql = "SELECT a.id, company_id, location_id, a.dated, a.trans_type, total_amount, b.invoice_no, b.expense_id, b.spend_by, b.paid_to FROM `sma_pettycash` a, sma_pettycash_exp b where a.id = b.approval_ref_no and a.id ='$id' " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$spend_by	 		= $r2->spend_by;
				$paid_to 			= $r2->paid_to;
				$trans_type			= $r2->trans_type;
				$dated 				= $r2->dated;
				$company_id 		= $r2->company_id;
				$location_id 		= $r2->location_id;
				$invoice_no	 		= $r2->invoice_no;
				$total_amount 		= $r2->total_amount;
				$expense_id			= $r2->expense_id;
				
			};

?>
			
			<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Nnumber</label>
								<input type="text" class="form-control" readonly value="<?php echo $id;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label"> Prepared Date</label>
								<input type="text" class="form-control" readonly value="<?php echo date('d-m-Y', strtotime($dated));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
								
								$sql    = "select * from sma_location where id = $location_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	  = mysqli_fetch_array($q2);
									$loc_name = $r2['loc_name'];
								}
								
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							<div class="col-sm-2">

								<label for="company_id" class="control-label">Location</label>
								<input type="text" class="form-control" readonly value="<?php echo $loc_name;?>" >
									
							</div>
						</div>
						
						
						<?php 
							if($paid_to=='U'){
								$sql = "select * from sma_user where id = $spend_by ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$party_name = $r2['username'];
								}
							}
							if($paid_to=='V'){
								$sql = "select * from sma_party_mst where id = $spend_by ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$party_name = $r2['party_name'];
								}
							}
							if($paid_to=='O'){
								$party_name = $spend_by;
							}	
							
						?>
							
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Party Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name 	?>">									
							</div>
													
							<div class="col-md-2">
								<label class="control-label">Paid Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
						
						<?php
								
								$sql  = " SELECT * from account_mst where id = '$expense_id' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$account_name = $r1['account_name'];
									
						?>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Expense Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $account_name ?>">									
							</div>
							
							
							
							<div class="col-md-4">
								<label class="control-label">Invoice No.</label>
								<input type="text" class="form-control" readonly value="<?php echo $invoice_no;?>" >
							</div>
							
						</div>
						
<?php						
						
		}
		
		if($doc_type=='TE'){
				
			$sql = "select * from sma_travel_expenses a, sma_party_mst b where a.exp_type = 'T' and a.id ='$id' and a.emp_id = b.id " ;
			$q2  = mysqli_query($con, $sql);
	//echo $sql;		
			while($r2 = mysqli_fetch_object($q2)){
				
				$party_name 		 = $r2->party_name;
				$invoice_date 		= $r2->dated;
				$company_id 		= $r2->company_id;
				$total_amount 		= $r2->total_amount;
				
			};

?>
			
			<div class="form-group">
											
							<div class="col-md-2">
								<label class="control-label">Nnumber</label>
								<input type="text" class="form-control" readonly value="<?php echo $id;?>" >
							</div>
						 
							<div class="col-md-2">
								<label class="control-label"> Date</label>
								<input type="text" class="form-control" readonly value="<?php echo date('d-m-Y', strtotime($invoice_date));?>" >
								
							</div>
							
							<?php $sql = "select * from company where comp_id = $company_id ";
								$q2 	= mysqli_query($con, $sql);
								$err	= mysqli_error($con);
								if(empty($err)){
									$r2 	= mysqli_fetch_array($q2);
									$comp_name = $r2['comp_name'];
								}
							?>
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<input type="text" class="form-control" readonly value="<?php echo $comp_name;?>" >
									
							</div>
							
							
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label">Supplier Name</label>
								<input type="text" class="form-control" readonly value="<?php echo $party_name ?>">									
							</div>
							
							<?php
								
								$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$res  = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r1 = mysqli_fetch_array($res);
								
								$our_po_ref_no_a = $r1['po_number'];
									
							?>
							
							<div class="col-md-2">
								<label class="control-label">Invoice Amount</label>
								<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo number_format($total_amount,0);?>" >
							</div>
							
						</div>
						
<?php						
						
		}
		
//SELECT a.id, company_id, location_id, a.dated, a.trans_type, total_amount, b.invoice_no, b.expense_id, b.spend_by, b.paid_to FROM `sma_pettycash` a, sma_pettycash_exp b where a.id = b.approval_ref_no
				
?>

<?php		

    }	
	
	if(isset($_POST['sub2'])){
    
        $main_menu = $_POST['id'];
?>

		<select class="form-control" name="sub_menu" id="sub_menu" >
										<option value=""> Select.. </option>
											<?php $sql = "select distinct(sub_menu) from log_tbl where main_menu = '$main_menu' order by sub_menu";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['sub_menu'];?>" ><?php echo $r2['sub_menu'];?></option>
											<?php } ?>
									</select>
									
<?php		
	}
?>


	