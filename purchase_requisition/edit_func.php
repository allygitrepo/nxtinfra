<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Material </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
							$sql  = "SELECT * from sma_purchase_req_items where id = '$srno' ";
					//echo $sql;
							$res22  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1   = mysqli_fetch_array($res22);
							
							$purchase_req_id 	= $r1['purchase_req_id'];
							$product_id			= $r1['product_id'];
							$description		= $r1['description'];
							$quantity			= $r1['quantity'];
							$unit 				= $r1['unit'];
							$sql = "select * from sma_product where id = '$product_id'";
							$r2 = mysqli_query($con, $sql);
							$r1 = mysqli_fetch_array($r2);
							$product_name   = $r1['name'];
							$unit           = $r1['uom'];
							$product_group = $r1['product_group'];
							$category = $r1['category'];
							$budget_head = $r1['budget_head'];
							
							$sql = "SELECT * from sma_budget_subgroup  where 1 and id = '$budget_head' ";
                    		$q2  		 = mysqli_query($con, $sql);
                    		$r2 = mysqli_fetch_array($q2);
                    		$budget_head = $r2['budget_head'];
							
						?>
                        <form class="form-horizontal" action="pritem_update.php" method="POST">
							<input type="hidden" id="rid" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="purchaseId_e" name="purchase_req_id" value="<?php echo $_GET['id'];?>">
							<input type="hidden"  name="product_id"  id="itemName_e" value="<?php echo $product_id;?>">

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
								
								<label for="itemName" class="control-label" style="margin-left:15px;">Product Name</label>
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
									<textarea rows="3" class="form-control" id="itemDescription_e" name="description" placeholder="Item Description..."><?php echo $description;?></textarea>
                                </div>
                            </div>
							<br>
						<?php
							if($category=='M'){
								$txtv =  'Qty.';
							}
				// 			else {
				// 				$txtv =  'Value';
				// 				$unit = 'INR';
				// 			}	
						?>
							<div class="form-group col-md-12">
                                <label for="itemQuantity" class="control-label" style="margin-left:15px;" ><?= $txtv;?></label><br>
                                <div class="col-sm-4">
									<input type="text" class="form-control" id="itemQuantity_e" name="quantity"  style="text-align:right;" value="<?php echo $quantity;?>" onkeyup="calculateTotalAmounte();" size="20%">
                                </div>
								
								<!--<div class="col-sm-8" style="color:red;" > If it's a service order, please enter the total amount (including all taxes).-->
								<!--In case of a purchase order (Material), please enter the total quantity.-->
								<!--</div>-->
								
                            </div>
							<br>
						
							<div class="form-group col-md-12">
									
								<div class="col-sm-4">
								        <label for="itemUnits" class="control-label" style="margin-left:1px;" >Unit of Measurement</label><br>
								
										<input type="text" class="form-control" id="itemUnits_e" readonly name="units"  value="<?php echo $unit;?>" size="20%" >
                                	
                                </div>
                                
                          <!--      <div class="col-sm-4 col-md-4">-->
                        		<!--	<label for="itemUnits" class="control-label">Budget Head</label>-->
                        		<!--   <input type="text" class="form-control" readonly value = "<?= $budget_head;?>" >-->
                        		<!--</div>-->
                        		
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