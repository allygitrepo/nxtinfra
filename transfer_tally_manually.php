
<?php

include("header.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>

    <section class="content-header">
        <h1>
            Tally Manual update process
            <small>Tally Process</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Tally process</a></li>
            
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="transfer_tally_manually.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
								<label class="col-lg-2 control-label">Document Type</label>
								<div class="col-md-3">
									<select class="form-control" name="doc_type" id="doc_type" required >
										<option value=""> Select </option>
										<option value="All"> All </option>
										<option value="SI"> Supplier Invoice</option>
										<option value="OE"> Operating Expense </option>
										<option value="RE"> Regular Expense</option>
										<option value="PY"> Payment</option>
										<option value="PC"> Petty Cash</option>
									</select>	
								</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.Date From</label>
							<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
							</div>
							<label class="col-lg-2 control-label">Doc.Date To</label>
							<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
							</div>
							
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
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
</section>  
<?php }	?>


<?php 	
		include("footer.php");	
?>
	
<?php 
	if($_GET['sub'] == 'edit'){
		
//	session_start();
	include "dbcon.php";
	include "baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

	$start_date = date('Y-m-d', strtotime($_POST['start_date']));
	$end_date 	= date('Y-m-d', strtotime($_POST['end_date']));
	$doc_type	= $_POST['doc_type'];
					
//	$sql = " DELETE from tally_all_journal ";
//	mysqli_query($con,$sql);
	
	$sql = " SELECT record_id, record_type, doc_type, doc_no, doc_date, company_id, supplier_id, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, bank_name, effect, amount, narration, cheque_no, address, gst_no, state 
		FROM `tally_journal_entry`
			WHERE doc_date >= '$doc_date' and doc_date <= '$doc_date' ";

	if($doc_type=='All'){
		$sql .= '';
	}
	else if(!empty($doc_type)){
		$sql .= " and doc_type = '$doc_type' ";
	}

	$sql .= " ORDER BY doc_type, doc_no, effect ";

//echo $sql."<BR>";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_date 					= date('d-m-Y', strtotime($row['doc_date']));
		$supp_invoice_date 			= date('d-m-Y', strtotime($row['supp_invoice_date']));
		$record_id 					= $row['record_id'];
		$record_type 				= $row['record_type'];
		$doc_type 					= $row['doc_type'];
		$doc_no 					= $doc_type . $row['doc_no'];
		
		$supplier_id 				= $row['supplier_id'];
		$supp_invoice_no 			= $row['supp_invoice_no'];
		$account_type				= $row['account_type'];
		
		
		if($account_type !='V'){
			$account_type = '';
		}	
		
		
		$account_id					= $row['account_id'];
		$account_name 				= $row['account_name'];
		$bank_name 					= $row['bank_name'];
		$effect 					= $row['effect'];
		$amount 					= $row['amount'];
		$narration 					= $row['narration'];
		$cheque_no 					= $row['cheque_no'];
		$address 					= $row['address'];
		$gst_no 					= $row['gst_no'];
		$pan_no 					= $row['pan_no'];
		$mobile_no 					= $row['mobile_no'];
		$state 						= $row['state'];
		$company_id					= $row['company_id'];

		$sql = "SELECT * FROM `company` WHERE comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		$r   = mysqli_fetch_object($res);
		$company_code = $r->comp_code;
		
		$sql = " INSERT INTO tally_all_journal (record_id, record_type, doc_type, doc_no, doc_date, company_code, supplier_id, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, bank_name, effect, amount, narration, cheque_no, address, gst_no, state, status, pan_no, mobile_no ) 
		values ('$record_id', '$record_type', '$doc_type', '$doc_no', '$doc_date', '$company_code', '$supplier_id', '$supp_invoice_no', '$supp_invoice_date', '$account_type', '$account_id', '$account_name', '$bank_name', '$effect', '$amount', '$narration', '$cheque_no', '$address', '$gst_no', '$state', 'R', '$pan_no', '$mobile_no')";
//echo $sql."<BR>";

		mysqli_query($con,$sql);
		
		if($doc_type=='PY'){
			$sql = "UPDATE payment_header set tally_status ='U' where tally_status = 'R' and id = '$doc_no' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='SI'){
			$sql = "UPDATE supplier_invoice set tally_status ='U' where tally_status = 'R' and id = '$doc_no' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='PC'){
			$sql = "UPDATE sma_supplier_invoice set tally_status ='U' where tally_status = 'R' and id = '$doc_no' ";
			mysqli_query($con,$sql);
		}
		else {
			$sql = "UPDATE sma_travel_expenses set tally_status ='U' where tally_status = 'R' and id = '$doc_no' ";
			mysqli_query($con,$sql);
		}
		
	}

	$sql = "UPDATE`tally_journal_entry` set status ='U' where status = 'R' ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);

    echo " Workflow data transfer to Tally DB. ";

}
	
	
	