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
	//$comp_id	= $_GET['comp_id'];	
	//$location   = $_GET['location'];
	
	$tableName		= "payment_header";
	$sql 	= "SELECT * FROM $tableName where id = '$id'";

//echo $sql;
		$payment_hdr_id		= $row['id']; 
		$paid_to			= $row['paid_to']; 
		$company_id			= $row['company_id']; 
		$cheque_no			= $row['cheque_no']; 
		$utr_no				= $row['utr_no']; 
		$dated				= date('d-m-Y', strtotime($row['dated'])); 
		$cash_bank_name		= $row['cash_bank_name'];
		
		
$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
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
			<tr><td style='width: 95%;'> Payment Voucher</td></tr></table>";
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

	
		$sql 	= "SELECT * FROM sma_party_mst where id = '$paid_to'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$party_name  	 = $dep['party_name'];
		
		$sql 	= "SELECT * FROM account_mst where id = '$cash_bank_name'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$account_name  	 		= $dep['account_name'];
		
		$sql 	= "SELECT * FROM sma_supplier_invoice where id = '$sma_invoice_no'";
		$result = mysqli_query($con,$sql);
		$dep 	= mysqli_fetch_array($result);
		$supplier_invoice_no  	 = $dep['supplier_invoice_no'];
		$invoice_date		  	 = date('d-m-Y h:m i', strtotime($dep['invoice_date']));
		
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><th style='width: 50%;text-align: left'>Paid To : $party_name </th>";
	$message .= "<th style='width: 25%;'> Date :$dated </th>";
	$message .= "<td style='width: 25%;'>Payment Sr.No.:$po_number </td>
				</tr></table>";

	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 20%;'>Invoice No.</td>
			<td style='width: 10%;'>Cheque No.</td>
			<td style='width: 10%;'>#</td>
			<td style='width: 30%;'>Narration</td>
			<td style='width: 10%;'>Amount(Rs.)</td>
			
			</tr></table>";
			
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$row = mysqli_fetch_array($result);
		
		$sql 	= "SELECT * FROM payment_details where payment_hdr_id = '$payment_hdr_id'";
		$res= mysqli_query($con,$sql);
		while($rowd = mysqli_fetch_array($res)){
		
			$supplier_invoice_no		= $row['supplier_invoice_no']; 
			$supp_id					= $row['supp_id']; 
			$invoice_date				= date('d-m-Y', strtotime($row['invoice_date'])); 
			$payment_adjusted			= $row['payment_adjusted']; 
			$deduction_amt				= $row['deduction_amt']; 
			$deduction_amt1				= $row['deduction_amt1']; 
			$deduction_head				= $row['deduction_head']; 
			$deduction_head1			= $row['deduction_head1']; 
			$actual_payment				= $row['actual_payment']; 
			$remarks					= $row['remarks']; 
			
			
			$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 13px;'>
			<tr><td style='width: 20%;'>$supplier_invoice_no</td>
			<td style='width: 10%;'>$cheque_no</td>
			<td style='width: 10%;'>$supp_id</td>
			<td style='width: 30%;'>$remarks</td>
			<td style='width: 10%;'>$actual_payment</td>
			</tr></table>";
			
			$tot_amount = $tot_amount + $actual_payment;
	
		}
	
	
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 11px;'>
			<tr><td style='width: 10%;'></td>
			<td style='width: 10%;'></td>
			<td style='width: 10%;'></td>
			<td style='width: 30%;'>Total</td>
			<td style='width: 10%;'>$tot_amount</td>
			
			</tr></table>";
			
	$message .= "<table cellspacing='0' style='width: 95%; text-align: left;margin-left:0px; font-size: 12px;'>
			<tr><td style='width: 10%;'>UTR No.</td>
			<td style='width: 70%;'>$utr_no</td>
			</tr></table>";
	
	
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
	
	$sql 	= "SELECT a.create_by, a.create_date, a.status, b.username FROM `workflow_history` a, `sma_user` b where a.doc_type = 'PY' and a.doc_id = '$payment_hdr_id' and b.id = a.create_by order by a.id desc ";
	$bs 	= mysqli_query($con,$sql);
	while($bs1 	= mysqli_fetch_array($bs)){
		
		$status 		= $bs1['status'];
		if ($status == 'Submited' || $status == 'Prepared' || $status == 'Verified'){
			if(empty($checker)){
				$checker 		= $bs1['username'];
				$checker_date 	= date('d-m-Y h:m ia', strtotime($bs1['create_date']));
			}
		}
		else if ($status == 'Completed' || $status == 'Approved'){
			$approval 		= $bs1['username'];
			$approval_date 	= date('d-m-Y h:m ia', strtotime($bs1['create_date']));
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