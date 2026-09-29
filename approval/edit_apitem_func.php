<div class="modal fade" id="modalEditItem<?php echo $rid;?>" role="dialog" aria-labelledby="modalEditItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearfld()" ><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEditItemLabel">Edit Product/Services to Approval Note </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
						<?php
							
							$srno = $rid;
							
							$sql  = "SELECT * from sma_approval_items where id = '$srno' ";
					//echo $sql;		
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$approval_hdr_id 	= $r1['approval_hdr_id'];
							$supplier_id		= $r1['supplier_id'];
							$qty 				= $r1['quantity'];
							$rate 				= $r1['unit_rate'];
							$gst				= $r1['gst'];
							$amount 			= $qty * $rate + (($qty * $rate) * $gst / 100);
							
							$unit				= $r1['uom'];
							$delivery_date		= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$product_desc		= $r1['product_desc'];
							$product_id			= $r1['product_id'];
				
							$categoryid			= $r1['product_category'];
				//echo $product_id . ' <<<>>> ' . $categoryid;			
							$total_budget		= $r1['total_budget'];
							$balance_budget		= $r1['balance_budget'];
							$budget_id			= $r1['budget_id'];
							
							
						//	echo $categoryid;
						
						    $sql  = "SELECT * from sma_product where id = '$product_id' ";
							$res2  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r12 = mysqli_fetch_array($res2);
							$categoryid 	= $r12['product_group'];
							$unit 	        = $r12['uom'];
							
						?>
                        <form class="form-horizontal" action="#" method="POST">
							<input type="hidden" id="rId" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="purchaseId_e" name="approval_hdr_id" value="<?php echo $_GET['id'];?>">

							<input type="hidden" name="app_remo_ref" id="app_remo_ref" value="<?php echo $approval_memo_ref ?>" >
							
							<input type="hidden" name="product_id_p" value="<?= $product_id ?>" >
							<input type="hidden" name="budget_id_p" value="<?= $budget_id ?>" >
						<?php
								$sqla = "";	
								if($status != 'Draft'){
									$sqla = " and id = '$categoryid' ";
								}
						?>	
							
							<div class="form-group">
                                <div class="col-sm-4">
									<label for="itemName" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryID" name="categoryid"  <?php echo $readonly; ?> onchange="getmaterial<?= $srno;?>(this.value)" >
								<?php	if($status == 'Draft'){ ?>
										<option value=""> Select </option>
								<?php } ?>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group where 1 $sqla ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        
                                        <option value="<?php echo $rw['id']?>" <?php echo ($categoryid == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>                            
							
								<div class="col-md-8">
									<label for="itemName" class="control-label">Product Name</label>
									<span id="getmaterial<?= $srno;?>" >
										<select class="form-control" name="product_id" id="itemName_e"  <?php echo $readonly; ?> >
									<?php	if($status == 'Draft'){ ?>
										<option value=""> Select </option>
									<?php } ?>
											<?php
                                    	$sql="SELECT * FROM sma_product where `id` = '$product_id' ";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        
                                        <option value="<?php echo $rw['id']?>" <?php echo ($product_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'] ?></option>
                                        <?php } ?>
										
										</select>
									</span>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemDescription" class="control-label">Description </label>
                                    <textarea rows='02' class="form-control" id="itemDescription_e" name="itemdescription" <?php echo $readonly; ?>  placeholder="Item Description..."><?php echo $product_desc;?></textarea>
                                </div>
                            </div>
							
							<div class="form-group col-md-12">
							    <label for="itemName" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2-123" required name="supplier_id" >
									<option value=""> Select </option>
										<?php $sql = "select a.* from sma_party_mst a, sma_approval_details b where 1 and a.id = b.supplier_name and b.approval_hdr_id = '$approval_hdr_id'  order by a.party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($supplier_id == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                        <!--<div class="well well-sm" >    -->
                            
							<?php
							
								// $b_readonly = '';
									
								// 	$sql = "SELECT * FROM `sma_budget` where id = '$budget_id' ";
								
								// //echo $sql;	
								// 	$bd = mysqli_query($con, $sql);
        //                             echo mysqli_error($con);
        //                             $bdrw = mysqli_fetch_array($bd);
								// 	//$budget_head 		= $bdrw['id'];	
								// 	$account_year 		= $bdrw['account_year'];
								// 	$budget_name  		= $bdrw['budget_name'];
								// 	$budget_head	 	= $bdrw['budget_head'];
								// 	$budget_code	 	= $bdrw['budget_code'];
								// 	$project 			= $bdrw['project'];
								// 	$total_budget		= $bdrw['total_budget'];
								// 	$used_budget		= $bdrw['used_budget'];
								// 	$blocked_budget		= $bdrw['blocked_budget'];
								// 	$adjustment_budget	= $bdrw['adjustment_budget'];
								// 	$balance_budget		= ($total_budget + $adjustment_budget) - ( $used_budget + $blocked_budget );
								// 	$total_budget		= ($total_budget + $adjustment_budget);
								// 	$b_readonly   		= "READONLY";
									
								// 	$sql = "SELECT * from company where comp_id = '$project' ";
								// 	$res = mysqli_query($con, $sql);
								// 	//echo mysqli_error($con);
								// 	$r2 = mysqli_fetch_array($res);
								// 	$project = $r2['comp_name'];
									
								// 	$sql="SELECT * FROM sma_product where id = '$product_id' ";
								// 	$res2 = mysqli_query($con, $sql);
								// 	echo mysqli_error($con);
								// 	$mat = mysqli_fetch_array($res2);
								// 	$product_name = $mat['name'];
								// 	$product_category = $mat['group'];
													
								// 	$sql="SELECT * FROM sma_product_group where id = '$product_category' ";
								// 	$res2 = mysqli_query($con, $sql);
								// 	$cat = mysqli_fetch_array($res2);
								// 	//$category = $cat['description'];
													
								// 	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
								// 	$res = mysqli_query($con, $sql);
								// 	$r2 = mysqli_fetch_array($res);
								// 	$bname = $r2['name'];
									
								// 	$sql = "SELECT * from sma_budget_subgroup where id = '$budget_head' ";
								// 	$res = mysqli_query($con, $sql);
								// 	$r2 = mysqli_fetch_array($res);
								// 	$budget_head_id = $r2['id'];
								// 	$budget_head 	= $r2['budget_head'];
									
								?>
							
								<!--<input type="hidden" class="form-control" name="budget_name" readonly value="<?php echo $budget_name ?>" >-->
								<!--<input type="hidden" class="form-control" name="budget_head" readonly value="<?php echo $budget_head_id ?>" >-->
								<!--<input type="hidden" class="form-control" name="budget_code" readonly value="<?php echo $budget_code ?>" >-->
								<!--<input type="hidden" class="form-control" name="blocked_budget" readonly value="<?php echo $blocked_budget ?>" >-->
											
											
								<!--<div class="form-group">-->
								<!--	<div class="col-sm-2">-->
								<!--	<label for="itemName" class="control-label">Account Year</label>-->
								<!--	<input type="text" class="form-control" id="account_year" name="account_year" value = " <?=$account_year; ?>" readonly >-->
								<!--	</div>-->
								<!--</div>-->
								
								<!--<div class="form-group">-->
								<!--	<div class="col-sm-2">-->
								<!--		<label class="control-label">Budget Group</label>-->
								<!--		<input type="text" class="form-control" id="a3" readonly value="<?php echo $bname ?>" >-->
								<!--	</div>-->
								
									
								<!--	<div class="col-sm-4">-->
								<!--		<label class="control-label">Budget Sub Group Head</label>-->
								<!--		<input type="text" class="form-control" id="a4" readonly value="<?php echo $budget_head ?>" >-->
								<!--	</div>-->
								
								<!--	<div class="col-sm-2">-->
								<!--		<label class="control-label">Total Budget </label>-->
								<!--		<input type="text" class="form-control" id="total_budget_ab" readonly style="text-align:right;"  value="<?php echo $total_budget ?>" >-->
								<!--	</div>-->
														
								<!--	<div class="col-sm-2">-->
								<!--		<label class="control-label">Balance Budget </label>-->
								<!--		<input type="text" class="form-control" id="balance_budget_ab" name="balance_budget_ab" readonly style="text-align:right;"  value="<?php echo $balance_budget ?>" >-->
								<!--	</div>-->
								<!--</div>-->
								
						<!--</div>-->
						
							
							
							<div class="form-group col-md-12">
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="text" class="form-control" id="itemQuantity_e" name="itemquantity"  style="text-align:right;" <?php echo $readonly; ?>  value="<?php echo $qty;?>" onkeyup="calculateTotalAmounte();">
									
									<input type="hidden" name="itemquantity_p" value="<?= $qty;?>" >
                                </div>
                            
								<div class="col-sm-3">
									<label for="itemUnits" class="control-label">Units</label>
									<span id="getunit2">
										<input type="text" class="form-control" id="itemUnits_e" name="itemunits" readonly value="<?php echo $unit;?>" >
                                	</span>
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate_e" name="itemrate" <?php echo $readonly; ?>  placeholder="0.00" style="text-align:right;" value="<?php echo $rate;?>"  onkeyup="calculateTotalAmounte();">
										
										<input type="hidden" name="itemrate_p" value="<?= $rate;?>" >
										
                                    </div>
                                </div>
                            
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST_e" name="itemgst" <?php echo $readonly; ?>  style="text-align:right;" value="<?php echo $gst;?>" onkeyup="calculateTotalAmounte();">
									
									<input type="hidden" name="itemgst_p" value="<?= $gst;?>" >
									
                                </div>
                            
								
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount_e" name="itemamount" <?php echo $readonly; ?> style="text-align:right;"  value="<?php echo $amount;?>">
                                </div>
                            
							</div>
		<?php
		//echo $primary_role. "<BR>";
			if($primary_role=='Journal F&A' || $primary_role == '39'){
				$readonly ='';
			}
		?>	
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
			<?php if (empty($readonly)){ ?>
                <input type="submit" class="btn btn-primary" id="editItem12345" name="editItem"  value="Save" >
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

	function getmaterial123(id){
		var sub    = 'sub33';
		var strURL = "app_func.php";

		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('#getmaterial').html(result);
		});

	}
	
	function getmaterial<?= $srno;?>(id){
		var sub    = 'sub3';
		var strURL = "app_func.php";
	//	var company_id    = document.getElementById("projecT").value;
		
//alert(sub + ' ' + id + ' ' + strURL);
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial'+<?= $srno;?>).html(result);
		});

// 		$.post(strURL,{id:id,company_id:company_id,sub14:sub},function(result){
// 		      $('#getcatbudget').html(result);
// 		});
		
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

alert(sub + ' ' + rid+ ' ' + abc);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#'+abc).html(result);
		});

	}

</script>


