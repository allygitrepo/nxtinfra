<?php

$message ='';

	$id				= $ipc_id;
	$tableName		= "sma_ipc";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

//echo $sql;

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$ipc_id		= $row['id']; 
		$sma_po_no	= $row['sma_po_no'];
		$pipc_date	= $row['ipc_date'];
		
		$ipc_date			= date('d-m-Y', strtotime($row['ipc_date']));
		$sma_comp_id		= $row['sma_comp_id'];
		$sma_vendor_id		= $row['sma_vendor_id'];
		$sma_po_no			= $row['sma_po_no'];
		$sma_inv_adv		= $row['sma_inv_adv'];
		$sma_invoice_no		= $row['sma_invoice_no'];
		$sma_po_amount		= $row['sma_po_amount'];
		$sma_invoice_amount			= $row['sma_invoice_amount'];
		$sma_variation_order_amt	= $row['sma_variation_order_amt'];
		$sma_variation_in_price		= $row['sma_variation_in_price'];
		$sma_material_advance		= $row['sma_material_advance'];

		$sma_material_advance_recovery	= $row['sma_material_advance_recovery'];
		$sma_deduct_retention_money		= $row['sma_deduct_retention_money'];
		$sma_release_retention_money	= $row['sma_release_retention_money'];
		$sma_variation_due_to_arbitratioon		= $row['sma_variation_due_to_arbitratioon'];
		$sma_deduction_work_contract_tax		= $row['sma_deduction_work_contract_tax'];
		$sma_deduction_liquidated_damage		= $row['sma_deduction_liquidated_damage'];
		$sma_other_deduction			= $row['sma_other_deduction'];
		$sma_other_deduction_desc		= $row['sma_other_deduction_desc'];

		$sma_amount_withhold			= $row['sma_amount_withhold'];
		$release_withheld_amount		= $row['release_withheld_amount'];
		$other_addition					= $row['other_addition'];
		$other_addition_desc			= $row['other_addition_desc'];
		$other_statutory_deduction		= $row['other_statutory_deduction'];
		$other_statutory_deduction_desc	= $row['other_statutory_deduction_desc'];
		$remarks						= $row['remarks'];
		
		$maker					= $row['draft_by'];
		$maker_date				= date('d-m-Y h:i:sa', strtotime($row['draft_dated']));
//		$checker				= $row['changed_by'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$sma_vendor_id'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$party_name  	 = $dep['party_name'];
		
		$sql 	= "SELECT * FROM sma_purchase_order where id = '$sma_po_no'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$po_number  	 		= $dep['po_number'];
		$budget_head  	 		= $dep['budget_head'];
		
		$sql 	= "SELECT * FROM sma_supplier_invoice where id = '$sma_invoice_no'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$supplier_invoice_no  	 = $dep['supplier_invoice_no'];
		$invoice_date		  	 = date('d-m-Y h:m i', strtotime($dep['invoice_date']));
		
	}
	
	
		$sql 	= "SELECT b.name as budget_name, c.category as budget_head, a.balance_budget as budget_balance, a.total_budget as budget_available FROM sma_budget a, sma_budget_name b, sma_budget_category c where a.id = '$budget_head' and a.budget_name = b.id and a.budget_category = c.id";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$budget_name  	 = $s1['budget_name'];
		$budget_head  	 = $s1['budget_head'];
		//$budget_available= $s1['budget_available'];
		//$budget_balance  = $s1['budget_balance'];
	
//Previous Start	
		$sql 		= "SELECT sma_po_no, sma_comp_id, sum(sma_invoice_amount) as sma_invoice_amount, sum(sma_variation_order_amt) as sma_variation_order_amt, sum(sma_variation_in_price) as sma_variation_in_price, sum(sma_material_advance) as sma_material_advance, sum(sma_material_advance_recovery) as sma_material_advance_recovery, sum(sma_deduct_retention_money)  as sma_deduct_retention_money, sum(sma_release_retention_money) as sma_release_retention_money, sum(sma_variation_due_to_arbitratioon) as sma_variation_due_to_arbitratioon, sum(sma_deduction_work_contract_tax) as sma_deduction_work_contract_tax, sum(sma_deduction_liquidated_damage) as sma_deduction_liquidated_damage, sum(sma_amount_withhold) as sma_amount_withhold, sum(release_withheld_amount)  as release_withheld_amount, sum(sma_other_deduction) as sma_other_deduction, sum(sma_other_deduction_desc) as sma_other_deduction_desc, sum(other_addition) as other_addition, sum(other_statutory_deduction) as other_statutory_deduction
		FROM $tableName where sma_po_no = '$sma_po_no' and ipc_date <= '$pipc_date' and id != '$ipc_id' and del !='Y' group by sma_po_no";

//echo $sql;
//exit();

		$presult 	= mysqli_query($con,$sql);
		$error  	= mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$prow = mysqli_fetch_array($presult);
		
		$psma_invoice_amount			= $prow['sma_invoice_amount'];
		$psma_variation_order_amt		= $prow['sma_variation_order_amt'];
		$psma_variation_in_price		= $prow['sma_variation_in_price'];
		$psma_material_advance			= $prow['sma_material_advance'];
		$psma_material_advance_recovery	= $prow['sma_material_advance_recovery'];
		$psma_deduct_retention_money	= $prow['sma_deduct_retention_money'];
		$psma_release_retention_money	= $prow['sma_release_retention_money'];
		$psma_variation_due_to_arbitratioon		= $prow['sma_variation_due_to_arbitratioon'];
		$psma_deduction_work_contract_tax		= $prow['sma_deduction_work_contract_tax'];
		$psma_deduction_liquidated_damage		= $prow['sma_deduction_liquidated_damage'];
		$psma_other_deduction			= $prow['sma_other_deduction'];
		$psma_other_deduction_desc		= $prow['sma_other_deduction_desc'];
		$psma_amount_withhold			= $prow['sma_amount_withhold'];
		$prelease_withheld_amount		= $prow['release_withheld_amount'];
		$pother_addition				= $prow['other_addition'];
		$pother_statutory_deduction		= $prow['other_statutory_deduction'];
		
		
//Previous End	
				
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; margin-left:0px; font-size: 13px;' border='1'  >
			<tr><td style='width: 30%;font-size:12px;'> PARTICULARS</td>
				<td style='width: 20%;text-align: left;'>&nbsp;</td>
				<td style='width: 20%;text-align: right;''> CURRENT </td>
				
			</tr></table>";
			
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black;  margin-left:0px; font-size: 12px;' border='1' > ";
	
    if($sma_invoice_amount>0){
		$message .= "<tr><td style='width: 30%;font-size:12px;'>Value of the work executed </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_invoice_amount)." </td>
					
				</tr>";
	}			
    if($sma_invoice_amount>0){			
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Variation orders </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_invoice_amount)." </td>
					
				</tr>";
	}			
    if($sma_invoice_amount>0){		
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Variation in Price (VOP) </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_invoice_amount)." </td>
					
				</tr>";
				
	 $total_value_work_done = $sma_invoice_amount + $sma_variation_order_amt + $sma_variation_in_price;
	 $ptotal_value_work_done = $psma_invoice_amount + $psma_variation_order_amt + $psma_variation_in_price;
	if($total_value_work_done>0){	
	 $message .= "<tr><td style='width: 30%;font-size:12px;'><b>Total Value of Work Done </b> </td>
					<td style='width: 20%;text-align: left;'> <b>Sub Total (A)</b> &nbsp;</td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($total_value_work_done)."</b></td>
					
				</tr>";
	}			
    if($sma_material_advance>0){
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Material Advance </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>".($sma_material_advance) ." </td>
					
				</tr>";
	}			
    if($sma_release_retention_money>0){
	 $message .= "<tr><td style='width: 30%;font-size:12px;'>Release of Retention Money.</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_release_retention_money)."</td>
					
				</tr>";

	}			
    if($release_withheld_amount>0){
    $message .= "<tr><td style='width: 30%;font-size:12px;'>Release of Withheld Amount </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($release_withheld_amount)." </td>
					
				</tr>";
	}			
    if($other_addition>0){
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Other Addition &nbsp $other_addition_desc </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($other_addition)." </td>
					
				</tr>";
	}		
	 $gross_payable = $total_value_work_done + ($sma_material_advance + $sma_release_retention_money + $release_withheld_amount + $other_addition);
	 $pgross_payable = $ptotal_value_work_done + ($psma_material_advance + $psma_release_retention_money + $prelease_withheld_amount + $pother_addition);
				
    if($gross_payable>0){	
	$message .= "<tr><td style='width: 30%;font-size:12px;'><b>Gross Total Payable</b> </td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (B)</b></td>
					<td style='width: 20%;text-align: right;'><b>". moneyFormatIndia($gross_payable)."</b> </td>
					
				</tr>";
	}			
    if($net_advance>0){
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Nett Advance</td>
					<td style='width: 20%;text-align: left;'> </td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($net_advance)." </td>
					
				</tr>";
	}
	
	$var_x =  '';$var_y =  '';
	
	if($sma_deduct_retention_money <0){
		$var_x =  '-';
		$sma_deduct_retention_money_x = $sma_deduct_retention_money * -1;
	}
	else{
		$sma_deduct_retention_money_x = $sma_deduct_retention_money;
	}
	
	if($psma_deduct_retention_money <0){
		$var_y =  '-';
		$psma_deduct_retention_money_x = $psma_deduct_retention_money * -1;
	}
	else {
		$psma_deduct_retention_money_x = $psma_deduct_retention_money;
	}
	
	//echo $var_y. ' ' . $psma_deduct_retention_money ;
	}			
    if($sma_deduct_retention_money_x>0){
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Deduction Retention Money</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".$var_x.moneyFormatIndia($sma_deduct_retention_money_x)." </td>
					
				</tr>";
	}			
	$var_x =  '';$var_y =  '';	
			
    if($sma_amount_withhold>0){	
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Amount Withhold</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_amount_withhold)." </td>
					
				</tr>";	
	}			
    if($sma_variation_due_to_arbitratioon>0){
	 $message .= "<tr><td style='width: 30%;font-size:12px;'>Variation due to Arbitration/Claims or disputes</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_variation_due_to_arbitratioon)." </td>
					</td>
				</tr>";
	}			
    if($sma_deduction_liquidated_damage>0){			
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Bonus/ Liquidated damages</td>
					<td style='width: 20%;text-align: left;'> </td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_deduction_liquidated_damage)." </td>
					
				</tr>";
	}			
    if($sma_other_deduction>0){
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Other Deduction. &nbsp $sma_other_deduction_desc</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_other_deduction)." </td>
					
				</tr>";			
	}			
	 $gross_total_payable = $gross_payable - ($net_advance + $sma_deduct_retention_money + $sma_amount_withhold + $sma_variation_due_to_arbitratioon + $sma_deduction_liquidated_damage + $sma_other_deduction);
	 $pgross_total_payable = $pgross_payable - ($pnet_advance + $psma_deduct_retention_money + $psma_amount_withhold + $psma_variation_due_to_arbitratioon + $psma_deduction_liquidated_damage + $psma_other_deduction);
    			
    if($gross_total_payable>0){
		$message .= "<tr><td style='width: 30%;font-size:12px;'><b>Total Payable</b></td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (C)</b></td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($gross_total_payable)."</b></td>
					
				</tr>";
	}			
    if($sma_deduction_work_contract_tax>0){			
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Deduction Work contract Tax </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_deduction_work_contract_tax)." </td>
					</td>
				</tr>";
	}			
    if($other_statutory_deduction>0){
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Other statutory deduction &nbsp $other_statutory_deduction_desc </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($other_statutory_deduction)." </td>
					
				</tr>";
	}			
    if($nett_value_cert>0){			
	 $nett_value_cert = $gross_total_payable - ($sma_deduction_work_contract_tax + $other_statutory_deduction);
     $pnett_value_cert = $pgross_total_payable - ($psma_deduction_work_contract_tax + $pother_statutory_deduction);
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Nett Value of Certificate</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($nett_value_cert)."</td>
					
				</tr>";  
	}			
    if($deduct_amount_certified_earlier>0){
		$message .= "<tr><td style='width: 30%;font-size:12px;'>Deduct amount certified earlier</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($deduct_amount_certified_earlier)." </td>
					
				</tr>"; 
	}	
	
	 $nett_amount_payable_cert = $nett_value_cert - $deduct_amount_certified_earlier;
	 $pnett_amount_payable_cert = $pnett_value_cert - $pdeduct_amount_certified_earlier;
	if($nett_amount_payable_cert>0){
		$message .= "<tr><td style='width: 30%;font-size:12px;'><b>Nett amount payable in this Certificate</b></td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (D)</b></td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($nett_amount_payable_cert)." </td>
					
				</tr>";
	}		
	$message .= "</table>";
	
//	if(!empty($remarks)){
//	$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
//			<tr><td style='width: 100%;text-align: left;font-size:12px;'>Remarks: $remarks</td></tr></table>";
//	}
	
	//$message .= "<table cellspacing='0' style='width: 95%; text-align: center; font-size: 01pt;'><tr><td style='width: 95%;'> <hr style='height: .5px;'> </td></tr></table>";
	
	$ln  = 2;
	$l   =  $i;
	
	
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
	
//print $message;
//exit();
	
 