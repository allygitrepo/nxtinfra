<?php
//	session_start();
	include "dbcon.php";
	include "baseurl.php";

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
	$flname = 'p2p_jv_transfer_tally.csv';
	$fp = fopen($flname, 'w');
	
	$fdata = "Record id,Record Type,Doc Type,Doc No,Doc Date,Company Code,Supplier Id,Supp Invoice No.,Supp Invoice Date, Paid Date,Account Type,Account Id,Account Name,Budget Head,Bank Name,Effect,Amount,Narration,Cheque No.,Address, GST No.,State,PAN No.,Mobile No., ".PHP_EOL;
	fwrite($fp, $fdata);
		
		
	$message =' Data Updated to Tally';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Doc No.</b> </td>
					<td style='width: 10%;'><b>Doc Date</b></td>
					<td style='width: 15%;'><b>Supp.Invocie No.</b></td>
					<td style='width: 10%;'><b>Supp. Invoice Date</b></td>
					<td style='width: 20%;'><b>Account Name</b></td>
					<td style='width: 10%;'><b>Amount</b></td
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
	
//	$sql = " DELETE from tally_all_journal ";
//	mysqli_query($con,$sql);
	
	$sql = " SELECT * FROM `tally_journal_entry` 
				WHERE status = 'R' 
					order by doc_type, doc_no, effect limit 1,100";
				
//echo $sql."<BR>";				
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$doc_no 		= $row['doc_no'];
		$account_name 	= $row['account_name'];
		if(empty($account_name)){
			$upload_error .= "Name is blank for ID : " . $doc_no . "<BR>";
			$ignore_doc_no .= $doc_no. ',';
			continue;
		}	
	}	

	$ignore_doc_no .= '0';
	
	$sql = " SELECT *
		FROM `tally_journal_entry` 
			WHERE status = 'R'  
				order by doc_type, doc_no, effect ";
				
			 
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){

//echo $sql; exit();
		
		$amount 					= $row['amount'];
		
		if($amount<0){
			continue;
		}
		
		$doc_date 					= date('d-m-Y', strtotime($row['doc_date']));
		
		//$doc_date = '08-07-2020';
		$supp_invoice_date 			= date('d-m-Y', strtotime($row['supp_invoice_date']));
		$paid_date					= date('d-m-Y', strtotime($row['paid_date']));
		
		if($paid_date == '01-01-1970'){
            $paid_date = '';
        }
		
		$today_date 				= date('d-m-Y');
		//$today_date  = '10-07-2020';
		$record_id 					= $row['record_id'];
		$record_type 				= $row['record_type'];
		$doc_type 					= $row['doc_type'];
		
		$doc_no_upd					= $row['doc_no'];
		$doc_no 					= $doc_type.$row['doc_no'];
		
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
		$account_name 				= $row['account_name'];
		$budget_head 				= $row['budget_head'];
		
		$bank_name 					= $row['bank_name'];
		$effect 					= $row['effect'];
		$amount 					= round($row['amount'],0);
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
		//mysqli_query($con,$sql);
		 
		//."\n".PHP_EOL 
		//str_replace(',','.',$form);
		if($paid_date=='30-11--0001'){
			$paid_date='';
		}	
		if($today_date=='30-11--0001'){
			$today_date='';
		}
		if($supp_invoice_date=='30-11--0001'){
			$supp_invoice_date='';
		}

		$address_arr = explode(',',$address);
		//$address_v 	 = $address_arr[0].' '.$address_arr[1].' '.$address_arr[2].' '.$address_arr[3].' '.$address_arr[4].' '.$address_arr[5].' '.$address_arr[6].' '.$address_arr[7];
		$address_v 	 = $address_arr[0].' '.$address_arr[1];


		
		$fdata =  $record_id.','. $record_type.','. $doc_type.','. $doc_no.','. $today_date.','. $company_code.','. $supplier_id.','. $supp_invoice_no.','. $supp_invoice_date.','. $paid_date.','. $account_type.','. $account_id.','. $account_name.','. $budget_head.','. $bank_name.','. $effect.','. $amount.','. str_replace(',','.',trim($narration)).','. $cheque_no.','. str_replace(',','.',substr($address_v,1,20)).','. $gst_no.','. $state.','. $pan_no.','. $mobile_no.',' .PHP_EOL;
		fwrite($fp, $fdata);
/* 
		
if($doc_no=='PY1001'){		
	echo $address ."<BR>";
}
if($doc_no=='PY1001'){		
	print_r($address_arr) ."<BR>";
	echo "<BR>".$address_v."<BR>";
	echo "<BR>".$fdata."<BR>";
	exit();
} */
		
		if($doc_no_prev != $doc_no){
			$message .= "<tr><td style='width: 10%;'>$doc_no</td>
					<td style='width: 10%;'>$today_date</td>
					<td style='width: 15%;'>$supp_invoice_no</td>
					<td style='width: 10%;'>$supp_invoice_date</td>
					<td style='width: 20%;'>$account_name</td>
					<td style='width: 10%;'>$amount</td
				</tr>";
		}
		
		$doc_no_prev = $doc_no;
	
		/* if($doc_type=='PY'){
			$sql = "UPDATE payment_header set tally_status ='U' where tally_status in ('U', 'R') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='SI'){
			$sql = "UPDATE sma_supplier_invoice set tally_status ='U' where tally_status in ('U', 'R') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type=='PC'){
			$sql = "UPDATE sma_pettycash set tally_status ='U' where tally_status in ('U', 'R') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else {
			$sql = "UPDATE sma_travel_expenses set tally_status ='U' where tally_status in ('U', 'R') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		
		$sql = "UPDATE `tally_journal_entry` set status ='U' , comp_code ='$company_code', tally_uploaded_on = now() where doc_no = '$doc_no_upd' and doc_type = '$doc_type' ";
		mysqli_query($con,$sql); //status = 'R' and 
		$error  = mysqli_error($con); */

	}

	$message .="</table>";
	
	echo $message ;
	
	fclose($fp);
	
    echo " Workflow data transfer to Tally DB. ";

	include "tally_mail.php";
	
	
	
