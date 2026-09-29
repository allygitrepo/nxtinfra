<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "payment/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php
	if(isset($_POST['Save'])){
			
			$srno					= $_POST['srno'];
			$py_id					= $srno;
			$company_id				= $_POST['company_id'];
			$paid_to				= $_POST['paid_to'];
			$paid_date				= date('Y-m-d', strtotime($_POST['paid_date']));
			$cash_bank_name			= $_POST['cash_bank_name'];
			$cheque_no				= $_POST['cheque_no'];
			$tds_amount				= $_POST['tds_amount'];
			$total_amount_paid		= $_POST['total_amount_paid'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$remarks_hdr			= $_POST['remarks_hdr'];
			$status 			    = 'Draft';
			$changed_by             = $_SESSION['user'];
			$user   				= $_SESSION['user'];

			$due_date =  date('Y-m-d', strtotime("$credit_days day",strtotime($_POST['paid_date'])));

  			$sql = "insert into payment_header (id, paid_to, company_id, paid_date, cash_bank_name, cheque_no, dated, tds_amount, total_amount_paid, remarks, status, draft_by, draft_dated, changed_by )
			Values('$srno', '$paid_to', '$company_id', '$paid_date', '$cash_bank_name', '$cheque_no', '$dated', '$tds_amount', '$total_amount_paid', '$remarks_hdr' , '$status', '$user', now(), '$changed_by' )";
			
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$supp_id				= $_POST["supp_id"];

			for($i = 0; $i < sizeof($supp_id); $i++){
				$supp_id			= $_POST["supp_id"][$i];
				$invoice_date		= $_POST["invoice_date"][$i];
				$supplier_invoice_no= $_POST["supplier_invoice_no"][$i];
				$bal_amount			= $_POST["bal_amount"][$i];
				$payment_adjusted	= $_POST["payment_adjusted"][$i];
				$deduction_head		= $_POST["deduction_head"][$i];
				$deduction_amt		= $_POST["deduction_amt"][$i];
				$actual_payment		= $_POST["actual_payment"][$i];
				$remarks_dtl			= $_POST["remarks_dtl"][$i];
				
				$actual_payment		= $payment_adjusted + $deduction_amt;
				$tot_payment_adjusted	= $payment_adjusted + $deduction_amt;
				
				$tot_tds_amount		= $tot_tds_amount + $deduction_amt;
				$tot_amount			= $actual_payment;
				
				if( ($payment_adjusted + $deduction_amt) >0 ){
					$sql = " insert into `payment_details` (payment_hdr_id, supp_id, invoice_date, supplier_invoice_no, bal_amount, payment_adjusted, deduction_head, deduction_amt, actual_payment, remarks ) values ('$srno', '$supp_id', '$invoice_date', '$supplier_invoice_no', '$bal_amount', '$payment_adjusted', '$deduction_head', '$deduction_amt', '$actual_payment', '$remarks_dtl') ";
					$r2 = mysqli_query($con, $sql);
					
					$sql = " update sma_supplier_invoice set bal_amount = bal_amount - $tot_payment_adjusted where id = '$supp_id' ";
					$r2 = mysqli_query($con, $sql);
					
					$sql = " update payment_header set tds_amount = tds_amount + $tot_tds_amount, total_amount_paid = total_amount_paid + '$tot_amount' - $tot_tds_amount where id = '$srno' ";
					$r2 = mysqli_query($con, $sql);
					
					//mail to draft user
					$sql   = "SELECT * FROM `sma_user` where userid in (Select draft_by from sma_supplier_invoice where id = '$supp_id' ) ";
					$query = mysqli_query($con, $sql);
					$row   = mysqli_fetch_array($query);
					$user_email = $row['email'];
					$user_name = $row['username'];
					
					$sql   = "select * from sma_party_mst where id in (Select suplier_name from sma_supplier_invoice where id ='$supp_id') ";
					$query = mysqli_query($con, $sql);
					$row   = mysqli_fetch_array($query);
					$mail_to = $row['party_email'];
					$party_name = $row['party_name'];
					
					$msg = 'Payment for Supplier Invoice Number : '.$supplier_invoice_no . ' ' . 'Dated : ' . date("d-m-Y"). "<br>";
					$msg .= 'Payment Paid : ' . $payment_adjusted . "<br>";
					$msg .= 'Deduction under : ' . $deduction_head . ' : ' . $deduction_amt. "<br>";
					include "py_mail.php";
					
				}
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
					
					//echo $sql;
					
				}
			}
			
			//exit();
			
			$baseurl.=$modulePath;
//$baseurl.=$modulePath.'edit.php?id='.$srno.'&active=active';

			echo "<script>window.location.href='$baseurl';</script>";
		
		}

?>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
            Payment Entry
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Payment Entry</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="add.php?sub=add" method="post"  enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php
							$sql  = " SELECT max(id) as srno from payment_header ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$srno = $r1['srno']+1;
						?>
			
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Paid Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="paid_date" name="paid_date" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>
							
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Company</label>
								<select class="form-control select2" name="company_id" id="company_Id" onchange="getsupplier(this.value)" >
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
								<span id="getsupplier">
									
								</span>	
							</div>
						
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Paid via</label>
									<select class="form-control" id="cash_bank_name" name="cash_bank_name" >
										<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM account_mst where account_type = 'B' ORDER BY account_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
										<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'] ?></option>
										<?php } ?>
									</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Cheque /UTR Number</label>
								<input type="text" class="form-control" id="cheque_no" name="cheque_no" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Dated</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" placeholder="" value="<?php echo date("d-m-Y"); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>	
							</div>

							<div class="col-md-2">
								<label class="control-label">Total Amount Paid</label>
								<input type="text" class="form-control" id="total_amount_paid" style="text-align:right;" name="total_amount_paid" readonly value="" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Remarks</label>
								<textarea rows="2" class="form-control" id="remarks_hdr" name="remarks_hdr" ></textarea>
							</div>
						</div>
						
						<span id="getinvoice">
						
						</span>
						
						
                       <!-- Attachments -->
						<div class="box">	
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td><label class="col-sm-1 control-label">Document</label></td>    
										<td>
                                            <select class="form-control select2 doctype" name="doctype[]">
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
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
                                         <td><button type="button" name="add" id="add_doc" class="btn btn-success">Add More</button></td>  
                                    </tr>  
                               </table>  
                            
							</div>
						</div>	
						<!-- Attachments -->
						
						<div class="box-footer">
							<div class="col-sm-6">
									<?php $did = $_GET['id']; 
										$baseurl1 = $baseurl.$modulePath;
									?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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
  <!-- /.content-wrapper -->

 
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}
	
	function getcreditdays(id){
		
        var sub    = 'sub4';
//		var paid_date = document.getElementById(paid_date);
//	alert(paid_date);,paid_date:paid_date
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getcreditdays').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}
		
</script>
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
		
	
<!-- For Document Attachment Start-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>
 $(document).ready(function(){  
      var i=1;  
      $('#add_doc').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">Document</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
  <!-- For Document Attachment End-->
</script>
  
<?php 	
 
		include("../footer.php");	
		
?>
	
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

	
	function getinvoice(id){
	
		var sub    = 'sub4';
		
		var company_id = document.getElementById("company_Id").value;
//alert(company_id);		
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub4:sub},function(result){
		      $('#getinvoice').html(result);
		});
		
	}
	
	function getactual(){

		var payment_adjusted = document.getElementById("payment_adjusted").value;
		var deduction_amt    = document.getElementById("deduction_amt").value;
		var deduction_amt1   = document.getElementById("deduction_amt1").value;
		var actual_payment   = parseInt(payment_adjusted) - parseInt(deduction_amt) - parseInt(deduction_amt1);
		if (!isNaN(actual_payment)) {
       //  document.getElementById('txt3').value = result;
		   document.getElementById("actual_payment").value=actual_payment;
	//		alert(actual_payment);
		}
	
	}
	
	
	
	function checkadjusted(){
		var bal_amount = document.getElementById("bal_amount").value;
		var payment_adjusted = document.getElementById("payment_adjusted").value;
		
//		if (payment_adjusted > bal_amount){
//			alert("Payment adjustment amount should not be greater then balance amount...");
//			document.getElementById("payment_adjusted").value = '';
//			return false;
//		}

	}
	
	function getsupplier(id){
	
		var sub    = 'sub6';
//alert(sub);
		var strURL = "pay_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getsupplier').html(result);
		});
		
	
	}
	
 
</script>
