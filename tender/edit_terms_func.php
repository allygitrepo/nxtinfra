<div class="modal fade" id="modalEditTerms<?php echo $terms_srno;?>" role="dialog" aria-labelledby="modalEditTermsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				
				<h4 class="modal-title" id="modalEditTermsLabel" style="text-align:left;">Edit Terms </h4>
                
	        </div>
            <div class="modal-body">	
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $terms_srno;
							
							$sql  = "SELECT * from sma_tender_terms where id = '$srno' ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$tender_hdr_id 			= $r1['tender_hdr_id'];
							$terms_conditions		= $r1['terms_conditions'];
							
						?>

                        <form class="form-horizontal" id="editFormTerms" action="#" method="POST">
							
							<input type="hidden" id="tender_hdr_id" name="tender_hdr_id" value="<?php echo $tender_hdr_id;?>" >
							<input type="hidden" id="terms_srno" name="terms_srno" value="<?php echo $terms_srno;?>" >
                            
							<h4 class="modal-title" id="modalEditTermsLabel" style="text-align:left;">
									Terms & Conditions
							</h4>		
                            <div class="form-group ">
								<div class="col-sm-12">
									<input type="text" class="form-control" <?= $readonlya;?> id="terms_conditions" name="terms_cond" style="width: 100%;text-align:left;" size="150" value="<?php echo $terms_conditions;?>" >
                                </div>
                            </div>
							
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<?php if (empty($readonlya)){ ?>		
									<input type="submit" class="btn btn-primary" id='editSave123' name='editterms' value="Save" >
						<?php } ?>			
								</div>
						
                        </form>
                    </div>
                </section>
            </div>
          
        </div>
    </div>
</div>
