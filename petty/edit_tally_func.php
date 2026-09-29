<!-- Modal Tally Edit Item-->
<div class="modal fade" id="modalEditTally<?php echo $record_id;?>" role="dialog" aria-labelledby="modalEditTallyLabel">
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
										$account_id  		= $r3['account_id'];
										$account_name  		= $r3['account_name'];
										$amount		   		= $r3['amount'];
										$narration		   	= $r3['narration'];
										$cheque_no		   	= $r3['cheque_no'];
										$address		   	= $r3['address'];
										$gst_no		   		= $r3['gst_no'];
										$state		   		= $r3['state'];
										$tally_status		= $r3['tally_status'];

						?>
					<div id="box">
					<div class="col-md-12">
                       <div class="box-body">
                        <form class="form-horizontal" action="#" method="POST">
						  <div class="box-body ">
							<fieldset>
								
								<input type="hidden" name="record_id" id="record_idB" value="<?php echo $record_id; ?>" >
								<input type="hidden" name="si_hdr_id" id="si_hdr_idB" value="<?php echo $doc_no; ?>" >
								
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c </label><br>
                                            	<input type="radio" id="type_acB" name="account_type" value='U' onchange="getaccount(this.value)" <?php echo ($account_type == 'U' )?'checked="CHECKED"':'';?>> User
												<input type="radio" id="type_acB" name="account_type" value='B' onchange="getaccount(this.value)" <?php echo ($account_type == 'B' )?'checked="CHECKED"':'';?> > Budget
												<input type="radio" id="type_acB" name="account_type" value='V' onchange="getaccount(this.value)" <?php echo ($account_type == 'V' )?'checked="CHECKED"':'';?>> Vendor
												<input type="radio" id="type_acB" name="account_type" value='A' onchange="getaccount(this.value)" <?php echo ($account_type == 'A' )?'checked="CHECKED"':'';?>> Account
												
												
                                            </div>
                                        </div>
									<?php	
											if( $account_type =='U' ){
												$sql = "select id, username as account_name from sma_user where id = '$account_id' ";
												$q2 	  = mysqli_query($con, $sql);
												$r2 = mysqli_fetch_array($q2);
												//$account_name = $r2['account_name'];
											}
								//echo $sql;
											if( $account_type =='A' ){
												$sql = "SELECT * FROM account_mst where account_name = '$account_name' ";
											
												$re = mysqli_query($con, $sql);
												echo mysqli_error($con);
												$r5 = mysqli_fetch_array($re);
												$account_id = $r5['id'];		
													
												$sql = "SELECT id, account_name as 'account_name' FROM account_mst where 1  order by account_name "; //account_type = 'E'
											}
											else if($account_type =='V'){
												$sql = "SELECT id, party_name as 'account_name' FROM sma_party_mst order by party_name ";
											}
											else if($account_type =='B'){
												$sql = "SELECT id, category as 'account_name' FROM sma_budget_category order by category ";
											}
									?>	
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class=" control-label">Account Name</label>                                        
												<select class="form-control select2" id="account_idB" name="account_id" required="required" onchange="gettdsamt(this.value)" >
													<option value="">Select</option>
													<?php
													$result = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r4 = mysqli_fetch_array($result)){
													?>	
													<option value="<?php echo $r4['id']?>" <?php echo ($account_id == $r4['id'])?'selected="selected"':'';?> ><?php echo $r4['account_name'] ?></option>
												<?php } ?>
												</select>
											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect </label><br>
                                            	<input type="radio"  id="effectB" name="effect" value='Dr' <?php echo ($effect == 'Dr' )?'checked="CHECKED"':'';?>> Debit
												<input type="radio"  id="effectB" name="effect" value='Cr' <?php echo ($effect == 'Cr' )?'checked="CHECKED"':'';?>> Credit
											</div>
                                        
											<div class="col-sm-6">
												<label for="approver" class="control-label">Amount</label>
											<span class="gettdsamt">	
                                            	<input type="text" class="form-control" id="amountB" autocomplete="off" style="text-align:right;;" name="amount" value="<?php echo $amount; ?>" >
											</span>	
                                            </div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationB"><?php echo $narration; ?></textarea>
											</div>
										</div>
											
										
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<?php if (empty($tally_status)){ ?>
								<input type="submit" class="btn btn-primary" id="editItem123" name="editTally"  value="Save">
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

