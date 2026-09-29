
<div class="modal fade" id="modalEditItemq<?php echo $approval_srno;?>" role="dialog" aria-labelledby="modalEditItemqLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				
				<h4 class="modal-title" id="modalEditItemqLabel" style="text-align:left;">Edit Supplier </h4>
                
	        </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row123">
						<?php
							
							$srno = $approval_srno;
							
							$sql  = "SELECT * from sma_tender_supplier where id = '$srno' ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$tender_hdr_id 	= $r1['tender_hdr_id'];
							$remarks 			= $r1['remarks'];
							$vendor_selected	= $r1['vendor_selected'];
							$email_id			= $r1['email_id'];
							$supplier_id		= $r1['supplier_id'];
							
						?>

                        <form class="form-horizontal" id="editForm" action="#" method="POST">
							<input type="hidden" id="tender_hdr_id" name="tender_hdr_id" value="<?php echo $tender_hdr_id;?>" >
							<input type="hidden" id="approval_srno" name="approval_srno" value="<?php echo $approval_srno;?>" >
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-4 control-label">Supplier</label>
                                <div class="col-sm-8">
								   <select class="form-control select2-123" id="supplier_name" name="supplier_name" <?php echo $readonly;?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($supplier_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-4 control-label">Email ID </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="email_id" name="email_id"  <?php echo $readonly;?> placeholder="Item email_id..." value="<?php echo $email_id;?> " >
                                </div>
                            </div>
                            
							<?php $checked = ''; if($vendor_selected == 'Y'){ $checked = "CHECKED";}
							
						//	if($vendor_selected == 'Y'){ 
							?>
						<!--	<div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-4 control-label">Vendor Selected</label>
                                <div class="col-sm-1">								    
                                    <input type="checkbox"  id="vendor_selected" name="vendor_selected"   <?php echo $checked; ?> value="Y" >
								</div>
                            </div>
						-->	
                            <?php //} ?>
							
							<div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-4 control-label">Remarks</label>
								<div class="col-sm-8">
                                    <textarea rows="3" cols="50" class="form-control" id="remarks"  <?php echo $readonly;?> name="remarks"><?= $remarks;?></textarea>
                                </div>
                            </div>
							
							
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<?php if (empty($readonlya)) { ?>		
									<input type="submit" class="btn btn-primary" id='editSave123' name='editvendor' value="Save" >
						<?php } ?>			
								</div>
						
                        </form>
                    </div>
                </section>
            </div>
          
        </div>
    </div>
</div>
