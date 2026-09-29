<?php
	
	include "../baseurl.php"; 

	include("../header_only.php");
	include "../dbcon.php"; 
$modulePath = "purchase_order_entry/"; 
session_start();

$statusm 	= $_GET['status'];
$emid 		= $_GET['emid'];
$vnid 		= $_GET['vnid'];
$po_id 		= $_GET['id'];
$_SESSION['reset'] = '1';

	$sql = "SELECT * FROM sma_purchase_order WHERE id  = '$po_id' "; //
	$qry = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($qry);
	$status 		= $r2['status'];
	$approval_status= $r2['approval_status'];
	$vnid			= $r2['to_supplier'];
	
	
		$sql="select * from sma_party_mst where id = '$vnid' ";			
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->party_name;
			$id		 		= $r->id;
			$_SESSION['usrid'] = $id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
	$userid   	= $_SESSION['usrid'];
	$_SESSION['user'] = $vnid;

	/* if($approval_status=='Rejected'){
		$baseurl= 'window_to_close.php';	
		echo "<script>alert('Already Rejected !!!');window.location.href='$baseurl';</script>";
	}
	if($status=='Completed'){
		$baseurl= 'window_to_close.php';	
		echo "<script>alert('Already Approved !!!');window.location.href='$baseurl';</script>";
	}
 */
	$_SESSION['budget_id'] ='';
		$id = $_GET['id'];
		$po_id = $_GET['id'];
		$sql="Select * from sma_purchase_order where id ='$po_id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

	
	$status 	= $row['status'];
	$del 		= $row['del'];
	$new_po_no 	= $row['new_po_no'];
	$po_new_no 	= $row['po_new_no'];
	$old_po_no 	= $row['old_po_no'];
	
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Order
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard_athang.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Order</a></li>
            <li class="active">Edit</li>
        </ol>
		
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						
					</div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Give Remarks if any</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="box-body">
                        <div class="col-md-12">
                        <div class="box-body">
							<form class="form-horizontal">
                                        
							<?php   
										
							$statusm 	= $_GET['status'];
							$emid 		= $_GET['emid'];
							$vnid 		= $_GET['vnid'];
							
							?>	
										
							<input type="hidden" id="approverC" name="approver" value='<?= $vnid ?>' >
							
							<input type="hidden" id="po_idE" name="po_id" value="<?= $po_id; ?>" >
							<input type="hidden" id="emidE" name="emid" value="<?= $emid; ?>" >
							
							<div class="form-group">
								<div class="col-sm-10">
									<label for="approver" class="col-sm-2 control-label">&nbsp;</label>
                                
									<input type="RADIO" id="modeA" name="mode" checked value='Accept' > <b>Accept</b> &nbsp;&nbsp;&nbsp;
								
									<input type="RADIO" id="modeB" name="mode" value='Reject' > <b>Reject</b>
									
								</div>
							</div>
							
							<div class="form-group">
								<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                <div class="col-sm-10">
									<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
								</div>
							</div>
							
							</form>	
									
                        </div>
			
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
                <h4 class="modal-title" id="rejectAuthority">Give Remarks if any </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                <div class="box-body">
									<form class="form-horizontal">
											
									<?php   
													
										$statusm 	= $_GET['status'];
										$emid 		= $_GET['emid'];
										$vnid 		= $_GET['vnid'];
									?>	
											
										<input type="hidden" id="approverR" name="approver" value='<?= $vnid ?>' >
										<input type="hidden" id="modeR" name="mode" value='Reject' >
										<input type="hidden" id="po_idE" name="po_id" value="<?= $po_id; ?>" >
										<input type="hidden" id="emidR" name="emid" value="<?= $emid; ?>" >
											
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

<!--/.col (right) -->
										
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

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<script>

    $("#submitApprove").on("click", function(e){
		//$('.hidden-div').hide();
		//$('#predit').html('Wait...');
        var sub 			= 'sub99';
		
		if (document.getElementById('modeA').checked) {
		    mode = document.getElementById('modeA').value;
		}
		else if (document.getElementById('modeR').checked) {
		    mode = document.getElementById('modeR').value;
		}

		var po_id		 	=  $("#po_idE").val();
	    var to_supplier		=  $("#approverC").val();
		var approver		=  $("#approverC").val();
		var remarks			=  $("#remarksA").val();
//alert(mode+' #0# '+to_supplier+' #1# '+remarks+' #5# '+po_id);
		
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						to_supplier:to_supplier,
						approver:approver,
						remarks:remarks,
						sub99:sub},
						function(result){
		     // $('#predit').html(result);
			 alert('Thank you accepting our order!');
			  var win = window.open("about:blank", "_self");
			  win.close();
				
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub 			= 'sub99';
		var mode		 	=  $("#modeR").val();
		var po_id		 	=  $("#po_idR").val();
	    var to_supplier		=  $("#approverR").val();
		var approver		=  $("#approverR").val();
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+po_id);

		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ po_id:po_id,
						mode:mode,
						to_supplier:to_supplier,
						approver:approver,
						statusap:mode,
						remarks:remarks,
						sub99:sub},
						function(result){
		    //  $('#predit').html(result);
			  
			   var win = window.open("about:blank", "_self");
				win.close();
				
		});
		
	});

</script>


<?php if($statusm=='A'){ ?>
<script>
    $(window).load(function(){
        $('#approvalAuthority').modal('show');
    });
</script>
<?php } ?>

<?php if($statusm=='R'){ ?>
<script>
    $(window).load(function(){
        $('#rejectAuthority').modal('show');
    });
</script>
<?php } ?>

</body>
</html>
