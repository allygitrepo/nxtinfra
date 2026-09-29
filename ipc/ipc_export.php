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
//	$from_date	= date('Y-m-d', strtotime($row['from_date']));
//	$to_date	= date('Y-m-d', strtotime($row['to_date']));
//	$supplier_id= $row['supplier_id'];	
//	$company_id= $row['company_id'];
		
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='16'> IPC Transaction List </th></tr></table>";		
						
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt;'>
				<tr><td style='width: 10%;'><b>IPC Serial Number</b></td>
					<td style='width: 40%;'><b>Company</b></td>
					<td style='width: 10%;'><b>Vendor</b></td>
					<td style='width: 15%;'><b>Date</b></td>					
					<td style='width: 10%;'><b>PO No.</b></td>
					<td style='width: 15%;'><b>Supplier Invoice No.</b></td>
					<td style='width: 10%;'><b>PO Amount</b></td>					
					<td style='width: 15%;'><b>Invoice Amount</b></td>
					<td style='width: 10%;'><b>Variation Order Amount</b></td>
					<td style='width: 10%;'><b>Variation in Price(VOP)</b></td>
					<td style='width: 10%;'><b>Material Advance</b></td>
					<td style='width: 10%;'><b>Material Advance Recovery</b></td>
					<td style='width: 10%;'><b>Deduct Retention Money</b></td>
					<td style='width: 10%;'><b>Release of Retention Money.</b></td>
					<td style='width: 10%;'><b>Variation due to Arbitration/ Claims or disputes</b></td>
					<td style='width: 10%;'><b>Deduction Work contract Tax</b></td>
					<td style='width: 10%;'><b>Deduction Liquidated Damage</b></td>
					<td style='width: 10%;'><b>Amount Withhold</b></td>
					<td style='width: 10%;'><b>Release of Withheld Amount</b></td>
					<td style='width: 10%;'><b>Other Deduction</b></td>
					<td style='width: 10%;'><b>Other Addition</b></td>
					<td style='width: 10%;'><b>Other Statutory Deduction</b></td>
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 12pt;'>";
				
		$id		= $_GET['id'];
		
	$comid = $_SESSION['comid'];	
	$sql = " SELECT * FROM `sma_ipc` ";
	if($user != 'Admin'){
		$sql .= " where sma_comp_id in ( $comid ) ";
	}
	
	$sql = $_SESSION['sqlex'];
	
//echo $sql."<BR>";

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
			$sma_id				= $row['id'];
			$ipc_date			= date('Y-m-d', strtotime($row['ipc_date']));
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
			$sma_amount_withhold			= $row['sma_amount_withhold'];
			$release_withheld_amount		= $row['release_withheld_amount'];
			$sma_other_deduction			= $row['sma_other_deduction'];
			$sma_other_deduction_desc		= $row['sma_other_deduction_desc'];
			$other_addition					= $row['other_addition'];
			$other_addition_desc			= $row['other_addition_desc'];
			$other_statutory_deduction		= $row['other_statutory_deduction'];
			$other_statutory_deduction_desc	= $row['other_statutory_deduction_desc'];
		
		$sql = "SELECT * from company where comp_id = '$sma_comp_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$company_name = $r2['comp_name'];

		$sql = "SELECT * from sma_party_mst where id = '$sma_vendor_id' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$vendor_name = $r2['party_name'];

		$sql = "SELECT * from account_mst where id = '$cash_bank_name' ";
		$com = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($com);
		$account_name = $r2['account_name'];		
		//$doc_type		= 'PY';

			$message .= "<tr>
						<td>$sma_id</td>
						<td>$company_name</td>
						<td>$vendor_name</td>
						<td>$ipc_date</td>
						<td>$sma_po_no</td>
						<td>$sma_invoice_no</td>
						<td>$sma_po_amount</td>
						<td>$sma_invoice_amount</td>
						<td>$sma_variation_order_amt</td>
						<td>$sma_variation_in_price</td>
						<td>$sma_material_advance</td>
						<td>$sma_material_advance_recovery</td>
						<td>$sma_deduct_retention_money</td>
						<td>$sma_release_retention_money</td>
						<td>$sma_variation_due_to_arbitratioon </td>
						<td>$sma_deduction_work_contract_tax</td>
						<td>$sma_deduction_liquidated_damage</td>
						<td>$sma_amount_withhold</td>
						<td>$release_withheld_amount</td>
						<td>$sma_other_deduction</td>
						<td>$other_addition</td>
						<td>$other_statutory_deduction_desc</td>";
					$message .= "</tr>";
	
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
		$fl_name = 'ipc_export.xls';
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
