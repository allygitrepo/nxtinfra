<?php
include("../header.php");
$modulePath = "payment/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php

		
		$status_task	 = $_GET['status'];
		
?>
	
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
        <h1>
            Task
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Task</a></li>
        </ol>
		
    </section>
		
		<div class="form-group">
		<span class="pull-right"><a href="<?php echo $baseurl . 'dashboard.php' ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		</div>
		
	<div class="col-md-12">
    	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
			<div class="box box-info">
            <!-- form start -->
					
            <form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
              <div class="box-body">
					
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                    
					
<!--PEnding TAsk -->								
					<div class="tab-pane123" id="tab_6123">
							
						<div class="modal-header123" >
							        
									<div id="taskentry">
									
									<!-- Enter Here -->
							
								
										<table id="prtablea" class="table table-bordered table-striped" >
											<thead>
												<tr>
													<th width="1%" style="text-align:right;">#</th>
													<th width="09%">Payment&nbsp;Id</th>
													<th width="08%">Company</th>
													<th width="12%">Vendor</th>
													<th width="13%">Doc. Name</th>
													<th width="07%">Ref.No.</th>
													<th width="20%"> Task</th>
													<th width="14%">Status</th>
													<th width="16%">Remark</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										$comid = $_SESSION['comid'];
										//$sql = "select * from sma_pending_task where ";	
										$sql = "select a.*, c.module_name, c.module_code, b.st_flag , b.company_id, 
											b.paid_to
											from sma_pending_task a, payment_header b, sma_module c 
												where b.id = a.payment_id and c.id = a.document_id 
												and b.company_id in ($comid) and a.status_task in( '$status_task' )
													order by a.id  desc ";
//echo $sql;

										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['id'];
											$payment_id	  		= $r2['payment_id'];
											$document_id  		= $r2['document_id'];
											$updated_by		    = $r2['updated_by'];
											$company_id			= $r2['company_id'];
											$paid_to			= $r2['paid_to'];
											$st_flag			= $r2['st_flag'];
											
											$sql="SELECT * FROM company where comp_id  = '$company_id' ";
											$q3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r3 = mysqli_fetch_array($q3);
											$comp_code			= $r3['comp_code'];
											
											if($st_flag=='S' || $st_flag=='D' || $st_flag=='C' || $module_code=='I' ){
												$sql="SELECT * FROM sma_party_mst where id  = '$paid_to' ";
												$q3 = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$r3 = mysqli_fetch_array($q3);
												$party_name			= $r3['party_name'];
											}
											else {
												$sql="SELECT * FROM sma_user where id  = '$paid_to' ";
												$q3 = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$r3 = mysqli_fetch_array($q3);
												$party_name			= $r3['username'];
											}
											
											$task_update_on  	= date('d-m-Y h:i ', strtotime($r2['task_update_on']));
											$pending_task		= $r2['pending_task']. ' <br>'. $task_update_on. ' ' . $updated_by;
											$completed_on		= date('d-m-Y h:i ', strtotime($r2['completed_on']));
											$completed_by		= $r2['completed_by'];
											$status_task		= $r2['status_task'];
											$module_name		= $r2['module_name'];
											$module_code		= $r2['module_code'];
											
											$task_remark		= $r2['task_remark'];
											
											$sql="SELECT * FROM payment_details where payment_hdr_id  = '$payment_id' ";
											$q3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r3 = mysqli_fetch_array($q3);
											$supp_id		= $r3['supp_id'];
											
											$sql = "SELECT * FROM sma_supplier_invoice where id = '$supp_id' and del !='Y' ";
								//echo $sql."<BR>";			
											$q3  = mysqli_query($con, $sql);
											$r3  = mysqli_fetch_array($q3);
											$draft_by			= $r3['draft_by'];

									//echo $st_flag;
									
											if( $st_flag=='S' || $st_flag=='R' ){
												$invoice_no		= $supp_id;
												$baseurl_link = $baseurl . "supp_invoice/edit.php?sub=edit&id=$invoice_no";
											}
											else if($st_flag=='D'){
												$sql = "SELECT * FROM sma_purchase_order where id = '$supp_id' and del !='Y' ";		
												$q3  = mysqli_query($con, $sql);
												$r3  = mysqli_fetch_array($q3);
												$our_po_ref_no 	 = $r3['id'];
												$baseurl_link = $baseurl . "purchase_order/edit.php?sub=edit&id=$our_po_ref_no";
												$invoice_no  = $our_po_ref_no;
												$draft_by		= $r3['draft_by'];
											}
											else if($st_flag=='A'){
												$sql = "SELECT * FROM sma_supplier_invoice a , sma_purchase_order b where b.id = a.our_po_ref_no and a.id = '$supp_id' and del !='Y' ";		
												$q3  = mysqli_query($con, $sql);
												$r3  = mysqli_fetch_array($q3);
												$our_po_ref_no 	 	= $r3['our_po_ref_no'];
												$approval_memo_ref 	= $r3['approval_memo_ref'];
												
												$baseurl_link = $baseurl . "approval/edit.php?sub=edit&id=$approval_memo_ref";
												$invoice_no  = $approval_memo_ref;
												$draft_by		= $r3['draft_by'];
											}
											else if($st_flag=='C'){
												$sql = "SELECT * FROM sma_travel_expenses where id = '$supp_id' and del !='Y' ";		
												$q3  = mysqli_query($con, $sql);
												$r3  = mysqli_fetch_array($q3);
												$approval_ref_no 	 = $r3['id'];
												$baseurl_link = $baseurl . "travel_approval/company_expense.php?sub=edit&id=$approval_ref_no";
												$invoice_no  = $approval_ref_no;
												$draft_by		= $r3['draft_by'];
											}
											else if($st_flag=='T'){
												$sql = "SELECT * FROM sma_travel_expenses where id = '$supp_id' and del !='Y' ";		
												$q3  = mysqli_query($con, $sql);
												$r3  = mysqli_fetch_array($q3);
												$approval_ref_no 	 = $r3['id'];
												$exp_type 	 = $r3['exp_type'];
												if($exp_type=='T'){
													$baseurl_link = $baseurl . "travel_approval/travel_expence.php?sub=edit&id=$approval_ref_no";
												}
												else if($exp_type=='R'){
													$baseurl_link = $baseurl . "travel_approval/regular_expence.php?sub=edit&id=$approval_ref_no";
												}
												$invoice_no  = $approval_ref_no;
												$draft_by		= $r3['draft_by'];
											}
											else if($module_code=='I'){
												$sql = "SELECT b.* FROM sma_supplier_invoice a, sma_ipc b 
												where a.id = b.sma_invoice_no and a.id = '$supp_id' and b.del !='Y' ";
												$q3  = mysqli_query($con, $sql);
												$r3  = mysqli_fetch_array($q3);
												$ipc_no 	  = $r3['id'];
												$baseurl_link = $baseurl . "ipc/ipc.php?sub=edit&id=$ipc_no";
												$invoice_no   = $ipc_no;
												$draft_by		= $r3['draft_by'];
											}
									
//echo $user. ' ' . $draft_by;
									
										if( $draft_by == $user || $user=='Admin' || $role =='Accountant' || $role=='CXO' ){
											$a='';
										}
										else {
											continue;
										}
										
											$url_var = urlencode($_SERVER['REQUEST_URI']);
											
											//echo $invoice_no . ' <<>> ' . $supp_id;
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												
												<td><?php echo $payment_id ?> </td>
												<td><?php echo $comp_code ?> </td>
												<td><?php echo $party_name ?> </td>
												
												<td><?php echo $module_name . '<BR>' . $draft_by ?> </td>
												<td><a href="<?php echo $baseurl_link ; ?>" target="_blank"><b><?php echo $invoice_no ?> </b?</a></td>
												<td><?php echo $pending_task ?> </td>
												
											<?php	if($draft_by == $user and ( $status_task =='P'  || empty($status_task) ) ){ ?>
												<td style="text-align:left;">
													<select class="form-control" name="status_task" id="status_task" onBlur="savetaskstatus(this.value,'status_task','<?php echo $record_id; ?>')" onClick="showEdit(this);" > 
												
														<option value=''>Select</option>
														<option value='S' <?php echo ($status_task == 'S')?'selected="selected"':'';?>>Submitted</option>
														<option value='P' <?php echo ($status_task == 'P')?'selected="selected"':'';?>>Pending</option>
														
													</select>
												</td>
											<?php	}
											if($draft_by == $user and ( $status_task =='C' || $status_task =='S' ) ){?>
												<td style="text-align:left;">
													<select class="form-control" disabled> 
														<option value=''>Select</option>
														<option value='C' <?php echo ($status_task == 'C')?'selected="selected"':'';?>>Completed</option>
														<option value='S' <?php echo ($status_task == 'S')?'selected="selected"':'';?>>Submitted</option>
													</select>
													<?php if($status_task == 'C'){ echo $completed_on. ' <BR>' . $completed_by; } ?>
												</td>
											<?php	}
											else if( $draft_by != $user  ){?>
												<td style="text-align:left;">
													<select class="form-control" name="status_task" id="status_task" onBlur="savetaskstatus(this.value,'status_task','<?php echo $record_id; ?>')" onClick="showEdit(this);" > 
												
														<option value=''>Select123</option>
														<option value='S' <?php echo ($status_task == 'S')?'selected="selected"':'';?>>Submitted</option>
														<option value='P' <?php echo ($status_task == 'P')?'selected="selected"':'';?>>Pending</option>
														<option value='C' <?php echo ($status_task == 'C')?'selected="selected"':'';?>>Completed</option>
													</select>
													<?php if($status_task == 'C'){ echo $completed_on. ' <BR>' . $completed_by; } ?>
												</td>
											<?php	} ?>
											
												<td>
													<input type="text" class="form-control" name="task_remark" id="task_remark" onBlur="savetaskstatus(this.value,'task_remark','<?php echo $record_id; ?>')" onClick="showEdit(this);" value="<?php echo $task_remark ?>">
													
												</td>
											</tr>
											
									<?php } ?>
											
										</tbody>
									</table>
									
										</div>
						
									</div>
								</div>
							
						</div>
							
					</div>		
<!--PEnding TAsk End -->
											
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



<!--Make to DraftPopup-->

	  

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        

 <!-- For Document Attachment End-->


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
		
	$("#submitTask").on("click", function(e){
		
        var sub 			= 'sub1';
		var document_id 	= $("#document_idA").val();
		var module_name 	= $("#document_idA option:selected").html();
		var pending_task 	= $("#pending_taskA").val();
		var si_id 			= $("#si_idP").val();		

//alert(si_id + module_name + ' ' + document_id + ' ' + pending_task);

		$('#addTask').modal('hide');
		var strURL 		= "task_func.php";
		$.post(strURL,{ document_id:document_id,module_name:module_name,pending_task:pending_task,si_id:si_id,sub1:sub},
							function(result){
		      $('#taskentry').html(result);
		});
			  
    });

	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
	function savetaskstatus(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
	//	alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetaskstatus.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }
	   
</script>
	
</body>
</html>

