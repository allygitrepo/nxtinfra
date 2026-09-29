<?php
			$valid_flag = "OK##";	
			$sql = "SELECT * FROM sma_budget where id = '$budget_id'  ";
				$res2 			= mysqli_query($con, $sql);
				$budget_sub_id 	= mysqli_affected_rows($con);
//echo $sql."<BR>";				
				echo mysqli_error($con);
				$cat 		= mysqli_fetch_array($res2);
				$budget_id   = $cat['id'];
				$budget_name_id		= $cat['budget_name'];
				$budget_head_id 		= $cat['budget_head'];
				$open_budget 		= $cat['total_budget'];
				$used_budget 		= $cat['used_budget'];
				$blocked_budget		= $cat['blocked_budget'];
				$adjustment_budget	= $cat['adjustment_budget'];
				$balance_budget 	= ($open_budget + $adjustment_budget) - ( $blocked_budget + $used_budget); 
				
				$adjustment_budget	= $cat['adjustment_budget'];
				$total_budget		= $cat['total_budget'] + $adjustment_budget;
				$used_budget		= $cat['used_budget'];
				$blocked_budget		= $cat['blocked_budget'];
				$balance_budget		= round( ( $total_budget ) - ( $used_budget + $blocked_budget ),2);
			
				$sql="SELECT * from sma_budget_subgroup where 1 and id = '$budget_head_id' ";
//echo $sql."<BR>";				
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_code 			= $com['budget_code'];
				$budget_head_name		= $com['budget_head'];
				$budget_head		 	= $com['id'];
				$sql="SELECT * from sma_budget_name where 1 and id = '$budget_name_id' ";
				$cqry = mysqli_query($con,$sql);
				echo mysqli_error($con);
				$com = mysqli_fetch_array($cqry);
				$budget_name 		= $com['name'];
				
				$berr = "";
				if( $balance_budget<=0 || empty($balance_budget) || $budget_sub_id==0){
					$ii = $ii + 1;
					if($ii==1){
						echo $valid_flag = 'NO##';
?>
					<div class="box-body">
						<table id="prtable123" class="table table-bordered table-striped">

					<thead>
						<tr>		
							<th>ERROR</th>
							<th>Product Name</th>
							<th>Budget Name</th>
							<th>Budget Head</th>
							<th>Budget Code</th>
						</tr>
					</thead>
					<tbody>
				
<?php			
					}
					$berr = " Budget Not Available for ";
					if($budget_sub_id==0){
						$berr = " Budget Not mapped with product for ";
					}	
					//echo "Error => ".$berr. ' ' . "Product Name : <b>".$product_name . ', Budget Name=>' . $budget_name. ' ' . $budget_head_name. ' ' . $budget_code. "</b>". "<BR>";
					echo "<tr>
								<th>$berr</th>
								<td>$product_name</td>
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
							<th>Budget Name</th>
							<th>Budget Head</th>
							<th>Budget Code</th>
							<th style="text-align:right;">Budget Available</th>
						</tr>
					</thead>
					<tbody>';
				

					}
					
					$okmsg .= "<tr>
								<td>$budget_name</td>
								<td>$budget_head_name</td>
								<td>$budget_code</td>
								<td style='text-align:right;'>$balance_budget</td>
							</tr>";
							
				}
		
		if($ii>=1){
			echo "</tbody></table></div>";
		}
			
		if($ii==0){
		//	echo $valid_flag;
		}
		
		if($jj>0){
			if($balance_budget<=0 || $ii>0){
				$valid_flag = 'NO##';
			}	
			echo $valid_flag.$okmsg;
			//echo $okmsg;
?>			
	
			<input type="hidden" name="balance_budget" value="<?= $balance_budget;?>">  
			<input type="hidden" name="budget_id" value="<?= $budget_id;?>">  
	
<?php
		}
		
?>

		