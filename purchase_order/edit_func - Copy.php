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
							$sql  = "SELECT * from sma_po_items where id = '$srno' ";
		//echo $sql. ' ' . "<BR>"; 	
		//TEST
		$readonly ='';
							$readonly1 = $readonly;
							if($po_type=='A' && $status!='Completed'){
								$readonly1='';
							}
						//	echo $po_type. "<<>>";
							
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$purchase_id 	= $r1['purchase_id'];
		$sql = " select * from sma_purchase_order where id = '$purchase_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$company_id 		= $r2['project'];
		$po_type			= $r2['po_type'];
		$approval_memo_ref 	= $r2['approval_memo_ref'];
		
							$qty 			= $r1['quantity'];
							
							$bal_si_qty		= $row2['bal_si_qty'];
							
							if($bal_si_qty==0 && $po_type =='A' ){
								$qty = $bal_si_qty;
							}
							else {
								$qty		= $row2['bal_si_qty'];
							}	
							
							if($po_type =='C' ){
								$qty 			= $r1['quantity'];
							}	
							$rate 	= $r1['unit_rate'];
							$gst_id = $r1['gst_id'];
							
							$gst	= $r1['gst'];
							if(empty($gst)){
								$gst = 0;	
							}	
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							
							$unit	= $r1['uom'];
							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$product_desc	= $r1['product_desc'];
							$product_id		= $r1['product_id'];
				
							$total_budget		= $r1['total_budget'];
							$balance_budget		= $r1['balance_budget'];
							$budget_id			= $r1['budget_id'];
							
							$sql="SELECT * FROM sma_product where 1 and id = '$product_id' ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$rw = mysqli_fetch_array($rs);
								$product_group = $rw['product_group'];
							

							$sql="SELECT * FROM gst_mst where 1 and id = '$gst_id' ";
					//echo $sql;		
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$rw = mysqli_fetch_array($rs);
								$igst = $rw['igst'];
							if($gst==0){
								$gst = $igst;
							}	
														
							$sql = "SELECT * FROM `sma_budget` where id = '$budget_id' ";
						//echo $sql;	
									$bd = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    $bdrw = mysqli_fetch_array($bd);
									$budget_id 			= $bdrw['id'];	
									$budget_name  		= $bdrw['budget_name'];
									$budget_name_id		= $bdrw['budget_name'];
									$budget_head 		= $bdrw['budget_head'];
									$project 			= $bdrw['project'];
									$total_budget		= $bdrw['total_budget'];
									$used_budget		= $bdrw['used_budget'];
									$blocked_budget		= $bdrw['blocked_budget'];
									$balance_budget		= $total_budget - ( $used_budget + $blocked_budget );
	if($po_type=='A'){
		$sql = "SELECT *  FROM  sma_approval_items WHERE 1 and product_id = '$product_id' and approval_hdr_id = '$approval_memo_ref' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$quantity		= $r2->quantity;
		$unit_rate   	= $r2->unit_rate;
		$gst		   	= $r2->gst;
		if(empty($gst)){
			$gst=0;
		}
		$ap_value 		= round(($quantity * $unit_rate ) + (($quantity * $unit_rate ) * $gst / 100),0);
		$ap_quantity 	= $quantity;		
		
		$sql = "SELECT b.*  FROM  sma_purchase_order a, sma_po_items b WHERE 1 and a.id = b.purchase_id and b.product_id = '$product_id' and a.approval_memo_ref = '$approval_memo_ref' ";
		
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$quantity		= $r2->quantity;
			$unit_rate   	= $r2->unit_rate;
			$gst		   	= $r2->gst;
			if(empty($gst)){
				$gst=0;
			}
			
			//$po_quantity 	+= $quantity;
			$po_value 		+= ($quantity * $unit_rate ) + (($quantity * $unit_rate ) * $gst / 100);
		}
		
		
	}
	else {	
		$po_quantity 	=  $po_quantity - $qty;
		$po_value   	=  $po_value   - $amount;
	}	

		if($bal_si_qty==0){
			$po_quantity = $bal_si_qty;
		}
			
							
						//	echo $categoryid; poitem_update.php
						?>
                <form class="form-horizontal" action="#" method="POST" >
					<input type="hidden" id="rId" name="rid" value="<?php echo $srno;?>">
					<input type="hidden" id="purchaseId_e" name="purchase_id" value="<?php echo $_GET['id'];?>">
					
<!-- Approval MEMO QTY & Value -->


		<input type="hidden" id="ap_value_a" name="ap_value" value="<?php echo $ap_value ?>" >
		<input type="hidden" id="ap_quantity" name="ap_quantity" value="<?php echo $ap_quantity ?>" >
<!-- Purchase Order QTY & Value -->		
		<input type="hidden" id="po_value_a" name="po_value"  value="<?php echo $po_value ?>" >
		<input type="hidden" id="po_quantity" name="po_quantity"  value="<?php echo $po_quantity ?>" >
		
		<input type="hidden" name="approval_memo_ref"  value="<?= $approval_memo_ref ?>" >
		
		
					<input type="hidden" id="po_type" name="po_type" value="<?php echo $po_type;?>">
					
					<input type="hidden" id="budget_id_curr" name="budget_id_curr" value="<?php echo $budget_id;?>">
					<input type="hidden" id="budget_id_prev" name="budget_id_prev" value="<?php echo $budget_id;?>">
			<?php	
				if($bal_si_qty!=0){ ?>	
					<input type="hidden" id="qty_prev" name="qty_prev" value="<?php echo $qty;?>">
			<?php } ?>		
					<input type="hidden" id="rate_prev" name="rate_prev" value="<?php echo $rate;?>">
					<input type="hidden" id="gst_prev" name="gst_prev" value="<?php echo $gst;?>">
							
					<div class="form-group">
						<div class="col-sm-4">
							<label for="itemCategory" class="control-label"> Category</label>
                            <select class="form-control" id="categoryId" onchange="getmaterial(this.value)">
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
								<select class="form-control" name="product_id" id="itemName_e"  <?php echo $readonly; ?>  onchange="getunit3(this.value)" >
								<option value="">Select</option>
								<?php
								$sql="SELECT * FROM sma_product where 1 ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								while($rw = mysqli_fetch_array($rs)){
								?>
									<option value="<?php echo $rw['id']?>" <?php echo ($product_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'];?></option>
								<?php } ?>
								</select>
							</span>
							</div>
					</div>
						
					<div class="form-group">
						<div class="col-sm-12">
							<label for="itemDescription" class="control-label">Description </label>
                            <textarea rows='02' class="form-control" id="itemDescription_e" name="itemdescription" <?php echo $readonly1; ?>  placeholder="Item Description..."><?php echo $product_desc;?></textarea>
                        </div>
					</div>
						    
					
               <div class="well well-sm" >    
                        			
					<div class="form-group">
                            
							<div class="col-sm-6">
								<label class="control-label ">Cost Center Group</label>
									
								<?php
								$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";
								$q2  = mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$budget_name = $r2['name'];
								?>		
									<input type="text" class="form-control" readonly value="<?= $budget_name; ?>" >
								
                            </div>
		
							<div class="col-sm-6">
								<label class="control-label ">Cost Center Name</label>
								
								<?php
									$sql = "SELECT * from sma_budget where id = '$budget_id' ";		
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
									
									$sql = "SELECT * from company where comp_id = '$project' ";
									$res = mysqli_query($con, $sql);
									//echo mysqli_error($con);
									$r2 = mysqli_fetch_array($res);
									
									$comp_name = $r2['comp_name'];
									
									
									$sql = "SELECT * from sma_budget_name where id = '$budget_name_id' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									$budget_name = $r2['name'];
									
									$sql="SELECT * FROM sma_product where id = '$product_id' ";
									$res2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									$mat = mysqli_fetch_array($res2);
									$product_name = $mat['name'];
									$product_category = $mat['group'];
													
									$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									
									$bname = $r2['name'];

								?>
							
								<input type="hidden" class="form-control" name="budget_name_e" readonly value="<?php echo $budget_name_id ?>" >
								<input type="hidden" class="form-control" name="budget_head_e" readonly value="<?php echo $budget_head ?>" >
							
								
							<div class="form-group">
									<label class="control-label col-sm-3">Total Budget </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="total_budget_ab" style="text-align:right;" readonly value="<?php echo number_format($total_budget,2) ?>" >
									</div>
									
									<label class="control-label col-sm-3">Balance Budget </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo number_format($balance_budget,2) ?>" >
										
										<input type="hidden" id="balance_budget_ab" value="<?= $balance_budget ?>" >
									</div>
							</div>
						</div>
					
							
						<div class="form-group">	
							<div class="form-group col-md-12">
								
								<!--<div class="col-sm-2">
									<label for="itemGST" class="control-label ">GST Type</label>
									<select class="form-control" name="itemgst_id12" id="itemGST_id12" onchange="getgst1(this.value)" <?php echo $readonly; ?> >
									<option value=""> Select </option>
								<?php 
									$sql = "select * from gst_mst where 1  order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
								?>
									<option value="<?php echo $r22['id'].'-'.$r22['igst']; ?>" <?php echo ($gst_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['gst_name'];?></option>
									<?php } ?>
									</select>
								</div> -->
								
								
                                <div class="col-sm-2">
									<label for="gst" class="control-label ">GST%</label>
									<input type="text" class="form-control itemGST_e" id="itemGST_e" name="itemgst" <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $gst;?>" onkeyup="calculateTotalAmounte();">
									
									<input type="hidden" class="form-control itemGST_id1" name="itemgst_id" id="itemGST_id1"  <?php echo $readonly; ?> value="<?php echo $gst_id;?>" >
									
                                </div>
                            
								    
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
                            
								
                                <div class="col-sm-2">
									<label for="itemRate" class="control-label ">Rate</label>
									<div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate_e" name="itemrate" <?php echo $readonly; ?> placeholder="0.00" style="text-align:right;" value="<?php echo $rate;?>"  onkeyup="calculateTotalAmounte();">
									
                                    </div>
                                </div>
								
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total</label>
								
									<input type="text" class="form-control" id="itemAmount_e" name="itemamount" <?php echo $readonly; ?> style="text-align:right;" readonly value="<?php echo $amount;?>">
                                </div>
								
                            </div>
						</div>
						
                            <div class="form-group col-md-12">
							
						<?php
							if($delivery_date=='01-01-1970' || $delivery_date=='31-12-1969' || $delivery_date== '30-11--0001'){
								$delivery_date='';
							}
						?>
								<label class="control-label col-sm-2">Delivery Date</label>
								<div class="col-sm-3">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" autocomplete = 'off' name='deliverydate' id="deliveryDate" value ="<?= $delivery_date; ?>" <?php echo $readonly; ?>>
									</div>
								</div>
								
								<div class="col-sm-6">
									<span id="getbudgeterror" style="color:red;"></span>
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


