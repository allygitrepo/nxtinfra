<?php
session_start();
include("../dbcon.php");
include("../baseurl.php");

if($_GET['sub']=='pro'){
	
//	$comid  = $_SESSION['comid'];
	
	$sql = "truncate analysis_unpaid";
	mysqli_query($con, $sql);
	
//Supplier Invoice Start
	$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_supplier_invoice a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_supplier_invoice  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'S' and c.del !='Y' ) as DS 
				ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
				and a.del !='Y' 
				ORDER BY `a`.`id` ASC";
//echo $sql."<BR>";	

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
//exit();					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
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
	
			$sql  = " SELECT * from sma_supplier_invoice where id = '$doc_id' and del !='Y' ";
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$our_po_ref_no	= $r1['our_po_ref_no'];
					
			$sql  = " SELECT * from sma_purchase_order where advance_flag = 'Y' and del !='Y' and id = '$our_po_ref_no' ";
					
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$advance_paid_amount	= $r1['paid_amount'];
			$total_po_amount		= $r1['total_po_amount'];
			$paid_status			= $r1['paid_status'];
			if($paid_status=='Paid'){
				continue;
			}
	
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
			mysqli_error($con);

		}
		
	}
//Supplier Invoice End

//Operating Expense Start
		$sql 	= " SELECT distinct(a.id), a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
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
				ORDER BY `a`.`id` ASC ";
//echo $sql; exit();
	$result = mysqli_query($con, $sql); //
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
		include "analysis_unpaid_common.php";
		
		$doc_id				= $row['id'];	
		$del				= $row['del'];	
		
		if($doc_id == 38 || $doc_id == 1298 ||  $doc_id == 760 ||  $doc_id == 1336 || $del =='Y'){
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
//Travel/Regular Expense Start
		$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_travel_expenses a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_travel_expenses  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'T' ) as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y' and a.status = 'Completed'
				ORDER BY `a`.`id` ASC ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
		include "analysis_unpaid_common.php";
		
		$doc_id				= $row['id'];		
		$company_id			= $row['company_id'];
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
		$exp_type			= $row['exp_type'];
		if($doc_id == 38 || $doc_id == 1298 ||  $doc_id == 760 ||  $doc_id == 1336 || $del =='Y'){
			continue;
		}	
		
		if($exp_type=='C'){
			continue;
		}	
		if($exp_type=='T'){
			$module				= 'TE';
		}
		if($exp_type=='R'){
			$module				= 'RE';
		}
		
		$status				= $row['status'];
		if($status=='Completed'){
			$pending_with = '';
		}
	
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Travel /Regular Expense End

	echo "Process Over...";

//exit();

	$baseurl1= $baseurl."report/unpaid_days_report_scheduler.php?sub=list";
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

?>


<?php

if($_GET['sub']=='list'){
		
//	include("../header.php");
	$modulePath = "report/";

	$userid   	= $_SESSION['usrid'];
	$help_code = $modulePath.'unpaid_days_report_scheduler.php';
	include "../help_code.php";

	$pgname = $help_code;
	include("../viewonly.php");
	
?>
       
<?php
	
	$sql="SELECT module FROM `analysis_unpaid` where 1 group by  module  ";
	//$sql .= ' order by module ';
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);					
	while($row = mysqli_fetch_array($result)){
				
		$module		= $row['module'];
		if( $module_prev != $module ){
			
			if($module=='SI'){
				$module_nm = 'Supplier Invoice';
			}
			else if( $module=='CE'  ){
				$module_nm = 'Operating Expenses';
			}
			else if( $module=='TE'  ){
				$module_nm = 'Travel Expenses';
			}
			else if( $module=='RE' ){
				$module_nm = 'Reimbursement';
			}
				
			$message .= "<table >
                <tr >
                    <th colspan='3'>$module_nm</th>
					<th width='30%'></th>
					<th width='30%'></th>
					<th colspan='4'>PVC=>Payment Voucher Creation</th>
				</tr>
				</table>";
				
			$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;margin-left:0px;margin-top: 10px;'
                <tr>
                   <th>Doc.ID</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type/Invoice No.</th>
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
			else if( $module=='TE'  ){
				$module_nm = 'Travel Expenses';
				
			}
			else if( $module=='RE' ){
				$module_nm = 'Reimbursement';
				
			}
			
			if($module=='SI'){
				$table_name = 'sma_supplier_invoice';
				$order_by_company = ', company_id';
			}
			else if( $module=='CE' || $module=='TE' || $module=='RE' ){
				$table_name = 'sma_travel_expenses';
				$order_by_company = ', company_id';
			}
			
		$sql = "SELECT b.* FROM `analysis_unpaid` a, $table_name b where 1 and b.del !='Y' and b.id = a.doc_id and a.module  = '$module' ";
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
					$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$comon_id' and b.id = a.supplier_name " ;
				
					$ij=0;
					$party_name  = '';
					$amount		 =0;
					$q2  		= mysqli_query($con, $sql);
					$raffect 	= mysqli_affected_rows($con);
					while($r2 	= mysqli_fetch_array($q2)){
								
						if($ij>0){$party_name.=', <BR> ' ;}
						$party_name  .= $r2['party_name'];
						$amount		 += $r2['values'];
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
				
				if($module=='SI'){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['invoice_date']));
					$ttype  	 	= $rw2['supplier_invoice_no'];
					$supplier_id 	= $rw2['suplier_name'];
					$amount 	 	= $rw2['total_amount'];
					$company 		= $rw2['company_id'];
					
					$sql  = " SELECT * from sma_supplier_invoice where id = '$comon_id' ";
					$res  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res);
					$our_po_ref_no	= $r1['our_po_ref_no'];
					$del					= $r1['del'];
					
					$sql  = " SELECT * from sma_purchase_order where advance_flag = 'Y' and id = '$our_po_ref_no' ";
					
					$res  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 = mysqli_fetch_array($res);
					$advance_paid_amount	= $r1['paid_amount'];
					$total_po_amount		= $r1['total_po_amount'];
					$paid_status			= $r1['paid_status'];
					
					if($paid_status=='Paid' || $del =='Y' ){
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
				
				if($module=='RE' || $module=='TE' || $module=='CE'){
					$comon_id 		= $rw2['id'];	
					if($comon_id == 38 || $comon_id == 1298 ||  $comon_id == 760 ||  $comon_id == 1336 ){
						continue;
					}	
					
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= '';
					$supplier_id 	= $rw2['emp_id'];
					if($module=='RE' || $module=='TE' ){
						$supplier_id 	= $rw2['onbehalf_emp_id']; 
					}
					$company 		= $rw2['company_id'];
					if($module=='RE' ){
						$exptype = 'R';	
					}
					if($module=='TE' ){
						$exptype = 'T';	
					}
					if($module=='CE' ){
						$exptype = 'C';	
					}					
					$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = '$exptype' and approval_ref_no = '$comon_id' ";
					$res1 	= mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r1 	= mysqli_fetch_array($res1);
					$amount			= $r1['amount'];
					
				}
				
				if($module=='PO' || $module=='SI' || $module=='CE'){
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name 	= $r2['party_name'];
				}
				else if($st_flag =='A' || $st_flag =='T' || $module=='RE' || $module=='TE' || $module=='PC' ){
					$sql = "SELECT * FROM `sma_user` where id = '$supplier_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$party_name  = $r2['username'];
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
				$today_date		= date('Y-m-d');
				$datediff		= strtotime($today_date) - strtotime($create_date);
				$days 			= round($datediff / (60 * 60 * 24)) + 1;					
									
				if($days < 10){
					continue;
				}
				
				
				$sl="SELECT * FROM sma_user where id = '$create_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$sent_by = $rw['username'];
			
				$status = '';
				if( $module=='RE' || $module=='TE' || $module=='CE' || $module=='SI' ){
					if( $module=='RE' || $module=='TE' ){
						$stflag = 'T';
					}
					else if ( $module=='CE' ){
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
					}
				}
					
				$jj = $jj + 1;
				
			$message .="<tr>
				<td width='4%' >$doc_id</td>
				<td width='10%' >$dated</td>
				<td width='10%' style='text-align:left;' >$company</td>
				<td width='15%' style='text-align:left;'>$ttype</td>
				<td width='18%' style='text-align:left;' >$party_name</td>
				<td width='10%' style='text-align:right;' >". moneyFormatIndiaa($amount) . "</td>
				<td width='15%' > $sent_by <BR> $create_date_prn</td>
				<td width='08%' > $days</td>
				<td width='10%' > $status</td>
				</tr> ";
		
		}	
		
		$message .="</table>";
		
	}	
	
} 

$unpaid_rep = 'Y';
$message.="<BR><BR>";
//echo $message;
//exit();
	
	include "unpaid_report_mail.php";
	echo "<script>window.close();</script>";	
exit();

//http://athaang.in/report/unpaid_days_report_scheduler.php?sub=pro
?>


<?php

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

?>