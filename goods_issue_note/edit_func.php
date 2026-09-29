<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Product </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
							$sql  = "SELECT * from sma_goods_issue_note_items where id = '$srno' ";
					//echo $sql;
							$res22  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1   = mysqli_fetch_array($res22);
							
							$gin_hdr_id 		= $r1['gin_hdr_id'];
							$mrn_item_id		= $r1['mrn_item_id'];
							$product_id			= $r1['product_id'];
							$description		= $r1['specification'];
							$issue_qty			= $r1['issue_qty'];
							$unit 				= $r1['units'];
							$sql = "select * from sma_product where id = '$product_id'";
							$r2 = mysqli_query($con, $sql);
							$r1 = mysqli_fetch_array($r2);
							$product_name = $r1['name'];
							$product_group = $r1['product_group'];
							
						?>
                        <form class="form-horizontal" action="goods_issue_note.php" method="POST">
							<input type="hidden" id="rid" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="gin_hdr_id" name="gin_hdr_id" value="<?php echo $gin_hdr_id;?>">
							<input type="hidden"  name="mrn_item_id"  id="mrn_item_id_e" value="<?php echo $mrn_item_id;?>">
							
							<input type="hidden"  name="product_id"  id="itemName_e" value="<?php echo $product_id;?>">
							<input type="hidden"  name="companyid"  id="company_id_e" value="<?php echo $company_id;?>">

							<div class="form-group col-md-12">
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" disabled id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($product_group == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								<label for="itemName" class="control-label" style="margin-left:15px;">Material Name</label>
								<div class="col-md-8">
									<select class="form-control" disabled >
									<option value="">Select</option>
									<?php
                                    	$sql="SELECT * FROM sma_product where 1 and product_group = '$product_group' ";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                    <option value="<?php echo $rw['id']?>" <?php echo ($product_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'] ?></option>
                                        <?php } ?>
									</select>
								</div>
                            </div>
							<br>
							<div class="form-group col-md-12">
                                <label for="itemDescription" class="control-label" style="margin-left:15px;">Description</label><br>
                                <div class="col-sm-12">
									<input type="text" class="form-control" id="itemDescription_e" name="description" <?= $readonly; ?> placeholder="Item Description..." value="<?php echo $description;?>" size="120%">
                                </div>
                            </div>
							<br>
							
							<div class="form-group col-md-12">
                                <label for="itemQuantity" class="control-label" style="margin-left:15px;" >Qty.</label><br>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="issue_qty" name="issue_qty" <?= $readonly; ?> style="text-align:right;" value="<?php echo $issue_qty;?>" size="20%">
									
									
									<input type="hidden" id="issue_qty_prev" name="issue_qty_prev" value="<?php echo $issue_qty;?>" size="20%">
                                </div>
                            </div>
							<br>
							
							<div class="form-group col-md-12">
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label" style="margin-left:0px;" >Unit of Measurement</label>
									<input type="text" class="form-control" id="itemUnits_e" readonly name="units"  value="<?php echo $unit;?>" >
                                </div>
								
					<?php
								$sql = " SELECT * from sma_product_open_stock where product_name = '$product_id' and project = '$company_id'";
								$q2  = mysqli_query($con, $sql);

								$r2 = mysqli_fetch_object($q2);
								$close_stock 	= ($r2->opening_stock + $r2->receipts) - $r2->issue;
					?>
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label" style="margin-left:0px;" >Closing Stock</label>
									<input type="text" class="form-control" id="closeStock" readonly style="text-align:right;"  value = "<?= $close_stock; ?>" >
								</div>
								
	                        </div>
											
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<input type="submit" class="btn btn-primary" id="editItem12345" name="editSave"  value="Save changes" >
							</div>
							
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>

	function getmaterial(id){
		var sub    = 'sub33';
		var strURL = "app_func.php";

		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('#getmaterial').html(result);
		});

	}
	
	function getbudgetabc(id){
		
        var sub    = 'sub11';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getbudgetabc').html(result);
		});
	}

	function getbudgetheada(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudgetheada').html(result);
		});

	}

</script>