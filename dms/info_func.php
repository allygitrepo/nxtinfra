<!--INFO Popup-->
<?php
	include "../dbcon.php";
	$rid=$_POST['rid'];
?>


<section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                
									<?php   
										$rid=$_POST['rid'];
										
										$sql 	= "select * from dms_inward where inward_no in (select reference_id  from my_documents where id = '$rid')";
										$query 	= mysqli_query($con, $sql);
										$row 	= mysqli_fetch_array($query);	
										$readonly 	= "readonly";
										$inward_no 	= $row['inward_no'];
										$comid		= $row['company_for'];
									?>
										
								<div class="form-group">
										<input type="hidden" name="inward_no" value="<?php echo $inward_no;?>" >
										
										<div class="col-xs-4">
											<label for="prDate" class="control-label">Inward Number</label>
											<input type="text" class="form-control"  name="inward_no" readonly style="text-align:right;font-size:24px; font-family: Arial, Helvetica, sans-serif;" value="<?php echo $inward_no;?>">
										
										</div>
										<?php
											$date_of_received = date('d-m-Y', strtotime($row['date_of_received']));
											if($date_of_received=='01-01-1970'){
												$date_of_received='';
											}
										?>
										<div class="col-xs-4">
											<label for="prDate" class="control-label">Date</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<input type="text" class="form-control" id="prDate" name="date_of_received" placeholder="dd/mm/yyyy"
													   value="<?php echo $date_of_received;?>" readonly <?php echo $readonly; ?> >
											</div>
										</div>	
										
										
										<div class="col-sm-4">
											<label for="department_for" class="control-label">Mode or Receipt</label>
											<select class="form-control" name="mode_of_receipt"   required <?php echo $readonly; ?> >
											<option value=""> Select </option>
											<option value="C" <?php echo ($row['mode_of_receipt'] == 'C')?'selected="selected"':'';?> > Courier </option>
											<option value="H" <?php echo ($row['mode_of_receipt'] == 'H')?'selected="selected"':'';?> > Hand Delivery </option>
											<option value="E" <?php echo ($row['mode_of_receipt'] == 'E')?'selected="selected"':'';?> > Email </option>
											</select>	
										</div>
								</div>
								
								<div class="form-group">		
									   <div class="col-sm-7">
											<label for="company_for" class="control-label">Company</label>
											<select class="form-control" name="company_for" required <?php echo $readonly; ?> >
											<option value=""> Select </option>
												<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
												$q2 	= mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_for'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
												<?php } ?>
											</select>		
										</div>
										
										<div class="col-sm-5">
											<label for="department_for" class="control-label">Department</label>
											<select class="form-control" name="department_for"  required <?php echo $readonly; ?> >
											<option value=""> Select </option>
												<?php $sql = "select * from sma_department order by name ";
												$q2 	= mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>"  <?php echo ($row['department_for'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
												<?php } ?>
											</select>	
										</div>
										
								</div>
									
									<div class="form-group">
										<div class="col-md-5">	
											<label class=" control-label">Document Type</label>
											<select class="form-control col-sm-2 doc_type" name="doc_type"   <?php echo $readonly; ?> >
												<option value="0">Select</option>
															<?php
															$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
															$rs = mysqli_query($con, $sql);
															echo mysqli_error($con);
															while($rw = mysqli_fetch_array($rs)){
															?>
																<option value="<?php echo $rw['id']?>" <?php echo ($row['doc_type'] == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
															<?php } ?>	
															
											</select>
										</div>	
									
										<div class="col-md-7">		
											<label class="control-label">Sent By</label>
											<input type="text" class="form-control"  name="sent_by"   <?php echo $readonly; ?> value="<?php echo $row['sent_by'];?>" >
										</div>
									
									
										
									</div>
									
									<div class="form-group">
										<div class="col-md-8">
										
											<label class="control-label">Document description</label>
											<input type="text" class="form-control"  name="remarks" <?php echo $readonly; ?> value="<?php echo $row['subject'];?>" >
										
										</div>
									
										<div class="col-md-4">
											<label class="control-label">Storage Type</label>
											<select class="form-control col-sm-2 " name="storage_type" <?php echo $readonly; ?> >
												<option value=""  >Select</option>
												<option value="D" <?php echo ($row['storage_type'] == 'D' )?'selected="selected"':'';?> >Digital</option>
												<option value="P" <?php echo ($row['storage_type'] == 'P' )?'selected="selected"':'';?> >Physical</option>
												<option value="B" <?php echo ($row['storage_type'] == 'B' )?'selected="selected"':'';?> >Both</option>
											</select>	
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-4">
											<label class="control-label">Storage Rack</label>
											<input type="text" class="form-control"  name="storage_rack" <?php echo $readonly; ?> value="<?php echo $row['storage_rack'];?>" >
										</div>
										
										<div class="col-md-4">
											<label class="control-label">Shelf No.</label>
											<input type="text" class="form-control"  name="shelf_no" <?php echo $readonly; ?> value="<?php echo $row['shelf_no'];?>" >
										</div>
										
										<div class="col-md-4">
											<label class="control-label">File No.</label>
											<input type="text" class="form-control" name="file_no" <?php echo $readonly; ?> value="<?php echo $row['file_no'];?>" >
										</div>
										
									</div>
								
                                </div>
	
							
                            </div>
			
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								
								</div>
			
			
                            </div>			
			</section>
			
<!--INFO Popup End -->	