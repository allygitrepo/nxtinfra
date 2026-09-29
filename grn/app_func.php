<?php session_start();
	include('../dbcon.php');
	
	include "../baseurl.php";
	
	$comid  	= $_SESSION['comid'];	
	$userid   			= $_SESSION['usrid'];

	$finance_year = $_SESSION['finance_year'];
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="location" id="location" required="true" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$loc_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = "select * from sma_budget where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$balance_budget = $r2->balance_budget;
		$budget_head    = $r2->budget_category;
        
		
		$sql = "select * from sma_budget_category where id = '$budget_head' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_head    = $r2->category;
        
		$value ='<div class="col-md-3"><label class="control-label">Budget Head</label>';
		$value .= '<input type="text" class="form-control" id="budget_head" name="budget_head" readonly value="'.$budget_head.'" >';
		$value .= "</div>";	
		
		$value .='<div class="col-md-2"><label class="control-label">Budget Available</label>';
		$value .='<input type="text" class="form-control" id="budget_available" name="budget_available" readonly style="text-align:right;" value="'.$balance_budget.'" >';
		$value .= "</div>";	
		
		echo $value;
		
	}

    if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		$vertical_type = $_POST['vertical_type'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
?>		
		<span id="getgrnitem">
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit(this.value)" >
			<option value=""> Select </option>
<?php
			$sql = "SELECT * FROM sma_product where `product_group` = '$id' and vertical_type = '$vertical_type' ORDER BY name ASC";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
				$uom = $r2->uom;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>
		</span>

<?php
//        echo $value;

    }
	

    if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		$vertical_type = $_POST['vertical_type'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
?>		
		<span id="getgrnitem">
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit(this.value)" >
			<option value=""> Select </option>
<?php
			$sql = "SELECT id, name FROM sma_product where `product_group` = '$id' and vertical_type = '$vertical_type' ORDER BY name ASC";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>
		</span>

<?php
//        echo $value;

    }
	
    if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		$vertical_type = $_POST['vertical_type'];
		//$purchase_id = $_POST['purchase_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
				
		//$sql = "SELECT * FROM sma_product where id = '$id' and vertical_type = '$vertical_type' ";
		$sql="SELECT a.*, b.* FROM account_mst a, sma_product b 
							where a.id = b.account_id and b.id = '$id' 
								and a.vertical_type = '$vertical_type' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$uom 		= $r2->uom;
			$gst_type   = $r2->gst_type;
			$tolerance_level = $r2->tolerance_level;
			$po_threashold 	 = $r2->po_threashold;
			$account_name 	 = $r2->account_name;
			//$gst_type 		 = $r2->gst_type;
        
 		$sql = "SELECT * FROM gst_mst where id = '$gst_type' ";
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$igst = $r2->igst; 		
			$igst_id = $r2->id; 
		
		$value = $uom.'##'.$account_name.'##'.$tolerance_level.'##'.$po_threashold .'##'.$igst.'##'.$igst_id;
        echo $value;
		
		exit();
		
    }
	

    if(isset($_POST['sub44'])){
    
        $ida = explode('-',$_POST['id']);
		$comp_id = $_POST['comp_id'];
		$vertical_type = $_POST['vertical_type'];
		
		$purchase_id = $ida['0'];
		$id 	= $ida['1'];
		$product_id = $id; 
		$value = '';
		
		
		$sql = "SELECT * FROM `sma_po_items` where product_id = '$id' and purchase_id = '$purchase_id'  ";
//echo $sql;
//exit();
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$rate = $r2->unit_rate;
			$budget_id = $r2->budget_id;
			$budget_head = $r2->budget_head;
			$budget_name = $r2->budget_name;
			
	 
			$qty  = $r2->quantity - $r2->bal_si_qty;
			$rate = $r2->unit_rate;
		
		/* $sql = "SELECT * FROM sma_budget where id = '$budget_id' "; */
		$sql = "SELECT c.id as budget_id, c.budget_name as budget_name, c.budget_head as budget_head, 
					c.total_budget, c.used_budget, c.balance_budget, c.blocked_budget as blocked_budget
				FROM sma_budget c 
					where id = '$budget_id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		//$b_head = $r2->category;
		$budget_id   = $r2->budget_id;
		$budget_head 	= $r2->budget_head;
		$budget_name_id = $r2->budget_name;
		$balance_budget = $r2->total_budget - $r2->used_budget;
		$total_budget	= $r2->total_budget;
		$used_budget	= $r2->used_budget;
		$blocked_budget	= $r2->blocked_budget;
		$budget_category=	$r2->description;
		
		$balance_budget = $r2->total_budget - ($r2->used_budget + $blocked_budget);
		
		$sql = "SELECT * FROM sma_budget_name where id = '$budget_name_id' ";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$budget_name = $r2->name;
		
		$value .= '<input type="hidden" id="budget_id" name="budget_id" value="'.$budget_id.'" >';
		$value .= '<input type="hidden" id="budget_name" name="budget_name" value="'.$budget_name_id.'" >';
		$value .= '<input type="hidden" id="budget_head" name="budget_head" value="'.$budget_head.'" >';
		
		$sql="SELECT a.* FROM account_mst a, sma_product b 
					where a.id = b.account_id and b.id = '$id' 
					and a.vertical_type = '$vertical_type' ";

        $rs = mysqli_query($con, $sql);
        echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$posting_account_a = $rw['account_name'];
?>
			
			<div class="form-group">
				
				<label class="control-label col-sm-2">Posting&nbsp;Account(DR)</label>
				<div class="col-sm-10">	
					<input type="text" class="form-control" id="posting_account_a" name="posting_account_a"  readonly value="<?= $posting_account_a ?>" >
				</div>
				
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Cost Center Head</label>
				<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_name ?>" >
				
				<!-- <select class="form-control" id="budget_name_b" <?php echo $readonly ?>>
					<option value="">Select</option>
                    <?php
                       	$sql="SELECT * FROM sma_budget_name ORDER BY name ASC";
                        $rs = mysqli_query($con, $sql);
                        echo mysqli_error($con);
                        while($rw = mysqli_fetch_array($rs)){
                    ?>
                    <option value="<?php echo $rw['id']?>" <?php echo ($budget_name_id == $rw['id'])?'selected="selected"':'';?>><?php echo $rw['name'] ?></option>
                    <?php } ?>
                </select> -->
			</div>
									
			<div class="col-sm-6">
				<label class="control-label">Cost Center Name</label>
					<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_head ?>" >
				
				</div>
			</div>
			
			<span id="gettotbudget">
				<!--<div class="form-group">
					<div class="col-sm-6">
						<label class="control-label">Blocked Budget </label>
						<input type="text" class="form-control" id="blocked_budget_a" style="text-align:right;" readonly value="<?php echo $blocked_budget ?>" >
					</div>
										
					<div class="col-sm-6">
						<label class="control-label">Used Budget </label>
						<input type="text" class="form-control" id="used_budget_a" style="text-align:right;" readonly value="<?php echo $used_budget ?>" >
					</div>
				</div>-->
			
				<div class="form-group">
					<div class="col-sm-6">
						<label class="control-label">Total Budget </label>
						<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo number_format($total_budget,2); ?>" >
					</div>
										
					<div class="col-sm-6">
						<label class="control-label">Balance Budget</label>
						<input type="text" class="form-control balance_budget_a" id="balance_budget_a" style="text-align:right;" readonly value="<?php echo number_format($balance_budget,2); ?>" >
					</div>
				</div>
			</span>

<?php		
		
		$value .= '<input type="hidden" id="itemQtyChk" name="itemQtyChk" value="'.$qty.'" >';
		
        $value .= '<div class="col-sm-4">
						<label for="itemQuantity" class="control-label">Qty.</label>';
		$value .= '<input type="text" class="form-control" id="itemQuantity" style="text-align:right;" name="itemquantity" value="'.$qty.'" onkeyup="calculateTotalAmount();" >';
		$value .= '</div><div class="col-sm-4">
						<label for="itemUnits" class="control-label">Units</label>';
								
		$sql = "SELECT * FROM sma_product where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$uom = $r2->uom;
			$gst = $r2->gst;
			$po_threashold = $r2->po_threashold;
			$gst_type = $r2->gst_type;
        
		$sql = "SELECT * FROM gst_mst where id = '$gst_type' ";
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$igst = $r2->igst;
			
		$value .= '<input type="hidden" id="po_Threashold" name="po_threashold" value="'.$po_threashold.'" >';
		
        $value .= '<input type="text" class="form-control" id="itemUnits" name="itemunits" readonly value="'.$uom.'" ></div>';
			
        //unit_rate;
		$value .= '<div class="col-sm-4"><label for="itemRate" class="control-label">Rate</label>
                        <div class="input-group">
                        <span class="input-group-addon">&#8377</span>
                           <input type="text" class="form-control" id="itemRate"  style="text-align:right;"  value="'.$rate.'" >
                        </div>
                    </div>';
		
		//$gst = 18;
		$gst = $igst;
		$total_amount = $qty * $rate + (( $qty * $rate ) * $gst / 100);
		
		$value .= '<div class="form-group col-md-12">
                        <div class="col-sm-4">
							<label for="itemGST" class="control-label">GST%</label>
                            <input type="text" class="form-control" id="itemGST"  style="text-align:right;" value="'.$gst.'" >
                        </div>
                            
						<div class="col-sm-4">
							<label for="itemAmount" class="control-label">Total</label>
                            <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly value="'.$total_amount.'" >
                        </div>
                   </div>';
	
        echo $value;
    }


   if(isset($_POST['sub45'])){  
 //  alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
        
		$budget_head_id = $_POST['id']; 
		$budget_name_id = $_POST['budget_name_id']; 
		$comp_id 		 = $_POST['comp_id'];
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

		$value .= '<input type="hidden" id="budget_id" name="budget_id" value="'.$budget_id.'" >';
		$value .= '<input type="hidden" id="budget_name" name="budget_name" value="'.$budget_name_id.'" >';
		$value .= '<input type="hidden" id="budget_head" name="budget_head" value="'.$budget_head_id.'" >';
?>

			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Blocked Budget </label>
				<input type="text" class="form-control" id="blocked_budget_a" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Used Budget </label>
				<input type="text" class="form-control" id="used_budget_a" readonly value="<?php echo $used_budget ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Total Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Balance Budget  </label>
				<input type="text" class="form-control balance_budget_A " id="balance_budget_A" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>


<?php		
		
		
        
    }


    if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<span id="getmaterial1" ><select class="form-control" name="material_id" id="itemName" required="true" onchange="getunit(this.value)" >
									<option value=""> Select </option>';

	    $sql = "SELECT a.id, b.material_id, a.name FROM sma_product a, sma_grn_srn_details b where b.grn_srn_hdr_id = '$id' and a.id = b.material_id";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$material_id = $r2->material_id;
			$material_name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$material_name."</option>";
        };
		
		$value .= '</select></span>';

//$value=$sql;

        echo $value;
    }

  if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$account_year = $_POST['account_year'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_budget where budget_name = '$id' and account_year = '$account_year' and project = '$company_id' ";
//$value1 = $sql;
		$value ='<select class="form-control" name="budget_head" id="budget_head" required="true" >
									<option value=""> Select </option>';
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
		$id 				= $r2->id;
		$budget_category    = $r2->budget_category;
		
		

			$sql1 = "select * from sma_budget_category where id = '$budget_category' ";
			$q3  = mysqli_query($con, $sql1);
			$r3  = mysqli_fetch_object($q3);
			$budget_head    = $r3->category;
		
            $value .= "<option value='".$id."'>".$id. ' ' .$budget_head."</option>";
        };
		$value .= '</select>';


//$value = $value1;		
		echo $value;
		
	}

	if(isset($_POST['sub66'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$company_id = $_POST['company_id'];
		$account_year = $_POST['account_year'];
		
		$value = '';
	    $sql = "select * from sma_budget where budget_name = '$id' and account_year = '$account_year' and project = '$company_id' ";

//$value1 = $sql;
		$value ='<select class="form-control" name="budget_head" id="budget_head" required="true" >
									<option value=""> Select </option>';
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
		$id 				= $r2->id;
		$budget_category    = $r2->budget_category;
		
			$sql = "select * from sma_budget_category where id = '$budget_category' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_object($q3);
			$budget_head    = $r3->category;
		
            $value .= "<option value='".$id."'>".$budget_head."</option>";
        };
		$value .= '</select>';
//$value = $value1;		
		echo $value;
		
	}
	
    if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget(this.value)" required="true" >
									<option value=""> Select </option>';

	    //$sql = "select b.name, a.* from sma_budget a, sma_budget_name b  where project = '$id' and a.budget_name = b.id ";
		$sql = "SELECT name, id from sma_budget_name where id in (select budget_name FROM `sma_budget` where project = '$id')";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
			$id   = $r2->id;
            
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub77'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" onchange="getbudget1(this.value)" required="true" >
									<option value=""> Select </option>';

	   // $sql = "select b.name, a.* from sma_budget a, sma_budget_name b  where project = '$id' and a.budget_name = b.id ";
		$sql = "SELECT name, id from sma_budget_name where id in (select budget_name FROM `sma_budget` where project = '$id')";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
			$id   = $r2->id;
            
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
	
    if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control"  name="company_id" id="company_ID" onchange="getbudgetname(this.value)" required="true" >
									<option value=""> Select </option>';

	    $sql = "select distinct(b.comp_id), b.comp_name from sma_budget a, company b where account_year = '$id' and a.project = b.comp_id and b.comp_id in ($comid) ";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$comp_name 	= $r2->comp_name;
			$id 		= $r2->comp_id;
            
            $value .= "<option value='".$id."'>".$comp_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub88'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control"  name="company_id" id="company_iD" onchange="getbudgetname1(this.value)" required="true" >
									<option value=""> Select </option>';

	    $sql = "select distinct(b.comp_id), b.comp_name from sma_budget a, company b  where account_year = '$id' and a.project = b.comp_id and b.comp_id in ($comid)";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$comp_name 	= $r2->comp_name;
			$id 		= $r2->comp_id;
            
            $value .= "<option value='".$id."'>".$comp_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub9'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select party_state, b.state_name from sma_party_mst a, states b where a.id = '$id' and b.id = a.party_state";
		$q2  = mysqli_query($con, $sql);
		
		$r2 = mysqli_fetch_object($q2);
			$id = $r2->party_state;
			$state = $r2->state_name;
            
            $value = '<input type="text" class="form-control" id="state" name="state" value="'.$state.'" >';
        

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub10'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_product where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		
		$r2 = mysqli_fetch_object($q2);
			$id = $r2->id;
			$itemunits = $r2->uom;
            
            $value = '<input type="text" class="form-control" id="itemunits" name="itemunits" readonly value="'.$itemunits.'" >';
        
//$value=$sql;

        echo $value;
    }


    if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="approver" id="approverE" >
									<option value=""> Select </option>';

	    $sql = "SELECT * FROM sma_user where department = '$id' ORDER BY username ASC ";
		$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
			//$user_name = $r2->first_name. ' ' . $r2->last_name;
            $id = $r2->id;
			$username = $r2->username;
            $value .= "<option value='".$id."'>".$username."</option>";
        };
		$value .= '</select>';

//$value = $sql;
		
        echo $value;
    }


    if(isset($_POST['sub12'])){
    
        $company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$comp_vertical = $r2['comp_vertical'];
?>
		<select class="form-control select3" name="trans_type" id="trans_type" required >
			<option value=""> Select </option>
			<?php $sql = "select b.* from sma_workflow a, sma_workflow_type b where b.id = a.trans_type and b.doc_type = 'SI' and a.company_id = '$company_id'  ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>
	
<?php
	}

	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$trans_type 		= $_POST['trans_type'];
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = 'SI' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id' 
					and trans_type = '$trans_type' ";
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		$approval_role_4 = $r2['approval_role_4'];
		$approval_role_5 = $r2['approval_role_5'];
		$approval_role_6 = $r2['approval_role_6'];
		$approval_role_7 = $r2['approval_role_7'];
		$approval_role_8 = $r2['approval_role_8'];

		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
		}
		if($approval_role_4>0){
			$row_affected = $row_affected + 1;
			$required4 = 'REQUIRED';
		}
		if($approval_role_5>0){
			$row_affected = $row_affected + 1;
			$required5 = 'REQUIRED';
		}
		if($approval_role_6>0){
			$row_affected = $row_affected + 1;
			$required6 = 'REQUIRED';
		}
		if($approval_role_7>0){
			$row_affected = $row_affected + 1;
			$required7 = 'REQUIRED';
		}
		if($approval_role_8>0){
			$row_affected = $row_affected + 1;
			$required8 = 'REQUIRED';
		}
							
?>    
		<div class="box-footer">
					
					<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
					
								
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_1 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' 
											and '$checker_value' >= from_value and '$checker_value' <= to_value 
													and company_id = '$company_id' 
													and trans_type = '$trans_type'
													and approval_role_1 >0 ), role ) 
												 and FIND_IN_SET('$company_id', company_id )";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										//and id != '$userid'
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}			
							?>
							
									<select class="form-control  approver_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_2 FROM  
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_2 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id )";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										//and id != '$userid' 
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>		
									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_3 FROM  
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_3 >0 ), role ) 
											 and FIND_IN_SET('$company_id', company_id )";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										//and id != '$userid'
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>		
									<select class="form-control  approver_3" name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_4>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_4 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type'
												and approval_role_4 >0 ), role ) 
											 and FIND_IN_SET('$company_id', company_id )";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										//and id != '$userid'
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_4" name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_5 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
											//and id != '$userid' 
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_5" name="approver_5" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_6 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) ";
											//and id != '$userid' 
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_6" name="approver_6" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_7 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
											//and id != '$userid' 
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>	
									<select class="form-control  approver_7" name="approver_7" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT approval_role_8 FROM 
											sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = 'SI' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) ";
											$rs = mysqli_query($con, $sql);
											//and id != '$userid' 
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
										
							?>	
									<select class="form-control  approver_8" name="approver_8" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getsubmit();" >
								</div>
								
						</div>
						<br>
<?php						
	
	}

	if(isset($_POST['sub1a'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Cost Center Name</label>';
		$value .='<select class="form-control" name="budget_id" id="budget_id" required="true" onchange="getcatbudget(this.value);getcatbudgetC(this.value)"  >
			<option value=""> Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id'  ";	//	and project = '$company_id'
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }

    if(isset($_POST['sub14a'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$product_id = $_POST['product_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id 	= $r2->budget_name;
			
			$budget_head 	= $r2->budget_head;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			
			$balance_budget	= $total_budget - ( $blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" name="budget_id" id="budget_ID" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_BUDGET" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		
			<!--<div class="form-group">
							
				<div class="col-md-8">
					<label class=" control-label">Company</label>
					<input type="text" class="form-control" id="project_a" readonly value="<?php echo $project ?>" >
				</div>
			</div>
											
			<div class="form-group">
				<div class="col-sm-6">
					<label class="control-label">Cost Center Group</label>
					<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_name ?>" >
				</div>
				
				<div class="col-sm-6">
					<label class="control-label">Cost Center Name</label>
					<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_head ?>" >
				</div>
				
			</div>-->
			
		<!--	<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Blocked Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Used Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $used_budget ?>" >
				</div>
			</div>
		-->	
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Total Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Balance Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

       // echo $value;
    }


   if(isset($_POST['sub20'])){
    
        $invoice_no = $_POST['id'];
		$suplier_id = $_POST['suplier_id'];
		$company_id	= $_POST['company_id'];
		
		$invoice_len = strlen($invoice_no);
			
	    $sql = "select * from sma_grn_srn where 1 and supplier_invoice_no = '$invoice_no' and supplier_name = '$suplier_id' and company_id = '$company_id' and del !='Y' ";
		$q2  = mysqli_query($con, $sql);
		$affected_row = mysqli_affected_rows($con);
		
		if($invoice_len>16){
			$value = '<div class="col-md-12">';
			$value .= '<label class="control-label" style="color:red;"> Error: Invoice number should not be more then 16 digits... </label></div>';
		}
		else if($affected_row > 0 ){
		    /* $value = '<div class="col-md-12">';
			$value .= '<label class="control-label" style="color:red;"> Error: Dupplicate Invoice Number ... </label></div>' ; */
			echo $affected_row;
		}
		else {
			$value = '';
		}	
		
        echo $value;
		
		exit();
		
    }


   if(isset($_POST['sub21'])){
    
        $id = $_POST['id'];
		$value = '';	
	    $sql = "select * from gst_mst where 1 and id = '$id' ";

		$q2  = mysqli_query($con, $sql);
		//$affected_row = mysqli_affected_rows($con);
		$r3 = mysqli_fetch_array($q2);
		$igst = $r3['igst'];

		echo $value = $igst;
		
		exit();
		
   }	
   
    if(isset($_POST['sub22'])){
		
		$id = $_POST['id'];	
		$sql="SELECT * FROM account_mst where id = '$id' ";
//echo $sql;		
        $rs = mysqli_query($con, $sql);
        echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$percentage  = $rw['percentage'];
			
		echo $percentage;
		
		exit();
		
	}	
   	
	if(isset($_POST['sub23'])){
		
		$our_po_ref_no = $_POST['our_po_ref_no'];	
		$invoice_date  = date('Y-m-d', strtotime($_POST['invdate']));	
		$sql="SELECT * FROM sma_purchase_order where id = '$our_po_ref_no' ";
//echo $sql;
        $rs = mysqli_query($con, $sql);
        echo mysqli_error($con);
        $rw = mysqli_fetch_array($rs);
		$dated  = $rw['dated'];
		
		if($dated > $invoice_date){
			echo '1';
		}
		
		exit();
		
	}	
	
?>


<?php
	if(isset($_POST['sub35'])){
		$modulePath = "grn/";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$si_id		 		= $_POST['si_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page					= $_POST['page']; 		
		$baseurl .= $modulePath.'edit.php?sub=edit&id='.$si_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$si_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_grn_srn where id = '$si_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];				
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$approver_id 			= $r2['id'];
		include("comment_mail.php");
		
		if(!empty($approver_1_status)){
			$approver_id		= $rw['approver_1'];	
			include("comment_mail.php");
		}		
		if(!empty($approver_2_status)){
			$approver_id		= $rw['approver_2'];	
			include("comment_mail.php");
		}
		if(!empty($approver_3_status)){
			$approver_id		= $rw['approver_3'];	
			include("comment_mail.php");
		}
		if(!empty($approver_4_status)){
			$approver_id		= $rw['approver_4'];	
			include("comment_mail.php");
		}
		if(!empty($approver_5_status)){
			$approver_id		= $rw['approver_5'];	
			include("comment_mail.php");
		}
		if(!empty($approver_6_status)){
			$approver_id		= $rw['approver_6'];	
			include("comment_mail.php");
		}
		if(!empty($approver_7_status)){
			$approver_id		= $rw['approver_7'];	
			include("comment_mail.php");
		}
		if(!empty($approver_8_status)){
			$approver_id		= $rw['approver_8'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
		
		exit();
		
} ?>


<script>

	function calculateTotalAmount(){
	
//alert("Hello...");	
		var itemquantity 	= $(itemQuantity).val();
		var itemrate 		= $(itemRate).val();
		var gst				= $(itemGST).val();
	
		if(gst==''){
			gst = 0;
		}
		if(itemrate==''){
			var itemrate =0;
		}	
		
		var  itemamount  = parseInt(itemquantity) * parseInt(itemrate);	
		var gstamt       = itemamount * parseInt(gst) /100;

		
		var  itemamount  = itemamount  + gstamt;
//alert(itemquantity + ' ' + itemrate + ' ' + itemamount);		
		
		//var  itemamount  = parseInt(itemquantity) * parseInt(itemrate);
		
		$(itemAmount).val(itemamount);

	}

</script>
