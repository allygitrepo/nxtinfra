<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()"  ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel" style="text-align:left;" >Edit </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="travel_expence.php" method="POST">
						
						<fieldset>
				
							<div class="box-body">

						<?php
							
							$srno 	= $rid;
							$sql  = "SELECT * from sma_expenses where id = '$srno' ";
					//echo $sql;
					
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$dated			= date('d-m-Y', strtotime($r1['dated']));
							if($dated=='01-01-1970' || $dated=='31-12-1969'){
								$dated			='';
							}	
							$approval_ref_no= $r1['approval_ref_no'];
							$reference 		= $r1['reference'];
							$invoice_no 	= $r1['invoice_no'];
							$spend_by		= $r1['spend_by'];
							$amount 		= $r1['amount'];
							$note 			= $r1['note'];
							$gst_flag 		= $r1['gst_flag'];
							$budget_id		= $r1['budget_id'];
							$vendor_id		= $r1['vendor_id'];
							
							$sql  = "SELECT * from sma_travel_expenses where id = '$approval_ref_no' ";
							$res2  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($res2);
							$company_id = $r2['company_id'];
							
							$sqla = "";	
							if($status != 'Draft'){
								$sqla = " and id = '$reference' ";
							}
							
						?>
										<input type="hidden" name="rid" id="te_idE_B" value="<?php echo $srno; ?>" >
										<input type="hidden" id="modeE_B" name="mode" value='Approve'>
										<input type="hidden" id="approval_ref_no_B" name="approval_ref_no" value='<?php echo $approval_ref_no ?>'>

										<input type="hidden" id="suB" name="sub" value='sub10'>

									<div class="form-group">
										
										<div class="col-md-6" style="text-align:left;" >
											<label class="control-label" style="text-align:left;" >Expense Type </label>
										
											<select class="form-control" name="reference" id="reference_B" autocomplete="off" onchange="getcatbudgetA(this.value);" >
												<?php	if($status == 'Draft'){ ?>
												<option value=""> Select </option>
												<?php } ?>
												<?php 
												$sql = "SELECT * from sma_product where 1 and exp_flag = 'Y' $sqla order by name ";
												$q2  = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>" <?php echo ($reference == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'] ?> </option>
											<?php } ?>
											</select>
										</div>
										
										<div class="col-md-4" style="text-align:left;" >
											<label for="approver" class="control-label" style="text-align:left;" >Invoice No.</label>
											<input type="text" class="form-control" name="invoice_no" id="invoice_no_B" value="<?php echo $invoice_no ?>" required >
										</div>
										
									</div>
<?php

			$sql = " SELECT * FROM sma_budget where id = '$budget_id' ";							
//echo $sql;
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$account_year  		= $r2->account_year;
					$budget_head 		= $r2->budget_head;
					$budget_name_id 	= $r2->budget_name;
					$total_budget		= $r2->total_budget;
					$used_budget		= $r2->used_budget;
					$blocked_budget		= $r2->blocked_budget;
					$adjustment_budget 	= $r2->adjustment_budget;
			
			$balance_budget	= ( $total_budget + $adjustment_budget ) - ($used_budget + $blocked_budget);
			$budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);				
			$project = $r2['comp_name'];
			
			$sql="SELECT * FROM sma_budget_subgroup where id = '$budget_head' ";
			$res2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$cat = mysqli_fetch_array($res2);
			$budget_head = $cat['budget_head'];								
			
?>				
				<div class="form-group">
					<div class="col-sm-12">
						<span id="getcatbudgetA">
												
			<input type="hidden" name="company_id" value="<?php echo $company_id ?>" >
			<input type="hidden" id="budget_id" name="budget_id" value="<?php echo $budget_id ?>" >
			<input type="hidden" name="total_budget" value="<?php echo $total_budget ?>" >
			<input type="hidden" name="balance_budget" value="<?php echo $balance_budget ?>" >
			<input type="hidden" name="budget_name" value="<?php echo $budget_name_id ?>" >
			<input type="hidden" name="budget_head" value="<?php echo $budget_head ?>" >
				
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label " style="text-align:left;" >Account Year</label>
							<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >
						</div>
						<div class="col-sm-5" style="text-align:left;">
							<label class="control-label123" style="text-align:left;" >Cost Center Group </label>
				<?php
							$sql = " SELECT * FROM sma_budget_name where 1 and id = $budget_name_id ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_object($q2);
							$bname = $r2->name;
				?>			
							<input type="text" class="form-control" readonly value="<?= $bname; ?>" >
							
							<input type="hidden" class="form-control" readonly id="costcenter_group" name="costcenter_group"  value="<?= $budget_name_id; ?>" >
							
						</div>	
								<!--<div class="well well-sm" >-->
						<div class="col-sm-5" style="text-align:left;">
							<span class="getcostcenterD123" >
							<label class="control-label123" style="text-align:left;">Cost Center Name</label>
							<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >
							</span>
						</div>
								
					</div>
		
				<span class="getcatbudgetD">
					<div class="well well-sm" >		
						
					<div class="form-group">
						<label class="control-label col-sm-2" >Total Budget </label>
						
						<div class="col-sm-2" style="text-align:left;">
						<input type="text" class="form-control" id="total_budget_a" readonly style="text-align:right;" value="<?php echo $total_budget ?>" >
						</div>
						
						<label class="control-label col-sm-2" >Balance Budget </label>
						<div class="col-sm-2" style="text-align:left;">
						<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
						</div>
							
						</div>
					</div>	
				</span>
				
											</span>
										</div>
									</div>	


									<div class="form-group">
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" style="text-align:left;" >Date</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated_B" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value="<?php echo $dated ?>">
											</div>
										</div>
										
										<input type="hidden"  name="amount_prev"  value="<?php echo $amount ?>" >
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label"  >Amount</label>
											<input type="text" class="form-control" name="amount" id="Amount_B" style="text-align:right;"  value="<?php echo $amount ?>" required >
										</div>
									
										<div class="col-md-4" style="text-align:left;">
											<label for="approver" class="ontrol-label">Spend By</label>
											<select class="form-control" name="spend_by" id="spend_by_B" autocomplete="off" >
											<?php	if($status == 'Draft'){ ?>
												<option value=""> Select </option>
											<?php } ?>
												<option value="C" <?php echo ($spend_by == 'C')?'selected="selected"':'';?> >Paid by
												company </option>
									<?php	if($status != 'Completed'){ ?>			
												<option value="D" <?php echo ($spend_by == 'D')?'selected="selected"':'';?> >Paid thro Corp Card </option>
												<option value="P" <?php echo ($spend_by == 'P')?'selected="selected"':'';?> >Paid against petty cash advance </option>
												
												<option value="O" <?php echo ($spend_by == 'O')?'selected="selected"':'';?> >Paid thro. Cash, personal credit card, etc. </option>
									<?php } ?>		
											</select>
										</div>
										
									</div>
									
							<?php if($spend_by == 'C' && $status == 'Completed' ){ ?>		
									<div class="form-group">
										<label for="approver" class="col-md-1 control-label">Vendor</label>		
										<div class="col-md-8">
										<select class="form-control" name="vendor_id" id="vendor_id" required >
											<option value=""> Select </option>
										<?php 
											$sql = "SELECT * from sma_party_mst where 1 order by trim(party_name) ";
											$q2  = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
										?>
											<option value="<?php echo $r2['id'] ?>" <?= ($vendor_id == $r2['id'])?'selected="selected"':'';?> ><?= $r2['party_name'] ?></option>	
											<?php } ?>
										</select>
										</div>
									</div>
							<?php } ?>
							
									<div class="form-group">
																			
										<div class="col-md-10" style="text-align:left;" >
											<label for="approver" class="control-label">Remarks</label>
											<textarea rows="2" class="form-control" name="remarks" id="Remarks_B"  ><?php echo $note ?></textarea>
										</div>
										
									</div>
																
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()"  >Close</button>
                <input type="submit" class="btn btn-primary" id="editItem123" name="editItem"  value="Save changes">
            </div>
				<?php //exit();?>
				</fieldset>
				
					</div>
                        </form>
                    </div>
				  </div>
                </section>
				
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Item-->

