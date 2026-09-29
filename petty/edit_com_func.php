<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel" style="text-align:left;" >Edit </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="pettycash_expense.php" method="POST">
						
						<fieldset>
				
							<div class="box-body">

						<?php
							
							$srno 	= $rid;
							$sql  = "SELECT * from sma_pettycash_exp where id = '$srno' ";
					//echo $sql;
					
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$dated			= date('d-m-Y', strtotime($r1['dated']));
							$approval_ref_no= $r1['approval_ref_no'];
							$expense_id 	= $r1['expense_id'];
							$invoice_no 	= $r1['invoice_no'];
							$amount 		= $r1['amount'];
							$note 			= $r1['note'];
							$paid_to  		= $r1['paid_to'];
							$spend_by 		= $r1['spend_by'];
							
							$sql  = "SELECT * from sma_pettycash where id = '$approval_ref_no' ";
							$res1  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($res1);
							$trans_type 	= $r2['trans_type'];
							
						?>
										<input type="hidden" name="rid" id="te_idE_A" value="<?php echo $srno; ?>" >
										<input type="hidden" id="modeE_A" name="mode" value='Approve'>
										<input type="hidden" id="approval_ref_no_A" name="approval_ref_no" value='<?php echo $approval_ref_no ?>'>
										
										<input type="hidden" id="suB" name="sub" value='sub10'>

								<input type="hidden" name="balance_cash" value="<?= $balance_cash;?>">
								
									<div class="form-group">
										<div class="col-md-6" style="text-align:left;" >
										<?php if ($trans_type == 'R'){ ?>
											<label for="approver" class="control-label" style="text-align:left;" >Date *</label>
										<?php } ?>
										<?php if ($trans_type == 'P' ){ ?>
											<label for="approver" class="control-label" style="text-align:left;" >Spend/Invoice Date *</label>
										<?php } ?>	
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated_A" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value="<?php echo $dated ?>">
											</div>
										</div>
										
										<div class="col-md-6" style="text-align:left;" >
											<label for="approver" class="control-label" >Transaction Type</label><br>
											<?php if ($trans_type == 'R'){
												$ttype ='Receipt';
											}	
											else if ($trans_type == 'P'){
												$ttype ='Payment';
											}
											?>
											
											<input type="hidden" name="trans_type" id="trans_type_A"  value="<?php echo $trans_type ?>" >
											
											<input type="text" class="form-control" readonly value="<?php echo $ttype ?>" >
											
										</div>
									</div>
								
								<?php if ($trans_type == 'R'){ ?>
									<div class="form-group">
										<div class="col-md-12" style="text-align:left;" >
										<label class=" control-label">Received From</label>
										<span id="getvendor">
										<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_iD" autocomplete="off" required >
											<option value=""> Select </option>
											<?php 
											$sql = "SELECT * from account_mst where 1 and account_type = 'B' order by account_name";
											$q2  = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
											?>
											<option value="<?php echo $r2['id'] ?>" <?php echo ($spend_by == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['account_name'] ?> </option>	
											<?php } ?>
										</select>
										</span>
										</div>
									</div>
								<?php } ?> 
								
								<?php if ($trans_type == 'P'){ ?>
									<div class="form-group">		
										<div class="col-md-12" style="text-align:left;" >
											<label class="control-label" style="text-align:left;" >Account Type </label>
										
											<select class="form-control" name="expense_id" id="reference_A" autocomplete="off" >
												<option value=""> Select </option>
												<?php 
													$sql = "SELECT * from sma_product where 1 order by name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>" <?php echo ($expense_id == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'] ?> </option>
												<?php } ?>
											</select>
										</div>
										
										
										
									</div>
								
									<div class="form-group">
										<div class="col-md-6" style="text-align:left;" >
											<label for="approver" class="control-label" >Paid To  </label><br>
											<input type="radio" name="paid_to" id="paidto_B" value="V" <?php echo ($paid_to == 'V')?"CHECKED":'';?> 
											onchange="getvendora_A(this.value)" > Vendor &nbsp;
											<input type="radio" name="paid_to" id="paidto_B" value="U" <?php echo ($paid_to == 'U')?"CHECKED":'';?> 
											onchange="getvendora_A(this.value)" > User &nbsp;
											<input type="radio" name="paid_to" id="paidto_B" value="O" <?php echo ($paid_to == 'O')?"CHECKED":'';?> 
											onchange="getvendora_A(this.value)" > Others
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-10"  style="text-align:left;">
										<span class="getvendora_A">
										<?php if($paid_to =='V'){?>
											<label class=" control-label">Party Name</label>
											<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required >
												<option value=""> Select </option>
													<?php $sql = "select * from sma_party_mst where party_kyc = 'Y' order by party_name ";
													$q2 	  = mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" <?php echo ($spend_by == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
													<?php } ?>
											</select>
										<?php } 
										  else if($paid_to =='U'){?>
											<label class=" control-label">User Name</label>
											<select class="form-control select2" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required >
												<option value=""> Select </option>
													<?php $sql = "select * from sma_user where active = '1' order by username ";
													$q2 	  = mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>" <?php echo ($spend_by == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['username'];?></option>
													<?php } ?>
											</select>
									<?php } 
										  else if($paid_to =='O' ){
									?>			
												<label class=" control-label">Other Name </label>
												<input type="text" class="form-control" id="sma_vendor_id"  name="sma_vendor_id" autocomplete="off" 
												value="<?php echo $spend_by ; ?>" >
									<?php
											}
									?>	
										</span>
										</div>
									</div>
							<?php } ?>
								
									<div class="form-group">
							<?php if ($trans_type == 'P'){ ?>
										<div class="col-md-4" style="text-align:left;" >
											<label for="approver" class="control-label" style="text-align:left;" >Invoice No.</label>
											<input type="text" class="form-control" name="invoice_no" autocomplete="off" id="invoice_no_A" value="<?php echo $invoice_no ?>" required >
										</div>
							<?php } ?>			
										<div class="col-md-4" style="text-align:left;" >
											<label for="approver" class="control-label"  >Amount</label>
											<input type="text" class="form-control" name="amount" autocomplete="off" id="Amount_A" style="text-align:right;"  value="<?php echo $amount ?>" required >
										</div>
										
									</div>
									
									<div class="form-group">
										<div class="col-md-12" style="text-align:left;" >
											<label for="approver" class="control-label">Narrations</label>
											<textarea rows="2" class="form-control" name="remarks" autocomplete="off" id="Remarks_A"  ><?php echo $note ?></textarea>
										</div>
									</div>
																
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<script>

</script>