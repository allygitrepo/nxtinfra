<?php
//	session_start();
	include "dbcon.php";
	include "baseurl.php";

ini_set('max_execution_time', 0);

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

	$upload_error ='';
	$doc_no_prev ='';
	$message =' Data Updated to Tally';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Company Code</b> </td>
					<td style='width: 10%;'><b>Doc No.</b> </td>
					<td style='width: 10%;'><b>Doc Date</b></td>
					<td style='width: 10%;'><b>Supp.Invocie No.</b></td>
					<td style='width: 10%;'><b>Supp. Invoice Date</b></td>
					<td style='width: 20%;'><b>Account Name</b></td>
					<td style='width: 10%;'><b>Amount</b></td>
				</tr></table>";

	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";

$counter=0;
	
	$sql = " SELECT *
		FROM `tally_journal_entry` 
			WHERE 1 AND status = 'C' and doc_type not in ('RV','PV', 'IN')
			order by doc_type, doc_no, reversal_flag, effect ";
				
			//AND status = 'R'  
//echo $sql; exit();		 
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

//echo $sql; exit();
		
		$amount 					= $row['amount'];
		
		if($amount==0){
			continue;
		}
		
		$doc_date 					= date('d-m-Y', strtotime($row['doc_date']));
		//$doc_date 					= date('d-m-Y', strtotime($row['tally_uploaded_on']));
		
		$today_date					= $doc_date ;
		//$doc_date = '08-07-2020';
		$supp_invoice_date 			= date('d-m-Y', strtotime($row['supp_invoice_date']));
		$paid_date					= date('d-m-Y', strtotime($row['paid_date']));
		
		if($supp_invoice_date == '01-01-1970'){
            $supp_invoice_date = '';
        }
		if($paid_date == '01-01-1970'){
            $paid_date = '';
        }
		
		$today_date 				= date('d-m-Y');
		//$today_date  = '10-07-2020';
		$record_id 					= $row['record_id'];
		$record_type 				= $row['record_type'];
		$doc_type 					= $row['doc_type'];
		$reversal_flag				= $row['reversal_flag'];
		
		$doc_no_upd					= $row['doc_no'];

		$doc_no 					= $doc_type.$row['doc_no'];
		
		if( $doc_type=='PV' && $reversal_flag != 'R' ){
			$doc_type_v = $doc_type.'JV';
			$doc_no 					= $doc_type_v.$row['doc_no'];
			$today_date					= $doc_date ;
		}
		else if( $doc_type=='PV' && $reversal_flag == 'R' ){
			$doc_type_v = $doc_type.'JR';
			$doc_no 					= $doc_type_v.$row['doc_no'];
			$today_date					= $doc_date ;
		}
		
		$supplier_id 				= $row['supplier_id'];
		$supp_invoice_no 			= $row['supp_invoice_no'];
		$account_type				= $row['account_type'];
		$val_type					= $row['val_type'];
		
		if($supp_invoice_no ==','){
			$supp_invoice_no = '';
		}	
		
		if($account_type !='V'){
			$account_type = '';
		}
		
		if($val_type=='V'){
			$account_type ='V';
		}
		
		$account_id					= $row['account_id'];
		$account_name 				= ucwords(strtolower($row['account_name']));
		
		$bank_name 					= $row['bank_name'];
		$effect 					= $row['effect'];
		$amount 					= round($row['amount'],2);
		$narration 					= $row['narration'];
		$cheque_no 					= $row['cheque_no'];
		$address 					= $row['address'];
		$gst_no 					= $row['gst_no'];
		$pan_no 					= $row['pan_no'];
		$mobile_no 					= $row['mobile_no'];
		$state 						= $row['state'];
		$company_id					= $row['company_id'];

		$sql = "SELECT * FROM `company` WHERE comp_id = '$company_id' ";
		$res = mysqli_query($con, $sql);
		$r   = mysqli_fetch_object($res);
		$company_code = $r->comp_code;
		
		$sql = " INSERT INTO tally_all_journal ( record_id, record_type, doc_type, doc_no, doc_date, company_code, supplier_id, supp_invoice_no, supp_invoice_date, paid_date, account_type, account_id, account_name, bank_name, effect, amount, narration, cheque_no, address, gst_no, state, status, pan_no, mobile_no ) 
		values ('$record_id', '$record_type', '$doc_type', '$doc_no', '$today_date', '$company_code', '$supplier_id', '$supp_invoice_no', 
		'$supp_invoice_date', '$paid_date', '$account_type', '$account_id', '$account_name', '$bank_name', '$effect', '$amount', '$narration', 
		'$cheque_no', '$address', '$gst_no', '$state', 'R', '$pan_no', '$mobile_no')";
//echo $sql."<BR>";
		mysqli_query($con,$sql);
		
		
		
		if($doc_no_prev != $doc_no){
			$message .= "<tr><td style='width: 10%;'>$company_code</td>
					<td style='width: 10%;'>$doc_no</td>
					<td style='width: 10%;'>$today_date</td>
					<td style='width: 10%;'>$supp_invoice_no</td>
					<td style='width: 10%;'>$supp_invoice_date</td>
					<td style='width: 20%;'>$account_name</td>
					<td style='width: 10%;'>$amount</td>
				</tr>";
		}
		
		$doc_no_prev = $doc_no;

		 if($doc_type=='PY'){
			$sql = "UPDATE payment_header set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='SI'){
			$sql = "UPDATE sma_supplier_invoice set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='PC'){
			$sql = "UPDATE sma_pettycash set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='RV'){
			$sql = "UPDATE p2p_revenue_hdr set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='PV'){
			$sql = "UPDATE sma_provisional_jv_hdr set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='IN'){
			$sql = "UPDATE sma_income_hdr set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='BT'){
			$sql = "UPDATE budget_adjust_from_to set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else {
			$sql = "UPDATE sma_travel_expenses set tally_status ='U' where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		
		$sql = "UPDATE `tally_journal_entry` set status ='U' , comp_code ='$company_code', tally_uploaded_on = now() where doc_no = '$doc_no_upd' and doc_type = '$doc_type' ";
		mysqli_query($con,$sql); //status = 'R' and 
		$error  = mysqli_error($con); 

		$counter = $counter + 1;
		
	}

	$message .="</table>";
	
	$sql = "UPDATE payment_header set tally_status = 'U' where tally_status ='R' and id in (
		SELECT DISTINCT(replace(doc_no, 'PY', '')) as doc_no FROM `tally_all_journal` where doc_no like '%PY%' and status = 'I' ) ";
	mysqli_query($con,$sql);

	$sql = " UPDATE tally_journal_entry SET status ='U' where status ='R' and doc_type = 'PY' and doc_no in (
		SELECT DISTINCT(replace(doc_no, 'PY', '')) as doc_no FROM `tally_all_journal` where doc_no like '%PY%' and status = 'I' ) ";
	mysqli_query($con,$sql);

	$sql = "UPDATE sma_travel_expenses set tally_status = 'U' where tally_status ='C' and id in (
		SELECT DISTINCT(replace(doc_no, 'CE', '')) as doc_no FROM `tally_all_journal` where doc_no like '%CE%' and status = 'I' ) ";
	mysqli_query($con,$sql);

	$sql = " UPDATE tally_journal_entry SET status ='U' where status ='C' and doc_type = 'PY' and doc_no in (
		SELECT DISTINCT(replace(doc_no, 'CE', '')) as doc_no FROM `tally_all_journal` where doc_no like '%CE%' and status = 'I' ) ";
	mysqli_query($con,$sql);
	

	echo $message ;
	
    echo " Workflow data transfer to Tally DB. ";

	if($counter > 0){
		include "tally_mail.php";
	}
	
	echo "<script>window.close();</script>";
	
	exit();
	
	
	
