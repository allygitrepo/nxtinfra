<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static">
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
                        <form class="form-horizontal" action="company_expense.php" method="POST">
						
						<fieldset>
				
							<div class="box-body">

						<?php
						
						//echo $company_id. " <<<>>>";
							
							$srno 	= $rid;
							$sql  = "SELECT * from sma_expenses where id = '$srno' ";
					//echo $sql;
					
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$dated			= $r1['dated'];
							$approval_ref_no= $r1['approval_ref_no'];
							$reference 		= $r1['reference'];
							$invoice_no 	= $r1['invoice_no'];
							$amount 		= $r1['amount'];
							$note 			= $r1['note'];
							$budget_id		= $r1['budget_id'];
							$gst_amount		= $r1['gst_amount'];
							$gst_perc		= $r1['gst'];
							
							$sql  = "SELECT * from sma_travel_expenses where id = '$approval_ref_no' ";
							$res2  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($res2);
							$company_id = $r2['company_id'];
							$approval_number - $r2['approval_number'];
							
						?>
							<input type="hidden" name="approval_number" id="approval_number" value="<?php echo $approval_number; ?>" >
										<input type="hidden" name="rid" id="te_idE_A" value="<?php echo $srno; ?>" >
										<input type="hidden" id="modeE_A" name="mode" value='Approve'>
										<input type="hidden" id="approval_ref_no_A" name="approval_ref_no" value='<?php echo $approval_ref_no ?>'>
										
										<input type="hidden" id="suB" name="sub" value='sub10'>

								<div class="form-group">
									
										<div class="col-md-6" style="text-align:left;" >
											<label class="control-label" style="text-align:left;" >Product </label>
										<span id="getproduct_A">
											<select class="form-control" name="reference" id="reference_A" autocomplete="off" onchange="getcatbudgetA(this.value)" >
												<option value=""> Select </option>
												<?php 
													//$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' order by name";
													$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' and a.id in ($reference ) order by name";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>" <?php echo ($reference == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'] ?> </option>
												<?php } ?>
											</select>
										</span>	
										</div>
									</div>	
										
								<div class="form-group">		
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" style="text-align:left;" >Invoice No.*</label>
											<input type="text" class="form-control" name="invoice_no" id="invoice_no_A" value="<?php echo $invoice_no ?>" required >
										</div>
										
										<?php
											$dated = date('d-m-Y', strtotime($dated));
											if($dated=='30-11--0001' || $dated=='01-01-1970'){
												$dated='';
											}	
										?>			
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" style="text-align:left;" >Invoice Date*</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated_B" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value="<?php echo $dated ?>">
											</div>
										</div>
										
									</div>
									<?php	
											$sql   = "SELECT * FROM `account_mst` where id = '$reference' ";
											$q2  = mysqli_query($con, $sql);
											$r2 = mysqli_fetch_object($q2);
											$description 	= $r2->description;
										?>
										
										<div class="form-group">
											<div class="col-sm-12" style="text-align:left;">
												<label class="control-label">Description</label>
												<input type="text" class="form-control" value="<?php echo $description ?>" >
											</div>
										</div>
										
										
<?php
$sql   = "SELECT * 	FROM  sma_budget where id = '$budget_id'  ";
//echo $sql;

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$account_year  		= $r2->account_year;
			//$budget_head 		= $r2->budget_head;
			$budget_name 		= $r2->budget_name;
			
			$total_budget 		= $r2->total_budget;
			$blocked_budget 	= $r2->blocked_budget;
			$used_budget 		= $r2->used_budget;
			$adjustment_budget 	= $r2->adjustment_budget;
			
			$balance_budget	= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget);			
			
            $budget_id 		 	= $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
			$project = $r2['comp_name'];
			
			$sql ="SELECT * FROM `sma_budget_subgroup` where id = '$budget_head' ";
			$q2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2 = mysqli_fetch_array($q2);
			$budget_head = $r2['budget_head'];								
			
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
				<div class="col-sm-2">
					<label class="control-label " style="text-align:left;" >Account Year</label>
					<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >
				</div>
				<div class="col-sm-4" style="text-align:left;">
					<label class="control-label" >Cost Center Group</label>
					<input type="text" class="form-control" id="budget_name_a" readonly style="text-align:left;" value="<?php echo $budget_name ?>" >
				</div>
									
				<!-- <div class="col-sm-6" style="text-align:left;">
				<label class="control-label" >Cost Center Sub Group</label>
				<input type="text" class="form-control" id="budget_head_a" readonly style="text-align:left;" value="<?php echo $budget_head ?>" >
				</div> -->
			</div>
			
			<div class="form-group" >
				<div class="col-sm-6" style="text-align:left;">
				<label class="control-label" >Blocked Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly style="text-align:right;" value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6" style="text-align:left;">
				<label class="control-label" >Used Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly style="text-align:right;" value="<?php echo $used_budget ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6" style="text-align:left;">
				<label class="control-label" >Total Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly style="text-align:right;" value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6" style="text-align:left;">
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
											<label for="approver" class="control-label"  >Gross Amount</label>
											<input type="text" class="form-control" name="amount" id="Amount_B" style="text-align:right;"  value="<?php echo $amount ?>" required >
										</div>
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >GST%</label><br>
											<input type="text" class="form-control" name="gst_perc" id="getgstA" style="text-align:right;" value="<?php echo $gst_perc ?>" > 
										</div>
										
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >GST Amount</label><br>
											<input type="text" class="form-control" name="gst_amount" id="Gst_amount_B" style="text-align:right;" value="<?php echo $gst_amount ?>" > 
										</div>
									
									<?php $total_amount = $amount + $gst_amount; ?>
									
										<div class="col-md-3" style="text-align:left;" >
											<label for="approver" class="control-label" >Total Amount</label><br>
											<input type="text" readonly class="form-control"  style="text-align:right;" value="<?php echo $total_amount ?>" > 
										</div>
									</div>
									
									<div class="form-group">
																			
										<div class="col-md-10" style="text-align:left;" >
											<label for="approver" class="control-label">Narrations</label>
											<textarea rows="2" class="form-control" name="remarks" id="Remarks_A"  ><?php echo $note ?></textarea>
										</div>
										
									</div>
																
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
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

