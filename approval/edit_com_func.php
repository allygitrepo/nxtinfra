<div class="modal fade" id="modalEditItemexp<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel" style="text-align:left;" >Edit </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="edit.php" method="POST">
						
						<fieldset>
				
							<div class="box-body">

						<?php
						
							$srno 	= $rid;
							$sql  = "SELECT * from sma_approval_expenses where id = '$srno' ";
					//echo $sql;
					
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$dated			= date('d-m-Y', strtotime($r1['dated']));
							$approval_hdr_id= $r1['approval_hdr_id'];
							$reference 		= $r1['reference'];
							$invoice_no 	= $r1['invoice_no'];
							$amount 		= $r1['amount'];
							$budget_id 		= $r1['budget_id'];
							
							$note 			= $r1['note'];
							$gst_amount 	= $r1['gst_amount'];
							$total_amount = $amount + $gst_amount;
							
							$sql  = "SELECT * from sma_approval_memo where id = '$approval_hdr_id' ";
							$res2  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($res2);
							$company_id = $r2['company'];
							
						?>
										<input type="hidden" name="approval_hdr_id" id="approval_hdr_id" value="<?php echo $approval_hdr_id; ?>" >
										
										<input type="hidden" name="rid" id="te_idE_A" value="<?php echo $srno; ?>" >
										<input type="hidden" id="modeE_A" name="mode" value='Approve'>
										<input type="hidden" id="approval_ref_no_A" name="approval_ref_no" value='<?php echo $approval_ref_no ?>'>
										
										<input type="hidden" id="suB" name="sub" value='sub10'>

										<input type="hidden" name="reference_p" value='<?= $reference ?>'>
										<input type="hidden" name="budget_id_p" value='<?= $budget_id ?>'>
										
									<div class="form-group">
							<?php
								$sqla = "";	
								if($status != 'Draft'){
									$sqla = " and id = '$reference' ";
								}
							?>	
										<div class="col-md-6" style="text-align:left;" >
											<label class="control-label" style="text-align:left;" >Expense Type </label>
										
											<select class="form-control" name="reference" id="reference_A" autocomplete="off" onchange="getcatbudgetA(this.value)" >
											<?php	if($status == 'Draft'){ ?>
												<option value=""> Select </option>
										<?php } ?>
												<?php 
													$sql = "SELECT * from account_mst where account_type = 'E' $sqla order by account_name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>" <?php echo ($reference == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['account_name'] ?> </option>
												<?php } ?>
											</select>
										</div>
										
									</div>
										
<?php

			$sql   = "SELECT b.*, c.id as budget_name_id, c.name as budget_name
				FROM sma_budget b, sma_budget_name c
				where b.id = '$budget_id' and c.id = b.budget_name ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			//$budget_head_id 	= $r2->budget_head_id;
			$budget_name_id 	= $r2->budget_name_id;
			
			$budget_code 	= $r2->budget_code;
			$budget_head 	= $r2->budget_head;
			$budget_name 	= $r2->budget_name;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			$adjustment_budget 	= $r2->adjustment_budget;
			//$balance_budget = $r2->balance_budget;
			
			$balance_budget	= ($total_budget + $adjustment_budget)- ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
?>			
									<div class="form-group">
										<div class="col-sm-12">
											<span id="getcatbudgetA">
						
		<div class="well well-sm" >		
			
							
			<input type="hidden" class="form-control" name="company_id" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" name="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" name="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" name="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" name="budget_name" readonly value="<?php echo $budget_name_id ?>" >
			<input type="hidden" class="form-control" name="budget_head" readonly value="<?php echo $budget_head_id ?>" >
								
			<div class="form-group">
				<div class="col-sm-2" style="text-align:left;">
				<label class="control-label" >Cost Center Name</label>
				<input type="text" class="form-control" id="budget_name_a" readonly style="text-align:left;" value="<?php echo $budget_name ?>" >
				</div>
				
				<div class="col-sm-2" style="text-align:left;">
				<label class="control-label" >Cost Center Code</label>
				<input type="text" class="form-control" id="budget_name_a" readonly style="text-align:left;" value="<?php echo $budget_code ?>" >
				</div>
									
				<div class="col-sm-4" style="text-align:left;">
				<label class="control-label" >Cost Center Head</label>
				<input type="text" class="form-control" id="budget_head_a" readonly style="text-align:left;" value="<?php echo $budget_head ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-2" style="text-align:left;">
				<label class="control-label" >Total Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly style="text-align:right;" value="<?php echo $total_budget ?>" >
				</div>
				
				<div class="col-sm-2" style="text-align:left;">
				<label class="control-label" >Used Budget </label>
				<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo $used_budget ?>" >
				</div>
				
				<div class="col-sm-2" style="text-align:left;">
				<label class="control-label" >Balance Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
											</span>
										</div>
									</div>	
		

									<div class="form-group">
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label"  > Amount</label>
											<input type="text" class="form-control" name="amount" id="Amount_A" style="text-align:right;"  value="<?php echo $amount ?>" required >
											
											<input type="hidden" class="form-control" name="amount_p" id="Amount_AP" style="text-align:right;"  value="<?php echo $amount ?>" required >
											
										</div>
													
										<div class="col-md-9" style="text-align:left;" >
											<label for="approver" class="control-label">Narrations</label>
											<textarea rows="1" class="form-control" name="remarks" id="Remarks_A"  ><?php echo $note ?></textarea>
										</div>
										
									</div>
																
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()"  >Close</button>
                <input type="submit" class="btn btn-primary" id="editItem123" name="editExp"  value="Save changes">
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
