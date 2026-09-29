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
	
	$sql = " SELECT * FROM `tally_journal_entry` 
				WHERE 1 AND status = 'C' and doc_type in ('RV','PV')
					ORDER BY doc_type, doc_no, reversal_flag, effect ";
				
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
		
		if($paid_date == '01-01-1970'){
            $paid_date = '';
        }
		
		//$today_date 				= date('d-m-Y');
		//$today_date  = '10-07-2020';
		$record_id 					= $row['record_id'];
		$record_type 				= $row['record_type'];
		$doc_type 					= $row['doc_type'];
		$doc_type_t					= $row['doc_type'];
		$reversal_flag				= $row['reversal_flag'];
		
		$doc_no_upd					= $row['doc_no'];

		$doc_no 					= $doc_type.$row['doc_no'];
		
		$doc_no_v 					= $doc_type.$row['doc_no'];
		

		//if( $doc_type == 'RV' ){
		//	$today_date = $supp_invoice_date;
		//}
		if( $doc_type=='PV' && $reversal_flag != 'R' ){
			$doc_type_v = $doc_type.'JV';
			$doc_no 					= $doc_type_v.$row['doc_no'];
			$today_date					= $doc_date ;
		}
		else if( $doc_type=='PV' && $reversal_flag == 'R' ){
			$doc_type_v = $doc_type.'JR';
			//$doc_no 					= $doc_type_v.$row['doc_no'];
			$doc_no 					= $doc_type_v.$row['doc_no'].substr($doc_date, 0,2). substr($doc_date, 3,2). substr($doc_date, 6,4);
			$today_date					= $doc_date ;
			$supp_invoice_date 			= $today_date;
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
		
		$doc_type = $doc_type . $doc_no_upd;
		$sql = " INSERT INTO tally_all_journal ( record_id, record_type, doc_type, doc_no, doc_date, company_code, supplier_id, supp_invoice_no, supp_invoice_date, paid_date, account_type, account_id, account_name, bank_name, effect, amount, narration, cheque_no, address, gst_no, state, status, pan_no, mobile_no ) 
		values ('$record_id', '$record_type', '$doc_type', '$doc_no', '$today_date','$company_code', '$supplier_id','$supp_invoice_no', 
		'$supp_invoice_date', '$paid_date', '$account_type', '$account_id', '$account_name', '$bank_name', '$effect', '$amount', 
		'$narration', '$cheque_no', '$address', '$gst_no', '$state', 'R', '$pan_no', '$mobile_no')";
//echo $sql."<BR>";
		mysqli_query($con,$sql);
		echo mysqli_error($con);
		
		if($doc_no_prev != $doc_no_v){
			
			if(!empty($doc_no_prev)){
				$sql = "SELECT sum(amount) as amount, doc_no, effect, doc_type FROM `tally_all_journal` 
							WHERE doc_type = '$doc_no_prev' GROUP BY doc_type, effect ";
				$res = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($res)){
					$effect = $r->effect;
					if($effect=='Cr'){	
						$amount_cr = $r->amount;
					}
					else if($effect=='Dr'){
						$amount_dr = $r->amount;
					}
				}
				
				if($amount_cr != $amount_dr){
					$sql = "DELETE FROM `tally_all_journal` WHERE doc_type = '$doc_no_prev' ";
//echo $sql ."<BR>";					
					mysqli_query($con, $sql);
					
					$sql = "UPDATE `tally_journal_entry` set status ='C' 
								WHERE doc_no = '$doc_no_upd_prev' and doc_type = '$doc_type_t_prev'";
					mysqli_query($con,$sql);
					$error  = mysqli_error($con);
					continue;
				}
			}
			
			$message .= "<tr><td style='width: 10%;'>$company_code</td>
					<td style='width: 10%;'>$doc_no</td>
					<td style='width: 10%;'>$today_date</td>
					<td style='width: 10%;'>$supp_invoice_no</td>
					<td style='width: 10%;'>$supp_invoice_date</td>
					<td style='width: 20%;'>$account_name</td>
					<td style='width: 10%;text-align:right;'>$amount</td>
				</tr>";
		}
		
		$doc_no_prev 		= $doc_no_v;
		$doc_no_upd_prev 	= $doc_no_upd;
		$doc_type_t_prev 	= $doc_type_t;
	
		if($doc_type_t=='RV'){
			$sql = "UPDATE p2p_revenue_hdr set tally_status ='U' , tally_updated_on = now() where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		else if($doc_type_t=='PV'){
			$sql = "UPDATE sma_provisional_jv_hdr set tally_status ='U' , tally_updated_on = now() where tally_status in ('U', 'R', 'C') and id = '$doc_no_upd' ";
			mysqli_query($con,$sql);
		}
		
		$sql = "UPDATE `tally_journal_entry` SET status ='U', comp_code ='$company_code', 
					tally_uploaded_on = now() 
					WHERE doc_no = '$doc_no_upd' and doc_type = '$doc_type_t' ";
		mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		
//echo $error. "<BR>";
		$counter = $counter + 1;
		
	}

	if(!empty($doc_no_prev)){
		$sql = "SELECT sum(amount) as amount, doc_no, effect, doc_type FROM `tally_all_journal` 
						WHERE doc_type = '$doc_no_prev' GROUP BY doc_type, effect ";
		$res = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($res)){
			$effect = $r->effect;
			if($effect=='Cr'){	
				$amount_cr = $r->amount;
			}
			else if($effect=='Dr'){	
				$amount_dr = $r->amount;
			}
		}
				
		if($amount_cr != $amount_dr){
			$sql = "DELETE FROM `tally_all_journal` WHERE doc_type = '$doc_no_prev' ";					
			mysqli_query($con, $sql);
			
			$sql = "UPDATE `tally_journal_entry` set status ='C' 
								WHERE doc_no = '$doc_no_upd_prev' and doc_type = '$doc_type_t_prev'";
			mysqli_query($con,$sql);

		}
	}
	
	$message .="</table>";
	
	echo $message ;
	
    echo " Workflow data transfer to Tally DB. ";

	if($counter > 0){
		include "tally_mail.php";
	}
	
	echo "<script>window.close();</script>";	
	
	exit();
	
	
	
