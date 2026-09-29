<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Account Details </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
							$sql  = "SELECT * from payment_details where id = '$srno' ";
					
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
								$payment_hdr_id 	= $r1['payment_hdr_id'];
								$account_type		= $r1['account_type'];
								$account_name 		= $r1['account_name'];
								$against_invoice    = $r1['against_invoice'];
								$invoice_number     = $r1['invoice_number'];
								$debit_credit    	= $r1['debit_credit'];
								$amount    			= $r1['amount'];
								$remarks 			= $r1['remarks'];

						?>
					<div id="box">
                        <!--<form class="form-horizontal" action="pyacct_update.php" method="POST">-->
						<form class="form-horizontal" action="#" method="POST">
						  <div class="box-body">
							
							<input type="hidden" id="rid_e" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="payment_hdr_id1" name="payment_hdr_id" value="<?php echo $_GET['id'];?>">

							<fieldset>
								
							<div class="form-group col-md-12">
							
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> To Supplier / Account</label><br>
									<select class="form-control"  id="accountType1"  name="account_type" onchange="getaccount1(this.value)"  >
										<option value="">Select</option>
										<option value="S" <?php echo ($account_type == 'S')?"SELECTED":'';?> >Supplier</option>
										<option value="A" <?php echo ($account_type == 'A')?"SELECTED":'';?> >Account</option>
									</select>
								</div>
								
                                <div class="col-sm-8">
									<label for="accountName" class="control-label">Account Name</label>
									<span id ="getaccount1" >
										<select class="form-control" id="account_Name1" name="account_name" >
											<option value="">Select <?php echo $account_name;?></option>	
										<?php
										if ($account_type == 'S'){
											$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
											$r3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r4 = mysqli_fetch_array($r3)){
											$accountname = $r4['party_name'];
										?>
											<option value="<?php echo $r4['id']?>" <?php echo ($account_name == $r4['id'])?"SELECTED":'';?> > <?php echo $accountname ?></option>
										
										<?php }
										}
										else if ($account_type == 'A'){
											$sql = "SELECT * FROM account_mst ORDER BY account_name ASC ";
											$r3 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r4 = mysqli_fetch_array($r3)){
											$accountname = $r4['account_name'];
										?>
											<option value="<?php echo $r4['id']?>" <?php echo ($account_name == $r4['id'])?"SELECTED":'';?> > <?php echo $accountname ?></option>
										<?php } 	
											
										 } ?>	
										
										</select>
									</span>
								</div>
                                
                            </div>
                        	
							<div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="againstInvoice" class="control-label">Against Invoice</label><br>
                                    <input type="radio"  id="againstInvoice1" name="against_invoice" <?php if ($against_invoice=="Y") echo "checked";?> value = "Y" > Yes 
									<input type="radio"  id="againstInvoice1" name="against_invoice" <?php if ($against_invoice=="N") echo "checked";?>  value = "N" > No
                                </div>
								
                                <div class="col-sm-8">
									<label for="againstInvoice" class="control-label">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoiceNumber1" name="invoice_number" value="<?php echo $invoice_number;?>" >
                                </div>
								
                            </div>
						
                        <div class="form-group col-md-12">
                                
								<div class="col-sm-4">
									<label for="debitCredit" class="control-label">Debit / Credit</label> <br>
										<input type="radio" id="debitCredit1" name="debit_credit" <?php if ($debit_credit=="D") echo "checked";?> value="D" > Debit
										<input type="radio" id="debitCredit1" name="debit_credit" <?php if ($debit_credit=="C") echo "checked";?> value="C" > Credit
								</div>
                                
								<div class="col-sm-4">
									<label for="amount" class="control-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="amount1"  name="amount"  style="text-align:right;"  value="<?php echo $amount;?>">
                                    </div>
                                </div>
								 
                        </div>
						
						<div class="form-group col-md-12">
							<div class="col-sm-12">
								<label for="amount" class="control-label">Remarks</label>
                            	<input type="text" class="form-control" id="remarks1" name="remarks" value="<?php echo $remarks;?>">
							  
							</div>  
						</div>	
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" id="editItem123" name="editItem"  value="Save changes">
            </div>
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
