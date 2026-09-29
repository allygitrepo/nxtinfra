<?php

	include "../dbcon.php";
	include "../baseurl.php";
	
	require '../PHPMailer-master/PHPMailerAutoload.php';
	
ini_set('max_execution_time', 0);

	$prn = $_GET['prn'];
	
	$sql = " SELECT from_date, to_date, short_fy_code FROM `sma_financial_year` where status = 'Y' ";
	$qry = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($qry);
	$finance_from_date = $r2['from_date'];
	$finance_to_date   = $r2['to_date'];
	$short_fy_code     = $r2['short_fy_code'];
			
	$message ='';

	$heada = ' Period from ' . date('d-m-Y', strtotime($finance_from_date)) . ' To '. date('d-m-Y', strtotime($finance_to_date));
	$message .= "<table cellspacing='0' style='width: 95%; border: solid 0px black; text-align: center; margin-left:10px;font-size: 20px;'>
			<tr><td style='width: 95%;' colspan='8' >  Tax Invoice need to book against Advance Paid $heada </td></tr></table>";
			
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: left;  font-size: 10pt;' border='1' >
					<tr>
						<th style='width: 5%;'>PO Srno.</th>
						<th style='width: 10%;'>Company </th>
						<th style='width: 10%;'>Dated</th>
						<th style='width: 10%;text-align:left;'> PO Number</th>
						<th style='width: 10%;text-align:left;'> Vender Name</th>
						<th style='width: 10%;text-align:right;'> Total Value</th>
						<th style='width: 10%;text-align:right;'> Paid Amount</th>
					</tr>";
//		<th style='width: 10%;text-align:right;'> Balance Amount</th>
		
	$heading = $message;
	
	$sqlb = " ";
	//$sqlb = " and id = 987 ";
	$sqla = " AND dated >= '$finance_from_date' AND dated <= '$finance_to_date' ";		
	$sql 	= " SELECT * FROM sma_purchase_order WHERE 1 AND advance_flag = 'Y' AND del !='Y' AND status = 'Completed'  " . $sqla . $sqlb . ' order by draft_by ' ;
//echo $sql. "<BR>";
	
	$resulta = mysqli_query($con,$sql);
    $error  = mysqli_error($con); 
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($resulta)){
		
		$po_id					= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$project 				= $row['project'];
		$po_number				= $row['po_number'];
		$supplier_id			= $row['to_supplier'];
			
		$department  			= $row['department'];
		$subject 				= $row['subject'];
		$terms		 			= $row['terms'];
		$header_text			= $row['header_text'];
		$background 			= $row['background'];
		$scope_of_work 			= $row['scope_of_work'];
		$deviations_from_sop 	= $row['deviations_from_sop'];
		$important_terms_conditions	= $row['important_terms_conditions'];
		$additional_costs		= $row['additional_costs'];
		$against_indent_no		= $row['against_indent_no'];
		$overhead_exp			= $row['overhead_exp'];
		
		$maker					= $row['draft_by'];
		$maker_date				= date('d-m-Y  h:i:s', strtotime($row['draft_dated']));
		
		$paid_amount 			= $row['paid_amount'];
		$paid_against_invoice 	= $row['paid_against_invoice'];
		$tota_paid_amt			= round($paid_amount - $paid_against_invoice,0);
							
		if($paid_amount >0 && $advance_flag=='Y' ){
			$tota_paid_amt			= round($paid_amount,0);
		}
		else if($paid_against_invoice >0 && $advance_flag!='Y' ){
			$tota_paid_amt			= round($paid_against_invoice,0);
		}
		
		
		$sql = "SELECT sum(c.payment_adjusted ) as paid_amount, a.invoice_booked_by FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.our_po_ref_no = '$po_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status = 'Completed' ";
//echo $sql. "<BR>";		
		$res 	= mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($res);
		$paid_against_invoice  	 = $dep['paid_amount'];
		$invoice_booked_by  	 = $dep['invoice_booked_by'];
							
		$tds_amount_v = 0;					
		$sql = "SELECT a.* FROM `sma_supplier_invoice` a, payment_header b, payment_details c where a.our_po_ref_no = '$po_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'S' and b.del !='Y' and a.status = 'Completed' ";
//echo $sql. "<BR>";		
		$res 	= mysqli_query($con,$sql);
		while($dep 	= mysqli_fetch_array($res)){
			$si_id  	 = $dep['id'];
			$sql = "SELECT sum(amount) as tds_amount FROM `tally_journal_entry` where doc_type = 'SI' and doc_no = '$si_id' and account_name like '%TDS%' and effect = 'Cr' ";
			$qry 	= mysqli_query($con,$sql);
			$p2 	= mysqli_fetch_array($qry);
			$tds_amount   = $p2['tds_amount'];
			$tds_amount_v = $tds_amount_v + $tds_amount;
		}
		
		//+ c.tds_amount 
		$sql = "SELECT sum(c.payment_adjusted ) as paid_amount FROM `sma_purchase_order` a, payment_header b, payment_details c where a.id = '$po_id' and a.id = c.supp_id and b.id = c.payment_hdr_id and st_flag = 'D' and b.del !='Y' and a.status = 'Completed' ";
		$res 	= mysqli_query($con,$sql);
		$si_rowaffect = mysqli_affected_rows($con);
		$dep 	= mysqli_fetch_array($res);
		$po_paid_amt  	 = $dep['paid_amount'] - ( $paid_against_invoice + $tds_amount_v) ;
		$tota_paid_amt	 = round($po_paid_amt ,0);
//echo $sql. "<BR>";	
//echo  $dep['paid_amount']. "<BR>";	
//echo $tds_amount_v. ' ' .$po_paid_amt. ' ' . $paid_against_invoice . ' <<>> ' .$tota_paid_amt. "<BR>";
		 if($po_paid_amt <=0  ){//|| $po_paid_amt==$tota_paid_amt
			//echo $po_paid_amt .' >><< ' . $po_id. "<BR>";
			continue;
		}
		
		/* $sql = " UPDATE sma_purchase_order set paid_amount= '$po_paid_amt', paid_against_invoice = '$paid_against_invoice' where id = '$po_id' ";
		mysqli_query($con,$sql);
		echo mysqli_error($con); */
		
		$sql 	= "SELECT * FROM sma_department where id = '$department'";
		$res 	= mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($res);
		$department  	 = $dep['name'];
	
		$sql 	= "SELECT * FROM company where comp_id = '$project'";
		$res 	= mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($res);
		$comp_code  	 = $dep['comp_code'];
		
		$sql 	= "SELECT * FROM sma_party_mst where id = '$supplier_id'";
		$res	= mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($res);
		$party_name  	 = $dep['party_name'];
		
		$sql = " SELECT purchase_id, quantity, unit_rate, gst, sum((quantity * unit_rate) + ((( quantity * unit_rate) *  gst) / 100 ) ) as po_total  
					FROM sma_po_items WHERE purchase_id = '$po_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$po_total = round($r2['po_total'],0);
//echo $sql. "<BR>";							
		$balance_amt = round($po_total - $po_paid_amt,0);
//echo $balance_amt. ' ' . $po_total . ' <<>> ' .$tota_paid_amt. "<BR>";		
		if($balance_amt <=0){
			continue;
		}
		
		if( $maker_prev != $maker && !empty($maker_prev) ){
			$message .= "</table>";
			$sql 	= "SELECT * FROM sma_user where userid = '$maker_prev'";
			$res	= mysqli_query($con,$sql);
			$dep 	= mysqli_fetch_array($res);
			$maker_name  	 = $dep['username'];
			$maker_email  	 = $dep['email'];
			$booked_by_email = '';
			$booked_by_name  = '';
			if(!empty($invoice_booked_by_prev)){
				$sql 	= "SELECT * FROM sma_user where id = '$invoice_booked_by_prev'";
				$res	= mysqli_query($con,$sql);
				$dep 	= mysqli_fetch_array($res);
				$booked_by_name  	 = $dep['username'];
				$booked_by_email  	 = $dep['email'];
			}
			
			include "po_adv_mail.php";
			$message_v .= $message;
			$message = "";
			$message = $heading;
		}
		
		$maker_prev 			= $maker;
		$invoice_booked_by_prev = $invoice_booked_by;
		
		$message .= "<tr>
					<td style='width: 5%;text-align: left;'>". $po_id . " </td>
					<td style='width: 10%;text-align: left;'>". $comp_code . " </td>
					<td style='width: 10%;text-align: left;'>". $dated . " </td>
					<td style='width: 10%;text-align: left;'>" . $po_number . " </td>				
					<td style='width: 10%;text-align: left;'>" . $party_name . " </td>
					<td style='width: 10%;text-align: right;'>" . $po_total . " </td>
					<td style='width: 10%;text-align: right;'>" . $tota_paid_amt . " </td>
					
					</tr> ";
					
	}
	//<td style='width: 10%;text-align: right;'>" . $balance_amt . " </td>
	$message .= "</table>";
				
	$sql 	= "SELECT * FROM sma_user where userid = '$maker_prev'";
	$res	= mysqli_query($con,$sql);
	$dep 	= mysqli_fetch_array($res);
	$maker_name  	 = $dep['username'];
	$maker_email  	 = $dep['email'];
			
//	include "po_adv_mail.php";
	
	$message_v .= $message;
	print $message_v;
//	exit();
	
	echo "<script>window.close();</script>";
	
	//Athaangp2p@athaanginfra.in
	/* 
	if(empty($prn)){
		include "po_adv_mail.php";
		exit('Exit...');
	}
	else 
		 */
	if(!empty($prn)){
    // get the HTML
		ob_start();

		$fl_name = 'po_pending_advance_payment_report'. '.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}
	
function moneyFormatIndiaa($num){
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
				$nums[1] = '0';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
			//$thecash = $thecash;
		}
        
		return $thecash;
    }
}

