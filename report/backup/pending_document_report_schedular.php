<?php

include("../dbcon.php");
include("../baseurl.php");

if($_GET['sub']=='pro'){

	$role			= $_SESSION['role'];
	$readonly		= $_SESSION['readonly'];
	$user_category	= $_SESSION['user_category'];
					
	$sql = "truncate analysis_pending";
	mysqli_query($con, $sql);
	
//Approval Memo	Start
	$sql 	= "SELECT * from sma_approval_memo where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'AP';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Approval Memo	End

//Purchase Order	Start
	$sql 	= "SELECT * from sma_purchase_order where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'PO';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Purchase Order	End

//Supplier Invoice	Start
	$sql 	= "SELECT * from sma_supplier_invoice where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'SI';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Supplier Invoice	End

//Company Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'C' and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'CE';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Company Expense	End

//Travel Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'T' and status = 'Submitted' and del!='Y'";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'TE';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}	
//Travel Expense	End

//Regular Expense	Start
	$sql 	= "SELECT * from sma_travel_expenses where exp_type = 'R' and status = 'Submitted' and del!='Y'";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'RE';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Regular Expense	End

//Payment Start
	$sql 	= "SELECT * from payment_header where id > 0 and status = 'Submitted' and del!='Y'";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'PY';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module','$doc_id','$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
	
//Payment End

//Petty Cash Start
	$sql 	= "SELECT * from sma_pettycash where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'PC';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module', '$doc_id', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Petty Cash End

//IPC Start
	$sql 	= "SELECT * from sma_ipc where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'IP';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module', '$doc_id', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//IPC End

//Travel Request Start
	$sql 	= "SELECT * from sma_traval_approval where id > 0 and status = 'Submitted' and del!='Y' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		
		include "analysis_pending_common.php";
				
		$doc_id			= $row['id'];		
		$module			= 'TA';
		$sql = "INSERT INTO analysis_pending (module, doc_id, pending_with, status) 
					VALUES ('$module', '$doc_id', '$pending_with', 'Submitted')";
		mysqli_query($con, $sql);
		
	}
//Travel Request End

	echo "Process Over...";

	$baseurl1= $baseurl."report/pending_document_report_schedular.php?sub=list";
	echo "<script>window.location.href='$baseurl1';</script>";

}
?>

<?php

if($_GET['sub']=='list'){
	
	include("../dbcon.php");
	
	require '../PHPMailer-master/PHPMailerAutoload.php';
		
	$module_prev 		= '';
	$pending_with_prev 	= '';
	
	$sql = "SELECT module, count(*) as mcnt FROM `analysis_pending` where 1 group BY module ASC";
//echo $sql;	
	$res = mysqli_query($con, $sql);
	echo mysqli_error($con);					
	while($rwa = mysqli_fetch_array($res)){
		$module 			= $rwa['module'];
//echo $module. "<<<>>";		
		//$pending_with		= $rwa['pending_with'];
		$mcnt				= $rwa['mcnt'];
		
		if(  $module_prev != $module ){
			
				if($module=='AP'){
					$module_nm = 'Approval Memo';
				}
				else if($module=='PO'){
					$module_nm = 'Purchase Order';
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
					$module_nm = 'Regular Expenses';
				}
				else if($module=='PY'){
					$module_nm = 'Payment';
				}
				else if($module=='PC'){
					$module_nm = 'Petty Cash';
				}
				else if($module=='IP'){
					$module_nm = 'IPC';
				}
				else if($module=='TA'){
					$module_nm = 'Traval Approval';
				}
			
			$message .= "<table >
                <tr >
                    <th colspan='6'>$module_nm</th>
					<th></th>
				</tr>
				</table>";
				
			$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12px;margin-left:0px;margin-top: 10px;'
                <tr style='background-color:#DDDAD8;'>
                   <th>Doc.ID</th>
                    <th>Dated</th>
					<th>Company</th>
					<th>Type/Invoice No.</th>
					<th>Supplier Name</th>
					<th style='text-align:right;'>Amount</th>
					<th>Sent By</th>
					<th>Pending From</th>
					<th> Days</th>
				</tr>";
		}
		
		$module_prev		= $module;
		
			if($module=='AP'){
				$table_name = 'sma_approval_memo';
			}
			else if($module=='PO'){
				$table_name = 'sma_purchase_order';
			}	
			else if($module=='SI'){
				$table_name = 'sma_supplier_invoice';
			}
			else if( $module=='CE' || $module=='TE' || $module=='RE'  ){
				$table_name = 'sma_travel_expenses';
			}
			else if($module=='PY'){
				$table_name = 'payment_header';
			}
			else if($module=='PC'){
				$table_name = 'sma_pettycash';
			}
			else if($module=='IP'){
				$table_name = 'sma_ipc';
			}
			else if($module=='TA'){
				$table_name = 'sma_traval_approval';
			}
			
		$sql = "SELECT b.*, a.pending_with FROM `analysis_pending` a, $table_name b where 1 and b.id = a.doc_id and a.module  = '$module' ";
		$sql .= ' order by module ';
//echo $sql."<BR>";		
//exit();
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($rw2 = mysqli_fetch_array($result)){
			
			$doc_id 			= $rw2['id'];
			$pending_with		= $rw2['pending_with'];
//echo $module. "<BR>";
			$st_flag ='';
			$paid_to ='';
			if($module=='AP'){				
					$dated = date('d-m-Y', strtotime($rw2['dated']));
					$overhead_exp		= $rw2['overhead_exp'];
					$company = $rw2['company'];
					$comon_id = $rw2['id'];
					$sql = "SELECT b.party_name, a.values FROM `sma_approval_details` a , `sma_party_mst` b where vendor_selected = 'Y' and approval_hdr_id = '$comon_id' and b.id = a.supplier_name " ;
//echo $sql."<BR>";					
					$ij			 = 0;
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
					
				}
				
				if($module=='PO'){
					$comon_id 		= $rw2['id'];
					$company 		= $rw2['project'];
					$supplier_id 	= $rw2['to_supplier'];
					$purchase_id 	= $rw2['id'];
					$tot_amount 	= '0';
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
				
				if( $module=='RE' || $module=='TE' || $module=='CE' || $module=='TA' ){
					$comon_id 		= $rw2['id'];	
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$ttype  	 	= '';
					$supplier_id 	= $rw2['emp_id'];
					if( $module=='RE' || $module=='TE' || $module=='TA' ){
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
				
				if($module=='PC' ){
					$comon_id 		= $rw2['id'];	
					$company 		= $rw2['company_id'];
					$amount 		= $rw2['total_amount'];
					$dated 			= date('d-m-Y', strtotime($rw2['dated']));
					$sql = "SELECT * FROM `sma_pettycash_exp` where approval_ref_no = '$comon_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					
					$paid_to  		= $r2['paid_to'];
					$supplier_id  	= $r2['spend_by'];
					$ttype		  	= $r2['invoice_no'];
				}
				
				if($module=='PO' || $module=='SI' || $module=='CE' || $module=='PY' || ( $module=='PC' && $paid_to=='V') ){
					$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
					$q2 	= mysqli_query($con, $sql);
					$r2 	= mysqli_fetch_array($q2);
					$party_name 	= $r2['party_name'];
				}
	
				if($st_flag =='A' || $st_flag =='T' || $module=='TA' || $module=='RE' || $module=='TE' || ( $module=='PC' && $paid_to=='U') ){	
					$sql = "SELECT * FROM `sma_user` where id = '$supplier_id' ";
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$party_name  = $r2['username'];
				}
				
				if($paid_to=='O'){
					$party_name  = $supplier_id;
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
				
			/* 	if($days==0 or $days > 1000){
					continue;
				} */
				
 				$sl="SELECT * FROM sma_user where id = '$create_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$sent_by = $rw['username'];
										
				$sql = "SELECT * FROM `sma_user` where id = '$pending_with' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$user_name  	= $r2['username'];					

				$jj = $jj + 1;
				
			$message .="<tr>
				<td width='4%' >$doc_id</td>
				<td width='10%' >$dated</td>
				<td width='10%' style='text-align:left;' >$company</td>
				<td width='15%' style='text-align:left;'>$ttype</td>
				<td width='20%' style='text-align:left;'  >$party_name</td>
				<td width='10%' style='text-align:right;' >". moneyFormatIndiaa($amount) . "</td>
				<td width='20%' > $sent_by <BR> $create_date_prn</td>
				<td width='10%' > $user_name</td>
				<td width='10%' > $days</td>
				</tr> ";
//echo $message."<>";
		
		}	
		
		$message .="</table>";


	}
//echo $message;
//exit('Exit Here....');
		
} 

			
			$user_name	= 'BillDesk';
			$user_email = 'billdesk@athaanginfra.in';
			
			include "pending_mail.php";
			
//echo $message;
//exit();

		echo "<script>window.close();</script>";	
		exit();
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



