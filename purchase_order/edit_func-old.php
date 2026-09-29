	
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
							
							$sql  = "SELECT * from sma_po_items where id = '$srno' ";
					//echo $sql;		
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$purchase_id 	= $r1['purchase_id'];
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);
							
							$unit	= $r1['uom'];
							$delivery_date	= date('d-m-Y', strtotime($r1['delivery_date']));
							
							$product_desc	= $r1['product_desc'];
							$product_id		= $r1['product_id'];
							$categoryid		= $r1['product_category'];
						//	echo $categoryid;
						?>
                        <form class="form-horizontal" action="poitem_update.php" method="POST">
							<input type="hidden" id="rId" name="rid" value="<?php echo $srno;?>">
							<input type="hidden" id="purchaseId_e" name="purchase_id" value="<?php echo $_GET['id'];?>">

							<input type="hidden" name="app_remo_ref" id="app_remo_ref" value="<?php echo $approval_memo_ref ?>" >
							
							<div class="form-group">
                                <div class="col-sm-4">
									<label for="itemName" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" name="categoryid" onchange="getmaterial(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT id, description FROM sma_product_group ORDER BY description ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        
                                        <option value="<?php echo $rw['id']?>" <?php echo ($categoryid == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['description'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>                            
							
								<div class="col-md-8">
									<label for="itemName" class="control-label">Material Name</label>
									<span id="getmaterial" >
										<select class="form-control" name="product_id" id="itemName_e">
											<option value="">Select</option>
											<?php
                                    	$sql="SELECT * FROM sma_product ";//where `group` = '$categoryid' ";
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
									<label for="itemDescription" class="control-label">Description 123</label>
                                    <textarea rows='03' class="form-control" id="itemDescription_e" name="itemdescription" placeholder="Item Description..."><?php echo $product_desc;?></textarea>
                                </div>
                            </div>
							
							<?php $account_year = $row['account_year']; ?>
							
                        <div class="well well-sm" >    
                            
							<?php
							
								$b_readonly = '';
								if(!empty($approval_memo_ref)){
									
									echo "<center><b>Approval Memo Reference No. : ".$approval_memo_ref. "</b></center>";
									
									$sql = "SELECT * FROM `sma_budget` where id in (SELECT budget_head FROM `sma_approval_memo` where id = '$approval_memo_ref')";
									
									$bd = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    $bdrw = mysqli_fetch_array($bd);
									$budget_head 		= $bdrw['id'];	
									$account_year 		= $bdrw['account_year'];
									$budget_name  		= $bdrw['budget_name'];
									$budget_category 	= $bdrw['budget_category'];
									$project 			= $bdrw['project'];
									$b_readonly   		= "READONLY";
									
									$sql = "SELECT * from company where comp_id = '$project' ";
									$res = mysqli_query($con, $sql);
									//echo mysqli_error($con);
									$r2 = mysqli_fetch_array($res);
									
									$project = $r2['comp_name'];
									
									if ($account_year=='1'){
										$acyr = '2017-2018';
									}
									else if ($account_year=='2'){
										$acyr = '2018-2019';
									} 
									else if ($account_year=='3'){
										$acyr = '2019-2020';
									} 
									else if ($account_year=='4'){
										$acyr = '2020-2021';
									}
									else if ($account_year=='5'){
										$acyr = '2021-2022';
									}	
									else if ($account_year=='6'){
										$acyr = '2022-2023';
									}	
									else if ($account_year=='7'){
										$acyr = '2023-2024';
									}	
									else if ($account_year=='8'){
										$acyr = '2024-2025';
									}	
									else if ($account_year=='9'){
										$acyr = '2025-2026';
									}	
									else if ($account_year=='10'){
										$acyr = '2026-2027';
									}
									
									$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									
									$category = $r2['category'];
									
									$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
									$res = mysqli_query($con, $sql);
									$r2 = mysqli_fetch_array($res);
									
									$bname = $r2['name'];

								?>
							
								<input type="hidden" class="form-control" name="account_year" readonly value="<?php echo $account_year ?>" >
								<input type="hidden" class="form-control" name="budget_name" readonly value="<?php echo $budget_name ?>" >
								<input type="hidden" class="form-control" name="budget_head" readonly value="<?php echo $budget_category ?>" >
							
								<div class="form-group">
									<div class="col-md-4">
										<label class=" control-label">Account Year</label>
										<input type="text" class="form-control" id="a1" readonly value="<?php echo $acyr ?>" >
									</div>
									<div class="col-md-6">
										<label class=" control-label">Company</label>
										<input type="text" class="form-control" id="a2" readonly value="<?php echo $project ?>" >
									</div>
								</div>
											
								<div class="form-group">
									<div class="col-sm-6">
										<label class="control-label">Budget Name</label>
										<input type="text" class="form-control" id="a3" readonly value="<?php echo $bname ?>" >
									</div>
									
									<div class="col-sm-6">
										<label class="control-label">Budget Head</label>
										<input type="text" class="form-control" id="itemDescription" readonly value="<?php echo $category ?>" >
									</div>
								</div>
								
							<?php	
								}
								else {
							?>
							
							<div class="form-group">
								<div class="col-md-4">
									<label class=" control-label">Account Year</label>
									<select class="form-control" name="account_year" id="account_year" onchange="getbudget(this.value)" >
										<option value=""> Select </option>
										<option value="1" <?php echo ($row['account_year'] == '1')?'selected="selected"':'';?> > 2017-2018 </option>
										<option value="2" <?php echo ($row['account_year'] == '2')?'selected="selected"':'';?> > 2018-2019 </option>
										<option value="3" <?php echo ($row['account_year'] == '3')?'selected="selected"':'';?> > 2019-2020 </option>
										<option value="4" <?php echo ($row['account_year'] == '4')?'selected="selected"':'';?> > 2020-2021 </option>
										<option value="5" <?php echo ($row['account_year'] == '5')?'selected="selected"':'';?> > 2021-2022 </option>
										<option value="6" <?php echo ($row['account_year'] == '6')?'selected="selected"':'';?> > 2022-2023 </option>
										<option value="7" <?php echo ($row['account_year'] == '7')?'selected="selected"':'';?> > 2023-2024 </option>
										<option value="8" <?php echo ($row['account_year'] == '8')?'selected="selected"':'';?> > 2024-2025 </option>
										<option value="9" <?php echo ($row['account_year'] == '9')?'selected="selected"':'';?> > 2025-2026 </option>
										<option value="10" <?php echo ($row['account_year'] == '10')?'selected="selected"':'';?> > 2026-2027 </option>									
									</select>	
								</div>
								<?php
									//$budget_head = $row['budget_head'];
								//	$sql 	= "SELECT * FROM `sma_budget_name` where id = '$budget_head' ";
								//	$q2 	= mysqli_query($con, $sql);
								//	$r2 	= mysqli_fetch_array($q2);
								//	$budget_name = $r2['id'];
									//$sql = "SELECT name, id from sma_budget_name where id in (select budget_name FROM `sma_budget` where account_year = '$account_year')";
								?>
								
							</div>
						
							<div class="form-group" >
								<div class="col-sm-6">
									<label class="control-label">Budget Name</label>
									<span id="getbudgetabc">
										<select class="form-control" name="budget_name" id="budget_name" onchange="getbudgetheada(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "SELECT name, id from sma_budget_name where id in (select budget_name FROM `sma_budget` where account_year = '$account_year')";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['name'];?></option>
											<?php } ?>
										</select>
									</span>
								</div>

								<?php
									$cat_id = $row['budget_head'];
									//echo $cat_id . ' ' ;
									//$sql = "select a.id, a.project, a.budget_category, b.category from sma_budget a, sma_budget_category b where b.id = a.budget_category";
								//echo $sql;		    
								?>
								<div class="col-sm-6">
									<label class="control-label">Budget Head</label>
									
									<?php $getbudgetheada = 'getbudgetheada'.$rid;?>
									
									<input type="hidden" id="abc" name = "abc" value="<?php echo $getbudgetheada; ?>">
									
									<span id="<?php echo $getbudgetheada; ?>">
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php //$sql = "SELECT * FROM sma_budget_category ";
											$sql = "select a.id, a.project, a.budget_category, b.category from sma_budget a, sma_budget_category b where b.id = a.budget_category";
										    $q2  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['category'];?></option>
										<?php } ?>
									</select>
									</span>
								</div>
							</div>
							
							<?php 
								} 
							?>
							
						</div>
						
							<div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">Qty.</label>
                                    <input type="text" class="form-control" id="itemQuantity_e" name="itemquantity"  style="text-align:right;" value="<?php echo $qty;?>" onkeyup="calculateTotalAmounte();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemUnits" class="control-label">Units</label>
									<span id="getunit2">
										<input type="text" class="form-control" id="itemUnits_e" name="itemunits" readonly value="<?php echo $unit;?>" >
                                	</span>
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate_e" name="itemrate" placeholder="0.00" style="text-align:right;" value="<?php echo $rate;?>"  onkeyup="calculateTotalAmounte();">
                                    </div>
                                </div>
                            </div>
							
                            <div class="form-group col-md-12">
                                <div class="col-sm-4">
									<label for="itemQuantity" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST_e" name="itemgst"  style="text-align:right;" value="<?php echo $gst;?>" onkeyup="calculateTotalAmounte();">
                                </div>
                            
								<div class="col-sm-4">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount_e" name="itemamount" style="text-align:right;" readonly value="<?php echo $amount;?>">
                                </div>
                            
								<div class="col-sm-4">
									<label class="control-label">Delivery Date</label>
								    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="deliveryDate_e" name="deliverydate" value="<?php echo $delivery_date;?>" >
									</div>
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


