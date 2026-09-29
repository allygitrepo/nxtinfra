<?php
if($_GET['sub'] == 'pdf' || $_POST['sub'] == 'mail'){
	session_start();
	include "baseurl.php";
	$con = mysqli_connect("localhost","root","","hc_workflow");
	
	include "dbcon.php";
	
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
	
	$prn		= "excel";
//	$from_date	= date('Y-m-d', strtotime($_POST['from_date']));
//	$to_date	= date('Y-m-d', strtotime($_POST['to_date']));
//	$department = $_POST['department'];	
//	$supplier_id= $_POST['supplier_id'];	
//	$company_id= $_POST['company_id'];
	
	$module 		= $_SESSION['module'];
	
	$message ='';
	
	if($module=='A'){	
		$module_name = "Approval Memo ";
	}
	else if($module=='B'){
		$module_name = "Purchase Order ";
	}
	else if($module=='C'){
		$module_name = "GRN SRN ";
	}
	else if($module=='D'){
		$module_name = "Supplier Invoice ";
	}
	else if($module=='E'){
		$module_name = "IPC ";
	}
	else if($module=='F'){
		$module_name = "Payment ";
	}
	else if($module=='G'){
		$module_name = "Travel Request";
	}
	else if($module=='H'){
		$module_name = "Travel Expense ";
	}
	else if($module=='I'){
		$module_name = "Regular Expense ";
	}
	else if($module=='J'){
		$module_name = "Operating Expense ";
	}
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' > ". $module_name. " Status Report </th></tr></table>";		
	
	
				if($module=='A' || $module=='B'){ 
					$hdg_a 	= "Company";
					$hdg_b	= "Amount";
				} 
				else if($module=='C'){
					$hdg_a = "Supplier Invoice No.";
					$hdg_b	= "PO.REF.No.";
				 }
				 else if( $module =='D' || $module =='E'){
					$hdg_a	= "PO.REF.No.";
					$hdg_b	= "Amount";
				 }
				 else if($module=='F'){
					$hdg_a = "Paid Via";
					$hdg_b	= "Amount";
					$hdg_c	= "Supp.No.";
				 }
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12px;'>
				<tr> ";		 
				if($module!='G' && $module!='H' && $module!='I' && $module!='J'){
                    $message .= "<td style='width: 5%;text-align: right;'><b>No.</b></td>
					<td style='width: 10%;'><b>Date</b> </td>";
					
					$message .= "<td style='width: 20%;'><b> $hdg_a </b></td>
					<td style='width: 20%;'><b>Supplier Name</b></td>";
					if($module=='F'){ 
						$message .= "<td style='width: 10%;'><b> $hdg_c </b></td>";
					}
					$message .= "<td style='width: 10%;text-align: right;' ><b> $hdg_b </b></td>";
				} 
					else if($module=='G'){ 
						$message .= "<th>SrNo.</th>
						<th>Name</th>
						<th>Location From</th>
						<th>Date From</th>
						<th>Location To</th>
						<th>Date To</th>
						<th>Company</th>
						<th>Advance Amount</th>";
					}
					else if($module=='H'){ 
						$message .= "<th>SrNo.</th>
						<th>Name</th>
						<th>Company</th>
						<th>Date</th>
						<th>Approval Ref.no.</th>
						<th style='text-align:right;'>Trip.Amount</th>
						<th style='text-align:right;'>Exp.Amount</th>";
				 } else if($module=='I' || $module=='J'){ 
						$message .= "<th>SrNo.</th>
						<th>Name</th>
						<th>Company</th>
						<th>Date</th>
						<th  style='text-align:right;'>Total Amount</th>";
				 }
				
				
					$message .= "<td style='width: 10%;'><b>Sent By</b></td>
					<td style='width: 10%;'><b>Send Date</b></td>
					<td style='width: 10%;'><b>Pending To</b></td>
					<td style='width: 5%;'>Pendg Days</td></tr></table>";
//echo $message; exit();


			
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10px;'>";
	
	$sql = $_SESSION['sqlex'];
					
//echo $sql."<BR>";
	
	$today_date 	= strtotime(date("Y-m-d"));
		
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$sent_by 		= $row['create_by'];
					$send_to		= $row['reviewed_by'];
					$remarks 		= $row['remarks'];
					$sent_date 	= date('d-m-Y', strtotime($row['create_date']));

					$dated 		= date('d-m-Y', strtotime($row['dated']));
					
					if($module=='A'){
						$company 	= $row['company'];
						$dated 		= date('d-m-Y', strtotime($row['dated']));
						$approval_hdr_id = $row['id'];
						$hdr_id		= $row['id'];
						$sql 		= "SELECT `values` as total_amount FROM `sma_approval_details` where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' ";
						$r4 		= mysqli_query($con, $sql);
						$r3 		= mysqli_fetch_array($r4);
						$amount		= $r3['total_amount'];
										
						$sql 		= "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$approval_hdr_id' and b.id = a.supplier_name";
						$q2  		= mysqli_query($con, $sql);
						$r2 		= mysqli_fetch_array($q2);
						$party_name  = $r2['party_name'];
						$amount		 = $r2['values'];
						
					}
					else if($module=='B'){
						
						$purchase_id	= $row['id'];
						$supplier_id 	= $row['to_supplier'];
						$hdr_id	 	= $row['po_number'];
						$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						$amount		 	= $r2['values'];
						
						$sql = "SELECT round(sum( (quantity * unit_rate ) +  (((quantity * unit_rate) * gst) / 100) ),2) as po_total  from sma_po_items where purchase_id = '$purchase_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount 	= $r1['po_total'];
							
					}
					else if($module=='C'){
						$our_po_ref_no = $row['our_po_ref_no'];
						$dated 		= date('d-m-Y', strtotime($row['received_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['supplier_name'];
						$company		= $row['supplier_invoice_no'];
						//$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$amount		 = $r2['po_number'];
						
							
					}
					else if($module=='D'){
						$our_po_ref_no = $row['our_po_ref_no'];
						$dated 		= date('d-m-Y', strtotime($row['invoice_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['suplier_name'];
						$company		= $row['supplier_invoice_no'];
						//$company	 	= $row['project'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company	 = $r2['po_number'];
						
						$amount	 	= $row['total_amount'];
							
					}
					else if($module=='E'){  //IPC
						$our_po_ref_no = $row['sma_po_no'];
						$dated 			= date('d-m-Y', strtotime($row['ipc_date']));
						$hdr_id			= $row['id'];
						$supplier_id 	= $row['sma_vendor_id'];
						$company		= $row['supplier_invoice_no'];
						$company	 	= $row['sma_comp_id'];						
						$sql 			= "SELECT party_name, '' FROM `sma_party_mst` where id = '$supplier_id' ";
						$q2  			= mysqli_query($con, $sql);
						$r2 			= mysqli_fetch_array($q2);
						$party_name  	= $r2['party_name'];
						//$amount		 	= $r2['values'];
						
						$sql="SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
						$q2 = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company	 = $r2['po_number'];
						
						$amount	 	= $row['sma_po_amount'];
						$sma_invoice_no = $row['sma_invoice_no'];
						$sql = "select * from sma_supplier_invoice where id = '$sma_invoice_no' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no = $r2['supplier_invoice_no'];
							
					}
					else if($module=='F'){  //Payment
						$dated 			= date('d-m-Y', strtotime($row['dated']));
						$hdr_id			= $row['id'];
						
						$paid_to = $row['paid_to'];
						$st_flag = $row['st_flag'];
						if($st_flag =='A' || $st_flag =='T'){
							$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$party_name  = $r2['username'];
						}
						else {
							$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$party_name  = $r2['party_name'];
						}
						
						$cash_bank_name = $row['cash_bank_name'];
						$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
						$q2 	= mysqli_query($con, $sql);
						$r2 	= mysqli_fetch_array($q2);
						$company = $r2['account_name'];
						
						$amount	 	= $row['total_amount_paid'];
						$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$hdr_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$sma_invoice_no  = $r2['supplier_invoice_no'];
							
					}
					else if($module=='G'){  //Travel Request
						
						$emp_id = $row['emp_id'];
						$sql = "select * from sma_user where id = '$emp_id' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$emp_name = $r2['username'];
						
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 		= $r1['comp_name'];
						$comp_code 		= $r1['comp_code'];
						
						$start_date = date('d-m-Y', strtotime($row['start_date']));
						$end_date 	= date('d-m-Y', strtotime($row['end_date']));
						
						if($start_date=='01-01-1970'){ $start_date='';}
						if($end_date=='01-01-1970'){ $end_date='';}
					}
					else if($module=='H'){  //Travel Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 		= $r1['comp_name'];

						$emp_id = $row['emp_id'];
						$sql="SELECT * from sma_user where id = '$emp_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$username		= $r1['username'];
						
						$dated 			=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
						$exp_amount = 0;
						$fare		 = 0;
						$approval_ref_no = $row['approval_ref_no'];	
						$id = $row['id'];	
						$sql="SELECT * from sma_departure where approval_ref_no = '$id' ";
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$j = 0;
						while($d1 = mysqli_fetch_array($res)){
							$fare += $d1['fare'];
						}			
									
						$sql="SELECT * from sma_expenses where exp_type = 'T' and approval_ref_no = '$id' ";
						
						$res = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$j = 0;
						while($d1 = mysqli_fetch_array($res)){
							$exp_amount += $d1['amount'];
						}			
							
					}
					else if($module=='I'){  //Regular Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 	= $r1['comp_name'];

						$emp_id = $row['emp_id'];
						$sql  = "SELECT * from sma_user where id = '$emp_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$username		= $r1['username'];
						
						$re_id = $row["id"];
						$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'R' and approval_ref_no = '$re_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount		= $r1['amount'];
						 
						$dated 		=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
					}	
					else if($module=='J'){  //Operating Expense{
						$company_id  = $row['company_id'];
						$sql  = "SELECT * from company where comp_id = '$company_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$comp_name 	= $r1['comp_name'];

						$sma_vendor_id = $row['emp_id'];
						$sql  = "SELECT * from sma_party_mst where id = '$sma_vendor_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 	= mysqli_fetch_array($res1);
						$username		= $r1['party_name'];
						
						$re_id = $row["id"];
						$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
						$res1 = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res1);
						$amount		= $r1['amount'];
						 
						$dated 		=  date('d-m-Y', strtotime($row['dated']));
						if( $dated=='01-01-1970' ){ $dated='';}
						
					}
					
					if($module=='A' || $module=='B' ){
						$sql 		= "select * from company where comp_id = '$company' ";
						$q2  		= mysqli_query($con, $sql);
						$r2 		= mysqli_fetch_array($q2);
						$company  	= $r2['comp_name'];
					}
					
					$sl="SELECT * FROM sma_user where id = '$sent_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$sent_by = $rw['username'];
									
					$sl="SELECT * FROM sma_user where id = '$send_to' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$send_to = $rw['username'];
										
						$j=$j+1;						
						
					$s_date 		= strtotime(date('Y-m-d', strtotime($row['create_date'])));
					//$your_date 	= strtotime("2010-01-31");
					$datediff	 	= $today_date - $s_date;
					$pending_days 	= round(($datediff / (60 * 60 * 24))) + 1 ;
					
					
		$message .= "<tr>";
						
					if($module!='G' && $module!='H' && $module!='J' && $module!='I'){
					$message .= "<td style='width: 5%;text-align: right;'>  $hdr_id</td>
						<td style='width: 10%;'>  $dated</td>
						<td style='width: 20%; word-wrap:break-word;'>  $company</td>
						<td style='width: 20%; word-wrap:break-word;'>  $party_name</td>";
					if($module=='F'){ 
						$message .= "<td style='width: 10%;word-wrap:break-word;'>  $sma_invoice_no</td>";
					} 
					$message .= "<td style='width: 10%;text-align: right;' > $amount</td>";
					
				 } else if($module=='G'){	 
					$message .= "<td style='width: 5%;text-align:right;'> ".$row['id']."</td>
					<td style='width: 10%;word-wrap:break-word;'> $emp_name</td>
					<td style='width: 10%;'>". $row['traval_from']."</td>
					<td style='width: 10%;'>$start_date</td>
					<td style='width: 13%;'>".$row['traval_to']."</td>
					<td style='width: 10%;'> $end_date</td>
					<td style='width: 04%;'> $comp_code</td>
					<td style='width: 10%;'>". $row['advance_amount']."</td>";
				 }  
				else if($module=='H'){	 
					$message .= "<td style='width: 5%'>". $row['id']."</td>
					<td style='width: 15%;word-wrap:break-word;'> $username</td>
					<td style='width: 15%;word-wrap:break-word;'> $comp_name</td>
					<td style='width: 10%;'> $dated</td>
					<td style='width: 10%;'>".  $row['approval_ref_no']. "</td>
					<td style='width: 10%;' style='text-align:right;'>". number_format($fare)."</td>
					<td style='width: 10%;' style='text-align:right;'> ".number_format($exp_amount)."</td>";
				 } 
					else if($module=='I' || $module=='J'){  //Regular Expense{
						
						$message .= "<td style='width: 5%'>". $row['id']."</td>
						<td style='width: 20%;word-wrap:break-word;'> $username</td>
						<td style='width: 29%;word-wrap:break-word;'> $comp_name</td>
						<td style='width: 10%;'> $dated</td>
						<td style='width: 10%;text-align:right;'> $amount</td>";
				}	
					
					
						
					$message .= "<td style='width: 10%;'>".$sent_by."</td>
						<td style='width: 10%;'>".$sent_date ."</td>
						<td style='width: 10%;'>".$send_to ."</td>
						<td style='width: 5%;text-align: right;'>".$pending_days ."</td>";
						
		$message .= "</tr>";
			
	}
			
}
	
	
	$message .= "</table>";

	
if($_POST['sub'] == 'mail'){
	
	
/* 	echo $message;
exit();
 */	
		$approver = $_POST['approver'];
		$sql="select * from sma_user where id='$approver' ";
//echo $sql;
		
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			//$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}
			
		
		$fl_name = 'pending_export.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
		
	
		$dirname = 'C:\xampp\htdocs\workflow2020';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'pending_export.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			//$html2pdf->Output($fl_name);
			$html2pdf->Output($fl_name, 'F');// saving file as pdf file

//exit();
			
			include ("pending_trans_mail.php");
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
	
	exit();

}
else {

//		echo $message; exit();
	
	$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'pending_export.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			//$html2pdf->Output($fl_name, 'F');// saving file as pdf file
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	exit();

	//echo $message;

}
	
exit();


		
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
