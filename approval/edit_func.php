
<div class="modal fade" id="modalEditItemq<?php echo $approval_srno;?>" role="dialog" aria-labelledby="modalEditItemqLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalEditItemLabel" style="text-align:left;" >Edit Supplier Quotes </h4>
	        </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row123">
						<?php
							
							$srno = $approval_srno;
							
							$sql  = "SELECT * from sma_approval_details where approval_srno = '$srno' ";
							
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$approval_hdr_id 	= $r1['approval_hdr_id'];
							$values 	= $r1['values'];
							$remarks 	= $r1['remarks'];
							$vendor_selected	= $r1['vendor_selected'];
							$quote_ref_no		= $r1['quote_ref_no'];
							$supplier_id		= $r1['supplier_name'];
							//if($status =='Completed'){ 
							//	$readonly = "READONLY";
							//}
						
							$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and id = '$supplier_id' ";
							}	
						?>

                        <form class="form-horizontal" id="editForm" action="saveitem.php?sub=Edit" method="POST">
							<input type="hidden" id="approval_hdr_id" name="approval_hdr_id" value="<?php echo $approval_hdr_id;?>" >
							<input type="hidden" id="approval_srno" name="approval_srno" value="<?php echo $approval_srno;?>" >
                            
                            <div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-4 control-label">Supplier</label>
                                <div class="col-sm-8">
								   <select class="form-control select2-123" id="supplier_name" required name="supplier_name" <?php echo $readonly;?> >
							<?php	if($status == 'Draft'){ ?>
                             		<option value=""> Select </option>
							<?php } ?>
										<?php $sql = "select * from sma_party_mst where 1 $sqla order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($supplier_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <label for="itemquote_ref_no" class="col-sm-4 control-label">Quote Ref.No.</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="quote_ref_no" name="quote_ref_no"  <?php echo $readonly;?> placeholder="Item quote_ref_no..." value="<?php echo $quote_ref_no;?> " >
                                </div>
                            </div>
                            
							<?php $checked = ''; if($vendor_selected == 'Y'){ $checked = "CHECKED";} ?>
							
							<div class="form-group col-md-12">
                                <label for="itemQuantity" class="col-sm-4 control-label">Supplier  Selected</label>
                                <div class="col-sm-1">								    
                                    <input type="checkbox"  id="vendor_selected" name="vendor_selected"  <?php echo $readonly;?> <?php echo $checked; ?> value="Y" >
								</div>
                            </div>
                            
							
							<div class="form-group col-md-12">
                                <label for="itemvaluess" class="col-sm-4 control-label">Quoted Amount</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" style="text-align:right;" id="values"  <?php echo $readonly;?> <?php echo $readony ?> name="values" value="<?php echo $values;?>" >
                                </div>
                            </div>
                            
							
							<div class="form-group col-md-12">
                                <label for="itemRate" class="col-sm-4 control-label">Remarks</label>
								<div class="col-sm-5">
                                    <textarea rows="3" cols="50" class="form-control" id="remarks"  <?php echo $readonly;?> name="remarks"><?php echo $remarks;?></textarea>
                                </div>
                            </div>
							
							
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<?php	
							if($status !='Completed' or $user=='Admin' ){ 
						?>			
									<input type="submit" class="btn btn-primary" id='editSave123' name='edit' value="Save" >
						<?php } ?>
								</div>
						
                        </form>
                    </div>
                </section>
            </div>
           <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" >Save changes</button>
            </div>-->
        </div>
    </div>
</div>
