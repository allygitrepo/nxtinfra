<?php

	include("../dbcon.php");



	if(isset($_POST['sub11'])){
		
		$srno 		= $_POST['id'];
		$module 	= $_POST['module'];
		$trans_id 	= $_POST['trans_id'];

		$sql  = "SELECT * from sma_grn_srn where id = '$srno' ";
?>		
		<div class="form-group">
									<div class="col-sm-12">
										<label for="itemCategory" class="control-label"> Select Materials </label>
										<select class="form-control" id="si_id" name="si_id" onchange="getmatbudget(this.value)"  >
											<option value="">Select</option>
										<?php
											
											/* $sql = "SELECT a.grn_srn_srno, a.product_id, b.name, b.description, a.qty, a.rate FROM `sma_grn_srn_details` a, sma_product b WHERE grn_srn_hdr_id = '$trans_id' and a.product_id = b.id "; //and c.id = '$product_id' */
											$sql = " SELECT a.id as po_dtl, a.purchase_id, a.company_id, a.product_id, a.budget_id, b.name, b.description FROM `sma_po_items` a, sma_product b where a.product_id = b.id and purchase_id = '$trans_id' ";					
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											
										?>
											<option value="<?php echo $rw['po_dtl'];?>" >
											<?php echo 'ID: '.$rw['po_dtl'].' | '.'Material Name: '.$rw['name'].' | '.
											$rw['description']. '| Qty:'.$rw['qty'] ; ?>
											</option>
											
											<?php } ?>
										</select>
									</div>
								</div>

<?php
								
	}

	if(isset($_POST['sub1'])){
							
							$po_dtl		= $_POST['id'];
							$module 	= $_POST['module'];
							$trans_id 	= $_POST['trans_id'];
							
							
							$sql  = "SELECT * from sma_purchase_order where id = '$trans_id' ";
//echo $sql."<BR>";						
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							$po_number 		= $r1['po_number'];
							$company_id     = $r1['project'];
							
							
							$sql  = "SELECT * from sma_po_items where purchase_id = '$trans_id' and id = '$po_dtl' ";
//echo $sql."<BR>";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$qty 	= $r1['quantity'];
							$rate 	= $r1['unit_rate'];
							$gst	= $r1['gst'];
							$amount = $qty * $rate + (($qty * $rate) * $gst / 100);							
							$unit	= $r1['uom'];
							
							$description	= $r1['description'];
							$product_id	    = $r1['product_id'];
							$description 	= $r1['description'];
							$budget_name    = $r1['budget_name'];
							$budget_head    = $r1['budget_head'];
							$budget_id      = $r1['budget_id'];

						?>
					<div id="box">
                          <div class="box-body">
							
							<input type="hidden" id="po_dtl" name="po_dtl" value="<?php echo $po_dtl;?>">
							<input type="hidden" id="purchase_Id_e" name="purchase_id" value="<?php echo $trans_id;?>">
							
							<input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id;?>">
							
								<?php
                                   	$sql="SELECT * FROM sma_product where id = '$product_id' ";
                                    $rs = mysqli_query($con, $sql);
                                    echo mysqli_error($con);
                                    $r3 = mysqli_fetch_array($rs);
									$category_id = $r3['group'];
                                ?>
								
							<div class="form-group">
								<div class="col-sm-4">
									<label for="itemCategory" class="control-label"> Category</label>
                                    <select class="form-control" id="categoryId" READONLY >
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT id, description FROM sma_product_group ORDER BY description ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($category_id == $rw['id'])?'selected="selected"':'';?>><?php echo $rw['description'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								<div class="col-sm-4">
									<label for="material" class="control-label "> Material</label>
									<span id="getmaterial" >
                                    <select class="form-control" name="product_id" disabled id="itemName_e"  >
										<option value="">Select </option>
                                    <?php
                                    	$sql="SELECT id, name FROM sma_product ORDER BY name ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($product_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['name'] ?></option>
                                        <?php } ?>
                                    </select>
									</span>
                                </div>
                            
								    
                                <div class="col-sm-4">
									<label for="itemDescription" class="control-label ">Description</label>
                                	<input type="text" class="form-control" id="itemDescription_e" READONLY name="itemdescription" size="55" placeholder="Item Description..." value="<?php echo $description;?>">
                                </div>
                            </div>
<!--Budget-->
				<?php
					$sql = " SELECT * FROM sma_budget where id = '$budget_id' "; 
//echo $sql;
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$budget_id   	= $r2->id;
					$budget_head_id = $r2->budget_category;
					$budget_name_id = $r2->budget_name;
					$balance_budget = $r2->total_budget - ( $r2->used_budget );
					$total_budget	= $r2->total_budget;
					$used_budget	= $r2->used_budget;
					$blocked_budget	= $r2->blocked_budget;
					
					$sql = "SELECT * FROM sma_budget_category where id = '$budget_head_id' ";

					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$budget_head = $r2->category;
					
					$sql = "SELECT * FROM sma_budget_name where id     = '$budget_name_id' ";		
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$budget_name = $r2->name;

				?>
				
				
				<input type="hidden" name="budget_id_old" value="<?php echo $budget_id ?>" >
				
				<div class="form-group">
					<label class="control-label col-sm-2">Budget Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_name ?>" >
					</div>
					
					<label class="control-label col-sm-2">Budget Head</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-sm-3">
						<label class="control-label">Blocked Budget </label>
						<input type="text" class="form-control" id="blocked_budget_a" readonly style="text-align:right;" value="<?php echo $blocked_budget ?>" >
					</div>
										
					<div class="col-sm-3">
						<label class="control-label">Used Budget </label>
						<input type="text" class="form-control" id="used_budget_a" readonly style="text-align:right;" value="<?php echo $used_budget ?>" >
					</div>
				
					<div class="col-sm-3">
						<label class="control-label">Total Budget </label>
						<input type="text" class="form-control" id="total_budget_a" readonly style="text-align:right;" value="<?php echo $total_budget ?>" >
					</div>
										
					<div class="col-sm-3">
						<label class="control-label">Balance Budget </label>
						<input type="text" class="form-control" id="balance_budget_a" readonly style="text-align:right;" value="<?php echo $balance_budget ?>" >
					</div>
				</div>
<!--Budget -->			
							
				<div class="form-group">
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="hidden" id="itemQuantity_ep" name="itemqty_p" READONLY min="0" value="<?php echo $qty;?>" >
									
                                    <input type="text" class="form-control" id="itemQuantity_e" name="itemqty" min="0" style="text-align:right;" value="<?php echo $qty;?>" READONLY >
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
                                	<span id="getunit1" >
                                		<input type="text" class="form-control" id="itemUnits_e" name="itemunits" readonly value="<?php echo $unit ?>" >
									</span>
								</div>
                           
							    <div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate_e" name="itemrate" placeholder="0.00" style="text-align:right;" value="<?php echo $rate;?>" min="0" READONLY >
                                    </div>
                                </div>
                            
								<div class="col-sm-2">
									<label for="itemQuantity" class="control-label">GST%</label>
                                    <input type="text" class="form-control" id="itemGST_e" name="itemgst" min="0" style="text-align:right;" value="<?php echo $gst;?>" READONLY >
                                </div>
								
                                <div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total</label>
                                    <input type="text" class="form-control" id="itemAmount_e" name="itemamount" style="text-align:right;" READONLY value="<?php echo $amount;?>">
                                </div>
								
                            </div>
<!-- NEW BUDGET -->
			<div class="box">
			
			<div class="form-group">
				<label class="control-label col-sm-2">New Budget Name</label>
				<div class="col-sm-4">
					<select class="form-control" id="budget_name_b" name="budget_name_b" >
						<option value="">Select</option>
						<?php
							$sql="SELECT * FROM sma_budget_name ORDER BY name ASC";
							$rs = mysqli_query($con, $sql);
							echo mysqli_error($con);
							while($rw = mysqli_fetch_array($rs)){
						?>
						<option value="<?php echo $rw['id']?>" <?php echo ($budget_name_id == $rw['id'])?'selected="selected"':'';?>><?php echo $rw['name'] ?></option>
						<?php } ?>
					</select>
				</div>
				
				<label class="control-label col-sm-2">New Budget Head</label>		
				<div class="col-sm-4">
					<select class="form-control" id="budget_head_b" onchange="gettotbudget(this.value)" >
					<option value="">Select</option>
                    <?php
                       	$sql = "SELECT * FROM sma_budget_category ORDER BY category ASC";
                        $rs  = mysqli_query($con, $sql);
                        echo mysqli_error($con);
                        while($rw = mysqli_fetch_array($rs)){
                    ?>
                    <option value="<?php echo $rw['id']?>" <?php echo ($budget_head_id == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['category'] ?></option>
                    <?php } ?>
					</select>
				
				</div>
			</div>
			
			<span id="gettotbudget">
				<div class="form-group">
				
					<div class="col-sm-3">
						<label class="control-label">Total Budget </label>
						<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
					</div>
							
					<div class="col-sm-3">
						<label class="control-label">Blocked Budget </label>
						<input type="text" class="form-control" id="blocked_budget_a" style="text-align:right;" readonly value="<?php echo $blocked_budget ?>" >
					</div>
										
					<div class="col-sm-3">
						<label class="control-label">Used Budget </label>
						<input type="text" class="form-control" id="used_budget_a" style="text-align:right;" readonly value="<?php echo $used_budget ?>" >
					</div>
				
								
					<div class="col-sm-3">
						<label class="control-label">Balance Budget abc</label>
						<input type="text" class="form-control" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo $balance_budget ?>" >
					</div>
				</div>
			</span>
			
		</div>
		
<!-- NEW Budget-->			
						
						<div class="box-footer">
							<div class="col-sm-6">
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                        
				
					</div>
                       
			</div> 
<?php			
}
?>

<?php

   if(isset($_POST['sub2'])){  
 //  alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
        
		$budget_head_id = $_POST['id']; 
		$budget_name_id = $_POST['budget_name_id']; 
		$comp_id 		= $_POST['comp_id'];
		$value = '';
		
		$sql = "SELECT id as budget_id, budget_name as budget_name, budget_category as budget_head, total_budget, 
				used_budget, balance_budget, blocked_budget
				FROM sma_budget c where  budget_category = '$budget_head_id' and budget_name = '$budget_name_id'  and project = '$comp_id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		//$b_head = $r2->category;
		$budget_id   = $r2->budget_id;
		$budget_head_id = $r2->budget_head;
		$budget_name_id = $r2->budget_name;
		$balance_budget = $r2->total_budget - $r2->used_budget;
		$total_budget	= $r2->total_budget;
		$used_budget	= $r2->used_budget;
		$blocked_budget	= $r2->blocked_budget;
		
		$sql = "SELECT * FROM sma_budget_category where id = '$budget_head_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$budget_head = $r2->category;
		
		$sql = "SELECT * FROM sma_budget_name where id = '$budget_name_id' ";		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$budget_name = $r2->name;

?>
		<input type="hidden" id="budget_id_new" name="budget_id_new" value="<?php echo $budget_id ?>" >
		<input type="hidden" id="budget_name" name="budget_name" value="<?php echo $budget_name_id ?>" >
		<input type="hidden" id="budget_head" name="budget_head" value="<?php echo $budget_head_id ?>" >

		
			
			<div class="form-group">
				<div class="col-sm-3">
				<label class="control-label">Total Budget </label>
				<input type="text" class="form-control" id="total_budget_a" style="text-align:right;"  readonly value="<?php echo $total_budget ?>" >
				</div>
				
				<div class="col-sm-3">
				<label class="control-label">Blocked Budget </label>		
				<input type="text" class="form-control" id="blocked_budget_a" style="text-align:right;" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-3">
				<label class="control-label">Used Budget </label> 
				<input type="text" class="form-control" id="used_budget_a" style="text-align:right;"  readonly value="<?php echo $used_budget ?>" >
				</div>
			
				<div class="col-sm-3">
				<label class="control-label">Balance Budget </label>
				<input type="text" class="form-control balance_budget_A " style="text-align:right;"  id="balance_budget_A" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		
		
<?php
        
    }

?>

<script>
	
	function gettotbudget(id){
		
        var sub    = 'sub2';
	
//		alert(sub + ' <<>> ' );
		var budget_name_id 	= document.getElementById('budget_name_b').value;
		var comp_id 		= document.getElementById('company_id').value;
		
//	alert(sub + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id  );	
		var strURL = "bc_func.php";
		$.post(strURL,{id:id,budget_name_id:budget_name_id,comp_id:comp_id,sub2:sub},function(result){
		      $('#gettotbudget').html(result);
		});
		
	}
</script>	
			