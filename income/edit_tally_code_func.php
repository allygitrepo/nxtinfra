<!-- Modal Tally Edit Item-->
<div class="modal fade" id="modalEditTally<?php echo $record_id;?>" role="dialog" aria-labelledby="modalEditTallyLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditTallyLabel">Edit Tally Account </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $record_id;
									
							$sql = "select * from tally_journal_entry where record_id = '$srno' ";
						//echo $sql;	
										$q3 	= mysqli_query($con, $sql);
										$r3 	= mysqli_fetch_array($q3);
										$record_id		  	= $r3['record_id'];
										$effect		  		= $r3['effect'];
										$record_type  		= $r3['record_type'];
										$doc_no		  		= $r3['doc_no'];
										$doc_date	 	 	= $r3['doc_date'];
										$supp_invoice_no  	= $r3['supp_invoice_no'];
										$supp_invoice_date  = $r3['supp_invoice_date'];
										$account_type  		= $r3['account_type'];
										$account_type_ori	= $r3['account_type'];
										$account_id  		= $r3['account_id'];
										$account_name  		= $r3['account_name'];
										$budget_head  		= $r3['budget_head'];
										$amount		   		= $r3['amount'];
										$narration		   	= $r3['narration'];
										$cheque_no		   	= $r3['cheque_no'];
										$address		   	= $r3['address'];
										$gst_no		   		= $r3['gst_no'];
										$state		   		= $r3['state'];
										$tally_status		= $r3['tally_status'];
								if($account_type =='B' || $account_type =='E' || $account_type =='D' ){
									$account_type = 'A';
								}
									
//echo $account_type. ' ' . $account_id; 
						?>
					<div id="box">
					<div class="col-md-12">
                       <div class="box-body">
                        <form class="form-horizontal" action="#" method="POST">
						  <div class="box-body ">
							<fieldset>
								
								<input type="hidden" name="record_id" id="record_idB" value="<?php echo $record_id; ?>" >
								<input type="hidden" name="si_hdr_id" id="si_hdr_idB" value="<?php echo
								$doc_no; ?>" >
								<input type="hidden" name="account_type" id="account_typeB" value="<?php echo $account_type_ori; ?>" >
								
									<?php	
											
										$sql = "select * from sma_budget_subgroup where 1 order by budget_head ";//
									?>	
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class=" control-label">Budget Head</label>                                        
												<select class="form-control select2" id="budget_headB" name="budget_head" >
													<option value="">Select</option>
													<?php
													$res = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r4 = mysqli_fetch_array($res)){
													?>	
													<option value="<?php echo $r4['id']?>" 
													<?= ($budget_head == $r4['id'] || $budget_head == $r4['budget_head'])?'selected="selected"':'';?> ><?= $r4['budget_head']. ' ' . $r4['budget_code'] ?></option>
												<?php } ?>
												</select>
											</div>
										</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<?php if (empty($tally_status)){ ?>
								<input type="submit" class="btn btn-primary" id="editItem123" name="editTallycode"  value="Save">
							<?php } ?>	
							</div>
			
							</fieldset>
						  </div>
                        </form>
						</div>
						</div>
                    </div>
				  </div>
                </section>
				
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Item-->
