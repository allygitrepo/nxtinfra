<?php
if($_GET['sub'] == 'pdf'){
	session_start();
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

	$prn		= "excel";
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'> Payment Transaction List </th></tr></table>";		
													
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>Payment Serial No.</b></td>
					<td style='width: 10%;'><b>Type</b></td>
					<td style='width: 10%;'><b>Prepared date</b></td>
					<td style='width: 10%;'><b>Company</b> </td>
					<td style='width: 15%;'><b>Paid To</b></td>
					
					<td style='width: 10%;'><b>Paid Via</b></td>
					<td style='width: 15%;'><b>PO Number</b></td>
					<td style='width: 10%;'><b>Total Amount</b></td>
					<td style='width: 10%;'><b>TDS Amount</b></td>
					
					<td style='width: 15%;'><b>Paid Date</b></td>
					<td style='width: 15%;'><b>Supplier Invoice No.</b></td>
					<td style='width: 15%;'><b>Invoice Date</b></td>
					<td style='width: 15%;'><b>Supplier Invoice Amount</b></td>
					
					<td style='width: 10%;'><b>Payable Amount</b></td>
					<td style='width: 10%;'><b>Deduction Head</b></td>
					<td style='width: 10%;'><b>Deduction Amount</b></td>
					<td style='width: 10%;'><b>Deduction Head-2</b></td>
					<td style='width: 10%;'><b>Deduction Amount</b></td>
					<td style='width: 10%;'><b>Paid Amount</b></td>
					<td style='width: 10%;'><b>Bal. Amount</b></td>
					<td style='width: 10%;'><b>Cheque No.</b></td>
					<td style='width: 10%;'><b>UTR No.</b></td>	
					<th>By</th>
					<th>Pending With</th>
					<th>Decision</th>
					<th>MSME</th>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id				= $_GET['id'];
	$comid = $_SESSION['comid'];	
	
	$sql = "SELECT a.id as id, a.st_flag, a.paid_date, a.company_id, a.paid_to, a.cash_bank_name, a.cheque_no as cheque_no, a.utr_no as utr_no, a.total_amount_paid, a.tds_amount, b.supplier_invoice_no, b.invoice_date, b.deduction_head, b.deduction_amt, b.deduction_head1, b.deduction_amt1, b.actual_payment 
	FROM `payment_header` a, payment_details b where a.id = b.payment_hdr_id and del !='Y' ";
	if($user != 'Admin'){
		$sql .= " and a.company_id in ( $comid ) ";
	}
	
	if($_SESSION['sqlex']){
		$sql = $_SESSION['sqlex'];
	}
	
//echo $sql."<BR>";
//exit();

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$paid_date 				= date('d-m-Y', strtotime($row['paid_date']));
		$invoice_date 			= date('d-m-Y', strtotime($row['invoice_date']));
		$company_id				= $row['company_id'];
		$payment_no	 			= $row['id'];
		$paid_to 				= $row['paid_to'];
		$cash_bank_name 		= $row['cash_bank_name'];
		$cheque_no 				= $row['cheque_no'];
		$utr_no 				= $row['utr_no'];
		$total_amount_paid 		= $row['total_amount_paid'];
		$tds_amount 			= $row['tds_amount'];
		$st_flag 				= $row['st_flag'];
		
		$approval_status 		= $row['approval_status'];
		if($approval_status 	== 'Rejected' ){
			$approval_status 	= $row['status'];
		}	
		$del   = $row['del'];
		if($del =='Y'){
			$approval_status = 'Deleted';
		}
			
		$changed_by = $row['changed_by'];
				if(empty($changed_by)){
					$changed_by = $draft_by;
				}	
				
				$sql = "select * from sma_user where userid = '$changed_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$changed_by  = $r2['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
		$sql = "SELECT * from company where comp_id = '$company_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$company_name = $r2['comp_name'];

		$msme = '';
		if($st_flag=='S' || $st_flag =='C' || $st_flag =='D'){
			$sql = "SELECT * from sma_party_mst where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to 			= $r2['party_name'];
			$party_type			= $r2['party_type'];
			$party_msme_number 	= $r2['party_msme_number'];
			if($party_type==5){
				$msme = 'Yes';
			}
			else if($party_type==4){
				$msme = 'No';
			}		
			
		}
		else if($st_flag=='T' || $st_flag=='A'){
			$sql = "SELECT * from sma_user where id = '$paid_to' ";
			$com = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($com);
			$paid_to = $r2['username'];
        }

		$sql = "SELECT * from account_mst where id = '$cash_bank_name' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$account_name = $r2['account_name'];
		
		//$doc_type		= 'PY';
		//supplier_invoice_no, invoice_date, deduction_head, deduction_amt, deduction_head1, deduction_amt1, actual_payment
		$sql = "SELECT * FROM payment_details where payment_hdr_id = '$payment_no' ";
//echo $sql;		
		$res = mysqli_query($con,$sql);
		$error  = mysqli_error($con);
		if(!empty($error)){ echo "ERROR : " . $error; exit();}
		while($rw = mysqli_fetch_array($res)){
	
			$supplier_invoice_no 	= $rw['supplier_invoice_no'];
			$supp_id			 	= $rw['supp_id'];
			$deduction_head 		= $rw['deduction_head'];
			$deduction_amt 			= $rw['deduction_amt'];
			$deduction_head1 		= $rw['deduction_head1'];
			$deduction_amt1 		= $rw['deduction_amt1'];
			$actual_payment 		= $rw['actual_payment'];
			$payment_adjusted 		= $rw['payment_adjusted'];
			
			$payment_type = '';
			if($st_flag =='S'){
				$payment_type = 'Supplier Invoice';	
			}
			else if($st_flag =='D'){
				$payment_type = 'SI Advance';	
			}
			else if($st_flag =='T'){
				$payment_type = 'Travel / Reimbursement';	
			}
			else if($st_flag =='C'){
				$payment_type = 'OpEx';	
			}
			else if($st_flag =='A'){
				$payment_type = 'Travel Advance';	
			}
			
			
			$po_number = '';
			$si_total_amount = 0;
			if($st_flag =='S' || $st_flag=='R' ){
				$sql = "SELECT * FROM sma_supplier_invoice a, sma_purchase_order b where a.our_po_ref_no = b.id and a.id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
						$si_total_amount = $r2['total_amount'];
						$invoice_date	 = date('d-m-Y', strtotime($r2['invoice_date']));
									
			}
			else if($st_flag =='D'){
				$sql = "SELECT * FROM  sma_purchase_order b where id = '$supp_id' ";		
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$po_number = $r2['po_number'];
			}
			
			$bal_payment = $si_total_amount - $actual_payment;
			
			if($invoice_date=='01-01-1970'){
				$invoice_date='';
			}	
			
			$message .= "<tr><td>$payment_no</td>
							<td>$payment_type</td>
							<td>$paid_date</td>
							<td>$company_name</td>
							<td>$paid_to</td>
							<td>$account_name</td>
							<td>$po_number</td>
							<td>$total_amount_paid</td>
							<td>$tds_amount</td>
							<td>$paid_date</td>
							<td>$supplier_invoice_no</td>
							<td>$invoice_date</td>
							<td>$si_total_amount</td>
							<td>$payment_adjusted</td>
							<td>$deduction_head</td>
							<td>$deduction_amt</td>
							<td>$deduction_head1</td>
							<td>$deduction_amt1</td>
							<td>$actual_payment</td>
							<td>$bal_payment</td>
							<td>$cheque_no </td>
							<td>$utr_no</td>
							<td >$changed_by</td>
							<td >$pending_by</td>
							<td >$approval_status</td>
							<td >$msme</td>";
						$message .= "</tr>";

		}

	}
	
	$message .= "</table>";


//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'payment_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
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
			$fl_name  = 'budget_export.pdf';
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
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}
