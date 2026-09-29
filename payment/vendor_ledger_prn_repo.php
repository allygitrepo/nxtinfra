<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";

	$id					= $_GET['id'];
	$vendor_id			= $_GET['vendor_id'];
	$company_id			= $_GET['company_id'];

?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Vendor Ledger Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Vendor Ledger Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
					<form class="form-horizontal" action="vendor_ledger_prn_repo.php?sub=pdf" method="post">
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Company Name</label>
							<div class="col-md-5">
								
								<select class="form-control select2-123" name="company_id" id="company_id" >
									<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
								<label class="col-lg-2 control-label">Supplier Name</label>
								<div class="col-md-5">
									<select class="form-control select2" name="vendor_id" id="vendor_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_party_mst order by party_name  ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
											<?php } ?>
									</select>
								</div>
						</div>
						
						<div class="form-group">		
								<?php $start_date = '01-04-2020' ?>
								<label class=" col-sm-2 control-label">Start.Date</label>
									
								<div class="col-md-2">
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
								<label class="col-sm-1 control-label">End.Date</label>
								<div class="col-md-2">	
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							</div>
						
							<div class="form-group">
								<label for="project" class="col-sm-2 control-label">Output</label>
								<div class="col-sm-2">
									<select class="form-control " name="prn" id="prn" >
										<option value=""> Select </option>
										<option value="Pdf" selected > Pdf </option>
										<option value="Excel" > Excel </option>
											
									</select>
								</div>
						</div>
							
						<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="../dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
						</div>
							
				</form>
				
			</div>	
<?php 

	include("../footer.php");

}



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

	$prn		= $_POST['prn'];
	//$id			= $_POST['id'];
	$vendor_id	= $_POST['vendor_id'];
	$company_id	= $_POST['company_id'];
	
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	$prev_pay_id    = '';
	$message        = '';
	$grand_total_cr = 0;
	$grand_total_dr = 0;
	$amount_cumulative = 0;
	
	$message .= "<table cellspacing='0' style='width: 100%; border: solid 0px black; text-align: center; font-size: 20px;margin-left:1px;'><tr><td style='width: 100%;'> Statement of Accounts for the period from ".  date('d-m-Y', strtotime($start_date)) . " To " .  date('d-m-Y', strtotime($end_date)) . " </td></tr></table>";

	$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		$comp_code				= $row['comp_code'];
		$comp_name				= $row['comp_name'];
	}
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
	<tr><td style='width: 10%;'>Company</td>
		<td style='width: 35%;'>$comp_name</td>
		<td style='width: 10%;'>$comp_code</td>
		<td style='width: 20%;'></td>
		<td style='width: 10%;'>Date</td>
		<td style='width: 15%;'>".date("d-m-Y")."</td>
	</tr></table>";

	$sql 	= " SELECT *  FROM sma_party_mst where id = '$vendor_id' ";
	$res 	= mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$rs 	= mysqli_fetch_array($res);
	$party_name	= $rs['party_name'];

	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 16px;margin-left:1px;'>
	<tr><td style='width: 10%;'>Vendor Name</td>
		<td style='width: 90%;'>$party_name</td>
	</tr></table>";
	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 14px;margin-left:1px;'>
		<tr>
		<td style='width: 10%;'> Date</td>
		<td style='width: 15%;'> Doc Type</td>
		<td style='width: 10%;'> Doc No</td>
		<td style='width: 30%;'> Narration</td>
		<td style='width: 15%;'> Mode of Transaction</td>
		<td style='width: 10%;text-align:right;'> CR AMT</td>
		<td style='width: 10%;text-align:right;'> DR AMT</td>
				
		</tr></table>";
		
//echo $message; exit();
//	$sql = "SELECT * from tally_journal_entry where doc_date >= '$start_date' and doc_date <= '$end_date' and account_id = '$vendor_id' and account_type = 'V' and company_id = '$company_id' ";
			
	$sql = "SELECT a.* FROM `tally_journal_entry` a, sma_supplier_invoice b WHERE a.doc_no = b.id and a.doc_type = 'SI' and b.suplier_name = '$vendor_id' and a.company_id = '$company_id' and invoice_date >= '$start_date' and invoice_date <= '$end_date' 
		UNION
	SELECT a.* FROM `tally_journal_entry` a, payment_header b WHERE a.doc_no = b.id and a.doc_type = 'PY' and b.paid_to = '$vendor_id' and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date' 
	    UNION
	SELECT a.* FROM `tally_journal_entry` a, sma_travel_expenses b WHERE a.doc_no = b.id and a.doc_type = 'CE' and b.emp_id = '$vendor_id' and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date'  
		UNION
	SELECT a.* FROM `tally_journal_entry` a, sma_travel_expenses b WHERE a.doc_no = b.id and a.doc_type = 'RE' and b.emp_id = '$vendor_id' and a.company_id = '$company_id' and dated >= '$start_date' and dated <= '$end_date'  
		UNION
	SELECT a.* FROM `tally_journal_entry` a, sma_pettycash b, sma_pettycash_exp c WHERE a.doc_no = b.id and b.id = c.approval_ref_no and a.doc_type = 'PC' and c.spend_by = '$vendor_id' and a.company_id = '$company_id' and b.dated >= '$start_date' and b.dated <= '$end_date' order by doc_no, record_id ";

//echo $sql; exit();

	$reshdr = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	while($row   = mysqli_fetch_array($reshdr)){
		
			$doc_no					= $row['doc_no'];
			$doc_type				= $row['doc_type'];
			$doc_date 				= date('d-m-Y', strtotime($row['doc_date']));
			$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
			$company_id				= $row['company_id'];
			$supplier_id			= $row['supplier_id'];
			$invoice_no				= $row['supp_invoice_no'];
			$account_type			= $row['account_type'];
			$account_id				= $row['account_id'];
			$account_name			= $row['account_name'];
			$bank_name				= $row['bank_name'];
			$effect					= $row['effect'];
			$amount					= $row['amount'];
			$narration				= $row['narration'];

			$amount_dr = '0';
			$amount_cr = '0';
			if($effect=='Cr'){
				$amount_cr = $amount;
			}
			else if($effect=='Dr'){
				$amount_dr = $amount;
			}
			
			if($doc_type=='SI'){
				$test_v = 'Suupplier Invoice';
			}
			else if($doc_type=='CE'){
				$test_v = 'Operating Expences';
			}
			else if($doc_type=='RE'){
				$test_v = 'Regular Expences';
			}
			else if($doc_type=='PY'){
				$test_v = 'Payment';
				$invoice_no					= $row['doc_no'];
			}
			else if($doc_type=='PC'){
				$test_v = 'Petty Cash';
			}
			
			$amount_cr_tot = $amount_cr_tot	+ $amount_cr;
			$amount_dr_tot = $amount_dr_tot	+ $amount_dr;
			
			if($amount_cr ==0){
				$amount_cr ='';
			}
			else {
				$amount_cr = number_format($amount_cr,2);
			}
			
			if($amount_dr ==0){
				$amount_dr ='';
			}
			else {
				$amount_dr = number_format($amount_dr,2);
			}
			
			$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
				<tr>
				<td style='width: 10%;'>$doc_date</td>
				<td style='width: 15%;'>$test_v</td>
				<td style='width: 10%;'>$invoice_no</td>
				<td style='width: 30%;'>$narration</td>
				<td style='width: 15%;'>$account_name </td>
				<td style='width: 10%;text-align:right;'>".$amount_cr."</td>
				<td style='width: 10%;text-align:right;'>".$amount_dr."</td>
				</tr></table>";
				
			

	}		
	
	
	$message .= "<table cellspacing='0' border='.5' style='width: 100%; border: solid 0px black; text-align: left; font-size: 12px;margin-left:1px;'>
				<tr>
				<td style='width: 10%;'></td>
				<td style='width: 15%;'></td>
				<td style='width: 10%;'></td>
				<td style='width: 30%;'></td>
				<td style='width: 15%;'><b>Grand Total</b></td>
				<td style='width: 10%;text-align:right;'>".number_format($amount_cr_tot,2)."</td>
				<td style='width: 10%;text-align:right;'>".number_format($amount_dr_tot,2)."</td>
				</tr></table>";
				
//echo $message;
//exit();
	
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
	if($prn=='Pdf'){
	//$baseurl
		echo $message;
		exit();

		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
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
	}
}

 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertowordABC($num){
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