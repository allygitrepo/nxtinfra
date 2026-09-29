<!-- Modal PV Edit Item-->
<div class="modal fade" id="modalEditPVE<?php echo $dtlid;?>" role="dialog" aria-labelledby="modalEditPVELabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditPVELabel">Edit Account </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $dtlid;
									
							$sql = "select * from sma_provisional_jv_details  where id = '$srno' ";
						//echo $sql;	
										$q3 	= mysqli_query($con, $sql);
										$r3 	= mysqli_fetch_array($q3);
										$effect		  		= $r3['effect'];
										$doc_no		  		= $r3['provisional_jv_hdr_id'];
										$doc_date	 	 	= $r3['tally_entry_date'];
										$account_id  		= $r3['account_id'];
										//$account_name  		= $r3['account_name'];
										//$budget_head  		= $r3['budget_head'];
										$amount		   		= $r3['amount'];
										$narration		   	= $r3['narration'];
						$checkeddr ='';				
						$checkedcr ='';
						if($effect=='Dr'){
							$checkeddr = 'CHECKED';
						}
						else if($effect=='Cr'){
							$checkedcr = 'CHECKED';
						}						
//echo $account_type. ' ' . $account_id; 

//echo $sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' order by name";
						?>
					<div id="box">
					<div class="col-md-12">
                       <div class="box-body">
                        <form class="form-horizontal" action="#" method="POST">
						  <div class="box-body ">
							<fieldset>
								
								<input type="hidden" name="record_id" id="record_idPVB" value="<?= $dtlid; ?>" >
								<input type="hidden" name="provisional_jv_hdr_id" id="provisional_jv_hdr_idPVB" value="<?= $doc_no; ?>" >
								
									<?php
									//	$sql = "select * from sma_budget where 1 and project = '$company_id' or id = '$account_id' order by budget_code ";//
										$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.budget_id > 0 and b.company_id = '$company_id' order by name";
									?>	
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class=" control-label">Account Name</label>                                        
												<select class="form-control select2" id="account_idPVB" name="account_id" >
													<option value="">Select</option>
													<?php
													$res = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r4 = mysqli_fetch_array($res)){
													?>	
													<option value="<?php echo $r4['id']?>" 
													<?= ($account_id == $r4['id'])?'selected="selected"':'';?> ><?= $r4['name']. ' ' . $r4['id'];?></option>
												<?php } ?>
												</select>
											</div>
										</div>

									<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio" <?= $checkeddr;?> id="effectPVB" name="effect" value='Dr' > Debit
										<!--		<input type="radio" <?= $checkedcr;?> id="effectPVB" name="effect" value='Cr' > Credit-->
											</div>
                                        
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Amount *</label>
												<input type="text" class="form-control amountPVB"  autocomplete="off" style="text-align:right;;" name="amount" id="amountPVB"  value="<?= $amount;?>">
											</div>
                                    </div>
									
									<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationPVB"></textarea>
											</div>
									</div>
										
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<?php if (empty($tally_status)){ ?>
								<input type="submit" class="btn btn-primary" id="editItem123" name="editPVCode"  value="Save">
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
