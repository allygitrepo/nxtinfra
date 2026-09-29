<?php
include("../header.php");
$modulePath = "payment/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php  
	if($_GET['sub'] == 'delete'){ 
        $id  = $_GET['id'];
		$sql = "delete from payment_header where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		$sql = "delete from payment_details where payment_hdr_id = '$id' ";
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);

        //echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		$baseurl1 =$baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
			
	} 
?>

<?php
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$py_id			= $_POST['id'];
			
			$paid_to				= $_POST['paid_to'];
			$paid_date				= date('Y-m-d', strtotime($_POST['paid_date']));
			$company_id				= $_POST['company_id'];
			$cash_bank_name			= $_POST['cash_bank_name'];
			$cheque_no				= $_POST['cheque_no'];
			$utr_no					= $_POST['utr_no'];
			$tds_amount				= $_POST['tds_amount'];
			$total_amount_paid		= $_POST['total_amount_paid'];
			$tds_amount_prev		= $_POST['tds_amount_prev'];
			$total_amount_paid_prev	= $_POST['total_amount_paid_prev'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$remarks_hdr			= $_POST['remarks_hdr'];
			$due_date 				=  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['paid_date'])));
			
			$sql="update payment_header set paid_date = '$paid_date',
					company_id				= '$company_id',
					cash_bank_name			= '$cash_bank_name',
					cheque_no				= '$cheque_no',
					utr_no					= '$utr_no',
					dated					= '$dated',
					remarks					= '$remarks'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			
			$supp_id				= $_POST["supp_id"];
			
			$status				    = $_POST['status'];
			
				$sql="SELECT * FROM sma_party_mst where id ='$paid_to' ";
				$q2 = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$party_name  = $r2['party_name'];
				$party_email = $r2['party_email'];
				$user_name	 = $party_name;


			$party_name  = '';
			$party_email = '';
			if($status == 'Completed' && (!empty($cheque_no) || !empty($utr_no) ) ){
				
				for($i = 0; $i < sizeof($supp_id); $i++){
					$py_dtl_id			= $_POST['py_dtl_id'][$i];
					$supp_id			= $_POST["supp_id"][$i];
					$invoice_date		= $_POST["invoice_date"][$i];
					$supplier_invoice_no= $_POST["supplier_invoice_no"][$i];
					$bal_amount			= $_POST["bal_amount"][$i];
					$payment_adjusted	= $_POST["payment_adjusted"][$i];
					$deduction_head		= $_POST["deduction_head"][$i];
					$deduction_amt		= $_POST["deduction_amt"][$i];
					$deduction_head1	= $_POST["deduction_head1"][$i];
					$deduction_amt1		= $_POST["deduction_amt1"][$i];
					$remarks_dtl		= $_POST["remarks_dtl"][$i];
					
					$sql="SELECT a.*, b.* FROM sma_supplier_invoice_details a, sma_product b where a.si_hdr_id ='$supp_id' and b.id = a.material_id";
					$q2 = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$material_name  .= $r2['name'].' '.$r2['description']."<BR>";
						
					$msg_dtl  = "<br><br><table border='1'>"; 
					$msg_dtl  .= '<tr><td>Date</td><td>Invoice No.</td><td>Cheque/UTR No.</td><td> Amount </td><td>Vendor</td><td>Material</td></tr>';
					$msg_dtl  .= '<tr><td>'.$invoice_date .'</td><td>'.$supplier_invoice_no.'</td><td>'.$cheque_no.'</td><td>'.$payment_adjusted.'</td><td>'.$user_name.'</td><td>'.$material_name.'</td></tr>';
					if($deduction_amt>0){
						$msg_dtl .= '<tr><td>&nbsp;</td><td>Deduction 1: </td><td>'.$deduction_head.'</td><td> '.$deduction_amt.'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
					}		
					if($deduction_amt1>0){
						$msg_dtl .= '<tr><td>&nbsp;</td><td>Deduction 2: </td><td>'.$deduction_head1.' </td><td>'.$deduction_amt1.'</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
					}
					
					$msg_dtl  .= "</table>";
					
				}
				
				$srno = $py_id;
				
				$s = "select * from sma_user where userid in (select draft_by from sma_supplier_invoice where id='$supp_id') ";
				$sql = mysqli_query($con, $s);
				$r = mysqli_fetch_object($sql);
				$username 		= $r->userid;
				//$company_id 	= $r->company_id;
				$user_email		= $r->email;
				$user_name_by	= $r->username;
				
				$sql="SELECT * FROM company where comp_id ='$company_id' ";
				$q2 = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$user_name_by  = $r2['comp_name'];
				
	
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
				
				$msg = 'Payment Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");
				$msg .=  $msg_dtl;
				include "py_mail.php";
				
				$baseurl.=$modulePath;
				echo "<script>window.location.href='$baseurl';</script>";
				
			}
		
		
			for($i = 0; $i < sizeof($supp_id); $i++){
				$py_dtl_id			= $_POST['py_dtl_id'][$i];
				$supp_id			= $_POST["supp_id"][$i];
				$invoice_date		= $_POST["invoice_date"][$i];
				$supplier_invoice_no= $_POST["supplier_invoice_no"][$i];
				$bal_amount			= $_POST["bal_amount"][$i];
				$payment_adjusted	= $_POST["payment_adjusted"][$i];
				$deduction_head		= $_POST["deduction_head"][$i];
				$deduction_amt		= $_POST["deduction_amt"][$i];
				$deduction_head1	= $_POST["deduction_head1"][$i];
				$deduction_amt1		= $_POST["deduction_amt1"][$i];
				//$actual_payment	= $_POST["actual_payment"][$i];
				$remarks_dtl		= $_POST["remarks_dtl"][$i];
				
				$bal_amount_prev	= $_POST["bal_amount_prev"][$i];
				$payment_adjusted_prev	= $_POST["payment_adjusted_prev"][$i];
				$deduction_amt_prev		= $_POST["deduction_amt_prev"][$i];
				$deduction_amt_prev1	= $_POST["deduction_amt_prev1"][$i];
				$tot_payment_adjusted_prev	= $payment_adjusted_prev + $deduction_amt_prev1 + $deduction_amt_prev;
				//$tot_amount_prev 		= $payment_adjusted_prev + $tot_payment_adjusted_prev;
				$tot_tds_amount_prev	= $tot_tds_amount_prev + $deduction_amt_prev;
				
				$actual_payment			= $payment_adjusted ;
				$tot_payment_adjusted	= $payment_adjusted + $deduction_amt;
				
				$tot_tds_amount		= $tot_tds_amount + $deduction_amt;
				$tot_amount			= $tot_amount + $actual_payment;
				
				if( ($payment_adjusted + $deduction_amt) >0 ){
					$sql = " update `payment_details` set supp_id	= '$supp_id',
									invoice_date		= '$invoice_date',
									supplier_invoice_no = '$supplier_invoice_no',
									bal_amount			= '$bal_amount',
									payment_adjusted	= '$payment_adjusted' ,
									deduction_head		= '$deduction_head',
									deduction_amt		= '$deduction_amt' ,
									deduction_head1		= '$deduction_head1',
									deduction_amt1		= '$deduction_amt1' ,
									remarks				= '$remarks_dtl',
									actual_payment		= '$actual_payment'  
							where payment_hdr_id = '$id' and id = '$py_dtl_id' ";
					
					$r2 = mysqli_query($con, $sql);
				
				}
//echo $sql."<BR>";
					$sql = " update sma_supplier_invoice set bal_amount = bal_amount - $tot_payment_adjusted + $tot_payment_adjusted_prev where id = '$supp_id' ";
					$r2 = mysqli_query($con, $sql);
//echo $sql."<BR>";					
					$sql = "update payment_header set tds_amount = $tot_tds_amount , 
								total_amount_paid = $actual_payment - $tot_tds_amount 
							where id = '$id' ";
//echo $sql."<BR>";
//exit();
					$r2 = mysqli_query($con, $sql);
					//mail to draft user
					$sql   = "SELECT * FROM `sma_user` where userid in ( Select draft_by from sma_supplier_invoice where id = '$supp_id' ) ";
					$query = mysqli_query($con, $sql);
					$row   = mysqli_fetch_array($query);
					$user_email = $row['email'];
					$user_name = $row['username'];
					
					$sql   = "select * from sma_party_mst where id in ( Select suplier_name from sma_supplier_invoice where id ='$supp_id' ) ";
					$query = mysqli_query($con, $sql);
					$row   = mysqli_fetch_array($query);
					$mail_to = $row['party_email'];
					$party_name = $row['party_name'];
					
					$msg  = 'Payment for Supplier Invoice Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date("d-m-Y"). "<br>";
					$msg .= 'Payment Paid : ' . $payment_adjusted . "<br>";
					$msg .= 'Deduction under : ' . $deduction_head . ' : ' . $deduction_amt. "<br>";
			//		include "py_mail.php";

			}
			
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];

			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/py/" . $py_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];

				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('PY', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $py_id . ",'2018-01-01')";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/py/" . $py_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
					
					echo $sql;
					
				}
			}
		
				
//exit();

			$baseurl.=$modulePath;
			echo "<script>window.location.href='$baseurl';</script>";
		
		}
		
		$id	 = $_GET['id'];
		$py_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';
//			header('Location: '.$_SERVER['REQUEST_URI']);
		}
		
		$sql="Select * from payment_header where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$py_id 	= $row['id'];
								
		$status = $row['status'];
		$_SESSION['status'] = $row['status'];
		
		$draft_by = $row['draft_by'];
		$readonly = '';
		if ($status == 'Submited' || $status == 'Completed'){
			$readonly = 'READONLY';
		}		

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
            Payment
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Payment</a></li>
            
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
					
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                    
					<input type="hidden" name="status" value="<?php echo $row['status'];?>">
					<input type="hidden" name="draft_by" value="<?php echo $row['draft_by'];?>">
					<input type="hidden" name="id" value="<?php echo $row['id'];?>">
					<ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" > Payment </a></li>
                        <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
						<li><a href="neft_prn.php?sub=pdf&id=<?php echo $row['id'];?>&bank_id=<?php echo $row['cash_bank_name'];?>&r=1" class="btn btn-success"  target="_blank" >RTGS Print </a></li>
						<span class="pull-right" style="color:red;font-size:20px;"><b><?php echo $row['status'];?></b> </span>
					
                    </ul>
					
					<div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" value="<?php echo $row['id'];?>" >
							</div>
							
							<?php $paid_date =  date('d-m-Y', strtotime($row['paid_date']));?>
							
							<div class="col-md-2">
								<label class="control-label">Paid Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="paid_date" name="paid_date" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $paid_date; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
							
							<div class="col-sm-4">
								<?php 
									$_SESSION['company'] = $row['company_id'];
								?>
								<label for="company_id" class="control-label">Company</label>
								<select class="form-control select2" name="company_id" id="company_id" readonly >
								<option value=""> Select </option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Paid To</label>
									<?php
										$paid_to = $row['paid_to'];
										$sql="SELECT * FROM sma_party_mst where id ='$paid_to' ";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										$r2 = mysqli_fetch_array($q2);
									?>
									<input type="hidden" class="form-control" id="paid_To" name="paid_to" readonly value="<?php echo $row['paid_to'] ?>" >
									<input type="text" class="form-control"  name="paid_to_name" autocomplete="off" readonly value="<?php echo $r2['party_name'] ?>" >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-2">
								<label class="control-label">Paid via</label>
										<select class="form-control" id="cash_bank_name" name="cash_bank_name" <?php echo $readonly; ?> >
											<option value="">Select</option>	
										<?php
											$sql="SELECT * FROM account_mst where account_type = 'B' ORDER BY account_name ASC";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['cash_bank_name'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['account_name'] ?></option>
											<?php } ?>
										</select>
								
							</div>
							
							<?php $dated =  date('d-m-Y', strtotime($row['dated']));?>
							
							<div class="col-md-2">
								<label class="control-label">Dated</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" autocomplete="off" <?php echo $readonly; ?> value="<?php echo $dated; ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
													
							<div class="col-md-2">
								<label class="control-label">Total Amount</label>
								<input type="hidden" class="form-control" id="total_amount_paid_prev" style="text-align:right;" name="total_amount_paid_prev" value="<?php echo $row['total_amount_paid_prev'];?>">
								<input type="text" class="form-control" id="total_amount_paid" autocomplete="off" style="text-align:right;" name="total_amount_paid" readonly value="<?php echo number_format($row['total_amount_paid'],2);?>" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Narration</label>
								<textarea rows="2" class="form-control" id="remarks_hdr" name="remarks_hdr" autocomplete="off" <?php echo $readonly; ?> ><?php echo $row['remarks'];?></textarea>
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-md-2 control-label">Cheque Number</label>
							<div class="col-md-4">
							<?php //if($status == 'Completed'){
							
								if ($role == 'Maker' || $role == 'Accountant'){
							 ?>	
								<input type="text" class="form-control" id="cheque_No" name="cheque_no" autocomplete="off" value="<?php echo $row['cheque_no'];?>"  >
							<?php }
								else { 
							?>
								<input type="text" class="form-control" id="cheque_No" readonly name="cheque_no" placeholder="" value="<?php echo $row['cheque_no'];?>" >
								
							<?php }
							//}
							?>
						</div>
							
							<label class="col-md-1 control-label">UTR.Number</label>
							<div class="col-md-5">
							<?php if($status == 'Completed'){	
							
								if ($role == 'Maker' || $role == 'Accountant'){
							 ?>	
								<input type="text" class="form-control" id="utr_No" name="utr_no" autocomplete="off" value="<?php echo $row['utr_no'];?>"  >
							<?php }
								else { 
							?>
								<input type="text" class="form-control" id="utr_No" readonly name="utr_no" placeholder="" value="<?php echo $row['utr_no'];?>" >
								
							<?php }
							}
							?>
								
							</div>

							
						</div>
						
						
						<div class="box">
							<table id="prtable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th></th>
									<th>Dated</th>
									<th>Supp.Inv.No.</th>	
									<th style="text-align:right;">Balance Payment</th>
									<th style="text-align:right;">Payable</th>
									
									<th>Deduction Head</th>
									<th style="text-align:right;">Deduction</th>
									<th style="text-align:right;">Paid</th>
									<th>Remarks</th>
									<th>Document View</th>
									
								</tr>
							</thead>
							<tbody>
						<?php
						
								$payment_hdr_id = $row['id'];
								$sql="SELECT * from payment_details where payment_hdr_id = '$py_id' order by id desc";
											
								$result = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$value="";
								while($key1 = mysqli_fetch_array($result)){
									$supplier_invoice_no 	= $key1["supplier_invoice_no"];
									$invoice_date 			= $key1["invoice_date"];
									
									$actual_payment = $key1['payment_adjusted'] - $key1['deduction_amt'];
									$tot_actual_payment		= $tot_actual_payment + $actual_payment; 
									
									$tot_payment_adjusted = $tot_payment_adjusted + $key1['payment_adjusted'] ;
									$tot_deduction_amt 	  = $tot_deduction_amt + $key1['deduction_amt'] + $key1['deduction_amt1'];
									
									$supp_id = $key1['supp_id'];
									$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' ";		
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$bal_amount = $r2['bal_amount'];
									$our_po_ref_no = $r2['our_po_ref_no'];
									
									$sql = "SELECT * FROM sma_purchase_order where po_number = '$our_po_ref_no' or id = '$our_po_ref_no' ";
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
									//$comp_id	= $r2['company'];
									
									$sql = "SELECT * FROM sma_grn_srn where our_po_ref_no = '$po_id'  ";
							//echo $sql;		
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$srn_id = $r2['id'];
									
									$compid ='';
									$sql = "SELECT * FROM sma_ipc where sma_po_no = '$our_po_ref_no' and   ";
							//echo $sql;
									$q2  = mysqli_query($con, $sql);
									$r2  = mysqli_fetch_array($q2);
									$ipc_id = $r2['id'];
									$compid	= $r2['sma_comp_id'];
									
							?>

								<tr>
									<input type="hidden" name='py_dtl_id[]' id='py_dtl_id' value="<?php echo $key1['id'];?>" >
									<td width="1%"><input type="hidden" name='supp_id[]' value="<?php echo $key1['supp_id'];?>"></td>
										<input type="hidden" name='invoice_date[]' id='invoice_date' value="<?php echo $invoice_date;?>" >
									<td width="08%"><?php echo date('d-m-Y', strtotime($key1['invoice_date']));?></td>
										<input type="hidden" name='supplier_invoice_no[]' id='supplier_invoice_no' value="<?php echo $supplier_invoice_no;?>" >
									<td width="10%"><?php echo $key1['supplier_invoice_no'];?></td>
										<input type="hidden" name='bal_amount[]' id='bal_amount' value="<?php echo $bal_amount;?>" >
										<input type="hidden" name='bal_amount_prev[]' id='bal_amount_prev' value="<?php echo $bal_amount;?>" >
									<td width="8%" style="text-align:right;"><?php echo $bal_amount;?></td>
									
									<input type="hidden" class="form-control" id="payment_adjusted_prev[]" style="text-align:right;" name="payment_adjusted_prev[]" 
									value="<?php echo $key1['payment_adjusted'];?>" >
									<td width="10%"><input class="form-control col-md-2" type="text" autocomplete="off" name='payment_adjusted[]' id='payment_adjusted' 
									onblur="checkadjusted();getactual();"  style="text-align:right;" value="<?php echo $key1['payment_adjusted'];?>" <?php echo $readonly; ?>> </td>
								
									<td width="10%"><select class="form-control" name="deduction_head[]" id="deduction_head" <?php echo $readonly; ?> >
											<option value=""> Select </option>
											<?php $sql = "select * from account_mst where account_type = 'D' order by account_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['account_name'];?>" <?php echo ($key1['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> ><?php echo $r2['account_name'];?></option>
											<?php } ?>
										</select>
										<select class="form-control" name="deduction_head[]" id="deduction_head" <?php echo $readonly; ?> >
											<option value=""> Select </option>
											<?php $sql = "select * from account_mst where account_type = 'D' order by account_name";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['account_name'];?>" <?php echo ($row['deduction_head'] == $r2['account_name'])?'selected="selected"':'';?> ><?php echo $r2['account_name'];?></option>
											<?php } ?>
										</select>
									</td>
									
									<input type="hidden" class="form-control" id="deduction_amt_prev[]" style="text-align:right;" name="deduction_amt_prev[]" value="<?php echo $key1['deduction_amt'];?>" >
									<input type="hidden" class="form-control" id="deduction_amt_prev1[]" style="text-align:right;" name="deduction_amt_prev1[]" value="<?php echo $key1['deduction_amt1'];?>" >
									<td width="10%">
										<input class="form-control col-md-2" type="text" autocomplete="off" name='deduction_amt[]' id='deduction_amt' onblur="getactual()" style="text-align:right;" value="<?php echo $key1['deduction_amt'];?>" <?php echo $readonly; ?> >
										<input class="form-control col-md-2" type="text" autocomplete="off" name='deduction_amt1[]' id='deduction_amt1' onblur="getactual1()" style="text-align:right;" value="<?php echo $key1['deduction_amt1'];?>" <?php echo $readonly; ?> >
									</td>
									<?php 
										$supp_id = $key1['supp_id'];
										$baseurl_si = $baseurl . "supp_invoice/edit.php?sub=edit&id=$supp_id";
										
										$po_id = $po_id;
										//$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										$baseurl_po = $baseurl . "purchase_order/pur_order_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$ap_id = $ap_id;
										$company_id = $row['company_id'];
										//$baseurl_ap = $baseurl . "approval/edit.php?sub=edit&id=$ap_id";
										$baseurl_ap = $baseurl . "approval/approval_notes_prn.php?sub=pdf&id=$ap_id&comp_id=$company_id;&r=1";
										
										$srn_id = $srn_id;
										$company_id = $row['company_id'];
										//$baseurl_ap = $baseurl . "grnsrn/edit.php?sub=edit&id=$srn_id";
										$baseurl_srn = $baseurl . "grnsrn/grnsrn_prn.php?sub=pdf&id=$srn_id&comp_id=$company_id;&r=1";
										
										$po_id = $po_id;
										//$baseurl_po = $baseurl . "purchase_order/edit.php?sub=edit&id=$po_id";
										$baseurl_powf = $baseurl . "purchase_order/po_wf_prn.php?sub=pdf&id=$po_id&comp_id=$comp_id&location=$location";
										
										$ipc_id = $ipc_id;
										//$baseurl_ap = $baseurl . "grnsrn/edit.php?sub=edit&id=$srn_id";
										$baseurl_ipc = $baseurl . "ipc/ipc_prn.php?sub=pdf&id=$ipc_id&comp_id=$compid&r=1";
										
									?>
									<input type="hidden" class="form-control" id="actual_payment_prev[]" style="text-align:right;" name="actual_payment_prev[]" placeholder="" value="<?php echo $key1['actual_payment'];?>" >
									<td width="10%"><input class="form-control col-md-2" type="text" name='actual_payment[]' id='actual_payment' readonly style="text-align:right;" value="<?php echo number_format($actual_payment,2);?>"  ></td>
									<td width="10%"><textarea rows="1" autocomplete="off" name='remarks_dtl[]' id='remarks_dtl' ><?php echo $key1['remarks'];?></textarea></td>
									<td width="10%">
										<a href="<?php echo $baseurl_si;?>" target="_blank"><span class="label label-warning">Invoice</span></a>
										<a href="<?php echo $baseurl_po;?>" target="_blank"><span class="label label-success">Purchase Order</span></a>
										<a href="<?php echo $baseurl_ap;?>" target="_blank"><span class="label label-danger">Approval Notes</span></a>
										<a href="<?php echo $baseurl_srn;?>" target="_blank"><span class="label label-info">GRN</span></a>
										<a href="<?php echo $baseurl_powf;?>" target="_blank"><span class="label label-info">PO Workflow</span></a>
									<?php
										if(!empty($compid)){
									?>	
										<a href="<?php echo $baseurl_ipc;?>" target="_blank"><span class="label label-info">IPC</span></a>
									<?php } ?>	
									</td>
								</tr>
								<?php }?>
							</tbody>
							
							<tfoot>
								<tr>
									<th></th><th></th><th></th>
									<th  style="text-align:right;">Total</th>
									<th  style="text-align:right;"><?php echo $tot_payment_adjusted;?> </th>
									<th></th>
									<th  style="text-align:right;"><?php echo $tot_deduction_amt;?> </th>
									<th  style="text-align:right;"><?php echo $tot_actual_payment;?> </th>
									<th></th><th></th>
									
									
								</tr>
							</tfoot>
							
							</table>
						</div>
						
						
                       <!-- Attachments -->
					   <div class="box">
							<?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PY' AND reference_id = " . $py_id;
                              $docResults = mysqli_query($con, $sql);
							  $affected_rows =  mysqli_affected_rows($con);
							  
							  //echo $affected_rows;
							  if($affected_rows > 0){
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
													$delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
					                              ?>
                                          <tr>
                                              <td><?php echo $document ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
											  <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
								  <?php } ?>
								  
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td><label class="col-sm-1 control-label">Document</label></td>    
										<td>
                                            <select class="form-control select2 doctype" name="doctype[]" <?php echo $readonly; ?> >
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
                                            </select>
										</td>
										<td>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" <?php echo $readonly; ?> placeholder="Enter document description..."></textarea>
										</td>
										<td>
											<input type="file" name="fudoc[]" class="docfile" <?php echo $readonly; ?>>
										</td>
                                         <td><button type="button" name="add" id="add_doc" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            
							</div>
						</div>
					<!-- Attachments -->
				</div>
				
					<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
							//		SELECT * FROM `workflow_history` where doc_type='PY'
									$srno = $py_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
								 
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status_w			= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status_w; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
						</div>
					
					</div>	
						
							<span id="predit"></span>
											
				</div>
											
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl.$modulePath."edit.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							
							<div class="col-sm-6 text-right">
								<?php 
									$_SESSION['py_id'] 	= $py_id;
									$_SESSION['status']  = $status;
									
						//echo $status;
								?>

								<?php		
								$role = $_SESSION['role'];
								if (($status == 'Submited' || $status == 'Verified') && $role == 'HOD - Account'){
									//echo $status. ' < ###1> ' . $role;
								?>
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#approvalAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } 
							
							//echo $status. ' <> ' . $role;
							
								if ($status == 'Submited' && $role == 'Checker - Account'){
									//echo $status. ' <<>> ' . $role;
							
								?>
									<a href="#checkerAuthority" class="btn btn-info" data-toggle="modal" data-mode="Accept" data-target="#checkerAuthority">Accept </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } ?>
								
								<?php
								
									if ($status == 'Draft' && ($role == 'Maker' || $role == 'Accountant')){
								?>
										<span>&nbsp;&nbsp;</span>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send </a>
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php }
								
								if ($status == 'Completed' && ($role == 'Maker' || $role == 'Accountant')){
								?>
										<span>&nbsp;&nbsp;</span>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">
										<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php } ?>
								
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
									
							</div>
						</div>

				</div>
								
		</div>
				
                    </fieldset>

				</div>
				
            </form>
					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>  
</div>


<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company		 = $_SESSION['company'];
											
											if($role=='Maker' || $role =='Accountant'){
												$account_role = 'Checker - Account';
											}
											else {
												$account_role = 'HOD - Account';
												$status = 'Submited';
											}
								//echo	$sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and role in ( select id from sma_role where role = '$account_role' )  ORDER BY first_name ASC";		
										?>
										
										<input type="hidden" name="py_id" id="py_idE" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
								
										<div class="form-group col-md-12">
                                        
											<label for="approver" class="col-sm-4 control-label">User Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql="SELECT * FROM sma_user where FIND_IN_SET('$company', company_id)<>0 and role in ( select id from sma_role where role = '$account_role' )  ORDER BY first_name ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'] ?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Narration</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
										</form>	
									</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company		 = $_SESSION['company'];
											
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$row   = mysqli_fetch_array($query);
											$approver = $row['id'];
										?>
										<input type="hidden" name="py_id" id="py_idA" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeA" name="mode" value='Accept'>
										<input type="hidden" id="approverA" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusA" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
									<form class="form-horizontal">
										<?php
											$py_id 	= $_SESSION['py_id'];
											$status = $_SESSION['status'];
											
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$row   = mysqli_fetch_array($query);
											$approver = $row['id'];
										?>
										<input type="hidden" id="py_idR" 	name="py_id" value="<?php echo $py_id; ?>" >
										<input type="hidden" id="modeR" 	name="mode"  value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
										<input type="hidden" id="supp_idR" name="supp_id" value='<?php echo $supp_id;?>'>										
					
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusR" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>

									</form>
									
                                    </div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        


<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document1</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'<option value="PAN CARD">PAN Card</option><option value="AADHAAR CARD">AADHAAR Card</option></select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     //alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
 <!-- For Document Attachment End-->
 
<script>

   $("#submitChecker").on("click", function(e){
        var sub = 'sub8';
		var mode		 	=  $("#modeC").val();
		var py_id		 	=  $("#py_idE").val();
		var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
//alert(remarks);
	
		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#checkerAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

</script>

<script>

   $("#submitApprove").on("click", function(e){
        var sub = 'sub8';
//alert(sub);
		var mode		 	=  $("#modeA").val();
		var py_id		 	=  $("#py_idA").val();
		var approver		=  $("#approverA").val();
        var status 			=  $("#statusA").val();
		var remarks			=  $("#remarksA").val();
		var paid_to			=  $("#paid_To").val();
		var cheque_no		=  $("#cheque_No").val();

		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#approvalAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						cheque_no:cheque_no,
						status:status,
						paid_to:paid_to,
						remarks:remarks,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

	

   $("#submitReject").on("click", function(e){
        var sub = 'sub8';
//alert(sub);
		var mode		 	=  $("#modeR").val();
		var py_id		 	=  $("#py_idR").val();
		var approver		=  $("#approverR").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		var supp_id			=  $("#supp_idR").val();
		
		if(approver==''){
			alert("User Name should select...");
			return;
		}
		
		 $('#rejectAuthority').modal('hide');
		var strURL = "py_func.php";
		$.post(strURL,{ py_id:py_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						supp_id:supp_id,
						sub8:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
</script>

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

</script>

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "py_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "py_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
		});

	}
	
</script>


<script>

	function getaccount(id){
		
        var sub    = 'sub1';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getaccount').html(result);
		});

	}
</script>


<script>

	function getaccount1(id){
		
        var sub    = 'sub11';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getaccount1').html(result);
		});

	}

</script>

<script>

	function getinvnumber(id){
		
        var sub    = 'sub2';

//alert(sub + id );

	//	var id		= document.getElementById("accountType").value;

		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getinvnumber').html(result);
		});
	}

	function getdocview(id){
        var sub    = 'sub5';	
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getdocview').html(result);
		});	
		
	}

		
	function checkadjusted(){
//alert("HELLO ####1");		
		var bal_amount = document.getElementById("bal_amount").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		
//		if (parseInt(payment_adjusted) > parseInt(bal_amount) ){
//			alert(" Payment adjustment amount should not be greater then balance amount...");
//			document.getElementById("payment_adjusted").value = '';
//			return false;
//		}
		
	}
	
	function getactual(){
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		var deduction_amt    = document.getElementById("deduction_amt").value;
		var deduction_amt1    = document.getElementById("deduction_amt1").value;
		var actual_payment   = parseInt(payment_adjusted) - parseInt(deduction_amt) - parseInt(deduction_amt1);
		if (!isNaN(actual_payment)) {
       //  document.getElementById('txt3').value = result;
		   document.getElementById("actual_payment").value=actual_payment;
	//		alert(actual_payment);
		}
	//alert(payment_adjusted);
	}
	
	
	
	
	
</script>

<script type="text/javascript">
 var urlmenu = document.getElementById( 'menu1' );
 urlmenu.onchange = function() {
      window.open(  this.options[ this.selectedIndex ].value );
 };
</script>


</body>
</html>
