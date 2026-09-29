<!-- Modal Edit Item-->
<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Product</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
							$sql  = "SELECT * from sma_supplier_invoice_details where si_srno = '$srno' ";
//echo $sql."<BR>";	
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$dtl_id	= $r1['dtl_id'];
							$po_qty	= $r1['po_qty'];
							$qty 	= $r1['qty'];
							$rate 	= $r1['rate'];
							$gst	= $r1['gst'];
							$amount	= $r1['amount'];
							$gst_id	= $r1['gst_id'];
							$gstamt = (($qty * $rate) * $gst / 100);
							//$amount = ($qty * $rate) + $gstamt;
					
					//echo $gst . ' ' .$amount. ' ' .$gst . ' ' .$amount;
					
							$unit			= $r1['unit'];
//							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$description	= $r1['description'];
							$material_id	= $r1['material_id'];
							$grn_no			= $r1['grn_no'];
							$description 	= $r1['description'];
							$account_year   = $r1['account_year'];
							$company_id     = $r1['company_id'];
							$budget_name    = $r1['budget_name'];
							$budget_head    = $r1['budget_head'];
							$budget_id      = $r1['budget_id'];
							
							$sql="SELECT * FROM sma_product where id = '$material_id' ";
                            $rs = mysqli_query($con, $sql);
                            echo mysqli_error($con);
                            $r3 = mysqli_fetch_array($rs);
							$product_group = $r3['product_group'];
							$category 		= $r3['category'];  
									
							$sql = "SELECT * FROM `sma_po_items` where purchase_id = '$our_po_ref_no' and product_id = '$material_id' and budget_id = '$budget_id' ";
//echo $sql. "<BR>";							
								$res2 = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$cat = mysqli_fetch_array($res2);
								$poqty 		= $cat['quantity'];
								$bal_poqty 	= $cat['bal_si_qty'];
								$unit_rate  = $cat['unit_rate'];
								$bal_poamount 	= $cat['bal_si_amount'];
								$po_gst     	= $cat['gst'];
								
							if($category=='M'){	
								if($po_qty>0){
									$bal_qty = $poqty - $bal_poqty;
									
								//echo 	$poqty. ' - ' . $bal_poqty;
								}	
							}
							else if($category=='S'){
								$po_gst_v = '1' . '.'.$po_gst ;	
								$gstvalue = 0;
								$bal_qty = 0;
								if($bal_poamount>0){
									$gstvalue = $bal_poamount / ($po_gst_v );
								
									$bal_qty = $unit_rate - $gstvalue ;
								}
								//echo 	$bal_qty. ' ' . $unit_rate . ' - ' .$bal_poamount. ' >< ' . $gstvalue;
							}	
//siitem_update.php
						?>
					<div id="box">
                        <form class="form-horizontal" action="" method="POST">
						  <div class="box-body">
							
							<input type="hidden" id="dtl_id" name="dtl_id" value="<?php echo $dtl_id;?>">
							<input type="hidden" id="rid_e" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="si_hdr_Id_e" name="si_hdr_id" value="<?php echo $_GET['id'];?>">

							<fieldset>
							<?php
								
								$grn_no = $our_po_ref_no ;
				//echo $against_po_flag;			
						if($against_po_flag=='Y'){
						//if(!empty($grn_no)){	
								$sql = "SELECT * from sma_purchase_order where id = '$grn_no' ";
								$rs = mysqli_query($con, $sql);
                                $rw = mysqli_fetch_array($rs);
								$received_date = date('d-m-Y', strtotime($rw['dated']));
						} ?>
						
								<?php
                                   	
									$sqlb= '';
									$onchange ='';
									$readonly_drafta = '';
								
									if($against_po_flag=='Y'){	
										$sqlb = " and id = '$product_group' ";
										
										$readonly_drafta = 'READONLY';
									}
									else {
										
										$onchange = ' onchange="getmaterial(this.value)" ';	
									}	
                                ?>
								
							<div class="form-group">
								
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" readonly <?= $readonly_drafta; ?> <?= $readonly ?> id="categoryId" name="" <?= $onchange; ?> >
										
                                    <?php
									
                                    $sql="SELECT * FROM sma_product_group where 1 $sqlb ORDER BY product_group ASC";
                                    $rs = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($product_group == $rw['id'])?'selected="selected"':'';?>  ><?php echo $rw['product_group'] ?></option>
                                    <?php } ?>
                                    </select>
								</div>
								
                                <div class="col-sm-8">
									<label for="material" class="control-label "> Product</label>
									<span class="getmaterial" >
                                    <select class="form-control" readonly name="itemName" id="itemName_e"  <?php echo $readonly ?>  <?= $readonly_drafta; ?> ><!--STYLE="width: 270px" -->
										
                                    <?php
                                    	$sql="SELECT * FROM sma_product where 1 and product_group = '$product_group' and id = '$material_id' ORDER BY name ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($material_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'] ?></option>
                                        <?php } ?>
                                    </select>
									</span>
                                </div>
								
                            </div>
							
							<div class="form-group ">
                                <div class="col-sm-6">
									<label for="itemDescription" class="control-label">Description</label>
                                    <input type="text" class="form-control" id="itemDescription_e" <?php echo $readonly ?> <?= $readonly_drafta; ?> readonly name="itemdescription" size="55"  value="<?php echo $description;?>">
                                </div>
                            
<!--Budget-->
				<?php

					$sql = " SELECT * FROM sma_budget where id = '$budget_id' "; 
										
//echo $sql;
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$account_year  		= $r2->account_year;
					$budget_head 		= $r2->budget_head;
					$budget_name_id 	= $r2->budget_name;
					$total_budget		= $r2->total_budget;
					$used_budget		= $r2->used_budget;
					$blocked_budget		= $r2->blocked_budget;
					$adjustment_budget	= $r2->adjustment_budget;
					$balance_budget = ( $total_budget + $adjustment_budget ) - ( $used_budget + $blocked_budget );
					$balance_budget = $blocked_budget;
					
					$sql = " SELECT * FROM sma_product where id = '$material_id' ";
					$res2 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$mat = mysqli_fetch_array($res2);
					$product_name = $mat['name'];
					$product_category = $mat['product_group'];
					$unit = $mat['uom'];
													
					$sql = "SELECT * FROM sma_budget_name where id = '$budget_name_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$budget_name = $r2->name;
					
					$sql = "SELECT * FROM sma_budget_subgroup where id = '$budget_head' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$budget_head = $r2->budget_head;
					
				?>

				<!--	<div class="col-sm-6">
						<label class="control-label">Posting Account (DR)</label>
						<input type="text" class="form-control" id="posting_account_a" name="posting_account_a"  readonly value="<?= $posting_account_a ?>" >
					</div>
				-->	
				</div>
				
						<div class="form-group">
							<input type="hidden" id="itemrate_ep" name="itemrate_p" <?php echo $readonly ?> min="0" value="<?php echo $rate;?>" >
										
							<input type="hidden" class="form-control" id="budget_id_p" name="budget_id_p" readonly value="<?php echo $budget_id ?>" >
										
							<input type="hidden" class="form-control" id="budget_id" name="budget_id" readonly value="<?php echo $budget_id ?>" >
							
							<input type="hidden" id="CATEGORY<?= $srno;?>" name="category" readonly value="<?= $category ?>" >
									
									
							<!--<div class="col-sm-2">-->
							<!--	<label class="control-label ">Account Year</label>-->
							<!--	<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >-->
							<!--</div>-->
							
							<!--<div class="col-sm-3">-->
							<!--	<label for="itemName" class="control-label">Cost Center Group</label>-->
									
									<?php
										
								// 	$sql = " SELECT * FROM sma_budget_name where 1 ORDER BY name ASC "; //
								// 		$q2  = mysqli_query($con, $sql);
								// 		while($r2 = mysqli_fetch_object($q2)){
								// 			$name = $r2->name;
								// 			$id = $r2->id;
									?>
										
							<!--		<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_name ?>" >-->
							<!--	</div>	-->
								<!--<div class="well well-sm" >-->
							<!--	<div class="col-sm-5">-->
							<!--		<span class="getcostcenter" >-->
							<!--			<label class="control-label">Cost Center Name</label>-->
							<!--			<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >-->
							<!--		</span>-->
							<!--	</div>-->
										
							<!--	<div class="col-sm-2">-->
							<!--		<label class="control-label ">Balance Budget </label>-->
							<!--		<input type="text" class="form-control" id="balance_budget_a" readonly style="text-align:right;" value="<?php echo number_format($balance_budget,2) ?>" >-->
							<!--	</div>-->
										
							<!--</div>-->
					
			</div>
<!--Budget -->		
				<?php 
					$sql = "select * from gst_mst where 1 order by gst_name ";
					$q22 	= mysqli_query($con, $sql);
					while($r22 = mysqli_fetch_array($q22)){ 
						$igst = $r22['igst'];
					}
					$value_txt = 'Qty.';
					if($category=='S'){
						$value_txt = 'Value';
					}	 
				?>						
					<div class="col-sm-12">		
							<div class="form-group">
					
								<div class="col-sm-2">
									<label for="itemGST" class="control-label">GST%</label>
								
                                    <input type="text" class="form-control itemGST_e<?= $srno;?>" id="itemGST_e" name="itemgst" style="text-align:right;" readonly value="<?php echo $gst;?>">
									
									<input type="hidden" class="form-control itemGST_ID_e" readonly name="itemgst_id" id="itemGST_ID_e"  value="<?= $gst_id; ?>" >
								
									<input type="hidden" class="form-control" name="itemgst_p" value="<?= $gst; ?>" >
                                </div>
					            <div class="col-sm-2">
									<label for="itemQuantity" class="control-label" style="color:red;" >Received <?= $value_txt;?></label>
									<input type="hidden" id="itemQuantity_ep" name="itemqty_p" <?php echo $readonly ?> min="0" value="<?php echo $qty;?>" >
									
                                    <input type="text" class="form-control itemQuantity_e<?= $srno;?>" id="itemQuantity_e" name="itemqty" <?php echo $readonly ?> min="0" style="text-align:right;" value="<?php echo $qty;?>" onkeyup="calculateTotalAmounte(<?= $srno;?>);">
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
                                	<span id="getunit1" >
                                		<input type="text" class="form-control" id="itemUnits_e" name="itemunits" readonly value="<?php echo $unit ?>" >
									</span>
								</div>
                           
						-	    <div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control itemRate_e<?= $srno;?>" id="itemRate_e" name="itemrate" <?= $readonly_drafta; ?> <?php echo $readonly ?>  placeholder="0.00" style="text-align:right;" readonly value="<?php echo $rate;?>" min="0" >
										
                                    </div>
                                </div>
                            
                        <!--        <div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total </label>
                                    <input type="text" class="form-control" id="itemAmount_e" name="itemamount" style="text-align:right;" readonly value="<?php //echo number_format($amount,2);?>">
                                </div>
						-->
						
                            </div>
						</div>
						<span class="budget_errmsg<?= $srno;?>" style="color:red;" ></span>
						
						<div class="col-sm-12">
							<div class="form-group">
								<div class="col-sm-2">
									<label for="itemQuantity" class="control-label">PO.<?= $value_txt;?></label>
									<input type="text" class="form-control" readonly style="text-align:right;" value="<?php echo $po_qty;?>" >
								</div>
					
								<div class="col-sm-2">
									<label for="balAmount" class="control-label">Balance <?= $value_txt;?></label>
                                    <input type="text" class="form-control bal_qty<?= $srno;?>" style="text-align:right;" readonly value="<?php echo $bal_qty;?>" >
                                </div>
							
								<div class="col-sm-2">
									<label for="balAmount" class="control-label">Balance Amount.</label>
                                    <input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo number_format($amount,0);?>">
                                </div>
							</div>
							
						</div>
						
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
			<?php if (empty($readonly)){ ?>
                <input type="submit" class="btn btn-primary editItemSave<?= $srno;?>" id="editItem123" name="editItem"  value="Save" >
			<?php } ?>	
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



<Script>

   function calculateTotalAmounte(srno){
//alert(srno); 1 42-43 -1 
		var qty1		= parseInt($(".itemQuantity_e"+srno).val());
		var bal_qty		= parseInt($(".bal_qty"+srno).val());
		var category	= parseInt($(".CATEGORY"+srno).val());
        
//		 if(bal_qty!=0){
//			bal_qty = bal_qty - qty1;
//		 }
//alert(bal_qty + ' ' + qty1);
		
		$('.budget_errmsg'+srno).html('');
		$('.editItemSave'+srno).show();		
		
		var txt = 'Quantity ';
		if(category=='S'){
			var txt = 'Value ';
		}
		
		if(qty1 > bal_qty && bal_qty > 0){
			 errmsg = txt + " should not be more then balance " + txt + " ...";
			 $('.budget_errmsg'+srno).html(errmsg);
			 $('.editItemSave'+srno).hide();
			 return false;
		}
		
		
    }

</script>