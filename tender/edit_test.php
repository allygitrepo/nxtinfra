<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItemT" role="dialog" aria-labelledby="modalAddItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="clearfld()" >&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Product to Tender </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="tenderId" value="<?php echo $_GET['id'];?>">
						
							
							<div class="form-group">
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control select2123" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
                                <div class="col-sm-8">
									<label for="itemName" class="control-label">Product Name</label>
									<span id="getmaterial1" ><span id="getgrnitem" >
										<select class="form-control" id="itemName" required >
											<option value="">Select</option>	
										
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
							    		
								<div class="col-sm-6">
									<label for="itemDescription" class="control-label col-sm-2">Description</label>
									<span id = "getdesc">	
										<textarea rows='01' class="form-control" id="itemDescription" placeholder="Item Description..."></textarea>
									</span>
								</div>
								
                            </div>
                            
						<span id="getcatbudget" >
							<div class="form-group">
                               <div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group</label>
								</div>
								
								<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Name</label>
								</div>								
                            </div>
							
							<div class="form-group">	
								<div class="col-sm-12">
									
								</div>
							</div>
						</span>		
							
						<!--<div class="well well-sm" > -->
							
							<?php
							
								$b_readonly = '';
						
							?>
							
							 <div class="form-group">
								
								<div class="col-sm-2">
									<label for="itemQuantity" class="control-label col-sm-1">Qty.</label>
                                	<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" >
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label col-sm-1">Units</label>
									<span id="getunit2">
										<input type="text" class="form-control" id="itemUnits" name='itemunits' readonly >
									</span>

                                </div>
								
							<!--	<div class="col-sm-3">
									<label class="control-label ">Delivery Date </label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="deliveryDATE" >
									</div>
								</div>-->
								
								<div class="col-sm-6">
									<span id="errormsg" style="color:red;" ></span>
								</div>
								
							</div>
							
                        </form>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
                <button type="button" class="btn btn-primary" id="addItem">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Item-->

