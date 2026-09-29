<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document" >
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Product </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							$sql  = "SELECT * from sma_tender_items where id = '$srno' ";
		
							$readonly1 = $readonly;
							if($po_type=='A' && $status!='Completed'){
								$readonly1='';
							}
						
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$tender_hdr_id 	= $r1['tender_hdr_id'];
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$cost_center_group 		= $r1['cost_center_group'];
							$cost_center_subgroup 	= $r1['cost_center_subgroup'];
							
							
							$unit	= $r1['uom'];
							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$material_desc		= $r1['material_desc'];
							$material_id		= $r1['material_id'];
				
							$sql="SELECT * FROM sma_product where 1 and id = '$material_id' ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$rw = mysqli_fetch_array($rs);
								$product_group = $rw['product_group'];
							
						?>
                <form class="form-horizontal" action="#" method="POST" >
					<input type="hidden" id="rId" name="rid" value="<?php echo $srno;?>">
					<input type="hidden" id="tender_hdr_Id_e" name="tender_hdr_id" value="<?php echo $tender_hdr_id;?>">
					
<!-- Approval MEMO QTY & Value -->

					<div class="form-group">
						<div class="col-sm-4">
							<label for="itemCategory" class="control-label"> Category</label>
                            <select class="form-control" id="categoryId" <?php echo $readonly; ?> onchange="getmaterial(this.value)">
							<option value="">Select</option>
                             <?php
                               	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                $rs = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                while($rw = mysqli_fetch_array($rs)){
                             ?>
                                <option value="<?php echo $rw['id']?>"   <?php echo ($product_group == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['product_group'] ?></option>
                             <?php } ?>
                             </select>
						</div>
								
							<div class="col-sm-6">
							<label for="itemName" class="control-label">Product</label>
							<span id="getmaterial" >
								<select class="form-control" name="material_id" id="itemName_e"  <?php echo $readonly; ?>  onchange="getunit3(this.value)" >
								<option value="">Select</option>
								<?php
								$sql="SELECT * FROM sma_product where 1 ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								while($rw = mysqli_fetch_array($rs)){
								?>
									<option value="<?php echo $rw['id']?>" <?php echo ($material_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'];?></option>
								<?php } ?>
								</select>
							</span>
							</div>
					</div>
						
					<div class="form-group">
						<div class="col-sm-12">
							<label for="itemDescription" class="control-label">Description </label>
                            <textarea rows='02' class="form-control" id="itemDescription_e" name="itemdescription" <?php echo $readonly1; ?>  placeholder="Item Description..."><?php echo $material_desc;?></textarea>
                        </div>
					</div>
						    
					
               <div class="well well-sm" >    
                        			
					<div class="form-group">
                            
							<div class="col-sm-6">
								<label class="control-label ">Cost Center Group</label>
									
								<?php
								$sql = " SELECT * FROM sma_budget_name where 1 and id = '$cost_center_group' ";
								$q2  = mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$budget_name = $r2['name'];
								?>		
									<input type="text" class="form-control" readonly value="<?= $budget_name; ?>" >
								
                            </div>
		
							<div class="col-sm-6">
								<label class="control-label ">Cost Center Name</label>
								
								<?php
									$sql = "SELECT * from sma_budget_subgroup where id = '$cost_center_subgroup' ";		
									$q2  = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_object($q2);
									$budget_head = $r2->budget_head;
									$id = $r2->id;
								?>	
									<input type="text" class="form-control" readonly value="<?= $budget_head; ?>" >
								
							</div>
					</div>
						    
							<?php
							
								$b_readonly = '';
								$b_readonly   		= "READONLY";
									
							?>
							
								<input type="hidden" class="form-control" name="budget_name_e" readonly value="<?php echo $budget_name_id ?>" >
								<input type="hidden" class="form-control" name="budget_head_e" readonly value="<?php echo $budget_head ?>" >
							
				</div>
					
							
						<div class="form-group">	
							<div class="form-group col-md-12">
								
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label ">Qty.</label>
                                
									<input type="text" class="form-control" id="itemQuantity_e" name="itemquantity"  style="text-align:right;" <?php echo $readonly; ?> value="<?php echo $qty;?>" 
									onkeyup="calculateTotalAmounte();getvaluecheck();">
									
                                </div>
								
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label col-sm-1">Units</label>
								
									<span id="getunit3">
										<input type="text" class="form-control" id="itemUnits_e" name="itemunits" readonly value="<?php echo $unit;?>" >
                                	</span>
                                </div>
                            
						<?php
							if($delivery_date=='01-01-1970' || $delivery_date=='31-12-1969' || $delivery_date== '30-11--0001'){
								$delivery_date='';
							}
						?>
								<div class="col-sm-3">
									<label class="control-label ">Delivery Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" name='deliverydate' id="deliveryDate" value ="<?= $delivery_date; ?>" <?php echo $readonly; ?>>
									</div>
								</div>
								
								<div class="col-sm-6">
									<span id="getbudgeterror" style="color:red;"></span>
								</div>
							</div>
						
                        </div>	
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
			<?php if (empty($readonly)){ ?>
                <input type="submit" class="btn btn-primary" id="editItem12345" name="editSave" value="Save" onclick="getvaluecheck()">
			<?php } ?>	
            </div>
			
			
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>

 	function getvaluecheck(){
		
		var ap_value_a		= parseInt($("#ap_value_a").val());
		var ap_quantity		= parseInt($("#ap_quantity").val());
		var po_value_a		= parseInt($("#po_value_a").val());
		var po_quantity		= parseInt($("#po_quantity").val());
		
		var item_quantity	= parseInt($("#itemQuantity_e").val()) + po_quantity;
		
//alert(item_quantity+ ' <<>>' + ap_value_a +' <#> ' + ap_quantity +' <#> '+ po_value_a +' <#> ' + po_quantity);
		var err = '';
		$('#getbudgeterror').html(err);
		if(item_quantity > ap_quantity){
			alert('Quantity overflow should not be for Approved quantity...');
			var err = 'Quantity overflow should not be for Approved quantity...';
			$('#getbudgeterror').html(err);
			return false;
		}

		
	}

	
	function getmaterial123(id){
		var sub    = 'sub33';
		var strURL = "app_func.php";

		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('#getmaterial').html(result);
		});

	}
	
	function getmaterial(id){
		var sub    = 'sub3';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial').html(result);
		});

		$.post(strURL,{id:id,company_id:company_id,sub14:sub},function(result){
		      $('#getcatbudget').html(result);
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

		//var rid    = $("#rId").val();
		var rid		= '<?php echo $rid ?>';
		var abc    = document.getElementsByName("abc")[1].value;

//alert(sub + ' ' + rid+ ' ' + abc);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#'+abc).html(result);
		});

	}



</script>


