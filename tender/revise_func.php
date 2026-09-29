<?php 
	session_start();

	include('../dbcon.php');
	
	include('../baseurl.php');

	$userid   			= $_SESSION['usrid'];
	$finance_year 		= $_SESSION['finance_year'];
					
	$tender_id   		= $_POST['tender_id'];
	$remarks			= $_POST['remarks'];
	
	$sql 	= " SELECT* FROM sma_tender_header WHERE id = '$tender_id' ";
	$query 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($query);
	$srno 	= $r2['id'];
									
?>

<div class="content-wrapper123">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Revise Tender / RFP
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender / RFP</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						
						<form id="form1a" class="form-horizontal" action="copy_tender.php?sub=Revise" method="post" onsubmit="return checkreviseDATA123();" >
						  <div class="box-body">
							
						  <fieldset>
						  
							<input type="hidden" name="tender_hdr_id" value="<?php echo $tender_id;?>" >
							<input type="hidden" name="remarks" id="remarks" value="<?php echo $remarks;?>" >
						
					<?php	  
						  $sql = "SELECT b.* FROM `sma_tender_supplier` a, sma_party_mst b where a.tender_hdr_id = '$tender_id' and a.supplier_id = b.id ";
						  $q2 = mysqli_query($con, $sql);
					?>
							<div class="form-group">
								
								<div class="col-md-5">
									<label class="control-label"> Select Supplier</label>
								
									<div style="height:200px;width:400px;overflow:scroll;border:1px #999;">
										<table id="myTable" class="table table-hover panel panel-default table-bordered" >
									
											<tbody>
													<?php 
													$ix=0;
														while($r2 = mysqli_fetch_array($q2)){ 
															$ix = $ix +1;
													?>
													<tr>
														<td width="25%" style="text-align:left"><?php echo $r2['party_name'];?></td>
														<td width="5%" style="text-align:center"><input type="checkbox" id="supplier_IDRT<?= $ix; ?>" name="supplier_id[]" value="<?= $r2['id'];?>" /> </td>
														
													</tr>
												<?php }?>
											</tbody>
										</table>
									</div>
								
								</div>
								
							
								<div class="col-md-3">
								<label class="control-label"> Deadline Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
									<input type="text" class="form-control" id="deadline_DATERT" name="deadline_date" placeholder="dd-mm-yyyy" value="<?php echo date("d-m-Y");?>">
										</div>
									</div>
									
								<div class="col-md-2">
								<div class="bootstrap-timepicker">
									<label>Time </label>
									<div class="input-group">
										<input type="time" class="form-control timepicker123" id="deadline_time" name="deadline_time"  >

									</div>
								</div>
								</div>
									 
							</div>	
								
								
							<div class="box-footer">
									
									<div class="col-sm-6 text-right">
										<span>&nbsp;&nbsp;</span>
								
									</div>
									<div class="col-sm-6 text-right">
										
									<!--<<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>-->
										
										<a href="<?php echo $baseurl . 'tender/' . "edit.php?sub=edit&id=". $tender_id;?>" class="btn btn-default" title="Edit">Cancel</a>
										
										<span>&nbsp;&nbsp;</span>
										<input class="btn btn-primary" type="submit" value="Submit" name="Save" onclick="return checkreviseDATA123();">
										
									</div>

							</div>	
								
						  </fieldset>
						  </div>
						
						</form>
						
					</div>
				</div>
			</div>
		</div>
		
	</section>	

<script>									
	function checkreviseDATA(){
	
        //var supplier_id		 	=  $("#supplier_IDRT").val();
        var deadline_date 			=  $("#deadline_DATERT").val();
		
		var myForm = document.forms.form1a;
		var myControls = myForm.elements['supplier_IDRT1[]'];
		for (var i = 0; i < myControls.length; i++) {
			var aControl = myControls[i];
			alert(aControl);
		}

		var blank_var = '';
		if (document.getElementById('supplier_IDRT1').checked) {
		    supplier_id = document.getElementById('supplier_IDRT1').value;
			var blank_var ='ok';
		}
		if (document.getElementById('supplier_IDRT2').checked) {
		    supplier_id = document.getElementById('supplier_IDRT2').value;
			var blank_var ='ok';
		}
		
		//alert(blank_var +  ' '  + supplier_id + ' ' + deadline_date);
		if(blank_var != 'ok'){
			alert('Supplier selection is mandatory ##1! ');
			return false;
		}
		alert('Supplier selection is mandatory ##2! ');
		return false;
	}
</script>

<?php		
		
		


?>