<?php 
if($_GET['sub'] == 'list'){
include("../header.php");
$modulePath = "approval/";

ini_set('max_execution_time', 0);

?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Workflow Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="unpaid_msme_report_scheduler.php?sub=pro" method="post">
                      
							<div class="form-group">
								
								<div class="col-sm-5">
									<label for="company_id" class="control-label ">Company</label>
									<select class="form-control select2123" name="company_id" id="company_id" >
										<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							</div>	
								
							<div class="form-group">
								<div class="col-xs-4">
									<label for="company_id" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
                                	<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
									
									<a href="dashboard.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
if($_GET['sub']=='pro'){

session_start();
include("../dbcon.php");
include("../baseurl.php");

ini_set('max_execution_time', 0);

$tmpv = $_GET['m'];

	$company_id  = $_POST['company_id'];
	
	$sql="SELECT * FROM company where comp_id = '$company_id' ";		
	$res2 = mysqli_query($con, $sql);
	$mat = mysqli_fetch_array($res2);
	$_SESSION['compnamee'] 	= $mat['comp_name'];
			
	$sqlc = "";
	if(!empty($company_id)){
		$sqlc = " and company_id = '$company_id' ";
	}	
	$sql = "truncate analysis_unpaid";
	mysqli_query($con, $sql);
	
	$start_date = '2024-04-01';
	$end_date 	= '2025-03-31';
	
//Supplier Invoice Start
	$sql 	= "SELECT company_id, payable_amount, id, supp_id, payment_hdr_id , st_flag, utr_no , del
				FROM ( 
					SELECT a.company_id, a.payable_amount, a.id, a.del, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
					FROM sma_supplier_invoice a
					LEFT JOIN 
						( SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
						FROM sma_supplier_invoice  a
						LEFT JOIN  payment_details b
						ON b.supp_id = a.id  
						LEFT JOIN  payment_header c
						ON b.payment_hdr_id = c.id where c.st_flag = 'S' and c.del !='Y' ) as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y'
					and a.created_date >= '$start_date' and a.created_date <= '$end_date' $sqlc
					ORDER BY `a`.`id` ASC ) DS1 where 1 $sqlc ";
//echo $sql."<BR>";	
//exit();
 
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
//exit();					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_unpaid_common.php";
		
		$doc_id				= $row['id'];		
		$company_id			= $row['company_id'];
		$amount				= $row['payable_amount'];
		$utr_no				= $row['utr_no'];
		$module				= 'SI';
		$status				= $row['status'];
		if($status=='Completed'){
			$pending_with='';
		}
	
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
			mysqli_error($con);

		}
		//echo $sql."<BR>";	
	}
//Supplier Invoice End
//exit();
//Operating Expense Start
		$sql 	= " SELECT id, company_id, total_amount, exp_type, del, supp_id, payment_hdr_id , st_flag, utr_no 
				FROM (
					SELECT distinct(a.id), a.company_id,a.total_amount, a.exp_type, a.del, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
					FROM sma_travel_expenses a
					LEFT JOIN 
						( SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
						FROM sma_travel_expenses  a
						LEFT JOIN  payment_details b
						ON b.supp_id = a.id  
						LEFT JOIN  payment_header c
						ON b.payment_hdr_id = c.id where c.st_flag = 'C' and c.del !='Y' ) as DS 
						ON a.id = DS.supp_id  and ( DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
						and a.del !='Y' and a.status = 'Completed' 
						and a.dated >= '$start_date' and a.dated <= '$end_date'
					ORDER BY `a`.`id` ASC ) DS1 where 1 $sqlc ";
//echo $sql; exit();
	$result = mysqli_query($con, $sql); //
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_unpaid_common.php";
		
		$doc_id				= $row['id'];	
		$del				= $row['del'];
		if($doc_id == 38 || $del=='Y'){
			continue;
		}		
		$company_id			= $row['company_id'];
		
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
		//$module			= 'CE';
		$status				= $row['status'];
		$exp_type			= $row['exp_type'];
		if($exp_type=='C'){
			$module				= 'CE';
		}
		if($status=='Completed'){
			$pending_with='';
		}

		if(empty($utr_no) && $exp_type=='C' && $status=='Completed' ){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id', '$utr_no', '$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Operating Expense End
//exit();

//	echo "Process Over...";

//exit();

	$baseurl1= $baseurl."report/unpaid_msme_report_scheduler.php?sub=pdf&tmpv=$tmpv";
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

if($_GET['sub']=='pdf'){
	session_start();
	
	$modulePath = "report/";
	include("../dbcon.php");
	
	$comp_name = $_SESSION['compnamee'];
	$sql="SELECT module FROM `analysis_unpaid` where 1 group by  module  ";
	mysqli_query($con, $sql);
	$rowaffect = mysqli_affected_rows($con);
	
	$message = '';
	if($rowaffect==0){
		$message .= "<table >
					<tr>
						<th width='100%'>$comp_name</th>
						
					</tr>
					</table>";
		$message .= "<table >
					<tr>
						<th width='30%'>MSME Unpaid Report</th>
						<th width='40%'> Date :".date("d-m-Y")."</th>
						<th width='40%'>PVC=>Payment Voucher Creation</th>
					</tr>
					</table>";			
		$message .= "<table border='1' cellspacing='0' >
					<tr>
					   <th width='10%'>Doc.ID</th>
						<th width='10%'>Dated</th>
						<th width='10%'>Company</th>
						<th width='10%'>Type/Invoice No.</th>
						<th width='10%'>Invoice Date</th>
						<th width='15%'>Supplier Name</th>
						<th width='10%' style='text-align:right;'>Amount</th>
						<th width='15%'>Sent By</th>
						<th width='10%'>Pending From Days</th>
						<th width='10%'>Status</th>
						
					</tr></table>";
	}
//echo $message. "<BR>";
	
	$sql="SELECT module FROM `analysis_unpaid` where 1 group by  module  ";
	//$sql .= ' order by module ';
//echo $sql. "<BR>";
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);					
	while($row = mysqli_fetch_array($result)){
				
		$company_with		= $row['company_with'];
		$module		= $row['module'];
		if( $module_prev != $module ){
			
			if($module=='SI'){
				$module_nm = 'Supplier Invoice';
			}
			else if( $module=='CE'  ){
				$module_nm = 'Operating Expenses';
			}
			
			$sql="SELECT * FROM company where comp_id = '$company_with' ";		
			$res2 = mysqli_query($con, $sql);
			$mat = mysqli_fetch_array($res2);
			$comp_name 	= $mat['comp_name'];
				
			$message .= "<table >
                <tr >
                    <th colspan='2'>$comp_name</th>
					<th width='10%'>$module_nm</th>
					<th width='30%'>MSME Unpaid Report</th>
					<th width='30%'> Date :".date("d-m-Y")."</th>
					<th colspan='4'>PVC=>Payment Voucher Creation</th>
				</tr>
				</table>";
				
			$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;margin-left:0px;margin-top: 10px;' >
                <tr>
                   <th>Doc.ID</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type/Invoice No.</th>
					<th>Invoice Date</th>
					<th>Supplier Name</th>
					<th style='text-align:right;'>Amount</th>
					<th>Sent By</th>
					<th>Pending From Days</th>
					<th>Status</th>
					
				</tr>";
			}
			
			$module_prev		= $module;
			$pending_with_prev	= $pending_with;
			if($module=='PO'){
				$module_nm = 'Purchase Order';
				$order_by_comany = ', project';
			}	
			else if($module=='SI'){
				$module_nm = 'Supplier Invoice';
				
			}
			else if( $module=='CE'  ){
				$module_nm = 'Operating Expenses';
				
			}
			
			
			if($module=='SI'){
				$table_name = 'sma_supplier_invoice';
				$order_by_company = ', company_id';
			}
			else if( $module=='CE'  ){
				$table_name = 'sma_travel_expenses';
				$order_by_company = ', company_id';
			}
			
		$sql = "SELECT b.* FROM `analysis_unpaid` a, $table_name b where 1 and b.id = a.doc_id and a.module  = '$module' ";
		$sql .= ' order by pending_with, module '. $order_by_company;
//echo $sql."<BR>";		
		$rec = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($rw2 = mysqli_fetch_array($rec)){
			
			$doc_id 			= $rw2['id'];
			if($module=='AP'){				
					$dated = date('d-m-Y', strtotime($rw2['dated']));
					$overhead_exp		= $rw2['overhead_exp'];
					$company = $rw2['company'];
					$comon_id = $rw2['id'];
					$sql = "SELECT b.party_name, b.party_msme_number, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$comon_id' and b.id = a.supplier_name " ;
				
					$ij=0;
					$party_name  = '';
					$amount		 =0;
					$q2  		= mysqli_query($con, $sql);
					$raffect 	= mysqli_affected_rows($con);
					while($r2 	= mysqli_fetch_array($q2)){
								
						if($ij>0){$party_name.=', <BR> ' ;}
						$party_name  .= $r2['party_name'];
						$amount		 += $r2['values'];
						$party_msme_number	= $r2['party_msme_number'];
						if(empty($party_msme_number)){
							continue;
						}	
						$ij = $ij + 1;
		
					}		
					if($overhead_exp=='Y'){
						$ttype = "For Operating Expense";
					}
					else{
						$ttype = "For PO";
					}
			}
				
				if($module=='PO'){
					$comon_id = $rw2['id'];
					$company = $rw2['project'];
					$supplier_id = $rw2['to_supplier'];
					$purchase_id = $rw2['id'];
					$tot_amount = '0';
					$sql="SELECT * from sma_po_items where purchase_id = '$purchase_id' ";
					$res1 = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($r1 = mysqli_fetch_array($res1)){
						$qty 	= $r1['quantity'];
						$rate 	= $r1['unit_rate'];
						$gst	= $r1['gst'];
						$amount = round($qty * $rate + ((($qty * $rate) * $gst) / 100),0);
						$tot_amount = $tot_amount + $amount;
					}
					$amount = $tot_amount;
					$ttype  = $rw2['po_number'];
					
					$dated = date('d-m-Y', strtotime($rw2['dated']));
					
				}
				$invoice_date ='';
				if($module=='SI'){
					$comon_id 		= $rw2['id'];	
					$dated			= date('d-m-Y', strtotime($rw2['invoice_date']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['suplier_name'];
					$amount 	 	= $rw2['total_amount'];
					$company 		= $rw2['company_id'];
					
					$sql  = " SELECT * from sma_supplier_invoice where id = '$comon_id' ";
					$res  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res);
					$our_po_ref_no	= $r1['our_po_ref_no'];
					$invoice_date	= date('d-m-Y', strtotime($r1['invoice_date']));
					$dated 			= date('d-m-Y', strtotime($r1['created_date']));
					
					$sql  = " SELECT * from sma_purchase_order where advance_flag = 'Y' and id = '$our_po_ref_no' ";
					
					$res  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res);
					$advance_paid_amount	= $r1['paid_amount'];
					$total_po_amount		= $r1['total_po_amount'];
					$paid_status			= $r1['paid_status'];
					if($paid_status=='Paid'){
						continue;
					}
					
				}
				
				if($module=='PY'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['paid_to'];
					$st_flag 		= $rw2['st_flag'];
					$amount 	 	= $rw2['total_amount_paid'];
					$company 		= $rw2['company_id'];
				}
				
				if( $module=='CE'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= '';
					$supplier_id 	= $rw2['emp_id'];
					
					$company 		= $rw2['company_id'];
					
					if($module=='CE' ){
						$exptype = 'C';	
					}					
					$sql  = "SELECT sum(amount) as amount, sum(gst_amount) as gst_amount FROM `sma_expenses` where exp_type = '$exptype' and approval_ref_no = '$comon_id' ";
					$res1 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$amount			= $r1['amount'] + $r1['gst_amount'];
					
				}
				
				if($module=='PO' || $module=='SI' || $module=='CE'){
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name 	= $r2['party_name'];
					$party_type 	= $r2['party_type'];
					$party_msme_number = $rw['party_msme_number'];
					$party_msme_number	= $r2['party_msme_number'];
					if(empty($party_msme_number)){
						continue;
					}
					$sql 	= " select * from sma_type where type like '%MSME%' and id = '$party_type' " ;
					$q2 	= mysqli_query($con, $sql);
					$rowaffect = mysqli_affected_rows($con);
					$r2 	= mysqli_fetch_array($q2);
					$msme_id 	= $r2['id'];
					if($msme_id != $party_type ){
						continue;
					}
					
				}
				
				if($dated =='01-01-1970'){
					$dated ='';
				}
				
				$sql = "select * from company where comp_id = '$company' ";			
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$company_name  	= $r2['comp_name'];
				$company  		= $r2['comp_code'];
				
				$s1  = "SELECT * from workflow_history where doc_id = '$doc_id' and doc_type = '$module' and status in ('Submitted', 'Approved' ) order by id desc  ";
				$res2  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res2);
				$create_by		= $r1['create_by'];
				$create_date_prn= date('d-m-Y', strtotime($r1['create_date']));
				$create_date	= $r1['create_date'];
				
				// As Rajesh 29-01-2024
				$create_date	= date('Y-m-d', strtotime($dated));
				// As Rajesh 29-01-2024
				$today_date		= date('Y-m-d');
				$datediff		= strtotime($today_date) - strtotime($create_date);
				$days 			= round($datediff / (60 * 60 * 24)) + 1;					
									
				if($days < 15){
					continue;
				}
				
				
				$sl="SELECT * FROM sma_user where id = '$create_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$sent_by = $rw['username'];
			
				$status = '';
				if( $module=='CE' || $module=='SI' ){
					if ( $module=='CE' ){
						$stflag = 'C';
					}
					else if ( $module=='SI' ){
						$stflag = 'S';
					}	
					
					$sql = "SELECT a.* FROM `payment_header` a, payment_details b 
						WHERE a.id = b.payment_hdr_id and supp_id = '$comon_id' 
							and a.st_flag = '$stflag' and a.del !='Y'";
					$q2  			= mysqli_query($con, $sql);
					$py_raffect 	= mysqli_affected_rows($con);
					if($py_raffect==0){
						$status = 'PVC Pending';	
						if($comon_id==151){ 
							continue; 
						}

					}
					else {
						$sql = "SELECT a.* FROM `payment_header` a, payment_details b 
							WHERE a.id = b.payment_hdr_id and supp_id = '$comon_id' 
								and a.st_flag = '$stflag' and a.del !='Y' ";
						$qry2  			= mysqli_query($con, $sql);
						echo mysqli_error($con);					
						$rwa = mysqli_fetch_array($qry2);
						$utr_no 		= $rwa['utr_no'];
						if(empty($utr_no)){
							$status 		= 'UTR Blank';
						}
						else {
							continue;	
						}	
					}
				}
					
				$jj = $jj + 1;
				
			$message .="<tr>
				<td width='4%' >$doc_id</td>
				<td width='10%' >$dated</td>
				<td width='10%' style='text-align:left;' >$company</td>
				<td width='15%' style='text-align:left;'>$ttype</td>
				<td width='10%' >$invoice_date</td>
				<td width='18%' style='text-align:left;' >$party_name</td>
				<td width='10%' style='text-align:right;' >". number_format($amount,0) . "</td>
				<td width='15%' > $sent_by <BR> $create_date_prn</td>
				<td width='08%' > $days</td>
				<td width='10%' > $status</td>
				</tr> ";
		
		}	
		
		$message .="</table>";
		
	}	

$unpaid_rep = 'M';
//$message.="<BR><BR>";
//echo $message;
//exit('Exit Here...');

if(empty($_GET['tmpv'])){
	echo $message;
	include "unpaid_report_mail.php";
	echo '';
	
	echo "<script>window.close();</script>";

	exit(); 
		
}
else {

		require_once('../html2pdf/html2pdf.class.php');
			try
			{
				$fl_name = 'unpaid_msme_report_'.$company_id. '.pdf';
				$html2pdf = new HTML2PDF('P', 'A4', 'fr');
				$html2pdf->pdf->SetDisplayMode('fullpage');
				$html2pdf->setDefaultFont('freesans');
		//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
			   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
				$html2pdf->writeHTML($message);
				$html2pdf->Output($fl_name);
				
			}
			catch(HTML2PDF_exception $e) {
				echo $e;
				exit;
			}

//		include "unpaid_report_mail.php";

	}
		
}


/* 
if(empty($_GET['tmpv'])){
	include "unpaid_report_mail.php";
	echo '#####123';
} */



//http://athaang.in/report/unpaid_days_report_scheduler.php?sub=pro
?>


<?php
/* 
function moneyFormatIndiaaa($num){
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
} */

?>