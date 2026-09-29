<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

?>

<?php
	if(isset($_POST['sub1'])){
		
		$modulePath 	= "payment/";
		$module_name 	= $_POST['module_name'];
		$document_id 	= $_POST['document_id'];
		$pending_task 	= $_POST['pending_task'];
		$payment_id	    = $_POST['py_id'];
		
		$user = $_SESSION['user'];
		$userid 	= $_SESSION['usrid'];
	
		
	/*	$sql 	= " select * from payment_header where  id = '$py_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$paid_date		    = date('Y-m-d', strtotime($r2['paid_date']));
		
	 	$sql="SELECT * FROM sma_module where id  = '$document_id' ";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$module_name		= $r2['module_name'];
		 */
		$sql = "INSERT INTO sma_pending_task(payment_id, document_id, pending_task, status_task , task_update_on, updated_by)
				VALUES( '$payment_id', '$document_id', '$pending_task', 'P', now(), '$user' ) ";
		mysqli_query($con, $sql);
		$task_id = mysqli_insert_id($con);
		echo mysqli_error($con);
		//echo $sql."<BR>";exit();
		
		$sql = " INSERT into task_workflow( doc_type, doc_id, payment_id, create_by, create_date, status )
			values ( '$document_id', '$task_id', '$payment_id', '$userid', now(), 'P' ) ";
		mysqli_query($con,$sql);
		
			
?>

		<table id="prtablea" class="table table-bordered table-striped" width="100%" >
			<thead>
			<tr>
				<th width="10%" style="text-align:right;">#</th>
				<th width="20%">Document Name</th>
				<th width="50%">Task</th>
				<th width="10%">Status</th>
				<th width="10%">Action</th>
			</tr>
			</thead>
											
			<tbody>
										
			<?php
										
										$sql = "select * from sma_pending_task where payment_id = '$payment_id' order by id ";	
//echo $sql;										
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['id'];
											$payment_id	  		= $r2['payment_id'];
											$document_id  		= $r2['document_id'];
											$pending_task		= $r2['pending_task'];
											$status_task 	 	= $r2['status_task'];
											
											if($status_task=='P'){
												$status_task = 'Pending';
											}
											else if($status_task=='C'){
												$status_task = 'Completed';
											}
											
											$sql="SELECT * FROM sma_module where id  = '$document_id' ";
											$q3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r3 = mysqli_fetch_array($q3);
											$module_name		= $r3['module_name'];
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $module_name ?> </td>
												<td><?php echo $pending_task ?> </td>
												<td style="text-align:right;"><?php echo $status_task; ?> </td>
												<td>
										
													<a href='#modalEditTally' data-id='<?php echo $record_id;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditTally<?php echo $record_id;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
													<?php // include "edit_task_func.php"; ?>	
													<a href="delete_tally.php?sub=delete&record_id=<?php echo $record_id;?>&url=<?php echo $url_var ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
												
												</td>
											</tr>
											
										<?php } ?>	
									
										</tbody>
									</table>
									
<?php			
		//$value = "https://hcone.co.in/workflow2020/payment/pay_edit_test.php?sub=edit&id=5002&active6=active";
		//$value = "<script>window.location.href='edit.php?sub=edit&id=$payment_id&active6=active';</script>";
	
		//echo $value;
	
	}
?>		
