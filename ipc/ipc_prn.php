<?php
if($_GET['sub'] == 'pdf'){
	include "../dbcon.php";
	include "../baseurl.php";

//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	//$message="<table><tr><td>Table</td></tr></table>";
	if(empty($_GET['id'])){
		echo "<script>alert('File not found...');window.close();</script>";
		
		return;
	}
	
	$prn		= $_GET['sub'];
	$id			= $_GET['id'];
	$comp_id	= $_GET['comp_id'];	
	$location   = $_GET['location'];
	

	$sql 	= "SELECT * FROM `sma_location` where id = '$location'";	
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$loc_name    = $row['loc_name'];
		$loc_addr1   = $row['loc_addr1'];
		$loc_addr2   = $row['loc_addr2'];
		$loc_addr3   = $row['loc_addr3'];
		$loc_city    = $row['loc_city'];
		$loc_pincode = $row['loc_pincode'];
		$loc_phone   = $row['loc_phone'];
		$loc_mobile  = $row['loc_mobile'];
		$loc_email   = $row['loc_email'];
		$loc_pan_no  = $row['loc_pan_no'];
		$loc_gst_no  = $row['loc_gst_no'];
	}
	
$sql="SELECT * FROM `company` where comp_id = '$comp_id' ";
$comresult 	= mysqli_query($con,$sql);
if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$error  			= mysqli_error($con);
	$com 				= mysqli_fetch_array($comresult);
	
	$comp_name 			= $com['comp_name'];
	$comp_addr1 		= $com['comp_addr1'];
	$comp_addr2 		= $com['comp_addr2'];
	$comp_addr3 		= $com['comp_addr3'];
	$comp_email 		= $com['comp_email'];
	$comp_office 		= $com['comp_office'];
	$comp_mobile 		= $com['comp_mobile'];
	$comp_city  		= $com['comp_city'];
	$comp_pincode 		= $com['comp_pincode'];
	$comp_country 		= $com['comp_country'];
	$comp_faxno 		= $com['comp_faxno'];
	$comp_cin_no 		= $com['comp_cin_no'];
	$comp_pan_no 		= $com['comp_pan_no'];
	
	$message ='';

	$message1 .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;margin-left:10px;margin-top: 10px;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; font-size: 16pt;margin-left:10px;'><tr><td style='width: 95%;'> " . $comp_name."</td></tr></table>";
//	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 10pt;'><tr><td style='width: 95%;'>Location : ". $loc_name."</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center;margin-left:10px; font-size: 10px;'><tr><td style='width: 95%;'>Office Address : ". $comp_addr1.', '.$comp_addr2.', '.$comp_addr3.' '.$comp_city.' Pincode : '.$comp_pincode."</td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 20px;'>
			<tr><td style='width: 95%;'> INTERIM PAYMENT CERTIFICATE</td></tr></table>";
	$message .= "<table cellspacing='0' style='width: 100%; text-align: center; margin-left:20px;font-size: 05pt;'>
			<tr><td style='width: 90%;'> <hr style='height: 1px;'> </td></tr></table>";
$head = $message;

$message ='';
	
$message .= '<page backtop=26mm" backbottom="14mm" backleft="10mm" backright="2mm" pagegroup="new">
    <page_header>
        <table class="page_header" style="width: 103%; text-align: center;font-size: 18pt">
            <tr>
                <td style="width: 103%; text-align: center123;text-align: center;">
                    '.$head.'
                </td>
            </tr>
        </table>
    </page_header>';
	
$message123 .= '<page_footer>
        <table class="page_footer" >
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
</page>';

	$id				= $_GET['id'];
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
		$sma_inv_adv		= $row['sma_inv_adv'];
		
		
			
		$ipc_date			= date('d-m-Y', strtotime($row['ipc_date']));
		$sma_comp_id		= $row['sma_comp_id'];
		$sma_vendor_id		= $row['sma_vendor_id'];
		$sma_po_no			= $row['sma_po_no'];
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
		$status					= $row['status'];
		$maker_date				= date('d-m-Y h:i:sa', strtotime($row['draft_dated']));
//		$checker				= $row['changed_by'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$sma_vendor_id'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$party_name  	 = $dep['party_name'];
		
		$sql 	= "SELECT * FROM sma_purchase_order where id = '$sma_po_no'";
//echo $sql."<BR>";		
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$po_number  	 		= $dep['po_number'];
		$po_rev		  	 		= $dep['po_rev'];
		if($po_rev>0){
			$po_number = $po_number.'-'.$po_rev;
		}
		$budget_head  	 		= $dep['budget_head'];
		$amend_po_no			= $dep['old_po_no'];
		
		$sql 	= "SELECT * FROM sma_supplier_invoice where id = '$sma_invoice_no'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$supplier_invoice_no  	 = $dep['supplier_invoice_no'];
		$invoice_date		  	 = date('d-m-Y h:m i', strtotime($dep['invoice_date']));
		
	}
		if($sma_inv_adv=='P'){
			$sma_po_amount				= '';
			$sma_invoice_amount			= '';
			$sma_variation_order_amt	= '';
			$sma_variation_in_price		= '';
			$sma_material_advance		= '';

			$sma_material_advance_recovery	= '';
			$sma_deduct_retention_money		= '';
			$sma_release_retention_money	= '';
			$sma_variation_due_to_arbitratioon		= '';
			$sma_deduction_work_contract_tax		= '';
			$sma_deduction_liquidated_damage		= '';
			$sma_other_deduction			= '';
			$sma_other_deduction_desc		= '';

			$sma_amount_withhold			= '';
			$release_withheld_amount		= '';
			$other_addition					= '';
			$other_addition_desc			= '';
			$other_statutory_deduction		= '';
			$other_statutory_deduction_desc	= '';
		}
		
		$sql 	= "SELECT b.name as budget_name, c.category as budget_head, a.balance_budget as budget_balance, a.total_budget as budget_available FROM sma_budget a, sma_budget_name b, sma_budget_category c where a.id = '$budget_head' and a.budget_name = b.id and a.budget_category = c.id";
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$budget_name  	 = $s1['budget_name'];
		$budget_head  	 = $s1['budget_head'];
		//$budget_available= $s1['budget_available'];
		//$budget_balance  = $s1['budget_balance'];
		
		$sql 	= "SELECT count(*) as pocnt FROM $tableName where sma_po_no = '$sma_po_no'";

		$res1 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s2 	= mysqli_fetch_array($res1);
		$pocnt  	 = $s2['pocnt'];
//echo $sql. ' '. $pocnt;			
//Previous Start	
		$sql 		= "SELECT sma_po_no, sma_comp_id, sum(sma_invoice_amount) as sma_invoice_amount, sum(sma_variation_order_amt) as sma_variation_order_amt, sum(sma_variation_in_price) as sma_variation_in_price, sum(sma_material_advance) as sma_material_advance, sum(sma_material_advance_recovery) as sma_material_advance_recovery, sum(sma_deduct_retention_money)  as sma_deduct_retention_money, sum(sma_release_retention_money) as sma_release_retention_money, sum(sma_variation_due_to_arbitratioon) as sma_variation_due_to_arbitratioon, sum(sma_deduction_work_contract_tax) as sma_deduction_work_contract_tax, sum(sma_deduction_liquidated_damage) as sma_deduction_liquidated_damage, sum(sma_amount_withhold) as sma_amount_withhold, sum(release_withheld_amount)  as release_withheld_amount, sum(sma_other_deduction) as sma_other_deduction, sum(sma_other_deduction_desc) as sma_other_deduction_desc, sum(other_addition) as other_addition, sum(other_statutory_deduction) as other_statutory_deduction
		FROM $tableName where sma_po_no = '$sma_po_no' and ipc_date <= '$pipc_date' and id != '$ipc_id' and del !='Y' group by sma_po_no";
		
		if( $sma_inv_adv=='P' ){
			$sql = "SELECT sma_po_no, sma_comp_id, sum(sma_invoice_amount) as sma_invoice_amount, sum(sma_variation_order_amt) as sma_variation_order_amt, sum(sma_variation_in_price) as sma_variation_in_price, sum(sma_material_advance) as sma_material_advance, sum(sma_material_advance_recovery) as sma_material_advance_recovery, sum(sma_deduct_retention_money)  as sma_deduct_retention_money, sum(sma_release_retention_money) as sma_release_retention_money, sum(sma_variation_due_to_arbitratioon) as sma_variation_due_to_arbitratioon, sum(sma_deduction_work_contract_tax) as sma_deduction_work_contract_tax, sum(sma_deduction_liquidated_damage) as sma_deduction_liquidated_damage, sum(sma_amount_withhold) as sma_amount_withhold, sum(release_withheld_amount)  as release_withheld_amount, sum(sma_other_deduction) as sma_other_deduction, sum(sma_other_deduction_desc) as sma_other_deduction_desc, sum(other_addition) as other_addition, sum(other_statutory_deduction) as other_statutory_deduction
			FROM $tableName where ( ( sma_po_no = '$sma_po_no' and ipc_date <= '$pipc_date' ) or id = '$ipc_id' ) and del !='Y' group by sma_po_no";
		}
//echo $sql."<BR>";

//echo $amend_po_no. "<BR>";

		if($amend_po_no>0){
			$sql 		= "SELECT sma_po_no, sma_comp_id, sum(sma_invoice_amount) as sma_invoice_amount, sum(sma_variation_order_amt) as sma_variation_order_amt, sum(sma_variation_in_price) as sma_variation_in_price, sum(sma_material_advance) as sma_material_advance, sum(sma_material_advance_recovery) as sma_material_advance_recovery, sum(sma_deduct_retention_money)  as sma_deduct_retention_money, sum(sma_release_retention_money) as sma_release_retention_money, sum(sma_variation_due_to_arbitratioon) as sma_variation_due_to_arbitratioon, sum(sma_deduction_work_contract_tax) as sma_deduction_work_contract_tax, sum(sma_deduction_liquidated_damage) as sma_deduction_liquidated_damage, sum(sma_amount_withhold) as sma_amount_withhold, sum(release_withheld_amount)  as release_withheld_amount, sum(sma_other_deduction) as sma_other_deduction, sum(sma_other_deduction_desc) as sma_other_deduction_desc, sum(other_addition) as other_addition, sum(other_statutory_deduction) as other_statutory_deduction
			FROM $tableName where sma_po_no in ('$amend_po_no', '$sma_po_no') and id < '$ipc_id' and del !='Y'   ";
			
		}
		else 
		if($pocnt>1 && $sma_inv_adv!='P'){
			$sql 		= "SELECT sma_po_no, sma_comp_id, sum(sma_invoice_amount) as sma_invoice_amount, sum(sma_variation_order_amt) as sma_variation_order_amt, sum(sma_variation_in_price) as sma_variation_in_price, sum(sma_material_advance) as sma_material_advance, sum(sma_material_advance_recovery) as sma_material_advance_recovery, sum(sma_deduct_retention_money)  as sma_deduct_retention_money, sum(sma_release_retention_money) as sma_release_retention_money, sum(sma_variation_due_to_arbitratioon) as sma_variation_due_to_arbitratioon, sum(sma_deduction_work_contract_tax) as sma_deduction_work_contract_tax, sum(sma_deduction_liquidated_damage) as sma_deduction_liquidated_damage, sum(sma_amount_withhold) as sma_amount_withhold, sum(release_withheld_amount)  as release_withheld_amount, sum(sma_other_deduction) as sma_other_deduction, sum(sma_other_deduction_desc) as sma_other_deduction_desc, sum(other_addition) as other_addition, sum(other_statutory_deduction) as other_statutory_deduction
			FROM $tableName where sma_po_no = '$sma_po_no' and ( id < '$ipc_id' || ipc_date < '$pipc_date') and del !='Y' ";
		}
		
//echo $sql. " <<>> ";;
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
	if(!empty($ipc_date)){
		$dated = $ipc_date;	
	}
	else {
		$dated = date("d-m-Y");
	}
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 50%;'> IPC Serial No.: $id </th><th style='width: 20%;text-align: left'>Dated : $dated </th></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 95%;'>Name of the Contractor	.: $party_name </th></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 95%;'>W.O./Contract Agreement No. & Date : $po_number </td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 95%;'>W.O./Contract Amount: ".moneyFormatIndia($sma_po_amount)."/- </td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 95%;'>Does this amount in approved budget? if no specify the reason for variation : ". $budget_name. ' ' . $budget_head . "</td></tr></table>";
			
	if($sma_inv_adv=='I'){
		$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 95%;'>Invoice Details : ". $supplier_invoice_no . ' ' . $invoice_date."</td></tr></table>";
	}
						
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black; background: #E7E7E7; margin-left:0px; font-size: 13px;' border='1'  >
			<tr><td style='width: 30%;font-size:12px;'> PARTICULARS</td>
				<td style='width: 20%;text-align: left;'>&nbsp;</td>
				<td style='width: 20%;text-align: right;''> CURRENT </td>
				<td style='width: 15%;text-align: right;'> PREVIOUS</td>
				<td style='width: 15%;text-align: right;'> TOTAL TILL DATE </td>
			</tr></table>";
			
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 1px black;  margin-left:0px; font-size: 12px;' border='1' > ";
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Value of the work executed </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_invoice_amount)." </td>
					<td style='width: 15%;text-align: right;'>". moneyFormatIndia($psma_invoice_amount)."  </td>
					<td style='width: 15%;text-align: right;'> ". moneyFormatIndia($sma_invoice_amount + $psma_invoice_amount)." </td>
				</tr>";
				
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Variation orders </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_variation_order_amt)." </td>
					<td style='width: 15%;text-align: right;'>". moneyFormatIndia($psma_variation_order_amt)." </td>
					<td style='width: 15%;text-align: right;'> ". moneyFormatIndia($sma_variation_order_amt+$psma_variation_order_amt)." </td>
				</tr>";
			
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Variation in Price (VOP) </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_variation_in_price)." </td>
					<td style='width: 15%;text-align: right;'>". moneyFormatIndia($psma_variation_in_price)." </td>
					<td style='width: 15%;text-align: right;'> ". moneyFormatIndia($sma_variation_in_price + $psma_variation_in_price)." </td>
				</tr>";
				
	 $total_value_work_done = $sma_invoice_amount + $sma_variation_order_amt + $sma_variation_in_price;
	 $ptotal_value_work_done = $psma_invoice_amount + $psma_variation_order_amt + $psma_variation_in_price;
	 $message .= "<tr><td style='width: 30%;font-size:12px;'><b>Total Value of Work Done </b> </td>
					<td style='width: 20%;text-align: left;'> <b>Sub Total (A)</b> &nbsp;</td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($total_value_work_done)."</b></td>
					<td style='width: 15%;text-align: right;'> <b>".moneyFormatIndia($ptotal_value_work_done)."</b></td>
					<td style='width: 15%;text-align: right;'>  <b>".moneyFormatIndia($total_value_work_done + $ptotal_value_work_done)."</td>
				</tr>";

//echo $sma_material_advance;;
	
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Material Advance </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>".($sma_material_advance) ." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($psma_material_advance) ." </td>
					<td style='width: 15%;text-align: right;'> ".($sma_material_advance + $psma_material_advance) ."  </td>
				</tr>";

	 $message .= "<tr><td style='width: 30%;font-size:12px;'>Release of Retention Money.</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_release_retention_money)."</td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($psma_release_retention_money)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($sma_release_retention_money+$psma_release_retention_money)." </td>
				</tr>";

    $message .= "<tr><td style='width: 30%;font-size:12px;'>Release of Withheld Amount </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($release_withheld_amount)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($prelease_withheld_amount)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($release_withheld_amount+$prelease_withheld_amount)." </td>
				</tr>";

	$message .= "<tr><td style='width: 30%;font-size:12px;'>Other Addition &nbsp $other_addition_desc </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($other_addition)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($pother_addition)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($other_addition+$pother_addition)." </td>
				</tr>";
			
	 $gross_payable = $total_value_work_done + ($sma_material_advance + $sma_release_retention_money + $release_withheld_amount + $other_addition);
	 $pgross_payable = $ptotal_value_work_done + ($psma_material_advance + $psma_release_retention_money + $prelease_withheld_amount + $pother_addition);
     $message .= "<tr><td style='width: 30%;font-size:12px;'><b>Gross Total Payable</b> </td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (B)</b></td>
					<td style='width: 20%;text-align: right;'><b>". moneyFormatIndia($gross_payable)."</b> </td>
					<td style='width: 15%;text-align: right;'><b>". moneyFormatIndia($pgross_payable)."</b>  </td>
					<td style='width: 15%;text-align: right;'> <b>". moneyFormatIndia($gross_payable + $pgross_payable)."</b> </td>
				</tr>";
	
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Nett Advance</td>
					<td style='width: 20%;text-align: left;'> </td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($net_advance)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($pnet_advance)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($net_advance + $pnet_advance)." </td>
				</tr>";
	
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
	
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Deduction Retention Money</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".$var_x.moneyFormatIndia($sma_deduct_retention_money_x)." </td>
					<td style='width: 15%;text-align: right;'>".$var_y.moneyFormatIndia($psma_deduct_retention_money_x)." </td>
					<td style='width: 15%;text-align: right;'> ".$var_y.moneyFormatIndia($sma_deduct_retention_money + $psma_deduct_retention_money)." </td>
				</tr>";
	$var_x =  '';$var_y =  '';	
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Amount Withhold</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_amount_withhold)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($psma_amount_withhold)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($sma_amount_withhold + $psma_amount_withhold)."   </td>
				</tr>";	
	
	 $message .= "<tr><td style='width: 30%;font-size:12px;'>Variation due to Arbitration/Claims or disputes</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_variation_due_to_arbitratioon)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($psma_variation_due_to_arbitratioon)."</td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($sma_variation_due_to_arbitratioon + $psma_variation_due_to_arbitratioon)." </td>
				</tr>";
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Bonus/ Liquidated damages</td>
					<td style='width: 20%;text-align: left;'> </td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_deduction_liquidated_damage)." </td>
					<td style='width: 15%;text-align: right;'>".moneyFormatIndia($psma_deduction_liquidated_damage)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($sma_deduction_liquidated_damage + $psma_deduction_liquidated_damage)." </td>
				</tr>";
	
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Other Deduction. &nbsp $sma_other_deduction_desc</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($sma_other_deduction)." </td>
					<td style='width: 15%;text-align: right;'> ". moneyFormatIndia($psma_other_deduction)."</td>
					<td style='width: 15%;text-align: right;'>  ". moneyFormatIndia($sma_other_deduction+$psma_other_deduction)." </td>
				</tr>";			
				
	 $gross_total_payable = $gross_payable - ($net_advance + $sma_deduct_retention_money + $sma_amount_withhold + $sma_variation_due_to_arbitratioon + $sma_deduction_liquidated_damage + $sma_other_deduction);
	 $pgross_total_payable = $pgross_payable - ($pnet_advance + $psma_deduct_retention_money + $psma_amount_withhold + $psma_variation_due_to_arbitratioon + $psma_deduction_liquidated_damage + $psma_other_deduction);
     $message .= "<tr><td style='width: 30%;font-size:12px;'><b>Total Payable</b></td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (C)</b></td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($gross_total_payable)."</b></td>
					<td style='width: 15%;text-align: right;'><b>".moneyFormatIndia($pgross_total_payable)." </td>
					<td style='width: 15%;text-align: right;'> <b>".moneyFormatIndia($gross_total_payable+$pgross_total_payable)."</b> </td>
				</tr>";
				
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Deduction Work contract Tax </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($sma_deduction_work_contract_tax)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($psma_deduction_work_contract_tax)."</td>
					<td style='width: 15%;text-align: right;'>  ".moneyFormatIndia($sma_deduction_work_contract_tax + $psma_deduction_work_contract_tax)." </td>
				</tr>";
	
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Other statutory deduction &nbsp $other_statutory_deduction_desc </td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'>". moneyFormatIndia($other_statutory_deduction)." </td>
					<td style='width: 15%;text-align: right;'>". moneyFormatIndia($pother_statutory_deduction)." </td>
					<td style='width: 15%;text-align: right;'> ". moneyFormatIndia($other_statutory_deduction+$pother_statutory_deduction)."  </td>
				</tr>";
				
	 $nett_value_cert = $gross_total_payable - ($sma_deduction_work_contract_tax + $other_statutory_deduction);
     $pnett_value_cert = $pgross_total_payable - ($psma_deduction_work_contract_tax + $pother_statutory_deduction);
     $message .= "<tr><td style='width: 30%;font-size:12px;'>Nett Value of Certificate</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($nett_value_cert)."</td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($pnett_value_cert)."</td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($nett_value_cert+$pnett_value_cert)."</td>
				</tr>";  
    
	$message .= "<tr><td style='width: 30%;font-size:12px;'>Deduct amount certified earlier</td>
					<td style='width: 20%;text-align: left;'>&nbsp;</td>
					<td style='width: 20%;text-align: right;'> ".moneyFormatIndia($deduct_amount_certified_earlier)." </td>
					<td style='width: 15%;text-align: right;'>  ".moneyFormatIndia($pdeduct_amount_certified_earlier)." </td>
					<td style='width: 15%;text-align: right;'> ".moneyFormatIndia($deduct_amount_certified_earlier + $pdeduct_amount_certified_earlier)."</td>
				</tr>";  
	 
	 $nett_amount_payable_cert = $nett_value_cert - $deduct_amount_certified_earlier;
	 $pnett_amount_payable_cert = $pnett_value_cert - $pdeduct_amount_certified_earlier;
     $message .= "<tr><td style='width: 30%;font-size:12px;'><b>Net amount payable in this Certificate</b></td>
					<td style='width: 20%;text-align: left;'><b>Sub Total (D)</b></td>
					<td style='width: 20%;text-align: right;'><b>".moneyFormatIndia($nett_amount_payable_cert)." </td>
					<td style='width: 15%;text-align: right;'> <b>".moneyFormatIndia($pnett_amount_payable_cert)."</td>
					<td style='width: 15%;text-align: right;'> <b>".moneyFormatIndia($nett_amount_payable_cert+$pnett_amount_payable_cert)." </b></td>
				</tr>";
			
	$message .= "</table>";
	
	if(!empty($remarks)){
	$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'>Remarks: $remarks</td></tr></table>";
	}
	
	$message .=  "<h4> Approval Process</h4>";
	$message .= '<table cellspacing="0" style="width: 95%; margin-left:0px; border: solid 1px #000000; ">
				<tr>
                <td style="width: 100%;">';
	$message .= "<table cellspacing='-1' style='width: 100%;  background: #E7E7E7; border: solid 0px black; text-align: center;  font-size: 13px;' >
    		<tr>
				<th style='width: 20%;'>Maker </th>
				<th style='width: 20%;'>Verified By</th>
				<th style='width: 30%;'> Approved By </th>				
			</tr></table>";
	
	$checker 		='';
	$approval 		='';
	$id		= $_GET['id'];
	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'IP' and a.doc_id = '$ipc_id' and b.id = a.create_by order by a.id desc ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			if ($status == 'Submited' || $status == 'Prepared' || $status == 'Verified'){
				if(empty($checker)){
					$checker 		= $bs1['username'];
					$checker_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
				}
			}
			else if ($status == 'Completed' || $status == 'Approved'){
				$approval 		= $bs1['username'];
				$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			}
		
		}
	}
	
	$message .= "<table cellspacing='-1' style='width: 100%; border: solid 0px black; text-align: center; font-size: 10pt;' >
			<tr>
				<td style='width: 20%;'>". $maker . " </td>
				<td style='width: 20%;'>". $checker . " </td>
				<td style='width: 30%;'> " . $approval . " </td>				
			</tr>
			<tr>
				<td style='width: 20%;'>". $maker_date . " </td>
				<td style='width: 20%;'>". $checker_date . " </td>
				<td style='width: 30%;'> " . $approval_date . " </td>				
			</tr></table>";
	$message .= "</td></tr></table>";
	
	
	$message .=  "<h4> Documents</h4>";
	
	$modulePath = "ipc/";
	$baseurl2  = $baseurl.$modulePath;
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM file_uploads where module = 'IP' and reference_id = '$ipc_id'";
	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$file_name = $row['file_name'];
		$file_path = $row['file_path'];
		$doc_type = $row['doc_type'];
		
		$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
		
		$message .= "<table cellspacing='2' style='width: 95%; border: solid 1px black; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'><a href='$baseurl1'>$baseurl1</a></td></tr></table>";
	
	}
	
	//$message .= "<table cellspacing='0' style='width: 95%; text-align: center; font-size: 01pt;'><tr><td style='width: 95%;'> <hr style='height: .5px;'> </td></tr></table>";
	
	$ln  = 2;
	$l   =  $i;
	
	for($l = $l; $l < $ln; $l++){
		$message .= "<table border='0' cellspacing='-1' style='width: 95%; text-align: center;margin-left:0px; font-size: 10pt;' border='0'>
					<tr><td style='width: 6%;text-align: Center;'> &nbsp;</td>
				<td style='width: 24%;text-align: left;'>&nbsp; </td>
				<td style='width: 30%;text-align: center;'> &nbsp; </td>
				<td style='width: 6%;text-align: center;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: right;'> &nbsp; </td>
				<td style='width: 8%;text-align: center;'> &nbsp; </td>
				<td style='width: 10%;text-align: right;'> &nbsp;</td>
			</tr></table>";
	}
	
	for($l = 0; $l < 1; $l++){
	
		$message .= "<table cellspacing='0' ><tr><td> &nbsp;</td></tr></table>";
	
	}
		
	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; margin-left:10px;font-size: 10pt;'>
			<tr><td style='width: 95%;text-align: Center;'>&nbsp; </td></tr></table>";		
	
//	$message .= "<table border='0' cellspacing='10' style='width: 95%; text-align: center; font-size: 10pt;'>
//			<tr><td style='width: 95%;text-align: Center;'>This is a computer-generated document. No signature is required &nbsp; </td></tr></table>";		

//	$message .= "</div>";
	
	
print $message;
exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='excel'){
		$fl_name = 'approval_notes_'.$id. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}	

	
    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'approval_notes_'.$id. '.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
}

function moneyFormatIndia($num){
		
		$msign ='';
		
		$vnum = $num;
		if($vnum <0){
			//echo $vnum;
			$msign = '-';
			
		}
		
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $msign.$thecash.".".$nums[1];
		}
		
        //if($vnum <0){
	//		echo $vnum;
	//		echo $thecash;
	//		$msign = '-';
			
	//	exit();
	//	}
		
		return $thecash;
    }
}