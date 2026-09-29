	<div class="modal fade" id="modalHistoryItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalHistoryItemLabel" >
    <div class="modal-dialog" role="document" style="width:950px;" >
	
        <div class="modal-content"   >
			
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>&nbsp;&nbsp;
				<?php 
					$srno = $rid;
					$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id ";
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
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" ><b>Document Number : <?php echo $rid;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?></b></span>
								
                <h4 class="modal-title" id="modalHistoryItemLabel"> Workflow History </h4>
				
            </div>
            <div class="modal-body" >
                <section class="content">
				<div class="row">
				   
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
							$srno = $rid;
							$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PY' order by id desc";
					//	echo $s1;
							$res  = mysqli_query($con, $s1);
							echo mysqli_error($con);
							while($r1 = mysqli_fetch_array($res)){
								$id 			= $r1['id'];
								//$create_by 			= $r1['create_by'];
								//$create_date		= $r1['create_date'];
								$status				= $r1['status'];
								$reviewed_by 		= $r1['reviewed_by'];
								$approved 			= $r1['approved'];
								$approved_date		= date('d-m-Y', strtotime($r1['approved_date']));
								$remarks 			= $r1['remarks'];
								
								$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
								$r3 = mysqli_query($con, $s2);
								$rw1 = mysqli_fetch_array($r3);
								$reviewed_by = $rw1['username'];
								
								$role = $rw1['role'];

								$sl="SELECT * FROM sma_role where id = '$role' ";
								$r3 = mysqli_query($con, $sl);
								$rw = mysqli_fetch_array($r3);
								$role = $rw['role'];
								
								$create_by		= $r1['create_by'];
								$create_date	= date('d-m-Y', strtotime($r1['create_date']));
								
								$sl="SELECT * FROM sma_user where id = '$create_by' ";
								$r3 = mysqli_query($con, $sl);
								$rw = mysqli_fetch_array($r3);
								$create_by = $rw['username'];
								
						?>      
							<tr>
								<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>	 	
								<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
								<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
								<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
								<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
								<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
								<td width="10%" style="text-align:left;"><?php echo $role;?></td>
								<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
															</tr>
						<?php	}	?>
							</tbody>
                        </table>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							
						</div>
			
                        
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
