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

	//$prn		= $_POST['prn'];
	$id			= $_GET['id'];
	$vendor_id	= $_GET['vendor_id'];
	$company_id	= $_GET['company_id'];
	
	$message  = '';
	
	$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
		$comp_name				= $row['comp_name'];
		$comp_addr1				= $row['comp_addr1'];
		$comp_addr2				= $row['comp_addr2'];
		$comp_addr3				= $row['comp_addr3'];
		$comp_city				= $row['comp_city'];
		$comp_pincode			= $row['comp_pincode'];
		$comp_email			    = $row['comp_email'];
	}

	$message .= "<table style='width: 100%;margin-left:10px;'><tr><td style='width: 84%;'></td></tr></table>";
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 24px;margin-left:1px;'><tr><td style='width: 100%;'> $comp_name </td></tr></table>";

	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 14px;margin-left:1px;'><tr><td style='width: 100%;'> $comp_addr1 $comp_addr2 $comp_addr2 $comp_city $comp_pincode </td></tr></table>";
	
	if(!empty($comp_email)){
		$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 14px;margin-left:1px;'><tr><td style='width: 100%;'>E-Mail : $comp_email </td></tr></table>";
	}
	
	$message .= "<br><br>";
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;'> Payment Memo </td></tr></table>";

	$message .= "<br><br>";
	
	
	//$sql 	= "SELECT * from purchase_order where id = '$id' ";
	$sql    = "SELECT * from sma_supplier_invoice where id = '$id' ";
	
//echo $sql;
	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	$row    = mysqli_fetch_array($reshdr);
		$si_id					= $row['id'];
		$supplier_invoice_no	= $row['supplier_invoice_no'];
		$invoice_date			= date('d-m-Y', strtotime($row['invoice_date']));
		$company_id				= $row['company_id'];
		$suplier_name			= $row['suplier_name'];
		$our_po_ref_no			= $row['our_po_ref_no'];
		$tally_ticked_by		= $row['tally_ticked_by'];
		$tally_created_by		= $row['tally_created_by'];
		$tally_created_date		= date('d-m-Y h:i:sa', strtotime($row['tally_created_date']));		
		
		$maker					= $row['draft_by'];
		$status					= $row['status'];
		$maker_date				= date('d-m-Y h:i:sa', strtotime($row['draft_date']));
		
		$sql 	= "SELECT * FROM sma_purchase_order where id = '$our_po_ref_no'";
		$res = mysqli_query($con,$sql);
		$r1 	= mysqli_fetch_array($res);
		$our_po_ref_no_a 	= $r1['po_number'];
		$po_rev				= $r1['po_rev'];
		if($po_rev>0){
			$our_po_ref_no_a 	= $our_po_ref_no_a .'-'.	$po_rev;
		}
		
		$sql 	= " SELECT party_name, party_address_1, party_address_2, party_address_3, party_pincode FROM sma_party_mst where id = '$suplier_name' ";
//echo $sql; //a, `cities` b, states c  //and a.party_city = b.id and a.party_state = c.id 
		$res 	= mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$rs 	= mysqli_fetch_array($res);
		
		$party_name		= $rs['party_name'];
		$party_address	= $rs['party_address_1'].','.$rs['party_address_2'];
		$party_address1	= $rs['party_address_3'];
		
		$city_name 		= $rs['city_name'];
		
		if(!empty($city_name)){
			$party_address1	.= ', '.$rs['city_name'];
		}
		if(!empty($state_name)){
			$party_address1	.= ','.$rs['state_name'];
		}
		if(!empty($party_pincode)){
			$party_address1	.= ','.$rs['party_pincode'];
		}
		
		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr><td style='width: 15%;'>No.</td>
			<td style='width: 15%;'>$id</td>
			<td style='width: 15%;'></td>
			<td style='width: 20%;'></td>
			<td style='width: 20%;'>Invoice Date : </td>
			<td style='width: 20%;'>".$invoice_date."</td>
		</tr></table>";

		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr><td style='width: 15%;'>Invoice No.</td>
			<td style='width: 25%;'>$supplier_invoice_no</td>
			<td style='width: 10%;'></td>
			<td style='width: 20%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr></table>";

		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr><td style='width: 15%;'>Party's Name</td>
			<td style='width: 35%;'>$party_name</td>
			<td style='width: 10%;'></td>
			<td style='width: 10%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 20%;'></td>
		</tr>	
		<tr><td style='width: 15%;'>&nbsp;</td>
			<td style='width: 35%;'>$party_address</td>
			<td style='width: 10%;'></td>
			<td style='width: 20%;'></td>
			<td style='width: 10%;'> </td>
			<td style='width: 10%;'></td>
		</tr>
		
		</table>";
		
		
		$sql    = "SELECT doc_date, account_type, account_id, account_name, amount, effect, narration FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$id' and account_type = 'V' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$rw    = mysqli_fetch_array($res);
		$doc_date			= date('d-m-Y', strtotime($rw['doc_date']));
		
		if(!empty($party_address1)){
		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr>
			<td style='width: 35%;'>$party_address1</td>
		</tr></table>";
		}
		
		$message .= "<table cellspacing='0' border='0' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr>
			<td style='width: 08%;'>PO.No.</td>
			<td style='width: 22%;'>$our_po_ref_no_a</td>
			<td style='width: 35%;'>&nbsp;</td>
			<td style='width: 20%;'>Posting Date : </td>
			<td style='width: 20%;'>".$doc_date."</td>
		</tr></table>";
		
		$message .= "<br>";
		
		$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:1px;'>
		<tr><td style='width: 75%;'><b>Particulars</b></td>
			<td style='width: 12%;text-align:center'><b>Debit</b></td>
			<td style='width: 13%;text-align:center'><b>Credit</b></td>
		</tr>";
		
		$sql    = "SELECT doc_date, account_type, account_id, account_name, amount, effect, narration FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$id' and account_type = 'V' ";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$rw    = mysqli_fetch_array($res);
		$doc_date			= $rw['doc_date'];
			$account_type		= $rw['account_type'];
			$account_id			= $rw['account_id'];
			$account_name		= $rw['account_name'];
			$bank_amount		= $rw['amount'];
			$effect				= $rw['effect'];
			$narration			= $rw['narration'];
			
		$sql    = "SELECT doc_date, account_type, account_id, account_name, amount, effect, narration , budget_head FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$id' order by effect desc , record_id ";
//echo $sql;		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		while($rw    = mysqli_fetch_array($res)){
			
			$doc_date			= $rw['doc_date'];
			$account_type		= $rw['account_type'];
			$account_id			= $rw['account_id'];
			$account_name		= $rw['account_name'];
			$budget_head		= $rw['budget_head'];
			$amount				= $rw['amount'];
			$effect				= $rw['effect'];
			$narration			= $rw['narration'];
			
			$sql = "select * from sma_budget_subgroup where id = '$budget_head'";
			$r2 = mysqli_query($con, $sql);
			$r1 = mysqli_fetch_array($r2);
			$budget_head = $r1['budget_head'];
			
			$bhead = '';
			if($account_type=='A'){
				$bhead = '('.$budget_head.')';
			}
			
			if($effect=='Dr'){
				$message .= "<tr>
				<td style='width: 75%;'>$account_name $bhead</td>
				<td style='width: 12%;text-align:right;'>$amount</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				
				</tr>";
			}	
			else { 
				$message .= "<tr>
				<td style='width: 75%;'>$account_name</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>$amount</td>
				</tr>";
			}

		}
		
		$message .= "<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>
				<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>
				<tr>
				<td style='width: 75%;'>&nbsp;</td>
				<td style='width: 12%;text-align:right;'>&nbsp;</td>
				<td style='width: 13%;text-align:right;'>&nbsp;</td>
				</tr>";
				
		$message .= "</table>";

		
		
		$message .= "<table cellspacing='0' border='.3' style='width: 100%; border: solid 0px black; text-align: left; margin-left:1px;'>
		<tr>
			<td style='width: 75%;'  border='.5'><b>On Account of </b>: $narration </td>
			<td style='width: 12%;'  border='.5'></td>
			<td style='width: 13%;text-align:right;'>&nbsp;</td>
		</tr>
		
		<tr>
			<td style='width: 75%;'  border='.5'>Rs. ". numbertoword($bank_amount) ." Only</td>
			<td style='width: 12%;text-align:right;'  border='.5'>$bank_amount</td>
			<td style='width: 13%;text-align:right;'>$bank_amount</td>
		</tr>
	</table>";
		
		$sql 	= "SELECT * FROM `sma_user` where id = '$tally_created_by' ";
		$bs 	= mysqli_query($con,$sql);
		$bs1 	= mysqli_fetch_array($bs);	
		$tally_created_by 	= $bs1['username'];
			
	$message .= "<br>";
	$approval 		='';
	$id		= $_GET['id'];
		
		
	$sql 	= "SELECT * FROM sma_supplier_invoice where id = '$id'";	
	$rs = mysqli_query($con,$sql);
	$r3 = mysqli_fetch_array($rs);
	$deduction1_id		= $r3['deduction1_id'];
	$deduction2_id		= $r3['deduction2_id'];
	$deduction3_id		= $r3['deduction3_id'];
	$deduction1_amount		= $r3['deduction1_amount'];
	$deduction2_amount		= $r3['deduction2_amount'];
	$deduction3_amount		= $r3['deduction3_amount'];
	$deduction1_remarks		= $r3['deduction1_remarks'];
	$deduction2_remarks		= $r3['deduction2_remarks'];
	$deduction3_remarks		= $r3['deduction3_remarks'];
	$message .=  "<h4> Deduction Details</h4>";	
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 1px black;  margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 05%;text-align: Center;''><b> # </b></td>
				<td style='width: 40%;text-align: left;font-size:12px;'><b> Account Name</b></td>
				<td style='width: 15%;text-align: Center;'><b> Amount </b></td>
				<td style='width: 40%;text-align: left;'><b> Remarks  </b></td>
			</tr>";	
	$i =0 ;		
	if($deduction1_amount !=0){	
		$sql = " SELECT * FROM `account_mst` where id = '$deduction1_id' ";
		$rs = mysqli_query($con,$sql);
		$r3 = mysqli_fetch_array($rs);
		$account_name		= $r3['account_name'];
		$message .= "<tr>
				<td style='width: 05%;text-align: Center;'>".++$i."</td>
				<td style='width: 40%;text-align: left;'>". $account_name . "</td>
				<td style='width: 15%;text-align: right;'>" . ($deduction1_amount) . "</td>
				<td style='width: 40%;text-align: left;'>" . $deduction1_remarks . "</td>
			</tr>";
	}
	if($deduction2_amount !=0){	
		$sql = " SELECT * FROM `account_mst` where id = '$deduction2_id' ";
		$rs = mysqli_query($con,$sql);
		$r3 = mysqli_fetch_array($rs);
		$account_name		= $r3['account_name'];
		$message .= "<tr>
				<td style='width: 05%;text-align: Center;'>".++$i."</td>
				<td style='width: 40%;text-align: left;'>". $account_name . "</td>
				<td style='width: 15%;text-align: right;'>" . ($deduction2_amount) . "</td>
				<td style='width: 40%;text-align: left;'>" . $deduction1_remarks . "</td>
			</tr>";
	}		
	if($deduction3_amount !=0){		
		$sql = " SELECT * FROM `account_mst` where id = '$deduction3_id' ";
		$rs = mysqli_query($con,$sql);
		$r3 = mysqli_fetch_array($rs);
		$account_name		= $r3['account_name'];
		$message .= "<tr>
				<td style='width: 05%;text-align: Center;'>".++$i."</td>
				<td style='width: 40%;text-align: left;'>". $account_name . "</td>
				<td style='width: 15%;text-align: right;'>" . ($deduction3_amount) . "</td>
				<td style='width: 40%;text-align: left;'>" . $deduction1_remarks . "</td>
			</tr>";
	}							
			
	$message .= "</table>";		


	$i =0 ;			
	$message .=  "<h4> Material Details</h4>";
	
	$message .= "<table border='.2' cellspacing='0' style='width: 100%; border: solid 1px black; background: #E7E7E7; margin-left:0px; font-size: 12px;' >
			<tr><td style='width: 10%;text-align: Center;''><b> # </b></td>
				<td style='width: 43%;text-align: Center;font-size:12px;'><b> Material</b></td>
				<td style='width: 12%;'><b> Total Qty  </b></td>
				<td style='width: 12%;text-align: center;'><b> Received Qty  </b></td>
				<td style='width: 11%;text-align: center;'><b> Balance Qty </b></td>
				<td style='width: 12%;text-align: center;'><b> Amount Rs. </b></td>
			</tr></table>";

		
		
	$message .= '<table  cellspacing="0" style="width: 100%; margin-left:0px; border: solid 0px #000000; font-size: 12px;" border="1" >';
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM sma_supplier_invoice_details where si_hdr_id = '$id'";
//echo $sql;	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
						
		$material_id		= $row['material_id'];
		$description		= $row['description'];
		$qty				= $row['qty'];
		$po_qty 			= $row['po_qty'];
		$rate				= $row['rate'];
		$amount				= $row['amount'];
		
		$bal_qty = 0;
		if($po_qty>0){
			$bal_qty = $po_qty - $qty;
		}
		
		$sql 	= "SELECT * FROM sma_product where id = '$material_id'";
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$material_name  	 = $s1['name'];
		$category		  	 = $s1['category'];
		
			
		$sql 	= "SELECT * FROM sma_supplier_invoice where id = '$id'";	
		$rs = mysqli_query($con,$sql);
		$r3 = mysqli_fetch_array($rs);
		$status		= $r3['status'];
					
		$sql 	= "SELECT * FROM `sma_po_items` where purchase_id = '$our_po_ref_no' and product_id = '$material_id' ";
//echo $sql; 
//exit();
		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		$s1 	= mysqli_fetch_array($res);
		$total_po_qty  	 = $s1['quantity'];
		$unit_rate  	 = $s1['unit_rate'];
		$gst  	 		 = $s1['gst'];
		$bal_si_qty  	 = $s1['bal_si_qty'];
		
		$bal_si_amount	 = $s1['bal_si_amount'];
		
		if($bal_si_qty!=0){
			if($status=='Draft'){
				$bal_qty			= round($total_po_qty - $bal_si_qty,3);
			}
			else {
				$bal_qty			= round($total_po_qty - $bal_si_qty ,3);
			}
		}
		
		if($category=='S'){
			$amount_v = ($total_po_qty * $unit_rate);
			$gst_v		= 1 + ($gst /100);
			$bal_qty  = $amount_v - ($bal_si_amount / $gst_v);
		}
		
		$tot_amount 	= $tot_amount + $amount;
		
	    ++$i;
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'>".$i."</td>
				<td style='width: 43%;text-align: left;'>". $material_name . "</td>
				<td style='width: 12%;text-align: right;'>" . round($total_po_qty,4) . "</td>
				<td style='width: 12%;text-align: right;'>".round($qty,4)."</td>
				<td style='width: 11%;text-align: right;'>" . $bal_qty . "</td>
				<td style='width: 12%;text-align: right;'>" . $amount . "</td>
			</tr>";
			
		$iii++;	
	}
	
	if($additional_charges > 0){
		++$i;
		$message .= "<tr>
					<td style='width: 10%;text-align: Center;'>".$i."</td>
					<td style='width: 43%;text-align: left;font-size: 12px;'>" . $additional_remarks . "</td>
					<td style='width: 12%;text-align: right;'></td>
					<td style='width: 12%;text-align: right;'></td>
					<td style='width: 11%;text-align: right;'></td>
					<td style='width: 12%;text-align: right;'>" . $additional_charges . "</td>
				</tr>";
		$tot_amount 	= $tot_amount + $additional_charges;
	}
	
	$message .= "<tr>
				<td style='width: 10%;text-align: Center;'></td>
				<td style='width: 43%;text-align: left;font-size: 12px;'>Grand Total </td>
				<td style='width: 12%;text-align: right;'></td>
				<td style='width: 12%;text-align: right;'></td>
				<td style='width: 11%;text-align: right;'></td>
				<td style='width: 12%;text-align: right;'>" . number_format($tot_amount,2) . "</td>
			</tr>";
			
	$message .= "</table>";
	
			$message .=  "<br>";
	
	$message .=  "<h4> Remarks</h4>";
	
	if(!empty($remarks)){
	$message .= "<table cellspacing='2' style='width: 100%; border: solid 1px black; margin-left:00px; font-size: 12px;' >
			<tr><td style='width: 100%;text-align: left;font-size:12px;'> $remarks</td></tr></table>";
	}

	$message .=  "<h4> Documents</h4>";
	
	$modulePath = "supp_invoice/";
	$baseurl2  = $baseurl.$modulePath;
	
	
	$id		= $_GET['id'];
	$sql 	= "SELECT * FROM file_uploads where module = 'SI' and reference_id = '$id'";
	
	$result = mysqli_query($con,$sql);
    $row_affected  = mysqli_affected_rows($con);
	if($row_affected>0){
		$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black;  font-size: 12px;' border='1' > ";	
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($row = mysqli_fetch_array($result)){
			$file_name 			= $row['file_name'];
			$file_path 			= $row['file_path'];
			$doc_type 			= $row['doc_type'];
			$share_point_link 	= $row['share_point_link'];
	//echo $share_point_link . "<BR>";		
			$baseurl1 = $baseurl2.$file_path.'/'.$file_name;
			if(!empty($share_point_link)){
			
				$message .= "<tr><td style='width: 95%;text-align: left;font-size:10px;'><a href='$share_point_link'>"."$share_point_link"."</a></td></tr>";
			}	
			$iii++;
			/* if($iii>10){
				break;
			} */
		}
		
		$message .= "</table>";
		
	}
	
	$message .=  "<h4> Approval Process</h4>";
 $message .= "<table cellspacing='-1' border='.3' style='width: 100%; border: solid 0px black; text-align: center; font-size: 10pt;' > ";
$message .= "<tr>
					<th style='width: 40%;'>Decision by </th>
					<th style='width: 20%;'>Status </th>
					<th style='width: 40%;'> Date Time </th>				
					</tr> ";			
				
//	if($status!='Draft'){
		$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'SI' and a.doc_id = '$id' and b.id = a.create_by order by a.id ";
		$bs 	= mysqli_query($con,$sql);
		while($bs1 	= mysqli_fetch_array($bs)){
			
			$status 		= $bs1['status'];
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:i:sa', strtotime($bs1['create_date']));
			
			$message .= "<tr>
					<td style='width: 40%;'>". $approval . " </td>
					<td style='width: 20%;'>". $status . " </td>
					<td style='width: 40%;'> " . $approval_date . " </td>				
					</tr> ";			
			
		}
			$message .= "<tr>
					<td style='width: 100%;' colspan='3'>Tally Journal Created by : ". $tally_created_by . ' ' . $tally_created_date." </td>
					</tr> ";
		$message .= "</table>";
//	}
	
		//$message .= "</td></tr></table>"; 

if($iii>10){
//	echo $message;
//	exit();
}	
echo $message;
	exit();
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    if($prn=='Excel'){
		$fl_name = 'vendor_ledger_'.$id. '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
	}
	
    // convert to PDF
//	if($prn=='Pdf'){
	//$baseurl
		require_once('../html2pdf/html2pdf.class.php');
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		//require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name = 'vendor_ledger_'.$id. '.pdf';
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
	//}
}

 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertoword($num){
	   $number = $num;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
	  if(!empty($points)){
			$points = $points . " Paise";
		}
		else{$points='';}
	  //echo $result . "Rupees  " . $points . " Paise";
	  $words=ucwords($result) . " " . $points;
	  return $words;
	}

function moneyFormatIndia($num){
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
			//$thecash = $thecash.".".$nums[1];
			$thecash = $thecash;
		}
        
		return $thecash;
    }
}