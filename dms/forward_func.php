
<!--Forward Workflow Popup-->

<div class="modal fade" id="forwardAuthority<?php echo $rid?>" role="dialog" aria-labelledby="forwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="forwardAuthority">Forward To</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idF" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeF" name="mode" value='Send'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<input type="hidden" name="rid" id="ridF" value="<?php echo $rid; ?>" >
										
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Share To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control"  name="send_to" id="send_toF" <?php echo $readonly; ?> >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksF"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitSend">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	
