<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditExp<?php echo $reid;?>" role="dialog" aria-labelledby="modalEditExpLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditExpLabel"  style="text-align:left;" >Edit </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="travel_expence.php" method="POST" >
						
						<fieldset>
				
							<div class="box-body">

						<?php
							
							$srno 	= $reid;
							$sql  = "SELECT * from sma_departure where id = '$srno' ";
					//echo $sql;		
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1   = mysqli_fetch_array($res);
							
							$approval_ref_no= $r1['approval_ref_no'];
							$start_date		= date('d-m-Y', strtotime($r1['start_date']));
							$start_place	= $r1['start_place'];
							$start_time		= $r1['start_time'];
							$end_date		= date('d-m-Y', strtotime($r1['end_date']));
							$end_place		= $r1['end_place'];
							$end_time		= $r1['finish_time'];
							$mode_of_travel	= $r1['mode_of_travel'];
							$spend_by		= $r1['spend_by'];
							$invoice_no		= $r1['invoice_no'];
							$fare			= $r1['fare'];
							$gst_flag		= $r1['gst_flag'];
							$vendor_id		= $r1['vendor_id'];
							
											
						?>
										<input type="hidden" name="reid" id="reid" value="<?php echo $srno; ?>" >
										<input type="hidden" id="approval_ref_no_A" name="approval_ref_no" value='<?php echo $approval_ref_no; ?>'>
										
									<div class="form-group"  style="text-align:left;" >
									
										<div class="col-md-4">
											<label for="approver" class="control-label">Start Date</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="start_date_A" name="start_date" placeholder="dd/mm/yyyy" value="<?php echo $start_date ?>">
											</div>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">Start Place</label>
											<select class="form-control" name="start_place" id="start_place_A" required >
												<option value=""> Select </option>
													<?php $sql = "select * from cities order by city_name ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['city_name'];?>" <?php echo ($start_place == $r2['city_name'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
													<?php } ?>
											</select>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">Start Time</label>
											<input type="text" class="form-control" name="start_time" id="start_time_A" value="<?php echo $start_time ?>" >
										</div>
										
									</div>

									<div class="form-group"  style="text-align:left;" >
										<div class="col-md-4">
											<label for="approver" class="control-label">End Date</label>											
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="end_date_A" name="end_date" placeholder="dd/mm/yyyy" value="<?php echo $end_date ?>">
											</div>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">End Place</label>
											<select class="form-control" name="end_place" id="end_place_A" required >
												<option value=""> Select </option>
													<?php $sql = "select * from cities order by city_name ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['city_name'];?>" <?php echo ($end_place == $r2['city_name'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
													<?php } ?>
											</select>
										</div>
										<div class="col-md-4">
											<label for="approver" class="control-label">Finish Time</label>
											<input type="text" class="form-control" name="end_time" id="end_time_A" value="<?php echo $end_time ?>" >
										</div>
									</div>
									
									<div class="form-group"  style="text-align:left;" >
									
										<div class="col-md-4">
											<label for="approver" class="ontrol-label">Mode of Travel</label>
											<select class="form-control" name="mode_of_travel" id="mode_of_travel_A" autocomplete="off" >
												<option value=""> Select </option>
												<option value="Flight" <?php echo ($mode_of_travel == 'Flight')?'selected="selected"':'';?> >Flight </option>
												<option value="Bus" <?php echo ($mode_of_travel == 'Bus')?'selected="selected"':'';?>>Bus </option>
												<option value="Train" <?php echo ($mode_of_travel == 'Train')?'selected="selected"':'';?>>Train </option>
												<option value="Car" <?php echo ($mode_of_travel == 'Car')?'selected="selected"':'';?>>Car </option>
											</select>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="ontrol-label">Spend By</label>
											<select class="form-control" name="spend_by" id="spend_by_A" autocomplete="off" >
												<option value=""> Select </option>
												<option value="C" <?php echo ($spend_by == 'C')?'selected="selected"':'';?> >Company </option>
												<option value="O" <?php echo ($spend_by == 'O')?'selected="selected"':'';?> >Own</option>
											</select>
										</div>
									
										<div class="col-md-4">
											<label for="approver" class="control-label">Vendor Name</label>
											<select class="form-control" name="vendorid" id="vendor_ID"  >
												<option value=""> Select </option>
													<?php $sql = "select * from sma_party_mst order by party_name ";
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
												<option value="<?php echo $r2['id'];?>"  <?php echo ($vendor_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
													<?php } ?>
											</select>
										</div>
										
										
									</div>
									
								<div class="form-group"  style="text-align:left;" >
										<div class="col-md-4">
											<label for="approver" class="control-label">Fare (Rs.)</label>
											<input type="text" class="form-control" name="fare" id="fare_A" style="text-align:right;" value="<?php echo $fare ?>" required >
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">GST Applicable<span style="color:red;"> * </span></label><br>
											<input type="radio" name="gst_flag" id="gst_flag_A" <?php echo ($gst_flag == 'Y')?"CHECKED":'';?> value="Y" checked> Yes &nbsp;
											<input type="radio" name="gst_flag" id="gst_flag_A" <?php echo ($gst_flag == 'N')?"CHECKED":'';?> value="N" > No
										</div>
									
										<div class="col-md-4">
											<label for="Invoice_no" class="control-label">Invoice No.<span style="color:red;"> </span></label>
											<input type="text" class="form-control" name="invoice_no" id="invoice_no" value="<?php echo $invoice_no ?>"   >
										</div>
							
											
									</div>
																
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" id="editItem123" name="editExpItem"  value="Save changes">
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
