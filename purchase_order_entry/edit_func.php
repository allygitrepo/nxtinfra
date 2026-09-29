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
							
							$srno   = $rid;
							$srno_u = $rid;
							$sql  = "SELECT * from sma_po_items where id = '$srno' ";
		//echo $sql. ' ' . "<BR>";
//echo $role. ' <<>> ';		
		
							$readonly1 = $readonly;
							if( $status!='Completed'){
								$readonly1='';
							}
						//	echo $po_type. "<<>>";
							
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$purchase_id 	= $r1['purchase_id'];
							$qty 			= $r1['quantity'];
							$qty_po 			= $r1['quantity'];
							$rate 			= $r1['unit_rate'];
							$rate_po			= $r1['unit_rate'];
							$gst_id 		= $r1['gst_id'];
							$first_insert	= $r1['first_insert'];
							$budget_id 		= $r1['budget_id'];
							$tds_id			= $r1['tds_id'];
							
							$gst	= $r1['gst'];
							if(empty($gst)){
								$gst = 0;	
							}	
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							
							$unit	= $r1['uom'];
							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$product_desc	= $r1['product_desc'];
							$product_id		= $r1['product_id'];
							
							$sql="SELECT * FROM sma_product where 1 and id = '$product_id' ";
								$rs = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$rw = mysqli_fetch_array($rs);
								$product_group 	= $rw['product_group'];
								$category 		= $rw['category'];
							if(empty($unit)){
								$unit	        = $rw['uom'];
							}
							
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
									$account_year  		= $bdrw['account_year'];
									$budget_name  		= $bdrw['budget_name'];
									$budget_name_id		= $bdrw['budget_name'];
									$budget_head 		= $bdrw['budget_head'];
									$project 			= $bdrw['project'];
									$adjustment_budget	= $bdrw['adjustment_budget'];
									$total_budget		= round($bdrw['total_budget'] + $adjustment_budget,0);
									$used_budget		= round($bdrw['used_budget'],0);
									$blocked_budget		= round($bdrw['blocked_budget'],0);
									$balance_budget		= round(( $total_budget ) - ( $used_budget + $blocked_budget ),0);
							
				// 			$sql = "SELECT * FROM `sma_product_cost_center` where product_id = '$product_id' and budget_id = '$budget_name' and company_id = '$project' ";
				// 	//	echo $sql;	
				// 					$bd = mysqli_query($con, $sql);
    //                                 echo mysqli_error($con);
    //                                 $bdrw = mysqli_fetch_array($bd);
				// 					//$tally_account_id 			= $bdrw['tally_account_id'];	
									
		$sql = "SELECT *  FROM  sma_purchase_req_items WHERE 1 and product_id = '$product_id' and purchase_req_id = '$approval_memo_ref' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$quantity		= $r2->quantity;
		$unit_rate   	= $r2->rate;
		$gst		   	= '';
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
			
			$po_quantity 	+= $quantity;
			$po_value 		+= ($quantity * $unit_rate ) + (($quantity * $unit_rate ) * $gst / 100);
		}
		$po_quantity 	=  $po_quantity - $qty;
		$po_value   	=  $po_value   - $amount;
		
						//	echo $categoryid; poitem_update.php onsubmit="getvaluecheck(); return false; "
						
						
						?>
                <form class="form-horizontal" action="#" method="POST" >
					<input type="hidden" id="rId" name="rid" value="<?php echo $srno;?>">
					<input type="hidden" id="purchaseId_e" name="purchase_id" value="<?php echo $_GET['id'];?>">
					
<!-- Approval MEMO QTY & Value -->

		<input type="hidden" id="ap_value_a<?= $srno;?>" name="ap_value" value="<?php echo $ap_value ?>" >
		<input type="hidden" id="ap_quantity<?= $srno;?>" name="ap_quantity" value="<?php echo $ap_quantity ?>" >
<!-- Purchase Order QTY & Value -->		
		<input type="hidden" id="po_value_a<?= $srno;?>" name="po_value"  value="<?php echo $po_value ?>" >
		<input type="hidden" id="po_quantity<?= $srno;?>" name="po_quantity"  value="<?php echo $po_quantity ?>" >
		
		<input type="hidden" name="approval_memo_ref"  value="<?= $approval_memo_ref ?>" >
		
		
					<input type="hidden" id="po_type" name="po_type" value="<?php echo $po_type;?>">
					
					<input type="hidden" id="budget_id_curr" name="budget_id_curr" value="<?php echo $budget_id;?>">
					<input type="hidden" id="budget_id_prev" name="budget_id_prev" value="<?php echo $budget_id;?>">
					
					<input type="hidden" id="first_insert" name="first_insert" value="<?php echo $first_insert;?>">
					
		<?php if ($first_insert!='F'){ ?>		
					<input type="hidden" id="qty_prev" name="qty_prev" value="<?php echo $qty;?>">
		<?php } 
			  else { ?>
					<input type="hidden" id="qty_prev" name="qty_prev" value="0">
		<?php }	?>
					<input type="hidden" id="rate_prev" name="rate_prev" value="<?php echo $rate;?>">
					<input type="hidden" id="gst_prev" name="gst_prev" value="<?php echo $gst;?>">
				<?php			
							$sqln = "";
							if($readonly){
								$sqlp = " and id = '$product_group' ";
							}	
					
						?>
					<div class="form-group">
						<div class="col-sm-4">
							<label for="itemCategory" class="control-label"> Category</label>
                            <select class="form-control" readonly id="categoryId" <?php echo $readonly; ?> onchange="getmaterial(this.value)">
							<option value="">Select</option>
                             <?php
                               	$sql="SELECT * FROM sma_product_group where 1 $sqlp ORDER BY product_group ASC";
                                $rs = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                while($rw = mysqli_fetch_array($rs)){
                             ?>
                                <option value="<?php echo $rw['id']?>"   <?php echo ($product_group == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['product_group'] ?></option>
                             <?php } ?>
                             </select>
						</div>
					<?php			
							$sqln = "";
							if($readonly){
								$sqln = " and id = '$product_id' ";
							}	
						?>	 
							<div class="col-sm-6">
							<label for="itemName" class="control-label">Product</label>
							<span id="getmaterial" >
								<select class="form-control" readonly name="product_id" id="itemName_e"  <?php echo $readonly; ?>  onchange="getunit3(this.value)" >
								<option value="">Select</option>
								<?php
								$sql="SELECT * FROM sma_product where 1 " . $sqln;
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
                            <textarea rows='03' class="form-control" id="itemDescription_e" name="itemdescription" <?php echo $readonly1; ?>  placeholder="Item Description..."><?php echo $product_desc;?></textarea>
                        </div>
					</div>
						    
				<?php
					if($budget_id ==0 ){
							
						echo  "<span style='color:red;'><b>No Budget defined in the Product</b></span>";
							
					}	
				?>		
					
               <div class="well well-sm" >    
                        			
					<div class="form-group">
                            <div class="col-sm-2">
								<label class="control-label ">Account Year</label>
								<input type="text" class="form-control" readonly value="<?= $account_year; ?>" >
							</div>
							
							<div class="col-sm-4">
								<label class="control-label ">Budget Group</label>
									
								<?php
								$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";
								$q2  = mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$budget_name = $r2['name'];
								?>		
									<input type="text" class="form-control" readonly value="<?= $budget_name; ?>" >
								
                            </div>
		
							<div class="col-sm-6">
								<label class="control-label ">Budget Sub Group</label>
								
								<?php
									$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";		
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
									$budget_control_gst = $r2['budget_control_gst'];
									
									
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
								<input type="hidden" class="form-control budget_control_GST<?= $srno;?>" name="budget_control_GST" id="budget_control_GST" readonly value="<?php echo $budget_control_gst ?>" >
								
								
							
								
							<div class="form-group">
									<label class="control-label col-sm-3">Total Budget </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="total_budget_ab" style="text-align:right;" readonly value="<?php echo number_format($total_budget,2) ?>" >
									</div>
									
									<label class="control-label col-sm-3">Balance Budget </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo number_format($balance_budget,2) ?>" >
										
										<input type="hidden" class="balance_budget_AB<?= $srno;?>" id="balance_budget_ab" value="<?= $balance_budget ?>" >
									</div>
							</div>
						</div>
					
							
						<div class="form-group">	
							<div class="form-group col-md-12">
					<?php
						$value_txt ='';
						$readonly_v = '';
						$sqlm = '';
						if($category=='S'){
							//$qty = 1;
							$value_txt = '/Value';
						//	$readonly_v	= 'READONLY';
							
							if($rate==1){
								$rate = $qty_po;	
							}
							
							$sqlm = " and account_name != 'NO TDS' ";
							
						} 
						if($category=='M'){
							$sqlm = " and account_name = 'NO TDS' ";
						}	
						
					?>
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label ">Qty.</label>
                                
									<input type="text" class="form-control itemQuantity_e<?= $srno;?>" id="itemQuantity_e" name="itemquantity"  style="text-align:right;" readonly <?php echo $readonly. ' ' . $readonly_v; ?> value="<?php echo $qty;?>" 
									onkeyup="calculateTotalAmounte(<?= $srno;?>);getvaluecheck(<?= $srno;?>);">
									
                                </div>
								
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label col-sm-1">Units</label>
								
									<span id="getunit3">
										<input type="text" class="form-control" id="itemUnits_e" name="itemunits" <?php echo $readonly1; ?> value="<?= $unit;?>" >
                                	</span>
                                </div>
                            
                                <div class="col-sm-2">
									<input type="hidden" class="itemRate_e_prev<?= $srno;?>" id="itemRate_e_prev" name="itemrate_prev"  value="<?php echo $rate;?>" >
								
									<label for="itemRate" class="control-label ">Rate <?= $value_txt;?></label>
									<div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control itemRate_e<?= $srno;?>" id="itemRate_e" name="itemrate" readonly <?php echo $readonly; ?> placeholder="0.00" style="text-align:right;" value="<?php echo $rate;?>"  onkeyup="calculateTotalAmounte(<?= $srno;?>);getvaluecheck(<?= $srno;?>);">
									
                                    </div>
                                </div>
								
								<div class="col-sm-3">
									<label for="gst" class="control-label ">GST%</label>
								<!--	<input type="text" class="form-control itemGST_e<?= $srno;?>" id="itemGST_e" name="itemgst" readonly <?php echo $readonly; ?> style="text-align:right;" value="<?php echo $gst;?>" onkeyup="calculateTotalAmounte(<?= $srno;?>);">-->
									
									<input type="hidden" class="form-control itemGST_id1" name="itemgst_id1" id="itemGST_id1"  <?php echo $readonly; ?> value="<?php echo $gst_id;?>" >
									
									<select class="form-control itemGST_e<?= $srno;?>" name="itemgst_id" id="itemgst" readonly >
									<option value=""> Select </option>
									<?php 
									$sql = "select * from gst_mst where 1 order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'];?>" <?php echo ($gst_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['gst_name'].'-'.$r22['igst'];?></option>
										<?php } ?>
								</select>
								
									
                                </div>
                        <?php 
							$readonlyr = '';
							if ($status=='Completed'){
								$readonlyr = 'READONLY';
							}	
						?>
								<!--<div class="col-sm-3">-->
								<!--	<label for="gst" class="control-label ">TDS%</label>-->
								<!--	<input type="hidden" class="form-control itemTDS_id1" name="tds_id" id="tds_id"  <?php echo $readonlyr; ?> value="<?php echo $tds_id;?>" >-->
									
								<!--	<select class="form-control itemTDS_e<?= $srno;?>" name="itemtds_id" id="itemtds_id" >-->
								<!--	<option value=""> Select </option>-->
									<?php 
								// 	$sql = "SELECT * FROM `account_mst` where tds_flag = 'Y' and account_type = 'D' " . $sqlm ;
								// 	$q22 	= mysqli_query($con, $sql);
								// 	while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<!--<option value="<?php echo $r22['id'];?>" <?php echo ($tds_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'].'-'.$r22['percentage'];?></option>-->
										<?php //} ?>
								<!--</select>-->
								
									
								
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total</label>
								
									<input type="text" class="form-control" id="itemAmount_e<?= $srno;?>" name="itemamount" <?php echo $readonly; ?> style="text-align:right;" readonly value="<?php echo $amount;?>">
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
                                        <input type="text" class="form-control" name='deliverydate' id="deliveryDate" value ="<?= $delivery_date; ?>" <?php echo $readonly; ?>>
									</div>
								</div>
							<?php
								$ermsg =='';
								if($rate==0){
									$ermsg = 'Rate should not be zero...';
								}
							?>	
								<div class="col-sm-6">
									<span class= 'budget_errmsg' style="color:red;"></span>
									<span class="getbudgeterror" style="color:red;"><?= $ermsg;?></span>
								</div>
							</div>
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()">Close</button>
			<?php
			if (empty($readonly) || TRIM($role)=='F&A Executive' || $status != 'Completed' ){ ?>
                <input type="submit" class="btn btn-primary editItemSave<?= $srno;?>" id="editItem12345" name="editSave" value="Save" >
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

   function calculateTotalAmounte(srno){
		var qty1='';
		var rate1='';
		var amt1='';
		var gst1='';
//alert('Hello');		
		
		//var srno = <?= $srno_u;?>; 
		var qty1		= parseInt($(".itemQuantity_e"+srno).val());
		var rate1		= parseInt($(".itemRate_e"+srno).val());
		var itemRate_e_prev = parseInt($(".itemRate_e_prev"+srno).val());
		var gst1		= parseInt($(".itemGST_e"+srno).val());
		var balance_budget		= parseInt($(".balance_budget_AB"+srno).val());
//alert(balance_budget);		
		var budget_control_gst = parseInt($(".budget_control_GST"+srno).val());
		if(gst1==1){
			gst1 = 18;	
		}	
		else if(gst1==2){
			gst1 = 5;	
		}
		if(gst1==3){
			gst1 = 12;	
		}
		if(gst1==1){
			gst1 = 28;	
		}
//alert(balance_budget + ' ' + qty1 + ' <> ' + rate1 + ' <> ' +  amt1 + ' ' + gst1);
        var amt1 = qty1 * rate1;
		
		var amt1_prev = qty1 * itemRate_e_prev;
		if(budget_control_gst=='Y'){
			var amt1 = amt1 + (amt1 * gst1 /100);
			var amt1_prev = amt1_prev + (amt1_prev * gst1 /100);
		}
		
		/* if(amt1_prev>0){
			balance_budget = balance_budget + amt1_prev;
		} */
//alert(balance_budget + ' ' + qty1 + ' <> ' + rate1 + ' <> ' +  amt1 + ' ' + gst1);

		$('.budget_errmsg').html('');
		$('.editItemSave'+srno).show();
		
        balance_budget = parseFloat(balance_budget) ;
		amt1 		   = parseFloat(amt1) ;
		amt_v = amt1.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount_e'+srno).val(amt_v);

//alert(amt1 + '>' + balance_budget);
		
		if(amt1 > balance_budget){
			// alert("Budget goes into negative balance ... Please confirm...");
			 errmsg = "Insufficient AOP Budget ...!";
			 $('.budget_errmsg').html(errmsg);
			 $('.editItemSave'+srno).hide();
			 return false;
		}
		
    }


 	function getvaluecheck(srno){
		
		//var srno = <?= $srno_u;?>;
		//var srno		= parseInt($(".srno_u").val());
		
		var ap_value_a		= parseInt($("#ap_value_a"+srno).val());
		var ap_quantity		= parseInt($("#ap_quantity"+srno).val());
		var po_value_a		= parseInt($("#po_value_a"+srno).val());
		var po_quantity		= parseInt($("#po_quantity"+srno).val());
		
//alert(srno);
		var itemRate_e		= parseFloat($(".itemRate_e"+srno).val(),2);
//alert(itemRate_e);		
		var item_quantity	= parseInt($(".itemQuantity_e"+srno).val()) ;//+ po_quantity
		
		$('.editItemSave').show();
		
//alert(itemRate_e+ ' <> ' +item_quantity + ' <<>>' + ap_value_a +' <#> ' + ap_quantity +' <#> '+ po_value_a +' <#> ' + po_quantity);
		var err = '';
		$('.getbudgeterror').html(err);
		/* if(item_quantity==0 || item_quantity==''){
			var err = 'Quantity should not be zero....';
			$('.getbudgeterror').html(err);
			$('.editItemSave'+srno).hide();
			return false;
		} */
//alert(item_quantity + '>'+ ap_quantity);		
		if(item_quantity > ap_quantity){
			//alert('Quantity overflow should not be for Approved quantity...');
			var err = 'PO Qty should not be more than MRN qty...';
			$('.getbudgeterror').html(err);
			$('.editItemSave'+srno).hide();
			return false;
		}
		//if( isNaN(adate))
		if(isNaN(itemRate_e) || itemRate_e==0 || itemRate_e==''){
			var err = 'Rate should not be zero....##1';
			$('.getbudgeterror').html(err);
			$('.editItemSave'+srno).hide();
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


