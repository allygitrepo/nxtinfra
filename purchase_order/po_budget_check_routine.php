<?php
	
			$fyr		= date('Y', strtotime($dated));
			$fmth		= date('m', strtotime($dated));
			$fin_year	= '';
			if($fmth>=1 && $fmth<=3){
				$styr = $fyr - 1;
				$fin_year = $styr . '-'. $fyr;
			}
			else {
				$ltyr = $fyr + 1;
				$fin_year = $fyr . '-'. $ltyr;
			}	
			
		$valid_flag = "OKXX  ";	
		$ii = 0;
		$sql = "SELECT * FROM sma_purchase_req WHERE 1 and id = '$approval_memo_ref' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_object($q2);
				$company_id	  		= $r2->company_id;
				
		$sql = "SELECT * FROM sma_purchase_req_items WHERE 1 and quantity > 0 and purchase_req_id = '$approval_memo_ref'";
//echo $sql ."<BR>";
		$qry2  = mysqli_query($con, $sql);	
		while($rw = mysqli_fetch_array($qry2)){
		
			$pr_item_id			= $rw['id'];
			$unit				= $rw['unit'];
			$description		= $rw['description'];
			$quantity			= $rw['quantity'];
			$po_quantity		= $rw['po_quantity'];
			$rate				= $rw['rate'];
			$pr_quantity		= $quantity - $po_quantity;
			$quantity			= $quantity - $po_quantity;
			
			$product_id			= $rw['product_id'];
			$sql = "SELECT * from sma_product where 1 and id = '$product_id' ";
			$res2  = mysqli_query($con, $sql);
			$mat = mysqli_fetch_array($res2);
			$product_name 		= $mat['name'];
			$gst_type	  		= $mat['gst_type'];
			$product_category 	= $mat['group'];
			$category 			= $mat['category'];
			$budget_sub_id 		= $mat['budget_head'];

			if( $category=='S' ){
				$rate		= $quantity;
				$quantity	= 1;				
				$qty = $rate;
			}
			else if( $category=='M' ){
				$a= '';
				$quantity		= $quantity;
				$rate			= 0;
				$qty = $quantity;
			}

// 			$sql="SELECT * FROM sma_product_cost_center where company_id = '$company_id' and product_id = '$product_id' ";
// 				$res2 = mysqli_query($con, $sql);
// 				echo mysqli_error($con);
// 				$cat = mysqli_fetch_array($res2);
// 				$budget_sub_id = $cat['budget_id'];
//echo $sql."<BR>";						
				$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_sub_id' ";
//echo $sql."<BR>";				
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_code 			= $com['budget_code'];
				$budget_head_name		= $com['budget_head'];
				$budget_head		 	= $com['id'];
				$budget_name_id			= $com['budget_name'];
				
				$sql="SELECT * from sma_budget_name where 1 and id = '$budget_name_id' ";
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_name 		= $com['name'];
				
			$sql = "SELECT * FROM sma_budget where project = '$company_id' and budget_name = '$budget_name_id' 
								and budget_head = '$budget_head' and trim(budget_code) = trim('$budget_code') 
								and account_year = '$fin_year'  ";
				$res2 		= mysqli_query($con, $sql);
//echo $sql."<BR>";				
				echo mysqli_error($con);
				$cat 		= mysqli_fetch_array($res2);
				$budget_id   = $cat['id'];
				$budget_name_id		= $cat['budget_name'];
				$budget_head 		= $cat['budget_head'];
				
				$adjustment_budget	= $cat['adjustment_budget'];
				$total_budget		= $cat['total_budget'] + $adjustment_budget;
				$used_budget		= $cat['used_budget'];
				$blocked_budget		= $cat['blocked_budget'];
				$balance_budget		= ( $total_budget ) - ( $used_budget + $blocked_budget );
			
				$berr = "";
				if( $balance_budget<=0 || empty($balance_budget) || $budget_sub_id==0){
					$ii = $ii + 1;
					if($ii==1){

					$errmsg = '<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

					<thead>
						<tr>		
							<th>ERROR</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Budget Name</th>
							<th>Budget Head</th>
							<th>Budget Code</th>
						</tr>
					</thead>
					<tbody>';
				

					}
					$berr = " Budget Not Available for ";
					if($budget_sub_id==0){
						$berr = " Budget Not mapped with product for ";
					}	
					//echo "Error => ".$berr. ' ' . "Product Name : <b>".$product_name . ', Budget Name=>' . $budget_name. ' ' . $budget_head_name. ' ' . $budget_code. "</b>". "<BR>";
					$errmsg .=  "<tr>
								<th>$berr</th>
								<td>$product_name</td>
								<td>$qty</td>
								<td>$budget_name</td>
								<td>$budget_head_name</td>
								<td>$budget_code</td>
							</tr>";
				}
				else {
					$jj = $jj + 1;
					if($jj==1){

					$okmsg = '<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

					<thead>
						<tr>		
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Budget Name</th>
							<th>Budget Head</th>
							<th>Budget Code</th>
							<th>Budget Available</th>
						</tr>
					</thead>
					<tbody>';
				

					}
					
					$okmsg .= "<tr>
								<td>$product_name</td>
								<td>$qty</td>
								<td>$budget_name</td>
								<td>$budget_head_name</td>
								<td>$budget_code</td>
								<td style='text-align:right;'>".round($balance_budget,2)."</td>
							</tr>";
							
				}
				
		}
			if($ii>=1){
				$errmsg .= "</tbody></table></div>";
			}
			else { 				
				$okmsg .= "</tbody></table></div>";
			}

			
		if($ii>0){
			echo $errmsg;
		}
		if($jj>0){
			echo $valid_flag.$okmsg;
		}
				
		
		
?>

		